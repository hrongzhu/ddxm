<?php
/**
 * Author: chenjing
 * Date: 2017/12/21
 * Description:
 */

namespace app\admin\model;


use think\Log;
use think\Model;
use think\Validate;
use think\Db;
use app\admin\controller\LogsController;
use app\admin\model\order\OrderModel;
use app\admin\model\user\UserModel;

class PayModel extends Model
{

    private function _weixin_config(){//微信支付公共配置函数
        define('WXPAY_APPID', "wxb5ee49b69efc2429");//微信公众号APPID
        define('WXPAY_MCHID', "1486226662");//微信商户号MCHID
        define('WXPAY_KEY', "daodanxiongmaokkgg4945sdfjklghgh");//微信商户自定义32位KEY
        define('WXPAY_APPSECRET', "");//微信公众号appsecret
        vendor('wxpay.WxPay_Api');
        vendor('wxpay.WxPay_NativePay');
    }

    public function weixin($data=[])
    {//发起微信支付，如果成功，返回微信支付字符串，否则范围错误信息
        $validate = new Validate([
            ['body','require','请输入订单描述'],
            ['attach','require','请输入订单标题'],
            ['out_trade_no','require|alphaNum','订单编号输入错误|订单编号输入错误'],
            ['total_fee','require|number|gt:0','金额输入错误|金额输入错误|金额输入错误'],
            ['notify_url','require','异步通知地址不为空'],
            ['trade_type','require|in:JSAPI,NATIVE,APP','交易类型错误'],
        ]);
        if (!$validate->check($data)) {
            return ['code'=>0,'msg'=>$validate->getError()];
        }
        $this->_weixin_config();
        $notify = new \NativePay();
        $input = new \WxPayUnifiedOrder();
        $input->SetBody($data['body']);
        $input->SetAttach($data['attach']);
        $input->SetOut_trade_no($data['out_trade_no']);
        $input->SetTotal_fee($data['total_fee']);
        $input->SetTime_start($data['time_start']);
        $input->SetTime_expire($data['time_expire']);
        $input->SetGoods_tag($data['goods_tag']);
        $input->SetNotify_url($data['notify_url']);
        $input->SetTrade_type($data['trade_type']);
        $input->SetProduct_id($data['product_id']);
        $result = $notify->GetPayUrl($input);
        if($result['return_code'] != 'SUCCESS'){
            return ['code'=>0,'msg'=> $result['return_msg']];
        }
        if($result['result_code'] != 'SUCCESS'){
            return ['code'=>0,'msg'=> $result['err_code_des']];
        }
        return ['code'=>1,'msg'=>$result["code_url"]];
    }

    /**
     * 微信退款
     * @param  array   $data   数据
     */
    public function wxRefund($data = []){
        //查询订单,根据订单里边的数据进行退款
        $this->_weixin_config();
        if(empty($data)){
            return false;
        }
        $wxPay = new \WxPayApi();
        $input = new \WxPayRefund();
        $input->SetOut_trade_no($data['order_sn']);         //自己的订单号
        $input->SetTransaction_id($data['transaction_id']);     //微信官方生成的订单流水号，在支付成功中有返回
        $input->SetOut_refund_no($data['refund_no']);         //退款单号
        $input->SetTotal_fee($data['total_price']);         //订单标价金额，单位为分
        $input->SetRefund_fee($data['total_price']);            //退款总金额，订单总金额，单位为分，只能为整数
        $input->SetOp_user_id(WXPAY_MCHID);
        $result = $wxPay::refund($input); //退款操作
        // 这句file_put_contents是用来查看服务器返回的退款结果 测试完可以删除了
        file_put_contents('refunds.txt',json_encode($result),FILE_APPEND);
        return $result;
    }

    /**
     * 余额退款
     */
    public function balanceRefund($order_id = 0)
    {
        if (empty($order_id)) {
            return false;
        }
        $orderModel = new OrderModel();
        $order_info = $orderModel->where(['id'=>$order_id])->field('member_id,amount')->find();
        $res = (new UserModel())->where(['id'=>$order_info['member_id']])->setInc('money',$order_info['amount']);
        if ($res) {
            return true;
        }
        return false;
    }


