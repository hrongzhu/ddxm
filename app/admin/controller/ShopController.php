<?php
/**
 * Author: chenjing
 * Date: 2018/1/11
 * Description:
 */

namespace app\admin\controller;

use app\admin\common\Logs;
use app\admin\model\AdminUserModel;
use app\admin\model\FranchiseeModel;
use app\admin\model\LevelModel;
use app\admin\model\order\OrderModel;
use app\admin\model\ServiceModel;
use app\admin\model\shop\ShopModel;
use app\admin\common\upload as uploadService;
use app\admin\model\shop\WorkerModel;
use app\admin\model\shop\WorkTimeModel;
use think\Db;
use think\Request;
use think\Validate;
use think\View;
use Endroid\QrCode\QrCode;
use think\Session;

/**
 * Class ShopController
 * @package app\admin\controller
 * @adminMenuRoot(
 *     'name'   =>'门店管理',
 *     'action' =>'menuDefault',
 *     'parent' =>'',
 *     'display'=> true,
 *     'order'  => 10000,
 *     'icon'   =>'cogs',
 *     'remark' =>'门店管理'
 * )
 */
class ShopController extends BaseController
{

    /**
     * 门店列表
     * @adminMenu(
     *     'name'   => '门店列表',
     *     'parent' => 'menuDefault',
     *     'display'=> true,
     *	   'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '门店列表',
     *     'param'  => ''
     * )
     */
    public function shopList()
    {
    	$shop_id = Session::get('SHOP_ID');
        $except_role_id = [1,6];//不限制的门店管理员角色
        //如果登录的是加盟商呢??  此时只考虑独立门店(就是一个人一个店那种)
        //获取店铺信息(回去根据当前登录的用户来筛选店铺)
        $admin_id = $this->admin_id;
        $role_id = Db::table('ddxm_role_user')->where('user_id','=',$admin_id)->value('role_id');
        $where = [];
        if (in_array($role_id,$except_role_id) || $admin_id == 1) {
            $where = [];
        }else{
            $where['id'] = ['in',$shop_id];//加盟商的情况
        }
        $shopList = (new ShopModel())->shopList($where)->paginate(10);
        // 获取分页显示
        $page = $shopList->render();
        // 模板变量赋值
        $this->assign('page', $page);
        $this->assign('shopList',$shopList);
        return view('shop_list');
    }

    //添加门店
    public function addOrUpdate()
    {
        $id= $this->request->param('shop_id','');
        $info = [];
        if ($id){
            $info = (new ShopModel())->shopInfo($id);
            $info->mainPic = empty($info->mainPic)?'':json_decode($info->mainPic);
        }
        if(empty($info)){
            $maxid = (new ShopModel())->maxId();
            $shop_code='A'.str_pad($maxid +1,5,'0',STR_PAD_LEFT );
            $info['code'] = $shop_code;
        }
        $group = (new FranchiseeModel())->field('id,name')->select();
        $host = config('file_server_url');
        // dump($info);die;
        $this->assign('host',$host);
        $this->assign('group',$group);
        $this->assign('info',$info);
        return view('edit_detail');
    }

    //门店服务及价格设置
    public function servicePriceSet()
    {
        $id = $this->request->param('id');
        if(!$id){
            return outPut(2,'门店ID参数缺失，刷新后重试！');
        }
        $info = (new ShopModel())->shopInfo($id);
        $service_level_price_lists = $info->service_level_price;
        $online_service_book = $info->online_service_book;
        //添加门店时列出所有会员等级和参考价格供选择
        $level_lists = (new LevelModel())->get_level_list();
        $lists = (new ShopModel())->get_standard_price()->select();
        $level_old = 0;
        foreach ($service_level_price_lists as $v){
            $level_old = count($v);
            continue;
        }
        if (count($level_lists)!=$level_old) {
            $different = count($level_lists)-$level_old;
            $this->assign('different',$different);
        }
        $this->assign('shop_id',$id);
        $this->assign('service_level_price_lists',$service_level_price_lists);
        $this->assign('online_service_book',$online_service_book);
        $this->assign('level_lists',$level_lists);
        $this->assign('lists',$lists);
        return view('service_price_set');
    }

