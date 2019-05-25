<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:53:"themes/admin_simpleboot3/admin\shop\standard_add.html";i:1554867178;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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

<link rel="stylesheet" type="text/css" href="__STATIC__/js/webuploader/webuploader.css">
</head>
<body>
	<div class="wrap">
		<ul class="nav nav-tabs">
			<!--<li><a href="<?php echo url('Shop/standard'); ?>">服务及参考价格列表</a></li>-->
		</ul>
		<div class="form-horizontal margin-top-20">
		    <div class="form-group">
				<label class="col-sm-2 control-label"><span class="form-required">*</span>服务名称</label>
				<div class="col-md-6 col-sm-10">
					<input type="text" class="form-control" id="sname">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">条码形(选填)</label>
				<div class="col-md-6 col-sm-10">
					<input type="text" class="form-control" id="bar_code" value="0" >
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label"><span class="form-required">*</span>服务图标</label>
				<div class="col-md-6 col-sm-10" id="pickIcon">+ 选择图标</div>
				<div class="col-sm-10">
					<div id="icons" class="uploader-list" style="margin-left: 20%;">
						<div>
							<img src="" id="show-icon" style="height:80px;width:120px;margin-bottom: 10px" alt="">
							<input type="hidden" id="icon" value="">
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label"><span class="form-required">*</span>服务图片</label>
				<div class="col-md-6 col-sm-10" id="pickCover">+ 选择图片</div>
				<div class="col-sm-10">
					<div id="covers" class="uploader-list" style="margin-left: 20%;">
						<div>
							<img src="" id="cover-img" style="height:80px;width:120px;margin-bottom: 10px" alt="">
							<input type="hidden" id="cover" value="">
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label"><span class="form-required">*</span>会员等级参考价</label>
				<div class="col-md-6 col-sm-10">
					<table class="table table-hover table-bordered" id="level_price">
						<tbody>
							<tr style="height: 50px;">
								<?php if(is_array($lists) || $lists instanceof \think\Collection || $lists instanceof \think\Paginator): if( count($lists)==0 ) : echo "" ;else: foreach($lists as $key=>$vo): ?>
									<td class="btn-primary" style="align:center" data-level-id="<?php echo $vo['id']; ?>"><?php echo $vo['level_name']; ?></td>
								<?php endforeach; endif; else: echo "" ;endif; ?>
							</tr>
							<tr style="height: 50px;">
								<?php if(is_array($lists) || $lists instanceof \think\Collection || $lists instanceof \think\Paginator): if( count($lists)==0 ) : echo "" ;else: foreach($lists as $key=>$v): ?>
									<td><input type="text" class="form-control" data-id="<?php echo $v['id']; ?>" name="level_id[]"></td>
								<?php endforeach; endif; else: echo "" ;endif; ?>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label"><span class="form-required">*</span>服务描述</label>
				<div class="col-md-6 col-sm-10">
					<textarea cols="30" rows="10" id="remark" class="form-control"></textarea>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button id="save" class="btn btn-primary"><?php echo lang('ADD'); ?></button>
				</div>
			</div>
		</div>
	</div>
	<script src="__STATIC__/js/admin.js"></script>
	<script type="text/javascript" src="__STATIC__/js/webuploader/webuploader.min.js"></script>
</body>
<script type="text/javascript">
	$(function(){
		$('#save').click(function(){
            let data = {
                sname:'',
                icon:'',
                cover:'',
                bar_code:0,
                level_price:{},
                remark:''
            };
            $.each($('#level_price tr'),function(kk,vv){
                $.each($(this).find('td'),function(kk,vv){
                    var current = $(this);
                    if($('#level_price tr:eq(0) td:eq('+kk+')').attr('data-level-id')!=null){
                        data.level_price[$('#level_price tr:eq(0) td:eq('+kk+')').attr('data-level-id')] = $.trim(current.find('input').val())
                    }
                })
            })
            data.sname = $('#sname').val();
            data.icon = $('#icon').val();
            data.cover = $('#cover').val();
            data.barcode = $('#bar_code').val();
            data.remark = $.trim($('#remark').val());
			$.post("<?php echo url('Shop/standard_save'); ?>",{data},function(res){
				if (res.code === 200){
				    layer.msg(res.msg,{icon:1,time:1500},function () {
                        parent.layer.close(parent.layer.getFrameIndex(window.name));
                    });
				} else{
				    layer.msg(res.msg,{icon:2,time:1500});
				}
			})
		});

		//文件上传
		var cover = createUploader('#pickCover');
        cover.on('uploadSuccess', function (file, res) {
            if(res.code === 200){
                layer.msg(res.msg, {icon: 1, time: 2000});
                $('#cover').val(res.data.path);
                $('#cover-img').attr('src',res.data.show_path);
            }else{
                layer.msg(res.msg, {icon: 2, time: 2000});
            }
        });
        var icon = createUploader('#pickIcon');
        icon.on('uploadSuccess', function (file, res) {
            if(res.code === 200){
                layer.msg(res.msg, {icon: 1, time: 2000});
                $('#icon').val(res.data.path);
                $('#show-icon').attr('src',res.data.show_path);
            }else{
                layer.msg(res.msg, {icon: 2, time: 2000});
            }
        });
	});
    function createUploader(pick_btn) {
        var domian = "<?php echo $_SERVER['HTTP_HOST']; ?>"
		return WebUploader.create({
            auto: true,
            swf: 'https://cdn.staticfile.org/webuploader/0.1.5/Uploader.swf',
            server: 'http://'+domian+"<?php echo url('Base/uploadImgToFileService'); ?>",
            pick: pick_btn,
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/*'
            }
        });
    }
</script>
</html>