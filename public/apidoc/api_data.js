define({ "api": [
  {
    "type": "POST",
    "url": "/admin/StockCheck/getStockList",
    "title": "获取商品库存信息",
    "group": "库存查询",
    "name": "getStockList",
    "version": "1.0.0",
    "description": "<p>ajax获取分页商品数据</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "curPage",
            "description": "<p>页数 不可空</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "item_name",
            "description": "<p>商品名称 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "shop_id",
            "description": "<p>所属仓库ID 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>二级分类ID 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "show_zero",
            "description": "<p>是否显示0库存商品 1是 0否</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "bar_code",
            "description": "<p>商品条形码 可空</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.totalPage",
            "description": "<p>总页数</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.totalNum",
            "description": "<p>数据总条数</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.pageSize",
            "description": "<p>每页数据条数</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.sotck_all",
            "description": "<p>总库存</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.md_price_all",
            "description": "<p>门店进价总额</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.store_cost_all",
            "description": "<p>库存总成本</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data.list",
            "description": "<p>商品库存信息列表</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.list.title",
            "description": "<p>商品名</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.list.shop_name",
            "description": "<p>所属仓库</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.list.stock",
            "description": "<p>库存</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.list.num",
            "description": "<p>在途</p>"
          },
          {
            "group": "返回参数",
            "type": "float",
            "optional": false,
            "field": "data.list.store_cost",
            "description": "<p>库存单位成本</p>"
          },
          {
            "group": "返回参数",
            "type": "float",
            "optional": false,
            "field": "data.list.md_price",
            "description": "<p>门店单位成本</p>"
          },
          {
            "group": "返回参数",
            "type": "float",
            "optional": false,
            "field": "data.list.price",
            "description": "<p>售价</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.list.cname",
            "description": "<p>商品分类</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.list.bar_code",
            "description": "<p>条形码</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":{\"shop_list\":[{\"id\":18,\"name\":\"总店\"},{\"id\":6,\"name\":\"爱琴海店\"},{\"id\":5,\"name\":\"留云路店\"},{\"id\":16,\"name\":\"龙湖源著店\"},{\"id\":17,\"name\":\"重庆城口店\"},{\"id\":15,\"name\":\"珠江太阳城店\"},{\"id\":21,\"name\":\"两江时光店\"},{\"id\":22,\"name\":\"测试门店10\"},{\"id\":25,\"name\":\"约克郡南郡\"},{\"id\":24,\"name\":\"约克郡北郡\"},{\"id\":26,\"name\":\"蓝光COCO店\"},{\"id\":29,\"name\":\"融创金茂店\"},{\"id\":28,\"name\":\"港城国际店\"},{\"id\":27,\"name\":\"江与城店\"},{\"id\":30,\"name\":\"麓山别苑店\"},{\"id\":31,\"name\":\"奥山别墅店\"}],\"is_show\":1,\"totalPage\":1,\"list\":[{\"id\":3,\"shop_name\":\"留云路店\",\"title\":\"三鹿三聚氰胺奶粉2段3罐\",\"price\":\"100.00\",\"bar_code\":\"654654\",\"item_id\":2,\"cname\":\"洗发沐浴\",\"num\":null,\"md_price_after\":\"8.0000\",\"store_cost_after\":\"6.0000\"},{\"id\":2,\"shop_name\":\"留云路店\",\"title\":\"惠氏金装婴儿奶粉 3段2罐\",\"price\":\"268.00\",\"bar_code\":\"6615249817\",\"item_id\":3,\"cname\":\"惠氏\",\"num\":null,\"md_price_after\":\"6.0000\",\"store_cost_after\":\"4.0000\"}]}}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/StockCheck/getStockList"
      }
    ],
    "filename": "../../app/admin/controller/StockCheckController.php",
    "groupTitle": "库存查询"
  },
  {
    "type": "POST",
    "url": "/admin/StockCheck/get_price_detail",
    "title": "金额变动",
    "group": "库存查询",
    "name": "get_price_detail",
    "version": "1.0.0",
    "description": "<p>获取商品出入库详细信息</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "shop_id",
            "description": "<p>仓库（门店）ID</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "item_id",
            "description": "<p>商品ID</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "curPage",
            "description": "<p>页码</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "time",
            "description": "<p>出入库时间</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>单据类型 1采购单 2调拨单 5零售退货单 7供应商退货单</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.totalPage",
            "description": "<p>数据总页数</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.totalNum",
            "description": "<p>数据总条数</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.pageSize",
            "description": "<p>每页数据条数</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data.list",
            "description": "<p>商品金额变动详细信息列表</p>"
          },
          {
            "group": "返回参数",
            "type": "float",
            "optional": false,
            "field": "data.list.md_price_after",
            "description": "<p>门店单位进价</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.list.store_cost_after",
            "description": "<p>库存单位成本</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.list.type_name",
            "description": "<p>单据类型</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.list.sn",
            "description": "<p>单据编号</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.list.change_stock",
            "description": "<p>变化数量</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":{\"list\":[{\"time\":0,\"stock\":1,\"type\":1,\"pd_id\":1,\"type_name\":\"采购\",\"sn\":\"CG201807190001\",\"change_stock\":1},{\"time\":0,\"stock\":3,\"type\":1,\"pd_id\":1,\"type_name\":\"采购\",\"sn\":\"CG201807190001\",\"change_stock\":2}]}}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/StockCheck/get_price_detail"
      }
    ],
    "filename": "../../app/admin/controller/StockCheckController.php",
    "groupTitle": "库存查询"
  },
  {
    "type": "POST",
    "url": "/admin/StockCheck/get_stock_detail",
    "title": "出入库",
    "group": "库存查询",
    "name": "get_stock_detail",
    "version": "1.0.0",
    "description": "<p>获取商品出入库详细信息</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "shop_id",
            "description": "<p>仓库（门店）ID</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "item_id",
            "description": "<p>商品ID</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "curPage",
            "description": "<p>页码</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "time",
            "description": "<p>出入库时间</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>单据类型 1采购单 2调拨单 3盘盈单 4盘亏单 5零售退货单 6零售出库单 7供应商退货单</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.totalPage",
            "description": "<p>数据总页数</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.totalNum",
            "description": "<p>数据总条数</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.pageSize",
            "description": "<p>每页数据数</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data.list",
            "description": "<p>商品出入库详细信息列表</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.list.time",
            "description": "<p>操作时间（时间戳）</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.list.type_name",
            "description": "<p>单据类型</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.list.sn",
            "description": "<p>单据编号</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.list.change_stock",
            "description": "<p>变化数量</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":{\"list\":[{\"time\":0,\"stock\":1,\"type\":1,\"pd_id\":1,\"type_name\":\"采购\",\"sn\":\"CG201807190001\",\"change_stock\":1},{\"time\":0,\"stock\":3,\"type\":1,\"pd_id\":1,\"type_name\":\"采购\",\"sn\":\"CG201807190001\",\"change_stock\":2}]}}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/StockCheck/get_stock_detail"
      }
    ],
    "filename": "../../app/admin/controller/StockCheckController.php",
    "groupTitle": "库存查询"
  },
  {
    "type": "POST",
    "url": "/admin/Stock/create_inventory_loss",
    "title": "生成盘亏单",
    "group": "库存盘点",
    "name": "create_inventory_loss",
    "version": "1.0.0",
    "description": "<p>生成盘亏单时获取初始数据</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "shop_id",
            "description": "<p>门店（仓库）id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "msg",
            "description": "<p>请求结果 success成功 fail失败</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果集</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.shop_id",
            "description": "<p>门店（仓库）ID</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.shop_name",
            "description": "<p>门店（仓库）名</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.time",
            "description": "<p>盘亏单日期</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.sn",
            "description": "<p>盘亏单单号</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.creator_name",
            "description": "<p>盘亏单制单人</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.outer_name",
            "description": "<p>盘亏单出库人</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":{\"shop_id\":5,\"shop_name\":\"留云路店\",\"time\":\"2018-08-06\",\"sn\":\"PK201808060001\",\"creator_name\":\"admin\",\"outer_name\":\"--\"}}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/Stock/create_inventory_loss"
      }
    ],
    "filename": "../../app/admin/controller/StockController.php",
    "groupTitle": "库存盘点"
  },
  {
    "type": "POST",
    "url": "/admin/Stock/create_inventory_profit",
    "title": "生成盘盈单",
    "group": "库存盘点",
    "name": "create_inventory_profit",
    "version": "1.0.0",
    "description": "<p>生成盘盈单时获取初始数据</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "shop_id",
            "description": "<p>门店（仓库）id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "msg",
            "description": "<p>请求结果 success成功 fail失败</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果集</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.shop_id",
            "description": "<p>门店（仓库）ID</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.shop_name",
            "description": "<p>门店（仓库）名</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.time",
            "description": "<p>盘盈单日期</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.sn",
            "description": "<p>盘盈单单号</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.creator_name",
            "description": "<p>盘盈单制单人</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.iner_name",
            "description": "<p>盘盈单入库人</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":{\"shop_id\":5,\"shop_name\":\"留云路店\",\"time\":\"2018-08-02\",\"sn\":\"PY201808020001\",\"creator_name\":\"admin\",\"iner_name\":\"--\"}}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/Stock/create_inventory_profit"
      }
    ],
    "filename": "../../app/admin/controller/StockController.php",
    "groupTitle": "库存盘点"
  },
  {
    "type": "POST",
    "url": "/admin/Stock/delete",
    "title": "删除盘盈单/盘亏单",
    "group": "库存盘点",
    "name": "delete",
    "version": "1.0.0",
    "description": "<p>删除盘盈单/盘亏单</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>盘盈单（盘亏单）id</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>单据类型，1为盘盈单，2为盘亏单</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "msg",
            "description": "<p>请求结果 success成功 fail失败</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果信息</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":\"删除成功\"}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/Stock/delete"
      }
    ],
    "filename": "../../app/admin/controller/StockController.php",
    "groupTitle": "库存盘点"
  },
  {
    "type": "POST",
    "url": "/admin/Stock/get_init_data",
    "title": "获取初始数据",
    "group": "库存盘点",
    "name": "get_init_data",
    "version": "1.0.0",
    "description": "<p>库存盘点获取门店列表及权限初始数据</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "null",
            "description": "<p>不需要请求参数</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "msg",
            "description": "<p>请求结果 success成功 fail失败</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果集</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.is_show",
            "description": "<p>是否显示金额相关数据 1是 0否</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data.shop_list",
            "description": "<p>门店（仓库）列表</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.shop_list.id",
            "description": "<p>门店（仓库）ID</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.shop_list.name",
            "description": "<p>门店（仓库）名</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":{\"is_show\":1,\"shop_list\":[{\"id\":18,\"name\":\"总店\"},{\"id\":6,\"name\":\"爱琴海店\"},{\"id\":5,\"name\":\"留云路店\"},{\"id\":16,\"name\":\"龙湖源著店\"},{\"id\":17,\"name\":\"重庆城口店\"},{\"id\":15,\"name\":\"珠江太阳城店\"},{\"id\":21,\"name\":\"两江时光店\"},{\"id\":22,\"name\":\"测试门店10\"},{\"id\":25,\"name\":\"约克郡南郡\"},{\"id\":24,\"name\":\"约克郡北郡\"},{\"id\":26,\"name\":\"蓝光COCO店\"},{\"id\":29,\"name\":\"融创金茂店\"},{\"id\":28,\"name\":\"港城国际店\"},{\"id\":27,\"name\":\"江与城店\"},{\"id\":30,\"name\":\"麓山别苑店\"},{\"id\":31,\"name\":\"奥山别墅店\"}]}}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/Stock/get_init_data"
      }
    ],
    "filename": "../../app/admin/controller/StockController.php",
    "groupTitle": "库存盘点"
  },
  {
    "type": "POST",
    "url": "/admin/Stock/get_stock_list",
    "title": "获取库存盘点列表",
    "group": "库存盘点",
    "name": "get_stock_list",
    "version": "1.0.0",
    "description": "<p>获取库存盘点列表</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码 不可空</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "shop_id",
            "description": "<p>门店（仓库）ID 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "item_name",
            "description": "<p>商品名 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "bar_code",
            "description": "<p>商品条形码 可空</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "msg",
            "description": "<p>请求结果 success成功 fail失败</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果集</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.totalPage",
            "description": "<p>总页数</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.totalNum",
            "description": "<p>总记录条数</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.pageSize",
            "description": "<p>每页记录条数</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data.list",
            "description": "<p>库存数据列表</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.list.id",
            "description": "<p>库存数据表ID</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.list.item_id",
            "description": "<p>商品ID</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.list.shop_id",
            "description": "<p>门店（仓库）ID</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.list.shop_name",
            "description": "<p>门店（仓库）名</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.list.title",
            "description": "<p>商品名称</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.list.cname",
            "description": "<p>商品分类</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.list.bar_code",
            "description": "<p>商品条形码</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.list.stock",
            "description": "<p>当前库存</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.list.num",
            "description": "<p>冻结库存</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":{\"totalPage\":4,\"totalNum\":35,\"pageSize\":10,\"list\":[{\"id\":53,\"shop_name\":\"测试门店10\",\"shop_id\":22,\"title\":\"花王NB90纸尿裤  1袋装\",\"bar_code\":\"4901301230782\",\"item_id\":1,\"cname\":\"花王\",\"stock\":32,\"num\":0},{\"id\":52,\"shop_name\":\"测试门店10\",\"shop_id\":22,\"title\":\"花王S82纸尿裤  1袋装\",\"bar_code\":\"4901301230812\",\"item_id\":2,\"cname\":\"花王\",\"stock\":0,\"num\":0},{\"id\":51,\"shop_name\":\"测试门店10\",\"shop_id\":22,\"title\":\"花王L54纸尿裤  1袋装\",\"bar_code\":\"4901301230881\",\"item_id\":4,\"cname\":\"花王\",\"stock\":99,\"num\":0},{\"id\":50,\"shop_name\":\"测试门店10\",\"shop_id\":22,\"title\":\"花王XL44纸尿裤  1袋装\",\"bar_code\":\"4901301253422\",\"item_id\":5,\"cname\":\"花王\",\"stock\":0,\"num\":0},{\"id\":49,\"shop_name\":\"测试门店10\",\"shop_id\":22,\"title\":\"新西兰惠氏金装S-26  1段1罐装\",\"bar_code\":\"\",\"item_id\":6,\"cname\":\"惠氏\",\"stock\":1,\"num\":0},{\"id\":46,\"shop_name\":\"总店\",\"shop_id\":18,\"title\":\"花王NB90纸尿裤  1袋装\",\"bar_code\":\"4901301230782\",\"item_id\":1,\"cname\":\"花王\",\"stock\":12,\"num\":0},{\"id\":45,\"shop_name\":\"总店\",\"shop_id\":18,\"title\":\"花王S82纸尿裤  1袋装\",\"bar_code\":\"4901301230812\",\"item_id\":2,\"cname\":\"花王\",\"stock\":1,\"num\":0},{\"id\":44,\"shop_name\":\"总店\",\"shop_id\":18,\"title\":\"花王L54纸尿裤  1袋装\",\"bar_code\":\"4901301230881\",\"item_id\":4,\"cname\":\"花王\",\"stock\":23,\"num\":0},{\"id\":43,\"shop_name\":\"总店\",\"shop_id\":18,\"title\":\"花王XL44纸尿裤  1袋装\",\"bar_code\":\"4901301253422\",\"item_id\":5,\"cname\":\"花王\",\"stock\":1,\"num\":0},{\"id\":42,\"shop_name\":\"总店\",\"shop_id\":18,\"title\":\"新西兰惠氏金装S-26  1段1罐装\",\"bar_code\":\"\",\"item_id\":6,\"cname\":\"惠氏\",\"stock\":1,\"num\":0}]}}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/Stock/get_stock_list"
      }
    ],
    "filename": "../../app/admin/controller/StockController.php",
    "groupTitle": "库存盘点"
  },
  {
    "type": "POST",
    "url": "/admin/Stock/save",
    "title": "保存盘盈单/盘亏单",
    "group": "库存盘点",
    "name": "save",
    "version": "1.0.0",
    "description": "<p>保存或编辑盘盈单（盘亏单）</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>盘盈单（盘亏单）id，为空时添加，不为空时编辑</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>单据类型，1为盘盈单，2为盘亏单</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "shop_id",
            "description": "<p>门店（仓库）ID</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "sn",
            "description": "<p>盘盈单（盘亏单）编号</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "remark",
            "description": "<p>盘盈单（盘亏单）备注</p>"
          },
          {
            "group": "请求参数",
            "type": "array",
            "optional": false,
            "field": "item_list",
            "description": "<p>商品数据集</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "item_list.item_id",
            "description": "<p>商品ID</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "item_list.num",
            "description": "<p>商品盘盈（盘亏）数量</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "item_list.remark",
            "description": "<p>备注</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "msg",
            "description": "<p>请求结果 success成功 fail失败</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果信息</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":\"保存成功\"}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/Stock/save"
      }
    ],
    "filename": "../../app/admin/controller/StockController.php",
    "groupTitle": "库存盘点"
  },
  {
    "type": "POST",
    "url": "/admin/InventoryLoss/get_inventory_loss_list",
    "title": "盘亏单列表",
    "group": "盘亏单管理",
    "name": "get_inventory_loss_list",
    "version": "1.0.0",
    "description": "<p>盘亏单列表</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "shop_id",
            "description": "<p>门店（仓库）ID 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "item_name",
            "description": "<p>商品名称 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "sn",
            "description": "<p>盘亏单号 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "creator_name",
            "description": "<p>入库制单 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "time",
            "description": "<p>盘亏单时间段 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "status",
            "description": "<p>入库状态 1已入库 0未入库 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码 可空</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "msg",
            "description": "<p>请求结果 success成功 fail失败</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果集</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.totalPage",
            "description": "<p>总页数</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.totalNum",
            "description": "<p>总记录条数</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.pageSize",
            "description": "<p>每页记录条数</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data.list",
            "description": "<p>盘亏单列表数据集</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.list.id",
            "description": "<p>stock表的ID</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.list.shop_id",
            "description": "<p>不用管</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.list.shop_name",
            "description": "<p>门店（仓库）名</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.list.sn",
            "description": "<p>盘亏单编号</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.list.creator_id",
            "description": "<p>不用管</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.list.creator_name",
            "description": "<p>制单人</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.list.status",
            "description": "<p>盘亏单状态 0未入库 1已入库,</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.list.time",
            "description": "<p>盘亏单时间</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":{\"list\":[{\"id\":2,\"shop_id\":22,\"sn\":\"PY201808060002\",\"creator_id\":1,\"time\":\"2018-08-06 14:59:14\",\"status\":0,\"shop_name\":\"测试门店10\",\"creator_name\":\"admin\"},{\"id\":1,\"shop_id\":22,\"sn\":\"PY201808060001\",\"creator_id\":1,\"time\":\"2018-08-06 15:00:55\",\"status\":0,\"shop_name\":\"测试门店10\",\"creator_name\":\"admin\"}],\"totalPage\":1,\"totalNum\":2,\"pageSize\":10}}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/InventoryLoss/get_inventory_loss_list"
      }
    ],
    "filename": "../../app/admin/controller/InventoryLossController.php",
    "groupTitle": "盘亏单管理"
  },
  {
    "type": "POST",
    "url": "/admin/InventoryLoss/get_one_inventory_loss_info",
    "title": "查看盘亏单详细信息",
    "group": "盘亏单管理",
    "name": "get_one_inventory_loss_info",
    "version": "1.0.0",
    "description": "<p>查看盘亏单详细信息</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>stock表的ID，不可空</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "msg",
            "description": "<p>请求结果 success成功 fail失败</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果集</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data.stock_info",
            "description": "<p>盘亏单数据</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.stock_info.id",
            "description": "<p>stock表的id</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.stock_info.creator_id",
            "description": "<p>不用管</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.stock_info.complete_admin_id",
            "description": "<p>不用管</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.stock_info.shop_id",
            "description": "<p>不用管</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.stock_info.shop_name",
            "description": "<p>所属仓库</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.stock_info.time",
            "description": "<p>盘亏单日期</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.stock_info.sn",
            "description": "<p>盘亏单编号</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.stock_info.creator_name",
            "description": "<p>盘点制单人</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.stock_info.complete_name",
            "description": "<p>出库人</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.stock_info.remark",
            "description": "<p>备注信息</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data.stock_item_info",
            "description": ""
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.stock_item_info.item_id",
            "description": "<p>商品ID</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.stock_item_info.title",
            "description": "<p>商品名称</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.stock_item_info.bar_code",
            "description": "<p>商品条形码</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.stock_item_info.cname",
            "description": "<p>商品分类</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.stock_item_info.num",
            "description": "<p>数量</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.stock_item_info.remark",
            "description": "<p>备注</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":{\"list\":[{\"id\":2,\"shop_id\":22,\"sn\":\"PY201808060002\",\"creator_id\":1,\"time\":\"2018-08-06 14:59:14\",\"status\":0,\"shop_name\":\"测试门店10\",\"creator_name\":\"admin\"},{\"id\":1,\"shop_id\":22,\"sn\":\"PY201808060001\",\"creator_id\":1,\"time\":\"2018-08-06 15:00:55\",\"status\":0,\"shop_name\":\"测试门店10\",\"creator_name\":\"admin\"}],\"totalPage\":1,\"totalNum\":2,\"pageSize\":10}}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/InventoryLoss/get_one_inventory_loss_info"
      }
    ],
    "filename": "../../app/admin/controller/InventoryLossController.php",
    "groupTitle": "盘亏单管理"
  },
  {
    "type": "POST",
    "url": "/admin/InventoryLoss/stock_out_confirm",
    "title": "确认出库",
    "group": "盘亏单管理",
    "name": "stock_out_confirm",
    "version": "1.0.0",
    "description": "<p>确认出库</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>stock表的ID，不可空</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "msg",
            "description": "<p>请求结果 success成功 fail失败</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果信息</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":\"\"}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/InventoryLoss/stock_out_confirm"
      }
    ],
    "filename": "../../app/admin/controller/InventoryLossController.php",
    "groupTitle": "盘亏单管理"
  },
  {
    "type": "POST",
    "url": "/admin/InventoryProfit/get_inventory_profit_list",
    "title": "盘盈单列表",
    "group": "盘盈单管理",
    "name": "get_inventory_profit_list",
    "version": "1.0.0",
    "description": "<p>盘盈单列表</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "shop_id",
            "description": "<p>门店（仓库）ID 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "item_name",
            "description": "<p>商品名称 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "sn",
            "description": "<p>盘盈单号 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "creator_name",
            "description": "<p>入库制单 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "time",
            "description": "<p>盘盈单时间段 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "status",
            "description": "<p>入库状态 1已入库 0未入库 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码 可空</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "msg",
            "description": "<p>请求结果 success成功 fail失败</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果集</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.totalPage",
            "description": "<p>总页数</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.totalNum",
            "description": "<p>总记录条数</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.pageSize",
            "description": "<p>每页记录条数</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data.list",
            "description": "<p>盘盈单列表数据集</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.list.id",
            "description": "<p>stock表的ID</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.list.shop_id",
            "description": "<p>不用管</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.list.shop_name",
            "description": "<p>门店（仓库）名</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.list.sn",
            "description": "<p>盘盈单编号</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.list.creator_id",
            "description": "<p>不用管</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.list.creator_name",
            "description": "<p>制单人</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.list.status",
            "description": "<p>盘盈单状态 0未入库 1已入库,</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.list.time",
            "description": "<p>盘盈单时间</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":{\"list\":[{\"id\":2,\"shop_id\":22,\"sn\":\"PY201808060002\",\"creator_id\":1,\"time\":\"2018-08-06 14:59:14\",\"status\":0,\"shop_name\":\"测试门店10\",\"creator_name\":\"admin\"},{\"id\":1,\"shop_id\":22,\"sn\":\"PY201808060001\",\"creator_id\":1,\"time\":\"2018-08-06 15:00:55\",\"status\":0,\"shop_name\":\"测试门店10\",\"creator_name\":\"admin\"}],\"totalPage\":1,\"totalNum\":2,\"pageSize\":10}}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/InventoryProfit/get_purchase_list"
      }
    ],
    "filename": "../../app/admin/controller/InventoryProfitController.php",
    "groupTitle": "盘盈单管理"
  },
  {
    "type": "POST",
    "url": "/admin/InventoryProfit/get_one_inventory_profit_info",
    "title": "查看盘盈单详细信息",
    "group": "盘盈单管理",
    "name": "get_one_inventory_profit_info",
    "version": "1.0.0",
    "description": "<p>查看盘盈单详细信息</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>stock表的ID，不可空</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "msg",
            "description": "<p>请求结果 success成功 fail失败</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果集</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data.stock_info",
            "description": "<p>盘盈单数据</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.stock_info.id",
            "description": "<p>stock表的id</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.stock_info.creator_id",
            "description": "<p>不用管</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.stock_info.complete_admin_id",
            "description": "<p>不用管</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.stock_info.shop_id",
            "description": "<p>不用管</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.stock_info.shop_name",
            "description": "<p>所入仓库</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.stock_info.time",
            "description": "<p>盘盈单日期</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.stock_info.sn",
            "description": "<p>盘盈单编号</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.stock_info.creator_name",
            "description": "<p>盘点制单人</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.stock_info.complete_name",
            "description": "<p>入库人</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.stock_info.remark",
            "description": "<p>备注信息</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data.stock_item_info",
            "description": ""
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.stock_item_info.item_id",
            "description": "<p>商品ID</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.stock_item_info.title",
            "description": "<p>商品名称</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.stock_item_info.bar_code",
            "description": "<p>商品条形码</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.stock_item_info.cname",
            "description": "<p>商品分类</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.stock_item_info.num",
            "description": "<p>数量</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.stock_item_info.remark",
            "description": "<p>备注</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":{\"list\":[{\"id\":2,\"shop_id\":22,\"sn\":\"PY201808060002\",\"creator_id\":1,\"time\":\"2018-08-06 14:59:14\",\"status\":0,\"shop_name\":\"测试门店10\",\"creator_name\":\"admin\"},{\"id\":1,\"shop_id\":22,\"sn\":\"PY201808060001\",\"creator_id\":1,\"time\":\"2018-08-06 15:00:55\",\"status\":0,\"shop_name\":\"测试门店10\",\"creator_name\":\"admin\"}],\"totalPage\":1,\"totalNum\":2,\"pageSize\":10}}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/InventoryProfit/get_one_inventory_profit_info"
      }
    ],
    "filename": "../../app/admin/controller/InventoryProfitController.php",
    "groupTitle": "盘盈单管理"
  },
  {
    "type": "POST",
    "url": "/admin/InventoryProfit/stock_in_confirm",
    "title": "确认入库",
    "group": "盘盈单管理",
    "name": "stock_in_confirm",
    "version": "1.0.0",
    "description": "<p>确认入库</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>stock表的ID，不可空</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "msg",
            "description": "<p>请求结果 success成功 fail失败</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果信息</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":\"\"}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/InventoryProfit/stock_in_confirm"
      }
    ],
    "filename": "../../app/admin/controller/InventoryProfitController.php",
    "groupTitle": "盘盈单管理"
  },
  {
    "type": "POST",
    "url": "/admin/Allot/add",
    "title": "添加调拨单时获取初始化数据",
    "group": "调拨单管理",
    "name": "add",
    "version": "1.0.0",
    "description": "<p>添加调拨单时获取初始化数据</p>",
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码 200成功 301失败</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "msg",
            "description": "<p>请求结果 success成功 fail失败</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果集</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.sn",
            "description": "<p>调拨单号</p>"
          },
          {
            "group": "返回参数",
            "type": "Array",
            "optional": false,
            "field": "data.from_shop",
            "description": "<p>调出仓库列表</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.from_shop.id",
            "description": "<p>调出门店（仓库）ID</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.from_shop.name",
            "description": "<p>调出门店（仓库）名</p>"
          },
          {
            "group": "返回参数",
            "type": "Array",
            "optional": false,
            "field": "data.to_shop",
            "description": "<p>调入仓库列表</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.to_shop.id",
            "description": "<p>调入门店（仓库）ID</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.to_shop.name",
            "description": "<p>调入门店（仓库）名</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.time",
            "description": "<p>调拨单时间戳</p>"
          },
          {
            "group": "返回参数",
            "type": "Array",
            "optional": false,
            "field": "data.outer",
            "description": "<p>出库人</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"\",\"data\":{\"sn\":\"DB201807240002\",\"from_shop\":[{\"id\":18,\"name\":\"总店\"},{\"id\":6,\"name\":\"爱琴海店\"},{\"id\":5,\"name\":\"留云路店\"},{\"id\":16,\"name\":\"龙湖源著店\"},{\"id\":17,\"name\":\"重庆城口店\"},{\"id\":15,\"name\":\"珠江太阳城店\"},{\"id\":21,\"name\":\"两江时光店\"},{\"id\":22,\"name\":\"测试门店10\"},{\"id\":25,\"name\":\"约克郡南郡\"},{\"id\":24,\"name\":\"约克郡北郡\"},{\"id\":26,\"name\":\"蓝光COCO店\"},{\"id\":29,\"name\":\"融创金茂店\"},{\"id\":28,\"name\":\"港城国际店\"},{\"id\":27,\"name\":\"江与城店\"},{\"id\":30,\"name\":\"麓山别苑店\"},{\"id\":31,\"name\":\"奥山别墅店\"}],\"to_shop\":[{\"id\":18,\"name\":\"总店\"},{\"id\":6,\"name\":\"爱琴海店\"},{\"id\":5,\"name\":\"留云路店\"},{\"id\":16,\"name\":\"龙湖源著店\"},{\"id\":17,\"name\":\"重庆城口店\"},{\"id\":15,\"name\":\"珠江太阳城店\"},{\"id\":21,\"name\":\"两江时光店\"},{\"id\":22,\"name\":\"测试门店10\"},{\"id\":25,\"name\":\"约克郡南郡\"},{\"id\":24,\"name\":\"约克郡北郡\"},{\"id\":26,\"name\":\"蓝光COCO店\"},{\"id\":29,\"name\":\"融创金茂店\"},{\"id\":28,\"name\":\"港城国际店\"},{\"id\":27,\"name\":\"江与城店\"},{\"id\":30,\"name\":\"麓山别苑店\"},{\"id\":31,\"name\":\"奥山别墅店\"}],\"time\":1532414864,\"outer\":\"admin\"}}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/Allot/add"
      }
    ],
    "filename": "../../app/admin/controller/AllotController.php",
    "groupTitle": "调拨单管理"
  },
  {
    "type": "POST",
    "url": "/admin/Allot/allot_confirm",
    "title": "确认调入",
    "group": "调拨单管理",
    "name": "allot_confirm",
    "version": "1.0.0",
    "description": "<p>确认调入，重新计算调入仓库的商品成本和库存，减少调出仓库的库存</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "allot_id",
            "description": "<p>调拨单ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码 200成功 301失败</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":[]}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/Allot/allot_confirm"
      }
    ],
    "filename": "../../app/admin/controller/AllotController.php",
    "groupTitle": "调拨单管理"
  },
  {
    "type": "POST",
    "url": "/admin/Allot/delete",
    "title": "删除调拨单",
    "group": "调拨单管理",
    "name": "delete",
    "version": "1.0.0",
    "description": "<p>删除调拨单</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "allot_id",
            "description": "<p>调拨单ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码 200成功 301失败</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":[]}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/Allot/delete"
      }
    ],
    "filename": "../../app/admin/controller/AllotController.php",
    "groupTitle": "调拨单管理"
  },
  {
    "type": "POST",
    "url": "/admin/Allot/editOrView",
    "title": "编辑或查看调拨单",
    "group": "调拨单管理",
    "name": "editOrView",
    "version": "1.0.0",
    "description": "<p>编辑或查看调拨单，data.allotInfo.status值为0时可编辑、删除，为1时只可查看</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "allot_id",
            "description": "<p>调拨单ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码 200成功 301失败</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "msg",
            "description": "<p>请求结果 success成功 fail失败</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果集</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.is_show",
            "description": "<p>是否显示金额数据 1是 0否</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data.allot.allotInfo",
            "description": "<p>调拨单基本数据</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.allot.allotInfo.to_shop",
            "description": "<p>调出仓库ID</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.allot.allotInfo.from_shop",
            "description": "<p>调入仓库ID</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.allot.allotInfo.sn",
            "description": "<p>调拨单号</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.allot.allotInfo.time",
            "description": "<p>调拨单时间戳</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.allot.allotInfo.status",
            "description": "<p>调拨单状态</p>"
          },
          {
            "group": "返回参数",
            "type": "float",
            "optional": false,
            "field": "data.allot.allotInfo.bid_amount",
            "description": "<p>调拨单进价金额</p>"
          },
          {
            "group": "返回参数",
            "type": "float",
            "optional": false,
            "field": "data.allot.allotInfo.cost",
            "description": "<p>调拨单实际成本金额</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.allot.allotInfo.outor",
            "description": "<p>调拨单出库人</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.allot.allotInfo.iner",
            "description": "<p>调拨单入库人</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.allot.allotInfo.remark",
            "description": "<p>调拨单备注</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data.allot.allotItemInfo",
            "description": "<p>调拨单商品数据</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.allot.allotItemInfo.id",
            "description": "<p>调拨商品表ID</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.allot.allotItemInfo.item_id",
            "description": "<p>商品ID</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.allot.allotItemInfo.num",
            "description": "<p>调拨数量</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.allot.allotItemInfo.title",
            "description": "<p>商品名</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.allot.allotItemInfo.bar_code",
            "description": "<p>条形码</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.allot.allotItemInfo.cname",
            "description": "<p>商品分类</p>"
          },
          {
            "group": "返回参数",
            "type": "float",
            "optional": false,
            "field": "data.allot.allotItemInfo.md_price",
            "description": "<p>调拨时商品的门店单价</p>"
          },
          {
            "group": "返回参数",
            "type": "float",
            "optional": false,
            "field": "data.allot.allotItemInfo.store_cost",
            "description": "<p>调拨时商品的真实成本</p>"
          },
          {
            "group": "返回参数",
            "type": "float",
            "optional": false,
            "field": "data.allot.allotItemInfo.stock",
            "description": "<p>调拨时商品的库存</p>"
          },
          {
            "group": "返回参数",
            "type": "Array",
            "optional": false,
            "field": "data.toShopDatas",
            "description": "<p>调入仓库列表</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.toShopDatas.id",
            "description": "<p>仓库ID</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.toShopDatas.name",
            "description": "<p>仓库名</p>"
          },
          {
            "group": "返回参数",
            "type": "Array",
            "optional": false,
            "field": "data.fromShopDatas",
            "description": "<p>调出仓库列表</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.fromShopDatas.id",
            "description": "<p>仓库ID</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.fromShopDatas.name",
            "description": "<p>仓库名</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":{\"is_show\":0,\"formShopDatas\":{\"id\":18,\"name\":\"总店\"},\"toShopDatas\":[{\"id\":18,\"name\":\"总店\"},{\"id\":6,\"name\":\"爱琴海店\"},{\"id\":5,\"name\":\"留云路店\"},{\"id\":16,\"name\":\"龙湖源著店\"},{\"id\":17,\"name\":\"重庆城口店\"},{\"id\":15,\"name\":\"珠江太阳城店\"},{\"id\":21,\"name\":\"两江时光店\"},{\"id\":22,\"name\":\"测试门店10\"},{\"id\":25,\"name\":\"约克郡南郡\"},{\"id\":24,\"name\":\"约克郡北郡\"},{\"id\":26,\"name\":\"蓝光COCO店\"},{\"id\":29,\"name\":\"融创金茂店\"},{\"id\":28,\"name\":\"港城国际店\"},{\"id\":27,\"name\":\"江与城店\"},{\"id\":30,\"name\":\"麓山别苑店\"},{\"id\":31,\"name\":\"奥山别墅店\"}],\"allot\":{\"allotInfo\":{\"id\":1,\"sn\":\"DB201807230001\",\"from_shop\":6,\"out_admin_id\":1,\"to_shop\":17,\"in_admin_id\":0,\"status\":0,\"in_time\":0,\"bid_amount\":\"600.0000\",\"cost\":\"540.0000\",\"remark\":\"\",\"time\":0,\"outor\":\"admin\",\"iner\":null},\"allotItemInfo\":[{\"id\":1,\"allot_id\":1,\"shop_id\":4,\"item_id\":3,\"num\":2,\"now_md_univalent\":\"0.0000\",\"now_store_cost\":\"0.0000\",\"remark\":\"\",\"stock\":null,\"title\":\"惠氏金装婴儿奶粉 3段2罐\",\"bar_code\":\"6615249817\",\"cname\":\"惠氏\"},{\"id\":2,\"allot_id\":1,\"shop_id\":4,\"item_id\":2,\"num\":2,\"now_md_univalent\":\"3.0000\",\"now_store_cost\":\"2.0000\",\"remark\":\"调拨备注1\",\"stock\":null,\"title\":\"三鹿三聚氰胺奶粉2段3罐\",\"bar_code\":\"654654\",\"cname\":\"洗发沐浴\"},{\"id\":3,\"allot_id\":1,\"shop_id\":4,\"item_id\":3,\"num\":3,\"now_md_univalent\":\"4.0000\",\"now_store_cost\":\"3.0000\",\"remark\":\"调拨备注2\",\"stock\":null,\"title\":\"惠氏金装婴儿奶粉 3段2罐\",\"bar_code\":\"6615249817\",\"cname\":\"惠氏\"}]}}}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/Allot/editOrView"
      }
    ],
    "filename": "../../app/admin/controller/AllotController.php",
    "groupTitle": "调拨单管理"
  },
  {
    "type": "Post",
    "url": "/admin/Allot/getPage",
    "title": "ajax获取分页商品数据",
    "group": "调拨单管理",
    "name": "getPage",
    "version": "1.0.0",
    "description": "<p>ajax获取分页商品数据</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "curPage",
            "description": "<p>页数 不可空</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "goods_name",
            "description": "<p>商品名称 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "f_cate",
            "description": "<p>一级分类ID 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "s_cate",
            "description": "<p>二级分类ID 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "shop_id",
            "description": "<p>门店ID 不可空</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码 200成功 301错误</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "msg",
            "description": "<p>请求结果 success成功 fail失败</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>返回参数结果集</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.pageSize",
            "description": "<p>每页记录条数</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.totalItem",
            "description": "<p>总记录条数</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.totalPage",
            "description": "<p>总页数</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data.data_content",
            "description": "<p>商品数据</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.data_content.item_id",
            "description": "<p>商品ID</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.data_content.title",
            "description": "<p>商品名称</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.data_content.cname",
            "description": "<p>商品分类</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.data_content.bar_code",
            "description": "<p>商品条形码</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.data_content.price",
            "description": "<p>商品原价</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.data_content.md_price",
            "description": "<p>商品门店进价</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.data_content.store_cost",
            "description": "<p>商品入库成本</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.data_content.stock",
            "description": "<p>商品库存</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":\"1\",\"msg\":\"成功\",\"time\":1532416271,\"data\":{\"totalItem\":4,\"pageSize\":10,\"totalPage\":1,\"data_content\":[{\"id\":2,\"title\":\"三鹿三聚氰胺奶粉2段3罐\",\"cname\":\"洗发沐浴\",\"is_price_control\":0,\"price\":\"100.00\",\"bar_code\":\"654654\",\"cg_standard_price\":\"666.00\",\"md_standard_price\":\"555.00\",\"stock\":null,\"md_price\":null,\"store_cost\":null},{\"id\":3,\"title\":\"惠氏金装婴儿奶粉 3段2罐\",\"cname\":\"惠氏\",\"is_price_control\":0,\"price\":\"268.00\",\"bar_code\":\"6615249817\",\"cg_standard_price\":\"210.00\",\"md_standard_price\":\"258.00\",\"stock\":null,\"md_price\":null,\"store_cost\":null},{\"id\":4,\"title\":\"惠氏启赋金装二段2罐装\",\"cname\":\"惠氏\",\"is_price_control\":0,\"price\":\"359.00\",\"bar_code\":\"1231431232\",\"cg_standard_price\":\"309.00\",\"md_standard_price\":\"339.00\",\"stock\":null,\"md_price\":null,\"store_cost\":null},{\"id\":5,\"title\":\"测试商品\",\"cname\":\"湿巾纸巾\",\"is_price_control\":1,\"price\":\"52.00\",\"bar_code\":\"54779876984\",\"cg_standard_price\":\"30.00\",\"md_standard_price\":\"40.00\",\"stock\":null,\"md_price\":null,\"store_cost\":null}]}}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/Allot/getPage"
      }
    ],
    "filename": "../../app/admin/controller/AllotController.php",
    "groupTitle": "调拨单管理"
  },
  {
    "type": "POST",
    "url": "/admin/Allot/get_allot_list",
    "title": "调拨单列表",
    "group": "调拨单管理",
    "name": "get_allot_list",
    "version": "1.0.0",
    "description": "<p>调拨单列表</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "time",
            "description": "<p>调拨单时间 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "item_name",
            "description": "<p>商品名称 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "sn",
            "description": "<p>调拨单号 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "from_shop",
            "description": "<p>调出仓库 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "to_shop",
            "description": "<p>调入仓库 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "status",
            "description": "<p>调拨状态 1已完成 0调拨中 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码 可空</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "msg",
            "description": "<p>请求结果 success成功 fail失败</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data.allot_list",
            "description": "<p>调拨单列表</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.allot_list.id",
            "description": "<p>调拨单ID</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.allot_list.time",
            "description": "<p>调拨单时间</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.allot_list.sn",
            "description": "<p>调拨单编号</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.allot_list.from_shop_name",
            "description": "<p>调出仓库名</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.allot_list.to_shop_name",
            "description": "<p>调入仓库名</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.allot_list.outer",
            "description": "<p>出库人员</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.allot_list.iner",
            "description": "<p>入库人员</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.allot_list.in_time",
            "description": "<p>入库时间</p>"
          },
          {
            "group": "返回参数",
            "type": "float",
            "optional": false,
            "field": "data.allot_list.bid_amount",
            "description": "<p>进价金额</p>"
          },
          {
            "group": "返回参数",
            "type": "float",
            "optional": false,
            "field": "data.allot_list.cost",
            "description": "<p>成本金额</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.allot_list.can_edit",
            "description": "<p>是否可编辑 1可以 0不可以</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.allot_list.status",
            "description": "<p>调拨状态 1已完成 0调拨中</p>"
          },
          {
            "group": "返回参数",
            "type": "float",
            "optional": false,
            "field": "data.bid_amount_all",
            "description": "<p>进价总金额</p>"
          },
          {
            "group": "返回参数",
            "type": "float",
            "optional": false,
            "field": "data.cost_all",
            "description": "<p>成本总金额</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.is_show",
            "description": "<p>是否显示金额相关数据 1是 0否</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.totalPage",
            "description": "<p>总页数</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.totalNum",
            "description": "<p>记录总条数</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.pageSize",
            "description": "<p>每页记录条数</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":{\"shop_list\":[{\"id\":18,\"name\":\"总店\"},{\"id\":6,\"name\":\"爱琴海店\"},{\"id\":5,\"name\":\"留云路店\"},{\"id\":16,\"name\":\"龙湖源著店\"},{\"id\":17,\"name\":\"重庆城口店\"},{\"id\":15,\"name\":\"珠江太阳城店\"},{\"id\":21,\"name\":\"两江时光店\"},{\"id\":22,\"name\":\"测试门店10\"},{\"id\":25,\"name\":\"约克郡南郡\"},{\"id\":24,\"name\":\"约克郡北郡\"},{\"id\":26,\"name\":\"蓝光COCO店\"},{\"id\":29,\"name\":\"融创金茂店\"},{\"id\":28,\"name\":\"港城国际店\"},{\"id\":27,\"name\":\"江与城店\"},{\"id\":30,\"name\":\"麓山别苑店\"},{\"id\":31,\"name\":\"奥山别墅店\"}],\"allot_list\":[{\"id\":1,\"sn\":\"DB201807230001\",\"from_shop\":6,\"out_admin_id\":1,\"to_shop\":17,\"in_admin_id\":0,\"status\":0,\"in_time\":0,\"bid_amount\":\"600.0000\",\"cost\":\"540.0000\",\"time\":0,\"outer\":\"admin\",\"iner\":null,\"from_shop_name\":\"爱琴海店\",\"to_shop_name\":\"重庆城口店\"}],\"allot_list_num\":1,\"bid_amount_all\":600,\"cost_all\":540,\"is_show\":0}}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/Allot/get_allot_list"
      }
    ],
    "filename": "../../app/admin/controller/AllotController.php",
    "groupTitle": "调拨单管理"
  },
  {
    "type": "Post",
    "url": "/admin/Allot/get_goods_by_code",
    "title": "条形码",
    "group": "调拨单管理",
    "name": "get_goods_by_code",
    "version": "1.0.0",
    "description": "<p>根据条形码和店铺ID获取商品信息</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "code",
            "description": "<p>条形码 不可空</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "shop_id",
            "description": "<p>门店ID 不可空</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码 200成功 301错误</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "msg",
            "description": "<p>请求结果 success成功 fail失败</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>返回参数结果集</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.item_id",
            "description": "<p>商品ID</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.title",
            "description": "<p>商品名称</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.cname",
            "description": "<p>商品分类</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.bar_code",
            "description": "<p>商品条形码</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.price",
            "description": "<p>商品原价</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.md_price",
            "description": "<p>商品门店进价</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.store_cost",
            "description": "<p>商品入库成本</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.stock",
            "description": "<p>商品库存</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":{\"shop_name\":\"测试门店10\",\"title\":\"测试商品2\",\"price\":\"200.00\",\"bar_code\":\"154854\",\"item_id\":746,\"cname\":\"模型玩具\",\"shop_id\":22,\"stock\":80,\"md_price\":\"120.00\",\"store_cost\":\"879.18\"}}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/Allot/get_goods_by_code"
      }
    ],
    "filename": "../../app/admin/controller/AllotController.php",
    "groupTitle": "调拨单管理"
  },
  {
    "type": "POST",
    "url": "/admin/Allot/save",
    "title": "保存调拨单信息",
    "group": "调拨单管理",
    "name": "save",
    "version": "1.0.0",
    "description": "<p>保存调拨单信息，有id时为修改，无id时新增</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>调拨单ID，可以为空</p>"
          },
          {
            "group": "请求参数",
            "type": "array",
            "optional": false,
            "field": "item_list",
            "description": "<p>调拨单所含商品</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "item_list.item_id",
            "description": "<p>商品ID</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "item_list.num",
            "description": "<p>商品调拨数量</p>"
          },
          {
            "group": "请求参数",
            "type": "float",
            "optional": false,
            "field": "item_list.now_md_univalent",
            "description": "<p>调出时商品的门店单价（假成本）</p>"
          },
          {
            "group": "请求参数",
            "type": "float",
            "optional": false,
            "field": "item_list.now_store_cost",
            "description": "<p>调出时商品的实际成本（真成本）</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "item_list.remark",
            "description": "<p>备注</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "from_shop",
            "description": "<p>调出门店（仓库）ID</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "to_shop",
            "description": "<p>调入门店（仓库）ID</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "sn",
            "description": "<p>调拨单编号</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "remark",
            "description": "<p>备注</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求样例:",
          "content": "{\"item_list\":[{\"item_id\":2,\"num\":2,\"now_md_univalent\":8,\"now_store_cost\":4,\"remark\":\"\\u8c03\\u62e8\\u5907\\u6ce85\"},{\"item_id\":3,\"num\":3,\"now_md_univalent\":10,\"now_store_cost\":7,\"remark\":\"\\u8c03\\u62e8\\u5907\\u6ce86\"}],\"from_shop\":5,\"to_shop\":17,\"time\":\"1531729167\",\"sn\":\"DB201807190001\",\"id\":\"2\",\"remark\":\"\\u603b\\u5907\\u6ce81\"}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码 200成功 301失败 302库存发生变化刷新页面重试</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "msg",
            "description": "<p>请求结果 success成功 fail失败</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data",
            "description": "<p>请求结果信息</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":[]}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/Allot/save"
      }
    ],
    "filename": "../../app/admin/controller/AllotController.php",
    "groupTitle": "调拨单管理"
  },
  {
    "type": "POST",
    "url": "/admin/Reject/confirmOutStock",
    "title": "确认出库",
    "group": "退货单管理",
    "name": "confirmOutStock",
    "version": "1.0.0",
    "description": "<p>确认出库，重新计算仓库的商品成本和库存</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "Int",
            "optional": false,
            "field": "reject_id",
            "description": "<p>调拨单ID</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":[]}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/Reject/confirmOutStock"
      }
    ],
    "filename": "../../app/admin/controller/RejectController.php",
    "groupTitle": "退货单管理"
  },
  {
    "type": "POST",
    "url": "/admin/Reject/delete",
    "title": "删除退货单",
    "group": "退货单管理",
    "name": "delete",
    "version": "1.0.0",
    "description": "<p>删除退货单</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "reject_id",
            "description": "<p>退货单id</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":[]}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/Reject/delete"
      }
    ],
    "filename": "../../app/admin/controller/RejectController.php",
    "groupTitle": "退货单管理"
  },
  {
    "type": "POST",
    "url": "/admin/reject/getInitData",
    "title": "获取初始化数据【进入首页就获取】",
    "group": "退货单管理",
    "name": "getInitData",
    "version": "1.0.0",
    "description": "<p>获取默认数据</p>",
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "is_show",
            "description": "<p>对门店的一些内容的限制显示</p> <ul> <li>0 就不显示相应内容</li> <li>1 不限制</li> </ul>"
          },
          {
            "group": "返回参数",
            "type": "Array",
            "optional": false,
            "field": "supplier_list",
            "description": "<p>请求结果</p>"
          },
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "supplier_list.id",
            "description": "<p>供应商id</p>"
          },
          {
            "group": "返回参数",
            "type": "String",
            "optional": false,
            "field": "supplier_list.name",
            "description": "<p>供应商name</p>"
          },
          {
            "group": "返回参数",
            "type": "Array",
            "optional": false,
            "field": "shop_list",
            "description": "<p>仓库列表</p>"
          },
          {
            "group": "返回参数",
            "type": "Array",
            "optional": false,
            "field": "shop_list.id",
            "description": "<p>仓库id</p>"
          },
          {
            "group": "返回参数",
            "type": "Array",
            "optional": false,
            "field": "shop_list.name",
            "description": "<p>仓库name</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"获取成功\",\"data\":{\"is_show\":1,\"supplier_list\":[{\"id\":1,\"name\":\"1\"},{\"id\":2,\"name\":\"11\"},{\"id\":3,\"name\":\"供应商1\"},{\"id\":4,\"name\":\"供应商2\"},{\"id\":5,\"name\":\"供应商3\"}],\"shop_list\":[{\"id\":18,\"name\":\"总店\"},{\"id\":6,\"name\":\"爱琴海店\"},{\"id\":5,\"name\":\"留云路店\"},{\"id\":16,\"name\":\"龙湖源著店\"},{\"id\":17,\"name\":\"重庆城口店\"},{\"id\":15,\"name\":\"珠江太阳城店\"},{\"id\":21,\"name\":\"两江时光店\"},{\"id\":22,\"name\":\"测试门店10\"},{\"id\":25,\"name\":\"约克郡南郡\"},{\"id\":24,\"name\":\"约克郡北郡\"},{\"id\":26,\"name\":\"蓝光COCO店\"},{\"id\":29,\"name\":\"融创金茂店\"},{\"id\":28,\"name\":\"港城国际店\"},{\"id\":27,\"name\":\"江与城店\"},{\"id\":30,\"name\":\"麓山别苑店\"},{\"id\":31,\"name\":\"奥山别墅店\"}]}}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/reject/getInitData"
      }
    ],
    "filename": "../../app/admin/controller/RejectController.php",
    "groupTitle": "退货单管理"
  },
  {
    "type": "Post",
    "url": "/admin/Reject/getPage",
    "title": "ajax获取分页商品数据",
    "group": "退货单管理",
    "name": "getPage",
    "version": "1.0.0",
    "description": "<p>ajax获取分页商品数据</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "curPage",
            "description": "<p>页数 不可空</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "goods_name",
            "description": "<p>商品名称 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "f_cate",
            "description": "<p>一级分类ID 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "s_cate",
            "description": "<p>二级分类ID 可空</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码 200成功 301错误</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "msg",
            "description": "<p>请求结果 success成功 fail失败</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>返回参数结果集</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.pageSize",
            "description": "<p>每页记录条数</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.totalItem",
            "description": "<p>总记录条数</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.totalPage",
            "description": "<p>总页数</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data.data_content",
            "description": "<p>商品数据</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.data_content.id",
            "description": "<p>商品ID</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.data_content.title",
            "description": "<p>商品名称</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.data_content.cname",
            "description": "<p>商品分类</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.data_content.bar_code",
            "description": "<p>商品条形码</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.data_content.price",
            "description": "<p>商品原价</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.data_content.md_price",
            "description": "<p>商品门店进价</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.data_content.store_cost",
            "description": "<p>商品入库成本</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.data_content.stock",
            "description": "<p>商品库存</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.data_content.is_price_control",
            "description": "<p>是否控价 1是 0不是</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":\"1\",\"msg\":\"成功\",\"time\":1532416271,\"data\":{\"totalItem\":4,\"pageSize\":10,\"totalPage\":1,\"data_content\":[{\"id\":2,\"title\":\"三鹿三聚氰胺奶粉2段3罐\",\"cname\":\"洗发沐浴\",\"is_price_control\":0,\"price\":\"100.00\",\"bar_code\":\"654654\",\"cg_standard_price\":\"666.00\",\"md_standard_price\":\"555.00\",\"stock\":null,\"md_price\":null,\"store_cost\":null},{\"id\":3,\"title\":\"惠氏金装婴儿奶粉 3段2罐\",\"cname\":\"惠氏\",\"is_price_control\":0,\"price\":\"268.00\",\"bar_code\":\"6615249817\",\"cg_standard_price\":\"210.00\",\"md_standard_price\":\"258.00\",\"stock\":null,\"md_price\":null,\"store_cost\":null},{\"id\":4,\"title\":\"惠氏启赋金装二段2罐装\",\"cname\":\"惠氏\",\"is_price_control\":0,\"price\":\"359.00\",\"bar_code\":\"1231431232\",\"cg_standard_price\":\"309.00\",\"md_standard_price\":\"339.00\",\"stock\":null,\"md_price\":null,\"store_cost\":null},{\"id\":5,\"title\":\"测试商品\",\"cname\":\"湿巾纸巾\",\"is_price_control\":1,\"price\":\"52.00\",\"bar_code\":\"54779876984\",\"cg_standard_price\":\"30.00\",\"md_standard_price\":\"40.00\",\"stock\":null,\"md_price\":null,\"store_cost\":null}]}}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/Reject/getPage"
      }
    ],
    "filename": "../../app/admin/controller/RejectController.php",
    "groupTitle": "退货单管理"
  },
  {
    "type": "POST",
    "url": "/admin/Reject/getRejectDetail",
    "title": "退货单详细信息",
    "group": "退货单管理",
    "name": "getRejectDetail",
    "version": "1.0.0",
    "description": "<p>【查看|修改|出库】的时候要传ID 然后查看的时候没有确定按钮吧</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "Int",
            "optional": true,
            "field": "reject_id",
            "description": "<p>退货单ID</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求样例:",
          "content": "{\"reject_id\":1}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "id",
            "description": "<p>退货单id</p>"
          },
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "shop_id",
            "description": "<p>退货仓库</p>"
          },
          {
            "group": "返回参数",
            "type": "String",
            "optional": false,
            "field": "sn",
            "description": "<p>退货单号</p>"
          },
          {
            "group": "返回参数",
            "type": "String",
            "optional": false,
            "field": "out_stock_user",
            "description": "<p>出库人</p>"
          },
          {
            "group": "返回参数",
            "type": "String",
            "optional": false,
            "field": "supplier",
            "description": "<p>供应商</p>"
          },
          {
            "group": "返回参数",
            "type": "String",
            "optional": false,
            "field": "refund_bill_user",
            "description": "<p>退货单添加人</p>"
          },
          {
            "group": "返回参数",
            "type": "String",
            "optional": false,
            "field": "out_stock_time",
            "description": "<p>出库时间</p>"
          },
          {
            "group": "返回参数",
            "type": "String",
            "optional": false,
            "field": "remark",
            "description": "<p>退货单描述</p>"
          },
          {
            "group": "返回参数",
            "type": "Float",
            "optional": false,
            "field": "amount",
            "description": "<p>退货单总额</p>"
          },
          {
            "group": "返回参数",
            "type": "String",
            "optional": false,
            "field": "addtime",
            "description": "<p>退货单添加时间</p>"
          },
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "status",
            "description": "<p>退货单状态</p> <ul> <li>1 为1的时候只能执行查看操作</li> <li>0 为0的时候可以编辑删除等操作</li> </ul>"
          },
          {
            "group": "返回参数",
            "type": "Array",
            "optional": false,
            "field": "reject_item_list",
            "description": "<p>退货单商品列表</p>"
          },
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "reject_item_list.reject_item_id",
            "description": "<p>退货商品表的id</p>"
          },
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "reject_item_list.item_id",
            "description": "<p>商品id</p>"
          },
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "reject_item_list.reject_id",
            "description": "<p>退货单id</p>"
          },
          {
            "group": "返回参数",
            "type": "Float",
            "optional": false,
            "field": "reject_item_list.price",
            "description": "<p>商品单价</p>"
          },
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "reject_item_list.num",
            "description": "<p>退货商品数量</p>"
          },
          {
            "group": "返回参数",
            "type": "Float",
            "optional": false,
            "field": "reject_item_list.sum_price",
            "description": "<p>退货商品总金额</p>"
          },
          {
            "group": "返回参数",
            "type": "String",
            "optional": false,
            "field": "reject_item_list.remark",
            "description": "<p>退货商品备注</p>"
          },
          {
            "group": "返回参数",
            "type": "String",
            "optional": false,
            "field": "reject_item_list.title",
            "description": "<p>退货商品名称</p>"
          },
          {
            "group": "返回参数",
            "type": "Number",
            "optional": false,
            "field": "reject_item_list.bar_code",
            "description": "<p>退货商品条形码</p>"
          },
          {
            "group": "返回参数",
            "type": "String",
            "optional": false,
            "field": "reject_item_list.cname",
            "description": "<p>退货商品分类名</p>"
          },
          {
            "group": "返回参数",
            "type": "String",
            "optional": false,
            "field": "reject_item_list.cost_price",
            "description": "<p>单位成本</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\n            \"code\": 200,\n            \"msg\": \"success\",\n            \"data\": {\n                \"id\": 1,\n                \"shop_id\": 22,\n                \"sn\": \"TH201807261606\",\n                \"out_stock_user\": \"admin\",\n                \"supplier\": 1,\n                \"refund_bill_user\": null,\n                \"out_stock_time\": \"2018-07-27\",\n                \"remark\": \"测试退货单\",\n                \"amount\": \"12400.00\",\n                \"addtime\": \"2018-07-22\",\n                \"status\": 0,\n                \"reject_item_list\": [\n                    {\n                        \"reject_item_id\": 1,\n                        \"item_id\": 2,\n                        \"reject_id\": 1,\n                        \"cost_price\": \"60.00\",\n                        \"num\": 40,\n                        \"sum_price\": \"2400.00\",\n                        \"remark\": \"灭的描述\",\n                        \"title\": \"三鹿三聚氰胺奶粉2段3罐\",\n                        \"bar_code\": \"654654\",\n                        \"cate_name\": \"洗发沐浴\"\n                    }\n                ]\n            }\n        }",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/Reject/getRejectDetail"
      }
    ],
    "filename": "../../app/admin/controller/RejectController.php",
    "groupTitle": "退货单管理"
  },
  {
    "type": "POST",
    "url": "/admin/reject/getRejectList",
    "title": "退货单列表",
    "group": "退货单管理",
    "name": "getRejectList",
    "version": "1.0.0",
    "description": "<p>退货单列表</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "String",
            "optional": true,
            "field": "time",
            "description": "<p>调拨单时间</p>"
          },
          {
            "group": "请求参数",
            "type": "String",
            "optional": true,
            "field": "item_name",
            "description": "<p>商品名称</p>"
          },
          {
            "group": "请求参数",
            "type": "String",
            "optional": true,
            "field": "sn",
            "description": "<p>退货单号</p>"
          },
          {
            "group": "请求参数",
            "type": "Int",
            "optional": true,
            "field": "shop_id",
            "description": "<p>退货仓库</p>"
          },
          {
            "group": "请求参数",
            "type": "Int",
            "optional": true,
            "field": "status",
            "description": "<p>调拨状态</p> <ul> <li>1 已出库</li> <li>0 出库中</li> </ul>"
          },
          {
            "group": "请求参数",
            "type": "Int",
            "optional": true,
            "field": "page",
            "description": "<p>页码</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Array",
            "optional": false,
            "field": "reject_list",
            "description": "<p>退货单列表</p>"
          },
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "reject_list.id",
            "description": "<p>退货单ID</p>"
          },
          {
            "group": "返回参数",
            "type": "Int",
            "optional": true,
            "field": "reject_list.shop_id",
            "description": "<p>仓库id</p>"
          },
          {
            "group": "返回参数",
            "type": "String",
            "optional": false,
            "field": "reject_list.sn",
            "description": "<p>退货单编号</p>"
          },
          {
            "group": "返回参数",
            "type": "String",
            "optional": false,
            "field": "reject_list.supplier",
            "description": "<p>供应商</p>"
          },
          {
            "group": "返回参数",
            "type": "String",
            "optional": false,
            "field": "reject_list.refund_shop_name",
            "description": "<p>调出仓库名</p>"
          },
          {
            "group": "返回参数",
            "type": "String",
            "optional": false,
            "field": "reject_list.out_stock_user",
            "description": "<p>操作出库的人员</p>"
          },
          {
            "group": "返回参数",
            "type": "String",
            "optional": false,
            "field": "reject_list.refund_bill_user",
            "description": "<p>填写退货单的人</p>"
          },
          {
            "group": "返回参数",
            "type": "String",
            "optional": false,
            "field": "reject_list.out_stock_time",
            "description": "<p>退货单出库时间</p>"
          },
          {
            "group": "返回参数",
            "type": "String",
            "optional": false,
            "field": "reject_list.addtime",
            "description": "<p>退货单添加时间</p>"
          },
          {
            "group": "返回参数",
            "type": "float",
            "optional": false,
            "field": "reject_list.amount",
            "description": "<p>退货单总金额</p>"
          },
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "reject_list.status",
            "description": "<p>退货状态</p> <ul> <li>1 已出库</li> <li>0 出库中</li> </ul>"
          },
          {
            "group": "返回参数",
            "type": "float",
            "optional": false,
            "field": "reject_list.remark",
            "description": "<p>退货单备注</p>"
          },
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "order_num",
            "description": "<p>单据总笔数</p>"
          },
          {
            "group": "返回参数",
            "type": "Float",
            "optional": false,
            "field": "total_amount",
            "description": "<p>进价总金额</p>"
          },
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "totalPage",
            "description": "<p>总页数</p>"
          },
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "totalNum",
            "description": "<p>总条数</p>"
          },
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "pageSize",
            "description": "<p>每页条数</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":{\"reject_list\":[{\"id\":1,\"shop_id\":22,\"sn\":\"TH201807261606\",\"out_stock_user\":\"杨成一\",\"supplier\":\"供应商1\",\"refund_bill_user\":\"雷雷\",\"out_stock_time\":\"2018-07-27 19:05\",\"remark\":\"测试退货单\",\"amount\":\"12400.00\",\"addtime\":\"2018-07-22 19:28\",\"status\":0,\"refund_shop_name\":\"测试门店10\"}],\"totalPage\":1,\"order_num\":1,\"total_amount\":12400}}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/Allot/get_allot_list"
      }
    ],
    "filename": "../../app/admin/controller/RejectController.php",
    "groupTitle": "退货单管理"
  },
  {
    "type": "Post",
    "url": "/admin/Reject/get_goods_by_code",
    "title": "条形码",
    "group": "退货单管理",
    "name": "get_goods_by_code",
    "version": "1.0.0",
    "description": "<p>根据条形码和店铺ID获取商品信息</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "code",
            "description": "<p>条形码 不可空</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "shop_id",
            "description": "<p>门店ID 不可空</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码 200成功 301错误</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "msg",
            "description": "<p>请求结果 success成功 fail失败</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>返回参数结果集</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.item_id",
            "description": "<p>商品ID</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.title",
            "description": "<p>商品名称</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.cname",
            "description": "<p>商品分类</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.bar_code",
            "description": "<p>商品条形码</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.price",
            "description": "<p>商品原价</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.md_price",
            "description": "<p>商品门店进价</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.cost_price",
            "description": "<p>商品库存单位成本</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.stock",
            "description": "<p>商品库存</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":{\"shop_name\":\"测试门店10\",\"title\":\"测试商品2\",\"price\":\"200.00\",\"bar_code\":\"154854\",\"item_id\":746,\"cname\":\"模型玩具\",\"shop_id\":22,\"stock\":80,\"md_price\":\"120.00\",\"store_cost\":\"879.18\"}}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/Reject/get_goods_by_code"
      }
    ],
    "filename": "../../app/admin/controller/RejectController.php",
    "groupTitle": "退货单管理"
  },
  {
    "type": "POST",
    "url": "/admin/Reject/get_s_cate",
    "title": "获取商品二级分类",
    "group": "退货单管理",
    "name": "get_s_cate",
    "version": "1.0.0",
    "description": "<p>获取商品二级分类</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>一级分类ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码 200成功 301失败</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "msg",
            "description": "<p>请求结果 success成功 fail失败</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>请求结果信息</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>二级分类ID</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "cname",
            "description": "<p>二级分类名</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":[]}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/Reject/get_s_cate"
      }
    ],
    "filename": "../../app/admin/controller/RejectController.php",
    "groupTitle": "退货单管理"
  },
  {
    "type": "POST",
    "url": "/admin/Reject/update",
    "title": "保存退货单信息",
    "group": "退货单管理",
    "name": "save",
    "version": "1.0.0",
    "description": "<p>新增或修改退货单</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "Int",
            "optional": true,
            "field": "reject_id",
            "description": "<p>退货单ID</p>"
          },
          {
            "group": "请求参数",
            "type": "Array",
            "optional": false,
            "field": "item_list",
            "description": "<p>退货单所含商品</p>"
          },
          {
            "group": "请求参数",
            "type": "Int",
            "optional": true,
            "field": "item_list.reject_item_id",
            "description": "<p>反正你传给我就是了 【修改的时候才会有】</p>"
          },
          {
            "group": "请求参数",
            "type": "Int",
            "optional": false,
            "field": "item_list.item_id",
            "description": "<p>商品ID</p>"
          },
          {
            "group": "请求参数",
            "type": "Int",
            "optional": false,
            "field": "item_list.num",
            "description": "<p>数量</p>"
          },
          {
            "group": "请求参数",
            "type": "Float",
            "optional": false,
            "field": "item_list.cost_price",
            "description": "<p>退货时的商品单价</p>"
          },
          {
            "group": "请求参数",
            "type": "String",
            "optional": false,
            "field": "item_list.remark",
            "description": "<p>备注</p>"
          },
          {
            "group": "请求参数",
            "type": "Int",
            "optional": false,
            "field": "shop_id",
            "description": "<p>退货门店（仓库）ID</p>"
          },
          {
            "group": "请求参数",
            "type": "Int",
            "optional": false,
            "field": "supplier_id",
            "description": "<p>供应商ID</p>"
          },
          {
            "group": "请求参数",
            "type": "Int",
            "optional": false,
            "field": "remark",
            "description": "<p>备注</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求样例:",
          "content": "{\"reject_id\":1,\"shop_id\":22,\"supplier\":5,\"reject_item_list\":[{\"reject_item_id\":1,\"item_id\":2,\"num\":50,\"cost_price\":100,\"remark\":\"谁知道呢\"}]}",
          "type": "json"
        },
        {
          "title": "请求样例:",
          "content": "{\"shop_id\":22,\"supplier\":5,\"reject_item_list\":[{\"item_id\":2,\"num\":50,\"cost_price\":100,\"remark\":\"谁知道呢\"}],\"remark\":\"你懂的\"}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":[]}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/Reject/update"
      }
    ],
    "filename": "../../app/admin/controller/RejectController.php",
    "groupTitle": "退货单管理"
  },
  {
    "type": "POST",
    "url": "/admin/Purchase/add",
    "title": "添加采购单时获取初始化数据",
    "group": "采购单管理",
    "name": "add",
    "version": "1.0.0",
    "description": "<p>添加采购单时获取初始化数据</p>",
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码 200成功 301失败</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "msg",
            "description": "<p>请求结果 success成功 fail失败</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果集</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.sn",
            "description": "<p>采购单号</p>"
          },
          {
            "group": "返回参数",
            "type": "Array",
            "optional": false,
            "field": "data.shop_list",
            "description": "<p>所入仓库</p>"
          },
          {
            "group": "返回参数",
            "type": "Array",
            "optional": false,
            "field": "data.supplier_list",
            "description": "<p>供应商列表</p>"
          },
          {
            "group": "返回参数",
            "type": "Array",
            "optional": false,
            "field": "data.admin_name",
            "description": "<p>操作人姓名</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":\"1\",\"msg\":\"\",\"data\":{\"sn\":\"CG201807192\",\"shop_list\":[{\"id\":18,\"name\":\"总店\"},{\"id\":6,\"name\":\"爱琴海店\"},{\"id\":5,\"name\":\"留云路店\"},{\"id\":16,\"name\":\"龙湖源著店\"},{\"id\":17,\"name\":\"重庆城口店\"},{\"id\":15,\"name\":\"珠江太阳城店\"},{\"id\":21,\"name\":\"两江时光店\"},{\"id\":22,\"name\":\"测试门店10\"},{\"id\":25,\"name\":\"约克郡南郡\"},{\"id\":24,\"name\":\"约克郡北郡\"},{\"id\":26,\"name\":\"蓝光COCO店\"},{\"id\":29,\"name\":\"融创金茂店\"},{\"id\":28,\"name\":\"港城国际店\"},{\"id\":27,\"name\":\"江与城店\"},{\"id\":30,\"name\":\"麓山别苑店\"},{\"id\":31,\"name\":\"奥山别墅店\"}],\"supplier_list\":[{\"id\":1,\"name\":\"1\"},{\"id\":2,\"name\":\"11\"},{\"id\":3,\"name\":\"供应商1\"},{\"id\":4,\"name\":\"供应商2\"},{\"id\":5,\"name\":\"供应商3\"}]}}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/Purchase/add"
      }
    ],
    "filename": "../../app/admin/controller/PurchaseController.php",
    "groupTitle": "采购单管理"
  },
  {
    "type": "POST",
    "url": "/admin/Purchase/before_purchase",
    "title": "点击入库时获取采购单详情",
    "group": "采购单管理",
    "name": "before_purchase",
    "version": "1.0.0",
    "description": "<p>点击入库时获取采购单详情</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>采购单ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码 200成功 301失败</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "msg",
            "description": "<p>请求结果 success成功 fail失败</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>请求结果信息</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data.item_list",
            "description": "<p>采购单包含商品</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.item_list.title",
            "description": "<p>商品名</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.item_list.cname",
            "description": "<p>商品分类</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.item_list.bar_code",
            "description": "<p>条形码</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.item_list.num",
            "description": "<p>数量</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.item_list.remark",
            "description": "<p>商品备注</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.item_list.purchase_item_id",
            "description": "<p>采购单商品id(不显示)</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.item_list.item_id",
            "description": "<p>商品id(不显示)</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.purchaser",
            "description": "<p>采购制单人</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.storer",
            "description": "<p>入库人</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.remark",
            "description": "<p>采购单备注</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.time",
            "description": "<p>入库日期</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.purchase_id",
            "description": "<p>采购单ID</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.shop_id",
            "description": "<p>门店（仓库）ID</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":{\"item_list\":[{\"cname\":\"洗发沐浴\",\"title\":\"三鹿三聚氰胺奶粉2段3罐\",\"bar_code\":\"654654\",\"num\":100,\"remark\":\"备注1\",\"purchase_item_id\":1},{\"cname\":\"惠氏\",\"title\":\"惠氏金装婴儿奶粉 3段2罐\",\"bar_code\":\"6615249817\",\"num\":100,\"remark\":\"备注2\",\"purchase_item_id\":2}],\"purchaser\":\"admin\",\"storer\":\"admin\",\"remark\":\"备注4\",\"purchase_id\":1,\"time\":\"2018-07-23\"}}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/Purchase/before_purchase"
      }
    ],
    "filename": "../../app/admin/controller/PurchaseController.php",
    "groupTitle": "采购单管理"
  },
  {
    "type": "POST",
    "url": "/admin/Purchase/delete",
    "title": "删除采购单",
    "group": "采购单管理",
    "name": "delete",
    "version": "1.0.0",
    "description": "<p>删除采购单</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "purchase_id",
            "description": "<p>采购单ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码 200成功 301失败</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":[]}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/Purchase/delete"
      }
    ],
    "filename": "../../app/admin/controller/PurchaseController.php",
    "groupTitle": "采购单管理"
  },
  {
    "type": "POST",
    "url": "/admin/Purchase/editOrView",
    "title": "编辑或查看采购单",
    "group": "采购单管理",
    "name": "editOrView",
    "version": "1.0.0",
    "description": "<p>编辑或查看采购单，data.purchaseInfo.status值为0时可编辑、删除，为1时只可查看</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "purchase_id",
            "description": "<p>采购单ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码 200成功 301失败</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "msg",
            "description": "<p>请求结果 success成功 fail失败</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果集</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data.purchaseInfo",
            "description": "<p>采购单基本数据</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.purchaseInfo.shop_id",
            "description": "<p>所入仓库ID</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.purchaseInfo.id",
            "description": "<p>采购单ID</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.purchaseInfo.sn",
            "description": "<p>采购单号</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.purchaseInfo.time",
            "description": "<p>采购单时间戳</p>"
          },
          {
            "group": "返回参数",
            "type": "float",
            "optional": false,
            "field": "data.purchaseInfo.amount",
            "description": "<p>采购单单据金额</p>"
          },
          {
            "group": "返回参数",
            "type": "float",
            "optional": false,
            "field": "data.purchaseInfo.bid_amount",
            "description": "<p>采购单进价金额</p>"
          },
          {
            "group": "返回参数",
            "type": "float",
            "optional": false,
            "field": "data.purchaseInfo.real_amount",
            "description": "<p>采购单实际金额</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.purchaseInfo.creator",
            "description": "<p>采购制单人</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.purchaseInfo.iner",
            "description": "<p>入库人</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.purchaseInfo.remark",
            "description": "<p>采购单备注</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.purchaseInfo.status",
            "description": "<p>采购单状态 1入库 0未入库</p>"
          },
          {
            "group": "返回参数",
            "type": "Array",
            "optional": false,
            "field": "data.shopDatas",
            "description": "<p>所入仓库</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.shopDatas.id",
            "description": "<p>仓库ID</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.shopDatas.name",
            "description": "<p>仓库名</p>"
          },
          {
            "group": "返回参数",
            "type": "Array",
            "optional": false,
            "field": "data.supplierDatas",
            "description": "<p>供应商列表</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.supplierDatas.id",
            "description": "<p>供应商ID</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.supplierDatas.name",
            "description": "<p>供应商名字</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data.item_list",
            "description": "<p>采购单的商品数据</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.item_list.purchase_item_id",
            "description": "<p>采购单的商品ID</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.item_list.item_id",
            "description": "<p>商品ID</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.item_list.title",
            "description": "<p>商品名</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.item_list.bar_code",
            "description": "<p>商品条形码</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.item_list.cname",
            "description": "<p>分类名</p>"
          },
          {
            "group": "返回参数",
            "type": "float",
            "optional": false,
            "field": "data.item_list.cg_standard_price",
            "description": "<p>采购单价</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.item_list.num",
            "description": "<p>数量</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.item_list.cg_amount",
            "description": "<p>采购金额</p>"
          },
          {
            "group": "返回参数",
            "type": "float",
            "optional": false,
            "field": "data.item_list.md_standard_price",
            "description": "<p>门店单价</p>"
          },
          {
            "group": "返回参数",
            "type": "float",
            "optional": false,
            "field": "data.item_list.md_amount",
            "description": "<p>门店进价</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.item_list.remark",
            "description": "<p>商品备注</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":{\"is_show\":0,\"shopDatas\":[{\"id\":18,\"name\":\"总店\"},{\"id\":6,\"name\":\"爱琴海店\"},{\"id\":5,\"name\":\"留云路店\"},{\"id\":16,\"name\":\"龙湖源著店\"},{\"id\":17,\"name\":\"重庆城口店\"},{\"id\":15,\"name\":\"珠江太阳城店\"},{\"id\":21,\"name\":\"两江时光店\"},{\"id\":22,\"name\":\"测试门店10\"},{\"id\":25,\"name\":\"约克郡南郡\"},{\"id\":24,\"name\":\"约克郡北郡\"},{\"id\":26,\"name\":\"蓝光COCO店\"},{\"id\":29,\"name\":\"融创金茂店\"},{\"id\":28,\"name\":\"港城国际店\"},{\"id\":27,\"name\":\"江与城店\"},{\"id\":30,\"name\":\"麓山别苑店\"},{\"id\":31,\"name\":\"奥山别墅店\"}],\"supplierDatas\":[{\"id\":1,\"name\":\"1\"},{\"id\":2,\"name\":\"11\"},{\"id\":3,\"name\":\"供应商1\"},{\"id\":4,\"name\":\"供应商2\"},{\"id\":5,\"name\":\"供应商3\"}],\"item_list\":[{\"cname\":\"洗发沐浴\",\"title\":\"三鹿三聚氰胺奶粉2段3罐\",\"bar_code\":\"654654\",\"item_id\":2,\"num\":100,\"remark\":\"备注1\",\"purchase_item_id\":1,\"cg_amount\":\"200.0000\",\"md_amount\":\"300.0000\",\"cg_univalent\":\"2.0000\",\"md_univalent\":\"3.0000\",\"amount\":\"400\",\"real_amount\":\"450\"},{\"cname\":\"惠氏\",\"title\":\"惠氏金装婴儿奶粉 3段2罐\",\"bar_code\":\"6615249817\",\"item_id\":3,\"num\":100,\"remark\":\"备注2\",\"purchase_item_id\":2,\"cg_amount\":\"200.0000\",\"md_amount\":\"300.0000\",\"cg_univalent\":\"2.0000\",\"md_univalent\":\"3.0000\",\"amount\":\"400\",\"real_amount\":\"450\"}],\"purchaseInfo\":{\"shop_id\":6,\"supplier_id\":1,\"sn\":\"CG201807190001\",\"time\":1531929600,\"amount\":\"400\",\"bid_amount\":\"600\",\"real_amount\":\"450\",\"remark\":\"备注4\",\"purchase_admin_id\":1,\"creator\":\"admin\"}}}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/Purchase/editOrView"
      }
    ],
    "filename": "../../app/admin/controller/PurchaseController.php",
    "groupTitle": "采购单管理"
  },
  {
    "type": "GET",
    "url": "/admin/MemberGoods/getPage",
    "title": "ajax获取分页商品数据",
    "group": "采购单管理",
    "name": "getPage",
    "version": "1.0.0",
    "description": "<p>ajax获取分页商品数据</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "curPage",
            "description": "<p>页数 不可空</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "goods_name",
            "description": "<p>商品名称 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "f_cate",
            "description": "<p>一级分类ID 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "s_cate",
            "description": "<p>二级分类ID 可空</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码 200成功 301错误</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "msg",
            "description": "<p>请求结果 success成功 fail失败</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>返回参数结果集</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.pageSize",
            "description": "<p>每页记录条数</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.totalItem",
            "description": "<p>总记录条数</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.totalPage",
            "description": "<p>总页数</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data.f_list",
            "description": "<p>一级分类列表</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data.data_content",
            "description": "<p>商品数据</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.data_content.id",
            "description": "<p>商品ID</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.data_content.title",
            "description": "<p>商品名称</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.data_content.cname",
            "description": "<p>商品分类</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.data_content.bar_code",
            "description": "<p>商品条形码</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.data_content.price",
            "description": "<p>商品原价</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.data_content.is_price_control",
            "description": "<p>是否控价 1是 0不是</p>"
          },
          {
            "group": "返回参数",
            "type": "float",
            "optional": false,
            "field": "data.data_content.cg_standard_price",
            "description": "<p>采购单价</p>"
          },
          {
            "group": "返回参数",
            "type": "float",
            "optional": false,
            "field": "data.data_content.md_standard_price",
            "description": "<p>门店单价</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":\"1\",\"msg\":\"成功\",\"time\":1532401296,\"data\":{\"totalItem\":2,\"pageSize\":10,\"totalPage\":1,\"data_content\":[{\"id\":3,\"title\":\"惠氏金装婴儿奶粉 3段2罐\",\"cname\":\"惠氏\",\"is_price_control\":0,\"price\":\"268.00\",\"bar_code\":\"6615249817\",\"cg_standard_price\":\"210.00\",\"md_standard_price\":\"258.00\"},{\"id\":4,\"title\":\"惠氏启赋金装二段2罐装\",\"cname\":\"惠氏\",\"is_price_control\":0,\"price\":\"359.00\",\"bar_code\":\"1231431232\",\"cg_standard_price\":\"309.00\",\"md_standard_price\":\"339.00\"}]}}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/MemberGoods/getPage"
      }
    ],
    "filename": "../../app/admin/controller/MemberGoodsController.php",
    "groupTitle": "采购单管理"
  },
  {
    "type": "POST",
    "url": "/admin/Purchase/get_goods_by_code",
    "title": "根据商品二维码获取商品信息",
    "group": "采购单管理",
    "name": "get_goods_by_code",
    "version": "1.0.0",
    "description": "<p>根据商品二维码获取商品信息</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "bar_code",
            "description": "<p>商品条形码</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码 200成功 301失败</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "msg",
            "description": "<p>请求结果 success成功 fail失败</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>请求结果信息</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data.item_info",
            "description": "<p>商品数据</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.item_info.id",
            "description": "<p>商品ID</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.item_info.title",
            "description": "<p>商品名称</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.item_info.cname",
            "description": "<p>商品分类</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.item_info.bar_code",
            "description": "<p>商品条形码</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.item_info.price",
            "description": "<p>商品原价</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.item_info.is_price_control",
            "description": "<p>是否控价 1是 0不是</p>"
          },
          {
            "group": "返回参数",
            "type": "float",
            "optional": false,
            "field": "data.item_info.cg_standard_price",
            "description": "<p>采购单价</p>"
          },
          {
            "group": "返回参数",
            "type": "float",
            "optional": false,
            "field": "data.item_info.md_standard_price",
            "description": "<p>门店单价</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":{\"item_info\":{\"id\":4,\"title\":\"惠氏启赋金装二段2罐装\",\"cname\":\"惠氏\",\"is_price_control\":0,\"price\":\"359.00\",\"bar_code\":\"1231431232\",\"cg_standard_price\":\"309.00\",\"md_standard_price\":\"339.00\"}}}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/Purchase/get_goods_by_code"
      }
    ],
    "filename": "../../app/admin/controller/PurchaseController.php",
    "groupTitle": "采购单管理"
  },
  {
    "type": "POST",
    "url": "/admin/Purchase/get_purchase_list",
    "title": "采购单列表",
    "group": "采购单管理",
    "name": "get_purchase_list",
    "version": "1.0.0",
    "description": "<p>采购单列表</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "time",
            "description": "<p>采购单时间 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "item_name",
            "description": "<p>商品名称 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "sn",
            "description": "<p>采购单号 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "shop_id",
            "description": "<p>所入仓库 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "supplier_id",
            "description": "<p>供应商ID 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "status",
            "description": "<p>入库状态 1已入库 0未入库 可空</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页码 可空</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "msg",
            "description": "<p>请求结果 success成功 fail失败</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>返回结果集</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.totalPage",
            "description": "<p>总页数</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.totalNum",
            "description": "<p>总记录条数</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.pageSize",
            "description": "<p>每页记录条数</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data.shop_list",
            "description": "<p>仓库列表</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.shop_list.id",
            "description": "<p>仓库ID</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.shop_list.name",
            "description": "<p>仓库名</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data.supplier_list",
            "description": "<p>供应商列表</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.supplier_list.id",
            "description": "<p>供应商ID</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.supplier_list.name",
            "description": "<p>供应商名</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data.purchase_list",
            "description": "<p>采购单列表</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.purchase_list.id",
            "description": "<p>采购单ID</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.purchase_list.shop_id",
            "description": "<p>仓库ID</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.purchase_list.sn",
            "description": "<p>采购单编号</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.purchase_list.supplier_id",
            "description": "<p>供应商ID</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.purchase_list.status",
            "description": "<p>采购单状态 1已入库 0未入库</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.purchase_list.creator",
            "description": "<p>采购制单人</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data.purchase_list.storer",
            "description": "<p>入库人 （status为0时为空）</p>"
          },
          {
            "group": "返回参数",
            "type": "float",
            "optional": false,
            "field": "data.purchase_list.amount",
            "description": "<p>单据金额</p>"
          },
          {
            "group": "返回参数",
            "type": "float",
            "optional": false,
            "field": "data.purchase_list.bid_amount",
            "description": "<p>进价金额</p>"
          },
          {
            "group": "返回参数",
            "type": "float",
            "optional": false,
            "field": "data.purchase_list.real_amount",
            "description": "<p>实际金额</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.purchase_list.store_time",
            "description": "<p>入库时间（status为0时为空）</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "data.purchase_list.time",
            "description": "<p>采购单时间</p>"
          },
          {
            "group": "返回参数",
            "type": "float",
            "optional": false,
            "field": "data.purchase_amount_all",
            "description": "<p>单据总金额</p>"
          },
          {
            "group": "返回参数",
            "type": "float",
            "optional": false,
            "field": "data.purchase_bid_amount_all",
            "description": "<p>进价总金额</p>"
          },
          {
            "group": "返回参数",
            "type": "float",
            "optional": false,
            "field": "data.real_amount_all",
            "description": "<p>实际总金额</p>"
          },
          {
            "group": "返回参数",
            "type": "float",
            "optional": false,
            "field": "data.is_show",
            "description": "<p>是否显示金额相关数据 1是 0否</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":1,\"msg\":\"success\",\"data\":{\"shop_list\":[{\"id\":18,\"name\":\"总店\"},{\"id\":6,\"name\":\"爱琴海店\"},{\"id\":5,\"name\":\"留云路店\"},{\"id\":16,\"name\":\"龙湖源著店\"},{\"id\":17,\"name\":\"重庆城口店\"},{\"id\":15,\"name\":\"珠江太阳城店\"},{\"id\":21,\"name\":\"两江时光店\"},{\"id\":22,\"name\":\"测试门店10\"},{\"id\":25,\"name\":\"约克郡南郡\"},{\"id\":24,\"name\":\"约克郡北郡\"},{\"id\":26,\"name\":\"蓝光COCO店\"},{\"id\":29,\"name\":\"融创金茂店\"},{\"id\":28,\"name\":\"港城国际店\"},{\"id\":27,\"name\":\"江与城店\"},{\"id\":30,\"name\":\"麓山别苑店\"},{\"id\":31,\"name\":\"奥山别墅店\"}],\"supplier_list\":[{\"id\":1,\"name\":\"1\"},{\"id\":2,\"name\":\"11\"},{\"id\":3,\"name\":\"供应商1\"},{\"id\":4,\"name\":\"供应商2\"},{\"id\":5,\"name\":\"供应商3\"}],\"purchase_list\":[{\"id\":1,\"shop_id\":6,\"sn\":\"CG201807190001\",\"supplier_id\":3,\"purchase_admin_id\":21,\"store_admin_id\":38,\"status\":0,\"store_time\":0,\"amount\":\"100\",\"bid_amount\":\"100\",\"real_amount\":\"100\",\"time\":0,\"creator\":\"糖糖\",\"storer\":\"杨呈怡\"}],\"purchase_list_num\":1,\"purchase_amount_all\":100,\"purchase_bid_amount_all\":100,\"real_amount_all\":100}}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/Purchase/get_purchase_list"
      }
    ],
    "filename": "../../app/admin/controller/PurchaseController.php",
    "groupTitle": "采购单管理"
  },
  {
    "type": "POST",
    "url": "/admin/Purchase/get_s_cate",
    "title": "获取商品二级分类",
    "group": "采购单管理",
    "name": "get_s_cate",
    "version": "1.0.0",
    "description": "<p>获取商品二级分类</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>一级分类ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码 200成功 301失败</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "msg",
            "description": "<p>请求结果 success成功 fail失败</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>请求结果信息</p>"
          },
          {
            "group": "返回参数",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>二级分类ID</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "cname",
            "description": "<p>二级分类名</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":[]}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/Purchase/delete"
      }
    ],
    "filename": "../../app/admin/controller/PurchaseController.php",
    "groupTitle": "采购单管理"
  },
  {
    "type": "POST",
    "url": "/admin/Purchase/purchase_save",
    "title": "确定入库",
    "group": "采购单管理",
    "name": "purchase_save",
    "version": "1.0.0",
    "description": "<p>确定入库,前端判断用户输入的本次数量和总数量相等才允许提交</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "shop_id",
            "description": "<p>店铺（仓库ID）</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "purchase_id",
            "description": "<p>采购单ID</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":[]}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/Purchase/purchase_save"
      }
    ],
    "filename": "../../app/admin/controller/PurchaseController.php",
    "groupTitle": "采购单管理"
  },
  {
    "type": "POST",
    "url": "/admin/Purchase/reverse_purchase",
    "title": "反入库",
    "group": "采购单管理",
    "name": "reverse_purchase",
    "version": "1.0.0",
    "description": "<p>操作有误时采购单反入库，重算成本价</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "purchase_id",
            "description": "<p>采购单ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码 200成功 301失败</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "msg",
            "description": "<p>请求结果 success成功 fail失败</p>"
          },
          {
            "group": "返回参数",
            "type": "array",
            "optional": false,
            "field": "data",
            "description": "<p>请求结果信息</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":[]}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/Purchase/reverse_purchase"
      }
    ],
    "filename": "../../app/admin/controller/PurchaseController.php",
    "groupTitle": "采购单管理"
  },
  {
    "type": "POST",
    "url": "/admin/Purchase/save",
    "title": "保存采购单信息",
    "group": "采购单管理",
    "name": "save",
    "version": "1.0.0",
    "description": "<p>保存采购单信息，有id时为修改，无id时新增</p>",
    "parameter": {
      "fields": {
        "请求参数": [
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>采购单ID，可以为空</p>"
          },
          {
            "group": "请求参数",
            "type": "array",
            "optional": false,
            "field": "item_list",
            "description": "<p>采购单所含商品</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "item_list.item_id",
            "description": "<p>商品ID</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "item_list.num",
            "description": "<p>商品数量</p>"
          },
          {
            "group": "请求参数",
            "type": "float",
            "optional": false,
            "field": "item_list.cg_univalent",
            "description": "<p>商品采购单价</p>"
          },
          {
            "group": "请求参数",
            "type": "float",
            "optional": false,
            "field": "item_list.md_univalent",
            "description": "<p>商品门店单价</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "item_list.remark",
            "description": "<p>商品备注</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "supplier_id",
            "description": "<p>供应商ID</p>"
          },
          {
            "group": "请求参数",
            "type": "int",
            "optional": false,
            "field": "shop_id",
            "description": "<p>门店ID</p>"
          },
          {
            "group": "请求参数",
            "type": "str",
            "optional": false,
            "field": "sn",
            "description": "<p>采购单编号</p>"
          },
          {
            "group": "请求参数",
            "type": "float",
            "optional": false,
            "field": "amount",
            "description": "<p>采购单总单据金额</p>"
          },
          {
            "group": "请求参数",
            "type": "float",
            "optional": false,
            "field": "bid_amount",
            "description": "<p>采购单总进价金额</p>"
          },
          {
            "group": "请求参数",
            "type": "float",
            "optional": false,
            "field": "real_amount",
            "description": "<p>采购单实际金额</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "返回参数": [
          {
            "group": "返回参数",
            "type": "Int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码 200成功 301失败</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "msg",
            "description": "<p>请求结果 success成功 fail失败</p>"
          },
          {
            "group": "返回参数",
            "type": "str",
            "optional": false,
            "field": "data",
            "description": "<p>请求结果信息</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "返回样例:",
          "content": "{\"code\":200,\"msg\":\"success\",\"data\":[]}",
          "type": "json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/admin/Purchase/save"
      }
    ],
    "filename": "../../app/admin/controller/PurchaseController.php",
    "groupTitle": "采购单管理"
  }
] });
