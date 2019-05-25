let f_focus = false;
// 获取分页数据
var getStockList = function (page) {
	var data = {};
	var item_name = $('.choose-itemname').val();
	var bar_code = $('.choose-code').val();
	var shop_id = $('.choose-shop').val() || '';
	var f_cate = $('.search-type1').val();
	var s_cate = $('.search-type2').val();
	var show_zero = $('.choose-status').val();
	data = {
		item_name: item_name,
		 bar_code: bar_code,
		  shop_id: shop_id,
		   f_cate: f_cate,
		   s_cate: s_cate,
		show_zero: show_zero,
		  curPage: page
	}
	$ajax('/stock_check/getStockList', data,
		function (rel) {
			console.log(rel);
			// 加入总数据
			let summary = `
				<div>
					<span>当前库存总数量</span>
					<span>${rel.stock_all}</span>
				</div>
			`;
			if (is_show) {
				summary = summary + `
				<div>
						<span>当前库存总成本</span>
						<span>${rel.store_cost_all}</span>
					</div>
					<div>
						<span>当前门店进价总额</span>
						<span>${rel.md_price_all}</span>
					</div>
				`
			}
			$('.summary').html(summary);
			layui.form.render('select','searchHeader');
			invertoryList.list = rel.list;
			if (invertoryList.list.length) {
				let list = template('stockList', invertoryList);
				$('#table_list').html(list);
			}
			if (page === 1) {
				laypage.render({
				    elem: 'pagelayer' //注意，这里的 test1 是 ID，不用加 # 号
				    ,count: rel.totalNum //数据总数，从服务端得到
				    ,limit: rel.pageSize
				    ,jump: function (obj, first) {
				    	if (!first) {
				    		getStockList(obj.curr);
				    	}
				    }
				})
			}
			if (rel.list.length === 0 && page === 1) {
				invertoryList.list = [];
				let list = template('stockList', invertoryList);
				$('#table_list').html(list);
				layer.msg('还没有数据哦~', {offset: '100px'});
				if (!$('#pagelayer').hasClass('hidden')) {
					$('#pagelayer').addClass('hidden')
				}
			} else {
				if ($('#pagelayer').hasClass('hidden')) {
					$('#pagelayer').removeClass('hidden')
				}
			}
		},
		function (err) {
			invertoryList.list = [];
			let list = template('stockList', invertoryList);
			$('#table_list').html(list);
			if (!$('#pagelayer').hasClass('hidden')) {
				$('#pagelayer').addClass('hidden')
			}
			layer.msg(err.msg, {offset: '100px'});
		}, 'POST'
	)
}

// 根据条件查询
$('.search-stock').on('click', function () {
	getStockList(1);
})

$('.stock').on('click', function () {
	_fnGetStockDetail('', 1, 1);
})

$('.cost').on('click', function () {
	_fnGetStockDetail('', 1, 2);
})

