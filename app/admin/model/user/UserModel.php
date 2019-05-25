<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\admin\model\user;

use app\admin\model\BaseModel;
use app\admin\common\Logs;
use app\admin\controller\LogsController;
use app\admin\model\shop\ShopModel;
use think\Db;
use think\Exception;

class UserModel extends BaseModel
{
    protected $table = 'tf_member';

    protected $autoWriteTimestamp=true;

    protected $createTime='regtime';
    protected $updateTime='';

    //事件
    protected static function init()
    {
        self::afterUpdate(function ($userModel) {
            Logs::actionLogRecord($userModel->msg);
            LogsController::actionLogRecord($userModel->msg);
        });
        self::afterInsert(function ($userModel) {
            Logs::actionLogRecord($userModel->msg);
            LogsController::actionLogRecord($userModel->msg);
        });
    }

    public function getRegtimeAttr($value)
    {
        if ($value) {
            return date('Y-m-d H:i',$value);
        }
        return '2017-01-01 00:00';

    }

    // public function getPicAttr($value)
    // {
    //     if (empty($value)) {
    //         return '';
    //     }elseif (is_numeric(strpos($value,'https')) || is_numeric(strpos($value,'http'))) {
    //         return $value;
    //     }else{
    //         return config('upload.file_host').$value;
    //     }
    // }

   public function getShopCodeAttr($value)
    {
        if ($value == '') {
            return  (new ShopModel())->where('code','=',$value)->field('code,name')->find();
        }elseif($value != '' || $value != false){
           return  (new ShopModel())->where(strtolower('code'),'=',strtolower($value))->field('code,name')->find();
        }
        return '';
    }
    //一对一模型(获取店铺信息)
    public function shopInfo()
    {
        return $this->belongsTo('app\admin\model\shop\ShopModel','shop_code','code')
            ->field('code,name');
    }

    /**
     * [getUserShopId 获取用户门店id]
     * @param  integer $id    [id]
     * @return integer $info  [数组]
     */
    public function getUserShopId($id = 0)
    {
        $shop_code = $this->where(['id'=>$id])->value('shop_code as s_code');
        $shop_id =(new ShopModel())->where('code','=',$shop_code)->value('id');
        return $shop_id;
    }

    //获取用户列表信息
    public function getMemberDatas($where)
    {
        return $this->alias('a')
            ->where($where)
            ->field('a.id,a.pic,a.nickname,a.no,a.amount,a.money,a.pid,a.mobile,a.shop_code,a.openid,a.is_staff,a.status,a.regtime,c.level_name')
            ->join('tf_level c','a.level_id = c.id','LEFT')
            ->order('a.id DESC');
    }

    //保存或修改
    public function saveDatas(array $params = [],$id = 0)
    {
        if ($id) {
            $userInfo = self::get($id)->toArray();

            list($beforeUpdateData,$afterUpdateData) = getDiffArr($params,$userInfo);

            $params['msg'] = '修改了昵称: ' . $params['nickname'] . ', 手机号: '. $userInfo['mobile'] .',的用户|修改前数据: '. json_encode($beforeUpdateData,256) . '. 修改后数据: ' . json_encode($afterUpdateData,256);

            $data = $this->allowField(true)->save($params,$id);
            return $data;
        }

        unset($params['id']);
        $params['regtime'] = time();
        $params['mobile'] = trim($params['mobile']);
        $params['paypwd'] = md5('888888');
        $params['msg'] = '添加了昵称: ' . $params['nickname'] . ', 手机号: '. $params['mobile'] .',的新用户 ';
        return $this->allowField(true)->save($params);
    }

    //获取用户详细信息
    public function getUserDetailInfo($id = 0)
    {
        return $this->alias('a')
            ->field('a.id,a.level_id,a.pid,a.mobile,a.shop_code,a.nickname,a.money,a.amount,a.profit,a.openid,a.regtime,a.status,a.is_staff,b.nickname as recommend_name,a.score_item,a.score_server')
            ->where('a.id','=', $id)
            ->join('tf_member b','a.pid = b.id','LEFT')
            ->find()->toArray();
    }

    //获取用户某个字段的信息
    public function getUserField($id,$field){
        return $this->where('id',$id)->value($field);
    }

    /**
     * [upgradeLevelTest 计算会员的等级]
     * @param  integer $member_id [会员id]
     * @return [type]             [description]
     */
    public static function upgradeMemberLevel($member_id = 0){
        $db = Db::connect(config('ddxx'));
        $members = $db->name('member')->alias('a')
            ->join('tf_shop b','a.shop_code = b.code','LEFT')
            ->where(['a.id'=>$member_id])
            ->field('a.id,b.id as shop_id,a.amount,a.level_id')
            ->find();
        $shop_level_price = $db->name('shop')->where(['id'=>$members['shop_id']])->value('level_standard');
        $shop_level_price = json_decode($shop_level_price,true);
        $level_id = $members['level_id'];
        foreach ($shop_level_price as $k=>$v){
            if ($members['amount'] >= $v) {
                if ($k >= $level_id) {
                    $level_id = $k;
                }
            }
        }
        $db->name('member')->where('id',$member_id)->update(['level_id'=>$level_id]);
    }

    //跟新信息和增加记录
    public function updateAndRecord($data = [])
    {
        if (empty($data)) {
            return false;
        }
        $db = Db::connect(config('ddxx'));
        $db->startTrans();
        try {
            $old_num = $this->where('id','=',$data['id'])->value($data['field']);
            if ($data['set_type'] == 1) {
                $res = $this->where('id','=',$data['id'])->setInc($data['field'],$data['edit_num']);
            }else{
                $res = $this->where('id','=',$data['id'])->setDec($data['field'],$data['edit_num']);
            }
            if ($res) {
                //写入记录
                $this->writeRecord($data,$old_num);
            }
        } catch (Exception $e) {
            $db->rollback();
            // var_dump($e);die;
            return false;
        }
        $db->commit();
        return true;
    }

    /**
     *写入记录
     */
    protected function writeRecord($datas = [],$old_num = 0)
    {
        $data = [];
        $db = Db::connect(config('ddxx'));
        $db->startTrans();
        try {
            if($datas['type'] == 1 || $datas['type'] == 2){//写入会员积分记录
                $data['member_id'] = $datas['id'];
                $data['order_id'] = 0;
                $data['integral_type'] = $datas['type'];
                $data['consume_type'] = $datas['set_type'];
                $data['price'] = $datas['edit_num'];
                $data['is_online'] = 0;
                $data['content'] = $datas['remark'];
                $data['addtime'] = time();
                $db->name('member_integral_log')->insert($data);
            }elseif($datas['type'] == 3 || $datas['type'] == 4){
                $field_name = $datas['type'] == 3?"累积充值额度":"用户余额";
                if ($datas['set_type'] == 1){
                    $new_money = (float)$old_num + (float)$datas['edit_num'];
                }else{
                    $new_money = (float)$old_num - (float)$datas['edit_num'] < 0?0:(float)$old_num - (float)$datas['edit_num'];
                }
                $msg = $datas['admin_name'].'调整id为'.$datas['id'].'的用户的'.$field_name.',调整前后为：'.$old_num.'=>'.$new_money.'。原因：'.$datas['remark'];
                LogsController::actionLogRecord($msg);
            }
            $db->commit();
        } catch (\Exception $e) {
            $db->rollback();
        }
    }
}
