<?php
/**
 * Author: chenjing
 * Date: 2018/1/16
 * Description:
 */

namespace app\admin\model;
use app\admin\model\BaseModel;
use app\admin\model\item\ItemCategoryModel;
use think\Db;

class AdvertModel extends BaseModel
{
    protected $table = 'tf_advert_position';

    public function getAddtimeAttr($v)
    {
        return date('Y-m-d H:i',$v);
    }
    public function getAdtypeAttr($type)
    {
        $arr = [
            0=>'文本',
            1=>'图片',
        ];
        return $arr[$type];
    }
    public function shop()
    {
        return $this->hasOne('\app\admin\model\shop\ShopModel','id','shop_id')
            ->field('id,name,code');
    }
    public function advertLists()
    {
        return $this->table($this->table)
            ->alias('a')
            ->field('a.id,a.shop_id,a.name,a.adtype,a.width,a.height,a.addtime,b.name as shop_name')
            ->join('tf_shop b','a.shop_id = b.id','LEFT')
            ->order('a.id asc');
//            ->select();
    }
    public function advertinfo($id)
    {
        return self::with('shop')
            ->where(['id'=>$id])
            ->find()->toArray();
    }
    public function advertDel($id)
    {
        return self::destroy(['id'=>$id]);
    }
    //分类列表
    public function cateList()
    {
        return (new ItemCategoryModel())
            ->field('id,cname')
            ->select();
    }

    //新增货修改
    public function addOrEdit($data = [],$id = '')
    {
        if ($id){
            $res = $this->allowField(true)->save($data,['id'=>$id]);
        }else{
            $res = $this->allowField(true)->save($data);
        }
        return $res;
    }
}