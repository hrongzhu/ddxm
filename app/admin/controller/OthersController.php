<?php
/**
 * Author: chenjing
 * Date: 2018/1/11
 * Description:
 */

namespace app\admin\controller;

use app\admin\model\AdminUserModel;

use app\admin\model\order\OrderModel;
use app\admin\model\user\UserCardModel;
use app\admin\model\user\UserModel;
use app\admin\model\shop\ShopModel;
use app\admin\model\item\ItemCategoryModel;
use think\Db;
use think\Request;
use think\Session;
use think\View;


/**
 * Class OthersController
 * @package app\admin\controller
 * @adminMenuRoot(
 *     'name'   =>'杂项管理',
 *     'action' =>'menuDefault',
 *     'parent' =>'',
 *     'display'=> true,
 *     'order'  => 10000,
 *     'icon'   =>'cogs',
 *     'remark' =>'杂项管理'
 * )
 */
class OthersController extends BaseController
{
    /**
     * 短信通知号码列表
     * @adminMenu(
     *     'name'   => '短信通知号码列表',
     *     'parent' => 'menuDefault',
     *     'display'=> true,
     *	   'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '短信通知号码列表',
     *     'param'  => ''
     * )
     */
    public function mobileList()
    {
        $mobileList = Db::connect(config('ddxx'))->table('tf_notice_phone')->select();
        // 模板变量赋值
        $this->assign('List',$mobileList);
        return view('mobile_list');
    }
    //添加电话
    public function addMobile()
    {
        if ($_POST){
            $data= $this->request->param();
            $data['addtime'] = time();
            if (empty($data)){
                return outPut(301,'参数错误');
            }
            $res = Db::connect(config('ddxx'))->table('tf_notice_phone')->insert($data);
            if ($res){
                return outPut(200,'添加成功');
            }
            return outPut(301,'添加失败');
        }else{
            return view('add_mobile');
        }
    }

    //删除电话
    public function delMobile()
    {
        $id= $this->request->param('id','');
        if (empty($id)){
            return outPut(301,'参数错误');
        }
        $res = Db::connect(config('ddxx'))->table('tf_notice_phone')->where('id',$id)->delete();
        if ($res){
            return outPut(200,'删除成功');
        }
        return outPut(301,'删除失败');
    }

