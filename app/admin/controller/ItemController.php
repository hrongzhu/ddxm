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
use plugins\qiniu\lib\Qiniu;
use Qiniu\Auth;
use Qiniu\Config;
use Qiniu\Http\Error;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;
use think\Db;
use app\admin\model\AdminMenuModel;
use app\admin\model\ItemModel;
use app\admin\model\CouponModel;
use app\admin\model\LvModel;
use app\admin\controller\UploadController;
use app\admin\common\upload as uploadService;
use cmf\lib\Upload;
use think\Exception;
use think\Session;

class ItemController extends AdminBaseController
{
	protected $targets = ["_blank" => "新标签页打开", "_self" => "本窗口打开"];
    /**
     * 分类列表
     */
    public function type_list()
    {

        $Item = new ItemModel();
        $type_list = $Item->type_list();

        //print_r($type_list);die;
        $this->assign('targets', $this->targets);
        $this->assign('type_list', $type_list);
        return $this->fetch();
    }
    /*
    分类删除
     */
    public function type_delete(){
        $catid = $this->request->param("id", 0, 'intval');


        $Item = new ItemModel();
        $res = $Item->type_delete($catid);

        if($res!=1){
            $this->error($res);
        }else{
            $this->success("删除成功！", url('Item/type_list'));
        }
    }
    /*
    分类编辑
     */
    public function type_edit()
    {
        $catid = $this->request->param("id", 0, 'intval');

        $Item = new ItemModel();
        $typeinfo = $Item->type_find($catid);

        if (!$typeinfo) {
            $this->error("该分类不存在！");
        }

        $this->assign("data", $typeinfo);
        return $this->fetch();
    }
    /*
    分类编辑操作
     */
    public function type_editPost()
    {
        $catid = $this->request->param("id", 0, 'intval');

        if ($this->request->isPost()) {
            $data   = $this->request->param();
            $rec = array_key_exists('mainPic', $data);
            if($rec==true){
                $data['thumb']   = $data['mainPic'][0];
                unset($data['mainPic']);
                unset($data['files']);
            }else{
                unset($data['files']);
            }

            $Item = new ItemModel();
            $res = $Item->type_editPost($catid,$data);

            if ($res==1) {
                $this->success("保存成功！",url('Item/type_list'));
            } else {
                $this->error("您没有做任何修改！");
            }
        }
    }
    /*
    分类添加
     */
    public function type_add(){

        $Item = new ItemModel();
        $type_list = $Item->type_list();

        /*print_r($type_list);die;*/
        $this->assign('targets', $this->targets);
        $this->assign('type_list', $type_list);
        return $this->fetch();
    }
    /*
    分类添加操作
     */
    public function type_addPost(){

        if ($this->request->param()) {
            $data            = $this->request->param();
            $rec = array_key_exists('mainPic', $data);
            if($data['cname']==''){
                $this->error("请添加分类名称");
            }
            if($rec==false){
                $this->error("请上传图片");
                if($data['mainPic']==null){
                    $this->error("请上传图片");
                }
            }

            $data['addtime'] = time();
            $data['thumb']   = $data['mainPic'][0];
            unset($data['mainPic']);
            unset($data['files']);
            $Item = new ItemModel();
            $res = $Item->type_add($data);

            if ($res==1) {
                $this->success("添加成功！",url('Item/type_list'));
            } else {
                $this->error("您没有做任何修改！");
            }
        }
    }
    /*
    分类拉取
    */
    public function typelian()
    {

        $catid = $this->request->param("pid",0,'intval');

        //$catid = 3;
        $Item = new ItemModel;
        $data = $Item->typefind($catid);


        echo json_encode($data,true);
    }
    /**
     * 商品列表搜索
     */
    public function item_list()
    {
        if($this->admin_id == 1){
            $this->assign('show_sale',1);
        }
		$show_del = $this->request->param('show_del',0);//用来确定是否显示隐藏（删除了）的商品
        $goods_name = $this->request->param('search');
        $bar_code = trim($this->request->param('bar_code'));
        $f_cate = $this->request->param('f_cate');
        $s_cate = $this->request->param('s_cate');

        $where = [];
        if ($goods_name) {
          $where['a.title'] = ['like',"%$goods_name%"];
        }
		$this->assign('search',$goods_name);
        $this->assign('f_cate',$f_cate);
		if ($show_del == 0) {
			$where['a.status'] = ['<=',1];
		}
		$this->assign('show_del',$show_del);
        if ($bar_code) {
            $where['a.bar_code'] = $bar_code;
        }
        $this->assign('bar_code',$bar_code);
        if ($s_cate) {
            $where['a.type'] = $s_cate;
        }
        $this->assign('s_cate',$s_cate);
        $Item = new ItemModel();
        //分页参数
        $pageParams = [
			'show_del' => $show_del? $show_del : '',
            'search' => $goods_name? $goods_name : '',
            'bar_code' => $bar_code ? $bar_code : '',
            'f_cate' => $f_cate ? $f_cate : '',
            's_cate' => $s_cate? $s_cate : '',
        ];
        // var_dump($pageParams);die;
        $list = $Item->getOrderListDatas($where)->paginate(12, false, ['query'=>$pageParams]);
//        dump($list);
//        exit;
        //  分页样式
        $this->assign('page', $list->render());
        //  总条数
        $this->assign('totalCount', $list->total());
        //  当前页面
        $this->assign('current', $list->currentPage());
        //  每页显示数量
        $this->assign('listRows', $list->listRows());
        $f_list = $Item->tiemYlian();
        $this->assign('f_list', $f_list);
        $this->assign("iteminfo", $list);
        return view('item_list');
    }

