<?php
/**
 * FileName config.php.
 * User: yangzie1192@163.com
 * Version:0.1
 * Date: 2015/12/18
 * Time: 12:06
 */
/*
 * 系统配置文件
 */
return array(
    // 验证码位数
    'CODE_LEN'=>4,
    //默认时区
    "DEFAULT_TIME_ZONE"=>"PRC",
    //是否开启session
    "SESSION_AUTO_START"=>true,
    //默认控制器
    "VAR_CONTROLLER"=>'c',
    //默认方法
    "VAR_ACTION"=>'a',
    //是否开启日志
    'SAVE_LOG'=>true,
    //错误跳转地址
    "ERROR_URL"=>'',
    "ERROR_MESSAGE"=>'抱歉，出错了！！！',
    "SMARTY_ON"=>true,
    "LEFT_DELIMITER"=>"{",
    "RIGHT_DELIMITER"=>"}",
    "CACHE_ON"=>false,
    "CACHE_TIME"=>60,
    //自动加载文件
    'AUTO_LOAD_FILE'=>array(),
    /*
     * 数据库信息
     */
    'DB_CHARSET'=>'utf8',
    'DB_HOST'=>'127.0.0.1',
    'DB_PORT'=>'3306',
    'DB_USER'=>'',
    'DB_PASSWORD'=>'',
    'DB_DATABASE'=>'',
    'DB_PREFIX'=>'',
);