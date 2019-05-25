<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/10
 * Time: 17:26
 */

namespace app\admin\model;


use think\Db;

class LevelModel extends BaseModel
{
    protected $table = 'tf_level';
    protected $tableName = 'level';
    /*
     * 获取等级列表
     */
    public function get_level_list(){
        $db = Db::connect(config('ddxx'));
        $list = $db->name('level')->where('status',1)->order('sort asc')->select();
        return $list;
    }

    /**
     * 根据等级ID获取等级信息
     * @param $level_id等级ID
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function get_one($level_id){
        $db = Db::connect(config('ddxx'));
        $list = $db->name('level')->where(['id'=>$level_id,'status'=>1])->find();
        return $list;
    }

    /*
     * 编辑等级
     */
    public function edit_level($id,$value){
        $db = Db::connect(config('ddxx'));
        if($id!=0&&is_numeric($id)){
            $level_info = $db->name('level')->where('id',$id)->find();
            if (!empty($level_info)) {
                if ($db->name('level')->where('id',$id)->update(['level_name'=>$value,'add_time'=>time()])) {
                    return 1;
                }else{
                    return 0;
                }
            }
        }
    }

    /*
     * 删除等级，把等级状态改为失效
     */
    public function delete_level($id){
        $db = Db::connect(config('ddxx'));
        if ($db->name('level')->where('id',$id)->update(['status'=>0])) {
            return 1;
        }else{
            return 0;
        }
    }


    public function add_level($value){
        $db = Db::connect(config('ddxx'));
        $last_sort = $db->name('level')->field('sort')->order('sort desc')->find();
        if ($db->name('level')->insert(['level_name'=>$value,'add_time'=>time(),'status'=>1,'sort'=>$last_sort['sort']+1])) {
            return 1;
        }else{
            return 0;
        }
    }

}