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
namespace app\admin\model\item;

use app\admin\model\BaseModel;

class ItemCategoryModel extends BaseModel
{
    protected $table = 'tf_item_category';

    //返回以主键为键名的数组
    public function getAllCategoryDatas()
    {
        $datas = changePkAsKey(collection($this->field('id,cname')->select())->toArray());
        return $datas;
    }

}
