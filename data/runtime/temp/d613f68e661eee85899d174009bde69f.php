<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:49:"themes/admin_simpleboot3/admin\collect\index.html";i:1557224973;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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

<link rel="stylesheet" type="text/css" href="/plugins/layer/2.4/css/layui.css">
<link rel="stylesheet" type="text/css" href="__STATIC__/css/collect.css">
<!-- 页面中所有需动态更新数据的元素片段加入 artTemplate模版 -->
<!-- IE8 需加入补丁 -->
<script src="__STATIC__/js/es5-sham.min.js"></script>
<script src="__STATIC__/js/json3.min.js"></script>
<!-- ****** -->
<script src="__STATIC__/js/artTemplate/template-web.js"></script>
</head>
<body>
	<div class="collect-content">
		<div class="collect">
			<!-- 左边搜索商品展示 -->
			<div class="c-show">
				<div class="header">
					<div class="items change-type">
						<span class="active" data-type="0">门店自用</span>
						<span data-type="3">搜索结果</span>
					</div>
					<div class="search">
						<input type="text" class="form-control input-search" name="search" placeholder="名称/条形码">
						<img src="__STATIC__/images/collect/btn-search.png" alt="" class="product-search">
					</div>
				</div>
				<div class="list-items">
					<div class="list" id="list_items">

					</div>
					<!-- 分页 -->
					<div id="pagination" style="text-align: center;"></div>
				</div>
			</div>
			<!-- 右边操作显示 -->
			<div class="c-calc">
				<!--加入订单标注(普通/预定) -->
				<div class="flag-order">
					<span class="normal" data-type="1">普通订单</span>
					<span class="register hidden" data-type="2">预定订单</span>
				</div>
				<div class="role-info">
					<div>
						<span>服务人员 : </span>
						<span class="service-name">-</span>
					</div>
					<div>
						<P>
							<span>会&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;员 : </span>
							<span class="member-level" data-memberid="" data-phone="">-</span>
						</P>
						<P class="member-balance hidden">
							<span>余&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;额 : </span>
							<span class="m-balance" data-balance="">￥0.00</span>
						</P>
					</div>
				</div>
				<div class="items-box">
					<div class="product">
						<div class="header item-width">
							<div>名称</div>
							<div>金额</div>
							<div>数量</div>
							<div>金额</div>
							<div>操作</div>
						</div>
						<div class="goods">
							<div class="list" id="order_product"></div>
							<div class="tip orange normal hidden">
								<span>代金券</span>
								<p><span class="ticket-num">10</span><span>张代金券可用，请选择</span><i class="layui-icon layui-icon-right" style="font-size: 16px; color: #b6b5b1; margin-left: 4px;"></i></p>
							</div>
							<div class="tip red special hidden">
								<span>代金券</span>
								<p><img  data-toggle="tooltip" data-placement="top" title="
