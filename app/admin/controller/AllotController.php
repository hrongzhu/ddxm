<?php
/**
 * Created by PhpStorm.
 * User: shiyu
 * Date: 2018/7/23 0023
 * Time: 下午 18:54
 */

namespace app\admin\controller;


use app\admin\model\AllotModel;
use app\admin\model\ProfitManageModel;
use app\admin\model\shop\StockModel;
use app\admin\model\StockCheckModel;
use cmf\controller\AdminBaseController;
use think\Db;
use think\Exception;
use think\Session;

//调拨单
class AllotController extends AdminBaseController
{

    public function index()
    {
        return view('index');
    }

    /**
     * @api {POST} /admin/Allot/get_allot_list 调拨单列表
     * @apiGroup 调拨单管理
     * @apiName get_allot_list
     * @apiVersion 1.0.0
     * @apiDescription 调拨单列表
     * @apiParam (请求参数) {str} time 调拨单时间 可空
     * @apiParam (请求参数) {str} item_name 商品名称 可空
     * @apiParam (请求参数) {str} sn 调拨单号 可空
     * @apiParam (请求参数) {int} from_shop 调出仓库 可空
     * @apiParam (请求参数) {int} to_shop 调入仓库 可空
     * @apiParam (请求参数) {int} status 调拨状态 1已完成 0调拨中 可空
     * @apiParam (请求参数) {int} page 页码 可空
     * @apiSuccess (返回参数) {Int} code 状态码
     * @apiSuccess (返回参数) {str} msg 请求结果 success成功 fail失败
     * @apiSuccess (返回参数) {array} data.allot_list 调拨单列表
     * @apiSuccess (返回参数) {int} data.allot_list.id 调拨单ID
     * @apiSuccess (返回参数) {int} data.allot_list.time 调拨单时间
     * @apiSuccess (返回参数) {str} data.allot_list.sn 调拨单编号
     * @apiSuccess (返回参数) {str} data.allot_list.from_shop_name 调出仓库名
     * @apiSuccess (返回参数) {str} data.allot_list.to_shop_name 调入仓库名
     * @apiSuccess (返回参数) {str} data.allot_list.outer 出库人员
     * @apiSuccess (返回参数) {str} data.allot_list.iner 入库人员
     * @apiSuccess (返回参数) {int} data.allot_list.in_time 入库时间
     * @apiSuccess (返回参数) {float} data.allot_list.bid_amount 进价金额
     * @apiSuccess (返回参数) {float} data.allot_list.cost 成本金额
     * @apiSuccess (返回参数) {int} data.allot_list.can_edit 是否可编辑 1可以 0不可以
     * @apiSuccess (返回参数) {int} data.allot_list.status 调拨状态 1已完成 0调拨中
     * @apiSuccess (返回参数) {float} data.bid_amount_all 进价总金额
     * @apiSuccess (返回参数) {float} data.cost_all 成本总金额
     * @apiSuccess (返回参数) {int} data.is_show 是否显示金额相关数据 1是 0否
     * @apiSuccess (返回参数) {int} data.totalPage 总页数
     * @apiSuccess (返回参数) {int} data.totalNum 记录总条数
     * @apiSuccess (返回参数) {int} data.pageSize 每页记录条数
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"success","data":{"shop_list":[{"id":18,"name":"总店"},{"id":6,"name":"爱琴海店"},{"id":5,"name":"留云路店"},{"id":16,"name":"龙湖源著店"},{"id":17,"name":"重庆城口店"},{"id":15,"name":"珠江太阳城店"},{"id":21,"name":"两江时光店"},{"id":22,"name":"测试门店10"},{"id":25,"name":"约克郡南郡"},{"id":24,"name":"约克郡北郡"},{"id":26,"name":"蓝光COCO店"},{"id":29,"name":"融创金茂店"},{"id":28,"name":"港城国际店"},{"id":27,"name":"江与城店"},{"id":30,"name":"麓山别苑店"},{"id":31,"name":"奥山别墅店"}],"allot_list":[{"id":1,"sn":"DB201807230001","from_shop":6,"out_admin_id":1,"to_shop":17,"in_admin_id":0,"status":0,"in_time":0,"bid_amount":"600.0000","cost":"540.0000","time":0,"outer":"admin","iner":null,"from_shop_name":"爱琴海店","to_shop_name":"重庆城口店"}],"allot_list_num":1,"bid_amount_all":600,"cost_all":540,"is_show":0}}
     * @apiSampleRequest /admin/Allot/get_allot_list
     */
    public function get_allot_list()
    {
        $shop_id = Session::get('SHOP_ID');
        $except_role_id = [1, 6];//不限制的门店管理员角色
        $data = [];
        $where = [];
        $is_show = 0;//是否显示金额数据，默认为否
        //获取店铺信息(回去根据当前登录的用户来筛选店铺)
        $admin_id = $this->admin_id;
        $role_id = Db::table('ddxm_role_user')->where('user_id', '=', $admin_id)->value('role_id');
        if ($role_id == 1 || $role_id == 6 || $role_id == 7||$admin_id==1) {
            $is_show = 1;
        }
        //搜索数据
        $page = trim($this->request->param('page', 1));//请求页数
        $itemName = trim($this->request->param('item_name', ''));//商品名称
        $sn = trim($this->request->param('sn', ''));//采购单号
        $from_shop = trim($this->request->param('from_shop', ''));//调出仓库
        $to_shop = trim($this->request->param('to_shop', ''));//调入仓库
        $status = trim($this->request->param('status'));//调拨状态
        $time = $this->request->param('time');
        $where['a.status'] = ['neq',-1];
        if ($itemName != null) {
            $where['c.title'] = ['like', "%$itemName%"];
        }
        if ($sn != null) {
            $where['a.sn'] = $sn;
        }
        if ($from_shop == $to_shop&&$from_shop!=null&&$to_shop!=null) {
            return outPut(301, 'fail', '调出仓库不能和调入仓库相同！');
        } else {
            if ($from_shop != null) {
                $where['a.from_shop'] = $from_shop;
            }
            if ($to_shop != null) {
                $where['a.to_shop'] = $to_shop;
            }
        }
        if ($status != null) {
            $where['a.status'] = $status;
        }
        if ($time) {
            $time_arr = explode('~', $time);
            $add_s = strtotime($time_arr[0]);
            $add_e = strtotime($time_arr[1]) + 24 * 3600;
            $where['a.time'] = ['between', [$add_s, $add_e]];
        }
        //判断当前用户是管理员还是店长
        //管理员不受限制可以查看所有仓库调拨情况
        //店长查看时调出仓库或调入仓库必有其一是其自己门店
        if ($admin_id != 1 && !in_array($role_id, $except_role_id)) {
//            echo 1;exit;
            if (empty($from_shop) && !empty($to_shop)) {
                $where['a.to_shop'] = $to_shop;
            } else if (!empty($from_shop) && empty($to_shop)) {
                $where['a.from_shop'] = $from_shop;
            } else if (empty($from_shop) && empty($to_shop)) {
                $where['a.from_shop|a.to_shop'] = $shop_id;
            }
        }
        //获取所有店铺数据，前端判断用户选择的筛选条件是否合法
        $shopDatas = Db::connect(config('ddxx'))->name('shop')->where(['status'=>['neq',0]])->field('id,name')->select();
        $data['shop_list'] = $shopDatas;
        //调拨单列表
        //定义分页所需的数据
        $count = (new AllotModel())->get_allot_list($where)->count();
        $totalItem = $count;   //总记录数(自行定义)
        $pageSize = 10;  //每一页记录数(自行定义)
        $totalPage = ceil($totalItem / $pageSize);  //总页数
        $startItem = ($page - 1) * $pageSize;//根据页码来决定查询数据的节点
//        dump($where);exit;
        $list = (new AllotModel())->get_allot_list($where)->limit($startItem,$pageSize)->select();
        foreach ($list as $k => $item) {
            $can_edit = 0;
            //获取出库人、入库人
            $outer_id = $item['out_admin_id'];
            $iner_id = $item['in_admin_id'];
            $outer = \db('user')->where(['id' => $outer_id])->value('user_login');
            $iner = \db('user')->where(['id' => $iner_id])->value('user_login');
            $list[$k]['outer'] = $outer;
            $list[$k]['iner'] = $iner;
            //获取调出仓库名、调入仓库名
            $from_shop = Db::connect(config('ddxx'))->name('shop')->where(['id' => $item['from_shop']])->value('name');
            if($item['from_shop']==$shop_id){
                $can_edit = 1;
            }
            if(in_array($role_id,$except_role_id)||$admin_id==1){
                $can_edit = 1;
            }
            $to_shop = Db::connect(config('ddxx'))->name('shop')->where(['id' => $item['to_shop']])->value('name');
            $list[$k]['from_shop_name'] = $from_shop;
            $list[$k]['to_shop_name'] = $to_shop;
            $list[$k]['can_edit'] = $can_edit;
        }
        $data['allot_list'] = $list;
        $data['totalPage'] = $totalPage;
        $data['totalNum'] = $count;
        $data['pageSize'] = $pageSize;
        //进价总金额
        unset($where['c.title']);
        $bid_amount = (new AllotModel())->get_amount_total($where, 'bid_amount');
        $data['bid_amount_all'] = $bid_amount;
        //成本总金额
        $cost_all = (new AllotModel())->get_amount_total($where, 'cost');
        $data['cost_all'] = $cost_all;
        $data['is_show'] = $is_show;
        return outPut(200, 'success', $data);
    }

