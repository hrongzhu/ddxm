<?php
/**
 * Created by PhpStorm.
 * User: shiyu
 * Date: 2018/7/23 0023
 * Time: 下午 19:09
 */

namespace app\admin\model;


use think\Db;

class AllotModel extends BaseModel
{
    /**
     * 获取调拨单列表
     * @param $where 查询条件
     * @return mixed
     */
    public function get_allot_list($where)
    {
        $res = Db::connect(config('ddxx'))->name('allot')->alias('a')
            ->join('tf_allot_item b','a.id = b.allot_id','LEFT')
            ->join('tf_item c','b.item_id = c.id')
            ->field('a.*')
            ->where($where)
            ->group('a.id')
            ->order('a.status asc,a.time desc');
        return $res;
    }

    /**
     * 获取总金额
     * @param $where 获取条件
     * @param $type 获取类型 字段名
     */
    public function get_amount_total($where,$type)
    {
        $res =Db::connect(config('ddxx'))->name('allot')->alias('a')->where($where)->sum($type);
        return $res;
    }


    /**
     * 获取调拨单相关信息
     * @param $id 调拨单ID
     * @return array
     */
    public function get_allot_info($id)
    {
        $db = Db::connect(config('ddxx'));
        //调拨单基本数据
        $allotInfo = $db->name('allot')->where(['id'=>$id])->find();
        //调拨单商品数据
        $allotItemInfo = $db->name('allot_item')->where(['allot_id'=>$id])->select();
        //查询库存
        foreach ($allotItemInfo as $k=>$v){
            $stock = $db->name('purchase_price')->where(['shop_id'=>$allotInfo['from_shop'],'item_id'=>$v['item_id']])->order('id desc')->value('stock');
            $goods = $db->name('item')->where(['a.id'=>$v['item_id']])->alias('a')
                ->join('tf_item_category b','a.type = b.id','LEFT')
                ->field('a.title,a.bar_code,b.cname')
                ->find();
            $allotItemInfo[$k]['stock'] = isset($stock)?$stock:0;
            $allotItemInfo[$k]['title'] = $goods['title'];
            $allotItemInfo[$k]['bar_code'] = $goods['bar_code'];
            $allotItemInfo[$k]['cname'] = $goods['cname'];
            $allotItemInfo[$k]['md_price'] = $v['now_md_univalent'];
            $allotItemInfo[$k]['store_cost'] = $v['now_store_cost'];
            unset($allotItemInfo[$k]['now_md_univalent']);
            unset($allotItemInfo[$k]['now_store_cost']);
        }
        return [
            'allotInfo'=>$allotInfo,
            'allotItemInfo'=>$allotItemInfo
        ];
    }

}
