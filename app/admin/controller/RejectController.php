<?php
/**
 * Created by PhpStorm.
 * User: shiyu
 * Date: 2018/7/23 0023
 * Time: 下午 18:54
 */

namespace app\admin\controller;


use app\admin\model\AllotModel;
use app\admin\model\MemberGoodsModel;
use app\admin\model\ProfitManageModel;
use app\admin\model\reject\RejectModel;
use app\admin\model\StockCheckModel;
use think\Db;
use think\Session;

//调拨单
class RejectController extends BaseController
{

    protected $except_role_id = [1,6,7,9];
    // public function index()
    // {
    //     $id = input('post.id');
    //     $id = 1;
    //     //修改调拨状态，重新计算调入仓库商品成本价
    //     $db = Db::connect(config('ddxx'));
    //     $status = $db->name('allot')->where(['id'=>$id])->value('status');
    //     if($status==1){
    //         return outPut(301,'fail','该单据已完成调拨，请不要重复入库！');
    //     }
    //     //计算假成本
    //     $allot_info = (new AllotModel())->get_allot_info($id);
    //     dump($allot_info);
    // }

    public function index()
    {
        return view('index');
    }

    /**
     * @api {POST} /admin/reject/getInitData 获取初始化数据【进入首页就获取】
     * @apiGroup 退货单管理
     * @apiName getInitData
     * @apiVersion 1.0.0
     * @apiDescription 获取默认数据
     * @apiSuccess (返回参数) {Int} is_show 对门店的一些内容的限制显示
     *  - 0 就不显示相应内容
     *  - 1 不限制
     * @apiSuccess (返回参数) {Array} supplier_list 请求结果
     * @apiSuccess (返回参数) {Int} supplier_list.id 供应商id
     * @apiSuccess (返回参数) {String} supplier_list.name 供应商name
     * @apiSuccess (返回参数) {Array} shop_list 仓库列表
     * @apiSuccess (返回参数) {Array} shop_list.id 仓库id
     * @apiSuccess (返回参数) {Array} shop_list.name 仓库name
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"获取成功","data":{"is_show":1,"supplier_list":[{"id":1,"name":"1"},{"id":2,"name":"11"},{"id":3,"name":"供应商1"},{"id":4,"name":"供应商2"},{"id":5,"name":"供应商3"}],"shop_list":[{"id":18,"name":"总店"},{"id":6,"name":"爱琴海店"},{"id":5,"name":"留云路店"},{"id":16,"name":"龙湖源著店"},{"id":17,"name":"重庆城口店"},{"id":15,"name":"珠江太阳城店"},{"id":21,"name":"两江时光店"},{"id":22,"name":"测试门店10"},{"id":25,"name":"约克郡南郡"},{"id":24,"name":"约克郡北郡"},{"id":26,"name":"蓝光COCO店"},{"id":29,"name":"融创金茂店"},{"id":28,"name":"港城国际店"},{"id":27,"name":"江与城店"},{"id":30,"name":"麓山别苑店"},{"id":31,"name":"奥山别墅店"}]}}
     * @apiSampleRequest /admin/reject/getInitData
     */
    public function getInitData()
    {
        $shop_id = Session::get('SHOP_ID');
        $is_show = 0;//是否显示金额数据，默认为否
        //获取店铺信息(回去根据当前登录的用户来筛选店铺)
        $admin_id = $this->admin_id;
        $s_where = [];
        $role_id = Db::name('role_user')->where('user_id', '=', $admin_id)->value('role_id');
        if ($admin_id == 1) {
            $is_show = 1;
        }elseif (in_array($role_id,$this->except_role_id)) {
            $is_show = 1;
        }else{
            $s_where['id'] = ['=',$shop_id];
        }
        $data['is_show'] = $is_show;
        $data['supplier_list'] = model('Supplier')->get_all_supplier('id,name');
        $data['shop_list'] = model('shop.Shop')->where($s_where)->field('id,name')->select();
        $data['first_cate'] = model('Item')->tiemYlian('id,cname');
        return outPut(200,'获取成功',$data);
    }

