<?php
/**
 * Created by PhpStorm.
 * User: shiyu
 * Date: 2018/7/18 0018
 * Time: 下午 14:14
 */

namespace app\admin\model;


use think\Db;

class PurchaseModel extends BaseModel
{
    //获取采购单列表
    public function get_purchase_list($where)
    {
        $res = Db::connect(config('ddxx'))->name('purchase')->alias('a')
            ->join('tf_purchase_item b','a.id = b.purchase_id','LEFT')
            ->join('tf_item c','b.item_id = c.id')
            ->field('a.*')
            ->where($where)
            ->group('a.id')
            ->order('a.status asc,a.id desc');
        return $res;
    }

    /**
     * 获取总金额
     * @param $where 获取条件
     * @param $type 获取类型 字段名
     */
    public function get_amount_total($where,$type)
    {
        $res =Db::connect(config('ddxx'))->name('purchase')->alias('a')
            ->join('tf_purchase_item pi','a.id = pi.purchase_id','LEFT')
            ->join('tf_item c','c.id = pi.item_id','LEFT')
            ->where($where)->sum('a.'.$type);
        return $res;
    }

    /**
     * 获取一条采购单详情
     * @param $id 采购单ID
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function get_one_purchase($id)
    {
        $db = Db::connect(config('ddxx'));
        $purchase_info = $db->name('purchase')->alias('p')
            ->join('tf_purchase_item pi','p.id = pi.purchase_id','LEFT')
            ->join('tf_item i','pi.item_id = i.id','LEFT')
            ->join('tf_item_category ic','i.type = ic.id','LEFT')
            ->field('ic.cname,i.title,i.bar_code,i.id as item_id,pi.num,pi.remark,pi.id as purchase_item_id,pi.cg_amount,pi.md_amount,pi.cg_univalent,pi.md_univalent,p.amount,p.real_amount')
            ->where(['p.id'=>$id])
            ->select();
        foreach ($purchase_info as $k=>$v){
            if($v['remark']==null){
                $purchase_info[$k]['remark'] = '无';
            }
        }
        return $purchase_info;
    }


}