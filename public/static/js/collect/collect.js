/**
 * 捣蛋熊猫后台管理收银
 * @param  {[type]} ){	} [description]
 * @return {[type]}        [description]
 */
var order_sn = '';         // 订单编号
var shop_id = '';          // 当前订单所属店铺id
var worker_list = [];      // 当前店铺服务人员列表
var shop_list = [];        // 店铺列表
var workId   = '';         // 当前操作人员id
var orderList = {          // 订单列表
	list: []
};
var searchList = {         // 搜索结果列表
	list: []
};
var orderSum = 0;           // 订单合计价格
var orderTotalNum = 0;      // 订单合计商品数量
var orderDiscountSum = 0;   // 订单合计优惠价格
var productSum = 0;         // 订单中商品总额
var productDistcountSum = 0 // 订单中商品经过优惠的总额
var memberInfo = {};        // 会员信息
var ticketList = {          // 会员商品代金券列表
	list: []
};
var bookGoodsList = {       // 会员预定的商品列表
	list: []
}
var bookGoodsListDetail = {       // 会员预定的商品列表
	list: []
}
var ww, sw;
var myinterval;            // 支付轮训定时器
var intervaltime = 30;     // 支付轮训时间
// 接口地址
//var interface = 'http://192.168.25.207:89/wechat'   // 测试
// var interface = 'http://localhost:89/wechat'   // 测试
var interface = 'http://192.168.25.128:82/wechat'   // 测试
// var interface = 'http://dd.ddxm661.com/wechat'             // 线上正式
var chooseTicketSum = 0;   // 当前选择的商品代金券总额
var sum = 0, psum = 0, change = 0, discount = 0;  // 结账页面一些信息

let laypage, form;
layui.use('laypage', function(){
	laypage = layui.laypage;
})
layui.use('form', function(){
  form = layui.form;
    // 商品代金券选择
  form.on('checkbox(choose_ticket)', function(obj){
  	let v = ticketList.list[$(obj.elem).parent().data('index')]
  	// v.choose = obj.elem.checked;
  	if (obj.elem.checked) {
  		chooseTicketSum = v.money - 0 + chooseTicketSum
  	} else {
		chooseTicketSum = chooseTicketSum - v.money
  	}
  	$('.tickets-sum').html(chooseTicketSum);
  })
});

// var plusflag = 1;   // 下拉加载标志

// 公共ajax请求方法
var $ajax =  function (url, data, sucF, errF, type, dataType) {
	var type = type || 'post';
	var dataType = dataType || 'json';
	var durl = url === '/admin/collect/getAllData'? url : (interface + url)
	var index;
	$.ajax({
		type: type,
		url:  durl,
		data: data,
		dataType: dataType,
		beforeSend: function () {
			index = layer.load(1);
		},
		success: function (res) {
			if (res.code === 200) {
				sucF && sucF(res.data);
			} else {
				errF && errF(res);
			}
		},
		error: function (err) {
			errF(err);
		},
		complete: function () {
			layer.close(index);
		}
	})
}

// 换算时间成 2018-04-07形式
var changeTime = function (timstamp) {
  let time = new Date(timstamp)
  let y = time.getFullYear()
  let m = time.getMonth() - 0 + 1
  let d = time.getDate()
  m = (m + '').length === 1 ? '0' + m : m
  d = (d + '').length === 1 ? '0' + d : d
  return y + '-' + m + '-' + d
}

// 打开结账页面 计算订单信息
// @param type 支付方式
var calOrderSum = function (type) {
	if (type === 3) { // 会员卡
		$('.sum-sum').html(sum.toFixed(2));
		$('.sum-psum').html((psum - 0).toFixed(2));
		if (change > 0) {
			$('.sum-change').html('-&nbsp;' + change.toFixed(2));
		} else {
			$('.sum-change').html('+&nbsp;' + -change.toFixed(2));
		}
		$('.sum-discount').html('-&nbsp;' + discount.toFixed(2));
	} else {
		$('.sum-sum').html(sum.toFixed(2));
		$('.sum-discount').html('-&nbsp;0.00');
		if (change) {
			if (change > 0) {
				$('.sum-change').html('-&nbsp;' + change.toFixed(2));
			} else {
				$('.sum-change').html('+&nbsp;' + -change.toFixed(2));
			}
		} else {
			$('.sum-change').html('-&nbsp;0.00');
		}
		$('.sum-psum').html((sum - chooseTicketSum - change).toFixed(2));
	}
	$('.sum-ticket').html('-&nbsp;' + chooseTicketSum.toFixed(2));
}

/***
 * 统一支付请求
 * @param authCode 扫码枪获取到的支付二维码
 * @param type     支付方式
 */
var orderPay = function (authCode, type) {
	var data = {
		order_sn: order_sn,
		type: type,
		work_id: $($("#worker_list").find("option:selected")[0]).data('id')
	}
	if (type == 3 && $('.role-card').data('id')) {
		data.card_id = $('.role-card').data('id');
	}
	if (authCode) {
		data.auth_code = authCode
	}
	// 支付需处理状态 不能用公共方法
	$.ajax({
		type: 'POST',
		url:  interface + '/api/payCenter',
		data: data,
		dataType: 'json',
		beforeSend: function () {
			index = layer.load(1);
		},
		success: function (res) {
			console.log(res);
			if (res.code === 200) {
				layer.close(index);
				layer.open({
				  content: res.msg,
				  yes: function(index, layero){
				    layer.close(index);
					document.location.reload();
				  }
				});
			} else if (res.code === 201) {    // 当前用户需输入密码支付 要轮训来接收支付结果
				myinterval = setInterval(function () {
					intervaltime = intervaltime - 5;
					checkCheckResult(type, index, res.data.order_sn);
				}, 5000)
			} else if (res.code === 302) {  // 库存发生变化
				layer.close(index);
				layer.open({
				  content: res.msg,
				  yes: function(i, layero){
				    $('#checkout').modal('hide');  // 关闭结账框
				    for (let i=0; i<orderList.list.length; i++) {
				    	let old = orderList.list[i];
				    	for (let j=0, l=res.data.length; j<l; j++) {
							let neww = res.data[j];
							if (old.id === neww.id) {
								old.stock = neww.stock
								if (!old.stock) {
									orderList.list.splice(i, 1);
									i--;
									break;
								} else if (old.stock < old.num) {   // 当当前购买的数量大于库存数时
									old.num = old.stock
								}
							}
				    	}
				    }
				    _fnShowOrder();
				    calcProductData();
				    // 重新刷新显示区的商品
				    if ($('.change-type').find('span.active').data('type') == '3') {
						$('.change-type').find('span.active').removeClass('active')
						$('.change-type').find('span[data-type=0]').addClass('active')
					}
				    getProductInfo(2, '', 1)
				    layer.close(i);
				  }
				});
			} else {
				layer.close(index);
				layer.open({
				  content: res.msg,
				  yes: function(index, layero){
				    layer.close(index);
				    $('.zftm').val('');
					$('.zftm').focus();
				  }
				});
			}
		},
		error: function (err) {
			layer.close(index);
			layer.open({
			  content: err.msg || '请求错误，请重新点击结账',
			  yes: function(index, layero){
			    layer.close(index);
			    $('.zftm').val('');
				$('.zftm').focus();
			  }
			});
		}
	})
}

