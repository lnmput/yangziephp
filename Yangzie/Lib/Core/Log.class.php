<?php
/**
 * FileName Log.class.php.
 * User: yangzie1192@163.com
 * Version:0.1
 * Date: 2015/12/19
 * Time: 12:35
 */
class Log
{
    static public function write($msg,$level='ERROR',$type=3,$dest=null)
    {
        //如果没有开启日志功能，直接返回
        if(!C("SAVE_LOG")){
            return;
        }
        if(is_null($dest)){
            $dest=LOG_PATH.'/'.date('Y_m_d').'.log';
        }
        if(is_dir(LOG_PATH)){
            error_log("[time]:".date("Y-m-d H:m:s")."{$level}:{$msg}\r\n",$type,$dest);
        }
    }
}
?>