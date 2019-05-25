<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:56:"themes/admin_simpleboot3/admin\finance\finance_shop.html";i:1554867194;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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
			<li  class="active"><a href="<?php echo url('Finance/finance_shop'); ?>">门店日报</a></li>
		</ul>
        <form class="well form-inline margin-top-20" method="post" action="<?php echo url('Finance/finance_shop'); ?>" id="from">
            <select name="shop_id" class="form-control" id="shopchange">
                <?php if(is_array($shop_list) || $shop_list instanceof \think\Collection || $shop_list instanceof \think\Paginator): if( count($shop_list)==0 ) : echo "" ;else: foreach($shop_list as $key=>$v): if($v['id'] == $dian_id): ?>
                	     <option value="<?php echo $v['id']; ?>" selected="selected"><?php echo $v['name']; ?></option>
                	     <?php else: ?>
                	     <option value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                	<?php endif; endforeach; endif; else: echo "" ;endif; ?>
            </select>
            时间:
            <input type="text" class="js-bootstrap-date form-control" id="input-name" name="time" value="<?php echo $times; ?>">
            <input type="submit" id="submit" value="搜索">
        </form>
		<table class="table table-hover table-bordered">
			<h4>门店收入汇总</h4>
			<thead>
				<tr>
					<th>收入类型</th>
					<th>现金收款</th>
					<th>卡片扣款</th>
					<th>合计金额</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>水育</td>
					<td><?php echo $finance_shop['wechat_swim']; ?></td>
					<td><?php echo $finance_shop['card_swim']; ?></td>
					<td><?php echo $finance_shop['swim_num']; ?></td>
				</tr>
				<tr>
					<td>推拿</td>
					<td><?php echo $finance_shop['wechat_tuina']; ?></td>
					<td><?php echo $finance_shop['card_tuina']; ?></td>
					<td><?php echo $finance_shop['tuina_num']; ?></td>
				</tr>
				<tr>
					<td>商品</td>
					<td><?php echo $finance_shop['wechat_goods']; ?></td>
					<td><?php echo $finance_shop['card_goods']; ?></td>
					<td><?php echo $finance_shop['goods_num']; ?></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td>合计金额:<span style="color: red;"><?php echo $finance_shop['shou_num']; ?></span></td>
				</tr>
			</tbody>
		</table>
		<table class="table table-hover table-bordered">
			<h4>门店支出汇总</h4>
			<thead>
				<tr>
					<th>支出类型</th>
					<th>支出原因</th>
					<th>合计金额</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>商品成本</td>
					<td>商品进货价格成本</td>
					<td><?php echo $finance_shop['goods_oprice']; ?></td>
				</tr>
				<?php if(is_array($finance_shop['shop_zhichu']) || $finance_shop['shop_zhichu'] instanceof \think\Collection || $finance_shop['shop_zhichu'] instanceof \think\Paginator): if( count($finance_shop['shop_zhichu'])==0 ) : echo "" ;else: foreach($finance_shop['shop_zhichu'] as $key=>$vo): ?>
				<tr>
					<td>其他</td>
					<td><?php echo $vo['content']; ?></td>
					<td><?php echo $vo['price']; ?></td>
					<?php if($is_del == 1): ?>
					    <td><a href="<?php echo url('Finance/finance_delete',array('id'=>$vo['id'])); ?>" class="js-ajax-delete">删除</a></td>
				    <?php endif; ?>
				</tr>
			    <?php endforeach; endif; else: echo "" ;endif; ?>
				<tr>
					<td><a href="javascript:void(0)" class="add">支出添加</a> <a href="javascript:void(0)" class="finance_add">确认添加</a></td>
					<td></td>
					<td>合计金额:<span style="color: red;"><?php echo $finance_shop['zhichunum']; ?></span></td>
				</tr>
			</tbody>
		</table>
		<table class="table table-hover table-bordered">
			<h4>门店售卡汇总</h4>
			<thead>
				<tr>
					<th>卡片类型</th>
					<th>卡片售价</th>
					<th>销售数量</th>
					<th>销售金额</th>
				</tr>
			</thead>
			<tbody>
				<?php if(is_array($finance_shop['cardzong']) || $finance_shop['cardzong'] instanceof \think\Collection || $finance_shop['cardzong'] instanceof \think\Paginator): if( count($finance_shop['cardzong'])==0 ) : echo "" ;else: foreach($finance_shop['cardzong'] as $key=>$vo): ?>
				<tr>
					<td><?php echo $vo['title']; ?></td>
					<td><?php echo $vo['price']; ?></td>
					<td><?php echo $vo['num']; ?></td>
					<td><?php echo $vo['pricesum']; ?></td>
				</tr>
			    <?php endforeach; endif; else: echo "" ;endif; ?>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td>合计金额:<span style="color: red;"><?php echo $finance_shop['countnum']; ?></span></td>
				</tr>
			</tbody>
		</table>
	</div>
	<script src="__STATIC__/js/admin.js"></script>
	<script type="text/javascript">
		//添加和删除事件
	    $(function(){
	    	$('.add').click(function(){
	    		var  html="<tr><td>其他</td><td><input type='text' name='content' class='content' placeholder='请输入支出理由'></td><td><input type='text' name='price' class='price' placeholder='请输入支出金额'></td></tr>";
	    		$(this).parent().parent().before(html);
	    	})
	    })
	    //添加操作
	    $(function(){
	    	$('.finance_add').click(function(){

                var shop_id = $("#shopchange").val();
	    		var content = $(this).parent().parent().prev().children().children('.content').val();
	    		var price   = $(this).parent().parent().prev().children().children('.price').val();
	    		var time   = $('#input-name').val();
                console.log(content);
                console.log(price);
                console.log(shop_id);
	    		$.ajax({
		    			url:"<?php echo url('Finance/finance_add'); ?>",
		    			type:"post",
		    			dataType: 'text',
	                    data: {shop_id: shop_id,content: content,price: price,time:time},
	                    success:function(data){
	                    	if(data==1){
	                    		alert("添加成功")
	                    		window.location.reload();
	                    	}else{
	                    		alert("添加失败")
	                    		window.location.reload();
	                    	}
	                    }
		    		})
	    	})
	    })
	</script>
</body>
</html>