    /**
     * @api {POST} /admin/reject/getRejectList 退货单列表
     * @apiGroup 退货单管理
     * @apiName getRejectList
     * @apiVersion 1.0.0
     * @apiDescription 退货单列表
     * @apiParam (请求参数) {String} [time] 调拨单时间
     * @apiParam (请求参数) {String} [item_name] 商品名称
     * @apiParam (请求参数) {String} [sn] 退货单号
     * @apiParam (请求参数) {Int} [shop_id]  退货仓库
     * @apiParam (请求参数) {Int} [status] 调拨状态
     *  - 1 已出库
     *  - 0 出库中
     * @apiParam (请求参数) {Int} [page] 页码
     * @apiSuccess (返回参数) {Array} reject_list 退货单列表
     * @apiSuccess (返回参数) {Int} reject_list.id 退货单ID
     * @apiSuccess (返回参数) {Int} [reject_list.shop_id] 仓库id
     * @apiSuccess (返回参数) {String} reject_list.sn 退货单编号
     * @apiSuccess (返回参数) {String} reject_list.supplier 供应商
     * @apiSuccess (返回参数) {String} reject_list.refund_shop_name 调出仓库名
     * @apiSuccess (返回参数) {String} reject_list.out_stock_user 操作出库的人员
     * @apiSuccess (返回参数) {String} reject_list.refund_bill_user 填写退货单的人
     * @apiSuccess (返回参数) {String} reject_list.out_stock_time 退货单出库时间
     * @apiSuccess (返回参数) {String} reject_list.addtime 退货单添加时间
     * @apiSuccess (返回参数) {float} reject_list.amount 退货单总金额
     * @apiSuccess (返回参数) {Int} reject_list.status 退货状态
     * - 1 已出库
     *  - 0 出库中
     * @apiSuccess (返回参数) {float} reject_list.remark 退货单备注
     * @apiSuccess (返回参数) {Int} order_num 单据总笔数
     * @apiSuccess (返回参数) {Float} total_amount 进价总金额
     * @apiSuccess (返回参数) {Int} totalPage 总页数
     * @apiSuccess (返回参数) {Int} totalNum 总条数
     * @apiSuccess (返回参数) {Int} pageSize 每页条数
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"success","data":{"reject_list":[{"id":1,"shop_id":22,"sn":"TH201807261606","out_stock_user":"杨成一","supplier":"供应商1","refund_bill_user":"雷雷","out_stock_time":"2018-07-27 19:05","remark":"测试退货单","amount":"12400.00","addtime":"2018-07-22 19:28","status":0,"refund_shop_name":"测试门店10"}],"totalPage":1,"order_num":1,"total_amount":12400}}
     * @apiSampleRequest /admin/Allot/get_allot_list
     */
    public function getRejectList()
    {
        $shop_id = Session::get('SHOP_ID');
        $admin_name = Session::get('name');
        $data = [];
        $where = [];
        //获取店铺信息(回去根据当前登录的用户来筛选店铺)
        $admin_id = $this->admin_id;
        $role_id = Db::table('ddxm_role_user')->where('user_id', '=', $admin_id)->value('role_id');
        //搜索数据
        $page = trim($this->request->param('page', 1));//请求页数
        $itemName = trim($this->request->param('item_name', ''));//商品名称
        $sn = trim($this->request->param('sn', ''));//退货单号
        $refund_shop = trim($this->request->param('shop_id', ''));//退货仓库
        $supplier = trim($this->request->param('supplier', ''));//供应商
        $status = trim($this->request->param('status'));//出库状态 单价
        $time = trim($this->request->param('time'));

        if ($itemName != null) {
            $where['c.title'] = ['like', "%$itemName%"];
        }
        if ($sn != null) {
            $where['a.sn'] = $sn;
        }
        if ($admin_id != 1 && !in_array($role_id, $this->except_role_id)) {
            $where['a.shop_id'] = ['in',$shop_id];
        }
        if ($refund_shop){
            $where['a.shop_id'] = $refund_shop;
        }
        if ($status != null) {//此处status有0的情况
            $where['a.status'] = $status;
        }else{
            $where['a.status'] = ['neq',-1];
        }
        if ($time) {
            $time_arr = explode('~',$time);
            $stime = strtotime($time_arr[0]);
            $etime = strtotime($time_arr[1]);
            if ($stime == $etime) {
                $etime = $etime + 24 * 3600;
            }
            $where['a.addtime'] = ['between', [$stime, $etime]];
        }
        //调拨单列表
        //定义分页所需的数据
        $count = (new RejectModel())->getRejectLists($where)->count();
        $totalItem = $count;   //总记录数(自行定义)
        $pageSize = 10;  //每一页记录数(自行定义)
        $data['totalNum'] = $count;
        $data['pageSize'] = $pageSize;
        $totalPage = ceil($totalItem / $pageSize);  //总页数
        $startItem = ($page - 1) * $pageSize;//根据页码来决定查询数据的节点
        $list = (new RejectModel())->getRejectLists($where)->limit($startItem,$pageSize)->select();
        foreach ($list as &$v) {
            $v['refund_shop_name'] = model('shop.Shop')->where(['id' => $v['shop_id']])->value('name');
            $v['refund_bill_user'] = db('user')->where(['id'=>$v['refund_bill_user']])->value('user_login');
            $v['out_stock_user'] = db('user')->where(['id'=>$v['out_stock_user']])->value('user_login');
            $v['supplier'] = db('supplier')->where(['id'=>$v['supplier']])->value('name');
            $v['addtime'] = date('Y-m-d H:i',$v['addtime']);
            $v['out_stock_time'] = empty($v['out_stock_time'])?'':date('Y-m-d H:i',$v['out_stock_time']);
        }
        $data['reject_list'] = $list;
        $data['totalPage'] = $totalPage;
        //单据总笔数
        $data['order_num'] = $count;
        //退货总金额
        $lists = (new RejectModel())->getRejectLists($where)->select();
        $amount = 0;
        foreach ($lists as $vs){
            $amount += $vs['amount'];
        }
        $data['total_amount'] = $amount;
        return outPut(200, 'success', $data);
    }


