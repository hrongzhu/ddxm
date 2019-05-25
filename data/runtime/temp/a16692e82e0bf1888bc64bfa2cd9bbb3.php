<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:55:"themes/admin_simpleboot3/admin\collect\today_order.html";i:1554867196;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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

    <form class="well form-inline margin-top-0" method="get" action="<?php echo url('Collect/orderList'); ?>">
        <input type="text" class="form-control" name="sn" style="width: 120px;" value="<?php echo input('request.sn/s',''); ?>"
               placeholder="订单号">
        <input type="text" class="form-control" name="mobile" style="width: 120px;"
               value="<?php echo input('request.mobile/s',''); ?>" placeholder="用户账号|电话">
        <input type="text" class="form-control" id="slay_date" name="start_time" style="width: 120px;"
               value="<?php echo input('request.start_time/s',''); ?>" placeholder="开始时间">
        <input type="text" class="form-control" id="elay_date" name="end_time" style="width: 120px;"
               value="<?php echo input('request.end_time/s',''); ?>" placeholder="结束时间">
        <select class="form-control" name="pay_way">
            <option value="">选择付款方式</option>
            <option <?php echo $pay_way==1?'selected':""; ?> value="1">微信</option>
            <option <?php echo $pay_way==2?'selected':""; ?> value="2">支付宝</option>
            <option <?php echo $pay_way==3?'selected':""; ?> value="3">余额</option>
            <option <?php echo $pay_way==4?'selected':""; ?> value="4">银行卡</option>
            <option <?php echo $pay_way==5?'selected':""; ?> value="5">现金</option>
            <option <?php echo $pay_way==6?'selected':""; ?> value="6">美团</option>
            <option <?php echo $pay_way==7?'selected':""; ?> value="7">赠送</option>
            <option <?php echo $pay_way==8?'selected':""; ?> value="8">门店自用</option>
            <option <?php echo $pay_way==10?'selected':""; ?> value="10">包月服务</option>
            <option <?php echo $pay_way==11?'selected':""; ?> value="11">定制疗程</option>
        </select>
        <!-- 交易内容:
        <select class="form-control" name="buy_type">
            <option value="">请选择</option>
            <option <?php echo $buy_type==1?'selected':""; ?> value="1">商品</option>
            <option <?php echo $buy_type==2?'selected':""; ?> value="2">服务</option>
        </select> -->
        <?php if($show_shop): ?>
            <select class="form-control" id="shop_id" onchange="get_worker($(this))" name="shop_id">
                <option value="">选择门店</option>
                <?php foreach($shoplist as $v): ?>
                    <option
                    <?php if($shop_id == $v['id']): ?>selected<?php endif; ?>
                    value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>
        <select class="form-control" id="workid" name="workid">
            <option value="">选择服务人员</option>
        </select> &emsp;&emsp;
        <input type="submit" class="btn btn-primary" value="搜索"/>
    </form>
    <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th width="50">ID</th>
            <th>订单类型</th>
            <th>时间</th>
            <th>订单号</th>
            <th>会员账号</th>
            <th>会员昵称</th>
            <th>交易门店</th>
            <th>服务人员</th>
            <th>交易渠道</th>
            <th>付款方式</th>
            <th>交易内容</th>
            <th>实收金额</th>
            <?php if (isset($shows)): ?>
                <th >公司成本</th>
                <th >门店成本</th>
            <?php endif ?>
            <th>是否改价</th>
            <th>操作</th>
        </tr>
        </thead>
        <!--<button class="btn btn-danger" id="export" style="float: right">导出excel</button>-->
        <a href="<?php echo url('Collect/export',[
        'start_time'=>$start_time,
        'end_time'=>$end_time,
        'sn'=>$sn,
        'mobile'=>$mobile,
        'pay_way'=>$pay_way,
        'workid'=>$workid,
        'shop_id'=>$shop_id
        ]); ?>" class="btn btn-danger" style="float: right">导出excel</a>
        <tbody>
        <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $key=>$v): ?>
            <tr style="height: 55px;">
                <td><?php echo $v['id']; ?></td>
                <td><?php echo $v['order_type']; ?></td>
                <td><?php echo date('Y-m-d H:i:s',$v['addtime']); ?></td>
                <td><?php echo $v['sn']; ?></td>
                <td><?php echo (isset($v['mobile']) && ($v['mobile'] !== '')?$v['mobile']:'无'); ?></td>
                <td><?php echo (isset($v['nickname']) && ($v['nickname'] !== '')?$v['nickname']:'非会员'); ?></td>
                <td><?php echo $v['shop_info']['name']; ?></td>
                <td><?php echo (isset($v['waiter']) && ($v['waiter'] !== '')?$v['waiter']:'无'); ?></td>
                <td><?php echo !empty($v['is_online'])?'线上商城':'线下收银'; ?></td>
                <td><?php echo $v['pay_way']; ?></td>
                <td><?php echo $v['buy_type']; ?></td>
                <td <?php echo !empty($v['is_refund'])?"style='color: red;'":"";; ?>><?php echo $v['amount']; ?></td>
                <?php if (isset($shows)): ?>
                    <th ><?php echo $v['cost_price']; ?></th>
                    <th ><?php echo $v['faker_price']; ?></th>
                <?php endif ?>
                <td><?php echo !empty($v['is_eprice'])?"<span style='color: orange;font-size: 25px;'>是</span>":"否"; ?></td>
                <td>
                    <a href="javascript:;" onclick="detail('查看详情','<?php echo url('collect/detail', ['id'=>$v['id']]); ?>',800,800)">查看详情</a>
                    <!-- <a href="javascript:;" onclick="add_order_goods('临时添加商品信息','<?php echo url('collect/addOrderGoods', ['id'=>$v['id']]); ?>')">临时添加商品信息</a> -->
                </td>
            </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
    <div style="float: left;margin-left: 10px;" class="pagination"><?php echo $page; ?></div>
</div>
<script src="__STATIC__/js/admin.js"></script>
<script src="__STATIC__/js/laydate/laydate.js"></script>
<script type="text/javascript">
    /*编辑*/
    function detail(title, url) {
        layer_show(title, url);
    }

    function add_order_goods(title, url) {
        layer_show(title, url,1000);
    }

    //日期时间选择器
    laydate.render({
        elem: '#slay_date'
        , type: 'date'
    });

    //日期时间选择器
    laydate.render({
        elem: '#elay_date'
        , type: 'date'
    });


    // 门店切换服务人员
    $(function () {
        var obj = $('#shop_id');
        get_worker(obj);
    })

    // $(function () {
    //     $('#export').click(function () {
    //         $.post("<?php echo url('Collect/export'); ?>",{
    //
    //         },function(res){
    //
    //         })
    //     })
    // })

    function get_worker(obj) {
        var shop_id = obj.val();
        var url = "<?php echo url('collect/getWorkerList'); ?>";
        $.post(url, {shop_id: shop_id}, function (res) {
            $shtml = '';
            if (res.code === 200) {
                $shtml += `<option value="">选择服务人员</option>`;
                $.each(res.data, function (k, v) {
                    $shtml += `<option value="${v.id}">${v.name}</option>`;
                });
                $('#workid').html($shtml);
            } else {
                $shtml = `<option value="">选择服务人员</option>`;
                $('#workid').html($shtml);
            }
        }, 'json');
    }
</script>
</body>
</html>
