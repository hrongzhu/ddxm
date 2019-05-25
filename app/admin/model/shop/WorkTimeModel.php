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
use think\Db;

class WorkTimeModel extends BaseModel
{
    protected $table = 'tf_worktime';

    //
    public function getAddtimeAttr($time)
    {
       return date('Y-m-d H:i:s',$time);
    }
//    public function getStimeAttr($time)
//    {
//        return date('H:i',$time);
//    }
//    public function getEtimeAttr($time)
//    {
//        return date('H:i',$time);
//    }

    //员工排班列表
    public function workTimeList($w_id)
    {
        return $this->table($this->table)
            ->field('id,wid,addtime,stime,etime,status,isloop,mon,tue,wed,thu,fri,sat,sun')
            ->where(['workid'=>$w_id])
            ->select();
    }

    //员工排班列表
    public function workTimeLists($w_id,$week)
    {
        return collection($this->table($this->table)
            ->field('time_index')
            ->where(['workid'=>$w_id,'week_day'=>$week])
            ->select())->toArray();
    }

    //更新或者添加数据
    public function updateWorker($data = [],$id= '')
    {
        if ($id){
            return $res = $this->allowField(true)->save($data,['id'=>$id]);
        }
        return $res = $this->allowField(true)->save($data);
    }

    //添加排班
    public function scheduling($workid,$wid,$week,$time_data)
    {
        if (empty($workid) || empty($week) || empty($time_data) || empty($wid))
        {
            return false;
        }
        $dateArr= [];
        foreach ($time_data as $k => $v) {
            //将时间点转换为时间段的时间戳
            $dateArr[$v]['stime'] = strtotime($v.':00');
            $dateArr[$v]['etime'] = strtotime($v.':59');
            $dateArr[$v]['workid'] = $workid;
            $dateArr[$v]['wid'] = $wid;
            $dateArr[$v]['isloop'] = 1;
            $dateArr[$v]['week_day'] = $week;
            $dateArr[$v]['time_index'] = $v;
            $dateArr[$v]['addtime'] = time();
        }
//        $res = (new WorkTimesModel())->allowField(true)->saveAll($dateArr);
        $this->allowField(true)->saveAll($dateArr);
        return true;
    }

    //删除排班数据
    public function DelScheduling($wid = 0)
    {
        if (empty($wid)) {
            return false;
        }
        return $this->where('wid','=',$wid)->delete();
    }
}
