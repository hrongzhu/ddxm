<?php
/**
 * Created by PhpStorm.
 * User: shiyu
 * Date: 2018/8/7 0007
 * Time: 上午 10:46
 */

namespace app\admin\model;


use think\Db;

class ProfitManageModel extends BaseModel
{
    //根据条件获取盘盈/盘亏单列表
    public function get_profit_list($where)
    {
        $db = Db::connect(config('ddxx'));
        $list = $db->name('stock')->alias('a')
            ->join('tf_stock_item c','a.id = c.stock_id','LEFT')
            ->join('tf_item b','b.id = c.item_id','LEFT')
            ->field('a.id,a.shop_id,a.sn,a.creator_id,a.time,a.status')
            ->where($where)
            ->group('a.id')
            ->order('a.status asc,a.id desc');
        return $list;
    }


    //根据条件获取一条盘盈/盘亏单详细信息
    public function get_one_inventory_info($where)
    {
        $db = Db::connect(config('ddxx'));
        $stock_info = $db->name('stock')->where($where)->field('id,shop_id,time,sn,creator_id,complete_admin_id,remark')->find();
        $creator_name = Db::name('user')->where(['id'=>$stock_info['creator_id']])->value('user_login');
        $complete_name = Db::name('user')->where(['id'=>$stock_info['complete_admin_id']])->value('user_login');
        $shop_name = $db->name('shop')->where(['id'=>$stock_info['shop_id']])->value('name');
        $stock_info['creator_name'] = $creator_name;
        $stock_info['complete_name'] = $complete_name?$complete_name:'--';
        $stock_info['shop_name'] = $shop_name;
        $stock_info['time'] = date("Y-m-d",$stock_info['time']);
        $stock_item_list = $db->name('stock_item')->alias('a')
            ->join('tf_item b','a.item_id = b.id','LEFT')
            ->join('tf_item_category c','b.type = c.id','LEFT')
            ->where(['stock_id'=>$stock_info['id']])
            ->field('a.item_id,a.num,a.remark,b.title,b.bar_code,c.cname')
            ->select();
        $data['stock_info'] = $stock_info;
        $data['stock_item_info'] = $stock_item_list;
        return $data;
    }


    //获取调拨冻结状态中的商品总数
    public function get_allot_ice($shop_id,$item_id)
    {
        $db = Db::connect(config('ddxx'));
        $allot_on_way = $db->name('allot')->alias('a')
            ->join('tf_allot_item b','b.allot_id = a.id','LEFT')
            ->where(['a.from_shop'=>$shop_id,'a.status'=>0,'b.item_id'=>$item_id])->sum('b.num');
        return $allot_on_way;
    }

    //获取退货冻结状态中的商品总数
    public function get_reject_ice($shop_id,$item_id)
    {
        $db= Db::connect(config('ddxx'));
        $reject_on_way = $db->name('reject')->alias('a')
            ->join('tf_reject_item b','b.reject_id = a.id','LEFT')
            ->where(['a.shop_id'=>$shop_id,'a.status'=>0,'b.item_id'=>$item_id])->sum('b.num');
        return $reject_on_way;
    }

    //获取盘亏冻结状态中的商品总数
    public function get_inventory_loss_ice($shop_id,$item_id){
        $db= Db::connect(config('ddxx'));
        $inventory_loss_on_way = $db->name('stock')->alias('a')
            ->join('tf_stock_item b','b.stock_id = a.id','LEFT')
            ->where(['a.shop_id'=>$shop_id,'a.status'=>0,'a.type'=>2,'b.item_id'=>$item_id])->sum('b.num');
        return abs($inventory_loss_on_way);
    }

    //获取线上商城占用状态的商品总数
    public function get_order_tmp_ice($shop_id,$item_id)
    {
        $db = Db::connect(config('ddxx'));
        $order_tmp_on_way = $db->name('order_temp_data')
            ->where(['shop_id'=>$shop_id,'item_id'=>$item_id])->sum('num');
        return $order_tmp_on_way;
    }

    //获取盘盈冻结状态中的商品总数
    public function get_inventory_profit_ice($shop_id,$item_id)
    {
        $db = Db::connect(config('ddxx'));
        $inventory_profit_on_way = $db->name('stock')->alias('a')
            ->join('tf_stock_item','b.stock_id = a.id','LEFT')
            ->where(['a.shop_id'=>$shop_id,'a.status'=>0,'a.type'=>1,'b.item_id'=>$item_id])->sum('b.num');
        return $inventory_profit_on_way;
    }


}