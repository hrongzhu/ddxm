<?php
/**
 * Created by PhpStorm.
 * User: shiyu
 * Date: 2018/8/1 0001
 * Time: 下午 14:10
 */

namespace app\admin\controller;


use app\admin\model\ProfitManageModel;
use app\admin\model\StockCheckModel;
use app\admin\model\StockModel;
use cmf\controller\AdminBaseController;
use think\Db;
use think\Session;

class StockController extends AdminBaseController
{

    public function index()
    {
        return view('index');
    }


    /**
     * @api {POST} /admin/Stock/get_init_data 获取初始数据
     * @apiGroup 库存盘点
     * @apiName get_init_data
     * @apiVersion 1.0.0
     * @apiDescription 库存盘点获取门店列表及权限初始数据
     * @apiParam (请求参数) {str} null 不需要请求参数
     * @apiSuccess (返回参数) {Int} code 状态码
     * @apiSuccess (返回参数) {str} msg 请求结果 success成功 fail失败
     * @apiSuccess (返回参数) {array} data 返回结果集
     * @apiSuccess (返回参数) {int} data.is_show 是否显示金额相关数据 1是 0否
     * @apiSuccess (返回参数) {array} data.shop_list 门店（仓库）列表
     * @apiSuccess (返回参数) {int} data.shop_list.id 门店（仓库）ID
     * @apiSuccess (返回参数) {str} data.shop_list.name 门店（仓库）名
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"success","data":{"is_show":1,"shop_list":[{"id":18,"name":"总店"},{"id":6,"name":"爱琴海店"},{"id":5,"name":"留云路店"},{"id":16,"name":"龙湖源著店"},{"id":17,"name":"重庆城口店"},{"id":15,"name":"珠江太阳城店"},{"id":21,"name":"两江时光店"},{"id":22,"name":"测试门店10"},{"id":25,"name":"约克郡南郡"},{"id":24,"name":"约克郡北郡"},{"id":26,"name":"蓝光COCO店"},{"id":29,"name":"融创金茂店"},{"id":28,"name":"港城国际店"},{"id":27,"name":"江与城店"},{"id":30,"name":"麓山别苑店"},{"id":31,"name":"奥山别墅店"}]}}
     * @apiSampleRequest /admin/Stock/get_init_data
     */
    public function get_init_data()
    {
        $shop_id = Session::get('SHOP_ID');
        $except_role_id = [1, 6, 7];//不限制的门店管理员角色
        $is_show = 0;//是否显示金额数据，默认为否
        //获取店铺信息(回去根据当前登录的用户来筛选店铺)
        $admin_id = $this->admin_id;
        $role_id = Db::table('ddxm_role_user')->where('user_id', '=', $admin_id)->value('role_id');
        if ($shop_id && $admin_id != 1) {
            $shopDatas = Db::connect(config('ddxx'))->name('shop')->where(['id' => $shop_id, 'status' => ["neq", 0]])->field('id,name')->select();
        }
        if (in_array($role_id, $except_role_id) || $admin_id == 1) {
            $shopDatas = Db::connect(config('ddxx'))->name('shop')->where(['status' => ["neq", 0]])->field('id,name')->select();
            $is_show = 1;
        }
        $data['is_show'] = $is_show;
        $data['shop_list'] = $shopDatas;
        return outPut(200, 'success', $data);
    }

