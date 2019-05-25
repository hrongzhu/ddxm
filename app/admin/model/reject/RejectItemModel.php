<?php
/**
 * Created by PhpStorm.
 * User: shiyu
 * Date: 2018/7/18 0018
 * Time: 下午 14:14
 */

namespace app\admin\model\reject;


use think\Db;
use app\admin\model\BaseModel;

class RejectItemModel extends BaseModel
{
    protected $table = 'tf_reject_item';

    /**
     * [delRejectItem 删除]
     * @param  [type] $where [条件]
     * @return [type]        [description]
     */
    public function delRejectItem($where)
    {
        if (empty($where)) {
            return false;
        }
        return $this->where($where)->delete();
    }
}