    /*
    商品分类下拉2
    */
    public function itemTlians()
    {
        $catid = $this->request->param("pid",0,'intval');

        //$catid = 3;
        $Item = new ItemModel;
        $data = $Item->itemTlian($catid);
        return outPut(200,'获取成功',$data);
    }

    /**
     * 商品条形码修改
     */
    public function attr_bar()
    {
        $data    = $this->request->param();
        unset($data['id']);
        $attr_id = $this->request->param('id');

        if($data['bar_code']==null){
            echo 2;exit;
        }
        $Item = new ItemModel();
        $res = $Item->bar_find($data['bar_code']);
        if($res){
            echo 3;exit;
        }
        //条形码验证
        /*$bar_code = str_split($data['bar_code']);

        if(strlen($data['bar_code'])!=13){
            echo 2;exit;
        }
        //$num  = 0;
        $onum = 0;
        $jnum = 0;
        foreach ($bar_code as $key => $value) {
            if($key<12){
                if(($key+1)%2==0){
                    $onum = $onum+$bar_code[$key];
                }else{
                    $jnum = $jnum+$bar_code[$key];
                }
            }
        }
        $nums = $onum*3+$jnum;

        $snum = str_split($nums);
        //print_r($snum);
        foreach ($snum as $key => $value) {
            $snum = $value;
        }
        $xiaoyan = 10 - $snum;
        //echo $xiaoyan.'      '.$bar_code['12'];die;
        if(($xiaoyan==$bar_code['12']) or ($xiaoyan==10 and $bar_code['12'])){
            $Item = new ItemModel();
            $res = $Item->attredit($data,$attr_id);

            if($res){
                echo 1;exit;
            }else{
                echo 0;exit;
            }
        }else{
            echo 2;exit;
        }*/

        $res = $Item->attredit($data,$attr_id);

        if($res){
            echo 1;exit;
        }else{
            echo 0;exit;
        }
    }
    /**
     * 京东商品id添加
     */
    public function attr_jd()
    {
        $data    = $this->request->param();
        unset($data['id']);
        $attr_id = $this->request->param('id');

        preg_match_all('/\d+/',$data['jing_code'],$arr);
        $data['jing_code'] = $arr[0][0];

        if($data['jing_code']==null){
            echo 2;exit;
        }

        $Item = new ItemModel();
        $res = $Item->jing_find($data['jing_code']);
        if($res){
            echo 3;exit;
        }

        $res = $Item->attredit($data,$attr_id);

        if($res){
            echo 1;exit;
        }else{
            echo 0;exit;
        }
    }
    /**
     * 文件上传
     */
    public function item_addupload()
    {

        $uploader = new Upload();

        $result = $uploader->upload();

        if ($result === false) {
            $this->error($uploader->getError());
        } else {
            $data['success'] = 1;
            $data['msg']     = 'http://'.$_SERVER['HTTP_HOST'].$result['url'];
            $data['msgurl']  = $result['filepath'];
            echo json_encode($data);
            //$this->success("上传成功!", '', $result);
        }
    }