    /**
     * @api {POST} /admin/Stock/get_stock_list 获取库存盘点列表
     * @apiGroup 库存盘点
     * @apiName get_stock_list
     * @apiVersion 1.0.0
     * @apiDescription 获取库存盘点列表
     * @apiParam (请求参数) {int} page 页码 不可空
     * @apiParam (请求参数) {int} shop_id 门店（仓库）ID 可空
     * @apiParam (请求参数) {str} item_name 商品名 可空
     * @apiParam (请求参数) {str} bar_code 商品条形码 可空
     * @apiSuccess (返回参数) {Int} code 状态码
     * @apiSuccess (返回参数) {str} msg 请求结果 success成功 fail失败
     * @apiSuccess (返回参数) {array} data 返回结果集
     * @apiSuccess (返回参数) {int} data.totalPage 总页数
     * @apiSuccess (返回参数) {int} data.totalNum 总记录条数
     * @apiSuccess (返回参数) {int} data.pageSize 每页记录条数
     * @apiSuccess (返回参数) {array} data.list 库存数据列表
     * @apiSuccess (返回参数) {int} data.list.id 库存数据表ID
     * @apiSuccess (返回参数) {int} data.list.item_id 商品ID
     * @apiSuccess (返回参数) {int} data.list.shop_id 门店（仓库）ID
     * @apiSuccess (返回参数) {str} data.list.shop_name 门店（仓库）名
     * @apiSuccess (返回参数) {str} data.list.title 商品名称
     * @apiSuccess (返回参数) {str} data.list.cname 商品分类
     * @apiSuccess (返回参数) {str} data.list.bar_code 商品条形码
     * @apiSuccess (返回参数) {int} data.list.stock 当前库存
     * @apiSuccess (返回参数) {int} data.list.num 冻结库存
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"success","data":{"totalPage":4,"totalNum":35,"pageSize":10,"list":[{"id":53,"shop_name":"测试门店10","shop_id":22,"title":"花王NB90纸尿裤  1袋装","bar_code":"4901301230782","item_id":1,"cname":"花王","stock":32,"num":0},{"id":52,"shop_name":"测试门店10","shop_id":22,"title":"花王S82纸尿裤  1袋装","bar_code":"4901301230812","item_id":2,"cname":"花王","stock":0,"num":0},{"id":51,"shop_name":"测试门店10","shop_id":22,"title":"花王L54纸尿裤  1袋装","bar_code":"4901301230881","item_id":4,"cname":"花王","stock":99,"num":0},{"id":50,"shop_name":"测试门店10","shop_id":22,"title":"花王XL44纸尿裤  1袋装","bar_code":"4901301253422","item_id":5,"cname":"花王","stock":0,"num":0},{"id":49,"shop_name":"测试门店10","shop_id":22,"title":"新西兰惠氏金装S-26  1段1罐装","bar_code":"","item_id":6,"cname":"惠氏","stock":1,"num":0},{"id":46,"shop_name":"总店","shop_id":18,"title":"花王NB90纸尿裤  1袋装","bar_code":"4901301230782","item_id":1,"cname":"花王","stock":12,"num":0},{"id":45,"shop_name":"总店","shop_id":18,"title":"花王S82纸尿裤  1袋装","bar_code":"4901301230812","item_id":2,"cname":"花王","stock":1,"num":0},{"id":44,"shop_name":"总店","shop_id":18,"title":"花王L54纸尿裤  1袋装","bar_code":"4901301230881","item_id":4,"cname":"花王","stock":23,"num":0},{"id":43,"shop_name":"总店","shop_id":18,"title":"花王XL44纸尿裤  1袋装","bar_code":"4901301253422","item_id":5,"cname":"花王","stock":1,"num":0},{"id":42,"shop_name":"总店","shop_id":18,"title":"新西兰惠氏金装S-26  1段1罐装","bar_code":"","item_id":6,"cname":"惠氏","stock":1,"num":0}]}}
     * @apiSampleRequest /admin/Stock/get_stock_list
     */
    public function get_stock_list()
    {
        $shop_id = Session::get('SHOP_ID');
        $except_role_id = [1, 6, 7];//不限制的门店管理员角色
        $admin_id = $this->admin_id;
        $where = [];
        $data = [];
        $role_id = Db::table('ddxm_role_user')->where('user_id', '=', $admin_id)->value('role_id');
        $input = input('post.');
        $shopId = isset($input['shop_id']) ? $input['shop_id'] : '';
        if (empty($shopId)) {
            if ($admin_id == 1 || in_array($role_id, $except_role_id)) {
                $shop_id = '';
            }
        } else {
            $shop_id = $shopId;
        }
        if($shop_id!==''){
            $where['a.shop_id'] = $shop_id;
        }
        $item_name = isset($input['item_name']) ? $input['item_name'] : '';
        if ($item_name) {
            $where['b.title'] = ['like',"%$item_name%"];
        }
        $bar_code = isset($input['bar_code']) ? $input['bar_code'] : '';
        if ($bar_code) {
            $where['b.bar_code'] = $input['bar_code'];
        }
        $page = isset($input['page']) ? $input['page'] : 1;
        $count = (new StockModel())->get_stock_lists($where)->count();
        $totalItem = $count;   //总记录数(自行定义)
        $pageSize = 30;  //每一页记录数(自行定义)
        $totalPage = ceil($totalItem / $pageSize);  //总页数
        $startItem = ($page - 1) * $pageSize;//根据页码来决定查询数据的节点
        $list = (new StockModel())->get_stock_lists($where)->limit($startItem, $pageSize)->select();
        $profitModel = new ProfitManageModel();
        foreach ($list as $k=>$v){
            $stock = Db::connect(config('ddxx'))->name('purchase_price')->where(['shop_id'=>$v['shop_id'],'item_id'=>$v['item_id']])->order('id desc')->find();
            $num = $profitModel->get_allot_ice($v['shop_id'],$v['item_id'])
                +$profitModel->get_reject_ice($v['shop_id'],$v['item_id'])
                +$profitModel->get_order_tmp_ice($v['shop_id'],$v['item_id'])
                +$profitModel->get_inventory_loss_ice($v['shop_id'],$v['item_id'])
            ;
            $list[$k]['stock'] = $stock['stock'];
            $list[$k]['num'] = $num;
        }
        $data['totalPage'] = $totalPage;
        $data['totalNum'] = $count;
        $data['pageSize'] = $pageSize;
        $data['list'] = $list;
        return outPut(200, 'success', $data);
    }