    /**
     * @api {Post} /admin/Allot/getPage ajax获取分页商品数据
     * @apiGroup 调拨单管理
     * @apiName getPage
     * @apiVersion 1.0.0
     * @apiDescription  ajax获取分页商品数据
     * @apiParam (请求参数) {str} curPage 页数 不可空
     * @apiParam (请求参数) {str} goods_name 商品名称 可空
     * @apiParam (请求参数) {str} f_cate 一级分类ID 可空
     * @apiParam (请求参数) {str} s_cate 二级分类ID 可空
     * @apiParam (请求参数) {int} shop_id 门店ID 不可空
     * @apiSuccess (返回参数) {Int} code 状态码 200成功 301错误
     * @apiSuccess (返回参数) {str} msg 请求结果 success成功 fail失败
     * @apiSuccess (返回参数) {array} data 返回参数结果集
     * @apiSuccess (返回参数) {int} data.pageSize 每页记录条数
     * @apiSuccess (返回参数) {int} data.totalItem 总记录条数
     * @apiSuccess (返回参数) {int} data.totalPage 总页数
     * @apiSuccess (返回参数) {array} data.data_content 商品数据
     * @apiSuccess (返回参数) {str} data.data_content.item_id 商品ID
     * @apiSuccess (返回参数) {str} data.data_content.title 商品名称
     * @apiSuccess (返回参数) {str} data.data_content.cname 商品分类
     * @apiSuccess (返回参数) {str} data.data_content.bar_code 商品条形码
     * @apiSuccess (返回参数) {str} data.data_content.price 商品原价
     * @apiSuccess (返回参数) {str} data.data_content.md_price 商品门店进价
     * @apiSuccess (返回参数) {str} data.data_content.store_cost 商品入库成本
     * @apiSuccess (返回参数) {str} data.data_content.stock 商品库存
     * @apiSuccessExample {json} 返回样例:
     * {"code":"1","msg":"成功","time":1532416271,"data":{"totalItem":4,"pageSize":10,"totalPage":1,"data_content":[{"id":2,"title":"三鹿三聚氰胺奶粉2段3罐","cname":"洗发沐浴","is_price_control":0,"price":"100.00","bar_code":"654654","cg_standard_price":"666.00","md_standard_price":"555.00","stock":null,"md_price":null,"store_cost":null},{"id":3,"title":"惠氏金装婴儿奶粉 3段2罐","cname":"惠氏","is_price_control":0,"price":"268.00","bar_code":"6615249817","cg_standard_price":"210.00","md_standard_price":"258.00","stock":null,"md_price":null,"store_cost":null},{"id":4,"title":"惠氏启赋金装二段2罐装","cname":"惠氏","is_price_control":0,"price":"359.00","bar_code":"1231431232","cg_standard_price":"309.00","md_standard_price":"339.00","stock":null,"md_price":null,"store_cost":null},{"id":5,"title":"测试商品","cname":"湿巾纸巾","is_price_control":1,"price":"52.00","bar_code":"54779876984","cg_standard_price":"30.00","md_standard_price":"40.00","stock":null,"md_price":null,"store_cost":null}]}}
     * @apiSampleRequest /admin/Allot/getPage
     */
    public function getPage()
    {
        //1.获取数据（curPage）
        $page = input('post.');
//        $page = [
//            'goods_name' => '',
//            's_cate' => '',
//            'curPage' => 1,
//            'shop_id'=>''
//        ];
        $shop_id = Session::get('SHOP_ID');
        $goods_name = $page['goods_name'];
        $s_cate = $page['s_cate'];
        $curPage = $page['curPage'];
        $shopId = $page['shop_id'];
        $bar_code = $page['bar_code'];
        $where = [];
        if ($goods_name) {
            $where['c.title'] = ['like', "%$goods_name%"];
        }
        if ($s_cate) {
            $where['c.type'] = $s_cate;
        }
        if($shopId){
            $shop_id = $shopId;
            $where['b.id'] = $shop_id;
        }
        if ($bar_code) {
            $where['c.bar_code'] = $bar_code;
        }
        $count = (new StockCheckModel())->get_stock_list($where)->count();
        //2.定义分页所需的数据
        $totalItem = $count;   //总记录数(自行定义)
        $pageSize = 10;  //每一页记录数(自行定义)
        $totalPage = ceil($totalItem / $pageSize);  //总页数
        $startItem = ($curPage - 1) * $pageSize;//根据页码来决定查询数据的节点
        //查询数据
        $res = (new StockCheckModel())->get_stock_list($where)->limit($startItem,$pageSize)->select();
        $db = Db::connect(config('ddxx'));
        foreach ($res as $k=>$v){
            unset($res[$k]['id']);
            $stock = $db->name('purchase_price')
                ->where(['shop_id'=>$shop_id,'item_id'=>$v['item_id']])->order('id desc')
                ->field('stock,md_price_after,store_cost_after')->find();
            //实际库存=库存表库存-调拨中库存-退货中库存-线上订单冻结库存
            $allot_stock = $db->name('allot_item')->alias('a')
                ->join('tf_allot b','a.allot_id = b.id','LEFT')
                ->where([ 'a.item_id' => $v['item_id'],'b.from_shop' => $shop_id,'b.status'=>0])->sum('a.num');
            $reject_stock = $db->name('reject_item')->alias('a')
                ->join('tf_reject b','a.reject_id = b.id','LEFT')
                ->where(['a.item_id'=>$v['item_id'],'b.shop_id'=>$shop_id,'b.status'=>0])->sum('a.num');
            $order_tmp_stock = (new ProfitManageModel())->get_order_tmp_ice($shop_id,$v['item_id']);
            $real_stock = $stock['stock']-$allot_stock-$reject_stock-$order_tmp_stock;
//            if($real_stock<=0){
//                unset($res[$k]);
//                continue;
//            }
            $res[$k]['stock'] = $real_stock;
            $res[$k]['md_price'] = $stock['md_price_after'];
            $res[$k]['store_cost'] = $stock['store_cost_after'];
        }
        //4.放入所有数据
        $arr['totalItem'] = $totalItem;
        $arr['pageSize'] = $pageSize;
        $arr['totalPage'] = $totalPage;
        foreach ($res as $lab) {
            $arr['data_content'][] = $lab;
        }
        //5.结果返回（此处没有判定是否查询成功）
        $this->result($arr, 200, '成功', 'json');
    }

    /**
     * @api {POST} /admin/Allot/add 添加调拨单时获取初始化数据
     * @apiGroup 调拨单管理
     * @apiName add
     * @apiVersion 1.0.0
     * @apiDescription 添加调拨单时获取初始化数据
     * @apiSuccess (返回参数) {Int} code 状态码 200成功 301失败
     * @apiSuccess (返回参数) {str} msg 请求结果 success成功 fail失败
     * @apiSuccess (返回参数) {array} data 返回结果集
     * @apiSuccess (返回参数) {str} data.sn 调拨单号
     * @apiSuccess (返回参数) {Array} data.from_shop 调出仓库列表
     * @apiSuccess (返回参数) {str} data.from_shop.id 调出门店（仓库）ID
     * @apiSuccess (返回参数) {str} data.from_shop.name 调出门店（仓库）名
     * @apiSuccess (返回参数) {Array} data.to_shop 调入仓库列表
     * @apiSuccess (返回参数) {str} data.to_shop.id 调入门店（仓库）ID
     * @apiSuccess (返回参数) {str} data.to_shop.name 调入门店（仓库）名
     * @apiSuccess (返回参数) {int} data.time 调拨单时间戳
     * @apiSuccess (返回参数) {Array} data.outer 出库人
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"","data":{"sn":"DB201807240002","from_shop":[{"id":18,"name":"总店"},{"id":6,"name":"爱琴海店"},{"id":5,"name":"留云路店"},{"id":16,"name":"龙湖源著店"},{"id":17,"name":"重庆城口店"},{"id":15,"name":"珠江太阳城店"},{"id":21,"name":"两江时光店"},{"id":22,"name":"测试门店10"},{"id":25,"name":"约克郡南郡"},{"id":24,"name":"约克郡北郡"},{"id":26,"name":"蓝光COCO店"},{"id":29,"name":"融创金茂店"},{"id":28,"name":"港城国际店"},{"id":27,"name":"江与城店"},{"id":30,"name":"麓山别苑店"},{"id":31,"name":"奥山别墅店"}],"to_shop":[{"id":18,"name":"总店"},{"id":6,"name":"爱琴海店"},{"id":5,"name":"留云路店"},{"id":16,"name":"龙湖源著店"},{"id":17,"name":"重庆城口店"},{"id":15,"name":"珠江太阳城店"},{"id":21,"name":"两江时光店"},{"id":22,"name":"测试门店10"},{"id":25,"name":"约克郡南郡"},{"id":24,"name":"约克郡北郡"},{"id":26,"name":"蓝光COCO店"},{"id":29,"name":"融创金茂店"},{"id":28,"name":"港城国际店"},{"id":27,"name":"江与城店"},{"id":30,"name":"麓山别苑店"},{"id":31,"name":"奥山别墅店"}],"time":1532414864,"outer":"admin"}}
     * @apiSampleRequest /admin/Allot/add
     */
    public function add()
    {
        $shop_id = Session::get('SHOP_ID');
        $except_role_id = [1, 6,7,9];//不限制的门店管理员角色
        $admin_id = $this->admin_id;
        $role_id = Db::table('ddxm_role_user')->where('user_id', '=', $admin_id)->value('role_id');
        //获取所有店铺（仓库）数据
        $fromShopDatas = $toShopDatas = Db::connect(config('ddxx'))->name('shop')->field('id,name')->select();
        if (!in_array($role_id, $except_role_id) && $admin_id != 1) {
            $fromShopDatas = Db::connect(config('ddxx'))->name('shop')->field('id,name')->where(['id' => $shop_id,'status'=>['neq',0]])->find();
        }
        //生成调拨单号
        $last_item = Db::connect(config('ddxx'))->name('allot')->field('sn')->order('time desc')->limit(1)->find();
        if (!$last_item) {
            $end_num = '0001';
        } else {
            $last_sn = substr($last_item['sn'], -4);
            $end_num = $newStr = sprintf('%04s', $last_sn + 1);
        }
        $new_sn = 'DB' . date("Ymd", time()) . $end_num;
        //获取操作员工
        $administor = Db::name('user')->where(['id' => $admin_id])->value('user_login');
        $data = [
            'sn' => $new_sn,
            'from_shop' => $fromShopDatas,
            'to_shop' => $toShopDatas,
            'time' => time(),
            'outer' => $administor
        ];
        return outPut(200, 'success', $data);
    }