    /**
     * 销卡
     * @adminMenu(
     *     'name'   => '销卡',
     *     'parent' => 'menuDefault',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '销卡',
     *     'param'  => ''
     * )
     */
    public function registrationCard()
    {
        if ($_POST){
            $data= $_POST;
            if (empty($data)){
                return outPut(301,'参数错误');
            }
            if (!is_numeric($data['type'])){
                return outPut(301,'请选择服务类型');
            }
            $uid = Db::connect(config('ddxx'))->table('tf_member')->where(['mobile'=>$data['mobile']])->value('id');
            $shop_id = Db::connect(config('ddxx'))->table('tf_card')->where(['id'=>$data['card_id']])->value('shop_id');
            $workid = Db::connect(config('ddxx'))->table('tf_worker')->where(['id'=>$data['worker_id']])->value('workid');
            $yyname = Db::connect(config('ddxx'))->table('tf_worker')->where(['id'=>$data['worker_id']])->value('name');
            if (empty($data['xk_time'])) {
                $times = time();
            }else{
                $times = strtotime($data['xk_time']);
            }
            //预约数据
            $yuyue['addtime']       = $times;
            $yuyue['paytime']       = $times;
            $yuyue['workid']        = $workid;
            $yuyue['workerid']      = $data['worker_id'];
            $yuyue['name']          = $yyname;
            $yuyue['yytime']        = 0;
            $yuyue['type']          = $data['type'];
            $yuyue['cardid']        = $data['card_id'];
            $yuyue['sid']           = $shop_id;
            $yuyue['status']        = 1;
            $yuyue['member_id']     = $uid;
            $yuyue['isdel']         = 0;

            //卡数据
            $my_num = Db::connect(config('ddxx'))->table('tf_card')->where(['id'=>$data['card_id']])->value('my_num');
            if ($my_num < $data['kmoney']){
                return outPut(301,'卡内余额不足');
            }
            $card_datas['my_num'] = $my_num - $data['kmoney'];//扣钱
            /*****************************************************************/
            $order['shop_id']   = $shop_id;
            $order['member_id'] = $uid;
            $order['card_id']   = $data['card_id'];
            $order['sn']        = time().rand(10,99);
            $order['amount']    = $data['kmoney'];
            $order['type']      = 2;
            $order['pay_way']   = 3;
            $order['mobile']    = $data['mobile'];
            $order['addtime']   = $times;
            $order['paytime']   = $times;
            $order['is_online'] = 0;
            //事物操作
            Db::startTrans();
            $orders = new OrderModel();
            $orders->startTrans();
            try{
                $res = Db::connect(config('ddxx'))->table('tf_yuyue')->insert($yuyue);
                $order['yy_id'] = Db::connect(config('ddxx'))->table('tf_yuyue')->getLastInsID();
                $o_res = $orders->allowField(true)->save($order);
                $res = Db::connect(config('ddxx'))->table('tf_card')->where(['id'=>$data['card_id']])->update($card_datas);
                // 提交事务
                Db::commit();
                $orders->commit();
                return outPut(200,'操作成功');
            }catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                $orders->rollback();
                return outPut(301,'操作失败');
            }
        }else{
            $catelist = (new ItemCategoryModel())->where(['status'=>1,'pid'=>0])->select();
            $this->assign('catelist',$catelist);
            return view('registration');
        }
    }

    //获取2级分类列表
    public function getGoodsCateList()
    {
        $cate_id = $_GET['cateid'];
        $catelist = (new ItemCategoryModel())->where(['status'=>1,'pid'=>$cate_id])->select();
        if ($catelist) {
            return outPut(200,'获取成功',$catelist);
        }
        return outPut(301,'获取失败');

    }

    // 获取用户卡片列表
    public function getCardList()
    {
        $mobile     = empty($_GET['mobile'])?'':$_GET['mobile'];
        $uid = (new UserModel)->where(['mobile'=>$mobile])->value('id');//user_id
        $nickname = (new UserModel)->where(['mobile'=>$mobile])->value('nickname');//user_id
        $shop_code = (new UserModel)->where(['id'=>$uid])->value('shop_code');
        $cardinfo = Db::connect(config('ddxx'))->table('tf_card')->field('id,shop_id')->where(['member_id'=>$uid])->find();
        $shop_id = $cardinfo['shop_id'];
        if (empty($shop_id)) {
            $shop_id = Db::connect(config('ddxx'))->table('tf_shop')->where(['code'=>$shop_code])->value('id');//门店id
        }
        //获取门店员工
        $workerlist = Db::connect(config('ddxx'))->table('tf_worker')->field('id,name,type')->where(['sid'=>$shop_id,'status'=>1])->select();
        foreach ($workerlist as $k => $v) {
            if ($v['type'] == 0) {
                $workerlist[$k]['type'] = '婴儿游泳';
            }elseif($v['type'] == 1){
                $workerlist[$k]['type'] = '小儿推拿';
            }else{
                $workerlist[$k]['type'] = '成人推拿';
            }
        }
        //获取卡的条件
        $where              = [];
        $where['a.status']  = 1;
        $where['member_id'] = $uid;
        $where['isdel']     = 0;
        $cardlist = (new UserCardModel())->getUserCardList($where);
        $clist['list'] = $cardlist;
        $clist['worker'] = $workerlist;
        $clist['nickname'] = $nickname;
        if (!empty($cardlist)) {
            return outPut(200,'获取成功',$clist);
        }
        return outPut(300,'没有数据');
    }

    // 获取分类下的商品列表
    public function getGoodsList()
    {
        // // 获取商品的分类条件
        $cate_id     = empty($_GET['cate_id'])?0:$_GET['cate_id'];
        $goodslist = [];
        $goodslist = Db::connect(config('ddxx'))
            ->table('tf_item_attr')
            ->alias('a')
            ->join('tf_item b','a.item_id = b.id')
            ->join('tf_item_category c','c.id = b.type')
            ->field('a.id,a.attr_pic,a.attr_name,a.attr_names,a.price,b.title,c.cname,b.addtime')
            ->where('b.type','=',$cate_id)
            ->where(['a.status'=>1,'b.status'=>1])
            ->select();
        if (!empty($goodslist)) {
            foreach ($goodslist as &$v) {
                $v['attr_pic'] = config('domino.domino').$v['attr_pic'];
            }
            return outPut(200,'获取成功',$goodslist);
        }
        return outPut(300,'没有数据');
    }


    // 获取门店列表
    public function getShopList()
    {
        $mobile = $_GET['mobile'];
        $shopslist['list'] = Db::connect(config('ddxx'))
            ->table('tf_shop')
            ->field('id,name')
            ->where(['status'=>1])
            ->select();
        $shopslist['nickname'] = (new UserModel)->where(['mobile'=>$mobile])->value('nickname');//user_name
        if (!empty($shopslist)) {
            return outPut(200,'获取成功',$shopslist);
        }
        return outPut(300,'没有数据');
    }

    //销商品（有用户的情况下）
    public function registrationGoods()
    {
        $data = isset($_POST)?$_POST:[];
        if (empty($data)) {
            return outPut(301,'请填写数据');
        }
        // var_dump($data);die;
        $mobile = $data['mobile'];
        $shopids = isset($data['shop_id'])?$data['shop_id']:'';
        $price = $data['zprice'];
        $card_id = isset($data['card_id'])?$data['card_id']:'';
        $goods_list = isset($data['goods_list'])?$data['goods_list']:[];
        $xd_time = isset($data['xd_date'])?$data['xd_date']:'';
        $arr = [];
        if (!empty($goods_list)) {
            //处理数据
            $i = 0;
            foreach ($goods_list as $k => $v) {
                if ($k % 2 == 0) {
                    $arr[$i]['goods_id'] = $goods_list[$k];
                    $arr[$i]['num'] = $goods_list[$k+1];
                }
                $i++;
            }
        }
        if ($xd_time) {
            $times = strptime($xd_time);
        }else{
            $times = time();
        }
        //查询用户相关数据
        $order_data = [];//订单数组
        //1.收货地址 2用户id，姓名等
        $uinfo =  (new UserModel())->where(['mobile'=>$mobile])->field('id,nickname,shop_code as s_code')->find();//user信息
        if (empty($shopids)) {
            if ($card_id) {
                $shopid = Db::connect(config('ddxx'))->table('tf_card')->where(['id'=>$card_id])->value('shop_id');
                $shop_id = $shopid;
            }
            if (empty($shop_id) && isset($uinfo->s_code)) {
                $shop_id = Db::connect(config('ddxx'))->table('tf_shop')->where(['code'=>$uinfo->s_code])->value('id');//门店id
                $shop_id = $shop_id;
            }
        }else{
            $shop_id = $shopids;
        }

        $order_data['shop_id']      = empty($shop_id)?0:$shop_id;
        $order_data['member_id']    = isset($uinfo->id)?$uinfo->id:0;
        $order_data['mobile']       = $mobile;
        $order_data['type']         = 1;
        $order_data['sn']           = $times.rand(10,99);
        $order_data['pay_sn']       = '42000000'.date('w').date('Ymd').rand(10000,99999).rand(10000,99999);
        $order_data['amount']       = $price;
        $order_data['pay_status']   = 1;
        if ($card_id) {
            $order_data['pay_way']  = 3;
            $order_data['card_id']  = $card_id;
        }else{
            $order_data['pay_way']  = 1;
        }
        $order['is_online']         = 0;
        $order_data['order_status'] = 2;
        $order_data['paytime']      = $times;
        $order_data['addtime']      = $times;
        $order_data['overtime']     = $times;
        $order_data['send_way']     = 1;
        $order_data['postage']      = 0;
        $order_data['send_way']     = 1;

        $order_data['realname']         = empty($uinfo->nickname)?'匿名用户':$uinfo->nickname;
        $order_data['detail_address']   = '无(通过收银台操作)';
        //要添加数据的表
        //1 订单表，2订单商品表
        //插入订单信息
        //事物操作
        Db::startTrans();
        $orders = new OrderModel();
        $orders->startTrans();
        if (empty($card_id) || !empty($shopids)) {
            $shopmodel = new ShopModel();
            $shopmodel->startTrans();
        }
        try{
            $orders->allowField(true)->save($order_data);
            $order_id = $orders->id;
            if (empty($card_id)) {
                $res = $shopmodel->get($shop_id);
                $shop_money = $res->amount + $price;
                $shopmodel->allowField(true)->save(['amount'=>$shop_money],['id'=>$res->id]);
            }elseif(!empty($shopids)){
                $res = $shopmodel->get($shopids);
                $shop_money = $res->amount + $price;
                $shopmodel->allowField(true)->save(['amount'=>$shop_money],['id'=>$res->id]);
            }else{
                $my_num = Db::connect(config('ddxx'))->table('tf_card')->where(['id'=>$card_id])->value('my_num');
                if ($my_num < $price) {
                    return outPut(301,'当前卡余额不足');
                }
                $my_num = $my_num - $price;
                Db::connect(config('ddxx'))->table('tf_card')->where(['id'=>$card_id])->update(['my_num'=>$my_num]);
            }
            if (!empty($arr)) {
                //循环添加商品信息
                foreach ($arr as $v) {
                    $goods_info = Db::connect(config('ddxx'))->table('tf_item_attr')
                            ->alias('a')
                            ->join('tf_item b','a.item_id = b.id')
                            ->field('a.id,a.attr_name,a.price,b.title,b.id as item_id,b.type')
                            ->where('a.id','=',$v['goods_id'])
                            ->where(['a.status'=>1])
                            ->find();
                    $goods_infos['order_id'] = $order_id;
                    $goods_infos['ch_attr_id'] = $goods_info['id'];
                    $goods_infos['ch_id'] = $goods_info['item_id'];
                    $goods_infos['attr_name'] = $goods_info['attr_name'];
                    $goods_infos['subtitle'] = $goods_info['title'];
                    $goods_infos['category_id'] = $goods_info['type'];
                    $goods_infos['num'] = empty($v['num'])?1:$v['num'];
                    $goods_infos['price'] = $goods_info['price'];
                    Db::connect(config('ddxx'))->table('tf_order_goods')->insert($goods_infos);

                }
            }
            // 提交事务
            Db::commit();
            $orders->commit();
            if (empty($card_id) || !empty($shopids)) {
                $shopmodel->commit();
            }
            return outPut(200,'操作成功');
        }catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $orders->rollback();
            if (empty($card_id) || !empty($shopids)) {
                $shopmodel->rollback();
            }
            return outPut(301,'操作失败');
        }

    }


    //销商品（有用户的情况下）
    public function registrationGoods1()
    {
        $data = isset($_POST)?$_POST:[];
        if (empty($data)) {
            return outPut(301,'请填写数据');
        }
        $order_id = $data['order_id'];
        $goods_list = isset($data['goods_list'])?$data['goods_list']:[];
        $arr = [];
        if (!empty($goods_list)) {
            //处理数据
            foreach ($goods_list as $k => $v) {
                $arr[$k]['goods_id'] = $v[0];
                $arr[$k]['num'] = $v[1];
                $arr[$k]['p_price'] = $v[2];
            }
        }else{
            return outPut(301,'请选择商品');
        }
        try{
            //循环添加商品信息
            foreach ($arr as $v) {
                $goods_info = Db::connect(config('ddxx'))->table('tf_item_attr')
                    ->alias('a')
                    ->join('tf_item b','a.item_id = b.id')
                    ->field('a.id,a.attr_pic,a.attr_name,a.attr_names,a.price,a.oldprice,b.title,b.id as item_id,b.type,b.type_id')
                    ->where('a.id','=',$v['goods_id'])
                    ->where(['a.status'=>1])
                    ->find();
                //查询改价信息
                $disprice = Db::connect(config('ddxx'))->name('discount')
                    ->where(['order_id'=>$order_id,'goods_id'=>$v['goods_id']])
                    ->field('price,disprice')
                    ->find();
                if ($disprice) {
                    $price = $disprice['price'] - $disprice['disprice'];
                }elseif($v['p_price']){
                    $price = $v['p_price'];
                }else{
                    $price = $goods_info['price'];
                }
                $goods_infos['order_id'] = $order_id;
                $goods_infos['ch_attr_id'] = $goods_info['id'];
                $goods_infos['ch_id'] = $goods_info['item_id'];
                $goods_infos['attr_name'] = $goods_info['attr_name'];
                $goods_infos['attr_pic'] = $goods_info['attr_pic'];
                $goods_infos['subtitle'] = $goods_info['title'].$goods_info['attr_name'].$goods_info['attr_names'];
                $goods_infos['category_id'] = $goods_info['type'];
                $goods_infos['type_id'] = $goods_info['type_id'];
                $goods_infos['num'] = empty($v['num'])?1:$v['num'];
                $goods_infos['price'] = $price;
                $goods_infos['oprice'] = $goods_info['oldprice'];
                Db::connect(config('ddxx'))->table('tf_order_goods')->insert($goods_infos);
            }
            // 提交事务
            Db::commit();
            return outPut(200,'操作成功');
        }catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            var_dump($e);die;
            return outPut(301,'操作失败');
        }

    }

    public function getSongDaItemList()
    {
        $db = Db::connect(config('ddxx'));
        $list = $db->name('order_goods')->alias('a')
            ->join('tf_order b','a.order_id = b.id','LEFT')
            ->join('tf_shop s','b.shop_id = s.id','LEFT')
            ->join('tf_member m','b.member_id = m.id','LEFT')
            ->join('tf_level l','m.level_id = l.id','LEFT')
            ->where('b.addtime','>','1520438400')
            ->where('b.paytime','>',0)
            ->field('s.name,m.nickname,m.mobile,l.level_name,a.subtitle,a.price,a.og_price,a.num,a.status,b.addtime')
            ->select();
        $phpexcel = new \PHPExcel();
        $phpexcel->getActiveSheet()
            ->setCellValue('A1','所属门店')
            ->setCellValue('B1','会员账号')
            ->setCellValue('C1','会员昵称')
            ->setCellValue('D1','会员等级')
            ->setCellValue('E1','商品名称')
            ->setCellValue('F1','原价')
            ->setCellValue('G1','结算价')
            ->setCellValue('H1','数量')
            ->setCellValue('I1','小计')
            ->setCellValue('J1','下单时间');
        foreach ($list as $k=>$v){
            $phpexcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$v['name'],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('B'.($k+2),$v['mobile'],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('C'.($k+2),$v['nickname'],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('D'.($k+2),$v['level_name'],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('E'.($k+2),$v['subtitle'],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('F'.($k+2),$v['og_price'])
                ->setCellValue('G'.($k+2),$v['price'])
                ->setCellValue('H'.($k+2),$v['num'])
                ->setCellValue('I'.($k+2),$v['price'] * $v['num'])
                ->setCellValueExplicit('J'.($k+2),date('Y-m-d H:i:s',$v['addtime']),\PHPExcel_Cell_DataType::TYPE_STRING);
        }
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.date("YmdH:i",time()).' 商品信息.xls"');
        header('Cache-Control: max-age=0');
        $a = new \PHPExcel_Writer_Excel5($phpexcel);
        $a->save('php://output');
    }


    public function getSongDaItemList1()
    {
        $db = Db::connect(config('ddxx'));
        $list = $db->name('order_goods')->alias('a')
            ->join('tf_order b','a.order_id = b.id','LEFT')
            ->join('tf_shop s','b.shop_id = s.id','LEFT')
            ->field('s.name,a.subtitle,a.price,a.og_price,a.num,a.status,b.addtime')
            ->where('a.subtitle','like',"%松达%")
            ->where('b.addtime','between',[1543593600,1546271999])
            ->select();
        foreach ($list as &$v){
            $v['addtime'] = date('Y-m-d H:i:s',$v['addtime']);
            $v['status'] = $v['status']== -1?'退款':'正常';
        }
        $phpexcel = new \PHPExcel();
        $phpexcel->getActiveSheet()
            ->setCellValue('A1','门店')
            ->setCellValue('B1','名称')
            ->setCellValue('C1','原价')
            ->setCellValue('D1','结算价')
            ->setCellValue('E1','数量')
            ->setCellValue('F1','小计')
            ->setCellValue('G1','状态')
            ->setCellValue('H1','时间');
        foreach ($list as $k=>$v){
            $phpexcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$v['name'],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('B'.($k+2),$v['subtitle'],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('C'.($k+2),$v['og_price'])
                ->setCellValue('D'.($k+2),$v['price'])
                ->setCellValue('E'.($k+2),$v['num'])
                ->setCellValue('F'.($k+2),$v['price'] * $v['num'])
                ->setCellValueExplicit('G'.($k+2),$v['status'],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('H'.($k+2),$v['addtime'],\PHPExcel_Cell_DataType::TYPE_STRING);
        }
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.date("YmdH:i",time()).' 松达商品信息.xls"');
        header('Cache-Control: max-age=0');
        $a = new \PHPExcel_Writer_Excel5($phpexcel);
        $a->save('php://output');
    }

    //获取水浴次数
    public function getSwimWithServiceList()
    {
        $db = Db::connect(config('ddxx'));
        $list = $db->name('yuyue')->alias('a')
            ->join('tf_order o','a.order_id = o.id','LEFT')
            ->join('tf_member b','a.member_id = b.id','LEFT')
            ->join('tf_shop s','a.sid = s.id','LEFT')
            ->field('s.name,b.nickname,b.mobile,a.price,a.status,a.type,a.addtime')
            ->where('o.pay_way','=',7)
            ->where('a.status','>=',0)
            ->where('a.member_id','>',0)
            ->where('a.addtime','>=',1538323200)
            ->select();
        $status = [
            0 => '预约中',
            1 => '完成',
            2 => '过期',
            3 => '取消',
            -1 => '退款'
        ];
        $types = $db->name('service')->where('status','=',1)->field('id,sname')->select();
        $type_list = changeKeyTransferVal($types,'id','sname');
        $phpexcel = new \PHPExcel();
        $phpexcel->getActiveSheet()
            ->setCellValue('A1','门店')
            ->setCellValue('B1','服务名称')
            ->setCellValue('C1','用户名')
            ->setCellValue('D1','电话')
            ->setCellValue('E1','小计')
            ->setCellValue('F1','状态')
            ->setCellValue('G1','时间');
        foreach ($list as $k=>$v){
            $phpexcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$v['name'],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('B'.($k+2),$type_list[$v['type']],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('C'.($k+2),$v['nickname'],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('D'.($k+2),$v['mobile'])
                ->setCellValue('E'.($k+2),$v['price'])
                ->setCellValueExplicit('F'.($k+2),$status[$v['status']],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('G'.($k+2),date('Y-m-d H:i:s',$v['addtime']),\PHPExcel_Cell_DataType::TYPE_STRING);
        }
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.date("YmdH:i",time()).' 门店游泳消费信息.xls"');
        header('Cache-Control: max-age=0');
        $a = new \PHPExcel_Writer_Excel5($phpexcel);
        $a->save('php://output');
    }

    public function gteRechargeOrderList()
    {
        $where['a.paytime'] = ['>=',1543593600];
        $orderListDatas = (new OrderModel())->getRechargeOrderListDatas($where)->select();
        $phpexcel = new \PHPExcel();
        $phpexcel->getActiveSheet()
            ->setCellValue('A1','门店')
            ->setCellValue('B1','名称')
            ->setCellValue('C1','会员昵称')
            ->setCellValue('D1','会员账号')
            ->setCellValue('E1','来源')
            ->setCellValue('F1','支付方式')
            ->setCellValue('G1','充值金额')
            ->setCellValue('H1','支付时间')
            ->setCellValue('I1','第一次充值时间')
            ->setCellValue('J1','状态');
        foreach ($orderListDatas as $k=>$v){
            $first_id = (new OrderModel())->where(['member_id'=>$v['member_id'],'isDel'=>0,'type'=>3,'pay_status'=>1,'card_id'=>0])->min('id');
            $first_time = (new OrderModel())->where('id',$first_id)->value('paytime');

            $phpexcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$v['shop_id'],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('B'.($k+2),'充值',\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('C'.($k+2),$v['nickname'],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('D'.($k+2),$v['mobile'],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('E'.($k+2),$v['is_online']==1?'线上支付':'门店充值',\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('F'.($k+2),$v['pay_way'],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('G'.($k+2),$v['amount'],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('H'.($k+2),date('Y-m-d H:i:s',$v['paytime']),\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('I'.($k+2),date('Y-m-d H:i:s',$first_time),\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('J'.($k+2),$v['check_status']==1?'已对账':'未对账',\PHPExcel_Cell_DataType::TYPE_STRING);
        }
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.date("YmdH:i",time()).' 充值信息.xls"');
        header('Cache-Control: max-age=0');
        $a = new \PHPExcel_Writer_Excel5($phpexcel);
        $a->save('php://output');
    }
}
