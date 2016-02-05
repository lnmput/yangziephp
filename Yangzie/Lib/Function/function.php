<?php
/**
 * FileName function.php.
 * User: yangzie1192@163.com
 * Version:0.1
 * Date: 2015/12/18
 * Time: 11:46
 */
/*
 * 打印函数
 */
function p($arr)
{
    if(is_bool($arr) || is_null($arr)){
        var_dump($arr);
    }else{
        echo "<pre style='padding: 10px; border: 1px solid #f5f5f5; border-radius: 4px; background: #CCCCCC;'>";
        print_r($arr);
        echo "</pre>";
    }
    die();
}
/*
 * 跳转函数
 */
function go($url,$time=0,$msg=''){

    if(!headers_sent()){
        $time==0 ? header("Location:".$url):header("refresh:{$time};url={$url}");
        die($msg);
    }else{
        echo '<meta http-equiv="refresh" content="'.$time.'; url='.$url.'" />';
        if($time ==0)  die($msg);
    }
}


/*
 * 加载配置项
 * 首先加载系统配置，然后在加载用户配置
 * 读取配置项 C('CODE_LEN')
 * 临时改变配置项 C('CODE_LEN',30)
 * 读取所有配置 C()
 */

function C($var=NULL,$value=NULL)
{
     static $config = array();
     //将配置项的数组保存在静态变量里
     if(is_array($var)){
        $config=array_merge($config,array_change_key_case($var,CASE_UPPER));
         return;
     }
    if(is_string($var)){
        $var=strtoupper($var);
        //两个参数传递
        if(! is_null($value)){
            $config[$var]=$value;
            return;
        }
        return isset($config[$var])?$config[$var]:NULL;
    }
     //返回所有配置项
    if(is_null($var) && is_null($value)){
        return $config;
    }
}
/*
 * error
 *
 */
function halt($error,$level='ERROR',$type=3,$dest=null)
{
   if(is_array($error)){
       Log::write($error['message'],$level,$type,$dest);
   }else{
       Log::write($error,$level,$type,$dest);
   }
    $e=array();
    if(DEBUG){
        if(! is_array($error)){
            $trace=debug_backtrace();
            $e['message']=$error;
            $e['file']=$trace[0]['file'];
            $e['line']=$trace[0]['line'];
            $e['class']=isset($trace[0]['class'])?$trace[0]['class']:'';
            $e['function']=isset($trace[0]['function'])?$trace[0]['function']:'';
            ob_start();
            debug_print_backtrace();
            $e['trace']=htmlspecialchars(ob_get_clean());
        }else{
            $e=$error;
        }
    }else{
        //debug关闭
        if($url=C("ERROR_URL")){
            go($url);
        }else{
            $e['message']=C("ERROR_MESSAGE");
            p($e);
        }
    }
    include APP_TPL_PATH.'/Halt.html';
}



function M($table)
{
    $obj=new Model($table);
    return $obj;
}


function K($model)
{
    $model.='Model';
    return new $model();
}


?>