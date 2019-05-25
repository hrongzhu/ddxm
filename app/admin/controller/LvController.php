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
use app\admin\model\CouponModel;
use app\admin\model\LvModel;


/**
 * Class cardController
 * @package app\admin\controller
 * @adminMenuRoot(
 *     'name'   =>'税率模板管理',
 *     'action' =>'menu_default',
 *     'parent' =>'',
 *     'display'=> true,
 *     'order'  => 10000,
 *     'icon'   =>'cogs',
 *     'remark' =>'税率模板管理'
 * )
 */
class LvController extends AdminBaseController
{
	protected $targets = ["_blank" => "新标签页打开", "_self" => "本窗口打开"];
    
    /**
     * 模板税率列表
     * @adminMenu(
     *     'name'   => '税率模板列表',
     *     'parent' => 'menu_default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '税率模板管理',
     *     'param'  => ''
     * )
     */
    public function lv_list()
    {
        $Lv = new LvModel();
        
        $list = $Lv->list();
        $page = $list->render();

        $this->assign("list", $list);
        $this->assign("page", $page);
        return $this->fetch();
    }
    /*
    删除模板操作
     */
    public function delete()
    {
        $id = $this->request->param("id", 0, 'intval');
        
        $Lv = new lvModel();
        $res = $Lv->operation($id,'-1');

        if($res!=1){
            $this->error($res);
        }else{
            $this->success("删除成功！", url('Lv/lv_list'));
        }
    }
    /*
    启用操作
     */
    public function on(){
        $id = $this->request->param("id", 0, 'intval');
        
        $Lv = new LvModel();
        $res = $Lv->operation($id,'1');

        if($res!=1){
            $this->error($res);
        }else{
            $this->success("启用成功！", url('Lv/lv_list'));
        }
    }
    /*
    禁用操作
     */
    public function off(){
        $id = $this->request->param("id", 0, 'intval');
        
        $Lv = new LvModel();
        $res = $Lv->operation($id,'0');

        if($res!=1){
            $this->error($res);
        }else{
            $this->success("禁用成功！", url('Lv/lv_list'));
        }
    }
    /*
    运费模板添加
     */
    public function add(){
        return $this->fetch();
    }
    /*
    运费模板添加操作
     */
    public function addPost(){

        if ($this->request->param()) {

            $data            = $this->request->param();
            $data['addtime'] = time();
            
            $Lv = new LvModel();
            $res = $Lv->add($data);

            if ($res==1) {
                $this->success("添加成功！",url('Lv/lv_list'));
            } else {
                $this->error("您没有做任何修改！");
            }
        }
    }
    /*
    税率模板编辑
     */
    public function edit(){

        $id = $this->request->param("id", 0, 'intval');
        
        $Lv   = new LvModel();
        $info = $Lv->find($id);

        $this->assign("info", $info);
        return $this->fetch();
    }
    /*
    税率编辑操作
     */
    public function editPost(){
        
        if ($this->request->param()) {
            $data   = $this->request->param();
            //print_r($data);die;
            $id = $data['id'];

            $Lv   = new LvModel();
            $res = $Lv->edit($id,$data);
            if ($res==1) {
                $this->success("修改成功！",url('Lv/lv_list'));
            } else {
                $this->error("您没有做任何修改！");
            }
        }
    }
}
