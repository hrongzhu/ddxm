<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:44:"themes/admin_simpleboot3/admin\user\add.html";i:1554867179;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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
	<div class="wrap">
		<ul class="nav nav-tabs">
			<li><a href="<?php echo url('user/index'); ?>"><?php echo lang('ADMIN_USER_INDEX'); ?></a></li>
			<li class="active"><a href="<?php echo url('user/add'); ?>"><?php echo lang('ADMIN_USER_ADD'); ?></a></li>
		</ul>
		<form method="post" class="form-horizontal js-ajax-form margin-top-20" action="<?php echo url('user/addpost'); ?>">
			<div class="form-group">
				<label for="input-user_login" class="col-sm-2 control-label"><span class="form-required">*</span><?php echo lang('USERNAME'); ?></label>
				<div class="col-md-6 col-sm-10">
					<input type="text" class="form-control" id="input-user_login" name="user_login">
				</div>
			</div>
			<div class="form-group">
				<label for="input-user_pass" class="col-sm-2 control-label"><span class="form-required">*</span><?php echo lang('PASSWORD'); ?></label>
				<div class="col-md-6 col-sm-10">
					<input type="text" class="form-control" id="input-user_pass" name="user_pass" placeholder="******">
				</div>
			</div>
			<div class="form-group">
				<label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span><?php echo lang('EMAIL'); ?></label>
				<div class="col-md-6 col-sm-10">
					<input type="text" class="form-control" id="input-user_email" name="user_email">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label"><span class="form-required">*</span><?php echo lang('用户类型'); ?></label>
				<div class="col-md-6 col-sm-10">
					<select class="form-control" name="user_type">
						<option  value="1">管理员</option>
						<option  value="2">用户</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="input-user_email" class="col-sm-2 control-label"><span class="form-required">*</span><?php echo lang('ROLE'); ?></label>
				<div class="col-md-6 col-sm-10">
					<?php if(is_array($roles) || $roles instanceof \think\Collection || $roles instanceof \think\Paginator): if( count($roles)==0 ) : echo "" ;else: foreach($roles as $key=>$vo): ?>
						<label class="checkbox-inline">
							<input value="<?php echo $vo['id']; ?>" type="checkbox" name="role_id[]" <?php if(cmf_get_current_admin_id() != 1 && $vo['id'] == 1): ?>disabled="true"<?php endif; ?>><?php echo $vo['name']; ?>
						</label>
					<?php endforeach; endif; else: echo "" ;endif; ?>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-primary js-ajax-submit"><?php echo lang('ADD'); ?></button>
				</div>
			</div>
		</form>
	</div>
	<script src="__STATIC__/js/admin.js"></script>
</body>
</html>