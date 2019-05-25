<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:54:"themes/admin_simpleboot3/admin\shop\worker_update.html";i:1554867178;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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
<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
<style type="text/css">
    body{ font-size:12px;}
    .l-table-edit {}
    .l-table-edit-td{ padding:4px;}
    .l-button-submit,.l-button-test{width:80px; float:left; margin-left:10px; padding-bottom:2px;}
    .l-verify-tip{ left:230px; top:120px;}
</style>
<div class="table wrap js-check-wrap">
    <!-- <form id="form" action="<?php echo url('shop/workerUpdate'); ?>" method="post" class="form-horizontal margin-top-20"> -->
    <div class="form-horizontal margin-top-20">
        <div class="form-group">
            <label for="workid" class="col-sm-2 control-label">工号</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control" disabled id="workid" name="workid" value="<?php echo (isset($info['workid']) && ($info['workid'] !== '')?$info['workid']:$workId); ?>">
                <p class="help-block">工号自动生成且不允许更改</p>
            </div>
        </div>
        <div class="form-group">
            <label for="name" class="col-sm-2 control-label">员工姓名</label>
            <div class="col-md-6 col-sm-10">
              <input type="text" class="form-control" id="name" name="name" value="<?php echo (isset($info['name']) && ($info['name'] !== '')?$info['name']:''); ?>">
              <p class="help-block">员工姓名</p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">员工头像</label>
            <div class="col-md-6 col-sm-10">
                <div id="fileList" class="uploader-list">
                    <img id="headImg" src="<?php echo isset($info['head'])?$host:''; ?><?php echo (isset($info['head']) && ($info['head'] !== '')?$info['head']:''); ?>" width="150" style="margin-bottom:5px;" alt="">
                </div>
                <input type="hidden" id="head" name="head" value="<?php echo (isset($info['head']) && ($info['head'] !== '')?$info['head']:''); ?>">
                <div id="filePicker">选择图片</div>
                <p class="help-block">推荐图片尺寸：400*400（比例相同即可）</p>
            </div>
        </div>
        <div class="form-group">
            <label for="mobile" class="col-sm-2 control-label">手机号</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo (isset($info['mobile']) && ($info['mobile'] !== '')?$info['mobile']:''); ?>">
                <p class="help-block">手机号</p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">所属店铺</label>
            <div class="col-md-6 col-sm-10">
                <select class="form-control" id="shop" name="sid">
                    <option value="">请选择</option>
                    <?php if($shopList != ''): foreach($shopList as $v): if($info != ''): ?>
                                <option  <?php echo $info['sid']==$v['id']?'selected = "selected"' : ''; ?> value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                                <?php else: ?>
                                <option value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                            <?php endif; endforeach; endif; ?>
                </select>
                <p class="help-block">所属店铺</p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">工种</label>
                <?php if(isset($info['types'])): ?>
                    <div class="col-md-6 col-sm-10">
                        <?php foreach($job as $vv): ?>
                            <label class="checkbox-inline">
                                <?php $job_checked=in_array($vv['id'],$work_job)?'checked="checked"':""; ?>
                                <input type="checkbox" name="type[]" <?php echo $job_checked; ?> value="<?php echo $vv['id']; ?>"><?php echo $vv['sname']; ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="col-md-6 col-sm-10" id="jobs">

                    </div>
                <?php endif; ?>
        </div>
        <br>
        <div class="form-group">
            <label for="lv" class="col-sm-2 control-label">职称等级</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control" id="lv" name="lv" value="<?php echo (isset($info['lv']) && ($info['lv'] !== '')?$info['lv']:''); ?>" placeholder="级别">
                <p class="help-block">职称等级</p>
            </div>
        </div>
        <div class="form-group">
            <label for="pay" class="col-sm-2 control-label">额外收费</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control" id="pay" name="pay" value="<?php echo (isset($info['pay']) && ($info['pay'] !== '')?$info['pay']:''); ?>">
                <p class="help-block">联系电话</p>
            </div>
        </div>
        <div class="form-group">
            <label for="remark" class="col-sm-2 control-label">显示备注</label>
            <div class="col-md-6 col-sm-10">
                <input type="text"  class="form-control" id="remark" name="remark" value="<?php echo (isset($info['remark']) && ($info['remark'] !== '')?$info['remark']:''); ?>">
                <p class="help-block">商家配送费</p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">所属加盟商</label>
            <div class="col-md-6 col-sm-10">
                <select class="form-control" name="pid" id="pid">
                    <option value="">请选择</option>
                    <?php if($group != ''): foreach($group as $v): if($info != ''): ?>
                                <option  <?php echo $info['pid']==$v->id?'selected = "selected"' : ''; ?> value="<?php echo $v->id; ?>"><?php echo $v->name; ?></option>
                                <?php else: ?>
                                <option value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                            <?php endif; endforeach; endif; ?>
                </select>
                <p class="help-block">所属加盟商</p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">人员详情</label>
            <div class="col-md-6 col-sm-10">
                <textarea name="detail" id="detail" cols="30" rows="10"><?php echo (isset($info['detail']) && ($info['detail'] !== '')?$info['detail']:''); ?></textarea>
                <p class="help-block">人员详情</p>
            </div>
        </div>
        <input type="hidden"  class="form-control" id="id" name="id" value="<?php echo (isset($info['id']) && ($info['id'] !== '')?$info['id']:''); ?>">
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button id="save" class="btn btn-primary"><?php echo lang('SAVE'); ?></button>
            </div>
        </div>
    </div>
