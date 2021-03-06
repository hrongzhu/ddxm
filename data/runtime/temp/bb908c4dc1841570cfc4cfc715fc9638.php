<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:47:"themes/admin_simpleboot3/admin\allot\index.html";i:1554867174;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <!-- Set render engine for 360 browser -->
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- HTML5 shim for IE8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->


    <link href="__TMPL__/public/assets/themes/<?php echo cmf_get_admin_style(); ?>/bootstrap.min.css" rel="stylesheet">
    <link href="__TMPL__/public/assets/themes/<?php echo cmf_get_admin_style(); ?>/bootstrap-select.min.css" rel="stylesheet">
    <link href="__TMPL__/public/assets/simpleboot3/css/simplebootadmin.css" rel="stylesheet">
    <link href="__STATIC__/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <style>
        form .input-order {
            margin-bottom: 0px;
            padding: 0 2px;
            width: 42px;
            font-size: 12px;
        }

        form .input-order:focus {
            outline: none;
        }

        .table-actions {
            margin-top: 5px;
            margin-bottom: 5px;
            padding: 0px;
        }

        .table-list {
            margin-bottom: 0px;
        }

        .form-required {
            color: red;
        }
    </style>
    <link href="/plugins/layer/2.4/skin/layer.css" rel="stylesheet">
    <script type="text/javascript">
        //全局变量
        var GV = {
            ROOT: "__ROOT__/",
            WEB_ROOT: "__WEB_ROOT__/",
            JS_ROOT: "static/js/",
            APP: '<?php echo \think\Request::instance()->module(); ?>'/*当前应用名*/
        };
    </script>
    <script src="__TMPL__/public/assets/js/jquery-1.10.2.min.js"></script>
    <script src="__STATIC__/js/wind.js"></script>
    <script src="__TMPL__/public/assets/js/bootstrap.min.js"></script>
    <script src="__TMPL__/public/assets/js/jquery.qrcode.min.js"></script>
    <script src="/plugins/layer/2.4/layer.js"></script>
    <script src="__TMPL__/public/assets/js/bootstrap-select.min.js"></script>
    <script src="__TMPL__/public/assets/js/defaults-zh_CN.min.js"></script>
    <script src="/plugins/h-ui.admin/js/H-ui.admin.page.js"></script>
    <!-- <script src="/ddxm_admin/public/plugins/h-ui/js/H-ui.min.js"></script> -->
    <script>
        Wind.css('artDialog');
        Wind.css('layer');
        $(function () {
            $("[data-toggle='tooltip']").tooltip();
            $("li.dropdown").hover(function () {
                $(this).addClass("open");
            }, function () {
                $(this).removeClass("open");
            });
        });
    </script>
    <?php if(APP_DEBUG): ?>
        <style>
            #think_page_trace_open {
                z-index: 9999;
            }
        </style>
    <?php endif; ?>

<link rel="stylesheet" type="text/css" href="/plugins/layer/2.4/css/layui.css">
<link rel="stylesheet" type="text/css" href="__STATIC__/css/inventory.css">
<style type="text/css">
    table {border-collapse:collapse;margin: auto;width: 90%}
    table tr td{border:1px solid black;text-align: center}
    table tr{line-height: 36px}
</style>
<!-- 页面中所有需动态更新数据的元素片段加入 artTemplate模版 -->
<!-- IE8 需加入补丁 -->
<script src="__STATIC__/js/es5-sham.min.js"></script>
<script src="__STATIC__/js/json3.min.js"></script>
<!-- ****** -->
<script src="__STATIC__/js/artTemplate/template-web.js"></script>
</head>
<body>
<div class="invertory-content">
    <div class="invertory">
        <!-- 搜索头部 -->
        <div class="i-header">
            <form class="layui-form" action="" lay-filter="searchHeader">
                <div class="layui-form-item">
                    <input type="text" class="layui-input border-ff choose-time" placeholder="调拨时间选择">
                    <input type="text" class="layui-input border-ff choose-itemname" placeholder="商品名称">
                    <input type="text" class="layui-input border-ff choose-sn" placeholder="调拨单号">
                    <div class="layui-input-inline">
                        <select name="modules" class="from-shop" lay-verify="required" lay-search="">
                        </select>
                    </div>
                    <div class="layui-input-inline">
                        <select name="modules" class="to-shop" lay-verify="required" lay-search="">
                        </select>
                    </div>
                    <div class="layui-input-inline">
                        <select name="modules" class="choose-status" lay-verify="required" lay-search="">
                            <option value="">全部状态</option>
                            <option value="1">已完成</option>
                            <option value="0">调拨中</option>
                        </select>
                    </div>
                    <button type="button" class="layui-btn layui-btn-primary search-allot">查询</button>
                </div>
            </form>
        </div>
        <!--  -->
        <div class="i-title fj-row">
            <div class="summary border-ff">

            </div>
            <button type="button" class="btn-success-reset add-allot" data-toggle="modal" data-target="#editAllot">
                新增调拨单
            </button>
        </div>
        <!-- 列表 -->
        <div class="i-content">
            <table lay-even class="layui-table" id="table_list">

            </table>
            <!-- 分页 -->
            <div id="pagelayer">

            </div>
        </div>
    </div>
