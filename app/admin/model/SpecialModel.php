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

class SpecialModel extends Model
{
    /*
    获取专题列表
     */
    public function special_list(){

        $db = Db::connect(config('ddxx'));

        $special_list = $db
        ->name('article')
        ->alias('a')
        ->join("article_cat c","a.catid=c.id")
        ->field("a.id,a.title,a.subtitle,c.catname,a.status,FROM_UNIXTIME(a.addtime,'%Y年%m月%d日') as addtime")
        ->where('a.status!= -1')
        ->where('c.status=1')
        ->where('c.id = 3')
        ->order("a.sort DESC")
        //->fetchSql(true)
        ->paginate(12);
        //print_r($special_list);die;

        return $special_list;
    }
    /*
    上下架删除操作
     */
    public function special_operation($cardid,$status){

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
    专题编辑查询
     */
    public function special_find($artid){

        if($artid==''){
            return '参数错误';
        }
        $db = Db::connect(config('ddxx'));

        $special = $db
        ->name('article')
        ->where('status != -1')
        ->where("id=".$artid)
        //->fetchSql(true)
        ->find();

        return $special;

    }
    /*
    服务专题编辑
     */
    public function special_edit($artid,$data){

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
    专题添加
     */
    public function special_add($data){

        $db = Db::connect(config('ddxx'));

        $res = $db->name('article')->insert($data);

        if($res){
            return 1;
        }else{
            return '添加失败！';
        }
    }
    /*
    活动专题列表
     */
    public function activity_list(){

        $db = Db::connect(config('ddxx'));

        $activity_list = $db
        ->name('article')
        ->alias('a')
        ->join("article_cat c","a.catid=c.id")
        ->field("a.id,a.title,a.subtitle,c.catname,a.status,FROM_UNIXTIME(a.addtime,'%Y年%m月%d日') as addtime")
        ->where('a.status!= -1')
        ->where('c.status=1')
        ->where('c.id = 2')
        ->order("a.sort DESC")
        //->fetchSql(true)
        ->paginate(12);
        //print_r($special_list);die;

        return $activity_list;
    }
    /*
    活动专题添加
     */
    public function activity_add($data){

        $db = Db::connect(config('ddxx'));

        $res = $db->name('item_activity')->insert($data);

        if($res){
            return 1;
        }else{
            return '添加失败！';
        }
    }
    public function getactid()
    {
        $db = Db::connect(config('ddxx'));

        $actid = $db->name('article')->getLastInsID();

        return $actid;
    }
    public function activity_edit($artid,$data)
    {
        $db = Db::connect(config('ddxx'));

        $res = $db
        ->name('item_activity')
        ->where('id='.$artid)
        ->update($data);

        if($res){
            return 1;
        }else{
            return '修改失败！';
        }
    }
    /*
    活动专题删除操作
     */
    public function activity_operation($cardid,$status){

        $db = Db::connect(config('ddxx'));

        $data['status'] = $status;

        /*if($status==1){
            $where['status'] = 0;
        }else{
            $where['status'] = 1;
        }*/

        $res = $db
        ->name('item_activity')
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
    首页数据统计
     */
    public function index_count($shop_id){
        $db = Db::connect(config('ddxx'));

        //$where['type!']      = 3;
        $where['order_status'] = 2;
        $where['shop_id']      = $shop_id;
        $Yesterday    = strtotime(date("Y-m-d",strtotime("-1 day")));
        $today    = strtotime(date("Y-m-d"));

        //昨天总金额
        $data['goods'] = $db
        ->name('order')
        ->where($where)
        ->where("type=1")
        ->where("addtime>".$Yesterday)
        ->where("addtime<".$today)
        //->fetchSql(true)
        ->sum('amount');

        $data['yuyue'] = $db
        ->name('order')
        ->where("shop_id=".$shop_id)
        ->where("type=2")
        ->where("paytime>0")
        ->where("addtime>".$Yesterday)
        ->where("addtime<".$today)
        //->fetchSql(true)
        ->sum('amount');

        $data['amount'] = $data['goods']+$data['yuyue'];

            //预约人数
        $data['nums'] = $db
        ->name('order')
        ->where("type=2")
        ->where("shop_id=".$shop_id)
        ->where("paytime>0")
        ->where("addtime>".$Yesterday)
        ->where("addtime<".$today)
        //->fetchSql(true)
        ->count();

        //卡片销售金额
        $data['cardprice'] = $db
        ->name('order')
        ->where("type=3")
        ->where("shop_id=".$shop_id)
        ->where("addtime>".$Yesterday)
        ->where("addtime<".$today)
        ->where("paytime>0")
        ->sum('amount');
        //现金支付
        $data['weixin'] = $db
        ->name('order')
        ->where("type=1")
        ->where($where)
        ->where("pay_way=1")
        ->where("addtime>".$Yesterday)
        ->where("addtime<".$today)
        ->sum('amount');

        $data['num'] = $db
        ->name('order')
        ->where("type=2")
        ->where("shop_id=".$shop_id)
        ->where("pay_way=1")
        ->where("paytime>0")
        ->where("addtime>".$Yesterday)
        ->where("addtime<".$today)
        ->sum('amount');

        $data['money'] = $data['weixin']+$data['num'];

        //卡支付
        $data['card']  = $db
        ->name('order')
        ->where("type=1")
        ->where($where)
        ->where("pay_way=3")
        ->where("paytime>0")
        ->where("addtime>".$Yesterday)
        ->where("addtime<".$today)
        ->sum('amount');

        $data['numss'] = $db
        ->name('order')
        ->where("shop_id=".$shop_id)
        ->where("type=2")
        ->where("pay_way=3")
        ->where("paytime>0")
        ->where("addtime>".$Yesterday)
        ->where("addtime<".$today)
        //->fetchSql(true)
        ->sum('amount');

        $data['cardmoney'] = $data['card']+$data['numss'];

        //购物消费
        $data['itemmoney']  = $db
        ->name('order')
        ->where("type=1")
        ->where($where)
        ->where("addtime>".$Yesterday)
        ->where("addtime<".$today)
        ->sum('amount');

        //服务消费
        $data['fuwumoney']  = $db
        ->name('order')
        ->where("type=2")
        ->where("shop_id=".$shop_id)
        ->where("paytime>0")
        ->where("addtime>".$Yesterday)
        ->where("addtime<".$today)
        ->sum('amount');
        //print_r($data);

        //卡片购买金额
        $data['cardinfo']  = $db
        ->name('card')
        ->field("sum(price) as value,title as name")
        ->where("shop_id=".$shop_id)
        ->where("paytime>0")
        ->where("addtime>".$Yesterday)
        ->where("addtime<".$today)
        ->group("title")
        //->fetchSql(true)
        ->select();

        //卡片购买数量
        $data['cardnums']  = $db
        ->name('card')
        ->field("count(id) as  value,title as name")
        ->where("shop_id=".$shop_id)
        ->where("paytime>0")
        ->where("addtime>".$Yesterday)
        ->where("addtime<".$today)
        ->group("title")
        //->fetchSql(true)
        ->select();

        //print_r($data);die;

        //卡片购买数量
        $data['cardnums']  = $db
        ->name('card')
        ->field("count(id) as  value,title as name")
        ->where("shop_id=".$shop_id)
        ->where("paytime>0")
        ->where("addtime>".$Yesterday)
        ->where("addtime<".$today)
        ->group("title")
        //->fetchSql(true)
        ->select();

        //购物类型消费
        $goods_type = $db
        ->name('order_goods')
        ->join("tf_order","tf_order_goods.order_id=tf_order.id")
        ->join("tf_item_category","tf_order_goods.type_id=tf_item_category.id")
        ->field("tf_item_category.cname as title,sum(tf_order_goods.price) as amount")
        ->where($where)
        ->where("tf_order.type=1")
        ->where("tf_order.addtime>".$Yesterday)
        ->where("tf_order.addtime<".$today)
        ->group("tf_order_goods.type_id")
        //->fetchSql(true)
        ->select();

        $amount = array();

        foreach ($goods_type as $key => $value) {
            $amount[] = $value['amount'];
        }
        $amounts = array_sum($amount);

        foreach ($goods_type as $key => $value) {
            $goods_type[$key]['bili'] = round($value['amount']/$amounts,2)*100;
        }
        $data['goods_type'] = $goods_type;

        //预约类型消费
        $fuwu_type = $db
        ->name('order')
        ->join("tf_yuyue","tf_order.yy_id=tf_yuyue.id")
        ->field("tf_yuyue.type as type,sum(tf_order.amount) as amount")
        ->where("shop_id=".$shop_id)
        ->where("tf_order.type=2")
        ->where("tf_order.paytime>0")
        ->where("tf_order.addtime>".$Yesterday)
        ->where("tf_order.addtime<".$today)
        ->group("tf_yuyue.type")
        //->fetchSql(true)
        ->select();

        $fuwuamount = array();

        foreach ($fuwu_type as $key => $value) {
            if($value['type']==0)
            {
                $fuwu_type[$key]['title'] = "游泳";
            }
            if($value['type']==1)
            {
                $fuwu_type[$key]['title'] = "小儿推拿";
            }
            if($value['type']==3)
            {
                $fuwu_type[$key]['title'] = "成人推拿";
            }
            $fuwuamount[] = $value['amount'];
        }

        $fuwuamounts = array_sum($fuwuamount);

        foreach ($fuwu_type as $key => $value) {
            $fuwu_type[$key]['bili'] = round($value['amount']/$fuwuamounts,2)*100;
        }

        $data['fuwu_type'] = $fuwu_type;

        return $data;

    }
    //卡片购买金额
    public function timeselect($shop_id,$from,$to){

        if($from==null){

            $db = Db::connect(config('ddxx'));

            //$where['type!']      = 3;
            $where['order_status'] = 2;
            $where['shop_id']      = $shop_id;
            $Yesterday    = strtotime(date("Y-m-d",strtotime("-1 day")));
            $today    = strtotime(date("Y-m-d"));

            //昨天总金额
            $data['goods'] = $db
            ->name('order')
            ->where($where)
            ->where("type=1")
            ->where("addtime>".$Yesterday)
            ->where("addtime<".$today)
            //->fetchSql(true)
            ->sum('amount');

            $data['yuyue'] = $db
            ->name('order')
            ->where("shop_id=".$shop_id)
            ->where("type=2")
            ->where("paytime>0")
            ->where("addtime>".$Yesterday)
            ->where("addtime<".$today)
            //->fetchSql(true)
            ->sum('amount');

            $data['amount'] = $data['goods']+$data['yuyue'];

            //预约人数
            $data['nums'] = $db
            ->name('order')
            ->where("type=2")
            ->where("shop_id=".$shop_id)
            ->where("paytime>0")
            ->where("addtime>".$Yesterday)
            ->where("addtime<".$today)
            //->fetchSql(true)
            ->count();

            //卡片销售金额
            $data['cardprice'] = $db
            ->name('order')
            ->where("type=3")
            ->where("shop_id=".$shop_id)
            ->where("addtime>".$Yesterday)
            ->where("addtime<".$today)
            ->where("paytime>0")
            ->sum('amount');

            //现金支付
            $data['weixin'] = $db
            ->name('order')
            ->where("type=1")
            ->where($where)
            ->where("pay_way=1")
            ->where("addtime>".$Yesterday)
            ->where("addtime<".$today)
            ->sum('amount');

            $data['num'] = $db
            ->name('order')
            ->where("type=2")
            ->where("shop_id=".$shop_id)
            ->where("pay_way=1")
            ->where("paytime>0")
            ->where("addtime>".$Yesterday)
            ->where("addtime<".$today)
            ->sum('amount');

            $data['money'] = $data['weixin']+$data['num'];

            //卡支付
            $data['card']  = $db
            ->name('order')
            ->where("type=1")
            ->where($where)
            ->where("pay_way=3")
            ->where("paytime>0")
            ->where("addtime>".$Yesterday)
            ->where("addtime<".$today)
            ->sum('amount');

            $data['numss'] = $db
            ->name('order')
            ->where("shop_id=".$shop_id)
            ->where("type=2")
            ->where("pay_way=3")
            ->where("paytime>0")
            ->where("addtime>".$Yesterday)
            ->where("addtime<".$today)
            //->fetchSql(true)
            ->sum('amount');

            $data['cardmoney'] = $data['card']+$data['numss'];

            //购物消费
            $data['itemmoney']  = $db
            ->name('order')
            ->where("type=1")
            ->where($where)
            ->where("addtime>".$Yesterday)
            ->where("addtime<".$today)
            ->sum('amount');

            //服务消费
            $data['fuwumoney']  = $db
            ->name('order')
            ->where("type=2")
            ->where("shop_id=".$shop_id)
            ->where("paytime>0")
            ->where("addtime>".$Yesterday)
            ->where("addtime<".$today)
            ->sum('amount');
            //print_r($data);

            //卡片购买金额
            $data['cardinfo']  = $db
            ->name('card')
            ->field("sum(price) as value,title as name")
            ->where("shop_id=".$shop_id)
            ->where("paytime>0")
            ->where("addtime>".$Yesterday)
            ->where("addtime<".$today)
            ->group("title")
            //->fetchSql(true)
            ->select();

            //卡片购买数量
            $data['cardnums']  = $db
            ->name('card')
            ->field("count(id) as  value,title as name")
            ->where("shop_id=".$shop_id)
            ->where("paytime>0")
            ->where("addtime>".$Yesterday)
            ->where("addtime<".$today)
            ->group("title")
            //->fetchSql(true)
            ->select();

            //购物类型消费
            $goods_type = $db
            ->name('order_goods')
            ->join("tf_order","tf_order_goods.order_id=tf_order.id")
            ->join("tf_item_category","tf_order_goods.type_id=tf_item_category.id")
            ->field("tf_item_category.cname as title,sum(tf_order_goods.price) as amount")
            ->where($where)
            ->where("tf_order.type=1")
            ->where("tf_order.addtime>".$Yesterday)
            ->where("tf_order.addtime<".$today)
            ->group("tf_order_goods.type_id")
            //->fetchSql(true)
            ->select();

            $amount = array();

            foreach ($goods_type as $key => $value) {
                $amount[] = $value['amount'];
            }
            $amounts = array_sum($amount);

            foreach ($goods_type as $key => $value) {
                $goods_type[$key]['bili'] = round($value['amount']/$amounts,2)*100;
            }
            $data['goods_type'] = $goods_type;

            //预约类型消费
            $fuwu_type = $db
            ->name('order')
            ->join("tf_yuyue","tf_order.yy_id=tf_yuyue.id")
            ->field("tf_yuyue.type as type,sum(tf_order.amount) as amount")
            ->where("shop_id=".$shop_id)
            ->where("tf_order.type=2")
            ->where("tf_order.paytime>0")
            ->where("tf_order.addtime>".$Yesterday)
            ->where("tf_order.addtime<".$today)
            ->group("tf_yuyue.type")
            //->fetchSql(true)
            ->select();

            $fuwuamount = array();

            foreach ($fuwu_type as $key => $value) {
                if($value['type']==0)
                {
                    $fuwu_type[$key]['title'] = "游泳";
                }
                if($value['type']==1)
                {
                    $fuwu_type[$key]['title'] = "小儿推拿";
                }
                if($value['type']==3)
                {
                    $fuwu_type[$key]['title'] = "成人推拿";
                }
                $fuwuamount[] = $value['amount'];
            }

            $fuwuamounts = array_sum($fuwuamount);

            foreach ($fuwu_type as $key => $value) {
                $fuwu_type[$key]['bili'] = round($value['amount']/$fuwuamounts,2)*100;
            }

            $data['fuwu_type'] = $fuwu_type;

        }else{
            $db = Db::connect(config('ddxx'));

            //$where['type!']      = 3;
            $where['order_status'] = 2;
            $where['shop_id']      = $shop_id;
            $Yesterday    = strtotime(date("Y-m-d",strtotime("-1 day")));

            //昨天总金额
            $data['goods'] = $db
            ->name('order')
            ->where($where)
            ->where("type=1")
            ->where("addtime>".$from)
            ->where("addtime<".$to)
            //->fetchSql(true)
            ->sum('amount');

             $data['yuyue'] = $db
            ->name('order')
            ->where("shop_id=".$shop_id)
            ->where("type=2")
            ->where("paytime>0")
            ->where("addtime>".$from)
            ->where("addtime<".$to)
            //->fetchSql(true)
            ->sum('amount');

            $data['amount'] = $data['goods']+$data['yuyue'];

            //预约人数
            $data['nums'] = $db
            ->name('order')
            ->where("type=2")
            ->where("shop_id=".$shop_id)
            ->where("paytime>0")
            ->where("addtime>".$from)
            ->where("addtime<".$to)
            //->fetchSql(true)
            ->count();

            //卡片销售金额
            $data['cardprice'] = $db
            ->name('order')
            ->where("type=3")
            ->where("shop_id=".$shop_id)
            ->where("addtime>".$from)
            ->where("addtime<".$to)
            ->where("paytime>0")
            ->sum('amount');

            //现金支付
            $data['weixin'] = $db
            ->name('order')
            ->where("type=1")
            ->where($where)
            ->where("addtime>".$from)
            ->where("addtime<".$to)
            ->where("paytime>0")
            ->sum('amount');

            $data['num'] = $db
            ->name('order')
            ->where("type=2")
            ->where("shop_id=".$shop_id)
            ->where("pay_way=1")
            ->where("addtime>".$from)
            ->where("addtime<".$to)
            ->where("paytime>0")
            ->sum('amount');

            $data['money'] = $data['weixin']+$data['num'];

            //卡支付
            $data['card']  = $db
            ->name('order')
            ->where("type=1")
            ->where($where)
            ->where("pay_way=3")
            ->where("paytime>0")
            ->where("addtime>".$from)
            ->where("addtime<".$to)
            ->sum('amount');

            $data['numss'] = $db
            ->name('order')
            ->where("shop_id=".$shop_id)
            ->where("type=2")
            ->where("pay_way=3")
            ->where("paytime>0")
            ->where("addtime>".$from)
            ->where("addtime<".$to)
            //->fetchSql(true)
            ->sum('amount');

            $data['cardmoney'] = $data['card']+$data['numss'];

            //购物消费
            $data['itemmoney']  = $db
            ->name('order')
            ->where("type=1")
            ->where($where)
            ->where("addtime>".$from)
            ->where("addtime<".$to)
            ->sum('amount');

            //服务消费
            $data['fuwumoney']  = $db
            ->name('order')
            ->where("type=2")
            ->where("shop_id=".$shop_id)
            ->where("paytime>0")
            ->where("addtime>".$from)
            ->where("addtime<".$to)
            ->sum('amount');
            //print_r($data);

            //卡片购买金额
            $data['cardinfo']  = $db
            ->name('card')
            ->field("sum(price) as value,title as name")
            ->where("shop_id=".$shop_id)
            ->where("paytime>0")
            ->where("addtime>".$from)
            ->where("addtime<".$to)
            ->group("title")
            //->fetchSql(true)
            ->select();

            //卡片购买数量
            $data['cardnums']  = $db
            ->name('card')
            ->field("count(id) as  value,title as name")
            ->where("shop_id=".$shop_id)
            ->where("paytime>0")
            ->where("addtime>".$from)
            ->where("addtime<".$to)
            ->group("title")
            //->fetchSql(true)
            ->select();

            //购物类型消费
            $goods_type = $db
            ->name('order_goods')
            ->join("tf_order","tf_order_goods.order_id=tf_order.id")
            ->join("tf_item_category","tf_order_goods.type_id=tf_item_category.id")
            ->field("tf_item_category.cname as title,sum(tf_order_goods.price) as amount")
            ->where($where)
            ->where("tf_order.type=1")
            ->where("tf_order.addtime>".$from)
            ->where("tf_order.addtime<".$to)
            ->group("tf_order_goods.type_id")
            //->fetchSql(true)
            ->select();

            $amount = array();

            foreach ($goods_type as $key => $value) {
                $amount[] = $value['amount'];
            }
            $amounts = array_sum($amount);

            foreach ($goods_type as $key => $value) {
                $goods_type[$key]['bili'] = round($value['amount']/$amounts,2)*100;
            }
            $data['goods_type'] = $goods_type;

            //预约类型消费
            $fuwu_type = $db
            ->name('order')
            ->join("tf_yuyue","tf_order.yy_id=tf_yuyue.id")
            ->field("tf_yuyue.type as type,sum(tf_order.amount) as amount")
            ->where("shop_id=".$shop_id)
            ->where("tf_order.type=2")
            ->where("tf_order.paytime>0")
            ->where("tf_order.addtime>".$from)
            ->where("tf_order.addtime<".$to)
            ->group("tf_yuyue.type")
            //->fetchSql(true)
            ->select();

            $fuwuamount = array();

            foreach ($fuwu_type as $key => $value) {
                if($value['type']==0)
                {
                    $fuwu_type[$key]['title'] = "游泳";
                }
                if($value['type']==1)
                {
                    $fuwu_type[$key]['title'] = "小儿推拿";
                }
                if($value['type']==3)
                {
                    $fuwu_type[$key]['title'] = "成人推拿";
                }
                $fuwuamount[] = $value['amount'];
            }

            $fuwuamounts = array_sum($fuwuamount);

            foreach ($fuwu_type as $key => $value) {
                $fuwu_type[$key]['bili'] = round($value['amount']/$fuwuamounts,2)*100;
            }

            $data['fuwu_type'] = $fuwu_type;
        }

        //print_r($data);die;

        return $data;

    }
    //店铺列表
    public function shoplist($id,$shopid)
    {
        if($id!=1){
            $where['id'] = array('in',$shopid);

            $db = Db::connect(config('ddxx'));

            $shop_list = $db
            ->name('shop')
            ->field('id,name')
            ->where($where)
            ->select();
        }else{
            $where['id'] = array('in',$shopid);

            $db = Db::connect(config('ddxx'));

            $shop_list = $db
            ->name('shop')
            ->field('id,name')
            ->select();
        }
        //print_r($type);die;
        return $shop_list;
    }
}
