let f_focus = false;
// 获取分页数据
var getStockList = function (page) {
	var data = {};
	var item_name = $('.choose-itemname').val();
	var bar_code = $('.choose-code').val();
	var shop_id = $('.choose-shop').val() || '';
	data = {
		item_name: item_name,
		 bar_code: bar_code,
		  shop_id: shop_id,
		  page: page
	}
	$ajax('/Stock/get_stock_list', data,
		function (rel) {
			console.log(rel);
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
			layui.form.render('select','searchHeader');
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

// 输入门店库存
$(document).on('keyup', '.stock table .input-stock', function () {
	$(this).val($(this).val().replace(/[^\d\.]/g,''));
	let index = $(this).parent().parent().parent().data('index');
	if (!$(this).val()) {
		layer.msg('请输入数字~', {offset: '100px'});
		setTimeout(function() {
		    $(this).focus();
		}, 1000)
	} else {
		if ($(this).val()[$(this).val().length - 1] === '.') {
			$(this).val($(this).val().replace('.', ''));
			console.log($(this).val())
		}
		invertoryList.list[index].currstock = $(this).val();
		$(this).parent().parent().next().find('.differ').val($(this).val() - invertoryList.list[index].stock);
	}
})

// 获取生成单子的初始数据
let _fnGetInitData = function (flag) {
	if (invertoryList.list.length === 0) {
		return
	}
	// 判断当前的库存有没有填完
	for (let i=0, len=invertoryList.list.length; i<len; i++) {
		let v = invertoryList.list[i];
		if (v.currstock === undefined) {
			layer.msg('当前还有库存未盘点完~', {offset: '100px'});
			$($('#table_list tbody tr')[i]).find('.input-stock').focus();
			return false;
		}
	}
	let urll = flag?'/Stock/create_inventory_profit':'/Stock/create_inventory_loss';
	$ajax(urll, {shop_id:  $('.choose-shop').val()}, function (rel) {
		console.log(rel);
		$('.profit-loss .create').val(rel.creator_name);
		$('.profit-loss .sn').val(rel.sn);
		$('.profit-loss .shop').val(rel.shop_name);
		invertoryList.flag = flag;
		if (flag) {
			$('.profit-loss .modal-title').html('盘盈入库单');
			$('#sure_items').html('提交盘盈单');
		} else {
			$('.profit-loss .modal-title').html('盘亏出库单');
			$('#sure_items').html('提交盘亏单');
		} 
		let data = template('itemList', invertoryList);
		$('#product_list').html(data);
		$('#editInventory').modal('show')
	}, function (err) {
		layer.msg(er.msg, {offset: '100px'})
	}, 'POST')
}

// 生成盘盈单
$(document).on('click', '.take-profit', function () {
	_fnGetInitData(1);
})
// 生成盘亏单
$(document).on('click', '.take-loss', function () {
	_fnGetInitData(0);	
})

$('.profit-loss').on('hide.bs.modal', function () {
	$('.profit-loss .remark').val('');
})


// 提交表单
$('#sure_items').on('click', function () {
	let data = {}
	if (invertoryList.flag) {  // 盘盈单
		data.type = 1;
	} else {   // 盘亏单
		data.type = 2;
	}
	data.id = '';
	data.sn = $('.profit-loss .sn').val();
	data.shop_id = $('.choose-shop').val();
	data.remark = $('.profit-loss .remark').val();
	data.item_list = [];
	let arr = [];
	$('.profit-loss tbody tr').each(function (i, v) {
		data.item_list.push({
			item_id: $(v).data('itemid'),
			num: $(v).find('.num').html() - 0,
			remark: $(v).find('.s-remark').val()
		})
	})
	$ajax('/Stock/save', data, function (rel) {
		console.log(rel);
		layer.msg('提交成功~', {offset: '100px'});
		$('#editInventory').modal('hide');
	}, function (err) {
		layer.msg(err.msg, {offset: '100px'})
	}, 'POST')
})

let _fnInit = function () {
	let shop = '';
	// 加入仓库数据
	for (let i = 0, len = shop_list.length; i<len; i++) {
		if (i === 0) {
			let v = shop_list[i]
			shop += `<option value=${v.id} selected>${v.name}</option>`
			$('.choose-shop').html(shop);
		} else {
			let v = shop_list[i]
			shop += `<option value=${v.id}>${v.name}</option>`
			$('.choose-shop').html(shop);
		}
	}
	getStockList(1);
}
_fnInitDatas(_fnInit);
