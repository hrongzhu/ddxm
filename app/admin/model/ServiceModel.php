<?php
/**
 * Author: chenjing
 * Date: 2017/12/20
 * Description:
 */

namespace app\admin\model;


use think\Db;
use think\Model;

class ServiceModel extends BaseModel
{
    protected $table = 'tf_service';
    protected $tableName = 'service';

    protected  function getIconAttr($icon)
    {
        return !empty($icon)?config('file_server_url').$icon:'';
    }

    protected  function getCoverAttr($cover)
    {
        return !empty($cover)?config('file_server_url').$cover:'';
    }

    protected  function getServicePriceAttr($price)
    {
        $levelModle = new LevelModel();
        $member_price = json_decode($price,true);
        $member_price = changeValTransferKey($member_price,'id','price');
        foreach ($member_price as &$v){
            $v['level_name'] = $levelModle->where(['id'=>$v['id']])->value('level_name');
        }
        return $member_price;
    }

    public function getInfo($id = 0,$field = ''){
        $field = $field?$field:'s_id,sname,bar_code,standard_price as service_price,icon,icon as icons,cover,cover as covers,remark';
        return $this->where(['s_id'=>$id])->field($field)->find();
    }

    public function getServiceList($where=[],$field='*')
    {
        $db = Db::connect(config('ddxx'));
        $list = $db->name('service')->where($where)->field($field)->select();
        return $list;
    }


}
