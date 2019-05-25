<?php
/**
 * Created by PhpStorm.
 * User: shiyu
 * Date: 2018/7/26 0026
 * Time: 上午 11:40
 */

namespace app\admin\controller;


use app\admin\model\ProfitManageModel;
use app\admin\model\StockCheckModel;
use cmf\controller\AdminBaseController;
use think\Db;
use think\Session;

//库存查询
class StockCheckController extends AdminBaseController
{

    public function index()
    {
        return view('index');
    }


    /**
     * @api {POST} /admin/StockCheck/getStockList 获取商品库存信息
     * @apiGroup 库存查询
     * @apiName getStockList
     * @apiVersion 1.0.0
     * @apiDescription  ajax获取分页商品数据
     * @apiParam (请求参数) {int} curPage 页数 不可空
     * @apiParam (请求参数) {str} item_name 商品名称 可空
     * @apiParam (请求参数) {int} shop_id 所属仓库ID 可空
     * @apiParam (请求参数) {int} type 二级分类ID 可空
     * @apiParam (请求参数) {int} show_zero 是否显示0库存商品 1是 0否
     * @apiParam (请求参数) {str} bar_code 商品条形码 可空
     * @apiSuccess (返回参数) {int} data.totalPage 总页数
     * @apiSuccess (返回参数) {int} data.totalNum 数据总条数
     * @apiSuccess (返回参数) {int} data.pageSize 每页数据条数
     * @apiSuccess (返回参数) {int} data.sotck_all 总库存
     * @apiSuccess (返回参数) {int} data.md_price_all 门店进价总额
     * @apiSuccess (返回参数) {int} data.store_cost_all 库存总成本
     * @apiSuccess (返回参数) {int} data.pageSize 每页数据条数
     * @apiSuccess (返回参数) {array} data.list 商品库存信息列表
     * @apiSuccess (返回参数) {str} data.list.title 商品名
     * @apiSuccess (返回参数) {str} data.list.shop_name 所属仓库
     * @apiSuccess (返回参数) {int} data.list.stock 库存
     * @apiSuccess (返回参数) {int} data.list.num 在途
     * @apiSuccess (返回参数) {float} data.list.store_cost 库存单位成本
     * @apiSuccess (返回参数) {float} data.list.md_price 门店单位成本
     * @apiSuccess (返回参数) {float} data.list.price 售价
     * @apiSuccess (返回参数) {str} data.list.cname 商品分类
     * @apiSuccess (返回参数) {str} data.list.bar_code 条形码
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"success","data":{"shop_list":[{"id":18,"name":"总店"},{"id":6,"name":"爱琴海店"},{"id":5,"name":"留云路店"},{"id":16,"name":"龙湖源著店"},{"id":17,"name":"重庆城口店"},{"id":15,"name":"珠江太阳城店"},{"id":21,"name":"两江时光店"},{"id":22,"name":"测试门店10"},{"id":25,"name":"约克郡南郡"},{"id":24,"name":"约克郡北郡"},{"id":26,"name":"蓝光COCO店"},{"id":29,"name":"融创金茂店"},{"id":28,"name":"港城国际店"},{"id":27,"name":"江与城店"},{"id":30,"name":"麓山别苑店"},{"id":31,"name":"奥山别墅店"}],"is_show":1,"totalPage":1,"list":[{"id":3,"shop_name":"留云路店","title":"三鹿三聚氰胺奶粉2段3罐","price":"100.00","bar_code":"654654","item_id":2,"cname":"洗发沐浴","num":null,"md_price_after":"8.0000","store_cost_after":"6.0000"},{"id":2,"shop_name":"留云路店","title":"惠氏金装婴儿奶粉 3段2罐","price":"268.00","bar_code":"6615249817","item_id":3,"cname":"惠氏","num":null,"md_price_after":"6.0000","store_cost_after":"4.0000"}]}}
     * @apiSampleRequest /admin/StockCheck/getStockList
     */
    public function getStockList()
    {
        $shop_id = Session::get('SHOP_ID');
        $except_role_id = [1, 6,7,8,9];//不限制的门店管理员角色
        $data = [];
        $where = [];
        //获取店铺信息(回去根据当前登录的用户来筛选店铺)
        $admin_id = $this->admin_id;
        $role_id = Db::table('ddxm_role_user')->where('user_id', '=', $admin_id)->value('role_id');
        //搜索数据
        $page = trim($this->request->param('curPage', '1'));//请求页数
        $show_all_stock = trim($this->request->param('show_all_stock', 0));//请求页数
        $itemName = trim($this->request->param('item_name', ''));//商品名称
        $shopId = trim($this->request->param('shop_id'));//所属仓库
        $category = trim($this->request->param('type'));//商品分类
        $show_zero = trim($this->request->param('show_zero'));//是否显示零库存商品 1显示 0不显示
        $bar_code = $this->request->param('bar_code');//商品条形码
        if ($bar_code!=null) {
            $where['c.bar_code'] = $bar_code;
        }
        if ($itemName != null) {
            $where['c.title'] = ['like', "%$itemName%"];
        }
        //判断用户是否是管理员
        //是的话忽略搜索条件赋值shop_id
        //不是的话限制shop_id为本店的shop_id
        if($shopId != null){
            $where['a.shop_id'] = $shopId;
        }
        if ($admin_id!=1&&!in_array($role_id,$except_role_id)) {
            $where['a.shop_id'] = $shop_id;
        }
       // if (empty($shopId)) {
       //     if ($admin_id != 1 && !in_array($role_id, $except_role_id)) {
       //         $where['a.shop_id'] = $shop_id;
       //     }
       // } else {
       //     $where['a.shop_id'] = $shopId;
       //     $shop_id = $shopId;
       // }
       // $where['a.shop_id'] = $shop_id;
        if ($category != null) {
            $where['c.type'] = $category;
        }
        if ($show_zero == 0) {
            $where['a.stock'] = ['neq', 0];
        }
        //定义分页所需的数据
        $count = (new StockCheckModel())->get_stock_list($where)->count();
        $totalItem = $count;   //总记录数(自行定义)
        $pageSize = 15;  //每一页记录数(自行定义)
        $totalPage = ceil($totalItem / $pageSize);  //总页数
        $startItem = ($page - 1) * $pageSize;//根据页码来决定查询数据的节点
        //所有商品库存信息列表
        $list = (new StockCheckModel())->get_stock_list($where)->limit($startItem, $pageSize)->select();
        $db = Db::connect(config('ddxx'));
        $stock_all = 0;
        $md_price_all = 0;
        $store_cost_all = 0;
        foreach ($list as $k=>$v){
            $now_stock = $db->name('purchase_price')->where(['shop_id'=>$v['shop_id'],'item_id'=>$v['item_id']])->order('id desc')->find();
           // dump($now_stock);exit;
            $on_allot_stock = $db->name('allot_item')->alias('a')
                ->join('tf_allot b','a.allot_id = b.id','LEFT')
                ->where(['a.shop_id'=>$v['shop_id'],'a.item_id'=>$v['item_id'],'b.status'=>0])->sum('a.num');
           // echo $on_allot_stock;
            $on_reject_stock = $db->name('reject_item')->alias('a')
                ->join('tf_reject b','a.reject_id = b.id','LEFT')
                ->where(['b.shop_id'=>$v['shop_id'],'a.item_id'=>$v['item_id'],'b.status'=>0])->sum('a.num');
           // echo $on_reject_stock;
            $order_tmp_stock = (new ProfitManageModel())->get_order_tmp_ice($v['shop_id'],$v['item_id']);
           // echo $order_tmp_stock;
            $inventory_loss_ice_stock = (new ProfitManageModel())->get_inventory_loss_ice($v['shop_id'],$v['item_id']);
           // echo $inventory_loss_ice_stock;
            //冻结库存(在途) = 调拨中库存+退货给供货商途中库存+线上订单占用库存+盘亏状态但还未出库的库存
            $on_way_stock = $on_allot_stock+$on_reject_stock+$order_tmp_stock+$inventory_loss_ice_stock;


            $list[$k]['md_price'] = $now_stock['md_price_after'];
            $list[$k]['store_cost'] = $now_stock['store_cost_after'];
            $list[$k]['num'] = $on_way_stock;
//            $list[$k]['num'] = $v['item_id'].'____'.$v['shop_id'];

//            if($v['item_id'] == 1417){
//                $list[$k]['stock'] = 'nihao:'.;
//            }else{
                $list[$k]['stock'] = $now_stock['stock'];
//            }


            $stock_all += $now_stock['stock'];
            $md_price_all += $now_stock['stock']*$now_stock['md_price_after'];
            $store_cost_all += $now_stock['stock']*$now_stock['store_cost_after'];
        }

        if ($show_all_stock)
        {
            $stockCheckModel = new StockCheckModel();
            $pageSizes = 1000;  //每一页记录数(自行定义)
            $totalPages = ceil($totalItem / $pageSizes);  //总页数
            for ($i= 0; $i < $totalPages; $i++) {
                $lists = $stockCheckModel->get_stock_list($where)->limit($i*$pageSizes, $pageSizes)->select();
                foreach ($lists as $kk=>$vv){
                    $now_stock = $db->name('purchase_price')->where(['shop_id'=>$vv['shop_id'],'item_id'=>$vv['item_id']])->order('id desc')->find();
                    $stock_all+=$now_stock['stock'];
                    $md_price_all += $now_stock['stock']*$now_stock['md_price_after'];
                    $store_cost_all += $now_stock['stock']*$now_stock['store_cost_after'];
                }
            }
        }

        $data['stock_all'] = $stock_all;
        $data['md_price_all'] = $md_price_all;
        $data['store_cost_all'] = $store_cost_all;
        $data['totalPage'] = $totalPage;
        $data['totalNum'] = $totalItem;
        $data['pageSize'] = $pageSize;
        $data['list'] = $list;
        return outPut(200, 'success', $data);
    }

