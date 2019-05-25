<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/17 0017
 * Time: 下午 15:07
 */

namespace app\admin\controller;


use app\admin\model\PurchaseModel;
use cmf\controller\AdminBaseController;
use think\Db;
use think\Session;

class SupplierController extends AdminBaseController
{

    public function index()
    {
        $keywords = $this->request->param('keywords');
        $where = [];
        if($keywords){
            $where['name|linkman|tel'] = ['like',"%$keywords%"];
            $this->assign('keywords',$keywords);
        }
        $pageParams = [
            'keywords' => $keywords
        ];
        $where['status'] = ["neq",-1];
        $list = db('supplier')->where($where)->paginate(15,false,['query'=>$pageParams]);
        $this->assign('list',$list);
        return $this->fetch();
    }

    public function getOne()
    {
        $id = input('post.id');
        if(!$id){
            return outPut(0,'','ID参数丢失！');
        }
        $item = db('supplier')->where(['id'=>$id])->find();
        if(!$item){
            return outPut(0,'','数据不存在！');
        }
        return outPut(1,'',$item);
    }

    public function save()
    {
        $data = input('post.');
        if(!$data['name']){
            return outPut(0,'','供应商名不能为空');
        }
        if(!$data['linkman']){
            return outPut(0,'','联系人不能为空');
        }
        if(!$data['tel']){
            return outPut(0,'','联系电话不能为空');
        }
        if(!$data['remark']){
            return outPut(0,'','备注信息不能为空');
        }
        $id = $data['id'];
        unset($data['id']);
        $data = array_merge($data,['time'=>time()]);
        if($id){
            $res = db('supplier')->where(['id'=>$id])->update($data);
        }else{
            $res = db('supplier')->insert($data);
        }
        if($res){
            return outPut(1,'success');
        }else{
            return outPut(0,'fail','保存失败');
        }
    }

    public function delete()
    {
        $id = input('post.id');
        $res = db('supplier')->where(['id'=>$id])->update(['status'=>-1]);
        if(!$res){
            return outPut(0,'','删除失败');
        }
        return outPut(1,'','删除成功');
    }

}