<?php

namespace app\admin\controller;

use app\model\Brand;
use app\model\BrandWares;
use app\model\Shop;
use think\Db;


class Brands extends Base
{

    public $left_menu_active = 'admin_brands_index';
    public $top_menu_active = 'brands';

    public function initialize()
    {
        parent::initialize();
    }
    public function index(){

        //获取数据  分页
        $brandModel = new Brand();
        $limit = input('limit') == null || empty(input('limit')) ? getLimit() : input('limit');
        $where[] = array('status','>',0);
        $list = $brandModel->where($where)->order('sort desc,id desc')->paginate($limit);
        $this->assign('list', $list);
        return $this->fetch();
    }

    public function addPage(){
        return $this->fetch();
    }

    public function editPage(){
        $brandId = input('id');
        $brandModel = new Brand();
        $brandData = $brandModel->find($brandId);
        if(empty($brandData)){
            $this->error('品牌不存在！');
        }elseif($brandData['status'] == 0){
            $this->error('品牌已删除！');
        }
        $this->assign('brandData',$brandData);
        return $this->fetch();
    }

    public function add(){
        $name = input('post.title');
        /*$gameType = input('post.gameType');
        $time = input('post.time');
        $address = input('post.address');*/
        $appId = input('post.appId');
        $appSecret = input('post.appSecret');
        /*$img = input('post.upload');*/
        if(empty($name)||$name==null
            /*||empty($gameType)||$gameType==null||
            empty($time)||$time==null||
            empty($address)||$address==null||*/
            /*empty($appId)||$appId==null||
            empty($appSecret)||$appSecret==null*/
            /*||empty($img)||$img==null*/){
            $this->error('请补全参数！');
        }
        $brandModel = new Brand();
        //检查标签是否存在
        /*if($brandModel->checkName($name)){
            $this->error('品牌已存在！');
        }*/

        //获取最大sort
        $maxSort = $brandModel->field('max(sort) maxSort')->find();
        $maxSort = $maxSort['maxSort'];

        $data = array(
            'name' => $name,
            'sort' => ++$maxSort,
            /*'type' => $gameType,
            'time' => $time,
            'address' => $address,*/
            'app_id' => $appId,
            'app_secret' => $appSecret,
            /*'address_pic' => $img,*/
        );
        $id = $brandModel->addData($data);
        if($id >= 1){
            $this->redirect('admin/Brands/index');
        }else{
            $this->error('添加失败！');
        }
    }

    public function edit(){
        $brandId = input('id');
        $name = input('post.title');
        /*$gameType = input('post.gameType');
        $time = input('post.time');
        $address = input('post.address');*/
        $appId = input('post.appId');
        $appSecret = input('post.appSecret');
        /*$img = input('post.upload');*/
        if(empty($name)||$name==null
            /*||empty($gameType)||$gameType==null||
            empty($time)||$time==null||
            empty($address)||$address==null||*/
            /*empty($appId)||$appId==null||
            empty($appSecret)||$appSecret==null*/
            /*||empty($img)||$img==null*/){
            $this->error('请补全参数！');
        }
        $brandModel = new Brand();
        $data = $brandModel->find($brandId);
        if(empty($data)){
            $this->error('品牌不存在！');
        }elseif($data['status'] == 0){
            $this->error('品牌已删除！');
        }

        //检查标签是否存在
        /*if($data['name'] != $name){
            if($brandModel->checkName($name)){
                $this->error('品牌已存在！');
            }
        }*/

        $updataData = array(
            'name' => $name,
            /*'type' => $gameType,
            'time' => $time,
            'address' => $address,*/
            'app_id' => $appId,
            'app_secret' => $appSecret,
            /*'address_pic' => $img,*/
        );
        $status = input('status');
        if(empty($status) || $status == null){
            $updataData['status'] = 2;
        }else{
            $updataData['status'] = 1;
        }

        $id = $brandModel->saveData($brandId,$updataData);
        if($id >= 0){
            $this->redirect('admin/Brands/index');
            //$this->success('修改成功','admin/Brands/index');
        }else{
            $this->error('修改失败！');
        }
    }

    public function delete(){
        $brandId = input('id');
        $brandModel = new Brand();
        $data = $brandModel->find($brandId);
        if(empty($data)){
            $this->error('品牌不存在！');
        }elseif($data['status'] == 0){
            $this->error('品牌已删除！');
        }
        $updateData['status'] = 0;
        $info = $brandModel->saveData($brandId,$updateData);
        if(empty($info)){
            $this->error('删除失败！');
        }else{
            $this->redirect('admin/Brands/index');
            //$this->success('删除成功','admin/Brands/index');
        }
    }

