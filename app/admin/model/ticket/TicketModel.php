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
namespace app\admin\model\ticket;

use app\admin\controller\LogsController;
use app\admin\model\BaseModel;
use think\Db;

class TicketModel extends BaseModel
{
    protected $table = 'tf_ticket';

    protected function getAddtimeAttr($addtime)
    {
        return date('Y-m-d H:i:s',$addtime);
    }
    protected function getRestrictNumAttr($rn)
    {
        return empty($rn)?'不限':$rn;
    }
    protected function getCirculationAttr($cri)
    {
        return empty($cri)?'不限':$cri;
    }
    protected function getIntegralNumAttr($In)
    {
        return empty($In)?'不限':$In;
    }
    protected function getExpireDateAttr($date)
    {
        return empty($date)?'不限':$date;
    }
    protected function getGetWayAttr($value)
    {
        $array = [
            0 => '未知',
            1 => '售卖',
            2 => '兑换'
        ];
        return ['status'=>$value,'name'=>$array[$value]];
    }

    protected function getServiceIdAttr($value)
    {
        $serviceModel = new \app\admin\model\ServiceModel();
        return $serviceModel->where(['s_id'=>$value])->field('s_id as id,sname')->find();
    }

    /**
     * @param array $where
     * @return TicketModel
     */
    public function getTicketList(array $where = [])
    {
        return $this->where($where)->order('addtime ASC');
    }

    //获取当前员工信息
    public function voucherInfo($id)
    {
        return $this->where(['id'=>$id])->find();
    }

    //更新或者添加数据
    public function updateDetail(array $data = [],$id= 0)
    {
        if ($id){
            $msg = '修改代金券或服务券，id：'.$id;
            LogsController::actionLogRecord($msg);
            return $res = $this->allowField(true)->save($data,['id'=>$id]);
        }
        $msg = '添加代金券或服务券';
        LogsController::actionLogRecord($msg);
        return $res = $this->allowField(true)->save($data);
    }

}
