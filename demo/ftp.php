<?php
/**
 * @description 引用
 * @author Holyrisk
 * @date 2020/12/28 14:45
 */
require_once 'getPhpFile.php';

$host = '127.0.0.1';
$port = 21;

try{
    $obj = new FtpHandle($host,$port);
    //连接
    $connectId = $obj->connect();
    //登录
    $loginRes = $obj->login($connectId,'wshx','123456');
    if ($loginRes == true){
        //设置 打开 或者 关闭 被动模式
        $pasvRes = $obj->pasv($connectId,true);
        var_dump($pasvRes);
    }

//    $dd = $obj->close($connectId);
//    var_dump($dd);
}catch (Exception $exception){
    var_dump($exception->getMessage());
}

