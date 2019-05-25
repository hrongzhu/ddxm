<?php
/**
 * Author: chenjing
 * Date: 2017/11/13
 * Description:
 */

namespace app\admin\service;


use app\wechat\common\lib\exception\ApiException;
use Curl\Curl;

class WechatService
{
    // 常规类型
    public function firstType($data,$types)
    {
        // 获取事件类型
       $type_keys = array_keys($types);
       $type = $type_keys[1];
       // 得到改类型的值
       $type_val = array_values($types);
       $value = $type_val[1];
        //事件类型分离
       switch ($type)
        {
            case "keyword":
                $dataChild = [
                        "type"=>"click",
                        "name" => $types['title'],
                        "$type" => $value,
                        "key"=>"V1001_DIANJI"
                    ];
                break;
            case "url":
                $dataChild = [
                        "type"=>"view",
                        "name" => $types['title'],
                        "$type" => $value,
                    ];
                break;
            // 当为扩展时
            case "wxsys":
                $dataChild = $this->extendType($value);
                break;
            case "tel":

                break;
            // 导航
            case "nav":
                $dataChild = [
                        "type"=>"nav",
                        "name" => $types['title'],
                        "$type" => $value,
                    ];
                break;
            case "replay_text":
                $dataChild = [
                        "type"=>"media_id",
                        'name' => $data['title'],
                        'url' => $data['url']
                    ];
                break;
            case "image":
                $dataChild = [
                        "type"=>"media_id",
                        'name' => $data['title'],
                        'url' => $data['url']
                    ];
                break;
            case "voice":
                $result = $this->receiveVoice($postObj);
                break;
            case "news":
                $dataChild = [
                        "type"=>"view_limited",
                        'name' => $data['title'],
                        'url' => $data['url']
                    ];
                break;
            default:
                $result = $dataChild = [
                "type"=>"click",
                "name"=>"请自定义",
                "key"=>"V1001_DINGYI",
            ];
                break;
        }
        return $dataChild;
    }

    // 扩展类型
    public function extendType($type){
        //消息类型分离
            switch ($type)
            {
                // 系统拍照发图
                case "系统拍照发图":
                    $dataChild = [
                        "type"=>"pic_sysphoto",
                        "name"=>"系统拍照发图",
                        "key"=>"rselfmenu_1_0",
                        "sub_button"=>[ ]
                    ];
                    break;
                // 拍照或者相册发图
                case "拍照或者相册发图":
                    $dataChild = [
                        "type"=>"pic_photo_or_album",
                        "name"=>"拍照或者相册发图",
                        "key"=>"rselfmenu_1_1",
                        "sub_button"=>[ ]
                    ];
                    break;
                // 微信相册发图
                case "微信相册发图":
                    $dataChild = [
                        "type"=>"pic_weixin",
                        "name"=>"微信相册发图",
                        "key"=>"rselfmenu_1_2",
                        "sub_button"=>[ ]
                    ];
                    break;
                case "location_select":
                    $result = $this->receiveLocation($postObj);
                    break;
                // 扫码带提示
                case "扫码带提示":
                    $dataChild = [
                        "type"=>"scancode_waitmsg",
                        "name"=>"扫码带提示",
                        "key"=>"rselfmenu_1_3",
                        "sub_button"=>[ ]
                    ];
                    break;
                // 扫码推事件
                case "扫码推事件":
                    $dataChild = [
                        "type"=>"scancode_push",
                        "name"=>"扫码推事件",
                        "key"=>"rselfmenu_1_4",
                        "sub_button"=>[ ]
                    ];
                    break;
                default:
                    $dataChild = [
                        "type"=>"click",
                        "name"=>"请自定义",
                        "key"=>"V1001_DINGYI",
                    ];
                    break;
            }
            return $dataChild;
    }

    //回复文本消息
    private function transmitText($object, $content)
    {
        if (!isset($content) || empty($content)){
            return "";
        }

        $xmlTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                </xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), $content);

        return $result;
    }

    //回复图文消息
    private function transmitNews($object, $newsArray)
    {
        if(!is_array($newsArray)){
            return "";
        }
        $itemTpl = "<item>
                        <Title><![CDATA[%s]]></Title>
                        <Description><![CDATA[%s]]></Description>
                        <PicUrl><![CDATA[%s]]></PicUrl>
                        <Url><![CDATA[%s]]></Url>
                    </item>";
        $item_str = "";
        foreach ($newsArray as $item){
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        }
        $xmlTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[news]]></MsgType>
                    <ArticleCount>%s</ArticleCount>
                    <Articles>
                    $item_str    </Articles>
                </xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
        return $result;
    }

    //回复音乐消息
    private function transmitMusic($object, $musicArray)
    {
        if(!is_array($musicArray)){
            return "";
        }
        $itemTpl = "<Music>
                        <Title><![CDATA[%s]]></Title>
                        <Description><![CDATA[%s]]></Description>
                        <MusicUrl><![CDATA[%s]]></MusicUrl>
                        <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
                    </Music>";

        $item_str = sprintf($itemTpl, $musicArray['Title'], $musicArray['Description'], $musicArray['MusicUrl'], $musicArray['HQMusicUrl']);

        $xmlTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[music]]></MsgType>
                        $item_str
                    </xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复图片消息
    private function transmitImage($object, $imageArray)
    {
        $itemTpl = "<Image>
                        <MediaId><![CDATA[%s]]></MediaId>
                    </Image>";

        $item_str = sprintf($itemTpl, $imageArray['MediaId']);

        $xmlTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[image]]></MsgType>
                    $item_str
                    </xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复语音消息
    private function transmitVoice($object, $voiceArray)
    {
        $itemTpl = "<Voice>
                        <MediaId><![CDATA[%s]]></MediaId>
                    </Voice>";

        $item_str = sprintf($itemTpl, $voiceArray['MediaId']);
        $xmlTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[voice]]></MsgType>
                    $item_str
                </xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复视频消息
    private function transmitVideo($object, $videoArray)
    {
        $itemTpl = "<Video>
                    <MediaId><![CDATA[%s]]></MediaId>
                    <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
                    <Title><![CDATA[%s]]></Title>
                    <Description><![CDATA[%s]]></Description>
                </Video>";

        $item_str = sprintf($itemTpl, $videoArray['MediaId'], $videoArray['ThumbMediaId'], $videoArray['Title'], $videoArray['Description']);

        $xmlTpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[video]]></MsgType>
                $item_str
                </xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }
}
