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
    $connectId = $obj->connect();
    print_r($connectId);
    $loginRes = $obj->login($connectId,'wshx','1234561');
    var_dump($loginRes);
//    $dd = $obj->close($connectId);
//    var_dump($dd);
}catch (Exception $exception){
    var_dump($exception->getMessage());
}

