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
namespace app\admin\model;

use app\admin\model\user\UserModel;
use app\admin\controller\LogsController;
use think\Model;
use think\Cache;
use think\Db;

class CardModel extends BaseModel
{
    protected $tableName = 'service_card';
    protected $table = 'tf_service_card';
    protected $db = '';

    public function __construct()
    {
        $this->db = Db::connect(config('ddxx'));
    }

    protected function getAddtimeAttr($addtime)
    {
        return date('Y-m-d H:i:s',$addtime);
    }

    //获取购买的卡的列表
    public function getBuyServiceCardList($where)
    {
        return $this->db->name('member_card')->alias('b')
            ->join('tf_order o','b.order_id = o.id','LEFT')
            ->join('tf_member c','b.member_id = c.id','LEFT')
            ->join('tf_shop f','b.shop_id = f.id','LEFT')
            ->where($where)
            ->order('b.addtime desc')
            ->field('b.id,b.card_id,b.type,b.server_list,b.service_id,b.card_name,b.expire_month,b.money,c.nickname,c.mobile,b.paytime,b.active_time,b.status,f.name as shop_name,o.pay_way');
    }

    //更新或者添加数据
    public function updateDetail(array $data = [],$id= 0)
    {
        if ($id){
            $msg = '修改服务卡，id：'.$id;
            LogsController::actionLogRecord($msg);
            return $res = $this->allowField(true)->save($data,['id'=>$id]);
        }
        $msg = '添加服务卡';
        LogsController::actionLogRecord($msg);
        return $res = $this->allowField(true)->save($data);
    }

    //获取卡的服务记录
    public function getCardServerRecord($card_id = 0)
    {
        $service_list = $this->db->name('yuyue')->where('user_card_id',$card_id)->field('id,yytime,name,sid,type,order_id')->select();
        if (empty($service_list)) {
            return [];
        }
        foreach ($service_list as $k => $v) {
             $service_list[$k]['price'] = $this->db->name('order')->where('id',$v['order_id'])->value('amount');
            $service_list[$k]['shop_name'] = $this->db->name('shop')->where('id',$v['sid'])->value('name');
            $service_list[$k]['service_name'] = $this->db->name('service')->where('id',$v['type'])->value('sname');
            $service_list[$k]['yytime'] = date('Y-m-d H:i:s',$v['yytime']);
        }
        return $service_list;
    }

    //获取所有卡的服务记录
    public function getUsedCardServerRecord($where)
    {
        return $service_list = $this->db->name('member_card')->alias('d')
            ->join('yuyue a','d.id=a.user_card_id')
            ->join('order o','d.order_id=o.id')
            ->join('service b','a.type=b.id')
            ->join('shop c','a.sid=c.id')
            ->join('member m','a.member_id=m.id')
            ->where($where)
            ->field('d.id,d.type as card_type,d.card_name,a.yytime,a.name as work_name,a.name,b.sname,c.name as shop_name,m.nickname,m.mobile,o.pay_way,o.amount as price')
            ->order('a.yytime desc');
    }

    public function getCardServer($card_id = 0)
    {
        $service_id = $this->db->name('member_card')->where('id',$card_id)->value('service_id');
        if ($service_id) {
            return $this->db->name('service')->where('s_id','in',$service_id)->field('s_id,id,sname')->select();
        }
        return [];
    }

    /**
     * [banServiceCard 禁用服务卡]
     * @method banServiceCard
     * @param  integer        $user_card_id [用户服务卡id]
     * @return [type]                       [description]
     */
    public function banServiceCard($user_card_id = 0)
    {
        $res = $this->db->name('member_card')->where(['id'=>$user_card_id])->update(['status'=>3]);
        if ($res) {
            LogsController::actionLogRecord('禁用服务卡，卡id：'.$user_card_id);
            return true;
        }
        return false;
    }