    public function notify_weixin($data='')
    {//微信支付异步通知
        if(!$data){
            return false;
        }

        $this->_weixin_config();
        $doc = new \DOMDocument();
        $doc->loadXML($data);
        $out_trade_no = $doc->getElementsByTagName("out_trade_no")->item(0)->nodeValue;
        $transaction_id = $doc->getElementsByTagName("transaction_id")->item(0)->nodeValue;
        $openid = $doc->getElementsByTagName("openid")->item(0)->nodeValue;
        $attach = $doc->getElementsByTagName("attach")->item(0)->nodeValue;
        $input = new \WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = \WxPayApi::orderQuery($input);
        //得到order_id
        $start = strpos($attach,'=');
        $order_id = substr($attach,$start+1);
        if(array_key_exists("return_code", $result) && array_key_exists("result_code", $result) && $result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS"){
            // 处理支付成功后的逻辑业务

            // Db::startTrans();
            try{
                // 提交事务
                $res = Db::connect(config('ddxx'))->name('admin_order')->where(['order_id'=>$order_id])->update(['pay_status'=>1,'pay_time'=>time()]);
                if (!$res) {
                    LogsController::actionLogRecord('代理发货成功,状态修改失败,订单ID:'.$order_id);
                }
                // Db::commit();
            } catch (Exception $e) {
                // 回滚事务
                // Db::rollback();
                LogsController::actionLogRecord('修改支付订单状态失败,订单号:'.$order_id);
            }
            return 'SUCCESS';
        }
        return false;
    }
    //商家付款到零钱
    public function transferToCoin($data = [])
    {
        //查询订单,根据订单里边的数据进行退款
        $this->_weixin_config();
        if(empty($data)){
            return ['code'=>301,'msg'=>'缺少参数'];
        }
        $wxPay = new \WxPayApi();
        $trans_data = [];
        $trans_data['mch_appid']        = WXPAY_APPID;
        $trans_data['mchid']            = WXPAY_MCHID;
        $trans_data['openid']           = $data['openid'];
        $trans_data['partner_trade_no'] = $data['order_sn'];
        $trans_data['re_user_name']     = $data['user_name'];
        $trans_data['amount']           = $data['amount'] * 100;
        $trans_data['check_name']       = 'NO_CHECK';
        $trans_data['desc']             = $data['desc'];
        $trans_data['spbill_create_ip'] = $this->getIp();
        $trans_data['nonce_str']        = $wxPay::getNonceStr();
        $trans_datas = $this->MakeSign($trans_data);
        $result = $wxPay::transferToCoin($this->transferToXml($trans_datas)); //转账到零钱操作
        $result = $this->FromXml($result);
        if(($result['return_code']=='SUCCESS') && ($result['result_code']=='SUCCESS')){
            return ['code'=>200,'msg'=>'转账成功'];
        }else if(($result['return_code']=='FAIL') || ($result['result_code']=='FAIL')){
            // return false;
            return ['code'=>301,'msg'=>$result['err_code_des']];
            //退款失败
        }else{
             return ['code'=>301,'msg'=>'退款失败，联系客服'];
            //失败
        }
    }

    //获取ip
    public function getIp(){
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }elseif(isset($_SERVER['HTTP_CLIENT_IP'])){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $ip_arr = explode(',', $ip);
        return $ip_arr[0];
    }
    /**
     * 生成签名
     * @return ''
     */
    protected function MakeSign($data)
    {
        foreach ($data as $k => $v) {
            $temp[] =$k.'='.$v;
        }
        sort($temp);
        $sign = implode($temp, '&');
        $sign .= '&key='.WXPAY_KEY;
        $data['sign'] = strtoupper(md5($sign));
        return $data;
    }
    /**
     * 数组转xml字符
     * @return ''
     **/
    public function transferToXml($data = [])
    {
        if(!is_array($data)
            || count($data) <= 0)
        {
            return '';
        }

        $xml = "<xml>";
        foreach ($data as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }
    /**
     * 将xml转为array
     * @param string $xml
     * @return ''
     */
    public function FromXml($xml)
    {
        if(!$xml){
            throw new WxPayException("xml数据异常！");
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }
}
