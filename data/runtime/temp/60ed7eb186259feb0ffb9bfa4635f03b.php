<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:50:"themes/admin_simpleboot3/admin\card\card_edit.html";i:1554867191;s:45:"themes/admin_simpleboot3/public\ptheader.html";i:1554867210;s:45:"themes/admin_simpleboot3/public\ptfooter.html";i:1554867211;}*/ ?>
<!DOCTYPE html>
<html lang="zh-cn">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="renderer" content="webkit">
		<title>订单管理 - 订单详情</title>
        <link rel="stylesheet" href="__STATIC__/pintuer/pintuer.css">
        <link href="/plugins/layer/2.4/skin/layer.css" rel="stylesheet">
		<script src="__TMPL__/public/assets/js/jquery-1.10.2.min.js"></script>
	</head>
	<body>
		<div class="layout" style="min-height: 980px;">
			<div class="container">

<link rel="stylesheet" type="text/css" href="__STATIC__/js/webuploader/webuploader.css">
<style type="text/css">
    body{ font-size:12px;}
    .l-table-edit {}
    .l-table-edit-td{ padding:4px;}
    .l-button-submit,.l-button-test{width:80px; float:left; margin-left:10px; padding-bottom:2px;}
    .l-verify-tip{ left:230px; top:120px;}
</style>

