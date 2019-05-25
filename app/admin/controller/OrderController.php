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

use app\admin\model\AdminUserModel;
use app\admin\model\ItemModel;
use app\admin\model\order\OrderExpressModel;
use app\admin\model\order\OrderGoodsModel;
use app\admin\model\order\OrderLogsModel;
use app\admin\model\order\OrderModel;
use app\admin\model\order\OrderRefundModel;
use app\admin\model\order\TestModel;
use app\admin\model\PayModel;
// use app\admin\model\shop\ShopModel;
use app\admin\model\user\UserModel;
use think\Cookie;
use think\Db;
use \think\Request;
use think\Session;
use traits\think\Instance;

/**
 * Class OrderController
 * @package app\admin\controller
 * @adminMenuRoot(
 *     'name'   =>'订单模块',
 *     'action' =>'menuDefault',
 *     'parent' =>'',
 *     'display'=> true,
 *     'order'  => 10000,
 *     'icon'   =>'cogs',
 *     'remark' =>'订单模块'
 * )
 */
class OrderController extends BaseController
{
    protected $pageLimit = 12;
    protected $otherdb = [];
    protected $templatePrefix = 'order/';

    /**
     * 订单列表
     * @adminMenu(
     *     'name'   => '订单列表',
     *     'parent' => 'menuDefault',
     *     'display'=> true,
     *       'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '订单列表',
     *     'param'  => ''
     * )
     */
    public function orderList()
    {
        //搜索参数
        // $shopInfo = (new AdminUserModel())->getAdminUserBasicInfo($this->admin_id,'shop_id');
        $shop_id = Session::get('SHOP_ID');
        $except_role_id = [1, 6,7,9];//不限制的门店管理员角色
        //如果登录的是加盟商呢??  此时只考虑独立门店(就是一个人一个店那种)
        //获取店铺信息(回去根据当前登录的用户来筛选店铺)
        $admin_id = $this->admin_id;
        $role_id = Db::name('role_user')->where('user_id', '=', $admin_id)->value('role_id');
        //获取店铺信息(回去根据当前登录的用户来筛选店铺)
        if (in_array($role_id,$except_role_id) || $admin_id == 1) {
            $shopDatas = Db::connect(config('ddxx'))->name('shop')->field('id,name,code')->select();
        }else{
            $shopDatas = Db::connect(config('ddxx'))->name('shop')->where(['id'=>['in',$shop_id]])->field('id,name,code')->select();
        }
        $this->assign('shopDatas',$shopDatas);
        $this->assign('admin_id', $admin_id);
        $sn = $this->request->param('sn');
        $subtitle = trim($this->request->param('subtitle'));
        $nickname = trim($this->request->param('nickname'));
        $mobile   = trim($this->request->param('mobile'));
        $payStatus = trim($this->request->param('pay_status', -1000));
        $shopId = trim($this->request->param('shop_id',''));
        $pay_way = trim($this->request->param('pay_way'));
        $show = 0;
        //生成搜索条件
        $where = [];
        //店铺加上 如果是加盟商的话就是多个商铺 那么就用in查询
        if ($shop_id && $admin_id != 1) {
            if (!in_array($role_id, $except_role_id)) {
                $where['a.shop_id'] = ['in', $shop_id];//加盟商的情况
            }
        }
        if (in_array($role_id, $except_role_id) || $admin_id == 1) {
            $show = 1;
        }
        $this->assign('show', $show);
        if ($sn != null) {
            $where['a.sn'] = ['like', "%$sn%"];
        }

        if ($subtitle != null) {
            $where['b.subtitle'] = ['like', "%$subtitle%"];
        }

        if ($nickname != null) {
            $where['c.nickname'] = ['like', "%$nickname%"];
        }
        if ($mobile != null) {
            $where['a.mobile'] = ['like', "%$mobile%"];
        }
        // $this->assign('mobile', $mobile);
        if ($payStatus != null && $payStatus != -1000) {
            $where['a.pay_status'] = ['=', $payStatus];
        }
        $this->assign('pay_status', $payStatus);

        if ($pay_way) {
            $where['a.pay_way'] = $pay_way;
            $where['a.pay_status'] = 1;
        }
        $this->assign('pay_way',$pay_way);

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
        //订单状态
        $orderStatus = $this->request->param('order_status/d', -100);

        if ($orderStatus !== null && $orderStatus != -100) {
            $where['a.pay_status'] = ['=', 1];
            if ($orderStatus == 1000) {
                //完成状态单独判断
                $where['a.order_status'] = 2;
            } elseif ($orderStatus == -7) {
                $where['a.order_status'] = ['in',[-1,-2,-7]];
            } else {
                $where['a.order_status'] = ['=', $orderStatus];
            }
        }
        $this->assign('order_status', $orderStatus);

        $orderListDatas = (new OrderModel())->getOrderListDatas($where);

        //分页参数
        $pageParams = [
            'sn' => $sn != null ? $sn : '',
            'subtitle' => $subtitle != null ? $subtitle : '',
            'nickname' => $nickname != null ? $nickname : '',
            'pay_way' => $pay_way != null ? $pay_way : '',
            'pay_status' => $payStatus != null ? $payStatus : '',
            'shop_id' => $shop_id!=null?$shop_id:'',
            'order_status' => $orderStatus != null ? $orderStatus : ''
        ];

        $datas = $this->lists($orderListDatas, $pageParams)->toArray();

        $order_data = $datas['data'];

        foreach ($order_data as $k => $v) {
            $order_data[$k]['subtitle'] = (new OrderModel())->getGoodsName($v['id']);
            $order_data[$k]['level_name'] = model('Level')->where(['id'=>$v['level_id']])->value('level_name');
            $order_data[$k]['sum_cost'] = (new OrderModel())->getOrderSumCostPrice($v['id'],1);
            $order_data[$k]['faker_cost'] = (new OrderModel())->getOrderSumCostPrice($v['id'],2);
        }

        $this->assign('datas', $order_data);

        return $this->fetch('order_list');
    }

