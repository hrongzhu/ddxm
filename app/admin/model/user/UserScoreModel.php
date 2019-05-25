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
use app\admin\controller\LogsController;

class UserScoreModel extends BaseModel
{
    protected $table = 'tf_member_score_rule';

    //事件
    protected static function init()
    {
        self::afterUpdate(function ($userScoreModel) {
            LogsController::actionLogRecord($userScoreModel->msg);
        });
        self::afterInsert(function ($userScoreModel) {
            LogsController::actionLogRecord($userScoreModel->msg);
        });
    }

    protected function getAddtimeAttr($time)
    {
    	return date('Y-m-d H:i:s',$time);
    }

    public function getRuleList()
	{
		return $this->order('level_id asc');
	}

	//更新或者添加数据
    public function updateScoreRule($data = [],$id= 0)
    {
        if ($id){
        	$before_ratio = $this->where('id',$id)->value('ratio');
        	$data['msg'] = '修改id为'.$id.'的会员积分规则,修改前为：'.$before_ratio.'，修改后为：'.$data['ratio'];
            return $res = $this->allowField(true)->save($data,['id'=>$id]);
        }
        $data['msg'] = '增加会员积分规则,比例是：'.$data['ratio'];
        $data['addtime'] = time();
        return $res = $this->allowField(true)->save($data);
    }

    public function delScoreRule($id = 0)
    {
    	$msg= '删除会员积分规则,id是：'.$id;
		$res = $this->destroy($id);
		if ($res) {
			LogsController::actionLogRecord($msg);
			return true;
		}
		return false;
    }
}
