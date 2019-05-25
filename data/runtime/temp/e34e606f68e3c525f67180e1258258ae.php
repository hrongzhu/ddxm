<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:50:"themes/admin_simpleboot3/admin\shop\shop_list.html";i:1554867178;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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
        <li class="active"><a href="<?php echo url('shop/shopList'); ?>">门店列表</a></li>
        <li><a href="<?php echo url('shop/addOrUpdate'); ?>">添加门店</a></li>
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
            <th >门店名称</th>
            <th >门店编号</th>
            <th>加盟商</th>
            <th >详细地址</th>
            <th >添加时间</th>
            <th >操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($shopList) || $shopList instanceof \think\Collection || $shopList instanceof \think\Paginator): if( count($shopList)==0 ) : echo "" ;else: foreach($shopList as $key=>$v): ?>
            <tr style="height: 55px;">
                <td><?php echo $v['id']; ?></td>
                <td><?php echo $v['name']; ?></td>
                <td><?php echo $v['code']; ?></td>
                <td><?php echo $v['franchisee']['name']; ?></td>
                <td><?php echo empty($v['detail_address'])?$v['location_address']:$v['detail_address']; ?></td>
                <td><?php echo $v['addtime']; ?></td>
                <td>
                    <a href="<?php echo url('Shop/addOrUpdate', ['shop_id'=>$v['id']]); ?>">编辑详情</a>|
                    <a href="javascript:void (0);" id="level_set" onclick="level_set(<?php echo $v['id']; ?>)" data-target="#myModal">设置等级</a>|
                    <!-- <a href="javascript:void (0);" id="recharge_set" onclick="recharge_set(<?php echo $v['id']; ?>)" data-target="#myModal">设置充值面额</a>| -->
                    <a href="javascript:parent.openIframeLayer('<?php echo url('Shop/servicePriceSet',['id'=>$v['id']]); ?>');">设置服务价格</a>|
                    <a href="javascript:void (0);" onclick="setDeliveryArea('<?php echo url('Shop/setDeliveryArea',['id'=>$v['id']]); ?>')">设置配送范围</a>|
                    <a href="javascript:void (0);" onclick="generate_qrcode('<?php echo url('Shop/createQrcode'); ?>',<?php echo $v['id']; ?>)">生成门店二维码</a>|
                    <a href="javascript:void (0);" onclick="del_shop('<?php echo url('Shop/del'); ?>',<?php echo $v['id']; ?>)">删除</a>
                </td>
            </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
    <div class="pagination" style="float: left">
        <?php echo $page; ?>
    </div>

</div>


<!--等级设置模态框-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">设置等级标准</h4>
            </div>
                <form action="<?php echo url('admin/shop/updata_level_price'); ?>" id="form" method="post" class="form-horizontal  margin-top-20 js-ajax-form" enctype="multipart/form-data">
            <div class="modal-body" id="level-set-modal">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="submit" class="btn btn-primary js-ajax-submit" id="level_price_save">保存</button>
            </div>
                </form>
        </div>
    </div>
</div>

<!--充值面额设置模态框-->
<div class="modal fade" id="rechargeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="rechargeModalLabel">设置充值面额</h4>
            </div>
            <div class="modal-body" id="recharge-set-modal">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                    <th>ID</th>
                    <th>面额</th>
                    <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <button class='btn btn-primary add-recharge-price'>添加面额</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" id="recharge-save">保存</button>
            </div>
        </div>
    </div>
</div>

