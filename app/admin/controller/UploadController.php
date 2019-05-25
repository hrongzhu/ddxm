<?php
/**
 * Author: chenjing
 * Date: 2018/1/16
 * Description:
 */

namespace app\admin\controller;


use app\admin\common\upload as uploadService;

class UploadController extends BaseController
{

    //图片上传
    public function uploadPic()
    {
        $result = (new uploadService())->uploadImg();
        if ($result) {
            return outPut(200,'上传成功',[ 'path' =>$result]);
        }
        return outPut(301,'上传失败');
    }
}