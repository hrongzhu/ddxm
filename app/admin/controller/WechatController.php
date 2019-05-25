<?php
/**
 * Author: chenjing
 * Date: 2017/12/21
 * Description:
 */

namespace app\admin\controller;
use app\admin\service\WechatService;
use think\Db;

/**
 * Class WechatController
 * @package app\admin\controller
 * @adminMenuRoot(
 *     'name'   =>'微信模块管理',
 *     'action' =>'menuDefault',
 *     'parent' =>'',
 *     'display'=> true,
 *     'order'  => 10000,
 *     'icon'   =>'cogs',
 *     'remark' =>'微信模块管理'
 * )
 */
class WechatController extends BaseController
{
    /**
     * 微信菜单
     * @adminMenu(
     *     'name'   => '微信菜单',
     *     'parent' => 'menuDefault',
     *     'display'=> true,
     *	   'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '微信菜单',
     *     'param'  => ''
     * )
     */
     public function showMenu()
     {
        $refresh = $this->request->param('refresh',0);
        $db = Db::connect(config('ddxx'));
        $menuList = $db->name('wechat_data')->where('type','=','menu')->find();
        $menu = $menuList['content'];
        if (empty($menuList)) {
            $menuList = json_decode($this->getmenu());
            $menuList = $menuList->menu->button;
            foreach ($menuList as $k => &$v) {
                if (isset($v->sub_button)) {
                    if (empty($v->sub_button)) {
                        unset($v->sub_button);
                    }
                }
            }
            $menu = json_encode($menuList,JSON_UNESCAPED_UNICODE);
            $db->name('wechat_data')->insert(['type'=>'menu','content'=>$menu,'status'=>1,'addtime'=>time()]);
        }elseif (empty($menuList['content'])) {
            $menuList = json_decode($this->getmenu());
            $menuList = $menuList->menu->button;
            foreach ($menuList as $k => &$v) {
                if (isset($v->sub_button)) {
                    if (empty($v->sub_button)) {
                        unset($v->sub_button);
                    }
                }
            }
            $menu = json_encode($menuList,JSON_UNESCAPED_UNICODE);
            $db->name('wechat_data')->where(['type'=>'menu'])->update(['content'=>$menu]);
        }
        if ($refresh == 1) {
            $menuList = json_decode($this->getmenu());
            $menuList = $menuList->menu->button;
            foreach ($menuList as $k => &$v) {
                if (isset($v->sub_button)) {
                    if (empty($v->sub_button)) {
                        unset($v->sub_button);
                    }
                }
            }
            $menu = json_encode($menuList,JSON_UNESCAPED_UNICODE);
            $db->name('wechat_data')->where(['type'=>'menu'])->update(['content'=>$menu]);
        }
        $this->view->assign('menu', (array)json_decode($menu, TRUE));
        return view('menu1');
     }

     public function update()
     {
         $menu = $this->request->param();
         $menuList = json_encode($menu['menu'], JSON_UNESCAPED_UNICODE);
         $db = Db::connect(config('ddxx'));
         $res = $db->name('wechat_data')->where(['type'=>'menu'])->update(['content'=>$menuList]);
         if($res){
             return json(['code'=>200]);
         }else{
             return json(['code'=>301]);
         }
     }

     //获取设置的菜单
     public function getmenu(){
         $token = get_access_token();
         $url = 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token='.$token;
         return $result = curl_request($url);
     }

     /**
      * 同步
      */
     public function sync($ids = NULL)
     {
         try {
             $hasError = false;
             $db = Db::connect(config('ddxx'));
             $menu = $db->name('wechat_data')->where(['type'=>'menu'])->value('content');
             $menu = json_decode($menu, TRUE);
             foreach ($menu as $k => $v) {
                 if (isset($v['sub_button'])) {
                     foreach ($v['sub_button'] as $m => $n) {
                         if (isset($n['key']) && !$n['key']) {
                             $hasError = true;
                             break 2;
                         }
                     }
                 } else if (isset($v['key']) && !$v['key']) {
                     $hasError = true;
                     break;
                 }
             }
             if (!$hasError) {
                 $token = get_access_token();
                 // 设置菜单的url接口
                 $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$token;
                 $menuJson = ['button' => $menu];
                 $ret = curl_request($url,"post", json_encode($menuJson,JSON_UNESCAPED_UNICODE));
                 $ret = json_decode($ret,true);
                 if ($ret['errcode'] == 0) {
                     return json(['code'=>200,'msg'=>'菜单更新成功']);
                 } else {
                     return json(['code'=>301,'msg'=>$ret['errmsg']]);
                 }
             } else {
                 return json(['code'=>301,'msg'=>'无效的参数']);
             }
         } catch (Exception $e) {
             return json(['code'=>301,'msg'=>$e->getMessage()]);
         }
     }


     //设置自定义菜单
     public function actionSetmenu()
     {

         $model = new Menu();
         // 获取token
         $token = Yii::$app->getSecurity()->decryptByPassword(Yii::$app->request->get('token'),Yii::$app->params['secretKey']);
         // 设置菜单的url接口
         $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$token;
         $jsondata = Yii::$app->request->post();
         $data = $jsondata['menu'];
         // var_dump($data);
         // 处理接受的数据
         $dataAll = [];
         // 循环外层菜单
         $i=0;
         foreach($data as $v){
             // 如果子菜单数量不为零
             $data2 = $data[$i]['class'];
             if (count($data2) > 0) {
                 $j=0;
                 foreach($data2 as $vv){
                     // 调用方法处理
                     $dataChild[$j] = $model->firstType($data2,$vv);
                    $j++;
                 }
                 $dataAll[$i] = [
                     'name' => $data[$i]['title'],
                     'sub_button' => array_values($dataChild)
                 ];
             }else {
                 $dataAll[$i] = $model->firstType($data,$v);
             }
             $i++;
         }
         // 组装菜单数组
         $dataAll = array_values($dataAll);
         $menu = [
             "button"=>$dataAll
         ];
         $menujson = json_encode($menu,JSON_UNESCAPED_UNICODE);
         // 使用curl发送请求
         $result = $this->curl_request($url,"post", $menujson);
         $result = json_decode($result,true);
         if ($result['errcode'] == 0) {
             print_r(json_encode(array('error_code'=>0,'error_msg'=>"菜单保存成功","datas"=>$result)));
         }elseif($result['errcode'] == 40001){
              print_r(json_encode(array('error_code'=>40001,'error_msg'=>"授权失效了,请刷新页面重试","datas"=>$result)));
         }else {
              print_r(json_encode(array('error_code'=>40015,'error_msg'=>"未知错误","datas"=>$result)));
         }
     }
}
