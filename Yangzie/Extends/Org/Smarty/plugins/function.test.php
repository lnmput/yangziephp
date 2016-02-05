<?php
   /*
    * 文件名：function.test.php
    * 功能：计算长方形的面积
    */
   function smarty_function_test($params){
   	     $w=$params['width'];
   	     $h=$params['height'];
   	     return $w*$h;
   }
?>