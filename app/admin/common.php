<?php

/**
 * 删除按钮
 * @param $url
 * @return string
 */
function deleteButton($url)
{
    return \app\admin\common\library\AdminHtml::deleteButton($url);
}

/**
 * 启用按钮
 * @param $url
 * @return string
 */
function startButton($url)
{
    return \app\admin\common\library\AdminHtml::startButton($url);
}

/**
 * 禁用按钮
 * @param $url
 * @return string
 */
function stopButton($url)
{
    return \app\admin\common\library\AdminHtml::stopButton($url);
}

/**
 * 添加按钮
 * @param $url
 * @param array $param
 * @param string $title
 * @return string
 */
function insertButton($url, $param = [], $title = '添加')
{
    return \app\admin\common\library\AdminHtml::insertButton($url, $param, $title);
}

/**
 * 排序按钮
 * @param $url
 * @return string
 */
function sortButton($url)
{
    return \app\admin\common\library\AdminHtml::sortButton($url);
}

/**
 * 排序文本框
 * @param string $value
 * @param int $width
 * @return string
 */
function sortInputText($value = '', $width = 50)
{
    return \app\admin\common\library\AdminHtml::sortInputText($value, $width);
}

/**
 * 音频上传控件
 * @param string $inputName input名称
 * @param string $serverUrl 上传地址
 * @param string $uploadMode 上传方式 sign|server 签名直伟方式或服务器中转方式
 * @param string $module 上传的具体模块数据
 * @param string $fileName 上传人控件名称
 * @param string $defaultVal input默认值
 * @param string $placeholder 提示信息
 * @return string
 */
function pictureInput($inputName, $defaultVal = '', $module = 'common', $uploadMode = 'sign', $serverUrl = '', $fileName = 'file', $placeholder = '')
{
    return \app\admin\common\library\AdminHtml::pictureInput($inputName, $defaultVal, $module, $uploadMode, $serverUrl, $fileName, $placeholder);
}

/**
 * 音频上传控件
 * @param string $inputName input名称
 * @param string $module 上传的具体模块数据
 * @param string $serverUrl 上传地址
 * @param string $uploadMode 上传方式 sign|server 签名直伟方式或服务器中转方式
 * @param string $fileName 上传人控件名称
 * @param string $defaultVal input默认值
 * @param string $placeholder 提示信息
 * @return string
 */
function audioInput($inputName, $defaultVal = '', $module = 'common', $uploadMode = 'sign', $serverUrl = '', $fileName = 'file', $placeholder = '')
{
    return \app\admin\common\library\AdminHtml::audioInput($inputName, $defaultVal, $module, $uploadMode, $serverUrl, $fileName, $placeholder);
}

/**
 * 视频上传控件
 * @param string $inputName input名称
 * @param string $module 上传的具体模块数据
 * @param string $serverUrl 上传地址
 * @param string $uploadMode 上传方式 sign|server 签名直伟方式或服务器中转方式
 * @param string $fileName 上传人控件名称
 * @param string $defaultVal input默认值
 * @param string $placeholder 提示信息
 * @return string
 */
function videoInput($inputName, $defaultVal = '', $module = 'common', $uploadMode = 'sign', $serverUrl = '', $fileName = 'file', $placeholder = '')
{
    return \app\admin\common\library\AdminHtml::videoInput($inputName, $defaultVal, $module, $uploadMode, $serverUrl, $fileName, $placeholder);
}

/**
 * 文件上传控件
 * @param string $inputName input名称
 * @param string $module 上传的具体模块数据
 * @param string $serverUrl 上传地址
 * @param string $uploadMode 上传方式 sign|server 签名直伟方式或服务器中转方式
 * @param string $fileName 上传人控件名称
 * @param string $defaultVal input默认值
 * @param string $placeholder 提示信息
 * @return string
 */
function fileInput($inputName, $defaultVal = '', $module = 'common', $uploadMode = 'sign', $serverUrl = '', $fileName = 'file', $placeholder = '')
{
    return \app\admin\common\library\AdminHtml::fileInput($inputName, $defaultVal, $module, $uploadMode, $serverUrl, $fileName, $placeholder);
}

