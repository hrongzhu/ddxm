<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:52:"themes/admin_simpleboot3/admin\shop\edit_detail.html";i:1554867181;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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
    <form action="<?php echo url('admin/shop/shopUpdate'); ?>" id="form" method="post" class="form-horizontal  margin-top-20 js-ajax-form" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name" class="col-sm-2 control-label"><span class="form-required">*</span>店铺名称</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control" id="name" name="name" value="<?php echo (isset($info['name']) && ($info['name'] !== '')?$info['name']:''); ?>">
                <p class="help-block">店铺名称</p>
            </div>
        </div>
        <div class="form-group">
            <label for="qrcode" class="col-sm-2 control-label">店铺二维码</label>
            <div class="col-md-6 col-sm-10">
                <p><img id="qrcode" src="<?php echo (isset($info['qrcode']) && ($info['qrcode'] !== '')?$info['qrcode']:''); ?>" alt="二维码" width="150"></p>
                <p class="help-block">店铺二维码请在创建后去生成</p>
            </div>
        </div>
        <div class="form-group">
            <label for="code" class="col-sm-2 control-label"><span class="form-required">*</span>店铺编码</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control" id="codes" name="codes" disabled="disabled" value="<?php echo (isset($info['code']) && ($info['code'] !== '')?$info['code']:''); ?>">
                <input type="hidden" class="form-control" id="code" name="code"  value="<?php echo (isset($info['code']) && ($info['code'] !== '')?$info['code']:''); ?>">
                <p class="help-block">店铺编号(系统自动分配)</p>
            </div>
        </div>
        <div class="form-group">
            <label for="mobile" class="col-sm-2 control-label"><span class="form-required">*</span>联系电话(固话)</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo (isset($info['mobile']) && ($info['mobile'] !== '')?$info['mobile']:''); ?>">
                <p class="help-block">联系电话</p>
            </div>
        </div>
        <div class="form-group">
            <label for="telephone" class="col-sm-2 control-label"><span class="form-required">*</span>手机号码</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control" id="telephone" name="telephone" value="<?php echo (isset($info['telephone']) && ($info['telephone'] !== '')?$info['telephone']:''); ?>">
                <p class="help-block">手机号码(接收消息的号码)</p>
            </div>
        </div>
        <div class="form-group">
            <label for="start_money" class="col-sm-2 control-label">满多少起送</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control" id="start_money" name="start_money" value="<?php echo (isset($info['start_money']) && ($info['start_money'] !== '')?$info['start_money']:''); ?>">
                <p class="help-block">满多少起送</p>
            </div>
        </div>
        <div class="form-group">
            <label for="deliver_price" class="col-sm-2 control-label">商家配送费</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control" id="deliver_price" name="deliver_price" value="<?php echo (isset($info['deliver_price']) && ($info['deliver_price'] !== '')?$info['deliver_price']:''); ?>">
                <p class="help-block">商家配送费</p>
            </div>
        </div>
        <div class="form-group">
            <label for="stime" class="col-sm-2 control-label">营业开始时间</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control js-bootstrap-datetime" id="stime" name="stime" value="<?php echo (isset($info['stime']) && ($info['stime'] !== '')?$info['stime']:''); ?>">
                <p class="help-block">营业开始时间</p>
            </div>
        </div>
        <div class="form-group">
            <label for="etime" class="col-sm-2 control-label">营业结束时间</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control js-bootstrap-datetime" id="etime" name="etime" value="<?php echo (isset($info['etime']) && ($info['etime'] !== '')?$info['etime']:''); ?>">
                <p class="help-block">营业结束时间</p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">店铺实景照片</label>
            <div id="filePicker">选择图片</div>
            <div class="col-sm-10">
                <div id="fileList" class="uploader-list" style="margin-left: 20%;">
                    <?php if(isset($info['mainPic'])): if(is_array($info['mainPic']) || $info['mainPic'] instanceof \think\Collection || $info['mainPic'] instanceof \think\Paginator): if( count($info['mainPic'])==0 ) : echo "" ;else: foreach($info['mainPic'] as $key=>$v): ?>
                            <div style="margin-left: 20px;float: left;">
                                <div>
                                    <img src="<?php echo (isset($host) && ($host !== '')?$host:''); ?><?php echo (isset($v) && ($v !== '')?$v:'http://dd.ddxm661.com/library/admin/images/defaulthead.png'); ?>" style="height:80px;width:120px;margin-bottom: 10px"  alt="">
                                    <input type="hidden" name="mainPic[]" value="<?php echo $v; ?>">
                                </div>
                                <div class="btn btn-warning" onclick="del($(this))" style="float: left;line-height: 100%">删除</div>
                            </div>
                        <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">所属加盟商</label>
            <div class="col-md-6 col-sm-10">
                <select class="form-control" name="account_id">
                    <!--<option value="">请选择</option>-->
                    <?php if($group != ''): foreach($group as $v): if(isset($info['account_id'])): ?>
                                <option  <?php echo $info['account_id']==$v->id?'selected = "selected"' : ''; ?> value="<?php echo $v->id; ?>"><?php echo $v->name; ?></option>
                                <?php else: ?>
                                <option value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                            <?php endif; endforeach; endif; ?>
                </select>
                <p class="help-block">所属加盟商</p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label"><span class="form-required">*</span><?php echo lang('店铺类型'); ?></label>
            <div class="col-md-6 col-sm-10">
                <select class="form-control" name="shop_type">
                	<?php if(isset($info['shop_type'])): ?>
                        <option <?php echo $info['shop_type']==1?'selected = "selected"' : ''; ?> value="1">直营店</option>
                        <option <?php echo $info['shop_type']==2?'selected = "selected"' : ''; ?>  value="2">合伙店</option>
                        <option <?php echo $info['shop_type']==3?'selected = "selected"' : ''; ?> value="3">加盟店</option>
                    <?php else: ?>
                        <option  value="1">直营店</option>
	                    <option  value="2">合伙店</option>
	                    <option  value="3">加盟店</option>
                    <?php endif; ?>

                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="sendExplain" class="col-sm-2 control-label"><span class="form-required">*</span>开启配送</label>
            <div class="col-md-6 col-sm-10">
                <!--<input type="text" class="form-control" id="sendExplain" name="sendExplain" value="<?php echo (isset($info['sendExplain']) && ($info['sendExplain'] !== '')?$info['sendExplain']:''); ?>">-->
                <input type="radio" name="is_delivery" value="1" <?php if(isset($info['is_delivery'])&&$info['is_delivery']==1): ?>checked<?php endif; ?>>是
                <input type="radio" name="is_delivery" value="0" <?php if(isset($info['is_delivery'])&&$info['is_delivery']==0): ?>checked<?php endif; ?>>否
                <p class="help-block">是否开启门店商品配送</p>
            </div>
        </div>
        <div class="form-group">
            <label for="sendExplain" class="col-sm-2 control-label">配送说明</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control" id="sendExplain" name="sendExplain" value="<?php echo (isset($info['sendExplain']) && ($info['sendExplain'] !== '')?$info['sendExplain']:''); ?>">
                <p class="help-block">配送说明</p>
            </div>
        </div>
        <div class="form-group">
            <label for="detail_address" class="col-sm-2 control-label"><span class="form-required">*</span>详细地址</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control" id="detail_address" name="detail_address" value="<?php echo (isset($info['detail_address']) && ($info['detail_address'] !== '')?$info['detail_address']:''); ?>">
                <p class="help-block">详细地址</p>
            </div>
        </div>
        <div class="form-group">
            <label for="location_address" class="col-sm-2 control-label"><span class="form-required">*</span> 定位地址</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control" id="location_address" name="location_address" value="<?php echo (isset($info['location_address']) && ($info['location_address'] !== '')?$info['location_address']:''); ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="location_address" class="col-sm-2 control-label"></label>
            <div class="col-md-6 col-sm-10">
                <iframe style="height: 450px;width: 100%;border-radius: 2px;border:1px solid grey;" id="mapPage" frameborder=0 src="http://apis.map.qq.com/tools/locpicker?search=1&type=1&key=JBXBZ-NBDRP-HQADR-LLJ5C-GI7MF-MFFO7&referer=myapp"></iframe>
            </div>
        </div>

        <div class="form-group">
            <label for="content" class="col-sm-2 control-label">商品详情</label>
            <div class="col-md-6 col-sm-10">
                <textarea name="content" id="content" cols="30" rows="10"><?php echo (isset($info['content']) && ($info['content'] !== '')?$info['content']:''); ?></textarea>
                <p class="help-block">商品详情</p>
            </div>
        </div>
        <br>
        <input type="hidden" name="lng" id="lng" value="<?php echo (isset($info['lng']) && ($info['lng'] !== '')?$info['lng']:''); ?>">
        <input type="hidden" name="lat" id="lat" value="<?php echo (isset($info['lat']) && ($info['lat'] !== '')?$info['lat']:''); ?>">
        <input type="hidden" name="id" value="<?php echo (isset($info['id']) && ($info['id'] !== '')?$info['id']:''); ?>">
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
        editorcontent.render('content');
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
        var reset_html = $('#price_modal').html();
        window.addEventListener('message', function (event) {
            var location = event.data;
            if (location && location.module == 'locationPicker') {
                //防止其他应用也会向该页面post信息，需判断module是否为'locationPicker'
                location_str = location.latlng;
                $("#location_address").val(location.poiaddress + '(' + location.poiname + ')');
                // $("#city").val(loc.cityname);
                $("#lat").val(location_str.lat);
                $("#lng").val(location_str.lng);
            }
        }, false);

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
                $html = '<div style="margin-left: 20px;float: left;">'+
                        '<div>'+
                        '<img src="'+ hosts + res.data.path+'"  style="height: 80px;width:120px;float: left;" alt="">'+
                                '<input type="hidden" name="mainPic[]" value="'+ res.data.path+'">'+
                        '</div>'+
                        '<div class="btn btn-warning" onclick="del($(this))" style="float: left">删除</div>'+
                        '</div>';
                $('#fileList').append($html);

            }else{
                layer.msg(res.msg, {icon: 2, time: 2000});
            }
        })

    //删除会员等级
        $('#price_modal').on('click','.delete_level',function(){
            var level_id = $(this).parent().attr('data_level_id');
            $('#price_table td.level'+level_id+'').remove();
            $('#price_table th.level'+level_id+'').remove();
        })

    //重设服务价格和会员等级
        $('#price_modal').on('click','#reset',function(){
            $('#price_modal').html(reset_html)
        })

    });


    function del(obj) {
        var link = obj.parent("div");
//        console.log(link);return;
        link.remove();
    }
</script>
</body>
</html>
