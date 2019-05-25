<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:59:"themes/admin_simpleboot3/admin\order\order_refund_list.html";i:1554867186;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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
        <form class="well form-inline margin-top-0" method="get" action="<?php echo url('Order/orderRefundList'); ?>">
            服务单号:
            <input type="text" class="form-control" name="as_sn" style="width: 120px;" value="<?php echo input('request.as_sn/s',''); ?>" placeholder="请输入单号">
            收货人:
            <input type="text" class="form-control" name="name" style="width: 120px;" value="<?php echo input('request.name/s',''); ?>" placeholder="请输入收货人">
			处理状态:
			<select class="form-control" name="status">
				<option value="" <?php if($status == ''): ?>selected<?php endif; ?>>请选择</option>
				<option value="0" <?php if($status == '0'): ?>selected<?php endif; ?>>未处理</option>
				<option value="1" <?php if($status == 1): ?>selected<?php endif; ?>>已处理</option>
			</select>
            <input type="submit" class="btn btn-primary" value="查询" />
            <a class="btn btn-danger" href="<?php echo url('Order/orderRefundList'); ?>">清空</a>
        </form>
		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<!--<th>选择</th>-->
					<th >服务单号</th>
					<th >申请时间</th>
					<th >联系人</th>
					<th >申请状态</th>
					<th >处理时间</th>
					<th >操作</th>
				</tr>
			</thead>
			<tbody>
			<?php if(is_array($datas) || $datas instanceof \think\Collection || $datas instanceof \think\Paginator): if( count($datas)==0 ) : echo "" ;else: foreach($datas as $key=>$v): ?>
				<tr style="height: 55px;">
					<!--<td><input type="checkbox" name="ids[]" value=""></td>-->
					<td><?php echo $v['as_sn']; ?></td>
					<td><?php echo $v['add_time']; ?></td>
					<td><?php echo $v['name']; ?></td>
					<td>
						<?php if($v['status'] == -1): ?>
							取消申请
							<?php elseif($v['status'] == 0): ?>
								待处理
								<?php else: ?>
								已完成
						<?php endif; ?>
					</td>
					<td><?php echo $v['finish_time']; ?></td>
					<td>
						<a href="javascript:void (0);" onclick="r_detail('订单详情','<?php echo url('Order/refundDetail', ['id'=>$v['as_id']]); ?>')">查看详情</a>
						<a href="javascript:void (0);" onclick="delete_refund('<?php echo url('Order/refundDel'); ?>',<?php echo $v['as_id']; ?>,$(this))">删除</a>
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
		<div style="float: right;margin-left: 10px;" class="pagination"><?php echo $page; ?></div>
	</div>
	<script src="__STATIC__/js/admin.js"></script>
<script type="text/javascript">
    /*编辑*/
    function r_detail(title,url){
        layer_show(title,url,1000,800);
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
    function delete_refund(url,id,obj) {
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
                if (data.code == 0) {
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
        /*var index = layer.open({
            type: 2,
            content: url,
            area: ['300px', '195px'],
            maxmin: true,
            zIndex:700
        });
        layer.full(index);*/
        layer_show(title,url,w,h);
    }
    //测试扫码支付
    function dealwith(title,url,url2) {
        $.get(url2, {}, function(data){
            if (data.code == 200) {
                window.location.href = url;
            }else {
                layer.msg(data.msg, {icon: 0,time:2000});
                setTimeout('reloads()', 2000);
            }
        });
    }

    //刷新页面
    function reloads(){
        window.location.reload();
	}
</script>
</body>
</html>