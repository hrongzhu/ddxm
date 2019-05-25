<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:58:"themes/admin_simpleboot3/admin\card\service_card_list.html";i:1554867191;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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
        <li class="active">
            <a href="">服务卡列表</a>
        </li>
        <li>
            <a href="javascript:parent.openIframeLayer('<?php echo url('Card/cardAddOrUpdate',['type'=>1]); ?>','新增服务卡',{});">新增服务卡</a>
        </li>
    </ul>
    <!--<form class="well form-inline margin-top-0" method="get" action="<?php echo url('Order/orderList',['order_status' => isset($order_status) ? $order_status : ""]); ?>">-->
    <!--订单号:-->
    <!--<input type="text" class="form-control" name="sn" style="width: 120px;" value="<?php echo input('request.sn/s',''); ?>" placeholder="请输入订单号">-->
    <!--商品名称:-->
    <!--<input type="text" class="form-control" name="subtitle" style="width: 120px;" value="<?php echo input('request.subtitle/s',''); ?>" placeholder="请输入商品名">-->
    <!--购买人:-->
    <!--<input type="text" class="form-control" name="nickname" style="width: 120px;" value="<?php echo input('request.nickname/s',''); ?>" placeholder="请输入购买人">-->
    <!--<input type="submit" class="btn btn-primary" value="搜索" />-->
    <!--<a class="btn btn-danger" href="<?php echo url('Order/orderList',['order_status' => isset($order_status) ? $order_status : ""]); ?>">清空</a>-->
    <!--</form>-->
    <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th width="50">ID</th>
            <th >标题</th>
            <th >封面</th>
            <th >售价(元)</th>
            <th >单人限兑(张)</th>
            <th >有效时长(个月)</th>
            <th >包含门店</th>
            <th >状态</th>
            <th >添加时间</th>
            <th >操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $key=>$v): ?>
            <tr style="height: 55px;">
                <td><?php echo (isset($v['id']) && ($v['id'] !== '')?$v['id']:0); ?></td>
                <td><?php echo (isset($v['name']) && ($v['name'] !== '')?$v['name']:''); ?></td>
                <td><img src="<?php echo !empty($v['cover'])?$file_host:''; ?><?php echo (isset($v['cover']) && ($v['cover'] !== '')?$v['cover']:''); ?>" height="80"/></td>
                <td><?php echo (isset($v['money']) && ($v['money'] !== '')?$v['money']:''); ?> 元</td>
                <td><?php echo $v['restrict_num']==0?'不限':$v['restrict_num']; ?></td>
                <td><?php echo $v['expire_month']==0?'永久':$v['expire_month']; ?></td>
                <td data-ticketid="<?php echo $v['id']; ?>" class="view-openId"><?php echo !empty($v['shop_id'])?'<button type="button" class="btn btn-default btn-sm view-btn">
                    <span  class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span> 查看
                </button>':'暂未添加门店'; ?>
                </td>
                <td><?php echo isset($v['status'])&&$v['status'] == 1?'上架':'下架'; ?></td>
                <td><?php echo date('Y-m-d H:i:s',$v['addtime']); ?></td>
                <td>
                    <a href="javascript:parent.openIframeLayer('<?php echo url('Card/cardAddOrUpdate', ['id'=>$v['id']]); ?>','详情',{});">编辑</a>|
                    <a href="javascript:void (0);" onclick="deletes('<?php echo url('Card/delCard'); ?>',<?php echo $v['id']; ?>)">删除</a>
                </td>
            </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
    <div class="pagination" style="float: left">
        <?php echo $page; ?>
    </div>
</div>
<script src="__STATIC__/js/admin.js"></script>
<script type="text/javascript">
    //显示门店
    $(function () {
        $('.view-btn').click(function(){
            var id = $(this).parent().attr('data-ticketid')
            $.post("<?php echo url('Card/getBindShopList'); ?>",{'id':id},function(res){
                if (res.code === 200){
                    var html = '<div style="margin-top: 5px;background-color: #9d9d9d;">';
                    $.each(res.data,function(i,val){  //遍历二维数组
                        html += '<button class="btn btn-primary" style="float:left;margin-left: 2px;">'+ val.name+'</button>';
                    })
                    html += '</div>';
                    layer.open({
                        title: '门店信息',
                        type: 1,
                        area: ['300px', '300px'],
                        skin: 'layui-layer-demo', //样式类名
                        closeBtn: 1, //不显示关闭按钮
                        anim: 0,
                        shadeClose: true, //开启遮罩关闭
                        content: html
                    });
                } else{
                    layer.msg(res.msg,{icon:0});
                }
            })
        });
    });
    function deletes(url,id){
        layer.confirm('确认要删除？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.post(url, { ticket_id: id }, function(data){
                if (data.code == 200) {
                    layer.msg(data.msg, {icon: 1,time:1000},function() {
                        reloads();
                    });
                }else {
                    layer.msg(data.msg, {icon: 0,time:1000});
                }
            });
        });
    };
    //------------------------------------------------

    //刷新页面
    function reloads(){
        window.location.reload();
    }
</script>
</body>
</html>
