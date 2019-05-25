let f_focus = false;
let putPurchaseList = {    // 采购单入库商品列表
	list: []
}

// 获取分页数据
var getPurchaseList = function (page) {
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
	$ajax('/Purchase/get_purchase_list', data,
		function (rel) {
			console.log(rel);
			// 加入总数据
			let summary = `
				<div>
					<span>单据总笔数：</span>
					<span>${rel.totalNum}</span>
				</div>
			`;
			if (rel.is_show) {
				summary = summary + `
					<div>
						<span>单据总金额：</span>
						<span>${rel.purchase_amount_all}</span>
					</div>
					<div>
						<span>进价总金额：</span>
						<span>${rel.purchase_bid_amount_all}</span>
					</div>
					<div>
						<span>实际总金额：</span>
						<span>${rel.real_amount_all}</span>
					</div>
				`
			}
			$('.summary').html(summary);
			layui.form.render('select','searchHeader');
			// 根据仓库id 和供应商id 换取名字
			for (let i=0, len=rel.purchase_list.length; i<len; i++) {
				rel.purchase_list[i].time = changeTime(rel.purchase_list[i].time * 1000);
				if (rel.purchase_list[i].store_time) {
					rel.purchase_list[i].store_time = changeTime(rel.purchase_list[i].store_time * 1000);
				}
				rel.purchase_list[i].shopname = searchNameByid(rel.purchase_list[i].shop_id, rel.shop_list);
				rel.purchase_list[i].suppername = searchNameByid(rel.purchase_list[i].supplier_id, rel.supplier_list);
			}
			invertoryList.list = rel.purchase_list;
			let list = template('purchaseList', invertoryList);
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
				    		getPurchaseList(obj.curr);
				    	}
				    }
				})
			}
			if (rel.purchase_list.length === 0 && page === 1) {
				invertoryList.list = [];
				let list = template('purchaseList', invertoryList);
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
			invertoryList.show = '';
			let list = template('purchaseList', invertoryList);
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

// 根据条件查询采购单
$('.search-purchase').on('click', function () {
	getPurchaseList(1);
})

// 清除或者赋值采购模态框
let clearPurchase = function () {
	$('#editPurchase table tbody').html('');
	$('.edit-purchase .list .detail .amount').val('');
	$('.edit-purchase .list .detail .bid-amount').val('');
	$('.edit-purchase .list .detail .bid-amount').val('');
	$('.edit-purchase .list .detail .admin-name').val('');
	$('.edit-purchase .list .detail .shopcalc-name').val('');
	$('.edit-purchase .list .detail .remark').val('');
	$('.edit-purchase .list .detail .edit-sn').val('');
	$('.edit-time').val('');
	$('.edit-shop').val('');
	$('.edit-supplier').val('');
	$('.real-amount').val('');
	if ($('.set-scan').hasClass('active')) {
		$('.set-scan').removeClass('active')
	}
}

// 新增采购单 弹出模态框
$('.add-purchase').click(function () {
	// 获取基本信息
	$ajax('/Purchase/add', {},
	function(rel) {
		console.log(rel);
		productList.id = '';
		productList.status = 0;
		$('.edit-purchase .real-amount').attr('readonly', false);
		$('.edit-purchase .remark').attr('readonly', false);
		if ($('.edit-purchase .btn-default-border').hasClass('hidden')) {
			$('.edit-purchase .btn-default-border').removeClass('hidden');
		}
		$('.edit-purchase .list .detail .admin-name').val(rel.admin_name);
		$('.edit-purchase .top .edit-sn').val(rel.sn);
		let shop = '';
		// 加入仓库数据
		for (let i = 0, len = shop_list.length; i<len; i++) {
			let v = shop_list[i]
			shop += `<option value=${v.id}>${v.name}</option>`
		}
		shop = `<option value="">请选择仓库</option>` + shop;
		$('.edit-shop').html(shop);
		// 加入供应商数据
		let supper = '';
		for (let i = 0, len = supplier_list.length; i<len; i++) {
			let v = supplier_list[i]
			supper += `<option value=${v.id}>${v.name}</option>`
		}
		supper = `<option value="">请选择供应商</option>` + supper;
		$('.edit-supplier').html(supper);
		// 加入初始商品列表
		let list = template('productList', productList);
		$('#product_list').html(list);
		if (!$('.edittime').hasClass('hidden')) {
			$('.edittime').addClass('hidden');
		}
		if (is_show) {
			if ($('.showamount').hasClass('hidden')) {
				$('.showamount').removeClass('hidden')
			}
			if ($('.showbid').hasClass('hidden')) {
				$('.showbid').removeClass('hidden')
			}
			if ($('.showreal').hasClass('hidden')) {
				$('.showreal').removeClass('hidden')
			}
		} else {
			if (!$('.showamount').hasClass('hidden')) {
				$('.showamount').addClass('hidden')
			}
			if (!$('.showbid').hasClass('hidden')) {
				$('.showbid').addClass('hidden')
			}
			if (!$('.showreal').hasClass('hidden')) {
				$('.showreal').addClass('hidden')
			}
		}
		layui.form.render('select','editDefaultValue');
	}, 
	function (err) {
		layer.msg(err.msg, {offset: '100px'});
	}, 'POST')
})

// 采购单模态框弹出事件
$('#editPurchase').on('shown.bs.modal', function () {
	
})

// 采购单模态框关闭事件 清除一些数据
$('#editPurchase').on('hide.bs.modal', function () {
	productList.list = []
	let list = template('productList', productList);
	$('#product_list').html(list);
	clearPurchase();
})

// 激活扫码枪
$(document).on('click', '#editPurchase .set-scan', function () {
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
			$('#product_list tr:last-child').find('.s-search').focus();
		}
	}
})

