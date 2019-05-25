<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:53:"themes/admin_simpleboot3/admin\ticket\ticket_add.html";i:1554867203;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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

<link rel="stylesheet" type="text/css" href="__STATIC__/js/webuploader/webuploader.css">
</head>
<body>
<style type="text/css">
    body{ font-size:12px;}
    .l-table-edit {}
    .l-table-edit-td{ padding:4px;}
    .l-button-submit,.l-button-test{width:80px; float:left; margin-left:10px; padding-bottom:2px;}
    .l-verify-tip{ left:230px; top:120px;}
    .get_way{display: none;}
    .ticket-money{display: none;}
</style>
<div class="table wrap js-check-wrap">
    <div  class="form-horizontal margin-top-20">
        <div class="form-group">
            <label for="name" class="col-sm-2 control-label"><span class="form-required">*</span>券名称</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control" id="name" name="name" value="">
                <p class="help-block">券名称</p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label"><span class="form-required">*</span>券封面</label>
            <div class="col-md-6 col-sm-10" id="filePicker">+ 选择封面</div>
            <div class="col-sm-10">
                <div id="fileList" class="uploader-list" style="margin-left: 20%;">
                    <div>
                        <img src="" id="headimg" style="height:80px;width:120px;margin-bottom: 10px" alt="">
                        <input type="hidden" id="cover" name="cover" value="">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="restrict_num" class="col-sm-2 control-label">单人限购数</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control" id="restrict_num" name="restrict_num" value="">
                <p class="help-block">单人限购数（默认不限）</p>
            </div>
        </div>
        <div class="form-group">
            <label for="circulation" class="col-sm-2 control-label">发行量</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control" id="circulation" name="circulation" value="">
                <p class="help-block">发行量(默认不限)</p>
            </div>
        </div>
        <div class="form-group">
            <label for="expire_date" class="col-sm-2 control-label">有效期</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control" id="expire_date" name="expire_date" value="">
                <p class="help-block">有效期天数（默认不过期）</p>
            </div>
        </div>
        <input type="hidden" name="type" class="ticket_type" id="ticket_type" value="<?php echo $type; ?>" >
        <div class="form-group ticket-money">
            <label for="money" class="col-sm-2 control-label"><span class="form-required">*</span>代金券面额</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control" id="money" name="money" value=""/>
                <p class="help-block"><span class="form-required">*</span>代金券面额设置之后不可修改</p>
            </div>
        </div>
        <div class="form-group get_way">
            <label class="col-sm-2 control-label"><span class="form-required">*</span>服务项目</label>
            <div class="col-md-6 col-sm-10">
                <select class="form-control" id="service_id" name="service_id">
                    <?php if(is_array($serverList) || $serverList instanceof \think\Collection || $serverList instanceof \think\Paginator): if( count($serverList)==0 ) : echo "" ;else: foreach($serverList as $key=>$v): ?>
                        <option value="<?php echo $v['id']; ?>"><?php echo $v['sname']; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
            <p class="help-block">服务项目</p>
        </div>
        <div class="form-group get_way">
            <label class="col-sm-2 control-label"><span class="form-required">*</span>获取方式</label>
            <div class="col-md-6 col-sm-10">
                <label class="radio-inline">
                    <input type="radio" name="get_way" value="1" > 售卖
                </label>
                <label class="radio-inline">
                    <input type="radio" name="get_way" value="2" checked="checked"> 兑换
                </label>
            </div>
            <p class="help-block">选择获取服务券的方式</p>
        </div>
        <div class="form-group">
            <label for="integral_price" class="col-sm-2 control-label"><span class="form-required">*</span>消耗</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control" id="integral_price" name="integral_price" value="">
                <p class="help-block">价值积分或现金</p>
            </div>
        </div>
        <br>
        <div class="form-group">
            <label class="col-sm-2 control-label"><span class="form-required">*</span>上架门店</label>
            <div class="col-md-6 col-sm-10">
                <?php if($shop_list != ''): foreach($shop_list as $v): ?>
                        <input type="checkbox" name="shop_id" value="<?php echo $v['id']; ?>"> <?php echo $v['name']; endforeach; endif; ?>
                <p class="help-block">选择上架门店</p>
            </div>
        </div>
        <br>
        <div class="form-group">
            <label class="col-sm-2 control-label"><span class="form-required">*</span>是否上架</label>
            <div class="col-md-6 col-sm-10">
                <label class="radio-inline">
                    <input type="radio" name="status" value="1" > 上架
                </label>
                <label class="radio-inline">
                    <input type="radio" name="status" value="0" checked="checked"> 下架
                </label>
            </div>
        </div>
        <br>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button id="sure" class="btn btn-primary "><?php echo lang('SAVE'); ?></button>
            </div>
        </div>
        <br>

    </div>
</div>


<div style="display:none">
    <script src="__STATIC__/js/admin.js"></script>
    <script type="text/javascript" src="__STATIC__/js/webuploader/webuploader.min.js"></script>
</div>
<script type="text/javascript">
    $(function() {
    //图片上传
        $host = 'http://' + "<?php echo $_SERVER['HTTP_HOST']; ?>"
        var hosts = "<?php echo $host; ?>";
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
        //上传成功获取图片路径
        uploader.on('uploadSuccess', function (file, res) {
            if(res.code == 200){
                layer.msg(res.msg, {icon: 1, time: 2000});
                $('#cover').val(res.data.path);
                $('#headimg').attr('src',hosts + res.data.path);
            }else{
                layer.msg(res.msg, {icon: 2, time: 2000});
            }
        });

        $('#sure').click(function () {
            var data = {
                name:'',
                cover:'',
                money:0,
                restrict_num:0,
                circulation:0,
                expire_date:0,
                integral_price:0,
                shop_id:[],
                get_way:2,
                service_id:0,
                type:1,
                status:0
            };
            $.each($('input:checkbox'),function(){
                if(this.checked){
                    data.shop_id.push($(this).val());
                }
            });
            data.name = $('#name').val();
            data.money = $('#money').val();
            data.cover = $('#cover').val();
            data.restrict_num = $('#restrict_num').val();
            data.circulation = $('#circulation').val();
            data.expire_date = $('#expire_date').val();
            data.type = $('#ticket_type').val();
            if (data.type == 2) {
                data.get_way = $('input:radio[name=get_way]:checked').val();
                data.service_id = $('#service_id').val();
            }
            data.integral_price = $('#integral_price').val();
            data.status = $('input:radio[name=status]:checked').val();
            if (data.name === ''){
                layer.msg('请输入券名称',{icon:0,time:1500});
                return false;
            }
            if (data.type == null){
                layer.msg('请选择券类型',{icon:0,time:1500});
                return false;
            }
            if (data.type === 2){
                if (data.get_way === 0){
                    layer.msg('请选择服务券的获取方式',{icon:0,time:1500});
                    return false;
                }
            }
            $.post("<?php echo url('admin/ticket/ticketaddorupdate'); ?>",{data},function (res) {
                if (res.code === 200){
                    layer.msg(res.msg,{icon:1,time:2000},function () {
                        parent.layer.close(parent.layer.getFrameIndex(window.name));
                    });
                }else{
                    layer.msg(res.msg,{icon:0,time:1500});
                }
            });
        });

        var ticket_type = $('#ticket_type').val();
        if (ticket_type == 2){
            $('.get_way').show();
            $('.ticket-money').hide();
        }else{
            $('.ticket-money').show();
            $('.get_way').hide();
        }
    });
</script>
</body>
</html>