</div>
<!-- 调拨单弹出框（模态框） -->
<div class="modal frame-modal edit-allot fade" data-backdrop="static" id="editAllot" tabindex="-1" role="dialog"
     aria-labelledby="purchaselabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <p class="modal-title" id="purchaselabel">调拨单详情</p>
            </div>
            <div class="modal-body">
                <div class="top">
                    <form class="layui-form layui-form-pane" action="" lay-filter="editDefaultValue">
                        <div class="layui-form-item fj-row">
                            <div class="layui-inline">
                                <label class="layui-form-label">调出仓库</label>
                                <div class="layui-input-inline">
                                    <select name="modules" class="choosef-shop" lay-verify="required" lay-search="" lay-filter="choose_shop">

                                    </select>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">调入仓库</label>
                                <div class="layui-input-inline">
                                    <select name="modules" class="chooset-shop" lay-verify="required" lay-search="">

                                    </select>
                                </div>
                            </div>
                            <div class="layui-inline edittime hidden">
                               <label class="layui-form-label">调拨单日期</label>
                               <div class="layui-input-inline">
                                    <input type="text" readonly class="layui-input edit-time" placeholder="">
                               </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">调拨单号</label>
                                <div class="layui-input-inline">
                                    <input type="text" readonly class="layui-input edit-sn" placeholder="">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="list">
                    <div class="list-table">
                        <table class="layui-table" id="product_list">

                        </table>
                    </div>
                    <form class="layui-form layui-form-pane detail" action="">
                        <div class="layui-form-item fj-row">
                            <div class="layui-inline bidamount">
                                <label class="layui-form-label">进价金额</label>
                                <div class="layui-input-inline">
                                    <input type="text" readonly class="layui-input bid-amount" placeholder="">
                                </div>
                            </div>
                            <div class="layui-inline realamount">
                                <label class="layui-form-label">成本金额</label>
                                <div class="layui-input-inline">
                                    <input type="text" readonly class="layui-input real-amount" placeholder="">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">备注信息</label>
                                <div class="layui-input-inline">
                                    <input type="text" class="layui-input remark" placeholder="">
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item fj-row">
                            <div class="layui-inline">
                                <label class="layui-form-label">出库人员</label>
                                <div class="layui-input-inline">
                                    <input type="text" readonly class="layui-input from-people" placeholder="">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">入库人员</label>
                                <div class="layui-input-inline">
                                    <input type="text" readonly class="layui-input to-people" placeholder="">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <!-- 权限问题 -->
                <button type="button" class="btn-blue-default" id="sure_allot">调入仓库</button>
                <button type="button" class="btn-blue-default" id="add_allot">确定保存</button>
                <button type="button" class="btn-blue-default hidden sure" data-dismiss="modal">确定</button>
            </div>
        </div>
    </div>