选择消耗代金券金额已大于待结账商品金额，代金券仅抵扣商品金额且不找零！" src="__STATIC__/images/collect/icon-caution.png" alt=""><span>-￥</span><span class="ticket-sum">100.00</span><i class="layui-icon layui-icon-right" style="font-size: 16px; color: #b6b5b1; margin-left: 4px;"></i></p>
							</div>
						</div>
					</div>
					<div class="coupon hidden">
						<div class="list layui-form" id="coupon_list">
						 <!-- 商品代金券列表 -->
						</div>
						<div class="tip orange">
							<div>
								<button class="btn-blue-default submit-ticket">确定</button>
								<button class="btn-blue-border submit-cancel">取消</button>
							</div>
							<span><span>￥</span><span class="tickets-sum">100</span></span>
						</div>
					</div>
					<div class="footer">
						<span class="sn">合计<span class="sum-num">0</span>件</span>
						<span class="sm">￥<span class="uordersum">0.00</span></span>
					</div>
				</div>
			</div>
		</div>
		<div class="fix-footer">
			<button class="btn-purple-border" id="takeBookGoods">取件</button>
			<button class="btn-blue-border"  data-toggle="modal" data-target="#member">会员</button>
    		<button class="btn-blue-default check-out">结账</button>
		</div>
	</div>
	<!-- 会员弹出框（模态框） -->
	<div class="modal frame-member fade" data-backdrop="static" id="member" tabindex="-1" role="dialog" aria-labelledby="memberlabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	      	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <p class="modal-title" id="memberlabel">会员信息</p>
	      </div>
	      <div class="modal-body">
	        <div class="member-top inline-box">
	        	<div class="search">
					<input type="text" class="form-control member-search" name="search" placeholder="输入会员手机号">
					<img src="__STATIC__/images/collect/btn-search.png" alt="" class="btn-member-search">
				</div>
	        </div>
	        <div class="member-info">
	        	<div class="detail inline-box">
	        		<p class="inline-box">
	        			<span>手&nbsp;&nbsp;&nbsp;机&nbsp;&nbsp;号</span>
	        			<span class="role-phone"></span>
	        		</p>
	        		<p class="inline-box">
	        			<span>绑定店铺</span>
	        			<span class="role-shop"></span>
	        		</p>
	        		<p class="inline-box">
	        			<span>昵&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;称</span>
	        			<span class="role-nick"></span>
	        		</p>
	        		<p class="inline-box">
	        			<span>会员等级</span>
	        			<span class="level-name"></span>
	        		</p>
	        		<p class="inline-box">
	        			<span>卡&nbsp;&nbsp;余&nbsp;&nbsp;额</span>
	        			<span class="role-money"></span>
	        		</p>
	        		<p class="inline-box">
	        			<span>服&nbsp;&nbsp;务&nbsp;&nbsp;券</span>
	        			<span class="coupons-num"></span>
	        		</p>
	        		<p class="inline-box">
	        			<span>竹&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;子</span>
	        			<span class="bamboo-points"></span>
	        		</p>
	        		<p class="inline-box">
	        			<span>笋&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;子</span>
	        			<span class="bambooshoot-points"></span>
	        		</p>
	        	</div>
	        </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn-default-border reset_member">重置</button>
	        <button type="button" class="btn-blue-default" id="sure_member">确定</button>
	      </div>
	    </div>
	  </div>
	</div>
	<!-- 结账弹出框（模态框） -->
	<div class="modal frame-member fade"  data-backdrop="static" id="checkout" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <p class="modal-title" id="checkoutlabel">结账</p>
	      </div>
	      <div class="modal-body">
	        <div class="num">
	        	<p class="sum">
	        		<span>应收</span>
	        		<span class="pull-right sum-psum">88.88</span>
	        	</p>
	        	<div class="detail">
	        		<p>
	        			<span>合&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;计</span>
	        			<span class="pull-right sum-sum">9999.99</span>
	        		</p>
	        		<p>
	        			<span>会员优惠</span>
	        			<span class="pull-right sum-discount">-999.00</span>
	        		</p>
	        		<p>
	        			<span>手动改价:</span>
	        			<span class="pull-right sum-change">-900.00</span>
	        		</p>
	        		<p>
	        			<span>商品代金券:</span>
	        			<span class="pull-right sum-ticket">-900.00</span>
	        		</p>
	        	</div>
	        	<div class="role">
	        		<p>
	        			<span>服务店铺&nbsp;&nbsp;&nbsp;&nbsp;</span>
	        			<select name="choose-services" id="shop_list">
	        				<!-- <option value=""></option> -->
	        			</select>
	        		</p>
	        		<p>
	        			<span>服务人员&nbsp;&nbsp;&nbsp;&nbsp;</span>
	        			<select name="choose-services" id="worker_list">
	        				<!-- <option value=""></option> -->
	        			</select>
	        		</p>
	        		<p>
	        			<span>会&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;员&nbsp;&nbsp;&nbsp;&nbsp;<span class="sum-member">无</span></span>
	        		</p>
	        	</div>
	        </div>
	        <div class="card">
	        	<div class="inline-box">
	        		<div class="item-box" data-id="3">
		        		<img src="__STATIC__/images/collect/checkout-huiyuanka.png" alt="">
		        		<img class="in hidden" src="__STATIC__/images/collect/checkout-huiyuanka-sel.png" alt="">
		        		<span>&nbsp;&nbsp;会员卡</span>
		        	</div>
					<div class="item-box" data-id="8">
		        		<img src="__STATIC__/images/collect/checkout-mendian.png" alt="">
		        		<img class="in hidden" src="__STATIC__/images/collect/checkout-mendian-sel.png" alt="">
		        		<span>&nbsp;&nbsp;门店自用</span>
		        	</div>
	        	</div>
	        	<div class="inline-box">
	        		<div class="item-box" data-id="5">
		        		<img src="__STATIC__/images/collect/checkout-xianjing.png" alt="">
		        		<img class="in hidden" src="__STATIC__/images/collect/checkout-xianjing-sel.png" alt="">
		        		<span>&nbsp;&nbsp;现金</span>
		        	</div>
					<div class="item-box" data-id="4">
		        		<img src="__STATIC__/images/collect/checkout-yinhangka.png" alt="">
		        		<img class="in hidden" src="__STATIC__/images/collect/checkout-yinhangka-sel.png" alt="">
		        		<span>&nbsp;&nbsp;银行卡</span>
		        	</div>
	        	</div>
	        	<div class="inline-box">
	        		<div class="item-box" data-id="2">
		        		<img src="__STATIC__/images/collect/checkout-zhifubao.png" alt="">
		        		<img class="in hidden" src="__STATIC__/images/collect/checkout-zhifubao-sel.png" alt="">
		        		<span>&nbsp;&nbsp;支付宝</span>
		        	</div>
		        	<div class="item-box" data-id="1">
		        		<img src="__STATIC__/images/collect/checkout-weixin.png" alt="">
		        		<img class="in hidden" src="__STATIC__/images/collect/checkout-weixin-sel.png" alt="">
		        		<span>&nbsp;&nbsp;微信</span>
		        	</div>
	        	</div>
	        	<div class="inline-box">
	        		<div class="item-box" data-id="6">
		        		<img src="__STATIC__/images/collect/checkout-meituan.png" alt="">
		        		<img class="in hidden" src="__STATIC__/images/collect/checkout-meituan-sel.png" alt="">
		        		<span>&nbsp;&nbsp;美团</span>
		        	</div>
					<div class="item-box" data-id="7">
		        		<img src="__STATIC__/images/collect/checkout-zengpin.png" alt="">
		        		<img class="in hidden" src="__STATIC__/images/collect/checkout-zengpin-sel.png" alt="">
		        		<span>&nbsp;&nbsp;赠送</span>
		        	</div>
	        	</div>
	        	<div class="inline-box">
	        		<div class="item-box" data-id="10">
		        		<img src="__STATIC__/images/collect/checkout-baoyue.png" alt="">
		        		<img class="in hidden" src="__STATIC__/images/collect/checkout-baoyue-sel.png" alt="">
		        		<span>&nbsp;&nbsp;包月服务</span>
		        	</div>
					<div class="item-box" data-id="11">
		        		<img src="__STATIC__/images/collect/checkout-baoyue.png" alt="">
		        		<img class="in hidden" src="__STATIC__/images/collect/checkout-baoyue-sel.png" alt="">
		        		<span>&nbsp;&nbsp;定制疗程</span>
		        	</div>
	        	</div>
	        	<input type="text" class="form-control zftm hidden" name="" placeholder="请扫描支付条码">
	        	<p class="check-tip hidden">请确认当前已收款</p>
	        </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn-default-border" data-dismiss="modal">取消</button>
	        <button type="button" class="btn-blue-default" id="check_out">结账</button>
	      </div>
	    </div>
	  </div>
	</div>
	<!-- 改价弹出框 -->
	<div class="modal frame-member frame-change fade" data-backdrop="static" id="changeprice" tabindex="-1" role="dialog" aria-labelledby="memberlabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	      	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <p class="modal-title" id="checkoutlabel">改价</p>
	      </div>
	      <div class="modal-body change-madel" data-index="">
			<p>
				<span>名&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;称</span>
				<span class="product-name">奶粉奶粉奶粉奶粉奶粉奶粉奶粉奶粉</span>
			</p>
			<p>
				<span>条&nbsp;形&nbsp;&nbsp;码</span>
				<span class="product-sn">111100000000000</span>
			</p>
			<p>
				<span>原&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;价</span>
				<span class="product-price">￥456012</span>
			</p>
			<p>
				<span>改后价格</span>
				<span>
					<input type="text" class='form-control input-discount' placeholder="请输入改后价">
					元
				</span>
			</p>
			<p>
				<span>改价优惠</span>
				<span class="discount">-￥0.00</span>
			</p>
			<p>
				<span>改价原因</span>
				<span>
					<textarea name="changeReason" rows="5" class='form-control change-reason' placeholder='请输入改价原因'></textarea>
				</span>
			</p>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn-default-border" data-dismiss="modal">取消</button>
	        <button type="button" class="btn-blue-default" id="sure_changeprice">确定</button>
	      </div>
	    </div>
	  </div>
	</div>
	<!-- 取件弹出框（模态框） -->
	<div class="modal frame-member take-part fade"  data-backdrop="static" id="takePart" role="dialog" aria-labelledby="memberlabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	      	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <p class="modal-title" id="memberlabel">会员取件信息</p>
	      </div>
	      <div class="modal-body">
	        <div class="member-top inline-box">
	        	<div class="search">
					<input type="text" class="form-control search-bookgoods" name="search" placeholder="输入会员手机号">
					<img src="__STATIC__/images/collect/btn-search.png" alt="" class="btn-search-bookgoods">
				</div>
	        </div>
	        <div class="package-content">
	        	<!-- 取件列表 -->
	        	<div class="list">
	        		<div class="layui-form">
	        			<table class="layui-table bookgoods-list" data-type="list">

			        	</table>
	        		</div>
	        	</div>

	        	<!-- 取件详情 -->
	        	<div class="detail hidden">
	        		<div class="layui-form">
	        			<table class="layui-table bookgoods-listdetail" data-type="detail">

			        	</table>
	        		</div>
	        	</div>
	        </div>
	      </div>
	      <div class="modal-footer">
	        <div class="list-btn hidden">
	        	<button type="button" class="btn-default-border goods-refond">退款</button>
	        	<button type="button" class="btn-default-border goods-check">查看</button>
	        	<button type="button" class="btn-blue-default goods-take">取货</button>
	        </div>
	        <div class="detail-btn hidden">
	        	<button type="button" style="width: 138px;" class="btn-default-border detail-return">返回</button>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>
	<div id="take_input" style="display: none;">
		<div class="take-input">
			<input type="text" placeholder="请输入取货数量" class="layui-input take-num">
			<p class="take-tip">
				<img src="__STATIC__/images/collect/icon-caution.png" alt="">
				<span>门店可用库存<span class="stock">0</span>件</span>
			</p>
		  </div>
	</div>
	<div id="take_refund" style="display: none;">
		<div class="take-input">
			<input type="text" placeholder="请输入退款商品数量" class="layui-input take-refund-num">
			<p class="take-tip type-cash">
				预定款<span>￥</span><span class="refund-money">0.00</span>请以<span>【现金】</span>退回客户
			</p>
			<p class="take-tip type-member hidden">
				预定款<span>￥</span><span class="refund-money">0.00</span>将退回至用户会员卡
			</p>
		  </div>
	</div>