    /**
     * [exportStock 商品库存信息导出]
     * @method exportStock
     * @return [type]      [description]
     */
    public function exportStock()
    {
        $shop_id = Session::get('SHOP_ID');
        $except_role_id = [1, 6,7,8,9];//不限制的门店管理员角色
        $where = [];
        //获取店铺信息(回去根据当前登录的用户来筛选店铺)
        $admin_id = $this->admin_id;
        $role_id = Db::name('role_user')->where('user_id', '=', $admin_id)->value('role_id');
        //搜索数据
        $page = trim($this->request->param('curPage', '1'));//请求页数
        $itemName = trim($this->request->param('item_name', ''));//商品名称
        $shopId = trim($this->request->param('shop_id'));//所属仓库
        $category = trim($this->request->param('type'));//商品分类
        $show_zero = trim($this->request->param('show_zero'));//是否显示零库存商品 1显示 0不显示
        $bar_code = $this->request->param('bar_code');//商品条形码
        if ($bar_code!=null) {
            $where['c.bar_code'] = $bar_code;
        }
        if ($itemName != null) {
            $where['c.title'] = ['like', "%$itemName%"];
        }
        //判断用户是否是管理员
        //是的话忽略搜索条件赋值shop_id
        //不是的话限制shop_id为本店的shop_id
        if($shopId != null){
            $where['a.shop_id'] = $shopId;
        }
        if ($admin_id!=1&&!in_array($role_id,$except_role_id)) {
            $where['a.shop_id'] = $shop_id;
        }
        if ($category != null) {
            $where['c.type'] = $category;
        }
        if ($show_zero == 0) {
            $where['a.stock'] = ['neq', 0];
        }
        //所有商品库存信息列表
        $list = (new StockCheckModel())->get_stock_list($where)->select();
        $db = Db::connect(config('ddxx'));
        $phpexcel = new \PHPExcel();
        $phpexcel->getActiveSheet()->setCellValue('A1','商品名称')
            ->setCellValue('B1','所属仓库')
            ->setCellValue('C1','库存数量')
            ->setCellValue('D1','其中在途')
            ->setCellValue('E1','库存单位成本')
            ->setCellValue('F1','门店单位进价')
            ->setCellValue('G1','售价')
            ->setCellValue('H1','商品分类')
            ->setCellValue('I1','条形码');
        foreach ($list as $k=>$v){
            $now_stock = $db->name('purchase_price')->where(['shop_id'=>$v['shop_id'],'item_id'=>$v['item_id']])->order('id desc')->find();
            $on_allot_stock = $db->name('allot_item')->alias('a')
                ->join('tf_allot b','a.allot_id = b.id','LEFT')
                ->where(['a.shop_id'=>$v['shop_id'],'a.item_id'=>$v['item_id'],'b.status'=>0])->sum('a.num');
            $on_reject_stock = $db->name('reject_item')->alias('a')
                ->join('tf_reject b','a.reject_id = b.id','LEFT')
                ->where(['b.shop_id'=>$v['shop_id'],'a.item_id'=>$v['item_id'],'b.status'=>0])->sum('a.num');
            $order_tmp_stock = (new ProfitManageModel())->get_order_tmp_ice($v['shop_id'],$v['item_id']);
            $inventory_loss_ice_stock = (new ProfitManageModel())->get_inventory_loss_ice($v['shop_id'],$v['item_id']);
            //冻结库存 = 调拨中库存+退货给供货商途中库存+线上订单占用库存+盘亏状态但还未出库的库存
            $on_way_stock = $on_allot_stock+$on_reject_stock+$order_tmp_stock+$inventory_loss_ice_stock;
            $phpexcel->getActiveSheet()->setCellValue('A'.($k+2),$v['title'])
                ->setCellValue('B'.($k+2),$v['shop_name'])
                ->setCellValue('C'.($k+2),$now_stock['stock'])
                ->setCellValue('D'.($k+2),$on_way_stock)
                ->setCellValueExplicit('E'.($k+2),$now_stock['store_cost_after'])
                ->setCellValue('F'.($k+2),$now_stock['md_price_after'])
                ->setCellValue('G'.($k+2),$v['price'])
                ->setCellValue('H'.($k+2),$v['cname'])
                ->setCellValue('I'.($k+2),$v['bar_code']);
        }
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.date("Y年m月d日 H:i",time()).$shopId.'商品 - '.$itemName.' 库存导出.xls"');
        header('Cache-Control: max-age=0');
        $a = new \PHPExcel_Writer_Excel5($phpexcel);
        $a->save('php://output');
    }

    /**
     * @api {POST} /admin/StockCheck/get_stock_detail 出入库
     * @apiGroup 库存查询
     * @apiName get_stock_detail
     * @apiVersion 1.0.0
     * @apiDescription  获取商品出入库详细信息
     * @apiParam (请求参数) {int} shop_id 仓库（门店）ID
     * @apiParam (请求参数) {int} item_id 商品ID
     * @apiParam (请求参数) {int} curPage 页码
     * @apiParam (请求参数) {str} time 出入库时间
     * @apiParam (请求参数) {int} type 单据类型 1采购单 2调拨单 3盘盈单 4盘亏单 5零售退货单 6零售出库单 7供应商退货单
     * @apiSuccess (返回参数) {int} data.totalPage 数据总页数
     * @apiSuccess (返回参数) {int} data.totalNum 数据总条数
     * @apiSuccess (返回参数) {int} data.pageSize 每页数据数
     * @apiSuccess (返回参数) {array} data.list 商品出入库详细信息列表
     * @apiSuccess (返回参数) {int} data.list.time 操作时间（时间戳）
     * @apiSuccess (返回参数) {str} data.list.type_name 单据类型
     * @apiSuccess (返回参数) {str} data.list.sn 单据编号
     * @apiSuccess (返回参数) {int} data.list.change_stock 变化数量
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"success","data":{"list":[{"time":0,"stock":1,"type":1,"pd_id":1,"type_name":"采购","sn":"CG201807190001","change_stock":1},{"time":0,"stock":3,"type":1,"pd_id":1,"type_name":"采购","sn":"CG201807190001","change_stock":2}]}}
     * @apiSampleRequest /admin/StockCheck/get_stock_detail
     */
    public function get_stock_detail()
    {
        $shop_id = input('post.shop_id');
        $item_id = input('post.item_id');
        $page = input('post.curPage',1);
        $time = input('post.time');
        $type = input('post.type');
        $where = [];
        $where['shop_id'] = $shop_id;
        $where['item_id'] = $item_id;
        if ($time) {
            $time_arr = explode('~',$time);
            $add_s = strtotime($time_arr[0]);
            $add_e = strtotime($time_arr[1])+24*3600;
            $where['time'] = ['between',[$add_s,$add_e]];
        }
        if ($type) {
            $where['type'] = $type;
        }

        $db = Db::connect(config('ddxx'));
        $count = $db->name('purchase_price')
            ->where($where)
            ->field('id')->count();
        $totalItem = $count;   //总记录数(自行定义)
        $pageSize = 10;  //每一页记录数(自行定义)
        $totalPage = ceil($totalItem / $pageSize);  //总页数
        $startItem = ($page - 1) * $pageSize;//根据页码来决定查询数据的节点
        $list = $db->name('purchase_price')
            ->where($where)
            ->field('time,stock,type,pd_id,item_id')
            ->order('id desc')
            ->limit($startItem,$pageSize)
            ->select();
//        $first_line_stock = $list[0]['stock'];
//        foreach ($list as $k=>$v){
//            switch ($v['type']){
//                case '1':
//                    $list[$k]['type_name'] = '采购';
//                    $list[$k]['sn'] = $db->name('purchase')->where(['id'=>$v['pd_id']])->value('sn');
//                    $list[$k]['change_stock'] = $v['stock'];
//                    break;
//                case '2':
//                    $info = $db->name('allot')->where(['id'=>$v['pd_id']])->field('sn,from_shop,to_shop')->find();
//                    $list[$k]['sn'] = $info['sn'];
//                    $list[$k]['change_stock'] = $v['stock'];
//                    if($shop_id==$info['from_shop']){
//                        $list[$k]['type_name'] = '商品调拨(调出)';
//                    }
//                    if($shop_id==$info['to_shop']){
//                        $list[$k]['type_name'] = '商品调拨(调入)';
//                    }
//                    break;
//                case '3':
//                    $list[$k]['type_name'] = '盘盈入库';
//                    $list[$k]['sn'] = $db->name('stock')->where(['id'=>$v['pd_id']])->value('sn');
//                    $list[$k]['change_stock'] = $v['stock'];
//                    break;
//                case '4':
//                    $list[$k]['type_name'] = '盘亏出库';
//                    $list[$k]['sn'] = $db->name('stock')->where(['id'=>$v['pd_id']])->value('sn');
//                    $list[$k]['change_stock'] = $v['stock'];
//                    break;
//                case '5':
//                    $list[$k]['type_name'] = '退货供应商';
//                    $list[$k]['sn'] = $db->name('reject')->where(['id'=>$v['pd_id']])->value('sn');
//                    $list[$k]['change_stock'] = $v['stock'];
//                    break;
//                case '6':
//                    $list[$k]['type_name'] = '零售出库';
//                    $list[$k]['sn'] = $db->name('order')->where(['id'=>$v['pd_id']])->value('sn');
//                    $list[$k]['change_stock'] = $v['stock'];
//                    break;
//                case '7':
//                    $list[$k]['type_name'] = '零售退货';
//                    $list[$k]['sn'] = $db->name('order')->where(['id'=>$v['pd_id']])->value('sn');
//                    $list[$k]['change_stock'] = $v['stock'];
//                    break;
//            }
//        }
//        $init_stock=0;

//        if($page == 1){
//            $init_stock = 0;
//        }else{
//            $init_stock = $first_line_stock;
//        }

        foreach ($list as $k=>$v){
            switch ($v['type']){
                case '1':
                    $list[$k]['type_name'] = '采购';
                    $list[$k]['sn'] = $db->name('purchase')->where(['id'=>$v['pd_id']])->value('sn');
                    $list[$k]['stock'] = '+'.$db->name('purchase_item')->where(['purchase_id'=>$v['pd_id'],'item_id'=>$v['item_id']])->value('num');
                    break;
                case '2':
                    $info = $db->name('allot')->where(['id'=>$v['pd_id']])->field('sn,from_shop,to_shop')->find();
                    $list[$k]['sn'] = $info['sn'];
                    if($shop_id==$info['from_shop']){
                        $list[$k]['type_name'] = '商品调拨(调出)';
                        $list[$k]['stock'] = '-'.$db->name('allot_item')->where(['allot_id'=>$v['pd_id'],'item_id'=>$v['item_id']])->value('num');
                    }
                    if($shop_id==$info['to_shop']){
                        $list[$k]['type_name'] = '商品调拨(调入)';
                        $list[$k]['stock'] = '+'.$db->name('allot_item')->where(['allot_id'=>$v['pd_id'],'item_id'=>$v['item_id']])->value('num');
                    }
                    break;
                case '3':
                    $list[$k]['type_name'] = '盘盈入库';
                    $list[$k]['sn'] = $db->name('stock')->where(['id'=>$v['pd_id']])->value('sn');
                    $list[$k]['stock'] = '+'.$db->name('stock_item')->where(['stock_id'=>$v['pd_id'],'item_id'=>$v['item_id']])->value('num');
                    break;
                case '4':
                    $list[$k]['type_name'] = '盘亏出库';
                    $list[$k]['sn'] = $db->name('stock')->where(['id'=>$v['pd_id']])->value('sn');
                    $list[$k]['stock'] = $db->name('stock_item')->where(['stock_id'=>$v['pd_id'],'item_id'=>$v['item_id']])->value('num');
                    break;
                case '5':
                    $list[$k]['type_name'] = '退货供应商';
                    $list[$k]['sn'] = $db->name('reject')->where(['id'=>$v['pd_id']])->value('sn');
                    $list[$k]['stock'] = '-'.$db->name('reject_item')->where(['reject_id'=>$v['pd_id'],'item_id'=>$v['item_id']])->value('num');
                    break;
                case '6':
                    $list[$k]['type_name'] = '零售出库';
                    $list[$k]['sn'] = $db->name('order')->where(['id'=>$v['pd_id']])->value('sn');
                    $list[$k]['stock'] = '-'.$db->name('order_goods')->where(['order_id'=>$v['pd_id'],'item_id'=>$v['item_id']])->value('num');
                    break;
                case '7':
                    $list[$k]['type_name'] = '零售退货';
                    $list[$k]['sn'] = $db->name('order')->where(['id'=>$v['pd_id']])->value('sn');
                    $list[$k]['stock'] = '+'.$db->name('order_goods')->where(['order_id'=>$v['pd_id'],'item_id'=>$v['item_id']])->value('num');
                    break;
                case '8':
                    $list[$k]['type_name'] = '反入库';
                    $list[$k]['sn'] = $db->name('purchase')->where(['id'=>$v['pd_id']])->value('sn');
                    $list[$k]['stock'] = '-'.$db->name('purchase_item')->where(['purchase_id'=>$v['pd_id'],'item_id'=>$v['item_id']])->value('num');
                    break;
                case '9':
                    $list[$k]['type_name'] = '预定取货';
                    $order_id_tmp = $db->name('goods_book_history')->where(['id'=>$v['pd_id']])->value('order_id');
                    $list[$k]['sn'] = $db->name('order')->where(['id'=>$order_id_tmp])->value('sn');
                    $list[$k]['stock'] = '-'.$db->name('goods_book_history')->where(['id'=>$v['pd_id']])->value('num');
                    break;
            }
            $list[$k]['change_stock'] = $list[$k]['stock'];
//            $change_stock = $v['stock']-$init_stock;
//            $change_stock = $v['stock'];
//            $list[$k]['change_stock'] = $v['stock'];
//            $init_stock = $v['stock'];
//            dump($init_stock);exit;
        }


        $data['list'] = $list;
//        $data['list'] = array_reverse($list);
        $data['totalPage'] = $totalPage;
        $data['totalNum'] = $totalItem;
        $data['pageSize'] = $pageSize;
        return outPut(200,'success',$data);
    }

    /**
     * @api {POST} /admin/StockCheck/get_price_detail 金额变动
     * @apiGroup 库存查询
     * @apiName get_price_detail
     * @apiVersion 1.0.0
     * @apiDescription  获取商品出入库详细信息
     * @apiParam (请求参数) {int} shop_id 仓库（门店）ID
     * @apiParam (请求参数) {int} item_id 商品ID
     * @apiParam (请求参数) {int} curPage 页码
     * @apiParam (请求参数) {str} time 出入库时间
     * @apiParam (请求参数) {int} type 单据类型 1采购单 2调拨单 5零售退货单 7供应商退货单
     * @apiSuccess (返回参数) {int} data.totalPage 数据总页数
     * @apiSuccess (返回参数) {int} data.totalNum 数据总条数
     * @apiSuccess (返回参数) {int} data.pageSize 每页数据条数
     * @apiSuccess (返回参数) {array} data.list 商品金额变动详细信息列表
     * @apiSuccess (返回参数) {float} data.list.md_price_after 门店单位进价
     * @apiSuccess (返回参数) {str} data.list.store_cost_after 库存单位成本
     * @apiSuccess (返回参数) {str} data.list.type_name 单据类型
     * @apiSuccess (返回参数) {str} data.list.sn 单据编号
     * @apiSuccess (返回参数) {int} data.list.change_stock 变化数量
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"success","data":{"list":[{"time":0,"stock":1,"type":1,"pd_id":1,"type_name":"采购","sn":"CG201807190001","change_stock":1},{"time":0,"stock":3,"type":1,"pd_id":1,"type_name":"采购","sn":"CG201807190001","change_stock":2}]}}
     * @apiSampleRequest /admin/StockCheck/get_price_detail
     */
    public function get_price_detail()
    {
        $shop_id = input('post.shop_id');
        $item_id = input('post.item_id');
        $page = input('post.curPage',1);
        $time = input('post.time');
        $type = input('post.type');
        $where = [];
        $where['shop_id'] = $shop_id;
        $where['item_id'] = $item_id;
        if ($time) {
            $time_arr = explode('~',$time);
            $add_s = strtotime($time_arr[0]);
            $add_e = strtotime($time_arr[1])+24*3600;
            $where['time'] = ['between',[$add_s,$add_e]];
        }
        if ($type) {
            $where['type'] = $type;
        }

        $db = Db::connect(config('ddxx'));
        $count = $db->name('purchase_price')
            ->where($where)
            ->field('id')->count();
        $totalItem = $count;   //总记录数(自行定义)
        $pageSize = 10;  //每一页记录数(自行定义)
        $totalPage = ceil($totalItem / $pageSize);  //总页数
        $startItem = ($page - 1) * $pageSize;//根据页码来决定查询数据的节点
        $list = $db->name('purchase_price')
            ->where($where)
            ->field('time,stock,type,pd_id,md_price_after,store_cost_after')
            ->order('id desc')
            ->limit($startItem,$pageSize)
            ->select();
        foreach ($list as $k=>$v){
            switch ($v['type']){
                case '1':
                    $list[$k]['type_name'] = '采购';
                    $list[$k]['sn'] = $db->name('purchase')->where(['id'=>$v['pd_id']])->value('sn');
                    break;
                case '2':
                    $list[$k]['type_name'] = '商品调拨';
                    $list[$k]['sn'] = $db->name('allot')->where(['id'=>$v['pd_id']])->value('sn');
                    break;
                case '3':
                    $list[$k]['type_name'] = '盘盈入库';
                    $list[$k]['sn'] = $db->name('stock')->where(['id'=>$v['pd_id']])->value('sn');
                    break;
                case '4':
                    $list[$k]['type_name'] = '盘亏出库';
                    $list[$k]['sn'] = $db->name('stock')->where(['id'=>$v['pd_id']])->value('sn');
                    break;
                case '5':
                    $list[$k]['type_name'] = '退货供应商';
                    $list[$k]['sn'] = $db->name('reject')->where(['id'=>$v['pd_id']])->value('sn');
                    break;
                case '6':
                    $list[$k]['type_name'] = '零售出库';
                    $list[$k]['sn'] = $db->name('order')->where(['id'=>$v['pd_id']])->value('sn');
                    break;
                case '7':
                    $list[$k]['type_name'] = '零售退货';
                    $list[$k]['sn'] = $db->name('order')->where(['id'=>$v['pd_id']])->value('sn');
                    break;
                case '8':
                    $list[$k]['type_name'] = '反入库';
                    $list[$k]['sn'] = $db->name('purchase')->where(['id'=>$v['pd_id']])->value('sn');
                    break;
                case '9':
                    $list[$k]['type_name'] = '预定取货';
                    $order_id_tmp = $db->name('goods_book_history')->where(['id'=>$v['pd_id']])->value('order_id');
                    $list[$k]['sn'] = $db->name('order')->where(['id'=>$order_id_tmp])->value('sn');
                    break;
            }
        }
        $data['list'] = $list;
        $data['totalPage'] = $totalPage;
        $data['totalNum'] = $totalItem;
        $data['pageSize'] = $pageSize;
        return outPut(200,'success',$data);
    }

}
