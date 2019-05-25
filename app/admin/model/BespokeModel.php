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

class BespokeModel extends Model
{
    /*
    获取门店预约列表
     */
    public function list($where){

        //print_r($where);die;

        $db = Db::connect(config('ddxx'));

        $bespoke_list = $db
        ->name('yuyue')
        ->field("FROM_UNIXTIME(tf_yuyue.yytime,'%Y-%m-%d %H:%i:%s') as yytime,tf_yuyue.workerid,FROM_UNIXTIME(tf_yuyue.paytime,'%Y-%m-%d %H:%i:%s') as paytime,FROM_UNIXTIME(tf_yuyue.addtime,'%Y-%m-%d %H:%i:%s') as addtime,tf_yuyue.name,tf_yuyue.status,tf_yuyue.id,tf_yuyue.type,tf_member.mobile,tf_member.nickname,tf_order.amount,tf_order.pay_way,tf_shop.name as shop_name")
        ->join("tf_member","tf_yuyue.member_id=tf_member.id")
        // ->join("tf_card","tf_yuyue.cardid=tf_card.id")
        ->join("tf_order","tf_yuyue.id=tf_order.yy_id")
        ->join("tf_shop","tf_yuyue.sid=tf_shop.id")
        ->where('tf_order.is_online=1')
        ->where('tf_yuyue.isdel=0')
        ->where($where)
        ->where('tf_yuyue.paytime!=0')
        ->order("tf_yuyue.addtime desc");
        //->fetchSql(true)
        // ->paginate(20);

        //print_r($bespoke_list);die;

        return $bespoke_list;
    }
    /*
    上下架删除操作
     */
    public function operation($id,$cloum,$status){

        $db = Db::connect(config('ddxx'));

        $data[$cloum] = $status;

        $res = $db
        ->name('yuyue')
        ->where('id='.$id)
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
    店员查询
     */
    public function bes_find($waiterid){

        if($waiterid==''){
            return '参数错误';
        }
        $db = Db::connect(config('ddxx'));

        $article = $db
        ->name('worker')
        ->field("workid,name,id")
        ->where("id=$waiterid")
        ->where("status=1")
        ->where("iswork=1")
        //->fetchSql(true)
        ->find();
        //print_r($article);die;
        return $article;

    }
    /*
    预约查询
     */
    public function yuyue_find($yuyueid){

        if($yuyueid==''){
            return '参数错误';
        }
        $db = Db::connect(config('ddxx'));

        $article = $db
        ->name('yuyue')
        ->field("workerid")
        ->where("id=$yuyueid")
        //->fetchSql(true)
        ->find();
        //print_r($article);die;
        return $article;

    }
    /*
    上下架删除操作
     */
    public function waiter($id,$data){

        $db = Db::connect(config('ddxx'));

        $res = $db
        ->name('yuyue')
        ->where('id='.$id)
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
    店铺服务人员列表
     */
    public function waiterlist($id,$shop_id){

        $db = Db::connect(config('ddxx'));

        if($id==1){
            $where = "1=1";
        }else{
            $where['sid'] = $shop_id;
        }
        $res = $db
        ->name('worker')
        ->where($where)
        //->fetchSql(true)
        ->select();

        return $res;
    }

    //获取所有门店
    public function getAllShop($field = '',$where)
    {
        if ($field) {
            return Db::connect(config('ddxx'))->name('shop')->where(['status'=>1])->where($where)->field($field)->select();
        }else{
            return Db::connect(config('ddxx'))->name('shop')->where(['status'=>1])->where($where)->select();
        }

    }

    public function yyTotemp($open_id,$token,$first = '',$type = '',$time = '',$address = '',$cause = '',$remark = '',$url = '')
    {
        $temp_id = Db::connect(config('ddxx'))->table('tf_template_msg')->where(['type_name'=>'yy_cancel'])->value('template_id');
        $datas = [];
        $datas['touser'] = $open_id;
        $datas['template_id'] = $temp_id;
        $datas['url'] = $url;
        $tmp_data = [];
        $tmp_data['first'] = ['value'=>$first,'color'=>'#006400'];//退款单号
        $tmp_data['keyword1'] = ['value'=>$type];//预约类型
        $tmp_data['keyword2'] = ['value'=>$time];//预约时间
        $tmp_data['keyword3'] = ['value'=>$address];//预约地址
        $tmp_data['keyword4'] = ['value'=>$cause];//取消原因
        $tmp_data['remark'] = ['value'=>$remark,'color'=>'#8F8F8F'];//
        $datas['data'] = $tmp_data;
        $json_data = json_encode($datas,JSON_UNESCAPED_UNICODE);
        sendtemplateinfo($token,$json_data);
    }

}
