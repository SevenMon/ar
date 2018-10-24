<?php

namespace app\admin\controller;

use app\model\Brand;
use app\model\Coupon;
use app\model\Project;


class Coupons extends Base
{

    public $left_menu_active = 'admin_coupons_index';
    public $top_menu_active = 'coupons';

    public function initialize()
    {
        parent::initialize();
    }
    public function index(){
        $status = input('status');
        //获取数据  分页
        $couponModel = new Coupon();
        $limit = input('limit') == null || empty(input('limit')) ? getLimit() : input('limit');
        $where = array();
        if(!empty($status) && $status != null){
            if(in_array($status,array(1,2,3))){
                $where[] = array('status','=',$status);
            }elseif ($status == 4){
                $where[] = array('time','<',date('Y-m-d',time()));
            }
        }

        $list = $couponModel->where($where)->order('id desc')->paginate($limit);
        $this->assign('list', $list);
        $this->assign('status', $status);
        return $this->fetch();
    }

    //发货信息模板下载
    public function couponModelDown() {
        require '../extend/phpexcel/PHPExcel.php';
        $objPHPExcel  = new \PHPExcel();

        //导出模板
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','品牌商id');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','品牌商名称');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','项目id');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1','项目名称');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1','优惠券码');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1','领取时间（例：2018-10-10）');
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(17);//所有单元格（列）默认宽度
        $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(15);

        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true)->setSize(12);

        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('D1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('F1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        /*for ($i=0;$i<50;$i++){
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$i,'');
        }*/
        //导出execl
        header('Content-Type: application/vnd.ms-excel');//storeSelfTradeTemplatet
        header('Content-Disposition: attachment;filename="coupon_model.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }


    //导入发货信息
    public function uploadCouponInfo(){

        $file = request()->file('fileUpload');
        if($file){
            $info = $file->move( './Uploads/ordersendinfo/');
            if($info->getExtension() != 'xls'){
                $this->error('文件类型错误！');
                exit;
            }
            $fileName = $info->getFileName();
            $path = $info->getPath().'/'.$fileName;
            require '../extend/phpexcel/PHPExcel.php';
            $PHPReader = new \PHPExcel_Reader_Excel2007();
            //判断文件类型
            if (!$PHPReader->canRead($path)) {
                $PHPReader = new \PHPExcel_Reader_Excel5();
                if (!$PHPReader->canRead($path)) {
                    $this->error('请选择Excel文件');
                }
            }
            $PHPExcel = $PHPReader->load($path);
            /**读取excel文件中的第一个工作表*/
            //并且转成数组
            $currentSheetArr = $PHPExcel->getSheet(0)->toArray();
            if(empty($currentSheetArr) || !is_array($currentSheetArr)){
                $this->error('文件内容错误！');
            }
            $couponModel = new Coupon();
            foreach ($currentSheetArr as $key => $value){
                if($key == 0)continue;
                $data = array(
                    'brand_id' => $value[0],
                    'brand_name' => $value[1],
                    'project_id' => $value[2],
                    'project_name' => $value[3],
                    'code' => $value[4],
                    'time' => $value[5],
                );
                $where = array();
                $where[] = array('brand_id','=',$value[0]);
                $where[] = array('project_id','=',$value[0]);
                $where[] = array('code','=',$value[0]);
                $couponData = $couponModel->where($where)->find();
                if(!empty($couponData))continue;
                $couponModel->insertGetId($data);
            }
            $this->redirect('admin/Coupons/index',array('status' => 1));
        }else{
            // 上传失败获取错误信息
            $this->error('上传失败，请稍后再试！');
        }
    }
}