<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:61:"themes/admin_simpleboot3/admin\order\recharge_order_list.html";i:1554867186;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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
    <form class="well form-inline margin-top-0" method="get" action="<?php echo url('Order/rechargeOrderList'); ?>">
        <input type="text" class="form-control" name="sn" style="width: 120px;" value="<?php echo input('request.sn/s',''); ?>"
               placeholder="订单号">
        <input type="text" class="form-control" name="keywords" style="width: 120px;" <?php if(isset($keywords)): ?> value="<?php echo $keywords; ?>"<?php endif; ?>
        placeholder="会员账号或昵称">
        <input type="text" id="time" style="width: 12%;" class="form-control" name="time"
        <?php if(isset($add_s)&&isset($add_e)): ?> value="<?php echo date("Y-m-d",$add_s); ?> ~ <?php echo date("Y-m-d",$add_e); ?>"<?php endif; ?>
        placeholder="选择时间段">
        <select class="form-control" name="pay_way">
            <option value="">选择付款方式</option>
            <option <?php echo $pay_way==1?'selected':""; ?> value="1">微信</option>
            <option <?php echo $pay_way==2?'selected':""; ?> value="2">支付宝</option>
            <option <?php echo $pay_way==3?'selected':""; ?> value="3">余额</option>
            <option <?php echo $pay_way==4?'selected':""; ?> value="4">银行卡</option>
            <option <?php echo $pay_way==5?'selected':""; ?> value="5">现金</option>
            <option <?php echo $pay_way==6?'selected':""; ?> value="6">美团</option>
            <option <?php echo $pay_way==7?'selected':""; ?> value="7">赠送</option>
        </select>
        <select class="form-control" name="shop_id">
            <option value="">选择店铺</option>
            <?php foreach($shopDatas as $v): ?>
                <option <?php if($shop_id == $v['id']): ?>selected<?php endif; ?> value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
            <?php endforeach; ?>
        </select>
        <?php if(isset($check_status)): ?><?php echo $check_status; endif; ?>
        <select class="form-control" name="check_status">
            <option value="">选择对账状态</option>
            <option value="1" <?php if(isset($check_status)&&$check_status==1): ?>selected<?php endif; ?>>已对账</option>
            <option value="0" <?php if(isset($check_status)&&$check_status==0): ?>selected<?php endif; ?>>未对账</option>
        </select>
        <input type="submit" class="btn btn-primary" value="搜索"/>
        <a class="btn btn-danger" id="reset" href="javascript:void(0)">清空</a>
    </form>
    <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th><input type="checkbox" name="all"></th>
            <th width="50">ID</th>
            <th>交易时间</th>
            <th>订单号</th>
            <th>所属门店</th>
            <th>商品名称</th>
            <th>会员昵称</th>
            <th>会员账号</th>
            <th>订单来源</th>
            <th>支付方式</th>
            <th>卡面金额</th>
            <th>充值金额</th>
            <th>是否改价</th>
            <th>改价原因</th>
            <th>状态</th>
        </tr>
        </thead>
        <tbody>
        订单数:<?php echo $total; ?>&emsp;&emsp;&emsp;合计金额：<?php echo $total_money; ?>
        <span style="float: right"><button class="btn btn-sm" id="batch_check">批量对账</button></span>
        <?php if(is_array($datas) || $datas instanceof \think\Collection || $datas instanceof \think\Paginator): if( count($datas)==0 ) : echo "" ;else: foreach($datas as $key=>$v): ?>
            <tr style="height: 55px;">
                <td><input type="checkbox" name="ids" value="<?php echo $v['id']; ?>"></td>
                <td><?php echo $v['id']; ?></td>
                <td><?php echo date('Y-m-d H:i',$v['paytime']); ?></td>
                <td><?php echo $v['sn']; ?></td>
                <td><?php echo $v['shop_id']; ?></td>
                <td style="overflow: hidden;text-overflow: ellipsis;white-space: nowrap; max-width: 200px;">
                    <?php echo $v['subtitle']; ?>
                </td>
                <td><?php echo $v['nickname']; ?></td>
                <td><?php echo $v['mobile']; ?></td>
                <?php if($v['is_online']==1): ?>
                    <td>线上支付</td>
                <?php endif; if($v['is_online']==0): ?>
                    <td>门店收银</td>
                <?php endif; ?>
                <td><?php echo $v['pay_way']; ?></td>
                <td><?php echo $v['old_amount']; ?></td>
                <td><?php echo $v['amount']; ?></td>
                <td><?php echo $v['edit_price']==1?"是":"否"; ?></td>
                <td><?php echo $v['edit_remark']; ?></td>
                <td><?php echo $v['check_status']==1?"已对账":"未对账"; ?></td>
            </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
    <!--<div class="pagination" style="float: left">-->
    <!--<button class="btn btn-success" id="selectAll">全选</button>-->
    <!--<button class="btn btn-warning" id="closeAll" style="margin-left: 10px;">关闭订单</button>-->
    <!--<button class="btn btn-danger" id="delAll" style="margin-left: 10px;">删除订单</button>-->
    <!--</div>-->
    <div style="float: left;margin-left: 10px;" class="pagination"><?php echo $page; ?></div>
</div>
<script src="__STATIC__/js/admin.js"></script>
<script src="__STATIC__/js/laydate/laydate.js"></script>
<script type="text/javascript">
    //------------------------------------------------
    $(function () {
        //全选/全不选
        $('input[name=all]').click(function () {
            $("input[name='ids']").prop("checked", $(this).prop("checked"));
        });
        //批量对账
        $('#batch_check').click(function () {
            var ids = [];
            $("input:checkbox[name='ids']:checked").each(function(k,v){
                ids.push($(v).val());
            })
            $.post("<?php echo url('Order/batchCheck'); ?>",{
                'id':JSON.stringify(ids)
            },function(res){
                if(res.code==1){
                    layer.msg('批量对账成功',{'icon':1},function(){
                        reloads()
                    })
                }else {
                    layer.msg('批量对账失败',{'icon':2},function(){
                        reloads()
                    })
                }
            },'JSON')
        });
        $('#reset').click(function(){
            $("input[name='sn']").val('');
            $("#time").val('');
            $("select[name='shop_id']").val('');
            $("input[name='keywords']").val('');
            $("select[name='check_status']").val('');
        })
    })

    //删除订单
    function delete_order(url, id, obj) {
        layer.confirm('确认要删除？', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            $.post(url, {id: id}, function (data) {
                if (data.code == 200) {
                    var link = obj.parents("tr");
                    link.remove();
                    layer.msg(data.msg, {icon: 1, time: 1000});
                } else {
                    layer.msg(data.msg, {icon: 0, time: 1000});
                }
            });
        });
    }

    //刷新页面
    function reloads() {
        window.location.reload();
    }

    laydate.render({
        elem: '#time',
        type: 'date',
        range: '~'
    });

</script>
</body>
</html>