    /**
     * @api {POST} /admin/Stock/create_inventory_profit 生成盘盈单
     * @apiGroup 库存盘点
     * @apiName create_inventory_profit
     * @apiVersion 1.0.0
     * @apiDescription 生成盘盈单时获取初始数据
     * @apiParam (请求参数) {int} shop_id 门店（仓库）id
     * @apiSuccess (返回参数) {Int} code 状态码
     * @apiSuccess (返回参数) {str} msg 请求结果 success成功 fail失败
     * @apiSuccess (返回参数) {array} data 返回结果集
     * @apiSuccess (返回参数) {int} data.shop_id 门店（仓库）ID
     * @apiSuccess (返回参数) {int} data.shop_name 门店（仓库）名
     * @apiSuccess (返回参数) {str} data.time 盘盈单日期
     * @apiSuccess (返回参数) {str} data.sn 盘盈单单号
     * @apiSuccess (返回参数) {str} data.creator_name 盘盈单制单人
     * @apiSuccess (返回参数) {str} data.iner_name 盘盈单入库人
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"success","data":{"shop_id":5,"shop_name":"留云路店","time":"2018-08-02","sn":"PY201808020001","creator_name":"admin","iner_name":"--"}}
     * @apiSampleRequest /admin/Stock/create_inventory_profit
     */
    public function create_inventory_profit()
    {
//        $arr = [
//            'shop_id' => 5,
//            'item_list' => [
//                [
//                    'item_id' => 1,
//                    'num' => 1
//                ]
//            ]
//        ];
//        $data = $arr;
        $data = input('post.');
        $shop_id = $data['shop_id'];
//        $item_list = $data['item_list'];
        $db = Db::connect(config('ddxx'));
        $res_data = [];
//        foreach ($item_list as $k=>$v){
//            $item_info = $db->name('item')->alias('a')
//                ->join('tf_item_category b','a.type = b.id')
//                ->field('a.title,a.bar_code,b.cname')
//                ->where(['a.id'=>$v['item_id']])
//                ->find();
//            $item_list[$k]['title'] = $item_info['title'];
//            $item_list[$k]['bar_code'] = $item_info['bar_code'];
//            $item_list[$k]['cname'] = $item_info['cname'];
//        }
        $shop_name = $db->name('shop')->where(['id' => $shop_id])->value('name');
        $time = date("Y-m-d", time());
        //生成盘盈单号
        $last_item = Db::connect(config('ddxx'))->name('stock')->field('sn')->where(['type' => 1])->order('time desc')->limit(1)->find();
        if (!$last_item) {
            $end_num = '0001';
        } else {
            $last_sn = substr($last_item['sn'], -4);
            $end_num = $newStr = sprintf('%04s', $last_sn + 1);
        }
        $new_sn = 'PY' . date("Ymd", time()) . $end_num;
        $res_data['shop_id'] = $shop_id;
        $res_data['shop_name'] = $shop_name;
        $res_data['time'] = $time;
        $res_data['sn'] = $new_sn;
        $res_data['creator_name'] = \db('user')->where(['id' => $this->admin_id])->value('user_login');
        $res_data['iner_name'] = '--';
        return outPut(200, 'success', $res_data);
    }


