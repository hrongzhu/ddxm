<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:48:"themes/admin_simpleboot3/admin\reject\index.html";i:1554867193;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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
				    <input type="text" class="layui-input border-ff choose-time"placeholder="退货单时间选择">
				    <input type="text" class="layui-input border-ff choose-itemname" placeholder="商品名称">
				    <input type="text" class="layui-input border-ff choose-sn" placeholder="退货单号">
				    <div class="layui-input-inline">
					    <select name="modules" class="choose-shop" lay-verify="required" lay-search="">
		       			</select>
		       		</div>
		       		<div class="layui-input-inline">
					    <select name="modules" class="choose-supplier" lay-verify="required" lay-search="">
		       			</select>
		       		</div>
		       		<div class="layui-input-inline">
					    <select name="modules" class="choose-status" lay-verify="required" lay-search="">
				          <option value="">出库状态</option>
				          <option value="1">已入库</option>
				          <option value="0">未入库</option>
		       			</select>
		       		</div>
		       		<button type="button" class="layui-btn layui-btn-primary search-reject">查询</button>
		       	</div>
		       </form>
			</div>
			<!--  -->
			<div class="i-title fj-row">
				<div class="summary border-ff">
					
				</div>
				<button type="button" class="btn-success-reset add-reject" data-toggle="modal" data-target="#editReject">新增退货单</button>
			</div>
			<!-- 列表 -->
			<div class="i-content">
				<table lay-even class="layui-table" id="table_list">
				  
				</table>
				<!-- 分页 -->
				<div id="pagelayer" class="hidden">
					
				</div>
			</div>
		</div>
	</div>
	<!-- 退货单弹出框（模态框） -->
	<div class="modal frame-modal edit-reject fade" data-backdrop="static" id="editReject" tabindex="-1" role="dialog" aria-labelledby="rejectlabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	      	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <p class="modal-title" id="rejectlabel">退货单详情</p>
	      </div>
	      <div class="modal-body">
	        <div class="top">
	        	<form class="layui-form layui-form-pane" action="" lay-filter="editDefaultValue">
				  <div class="layui-form-item">
				    <div class="layui-inline">
				       <label class="layui-form-label">供应商</label>
				       <div class="layui-input-inline">
				       		<select name="modules" class="edit-supplier" lay-verify="required" lay-search="">
					          
			       			</select>
				       </div>
				    </div>
				    <div class="layui-inline">
				       <label class="layui-form-label">退货仓库</label>
				       <div class="layui-input-inline">
				       		<select name="modules" class="edit-shop choosef-shop" lay-verify="required" lay-search="" lay-filter="choose_reject">
					          
			       			</select>
				       </div>
				    </div>
				    <div class="layui-inline puttime">
				       <label class="layui-form-label">退货单日期</label>
				       <div class="layui-input-inline">
				       	   	<input type="text" readonly class="layui-input edit-time" placeholder="">
				       </div>
				    </div>
				    <div class="layui-inline putsn">
				       <label class="layui-form-label">退货单号</label>
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
	        	<br>
	        	<br>
	        	<br>
				<form class="layui-form layui-form-pane detail" action="">
				  <div class="layui-form-item">
				    <div class="layui-inline showsum">
				       <label class="layui-form-label">退货总额</label>
				       <div class="layui-input-inline">
				       		<input type="text" readonly class="layui-input r-sum" placeholder="">
				       </div>
				    </div>
				    <div class="layui-inline creator">
				       <label class="layui-form-label">退货制单</label>
				       <div class="layui-input-inline">
				       		<input type="text" readonly class="layui-input r-creator" placeholder="">
				       </div>
				    </div>
				    <div class="layui-inline calcpeople">
				       <label class="layui-form-label">出库人员</label>
				       <div class="layui-input-inline">
				       	   	<input type="text" readonly class="layui-input s-putpeople" placeholder="">
				       </div>
				    </div>
				    <div class="layui-inline">
				       <label class="layui-form-label">备注信息</label>
				       <div class="layui-input-inline">
				       		<input type="text" class="layui-input r-remark" placeholder="">
				       </div>
				    </div>
				  </div>
				</form>	
	        </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn-default-border" data-dismiss="modal">取消</button>
	        <button type="button" class="btn-blue-default" id="add_reject">确定</button>
	      </div>
	    </div>
	  </div>
	</div>
	<!-- 商品信息弹出框 -->
	<div class="modal frame-modal put-product fade" id="editProduct" tabindex="-1" role="dialog" aria-labelledby="purchaselabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	      	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
						    <select name="modules" class="search-type1" lay-verify="required" lay-search="" lay-filter="search_type">
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
	        		<table class="layui-table">
					  	<colgroup>
					    <col width="70">
					    <col width="240">
					    <col width="190">
					    <col width="140">
					    <col width="120">
					    <col width="120">
					    <col width="120">
					    <col width="120">
					  </colgroup>
					  <thead>
					    <tr>
					      <th><input type="checkbox" name="" id="all_choose" lay-skin="primary" lay-filter="allChoose"></th>
					      <th>
					      	商品名称
					      </th>
					      <th>条形码</th>
					      <th>商品分类</th>
					      <th>采购原价</th> 
					      <th>门店进价</th>
					      <th>入库成本</th>
					      <th>库存</th>
					      <th>控价</th>
					    </tr> 
					  </thead>
					  <tbody id="productItems">
					  	
					  </tbody>
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
	<!-- 退货单出库弹出框（模态框） -->
	<div class="modal frame-modal put-reject fade" id="putReject" tabindex="-1" role="dialog" aria-labelledby="purchaselabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	      	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <p class="modal-title" id="purchaselabel">退货单出库</p>
	      </div>
	      <div class="modal-body">
	        <div class="top">
	        	<form class="layui-form layui-form-pane" action="" lay-filter="editDefaultValue">
				  <div class="layui-form-item fj-row">
				    <div class="layui-inline">
				       <label class="layui-form-label">退货仓库</label>
				       <div class="layui-input-inline">
				       		<input type="text" readonly class="layui-input put-shop" placeholder="">
				       </div>
				    </div>
				    <!-- <div class="layui-inline">
				       <label class="layui-form-label">出库日期</label>
				       <div class="layui-input-inline">
				       		<input type="text" class="layui-input put-time" placeholder="">
				       </div>
				    </div> -->
				  </div>
				</form>	
	        </div>
	        <div class="list">
	        	<div class="list-table">
	        		<table class="layui-table">
						<colgroup>
					    <col width="70">
					    <col width="210">
					    <col width="160">
					    <col width="100">
					    <col width="100">
					    <col width="110">
					  </colgroup>
					  <thead>
					    <tr>
					      <th>序列</th>
					      <th>
					      	商品名称
					      </th>
					      <th>条形码</th>
					      <th>商品分类</th>
					      <th>退货数量</th> 
					      <th>备注</th>
					    </tr> 
					  </thead>
					  <tbody id="putProductItems">
					  	
					  </tbody>
					</table>
	        	</div>
				<form class="layui-form layui-form-pane detail" action="">
				  <div class="layui-form-item fj-row">
				    <div class="layui-inline">
				       <label class="layui-form-label">退货制单</label>
				       <div class="layui-input-inline">
				       		<input type="text" readonly class="layui-input put-admin" placeholder="">
				       </div>
				    </div>
				    <!-- <div class="layui-inline">
				       <label class="layui-form-label">出库人员</label>
				       <div class="layui-input-inline">
				       		<input type="text" readonly class="layui-input put-shopcalc" placeholder="">
				       </div>
				    </div> -->
				    <div class="layui-inline">
				       <label class="layui-form-label">备注信息</label>
				       <div class="layui-input-inline">
				       	   	<input type="text" readonly class="layui-input put-remark" placeholder="">
				       </div>
				    </div>
				  </div>
				</form>	
	        </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn-default-border" data-dismiss="modal">取消</button>
	        <button type="button" class="btn-blue-default" id="put_reject">确认出库</button>
	      </div>
	    </div>
	  </div>
	</div>
