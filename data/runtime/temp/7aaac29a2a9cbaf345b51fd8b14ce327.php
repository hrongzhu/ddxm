<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:48:"themes/admin_simpleboot3/admin\coupon\index.html";i:1554867205;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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
			<li  class="active"><a href="<?php echo url('Coupon/index'); ?>">体验券列表</a></li>
		</ul>
		<form class="well form-inline margin-top-20" method="get" action="<?php echo url('Coupon/index'); ?>">
			昵称:
			<input type="text" class="form-control" name="nickname" style="width: 120px;" value="<?php echo $nickname; ?>">
			手机号:
            <input type="text" class="form-control" name="mobile" style="width: 120px;" value="<?php echo $mobile; ?>">
            所属店铺:
			<select class="form-control" name="shop_id">
                <option value="">请选择</option>
		        <?php foreach($shopDatas as $v): ?>
		            <option <?php if($shop_id == $v['id']): ?>selected<?php endif; ?> value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
		        <?php endforeach; ?>
		    </select>
            添加时间:
            <input type="text" style="width: 12%;" class="form-control" name="add-time" <?php if(isset($add_s)&&isset($add_e)): ?> value="<?php echo date("Y-m-d",$add_s); ?> ~ <?php echo date("Y-m-d",$add_e); ?>"<?php endif; ?> id="add-time" placeholder="选择添加时间">
            核销时间:
            <input type="text" style="width: 12%;" class="form-control" name="check-time" <?php if(isset($check_s)&&isset($check_e)): ?> value="<?php echo date("Y-m-d",$check_s); ?> ~ <?php echo date("Y-m-d",$check_e); ?>"<?php endif; ?> id="check-time" placeholder="选择核销时间">
            体验券状态:
            <select class="form-control" name="coupon-status">
                <option value="">请选择</option>
                <option value="1" <?php if(isset($coupon_status)&&$coupon_status == 1): ?>selected<?php endif; ?>>待核销</option>
                <option value="2" <?php if(isset($coupon_status)&&$coupon_status == 2): ?>selected<?php endif; ?>>已核销</option>
            </select>
            服务人员:
            <select class="form-control" name="worker-id">
                <option value="">请选择</option>
                <?php foreach($wokerDatas as $v): ?>
                    <option value="<?php echo $v['workid']; ?>" <?php if(isset($worker_id)&&$worker_id == $v['workid']): ?>selected<?php endif; ?>><?php echo $v['name']; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="submit" class="btn btn-primary" value="搜索" />
        </form>
		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th>体验券名称</th>
					<th>会员名</th>
					<th>会员电话</th>
					<th>所属门店</th>
					<th>体验券价格</th>
					<th>添加时间</th>
					<th>服务人员</th>
					<th>核销时间</th>
					<th width="130">操作</th>
				</tr>
			</thead>
			<tbody>
			<?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): if( count($data)==0 ) : echo "" ;else: foreach($data as $key=>$vo): ?>
				<tr>
					<td><?php echo $vo['title']; ?></td>
					<td><?php echo $vo['nickname']; ?></td>
					<td><?php echo $vo['mobile']; ?></td>
				    <td><?php echo $vo['name']; ?></td>
					<td><?php echo $vo['price']; ?></td>
					<td><?php echo date("Y-m-d H:i:s",$vo['addtime']); ?></td>
					<td>
						<?php if(empty($vo['worker_name'])): ?>
							——
							<?php else: ?>
							<?php echo $vo['worker_name']; endif; ?>
					</td>
					<td>
						<?php if(empty($vo['checktime'])): ?>
							——
							<?php else: ?>
							<?php echo date("Y-m-d H:i:s",$vo['checktime']); endif; ?>
					</td>
					<td>
						<?php if($vo['coupon_status']==2): ?>
							已核销
						<?php endif; if($vo['coupon_status']==1): ?>
							<a href="javascript:void (0);" class="check-coupon" data-shop-id="<?php echo $vo['shop_id']; ?>" data-coupon-id="<?php echo $vo['coupon_id']; ?>">核销</a>
						<?php endif; if($vo['coupon_status']==0): ?>
							已禁用
						<?php endif; ?>
					</td>
				</tr>
				<?php endforeach; endif; else: echo "" ;endif; ?>
			</tbody>
		</table>
		<div class="pagination"><?php echo $page; ?></div>
	</div>

	<!--核销模态框-->
	<div class="modal fade" id="checkModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="checkModalLabel">选择服务人员</h4>
				</div>

				<div class="modal-body recharge-items">
					<select name="worker" id="worker" class="form-control">
					</select>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-primary" id="check_coupon_save">确认核销</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
				</div>
			</div>
		</div>
	</div>



	<script src="__STATIC__/js/admin.js"></script>
    <script src="__STATIC__/js/laydate/laydate.js"></script>

    <script type="text/javascript">
        $('.check-coupon').click(function(){
            var coupon_id = $(this).attr('data-coupon-id');
            var shop_id = $(this).attr('data-shop-id');
            $.post("<?php echo url('Coupon/getWorkers'); ?>",{'shop_id':shop_id},function(res){
                if(res.code==1){
                    var htmls='';
                    $.each(res.data,function(k,v){
						htmls+='<option value="'+v.workid+'">'+v.shop_name+v.name+'</option>'
					})
                    $('#worker').html(htmls)
            		$('#checkModal').modal('show');
				}
			})

            $('#check_coupon_save').click(function(){
                layer.confirm("确认核销？", { title: "核销确认" }, function (index) {
                    layer.close(index);
                    $.post("<?php echo url('Coupon/check'); ?>", { 'id':coupon_id,'worker_id':$('#worker option:selected').val()}, function (res) {
                        if(res.code==1){
                            layer.msg(res.msg,{'icon':1},$('#checkModal').modal('hide'));
                            window.location.reload();
                        }else {
                            layer.msg(res.msg,{'icon':2});
                        }
                    });
                });
            })
        })
        laydate.render({
            elem: '#add-time'
            ,type: 'date',
            range:'~'
        });

        laydate.render({
            elem: '#check-time'
            ,type: 'date',
            range:'~'
        });
	</script>
</body>
</html>