function downloadInput($inputName, $defaultVal = '', $module = 'common', $uploadMode = 'sign', $serverUrl = '', $fileName = 'file', $placeholder = '')
{
    return \app\admin\common\library\AdminHtml::downloadInput($inputName, $defaultVal, $module, $uploadMode, $serverUrl, $fileName, $placeholder);
}
/**
 * 精简版编辑器
 * @param $inputName
 * @param string $defaultVal
 * @param string $module
 * @param array $editorConfig
 * @return string
 */
function simplifyEditor($inputName, $defaultVal = '请输入内容...', $module='common', $editorConfig = [])
{
    return \app\common\library\Html::simplifyEditor($inputName, $defaultVal, $module, $editorConfig);
}

/**
/**
 * 完整版编辑器
 * @param $inputName    表单名称
 * @param string $defaultVal    默认值
 * @param string $module        模块
 * @param array $editorConfig   编辑器配置
 * @return string
 */
function completeEditor($inputName, $defaultVal = '请输入内容...', $module='common', $editorConfig = [])
{
    return \app\common\library\Html::completeEditor($inputName, $defaultVal, $module, $editorConfig);
}

/**
 * 营养元素选择器
 * @param $ingredientId
 * @return mixed
 */
function nutrientSelector($ingredientId)
{
    return \app\admin\common\library\AdminHtml::nutrientSelector($ingredientId);
}

/**
 * 功效选择器
 * @param $ingredientId
 * @return mixed
 */
function effectSelector($ingredientId)
{
    return \app\admin\common\library\AdminHtml::effectSelector($ingredientId);
}

//比较数组中的不同键值
function getDiffArr(array $updateArr,array $orignArr){

    array_intersect_key($updateArr,$orignArr);

    $orignArr = array_intersect_key($orignArr,$updateArr);

    $afterUpdateData = array_diff($updateArr,$orignArr);
    $beforeUpdateData = array_intersect_key($orignArr,$afterUpdateData);

    return [
        $beforeUpdateData,
        $afterUpdateData
    ];
}

/**
 * 伪造IP
 * */
function fakeIp(){
    $binfo =array('Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; InfoPath.2; AskTbPTV/5.17.0.25589; Alexa Toolbar)','Mozilla/5.0 (Windows NT 5.1; rv:22.0) Gecko/20100101 Firefox/22.0','Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET4.0C; Alexa Toolbar)','Mozilla/4.0(compatible; MSIE 6.0; Windows NT 5.1; SV1)',$_SERVER['HTTP_USER_AGENT']);
    //123.125.68.*
    //125.90.88.*

    //定义伪造IP来源段，这里我找的是百度的IP地址
    //复制代码 代码如下:
    $cip = '14.111.58.'.mt_rand(0,254);
    $xip = '125.90.88.'.mt_rand(0,254);
    $header = array(
            'CLIENT-IP:'.$cip,
            'X-FORWARDED-FOR:'.$xip,
            'Accept: text/html,application/xhtml+xml,application/xml搜索;q=0.9,*/*;q=0.8',
            );

    return $header;
}

//此函数提供了国内的IP地址
function randIP(){
       $ip_long = array(
           array('607649792', '608174079'), //36.56.0.0-36.63.255.255
           array('1038614528', '1039007743'), //61.232.0.0-61.237.255.255
           array('1783627776', '1784676351'), //106.80.0.0-106.95.255.255
           array('2035023872', '2035154943'), //121.76.0.0-121.77.255.255
           array('2078801920', '2079064063'), //123.232.0.0-123.235.255.255
           array('-1950089216', '-1948778497'), //139.196.0.0-139.215.255.255
           array('-1425539072', '-1425014785'), //171.8.0.0-171.15.255.255
           array('-1236271104', '-1235419137'), //182.80.0.0-182.92.255.255
           array('-770113536', '-768606209'), //210.25.0.0-210.47.255.255
           array('-569376768', '-564133889'), //222.16.0.0-222.95.255.255
       );
       $rand_key = mt_rand(0, 9);
       $ip= long2ip(mt_rand($ip_long[$rand_key][0], $ip_long[$rand_key][1]));
       $headers['CLIENT-IP'] = $ip;
       $headers['X-FORWARDED-FOR'] = $ip;

       $headerArr = array();
       foreach( $headers as $n => $v ) {
           $headerArr[] = $n .':' . $v;
       }
       return $headerArr;
}

