<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:47:"themes/admin_simpleboot3/admin\member\list.html";i:1554867177;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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
	/* 充值模态框内容样式 */
	#rechargeModal .modal-dialog { width: 402px!important; }
	#rechargeModal .modal-body { padding:  40px 34px; }
	.recharge-items .recharge-top { margin-bottom: 30px; }
	.recharge-items .recharge-top > div {
		display: flex; flex-direction: row; align-items: center; justify-content: space-between;
	}
	.recharge-items .recharge-tips{
		width: 400px;
		height: 40px;
		display: none;
		background:#007FFF;
		position: fixed;
		left: 50%;
		top: 50%;
		margin-left: -200px;
		margin-top: -150px;
		z-index: 999;
		border-radius: 5px;
		border: 1px solid #3399ff;
	}
	.recharge-items .recharge-tips .msg{
		margin-top: 5px;
		text-align: center;
		font-size: 22px;
		color: #fff;
	}
	.auth-code {display:none;height: 50px;}
	.pay-money-tips{height: 50px;}
	.recharge-top div {  }
	.recharge-top div span { font-size: 14px; color: #333; }
	.recharge-top div .account { font-size: 22px; color: #000; }
	.recharge-top div .member,
	.recharge-top div .name { font-size: 16px;}
	.recharge-top div .name { margin-left: 15px; }
	.recharge-top div .balance { font-size: 16px; color:  #fb6d74; }
	#rechargeModal label { color: ;  }
	#rechargeModal .recharge-num { height: 50px; }
	#rechargeModal .recharge-num:focus,
	#rechargeModal .recharge-num:visited,
	#rechargeModal .recharge-num:hover,
	#rechargeModal .recharge-num:active { border-color: #3399ff; }
	#rechargeModal .num-items {
		display: flex; flex-direction: row; align-items: center; flex-wrap: wrap;
	}
	#rechargeModal .num-items span {
		display: inline-block;
		width:  106px;
		text-align:  center;
		height: 68px; line-height: 68px;
		font-size: 20px; color: #666;
		border: 1px solid #e9e9e9;
		border-radius: 6px;
		margin: 6px 7px;
		cursor: pointer;
	}
	#rechargeModal .num-items span.active {
		border-color: rgba(26, 188, 156, 1);
	}
	#rechargeModal .num-items span:hover,
	#rechargeModal .num-items span:focus,
	#rechargeModal .num-items span:visited,
	#rechargeModal .num-items span:active { border-color: rgba(26, 188, 156, 1); }
	#rechargeModal .recharge-type { margin-top: 24px; }
	.recharge-type .recharge-box {
		display: flex; flex-direction: row; align-items: center; flex-wrap: wrap;
	}
	.recharge-type .recharge-box > div {
		width: 116px;
		height:  50px;
		display: flex; flex-direction: row; justify-content: center; align-items: center;
		border: 1px solid #e9e9e9;
		margin-right: 14px;
		margin-bottom: 14px;
	}
	.recharge-type .recharge-box > div.active {
		border-color: rgba(26, 188, 156, 1);
	}
	.recharge-type .recharge-box > div:active,
	.recharge-type .recharge-box > div:hover,
	.recharge-type .recharge-box > div:visited,
	.recharge-type .recharge-box > div:hover {
		border-color: rgba(26, 188, 156, 1);
	}
	.recharge-type .recharge-box > div img { width:  26px; height:  26px; }
	.recharge-type .recharge-box > div span { font-size: 14px; color: #666; }
</style>
</head>
<body>
<div class="wrap js-check-wrap">
	<ul class="nav nav-tabs">
		<li class="active"><a href="<?php echo url('Member/userlist'); ?>">会员列表</a></li>
		<li><a href="javascript:void (0);" onclick="admin_edit('添加会员','<?php echo url('Member/addOrUpdateUser'); ?>')">添加会员</a></li>
	</ul>
	<form class="well form-inline margin-top-0" method="get" action="<?php echo url('Member/userList'); ?>">
		用户ID:
		<input type="text" class="form-control" name="id" style="width: 120px;" value="<?php echo input('request.id/s',''); ?>" placeholder="">
		昵称:
		<input type="text" class="form-control" name="nickname" style="width: 120px;" value="<?php echo input('request.nickname/s',''); ?>" placeholder="">
		<!-- 推荐人:
        <input type="text" class="form-control" name="recommend_name" style="width: 120px;" value="<?php echo input('request.recommend_name/s',''); ?>" placeholder=""> -->
		手机号:
		<input type="text" class="form-control" name="mobile" style="width: 120px;" value="<?php echo input('request.mobile/s',''); ?>" placeholder="">
		所属店铺:
		<select class="form-control" name="shop_code">
			<option value="">请选择</option>
			<?php foreach($shopDatas as $v): ?>
				<option <?php if($shopCode == $v['code']): ?>selected<?php endif; ?> value="<?php echo $v->code; ?>"><?php echo $v->name; ?></option>
			<?php endforeach; ?>
		</select>
		是否为员工:
		<select class="form-control" name="is_staff">
			<option value="" <?php if($isStaff == ''): ?>selected<?php endif; ?>>请选择</option>
			<option value="0" <?php if($isStaff == '0'): ?>selected<?php endif; ?>>否</option>
			<option value="1" <?php if($isStaff == 1): ?>selected<?php endif; ?>>是</option>
		</select>
		<input type="submit" class="btn btn-primary" value="搜索" />
		<a class="btn btn-danger" href="<?php echo url('Member/userlist'); ?>">清空</a>
	</form>
	<table class="table table-hover table-bordered">
		<thead>
		<tr>
			<th width="50">ID</th>
			<th >昵称</th>
			<th >会员编号</th>
			<th >手机号</th>
			<th >所属店铺</th>
			<th >会员等级</th>
			<th >微信OpenId</th>
			<!--<th >是否员工</th>-->
			<th >累计充值</th>
			<th >会员余额</th>
			<th >状态</th>
			<th >注册时间</th>
			<th >操作</th>
		</tr>
		</thead>
		<a href="<?php echo url('member/exportUserList'); ?>" class="btn btn-danger" style="float: right">导出所有|excel</a>
		<tbody>
		<?php $i = 1; if(is_array($datas) || $datas instanceof \think\Collection || $datas instanceof \think\Paginator): if( count($datas)==0 ) : echo "" ;else: foreach($datas as $key=>$v): ?>
			<tr <?php if($i %2 ==0): ?>class=""<?php else: ?>class=""<?php endif; ?>>
			<td><?php echo $v['id']; ?></td>
			<td><?php echo $v['nickname']; ?></td>
			<td onclick="update_No('','<?php echo $v['id']; ?>','<?php echo url('Member/updateNo'); ?>')"><?php echo (isset($v['no']) && ($v['no'] !== '')?$v['no']:"未添加"); ?></td>
			<td><?php echo $v['mobile']; ?></td>
			<td><?php echo !empty($v['shop_code'])?$v['shop_code']['code'].$v['shop_code']['name'] : '未绑定店铺'; ?></td>
			<td><?php echo $v['level_name']; ?></td>
			<td data_member_id="<?php echo $v['id']; ?>" class="view-openId"><?php echo !empty($v['openid'])?'<button type="button" class="btn btn-default btn-sm view-btn">
				<span  class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span> 查看
			</button>':'暂未绑定'; ?></td>
			<td><?php echo $v['amount']; ?></td>
			<td><?php echo $v['money']; ?></td>
			<!--<td>-->
			<!--<?php if($v['is_staff'] == 0): ?>-->
			<!--否-->
			<!--<?php else: ?>-->
			<!--是-->
			<!--<?php endif; ?>-->
			<!--</td>-->
			<td>
				<?php if($v['status'] == 0): ?>
					<font color='orange' size="4">已禁用</font>
					<?php else: ?>
					<font size="4">正常</font>
				<?php endif; ?>
			</td>
			<td><?php echo $v['regtime']; ?></td>
			<td>
				<!-- <a href="javascript:parent.openIframeLayer('<?php echo url('Member/addOrUpdateUser', ['id'=>$v['id']]); ?>','编辑',{});">编辑详情</a> -->
				<a href="javascript:void (0);" onclick="admin_edit('查看详情', '<?php echo url('Member/addOrUpdateUser', ['id'=>$v['id']]); ?>')">编辑</a>|
				<a href="<?php echo url('Member/userCardlist', ['id'=>$v['id']]); ?>">管理所有券</a>|
				<?php if(isset($isAdmin)&&$isAdmin==1): endif; if($v['status'] == 0): ?>
					<a href="javascript:void (0);" onclick="forbidden_user('<?php echo url('Member/forbiddenUser'); ?>',<?php echo $v['id']; ?>,1)">启用</a>|
					<?php else: ?>
					<a href="javascript:void (0);" onclick="forbidden_user('<?php echo url('Member/forbiddenUser'); ?>',<?php echo $v['id']; ?>,0)">禁用</a>|
				<?php endif; ?>
				<a href="javascript:void (0);" onclick="reset_paypwd('<?php echo url('Member/resetPayPwd'); ?>',<?php echo $v['id']; ?>)">重置密码</a>|
				<a href="javascript:void (0)" data-member-id="<?php echo $v['id']; ?>" class="recharge">充值</a>|
				<a href="javascript:void (0)" data-member-id="<?php echo $v['id']; ?>" onclick="recharge('买卡','<?php echo url('Member/buyCard',['uid'=>$v['id']]); ?>')">购卡</a>
			</td>
			</tr>
			<?php $i++; endforeach; endif; else: echo "" ;endif; ?>
		</tbody>
	</table>
	<div class="pagination"><?php echo $page; ?></div>
</div>

<!--充值模态框-->
<div class="modal fade" id="rechargeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">会员充值</h4>
			</div>

			<div class="modal-body recharge-items">
				<div class="recharge-top">
					<div>
						<span class="account" id="user_mobile" data-user-id=""></span>
						<span>储值余额</span>
					</div>
					<div>
						<div>
							<span class="member" id="user_level">普通会员</span>
							<span class="name" id="user_name">捣蛋熊猫</span>
						</div>
						<span class="balance" id="user_money">￥0.00</span>
					</div>
				</div>
				<div class="recharge-tips">
					<div class="msg">
						充值成功
					</div>
				</div>
				<div class="form-group">
					<label for="recharge-num">充值金额</label>
					<input type="text" class="form-control recharge-num" id="recharge-num" placeholder="请选择充值金额">
				</div>
				<div class="num-items">

				</div>
				<div class="recharge-type">
					<p>选择充值方式</p>
					<div class="recharge-box">
						<div class="active" data-mode-id="5">
							<img src="__STATIC__/images/cashicon.png" alt="">
							<span>&nbsp;&nbsp;现金</span>
						</div>
						<div data-mode-id="4">
							<img src="__STATIC__/images/yhkicon.png" alt="">
							<span>&nbsp;&nbsp;银行卡</span>
						</div>
						<div data-mode-id="7">
							<img src="__STATIC__/images/zengsong.png" alt="">
							<span>&nbsp;&nbsp;赠送</span>
						</div>
						<?php if($is_show == 1): ?>
							<div data-mode-id="99">
								<img src="__STATIC__/images/warnicon.png" alt="">
								<span>&nbsp;&nbsp;异常充值</span>
							</div>
						<?php endif; ?>
						<div data-mode-id="1">
							<img src="__STATIC__/images/wxicon.png" alt="">
							<span>&nbsp;&nbsp;微信</span>
						</div>
						<div data-mode-id="2">
							<img src="__STATIC__/images/zfbicon.png" alt="">
							<span>&nbsp;&nbsp;支付宝</span>
						</div>
					</div>
				</div>
				<div class="form-group pay-money-tips">
					<div>
						<span style="color:red">请确认已收款</span>
					</div>
				</div>
				<div class="form-group auth-code">
					<input type="text" class="form-control" id="auth-code" placeholder="请输入或者扫描付款码">
				</div>
				<input type="hidden" class="form-control" id="id-auth-code" >
				<input type="hidden" class="form-control" id="order-sn" >
				<button type="button" class="btn btn-primary" id="recharge_save">确认充值</button>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
			</div>
		</div>
	</div>
</div>

<script src="__STATIC__/js/admin.js"></script>
<script type="text/javascript">

    var host = "http://dd.ddxm661.com";
    var intervals = null;

    $('.recharge-box > div').click(function(){
        $(this).siblings("div.active").removeClass('active')
        $(this).addClass('active');
    })

    $('.view-btn').click(function(){
        var member_id = $(this).parent().attr('data_member_id')
        $.post("<?php echo url('Member/getOneOpenId'); ?>",{'id':member_id},function(res){
            layer.open({
                title: 'openId'
                ,content: res.data.data
            });
        })
    })

    //会员充值
    $('.recharge').click(function(){
        var member_id = $(this).attr('data-member-id');
        $.post("<?php echo url('Recharge/rechargeIndex'); ?>",{
            'id':member_id
        },function(re){
            if(re.code == 1){
                var order_sn = re.data.order_sn;
                var id_auth_code = re.data.id_auth_code;
                // var shopStandardPrice = re.data.shopStandardPrice;
                var userInfo = re.data.userInfo;
                var html = '';
                $('#user_mobile').text(userInfo.mobile);
                $('#user_mobile').attr('data-user-id',userInfo.id);
                $('#user_level').text(userInfo.level_name);
                $('#user_name').text(userInfo.nickname);
                $('#user_money').text(userInfo.money);
                $('#id_auth_code').val(id_auth_code);
                $('#order-sn').val(order_sn);
                // $.each(shopStandardPrice,function(k,v){
                //     html+='<span data-tip-id ="'+v.id+'">'+v.price+'</span>'
                // })
                $('div.num-items').html(html);
                $('div.num-items').find('span').first().addClass('active');
                $('#recharge-num').val($('div.num-items').find('span').first().text())
                $('#rechargeModal').modal('show');
            }else {
                $('#user_mobile').text('1008611');
                $('#user_level').text('普通会员');
                $('#user_name').text('捣蛋熊猫');
                $('#user_money').text('0.00');
                $('#rechargeModal').modal('show');
                tips(re.msg);
                var inte = setInterval(function(){
                    window.location.reload();
                    clearInterval(inte);
                }, 2500);
            }
        })
    })

    $('div.num-items').on('click',"span",function(){
        $('#recharge-num').val($(this).text())
        $(this).addClass('active')
        $(this).siblings("span").removeClass('active')
    })

    $('.recharge-box > div').click(function(){
        $pay_way = $(this).attr('data-mode-id');
        if ($pay_way == 1 || $pay_way == 2) {
            $('#auth-code').val('');
            $('.auth-code').show();
            $('.pay-money-tips').hide();
        }else{
            $('#auth-code').val('');
            $('.pay-money-tips').show();
            $('.auth-code').hide();
        }

    })

    var orderPay = function () {
        console.log('开始支付');
        var url = host + "/wechat/api/userrecharge";
        var pay_way = $('div.recharge-box div.active').attr('data-mode-id');
        var recharge_id = $('div.num-items span.active').attr('data-tip-id');
        var money = $('#recharge-num').val();
        var member_id = $('#user_mobile').attr('data-user-id');
        var auth_code = $('#auth-code').val();
        var id_auth_code = $('#id-auth-code').val();
        var sn = $('#order-sn').val();//初始订单号
		if ((pay_way == 1 || pay_way == 2) && auth_code == '') {
        	layer.msg('请扫描支付二维码',{icon:2,time:3000});
            return false;
        }
		if (Number(money) <= 0) {
			layer.confirm('充值金额小于等于 0,确定充值吗?',{btn: ['确定', '取消'],title:"提示"}, function(res){
				$('#recharge_save').hide();
				$.post( url,{
					order_sn:sn,
					member_id:member_id,
					recharge_id:recharge_id,
					money:money,
					pay_way:pay_way,
					id_auth_code:id_auth_code,
					auth_code:auth_code
				},function(data){
					if (data.code == 200) {
						layer.msg(data.msg,{icon:1,time:2000});
						setTimeout(function () {
							window.location.reload();
						}, 3500)

					}else if(data.code == 201){
						$('.recharge-tips .msg').text(data.msg);
						$('.recharge-tips').fadeIn();

						var order_sn = data.data.order_sn;
						var pay_type = $('div.recharge-box div.active').attr('data-mode-id');
						var urls = '';
						var close_urls = '';
						if (pay_type == 1) {
							urls = host + "/wechat/api/queryWxOrder";
							close_urls = host + "/wechat/api/closeWxOrder";
						}else if(pay_type == 2){
							urls = host + "/wechat/api/queryAliOrder";
							close_urls = host + "/wechat/api/closeAliOrder";
						}
						var times = 0;
						intervals = setInterval(function(){
							times += 5;
							$.post(urls,{order_sn:order_sn,sn:sn},function(res){
								if (res.code == 200) {
									layer.msg(res.msg,{icon:1,time:2000});
									setTimeout(function () {
										window.location.reload();
										clearInterval(intervals);
										intervals = null;
									}, 3500)
									// var inte = setInterval(function(){
									// 	window.location.reload();
									// 	clearInterval(inte);
									// }, 3500);
								}else if (res.code === 300) {
									layer.msg(res.msg,{icon:0,time:2000});
									setTimeout(function () {
										window.location.reload();
										clearInterval(intervals);
										intervals = null;
									}, 3500)
								} else {
									layer.msg(res.msg,{icon:2,time:3000});
								}
								if(times === 60){
									$.post(close_urls,{order_sn:order_sn,sn:sn},function(res){})
									setTimeout(function () {
										window.location.reload();
										clearInterval(intervals);
										intervals = null;
									}, 3500)
								}
							});
						}, 5000);
					}else{
						layer.msg(data.msg,{icon:2,time:3000});
						setTimeout(function () {
							window.location.reload();
						}, 3500)
						/*var inte = setInterval(function(){
						window.location.reload();
						clearInterval(inte);
					}, 3500);*/
				}
			})
			});
		}else{
        	$('#recharge_save').hide();
    		$.post( url,{
	            order_sn:sn,
	            member_id:member_id,
	            recharge_id:recharge_id,
	            money:money,
	            pay_way:pay_way,
	            id_auth_code:id_auth_code,
	            auth_code:auth_code
	        },function(data){
	            if (data.code == 200) {
	            	layer.msg(data.msg,{icon:1,time:2000});
	                setTimeout(function () {
	                    window.location.reload();
	                }, 3500)

	            }else if(data.code == 201){
	                $('.recharge-tips .msg').text(data.msg);
	                $('.recharge-tips').fadeIn();

	                var order_sn = data.data.order_sn;
	                var pay_type = $('div.recharge-box div.active').attr('data-mode-id');
	                var urls = '';
	                var close_urls = '';
	                if (pay_type == 1) {
	                    urls = host + "/wechat/api/queryWxOrder";
	                    close_urls = host + "/wechat/api/closeWxOrder";
	                }else if(pay_type == 2){
	                    urls = host + "/wechat/api/queryAliOrder";
	                    close_urls = host + "/wechat/api/closeAliOrder";
	                }
	                var times = 0;
	                intervals = setInterval(function(){
	                    times += 5;
	                    $.post(urls,{order_sn:order_sn,sn:sn},function(res){
	                        if (res.code == 200) {
	                            layer.msg(res.msg,{icon:1,time:2000});
	                            setTimeout(function () {
	                                window.location.reload();
	                                clearInterval(intervals);
	                                intervals = null;
	                            }, 3500)
	                            // var inte = setInterval(function(){
	                            // 	window.location.reload();
	                            // 	clearInterval(inte);
	                            // }, 3500);
	                        }else if (res.code === 300) {
	                            layer.msg(res.msg,{icon:0,time:2000});
	                            setTimeout(function () {
	                                window.location.reload();
	                                clearInterval(intervals);
	                                intervals = null;
	                            }, 3500)
	                        } else {
	                            layer.msg(res.msg,{icon:2,time:3000});
	                        }
	                        if(times === 60){
	                            $.post(close_urls,{order_sn:order_sn,sn:sn},function(res){})
	                            setTimeout(function () {
	                                window.location.reload();
	                                clearInterval(intervals);
	                                intervals = null;
	                            }, 3500)
	                        }
	                    });
	                }, 5000);
	            }else{
	            	layer.msg(data.msg,{icon:2,time:3000});
	                setTimeout(function () {
	                    window.location.reload();
	                }, 3500)
	                /*var inte = setInterval(function(){
	                    window.location.reload();
	                    clearInterval(inte);
	                }, 3500);*/
	            }
	        })
        }
    }
    $('#auth-code').bind('keypress', function (e) {
        if (e.keyCode == 13) {
            orderPay();
        }
    })

    $('#recharge_save').click(function(){
        // console.log($('div.recharge-box div.active').attr('data-mode-id'));return
        orderPay();
    })

    function tips(data)
    {
        // $('#recharge_save').attr('style','display:none');
        $('.recharge-tips .msg').text(data);
        $('.recharge-tips').fadeIn(1000);
        setTimeout(function(){
            // $('#recharge_save').attr('style','display:block');
            $('.recharge-tips').fadeOut('slow');
        }, 3500);
    }


    /*编辑*/
    function admin_edit(title,url){
        layer_show(title,url);
    }

    /*编辑*/
    function recharge(title,url){
        layer_show(title,url,1000);
    }

    //禁用用户
    function forbidden_user(url,id,status) {
        layer.confirm('确认要执行操作？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.post(url, { id: id ,status:status}, function(data){
                if (data.code == 0) {
                    layer.msg(data.msg, {icon: 1,time:1000},function () {
                        location.reload();
                    });
                }else {
                    layer.msg(data.msg, {icon: 0,time:1000});
                }
            });
        });
    }

    //添加或修改编号
    function update_No(content,id,url){
        layer.prompt({
            formType: 0,
            value: content,
            title: '请输入编号',
        }, function(value, index){
            $.post(url, { id: id ,user_no:value}, function(data){
                if (data.code === 200) {
                    layer.msg(data.msg, {icon: 1,time:1000},function () {
                        window.location.reload();
                    });
                }else {
                    layer.msg(data.msg, {icon: 0,time:1000});
                }
            });
            layer.close(index);
        });
    }

    // 重置密码
    function reset_paypwd(url,id) {
        layer.confirm('确认要重置？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.post(url, { id: id}, function(res){
                if (res.code == 200) {
                    layer.msg(res.msg, {icon: 1,time:1000},function () {
                        location.reload();
                    });
                }else {
                    layer.msg(res.msg, {icon: 0,time:1000});
                }
            });
        });
    }

</script>
</body>
</html>
