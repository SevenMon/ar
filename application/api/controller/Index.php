<?php

// 命名空间

namespace app\api\controller;

use think\Request;
use think\Db;

class Index {

    public function index(){
        $gfe = new GifFrameExtractor();
        $gfe->extract('./static/upload/game1/kuzi.gif', true);
        $frameImages = $gfe->getFrameImages();
        $frameDurations = $gfe->getFrameDurations();

        var_dump($frameDurations);

        $i = 0;
        foreach ($frameImages as $image) {
            imagejpeg($image, $i.".jpeg");
            $i++;
        }
    }

    //获取背景图
    public function getBackPic(){
        //file_get_contents($url,true); 可以读取远程图片，也可以读取本地图片
        $img = file_get_contents('./static/upload/material/picture.jpg',true);
        //使用图片头输出浏览器
        header("Content-Type: image/jpeg;text/html; charset=utf-8");
        echo $img;
        exit;
    }
}
