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
namespace app\admin\model\user;

use app\admin\model\BaseModel;

class UserDeclareModel extends BaseModel
{
    protected $table = 'tf_order_declare';

    public function getFrontIdcardAttr($value)
    {
        if (empty($value)) {
            return '';
        }
        return config('domino.domino').$value;
    }

    public function getRevIdcardAttr($value)
    {
        if (empty($value)) {
            return '';
        }
        return config('domino.domino').$value;
    }
}