<script src="/plugins/layer/2.4/layui.js"></script>
<script src="__STATIC__/js/inventory/public.js" type="text/javascript"></script>
<script src="__STATIC__/js/inventory/reject.js" type="text/javascript"></script>
<!-- 退货单列表模版 -->
<script type="text/html" id="rejectList">
 <colgroup>
    <col width="160">
    <col width="190">
    <col width="160">
    <col width="160">
    <col width="100">
    <col width="100">
    <col width="160">
    <col width="100">
    <col width="190">
  </colgroup>
  <thead>
    <tr>
      <th>退货单时间</th>
      <th>退货单号</th>
      <th>退货仓库</th>
      <th>供应商</th>
      <th>退货制单</th>
      <th>出库状态</th>
      <th>出库人员</th>
      {{if show === 1}}
      <th>退货总额</th>
      {{/if}}
      <th>操作</th>
    </tr> 
  </thead>
  <tbody>
  {{each list v i}}
    <tr data-id="{{v.id}}" data-index="i">
      <td>{{v.addtime}}</td>
      <td>{{v.sn}}</td>
      <td>{{v.refund_shop_name}}</td>
      <td>{{v.supplier}}</td>
      <td>{{v.refund_bill_user}}</td>
      <td>{{v.status == 0?'出库中':'已出库'}}</td>
      <td>{{v.out_stock_user}}</td>
      {{if show === 1}}
      <td>{{v.amount}}</td>
      {{/if}}
      <td>
      	<div class="t-calc">
			{{if show === 1}}
			{{if v.status === 1}}
      		<span class="edit" data-toggle="modal" data-target="#editReject">查看</span>
      		{{else}}
      		<span class="put" data-toggle="modal" data-target="#putReject">出库</span>
			<span class="edit" data-toggle="modal" data-target="#editReject">编辑</span>
      		<span class="remove">删除</span>
      		{{/if}}
			{{else}}
			{{if v.status === 0}}
			<span class="put" data-toggle="modal" data-target="#putReject">出库</span>
			{{else}}
			<span class="edit" data-toggle="modal" data-target="#editReject">查看</span>
			{{/if}}
      		{{/if}}
      	</div>
      </td>
    </tr>
  {{/each}}
  </tbody>
