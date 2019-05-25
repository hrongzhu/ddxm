<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:49:"themes/admin_simpleboot3/admin\order\expense.html";i:1554867187;s:45:"themes/admin_simpleboot3/public\ptheader.html";i:1554867210;s:45:"themes/admin_simpleboot3/public\ptfooter.html";i:1554867211;}*/ ?>
<!-- 修改订单成本 -->
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

<style>
	.detail-product {
		padding: 30px 40px;
	}
	.detail-product table thead { background-color: #f5f5f5; }
	.detail-product table thead th:nth-child(2) { width: 30%; }
	.detail-product table thead th:nth-child(4) { width: 22%; }
	.detail-product table label { margin-bottom: 0; }
	.detail-product table p {
		display: flex;
		flex-direction: row;
		justify-content: center;
		align-items: center;
		border: 1px solid #f5f5f5;
		border-radius: 2px;
	}
	.detail-product table p span {
		width: 30%;
		text-align: center;
		cursor: pointer;
	}
	.detail-product table p input { width: 40%; text-align: center; }
	.other p { color: #f56651; }
	.foot {
		padding: 30px 40px;
		text-align: right;
	}
	.foot .btn { margin-left: 20px; padding: 6px 20px; }
</style>
	<div class="panel margin-top">
		<div class="panel-head bg-main">
			<span>订单成本</span>
		</div>
		<div class="panel-body">
		<table class="table table-responsive-y table-bordered">
			<tr>
				<th>商品名称</th>
				<th>本商品结算单价</th>
				<th>商品数量</th>
				<th>本商品结算总价</th>
				<th>真实成本价</th>
				<th>假成本价</th>
			</tr>
			<tbody id="datas">
				<?php if(!empty($info)): foreach ($info as $v): ?>
					<tr>
						<td style="display: none;"><input type="text" class="input" name="id" value="<?php echo $v['id']; ?>"></td>
						<td><?php echo $v['subtitle']; ?></td>
						<td class='price'><?php echo (isset($v['price']) && ($v['price'] !== '')?$v['price']:0); ?>元</td>
						<td>
							<p>
								<?php echo (isset($v['num']) && ($v['num'] !== '')?$v['num']:1); ?>
							</p>
						</td>
						<td class="hj_price"><?php echo round($v['price'] * $v['num'],2); ?> 元</td>
						<td>
							<input type="text" class="costprice input"  value="<?php echo round($v['cost_price'],2); ?>" placeholder="添加当前商品的真成本总价">
						</td>
						<td>
							<input type="text" class="costprice input"  value="<?php echo round($v['faker_price'],2); ?>" placeholder="添加当前商品的假成本总价">
						</td>
					</tr>
					<?php endforeach; ?>
					<tr>
						<td style="display: none;"><input type="text" name="id" value="0"></td>
						<td>订单邮费</td>
						<td class='price'><span></span>不计</td>
						<td>
							<p>
								1
							</p>
						</td>
						<td class="hj_price"><span>无</span></td>
						<td >
							<input type="text" id="postage" class="costprice input" value="0">
						</td>
						<td >
							<input type="text" disabled class=" input" value="无">
						</td>
					</tr>
				<?php else: ?>
					<tr>
						<td class="detail-head" colspan="5" style="text-align:center;">没有商品</td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
        </div>
		<input type="hidden" id="order-id" value="<?php echo $order_id; ?>">
		<div class="foot">
			<button type="button" id="close_btn" class="button radius-none bg-red">取消</button>
			<button type="button" onclick="submits('<?php echo url('order/expense'); ?>')" class="button radius-none bg-green">确认</button>
		</div>
	</div>
<script src="__STATIC__/js/admin.js"></script>
<script type="text/javascript">

	$(function(){
		$(".costprice").keyup(function(event) {
	        var keyCode = event.which;
	         if (keyCode == 46 || (keyCode >= 48 && keyCode <=57) ||(keyCode >= 96 && keyCode <=105)){
	         	return true;
	         }else if(keyCode == 110 || keyCode == 190){
	         	return true;
	         }else{
	         	$(this).val('');
	         }
	        }).focus(function() {
               this.style.imeMode='disabled';
        	});

		$('#close_btn').click(function(){
			var index = parent.layer.getFrameIndex(window.name);
	        parent.layer.close(index);
		})
	})

	function submits(url)
	{
		var order_id = $('#order-id').val();
		var postage = $('#postage').val();
		var lists = {
				order_id:order_id,
				goods_list:[],
				postage:postage
			};
			$('#datas tr').each(function(i){
				var list = {
					id:0,
					cost_price: '',
					faker_price: '',
				};
				$(this).children('td').each(function(j){
					if (j === 0 ) {
						list.id = $(this).find('input').val();
					}
					if (j === 5 ) {
						list.cost_price = $(this).find('input').val();
					}
                    if (j === 6 ) {
                        list.faker_price = $(this).find('input').val();
                    }
				});
				lists.goods_list.push(list);
			});

		layer.confirm('确认无误？', {
            btn: ['确定','取消'] //按钮
        }, function(){
		    $.post(url,{list:lists},function(res){
		    	if (res.code == 200) {
	                    layer.msg(res.msg, {icon: 1,time:2000},function () {
	                        parent.layer.close(parent.layer.getFrameIndex(window.name));
	                    });
	                }else {
	                    layer.msg(res.msg, {icon: 0,time:2000});
	                }
		    },'json')
        });
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

