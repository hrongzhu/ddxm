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
namespace app\admin\model\shop;

use app\admin\model\BaseModel;
use app\admin\model\FranchiseeModel;
use app\admin\model\LevelModel;
use app\admin\model\ServiceModel;
use think\Db;

class ShopModel extends BaseModel
{
    protected $table = 'tf_shop';
    protected $tableName = 'shop';

    public function getCodeAttr($value)
    {
        if (empty($value)) {
            return '';
        }
        return $value;
    }
    public function getQrcodeAttr($value)
    {
        return 'http://'.$_SERVER['HTTP_HOST'].'/'.$value;
    }
//   public function getStatusAttr($value)
//    {
//        if (empty($value)) {
//            return '异常';
//        }
//        $Arr = [
//            -1 =>'禁用',
//            1 => '正常',
//            0 => '审核中'
//        ];
//        return $Arr[$value];
//    }

    public function getAddtimeAttr($value)
    {
        if (empty($value)) {
            return '';
        }
        return date('Y-m-d H:i',$value);
    }
    public function getThumbAttr($value)
    {
        if(is_numeric(strpos($value,'http'))){
            return $value;
        }
        return config('file_server_url').$value;
    }

//    一对一  加盟商
    public function franchisee()
    {
        return $this->hasOne('app\admin\model\FranchiseeModel','id','account_id')->field('id,name');
    }

    //根据id获取店铺code
    public function getShopCode($where = '')
    {
       $shopCodes =  $this->where($where)->field('code')->fetchSql(false)->select();
       return $shopCodes;
    }

    //门店列表
    public function shopList($where = [])
    {
        return self::with('franchisee')
             ->where($where)
             ->field('id,name,code,account_id,detail_address,location_address,addtime')
             ->order('id asc');
    }

    //门店列表
    public function shopInfo($id)
    {
        $res = self::with('franchisee')
            ->where(['id'=>$id])
            ->find();
        $res->service_level_price = json_decode($res->service_level_price,true);
        $res->online_service_book = json_decode($res->online_service_book,true);
        return $res;
    }
    //更新或者添加数据
    public function updateShop($data = [],$id= '')
    {
        if ($id){
            return $res = $this->allowField(true)->save($data,['id'=>$id]);
        }
        return $res = $this->allowField(true)->save($data);
    }
    //删除门店
    public function delShop($id = 0)
    {
        $order = ShopModel::get($id);
        $res = $order->delete();
        if ($res){
            return true;
        }
        return false;
    }

    //获取门店单个字段的值
    public function shopBaseInfo($id,$field)
    {
        return $res = $this
            ->where(['id'=>$id])
            ->value($field);
    }

    //获取最大id
    public function maxId()
    {
        return $this->max('id');
    }

    /*
     * 获取服务和参考价格列表
     */
    public function get_standard_price(){
        return (new ServiceModel())->field('s_id,sname,bar_code,cover,icon,remark')->where('status',1);
    }


    /**
     * @param $service_id 服务ID
     * @param $level_id 等级ID
     * @param $value 修改后的值
     * @return int 1修改成功，0修改失败
     */
    public function edit_standard_price($service_id,$level_id,$value){
        $db = Db::connect(config('ddxx'));
        $result1 = $result2 = 0;
        if($level_id){//有level_id修改价格
            $one = json_decode($db->name('service')->where('s_id',$service_id)->value('standard_price'),true);
            $one[$level_id] = intval($value);
            $new = json_encode($one);
            $result1 = $db->name('service')->where('s_id',$service_id)->update(['standard_price'=>$new]);
        }
        if(!$level_id){//没有level_id修改服务名称
            $result2 = $db->name('service')->where('s_id',$service_id)->update(['sname'=>$value]);
        }
        if ($result1&&$result2) {
            return 0;
        }
        return 1;
    }

    /**
     * 用户提交的数据
     * @param array $data
     * @param int $id
     * @return false|int
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function save_standard(array $data = [],$id = 0){
        $serviceModel = new ServiceModel();
        $level_arr = $data['level_price'];
        $original_price = $data['level_price'][1];
        $lastId = $serviceModel->field('id')->order('s_id desc')->limit(1)->find();
        $standard_price = json_encode($level_arr,JSON_UNESCAPED_UNICODE);
        $arr = [
            'sname'=> $data['sname'],
            'icon'=> $data['icon'],
            'cover'=> $data['cover'],
            'bar_code'=> $data['bar_code'],
            'remark'=>$data['remark'],
            'price'=>$original_price,
            'addtime'=>time(),
            'standard_price'=>$standard_price,
            'status'=>1
        ];
        if ($id){
            return $serviceModel->allowField(true)->save($arr,['s_id'=>$id]);
        }
        $arr['id'] = $lastId['id']+1;
        return $serviceModel->allowField(true)->save($arr);
    }

    /**
     * @param $s_id 服务ID
     * @return int
     */
    public function delete_standard($s_id){
        $db = Db::connect(config('ddxx'));
        if ($db->name('service')->where('s_id',$s_id)->update(['status'=>0])) {
            return 1;
        }else{
            return 0;
        }
    }

    /**
     * 更新店铺的等级标准
     * @param $data
     * @return bool
     */
    public function update_level_price($data){
        $shop_id = $data['shop_id'];
        unset($data['shop_id']);
        $flag = 1;
        foreach ($data as $v){
            if($v==''){
                $flag =0;
                break;
            }
        }
        if($flag){
            $re = $this->where('id',$shop_id)->update(['level_standard'=>json_encode($data)]);
            if ($re) {
                return 1;
            }else{
                return 0;
            }
        }else{
            return 2;
        }
    }

    /**
     * 获取一个店铺的等级标准
     * @param $shop_id
     * @return mixed
     */
    public function get_one_level_standard($shop_id){
        return $this->where('id',$shop_id)->value('level_standard');
    }

}
