<?php
/**
 * Created by PhpStorm.
 * User: shiyu
 * Date: 2018/10/9 0009
 * Time: 上午 10:15
 */

namespace app\admin\model;



use think\Db;

class GoodsBookModel extends BaseModel
{

    //获取预订商品清单
    public function get_book_list($where)
    {
        $db = Db::connect(config('ddxx'));
        $list = $db->name('order')->alias('a')
            ->join('tf_order_goods b',"a.id=b.order_id")
            ->join('tf_member c',"a.member_id=c.id")
            ->join('tf_item d',"b.item_id=d.id")
            ->join('tf_shop e',"a.shop_id=e.id")
            ->where($where)
            ->field('a.paytime,a.pay_way,a.sn,b.subtitle,b.id as order_goods_id,b.price,b.ticket_deduction,b.num,b.dq_num,c.mobile as member_mobile,c.nickname,d.id as sku_id,e.name')
            ->order('a.paytime desc,b.dq_num desc');
        return $list;
    }

}
