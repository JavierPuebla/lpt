<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Excel_features extends CI_Controller {
  public function __construct() {
    parent::__construct();
    $this->load-> model('app_model');
    $this->load->helper('array');
    $this->load->helper('form');
    $this->load->helper('download');
    $this->load->library('cmn_functs');

    // Establecer la zona horaria predeterminada a usar. Disponible desde PHP 5.1
    date_default_timezone_set('America/Argentina/Buenos_Aires');

    include (APPPATH . 'JP_classes/Atom.php');
    include (APPPATH . 'JP_classes/Element.php');
    include (APPPATH . 'JP_classes/Event.php');
  }

  // recibe array de objetos, convierte los keys en headings de fila 0
  // y vuelca el contenido en las columnas
  static function create_file($data,$file_name){
    // $max_cols = 670; // ESTO ES 26 LETRAS POR 26
    // if(count($data) > $max_cols){
    //   exit('Error demasiadas columnas...'); 
    // }
    /** PHPExcel_IOFactory */
    include(APPPATH.'libraries/PHPExcel/IOFactory.php');
    
    // INSTANTIATE A NEW PHPEXCEL OBJECT
    $objPHPExcel = new PHPExcel(); 
    // SET THE ACTIVE EXCEL WORKSHEET TO SHEET 0
    $objPHPExcel->setActiveSheetIndex(0); 
    // INITIALISE THE EXCEL ROW NUMBER
    $h = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
    $x = 0;
    $fila1 = array_keys($data[0]);
    foreach ($fila1 as $key => $v) {
      if($key > count($h)){
        $t = ($key - (count($h)*($x+1)))-1;
        $objPHPExcel->getActiveSheet()->getColumnDimension($h[$x].$h[$t])->setWidth(20);
        $objPHPExcel->getActiveSheet()->SetCellValue($h[$x].$h[$t].'1', $v);
        if($key > count($h) && $key % count($h) == 0){$x ++;}
      }else{
        $objPHPExcel->getActiveSheet()->getColumnDimension($h[$key])->setWidth(20);
        $objPHPExcel->getActiveSheet()->SetCellValue($h[$key].'1', $v);  
      }
      
    }
    $rowCount = 2; 
    $x = 0;
    // ITERATE THROUGH EACH RESULT FROM THE SQL QUERY IN TURN
    // WE FETCH EACH DATABASE RESULT ROW INTO $ROW IN TURN
    foreach ($data as $row){ 
      foreach ($fila1 as $col => $col_name) {
        if($col > count($h)){
          $t = ($col - (count($h)*($x+1)))-1;
          $objPHPExcel->getActiveSheet()->SetCellValue($h[$x].$h[$t].$rowCount, $row[$col_name]); 
          if($col > count($h) && $col % count($h) == 0){$x ++;}  
        }else{
          $objPHPExcel->getActiveSheet()->SetCellValue($h[$col].$rowCount, $row[$col_name]);   
        }

      }
      $rowCount++; 
    } 
    // $OBJPHPEXCEL->GETACTIVESHEET()->SETCELLVALUE('A1', 'HOLA'); 
    // INSTANTIATE A WRITER TO CREATE AN OFFICEOPENXML EXCEL .XLSX FILE
    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
    // WRITE THE EXCEL FILE TO FILENAME 
    $objWriter->save("uploads/".$file_name.".xlsx");
    return $file_name.".xlsx";
  }
}
