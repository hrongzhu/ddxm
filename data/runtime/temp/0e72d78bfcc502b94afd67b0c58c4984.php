<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:50:"themes/admin_simpleboot3/admin\item\item_edit.html";i:1554867192;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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

<style type="text/css" media="screen">
	.wrap .upload-img {}
	.wrap .upload-img .upload-img-list {
		width:  100%;
		display: flex;
		flex-direction: row;
		align-items: center;
		margin-bottom: 10px;
	}
	.wrap .upload-img .upload-img-list div {
		width: 100px;
		height:  100px;
		position: relative;
		margin: 10px;
		background-color: #fff;text-decoration:
	}
	.wrap .upload-img .upload-img-list div i {
		position: absolute;
		top: -8px;
		right: -3px;
		font-size: 20px;
		color: red;
		background: #f5f5f5;
		border-radius: 100%;
		z-index: 99;
	}
	.wrap .upload-img .upload-img-list div img {
		width: 100%;
		height: 100%;
	}
	.wrap .upload-img .calc-upload button {
		margin-top: 10px;
	}
</style>
</head>
<body>
<div class="wrap">
	<ul class="nav nav-tabs">
		<ul class="nav nav-tabs">
			<li><a href="<?php echo url('Item/item_list'); ?>">商品列表</a></li>
			<li class="active"><a href="<?php echo url('Item/item_add'); ?>">商品添加</a></li>
		</ul>
	</ul>
	<form method="post" class="form-horizontal margin-top-20" action="javascript:void(0)">
		<div class="form-group">
			<label for="input-name" class="col-sm-2 control-label"><span class="form-required">*</span>商品名称</label>
			<div class="col-md-6 col-sm-10">
				<input type="text" class="form-control" id="title" name="title" value="<?php echo $item['title']; ?>">
			</div>
		</div>
		<div class="form-group">
			<label for="input-name" class="col-sm-2 control-label"><span class="form-required">*</span>商品库</label>
			<div class="col-md-6 col-sm-10 choose-saletype">
				<?php if($item['item_type']==1): ?>
					<input type="checkbox" name="item-type" value="1" id="online" checked>
					<label for="online">线上商城</label>
					<input type="checkbox" name="item-type" value="2" id="offline">
					<label for="offline">门店商品</label>
				<?php endif; if($item['item_type']==2): ?>
					<input type="checkbox" name="item-type" value="1" id="online">
					<label for="online">线上商城</label>
					<input type="checkbox" name="item-type" value="2" id="offline" checked>
					<label for="offline">门店商品</label>
				<?php endif; if($item['item_type']==3): ?>
					<input type="checkbox" name="item-type" value="1" id="online" checked>
					<label for="online">线上商城</label>
					<input type="checkbox" name="item-type" value="2" id="offline" checked>
					<label for="offline">门店商品</label>
				<?php endif; ?>
			</div>
		</div>
		<div class="form-group online">
			<label for="input-name" class="col-sm-2 control-label"><span class="form-required">*</span>选择专区</label>
			<div class="col-md-6 col-sm-10">
				<select  name="type_id" class="form-control" id="area">
					<option value="0">请选择分区</option>
					<?php if(is_array($part_list) || $part_list instanceof \think\Collection || $part_list instanceof \think\Paginator): if( count($part_list)==0 ) : echo "" ;else: foreach($part_list as $key=>$vo): ?>
						<option value="<?php echo $vo['id']; ?>" <?php if($item['type_id']==$vo['id']): ?>selected<?php endif; ?>><?php echo $vo['title']; ?></option>
					<?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label for="input-parent_id" class="col-sm-2 control-label"><span class="form-required">*</span>商品分类</label>
			<div class="col-md-6 col-sm-10">
				<select class="form-control selectboy" id="cat_parent">
					<option value="0">请选择分类</option>
					<?php if(is_array($Ylian_list) || $Ylian_list instanceof \think\Collection || $Ylian_list instanceof \think\Paginator): if( count($Ylian_list)==0 ) : echo "" ;else: foreach($Ylian_list as $key=>$vo): ?>
						<option value="<?php echo $vo['id']; ?>" <?php if($item['pid']==$vo['id']): ?>selected<?php endif; ?>><?php echo $vo['cname']; ?></option>
					<?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label for="input-parent_id" class="col-sm-2 control-label"><span class="form-required">*</span>商品二级分类</label>
			<div class="col-md-6 col-sm-10">
				<select class="form-control selectson" name="type" id="cat_son">
					<option value="0">请选择分类</option>
					<?php if(is_array($second_type_list) || $second_type_list instanceof \think\Collection || $second_type_list instanceof \think\Paginator): if( count($second_type_list)==0 ) : echo "" ;else: foreach($second_type_list as $key=>$vo): ?>
						<option value="<?php echo $vo['id']; ?>" <?php if($item['type']==$vo['id']): ?>selected<?php endif; ?>><?php echo $vo['cname']; ?></option>
					<?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</div>
		</div>
		<div class="form-group online">
			<label for="lv_id" class="col-sm-2 control-label"><span class="form-required">*</span>适用税率</label>
			<div class="col-md-6 col-sm-10">
				<select  name="lvid" class="form-control" id="lv_id">
					<option value="0">商品没有税收</option>
					<?php if(is_array($lv_list) || $lv_list instanceof \think\Collection || $lv_list instanceof \think\Paginator): if( count($lv_list)==0 ) : echo "" ;else: foreach($lv_list as $key=>$vo): ?>
						<option value="<?php echo $vo['id']; ?>" <?php if($item['lvid']==$vo['id']): ?>selected<?php endif; ?>><?php echo $vo['title']; ?></option>
					<?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</div>
		</div>
		<div class="form-group online">
			<label class="col-sm-2 control-label"><span class="form-required">*</span>上下架</label>
			<div class="col-md-6 col-sm-10">
				<label class="radio-inline">
					<input type="radio" name="status" value="1" <?php if($item['status']==1): ?>checked<?php endif; ?>> 上架
				</label>
				<label class="radio-inline">
					<input type="radio" name="status" value="0" <?php if($item['status']==0): ?>checked<?php endif; ?>> 下架
				</label>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label"><span class="form-required">*</span>是否控价</label>
			<div class="col-md-6 col-sm-10">
				<label class="radio-inline">
					<input type="radio" name="is_control" value="1" <?php if($item['is_price_control']==1): ?>checked<?php endif; ?>> 是
				</label>
				<label class="radio-inline">
					<input type="radio" name="is_control" value="0"  <?php if($item['is_price_control']==0): ?>checked<?php endif; ?>> 否
				</label>
			</div>
		</div>
		<div class="form-group">
			<label for="price" class="col-sm-2 control-label"><span class="form-required">*</span>售价</label>
			<div class="col-md-6 col-sm-10">
				<input type="text" name="price" id="price" value="<?php echo $item['price']; ?>"><span id="warning" class="hidden" style="color: red;margin-left: 20px">控价商品，请谨慎修改价格！</span>
			</div>
		</div>
		<div class="form-group offline">
			<label for="input-name" class="col-sm-2 control-label"><span class="form-required">*</span>采购单价</label>
			<div class="col-md-6 col-sm-10">
				<input type="text" name="cg_price" id="cg_price" value="<?php echo $item['cg_standard_price']; ?>">
			</div>
		</div>
		<div class="form-group offline">
			<label for="input-name" class="col-sm-2 control-label"><span class="form-required">*</span>门店单价</label>
			<div class="col-md-6 col-sm-10">
				<input type="text" name="md_price" id="md_price" value="<?php echo $item['md_standard_price']; ?>">
			</div>
		</div>
		<div class="form-group offline">
			<label for="input-name" class="col-sm-2 control-label"><span class="form-required">*</span>商品条形码</label>
			<div class="col-md-6 col-sm-10">
				<input type="text" name="code" id="code" value="<?php echo $item['bar_code']; ?>">
			</div>
		</div>
		<div class="form-group offline">
			<label for="input-name" class="col-sm-2 control-label"><span class="form-required">*</span>库存预警值</label>
			<div class="col-md-6 col-sm-10">
				<input type="text" name="code" id="stock_alert" value="<?php echo $item['stock_alert']; ?>">
			</div>
		</div>
		<div class="form-group online">
			<label for="input-name" class="col-sm-2 control-label"><span class="form-required">*</span>商品图片上传</label>
			<div class="col-md-6 col-sm-10 upload-img">
				<!-- 上传的图片列表 -->
				<div class="upload-img-list">
					<?php if(is_array($item['pics']) || $item['pics'] instanceof \think\Collection || $item['pics'] instanceof \think\Paginator): if( count($item['pics'])==0 ) : echo "" ;else: foreach($item['pics'] as $key=>$v): ?>
						<div>
						<!-- 图片地址隐藏域 -->
						<input type="hidden" value="<?php echo $v; ?>">
						<i class="glyphicon glyphicon-remove-sign remove-img"></i>
						<img src="http://picture.ddxm661.com/<?php echo $v; ?>" alt="" >
						</div>
					<?php endforeach; endif; else: echo "" ;endif; ?>
				</div>
				<div class="calc-upload">
					<input type='file' id='file' name='file' ltype='text' />
					<button type='button' class="btn btn-success" onclick='attr_img(this)'>上传</button>
				</div>
			</div>
		</div>
		<div class="form-group online">
			<label for="input-name" class="col-sm-2 control-label"><span class="form-required">*</span>商品详情</label>
			<div class="col-md-6 col-sm-10">
				<script type="text/plain" id="content" name="content"><?php echo $item['content']; ?></script>
			</div>
		</div>
		<div class="form-group">
			<input type="hidden" id="item-id" value="<?php echo $item['id']; ?>">
			<input type="hidden" id="item-type" value="<?php echo $item['item_type']; ?>">
			<div class="col-sm-offset-2 col-sm-10">
				<button id="submit" class="btn btn-primary">保存</button>
			</div>
		</div>
	</form>
