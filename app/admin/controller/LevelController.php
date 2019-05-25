<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/10
 * Time: 11:53
 */

namespace app\admin\controller;




use app\admin\model\LevelModel;
use cmf\controller\AdminBaseController;
use think\Session;

class LevelController extends AdminBaseController
{

    /*
     * 等级列表
     */
    public function Index(){
        // $shop_id = Session::get('SHOP_ID');
        $model = new LevelModel();
        $lists = $model->get_level_list();
        $this->assign('lists',$lists);
        return $this->fetch('level_list');
    }

    /*
     * 修改等级
     */
    public function Edit(){
        $params = input('post.');
        $model = new LevelModel();
        $result = $model->edit_level($params['id'],$params['value']);
        if($result){
            return outPut(1,'修改成功');
        }else{
            return outPut(0,'修改失败');
        }
    }

    /*
     * 删除等级
     */
    public function Delete(){
        $id = $this->request->param("id", 0, 'intval');
        if(!$id){
            return outPut(0,'删除失败，缺少参数请重试');
        }
        $model = new LevelModel();
        $result = $model->delete_level($id);
        if($result!=1){
            $this->error($result);
        }else{
            $this->success("删除成功！", url('Level/Index'));
        }
    }

    /*
     * 添加等级
     */
    public function Add(){
        return $this->fetch('level_add');
    }

    /*
     * 保存等级
     */
    public function LevelSave(){
        $params = input('post.');
        $model = new LevelModel();
        $result = $model->add_level($params['title']);
        if(!$result){
            $this->error($result);
        }else{
            $this->success("添加成功！", url('Level/Index'));
        }
    }

}
