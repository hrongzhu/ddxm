<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/9
 * Time: 17:32
 */

namespace app\admin\model;


use think\Db;

class AttrModel extends BaseModel
{
    public function get_attr_list($where)
    {
        $db = Db::connect(config('ddxx'));
        return $db->name('attr')->where($where)->paginate(15);
    }


}