</script>
<!-- 退货单单详情 -->
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
    <col width="100">
    {{/if}}
    <col width="130">
    <col width="180">
  </colgroup>
  <thead>
    <tr>
      <th>序号</th>
      <th>
      	<div>
      		<span>商品名称</span>
      		{{if !status}}
      		<button type="button" class="set-scan">扫码枪</button>
      		{{/if}}
      	</div>
      </th>
      <th>条形码</th>
      <th>商品分类</th>
      {{if !status}}
      <th>库存</th>
      {{/if}}
      {{if show}}
      <th>库存单位成本</th>
      {{/if}}
      <th>数量</th>
      {{if show}}
      <th>退货单价</th>
      <th>退货金额</th>
      {{/if}}
      <th>备注</th>
      {{if !status}}
      <th>操作</th>
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
      <td>
      	
      </td>
      <td>
      	
      </td>
       <td>
      	
      </td>
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
      		{{if v.item_id}}
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
       {{if !status}}
      <td>
      	<input type="text" name="" readonly class="layui-input s-stock" value="{{v.stock}}">
      </td>
      {{/if}}
      {{if show}}
      <td>
      	<input type="text" name="" readonly class="layui-input s-cost" value="{{v.cost_price}}">
      </td>
      {{/if}}
      <td>
      	{{if !status}}
      	<input type="text" name="" class="layui-input s-num" value="{{v.num}}">
      	{{else}}
      	<input type="text" name="" readonly class="layui-input s-num" value="{{v.num}}">
      	{{/if}}
      </td>
      {{if show}}
       <td>
      	<div>
      		{{if !status}}
      		<input type="text" name="" class="layui-input s-single" value="{{v.cost_price}}">
      		{{else}}
      		<input type="text" name="" readonly class="layui-input s-single" value="{{v.cost_price}}">
      		{{/if}}
      	</div>
      </td>
       <td>
       	{{if v.num}}
      	<div>
      		<input type="text" name="" readonly class="layui-input s-amount" value="{{v.num * v.cost_price}}">
      	</div>
      	{{/if}}
      </td>
      {{/if}}
      <td>
      	<div>
      		{{if !status}}
      		<input type="text" name="" class="layui-input s-remark" value="{{v.remark}}">
      		{{else}}
      		<input type="text" readonly name="" class="layui-input s-remark" value="{{v.remark}}">
      		{{/if}}
      	</div>
      </td>
      {{if !status}}
      <td>
      	{{if v.item_id}}
      	<div class="t-calc">
      		<span class="add">增加</span>
      		<span class="remove">删除</span>
      	</div>
      	{{else}}
      	{{/if}}
      </td>
      {{/if}}
    </tr> 
	{{/each}}
	{{/if}}
</tbody>
</script>
<!-- 商品列表 -->
<script type="text/html" id="productItem">
	{{each list v i}}
		<tr data-id="{{v.id}}" data-index="{{i}}">
			<td><input type="checkbox" lay-filter="owner_one" class="check-box" name="" lay-skin="primary"></td>
			<td title="{{v.title}}">{{v.title}}</td>
			<td>{{v.bar_code}}</td>
			<td>{{v.cname}}</td>
			<td>{{v.price}}</td>
			<td>{{v.md_price}}</td>
			<td>{{v.cost_price}}</td>
			<td>{{v.stock}}</td>
			<td>{{v.is_price_control == 1?'是':'否'}}</td>
		</tr>
	{{/each}}
</script>
<!-- 退货单出库商品列表 -->
<script type="text/html" id="putProductList">
	{{each list v i}}
	<tr data-id="{{v.item_id || v.id}}" data-index="{{i}}">
		<td>{{i}}</td>
		<td title="{{v.title}}">{{v.title}}</td>
		<td>{{v.bar_code}}</td>
		<td>{{v.cname}}</td>
		<td>{{v.num}}</td>
		<td>
			{{v.remark}}
		</td>
	</tr>
	{{/each}}
</script>
</body>
</html>

