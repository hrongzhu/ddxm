<?php
// 应用公共文件

/*
* 把对象转为数组格式
* @param $object
* @return mixed
*/
function objectToArray($object)
{
    $array = [];
    if (is_object($object)) {
        foreach ($object as $key => $value) {
            if (is_object($value)) {
                objectToArray($value);
            }
            $array[$key] = $value;
        }
    }
    return $array;
}

/**
 * 把数组转为对象格式
 * @param array $array
 * @return StdClass
 */
function arrayToObject(array $array)
{
    $obj = new StdClass();
    if (is_array($array)) {
        foreach ($array as $key => $val) {
            if (is_array($val)) {
                arrayToObject($val);
            }
            $obj->$key = $val;
        }
    }
    return $obj;
}

/**
 * 字符串转换为数组，主要用于把分隔符调整到第二个参数
 * @param  string $str  要分割的字符串
 * @param  string $glue 分割符
 * @return array
 */
function str2arr($str, $glue = ','){
    return explode($glue, $str);
}

/**
 * 将驼峰转下划线
 * @param $str
 * @return mixed
 */
function hump_to_underline($str){
    $str = preg_replace_callback('/([A-Z]{1})/',function($matches){
        return '_'.strtolower($matches[0]);
    },$str);
    return $str;
}

/**
 * 获取13位的时间戳
 * @return int
 */
function get13TimeStamp() {
    list($t1, $t2) = explode(' ', microtime());
    return $t2 . ceil($t1 * 1000);
}

/**
 * @param Int $length 字符串长度
 * @return string
 */
function getRandChar($length,$type = 'numeric')
{
    $str = null;
    switch ($type){
        case 'numeric':
            $strPol = "0123456789";
            break;
        default:
            $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    }
    $max = strlen($strPol) - 1;

    for ($i = 0;
         $i < $length;
         $i++) {
        $str .= $strPol[rand(0, $max)];
    }

    return $str;
}

function outPut($code = 0,$msg = '',$data = [])
{
    $arr = [
        'code' => $code,
        'msg' => $msg,
        'data' => $data
    ];
    return json($arr);
}

/**
 * 把主键转化为键名
 * @param array $datas
 * @param string $pk
 * @return array
 */
function changePkAsKey(array $datas = [], $pk = 'id')
{
    $arr = [];
    foreach ($datas as $v){
        $arr[$v[$pk]] = $v;
    }
    return $arr;
}

/**
 * 创建支付流水号
 * @return string
 */
function createPaySn()
{
    return '42000000'.date('W').date('Ymd').rands(10);//支付流水号
}

function rands($no)
{
    //range 是将1到100 列成一个数组
    $numbers = range (0,9);
    //shuffle 将数组顺序随即打乱
    shuffle ($numbers);
    //array_slice 取该数组中的某一段
    $result = array_slice($numbers,0,$no);
    $str = '';
    for ($i=0;$i<$no;$i++){
        $str.= $result[$i];
    }
    return $str;
}