// 获取出入库明细
let _fnGetStockDetail = function (self, page, item) {
	let data = {};
	let urll = item ===1?'/stock_check/get_stock_detail':'/stock_check/get_price_detail';
	if (self) {
		let curr = $(self).parent().parent().parent();
		data.shop_id = $(curr).data('shop');
		data.item_id = $(curr).data('id');
		if (item === 1) {
			$('#stock_list').data('shopid', data.shop_id);
			$('#stock_list').data('itemid', data.item_id);
		} else {
			$('#cost_list').data('shopid', data.shop_id);
			$('#cost_list').data('itemid', data.item_id);
		}
	} else {
		if (item === 1) {
			data.shop_id = $('#stock_list').data('shopid');
			data.item_id = $('#stock_list').data('itemid');
		} else {
			data.shop_id = $('#cost_list').data('shopid');
			data.item_id = $('#cost_list').data('itemid');
		}
	}
	data.curPage = page;
	if (item === 1) {
		data.time = $('.get-stock .choose-time').val();
    	data.type = $('.get-stock .choose-type').val();
	} else {
		data.time = $('.get-cost .choose-time').val();
    	data.type = $('.get-cost .choose-type').val();
	}
    let list;
    $ajax(urll, data, function (rel) {
    	console.log(rel);
    	for (let i=0,len=rel.list.length; i<len; i++) {
    		rel.list[i].time = changeTime(rel.list[i].time * 1000);
    	}
    	productList.list = rel.list;
    	if (item === 1) {
    		let list = template('productList', productList);
    		$('#stock_list').html(list);
    		if (page === 1) {
			laypage.render({
			    elem: 'stockPage' //注意，这里的 test1 是 ID，不用加 # 号
			    ,count: rel.totalNum //数据总数，从服务端得到
			    ,limit: rel.pageSize
			    ,jump: function (obj, first) {
			    	if (!first) {
			    		_fnGetStockDetail('', obj.curr, item);
			    	}
			    }
			})
			}
	    	$('.get-stock .choose-time').each(function() {
				laydate.render({
					elem: this
				    ,type: 'datetime'
				    ,max: changeTime(new Date())
				    ,range: '~'
				});
			});
    	} else {
    		let list = template('costList', productList);
    		$('#cost_list').html(list);
    		if (page === 1) {
				laypage.render({
				    elem: 'costPage' //注意，这里的 test1 是 ID，不用加 # 号
				    ,count: rel.totalNum //数据总数，从服务端得到
				    ,limit: rel.pageSize
				    ,jump: function (obj, first) {
				    	if (!first) {
				    		_fnGetStockDetail('', obj.curr, item);
				    	}
				    }
				})
			}
	    	$('.get-cost .choose-time').each(function() {
				laydate.render({
					elem: this
				    ,type: 'datetime'
				    ,max: changeTime(new Date())
				    ,range: '~'
				});
			});
    	}
    	if (rel.list.length === 0 && page === 1) {
			layer.msg('还没有数据哦~', {offset: '100px'});
			if (item === 1) {
				if (!$('#stockPage').hasClass('hidden')) {
					$('#stockPage').addClass('hidden')
				}
			} else {
				if (!$('#costPage').hasClass('hidden')) {
					$('#costPage').addClass('hidden')
				}
			}
		} else {
			if (item === 1) {
				if ($('#stockPage').hasClass('hidden')) {
					$('#stockPage').removeClass('hidden')
				}
			} else {
				if ($('#costPage').hasClass('hidden')) {
					$('#costPage').removeClass('hidden')
				}
			}
		}
    	layui.form.render('select','editDefaultValue');
    }, function (err) {
    	layer.msg(err.msg, {offset: '100px'});
    }, 'POST')

}

$('.get-stock').on('hide.bs.modal', function () {
	$('.get-stock .choose-time').val('');
	$('.get-stock .choose-type').val('');
	$('#product_list').data('shopid', '');
	$('#product_list').data('itemid', '');
	productList.list = '';
})
$('.get-cost').on('hide.bs.modal', function () {
	$('.get-cost .choose-time').val('');
	$('.get-cost .choose-type').val('');
	$('#cost_list').data('shopid', '');
	$('#cost_list').data('itemid', '');
	productList.list = '';
})

// 出入库明细
$(document).on('click', '.invertory .i-content .t-calc .stock', function () {
	_fnGetStockDetail(this, 1, 1);
})

// cost 成本明细
$(document).on('click', '.invertory .i-content .t-calc .cost', function () {
	_fnGetStockDetail(this, 1, 2);
})


let _fnInit = function () {
	let shop = '';
	// 加入仓库数据
	for (let i = 0, len = shop_list.length; i<len; i++) {
		if (len === 1) {
			let v = shop_list[i]
			shop += `<option value=${v.id} selected>${v.name}</option>`
			$('.choose-shop').html(shop);
		} else {
			let v = shop_list[i]
			shop += `<option value=${v.id}>${v.name}</option>`
			$('.choose-shop').html(`<option value="">请选择店铺</option>` + shop);
		}
	}
	getStockList(1);
}
_fnInitDatas(_fnInit);