    public function getNewBillData()
    {
        $count = (new RejectModel())->where("FROM_UNIXTIME(stime,'%Y-%m-%d')",'=',date('Y-m-d',time()))->count();
        if ($count) {
            $sn = 'DB' . date("Ymd", time()) .str_pad($count +1,5,'0',STR_PAD_LEFT );
        } else {
            $sn = 'DB' . date("Ymd", time()) .str_pad(1,5,'0',STR_PAD_LEFT );
        }
        //获取操作员工
        $administor = Session::get('name');
        $data = [
            'sn' => $sn,
            'time' => date('Y-m-d',time()),
            'outer' => $administor
        ];
        return outPut(200, '', $data);
    }


    /**
     * @api {POST} /admin/Reject/update 保存退货单信息
     * @apiGroup 退货单管理
     * @apiName save
     * @apiVersion 1.0.0
     * @apiDescription 新增或修改退货单
     * @apiParam (请求参数) {Int} [reject_id] 退货单ID
     * @apiParam (请求参数) {Array} item_list 退货单所含商品
     * @apiParam (请求参数) {Int} [item_list.reject_item_id] 反正你传给我就是了 【修改的时候才会有】
     * @apiParam (请求参数) {Int} item_list.item_id 商品ID
     * @apiParam (请求参数) {Int} item_list.num 数量
     * @apiParam (请求参数) {Float} item_list.cost_price 退货时的商品单价
     * @apiParam (请求参数) {String} item_list.remark 备注
     * @apiParam (请求参数) {Int} shop_id 退货门店（仓库）ID
     * @apiParam (请求参数) {Int} supplier_id 供应商ID
     * @apiParam (请求参数) {Int} remark 备注
     * @apiParamExample {json} 请求样例:
     * {"reject_id":1,"shop_id":22,"supplier":5,"reject_item_list":[{"reject_item_id":1,"item_id":2,"num":50,"cost_price":100,"remark":"谁知道呢"}]}
     * @apiParamExample {json} 请求样例:
     * {"shop_id":22,"supplier":5,"reject_item_list":[{"item_id":2,"num":50,"cost_price":100,"remark":"谁知道呢"}],"remark":"你懂的"}
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"success","data":[]}
     * @apiSampleRequest /admin/Reject/update
     */
    public function update()
    {
        $data = $this->request->param();
        $reject_item_list = isset($data['item_list'])?$data['item_list']:[];
        $reject_id = isset($data['reject_id'])?$data['reject_id']:0;
        if (empty($reject_item_list)) {
            return outPut(301,'没有退货商品');
        }
        // var_dump($data['item_list']);die;
        $amount = 0;
        foreach ($reject_item_list as $v) {
            $amount += $v['cost_price'] * $v['num'];
        }
        $reject_data = [];
        $reject_data['shop_id'] = $data['shop_id'];
        $reject_data['supplier'] = $data['supplier_id'];
        $reject_data['amount'] = $amount;
        $reject_data['remark'] = empty($data['remark'])?'':$data['remark'];

        $rejectModel = new RejectModel();
        $rejectItemModel = model('reject.RejectItem');
        $db = Db::connect(config('ddxx'));
        $db->startTrans();
        try{
            if($reject_id){
                $info = $rejectModel::get($reject_id);
                if(!$info){
                    return outPut(301,'单据不存在或已删除');
                }
                if($info->status == 1 ){
                    return outPut(301,'当前单据已出库，不可编辑！');
                }
                $change_item = [];
                $fail=0;
                foreach ($reject_item_list as $k => $vs) {
                    $stock = model('shop.Stock')->getItemStock($vs['item_id'],$data['shop_id']);
                    if ($stock < $vs['num']) {
                        $change_item[$k]['reject_item_id'] = $vs['reject_item_id'];
                        $change_item[$k]['item_id'] = $vs['item_id'];
                        $change_item[$k]['stock'] = $stock;
                    }else{
                        continue;
                    }
                    //修改shop_item表中的库存
                    //冻结中的库存 = 更新前的冻结中库存-该调拨单据原冻结的库存+本次提交的调拨数量
                    $reject_ice_now = $db->name('reject_item')->where(['reject_id'=>$reject_id,'item_id'=>$vs['item_id']])->value('num');//现已冻结的退货中库存
                    $stock_ice_all = $db->name('shop_item')->where(['shop_id'=>$data['shop_id'],'item_id'=>$vs['item_id']])->value('stock_ice');
                    $ress = $db->name('shop_item')->where(['shop_id'=>$data['shop_id'],'item_id'=>$v['item_id']])->update(['stock_ice'=>$stock_ice_all-$reject_ice_now+$vs['num']]);
                    if(!$ress){
                        $fail+=1;
                    }

                }
                if ($change_item) {
                    return outPut(302,'部分商品库存不足，请查验', $change_item);
                }
                $res = $rejectModel->saveDatas($reject_data,$reject_id);
                if (!$res) {
                    $db->rollback();
                    return outPut(301,'修改失败',$res);
                }
                $del_res = $rejectItemModel->delRejectItem(['reject_id'=>$reject_id]);
                if ($del_res) {
                    $all_data = [];
                    foreach ($reject_item_list as $k => $v){
                        $all_data[$k] = $v;
                        $all_data[$k]['reject_id'] = $reject_id;
                        $all_data[$k]['addtime'] = time();
                    }
                    $rejectItemModel->allowField(true)->saveAll($all_data);
                }else{
                    $db->rollback();
                    return outPut(301,'修改出现异常',$del_res);
                }
                $db->commit();
                return outPut(200,'修改成功');
            }else{
                if (empty($reject_data['shop_id'])) {
                    return outPut(301,'请选择退货仓库');
                }
                if (empty($reject_data['supplier'])) {
                    return outPut(301,'请选择退供应商');
                }
                //生成sn
                $count = $rejectModel->where("FROM_UNIXTIME(addtime,'%Y-%m-%d')",'=',date('Y-m-d',time()))->count();
                if ($count) {
                    $sn = 'TH' . date("Ymd", time()) .str_pad($count +1,5,'0',STR_PAD_LEFT );
                } else {
                    $sn = 'TH' . date("Ymd", time()) .str_pad(1,5,'0',STR_PAD_LEFT );
                }
                $change_item = [];
                $fail = 0;
                foreach ($reject_item_list as $k => $vs) {
                    $stock = model('shop.Stock')->getItemStock($vs['item_id'],$data['shop_id']);
                    if ($stock < $vs['num']) {
                        $change_item[$k]['item_id'] = $vs['item_id'];
                        $change_item[$k]['stock'] = $stock;
                    }else{
                        if (!($db->name('shop_item')->where(['shop_id'=>$data['shop_id'],'item_id'=>$vs['item_id']])->setInc('stock_ice',$vs['num']))) {
                            $fail+=1;
                        }
                    }
                }
                if ($change_item) {
                    return outPut(302,'部分商品库存不足，请查验', $change_item);
                }
                $reject_data['sn'] = $sn;
                //获取操作员工
                $reject_data['refund_bill_user'] = $this->admin_id;
                $reject_data['addtime'] = time();
                $res = $rejectModel->saveDatas($reject_data,$reject_id);
                $reject_id = $rejectModel->id;
                $data = [];
                foreach ($reject_item_list as $k => $v){
                    $data[$k] = $v;
                    $data[$k]['reject_id'] = $reject_id;
                    $data[$k]['addtime'] = time();
                }
                $res2= $rejectItemModel->allowField(true)->saveAll($data);
                if($res&&$res2&&$fail==0){
                    $db->commit();
                    return outPut(200,'添加成功');
                }
                $db->rollback();
                return outPut(301,'fail','操作失败');
            }
        } catch (\Exception $e) {
            $db->rollback();
            return outPut(301,'操作失败：'.$e->getMessage());
        }
    }


