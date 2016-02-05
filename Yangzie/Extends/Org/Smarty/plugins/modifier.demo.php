<?php
   /*
    * 文件名：modifier.demo.php
    * 功能：格式化一个unix时间戳
    */
  function smarty_modifier_demo($time,$format)
  {
  	return  date($format,$time);
  }
?>