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
use app\admin\model\FranchiseeModel;
use app\admin\model\ServiceModel;
use think\Db;

class WorkerModel extends BaseModel
{
    protected $table = 'tf_worker';

    //
    public function getAddtimeAttr($time)
    {
       return date('Y-m-d H:i:s',$time);
    }

    public function getTypeAttr($type)
    {
        $server_name = (new ServiceModel())->where(['id'=>['in',$type]])->field('sname')->select();
        return arrayFieldTransferString($server_name,'sname');
    }

    public function getIsworkAttr($type)
    {
        $arr = [
            0=>'停工中',
            1=>'开工中'
        ];
        return $arr[$type];
    }
//    public function getHeadAttr($type)
//    {
//        if (is_numeric(strpos($type,'http')) || is_numeric(strpos($type,'https'))){
//            return $type;
//        }
//        return 'http://'.$_SERVER['HTTP_HOST'].'/'.$type;
//    }

    //员工列表
    public function workerList($where,$o_where = '')
    {
        return $this->table($this->table)
            ->alias('a')
            ->field('a.id,a.workid,a.name,a.type,a.mobile,a.pid,a.sid,a.remark,a.iswork,a.addtime,a.lv,a.pay,b.name as shop_name,c.name as p_name')
            ->join('tf_shop b','a.sid = b.id','LEFT')
            ->join('tf_franchisee c','a.pid = c.id','LEFT')
            ->where($o_where)
            ->where($where)
            ->group('a.id')
            ->order('a.id ASC');
    }

    //获取当前员工信息
    public function workerInfo($w_id)
    {
        return $this->table($this->table)
            ->alias('a')
            ->field('a.id,a.workid,a.name,a.type as types,a.head,a.mobile,a.pid,a.detail,a.sid,a.remark,a.iswork,a.addtime,a.lv,a.pay,b.name as shop_name,c.name as p_name')
            ->join('tf_shop b','a.sid = b.id','LEFT')
            ->join('tf_franchisee c','a.pid = c.id','LEFT')
            ->where(['a.id'=>$w_id])
            ->find();
    }

    //获取当前员工信息
    public function workerInfos($wid)
    {
        return $this->table($this->table)
            ->alias('a')
            ->field('a.id,a.workid,a.name,a.type,a.head,a.mobile,a.pid,a.detail,a.sid,a.remark,a.iswork,a.addtime,a.lv,a.pay,b.name as shop_name,c.name as p_name')
            ->join('tf_shop b','a.sid = b.id','LEFT')
            ->join('tf_franchisee c','a.pid = c.id','LEFT')
            ->where(['a.workid'=>$wid])
            ->find();
    }
    //更新或者添加数据
    public function updateWorker($data = [],$id= '')
    {
        if ($id){
            return $res = $this->allowField(true)->save($data,['id'=>$id]);
        }
        return $res = $this->allowField(true)->save($data);
    }

    //开收工
    public function setIswork($workid = 0)
    {
        $info = $this->where(['workid' => $workid])->field('iswork as works')->find();
        if ($info->works == 0)
        {
            $res = $this->allowField(true)->save(['iswork' => 1],['workid' => $workid]);
        }
        else
        {
            $res = $this->allowField(true)->save(['iswork' => 0],['workid' => $workid]);
        }
        return $res;
    }


}
