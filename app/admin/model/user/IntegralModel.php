<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/23
 * Time: 9:44
 */

namespace app\admin\model\user;

use app\admin\model\BaseModel;
use app\admin\model\order\OrderGoodsModel;
use app\admin\model\order\OrderModel;
use app\admin\model\shop\YuyueModel;
use app\admin\model\ticket\TicketModel;
use app\admin\model\user\UserTicketModel;
use think\Db;
use think\Exception;

/**
 * 积分类
 */
class IntegralModel extends BaseModel
{
    protected $table = 'tf_member';

    /**
     * [addIntegral 增加积分 计算比例默认是1:10 会员等级不同有所不同]
     * @param integer $order_id     [购买金额]
     * @return boolean
     */
    public function addIntegral($order_id = 0)
    {
        if (!$order_id) {
            return false;
        }
        $db = Db::connect(config('ddxx'));
        $db->startTrans();
        try{
            $order_info     = (new OrderModel())->where(['id'=>$order_id])->field('member_id,type,amount,postage')->find();
            $member_id      = $order_info['member_id'];
            $buy_type       = $order_info['type'];
            $amount         = $order_info['amount'] - $order_info['postage'];
            if ($member_id == 0 || $buy_type == 0){
                return false;
            }
            $score_item     = $this->where(['id'=>$member_id])->value('score_item');
            $score_serv     = $this->where(['id'=>$member_id])->value('score_server');
            $score = $this->calculateIntegral($member_id,$amount);
            $data = [];
            if ($buy_type == 4 && $score != 0){
                $yuyue_list = (new YuyueModel())->where(['order_id'=>$order_id])->field('num,price')->select();
                $item_list = (new OrderGoodsModel)->where(['order_id'=>$order_id])->field('num,price,ticket_deduction')->select();
                $yuyue_amount = 0;
                $item_amount = 0;
                if(!empty($item_list)){
                    $ticket_amount = 0;
                    foreach($item_list as $vi){
                        $item_amount += $vi['num'] * $vi['price'];
                        $ticket_amount += $vi['num'] * $vi['ticket_deduction'];
                    }
                    $item_amount = ($ticket_amount - $item_amount)>= 0?0:$item_amount-$ticket_amount;
                }
                if (!empty($yuyue_list)) {
                    foreach($yuyue_list as $vs){
                        $yuyue_amount += $vs['num'] * $vs['price'];
                    }
                }
                $server_score   = $this->calculateIntegral($member_id,$yuyue_amount);
                $item_score     = $this->calculateIntegral($member_id,$item_amount);
                $data['score_item'] = $score_item + $item_score;
                $data['score_server'] = $score_serv + $server_score;
            }else if ($buy_type == 1) {
                $data['score_item'] = $score_item + $score;
            }else if($buy_type == 2){
                $data['score_server'] = $score_serv + $score;
            }else if($buy_type == 5){
                $data['score_server'] = $score_serv + $score;
            }elseif ($buy_type == 3) {
                //必须是购买服务卡才加
                if ($db->name('member_card')->where(['order_id'=>$order_id])->value('id')) {
                    $data['score_server'] = $score_serv + $score;
                }
            }
            $res = $this->allowField(true)->save($data,['id'=>$member_id]);
            if (!$res) {
                $db->rollback();
                return false;
            }
            $this->addIntegralRecord($order_id);
            $db->commit();
            return true;
        }catch (Exception $e){
            $db->rollback();
            return false;
        }
    }