// 轮巡支付结果 3秒检查一次
var checkCheckResult = function (type, index, returnOrdersn) {
	var url, data;
	if (type == 2) {
		url = '/Api/queryAliOrder'
	} else {
		url = '/Api/queryWxOrder'
	}
	data = {
		sn: order_sn,
		order_sn: returnOrdersn
	}
	$.ajax({
		type: 'POST',
		url:  interface + url,
		data: data,
		dataType: 'json',
		beforeSend: function () {
		},
		success: function (res) {
			if (res.code === 200) {          // 支付成功
				layer.close(index);
				if (myinterval) {
					myinterval = null;
					clearInterval(myinterval);
				}
				layer.open({
				  content: res.msg,
				  yes: function(index, layero){
				    layer.close(index);
					document.location.reload();
				  }
				});
			} else if (res.code === 300) {   // 用户已取消支付
				layer.close(index);
				intervaltime = 30;
				clearInterval(myinterval);
				myinterval = null;
				layer.open({
				  content: res.msg,
				  yes: function(index, layero){
				    layer.close(index);
				    $('.zftm').val('');
					$('.zftm').focus();
				  }
				});
			} else {                         // 用户未付款
				if (intervaltime <= 0) {
					intervaltime = 30;
					clearInterval(myinterval);
					myinterval = null;
					// 关闭订单
					var dd;
					if (type == 2) {
						dd = '/Api/closeAliOrder'
					} else {
						dd = '/Api/closeWxOrder'
					}
					$.ajax({                 // 轮训30秒后未支付关闭订单
						type: 'POST',
						url:  interface + dd,
						data: {order_sn: returnOrdersn},
						dataType: 'json',
						beforeSend: function () {
						},
						success: function (res) {
							layer.close(index);
							layer.open({
							  content: '等待支付时间太久~, 请重新点击结账',
							  yes: function(index, layero){
							    layer.close(index);
							    $('.zftm').val('');
								$('.zftm').focus();
							  }
							});
						},
						error: function (err) {
							layer.close(index);
							layer.open({
							  content: '等待支付时间太长~已关闭订单，请重新下单',
							  yes: function(index, layero){
							    layer.close(index);
							    document.location.reload();
							  }
							});
						}
					})
				}
			}
		},
		error: function (err) {
			intervaltime = 30;
			clearInterval(myinterval);
			myinterval = null;
			// 关闭订单
			var dd;
			if (type == 2) {
				dd = '/Api/closeAliOrder'
			} else {
				dd = '/Api/closeWxOrder'
			}
			$.ajax({                 // 轮训30秒后未支付关闭订单
				type: 'POST',
				url:  interface + dd,
				data: {order_sn: returnOrdersn},
				dataType: 'json',
				beforeSend: function () {
				},
				success: function (res) {
					layer.close(index);
					layer.open({
					  content: '出现未知错误~已关闭订单, 请重新点击结账',
					  yes: function(index, layero){
					    layer.close(index);
					    $('.zftm').val('');
						$('.zftm').focus();
					  }
					});
				},
				error: function (err) {
					layer.close(index);
					layer.open({
					  content: '出现未知错误~已关闭订单，请重新下单',
					  yes: function(index, layero){
					    layer.close(index);
					    document.location.reload();
					  }
					});
				}
			})
		}
	})
}

// 当用户点击了会员确认后重刷order列表
var refrushOrderList = function (flag, calc) {
	if (flag) {
		var data = {
			goods_ids: [],
			shop_id: shop_id,
		}
		for (var i = 0, len = orderList.list.length; i < len; i++) {
			data.goods_ids[i] = {
				id: orderList.list[i].id,
				class: orderList.list[i].class
			}
		}
		if ($('.member-level').data('memberid')) {
			data.member_id = $('.member-level').data('memberid');
		}
		console.log(data);
		$ajax('/api/getAllGoodsInfo', data,
			function (rel) {
				console.log(rel);
				var flag = false;
				for (var i = 0, len = rel.length; i < len; i++) {
					if (rel[i].discount > 0) {
						orderList.list[i].discount = rel[i].discount;
						flag = true;
					}
				}
				_fnShowOrder();
				if (calc) {
					calcProductData(orderList.list, 1);
				}
			},
			function (err) {
				layer.msg(err.msg, {offset: '100px'});
			}, 'POST'
		)
	} else {
		var flag1 = 0;
		for (var i=0, len = orderList.list.length; i< len; i++) {
			if (orderList.list[i].discount > 0) {
				orderList.list[i].discount = 0;
				flag1 = 1;
			}
		}
		if (calc) {
			calcProductData(orderList.list, 1);
		}
		_fnShowOrder();
	}
}

// 修改和清除改价弹出框内容
var setOrClearChangeP = function (flag, index) {
	if (flag) {
		if (orderList.list[index].class === 1) {  // 商品
			$('.product-name').html(orderList.list[index].title);
		} else {
			$('.product-name').html(orderList.list[index].sname);
		}

		$('.product-sn').html(orderList.list[index].bar_code || '无条形码');
		$('.product-price').html(orderList.list[index].price);
		if (orderList.list[index].changeprice > -1) {
			$('.input-discount').val(orderList.list[index].changeprice);
			var d = orderList.list[index].changeprice - orderList.list[index].price;
			if (d > 0) {
				$('.discount').html('+&nbsp;￥' + d.toFixed(2));
			} else {
				$('.discount').html('-&nbsp;￥' + -d.toFixed(2));
			}
			$('.change-reason').val(orderList.list[index].reason);
		} else {
			$('.input-discount').val('');
			var d = 0;
			$('.discount').html('-&nbsp;￥' + d.toFixed(2));
			$('.change-reason').val('');
		}
	}
}

// 获取会员信息
var getMemberInfo = function (text) {
	$ajax('/api/getMemberInfo', {num_id: text, shop_id: shop_id},
		function (rel) {
			console.log(rel);
			memberInfo = rel;
			setOrClearMemberInfo(true);
			if (!rel.current_shop) {
				layer.msg('当前会员不是本店的会员~', { offset: '100px' })
			}
		},
		function (err) {
			layer.msg(err.msg, {offset: '100px'});
		}, 'POST'
	)
}

// 通过获取到的会员信息添加会员信息或者移除信息
var setOrClearMemberInfo = function (flag) {
	if (flag) {      // set
		$('.role-phone').html(memberInfo.mobile);
		$('.role-shop').html(memberInfo.shop_name);
		$('.role-nick').html(memberInfo.nickname);
		$('.role-money').html(memberInfo.money);
		$('.level-name').html(memberInfo.level_name);
		$('.coupons-num').html(memberInfo.coupons_num);
		$('.bamboo-points').html(memberInfo.score_server); // 服务积分
		$('.bambooshoot-points').html(memberInfo.score_item);  // 商品积分
	} else {         // clear
		$('.role-phone').html('');
		$('.role-shop').html('');
		$('.role-nick').html('');
		$('.role-money').html('');
		$('.level-name').html('');
		$('.coupons-num').html('');
		$('.bamboo-points').html('');
		$('.bambooshoot-points').html('');
		if ($('.member-search').val()) {
			$('.member-search').val('');
			$('.member-search').focus();
		}
	}
}

// 删除某个商品
var _fnDeleteProduct = function (index) {
	orderList.list.splice(index, 1);
	calcProductData();
	_fnShowOrder()
}

// 右边订单模版渲染
var _fnShowOrder = function (flag) {
	var order_list = template('contentProduct', orderList)
    $('#order_product').html(order_list)
}

/***
 * 左边搜索结果数量、数据列表模版渲染
 * @params p 重新搜索时 把page还原
 */
var _fnShowResult = function (p) {
	var search_list = template('showProductList', searchList)
	$('#list_items').html(search_list)
	if (p === 1) {
		page = 1;
	}
}

// 获取页面固定数据
var getPageMsg = function () {
	$ajax('/admin/collect/getAllData', '',
		function (rel) {
			shop_id     = rel.shop_id;
			order_sn    = rel.order_sn;
			worker_list = rel.work_list;
			shop_list   = rel.shop_list;
			workId      = rel.worker_id
			// 左边初始显示服务项目
    		getProductInfo(2, '', 1)
    		// 获取完初始数据 打开搜索会员模态框
    		// $('#member').modal('show')
		},
		function (err) {
			layer.msg('请求错误，请重试！', {offset: '100px'});
		}, 'get'
	)
}

// 处理显示区tab标签的状态
var calcSearchPage = function (page) {
	if (page === 1) {
		if ($('.tab-prev').hasClass('active')) {
			$('.tab-prev').removeClass('active')
		}
	}
	if (page > 1) {
		if (!$('.tab-prev').hasClass('active')) {
			$('.tab-prev').addClass('active')
		}
	}
	if (searchList.list.length < 16) {
		if ($('.tab-next').hasClass('active')) {
			$('.tab-next').removeClass('active')
		}
	} else {
		$('.tab-next').addClass('active')
	}
}

// 处理页面中(tab/nav)的显示与隐藏
var _fnShowOrNot = function (classify) {
	// 分类搜索导航
	if (classify === 1) {   // true or false
		if ($('.nav-box').hasClass('hidden')) {
			$('.nav-box').removeClass('hidden')
		}
	} else if (classify === 0) {
		if (!$('.nav-box').hasClass('hidden')) {
			$('.nav-box').addClass('hidden')
		}
	}
}