<script src="/plugins/layer/2.4/layui.js"></script>
<script src="__STATIC__/js/collect/collect.js" type="text/javascript"></script>
<!-- 数据模版 -->
<!-- 右边订单列表模版 -->
<script type="text/html" id="contentProduct">
	{{each list value i}}
	<div class="item-width" data-index='{{i}}' data-class="{{value.class}}">
		<div>
			{{if value.class == 2}}
			<span class="s-text">服务</span>
			<span class="name">{{value.sname}}</span>
			{{else}}
			<span class="name">{{value.title}}</span>
			{{/if}}
		</div>
		<div class="p-price">
			{{if value.discount > 0 || value.changeprice > -1}}
			{{if value.changeprice > -1}}
			<span class="np">￥{{value.changeprice}}</span>
			{{else}}
			<span class="np">￥{{value.discount}}</span>
			{{/if}}
			<span class="change">￥{{value.price}}</span>
			{{else}}
			<span class="np">￥{{value.price}}</span>
			{{/if}}
		</div>
		<div>
			<div class="choose-num">
				<span class="btn-reduce">-</span>
			 	<input type="text" class="form-control product-num" name="number" value={{value.num}}>
			 	<span class="btn-add">+</span></div>
			</div>
		<div>
			{{if value.changeprice > -1}}
			<span class="single-sum">￥{{(value.changeprice * value.num).toFixed(2)}}</span>
			{{else}}
			{{if value.discount > 0}}
			<span class="single-sum">￥{{(value.discount * value.num).toFixed(2)}}</span>
			{{else}}
			<span class="single-sum">￥{{(value.price * value.num).toFixed(2)}}</span>
			{{/if}}
			{{/if}}
		</div>
		<div>
			{{if value.changeprice > -1}}
			<span class="calc change-price active" data-toggle="modal" data-target="#changeprice">改价</span>
			{{else}}
			<span class="calc change-price" data-toggle="modal" data-target="#changeprice">改价</span>
			{{/if}}
		</div>
	</div>
	{{/each}}
