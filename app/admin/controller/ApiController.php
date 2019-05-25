<?php
/**
 * Author: chenjing
 * Date: 2018/1/16
 * Description:
 */

namespace app\admin\controller;

use think\Session;
use think\Db;

// header("Access-Control-Allow-Origin:*");
// header("Access-Control-Allow-Methods:GET, POST, OPTIONS, DELETE");
// header("Access-Control-Allow-Headers:DNT,X-Mx-ReqToken,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type, Accept-Language, Origin, Accept-Encoding");

class ApiController extends BaseController
{
    public function _initialize()
    {
        //重新初始化,过滤验证
    }

    public function getExpress()
    {
        $data = $this->request->param();;
        // var_dump($data);die;
        if (empty($data)){
            $express_info = '';
        }else{
            $url = "https://m.kuaidi100.com/query?type=".$data['type']."&postid=".$data['postid'];
            $res = curl_get($url);
            $res = json_decode($res,true);
            // return $res;
            if (empty($res) || $res['status'] != 200){
                $express_info = '';
            }else{
                $express_info = $res['data'];
            }
        }
        return json_encode($res,JSON_UNESCAPED_UNICODE);
    }

    //获取页面初始化所需数据
    public function getAllData()
    {
        $data = [];
        $except_role_id = [1,6];//不限制的门店管理员角色
        $shop_id = Session::get('SHOP_ID');
        $admin_id = $this->admin_id;
        $role_id = Db::table('ddxm_role_user')->where('user_id','=',$admin_id)->value('role_id');
        if ($admin_id == 1 ||in_array($role_id,$except_role_id)) {
            $shop_id = 22;
        }
        if (empty($shop_id) || !is_numeric($shop_id)) {
            return outPut(301,'当前信息有误，请退出重新登录');
        }else{
            $data['shop_id'] = (int)$shop_id;
        }
        $workerlist = Db::connect(config('ddxx'))->table('tf_worker')->field('id,name,type,workid')->where(['sid'=>$shop_id,'status'=>1])->select();//获取门店员工
        foreach ($workerlist as $k => $v)
        {
            if ($v['type'] == 0) {
                $workerlist[$k]['type'] = '婴儿游泳';
            }elseif($v['type'] == 1){
                $workerlist[$k]['type'] = '小儿推拿';
            }else{
                $workerlist[$k]['type'] = '成人推拿';
            }
        }
        $data['work_list'] = $workerlist;
        //生成一个订单号
        $data['order_sn'] = time().rand(10,99).$shop_id;
        return outPut(200, '获取成功', $data);
    }

}
