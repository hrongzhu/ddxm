<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:49:"themes/admin_simpleboot3/admin\order\deliver.html";i:1554867186;s:45:"themes/admin_simpleboot3/public\ptheader.html";i:1554867210;s:45:"themes/admin_simpleboot3/public\ptfooter.html";i:1554867211;}*/ ?>
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

<div class="panel-body table-responsive-y">
    <div class="panel-head bg-main">
        <span>商品基本信息:</span>
    </div>
    <table class="table table-hover table-bordered table-striped panel">

            <tr>
                <th >ID</th>
                <th >商品ID</th>
                <th>商品名称</th>
                <th>商品价格</th>
                <th>数量</th>
                <th>总价</th>
                <th>发货仓库选择</th>
                <th>成本价</th>
                <th>门店进价（虚拟成本）</th>
            </tr>
        <tbody id="goods-list">
            <?php if(is_array($goods_list) || $goods_list instanceof \think\Collection || $goods_list instanceof \think\Paginator): $i = 0; $__LIST__ = $goods_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
            <tr>
                <td><?php echo $v['id']; ?></td>
                <td><?php echo $v['item_id']; ?></td>
                <td><?php echo $v['subtitle']; ?></td>
                <td><?php echo $v['price']; ?></td>
                <td><?php echo $v['num']; ?></td>
                <td><?php echo $v['num'] * $v['price']; ?></td>
                <?php if($v['shop_id'] == 0): ?>
                    <td>
                        <select class="input" onchange="getPrice($(this))" >
                            <option value="0" selected='selected'>不选择库存</option>
                            <option value="<?php echo $v['shop_info']['id']; ?>"><?php echo $v['shop_info']['name']; ?>【<?php echo $v['stock']; ?>】</option>
                        </select>
                    </td>
                <?php else: ?>
                    <td>
                        <?php if($v['is_stock'] == 0 && $v['shop_id'] == 0): ?>
                            没有库存
                        <?php else: ?>
                            <select class="input" onchange="getPrice($(this))" >
                                <option value="0">不选择库存</option>
                                <option value="<?php echo $v['shop_info']['id']; ?>" <?php echo $v['shop_id']==$v['shop_info']['id']?"selected='selected'":""; ?>><?php echo $v['shop_info']['name']; ?>【<?php echo $v['stock']; ?>】</option>
                            </select>
                        <?php endif; ?>
                    </td>
                <?php endif; ?>
                <td><input type="text" class="input cost-price"  value="<?php echo (isset($v['cost_price']) && ($v['cost_price'] !== '')?$v['cost_price']:'0.00'); ?>" placeholder="0.00"></td>
                <td><input type="text" class="input faker-price"  value="<?php echo (isset($v['faker_price']) && ($v['faker_price'] !== '')?$v['faker_price']:'0.00'); ?>"  placeholder="0.00"></td>
            </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
    </table>
    <br>
    <div class="form-x form-inline">
            <div class="form-group">
                <label for="pid">物流公司：</label>
                <lable>
                    <select class="input" name="express_id" id="express_id" >
                         <option value="">选择物流公司</option>
                         <?php if($orderExpress != null): foreach($expressInfo as $v): ?>
                                 <option  <?php echo $orderExpress['express_name']==$v->express_name?'selected = "selected"' : ''; ?> value="<?php echo $v->id; ?>"><?php echo $v->express_name; ?></option>
                             <?php endforeach; else: foreach($expressInfo as $v): ?>
                                 <option  value="<?php echo $v->id; ?>"><?php echo $v->express_name; ?></option>
                             <?php endforeach; endif; ?>
                     </select>
                </lable>
            </div> &emsp;
            <label for="pid">快递单号：</label>
            <lable>
                <div class="form-group">
                      <input type="text" class="input" name="express_sn" id="express_sn"  value="<?php echo $orderExpress['express_sn']; ?>" placeholder="快递单号">
                </div>
            </lable>

            <div class="margin-top">
                <?php if($orderExpress['id'] == null): ?>
                    <button id="delive" class="button bg-green">确认发货</button>
                    <button id="save" class="button bg-main">保存数据</button>
                <?php else: ?>
                    <button id="delive" class="button bg-green">更新物流</button>
                <?php endif; ?>
            </div>
        </div>
    <input type="hidden" class="input" name="order_id" id="order_id"  value="<?php echo $order_id; ?>" placeholder="">
    <input type="hidden" class="input" name="orderExpress_id" id="orderExpress_id"  value="<?php echo $orderExpress['id']; ?>" placeholder="">
    </div>
