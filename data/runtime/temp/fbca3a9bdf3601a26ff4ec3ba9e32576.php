<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:47:"themes/admin_simpleboot3/admin\card\export.html";i:1554867190;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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
			<li  class="active">服务卡消耗总列表</li>
		</ul>
        <form class="well form-inline margin-top-20" method="post" action="<?php echo url('Card/useServiceCardRecord'); ?>" id="from">
            <input type="text" class="form-control" name="mobile" style="width: 120px;"
                   value="<?php echo input('request.mobile/s',''); ?>" placeholder="用户账号|电话">
            <input type="text" class="form-control" id="slay_date" name="start_time" style="width: 120px;"
                   value="<?php echo input('request.start_time/s',''); ?>" placeholder="开始时间">
            <input type="text" class="form-control" id="elay_date" name="end_time" style="width: 120px;"
                   value="<?php echo input('request.end_time/s',''); ?>" placeholder="结束时间">
            <select name="shopid" class="form-control" id="shopids" onchange="get_worker()">
                <option value="0">选择门店</option>
                <?php if(is_array($shop_list) || $shop_list instanceof \think\Collection || $shop_list instanceof \think\Paginator): if( count($shop_list)==0 ) : echo "" ;else: foreach($shop_list as $key=>$v): ?>
                    <option <?php echo $shop_id==$v['id']?"selected":""; ?> value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <select class="form-control" id="workid" name="workid">
                <option value="">服务人员</option>
            </select>
            <select class="form-control" id="card_type" name="card_type">
                <option value="0">服务卡类型</option>
                <option <?php echo $card_type==1?"selected":""; ?> value="1">包月卡</option>
                <option <?php echo $card_type==2?"selected":""; ?> value="2">次卡</option>
            </select>
            <select class="form-control" name="search_type">
                <option value="1">查询数据</option>
                <option value="2">导出数据</option>
            </select>&emsp;&emsp;
            <input type="submit" class="btn btn-primary" value="操作"/>
        </form>
		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th width="50">卡ID</th>
                    <th>会员名称</th>
                    <th>会员账号</th>
					<th>服务卡名称</th>
					<th>服务卡类型</th>
					<th>支付方式</th>
                    <th>服务时间</th>
					<th>所属门店</th>
                    <th>服务人员</th>
					<th>服务项目</th>
					<th>服务价格</th>
				</tr>
			</thead>
			<tbody>
			<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $key=>$vo): ?>
				<tr>
					<td><?php echo $vo['id']; ?></td>
					<td><?php echo $vo['nickname']; ?></td>
					<td><?php echo $vo['mobile']; ?></td>
					<td><?php echo $vo['card_name']; ?></td>
					<td><?php echo $vo['card_type']==1?"包月卡":"次卡"; ?></td>
					<td><?php echo $vo['pay_way']; ?></td>
					<td><?php echo date('Y-m-d H:i:s',$vo['yytime']); ?></td>
					<td><?php echo $vo['shop_name']; ?></td>
					<td><?php echo $vo['work_name']; ?></td>
					<td><?php echo $vo['sname']; ?></td>
					<td><?php echo $vo['price']; ?></td>
				</tr>
				<?php endforeach; endif; else: echo "" ;endif; ?>
			</tbody>
		</table>
		<div class="pagination"><?php echo $page; ?></div>
	</div>
	<script src="__STATIC__/js/admin.js"></script>
	<script src="__STATIC__/js/laydate/laydate.js"></script>
	<script type="text/javascript">
	    $(function(){
            get_worker();
	    })

		//日期时间选择器
	    laydate.render({
	        elem: '#slay_date'
	        , type: 'date'
	    });

	    //日期时间选择器
	    laydate.render({
	        elem: '#elay_date'
	        , type: 'date'
	    });

        function get_worker()
        {
            let shop_id = $('#shopids').val();
            $.post("<?php echo url('card/getWorkerByShop'); ?>",{shop_id:shop_id},function(res){
                let worker_html = '<option value="">选择服务人员</option>';
                if(res.code == 200){
                    $(res.data).each(function(i,v){
                        worker_html += `<option value="${v.workid}">${v.name}</option>`;
                    })
                }
                $('#workid').html(worker_html);
            })
        }

	</script>
</body>
</html>
