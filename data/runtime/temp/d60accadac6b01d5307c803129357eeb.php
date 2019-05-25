<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:53:"themes/admin_simpleboot3/admin\stock_check\index.html";i:1557217689;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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
				    <input type="text" class="layui-input border-ff choose-itemname" placeholder="商品名称">
				    <input type="text" class="layui-input border-ff choose-code" placeholder="条形码">
				    <div class="layui-input-inline">
					    <select name="modules" class="choose-shop" lay-verify="required" lay-search="">
		       			</select>
		       		</div>
		       		<div class="layui-input-inline">
					    <select name="modules" class="search-type1" lay-verify="required" lay-search="" lay-filter="search_type">
		       			</select>
		       		</div>
		       		<div class="layui-input-inline">
					    <select name="modules" class="search-type2" data-head="1" lay-verify="required" lay-search="">
					    	<option value="">请先选择一级分类</option>
		       			</select>
		       		</div>
		       		<div class="layui-input-inline">
					    <select name="modules" class="choose-status" lay-verify="required" lay-search="">
				          <!-- <option value="">显示库存</option> -->
				          <option value="1" selected>显示零库存</option>
				          <option value="0">隐藏零库存</option>
		       			</select>
		       		</div>
					<button type="button" class="layui-btn layui-btn-primary search-stock">查询</button>
		       	</div>
		       </form>
			</div>
			<!--  -->
			<div class="i-title fj-row">
				<div class="summary border-ff">

				</div>
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
	<!-- 出入库信息弹出框（模态框） -->
	<div class="modal frame-modal get-stock fade" id="getStock" tabindex="-1" role="dialog" aria-labelledby="stocklabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	      	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <p class="modal-title" id="stocklabel">商品出入库明细</p>
	      </div>
	      <div class="modal-body">
	        <div class="top">
	        	<form class="layui-form layui-form-pane" action="" lay-filter="editDefaultValue">
				  <div class="layui-form-item fj-row">
				    <div class="layui-inline">
				       <label class="layui-form-label">查询时间</label>
				       <div class="layui-input-inline">
				       	   	<input type="text" class="layui-input choose-time" placeholder="">
				       </div>
				    </div>
				    <div class="layui-inline">
				       <label class="layui-form-label">单据明细</label>
				       <div class="layui-input-inline">
			       			<select name="modules" class="choose-type" lay-verify="required" lay-search="">
					          <option value="">全部类型</option>
					          <option value="1">采购单</option>
					          <option value="2">调拨单</option>
					          <option value="3">盘盈单</option>
					          <option value="4">盘亏单</option>
					          <option value="5">零售退货单</option>
					          <option value="6">零售出库单</option>
					          <option value="7">供应商退货单</option>
			       			</select>
				       </div>
				    </div>
				    <div class="layui-inline">
				    	<button type="button" class="layui-btn layui-btn-primary stock">查询</button>
				    </div>
				  </div>
				</form>
	        </div>
	        <div class="list">
	        	<div class="list-table">
	        		<table class="layui-table" id="stock_list">

					</table>
	        	</div>
	        	<!-- 分页 -->
	        	<div id="stockPage">

	        	</div>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>
	<!-- 金额变动弹出框 -->
	<div class="modal frame-modal get-cost fade" id="getCost" tabindex="-1" role="dialog" aria-labelledby="stocklabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	      	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <p class="modal-title" id="stocklabel">金额变动</p>
	      </div>
	      <div class="modal-body">
	        <div class="top">
	        	<form class="layui-form layui-form-pane" action="" lay-filter="editDefaultValue">
				  <div class="layui-form-item fj-row">
				    <div class="layui-inline">
				       <label class="layui-form-label">查询时间</label>
				       <div class="layui-input-inline">
				       	   	<input type="text" class="layui-input choose-time" placeholder="">
				       </div>
				    </div>
				    <div class="layui-inline">
				       <label class="layui-form-label">单据明细</label>
				       <div class="layui-input-inline">
			       			<select name="modules" class="choose-type" lay-verify="required" lay-search="">
					          <option value="">全部类型</option>
					          <option value="1">采购单</option>
					          <option value="2">调拨单</option>
					          <option value="5">零售退货单</option>
					          <option value="7">供应商退货单</option>
			       			</select>
				       </div>
				    </div>
				    <button type="button" class="layui-btn layui-btn-primary cost">查询</button>
				  </div>
				</form>
	        </div>
	        <div class="list">
	        	<div class="list-table">
	        		<table class="layui-table" id="cost_list">

					</table>
	        	</div>
	        	<!-- 分页 -->
	        	<div id="costPage">

	        	</div>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>
