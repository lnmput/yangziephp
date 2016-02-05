<?php
   /*
    * 文件名：block.ceshi.php
    * 功能：
    */
   function smarty_block_ceshi($parmas,$content)
   {
   	  $replace=$parmas['replace'];
   	  $maxnum=$parmas['maxnum'];
   	  if($replace==TRUE){
   	  	 $content=str_replace("，", ",", $content);
   	  	 $content=str_replace("。", ".", $content);
   	  }
   	  $content=substr($content, 0,$maxnum);
   	  return $content;
   }
?>