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

class AgreeModel extends Model
{
    /*
    获取沙龙列表
     */
    public function agree_list(){

        $db = Db::connect(config('ddxx'));

        $agree_list = $db
        ->name('article')
        ->alias('a')
        ->join("article_cat c","a.catid=c.id")
        ->field("a.id,a.title,a.subtitle,c.catname,a.catid,a.status,FROM_UNIXTIME(a.addtime,'%Y年%m月%d日') as addtime")
        ->where('a.status!= -1')
        ->where('c.status=1')
        ->where('c.id = 20 or c.id=4 or c.id=18 or c.id=19 or c.id=7')
        ->order("id DESC")
        //->fetchSql(true)
        ->paginate(12);
        //print_r($agree_list);die;

        return $agree_list;
    }
    /*
    上下架删除操作
     */
    public function agree_operation($cardid,$status){

        $db = Db::connect(config('ddxx'));

        $data['status'] = $status;

        /*if($status==1){
            $where['status'] = 0;
        }else{
            $where['status'] = 1;
        }*/

        $res = $db
        ->name('article')
        ->where('id='.$cardid)
        //->where($where)
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
    加盟商列表
    */
    public function franchisee_list(){

        $db = Db::connect(config('ddxx'));

        $shop_list = $db
        ->name('franchisee')
        ->field('id,name')
        ->select();
        //print_r($type);die;
        return $shop_list;
    }
    /*
    店铺列表
    */
    public function shop_list(){

        $db = Db::connect(config('ddxx'));

        $shop_list = $db
        ->name('shop')
        ->field('id,name')
        ->select();
        //print_r($type);die;
        return $shop_list;
    }
    /*
    店铺列表
    */
    public function shoplist($pid){

        $db = Db::connect(config('ddxx'));

        $shop_list = $db
        ->name('shop')
        ->field('id,name')
        ->where("account_id=".$pid)
        ->select();
        //print_r($type);die;
        return $shop_list;
    }
    /*
    沙龙编辑查询
     */
    public function agree_find($artid){

        if($artid==''){
            return '参数错误';
        }
        $db = Db::connect(config('ddxx'));

        $agree = $db
        ->name('article')
        ->where('status != -1')
        ->where("id=".$artid)
        //->fetchSql(true)
        ->find();

        return $agree;

    }
    /*
    沙龙编辑
     */
    public function agree_edit($artid,$data){

        $db = Db::connect(config('ddxx'));

        $res = $db
        ->name('article')
        ->where('id='.$artid)
        ->update($data);

        if($res){
            return 1;
        }else{
            return '修改失败！';
        }

    }
    /*
    沙龙添加
     */
    public function agree_add($data){

        $db = Db::connect(config('ddxx'));

        $res = $db->name('article')->insert($data);

        if($res){
            return 1;
        }else{
            return '添加失败！';
        }
    }
}
