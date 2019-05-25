<?php
/**
 * Created by PhpStorm.
 * User: baidu
 * Date: 17/8/6
 * Time: 上午2:45
 */

namespace app\admin\controller;


use app\admin\model\AdminMenuModel;
use app\admin\model\AdminUserModel;
use app\admin\model\LogsModel;
use app\admin\validate\LogsValidate;
use think\Exception;
use think\Request;
use think\Session;
use think\Validate;

class LogsController extends BaseController{

    protected $pageLimit = 12;
    public static function actionLogRecord($msg)
    {
        if (!isset($msg) || empty($msg)) {
            $msg = '';
        }
        //获取请求的路由
        $request    = Request::instance();
        $actionPath = $request->url();
        $arr = [];
        $name = isset($_COOKIE['admin_username'])?$_COOKIE['admin_username']:'系统';
        $arr['user_id'] = Session::get('ADMIN_ID')?Session::get('ADMIN_ID'):1000;
        $arr['user_name'] = Session::get('name')?Session::get('name'):$name;
        $arr['action_path'] = $actionPath;
        $arr['action_event'] = $msg;
        $arr['access_time'] = time();

        return $res = (new LogsModel())->saveLogs($arr);


    }

    /**
     * 日志列表
     * @adminMenu(
     *     'name'   => '日志列表',
     *     'parent' => 'menuDefault',
     *     'display'=> true,
     *	   'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '日志列表',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $time = $this->request->param('time','');
        $content = trim($this->request->param('content',''));
        $admin_name = trim($this->request->param('admin_name',''));
        $where = [];
        if ($time) {
            $time_arr = explode('~',$time);
            $add_s = strtotime($time_arr[0]);
            $add_e = strtotime($time_arr[1])+24*3600;
            $where['access_time'] = ['between',[$add_s,$add_e]];
            $this->assign('stime',$add_s);
            $this->assign('etime',$add_e-24*3600+1);
        }
        if ($admin_name) {
            $where['user_name'] = ['like',"%$admin_name%"];
        }
        $this->assign('admin_name', $admin_name);
        if ($content) {
            $where['action_event'] = ['like',"%$content%"];
        }
        $this->assign('content', $content);
        $list  = (new LogsModel())->logsList($where);
        $pageParams = [
            'content' => $content,
            'admin_name' => $admin_name,
        ];
        $datas = $this->lists($list, $pageParams)->toArray();
        // 获取分页显示
        $this->assign('list', $datas['data']);
        return view('logs_list');
    }

    /**
     * 支付日志
     * @adminMenu(
     *     'name'   => '支付日志',
     *     'parent' => 'menuDefault',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 99,
     *     'icon'   => '',
     *     'remark' => '支付日志',
     *     'param'  => ''
     * )
     */
    public function payLogs()
    {
        $list  = model('IndexLog')->logsList()->paginate(10);
        // 获取分页显示
        $page = $list->render();
        // 模板变量赋值
        $this->assign('page', $page);
        $this->assign('list',$list);
        return view('index_log');
    }
}
