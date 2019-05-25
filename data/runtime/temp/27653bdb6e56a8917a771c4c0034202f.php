<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:50:"themes/admin_simpleboot3/admin\collect\detail.html";i:1554867198;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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

</head>
<style>
	.detail-head {
		line-height: 50px;
		font-size: 16px;
		font-weight: 600;
		background-color: #eee;
		padding-left: 20px;
	}
	.detail-content {
		padding: 20px 36px;
		border-bottom: 1px solid rgba(228, 228, 228, 1);
	}
	.detail-content table thead {
		background-color: #f5f5f5;
	}
	.detail-content table thead tr th:nth-child(1) {
		width: 30%;
	}
	.detail-content table tr { height: 50px; }
	.detail-content .items {
		border-bottom: 1px solid rgba(228, 228, 228, 1);
	}
	.detail-content .items p { font-size: 0; }
	.detail-content .special p { font-size: 14px; }
	.detail-content .items p span { font-size: 14px; display: inline-block; width: 50%; }
	.detail-footer {
		padding: 30px;
	}
	.detail-footer .btn { padding: 6px 20px; margin-left: 20px; }
</style>
<body>
	<div class="detail-head">
		订单明细-<?php echo $info['sn']; ?>
	</div>
	<div class="detail-content">
		<div class="items">
			<h5 style="margin-top: 0;"><strong>订单详情</strong></h5>
			<p>
				<span>交易时间：<?php echo (isset($info['paytime']) && ($info['paytime'] !== '')?$info['paytime']:'未知'); ?></span>
				<span>交易门店：<?php echo (isset($info['shop_info']['code']) && ($info['shop_info']['code'] !== '')?$info['shop_info']['code']:'无'); ?> - <?php echo (isset($info['shop_info']['name']) && ($info['shop_info']['name'] !== '')?$info['shop_info']['name']:'未知'); ?></span>
			</p>
			<p>
				<span>交易渠道：门店收银</span>
				<span>服务人员：<?php echo (isset($info['waiter']) && ($info['waiter'] !== '')?$info['waiter']:'无'); ?></span>
			</p>
			<?php if (isset($show_cost)): ?>
				<p>
					<span>订单总成本：<?php echo $info['order_goods_cost']; ?> 元</span>
				</p>
			<?php endif ?>
		</div>
		<div class="items special">
			<h5><strong>收款信息</strong></h4>
			<p>应收金额：<?php echo (isset($info['amount']) && ($info['amount'] !== '')?$info['amount']:0); ?> 元</p>
			<p>实收金额：<?php echo (isset($info['amount']) && ($info['amount'] !== '')?$info['amount']:0); ?> 元&emsp;【<?php echo (isset($info['pay_way']) && ($info['pay_way'] !== '')?$info['pay_way']:'未知'); ?>扣款：<?php echo (isset($info['amount']) && ($info['amount'] !== '')?$info['amount']:0); ?>元】</p>
			<p>合计金额：<?php echo (isset($info['old_price']) && ($info['old_price'] !== '')?$info['old_price']:0); ?> 元</p>
			<p>
				优惠合计：	<?php if($info['discount_info']['discount_price'] >= 0): ?>
							<?php echo (- $info['discount_info']['discount_price']);  ?> 元
							<?php else: ?>
							+ <?php echo (- $info['discount_info']['discount_price']);  ?> 元
							<?php endif; if(!empty($info['discount_info']['vip_discount'])): ?>
					【会员折扣：- <?php echo $info['discount_info']['vip_discount']; ?> 元】
				<?php endif; if(!empty($info['discount_info']['eprice_discount'])): if($info['discount_info']['discount_price'] >= 0): ?>
					&emsp;【改价优惠：<?php echo (- $info['discount_info']['eprice_discount']);  ?> 元】
					<?php else: ?>
					&emsp;【改价优惠：+ <?php echo (- $info['discount_info']['eprice_discount']);  ?>元】
					<?php endif; if($info['discount_info']['ticket_discount'] >= 0): ?>
						【代金券抵扣：<?php echo (- $info['discount_info']['ticket_discount']);  ?> 元】
						<?php else: ?>
						【代金券抵扣：+ <?php echo (- $info['discount_info']['ticket_discount']);  ?>元】
					<?php endif; endif; ?>
			</p>
		</div>
		<div class="items">
			<h5><strong>改价信息</strong></h5>

				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>商品/服务名称</th>
							<th>改价前</th>
							<th>改价后</th>
							<th>原因</th>
						</tr>
					</thead>
					<tbody>
						<?php if(isset($info['edit_price_goods']) && !empty($info['edit_price_goods'])): foreach ($info['edit_price_goods'] as $v): ?>
								<tr>
									<td><?php echo $v['subtitle']; ?></td>
									<td><?php echo $v['price']; ?> 元</td>
									<td><?php echo round($v['price'] - $v['disprice'],2); ?> 元</td>
									<td><?php echo $v['content']; ?></td>
								</tr>
							<?php endforeach; else: ?>
							<tr>
								<td class="detail-head" colspan="4" style="text-align:center;">没有改价商品信息</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>

		</div>
		<div class="items">
			<h5><strong>使用代金券</strong></h5>
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>优惠券名称</th>
							<th>价值</th>
						</tr>
					</thead>
					<tbody>
						<?php if(isset($info['ticket_used_list']) && !empty($info['ticket_used_list'])): foreach ($info['ticket_used_list'] as $v): ?>
								<tr>
									<td><?php echo $v['name']; ?></td>
									<td><?php echo $v['money']; ?> 元</td>
								</tr>
							<?php endforeach; else: ?>
							<tr>
								<td class="detail-head" colspan="4" style="text-align:center;">没有使用代金券</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>

		</div>
		<div class="items">
			<h5><strong>会员信息</strong></h5>
			<p>
				<span>会员帐号：<?php echo (isset($info['member_info']['telephone']) && ($info['member_info']['telephone'] !== '')?$info['member_info']['telephone']:'无'); ?></span>
				<span>会员昵称：<?php echo (isset($info['member_info']['nickname']) && ($info['member_info']['nickname'] !== '')?$info['member_info']['nickname']:'匿名用户'); ?></span>
			</p>
			<p>
				<span>绑定门店：<?php echo (isset($info['member_info']['shop_name']) && ($info['member_info']['shop_name'] !== '')?$info['member_info']['shop_name']:'无'); ?></span>
				<span>会员等级：【<?php echo (isset($info['member_info']['level_name']) && ($info['member_info']['level_name'] !== '')?$info['member_info']['level_name']:'无'); ?>】</span>
			</p>
		</div>
		<div class="items">
			<h5><strong>消费信息</strong></h5>
				<table class="table table-bordered table-hover">
					<thead class="">
						<tr>
							<th>商品/服务名称</th>
							<th>原价</th>
							<th>结算价格</th>
							<th>数量</th>
							<?php if($info['order_type']==2): ?>
								<th>已退款数量</th>
								<th>待取数量</th>
							<?php endif; ?>
							<th>小计</th>
							<?php if (isset($show_cost)): ?>
								<th>成本价</th>
								<th>门店进价</th>
							<?php endif ?>
							<th>状态</th>
						</tr>
					</thead>
					<tbody>
						<?php if(isset($info['more_order_goods']) && !empty($info['more_order_goods'])): foreach ($info['more_order_goods'] as $v): ?>
							<tr>
								<td><?php echo $v['name']; ?></td>
								<td><?php echo $v['og_price']!=0?'￥'.$v['og_price']:'-'; ?></td>
								<td>￥<?php echo (isset($v['price']) && ($v['price'] !== '')?$v['price']:0); ?></td>
								<td><?php echo $v['num']; ?></td>
								<?php if($info['order_type']==2): ?>
									<td><?php echo $v['refund_num']; ?></td>
									<td><?php echo $v['dq_num']; ?></td>
								<?php endif; ?>
								<td>￥<?php echo round($v['price'] * $v['num'],2); ?></td>
								<?php if (isset($show_cost)): ?>
									<td>￥<?php echo round($v['oprice'] * $v['num'],2); ?></td>
									<td>￥<?php echo round($v['faker_price'] * $v['num'],2); ?></td>
								<?php endif ?>
								<td><?php echo $v['status']===1 || $v['status']== 0?"正常":"退货"; ?></td>
							</tr>
							<?php endforeach; else: ?>
							<tr>
								<td class="detail-head" colspan="6" style="text-align:center;">没有商品信息</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
		</div>
	</div>
	<div class="detail-footer text-right">
		<button type="button" id="refund" class="btn btn-danger return-goods">退货</button>
		<button type="button" id="close_btn" class="btn btn-success">关闭</button>
	</div>
<script src="__STATIC__/js/admin.js"></script>
<script type="text/javascript">
$(function(){
	$('#refund').click(function(){
		var url = '<?php echo url('collect/refund',['id'=>$info['id']]); ?>';
	    layer_show('订单退货',url,600);
	})

	$('#close_btn').click(function(){
		var index = parent.layer.getFrameIndex(window.name);
        parent.layer.close(index);
	})
})
</script>
</body>
</html>
