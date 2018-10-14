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
}
