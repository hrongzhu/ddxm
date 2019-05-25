<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/22
 * Time: 14:42
 */

namespace app\admin\controller;

use app\admin\model\LevelModel;
use app\admin\model\order\OrderModel;
use app\admin\model\shop\ShopModel;
use app\admin\model\shop\ShopSetMoneyModel;
use app\admin\model\user\UserModel;
use think\Db;
use think\Exception;
use think\Session;

class RechargeController extends BaseController
{

    /**
     * 获取用户数据、可充值面额
     * @return string|\think\response\Json
     * @throws \think\exception\DbException
     */
    public function rechargeIndex(){

        $shop_id = Session::get('SHOP_ID');
        $member_id = input('post.id',958);
        $userInfo= UserModel::get(['id'=>$member_id]);
        $userLevel = LevelModel::get(['id'=>$userInfo->level_id]);
        $userInfo['level_name'] = $userLevel->level_name;
        $shopCode = $userInfo->shop_code['code'];
        if(!$shopCode){
            return outPut(2,'该会员还未绑定门店，无法充值！');
        }
        $userAmount = $userInfo->amount;
        $shopInfo = ShopModel::get(['code'=>$shopCode]);
        //生成一个安全的身份验证码
        $ID_AUTH_CODE = md5('ddxm'.$member_id);
        $order_sn = time().rand(10,99).$shop_id;

        if($userAmount == 0){
            //余额为零即用户没有过充值行为
            $arr=[];
            $shopStandardPrice = json_decode($shopInfo->level_standard,true);
            foreach ($shopStandardPrice as $k=>$v){
                $arr[$k]['id'] = $k;
                $arr[$k]['price']=$v;
            }
            $shopStandardPrice = $arr;
        }else{
        //有余额即用户为老会员，已有过充值行为
            $shopStandardPrice = (new ShopSetMoneyModel())->getlist($shopInfo->id);
        }
        return outPut(1,'',['shopStandardPrice'=>$shopStandardPrice,'userInfo'=>$userInfo,'id_auth_code'=>$ID_AUTH_CODE,'order_sn'=>$order_sn]);

    }


    public function moneyList(){
        $shop_id = Session::get('SHOP_ID');
        $recharge_list = (new ShopSetMoneyModel())->getlist($shop_id);
        $this->assign('r_list',$recharge_list);
        return $this->fetch('recharge_list');
    }

    /**
     * [add_recharge 添加门店的充值面额]
     */
    public function addRecharge()
    {
        $data = input('post.');
        $shop_id = $data['id'];
        $price = $data['price'];
        $fail = 0;
        //已存在执行修改，存在新ID执行增加
        (new ShopSetMoneyModel())->startTrans();
        try{
            foreach ($price as $k=>$v){
                $item = (new ShopSetMoneyModel())->find($k);
                if($item&&$shop_id==$item['shop_id']){
                    if($v!=$item['price']){
                        if (!(new ShopSetMoneyModel())->allowField(true)->save(['price'=>$v],['id'=>$item['id']])) {
                            $fail+=1;
                        }
                    }else{
                        continue;
                    }
                }else{
                    if (!(new ShopSetMoneyModel())->insert(['price'=>$v,'shop_id'=>$shop_id,'addtime'=>time()])) {
                        $fail+=1;
                    }
                }
            }
        }catch (Exception $e){
            (new ShopSetMoneyModel())->rollback();
            return outPut(500,$e->getMessage());
        }
        if ($fail==0) {
            (new ShopSetMoneyModel())->commit();
            return outPut(200,'保存成功');
        }else{
            return outPut(301,'保存失败');
        }
    }

    public function getRecharge(){
        $id = input('post.id');
        $list = ShopSetMoneyModel::all(['shop_id'=>$id]);
        if($list){
            return outPut(1,'success',$list);
        }else{
            return outPut(2,'empty sets');
        }
    }

    /**
     * [delRecharge 删除门店面额]
     * @return [type] [description]
     */
    public function delRecharge()
    {
        $id = input('post.id');
        $res = (new \app\admin\model\shop\ShopSetMoneyModel())->del($id);
        if ($res) {
            return outPut(200,'删除成功');
        }
        return outPut(301,'删除失败');
    }

    /**
     * 获取储值明细记录
     * @return \think\response\View
     */
    public function history(){
        $shop_id = Session::get('SHOP_ID');
        $mobile = trim($this->request->param('mobile'));
        $nickname = trim($this->request->param('nickname'));
        $time = $this->request->param('time');
        $where = [];
        $where['o.shop_id'] = $shop_id;
        $where['o.type'] = 3;
        if($time){
            $timeArr = explode('~',$time);
            $startTime = strtotime($timeArr[0]);
            $endTime = strtotime($timeArr[1])+24*3600;
            if($startTime&&$endTime){
                $where['o.addtime'] = ['between',[$startTime,$endTime]];
            }
        }
        if($mobile){
            $where['m.mobile'] = ['like',"%$mobile%"];
        }
        if($nickname){
            $where['m.nickname'] = ['like',"%$nickname%"];
        }
        $pageParams = [
            'mobile' => $mobile != null ? $mobile: '',
            'nickname' => $nickname!= null ? $nickname: '',
            'time' => $time!= null ? $time: '',
        ];
        $lists = (new OrderModel())->getRechargeHistroy($where)->paginate('12',false, ['query'=>$pageParams]);
        dump($lists);exit;
        //总订单数
        $total = (new OrderModel())->getRechargeHistroy($where)->count();
        //总金额
        $totalMoney = (new OrderModel())->getRechargeHistroy($where)->sum('o.amount');
        $this->assign('total',$total);
        $this->assign('totalMoney',$totalMoney);
//        dump($totalMoney);exit;
        //  分页样式
        $this->assign('page', $lists->render());
        //  总条数
        $this->assign('totalCount',$lists->total());
        //  当前页面
        $this->assign('current', $lists->currentPage());
        //  每页显示数量
        $this->assign('listRows', $lists->listRows());
        $this->assign("lists", $lists);
        return view('history');
    }

}
