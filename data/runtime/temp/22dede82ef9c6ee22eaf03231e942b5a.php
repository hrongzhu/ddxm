<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:52:"themes/admin_simpleboot3/admin\goods_book\index.html";i:1554867193;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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
		<ul class="nav nav-tabs">
			<li  class="active"><a href="<?php echo url('GoodsBook/bookList'); ?>">预订清单</a></li>
		</ul>
		<form class="well form-inline margin-top-20" method="get" action="<?php echo url('GoodsBook/bookList'); ?>">
            选择门店:
			<select class="form-control" name="shop_id">
                <option value="">请选择</option>
		        <?php foreach($shopDatas as $v): ?>
		            <option <?php if($shop_id == $v['id']): ?>selected<?php endif; ?> value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
		        <?php endforeach; ?>
		    </select>
			手机号:
            <input type="text" class="form-control" name="mobile" style="width: 120px;" value="<?php echo (isset($mobile) && ($mobile !== '')?$mobile:''); ?>">
			会员昵称:
			<input type="text" class="form-control" name="nick_name" style="width: 120px;" value="<?php echo (isset($nick_name) && ($nick_name !== '')?$nick_name:''); ?>">
			商品名称:
			<input type="text" class="form-control" name="item_name" style="width: 120px;" value="<?php echo (isset($item_name) && ($item_name !== '')?$item_name:''); ?>">
			预订单号:
			<input type="text" class="form-control" name="sn" style="width: 120px;" value="<?php echo (isset($sn) && ($sn !== '')?$sn:''); ?>">
            预订时间:
            <input type="text" style="width: 12%;" class="form-control" name="book_time" <?php if(isset($add_s)&&isset($add_e)): ?> value="<?php echo date("Y-m-d",$add_s); ?> ~ <?php echo date("Y-m-d",$add_e); ?>"<?php endif; ?> id="add-time" placeholder="选择添加时间">
            <input type="submit" class="btn btn-primary" value="搜索" />
        </form>
		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th>预订门店</th>
					<th>手机号</th>
					<th>会员昵称</th>
					<th>商品名称</th>
					<th>预订单号</th>
					<th>预定日期</th>
					<th>实付单价</th>
					<th>预订数量</th>
					<th>待取数量</th>
					<th width="130">操作</th>
				</tr>
			</thead>
			<tbody>
			<?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): if( count($data)==0 ) : echo "" ;else: foreach($data as $key=>$vo): ?>
				<tr data-order-goods-id="<?php echo $vo['order_goods_id']; ?>" data-pay-way="<?php echo $vo['pay_way']; ?>">
					<td><?php echo $vo['name']; ?></td>
					<td><?php echo $vo['member_mobile']; ?></td>
					<td class="nickname"><?php echo $vo['nickname']; ?></td>
					<td><?php echo $vo['subtitle']; ?></td>
					<td><?php echo $vo['sn']; ?></td>
					<td><?php echo date("Y-m-d H:i:s",$vo['paytime']); ?></td>
					<td class="deal_price"><?php echo $vo['price']-$vo['ticket_deduction']; ?></td>
					<td><?php echo $vo['num']; ?></td>
					<td class="dq_num"><?php echo $vo['dq_num']; ?></td>
					<td>
						<a href="javascript:void(0)" class="book_history">订取明细</a>
						<a href="javascript:void(0)" class="refund">退款</a>
					</td>
				</tr>
				<?php endforeach; endif; else: echo "" ;endif; ?>
			</tbody>
		</table>
		<div class="pagination"><?php echo $page; ?></div>
	</div>

	<!--订取明细模态框-->
	<div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">订取明细</h4>
				</div>
				<div class="modal-body">
					<table class="table table-bordered" id="historyTable">
						<thead>
							<tr>
								<th>时间</th>
								<th>订单号</th>
								<th>状态</th>
								<th>数量</th>
								<th>金额</th>
								<th>成本</th>
								<th>操作人</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<!--退款模态框-->
	<div class="modal fade" id="refundModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="checkModalLabel">退款数量</h4>
				</div>
				<div class="modal-body">
					<input class="form-control" type="text" id="refund_num" placeholder="输入退款数量">
					<div id="toast"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" id="refundSubmit">确定</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				</div>
			</div>
		</div>
	</div>


	<script src="__STATIC__/js/admin.js"></script>
    <script src="__STATIC__/js/laydate/laydate.js"></script>

    <script type="text/javascript">
        function accMul(arg1,arg2)
        {
            var m=0,s1=arg1.toString(),s2=arg2.toString();
            try{m+=s1.split(".")[1].length}catch(e){}
            try{m+=s2.split(".")[1].length}catch(e){}
            return Number(s1.replace(".",""))*Number(s2.replace(".",""))/Math.pow(10,m)
        }
        laydate.render({
            elem: '#add-time'
            ,type: 'date',
            range:'~'
        });

        laydate.render({
            elem: '#check-time'
            ,type: 'date',
            range:'~'
        });
        $('.book_history').click(function(){
            $('#historyTable tbody').html('');
            $.post("<?php echo url('GoodsBook/viewBookHistory'); ?>",{'order_goods_id':$(this).parent().parent().attr('data-order-goods-id')},function(res){
                if(res.code==200){
                    var htmls='';
                    $.each(res.data,function(k,v){
                        htmls+='<tr><td>'+v.addtime+'</td><td>'+v.sn+'</td><td>'+v.type+'</td><td>'+v.num+'</td><td>'+(v.price-v.ticket_deduction)+'</td><td>'+v.oprice+'</td><td>'+v.worker+'</td></tr>';
                    })
                    $('#historyTable tbody').append(htmls)
                    $('#historyModal').modal('show');
                }
            })
		})
		var nickname;
        var dealprice;
        var payway;
        var dqnum;
        var order_goods_id;
		$('.refund').click(function () {
		    nickname = $(this).parent().prevAll('.nickname').html();
		    dealprice = $(this).parent().prevAll('.deal_price').html();
			payway = $(this).parent().parent().attr('data-pay-way');
			dqnum = $(this).parent().prev().html();
			//console.log(dqnum);
			order_goods_id = $(this).parent().parent().attr('data-order-goods-id');
            // if ($(this).parent().parent().attr('data-pay-way') == 3) {
				// $('#toast').html()
            // }
			$('#refundModal').modal('show');
        })
		$("#refund_num").on("input propertychange",function(){
		    var html = '';
		    if(Number($(this).val())>Number(dqnum)){
		        layer.msg('退款数量不能大于待取货数量！');
		        $(this).val('')
			}else {
				if (payway == 3) {
					html = '预订款&yen;'+accMul(dealprice,$(this).val())+'将退回至'+nickname+'会员卡内';
				}else{
					html = '请退还'+nickname+'现金&yen;'+accMul(dealprice,$(this).val())+'元';
				}
				$('#toast').html(html)
			}
		})
		$('#refundSubmit').click(function () {
            $.post("<?php echo url('GoodsBook/refund'); ?>",{'order_goods_id':order_goods_id,'refund_num':$('#refund_num').val()},function(res){
                if(res.code==200){
                    layer.msg('退款成功',{'icon':1},function(){
                        self.location.reload()
					})
                }else {
                    layer.msg('退款失败')
				}
            })
        })
	</script>


</body>
</html>
