<?php
/**
 * Created by PhpStorm.
 * User: shiyu
 * Date: 2018/10/19 0019
 * Time: 下午 16:55
 */

namespace app\admin\model;


use think\Db;

class FinanceModel extends BaseModel
{

    /*
    获取沙龙列表
     */
    public function finance_shop($shop_id,$from,$to)
    {
        $where['tf_order.shop_id']      = $shop_id;
        //$where['shop_id']      = $shop_id;

        $db = Db::connect(config('ddxx'));

        //微信商品付款
        $finance_shop['wechat_goods'] = $db
            ->name('order')
            ->join("tf_order_goods","tf_order.id=tf_order_goods.order_id","right")
            ->field("tf_order_goods.price,tf_order_goods.num")
            ->where("tf_order.pay_way!=3")
            ->where("tf_order.paytime>0")
            ->where($where)
            ->where("tf_order.addtime>".$from)
            ->where("tf_order.addtime<".$to)
            //->fetchSql(true)
            ->select();

        //print_r($finance_shop);die;

        $price_wechat = array();
        foreach ($finance_shop['wechat_goods'] as $key => $value) {
            $price_wechat[] = $value['price']*$value['num'];
        }
        //print_r($price_wechat);die;
        if($price_wechat!=null){
            $finance_shop['wechat_goods'] = array_sum($price_wechat);
        }else{
            $finance_shop['wechat_goods'] = 0;
        }
        //卡片商品付款
        $finance_shop['card_goods'] = $db
            ->name('order')
            ->join("tf_order_goods","tf_order.id=tf_order_goods.order_id","right")
            ->field("tf_order_goods.price,tf_order_goods.num")
            ->where("tf_order.paytime>0")
            ->where("tf_order.pay_way=3")
            ->where($where)
            ->where("tf_order.addtime>".$from)
            ->where("tf_order.addtime<".$to)
            ->select();

        $price_card = array();
        foreach ($finance_shop['card_goods'] as $key => $value) {
            $price_card[] = $value['price']*$value['num'];
        }
        //print_r($price_card);die;
        if($price_card!=null){
            $finance_shop['card_goods'] = array_sum($price_card);
        }else{
            $finance_shop['card_goods'] = 0;
        }

        //商品总金额
        $finance_shop['goods_num']  = $finance_shop['wechat_goods']+$finance_shop['card_goods'];

        //游泳现金支付
        $finance_shop['wechat_swim']  = $db
            ->name('yuyue')
            ->join("tf_order","tf_yuyue.order_id=tf_order.id")
            ->field("tf_yuyue.price,tf_yuyue.num")
            ->where("tf_order.pay_way!=3")
            ->where("tf_yuyue.sid=".$shop_id)
            ->where("tf_yuyue.type=0")
            ->where("tf_yuyue.paytime>0")
            ->where("tf_yuyue.addtime>".$from)
            ->where("tf_yuyue.addtime<".$to)
            ->select();

        $wechat_swim = array();
        foreach ($finance_shop['wechat_swim'] as $key => $value) {
            $wechat_swim[] = $value['price']*$value['num'];
        }
        //print_r($price_wechat);die;
        if($wechat_swim!=null){
            $finance_shop['wechat_swim'] = array_sum($wechat_swim);
        }else{
            $finance_shop['wechat_swim'] = 0;
        }

        //游泳卡片支付
        $finance_shop['card_swim']  = $db
            ->name('yuyue')
            ->join("tf_order","tf_yuyue.order_id=tf_order.id")
            ->field("tf_yuyue.price,tf_yuyue.num")
            ->where("tf_order.pay_way=3")
            ->where("tf_yuyue.sid=".$shop_id)
            ->where("tf_yuyue.type=0")
            ->where("tf_yuyue.paytime>0")
            ->where("tf_yuyue.addtime>".$from)
            ->where("tf_yuyue.addtime<".$to)
            ->select();

        $card_swim = array();
        foreach ($finance_shop['card_swim'] as $key => $value) {
            $card_swim[] = $value['price']*$value['num'];
        }
        //print_r($price_wechat);die;
        if($card_swim!=null){
            $finance_shop['card_swim'] = array_sum($card_swim);
        }else{
            $finance_shop['card_swim'] = 0;
        }

        //游泳总金额
        $finance_shop['swim_num']  = $finance_shop['wechat_swim']+$finance_shop['card_swim'];

        //推拿现金支付
        $finance_shop['wechat_tuina']  = $db
            ->name('yuyue')
            ->join("tf_order","tf_yuyue.order_id=tf_order.id")
            ->field("tf_yuyue.price,tf_yuyue.num")
            ->where("tf_order.pay_way!=3")
            ->where("tf_yuyue.sid=".$shop_id)
            ->where("tf_yuyue.type=1")
            ->where("tf_yuyue.paytime>0")
            ->where("tf_yuyue.addtime>".$from)
            ->where("tf_yuyue.addtime<".$to)
            ->select();

        //print_r($finance_shop['wechat_tuina']);die;
        $wechat_tuina = array();
        foreach ($finance_shop['wechat_tuina'] as $key => $value) {
            $wechat_tuina[] = $value['price']*$value['num'];
        }
        //print_r($price_wechat);die;
        if($wechat_tuina!=null){
            $finance_shop['wechat_tuina'] = array_sum($wechat_tuina);
        }else{
            $finance_shop['wechat_tuina'] = 0;
        }

        //推拿卡片支付
        $finance_shop['card_tuina']  = $db
            ->name('yuyue')
            ->join("tf_order","tf_yuyue.order_id=tf_order.id")
            ->field("tf_yuyue.price,tf_yuyue.num")
            ->where("tf_order.pay_way=3")
            ->where("tf_yuyue.sid=".$shop_id)
            ->where("tf_yuyue.type=1")
            ->where("tf_yuyue.paytime>0")
            ->where("tf_yuyue.addtime>".$from)
            ->where("tf_yuyue.addtime<".$to)
            ->select();

        $card_tuina = array();
        foreach ($finance_shop['card_tuina'] as $key => $value) {
            $card_tuina[] = $value['price']*$value['num'];
        }
        //print_r($price_wechat);die;
        if($card_tuina!=null){
            $finance_shop['card_tuina'] = array_sum($card_tuina);
        }else{
            $finance_shop['card_tuina'] = 0;
        }

        //推拿总金额
        $finance_shop['tuina_num']  = $finance_shop['wechat_tuina']+$finance_shop['card_tuina'];
        //收入总金额
        $finance_shop['shou_num']   = $finance_shop['goods_num']+$finance_shop['swim_num']+$finance_shop['tuina_num'];

        //卡片销售汇总
        $finance_shop['cardzong'] = $db
            ->name('card')
            ->field("title,price,sum(price) as pricesum,count(id) as num")
            ->where("shop_id=".$shop_id)
            ->where("paytime>0")
            ->where("addtime>".$from)
            ->where("addtime<".$to)
            ->group("title,price")
            //->fetchSql(true)
            ->select();

        $finance_shop = json_decode(json_encode($finance_shop),1);

        $finance_shop['countnum'] = 0;

        if($finance_shop['cardzong']!=null){

            foreach ($finance_shop['cardzong'] as $key => $value) {
                $countnum[] = $value['price'];
            }

            $finance_shop['countnum'] = array_sum($countnum);
        }
        //店铺商品支出金额
        $finance_shop['goods_oprice'] = $db
            ->name("finance")
            ->where("shop_id=".$shop_id)
            ->where("status=1")
            ->where("type=1")
            ->where("addtime>".$from)
            ->where("addtime<".$to)
            ->sum('price');
        //店铺其他支出
        $finance_shop['shop_zhichu'] = $db
            ->name("finance")
            ->where("shop_id=".$shop_id)
            ->where("status=1")
            ->where("type=2")
            ->where("addtime>".$from)
            ->where("addtime<".$to)
            ->select();

        $zhichu = $db
            ->name("finance")
            ->where("shop_id=".$shop_id)
            ->where("status=1")
            ->where("type=2")
            ->where("addtime>".$from)
            ->where("addtime<".$to)
            ->sum('price');

        $finance_shop['zhichunum'] = $finance_shop['goods_oprice']+$zhichu;

        return $finance_shop;

    }