<script src="/plugins/layer/2.4/layui.js"></script>
<script src="__STATIC__/js/inventory/public.js" type="text/javascript"></script>
<script src="__STATIC__/js/inventory/stock_check.js" type="text/javascript"></script>
<!--库存列表模版 -->
<script type="text/html" id="stockList">
 <colgroup>
    <col width="160">
    <col width="100">
    <col width="100">
    <col width="100">
    {{if show}}
    <col width="120">
    <col width="100">
    {{/if}}
    <col width="100">
    <col width="100">
    <col width="160">
    <col width="190">
  </colgroup>
  <thead>
    <tr>
      <th>商品名称</th>
      <th>所属仓库</th>
      <th>库存数量</th>
      <th>其中在途</th>
      {{if show}}
      <th>库存单位成本</th>
      <th>门店单位进价</th>
      {{/if}}
      <th>售价</th>
      <th>商品分类</th>
      <th>条形码</th>
      <th>操作</th>
    </tr>
  </thead>
  <tbody>
  {{each list v i}}
    <tr data-id="{{v.item_id}}" data-shop="{{v.shop_id}}" data-index="{{i}}">
      <td title="{{v.title}}">{{v.title}}</td>
      <td>{{v.shop_name}}</td>
      <td>{{v.stock}}</td>
      <td>{{v.num}}</td>
      {{if show}}
      <td>{{v.store_cost}}</td>
      <td>{{v.md_price}}</td>
      {{/if}}
      <td>{{v.price}}</td>
      <td>{{v.cname}}</td>
      <td>{{v.bar_code}}</td>
      <td>
      	<div class="t-calc">
      		{{if show}}
      		<span class="stock" data-toggle="modal" data-target="#getStock">出入库明细</span>
      		<span class="cost" data-toggle="modal" data-target="#getCost">金额变动</span>
      		{{else}}
      		<span class="stock" data-toggle="modal" data-target="#getStock">出入库明细</span>
      		{{/if}}
      	</div>
      </td>
    </tr>
  {{/each}}
  </tbody>
</script>
<!-- 出入库明细 -->
<script type="text/html" id="productList">
  <colgroup>
    <col width="120">
    <col width="120">
    <col width="210">
    <col width="100">
  </colgroup>
  <thead>
    <tr>
      <th>操作时间</th>
      <th>
      	单据类型
      </th>
      <th>单据编号</th>
      <th>商品数量</th>
    </tr>
  </thead>
  <tbody>
  	{{each list v i}}
	<tr>
      <td>{{v.time}}</td>
      <td>
      	{{v.type_name}}
      </td>
       <td>
      	{{v.sn}}
      </td>
       <td>
      	{{v.change_stock}}
      </td>
    </tr>
	{{/each}}
</tbody>
</script>
<!-- 金额变动 -->
<script type="text/html" id="costList">
	<colgroup>
    <col width="120">
    <col width="120">
    <col width="210">
    <col width="100">
    <col width="100">
  </colgroup>
  <thead>
    <tr>
      <th>操作时间</th>
      <th>
      	单据类型
      </th>
      <th>单据编号</th>
      <th>库存单位成本</th>
      <th>门店单位进价</th>
    </tr>
  </thead>
  <tbody>
  	{{each list v i}}
	<tr>
      <td>{{v.time}}</td>
      <td>
      	{{v.type_name}}
      </td>
       <td>
      	{{v.sn}}
      </td>
       <td>
      	{{v.store_cost_after}}
      </td>
      <td>
      	{{v.md_price_after}}
      </td>
    </tr>
	{{/each}}
</script>
</body>
</html>
