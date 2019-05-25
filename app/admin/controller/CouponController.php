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

use app\admin\model\CardModel;
use app\admin\model\shop\WorkerModel;
use cmf\controller\AdminBaseController;
use think\Db;
use app\admin\model\AdminMenuModel;
use app\admin\model\CouponModel;
use app\admin\model\ItemModel;
use think\Session;

class CouponController extends AdminBaseController
{
	protected $targets = ["_blank" => "新标签页打开", "_self" => "本窗口打开"];

    public function index()
    {
        $where = [];
        $except_role_id = [1,6,7,8];//不限制的门店管理员角色
        //查询shop_id,shop_code  限制默认显示的内容d
        $shop_id = Session::get('SHOP_ID');
        $admin_id = $this->admin_id;
        $nickname = trim($this->request->param('nickname',''));
        $mobile = trim($this->request->param('mobile',''));
        $shopId = trim($this->request->param('shop_id',''));
        $addTime = $this->request->param('add-time','');
        $checkTime = $this->request->param('check-time','');
        $couponStatus = $this->request->param('coupon-status','');
        $workerId = $this->request->param('worker-id');
        $role_id = Db::table('ddxm_role_user')->where('user_id','=',$admin_id)->value('role_id');
        if ($addTime) {
            $addTime_arr = explode('~',$addTime);
            $add_s = strtotime($addTime_arr[0]);
            $add_e = strtotime($addTime_arr[1])+24*3600;
            $where['c.addtime'] = ['between',[$add_s,$add_e]];
            $this->assign('add_s',$add_s);
            $this->assign('add_e',$add_e-24*3600+1);
        }
        if($checkTime){
            $checkTime_arr = explode('~',$checkTime);
            $check_s = strtotime($checkTime_arr[0]);
            $check_e = strtotime($checkTime_arr[1])+24*3600;
            $where['c.checktime'] = ['between',[$check_s,$check_e]];
            $this->assign('check_s',$check_s);
            $this->assign('check_e',$check_e-24*3600+1);
        }
        if ($couponStatus) {
            $where['c.status'] = $couponStatus;
            $this->assign('coupon_status',$couponStatus);
        }
        if ($workerId) {
            $where['c.worker_id'] = $workerId;
            $this->assign('worker_id',$workerId);
        }
        if(empty($shopId)){
            if($admin_id!=1&&!in_array($role_id,$except_role_id)){
                $where['c.shop_id'] = $shop_id;
                $this->assign('shop_id', $shop_id);
            }else if($admin_id==1||in_array($role_id,$except_role_id)){
                $this->assign('shop_id','');
                $shop_id = '';
            }
        }else{
            $where['c.shop_id'] = $shopId;
            $this->assign('shop_id', $shopId);
            $shop_id = $shopId;
        }
//        dump($shop_id);exit;
        //获取店铺信息(回去根据当前登录的用户来筛选店铺)
        if ($shop_id && $admin_id != 1){
            $shopDatas = Db::connect(config('ddxx'))->name('shop')->where('id',$shop_id)->field('id,name,code')->select();
            $workerDatas = Db::connect(config('ddxx'))->name('worker')->where('sid',$shop_id)->field('id,sid,workid,name')->select();
        }
        if (in_array($role_id,$except_role_id)) {
            $shopDatas = Db::connect(config('ddxx'))->name('shop')->field('id,name,code')->select();
            $workerDatas = Db::connect(config('ddxx'))->name('worker')->field('id,sid,workid,name')->select();
        }
        if($admin_id == 1){
            $shopDatas = Db::connect(config('ddxx'))->name('shop')->field('id,name,code')->select();
            $workerDatas = Db::connect(config('ddxx'))->name('worker')->field('id,sid,workid,name')->select();
        }
//        elseif (in_array($role_id,$except_role_id)) {
//            $shopDatas = Db::connect(config('ddxx'))->name('shop')->field('id,name,code')->select();
//            $workerDatas = Db::connect(config('ddxx'))->name('worker')->field('id,sid,workid,name')->select();
//        }else{
//            $shopDatas = Db::connect(config('ddxx'))->name('shop')->field('id,name,code')->select();
//            $workerDatas = Db::connect(config('ddxx'))->name('worker')->field('id,sid,workid,name')->select();
//        }
//        dump($shopId);die;
        $this->assign('shopDatas',$shopDatas);
        $this->assign('wokerDatas',$workerDatas);
        //搜索数据

        if ($nickname != null) {
            $where['m.nickname'] = ['like', "%$nickname%"];
        }

        if ($mobile != null) {
            $where['m.mobile'] = ['like', "%$mobile%"];
        }

        $where['ci.is_coup'] = ['=',1];
//        dump($where);exit;
        $pageParams = [
            'nickname' => $nickname,
            'mobile' => $mobile,
            'shop_id' => $shop_id,
            'add-time'=>$addTime,
            'check-time'=>$checkTime,
            'coupon-status'=>$couponStatus,
            'worker-id'=>$workerId
        ];
        $this->assign('nickname',$nickname);
        $this->assign('mobile',$mobile);
//        dump($where);exit;
        $data = (new CouponModel())->coupons($where)->paginate(10,false,['query'=>$pageParams]);
//        dump($data);exit;
        $data->each(function($item,$key){
            if($item['worker_id']){
                $woker_name = (new WorkerModel())->where(['workid'=>$item['worker_id']])->field('name')->find();
                $item['worker_name'] = $woker_name['name'];
            }else{
                $item['worker_name'] = '';
            }
            return $item;
        });
//        dump($data);exit;
        $this->assign('data',$data);
        //  分页样式
        $this->assign('page', $data->render());
        //  总条数
        $this->assign('totalCount', $data->total());
        //  当前页面
        $this->assign('current', $data->currentPage());
        //  每页显示数量
        $this->assign('listRows', $data->listRows());
        return $this->fetch('index');
    }

