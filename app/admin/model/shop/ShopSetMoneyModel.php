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

class ShopSetMoneyModel extends BaseModel
{
    protected $table = 'tf_shop_set_money';

    public function getAddtimeAttr($addtime)
    {
        return date('Y-m-d H:i:s',$addtime);
    }


    //更新或者添加数据
    public function updates($data = [])
    {
        return $res = $this->allowField(true)->save($data);
    }

    public function getlist($shop_id)
    {
        return $this->where(['shop_id'=>$shop_id])->select();
    }

    public function del($id = 0)
    {
        return $res = $this->destroy($id);
    }

}
