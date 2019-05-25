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

class PostageModel extends BaseModel
{
    protected $table = 'tf_postage';

    public function getAddtimeAttr($addtime)
    {
        return date('Y-m-d H:i:s',$addtime);
    }


    //员工列表
    public function postageList()
    {
        return $this->table($this->table)
            ->order('addtime ASC');
    }

    //获取当前员工信息
    public function postageInfo($id)
    {
        return $this->table($this->table)
            ->field('id,temp_name,type,first_price,first_num,add_num,add_price,addtime,status')
            ->where(['id'=>$id])
            ->find();
    }

    //更新或者添加数据
    public function updatePostage($data = [],$id= 0)
    {
        if ($id){
            return $res = $this->allowField(true)->save($data,['id'=>$id]);
        }
        return $res = $this->allowField(true)->save($data);
    }

}
