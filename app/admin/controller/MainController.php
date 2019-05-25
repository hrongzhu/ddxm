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
use app\admin\model\SpecialModel;
use think\Db;
use app\admin\model\Menu;
use think\Session;

class MainController extends AdminBaseController
{

    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     *  后台欢迎页
     */
    public function index()
    {
        $dashboardWidgets = [];
        $widgets          = cmf_get_option('admin_dashboard_widgets');

        $defaultDashboardWidgets = [
            '_SystemCmfHub'           => ['name' => 'CmfHub', 'is_system' => 1],
            '_SystemMainContributors' => ['name' => 'MainContributors', 'is_system' => 1],
            '_SystemContributors'     => ['name' => 'Contributors', 'is_system' => 1],
            '_SystemCustom1'          => ['name' => 'Custom1', 'is_system' => 1],
            '_SystemCustom2'          => ['name' => 'Custom2', 'is_system' => 1],
            '_SystemCustom3'          => ['name' => 'Custom3', 'is_system' => 1],
            '_SystemCustom4'          => ['name' => 'Custom4', 'is_system' => 1],
            '_SystemCustom5'          => ['name' => 'Custom5', 'is_system' => 1],
        ];

        if (empty($widgets)) {
            $dashboardWidgets = $defaultDashboardWidgets;
        } else {
            foreach ($widgets as $widget) {
                if ($widget['is_system']) {
                    $dashboardWidgets['_System' . $widget['name']] = ['name' => $widget['name'], 'is_system' => 1];
                } else {
                    $dashboardWidgets[$widget['name']] = ['name' => $widget['name'], 'is_system' => 0];
                }
            }

            foreach ($defaultDashboardWidgets as $widgetName => $widget) {
                $dashboardWidgets[$widgetName] = $widget;
            }


        }

        $dashboardWidgetPlugins = [];

        $hookResults = hook('admin_dashboard');

        if (!empty($hookResults)) {
            foreach ($hookResults as $hookResult) {
                if (isset($hookResult['width']) && isset($hookResult['view']) && isset($hookResult['plugin'])) { //验证插件返回合法性
                    $dashboardWidgetPlugins[$hookResult['plugin']] = $hookResult;
                    if (!isset($dashboardWidgets[$hookResult['plugin']])) {
                        $dashboardWidgets[$hookResult['plugin']] = ['name' => $hookResult['plugin'], 'is_system' => 0];
                    }
                }
            }
        }

        $smtpSetting = cmf_get_option('smtp_setting');

        //库存预警信息
        $except_role_id = [1,6,7,9];//不限制的门店管理员角色
        $show_role_id = [1,5,6,9];//可以看库存的人
        $shop_id = Session::get('SHOP_ID');
        $admin_id = $this->admin_id;
        $role_id = Db::name('role_user')->where('user_id','=',$admin_id)->value('role_id');
        $where = [];
        $where['del'] = 0;
        if ($admin_id == 1 ||in_array($role_id,$except_role_id)) {
            $where['a.status'] = 1;
        }else{
            $where['a.status'] = 1;
            $where['a.shop_id'] = $shop_id;
        }
        $show_stock = 0;
        if(in_array($role_id, $show_role_id) || $admin_id == 1){
            $show_stock = 1;
        }
        //分页参数
        $pageParams = [
            // 'shop_id' => $shopId != null ? $shopId : '',
        ];
        $stock_alert = $this->getItemStockAlertList($where)->paginate(15, false, ['query'=>$pageParams]);
        $this->assign('stock_alert', $stock_alert);
        //  分页样式
        $this->assign('page', $stock_alert->render());
        //  总条数
        $this->assign('totalCount', $stock_alert->total());
        //  当前页面
        $this->assign('current', $stock_alert->currentPage());
        //  每页显示数量
        $this->assign('listRows', $stock_alert->listRows());

        $this->assign('show_stock', $show_stock);
        //********************************************************************
        $this->assign('dashboard_widgets', $dashboardWidgets);
        $this->assign('dashboard_widget_plugins', $dashboardWidgetPlugins);
        $this->assign('has_smtp_setting', empty($smtpSetting) ? false : true);

        return $this->fetch();
    }

    public function dashboardWidget()
    {
        $dashboardWidgets = [];
        $widgets          = $this->request->param('widgets/a');
        if (!empty($widgets)) {
            foreach ($widgets as $widget) {
                if ($widget['is_system']) {
                    array_push($dashboardWidgets, ['name' => $widget['name'], 'is_system' => 1]);
                } else {
                    array_push($dashboardWidgets, ['name' => $widget['name'], 'is_system' => 0]);
                }
            }
        }

        cmf_set_option('admin_dashboard_widgets', $dashboardWidgets, true);

        $this->success('更新成功!');

    }
    /*
     *首页数据
     */
    public function getItemStockAlertList($where = [])
    {
        return Db::connect(config('ddxx'))
            ->name('item_stock_alert')->alias('a')
            ->join('tf_item b','a.item_id = b.id','LEFT')
            ->join('tf_shop c','a.shop_id = c.id','LEFT')
            ->field('a.id,a.stock,b.title,c.name,a.addtime')
            ->where($where)->order('a.addtime desc');
    }
    /**
     * [updateStockAlertStatus 更新库存警戒的状态]
     * @return [type] [description]
     */
    public function updateStockAlertStatus()
    {
        $datas = $this->request->param();
        $type = $datas['type'];
        $sa_id = $datas['id'];
        $except_role_id = [1,6,7,9];//不限制的门店管理员角色
        $admin_id = $this->admin_id;
        $role_id = Db::name('role_user')->where('user_id','=',$admin_id)->value('role_id');
        if ($admin_id != 1 ||in_array($role_id,$except_role_id) == false) {
            return outPut(301,'你没有权限操作');
        }
        $data = [];
        if ($type == 1) {
            $data['status'] = 0;
        }else{
            $data['status'] = 0;
            $data['del'] = 1;
        }
        $res = Db::connect(config('ddxx'))->name('item_stock_alert')->where(['id'=>$sa_id])->update($data);
        if ($res) {
            return outPut(200,'操作成功');
        }
        return outPut(301,'操作失败');
    }
}
