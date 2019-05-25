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

use app\admin\controller\LogsController;
use app\admin\model\BaseModel;
use app\admin\common\Logs;

class CardInfoModel extends BaseModel
{
    protected $table = 'tf_card_info';

    public function getcardlists($where)
    {
        return $this->where($where)
            ->alias('a')
            ->field('a.id,a.title,a.sid,b.name as shop_name,a.price')
            ->join('tf_shop b','a.sid = b.id','LEFT')
            ->order('a.sid desc,a.price asc')
            ->select();
    }

    //获取卡片信息
    public function getcardinfo($card_id)
    {
        return $this->where(['id'=>$card_id])->find()->toArray();
    }

    //获取用户优惠券信息（就是原来的card表）
    public function getUserCoupList($where)
    {
        return $this
            ->alias('a')
            ->join('tf_shop b','a.sid = b.id','LEFT')
            ->field('a.id,a.title,a.thumb,price,a.yxq,a.status,a.sid,b.name as shop_name,a.price,a.xgnums')
            ->where($where)
            ->order('a.sid desc,a.price asc');
    }
}
