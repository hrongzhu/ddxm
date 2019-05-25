<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:50:"themes/admin_simpleboot3/admin\item\type_list.html";i:1554867193;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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
			<li class="active"><a href="<?php echo url('Item/type_list'); ?>">分类列表</a></li>
			<li><a href="<?php echo url('Item/type_add'); ?>">分类添加</a></li>
		</ul>
        <!-- <form class="well form-inline margin-top-20" method="post" action="<?php echo url('User/index'); ?>">
            用户名:
            <input type="text" class="form-control" name="user_login" style="width: 120px;" value="<?php echo input('request.user_login/s',''); ?>" placeholder="请输入<?php echo lang('USERNAME'); ?>">
            邮箱:
            <input type="text" class="form-control" name="user_email" style="width: 120px;" value="<?php echo input('request.user_email/s',''); ?>" placeholder="请输入<?php echo lang('EMAIL'); ?>">
            <input type="submit" class="btn btn-primary" value="搜索" />
            <a class="btn btn-danger" href="<?php echo url('User/index'); ?>">清空</a>
        </form> -->
		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th>伸缩</th>
					<th>分类名称</th>
					<th>ID</th>
					<th>上级ID</th>
					<th>logo-URL</th>
					<th>状态</th>
					<th width="160">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if(is_array($type_list) || $type_list instanceof \think\Collection || $type_list instanceof \think\Paginator): if( count($type_list)==0 ) : echo "" ;else: foreach($type_list as $key=>$vo): ?>
				<tr>
					<td><span class="open" data="<?php echo $vo['id']; ?>" pid="<?php echo $vo['pid']; ?>">→</span></td>
					<td><?php echo $vo['count']; ?><?php echo $vo['cname']; ?></td>
					<td><?php echo $vo['id']; ?></td>
					<td><?php echo $vo['pid']; ?></td>
					<td><img src="http://upload.ddxm661.com<?php echo $vo['thumb']; ?>" style="width: 60px;height: 60px;"></td>
					<?php if($vo['status'] == 1): ?>
					<td>正常</td>
					<?php else: ?>
					<td>禁用</td>
					<?php endif; ?>
					<td>
					<a href="<?php echo url('Item/type_delete',array('id'=>$vo['id'])); ?>" class="js-ajax-delete">删除</a>
					<a href="<?php echo url('Item/type_edit',array('id'=>$vo['id'])); ?>">编辑</a>
					</td>
				</tr>
				<?php if(is_array($vo['children']) || $vo['children'] instanceof \think\Collection || $vo['children'] instanceof \think\Paginator): if( count($vo['children'])==0 ) : echo "" ;else: foreach($vo['children'] as $key=>$value): if($value['pid'] == $vo['id']): ?>
				    <tr data="<?php echo $value['id']; ?>" pid="<?php echo $value['pid']; ?>" style="display: none;">
				    <td><?php echo $value['count']; ?></td>
				    <td><?php echo $value['count']; ?><?php echo $value['cname']; ?></td>
					<td><?php echo $value['id']; ?></td>
					<td><?php echo $value['pid']; ?></td>
					<td><img src="http://upload.ddxm661.com<?php echo $value['thumb']; ?>" style="width: 60px;height: 60px;"></td>
					<?php if($value['status'] == 1): ?>
					<td>正常</td>
					<?php else: ?>
					<td>禁用</td>
					<?php endif; ?>
					<td>
					<a href="<?php echo url('Item/type_delete',array('id'=>$value['id'])); ?>" class="js-ajax-delete">删除</a>
					<a href="<?php echo url('Item/type_edit',array('id'=>$value['id'])); ?>">编辑</a>
					</td>
				</tr>
				<?php endif; endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
			</tbody>
		</table>
		<div class="pagination"></div>
	</div>
	<script src="__STATIC__/js/admin.js"></script>
	<script type="text/javascript">
		$('.open').click(function() {
		    // 首先需要判断一下目前的状态是出于加号的状态还是减号的状态
		    var status = $(this).text();

		    var id = $(this).attr('data');

		    if(status == '→') {
		     $(this).text('↓');
             $('tr[pid='+id+']').show();
			} else {
			   $(this).text('→');
			   $('tr[pid='+id+']').hide();
			}
		});

	</script>
</body>
</html>
