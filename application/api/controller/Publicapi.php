<?php

// 命名空间

namespace app\api\controller;

use think\Request;
use think\Db;
use app\model\Product;
use app\model\AppConfig;
use app\model\Label;
use app\model\Type;
use app\model\User;
use app\model\ProductWaresLevel;
use app\model\ProductWaresRelation;
use app\model\Order;

//统计接口
class Publicapi {
    public function ceshi(){
        echo '{"code":1,"msg":"/upload/1.jpg"}';
    }
    public function game1ImgUpload(){
        $typeArr = array("png","jpg","gif","jpeg"); //允许上传文件格式
        $path = "./static/upload/game1/"; //上传路径
        if (isset($_POST)) {
            $name = $_FILES['file']['name'];
            $size = $_FILES['file']['size'];
            $name_tmp = $_FILES['file']['tmp_name'];
            if (empty($name)) {
                echo json_encode(array("error" => "您还未选择文件"));
                exit;
            }
            $type = strtolower(substr(strrchr($name, '.'), 1)); //获取文件类型

            if (!in_array($type, $typeArr)) {
                echo json_encode(array("error" => "请上传pem证书文件！"));
                exit;
            }
            $pic_name = time() . '_' .rand(1000,9999).'.'.$type; //图片名称
            $pic_url = $path . $pic_name; //上传后图片路径+名称

            //图片剪裁
            if (move_uploaded_file($name_tmp, $pic_url)) { //临时文件转移到目标文件夹
                //$this->changeImage($pic_url);
                echo json_encode(array("code" => "1", "msg" => "/static/upload/game1/".$pic_name, "filename" => $pic_name));
                exit;
            } else {
                echo json_encode(array("code" => "0","msg" => "上传有误，清检查服务器配置！"));
                exit;
            }
        }
    }

    public function waresImgUpload(){
        $typeArr = array("png","jpg","gif","jpeg"); //允许上传文件格式
        $path = "./static/upload/wares/"; //上传路径
        if (isset($_POST)) {
            $name = $_FILES['file']['name'];
            $size = $_FILES['file']['size'];
            $name_tmp = $_FILES['file']['tmp_name'];
            if (empty($name)) {
                echo json_encode(array("error" => "您还未选择文件"));
                exit;
            }
            $type = strtolower(substr(strrchr($name, '.'), 1)); //获取文件类型

            if (!in_array($type, $typeArr)) {
                echo json_encode(array("error" => "请上传pem证书文件！"));
                exit;
            }
            $pic_name = time() . '_' .rand(1000,9999).'.'.$type; //图片名称
            $pic_url = $path . $pic_name; //上传后图片路径+名称

            //图片剪裁
            if (move_uploaded_file($name_tmp, $pic_url)) { //临时文件转移到目标文件夹
                //$this->changeImage($pic_url);
                echo json_encode(array("code" => "1", "msg" => "/static/upload/wares/".$pic_name, "filename" => $pic_name));
                exit;
            } else {
                echo json_encode(array("code" => "0","msg" => "上传有误，清检查服务器配置！"));
                exit;
            }
        }
    }

}