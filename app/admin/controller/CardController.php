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

use cmf\controller\AdminBaseController;
use think\Db;
use app\admin\model\AdminMenuModel;
use app\admin\model\CouponModel;
use app\admin\model\CardModel;
use app\admin\model\shop\ShopModel;
use app\admin\model\shop\WorkerModel;
use app\admin\model\ServiceModel;
use app\admin\model\order\OrderModel;
use think\Session;


/**
 * Class cardController
 * @package app\admin\controller
 * @adminMenuRoot(
 *     'name'   =>'会员卡管理',
 *     'action' =>'menu_default',
 *     'parent' =>'',
 *     'display'=> true,
 *     'order'  => 10000,
 *     'icon'   =>'cogs',
 *     'remark' =>'会员卡管理'
 * )
 */
class CardController extends BaseController
{
    protected $pageLimit = 12;


    /**
     * 会员卡列表（无意义的了）
     * @adminMenu(
     *     'name'   => '会员卡购买列表',
     *     'parent' => 'menu_default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '会员卡列表',
     *     'param'  => ''
     * )
     */
    public function buycard()
    {
        $Card = new CardModel();

        $buycard = $Card->buycard();

        $page = $buycard->render();

        //print_r($card_list);die;
        $this->assign("page", $page);
        $this->assign('buycard', $buycard);
        return $this->fetch();
    }

    //------------------------------------------服务卡区域---------------------------
    public function getPayWay($value)
    {
    	if (empty($value)) {
    		return '未支付';
    	}
        $status = [
            1 => '微信',
            2 => '支付宝',
            3 => '余额',
            4 => '银行卡',
            5 => '现金支付',
            6 => '美团',
            7 => '赠送',
            8 => '门店自用',
            9 => '兑换',
            10 => '包月服务',
            11 => '定制疗程',
            99 => '管理员充值'
        ];
        return $status[$value];
    }