<script src="__STATIC__/js/admin.js"></script>
<script type="text/javascript">
    $('#delive').click(function(){
        $express_id = $('#express_id').val();
        $orderExpress_id= $('#orderExpress_id').val();
        $express_sn =  $('#express_sn').val();
        $order_id = $('#order_id').val();
        var lists = {
            order_id:$order_id,
            goods_list:[],
            express_data:{express_id:$express_id,orderExpress_id:$orderExpress_id,express_sn:$express_sn}
        };
        $('#goods-list tr').each(function(i){
            var list = {
                id:0,
                item_id:0,
                num:0,
                shop_id:0,
                cost_price: 0,
                faker_price: 0,
                send_by_purchase:1
            };
            $(this).children('td').each(function(j){
                if (j === 0) {
                    list.id = $(this).text();
                }
                if (j === 1 ) {
                    list.item_id = $(this).text();
                }
                if (j === 4 ) {
                    list.num = $(this).text();
                }
                if (j === 6 ) {
                    if ( $(this).text() == '没有库存') {
                        list.shop_id = 0;
                    }else{
                        list.shop_id = $(this).find('select option:selected').val();
                    }
                    if($(this).find('select option:selected').val()==0){
                        list.send_by_purchase = 0;
                    }
                }
                if (j === 7 ) {
                    list.cost_price = $(this).find('input').val();
                }
                if (j === 8 ) {
                    list.faker_price = $(this).find('input').val();
                }
            });
            lists.goods_list.push(list);
        });
        // console.log(lists);return false;
        $.post('<?php echo url("Order/deliver"); ?>', {lists}, function(data){
            if (data.code == 200) {
                layer.msg(data.msg, {icon: 1,time: 1500},function(){
                    var index = parent.layer.getFrameIndex(window.name);
                    parent.layer.close(index);
                });
            }else {
                layer.msg(data.msg, {icon: 0,time: 1500});
            }
        },'json');
    });

    //保存数据
    $('#save').click(function(){
        $order_id = $('#order_id').val();
        var lists = {
            order_id:$order_id,
            goods_list:[]
        };
        $('#goods-list tr').each(function(i){
            var list = {
                order_goods_id:0,
                item_id:0,
                num:0,
                shop_id:0,
                cost_price: 0,
                faker_price: 0
            };
            $(this).children('td').each(function(j){
                if (j === 0) {
                    list.order_goods_id = $(this).text();
                }
                if (j === 1 ) {
                    list.item_id = $(this).text();
                }
                if (j === 4 ) {
                    list.num = $(this).text();
                }
                if (j === 6 ) {
                    if ( $(this).text() == '没有库存') {
                        list.shop_id = 0;
                    }else{
                        list.shop_id = $(this).find('select option:selected').val();
                    }
                }
                if (j === 7 ) {
                    list.cost_price = $(this).find('input').val();
                }
                if (j === 8 ) {
                    list.faker_price = $(this).find('input').val();
                }
            });
            lists.goods_list.push(list);
        });
        // console.log(lists);return false;
        $.post('<?php echo url("Order/saveOrderTempData"); ?>', {lists}, function(data){
            if (data.code == 200) {
                layer.msg(data.msg, {icon: 1,time: 1500},function(){
                   var index = parent.layer.getFrameIndex(window.name);
                    parent.layer.close(index);
                });
            }else {
                layer.msg(data.msg, {icon: 0,time: 1500});
            }
        },'json');
    });

    function getPrice(obj){
        $shop_id = obj.val();
        if ($shop_id > 0) {
            var stockObj = obj.text();
            $start = stockObj.indexOf('【');
            $end = stockObj.indexOf('】');
            var stock = stockObj.substring($start +1,$end);
            var num = obj.parent().prev().prev().text();
            var item_id = obj.parent().parent().children().first().next().text();
            var order_id = $("#order_id").val();
            if (parseInt(stock) < parseInt(num)) {
                layer.open({
                      title: '温馨提示'
                      ,content: '库存不足，请选择其他方式发货'
                    });
                obj.find('option').first().prop("selected", 'selected');
                obj.parent().next().children().val('0');
                obj.parent().next().next().children().val('0');
                return false;
            }
            $.post("<?php echo url('order/getItemCostPriceInStock'); ?>", {"order_id":order_id,'item_id':item_id}, function(res){
                var num = obj.parent().prev().prev().text();
                obj.parent().next().children().val(res.data.cost_price * num);
                obj.parent().next().next().children().val(res.data.faker_price * num);
            })
        }else{
            obj.parent().next().children().val(0);
            obj.parent().next().next().children().val(0)
        }
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

