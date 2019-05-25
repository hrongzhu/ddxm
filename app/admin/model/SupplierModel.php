<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/17 0017
 * Time: 下午 15:26
 */

namespace app\admin\model;


class SupplierModel extends BaseModel
{

    public function get_all_supplier($field)
    {
        $list = db('supplier')->field($field)->select();
        return $list;
    }

}