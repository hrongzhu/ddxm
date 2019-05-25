let f_focus = false;
// 获取分页数据
var getItemList = function (page) {
	var data = {};
	var item_name = $('.choose-itemname').val();
	var time = $('.choose-time').val();
	var shop_id = $('.choose-shop').val();
	var sn = $('.choose-sn').val() || '';
	var creator_name = $('.choose-create').val() || '';
	var status = $('.choose-status').val() || '';
	data = {
		item_name: item_name,
		     time: time,
		  shop_id: shop_id,
		       sn: sn,
     creator_name: creator_name,
		   status: status,
		     page: page
	}
	$ajax('/inventory_loss/get_inventory_loss_list', data,
		function (rel) {
			console.log(rel);
			invertoryList.list = rel.list;
			if (invertoryList.list.length) {
				let list = template('itemList', invertoryList);
				$('#table_list').html(list);
			}
			if (page === 1) {
				laypage.render({
				    elem: 'pagelayer' //注意，这里的 test1 是 ID，不用加 # 号
				    ,count: rel.totalNum //数据总数，从服务端得到
				    ,limit: rel.pageSize
				    ,jump: function (obj, first) {
				    	if (!first) {
				    		getItemList(obj.curr);
				    	}
				    }
				})
			}
			if (rel.list.length === 0 && page === 1) {
				invertoryList.list = [];
				let list = template('itemList', invertoryList);
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
			$('.choose-time').each(function() {
				laydate.render({
					elem: this
				    ,type: 'datetime'
				    ,max: changeTime(new Date())
				    ,range: '~'
				});
			});
		},
		function (err) {
			invertoryList.list = [];
			let list = template('itemList', invertoryList);
			$('#table_list').html(list);
			if (!$('#pagelayer').hasClass('hidden')) {
				$('#pagelayer').addClass('hidden')
			}
			layer.msg(err.msg, {offset: '100px'});
			$('.choose-time').each(function() {
				laydate.render({
					elem: this
				    ,type: 'datetime'
				    ,max: changeTime(new Date())
				    ,range: '~'
				});
			});
		}, 'POST'
	)
}

// 根据条件查询
$('.search-items').on('click', function () {
	getItemList(1);
})

// 获取单子详情
let _fnGetItemDetail = function (id, calc) {
	$ajax('/inventory_loss/get_one_inventory_loss_info', { id: id }, function (rel) {
		console.log(rel);
		$('.profit-loss .shop').val(rel.stock_info.shop_name);
		$('.profit-loss .time').val(rel.stock_info.time);
		$('.profit-loss .sn').val(rel.stock_info.sn);
		$('.profit-loss .create').val(rel.stock_info.creator_name);
		$('.profit-loss .putrule').val(rel.stock_info.complete_name);
		if (calc) {   // 入库
			productList.status = 0;
			if ($('#put_storage').hasClass('hidden')) {
				$('#put_storage').removeClass('hidden')
			}
			if (!$('.btn-close').hasClass('hidden')) {
				$('.btn-close').addClass('hidden')
			}
		} else {      // 查看
			productList.status = 1;
			if (!$('#put_storage').hasClass('hidden')) {
				$('#put_storage').addClass('hidden')
			}
			if ($('.profit-loss .btn-close').hasClass('hidden')) {
				$('.profit-loss .btn-close').removeClass('hidden')
			}
		}
		productList.id = rel.stock_info.id;
		$('.profit-loss .detail .remark').val(rel.stock_info.remark);
		productList.list = rel.stock_item_info;
		let data = template('productList', productList);
		$('#product_list').html(data);
		$('.profit-loss').modal('show');
	}, function (err) {
		layer.msg(err.msg, '100px');
	}, 'POST')
}

// 查看详情
$(document).on('click', '#table_list .t-calc .view', function () {
	let id = $(this).parent().parent().parent().data('id');
	_fnGetItemDetail(id, 0);
})

// 出库选择
$(document).on('click', '#table_list .t-calc .put', function () {
	let id = $(this).parent().parent().parent().data('id');
	_fnGetItemDetail(id, 1);
})

// 删除
$(document).on('click', '#table_list .t-calc .remove', function () {
	let id = $(this).parent().parent().parent().data('id');
	$ajax('/Stock/delete', {id: id, type: 2}, function (rel) {
		console.log(rel)
		getItemList(1);
		layer.msg('删除成功~', {offset: '100px'})
	}, function (err) {
		layer.msg(err.msg, {offset: '100px'})
	})
})

// 确定出库
$(document).on('click', '.profit-loss #put_storage', function () {
	$ajax('/inventory_loss/stock_out_confirm', {id: productList.id}, function (rel) {
		console.log(rel);
		layer.msg('操作成功~', {offset: '100px'});
		$('.profit-loss').modal('hide');
		getItemList(1);
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
	getItemList(1);
}

_fnInitDatas(_fnInit);
