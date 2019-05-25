<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/23
 * Time: 9:44
 */

namespace app\admin\model;


use think\Db;

class MemberGoodsModel extends BaseModel
{

    public function get_member_goods_list($where){
        $db = Db::connect('ddxx');
        $list =  $db->name('member_goods')->where($where)->select();
        $levels = '';
        foreach ($list as $k=>$v){
            $level_include = json_decode($v['level_include']);
            foreach ($level_include as $kk=>$vv){
                $level = (LevelModel::get($vv))->level_name;
                $levels.=$level.'、';
            }
            $goods_info = count(json_decode($v['goods_info'],true));
            $list[$k]['level_include']= $levels;
            $list[$k]['count'] = $goods_info;
        }
        return $list;
    }

    //获取商品数据
    public function ajax_get_goods_list($where){
        $db = Db::connect('ddxx');
        $list = $db->name('item')->alias('a')
            ->join('tf_item_category c','a.type=c.id','LEFT')
            ->where($where)
            ->field('a.id,a.title,c.cname,a.is_price_control,a.price')
            ->select();
        return $list;
    }

    //获取会员商品列表
    public function ajax_get_member_goods_list($where){
        $db = Db::connect('ddxx');
        $newWhere = [];
        $list = '';
        //有include_level的情况单独处理
        if(!isset($where['include_level'])){
            if(!isset($where['status'])){
               $list = $db->name('member_goods')->where($where)->select();
            }else{
                if(isset($where['title'])){
                    $newWhere['title'] = $where['title'];
                }
                switch ($where['status']){
                    case '0'://已结束
                        $newWhere = array_merge($newWhere,['end_time'=>["lt",time()]]);
                        break;
                    case '1'://进行中
                        $newWhere = array_merge($newWhere,['begin_time'=>["lt",time()]]);
                        $newWhere = array_merge($newWhere,['end_time'=>["egt",time()]]);
                        break;
                    case '2'://未开始
                        $newWhere = array_merge($newWhere,["begin_time"=>["gt",time()]]);
                        break;
                }
                $list = $db->name('member_goods')->where($newWhere)->select();
            }
        }
        if(isset($where['include_level'])){
            if(!isset($where['status'])){
                $list = $db->name('member_goods')->where($where)->select();
            }else{
                if(isset($where['title'])){
                    $newWhere['title'] = $where['title'];
                }
                switch ($where['status']){
                    case '0'://已结束
                        $newWhere = array_merge($newWhere,['end_time'=>["lt",time()]]);
                        break;
                    case '1'://进行中
                        $newWhere = array_merge($newWhere,['begin_time'=>["lt",time()]]);
                        $newWhere = array_merge($newWhere,['end_time'=>["egt",time()]]);
                        break;
                    case '2'://未开始
                        $newWhere = array_merge($newWhere,["begin_time"=>["gt",time()]]);
                        break;
                }
                $list = $db->name('member_goods')->where($newWhere)->select();
            }
            $include_level = $where['include_level'];
            foreach ($include_level as $k=>$v){
                foreach ($list as $kk=>$vv){
                    $level_include = json_decode($vv['level_include'],true);
                    if(!in_array($v,$level_include)){
                        unset($list[$kk]);
                    }
                }
            }
        }
        $levels = '';
        foreach ($list as $k=>$v){
            $level_include = json_decode($v['level_include']);
            foreach ($level_include as $kk=>$vv){
                $level = (LevelModel::get($vv))->level_name;
                $levels.=$level.'、';
            }
            $goods_info = count(json_decode($v['goods_info'],true));
            $list[$k]['level_include']= $levels;
            $list[$k]['count'] = $goods_info;
        }
        return $list;
    }

    //分页获取商品数据
    public function ajax_get_goods_list_limit($where,$startItem,$pageSize){
        $db = Db::connect('ddxx');
        $list = $db->name('item')->alias('a')
            ->join('tf_item_category c','a.type=c.id','LEFT')
            ->where($where)
            ->field('a.id,a.id as item_id,a.title,c.cname,a.is_price_control,a.price,a.bar_code,a.cg_standard_price,a.md_standard_price')
            ->limit($startItem,$pageSize)
            ->select();
        return $list;
    }

