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
namespace app\admin\model\order;
use app\admin\common\Logs;
use app\admin\controller\LogsController;
use app\admin\model\BaseModel;

class OrderRefundModel extends BaseModel
{
    protected $autoWriteTimestamp = false;

    protected $table = 'tf_after_sale';


    //获取器(门店代码对应的门店)
    public function getShopIdAttr($value)
    {
        $data = self::getShopList();
        $shopArr = [];
        foreach ($data as $v){
            $shopArr[$v['id']] = $v['name'];
        }
        $shopArr[0] = '捣蛋熊猫总店';
        $shop_name = isset($shopArr[$value])?$shopArr[$value]:'未知门店';
        return $shop_name;
    }

    public function getAddtimeAttr($value)
    {
        if ($value) {
            return date('Y-m-d H:i',$value);
        }
        return '暂无';
    }

    public function getFinishTimeAttr($value)
    {
        if ($value) {
            return date('Y-m-d H:i',$value);
        }
        return '暂未处理';
    }
    //列表数据
    public function getOrderRefundListDatas($where)
    {
        return $this->table($this->table)
            ->alias('a')
            ->field('a.as_id,a.as_sn,a.member_id,a.add_time,a.status,a.finish_time,b.nickname as name')
            ->join('tf_member b','a.member_id = b.id','LEFT')
            ->where($where)
            ->group('a.as_id')
            ->order('a.add_time DESC');
    }

    //一对一模型(用户信息)
    public function memberInfo()
    {
        return $this->belongsTo('app\admin\model\user\UserModel','member_id','id')
            ->field('id,shop_code,nickname');
    }

    //一对一模型(用户信息)
    public function declareInfo()
    {
        return $this->belongsTo('app\admin\model\user\UserDeclareModel','member_id','member_id')
            ->field('member_id,name,idcard,front_idcard,rev_idcard');
    }

    //订单详情数据
    public function getDetailData($id = '')
    {
        $datas = $this->table($this->table)
            ->alias('a')
            ->field('a.as_id,a.as_sn,a.order_id,a.member_id,a.goods_id,a.content,a.staff_reply,a.price as r_price,a.pic,a.goods_num,a.add_time,a.status,a.finish_time,b.title,c.attr_pic as thumb,b.type,c.price,c.oldprice,c.attr_name,c.attr_names')
            ->join('tf_item b','a.goods_id = b.id','LEFT')
            ->join('tf_item_attr c','b.id = c.item_id','LEFT')
            ->where(['a.as_id'=>$id])
            ->find()->toArray();
        $datas['thumb'] = config('domino.domino').$datas['thumb'];
        if($datas['pic'] == ''){
            $datas['pic'] = '';
        }elseif (is_numeric(strpos($datas['pic'],','))){
            $datas['pic'] = explode(',',$datas['pic']);
            foreach ($datas['pic'] as $key => $v){
                $datas['pic'][$key] = config('domino.domino').$v;
            }
        }else{
            $datas['pic'] = config('domino.domino').$datas['pic'];
        }
        $orderDatas = [];
        if(OrderModel::get($datas['order_id']) != null){
            $orderDatas = (new OrderModel())
                ->where(['id'=>$datas['order_id']])
                ->field('pay_way,send_way,postage,sn,mobile,realname,detail_address')
                ->find()->toArray();
        }
        $datas['order_info'] = $orderDatas;
        return $datas;
    }

    //处理售后
    public function dealwith($data,$id)
    {
        $msg = '处理售后,id为：'.$id;
        Logs::actionLogRecord($msg);
        return self::update($data,['as_id'=>$id]);
    }

    //删除订单
    public function deletes($id)
    {
        $orderInfo = self::get($id);
        $msg = '将退货订单删除,订单号: '.$orderInfo->as_sn;
        Logs::actionLogRecord($msg);
        LogsController::actionLogRecord($msg);
        $result = $orderInfo->destroy(['as_id'=>$id]);
        if($result){
            return true;
        }
        return false;
    }

}