<script src="__STATIC__/js/admin.js"></script>
<script type="text/javascript">
    var shop_id ;
    //设置服务及价格
    $('#service-price-set').click(function(){
        var shopId = $(this).attr('data-shop-id');
        alert(shopId);
    })

    //设置等级
    function level_set(id){
        $.post("<?php echo url('Shop/level_set'); ?>",{'id':id},function (res) {
            if(res.code==1){
                var htmls = '<input type="hidden" name="shop_id" value="'+id+'">';
                var level_list = res.data.level_list;
                var level_standard = res.data.level_standard;
                $.each(level_list,function(k,v){
                    if(level_standard!=null){
                        htmls+='<div class="form-group">\n' +
                            '            <label for="" class="col-sm-2 control-label"><span class="form-required">*</span>'+v.level_name+'</label>\n' +
                            '            <div>\n' +
                            '                <input type="text" data_level_id="'+v.id+'"name="'+v.id+'" class="form-control level_price_input" value="'+level_standard[v.id]+'">\n' +
                            '            </div>\n' +
                            '        </div>';
                    }else{
                        htmls+='<div class="form-group">\n' +
                            '            <label for="" class="col-sm-2 control-label"><span class="form-required">*</span>'+v.level_name+'</label>\n' +
                            '            <div>\n' +
                            '                <input type="text" data_level_id="'+v.id+'"name="'+v.id+'" class="form-control level_price_input" placeholder="输入积分标准">\n' +
                            '            </div>\n' +
                            '        </div>';
                    }
                })
                $('#level-set-modal').html(htmls);
            }
            $('#myModal').modal('show');
        })
    }

    //设置充值面额
    function recharge_set(id){
        shop_id = id;
        $.post("<?php echo url('Recharge/getRecharge'); ?>",{
            'id':id
        },function(res){
            var htmls = '';
            if (res.code == 1) {
                $.each(res.data,function(k,v){
                    htmls+="<tr><td>"+v.id+"</td><td class='recharge-price'>"+v.price+"</td><td><a href='javascript:void(0)' class='recharge_del'>删除</a></td></tr>"
                })
                $('#recharge-set-modal tbody').html(htmls);
                $('#rechargeModal').modal('show');
            }else {
                layer.msg('该店还未设置充值面额,请尽快添加！',{'icon':2},function(){
                    $('#rechargeModal').modal('show');
                })
            }

        })
    }

    //添加充值面额
    $('body').on('click','.add-recharge-price',function(){
        var last_id = $('#recharge-set-modal tbody tr:last').find('td:first').text();
        var htmls = "<tr><td>"+(parseInt(last_id)+1)+"</td><td class='recharge-price added'>双击此单元格添加面额</td><td><a href='javascript:void(0)' class='recharge_del'>删除</a></td></tr>";
        $('#recharge-set-modal tbody').append(htmls)
    })

    //表格双击可编辑
    $('body').on('click','.recharge-price',function () {
        var old_value = $(this).text();
        if(!$(this).is('.input')){
            $(this).addClass('input').html('<input type="text" value="'+ $.trim($(this).text()) +'" />').find('input').focus().blur(function(){
                var new_value = $(this).val();
                if(new_value !== old_value){
                    $(this).parent().removeClass('input').html($(this).val() || 0);return;
                }
            });
        }
    }).hover(function(){
        $(this).addClass('hover');
    },function(){
        $(this).removeClass('hover');
    });

    //保存充值金额
    $('#recharge-save').click(function(){
        var price = {};
        $('.recharge-price').each(function(){
            price[$(this).prev('td').text()] = $.trim($(this).text())
        })
        $.post("<?php echo url('Recharge/addRecharge'); ?>",{
            'id':shop_id,
            'price':price
        },function (res) {
            if(res.code==200){
                layer.msg(res.msg,{'icon':1},function(){
                    window.location.reload()
                })
            }else {
                layer.msg(res.msg,{'icon':2})
            }
        })
    })

    //删除一个充值面额
    $('body').on('click','.recharge_del',function(){
        var td = $(this).parent();
        var tr = td.parent();
        var id = tr.find('td').first().text();
        if(td.prev('td').hasClass('added')){
            tr.remove()
        }else {
            $.post("<?php echo url('Recharge/delRecharge'); ?>",{
                'id':id
            },function(res){
                if(res.code==200){
                    layer.msg(res.msg,{'icon':1},function(){
                        tr.remove();
                    })
                }else {
                    layer.msg(res.msg,{'icon':2})
                }
            })
        }
    })

    //输入校验
    $('div.modal-body').on('blur','input.level_price_input',function(){
       if(isNaN(parseInt($(this).val()))) {
            layer.msg('只能输入数字！', {icon: 2, time: 2000})
        }
    })

    /*编辑*/
    function edit_detail(title,url,w,h){
        layer_show(title,url,w,h);
    }

    /*编辑*/
    function add_shop(title,url,w,h){
        layer_show(title,url,w,h);
    }

    function generate_qrcode(url,id){
        $.post(url, { shop_id: id }, function(data){
            if (data.code == 200) {
                layer.msg(data.msg, {icon: 1,time:1000});
            }else {
                layer.msg(data.msg, {icon: 0,time:1000});
            }
        });
    };

    function setDeliveryArea(url)
    {
        layer_show('设置配送范围',url,1000,800);
    }

    function del_shop(url,id){
        layer.confirm('确认要删除？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.post(url, { shop_id: id }, function(data){
                if (data.code == 200) {
                    layer.msg(data.msg, {icon: 1,time:1000});
                    setTimeout('reloads()', 2000);
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
