<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:55:"themes/admin_simpleboot3/admin\others\registration.html";i:1554867175;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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
    <div class="form-group">
        选择操作类型:
        <select class="form-control" id="z_type"  name="z_type" style="width: 200px;">
            <option value="">请选择</option>
            <option value="1" >服务</option>
            <option value="2" >商品</option>
        </select>
    </div>
    <div style="margin-bottom: 5px;">
        <div class="form-group" id="phone" style="display: none;">
            手机号:
            <input type="text" class="form-control" id="mobile" style="width: 200px;" value="<?php echo input('request.mobile/d',''); ?>" placeholder="请输入手机号">
            <!-- <input type="text" class="form-control js-datetime" value="" style="width: 200px;" placeholder="2018-01-04 09:20"> -->
        </div>
        <div class="form-group" id="cate" style="display: none;">
            商品主要分类:
            <select class="form-control" id="cateid" style="width: 200px;">
                <?php if(is_array($catelist) || $catelist instanceof \think\Collection || $catelist instanceof \think\Paginator): $i = 0; $__LIST__ = $catelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $v['id']; ?>"><?php echo $v['cname']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
        <div class="form-group" id="cates" style="display: none;">
            
        </div>
        <button id="submit" class="btn btn-primary" >搜索</button>
    </div>


    <!-- 表单显示 -->
    <div id="table_card" style="display: none;">
        <table class="table table-hover table-bordered" >
            <thead>
            <tr>
                <th width="50">ID</th>
                <th >用户姓名（昵称）</th>
                <th >描述</th>
                <th>所属店铺</th>
                <th >金额</th>
                <th >余额</th>
                <th >销卡时间</th>
                <th >服务人员</th>
                <th width="150">扣除金额</th>
                <th width="150">服务类型</th>
                <th >操作</th>
            </tr>
            </thead>
            <tbody class="table_card">

            </tbody>
        </table>
    </div>
    <div id="goods" style="display: none; margin-bottom: 20px;background: #B0E0E6;border: 1px solid grey;border-radius: 3px;">
        <form id="form4" onsubmit="return false;" style="margin: 5px;">
            用户手机号:<input type="text" class="form-control" name="mobile" id="myphone" style="width: 240px;" value=""><br/>
            <span style="display: none;" id="n_name">用户姓名（昵称）:<input type="text" class="form-control" id="nickname" style="width: 240px;" value=""></span><br/>
            <div class="goods_card">
            </div><br/>
            确认总金额:<input type="text" class="form-control" name="price" id="zprice" style="width: 240px;" value=""><br/>
            下单日期：<input type="text" class="form-control js-datetime" value="" id="xd_date" style="width: 240px;" placeholder="2018-01-04 09:20"><br>
            <table class="table table-hover table-bordered">
                <thead>
                <tr>
                    <th >商品ID</th>
                    <th >商品图片</th>
                    <th >商品名称</th>
                    <th >数量</th>
                    <th >操作</th>
                </tr>
                </thead>
                <tbody class="goods_list">
                    
                </tbody>
            </table>
            <button  id="tijiao" onclick="ajaxsubmit()"  class="btn btn-success "><?php echo lang('提交'); ?></button>
        </form>
    </div>
    <div id="table_goods" style="display: none;">
        <table class="table table-hover table-bordered">
            <thead>
            <tr>
                <th width="50">ID</th>
                <th >商品图片</th>
                <th >商品名称</th>
                <th >二级分类</th>
                <th >商品价格</th>
                <th >操作</th>
            </tr>
            </thead>
            <tbody class="table_goods">
                
            </tbody>
        </table>
    </div>
    
    <input type="hidden" class="form-control" id="mobile" value="<?php echo (isset($mobile) && ($mobile !== '')?$mobile:''); ?>">
</div>
<div style="display:none">
    <script src="__STATIC__/js/admin.js"></script>