</div>
<!-- 商品信息弹出框 -->
<div class="modal frame-modal put-product fade" id="editProduct" tabindex="-1" role="dialog"
     aria-labelledby="purchaselabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <p class="modal-title" id="productlabel">商品信息</p>
            </div>
            <div class="modal-body">
                <div class="top">
                    <form class="layui-form layui-form-pane" action="" lay-filter="searchProduct">
                        <div class="layui-inline">
                            <input type="text" class="layui-input border-ff search-name" placeholder="商品名称">
                        </div>
                        <div class="layui-inline">
                            <input type="text" class="layui-input border-ff search-sn" placeholder="条形码">
                        </div>
                        <div class="layui-inline">
                            <div class="layui-input-inline">
                                <select name="modules" class="search-type1" lay-verify="required" lay-search=""
                                        lay-filter="search_type">
                                </select>
                            </div>
                        </div>
                        <div class="layui-inline">
                            <div class="layui-input-inline">
                                <select name="modules" class="search-type2" lay-verify="required" lay-search="">
                                    <option value="">请先选择一级分类</option>
                                </select>
                            </div>
                        </div>
                        <button type="button" class="layui-btn layui-btn-primary search-product">查询</button>
                    </form>
                </div>
                <div class="list">
                    <div class="list-table layui-form">
                        <table class="layui-table" id="productItems">
                    
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="fj-row footer">
                    <div id="productPage">

                    </div>
                    <div>
                        <button type="button" class="btn-default-border" data-dismiss="modal">取消</button>
                        <button type="button" class="btn-blue-default" id="add_product">确定</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/plugins/layer/2.4/layui.js"></script>
<script src="__STATIC__/js/inventory/public.js" type="text/javascript"></script>
<script src="__STATIC__/js/inventory/allot.js" type="text/javascript"></script>
<!--打印模态框-->
<div class="modal frame-modalfade" id="printModal" tabindex="-1" role="dialog" aria-labelledby="purchaselabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title">打印调拨单</p>
            </div>
            <div class="modal-body" id="printBody">
                <table>
                    <tr>
                        <td>调拨单编号</td>
                        <td colspan="2" id="allot_sn"></td>
                        <td>调拨日期</td>
                        <td colspan="2" id="time"></td>
                    </tr>
                    <tr>
                        <td>调出仓库</td>
                        <td colspan="2" id="from_shop"></td>
                        <td>调入仓库</td>
                        <td colspan="2" id="to_shop"></td>
                    </tr>
                    <tr>
                        <td>序列</td>
                        <td>商品名称</td>
                        <td>条形码</td>
                        <td>商品分类</td>
                        <td>调拨数量</td>
                        <td>备注</td>
                    </tr>
                    <!-- <tr>
                        <td>进价金额</td>
                        <td colspan="2" id="bid_amount"></td>
                        <td>成本金额</td>
                        <td colspan="2" id="cost"></td>
                    </tr> -->
                    <!--<tr>-->
                        <!--<td>出库人员</td>-->
                        <!--<td colspan="2" id="outer"></td>-->
                        <!--<td>入库人员</td>-->
                        <!--<td colspan="2" id="iner"></td>-->
                    <!--</tr>-->
                    <!--<tr>-->
                        <!--<td>备注</td>-->
                        <!--<td colspan="5" id="remark"></td>-->
                    <!--</tr>-->
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" id="print_button" class="btn btn-primary">打印</button>
            </div>
        </div>
    </div>
</div>
<!-- 调拨单列表模版 -->
<script type="text/html" id="allotList">
    <colgroup>
        <col width="160">
        <col width="190">
        <col width="160">
        <col width="160">
        <col width="100">
        <col width="100">
        <col width="160">
        <col width="100">
        <col width="100">
        <col width="190">
    </colgroup>
    <thead>
    <tr>
        <th>调拨单时间</th>
        <th>调拨单号</th>
        <th>调出仓库</th>
        <th>出库人员</th>
        <th>调拨状态</th>
        <th>调入仓库</th>
        <th>入库日期</th>
        <th>入库人员</th>
        {{if show === 1}}
        <th>进价金额</th>
        <th>成本金额</th>
        {{/if}}
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    {{each list v i}}
    <tr data-id="{{v.id}}" data-index="{{i}}" data-can="{{v.can_edit}}" style="background-color: {{v.type === 1 ? '#d4f6ff' : ''}}">
        <td>{{v.time}}</td>
        <td>{{v.sn}}</td>
        <td>{{v.from_shop_name}}</td>
        <td>{{v.outer}}</td>
        <td>{{v.status === 1?'已完成':'调拨中'}}</td>
        <td>{{v.to_shop_name}}</td>
        <td>{{v.in_time || '-'}}</td>
        <td>{{v.iner || '-'}}</td>
        {{if show === 1}}
        <td>{{v.bid_amount}}</td>
        <td>{{v.cost}}</td>
        {{/if}}
        <td>
            <div class="t-calc">
                <span class="print" data-purchase-id="{{v.id}}">打印</span>
                {{if v.status}}
                    <span class="edit" data-toggle="modal" data-target="#editAllot">查看</span>
                {{else}}
                    {{if show === 1}}
                    <span class="edit" data-toggle="modal" data-target="#editAllot">编辑</span>
                    <span class="edit" data-put="1" data-toggle="modal" data-target="#editAllot">入库</span>
                    <span class="remove">删除</span>
                    {{else}}
                        {{if v.can_edit}}
                        <span class="edit" data-toggle="modal" data-target="#editAllot">编辑</span>
                        <span class="remove">删除</span>
                        {{else}}
                        <span class="edit" data-put="1" data-toggle="modal" data-target="#editAllot">入库</span>
                        {{/if}}
                    {{/if}}
                {{/if}}
            </div>
        </td>
    </tr>
    {{/each}}
    </tbody>