    /**
     * @api {POST} /admin/Reject/getRejectDetail 退货单详细信息
     * @apiGroup 退货单管理
     * @apiName getRejectDetail
     * @apiVersion 1.0.0
     * @apiDescription 【查看|修改|出库】的时候要传ID 然后查看的时候没有确定按钮吧
     * @apiParam (请求参数) {Int} [reject_id] 退货单ID
     * @apiParamExample {json} 请求样例:
     * {"reject_id":1}
     * @apiSuccess (返回参数) {Int} id 退货单id
     * @apiSuccess (返回参数) {Int} shop_id 退货仓库
     * @apiSuccess (返回参数) {String} sn 退货单号
     * @apiSuccess (返回参数) {String} out_stock_user 出库人
     * @apiSuccess (返回参数) {String} supplier 供应商
     * @apiSuccess (返回参数) {String} refund_bill_user 退货单添加人
     * @apiSuccess (返回参数) {String} out_stock_time 出库时间
     * @apiSuccess (返回参数) {String} remark 退货单描述
     * @apiSuccess (返回参数) {Float} amount 退货单总额
     * @apiSuccess (返回参数) {String} addtime 退货单添加时间
     * @apiSuccess (返回参数) {Int} status 退货单状态
     *  - 1 为1的时候只能执行查看操作
     *  - 0 为0的时候可以编辑删除等操作
     * @apiSuccess (返回参数) {Array} reject_item_list 退货单商品列表
     * @apiSuccess (返回参数) {Int} reject_item_list.reject_item_id 退货商品表的id
     * @apiSuccess (返回参数) {Int} reject_item_list.item_id 商品id
     * @apiSuccess (返回参数) {Int} reject_item_list.reject_id 退货单id
     * @apiSuccess (返回参数) {Float} reject_item_list.price 商品单价
     * @apiSuccess (返回参数) {Int} reject_item_list.num 退货商品数量
     * @apiSuccess (返回参数) {Float} reject_item_list.sum_price 退货商品总金额
     * @apiSuccess (返回参数) {String} reject_item_list.remark 退货商品备注
     * @apiSuccess (返回参数) {String} reject_item_list.title 退货商品名称
     * @apiSuccess (返回参数) {Number} reject_item_list.bar_code 退货商品条形码
     * @apiSuccess (返回参数) {String} reject_item_list.cname 退货商品分类名
     * @apiSuccess (返回参数) {String} reject_item_list.cost_price 单位成本
     * @apiSuccessExample {json} 返回样例:
     * {
            "code": 200,
            "msg": "success",
            "data": {
                "id": 1,
                "shop_id": 22,
                "sn": "TH201807261606",
                "out_stock_user": "admin",
                "supplier": 1,
                "refund_bill_user": null,
                "out_stock_time": "2018-07-27",
                "remark": "测试退货单",
                "amount": "12400.00",
                "addtime": "2018-07-22",
                "status": 0,
                "reject_item_list": [
                    {
                        "reject_item_id": 1,
                        "item_id": 2,
                        "reject_id": 1,
                        "cost_price": "60.00",
                        "num": 40,
                        "sum_price": "2400.00",
                        "remark": "灭的描述",
                        "title": "三鹿三聚氰胺奶粉2段3罐",
                        "bar_code": "654654",
                        "cate_name": "洗发沐浴"
                    }
                ]
            }
        }
     * @apiSampleRequest /admin/Reject/getRejectDetail
     */
    public function getRejectDetail()
    {
        $id = $this->request->param('reject_id');
        //可编辑的情况
        $rejectModel = new RejectModel();
        $shop_id = $rejectModel->where(['id'=>$id])->value('shop_id');
        $info = $rejectModel->getRejectDetail($id);
        $info['refund_bill_user'] = db('user')->where(['id'=>$info['refund_bill_user']])->value('user_login');
        $info['out_stock_user'] = db('user')->where(['id'=>$info['out_stock_user']])->value('user_login');
        $info['addtime'] = date('Y-m-d H:i:s',$info['addtime']);
        $info['out_stock_time'] = date('Y-m-d H:i:s',$info['out_stock_time']);
        $reject_item_list = $info['reject_item_list'];
        foreach ($reject_item_list as &$v) {
            $item_info = model('Item')->where(['id'=>$v['item_id']])->field('title,bar_code,type')->find();
            $stock_info = Db::connect(config('ddxx'))->name('purchase_price')
                ->where(['shop_id'=>$shop_id,'item_id'=>$v['item_id']])
                ->field('store_cost_after,stock')
                ->order('id desc')
                ->find();
            $cate_name = Db::connect(config('ddxx'))->name('item_category')->where(['id'=>$item_info['type']])->value('cname');
            $v['stock'] = $stock_info['stock'];
            // $v['cost_price'] = $stock_info['store_cost_after'];
            $v['title'] = $item_info['title'];
            $v['bar_code'] = $item_info['bar_code'];
            $v['cname'] = $cate_name;
            $v['sum_price'] = round($v['cost_price']*$v['num'],2);
        }
        return outPut(200,'success',$info);
    }