    /**
     * @api {POST} /admin/Allot/save 保存调拨单信息
     * @apiGroup 调拨单管理
     * @apiName save
     * @apiVersion 1.0.0
     * @apiDescription 保存调拨单信息，有id时为修改，无id时新增
     * @apiParam (请求参数) {int} id 调拨单ID，可以为空
     * @apiParam (请求参数) {array} item_list 调拨单所含商品
     * @apiParam (请求参数) {int} item_list.item_id 商品ID
     * @apiParam (请求参数) {int} item_list.num 商品调拨数量
     * @apiParam (请求参数) {float} item_list.now_md_univalent 调出时商品的门店单价（假成本）
     * @apiParam (请求参数) {float} item_list.now_store_cost 调出时商品的实际成本（真成本）
     * @apiParam (请求参数) {str} item_list.remark 备注
     * @apiParam (请求参数) {int} from_shop 调出门店（仓库）ID
     * @apiParam (请求参数) {int} to_shop 调入门店（仓库）ID
     * @apiParam (请求参数) {str} sn 调拨单编号
     * @apiParam (请求参数) {str} remark 备注
     * @apiParamExample  {json} 请求样例:
     * {"item_list":[{"item_id":2,"num":2,"now_md_univalent":8,"now_store_cost":4,"remark":"\u8c03\u62e8\u5907\u6ce85"},{"item_id":3,"num":3,"now_md_univalent":10,"now_store_cost":7,"remark":"\u8c03\u62e8\u5907\u6ce86"}],"from_shop":5,"to_shop":17,"time":"1531729167","sn":"DB201807190001","id":"2","remark":"\u603b\u5907\u6ce81"}
     * @apiSuccess (返回参数) {Int} code 状态码 200成功 301失败 302库存发生变化刷新页面重试
     * @apiSuccess (返回参数) {str} msg 请求结果 success成功 fail失败
     * @apiSuccess (返回参数) {str} data 请求结果信息
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"success","data":[]}
     * @apiSampleRequest /admin/Allot/save
     */
    public function save1()
    {
//        $arr = [
//            'item_list' => [
//                ['item_id' => 2, 'num' => 2, 'now_md_univalent' => 6.0000, 'now_store_cost' => 4.0000,'remark'=>'调拨备注5'],
//                ['item_id' => 3, 'num' => 3, 'now_md_univalent' => 8.0000, 'now_store_cost' => 7.0000,'remark'=>'调拨备注6']
//            ],
//            'from_shop' => 5,
//            'to_shop' => 17,
//            'time' => '1531729167',
//            'sn' => 'DB201807190001',
//            'id' => '2',
//            'remark'=>'总备注'
//        ];
//        $data = $arr;
        $data = input('post.');
        $id = $data['id'];
        $from_shop = $data['from_shop'];
        $to_shop = $data['to_shop'];
        $sn = $data['sn'];
        $item_list = $data['item_list'];
        $remark = $data['remark'];
        $db = Db::connect(config('ddxx'));
        $bid_amount = 0;
        $store_cost = 0;
        $fail = 0;
        foreach ($item_list as $k => $v) {
            $bid_amount += $v['now_md_univalent'] * $v['num'];
            $store_cost += $v['now_store_cost'] * $v['num'];
        }
        $allot_data = [
            'sn' => $sn,
            'from_shop' => $from_shop,
            'to_shop' => $to_shop,
            'out_admin_id' => $this->admin_id,
            'bid_amount' => $bid_amount,
            'cost' => $store_cost,
            'time' => time(),
            'remark'=>$remark
        ];
//        dump($item_list);exit;
        $new_data = [];

        $db->startTrans();
        if($id){
            unset($allot_data['sn']);
            $isset = $db->name('allot')->where(['id'=>$id])->field('id,status')->find();
            if(!$isset){
                return outPut(301,'fail','没有这条数据');
            }
            if($isset['status']==1){
                return outPut(301,'fail','该调拨单已完成，不能再进行编辑！');
            }
            //检查库存是否变化、调拨量不能大于库存量
            foreach ($item_list as $k=>$v){
                $now_stock = $db->name('purchase_price')->where(['shop_id'=>$from_shop,'item_id'=>$v['item_id']])->order('id desc')->find();
                $on_allot_stock = $db->name('allot_item')->alias('a')
                    ->join('tf_allot b','a.allot_id = b.id','LEFT')
                    ->where(['a.shop_id'=>$from_shop,'a.item_id'=>$v['item_id'],'b.status'=>0])->sum('a.num');
                $on_reject_stock = $db->name('reject_item')->alias('a')
                    ->join('tf_reject b','a.reject_id = b.id','LEFT')
                    ->where(['b.shop_id'=>$from_shop,'a.item_id'=>$v['item_id'],'b.status'=>0])->sum('a.num');
                $order_tmp_stock = (new ProfitManageModel())->get_order_tmp_ice($from_shop,$v['item_id']);
                $now_real_stock = $now_stock['stock']-$on_allot_stock-$on_reject_stock-$order_tmp_stock;
                //如果调拨库存大于现有库存，从数组中删除该条商品信息，重新查询该条商品库存、成本信息加入数组中
                if($v['num']>$now_real_stock+$on_allot_stock){
                    unset($item_list[$k]);
                    $new_data[] = [
                        'item_id'=>$v['item_id'],
                        'stock'=>$now_real_stock,
                        'md_price'=>$now_stock['md_price_after'],
                        'store_cost'=>$now_stock['store_cost_after']
                    ];
                }
            }
            if($new_data!=null){
                return outPut(302,'fail',$new_data);
                exit;
            }
            try{
                foreach ($item_list as $k=>$v){
                    $item_list[$k]['allot_id'] = $id;
                    $item_list[$k]['shop_id'] = $from_shop;
                    //修改shop_item表中的库存
                    //冻结中的库存 = 更新前的冻结中库存-该调拨单据原冻结的库存+本次提交的调拨数量
                    $allot_ice_now = $db->name('allot_item')->where(['allot_id'=>$id,'shop_id'=>$from_shop,'item_id'=>$v['item_id']])->value('num');//现已冻结的调拨中库存
                    $stock_ice_all = $db->name('shop_item')->where(['shop_id'=>$from_shop,'item_id'=>$v['item_id']])->value('stock_ice');
                    $ress = $db->name('shop_item')->where(['shop_id'=>$from_shop,'item_id'=>$v['item_id']])->update(['stock_ice'=>$stock_ice_all-$allot_ice_now+$v['num']]);
                    if(!$ress){
                        $fail+=1;
                    }
                }
                $res = $db->name('allot')->where(['id'=>$id])->update($allot_data);
                $res2 = $db->name('allot_item')->where(['allot_id'=>$id])->delete();
                $res3 = $db->name('allot_item')->insertAll($item_list);
                $db->commit();
                return outPut(200,'success','保存成功');
            }catch (\Exception $e){
                $db->rollback();
                return outPut(301,'fail',$e->getMessage());
            }
        }else{
            //检查库存是否变化、调拨量不能大于库存量
            foreach ($item_list as $k=>$v){
                $now_stock = $db->name('purchase_price')->where(['shop_id'=>$from_shop,'item_id'=>$v['item_id']])->order('id desc')->find();
                $on_allot_stock = $db->name('allot_item')->alias('a')
                    ->join('tf_allot b','a.allot_id = b.id','LEFT')
                    ->where(['a.shop_id'=>$from_shop,'a.item_id'=>$v['item_id'],'b.status'=>0])->sum('a.num');
                $on_reject_stock = $db->name('reject_item')->alias('a')
                    ->join('tf_reject b','a.reject_id = b.id','LEFT')
                    ->where(['b.shop_id'=>$from_shop,'a.item_id'=>$v['item_id'],'b.status'=>0])->sum('a.num');
                $order_tmp_stock = (new ProfitManageModel())->get_order_tmp_ice($from_shop,$v['item_id']);
                $now_real_stock = $now_stock['stock']-$on_allot_stock-$on_reject_stock-$order_tmp_stock;
                //如果调拨库存大于现有库存，从数组中删除该条商品信息，重新查询该条商品库存、成本信息加入数组中
                if($v['num']>$now_real_stock+$on_allot_stock){
                    unset($item_list[$k]);
                    $new_data[] = [
                        'item_id'=>$v['item_id'],
                        'stock'=>$now_real_stock,
                        'md_price'=>$now_stock['md_price_after'],
                        'store_cost'=>$now_stock['store_cost_after']
                    ];
                }
            }
            if($new_data!=null){
                return outPut(302,'fail',$new_data);
                exit;
            }
            try {
                //生成调拨单号
                $last_item = Db::connect(config('ddxx'))->name('allot')->field('sn')->order('time desc')->limit(1)->find();
                if (!$last_item) {
                    $end_num = '0001';
                } else {
                    $last_sn = substr($last_item['sn'], -4);
                    $end_num = $newStr = sprintf('%04s', $last_sn + 1);
                }
                $new_sn = 'DB' . date("Ymd", time()) . $end_num;
                $allot_data['sn'] = $new_sn;
                $allot = $db->name('allot')->insert($allot_data);
                if ($allot) {
                    $allot_id = $db->name('purchase')->getLastInsID();
                    foreach ($item_list as $k=>$v){
                        $item_list[$k]['allot_id'] = $allot_id;
                        $item_list[$k]['shop_id'] = $from_shop;
                        $ress = $db->name('shop_item')->where(['shop_id'=>$from_shop,'item_id'=>$v['item_id']])->setInc('stock_ice',$v['num']);
                    }
                    $res = $db->name('allot_item')->insertAll($item_list);
                    if(!$res||!$ress){
                        $fail+=1;
                    }
                }else{
                    $fail+=1;
                }
                if($fail==0){
                    $db->commit();
                    return outPut(200,'success','保存成功');
                }
            } catch (\Exception $e) {
                $db->rollback();
                return outPut(301,'fail',$e->getMessage());
            }
        }
    }
    public function save()
    {
//        $arr = [
//            'item_list' => [
//                ['item_id' => 2, 'num' => 2, 'now_md_univalent' => 6.0000, 'now_store_cost' => 4.0000,'remark'=>'调拨备注5'],
//                ['item_id' => 3, 'num' => 3, 'now_md_univalent' => 8.0000, 'now_store_cost' => 7.0000,'remark'=>'调拨备注6']
//            ],
//            'from_shop' => 5,
//            'to_shop' => 17,
//            'time' => '1531729167',
//            'sn' => 'DB201807190001',
//            'id' => '2',
//            'remark'=>'总备注'
//        ];
//        $data = $arr;
        $data = input('post.');
        $id = $data['id'];
        $from_shop = $data['from_shop'];
        $to_shop = $data['to_shop'];
        //生成调拨单号
        $last_item = Db::connect(config('ddxx'))->name('allot')->field('sn')->order('id desc')->limit(1)->find();
        if (!$last_item) {
            $end_num = '0001';
        } else {
            $last_sn = substr($last_item['sn'], -4);
            $end_num = $newStr = sprintf('%04s', $last_sn + 1);
        }
        $new_sn = 'DB' . date("Ymd", time()) . $end_num;
        $sn = $new_sn;
        $item_list = $data['item_list'];
        $remark = $data['remark'];
        $db = Db::connect(config('ddxx'));
        $bid_amount = 0;
        $store_cost = 0;
        $fail = 0;
        foreach ($item_list as $k => $v) {
            $bid_amount += $v['now_md_univalent'] * $v['num'];
            $store_cost += $v['now_store_cost'] * $v['num'];
        }
        $special_shop_arr = [18];
        $from_shop_name = $db->name('shop')->where(['id'=>$from_shop])->value('name');
        $to_shop_name = $db->name('shop')->where(['id'=>$to_shop])->value('name');
        $allot_data = [
            'sn' => $sn,
            'from_shop' => $from_shop,
            'to_shop' => $to_shop,
            'out_admin_id' => $this->admin_id,
            'bid_amount' => $bid_amount,
            'cost' => $store_cost,
            'time' => time(),
            'remark'=>$remark
        ];
//        dump($item_list);exit;
        $new_data = [];

        $db->startTrans();
        if($id){
            unset($allot_data['sn']);
            $isset = $db->name('allot')->where(['id'=>$id])->field('id,status,type')->find();
            if ($isset['type']==2) {
                return outPut(301,'fail','特殊调拨单，不允许编辑');
            }
            if(!$isset){
                return outPut(301,'fail','没有这条数据');
            }
            if($isset['status']==1){
                return outPut(301,'fail','该调拨单已完成，不能再进行编辑！');
            }
            //检查库存是否变化、调拨量不能大于库存量
            foreach ($item_list as $k=>$v){
                $now_stock = $db->name('purchase_price')->where(['shop_id'=>$from_shop,'item_id'=>$v['item_id']])->order('id desc')->find();
                $on_allot_stock = $db->name('allot_item')->alias('a')
                    ->join('tf_allot b','a.allot_id = b.id','LEFT')
                    ->where(['a.shop_id'=>$from_shop,'a.item_id'=>$v['item_id'],'b.status'=>0])->sum('a.num');
                $on_reject_stock = $db->name('reject_item')->alias('a')
                    ->join('tf_reject b','a.reject_id = b.id','LEFT')
                    ->where(['b.shop_id'=>$from_shop,'a.item_id'=>$v['item_id'],'b.status'=>0])->sum('a.num');
                $order_tmp_stock = (new ProfitManageModel())->get_order_tmp_ice($from_shop,$v['item_id']);
                $now_real_stock = $now_stock['stock']-$on_allot_stock-$on_reject_stock-$order_tmp_stock;
                //如果调拨库存大于现有库存，从数组中删除该条商品信息，重新查询该条商品库存、成本信息加入数组中
                if($v['num']>$now_real_stock+$on_allot_stock){
                    unset($item_list[$k]);
                    $new_data[] = [
                        'item_id'=>$v['item_id'],
                        'stock'=>$now_real_stock,
                        'md_price'=>$now_stock['md_price_after'],
                        'store_cost'=>$now_stock['store_cost_after']
                    ];
                }
            }
            if($new_data!=null){
                return outPut(302,'fail',$new_data);
                exit;
            }
            try{
                foreach ($item_list as $k=>$v){
                    $item_list[$k]['allot_id'] = $id;
                    $item_list[$k]['shop_id'] = $from_shop;
                    //修改shop_item表中的库存
                    //冻结中的库存 = 更新前的冻结中库存-该调拨单据原冻结的库存+本次提交的调拨数量
                    $allot_ice_now = $db->name('allot_item')->where(['allot_id'=>$id,'shop_id'=>$from_shop,'item_id'=>$v['item_id']])->value('num');//现已冻结的调拨中库存
                    $stock_ice_all = $db->name('shop_item')->where(['shop_id'=>$from_shop,'item_id'=>$v['item_id']])->value('stock_ice');
                    $ress = $db->name('shop_item')->where(['shop_id'=>$from_shop,'item_id'=>$v['item_id']])->update(['stock_ice'=>$stock_ice_all-$allot_ice_now+$v['num']]);
                    if(!$ress){
                        $fail+=1;
                    }
                }
                $res = $db->name('allot')->where(['id'=>$id])->update($allot_data);
                $res2 = $db->name('allot_item')->where(['allot_id'=>$id])->delete();
                $res3 = $db->name('allot_item')->insertAll($item_list);
                $db->commit();
                return outPut(200,'success','保存成功');
            }catch (\Exception $e){
                $db->rollback();
                return outPut(301,'fail',$e->getMessage());
            }
        }else{
            //检查库存是否变化、调拨量不能大于库存量
            foreach ($item_list as $k=>$v){
                $now_stock = $db->name('purchase_price')->where(['shop_id'=>$from_shop,'item_id'=>$v['item_id']])->order('id desc')->find();
                $on_allot_stock = $db->name('allot_item')->alias('a')
                    ->join('tf_allot b','a.allot_id = b.id','LEFT')
                    ->where(['a.shop_id'=>$from_shop,'a.item_id'=>$v['item_id'],'b.status'=>0])->sum('a.num');
                $on_reject_stock = $db->name('reject_item')->alias('a')
                    ->join('tf_reject b','a.reject_id = b.id','LEFT')
                    ->where(['b.shop_id'=>$from_shop,'a.item_id'=>$v['item_id'],'b.status'=>0])->sum('a.num');
                $order_tmp_stock = (new ProfitManageModel())->get_order_tmp_ice($from_shop,$v['item_id']);
                $now_real_stock = $now_stock['stock']-$on_allot_stock-$on_reject_stock-$order_tmp_stock;
                //如果调拨库存大于现有可用库存，从数组中删除该条商品信息，重新查询该条商品库存、成本信息加入数组中
                if($v['num']>$now_real_stock+$on_allot_stock){
                    unset($item_list[$k]);
                    $new_data[] = [
                        'item_id'=>$v['item_id'],
                        'stock'=>$now_real_stock,
                        'md_price'=>$now_stock['md_price_after'],
                        'store_cost'=>$now_stock['store_cost_after']
                    ];
                }
            }
            if($new_data!=null){
                return outPut(302,'调拨失败，调拨清单中有商品库存发生变化！',$new_data);
                exit;
            }
            //调出门店或调入门店是总店按正常流程操作
            if(in_array($to_shop,$special_shop_arr)||in_array($from_shop,$special_shop_arr)){
                foreach ($item_list as $k=>$v){
                    $now_stock = $db->name('purchase_price')->where(['shop_id'=>$from_shop,'item_id'=>$v['item_id']])->order('id desc')->find();
                    $on_allot_stock = $db->name('allot_item')->alias('a')
                        ->join('tf_allot b','a.allot_id = b.id','LEFT')
                        ->where(['a.shop_id'=>$from_shop,'a.item_id'=>$v['item_id'],'b.status'=>0])->sum('a.num');
                    $on_reject_stock = $db->name('reject_item')->alias('a')
                        ->join('tf_reject b','a.reject_id = b.id','LEFT')
                        ->where(['b.shop_id'=>$from_shop,'a.item_id'=>$v['item_id'],'b.status'=>0])->sum('a.num');
                    $order_tmp_stock = (new ProfitManageModel())->get_order_tmp_ice($from_shop,$v['item_id']);
                    $now_real_stock = $now_stock['stock']-$on_allot_stock-$on_reject_stock-$order_tmp_stock;
                    //如果调拨库存大于现有库存，从数组中删除该条商品信息，重新查询该条商品库存、成本信息加入数组中
                    if($v['num']>$now_real_stock+$on_allot_stock){
                        unset($item_list[$k]);
                        $new_data[] = [
                            'item_id'=>$v['item_id'],
                            'stock'=>$now_real_stock,
                            'md_price'=>$now_stock['md_price_after'],
                            'store_cost'=>$now_stock['store_cost_after']
                        ];
                    }
                }
                if($new_data!=null){
                    return outPut(302,'fail',$new_data);
                    exit;
                }
                try {
                    //生成调拨单号
                    $last_item = Db::connect(config('ddxx'))->name('allot')->field('sn')->order('id desc')->limit(1)->find();
                    if (!$last_item) {
                        $end_num = '0001';
                    } else {
                        $last_sn = substr($last_item['sn'], -4);
                        $end_num = $newStr = sprintf('%04s', $last_sn + 1);
                    }
                    $new_sn = 'DB' . date("Ymd", time()) . $end_num;
                    $allot_data['sn'] = $new_sn;
                    $allot = $db->name('allot')->insert($allot_data);
                    if ($allot) {
                        $allot_id = $db->name('allot')->getLastInsID();
                        foreach ($item_list as $k=>$v){
                            $item_list[$k]['allot_id'] = $allot_id;
                            $item_list[$k]['shop_id'] = $from_shop;
                            $ress = $db->name('shop_item')->where(['shop_id'=>$from_shop,'item_id'=>$v['item_id']])->setInc('stock_ice',$v['num']);
                        }
                        $res = $db->name('allot_item')->insertAll($item_list);
                        if(!$res||!$ress){
                            $fail+=1;
                        }
                    }else{
                        $fail+=1;
                    }
                    if($fail==0){
                        $db->commit();
                        return outPut(200,'success','保存成功');
                    }
                } catch (\Exception $e) {
                    $db->rollback();
                    return outPut(301,'fail',$e->getMessage());
                }

            }else{
            //如果是门店之间的调拨，自动新增门店A->总店->门店B的调拨单数据，并重新计算库存成本
                try {
                    //生成同一个随机码标记两笔调拨单以便删除
                    $union_code = time().rand(1000,9999);
                    //生成调拨单号,新增门店A->总店的调拨单
                    $first_allot_data = [
                        'sn' => $sn,
                        'from_shop' => $from_shop,
                        'to_shop' => 18,//总店ID
                        'out_admin_id' => $this->admin_id,
                        'bid_amount' => $bid_amount,
                        'cost' => $store_cost,
                        'time' => time(),
                        'remark'=>'('.$from_shop_name.'->'.$to_shop_name.')'.$remark,
                        'type'=>2,
                        'union_code'=>$union_code
                    ];
                    $last_item = Db::connect(config('ddxx'))->name('allot')->field('sn')->order('id desc')->limit(1)->find();
                    if (!$last_item) {
                        $end_num = '0001';
                    } else {
                        $last_sn = substr($last_item['sn'], -4);
                        $end_num = $newStr = sprintf('%04s', $last_sn + 1);
                    }
                    $new_sn = 'DB' . date("Ymd", time()) . $end_num;
                    $first_allot_data['sn'] = $new_sn;
                    $allot_id= $db->name('allot')->insertGetId($first_allot_data);
                    if ($allot_id) {
                        foreach ($item_list as $k=>$v){
                            $item_list[$k]['allot_id'] = $allot_id;
                            $item_list[$k]['shop_id'] = $from_shop;
                        }
                        $res = $db->name('allot_item')->insertAll($item_list);
                        if(!$res){
                            $fail+=1;
                        }
                    }else{
                        $fail+=1;
                    }
                    //入库操作
                    $allot = (new AllotModel())->get_allot_info($allot_id);
                    $insert_to_data = [];
                    $insert_from_data = [];
                    foreach ($allot['allotItemInfo'] as $k=>$v){
                        //某商品库存单位成本=（原库存商品单位成本*库存内该商品数量+本次该商品入库成本）/（库存内该商品数量+本次入库数量）
                        $stock_info = $db->name('purchase_price')
                            ->where(['shop_id'=>$allot['allotInfo']['to_shop'],'item_id'=>$v['item_id']])
                            ->order('id desc')->find();
                        $from_stock_info = $db->name('purchase_price')
                            ->where(['shop_id'=>$allot['allotInfo']['from_shop'],'item_id'=>$v['item_id']])
                            ->order('id desc')->find();
                        //计算假成本
                        $mom = $stock_info['md_price_after']*$stock_info['stock']+$v['md_price']*$v['num'];
                        $son = $stock_info['stock']+$v['num'];
                        $md_price_before = $stock_info['md_price_after'];
                        $md_price_after = $mom/$son;
                        //计算真实库存成本
                        $r_mom = $stock_info['store_cost_after']*$stock_info['stock']+$v['store_cost']*$v['num'];
                        $store_cost_before = $stock_info['store_cost_after'];//最近一次商品的入库后成本等于本次商品的入库前成本
                        $store_cost_after = $r_mom/$son;
                        //添加调入仓商品成本变化数据，增加调入仓库库存
                        $insert_to_data[] = [
                            'shop_id'=>$allot['allotInfo']['to_shop'],
                            'type'=>2,
                            'pd_id'=>$allot_id,
                            'item_id'=>$v['item_id'],
                            'md_price_before'=>$md_price_before?$md_price_before:0,
                            'md_price_after'=>$md_price_after,
                            'store_cost_before'=>$store_cost_before?$store_cost_before:0,
                            'store_cost_after'=>$store_cost_after,
                            'stock'=>$stock_info['stock']+$v['num'],
                            'time'=>time()
                        ];
                        //添加调出仓商品成本变化数据，减少调出仓库库存
                        $insert_from_data[] = [
                            'shop_id'=>$allot['allotInfo']['from_shop'],
                            'type'=>2,
                            'pd_id'=>$allot_id,
                            'item_id'=>$v['item_id'],
                            'md_price_before'=>$from_stock_info['md_price_before']?$from_stock_info['md_price_before']:0,
                            'md_price_after'=>$from_stock_info['md_price_after'],
                            'store_cost_before'=>$from_stock_info['store_cost_before']?$from_stock_info['store_cost_before']:0,
                            'store_cost_after'=>$from_stock_info['store_cost_after'],
                            'stock'=>$from_stock_info['stock']-$v['num'],
                            'time'=>time()
                        ];
                        if($from_stock_info['stock']-$v['num']<0){
                            $fail+=1;
                        }
                    }
                    if (!($db->name('purchase_price')->insertAll($insert_to_data))) {
                        $fail+=1;
                    }
                    if (!($db->name('purchase_price')->insertAll($insert_from_data))) {
                        $fail+=1;
                    }
                    $allot_item_list = $db->name('allot_item')->where(['allot_id'=>$allot_id])->select();
                    foreach ($allot_item_list as $a=>$b){
                        $info = $db->name('shop_item')->where(['shop_id'=>18,'item_id'=>$b['item_id']])->find();
                        //增加shop_item表调入仓库中商品的实际库存
                        //shop_item中无该商品数据则新增一条数据，有则增加该商品库存
                        if(empty($info)){
                            if (!($db->name('shop_item')->insert(['shop_id'=>18,'item_id'=>$b['item_id'],'stock'=>$b['num']]))) {
                                $fail+=1;
                            }
                        }else{
                            if (!($db->name('shop_item')->where(['shop_id'=>18,'item_id'=>$b['item_id']])->setInc('stock',$b['num']))) {
                                $fail+=1;
                            }
                        }
                        //减少调出仓库的库存
                        $from_shop_info = $db->name('shop_item')->where(['shop_id'=>$allot['allotInfo']['from_shop'],'item_id'=>$b['item_id']])->find();
                        if (!($db->name('shop_item')->where(['shop_id'=>$allot['allotInfo']['from_shop'],'item_id'=>$b['item_id']])->update(['stock'=>$from_shop_info['stock']-$b['num']]))) {
                            $fail+=1;
                        }
                    }
                    //修改A->总的调拨单状态
                    if (!($db->name('allot')->where(['id'=>$allot_id])->update(['status'=>1,'in_admin_id'=>$this->admin_id,'in_time'=>time()]))) {
                        $fail+=1;
                    }
                    //调出仓商品库存变化之后添加库存预警信息
                    foreach ($insert_from_data as $vv){
                        (new StockModel())->updateStockAlert($vv['item_id'],$vv['shop_id'],$vv['stock']);
                    }
                    //新增总店->门店B的调拨单
                    $last_item = Db::connect(config('ddxx'))->name('allot')->field('sn')->order('id desc')->limit(1)->find();
                    if (!$last_item) {
                        $end_num = '0001';
                    } else {
                        $last_sn = substr($last_item['sn'], -4);
                        $end_num = $newStr = sprintf('%04s', $last_sn + 1);
                    }
                    $second_sn = 'DB' . date("Ymd", time()) . $end_num;
                    $second_bid_amount = 0;
                    $second_store_cost = 0;
                    foreach ($item_list as $k=>$v){
                        $second_bid_amount += $md_price_after*$v['num'];
                        $second_store_cost += $store_cost_after*$v['num'];
                    }
                    $second_allot_data = [
                        'sn' => $second_sn,
                        'from_shop' => 18,//总店ID
                        'to_shop' => $to_shop,
                        'out_admin_id' => $this->admin_id,
                        'bid_amount' => $second_bid_amount,
                        'cost' => $second_store_cost,
                        'time' => time(),
                        'remark'=>'('.$from_shop_name.'->'.$to_shop_name.')'.$remark,
                        'type'=>2,
                        'union_code'=>$union_code
                    ];
                    $second_insert_id = $db->name('allot')->insertGetId($second_allot_data);
                    if ($second_insert_id) {
                        foreach ($item_list as $k=>$v){
                            $item_list[$k]['allot_id'] = $second_insert_id;
                            $item_list[$k]['shop_id'] = 18;
                            //获取当前假成本和真成本
                            $current_stock_info = $db->name('purchase_price')->where(['shop_id'=>18,'item_id'=>$v['item_id']])->field('md_price_after,store_cost_after')->order('id desc')->limit(1)->find();
                            $item_list[$k]['now_md_univalent'] = $current_stock_info['md_price_after'];
                            $item_list[$k]['now_store_cost'] = $current_stock_info['store_cost_after'];
                            if (!($db->name('shop_item')->where(['shop_id'=>18,'item_id'=>$v['item_id']])->setInc('stock_ice',$v['num']))) {
                                $fail+=1;
                            }
                        }
                        if (!($db->name('allot_item')->insertAll($item_list))) {
                            $fail+=1;
                        }
                    }else{
                        $fail+=1;
                    }
                    if($fail==0){
                        $db->commit();
                        return outPut(200,'success','保存成功');
                    }else{
                        $db->rollback();
                        return outPut(301,'fail');
                    }
                } catch (\Exception $e) {
                    $db->rollback();
                    return outPut(301,'fail',$e->getMessage());
                }
            }
//            if (!in_array($to_shop,$special_shop_arr)) {
//                try {
//                    //生成调拨单号,新增门店A->总店的调拨单
//                    $first_allot_data = [
//                        'sn' => $sn,
//                        'from_shop' => $from_shop,
//                        'to_shop' => 18,//总店ID
//                        'out_admin_id' => $this->admin_id,
//                        'bid_amount' => $bid_amount,
//                        'cost' => $store_cost,
//                        'time' => time(),
//                        'remark'=>'【'.$from_shop_name.'->'.$to_shop_name.'】'.$remark,
//                        'type'=>2
//                    ];
//                    $last_item = Db::connect(config('ddxx'))->name('allot')->field('sn')->order('time desc')->limit(1)->find();
//                    if (!$last_item) {
//                        $end_num = '0001';
//                    } else {
//                        $last_sn = substr($last_item['sn'], -4);
//                        $end_num = $newStr = sprintf('%04s', $last_sn + 1);
//                    }
//                    $new_sn = 'DB' . date("Ymd", time()) . $end_num;
//                    $first_allot_data['sn'] = $new_sn;
//                    $allot_id= $db->name('allot')->insertGetId($first_allot_data);
//                    if ($allot_id) {
//                        foreach ($item_list as $k=>$v){
//                            $item_list[$k]['allot_id'] = $allot_id;
//                            $item_list[$k]['shop_id'] = $from_shop;
//                        }
//                        $res = $db->name('allot_item')->insertAll($item_list);
//                        if(!$res){
//                            $fail+=1;
//                        }
//                    }else{
//                        $fail+=1;
//                    }
//                    //入库操作
//                    $allot = (new AllotModel())->get_allot_info($allot_id);
//                    $insert_to_data = [];
//                    $insert_from_data = [];
//                    foreach ($allot['allotItemInfo'] as $k=>$v){
//                        //某商品库存单位成本=（原库存商品单位成本*库存内该商品数量+本次该商品入库成本）/（库存内该商品数量+本次入库数量）
//                        $stock_info = $db->name('purchase_price')
//                            ->where(['shop_id'=>$allot['allotInfo']['to_shop'],'item_id'=>$v['item_id']])
//                            ->order('id desc')->find();
//                        $from_stock_info = $db->name('purchase_price')
//                            ->where(['shop_id'=>$allot['allotInfo']['from_shop'],'item_id'=>$v['item_id']])
//                            ->order('id desc')->find();
//                        //计算假成本
//                        $mom = $stock_info['md_price_after']*$stock_info['stock']+$v['md_price']*$v['num'];
//                        $son = $stock_info['stock']+$v['num'];
//                        $md_price_before = $stock_info['md_price_after'];
//                        $md_price_after = $mom/$son;
//                        //计算真实库存成本
//                        $r_mom = $stock_info['store_cost_after']*$stock_info['stock']+$v['store_cost']*$v['num'];
//                        $store_cost_before = $stock_info['store_cost_after'];//最近一次商品的入库后成本等于本次商品的入库前成本
//                        $store_cost_after = $r_mom/$son;
//                        //添加调入仓商品成本变化数据，增加调入仓库库存
//                        $insert_to_data[] = [
//                            'shop_id'=>$allot['allotInfo']['to_shop'],
//                            'type'=>2,
//                            'pd_id'=>$allot_id,
//                            'item_id'=>$v['item_id'],
//                            'md_price_before'=>$md_price_before?$md_price_before:0,
//                            'md_price_after'=>$md_price_after,
//                            'store_cost_before'=>$store_cost_before?$store_cost_before:0,
//                            'store_cost_after'=>$store_cost_after,
//                            'stock'=>$stock_info['stock']+$v['num'],
//                            'time'=>time()
//                        ];
//                        //添加调出仓商品成本变化数据，减少调出仓库库存
//                        $insert_from_data[] = [
//                            'shop_id'=>$allot['allotInfo']['from_shop'],
//                            'type'=>2,
//                            'pd_id'=>$allot_id,
//                            'item_id'=>$v['item_id'],
//                            'md_price_before'=>$from_stock_info['md_price_before']?$from_stock_info['md_price_before']:0,
//                            'md_price_after'=>$from_stock_info['md_price_after'],
//                            'store_cost_before'=>$from_stock_info['store_cost_before']?$from_stock_info['store_cost_before']:0,
//                            'store_cost_after'=>$from_stock_info['store_cost_after'],
//                            'stock'=>$from_stock_info['stock']-$v['num'],
//                            'time'=>time()
//                        ];
//                        if($from_stock_info['stock']-$v['num']<0){
//                            $fail+=1;
//                        }
//                    }
//                    if (!($db->name('purchase_price')->insertAll($insert_to_data))) {
//                        $fail+=1;
//                    }
//                    if (!($db->name('purchase_price')->insertAll($insert_from_data))) {
//                        $fail+=1;
//                    }
//                    $allot_item_list = $db->name('allot_item')->where(['allot_id'=>$allot_id])->select();
//                    foreach ($allot_item_list as $a=>$b){
//                        $info = $db->name('shop_item')->where(['shop_id'=>18,'item_id'=>$b['item_id']])->find();
//                        //增加shop_item表调入仓库中商品的实际库存
//                        //shop_item中无该商品数据则新增一条数据，有则增加该商品库存
//                        if(empty($info)){
//                            if (!($db->name('shop_item')->insert(['shop_id'=>18,'item_id'=>$b['item_id'],'stock'=>$b['num']]))) {
//                                $fail+=1;
//                            }
//                        }else{
//                            if (!($db->name('shop_item')->where(['shop_id'=>18,'item_id'=>$b['item_id']])->setInc('stock',$b['num']))) {
//                                $fail+=1;
//                            }
//                        }
//                        //减少调出仓库的库存
//                        $from_shop_info = $db->name('shop_item')->where(['shop_id'=>$allot['allotInfo']['from_shop'],'item_id'=>$b['item_id']])->find();
//                        if (!($db->name('shop_item')->where(['shop_id'=>$allot['allotInfo']['from_shop'],'item_id'=>$b['item_id']])->update(['stock'=>$from_shop_info['stock']-$b['num']]))) {
//                            $fail+=1;
//                        }
//                    }
//                    //修改A->总的调拨单状态
//                    if (!($db->name('allot')->where(['id'=>$allot_id])->update(['status'=>1,'in_admin_id'=>$this->admin_id,'in_time'=>time()]))) {
//                        $fail+=1;
//                    }
//                    //调出仓商品库存变化之后添加库存预警信息
//                    foreach ($insert_from_data as $vv){
//                        (new StockModel())->updateStockAlert($vv['item_id'],$vv['shop_id'],$vv['stock']);
//                    }
//                    //新增总店->门店B的调拨单
//                    $last_item = Db::connect(config('ddxx'))->name('allot')->field('sn')->order('time desc')->limit(1)->find();
//                    if (!$last_item) {
//                        $end_num = '0001';
//                    } else {
//                        $last_sn = substr($last_item['sn'], -4);
//                        $end_num = $newStr = sprintf('%04s', $last_sn + 1);
//                    }
//                    $second_sn = 'DB' . date("Ymd", time()) . $end_num;
//                    $second_bid_amount = 0;
//                    $second_store_cost = 0;
//                    foreach ($item_list as $k=>$v){
//                        $second_bid_amount += $md_price_after*$v['num'];
//                        $second_store_cost += $store_cost_after*$v['num'];
//                    }
//                    $second_allot_data = [
//                        'sn' => $second_sn,
//                        'from_shop' => 18,//总店ID
//                        'to_shop' => $to_shop,
//                        'out_admin_id' => $this->admin_id,
//                        'bid_amount' => $second_bid_amount,
//                        'cost' => $second_store_cost,
//                        'time' => time(),
//                        'remark'=>'【'.$from_shop_name.'->'.$to_shop_name.'】'.$remark,
//                        'type'=>2
//                    ];
//                    $second_insert_id = $db->name('allot')->insertGetId($second_allot_data);
//                    if ($second_insert_id) {
//                        foreach ($item_list as $k=>$v){
//                            $item_list[$k]['allot_id'] = $second_insert_id;
//                            $item_list[$k]['shop_id'] = 18;
//                            //获取当前假成本和真成本
//                            $current_stock_info = $db->name('purchase_price')->where(['shop_id'=>18,'item_id'=>$v['item_id']])->field('md_price_after,store_cost_after')->order('id desc')->limit(1)->find();
//                            $item_list[$k]['now_md_univalent'] = $current_stock_info['md_price_after'];
//                            $item_list[$k]['now_store_cost'] = $current_stock_info['store_cost_after'];
//                            if (!($db->name('shop_item')->where(['shop_id'=>18,'item_id'=>$v['item_id']])->setInc('stock_ice',$v['num']))) {
//                                $fail+=1;
//                            }
//                        }
//                        if (!($db->name('allot_item')->insertAll($item_list))) {
//                            $fail+=1;
//                        }
//                    }else{
//                        $fail+=1;
//                    }
//                    if($fail==0){
//                        $db->commit();
//                        return outPut(200,'success','保存成功');
//                    }else{
//                        $db->rollback();
//                        return outPut(301,'fail');
//                    }
//                } catch (\Exception $e) {
//                    $db->rollback();
//                    return outPut(301,'fail',$e->getMessage());
//                }
//            }else{
//            //门店调拨到总店的情况
//                foreach ($item_list as $k=>$v){
//                    $now_stock = $db->name('purchase_price')->where(['shop_id'=>$from_shop,'item_id'=>$v['item_id']])->order('id desc')->find();
//                    $on_allot_stock = $db->name('allot_item')->alias('a')
//                        ->join('tf_allot b','a.allot_id = b.id','LEFT')
//                        ->where(['a.shop_id'=>$from_shop,'a.item_id'=>$v['item_id'],'b.status'=>0])->sum('a.num');
//                    $on_reject_stock = $db->name('reject_item')->alias('a')
//                        ->join('tf_reject b','a.reject_id = b.id','LEFT')
//                        ->where(['b.shop_id'=>$from_shop,'a.item_id'=>$v['item_id'],'b.status'=>0])->sum('a.num');
//                    $order_tmp_stock = (new ProfitManageModel())->get_order_tmp_ice($from_shop,$v['item_id']);
//                    $now_real_stock = $now_stock['stock']-$on_allot_stock-$on_reject_stock-$order_tmp_stock;
//                    //如果调拨库存大于现有库存，从数组中删除该条商品信息，重新查询该条商品库存、成本信息加入数组中
//                    if($v['num']>$now_real_stock+$on_allot_stock){
//                        unset($item_list[$k]);
//                        $new_data[] = [
//                            'item_id'=>$v['item_id'],
//                            'stock'=>$now_real_stock,
//                            'md_price'=>$now_stock['md_price_after'],
//                            'store_cost'=>$now_stock['store_cost_after']
//                        ];
//                    }
//                }
//                if($new_data!=null){
//                    return outPut(302,'fail',$new_data);
//                    exit;
//                }
//                try {
//                    //生成调拨单号
//                    $last_item = Db::connect(config('ddxx'))->name('allot')->field('sn')->order('time desc')->limit(1)->find();
//                    if (!$last_item) {
//                        $end_num = '0001';
//                    } else {
//                        $last_sn = substr($last_item['sn'], -4);
//                        $end_num = $newStr = sprintf('%04s', $last_sn + 1);
//                    }
//                    $new_sn = 'DB' . date("Ymd", time()) . $end_num;
//                    $allot_data['sn'] = $new_sn;
//                    $allot = $db->name('allot')->insert($allot_data);
//                    if ($allot) {
//                        $allot_id = $db->name('allot')->getLastInsID();
//                        foreach ($item_list as $k=>$v){
//                            $item_list[$k]['allot_id'] = $allot_id;
//                            $item_list[$k]['shop_id'] = $from_shop;
//                            $ress = $db->name('shop_item')->where(['shop_id'=>$from_shop,'item_id'=>$v['item_id']])->setInc('stock_ice',$v['num']);
//                        }
//                        $res = $db->name('allot_item')->insertAll($item_list);
//                        if(!$res||!$ress){
//                            $fail+=1;
//                        }
//                    }else{
//                        $fail+=1;
//                    }
//                    if($fail==0){
//                        $db->commit();
//                        return outPut(200,'success','保存成功');
//                    }
//                } catch (\Exception $e) {
//                    $db->rollback();
//                    return outPut(301,'fail',$e->getMessage());
//                }
//            }
        }
    }


