<?php
/**
 * Author: zhoumi
 * Date: 2018/3/28
 * Description:
 */

namespace app\admin\controller;

use app\admin\model\CollectModel;
use think\Session;
use think\Db;

/**
 * Class CollectController
 * @package app\admin\controller
 * @adminMenuRoot(
 *     'name'   =>'收银台',
 *     'action' =>'menuDefault',
 *     'parent' =>'',
 *     'display'=> true,
 *     'order'  => 10000,
 *     'icon'   =>'cogs',
 *     'remark' =>'收银台'
 * )
 */
class CollectController extends BaseController
{
    protected $pageLimit = 12;

    /**
     * 收银台主页
     * @adminMenu(
     *     'name'   => '收银台主页',
     *     'parent' => 'menuDefault',
     *     'display'=> true,
     *	   'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '收银台主页',
     *     'param'  => ''
     * )
     */
    public function show()
    {
        return view('index');
    }

    //获取页面初始化所需数据
    public function getAllData()
    {
        $data = [];
        $except_role_id = [1,6];//不限制的门店管理员角色
        $shop_id = Session::get('SHOP_ID');
        $admin_id = $this->admin_id;
        $role_id = Db::table('ddxm_role_user')->where('user_id','=',$admin_id)->value('role_id');
        if ($admin_id == 1 ||in_array($role_id,$except_role_id)) {
            $shop_id = 22;
        }
        if (empty($shop_id) || !is_numeric($shop_id)) {
            return outPut(301,'当前信息有误，请退出重新登录');
        }else{
            $data['shop_id'] = (int)$shop_id;
        }
        $workerlist = Db::connect(config('ddxx'))->table('tf_worker')->field('id,name,type,workid')->where(['sid'=>$shop_id,'status'=>1])->select();//获取门店员工
        $shoplist = model('shop.Shop')->field('id,name')->select();
        foreach ($workerlist as $k => $v)
        {
            if ($v['type'] == 0) {
                $workerlist[$k]['type'] = '婴儿游泳';
            }elseif($v['type'] == 1){
                $workerlist[$k]['type'] = '小儿推拿';
            }else{
                $workerlist[$k]['type'] = '成人推拿';
            }
        }
        $data['work_list'] = $workerlist;
        $data['shop_list'] = $shoplist;
        $data['worker_id'] = $admin_id;
        //生成一个订单号
        $data['order_sn'] = time().rand(10,99).$shop_id;
        return outPut(200, '获取成功', $data);
    }

