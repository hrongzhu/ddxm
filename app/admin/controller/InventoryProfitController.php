<?php
/**
 * Created by PhpStorm.
 * User: shiyu
 * Date: 2018/8/6 0006
 * Time: 下午 17:11
 */

namespace app\admin\controller;


use app\admin\model\ProfitManageModel;
use cmf\controller\AdminBaseController;
use think\Db;
use think\Session;

//盘盈单管理
class InventoryProfitController extends AdminBaseController
{

    public function index()
    {
        return view('index');
    }


    /**
     * @api {POST} /admin/InventoryProfit/get_inventory_profit_list 盘盈单列表
     * @apiGroup 盘盈单管理
     * @apiName get_inventory_profit_list
     * @apiVersion 1.0.0
     * @apiDescription 盘盈单列表
     * @apiParam (请求参数) {int} shop_id 门店（仓库）ID 可空
     * @apiParam (请求参数) {str} item_name 商品名称 可空
     * @apiParam (请求参数) {str} sn 盘盈单号 可空
     * @apiParam (请求参数) {str} creator_name 入库制单 可空
     * @apiParam (请求参数) {str} time 盘盈单时间段 可空
     * @apiParam (请求参数) {int} status 入库状态 1已入库 0未入库 可空
     * @apiParam (请求参数) {int} page 页码 可空
     * @apiSuccess (返回参数) {Int} code 状态码
     * @apiSuccess (返回参数) {str} msg 请求结果 success成功 fail失败
     * @apiSuccess (返回参数) {array} data 返回结果集
     * @apiSuccess (返回参数) {int} data.totalPage 总页数
     * @apiSuccess (返回参数) {int} data.totalNum 总记录条数
     * @apiSuccess (返回参数) {int} data.pageSize 每页记录条数
     * @apiSuccess (返回参数) {array} data.list 盘盈单列表数据集
     * @apiSuccess (返回参数) {int} data.list.id stock表的ID
     * @apiSuccess (返回参数) {int} data.list.shop_id 不用管
     * @apiSuccess (返回参数) {str} data.list.shop_name 门店（仓库）名
     * @apiSuccess (返回参数) {str} data.list.sn 盘盈单编号
     * @apiSuccess (返回参数) {int} data.list.creator_id 不用管
     * @apiSuccess (返回参数) {str} data.list.creator_name 制单人
     * @apiSuccess (返回参数) {int} data.list.status 盘盈单状态 0未入库 1已入库,
     * @apiSuccess (返回参数) {str} data.list.time 盘盈单时间
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"success","data":{"list":[{"id":2,"shop_id":22,"sn":"PY201808060002","creator_id":1,"time":"2018-08-06 14:59:14","status":0,"shop_name":"测试门店10","creator_name":"admin"},{"id":1,"shop_id":22,"sn":"PY201808060001","creator_id":1,"time":"2018-08-06 15:00:55","status":0,"shop_name":"测试门店10","creator_name":"admin"}],"totalPage":1,"totalNum":2,"pageSize":10}}
     * @apiSampleRequest /admin/InventoryProfit/get_purchase_list
     */
    public function get_inventory_profit_list()
    {
        $shop_id = Session::get('SHOP_ID');
        $except_role_id = [1, 6,7,8];//不限制的门店管理员角色
        $admin_id = $this->admin_id;
        $role_id = Db::table('ddxm_role_user')->where('user_id', '=', $admin_id)->value('role_id');
        $data = input('post.');
//        $data = [
//            'shop_id'=>'',
//            'sn'=>'',
//            'item_name'=>'',
//            'creator_name'=>'',
//            'time'=>'',
//            'status'=>'',
//            'page'=>1
//        ];
        $shopId = $data['shop_id'];
        $sn = $data['sn'];
        $item_name = $data['item_name'];
        $creator = $data['creator_name'];
        $time = $data['time'];
        $status = $data['status'];
        $page = $data['page'];
        $where = [];
        $res_data = [];
        if ($time) {
            $time_arr = explode('~', $time);
            $add_s = strtotime($time_arr[0]);
            $add_e = strtotime($time_arr[1]) + 24 * 3600;
            $where['a.time'] = ['between', [$add_s, $add_e]];
        }
        if ($sn) {
            $where['a.sn'] = $sn;
        }
        if ($item_name) {
            $where['b.title'] = $item_name;
        }
        if ($creator) {
            $creator_id = Db::name('user')->where(['user_login' => ["like", "%$creator%"]])->value('id');
            $where['a.creator_id'] = $creator_id;
        }
        if ($status) {
            $where['a.status'] = $status;
        }
        if (empty($shopId)) {
            if ($admin_id == 1 || in_array($role_id, $except_role_id)) {
                $shop_id = '';
            }
        } else {
            $shop_id = $shopId;
        }
        if($shop_id!=''){
            $where['a.shop_id'] = $shop_id;
        }
        $where['a.type'] = 1;
        $where['a.status'] = ["neq",-1];
        //定义分页所需的数据
        $count = (new ProfitManageModel())->get_profit_list($where)->count();
        $totalItem = $count;   //总记录数(自行定义)
        $pageSize = 10;  //每一页记录数(自行定义)
        $totalPage = ceil($totalItem / $pageSize);  //总页数
        $startItem = ($page - 1) * $pageSize;//根据页码来决定查询数据的节点
        $list = (new ProfitManageModel())->get_profit_list($where)->limit($startItem,$pageSize)->select();
        foreach ($list as &$v){
            $creator_name = Db::name('user')->where(['id'=>$v['creator_id']])->value('user_login');
            $shop_name = Db::connect(config('ddxx'))->name('shop')->where(['id'=>$v['shop_id']])->value('name');
            $v['shop_name'] = $shop_name;
            $v['creator_name'] = $creator_name;
            $v['time'] = date("Y-m-d H:i:s",$v['time']);
        }

        $res_data['list'] = $list;
        $res_data['totalPage'] = $totalPage;
        $res_data['totalNum'] = $count;
        $res_data['pageSize'] = $pageSize;
        return outPut(200,'success',$res_data);
    }

