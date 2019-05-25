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
use app\admin\model\AgreeModel;


/**
 * Class cardController
 * @package app\admin\controller
 * @adminMenuRoot(
 *     'name'   =>'协议文章管理',
 *     'action' =>'menu_default',
 *     'parent' =>'',
 *     'display'=> true,
 *     'order'  => 10000,
 *     'icon'   =>'cogs',
 *     'remark' =>'协议文章管理'
 * )
 */
class AgreeController extends AdminBaseController
{
	protected $targets = ["_blank" => "新标签页打开", "_self" => "本窗口打开"];
    
    /**
     * 协议列表
     * @adminMenu(
     *     'name'   => '协议文章列表',
     *     'parent' => 'menu_default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '协议文章列表',
     *     'param'  => ''
     * )
     */
    public function Agree_list()
    {
        $Agree = new AgreeModel();
        $agree_list = $Agree->agree_list();
        
        $page = $agree_list->render();
        
        //print_r($card_list);die;
        $this->assign("page", $page);
        $this->assign('agree_list', $agree_list);
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
    public function agree_delete()
    {
        $id = $this->request->param("id", 0, 'intval');
        
        $Agree = new AgreeModel();
        $res = $Agree->agree_operation($id,'-1');

        if($res!=1){
            $this->error($res);
        }else{
            $this->success("删除成功！", url('Agree/agree_list'));
        }
    }
    /*
    添加沙龙
     */
    public function agree_add(){
		
        return $this->fetch();
		
    }
    /*
    沙龙添加操作
     */
    public function agree_addPost(){

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
            
            $Agree = new AgreeModel();
            $res = $Agree->agree_add($data);

            if ($res==1) {
                $this->success("添加成功！",url('Agree/agree_list'));
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
            $data['catid']   = 7;
            $data['status']  = 1;
            //$this->error("hahahhahah");
            $Agree = new AgreeModel();
            $res = $Agree->agree_add($data);

            if ($res==1) {
                $this->success("添加成功！",url('Agree/agree_list'));
            } else {
                $this->error("您没有做任何修改！");
            }
    }
    /*
    沙龙编辑
     */
    public function agree_edit(){

        $artid = $this->request->param("id", 0, 'intval');
        
        $Agree = new AgreeModel();
        $agree = $Agree->agree_find($artid);

        $this->assign("agree", $agree);
        return $this->fetch();
    }
    /*
    沙龙编辑操作
     */
    public function agree_editPost(){
        
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
            $Agree = new AgreeModel();
            $res = $Agree->agree_edit($artid,$data);
            if ($res==1) {
                $this->success("修改成功！",url('Agree/agree_list'));
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
            $Agree = new AgreeModel();
            $res = $Agree->agree_edit($data['id'],$data);
            if ($res==1) {
                $this->success("修改成功！",url('Agree/agree_list'));
            } else {
                $this->error("您没有做任何修改！");
            }
        }
    }
}