</script>
<!-- 左边商品结果展示列表 -->
<script type="text/html" id="showProductList">
	{{if list.length > 0}}
	{{each list value i}}
	<div class="item" data-index="{{i}}">
		{{if value.class == 2}}
		<img src="__STATIC__/images/collect/icon-service.png" alt="">
		<div class="name">
			<span class="pname ellipsis" title="{{value.title}}">{{value.sname}}</span>
			<span>{{value.bar_code || '无条形码'}}</span>
		</div>
		{{else}}
		<div class="name">
			<span class="pname ellipsis" title="{{value.title}}">{{value.title}}</span>
			<span>{{value.bar_code || '无条形码'}}</span>
		</div>
		{{/if}}
		<div class="price">
			{{if value.class == 2}}
			<span>-</span>
			{{else}}
			<span>库存&nbsp;&nbsp;{{value.stock}}</span>
			{{/if}}
			<span>￥{{value.price}}</span>
		</div>
	</div>
	{{/each}}
	<!-- 加入加载中状态 -->
	<p class="load hidden">
		<img src="__STATIC__/images/loading-s.gif" alt="">
		<span>正在加载...</span>
	</p>
	{{else}}
	<p class="tip-search">
		<img src="__STATIC__/images/scanicon.png" alt="">
		<span>请使用扫码枪或者输入关键字~</span>
	</p>
	{{/if}}