/***
 * 搜索商品或服务
 * @params shopid      店铺id
 * @params page        分页
 * @params cateid      分类id   [可选] 商品(1) 服务(2)
 * @params sign        条码/助记码/金额/搜索文字 [可选]
 */
var getProductInfo = function (cateid, sign, page) {
	var data = {};
	if (cateid) {
		data = {
			shop_id: shop_id,
			type: cateid,
		    page:    page
		}
	} else {
		data = {
			shop_id: shop_id,
			sign:    $(sign).val(),
		    page:    page
		}
	}
    $ajax('/Api/getGoodsOrServerList', data,
		function (rel) {
			console.log(rel);   // 店铺D 订单ID 服务人员ID
			if (sign) {        // 条形码 1个则直接加入订单 多个就在显示区显示
				if (rel.goods_list.length === 1) {
					// 当前扫码出来的是服务
					if (!$('.register').hasClass('hidden')) {  // 不能预定服务
			    		if (searchList.list[$(this).data('index') - 0].class === 2) {
			    			layer.msg('服务不能进行预定~', { offset: '100px'} )
			    			$(sign).val('');
			    			$(sign).focus();
			    			return;
			    		}
			    	}
					calcProductData(rel.goods_list[0]);
					searchList.list = [];
					_fnShowResult();
					$(sign).val('');
					_fnShowOrder();
					if (!$('#pagination').hasClass('hidden')) {
						$('#pagination').addClass('hidden');
					}
				} else {
					searchList.list = rel.goods_list;
					_fnShowResult();
				}
			} else {
				searchList.list = rel.goods_list;
				_fnShowResult();
			}
			calcSearchPage(page);
			$(sign).focus();
			if (page === 1 && rel.goods_list.length > 1) {
				if ($('#pagination').hasClass('hidden')) {
					$('#pagination').removeClass('hidden')
				}
				laypage.render({
				    elem: 'pagination' //注意，这里的 test1 是 ID，不用加 # 号
				    ,count: rel.totalNum //数据总数，从服务端得到
				    ,limit: rel.pageSize
				    ,jump: function (obj, first) {
				    	if (!first) {
				    		getProductInfo(cateid, sign, obj.curr);
				    	}
				    }
				})
			}
	    },
		function (res) {
			if (res.code === 300) {  // 没有数据
				if (page === 1) {
					var html = '<p class="tip-search"><img src="/static/images/scanicon.png" alt=""><span>什么都没有搜到，再搜一次吧~</span></p>'
					$('#list_items').html(html)
				}
				// } else {
				// 	$('.load').addClass('hidden');
				// }
				// plusflag = 0;
			} else {
				searchList.list = []
    			_fnShowResult()
			}
			if (!$('#pagination').hasClass('hidden')) {
				$('#pagination').addClass('hidden')
			}
		}, 'post'
	)
}

/***
 * 处理将要添加进订单列表的数据
 * 可能会存在添加相同商品的情况 处理数量 判断库存是否充足
 * 可能存在点击了会员的情况下
 */
var calcProductData = function (data, calc) {
	orderSum = 0;
	orderDiscountSum = 0;
	orderTotalNum = 0;
	productDistcountSum = 0;
	productSum = 0;
	if (data && !calc) {
		if (orderList.list.length === 0) {
			data.num = 1;
			data.changeprice = -1;
			orderList.list.push(data);
		} else {
			var flag = 0
			for (var i = 0, len = orderList.list.length; i < len; i++) {
				if (!orderList.list[i].num) {
					orderList.list[i].num = 1;
				}
				if (data.id === orderList.list[i].id && data.class === orderList.list[i].class) {
					// 预定订单不需要限制库存
					if (data.class === 1 && orderList.list[i].num >= orderList.list[i].stock && $('.register').hasClass('hidden')) {
						layer.msg(data.title + '库存为' + data.stock + ', 库存不足~', {offset: '100px'})
						flag = 2;
						continue;
					} else {
						orderList.list[i].num = orderList.list[i].num - 0 + 1;
						flag = 1;
					}
				}
			}
			if (flag === 0) {
				data.num = 1;
				data.changeprice = -1;
				orderList.list.push(data);
			}
		}
	}

	// 计算订单合计后的价格
	for ( var j = 0 , len = orderList.list.length; j < len; j++ ) {
		var item = orderList.list[j];
		if (item.changeprice > -1) {
			orderDiscountSum = orderDiscountSum + ((orderList.list[j].changeprice - 0) * orderList.list[j].num);
			orderSum = orderSum + ((orderList.list[j].changeprice - 0) * orderList.list[j].num);
			if (item.class === 1) {  // 是商品
				productSum = productSum + ((orderList.list[j].changeprice - 0) * orderList.list[j].num);
				productDistcountSum = productDistcountSum + ((orderList.list[j].changeprice - 0) * orderList.list[j].num);
			}
		} else if (item.discount > 0) {
			orderDiscountSum = orderDiscountSum + ((orderList.list[j].discount - 0) * orderList.list[j].num);
			orderSum = orderSum + ((orderList.list[j].price - 0) * orderList.list[j].num);
			if (item.class === 1) {  // 是商品
				productSum = productSum + ((orderList.list[j].price - 0) * orderList.list[j].num);
				productDistcountSum = productDistcountSum + ((orderList.list[j].discount - 0) * orderList.list[j].num);
			}
		} else {
			orderDiscountSum = orderDiscountSum + ((orderList.list[j].price - 0) * orderList.list[j].num);
			orderSum = orderSum + ((orderList.list[j].price - 0) * orderList.list[j].num);
			if (item.class === 1) {  // 是商品
				productSum = productSum + ((orderList.list[j].price - 0) * orderList.list[j].num);
				productDistcountSum = productDistcountSum + ((orderList.list[j].price - 0) * orderList.list[j].num);
			}
		}
		orderTotalNum = orderList.list[j].num - 0 + orderTotalNum
	}
	calcOrderSum();
	if ($('.member-level').data('memberid') && !calc && orderList.list.length) {
		refrushOrderList(true, 1);
	}
}

// 处理合计价格
var calcOrderSum = function (flag) {  // flag  代表已经减去过代金券
	if (!orderList.list.length) { // 限制在没有选择商品的时候 不能使用代金券
		chooseTicketSum = 0;
		for (let i=0, len=ticketList.list.length; i<len; i++) {
			ticketList.list[i].choose = false
		}
	}
	if ($('.member-level').data('memberid')) {
		if (ticketList.list.length) {
			if (chooseTicketSum) {
				if (!$('.product .normal').hasClass('hidden')) {
					$('.product .normal').addClass('hidden')
				}
				if ($('.product .special').hasClass('hidden')) {
					$('.product .special').removeClass('hidden')
				}
				if (chooseTicketSum > productDistcountSum) {
					orderDiscountSum = orderDiscountSum - productDistcountSum;
					if ($('.product .special img').hasClass('hidden')) {
						$('.product .special img').removeClass('hidden');
					}
					if ($('.product .special').hasClass('purple')) {
						$('.product .special').removeClass('purple').addClass('red')
					}

				} else {
					orderDiscountSum = orderDiscountSum - chooseTicketSum
					if (!$('.product .special img').hasClass('hidden')) {
						$('.product .special img').addClass('hidden');
					}
					if ($('.product .special').hasClass('red')) {
						$('.product .special').removeClass('red').addClass('purple')
					}
				}
				$('.ticket-sum').html(chooseTicketSum);
			} else {
				if ($('.product .normal').hasClass('hidden')) {
					$('.product .normal').removeClass('hidden')
				}
				if (!$('.product .special').hasClass('hidden')) {
					if (!$('.product .special img').hasClass('hidden')) {
						$('.product .special img').addClass('hidden');
					}
					$('.product .special').addClass('hidden')
				}
			}
		}
		if ($('.m-balance').data('balance') >= orderDiscountSum) {
			$('.uordersum').html(orderDiscountSum.toFixed(2));
		} else {
			if (ticketList.list.length) {
				if (chooseTicketSum) {
					if (chooseTicketSum > productSum) {
						orderSum = orderSum - productSum
					} else {
						orderSum = orderSum - chooseTicketSum
					}
				}
			}
			$('.uordersum').html(orderSum.toFixed(2));
			layer.msg('待结算金额已超过会员余额，将不享受优惠~');
		}
		if (!$('.coupon').hasClass('hidden')) {
			$('.coupon').addClass('hidden')
		}
		if ($('.product').hasClass('hidden')) {
			$('.product').removeClass('hidden');
		}

	} else {
		if (ticketList.list.length) {
			if (chooseTicketSum) {
				if (chooseTicketSum > productSum) {
					orderSum = orderSum - productSum
				} else {
					orderSum = orderSum - chooseTicketSum
				}
			}
		}
		$('.uordersum').html(orderSum.toFixed(2));
	}
	$('.sum-num').html(orderTotalNum);    // 赋值订单商品数量
}

