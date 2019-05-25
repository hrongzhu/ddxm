<?php
/**
 * Created by PhpStorm.
 * User: shiyu
 * Date: 2018/8/1 0001
 * Time: 下午 15:29
 */

namespace app\admin\model;


use think\Db;

class StockModel extends BaseModel
{

    public function get_stock_lists($where)
    {
//        $db = Db::connect(config('ddxx'));
//        $lists = $db->name('purchase_price')->alias('a')
//            ->join('tf_item b','a.item_id = b.id','LEFT')
//            ->join('tf_item_category c','b.type = c.id','LEFT')
//            ->join('tf_shop d','a.shop_id = d.id','LEFT')
//            ->where($where)
//            ->field('max(a.id) as id,a.stock,b.id as item_id,b.title,b.bar_code,c.cname,d.name as shop_name,d.id as shop_id')
//            ->group('a.item_id,a.shop_id')
//            ->order('max(a.id) desc,a.shop_id asc');
//        return $lists;

        return  Db::connect(config('ddxx'))->name('purchase_price')->alias('a')
            ->join('tf_shop d','a.shop_id = d.id','LEFT')
            ->join('tf_item b','a.item_id = b.id','LEFT')
            ->join('tf_item_category c','b.type = c.id','LEFT')
            ->field('max(a.id) as id,d.name as shop_name,d.id as shop_id,b.title,b.bar_code,a.item_id,c.cname')
            ->where($where)
            ->group('a.shop_id,a.item_id')
            ->order('max(a.id) desc');
    }

}