<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:60:"themes/admin_simpleboot3/admin\member\score_rule_update.html";i:1554867176;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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
		<form action="<?php echo url('Member/updateScoreRule'); ?>" method="post" id="form-edit">
			<input type="hidden" name="id" value="<?php echo (isset($info['id']) && ($info['id'] !== '')?$info['id']:0); ?>">
			<div class="form-group">
				<label for="ratio">积分获取比例</label>
				<input type="text" name="ratio" class="form-control" value="<?php echo (isset($info['ratio']) && ($info['ratio'] !== '')?$info['ratio']:1.0); ?>" id="ratio" placeholder="">
			</div>
			<div class="form-group">
				<label for="level">会员等级</label>
				<select class="form-control" name="level_id">
                    <option value="">请选择</option>
                    <?php foreach($level_list as $v): ?>
                        <option <?php if(isset($info['id']) && $info['level_id'] == $v['id']): ?>selected<?php endif; ?> value="<?php echo $v->id; ?>"><?php echo $v->level_name; ?></option>
                    <?php endforeach; ?>
                </select>
			</div>
            <div>
                <input type="radio" name="type" <?php if(isset($info['id']) && $info['type'] == 1): ?>checked<?php endif; ?> value="1">
                <label>购买商品</label>
                <input type="radio" name="type" <?php if(isset($info['id']) && $info['type'] == 2): ?>checked<?php endif; ?> value="2" >
                <label>消费服务</label>
                <input type="radio" name="type" <?php if(isset($info['id']) && $info['type'] == 3): ?>checked<?php endif; ?> value="3" >
                <label>通用</label>
            </div>
			<button type="submit" class="btn btn-default">提交</button>
		</form>
	</div>
	<script src="__STATIC__/js/admin.js"></script>
	<script src="/plugins/h-ui/js/H-ui.min.js"></script>
	<script type="text/javascript">

        $(function () {
            /*管理员-编辑*/
            function admin_edit(title, url, w, h) {
                layer_show(title, url, w, h);
            }

            $('#form-edit').bind('submit', function () {
                $("#form-edit").ajaxSubmit(function (result) {
                    if (result.code == 200) {

                        layer.msg(result.msg, {
                            icon: 1,
                            time: 1500 //2秒关闭（如果不配置，默认是3秒）
                        }, function () {
                            window.parent.location.reload();
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index);
                        });
                    } else {
                        layer.msg(result.msg, {
                            icon: 0,
                            time: 1500 //2秒关闭（如果不配置，默认是3秒）
                        });
                    }
                });
                return false;
            });
        });
	</script>
</body>
</html>
