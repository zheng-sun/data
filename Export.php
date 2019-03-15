<?php

/**
 * @author blog.anchen8.net
 * @copyright 2016
 */
class Export{

    public function __construct(){
        //$this->log = $registry->get('log');
    }
    
    //  获取ecexl的数据
    public function exprot($field , $data , $filename){
        require_once('Classes/PHPExcel.php');
        $objPHPExcel = new PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                ->setLastModifiedBy("Maarten Balliauw")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");
        //  设置表头
        $array = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T');
        $length = count($field);
        for($i = 0; $i < $length; $i++){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($array[$i].'1',$field[$i]);
        }
        $i = 2;
        foreach($data as $a) {
            $j = 0;
            foreach($a as $b) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($array[$j].$i,$b);
                $j++;
            }
            $i++;
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . date('Y-m-d', time()) . 'test.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
    
    //  备份execl
    public function move_new_file($old_file,$new_path,$file_type = '') {
        if( $old_file['error'] > 0 ){
            return $respon = array('ret'=>-1,'msg'=>'文件上传错误');
        }
        //  获取文件的扩展名
        $file_info = pathinfo($old_file['name']);
        $extension = $file_info['extension'];
        $new_file_path = $new_path.$file_type.'_'.date('YmdHis').'_'.rand(10000,99999).".".$extension;
        //  检测目录是否存在
        if( !file_exists($new_path)) {
            mkdir($new_path,0700,true);
            //$this->log->write("创建新的目录 ,path:{$new_path}",'import');
            //$this->log->user_log(['class'=>__CLASS__, 'function'=>__FUNCTION__,'message'=>"创建新的目录 ,path:{$new_path}"],'import');
        }
        //  移动文件到新的目录
        move_uploaded_file($old_file['tmp_name'],$new_file_path);
        //$this->log->write("移动文件到新的目录 , old_file_name:{$old_file['name']}, new_file_name:{$new_file_path}",'import');
        //$this->log->user_log(['class'=>__CLASS__, 'function'=>__FUNCTION__,'message'=>"移动文件到新的目录 , old_file_name:{$old_file['name']}, new_file_name:{$new_file_path}"],'import');
    }
    //根据文件名下文件
    public function get_file_content($get, $dir = '') {
        if(isset($get['filename'])&& !empty($get['filename']) && isset($get['exportExcle'])){
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' .$get['filename']. '"');
            header('Cache-Control: max-age=0');
            echo file_get_contents(dirname(DIR_SYSTEM).'/upload/'.$get['filename']);
            exit;
        }else{
            return true;
        }
    }
    //将excel内容保存到文件
     public function export_file_content($objPHPExcel, $exportFiles, $dir=''){
        $saveDir = !$dir ? dirname(DIR_SYSTEM).'/upload/' : $dir;
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($saveDir.$exportFiles);
        $ret = array('ret'=>0,'msg'=>'成功！','data'=>array('filename'=>$exportFiles));
        echo json_encode($ret);
        exit;
    }

    // 根据传入参数和数据，生成execl
    public function generate_EXECL($columns, $data, $get, $filename = ''){

        require_once('Classes/PHPExcel.php');

        $this->get_file_content($get);

        $objPHPExcel = new PHPExcel();

        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
        ->setLastModifiedBy("Maarten Balliauw")
        ->setTitle("Office 2007 XLSX Test Document")
        ->setSubject("Office 2007 XLSX Test Document")
        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Test result file");

        $ascli_start = '65';  // A 的ascli
        $ascli_end = '90';    // Z 的ascli

        // 循环列头
        foreach($columns as $key=>$column) {
            $col = chr($key + $ascli_start);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($col.'1', $column);
        }

        // 获取数据的字段名
        $array_keys = array_keys($data[0]);

        // 循环列表
        foreach($data as $k=>$d) {
            foreach($array_keys as $key=>$keys) {
                $col = chr($key + $ascli_start);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($col.($k + 2), $d[$keys]);
            }
        }

        $this->export_file_content($objPHPExcel, date('YmdHis-').'-'.$filename.'.xls');
    }
}


?>