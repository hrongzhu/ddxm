<?php
/**
 * Author: chenjing
 * Date: 2018/1/11
 * Description:
 */

namespace app\admin\controller;

use app\admin\model\AdminUserModel;
use app\admin\model\FranchiseeModel;
use app\admin\model\order\OrderModel;
use app\admin\model\shop\ShopModel;
use think\Db;
use think\Request;
use think\View;


/**
 * Class FranchiseeController
 * @package app\admin\controller
 * @adminMenuRoot(
 *     'name'   =>'加盟商管理',
 *     'action' =>'menuDefault',
 *     'parent' =>'',
 *     'display'=> true,
 *     'order'  => 10000,
 *     'icon'   =>'cogs',
 *     'remark' =>'加盟商管理'
 * )
 */
class FranchiseeController extends BaseController
{

    /**
     * 加盟商列表
     * @adminMenu(
     *     'name'   => '加盟商列表',
     *     'parent' => 'menuDefault',
     *     'display'=> true,
     *	   'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '加盟商列表',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $franList = (new FranchiseeModel())->franchList()->paginate(10);
        // 获取分页显示
        $page = $franList->render();
        // 模板变量赋值
        $this->assign('page', $page);
        $this->assign('List',$franList);
        return view('list');
    }

    //添加门店
    public function addOrUpdate()
    {
        $id= $this->request->param('id','');
        $info = [];
        if ($id){
            $info = (new FranchiseeModel())->franchInfo($id);
        }
        $this->assign('info',$info);
        return view('edit_detail');
    }

    //更新门店信息
    public function update()
    {
        $id = $this->request->param('id','');
        $data = $this->request->param();
//        dump($data);die;
        if ($id){
            //更新
            unset($data['id']);//去掉id
            $res = (new FranchiseeModel())->updateFranch($data,$id);
            if ($res){
                LogsController::actionLogRecord('更新加盟商信息,加盟商:'.$data['name'].',id:'.$id);
                $this->success('更新成功','franchisee/index');
            }
            $this->error('更新失败');
        }
        //新增
        $data['addtime'] = time();
        $res = (new FranchiseeModel())->updateFranch($data);
        if ($res){
            LogsController::actionLogRecord('添加加盟商:'.$data['name']);
            $this->success('新增成功','franchisee/index');
        }
        $this->error('新增失败');
    }

    //删除门店
    public function del()
    {
        $id= $this->request->param('id','');
        if (empty($id)){
            return outPut(301,'参数错误');
        }
        $res = (new FranchiseeModel())->del($id);
        if ($res){
            return outPut(200,'删除成功');
        }
        return outPut(301,'删除失败');
    }

}
