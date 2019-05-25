<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:58:"themes/admin_simpleboot3/admin\member\score_rule_list.html";i:1554867177;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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
			<li class="active"><a href="<?php echo url('Member/scoreRuleList'); ?>">积分规则列表</a></li>
			<li><a href="javascript:void (0);" onclick="admin_edit('添加规则','<?php echo url('Member/updateScoreRule'); ?>')">添加积分规则</a></li>
		</ul>
        <form class="well form-inline margin-top-0" method="get" action="<?php echo url('Member/scoreRuleList'); ?>">
            <input type="submit" class="btn btn-primary" value="搜索" />
            <a class="btn btn-danger" href="<?php echo url('Member/scoreRuleList'); ?>">清空</a>
        </form>
		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th width="50">ID</th>
					<th >获取比例</th>
					<th >会员等级</th>
					<th >类型</th>
					<th >时间</th>
					<th >操作</th>
				</tr>
			</thead>
			<tbody>
			<?php if(is_array($rule_list) || $rule_list instanceof \think\Collection || $rule_list instanceof \think\Paginator): if( count($rule_list)==0 ) : echo "" ;else: foreach($rule_list as $key=>$v): ?>
				<tr>
					<td><?php echo $v['id']; ?></td>
					<td><?php echo $v['ratio']; ?></td>
					<td><?php echo $v['level_name']; ?></td>
					<td><?php echo $v['type_name']; ?></td>
					<td><?php echo $v['addtime']; ?></td>
					<td>
						<a href="javascript:void (0);" onclick="admin_edit('查看详情', '<?php echo url('Member/updateScoreRule', ['id'=>$v['id']]); ?>')">编辑</a>|
						<a href="javascript:void (0);" onclick="delSR('<?php echo url('Member/delScoreRule'); ?>',<?php echo $v['id']; ?>)">删除</a>
					</td>
				</tr>
				<?php endforeach; endif; else: echo "" ;endif; ?>
			</tbody>
		</table>
		<!-- <div class="pagination"><?php echo $page; ?></div> -->
	</div>
	<script src="__STATIC__/js/admin.js"></script>
	<script type="text/javascript">

        /*编辑*/
        function admin_edit(title,url){
            layer_show(title,url,1000);
        }

    // 重置密码
    function delSR(url,id) {
        layer.confirm('确认要删除？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.post(url, { id: id}, function(res){
                if (res.code == 200) {
                    layer.msg(res.msg, {icon: 1,time:1000},function () {
                        location.reload();
                    });
				}else {
                    layer.msg(res.msg, {icon: 0,time:1000});
				}
            });
        });
    }

	</script>
</body>
</html>
