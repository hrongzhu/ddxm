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
use app\admin\common\Logs;

use app\admin\controller\LogsController;
use app\admin\model\BaseModel;
use app\admin\model\item\ItemCategoryModel;
use app\admin\model\ItemModel;
use app\admin\model\user\IntegralModel;
use app\admin\model\shop\ShopModel;
use app\admin\model\order\OrderModel;
use app\admin\model\user\UserModel;
use app\admin\model\order\OrderGoodsModel;
use app\admin\model\ServiceModel;
use think\Db;
use think\Exception;

class CollectModel extends BaseModel
{
    protected $autoWriteTimestamp = false;

    protected $table = 'tf_order';

    public function getPayWayAttr($value)
    {
        if (empty($value)) {
            return '未知';
        }
        $status = [
            1 => '微信',
            2 => '支付宝',
            3 => '余额',
            4 => '银行卡',
            5 => '现金',
            6 => '美团',
            7 => '赠送',
            8 => '门店自用',
            10 => '包月服务',
            11 => '定制疗程'
        ];
        return $status[$value];
    }

    public function getPaytimeAttr($value)
    {
        if (empty($value)) {
            return '未知';
        }
        return date('Y-m-d H:i:s',$value);
    }

    /**
     * 列表数据
     * @param  [type] $where    [主要查询条件]
     * @param  string $buy_type [额外查询条件]
     * @return obj              [返回对象]
     */
    public function getOrderListDatas($where,$o_where = '')
    {
        if (isset($o_where['buy_type'])) {
            return $this->table($this->table)
            ->alias('a')
            ->join($o_where['buy_type'])
            ->field('a.id,a.sn,a.shop_id,a.amount,a.addtime,a.pay_way,a.waiter,a.member_id,a.is_online,a.sendtime,a.order_type')
            ->where($where)
            ->where(['a.isDel'=>0])
            ->order('a.addtime DESC');
        }else{
            return $this->table($this->table)
            ->alias('a')
            ->field('a.id,a.sn,a.shop_id,a.amount,a.addtime,a.pay_way,a.waiter,a.member_id,a.is_online,a.sendtime,a.order_type')
            ->where($where)
            ->where(['a.isDel'=>0])
            ->order('a.addtime DESC');
        }
    }


    //一对多模型(商品信息)
    public function moreOrderGoods()
    {
        return $this->hasMany('OrderGoodsModel','order_id','id')
            ->field('order_id,ch_id,subtitle,attr_pic,attr_name,num,category_id,price,oprice,price');
    }

    //一对一模型(用户信息)
    public function memberInfo()
    {
        return $this->belongsTo('app\admin\model\user\UserModel','member_id','id')
            ->field('id,shop_code as scode,mobile as telephone,nickname,level_id');
    }

    //一对一模型(获取店铺信息)
    public function shopInfo()
    {
        return $this->hasOne('app\admin\model\shop\ShopModel','id','shop_id')
            ->field('id,code,name');
    }