// 获取会员取件列表
var getMemberReserveList = function (phone, flag) { // flag 表示是自己搜索的
	// 可能现在处于取件详情的界面 调整
	if (!$('.package-content .detail').hasClass('hidden')) {
		$('.package-content .list').removeClass('hidden');
		$('.package-content .detail').addClass('hidden');
	}
	let data = {
		shop_id: shop_id,
		member_id: phone
	}
	let btn = $('.' + $('.bookgoods-list').data('type') + '-btn')
	$ajax('/api/getMemberBookGoods', data,
		function (rel) {
			console.log(rel);
			if (rel.dq_list.length) {
				bookGoodsList.list = rel.dq_list;
				let data = template('booksGoodsList', bookGoodsList);
				$('.bookgoods-list').html(data);
				if ($(btn).hasClass('hidden')) {
					$(btn).removeClass('hidden')
				}
				if (!$(btn).siblings().hasClass('hidden')) {
					$(btn).siblings().addClass('hidden')
				}
			} else {
				layer.msg('当前会员没有预定商品~', { offset: '100px' })
			}
			if (!flag) {
				$('.search-bookgoods').val($('.member-level').data('phone'));
			}
			$('#takePart').modal('show')
		},
		function (err) {
			bookGoodsList.list = [];
			let data = template('booksGoodsList', bookGoodsList);
			$('.bookgoods-list').html(data);
			if (!$(btn).hasClass('hidden')) {
				$(btn).addClass('hidden')
			}
			if (!$(btn).siblings().hasClass('hidden')) {
				$(btn).siblings().addClass('hidden')
			}
			if (!flag) {
				$('.search-bookgoods').val($('.member-level').data('phone'));
			}
			$('#takePart').modal('show')
			layer.msg(err.msg, {offset: '100px'});
		}, 'POST'
	)
}

// 处理点击外层结账模态框
var handleCeckOutModelInfo = function () {
	sum = 0, psum = 0, change = 0, discount = 0;
	// 与后台通讯创建订单
	var data = {
		goods_ids: [],
		shop_id: shop_id,
		order_sn: order_sn,
		order_type: $(".flag-order > span:not(.hidden)").data('type'),
		ticket_ids: []
	};
	console.log(data);

	let flag = false;  // 判断当前商品列表中存在商品
	for (var i = 0,len = orderList.list.length; i < len; i++) {
		var d = orderList.list[i];
		if (d.class === 1) {
			flag = true
		}
		if (d.changeprice > -1) {        // 存在改价
			data.goods_ids[i] = {
				id: d.id,
				class: d.class,
				num: d.num,
				eprice: d.changeprice,
				eprice_remark: d.reason
			}
		} else {
			data.goods_ids[i] = {
				id: d.id,
				class: d.class,
				num: d.num
			}
		}
	}
	if (chooseTicketSum && flag) {
		for (let i=0, len=ticketList.list.length; i<len; i++) {
			if (ticketList.list[i].choose) {
				data.ticket_ids.push({id: ticketList.list[i].id})
			}
		}
	}
	if ($('.member-level').data('phone')) {
		data.mobile = $('.member-level').data('phone');
	}

	$ajax('/api/addOrder', data,
		function (rel) {
			console.log(rel);
		},
		function (err) {
			layer.msg(err.msg, {offset: '100px'});
		}, 'POST'
	)
	// 计算结账模态框中订单信息
	// 添加店铺
	var shop = '';
	for (let i=0, len=shop_list.length; i<len; i++) {
		if (shop_id === shop_list[i].id) {
			shop = `<option value=${shop_list[i].id} selected>${shop_list[i].name}</option>` + shop;
		} else {
			shop = `<option value=${shop_list[i].id}>${shop_list[i].name}</option>` + shop;
		}
	}
	$('#shop_list').html(shop);
	var d = {
		list: worker_list
	}
	var data = template('workerList', d);
	$('#worker_list').html(data);
	// 合计
	for (var i = 0, len = orderList.list.length; i < len; i++) {
		var d = orderList.list[i];
		sum += d.num * d.price;
		if (d.changeprice > -1) {
			change += (d.price - d.changeprice) * d.num;
		} else {
			if (d.discount > 0) {
				discount += (d.price - d.discount) * d.num;
			}
		}
	}
	psum = $('.uordersum').html();
	// console.log('优惠。。')
	// console.log('实收：' + psum);
	// console.log('合计：' + sum);
	// console.log('优惠：' + discount);
	// console.log('改价：' + change);
	if ($('.member-level').data('memberid')) {   // 当前会员有会员卡
		$('.sum-member').html($('.member-level').html());
	} else if ($('.member-level').data('phone')) { // 当前会员无会员卡
		$('.sum-member').html($('.member-level').html().split('&nbsp;&nbsp;&nbsp;&nbsp;')[0] + '&nbsp;&nbsp;&nbsp;&nbsp;非本店会员');
	}
	// 清除调上一次使用结账弹出框的痕迹
	$('.card').find('.item-box.active').removeClass('active');
	if (!$('.zftm').hasClass('hidden')) {
		$('.zftm').addClass('hidden');
	}
	if (!$('.check-tip').hasClass('hidden')) {
		$('.check-tip').addClass('hidden');
	}
	// 服务人员可能上一次已经选过 恢复选过的值
	$('#worker_list').val($('.service-name').html()).trigger('change');
	// 根据用户选择动态改变订单信息
	if ($('.member-level').data('memberid') && psum <= ($('.m-balance').data('balance') - 0)) {
		calOrderSum(3);
		$('.item-box[data-id="3"]').addClass('active');
	} else {
		calOrderSum(0);
		$('.item-box[data-id="5"]').addClass('active');
		if ($('.check-tip').hasClass('hidden')) {
			$('.check-tip').removeClass('hidden')
		}
		if (!$('.zftm').hasClass('hidden')) {
			$('.zftm').addClass('hidden')
		}
	}
}

// 选择支付方式
$('.card .item-box').on('click', function () {
	if ($(this).hasClass('active')) {
		return;
	} else {
		if ($(this).data('id') == 3 && !$('.member-level').data('memberid')) {
			layer.msg('当前用户没有会员卡可以在会员搜索后查看~', {offset: '100px'});
			return;
		}
		if ($(this).data('id') == 3) {
			if (psum > ($('.m-balance').data('balance') - 0)) {
				layer.msg('订单金额大于会员卡卡余额~', {offset: '100px'});
				return;
			}
		}
		if ($(this).data('id') == 3) {
			if (!$('.zftm').hasClass('hidden')) {
				$('.zftm').addClass('hidden')
			}
			if (!$('.check-tip').hasClass('hidden')) {
				$('.check-tip').addClass('hidden')
			}
			calOrderSum(3);   // 默认会员卡
		} else {
			calOrderSum(0);   // 默认现金
		}
		$(this).parents().find('div.item-box.active .in').addClass('hidden')
		$(this).parents().find('div.item-box.active .in').prev().removeClass('hidden')
		$(this).parents().find('div.item-box.active').removeClass('active');
		$(this).addClass('active');
		$(this).find('.in').removeClass('hidden')
			$(this).find('.in').prev().addClass('hidden')
		if ($(this).data('id') == 4 || $(this).data('id') == 5 || $(this).data('id') == 6 || $(this).data('id') == 7 || $(this).data('id') == 8 || $(this).data('id') == 10) {
			if ($('.check-tip').hasClass('hidden')) {
				$('.check-tip').removeClass('hidden')
			}
			if (!$('.zftm').hasClass('hidden')) {
				$('.zftm').addClass('hidden')
			}
		} else if ($(this).data('id') == 1 || $(this).data('id') == 2) {
			if ($('.zftm').hasClass('hidden')) {
				$('.zftm').removeClass('hidden');
			}
			if (!$('.check-tip').hasClass('hidden')) {
				$('.check-tip').addClass('hidden')
			}
			$('.zftm').focus();
		}
	}
})

