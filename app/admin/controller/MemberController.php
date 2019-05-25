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

use app\admin\model\CardModel;
use app\admin\model\LevelModel;
use app\admin\model\MemberGoodsModel;
use app\admin\model\MemberModel;
use app\admin\model\order\OrderModel;
use app\admin\model\shop\ShopModel;
use app\admin\model\shop\WorkerModel;
use app\admin\model\ticket\TicketModel;
use app\admin\model\user\CardInfoModel;
use app\admin\model\user\UserCardModel;
use app\admin\model\user\UserModel;
use app\admin\model\AdminUserModel;
use app\admin\model\user\UserTicketModel;
use http\Env\Request;
use think\Exception;
use think\Session;
use think\Db;

/**
 * Class MemberController
 * @package app\admin\controller
 * @adminMenuRoot(
 *     'name'   =>'用户模块',
 *     'action' =>'menuDefault',
 *     'parent' =>'',
 *     'display'=> true,
 *     'order'  => 10000,
 *     'icon'   =>'cogs',
 *     'remark' =>'用户模块'
 * )
 */
class MemberController extends BaseController
{
    protected $pageLimit = 12;

    protected $templatePrefix = 'member/';

    /**
     * 会员列表
     * @adminMenu(
     *     'name'   => '会员列表',
     *     'parent' => 'menuDefault',
     *     'display'=> true,
     *	   'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '会员列表',
     *     'param'  => ''
     * )
     */
    public function userList()
    {
        //查询shop_id,shop_code  限制默认显示的内容d
        $shop_id = Session::get('SHOP_ID');
        $except_role_id = [1,6];//不限制的门店管理员角色
        //获取店铺信息(回去根据当前登录的用户来筛选店铺)
        $admin_id = $this->admin_id;
        $role_id = Db::table('ddxm_role_user')->where('user_id','=',$admin_id)->value('role_id');
        $is_show_warn_recharge = $admin_id == 1?1:0;
        if ($admin_id == 1 || in_array($role_id,$except_role_id)) {
          $shopDatas = ShopModel::all(function($query){
              $query->field('name,code');
          });
        }else{
          $w['id'] = ['in',$shop_id];
          $shopCodes = collection((new ShopModel())->getShopCode($w))->toArray();
          $shop_codes = '';
          foreach ($shopCodes as $v){
              $shop_codes .= $v['code'].',';
          }
          $shop_codes = rtrim($shop_codes, ",");
          $shopDatas = OrderModel::getShopList($w,1);
        }
        $this->assign('is_show',$is_show_warn_recharge);
        $this->assign('shopDatas',$shopDatas);
        if($admin_id==1||in_array($role_id,$except_role_id)){
            $this->assign('isAdmin',1);
        }
        //搜索数据
        $where = [];

        $id = $this->request->param('id','');
        $nickname = trim($this->request->param('nickname',''));
        $recommendName = trim($this->request->param('recommend_name',''));
        $mobile = trim($this->request->param('mobile',''));
        $shopCode = trim($this->request->param('shop_code',''));
        $isStaff = trim($this->request->param('is_staff',''));

        if ($id != null) {
            $where['a.id'] = ['=', $id];
        }

        if ($nickname != null) {
            $where['a.nickname'] = ['like', "%$nickname%"];
        }

        if ($recommendName != null) {
            $where['b.nickname'] = ['like', "%$recommendName%"];
        }

        if ($mobile != null) {
            $where['a.mobile'] = ['like', "%$mobile%"];
        }
        // var_dump($where);die;
        if ($shopCode != null) {
            $where['a.shop_code'] = ['=', "$shopCode"];
        }else if (isset($shop_codes) && $admin_id != 1 && !in_array($role_id,$except_role_id) ) {
            $where['a.shop_code'] = ['in',$shop_codes];
        }

        $this->assign('shopCode',$shopCode);


        if ($isStaff != null) {
            $where['a.is_staff'] = ['=', "$isStaff"];
        }

        // var_dump($where);die;
        $this->assign('isStaff',$isStaff);
        $userModel = new UserModel();
        $userDatas = $userModel->getMemberDatas($where);
        $pageParams = [
            'id' => $id,
            'nickname' => $nickname,
            'recommend_name' => $recommendName,
            'mobile' => $mobile,
            'shop_code' => $shopCode,
            'is_staff' => $isStaff,
        ];
        $datas = $this->lists($userDatas,$pageParams)->toArray();
//        dump($datas);exit;
        $host = config('file_server_url');
        $this->assign('host' , $host);
        $this->assign('datas' , $datas['data']);

        return $this->fetch('list');
    }