    public function getOrderDetailData($id = 0)
    {
        $datas = self::with(['memberInfo','shopInfo'])
            ->table($this->table)->field('fixtime,evaluate,isDel,code,activity_id,discount,profit,send_way,code,yy_id,coupon_id',true)
            ->where('id','=',$id)->find()->toArray();
        //获取商品信息
        $order_type = Db::connect(config('ddxx'))->name('order')->where(['id'=>$id])->value('order_type');
        $orderGoodsData = $this->getGoodsOrServerList($id);
        // var_dump($orderGoodsList);die;
        if ($datas['card_id']) {
            $datas['member_info']['card_title'] = Db::connect(config('ddxx'))
                ->name('card')
                ->where('id','=',$datas['card_id'])
                ->value('title');
        }
        if ($datas['member_id']) {
            $datas['member_info']['shop_name'] = model('shop.Shop')->where(['code'=>$datas['member_info']['scode']])->value('name');
            $datas['member_info']['level_name'] = model('Level')->where(['id'=>$datas['member_info']['level_id']])->value('level_name');
        }else{
            $datas['member_info']['shop_name'] = '无';
            $datas['member_info']['level_name'] = '无';
        }
        $db = Db::connect(config('ddxx'));
        // //获取改价商品信息
        $editPriceGoodsList = $db
            ->name('discount')
            ->field('content,type,disprice,price,addtime,goods_id')
            ->where(['order_id'=>$id,'type'=>2])
            ->select();
//        var_dump($editPriceGoodsList);die;
        //获取优惠券使用信息
        $ticket_used = $db
            ->name('order')
            ->where(['id'=>$id])
            ->value('ticket_ids');
        if ($ticket_used){
            $ticket_used_arr = explode(',',$ticket_used);
            $ticket_used_list = [];
            foreach ($ticket_used_arr as $v){
                $ticket_id = $db->name('member_ticket')->where(['id'=>$v])->value('ticket_id');
                $ticket_info = $db->name('ticket')->where(['id'=>$ticket_id])->field('name,money')->find();
                $ticket_used_list[] = $ticket_info;
            }
            $datas['ticket_used_list'] = $ticket_used_list;
        }
        foreach ($editPriceGoodsList as $k=>$v) {
            $subtitle = Db::connect(config('ddxx'))
                ->name('order_goods')
                ->where(['item_id'=>$v['goods_id'],'order_id'=>$id])
                ->value('subtitle');
            if (empty($subtitle)) {
                $editPriceGoodsList[$k]['subtitle'] = model('Service')->where('id','=',$v['goods_id'])->value('sname');
            }else{
                $editPriceGoodsList[$k]['subtitle'] = $subtitle;
            }
        }

        //获取分类数据(待改进)
        $datas['more_order_goods'] = $orderGoodsData['orderGoodsList'];
        $datas['order_goods_cost'] = $datas['amount'] < 0?-$orderGoodsData['orderGoodsCost']:$orderGoodsData['orderGoodsCost'];
        $datas['edit_price_goods'] = $editPriceGoodsList;
        //计算优惠价等等
        $datas['discount_info'] = [];
        //总vip优惠
        $vip_discount = 0;
        $vip_discount_info = $db->name('discount')->where(['order_id'=>$id,'type'=>1])->select();
        foreach ($vip_discount_info as $v){
            $vip_discount+=($v['disprice']*$v['num']);
        }
        $datas['discount_info']['vip_discount'] = $vip_discount;
        //总改价优惠
        $eprice_discount = 0;
        $eprice_discount_info = $db->name('discount')->where(['order_id'=>$id,'type'=>2])->select();
        foreach ($eprice_discount_info as $v){
            $eprice_discount+=($v['disprice']*$v['num']);
        }
        $datas['discount_info']['eprice_discount'] = $eprice_discount;
        //总代金券优惠
        $ticket_discount = 0;
        $ticket_ids = $db->name('order')->where(['id'=>$id])->value('ticket_ids');
        $ticket_ids_arr = explode(',',$ticket_ids);
        if ($ticket_ids_arr) {
            foreach ($ticket_ids_arr as $v){
                $ticket_id = $db->name('member_ticket')->where(['id'=>$v])->value('ticket_id');
                $money = $db->name('ticket')->where(['id'=>$ticket_id])->value('money');
                $ticket_discount+=$money;
            }
        }
        $datas['discount_info']['ticket_discount'] = $ticket_discount;
        //总优惠金额
        $datas['discount_info']['discount_price']= $vip_discount+$eprice_discount+$ticket_discount;
        //合计金额
        $datas['old_price'] = $this->getOrderOldPrice($id);
        $datas['order_type'] = $order_type;
        return $datas;
    }

    //获取当前订单的基本信息
    public function getGoodsOrServerList($id= 0)
    {
        $data = [];
        //获取商品信息
        $orderGoodsList = (new OrderGoodsModel())
            ->field('id as goods_id,subtitle,price,oprice,og_price,faker_price,num,dq_num,refund_num,status')
            ->where(['order_id'=>$id])
            ->select();
        $orderGoodsList = collection($orderGoodsList)->toArray();
        // 查询服务列表
        $server_list = Db::connect(config('ddxx'))
            ->name('yuyue')->alias('a')
            ->join('tf_service b','a.type=b.id','LEFT')
            ->field('a.type as goods_id,b.sname,a.num,a.price,a.status,a.sid')
            ->where(['a.order_id'=>$id])
            ->select();
        $cost_price = 0;
        foreach ($orderGoodsList as &$v) {
            $cost_price += round($v['oprice'] * $v['num'],2);
            $v['name'] = '【商品】'.$v['subtitle'];
            $v['class'] = 1;
        }
        foreach ($server_list as &$vs) {
            $vs['oprice'] = 0;
            $vs['faker_price'] = 0;
            $vs['og_price'] = self::getOneServicePrice($vs['goods_id'], $vs['sid']);
            $vs['name'] = '【服务】'.$vs['sname'];
            $vs['class'] = 2;
            array_push($orderGoodsList,$vs);
        }
        $data['orderGoodsList'] = $orderGoodsList;
        $data['orderGoodsCost'] = $cost_price;
        return $data;
    }

