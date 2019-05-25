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
namespace app\admin\model\shop;

use app\admin\model\BaseModel;
use think\Db;

/**
 * 商品库存模型
 * 本类 比较特殊  不使用模型 因为涉及到的表比较多 就用Db执行查询
 */
class StockModel extends BaseModel
{
    /**
     * [getItemStock 获取商品库存]
     * @param  integer $item_id [商品did]
     * @param  integer $shop_id [仓库id]
     * @return [type]           [description]
     */
    public function getItemStock($item_id = 0,$shop_id = 0)
    {
        $db = Db::connect(config('ddxx'));
        $info = $db->name('shop_item')
             ->where(['item_id'=>$item_id,'shop_id'=>$shop_id])
             ->field('stock,stock_ice')
             ->find();
        return $info['stock'] - $info['stock_ice'];
    }

    /**
     * [getItemCostPrice 获取商品成本价]
     * @param  integer $item_id [商品did]
     * @param  integer $shop_id [仓库id]
     * @return integer          [金额]
     */
    public function getItemCostPrice($item_id = 0,$shop_id = 0)
    {
        $db = Db::connect(config('ddxx'));
        $max_id =  $db->name('purchase_price')->where(['shop_id'=>$shop_id,'item_id'=>$item_id])->max('id');
        $cost_price = $db->name('purchase_price')
            ->where(['id'=>$max_id])
            ->value('store_cost_after');
        if ($cost_price) {
            return $cost_price;
        }
        return 0;
    }

    /**
     * [getItemCostPrice 获取商品假成本价]
     * @param  integer $item_id [商品did]
     * @param  integer $shop_id [仓库id]
     * @return integer          [金额]
     */
    public function getItemFakerCostPrice($item_id = 0,$shop_id = 0)
    {
        $db = Db::connect(config('ddxx'));
        $max_id =  $db->name('purchase_price')->where(['shop_id'=>$shop_id,'item_id'=>$item_id])->max('id');
        $cost_price = $db->name('purchase_price')
            ->where(['id'=>$max_id])
            ->value('md_price_after');
        if ($cost_price) {
            return $cost_price;
        }
        return 0;
    }

    /**
     * [getItemCostPrice 获取商品其他数据信息]
     * @param  integer $item_id [商品did]
     * @param  integer $shop_id [仓库id]
     * @return                  [description]
     */
    public function getItemValue($item_id = 0,$shop_id = 0,$field = '')
    {
        $db = Db::connect(config('ddxx'));
        $max_id =  $db->name('purchase_price')->where(['shop_id'=>$shop_id,'item_id'=>$item_id])->max('id');
        $value = $db->name('purchase_price')
            ->where(['id'=>$max_id])
            ->value($field);
        if ($value) {
            return $value;
        }
        return 0;
    }


    /**
     * [updateItemStock 更新商品库存]
     * @param  integer $item_id [商品id]
     * @param  integer $shop_id [仓库id]
     * @param  integer $num     [数量]
     * @param  integer $order_id     [单号id]
     * @param  integer $type    [类型 6 商品零售 7 会员退货]
     * @return [type]           [description]
     */
    public function updateItemStock($item_id = 0,$shop_id = 0,$num = 0,$order_id = 0,$type = 0)
    {
        $db = Db::connect(config('ddxx'));
        $max_id =  $db->name('purchase_price')->where(['shop_id'=>$shop_id,'item_id'=>$item_id])->max('id');
        $info = $db->name('purchase_price')
            ->field('id,time',true)
            ->where(['id'=>$max_id])
            ->find();
        $stock = $info['stock'] - $num;
        if ($stock <= 0) {
            $stock = 0;
        }
        $data = $info;
        $data['stock'] = $stock;
        $data['type']  = $type;
        $data['pd_id']  = $order_id;
        $data['time']  = time();

        $res = $db->name('purchase_price')->insert($data);
        // 发货了就减真实库存
        $curr_stock = $db->name('shop_item')->where(['shop_id'=>$shop_id,'item_id'=>$item_id])->value('stock');
        if ($curr_stock - $num <= 0 ) {
            $num = $num - $curr_stock;
        }
        $db->name('shop_item')->where(['shop_id'=>$shop_id,'item_id'=>$item_id])->setDec('stock',$num);

        // $res = Db::name('purchase_price')->where(['id'=>$info['id']])->update(['stock'=>$stock]);
        if ($res) {
            $this->updateStockAlert($item_id,$shop_id,$stock);
            return true;
        }
        return false;
    }