</div>
<script src="__STATIC__/js/admin.js"></script>
<script type="text/javascript">
    //编辑器路径定义
    var editorURL = GV.WEB_ROOT;
</script>
<script type="text/javascript" src="__STATIC__/js/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="__STATIC__/js/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript">
        var uploadImgList = [];
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

    $(function(){
        if ($('#item-type').val()==2) {
            $('div .online').addClass('hidden')
        }
    })

	//控价提示
	$('#price').focus(function(){
		if($("input[name='is_control']:checked").val()==1){
			$('#warning').removeClass('hidden')
		}
		if($("input[name='is_control']:checked").val()==0){
			$('#warning').addClass('hidden')
		}
	})

    // 多图上传
    function more_upload(){
        var i = 0;
        var s = '';
        $("img[id=mainPic]").each(function(n,e){
            i++;
        })
        var formData = new FormData();
        upfile = $("#files")[0].files;
        var ii = 1;
        $(upfile).each(function (n, e) {
            i++;
            if(i>5){
                alert("上传图片不能超过5张");
                clearInterval(s);
                return false;
            }
            var s = setTimeout(function () {
                formData.append('file', $(e)[0]);
                upload_img(2, formData);
            }, ii);
            ii += 1000;
        });
    }
    // 上传图片
    function upload_img(id,formData){

        var file =  $("#lfile").val();

        if((file =='' || file == null) && id==1){
            alert("请选择上传文件");
            return false;
        }else{
            var upfile = '';
            if(id==1){
                var formData = new FormData();
                upfile = $("#lfile")[0].files[0];
                formData.append('file',upfile);
            }
            $.ajax({
                url: "<?php echo url('Item/uploadTest'); ?>",
                type: "POST",
                data: formData,
                dataType:'json',
                processData : false, // 不处理发送的数据，因为data值是Formdata对象，不需要对数据做处理
                contentType : false, // 不设置Content-type请求头
                success: function(data) {
                    console.log(data);return;
                    if(data.success==1){
                        // $(".imgList").append($(".imgList>div:first-child").clone());
                        if(id==1){
                            $("#thumb").val(data.msg);
                            $("#showThumb").attr('src',data.msg);
                        }else{
                            var img = '<div><img src="'+data.msg+'" id="mainPic" style="width:60px;height:100px;">';
                            img += '<input type="hidden" name="mainPic[]" value="'+data.msgurl+'"><a href="javascript:;" onclick="moveImg(this)">删除</a></div>'
                            $("#showPic").before(img);
                            $("#files").val('');
                            // $("#mainPic").val(data.msg);
                            // $("#showPic").attr('src',data.msg);
                        }
                    }else{
                        alert(data.msg);
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert("上传失败，请检查网络后重试");
                }
            });
        }
    }
    function moveImg(str){
        $(str).parent().remove();
    }
    //提交表单
    $('#submit').click(function(){
        var id = $("#item-id").val();
        var title = $("input[name='title']").val();
        var item_type = [];
        var area = $("#area").val();
        var cat_id = $("#cat_son").val();
        var lv_id = $("#lv_id").val();
        var status = $("input[name='status']:checked").val();
        var price = $("#price").val();
        var cg_price = $('#cg_price').val();
        var md_price = $('#md_price').val();
        var code = $('#code').val();
        var stock_alert = $('#stock_alert').val();
        var is_control = $("input[name='is_control']:checked").val();
        var pics = []
		$('.upload-img-list div').each(function(index, value){
            pics.push($(value).find('input').val())
		})
        var content = editorcontent.getContent();
        if(title==''){
            layer.msg('商品名称为必填项!',{'icon':2});return;
        }
        if(cat_id==0){
            layer.msg('必须为商品指定一个二级分类!',{'icon':2});return;
        }
        if(price==''||price<=0){
            layer.msg('商品价格不能为空且必须大于0!',{'icon':2});return;
        }
        $("input[name='item-type']:checked").each(function(k,v){
            item_type[k] = $(this).val();
        })
        if(item_type.length==0){
            layer.msg('必须至少选择一个商品库!',{'icon':2});return;
        }
        // if(item_type.indexOf('2')!=-1){
        //     if($('#md_price').val()==''||$('#md_price').val()==0||$('#cg_price').val()==''||$('#cg_price').val()==0||$('#code').val()==''){
        //         layer.msg('门店商品必须填写采购单价、门店单价及条形码',{'icon':2});return;
        //     }
        // }
        $.post("<?php echo url('Item/item_addPost'); ?>",{
            'title':title,'item_type':item_type,'area':area,'cat_id':cat_id,'lv_id':lv_id,
            'status':status,'price':price,'cg_price':cg_price,'md_price':md_price,'code':code,
            'is_priceContorl':is_control,'pics':pics,'content':content,'id':id,'stock_alert':stock_alert
        },function(res){
            if(res.code==1){
                layer.msg('操作成功',{'icon':1},function(res){
                    var index = parent.layer.getFrameIndex(window.name);
                    parent.layer.close(index);
                })
            } else {
                layer.msg(res.data,{'icon':2});
            }
        },'JSON')
    })

    // 属性上传图片 不删
    function attr_img(str){
        console.log(uploadImgList);
        var file =  $(str).parent().find("#file").val();
        if(file =='' || file == null){
            layer.msg("请选择上传文件", {offset: '100px'});
        }else{
            var upfile = '';
            upfile = $(str).parent().find("#file")[0].files[0];
            var formData = new FormData();
            formData.append('file',upfile);
            $.ajax({
                url: "<?php echo url('Item/uploadToQiniu'); ?>",
                type: "POST",
                data: formData,
                dataType:'json',
                processData : false, // 不处理发送的数据，因为data值是Formdata对象，不需要对数据做处理
                contentType : false, // 不设置Content-type请求头
                success: function(data) {
                    console.log(data);
                    if(data.err ==0){
                        // 添加一张图片
                        $('.upload-img-list').append(
                            `<div>
									<input type="hidden" value="`+ data.data.key +`">
									<i class="glyphicon glyphicon-remove-sign remove-img"></i>
									<img src="http://picture.ddxm661.com/`+ data.data.key +`" alt="">
		                    	</div>`
                        )
                        // 保存后台传的图片地址
                        // uploadImgList.push(data.data.key)
                        // 清除掉选择过的文件
                        var origin = $("#file")
                        origin.after(origin.clone().val(""));
                        origin.remove();
                        console.log(uploadImgList)
                    }else{
                        layer.msg(data.msg, { offset: '100px' });
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    layer.msg("上传失败，请检查网络后重试", { offset: '100px' });
                }
            });
        }
    }

    // 干掉上传过的图片 不删
    $(document).on('click', '.upload-img-list .remove-img' , function () {
        var that = this
        var index = $('.upload-img-list .remove-img').index(that) + 1;//获取当前点击的i下标
        var ind = layer.load(1);
        $.post("<?php echo url('Item/delelteImage'); ?>",{
            'key': $(that).prev().val(),
			'id':$('#item-id').val()
        },function(res){
            if(res.code==1){
                $(that).parent().remove()
            }else {
                layer.msg('删除失败请重试',{'icon':2})
            }
            layer.close(ind);
        })
    })

    $(function(){
        $('.selectpid').change(function(){
            var pid=$('.selectpid').val();
            //console.log(pid);
            if(pid==0)
            {
                var option = "<option>请选择分类</option>";
                $(".selectboy").html(option);
            }else{
                $.ajax({
                    url:"<?php echo url('Item/itemYlian'); ?>",
                    type:"post",
                    dataType: 'text',
                    data: {pid: pid},
                    success:function(data){
                        var res = $.parseJSON(data);
                        var option = "<option value='0'>请选择分类</option>";
                        $.each(res,function(k,v){
                            console.log(v.id);
                            option += "<option value='"+v.id+"'>"+v.cname+"</option>";
                        });
                        $(".selectboy").html(option);
                    }
                })
            }
        })
    })
    $(function(){
        $('.selectboy').change(function(){
            var pid=$('.selectboy').val();
            console.log(pid);
            if(pid==0)
            {
                var option = "<option>请选择分类</option>";
                $(".selectson").html(option);
                // 清除属性选择区域 attr-box
                if (!$('.attr-box').hasClass('hidden')) {
                    $('.attr-box').addClass('hidden')
                }
            }else{
                // 显示属性选择区域 attr-box
                if ($('.attr-box').hasClass('hidden')) {
                    $('.attr-box').removeClass('hidden')
                }
                $.ajax({
                    url:"<?php echo url('Item/itemTlian'); ?>",
                    type:"post",
                    dataType: 'text',
                    data: {pid: pid},
                    success:function(data){
                        var res = $.parseJSON(data);
                        /*var selecthead="<select  name='sid' class='form-control' id='input-parent_id'><option value='0'>请选择</option>";
                        var selectfoot="</select>";*/
                        console.log(res);
                        var option = "<option value='0'>请选择分类</option>";
                        $.each(res,function(k,v){
                            console.log(v.id);
                            option += "<option value='"+v.id+"'>"+v.cname+"</option>";
                            /*$.each(v.children,function(ke,va){
                                if(v.id==va.pid){
                                    option += "<option value='"+va.id+"'>"+va.count+va.cname+"</option>";
                                }
                                $.each(va.children,function(key,val){
                                    if(va.id==val.pid){
                                        option += "<option value='"+val.id+"'>"+val.count+val.cname+"</option>";
                                    }
                                });
                            });*/
                        });
                        //var html=selecthead+med+selectfoot;
                        $(".selectson").html(option);
                        /*alert(html);*/
                    }
                })
            }
        })
    })

    // 商品销售渠道选择
    $(document).on('change', '.choose-saletype > input', function () {
        // 先看当前单选框的选中状态
        var that = this
        if ($(that).attr('checked') !== 'checked') {
            $(that).attr('checked', 'checked')
            if ($(that).val() === '1') {  // 线上
                $('.online').each(function (index, value) {
                    if ($(value).hasClass('hidden')) {
                        $(value).removeClass('hidden')
                    }
                })
            } else {   // 门店
                $('.offline').each(function (index, value) {
                    if ($(value).hasClass('hidden')) {
                        $(value).removeClass('hidden')
                    }
                })
            }
        } else {
            $(that).removeAttr('checked')
            if ($(that).val() === '1') {  // 线上
                $('.online').each(function (index, value) {
                    if (!$(value).hasClass('hidden')) {
                        $(value).addClass('hidden')
                    }
                })
            } else {   // 门店
                $('.offline').each(function (index, value) {
                    if (!$(value).hasClass('hidden')) {
                        $(value).addClass('hidden')
                    }
                })
            }
        }
        console.log($(this).val())
    })
</script>
</body>
</html>
