<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:58:"themes/admin_simpleboot3/admin\shop\service_price_set.html";i:1554867178;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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
    <div class="container">
        <!--服务及价格修改-->
        <?php if(isset($service_level_price_lists)): ?>
            <div class="modal-header">
                <h4 class="modal-title">请选择服务并设定价格</h4>
            </div>
            <div class="modal-body old_html">

                <!--现有服务-->
                <div class="modal-header">
                    <h4 class="modal-title">已有服务</h4>
                </div>
                <table class="table table-hover table-bordered" id="price_table_now">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>服务名称</th>
                        <?php if(is_array($level_lists) || $level_lists instanceof \think\Collection || $level_lists instanceof \think\Paginator): if( count($level_lists)==0 ) : echo "" ;else: foreach($level_lists as $key=>$l): ?>
                            <th data_level_id=<?php echo $l['id']; ?> class="level<?php echo $l['id']; ?>">
                                <?php echo $l['level_name']; ?>&nbsp;&nbsp;
                            </th>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                        <th width="130">操作</th>
                        <th width="130">线上预约</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!--现有服务列表-->
                    <?php if(is_array($service_level_price_lists) || $service_level_price_lists instanceof \think\Collection || $service_level_price_lists instanceof \think\Paginator): if( count($service_level_price_lists)==0 ) : echo "" ;else: foreach($service_level_price_lists as $slp=>$slp_value): if(is_array($lists) || $lists instanceof \think\Collection || $lists instanceof \think\Paginator): if( count($lists)==0 ) : echo "" ;else: foreach($lists as $key=>$vo): if($slp==$vo['s_id']): ?>
                                <tr>
                                    <td>
                                        <?php echo $vo['s_id']; ?>
                                    </td>
                                    <td>
                                        <?php echo $vo['sname']; ?>
                                    </td>
                                    <?php if(is_array($level_lists) || $level_lists instanceof \think\Collection || $level_lists instanceof \think\Paginator): if( count($level_lists)==0 ) : echo "" ;else: foreach($level_lists as $key=>$cll): if(is_array($slp_value) || $slp_value instanceof \think\Collection || $slp_value instanceof \think\Paginator): if( count($slp_value)==0 ) : echo "" ;else: foreach($slp_value as $slp_key=>$slp_v): if($cll['id']==$slp_key): ?>
                                                <td data_level_id=<?php echo $cll['id']; ?> class="level<?php echo $cll['id']; ?> price">
                                                    <?php echo $slp_v; ?>
                                                </td>
                                            <?php endif; endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; if(isset($different)&&$different!=0): $__FOR_START_25622__=0;$__FOR_END_25622__=$different;for($i=$__FOR_START_25622__;$i < $__FOR_END_25622__;$i+=1){ ?>
                                            <td class="price">0</td>
                                        <?php } endif; ?>
                                    <td>
                                        <span class="glyphicon glyphicon-minus delete_service" style="cursor:pointer" aria-hidden="true"></span>
                                    </td>
                                    <td>
                                        <input type="checkbox" class="book_status" <?php if($online_service_book[$slp]==1): ?>checked<?php endif; ?>>
                                    </td>
                                </tr>

                            <?php endif; endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>

                <!--可选服务-->
                <div class="modal-header">
                    <h4 class="modal-title">可选服务</h4>
                </div>
                <table class="table table-hover table-bordered" id="price_table_other">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>服务名称</th>
                        <th width="130">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!--可选服务列表-->
                    <?php if(is_array($lists) || $lists instanceof \think\Collection || $lists instanceof \think\Paginator): if( count($lists)==0 ) : echo "" ;else: foreach($lists as $list_k=>$list_v): if(!in_array($list_v['s_id'],array_keys($service_level_price_lists))): ?>
                            <tr>
                                <td>
                                    <?php echo $list_v['s_id']; ?>
                                </td>
                                <td>
                                    <?php echo $list_v['sname']; ?>
                                </td>
                                <td>
                                    <span class="glyphicon glyphicon-plus add_service" style="cursor:pointer" aria-hidden="true"></span>
                                </td>
                            </tr>
                        <?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" data-dismiss="modal" id="save_edit">保存</button>
            </div>
        <?php endif; ?>
        <!--服务及价格修改end-->

        <!--添加门店，设置服务及价格-->
        <?php if(!isset($service_level_price_lists)): ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">请选择服务并设定价格</h4>
            </div>
            <div class="modal-body old_html">
                <table class="table table-hover table-bordered" id="price_table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>服务名称</th>
                        <?php if(is_array($level_lists) || $level_lists instanceof \think\Collection || $level_lists instanceof \think\Paginator): if( count($level_lists)==0 ) : echo "" ;else: foreach($level_lists as $key=>$l): ?>
                            <th data_level_id=<?php echo $l['id']; ?> class="level<?php echo $l['id']; ?>">
                                <?php echo $l['level_name']; ?>&nbsp;&nbsp;
                            </th>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                        <th width="130">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($lists) || $lists instanceof \think\Collection || $lists instanceof \think\Paginator): if( count($lists)==0 ) : echo "" ;else: foreach($lists as $key=>$vo): ?>
                        <tr>
                            <td class="id"><?php echo $vo['s_id']; ?></td>
                            <td><?php echo $vo['sname']; ?></td>
                            <?php if(is_array($level_lists) || $level_lists instanceof \think\Collection || $level_lists instanceof \think\Paginator): if( count($level_lists)==0 ) : echo "" ;else: foreach($level_lists as $key=>$l): if(is_array($vo['standard_price']) || $vo['standard_price'] instanceof \think\Collection || $vo['standard_price'] instanceof \think\Paginator): if( count($vo['standard_price'])==0 ) : echo "" ;else: foreach($vo['standard_price'] as $k=>$v): if($k==$l['id']): ?>
                                        <td data_level_id=<?php echo $l['id']; ?> class="level<?php echo $l['id']; ?> price">
                                            <?php echo $v; ?>
                                        </td>
                                    <?php endif; endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                            <td>
                                <label>
                                    <input type="checkbox" class="service_choose" value="option1" aria-label="...">
                                </label>
                            </td>
                        </tr>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="reset">重设</button>
                <button type="button" class="btn btn-success " data-dismiss="modal" id="save">保存</button>
            </div>
        <?php endif; ?>
        <!--添加门店，设置服务及价格end-->

        <!--隐藏域保存选择的服务及价格-->
        <input type="hidden" name="service_price" id="service_price" value="">
        <input type="hidden" name="book_status" id="book_status" value="">
        <input type="hidden" name="shop_id" id="shop_id" value="<?php echo $shop_id; ?>">


    </div><!-- /.modal-content -->