    //门店支出添加
    public function finance_add($data)
    {
        $db = Db::connect(config('ddxx'));
        return $db->name('finance')->insert($data);
    }
    //门店支出添加
    public function finance_edit($id,$data)
    {
        $db = Db::connect(config('ddxx'));
        return $db->name('finance')->where("id=".$id)->update($data);
    }


    public function get_sale_count($where)
    {
        $db = Db::connect(config('ddxx'));
        $list = $db->name('count_sale')->alias('a')
            ->join("tf_order b","a.shop_id=b.shop_id","LEFT")
            ->join("tf_item c","a.item_id=c.id","LEFT")
            ->join("tf_shop d","b.shop_id=d.id","LEFT")
            ->field('a.num,a.refund_num,a.is_online,a.sale_way,a.update_time,d.name,c.id as sku_id,c.title as subtitle')
            ->where($where)
            ->group('a.id')
            ->order('a.update_time desc');
        return $list;

    }

    public function get_service_sale_count($where)
    {
        $db = Db::connect(config('ddxx'));
        $list = $db->name('count_service')->alias('a')
            ->join("tf_yuyue b","a.shop_id=b.sid","LEFT")
            ->join("tf_shop c","a.shop_id=c.id","LEFT")
            ->join("tf_worker d","a.worker_id=d.id","LEFT")
            ->join("tf_service e","a.service_id=e.id","LEFT")
            ->field("a.num,a.refund_num,a.is_online,a.update_time,c.name as shop_name,d.name as worker_name,d.workid,e.sname")
            ->where($where)
            ->group('a.id')
            ->order('a.update_time desc');
        return $list;
    }


    /**
     * @param $where 查询条件
     * @param $field 查询类型 num售出数量 refund_num退货数量
     * @return float|int
     */
    public function get_sale_all($where,$field)
    {
        $db = Db::connect(config('ddxx'));
        $list = $db->name('count_sale')->alias('a')
            ->join("tf_order b","a.shop_id=b.shop_id","LEFT")
            ->join("tf_item c","a.item_id=c.id","LEFT")
            ->join("tf_shop d","b.shop_id=d.id","LEFT")
            ->field('a.num,a.refund_num,a.is_online,a.sale_way,a.update_time,d.name,c.id as sku_id,c.title as subtitle')
            ->where($where)
            ->group('a.id')
            ->select();
        $num = 0;
        foreach ($list as $k=>$v){
            $num+=$v[$field];
        }
        return $num;
    }


    public function get_service_sale_all($where,$field)
    {
        $db = Db::connect(config('ddxx'));
        $list = $db->name('count_service')->alias('a')
            ->join("tf_yuyue b","a.shop_id=b.sid","LEFT")
            ->join("tf_shop c","a.shop_id=c.id","LEFT")
            ->join("tf_worker d","a.worker_id=d.id","LEFT")
            ->join("tf_service e","a.service_id=e.s_id","LEFT")
            ->field("a.num,a.refund_num,a.is_online,a.update_time,c.name as shop_name,b.name as worker_name,d.workid,e.sname")
            ->where($where)
            ->group('a.id')
            ->select();
        $num = 0;
        foreach ($list as $k=>$v){
            $num+=$v[$field];
        }
        return $num;
    }


}