    public function getWorkers(){
        $id = input('post.shop_id');
        $worker_list = (new WorkerModel())->workerList(['b.id'=>$id])->select();
        if ($worker_list) {
            return outPut(1,'success',$worker_list);
        }else{
            return outPut(0,'该店铺没有员工信息，无法核销！');
        }
    }

    /**
     * 优惠券列表
     */
    public function coupon_list()
    {

        $Coupon = new CouponModel();
        $coupon_list = $Coupon->coupon_list();

        $page = $coupon_list->render();

        $this->assign("page", $page);
        $this->assign('coupon_list', $coupon_list);
        return $this->fetch();
    }
    /*
    优惠券删除
     */
    public function coupon_delete(){
        $couponid = $this->request->param("id", 0, 'intval');
        $Coupon = new CouponModel();
        $res = $Coupon->coupon_delete($couponid);
        if(!$res){
            $this->error($res);
        }else{
            $this->success("删除成功！", url('Card/getCouponList'));
        }
    }
    /*
    优惠券编辑
     */
    public function coupon_edit()
    {
        $couponid = $this->request->param("id", 0, 'intval');
        $couponinfo = (new CouponModel())->get_one($couponid);
        $card = new CardModel();
        $shop_list = $card->shop_list();
        $this->assign("shop_list", $shop_list);
        if (!$couponinfo) {
            $this->error("该优惠券不存在！");
        }
        $this->assign("data", $couponinfo);
        return $this->fetch();
    }
    /*
    优惠券编辑操作
     */
    public function coupon_editPost()
    {
        $catid = $this->request->param("id", 0, 'intval');

        if ($this->request->isPost()) {
            $data   = $this->request->param();

            $Item = new ItemModel();
            $res = $Item->type_editPost($catid,$data);

            if ($res==1) {
                $this->success("保存成功！",url('Item/type_list'));
            } else {
                $this->error("您没有做任何修改！");
            }
        }
    }
    /*
    优惠券添加
     */
    public function coupon_add(){
        $card = new CardModel();
        $shop_list = $card->shop_list();
        $this->assign("shop_list", $shop_list);
        return $this->fetch();
    }
    /*
    优惠券添加操作
     */
    public function couponAddPost(){

        if ($this->request->param()) {
            $data            = $this->request->param();
            $insert_data = [
                'title'=>$data['title'],
                'sid'=>$data['shop'],
                'price'=>$data['price'],
                'yxq'=>$data['can-use'],
                'xgnums'=>$data['limit-num'],
                'status'=>$data['status'],
                'thumb'=>$data['mainPic'][0],
                'content'=>htmlspecialchars_decode($data['coupon-intro']),
                'is_coup'=>1,
                'addtime'=>time()
            ];
            if(isset($data['id'])){
                $res = (new CardModel())->card_edit($data['id'],$insert_data);
            }else{
                $res = (new CardModel())->card_add($insert_data);
            }
            if ($res==1) {
                $this->success("保存成功！",url('Card/getCouponList'));
            } else {
                $this->error("保存失败！");
            }
        }
    }

    /**
     * 体验券核销
     */
    public function check(){
        $id = input('post.id');
        $worker_id = input('post.worker_id');
        if ((new CardModel())->check_user_coupon($id,$worker_id)) {
            return outPut(1,'核销成功');
        }else{
            return outPut(0,'核销失败');
        }
    }

}