/**
 * get获取网页内容
 * @header 伪造IP句柄
 * */
function getUrlContent($url,$header,$cookie = null, $is_https = 0)
{
    $ch = curl_init();
    curl_setopt ($ch, CURLOPT_URL, "$url");
    if(!empty($header)){
        $header = randIP();
        curl_setopt ($ch, CURLOPT_HTTPHEADER, $header);
    }
    // curl_setopt ($ch, CURLOPT_REFERER, "zsxs.shu22.cn");
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36 MicroMessenger/6.5.2.501 NetType/WIFI WindowsWechat QBCore/3.43.493.400 QQBrowser/9.0.2524.400");
    curl_setopt ($ch, CURLOPT_ENCODING, "gzip, deflate");
    //curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

    if($is_https) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    }

    if(!empty($cookie))
        curl_setopt($ch,CURLOPT_COOKIE,$cookie);
    $contents = curl_exec($ch);
    $info = curl_getinfo ($ch);
    // var_dump($info);die;
    curl_close($ch);
    return $contents;
}

// GET 请求
function curl_get($url,$is_https = 1,$header = '')
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    if(!empty($header)){
        $header = randIP();
        curl_setopt ($ch, CURLOPT_HTTPHEADER, $header);
    }
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36 MicroMessenger/6.5.2.501 NetType/WIFI WindowsWechat QBCore/3.43.493.400 QQBrowser/9.0.2524.400");
    // curl_setopt($ch, CURLOPT_USERAGENT, "Dalvik/1.6.0 (Linux; U; Android 4.1.2; DROID RAZR HD Build/9.8.1Q-62_VQW_MR-2)");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    if($is_https) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    }
    $output = curl_exec($ch);
    $info = curl_getinfo ($ch);
    // dump($info);die;
    curl_close($ch);
    return $output;
}

/**
 * @return mixed
 * 获取access_token
 */
 //获取access_token
 function get_access_token($appid = '',$secret = '')
 {
     /**
      * 先去数据库里找access_token如果没有就取请求，请求
      */
     if (empty($appid) && empty($secret)){
         $appid = config('wechat.app_id');
         $secret = config('wechat.secret');
     }
     $update_time = \think\Db::connect(config('ddxx'))->name('wechat_data')->where('type','access_token')->value('expire_time');
     $accessToken = \think\Db::connect(config('ddxx'))->name('wechat_data')->where('type','access_token')->value('content');
     if (empty($accessToken) || $update_time < time()) {
         $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$secret;
         $result = curl_request($url);
         $resObj = json_decode($result);
         $accessToken = $resObj->access_token;
         if (!empty($accessToken)) {
             \think\Db::connect(config('ddxx'))
                 ->name('wechat_data')
                 ->where(['type'=>'access_token'])
                 ->update(['content' => $accessToken,'expire_time'=>time() + 4800]);
         }
     }
     return $accessToken;
 }

function sendtemplateinfo($token,$json_data)
{
    $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$token";
    $result = weChatCurl($url, 1, $json_data);
    return $result;
}
/**
 * 微信专用curl
 * @url接口地址
 * @type 1post 默认0get
 * @data post数据包
 * */
function weChatCurl($url, $type=0, $data = array()){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if($type) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    $output = curl_exec($ch);
    curl_close($ch);
    $jsoninfo = json_decode($output, true);

    return $jsoninfo;
}
/**
 * @param $url
 * @param $type
 * @param array $data
 * @return mixed
 * 模拟发送get  和   post 请求
 */
function curl_request($url,$type = "get", $data = "")
{
    $type = strtolower($type);//字母全转小写
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
    curl_setopt($ch,CURLOPT_HEADER,0);
    if ($type == 'post') {
        // 发送post请求配置
        curl_setopt ($ch, CURLOPT_POST, 1 );
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $data );
    }
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
/**
 * [api_img 上传文件到文件服务器中转时的处理方法]
 * @param  [type] $url  [要上传文件的路径]
 * @param  [type] $file [要上传的文件]
 * @return [type]       [最终返回的文件地址]
 */
