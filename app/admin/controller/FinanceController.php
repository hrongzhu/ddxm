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

use app\admin\model\ServiceModel;
use cmf\controller\AdminBaseController;
use think\Db;
use app\admin\model\AdminMenuModel;
use app\admin\model\FinanceModel;
use app\admin\model\SpecialModel;
use think\Session;

/**
 * Class cardController
 * @package app\admin\controller
 * @adminMenuRoot(
 *     'name'   =>'财务管理',
 *     'action' =>'menu_default',
 *     'parent' =>'',
 *     'display'=> true,
 *     'order'  => 10000,
 *     'icon'   =>'cogs',
 *     'remark' =>'财务管理'
 * )
 */
class FinanceController extends BaseController
{
	protected $targets = ["_blank" => "新标签页打开", "_self" => "本窗口打开"];

	protected  $pageLimit = 20;

    /**
     * 门店报表
     * @adminMenu(
     *     'name'   => '门店报表',
     *     'parent' => 'menu_default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '门店报表',
     *     'param'  => ''
     * )
     */
    public function finance_shop()
    {
        $time = $this->request->param('time');
        $sid = $this->request->param('shop_id');
        $is_del = 0;
        if($time==null){
            $from = strtotime(date("Y-m-d",time()));
            //$to   = strtotime(date("Y-m-d",time("+1 day")))-1;
            $to   = strtotime(date("Y-m-d",strtotime("+1 day")));
            $is_del = 1;
        }else{
            $froms = date("Y-m-d",strtotime($time));
            // if(date("Y-m-d",time())==$froms)
            // {
                $is_del = 1;
            // }
            $from  = strtotime($froms);
            $tos    = $froms." 23:59:59";
            $to    = strtotime($tos);
        }
        //echo $from.'     '.$to;die;
        $shop_id = Session::get('SHOP_ID');
        $id = $this->admin_id;

        $shopid  = explode(',', $shop_id);

        $Special = new SpecialModel();
        $shop_list = $Special->shoplist($id,$shop_id);

        $dian_id = $shop_list[0]['id'];

        if($sid!=null){
            $dian_id = $sid;
        }

        $Finance = new FinanceModel();
        $finance_shop = $Finance->finance_shop($dian_id,$from,$to);

        $times = $time;

        $this->assign("is_del",$is_del);
        $this->assign("dian_id",$dian_id);
        $this->assign("times",$times);
        $this->assign("shop_list", $shop_list);
        $this->assign('finance_shop', $finance_shop);
        return $this->fetch();
    }
    //店铺支出添加
    public function finance_add()
    {
        $date['shop_id'] = $this->request->param('shop_id');
        $date['content'] = $this->request->param('content');
		$date['price']   = $this->request->param('price');
        $date['cost_price']   = $this->request->param('price');
		$addtime         = $this->request->param('time');
        if ($addtime){
            if ($addtime == date('Y-m-d',time())) {
                $date['addtime'] = time();
            }else{
                $date['addtime'] = strtotime($addtime) + 10 * 3600;
            }
        }else{
			$date['addtime'] = time();
		}
        $date['type'] =    2;

        $Finance = new FinanceModel();

        $res     = $Finance->finance_add($date);

        if($res){
            return 1;
        }else{
            return 0;
        }
    }
    //店铺支出删除操作
    public function finance_delete()
    {
        $id = $this->request->param('id');

        $data['status'] = 0;

        $Finance = new FinanceModel();

        $res     = $Finance->finance_edit($id,$data);

        if($res){
            $this->success("删除成功！", url('Finance/finance_shop'));
        }else{
            $this->error("删除失败！");
        }
    }

