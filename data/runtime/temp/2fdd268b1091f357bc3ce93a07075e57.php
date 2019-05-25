<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:49:"themes/admin_simpleboot3/admin\user\bindshop.html";i:1554867179;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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
<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
<style type="text/css">
    body{ font-size:12px;}
    .l-table-edit {}
    .l-table-edit-td{ padding:4px;}
    .l-button-submit,.l-button-test{width:80px; float:left; margin-left:10px; padding-bottom:2px;}
    .l-verify-tip{ left:230px; top:120px;}
</style>
<div class="table wrap js-check-wrap">
    <form action="<?php echo url('admin/User/bindShop'); ?>" id="form" method="post" class="form-horizontal  margin-top-20 js-ajax-form" enctype="multipart/form-data">

        <div class="form-group">
            <label class="col-sm-2 control-label">选择店铺</label>
            <div class="col-md-6 col-sm-10">
                <?php if(is_array($shopInfo) || $shopInfo instanceof \think\Collection || $shopInfo instanceof \think\Paginator): if( count($shopInfo)==0 ) : echo "" ;else: foreach($shopInfo as $key=>$vo): ?>
                    <label class="checkbox-inline">
                        <?php $role_id_checked=in_array($vo['id'],$shop_id)?"checked":""; ?>
                        <input value="<?php echo $vo['id']; ?>" type="checkbox" name="shop_id[]" <?php echo $role_id_checked; ?> ><?php echo $vo['name']; ?>
                    </label>
                <?php endforeach; endif; else: echo "" ;endif; ?>
                <!--<p class="help-block">店铺名称</p>-->
            </div>
        </div>

        <input type="hidden" name="user_id" value="<?php echo (isset($user_id) && ($user_id !== '')?$user_id:''); ?>">
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary js-ajax-submit"><?php echo lang('SAVE'); ?></button>
            </div>
        </div>
        <br>

    </form>
</div>
<div style="display:none">
    <script src="__STATIC__/js/admin.js"></script>
    <!--<script type="text/javascript" src="https://cdn.staticfile.org/webuploader/0.1.5/webuploader.js"></script>-->
    <!--<script type="text/javascript" src="__STATIC__/js/ueditor/ueditor.config.js"></script>-->
    <!--<script type="text/javascript" src="__STATIC__/js/ueditor/ueditor.all.min.js"></script>-->
</div>
<script type="text/javascript">
    //编辑器路径定义
//    var editorURL = GV.WEB_ROOT;
</script>

<script type="text/javascript">

</script>
</body>
</html>