    //获取当前订单的成本总价 type 默认是真成本 1
    public function getOrderCost($id= 0,$type = 1)
    {
        //获取商品信息
        $info = Db::connect(config('ddxx'))->name('finance')->where(['order_id'=>$id])->field('cost_price,price')->find();
        if ($type == 1) {
            return $info['cost_price'] == 0?$info['price']:$info['cost_price'];
        }else{
            return $info['price'];
        }
    }
    //临时用
    public function getOrderCost1($id= 0,$refund = 0,$info = [])
    {
        $db = Db::connect(config('ddxx'));
        //获取商品信息
        $orderGoodsList = (new OrderGoodsModel())
            ->alias('a')
            ->join('tf_item b','a.item_id=b.id')
            ->field('a.oprice as oldprice,a.num')
            ->where(['a.order_id'=>$id])
            ->select();
        $orderGoodsList = collection($orderGoodsList)->toArray();
        $cost_price = 0;
        foreach ($orderGoodsList as &$v) {
            $cost_price += round($v['oldprice'] * $v['num'],2);
        }
        if ($refund){
            $cost_price = -$cost_price;
        }
        try{
            $finance = $db->name('finance')->where(['order_id'=>$id])->field('id,price')->find();
            if (!empty($finance)){
                if ($finance['price'] == 0){
                    $db->name('finance')->where(['id'=>$finance['id']])->update(['price'=>$cost_price]);
                }
            }else{
                $data = [];
                $data['sn'] = $info['sn'];
                $data['order_id'] = $id;
                $data['content'] = '商品购买支出';
                $data['price'] = $cost_price;
                $data['shop_id'] = $info['shop_id'];
                $data['status'] = 1;
                $data['type'] = 1;
                $data['addtime'] = $info['sendtime']?$info['sendtime']:$info['addtime'];
                $db->name('finance')->insert($data);
            }
        }catch (Exception $e){
            var_dump($e->getMessage());
        }
        return $cost_price;
    }


    //获取当前订单的基本信息
    public function getGoodsOrServerListToRefund($id= 0)
    {
        //获取商品信息
        $orderGoodsList = (new OrderGoodsModel())
            ->field('id as goods_id,subtitle,price,ticket_deduction,num,refund_num,dq_num,status')
            ->where('status','neq',-1)
            ->where(['order_id'=>$id])
            ->select();
        $orderGoodsList = collection($orderGoodsList)->toArray();
        // 查询服务列表
        $server_list = Db::connect(config('ddxx'))
            ->name('yuyue')->alias('a')
            ->join('tf_service b','a.type=b.id')
            ->field('a.id as goods_id,b.sname,a.num,a.price,a.status')
            ->where('a.status','neq',-1)
            ->where(['a.order_id'=>$id])
            ->select();
        foreach ($orderGoodsList as &$v) {
            $v['name'] = '【商品】'.$v['subtitle'];
            $v['class'] = 1;
            $v['num'] = $v['num']-$v['refund_num']-$v['dq_num'];
            $v['price'] = (($v['price']-$v['ticket_deduction'])<=0)?0:$v['price']-$v['ticket_deduction'];
        }
        foreach ($server_list as &$vs) {
            $vs['name'] = '【服务】'.$vs['sname'];
            $vs['class'] = 2;
            array_push($orderGoodsList,$vs);
        }

        return $orderGoodsList;
    }

    //获取当前订单的原价
    public function getOrderOldPrice($id= 0)
    {
        //获取商品信息
        $goods_list = (new OrderGoodsModel())
            ->where(['order_id'=>$id])
            ->field('price,num')
            ->select();
        $goods_price = 0;
        foreach ($goods_list as $vg) {
            $goods_price += round($vg['price'] * $vg['num'],2);
        }
        // 查询服务列表
        $server_list = Db::connect(config('ddxx'))
            ->name('yuyue')
            ->field('type,order_id,num,sid')
            ->where(['order_id'=>$id])
            ->select();
        $server_price = 0;
        foreach ($server_list as $v) {
            // 判断是哪种服务
            $price = self::getOneServicePrice($v['type'], $v['sid']);
            $server_price  += round($price * $v['num'],2);
        }
        return round($goods_price + $server_price,2);
    }

    public function getPayWay($id = 0)
    {
        return Db::connect(config('ddxx'))->name('order')->where('id','=',$id)->value('pay_way');
    }

