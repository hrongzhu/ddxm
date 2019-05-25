<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:50:"themes/admin_simpleboot3/admin\collect\refund.html";i:1554867196;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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
</head>
<body>
	<div class="detail-product">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>
						选择
					</th>
					<th>商品/服务名称</th>
					<th>结算价格</th>
					<th>数量</th>
					<th>小计</th>
				</tr>
			</thead>
			<tbody id="datas">
				<?php if(!empty($list)): foreach ($list as $v): ?>
					<tr>
						<td>
							<label>
					          <input type="checkbox" name="id[]" onchange="sumPrice()" value="<?php echo $v['goods_id']; ?>">
					        </label>
						</td>
						<td><?php echo $v['name']; ?></td>
						<td class='price'>￥<span><?php echo (isset($v['price']) && ($v['price'] !== '')?$v['price']:0); ?></span> 元</td>
						<td>
							<p>
								<!-- <span></span> -->
								<!-- <input type="num" class="nums" onkeyup= "if(! /^d+$/.test(this.value)){layer.msg('只能输入整数', {icon: 0,time:1000});this.value='<?php echo (isset($v['num']) && ($v['num'] !== '')?$v['num']:1); ?>';}" value="<?php echo (isset($v['num']) && ($v['num'] !== '')?$v['num']:1); ?>"> -->
								<?php if($v['class']==1): ?>
                                    <input type="num" class="nums" onchange="edit_num(this,<?php echo $v['num']-$v['refund_num']-$v['dq_num']; ?>)" value="<?php echo (isset($v['num']) && ($v['num'] !== '')?$v['num']:1); ?>">
                                <?php else: ?>
                                    <input type="num" class="nums" onchange="edit_num(this,<?php echo $v['num']; ?>)" value="<?php echo (isset($v['num']) && ($v['num'] !== '')?$v['num']:1); ?>">
                                <?php endif; ?>
								<!-- <span>+</span> -->
							</p>
						</td>
						<td class="hj_price">￥<span><?php echo $v['price'] * $v['num']; ?></span></td>
						<td style="display: none;"><?php echo (isset($v['class']) && ($v['class'] !== '')?$v['class']:1); ?></td>
					</tr>
					<?php endforeach; else: ?>
					<tr>
						<td class="detail-head" colspan="5" style="text-align:center;">没有可退款的商品</td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
		<div class="other">
			<!-- 支付方式除熊猫卡 -->
			<?php if (!empty($pay_way) && $pay_way !== 3): ?>
				<p>请核对退货信息，请“ 现金 ”退还客户</p>
				<p><span class="zprice" style="font-size: 25px">0</span> 元</p>
			<?php else: ?>
				<!-- 支付方式为是熊猫卡支付 -->
				<p>请核对退货信息，确认后将退还客户<span class="zprice" style="font-size: 25px">0</span> 元 至其熊猫卡内</p>
			<?php endif ?>
		</div>
		<input type="hidden" id="order-id" value="<?php echo $order_id; ?>">
		<div class="foot">
			<button type="button" id="close_btn" class="btn btn-default">取消</button>
			<?php if($order_type == 1): ?>
				<button type="button" onclick="submits('<?php echo url('collect/queryRefund'); ?>')" class="btn btn-success">确认退款</button>
				<?php else: ?>
				<button type="button" class="btn btn-danger disabled">预定单不在此处退款</button>
			<?php endif; ?>
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

	function edit_num(obj,nums){
		$num = $(obj).val();
		if ($num > nums) {
			layer.msg('数量不能超过最大数量', {icon:0,time:2000});
			$(obj).val(nums);
			return false;
		}else if(Number($num) <= 0){
			layer.msg('数量不能小于1', {icon:0,time:2000});
			$(obj).val(nums);
			return false;
		}
		$price = $(obj).parent().parent().prev().children().text();
		$hj_price = $num * $price;
		$(obj).parent().parent().next().children().text($hj_price);
		sumPrice();
		layer.msg('退货数量已修改，该订单下此商品/服务无法再次退货',{icon:0,time:3000});
		// console.log($hj_price);return;
	}

	function submits(url)
	{
		var order_id = $('#order-id').val();
		var lists = {
				order_id:order_id,
				goods_list:[]
			};
			$('#datas tr').each(function(i){
				var list = {
					goods_id: '',
					num: 0,
					type: ''
				};
				$xz = $(this).children('td').find('input').prop('checked');
				if ($xz) {
					$(this).children('td').each(function(j){
						if (j === 0) {
							list.goods_id = $(this).find('input').val();
						}
						if (j === 3 ) {
							list.num = $(this).find('input').val();
						}
						if (j === 5 ) {
							list.type = $(this).text();
						}
					});
					lists.goods_list.push(list);
				}
			});

		layer.confirm('确认无误？', {
            btn: ['确定','取消'] //按钮
        }, function(){
        	if (lists.goods_list == '') {
        		layer.msg('请选择退款商品', {icon: 0,time:2000});
        		return false;
        	}
		    $.post(url,{list:lists},function(res){
		    	if (res.code == 200) {
	                    layer.msg(res.msg, {icon: 1,time:2000},function () {
	                        window.parent.location.reload();
	                    });
	                }else {
	                    layer.msg(res.msg, {icon: 0,time:2000});
	                }
		    },'json')
        });
	}

	function sumPrice()
	{
		var zprice = 0;
		$('#datas tr').each(function(i){
			var num = 0;
			var price = 0;
			$xz = $(this).children('td').find('input').prop('checked');
			if ($xz) {
				$(this).children('td').each(function(j){
					if (j === 2 ) {
						price = $(this).find('span').text();
					}
					if (j === 3 ) {
						num = $(this).find('input').val();
					}
				});
				zprice += price*num;
			}
			$('.zprice').text(zprice);
		});

	}

</script>
</body>
</html>
