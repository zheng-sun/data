<?php

/**
 * @author blog.anchen8.net
 * @copyright 2016
 */
class Import{

    public function __construct(){
        //$this->log = $registry->get('log');
    }
    
    //  获取ecexl的数据
    public function get_execl($files){
        require_once('Classes/PHPExcel.php');
        /*获取Excel文件类型，确定版本*/
        $file = $files;
        $extend = pathinfo($file['name']);
        $extend = strtolower($extend["extension"]);
        $extend == 'xlsx' ? $reader_type = 'Excel2007' : $reader_type = 'Excel5';
        $objReader = \PHPExcel_IOFactory::createReader($reader_type);
        if (!$objReader) {
            //$this->log->write('抱歉！excel文件不兼容。','import'); //执行失败，直接抛出错误中断
            $this->log->user_log(['class'=>__CLASS__, 'function'=>__FUNCTION__,'message'=>'抱歉！excel文件不兼容。'],'import');
        }
        $objPHPExcel = $objReader->load($file['tmp_name']);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);//总列数
        $headtitle = array();
        for ($cols = 0; $cols <= $highestColumnIndex; $cols++) {
            $headtitle[$cols] = (string)$objWorksheet->getCellByColumnAndRow($cols, 1)->getValue();
        }
        if (empty($headtitle[0])) {
            for ($cols = 0; $cols <= $highestColumnIndex; $cols++) {
                $headtitle[$cols] = (string)$objWorksheet->getCellByColumnAndRow($cols, 2)->getValue();
            }
        }
        $strs = array();
        /*第二行开始读取*/
        for ($row = 1; $row <= $highestRow; $row++) {
            if( $row == 2 ) {
                continue;
            }
            for ($cols = 0; $cols < $highestColumnIndex; $cols++) {
                $strs[$row][$cols] = urldecode(urldecode((string)$objWorksheet->getCellByColumnAndRow($cols, $row)->getValue()));
            }
        }
        return $strs;
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
            $this->log->user_log(['class'=>__CLASS__, 'function'=>__FUNCTION__,'message'=>"创建新的目录 ,path:".$new_path],'import');
        }
        //  移动文件到新的目录
        move_uploaded_file($old_file['tmp_name'],$new_file_path);
        //$this->log->write("移动文件到新的目录 , old_file_name:{$old_file['name']}, new_file_name:{$new_file_path}",'import');
        $this->log->user_log(['class'=>__CLASS__, 'function'=>__FUNCTION__,'message'=>"移动文件到新的目录 , old_file_name:{$old_file['name']}, new_file_name:{$new_file_path}"],'import');
    }
}


?>