    /**
     * [activeServiceCard 激活服务卡]
     * @method banServiceCard
     * @param  integer        $user_card_id [用户服务卡id]
     * @return [type]                       [description]
     */
    public function activeServiceCard($user_card_id = 0)
    {
        return $this->db->name('member_card')->where(['id'=>$user_card_id])->update(['status'=>2,'active_time'=>time()]);
    }

    /**
     * [CheckServiceCard 销服务卡]
     * @method CheckServiceCard
     * @param  array            $param [description]
     */
    public function CheckServiceCard($param = [])
    {
        //代码实现
        $service_id = $param['service_id'];
        $workid     = $param['workid'];
        $user_card_id = $param['id'];
        $card_info = $this->db->name('member_card')->where(['id'=>$user_card_id])->field('id,card_name,member_id,shop_id,money,type,service_id,status,active_time,server_list,expire_month')->find();
        if ($card_info['status'] == 3) {
            return ['code'=>301,'msg'=>'服务卡已失效','data'=>''];
        }
        $this->db->startTrans();
        try {
            $service_money = 0;
            if ($card_info['type'] == 1) {
                if ($card_info['status'] == 1) {
                    return ['code'=>301,'msg'=>'服务卡未激活','data'=>''];
                }
                $current_num = strtotime(date('m'),time()) - date('m',$card_info['active_time']);
                if ($current_num > $card_info['expire_month']){
                    $update_res = $this->db->name('member_card')->where(['id'=>$user_card_id])->update(['status'=>3]);
                    return ['code'=>301,'msg'=>'服务卡已过期失效','data'=>''];
                }
                if ($card_info['service_id']) {
                    $service_arr = explode(',', $card_info['service_id']);
                    if (!in_array($service_id, $service_arr)) {
                        return ['code'=>301,'msg'=>'服务卡未包含所选服务项目','data'=>''];
                    }
                    $service_money_arr = $this->db->name('service')->where('s_id',$service_id)->value('standard_price');
                    $service_money_arr = !empty($service_money_arr)?json_decode($service_money_arr,true):[];
                    $service_money     = !empty($service_money_arr)?$service_money_arr[1]:0;
                }else{
                    return ['code'=>301,'msg'=>'服务卡未包含服务项目','data'=>''];
                }
            }else{
                if (empty($card_info['server_list'])) {
                    return ['code'=>301,'msg'=>'服务卡未包含服务项目','data'=>''];
                }
                $server_arr = json_decode($card_info['server_list'],true);
                foreach ($server_arr as $k => $v) {
                    if ($v['id'] == $service_id) {
                        $service_count = $this->db->name('yuyue')->where(['type'=>$service_id,'member_id'=>$card_info['member_id'],'user_card_id'=>$user_card_id])->count();
                        if ($service_count >= $v['num']) {
                            $service_name = $this->db->name('service')->where(['s_id'=>$service_id])->value('sname');
                            return ['code'=>301,'msg'=>"当前卡的 $service_name 项目次数已使用完",'data'=>''];
                        }
                        $service_money = installmentAlgorithm($v['money'],$v['num'],'json',$service_count +1);
                    }
                }
            }
            $worker_id = $this->db->name('worker')->where(['workid'=>$workid])->value('id');
            $worker_name = $this->db->name('worker')->where(['workid'=>$workid])->value('name');
            //订单数据
            $order_data = [];
            $order_data['member_id']    = $card_info['member_id'];
            $order_data['shop_id']      = $card_info['shop_id'];
            $order_data['sn']           = $order_sn = time().mt_rand(10,99).$card_info['shop_id'];
            $order_data['pay_sn']       = createPaySn();
            $order_data['amount']       = $card_info['type'] == 1?0:$service_money;
            $order_data['old_amount']   = $service_money;
            $order_data['type']         = 2;
            $order_data['pay_status']   = 1;
            $order_data['pay_way']      = 3;
            $order_data['paytime']      = time();
            $order_data['order_status'] = 2;
            $order_data['overtime']     = time();
            $order_data['addtime']      = time();
            $order_data['is_online']    = 0;
            $order_data['waiter']       = $worker_name;
            $order_res = $this->db->name('order')->insertGetId($order_data);
            if (!$order_res) {
                $this->db->rollback();
                return ['code'=>301,'msg'=>"耗卡失败",'data'=>''];
            }
            $service_ids = $this->db->name('service')->where('s_id',$service_id)->value('id');
            // 预约数据
            $yuyue_data = [];
            $yuyue_data['member_id']    = $card_info['member_id'];
            $yuyue_data['workid']       = $workid;
            $yuyue_data['user_card_id'] = $user_card_id;
            $yuyue_data['workerid']     = $worker_id;
            $yuyue_data['sn']           = $order_sn;
            $yuyue_data['status']       = 1;
            $yuyue_data['sid']          = $card_info['shop_id'];
            $yuyue_data['order_id']     = $order_res;
            $yuyue_data['paytime']      = time();
            $yuyue_data['addtime']      = time();
            $yuyue_data['yytime']       = strtotime(date('Y-m-d H:i',time()));
            $yuyue_data['type']         = $service_ids;
            $yuyue_data['name']         = $worker_name;
            $yuyue_data['num']          = 1;
            $yuyue_data['price']        = $service_money;
            $yuyue_res = $this->db->name('yuyue')->insert($yuyue_data);
            if (!$yuyue_res) {
                $this->db->rollback();
                return ['code'=>301,'msg'=>'耗卡失败','data'=>''];
            }
            $c_res = (new CollectModel())->countService($card_info['shop_id'],$service_ids,$worker_id,1,0);
            if (!$c_res) {
                $this->db->rollback();
                return ['code'=>301,'msg'=>'服务销量增加失败！'];
            }
            if ($card_info['type'] == 2) {
                $server_arr = json_decode($card_info['server_list'],true);
                $server_new_arr = arrayFieldTransferArray($server_arr,'num');
                $card_all_num = 0;
                foreach ($server_new_arr as $vs) {
                    $card_all_num += $vs;
                }
                $use_all_num = 0;
                foreach ($server_arr as $v) {
                    $count = $this->db->name('yuyue')->where(['type'=>$v['id'],'member_id'=>$card_info['member_id'],'user_card_id'=>$user_card_id])->count();
                    $use_all_num += $count;
                }
                if ($use_all_num == $card_all_num) {
                    $this->db->name('member_card')->where(['id'=>$user_card_id])->update(['status'=>3]);
                }
            }
            $this->db->commit();
            return ['code'=>200,'msg'=>'耗卡成功','data'=>''];
        } catch (Exception $e) {
            $this->db->rollback();
            return ['code'=>301,'msg'=>'系统异常','data'=>$e->getMessage()];
        }
    }