    /**
     * @api {POST} /admin/Reject/confirmOutStock 确认出库
     * @apiGroup 退货单管理
     * @apiName confirmOutStock
     * @apiVersion 1.0.0
     * @apiDescription 确认出库，重新计算仓库的商品成本和库存
     * @apiParam (请求参数)     {Int} reject_id 调拨单ID
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"success","data":[]}
     * @apiSampleRequest /admin/Reject/confirmOutStock
     */
    public function confirmOutStock()
    {
        $id = $this->request->param('reject_id');
        //修改调拨状态，重新计算调入仓库商品成本价
        $rejectModel = new RejectModel();
        $list = $rejectModel->getRejectDetail($id);
        if($list['status'] == 1){
            return outPut(301,'当前单据已出库，请勿重复操作');
        }
        if($list['status'] == -1){
            return outPut(301,'当前单据已删除');
        }
        $db = Db::connect(config('ddxx'));
        $db->startTrans();
        $fail=0;
        try{
            $out_stock_user = $this->admin_id;
            $Reject_res = $rejectModel->allowField(true)->save(['out_stock_user'=>$out_stock_user,'status'=>1,'out_stock_time'=>time()],['id'=>$id]);
            if($Reject_res){
                foreach ($list['reject_item_list'] as $k=> $v){
                    $res1 = model('shop.Stock')->updateItemStock($v['item_id'],$list['shop_id'],$v['num'],$id,5);//修改库存
                    //减少shop_item表中该商品的冻结库存及库存
                    $res2 = $db->name('shop_item')->where(['shop_id'=>$list['shop_id'],'item_id'=>$v['item_id']])->setDec('stock_ice',$v['num']);
                    if (!$res2||!$res1) {
                        $fail+=1;
                    }
                }
                if($fail==0){
                    $db->commit();
                    return outPut(200,'success','出库成功');
                }
                $db->rollback();
                return outPut(301,'fail','出库失败');
            }else{
                $db->rollback();
                return outPut(301,'fail','出库失败');
            }
        }catch (\Exception $e){
            $db->rollback();
            return outPut(301,'系统异常:'.$e->getMessage());
        }
    }

