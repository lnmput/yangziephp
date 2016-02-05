<?php
/**
 * FileName SmartyView.class.php.
 * User: yangzie1192@163.com
 * Version:0.1
 * Date: 2015/12/21
 * Time: 12:12
 */
class SmartyView
{
    private static $smarty=null;
    public function __construct()
    {
        if( ! is_null(self::$smarty))  return;
        $smarty= new Smarty();
        $smarty->left_delimiter=C('LEFT_DELIMITER');
        $smarty->right_delimiter=C('RIGHT_DELIMITER');
        $smarty->template_dir=APP_TPL_PATH.'/'.CONTROLLER.'/';
        $smarty->compile_dir=APP_COMPILE_PATH;
        $smarty->cache_dir=APP_CACHE_PATH;
        $smarty->caching=C('CACHE_ON');
        $smarty->cache_lifetime=C("CACHE_TIME");
        $smarty->unmuteExpectedErrors();
        self::$smarty=$smarty;
    }

    protected function display($tpl)
    {
        self::$smarty->display($tpl,$_SERVER['REQUEST_URI']);
    }

    protected function assign($var,$value)
    {
        self::$smarty->assign($var,$value);
    }
    protected function is_cached($tpl=null)
    {
        if(C("SMARTY_ON")) halt("请先开启smarty");
        $tpl=$this-get_tpl($tpl);
        return self::$smarty->is_cached($tpl,$_SERVER['REQUEST_URI']);
    }
}