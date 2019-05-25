<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:52:"themes/admin_simpleboot3/admin\order\order_list.html";i:1554867186;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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
<body>
	<div class="wrap js-check-wrap">
		<ul class="nav nav-tabs">
			<li class="<?php if($order_status == -100 && $pay_status !=0): ?>active<?php endif; ?>"><a href="<?php echo url('Order/orderList',['order_status' => -100]); ?>">订单列表</a></li>
			<!-- <li class="<?php if($order_status == 9): ?>active<?php endif; ?>"><a href="<?php echo url('Order/orderList',['order_status' => 9]); ?>">待处理</a></li> -->
			<li class="<?php if($order_status === 0): ?>active<?php endif; ?>"><a href="<?php echo url('Order/orderList',['order_status' => 0]); ?>">待发货</a></li>
			<li class="<?php if($order_status == 1): ?>active<?php endif; ?>"><a href="<?php echo url('Order/orderList',['order_status' => 1]); ?>">待确认</a></li>
			<li class="<?php if($order_status == 1000): ?>active<?php endif; ?>"><a href="<?php echo url('Order/orderList',['order_status' => 1000]); ?>">已完成</a></li>
			<li class="<?php if($order_status == -7): ?>active<?php endif; ?>"><a href="<?php echo url('Order/orderList',['order_status' => -7]); ?>">已取消</a></li>
		</ul>
        <form class="well form-inline margin-top-0" method="get" action="<?php echo url('Order/orderList',['order_status' => isset($order_status) ? $order_status : ""]); ?>">
			<input type="text" class="form-control" name="sn" style="width: 120px;" value="<?php echo input('request.sn/s',''); ?>" placeholder="订单号">
            <input type="text" class="form-control" name="mobile" style="width: 120px;" value="<?php echo input('request.mobile/s',''); ?>" placeholder="手机号">
            <input type="text" class="form-control" name="subtitle" style="width: 120px;" value="<?php echo input('request.subtitle/s',''); ?>" placeholder="商品名">
			<input type="text" class="form-control" name="nickname" style="width: 120px;" value="<?php echo input('request.nickname/s',''); ?>" placeholder="购买人">
			<select class="form-control" name="shop_id">
				<option value="">所属门店</option>
				<?php foreach($shopDatas as $v): ?>
					<option <?php if($shop_id == $v['id']): ?>selected<?php endif; ?> value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
				<?php endforeach; ?>
			</select>
			<select class="form-control" name="pay_way">
				<option value="">付款方式</option>
				<option <?php echo $pay_way==1?'selected':""; ?> value="1">微信</option>
				<option <?php echo $pay_way==2?'selected':""; ?> value="2">支付宝</option>
				<option <?php echo $pay_way==3?'selected':""; ?> value="3">余额</option>
			</select>
            <input type="submit" class="btn btn-primary" value="搜索" />
            <a class="btn btn-danger" href="<?php echo url('Order/orderList',['order_status' => isset($order_status) ? $order_status : ""]); ?>">清空</a>
        </form>
		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th>选择</th>
					<th width="50">ID</th>
					<th >订单号</th>
					<th >所属门店</th>
					<th>商品名称</th>
					<th >购买人</th>
					<th >会员星级</th>
					<th >邮费</th>
					<th >商品数量</th>
					<th >总金额</th>
					<?php if($show == 1): ?>
						<th >公司成本</th>
						<th >门店成本</th>
					<?php endif; ?>
					<th >下单时间</th>
					<th >状态</th>
					<th >发货方式</th>
					<th >支付方式</th>
					<th >操作</th>
				</tr>
			</thead>
			<tbody>
			<?php if(is_array($datas) || $datas instanceof \think\Collection || $datas instanceof \think\Paginator): if( count($datas)==0 ) : echo "" ;else: foreach($datas as $key=>$v): ?>
				<tr style="height: 55px;border-radius: 3px;">
					<td><input type="checkbox" name="ids[]" value=""></td>
					<td><?php echo $v['id']; ?></td>
					<td><?php echo $v['sn']; ?></td>
					<td><?php echo $v['shop_id']; ?></td>
					<td style="overflow: hidden;text-overflow: ellipsis;white-space: nowrap; max-width: 200px;"><?php echo $v['subtitle']; ?></td>
					<td><?php echo $v['nickname']; ?></td>
					<td><?php echo $v['level_name']; ?></td>
					<td><?php echo $v['postage']; ?></td>
					<td><?php echo $v['number']; ?></td>
					<?php if($v['order_status']['status'] == -1): ?>
						<td style="color:red"><?php echo $v['amount']; ?></td>
						<?php else: ?>
						<td><?php echo $v['amount']; ?></td>
					<?php endif; if($show == 1): ?>
						<td><?php echo $v['sum_cost']; ?></td>
						<td><?php echo $v['faker_cost']; ?></td>
					<?php endif; ?>
					<td><?php echo date('Y-m-d H:i',$v['addtime']); ?></td>
					<td>
						<?php if($v['pay_status'] == -1 || $v['order_status']['status']== -7): ?>
							已取消
						<?php elseif($v['pay_status'] == 0): ?>
							待付款
						<?php else: ?>
							<?php echo $v['order_status']['text']; endif; ?>
					</td>
					<td>
						<?php echo $v['send_way']; ?>
					</td>
					<td>
						<?php if($v['pay_status'] == -1 || $v['order_status']['status']== -7): ?>
							交易关闭
							<?php elseif($v['pay_status'] == 0): ?>
								未支付
							<?php else: ?>
								<?php echo $v['pay_way']; endif; ?>
					</td>
					<td>
						<a href="javascript:parent.openIframeLayer('<?php echo url('Order/detail', ['id'=>$v['id']]); ?>','详情',{});">查看详情</a>|
						<?php if($v['pay_status'] == 0): if($v['order_status']['status'] != -7): ?>
								<a href="javascript:void (0);" onclick="close_order('<?php echo url('Order/closeOrder', ['order_id'=>$v['id']]); ?>')">关闭订单</a>|
							<?php endif; elseif($v['pay_status'] == 1): if($v['order_status']['status'] == 0 && $admin_id == 1): ?>
								<a href="javascript:void (0);" onclick="close_order('<?php echo url('Order/closeOrder', ['order_id'=>$v['id']]); ?>')">取消订单</a>|
								<a href="javascript:parent.openIframeLayer('<?php echo url('Order/deliver', ['order_id'=>$v['id']]); ?>','发货',{});">发货</a>|
							<?php endif; if($v['order_status']['status'] == 1): ?>
								<a href="javascript:parent.openIframeLayer('<?php echo url('Order/deliver', ['order_id'=>$v['id']]); ?>','修改发货信息',{});">修改发货信息</a>|<a href="javascript:void (0);" onclick="express('物流跟踪', '<?php echo url('Order/express', ['order_id'=>$v['id']]); ?>','1000','800')">物流跟踪</a>|
							<?php endif; else: endif; ?>
						<a href="javascript:void (0);" onclick="delete_order('<?php echo url('Order/delete'); ?>',<?php echo $v['id']; ?>,$(this))">删除</a>
					</td>
				</tr>
				<?php endforeach; endif; else: echo "" ;endif; ?>
			</tbody>
		</table>
		<!--<div class="pagination" style="float: left">-->
			<!--<button class="btn btn-success" id="selectAll">全选</button>-->
			<!--<button class="btn btn-warning" id="closeAll" style="margin-left: 10px;">关闭订单</button>-->
			<!--<button class="btn btn-danger" id="delAll" style="margin-left: 10px;">删除订单</button>-->
		<!--</div>-->
		<div style="float: left;margin-left: 10px;" class="pagination"><?php echo $page; ?></div>
	</div>
	<script src="__STATIC__/js/admin.js"></script>