    /**
     * @api {POST} /admin/Reject/delete 删除退货单
     * @apiGroup 退货单管理
     * @apiName delete
     * @apiVersion 1.0.0
     * @apiDescription 删除退货单
     * @apiParam (请求参数) {int} reject_id 退货单id
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"success","data":[]}
     * @apiSampleRequest /admin/Reject/delete
     */
    public function delete()
    {
        $id = $this->request->param('reject_id');
        $rejectModel = new RejectModel;
        $status = $rejectModel->where(['id'=>$id])->value('status');
        if($status == 1){
            return outPut(301,'该退货单已完成，不能删除！');
        }
        $db = Db::connect(config('ddxx'));
        $db->startTrans();
        try{
            $res = $rejectModel->allowField(true)->save(['status'=>-1],['id'=>$id]);
            if ($res){
                //删除之后释放冻结中的库存
                $reject_item_list = $db->name('reject_item')->alias('a')
                    ->join('tf_reject b','a.reject_id = b.id','LEFT')
                    ->where(['a.reject_id'=>$id])
                    ->field('a.*,b.shop_id')
                    ->select();
                $fail = 0;
                foreach ($reject_item_list as $k=>$v){
                    $ress = $db->name('shop_item')->where(['shop_id'=>$v['shop_id'],'item_id'=>$v['item_id']])->setDec('stock_ice',$v['num']);
                    if(!$ress){
                        $fail+=1;
                    }
                }
                if($fail==0){
                    $db->commit();
                    return outPut(200,'success','删除成功');
                }
            }
            $db->rollback();
            return outPut(301,'fail','删除失败');
        }catch (\Exception $e){
            $db->rollback();
            return outPut(301,'fail','删除失败');
        }
    }


