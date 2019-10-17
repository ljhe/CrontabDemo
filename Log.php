<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/16
 * Time: 15:16
 */

namespace Log;

class Log
{
    /**
     * 保存日志
     * @param $data
     * @param bool $title
     */
    public static function SaveLog ($data, $title = false)
    {
        if ( ! $title) {
            $title = date('Ymd', time());
        }

        // 读写方式打开文件 将文件指针指向文件末尾 如果文件不存在 则创建
        $myFile = fopen(__DIR__ . '/' . $title, 'a+');
        fwrite($myFile, date('Y-m-d H:i:s') . ':' . $data . PHP_EOL);
        // 关闭打开的文件
        fclose($myFile);
    }

    /**
     * 输出内容
     * 因为cmd 编码为 gb2312 所以输出的中文必须要转码
     * @param $message
     * @param string $br
     */
    public static function echos($message,$br = "\n")
    {
        switch (true) {
            case stristr(PHP_OS, 'WIN'):
                $t = eval('return '.mb_convert_encoding(var_export($message,true),'gb2312','utf-8').';');
                break;
            case stristr(PHP_OS, 'DAR'):
                $t = eval('return '.mb_convert_encoding(var_export($message,true),'utf-8','auto').';');
                break;
            case stristr(PHP_OS, 'LINUX'):
                $t = eval('return '.mb_convert_encoding(var_export($message,true),'utf-8','auto').';');
                break;
            default :
                $t = eval('return '.mb_convert_encoding(var_export($message,true),'utf-8','auto').';');
        }
        print_r($t);
        echo $br;
    }
}