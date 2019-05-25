<?php
/**
 * Created by PhpStorm.
 * User: shiyu
 * Date: 2018/7/18 0018
 * Time: 下午 14:14
 */

namespace app\admin\model\reject;


use think\Db;
use app\admin\model\BaseModel;

class RejectModel extends BaseModel
{
    protected $table = 'tf_reject';

    //-------------------一对多模型------------------------------------------------------------------//
    //一对多模型(商品信息)
    public function rejectItemList()
    {
        return $this->hasMany('\app\admin\model\reject\RejectItemModel','reject_id','id')
            ->field('id as reject_item_id,item_id,reject_id,cost_price,num,remark');
    }

    //获取采购单列表
    public function getRejectLists($where)
    {
        return $this->alias('a')
            ->join('tf_reject_item b','a.id = b.reject_id','LEFT')
            ->join('tf_item c','b.item_id = c.id','LEFT')
            ->field('a.*')
            ->where($where)
            ->group('a.id')
            ->order('a.status asc,a.addtime desc');
    }

    /**
     * [getRejectDetail 获取详情]
     * @param  integer $id   [id]
     * @return [type]        [description]
     */
    public function getRejectDetail($id = 0)
    {
        return self::with(['rejectItemList'])
            ->where(['id'=>$id])
            ->find();
    }

    /**
     * [getRejectDetail 更新oradd]
     * @param  array    $data   [shuzu]
     * @param  integer  $id     [id]
     * @return [type]        [description]
     */
    public function saveDatas($data = [],$id = 0)
    {
        if ($id) {
            return $this->allowField(true)->save($data,['id'=>$id]);
        }
        return $this->allowField(true)->save($data);
    }

}