    public function getOrderField($id = 0,$field = 'id')
    {
        return Db::connect(config('ddxx'))->name('order')->where('id','=',$id)->value($field);
    }

    //门店手动退货
    public function refund($order_goods = [],$worker_id)
    {
        $orderModel = new OrderModel();
        $goodsModel = new OrderGoodsModel();
        $db = Db::connect(config('ddxx'));
        // 退货商品订单详情
        $order_info = $db->name('order')->where('id','=',$order_goods['order_id'])->field('id,amount,sn,pay_sn,addtime,overtime,paytime',true)->find();
        if (empty($order_info)) {
            return array('code'=>301,'msg'=>'订单号有误');
        }
        foreach ($order_goods['goods_list'] as $key=>$value){
            if($value['num']==0){
                return array('code'=>301,'msg'=>'无效数据','data'=>[]);
                exit;
            }
        }
        $orderModel->startTrans();
        $db->startTrans();
        $goodsModel->startTrans();
        try {
            //查处商品应该有的价格
            $zprice = 0;
            foreach ($order_goods['goods_list'] as $v) {
                if ($v['type'] == 1) {
                    $og_info = $goodsModel->where('id','=',$v['goods_id'])->field('price,num,ticket_deduction')->find();
                    $zprice += round(($og_info['price']-$og_info['ticket_deduction'])* $v['num'],2);
                }else{
                    $og_info = $db->name('yuyue')->where('id','=',$v['goods_id'])->field('price,num')->find();
                    $zprice += round($og_info['price'] * $v['num'],2);
                }

            }
            $order_info['amount'] = ($zprice<=0)?0:- $zprice;
            $order_info['sn'] = time().rand(10,99).$order_info['shop_id'];
            $pay_sn = '42000000'.date('W').date('Ymd').$this->rands(10);//支付流水号
            $order_info['pay_sn'] = $pay_sn;
            $order_info['addtime'] = time();
            $order_info['overtime'] = time();
            $order_info['paytime'] = time();
            $orderModel->allowField(true)->save($order_info);
            $order_id = $orderModel->id;

            $costPrice = 0;//成本价
            $fakerPrice = 0;//成本价
            foreach ($order_goods['goods_list'] as $k => $v) {
                if ($v['type'] == 1) {
                    //防止重复提交
                    $status = $db->name('order_goods')->where(['id'=>$v['goods_id']])->value('status');
                    if ($status==-1) {
                        $db->rollback();
                        return array('code'=>301,'msg'=>'商品已退，请不要重复操作！','data'=>[]);
                    }
                    $info = $db->name('order_goods')->where('id','=',$v['goods_id'])->field('id,order_id,num',true)->find();
                    $costPrice += $info['oprice'] * $v['num'];
                    $fakerPrice += $info['faker_price'] * $v['num'];
                    $goods = [];
                    $info['order_id']      = $order_id;
                    $info['num']           = $v['num'];
                    $info['price']         = -$info['price'];
                    $info['ticket_deduction']  = -$info['ticket_deduction'];
                    $info['status']        = -1;
                    $db->name('order_goods')->where('id','=',$v['goods_id'])->update(['status'=>-1]);//修改是否退款了
                    $db->name('order_goods')->insert($info);
                    if($order_info['order_type']==2){
                        //记录操作历史
                        $db->name('goods_book_history')->insert([
                            'order_id'=>$order_goods['order_id'],
                            'type'=>3,
                            'item_id'=>$info['item_id'],
                            'worker_id'=>$worker_id,
                            'num'=>$v['num'],
                            'addtime'=>time()
                        ]);
                    }
                    //该笔订单的所有商品全部退完之后退还用户代金券
                    $order_goods_list = $db->name('order_goods')->where(['order_id'=>$order_goods['order_id']])->field('status,num')->select();
                    $no_refund_num = 0;//未退货的商品数量
                    foreach ($order_goods_list as $k=>$vv){
                        if($vv['status']!=-1){
                            $no_refund_num+=$vv['num'];
                        }
                    }
                    if ($no_refund_num==0) {
                        $ticket = $db->name('order')->where(['id'=>$order_goods['order_id']])->value('ticket_ids');
                        $ticket_arr = explode(',',$ticket);
                        foreach ($ticket_arr as $k=>$vvv){
                            $db->name('member_ticket')->where(['id'=>$vvv])->update(['status'=>1]);
                        }
                    }
                    //更新商品销量数据
                    $this->countSale($order_info['shop_id'],$info['item_id'],-$v['num'],0,1);
                    //更新一条成本信息
                    model('shop.Stock')->updateItemCostPrice($info['item_id'],$order_info['shop_id'],$info['oprice'],$info['faker_price'],$v['num'],$order_id);
                }elseif ($v['type'] == 2) {
                    //防止重复提交
                    $status = $db->name('yuyue')->where(['id'=>$v['goods_id']])->value('status');
                    if ($status==-1) {
                        $db->rollback();
                        return array('code'=>301,'msg'=>'商品已退，请不要重复操作！','data'=>[]);
                    }
                    $yuyue = $db->name('yuyue')->where('id','=',$v['goods_id'])->field('id,num,sn,paytime.addtime,order_id',true)->find();
                    // $costPrice += $yuyue['price'];
                    $yuyue['yytime']        = time();
                    $yuyue['price']         = -$yuyue['price'];
                    $yuyue['num']           = $v['num'];
                    $yuyue['paytime']       = time();
                    $yuyue['addtime']       = time();
                    $yuyue['order_id']      = $order_id;
                    $yuyue['status']        = -1;
                    $yuyue['sn']            = time().rand(10,99).$yuyue['sid'];
                    $db->name('yuyue')->where('id','=',$v['goods_id'])->update(['status'=>-1]);
                    $db->name('yuyue')->insert($yuyue);
                    //记录服务销量
                    $this->countService($yuyue['sid'],$yuyue['type'],$yuyue['workerid'],-$v['num'],0);
                }

            }
            //成本
            $finance_data = [];
            $finance_data['shop_id'] = $order_info['shop_id'];
            $finance_data['order_id'] = $order_id;
            $finance_data['content'] = '商品或服务退款';
            if (config('finance.show_cost_finance')) {
                $finance_data['price'] = -$costPrice;
            }else{
                $finance_data['price'] = -$fakerPrice;
            }
            $finance_data['cost_price'] = -$costPrice;
            $finance_data['addtime'] = time();
            $finance_data['status'] = 1;
            $finance_data['type'] = 1;
            $finance_data['sn'] = $order_info['sn'];
            $db->name('finance')->insert($finance_data);

            if ($order_info['pay_way'] == 3) {
                //退到余额
                $user_money = $db->name('member')->where(['id'=>$order_info['member_id']])->value('money');
                $moneys = round($user_money + ($zprice<=0?0:$zprice),2);
                $db->name('member')->where(['id'=>$order_info['member_id']])->update(['money'=>$moneys]);
            }
            $orderModel->commit();
            $db->commit();
            $goodsModel->commit();
            (new IntegralModel())->reduceIntegral($order_id);
            return array('code'=>200,'msg'=>'退款成功');
        } catch (\Exception $e) {
            $orderModel->rollback();
            $db->rollback();
            $goodsModel->rollback();
            return array('code'=>301,'msg'=>'退款失败','data'=>$e->getMessage());
        }
    }