<script type="text/javascript">
    /*编辑*/
    function admin_edit(title,url,w,h){
        layer_show(title,url,w,h);
    }
    //单击双击=----------------------------------
    $("#selectAll").click(function () {
        $("input:checkbox").each(function () {
            $(this).prop('checked', true);//
        });
    });
    $("#selectAll").dblclick(function () {
        $("input:checkbox").each(function () {
            $(this).prop('checked', false);//
        });
    });
//------------------------------------------------
    //删除订单
    function delete_order(url,id,obj) {
        layer.confirm('确认要删除？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.post(url, { id: id }, function(data){
                if (data.code == 200) {
                    var link = obj.parents("tr");
                    link.remove();
                    layer.msg(data.msg, {icon: 1,time:1000});
				}else {
                    layer.msg(data.msg, {icon: 0,time:1000});
				}
            });
        });
    }
    //物流信息
    function express(title,url,w,h) {
        layer_show(title,url,w,h);
    }
    //关闭订单
    function close_order(url) {
        layer.confirm('确认要关闭？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.get(url, {}, function(data){
                if (data.code == 200) {
                    layer.msg(data.msg, {icon: 1,time:2000});
                    setTimeout('reloads()', 2000);
                }else {
                    layer.msg(data.msg, {icon: 0,time:2000});
                }
            });
        });
    }

    //修改价格
	function update_price(price,id,url){
        layer.prompt({
            formType: 0,
            value: price,
            title: '请输入价格',
        }, function(value, index){
            $.post(url, { id: id ,price:value}, function(data){
                if (data.code == 200) {
                    layer.msg(data.msg, {icon: 1,time:1000},function () {
                        location.reload();
                    });
                }else {
                    layer.msg(data.msg, {icon: 0,time:1000});
                }
            });
            layer.close(index);
        });
	}

	//发货,修改物流信息
	function deliver(title,url,w,h) {

        layer_show(title,url,w,h);
    }
//    //
//    function dealwith(title,url,url2) {
//        $.get(url2, {}, function(data){
//            if (data.code == 200) {
//                window.location.href = url;
//            }else {
//                layer.msg(data.msg, {icon: 0,time:2000});
//                setTimeout('reloads()', 2000);
//            }
//        });
//    }
    //
    function dealwith(title,url,url2) {
		window.location.href = url;
    }

    //刷新页面
    function reloads(){
        window.location.reload();
	}
</script>
</body>
</html>
