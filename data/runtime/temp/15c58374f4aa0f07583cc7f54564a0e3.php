<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:48:"themes/admin_simpleboot3/admin\order\detail.html";i:1554867187;s:45:"themes/admin_simpleboot3/public\ptheader.html";i:1554867210;s:45:"themes/admin_simpleboot3/public\ptfooter.html";i:1554867211;}*/ ?>
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
			<span>订单进度</span>
		</div>
		<div class="panel-body">
			<div class="step">
				<div class="step-bar bg-green" style="width: 25%;">
					<span class="step-point bg-green icon-check"></span>
					<span class="button radius-none bg-green step-text">提交订单 [<?php echo date('Y-m-d H:i',$detailInfo['addtime']); ?>]</span>
				</div>
				<div class="step-bar bg-sub" style="width: 25%;">
					<?php $ok1 = $detailInfo['pay_status'] == 1?'icon-check':''; ?>
					<span class="step-point bg-sub <?php echo $ok1; ?>"><?php echo !empty($ok1)?'':2; ?></span>
					<?php $paytime = date('Y-m-d H:i',$detailInfo['paytime']); ?>
					<span class="button radius-none bg-sub step-text">支付完成 [<?php echo !empty($detailInfo['paytime'])?$paytime:'未支付'; ?>]</span>
				</div>
				<div class="step-bar bg-green"  style="width: 25%;">
					<?php $ok2 = $detailInfo['sendtime'] != "未发货"?'icon-check':''; ?>
					<span class="step-point bg-green <?php echo $ok2; ?>"><?php echo !empty($ok2)?'':3; ?></span>
					<span class="button radius-none bg-green step-text">平台发货 [<?php echo $detailInfo['sendtime']=="未发货"?'未发货':$detailInfo['sendtime']; ?>]</span>
				</div>
				<div class="step-bar bg-sub" style="width: 25%;">
					<?php $ok3 = $detailInfo['order_status']['status'] == 2 || $detailInfo['overtime'] != '暂无'?'icon-check':''; ?>
					<span class="step-point bg-sub <?php echo $ok3; ?>"><?php echo !empty($ok3)?'':4; ?></span>
					<span class="button radius-none bg-sub step-text">确认收货 [<?php echo $detailInfo['overtime']=='暂无'?'待收货':$detailInfo['overtime']; ?>]</span>
				</div>
			</div>
			<br>
		</div>
	</div>
	<!-- 订单基础信息 -->
	<div class="panel margin-top">
		<div class="panel-head bg-main">
			<span>订单概况</span>
		</div>
		<div class="panel-body">
			<div class="alert border border-sub">
				<div class="alert border border-mix">
					<span>
						<span class="text-blue text-big">订单编号：</span><span class="button radius-none bg-blue button-little"><?php echo $detailInfo['sn']; ?></span>
					</span>
					<br>
					<span>
						<span class="text-blue text-big">下单用户：</span><span class="button radius-none bg-blue button-little"><?php echo $detailInfo['realname']!=''?$detailInfo['realname']:$detailInfo['nickname']; ?></span>
					</span>
					<br>
					<span>
						<span class="text-blue text-big">订单状态：</span><span class="text-red text-big"><?php echo $detailInfo['pay_status']==0?'待支付':$detailInfo['order_status']['text']; ?></span>
						<span>
							<?php if($detailInfo['pay_status'] == 1):  $show1 = in_array($detailInfo['order_status']['status'],[2,-2,-1,-7]); if($show1 == false): ?>
									<span>
										<a class="button bg-blue button-little" href="javascript:parent.openIframeLayer('<?php echo url('Order/expense', ['order_id'=>$detailInfo['id']]); ?>','编辑订单成本',{});">修改订单成本</a>
									</span>
									<span >
										<button class="button bg-green button-little" onclick="complete_order('<?php echo url('Order/completeOrder', ['order_id'=>$detailInfo['id']]); ?>')">完成订单</button>
									</span>
								<?php endif; endif; if($detailInfo['pay_status'] == 1 && $detailInfo['order_status']['status'] == 2): ?>
								<span><a class="button bg-green button-little" href="javascript:parent.openIframeLayer('<?php echo url('Order/showExpense', ['order_id'=>$detailInfo['id']]); ?>','订单成本',{});">查看订单成本</a></span>
							<?php endif; if($detailInfo['pay_status'] == 0 && $detailInfo['order_status']['status'] != 2): ?>
								<span><a href="javascript:parent.openIframeLayer('<?php echo url('Order/mdyPrice', ['id'=>$detailInfo['id']]); ?>','修改价格',{});" class="button bg-blue button-little">修改价格</a></span>
							<?php endif; ?>
						</span>
					</span>
					<br>
					<span>
						<span class="text-blue text-big">应付金额：</span>￥<span class="text-red"><?php echo $detailInfo['amount']; ?></span>【邮费 ￥<?php echo $detailInfo['postage']; ?>】
					</span><br>
					<span><span class="text-blue text-big">支付方式：</span><span class="button radius-none bg-blue button-little"><?php if($detailInfo['pay_status'] == 0): ?>未支付<?php endif; if($detailInfo['pay_status'] == 1): ?><?php echo $detailInfo['pay_way']; endif; ?></span></span><br>
					<span><span class="text-blue text-big">配送方式：</span><span class="button radius-none bg-blue button-little"><?php echo $detailInfo['send_way']; ?></span></span><br>
					<span>
						<span class="text-blue text-big">物流单号：</span><span class="button radius-none bg-blue button-little">
							<?php if(empty($detailInfo['more_order_express']) || (($detailInfo['more_order_express'] instanceof \think\Collection || $detailInfo['more_order_express'] instanceof \think\Paginator ) && $detailInfo['more_order_express']->isEmpty())): ?>
								暂无
							<?php else: ?>
								<?php echo $detailInfo['more_order_express']['express_sn']; endif; ?>
						</span>
					</span>
					<br>
					<span>
						<span class="text-blue text-big">收货信息：</span><span class="border border-sub border-small border-dashed"> <?php echo $detailInfo['declare_info']['name']!=''?$detailInfo['declare_info']['name']:$detailInfo['realname']; ?> - <?php echo $detailInfo['mobile']; ?> - <?php echo (isset($detailInfo['declare_info']['idcard']) && ($detailInfo['declare_info']['idcard'] !== '')?$detailInfo['declare_info']['idcard']:'无'); ?> - <?php echo $detailInfo['detail_address']; ?> </span>
						<span> &emsp;<button class="button bg-blue button-little" onclick="edit_info('<?php echo url('Order/editReceiptInfo', ['member_id'=>$detailInfo['member_id'],'order_id'=>$detailInfo['id']]); ?>')">修改收货人信息</button></span>
					</span>
					<br>
				</div>
				<br>
				<!-- <div class="alert border border-mix" style="height: 20%;">
					<p class="text-break"><span class="text-blue text-big">备注信息：</span><?php echo (isset($detailInfo['remark']) && ($detailInfo['remark'] !== '')?$detailInfo['remark']:"无"); ?></p>
				</div> -->
			</div>
		</div>
	</div>

	<div class="panel margin-top">
		<div class="panel-head bg-main">
			<span>费用信息</span>
		</div>
		<div class="panel-body">
			<div class="alert border border-sub">
				<div class="alert border border-mix">
					<span>
						<span class="text-blue text-big">售价合计：</span>
						<span class="">￥<span class="text-red"><?php echo $detailInfo['amount'] - $detailInfo['postage']; ?></span></span>
					</span>
					&emsp;|&emsp;
					<span>
						<span class="text-blue text-big">订单运费：</span>
						<span class="">￥<span class="text-red"><?php echo $detailInfo['postage']; ?></span></span>
					</span>
					&emsp;|&emsp;
					<span>
						<span class="text-blue text-big">订单总额：</span>
						<span class="">￥<span class="text-red"><?php echo $detailInfo['amount']; ?></span></span>
					</span>
					&emsp;|&emsp;
					<span>
						<span class="text-blue text-big">完成金额：</span>
						<span class="">
							<?php if($detailInfo['pay_status'] == 0): ?>
								订单未支付
							<?php else: if($detailInfo['overtime'] == '未完成'): ?>
									订未单完成
									<?php else: ?>
									￥<span class="text-red"><?php echo $detailInfo['amount']; ?></span>
								<?php endif; endif; ?>
						</span>
					</span>
					<br>
				</div>
			</div>
		</div>
	</div>

	<div class="panel margin-top">
		<div class="panel-head bg-main">
			<span>商品信息</span>
		</div>
		<div class="panel-body">
			<div class="table-responsive-y">
				<table class="table table-hover table-bordered">
					<thead>
					<tr class="bg-sub">
						<th >商品图片</th>
						<th >商品名称</th>
						<th >商品专区</th>
						<th >商品分类</th>
						<?php if(isset($is_show)): ?>
							<th >成本价(单价)</th>
						<?php endif; ?>
						<th >商品现价(单价)</th>
						<th >商品税费</th>
						<th >商品数量</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach($detailInfo['more_order_goods'] as $v): ?>
						<tr class="text-center">
							<td>
								<img src="<?php echo $v['attr_pic']; ?>" data-image="<?php echo $v['attr_pic']; ?>" width="40" height="40" data-toggle="hover" class="radius tips" />
							</td>
							<td><a href="#"><?php echo $v['subtitle']; ?></a></td>
							<td><?php echo $v['type_id']; ?></td>
							<td><?php echo $v['cate_name']; ?></td>
							<?php if(isset($is_show)): ?>
								<td><?php echo $v['oprice']; ?></td>
							<?php endif; ?>
							<td><?php echo $v['price']; ?></td>
							<td><?php echo $v['suiprice']; ?></td>
							<td><?php echo $v['num']; ?></td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<?php if(isset($is_show)): ?>
		<div class="panel margin-top table-responsive-y">
			<div class="panel-head bg-main">
				<span>订单操作记录</span>
			</div>
			<div class="panel-body">
				<div class="table-responsive-y">
					<table class="table table-hover table-bordered">
						<thead>
						<tr class="bg-sub">
							<th>操作者</th><th>订单状态</th><th>付款状态</th><th>日志(备注)</th><th>操作时间</th>
						</tr>
						</thead>
						<tbody>
							<?php foreach($orderlogs as $vs): ?>
								<tr>
									<td><?php echo $vs['name']; ?></td>
									<td><?php echo $vs['order_status']['text']; ?></td>
									<td><?php echo $vs['pay_status']; ?></td>
									<td><?php echo $vs['logs']; ?>(<?php echo !empty($vs['content'])?$vs['content']:''; ?>)</td>
									<td><?php echo $vs['addtime']; ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<br>
<script src="__STATIC__/js/admin.js"></script>
<script type="text/javascript">
    /*管理员-编辑*/
    function admin_edit(title,url,w,h){
        layer_show(title,url,w,h);
    }

    function edit_info(url){
        layer_show('修改收货信息',url,600,500);
    }

    function complete_order(url){
    	$.get(url, {}, function(data){
    		if(data.code == 200){
                layer.msg(data.msg, {icon: 1,time:2000},function () {
                        // window.opener.location.reload();
                        window.location.reload();
                    });

            }else{
                layer.msg(data.msg, {icon: 0,time:2000});
            }
    	},'json');
    }

    function show_express(url)
    {
    	layer_show('查看物流',url)
    }

    function show_pic(str){
        layer_show('身份证照片',str,800,400);
	}
</script>
		</div>
	</div>
</body>
<script src="/plugins/layer/2.4/layer.js"></script>
<script src="__STATIC__/pintuer/pintuer.js"></script>
<script src="__STATIC__/pintuer/respond.js"></script>
<script src="/plugins/h-ui.admin/js/H-ui.admin.page.js"></script>
</html>

