<?php
/**
 * Author: chenjing
 * Date: 2018/1/10
 * Description:
 */

namespace app\admin\model;


use think\Model;

class LogsModel extends Model
{
    protected $table = 'ddxm_log';
    //设置查询时不需要的字段
    public function getAccessTimeAttr($value)
    {
        if (empty($value)) {
            return '';
        }
        return date('Y-m-d H:i:s',$value);
    }


    public function saveLogs($datas)
    {
        return $this->validate(true)->save($datas);
    }

    //门店列表
    public function logsList($where = [])
    {
        return self::field('id,user_name,action_path,action_event,access_time')
            ->where($where)
            ->order('access_time desc');
    }
}