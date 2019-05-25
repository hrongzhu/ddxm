<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:46:"themes/admin_simpleboot3/admin\lv\lv_list.html";i:1554867202;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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
			<li  class="active"><a href="<?php echo url('Lv/lv_list'); ?>">税率模板列表</a></li>
			<li><a href="<?php echo url('Lv/add'); ?>">税率模板添加</a></li>
		</ul>
        <!-- <form class="well form-inline margin-top-20" method="post" action="<?php echo url('Lv/list'); ?>" id="from">
            商品名称:
            <input type="text" class="form-control" name="user_login" style="width: 120px;" value="<?php echo input('request.user_login/s',''); ?>" placeholder="请输入<?php echo lang('USERNAME'); ?>">
            邮箱:
            <input type="text" class="form-control" name="user_email" style="width: 120px;" value="<?php echo input('request.user_email/s',''); ?>" placeholder="请输入<?php echo lang('EMAIL'); ?>">
            <input type="submit" class="btn btn-primary" value="搜索" />
            <a class="btn btn-danger" href="<?php echo url('User/index'); ?>">清空</a>
        </form> -->
		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th width="50">ID</th>
					<th>模板名称</th>
					<th>税率</th>
					<th>备注</th>
					<th>状态</th>
					<th>添加时间</th>
					<th width="130">操作</th>
				</tr>
			</thead>
			<tbody>
			<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $key=>$vo): ?>
				<tr>
					<td><?php echo $vo['id']; ?></td>
					<td><?php echo $vo['title']; ?></td>
					<td><?php echo $vo['lv']; ?></td>
					<td><?php echo $vo['subtitle']; ?></td>
                    <?php if($vo['status'] == 1): ?>
					<td>启用</td>
					<?php else: ?>
					<td>禁用</td>
					<?php endif; ?>
					<th><?php echo $vo['addtime']; ?></th>
					<td>
					<?php if($vo['status'] == 1): ?>
					<a href="<?php echo url('Lv/off',array('id'=>$vo['id'])); ?>" class="js-ajax-dialog-btn">禁用</a>
					<?php else: ?>
					<a href="<?php echo url('Lv/on',array('id'=>$vo['id'])); ?>" class="js-ajax-dialog-btn">启用</a>
					<?php endif; ?>
					<a href="<?php echo url('Lv/delete',array('id'=>$vo['id'])); ?>" class="js-ajax-delete">删除</a>
					<a href="<?php echo url('Lv/edit',array('id'=>$vo['id'])); ?>">编辑</a>
					</td>
				</tr>
				<?php endforeach; endif; else: echo "" ;endif; ?>
			</tbody>
		</table>
		<div class="pagination"><?php echo $page; ?></div>
	</div>
	<script src="__STATIC__/js/admin.js"></script>
	<script type="text/javascript">
	    $(function(){
	    	$('#shopchange').change(function(){
	    		$('#from').submit();
	    	})
	    })
	</script>
</body>
</html>