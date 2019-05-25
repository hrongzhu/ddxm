<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 小夏 < 449134904@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\common\Logs;
use cmf\controller\AdminBaseController;
use app\admin\common\upload as uploadService;

class BaseController extends AdminBaseController
{
    //公共的显示页面
    public function menuDefault()
    {
        echo '欢迎光临';
    }

    protected $templatePrefix = '';
    /**
     * 重写fetch方法
     * @param string $template
     * @param array $vars
     * @param array $replace
     * @param array $config
     * @return mixed
     */
    protected function fetch($template = '', $vars = [], $replace = [], $config = [])
    {
        $template = $this->templatePrefix.''.$template;
        return parent::fetch($template, $vars, $replace, $config); // TODO: Change the autogenerated stub
    }

    /**
     * @param $model 是使用model查询数据时,不使用select(),find()方法的对象  比如OrderModel中的getOrderListDatas方法返回的数据
     * @param array $query 翻页时 需要带上的参数
     * @return bool
     */
    protected function lists($model, $query = [])
    {
        if (!is_object($model)) {
            return false;
        }
        if(empty($query))
            $query = [];

        $list = $model->paginate($this->pageLimit, false, ['query'=>$query]);


        //  分页样式
        $this->assign('page', $list->render());

        //  总条数
        $this->assign('totalCount', $list->total());

        //  当前页面
        $this->assign('current', $list->currentPage());

        //  每页显示数量
        $this->assign('listRows', $list->listRows());

        return $list;
    }

    //获取ip
    public function getIp(){
        $ip = '';
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }elseif(isset($_SERVER['HTTP_CLIENT_IP'])){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $ip_arr = explode(',', $ip);
        return $ip_arr[0];
    }
    //生成指定长度数字2
    public function rands($no)
    {
        //range 是将1到100 列成一个数组
        $numbers = range (0,9);
        //shuffle 将数组顺序随即打乱
        shuffle ($numbers);
        //array_slice 取该数组中的某一段
        $result  = array_slice($numbers,0,$no);
        $str = '';
        for ($i=0;$i<$no;$i++){
            $str.= $result[$i];
        }
        return $str;
    }

    //图片上传
    public function uploadImgToFileService()
    {
        $result = (new uploadService())->uploadImg();
        $file_host = config('file_server_url');
        if ($result) {
            return outPut(200,'上传成功',['path' =>$result,'show_path'=>$file_host.$result]);
        }
        return outPut(301,'上传失败');
    }
}