    /**
     * @api {Post} /admin/Reject/getPage ajax获取分页商品数据
     * @apiGroup 退货单管理
     * @apiName getPage
     * @apiVersion 1.0.0
     * @apiDescription  ajax获取分页商品数据
     * @apiParam (请求参数) {str} curPage 页数 不可空
     * @apiParam (请求参数) {str} goods_name 商品名称 可空
     * @apiParam (请求参数) {str} f_cate 一级分类ID 可空
     * @apiParam (请求参数) {str} s_cate 二级分类ID 可空
     * @apiSuccess (返回参数) {Int} code 状态码 200成功 301错误
     * @apiSuccess (返回参数) {str} msg 请求结果 success成功 fail失败
     * @apiSuccess (返回参数) {array} data 返回参数结果集
     * @apiSuccess (返回参数) {int} data.pageSize 每页记录条数
     * @apiSuccess (返回参数) {int} data.totalItem 总记录条数
     * @apiSuccess (返回参数) {int} data.totalPage 总页数
     * @apiSuccess (返回参数) {array} data.data_content 商品数据
     * @apiSuccess (返回参数) {str} data.data_content.id 商品ID
     * @apiSuccess (返回参数) {str} data.data_content.title 商品名称
     * @apiSuccess (返回参数) {str} data.data_content.cname 商品分类
     * @apiSuccess (返回参数) {str} data.data_content.bar_code 商品条形码
     * @apiSuccess (返回参数) {str} data.data_content.price 商品原价
     * @apiSuccess (返回参数) {str} data.data_content.md_price 商品门店进价
     * @apiSuccess (返回参数) {str} data.data_content.store_cost 商品入库成本
     * @apiSuccess (返回参数) {str} data.data_content.stock 商品库存
     * @apiSuccess (返回参数) {str} data.data_content.is_price_control 是否控价 1是 0不是
     * @apiSuccessExample {json} 返回样例:
     * {"code":"1","msg":"成功","time":1532416271,"data":{"totalItem":4,"pageSize":10,"totalPage":1,"data_content":[{"id":2,"title":"三鹿三聚氰胺奶粉2段3罐","cname":"洗发沐浴","is_price_control":0,"price":"100.00","bar_code":"654654","cg_standard_price":"666.00","md_standard_price":"555.00","stock":null,"md_price":null,"store_cost":null},{"id":3,"title":"惠氏金装婴儿奶粉 3段2罐","cname":"惠氏","is_price_control":0,"price":"268.00","bar_code":"6615249817","cg_standard_price":"210.00","md_standard_price":"258.00","stock":null,"md_price":null,"store_cost":null},{"id":4,"title":"惠氏启赋金装二段2罐装","cname":"惠氏","is_price_control":0,"price":"359.00","bar_code":"1231431232","cg_standard_price":"309.00","md_standard_price":"339.00","stock":null,"md_price":null,"store_cost":null},{"id":5,"title":"测试商品","cname":"湿巾纸巾","is_price_control":1,"price":"52.00","bar_code":"54779876984","cg_standard_price":"30.00","md_standard_price":"40.00","stock":null,"md_price":null,"store_cost":null}]}}
     * @apiSampleRequest /admin/Reject/getPage
     */
    public function getPage()
    {
        //1.获取数据（curPage）
        $page = input('post.');
//        $page = [
//            'goods_name' => '',
//            's_cate' => '',
//            'curPage' => 1
//        ];
        $shop_id = Session::get('SHOP_ID');
        $goods_name = $page['goods_name'];
        $s_cate = $page['s_cate'];
        $curPage = $page['curPage'];
        $shopId = $page['shop_id'];
        $bar_code = $page['bar_code'];
        $where = [];
        $where['c.status'] = 1;
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
        //3.查询数据
        $res = (new StockCheckModel())->get_stock_list($where)->limit($startItem,$pageSize)->select();
        $db = Db::connect(config('ddxx'));
        foreach ($res as $k=>$v){
            $stock = $db->name('purchase_price')
                ->where(['shop_id'=>$shop_id,'item_id'=>$v['item_id']])->order('id desc')
                ->field('md_price_after,store_cost_after')->find();
            //真实库存
            $real_stock = model('shop.Stock')->getItemStock($v['item_id'],$shop_id);
//            if($shopId && $real_stock <= 0 ){
//                unset($res[$k]);
//                continue;
//            }
            $res[$k]['stock'] = $real_stock;
            $res[$k]['md_price'] = $stock['md_price_after'];
            $res[$k]['cost_price'] = $stock['store_cost_after'];
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
     * @api {POST} /admin/Reject/get_s_cate 获取商品二级分类
     * @apiGroup 退货单管理
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
     * @apiSampleRequest /admin/Reject/get_s_cate
     */
    public function get_s_cate()
    {
        $id = input('post.id');
        $s_list = Db::connect(config('ddxx'))->name('item_category')->where(['pid' => $id, 'status' => ['neq', -1]])->field('id,cname')->select();
        $data = ['s_list' => $s_list];
        return outPut(200, 'success', $data);
    }


    /**
     * @api {Post} /admin/Reject/get_goods_by_code 条形码
     * @apiGroup 退货单管理
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
     * @apiSuccess (返回参数) {str} data.cost_price 商品库存单位成本
     * @apiSuccess (返回参数) {str} data.stock 商品库存
     * @apiSuccessExample {json} 返回样例:
     * {"code":200,"msg":"success","data":{"shop_name":"测试门店10","title":"测试商品2","price":"200.00","bar_code":"154854","item_id":746,"cname":"模型玩具","shop_id":22,"stock":80,"md_price":"120.00","store_cost":"879.18"}}
     * @apiSampleRequest /admin/Reject/get_goods_by_code
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
        $res['cost_price'] = $stock['store_cost_after'];
        if (empty($res)) {
            return outPut(301, 'fail', '没有找到相关商品');
        }
        return outPut(200, 'success', $res);
    }

}