    /**
     * updateItemCostPrice 重新计算仓库商品平均成本
     * @param  integer $item_id [商品id]
     * @param  integer $shop_id [仓库id]
     * @param  float   $price   [商品价格]
     * @param  integer $num     [商品数量]
     * @return [type]           [description]
     */
    public function updateItemCostPrice($item_id = 0,$shop_id = 0,$price = 0.00,$faker_price = 0.00,$num = 0,$order_id = 0,$type = 7)
    {
        $db = Db::connect(config('ddxx'));
        $max_id =  $db->name('purchase_price')->where(['shop_id'=>$shop_id,'item_id'=>$item_id])->max('id');
        $stock_info = $db->name('purchase_price')->where(['id'=>$max_id])->find();
        //计算假成本
        $total_num = $stock_info['stock'] + $num;
        $md_price_before = empty($stock_info['md_price_after'])?0:$stock_info['md_price_after'];

        $faker_cost = round($md_price_before * $stock_info['stock'],2) + round($faker_price * $num,2);
        $md_price_after = round($faker_cost/$total_num,2);
        //  计算真实库存成本
        $store_cost_before = empty($stock_info['store_cost_after'])?0:$stock_info['store_cost_after'];//最近一次商品的入库后成本等于本次商品的入库前成本
        $real_cost = round($store_cost_before * $stock_info['stock'],2) + round($price * $num,2);
        $store_cost_after = round($real_cost/$total_num,2);
        $stock = ($stock_info['stock']+$num) < 0?0:$stock_info['stock']+$num;
        //添加调出仓商品成本变化数据，减少调出仓库库存
        $purchase_price_data = [
            'shop_id'=>$shop_id,
            'type'=>$type,
            'pd_id'=>$order_id,
            'item_id'=>$item_id,
            'md_price_before'=>$md_price_before,
            'md_price_after'=>$md_price_after,
            'store_cost_before'=>$store_cost_before,
            'store_cost_after'=>$store_cost_after,
            'stock'=>$stock,
            'time'=>time()
        ];
        $db->startTrans();
        try{
            $res1 = $db->name('purchase_price')->insert($purchase_price_data);
            // 库存还原
            $res2 = $db->name('shop_item')->where(['shop_id'=>$shop_id,'item_id'=>$item_id])->setInc('stock',$num);
            if ($res1&&$res2) {
                $db->commit();
                return true;
            }else{
                $db->rollback();
                return false;
            }
        }catch (\Exception $e){
            $db->rollback();
            return false;
        }
    }

    /**
     * [updateStockAlert 库存预警主要方法]
     * @param  integer $item_id [商品id]
     * @param  integer $shop_id [仓库id]
     * @param  integer $stock   [库存量]
     * @return [type]           [description]
     */
    public function updateStockAlert($item_id = 0,$shop_id = 0,$stock = 0)
    {
        if (empty($item_id) || empty($shop_id)) {
            return false;
        }
        $db = Db::connect(config('ddxx'));
        $item_stock_alert = model('Item')->where(['id'=>$item_id])->value('stock_alert');
        if ($stock <= 0) {
            $stock = 0;
        }
        if ($stock <= $item_stock_alert) {
            //添加或更新一条预警信息
            $info = $db->name('item_stock_alert')->where(['item_id'=>$item_id,'shop_id'=>$shop_id])->find();
            if (!empty($info)) {
                if ($info['del'] != 1){
                    $datas['addtime'] = time();
                    if ($info['status'] == 0){
                        $datas['status'] = 1;
                    }
                    $datas['stock'] = $stock;
                    $db->name('item_stock_alert')->where(['id'=>$info['id']])->update($datas);
                }
            }else{
                $data['item_id'] = $item_id;
                $data['shop_id'] = $shop_id;
                $data['stock']   = $stock;
                $data['addtime'] = time();
                $db->name('item_stock_alert')->insert($data);
            }
        }
    }
}
