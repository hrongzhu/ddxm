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

use app\admin\model\shop\WorkerModel;
use think\Model;
use think\Cache;
use think\Db;

class CouponModel extends Model
{


    public function coupons($where){
        $db = Db::connect(config('ddxx'));
        $list = $db->name('card')->alias('c')
            ->join('card_info ci','c.card_id=ci.id')
            ->join('member m','c.member_id=m.id')
            ->join('shop s','c.shop_id=s.id')
            ->field('m.nickname,m.mobile,s.id as shop_id,s.code,s.name,ci.title,ci.price,c.addtime,c.checktime,c.status as coupon_status,c.id as coupon_id,c.worker_id')
            ->where($where)
            ->where('c.paytime','>',0)
            ->order('c.status asc,c.addtime desc,c.checktime desc,ci.status desc');
        return $list;
    }

    /*
    获取商品分类
     */
    public function coupon_list(){

        $db = Db::connect(config('ddxx'));

        $coupon_list = $db
        ->name('shop_coupon')
        ->alias('s')
        ->join('shop_coupon_cate c','s.item_catid=c.id')
        ->field("s.id,s.title,s.price,s.subtitle,s.amount,FROM_UNIXTIME(s.stime,'%Y年%m月%d日') as stime,FROM_UNIXTIME(s.etime,'%Y年%m月%d日') as etime,s.status,FROM_UNIXTIME(s.addtime,'%Y年%m月%d日') as addtime,c.name as cate_name")
        ->where('s.status!=-1')
        ->order("id DESC")
        ->paginate(12);
        //print_r($coupon_list);die;

        return $coupon_list;
    }
    /*
    优惠券删除
     */
    public function coupon_delete($conponid){

        $db = Db::connect(config('ddxx'));

        $res = $db
        ->name('card_info')
        ->where(['id'=>$conponid])
        ->update(['status'=>-1]);
        //print_r($type);die;
        if($res){
            return 1;
        }else{
            return '删除失败！';
        }
    }
    /*
    获取优惠券分类
     */
    public function cate_list(){

        $db = Db::connect(config('ddxx'));

        $cate_list = $db
        ->name('shop_coupon_cate')
        ->field('id,name')
        ->select();
        //print_r($type);die;
        return $cate_list;
    }
    /*
    编辑优惠券查询
     */
    public function coupon_edit($couponid){

        $db = Db::connect(config('ddxx'));

        Db::startTrans();
        try{
            $res = $db
            ->name('item_category')
            ->field('id,pid,cname,thumb,status,sort')
            ->where('id='.$catid)
            ->update($data);

            if($data['status']==1){
                $status['status'] = '1';

                $rec = $db
                ->name('item_category')
                ->field('id,pid,cname,thumb,status,sort')
                ->where('status=-2')
                ->where('pid='.$catid)
                ->update($status);

            }elseif($data['status']==0){
                $status['status'] = '-2';

                $rec = $db
                ->name('item_category')
                ->field('id,pid,cname,thumb,status,sort')
                ->where('status=1')
                ->where('pid='.$catid)
                ->update($status);
            }

            Db::commit();
            return 1;
        }catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return 0;
        }
    }
    /*
    分类添加
     */
    public function type_add($data){

        $db = Db::connect(config('ddxx'));

        $res = $db->name('item_category')->insert($data);

        if($res){
            return 1;
        }else{
            return '添加失败！';
        }
    }
    /*
    商品列表查询
     */
    public function item_list(){

        $db = Db::connect(config('ddxx'));

        $iteminfo = $db
        ->name('item')
        ->field("id,title,status,FROM_UNIXTIME(addtime,'%Y年%m月%d日') as addtime")
        ->order("id DESC")
        ->paginate(12);
        //print_r($type);die;

        return $iteminfo;
    }

    public function get_one($id){
        $db = Db::connect(config('ddxx'));
        $list = $db->name('card_info')
            ->where(['id'=>$id])
            ->find();
        return $list;
    }

}
