<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:51:"themes/admin_simpleboot3/admin\shop\scheduling.html";i:1554867181;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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

<link rel="stylesheet" href="__STATIC__/css/paiban.css">
</head>
<body>
<div class="wrap js-check-wrap">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <h3>员工排班 -- <?php echo $info['name']; ?>(工号:<?php echo $info['workid']; ?>)</h3>
            </tr>
        </thead>
        <tbody>
            <tr>
                <button class="btn btn-success" id="iswork"><?php echo $info['iswork']; ?></button>
            </tr>
        </tbody>
    </table>
    <div id="table_time_selected" class="table_time_selected"></div>
</div>
<script src="__STATIC__/js/admin.js"></script>
<script src="__STATIC__/js/paiban.js"></script>
<script type="text/javascript">
    $(function(){
        var workid = "<?php echo $info['workid']; ?>";
        var wid = "<?php echo $info['id']; ?>";
        //提交后台需要的数据
        $('.submit').click(function () {
            var arrData = {}
            $.each($(this).parent().find('td'), function(item, index, arr){
                if ($(this).hasClass('Selected')) {
                    if (!arrData[$(this).parent().find('th').attr('week')]) {
                        arrData[$(this).parent().find('th').attr('week')] = []
                    }
                    arrData[$(this).parent().find('th').attr('week')].push($(this).index() - 1)
                }
            })
            $.post("<?php echo url('shop/scheduling'); ?>",{workid:workid,wid:wid,data:arrData},function(res){
                if (res.code == 200){
                    layer.msg(res.msg, {icon: 1, time: 2000});
                    setTimeout('reloads()', 2000);
                }else{
                    layer.msg(res.msg, {icon: 0, time: 2000});
                }
            },'json');
//            console.log(arrData)
        });

        $('#iswork').click(function(){
          $.post("<?php echo url('shop/openOrClose'); ?>",{workid:workid},function(res){
              if (res.code == 200){
                  var dos = $('#iswork').text();
                  if (dos == '开工中') {
                      $('#iswork').text('停工中');
                  }else {
                      $('#iswork').text('开工中');
                  }
                  layer.msg(res.msg, {icon: 1, time: 2000});
              }else{
                layer.msg(res.msg, {icon: 0, time: 2000});
              }
          },'json');
        })

        var extral_data = '';
        $.post("<?php echo url('shop/getworklist'); ?>",{workid:workid},function(res){
            if (res.code == 200){
                extral_data = res.data;
                if (extral_data) {
                    for (var weekday in extral_data) {
                        for (var j = 0, len=extral_data[weekday].length; j<len; j++ ) {
                            $($($('.submit').parent().find('tr')[weekday-0+1]).find('td')[extral_data[weekday][j]]).addClass('Selected')
                        }
                    }
                }
                renewal()
            }
        },'json');


    })

    //刷新页面
    function reloads(){
        window.parent.location.reload();
    }
</script>
</body>
</html>
