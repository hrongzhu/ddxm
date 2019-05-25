<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 小夏 < 449134904@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use app\admin\model\AdminMenuModel;
use app\admin\model\order\OrderModel;
use app\admin\model\PayModel;


class IndexController extends AdminBaseController
{

    public function _initialize()
    {
        $adminSettings = cmf_get_option('admin_settings');
        if (empty($adminSettings['admin_password']) || $this->request->path() == $adminSettings['admin_password']) {
            $adminId = cmf_get_current_admin_id();
            if (empty($adminId)) {
                session("__LOGIN_BY_CMF_ADMIN_PW__", 1);//设置后台登录加密码
            }
        }

        parent::_initialize();
    }

    /**
     * 后台首页
     */
    public function index()
    {
        $adminMenuModel = new AdminMenuModel();
        $menus          = $adminMenuModel->menuTree();

        $this->assign("menus", $menus);

        $admin = Db::name("user")->where('id', cmf_get_current_admin_id())->find();
        $this->assign('admin', $admin);

        return $this->fetch();
    }
    /*
    页面数据
     */
    /*public function indexinfo()
    {
        $pid = 0;
        if($this->request->param()){
            $pid = $this->request->param('pid');
        }

        $Order = new OrderModel;
        $orderwan = $Order->orderwan($pid);
        $Order->
    }*/

    /*
    页面数据
     */
    public function testPay()
    {
        $paymodel = new PayModel();
        $data['openid'] = 'oZULn01pAueXssf4NmT0jjYImUJU';
        $data['order_sn'] = 'ZZ201903051709'.mt_rand(1000,9999);
        $data['user_name'] = '测试用户1';
        $data['amount'] = '0.30';
        $data['desc'] = '测试商家付款到零钱';
        $res = $paymodel->transferToCoin($data);
        dump($res);
    }
}