    /**
     * [exportUserList 导出所有用户数据]
     * @return [type] [description]
     */
    public function exportUserList()
    {
        set_time_limit(0);
        //查询用户列表数据
        $userModel = new UserModel();
        $user_list = $userModel->getMemberDatas($where = [])->select();
        // var_dump($user_list);die;
        $phpexcel = new \PHPExcel();
        $phpexcel->getActiveSheet()
            ->setCellValue('A1','ID')
            ->setCellValue('B1','昵称')
            ->setCellValue('C1','手机号')
            ->setCellValue('D1','所属店铺')
            ->setCellValue('E1','会员等级')
            ->setCellValue('F1','OPENID')
            ->setCellValue('G1','累计充值')
            ->setCellValue('H1','会员余额');
        foreach ($user_list as $k=>$v){
            $phpexcel->getActiveSheet()->setCellValue('A'.($k+2),$v['id'])
                ->setCellValueExplicit('B'.($k+2),$v['nickname'],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('C'.($k+2),$v['mobile'],\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('D'.($k+2),isset($v['shop_code'])?$v['shop_code']['name']:'无',\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('E'.($k+2),isset($v['level_name'])?$v['level_name']:'普通会员',\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('F'.($k+2),$v['openid']?$v['openid']:'无')
                ->setCellValue('G'.($k+2),$v['amount'])
                ->setCellValue('H'.($k+2),$v['money']);
        }
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.date("YmdH:i",time()).' 会员信息.xls"');
        header('Cache-Control: max-age=0');
        $a = new \PHPExcel_Writer_Excel5($phpexcel);
        $a->save('php://output');
    }

    /**
     * 添加/修改会员
     * @adminMenu(
     *     'name'   => '添加/修改会员',
     *     'parent' => 'menuDefault',
     *     'display'=> false,
     *	   'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加/修改会员',
     *     'param'  => ''
     * )
     */
    public function addOrUpdateUser()
    {
        $id = $this->request->param('id/d',0);
        $this->assign('id',$id);
        $levelInfo = (new LevelModel())->get_level_list();
        $isAdmin = 0;

        //查询shop_id,shop_code  限制默认显示的内容d
        $shop_id = Session::get('SHOP_ID');
        $except_role_id = [1,6];//不限制的门店管理员角色
        //获取店铺信息(回去根据当前登录的用户来筛选店铺)
        $admin_id = $this->admin_id;
        $role_id = Db::table('ddxm_role_user')->where('user_id','=',$admin_id)->value('role_id');
//            dump($role_id);exit;
        if ($shop_id && $admin_id != 1){
             $shop_id = explode(',',$shop_id);
            $w['id'] = ['in',$shop_id];
            $shopCodes = collection((new ShopModel())->getShopCode($w))->toArray();
            $shop_codes = '';
            foreach ($shopCodes as $v){
                $shop_codes .= $v['code'].',';
            }
            $shop_codes = rtrim($shop_codes, ",");
            $shopDatas = OrderModel::getShopList($w,1);
        }elseif (in_array($role_id,$except_role_id)) {
            $shopDatas = ShopModel::all(function($query){
                $query->field('name,code');
            });
            $isAdmin = 1;
        }else{
            $isAdmin = 1;
            $shopDatas = ShopModel::all(function($query){
                $query->field('name,code');
            });
        }
        $this->assign('shopInfo',$shopDatas);

        if ($id) {
            $userInfo = (new UserModel())->getUserDetailInfo($id);
            $this->assign('userInfo',$userInfo);
        }
        $this->assign('shopId',$shop_id);
        $this->assign('isAdmin',$isAdmin);
        $this->assign('levelInfo',$levelInfo);

        return $this->fetch('add_update');
    }

    /**
     * 保存会员
     * @adminMenu(
     *     'name'   => '保存会员',
     *     'parent' => 'menuDefault',
     *     'display'=> false,
     *	   'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '保存会员',
     *     'param'  => ''
     * )
     */
    public function saveUser()
    {
        $params = $this->request->post();
        $params = $params['data'];
        // $params['mobile'] = trim($params['mobile']);
        // $shop_code = (new UserModel())->where(['mobile'=>$params['mobile']])->value('shop_code');
        if($params['id']==0){
            $params['mobile'] = str_replace(' ','',$params['mobile']);
            //检验手机号
            $rule = '^1(3|4|5|7|8|9)[0-9]\d{8}$^';
            $result = preg_match($rule, $params['mobile']);
            if (!$result) {
                return outPut(301,'手机号错误');
            }
            //检验是否注册
            $shop_code = (new UserModel())->where(['mobile'=>$params['mobile']])->value('shop_code');
            $isset = UserModel::get(['mobile'=>$params['mobile']]);
            if (!empty($isset)) {
                $shopInfo = ShopModel::get(['code'=>$isset->shop_code['code']]);
                if ($shopInfo) {
                    return outPut(300,'保存失败！此号码已在'.$shopInfo->name.'注册！',$isset);
                }else{
                    return outPut(301,'此号码已注册,但未绑店！提醒绑定店铺');
                }
            }
        }
        if (isset($params['id']) && $params['id'] != 0) {
            $result = (new UserModel())->saveDatas($params,$params['id']);
            $shop_code = (new UserModel())->where(['id'=>$params['id']])->value('shop_code');
            //重新计算会员等级
            if (isset($params['shop_code'])) {
                if ($result && $shop_code != $params['shop_code']) {
                    UserModel::upgradeMemberLevel($params['id']);
                }
            }
        }else{
            $result = (new UserModel())->saveDatas($params);
        }
        if ($result) {
            return outPut(200,'保存成功!');
        }
        elseif ($result === 0) {
            return outPut(301,'保存失败，未做修改！');
        }
        return outPut(301,'保存失败！');
    }

    /**
     * 禁用用户
     * @adminMenu(
     *     'name'   => '禁用用户',
     *     'parent' => 'menuDefault',
     *     'display'=> false,
     *	   'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '禁用用户',
     *     'param'  => ''
     * )
     */
    public function forbiddenUser()
    {
        $id = $this->request->post('id');

        $status = $this->request->post('status');

        if ($status) {
            $msg =  '启用用户id: '.$id;
        }else{
            $msg =  '禁用用户id: '.$id;
        }

        $result = (new UserModel())->allowField(true)->save(['status' => $status , 'msg' => $msg],['id' => $id]);

        if ($result) {
            return outPut(0,'操作成功');
        }
        return outPut(-1000,'操作失败');
    }

    /**
     * 添加会员编号
     * @adminMenu(
     *     'name'   => '添加会员编号',
     *     'parent' => 'menuDefault',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加会员编号',
     *     'param'  => ''
     * )
     */
    public function updateNo()
    {
        $id = $this->request->post('id');
        $no = $this->request->post('user_no');
        $res = Db::connect(config('ddxx'))->name('member')->where(['id'=>$id])->update(['no'=>$no]);
        if ($res) {
            return outPut(200,'修改成功');
        }
        return outPut(301,'修改失败');
    }

    /**
     * 重置用户支付密码
     * @adminMenu(
     *     'name'   => '重置用户支付密码',
     *     'parent' => 'menuDefault',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '重置用户支付密码',
     *     'param'  => ''
     * )
     */
    public function resetPayPwd()
    {
        $id = $this->request->post('id');
        $datas['paypwd'] = md5('888888');
        $res = Db::connect(config('ddxx'))->name('member')->where(['id'=>$id])->update($datas);
        // $res = (new UserModel())->saveDatas($datas,$id);
        if ($res) {
            return outPut(200,'重置成功');
        }
        return outPut(301,'重置失败');
    }

    //****************************************各种券*****************
    /**
     * 会员卡列表
     * @adminMenu(
     *     'name'   => '会员卡列表',
     *     'parent' => 'menuDefault',
     *     'display'=> false,
     *	   'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '会员卡列表',
     *     'param'  => ''
     * )
     */
    public function userCardList()
    {
        $shop_id = Session::get('SHOP_ID');
        $except_role_id = [1,6];//不限制的门店管理员角色
        //获取店铺信息(回去根据当前登录的用户来筛选店铺)
        $admin_id = $this->admin_id;
        $role_id = Db::name('role_user')->where('user_id','=',$admin_id)->value('role_id');
        if ($admin_id == 1||in_array($role_id,$except_role_id)) {
            $is_show = 1;
        }else{
            $is_show = 0;
        }
        $member_id = trim($this->request->param('id',0));
        //查询服务人员信息
        $worker_list = (new WorkerModel())->workerList(['a.sid'=>$shop_id])->select();
        $where = [];
        $where['member_id'] = $member_id;
        $where['isdel'] = 0;
        $list = (new UserCardModel())->getUserCardList($where);
        $this->assign('worker_list',$worker_list);
        $this->assign('member_id',$member_id);
        $this->assign('list', $list);
        $this->assign('is_show',$is_show);
        return view('card_list');
    }
    //会员代金券
    public function userVoucherList()
    {
        $member_id = trim($this->request->param('id',0));
        $except_role_id = [1,6];//不限制的门店管理员角色
        $admin_id = $this->admin_id;
        $role_id = Db::name('role_user')->where('user_id','=',$admin_id)->value('role_id');
        if ($admin_id == 1||in_array($role_id,$except_role_id)) {
            $is_show = 1;
        }else{
            $is_show = 0;
        }
        $ticketModel = new UserTicketModel();
        $list = $ticketModel->getUserVoucherTicketList($member_id);
        $pageParams = [
        ];
        $datas = $this->lists($list,$pageParams)->toArray();
        $this->assign('list' , $datas['data']);
        $this->assign('member_id',$member_id);
        $this->assign('is_show',$is_show);
        return view('member_voucher_ticket');
    }
    //会员服务券
    public function userServerList()
    {
        $shop_id = Session::get('SHOP_ID');
        // $shop_id = 22;
        $except_role_id = [1,6];//不限制的门店管理员角色
        $admin_id = $this->admin_id;
        $role_id = Db::name('role_user')->where('user_id','=',$admin_id)->value('role_id');
        if ($admin_id == 1||in_array($role_id,$except_role_id)) {
            $is_show = 1;
        }else{
            $is_show = 0;
        }
        $member_id = trim($this->request->param('id',0));
        $ticketModel = new UserTicketModel();
        $list = $ticketModel->getUserServerTicketList($member_id);
        $worker_list = (new WorkerModel())->workerList(['a.sid'=>$shop_id])->select();
        $pageParams = [
        ];
        $datas = $this->lists($list,$pageParams)->toArray();
        $this->assign('list' , $datas['data']);
        $this->assign('worker_list',$worker_list);
        $this->assign('is_show',$is_show);
        $this->assign('member_id',$member_id);
        return view('member_server_ticket');
    }
    //**************************************************************
    //给会员添加卡片
    public function addUserCard()
    {
        if ($_POST){
          // var_dump($_POST);die;
            $card_id    = $_POST['card_id'];
            $member_id  = $_POST['member_id'];
            $actual_title = isset($_POST['actual_title'])?trim($_POST['actual_title']):'';
            $actual_money = isset($_POST['actual_money'])?trim($_POST['actual_money']):0;
            $userInfo   = (new UserModel())->get($member_id);
            $cardinfo   = (new CardInfoModel())->getcardinfo($card_id);
            //添加卡的数据
            $data = [];
            $data['member_id']  = $member_id;
            $data['shop_id']    = $cardinfo['sid'];
            $data['title']      = $cardinfo['title'];
            if ($actual_title) {
              $data['title'] = $actual_title;
            }
            $data['thumb']      = $cardinfo['thumb'];
            if ($actual_money) {
              $data['num'] = $actual_money;
              $data['my_num'] = $actual_money;
              $data['price'] = $actual_money;
            }else{
              $data['num'] = $cardinfo['price'];
              $data['my_num'] = $cardinfo['price'];
              $data['price'] = $cardinfo['price'];
            }
            $data['card_id'] = $cardinfo['id'];
            $data['status']     = 1;
            $data['yxq']        = $cardinfo['yxq'];
            $data['addtime']    = time();
            $data['yxqtime']    = time() + $cardinfo['yxq']*3600*24;
            $data['paytime']    = time();
            $data['swimnum']    = $cardinfo['num'];
            $data['tuinum']     = $cardinfo['tnum'];
            $data['ctuinum']    = $cardinfo['ctnum'];
            if ($actual_money) {
              $data['msg']        = '给用户id为'.$member_id.'的用户添加熊猫卡,金额:'.$actual_money;
            }else{
              $data['msg']        = '给用户id为'.$member_id.'的用户添加熊猫卡,金额:'.$cardinfo['price'];
            }
            //添加到订单的数据
            $order['shop_id']   = $cardinfo['sid'];
            $order['member_id'] = $member_id;
            $order['card_id']   = $card_id;
            $order['pay_status'] = 1;
            $order['sn']        = time().rand(10,99);
            $codes              = $this->rands(10);
            $order['pay_sn']    = '42000000'.date('W').date('Ymd').$codes;//支付流水号;
            if ($actual_money){
              $order['amount']    = $actual_money;
            }else{
              $order['amount']    = $cardinfo['price'];
            }
            $order['type']      = 3;//买卡
            $order['addtime']   = time();
            $order['paytime']   = time();
            $order['order_status'] = 2;
            $infos = (new UserCardModel())
                ->where(['member_id'=>$member_id,'is_default'=>1])
                ->where('paytime','>',0)
                ->find();
            if (empty($infos)){
                $data['is_default'] = 1;
            }else{
                $data['is_default'] = 0;
            }
            try{
                $orders     = new OrderModel();
                $usercard   = new UserCardModel();
                $shopmodel  = new ShopModel();
                $orders->startTrans();
                $usercard->startTrans();
                $shopmodel->startTrans();
                $usercard->allowField(true)->save($data);
                $res        = $shopmodel->get($cardinfo['sid']);
                $shop_money = $res->amount + $cardinfo['price'];
                $shopmodel->allowField(true)->save(['amount'=>$shop_money],['id'=>$res->id]);
                $orders->allowField(true)->save($order);
                if ($userInfo->paypwd == '') {
                    $userInfo->paypwd = md5('888888');
                    $userInfo->msg = '给用户'.$userInfo->nickname.'设置默认支付密码';
                    $userInfo->allowField(true)->save();
                }
                $orders->commit();
                $shopmodel->commit();
                $usercard->commit();
                return outPut(200,'添加成功');
            }catch (Exception $e){
                $usercard->rollback();
                $shopmodel->rollback();
                $orders->rollback();
                return outPut(301,'添加失败');
            }
            exit;
        }
        //查询shop_id,shop_code  限制默认显示的内容d
        $where = [];
        $shop_id = Session::get('SHOP_ID');
        if (!empty($shop_id) && $this->admin_id != 1) {
            //查询shop_id,shop_code  限制默认显示的内容d
            $shop_id = explode(',',$shop_id);
            $w['id'] = array('in',$shop_id);
            $where['a.sid'] = ['in',$shop_id];
        }
        $member_id = $this->request->param('member_id',0);
        $cardlist = (new CardInfoModel())->getcardlists($where);
        $this->assign('member_id',$member_id);
        $this->assign('cardlist',$cardlist);
        return view('add_card');
    }

    //添加券给会员
    public function addTicket(){
        if ($this->request->isPost()) {
            $member_id = $this->request->param('member_id',0);
            $ticket_id = $this->request->param('ticket_id',0);
            $res = (new UserTicketModel())->addTicketToUser($member_id,$ticket_id);
            return outPut($res['code'],$res['msg'],$res['data']);
        }
        $where = [];
        $shop_id = Session::get('SHOP_ID');
        if (!empty($shop_id) && $this->admin_id != 1) {
            //查询shop_id,shop_code  限制默认显示的内容d
            $shop_id = explode(',',$shop_id);
            $w['id'] = array('in',$shop_id);
            $where['a.sid'] = ['in',$shop_id];
        }
        $member_id = $this->request->param('member_id',0);
        $type = $this->request->param('type',0);
        if ($type) {
            $where['type'] = $type;
        }
        $ticket_list = (new TicketModel())->getTicketList($where)->field('id,name as ticket_name,get_way as getway,integral_price')->select();
        $this->assign('member_id',$member_id);
        $this->assign('ticket_list',$ticket_list);
        return view('add_ticket');
    }

    //编辑卡片
    public function editUserCard()
    {
        $cid = $this->request->param('id');
        $cardinfo = UserCardModel::get($cid);
        $info['my_num'] = $cardinfo->my_num;
        $info['sy_day'] = floor(($cardinfo->yxqtime - time())/3600/24);
        $this->assign('id',$cid);
        $this->assign('cardinfo',$info);
        return view('edit_card');
    }
    //更新卡片
    public function updatCard()
    {
      $datas = $this->request->param();
      $cardinfo = UserCardModel::get($datas['id']);

      $data['yxqtime'] = $datas['sy_day']*3600*24 + time();
      if ($datas['sy_day'] < $cardinfo->yxq) {
        $data['yxq'] = $cardinfo->yxq;
      }else{
        $data['yxq'] = $datas['sy_day'];
      }

      $data['my_num'] = $datas['my_num'];
      $data['msg'] = '修改id为'.$datas['id'].'的熊猫卡';
      $res = (new UserCardModel())->updateUserCard($data,$datas['id']);
      if ($res) {
        return outPut(200,'修改成功');
      }
      return outPut(301,'修改失败');
    }
    //删除卡
    public function delUserCard()
    {
        $card_id = $this->request->param('card_id');
        $card = UserCardModel::get($card_id);
        // if ($card->is_default == 1){
        //     return outPut(301,'当前卡是默认卡,请先更改默认');
        // }
        $res = $card->delete();
        if ($res){
            return outPut(200,'删除成功');
        }
        return outPut(301,'删除失败');
    }
    //删除券
    public function delUserticket()
    {
        $ticket_id = $this->request->param('user_ticket_id');
        $res = (new UserTicketModel())->delMemberTicket($ticket_id);
        if ($res){
            return outPut(200,'删除成功');
        }
        return outPut(301,'已经删除或删除失败');
    }


    public function buyCard()
    {

        $db = Db::connect(config('ddxx'));
        $uid = $this->request->param('uid');
        $card_list = model('Card')->where('status',1)->field('id,name,money,cover,expire_month,type,service_id,server_list')->select();
        foreach ($card_list as $k => $v) {
            if ($v['type'] == 1) {
                $service_name = $db->name('service')->where('s_id','in',$v['service_id'])->field('sname')->select();
    			$card_list[$k]['service_name'] = arrayFieldTransferString($service_name,'sname');
            }else{
                $server_list = json_decode($v['server_list'],true);
                $str = '';
                foreach ($server_list as $kk => $vv) {
                    $service_name = $db->name('service')->where('s_id',$vv['id'])->value('sname');
                    $str .= $service_name.': '.$vv['num'].'次;';
                }
                $card_list[$k]['service_name'] = $str;
            }
            $card_list[$k]['cover'] = !empty($v['cover'])?config('file_server_url').$v['cover']:'';
        }
        //查询手机号一列
        //这块的Membermodel是模型层的东西 你想用这个模型 必须先在model里面建一个对应的模型文件 命名格式是跟你的表名字格式一样的 因为这个模型操作的就是你数据库里面的表 明明就是表名＋Model 然后你再引入这个模型 use app\admin\model\MemberModel; 这是引入模型的代码  就可以使用这个模型里面的方法了 然后你看手册也会看了  模型的操作你就直接看模型的增删该查 点开模型 想要的方法手册里都有
//        看见了 每个方法里面都有例子 返回什么数据你看手册就可以了
        $mobile = MemberModel::where('id',$uid)->value('mobile');
        //查询余额一列
        $money = MemberModel::where('id',$uid)->value('money');
        //查询用户名一列
        $nickname = MemberModel::where('id',$uid)->value('nickname');
       /* $level = MemberModel::where('id',$uid)->value('level_id')
            ->alias('a')
            ->join('level as b','a.level_id = b.id')
            ->select();*/
       //这块用原生的表关联查询就可以  不用用系统提供的join方法 ，用join方法反而麻烦了 你就直接用Db::query这个方法写原生的sql 再过滤一下 查出来一条就可以了
        $level = $db->query('select b.level_name from tf_member as a left join tf_level as b  on a.level_id = b.id where a.id = '.$uid);
        /*echo '<pre>';
       var_dump($level);exit;*/
        $data = array();
        //还有哪不明白吗
        //将查询的数据拼到一起  //其实不用这么麻烦，主要之前查询出来的数据会有一个以手机号为键名的二维数组 模版里面还不能循环 只能把他拼成一维数组 只能这么干了
        $data['mobile'] = $mobile;
        $data['money'] = $money;
        $data['nickname'] = $nickname;
        $data['level'] = $level[0]['level_name'];
        /*echo '<pre>';
        var_dump($data);exit;*/
        $this->assign('data',$data);
        $this->assign('uid',$uid);
        $this->assign('card_list',$card_list);
        return view('buycard');
    }

    //------------购买服务卡------------------------
    /**
     * 获取用户数据
     * @return string|\think\response\Json
     * @throws \think\exception\DbException
     */
    public function buyCardIndex(){

        $shop_id = Session::get('SHOP_ID');
        $member_id = input('post.id',958);
        $userInfo= UserModel::get(['id'=>$member_id]);
        //生成一个安全的身份验证码
        $ID_AUTH_CODE = sha1('ddxm'.$member_id);
        $order_sn = time().mt_rand(1000,9999);
        return outPut(1,'',['userInfo'=>$userInfo,'id_auth_code'=>$ID_AUTH_CODE,'order_sn'=>$order_sn]);

    }
    //-------------------------------------会员积分规则--------------------------------------------------
    /**
     * 会员积分规则
     * @adminMenu(
     *     'name'   => '会员积分规则',
     *     'parent' => 'menuDefault',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '会员积分规则',
     *     'param'  => ''
     * )
     */
    public function scoreRuleList()
    {
      $rule_list = (new \app\admin\model\user\UserScoreModel())->getRuleList()->paginate(12);
      $levelModel = new \app\admin\model\user\UserLevelModel();
      foreach ($rule_list as &$v)
      {
          $v['level_name'] = $levelModel->where(['id'=>$v['level_id']])->value('level_name');
          if ($v['type'] == 1) {
              $v['type_name'] = '适用于商品';
          }else if($v['type'] == 2){
              $v['type_name'] = '适用于服务';
          }else{
              $v['type_name'] = '通用';
          }
      }
      $this->assign('page',$rule_list->render());
      $this->assign('rule_list',$rule_list);
      return view('score_rule_list');
    }

    /**
     * [updateScoreRule 更新规则]
     * @return [type] [description]
     */
    public function updateScoreRule()
    {
      $userScore = new \app\admin\model\user\UserScoreModel();
      if ($_POST) {
        $datas = $this->request->param();
        if (empty($datas['ratio']) || empty($datas['level_id']) || !isset($datas['type'])) {
          return outPut(301,'缺少数据');
        }
        $id = $datas['id'];
        unset($datas['id']);
        $data = $datas;
        $exists = $userScore->where(['level_id'=>$data['level_id']])->value('id');
        if (!$id && $exists) {
          return outPut(301,'当前等级已存在规则');
        }
        $res = $userScore->updateScoreRule($data,$id);
        if ($res) {
          return outPut(200,'操作成功');
        }
        return outPut(301,'操作失败');
      }
        $id = $this->request->param('id',0);
        $info = [];
        if ($id) {
          $info = $userScore::get($id);
        }
        //获取会员等级列表
        $level_list = (new \app\admin\model\user\UserLevelModel())->getLevelList()->select();
        $this->assign('info',$info);
        $this->assign('level_list',$level_list);
        return view('score_rule_update');

    }

    public function delScoreRule()
    {
      $id = $this->request->param('id',0);
      $res = (new \app\admin\model\user\UserScoreModel())->delScoreRule($id);
      if ($res) {
        return outPut(200,'删除成功');
      }
      return outPut(301,'删除失败');
    }


    //-------------------------------------------------------------------------------------------------------------

    //生成指定长度数字2
    public function rands($no)
    {
        //range 是将1到100 列成一个数组
        $numbers = range (0,9);
        //shuffle 将数组顺序随即打乱
        shuffle ($numbers);
        //array_slice 取该数组中的某一段
        $result  = array_slice($numbers,0,$no);
        $str = '';
        for ($i=0;$i<$no;$i++){
            $str.= $result[$i];
        }
        return $str;
    }

    /**
     * 获取一个用户的openId
     * @return string|\think\response\Json
     */
    public function getOneOpenId(){
        $memberId = input('post.id');
        $data = (new UserModel())->getUserField($memberId,'openid');
        if($data){
            return outPut(1,'',['data'=>$data]);
        }
    }


    /**
     * 把购买的熊猫卡等值转为用户充值，熊猫卡剩余金额转为会员余额，更改会员绑定的店铺为购买熊猫卡的店铺
     */
    public function cardToAmount(){
        set_time_limit(0);
        $cardLists = (new CardModel())->get_card_list('official');//official只查询非体验卡
//        dump($cardLists);exit;
        $result = (new CardModel())->card_to_amount($cardLists);
    }

    /**
     * 计算所有会员的等级
     */
    public function upgradeLevelTest(){
        set_time_limit(0);
        $db = Db::connect(config('ddxx'));
        $members = $db->name('member')->alias('a')
            ->join('tf_shop b','a.shop_code = b.code','LEFT')
            ->field('a.id,b.code,a.amount,a.level_id')
            ->select();
        $arr = [];
        $shop_price = $db->name('shop')->field('id,code,level_standard')->select();
        foreach ($shop_price as $k=>$v){
            if($v['code']!=''){
                $arr[$v['code']]=json_decode($v['level_standard'],true);
            }
        }
        foreach ($members as $kkk=>$vvv){
            if($vvv['code']!=''||$vvv['code']!=0){
                $current_level_price = $arr[$vvv['code']];
                foreach ($current_level_price as $kk=>$vv){
                    if($vvv['amount']>=$vv){
                        $members[$kkk]['level_id'] = $kk;
                    }
                }
            }
        }
        foreach ($members as $value){
            $db->name('member')->where('id',$value['id'])->update(['level_id'=>$value['level_id']]);
        }
    }

    //核销券
    public function checkTicket()
    {
        $id = $this->request->param('id',0);
        $workid = $this->request->param('workid',0);
        $res = (new UserTicketModel())->checkUserTicket($id,$workid);
        return outPut($res['code'],$res['msg']);
    }

    //调整积分等信息
    public function updateScoreOrMoney()
    {
        $userModel = new UserModel();
        $data = $this->request->param();
        $user_name = Session::get('name');
        switch ((int)$data['type']) {
            case 1:
                $field = 'score_server';
                $fields = 'id,score_server as num';
                $remark = '服务积分（竹子）';
                break;
            case 2:
                $field = 'score_item';
                $fields = 'id,score_item as num';
                $remark = '商品积分（笋子）';
                break;
            case 3:
                $field = 'amount';
                $fields = 'id,amount as num';
                $remark = '累计充值金额';
                break;
            case 4:
                $field = 'money';
                $fields = 'id,money as num';
                $remark = '会员余额';
                break;
            default:
                $field = 'score_server';
                $fields = 'id,score_server as num';
                $remark = '服务积分（竹子）';
                break;
        }
        if ($_POST) {
            $data = $this->request->param();
            $admin_id = $this->admin_id;
            $except_role_id = [1,6];
            $role_id = Db::name('role_user')->where('user_id','=',$admin_id)->value('role_id');
            if ($admin_id == 1||in_array($role_id,$except_role_id)) {

            }else{
                return outPut(301,'您没有权限操作');
            }
            $data['field'] = $field;
            $data['admin_name'] = $user_name;
            $res = $userModel->updateAndRecord($data);
            if ($res) {
                return outPut(200,'修改成功');
            }
            return outPut(301,'修改失败');
        }
        $info = $userModel
            ->where('id','=',$data['id'])
            ->field($fields)
            ->find();
        $info['type'] = $data['type'];
        $info['remark'] = $remark;
        $info['admin_name'] = $user_name;
        $this->assign('info',$info);
        return view('update_info');
    }


/*    public function buycard() //这个方法定义了？ 为什么没有定义 它会包这个方法已经定一的错 buycard
    {   //这个字段你等会需要那块改哪个 我先给你举个例子
        $data = MemberGoodsModel::column('shop_code');
        $this->assign('data',$data);
        return $this->fetch('buycard');
    }*/
}