    public function export(){

        $sql = "SELECT cn_brand.id cn_brand_id,
                cn_brand.name cn_brand_name,
                cn_brand.status cn_brand_status,
                cn_brand.create_time cn_brand_create_time,
                cn_shop.id cn_shop_id,
                cn_shop.name cn_shop_name,
                cn_shop.address cn_shop_address,
                cn_shop.phone cn_shop_phone,
                cn_shop.address_pic cn_shop_address_pic,
                cn_shop.status cn_shop_address_status,
                cn_brand_wares.id cn_brand_wares_id,
                cn_brand_wares.name cn_brand_wares_name,
                cn_brand_wares.pic cn_brand_wares_pic,
                cn_brand_wares.introduce cn_brand_wares_introduce,
                cn_brand_wares.status cn_brand_wares_status,
                cn_brand_wares.type cn_brand_wares_type,
                cn_brand_wares.time cn_brand_wares_time 
                FROM `cn_brand` 
                LEFT JOIN (select * from cn_shop where status > 0) cn_shop
                ON `cn_brand`.`id`=`cn_shop`.`brand_id` 
                LEFT JOIN (select * from cn_brand_wares where status > 0) cn_brand_wares
                ON `cn_brand`.`id`=`cn_brand_wares`.`brand_id` 
                WHERE `cn_brand`.`status` > '0' 
                ORDER BY `cn_brand`.`id` ASC";
        $data = Db::query($sql);
        require '../extend/phpexcel/PHPExcel.php';
        $objPHPExcel  = new \PHPExcel();
        $objDrawing = new \PHPExcel_Worksheet_Drawing();
        //导出订单模板
        //定义配置
        $topNumber = 2;//表头有几行占用
        $cellKey = array(
            'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O'
        );
        //写在处理的前面（了解表格基本知识，已测试）
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','品牌商id');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','品牌商名称');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','品牌商状态');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1','创建时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1','店铺id');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1','店铺名称');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1','店铺地址');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1','联系电话');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1','地址图片');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1','店铺状态');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1','商品id');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1','商品名称');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1','商品图片');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1','商品状态');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1','商品类别');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P1','领取时间');
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(17);//所有单元格（列）默认宽度
        //$objPHPExcel->getActiveSheet()->getDefaultRowDimension(2)->setRowHeight(80);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('N1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('O1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('P1')->getFont()->setBold(true)->setSize(12);

        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('D1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('F1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('G1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('H1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('I1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('J1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('K1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('L1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('M1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('N1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('O1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('P1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $i = 2;
        foreach ($data as $value){
            $objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('F'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('H'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('J'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('K'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('L'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('M'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('N'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('O'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('P'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            if(empty($value['cn_shop_address_status']) || $value['cn_shop_address_status'] == null){
                $value['cn_shop_address_status'] = '';
            }else{
                $value['cn_shop_address_status'] = $value['cn_shop_address_status'] == 1?'启用':'禁用';
            }
            if(empty($value['cn_brand_wares_status']) || $value['cn_brand_wares_status'] == null){
                $value['cn_brand_wares_status'] = '';
            }else{
                $value['cn_brand_wares_status'] = $value['cn_brand_wares_status'] == 1?'启用':'禁用';
            }
            if($value['cn_shop_address_pic']==null || empty($value['cn_shop_address_pic'])){
                $objPHPExcel->getActiveSheet()->setCellValue('I'.$i,$value['cn_shop_address_pic']==null?'':$value['cn_shop_address_pic']);
            }else{
                $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(80);
                // 图片生成
                $objDrawing = new \PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath('.'.$value['cn_shop_address_pic']);
                // 设置宽度高度
                $objDrawing->setHeight(80);//照片高度
                $objDrawing->setWidth(80); //照片宽度
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('I'.$i);
                // 图片偏移距离
                $objDrawing->setOffsetX(12);
                $objDrawing->setOffsetY(12);
                $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
            }

            if($value['cn_brand_wares_pic']==null || empty($value['cn_brand_wares_pic'])){
                $objPHPExcel->getActiveSheet()->setCellValue('M'.$i,$value['cn_brand_wares_pic']==null?'':$value['cn_brand_wares_pic']);
            }else{
                $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(80);
                // 图片生成
                $objDrawing = new \PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath('.'.$value['cn_brand_wares_pic']);
                // 设置宽度高度
                $objDrawing->setHeight(80);//照片高度
                $objDrawing->setWidth(80); //照片宽度
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('M'.$i);
                // 图片偏移距离
                $objDrawing->setOffsetX(12);
                $objDrawing->setOffsetY(12);
                $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
            }
            switch ($value['cn_brand_wares_type'])
            {
                case 1:
                    $value['cn_brand_wares_type'] = '优惠券';
                    break;
                case 2:
                    $value['cn_brand_wares_type'] = '实物奖品';
                    break;
                case 3:
                    $value['cn_brand_wares_type'] = '微信卡卷';
                    break;
                default:
                    $value['cn_brand_wares_type'] = '';
                    break;
            }
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$value['cn_brand_id']);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$i,$value['cn_brand_name']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$i,$value['cn_brand_status'] == 1?'启用':'禁用');
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$i,$value['cn_brand_create_time']);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$i,$value['cn_shop_id']==null?'':$value['cn_shop_id']);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$i,$value['cn_shop_name']==null?'':$value['cn_shop_name']);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$i,$value['cn_shop_address']==null?'':$value['cn_shop_address']);
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$i,$value['cn_shop_phone']==null?'':$value['cn_shop_phone']);

            $objPHPExcel->getActiveSheet()->setCellValue('J'.$i,$value['cn_shop_address_status']);
            $objPHPExcel->getActiveSheet()->setCellValue('K'.$i,$value['cn_brand_wares_id']==null?'':$value['cn_brand_wares_id']);
            $objPHPExcel->getActiveSheet()->setCellValue('L'.$i,$value['cn_brand_wares_name']==null?'':$value['cn_brand_wares_name']);

            $objPHPExcel->getActiveSheet()->setCellValue('N'.$i,$value['cn_brand_wares_status']);
            $objPHPExcel->getActiveSheet()->setCellValue('O'.$i,$value['cn_brand_wares_type']);
            $objPHPExcel->getActiveSheet()->setCellValue('P'.$i,$value['cn_brand_wares_time']==null?'':$value['cn_brand_wares_time']);

            $i++;
        }
        //导出execl
        header('Content-Type: application/vnd.ms-excel');//storeSelfTradeTemplatet
        header('Content-Disposition: attachment;filename="brand_'.date('Y-m-d',time()).'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');

        exit;
    }
}