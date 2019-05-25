<?php
/**
 * Author: chenjing
 * Date: 2018/2/5
 * Description:
 */

namespace app\admin\controller;

use think\Db;
use app\admin\model\order\OrderModel;
use app\admin\model\order\TestModel;

class CrontabController extends BaseController
{
    public function _initialize()
    {
        //重新初始化,过滤验证
    }
    //检测当前订单是否超过处理时间(暂时不用)
    Public function checkOrderDealwithTime()
    {
        $where['pay_status'] = 1;
        $where['order_status'] = 9;
        $orderlist = (new OrderModel())->getBasicOrderList($where);
//        dump($orderlist);die;
        if (empty($orderlist)){
            return false;
        }
        //如果订单处理时间过了,那么久改变他的订单状态
        foreach ($orderlist as $k => $v){
            if (($v['addtime'] + 48 * 3600) <time()){
                (new OrderModel())->updateOrderStatus($v['id']);
            }
        }
    }
    //检测当前订单是否超过最后支付时间
    Public function checkOrderNopay()
    {
        $where['pay_status'] = 0;
        $where['isDel'] = 0;
        $orderlist = (new OrderModel())->getBasicOrderList($where);
//        dump($orderlist);die;
        if (empty($orderlist)){
            return false;
        }
        //如果长时间未支付,取消订单
        $orderTempData = new \app\admin\model\order\OrderTempDataModel();
        foreach ($orderlist as $k => $v){
            if (($v['addtime'] + 24 * 3600) <time()){
                (new OrderModel())->delTimeOutOrder($v['id']);
                $orderTempData->where(['order_id'=>$v['id']])->delete();
            }
        }
    }

    // public function delLogs()
    // {
    //     Db::table('ddxm_log')->where('access_time','=','1521776647')->delete();
    // }
}
