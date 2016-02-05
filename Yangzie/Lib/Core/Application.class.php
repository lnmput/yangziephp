<?php
/**
 * FileName Application.class.php.
 * User: yangzie1192@163.com
 * Version:0.1
 * Date: 2015/12/18
 * Time: 11:49
 */
final class Application
{
    public static function run()
    {
        //框架初始化配置
        self::_init();
        //接管错误处理
        set_error_handler(array(__CLASS__,'error'));
        //处理致命错误
        register_shutdown_function(array(__CLASS__,'fatal_error'));
        //引入用户自定义文件
        self::_user_import();
        //设置外部路径
        self::_set_url();
        //自动载入
        spl_autoload_register(array(__CLASS__,'_autoload'));
        //创建一个demo
        self::_create_demo();
        //运行控制器
        self::_app_run();
    }
    /*
     * 初始化框架
     */
    private static function _init()
    {
        //加载配置项，用户定义的配置的优先级会高于框架
        C(include CONFIG_PATH.'/config.php' );
        //公共配置项
        $commonPath=COMMON_CONFIG_PATH.'/Config.php';
        $commonConfig=<<<EOF
<?php
  return array(
   // 配置项=>配置值

  );
?>
EOF;
        if(! is_file($commonPath)){
            file_put_contents($commonPath,$commonConfig);
        }
        C(include $commonPath);
        //用户配置项
        $userPath=APP_CONFIG_PATH.'/config.php';
        $userConfig=<<<EOF
<?php
  return array(
   // 配置项=>配置值

  );
?>
EOF;
    if(! is_file($userPath)){
        file_put_contents($userPath,$userConfig);
     }
        //加载用户配置项
        C(include $userPath);

        //设置默认时区
        date_default_timezone_set(C("DEFAULT_TIME_ZONE"));
        //是否开启session
        if(C("SESSION_AUTO_START")){
            session_start();
        }
    }
     /*
      * 设置外部路径,应用  css js  image
      * http://
      */
     private static function _set_url()
     {
         $path='http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
         $path=str_replace("\\",'/',$path);
         //项目
         define("__APP__",$path);
         define("__ROOT__",dirname(__APP__));
         define("__TPL__",__ROOT__.'/'.APP_NAME.'/Tpl');
         define("__PUBLIC__",__TPL__.'/Public');
     }

    /*
     * 自动载入函数
     */
    private static function _autoload($classname)
    {
        switch(true){
            case strlen($classname)>10 && substr($classname,-10)=='Controller':
                $path=APP_CONTROLLER_PATH.'/'.$classname.'.class.php';
                if(! is_file($path)){
                    $emptyPath=APP_CONTROLLER_PATH.'/EmptyController.class.php';
                    if(is_file($emptyPath)){
                        include $emptyPath;
                        return;
                    }else{
                        halt($path.'控制器未找到');
                    }
                };
                include $path;
                break;
            //模型类
            case strlen($classname)>5 && substr($classname,-5)=='Model':
                 $path=COMMON_MODEL_PATH.'/'.$classname.'.class.php';
                 include $path;
                break;
            default:
                $path=TOOL_PATH.'/'.$classname.'.class.php';

                if(! is_file($path))  halt($path.'类未找到');
                include $path;
                break;
        }
    }
    /*
     * 错误处理
     */
    public  static function error($errno,$error,$file,$line)
    {
        switch($errno){
            case E_ERROR:
            case E_PARSE:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
                 $msg=$error.$file."第{$line}行";
                 halt($msg);
                break;

            case E_STRICT:
            case E_USER_WARNING:
            case E_USER_NOTICE:
            default:
                if(DEBUG){
                   // echo DATA_PATH.'/Tpl/notice.html';
                    include DATA_PATH.'/Tpl/notice.html';
                }
            break;
        }
    }
    /*
     * 致命错误处理
     */
    public static function fatal_error()
    {
        if($e=error_get_last()){
           self::error($e['type'],$e['message'],$e['file'],$e['line']);
        }
    }

    /*
     *创建一个demo,默认的控制器
     *
     */
    private  static function _create_demo()
    {
       $path=APP_CONTROLLER_PATH.'/IndexController.class.php';
        $str=<<<EOF
<?php
  class IndexController extends Controller
  {
      public function index()
      {
         echo "框架可以正常运行了";
      }
  }
?>
EOF;
    if(! is_file($path)){
        file_put_contents($path,$str);
    }

    }
   /*
    *  运行框架
    */
    private static function _app_run()
    {
        $c = isset($_GET[C("VAR_CONTROLLER")])?$_GET[C("VAR_CONTROLLER")]:'Index';
        $a = isset($_GET[C("VAR_ACTION")])?$_GET[C("VAR_ACTION")]:'index';
        define('CONTROLLER',$c);
        define('ACTION',$a);
        $c.='Controller';
        if(class_exists($c)){
            $obj=new $c();
            if(!method_exists($obj,$a)){
                if(method_exists($obj,'__empty')){
                    $obj->__empty();
                }else{
                    halt($c.'控制器中不存在'.$a.'方法');
                }
            }else{
                $obj->$a();
            }
        }else{
            $obj=new EmptyController();
            $obj->index();
        }
    }
    /*
     * 载入公共目录下Common/Lib下用户自定义的函数，类等文件
     */
    private static  function _user_import()
    {
         $fileArr=C('AUTO_LOAD_FILE');
         if(!empty($fileArr) && is_array($fileArr)){
             foreach($fileArr as $value){
                 require_once COMMON_LIB_PATH.'/'.$value;
             }
         }
    }
}
?>