function api_img($url,$file)
{

    // 临时文件命名
    $tmpname = $file['file']['name'];
    //临时文件路径
    $tmpfile = $file['file']['tmp_name'];
    // 文件类型
    $tmpType = $file['file']['type'];
    $filepath = uploadfile($url,$tmpname,$tmpfile,$tmpType);
    return $filepath;
}

/**
 * [uploadfile php5.5以后用于上传文件的方法,
 * 因为php5.5版本以后不支持使用@表示文件传输]
 * @param  [type] $url      [请求的接口地址]
 * @param  [type] $filename [文件名]
 * @param  [type] $path     [文件路径]
 * @param  [type] $type     [文件类型]
 * @return [type]           [返回的保存的文件地址]
 */
function uploadfile($url,$filename,$path,$type){

    $data = array(
        'img' => new \CURLFile($path, $type, $filename),
    );
    $ch = curl_init ();
    if (stripos ( $url, "https://" ) !== FALSE) {
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, false);
    }
//    curl_setopt ($ch, CURLOPT_SAFE_UPLOAD, false);
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_POSTFIELDS, $data);
    $sContent = curl_exec ($ch);
    $aStatus = curl_getinfo ($ch);
    curl_close ($ch);
    if (intval ($aStatus ["http_code"]) == 200) {
        return $sContent;
    } else {
        return  $aStatus;
    }

}
/**
 * [arrayFieldTransferString 取出数组字段转字符串]
 * @param  array  $array [description]
 * @param  string $field [description]
 * @param  string $cut   [description]
 * @return string        [description]
 */
function arrayFieldTransferString(array $array,$field = '',$cut = ',')
{
    $str = '';
    for ($i = 0; $i < count($array); $i++) {
        if ($i == count($array) -1) {
            $str .= $array[$i][$field];
        }else{
            $str .= $array[$i][$field].$cut;
        }
    }
    return $str;
}

/**
 * [arrayFieldTransferArray 取出数组字段组成新数组]
 * @param  array  $array [description]
 * @param  string $field [description]
 * @return array         [description]
 */
function arrayFieldTransferArray(array $array,$field = '')
{
    $new_array = [];
    foreach ($array as $v) {
        array_push($new_array,$v[$field]);
    }
    return $new_array;
}

/**
 * 把某个键值设为键，某个键值设为值
 * @param array $datas
 * @param string $pk
 * @param string $vk
 * @return array
 */
function changeKeyTransferVal(array $datas = [], $pk = 'id',$vk = 'id')
{
    $arr = [];
    foreach ($datas as $v){
        $arr[$v[$pk]] = $v[$vk];
    }
    return $arr;
}
/**
 * 把某数组key 和 value 换换
 * @param array $datas
 * @param string $pk
 * @param string $vk
 * @return array
 */
function changeValTransferKey(array $datas = [], $key = '',$val = '')
{
    $arr = [];
    foreach ($datas as $k => $v){
        $arr[$k][$key] = $k;
        $arr[$k][$val] = $v;
    }
    return $arr;
}
/**
 * [installmentAlgorithm 分期付款算法]
 * @method installmentAlgorithm
 * @param  integer              $amount         [需分期总金额]
 * @param  integer              $nper           [总【月/期】数]
 * @param  string               $return_type    [返回数据类型 支持 json/array]
 * @param  integer              $current_nper   [当前【月/期】数，不传就返回所有]
 * @return array                default         [返回数组]
 */
function installmentAlgorithm($amount = 0,$nper = 0,$return_type = 'json',$current_nper = 0)
{
    //先求余数
    $remainder          = $amount % $nper;
    $avg_month_money    = ($amount - $remainder) / $nper;
    $nper_money_arr = [];
    for ($i = 0; $i < $nper; $i++) {
        $nper_money_arr[$i] = $i == 0?$avg_month_money + $remainder:$avg_month_money;
    }
    if ($return_type == 'json') {
        return $current_nper?$nper_money_arr[$current_nper-1]:json_encode($nper_money_arr);
    }else{
        return $current_nper?$nper_money_arr[$current_nper-1]:$nper_money_arr;
    }
}
