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
namespace app\admin\model;

use think\Model;
use think\Cache;
use think\Db;

class LvModel extends BaseModel
{


    //模板列表展示
    public function list()
    {
        $db = Db::connect(config('ddxx'));

        return $db->name('lv')->field("*,FROM_UNIXTIME(addtime,'%Y年%m月%d日 %H时%i分') as addtime")->where("status!=-1")->paginate(12);
    }
    //模板列表查询
    public function lists()
    {
        $db = Db::connect(config('ddxx'));

        return $db->name('lv')->field("id,title")->where("status!=-1")->select();
    }
    //模板单条详情
    public function find($id)
    {
        $db = Db::connect(config('ddxx'));

        return $db->name('lv')->field("*,FROM_UNIXTIME(addtime,'%Y年%m月%d日 %H时%i分') as addtime")->where("status!=-1")->where("id=".$id)->find();
    }
    //模板信息操作
    public function operation($id,$status)
    {
        $db = Db::connect(config('ddxx'));

        $data['status'] = $status;

        if($status==1){
            $where['status'] = 0;
        }else if($status==0){
            $where['status'] = 1;
        }else{
            $where = '1=1';
        }

        $res = $db
        ->name('lv')
        ->where('id='.$id)
        ->where($where)
        //->fetchSql(true)
        ->update($data);

        //print_r($res);die;
        if($res){
            return 1;
        }else{
            return '操作失败！';
        }
    }
    /*
    税率模板添加操作
     */
    public function add($data){

        $db = Db::connect(config('ddxx'));

        $res = $db->name('lv')->insert($data);

        if($res){
            return 1;
        }else{
            return '添加失败！';
        }
    }
    /*
    税率模板编辑操作
     */
    public function edit($id,$data)
    {

        $db = Db::connect(config('ddxx'));

        $res = $db->name('lv')
        ->where('id='.$id)
        //->fetchSql(true)
        ->update($data);
        if($res){
            return 1;
        }else{
            return '编辑失败！';
        }
    }
}
