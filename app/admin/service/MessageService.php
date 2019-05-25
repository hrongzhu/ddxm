<?php
/**
 * Author: chenjing
 * Date: 2017/11/13
 * Description:
 */

namespace app\admin\service;


use app\wechat\common\lib\exception\ApiException;
use Curl\Curl;
use think\Cache;
use think\Validate;

class MessageService
{
    //发送信息
    public static function sendMessage($mobile)
    {
        if (!preg_match('^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9])\d{8}$',$mobile)) {
            throw new ApiException(301,'手机号码错误!!!');
        }

        $code = getRandChar(4,'numeric');
        $msgtext = $code."（捣蛋熊猫商城身份验证），有效期为10分钟。提示：请勿泄露给他人";

        //检查一天的发送次数(暂时不做)

        //检查验证码是否小于1分钟
        $sendMessageTime = Cache::store('file')->get($mobile.'_time') ? Cache::store('file')->get($mobile.'_time') : 0;
        if (time() - $sendMessageTime < 60) {
            throw new ApiException(301,'发送验证码时间小于1分钟');
        }
        //先删除上一次记录的验证码
        $res = Cache::store('file')->get($mobile);
        if($res){
            Cache::store('file')->rm($mobile);
        }
        //缓存验证码
        $result = Cache::store('file')->set($mobile,$code,600);
        if ($result) {
            Cache::store('file')->set($mobile.'_time',time());
        }
        $res = self::requestMessagePlant($mobile,$msgtext);

        return $res;

    }

    //发送其他短信通知
    public static function sendMessageAll($mobile,$msgtext)
    {
        $result = (new BaseValidate())->check(['mobile' => $mobile],['mobile' => 'isMobile']);
        if (!$result) {
            throw new ApiException(301,'手机号码错误!!!');
        }
        //检查一天的发送次数(暂时不做)
        //检查验证码是否小于1分钟
//        $sendMessageTime = Cache::store('file')->get($mobile.'_time') ? Cache::store('file')->get($mobile.'_time') : 0;
//        if (time() - $sendMessageTime < 60) {
//            throw new ApiException(301,'发送验证信息时间小于1分钟');
//        }
//        //缓存验证码
//        $result = Cache::store('file')->set($mobile,$msgtext,600);
//        if ($result) {
//            Cache::store('file')->set($mobile.'_time',time());
//        }
        return self::requestMessagePlants($mobile,$msgtext);
    }

    //请求短信平台(大周短信平台 网上没文档)-- 发送验证码专用
    public static function requestMessagePlant($mobile,$msgtext)
    {
        //建周短信平台http接口
        $postdata = array();
        $postdata['userId'] = 'J25888';
        $postdata['password'] = '851239';
        $postdata['pszMobis'] = $mobile;
        $postdata['pszMsg'] = $msgtext;   //自动添加签名
        $postdata['iMobiCount'] = 1;
        $postdata['pszSubPort'] = "*";
        $postdata['MsgId'] = getRandChar($length = 8,$type = 'numeric');
        $url = 'http://61.145.229.26:8086/MWGate/wmgw.asmx/MongateSendSubmit';
        $o="";
        foreach ($postdata as $k=>$v)
        {
            if($k =='content')
                $o.= "$k=".urlencode($v)."&";
            else
                $o.= "$k=".($v)."&";
        }

        $postdata=substr($o,0,-1);

        //去除xml标签
        $resutl = strip_tags((new Curl())->post($url,$postdata)->response);

        $res['success'] = 0;

        if(strlen($resutl)>=15){
            $res['success'] = 1;
            $res['msg'] = '验证码发送成功';
        }else{
            $res['msg'] = '验证码发送失败';
        }

        return $res;
    }

    //请求短信平台(大周短信平台 网上没文档) --- 发送通用信息专用
    public static function requestMessagePlants($mobile,$msgtext)
    {
        //建周短信平台http接口
        $postdata = array();
        $postdata['userId'] = 'J25888';
        $postdata['password'] = '851239';
        $postdata['pszMobis'] = $mobile;
        $postdata['pszMsg'] = $msgtext;   //自动添加签名
        $postdata['iMobiCount'] = 1;
        $postdata['pszSubPort'] = "*";
        $postdata['MsgId'] = getRandChar($length = 8,$type = 'numeric');
        $url = 'http://61.145.229.26:8086/MWGate/wmgw.asmx/MongateSendSubmit';
        $o="";
        foreach ($postdata as $k=>$v)
        {
            if($k =='content')
                $o.= "$k=".urlencode($v)."&";
            else
                $o.= "$k=".($v)."&";
        }
        $postdata=substr($o,0,-1);
        //去除xml标签
        $resutl = strip_tags((new Curl())->post($url,$postdata)->response);
        if(strlen($resutl)>=15){
            return true;
        }else{
           return false;
        }
    }

    //验证验证码是否正确
    public static function checkCode($mobile = '',$code = '')
    {
        //测试环境不检测
        if(config('app_debug') === true){
            return true;
        }
        if(empty($mobile) || empty($code)){
            return outPut(301,'数据错误');
        }
        $mobileCode = Cache::store('file')->get($mobile);
        if ($code == $mobileCode) {
            return true;
        }
        return false;
    }
}