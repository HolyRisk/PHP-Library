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
        //返回当前目录名
        $pwdRes = $obj->pwd($connectId);
        echo "返回当前目录名 ".$pwdRes;
        echo "<br />";
        //获取 路径下的文件列表 |  返回指定目录下文件的详细列表 | 有权限显示
        $rawList = $obj->rawlist($connectId,$pwdRes,true);
        echo "<br />";
        var_dump($rawList);
    }
    //断开连接
    $obj->close($connectId);
}catch (Exception $exception){
    var_dump($exception->getMessage());
}

