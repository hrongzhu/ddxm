<?php
/**
 * Created by PhpStorm.
 * User: shiyu
 * Date: 2018/10/9 0009
 * Time: 上午 9:15
 */

namespace app\admin\controller;


use app\admin\model\GoodsBookModel;
use think\Db;
use think\Session;

class GoodsBookController extends BaseController
{
    protected $pageLimit = 20;


    public function index()
    {
        return view('index');
    }

    public function bookList()
    {
        //搜索参数
        $shop_id = Session::get('SHOP_ID');
        $except_role_id = [1,6,7,8];//不限制的门店管理员角色
        //如果登录的是加盟商呢??  此时只考虑独立门店(就是一个人一个店那种)
        //获取店铺信息(回去根据当前登录的用户来筛选店铺)
        $admin_id = $this->admin_id;
        $role_id = Db::table('ddxm_role_user')->where('user_id', '=', $admin_id)->value('role_id');
        //获取店铺信息(回去根据当前登录的用户来筛选店铺)
        if ($admin_id == 1 ||in_array($role_id,$except_role_id)) {
            $shopDatas = Db::connect(config('ddxx'))->name('shop')->field('id,name,code')->select();
        }else{
            $shopDatas = Db::connect(config('ddxx'))->name('shop')->where('id','in',$shop_id)->field('id,name,code')->select();
        }
        $this->assign('shopDatas',$shopDatas);
        $this->assign('admin_id', $admin_id);
        $shopId = trim($this->request->param('shop_id',''));
        $mobile = trim($this->request->param('mobile'));
        $nick_name = trim($this->request->param('nick_name'));
        $item_name = trim($this->request->param('item_name'));
        $book_time = $this->request->param('book_time','');
        $sn = trim($this->request->param('sn'));
        //生成搜索条件
        $where = [];
        $where['a.order_status'] = 2;
        $where['a.order_type'] = 2;
        $where['b.price'] = ["egt",0];
        //店铺加上 如果是加盟商的话就是多个商铺 那么就用in查询
        if ($shop_id && $admin_id != 1) {
            if (!in_array($role_id, $except_role_id)) {
                $where['a.shop_id'] = ['in', $shop_id];//加盟商的情况
            }
        }
        if($mobile!=null){
            $where['c.mobile'] = ['like',"%$mobile%"];
            $this->assign('mobile',$mobile);
        }
        if($nick_name!=null){
            $where['c.nickname|c.realname'] = ['like',"%$nick_name%"];
            $this->assign('nick_name',$nick_name);
        }
        if($item_name!=null){
            $where['b.subtitle'] = ['like',"%$item_name%"];
            $this->assign('item_name',$item_name);
        }
        if ($book_time) {
            $time_arr = explode('~',$book_time);
            $add_s = strtotime($time_arr[0]);
            $add_e = strtotime($time_arr[1])+24*3600;
            $where['a.paytime'] = ['between',[$add_s,$add_e]];
            $this->assign('add_s',$add_s);
            $this->assign('add_e',$add_e-24*3600+1);
        }
        if ($sn != null) {
            $where['a.sn'] = ['like', "%$sn%"];
            $this->assign('sn',$sn);
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
        $book_list = (new GoodsBookModel())->get_book_list($where);
        //分页参数
        $pageParams = [
            'mobile' => $mobile != null ? $mobile : '',
            'book_time'=>$book_time!=null?$book_time:'',
            'shop_id' => $shop_id!=null?$shop_id:'',
            'nick_name'=>$nick_name!=null?$nick_name:'',
            'item_name' =>$item_name!= null ? $item_name: '',
            'sn'=>$sn!=null?$sn:''
        ];
        $datas = $this->lists($book_list, $pageParams)->toArray();
//        dump($datas);exit;
        $this->assign('data', $datas['data']);

        return $this->fetch('index');
    }

    public function viewBookHistory()
    {
        $data = input('post.');
        $order_goods_id = $data['order_goods_id'];
        $db = Db::connect(config('ddxx'));
        $order_goods_info = $db->name('order_goods')->where(['id'=>$order_goods_id])->find();
        $order_info = $db->name('order')->where(['id'=>$order_goods_info['order_id']])->field('sn,pay_way')->find();
        $order_sn = $order_info['sn'];
        $list = $db->name('goods_book_history')->where(['order_id'=>$order_goods_info['order_id'],'item_id'=>$order_goods_info['item_id']])->order('id desc')->select();
        if (empty($list)) {
            return outPut(200,'没有相关数据');
        }
        foreach ($list as $k=>$v){
            $list[$k]['addtime'] = date("Y-m-d H:i:s",$v['addtime']);
            $list[$k]['sn'] = $order_sn;
            $list[$k]['pay_way'] = $order_info['pay_way'];
            $list[$k]['worker'] = Db::connect(config('otherdb'))->name('user')->where(['id'=>$v['worker_id']])->value('user_login');
            switch ($v['type']){
                case 1:
                    $list[$k]['type'] = '预订';
                    $list[$k]['worker'] = Db::connect(config('ddxx'))->name('worker')->where(['workid'=>$v['worker_id']])->value('name');
                    break;
                case 2:
                    $list[$k]['type'] = '取货';
                    break;
                case 3:
                    $list[$k]['type'] = '退货退款';
                    break;
                case 4:
                    $list[$k]['type'] = '退款';
                    break;
            }
            $info = $db->name('order_goods')->where(['order_id'=>$v['order_id'],'item_id'=>$v['item_id']])->field('price,ticket_deduction,oprice')->find();
            $list[$k]['price'] = $info['price'];
            $list[$k]['ticket_deduction'] = $info['ticket_deduction'];
            $list[$k]['oprice'] = $info['oprice'];
        }
        return outPut(200,'success',$list);
    }



    public function refund()
    {
        $data = input('post.');
        $order_goods_id = $data['order_goods_id'];
        $refund_num = $data['refund_num'];
        $worker_id = Session::get('ADMIN_ID');
        //查询商品预定信息
        $db = Db::connect(config('ddxx'));
        $order_goods_info = $db->name('order_goods')->where(['id'=>$order_goods_id])->find();
        $order_info = $db->name('order')->where(['id'=>$order_goods_info['order_id']])->find();
        if ($refund_num>$order_goods_info['dq_num']) {
            return outPut(301,'退款数量不能大于待取数量');
        }
        //计算需退给客户的金额
        $refund_money = ($order_goods_info['price']-$order_goods_info['ticket_deduction'])*$refund_num;
        //会员卡支付退回会员卡，其他支付方式退现金
        $db->startTrans();
        try{
            //退款、减少待取数量
            if ($order_info['pay_way'] == 3) {
                $db->name('member')->where(['id'=>$order_info['member_id']])->setInc('money',$refund_money);
            }
	    $db->name('order_goods')->where(['id'=>$order_goods_id])->update(['dq_num'=>['exp','dq_num-'.$refund_num],'refund_num'=>['exp','refund_num+'.$refund_num]]);
//            $db->name('order_goods')->where(['id'=>$order_goods_id])->setDec('dq_num',$refund_num);
            //添加订单数据
            $old_order_id = $order_goods_info['order_id'];
            unset($order_goods_info['id'],$order_goods_info['dq_num']);
            unset($order_info['id']);
            $numbers = range (0,10);
            shuffle ($numbers);
            $result = array_slice($numbers,0,10);
            $str = '';
            for ($i=0;$i<10;$i++){
                $str.= $result[$i];
            }
            $order_info['pay_sn'] = '42000000'.date('W').date('Ymd').$str;//支付流水号
            $order_info['sn'] = time().rand(10,99).$order_info['shop_id'];
            $order_info['amount'] = -$refund_money;
            $order_info['order_status'] = 2;
            $order_info['paytime'] = $order_info['sendtime'] = $order_info['overtime'] = $order_info['addtime'] = time();
            $insert_order_data = $order_info;
            $order_goods_info['price'] = -$order_goods_info['price'];
            $order_goods_info['ticket_deduction'] = -$order_goods_info['ticket_deduction'];
            $order_goods_info['num'] = $refund_num;
            $order_goods_info['status'] = -1;
            $insert_order_goods_data = $order_goods_info;
            $db->name('order')->insert($insert_order_data);
            $new_order_id = $db->name('order')->getLastInsID();
            $insert_order_goods_data['order_id'] = $new_order_id;
            $db->name('order_goods')->insert($insert_order_goods_data);
            //记录操作历史
            $db->name('goods_book_history')->insert([
                'order_id'=>$old_order_id,
                'type'=>4,
                'item_id'=>$order_goods_info['item_id'],
                'worker_id'=>$worker_id,
                'num'=>$refund_num,
                'addtime'=>time()
            ]);
            //检查订单，该笔订单的所有商品全部退完之后退还用户代金券
            $book_num = $db->name('goods_book_history')->where(['order_id'=>$old_order_id,'type'=>1])->sum('num');
            $refund_num_all = $db->name('goods_book_history')->where(['order_id'=>$old_order_id,'type'=>['in',[3,4]]])->sum('num');
            if ($book_num==$refund_num_all) {
                $ticket = $db->name('order')->where(['id'=>$old_order_id])->value('ticket_ids');
                $ticket_arr = explode(',',$ticket);
                foreach ($ticket_arr as $k=>$v){
                    $db->name('member_ticket')->where(['id'=>$v])->update(['status'=>1]);
                }
            }
            $db->commit();
            return outPut(200,'退款成功');
        }catch (\Exception $e){
            $db->rollback();
//            dump($e);exit;
            return outPut(301,'退款失败',$e->getMessage());
        }
    }


}
