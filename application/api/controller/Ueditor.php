<?php
//命名空间
namespace app\api\controller;
use Think\Controller;

class Ueditor extends Base {
    protected $editorConfig;
    private $action;

    // 初始化父类
    public function initialize(){
        parent::initialize();
        // 获取配置参数
        $this->editorConfig = config('UEDITOR_CONFIG');

        // 获取请求类型
        $this->action = input('action');
    }

    /**
     * 默认Index方法
     * @author jihaichuan
     */
    public function index(){
        // 获取请求方式
        $this->action = input('get.action');
        switch ($this->action) {
            case 'config':
                $result =  json_encode($this->editorConfig);
                break;
            /* 上传图片 */
            case 'uploadimage':
                /* 上传涂鸦 */
            case 'uploadscrawl':
                /* 上传视频 */
            case 'uploadvideo':
                /* 上传文件 */
            case 'uploadfile':
                $result = $this->upload();
                break;
            /* 列出图片 */
            case 'listimage':
                $result = $this->fileList();
                break;
            /* 列出文件 */
            case 'listfile':
                $result = $this->fileList();
                break;

            /* 抓取远程文件 */
            case 'catchimage':
                $result = $this->crawler();
                break;
            default:
                $result = json_encode(array(
                    'state'=> '请求地址出错'
                ));
                break;
        }

        $callback = input('callback');
        /* 输出结果 */
        if ($callback) {
            if (preg_match("/^[\w_]+$/", $callback)) {
                echo htmlspecialchars($callback) . '(' . $result . ')';
            } else {
                echo json_encode(array(
                    'state'=> 'callback参数不合法'
                ));
            }
        } else {
            echo $result;
        }
    }


    /**
     * 请求上传方法
     * @return string
     * @author jihaichuan
     */
    private function upload(){
        /* 上传配置 */
        $base64 = "upload";
        switch (htmlspecialchars($this->action)) {
            case 'uploadimage':
                $config = array(
                    "pathFormat" => $this->editorConfig['imagePathFormat'],
                    "maxSize" => $this->editorConfig['imageMaxSize'],
                    "allowFiles" => $this->editorConfig['imageAllowFiles']
                );
                $fieldName = $this->editorConfig['imageFieldName'];
                break;
            case 'uploadscrawl':
                $config = array(
                    "pathFormat" => $this->editorConfig['scrawlPathFormat'],
                    "maxSize" => $this->editorConfig['scrawlMaxSize'],
                    "allowFiles" => $this->editorConfig['scrawlAllowFiles'],
                    "oriName" => "scrawl.png"
                );
                $fieldName = $this->editorConfig['scrawlFieldName'];
                $base64 = "base64";
                break;
            case 'uploadvideo':
                $config = array(
                    "pathFormat" => $this->editorConfig['videoPathFormat'],
                    "maxSize" => $this->editorConfig['videoMaxSize'],
                    "allowFiles" => $this->editorConfig['videoAllowFiles']
                );
                $fieldName = $this->editorConfig['videoFieldName'];
                break;
            case 'uploadfile':
            default:
                $config = array(
                    "pathFormat" => $this->editorConfig['filePathFormat'],
                    "maxSize" => $this->editorConfig['fileMaxSize'],
                    "allowFiles" => $this->editorConfig['fileAllowFiles']
                );
                $fieldName = $this->editorConfig['fileFieldName'];
                break;
        }
        /* 生成上传实例对象并完成上传 */
        $upload = new Uploader();
        $upload->setConfig($fieldName,$config,$base64);

        /**
         * 得到上传文件所对应的各个参数,数组结构
         * array(
         *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
         *     "url" => "",            //返回的地址
         *     "title" => "",          //新文件名
         *     "original" => "",       //原始文件名
         *     "type" => ""            //文件类型
         *     "size" => "",           //文件大小
         * )
         */

        /* 返回数据 */
        return json_encode($upload->getFileInfo());
    }


    /**
     * 获取文件列表
     * @return string
     * @author jihaichuan
     */
    private function fileList(){
        /* 判断类型 */
        switch ($this->action) {
            /* 列出文件 */
            case 'listfile':
                $allowFiles = $this->editorConfig['fileManagerAllowFiles'];
                $listSize = $this->editorConfig['fileManagerListSize'];
                $path = $this->editorConfig['fileManagerListPath'];
                break;
            /* 列出图片 */
            case 'listimage':
            default:
                $allowFiles = $this->editorConfig['imageManagerAllowFiles'];
                $listSize = $this->editorConfig['imageManagerListSize'];
                $path = $this->editorConfig['imageManagerListPath'];
        }
        $allowFiles = substr(str_replace(".", "|", join("", $allowFiles)), 1);

        /* 获取参数 */
        $size = input('size');
        $start = input('start');
        $size = $size ? htmlspecialchars($size) : $listSize;
        $start = $start ? htmlspecialchars($start) : 0;
        $end = $start + $size;

        /* 获取文件列表 */
        $path = $_SERVER['DOCUMENT_ROOT'] . (substr($path, 0, 1) == "/" ? "":"/") . $path;
        $files = getfiles($path, $allowFiles);
        if (!count($files)) {
            return json_encode(array(
                "state" => "no match file",
                "list" => array(),
                "start" => $start,
                "total" => count($files)
            ));
        }

        /* 获取指定范围的列表 */
        $len = count($files);
        for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--){
            $list[] = $files[$i];
        }
        //倒序
        //for ($i = $end, $list = array(); $i < $len && $i < $end; $i++){
        //    $list[] = $files[$i];
        //}

        /* 返回数据 */
        $result = json_encode(array(
            "state" => "SUCCESS",
            "list" => $list,
            "start" => $start,
            "total" => count($files)
        ));

        return $result;
    }


    /**
     * 远程抓取图片
     * @return string
     * @author jihaichuan
     */
    private function crawler(){
        set_time_limit(0);
        /* 上传配置 */
        $config = array(
            "pathFormat" => $this->editorConfig['catcherPathFormat'],
            "maxSize" => $this->editorConfig['catcherMaxSize'],
            "allowFiles" => $this->editorConfig['catcherAllowFiles'],
            "oriName" => "remote.png"
        );
        $fieldName = $this->editorConfig['catcherFieldName'];

        /* 抓取远程图片 */
        $list = array();
        if (isset($_POST[$fieldName])) {
            $source = $_POST[$fieldName];
        } else {
            $source = $_GET[$fieldName];
        }
        foreach ($source as $imgUrl) {
            R('Uploader/setConfig', array('fileField'=>$imgUrl, 'config'=>$config, 'type'=>'remote'));
            $info = R('Uploader/getFileInfo');
            array_push($list, array(
                "state" => $info["state"],
                "url" => $info["url"],
                "size" => $info["size"],
                "title" => htmlspecialchars($info["title"]),
                "original" => htmlspecialchars($info["original"]),
                "source" => htmlspecialchars($imgUrl)
            ));
        }

        /* 返回抓取数据 */
        return json_encode(array(
            'state'=> count($list) ? 'SUCCESS':'ERROR',
            'list'=> $list
        ));
    }

}