    /**
     * @api {POST} /admin/Stock/create_inventory_loss 生成盘亏单
     * @apiGroup 库存盘点
     * @apiName create_inventory_loss
     * @apiVersion 1.0.0
     * @apiDescription 生成盘亏单时获取初始数据
     * @apiParam (请求参数) {int} shop_id 门店（仓库）id
     * @apiSuccess (返回参数) {Int} code 状态码
     * @apiSuccess (返回参数) {str} msg 请求结果 success成功 fail失败
     * @apiSuccess (返回参数) {array} data 返回结果集
     * @apiSuccess (返回参数) {int} data.shop_id 门店（仓库）ID
     * @apiSuccess (返回参数) {int} data.shop_name 门店（仓库）名
     * @apiSuccess (返回参数) {str} data.time 盘亏单日期
     * @apiSuccess (返回参数) {str} data.sn 盘亏单单号
     * @apiSuccess (返回参数) {str} data.creator_name 盘亏单制单人
     * @apiSuccess (返回参数) {str} data.outer_name 盘亏单出库人
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"success","data":{"shop_id":5,"shop_name":"留云路店","time":"2018-08-06","sn":"PK201808060001","creator_name":"admin","outer_name":"--"}}
     * @apiSampleRequest /admin/Stock/create_inventory_loss
     */
    public function create_inventory_loss()
    {
//        $arr = [
//            'shop_id' => 5,
//            'item_list' => [
//                [
//                    'item_id' => 1,
//                    'num' => 1
//                ]
//            ]
//        ];
//        $data = $arr;
        $data = input('post.');
        $shop_id = $data['shop_id'];
//        $item_list = $data['item_list'];
        $db = Db::connect(config('ddxx'));
        $res_data = [];
//        foreach ($item_list as $k=>$v){
//            $item_info = $db->name('item')->alias('a')
//                ->join('tf_item_category b','a.type = b.id')
//                ->field('a.title,a.bar_code,b.cname')
//                ->where(['a.id'=>$v['item_id']])
//                ->find();
//            $item_list[$k]['title'] = $item_info['title'];
//            $item_list[$k]['bar_code'] = $item_info['bar_code'];
//            $item_list[$k]['cname'] = $item_info['cname'];
//        }
        $shop_name = $db->name('shop')->where(['id' => $shop_id])->value('name');
        $time = date("Y-m-d", time());
        //生成盘盈单号
        $last_item = Db::connect(config('ddxx'))->name('stock')->field('sn')->where(['type' => 2])->order('time desc')->limit(1)->find();
        if (!$last_item) {
            $end_num = '0001';
        } else {
            $last_sn = substr($last_item['sn'], -4);
            $end_num = $newStr = sprintf('%04s', $last_sn + 1);
        }
        $new_sn = 'PK' . date("Ymd", time()) . $end_num;
        $res_data['shop_id'] = $shop_id;
        $res_data['shop_name'] = $shop_name;
        $res_data['time'] = $time;
        $res_data['sn'] = $new_sn;
        $res_data['creator_name'] = \db('user')->where(['id' => $this->admin_id])->value('user_login');
        $res_data['outer_name'] = '--';
        return outPut(200, 'success', $res_data);
    }


