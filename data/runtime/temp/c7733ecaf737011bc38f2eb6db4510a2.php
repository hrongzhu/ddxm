<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:48:"themes/admin_simpleboot3/admin\bespoke\list.html";i:1554867200;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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
        <li class="active"><a href="<?php echo url('Bespoke/list'); ?>">预约列表</a></li>
    </ul>
    <form class="well form-inline margin-top-20" method="get"
          action="<?php echo url('Bespoke/list'); ?>">
        手机号:
        <input type="text" class="form-control" name="search" style="width: 120px;" value="<?php echo $search; ?>">
        <input type="hidden" name="is_pan" value="1">
        所属店铺:
        <select class="form-control" name="shop_id">
            <option value="">请选择</option>
            <?php foreach($shoplist as $v): ?>
                <option
                <?php if($shop_id == $v['id']): ?>selected<?php endif; ?>
                value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
            <?php endforeach; ?>
        </select>
        服务人员:
        <select class="form-control" name="workid">
            <option value="">请选择</option>
            <?php foreach($workerlist as $v): ?>
                <option
                <?php if(isset($workid)&&$workid == $v['id']): ?>selected<?php endif; ?>
                value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
            <?php endforeach; ?>
        </select>
        预约项目:
        <select class="form-control" name="service_id">
            <option value="-1">请选择</option>
            <?php foreach($service_list as $v): ?>
                <option
                <?php if(isset($service_id)&&$service_id == $v['id']): ?>selected<?php endif; ?>
                value="<?php echo $v['id']; ?>"><?php echo $v['sname']; ?></option>
            <?php endforeach; ?>
        </select>
        状态:
        <select class="form-control" name="status">
            <option value="-10">请选择</option>
            <option value="0" <?php if(isset($status)&&$status==0): ?>selected<?php endif; ?>>已预约</option>
            <option value="1" <?php if(isset($status)&&$status==1): ?>selected<?php endif; ?>>已完成</option>
            <option value="3" <?php if(isset($status)&&$status==3): ?>selected<?php endif; ?>>已取消</option>
        </select>
        支付方式:
        <select class="form-control" name="pay_way">
            <option value="">请选择</option>
            <option value="1" <?php if(isset($pay_way)&&$pay_way==1): ?>selected<?php endif; ?>>微信</option>
            <option value="2" <?php if(isset($pay_way)&&$pay_way==2): ?>selected<?php endif; ?>>支付宝</option>
            <option value="3" <?php if(isset($pay_way)&&$pay_way==3): ?>selected<?php endif; ?>>会员卡</option>
            <option value="4" <?php if(isset($pay_way)&&$pay_way==4): ?>selected<?php endif; ?>>银行卡</option>
            <option value="5" <?php if(isset($pay_way)&&$pay_way==5): ?>selected<?php endif; ?>>现金</option>
        </select>
        <input type="submit" class="btn btn-primary" value="搜索"/>
        <button class="btn btn-danger" id="reset">清空</button>
    </form>
    <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th width="50">ID</th>
            <th>预约人手机号</th>
            <th>预约服务人员</th>
            <th>预约项目</th>
            <th>预约门店</th>
            <th>预约时间</th>
            <th>状态</th>
            <th>支付方式</th>
            <th>预约客户</th>
            <th>价格</th>
            <th>添加时间</th>
            <th width="130">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $key=>$vo): ?>
            <tr>
                <td><?php echo $vo['id']; ?></td>
                <td><?php echo $vo['mobile']; ?></td>
                <td date="<?php echo $vo['id']; ?>"><span class="jing"><?php echo $vo['name']; ?></span></td>
                <!--<?php if($vo['type'] == 0): ?>-->
                    <!--<td>游泳</td>-->
                    <!--<?php elseif($vo['type'] == 1): ?>-->
                        <!--<td>推拿</td>-->
                        <!--<?php elseif($vo['type'] == 2): ?>-->
                            <!--<td>成人推拿</td>-->
                <!--<?php endif; ?>-->
                <?php foreach($service_list as $v): if($vo['type']==$v['id']): ?><td><?php echo $v['sname']; ?></td><?php endif; endforeach; ?>
                <td><?php echo $vo['shop_name']; ?></td>
                <td><?php echo $vo['yytime']; ?></td>
                <?php if($vo['status'] == 0): ?>
                    <td>已预约</td>
                    <?php elseif($vo['status'] == 1): ?>
                        <td>已完成</td>
                        <?php elseif($vo['status'] == 2): ?>
                            <td>已过期</td>
                            <?php elseif($vo['status'] == 3): ?>
                                <td>已取消</td>
                <?php endif; ?>
                </if>
                <?php if($vo['pay_way'] == 0): ?>
                    <td>--</td>
                <?php endif; if($vo['pay_way'] == 1): ?>
                    <td>微信支付</td>
                <?php endif; if($vo['pay_way'] == 2): ?>
                    <td>支付宝支付</td>
                <?php endif; if($vo['pay_way'] == 3): ?>
                    <td>会员卡支付</td>
                <?php endif; ?>
                <td><?php echo $vo['nickname']; ?></td>
                <td><?php echo $vo['amount']; ?></td>
                <td><?php echo $vo['addtime']; ?></td>
                <td>
                    <?php if($vo['status'] == 0): ?>
                        <a href="<?php echo url('Bespoke/cancel',array('id'=>$vo['id'])); ?>">取消</a>
                        <!-- <a href="<?php echo url('Bespoke/complete',array('id'=>$vo['id'])); ?>">完成</a> -->
                        <a href="javascript:void (0);"
                           onclick="edit_advert('编辑广告','<?php echo url('Bespoke/complpage',array('id'=>$vo['id'],'workerid'=>$vo['workerid'])); ?>',500,300)">完成</a>
                        <?php else: ?>
                        订单已结束
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
    <div class="pagination"><?php echo $page; ?></div>