    /**
     * @api {POST} /admin/Allot/editOrView 编辑或查看调拨单
     * @apiGroup 调拨单管理
     * @apiName editOrView
     * @apiVersion 1.0.0
     * @apiDescription 编辑或查看调拨单，data.allotInfo.status值为0时可编辑、删除，为1时只可查看
     * @apiParam (请求参数) {int} allot_id 调拨单ID
     * @apiSuccess (返回参数) {Int} code 状态码 200成功 301失败
     * @apiSuccess (返回参数) {str} msg 请求结果 success成功 fail失败
     * @apiSuccess (返回参数) {array} data 返回结果集
     * @apiSuccess (返回参数) {int} data.is_show 是否显示金额数据 1是 0否
     * @apiSuccess (返回参数) {array} data.allot.allotInfo 调拨单基本数据
     * @apiSuccess (返回参数) {int} data.allot.allotInfo.to_shop 调出仓库ID
     * @apiSuccess (返回参数) {int} data.allot.allotInfo.from_shop 调入仓库ID
     * @apiSuccess (返回参数) {str} data.allot.allotInfo.sn 调拨单号
     * @apiSuccess (返回参数) {int} data.allot.allotInfo.time 调拨单时间戳
     * @apiSuccess (返回参数) {int} data.allot.allotInfo.status 调拨单状态
     * @apiSuccess (返回参数) {float} data.allot.allotInfo.bid_amount 调拨单进价金额
     * @apiSuccess (返回参数) {float} data.allot.allotInfo.cost 调拨单实际成本金额
     * @apiSuccess (返回参数) {str} data.allot.allotInfo.outor 调拨单出库人
     * @apiSuccess (返回参数) {str} data.allot.allotInfo.iner 调拨单入库人
     * @apiSuccess (返回参数) {str} data.allot.allotInfo.remark 调拨单备注
     * @apiSuccess (返回参数) {array} data.allot.allotItemInfo 调拨单商品数据
     * @apiSuccess (返回参数) {int} data.allot.allotItemInfo.id 调拨商品表ID
     * @apiSuccess (返回参数) {int} data.allot.allotItemInfo.item_id 商品ID
     * @apiSuccess (返回参数) {int} data.allot.allotItemInfo.num 调拨数量
     * @apiSuccess (返回参数) {str} data.allot.allotItemInfo.title 商品名
     * @apiSuccess (返回参数) {str} data.allot.allotItemInfo.bar_code 条形码
     * @apiSuccess (返回参数) {str} data.allot.allotItemInfo.cname 商品分类
     * @apiSuccess (返回参数) {float} data.allot.allotItemInfo.md_price 调拨时商品的门店单价
     * @apiSuccess (返回参数) {float} data.allot.allotItemInfo.store_cost 调拨时商品的真实成本
     * @apiSuccess (返回参数) {float} data.allot.allotItemInfo.stock 调拨时商品的库存
     * @apiSuccess (返回参数) {Array} data.toShopDatas 调入仓库列表
     * @apiSuccess (返回参数) {int} data.toShopDatas.id 仓库ID
     * @apiSuccess (返回参数) {str} data.toShopDatas.name 仓库名
     * @apiSuccess (返回参数) {Array} data.fromShopDatas 调出仓库列表
     * @apiSuccess (返回参数) {int} data.fromShopDatas.id 仓库ID
     * @apiSuccess (返回参数) {str} data.fromShopDatas.name 仓库名
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"success","data":{"is_show":0,"formShopDatas":{"id":18,"name":"总店"},"toShopDatas":[{"id":18,"name":"总店"},{"id":6,"name":"爱琴海店"},{"id":5,"name":"留云路店"},{"id":16,"name":"龙湖源著店"},{"id":17,"name":"重庆城口店"},{"id":15,"name":"珠江太阳城店"},{"id":21,"name":"两江时光店"},{"id":22,"name":"测试门店10"},{"id":25,"name":"约克郡南郡"},{"id":24,"name":"约克郡北郡"},{"id":26,"name":"蓝光COCO店"},{"id":29,"name":"融创金茂店"},{"id":28,"name":"港城国际店"},{"id":27,"name":"江与城店"},{"id":30,"name":"麓山别苑店"},{"id":31,"name":"奥山别墅店"}],"allot":{"allotInfo":{"id":1,"sn":"DB201807230001","from_shop":6,"out_admin_id":1,"to_shop":17,"in_admin_id":0,"status":0,"in_time":0,"bid_amount":"600.0000","cost":"540.0000","remark":"","time":0,"outor":"admin","iner":null},"allotItemInfo":[{"id":1,"allot_id":1,"shop_id":4,"item_id":3,"num":2,"now_md_univalent":"0.0000","now_store_cost":"0.0000","remark":"","stock":null,"title":"惠氏金装婴儿奶粉 3段2罐","bar_code":"6615249817","cname":"惠氏"},{"id":2,"allot_id":1,"shop_id":4,"item_id":2,"num":2,"now_md_univalent":"3.0000","now_store_cost":"2.0000","remark":"调拨备注1","stock":null,"title":"三鹿三聚氰胺奶粉2段3罐","bar_code":"654654","cname":"洗发沐浴"},{"id":3,"allot_id":1,"shop_id":4,"item_id":3,"num":3,"now_md_univalent":"4.0000","now_store_cost":"3.0000","remark":"调拨备注2","stock":null,"title":"惠氏金装婴儿奶粉 3段2罐","bar_code":"6615249817","cname":"惠氏"}]}}}
     * @apiSampleRequest /admin/Allot/editOrView
     */
    public function editOrView()
    {
        $id = input('post.allot_id');
        //可编辑的情况
        $data = [];
        $shop_id = Session::get('SHOP_ID');
        $admin_id = $this->admin_id;
        $except_role_id = [1,6];
        $role_id = Db::table('ddxm_role_user')->where('user_id','=',$admin_id)->value('role_id');
        $is_show = 0;
        if ($role_id==1||$role_id==6||$role_id==7||$admin_id==1) {
            $is_show = 1;
        }
        $data['is_show'] = $is_show;
        //获取所有店铺（仓库）数据
        $fromShopDatas = $toShopDatas = Db::connect(config('ddxx'))->name('shop')->field('id,name')->select();
        if (!in_array($role_id, $except_role_id) && $admin_id != 1) {
            $fromShopDatas = Db::connect(config('ddxx'))->name('shop')->field('id,name')->where(['id' => $shop_id,'status'=>['neq',0]])->find();
        }
        $data['formShopDatas'] = $fromShopDatas;
        $data['toShopDatas'] = $toShopDatas;
        $allot = (new AllotModel())->get_allot_info($id);
        $outor = \db('user')->where(['id'=>$allot['allotInfo']['out_admin_id']])->value('user_login');
        $allot['allotInfo']['outor'] = $outor;
        $iner = \db('user')->where(['id'=>$allot['allotInfo']['in_admin_id']])->value('user_login');
        $allot['allotInfo']['iner'] = $iner;
        $data['allot'] = $allot;
        return outPut(200,'success',$data);
    }