    /**
     * 门店订单
     * @adminMenu(
     *     'name'   => '门店订单',
     *     'parent' => 'menuDefault',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '门店订单',
     *     'param'  => ''
     * )
     */
    public function orderList()
    {
        // //当存在时间段的时候要加上时间段条件(默认时昨天)
        $stime = trim($this->request->param('start_time'));
        $etime = trim($this->request->param('end_time'));

        $start = strtotime($stime);
        $end = strtotime($etime) + 24*3600 -1;
        //接受其他参数
        $sn = trim($this->request->param('sn'));
        $mobile = trim($this->request->param('mobile'));
        $workid = trim($this->request->param('workid'));
        $pay_way = trim($this->request->param('pay_way'));
        $buy_type = trim($this->request->param('buy_type'));
        $shopId = $this->request->param('shop_id');
        $this->assign('shop_id',$shopId);
        // 组装查询条件
        $where = [];
        $w_where = [];
        $show_shop = 0;//当前用户是否显示门店列表
        $shop_id = Session::get('SHOP_ID');
        // 获取门店列表
        $except_role_id = [1,6,7];//不限制的门店管理员角色
        $admin_id = $this->admin_id;
        $role_id = Db::name('role_user')->where('user_id','=',$admin_id)->value('role_id');
        if ($admin_id == 1 || in_array($role_id,$except_role_id)) {
            $this->assign('shows',1);
            $shoplist = (new CollectModel())->getAllShop('id,name');
        }else{
            $shoplist = (new CollectModel())->getAllShop('id,name',['id'=>['in',$shop_id]]);
        }
        $this->assign('shoplist', $shoplist);
        if ($this->admin_id != 1) {
            $where['a.shop_id'] = ['in',$shop_id];
            $w_where['sid'] = ['in',$shop_id];
        }
        //门店数大于1就显示选择门店
        $shopArr = explode(',',$shop_id);
        if (count($shopArr) > 1 || $this->admin_id == 1) {
            $show_shop = 1;
        }
        $this->assign('show_shop',$show_shop);
        if (!empty($shopId)) {//财务
            $where['a.shop_id'] = $shopId;
            $w_where['sid'] = ['in',$shopId];
        }
        if ($workid) {
            $worker_name = model('shop.Worker')->where(['id'=>$workid])->value('name');
            $where['a.waiter'] = $worker_name;
        }
        $this->assign('workid',$workid);
        $workerlist = model('shop.Worker')->workerList($w_where)->select();
        if ($stime && $etime) {
            $where['a.addtime'] = ['between',[$start,$end]];
        }elseif ($start) {
            $end = strtotime(date('Y-m-d',time())) + 24*3600 -1;
            $where['a.addtime'] = ['between',[$start,$end]];
        }elseif ($etime){
            $start = 0;
            $where['a.addtime'] = ['between',[$start,$end]];
        }

        $this->assign('start_time',$stime);
        $this->assign('end_time',$etime);
        if ($sn) {
            $where['a.sn'] = $sn;
        }
        $this->assign('sn',$sn);

        if ($mobile) {
           $where['a.mobile'] = $mobile;
        }
        $this->assign('mobile',$mobile);

        if ($pay_way) {
            $where['a.pay_way'] = $pay_way;
        }
        $this->assign('pay_way',$pay_way);
        $other_where = [];
        if ($buy_type) {
            if ($buy_type == 1) {
                $join = [
                    ['tf_order_goods b','a.id=b.order_id'],
                ];
            }else{
                $join = [
                    ['tf_yuyue b','a.id=b.order_id'],
                ];
            }
            $other_where['buy_type'] = $join;
        }
        $this->assign('buy_type',$buy_type);

        $where['a.is_online'] = 0;
        $where['a.paytime'] = ['>',0];
        $where['a.pay_status'] = 1;
        // $where['a.type'] = ['neq',3];
        $where['a.type'] = 4;
        //分页参数
        $pageParams = [
            'sn' => $sn != null ? $sn : '',
            'pay_way' => $pay_way != null ? $pay_way : '',
            'buy_type' => $buy_type != null ? $buy_type : '',
            'workid' => $workid != null ? $workid : '',
            'start_time' => $stime != null ? $stime : '',
            'end_time' => $etime != null ? $etime : '',
            'shop_id' => $shopId != null ? $shopId : '',
            'mobile' => $mobile != null ? $mobile : ''
        ];
        $list = (new CollectModel())->getOrderListDatas($where,$other_where)->paginate($this->pageLimit, false, ['query'=>$pageParams]);

        $db = Db::connect(config('ddxx'));

        foreach ($list as $k => $v) {
            $goods_count = $db->name('order_goods')->where('order_id','=',$v->id)->count('id');
            $server_list = $db->name('yuyue')->where('order_id','=',$v->id)->field('type')->select();
            $server_count = count($server_list);
            $server_name = '';
            foreach ($server_list as $ks => $vs) {
                $server_names = $db->name('service')->where(['id'=>$vs['type']])->value('sname');
                $server_name .= ($ks == 0) ?$server_names:"、$server_names";
            }
            $v['is_refund'] = 0;
            $yy_status = $db->name('yuyue')->where('order_id','=',$v->id)->value('status');
            $goods_status = $db->name('order_goods')->where('order_id','=',$v->id)->value('status');
            if ($yy_status == -1 || $goods_status == -1) {
                $v['is_refund'] = 1;
            }
            if ($goods_count > 0 && $server_count > 0) {
                $list[$k]['buy_type'] = '商品、服务[ '.$server_name.' ]';
            }elseif($server_count > 0){
                $list[$k]['buy_type'] = '服务[ '.$server_name.' ]';
                // $usc_id = $db->name('yuyue')->where('order_id','=',$v->id)->value('user_card_id');
                // if ($usc_id) {
                //     unset($list[$k]);
                //     continue;
                // }
            }else{
                // $msc_id = $db->name('member_card')->where('order_id','=',$v->id)->value('id');
                // $finance_id = $db->name('finance')->where('order_id','=',$v->id)->value('id');
                // if ($msc_id || empty($finance_id)) {
                //     unset($list[$k]);
                //     continue;
                // }
                $list[$k]['buy_type'] = '商品';
            }
            // 成本价
            $list[$k]['cost_price'] = (new CollectModel())->getOrderCost($v->id);
            $list[$k]['faker_price'] = (new CollectModel())->getOrderCost($v->id,2);
            //是否改价
            $eprice = $db->name('discount')->where(['order_id'=>$v->id,'type'=>2])->count('id');
            if ($eprice) {
                $list[$k]['is_eprice'] = 1;
            }else{
                $list[$k]['is_eprice'] = 0;
            }
            // $list[$k]['mobile'] = '';
            $list[$k]['nickname'] = '非会员';
            if ($v['member_id']) {
                $list[$k]['mobile'] = $db->name('member')->where('id','=',$v->member_id)->value('mobile');
                $list[$k]['nickname'] = $db->name('member')->where('id','=',$v->member_id)->value('nickname');
            }
            if($v['order_type']==1){
                $list[$k]['order_type'] = '普通';
            }elseif ($v['order_type']==2){
                $list[$k]['order_type'] = '预定';
            }

        }
       //  分页样式
        $this->assign('page', $list->render());
        //  总条数
        $this->assign('totalCount', $list->total());
        //  当前页面
        $this->assign('current', $list->currentPage());
        //  每页显示数量
        $this->assign('listRows', $list->listRows());
        $this->assign('workerlist', $workerlist);
        $this->assign('list',$list);
        return view('today_order');
    }