</script>
<!-- 选择服务人员 list -->
<script type="text/html" id="workerList">
  <option data-id="0">-</option>
  {{if list.length > 0}}
  {{each list value i}}
  <option data-id="{{value.workid}}">{{value.name + '(' + value.type + ')'}}</option>
  {{/each}}
  {{/if}}
</script>
<!-- 商品代金券列表 -->
<script type="text/html" id="ticketsList">
{{each list value i}}
 <div data-id="{{value.id}}" data-index="{{i}}">
 	{{if value.choose}}
	<input type="checkbox" lay-filter="choose_ticket" class="check-box" checked name="" title="{{value.money}}元代金券" lay-skin="primary">
	{{else}}
	<input type="checkbox" lay-filter="choose_ticket" class="check-box" name="" title="{{value.money}}元代金券" lay-skin="primary">
	{{/if}}
	<span><span>{{value.expire_time}}</span></span>
</div>
{{/each}}
</script>
<!-- 取件列表 -->
<script type="text/html" id="booksGoodsList">
<colgroup>
  <col width="75">
  <col width="250">
  <col width="160">
  <col width="124">
  <col width="100">
  <col width="100">
</colgroup>
<thead>
	<tr>
		<th>SKUID</th>
		<th>商品名称</th>
		<th>预定日期</th>
		<th>实付单价</th>
		<th>预定数量</th>
		<th>待取数量</th>
	</tr>
</thead>
<tbody>
	{{each list value i}}
	{{if value.dq_num > 0}}
	<tr data-index="{{i}}">
		<td>{{value.sku_id}}</td>
		<td title="{{value.title}}">{{value.title}}</td>
		<td>{{value.addtime}}</td>
		<td>{{value.price}}</td>
		<td>{{value.num}}</td>
		<td>{{value.dq_num}}</td>
	</tr>
	{{/if}}
	{{/each}}
</tbody>
</script>
<!-- 取件详情 -->
<script type="text/html" id="booksGoodsListDetail">
<colgroup>
  <col width="188">
  <col width="230">
  <col width="100">
  <col width="100">
  <!-- <col width="100"> -->
  <col width="100">
</colgroup>
<thead>
	<tr>
		<th>时间</th>
		<th>订单号</th>
		<th>状态</th>
		<th>数量</th>
		<!-- <th>金额</th> -->
		<th>操作员</th>
	</tr>
</thead>
<tbody>
	{{each list value i}}
	<tr data-index="{{i}}">
		<td>{{value.addtime}}</td>
		<td>{{value.sn}}</td>
		<td>{{value.type}}</td>
		<td>{{value.num}}</td>
		<td>{{value.worker}}</td>
	</tr>
	{{/each}}
</tbody>
</script>
</body>
</html>