    public function sale_report(){
        //搜索参数
        $db = Db::connect(config('ddxx'));
        $shop_id = Session::get('SHOP_ID');
        $except_role_id = [1,6,7,8];//不限制的门店管理员角色
        //如果登录的是加盟商呢??  此时只考虑独立门店(就是一个人一个店那种)
        //获取店铺信息(回去根据当前登录的用户来筛选店铺)
        $admin_id = $this->admin_id;
        $role_id = Db::name('role_user')->where('user_id', '=', $admin_id)->value('role_id');
        if (in_array($role_id,$except_role_id) || $admin_id == 1) {
            $shopDatas = $db->name('shop')->field('id,name,code')->select();
        }else{
            $shopDatas = $db->name('shop')->where('id','in',$shop_id)->field('id,name,code')->select();
        }
        $this->assign('shopDatas',$shopDatas);
        $item_name = $this->request->param('item_name');
        $shopId = trim($this->request->param('shop_id',''));
        $time = $this->request->param('time','');
        $is_online = trim($this->request->param('is_online'));
        $sale_way = $this->request->param('sale_way');
        //生成搜索条件
        $where = [];
        //店铺加上 如果是加盟商的话就是多个商铺 那么就用in查询
        if ($shop_id && $admin_id != 1) {
            if (!in_array($role_id, $except_role_id)) {
                $where['a.shop_id'] = ['in', $shop_id];//加盟商的情况
            }
        }
        if ($item_name != null) {
            $item_name = trim($item_name);
            $where['c.title'] = ['like', "%$item_name%"];
            $this->assign('item_name',$item_name);
        }
        if ($time) {
            $time_arr = explode('~',$time);
            $add_s = strtotime($time_arr[0]);
            $add_e = strtotime($time_arr[1])+24*3600-1;
            $where['a.update_time'] = ['between',[$add_s,$add_e]];
            $this->assign('add_s',$add_s);
            $this->assign('add_e',$add_e-24*3600+1);
        }
        if($is_online!=null){
            $where['a.is_online'] = $is_online;
            $this->assign('is_online',$is_online);
        }
        if(empty($shopId)){
            if($admin_id!=1&&!in_array($role_id,$except_role_id)){
                $where['a.shop_id'] = $shop_id;
                $this->assign('shop_id', $shop_id);
            }else if($admin_id==1||in_array($role_id,$except_role_id)){
                $this->assign('shop_id','');
                $shop_id = '';
            }
        }else{
            $where['a.shop_id'] = $shopId;
            $this->assign('shop_id', $shopId);
            $shop_id = $shopId;
        }
        if ($sale_way) {
            $where['a.sale_way'] = $sale_way;
            $this->assign('sale_way',$sale_way);
        }
        // //获取店铺信息(回去根据当前登录的用户来筛选店铺)
        // if ($shop_id && $admin_id != 1){
        //     $shopDatas = Db::connect(config('ddxx'))->name('shop')->where('id',$shop_id)->field('id,name,code')->select();
        // }
        // if (in_array($role_id,$except_role_id)) {
        //     $shopDatas = Db::connect(config('ddxx'))->name('shop')->field('id,name,code')->select();
        // }
        // if($admin_id == 1){
        //     $shopDatas = Db::connect(config('ddxx'))->name('shop')->field('id,name,code')->select();
        // }
        // $this->assign('shopDatas',$shopDatas);
        $list = (new FinanceModel())->get_sale_count($where);
        //分页参数
        $pageParams = [
            'item_name' => $item_name!= null ? $item_name: '',
            'time'=>$time!=null?$time:'',
            'shop_id' => $shop_id!=null?$shop_id:'',
            'is_online'=>$is_online!=null?$is_online:'',
            'sale_way' => $sale_way!= null ? $sale_way: '',
        ];
        $datas = $this->lists($list, $pageParams)->toArray();
        foreach ($datas['data'] as $key=>$value){
            $datas['data'][$key]['subtitle'] = str_replace(" ","&nbsp",$value['subtitle']);
        }
        $data = $datas['data'];
        $this->assign('datas', $data);
        $num_all = (new FinanceModel())->get_sale_all($where,'num');
        $refund_num_all = (new FinanceModel())->get_sale_all($where,'refund_num');
        $this->assign('num_all',$num_all);
        $this->assign('refund_num_all',$refund_num_all);
        return $this->fetch('sale_report');
    }