//扫码枪得到二维码
$(document).on('keyup', '.edit-purchase #product_list tr .s-search', function (e) {
	let that = this;
	if (e.keyCode == 13) {
		$ajax('/Purchase/get_goods_by_code', {bar_code: $(that).val()}, function (rel) {
			console.log(rel);
			let flag = false;
			for (let i=0, len=productList.list.length; i<len; i++) {
				let v = productList.list[i];
				if (rel.item_info.bar_code === v.bar_code) {
					v.num++;
					flag = true;
					break;
				}
			}
			if (!flag) {
				rel.item_info.num = 1;
				productList.list.splice(productList.list.length-1, 1);
				productList.list.push(rel.item_info);
				productList.list.push({});
			}
			_fnSetChangeValue();
			layer.msg('添加成功~', {offset: '100px'});
			// 设置最后一个tr focus
			$('#product_list tr:last-child').find('.s-search').focus();

		}, function (err) {
			layer.msg('没有找到相关商品~', {offset: '100px'});
			$(that).val('');
			$(that).focus();
		}, 'POST')
	}
}) 

// 点击商品名称(focus)弹出商品列表
$(document).on('focus','#product_list tbody tr .s-search', function () {
	// 先判断扫码枪是否开启
	if ($('#editPurchase .set-scan').hasClass('active')) {

	} else if (!$(this).val()) {
		if (f_focus) {
			return false;
		}
		f_focus = true
		// 弹出选择商品列表框
		$('#editProduct').modal();
		_fnSearchProduct(1, '/member_goods/getPage');
	}
})

$('.search-product').on('click', function () {
	_fnSearchProduct(1, '/member_goods/getPage');
})

// 添加商品到采购单 默认数量为1 重复商品数量++
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
					v.num++;
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

