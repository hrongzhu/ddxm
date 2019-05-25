<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:50:"themes/admin_simpleboot3/admin\item\item_list.html";i:1554867192;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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
			<li class="active"><a href="<?php echo url('Item/item_list'); ?>">商品列表</a></li>
			<li><a href="<?php echo url('Item/item_add'); ?>">商品添加</a></li>
		</ul>
        <form class="well form-inline margin-top-0" method="get" action="<?php echo url('Item/item_list'); ?>">
            <input type="text" class="form-control" name="search" style="width: 120px;" value="<?php echo input('request.search/s',''); ?>" placeholder="商品名称">
            <input type="text" class="form-control" name="bar_code" style="width: 120px;" value="<?php echo input('request.bar_code/s',''); ?>" placeholder="商品条码">
			<select class="form-control" id="f_cate" onchange="get_s_cate($(this))" name="f_cate">
		        <option value="-1">选择一级分类</option>
		        <?php foreach($f_list as $v): ?>
		            <option <?php if($f_cate == $v['id']): ?>selected<?php endif; ?> value="<?php echo $v['id']; ?>"><?php echo $v['cname']; ?></option>
		        <?php endforeach; ?>
		    </select>
            <select class="form-control selectboy" name="s_cate" id="s_cate">
                <option value="">选择二级分类</option>
			</select>
			&emsp;是否显示已删除商品:
			<select class="form-control" name="show_del" id="show_del">
				<option <?php if($show_del == 0): ?>selected<?php endif; ?> value="0">否</option>
				<option <?php if($show_del == 1): ?>selected<?php endif; ?> value="1">是</option>
			</select>
            <input type="submit" class="btn btn-primary" value="搜索" />
        </form>
		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th width="50">SKUID</th>
					<th>商品名称</th>
					<th>商品库</th>
					<th>商品分区</th>
					<th>商品分类</th>
					<th>线上状态</th>
					<th>价格</th>
					<th>条形码</th>
					<th width="180">操作</th>
				</tr>
			</thead>
			<tbody>
			<?php if(is_array($iteminfo) || $iteminfo instanceof \think\Collection || $iteminfo instanceof \think\Paginator): if( count($iteminfo)==0 ) : echo "" ;else: foreach($iteminfo as $key=>$vo): ?>
				<tr style="height: 45px;">
					<td><?php echo $vo['id']; ?></td>
					<td><?php echo $vo['title']; ?></td>
					<?php if($vo['item_type'] == 1): ?>
						<td>线上商品</td>
						<?php elseif($vo['item_type'] == 2): ?>
						<td>门店商品</td>
						<?php elseif($vo['item_type'] == 3): ?>
						<td>线上、门店商品</td>
					<?php endif; if($vo['type_id'] == 2): ?>
					<td>国内商品</td>
					<?php elseif($vo['type_id'] == 3): ?>
					<td>跨境商品</td>
					<?php elseif($vo['type_id'] == 9): ?>
					<td>熊猫自营</td>
					<?php elseif($vo['type_id'] == 0): ?>
					<td>--</td>
					<?php endif; ?>
					<td><?php echo $vo['cname']; ?></td>
					<?php if($vo['status'] == 1): ?>
					<td>上架</td>
					<?php elseif($vo['status'] == 0): ?>
					<td>下架</td>
					<?php else: ?>
					<td>已删除</td>
					<?php endif; ?>
					<td><?php echo $vo['price']; ?></td>
					<td><?php echo $vo['bar_code']; ?></td>
					<td>
					<?php if($vo['status'] != 3): ?>
						<a href="javascript:void (0);" onclick="delete_item('<?php echo url('Item/item_delete',array('id'=>$vo['id'])); ?>',$(this))">删除</a>
					<?php endif; ?>
					<a href="javascript:parent.openIframeLayer('<?php echo url('Item/item_edit',array('id'=>$vo['id'])); ?>','编辑',{});">编辑</a>
					</td>
				</tr>
				<?php endforeach; endif; else: echo "" ;endif; ?>
			</tbody>
		</table>
		<div class="pagination"><?php echo $page; ?></div>
	</div>
	<script src="__STATIC__/js/admin.js"></script>
	<script>
	    $(document).on('click','.ji',function(){
	         old_val=$(this).html();
	        $(this).parent().html("<input class='gai' type=\'text\' value="+old_val+">");
	        $(document).on('blur','.gai',function(){
	        var obj=$(this);
	        var id=$(this).parent().attr('date'); //获取要修改内容的id
	        var val=$(this).val(); //获取修改后的值
	        console.log(id);
	        console.log(val);
	          $.ajax({
	            type:'post',
	            url:"<?php echo url('Item/attr_bar'); ?>",
	            data:{
	                id:id,
	                bar_code:val
	            },
	            success:function(msg){
	                if(msg == 1){
	                    obj.parent().html("<span class='ji'>"+val+"</span>");
	                }else if(msg == 0){
	                    obj.parent().html("<span class='ji'>"+old_val+"</span>");
	                }else if(msg == 2){
	                	//alert("条形码格式不正确，请重新输入");
	                	art.dialog({
                                    content: "条形码格式不正确，请重新输入",
                                    icon: 'warning',
                                    ok: function () {
                                        this.title("条形码格式不正确，请重新输入");
                                        return true;
                                    }
                                });
	                }

	            }
	       })
	    })
	})
	$(document).on('blur','.gai',function(){
	        var obj=$(this);
	        var id=$(this).parent().attr('date'); //获取要修改内容的id
	        var val=$(this).val(); //获取修改后的值
	        console.log(id);
	        console.log(val);
	          $.ajax({
	            type:'post',
	            url:"<?php echo url('Item/attr_bar'); ?>",
	            data:{
	                id:id,
	                bar_code:val
	            },
	            success:function(msg){
	                if(msg == 1){
	                    obj.parent().html("<span class='ji'>"+val+"</span>");
	                }else if(msg == 0){
	                    obj.parent().html("<span class='ji'>"+old_val+"</span>");
	                }else if(msg == 2){
	                	//alert("条形码格式不正确，请重新输入");
	                	art.dialog({
                                    content: "条形码格式不正确，请重新输入",
                                    icon: 'warning',
                                    ok: function () {
                                        this.title("条形码格式不正确，请重新输入");
                                        return true;
                                    }
                                });
	                }else if(msg == 3){
	                	//alert("条形码格式不正确，请重新输入");
	                	art.dialog({
                                    content: "条形码已存在",
                                    icon: 'warning',
                                    ok: function () {
                                        this.title("条形码已存在");
                                        return true;
                                    }
                                });
	                }

	            }
	       })
	    })
	    $(document).on('click','.jing',function(){
	         old_val=$(this).html();
	        $(this).parent().html("<input class='uid' type=\'text\' value="+old_val+">");
	        $(document).on('blur','.uid',function(){
	        var obj=$(this);
	        var id=$(this).parent().attr('date'); //获取要修改内容的id
	        var val=$(this).val(); //获取修改后的值
	          $.ajax({
	            type:'post',
	            url:"<?php echo url('Item/attr_jd'); ?>",
	            data:{
	                id:id,
	                jing_code:val
	            },
	            success:function(msg){
	            	console.log(msg);
	                if(msg == 1){
	                    obj.parent().html("<span class='jing'>"+val+"</span>");
	                }else if(msg == 0){
	                    obj.parent().html("<span class='jing'>"+old_val+"</span>");
	                }else if(msg == 2){
	                	//alert("条形码格式不正确，请重新输入");
	                	art.dialog({
                                    content: "条形码格式不正确，请重新输入",
                                    icon: 'warning',
                                    ok: function () {
                                        this.title("条形码格式不正确，请重新输入");
                                        return true;
                                    }
                                });
	                }

	            }
	       })
	    })
	})
	$(document).on('blur','.jing',function(){
	        var obj=$(this);
	        var id=$(this).parent().attr('date'); //获取要修改内容的id
	        var val=$(this).val(); //获取修改后的值
	          $.ajax({
	            type:'post',
	            url:"<?php echo url('Item/attr_jd'); ?>",
	            data:{
	                id:id,
	                jing_code:val
	            },
	            success:function(msg){
	                if(msg == 1){
	                    obj.parent().html("<span class='jing'>"+val+"</span>");
	                }else if(msg == 0){
	                    obj.parent().html("<span class='jing'>"+old_val+"</span>");
	                }else if(msg == 2){
	                	//alert("条形码格式不正确，请重新输入");
	                	art.dialog({
                                    content: "条形码格式不正确，请重新输入",
                                    icon: 'warning',
                                    ok: function () {
                                        this.title("条形码格式不正确，请重新输入");
                                        return true;
                                    }
                                });
	                }else if(msg == 3){
	                	layer.msg('条形码已存在',{icon: 2,time:1000})
	                	// alert("条形码格式不正确，请重新输入");
	                	// art.dialog({
                  //                   content: "条形码已存在",
                  //                   icon: 'warning',
                  //                   ok: function () {
                  //                       this.title("条形码已存在");
                  //                       return true;
                  //                   }
                  //               });
	                }

	            }
	       })
	    })
</script>
<script type="text/javascript" >
	$(function(){
		var obj = $('#f_cate');
		get_s_cate(obj);
	})

function get_s_cate(obj)
{
	var pid = obj.val();
	var url = "<?php echo url('Item/itemTlians'); ?>";
	$.post(url,{pid:pid},function(res){
		$shtml = '';
    	if (res.code === 200) {
            $.each(res.data, function(k, v) {
                $shtml += `<option value="${v.id}">${v.cname}</option>`;
            });
            $('#s_cate').html($shtml);
        }else {
        	$shtml += `<option value="">请选择分类</option>`;
        	$('#s_cate').html($shtml);
        }
	},'json');
}
//删除商品
function delete_item(urls,obj)
{
	layer.confirm('确认要删除？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.get(urls, {}, function(res){
                if (res.code == 200) {
                    layer.msg(res.msg, {icon: 1,time:1000},function () {
                        window.location.reload();
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