    /**
     * @api {POST} /admin/Allot/allot_confirm 确认调入
     * @apiGroup 调拨单管理
     * @apiName allot_confirm
     * @apiVersion 1.0.0
     * @apiDescription 确认调入，重新计算调入仓库的商品成本和库存，减少调出仓库的库存
     * @apiParam (请求参数) {int} allot_id 调拨单ID
     * @apiSuccess (返回参数) {Int} code 状态码 200成功 301失败
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"success","data":[]}
     * @apiSampleRequest /admin/Allot/allot_confirm
     */
    public function allot_confirm()
    {
        $id = input('post.allot_id');
        //修改调拨状态，重新计算调入仓库商品成本价
        $db = Db::connect(config('ddxx'));
        $allot = (new AllotModel())->get_allot_info($id);
        if($allot['allotInfo']['status']==1){
            return outPut(301,'fail','该单据已完成调拨，请不要重复确认！');
        }
        $insert_to_data = [];
        $insert_from_data = [];
//        dump($allot['allotItemInfo']);exit;
        foreach ($allot['allotItemInfo'] as $k=>$v){
            //某商品库存单位成本=（原库存商品单位成本*库存内该商品数量+本次该商品入库成本）/（库存内该商品数量+本次入库数量）
            $stock_info = $db->name('purchase_price')
                ->where(['shop_id'=>$allot['allotInfo']['to_shop'],'item_id'=>$v['item_id']])
                ->order('time desc')->find();
            $from_stock_info = $db->name('purchase_price')
                ->where(['shop_id'=>$allot['allotInfo']['from_shop'],'item_id'=>$v['item_id']])
                ->order('time desc')->find();
            //        计算假成本
            $mom = $stock_info['md_price_after']*$stock_info['stock']+$v['md_price']*$v['num'];
            $son = $stock_info['stock']+$v['num'];
            $md_price_before = $stock_info['md_price_after'];
            $md_price_after = $mom/$son;
//            计算真实库存成本
            $r_mom = $stock_info['store_cost_after']*$stock_info['stock']+$v['store_cost']*$v['num'];
            $store_cost_before = $stock_info['store_cost_after'];//最近一次商品的入库后成本等于本次商品的入库前成本
            $store_cost_after = $r_mom/$son;
            //添加调入仓商品成本变化数据，增加调入仓库库存
            $insert_to_data[] = [
                'shop_id'=>$allot['allotInfo']['to_shop'],
                'type'=>2,
                'pd_id'=>$id,
                'item_id'=>$v['item_id'],
                'md_price_before'=>$md_price_before?$md_price_before:0,
                'md_price_after'=>$md_price_after,
                'store_cost_before'=>$store_cost_before?$store_cost_before:0,
                'store_cost_after'=>$store_cost_after,
                'stock'=>$stock_info['stock']+$v['num'],
                'time'=>time()
            ];
            //添加调出仓商品成本变化数据，减少调出仓库库存
            $insert_from_data[] = [
                'shop_id'=>$allot['allotInfo']['from_shop'],
                'type'=>2,
                'pd_id'=>$id,
                'item_id'=>$v['item_id'],
                'md_price_before'=>$from_stock_info['md_price_before']?$from_stock_info['md_price_before']:0,
                'md_price_after'=>$from_stock_info['md_price_after'],
                'store_cost_before'=>$from_stock_info['store_cost_before']?$from_stock_info['store_cost_before']:0,
                'store_cost_after'=>$from_stock_info['store_cost_after'],
                'stock'=>$from_stock_info['stock']-$v['num'],
                'time'=>time()
            ];
            if($from_stock_info['stock']-$v['num']<0){
                return outPut(301,'fail','商品调出数量不能大于调出仓库原有库存！');
                exit;
            }
        }
//            dump($insert_to_data);
//        dump($insert_from_data);exit;
        $db->startTrans();
        try{
            $res1 = $db->name('purchase_price')->insertAll($insert_to_data);
            $res2 = $db->name('purchase_price')->insertAll($insert_from_data);
            $allot_item_list = $db->name('allot_item')->where(['allot_id'=>$id])->select();
            $fail = 0;
            foreach ($allot_item_list as $a=>$b){
                $info = $db->name('shop_item')->where(['shop_id'=>$allot['allotInfo']['to_shop'],'item_id'=>$b['item_id']])->find();
                //增加shop_item表调入仓库中商品的实际库存
                //shop_item中无该商品数据则新增一条数据，有则增加该商品库存
                if(!$info){
                    if (!($db->name('shop_item')->insert(['shop_id'=>$allot['allotInfo']['to_shop'],'item_id'=>$b['item_id'],'stock'=>$b['num']]))) {
                        $fail+=1;
                    }
                }else{
                    if (!($db->name('shop_item')->where(['shop_id'=>$allot['allotInfo']['to_shop'],'item_id'=>$b['item_id']])->setInc('stock',$b['num']))) {
                        $fail+=1;
                    }
                }
                //减少shop_item表调出仓库中商品的冻结库存,减少调出仓库的库存
                $from_shop_info = $db->name('shop_item')->where(['shop_id'=>$allot['allotInfo']['from_shop'],'item_id'=>$b['item_id']])->find();
                if (!($db->name('shop_item')->where(['shop_id'=>$allot['allotInfo']['from_shop'],'item_id'=>$b['item_id']])->update(['stock_ice'=>$from_shop_info['stock_ice']-$b['num'],'stock'=>$from_shop_info['stock']-$b['num']]))) {
                    $fail+=1;
                }
            }
            if($res1&&$res2&&$fail==0){
                $res3 = $db->name('allot')->where(['id'=>$id])->update(['status'=>1,'in_admin_id'=>$this->admin_id,'in_time'=>time()]);
                //调出仓商品库存变化之后添加库存预警信息
                foreach ($insert_from_data as $vv){
                    (new StockModel())->updateStockAlert($vv['item_id'],$vv['shop_id'],$vv['stock']);
                }
                if ($res3){
                    $db->commit();
                    return outPut(200,'success','操作成功');
                }
            }
            $db->rollback();
            return outPut(301,'fail','操作失败');
        }catch (\Exception $e){
            $db->rollback();
            // var_dump($e);exit;
            return outPut(301,'fail','操作失败:'.$e->getMessage());
        }
    }

