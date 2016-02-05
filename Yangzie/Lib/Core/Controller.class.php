<?php
/**
 * FileName Controller.class.php.
 * User: yangzie1192@163.com
 * Version:0.1
 * Date: 2015/12/18
 * Time: 16:34
 */
class Controller extends SmartyView
{
    private $var=array();
    public function __construct()
    {
        if(C("SMARTY_ON")){
            parent::__construct();
        }
        if(method_exists($this,'_init')){
            $this->_init();
        }
    }

    protected function success($msg,$url=null,$time=3)
    {
        $url =$url ? "window.location.href='".$url."'":"window.history.back(-1)";
        include APP_TPL_PATH.'/success.html';
        die();
    }

    protected function error($msg,$url=null,$time=3)
    {
        $url =$url ? "window.location.href='".$url."'":"window.history.back(-1)";
        include APP_TPL_PATH.'/success.html';
        die();
    }


    protected function get_tpl($tpl=null)
    {
        if(is_null($tpl)){
            $path=APP_TPL_PATH.'/'.CONTROLLER.'/'.ACTION.'.html';
        }else{
            $suffix=strrchr($tpl,'.');
            $tpl=empty($suffix)?$tpl.'.html':$tpl;
            $path=APP_TPL_PATH.'/'.CONTROLLER.'/'.$tpl;
        }

        return $path;
    }
    protected function display($tpl=null)
    {
        $path=$this->get_tpl($tpl);
        if(! is_file($path)){
            halt($path."模板文件不存在");
        }
        if(C("SMARTY_ON")){
            parent::display($path);
        }else{
            extract($this->var);
            include $path;
        }
    }
    protected function assign($var,$value)
    {
        if(C("SMARTY_ON")){
            parent::assign($var,$value);
        }else{
            $this->var[$var]=$value;
        }
    }





    /*
     * 打印所有常量
     */
    public function print_const()
    {
        $const=get_defined_constants(true);
        p($const['user']);
    }
}

?>