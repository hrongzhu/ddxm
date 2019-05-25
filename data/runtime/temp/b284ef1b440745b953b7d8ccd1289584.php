<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:57:"themes/admin_simpleboot3/admin\item\item_cate_update.html";i:1554867192;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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

<link rel="stylesheet" type="text/css" href="https://cdn.staticfile.org/webuploader/0.1.5/webuploader.css">
</head>
<body>
<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
<style type="text/css">
    body{ font-size:12px;}
    .l-table-edit {}
    .l-table-edit-td{ padding:4px;}
    .l-button-submit,.l-button-test{width:80px; float:left; margin-left:10px; padding-bottom:2px;}
    .l-verify-tip{ left:230px; top:120px;}
</style>
<div class="table wrap js-check-wrap">
    <form action="<?php echo url('item/itemCateUpdate'); ?>" id="form" method="post" class="form-horizontal  margin-top-20 js-ajax-form" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name" class="col-sm-2 control-label">专区名称</label>
            <div class="col-md-6 col-sm-10">
              <input type="text" class="form-control" id="title" name="title" value="<?php echo (isset($info['title']) && ($info['title'] !== '')?$info['title']:''); ?>">
              <p class="help-block">专区名称</p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">专区图片</label>
            <div class="col-md-6 col-sm-10">
                <div id="fileList" class="uploader-list">
                    <img id="thumbImg" src="<?php echo $host; ?><?php echo (isset($info['thumb']) && ($info['thumb'] !== '')?$info['thumb']:''); ?>" width="150" style="margin-bottom:5px;" alt="">
                </div>
                <input type="hidden" id="thumb" name="thumb" value="<?php echo (isset($info['thumb']) && ($info['thumb'] !== '')?$info['thumb']:''); ?>">
                <div id="filePicker">选择图片</div>
                <p class="help-block">推荐图片尺寸：800*400（比例相同即可）</p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">当前专区默认商品图片（头）</label>
            <div class="col-md-6 col-sm-10">
                <div id="fileList" class="uploader-list">
                    <img id="left_picImg" src="<?php echo $host; ?><?php echo (isset($info['left_pic']) && ($info['left_pic'] !== '')?$info['left_pic']:''); ?>" width="150" style="margin-bottom:5px;" alt="">
                </div>
                <input type="hidden" id="left_pic" name="left_pic" value="<?php echo (isset($info['left_pic']) && ($info['left_pic'] !== '')?$info['left_pic']:''); ?>">
                <div id="pickPic">选择图片</div>
                <p class="help-block">推荐图片尺寸：800*400（比例相同即可）</p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">当前专区默认商品图片（尾）</label>
            <div class="col-md-6 col-sm-10">
                <div id="fileList" class="uploader-list">
                    <img id="right_picImg" src="<?php echo $host; ?><?php echo (isset($info['right_pic']) && ($info['right_pic'] !== '')?$info['right_pic']:''); ?>" width="150" style="margin-bottom:5px;" alt="">
                </div>
                <input type="hidden" id="right_pic" name="right_pic" value="<?php echo (isset($info['right_pic']) && ($info['right_pic'] !== '')?$info['right_pic']:''); ?>">
                <div id="pick_R_pic">选择图片</div>
                <p class="help-block">推荐图片尺寸：800*400（比例相同即可）</p>
            </div>
        </div>
        <input type="hidden"  class="form-control" id="id" name="id" value="<?php echo (isset($info['id']) && ($info['id'] !== '')?$info['id']:''); ?>">
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button id="tijiao" class="btn btn-primary"><?php echo lang('SAVE'); ?></button>
            </div>
        </div>
    </form>
</div>
<div style="display:none">
    <script src="__STATIC__/js/admin.js"></script>
    <script type="text/javascript" src="https://cdn.staticfile.org/webuploader/0.1.5/webuploader.js"></script>
    <script type="text/javascript" src="__STATIC__/js/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" src="__STATIC__/js/ueditor/ueditor.all.min.js"></script>
</div>
<script type="text/javascript">
    $(function() {
    //图片上传
        $host = 'http://' + "<?php echo $_SERVER['HTTP_HOST']; ?>"
        $hosts = "<?php echo $host; ?>";
        var uploader = WebUploader.create({
            // 选完文件后，是否自动上传。
            auto: true,
            // swf文件路径
            swf: 'https://cdn.staticfile.org/webuploader/0.1.5/Uploader.swf',
            // 文件接收服务端。
            server: $host+"<?php echo url('Shop/uploadPic'); ?>",
            // 选择文件的按钮。可选。
            // 内部根据当前运行是创建，可能是input元素，也可能是flash.
            pick: '#filePicker',
            // 只允许选择图片文件。
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/*'
            }
        });
        var left_uploader = WebUploader.create({
            // 选完文件后，是否自动上传。
            auto: true,
            // swf文件路径
            swf: 'https://cdn.staticfile.org/webuploader/0.1.5/Uploader.swf',
            // 文件接收服务端。
            server: $host+"<?php echo url('Shop/uploadPic'); ?>",
            // 选择文件的按钮。可选。
            // 内部根据当前运行是创建，可能是input元素，也可能是flash.
            pick: '#pickPic',
            // 只允许选择图片文件。
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/*'
            }
        });
        var right_uploader = WebUploader.create({
            // 选完文件后，是否自动上传。
            auto: true,
            // swf文件路径
            swf: 'https://cdn.staticfile.org/webuploader/0.1.5/Uploader.swf',
            // 文件接收服务端。
            server: $host+"<?php echo url('Shop/uploadPic'); ?>",
            // 选择文件的按钮。可选。
            // 内部根据当前运行是创建，可能是input元素，也可能是flash.
            pick: '#pick_R_pic',
            // 只允许选择图片文件。
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/*'
            }
        });
        //上传成功获取图片路径
        uploader.on('uploadSuccess', function (file, res) {
            if(res.code == 200){
                layer.msg(res.msg, {icon: 1, time: 2000});
                $('#thumb').val(res.data.path);
                $('#thumbImg').attr('src',$hosts + res.data.path);
            }else{
                layer.msg(res.msg, {icon: 2, time: 2000});
            }
        })
        //上传前面的图片
        left_uploader.on('uploadSuccess', function (file, res) {
            if(res.code == 200){
                layer.msg(res.msg, {icon: 1, time: 2000});
                $('#left_pic').val(res.data.path);
                $('#left_picImg').attr('src',$hosts + res.data.path);
            }else{
                layer.msg(res.msg, {icon: 2, time: 2000});
            }
        })
        //上传后面的图片
        right_uploader.on('uploadSuccess', function (file, res) {
            if(res.code == 200){
                layer.msg(res.msg, {icon: 1, time: 2000});
                $('#right_pic').val(res.data.path);
                $('#right_picImg').attr('src',$hosts + res.data.path);
            }else{
                layer.msg(res.msg, {icon: 2, time: 2000});
            }
        })
    });
</script>
</body>
</html>
