let f_focus = false;
let putRejectList = {    // 退货单出库库商品列表
	list: []
}

// 获取退货单列表
var getRejectList = function (page) {
	var data = {};
	var time = $('.choose-time').val();
	var item_name = $('.choose-itemname').val();
	var sn = $('.choose-sn').val();
	var shop_id = $('.choose-shop').val();
	var supplier_id = $('.choose-supplier').val();
	var status = $('.choose-status').val();
	data = {
		time:          time,
		item_name:     item_name,
		sn:            sn,
		shop_id:       shop_id,
		supplier_id:   supplier_id,
		status:        status,
		page:          page
	}
	$ajax('/reject/getRejectList', data,
		function (rel) {
			console.log(rel);
			// 加入总数据
			let summary = `
				<div>
					<span>单据总笔数：</span>
					<span>${rel.totalNum}</span>
				</div>
			`;
			if (is_show) {
				summary = summary + `
					<div>
						<span>退货总额合计：</span>
						<span>${rel.total_amount}</span>
					</div>
				`
			}
			$('.summary').html(summary);
			layui.form.render('select','searchHeader');
			invertoryList.list = rel.reject_list;
			let list = template('rejectList', invertoryList);
			$('#table_list').html(list);
			$('.choose-time').each(function() {
				laydate.render({
					elem: this
				    ,type: 'datetime'
				    ,max: changeTime(new Date())
				    ,range: '~'
				});
			});
			if (page === 1) {
				laypage.render({
				    elem: 'pagelayer' //注意，这里的 test1 是 ID，不用加 # 号
				    ,count: rel.totalNum //数据总数，从服务端得到
				    ,limit: rel.pageSize
				    ,jump: function (obj, first) {
				    	if (!first) {
				    		getRejectList(obj.curr);
				    	}
				    }
				})
			}
			if (rel.reject_list.length === 0 && page === 1) {
				invertoryList.list = [];
				let list = template('rejectList', invertoryList);
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
			let list = template('rejectList', invertoryList);
			$('#table_list').html(list);
			if (!$('#pagelayer').hasClass('hidden')) {
				$('#pagelayer').addClass('hidden')
			}
			layer.msg(err.msg, {offset: '100px'});
			$('.choose-time').each(function() {
				layui.laydate.render({
					elem: this
				    ,type: 'datetime'
				    ,max: changeTime(new Date())
				    ,range: '~'
				});
			});
		}, 'POST'
	)
}

// 根据条件查询采购单
$('.search-reject').on('click', function () {
	getRejectList(1);
})

// 清除或者赋值采购模态框
let clearReject = function () {
	$('#editReject #product_list').html('');
	$('.edit-reject .list .detail .r-sum').val('');
	$('.edit-reject .list .detail .r-creator').val('');
	$('.edit-reject .list .detail .s-putpeople').val('');
	$('.edit-reject .list .detail .r-remark').val('');
	$('.edit-reject .list .detail .edit-sn').val('');
	$('.edit-time').val('');
	$('.edit-shop').val('');
	$('.edit-supplier').val('');
	if ($('.set-scan').hasClass('active')) {
		$('.set-scan').removeClass('active')
	}
	if ($('.showsum').hasClass('hidden')) {
		$('.showsum').removeClass('hidden')
	}
}

// 获取退货单详情  put => true 代表是出库获取
let _fnGetRejectDetail = function (id, put) {
	if (!put) {
		if(id) {
			let data = {};
			data.reject_id = id;
			$ajax('/Reject/getRejectDetail', data, function (rel) {
				console.log(rel);
				productList.list = rel.reject_item_list;
				productList.reject_id = id;
				if ($('.edit-reject .puttime').has('hidden')) {
					$('.edit-reject .puttime').removeClass('hidden');
				}
				if ($('.edit-reject .putsn').hasClass('hidden')) {
					$('.edit-reject .putsn').removeClass('hidden')
				}
				if ($('.edit-reject .creator').hasClass('hidden')) {
					$('.edit-reject .creator').removeClass('hidden')
				}
				if ($('.edit-reject .calcpeople').hasClass('hidden')) {
					$('.edit-reject .calcpeople').removeClass('hidden')
				}
				$('.edit-reject .list .detail .r-sum').val(rel.amount);
				$('.edit-reject .list .detail .r-creator').val(rel.refund_bill_user);
				$('.edit-reject .list .detail .s-putpeople').val(rel.out_stock_user);
				$('.edit-reject .list .detail .r-remark').val(rel.remark);
				$('.edit-sn').val(rel.sn);
				$('.edit-time').val(rel.addtime);
				let shop = '';
				// 加入仓库数据
				if (!rel.status) {
					for (let i = 0, len = shop_list.length; i<len; i++) {
						let v = shop_list[i]
						if (rel.shop_id === v.id) {
							shop += `<option value=${v.id} selected>${v.name}</option>`
						} else {
							shop += `<option value=${v.id}>${v.name}</option>`
						}
					}
					shop = `<option value="">退货仓库</option>` + shop;
				} else {
					shop = `<option value="`+ rel.shop_id +`" selected>`+ searchNameByid(rel.shop_id, shop_list) +`</option>`
				}
				$('.edit-shop').html(shop);
				// 加入供应商数据
				let supper = '';
				if (!rel.status) {
					for (let i = 0, len = supplier_list.length; i<len; i++) {
						let v = supplier_list[i];
						if (rel.supplier === v.id) {
							supper += `<option value=${v.id} selected>${v.name}</option>`
						} else {
							supper += `<option value=${v.id}>${v.name}</option>`
						}
					}
					supper = `<option value="">供应商</option>` + supper;
				} else {
					supper = `<option value="`+ rel.supplier +`" selected>`+ searchNameByid(rel.supplier, supplier_list) +`</option>`
				}
				$('.edit-supplier').html(supper);
				productList.status = rel.status;
				if (rel.status) {
					if (!$('.edit-reject .btn-default-border').hasClass('hidden')) {
						$('.edit-reject .btn-default-border').addClass('hidden')
					}
					if (!is_show) {
						if (!$('.showsum').hasClass('hidden')) {
							$('.showsum').addClass('hidden')
						}
					}
				} else {
					if ($('.edit-reject .btn-default-border').hasClass('hidden')) {
						$('.edit-reject .btn-default-border').removeClass('hidden')
					}
				}
				if (rel.status) {
					$('.r-remark').attr('readonly', true);
				} else {
					$('.r-remark').attr('readonly', false)
				} 
				let list = template('productList', productList);
				$('#product_list').html(list);
				layui.form.render('select','editDefaultValue');	
			}, function (err) {
				layer.msg(err.msg, {offset: '100px'})
			}, 'POST')
		} else {
			productList.reject_id = '';
			productList.status = '';
			let shop = '';
			// 加入仓库数据
			for (let i = 0, len = shop_list.length; i<len; i++) {
				let v = shop_list[i]
				
				shop += `<option value=${v.id}>${v.name}</option>`
			}
			shop = `<option value="">退货仓库</option>` + shop;
			$('.edit-shop').html(shop);
			// 加入供应商数据
			let supper = '';
			for (let i = 0, len = supplier_list.length; i<len; i++) {
				let v = supplier_list[i];
				
				supper += `<option value=${v.id}>${v.name}</option>`
			}
			supper = `<option value="">供应商</option>` + supper;
			$('.edit-supplier').html(supper);
			let list = template('productList', productList);
			$('#product_list').html(list);
			if (!$('.edit-reject .puttime').hasClass('hidden')) {
				$('.edit-reject .puttime').addClass('hidden');
			}
			if (!$('.edit-reject .putsn').hasClass('hidden')) {
				$('.edit-reject .putsn').addClass('hidden')
			}
			if (!$('.edit-reject .creator').hasClass('hidden')) {
				$('.edit-reject .creator').addClass('hidden')
			}
			if (!$('.edit-reject .calcpeople').hasClass('hidden')) {
				$('.edit-reject .calcpeople').addClass('hidden')
			}
			layui.form.render('select','editDefaultValue');
		}
	} else {
		let data = {};
		data.reject_id = id;
		$ajax('/Reject/getRejectDetail', data, function (rel) {
			console.log(rel);
			$('.put-reject .put-shop').val(searchNameByid(rel.shop_id, shop_list));
			$('.put-reject .put-admin').val(rel.refund_bill_user);
			// $('.put-reject .put-shopcalc').val(rel.out_stock_user);
			$('.put-reject .put-remark').val(rel.remark);
			putRejectList.list = rel.reject_item_list;
			putRejectList.reject_id = id;
			let list = template('putProductList', putRejectList);
			$('#putProductItems').html(list);
		}, function (err) {
			layer.msg(err.msg, {offset: '100px'})
		}, 'POST')
	}
}

// 新增采购单 弹出模态框
$('.add-reject').click(function () {
	// 获取基本信息
	_fnGetRejectDetail();
})

// 采购单模态框弹出事件
$('#editReject').on('shown.bs.modal', function () {
	
})

// 退货单模态框关闭事件 清除一些数据
$('#editReject').on('hide.bs.modal', function () {
	productList.list = []
	let list = template('productList', productList);
	$('#product_list').html(list);
	clearReject();
})

// 点击商品名称(focus)弹出商品列表
$(document).on('focus','#product_list tbody tr .s-search', function () {
	// 判断如果当前是采购 需要先选择一个退货仓库 才能搜索商品
	if (is_show) {
		if (!$('.edit-reject .edit-shop').val()) {
			layer.msg('添加商品需要先选一个退货仓库', {offset: '100px'})
			return false;
		}
	}
	// 先判断扫码枪是否开启
	if ($('#editReject .set-scan').hasClass('active')) {

	} else if (!$(this).val()) {
		if (f_focus) {
			return false;
		}
		f_focus = true
		// 弹出选择商品列表框
		$('#editProduct').modal();
		
		_fnSearchProduct(1);
	}
})

$('.search-product').on('click', function () {
	_fnSearchProduct(1);
})

// 激活扫码枪
$(document).on('click', '#editReject .set-scan', function () {
	if (is_show) {
		if (!$('.edit-reject .edit-shop').val()) {
			layer.msg('添加商品需要先选一个退货仓库', {offset: '100px'})
			return false;
		}
	}
	// 如果当前是激活状态
	if ($(this).hasClass('active')) {
		$(this).removeClass('active')
	} else {
		$(this).addClass('active');
		// 循环遍历有没有商品名为空的行 或者 没有空行添加行
		let flag = false;
		$('#product_list tbody tr').each(function (i, v) {
			if (!$(v).find('.s-search').val()) {
				$(v).find('.s-search').focus();
				flag = true
			}
		})
		if (!flag) {
			productList.list.push({});
			let list = template('productList', productList);
			$('#product_list').html(list);
			// 设置最后一个tr focus
			$('#product_list tbody tr:last-child').find('.s-search').focus();
		}
	}
})

//扫码枪得到二维码
$(document).on('keyup', '.edit-reject #product_list tr .s-search', function (e) {
	let that = this;
	if (!$(that).val()) {
		layer.msg('条码不能为空~', {offset: '100px'})
		$(that).focus();
		return
	}
	if (e.keyCode == 13) {
		$ajax('/Reject/get_goods_by_code', {bar_code: $(that).val(), shop_id: $('.edit-reject .edit-shop').val()}, function (rel) {
			console.log(rel);
			let flag = false;
			for (let i=0, len=productList.list.length; i<len; i++) {
				let v = productList.list[i];
				if (rel.item_id == v.item_id) {
					if (v.stock === 0 || v.num >= rel.stock) {   // 当前这个商品库存为0
						flag = true
						layer.msg('退货数量不能大于当前商品库存~', {offset: '100px'})
						break;
					} else {
						v.num++;
						flag = true;
						break;
					}
				}
			}
			if (!flag) {
				rel.num = 1;
				productList.list.splice(productList.list.length-1, 1);
				productList.list.push(rel);
				productList.list.push({});
				layer.msg('添加成功~', {offset: '100px'});
			}
			_fnSetChangeValue();
			// 设置最后一个tr focus
			$('#product_list tr:last-child').find('.s-search').focus();

		}, function (err) {
			layer.msg('没有找到相关商品~', {offset: '100px'});
			$(that).val('');
			$(that).focus();
		}, 'POST')
	}
}) 

// 添加商品到退货单 默认数量为1 重复商品数量++
$(document).on('click', '#editProduct #add_product', function () {
	let other_arr = [];
	if (productList.list.length === 0) {
		$('#productItems').find('input[type="checkbox"]:checked').each(function (i, v) {
			let curr = productItems.list[$(v).parent().parent().data('index')];
			curr.num = 1;
			productList.list.push(curr)
		})
	} else {
		$('#productItems').find('input[type="checkbox"]:checked').each(function (k, v) {
			let flag = false;
			let curr = productItems.list[$(v).parent().parent().data('index')];
			for (let i=0, len=productList.list.length; i<len; i++) {
				let v = productList.list[i];
				if (!v.item_id) {
					productList.list.splice(i, 1);
					continue;
				}
				if (v.item_id === curr.item_id) {
					if (v.num < v.stock) {
						v.num++;
					}
					flag = true
					continue;
				}
			}
			if (!flag) {
				curr.num = 1;
				other_arr.push(curr)
			}
		})
		productList.list = productList.list.concat(other_arr);
	}
	if ($('#productItems').find('input[type="checkbox"]:checked').length) {
		productList.list.push({});            // 添加商品的同时增加空的一行
	}
	_fnSetChangeValue('', true);
	// 主动关闭模态框
	$('#editProduct').modal('hide')
})

// 退货单详情中的添加
$(document).on('click', '.edit-reject table tbody .t-calc .add', function () {
	let flag = false;
	$('#product_list tbody tr').each(function (i, v) {
		if (!$(v).find('.s-search').val()) {
			flag = true;
		}
	})
	if (flag) {
		layer.msg('当前已添加新的一行~', {offset: '100px'})
	} else {
		productList.list.push({});
		let list = template('productList', productList);
		$('#product_list').html(list);
		if ($('#editPurchase .set-scan').hasClass('active')) {
			$('#product_list tr:last-child').find('.s-search').focus();
		}
	}
})

// 添加商品或者改变商品设置单据 进价 成本
   // 采购金额=数量*采购单价；门店进价=数量*门店单价
   // 单据金额=采购金额之和  
   // 门店总价=门店进价之和
   // 实际金额：默认为单据金额，可修改；实际金额不可大于单据金额，可为0
   // 商品A的入库成本=实际金额*（商品A的采购金额/单据金额）
let _fnSetChangeValue = function (index) {   // 当前改变的数组第几个 cost 修改成本
	let sum_value = 0;
	for (let i=0, len=productList.list.length; i<len; i++) {
		let v = productList.list[i];
		if (v.item_id) {
			sum_value = sum_value + v.num * (v.cost_price - 0);
		}
	}
	$('.edit-reject .r-sum').val(sum_value);
	if (index || index === 0) {  // 只修改当前那条数据
		let tr = $('.edit-reject table tbody').find('tr[data-index='+ index +']');
		$(tr).find('.s-amount').val(productList.list[index].num * (productList.list[index].cost_price - 0));
	} else {   // 整个添加
		let list = template('productList', productList);
		let flag = false;
		if ($('#editReject .set-scan').hasClass('active')) {
			flag = true;
		}
		$('#product_list').html(list);
		if (flag) {
			$('#editReject .set-scan').addClass('active')
		}
	}
}

// 删除
$(document).on('click', '.edit-reject table .t-calc .remove', function () {
	productList.list.splice($(this).parent().parent().parent().data('index'), 1);
	_fnSetChangeValue();
	if ($('#editRecject .set-scan').hasClass('active')) {
		$('#product_list tr:last-child').find('.s-search').focus();
	}
})
// 修改数量
$(document).on('keyup', '.edit-reject table .s-num', function () {
	$(this).val($(this).val().replace(/[^\d\.]/g,''));
	let index = $(this).parent().parent().data('index');
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
		if ($(this).val() - 0 > productList.list[index].stock) {
			productList.list[index].num = 1;
			$(this).val(1);
			layer.msg('调拨数量不能大于当前商品库存~', {offset: '100px'})
			setTimeout(function() {
			    $(this).focus();
			}, 1000)
		} else {
			productList.list[index].num = $(this).val();
			// 改变了数量 手动修改其它相关的值
		}
		// 改变了数量 手动修改其它相关的值
		_fnSetChangeValue(index);
		setTimeout(function() {
		    $(this).focus();
		}, 1000)
	}
})