    /*
    文件上传
    */
    public function uploadPic()
    {
        $result = (new uploadService())->uploadImg();

        $result = trim($result,'"');
        $str = stripslashes($result);

        if ($str) {
            $data['success'] = 1;
            $data['msg']     = config('file_server_url').$str;
            $data['msgurl']  = $str;

            echo json_encode($data);exit;
        }
        return outPut(301,'上传失败');
    }
    /*
    商品添加
     */
    public function item_add(){

        $Item = new ItemModel();
        $type_list = $Item->type_list();
        $part_list = $Item->part_list();
        $Ylian_list = $Item->tiemYlian();
        //print_r($part_list);die;
        //商品券列表
        $Coupon = new CouponModel();
        $coupon_list = $Coupon->coupon_list();

        //税率模板列表
        $Lv      = new LvModel();
        $lv_list = $Lv->lists();
        //print_r($coupon_list);die;
        $this->assign("lv_list", $lv_list);
        $this->assign("coupon_list", $coupon_list);
        $this->assign("type_list", $type_list);
        $this->assign("part_list", $part_list);
        $this->assign("Ylian_list", $Ylian_list);
        return $this->fetch();

    }
    /*
    商品添加动作
     */
    public function item_addPost(){
        $data = input('post.');
        $pics = '';
        if(in_array(1,$data['item_type'])&&!isset($data['pics'])){
            return outPut(0,'fail','线上商品必须添加商品图片！');
        }
        if(count($data['item_type'])==1){
            $data['item_type'] = $data['item_type'][0];
        }
        if(count($data['item_type'])!=1&&in_array(1,$data['item_type'])&&in_array(2,$data['item_type'])){
            $data['item_type'] = 3;
        }
        if(isset($data['pics'])){
            foreach ($data['pics'] as $k=>$v){
                if ($k == 0) {
                    $pics .= $v;
                }else{
                    $pics .= ','.$v;
                }
            }
        }
        if (isset($data['id']) && !empty($data['id'])) {
            $contents = htmlspecialchars_decode($data['content']);
        }else{
            //查询当前商品的头尾详情图
            $area_imgs = Db::connect(config('ddxx'))->name('item_cate')->where(['id'=>$data['area']])->field('left_pic,right_pic')->find();
            $top_pic = empty($area_imgs['left_pic'])?'':config('file_server_url').$area_imgs['left_pic'];
            $foot_pic = empty($area_imgs['right_pic'])?'':config('file_server_url').$area_imgs['right_pic'];
            $contents = htmlspecialchars_decode($data['content']);
            if ($top_pic) {
                $top_html = '<p><img src="'.$top_pic.'" style="" title="'.$area_imgs['left_pic'].'"/></p>';
                $contents = $top_html.$contents;
            }
            if ($foot_pic) {
                $foot_html = '<p><img src="'.$foot_pic.'" style="" title="'.$area_imgs['right_pic'].'"/></p>';
                $contents = $contents.$foot_html;
            }
        }

        // var_dump($contents);die;
        $datas = [
            'title'=>trim($data['title']),
            'item_type'=>$data['item_type'],//商品库，1线上，2门店
            'type_id'=>$data['area'],//专区ID
            'type'=>$data['cat_id'],//分类ID
            'lvid'=>$data['lv_id'],//税率模板
            'status'=>$data['status'],//商品上下架状态
            'price'=>$data['price'],
            'cg_standard_price'=>$data['cg_price'],//采购价格
            'md_standard_price'=>$data['md_price'],//门店价格
            'is_price_control'=>$data['is_priceContorl'],//是否控价
            'bar_code'=>trim($data['code']),//商品条形码
            'stock_alert'=>trim($data['stock_alert']),
            'content'=>$contents,
            'pics'=>$pics,
            'time'=>time()
        ];
        $db = Db::connect(\config('ddxx'));
        if($data['id']==''){
            try{
                if(!empty($datas['bar_code'])){
                    //根据条形码检查商品是否已存在
                    $isset = $db->name('item')->where(['bar_code'=>$datas['bar_code']])->field('id')->find();
                    if($isset){
                        return outPut(0,'fail','已存在同名商品');
                        exit;
                    }
                }
                $result = $db->name('item')->insertGetId($datas);
                if ($result) {
                    $msg = '添加名称为【'.trim($data['title']).'】的商品,商品id：'.$result;
                    LogsController::actionLogRecord($msg);
                    return outPut(1,'success');
                }
            }catch (\Exception $e){
                return outPut(0,'fail',$e->getMessage());
            }
        }else{
            //条码改变的话查询是否存在相同条码商品
            $isset = $db->name('item')->where(['id'=>$data['id']])->field('bar_code')->find();
            if($isset['bar_code']!==$datas['bar_code']){
                $isset2 = $db->name('item')->where(['bar_code'=>$datas['bar_code']])->field('id')->find();
                if($isset2){
                    return outPut(0,'fail','存在相同条形码的商品！');
                    exit;
                }
            }
            try{
                $result = $db->name('item')->where(['id'=>$data['id']])->update($datas);
                if ($result) {
                    $msg = '修改名称为【'.trim($data['title']).'】的商品,商品id：'.$data['id'];
                    LogsController::actionLogRecord($msg);
                    return outPut(1,'success');
                }
            }catch (\Exception $e){
                return outPut(0,'fail',$e->getMessage());
            }
        }

    }

