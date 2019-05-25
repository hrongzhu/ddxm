<?php
namespace app\admin\common\library;

class AdminHtml
{


    /**
     * 删除按钮
     * @param $url
     * @return string
     */
    public static function deleteButton($url)
    {
        $url = substr($url, 0, 4) == 'http' ? $url : url($url);
        $string = '<a href="javascript:;" onclick="delete_multi(\''.$url.'\')" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 删除</a>';
        return $string;
    }

    /**
     * 启用按钮
     * @param $url
     * @return string
     */
    public static function startButton($url)
    {
        $url = substr($url, 0, 4) == 'http' ? $url : url($url);
        $string = '<a href="javascript:;" onclick="start_multi(\''.$url.'\')" class="btn btn-success radius"><i class="Hui-iconfont">&#xe6e8;</i> 启用</a>';
        return $string;
    }

    /**
     * 禁用按钮
     * @param $url
     * @return string
     */
    public static function stopButton($url)
    {
        $url = substr($url, 0, 4) == 'http' ? $url : url($url);
        $string = '<a href="javascript:;" onclick="stop_multi(\''.$url.'\')" class="btn btn-warning radius"><i class="Hui-iconfont">&#xe6dd;</i> 停用</a>';
        return $string;
    }

    /**
     * 添加按钮
     * @param $url
     * @param array $param
     * @param string $title
     * @return string
     */
    public static function insertButton($url, $param = [], $title = '添加')
    {
        $url = substr($url, 0, 4) == 'http' ? $url : url($url, $param);
        $string = '<a href="javascript:;" onclick="layer_show(\''.$title.'\',\''.$url.'\',\'950\',\'500\')" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i> '.$title.'</a>';
        return $string;
    }

}