    //生成指定长度数字2
    public function rands($no)
    {
        //range 是将1到100 列成一个数组
        $numbers = range (0,10);
        //shuffle 将数组顺序随即打乱
        shuffle ($numbers);
        //array_slice 取该数组中的某一段
        $result = array_slice($numbers,0,$no);
        $str = '';
        for ($i=0;$i<$no;$i++){
            $str.= $result[$i];
        }
        return $str;
    }

    /**
     * [getServicePrice 获取当前门店单个服务的价格]
     * @param  integer $service_id   [description]
     * @param  integer $shop_id      [description]
     * @param  integer $member_id    [description]
     * @return [type]                [description]
     */
    protected static function getOneServicePrice($service_id = 0,$shop_id = 0 ,$member_id = 0)
    {
        $serviceModel = new ServiceModel();
        $info = $serviceModel->where(['status'=>1,'id'=>$service_id])->field('id,s_id,standard_price')->find();

        $service_price = (new ShopModel())->where(['id'=>$shop_id])->value('service_level_price');
        $service_price = json_decode($service_price,JSON_UNESCAPED_UNICODE);
        $server_keys   = array_keys($service_price);//服务的数组
        //服务默认价格
        $server_id = $info['s_id'];
        $service_default_price = json_decode($info['standard_price'],JSON_UNESCAPED_UNICODE);
        $server_price = 0;
        $server_price = $service_default_price[1];
        if ($member_id) {
            //如果会员等级不再列表里且大于最高会员等级就用最大会员等级的价格
            $user_level = (new UserModel())->where(['id'=>$member_id])->value('level_id');
            $level_keys = array_keys($service_price[$server_id]);
            $max_level = max($level_keys);
            $min_level = min($level_keys);
            if (!in_array($user_level, $level_keys) && $max_level < $user_level) {
                $server_price = $service_price[$server_id][$max_level];
            }elseif(!in_array($user_level, $level_keys) && $min_level > $user_level){
                $server_price = $service_price[$server_id][$min_level];
            }else{
                $server_price = $service_price[$server_id][$user_level];
            }
        }
        return $server_price;
    }