</div>
<script type="text/javascript">
    $(function(){
        var types = $('#z_type').val();
        $('#z_type').change(function(){
            types = $(this).val();
            if (types == 2) {
                var cateid = $('#cateid').val();
                getCate(cateid);
                $('#cate').show();
                $('#table_goods').show();
                $('#goods').show();
                $('#phone').hide();
                $('#table_card').hide();
            }else if(types == 1){
                $('#phone').show();
                $('#table_goods').hide();
                $('#cate').hide();
                $('#goods').hide();
                $('#cates').hide();
                $('#table_card').show();
            }else{
                $('#cate').hide();
                $('#cates').hide();
                $('#table_goods').hide();
                $('#goods').hide();
                $('#phone').hide();
                $('#table_card').hide();
            }
        });

        $('#submit').click(function(){
            if (types == 1) {
                var mobile = $('#mobile').val();
                getCard(mobile);
                
            }else if(types == 2){
                var cate_id = $('#cate_id').val();
                $.get('<?php echo url('Others/getGoodsList'); ?>',{cate_id:cate_id},function(data){
                    if (data.code === 200) {
                        $html = '';
                        $.each(data.data, function(k, v) {
                        $html += `<tr style="height: 55px;">
                            <td>${v.id}</td>
                            <td><img src="${v.attr_pic}" height="50"></td>
                            <td>${v.title} ${v.attr_name} ${v.attr_names}</td>
                            <td>${v.cname}</td>
                            <td>${v.price}</td>
                            <td>
                                <button class="btn btn-success" onclick="adds(this)">选择</button>
                            </td>
                        </tr>`;
                        });
                        $('.table_goods').html($html);
                    }else {
                        layer.msg(data.msg, {icon: 0,time:1000});
                    }
                },'json')
            }
        })
    });

    $('#cateid').change(function(){
        var cateid = $(this).val();
        getCate(cateid);
    });

    //获取分类
    function getCate(cateid)
    {
        $('#cates').show();
        $.get('<?php echo url('others/getGoodsCateList'); ?>',{cateid:cateid},function(data){
                if (data.code === 200) {
                    $catehtml = '';
                    $.each(data.data, function(k, v) {
                        $catehtml += `'<option value="${v.id}">${v.cname}</option>`;
                    });
                    $cates_html = `商品详细分类:
                            <select class="form-control" id="cate_id"  name="cate" style="width: 200px;">`
                                +$catehtml+
                            `</select>`;
                    $('#cates').html($cates_html);
                }else{
                    layer.msg(data.msg, {icon: 0,time:1000});
                }
        });
    }

    function getCard(mobile)
    {
        $.get('<?php echo url('Others/getCardList'); ?>',{mobile:mobile},function(data){
            if (data.code === 200) {
                //便利员工
                $whtml = '';
                $.each(data.data.worker, function(k, v) {
                    $whtml += `'<option value="${v.id}">${v.name} 【 ${v.type} 】</option>`;
                });
                $worker_html = `<select class="form-control" id="wid"  name="worker_id">
                            <option value="">请选择</option>`
                            +$whtml+
                        `</select>`;
                $html = '';
                $.each(data.data.list, function(k, v) {
                $html += `<tr style="height: 55px;">
                    <td>${v.id}</td>
                    <td>`+data.data.nickname+`</td>
                    <td>${v.title}</td>
                    <td>${v.shop_name}</td>
                    <td>${v.price}</td>
                    <td>${v.my_num}</td>
                    <td>
                        <input type="datetime-local" class="form-control js-datetime" name="times" width="50" value="" placeholder="2018-01-04 09:20">
                    </td>
                    <td>`+$worker_html+`</td>
                    <td>
                        <input type="text" class="form-control" name="kmoney" width="50" value="" placeholder="请输入金额">
                    </td>
                    <td>
                        <select class="form-control"  name="type">
                            <option value="">请选择</option>
                            <option value="0">婴儿游泳</option>
                            <option value="1">小儿推拿</option>
                            <option value="2">成人推拿</option>
                        </select>
                    </td>
                    <td>
                        <button class="btn btn-success" onclick="zhuxiao('<?php echo url('Others/registrationCard'); ?>',${v.id},this)">销卡</button>
                    </td>
                </tr>`;
                });
                $('.table_card').html($html);
            }else {
                layer.msg(data.msg, {icon: 0,time:1000});
            }
        },'json')
    }

    //列出用户的卡片
    $('#myphone').change(function(){
        var mobile = $(this).val();
        $.get('<?php echo url('others/getCardList'); ?>',{mobile:mobile},function(data){
            if (data.code == 200) {
                console.log(data.data.nickname);
                $('#n_name').show();
                $('#nickname').val(data.data.nickname);
                $chtml = '';    
                $.each(data.data.list, function(k, v) {
                    $chtml += `<option value="${v.id}">${v.title} ---- 剩余${v.my_num}元</option>`;
                });
                $cardhtml = `选择熊猫卡:
                        <select class="form-control" id="g_cardid"  name="g_cardid" style="width: 240px;">`
                            +$chtml+
                        `</select>`;
                $('.goods_card').html($cardhtml);
            }else{
                $.get('<?php echo url('others/getShopList'); ?>',{mobile:mobile},function(data){
                    console.log(data.data.nickname);
                    $('#n_name').show();
                    $('#nickname').val(data.data.nickname);
                    $shtml = '';    
                    $.each(data.data.list, function(k, v) {
                        $shtml += `<option value="${v.id}">${v.name}</option>`;
                    });
                    $shophtml = `选择门店:
                            <select class="form-control" id="shop_ids"  name="shop_ids" style="width: 240px;">
                                `+$shtml+`
                            </select>`;
                    $('.goods_card').html($shophtml);
                });
                // $cardhtml = `选择熊猫卡:
                //         <select class="form-control" id="g_cardid"  name="g_cardid" style="width: 240px;">
                //             <option value="">当前用户没有卡</option>
                //         </select>`;
                // $('.goods_card').html($cardhtml);
                // layer.msg(data.msg, {icon: 0,time:1000});
            }
            
        });
    });

    function zhuxiao(url,id,obj) {
            var mobile = $('#mobile').val();
            var worker_id = $(obj).parent().prev().prev().prev().children().val();
            var price = $(obj).parent().prev().prev().children().val();
            var type = $(obj).parent().prev().children().val();
            var times = $(obj).parent().prev().prev().prev().prev().children().val();
            // console.log(mobile,worker_id,price,type,times);return;
            if(price === ''){
                layer.msg('请输入金额', {icon:0,time:1500});
                return false;
            }
            if(worker_id === ''){
                layer.msg('请选择员工', {icon:0,time:1500});
                return false;
            }
            if(type === ''){
                layer.msg('请选择服务类型', {icon:0,time:1500});
                return false;
            }
            $.post(url,{card_id:id,type:type,kmoney:price,mobile:mobile,worker_id:worker_id,xk_time:times},function(data){
                if (data.code === 200) {
                    layer.msg(data.msg, {icon: 1,time:1500},function(){
                        var mobile = $('#mobile').val();
                        getCard(mobile);
                    });
                }else {
                    layer.msg(data.msg, {icon: 0,time:1000});
                }
            },'json')
    }

    function adds(obj) {
        // $('#tijiao').removeProp('disabled');
        $(obj).parents("tr").remove();
        // $($(obj).parent().parent()).appendTo($(".goods_list"));
        $goods_id = $(obj).parent().siblings().first().text();
        $goods_img = $(obj).parent().siblings().first().next().html();
        $name = $(obj).parent().prev().prev().prev().text();
        var html = `<tr class="a_goods">
            <td><input class="form-control" name="goods_id[]" value="`+$goods_id+`"/></td>
            <td>`+$goods_img+`</td>
            <td>`+$name+`</td>
            <td><input class="form-control" name="num[]"  value="1"/></td>
            <td><button class="btn btn-success" onclick="dels($(this))">删除</button></td>
        </tr>`;
        $(html).appendTo($(".goods_list"));
    }

    function ajaxsubmit()
    {
        var phone = $('#myphone').val();
        if (phone == '') {
            layer.msg('用户手机号不能为空',{icon: 0,time:1000});
            return false;
        }
        var zprice = $('#zprice').val();
        if (zprice == '') {
            layer.msg('请输入总金额',{icon: 0,time:1000});
            return false;
        }
        //获取商品数组
        var goods_list = new Array();
        $(".a_goods").each(function(){
            goods_list.push($(this).find("input[name='goods_id[]']").val());
            goods_list.push($(this).find("input[name='num[]']").val());
        });
        var xd_date = $('#xd_date').val();
        var card_ids = $('#g_cardid').val();
        
        var shop_id = $('#shop_ids').val();

        // var data = $("form").serializeArray();
        // console.log(data);return;
        $.post('<?php echo url('others/registrationGoods'); ?>',{goods_list:goods_list,mobile:phone,zprice:zprice,xd_data:xd_date,card_id:card_ids,shop_id:shop_id},function(res){
            if (res.code == 200) {
                layer.msg(res.msg, {icon: 1,time:1500},function () {
                        reloads()
                    });
            }else {
                layer.msg(res.msg, {icon: 0,time:1500});
            }
        })
    }

    function dels(obj)
    {
        var link = obj.parents("tr");
        link.remove();
    }

    //刷新页面
    function reloads(){
        window.location.reload();
    }
</script>
</body>
</html>