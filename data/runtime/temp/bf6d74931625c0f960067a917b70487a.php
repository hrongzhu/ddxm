<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:50:"themes/admin_simpleboot3/admin\supplier\index.html";i:1554867204;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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
			<li class="active"><a href="<?php echo url('Supplier/index'); ?>">供应商列表</a></li>
			<li><a href="javascript:void(0)" id="supplier-add">供应商添加</a></li>
		</ul>
        <form class="well form-inline margin-top-0" method="get" action="<?php echo url('Supplier/index'); ?>">
            供应商名称/电话/联系人:
            <input type="text" class="form-control" name="keywords" style="width: 20%;" value="<?php echo isset($keywords)?$keywords:''; ?>" placeholder="请输入查询关键词">
            <input type="submit" class="btn btn-primary" value="搜索" />
        </form>
		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th width="50">ID</th>
					<th>供应商名</th>
					<th>联系人</th>
					<th>联系电话</th>
					<th>备注</th>
					<th width="180">操作</th>
				</tr>
			</thead>
			<tbody>
			<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $key=>$item): ?>
				<tr>
					<td><?php echo $item['id']; ?></td>
					<td><?php echo $item['name']; ?></td>
					<td><?php echo $item['linkman']; ?></td>
					<td><?php echo $item['tel']; ?></td>
					<td><?php echo empty($item['remark'])?'无':$item['remark']; ?></td>
					<td>
						<a href="javascript:void (0)" data-supplier-id="<?php echo $item['id']; ?>" class="supplier-edit">编辑</a>
						<a href="javascript:void (0)" data-supplier-id="<?php echo $item['id']; ?>" class="supplier-delete">删除</a>
					</td>
				</tr>
			<?php endforeach; endif; else: echo "" ;endif; ?>
			</tbody>
		</table>
		<div class="pagination"><?php echo $list->render(); ?></div>
	</div>
	<!--添加/删除模态框-->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel"></h4>
				</div>
				<div class="modal-body">
					<input type="hidden" id="supplierId">
					<div class="form-group">
						<label class="sr-only" for="supplierName"></label>
						<div class="input-group">
							<div class="input-group-addon">供应商名称</div>
							<input type="text" class="form-control" id="supplierName" placeholder="请输入供应商名称">
						</div>
					</div>
					<div class="form-group">
						<label class="sr-only" for="linkman"></label>
						<div class="input-group">
							<div class="input-group-addon">联系人</div>
							<input type="text" class="form-control" id="linkman" placeholder="请输入联系人姓名">
						</div>
					</div>
					<div class="form-group">
						<label class="sr-only" for="tel"></label>
						<div class="input-group">
							<div class="input-group-addon">联系电话</div>
							<input type="text" class="form-control" id="tel" placeholder="请输入联系电话">
						</div>
					</div>
					<div class="form-group">
						<label class="sr-only" for="remark"></label>
						<div class="input-group">
							<div class="input-group-addon">备注信息</div>
							<input type="text" class="form-control" id="remark" placeholder="请输入备注信息">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
					<button type="button" class="btn btn-primary" id="save">保存</button>
				</div>
			</div>
		</div>
	</div>
</body>
<script>
    $('#supplier-add').click(function(){
		$('#myModalLabel').html('添加供应商');
        $('#supplierId').val('');
        $('#myModal').modal('show')
    })
    $('.supplier-edit').click(function(){
        $('#myModalLabel').html('编辑供应商');
        var id = $(this).attr('data-supplier-id');
		$('#supplierId').val(id);
		$.post("<?php echo url('Supplier/getOne'); ?>",{
		    'id':id
		},function(res){
			if(res.code==1){
			    $('#supplierName').val(res.data.name)
			    $('#linkman').val(res.data.linkman)
			    $('#tel').val(res.data.tel)
			    $('#remark').val(res.data.remark)
				$('#myModal').modal('show')
			}
		},'JSON')
    })
    $('.supplier-delete').click(function(){
        var id = $(this).attr('data-supplier-id');
        layer.confirm('你确定要删除吗？',function(){
            $.post("<?php echo url('Supplier/delete'); ?>",{
                'id':id
            },function(res){
                if(res.code==1){
                    layer.msg(res.data,{'icon':1},function(){
                        window.location.reload()
					})
				}else {
                    layer.msg(res.data,{'icon':2})
				}
			},'JSON')
		})
    })
	$('#save').click(function(){
		$.post("<?php echo url('Supplier/save'); ?>",{
		    'id':$('#supplierId').val(),
			'name':$('#supplierName').val(),
			'linkman':$('#linkman').val(),
			'tel':$('#tel').val(),
			'remark':$('#remark').val()
		},function(res){
			if(res.code==1){
			    layer.msg('保存成功',{'icon':1},function(){
                window.location.reload()})
			}else {
                layer.msg(res.data,{'icon':2})
			}
		},'JSON')
	})
</script>
</html>
