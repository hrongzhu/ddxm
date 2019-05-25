<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:54:"themes/admin_simpleboot3/admin\order\edit_receipt.html";i:1554867186;s:45:"themes/admin_simpleboot3/public\ptheader.html";i:1554867210;s:45:"themes/admin_simpleboot3/public\ptfooter.html";i:1554867211;}*/ ?>
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

<div class="panel margin-top">
    <div class="panel-head bg-main">
        <span>收货人信息：</span>
    </div>
    <div class="panel-body">
        <div class="form-x form-inline">
            <div class="form-group">
               <label>收货人姓名:</label>
                <label>
                    <input type="text" class="input" name="name" id="name"  value="<?php echo !empty($declareInfo['name'])?$declareInfo['name']:$orderInfo['realname']; ?>" placeholder="">
                </label>
            </div>
            <div class="form-group">
               <label>手机号:</label>
                <label>
                    <input type="text" class="input" name="mobile" id="mobile"  value="<?php echo $orderInfo['mobile']; ?>" placeholder="">
                </label>
            </div>
            <div class="form-group">
               <label>身份证号码:</label>
                <label>
                    <input type="text" class="input" name="idcard" id="idcard"  value="<?php echo !empty($declareInfo['idcard'])?$declareInfo['idcard']:''; ?>" placeholder="">
                </label>
            </div>
            <div class="form-group">
               <label> 收货地址:</label>
                <label>
                    <input type="text" class="input" name="detail_address" id="detail_address"  value="<?php echo $orderInfo['detail_address']; ?>" placeholder="">
                </label>
            </div>
            <input type="hidden" class="input" name="order_id" id="order_id"  value="<?php echo $orderInfo['id']; ?>" placeholder="">
            <input type="hidden" class="input" name="member_id" id="member_id"  value="<?php echo $orderInfo['member_id']; ?>" placeholder="">
            <div style="margin:20px 0px;">
                <button id="save" class="button radius-none bg-blue">保存</button>
            </div>
        </div>
    </div>
</div>
<script src="__STATIC__/js/admin.js"></script>
<script type="text/javascript">
    $('#save').click(function(){
        $name = $('#name').val();
        $mobile = $('#mobile').val();
        var mobile_prttern = /^1[345789]\d{9}$/;
        if(!(mobile_prttern.test($mobile))){
            layer.msg("手机号码有误，请重填",{icon:2,time:1500});
            $('#mobile').val($mobile)
            return false;
        }
        $detail_address = $('#detail_address').val();
        if($.trim($detail_address).length == 0) {
            layer.msg("请填写地址",{icon:2,time:1500});
            $('#detail_address').val($detail_address);
            return false;
        }
        $idcard =  $('#idcard').val();
        var idcard_pattern = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
        if(!(idcard_pattern.test($idcard))){
            layer.msg("身份证号码错误",{icon:2,time:1500});
            $('#idcard').val($idcard);
            return false;
        }
        $order_id = $('#order_id').val();
        $member_id = $('#member_id').val();
        $.post('<?php echo url("Order/editReceiptInfo"); ?>', {name: $name, mobile: $mobile, order_id: $order_id, detail_address:$detail_address,idcard:$idcard,member_id:$member_id}, function(data){
            if (data.code == 200) {
                    layer.msg(data.msg, {icon: 1,time:1000},function () {
                        window.parent.location.reload();
                        // location.reload();
                    });
            }else {
                layer.msg(data.msg, {icon: 0,time:1000},function () {
                        window.parent.location.reload();
                        // location.reload();
                    });
            }
        },'json');
    });

</script>
		</div>
	</div>
</body>
<script src="/plugins/layer/2.4/layer.js"></script>
<script src="__STATIC__/pintuer/pintuer.js"></script>
<script src="__STATIC__/pintuer/respond.js"></script>
<script src="/plugins/h-ui.admin/js/H-ui.admin.page.js"></script>
</html>

