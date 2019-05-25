<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:52:"themes/admin_simpleboot3/admin\level\level_list.html";i:1554867174;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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
			<li class="active"><a href="<?php echo url('Level/Index'); ?>">等级列表</a></li>
			<li><a href="<?php echo url('Level/Add'); ?>">等级添加</a></li>
		</ul>
		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th>等级ID</th>
					<th>等级名称</th>
					<th width="130">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if(is_array($lists) || $lists instanceof \think\Collection || $lists instanceof \think\Paginator): if( count($lists)==0 ) : echo "" ;else: foreach($lists as $key=>$vo): ?>
					<tr>
						<td><?php echo $vo['id']; ?></td>
						<td><?php echo $vo['level_name']; ?></td>
						<td>
							<a href="<?php echo url('Level/Delete',array('id'=>$vo['id'])); ?>" class="js-ajax-delete">删除</a>
						</td>
					</tr>
				<?php endforeach; endif; else: echo "" ;endif; ?>
			</tbody>
		</table>

	</div>
	<script src="__STATIC__/js/admin.js"></script>
	<script>
        $(function(){
            $('table td').not("td:first").dblclick(function(){
                var td = $(this);
                var id = $(this).parent().children('td').first().text();
                var old_value = $(this).text();
                if(!$(this).is('.input')){
                    $(this).addClass('input').html('<input type="text" value="'+ $(this).text() +'" />').find('input').focus().blur(function(){
                        var new_value = $(this).val();
                        if(new_value !== old_value){
                            // $(this).parent().removeClass('input').html($(this).val() || 0);return;
                            $.ajax({
								type: 'post',
								url: "<?php echo url('Level/Edit'); ?>",
								data: {
									id: id,
									value: new_value
								},
								success: function (msg) {
									if (msg.code==1) {
                                        layer.open({
                                            content: msg.msg,
                                            end: function(){
                                        		td.removeClass('input').html(new_value);
                                            }
                                        });
                                    } else{
                                        layer.open({
                                            content: msg.msg,
                                            end: function(){
                                                td.removeClass('input').html(old_value);
                                            }
                                        });
									}
								}
							})
						}
                    });
                }
            }).hover(function(){
                $(this).addClass('hover');
            },function(){
                $(this).removeClass('hover');
            });
        });

    function add_recharge()
    {
    	var price = $('#price').val();
    	var status = $('input[name="status"]:checked').val();
    	if (!isNum(price)) {
    		layer.msg('必须是数字',{icon: 0, time: 2000});
    		return false;
    	}else if(price <= 0){
    		layer.msg('不能小于0',{icon: 0, time: 2000});
    		return false;
    	}
    	// var shop_id =
    	$.post('<?php echo url('shop/addRecharge'); ?>',{price:price,status:status},function(res){
    		if (res.code == 200) {
    			layer.msg(res.msg,{icon: 1, time: 2000},function(){
    				window.location.reload();
    			});
    		}else{
    			layer.msg(res.msg,{icon: 0, time: 2000});
    		}
    	})
    }

    function dels(obj,id){
    	layer.confirm('确认要删除？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.post('<?php echo url('shop/delRecharge'); ?>',{id:id},function(res){
	    		if (res.code == 200) {
	    			var link = obj.parents("tr");
	                link.remove();
	    			layer.msg(res.msg,{icon: 1, time: 2000});
	    		}else{
	    			layer.msg(res.msg,{icon: 0, time: 2000});
	    		}
	    	})
        });
    }

    function isNum(num){
    	if(num === "" || num ==null){
	        return false;
	    }
	    if(!isNaN(num)){
	        return true;
	    }else{
	        return false;
	    }
    }
	</script>

</body>
</html>
