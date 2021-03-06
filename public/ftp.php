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
//        echo "返回当前目录名 ".$pwdRes;
//        echo "<br />";
//        $pwdRes = $pwdRes.'soft\FileZilla Server 中文版';
//        echo "切换当前目录名 ".$pwdRes;
//        echo "<br />";
        //--------------------------------------------------------------------------------------------------------------
        // 获取文件列表 start
        //--------------------------------------------------------------------------------------------------------------
        //获取 路径下的文件列表 |  返回指定目录下文件的详细列表 | 有权限显示
        $rawList = $obj->rawlist($connectId,$pwdRes,true);
//        echo "<br />";
//        var_dump($rawList);
//        //获取 路径下的文件列表 | 返回给定目录的文件列表 |  不显示权限
//        $nList = $obj->nlist($connectId,$pwdRes);
//        echo "<br />";
//        var_dump($nList);
//        //获取 路径下的文件列表 | 返回给定目录的文件列表 |  不显示权限  不显示路径 显示 区分 是否 文件夹 还是 文件
//        $mlsd = $obj->mlsd($connectId,$pwdRes);
//        echo "<br />";
//        var_dump($mlsd);
        //--------------------------------------------------------------------------------------------------------------
        // 获取文件列表 end
        //--------------------------------------------------------------------------------------------------------------

        //--------------------------------------------------------------------------------------------------------------
        // 文件夹目录 start
        //--------------------------------------------------------------------------------------------------------------
        //创建文件夹
//        $mkdirRes = $obj->mkdir($connectId,'/www/cp');
//        var_dump($mkdirRes);
        //重命名 文件夹
//        $ranameRes = $obj->rename($connectId,'/www/ppp','www2');
//        var_dump($mkdirRes);
        //删除 FTP 服务器上的一个目录 | 空目录
//        $rmdirRes = $obj->rmdir($connectId,'/aaa/bbb');
//        var_dump($rmdirRes);
        //重命名 文件
//        $renameRes = $obj->rename($connectId,'测试文件text.txt','测试文件-重命名.txt');
//        var_dump($renameRes);
        //--------------------------------------------------------------------------------------------------------------
        // 文件夹目录 end
        //--------------------------------------------------------------------------------------------------------------

        //--------------------------------------------------------------------------------------------------------------
        // 文件 start
        //--------------------------------------------------------------------------------------------------------------
        //普通 同步上传
//        $remote_file = '/www/iu.png';
//        $local_file = 'C:\Users\DDHK\Pictures\Saved Pictures\iu2.png';
//        $putRes = $obj->put($connectId,$remote_file,$local_file);
//        var_dump($putRes);
        //异步上传 -- 推荐使用这个
//        $remote_file = '/www/iu2.png';
//        $local_file = 'C:\Users\DDHK\Pictures\Saved Pictures\iu2.png';
//        $nbputRes = $obj->nb_put($connectId,$remote_file,$local_file);
//        var_dump($nbputRes);
        //从 FTP 服务器上下载一个文件 【普通 同步下载】
//        $remote_file = '/www/iu2.png';
//        $local_file = 'C:\Users\DDHK\Pictures\Saved Pictures\iu下载.png';
//        $getRes = $obj->get($connectId,$local_file,$remote_file);
//        var_dump($getRes);
        //从 FTP 服务器上获取文件并写入本地文件（non-blocking） 【异步下载】 -- 推荐使用这个
//        $remote_file = '/www/iu.png';
//        $local_file = 'C:\Users\DDHK\Pictures\Saved Pictures\iu异步下载.png';
//        $nbgetRes = $obj->nb_get($connectId,$local_file,$remote_file);
//        var_dump($nbgetRes);
        //删除文件
//        $filePath = '/www/iu.png';
//        $nbgetRes = $obj->delete($connectId,$filePath);
//        var_dump($nbgetRes);
//        $dirPath = '/aaa';
        $dirPath = $pwdRes;
        $dirDelete = $obj->deleteDir($connectId,$dirPath);
        var_dump($dirDelete);
//        echo json_encode($dirDelete);

        //--------------------------------------------------------------------------------------------------------------
        // 文件 start
        //--------------------------------------------------------------------------------------------------------------



    }
    //断开连接
    $obj->close($connectId);
}catch (Exception $exception){
    var_dump($exception->getMessage());
    var_dump($exception->getCode());
}

