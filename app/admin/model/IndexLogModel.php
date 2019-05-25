<?php
/**
 * Author: chenjing
 * Date: 2018/1/10
 * Description:
 */

namespace app\admin\model;


use think\Model;

class IndexLogModel extends Model
{
    protected $table = 'ddxm_index_log';
    //设置查询时不需要的字段
    public function getAddtimeAttr($value)
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
    public function logsList()
    {
        return self::field('id,member_id,mobile,action_path,content,addtime,user_name')
            ->order('addtime desc');
    }
}
