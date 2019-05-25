<?php
/**
 * Author: chenjing
 * Date: 2018/1/16
 * Description:
 */

namespace app\admin\controller;

use app\admin\model\ticket\TicketModel;
use app\admin\model\user\UserTicketModel;
use app\admin\model\shop\WorkerModel;
use app\admin\model\ServiceModel;
use app\admin\model\shop\ShopModel;
use app\admin\model\order\OrderModel;
use think\Session;
use think\Db;

class TicketController extends BaseController
{
    protected $pageLimit = 12;

    public function voucherList()
    {
        $name = $this->request->param('name');
        // $stime = $this->request->param('stime');
        // $etime = $this->request->param('etime');
        $status = $this->request->param('status');
        $shop_id = $this->request->param('shop_id');
        $where = [];
        if (!empty($name)) {
            $where['name'] = ['like',"%$name%"];
        }
        $where['type'] = 1;
        $where['del'] = 0;
        if (!empty($status)) {
            $where['status'] = $status;
        }
        if (!empty($shop_id)) {
            $where['shop_id'] = ['in',"$shop_id"];
        }
        $ticketModel = new TicketModel();
        $voucherlist = $ticketModel->getTicketList($where);
        $pageParams = [
            'type' => 1
        ];
        $datas = $this->lists($voucherlist, $pageParams)->toArray();
        $this->assign("file_host", config('file_server_url'));
        $this->assign("list", $datas['data']);
        return view('voucher_list');
    }

    public function serviceList()
    {
        $name = $this->request->param('name');
        // $stime = $this->request->param('stime');
        // $etime = $this->request->param('etime');
        $status = $this->request->param('status');
        $shop_id = $this->request->param('shop_id');
        $where = [];
        if (!empty($name)) {
            $where['name'] = ['like',"%$name%"];
        }
        $where['type'] = 2;
        $where['del'] = 0;
        if (!empty($status)) {
            $where['status'] = $status;
        }
        if (!empty($shop_id)) {
            $where['shop_id'] = ['in',"$shop_id"];
        }
        $ticketModel = new TicketModel();
        $serverlist = $ticketModel->getTicketList($where);
        $pageParams = [
            'type' => 2,
        ];
        $datas = $this->lists($serverlist, $pageParams)->toArray();
        $this->assign("file_host", config('file_server_url'));
        $this->assign("list", $datas['data']);
        return view('server_list');
    }

    public function ticketaddorupdate()
    {
        if ($this->request->isPost())
        {
            $param = $this->request->param();
            $data = $param['data'];
            $id = isset($data['id'])?$data['id']:0;
            if ($id){unset($data['id']);}
            $res= $this->voucherUpdate($data,$id);
            return outPut($res['code'],$res['msg']);
        }
        $id = $this->request->param('id');
        $type = $this->request->param('type');
        $shop_list = model('shop.Shop')->where(['status'=>1])->field('id,name')->select();
        $server_list = model('Service')->where(['status'=>1])->field('s_id as id,sname')->select();
        $this->assign('serverList',$server_list);
        $this->assign('shop_list',$shop_list);
        $this->assign('host',config('file_server_url'));
        if ($id){
            $info = (new TicketModel())->voucherInfo($id);
            $shop_ids = explode(',',$info['shop_id']);
            $this->assign('info',$info);
            $this->assign('shop_ids',$shop_ids);
            return view('ticket_update');
        }else{
            $this->assign('type',$type);
            return view('ticket_add');
        }
    }

    protected function voucherUpdate(array $data = [],$id = 0)
    {
        if (empty($data)){ return ['code'=>301,'msg'=>'参数错误'];}
        if (empty($data['name'])){ return ['code'=>301,'msg'=>'标题必须'];}
        if (empty($data['integral_price'])){ return ['code'=>301,'msg'=>'所需积分必须'];}
        if (!is_numeric($data['restrict_num'])){ $data['restrict_num'] = 0;}
        if (!is_numeric($data['expire_date'])){ $data['expire_date'] = 0;}
        if (!is_numeric($data['circulation'])){ $data['circulation'] = 0;}
        if ($data['type'] == 2){
            if (empty($data['get_way'])){ return ['code'=>301,'msg'=>'服务券获取方式必须'];}
            if (empty($data['service_id'])){ return ['code'=>301,'msg'=>'服务券服务项目必须'];}
        }else{
            if (empty($data['money'])){ return ['code'=>301,'msg'=>'金额必须'];}
        }
        $ticketModel = new TicketModel();
        $data['addtime'] = time();
        $data['shop_id'] = implode(',',$data['shop_id']);
        if ($id){
            $res = $ticketModel->updateDetail($data,$id);
        }else{
            $res = $ticketModel->updateDetail($data);
        }
        if ($res){
            return ['code'=>200,'msg'=>'操作成功'];
        }
        return ['code'=>301,'msg'=>'操作失败'];
    }