$(document).ready(function(){
	// $('.utime').html(changeTime(new Date()));  // 订单生成时间

	//切换搜索分类 页面一开始需显示一项 现显示服务项
	$('.change-type span').bind('click', function () {
		if ($(this).hasClass('active')) {
			return
		} else {
			if ($('.input-search').val()) {
				$('.input-search').val('');
			}
			$(this).parent().find('span.active').removeClass('active');
			$(this).addClass('active');
			if ($(this).data('type') == '0') {
				getProductInfo(0, '', 1)
			} else {
				if (!$('#pagination').hasClass('hidden')) {
					$('#pagination').addClass('hidden')
				}
				searchList.list = [];
				_fnShowResult(1);
			}
		}
	});
	$('.input-search').focus();
	// input-search
	    //  扫码枪自动ENTER
	    //  手动输入搜索词并ENTER
		$('.input-search').bind('keydown', function (e) {
			if (e.keyCode == '13') {
				if (!$(this).val()) {
					layer.msg('请输入搜索内容', {offset: '100px'})
					if (searchList.list.length) {
						searchList.list = [];
    					_fnShowResult();
					}
					return;
				}

				// 搜索的时候 切换tab到搜索结果显示区
				if ($('.change-type').find('span.active').data('type') == '0') {
					$('.change-type').find('span.active').removeClass('active')
					$('.change-type').find('span[data-type=3]').addClass('active')
				}
				getProductInfo('', this, 1);
			}
		})

		// 点击搜索按钮搜索
	    $('.product-search').on('click', function () {
	    	var search = $('.input-search');
	    	if (!$(search).val()) {
				layer.msg('请输入搜索内容', {offset: '100px'})
				if (searchList.list.length) {
					searchList.list = [];
					_fnShowResult();
				}
				return;
			}

			// 搜索的时候 切换tab到搜索结果显示区
			if ($('.change-type').find('span.active').data('type') == '0') {
				$('.change-type').find('span.active').removeClass('active')
				$('.change-type').find('span[data-type=3]').addClass('active')
			}
			getProductInfo('', search, 1);
	    })


    // 清除input搜索框内容
    $('.input-clear').bind('click', function () {
    	var sib = $(this).prev()
    	if (!sib.val()) {
    		sib.focus()
    		return
    	} else {
    		sib.val('')
    	}
    	searchList.list = []
    	_fnShowResult()
    	sib.focus()
    })

    // 左边搜索或者分类的展示区每个商品点击事件
    $(document).on('click', '#list_items > div', function () {
    	if (!$('.register').hasClass('hidden')) {  // 不能预定服务
    		if (searchList.list[$(this).data('index') - 0].class === 2) {
    			layer.msg('服务不能进行预定~', { offset: '100px'} )
    			return;
    		}
    	}
    	if ($('.register').hasClass('hidden') && searchList.list[$(this).data('index') - 0].class === 1 && !searchList.list[$(this).data('index') - 0].stock) {
    		layer.msg(searchList.list[$(this).data('index') - 0].title + '库存为' + searchList.list[$(this).data('index') - 0].stock + ', 库存不足~', {offset: '100px'})
			return false;
    	}
    	calcProductData(searchList.list[$(this).data('index') - 0]);
    	_fnShowOrder();
    	$('.input-search').val('');
    	$('.input-search').focus();
    })

    // 输入订单中商品的数量
    $(document).on('keyup', '#order_product .product-num', function () {
    	var money = 0;
    	$(this).val($(this).val().replace(/[^\d\.]/g,''));
    	if (!$(this).val() || $(this).val()[0] === '0') {
    		layer.msg('请输入大于0的数字~', {offset: '100px'});
    		$(this).val('');
    		$(this).focus();
    		return
    	}
    	var index = $(this).parent().parent().parent().data('index');
    	if (orderList.list[index].class === 1 && $(this).val() - 0 > orderList.list[index].stock && $(".flag-order > span:not(.hidden)").data('type') == 1) {
    		layer.msg( orderList.list[index].title + '库存为' +  orderList.list[index].stock + ', 库存不足~', {offset: '100px'})
    		$(this).val(orderList.list[index].num);
    		return false;
    	}
    	// orderTotalNum = orderTotalNum - orderList.list[index].num + ($(this).val() - 0);
    	orderList.list[index].num = $(this).val();
    	if (orderList.list[index].changeprice > -1) {
    		money = orderList.list[index].changeprice * orderList.list[index].num;
    	} else if (orderList.list[index].discount ) {
    		money = orderList.list[index].discount * orderList.list[index].num;
    	} else {
    		money = orderList.list[index].price * orderList.list[index].num;
    	}
    	$($('#order_product').children('div')[index]).find('span.single-sum').html('￥' + money.toFixed(2));
    	calcProductData('', 1);
    })

    // 输入订单中商品的数量 判断没输入任何值的时候需要
    $(document).on('blur', '#order_product .product-num', function () {
    	if (!$(this).val()) {
    		var money = 0;
    		$(this).val(1)
    		var index = $(this).parent().parent().parent().data('index');
    		orderList.list[index].num = 1;
    		if (orderList.list[index].changeprice > -1) {
	    		money = orderList.list[index].changeprice * orderList.list[index].num;
	    	} else if (orderList.list[index].discount ) {
	    		money = orderList.list[index].discount * orderList.list[index].num;
	    	} else {
	    		money = orderList.list[index].price * orderList.list[index].num;
	    	}
	    	$($('#order_product').children('div')[index]).find('span.single-sum').html('￥' + money.toFixed(2));
	    	calcProductData('', 1);
    	}
    })

    // 数量增加
    $(document).on('click', '#order_product .btn-add', function () {
    	var money = 0;
    	var index = $(this).parent().parent().parent().data('index');
    	if (orderList.list[index].class === 1 && orderList.list[index].num >= orderList.list[index].stock && $(".flag-order > span:not(.hidden)").data('type') == 1) {
    		layer.msg(orderList.list[index].title + '库存为' + orderList.list[index].stock + ', 库存不足~', {offset: '100px'})
			return false;
    	}
    	orderList.list[index].num = orderList.list[index].num - 0 + 1;
    	$(this).parent().find('input').val(orderList.list[index].num);
    	if (orderList.list[index].changeprice > -1) {
    		money = orderList.list[index].changeprice * orderList.list[index].num;
    	} else if (orderList.list[index].discount ) {
    		money = orderList.list[index].discount * orderList.list[index].num;
    	} else {
    		money = orderList.list[index].price * orderList.list[index].num;
    	}
    	$($('#order_product').children('div')[index]).find('span.single-sum').html('￥' + money.toFixed(2));
    	calcProductData();
    })

    // 数量减少
    $(document).on('click', '#order_product .btn-reduce', function () {
    	var money = 0;
    	var index = $(this).parent().parent().parent().data('index');
    	if (orderList.list[index].num == 1) {  // 当数量为1时点击删除 表示删除这个商品
			_fnDeleteProduct(index);
			return
    	}
    	orderList.list[index].num = orderList.list[index].num - 1;
    	$(this).parent().find('input').val(orderList.list[index].num);
    	if (orderList.list[index].changeprice > -1) {
    		money = orderList.list[index].changeprice * orderList.list[index].num;
    	} else if (orderList.list[index].discount ) {
    		money = orderList.list[index].discount * orderList.list[index].num;
    	} else {
    		money = orderList.list[index].price * orderList.list[index].num;
    	}
    	$($('#order_product').children('div')[index]).find('span.single-sum').html('￥' + money.toFixed(2));
    	calcProductData();
    })

    // 会员模态框弹出事件
	$('#member').on('shown.bs.modal', function () {
		setTimeout(function () {       // 弹出时加一个延时 设置页面数据
			if ($('.member-level').data('phone')) {
				$('.member-search').val($('.member-level').data('phone'));
				getMemberInfo($('.member-level').data('phone'));
			}
			$('.member-search').focus();
		}, 500)
	})

	// 会员模态框关闭事件 清除会员信息
	$('#member').on('hide.bs.modal', function () {
		// console.log('点击了会员模态框关闭按钮');
		memberInfo = {};
		setOrClearMemberInfo(false);
	})

    // 会员搜索
    $('.member-search').on('keydown', function (e) {   // 限制条件为用户手机号/会员编号
    	if (e.keyCode == 13) {
    		if (!$(this).val()) {
    			layer.msg('请输入用户手机号或者会员编号', {offset: '100px'});
    			return;
    		}
    		getMemberInfo($(this).val());
    	}
    })

    // 点击会员搜索按钮搜索
    $('.btn-member-search').on('click', function () {
    	var search = $('.member-search');
    	if (!search.val()) {
    		layer.msg('请输入用户手机号或者会员编号', {offset: '100px'});
    		return;
    	}
    	getMemberInfo(search.val());
    })

    // 会员重置
    $('.reset_member').on('click', function () {   // 在未确定会员选择的时候点击重置
    	let that = this;
    	// 当订单类型是预定订单时
    	if (!$('.register').hasClass('hidden') && $('.member-level').data('memberid')) {
    		layer.open({
			  content: "当前订单类型是预定订单，重置会员后需重新添加预定订单~",
			  yes: function(index, layero){
			  	$('.member-level').data('phone', '');
				$('.member-level').html('-');
				$('.member-level').data('memberid', '');
				if (!$('.member-balance').hasClass('hidden')) {
					$('.member-balance').addClass('hidden');
				}
				$('.m-balance').html('');
				$('.m-balance').data('banlance', '');  // 当前用户的余额
				if (ticketList.list.length) {  // 当前有商品代金券
					$('.tip.normal').addClass('hidden');
					$('.tip.special').addClass('hidden');
					// 代金券选择页面清空
					if (!$('.coupon').hasClass('hidden')) {
						$('.coupon').addClass('hidden');
						$('#coupon_list').html('');
					}
				}
				memberInfo = {};
				ticketList.list = [];   // 会员商品代金券清空
		    	setOrClearMemberInfo(false);
			    $('.register').addClass('hidden');
	  			$('.register').siblings().removeClass('hidden');
	  			if (orderList.list.length) {
	  				orderList.list = [];
		  			calcProductData();
		  			_fnShowOrder();
	  			}
	  			layer.close(index);
			  }
			});
    	} else {
    		$('.member-level').data('phone', '');
			$('.member-level').html('-');
			$('.member-level').data('memberid', '');
			if (!$('.member-balance').hasClass('hidden')) {
				$('.member-balance').addClass('hidden');
			}
			$('.m-balance').html('');
			$('.m-balance').data('banlance', '');  // 当前用户的余额
			if (ticketList.list.length) {  // 当前有商品代金券
				$('.tip.normal').addClass('hidden');
				$('.tip.special').addClass('hidden');
				// 代金券选择页面清空
				if (!$('.coupon').hasClass('hidden')) {
					$('.coupon').addClass('hidden');
					$('#coupon_list').html('');
				}
			}
			memberInfo = {};
			ticketList.list = [];   // 会员商品代金券清空
	    	setOrClearMemberInfo(false);
	    	if (orderList.list.length) {
  				orderList.list = [];
	  			calcProductData();
	  			_fnShowOrder();
  			}
    	}
    })

    // 重新搜索
    $('.remove_member').on('click', function () {
    	memberInfo = {};
    	// memberInfo.card_list = [];
    	setOrClearMemberInfo(false);
    })

    // 会员确定
    $('#sure_member').on('click', function () {
    	if (!memberInfo.mobile) {
    		layer.msg('请搜索会员信息~', {offset: '100px'});
    		return;
    	}
    	$('.member-level').data('phone', memberInfo.mobile);  // 保存用户的手机
		if (memberInfo.current_shop) {   // 为1的时候说明是本店的会员
			$('.member-level').data('memberid', memberInfo.id);  // 是本店的会员保存memberid
			$('.m-balance').data('balance', memberInfo.money);  // 保存当前用的会员卡余额
			$('.member-balance').removeClass('hidden');
			$('.m-balance').html(memberInfo.money);
			$('.member-level').html(memberInfo.nickname + '(  ' + memberInfo.level_name + ')');
			// 用户是本店会员 记录下商品代金券信息
			if (memberInfo.ticket_list.length) {
				for (let i=0, len=memberInfo.ticket_list.length; i<len; i++) {
					let v = memberInfo.ticket_list[i];
					// v.expire_time = changeTime(v.expire_time * 1000);
				}
				ticketList.list = memberInfo.ticket_list;
				$('.tip.normal').removeClass('hidden');
				$('.ticket-num').html(memberInfo.ticket_list.length);
			}
			// 向后台请求当前用户是否有优惠
			if (orderList.list.length) {
				refrushOrderList(true, 1);
			}
		} else {      //可能是取消了会员卡选择
			$('.member-level').html(memberInfo.nickname + '  非本店会员');
			if (!$('.member-balance').hasClass('hidden')) {
				$('.member-balance').addClass('hidden');
			}
			if (orderList.list.length) {
				refrushOrderList(false, 1);
			}
		}
		$('#member').modal('hide');
    })

    // 改价弹出框
    $('#changeprice').on('shown.bs.modal', function () {
		// TO DO
	})

	$('#changeprice').on('hide.bs.modal', function () {
		// console.log('点击了改价模态框关闭按钮');
	})
	// 点击改价
	$(document).on('click', '#order_product .item-width .change-price', function () {
		// if (!$('.change-price').hasClass('active')) {
		// 	return false;
		// }
		var index = $(this).parent().parent().data('index');
		$('.change-madel').data('index', index);
		setOrClearChangeP(true, index);
	})
	// 输入改价价格
	$('.input-discount').bind('keyup', function (e) {
		$(this).val($(this).val().replace(/[^\d\.]/g,''));
		if (!$(this).val()) {
			$('.discount').html('-￥0.00');
			layer.msg('请输入以数字开头和数字结尾的优惠价~');
			return;
		}
		var index = $('.change-madel').data("index");
		if ($(this).val() < 0) {
			layer.msg('当前输入优惠有误，请重新输入', {offset: '100px'});
			$('.discount').html('-￥0.00');
			$(this).val('');
			$(this).focus();
		} else {
			// 限制改价只能输入至小数点后两位
			if ($(this).val().split('.')[1] && $(this).val().split('.')[1].length > 2) {
				$(this).val($(this).val().split('.')[0] + '.' +$(this).val().split('.')[1][0] + $(this).val().split('.')[1][1])
				layer.msg('改价只能输入至小数点后两位~', { offset: '100px' })
			}
			var d = orderList.list[index].price - $(this).val();
			if (d > 0) {
				$('.discount').html('-￥' + d.toFixed(2));
			} else {
				$('.discount').html('+￥' + -d.toFixed(2));
			}
		}
	})
	$('.input-discount').bind('blur', function (e) {
		var index = $('.change-madel').data("index");
		if ($(this).val() - 0 < 0) {
			layer.msg('请输入大于等于0的优惠价格', {offset: '100px'});
			$('.discount').html('-￥0.00');
			$(this).val('');
		}
		if (($(this).val() - orderList.list[index].price) === 0) {
			$('.discount').html('-￥0.00');
			layer.msg('当前输入与原价一样，无效~');
			$(this).val('');
			$(this).focus();
			return;
		}
		if (($(this).val() + '')[($(this).val() + '').length - 1] == '.') {
			layer.msg('当前输入优惠有误，请重新输入', {offset: '100px'});
			$('.discount').html('-￥0.00');
			$(this).val('');
			$(this).focus();
		}
	})
	// 确认改价
	$('#sure_changeprice').bind('click', function () {
		if (!$('.input-discount').val()) {
			layer.msg('请输入优惠价格。', {offset: '100px'});
			$('.input-discount').focus();
			return;
		}
		if (!$('.change-reason').val()) {
			layer.msg('请输入改价原因。', {offset: '100px'});
			$('.change-reason').focus();
			return;
		}
		var index = $('.change-madel').data("index");
		orderList.list[index].changeprice = $('.input-discount').val();
		orderList.list[index].reason = $('.change-reason').val();
		$('#changeprice').modal('hide');
		calcProductData();
		_fnShowOrder();
	})

	// 结账弹出框
    $('#check-out').on('show.bs.modal', function () {
		// TO DO
	})

	$('#check-out').on('hide.bs.modal', function () {
		// console.log('点击了结账模态框关闭按钮');
	})

	// 选择服务人员
	$('#worker_list').on('change', function () {
		$('.service-name').html($(this).val());
	})

	// 选择店铺
	$('#shop_list').on('change', function () {
		// change woker_list
		$ajax('/Api/getShopWorkerList', { shop_id: $(this).val() }, function (rel) {
			console.log(rel);
			var d = {
				list: rel
			}
			var data = template('workerList', d);
			$('#worker_list').html(data);
		}, function (err) {
			layer.msg(err.msg, {offset: '100px'})
		}, 'POST')
	})

	// 点击结账按钮
	$('.check-out').on('click', function () {
		if (!orderList.list.length) {
			layer.msg('请先加入商品。', {offset: '100px'});
			return false;
		}
		if (chooseTicketSum && !$('.goods .tip img').hasClass('hidden')) {
			layer.open({
			  content: '选择消耗代金券金额已大于待结账商品金额，代金券仅抵扣商品金额且不找零！',
			  yes: function(index, layero){
			    layer.close(index);
			    $('#checkout').modal('show');
			    handleCeckOutModelInfo();
			  }
			});
		} else {
			$('#checkout').modal('show');
			handleCeckOutModelInfo();
		}

	})

	// 扫码枪扫码输入框
	// 点击结账
	$('#check_out').on('click', function () {
		var role = $($("#worker_list").find("option:selected")[0]).data('id');
		if (!role) {
			layer.msg('当前没有选择这笔订单的服务人员哦~', {offset: '100px'});
			return;
		}
		var d = $($('.card').find('.item-box.active')[0]);
		if (!d.length) {
			layer.msg('需要选择当前订单的支付方式哦~', {offset: '100px'});
			return;
		}
		if (d.data('id') == 1 || d.data('id') == 2) {
			if (!$('.zftm').val()) {
				layer.msg('请先用扫码枪扫码~', {offset: '100px'});
				return;
			}
			orderPay($('.zftm').val(), d.data('id'));
		} else {
			orderPay('', d.data('id'));
		}
	});

	// 扫码就支付
	$('.zftm').bind('keydown', function (e) {
		if (e.keyCode == 13) {
			var type = $($('.card').find('.item-box.active')[0]).data('id');
			orderPay($(this).val(), type);
		}
	})
    getPageMsg();

    /***
    *  改版后新加代码
    */
   $('#checkout .card .inline-box .item-box').mouseenter(function () {
   	    $(this).find('.in').removeClass('hidden')
   		$(this).find('.in').prev().addClass('hidden')
   })
   $('#checkout .card .inline-box .item-box').mouseleave(function () {
   		if ($(this).hasClass('active')) {
   			return
   		}
   		$(this).find('.in').addClass('hidden')
   		$(this).find('.in').prev().removeClass('hidden')
   })

  /***
  	*  加预定新加代码
  	*/
  // 点击商品代金券提示
  $('.goods .tip').on('click', function () {
  	// 点击提示的时候显示商品代金券列表
  	$('.product').addClass('hidden');
  	$('.coupon').removeClass('hidden');
  	var data = template('ticketsList', ticketList);
	$('#coupon_list').html(data);
	// 把以前选择的代金券总额计算出来
	if (!chooseTicketSum) {
		for (let i=0, len=ticketList.list.length; i<len; i++) {
			if (ticketList.list[i].choose) {
				chooseTicketSum = ticketList.list[i].money - 0 + chooseTicketSum;
			}
		}
		$('.tickets-sum').html(chooseTicketSum);
	}
	$('.check-box').each(function() {
		layui.form.render('checkbox');
	});
  })
  // 确定商品代金券选择
  $('.coupon .submit-ticket').on('click', function () {
  	chooseTicketSum = 0;
  	$('#coupon_list').find('input[type="checkbox"]').each(function (i, v) {
  		ticketList.list[$(v).parent().data('index')].choose = $(v).is(':checked');
  		if ($(v).is(':checked')) {
  			chooseTicketSum = ticketList.list[$(v).parent().data('index')].money - 0 + chooseTicketSum
  		}
  	})
  	$('.coupon').addClass('hidden');
  	$('.product').removeClass('hidden');
  	// 要重新计算合计价格(商品代金券只能抵扣商品价格) 和 代金券tip显示哪条
  	calcProductData();
  })
  // 点击取消当前的代金券选择 对以前的代金券选择没有影响
  $('.coupon .submit-cancel').on('click', function () {
  	$('.coupon').addClass('hidden');
  	$('.product').removeClass('hidden');
  	chooseTicketSum = 0;
  	for (let i=0, len=ticketList.list.length; i<len; i++) {
		if (ticketList.list[i].choose) {
			chooseTicketSum = ticketList.list[i].money - 0 + chooseTicketSum;
		}
	}
	$('.ticket-sum').html(chooseTicketSum);
  })
  // 选择普通订单或者预定订单
  $('.flag-order > span').on('click', function () {
  	if (!$('.member-level').data('memberid') && $(this).siblings().data('type') == 2) {
  		layer.msg('预定订单需用户是本店会员才能使用~', {offset: '100px'});
  		return;
  	}
  	let that = this;
  	if (!orderList.list.length) {
  		$(that).addClass('hidden');
  		$(that).siblings().removeClass('hidden');
  	} else {
  		// 无论选择什么类型 当前有订单的数据清掉 之前提示用户的选择 确定之后执行
	  	layer.open({
		  content: "切换订单类型, 需要重新添加商品~",
		  yes: function(index, layero){
		    $(that).addClass('hidden');
  			$(that).siblings().removeClass('hidden');
  			orderList.list = [];
  			calcProductData();
  			_fnShowOrder();
  			layer.close(index);
		  }
		});
  	}
  })

  // 取件模态框关闭 清除掉页面信息
	$('#takePart').on('hide.bs.modal', function () {
		bookGoodsList.list = [];
		let data = template('booksGoodsList', bookGoodsList);
		$('.bookgoods-list').html(data);
		$('.search-bookgoods').val('');
		$('.list-btn').addClass('hidden');
		$('.detail-btn').addClass('hidden');
		if ($('.package-content .list').hasClass('hidden')) {
			$('.package-content .list').removeClass('hidden');
		}
		$('.package-content .detail').addClass('hidden');
	})
	// 取件模态框
	// $('#takePart').on('shown.bs.modal', function () {
	// 	setTimeout(function () {       // 弹出时加一个延时 设置页面数据
	// 		if ($('.member-level').data('phone')) {
	// 			$('.member-search').val($('.member-level').data('phone'));
	// 			getMemberInfo($('.member-level').data('phone'));
	// 		}
	// 		$('.member-search').focus();
	// 	}, 500)
	// })

  // 点击取件
  $('#takeBookGoods').on('click', function () {
  	// 当前有会员信息时 直接搜索当前会员的取件信息
  	if ($('.member-level').data('memberid')) {
  		getMemberReserveList($('.member-level').data('phone'));
  	} else {
		let data = template('booksGoodsList', bookGoodsList);
		$('.bookgoods-list').html(data);
		$('#takePart').modal('show');
		setTimeout(function () {
			$('.search-bookgoods').focus();
		}, 500)
	}
  })
  // 搜索某个会员的取件信息
  $('.btn-search-bookgoods').on('click', function () {
  	if (!$('.search-bookgoods').val()) {
  		layer.msg('请输入会员手机号~', {offset: '100px'})
  		setTimeout(function () {
  			$('.search-bookgoods').focus();
  		}, 500)
  		return
  	} else {
  		getMemberReserveList($('.search-bookgoods').val(), true);
  	}
  })
  $('.search-bookgoods').on('keydown', function (e) {
	if (e.keyCode == '13') {
		if (!$(this).val()) {
	  		layer.msg('请输入会员手机号~', {offset: '100px'})
	  		$(this).focus();
	  		return
	  	} else {
	  		getMemberReserveList($(this).val(), true);
	  	}
	}
 })
 // 选择某个预定单子操作
 $(document).on('click','.bookgoods-list tbody tr', function () {
 	if ($(this).hasClass('active')) {
 		$(this).removeClass('active');
 	} else {
 		$(this).parent().parent().find('tr.active').removeClass('active');
 		$(this).addClass('active');
 	}
 })
 // 预定退款
$(document).on('click' ,'.take-part .goods-refond', function () {
	let i = $('.bookgoods-list tbody').find('.active').data('index');
 	if (i || i === 0) {
 		layer.open({
		  type: 1,
		  skin: 'layui-layer-rim', //加上边框
  		  area: ['400px', '248px'], //宽高
		  title: '退款商品数量',
		  closeBtn: 1,
		  shadeClose: false,
		  skin: 'take-modal',
		  content: $('#take_refund'),
		  btn: ['确定', '取消'],
		  success: function () {
		  	$('.take-modal .take-refund-num').val('');
		  	$('.take-modal .take-refund-num').focus();
		  	$('.take-modal .refund-money').html('0.00');
		  	// 判断显示 现金/会员卡 退回方式
			if (bookGoodsList.list[i].pay_way === 3) {
				if ($('.take-modal .type-member').hasClass('hidden')) {
					$('.take-modal .type-member').removeClass('hidden');
					$('.take-modal .type-cash').addClass('hidden');
				}
			} else {
				if ($('.take-modal .type-cash').hasClass('hidden')) {
					$('.take-modal .type-cash').removeClass('hidden');
					$('.take-modal .type-member').addClass('hidden');
				}
			}
		  },
          yes: function(index){
          	// 确定退款
          	let num = $('.take-modal .take-refund-num').val()
          	if (!num) {
          		layer.msg('请输入退款数量~', { offset: '100px' })
          	} else {
          		let data = {
          			order_goods_id: bookGoodsList.list[i].order_goods_id,
          			refund_num: num,
          			worker_id: workId
          		}
          		$ajax('/api/refund', data,
					function (rel) {
						console.log(rel);
						bookGoodsList.list[i].dq_num = bookGoodsList.list[i].dq_num - num;
						if (!bookGoodsList.list[i].dq_num) {
							bookGoodsList.list.splice(i, 1)
						}
						let data = template('booksGoodsList', bookGoodsList);
						$('.bookgoods-list').html(data);
						layer.msg('退款成功~', { offset: '100px' });
						$('.take-modal .take-refund-num').val('');
						$('.take-modal .refund-money').html('0.00')
						layer.close(index)
					},
					function (err) {
						layer.msg(err.msg, {offset: '100px'});
					}, 'POST'
				)
          	}
          },
          cancel: function () {
				$('.take-modal .take-refund-num').val('');
				$('.take-modal .refund-money').html('0.00')
          }
		});
 	} else {
 		layer.msg('请先选择一个订单~', { offset: '100px' })
 		return
 	}
})
$(document).on('keyup', '.take-modal .take-refund-num', function () {
 	$(this).val($(this).val().replace(/[^\d]/g,''));
	if (!$(this).val() || $(this).val() === '0') {
		layer.msg('请输入大于0的数字~', { offset: '100px' });
		$('.take-modal .refund-money').html('0.00')
		$(this).val('');
		$(this).focus();
		return;
	} else {
		if (($(this).val() - 0) > bookGoodsList.list[$('.bookgoods-list tbody').find('.active').data('index')].dq_num) {
			layer.msg('当前退款数量不能大于待取数量~', { offset: '100px' });
			$(this).val('');
			$('.take-modal .refund-money').html('0.00')
		} else {
			$('.take-modal .refund-money').html((($(this).val() - 0) * (bookGoodsList.list[$('.bookgoods-list tbody').find('.active').data('index')].price - bookGoodsList.list[$('.bookgoods-list tbody').find('.active').data('index')].ticket_deduction)).toFixed(2))
		}
	}
 })
 // 预定查看
 $(document).on('click' ,'.take-part .goods-check', function () {
 	let i = $('.bookgoods-list tbody').find('.active').data('index');
 	if (i || i === 0) {
 		let data = {
 			order_goods_id: bookGoodsList.list[i].order_goods_id
 		}
 		$ajax('/api/viewBookHistory', data,
			function (rel) {
				console.log(rel);
				bookGoodsListDetail.list = rel;
				let data = template('booksGoodsListDetail', bookGoodsListDetail);
				$('.bookgoods-listdetail').html(data);
				$('.list-btn').addClass('hidden');
				$('.detail-btn').removeClass('hidden');
				$('.package-content .list').addClass('hidden');
				if ($('.package-content .detail').hasClass('hidden')) {
					$('.package-content .detail').removeClass('hidden');
				}
			},
			function (err) {
				layer.msg(err.msg, {offset: '100px'});
			}, 'POST'
		)
 	} else {
 		layer.msg('请先选择一个订单~', { offset: '100px' })
 		return
 	}
 })
 // 预定取货
 $(document).on('click' ,'.take-part .goods-take', function () {
 	let i = $('.bookgoods-list tbody').find('.active').data('index');
 	if (i || i === 0) {
 		//  当前门店没有库存的时候直接提示
	 	if (!bookGoodsList.list[i].stock) {
	 		layer.msg('当前商品门店没有库存~', { offset: '100px' })
	 		return
	 	}
 		layer.open({
		  type: 1,
		  skin: 'layui-layer-rim', //加上边框
  		  area: ['400px', '248px'], //宽高
		  title: '此次取货数量',
		  closeBtn: 1,
		  shadeClose: false,
		  skin: 'take-modal',
		  content: $('#take_input'),
		  btn: ['确定', '取消'],
		  success: function () {
		  		$('.take-modal .take-num').val('');
				$('.take-modal .take-num').focus();
				$('.take-modal .take-tip .stock').html(bookGoodsList.list[i].stock);
		  },
          yes: function(index){
          	// 确定取货
          	let num = $('.take-modal .take-num').val()
          	if (!num) {
          		layer.msg('请输入取货数量~', { offset: '100px' })
          	} else {
          		let data = {
          			order_goods_id: bookGoodsList.list[i].order_goods_id,
          			take_num: num,
          			worker_id: workId
          		}
          		$ajax('/api/takeBookGoods', data,
					function (rel) {
						console.log(rel);
						bookGoodsList.list[i].dq_num = bookGoodsList.list[i].dq_num - num;
						bookGoodsList.list[i].stock = bookGoodsList.list[i].stock - num;
						if (!bookGoodsList.list[i].dq_num) {
							bookGoodsList.list.splice(i, 1)
						}
						let data = template('booksGoodsList', bookGoodsList);
						$('.bookgoods-list').html(data);
						layer.msg('取货成功~', { offset: '100px'});
						$('.take-modal .take-num').val('');
						layer.close(index)
					},
					function (err) {
						if (err.code === 302) {  // 当前商品库存不足
							bookGoodsList.list[i].stock = err.data;
							layer.close(index);
						}
						layer.msg(err.msg, {offset: '100px'});
					}, 'POST'
				)
          	}
          },
          cancel: function () {
          	$('.take-modal .take-num').val('');
          }
		});
 	} else {
 		layer.msg('请先选择一个订单~', { offset: '100px' })
 		return
 	}
 })

 $(document).on('keyup', '.take-modal .take-num', function () {
 	$(this).val($(this).val().replace(/[^\d]/g,''));
	if (!$(this).val()) {
		layer.msg('请输入数字~', { offset: '100px' });
		return;
	} else {
		if (($(this).val() - 0) > bookGoodsList.list[$('.bookgoods-list tbody').find('.active').data('index')].stock) {
			layer.msg('当前输入大于门店可用库存~', { offset: '100px' });
			$(this).val(bookGoodsList.list[$('.bookgoods-list tbody').find('.active').data('index')].dq_num);
		} else if (($(this).val() - 0) > bookGoodsList.list[$('.bookgoods-list tbody').find('.active').data('index')].dq_num) {
			layer.msg('当前输入大于待取数量~', { offset: '100px' });
			$(this).val(bookGoodsList.list[$('.bookgoods-list tbody').find('.active').data('index')].dq_num);
		}
	}
 })
 // 预定详情返回
 $(document).on('click', '.take-part .detail-return', function () {
 	$('.list-btn').removeClass('hidden');
	$('.detail-btn').addClass('hidden');
	$('.package-content .list').removeClass('hidden');
	$('.package-content .detail').addClass('hidden');
 })
})
