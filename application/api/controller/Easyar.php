<?php

namespace app\api\controller;


use think\Request;
use think\Db;

use app\model\Card;
use app\model\AppConfig;
use app\model\UserScene;
use app\model\TicketApiConf;

//class Easyar extends Base {
class Easyar {
    public function check(){

        // step 1: 获取浏览器上传的图片数据
        $image = $this->getHttpData();
        if (!$image){
            return $this->showMsg(-5, '未发送图片数据');
        }

        // step 2: 将图片数据发送云识别服务
        $params = array(
            'timestamp' => time() * 1000,
            'appKey' => config('easyar.appKey'),
            'image' => $image,
        );
        $params['signature'] = $this->getSign($params, config('easyar.appSecret'));

        $str = $this->httpPost('http://'.config('easyar.Client').'/search', json_encode($params));

        if (!$str) {
            return $this->showMsg(-6, '网络错误');
        }

        // step 3: 解析识别结果，返回给浏览器使用
        $obj = json_decode($str);
        if (!$obj || (isset($obj->status) && $obj->status == 500)) {
            return $this->showMsg(-6, '网络错误');
        } else if ($obj->statusCode != 0) {
            return $this->showMsg(-7, '未识别到目标');
        } else {
            return $this->showMsg(0, $obj->result->target);
        }
    }

    /**
     * 获取浏览器上传的图上数据
     * @return string
     */
    function getHttpData() {
        $image = $this->getPostImage();
        if (!$image) $image = $this->getPostFile();
        return $image;
    }

    /**
     * WebAR使用
     * @return bool|string
     */
    function getPostImage() {
        $data = @file_get_contents('php://input');
        if ($data) {
            $obj = json_decode($data);
            $data = $obj->image;
        }
        return $data;
    }

    /**
     * 微信小程序使用(上传文件处理)
     * @return string
     */
    function getPostFile() {
        $data = '';
        if (isset($_FILES)) {
            foreach ($_FILES as $file) {
                if ($file['error'] == 0) {
                    $data = base64_encode(@file_get_contents($file['tmp_name']));
                    $this->base64_image_content($data,'.');
                    break;
                }
            }
        }
        return $data;
    }


    function base64_image_content($base64_image_content,$path)
    {
        //匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = $result[2];
            $new_file = $path . "/" . date('YmdHis', time()) . "/";
            if (!file_exists($new_file)) {
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0700);
            }
            $new_file = $new_file . time() . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                return '/' . $new_file;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 生成签名，使用sha256加密
     * @param $params
     * @param $cloudSecret
     * @return string
     */
    function getSign($params, $cloudSecret) {
        //按字典顺序排序
        ksort($params);

        $tmp = array();
        foreach ($params as $key => $value) {
            $tmp[] = $key . $value;
        }
        $str = implode('', $tmp);

        return hash('sha256', $str . $cloudSecret);
    }

    function showMsg($code, $msg) {
        $arr = array(
            'statusCode' => $code,
            'result' => $msg,
        );
        return $arr;
        //echo json_encode($arr);
        //exit;
    }


    function httpPost($url, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($data)));

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $str = curl_exec($ch);
        curl_close($ch);

        return $str;
    }

}