    //保存门店服务及价格
    public function saveServicePrice()
    {
        $id = input('post.id');
        $service_price = input('post.service_price');
        $book_status = input('post.book_status');
        if(!$id||!$service_price){
            return outPut(2,'参数丢失，请刷新页面后重试!');
        }
        $res = (new ShopModel())->allowField(true)->save(['service_level_price'=>htmlspecialchars_decode($service_price),'online_service_book'=>htmlspecialchars_decode($book_status)],['id'=>$id]);
        if(!$res){
            return outPut(0,'保存失败!');
        }
        return outPut(1,'保存成功');
    }

    //更新门店信息
    public function shopUpdate()
    {
        $id = $this->request->param('id','');
        $data = $this->request->param();
        //验证数据
        $rule = [
            'name|code|mobile|telephone|detail_address|location_address'=>'require'
        ];
        $msg = [
            'name.require'=>'必须输入店铺名称',
            'code.require'=>'缺少店铺码',
            'mobile.require'=>'必须输入电话号码',
            'telephone.require'=>'必须输入手机号码',
            'detail_address'=>'必须输入详细地址',
            'location_address'=>'必须选择定位地址'
        ];
        $validate = new Validate($rule,$msg);
        $result = $validate->check($data);
        if(!$result){
            $this->error($validate->getError());
        }
        $data['mainPic'] = isset($data['mainPic'])?$data['mainPic']:'';
        $data['mainPic'] = json_encode($data['mainPic'],JSON_UNESCAPED_UNICODE);
        $data['addtime'] = time();
        if ($id){
            //更新
            unset($data['id']);//去掉id
            $res = (new ShopModel())->updateShop($data,$id);
            if ($res){
                LogsController::actionLogRecord('更新门店信息,门店:'.$data['name'].',id:'.$id);
                $this->success('更新成功','shop/shoplist');
            }
            $this->error('更新失败');
        }
        //新增门店时添加默认服务
        $data['service_level_price'] = '{"1":{"1":"139","2":"88","3":"78","4":"68","5":"63","6":"58","7":"48","11":"38","12":"28"},"2":{"1":"188","2":"98","3":"88","4":"78","5":"73","6":"68","7":"58","11":"48","12":"38"}}';
        $data['level_standard'] = '{"1":"0","2":"200","3":"2500","4":"5000","5":"7500","6":"10000"}';
        $res = (new ShopModel())->updateShop($data);
        if ($res){
            LogsController::actionLogRecord('添加门店:'.$data['name']);
            $this->success('新增成功','shop/shoplist');
        }
        $this->error('新增失败');
    }

    //设置配送范围
    public function setDeliveryArea()
    {
        if ($_POST) {
            $data = $this->request->param();
            $delivery_area = json_encode($data['location_list']);
            // $res = isPointInPolygon1($data['location_list'], ["106.54843","29.64171"]);
            $datas['delivery_area'] = $delivery_area;
            $datas['id'] = $data['shop_id'];
            $res = (new ShopModel())->allowField(true)->isUpdate(true)->save($datas);
            if ($res) {
                return outPut(200,'保存成功');
            }
            return outPut(301,'保存失败');
        }
        $id = $this->request->param('id','');
        $json_location = (new ShopModel())->where(['id'=>$id])->value('delivery_area');
        if (empty($json_location)) {
            $json_location = '{}';
        }
        $this->assign('shop_id',$id);
        $this->assign('location_list',$json_location);
        return view('pick_address');
    }

    //删除门店
    public function del()
    {
        $id= $this->request->param('shop_id','');
        if ($id==18) {
            return outPut(301,'总店不能删除');
        }
        if (empty($id)){
            return outPut(301,'参数错误');
        }
        $res = (new ShopModel())->delShop($id);
        if ($res){
            return outPut(200,'删除成功');
        }
        return outPut(301,'删除失败');
    }

    //图片上传
    public function uploadPic()
    {
        $result = (new uploadService())->uploadImg();
        if ($result) {
            return outPut(200,'上传成功',['path' =>$result]);
        }
        return outPut(301,'上传失败');
    }

    //生成门店二维码
    public function createQrcode()
    {
        $data = $this->request->param();
        $shop_id = $data['shop_id'];
        $data = "http://dd.ddxm661.com/dist/#/register?shop_id=".$shop_id."&redirect=/home/mall";
        $random = 'shop_'.$shop_id.'_qrcode_'.time();
        $file_name = './qrcode/'.$random.'.png';
        $qrCode = new QRcode($data);
        header('Content-Type: '.$qrCode->getContentType());
        $qrCode->writeFile($file_name);
        $save_name = 'qrcode/'.$random.'.png';
        $res = (new ShopModel())->updateShop(['qrcode'=>$save_name],$shop_id);
        if ($res){
            return outPut(200,'生成成功');
        }
        return outPut(301,'生成错误');

    }

