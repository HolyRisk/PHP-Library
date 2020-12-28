<?php
/**
 * @description 引用
 * @author Holyrisk
 * @date 2020/12/28 14:45
 */
require_once 'getPhpFile.php';

$host = '127.0.0.1';

try{
    $obj = new FtpHandle($host);
    $result = $obj->connect();
    var_dump($result);
    sleep(3);
    $dd = $obj->close($result);
    var_dump($dd);
}catch (Exception $exception){
    var_dump($exception->getMessage());
}

