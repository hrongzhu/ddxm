<?php
/**
 * Author: chenjing
 * Date: 2017/12/21
 * Description:
 */

namespace app\admin\controller;

use app\admin\model\AdminUserModel;
use app\admin\model\order\OrderModel;
use app\admin\model\shop\PostageModel;
use think\Request;
use think\Session;

/**
 * Class ExpressController
 * @package app\admin\controller
 * @adminMenuRoot(
 *     'name'   =>'运费模板管理',
 *     'action' =>'menuDefault',
 *     'parent' =>'',
 *     'display'=> true,
 *     'order'  => 10000,
 *     'icon'   =>'cogs',
 *     'remark' =>'运费模板管理'
 * )
 */
class ExpressController extends BaseController
{
    /**
     * 运费模板列表
     * @adminMenu(
     *     'name'   => '运费模板列表',
     *     'parent' => 'menuDefault',
     *     'display'=> true,
     *	   'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '运费模板列表',
     *     'param'  => ''
     * )
     */
    public function postageList()
    {
        $info = (new PostageModel())->postageList()->paginate('10');
        // 获取分页显示
        $page = $info->render();
        // 模板变量赋值
        $this->assign('page', $page);
        $this->assign('list', $info);
        return view('postage_list');
    }

    //运费模板添加
    public function postageAdd()
    {
        return view('postage_update');
    }

    //运费模板修改
    public function postageedit()
    {
        $id= $this->request->param('id');
        $info = (new PostageModel())->postageInfo($id);
        $this->assign('info', $info);
        return view('postage_update');
    }

    //运费模板更新
    public function update()
    {
        $data = $this->request->param();
        $id = isset($data['id'])?$data['id']:0;
        if ($id) {
            unset($data['id']);
            $res = (new PostageModel())->updatePostage($data,$id);
            if($res){
                $this->success('更新成功',url("express/postageList"));
            }
            $this->error('更新失败');
        }else{
            $data['addtime'] = time();
            $res = (new PostageModel())->updatePostage($data);
            if($res){
                $this->success('添加成功',url("express/postageList"));
            }
            $this->error('添加失败');
        }

    }

    public function postagedel()
    {
        $id = $this->request->param('id');
        $postage = new PostageModel();
        $res = $postage->destroy($id);
        if ($res) {
           return outPut(200,'删除成功');
        }
         return outPut(301,'删除失败');
    }
}