    /**
     * 服务人员列表
     * @adminMenu(
     *     'name'   => '服务人员列表',
     *     'parent' => 'menuDefault',
     *     'display'=> true,
     *	   'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '服务人员列表',
     *     'param'  => ''
     * )
     */
    public function workerList()
    {

        $where = [];
        //查询shop_id,shop_code  限制默认显示的内容d
        $shop_id = Session::get('SHOP_ID');
        $except_role_id = [1,6];//不限制的门店管理员角色
        //获取店铺信息(回去根据当前登录的用户来筛选店铺)
        $admin_id = $this->admin_id;
        $role_id = Db::table('ddxm_role_user')->where('user_id','=',$admin_id)->value('role_id');

        if ($shop_id && $admin_id != 1){
            if (in_array($role_id,$except_role_id)) {
              $shopDatas = ShopModel::all(function($query){
                    $query->field('id,name,code');
                });
            }else{
                $w['id'] = ['in',$shop_id];
                $shopDatas = OrderModel::getShopList($w,1);
            }
        }else{
            $shopDatas = ShopModel::all(function($query){
                $query->field('id,name,code');
            });
        }
        $this->assign('shopDatas',$shopDatas);
        //服务列表
        $shop_service = (new ShopModel())->where(['id'=>$shop_id])->value('service_level_price');
        $jobs = array_keys(json_decode($shop_service,true));
        $shop_server_list = (new ServiceModel())->where(['s_id'=>['in',$jobs]])->field('id,sname')->select();
        $shop_server_list = !empty($shop_server_list)?collection($shop_server_list)->toArray():[];
        $this->assign('shop_server_list',$shop_server_list);
        /**搜索条件**/
        $shopId = $this->request->param('shop_id','');
        $type = $this->request->param('type',1000);
        $keyword = $this->request->param('keyword','');
        if ($shopId) {
            $where['a.sid'] = $shopId;
            $this->assign('shop_id',$shopId);
        }elseif(!empty($shop_id) && $this->admin_id != 1&&!in_array($role_id,$except_role_id)){
            $where['a.sid'] = ['in',$shop_id];
            $this->assign('shop_id','');
        }else{
            $this->assign('shop_id','');
        }

        if ($type != 1000) {
            $o_where = "a.type REGEXP '^$type$|^$type,|,$type$|,$type,'";
        }else{
            $o_where = '';
        }
        $this->assign('type',$type);
        if ($keyword) {
            $where['a.name|a.mobile'] = ['like',"%$keyword%"];
        }
        $this->assign('keyword',$keyword);
        // 每页显示10条数据
//        dump($where);exit;
        $workerModel = new WorkerModel();
        $list = $workerModel->workerList($where,$o_where)->paginate(20);
        // 获取分页显示
        $page = $list->render();
        // 模板变量赋值
        $this->assign('list', $list);
        $this->assign('page', $page);
        return view('workerlist');
    }

    //添加员工
    public function workerAdd()
    {
        //门店信息
        $shoplist = (new ShopModel())->shopList()->select();
        $this->assign('shopList',$shoplist);
        //加盟商信息
        $group = (new FranchiseeModel())->field('id,name')->select();
        $this->assign('group',$group);
        $workId = date("Ymd").rand(111,999);
        $isset = Db::connect(config('ddxx'))->name('worker')->where(['workid'=>$workId])->find();
        if ($isset) {
            $workId = date("Ymd").rand(111,999);
        }
        $this->assign('workId',$workId);
        $host = config('file_server_url');
        $this->assign('host',$host);
        $info = '';
        $this->assign('info',$info);
        return view('worker_update');
    }

