<?php
/**
 * Created by PhpStorm.
 * User: baidu
 * Date: 17/8/6
 * Time: 上午2:45
 */

namespace app\admin\common;


use app\admin\model\AdminMenuModel;
use think\Exception;
use think\Request;
use think\Session;

class Logs {

    public static function actionLogRecord($msg)
    {
        if (!isset($msg) || empty($msg)) {
            $msg = '';
        }
        //获取请求的路由
        $request    = Request::instance();
        $module = $request->module();
        $controller = $request->controller();
        $action = $request->action();
        $actionPath = $request->url();

        //生成文件名
        $logPath = ACTION_LOG_PATH . date('Ym', time());

        $fileNumber = date('d', time());

        $fileName = $logPath.$fileNumber.'.txt';

        try {
            //查询菜单名
            $menuInfo = (new AdminMenuModel)
                ->where('app', '=', $module)
                ->where('controller', '=', $controller)
                ->where('action', '=', $action)
                ->find();

            $arr = [];
            $arr['user_id'] = Session::get('ADMIN_ID')?Session::get('ADMIN_ID'):0;
            $arr['user_name'] = Session::get('name');
            $arr['action_path'] = $actionPath;
            $arr['action_name'] = $menuInfo->name;
            $arr['action_event'] = $msg;
            $arr['access_time'] = time();

            $dir = iconv("UTF-8", "GBK", $logPath);

            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }

            $file = fopen($fileName ,"a");
            fwrite($file,serialize($arr) . PHP_EOL);
            fclose($file);

        } catch (Exception $e) {

        }


//        $test = file_get_contents($fileName);
//
//        $test1 = explode(PHP_EOL,$test);
//        array_pop($test1);
//
//
//
//        dump($test1);
    }


}