    /**
     * 删除券
     */
    public function delTicket()
    {
        $id = $this->request->param('ticket_id');
        $res = (new TicketModel())->allowField(true)->save(['del'=>1],['id'=>$id]);
        if ($res){
            return outPut(200,'删除成功');
        }
        return outPut(301,'删除失败');
    }

    //服务券待核销列表
    public function ServerTicketList()
    {
        $status = $this->request->param('status',1000);
        $SHOPID = $this->request->param('shop_id','');
        $name = $this->request->param('name','');
        $nickname = $this->request->param('nickname','');
        $mobile = $this->request->param('mobile','');
        $workid = $this->request->param('workid','');
        $service_id = $this->request->param('service_id',1000);
        $get_way = $this->request->param('get_way','');
        $except_role_id = [1,6,7];//不限制的门店管理员角色
        //获取店铺信息(回去根据当前登录的用户来筛选店铺)
        $admin_id = $this->admin_id;
        $role_id = Db::name('role_user')->where('user_id','=',$admin_id)->value('role_id');
        $shop_id = Session::get('SHOP_ID');
        if ($shop_id && $admin_id != 1){
            if (in_array($role_id,$except_role_id)) {
              $shopDatas = ShopModel::all(function($query){
                    $query->field('id,name,code');
                });
            }else{
                $w['id'] = ['in',$shop_id];
                $shopDatas = OrderModel::getShopList($w,1);
            }
        }else{
            $shopDatas = ShopModel::all(function($query){
                $query->field('id,name,code');
            });
        }
        $this->assign('shopDatas',$shopDatas);
        $where = [];
        $where['a.type'] = 2;
        if ($status != 1000 && $status != '') {
            $where['a.status'] = $status;
        }else{
            $where['a.status'] = ['>',0];
        }
        $this->assign('status',$status);
        if ($name != '') {
            $where['b.name'] = ['like',"%$name%"];
        }
        $this->assign('name',$name);
        if ($nickname != '') {
            $where['c.nickname'] = $nickname;
        }
        $this->assign('nickname',$nickname);
        if ($mobile != '') {
            $where['c.mobile'] = $mobile;
        }
        $this->assign('mobile',$mobile);
        if ($workid != '') {
            $where['a.work_id'] = $workid;
        }
        $this->assign('workid',$workid);
        if ($service_id != 1000 && $service_id != '') {
            $where['b.service_id'] = $service_id;
        }
        $this->assign('service_id',$service_id);
        if ($get_way != '') {
            $where['b.get_way'] = $get_way;
        }
        $this->assign('get_way',$get_way);
        $shop_id = Session::get('SHOP_ID');
        if ($SHOPID) {
            $shop_id = $SHOPID;
        }
        if ($shop_id == 18) {
            $shop_id = '';
        }else{
            $where['a.shop_id'] = ['in',$shop_id];
        }
        $this->assign('shop_id',$shop_id);
        $ticketModel = new UserTicketModel();
        $list = $ticketModel->getServerTicketList($where);
        //服务列表
        if ($admin_id == 1 || in_array($role_id,$except_role_id)) {
            $worker_list = (new WorkerModel())->workerList([])->select();
            $shop_server_list = (new ServiceModel())->field('s_id as id,sname')->select();
        }else{
            $worker_list = (new WorkerModel())->workerList(['a.sid'=>$shop_id])->select();
            $shop_service = (new ShopModel())->where(['id'=>$shop_id])->value('service_level_price');
            $jobs = array_keys(json_decode($shop_service,true));
            $shop_server_list = (new ServiceModel())->where(['s_id'=>['in',$jobs]])->field('s_id as id,sname')->select();
            $shop_server_list = !empty($shop_server_list)?collection($shop_server_list)->toArray():[];
        }
        //筛选条件
        $pageParams = [
            'status' =>$status,
            'shop_id' =>$SHOPID,
            'work_id' =>$workid,
            'name'    =>$name,
            'nickname' =>$nickname,
            'mobile' =>$mobile,
            'get_way' =>$get_way,
            'service_id' =>$service_id
        ];
        $datas = $this->lists($list,$pageParams)->toArray();
        $this->assign('list' , $datas['data']);
        $this->assign('worker_list',$worker_list);
        $this->assign('server_list',$shop_server_list);
        return view('server_ticket_list');
    }


    /**
     * 获取当前卡券上架的门店
     */
    public function getBindShopList()
    {
        $id = $this->request->param('id');
        $shop_ids = (new TicketModel())->where('id',$id)->value('shop_id');
        $shop_list = model('shop.Shop')->where(['id'=>['in',$shop_ids]])->field('name')->select();
        if ($shop_list){
            return outPut(200,'获取成功',$shop_list);
        }
        return outPut(301,'没有门店信息');
    }
}