    //修改员工信息
    public function workerEdit($w_id)
    {
        //门店信息
        $shoplist = (new ShopModel())->shopList()->select();
        $this->assign('shopList',$shoplist);
        //加盟商信息
        $group = (new FranchiseeModel())->field('id,name')->select();
        $this->assign('group',$group);
        $info = (new WorkerModel())->workerInfo($w_id);
        $shop_service = (new ShopModel())->where(['id'=>$info['sid']])->value('service_level_price');
        $jobs = array_keys(json_decode($shop_service,true));
        $shop_server_list = (new ServiceModel())->where(['s_id'=>['in',$jobs]])->field('id,sname')->select();
        $shop_server_list = !empty($shop_server_list)?collection($shop_server_list)->toArray():[];
        $work_job = explode(',',$info['types']);
        //当前员工信息
        $host = config('file_server_url');
        $this->assign('host',$host);
        $this->assign('work_job',$work_job);
        $this->assign('job',$shop_server_list);
        $this->assign('info',$info);
        return view('worker_update');
    }

    //更新员工信息
    public function workerUpdate()
    {
        $params = $this->request->param();
        $id = $params['data']['id'];
        $data = $params['data'];
        $data['addtime'] = time();
        $data['type'] = implode(',',$data['type']);
        // var_dump($data);die;
        if ($id){
            //更新
            unset($data['id']);//去掉id
            $res = (new WorkerModel())->updateWorker($data, $id);
            if ($res){
                LogsController::actionLogRecord('更新服务人员,姓名:'.$data['name'].',id:'.$id);
                return outPut(200,'更新成功');
            }
            return outPut(301,'更新失败');
        }
        //新增
        //是否工号和手机号重复
        $exsit = (new WorkerModel())->where('mobile','=',$data['mobile'])->value('id');
        if ($exsit) {
            return outPut(301,'手机号已存在');
        }
        $res = (new WorkerModel())->updateWorker($data);
        if ($res){
            LogsController::actionLogRecord('添加服务人员:'.$data['name']);
            return outPut(200,'添加成功');
        }
        return outPut(301,'添加失败');
    }
    //获取门店的工种
    public function getShopJob()
    {
        $shop_id = $this->request->param('shop_id');
        if (empty($shop_id)) {
            return outPut(301,'门店信息错误');
        }
        $shop_service = (new ShopModel())->where(['id'=>$shop_id])->value('service_level_price');
        if (empty($shop_service)) {
            return outPut(301,'门店未设置服务项目');
        }
        $jobs = array_keys(json_decode($shop_service,true));
        $server_list = (new ServiceModel())->where(['s_id'=>['in',$jobs]])->field('id,sname')->select();
        if ($server_list){
            return outPut(200,'获取成功',$server_list);
        }
        return outPut(301,'获取失败');
    }


    //排班
    public function scheDuLing()
    {
        //根据员工工号来
        if (Request::instance()->isPost()){
//            echo strtotime('9:00');die;
            $data = $this->request->param();
            $workid = $data['workid'];
            $wid = $data['wid'];
            $datas = isset($data['data'])?$data['data']:'';
            if (empty($datas)){
                return outPut(301,'没有排班内容,请添加');
            }
            $infos = (new WorkTimeModel())->where(['workid' => $workid])
                ->find();
            if (!empty($infos)) {
                $worktime = new WorkTimeModel();
                $worktime::where('workid','=',$workid)->delete();
            }
            foreach ($datas as $key => $item) {
                //key是星期
                $res = (new WorkTimeModel())->scheduling($workid,$wid,$key,$item);
            }
            if ($res){
                return outPut(200,'排班完成');
            }else{
                return outPut(301,'排班出错');
            }
            die;
        }
        $w_id = $this->request->param('id');
        $info = (new WorkerModel())->workerInfos($w_id);
        $this->assign('info',$info);
        return view('scheduling');
    }

    //服务人员开收工
    public function openOrClose()
    {
      $workid = $this->request->param('workid');
      $res = (new WorkerModel())->setIswork($workid);
      if ($res) {
        return outPut(200,'操作成功');
      }
      return outPut(301,'操作失败');
    }

    //删除服务人员
    public function workerDel()
    {
        $w_id = $this->request->param('id');
        if (empty($w_id)) {
            outPut(301,'参数错误');
        }
        $Worker = WorkerModel::get($w_id);
//        $res = $Worker->delete();
        if (WorkerModel::destroy(['id' => $w_id])) {
            (new WorkTimeModel())->DelScheduling($w_id);
            LogsController::actionLogRecord('删除服务人员,姓名:'.$Worker->name.',id:'.$w_id);
            return outPut(200,'删除成功');
        }
        return outPut(301,'删除失败');
    }