    //计算商品税费
    public function lvnums($item_id)
    {
        $taxprice = Db::connect(config('ddxx'))->table('tf_item')
            ->join('tf_lv','tf_item.lvid=tf_lv.id')
            ->field('tf_lv.lv,tf_item.price')
            ->where("tf_item.id=".$item_id)
            ->where("tf_item.type_id=3")
            ->find();

        return round($taxprice['lv']*$taxprice['price']*0.01,2);
    }

    //获取所有门店
    public function getAllShop($field = '',$where = '')
    {
        if ($field) {
            return Db::connect(config('ddxx'))->name('shop')->where($where)->where(['status'=>1])->field($field)->select();
        }else{
            return Db::connect(config('ddxx'))->name('shop')->where($where)->where(['status'=>1])->select();
        }

    }


    /**
     * 记录销量
     * @param $shop_id 门店ID
     * @param $item_id 商品ID
     * @param $num 数量 为正售出，为负退货
     * @param $is_online 数据来源 1线上 0线下
     * @param $sale_way 发货方式 1库存发货 2代发
     * @return int|string
     */
    public function countSale($shop_id,$item_id,$num,$is_online,$sale_way)
    {
        $db = Db::connect(config('ddxx'));
        $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
        $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
        //查询销量统计表中今天有无同门店、同商品、同数据来源、同发货方式的数据，有执行修改，没有执行新增
        $isset = $db->name('count_sale')->where(['shop_id'=>$shop_id,'item_id'=>$item_id,'is_online'=>$is_online,'sale_way'=>$sale_way,'update_time'=>["between",[$beginToday,$endToday]]])->find();
        if($num<0){
            $field = 'refund_num';
            $num = abs($num);
        }else{
            $field = 'num';
        }
        if (empty($isset)) {
            $insert_data = [
                'shop_id'=>$shop_id,'item_id'=>$item_id,$field=>$num,'update_time'=>time(),'is_online'=>$is_online,'sale_way'=>$sale_way
            ];
            $res = $db->name('count_sale')->insert($insert_data);
        }else{
            $res = $db->name('count_sale')->where(['shop_id'=>$shop_id,'item_id'=>$item_id,'is_online'=>$is_online,'sale_way'=>$sale_way,'update_time'=>["between",[$beginToday,$endToday]]])->update([$field=>["exp",$field.'+'.$num],'update_time'=>time()]);
        }
        return $res;
    }


    /**
     * @param $shop_id 门店ID
     * @param $service_id 服务ID
     * @param $worker_id 员工ID
     * @param $num 数量 为正售出，为负退货
     * @param $is_online 数据来源 1线上 0线下
     * @return int|string
     */
    public function countService($shop_id,$service_id,$worker_id,$num,$is_online)
    {
        $db = Db::connect(config('ddxx'));
        $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
        $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
        //查询服务销量统计表中今天有无同门店、同服务、同数据来源、同服务员工，有执行修改，没有执行新增
        $isset = $db->name('count_service')->where(['shop_id'=>$shop_id,'service_id'=>$service_id,'worker_id'=>$worker_id,'is_online'=>$is_online,'update_time'=>["between",[$beginToday,$endToday]]])->find();
        if($num<0){
            $field = 'refund_num';
            $num = abs($num);
        }else{
            $field = 'num';
        }
        if (empty($isset)) {
            $insert_data = [
                'shop_id'=>$shop_id,'service_id'=>$service_id,'worker_id'=>$worker_id,$field=>$num,'update_time'=>time(),'is_online'=>$is_online
            ];
            $res = $db->name('count_service')->insert($insert_data);
        }else{
            $res = $db->name('count_service')->where(['shop_id'=>$shop_id,'service_id'=>$service_id,'worker_id'=>$worker_id,'is_online'=>$is_online,'update_time'=>["between",[$beginToday,$endToday]]])->update([$field=>["exp",$field.'+'.$num],'update_time'=>time()]);
        }
        return $res;
    }


}
