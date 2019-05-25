<?php
/**
 *
 * Created by PhpStorm.
 * User: shiyu
 * Date: 2018/7/17 0017
 * Time: 下午 17:03
 */

namespace app\admin\controller;


use app\admin\model\PurchaseModel;
use app\admin\model\SupplierModel;
use cmf\controller\AdminBaseController;
use think\Db;
use think\Session;

class PurchaseController extends AdminBaseController
{

    public function index()
    {
        return view('index');
    }

    /**
     * @api {POST} /admin/Purchase/get_purchase_list 采购单列表
     * @apiGroup 采购单管理
     * @apiName get_purchase_list
     * @apiVersion 1.0.0
     * @apiDescription 采购单列表
     * @apiParam (请求参数) {str} time 采购单时间 可空
     * @apiParam (请求参数) {str} item_name 商品名称 可空
     * @apiParam (请求参数) {str} sn 采购单号 可空
     * @apiParam (请求参数) {int} shop_id 所入仓库 可空
     * @apiParam (请求参数) {int} supplier_id 供应商ID 可空
     * @apiParam (请求参数) {int} status 入库状态 1已入库 0未入库 可空
     * @apiParam (请求参数) {int} page 页码 可空
     * @apiSuccess (返回参数) {Int} code 状态码
     * @apiSuccess (返回参数) {str} msg 请求结果 success成功 fail失败
     * @apiSuccess (返回参数) {array} data 返回结果集
     * @apiSuccess (返回参数) {int} data.totalPage 总页数
     * @apiSuccess (返回参数) {int} data.totalNum 总记录条数
     * @apiSuccess (返回参数) {int} data.pageSize 每页记录条数
     * @apiSuccess (返回参数) {array} data.shop_list 仓库列表
     * @apiSuccess (返回参数) {int} data.shop_list.id 仓库ID
     * @apiSuccess (返回参数) {str} data.shop_list.name 仓库名
     * @apiSuccess (返回参数) {array} data.supplier_list 供应商列表
     * @apiSuccess (返回参数) {int} data.supplier_list.id 供应商ID
     * @apiSuccess (返回参数) {str} data.supplier_list.name 供应商名
     * @apiSuccess (返回参数) {array} data.purchase_list 采购单列表
     * @apiSuccess (返回参数) {int} data.purchase_list.id 采购单ID
     * @apiSuccess (返回参数) {int} data.purchase_list.shop_id 仓库ID
     * @apiSuccess (返回参数) {str} data.purchase_list.sn 采购单编号
     * @apiSuccess (返回参数) {int} data.purchase_list.supplier_id 供应商ID
     * @apiSuccess (返回参数) {int} data.purchase_list.status 采购单状态 1已入库 0未入库
     * @apiSuccess (返回参数) {str} data.purchase_list.creator 采购制单人
     * @apiSuccess (返回参数) {str} data.purchase_list.storer 入库人 （status为0时为空）
     * @apiSuccess (返回参数) {float} data.purchase_list.amount 单据金额
     * @apiSuccess (返回参数) {float} data.purchase_list.bid_amount 进价金额
     * @apiSuccess (返回参数) {float} data.purchase_list.real_amount 实际金额
     * @apiSuccess (返回参数) {int} data.purchase_list.store_time 入库时间（status为0时为空）
     * @apiSuccess (返回参数) {int} data.purchase_list.time 采购单时间
     * @apiSuccess (返回参数) {float} data.purchase_amount_all 单据总金额
     * @apiSuccess (返回参数) {float} data.purchase_bid_amount_all 进价总金额
     * @apiSuccess (返回参数) {float} data.real_amount_all 实际总金额
     * @apiSuccess (返回参数) {float} data.is_show 是否显示金额相关数据 1是 0否
     * @apiSuccessExample {json} 返回样例:
     * {"code":1,"msg":"success","data":{"shop_list":[{"id":18,"name":"总店"},{"id":6,"name":"爱琴海店"},{"id":5,"name":"留云路店"},{"id":16,"name":"龙湖源著店"},{"id":17,"name":"重庆城口店"},{"id":15,"name":"珠江太阳城店"},{"id":21,"name":"两江时光店"},{"id":22,"name":"测试门店10"},{"id":25,"name":"约克郡南郡"},{"id":24,"name":"约克郡北郡"},{"id":26,"name":"蓝光COCO店"},{"id":29,"name":"融创金茂店"},{"id":28,"name":"港城国际店"},{"id":27,"name":"江与城店"},{"id":30,"name":"麓山别苑店"},{"id":31,"name":"奥山别墅店"}],"supplier_list":[{"id":1,"name":"1"},{"id":2,"name":"11"},{"id":3,"name":"供应商1"},{"id":4,"name":"供应商2"},{"id":5,"name":"供应商3"}],"purchase_list":[{"id":1,"shop_id":6,"sn":"CG201807190001","supplier_id":3,"purchase_admin_id":21,"store_admin_id":38,"status":0,"store_time":0,"amount":"100","bid_amount":"100","real_amount":"100","time":0,"creator":"糖糖","storer":"杨呈怡"}],"purchase_list_num":1,"purchase_amount_all":100,"purchase_bid_amount_all":100,"real_amount_all":100}}
     * @apiSampleRequest /admin/Purchase/get_purchase_list
     */
    public function get_purchase_list()
    {
        $shop_id = Session::get('SHOP_ID');
        $except_role_id = [1, 6,7,9];//不限制的门店管理员角色
        $data = [];
        $where = [];
        $is_show = 0;//是否显示金额数据，默认为否
        //获取店铺信息(回去根据当前登录的用户来筛选店铺)
        $admin_id = $this->admin_id;
        $role_id = Db::table('ddxm_role_user')->where('user_id', '=', $admin_id)->value('role_id');
        if (in_array($role_id,$except_role_id) || $admin_id == 1) {
            $is_show = 1;
        }
        //搜索数据
        $page = trim($this->request->param('page', ''));//请求页数
        $itemName = trim($this->request->param('item_name', ''));//商品名称
        $sn = trim($this->request->param('sn', ''));//采购单号
        $supplierId = trim($this->request->param('supplier_id', ''));//供应商ID
        $status = trim($this->request->param('status'));//入库状态
        $shopId = trim($this->request->param('shop_id'));
        $time = $this->request->param('time');
        $where['a.status'] = ['neq', -1];
        if ($itemName != null) {
            $where['c.title'] = ['like', "%$itemName%"];
        }
        if ($sn != null) {
            $where['a.sn'] = $sn;
        }
        if ($supplierId != null) {
            $where['a.supplier_id'] = $supplierId;
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
        if (empty($shopId)) {
            if ($admin_id != 1 && !in_array($role_id, $except_role_id)) {
                $where['a.shop_id'] = $shop_id;
            } else if ($admin_id == 1 || in_array($role_id, $except_role_id)) {
                $shop_id = '';
            }
        } else {
            $where['a.shop_id'] = $shopId;
            $shop_id = $shopId;
        }
        //根据登录用户身份获取店铺数据
        if ($shop_id && $admin_id != 1) {
            $shopDatas = Db::connect(config('ddxx'))->name('shop')->where(['id'=>$shop_id,'status'=>['neq',0]])->field('id,name')->select();
        }
        if (in_array($role_id, $except_role_id) || $admin_id == 1) {
            $shopDatas = Db::connect(config('ddxx'))->name('shop')->where(['status'=>['neq',0]])->field('id,name')->select();
        }
        $data['shop_list'] = $shopDatas;
        //获取所有供应商数据
        $supplier_list = (new SupplierModel())->get_all_supplier('id,name');
        $data['supplier_list'] = $supplier_list;
        //定义分页所需的数据
        $count = (new PurchaseModel())->get_purchase_list($where)->count();
        $totalItem = $count;   //总记录数(自行定义)
        $pageSize = 10;  //每一页记录数(自行定义)
        $totalPage = ceil($totalItem / $pageSize);  //总页数
        $startItem = ($page - 1) * $pageSize;//根据页码来决定查询数据的节点
        //采购单列表
        $list = (new PurchaseModel())->get_purchase_list($where)->limit($startItem, $pageSize)->select();
        foreach ($list as $k => $item) {
            //获取制单人、入库人
            $creator_id = $item['purchase_admin_id'];
            $storer_id = $item['store_admin_id'];
            $creator = \db('user')->where(['id' => $creator_id])->value('user_login');
            $storer = \db('user')->where(['id' => $storer_id])->value('user_login');
            $list[$k]['creator'] = $creator;
            $list[$k]['storer'] = $storer;
            $list[$k]['amount'] = number_format($item['amount'],2,".","");
            $list[$k]['bid_amount'] = number_format($item['bid_amount'],2,".","");
            $list[$k]['real_amount'] = number_format($item['real_amount'],2,".","");
        }

        $data['purchase_list'] = $list;
        $data['totalPage'] = $totalPage;
        $data['totalNum'] = $count;
        $data['pageSize'] = $pageSize;
        //单据总金额
        $all_list = (new PurchaseModel())->get_purchase_list($where)->select();
        $amount = 0;
        $bid_amount = 0;
        $real_amount = 0;
        foreach ($all_list as $kk=>$vv){
            $amount+=$vv['amount'];
            $bid_amount+=$vv['bid_amount'];
            $real_amount+=$vv['real_amount'];
        }
        $data['purchase_amount_all'] = number_format($amount,2,".","");
        //进价总金额
        $data['purchase_bid_amount_all'] = number_format($bid_amount,2,".","");
        //实际总金额
        $data['real_amount_all'] = number_format($real_amount,2,".","");
        $data['is_show'] = $is_show;
        return outPut(200, 'success', $data);
    }


    /**
     * @api {POST} /admin/Purchase/add 添加采购单时获取初始化数据
     * @apiGroup 采购单管理
     * @apiName add
     * @apiVersion 1.0.0
     * @apiDescription 添加采购单时获取初始化数据
     * @apiSuccess (返回参数) {Int} code 状态码 200成功 301失败
     * @apiSuccess (返回参数) {str} msg 请求结果 success成功 fail失败
     * @apiSuccess (返回参数) {array} data 返回结果集
     * @apiSuccess (返回参数) {str} data.sn 采购单号
     * @apiSuccess (返回参数) {Array} data.shop_list 所入仓库
     * @apiSuccess (返回参数) {Array} data.supplier_list 供应商列表
     * @apiSuccess (返回参数) {Array} data.admin_name 操作人姓名
     * @apiSuccessExample {json} 返回样例:
     * {"code":"1","msg":"","data":{"sn":"CG201807192","shop_list":[{"id":18,"name":"总店"},{"id":6,"name":"爱琴海店"},{"id":5,"name":"留云路店"},{"id":16,"name":"龙湖源著店"},{"id":17,"name":"重庆城口店"},{"id":15,"name":"珠江太阳城店"},{"id":21,"name":"两江时光店"},{"id":22,"name":"测试门店10"},{"id":25,"name":"约克郡南郡"},{"id":24,"name":"约克郡北郡"},{"id":26,"name":"蓝光COCO店"},{"id":29,"name":"融创金茂店"},{"id":28,"name":"港城国际店"},{"id":27,"name":"江与城店"},{"id":30,"name":"麓山别苑店"},{"id":31,"name":"奥山别墅店"}],"supplier_list":[{"id":1,"name":"1"},{"id":2,"name":"11"},{"id":3,"name":"供应商1"},{"id":4,"name":"供应商2"},{"id":5,"name":"供应商3"}]}}
     * @apiSampleRequest /admin/Purchase/add
     */
    public function add()
    {
        $shop_id = Session::get('SHOP_ID');
        $except_role_id = [1, 6,7,9];//不限制的门店管理员角色
        $admin_id = $this->admin_id;
        $role_id = Db::table('ddxm_role_user')->where('user_id', '=', $admin_id)->value('role_id');
        //根据登录用户身份获取店铺数据
        if ($shop_id && $admin_id != 1) {
            $shopDatas = Db::connect(config('ddxx'))->name('shop')->where(['id'=>$shop_id,'status'=>["neq",0]])->field('id,name')->select();
        }
        if (in_array($role_id, $except_role_id) || $admin_id == 1) {
            $shopDatas = Db::connect(config('ddxx'))->name('shop')->where(['status'=>["neq",0]])->field('id,name')->select();
        }
        //获取所有供应商数据
        $supplier_list = (new SupplierModel())->get_all_supplier('id,name');
        //生成采购单号
        $last_item = Db::connect(config('ddxx'))->name('purchase')->field('sn')->order('time desc')->limit(1)->find();
        if (!$last_item) {
            $end_num = '0001';
        } else {
            $last_sn = substr($last_item['sn'], -4);
            $end_num = $newStr = sprintf('%04s', $last_sn + 1);
        }
        $new_sn = 'CG' . date("Ymd", time()) . $end_num;
        //获取操作员工
        $admin_name = Db::name('user')->where(['id' => $admin_id])->value('user_login');
        $data = [
            'sn' => $new_sn,
            'shop_list' => $shopDatas,
            'supplier_list' => $supplier_list,
            'admin_name' => $admin_name
        ];
        return outPut(200, '', $data);
    }

    /**
     * @api {POST} /admin/Purchase/editOrView 编辑或查看采购单
     * @apiGroup 采购单管理
     * @apiName editOrView
     * @apiVersion 1.0.0
     * @apiDescription 编辑或查看采购单，data.purchaseInfo.status值为0时可编辑、删除，为1时只可查看
     * @apiParam (请求参数) {int} purchase_id 采购单ID
     * @apiSuccess (返回参数) {Int} code 状态码 200成功 301失败
     * @apiSuccess (返回参数) {str} msg 请求结果 success成功 fail失败
     * @apiSuccess (返回参数) {array} data 返回结果集
     * @apiSuccess (返回参数) {array} data.purchaseInfo 采购单基本数据
     * @apiSuccess (返回参数) {int} data.purchaseInfo.shop_id 所入仓库ID
     * @apiSuccess (返回参数) {int} data.purchaseInfo.id 采购单ID
     * @apiSuccess (返回参数) {str} data.purchaseInfo.sn 采购单号
     * @apiSuccess (返回参数) {int} data.purchaseInfo.time 采购单时间戳
     * @apiSuccess (返回参数) {float} data.purchaseInfo.amount 采购单单据金额
     * @apiSuccess (返回参数) {float} data.purchaseInfo.bid_amount 采购单进价金额
     * @apiSuccess (返回参数) {float} data.purchaseInfo.real_amount 采购单实际金额
     * @apiSuccess (返回参数) {str} data.purchaseInfo.creator 采购制单人
     * @apiSuccess (返回参数) {str} data.purchaseInfo.iner 入库人
     * @apiSuccess (返回参数) {str} data.purchaseInfo.remark 采购单备注
     * @apiSuccess (返回参数) {str} data.purchaseInfo.status 采购单状态 1入库 0未入库
     * @apiSuccess (返回参数) {Array} data.shopDatas 所入仓库
     * @apiSuccess (返回参数) {int} data.shopDatas.id 仓库ID
     * @apiSuccess (返回参数) {str} data.shopDatas.name 仓库名
     * @apiSuccess (返回参数) {Array} data.supplierDatas 供应商列表
     * @apiSuccess (返回参数) {int} data.supplierDatas.id 供应商ID
     * @apiSuccess (返回参数) {str} data.supplierDatas.name 供应商名字
     * @apiSuccess (返回参数) {array} data.item_list 采购单的商品数据
     * @apiSuccess (返回参数) {int} data.item_list.purchase_item_id 采购单的商品ID
     * @apiSuccess (返回参数) {int} data.item_list.item_id 商品ID
     * @apiSuccess (返回参数) {str} data.item_list.title 商品名
     * @apiSuccess (返回参数) {str} data.item_list.bar_code 商品条形码
     * @apiSuccess (返回参数) {str} data.item_list.cname 分类名
     * @apiSuccess (返回参数) {float} data.item_list.cg_standard_price 采购单价
     * @apiSuccess (返回参数) {int} data.item_list.num 数量
     * @apiSuccess (返回参数) {int} data.item_list.cg_amount 采购金额
     * @apiSuccess (返回参数) {float} data.item_list.md_standard_price 门店单价
     * @apiSuccess (返回参数) {float} data.item_list.md_amount 门店进价
     * @apiSuccess (返回参数) {str} data.item_list.remark 商品备注
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"success","data":{"is_show":0,"shopDatas":[{"id":18,"name":"总店"},{"id":6,"name":"爱琴海店"},{"id":5,"name":"留云路店"},{"id":16,"name":"龙湖源著店"},{"id":17,"name":"重庆城口店"},{"id":15,"name":"珠江太阳城店"},{"id":21,"name":"两江时光店"},{"id":22,"name":"测试门店10"},{"id":25,"name":"约克郡南郡"},{"id":24,"name":"约克郡北郡"},{"id":26,"name":"蓝光COCO店"},{"id":29,"name":"融创金茂店"},{"id":28,"name":"港城国际店"},{"id":27,"name":"江与城店"},{"id":30,"name":"麓山别苑店"},{"id":31,"name":"奥山别墅店"}],"supplierDatas":[{"id":1,"name":"1"},{"id":2,"name":"11"},{"id":3,"name":"供应商1"},{"id":4,"name":"供应商2"},{"id":5,"name":"供应商3"}],"item_list":[{"cname":"洗发沐浴","title":"三鹿三聚氰胺奶粉2段3罐","bar_code":"654654","item_id":2,"num":100,"remark":"备注1","purchase_item_id":1,"cg_amount":"200.0000","md_amount":"300.0000","cg_univalent":"2.0000","md_univalent":"3.0000","amount":"400","real_amount":"450"},{"cname":"惠氏","title":"惠氏金装婴儿奶粉 3段2罐","bar_code":"6615249817","item_id":3,"num":100,"remark":"备注2","purchase_item_id":2,"cg_amount":"200.0000","md_amount":"300.0000","cg_univalent":"2.0000","md_univalent":"3.0000","amount":"400","real_amount":"450"}],"purchaseInfo":{"shop_id":6,"supplier_id":1,"sn":"CG201807190001","time":1531929600,"amount":"400","bid_amount":"600","real_amount":"450","remark":"备注4","purchase_admin_id":1,"creator":"admin"}}}
     * @apiSampleRequest /admin/Purchase/editOrView
     */
    public function editOrView()
    {
        $id = input('post.purchase_id');
        $db = Db::connect(config('ddxx'));
        //可编辑的情况
        $data = [];
        $shop_id = Session::get('SHOP_ID');
        $admin_id = $this->admin_id;
        $except_role_id = [1, 6,7,9];
        $role_id = Db::table('ddxm_role_user')->where('user_id', '=', $admin_id)->value('role_id');
        $is_show = 0;
        if (in_array($role_id,$except_role_id) || $admin_id == 1) {
            $is_show = 1;
        }
        $data['is_show'] = $is_show;
        //根据登录用户身份获取店铺数据
        if ($shop_id && $admin_id != 1) {
            $shopDatas = $db->name('shop')->where('id', $shop_id)->field('id,name')->select();
        }
        if (in_array($role_id, $except_role_id) || $admin_id == 1) {
            $shopDatas = $db->name('shop')->field('id,name')->select();
        }
        $data['shopDatas'] = $shopDatas;
        //供应商数据
        $supplierDatas = \db('supplier')->field('id,name')->select();
        $data['supplierDatas'] = $supplierDatas;
        //商品数据
        $item_list = (new PurchaseModel())->get_one_purchase($id);
        //键名转换
        foreach ($item_list as $k=>$v){
            $item_list[$k]['cg_standard_price'] = $v['cg_univalent'];
            $item_list[$k]['md_standard_price'] = $v['md_univalent'];
            unset($item_list[$k]['cg_univalent']);
            unset($item_list[$k]['md_univalent']);
        }
        $data['item_list'] = $item_list;
        //采购单基本数据
        $purchase_info = $db->name('purchase')
            ->where(['id' => $id])->field('shop_id,supplier_id,sn,time,amount,bid_amount,real_amount,remark,purchase_admin_id,store_admin_id,status,id')
            ->find();
        $creator = \db('user')->where(['id' => $purchase_info['purchase_admin_id']])->value('user_login');
        $iner = \db('user')->where(['id' => $purchase_info['store_admin_id']])->value('user_login');
        $purchase_info['creator'] = $creator;
        $purchase_info['iner'] = isset($iner)?$iner:'--';
        $data['purchaseInfo'] = $purchase_info;
        return outPut(200, 'success', $data);
    }


    /**
     * @api {POST} /admin/Purchase/save 保存采购单信息
     * @apiGroup 采购单管理
     * @apiName save
     * @apiVersion 1.0.0
     * @apiDescription 保存采购单信息，有id时为修改，无id时新增
     * @apiParam (请求参数) {int} id 采购单ID，可以为空
     * @apiParam (请求参数) {array} item_list 采购单所含商品
     * @apiParam (请求参数) {int} item_list.item_id 商品ID
     * @apiParam (请求参数) {int} item_list.num 商品数量
     * @apiParam (请求参数) {float} item_list.cg_univalent 商品采购单价
     * @apiParam (请求参数) {float} item_list.md_univalent 商品门店单价
     * @apiParam (请求参数) {str} item_list.remark 商品备注
     * @apiParam (请求参数) {int} supplier_id 供应商ID
     * @apiParam (请求参数) {int} shop_id 门店ID
     * @apiParam (请求参数) {str} sn 采购单编号
     * @apiParam (请求参数) {float} amount 采购单总单据金额
     * @apiParam (请求参数) {float} bid_amount 采购单总进价金额
     * @apiParam (请求参数) {float} real_amount 采购单实际金额
     * @apiSuccess (返回参数) {Int} code 状态码 200成功 301失败
     * @apiSuccess (返回参数) {str} msg 请求结果 success成功 fail失败
     * @apiSuccess (返回参数) {str} data 请求结果信息
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"success","data":[]}
     * @apiSampleRequest /admin/Purchase/save
     */
    public function save()
    {
//        $arr = [
//            'item_list'=>[
//                ['item_id'=>2,'num'=>100,'cg_univalent'=>2.00,'cg_amount'=>200.00,'md_univalent'=>3.00,'md_amount'=>300.00,'remark'=>'备注1'],
//                ['item_id'=>3,'num'=>100,'cg_univalent'=>2.00,'cg_amount'=>200.00,'md_univalent'=>3.00,'md_amount'=>300.00,'remark'=>'备注2']
//            ],
//            'supplier_id'=>1,
//            'shop_id'=>5,
//            'time'=>'2018-07-19',
//            'sn'=>'CG201807190001',
//            'amount'=>400.00,
//            'bid_amount'=>600.00,
//            'real_amount'=>450.00,
//            'remark'=>'备注4',
//            'id'=>''
//        ];
//        $data = $arr;
        $data = input('post.');
        $id = $data['id'];
        $shop_id = $data['shop_id'];
        $sn = $data['sn'];
        $supplier_id = $data['supplier_id'];
        $real_amount = $data['real_amount'];
        $item_list = $data['item_list'];
        $amount = $data['amount'];
        $bid_amount = $data['bid_amount'];
//        dump($item_list);exit;
        $remark = $data['remark'];
        if($amount==0&&$amount!=$real_amount){
            return outPut(301,"非法数据！");
        }
        foreach ($item_list as $k => $v) {
            $md_amount = $v['md_univalent'] * $v['num'];
            $item_list[$k]['md_amount'] = $md_amount;
            $cg_amount = $v['cg_univalent'] * $v['num'];
            $item_list[$k]['cg_amount'] = $cg_amount;
        }

        $purchase_data = [
            'shop_id' => $shop_id,
            'sn' => $sn,
            'supplier_id' => $supplier_id,
            'purchase_admin_id' => $this->admin_id,
            'status' => 0,
            'amount' => $amount,
            'bid_amount' => $bid_amount,
            'real_amount' => $real_amount,
            'time' => time(),
            'remark' => $remark
        ];
//        dump($item_list);exit;
        $db = Db::connect(config('ddxx'));
        $db->startTrans();
        if ($id == '') {
            try {
                //生成采购单号
                $last_item = Db::connect(config('ddxx'))->name('purchase')->field('sn')->order('id desc')->limit(1)->find();
                if (!$last_item) {
                    $end_num = '0001';
                } else {
                    $last_sn = substr($last_item['sn'], -4);
                    $end_num = $newStr = sprintf('%04s', $last_sn + 1);
                }
                $new_sn = 'CG' . date("Ymd", time()) . $end_num;
                $purchase_data['sn'] = $new_sn;
                $res1 = $db->name('purchase')->insert($purchase_data);
                $purchase_id = $db->name('purchase')->getLastInsID();
                if ($res1) {
                    foreach ($item_list as $k => $v) {
                        $item_list[$k]['purchase_id'] = $purchase_id;
                        $item_list[$k]['shop_id'] = $shop_id;
                    }
                    $res2 = $db->name('purchase_item')->insertAll($item_list);
                    if ($res2) {
                        $db->commit();
                        return outPut(200, 'success');
                    }
                }
            } catch (\Exception $e) {
                $db->rollback();
                return outPut(301, 'fail', $e->getMessage());
            }
        } else {
            try {
                unset($purchase_data['sn']);
                $isset = $db->name('purchase')->where(['id' => $id])->field('id')->find();
                if (!$isset) {
                    return outPut(301, 'fail', '没有该条数据');
                }
                $res1 = $db->name('purchase')->where(['id' => $id])->update($purchase_data);
                if ($res1) {
                    $res2 = $db->name('purchase_item')->where(['purchase_id' => $id])->delete();
                    foreach ($item_list as $k => $v) {
                        $item_list[$k]['purchase_id'] = $id;
                        $item_list[$k]['shop_id'] = $shop_id;
                    }
                    $res3 = $db->name('purchase_item')->insertAll($item_list);
                    if ($res2 && $res3) {
                        $db->commit();
                        return outPut(200, 'success', '保存成功');
                    }
                }
            } catch (\Exception $e) {
                $db->rollback();
                return outPut(301, 'fail', $e->getMessage());
            }
        }
    }


    /**
     * @api {POST} /admin/Purchase/before_purchase 点击入库时获取采购单详情
     * @apiGroup 采购单管理
     * @apiName before_purchase
     * @apiVersion 1.0.0
     * @apiDescription 点击入库时获取采购单详情
     * @apiParam (请求参数) {int} id 采购单ID
     * @apiSuccess (返回参数) {Int} code 状态码 200成功 301失败
     * @apiSuccess (返回参数) {str} msg 请求结果 success成功 fail失败
     * @apiSuccess (返回参数) {array} data 请求结果信息
     * @apiSuccess (返回参数) {array} data.item_list 采购单包含商品
     * @apiSuccess (返回参数) {str} data.item_list.title 商品名
     * @apiSuccess (返回参数) {str} data.item_list.cname 商品分类
     * @apiSuccess (返回参数) {str} data.item_list.bar_code 条形码
     * @apiSuccess (返回参数) {str} data.item_list.num 数量
     * @apiSuccess (返回参数) {str} data.item_list.remark 商品备注
     * @apiSuccess (返回参数) {str} data.item_list.purchase_item_id 采购单商品id(不显示)
     * @apiSuccess (返回参数) {str} data.item_list.item_id 商品id(不显示)
     * @apiSuccess (返回参数) {str} data.purchaser 采购制单人
     * @apiSuccess (返回参数) {str} data.storer 入库人
     * @apiSuccess (返回参数) {str} data.remark 采购单备注
     * @apiSuccess (返回参数) {str} data.time 入库日期
     * @apiSuccess (返回参数) {str} data.purchase_id 采购单ID
     * @apiSuccess (返回参数) {str} data.shop_id 门店（仓库）ID
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"success","data":{"item_list":[{"cname":"洗发沐浴","title":"三鹿三聚氰胺奶粉2段3罐","bar_code":"654654","num":100,"remark":"备注1","purchase_item_id":1},{"cname":"惠氏","title":"惠氏金装婴儿奶粉 3段2罐","bar_code":"6615249817","num":100,"remark":"备注2","purchase_item_id":2}],"purchaser":"admin","storer":"admin","remark":"备注4","purchase_id":1,"time":"2018-07-23"}}
     * @apiSampleRequest /admin/Purchase/before_purchase
     */
    public function before_purchase()
    {
        $id = input('post.id');
        if (!$id) {
            return outPut(301, 'fail', '参数丢失！');
        }
        //查询相关采购单信息
        $purchase_item_data = (new PurchaseModel())->get_one_purchase($id);
        $purchase_info = Db::connect(config('ddxx'))->name('purchase')->alias('p')
            ->join('tf_shop s', 'p.shop_id = s.id', 'LEFT')
            ->where(['p.id' => $id])
            ->field('s.id as shop_id,s.name,p.purchase_admin_id,p.remark,p.id as purchase_id')
            ->find();
        $purchaser = Db::name('user')->where(['id' => $purchase_info['purchase_admin_id']])->value('user_login');
        $storer = Db::name('user')->where(['id' => $this->admin_id])->value('user_login');
        $data = [
            'item_list' => $purchase_item_data,
            'purchaser' => $purchaser,
            'storer' => $storer,
            'purchase_id' => $purchase_info['purchase_id'],
            'remark' => $purchase_info['remark'],
            'time' => date("Y-m-d", time()),
            'shop_id' => $purchase_info['shop_id']
        ];
        return outPut(200, 'success', $data);
    }


    /**
     * @api {POST} /admin/Purchase/purchase_save 确定入库
     * @apiGroup 采购单管理
     * @apiName purchase_save
     * @apiVersion 1.0.0
     * @apiDescription 确定入库,前端判断用户输入的本次数量和总数量相等才允许提交
     * @apiParam (请求参数) {str} shop_id 店铺（仓库ID）
     * @apiParam (请求参数) {str} purchase_id 采购单ID
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"success","data":[]}
     * @apiSampleRequest /admin/Purchase/purchase_save
     */
    public function purchase_save()
    {
        /*确定入库后保存店铺ID、商品ID及其库存到门店商品库存表tf_shop_item，tf_purchase_price表只作为库存信息及真假成本价格变动记录表
         * */
        $data = input('post.');
//        $data = $arr;
        $shop_id = $data['shop_id'];
        $purchase_id = $data['purchase_id'];
        $db = Db::connect(config('ddxx'));
        $fail = 0;
        $purchase_info = (new PurchaseModel())->get_one_purchase($purchase_id);
        $db->startTrans();
        try {
            foreach ($purchase_info as $key => $value) {
                $isset = $db->name('purchase_price')->where(['shop_id' => $shop_id, 'item_id' => $value['item_id']])->order('id desc')->find();
                if (!$isset) {
                    $md_price_after = $value['md_univalent'];
                    if($value['amount']==$value['real_amount']){
                        $store_cost_after = $value['cg_amount']/$value['num'];
                    }else{
                        $store_cost_after = $value['cg_amount'] / $value['amount'] * $value['real_amount'] / $value['num'];
                    }
                    $data = [
                        'shop_id' => $shop_id,
                        'type' => 1,
                        'pd_id' => $purchase_id,
                        'item_id' => $value['item_id'],
                        'md_price_before' => 0,
                        'md_price_after' => $md_price_after,
                        'store_cost_before' => 0,
                        'store_cost_after' => $store_cost_after,
                        'stock' => $value['num'],
                        'time' => time()
                    ];
                    $res = $db->name('purchase_price')->insert($data);
                    $ress = $db->name('shop_item')->insert(['stock'=>$value['num'],'shop_id'=>$shop_id,'item_id'=>$value['item_id']]);
                    if (!$res||!$ress) {
                        $fail += 1;
                    }
                } else {
                    $md_price_before_all = $isset['md_price_after'] * $isset['stock'];
                    $md_price_this_all = $value['md_amount'];
                    $stock_before_all = $isset['stock'];
                    $stock_this_all = $value['num'];
                    //上一次的商品入库后金额为这一次的商品入库前金额
                    $md_price_before = $isset['md_price_after'];
                    //某商品库存单位成本=（原库存商品单位成本*库存内该商品数量+本次该商品入库成本）/（库存内该商品数量+本次入库数量）
                    $md_price_after = ($md_price_before_all + $md_price_this_all) / ($stock_before_all + $stock_this_all);
                    $store_cost_before_all = $isset['store_cost_after'] * $isset['stock'];
                    if($value['amount']==$value['real_amount']){
                        $store_cost_this_all = $value['cg_amount'];
                    }else{
                        $store_cost_this_all = $value['cg_amount'] / $value['amount'] * $value['real_amount'];
                    }
                    $store_cost_before = $isset['store_cost_after'];
                    $store_cost_after = ($store_cost_before_all + $store_cost_this_all) / ($stock_before_all + $stock_this_all);
                    $data = [
                        'shop_id' => $shop_id,
                        'type' => 1,
                        'pd_id' => $purchase_id,
                        'item_id' => $value['item_id'],
                        'md_price_before' => $md_price_before,
                        'md_price_after' => $md_price_after,
                        'store_cost_before' => $store_cost_before,
                        'store_cost_after' => $store_cost_after,
                        'stock' => $stock_before_all + $stock_this_all,
                        'time' => time()
                    ];
                    $res = $db->name('purchase_price')->insert($data);
                    $ress = $db->name('shop_item')->where(['shop_id'=>$shop_id,'item_id'=>$value['item_id']])->setInc('stock',$stock_this_all);
                    if (!$res||!$ress) {
                        $fail += 1;
                    }
                }
            }
        } catch (\Exception $e) {
            $db->rollback();
            return outPut(301, 'fail', '入库失败,' . $e->getMessage());
        }
        if ($fail == 0) {
            if ($db->name('purchase')->where(['id' => $purchase_id])->update(['status' => 1, 'store_admin_id' => $this->admin_id, 'store_time' => time()])) {
                $db->commit();
                return outPut(200, 'success', '入库成功');
            }
        } else {
            $db->rollback();
            return outPut(301, 'fail', '入库失败');
        }
    }

    /**
     * @api {POST} /admin/Purchase/delete 删除采购单
     * @apiGroup 采购单管理
     * @apiName delete
     * @apiVersion 1.0.0
     * @apiDescription 删除采购单
     * @apiParam (请求参数) {int} purchase_id 采购单ID
     * @apiSuccess (返回参数) {Int} code 状态码 200成功 301失败
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"success","data":[]}
     * @apiSampleRequest /admin/Purchase/delete
     */
    public function delete()
    {
        $id = input('post.purchase_id');
        $status = Db::connect(config('ddxx'))->name('purchase')->where(['id' => $id])->value('status');
        if ($status == 1) {
            return outPut(301, 'fail', '该采购单已入库，不能删除！');
        }
        if (Db::connect(config('ddxx'))->name('purchase')->where(['id' => $id])->update(['status' => -1])) {
            return outPut(200, 'success', '删除成功');
        } else {
            return outPut(301, 'fail', '删除失败');
        }
    }

    /**
     * @api {POST} /admin/Purchase/get_s_cate 获取商品二级分类
     * @apiGroup 采购单管理
     * @apiName get_s_cate
     * @apiVersion 1.0.0
     * @apiDescription 获取商品二级分类
     * @apiParam (请求参数) {int} id 一级分类ID
     * @apiSuccess (返回参数) {Int} code 状态码 200成功 301失败
     * @apiSuccess (返回参数) {str} msg 请求结果 success成功 fail失败
     * @apiSuccess (返回参数) {array} data 请求结果信息
     * @apiSuccess (返回参数) {int} id 二级分类ID
     * @apiSuccess (返回参数) {str} cname 二级分类名
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"success","data":[]}
     * @apiSampleRequest /admin/Purchase/delete
     */
    public function get_s_cate()
    {
        $id = input('post.id');
        $s_list = Db::connect(config('ddxx'))->name('item_category')->where(['pid' => $id, 'status' => ['neq', -1]])->field('id,cname')->select();
        $data = ['s_list' => $s_list];
        return outPut(200, 'success', $data);
    }

    /**
     * @api {POST} /admin/Purchase/get_goods_by_code 根据商品二维码获取商品信息
     * @apiGroup 采购单管理
     * @apiName get_goods_by_code
     * @apiVersion 1.0.0
     * @apiDescription 根据商品二维码获取商品信息
     * @apiParam (请求参数) {str} bar_code 商品条形码
     * @apiSuccess (返回参数) {Int} code 状态码 200成功 301失败
     * @apiSuccess (返回参数) {str} msg 请求结果 success成功 fail失败
     * @apiSuccess (返回参数) {array} data 请求结果信息
     * @apiSuccess (返回参数) {array} data.item_info 商品数据
     * @apiSuccess (返回参数) {str} data.item_info.id 商品ID
     * @apiSuccess (返回参数) {str} data.item_info.title 商品名称
     * @apiSuccess (返回参数) {str} data.item_info.cname 商品分类
     * @apiSuccess (返回参数) {str} data.item_info.bar_code 商品条形码
     * @apiSuccess (返回参数) {str} data.item_info.price 商品原价
     * @apiSuccess (返回参数) {str} data.item_info.is_price_control 是否控价 1是 0不是
     * @apiSuccess (返回参数) {float} data.item_info.cg_standard_price 采购单价
     * @apiSuccess (返回参数) {float} data.item_info.md_standard_price 门店单价
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"success","data":{"item_info":{"id":4,"title":"惠氏启赋金装二段2罐装","cname":"惠氏","is_price_control":0,"price":"359.00","bar_code":"1231431232","cg_standard_price":"309.00","md_standard_price":"339.00"}}}
     * @apiSampleRequest /admin/Purchase/get_goods_by_code
     */
    public function get_goods_by_code()
    {
        $code = input('post.bar_code');
        $result = Db::connect(config('ddxx'))->name('item')->alias('a')
            ->join('tf_item_category c', 'a.type=c.id', 'LEFT')
            ->where(['a.bar_code' => $code])
            ->field('a.id,a.title,c.cname,a.is_price_control,a.price,a.bar_code,a.cg_standard_price,a.md_standard_price')
            ->find();
        if (empty($result)) {
            return outPut(301, 'fail', '没有找到相关商品');
        }
        $data['item_info'] = $result;
        return outPut(200, 'success', $data);
    }

    /**
     * @api {POST} /admin/Purchase/reverse_purchase 反入库
     * @apiGroup 采购单管理
     * @apiName reverse_purchase
     * @apiVersion 1.0.0
     * @apiDescription 操作有误时采购单反入库，重算成本价
     * @apiParam (请求参数) {int} purchase_id 采购单ID
     * @apiSuccess (返回参数) {Int} code 状态码 200成功 301失败
     * @apiSuccess (返回参数) {str} msg 请求结果 success成功 fail失败
     * @apiSuccess (返回参数) {array} data 请求结果信息
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"success","data":[]}
     * @apiSampleRequest /admin/Purchase/reverse_purchase
     */
    public function reverse_purchase()
    {
        $purchase_id = input('post.purchase_id');
        if (!$purchase_id) {
            return outPut(301, 'fail', '丢失ID参数');
        }
        $db = Db::connect(config('ddxx'));
        $shop_id = $db->name('purchase')->where(['id'=>$purchase_id])->value('shop_id');
        $purchase_item_list = (new PurchaseModel())->get_one_purchase($purchase_id);
        $db->startTrans();
        try{
            foreach ($purchase_item_list as $k=>$v){
                //(库内数量*成本-反入库数量*成本)/库内数量-反入库数量
                $stock_info = $db->name('purchase_price')->where(['shop_id'=>$shop_id,'item_id'=>$v['item_id']])->order('id desc')->find();
                $stock_num = $stock_info['stock'];
                $md_price_before = $stock_info['md_price_after'];
                $store_cost_before = $stock_info['store_cost_after'];
                //减少商品库存
                $new_stock = $stock_num-$v['num'];
                //---1.只有一次采购，new_stock等于0的情况
                if($new_stock==0){
                    $md_price_after = 0;
                    $store_cost_after = 0;
                }else{
                    //---2.多次采购，new_stock不等于0的情况
                    //计算反入库后商品的假成本
                    $md_price_after = ($stock_num*$stock_info['md_price_after']-$v['num']*$v['md_univalent'])/($stock_num-$v['num']);
                    //计算反入库后商品的真成本
                    $store_cost_after = ($stock_num*$stock_info['store_cost_after']-$v['num']*$v['cg_univalent'])/($stock_num-$v['num']);
                }
                //添加反入库记录到purchase_price表
                $res1 = $db->name('purchase_price')->insert(
                    [
                        'shop_id'=>$shop_id,'type'=>8,'pd_id'=>$purchase_id,'item_id'=>$v['item_id'],'md_price_before'=>$md_price_before,
                        'md_price_after'=>$md_price_after,'store_cost_before'=>$store_cost_before,'store_cost_after'=>$store_cost_after,
                        'stock'=>$new_stock,'time'=>time()
                    ]
                );
                //修改采购单为未入库，记录反入库操作人
                $user = Session::get('name');
                $res2 = $db->name('purchase')->where(['id'=>$purchase_id])->update(['status'=>0,'reverse_purchase_user'=>$user,'reverse_purchase_time'=>time()]);
                //减少shop_item表中实际库存
                $res3 = $db->name('shop_item')->where(['shop_id'=>$shop_id,'item_id'=>$v['item_id']])->setDec('stock',$v['num']);
            }
            $db->commit();
            return outPut(200,'success','操作成功');
        }catch (\Exception $e){
            $db->rollback();
            return outPut(301,'fail','操作失败:'.$e->getMessage());
        }
    }

    public function purchase_print()
    {
        $id = input('post.purchase_id');
        //查询相关采购单信息
        $purchase_item_data = (new PurchaseModel())->get_one_purchase($id);
        $purchase_info = Db::connect(config('ddxx'))->name('purchase')->alias('p')
            ->join('tf_shop s', 'p.shop_id = s.id', 'LEFT')
            ->where(['p.id' => $id])
            ->field('s.id as shop_id,s.name,p.purchase_admin_id,p.remark,p.id as purchase_id,p.sn as purchase_sn,p.supplier_id,p.store_time')
            ->find();
        $supplier_name = \db('supplier')->where(['id' => $purchase_info['supplier_id']])->field('name')->find();
        $purchaser = Db::name('user')->where(['id' => $purchase_info['purchase_admin_id']])->value('user_login');
        $storer = Db::name('user')->where(['id' => $this->admin_id])->value('user_login');
        $data = [
            'item_list' => $purchase_item_data,
            'purchaser' => $purchaser,
            'storer' => $storer,
            'remark' => $purchase_info['remark'] ? $purchase_info['remark'] : '无',
            'time' => date("Y-m-d",$purchase_info['store_time']),
            'shop_name' => $purchase_info['name'],
            'purchase_sn' => $purchase_info['purchase_sn'],
            'supplier_name' => $supplier_name['name']
        ];
        return outPut(200, 'success', $data);
    }

}