    /**
     * @api {POST} /admin/Allot/delete 删除调拨单
     * @apiGroup 调拨单管理
     * @apiName delete
     * @apiVersion 1.0.0
     * @apiDescription 删除调拨单
     * @apiParam (请求参数) {int} allot_id 调拨单ID
     * @apiSuccess (返回参数) {Int} code 状态码 200成功 301失败
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"success","data":[]}
     * @apiSampleRequest /admin/Allot/delete
     */
    public function delete()
    {
        $db = Db::connect(config('ddxx'));
        $id = input('post.allot_id');
        $status = Db::connect(config('ddxx'))->name('allot')->where(['id'=>$id])->value('status');
        if($status==1){
            return outPut(301,'fail','该采购单已入库，不能删除！');
        }
        $db->startTrans();
        try{
            $res = $db->name('allot')->where(['id'=>$id])->update(['status'=>-1]);
            $fail=0;
            if ($res) {
                //删除之后释放冻结中的库存
                $allot_item_list = $db->name('allot_item')->where(['allot_id'=>$id])->select();
                foreach ($allot_item_list as $k=>$v){
                    $ress = $db->name('shop_item')->where(['shop_id'=>$v['shop_id'],'item_id'=>$v['item_id']])->setDec('stock_ice',$v['num']);
                    if(!$ress){
                        $fail+=1;
                    }
                }
                if($fail==0){
                    $db->commit();
                    return outPut(200,'success','删除成功');
                }else{
                    $db->rollback();
                    return outPut(301,'fail','删除失败');
                }
            }
        }catch (\Exception $e){
            $db->rollback();
            return outPut(301,'fail','删除失败');
        }
    }

