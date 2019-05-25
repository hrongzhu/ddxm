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

class UserCardModel extends BaseModel
{
    protected $table = 'tf_card';

    protected $autoWriteTimestamp=true;

    protected $createTime='regtime';
    protected $updateTime='';

    //事件
    protected static function init()
    {
        self::afterUpdate(function ($userCardModel) {
            Logs::actionLogRecord($userCardModel->msg);
            LogsController::actionLogRecord($userCardModel->msg);
        });
        self::afterInsert(function ($userCardModel) {
            Logs::actionLogRecord($userCardModel->msg);
            LogsController::actionLogRecord($userCardModel->msg);
        });
    }

    public function getRegtimeAttr($value)
    {
        return date('Y-m-d H:i',$value);
    }

    //一对一模型(获取店铺信息)
    public function shopInfo()
    {
        return $this->belongsTo('app\admin\model\shop\ShopModel','shop_code','code')
            ->field('code,name');
    }


    //获取用户列表信息
    public function getUserCardList($where)
    {
        $data = $this->alias('a')
            ->where($where)
            ->where('paytime','>',0)
            ->field('a.id,a.title,a.shop_id,a.title,a.thumb,a.sn,a.num,a.my_num,a.price,a.addtime,a.status,a.is_default,a.paytime,a.yxqtime,a.yxq,b.nickname,c.name as shop_name')
            ->join('tf_member b','a.member_id = b.id','LEFT')
            ->join('tf_shop c','a.shop_id = c.id','LEFT')
            ->order('is_default = 1 desc')
            ->select();
        if (empty($data)) {
            return [];
        }
        $data = collection($data)->toArray();

        foreach ($data as $k => $v){
            $data[$k]['sy_day'] = (floor(($v['yxqtime'] - time())/3600/24) <= 0)?0:floor(($v['yxqtime'] - time())/3600/24);
            $data[$k]['thumb'] = config('file_server_url').$v['thumb'];
        }
        return $data;
    }

    //更新或者添加数据
    public function updateUserCard($data = [],$id= '')
    {
        if ($id){
            return $res = $this->allowField(true)->save($data,['id'=>$id]);
        }
        return $res = $this->allowField(true)->save($data);
    }

    //获取用户详细信息
    public function getUserDetailInfo($id = 0)
    {
        return $this->alias('a')
            ->field('a.id,a.pid,a.mobile,a.shop_code,a.nickname,a.amount,a.profit,a.openid,a.regtime,a.status,a.is_staff,b.nickname as recommend_name')
            ->where('a.id','=', $id)
            ->join('tf_member b','a.pid = b.id','LEFT')
            ->find()->toArray();
    }
}
