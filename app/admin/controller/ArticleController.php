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
use app\admin\model\ArticleModel;


/**
 * Class cardController
 * @package app\admin\controller
 * @adminMenuRoot(
 *     'name'   =>'沙龙管理',
 *     'action' =>'menu_default',
 *     'parent' =>'',
 *     'display'=> true,
 *     'order'  => 10000,
 *     'icon'   =>'cogs',
 *     'remark' =>'会员卡管理'
 * )
 */
class ArticleController extends AdminBaseController
{
	protected $targets = ["_blank" => "新标签页打开", "_self" => "本窗口打开"];
    
    /**
     * 沙龙列表
     * @adminMenu(
     *     'name'   => '沙龙列表',
     *     'parent' => 'menu_default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '沙龙列表',
     *     'param'  => ''
     * )
     */
    public function Article_list()
    {
        $Article = new ArticleModel();
        $article_list = $Article->article_list();
        
        $page = $article_list->render();
        
        //print_r($card_list);die;
        $this->assign("page", $page);
        $this->assign('article_list', $article_list);
        return $this->fetch();
    }
    /*
    上架操作
     */
    public function card_on(){
        $id = $this->request->param("id", 0, 'intval');
        
        $Card = new CardModel();
        $res = $Card->card_operation($id,'1');

        if($res!=1){
            $this->error($res);
        }else{
            $this->success("上架成功！", url('Card/card_list'));
        }
    }
    /*
    下架操作
     */
    public function card_off(){
        $id = $this->request->param("id", 0, 'intval');
        
        $Card = new CardModel();
        $res = $Card->card_operation($id,'0');

        if($res!=1){
            $this->error($res);
        }else{
            $this->success("下架成功！", url('Card/card_list'));
        }
    }
    /*
    删除沙龙操作
     */
    public function article_delete()
    {
        $id = $this->request->param("id", 0, 'intval');
        
        $Article = new ArticleModel();
        $res = $Article->article_operation($id,'-1');

        if($res!=1){
            $this->error($res);
        }else{
            $this->success("删除成功！", url('Article/article_list'));
        }
    }
    /*
    添加沙龙
     */
    public function article_add(){
		
        return $this->fetch();
		
    }
    /*
    沙龙添加操作
     */
    public function article_addPost(){

        /*if ($this->request->param()) {

            $data            = $this->request->param();
            $data['thumb']   = $data['post']['image'];
            $data['addtime'] = time();
			$data['catid']   = 1;
			$data['status']  = 1;
			$data['stime']   = strtotime($data['stime']);
			$data['etime']   = strtotime($data['etime']);
			$data['jtime']   = strtotime($data['jtime']);
			$data['content'] = html_entity_decode($data['content']);
            unset($data['post']);
            
            $Article = new ArticleModel();
            $res = $Article->article_add($data);

            if ($res==1) {
                $this->success("添加成功！",url('Article/article_list'));
            } else {
                $this->error("您没有做任何修改！");
            }
        }*/
        $data            = $this->request->param();

            $rec = array_key_exists('mainPic', $data);
            if($rec==true){
                $data['thumb']   = $data['mainPic'][0];
                unset($data['mainPic']);
                unset($data['files']);   
            }else{
                unset($data['files']);
            }
            $rec = array_key_exists('content', $data);
            if($rec==true){
                $data['content'] = html_entity_decode($data['content']);
            }
            $data['addtime'] = time();
            $data['catid']   = 1;
            $data['status']  = 1;
            $data['jtime']   = strtotime($data['jtime']);
            $data['stime']   = strtotime($data['stime']);
            $data['etime']   = strtotime($data['etime']);
            //$this->error("hahahhahah");
            $Article = new ArticleModel();
            $res = $Article->article_add($data);

            if ($res==1) {
                $this->success("添加成功！",url('Article/article_list'));
            } else {
                $this->error("您没有做任何修改！");
            }
    }
    /*
    沙龙编辑
     */
    public function article_edit(){

        $artid = $this->request->param("id", 0, 'intval');
        
        $Article = new ArticleModel();
        $article = $Article->article_find($artid);
		$article['jtime'] = date("Y-m-d H:i",$article['jtime']);
		$article['stime'] = date("Y-m-d H:i",$article['stime']);
		$article['etime'] = date("Y-m-d H:i",$article['etime']);

        $this->assign("article", $article);
        return $this->fetch();
    }
    /*
    沙龙编辑操作
     */
    public function article_editPost(){
        
        /*if ($this->request->param()) {
            $data   = $this->request->param();
            //print_r($data);die;
            $artid = $data['id'];
			$data['stime']   = strtotime($data['stime']);
			$data['etime']   = strtotime($data['etime']);
			$data['jtime']   = strtotime($data['jtime']);
            $data['thumb']   = $data['post']['image'];
            unset($data['post']);
            if($data['thumb']==''){
                unset($data['thumb']);
            }
            //print_r($data);die;
            $Article = new ArticleModel();
            $res = $Article->article_edit($artid,$data);
            if ($res==1) {
                $this->success("修改成功！",url('Article/article_list'));
            } else {
                $this->error("您没有做任何修改！");
            }
        }*/
        if ($this->request->param()) {
            $data   = $this->request->param();

            $rec = array_key_exists('mainPic', $data);
            if($rec==true){
                $data['thumb']   = $data['mainPic'][0];
                unset($data['mainPic']);
                unset($data['files']);
            }else{
                unset($data['files']);
            }
            $rec = array_key_exists('content', $data);
            if($rec==true){
                $data['content'] = html_entity_decode($data['content']);
            }
            //print_r($data);die;
            $data['addtime'] = time();
            $data['catid']   = 1;
            $data['status']  = 1;
            $data['jtime']   = strtotime($data['jtime']);
            $data['stime']   = strtotime($data['stime']);
            $data['etime']   = strtotime($data['etime']);
            //print_r($data);die;
            $Article = new ArticleModel();
            $res = $Article->article_edit($data['id'],$data);
            if ($res==1) {
                $this->success("修改成功！",url('Article/article_list'));
            } else {
                $this->error("您没有做任何修改！");
            }
        }
    }
}