    /**
     * [addIntegral 扣减积分]
     * @param integer $order_id     [购买金额]
     * @return boolean
     */
    public function reduceIntegral($order_id = 0)
    {
        if (!$order_id) {
            return false;
        }
        $db = Db::connect(config('ddxx'));
        $db->startTrans();
        try{
            $order_info     = (new OrderModel())->where(['id'=>$order_id])->field('member_id,type,amount')->find();
            $member_id      = $order_info['member_id'];
            $buy_type       = $order_info['type'];
            $amount         = $order_info['amount'];
            if ($member_id == 0 || $buy_type == 0){
                return false;
            }
            $score_item     = $this->where(['id'=>$member_id])->value('score_item');
            $score_serv     = $this->where(['id'=>$member_id])->value('score_server');
            $score = $this->calculateIntegral($member_id,$amount);
            $data = [];
            if ($buy_type == 4 && $score != 0){
                $yuyue_list = (new YuyueModel())->where(['order_id'=>$order_id])->field('num,price')->select();
                $item_list = (new OrderGoodsModel)->where(['order_id'=>$order_id])->field('num,price,ticket_deduction')->select();
                $yuyue_amount = 0;
                $item_amount = 0;
                if(!empty($item_list)){
                    $ticket_amount = 0;
                    foreach($item_list as $vi){
                        $item_amount += $vi['num'] * $vi['price'];
                        $ticket_amount += $vi['num'] * $vi['ticket_deduction'];
                    }
                    $item_amount = (abs($item_amount) - abs($ticket_amount)) <= 0?0:(abs($item_amount)-abs($ticket_amount));
                }
                if (!empty($yuyue_list)) {
                    foreach($yuyue_list as $vs){
                        $yuyue_amount += $vs['num'] * $vs['price'];
                    }
                }
                $server_score   = $this->calculateIntegral($member_id,$yuyue_amount);
                $item_score     = $this->calculateIntegral($member_id,$item_amount);
                $data['score_item'] = ($score_item - $item_score)<=0?0:$score_item - $item_score;
                $data['score_server'] = $score_serv + $server_score;
            }else if ($buy_type == 1) {
                $data['score_item'] = $score_item + $score;
            }else if($buy_type == 2){
                $data['score_server'] = $score_serv + $score;
            }else if ($buy_type == 5) {
                $data['score_server'] = $score_serv + $score;
            }
            $res = $this->allowField(true)->save($data,['id'=>$member_id]);
            if (!$res) {
                $db->rollback();
                return false;
            }
            $db->commit();
            $this->addIntegralRecord($order_id);
            return true;
        }catch (Exception $e){
            $db->rollback();
            return false;
        }
    }

    /**
     * 计算积分数量
     * @param int $member_id 会员id
     * @param int $price 金额
     * @return int
     */
    public function calculateIntegral($member_id = 0,$price = 0)
    {
        if (!$member_id || !$price) {
            return 0;
        }
        $user_level = $this->where(['id'=>$member_id])->value('level_id');
        $ratio = Db::connect(config('ddxx'))->name('member_score_rule')->where(['level_id'=>$user_level])->value('ratio');//查询当前会员等级的积分计算比例
        if ($ratio) {
            $score = (int)($price *10 * $ratio);
        }else{
            $score = (int)($price *10);
        }
        return $score;
    }