    /**
     * @api {Post} /admin/Allot/get_goods_by_code 条形码
     * @apiGroup 调拨单管理
     * @apiName get_goods_by_code
     * @apiVersion 1.0.0
     * @apiDescription  根据条形码和店铺ID获取商品信息
     * @apiParam (请求参数) {str} code 条形码 不可空
     * @apiParam (请求参数) {int} shop_id 门店ID 不可空
     * @apiSuccess (返回参数) {Int} code 状态码 200成功 301错误
     * @apiSuccess (返回参数) {str} msg 请求结果 success成功 fail失败
     * @apiSuccess (返回参数) {array} data 返回参数结果集
     * @apiSuccess (返回参数) {str} data.item_id 商品ID
     * @apiSuccess (返回参数) {str} data.title 商品名称
     * @apiSuccess (返回参数) {str} data.cname 商品分类
     * @apiSuccess (返回参数) {str} data.bar_code 商品条形码
     * @apiSuccess (返回参数) {str} data.price 商品原价
     * @apiSuccess (返回参数) {str} data.md_price 商品门店进价
     * @apiSuccess (返回参数) {str} data.store_cost 商品入库成本
     * @apiSuccess (返回参数) {str} data.stock 商品库存
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"success","data":{"shop_name":"测试门店10","title":"测试商品2","price":"200.00","bar_code":"154854","item_id":746,"cname":"模型玩具","shop_id":22,"stock":80,"md_price":"120.00","store_cost":"879.18"}}
     * @apiSampleRequest /admin/Allot/get_goods_by_code
     */
    public function get_goods_by_code()
    {
        $code = input('post.bar_code');
        $shop_id = input('post.shop_id');
        if(!$code){
            return outPut(301, 'fail', '条形码参数丢失');
        }
        $where = ['a.shop_id'=>$shop_id,'c.bar_code'=>$code];
        $res = (new StockCheckModel())->get_stock_list($where)->find();
        $db = Db::connect(config('ddxx'));
        unset($res['id']);
        $stock = $db->name('purchase_price')
            ->where(['shop_id'=>$shop_id,'item_id'=>$res['item_id']])->order('id desc')
            ->field('stock,md_price_after,store_cost_after')->find();
        //实际库存=库存表库存-调拨中库存-退货中库存-线上订单冻结库存
        $allot_stock = $db->name('allot_item')->alias('a')
            ->join('tf_allot b','a.allot_id = b.id','LEFT')
            ->where([ 'a.item_id' => $res['item_id'],'b.from_shop' => $shop_id,'b.status'=>0])->sum('a.num');
        $reject_stock = $db->name('reject_item')->alias('a')
            ->join('tf_reject b','a.reject_id = b.id','LEFT')
            ->where(['a.item_id'=>$res['item_id'],'b.shop_id'=>$shop_id,'b.status'=>0])->sum('a.num');
        $order_tmp_stock = (new ProfitManageModel())->get_order_tmp_ice($shop_id,$res['item_id']);
        $real_stock = $stock['stock']-$allot_stock-$reject_stock-$order_tmp_stock;
        if($real_stock<=0){
            return outPut(301, 'fail', '该商品库存不足，无法调拨');
        }
        $res['stock'] = $real_stock;
        $res['md_price'] = $stock['md_price_after'];
        $res['store_cost'] = $stock['store_cost_after'];
        if (empty($res)) {
            return outPut(301, 'fail', '没有找到相关商品');
        }
        return outPut(200, 'success', $res);
    }