<div class="panel margin-top">
    <div class="panel-head bg-main">
        <span>编辑服务卡</span>
    </div>
    <div  class="panel-body">
        <div class="form-x">
            <input type="hidden" class="input" id="ids" value="<?php echo (isset($info['id']) && ($info['id'] !== '')?$info['id']:0); ?>">
            <div class="form-group">
                <label for="name">券名称</label>
                <label>
                    <input type="text" class="input" id="name" name="name" value="<?php echo (isset($info['name']) && ($info['name'] !== '')?$info['name']:''); ?>" data-validate="required:请填写内容">
                </label>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><span class="form-required">*</span>券封面</label>
                <div class="col-md-6 col-sm-10" id="filePicker">+ 选择封面</div>
                <div class="col-sm-10">
                    <div id="fileList" class="uploader-list">
                        <div>
                            <img src="<?php echo (isset($host) && ($host !== '')?$host:''); ?><?php echo (isset($info['cover']) && ($info['cover'] !== '')?$info['cover']:''); ?>" id="headimg" style="height:80px;margin-bottom: 10px" alt="">
                            <input type="hidden" id="cover" name="cover" value="<?php echo (isset($info['cover']) && ($info['cover'] !== '')?$info['cover']:''); ?>">
                        </div>
                    </div>
                </div>
                <p><span class="text-red">建议尺寸 680px * 350px</span></p>
            </div>
            <div class="form-group">
                <label for="restrict_num" class="col-sm-2 control-label">单人限购数</label>
                <div class="col-md-6 col-sm-10">
                    <input type="text" class="input" id="restrict_num" name="restrict_num" value="<?php echo (isset($info['restrict_num']) && ($info['restrict_num'] !== '')?$info['restrict_num']:0); ?>" data-validate="required:请填写数字,znumber:请输入不含负数的数字">
                    <p class="help-block">单人限购数（默认不限）</p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 text-dot">* 服务卡类型</label><br>
                <div class="button-group border-blue radio">
                    <label class="button radius-rounded <?php echo $info['type']==1?'active':''; ?>">
                        <input type="radio" name="type" <?php echo $info['type']==1?"checked":""; ?> value="1" > 包月卡
                    </label>
                    <label class="button radius-rounded <?php echo $info['type']==2?'active':''; ?>">
                        <input type="radio" name="type" <?php echo $info['type']==2?"checked":""; ?> value="2"> 次卡
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label for="money" class="col-sm-2 control-label">金额</label>
                <div class="col-md-6 col-sm-10">
                    <input type="text" class="input" id="money" name="money" value="<?php echo (isset($info['money']) && ($info['money'] !== '')?$info['money']:''); ?>" data-validate="required:请填写数字,zfloat:请输入不含负数的数字"/>
                </div>
            </div>
            <!-- 次卡显示内容 -->
            <div class="select-once">
                <div class="form-group">
                    <label>服务项目</label><br/>
                    <table class="table table-hover table-bordered" id="select-service">
                        <tr class="text-blue">
                            <th>服务项目</th><th>包含服务次数</th><th>本服务总金额</th><th><button class="button border-sub add-line" onclick="addtr()">+</button></th>
                        </tr>
						<tbody id="server-price-list">
                            <?php if(!empty($info['server_list'])): if(is_array($info['server_list']) || $info['server_list'] instanceof \think\Collection || $info['server_list'] instanceof \think\Paginator): $i = 0; $__LIST__ = $info['server_list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?>
                                    <tr>
                                        <td>
                                            <select class="input">
                                                <?php if($serverList != ''): foreach($serverList as $v): ?>
                                                        <option <?php echo $vv['id']==$v['id']?"selected":""; ?> value="<?php echo $v['id']; ?>"><?php echo $v['sname']; ?></option>
                                                    <?php endforeach; endif; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="input" value="<?php echo $vv['num']; ?>" onchange="edit_num(this)">
                                        </td>
                                        <td><input type="text" class="input" onchange="edit_money(this)" value="<?php echo $vv['money']; ?>"></td>
        								<td><button class="button bg-dot del-line" onclick="deltr(this)">删除</button></td>
        							</tr>
                                <?php endforeach; endif; else: echo "" ;endif; else: ?>
                                <tr>
                                    <td>
                                        <select class="input" >
                                            <?php if($serverList != ''): foreach($serverList as $v): ?>
                                                    <option value="<?php echo $v['id']; ?>"><?php echo $v['sname']; ?></option>
                                                <?php endforeach; endif; ?>
                                        </select>
                                    </td>
                                    <td><input type="text" class="input" value="1" onchange="edit_num(this)"></td>
                                    <td><input type="text" class="input" onchange="edit_money(this)" value="0" ></td>
    								<td><button class="button bg-dot del-line" onclick="deltr(this)">删除</button></td>
    							</tr>
                            <?php endif; ?>

						</tbody>
					</table>
                </div>
            </div>
            <!-- 包月卡显示内容 -->
            <div class="select-more">
                <div class="form-group">
                    <label for="expire_month" class="col-sm-2 control-label">有效月数</label>
                    <div class="col-md-6 col-sm-10">
                        <input type="text" class="input" id="expire_month" name="expire_date" value="<?php echo $info['expire_month']; ?>" data-validate="required:请填写数字,plusinteger:请输入大于0的数字">
                    </div>
                </div>
                <div class="form-group">
                    <label>服务项目</label><br/>
                    <div class="button-group border-blue checkbox">
                        <?php if($serverList != ''): foreach($serverList as $vs): $service_id_checked=in_array($vs['id'],$service_ids)?"checked":""; ?>
                                <label class="button <?php echo !empty($service_id_checked)?'active':''; ?>">
                            		<input name="service_id" value="<?php echo $vs['id']; ?>" <?php echo $service_id_checked; ?> type="checkbox"><?php echo $vs['sname']; ?>
                            	</label>
                            <?php endforeach; endif; ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>上架门店</label>
                <div class="button-group border-blue checkbox" id="shop_list">

                </div>
            </div>
            <br>
            <div class="form-group">
                <label class="col-sm-2 control-label">服务卡状态</label><br>
                <div class="button-group border-blue radio">
                    <label class="button <?php echo $info['status']==1?'active':''; ?>">
                        <input type="radio" <?php echo $info['status']==1?'checked':''; ?> name="status" value="1" > 上架
                    </label>
                    <label class="button <?php echo $info['status']==0?'active':''; ?>">
                        <input type="radio" <?php echo $info['status']==0?'checked':''; ?> name="status" value="0"> 下架
                    </label>
                </div>
            </div>
            <br>
            <div class="form-group">
                <label class="col-sm-2 control-label">线上商城是否显示</label><br>
                <div class="button-group border-blue radio">
                    <label class="button <?php echo $info['is_online']==1?'active':''; ?>">
                        <input type="radio" <?php echo $info['is_online']==1?'checked':''; ?> name="is_online" value="1" > 显示
                    </label>
                    <label class="button <?php echo $info['is_online']==0?'active':''; ?>">
                        <input type="radio" <?php echo $info['is_online']==0?'checked':''; ?> name="is_online" value="0"> 不显示
                    </label>
                </div>
            </div>
            <br>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button id="sure" class="button bg-blue "><?php echo lang('SAVE'); ?></button>
                </div>
            </div>
            <br>
        </div>
    </div>
</div>
<div style="display:none">
    <script src="__STATIC__/js/admin.js"></script>
    <script type="text/javascript" src="__STATIC__/js/webuploader/webuploader.min.js"></script>
</div>
<script type="text/javascript">
    let shop_ids = <?php echo json_encode($shop_ids);; ?>;
    $(function() {
        //图片上传
        $host = 'http://' + "<?php echo $_SERVER['HTTP_HOST']; ?>"
        let hosts = "<?php echo $host; ?>";
        let uploader = WebUploader.create({
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
            let check_err = 0;
            $('.check-error').each(function () {
                check_err += 1;
            });
            if(check_err > 0){
                layer.msg('有条目填写不规范，请检查',{icon:0,time:3000});
                return false;
            }
            let data = {
                id:'',
                name:'',
                cover:'',
                type:1,
                money:0,
                restrict_num:0,
                expire_month:0,
                shop_id:[],
                server_list:[],
                service_id:[],
                status:0,
                is_online:0
            };
            $('input[name="shop_id"]').each(function () {
                if(this.checked){
                    data.shop_id.push($(this).val());
                }
            });
            data.type = $('input:radio[name=type]:checked').val();
            if(data.type == 2)
            {
                $('#server-price-list tr').each(function(i){
                    var list = {
                        id:0,
                        num:0,
                        money:0
                    };
                    $(this).children('td').each(function(j){
                        if (j === 0) {
                            list.id = $(this).find('select option:selected').val();
                        }
                        if (j === 1 ) {
                            list.num = $(this).find('input').val();
                        }
                        if (j === 2 ) {
                            list.money = $(this).find('input').val();
                        }
                    });
                    data.service_id.push($(this).find('select option:selected').val());
                    data.server_list.push(list);
                });
            }else{
                $('input[name="service_id"]').each(function () {
                    if(this.checked){
                        data.service_id.push($(this).val());
                    }
                });
            }
            data.id = $('#ids').val();
            data.name = $('#name').val();
            data.money = $('#money').val();
            data.cover = $('#cover').val();
            data.restrict_num = $('#restrict_num').val();
            data.expire_month = $('#expire_month').val();
            data.status = $('input:radio[name=status]:checked').val();
            data.is_online = $('input:radio[name=is_online]:checked').val();
            if (data.name === ''){
                layer.msg('请输入券名称',{icon:0,time:1500});
                return false;
            }
            console.log(data);
            $.post("<?php echo url('admin/card/cardAddOrUpdate'); ?>",{data},function (res) {
                if (res.code === 200){
                    layer.msg(res.msg,{icon:1,time:2000},function () {
                        parent.layer.close(parent.layer.getFrameIndex(window.name));
                    });
                }else{
                    layer.msg(res.msg,{icon:0,time:1500});
                }
            });
        });

        $('input:radio[name=type]').change(function(){
            show_content();
        })
        show_content();
        sumPrice();
        each_server();
        //动态获取门店列表
        $('input:checkbox[name=service_id]').change(function(){
            let service_id = [];
            $('input:checkbox[name="service_id"]').each(function () {
                if(this.checked){
                    service_id.push($(this).val());
                }
            });
            get_shop_list(service_id);
        })
    });
    //统计总金额
    function sumPrice()
	{
		let zprice = 0;
		$('#server-price-list tr').each(function(i){
			$(this).children('td').each(function(j){
				if (j === 2 ) {
					zprice = parseFloat($(this).find('input').val()) + zprice;
				}
			});
			$('#money').val(zprice);
		});

	}
    //修改次数
    function edit_num(obj){
        let num = $(obj).val();
        if (num < 1) {
            layer.msg('次数不能小于1', {icon:0,time:2000});
            $(obj).val(1);
            return false;
        }
    }

    // 修改金额
    function edit_money(obj){
        let price = $(obj).val();
        if (price < 0) {
            layer.msg('金额不能小于0', {icon:0,time:2000});
            return false;
        }
        sumPrice();
    }

    function show_content(){
        let card_type = $('input:radio[name=type]:checked').val();
        if (card_type == 2){
            $('.select-once').show();
            $('.select-more').hide();
            each_server();
        }else{
            $('.select-more').show();
            $('.select-once').hide();
        }
    };

    function add_class(obj){
        if($(obj).parent().is(".active")){
            console.log($(obj))
            $(obj).parent().removeClass('active');
        }else{
            console.log($(obj))
            $(obj).parent().addClass('active');
        }
    }

    function addtr(){
       let tr = $("#server-price-list tr").eq(0).clone();
       tr.appendTo("#server-price-list");
       //tr.insertBefore("#tb tr:last");
       //
       sumPrice();
       each_server();
    }
    function deltr(obj){
        let length = $("#server-price-list tr").length;
        if(length == 1){
            layer.msg('基础行不能删除',{icon:0,time:2000});
            return false;
        }
        $(obj).parent().parent().remove();
        sumPrice();
        each_server();
    }

    function change_server(obj)
    {
        each_server();
    }


    function each_server()
    {
        let service_id = [];
        let types = $('input:radio[name=type]:checked').val();
        if(types == 2){
            $('#server-price-list tr').each(function(i){
                $(this).children('td').each(function(j){
                    if (j === 0) {
                        service_id.push($(this).find('select option:selected').val());
                    }
                });
            });
        }else{
            $('input[name="service_id"]').each(function () {
                if(this.checked){
                    service_id.push($(this).val());
                }
            });
        }
        get_shop_list(service_id);
    }

    // 获取门店列表
    function get_shop_list(service_id)
    {
        $.post("<?php echo url('card/getShopListByServiceId'); ?>",{service_id},function(res){
            if(res.code === 200){
                let shop_checkbox = ``;
                let temp_lable = ``;
                $.each(res.data,function(i,v){
                    let  id = `${v.id}`;
                    if($.inArray(id,shop_ids) != -1){
                        temp_lable = `<label class="button radius-rounded active">
                            <input name="shop_id" onclick="add_class(this)" value="${v.id}" checked type="checkbox">${v.name}
                        </label>`;
                    }else{
                        temp_lable = `<label class="button radius-rounded">
                        <input name="shop_id" onclick="add_class(this)" value="${v.id}" type="checkbox">${v.name}
                        </label>`;
                    }
                    shop_checkbox += temp_lable;
                })
                $('#shop_list').html(shop_checkbox);
            }else{
                layer.msg('没有可选门店',{icon:0,time:2000});
            }
        });
    }
</script>
		</div>
	</div>
</body>
<script src="/plugins/layer/2.4/layer.js"></script>
<script src="__STATIC__/pintuer/pintuer.js"></script>
<script src="__STATIC__/pintuer/respond.js"></script>
<script src="/plugins/h-ui.admin/js/H-ui.admin.page.js"></script>
</html>