$(document).on('blur', '.edit-reject table .s-num', function () {
	if (!$(this).val()) {
		_fnSetChangeValue();
	}
})


// 修改退货金额
$(document).on('keyup', '.edit-reject table .s-single', function () {
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
		productList.list[index].cost_price = $(this).val();
		_fnSetChangeValue(index);
		setTimeout(function() {
		    $(this).focus();
		}, 1000)
	}
})

$(document).on('blur', '.edit-reject table .s-single', function () {
	if (!$(this).val()) {
		_fnSetChangeValue();
	}
})

// 修改备注
$(document).on('keyup', '.edit-reject table .s-remark', function () {
	let index = $(this).parent().parent().parent().data('index');
	productList.list[index].remark = $(this).val();
})

// 添加/保存退货单
$('#add_reject').on('click', function () {
	if (productList.status) {
		$('#editReject').modal('hide');
		return false;
	}
	let data = {};
	// 判断条件
	if (!$('.edit-supplier').val()) {
		layer.msg('请选择供应商~', {offset: '100px'})
		return;
	} else {
		data.supplier_id = $('.edit-supplier').val()
	}
	if (!$('.edit-shop').val()) {
		layer.msg('请选择退货仓库~', {offset: '100px'})
		return;
	} else {
		data.shop_id = $('.edit-shop').val()
	}
	if (productList.list.length === 0 || !productList.list[0].item_id) {
		layer.msg('请添加需退货的商品~', {offset: '100px'})
		return;
	} else {
		let arr = [];
		for (let i=0, len=productList.list.length; i<len; i++) {
			let d = {};
			let v = productList.list[i];
			if (v.item_id) {
				d.item_id = v.item_id;
				d.reject_item_id = v.reject_item_id || ''
				d.num = v.num;
				d.cost_price = v.cost_price;
				d.remark = v.remark || '';
			}
			arr.push(d);
		}
		data.item_list = arr;
	}
	data.sn = $('.edit-sn').val();
	data.remark = $('.r-remark').val();
	data.reject_id = productList.reject_id;                   // 编辑==退货单id
	$ajax('/Reject/update', data, function (rel) {
		console.log(rel);
		$('#editReject').modal('hide');
		// 重刷采购单列表
		getRejectList(1);
	}, function (err) {
		// 302 商品库存已经发生变化
		if (err.code === 302) {
			/***
			 *  [{id, stock}] 更新库存退货数量归最大库存
			 */
			for (let i=0, len=productList.list.length; i<len; i++) {
				let d = productList.list[i];
				let flag = false;
				for (let j=0, len=err.data.length; j<len; j++) {
					let v = err.data[j];
					if (v.item_id == d.item_id) {
						if (v.stock === 0) {   // 当前这个商品库存为0
							flag = true
							err.data.splice(j, 1);
							break;
						} else {
							d.stock = v.stock;
							d.num = v.stock;
						}
					}
				}
				if (flag) {
					productList.list.splice(i, 1);
					i--;
				}
			}
			_fnSetChangeValue();
			layer.msg(err.msg, {offset: '100px'})
		} else {
			layer.msg(err.msg, {offset: '100px'})
		}
	}, 'POST')
})

