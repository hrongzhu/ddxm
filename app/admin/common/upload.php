<?php
/**
 * Author: chenjing
 * Date: 2017/11/29
 * Description: 公共上传方法
 */

namespace app\admin\common;


use Qiniu\Auth as Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;


//暂时未用
class upload
{
    public function uploadImg($inputName = '')
    {
        $url = config('file_server_url').'/Home/upload/api_uploadimg';//文件服务器域名
        $file =$_FILES;
        $pic = api_img($url, $file);
        $start = strpos($pic,'/Public');

        if (!is_array($pic) || is_numeric($start)){
            return $pic;
        }
        return false;
    }
    //多文件上传
    public function uploadImgs($fileData)
    {
        $files = request()->file($fileData);
        if ($files && is_array($files)) {
            $fileList = [];
            foreach($files as $key => $file){
                // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->validate(config('upload.validate'))->move(config('upload.upload_path'));
                if($info){
                    $fileList[$key] = '/uploads/'.$info->getSaveName();
                }else{
                    // 上传失败获取错误信息
                    $fileList[$key] =  '该文件上传失败:'.$file->getError();
                }
            }
            return $fileList;
            file_put_contents('./upload.txt',serialize($fileList));
        }elseif($files){
            $info = $files->validate(config('upload.validate'))->move(config('upload.upload_path'));
            if($info){
                return '/uploads/'.$info->getSaveName();
            }
            // 上传失败获取错误信息
            return errOutPut(-1000,$files->getError());
        }
    }

    /**
     * 上传图片到七牛云
     * @param  [type] $files [description]
     * @return [type]        [description]
     */
    public function qiniuUploadImgs($files)
    {
        // 获取配置信息
        $qiniuConfig = config('qiniu');
        $auth = new Auth($qiniuConfig['accesskey'], $qiniuConfig['secretkey']);
        $token = $auth->uploadToken($qiniuConfig['bucket']);
        // var_dump($qiniuConfig);die;
        try {
            // 调用 UploadManager 的 putFile 方法进行文件的上传
            return $this->uploadToQiniu($files,$token);
            // 尝试上传 如有异常就抛出异常
        } catch (\Exception $e) {
            // 异常处理 返回错误信息 终止操作
            return false;
        }
    }

    /**
     * @param array $files
     * @param string $token
     * @param string $saveName
     * @return mixed
     * 单文件上传，如果添加多个文件则只上传第一个
     */
    protected function uploadToQiniu($files = [],$token = '',$saveName = '')
    {
        if (empty($files)) {
            throw new Exception('没有文件被上传', 10002);
        }
        $values = array_values($files);

        $uploadManager = new UploadManager();
        if (empty($saveName)) {
            $saveName = hash_file('sha1', $values[0]['tmp_name']).time();
        }
        $infoArr = explode('.', $values[0]['name']);
        $extension = array_pop($infoArr);
        $fileInfo = $saveName . '.' . $extension;
        list($ret, $err) = $uploadManager->putFile($token, $saveName, $values[0]['tmp_name']);
        // $res = $uploadManager->putFile($token, $saveName, $values[0]['tmp_name']);
        if ($err !== null) {
            return ['code'=>301,'msg'=>'上传出错','data'=>''];
        }
        return ['code'=>200,'msg'=>'上传成功','data'=>$ret['key']];
    }



}
