<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\admin\model;

use think\Model;
use think\Cache;
use think\Db;
use app\wechat\ItemModel;

class ApiModel extends Model
{
    //获取商品详情 
    public function getGoodsInfo($id = 0)
    {
        // $info = Db::table('tf_item_attr')
        //         ->where('id','=',$id)
        //         ->field('id,price,title,')
    }


    /**
     * 获取商品列表
     * @param  string $cate_list [分类组字符串]
     * @return [array]            [array]
     */
    public function getGoodsList($cate_list = '',$page = 0)
    {
        $goodslist = [];
        $goodslist = Db::table('tf_item_attr')
            ->alias('a')
            ->join('tf_item b','a.item_id = b.id')
            ->join('tf_item_category c','c.id = b.type')
            ->field('a.id,a.attr_pic,a.attr_name,a.attr_names,a.price,b.title,c.cname,b.addtime')
            ->where('b.type','in',$cate_list)
            ->where(['a.status'=>1,'b.status'=>1])
            ->limit($page * 16,16)
            ->select();
        if (!empty($goodslist)) {
            foreach ($goodslist as $k => $v) {
                $goodslist[$k]['attr_pic'] = config('upload.file_host').$v['attr_pic'];
            }
            return $goodslist;
        }
        return false;
    }
}