    /**
     * @api {POST} /admin/InventoryProfit/get_one_inventory_profit_info 查看盘盈单详细信息
     * @apiGroup 盘盈单管理
     * @apiName get_one_inventory_profit_info
     * @apiVersion 1.0.0
     * @apiDescription 查看盘盈单详细信息
     * @apiParam (请求参数) {int} id stock表的ID，不可空
     * @apiSuccess (返回参数) {Int} code 状态码
     * @apiSuccess (返回参数) {str} msg 请求结果 success成功 fail失败
     * @apiSuccess (返回参数) {array} data 返回结果集
     * @apiSuccess (返回参数) {array} data.stock_info 盘盈单数据
     * @apiSuccess (返回参数) {int} data.stock_info.id stock表的id
     * @apiSuccess (返回参数) {int} data.stock_info.creator_id 不用管
     * @apiSuccess (返回参数) {int} data.stock_info.complete_admin_id 不用管
     * @apiSuccess (返回参数) {int} data.stock_info.shop_id 不用管
     * @apiSuccess (返回参数) {int} data.stock_info.shop_name 所入仓库
     * @apiSuccess (返回参数) {int} data.stock_info.time 盘盈单日期
     * @apiSuccess (返回参数) {int} data.stock_info.sn 盘盈单编号
     * @apiSuccess (返回参数) {int} data.stock_info.creator_name 盘点制单人
     * @apiSuccess (返回参数) {int} data.stock_info.complete_name 入库人
     * @apiSuccess (返回参数) {int} data.stock_info.remark 备注信息
     * @apiSuccess (返回参数) {array} data.stock_item_info
     * @apiSuccess (返回参数) {int} data.stock_item_info.item_id 商品ID
     * @apiSuccess (返回参数) {str} data.stock_item_info.title 商品名称
     * @apiSuccess (返回参数) {str} data.stock_item_info.bar_code 商品条形码
     * @apiSuccess (返回参数) {str} data.stock_item_info.cname 商品分类
     * @apiSuccess (返回参数) {int} data.stock_item_info.num 数量
     * @apiSuccess (返回参数) {str} data.stock_item_info.remark 备注
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"success","data":{"list":[{"id":2,"shop_id":22,"sn":"PY201808060002","creator_id":1,"time":"2018-08-06 14:59:14","status":0,"shop_name":"测试门店10","creator_name":"admin"},{"id":1,"shop_id":22,"sn":"PY201808060001","creator_id":1,"time":"2018-08-06 15:00:55","status":0,"shop_name":"测试门店10","creator_name":"admin"}],"totalPage":1,"totalNum":2,"pageSize":10}}
     * @apiSampleRequest /admin/InventoryProfit/get_one_inventory_profit_info
     */
    public function get_one_inventory_profit_info()
    {
        $id = input('post.id');
        $where['type'] = 1;
        $where['id'] = $id;
        $list = (new ProfitManageModel())->get_one_inventory_info($where);
        return outPut(200,'success',$list);
    }

