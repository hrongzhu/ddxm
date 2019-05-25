<?php
/**
 * Author: chenjing
 * Date: 2017/12/21
 * Description:
 */

namespace app\admin\controller;

use app\admin\model\AdminUserModel;
use app\admin\model\order\OrderModel;
use app\admin\model\PayModel;
use app\admin\model\shop\ShopModel;
use think\Db;
use think\Request;
use think\Session;

class PayController extends BaseController
{
    public function _initialize()
    {
        //重新初始化,过滤验证
    }

    //支付配置文件
    protected $config = [
        'wechat' => [
            'app_id' => 'wxb5ee49b69efc2429',
            'mch_id' => '1486226662',
            'notify_url' => 'http://adm.lifephp.cn/admin/Pay/notify',
            'key' => 'daodanxiongmaokkgg4945sdfjklghgh',
        ],
    ];
    //额外的数据库配置
    protected $database = [
        // 数据库类型
        'type'        => 'mysql',
        // 服务器地址
//        'hostname'    => '120.55.63.230',
//        'hostname'    => '120.79.5.57',
        'hostname'    => 'localhost',
        // 数据库名
        'database'    => 'ddxx',
        // 数据库用户名
        'username'    => 'root',
        // 数据库密码
        'password'    => 'root',
//        'password'    => 'yu12346',
        // 数据库连接端口
        'hostport'    => '3306',
        // 数据库编码默认采用utf8
        'charset'     => 'utf8',
        // 数据库表前缀
        'prefix'      => 'tf_',
        // 数据集返回类型
        'resultset_type' => '',
    ];

    //使用门店预存款支付订单
    public function balancepay()
    {
        $uid = Session::get('ADMIN_ID');
        $orderid = Request::instance()->post('order_id',0);
        $orderInfo= (new OrderModel())->getPayData($orderid);
        //查询用户门店id
        $users = (new AdminUserModel())->getAdminUserBasicInfo($uid,'user_type,shop_id');
        //只有当时独立的那种加盟商才会调用扫码支付
        $shop_type = (new ShopModel())->shopBaseInfo($users['shop_id'],'shop_type');
        //现在判断是门店还是加盟商 ,门店则自己通过,记录一条支出记录,加盟商则直接让其微信支付
        if ($shop_type == 3){
            return outPut(301,'您需要微信支付');
        }
        $data = [];
        $data['order_id'] = $orderid;
        $data['shop_id'] = $users['shop_id'];
        $data['pay_money'] = $orderInfo['amount'];
        $data['pay_status'] = 1;
        $data['pay_time'] = time();
        $data['add_time'] = time();
        $res =  Db::connect($this->database)->table('tf_admin_order')->insert($data);
        if ($res) {
            return outPut(200,'支付成功');
        }
        return outPut(300,'支付失败');
    }

    //判断扫码支付是否完成
    public function ispay()
    {
        $order_id = Request::instance()->post('order_id',0);
        // $order_id = $_POST;
        // $order_id = $order_id['order_id'];
        $res = Db::connect($this->database)->table('tf_admin_order')->where(['order_id'=>$order_id])->find();
        if ($res['pay_status'] == 1){
            return outPut(200,'支付成功');
        }
        return outPut(301,'支付失败');
    }

    //微信公总号扫码支付测试(二维码出来了)
    public function wxScanPay()
    {
        //发起微信支付，得到微信支付字符串，直接输出字符串，在模板中通过jquery生成支付二维码
        if(request()->isPost()){
            $ip= $this->getIp();
            $orderid = Request::instance()->post('order_id');
            $orderInfo= (new OrderModel())->getPayData($orderid);
            $amount = (empty($orderInfo['amount'])||$orderInfo['amount'] == 0)?1:$orderInfo['amount'] * 100;
            $desc = empty($orderInfo['subtitle'])?'门店付款给平台':$orderInfo['subtitle'];
            $Pay = new PayModel();
            $result = $Pay->weixin([
                'body' => $desc,
                'attach' => 'orderid='.$orderid,
                'out_trade_no' => time().rand(10,99),
                'total_fee' => 1,//订单金额，单位为分，如果你的订单是100元那么此处应该为 100*100,1元就是1*100
                'time_start' => date("YmdHis"),//交易开始时间
                'time_expire' => date("YmdHis", time() + 604800),//一周过期
                'goods_tag' => '代理发货支付款',
                'notify_url' => request()->domain().url('admin/Pay/notify'),
                'trade_type' => 'NATIVE',
                'product_id' => rand(1,999999),
                'spbill_create_ip' => $ip,
            ]);
            $uid = Session::get('ADMIN_ID');
            //查询用户门店id
            $users = (new AdminUserModel())->getAdminUserBasicInfo($uid,'shop_id');
            //只有当时独立的那种加盟商才会调用扫码支付

            $data = [];
            $data['order_id'] = $orderid;
            $data['shop_id'] = $users['shop_id'];
            $data['pay_money'] = $orderInfo['amount'];
            $data['pay_status'] = 0;
            $data['pay_time'] = 0;
            $data['add_time'] = time();
            $res =  Db::connect($this->database)->table('tf_admin_order')->insert($data);

            if(!$result['code']){
                return outPut(0,$result['msg']);
            }elseif (!$res) {
               return outPut(0,'系统错误');
            }else{
               return outPut(1,$result['msg']);
            }
        }
    }

    public function notify()
    {//微信订单异步通知
        $notify_data = file_get_contents("php://input");//获取由微信传来的数据
        if(!$notify_data){
            $notify_data = $GLOBALS['HTTP_RAW_POST_DATA'] ?: '';//以防上面函数获取到的内容为空
        }
        if(!$notify_data){
            exit('');
        }
        LogsController::actionLogRecord('扫码支付');
        $Pay = new PayModel();
        $result = $Pay->notify_weixin($notify_data);//调用模型中的异步通知函数

        exit($result);
    }

}