</div>
<div style="display:none">
    <script src="__STATIC__/js/admin.js"></script>
    <script type="text/javascript" src="__STATIC__/js/webuploader/webuploader.min.js"></script>
    <script type="text/javascript" src="__STATIC__/js/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" src="__STATIC__/js/ueditor/ueditor.all.min.js"></script>
</div>
<script type="text/javascript">
    //编辑器路径定义
    var editorURL = GV.WEB_ROOT;
</script>
<script type="text/javascript">
    $(function () {

        editorcontent = new baidu.editor.ui.Editor();
        editorcontent.render('detail');
        try {
            editorcontent.sync();
        } catch (err) {
        }

        $('.btn-cancel-thumbnail').click(function () {
            $('#thumbnail-preview').attr('src', '__TMPL__/public/assets/images/default-thumbnail.png');
            $('#thumbnail').val('');
        });
    });
</script>
<script type="text/javascript">
    $(function() {
    //图片上传
        $host = 'http://' + "<?php echo $_SERVER['HTTP_HOST']; ?>"
        var hosts = "<?php echo $host; ?>";
        var uploader = WebUploader.create({
            auto: true,
            swf: 'https://cdn.staticfile.org/webuploader/0.1.5/Uploader.swf',
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
                $('#head').val(res.data.path);
                $('#headImg').attr('src',hosts + res.data.path);
            }else{
                layer.msg(res.msg, {icon: 2, time: 2000});
            }
        })
    });
    //判断工种
    $(function () {
        $('#shop').change(function () {
            var shop_id = $(this).val();
            $.post("<?php echo url('getShopJob'); ?>",{shop_id:shop_id},function (res) {
                if (res.code === 200){
                    var checkbox = '';
                    $.each(res.data, function(i,v) {
                        checkbox += `<label class="checkbox-inline">
                                <input type="checkbox" name="type[]" value="${v.id}">${v.sname}
                            </label>`;
                    });
                    $('#jobs').html(checkbox);
                } else{
                    layer.msg(res.msg,{icon:1,time:1500});
                }
            })
        });

        $('#save').click(function () {
            var data = {
                id:0,
                workid:'',
                name:'',
                head:'',
                type:[],
                pid:0,
                sid:0,
                mobile:0,
                lv:'',
                remark:'',
                pay:'',
                detail:''
            };
            $.each($('input:checkbox'),function(){
                if(this.checked){
                    data.type.push($(this).val());
                }
            });
            data.id = $('#id').val();
            data.workid = $('#workid').val();
            data.name = $('#name').val();
            data.pid = $('#pid').val();
            data.head = $('#head').val();
            data.sid = $('#shop').val();
            data.mobile = $('#mobile').val();
            data.lv = $('#lv').val();
            data.remark = $('#remark').val();
            data.pay = $('#pay').val();
            data.detail = $('#detail').val();
            if (data.name === ''){
                layer.msg('请输入员工姓名',{icon:0,time:1500});
                return false;
            }
            if (data.type == null){
                layer.msg('请选择员工工种',{icon:0,time:1500});
                return false;
            }
            if (data.sid == ''){
                layer.msg('请选择员工门店',{icon:0,time:1500});
                return false;
            }
            if (data.mobile == ''){
                if (data.get_way === 0){
                    layer.msg('请输入员工手机号',{icon:0,time:1500});
                    return false;
                }
            }
            $.post("<?php echo url('shop/workerUpdate'); ?>",{data},function (res) {
                if (res.code === 200){
                    layer.msg(res.msg,{icon:1,time:2000},function () {
                        window.parent.location.reload();
                        // parent.layer.close(parent.layer.getFrameIndex(window.name));
                    });
                }else{
                    layer.msg(res.msg,{icon:0,time:1500});
                }
            });
        });
    })

</script>
</body>
</html>
