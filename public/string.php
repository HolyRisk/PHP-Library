<?php
/**
 * @description 引用
 * @author Holyrisk
 * @date 2020/12/28 14:45
 */
require_once 'getPhpFile.php';

$obj = new StringHandle();

//要处理的字符串
$stringDel = "0084123456789";
//从头开始
echo $obj->delete($stringDel,'0',1);
echo PHP_EOL;
//从尾开始
echo $obj->delete($stringDel,'9',2);