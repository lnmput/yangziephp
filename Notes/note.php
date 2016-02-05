<?php
/**
 * FileName note.php.
 * User: yangzie1192@163.com
 * Version:0.1
 * Date: 2015/12/19
 * Time: 18:19
 */
    function one($str1, $str2)
    {
        two("Glenn", "Quagmire");
    }
    function two($str1, $str2)
    {
        three("Cleveland", "Brown");
    }
    function three($str1, $str2)
    {
        print_r(debug_backtrace());
    }

    one("Peter", "Griffin");