</script>
<!-- 调拨单详情 -->
<script type="text/html" id="productList">
    <colgroup>
        <col width="60">
        <col width="240">
        <col width="120">
        <col width="100">
        <col width="100">
        <col width="100">
        {{if show}}
        <col width="100">
        <col width="100">
        {{/if}}
        <col width="100">
        <col width="180">
    </colgroup>
    <thead>
    <tr>
        <th>序号</th>
        <th>
            <div>
                <span>商品名称</span>
                {{if !status}}
                {{if can !== 0 && !put}}
                <button type="button" class="set-scan">扫码枪</button>
                {{/if}}
                {{/if}}
            </div>
        </th>
        <th>条形码</th>
        <th>商品分类</th>
        <th>当前库存</th>
        <th>调拨数量</th>
        {{if show}}
        <th>门店进价</th>
        <th>入库成本</th>
        {{/if}}
        <th>备注</th>
        {{if !status}}
        {{if can !== 0 && !put}}
        <th>操作</th>
        {{/if}}
        {{/if}}
    </tr>
    </thead>
    <tbody>
    {{if list.length === 0}}
    <tr>
        <td>1</td>
        <td>
            <!-- 点击搜索商品 -->
            <div>
                <input type="text" name="" class="layui-input s-search">
            </div>
        </td>
        <td>

        </td>
        <td>

        </td>
        <td>

        </td>
        <td>

        </td>
        {{if show}}
        <td>

        </td>
        <td>

        </td>
        {{/if}}
        <td>

        </td>
        <td>

        </td>
    </tr>
    {{else}}
    {{each list v i}}
    <tr data-id="{{v.id}}" data-index="{{i}}">
        <td>{{i+1}}</td>
        <td>
            <div>
                {{if v.id || v.item_id}}
                <input type="text" name="" readonly class="layui-input s-search" title="{{v.title}}" value="{{v.title}}">
                {{else}}
                <input type="text" name="" class="layui-input s-search">
                {{/if}}
            </div>
        </td>
        <td>
            <div>
                <input type="text" name="" readonly class="layui-input" value="{{v.bar_code}}">
            </div>
        </td>
        <td>
            <div>
                <input type="text" name="" readonly class="layui-input s-type" value="{{v.cname}}">
            </div>
        </td>
        <td>
            <input type="text" name="" readonly class="layui-input s-stock" value="{{v.stock}}">
        </td>
        <td>
            <div>
                {{if !status}}
                {{if can !== 0 && !put}}
                <input type="text" name="" class="layui-input s-num" value="{{v.num}}">
                {{else}}
                <input type="text" name="" readonly class="layui-input s-num" value="{{v.num}}">
                {{/if}}
                {{else}}
                <input type="text" name="" readonly class="layui-input s-num" value="{{v.num}}">
                {{/if}}
            </div>
        </td>
        {{if show}}
        <td>
            {{if v.id || v.item_id}}
            <div>
                <input type="text" name="" readonly class="layui-input md-price" value="{{v.md_price}}">
            </div>
            {{/if}}
        </td>
        <td>
            <div>
                <input type="text" name="" readonly class="layui-input store-cost" value="{{v.store_cost}}">
            </div>
        </td>
        {{/if}}
        <td>
            <div>
                {{if !status}}
                {{if can !== 0 && !put}}
                <input type="text" name="" class="layui-input s-remark" value="{{v.remark}}">
                {{else}}
                <input type="text" name="" readonly class="layui-input s-remark" value="{{v.remark}}">
                {{/if}}
                {{else}}
                <input type="text" name="" readonly class="layui-input s-remark" value="{{v.remark}}">
                {{/if}}
            </div>
        </td>
        {{if !status}}
        {{if can !== 0 && !put}}
        <td>
            {{if v.id || v.item_id}}
            <div class="t-calc">
                <span class="add">增加</span>
                <span class="remove">删除</span>
            </div>
            {{else}}
            {{/if}}
        </td>
        {{/if}}
        {{/if}}
    </tr>
    {{/each}}
    {{/if}}
    </tbody>