    /**
     * @api {POST} /admin/InventoryProfit/stock_in_confirm 确认入库
     * @apiGroup 盘盈单管理
     * @apiName stock_in_confirm
     * @apiVersion 1.0.0
     * @apiDescription 确认入库
     * @apiParam (请求参数) {int} id stock表的ID，不可空
     * @apiSuccess (返回参数) {Int} code 状态码
     * @apiSuccess (返回参数) {str} msg 请求结果 success成功 fail失败
     * @apiSuccess (返回参数) {str} data 返回结果信息
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"success","data":""}
     * @apiSampleRequest /admin/InventoryProfit/stock_in_confirm
     */
    public function stock_in_confirm()
    {
        $id = input('post.id');
        if(!$id){
            return outPut(301,'fail','参数丢失！');
        }
        $where['type']=1;
        $where['id']=$id;
        $db = Db::connect(config('ddxx'));
        $stock_info = (new ProfitManageModel())->get_one_inventory_info($where);
        //查询商品当前的库存，当前库存+盘盈库存=实际库存，实际库存写入purchase_price表
        $stock_item_info = $stock_info['stock_item_info'];
        $shop_id = $stock_info['stock_info']['shop_id'];
        $insert_data = [];
        foreach ($stock_item_info as $k=>$v){
            $stock_now = $db->name('purchase_price')->where(['shop_id'=>$shop_id,'item_id'=>$v['item_id']])->order('id desc')->find();
            $stock_py = $v['num'];
            $stock = isset($stock_now['stock'])?$stock_now['stock']:0;
            $real_stock = $stock+$stock_py;
            $insert_data_tmp = [
                'shop_id'=>$shop_id,
                'type'=>3,
                'pd_id'=>$stock_info['stock_info']['id'],
                'item_id'=>$v['item_id'],
                'md_price_before'=>isset($stock_now['md_price_before'])?$stock_now['md_price_before']:0,
                'md_price_after'=>isset($stock_now['md_price_after'])?$stock_now['md_price_after']:0,
                'store_cost_before'=>isset($stock_now['store_cost_before'])?$stock_now['store_cost_before']:0,
                'store_cost_after'=>isset($stock_now['store_cost_after'])?$stock_now['store_cost_after']:0,
                'stock'=>$real_stock,
                'time'=>time()
            ];
            $insert_data[] = $insert_data_tmp;
        }
        $db->startTrans();
        $fail=0;
        try{
            $result = $db->name('purchase_price')->insertAll($insert_data);
            //更新shop_item表中该商品的库存
            foreach ($stock_item_info as $kk=>$vv){
                if (!($db->name('shop_item')->where(['shop_id'=>$shop_id,'item_id'=>$vv['item_id']])->setInc('stock',$vv['num']))) {
                    $fail+=1;
                }
            }
            if ($result) {
                $result2 = $db->name('stock')->where($where)->update(['status'=>1]);
            }
            if($result&&$result2&&$fail==0){
                $db->commit();
                return outPut(200,'success','保存成功');
            }else{
                $db->rollback();
                return outPut(301,'fail','保存失败');
            }
        }catch (\Exception $e){
            $db->rollback();
            return outPut(301,'fail','保存失败');
        }
    }
    
}