    //分页获取会员商品数据
    public function ajax_get_member_goods_list_limit($where,$startItem,$pageSize){
        $db = Db::connect('ddxx');
        $newWhere = [];
        $list = '';
        //有include_level的情况单独处理
        if(!isset($where['include_level'])){
            if(!isset($where['status'])){
                $list = $db->name('member_goods')->where($where)->limit($startItem,$pageSize)->select();
            }else{
                if(isset($where['title'])){
                    $newWhere['title'] = $where['title'];
                }
                switch ($where['status']){
                    case '0'://已结束
                        $newWhere = array_merge($newWhere,['end_time'=>["lt",time()]]);
                        break;
                    case '1'://进行中
                        $newWhere = array_merge($newWhere,['begin_time'=>["lt",time()]]);
                        $newWhere = array_merge($newWhere,['end_time'=>["egt",time()]]);
                        break;
                    case '2'://未开始
                        $newWhere = array_merge($newWhere,["begin_time"=>["gt",time()]]);
                        break;
                }
                $list = $db->name('member_goods')->where($newWhere)->limit($startItem,$pageSize)->select();
            }
        }
        if(isset($where['include_level'])){
            if(!isset($where['status'])){
                $list = $db->name('member_goods')->where($where)->limit($startItem,$pageSize)->select();
            }else{
                if(isset($where['title'])){
                    $newWhere['title'] = $where['title'];
                }
                switch ($where['status']){
                    case '0'://已结束
                        $newWhere = array_merge($newWhere,['end_time'=>["lt",time()]]);
                        break;
                    case '1'://进行中
                        $newWhere = array_merge($newWhere,['begin_time'=>["lt",time()]]);
                        $newWhere = array_merge($newWhere,['end_time'=>["egt",time()]]);
                        break;
                    case '2'://未开始
                        $newWhere = array_merge($newWhere,["begin_time"=>["gt",time()]]);
                        break;
                }
                $list = $db->name('member_goods')->where($newWhere)->limit($startItem,$pageSize)->select();
            }
            $include_level = $where['include_level'];
            foreach ($include_level as $k=>$v){
                foreach ($list as $kk=>$vv){
                    $level_include = json_decode($vv['level_include'],true);
                    if(!in_array($v,$level_include)){
                        unset($list[$kk]);
                    }
                }
            }
        }
        foreach ($list as $k=>$v){
            $level_include = json_decode($v['level_include']);
            $levels = '';
            foreach ($level_include as $kk=>$vv){
                $level = (LevelModel::get($vv))->level_name;
                $levels.=$level.'、';
            }
            $goods_info = count(json_decode($v['goods_info'],true));
            $list[$k]['level_include']= $levels;
            $list[$k]['count'] = $goods_info;
            $list[$k]['begin_time'] = date("Y-m-d H:i:s",$v['begin_time']);
            $list[$k]['end_time'] = date("Y-m-d H:i:s",$v['end_time']);
            if($v['begin_time']>time()){
                $list[$k]['activity_status'] = '未开始';
            }
            if($v['begin_time']<=time()&&$v['end_time']>time()){
                $list[$k]['activity_status'] = '进行中';
            }
            if ($v['end_time']<time()) {
                $list[$k]['activity_status'] = '已结束';
            }
            if($v['status']==1){
                $list[$k]['status'] = '启用';
            }
            if($v['status']==0){
                $list[$k]['status'] = '禁用';
            }
        }
        return $list;
    }

    //保存会员商品信息
    public function save_goods($data){
        $db = Db::connect('ddxx');
        $insert_data = [
            'no'=>date("Ymd",time()).rand(1,99),
            'title'=>$data['title'],
            'begin_time'=>strtotime($data['beginTime']),
            'end_time'=>strtotime($data['endTime']),
            'status'=>$data['status'],
            'level_include'=>htmlspecialchars_decode($data['include_level']),
            'pay_mode'=>htmlspecialchars_decode($data['pay_mode']),
            'goods_info'=>htmlspecialchars_decode($data['goods_info']),
            'add_time'=>time()
        ];
        return $db->name('member_goods')->insert($insert_data);
    }

    public function update_member_goods($id,$data){
        $db = Db::connect('ddxx');
        $update_data = [
            'title'=>$data['title'],
            'begin_time'=>strtotime($data['beginTime']),
            'end_time'=>strtotime($data['endTime']),
            'status'=>$data['status'],
            'level_include'=>htmlspecialchars_decode($data['include_level']),
            'pay_mode'=>htmlspecialchars_decode($data['pay_mode']),
            'goods_info'=>htmlspecialchars_decode($data['goods_info']),
            'add_time'=>time()
        ];
        return $db->name('member_goods')->where(['id'=>$id])->update($update_data);
    }

    public function get_member_goods($id)
    {
        $goods = [];
        $db = Db::connect('ddxx');
        $data = $db->name('member_goods')->where(['id'=>$id])->find();
        $data['level_include'] = json_decode($data['level_include'],true);
        $data['pay_mode'] = json_decode($data['pay_mode'],true);
        $goods_info = json_decode($data['goods_info'],true);
        $level_list = $db->name('level')->field('id,level_name')->order('sort asc')->select();
        foreach ($goods_info as $k=>$v){
            $tmp = [];
            $item = [];
            $item_info = $db->name('item')->where(['id'=>$k])->field('title,type,price')->find();
            $category = $db->name('item_category')->where(['id'=>$item_info['type']])->field('cname')->find();
            $tmp['name'] = $item_info['title'];
            $tmp['type'] = $item_info['type'];
            $tmp['type_name'] = $category['cname'];
            $tmp['oldprice'] = $item_info['price'];
            foreach ($v as $kk=>$vv){
                foreach ($level_list as $kkk=>$vvv){
                    if($kk==$vvv['id']){
                        $item[$kk]['level_name'] = $vvv['level_name'];
                        $item[$kk]['price'] = $vv;
                    }
                }
            }
            $tmp['level_price'] = $item;
            $goods[$k] = $tmp;
        }
        $data['goods_info'] = $goods;
        return $data;
    }

}
