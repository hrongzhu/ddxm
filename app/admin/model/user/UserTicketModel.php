<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\admin\model\user;

use app\admin\model\BaseModel;
use app\admin\controller\LogsController;
use app\admin\model\order\OrderModel;
use app\admin\model\ticket\TicketModel;
use app\admin\model\user\IntegralModel;
use app\admin\model\CollectModel;
use app\admin\model\shop\WorkerModel;
use app\admin\model\shop\YuyueModel;
use app\admin\model\user\UserModel;
use think\Db;

class UserTicketModel extends BaseModel
{
    protected $table = 'tf_member_ticket';

    //获取用户代金券列表
    public function getUserVoucherTicketList($uid = 0)
	{
		return $this->alias('a')
            ->join('tf_ticket b','a.ticket_id = b.id','LEFT')
            ->join('tf_order d','a.get_order_id = d.id','LEFT')
            ->where(['b.type'=>1,'a.member_id'=>$uid,'a.status'=>['>',0]])
            ->field('a.id,a.ticket_id,b.cover,b.name,b.money,a.paytime,a.status,a.use_time,d.sn');
	}
    //获取用户服务券列表
    public function getUserServerTicketList($uid = 0)
    {
        return $this->alias('a')
            ->join('tf_ticket b','a.ticket_id = b.id','LEFT')
            ->join('tf_member c','a.member_id = c.id','LEFT')
            ->join('tf_service d','b.service_id = d.s_id','LEFT')
            ->join('tf_worker e','a.work_id = e.workid','LEFT')
            ->where(['b.type'=>2,'a.member_id'=>$uid,'a.status'=>['>',0]])
            ->field('a.id,a.ticket_id,d.sname,b.get_way,b.integral_price,b.name,c.nickname,c.mobile,a.paytime,e.name as worker_name,a.abolish_time,a.status');
    }

    //获取门店服务券列表
    public function getServerTicketList($where = [])
    {
        return $this->alias('a')
            ->join('tf_ticket b','a.ticket_id = b.id','LEFT')
            ->join('tf_member c','a.member_id = c.id','LEFT')
            ->join('tf_service d','b.service_id = d.s_id','LEFT')
            ->join('tf_worker e','a.work_id = e.workid','LEFT')
            ->join('tf_shop f','a.shop_id = f.id','LEFT')
            ->where($where)
            ->order('a.addtime desc')
            ->field('a.id,a.ticket_id,d.sname,b.get_way,b.integral_price,b.name,c.nickname,c.mobile,a.paytime,e.name as worker_name,a.abolish_time,a.status,f.name as shop_name');
    }

    //删除用户的券
    public function delMemberTicket($id = 0)
    {
        $msg= '删除会员的券,id是：'.$id;
        $res = $this->allowField(true)->save(['status'=>0],['id'=>$id]);
        if ($res) {
            LogsController::actionLogRecord($msg);
            return true;
        }
        return false;
    }

    //核销服务券
    public function checkUserTicket($id,$workid){
        $orderModel = new OrderModel();
        $ticketModel = new TicketModel();
        $yuyueModel = new YuyueModel();
        $this->startTrans();
        $orderModel->startTrans();
        $yuyueModel->startTrans();
        try {
            $member_ticket_info = $this->where(['id'=>$id])->field('get_order_id,ticket_id,shop_id,member_id,status')->find();
            if ($member_ticket_info['status'] == 2){
                $this->rollback();
                $orderModel->rollback();
                $yuyueModel->rollback();
                return ['code'=>301,'msg'=>'已经核销过了，勿重复操作！'];
            }
            $ticket_id = $member_ticket_info['ticket_id'];
            $ticket_info = $ticketModel->where(['id'=>$ticket_id])->field('get_way,integral_price,service_id')->find();
            $worker_id = (new WorkerModel)->where(['workid'=>$workid])->value('id');
            $order_data = [];
            $order_id = $member_ticket_info['get_order_id'];
            $order_sn = (new OrderModel())->where(['id'=>$order_id])->value('sn');
            $order_data['order_status'] = 2;
            $order_data['overtime'] = time();
            $msg = '给用户id为：'.$member_ticket_info['member_id'].'的用户核销服务券';
            $order_data['msg'] = $msg;
            $orderModel->allowField(true)->save($order_data,['id'=>$order_id]);
            $yuyue_data = [];
            $yuyue_data['member_id']    = $member_ticket_info['member_id'];
            $yuyue_data['workid']       = $workid;
            $yuyue_data['cardid']       = 0;
            $yuyue_data['workerid']     = $worker_id;
            $yuyue_data['sn']           = $order_sn;
            $yuyue_data['status']       = 1;
            $yuyue_data['sid']          = $member_ticket_info['shop_id'];
            $yuyue_data['order_id']     = $order_id;
            $yuyue_data['paytime']      = time();
            $yuyue_data['addtime']      = time();
            $yuyue_data['yytime']       = 0;
            $yuyue_data['name']         = time();
            $yuyue_data['type']         = $ticket_info['service_id']['id']?$ticket_info['service_id']['id']:0;
            $yuyue_data['num']          = 1;
            $yuyue_data['price']        = $ticket_info['integral_price']?$ticket_info['integral_price']:0;
            $yuyueModel->allowField(true)->save($yuyue_data);
            $mt_data = [];
            $mt_data['status']          = 2;
            $mt_data['work_id']         = $workid;
            $mt_data['abolish_time']    = time();
            $mt_data['use_order_id']    = $order_id;
            $mt_data['use_time']        = time();
            $mt_data['msg']             = $msg;
            $this->allowField(true)->save($mt_data,['id'=>$id]);
            if ($ticket_info['get_way']['status'] === 1) {
              $res = (new IntegralModel())->addIntegral($order_id);
              if (!$res) {
                $this->rollback();
                $orderModel->rollback();
                $yuyueModel->rollback();
                return ['code'=>301,'msg'=>'积分增加失败！'];
              }
            }
            $c_res = (new CollectModel())->countService($member_ticket_info['shop_id'],$ticket_info['service_id']['id'],$worker_id,1,0);
            if (!$c_res) {
                $this->rollback();
                $orderModel->rollback();
                $yuyueModel->rollback();
                return ['code'=>301,'msg'=>'销量增加失败！'];
            }
            $this->commit();
            $orderModel->commit();
            $yuyueModel->commit();
            return ['code'=>200,'msg'=>'核销成功'];
        } catch (\Exception $e) {
            $this->rollback();
            $orderModel->rollback();
            $yuyueModel->rollback();
            return ['code'=>301,'msg'=>'系统异常！'.$e->getMessage()];
        }
    }

