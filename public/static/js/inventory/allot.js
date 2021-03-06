let f_focus = false;
// 获取分页数据
var getAllotList = function (page) {
	var data = {};
	var time = $('.choose-time').val();
	var item_name = $('.choose-itemname').val();
	var sn = $('.choose-sn').val();
	var from_shop = $('.from-shop').val();
	var to_shop = $('.to-shop').val();
	if (from_shop === to_shop && from_shop && to_shop) {
		$('.from-shop').val('');
		$('.to-shop').val('');
		layer.msg('调入仓库和调出仓库不能相同~', {offset: '100px'});
		getAllotList(1);
		return false;
	}
	var status = $('.choose-status').val();
	data = {
		time:          time,
		item_name:     item_name,
		sn:            sn,
		from_shop:     from_shop,
		to_shop:       to_shop,
		status:        status,
		page:          page
	}
	$ajax('/Allot/get_allot_list', data,
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
						<span>进价总金额</span>
						<span>${rel.bid_amount_all}</span>
					</div>
					<div>
						<span>成本总金额：</span>
						<span>${rel.cost_all}</span>
					</div>
				`
			}
			$('.summary').html(summary);
			layui.form.render('select','searchHeader');
			// 根据仓库id 和供应商id 换取名字
			for (let i=0, len=rel.allot_list.length; i<len; i++) {
				rel.allot_list[i].time = changeTime(rel.allot_list[i].time * 1000);
				if (rel.allot_list[i].in_time) {
					rel.allot_list[i].in_time = changeTime(rel.allot_list[i].in_time * 1000);
				} else {
					rel.allot_list[i].in_time = '';
				}
			}
			invertoryList.list = rel.allot_list;
			if (invertoryList.list.length) {
				let list = template('allotList', invertoryList);
				$('#table_list').html(list);
			}
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
				    		getAllotList(obj.curr);
				    	}
				    }
				})
			}
			if (rel.allot_list.length === 0 && page === 1) {
				invertoryList.list = [];
				let list = template('allotList', invertoryList);
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
			let list = template('allotList', invertoryList);
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

// 根据条件查询调拨单
$('.search-allot').on('click', function () {
	getAllotList(1);
})

// 清除或者赋值调拨模态框
let clearAllot = function () {
	$('#editAllot table tbody').html('');
	$('.edit-allot .list .detail .bid-amount').val('');
	$('.edit-allot .list .detail .from-people').val('');
	$('.edit-allot .list .detail .to-people').val('');
	$('.edit-allot .list .detail .shopcalc-name').val('');
	$('.edit-allot .list .detail .remark').val('');
	$('.edit-allot .list .detail .edit-sn').val('');
	$('.choosef-shop').val('');
	$('.chooset-shop').val('');
	$('.real-amount').val('');
	if ($('.set-scan').hasClass('active')) {
		$('.set-scan').removeClass('active')
	}
}

// 新增调拨单 弹出模态框
$('.add-allot').click(function () {
	// 获取基本信息
	$ajax('/allot/add', {},
	function(rel) {
		console.log(rel);
		$('.edit-allot .list .detail .admin-name').val(rel.admin_name);
		$('.edit-allot .top .edit-sn').val(rel.sn);
		$('.edit-allot .from-people').val(rel.outer);
		if (!$('.edittime').hasClass('hidden')) {
			$('.edittime').addClass('hidden')
		}
		let from_shop = '';
		if (is_show) {
			for (let i = 0, len = rel.from_shop.length; i<len; i++) {
				let v = rel.from_shop[i]
				from_shop += `<option value=${v.id}>${v.name}</option>`
			}
			from_shop = `<option value="">请选择仓库</option>` + from_shop;
		} else {
			from_shop += `<option value=${rel.from_shop.id} selected>${rel.from_shop.name}</option>`
		}
		$('.choosef-shop').html(from_shop);
		let to_shop = '';
		// 当前权限是门店的时候 调出仓库只能是总店
		// if (!is_show) {
		// 	to_shop = `<option value="`+ searchNameByName('总店', rel.to_shop) +`">总店</option>` + to_shop;
		// } else {
		for (let i = 0, len = rel.to_shop.length; i<len; i++) {
			let v = rel.to_shop[i]
			to_shop += `<option value=${v.id}>${v.name}</option>`
		}
		to_shop = `<option value="">请选择仓库</option>` + to_shop;
		// }
		$('.chooset-shop').html(to_shop);
		if ($('#add_allot').hasClass('hidden')) {
			$('#add_allot').removeClass('hidden')
		}
		if (!$('#sure_allot').hasClass('hidden')) {
			$('#sure_allot').addClass('hidden')
		}
		if (!is_show) {
			if (!$('.bidamount').hasClass('hidden')) {
				$('.bidamount').addClass('hidden')
			}
			if (!$('.realamount').hasClass('hidden')) {
				$('.realamount').addClass('hidden')
			}
		} else {
			if ($('.bidamount').hasClass('hidden')) {
				$('.bidamount').removeClass('hidden')
			}
			if ($('.realamount').hasClass('hidden')) {
				$('.realamount').remove('hidden')
			}
		}
		if (!$('.edit-allot .sure').hasClass('hidden')) {
			$('.edit-allot .sure').addClass('hidden')
		}
		$('.edit-allot .remark').attr('readonly', false);
		// 加入初始商品列表
		let list = template('productList', productList);
		$('#product_list').html(list);
		layui.form.render('select','editDefaultValue');
	}, 
	function (err) {
		layer.msg(err.msg, {offset: '100px'});
	}, 'POST')
})

// 调拨单模态框弹出事件
$('#editAllot').on('shown.bs.modal', function () {
	
})

// 调拨单模态框关闭事件 清除一些数据
$('#editAllot').on('hide.bs.modal', function () {
	productList.list = []
	productList.can = undefined;
	productList.status = '';
	productList.id = '';
	productList.put = '';
	let list = template('productList', productList);
	$('#product_list').html(list);
	clearAllot();
})

// 点击商品名称(focus)弹出商品列表
$(document).on('focus','#product_list tr .s-search', function () {
	if (is_show) {
		if (!$('.edit-allot .choosef-shop').val()) {
			layer.msg('请先选择从哪个仓库调拨商品~', {offset: '100px'});
			return false;
		}
	}
	// 先判断扫码枪是否开启
	if ($('#editAllot .set-scan').hasClass('active')) {

	} else if (!$(this).val()) {
		if (f_focus) {
			return false;
		}
		f_focus = true
		// 弹出选择商品列表框
		$('#editProduct').modal();
		_fnSearchProduct(1, '/Allot/getPage');
	}
})

$('.search-product').on('click', function () {
	_fnSearchProduct(1, '/Allot/getPage');
})

// 激活扫码枪
$(document).on('click', '#editAllot .set-scan', function () {
	if (is_show) {
		if (!$('.edit-allot .choosef-shop').val()) {
			layer.msg('请先选择从哪个仓库调拨商品~', {offset: '100px'});
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
$(document).on('keyup', '.edit-allot #product_list tbody tr .s-search', function (e) {
	let that = this;
	if (!$(that).val()) {
		layer.msg('条码不能为空~', {offset: '100px'})
		$(that).focus();
		return
	}
	if (e.keyCode == 13) {
		$ajax('/Allot/get_goods_by_code', {bar_code: $(that).val(), shop_id: $('.edit-allot .choosef-shop').val()}, function (rel) {
			console.log(rel);
			let flag = false;
			for (let i=0, len=productList.list.length; i<len; i++) {
				let v = productList.list[i];
				if (rel.item_id === v.item_id) {
					if (rel.stock === 0 || v.num >= rel.stock) {   // 当前这个商品库存为0
						flag = true
						layer.msg('调拨数量不能大于当前商品库存~', {offset: '100px'})
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
			$('#product_list tbody tr:last-child').find('.s-search').focus();

		}, function (err) {
			layer.msg('没有找到相关商品~', {offset: '100px'});
			$(that).val('');
			$(that).focus();
		}, 'POST')
	}
}) 

// 添加商品到调拨单 默认数量为1 重复商品数量++ 和库存比较
$(document).on('click', '#editProduct #add_product', function () {
	let other_arr = [];
	if (productList.list.length === 0) {
		$('#productItems').find('input[type="checkbox"]:checked').each(function (i, v) {
			let curr = productItems.list[$(v).parent().parent().data('index')];
			if (curr) {
				curr.num = 1;
				productList.list.push(curr)
			}
		})
	} else {
		$('#productItems').find('input[type="checkbox"]:checked').each(function (k, v) {
			let flag = false;
			let curr = productItems.list[$(v).parent().parent().data('index')];
			if (curr) {
				for (let i=0, len=productList.list.length; i<len; i++) {
					let v = productList.list[i];
					if (!v.item_id) {
						productList.list.splice(i, 1);
						continue;
					}
					if (v.bar_code === curr.bar_code) {
						if (v.num < curr.stock) {
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
			}
		})
		productList.list = productList.list.concat(other_arr);
	}
	if ($('#productItems').find('input[type="checkbox"]:checked').length) {
		productList.list.push({});            // 添加商品的同时增加空的一行
	}
	_fnSetChangeValue();
	// 主动关闭模态框
	$('#editProduct').modal('hide')
})

// 调拨单详情中的添加
$(document).on('click', '.edit-allot table .t-calc .add', function () {
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
		if ($('#editAllot .set-scan').hasClass('active')) {
			$('#product_list tr:last-child').find('.s-search').focus();
		}
	}
})

// 添加商品或者改变商品设置单据 进价 成本 判断库存和数量 如果加入超出 默认最大库存
let _fnSetChangeValue = function (index) {
	let s_value = 0, c_cost = 0;
	for (let i=0, len=productList.list.length; i<len; i++) {
		let v = productList.list[i];
		if (v.item_id) {
			s_value = s_value + v.num * (v.md_price - 0);
			c_cost = c_cost + v.num * (v.store_cost - 0);
		}
	}
	$('.edit-allot .bid-amount').val(s_value.toFixed(2));
	$('.edit-allot .real-amount').val(c_cost.toFixed(2));

	if (index !== undefined) {  // 只修改当前那条数据
		let tr = $('.edit-allot table tbody').find('tr[data-index='+ index +']');
		$(tr).find('.md-price').val(productList.list[index].num * productList.list[index].md_price);
		$(tr).find('.store-cost').val(productList.list[index].num * productList.list[index].store_cost);
	} else {   // 整个添加
		let list = template('productList', productList);
		let flag = false;
		if ($('#editAllot .set-scan').hasClass('active')) {
			flag = true;
		}
		$('#product_list').html(list);
		if (flag) {
			$('#editAllot .set-scan').addClass('active')
		}
	}
}

// 删除
$(document).on('click', '.edit-allot table .t-calc .remove', function () {
	productList.list.splice($(this).parent().parent().parent().data('index'), 1);
	_fnSetChangeValue();
	if ($('#editAllot .set-scan').hasClass('active')) {
		$('#product_list tbody tr:last-child').find('.s-search').focus();
	}
})
// 修改数量
$(document).on('keyup', '.edit-allot table .s-num', function () {
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
		if ($(this).val() - 0 > productList.list[index].stock) {
			productList.list[index].num = 1;
			$(this).val(1);
			layer.msg('调拨数量不能大于当前商品库存~', {offset: '100px'});
			setTimeout(function() {
			    $(this).focus();
			}, 1000)
		} else {
			productList.list[index].num = $(this).val();
			// 改变了数量 手动修改其它相关的值
		}
		_fnSetChangeValue(index);
		setTimeout(function() {
		    $(this).focus();
		}, 1000)
	}
})

$(document).on('blur', '.edit-allot table .s-num', function () {
	if (!$(this).val()) {
		_fnSetChangeValue();
	}
})

// 修改备注
$(document).on('keyup', '.edit-allot table .s-remark', function () {
	let index = $(this).parent().parent().parent().data('index');
	productList.list[index].remark = $(this).val();
})

// 添加/保存调拨单
$('#add_allot').on('click', function () {
	let data = {};
	// 判断条件                                                                                       
	if (!$('.choosef-shop').val()) {
		layer.msg('请选择调出仓库~', {offset: '100px'})
		return;
	} else {
		data.from_shop = $('.choosef-shop').val()
	}
	if (!$('.chooset-shop').val()) {
		layer.msg('请选择调入仓库~', {offset: '100px'})
		return;
	} else {
		data.to_shop = $('.chooset-shop').val()
	}
	// 调入和调出不能是同一个仓库
	if (data.from_shop === data.to_shop) {
		layer.msg('调出仓库和调入仓库不能是同一个仓库~', {offset: '100px'});
		return;
	}
	if (productList.list.length === 0 || !productList.list[0].item_id) {
		layer.msg('请添加需调拨的商品~', {offset: '100px'})
		return;
	} else {
		let arr = [];
		for (let i=0, len=productList.list.length; i<len; i++) {
			let d = {};
			let v = productList.list[i];
			if (v.item_id) {
				d.item_id = v.item_id;
				d.num = v.num;
				d.now_md_univalent = v.md_price;
				d.now_store_cost = v.store_cost;
				d.remark = v.remark || '';
			}
			arr.push(d);
		}
		data.item_list = arr;
	}
	data.sn = $('.edit-sn').val();
	data.remark = $('.remark').val();
	data.id = productList.id || '';                   // 调拨单id
	$ajax('/Allot/save', data, function (rel) {
		console.log(rel);
		$('#editAllot').modal('hide');
		// 重刷采购单列表
		getAllotList(1);
	}, function (err) {
		if (err.code === 302) {  // 库存发生变化
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
							d.md_price = v.md_price;
							d.store_cost = v.store_cost;
						}
					}
				}
				if (flag) {
					productList.list.splice(i, 1);
					i--;
				}
			}
			_fnSetChangeValue();
		}
		layer.msg(err.msg, {offset: '100px'})
	}, 'POST')
})

// 点击确定入库
$(document).on('click', '#editAllot #sure_allot', function () {
	$ajax('/Allot/allot_confirm', {allot_id: productList.id}, function (rel) {
		console.log(rel);
		$('#editAllot').modal('hide');
		// 重刷采购单列表
		getAllotList(1);
	}, function (err) {
		layer.msg(err.msg, {offset: '100px'})
	}, 'POST')
})

// 编辑调拨单
$(document).on('click', '.invertory .i-content .t-calc .edit', function () {
	let that = this;
	$ajax('/Allot/editOrView', { allot_id: $(that).parent().parent().parent().data('id') }, function (rel) {
		console.log(rel);
		// 回显采购单基本信息
		//权限
		productList.status = rel.allot.allotInfo.status;
		productList.can = $(that).parent().parent().parent().data('can') - 0;
		productList.id = rel.allot.allotInfo.id;
		let put = $(that).data('put');
		productList.put = put;
		$('.edit-allot .from-people').val(rel.allot.allotInfo
.outor);
		$('.edit-allot .to-people').val(rel.allot.allotInfo
.iner);
		$('.edit-allot .top .edit-sn').val(rel.allot.allotInfo.sn);
		if ($('.edittime').hasClass('hidden')) {
			$('.edittime').removeClass('hidden')
		}
		if (!is_show) {
			if (!$('.bidamount').hasClass('hidden')) {
				$('.bidamount').addClass('hidden')
			}
			if (!$('.realamount').hasClass('hidden')) {
				$('.realamount').addClass('hidden')
			}
		} else {
			if ($('.bidamount').hasClass('hidden')) {
				$('.bidamount').removeClass('hidden')
			}
			if ($('.realamount').hasClass('hidden')) {
				$('.realamount').removeClass('hidden')
			}
		}
		if (productList.can && !productList.status && !put) {
			$('.edit-allot .remark').attr('readonly', false)
		} else {
			$('.edit-allot .remark').attr('readonly', true)
		}
		$('.edit-allot .top .edit-time').val(changeTime(rel.allot.allotInfo.time * 1000));
		// 编辑的售后调出仓库不能变
		let fshop = '';
		fshop = `<option value="`+rel.allot.allotInfo.from_shop+`" selected>`+ searchNameByid(rel.allot.allotInfo.from_shop, rel.toShopDatas) +`</option>` + fshop;
		$('.choosef-shop').html(fshop);
		let toshop = '';
		if (!is_show) {
			if (productList.can !== 0  && !productList.status) {
				$('.edit-allot .remark').attr('readonly', false);
				if ($('#add_allot').hasClass('hidden')) {
					$('#add_allot').removeClass('hidden')
				}
				if (!$('#sure_allot').hasClass('hidden')) {
					$('#sure_allot').addClass('hidden')
				}
				for (let i = 0, len = rel.toShopDatas.length; i<len; i++) {
					let v = rel.toShopDatas[i];
					if (v.id === rel.allot.allotInfo.to_shop) {
						toshop += `<option value=${v.id} selected>${v.name}</option>`
					} else {
						toshop += `<option value=${v.id}>${v.name}</option>`
					}
				}
				toshop = `<option value="">请选择仓库</option>` + toshop;
				// toshop = `<option value="`+ searchNameByName('总店', rel.toShopDatas) +`">总店</option>` + toshop;
			} else {
				$('.edit-allot .remark').attr('readonly', true);
				if (!$('#add_allot').hasClass('hidden')) {
					$('#add_allot').addClass('hidden')
				}
				if ($('#sure_allot').hasClass('hidden') && !productList.status) {
					$('#sure_allot').removeClass('hidden')
				}
				toshop = `<option value="`+ rel.allot.allotInfo.to_shop +`">`+ searchNameByid(rel.allot.allotInfo.to_shop, rel.toShopDatas) +`</option>` + toshop;
			}
		} else {
			if (!productList.status && !put) {
				$('.edit-allot .remark').attr('readonly', false);
			} else {
				$('.edit-allot .remark').attr('readonly', true);
			}
			if (put || productList.status) {
				toshop = `<option value="`+ rel.allot.allotInfo.to_shop +`">`+ searchNameByid(rel.allot.allotInfo.to_shop, rel.toShopDatas) +`</option>` + toshop;
				if ($('#sure_allot').hasClass('hidden') && !productList.status) {
					$('#sure_allot').removeClass('hidden')
				} else {
					$('#sure_allot').addClass('hidden')
				}
				if (!$('#add_allot').hasClass('hidden')) {
					$('#add_allot').addClass('hidden')
				}
				if (put) {
					if ($('#sure_allot').hasClass('hidden')) {
						$('#sure_allot').removeClass('hidden')
					}
				}
			} else {
				if (!$('#sure_allot').hasClass('hidden')) {
					$('#sure_allot').addClass('hidden')
				}
				if ($('#add_allot').hasClass('hidden')  && !productList.status) {
					$('#add_allot').removeClass('hidden')
				}
				for (let i = 0, len = rel.toShopDatas.length; i<len; i++) {
					let v = rel.toShopDatas[i];
					if (v.id === rel.allot.allotInfo.to_shop) {
						toshop += `<option value=${v.id} selected>${v.name}</option>`
					} else {
						toshop += `<option value=${v.id}>${v.name}</option>`
					}
				}
				toshop = `<option value="">请选择仓库</option>` + toshop;
			}
		}
		if (productList.status) {
			if ($('.sure').hasClass('hidden')) {
				$('.sure').removeClass('hidden')
			}
		} else {
			if (!$('.sure').hasClass('hidden')) {
				$('.sure').addClass('hidden')
			}
		}
		// 加入仓库数据
		$('.chooset-shop').html(toshop);
		$('.edit-allot .real-amount').val(rel.allot.allotInfo.cost);
		$('.edit-allot .bid-amount').val(rel.allot.allotInfo.bid_amount);
		$('.edit-allot .remark').val(rel.allot.allotInfo.remark);
		// 加入初始商品列表
		productList.list = rel.allot.allotItemInfo;
		_fnSetChangeValue();
		layui.form.render('select','editDefaultValue');
	}, function (err) {
		layer.msg(err.msg, {offset: '100px'})
	}, 'POST')
})

// 删除调拨单
$(document).on('click', '.invertory .i-content .t-calc .remove', function () {
	let that = this;
	layer.open({
	  content: '确定要删除当前调拨单~',
	  yes: function(index, layero){
		$ajax('/Allot/delete', { allot_id: $(that).parent().parent().parent().data('id') }, function (rel) {
				console.log(rel);
				getAllotList(1);
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
	$('.from-shop').html(`<option value="">调出仓库选择</option>` + shop);
	if (!is_show) {
		shop = `<option value="">调入仓库选择</option><option value=18>总店</option>`
		$('.to-shop').html(shop);
	} else {
		$('.to-shop').html(`<option value="">调入仓库选择</option>` + shop);
	}
	getAllotList(1);
}
_fnInitDatas(_fnInit);