    //根据服务获取门店
    public function getShopList($service_ids = [])
    {
        $temp_list = [];
        if (empty($service_ids)) {
            return $this->db->name('shop')->where('status',1)->field('id,name')->select();
        }
        $list = $this->db->name('shop')->where('status',1)->field('id,name,service_level_price')->select();
        $i = 0;
        foreach ($list as &$v) {
            $service_arr = !empty($v['service_level_price'])?json_decode($v['service_level_price'],true):[];
            $service_id_key = !empty($service_arr)?array_keys($service_arr):[];
            unset($v['service_level_price']);
            $intersect = array_intersect($service_ids, $service_id_key);
            if (count($intersect) == count($service_ids)) {
                $temp_list[$i] = $v;
                $i++;
            }
        }
        return $temp_list;
    }

    //-----------------------------------下面区域分开----------------------------
    /*
    商品卡购买列表
     */
    public function buycard(){

        $db = Db::connect(config('ddxx'));

        $buycard = $db
        ->name('card')
        ->join('tf_shop','tf_card.shop_id=tf_shop.id')
        ->join('tf_member','tf_card.member_id=tf_member.id')
        ->field("tf_card.id,tf_card.title,tf_card.price,FROM_UNIXTIME(tf_card.addtime,'%Y年%m月%d日 %H:%i:%s') as addtime,FROM_UNIXTIME(tf_card.paytime,'%Y年%m月%d日 %H:%i:%s') as paytime,tf_member.mobile,tf_member.nickname,tf_shop.name")
        ->where("paytime>0")
        ->order("id DESC")
        ->paginate(20);
        //print_r($type);die;

        return $buycard;
    }