    //添加券给用户
    /**
     * 兑换券
     * @param int $uid
     * @param int $ticket_id
     * @return array
     */
   public function addTicketToUser($uid = 0,$ticket_id = 0)
   {
       $orderModel = new OrderModel();
       $userModel = new UserModel();
       $db = Db::connect(config('ddxx'));
       $db->startTrans();
       try{
           $ticket_info =$db->name('ticket')->where('id','=',$ticket_id)->field('addtime,del,service_id',true)->find();
           $buy_count = $this->where(['member_id'=>$uid,'status'=>['>',0],'ticket_id'=>$ticket_id])->count('id');
           $user_info = $userModel->where(['id'=>$uid])->field('mobile,score_item,score_server')->find();
           $shop_id   = $userModel->getUserShopId($uid);
           if ($ticket_info['restrict_num'] >0 && $buy_count > $ticket_info['restrict_num']){
               return ['code'=>301,'msg'=>'已达到最大购买次数','data'=>''];
           }
           if ($ticket_info['circulation'] > 0 && $ticket_info['exchange_num'] == $ticket_info['circulation']){
               return ['code'=>301,'msg'=>'券卖完了','data'=>''];
           }
           if ($ticket_info['circulation'] > 0){
               $ticke_res = $db->name('ticket')->where(['id'=>$ticket_id])->setInc('exchange_num',1);
               if (!$ticke_res) {
                   return ['code'=>301,'msg'=>'券销量增加失败','data'=>''];
               }
           }
           $ticket_data = [];
           $ticket_data['shop_id']      = $shop_id;
           $ticket_data['member_id']    = $uid;
           $ticket_data['ticket_id']    = $ticket_id;
           $ticket_data['status']       = 1;
           $ticket_data['type']         = $ticket_info['type'];
           $expire_time = $ticket_info['expire_date'] > 0?time() + $ticket_info['expire_date'] * 3600 * 24:0;
           $ticket_data['expire_time']  = $expire_time;
           $ticket_data['paytime']      = time();
           $ticket_data['addtime']      = time();

           $order_data = [];
           $order_data['member_id'] = $uid;
           $order_data['type']      = 6;//兑换券（不会经过支付）
           $order_data['shop_id']   = $shop_id;
           $order_data['mobile']    = $user_info['mobile'];
           $order_data['pay_way']   = 9;//兑换
           $order_data['sn']        = time().rand(10,99).$shop_id;;
           $order_data['pay_sn']    = createPaySn();
           $order_data['pay_status'] = 1;
           $order_data['order_status'] = $ticket_info['type'] == 1?2:0;
           $order_data['addtime']   = time();
           $order_data['sendtime']  = time();
           $order_data['paytime']   = time();
           $order_data['overtime']  = time();
           $order_data['amount']    = 0;
           $order_data['integral_amount'] = 0;
           $orderModel->allowField(true)->save($order_data);
           $order_id = $orderModel->id;
           $ticket_data['get_order_id'] = $order_id;
           $this->allowField(true)->save($ticket_data);
           (new IntegralModel())->addIntegralRecord($order_id);
           $db->commit();
           return ['code'=>200,'msg'=>'添加成功','data'=>''];
       }catch (Exception $e){
           $db->rollback();
           return ['code'=>301,'msg'=>'系统异常','exp_msg'=>$e->getMessage(),'data'=>$e->getMessage()];
       }
   }

    //跟新订单的状态 默认改为完成
    public function updateOrderTicketStatus($order_id = 0,$status = 2)
    {
        $ticket_ids = (new OrderModel())->where(['id'=>$order_id])->value('ticket_ids');
        if(!empty($ticket_ids)){
            //批量修改状态为已使用 2
            $ticket_datas['status'] = $status;
            $ticket_datas['use_order_id'] = $order_id;
            $ticket_datas['use_time'] = time();
            $this->where('id','in',$ticket_ids)->update($ticket_datas);
        }
    }
}
