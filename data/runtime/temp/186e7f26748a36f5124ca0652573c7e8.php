<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:54:"themes/admin_simpleboot3/admin\advert\advert_list.html";i:1554867173;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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

<style type="text/css">
    .table th, .table td {
        text-align: center;
        vertical-align: middle!important;
    }
</style>
</head>
<body>
<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li class="active"><a href="<?php echo url('Advert/advertList'); ?>">广告列表</a></li>
        <li><a href="javascript:void (0);" onclick="add_advert('添加广告','<?php echo url('Advert/advertAdd'); ?>',800,800)">添加广告</a></li>
    </ul>
    <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th width="50">ID</th>
            <th >店铺名称</th>
            <th >广告位名称</th>
            <th >广告类型</th>
            <th >宽度</th>
            <th >高度</th>
            <th >添加时间</th>
            <th >操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $key=>$v): ?>
            <tr style="height: 55px;" class="text-center">
                <td><?php echo $v['id']; ?></td>
                <td><?php echo empty($v['shop_name'])?'商城':$v['shop_name']; ?></td>
                <td><?php echo $v['name']; ?></td>
                <td><?php echo $v['adtype']; ?></td>
                <td><?php echo $v['width']; ?> px</td>
                <td><?php echo $v['height']; ?> px</td>
                <td><?php echo $v['addtime']; ?></td>
                <td>
                    <!-- <a href="javascript:void (0);" onclick="edit_advert('编辑广告','<?php echo url('Advert/advertEdit', ['id'=>$v['id']]); ?>',1000,800)">编辑详情</a>| -->
                    <!--<a href="javascript:void (0);" onclick="scheduling('员工排班','<?php echo url('Advert/scheDuLing', ['id'=>$v['id']]); ?>',1000,800)">员工排班</a>|-->
                    <a href="<?php echo url('Advert/advertEdit', ['id'=>$v['id']]); ?>" >编辑详情</a>|
                    <a href="javascript:void (0);" onclick="worker_del('<?php echo url('Advert/advertDel'); ?>',<?php echo $v['id']; ?>,$(this))">删除</a>
                </td>
            </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
    <div style="float: left;margin-left: 10px;" class="pagination"><?php echo $page; ?></div>
</div>
<script src="__STATIC__/js/admin.js"></script>
<script type="text/javascript">
    /*编辑*/
    function add_advert(title,url,w,h){
        layer_show(title,url,w,h);
    }

    function edit_advert(title,url,w,h){
        layer_show(title,url,w,h);
    }

    //------------------------------------------------
    //删除
    function worker_del(url,id,obj) {
        layer.confirm('确认要删除？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.post(url, { id: id }, function(data){
                if (data.code == 200) {
                    var link = obj.parents("tr");
                    link.remove();
                    layer.msg(data.msg, {icon: 1,time:2000});
                }else {
                    layer.msg(data.msg, {icon: 0,time:2000});
                }
            });
        });
    }

    //刷新页面
    function reloads(){
        window.location.reload();
    }
</script>
</body>
</html>
