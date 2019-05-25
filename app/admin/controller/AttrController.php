<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/9
 * Time: 17:08
 */

namespace app\admin\controller;


use app\admin\model\AttrModel;
use app\admin\model\ItemModel;
use cmf\controller\AdminBaseController;
use think\console\Output;
use think\Db;


class AttrController extends AdminBaseController
{
    /**
     * 属性管理
     * @adminMenu(
     *     'name'   => '属性管理',
     *     'parent' => 'menuDefault',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '属性管理',
     *     'param'  => ''
     * )
     */
    //属性列表
    public function Index(){
        $category_id = $this->request->param('category_id');
        $where = ['status'=>1];
        if($category_id){
            $where['category_id'] = $category_id;
            $this->assign('category_id',$category_id);
        }
        //获取所有商品分类
        $type_list = (new ItemModel())->type_list();
        $this->assign('type_list',$type_list);
        $list = (new AttrModel())->get_attr_list($where);
        $this->assign('list',$list);
        $this->assign('page',$list->render());
        return $this->fetch();
    }

    public function AddOrUpdate()
    {
        $category_id = input('post.category_id');
        $attr_name = input('post.attr_name');
        $attr_id = input('post.id','');
        if($attr_id){
            $where['id'] = $attr_id;
            $result = Db::connect(config('ddxx'))->name('attr')->where(['id'=>$attr_id])
                ->update(['category_id'=>$category_id,'attr_name'=>$attr_name,'time'=>time()]);
        }else{
            $result = Db::connect(config('ddxx'))->name('attr')
                ->insert(['category_id'=>$category_id,'attr_name'=>$attr_name,'time'=>time()]);
        }
        if($result){
            return \outPut(1,'success');
        }else{
            return \outPut(0,'fail');
        }
    }

    public function getcategory()
    {
        $id = input('post.id');
        $db = Db::connect(config('ddxx'));
        $category_info = $db->name('attr')->where(['id'=>$id])->field('attr_name,category_id')->find();
        if($category_info){
            return \outPut(1,'success',['category_id'=>$category_info['category_id'],'attr_name'=>$category_info['attr_name']]);
        }else{
            return \outPut(0,'fail');
        }
    }

    public function delete()
    {
        $id = input('post.id');
        $result = Db::connect(config('ddxx'))->name('attr')->where(['id'=>$id])->delete();
        if($result){
            return \outPut(1);
        }else{
            return \outPut(0);
        }
    }

}