    /**
     * 更新订单成本
     */
    public function updateCostPrice()
    {
        set_time_limit(0);
        // //当存在时间段的时候要加上时间段条件(默认时昨天)
        // $start = 1538323200;
        $start = 1535731200;
        // $end = 1539014400 + 24*3600 -1;
       $end = 1538236800 + 24*3600 -1;
        //接受其他参数
        $shopId = 25;
        // 组装查询条件
        $where = [];
        $where['a.addtime'] = ['between',[$start,$end]];
        $where['a.shop_id'] = ['neq','18,22'];
//        $where['a.is_online'] = 1;
        $where['a.paytime'] = ['>',0];
        $where['a.pay_status'] = 1;
        $where['a.type'] = ['neq',3];
        $list = (new CollectModel())->getOrderListDatas($where)->select();
        foreach ($list as $k => $v) {
            // 成本价
            if ($list[$k]['amount'] < 0) {
                $refund = 1;
            }else{
                $refund = 0;
            }
            (new CollectModel())->getOrderCost1($v->id,$refund,$v);
        }
        echo '执行完了';die;
    }

    /**
     * 根据搜索条件导出门店订单为EXCEL
     */
    public function export()
    {
        set_time_limit(0);
        // //当存在时间段的时候要加上时间段条件(默认时昨天)
        $stime = trim($this->request->param('start_time'));
        $etime = trim($this->request->param('end_time'));

        $start = strtotime($stime);
        $end = strtotime($etime) + 24*3600 -1;
        //接受其他参数
        $sn = trim($this->request->param('sn'));
        $mobile = trim($this->request->param('mobile'));
        $workid = trim($this->request->param('workid'));
        $pay_way = trim($this->request->param('pay_way'));
        $buy_type = trim($this->request->param('buy_type'));
        $shopId = $this->request->param('shop_id');
        $this->assign('shop_id',$shopId);
        // 组装查询条件
        $where = [];
        $w_where = [];
        $show_shop = 0;//当前用户是否显示门店列表
        $shop_id = Session::get('SHOP_ID');
        if ($this->admin_id != 1) {
            $where['a.shop_id'] = ['in',$shop_id];
            $w_where['sid'] = ['in',$shop_id];
        }
        $except_role_id = [1,6,7];//不限制的门店管理员角色
        $admin_id = $this->admin_id;
        $role_id = Db::name('role_user')->where('user_id','=',$admin_id)->value('role_id');
        //门店数大于1就显示选择门店
        $shopArr = explode(',',$shop_id);
        if (count($shopArr) > 1 || $this->admin_id == 1) {
            $show_shop = 1;
        }
        $this->assign('show_shop',$show_shop);
        if (!empty($shopId)) {//财务
            $where['a.shop_id'] = $shopId;
            $w_where['sid'] = ['in',$shopId];
        }
        if ($workid) {
            $worker_name = model('shop.Worker')->where(['id'=>$workid])->value('name');
            $where['a.waiter'] = $worker_name;
        }
        $this->assign('workid',$workid);
//        $workerlist = model('shop.Worker')->workerList($w_where)->select();
        if ($stime && $etime) {
            $where['a.addtime'] = ['between',[$start,$end]];
        }elseif ($start) {
            $end = strtotime(date('Y-m-d',time())) + 24*3600 -1;
            $where['a.addtime'] = ['between',[$start,$end]];
        }elseif ($etime){
            $start = 0;
            $where['a.addtime'] = ['between',[$start,$end]];
        }

        $this->assign('start_time',$stime);
        $this->assign('end_time',$etime);
        if ($sn) {
            $where['a.sn'] = $sn;
        }
        $this->assign('sn',$sn);

        if ($mobile) {
            $where['a.mobile'] = $mobile;
        }
        $this->assign('mobile',$mobile);

        if ($pay_way) {
            $where['a.pay_way'] = $pay_way;
        }
        $this->assign('pay_way',$pay_way);
        $other_where = [];
        if ($buy_type) {
            if ($buy_type == 1) {
                $join = [
                    ['tf_order_goods b','a.id=b.order_id'],
                ];
            }else{
                $join = [
                    ['tf_yuyue b','a.id=b.order_id'],
                ];
            }
            $other_where['buy_type'] = $join;
        }
        $this->assign('buy_type',$buy_type);

        $where['a.is_online'] = 0;
        $where['a.paytime'] = ['>',0];
        $where['a.pay_status'] = 1;
        $where['a.type'] = ['neq',3];

        $list = (new CollectModel())->getOrderListDatas($where,$other_where)->select();
        $db = Db::connect(config('ddxx'));
        foreach ($list as $k => $v) {
            $goods_count = $db->name('order_goods')->where('order_id','=',$v->id)->count('id');
            $server_list = $db->name('yuyue')->where('order_id','=',$v->id)->field('type')->select();
            $server_count = count($server_list);
            $server_name = '';
            foreach ($server_list as $ks => $vs) {
                $server_names = $db->name('service')->where(['id'=>$vs['type']])->value('sname');
                $server_name .= ($ks == 0) ?$server_names:"、$server_names";
            }
            $v['is_refund'] = 0;
            $yy_status = $db->name('yuyue')->where('order_id','=',$v->id)->value('status');
            $goods_status = $db->name('order_goods')->where('order_id','=',$v->id)->value('status');
            if ($yy_status == -1 || $goods_status == -1) {
                $v['is_refund'] = 1;
            }
            if ($goods_count > 0 && $server_count > 0) {
                $list[$k]['buy_type'] = '商品、服务[ '.$server_name.' ]';
            }elseif($server_count > 0){
                $list[$k]['buy_type'] = '服务[ '.$server_name.' ]';
            }else{
                $list[$k]['buy_type'] = '商品';
            }
            // 成本价
            $list[$k]['cost_price'] = (new CollectModel())->getOrderCost($v->id);
            $list[$k]['faker_price'] = (new CollectModel())->getOrderCost($v->id,2);
            //是否改价
            $eprice = $db->name('discount')->where(['order_id'=>$v->id,'type'=>2])->count('id');
            if ($eprice) {
                $list[$k]['is_eprice'] = 1;
            }else{
                $list[$k]['is_eprice'] = 0;
            }
            // $list[$k]['mobile'] = '';
            $list[$k]['nickname'] = '非会员';
            if ($v['member_id']) {
                $list[$k]['mobile'] = $db->name('member')->where('id','=',$v->member_id)->value('mobile');
                $list[$k]['nickname'] = $db->name('member')->where('id','=',$v->member_id)->value('nickname');
            }
            if($v['order_type']==1){
                $list[$k]['order_type'] = '普通';
            }elseif ($v['order_type']==2){
                $list[$k]['order_type'] = '预定';
            }

        }
        $phpexcel = new \PHPExcel();
        if ($admin_id == 1 || in_array($role_id,$except_role_id)) {
            $phpexcel->getActiveSheet()->setCellValue('A1','ID')
                ->setCellValue('B1','时间')
                ->setCellValue('C1','订单号')
                ->setCellValue('D1','会员账号')
                ->setCellValue('E1','会员昵称')
                ->setCellValue('F1','交易门店')
                ->setCellValue('G1','服务人员')
                ->setCellValue('H1','交易渠道')
                ->setCellValue('I1','付款方式')
                ->setCellValue('J1','交易内容')
                ->setCellValue('K1','实收金额')
                ->setCellValue('L1','真实成本')
                ->setCellValue('M1','虚拟成本')
                ->setCellValue('N1','是否改价');
            foreach ($list as $k=>$v){
                $phpexcel->getActiveSheet()->setCellValue('A'.($k+2),$v['id'])
                    ->setCellValue('B'.($k+2),date("Y-m-d H:i:s",$v['addtime']))
                    ->setCellValueExplicit('C'.($k+2),$v['sn'],\PHPExcel_Cell_DataType::TYPE_STRING)
                    ->setCellValueExplicit('D'.($k+2),isset($v['mobile'])?$v['mobile']:'无',\PHPExcel_Cell_DataType::TYPE_STRING)
                    ->setCellValueExplicit('E'.($k+2),isset($v['nickname'])?$v['nickname']:'无',\PHPExcel_Cell_DataType::TYPE_STRING)
                    ->setCellValue('F'.($k+2),$v['shop_info']['name'])
                    ->setCellValue('G'.($k+2),isset($v['waiter'])?$v['waiter']:'无')
                    ->setCellValue('H'.($k+2),$v['is_online']?'线上商城':'门店收银')
                    ->setCellValue('I'.($k+2),$v['pay_way'])
                    ->setCellValue('J'.($k+2),$v['buy_type'])
                    ->setCellValue('K'.($k+2),$v['amount'])
                    ->setCellValue('L'.($k+2),$v['cost_price'])
                    ->setCellValue('M'.($k+2),$v['faker_price'])
                    ->setCellValue('N'.($k+2),$v['is_eprice']?'是':'否');
            }
        }else{
            $phpexcel->getActiveSheet()->setCellValue('A1','ID')
                ->setCellValue('B1','时间')
                ->setCellValue('C1','订单号')
                ->setCellValue('D1','会员账号')
                ->setCellValue('E1','会员昵称')
                ->setCellValue('F1','交易门店')
                ->setCellValue('G1','服务人员')
                ->setCellValue('H1','交易渠道')
                ->setCellValue('I1','付款方式')
                ->setCellValue('J1','交易内容')
                ->setCellValue('K1','实收金额')
                ->setCellValue('L1','是否改价');
            foreach ($list as $k=>$v){
                $phpexcel->getActiveSheet()->setCellValue('A'.($k+2),$v['id'])
                    ->setCellValue('B'.($k+2),date("Y-m-d H:i:s",$v['addtime']))
                    ->setCellValueExplicit('C'.($k+2),$v['sn'],\PHPExcel_Cell_DataType::TYPE_STRING)
                    ->setCellValueExplicit('D'.($k+2),isset($v['mobile'])?$v['mobile']:'无',\PHPExcel_Cell_DataType::TYPE_STRING)
                    ->setCellValueExplicit('E'.($k+2),isset($v['nickname'])?$v['nickname']:'无',\PHPExcel_Cell_DataType::TYPE_STRING)
                    ->setCellValue('F'.($k+2),$v['shop_info']['name'])
                    ->setCellValue('G'.($k+2),isset($v['waiter'])?$v['waiter']:'无')
                    ->setCellValue('H'.($k+2),$v['is_online']?'线上商城':'门店收银')
                    ->setCellValue('I'.($k+2),$v['pay_way'])
                    ->setCellValue('J'.($k+2),$v['buy_type'])
                    ->setCellValue('K'.($k+2),$v['amount'])
                    ->setCellValue('L'.($k+2),$v['is_eprice']?'是':'否');
            }
        }

        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.date("Y-m-d H:i:s",time()).' 门店订单导出.xls"');
        header('Cache-Control: max-age=0');
        $a = new \PHPExcel_Writer_Excel5($phpexcel);
        $a->save('php://output');
    }

   public function export1()
   {
       set_time_limit(0);
       $item_name = $this->request->param('item_name','');
       $month = $this->request->param('month','');
       $where = [];
       if ($month) {
           $start_time = strtotime(date('Y').'0'.$month.'01');
           $end_month = $month + 1;
           $end_time = strtotime(date('Y').'0'.$end_month .'01');
           $where['b.addtime'] = ['>',$start_time];
       }
       if ($item_name) {
           $where['a.subtitle'] = ["LIKE","%$item_name%"];
       }
       $where['a.status'] = ["neq",-1];
       $db = Db::connect(config('ddxx'));
       $list = $db->name('order_goods')->alias('a')->join('order b','a.order_id = b.id','LEFT')->where($where)->field('a.order_id,a.num,a.price,a.subtitle')->select();
       foreach ($list as $k=>$v){
           $order_info = $db->name('order')->where(['id'=>$v['order_id'],'paytime'=>['>',0]])->field('shop_id,sn,is_online,mobile,realname,waiter,paytime,pay_way')->find();
           if (!isset($order_info['order_status'])||$order_info['order_status']!=2) {
               unset($v);
           }
           $shop_name = $db->name('shop')->where(['id'=>$order_info['shop_id']])->value('name as shop_name');
           $list[$k]['shop_name'] = $shop_name;
           $list[$k]['paytime'] = date("Y-m-d H:i:s",$order_info['paytime']);
           $list[$k]['sn'] = $order_info['sn'];
           $list[$k]['mobile'] = $order_info['mobile'];
           $list[$k]['is_online'] = $order_info['is_online'];
           $list[$k]['realname'] = $order_info['realname'];
           switch ($order_info['pay_way']){
               case 1:
                   $list[$k]['pay_way'] = '微信';
                   break;
               case 2:
                   $list[$k]['pay_way'] = '支付宝';
                   break;
               case 3:
                   $list[$k]['pay_way'] = '余额';
                   break;
               case 4:
                   $list[$k]['pay_way'] = '银行卡';
                   break;
               case 5:
                   $list[$k]['pay_way'] = '现金';
                   break;
               case 7:
                   $list[$k]['pay_way'] = '赠送';
                   break;
           }
       }
       $phpexcel = new \PHPExcel();
       $phpexcel->getActiveSheet()
           ->setCellValue('A1','ID')
           ->setCellValue('B1','时间')
           ->setCellValue('C1','订单号')
           ->setCellValue('D1','会员账号')
           ->setCellValue('E1','会员昵称')
           ->setCellValue('F1','交易门店')
           ->setCellValue('G1','服务人员')
           ->setCellValue('H1','交易渠道')
           ->setCellValue('I1','付款方式')
           ->setCellValue('J1','商品名')
           ->setCellValue('K1','单价')
           ->setCellValue('L1','数量');
       foreach ($list as $k=>$v){
           $phpexcel->getActiveSheet()
               ->setCellValue('A'.($k+2),$v['order_id'])
               ->setCellValue('B'.($k+2),$v['paytime'])
               ->setCellValueExplicit('C'.($k+2),$v['sn'],\PHPExcel_Cell_DataType::TYPE_STRING)
               ->setCellValueExplicit('D'.($k+2),isset($v['mobile'])?$v['mobile']:'无',\PHPExcel_Cell_DataType::TYPE_STRING)
               ->setCellValueExplicit('E'.($k+2),isset($v['realname'])?$v['realname']:'无',\PHPExcel_Cell_DataType::TYPE_STRING)
               ->setCellValue('F'.($k+2),$v['shop_name'])
               ->setCellValue('G'.($k+2),isset($v['waiter'])?$v['waiter']:'无')
               ->setCellValue('H'.($k+2),$v['is_online']?'线上商城':'门店收银')
               ->setCellValue('I'.($k+2),isset($v['pay_way'])?$v['pay_way']:'-')
               ->setCellValue('J'.($k+2),$v['subtitle'])
               ->setCellValue('K'.($k+2),$v['price'])
               ->setCellValue('L'.($k+2),$v['num']);
       }
       ob_end_clean();
       header('Content-Type: application/vnd.ms-excel');
       header('Content-Disposition: attachment;filename="'.date("Y-m-d H:i:s",time()).$item_name.' 订单导出.xls"');
       header('Cache-Control: max-age=0');
       $a = new \PHPExcel_Writer_Excel5($phpexcel);
       $a->save('php://output');
   }

    /**
     * [getWorkerList 获取门店员工列表]
     * @return [type] [description]
     */
    public function getWorkerList()
    {
        $shop_id = $this->request->param('shop_id');
        $w_where = [];
        if ($shop_id) {
            $w_where['a.sid'] = ['in',$shop_id];
        }
        $workerlist = model('shop.Worker')->workerList($w_where)->select();
        return outPut(200,'获取成功',$workerlist);
    }

    /**
     * [addOrderGoods 临时方法，添加商品数据]
     */
    public function addOrderGoods()
    {
        $order_id = $this->request->param('id');
        $this->assign('order_id',$order_id);
        $catelist = model('item.ItemCategory')->where(['status'=>1,'pid'=>0])->select();
        $this->assign('catelist',$catelist);
        return view('add_order_goods');
    }

    /**
     * [detail 订单详情]
     * @return [type] [description]
     */
    public function detail()
    {
        $order_id = $this->request->param('id');
        if (empty($order_id)) {
            $order_info = [];
        }else{
            $order_info = (new CollectModel())->getOrderDetailData($order_id);
        }
        $except_role_id = [1,6,7];//不限制的门店管理员角色
        //获取店铺信息(回去根据当前登录的用户来筛选店铺)
        $admin_id = $this->admin_id;
        $role_id = Db::table('ddxm_role_user')->where('user_id','=',$admin_id)->value('role_id');
        if ($admin_id == 1 || in_array($role_id,$except_role_id)) {
            $this->assign('show_cost',1);
        }
        $this->assign('info',$order_info);
        return view('detail');
    }

    /**
     * [refund 退款页面]
     * @return [type] [description]
     */
    public function refund()
    {
        $order_id = $this->request->param('id');
        if (empty($order_id)) {
            $goodsOrServerList = [];
        }else{
            $goodsOrServerList = (new CollectModel())->getGoodsOrServerListToRefund($order_id);
        }
        $pay_way = (new CollectModel())->getPayWay($order_id);
        $order_type = (new CollectModel())->getOrderField($order_id,'order_type');
        $this->assign('order_type',$order_type);
        $this->assign('pay_way',$pay_way);
        $this->assign('order_id',$order_id);
        $this->assign('list',$goodsOrServerList);
        return view('refund');
    }

    //确认退款
    public function queryRefund()
    {
        $params = $this->request->param();
        // var_dump($params['list']);die;
        if (empty($params)) {
            return outPut(301,'请选择退货商品和添加数量');
        }
        $worker_id = Session::get('ADMIN_ID');
        $res = (new CollectModel())->refund($params['list'],$worker_id);
        if ($res['code'] == 200) {
            return outPut(200,'成功');
        }
        return outPut($res['code'],$res['msg'],$res['data']);
    }

    public function getAllMobile()
    {
        // $count = Db::connect(config('ddxx'))->table('tf_member')->count('mobile');
        // echo $count;die;
        $data = Db::connect(config('ddxx'))->table('tf_member')->field('mobile')->select();
        foreach ($data as $v) {
            file_put_contents('mobile.txt',$v['mobile']."\r\n",FILE_APPEND);
        }
        echo 'end';
        // file_put_contents('refunds.txt',json_encode($result),FILE_APPEND);
    }

    //收银台使用体验券
    public function cancelExperienceCard($card_id = 0)
    {
        $card_info = model('user.UserCard')->where(['id'=>$card_id])->find();
        //修改了card表信息后还要添加一条订单
    }
}