    /**
     * [serviceCardList 服务卡列表]
     * @method serviceCardList
     * @return [type]          [description]
     */
    public function serviceCardList()
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
        if (!empty($status)) {
            $where['status'] = $status;
        }
        if (!empty($shop_id)) {
            $where['shop_id'] = ['in',"$shop_id"];
        }
        $cardModel = new CardModel();
        $voucherlist = $cardModel->where($where)->order('addtime asc');
        $pageParams = [
            'status' => $status,
        ];
        $datas = $this->lists($voucherlist, $pageParams)->toArray();
        $this->assign("file_host", config('file_server_url'));
        $this->assign("list", $datas['data']);
        return view('service_card_list');
    }

    public function cardAddOrUpdate()
    {
        if ($this->request->isPost())
        {
            $param  = $this->request->param();
            $data   = $param['data'];
            $id     = isset($data['id'])?$data['id']:0;
            if ( $id ) { unset($data['id']); }
            $res    = $this->cardUpdate( $data, $id);
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
            $info = (new CardModel())->where(['id'=>$id])->find();
            if ($info['type'] == 2) {
                $info['server_list'] = json_decode($info['server_list'],true);
            }
            $shop_ids = explode(',',$info['shop_id']);
            $service_ids = explode(',',$info['service_id']);
            $this->assign('info',$info);
            $this->assign('shop_ids',$shop_ids);
            $this->assign('service_ids',$service_ids);
            return view('card_edit');
        }else{
            $this->assign('type',$type);
            return view('card_add');
        }
    }

    protected function cardUpdate(array $data = [],$id = 0)
    {
        if (empty($data)){ return ['code'=>301,'msg'=>'参数错误'];}
        if (empty($data['name'])){ return ['code'=>301,'msg'=>'标题必须'];}
        if (!is_numeric($data['restrict_num'])){ $data['restrict_num'] = 0;}
        if (!is_numeric($data['expire_month'])){ $data['expire_date'] = 0;}
        if (empty($data['service_id'])){ return ['code'=>301,'msg'=>'服务券服务项目必须'];}
        if ($data['money'] == ''){ return ['code'=>301,'msg'=>'金额必须'];}
        if (empty($data['type'])){ return ['code'=>301,'msg'=>'服务卡类型必须'];}
        if (empty($data['shop_id'])){ return ['code'=>301,'msg'=>'门店必须'];}
        $cardModel = new CardModel();
        $data['shop_id'] = implode(',',$data['shop_id']);
        $data['service_id'] = !empty($data['service_id'])?implode(',',$data['service_id']):'';
        //判断服务是否重复
        $temp = [];
        if (!empty($data['server_list'])) {
            foreach ($data['server_list'] as $k => $v) {
                if (in_array($v['id'],$temp)) {
                    return ['code'=>301,'msg'=>'不能包含重复的服务项目'];
                }else{
                    array_push($temp,$v['id']);
                }
            }
        }
        $data['server_list'] = !empty($data['server_list'])?json_encode($data['server_list']):json_encode([]);
        if ($id){
            $res = $cardModel->updateDetail($data,$id);
        }else{
            $data['addtime'] = time();
            $res = $cardModel->updateDetail($data);
        }
        if ($res){
            return ['code'=>200,'msg'=>'操作成功'];
        }
        return ['code'=>301,'msg'=>'操作失败或未做修改'];
    }

    /**
     * 删除券
     */
    public function delCard()
    {
        $id = $this->request->param('ticket_id');
        $res = (new CardModel())->where('id',$id)->delete();
        if ($res){
            return outPut(200,'删除成功');
        }
        return outPut(301,'删除失败');
    }

    //根据服务项目获取门店列表
    public function getShopListByServiceId()
    {
        $data = $this->request->param();
        $service_ids = isset($data['service_id'])?$data['service_id']:[];
        $res = (new CardModel())->getShopList($service_ids);
        if ($res){
            return outPut(200,'获取成功',$res);
        }
        return outPut(301,'获取失败');
    }

    //---------------------------------------------用户已购买服务卡区域-----------------------

    public function userBuyServiceCardList()
    {
        $status = $this->request->param('status',1000);
        $SHOPID = $this->request->param('shop_id','');
        $name = $this->request->param('name','');
        $nickname = $this->request->param('nickname','');
        $mobile = $this->request->param('mobile','');
        $workid = $this->request->param('workid','');
        $service_id = $this->request->param('service_id',1000);
        $except_role_id = [1,6,7];//不限制的门店管理员角色
        //获取店铺信息(回去根据当前登录的用户来筛选店铺)
        $admin_id = $this->admin_id;
        $role_id = Db::name('role_user')->where('user_id','=',$admin_id)->value('role_id');
        $shop_id = Session::get('SHOP_ID');
        $show_export = 0;
        if ($shop_id && $admin_id != 1){
            if (in_array($role_id,$except_role_id)) {
                $show_export = 1;
                $shopDatas = ShopModel::all(function($query){
                    $query->field('id,name,code');
                });
            }else{
                $w['id'] = ['in',$shop_id];
                $shopDatas = OrderModel::getShopList($w,1);
            }
        }else{
            if (in_array($role_id,$except_role_id) || $admin_id == 1) {
                $show_export = 1;
            }
            $shopDatas = ShopModel::all(function($query){
                $query->field('id,name,code');
            });
        }
        $this->assign('show_export',$show_export);
        $this->assign('shopDatas',$shopDatas);
        $where = [];
        if ($status != 1000 && $status != '') {
            $where['b.status'] = $status;
        }else{
            $where['b.status'] = ['>',0];
        }
        $this->assign('status',$status);
        if ($name != '') {
            $where['b.card_name'] = ['like',"%$name%"];
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
        if ($service_id != 1000 && $service_id != '') {
            $where['b.service_id'] = $service_id;
        }
        $this->assign('service_id',$service_id);
        if ($SHOPID) {
            $shop_id = $SHOPID;
        }
        if ($shop_id == 18) {
            $shop_id = '';
        }else{
            $where['b.shop_id'] = ['in',$shop_id];
        }
        $this->assign('shop_id',$shop_id);
        $cardModel = new cardModel();
        $list = $cardModel->getBuyServiceCardList($where);
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
            'service_id' =>$service_id
        ];
        $datas = $this->lists($list,$pageParams)->toArray();
        $db = Db::connect(config('ddxx'));
        foreach ($datas['data'] as $k => $v) {
            if ($v['type'] == 1) {
                $service_name = $db->name('service')->where('s_id','in',$v['service_id'])->field('sname')->select();
    			$datas['data'][$k]['service_name'] = arrayFieldTransferString($service_name,'sname');
            }else{
                $server_list = json_decode($v['server_list'],true);
                $have_str = '';
                $residue_str = '';//剩余的服务次数
                foreach ($server_list as $kk => $vv) {
                    $service_name = $db->name('service')->where('s_id',$vv['id'])->value('sname');
                    $service_id = $db->name('service')->where('s_id',$vv['id'])->value('id');
                    //统计已用次数
                    $use_num = $db->name('yuyue')->where(['user_card_id'=>$v['id'],'type'=>$service_id])->count();
                    $once = (int)$vv['num'] - $use_num;
                    $residue_str .= $service_name.': 剩余'.$once.'次;';
                    $have_str .= $service_name.$vv['num'].'次;';
                }
                $datas['data'][$k]['service_name'] = $have_str;
                $datas['data'][$k]['residue_service'] = $residue_str;
            }
            $datas['data'][$k]['pay_way'] = $this->getPayWay($v['pay_way']);
        }
        $this->assign('list' , $datas['data']);
        $this->assign('worker_list',$worker_list);
        $this->assign('server_list',$shop_server_list);
        return view('buy_card_list');
    }

    /**
     * 删除服务卡
     */
    public function banServiceCard()
    {
        $id = $this->request->param('card_id');
        $res = (new CardModel())->banServiceCard($id);
        if ($res){
            return outPut(200,'禁用成功');
        }
        return outPut(301,'禁用失败');
    }

    /**
     * 激活服务卡
     */
    public function activeServiceCard()
    {
        $id = $this->request->param('card_id');
        $res = (new CardModel())->activeServiceCard($id);
        if ($res){
            return outPut(200,'激活成功');
        }
        return outPut(301,'激活失败');
    }

    /**
     * [getServiceCardServer 获取服务卡包含的服务项目]
     * @method getServiceCardServer
     * @return [type]               [description]
     */
    public  function getServiceCardServer()
    {
        $service_card_id = $this->request->param('card_id');
        $server_list = (new CardModel())->getCardServer($service_card_id);
        if (empty($server_list)) {
            return outPut(301,'没有服务项目可选');
        }
        return outPut(200,'获取成功',$server_list);
    }

    /**
     * [getServiceCardServerRecord 获取服务卡的服务记录]
     * @method getServiceCardServerRecord
     * @return [type]               [description]
     */
    public function getServiceCardServerRecord()
    {
        $service_card_id = $this->request->param('id');
        $server_list = (new CardModel())->getCardServerRecord($service_card_id);
        if ($server_list != null) {
            return outPut(200,'获取成功',$server_list);
        }
        return outPut(301,'没有服务记录');
    }

    //销服务卡
    public function checkServiceCard()
    {
        $params = $this->request->param();
        if (empty($params)) {
            return outPut(301,'参数错误');
        }
        if (!isset($params['service_id']) || $params['service_id'] == null) {
            return outPut(301,'参数错误');
        }
        if (!isset($params['workid']) || empty($params['workid'])) {
            return outPut(301,'参数错误');
        }
        if (!isset($params['id']) || empty($params['id'])) {
            return outPut(301,'参数错误');
        }
        $res = (new CardModel())->checkServiceCard($params);
        if ($res['code'] == 200) {
            return outPut(200,'销卡（服务）成功');
        }
        return outPut(301,$res['msg'],$res['data']);
    }

    //导出明细的列表
    public function useServiceCardRecord()
    {
        $search_type = $this->request->param('search_type');//是否导出操作
        // //当存在时间段的时候要加上时间段条件(默认时昨天)
        $stime = trim($this->request->param('start_time'));
        $etime = trim($this->request->param('end_time'));
        $start = strtotime($stime);
        $end = strtotime($etime) + 24*3600 -1;

        $mobile = trim($this->request->param('mobile'));
        $shop_id = trim($this->request->param('shopid'));
        $workid = trim($this->request->param('workid'));
        $card_type = trim($this->request->param('card_type'));
        $where = [];
        $where['user_card_id'] = ['>',0];
        if ($stime && $etime) {
            $where['a.yytime'] = ['between',[$start,$end]];
        }elseif ($start) {
            $end = strtotime(date('Y-m-d',time())) + 24*3600 -1;
            $where['a.yytime'] = ['between',[$start,$end]];
        }elseif ($etime){
            $start = 0;
            $where['a.yytime'] = ['between',[$start,$end]];
        }
        $this->assign('start_time',$stime);
        $this->assign('end_time',$etime);
        if ($mobile) {
            $where['m.mobile'] = $mobile;
        }
        $this->assign('mobile',$mobile);
        if ($workid) {
            $where['a.workid'] = $workid;
        }
        $this->assign('workid',$workid);
        if ($shop_id) {
            $where['a.sid'] = $shop_id;
        }
        $this->assign('shop_id',$shop_id);
        if ($card_type) {
            $where['d.type'] = $card_type;
        }
        $this->assign('card_type',$card_type);
        if ($search_type == 2) {
            set_time_limit(0);
            $list = (new cardModel())->getUsedCardServerRecord($where)->select();
            $phpexcel = new \PHPExcel();
            $phpexcel->getActiveSheet()
                ->setCellValue('A1','服务卡ID')
                ->setCellValue('B1','会员名称')
                ->setCellValue('C1','会员账号')
                ->setCellValue('D1','服务卡名称')
                ->setCellValue('E1','支付方式')
                ->setCellValue('F1','服务卡类型')
                ->setCellValue('G1','服务时间')
                ->setCellValue('H1','所属门店')
                ->setCellValue('I1','服务人员')
                ->setCellValue('J1','服务项目')
                ->setCellValue('K1','服务价格');
            foreach ($list as $k=>$v){
                $phpexcel->getActiveSheet()->setCellValue('A'.($k+2),$v['id'])
                    ->setCellValueExplicit('B'.($k+2),$v['nickname'],\PHPExcel_Cell_DataType::TYPE_STRING)
                    ->setCellValueExplicit('C'.($k+2),$v['mobile'],\PHPExcel_Cell_DataType::TYPE_STRING)
                    ->setCellValueExplicit('D'.($k+2),$v['card_name'],\PHPExcel_Cell_DataType::TYPE_STRING)
                    ->setCellValueExplicit('E'.($k+2),$v['card_type']==1?"包月卡":"次卡",\PHPExcel_Cell_DataType::TYPE_STRING)
                    ->setCellValueExplicit('F'.($k+2),$this->getPayWay($v['pay_way']),\PHPExcel_Cell_DataType::TYPE_STRING)
                    ->setCellValueExplicit('G'.($k+2),date('Y-m-d H:i:s',$v['yytime']),\PHPExcel_Cell_DataType::TYPE_STRING)
                    ->setCellValueExplicit('H'.($k+2),$v['shop_name'],\PHPExcel_Cell_DataType::TYPE_STRING)
                    ->setCellValueExplicit('I'.($k+2),$v['work_name'],\PHPExcel_Cell_DataType::TYPE_STRING)
                    ->setCellValueExplicit('J'.($k+2),$v['sname'],\PHPExcel_Cell_DataType::TYPE_STRING)
                    ->setCellValueExplicit('K'.($k+2),$v['price'],\PHPExcel_Cell_DataType::TYPE_STRING);
            }
            ob_end_clean();
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.date("YmdHis",time()).' 服务卡消耗明细.xls"');
            header('Cache-Control: max-age=0');
            $a = new \PHPExcel_Writer_Excel5($phpexcel);
            $a->save('php://output');
        }else{
            $shop_list = ShopModel::all(function($query){
                $query->field('id,name,code');
            });
            $used_card_record = (new cardModel())->getUsedCardServerRecord($where);
            $datas = $this->lists($used_card_record,[])->toArray();
            foreach ($datas['data'] as $kk => $vv) {
                $datas['data'][$kk]['pay_way'] = $this->getPayWay($vv['pay_way']);
            }
            $this->assign('list',$datas['data']);
            $this->assign('shop_list',$shop_list);
            return view('export');
        }

    }

    /**
     * 获取当前卡券上架的门店
     */
    public function getBindShopList()
    {
        $id = $this->request->param('id');
        $shop_ids = (new CardModel())->where('id',$id)->value('shop_id');
        $shop_list = model('shop.Shop')->where(['id'=>['in',$shop_ids]])->field('name')->select();
        if ($shop_list){
            return outPut(200,'获取成功',$shop_list);
        }
        return outPut(301,'没有门店信息');
    }

    /**
     * 根据门店获取服务人员
     */
    public function getWorkerByShop()
    {
        $id = $this->request->param('shop_id',0);
        if (!$id) {
            return outPut(301,'没有服务人员信息');
        }
        $worker_list = model('shop.Worker')->where(['sid'=>$id,'status'=>1])->field('workid,name')->select();
        if ($worker_list){
            return outPut(200,'获取成功',$worker_list);
        }
        return outPut(301,'没有服务人员信息');
    }

    //--------------------------------------------优惠券区域------------------------

    /**
     * [exportUserList 导出所有用户数据]
     * @return [type] [description]
     */
    public function exportBuycardList()
    {
        set_time_limit(0);
        $list = Db::connect(config('ddxx'))
            ->name('card')
            ->join('tf_shop','tf_card.shop_id=tf_shop.id')
            ->join('tf_member','tf_card.member_id=tf_member.id')
            ->field("tf_card.id,tf_card.title,tf_card.price,tf_card.addtime,tf_card.paytime,tf_member.mobile,tf_member.nickname,tf_shop.name")
            ->where('paytime','>',0)
            ->where('shop_id','=',21)
            ->order("id DESC")
            ->select();
        // var_dump($user_list);die;
        $phpexcel = new \PHPExcel();
        $phpexcel->getActiveSheet()
            ->setCellValue('A1','ID')
            ->setCellValue('B1','卡名称')
            ->setCellValue('C1','金额')
            ->setCellValue('D1','购买用户电话')
            ->setCellValue('E1','会员昵称')
            ->setCellValue('F1','店铺')
            ->setCellValue('G1','支付时间');
        foreach ($list as $k=>$v){
            $phpexcel->getActiveSheet()->setCellValue('A'.($k+2),$v['id'])
                ->setCellValueExplicit('B'.($k+2),$v['title'],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('C'.($k+2),$v['price'],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('D'.($k+2),$v['mobile'],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('E'.($k+2),$v['nickname'],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('F'.($k+2),$v['name'])
                ->setCellValue('G'.($k+2),date('Y-m-d H:i:s',$v['paytime']));
        }
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.date("YmdHis",time()).' 会员购卡列表.xls"');
        header('Cache-Control: max-age=0');
        $a = new \PHPExcel_Writer_Excel5($phpexcel);
        $a->save('php://output');
    }

    /**
     * 优惠券列表
     * @adminMenu(
     *     'name'   => '优惠券列表',
     *     'parent' => 'menu_default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '优惠券列表',
     *     'param'  => ''
     * )
     */
    public function getCouponList()
    {
        //查询shop_id,shop_code  限制默认显示的内容d
        $shop_id = Session::get('SHOP_ID');
        $except_role_id = [1,6];//不限制的门店管理员角色
        //获取店铺信息(回去根据当前登录的用户来筛选店铺)
        $admin_id = $this->admin_id;
        $role_id = Db::table('ddxm_role_user')->where('user_id','=',$admin_id)->value('role_id');
        if ($shop_id && $admin_id != 1){
            $w['id'] = ['in',$shop_id];
            $shopDatas = model('order.Order')->getShopList($w,1);
        }elseif (in_array($role_id,$except_role_id)) {
            $shopDatas = ShopModel::all(function($query){
                $query->field('id,name,code');
            });
        }else{
            $shopDatas = ShopModel::all(function($query){
                $query->field('id,name,code');
            });
        }

        //搜索数据
        $where = [];
        $where['a.is_coup'] = 1;
        $where['a.status'] = ["neq",-1];
        $shop_Id = trim($this->request->param('shop_id',''));
        $name = trim($this->request->param('name',''));

        if ($shop_Id != null) {
            $where['a.sid'] = $shop_Id;
        }else if (isset($shop_id) && $admin_id != 1 && !in_array($role_id,$except_role_id) ) {
            $where['a.sid'] = ['in',$shop_id];
        }
        $this->assign('shop_id',$shop_Id);

        if ($name) {
            $where['a.title'] = ['like',"%$name%"];
        }
        $this->assign('name',$name);

        $userCardModel = model('user.CardInfo');
        $userCardDatas = $userCardModel->getUserCoupList($where);
        $pageParams = [
            'shop_id' => $shop_Id,
            'name' => $name
        ];
        $datas = $this->lists($userCardDatas,$pageParams)->toArray();
        foreach ($datas['data'] as $k => $v){
            $datas['data'][$k]['thumb'] = config('file_server_url').$v['thumb'];
        }
        $this->assign('shop_list',$shopDatas);
        $this->assign('coupon_list' , $datas['data']);

        return $this->fetch('coupon/coupon_list');
    }



}