// 查看退货单
$(document).on('click', '.invertory .i-content .t-calc .scan', function () {

})

// 退货单出库
$(document).on('click', '.invertory .i-content .t-calc .put', function () {
	_fnGetRejectDetail($(this).parent().parent().parent().data('id'), true);
})

// 入库采购单模态框关闭
$('#putReject').on('hide.bs.modal', function () {
	putRejectList.list = [];
	putRejectList.reject_id = '';
	let data = template('putProductList', putRejectList);
	$('#putReject #putProductItems').html(data);
	$('.put-reject .put-shop').val('');
	$('.put-reject .put-time').val('');
	$('.put-reject .put-admin').val('');
	$('.put-reject .put-shopcalc').val('');
	$('.put-reject .put-remark').val('');
})

// 点击确定出库
$(document).on('click', '#putReject #put_reject', function () {
	let data = {
		reject_id: putRejectList.reject_id
	}
	$ajax('/Reject/confirmOutStock', data, function (rel) {
		console.log(rel);
		$('#putReject').modal('hide');
		getRejectList(1);
	}, function (err) {
		layer.msg(err.msg, {offset: '100px'})
	}, 'POST')
})

// 编辑退货单
$(document).on('click', '.invertory .i-content .t-calc .edit', function () {
	_fnGetRejectDetail($(this).parent().parent().parent().data('id'));
})

