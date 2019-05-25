<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 小夏 < 449134904@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\PayModel;
use app\admin\service\MessageService;
use cmf\controller\AdminBaseController;
use think\Db;
use app\admin\model\user\IntegralModel;
use app\admin\model\BespokeModel;
use think\Session;


/**
 * Class BespokeController
 * @package app\admin\controller
 * @adminMenuRoot(
 *     'name'   =>'预约管理',
 *     'action' =>'menu_default',
 *     'parent' =>'',
 *     'display'=> true,
 *     'order'  => 10000,
 *     'icon'   =>'cogs',
 *     'remark' =>'预约管理'
 * )
 */
class BespokeController extends AdminBaseController
{
	protected $targets = ["_blank" => "新标签页打开", "_self" => "本窗口打开"];




    /**
     * 预约列表
     * @adminMenu(
     *     'name'   => '预约列表',
     *     'parent' => 'menu_default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '预约列表',
     *     'param'  => ''
     * )
     */
    public function list()
    {
        //$shopid = $this->request->param('shopid');

        $id = $this->admin_id;
        $where = [];
        $s_where = [];
        $w_where = [];
        $shop_id = Session::get('SHOP_ID');
        $except_role_id = [1,6,7,8];//不限制的门店管理员角色
        $role_id = Db::name('role_user')->where('user_id','=',$id)->value('role_id');

        if ($id != 1) {
            $where['tf_yuyue.sid'] = ['in',$shop_id];
            $s_where['id'] = ['in',$shop_id];
        }
        if(!in_array($role_id,$except_role_id)&&$id!=1){
            $w_where['a.sid'] = $shop_id;
        }
        $search = $this->request->param('search');
        $is_pan = $this->request->param('is_pan');
        $shopId = $this->request->param('shop_id');
        $workid = trim($this->request->param('workid'));
        $serviceId = $this->request->param('service_id',-1);
        $status = $this->request->param('status',-10);
        $pay_way = $this->request->param('pay_way');
        if ($shopId) {
            $where['tf_yuyue.sid'] = ['in',$shopId];
        }
        if ($shopId) {
            $this->assign('shop_id', $shopId);
        }else{
            $this->assign('shop_id', $shop_id);
        }
        if ($workid) {
            $where['tf_yuyue.workerid'] = $workid;
            $this->assign('workid',$workid);
        }
        $workerlist = model('shop.Worker')->workerList($w_where)->select();
        $this->assign('workerlist', $workerlist);
        if($serviceId!=-1){
            $where['tf_yuyue.type'] = $serviceId;
            $this->assign('service_id',$serviceId);
        }
        if($status!=-10){
            $where['tf_yuyue.status'] = $status;
            $this->assign('status',$status);
        }
        if($pay_way){
            $where['tf_order.pay_way'] = $pay_way;
            $this->assign('pay_way',$pay_way);
        }
//        var_dump($where);exit;
        $serviceList = model('Service')->where(['status'=>1])->select();
        $this->assign('service_list',$serviceList);
        //echo $is_pan;
        if($is_pan==1){
            if($search){
                $where["tf_member.mobile"] = array('like',"%$search%");
            }
            // Session::set('yuyue',$search);
        }
        $Bespoke = new BespokeModel();
        $pageParams = [
            'search' => $search,
            'is_pan' => $is_pan,
            'shop_id' => $shopId,
            'workid'=>$workid,
            'service_id'=>$serviceId,
            'status'=>$status,
            'pay_way'=>$pay_way
        ];
//        var_dump($where);exit;
        $list = $Bespoke->list($where)->paginate(12, false, ['query'=>$pageParams]);;

        //  分页样式
        $this->assign('page', $list->render());
        //  总条数
        $this->assign('totalCount', $list->total());
        //  当前页面
        $this->assign('current', $list->currentPage());
        //  每页显示数量
        $this->assign('listRows', $list->listRows());
        $shoplist = Db::connect(config('ddxx'))->name('shop')->where(['status'=>1,'id'=>['not in','18']])->where($s_where)->field('id,name')->select();
        $this->assign('shoplist', $shoplist);
        $this->assign("search", $search);
        $this->assign('list', $list);
        return $this->fetch();
    }
    /*
    删除预约记录操作
     */
    public function delete()
    {
        $id = $this->request->param("id", 0, 'intval');

        $Bespoke = new BespokeModel();
        $res = $Bespoke->operation($id,'isdel','-1');

        if($res!=1){
            $this->error($res);
        }else{
            $this->success("删除成功！", url('Bespoke/list'));
        }
    }
    /*
    门店取消预约
     */
    public function cancel1(){

        $id = $this->request->param("id", 0, 'intval');

        $Bespoke = new BespokeModel();
        $res = $Bespoke->operation($id,'status','3');

        if($res!=1){
            $this->error($res);
        }else{
            $this->success("操作成功！", url('Bespoke/list'));
        }
    }
    public function cancel(){
        $id = $this->request->param("id", 0, 'intval');
        $db = Db::connect(config('ddxx'));
        $info = $db->table('tf_yuyue')->where(['id'=>$id])->field('member_id,sid,type,cardid,workid,workerid,order_id,isdel,status,yytime as ytime')->find();
        if ($info['isdel'] == 1){
            return ['code'=>301,'msg'=>'数据不存在'];
        }
        if ($info['status'] == 2){
            return ['code'=>301,'msg'=>'当前预约已过期,无需取消'];
        }
        if ($info['status'] == 3){
            return ['code'=>301,'msg'=>'当前预约已取消,无需重复取消'];
        }
        if ($info['order_id'] == 0) {
            $orderinfo = $db->table('tf_order')->where(['yy_id'=>$id])->field('sn,pay_sn,amount,pay_way')->find();
        }else{
            $orderinfo = $db->table('tf_order')->where(['id'=>$info['order_id'] ])->field('sn,pay_sn,amount,pay_way')->find();
        }
        $bespokeModel = new BespokeModel();
        Db::startTrans();
        try{
            //取消预约给员工通知
            $servicer_mobile = $db->table('tf_worker')->where(['workid'=>$info['workid']])->value('mobile');
            $shop_mobile = $db->table('tf_shop')->where(['id'=>$info['sid']])->value('telephone');
            $msg = "您在".date('m月d日 H:i',$info['ytime'])."-".date('H:i',$info['ytime'] + 3600)." 的预约已被取消，详情请查看排班记录。";
//            MessageService::sendMessageAll($servicer_mobile,$msg);
            if (!empty($orderinfo)){
                $token = get_access_token();
                $open_id = (new \app\admin\model\user\UserModel())->where(['id'=>$info['member_id']])->value('openid');
                if ($info['type'] == '0') {
                    $yy_name = '婴儿游泳';
                }elseif($info['type'] == '1'){
                    $yy_name = '小儿推拿';
                }else{
                    $yy_name = '成人推拿';
                }
                $first = '您好,您预约的'.$yy_name.'服务已成功取消';
                //预约的时间
                $yy_time = date('Y年m月d日 H时i分',$info['ytime']);
                //地点
                $shop_address = $db->table('tf_shop')->where('id','=',$info['sid'])->value('detail_address');
                $cancel_cause = '无';
                $remark = '温馨提示：您的预约取消成功，同时您支付的费用也原路退回到您的账户，请注意查看';
                /****************************************************************************************/
                $refund_data['order_sn'] = $orderinfo['sn'];
                $refund_data['transaction_id'] = $orderinfo['pay_sn'];
                //$refund_data['total_price'] = empty($orderinfo['amount'])?0:$orderinfo['amount'];
                $refund_data['total_price'] = 1;
                if ($orderinfo['pay_way'] == 1){
                    $result= (new PayModel())->wxRefund($refund_data);
                    if(($result['return_code']=='SUCCESS') && ($result['result_code']=='SUCCESS')){
                        $shop_money = $db->table('tf_shop')->where('id','=',$info['sid'])->value('amount');
                        $shop_money = $shop_money - $orderinfo['amount'];
                        $db->table('tf_shop')
                            ->where(['id'=>$info['sid']])
                            ->update(['amount'=>$shop_money]);
                        $bespokeModel->yyTotemp($open_id,$token,$first,$yy_name,$yy_time,$shop_address,$cancel_cause,$remark);
                    }else if(($result['return_code']=='FAIL') || ($result['result_code']=='FAIL')){
                        $this->error('操作失败');
                        //退款失败
                    }else{
                        $this->error('操作失败');
                        //失败
                    }
                }elseif($orderinfo['pay_way'] == 3){
                    $user_money = $db->table('tf_member')->where(['id'=>$info['member_id']])->value('money');
                    $amounts = $user_money + $orderinfo['amount'];
                    $u_res = $db->table('tf_member')
                        ->where(['id'=>$info['member_id']])
                        ->update(['money'=>$amounts]);
                    //退款成功
                    if ($u_res) {
                        $bespokeModel->yyTotemp($open_id,$token,$first,$yy_name,$yy_time,$shop_address,$cancel_cause,$remark);
                    }
                }
            }
            Db::commit();
            $res = $db->table('tf_yuyue')->where(['id'=>$id])->update(['status'=>3]);
            $order_res = $db->name('order')->where(['id'=>$info['order_id']])->update(['status'=>-7]);
            if ($res && $order_res != false) {
                $this->success("操作成功！", url('Bespoke/list'));
            }
            $this->error('操作失败');
        }catch (Exception $e){
            Db::rollback();
            // dump($e->getMessage());exit;
            $this->error('操作失败');
        }
    }
    /*
    门店完成页面
     */
    public function complpage(){

        $id = $this->admin_id;
        $shop_id = Session::get('SHOP_ID');
        $yuyueid = $this->request->param("id", 0, 'intval');
        $workerid = $this->request->param("workerid", 0, 'intval');

        $Bespoke = new BespokeModel();

        $res = $Bespoke->waiterlist($id,$shop_id);

        $this->assign("workerid", $workerid);
        $this->assign("yuyueid", $yuyueid);
        $this->assign("waiterlist", $res);
        return $this->fetch();

    }
    /*
    门店完成预约
     */
    public function complete(){
        $id = $this->request->param("id", 0, 'intval');

        $Bespoke = new BespokeModel();
        $res = $Bespoke->operation($id,'status','1');

        if($res!=1){
            $this->error($res);
        }else{

            $this->success("操作成功！", url('Bespoke/list'));
        }
    }
    /*
    服务人员修改
     */
    public function waiter(){

        $waiterid = $this->request->param('waiterid');
        $yuyueid   = $this->request->param('yuyueid');
        $Bespoke = new BespokeModel();

        $db = Db::connect(config('ddxx'));
        $order_id = $db->name('order')->where(['yy_id'=>$yuyueid])->value('id');
        $db->startTrans();
        try {
            $besfind = $Bespoke->bes_find($waiterid);
            $yuyue = $Bespoke->yuyue_find($yuyueid);
            if($yuyue['workerid'] == $waiterid){
                $Bespoke = new BespokeModel();
                $res = $Bespoke->operation($yuyueid,'status','1');
                $ress = (new IntegralModel())->addIntegral($order_id);
                if (!$ress) {
                    $db->rollback();
                    $this->error($res);
                }else{
                    $db->commit();
                    echo 1;
                }

            }else{

                $shop_id = Session::get('SHOP_ID');
                $adminid = $this->admin_id;
                //print_r($besfind);die;
                $date['name']     = $besfind['name'];
                $date['workerid'] = $besfind['id'];
                $date['workid']   = $besfind['workid'];
                $Bespoke->waiter($yuyueid,$date);
                $res = $Bespoke->operation($yuyueid,'status','1');

                if($res!=1){
                    $db->rollback();
                    $this->error($res);
                }else{
                    $ress = (new IntegralModel())->addIntegral($order_id);
                    if (!$ress) {
                        $db->rollback();
                        $this->error($res);
                    }else{
                        $db->commit();
                        echo 1;
                    }
                }
            }
        } catch (Exception $e) {
            $db->rollback();
            $this->error($res);
        }

    }
}
