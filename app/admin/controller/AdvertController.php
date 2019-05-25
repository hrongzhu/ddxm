<?php
/**
 * Author: chenjing
 * Date: 2018/1/16
 * Description:
 */

namespace app\admin\controller;


use app\admin\model\AdvertModel;
use app\admin\model\shop\ShopModel;

/**
 * Class AdvertController
 * @package app\admin\controller
 * @adminMenuRoot(
 *     'name'   =>'广告位管理',
 *     'action' =>'menuDefault',
 *     'parent' =>'',
 *     'display'=> true,
 *     'order'  => 10000,
 *     'icon'   =>'cogs',
 *     'remark' =>'广告位管理'
 * )
 */
class AdvertController extends BaseController
{

    /**
     * 广告列表
     * @adminMenu(
     *     'name'   => '广告列表',
     *     'parent' => 'menuDefault',
     *     'display'=> true,
     *	   'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '广告列表',
     *     'param'  => ''
     * )
     */
    public function advertList()
    {
        $list = (new AdvertModel())->advertLists()->paginate(10);
        // 获取分页显示
        $page = $list->render();
        // 模板变量赋值
        $this->assign('page', $page);
        $this->assign('list',$list);
        return view('advert_list');
    }

    //添加
    public function advertAdd()
    {
        $shopList = (new ShopModel())->shopList()->select();
        $catelist = (new AdvertModel())->cateList();
        $host = config('file_server_url');
        $this->assign('info','');
        $this->assign('host',$host);
        $this->assign('shoplist',$shopList);
        $this->assign('catelist',$catelist);
        return view('advert_update');
    }

    //修改
    public function advertEdit()
    {
        $ad_id= $this->request->param('id');
        $info = (new AdvertModel())->advertInfo($ad_id);
        $info['pics'] = json_decode($info['pics'],true);
        $shopList = (new ShopModel())->shopList()->select();
        $catelist = (new AdvertModel())->cateList();
        $host = config('file_server_url');
        $this->assign('shoplist',$shopList);
        $this->assign('catelist',$catelist);
        $this->assign('info',$info);
        $this->assign('host',$host);
        return view('advert_update');
    }

    //更新
    public function update()
    {
        $data = $this->request->param();
        $num = count($data['photo']);
        $pics = [];
        for ($i = 0;$i < $num;$i++){
            $pics[$i]['imgUrl'] = $data['photo'][$i];
            $pics[$i]['text'] = $data['text'][$i];
            $pics[$i]['url'] = $data['url'][$i];
        }
        $data['pics'] = json_encode($pics,JSON_UNESCAPED_UNICODE);
        $data['addtime'] = time();
        //修改
        if (isset($data['id']) && !empty($data['id'])){
            $id = $data['id'];
            unset($data['id']);
            $res = (new AdvertModel())->addOrEdit($data,$id);
            if($res){
                $this->success('更新成功',url("Advert/advertList"));
            }
            $this->error('更新失败');
        }else{
            //新增
            $res = (new AdvertModel())->addOrEdit($data);
            if($res){
                $this->success('添加成功',url("Advert/advertList"));
            }
            $this->error('添加失败');
        }
    }

    //删除广告
    public function advertDel()
    {
        $id = $this->request->param('id');
        $res = (new AdvertModel())->advertDel($id);
        if ($res) {
            return outPut(200,'删除成功');
        }
        return outPut(301,'删除失败');
    }
}