// 删除退货单
$(document).on('click', '.invertory .i-content .t-calc .remove', function () {
	let that = this;
	layer.open({
	  content: '确定要删除当前退货单~',
	  yes: function(index, layero){
		$ajax('/Reject/delete', { reject_id: $(that).parent().parent().parent().data('id') }, function (rel) {
				console.log(rel);
				getRejectList(1);
				layer.msg('删除成功~', {offset: '100px'})
			}, function (err) {
				layer.msg(err.msg, {offset: '100px'})
			}, 'POST')
	   }
	});
})

let _fnInit = function () {
	let shop = '';
	// 加入仓库数据
	for (let i = 0, len = shop_list.length; i<len; i++) {
		let v = shop_list[i]
		shop += `<option value=${v.id}>${v.name}</option>`
	}
	shop = `<option value="">退货仓库</option>` + shop;
	$('.choose-shop').html(shop);
	// 加入供应商数据
	let supper = '';
	for (let i = 0, len = supplier_list.length; i<len; i++) {
		let v = supplier_list[i]
		supper += `<option value=${v.id}>${v.name}</option>`
	}
	supper = `<option value="">供应商</option>` + supper;
	$('.choose-supplier').html(supper);
	// 权限
	if (!is_show) {
		$('.add-reject').addClass('hidden')
	}
	getRejectList(1);
}
_fnInitDatas(_fnInit);
