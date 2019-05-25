<?php
/**
 * Author: zhoumi
 * Date: 2018/1/12
 * Description:
 */

namespace app\admin\model;

// use app\admin\model\BaseModel;


class FranchiseeModel extends BaseModel
{
    protected $table = 'tf_franchisee';

    public function getAddtimeAttr($time)
    {
        if ($time){
            return date('Y-m-d H:i:s');
        }
        return "未知";
    }

    //列表
    public function franchList($id = '')
    {
        $where = [];
        if (!empty($id)){
            $where['id'] = $id;
        }
        return $this->where($where)
            ->field('id,name,type,addtime')
            ->order('addtime asc');
    }

    //detail
    public function franchInfo($id)
    {
        return $res = $this->where(['id'=>$id])
            ->find();
    }

    //add or update data
    public function updateFranch($data = [],$id= '')
    {
        if ($id){
            return $res = $this->allowField(true)->save($data,['id'=>$id]);
        }
        return $res = $this->allowField(true)->save($data);
    }
    //删除门店
    public function del($id = 0)
    {
        $order = self::get($id);
        $res = $order->delete();
        if ($res){
            return true;
        }
        return false;
    }
}