    public function allot_print()
    {
        $id = input('post.allot_id');
//        $id = 1;
//       查询调拨单信息
        $data = [];
        $db = Db::connect(config('ddxx'));
        $allot_info = $db->name('allot')->where(['id'=>$id])->find();
        $from_shop_name = $db->name('shop')->where(['id'=>$allot_info['from_shop']])->value('name');
        $to_shop_name = $db->name('shop')->where(['id'=>$allot_info['to_shop']])->value('name');
        $outer = \db('user')->where(['id'=>$allot_info['out_admin_id']])->value('user_login');
        $iner = \db('user')->where(['id'=>$allot_info['in_admin_id']])->value('user_login');
        $data['from_shop_name'] = $from_shop_name;
        $data['to_shop_name'] = $to_shop_name;
        $data['outer'] = $outer;
        $data['iner'] = $iner;
        $data['time'] = date("Y-m-d",$allot_info['time']);
        $allot_item_info = $db->name('allot_item')->alias('a')
            ->join('tf_item b', 'a.item_id = b.id', 'LEFT')
            ->join('tf_item_category c', 'b.type = c.id', 'LEFT')
            ->where(['a.allot_id'=>$id])
            ->field('b.title,b.bar_code,c.cname,a.num,a.remark')
            ->select();
        $data['allot_info'] = $allot_info;
        foreach ($allot_item_info as &$v){
            $v['bar_code'] = $v['bar_code']?$v['bar_code']:'无';
            $v['remark'] = $v['remark']?$v['remark']:'无';
        }
        $data['allot_item_info'] = $allot_item_info;
        return outPut(200,'success',$data);
    }

}
