<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/23
 * Time: 9:43
 */

namespace app\admin\controller;


use app\admin\model\ItemModel;
use app\admin\model\LevelModel;
use app\admin\model\MemberGoodsModel;
use think\Db;
use think\Session;

class MemberGoodsController extends BaseController
{
    /*
     * 会员商品活动列表
     */
    public function index(){
        $level_list = (new LevelModel())->get_level_list();
        $item = new ItemModel();
        $f_list = $item->tiemYlian();
        $this->assign('f_list',$f_list);
        $this->assign('level_lists',$level_list);
        return $this->fetch('index');
    }

    /**
     * 新增或修改会员商品数据
     * @return string|\think\response\Json
     */
    public function save(){
        $data = input('post.');
        if (isset($data['id'])) {
            $id = $data['id'];
            unset($data['id']);
            $result = (new MemberGoodsModel())->update_member_goods($id,$data);
        }else{
            $result = (new MemberGoodsModel())->save_goods($data);
        }
        if($result){
            return outPut(1,'保存成功');
        }else{
            return outPut(0,'保存失败');
        }
    }

    public function delMemberGoods(){
        $id = input('post.id');
        if (MemberGoodsModel::destroy($id)) {
            return outPut(1,'删除成功');
        }else{
            return outPut(0,'删除失败');
        }
    }

    /**
     * @api {GET} /admin/MemberGoods/getPage ajax获取分页商品数据
     * @apiGroup 采购单管理
     * @apiName getPage
     * @apiVersion 1.0.0
     * @apiDescription  ajax获取分页商品数据
     * @apiParam (请求参数) {str} curPage 页数 不可空
     * @apiParam (请求参数) {str} goods_name 商品名称 可空
     * @apiParam (请求参数) {str} f_cate 一级分类ID 可空
     * @apiParam (请求参数) {str} s_cate 二级分类ID 可空
     * @apiSuccess (返回参数) {Int} code 状态码 200成功 301错误
     * @apiSuccess (返回参数) {str} msg 请求结果 success成功 fail失败
     * @apiSuccess (返回参数) {array} data 返回参数结果集
     * @apiSuccess (返回参数) {int} data.pageSize 每页记录条数
     * @apiSuccess (返回参数) {int} data.totalItem 总记录条数
     * @apiSuccess (返回参数) {int} data.totalPage 总页数
     * @apiSuccess (返回参数) {array} data.f_list 一级分类列表
     * @apiSuccess (返回参数) {array} data.data_content 商品数据
     * @apiSuccess (返回参数) {str} data.data_content.id 商品ID
     * @apiSuccess (返回参数) {str} data.data_content.title 商品名称
     * @apiSuccess (返回参数) {str} data.data_content.cname 商品分类
     * @apiSuccess (返回参数) {str} data.data_content.bar_code 商品条形码
     * @apiSuccess (返回参数) {str} data.data_content.price 商品原价
     * @apiSuccess (返回参数) {str} data.data_content.is_price_control 是否控价 1是 0不是
     * @apiSuccess (返回参数) {float} data.data_content.cg_standard_price 采购单价
     * @apiSuccess (返回参数) {float} data.data_content.md_standard_price 门店单价
     * @apiSuccessExample {json} 返回样例:
     * {"code":"1","msg":"成功","time":1532401296,"data":{"totalItem":2,"pageSize":10,"totalPage":1,"data_content":[{"id":3,"title":"惠氏金装婴儿奶粉 3段2罐","cname":"惠氏","is_price_control":0,"price":"268.00","bar_code":"6615249817","cg_standard_price":"210.00","md_standard_price":"258.00"},{"id":4,"title":"惠氏启赋金装二段2罐装","cname":"惠氏","is_price_control":0,"price":"359.00","bar_code":"1231431232","cg_standard_price":"309.00","md_standard_price":"339.00"}]}}
     * @apiSampleRequest /admin/MemberGoods/getPage
     */
    public function getPage(){
        if (request()->isAjax()) {
            //1.获取数据（curPage）
            $page=input('get.');
            if (empty($page)) {
                $page = input('post.');
            }
            $goods_name = isset($page['goods_name'])?$page['goods_name']:'';
            $s_cate = isset($page['s_cate'])?$page['s_cate']:'';
            $curPage = $page['curPage'];
            $bar_code = isset($page['bar_code'])?$page['bar_code']:'';
            $where = [];
            $where['a.status'] = 1;
            if($goods_name){
                $where['a.title'] = ['like',"%$goods_name%"];
            }
            if($s_cate){
                $where['a.type'] = $s_cate;
            }
            if($bar_code){
                $where['a.bar_code'] = $bar_code;
            }
            $count = count((new MemberGoodsModel())->ajax_get_goods_list($where));
            $item = new ItemModel();
            $f_list = $item->tiemYlian();
            //2.定义分页所需的数据
            $totalItem = $count;   //总记录数(自行定义)
            $pageSize= 10;  //每一页记录数(自行定义)
            $totalPage =ceil($totalItem/$pageSize);  //总页数
            $startItem = ($curPage-1) * $pageSize;//根据页码来决定查询数据的节点
            //3.查询数据
//            dump($startItem.$pageSize);exit;
            $res=(new MemberGoodsModel())->ajax_get_goods_list_limit($where,$startItem,$pageSize);
            //4.放入所有数据
            $arr['totalItem']=$totalItem;
            $arr['pageSize']=$pageSize;
            $arr['totalPage']=$totalPage;
            foreach($res as $lab) {
                $arr['data_content'][] = $lab;
            }
            $arr['f_list'] = $f_list;
            //5.结果返回（此处没有判定是否查询成功）
            $this->result($arr,200,'成功','json');
        }
    }

