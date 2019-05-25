$(document).ready(function(){
    var shop_id = $('.select').val();
    $.ajax({
        url:"index_count",
        type:"post",
        dataType: 'text',
        data: {shop_id: shop_id},
        success:function(data){
            var res = $.parseJSON(data);
            console.log(res);
            $('.amount').html(res.amount);
            $('.nums').html(res.nums);
            $('.cardprice').html(res.cardprice);
            $('.money').html(res.money);
            $('.cardmoney').html(res.cardmoney);
            $('.itemmoney').html(res.itemmoney);
            $('.fuwumoney').html(res.fuwumoney);
            var cardinfo = res.cardinfo;
            var cardnums = res.cardnums;
            var html = '';
            $.each(res.goods_type,function(k,v){
                html += "<div><span>"+v.title+"</span><p><span style='background-color: red;width:"+v.bili+"%;'></span></p><span style='color: black;'>￥"+v.amount+"</span></div>";
            });
            $('#goods_type').html(html);
            var fuwu = '';
            $.each(res.fuwu_type,function(k,v){
                fuwu += "<div><span>"+v.title+"</span><p><span style='background-color: red;width:"+v.bili+"%;'></span></p><span style='color: black;'>￥"+v.amount+"</span></div>";
            });
            $('#fuwu_type').html(fuwu);
            console.log(cardinfo);
            console.log(cardnums);
            /***
         * 支付方式图表
         */
        var payWay = echarts.init(document.getElementById('pay_way'));
        var payType = echarts.init(document.getElementById('pay_type'));
        var saleRmb = echarts.init(document.getElementById('sale_rmb'));
        var saleNum = echarts.init(document.getElementById('sale_num'));

        /***
         * 消费方式 图表
         * @type {[string,string]}
         */
        var color_payway=['#fba950','#4dcaf5'];
        var i = 0;
        var payWayOption = {
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b}: {c} ({d}%)"
            },
            series: [
                {
                    name:'支付方式',
                    type:'pie',
                    radius: ['45%', '70%'],
                    avoidLabelOverlap: false,
                    itemStyle : {
                        color: function () {
                            return color_payway[i++]
                        }
                    },
                    label: {
                        normal: {
                            show: false,
                            position: 'center'
                        },
                        emphasis: {
                            show: true,
                            textStyle: {
                                fontSize: '14',
                                fontWeight: 'bold'
                            }
                        }
                    },
                    labelLine: {
                        normal: {
                            show: false
                        }
                    },
                    data:[
                        {value:res.money, name:'微信'},
                        {value:res.cardmoney, name:'卡消费'}
                    ]
                }
            ]
        };

        /***
         * 消费类型 图表
         * @type {[string,string]}
         */
        var color_paytype=['#737ee9','#ed6088'];
        var j = 0;
        var payTypeOption = {
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b}: {c} ({d}%)"
            },
            series: [
                {
                    name:'消费方式',
                    type:'pie',
                    radius: ['45%', '70%'],
                    avoidLabelOverlap: false,
                    itemStyle : {
                        color: function () {
                            return color_paytype[j++]
                        }
                    },
                    label: {
                        normal: {
                            show: false,
                            position: 'center'
                        },
                        emphasis: {
                            show: true,
                            textStyle: {
                                fontSize: '14',
                                fontWeight: 'bold'
                            }
                        }
                    },
                    labelLine: {
                        normal: {
                            show: false
                        }
                    },
                    data:[
                        {value:res.itemmoney, name:'购物'},
                        {value:res.fuwumoney, name:'服务'}
                    ]
                }
            ]
        };


        /***
         * 销售金额 图表
         * @type {[string,string,string,string]}
         */
        var color_salemb=['#8064a2','#9bbb59', '#c0504d', '#4f81bd'];
        var h = 0;
        var saleRmbOption = {
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            series : [
                {
                    name: '消费金额',
                    type: 'pie',
                    radius : '55%',
                    center: ['50%', '60%'],
                    data: cardinfo,
                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        },
                        color: function () {
                            return color_salemb[h++]
                        }
                    }
                }
            ]
        };


        /***
         * 销售卡片张数 图表
         * @type {number}
         */
        var k = 0;
        var saleNumOption = {
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            series : [
                {
                    name: '消费张数',
                    type: 'pie',
                    radius : '55%',
                    center: ['50%', '60%'],
                    data:cardnums,
                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        },
                        color: function () {
                            return color_salemb[k++]
                        }
                    }
                }
            ]
        };
        payWay.setOption(payWayOption);
        payType.setOption(payTypeOption);
        saleRmb.setOption(saleRmbOption);
        saleNum.setOption(saleNumOption);
        }
    })
});
$('.time').click(function(){
    var shop_id = $('.select').val();
    var from = $('#from').val();
    var to   = $('#to').val();
    console.log(shop_id);
    console.log(from);
    console.log(to);
    $.ajax({
        url:"timeselect",
        type:"post",
        dataType: 'text',
        data: {shop_id: shop_id,from:from,to:to},
        success:function(data){
            var res = $.parseJSON(data);
            console.log(res);
            $('.amount').html(res.amount);
            $('.nums').html(res.nums);
            $('.cardprice').html(res.cardprice);
            $('.money').html(res.money);
            $('.cardmoney').html(res.cardmoney);
            $('.itemmoney').html(res.itemmoney);
            $('.fuwumoney').html(res.fuwumoney);
            var cardinfo = res.cardinfo;
            var cardnums = res.cardnums;
            var html = '';
            $.each(res.goods_type,function(k,v){
                html += "<div><span>"+v.title+"</span><p><span style='background-color: red;width:"+v.bili+"%;'></span></p><span style='color: black;'>￥"+v.amount+"</span></div>";
            });
            $('#goods_type').html(html);
            var fuwu = '';
            $.each(res.fuwu_type,function(k,v){
                fuwu += "<div><span>"+v.title+"</span><p><span style='background-color: red;width:"+v.bili+"%;'></span></p><span style='color: black;'>￥"+v.amount+"</span></div>";
            });
            $('#fuwu_type').html(fuwu);
            console.log(html);
            //console.log(cardinfo);
            //console.log(cardnums);
            /***
         * 支付方式图表
         */
        var payWay = echarts.init(document.getElementById('pay_way'));
        var payType = echarts.init(document.getElementById('pay_type'));
        var saleRmb = echarts.init(document.getElementById('sale_rmb'));
        var saleNum = echarts.init(document.getElementById('sale_num'));

        /***
         * 消费方式 图表
         * @type {[string,string]}
         */
        var color_payway=['#fba950','#4dcaf5'];
        var i = 0;
        var payWayOption = {
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b}: {c} ({d}%)"
            },
            series: [
                {
                    name:'支付方式',
                    type:'pie',
                    radius: ['45%', '70%'],
                    avoidLabelOverlap: false,
                    itemStyle : {
                        color: function () {
                            return color_payway[i++]
                        }
                    },
                    label: {
                        normal: {
                            show: false,
                            position: 'center'
                        },
                        emphasis: {
                            show: true,
                            textStyle: {
                                fontSize: '14',
                                fontWeight: 'bold'
                            }
                        }
                    },
                    labelLine: {
                        normal: {
                            show: false
                        }
                    },
                    data:[
                        {value:res.money, name:'微信'},
                        {value:res.cardmoney, name:'卡消费'}
                    ]
                }
            ]
        };

        /***
         * 消费类型 图表
         * @type {[string,string]}
         */
        var color_paytype=['#737ee9','#ed6088'];
        var j = 0;
        var payTypeOption = {
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b}: {c} ({d}%)"
            },
            series: [
                {
                    name:'消费方式',
                    type:'pie',
                    radius: ['45%', '70%'],
                    avoidLabelOverlap: false,
                    itemStyle : {
                        color: function () {
                            return color_paytype[j++]
                        }
                    },
                    label: {
                        normal: {
                            show: false,
                            position: 'center'
                        },
                        emphasis: {
                            show: true,
                            textStyle: {
                                fontSize: '14',
                                fontWeight: 'bold'
                            }
                        }
                    },
                    labelLine: {
                        normal: {
                            show: false
                        }
                    },
                    data:[
                        {value:res.itemmoney, name:'购物'},
                        {value:res.fuwumoney, name:'服务'}
                    ]
                }
            ]
        };


        /***
         * 销售金额 图表
         * @type {[string,string,string,string]}
         */
        var color_salemb=['#8064a2','#9bbb59', '#c0504d', '#4f81bd'];
        var h = 0;
        var saleRmbOption = {
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            series : [
                {
                    name: '消费金额',
                    type: 'pie',
                    radius : '55%',
                    center: ['50%', '60%'],
                    data: cardinfo,
                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        },
                        color: function () {
                            return color_salemb[h++]
                        }
                    }
                }
            ]
        };


        /***
         * 销售卡片张数 图表
         * @type {number}
         */
        var k = 0;
        var saleNumOption = {
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            series : [
                {
                    name: '消费张数',
                    type: 'pie',
                    radius : '55%',
                    center: ['50%', '60%'],
                    data:cardnums,
                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        },
                        color: function () {
                            return color_salemb[k++]
                        }
                    }
                }
            ]
        };
        payWay.setOption(payWayOption);
        payType.setOption(payTypeOption);
        saleRmb.setOption(saleRmbOption);
        saleNum.setOption(saleNumOption);
        }
    })
});
/***
 * 日期插件
 */
$(function() {
    $( "#from" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 1,
        showOn: "button",
        buttonImage: "../../calendar.png",
        buttonImageOnly: true,
        onClose: function( selectedDate ) {
            $( "#to" ).datepicker( "option", "minDate", selectedDate );
        }
    });
    $( "#to" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 1,
        showOn: "button",
        buttonImage: "../../calendar.png",
        buttonImageOnly: true,
        onClose: function( selectedDate ) {
            $( "#from" ).datepicker( "option", "maxDate", selectedDate );
        }
    });
});