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
use app\admin\model\ItemModel;
use app\admin\model\CouponModel;
use app\admin\model\SpecialModel;


/**
 * Class cardController
 * @package app\admin\controller
 * @adminMenuRoot(
 *     'name'   =>'专题管理',
 *     'action' =>'menu_default',
 *     'parent' =>'',
 *     'display'=> true,
 *     'order'  => 10000,
 *     'icon'   =>'cogs',
 *     'remark' =>'专题管理'
 * )
 */
class SpecialController extends AdminBaseController
{
	protected $targets = ["_blank" => "新标签页打开", "_self" => "本窗口打开"];
    
    /**
     * 专题列表
     * @adminMenu(
     *     'name'   => '专题列表',
     *     'parent' => 'menu_default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '专题列表',
     *     'param'  => ''
     * )
     */
    public function special_list()
    {
        $Special = new SpecialModel();
        $special_list = $Special->special_list();
        
        $page = $special_list->render();
        
        //print_r($card_list);die;
        $this->assign("page", $page);
        $this->assign('special_list', $special_list);
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
    删除服务专题
     */
    public function special_delete()
    {
        $id = $this->request->param("id", 0, 'intval');
        
        $Special = new SpecialModel();
        $res = $Special->special_operation($id,'-1');

        if($res!=1){
            $this->error($res);
        }else{
            $this->success("删除成功！", url('special/special_list'));
        }
    }
    /*
    添加服务专题
     */
    public function special_add(){
		
        return $this->fetch();
		
    }
    /*
    服务专题添加操作
     */
    public function special_addPost(){

        if ($this->request->param()) {
            
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
			$data['catid']   = 3;
			$data['status']  = 1;
			$data['stime']   = strtotime($data['stime']);
			$data['etime']   = strtotime($data['etime']);
            //$this->error("hahahhahah");
            $Special = new SpecialModel();
            $res = $Special->special_add($data);

            if ($res==1) {
                $this->success("添加成功！",url('special/special_list'));
            } else {
                $this->error("您没有做任何修改！");
            }
        }
    }
    /*
    服务专题编辑
     */
    public function special_edit(){

        $artid = $this->request->param("id", 0, 'intval');
        
        $Special = new SpecialModel();
        $special = $Special->special_find($artid);
		$special['jtime'] = date("Y-m-d H:i",$special['jtime']);
		$special['stime'] = date("Y-m-d H:i",$special['stime']);
		$special['etime'] = date("Y-m-d H:i",$special['etime']);
        $special['content'] = html_entity_decode($special['content']);

        $this->assign("special", $special);
        return $this->fetch();
    }
    /*
    服务专题编辑操作
     */
    public function special_editPost(){
        
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
            $data['catid']   = 3;
            $data['status']  = 1;
            $data['stime']   = strtotime($data['stime']);
            $data['etime']   = strtotime($data['etime']);
            //print_r($data);die;
            $Special = new SpecialModel();
            $res = $Special->special_edit($data['id'],$data);
            if ($res==1) {
                $this->success("修改成功！",url('special/special_list'));
            } else {
                $this->error("您没有做任何修改！");
            }
        }
    }
    /**
     * 专题列表
     * @adminMenu(
     *     'name'   => '活动专题列表',
     *     'parent' => 'menu_default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '专题列表',
     *     'param'  => ''
     * )
     */
    public function activity_list()
    {
        $Special = new SpecialModel();
        $activity_list = $Special->activity_list();
        
        $page = $activity_list->render();
        
        //print_r($card_list);die;
        $this->assign("page", $page);
        $this->assign('activity_list', $activity_list);
        return $this->fetch();
    }
    /*
     *活动专题添加 
     */
    public function activity_add()
    {
        $Special = new SpecialModel();
        $Item = new ItemModel();
        //$activity_list = $Special->activity_list();
        $itemxuan = $Item->itemxuan();
        $f_list = $Item->tiemYlian();
        $this->assign('f_list',$f_list);
        //$page = $activity_list->render();
        
        //print_r($card_list);die;
        $this->assign("itemxuan", $itemxuan);
        return $this->fetch();
    }
    /*
     *活动商品选择
     */
    public function activity_item()
    {
        $Item = new ItemModel();
        $typexuan = $Item->typexuan();
        $itemxuan = $Item->itemxuan();
        
        /*foreach ($type_list as $key => $value) {
            foreach ($value['children'] as $ke => $val) {
                $type_list[$key]['children'][$ke]['children'] = $Item->type_item($val['id']);                
            }
        }*/
        foreach ($typexuan as $key => $value) {
            $data[] = $value;
        }
        foreach ($itemxuan as $key => $value) {
            $data[] = $value;
        }
        //print_r($data);
        echo json_encode($data);exit;
        
    }
    /*
     *活动专题添加操作
     */
    public function activity_addPost()
    {
        if ($this->request->param()) {

            $data            = $this->request->param();
            dump($data);exit;
            $item_id_str = '';
            foreach ($data['item_id'] as $k=>$v){
                $itemId = Db::connect(config('ddxx'))->name('item_attr')->where(['id'=>$v])->value('item_id');
                $item_id_str.=$itemId.',';
            }
            $item_id_str = substr($item_id_str,0,-1);
            $arr = explode(',',$item_id_str);
            $item_id_str = implode(',',array_unique($arr));
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
            $data['catid']   = 2;
            $data['status']  = 1;
            $data['stime']   = strtotime($data['stime']);
            $data['etime']   = strtotime($data['etime']);
            $data['goods'] = '推荐商品:'.$item_id_str;
            unset($data['item_id']);
            //print_r($data);die;
            //$this->error("hahahhahah");
            $Special = new SpecialModel();
            $res   = $Special->special_add($data);
            $actid = $Special->getactid();
            $data['itemlist'] = $item_id_str;
            $data['id'] = $actid;
            unset($data['goods']);
            unset($data['subtitle']);
            unset($data['catid']);
            $rec = $Special->activity_add($data);

            if ($res==1) {
                $this->success("添加成功！",url('special/activity_list'));
            } else {
                $this->error("您没有做任何修改！");
            }
        }
        
        //print_r($card_list);die;
        //return $this->fetch();
    }
    /*
     *活动专题编辑
     */
    public function activity_edit()
    {
        $artid = $this->request->param("id", 0, 'intval');
        
        $Special = new SpecialModel();
        $Item    = new ItemModel();
        $special = $Special->special_find($artid);
        $special['jtime'] = date("Y-m-d H:i",$special['jtime']);
        $special['stime'] = date("Y-m-d H:i",$special['stime']);
        $special['etime'] = date("Y-m-d H:i",$special['etime']);
        $special['content'] = html_entity_decode($special['content']);
        //print_r($special);die;
        $item_id = explode(':', $special['goods']);
        //print_r($itemid[1]);die;
        $itemid = explode(',',$item_id[1]);
//        print_r($item_id);die;
        $itemxuan = $Item->itemxuan();
//        dump($itemxuan);exit;
        $this->assign("itemid", $itemid);
        $this->assign("itemxuan", $itemxuan);
        $this->assign("special", $special);
        return $this->fetch();
    }
    /*
     *活动专题编辑操作
     */
    public function activity_editPost()
    {
        if ($this->request->param()) {

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
            $data['catid']   = 2;
            $data['status']  = 1;
            $data['stime']   = strtotime($data['stime']);
            $data['etime']   = strtotime($data['etime']);
            $item_id = implode(',', $data['item_id']);
            $data['goods'] = '推荐商品:'.$item_id;
            unset($data['item_id']);
            //print_r($data);die;
            //$this->error("hahahhahah");
            $Special = new SpecialModel();
            $res   = $Special->special_edit($data['id'],$data);
            $data['itemlist'] = $item_id;
            unset($data['goods']);
            unset($data['subtitle']);
            unset($data['catid']);
            $rec = $Special->activity_edit($data['id'],$data);

            if ($res==1) {
                $this->success("保存成功！",url('special/activity_list'));
            } else {
                $this->error("您没有做任何修改！");
            }
        }
        
        //print_r($card_list);die;
        //return $this->fetch();
    }
    /*
    删除活动服务专题
     */
    public function activity_delete()
    {
        $id = $this->request->param("id", 0, 'intval');
        
        $Special = new SpecialModel();
        $res = $Special->special_operation($id,'-1');
        $rec = $Special->activity_operation($id,'-1');

        if($res!=1){
            $this->error($res);
        }else{
            $this->success("删除成功！", url('special/activity_list'));
        }
    }
}