// 采购单详情中的添加
$(document).on('click', '.edit-purchase table .t-calc .add', function () {
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
let _fnSetChangeValue = function (index, cost) {   // 当前改变的数组第几个 cost 修改成本
	let s_value = 0, bid_value = 0;
	for (let i=0, len=productList.list.length; i<len; i++) {
		let v = productList.list[i];
		if (v.item_id) {
			s_value = s_value + v.num * v.cg_standard_price;
			bid_value = bid_value + v.num * v.md_standard_price;
		}
	}
	$('.edit-purchase .amount').val(s_value.toFixed(2));
	$('.edit-purchase .bid-amount').val(bid_value.toFixed(2));

	if (!cost) {
		if ($('.real-amount').val()) {
			$('.real-amount').val('');
		}
	}

	for (let i=0, len=productList.list.length; i<len; i++) {
		let v = productList.list[i];
		if ((!index && index!==0) && v.item_id) {
			if ($('.real-amount').val()) {
				if (s_value) {
					v.cost = (($('.real-amount').val() - 0) * ((v.num * (v.cg_standard_price - 0)) / s_value)).toFixed(2);
				} else {
					v.cost = 0;
				}
			} else {
				v.cost = '';
			}
		}
	}
	if (index || index === 0) {  // 只修改当前那条数据
		let tr = $('.edit-purchase table tbody').find('tr[data-index='+ index +']');
		$(tr).find('.s-amount').val(productList.list[index].num * productList.list[index].cg_standard_price - 0);
		$(tr).find('.s-mdamount').val(productList.list[index].num * productList.list[index].md_standard_price - 0);
		if ($('.real-amount').val()) {
			if (s_value) {
				$(tr).find('.s-cost').val(($('.real-amount').val() * ((productList.list[index].num * productList.list[index].cg_standard_price) / s_value)).toFixed(2));
			} else {
				$(tr).find('.s-cost').val(0);
			}
		} else {
			$(tr).find('.s-cost').val('');
		}
	} else {   // 整个添加
		let list = template('productList', productList);
		$('#product_list').html(list);
	}
}

// 删除
$(document).on('click', '.edit-purchase table .t-calc .remove', function () {
	productList.list.splice($(this).parent().parent().parent().data('index'), 1);
	_fnSetChangeValue();
	if ($('#editPurchase .set-scan').hasClass('active')) {
		$('#product_list tr:last-child').find('.s-search').focus();
	}
})

// 修改数量
$(document).on('keyup', '.edit-purchase table .s-num', function () {
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
		productList.list[index].num = $(this).val();
		// 改变了数量 手动修改其它相关的值
		_fnSetChangeValue(index);
		setTimeout(function() {
		    $(this).focus();
		}, 1000)
	}
})

$(document).on('blur', '.edit-purchase table .s-num', function () {
	if (!$(this).val()) {
		_fnSetChangeValue();
	}
})

// 修改采购单价
$(document).on('keyup', '.edit-purchase table .s-single', function () {
	$(this).val($(this).val().replace(/[^\d\.]/g,''));
	let index = $(this).parent().parent().parent().data('index');
	if (!$(this).val()) {
		layer.msg('请输入数字~', {offset: '100px'});
		setTimeout(function() {
		    $(this).focus();
		}, 1000)
	} else {
		if (productList.list[index].cg_standard_price) {
			productList.list[index].cg_standard_price = $(this).val();
		} else {
			productList.list[index].cg_amount = $(this).val();
		}
		// 改变了数量 手动修改其它相关的值
		_fnSetChangeValue(index);
		setTimeout(function() {
		    $(this).focus();
		}, 1000)
	}
})

$(document).on('blur', '.edit-purchase table .s-single', function () {
	if (!$(this).val()) {
		_fnSetChangeValue();
	} else if ($(this).val()[$(this).val().length-1] === '.') {
		$(this).val($(this).val().replace('.', ''))
	}
})

// 修改门店单价
$(document).on('keyup', '.edit-purchase table .s-mdsingle', function () {
	$(this).val($(this).val().replace(/[^\d\.]/g,''));
	let index = $(this).parent().parent().parent().data('index');
	if (!$(this).val()) {
		layer.msg('请输入数字~', {offset: '100px'});
		setTimeout(function() {
		    $(this).focus();
		}, 1000)
	} else {
		if (productList.list[index].md_standard_price) {
			productList.list[index].md_standard_price = $(this).val();
		} else {
			productList.list[index].md_univalent = $(this).val();
		}
		// 改变了数量 手动修改其它相关的值
		_fnSetChangeValue(index);
		setTimeout(function() {
		    $(this).focus();
		}, 1000)
	}
})

// 修改备注
$(document).on('keyup', '.edit-purchase table .s-remark', function () {
	let index = $(this).parent().parent().parent().data('index');
	productList.list[index].remark = $(this).val();
})

$(document).on('blur', '.edit-purchase table .s-mdsingle', function () {
	if (!$(this).val()) {
		_fnSetChangeValue();
	} else if ($(this).val()[$(this).val().length-1] === '.') {
		$(this).val($(this).val().replace('.', ''))
	}
})

// 修改实际金额
$(document).on('keyup', '.edit-purchase .real-amount', function () {
	$(this).val($(this).val().replace(/[^\d\.]/g,''));
	if (!$(this).val()) {
		layer.msg('请输入数字~', {offset: '100px'});
		_fnSetChangeValue('', true);
		setTimeout(function() {
		    $(this).focus();
		}, 1000)
	} else {
		_fnSetChangeValue('', true);
		setTimeout(function() {
		    $(this).focus();
		}, 1000)
	}
})

$(document).on('blur', '.edit-purchase .real-amount', function () {
	if (!$(this).val()) {
		_fnSetChangeValue('', true);
	} else if ($(this).val()[$(this).val().length-1] === '.') {
		$(this).val($(this).val().replace('.', ''))
	}
})

// 添加/保存采购单
$('#add_purchase').on('click', function () {
	if (productList.status) {
		$('#editPurchase').modal('hide');
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
		layer.msg('请选择所入仓库~', {offset: '100px'})
		return;
	} else {
		data.shop_id = $('.edit-shop').val()
	}
	if (productList.list.length === 0 || !productList.list[0].item_id) {
		layer.msg('请添加需采购的商品~', {offset: '100px'})
		return;
	} else {
		if (!$('.real-amount').val()) {
			layer.msg('请添加当前采购单的实际金额~', {offset: '100px'})
			return;
		} else {
			data.real_amount = $('.real-amount').val()
		}
		let arr = [];
		for (let i=0, len=productList.list.length; i<len; i++) {
			let d = {};
			let v = productList.list[i];
			if (v.item_id) {
				d.item_id = v.item_id;
				d.num = v.num;
				d.cg_univalent = v.cg_standard_price;
				d.md_univalent = v.md_standard_price;
				d.md_amount = v.num * v.md_standard_price;
				d.remark = v.remark || '';
			}
			arr.push(d);
		}
		data.item_list = arr;
		data.amount = $('.amount').val();
		data.bid_amount = $('.bid-amount').val();
	}
	data.sn = $('.edit-sn').val();
	data.remark = $('.remark').val();
	data.id = productList.id;                   // 采购单id
	$ajax('/Purchase/save', data, function (rel) {
		console.log(rel);
		$('#editPurchase').modal('hide');
		// 重刷采购单列表
		getPurchaseList(1);
	}, function (err) {
		layer.msg(err.msg, {offset: '100px'})
	}, 'POST')
})

// 查看采购单
$(document).on('click', '.invertory .i-content .t-calc .scan', function () {

})

// 入库采购单
$(document).on('click', '.invertory .i-content .t-calc .put', function () {
	$ajax('/Purchase/before_purchase', { id: $(this).parent().parent().parent().data('id') }, function (rel) {
		console.log(rel);
		putPurchaseList.list = rel.item_list;
		putPurchaseList.purchase_id = rel.purchase_id;
		// 回显所入仓库
		$('#putPurchase .put-shop').val(searchNameByid(rel.shop_id, shop_list));
		$('#putPurchase .put-shop').data('id', rel.shop_id);
		$('#putPurchase .put-time').val(rel.time);
		$('#putPurchase .put-admin').val(rel.purchaser);
		$('#putPurchase .put-shopcalc').val(rel.storer);
		$('#putPurchase .put-remark').val(rel.remark);
		let list = template('putProductList', putPurchaseList);
		$('#putPurchase #putProductItems').html(list);
	}, function (err) {
		layer.msg(err.msg, {offset: '100px'})
	}, 'POST')
})

// 入库采购单模态框关闭
$('#putPurchase').on('hide.bs.modal', function () {
	putPurchaseList.list = [];
	putPurchaseList.purchase_id = '';
	let data = template('putProductList', putPurchaseList);
	$('#putPurchase #putProductItems').html(data);
	$('#putPurchase .put-shop').val('');
	$('#putPurchase .put-shop').data('id', '');
	$('#putPurchase .put-time').val('');
	$('#putPurchase .put-admin').val('');
	$('#putPurchase .put-shopcalc').val('');
})

// 点击确定入库
$(document).on('click', '#putPurchase #put_purchase', function () {
	// 需判断当前入库数量和单子数量是否相等
	let flag = false;
	$('#putPurchase #putProductItems tr').each(function (i, v) {
		if (!$(v).find('.curr-num').val()) {
			layer.msg('请添加入库数量~', {offset: '100px'});
			$(v).find('.curr-num').focus();
			flag = true
		}
		if ($(v).find('.curr-num').val() != putPurchaseList.list[i].num) {
			layer.msg('请核实入库数量~', {offset: '100px'});
			$(v).find('.curr-num').focus();
			flag = true
		}
		if (flag) {
			return false;
		}
	})
	if (!flag) {
		let data = {};
		data.shop_id = $('#putPurchase .put-shop').data('id');
		data.purchase_id = putPurchaseList.purchase_id;
		// data.item_info = putPurchaseList.list;
		$ajax('/Purchase/purchase_save', data, function (rel) {
			console.log(rel);
			$('#putPurchase').modal('hide');
			getPurchaseList(1);
		}, function (err) {
			layer.msg(err.msg, {offset: '100px'})
		}, 'POST')
	}
})

// 编辑采购单
$(document).on('click', '.invertory .i-content .t-calc .edit', function () {
	$ajax('/Purchase/editOrView', { purchase_id: $(this).parent().parent().parent().data('id') }, function (rel) {
		console.log(rel);
		productList.id = rel.purchaseInfo.id;
		productList.status = rel.purchaseInfo.status;
		// 回显采购单基本信息
		$('.edit-purchase .admin-name').val(rel.purchaseInfo
.creator);
		$('.edit-purchase .top .edit-sn').val(rel.purchaseInfo.sn);
		if ($('.edittime').hasClass('hidden')) {
			$('.edittime').removeClass('hidden');
		}
		$('.edit-purchase .shopcalc-name').val(rel.purchaseInfo.iner);
		$('.edit-time').val(changeTime(rel.purchaseInfo.time * 1000));
		let shop = '';
		// 加入仓库数据
		if (rel.purchaseInfo.status) {
			$('.edit-purchase .remark').attr('readonly', true);
			shop = `<option value="`+ rel.purchaseInfo.shop_id +`">`+ searchNameByid(rel.purchaseInfo.shop_id, shop_list) +`</option>` + shop;
		} else {
			$('.edit-purchase .remark').attr('readonly', false);
			for (let i = 0, len = rel.shopDatas.length; i<len; i++) {
				let v = rel.shopDatas[i];
				// 回显选中的
				if (v.id === rel.purchaseInfo.shop_id) {
					shop += `<option value=${v.id} selected>${v.name}</option>`
				} else {
					shop += `<option value=${v.id}>${v.name}</option>`		
				}
			}
			shop = `<option value="">请选择仓库</option>` + shop;
		}
		$('.edit-shop').html(shop);
		// 加入供应商数据
		let supper = '';
		if (rel.purchaseInfo.status) {
			$('.edit-purchase .real-amount').attr('readonly', true);
			$('.edit-purchase .btn-default-border').addClass('hidden');
		} else {
			$('.edit-purchase .real-amount').attr('readonly', false);
			$('.edit-purchase .btn-default-border').removeClass('hidden');
		}
		if (rel.purchaseInfo.status) {
			supper = `<option value="`+ rel.purchaseInfo.supplier_id +`">`+ searchNameByid(rel.purchaseInfo.supplier_id, supplier_list) +`</option>` + supper;
		} else {
			for (let i = 0, len = rel.supplierDatas.length; i<len; i++) {
				let v = rel.supplierDatas[i];
				if (v.id === rel.purchaseInfo.supplier_id) {
					supper += `<option value=${v.id} selected>${v.name}</option>`
				} else {
					supper += `<option value=${v.id}>${v.name}</option>`
				}
			}
			supper = `<option value="">请选择供应商</option>` + supper;
		}
		$('.edit-supplier').html(supper);
		// $('.edit-purchase .amount').val(rel.purchaseInfo.amount);
		// $('.edit-purchase .bid-amount').val(rel.purchaseInfo.bid_amount);
		$('.edit-purchase .real-amount').val(rel.purchaseInfo.real_amount);
		$('.edit-purchase .remark').val(rel.purchaseInfo.remark);
		if (is_show) {
			if ($('.showamount').hasClass('hidden')) {
				$('.showamount').removeClass('hidden')
			}
			if ($('.showbid').hasClass('hidden')) {
				$('.showbid').removeClass('hidden')
			}
			if ($('.showreal').hasClass('hidden')) {
				$('.showreal').removeClass('hidden')
			}
		} else {
			if (!$('.showamount').hasClass('hidden')) {
				$('.showamount').addClass('hidden')
			}
			if (!$('.showbid').hasClass('hidden')) {
				$('.showbid').addClass('hidden')
			}
			if (!$('.showreal').hasClass('hidden')) {
				$('.showreal').addClass('hidden')
			}
		}
		// 加入初始商品列表
		productList.list = rel.item_list;
		_fnSetChangeValue('', true);
		layui.form.render('select','editDefaultValue');
	}, function (err) {
		layer.msg(err.msg, {offset: '100px'})
	}, 'POST')
})

// 删除采购单
$(document).on('click', '.invertory .i-content .t-calc .remove', function () {
	let that = this;
	layer.open({
	  content: '确定要删除当前采购单~',
	  yes: function(index, layero){
		$ajax('/Purchase/delete', { purchase_id: $(that).parent().parent().parent().data('id') }, function (rel) {
				console.log(rel);
				getPurchaseList(1);
				layer.msg('删除成功~', {offset: '100px'})
			}, function (err) {
				layer.msg(err.msg, {offset: '100px'})
			}, 'POST')
	    }
	});
})

// 反入库 采购单入库之后的删除
$(document).on('click', '.invertory .i-content .t-calc .return', function () {
	let that = this;
	layer.open({
	  content: '当前操作会影响成本变化，是否继续操作~',
	  yes: function(index, layero){
		$ajax('/Purchase/reverse_purchase', { purchase_id: $(that).parent().parent().parent().data('id') }, function (rel) {
				console.log(rel);
				getPurchaseList(1);
				layer.msg('反入库成功~', {offset: '100px'})
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
	shop = `<option value="">选择仓库</option>` + shop;
	$('.choose-shop').html(shop);
	// 加入供应商数据
	let supper = '';
	for (let i = 0, len = supplier_list.length; i<len; i++) {
		let v = supplier_list[i]
		supper += `<option value=${v.id}>${v.name}</option>`
	}
	supper = `<option value="">选择供应商</option>` + supper;
	$('.choose-supplier').html(supper);
	// 权限
	if (!is_show) {
		$('.add-purchase').addClass('hidden')
	}
	getPurchaseList(1);
}
_fnInitDatas(_fnInit);
