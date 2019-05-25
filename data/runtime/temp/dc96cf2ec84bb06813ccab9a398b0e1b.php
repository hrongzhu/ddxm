<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:61:"themes/admin_simpleboot3/admin\ticket\server_ticket_list.html";i:1554867203;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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
			<li class="active"><a href="<?php echo url('Ticket/ServerTicketList'); ?>">服务券列表</a></li>
		</ul>
        <form class="well form-inline margin-top-0" method="get" action="<?php echo url('Ticket/serverTicketList'); ?>">
    		<input type="text" class="form-control" name="name" style="width: 120px;" value="<?php echo input('request.name/s',''); ?>" placeholder="服务券名">
    		<input type="text" class="form-control" name="nickname" style="width: 120px;" value="<?php echo input('request.nickname/s',''); ?>" placeholder="会员昵称">
    		<input type="text" class="form-control" name="mobile" style="width: 120px;" value="<?php echo input('request.mobile/s',''); ?>" placeholder="会员手机号">
            <select class="form-control" name="workid">
    			<option value="">选择服务人员</option>
    			<?php foreach($worker_list as $vw): ?>
    				<option <?php if($workid == $vw['workid']): ?>selected<?php endif; ?> value="<?php echo $vw['workid']; ?>"><?php echo $vw['name']; ?></option>
    			<?php endforeach; ?>
    		</select>
    		<select class="form-control" name="shop_id">
    			<option value="">选择门店</option>
    			<?php foreach($shopDatas as $v): ?>
    				<option <?php if($shop_id == $v['id']): ?>selected<?php endif; ?> value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
    			<?php endforeach; ?>
    		</select>
            <select class="form-control" name="service_id">
    			<option value="">全部服务项目</option>
    			<?php foreach($server_list as $vs): ?>
    				<option <?php if($service_id == $vs['id']): ?>selected<?php endif; ?> value="<?php echo $vs['id']; ?>"><?php echo $vs['sname']; ?></option>
    			<?php endforeach; ?>
    		</select>
    		<select class="form-control" name="get_way">
    			<option value="">获取方式</option>
    			<option value="1" <?php if($get_way == 1): ?>selected<?php endif; ?>>购买</option>
    			<option value="2" <?php if($get_way == 2): ?>selected<?php endif; ?>>兑换</option>
    		</select>
            <select class="form-control" name="status">
    			<option value="1000" >全部状态</option>
    			<!-- <option value="0" <?php if($status == 0): ?>selected<?php endif; ?>>禁用</option> -->
                <option value="1" <?php if($status == 1): ?>selected<?php endif; ?>>待核销</option>
                <option value="2" <?php if($status == 2): ?>selected<?php endif; ?>>已核销</option>
    			<option value="3" <?php if($status == 3): ?>selected<?php endif; ?>>已过期</option>
    		</select>
    		<input type="submit" class="btn btn-primary" value="搜索" />
    		<a class="btn btn-danger" href="<?php echo url('Ticket/serverTicketList'); ?>">清空</a>
    	</form>
		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th width="50">STID</th>
					<th >服务券名称</th>
					<th >服务项目</th>
					<th >获取类型</th>
					<th >消耗</th>
					<th >会员名</th>
					<th >会员电话</th>
					<th >所属店铺</th>
					<th >获取时间</th>
					<th >服务人员</th>
					<th >核销时间</th>
					<th >状态</th>
					<th >操作</th>
				</tr>
			</thead>
			<tbody>
			<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $key=>$v): ?>
				<tr>
					<td><?php echo $v['id']; ?></td>
					<td><?php echo $v['name']; ?></td>
					<td><?php echo $v['sname']; ?></td>
					<td><?php echo $v['get_way']==1?'售卖':'兑换'; ?></td>
					<td><?php echo $v['integral_price']; ?><?php echo $v['get_way']==1?'元':'积分'; ?></td>
					<td><?php echo $v['nickname']; ?></td>
					<td><?php echo $v['mobile']; ?></td>
					<td><?php echo $v['shop_name']; ?></td>
					<td><?php echo !empty($v['paytime'])?date('Y-m-d H:i',$v['paytime']):'N/A'; ?></td>
					<td><?php echo !empty($v['worker_name'])?$v['worker_name']:'无'; ?></td>
					<td><?php echo !empty($v['abolish_time'])?date('Y-m-d H:i',$v['abolish_time']):'N/A'; ?></td>
					<td><?php if($v['status']==0): ?>已禁用<?php endif; if($v['status']==1): ?>待核销<?php endif; if($v['status']==2): ?>已核销<?php endif; if($v['status']==3): ?>已过期<?php endif; ?></td>
					<td>
						<?php if($v['abolish_time'] > 0 || $v['status'] == 2): ?>
							<a href="javascript:void (0);" onclick="delTicket('<?php echo $v['id']; ?>')" >删除（禁用）</a>
						<?php endif; if($v['status'] == 1): ?>
							<a href="javascript:void (0);" class="check-ticket" data-ticket-id="<?php echo $v['id']; ?>">核销</a>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; endif; else: echo "" ;endif; ?>
			</tbody>
		</table>
        <div style="float: left;margin-left: 10px;" class="pagination"><?php echo $page; ?></div>
	</div>

	<div class="modal fade" id="checkModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="checkModalLabel">选择服务人员</h4>
				</div>

				<div class="modal-body recharge-items">
					<select name="worker" id="worker" class="form-control">
						<?php if(is_array($worker_list) || $worker_list instanceof \think\Collection || $worker_list instanceof \think\Paginator): if( count($worker_list)==0 ) : echo "" ;else: foreach($worker_list as $key=>$vo): ?>
							<option value="<?php echo $vo['workid']; ?>"><?php echo $vo['workid']; ?> -- <?php echo $vo['name']; ?></option>
						<?php endforeach; endif; else: echo "" ;endif; ?>
					</select>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-primary" id="check_ticket_save">确认核销</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
				</div>
			</div>
		</div>
	</div>


	<script src="__STATIC__/js/admin.js"></script>
<script type="text/javascript">
    /*编辑*/
    function admin_edit(title,url){
        layer_show(title,url);
    }

    function delTicket(ticket_id){
    	layer.confirm("确认删除？", { title: "确认" }, function (index) {
			$.post("<?php echo url('Member/delUserTicket'); ?>", { 'user_ticket_id':ticket_id}, function (res) {
				if(res.code === 200){
					layer.msg(res.msg,{'icon':1,time:2000},function(){
						window.location.reload();
					});
				}else {
					layer.msg(res.msg,{'icon':2,time:2000});
				}
			});
		});
	}

    $('.check-ticket').click(function(){
        let ticket_id = $(this).attr('data-ticket-id');
		$('#checkModal').modal('show');
		$('#check_ticket_save').click(function(){
			let workid = $('#worker option:selected').val();
            layer.confirm("确认核销？", { title: "核销确认" }, function (index) {
                layer.close(index);
                $.post("<?php echo url('Member/checkTicket'); ?>", { 'id':ticket_id,'workid':workid}, function (res) {
					if(res.code === 200){
						layer.msg(res.msg,{'icon':1,time:2000},function(){
					    	$('#checkModal').modal('hide');
					    	window.location.reload();
					    });
					}else {
                        layer.msg(res.msg,{'icon':2});
                    }
                });
            });
        })
	})
</script>
</body>
</html>