    public function service_sale_report()
    {
        //搜索参数
        $db = Db::connect(config('ddxx'));
        $shop_id = Session::get('SHOP_ID');
        $except_role_id = [1,6,7,8];//不限制的门店管理员角色
        //如果登录的是加盟商呢??  此时只考虑独立门店(就是一个人一个店那种)
        $admin_id = $this->admin_id;
        $role_id = Db::name('role_user')->where('user_id', '=', $admin_id)->value('role_id');
        //如果登录的是加盟商呢??  此时只考虑独立门店(就是一个人一个店那种)
        //获取店铺信息(回去根据当前登录的用户来筛选店铺)
        if (in_array($role_id,$except_role_id) || $admin_id == 1) {
            $shopDatas = $db->name('shop')->field('id,name,code')->select();
        }else{
            $shopDatas = $db->name('shop')->where('id','in',$shop_id)->field('id,name,code')->select();
        }
        $this->assign('shopDatas',$shopDatas);
        $woker_name = $this->request->param('worker_name');
        $shopId = trim($this->request->param('shop_id',''));
        $time = $this->request->param('time','');
        $is_online = trim($this->request->param('is_online'));
        $service_id = $this->request->param('service_id');
        //生成搜索条件
        $where = [];
        //店铺加上 如果是加盟商的话就是多个商铺 那么就用in查询
        if ($shop_id && $admin_id != 1) {
            if (!in_array($role_id, $except_role_id)) {
                $where['a.shop_id'] = ['in', $shop_id];//加盟商的情况
            }
        }
        if ($woker_name != null) {
            $where['d.name'] = ['like', "%$woker_name%"];
            $this->assign('worker_name',$woker_name);
        }
        if ($time) {
            $time_arr = explode('~',$time);
            $add_s = strtotime($time_arr[0]);
            $add_e = strtotime($time_arr[1])+24*3600-1;
            $where['a.update_time'] = ['between',[$add_s,$add_e]];
            $this->assign('add_s',$add_s);
            $this->assign('add_e',$add_e-24*3600+1);
        }
        if($is_online!=null){
            $where['a.is_online'] = $is_online;
            $this->assign('is_online',$is_online);
        }
        if(empty($shopId)){
            if($admin_id!=1&&!in_array($role_id,$except_role_id)){
                $where['a.shop_id'] = $shop_id;
                $this->assign('shop_id', $shop_id);
            }else if($admin_id==1||in_array($role_id,$except_role_id)){
                $this->assign('shop_id','');
                $shop_id = '';
            }
        }else{
            $where['a.shop_id'] = $shopId;
            $this->assign('shop_id', $shopId);
            $shop_id = $shopId;
        }
        if ($service_id!=null) {
            $where['a.service_id'] = $service_id;
            $this->assign('service_id',$service_id);
        }
        // //获取店铺信息(回去根据当前登录的用户来筛选店铺)
        // if ($shop_id && $admin_id != 1){
        //     $shopDatas = $db->name('shop')->where('id',$shop_id)->field('id,name,code')->select();
        // }
        // if (in_array($role_id,$except_role_id)) {
        //     $shopDatas = $db->name('shop')->field('id,name,code')->select();
        // }
        // if($admin_id == 1){
        //     $shopDatas = $db->name('shop')->field('id,name,code')->select();
        // }
        // $this->assign('shopDatas',$shopDatas);
        //获取所有服务
        $service_list = (new ServiceModel())->getServiceList(['status'=>1],'id,sname');
        $this->assign('service_list',$service_list);
        //查询服务销量数据
        $list = (new FinanceModel())->get_service_sale_count($where);
        //分页参数
        $pageParams = [
            'worker_name' => $woker_name!= null ? $woker_name: '',
            'time'=>$time!=null?$time:'',
            'shop_id' => $shop_id!=null?$shop_id:'',
            'is_online'=>$is_online!=null?$is_online:'',
            'service_id' => $service_id!= null ? $service_id: '',
        ];
        $datas = $this->lists($list, $pageParams)->toArray();
        $data = $datas['data'];
        $this->assign('datas', $data);
        $num_all = (new FinanceModel())->get_service_sale_all($where,'num');
        $refund_num_all = (new FinanceModel())->get_service_sale_all($where,'refund_num');
        $this->assign('num_all',$num_all);
        $this->assign('refund_num_all',$refund_num_all);
        return $this->fetch('service_sale_report');
    }

}
