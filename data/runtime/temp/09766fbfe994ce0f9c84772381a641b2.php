<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:54:"themes/admin_simpleboot3/admin\card\buy_card_list.html";i:1554867188;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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

<style type="text/css">
	#showDetailModal .modal-dialog { width: 800px!important; }
	#showDetailModal .modal-body { padding:  40px 34px; }
</style>
</head>
<body>
	<div class="wrap js-check-wrap">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#">服务卡列表</a></li>
		</ul>
        <form class="well form-inline margin-top-0" method="get" action="<?php echo url('card/userBuyServiceCardList'); ?>">
    		<input type="text" class="form-control" name="name" style="width: 120px;" value="<?php echo input('request.name/s',''); ?>" placeholder="服务卡名">
    		<input type="text" class="form-control" name="nickname" style="width: 120px;" value="<?php echo input('request.nickname/s',''); ?>" placeholder="会员昵称">
    		<input type="text" class="form-control" name="mobile" style="width: 120px;" value="<?php echo input('request.mobile/s',''); ?>" placeholder="会员手机号">
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
            <select class="form-control" name="status">
    			<option value="1000" >全部状态</option>
    			<option value="0" <?php if($status == 0): ?>selected<?php endif; ?>>禁用</option>
                <option value="1" <?php if($status == 1): ?>selected<?php endif; ?>>未激活</option>
                <option value="2" <?php if($status == 2): ?>selected<?php endif; ?>>正常</option>
    			<option value="3" <?php if($status == 3): ?>selected<?php endif; ?>>已失效</option>
    		</select>
    		<input type="submit" class="btn btn-primary" value="搜索" />
    		<a class="btn btn-danger" href="<?php echo url('card/userBuyServiceCardList'); ?>">清空</a>
    	</form>
		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th width="50">卡ID</th>
					<th >服务卡名称</th>
					<th >卡类型</th>
					<th >服务项目</th>
					<th >剩余次数</th>
					<th >支付方式</th>
					<th >卡片售价</th>
					<th >卡片时长</th>
					<th >会员名</th>
					<th >会员电话</th>
					<th >所属店铺</th>
					<th >购卡时间</th>
					<th >激活时间</th>
					<th >有效期至</th>
					<th >状态</th>
					<th >操作</th>
				</tr>
			</thead>
			<a href="javascript:parent.openIframeLayer('<?php echo url('card/useServiceCardRecord'); ?>','详情',{});" class="btn btn-primary" style="float: right;display:<?php echo $show_export==1?'display':'none'; ?>">导出所有消耗明细</a>
			<tbody>
			<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $key=>$v): ?>
				<tr>
					<td><?php echo $v['id']; ?></td>
					<td><?php echo $v['card_name']; ?></td>
					<td><?php echo $v['type']==1?"包月卡":"次卡"; ?></td>
					<td><?php echo (isset($v['service_name']) && ($v['service_name'] !== '')?$v['service_name']:""); ?></td>
					<td><?php echo (isset($v['residue_service']) && ($v['residue_service'] !== '')?$v['residue_service']:"包月卡不限次数"); ?></td>
					<td><?php echo $v['pay_way']; ?></td>
					<td>￥<?php echo $v['money']; ?></td>
					<td><?php echo $v['expire_month']; ?> 个月</td>
					<td><?php echo $v['nickname']; ?></td>
					<td><?php echo $v['mobile']; ?></td>
					<td><?php echo $v['shop_name']; ?></td>
					<td><?php echo !empty($v['paytime'])?date('Y-m-d H:i',$v['paytime']):'N/A'; ?></td>
					<td><?php echo !empty($v['active_time'])?date('Y-m-d H:i',$v['active_time']):'N/A'; ?></td>
					<td><?php echo !empty($v['active_time'])?date('Y-m-d H:i',$v['active_time'] + $v['expire_month'] * 2678400):'N/A'; ?></td>
					<td><?php if($v['status']==0): ?>已禁用<?php endif; if($v['status']==1): ?>未激活<?php endif; if($v['status']==2): ?>正常<?php endif; if($v['status']==3): ?>已失效<?php endif; ?></td>
					<td>
						<?php if($v['active_time'] == 0 || $v['status'] == 1): ?>
							<a href="javascript:void (0);" onclick="activeServiceCard(<?php echo $v['id']; ?>)">激活卡片</a>
						<?php endif; if($v['status'] == 2): ?>
							<a href="javascript:void (0);"  data-card-id="<?php echo $v['id']; ?>" class="check-card">耗卡</a>|
							<a href="javascript:void (0);"  onclick="delTicket('<?php echo $v['id']; ?>')">禁用</a>|
							<a href="javascript:void (0)" data-card-id="<?php echo $v['id']; ?>" class="show-detail">查看明细</a>
						<?php endif; if($v['status'] == 3): ?>
							<a href="javascript:void (0)" data-card-id="<?php echo $v['id']; ?>" class="show-detail">查看明细</a>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; endif; else: echo "" ;endif; ?>
			</tbody>
		</table>
        <div style="float: left;margin-left: 10px;" class="pagination"><?php echo $page; ?></div>
	</div>

	<!-- 销卡模态框 -->
	<div class="modal fade" id="checkModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="checkModalLabel">耗卡</h4>
				</div>

				<div class="modal-body">
					<lable>服务人员</lable>
					<div class=" worker-items">
						<select name="worker" id="worker" class="form-control">
							<?php if(is_array($worker_list) || $worker_list instanceof \think\Collection || $worker_list instanceof \think\Paginator): if( count($worker_list)==0 ) : echo "" ;else: foreach($worker_list as $key=>$vo): ?>
								<option value="<?php echo $vo['workid']; ?>"><?php echo $vo['workid']; ?> -- <?php echo $vo['name']; ?></option>
							<?php endforeach; endif; else: echo "" ;endif; ?>
						</select>
					</div>
					<lable>服务项目</lable>
					<div class="server-items">
						<select name="service_id" id="service_id" class="form-control">

						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" id="check_ticket_save">确认耗卡</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
				</div>
			</div>
		</div>
	</div>

	<!-- 服务明细模态框 -->
	<div class="modal fade" id="showDetailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">消耗明细</h4>
				</div>

				<div class="modal-body">
					<table class="table table-hover table-bordered">
						<thead>
							<tr>
								<th width="50">ID</th>
								<th >服务时间</th>
								<th >服务门店</th>
								<th >服务项目</th>
								<th >服务人员</th>
								<th >单次金额</th>
							</tr>
						</thead>
						<tbody id="list-record">

						</tbody>
					</table>
				</div>
				<div class="modal-footer">
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

	// 激活卡
	function activeServiceCard(card_id){
		layer.confirm("确认激活？", { title: "确认" }, function (index) {
			$.post("<?php echo url('card/activeServiceCard'); ?>", { 'card_id':card_id}, function (res) {
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

	//会员充值
    $('.show-detail').click(function(){
        var card_id = $(this).attr('data-card-id');
        $.post("<?php echo url('card/getServiceCardServerRecord'); ?>",{
            'id':card_id
        },function(re){
            if(re.code == 200){
                let table_target = '';
				$(re.data).each(function(i,v){
					console.log(v)
					table_target += `<tr>
						<td>${v.id}</td>
						<td>${v.yytime}</td>
						<td>${v.shop_name}</td>
						<td>${v.service_name}</td>
						<td>${v.name}</td>
						<td>${v.price}</td>
					</tr>`;
				});
				$('#list-record').html(table_target);
                $('#showDetailModal').modal('show');
            }else {
				let table_target = `<tr>
						<td colspan="6" class="text-center">没有耗卡记录</td>
					</tr>`;
				$('#list-record').html(table_target);
                $('#showDetailModal').modal('show');
            }
        })
    })

	//删除卡
	function delTicket(card_id){
		layer.confirm("禁用后卡片将不可用！确认禁用？", { title: "确认？" }, function (index) {
			$.post("<?php echo url('card/banServiceCard'); ?>", { 'card_id':card_id}, function (res) {
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

    $('.check-card').click(function(){
        let card_id = $(this).attr('data-card-id');
		$('#checkModal').modal('show');
		$.post("<?php echo url('card/getServiceCardServer'); ?>", { 'card_id':card_id},function(re){
			if(re.code === 200){
				let html = '';
				$.each(re.data,function(i,v){
					 html += `<option value="${v.s_id}">${v.sname}</option>`
				});
				$('#service_id').html(html);
			}else {
				layer.msg(re.msg,{'icon':2});
			}
		})
		$('#check_ticket_save').click(function(){
			let workid = $('#worker option:selected').val();
			let server_id = $('#service_id option:selected').val();
            layer.confirm("确认消费？", { title: "销卡确认" }, function (index) {
                layer.close(index);
                $.post("<?php echo url('card/checkServiceCard'); ?>", { 'id':card_id,'workid':workid,"service_id":server_id}, function (res) {
					$('#checkModal').modal('hide');
					if(res.code === 200){
						layer.msg(res.msg,{'icon':1,time:2000},function(){
					    	window.location.reload();
					    });
					}else {
                        layer.msg(res.msg,{'icon':2,time:3000});
                    }
                });
            });
        })
	})
</script>
</body>
</html>
