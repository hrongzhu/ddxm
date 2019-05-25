<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:54:"themes/admin_simpleboot3/admin\order\show_expense.html";i:1554867187;s:45:"themes/admin_simpleboot3/public\ptheader.html";i:1554867210;s:45:"themes/admin_simpleboot3/public\ptfooter.html";i:1554867211;}*/ ?>
<!-- 支出 -->
<!DOCTYPE html>
<html lang="zh-cn">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="renderer" content="webkit">
		<title>订单管理 - 订单详情</title>
        <link rel="stylesheet" href="__STATIC__/pintuer/pintuer.css">
        <link href="/plugins/layer/2.4/skin/layer.css" rel="stylesheet">
		<script src="__TMPL__/public/assets/js/jquery-1.10.2.min.js"></script>
	</head>
	<body>
		<div class="layout" style="min-height: 980px;">
			<div class="container">

	<div class="panel margin-top">
		<div class="panel-head bg-main">
			<span>订单成本</span>
		</div>
		<div class="panel-body">
			<table class="table table-responsive-y table-hover table-bordered">
				<thead>
					<tr>
						<th>商品总售价</th>
						<th>成本总价</th>
					</tr>
				</thead>
				<tbody id="datas">
					<?php if(!empty($info)): ?>
						<tr>
							<td><?php echo $info['amount']; ?>元</td>
							<td><?php echo $info['oprice']; ?> 元</td>
						</tr>
					<?php else: ?>
						<tr>
							<td class="detail-head" colspan="2" style="text-align:center;">没有商品成本信息</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
        </div>
		<input type="hidden" id="order-id" value="<?php echo $order_id; ?>">
		<div class="foot">
			<!-- <button type="button" id="close_btn" class="btn btn-default" style="float: right;">关闭</button> -->
		</div>
	</div>
<script src="__STATIC__/js/admin.js"></script>
<script type="text/javascript">

	$(function(){
		$('#close_btn').click(function(){
			var index = parent.layer.getFrameIndex(window.name);
	        parent.layer.close(index);
		})
	})
</script>
		</div>
	</div>
</body>
<script src="/plugins/layer/2.4/layer.js"></script>
<script src="__STATIC__/pintuer/pintuer.js"></script>
<script src="__STATIC__/pintuer/respond.js"></script>
<script src="/plugins/h-ui.admin/js/H-ui.admin.page.js"></script>
</html>