    /**
     * @param string $condition 查询条件 all所有卡，official只查询非体验卡
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function get_card_list($condition='all'){
        $db = Db::connect(config('ddxx'));
        $card_list = $db->name('card')
            ->select();
        if($condition==''){
            return $card_list;
        }
        if($condition=='official'){
            $excpet_arr = ['熊猫银卡','熊猫金卡','熊猫白金卡','熊猫钻石卡',
                '熊猫至尊卡','熊猫普通卡','商品卡转熊猫卡 留云路','商品卡转熊猫卡  爱琴海',
                '商品卡转熊猫卡','银卡升级金卡','银卡享金卡折扣（开业活动）','北郡银卡享金卡折扣（5.11日新增卡）',
                '银行享受金卡折扣（开业活动）','银卡升级白金卡','银卡，充值5500，升级为白金卡','开业活动，银卡升级金卡（余额不变）',
                '次卡1214，充值1800，升级白金卡','5.26办银卡','普卡升银卡','金卡转白金卡'
            ];
            foreach ($card_list as $k=>$v){
                if (!in_array($v['title'],$excpet_arr)) {
                    unset($card_list[$k]);
                }
            }
            return $card_list;
        }
    }

    public function card_to_amount($lists){
        $db = Db::connect(config('ddxx'));
        foreach ($lists as $k=>$v){
            $bind_shop_info = $db->name('shop')->where('id',$v['shop_id'])->field('code')->find();
            $db->name('member')->where('id',$v['member_id'])->update(['shop_code'=>$bind_shop_info['code']]);
            $db->name('member')->where('id',$v['member_id'])->setInc('amount',$v['price']);
            $db->name('member')->where('id',$v['member_id'])->setInc('money',$v['my_num']);
            $db->name('card')->where('id',$v['id'])->update(['status'=>2]);
        }
    }

    //核销用户优惠券
    public function check_user_coupon($id,$worker_id){
        $orderModel = model('order.Order');
        $cardModel = model('user.UserCard');
        $cardModel->startTrans();
        $orderModel->startTrans();
        try {
            $cardinfo = $cardModel->where(['id'=>$id])->field('id,shop_id,title,card_id,member_id,price')->find();
            $order_data = [];
            $order_data['shop_id']      = $cardinfo->shop_id;
            $order_data['member_id']    = $cardinfo->member_id;
            $order_data['card_id']      = $cardinfo->id;
            $order_data['amount']       = $cardinfo->price;
            $order_data['paytime']      = time();
            $order_data['addtime']      = time();
            $order_data['pay_way']      = 3;//应该是余额这种的
            $order_data['order_status'] = 2;
            $order_data['is_online']    = 0;
            $order_data['type']         = 2;//体验券应该是服务的
            $order_data['sn']           = time().rand(10,99).$cardinfo->shop_id;
            $order_data['mobile']       = (new UserModel())->where(['id'=>$cardinfo->member_id])->value('mobile');
            $msg = '给用户id为：'.$cardinfo->member_id.'的用户核销体验券';
            $cardModel->allowField(true)->save(['msg'=>$msg,'status'=>2,'worker_id'=>$worker_id,'checktime'=>time()],['id'=>$id]);
            $orderModel->allowField(true)->save($order_data);
            (new CollectModel())->countService($cardinfo->shop_id,1,$worker_id,1,0);
            $cardModel->commit();
            $orderModel->commit();
            return true;
        } catch (\Exception $e) {
            $cardModel->rollback();
            $orderModel->rollback();
            // var_dump($e);
            return false;
        }
    }

}