    /**
     * @api {POST} /admin/Stock/save 保存盘盈单/盘亏单
     * @apiGroup 库存盘点
     * @apiName save
     * @apiVersion 1.0.0
     * @apiDescription 保存或编辑盘盈单（盘亏单）
     * @apiParam (请求参数) {int} id 盘盈单（盘亏单）id，为空时添加，不为空时编辑
     * @apiParam (请求参数) {int} type 单据类型，1为盘盈单，2为盘亏单
     * @apiParam (请求参数) {int} shop_id 门店（仓库）ID
     * @apiParam (请求参数) {str} sn 盘盈单（盘亏单）编号
     * @apiParam (请求参数) {str} remark 盘盈单（盘亏单）备注
     * @apiParam (请求参数) {array} item_list 商品数据集
     * @apiParam (请求参数) {int} item_list.item_id 商品ID
     * @apiParam (请求参数) {int} item_list.num 商品盘盈（盘亏）数量
     * @apiParam (请求参数) {str} item_list.remark 备注
     * @apiSuccess (返回参数) {Int} code 状态码
     * @apiSuccess (返回参数) {str} msg 请求结果 success成功 fail失败
     * @apiSuccess (返回参数) {str} data 返回结果信息
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"success","data":"保存成功"}
     * @apiSampleRequest /admin/Stock/save
     */
    public function save()
    {
        $data = input('post.');
//        $data = [
//            'id' => 1,
//            'type' => 1,
//            'shop_id' => 22,
//            'sn' => 'PY201808060001',
//            'remark' => '备注2',
//            'item_list' => [
//                [
//                    'item_id' => 2,
//                    'num' => 3,
//                    'remark'=>'备注321'
//                ],
//                [
//                    'item_id' => 4,
//                    'num' => 5,
//                    'remark'=>'备注456'
//                ]
//            ]
//        ];
        $id = $data['id'];
        $type = $data['type'];//保存单据类型，1盘盈单 2盘亏单
        $sn = $data['sn'];
        $remark = $data['remark'];
        $item_list = $data['item_list'];
        $shop_id = $data['shop_id'];
        $stock_data = [
            'type' => $type,
            'shop_id' => $shop_id,
            'sn' => $sn,
            'remark' => $remark,
            'creator_id' => $this->admin_id,
            'time' => time()
        ];
        $db = Db::connect(config('ddxx'));
        $db->startTrans();
        if ($id) {
            try{
                unset($stock_data['sn']);
                unset($stock_data['shop_id']);
                $isset = $db->name('stock')->where(['id' => $id])->field('id')->find();
                if (!$isset) {
                    return outPut(301, 'fail', '没有该条数据');
                }
                $fail=0;
                foreach ($item_list as $k => $v) {
                    $item_list[$k]['stock_id'] = $id;
                    if($type==2){
                        //冻结中的库存 = 更新前的冻结中库存-该盘亏单据原冻结的库存+本次提交的盘亏数量
                        $inventory_loss_ice_now = $db->name('stock_item')->where(['stock_id'=>$id,'shop_id'=>$shop_id,'item_id'=>$v['item_id']])->value('num');//现已冻结的调拨中库存
                        $stock_ice_all = $db->name('shop_item')->where(['shop_id'=>$shop_id,'item_id'=>$v['item_id']])->value('stock_ice');
                        $ress = $db->name('shop_item')->where(['shop_id'=>$shop_id,'item_id'=>$v['item_id']])->update(['stock_ice'=>$stock_ice_all-$inventory_loss_ice_now+abs($v['num'])]);
                        if(!$ress){
                            $fail+=1;
                        }
                    }
                }
                $res1 = $db->name('stock')->where(['id' => $id])->update($stock_data);
                $res2 = $db->name('stock_item')->where(['stock_id' => $id])->delete();
                $res3 = $db->name('stock_item')->insertAll($item_list);
                if ($res1 && $res2 && $res3 && $fail==0) {
                    $db->commit();
                    return outPut(200, 'success', '保存成功');
                }
                $db->rollback();
                return outPut(301,'fail','保存失败');
            }catch (\Exception $e){
                $db->rollback();
                return outPut(301,'fail','保存失败');
            }
        } else {
            try {
                $res = $db->name('stock')->insert($stock_data);
                $stock_id = $db->name('stock')->getLastInsID();
                if($res){
                    foreach ($item_list as $k=>$v){
                        $item_list[$k]['stock_id']=$stock_id;
                    }
                    $res2 = $db->name('stock_item')->insertAll($item_list);
                    $fail = 0;
                    if ($type==2) {
                        foreach ($item_list as $kk=>$vv){
                            if (!($db->name('shop_item')->where(['shop_id'=>$shop_id,'item_id'=>$vv['item_id']])->setInc('stock_ice',abs($vv['num'])))) {
                                $fail+=1;
                            }
                        }
                    }
                    if($res&&$res2&&$fail==0){
                        $db->commit();
                        return outPut(200,'success','保存成功');
                    }
                    $db->rollback();
                    return outPut(301,'fail','保存失败');
                }
            } catch (\Exception $e) {
                $db->rollback();
                return outPut(301,'fail','保存失败:'.$e->getMessage());
            }
        }

    }

