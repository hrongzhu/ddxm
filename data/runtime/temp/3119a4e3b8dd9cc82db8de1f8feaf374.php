<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:50:"themes/admin_simpleboot3/admin\item\type_edit.html";i:1554867192;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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
			<li><a href="<?php echo url('Item/type_list'); ?>">分类列表</a></li>
			<li><a href="<?php echo url('Item/type_add'); ?>">分类添加</a></li>
			<li class="active"><a>编辑分类</a></li>
		</ul>
		<form method="post" class="form-horizontal js-ajax-form margin-top-20" action="<?php echo url('Item/type_editPost'); ?>">
			<div class="form-group">
				<label for="input-user_login" class="col-sm-2 control-label"><span class="form-required">*</span>分类名称</label>
				<div class="col-md-6 col-sm-10">
					<input type="text" class="form-control" id="input-user_login" name="cname" value="<?php echo $data['cname']; ?>">
				</div>
			</div>
			<div class="form-group">
				<label for="input-user_login" class="col-sm-2 control-label"><span class="form-required">*</span>分类排序</label>
				<div class="col-md-6 col-sm-10">
					<input type="text" class="form-control" id="input-user_login" name="sort" value="<?php echo $data['sort']; ?>">
				</div>
			</div>
			<div class="form-group">
			    <label class="col-sm-2 control-label"><?php echo lang('STATUS'); ?></label>
				<div class="col-md-6 col-sm-10">
					<label class="radio-inline">
						<?php $active_true_checked=($data['status']==1)?"checked":""; ?>
						<input type="radio" name="status" value="1" <?php echo $active_true_checked; ?>> <?php echo lang('ENABLED'); ?>
					</label>
					<label class="radio-inline">
						<?php $active_false_checked=($data['status']==0)?"checked":""; ?>
						<input type="radio" name="status" value="0" <?php echo $active_false_checked; ?>> <?php echo lang('DISABLED'); ?>　　　　　　　　<b style="color: red;">注意：禁用父分类的话，子分类也会禁用</b>
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">分类主图</label>
				<div class="col-md-6 col-sm-10">
					<table class="table table-bordered" style="float: left;">
	                    <!-- <?php
                        if(!empty($list->mainPic)){
                            $arr = json_decode($list->mainPic,1);
                            if(is_array($arr)){
                            foreach ($arr as $key => $value) {
                        ?> -->
                        <div>                               
                            <img src="http://dd.ddxm661.com/library/admin/images/img.png" id="mainPic">
                            <input type="hidden" name="mainPic[]" value="<?php echo $value;?>">
                            <a href="javascript:;" onclick="moveImg(this)">删除</a>
                        </div>
                        <!-- <?php
                            }
                            }
                        }
                        ?> -->
                        <img src="http://upload.ddxm661.com<?php echo $data['thumb']; ?>" id="showPic" style="width: 80px;height: 80px;">
                        <img src="http://dd.ddxm661.com/library/admin/images/img.png" id="showPic">
                        <input type="file" id="files" name="files[]" ltype="text"  multiple="multiple" />    
                        <input type="button" value=" 上传 " onclick="more_upload()" />
                        (最多上传1张)
                    </table>
	            </div>
            </div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<input type="hidden" name="id" value="<?php echo $data['id']; ?>" />
					<button type="submit" class="btn btn-primary js-ajax-submit"><?php echo lang('SAVE'); ?></button>
					<a class="btn btn-default" href="javascript:history.back(-1);"><?php echo lang('BACK'); ?></a>
				</div>
			</div>
		</form>
	</div>
	<script src="__STATIC__/js/admin.js"></script>
	<script type="text/javascript">
		// 多图上传
		function more_upload(){
		    var i = 0;
		    var s = '';
		    $("img[id=mainPic]").each(function(n,e){
		        i++;
		    })
		    var formData = new FormData();
		    upfile = $("#files")[0].files;
		    var ii = 1;
		    $(upfile).each(function (n, e) {
		        i++;
		        if(i>1){
		            alert("上传图片不能超过1张");
		            clearInterval(s);
		            return false;
		        }
		        var s = setTimeout(function () {
		            formData.append('file', $(e)[0]);
		            upload_img(2, formData);
		        }, ii);
		        ii += 1000;
		    });
		}
        // 上传图片
		function upload_img(id,formData){
		    
		    var file =  $("#lfile").val();
		    // var files =  $("#files").val();

		    if((file =='' || file == null) && id==1){
		        alert("请选择上传文件");
		        return false;
		    }else{      
		        var upfile = '';
		        if(id==1){
		            var formData = new FormData();
		            upfile = $("#lfile")[0].files[0];
		            formData.append('file',upfile);
		        }
		        $.ajax({
		            url: "<?php echo url('Item/uploadPic'); ?>",
		            type: "POST",
		            data: formData,
		            dataType:'json',
		            processData : false, // 不处理发送的数据，因为data值是Formdata对象，不需要对数据做处理
		            contentType : false, // 不设置Content-type请求头
		            success: function(data) {
		                if(data.success==1){
		                    // $(".imgList").append($(".imgList>div:first-child").clone());
		                    if(id==1){
		                        $("#thumb").val(data.msg);
		                        $("#showThumb").attr('src',data.msg);
		                    }else{
		                        var img = '<div><img src="'+data.msg+'" id="mainPic" style="width:60px;height:100px;">';
		                        img += '<input type="hidden" name="mainPic[]" value="'+data.msgurl+'"><a href="javascript:;" onclick="moveImg(this)">删除</a></div>'
		                        $("#showPic").before(img);
		                        $("#files").val('');
		                        // $("#mainPic").val(data.msg);
		                        // $("#showPic").attr('src',data.msg);
		                    }
		                }else{
		                    alert(data.msg);
		                }
		            },
		            error: function(XMLHttpRequest, textStatus, errorThrown) {
		                alert("上传失败，请检查网络后重试");
		            }
		        });
		    }
		}
		function moveImg(str){
		    $(str).parent().remove();
		}
	</script>
</body>
</html>