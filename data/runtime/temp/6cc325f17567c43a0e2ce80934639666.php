<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:56:"themes/admin_simpleboot3/admin\inventory_loss\index.html";i:1554867202;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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
		<div class="invertory profit">
			<!-- 搜索头部 -->
			<div class="i-header">
				<form class="layui-form" action="" lay-filter="searchHeader">
  				<div class="layui-form-item">
            <input type="text" class="layui-input border-ff choose-time" placeholder="时间段选择">
            <div class="layui-input-inline">
                <select name="modules" class="choose-shop" lay-verify="required" lay-search="">
                </select>
            </div>
				    <input type="text" class="layui-input border-ff choose-itemname" placeholder="商品名称">
		     	</div>
          <div class="layui-form-item">
            <input type="text" class="layui-input border-ff choose-code" placeholder="出库单号">
            <input type="text" class="layui-input border-ff choose-create" placeholder="出库制单">
            <input type="text" class="layui-input border-ff choose-status" placeholder="出库状态">
            <div class="layui-input-inline">
              <select name="modules" class="choose-status" lay-verify="required" lay-search="">
                <option value="">全部状态</option>
                <option value="1">已出库</option>
                <option value="1">未出库</option>
                </select>
            </div>
            <button type="button" class="layui-btn layui-btn-primary search-items">查询</button>
          </div>
		    </form>
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
	<!-- 盘盈单详情（模态框） -->
	<div class="modal frame-modal profit-loss fade" data-backdrop="static" id="editInventory" tabindex="-1" role="dialog" aria-labelledby="stocklabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	      	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <p class="modal-title" id="stocklabel">盘亏单详情</p>
	      </div>
	      <div class="modal-body">
	        <div class="top">
	        	<form class="layui-form layui-form-pane" action="" lay-filter="editDefaultValue">
    				  <div class="layui-form-item fj-row">
    				    <div class="layui-inline">
    				       <label class="layui-form-label">所入仓库</label>
    				       <div class="layui-input-inline">
    				       	   	<input type="text" readonly class="layui-input shop" placeholder="">
    				       </div>
    				    </div>
                <div class="layui-inline">
                   <label class="layui-form-label">盘亏单日期</label>
                   <div class="layui-input-inline">
                        <input type="text" readonly class="layui-input time" placeholder="">
                   </div>
                </div>
    				    <div class="layui-inline">
    				       <label class="layui-form-label">盘亏单号</label>
    				       <div class="layui-input-inline">
    				       	   	<input type="text" readonly class="layui-input sn" placeholder="">
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
                        <label class="layui-form-label">盘点制单</label>
                        <div class="layui-input-inline">
                            <input type="text" readonly class="layui-input create" placeholder="">
                        </div>
                    </div>
                    <div class="layui-inline bidamount">
                        <label class="layui-form-label">出库人员</label>
                        <div class="layui-input-inline">
                            <input type="text" readonly class="layui-input putrule" placeholder="">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">备注信息</label>
                        <div class="layui-input-inline">
                            <input type="text" readonly class="layui-input remark" placeholder="">
                        </div>
                    </div>
                </div>
            </form>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-blue-default" id="put_storage">确认出库</button>
          <button type="button" class="btn-blue-default btn-close hidden" data-dismiss="modal">确认</button>
        </div>
      </div>
	  </div>
	</div>
<script src="/plugins/layer/2.4/layui.js"></script>
<script src="__STATIC__/js/inventory/public.js" type="text/javascript"></script>
<script src="__STATIC__/js/inventory/inventory_loss.js" type="text/javascript"></script>
<!--库存列表模版 -->
<script type="text/html" id="itemList">
  <colgroup>
    <col width="300">
    <col width="260">
    <col width="180">
    <col width="180">
    <col width="180">
  </colgroup>
  <thead>
    <tr>
      <th>盘亏时间</th>
      <th>盘亏单编号</th>
      <th>盘点制单</th>
      <th>出库状态</th>
      <th>操作</th>
    </tr> 
  </thead>
  <tbody>
  {{each list v i}}
    <tr data-id="{{v.id}}" data-index="{{i}}">
      <td>{{v.time}}</td>
      <td>{{v.sn}}</td>
      <td>{{v.creator_name}}</td>
      <td>{{v.status === 0?'未出库':'已出库'}}</td>
      <td>
      	<div class="t-calc">
            {{if v.status}}
            <span class="view">查看</span>
            {{else}}
            <span class="remove">删除</span>
            <span class="put">出库</span>
            {{/if}}
        </div>
      </td>
    </tr>
  {{/each}}
  </tbody>
</script>
<!-- 盘盈盘亏单 -->
<script type="text/html" id="productList">
  <colgroup>
    <col width="60">
    <col width="260">
    <col width="300">
    <col width="300">
    <col width="180">
    <col width="180">
  </colgroup>
  <thead>
    <tr>
      <th>序号</th>
      <th>商品名称</th>
      <th>商品分类</th>
      <th>条形码</th>
	  <th>数量</th>
      <th>备注</th>
    </tr> 
  </thead>
  <tbody>
  	{{each list v i}}
    <tr data-index="{{i}}" data-itemid="{{v.item_id}}">
  	  <td>{{i - 0 + 1}}</td>
        <td title="{{v.title}}">{{v.title}}</td>
        <td>{{v.cname}}</td>
        <td>{{v.bar_code}}</td>
        <td class="num">{{v.num}}</td>
        <td>
      	<div>
      		<input type="text" name="" readonly class="layui-input s-remark" title="{{v.remark}}" value="{{v.remark}}">
      	</div>
      </td>
    </tr>
	   {{/each}}
</tbody>
</script>
</body>
</html>