    /**
     * @api {POST} /admin/Stock/delete 删除盘盈单/盘亏单
     * @apiGroup 库存盘点
     * @apiName delete
     * @apiVersion 1.0.0
     * @apiDescription 删除盘盈单/盘亏单
     * @apiParam (请求参数) {int} id 盘盈单（盘亏单）id
     * @apiParam (请求参数) {int} type 单据类型，1为盘盈单，2为盘亏单
     * @apiSuccess (返回参数) {Int} code 状态码
     * @apiSuccess (返回参数) {str} msg 请求结果 success成功 fail失败
     * @apiSuccess (返回参数) {str} data 返回结果信息
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"success","data":"删除成功"}
     * @apiSampleRequest /admin/Stock/delete
     */
    public function delete()
    {
        $type = input('post.type');
        $id = input('post.id');
        if(!$id){
            return outPut(301,'fail','参数丢失');
        }
        $where = [];
        $where['type'] = $type;
        $where['id'] = $id;
        $db = Db::connect(config('ddxx'));
        $res = $db->name('stock')->where($where)->find();
        if($res['status']==1){
            return outPut(301,'fail','该单据已完成，不可删除！');
        }
        $db->startTrans();
        try{
            $r = $db->name('stock')->where($where)->update(['status'=>-1]);
            //如果删除的是盘亏单，释放冻结的库存
            $fail=0;
            if($type==2){
                $item_list = $db->name('stock_item')->where(['stock_id'=>$id])->select();
                foreach ($item_list as $k=>$v){
                    if (!($db->name('shop_item')->where(['shop_id'=>$res['shop_id'],'item_id'=>$v['item_id']])->setInc('stock_ice',$v['num']))) {
                        $fail+=1;
                    }
                }
            }
            if($r&&$fail==0){
                $db->commit();
                return outPut(200,'success','删除成功！');
            }
            $db->rollback();
            return outPut(301,'fail','删除失败');
        }catch (\Exception $e){
            $db->rollback();
            return outPut(301,'fail','删除失败');
        }
    }

}