</div>
</body>

<script type="text/javascript">
    $(function(){

        //编辑时添加服务
        $('#price_table_other').on('click','.add_service',function(){
            var tr = $(this).parents('tr');
            var service_id = $.trim($(this).parent().parent().children('td').first().text());
            var service_name = $.trim($(this).parent().parent().children('td').eq(1).text());
            var level_length = $("#price_table_now").find("tr").first().find("th[data_level_id]").length;
            var add_html = "<tr class='add'><td>"+service_id+"</td><td>"+service_name+"</td>";
            $.post("<?php echo url('Shop/get_standard_price'); ?>",{
                'service_id':service_id
            },function(res){
                if(res.code==1){
                    $.each((res.data.data),function(k,v){
                        add_html+='<td>'+v+'</td>';
                    })
                    var data_length = res.data.count;
                    if(data_length!=level_length){
                        for(var i=0;i<level_length-data_length;i++){
                            add_html+='<td class="price"></td>';
                        }
                    }
                    add_html+="<td><span class=\"glyphicon glyphicon-minus delete_service\" style=\"cursor:pointer\" aria-hidden=\"true\"></span></td><td><input type='checkbox' class='book_status'></td></tr>";
                    $('#price_table_now').append(add_html);
                    $.each($('#price_table_now tr.add td'),function(index,value){
                        var level_id = $(value).parent().prev().children('td').eq(index).attr('data_level_id');
                        if(level_id){
                            $(this).attr('data_level_id',level_id);
                            $(this).addClass('level'+level_id+' price');
                        }
                    })
                    tr.remove();
                }else {
                    layer.msg('获取服务参考价格失败请重试',{'icon':2})
                }
            })
        })

        //修改服务价格
        $('body').on('dblclick','.price',function(){
            var td = $(this);
            var service_id = $(this).parent().children('td').first().text();
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

        //编辑时删除服务
        $('#price_table_now').on('click','.delete_service',function(){
            var num = $('#price_table_now tr').length;
            if(num<=2){
                layer.msg('不能删除！至少必须提供一个服务!',{icon: 2, time: 3000});
            }else {
                var tr = $(this).parents('tr');
                var service_id = $.trim($(this).parent().parent().children('td').first().text());
                var service_name = $.trim($(this).parent().parent().children('td').eq(1).text());
                var delete_html = "<tr><td>"+service_id+"</td><td>"+service_name+"</td><td><span class=\"glyphicon glyphicon-plus add_service\" style=\"cursor:pointer\" aria-hidden=\"true\"></span></td></tr>";
                $('#price_table_other').append(delete_html);
                tr.remove();
            }
        })

        //保存选择的服务和价格生成隐藏域提交
        $('body').on('click','#save',function(){
            $.each($('input.book_status'),function(k,v){
                if($(this).prop('checked')){
                    $(this).val(1)
                }else {
                    $(this).val(0)
                }
            })
            if($('input:checkbox:checked').length==0){
                layer.msg('你没有勾选任何服务！',{'icon':2});
                return;
            }
            $('.price').each(function(k,v){
                if($(v).text()==''){
                    layer.msg('请将服务价格填写完整！',{'icon':2});
                    return;
                }
            })
            var service_level_price = {};
            $.each($('input:checkbox:checked'),function(kk,vv){
                var one = {};
                var service_id = $(vv).parent().parent().parent().find('td').first().text();
                var td = $(vv).parent().parent().parent().find('td');
                $.each(td,function(k,v){
                    if($(v).attr('data_level_id')){
                        one[$(v).attr('data_level_id')] = $.trim($(v).text())
                    }
                })
                service_level_price[service_id] = one;
            })
            $('#service_price').val(JSON.stringify(service_level_price));
            save();
        })

        //保存更改的服务和价格生成隐藏域提交
        $('body').on('click','#save_edit',function(){
            $.each($('input.book_status'),function(k,v){
                if($(this).prop('checked')){
                    $(this).val(1)
                }else {
                    $(this).val(0)
                }
            })
            $('.price').each(function(k,v){
                if($(v).text()==''){
                    layer.msg('请将服务价格填写完整！',{'icon':2});
                    return;
                }
            })
            var service_level_price = {};
            var book_status = {};
            var status=0;
            $.each($('#price_table_now tr:gt(0)'),function(kk,vv){
                var service_id = $.trim($(this).children('td').first().text());
                var one = {};
                $.each($(this).find('td'),function(kk,vv){
                    var current = $(this);
                    if($('#price_table_now thead th:eq('+kk+')').attr('data_level_id')!=null){
                        one[$('#price_table_now thead th:eq('+kk+')').attr('data_level_id')] = $.trim(current.text())
                        service_level_price[service_id] = one;
                    }
                })

                status = $.trim($(this).children('td:last').find('input.book_status').val());
                if(status==1){
                    book_status[service_id] = status;
                }else {
                    book_status[service_id] = "0";

                }
            })
            // console.log(service_level_price);
            $('#service_price').val(JSON.stringify(service_level_price));
            $('#book_status').val(JSON.stringify(book_status))
            save();
        })

        //ajax提交数据保存服务和价格
        function save(){
            var index = parent.layer.getFrameIndex(window.name);
            $.post("<?php echo url('Shop/saveServicePrice'); ?>",{
                'id':$('#shop_id').val(),
                'service_price':$('#service_price').val(),
                'book_status':$('#book_status').val()
            },function(res){
                if(res.code==1){
                    layer.msg(res.msg,{'icon':1},function(){
                        parent.layer.close(index);
                    })
                }else {
                    layer.msg(res.msg,{'icon':2});
                }
            })
        }
    })

</script>