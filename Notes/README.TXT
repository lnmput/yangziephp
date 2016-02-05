关于PHP框架的几点说明：
1.单入口，在应用目录下仅仅有index.php一个文件，其他关于框架自身
  的文件被放在一个单独的文件夹下面，你可以用你框架的名字来命名该
  文件夹，如果你框架名字叫ThinkPHP或者是Yangzie,那么就叫这个名字吧！
2.帮助用户创建应用程序目录，即应该在框架初始化的过程中创建应用程序
  目录，Application;
3.简要目录结构：
  --www
    --index.php             //单一入口文件,定义应用程序目录，引入核心框架类
    --Yangzie               //框架文件
      --Yangzie.php         //核心框架类，在入口文件引入
      --Config              //框架配置目录
        --config.php
      --Data
        --Tpl               //存放一些常用的模板文件，如操作成功，失败等显示的页面
      --Lib
        --Core
          --Controller.class.php
          --Application.class.php
        --Function
          --function.php    //这里存放一些系统常用的函数，比如像tp框架的A，B，M，D 函数
    --Index                 //系统帮助用户创建的应用程序目录
      --Config
        --config.php
      --Controller
        --IndexController.class.php
      --Tpl
        --Public
-----------------------------------------------------------------------
1.index.php 入口文件
  功能： 定义配置用户应用程序目录
         define('APP_NAME','Index');
        引入核心框架文件
         require 'Yangzie/Yangzie.php';
2.Yangzie.php  框架核心文件
  功能：
      定义常量
         1.框架各个文件夹目录，最好每个用到的文件对应的文件夹都创建对应的常量，例如：
           define('YANGZIE_PATH',dirname($path));        框架文件根目录
           define('CONFIG_PATH',YANGZIE_PATH.'/Config'); 框架文件配置文件目录
         2.定义应用程序目录，根据入口文件定义的常量进行设定，用于系统自动为用户创建应用程序目录结构
           define('ROOT_PATH',dirname(YANGZIE_PATH));
           define('APP_PATH',ROOT_PATH.'/'.APP_NAME);     应用程序根目录路径
           define('APP_CONFIG_PATH',APP_PATH.'/Config');
           define('APP_CONTROLLER_PATH',APP_PATH.'/Controller');   应用程序下的Controller文件夹路径
      创建目录
           根据上一步骤定义的常量，通过循环为用户创建应用程序文件夹，仅仅是目录结构
           $arr=array(
                        APP_PATH,
                        APP_CONFIG_PATH,
                        APP_CONTROLLER_PATH,
                        APP_TPL_PATH,
                        APP_PUBLIC_PATH
                    );
                    foreach($arr as $value){
                        if( ! is_dir($value)){
                            mkdir($value,0777,true);
                        }
                    }
      引入必要的文件
          通过循环引入初始状态一些必要的文件
          $file_arr=array(
                       FUNCTION_PATH.'/function.php',       // 一些自定义的函数
                       CORE_PATH.'/Controller.class.php',   //用户创建的所有的类文件都需要继承的一个类文件
                       CORE_PATH.'/Application.class.php'   // 框架初始化文件
                   );
                   foreach($file_arr as $value){
                       require_once  $value;
                       }
      说明：该文件需要自运行，被入口文件引入以后，里面的方法需要自己全部执行；
3.Application.class.php  框架初始化文件
  功能：
     初始化框架
        1.分别引入框架的配置文件和用户自定义的配置文件
          注意：首先引入框架的，然后在引入用户的，这样做可以保证用户自定义的配置文件的优先级高于系统
          在引入用户配置文件时需要判断是否存在，存在则引入，不存在则帮用户创建一个并引入
        2.设置默认时区，时区在配置文件中指定
        3.是否开启session,在配置文件中指定
     设置外部路径
       这里的路径并非绝对路径，而是带有http标识的通过浏览器访问的一些路径
            $path='http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
            $path=str_replace("\\",'/',$path);
            //项目
            define("__APP__",$path);
            define("__ROOT__",dirname(__APP__));
            define("__TPL__",__ROOT__.'/'.APP_NAME.'/Tpl');
            define("__PUBLIC__",__TPL__.'/Public');
     自动载入函数
        实现所有的类都能自动载入
            spl_autoload_register(array(__CLASS__,'_autoload'));
     给用户创建一个默认的控制器IndexController.class.php，并附带一个默认的方法index
        该控制器需要继承Controller.class.php
        同样在创建该控制器的时候一定要判断该控制器是否存在

     使我们的框架运行起来
        调用对应的控制器和相应的方法
            $c = isset($_GET[C("VAR_CONTROLLER")])?$_GET[C("VAR_CONTROLLER")]:'Index';
            $a = isset($_GET[C("VAR_ACTION")])?$_GET[C("VAR_ACTION")]:'index';
            $c.='Controller';
            $obj=new $c();
            $obj->$a();



