    /**
     * 积分记录添加
     * @param int $order_id 订单id
     */
    public function addIntegralRecord($order_id = 0)
    {
        $db = Db::connect(config('ddxx'));
        $db->startTrans();
        try{
            $orderModel = new OrderModel();
            $order_info = $orderModel->where(['id'=>$order_id])->field('member_id,amount,type,postage,is_online')->find();
            $amount = $order_info['amount'] >= 0?$order_info['amount']-$order_info['postage']:$order_info['amount']+$order_info['postage'];
            $integral_num = $this->calculateIntegral($order_info['member_id'],$amount);
            $integral_log_data = [];
            $integral_log_datas = [];
            $integral_log_data['order_id'] = $order_id;
            $integral_log_data['member_id'] = $order_info['member_id'];
            $integral_log_data['price'] = $integral_num;
            $integral_log_data['addtime'] = time();
            $integral_log_data['is_online'] = $order_info['is_online'];
            if ($order_info['type'] == 4 && $integral_num != 0){
                $yuyue_count = (new YuyueModel())->where(['order_id'=>$order_id])->count();
                $item_count = (new OrderGoodsModel())->where(['order_id'=>$order_id])->count();
                if ($yuyue_count && $item_count){
                    $yuyue_list = (new YuyueModel())->where(['order_id'=>$order_id])->field('num,price')->select();
                    $yuyue_amount = 0;
                    foreach($yuyue_list as $vi){
                        $yuyue_amount += $vi['num'] * $vi['price'];
                    }
                    $yuyue_integral = $this->calculateIntegral($order_info['member_id'],$yuyue_amount);
                    $integral_log_datas[0] = $integral_log_data; //当作商品的
                    $integral_log_datas[0]['consume_type'] = ($integral_num > 0)?1:2;
                    $integral_log_datas[0]['integral_type'] = 1;
                    $integral_log_datas[0]['content'] = ($integral_num > 0)?'购买商品获得':'退货款扣减';
                    //最多只能扣到0为止
                    $price = $integral_num - $yuyue_integral;
                    $integral_log_datas[0]['price'] = ($price < 0)?0:$price;
                    $integral_log_datas[1] = $integral_log_data; //当作服务的
                    $integral_log_datas[1]['consume_type'] = ($integral_num > 0)?1:2;
                    $integral_log_datas[1]['integral_type'] = 2;
                    $integral_log_datas[1]['content'] = ($integral_num > 0)?'[预约|购买]服务获得':'退服务扣减';
                    $integral_log_datas[1]['price'] = $yuyue_integral < 0?0:$yuyue_integral;
                }elseif($item_count){
                    $integral_log_data['consume_type'] = ($integral_num > 0)?1:2;
                    $integral_log_data['integral_type'] = 1;
                    $integral_log_data['content'] = ($integral_num > 0)?'购买商品获得':'退货款扣减';
                }elseif($yuyue_count){
                    $integral_log_data['consume_type'] = ($integral_num > 0)?1:2;
                    $integral_log_data['integral_type'] = 2;
                    $integral_log_data['content'] = ($integral_num > 0)?'[预约|购买]服务获得':'退服务扣减';
                }
            }else if ($order_info['type'] == 1){
                $integral_log_data['consume_type'] = ($integral_num > 0)?1:2;
                $integral_log_data['integral_type'] = 1;
                $integral_log_data['content'] = ($integral_num > 0)?'购买商品获得':'退货款扣减';
            }else if($order_info['type'] == 2){
                $integral_log_data['consume_type'] = ($integral_num > 0)?1:2;
                $integral_log_data['integral_type'] = 2;
                $integral_log_data['content'] = ($integral_num > 0)?'[预约|购买]服务获得':'退服务扣减';
            }else if($order_info['type'] == 5){
                $ticket_id = (new UserTicketModel())->where(['get_order_id'=>$order_id])->value('ticket_id');
                $ticket_type = (new TicketModel())->where(['id'=>$ticket_id])->value('type');
                $integral_log_data['consume_type'] = 1;
                $integral_log_data['integral_type'] = ($ticket_type == 1)?1:2;
                $integral_log_data['content'] = '购买服务券获得';
            }else if($order_info['type'] == 6){
                $ticket_id = (new UserTicketModel())->where(['get_order_id'=>$order_id])->value('ticket_id');
                $ticket_type = (new TicketModel())->where(['id'=>$ticket_id])->value('type');
                $integral_log_data['consume_type'] = 2;
                $integral_log_data['integral_type'] = ($ticket_type == 1)?1:2;
                $integral_amount = $orderModel->where(['id'=>$order_id])->value('integral_amount');
                $integral_log_data['price'] = $integral_amount;
                $integral_log_data['content'] = $integral_amount == 0?'赠送券':'兑换券扣减';
            }else if($order_info['type'] == 3){
                $integral_log_data['consume_type'] = ($integral_num > 0)?1:2;
                $integral_log_data['integral_type'] = 2;
                $integral_log_data['content'] = ($integral_num > 0)?'购买服务卡获得':'退服务卡扣减';
            }

            if (!empty($integral_log_datas)){
                $db->name('member_integral_log')->insertAll($integral_log_datas);
            }else{
                $db->name('member_integral_log')->insert($integral_log_data);
            }
            $db->commit();
            return true;
        }catch (Exception $e){
            $db->rollback();
            // var_dump($e);exit;
            return false;
        }
    }
}