    /**
     * 充值订单列表
     * @adminMenu(
     *     'name'   => '充值订单列表',
     *     'parent' => 'menuDefault',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '充值订单列表',
     *     'param'  => ''
     * )
     */
    public function rechargeOrderList()
    {
        $db = Db::connect(config('ddxx'));
        //搜索参数
        $shop_id = Session::get('SHOP_ID');
        $except_role_id = [1,6,7,8];//不限制的门店管理员角色
        //如果登录的是加盟商呢??  此时只考虑独立门店(就是一个人一个店那种)
        //获取店铺信息(回去根据当前登录的用户来筛选店铺)
        $admin_id = $this->admin_id;
        $role_id = Db::name('role_user')->where('user_id', '=', $admin_id)->value('role_id');
        //获取店铺信息(回去根据当前登录的用户来筛选店铺)
        if (in_array($role_id,$except_role_id) || $admin_id == 1) {
            $shopDatas = $db->name('shop')->field('id,name,code')->select();
        }else{
            $shopDatas = $db->name('shop')->where('id','in',$shop_id)->field('id,name,code')->select();
        }
        $this->assign('shopDatas',$shopDatas);
        $this->assign('admin_id', $admin_id);
        $sn = $this->request->param('sn');
        $shopId = trim($this->request->param('shop_id',''));
        $time = $this->request->param('time','');
        $keywords = $this->request->param('keywords','');
        $pay_way = trim($this->request->param('pay_way'));
        $check_status = $this->request->param('check_status','');
        //生成搜索条件
        $where = [];
        $where['a.paytime'] = ['>', '1527609600'];
        //店铺加上 如果是加盟商的话就是多个商铺 那么就用in查询
        if ($shop_id && $admin_id != 1) {
            if (!in_array($role_id, $except_role_id)) {
                $where['a.shop_id'] = ['in', $shop_id];//加盟商的情况
            }
        }
        if($keywords!=null){
            $where['c.mobile|c.nickname'] = ['like',"%$keywords%"];
            $this->assign('keywords',$keywords);
        }
        if ($sn != null) {
            $where['a.sn'] = ['like', "%$sn%"];
            $this->assign('sn',$sn
            );
        }
        if ($time) {
            $time_arr = explode('~',$time);
            $add_s = strtotime($time_arr[0]);
            $add_e = strtotime($time_arr[1])+24*3600;
            $where['a.paytime'] = ['between',[$add_s,$add_e]];
            $this->assign('add_s',$add_s);
            $this->assign('add_e',$add_e-24*3600+1);
        }
        if($check_status!=null){
            $where['a.check_status'] = $check_status;
            $this->assign('check_status',$check_status);
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
        if ($pay_way) {
            $where['a.pay_way'] = $pay_way;
        }
        $this->assign('pay_way',$pay_way);
        $total = (new OrderModel())->getRechargeOrderListDatas($where)->count();
        $this->assign('total',$total);
        $total_money = (new OrderModel())->getSumAmount($where);
        $this->assign('total_money',$total_money);
        $orderListDatas = (new OrderModel())->getRechargeOrderListDatas($where);
        //分页参数
        $pageParams = [
            'sn' => $sn != null ? $sn : '',
            'time'=>$time!=null?$time:'',
            'shop_id' => $shop_id!=null?$shop_id:'',
            'keywords'=>$keywords!=null?$keywords:'',
            'pay_way' => $pay_way != null ? $pay_way : '',
            'check_status'=>$check_status!=null?$check_status:''
        ];

        $datas = $this->lists($orderListDatas, $pageParams)->toArray();
        foreach ($datas['data'] as $k => &$v) {
            $v['edit_price'] = 0;
            $v['edit_remark'] = '无';
            $exsit = $db->name('member_card')->where(['order_id'=>$v['id']])->field('card_name,buy_remark,old_money')->find();
            if ($exsit) {
            	unset($datas['data'][$k]);
            	continue;
                $v['subtitle'] = '服务卡';
                $v['old_amount'] = $exsit['old_money'];
                if ($exsit['old_money'] != $v['amount']) {
                    $v['edit_price'] = 1;
                    $v['edit_remark'] = $exsit['buy_remark'];
                }
            } else {
                $v['subtitle'] = '充值';
                $v['old_amount'] = '无';
            }
        }
        $order_data = $datas['data'];
        $this->assign('datas', $order_data);

        return $this->fetch('recharge_order_list');
    }

    public function batchCheck()
    {
        if (empty(Session::get('name'))) {
            $uid = Session::get('ADMIN_ID');
            $users = (new AdminUserModel())->getAdminUserBasicInfo($uid, 'user_login');
        }
        $name = Session::get('name') ? Session::get('name') : $users['user_login'];//管理员名字
        $ids = json_decode(htmlspecialchars_decode(input('post.id')));
        // var_dump($ids);die;
        $res = (new OrderModel())->checkOrder($name,$ids);
        if($res){
            return outPut(1,'success');
        }else{
            return outPut(0,'fail');
        }
    }

    /**
     * 订单详情页
     * @adminMenu(
     *     'name'   => '订单详情页',
     *     'parent' => 'menuDefault',
     *     'display'=> false,
     *       'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '订单详情页',
     *     'param'  => ''
     * )
     */
    public function detail()
    {
        $id = $this->request->param('id');
        $detailInfo = (new OrderModel())->getDetailData($id);
        // dump($detailInfo);die;
        $orderLogs = (new OrderLogsModel())->getOrderLogs($id);
        $except_role_id = [1,6];
        $admin_id = $this->admin_id;
        $role_id = Db::name('role_user')->where('user_id','=',$admin_id)->value('role_id');
        if (in_array($role_id,$except_role_id) || $admin_id == 1) {
            $this->assign('is_show',1);
        }
        $this->assign('detailInfo', $detailInfo);
        $this->assign('orderlogs', $orderLogs);
//        dump($detailInfo);die;
        return $this->fetch('detail');
    }

    /**
     * 订单删除
     * @adminMenu(
     *     'name'   => '订单删除',
     *     'parent' => 'menuDefault',
     *     'display'=> false,
     *       'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '订单删除',
     *     'param'  => ''
     * )
     */
    public function delete()
    {
        $id = $this->request->param('id');

        $result = (new OrderModel())->deleteOrder($id);

        if ($result) {
            return outPut(200, '删除成功');
        }

        return outPut(301, '删除失败');
    }

    /**
     * 修改订单价格
     * @adminMenu(
     *     'name'   => '修改订单价格',
     *     'parent' => 'menuDefault',
     *     'display'=> false,
     *       'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '修改订单价格',
     *     'param'  => ''
     * )
     */
    public function mdyPrice()
    {
        if ($_POST) {
            $params = $this->request->post();
            // var_dump($params['list']);die;
            $data['data'] = $params['list'];
            $data['data']['name'] = Session::get('name');
            $result = (new OrderModel())->modifyPrice($data);
            if ($result) {
                return outPut(200, '修改成功');
            }
            return outPut(301, $result);
        }
        //订单id
        $data = $this->request->param();
        //获取xinxi
        $orderInfo = (new OrderModel())->getEditPriceInfo($data['id']);
        // var_dump($orderInfo);die;
        $this->assign('info', $orderInfo);
        return $this->fetch('edit_price');
    }

    /**
     * 添加订单商品成本
     * @adminMenu(
     *     'name'   => '添加订单商品成本',
     *     'parent' => 'menuDefault',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加订单商品成本',
     *     'param'  => ''
     * )
     */
    public function expense()
    {
        if ($_POST) {
            $params = $this->request->post();
            $order_id = $params['list']['order_id'];
            $list = $params['list']['goods_list'];
            $res = (new OrderModel())->addCostPrice($list, $order_id);
            if ($res) {
                return outPut(200, '操作成功');
            }
            return outPut(301, '操作失败');
        } else {
            $order_id = $this->request->param('order_id');
            $this->assign('order_id', $order_id);
            $goods_list = (new OrderModel())->getOrderGoodsLists($order_id);
            $this->assign('info', $goods_list);
            return $this->fetch('expense');
        }
    }

    /**
     * 查看改价信息
     * @return [type] [description]
     */
    public function showDiscount()
    {
        $order_id = $this->request->param('order_id');
        $info = (new OrderModel())->showEditPriceInfo($order_id);
        $this->assign('info', $info);
        return $this->fetch('show_discount');

    }

    /**
     * [showExpense 查看订单成本]
     * @return [type] [description]
     */
    public function showExpense()
    {
        $order_id = $this->request->param('order_id');
        $this->assign('order_id', $order_id);
        $goods_list = (new OrderModel())->getOrderCostPrice($order_id);
        $this->assign('info', $goods_list);
        return $this->fetch('show_expense');

    }

    //快递页面
    public function express()
    {
        $order_id = $this->request->param('order_id');
        //获取订单物流数据
        $info = (new OrderExpressModel())->getOrderExpress($order_id);
        if (empty($info)) {
            $express_info = '';
        } else {
            $kd_id = config('kdnapi.appid');
            $app_key = config('kdnapi.appkey');
            $url = config('kdnapi.apiurl');

            $requestData = '{"OrderCode":"","ShipperCode":"' . $info["express_code"] . '","LogisticCode":"' . $info["express_sn"] . '"}';
            // echo $requestData;die;
            $datas = array(
                'EBusinessID' => $kd_id,
                'RequestType' => '1002',
                'RequestData' => urlencode($requestData),
                'DataType' => '2',
            );
            $datas['DataSign'] = $this->encrypt($requestData, $app_key);
            // $url = "http://www.kuaidi.com/index-ajaxselectcourierinfo-123456-shentong.html";
            $res = curl_request($url, 'post', $datas);
            $res = json_decode($res, true);
            // print_r($res);die;
            if (!isset($res['Traces']) || empty($res['Traces'])) {
                $express_info = '';
            } else {
                $express_info = array_reverse($res['Traces']);
            }
        }
        $this->assign('express_info', $express_info);
        return $this->fetch('express');
    }

    /**
     * 电商Sign签名生成
     * @param data 内容
     * @param appkey Appkey
     * @return DataSign签名
     */
    public function encrypt($data, $appkey)
    {
        return urlencode(base64_encode(md5($data . $appkey)));
    }

    /**
     * 发货/修改物流
     * @adminMenu(
     *     'name'   => '发货/修改物流',
     *     'parent' => 'menuDefault',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '发货/修改物流',
     *     'param'  => ''
     * )
     */
    public function deliver()
    {
        //如果是post请求
        if (Request::instance()->isPost()) {
            //处理post请求过来的数据进行操作
            $data = Request::instance()->post();
            $data = $data['lists'];
            // var_dump($data['order_id']);die;
            $order_id = (int)$data['order_id'];
            $express_info = $data['express_data'];
            $express_info['order_id'] = $order_id;
            $express_info['name'] = Session::get('name');//管理员名字
            if (model('order.OrderTempData')->where(['order_id'=>$order_id])->count() < 1){
                $goods_lists = $data['goods_list'];
                foreach ($goods_lists as &$v){
                    $v['order_goods_id'] = $v['id'];
                    unset($v['id']);
                }
                $sd_res = (new OrderModel())->saveOrderTempData($goods_lists,$order_id );
                if (!$sd_res){
                    return outPut(301, '临时数据添加失败');
                }
            }
            if (empty($express_info['orderExpress_id'])) {
                if (isset($data['goods_list'])) {
                    $acp_res = (new OrderModel())->addCostPrice($data['goods_list'],$order_id );
                    if (!$acp_res) {
                        return outPut(301, '成本和临时信息添加失败');
                    }
                }
            }
            $res = (new OrderModel())->modifyOrSend($express_info);
            if ($res) {
                return outPut(200, '操作成功');
            }
            return outPut(301, '操作失败');
        }

        //订单id
        $order_id = $this->request->param('order_id');

        //获取快递列表
        $expressInfo = (new OrderModel())->getExpressInfo();
        //获取订单物流信息
        $orderExpress = (new OrderModel())->getOrderExpress($order_id);
        if (empty($orderExpress)) {
            $orderExpress = null;
        }
        $order_goods_list = (new OrderModel())->getOrderGoodsLists($order_id);
        $this->assign('orderExpress', $orderExpress);
        $this->assign('expressInfo', $expressInfo);
        $this->assign('order_id', $order_id);
        $this->assign('goods_list', $order_goods_list);

        return $this->fetch('deliver');
    }
    /**
     * 保存订单临时数据
     * @adminMenu(
     *     'name'   => '保存订单临时数据',
     *     'parent' => 'menuDefault',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '保存订单临时数据',
     *     'param'  => ''
     * )
     */
    public function saveOrderTempData()
    {
        //处理post请求过来的数据进行操作
        $data = Request::instance()->post();
        $data = $data['lists'];
        // var_dump($data['order_id']);die;
        $order_id = (int)$data['order_id'];
        if (empty($data['goods_list'])) {
            return outPut(301, '请填写成本等信息');
        }
        $res = (new OrderModel())->saveOrderTempData($data['goods_list'],$order_id );
        if (!$res) {
            return outPut(301, '保存数据失败');
        }
        return outPut(200, '保存数据成功');

    }

    /**
     * [getitemCostPriceInStock 获取商品成本]
     * @return [type] [description]
     */
    public function getItemCostPriceInStock()
    {
        $data = $this->request->param();
        $order_id = $data['order_id'];
        $shop_id = (new OrderModel())->where('id','=',$order_id)->value('shop_id');
        $item_id = $data['item_id'];
        $cost_price = model('shop.Stock')->getItemCostPrice($item_id,$shop_id);
        $faker_price = model('shop.Stock')->getItemFakerCostPrice($item_id,$shop_id);
        return outPut(200,'获取成功',['cost_price'=>$cost_price,'faker_price'=>$faker_price]);
    }



    /**
     * [editReceiptInfo 修改收获信息]
     * @return [type] [description]
     */
    public function editReceiptInfo()
    {
        //如果是post请求
        if (Request::instance()->isPost()) {

            //处理post请求过来的数据进行操作
            $data = Request::instance()->post();
            // var_dump($data);die;
            $data['admin_name'] = Session::get('name');//管理员名字
            $res = (new OrderModel())->modifyReceiptInfo($data);

            if ($res) {
                return outPut(200, '修改成功');
            }
            return outPut(301, '修改失败');
        }

        //订单id
        $data = $this->request->param();
        //获取xinxi
        $orderInfo = (new OrderModel())->get($data['order_id']);
        $declareInfo = (new \app\admin\model\user\UserDeclareModel())->get(['member_id' => $data['member_id']]);
        // var_dump($declareInfo);die;
        $this->assign('declareInfo', $declareInfo);
        $this->assign('orderInfo', $orderInfo);
        return $this->fetch('edit_receipt');
    }


    /**
     * 订单处理
     * @adminMenu(
     *     'name'   => '订单处理',
     *     'parent' => 'menuDefault',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '订单处理',
     *     'param'  => ''
     * )
     */
    public function dealwith()
    {
        if (empty(Session::get('name'))) {
            $uid = Session::get('ADMIN_ID');
            $users = (new AdminUserModel())->getAdminUserBasicInfo($uid, 'user_login');
        }
        $userinfo['name'] = Session::get('name') ? Session::get('name') : $users['user_login'];//管理员名字
        $userinfo['user_id'] = Session::get('ADMIN_ID');//管理员id
        //如果是post请求
        if (Request::instance()->isPost()) {
            $data = Request::instance()->post();
            if ($data['send_way'] == 0) {
                /**
                 * 门店自提时退运费
                 * 1.微信支付就原路退回
                 * 2.熊猫卡支付就返回金额到熊猫卡
                 */
                $orderinfo = (new OrderModel())->get($data['order_id']);
                // $orderinfo = (new OrderModel())->get(1002);
                $open_id = (new UserModel())->where(['id' => $orderinfo->member_id])->value('openid');
                $token = get_access_token();
                if ($orderinfo->pay_way == '微信') {
                    echo json_encode(array('code' => 100, 'msg' => '微信支付暂不支持自提'));
                    die;
                    // file_put_contents('notifys.txt','这里1',FILE_APPEND);
                    $refund_data['order_sn'] = $orderinfo->sn;
                    $refund_data['transaction_id'] = $orderinfo->pay_sn;
                    $refund_data['total_price'] = $orderinfo->postage * 100;
                    // var_dump($refund_data);die;
                    // $refund_data['total_price'] = 1;
                    $result = (new PayModel())->wxRefund($refund_data);
                    if (($result['return_code'] == 'SUCCESS') && ($result['result_code'] == 'SUCCESS')) {
                        //退款成功 并且需要修改门店的金额（减去运费的钱）
                        try {
                            $temp_id = Db::connect(config('ddxx'))->table('tf_template_msg')->where(['type_name' => 'refund'])->value('template_id');
                            $datas = [];
                            $datas['touser'] = $open_id;
                            $datas['template_id'] = $temp_id;
                            $datas['url'] = '';
                            $tmp_data = [];
                            $tmp_data['first'] = ['value' => '您的订单邮费已为您退回', 'color' => '#006400'];//退款单号
                            $tmp_data['keyword1'] = ['value' => $orderinfo->sn];//退款单号
                            $tmp_data['keyword2'] = ['value' => $orderinfo->postage . ' 元'];//金额
                            $tmp_data['remark'] = ['value' => '温馨提示:退款成功,已通过微信退回,注意查看', 'color' => '#B8B8B8'];//
                            $datas['data'] = $tmp_data;
                            $json_data = json_encode($datas, JSON_UNESCAPED_UNICODE);
                            sendtemplateinfo($token, $json_data);
                        } catch (Exception $e) {
                            LogsController::actionLogRecord('一条退款模板信息发送失败');
                        }

                    } else if (($result['return_code'] == 'FAIL') || ($result['result_code'] == 'FAIL')) {
                        // file_put_contents('notifys.txt',json_encode($result),FILE_APPEND);
                        LogsController::actionLogRecord('退款失败,退款方式:微信');
                    } else {
                        //失败
                        LogsController::actionLogRecord('发起微信退款失败,退款方式:微信');
                    }
                } elseif ($orderinfo->pay_way == '熊猫卡') {
                    //查询当前用户的默认卡
                    $card_id = Db::connect(config('ddxx'))
                        ->table('tf_card')
                        ->where(['member_id' => $orderinfo->member_id, 'is_default' => 1])
                        ->where('paytime', '>', 0)
                        ->value('id');
                    if (empty($card_id)) {
                        $cards = Db::connect(config('ddxx'))
                            ->table('tf_card')
                            ->where(['member_id' => $orderinfo->member_id])
                            ->where('paytime', '>', 0)
                            ->limit(1)
                            ->value('id');
                    }
                    if (empty($card_id)) {
                        echo json_encode(array('code' => 100, 'msg' => '没有熊猫卡可进行退款'));
                        die;
                    }
                    //给卡增加钱
                    $my_price = Db::connect(config('ddxx'))->table('tf_card')->where(['id' => $card_id])->value('my_num');
                    $amounts = $my_price + $orderinfo->postage;
                    $u_res = Db::connect(config('ddxx'))
                        ->table('tf_card')
                        ->where(['id' => $card_id])
                        ->update(['my_num' => $amounts]);
                    if ($u_res) {
                        //退款成功
                        try {
                            $temp_id = Db::connect(config('ddxx'))->table('tf_template_msg')->where(['type_name' => 'refund'])->value('template_id');
                            $datas = [];
                            $datas['touser'] = $open_id;
                            $datas['template_id'] = $temp_id;
                            $datas['url'] = '';
                            $tmp_data = [];
                            $tmp_data['first'] = ['value' => '您的订单邮费已为您退回', 'color' => '#006400'];//退款单号
                            $tmp_data['keyword1'] = ['value' => $orderinfo->sn];//退款单号
                            $tmp_data['keyword2'] = ['value' => $orderinfo->postage . ' 元'];//金额
                            $tmp_data['remark'] = ['value' => '温馨提示:退款成功,已退回您的熊猫卡,请查看卡内余额', 'color' => '#B8B8B8'];//
                            $datas['data'] = $tmp_data;
                            $json_data = json_encode($datas, JSON_UNESCAPED_UNICODE);
                            sendtemplateinfo($token, $json_data);
                        } catch (Exception $e) {
                            //无记录反馈
                            LogsController::actionLogRecord('一条退款模板信息发送失败');
                        }

                    } else {
                        LogsController::actionLogRecord('退款失败,退款方式:熊猫卡');
                    }
                }
            }
            if ($data['send_way'] == 2) {
                //此时判断商家是否支付了订单费用给平台,如果没有,就处理不了
                $res = Db::connect(config('ddxx'))->table('tf_admin_order')->where(['order_id' => $data['order_id']])->find();
                if ($res['pay_status'] != 1) {
                    echo json_encode(array('code' => 100, 'msg' => '请先付款'));
                    die;
                }
            }
            $result = (new OrderModel())->modifyOrder($data, $userinfo);
            if ($result) {
                echo json_encode(array('code' => 200, 'msg' => '订单处理成功'));
            } else {
                echo json_encode(array('code' => 100, 'msg' => '处理订单失败,请稍后再试'));
            }
            die;
        }
        //订单id
        $order_id = $this->request->param('order_id');
        //获取快递列表
        $expressInfo = (new OrderModel())->getExpressInfo();
        $orderinfo = (new OrderModel())->getBasicInfo($order_id);
        $this->assign('orderinfo', $orderinfo);
        $this->assign('expressInfo', $expressInfo);
        $this->assign('order_id', $order_id);
        return $this->fetch('dealwith');
    }

    //关闭订单
    public function closeOrder()
    {
        $admin_name = Session::get('name');
        $order_id = $this->request->param('order_id');
        $res = (new OrderModel())->closeOrder($order_id, $admin_name);
        if ($res) {
            return outPut(200, '订单关闭成功');
        }
        return outPut(301, '订单关闭失败');
    }

    //完成订单
    public function completeOrder()
    {
        $admin_name = Session::get('name');
        $order_id = $this->request->param('order_id');
        $res = (new OrderModel())->completeOrder($order_id, $admin_name);
        if ($res) {
            return outPut(200, '操作成功');
        }
        return outPut(301, '操作失败');
    }

    /**
     * 商品售后列表
     * @adminMenu(
     *     'name'   => '商品售后列表',
     *     'parent' => 'menuDefault',
     *     'display'=> true,
     *       'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '商品售后列表',
     *     'param'  => ''
     * )
     */
    public function orderRefundList()
    {
        //搜索参数
//        $shopInfo = (new AdminUserModel())->getAdminUserBasicInfo($this->admin_id,'shop_id');
//        $shop_id = $shopInfo['shop_id'];
        $sn = $this->request->param('as_sn');
        $name = trim($this->request->param('name', ''));
        $rStatus = trim($this->request->param('status', ''));

        //生成搜索条件
        $where = [];
//        //店铺加上
//        if ($shop_id && $this->admin_id != 1) {
//            $where['a.shop_id'] = $shop_id;
//        }

        if ($sn != null) {
            $where['a.as_sn'] = ['like', "%$sn%"];
        }
        $this->assign('name', $name);
        if ($name != null) {
            $where['b.name'] = ['like', "%$name%"];;
        }
        $this->assign('name', $name);
        if ($rStatus != null) {
            $where['a.status'] = ['=', $rStatus];
        }
        $this->assign('status', $rStatus);

        $orderListDatas = (new OrderRefundModel())->getOrderRefundListDatas($where);

        //分页参数
        $pageParams = [
            'as_sn' => $sn != null ? $sn : '',
            'name' => $name != null ? $name : '',
            'status' => $rStatus != null ? $rStatus : '',
        ];

        $datas = $this->lists($orderListDatas, $pageParams)->toArray();

        $this->assign('datas', $datas['data']);

        return $this->fetch('order_refund_list');
    }

    /**
     * 退货订单详情
     * @adminMenu(
     *     'name'   => '退货订单详情',
     *     'parent' => 'menuDefault',
     *     'display'=> false,
     *       'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '退货订单详情',
     *     'param'  => ''
     * )
     */
    public function refundDetail()
    {
        $id = $this->request->param('id');
        $detailInfo = (new OrderRefundModel())->getDetailData($id);
        $this->assign('info', $detailInfo);
        return $this->fetch('refund_detail');
    }

    /**
     * 删除退款申请
     * @adminMenu(
     *     'name'   => '删除退款申请',
     *     'parent' => 'menuDefault',
     *     'display'=> false,
     *       'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '删除退款申请',
     *     'param'  => ''
     * )
     */
    public function refundDel()
    {
        $id = $this->request->param('id');
        $result = (new OrderRefundModel())->deletes($id);
        if ($result) {
            LogsController::actionLogRecord('删除售后数据,id为:' . $id);
            return outPut(200, '删除成功');
        }
        return outPut(301, '删除失败');
    }

    /**
     * 退货订单处理
     * @adminMenu(
     *     'name'   => '退货订单处理',
     *     'parent' => 'menuDefault',
     *     'display'=> false,
     *       'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '退货订单处理',
     *     'param'  => ''
     * )
     */
    public function refundDealwith()
    {
        $id = $this->request->param('id');
        $data = $this->request->param();
        $is_deal = $data['is_deal'];
        $data['staff_reply'] = trim($data['staff_reply']);
        $data['finish_time'] = time();
        $data['status'] = 1;
        unset($data['id']);
        if (empty($data['staff_reply'])) {
            return outPut(301, '请输入处理信息');
        }
        $info = OrderRefundModel::get($id);
        //
        if ($is_deal == false) {
            unset($data['is_deal']);
            $res = (new OrderRefundModel())->dealwith($data, $id);
            if (!is_object($res)) {
                return outPut(301, '操作失败');
            }
            //微信模板消息 售后
            $token = get_access_token();
            $temp_id = Db::connect(config('ddxx'))->table('tf_template_msg')->where(['type_name' => 'sale_over'])->value('template_id');
            $open_id = (new UserModel())->where(['id' => $info->member_id])->value('openid');
            $goods_name = Db::connect(config('ddxx'))->table('tf_item')->where(['id' => $info->goods_id])->value('title');
            $datas = [];
            $datas['touser'] = $open_id;
            $datas['template_id'] = $temp_id;
            $datas['url'] = '';
            $tmp_data['first'] = ['value' => "您好,您的售后申请处理结果如下:", 'color' => '#006400'];
            $tmp_data['keyword1'] = ['value' => $goods_name];
            $tmp_data['keyword2'] = ['value' => $info->as_sn];
            $tmp_data['keyword3'] = ['value' => $data['staff_reply']];
            $tmp_data['remark'] = ['value' => '以上是您的售后处理结果,如有疑问可联系我们得客服 ', 'color' => '#B8B8B8'];
            $datas['data'] = $tmp_data;
            $json_data = json_encode($datas, JSON_UNESCAPED_UNICODE);
            sendtemplateinfo($token, $json_data);
            LogsController::actionLogRecord('处理了一条售后信息');
            return outPut(200, '处理完成');
        }
        if ($data['price'] == '') {
            return outPut(301, '退款金额有误');
        }
        if ($info->status == 1) {
            return outPut(301, '当前信息已经处理');
        }
        unset($data['is_deal']);
        $res = (new OrderRefundModel())->dealwith($data, $id);
        if (!is_object($res)) {
            return outPut(301, '操作失败');
        }
        //微信模板消息 售后
        $token = get_access_token();
        $temp_id = Db::connect(config('ddxx'))->table('tf_template_msg')->where(['type_name' => 'sale_over'])->value('template_id');
        $open_id = (new UserModel())->where(['id' => $info->member_id])->value('openid');
        $goods_name = Db::connect(config('ddxx'))->table('tf_item')->where(['id' => $info->goods_id])->value('title');
        $datas = [];
        $datas['touser'] = $open_id;
        $datas['template_id'] = $temp_id;
        $datas['url'] = '';
        $tmp_data['first'] = ['value' => "您好,您的售后申请处理结果如下:", 'color' => '#006400'];
        $tmp_data['keyword1'] = ['value' => $goods_name];
        $tmp_data['keyword2'] = ['value' => $info->as_sn];
        $tmp_data['keyword3'] = ['value' => $data['staff_reply']];
        $tmp_data['remark'] = ['value' => '以上是您的售后处理结果,如有疑问可联系客服', 'color' => '#B8B8B8'];
        $datas['data'] = $tmp_data;
        $json_data = json_encode($datas, JSON_UNESCAPED_UNICODE);
        sendtemplateinfo($token, $json_data);

        LogsController::actionLogRecord('处理一条售后信息,id为:' . $id);
        return outPut(200, '操作成功');
    }

}
