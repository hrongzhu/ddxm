<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:53:"themes/admin_simpleboot3/admin\shop\pick_address.html";i:1554867178;s:43:"themes/admin_simpleboot3/public\header.html";i:1554867210;}*/ ?>
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

<link rel="stylesheet" href="http://cache.amap.com/lbs/static/main1119.css" />
<link rel="stylesheet" href="http://cache.amap.com/lbs/static/AMap.DrivingRender1120.css" />
    <style>
    #container {border: 1px solid green;border-radius: 2px;margin:5px 5px; width: 86%; height: 95%; position: relative; overflow: hidden; float: left; } #tool { width: 12%; overflow: auto; box-shadow: 0 0 14px rgba(0, 0, 100, .2); height: 100%; background-color: white; float: left; z-index: 9999; } #tool button{float: right;width:110px;margin:5px 5px;}
    </style>
</head>

<body>
    <div id="container"></div>
    <div id="tool">
        <button id="circle" class="btn btn-success">画圆</button>
        <button id="rectangle" class="btn btn-success">画矩形</button>
        <button id="polygon" class="btn btn-success">画多边形</button>
        <button id="closeMouse" class="btn btn-warning">重设</button>
        <!-- <button id="testCircle" class="btn btn-success">是否在圆里</button> -->
        <!-- <button id="testPolygon" class="btn btn-success">是否在多边形里</button> -->

        <br/>
        <button id="save" class="btn btn-success">确认保存</button>
    </div>
    <input type="hidden" id="shop_id" value="<?php echo $shop_id; ?>">
    <div id="panel">
    </div>
    <div style="height: 10%;float: left">
    </div>

</body>
<!-- <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script> -->
<script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=111951780fac60c2933bd47fa387d23b&plugin=AMap.Driving,AMap.MouseTool,AMap.PolyEditor,AMap.ToolBar"></script>
<script type="text/javascript" src="http://cache.amap.com/lbs/static/DrivingRender1230.js"></script>
<script type="text/javascript" src="http://cache.amap.com/lbs/static/addToolbar.js"></script>
<script type="text/javascript">
    var status;
    var arr = [];
    var init_lineArr = <?php echo $location_list; ?>;
    var map = new AMap.Map("container", {
        resizeEnable: true,
        // center: [106.56605, 29.64915],
        center: [106.572441, 29.642759],
        zoom:13
    });
    var Polygon = new AMap.Polygon({
        path: init_lineArr,          //设置线覆盖物路径
        strokeColor: "#FF33FF", //线颜色
        strokeOpacity: 0.5, //线透明度
        strokeWeight: 3,    //线宽
        fillColor: "#1791fc", //填充色
        fillOpacity: 0.35//填充透明度
        // strokeStyle: "solid",   //线样式
        // strokeDasharray: [10, 5] //补充线样式
    });
    Polygon.setMap(map);

    map.plugin(["AMap.ToolBar"], function() { //在地图中添加ToolBar插件
        toolBar = new AMap.ToolBar();
        map.addControl(toolBar);
    });
    // toolBar.hide();

    map.plugin(["AMap.MouseTool"], function() { //鼠标工具插件
        mousetool = new AMap.MouseTool(map);
    });
    $('#circle').click(function() {
        status = 'circle';
        mousetool.circle();
    });
    $('#rectangle').click(function() {
        status = 'rectangle';
        mousetool.rectangle();
    });
    $('#polygon').click(function() {
        status = 'polygon';
        mousetool.polygon();
    });

    // 最终获取坐标
    AMap.event.addListener(mousetool, 'draw', function(e) {
        //arr = e.obj.getPath();//获取路径坐标
        if (status == 'circle') {
            lng = e.obj.getCenter().lng;
            lat = e.obj.getCenter().lat;
            radius = e.obj.getRadius();
        } else if (status == 'polygon' || status == 'rectangle') {
            var path = e.obj.getPath();
            for (var i = 0; i < path.length; i++) {
                // var lnglat = {};
                // lnglat[0] = path[i].lng;
                // lnglat[1] = path[i].lat;
                arr.push([path[i].lng,path[i].lat]);
            }
            console.log(arr);
        }
    });

    $('#closeMouse').click(function() {
        arr = [];
        mousetool.close(true);
    });
    // *********************************************************************************
    /**
     * [检查是否在园内]
     * @param  {AMap}   ) { var lat,lng,radius;        var myLngLat [description]
     * @return {[type]}   [description]
     */
    $('#testCircle').click(function() {
        var lat,lng,radius;
        var myLngLat = new AMap.LngLat(106.572441, 29.642759);
        var circle = new AMap.Circle({
            center: new AMap.LngLat(lng, lat), // 圆心位置
            radius: radius //半径
        });
        if (circle.contains(myLngLat)) {
            alert('在');
        } else {
            alert('不在');
        }
    });

    /**
     * [检查是否在多边形内]
     * @param  {AMap}   ) { var myLngLat [description]
     * @return {[type]}   [description]
     */
    $('#testPolygon').click(function() {
        var myLngLat = new AMap.LngLat(106.572441, 29.642759);
        // 定义一个多边形
        var polygon = new AMap.Polygon({
            path: arr
        });
        alert(arr);
        if (polygon.contains(myLngLat)) {
            alert('在');
        } else {
            alert('不在');
        }
    });
    /**
     * [保存]
     * @param  {AMap}   ) {                           var polygon [description]
     * @return {[type]}   [description]
     */
    $('#save').click(function() {
        var shop_id = $('#shop_id').val();
        // 定义一个多边形
        // var polygon = new AMap.Polygon({
        //     path: arr
        // });
        console.log(arr);
        if (arr == null) {
            layer.msg('请先画出范围', {icon: 1,time:2000});
            return false;
        }
        $.post('<?php echo url('shop/setDeliveryArea'); ?>',{location_list:arr,shop_id:shop_id},function(res){
            if (res.code == 200) {
                layer.msg(res.msg, {icon: 1,time:1500},function(){
                   // layer.close(layer.index);
                   var index = parent.layer.getFrameIndex(window.name);
                    parent.layer.close(index);
                });
            }else {
                layer.msg(res.msg, {icon: 0,time:1500});
            }
        })
    });
</script>
</html>
