<?php
/**
 * Created by PhpStorm.
 * User: shiyu
 * Date: 2018/7/26 0026
 * Time: 上午 11:46
 */

namespace app\admin\model;


use think\Db;

class StockCheckModel extends BaseModel
{

    public function get_stock_list($where)
    {
//        return  Db::connect(config('ddxx'))->name('item')->alias('a')
//            ->join('tf_shop b','c.shop_id = b.id','right')
//            ->join('tf_purchase_price c','c.item_id = a.id')
//            ->join('tf_item_category e','a.type = e.id','left')
//            ->field('max(c.id) as id,a.title,a.price,a.bar_code')
//            ->where($where)
//            ->group('c.shop_id,c.item_id')
//            ->order('max(c.id) desc');

        return  Db::connect(config('ddxx'))->name('purchase_price')->alias('a')
            ->join('tf_shop b','a.shop_id = b.id','LEFT')
            ->join('tf_item c','a.item_id = c.id','LEFT')
            ->join('tf_item_category e','c.type = e.id','LEFT')
            ->field('max(a.id) as id,b.name as shop_name,c.title,c.price,c.bar_code,a.item_id,e.cname,a.shop_id')
            ->where($where)
            ->group('a.shop_id,a.item_id')
            ->order('max(a.id) desc');

    }


    public function get_all_stock($where)
    {
        $db = Db::connect(config('ddxx'));
        $res = $db->name('purchase_price')->alias('a')
            ->join('tf_shop b','a.shop_id = b.id','LEFT')
            ->join('tf_item c','a.item_id = c.id','LEFT')
            ->join('tf_item_category e','c.type = e.id','LEFT')
            ->field('max(a.id) as id,b.name as shop_name,c.title,c.price,c.bar_code,a.item_id,e.cname,a.shop_id')
            ->where($where)
            ->group('a.shop_id,a.item_id')
            ->order('max(a.id) desc');
    }

}