    //获取会员商品数据
    public function getMemberGoodsPage(){
        if (request()->isAjax()) {
            //1.获取数据（curPage）
            $page=input('get.');
            $title = $page['title'];
            $status = $page['status'];
            $curPage = $page['curPage'];
            $where = [];
            if($title){
                $where['title'] = ['like',"%$title%"];
            }
            if(isset($page['include_level'])){
                $where['include_level'] = $page['include_level'];
            }
            if(isset($status)){
                $where['status'] = $status;
            }
            $count = count(Db::connect(config('ddxx'))->name('member_goods')->field('id')->select());
            //2.定义分页所需的数据
            $totalItem = $count;   //总记录数(自行定义)
            $pageSize= 10;  //每一页记录数(自行定义)
            $totalPage =ceil($totalItem/$pageSize);  //总页数
            $startItem = ($curPage-1) * $pageSize;//根据页码来决定查询数据的节点
            //3.查询数据
//            dump($startItem.$pageSize);exit;
            $res=(new MemberGoodsModel())->ajax_get_member_goods_list_limit($where,$startItem,$pageSize);
//            dump($res);exit;
            //4.放入所有数据
            $arr['totalItem']=$totalItem;
            $arr['pageSize']=$pageSize;
            $arr['totalPage']=$totalPage;
            foreach($res as $lab) {
                $arr['data_content'][] = $lab;
            }
            //5.结果返回（此处没有判定是否查询成功）
            $this->result($arr,'1','成功','json');
        }
    }

    //获取商品数据
    public function getGoods()
    {
        $id = input('post.id');
        $data = (new MemberGoodsModel())->ajax_get_member_goods_list(['id'=>$id]);
        $goodsArr = json_decode($data[0]['goods_info']);
        $arr = [];
        foreach ($goodsArr as $k=>$v){
            $tmp = (new MemberGoodsModel())->ajax_get_goods_list(['b.id'=>$k]);
            $arr['goods_info'][] = $tmp[0];
        }
        if(!$arr){
            return outPut(0,'fail');
        }
        return outPut(1,'success',$arr);
    }

    /**
     * 查看/编辑会员商品详情
     * @return mixed
     */
    public function viewDetail()
    {
        $id = $this->request->param('id');
        $data = (new MemberGoodsModel())->get_member_goods($id);
        $level_lists = (new LevelModel())->get_level_list();
        $item = new ItemModel();
        $f_list = $item->tiemYlian();
        $this->assign('id',$id);
        $this->assign('f_list',$f_list);
        $this->assign('level_lists',$level_lists);
        $this->assign('data',$data);
        return $this->fetch('edit');
    }

}
