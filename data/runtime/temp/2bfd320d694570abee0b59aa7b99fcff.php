<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:63:"themes/admin_simpleboot3/admin\finance\service_sale_report.html";i:1554867194;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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
    <form class="well form-inline margin-top-0" method="get" action="<?php echo url('Finance/service_sale_report'); ?>" id="search_form">
        <input type="text" class="form-control" name="worker_name" style="width: 120px;" <?php if(isset($worker_name)): ?> value="<?php echo $worker_name; ?>"<?php endif; ?>
        placeholder="员工名字">
        <input type="text" id="time" style="width: 12%;" class="form-control" name="time"
        <?php if(isset($add_s)&&isset($add_e)): ?> value="<?php echo date("Y-m-d",$add_s); ?> ~ <?php echo date("Y-m-d",$add_e); ?>"<?php endif; ?>
        placeholder="选择时间段">
        <select class="form-control" name="shop_id">
            <option value="">选择店铺</option>
            <?php foreach($shopDatas as $v): ?>
                <option <?php if($shop_id == $v['id']): ?>selected<?php endif; ?> value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
            <?php endforeach; ?>
        </select>
        <select class="form-control" name="is_online">
            <option value="">选择来源</option>
            <option value="1" <?php if(isset($is_online)&&$is_online==1): ?>selected<?php endif; ?>>线上</option>
            <option value="0" <?php if(isset($is_online)&&$is_online==0): ?>selected<?php endif; ?>>门店</option>
        </select>
        <select class="form-control" name="service_id">
            <option value="">选择服务类型</option>
            <?php if(is_array($service_list) || $service_list instanceof \think\Collection || $service_list instanceof \think\Paginator): if( count($service_list)==0 ) : echo "" ;else: foreach($service_list as $key=>$v): ?>
                <option value="<?php echo $v['id']; ?>" <?php if(isset($service_id)&&$service_id==$v['id']): ?>selected<?php endif; ?>><?php echo $v['sname']; ?></option>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </select>
        <input type="submit" class="btn btn-primary" value="搜索"/>
        <a class="btn btn-danger" id="reset" href="javascript:void(0)">清空</a>
    </form>
    <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th>服务名称</th>
            <th>店铺名称</th>
            <th>员工姓名</th>
            <th>员工工号</th>
            <th>售出数量</th>
            <th>退货数量</th>
            <th>来源</th>
            <th>时间</th>
        </tr>
        </thead>
        <tbody>
        累计销量:<?php echo $num_all; ?>&emsp;&emsp;&emsp;累计退货:<?php echo $refund_num_all; if(is_array($datas) || $datas instanceof \think\Collection || $datas instanceof \think\Paginator): if( count($datas)==0 ) : echo "" ;else: foreach($datas as $key=>$v): ?>
            <tr style="height: 55px;">
                <td><?php echo $v['sname']; ?></td>
                <td><?php echo $v['shop_name']; ?></td>
                <td><?php echo $v['worker_name']; ?></td>
                <td><?php echo $v['workid']; ?></td>
                <td><?php echo $v['num']; ?></td>
                <td><?php echo $v['refund_num']; ?></td>
                <?php if($v['is_online']==1): ?>
                    <td>线上</td>
                <?php endif; if($v['is_online']==0): ?>
                    <td>门店</td>
                <?php endif; ?>
                <td><?php echo date('Y-m-d H:i',$v['update_time']); ?></td>
            </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
    <!--<div class="pagination" style="float: left">-->
    <!--<button class="btn btn-success" id="selectAll">全选</button>-->
    <!--<button class="btn btn-warning" id="closeAll" style="margin-left: 10px;">关闭订单</button>-->
    <!--<button class="btn btn-danger" id="delAll" style="margin-left: 10px;">删除订单</button>-->
    <!--</div>-->
    <div style="float: left;margin-left: 10px;" class="pagination"><?php echo $page; ?></div>
</div>
<script src="__STATIC__/js/admin.js"></script>
<script src="__STATIC__/js/laydate/laydate.js"></script>
<script type="text/javascript">
    //------------------------------------------------

    //刷新页面
    function reloads() {
        window.location.reload();
    }

    laydate.render({
        elem: '#time',
        type: 'date',
        range: '~'
    });

    $('#reset').click(function(){
        $("#search_form input[type!='submit']").val('')
        $('#search_form select').val('')
    })

</script>
</body>
</html>