    public function getworklist()
    {
        $w_id = $this->request->param('workid');
        $works = [];
        for ($i = 1;$i < 8; $i++){
            $date = (new WorkTimeModel())->workTimeLists($w_id,$i);
            $works[$i] = [];
            for ($a = 0;$a < count($date);$a++){
                array_push($works[$i],$date[$a]['time_index']);
            }
        }
        return outPut(200,'获取成功',$works);
    }

    /*
     * 服务和参考价格列表
     */
    public function standard(){
        $lists = (new ShopModel())->get_standard_price()->paginate(15);
        // 获取分页显示
        $page = $lists->render();
        $this->assign('lists',$lists);
        $this->assign('page',$page);
        return $this->fetch('standard_index');
    }
    public function getServiceInfo()
    {
        $id = $this->request->param('s_id');
        $info = (new ServiceModel())->getInfo($id,'standard_price as service_price');
        if ($info){
            return outPut(200,'获取成功',$info);
        }
        return outPut(301,'没有设置会员价');
    }
    /*
     * 修改服务和参考价格
     */
    public function standard_edit(){
        if($this->request->isGet()){
            $id = $this->request->param('s_id');
            $level_list = (new LevelModel())->get_level_list();
            $info = (new ServiceModel())->getInfo($id);
            $different = count($level_list)-count($info['service_price']);
            $this->assign('info',$info);
            $this->assign('different',$different);
            $this->assign('level_list',$level_list);
//            dump($level_list);dump($info['service_price']);exit;
            return $this->fetch('standard_edit');
        }
        $params = input('post.');
        $service_id = $params['s_id'];
        $level_id = isset($params['l_id'])?$params['l_id']:'';
        $value = $params['value'];
        $model = new ShopModel();
        if ($model->edit_standard_price($service_id,$level_id,$value)) {
            return outPut(1,'修改成功');
        }
        return outPut(0,'修改失败');
    }

    /*
     * 添加服务及参考价格
     */
    public function standard_add(){
        $model = new LevelModel();
        $lists = $model->get_level_list();
        $this->assign('lists',$lists);
        return $this->fetch('standard_add');
    }

    /*
     * 保存服务及参考价格
     */
    public function standard_save(){
        $params = $this->request->param();
        $data = $params['data'];
        $id = isset($data['s_id'])?$data['s_id']:0;
        $res = (new ShopModel())->save_standard($data,$id);
        if ($res) {
            $code = 200;
            $msg = $id?'更新成功':'添加成功';
        }else{
            $code = 301;
            $msg = $id?'更新失败':'添加失败';
        }
        return outPut($code,$msg);
    }

    /*
     * 删除服务及参考价格
     */
    public function standard_delete(){
        $id = $this->request->param("s_id", 0, 'intval');
        if(!$id){
            return outPut(0,'删除失败，缺少参数请重试');
        }
        $model = new ShopModel();
        $result = $model->delete_standard($id);
        if($result!=1){
            $this->error($result);
        }else{
            $this->success("删除成功！", url('Shop/standard'));
        }
    }

    /**
     * 获取现有等级标准
     * @return string|\think\response\Json
     */
    public function level_set(){
        $id = input('post.id','');
        if(!$id){
            return outPut(0,'参数丢失');
        }
        $level_list = (new LevelModel())->get_level_list();
        $level_standard = json_decode(model('shop.Shop')->get_one_level_standard($id),true);
        return outPut(1,'获取等级列表成功',['level_list'=>$level_list,'level_standard'=>$level_standard]);
    }

    /**
     * 修改等级标准
     */
    public function updata_level_price(){
        $params = input('post.');
        $result = (new ShopModel())->update_level_price($params);
        switch ($result){
            case 1:
                $this->success('更新成功','shop/shoplist');
                break;
            case 0:
                $this->error('更新失败');
                break;
            case 2:
                $this->error('更新失败,请将数据填写完整！');
                break;
        }
    }

    public function get_standard_price()
    {
        $serviceId = input('post.service_id');
        $data = Db::connect(config('ddxx'))->name('service')->where(['s_id'=>$serviceId])->value('standard_price');
        $count = count(json_decode($data,true));
        $datas['data'] = json_decode($data,true);
        $datas['count'] = $count;
        return outPut(1,'success',$datas);
    }

}

