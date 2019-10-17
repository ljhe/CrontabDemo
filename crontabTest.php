<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/16
 * Time: 15:51
 */

require_once "Log.php";
class crontabTest
{
    public function test($date = false)
    {
        \Log\Log::echos("begin");
        \Log\Log::SaveLog('Hello World:' . $date);
        \Log\Log::echos("end");
    }
}
//var_dump($argc);	// 参数个数
//var_dump($argv);	// 参数数组 第一个参数为文件名
$date = false;
if ($argc > 1) {
    $date = $argv[1];
}
$crontabTest = new crontabTest();
$crontabTest->test($date);