</div>
<script src="__STATIC__/js/admin.js"></script>
<script type="text/javascript">
    function edit_advert(title, url, w, h) {
        layer_show(title, url, w, h);
    }

    $(function () {
        $('#shopchange').change(function () {
            $('#from').submit();
        });
        $('#reset').click(function(){
            $('input[name=search]').val('')
            $('select[name=shop_id]').val('')
            $('select[name=workid]').val('')
            $('select[name=service_id]').val('')
            $('select[name=status]').val('')
            $('select[name=pay_way]').val('')
        })
    })
    $(document).on('click', '.jing', function () {
        old_val = $(this).html();
        $(this).parent().html("<input class='uid' type=\'text\' value=" + old_val + ">");
        $(document).on('blur', '.uid', function () {
            var obj = $(this);
            var id = $(this).parent().attr('date'); //获取要修改内容的id
            var val = $(this).val(); //获取修改后的值
            console.log(id);
            console.log(val);
            $.ajax({
                type: 'post',
                url: "<?php echo url('Bespoke/waiter'); ?>",
                data: {
                    id: id,
                    name: val
                },
                success: function (msg) {
                    if (msg == 1) {
                        obj.parent().html("<span class='jing'>" + val + "</span>");
                    } else if (msg == 0) {
                        obj.parent().html("<span class='jing'>" + old_val + "</span>");
                    } else if (msg == 2) {
                        art.dialog({
                            content: "没有这个员工",
                            icon: 'warning',
                            ok: function () {
                                this.title("没有这个员工");
                                return true;
                            }
                        });
                    } else if (msg == 3) {
                        art.dialog({
                            content: "员工不属于这个门店",
                            icon: 'warning',
                            ok: function () {
                                this.title("员工不属于这个门店");
                                return true;
                            }
                        });
                    }
                }
            })
        })
    })
</script>
</body>
</html>
