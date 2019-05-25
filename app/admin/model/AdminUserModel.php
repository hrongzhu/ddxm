<?php
/**
 * Author: chenjing
 * Date: 2018/1/10
 * Description:
 */

namespace app\admin\model;


use think\Model;

class AdminUserModel extends Model
{
    protected $table = 'ddxm_user';
    //设置查询时不需要的字段

    //获取当前登录管理员的信息
    public function getAdminUserInfo($uid = 0)
    {
        return $this->where(['id'=>$uid])->field('id,user_type,user_status,user_login,user_nickname,shop_id,mobile,user_email')->find()->toArray();
    }
    //获取当前登录管理员的部分信息
    public function getAdminUserBasicInfo($uid = 0,$field = '')
    {
        return $this->where(['id'=>$uid])->field($field)->find()->toArray();
    }

    //获取当前登录管理员的单个信息
    public function getAdminUserOneInfo($uid = 0,$field = '')
    {
        return $this->where(['id'=>$uid])->value($field);
    }
}