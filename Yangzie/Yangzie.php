<?php
/**
 * FileName Yangzie.php.
 * User: yangzie1192@163.com
 * Version:0.1
 * Date: 2015/12/18
 * Time: 10:00
 */
/**
 * 核心类
 * 功能：
 *
 */
final class Yangzie
{

    public static function run()
    {

        self::_set_const();
        //调试模式默认关闭
        defined("DEBUG") || define("DEBUG",false);
        if(DEBUG){
            self::_create_dir();
            self::_import_file();
        }else{
             error_reporting(0);
             require  TEMP_PATH.'/~boot.php';
        }
            Application::run();
    }

    //设置常量
    private static function _set_const()
    {
        $path=str_replace("\\","/",__FILE__);


        //框架目录
        define('YANGZIE_PATH',dirname($path));
        define('CONFIG_PATH',YANGZIE_PATH.'/Config');
        define('DATA_PATH',YANGZIE_PATH.'/Data');
        define('LIB_PATH',YANGZIE_PATH.'/Lib');
        define('CORE_PATH',LIB_PATH.'/Core');
        define('FUNCTION_PATH',LIB_PATH.'/Function');
        //框架扩展目录
        define('EXTENDS_PATH',YANGZIE_PATH.'/Extends');
        define('TOOL_PATH',EXTENDS_PATH.'/Tool');
        define('ORG_PATH',EXTENDS_PATH.'/Org');
        //项目目录
        define('ROOT_PATH',dirname(YANGZIE_PATH));
        //临时目录
        define('TEMP_PATH',ROOT_PATH.'/Temp');
        //日志目录
        define('LOG_PATH',TEMP_PATH.'/Log');
        define('APP_PATH',ROOT_PATH.'/'.APP_NAME);
        define('APP_CONFIG_PATH',APP_PATH.'/Config');
        define('APP_CONTROLLER_PATH',APP_PATH.'/Controller');
        define('APP_TPL_PATH',APP_PATH.'/Tpl');
        define('APP_PUBLIC_PATH',APP_TPL_PATH.'/Public');
        //编译目录
        define('APP_COMPILE_PATH',TEMP_PATH.'/'.APP_NAME.'/Compile');
        //缓存目录
        define('APP_CACHE_PATH',TEMP_PATH.'/'.APP_NAME.'/Cache');
        //是否为post提交
        define('IS_POST',$_SERVER['REQUEST_METHOD']=='POST'?true:false);
        //是否为ajax提交
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])&&$_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest'){
             define('IS_AJAX',true);
        }else{
             define('IS_AJAX',false);
        }

        //设置公共目录，前后台公用
        define('COMMON_PATH',ROOT_PATH.'/Common');
        //公共配置项
        define('COMMON_CONFIG_PATH',COMMON_PATH.'/Config');
        //公共模型
        define('COMMON_MODEL_PATH',COMMON_PATH.'/Model');
        //公共库文件夹啊
        define('COMMON_LIB_PATH',COMMON_PATH.'/Lib');


    }

    //创建应用目录
     private static function  _create_dir()
     {
         $arr=array(
             COMMON_PATH,
             COMMON_CONFIG_PATH,
             COMMON_MODEL_PATH,
             COMMON_LIB_PATH,
             APP_PATH,
             APP_CONFIG_PATH,
             APP_CONTROLLER_PATH,
             APP_TPL_PATH,
             APP_PUBLIC_PATH,
             LOG_PATH,
             APP_COMPILE_PATH,
             APP_CACHE_PATH
         );
         foreach($arr as $value){
             if( ! is_dir($value)){
                 mkdir($value,0777,true);
             }
         }
         //创建成功，失败文件
         if(! is_file(APP_TPL_PATH.'/success.html')){
             copy(DATA_PATH.'/Tpl/success.html',APP_TPL_PATH.'/success.html');
         }
         if(! is_file(APP_TPL_PATH.'/error.html')){
             copy(DATA_PATH.'/Tpl/error.html',APP_TPL_PATH.'/error.html');
         }
         if(! is_file(APP_TPL_PATH.'/Halt.html')){
             copy(DATA_PATH.'/Tpl/Halt.html',APP_TPL_PATH.'/Halt.html');
         }
     }

    //引用框架必要文件
    private static function _import_file(){
        $file_arr=array(
            CORE_PATH.'/Log.class.php',
            FUNCTION_PATH.'/function.php',
            ORG_PATH.'/Smarty/Smarty.class.php',
            CORE_PATH.'/SmartyView.class.php',
            CORE_PATH.'/Controller.class.php',
            CORE_PATH.'/Application.class.php',
        );
        foreach($file_arr as $value){
            require_once $value;
        }
    }
}

Yangzie::run();