    public function uploadToQiniu()
    {
        require_once VENDOR_PATH.'/qiniu/php-sdk/autoload.php';
        if(request()->isPost()){
            $file = (request()->file())['file'];
            // 要上传图片的本地路径
            $filePath = $file->getPathname();
            // 上传到七牛后保存的文件名
            $key =substr(md5($file->getRealPath()) , 0, 5). date('YmdHis') . rand(0, 9999);
            // 需要填写你的 Access Key 和 Secret Key
            $accessKey = config('qiniu.accesskey');
            $secretKey = config('qiniu.secretkey');
            // 构建鉴权对象
            $auth = new Auth($accessKey, $secretKey);
            // 要上传的空间
            $bucket = config('qiniu.bucket');
            $domain = config('qiniu.domain');
            $token = $auth->uploadToken($bucket);
            // 初始化 UploadManager 对象并进行文件的上传
            $uploadMgr = new UploadManager();
            // 调用 UploadManager 的 putFile 方法进行文件的上传
            list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
            if ($err !== null) {
                return ["err"=>1,"msg"=>$err,"data"=>""];
            } else {
                //返回图片的完整URL
                return json(["err"=>0,"msg"=>"上传完成","data"=>$ret]);
            }
        }
    }

    //从七牛删除图片
    public function delelteImage()
    {
        $key = input('post.key');
        $id = input('post.id');
        if($key){
            $accessKey = config('qiniu.accesskey');
            $secretKey = config('qiniu.secretkey');
            $bucket = config('qiniu.bucket');
            $auth = new Auth($accessKey,$secretKey);
            $config = new Config();
            $bucketManager = new BucketManager($auth,$config);
            //curl查询云储存的图片是否存在
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL,"http://picture.ddxm661.com/".$key);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch,CURLOPT_HEADER,0);
            $output = curl_exec($ch);
            if($output === FALSE ){
                echo "CURL Error:".curl_error($ch);
            }
            curl_close($ch);
            //七牛云储存中图片已不存在或本次删除成功后都判定为删除成功
            $result = $bucketManager->delete($bucket,$key);
            if ($result==null||$output=='{"error":"Document not found"}') {
                return outPut(1,'success');
            }else{
                return outPut(0,'fail');
            }
        }
    }

    /*
    商品分类下拉
    */
    public function itemYlian()
    {
        $catid = $this->request->param("pid",0,'intval');

        //$catid = 3;
        $Item = new ItemModel;
        $data = $Item->itemYlian($catid);


        echo json_encode($data,true);
    }
    /*
    商品分类下拉
    */
    public function itemTlian()
    {
        $catid = $this->request->param("pid",0,'intval');

        //$catid = 3;
        $Item = new ItemModel;
        $data = $Item->itemTlian($catid);


        echo json_encode($data,true);
    }
    /*
    商品编辑
    */
    public function item_edit()
    {
        $item_id = $this->request->param("id", 0, 'intval');

        $Item = new ItemModel;

        $item = $Item->itemfind($item_id);
        $Ylian_list = $Item->tiemYlian();
        $item['pics'] = array_filter(explode(',',$item['pics']));
        $item_pid = Db::connect(\config('ddxx'))->name('item_category')->where(['id'=>$item['type']])->field('pid')->find();
        $item['pid'] = $item_pid['pid'];
        $Item = new ItemModel();
        $type_list = $Item->type_list();
        $second_type_list = Db::connect(\config('ddxx'))->name('item_category')->where(['pid'=>$item_pid['pid'],'status'=>['neq',-1]])->field('id,cname')->select();
        $part_list = $Item->part_list();
        //print_r($Ylian_list);die;
        //税率模板列表
        $Lv      = new LvModel();
        $lv_list = $Lv->lists();
        //print_r($attr_list);die;
        $this->assign("Ylian_list", $Ylian_list);
        $this->assign("lv_list", $lv_list);
        $this->assign("item", $item);
        $this->assign("type_list", $type_list);
        $this->assign("second_type_list", $second_type_list);
        $this->assign("part_list", $part_list);

        return $this->fetch();
    }
    /*
    商品编辑操作
    */
    public function item_editPost()
    {
        $data = input('post.');
        $item_type = '';
        $pics = '';
        foreach ($data['item_type'] as $v){
            $item_type.=$v.',';
        }
        foreach ($data['pics'] as $v){
            $pics.=$v.',';
        }
//        dump($data);exit;
        $data = [
            'title'=>$data['title'],
            'item_type'=>$item_type,//商品库，1线上，2门店
            'type_id'=>$data['area'],//专区ID
            'type'=>$data['cat_id'],//分类ID
            'lvid'=>$data['lv_id'],//税率模板
            'status'=>$data['status'],//商品上下架状态
            'price'=>$data['price'],
            'cg_standard_price'=>$data['cg_price'],//采购价格
            'md_standard_price'=>$data['md_price'],//门店价格
            'is_price_control'=>$data['is_priceContorl'],//是否控价
            'bar_code'=>$data['code'],//商品条形码
            'content'=>htmlspecialchars_decode($data['content']),
            'pics'=>$pics,
            'time'=>time()
        ];
        $db = Db::connect(\config('ddxx'));
        try{
            $result = $db->name('item')->insert($data);
            if ($result) {
                return outPut(1,'success');
            }
        }catch (\Exception $e){
            return outPut(0,'fail',$e->getMessage());
        }
    }
    /*
    上架操作
     */
    public function item_on(){
        $id = $this->request->param("id", 0, 'intval');

        $Item = new ItemModel();
        $res = $Item->item_operation($id,'1');

        if($res!=1){
            $this->error($res);
        }else{
            $this->success("上架成功！", url('Item/item_list'));
        }
    }
    /*
    下架操作
     */
    public function item_off(){
        $id = $this->request->param("id", 0, 'intval');

        $Item = new ItemModel();
        $res = $Item->item_operation($id,'0');

        if($res!=1){
            $this->error($res);
        }else{
            $this->success("下架成功！", url('Item/item_list'));
        }
    }
    /*
    删除商品操作
     */
    public function item_delete()
    {
        $id = $this->request->param("id", 0, 'intval');
        $db = Db::connect(\config('ddxx'));
        // $isset = $db->name('purchase_price')->where(['item_id'=>$id,'stock'=>["neq",0]])->count();
        // if ($isset!=0) {
        //     $this->error("进销存系统中存在该商品且库存不为0！");
        // }
        $res = $db->name('item')->where(['id'=>$id])->update(['status'=>3]);
        if(!$res){
            $this->error('删除失败');
        }else{
            $this->success("删除成功！", url('Item/item_list'));
        }
    }
     /*
    商品属性上架操作
     */
    public function attr_on(){
        $id = $this->request->param("id", 0, 'intval');

        $Item = new ItemModel();
        $res = $Item->attr_operation($id,'1');

        if($res!=1){
            return outPut(301,'上架操作失败');
        }else{
            return outPut(200,'上架操作成功');
        }
    }
    /*
    商品属性下架操作
     */
    public function attr_off(){
        $id = $this->request->param("id", 0, 'intval');

        $Item = new ItemModel();
        $res = $Item->attr_operation($id,'0');

        if($res!=1){
            return outPut(301,'下架操作失败');
        }else{
            return outPut(200,'下架操作成功');
        }
    }
    /*
    主图入库
    */
    public function mainpic($mainpic)
    {
        $mainpic = implode('","',$mainpic);

        $mainpic = '["'.$mainpic.'"]';

        return $mainpic;
    }

    /**
     * 商品专区
     * @adminMenu(
     *     'name'   => '商品专区',
     *     'parent' => 'menuDefault',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 3,
     *     'icon'   => '',
     *     'remark' => '商品专区',
     *     'param'  => ''
     * )
     */
    public function item_cate()
    {
        $list = model('Item')->item_cate();
        $this->assign('info',$list);
        return $this->fetch('item_cate');
    }

    //添加员工
    public function itemCateAdd()
    {
        $file_host = config('file_server_url');
        $this->assign('host',$file_host);
        return view('item_cate_update');
    }

    //修改专区信息
    public function itemCateEdit()
    {
        $cate_id = $this->request->param('id');
        $info = model('Item')->item_cate($cate_id);
        $file_host = config('file_server_url');
        $this->assign('host',$file_host);
        $this->assign('info',$info);
        return view('item_cate_update');
    }

    //更新专区信息
    public function itemCateUpdate()
    {
        $id = $this->request->param('id','');
        $data = $this->request->param();
        $res = [];
        if ($id){
            //更新
            unset($data['id']);//去掉id
            $res = model('Item')->itemCateUpdate($data, $id);
            if ($res){
               $this->success('更新成功',url("item/item_cate"));
            }
            $this->error('更新失败');
        }
        //新增
        $res = model('Item')->itemCateUpdate($data, $id);
        if ($res){
            $this->success('添加成功',url("item/item_cate"));
        }
        $this->error('添加失败');
    }

    //查看商品销量
    public function showSale()
    {
        $data = $this->request->param();
        $id = $data['id'];
        $sum = model('order.OrderGoods')->where('ch_attr_id','=',$id)->field('sum(num) as nums')->find();
        $nums = 0;
        if ($sum && $sum->nums) {
            $nums = $sum->nums;
        }
        return outPut(200,'获取成功',$nums);

    }
}