</script>
<!-- 商品列表 -->
<script type="text/html" id="productItem">
    <colgroup>
        <col width="70">
        <col width="210">
        <col width="190">
        <col width="140">
        {{if show}}
        <col width="100">
        <col width="100">
        {{/if}}
        <col width="100">
        <col width="180">
    </colgroup>
    <thead>
    <tr>
        <th><input type="checkbox" name="" id="all_choose" lay-skin="primary"
                   lay-filter="allChoose"></th>
        <th>
            商品名称
        </th>
        <th>条形码</th>
        <th>商品分类</th>
        <th>原价</th>
        {{if show}}
        <th>门店进价</th>
        <th>入库成本</th>
        {{/if}}
        <th>库存</th>
        <th>控价</th>
    </tr>
    </thead>
    <tbody>
    {{each list v i}}
    <tr data-id="{{v.id}}" data-index="{{i}}">
        <td><input type="checkbox" lay-filter="owner_one" class="check-box" name="" lay-skin="primary"></td>
        <td title="{{v.title}}">{{v.title}}</td>
        <td>{{v.bar_code}}</td>
        <td>{{v.cname}}</td>
        <td>{{v.price}}</td>
        {{if show}}
        <td>{{v.md_price}}</td>
        <td>{{v.store_cost}}</td>
        {{/if}}
        <td>{{v.stock}}</td>
        <td>{{v.is_price_control == 1?'是':'否'}}</td>
    </tr>
    {{/each}}
    </tbody>
</script>
<!-- 调拨单入库商品列表 -->
<script type="text/html" id="putProductList">
    {{each list v i}}
    <tr data-id="{{v.item_id || v.id}}" data-index="{{i}}">
        <td>{{i}}</td>
        <td title="{{v.title}}">{{v.title}}</td>
        <td>{{v.bar_code}}</td>
        <td>{{v.cname}}</td>
        <td>{{v.num}}</td>
        <td>
            <div>
                <input type="text" name="" class="layui-input curr-num">
            </div>
        </td>
        <td>
            {{v.remark}}
        </td>
    </tr>
    {{/each}}
</script>
<!--调拨单打印-->
<script type="text/javascript">
    $('body').on('click','.print',function(){
        $('#printBody tbody tr.new_tr').empty('')
        var tr='';
        $.post("<?php echo url('Allot/allot_print'); ?>",{
            'allot_id':$(this).attr('data-purchase-id'),
        },function(res){
            var data = res.data
            var allot_info = res.data.allot_info
            $('#from_shop').text(data.from_shop_name)
            $('#to_shop').text(data.to_shop_name)
            $('#allot_sn').text(allot_info.sn)
            $('#time').text(data.time)
            // $('#bid_amount').text(allot_info['bid_amount'])
            // $('#cost').text(allot_info['cost'])
            // $('#remark').text(allot_info['remark'])
            // $('#outer').text(data.outer)
            // $('#iner').text(data.iner)
            $(data.allot_item_info).each(function(k,v){
                tr+="<tr class='new_tr'><td>"+(k+1)+"</td><td>"+v.title+"</td><td>"+v.bar_code+"</td><td>"+v.cname+"</td><td>"+v.num+"</td><td>"+v.remark+"</td></tr>";
            })
            tr+="<tr class='new_tr'><td>出库人员</td><td colspan='2'>"+data.outer+"</td><td>入库人员</td><td colspan='2'>"+data.iner+"</td></tr>"
            tr+="<tr class='new_tr'><td>备注</td><td colspan='5'>"+allot_info['remark']+"</td></tr>"
            $('#printBody table').append(tr)
            // $('#printBody table').addClass('table table-bordered')
            $('#printModal').modal('show')
        },'json')
    })
    $('#print_button').click(function(){
        document.body.innerHTML = document.getElementById('printBody').innerHTML
        window.print()
        window.location.reload()
    })
</script>

</body>
</html>

