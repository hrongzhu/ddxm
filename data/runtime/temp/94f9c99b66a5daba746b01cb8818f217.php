<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:46:"themes/admin_simpleboot3/admin\main\index.html";i:1554867182;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
<?php function _get_system_widget($name){ ?>
<!-- <?php switch($name): case "CmfHub": ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">捣蛋熊猫后台管理</h3>
            </div>
            <div class="panel-body home-info">
                <ul class="list-unstyled">
                    <li>
                        <em>官网</em>
                        <span>
                            <a href="http://www.ddxm661.com/" target="_blank">www.ddxm661.com</a>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    <?php break; endswitch; ?> -->
<?php } ?>
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
    <?php endif; 
    \think\Hook::listen('admin_before_head_end',$temp5ce8aa0896de8,null,false);
 ?>

</head>
<body style="padding-top: 0!important;">
<div>
    <h1 style="color:#3399ff"><b><center>捣蛋熊猫</center></b></h1><br/>
    <?php if($show_stock): ?>
        <div class="wrap js-check-wrap">
            <form class="well form-inline margin-top-0" method="get" action="<?php echo url('Collect/orderList'); ?>">

                <!-- <input type="submit" class="btn btn-primary" value="搜索"/> -->
            </form>
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th width="50">ID</th>
                        <th >仓库名称</th>
                        <th >商品名称</th>
                        <th >剩余库存</th>
                        <th >到达警戒值时间</th>
                        <th >操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(is_array($stock_alert) || $stock_alert instanceof \think\Collection || $stock_alert instanceof \think\Paginator): if( count($stock_alert)==0 ) : echo "" ;else: foreach($stock_alert as $key=>$v): ?>
                        <tr style="height: 45px;">
                            <td><?php echo $v['id']; ?></td>
                            <td><?php echo $v['name']; ?></td>
                            <td><?php echo $v['title']; ?></td>
                            <td><?php echo $v['stock']; ?></td>
                            <td><?php echo date('Y-m-d H:i:s',$v['addtime']); ?></td>
                            <td>
                                <a href="javascript:void (0);" onclick="is_ok('<?php echo url('Main/updateStockAlertStatus'); ?>',<?php echo $v['id']; ?>,1,$(this))">我知道了</a>|
                                <a href="javascript:void (0);" onclick="is_ok('<?php echo url('Main/updateStockAlertStatus'); ?>',<?php echo $v['id']; ?>,2,$(this))">不再提醒</a>
                            </td>
                        </tr>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>
            <div style="float: left;margin-left: 10px;" class="pagination"><?php echo $page; ?></div>
        </div>
    <?php endif; ?>
    <!-- </div> -->
</div>
<script src="__STATIC__/js/admin.js"></script>
<!-- <script src="__STATIC__/js/echarts/echarts.min.js"></script> -->
<!-- <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script> -->
<!-- <script src="//apps.bdimg.com/libs/jqueryui/1.10.4/jquery-ui.min.js"></script> -->
<!-- <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
<!-- <script src="__STATIC__/js/index.js"></script> -->
<!-- <script src="__STATIC__/js/assets/js/jquery.ui.datepicker-zh-CN.js"  type="text/javascript"></script> -->
<script>
function is_ok(url,id,type,obj) {
        var alert = '';
        if (type == 1) {
            alert = '请再次确认！';
        }else{
            alert = '确认不再提醒？';
        }
        layer.confirm(alert, {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.post(url, { id: id ,type:type}, function(data){
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
</script>
<?php 
    \think\Hook::listen('admin_before_body_end',$temp5ce8aa0896de8,null,false);
 ?>
</body>
</html>
