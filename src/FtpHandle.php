<?php
/**
 * @description FTP 处理
 * @author Holyrisk
 * @date 2020/12/28 11:53
 * Class FtpHandle
 */

class FtpHandle
{
    /**
     * 连接 IP 或者 域名
     * @var
     */
    protected $host;

    /**
     * 端口
     * @var
     */
    protected $port;

    /**
     * @description 初始化 | 不建议 在初始化连接 资源 会导致 常驻进程失败
     * @author Holyrisk
     * @date 2020/12/28 17:35
     * FtpHandle constructor.
     * @param $host 连接 IP 或者 域名
     * @param int $port 端口
     * @throws Exception
     */
    public function __construct($host,$port = 21)
    {
        //code
        if (empty($port)){
            $port = 21;//默认 端口
        }
        $this->host = $host;
        $this->port = $port;
        //校验 ftp 扩展 是否 开启 可用
        $this->check();
    }

    /**
     * @description 校验 ftp 扩展 是否 开启 可用
     * @author Holyrisk
     * @date 2020/12/28 18:06
     * @throws Exception
     */
    protected function check()
    {
        // get_loaded_extensions()，返回所有已安装的扩展，格式为一维数组
        if (in_array('ftp', get_loaded_extensions()) == false){
            throw new Exception("请开启PHP ftp 扩展，才可以使用");
        }
    }

    /**
     * @description 连接ftp服务器
     * @author Holyrisk
     * @date 2020/12/28 18:06
     * @return resource
     * @throws Exception
     */
    public function connect()
    {
        $connectId =  ftp_connect($this->host,$this->port);
        if ($connectId == false){
            throw new Exception("连接ftp服务器失败");
        }
        return $connectId;
    }

    /**
     * @description 关闭一个 FTP 连接
     * @author Holyrisk
     * @date 2020/12/28 18:13
     * @param $connectId 连接 资源
     * @return bool
     */
    public function close($connectId)
    {
        $result = ftp_close($connectId);
        return $result;
    }

    /**
     * @description 设置 异常 错误信息 显示 和 捕获
     * @author Holyrisk
     * @date 2020/12/29 9:51
     */
    public function setLogError()
    {
        // 监听捕获的错误级别
        error_reporting(E_ALL);
        // 是否开启错误信息回显 将错误输出至标准输出（浏览器/命令行） 错误日志的保存文件。注意：如果路径无效，display_errors 会被强制开启。
        //是否记录错误日志。默认关闭，生产环境下强烈建议
        ini_set('display_errors',false);
        // 是否开启错误日志记录 将错误记录至 ini：error_log 指定文件
        ini_set('log_errors',true);
        //ini_set('error_log',__DIR__.'/php-errors.log');
    }

    /**
     * @description ftp 账号登录
     * @author Holyrisk
     * @date 2020/12/29 9:20
     * @param $connectId 资源 ID
     * @param $ftp_user_name 用户名
     * @param $ftp_user_pass 密码
     * @return bool
     * @throws Exception
     */
    public function login($connectId,$ftp_user_name,$ftp_user_pass)
    {
        //登录失败 会打印 Warning 错误显示
        $this->setLogError();

        $result = ftp_login($connectId, $ftp_user_name, $ftp_user_pass);
        if ($result == false){
            $error = error_get_last();//成功时返回 true， 或者在失败时返回 false。 如果登录失败，PHP 会抛出一个警告。
            throw new Exception("登录失败，账号或密码错误");//.$error['message']
        }
        return $result;
    }

    /**
     * @description 设置 打开 或者 关闭 被动模式
    总结
    下面的图表会帮助管理员们记住每种FTP方式是怎样工作的：

    主动FTP：
    命令连接：客户端 >1023端口 -> 服务器 21端口
    数据连接：客户端 >1023端口 <- 服务器 20端口

    被动FTP：
    命令连接：客户端 >1023端口 -> 服务器 21端口
    数据连接：客户端 >1023端口 -> 服务器 >1023端口

    下面是主动与被动FTP优缺点的简要总结：
    主动FTP对FTP服务器的管理有利，但对客户端的管理不利。因为FTP服务器企图与客户端的高位随机端口建立连接，而这个端口很有可能被客户端的防火墙阻塞掉。被动FTP对FTP客户端的管理有利，但对服务器端的管理不利。因为客户端要与服务器端建立两个连接，其中一个连到一个高位随机端口，而这个端口很有可能被服务器端的防火墙阻塞掉。
    幸运的是，有折衷的办法。既然FTP服务器的管理员需要他们的服务器有最多的客户连接，那么必须得支持被动FTP。我们可以通过为FTP服务器指定一个有限的端口范围来减小服务器高位端口的暴露。这样，不在这个范围的任何端口会被服务器的防火墙阻塞。虽然这没有消除所有针对服务器的危险，但它大大减少了危险。
     * @author Holyrisk
     * @date 2020/12/29 10:49
     * @param $connectId
     * @param bool $pasv 如果参数 pasv 为 true，打开被动模式传输 (PASV MODE) ，否则则关闭被动传输模式。
     * @return bool
     */
    public function pasv($connectId,$pasv = false)
    {
        $result = ftp_pasv($connectId,$pasv);
        return $result;
    }

    /**
     * @description 返回当前目录名
     * @author Holyrisk
     * @date 2020/12/29 11:16
     * @param $connectId
     * @return string
     * @throws Exception
     */
    public function pwd($connectId)
    {
        $result = ftp_pwd($connectId);
        if ($result === false){
            throw new Exception("返回当前目录名失败");
        }
        return $result;
    }

    //--------------------------------------------------------------------------------------------------------------
    // 获取文件列表 start
    //--------------------------------------------------------------------------------------------------------------

    /**
     * @description 获取 路径下的文件列表 |  返回指定目录下文件的详细列表 | 有权限显示
     * @author Holyrisk
     * @date 2020/12/29 11:23
     * @param $connectId
     * @param $pwdPath ftp 路径 如 通过 pwd 获取的 /
     * @param bool $recursive ftp_rawlist() 函数将执行 FTP LIST 命令，并把结果做为一个数组返回。 如果此参数为 true，实际执行的命令将会为 LIST -R。
     * @return array
     */
    public function rawlist($connectId,$pwdPath,$recursive = false)
    {
        $result = ftp_rawlist($connectId,$pwdPath,$recursive);
        return $result;
    }

    /**
     * @description 获取 路径下的文件列表 | 返回给定目录的文件列表 |  不显示权限 显示路径
     * @author Holyrisk
     * @date 2020/12/29 11:40
     * @param $connectId
     * @param $pwdPath 指定要列表的目录。本参数接受带参数的形式，例如： ftp_nlist($conn_id, "-la /you/dir"); 注意此参数不对传入值做处理，在目录或者文件名包括空格或特殊的情况下，可能会存在问题。
     * @return array
     */
    public function nlist($connectId,$pwdPath)
    {
        $result = ftp_nlist($connectId,$pwdPath);
        return $result;
    }

    /**
     * @description 获取 路径下的文件列表 | 返回给定目录的文件列表 |  不显示权限  不显示路径 显示 区分 是否 文件夹 还是 文件
     * @author Holyrisk
     * @date 2020/12/29 11:44
     * @param $connectId
     * @param $pwdPath
     * @return array [name 名称 type 类型 文件还是文件夹  modify 最后修改时间  类型为 file 还会出现 size 参数]
     */
    public function mlsd($connectId,$pwdPath)
    {
        $result = ftp_mlsd($connectId,$pwdPath);
        return $result;
    }

    //--------------------------------------------------------------------------------------------------------------
    // 获取文件列表 end
    //--------------------------------------------------------------------------------------------------------------

    //--------------------------------------------------------------------------------------------------------------
    // 文件夹目录 start
    //--------------------------------------------------------------------------------------------------------------

    /**
     * @description 在 FTP 服务器上，创建指定的目录
     * @author Holyrisk
     * @date 2020/12/29 14:17
     * @param $connectId
     * @param $dirNamePath 文件夹名称 及 路径 如果是 多层 那么 /第一层名称/第n层名称
     * @return string 若文件夹已经存在 则返回false
     */
    public function mkdir($connectId,$dirNamePath)
    {
        $result = ftp_mkdir($connectId,$dirNamePath);
        return $result;
    }

    /**
     * @description 删除 FTP 服务器上的一个目录 | 空目录
     * @author Holyrisk
     * @date 2020/12/29 14:32
     * @param $connectId
     * @param $dirNamePath 文件夹 路径 -- 绝对路径
     * @return bool
     */
    public function rmdir($connectId,$dirNamePath)
    {
        $result = ftp_rmdir($connectId,$dirNamePath);
        return $result;
    }

    //--------------------------------------------------------------------------------------------------------------
    // 文件夹目录 end
    //--------------------------------------------------------------------------------------------------------------

    //--------------------------------------------------------------------------------------------------------------
    // 文件夹目录 / 文件 都有 start
    //--------------------------------------------------------------------------------------------------------------

    /**
     * @description 更改 FTP 服务器上的文件或目录  名称 | 绝对路径 否者相当于 文件 、 文件夹 剪切
     * @author Holyrisk
     * @date 2020/12/29 14:26
     * @param $connectId
     * @param $fromOldName 必需。规定要改名的文件或目录。 -- 绝对路径
     * @param $toNewName 必需。规定文件或目录的新名称。-- 绝对路径 否者相当于 文件 、 文件夹 剪切
     * @return bool
     */
    public function rename($connectId,$fromOldName,$toNewName)
    {
        $result = ftp_rename($connectId,$fromOldName,$toNewName);
        return $result;
    }

    /**
     * @description 删除 整个文件夹数据 | 文件夹 含有数据的
     * @author Holyrisk
     * @date 2020/12/29 17:38
     * @param $connectId
     * @param $dirNamePath 删除文件夹的路径
     * @return bool
     */
    public function deleteDir($connectId,$dirNamePath)
    {
        //打开 列表
        $lists = ftp_mlsd($connectId, $dirNamePath);
        foreach($lists as $list){
            $full = $dirNamePath . '/' . $list['name'];
            if($list['type'] == 'dir'){
                //循环
                $this->deleteDir($connectId, $full);
            }else{
                //删除文件
                ftp_delete($connectId, $full);
            }
        }
        //删除 原路径
        $result = ftp_rmdir($connectId, $dirNamePath);
        return $result;
    }

    //--------------------------------------------------------------------------------------------------------------
    // 文件夹目录 / 文件 end
    //--------------------------------------------------------------------------------------------------------------

    //--------------------------------------------------------------------------------------------------------------
    // 文件 start
    //--------------------------------------------------------------------------------------------------------------

    /**
     * @description 上传指定的本地文件到 FTP 服务器 【普通 同步上传】
     * @author Holyrisk
     * @date 2020/12/29 14:55
     * @param $connectId
     * @param $remote_file 远程文件路径 | 如果 文件夹未创建 直接 上传文件过来 会导致 上传失败 false
     * @param $local_file 本地文件路径
     * @param int $mode 传送模式，只能为 FTP_ASCII（文本模式）或 FTP_BINARY（二进制模式）。
     * @param int $startpos 指定开始上传的位置，一般用来文件续传。
     * @return bool
     */
    public function put($connectId,$remote_file,$local_file,$mode = FTP_BINARY,$startpos = 0)
    {
        $result = ftp_put($connectId,$remote_file,$local_file,$mode,$startpos);
        return $result;
    }

    /**
     * @description 上传指定的本地文件到 FTP 服务器 【异步上传 （non-blocking） 】 -- 推荐使用这个  与函数 ftp_put() 不同的是，此函数上传文件的时候采用的是异步传输模式，也就意味着在文件传送的过程中，你的程序可以继续干其它的事情。
     * @author Holyrisk
     * @date 2020/12/29 15:45
     * @param $connectId
     * @param $remote_file 远程文件路径 | 如果 文件夹未创建 直接 上传文件过来 会导致 上传失败 false
     * @param $local_file 本地文件路径
     * @param int $mode 传送模式，只能为 FTP_ASCII（文本模式）或 FTP_BINARY（二进制模式）。
     * @param int $startpos 指定开始上传的位置，一般用来文件续传。
     * @return bool
     * @throws Exception
     */
    public function nb_put($connectId,$remote_file,$local_file,$mode = FTP_BINARY,$startpos = 0)
    {
        $result = ftp_nb_put($connectId,$remote_file,$local_file,$mode,$startpos);
        while ($result == FTP_MOREDATA) {
            // 可以同时干其它事
            //echo ".";
            // 继续上传...
            $result = ftp_nb_continue($connectId);
        }
        if ($result != FTP_FINISHED) {
            throw new Exception("上传过程中发生错误...");
        }
        return true;
    }

    /**
     * @description 从 FTP 服务器上下载一个文件 【普通 同步下载】
     * @author Holyrisk
     * @date 2020/12/29 16:30
     * @param $connectId
     * @param $local_file 文件本地的路径（如果文件已经存在，则会被覆盖）。
     * @param $remote_file 文件的远程路径。
     * @param int $mode 传送模式。只能为 (文本模式) FTP_ASCII 或 (二进制模式) FTP_BINARY 中的其中一个。
     * @param int $resumepos 从远程文件的这个位置继续下载。
     * @return bool
     */
    public function get($connectId,$local_file,$remote_file,$mode = FTP_BINARY,$resumepos = 0)
    {
        $result = ftp_get($connectId,$local_file,$remote_file,$mode,$resumepos);
        return $result;
    }

    /**
     * @description 从 FTP 服务器上获取文件并写入本地文件（non-blocking） 【异步下载】 -- 推荐使用这个   和 ftp_get() 不同之处，在于此函数是通过异步的方式来获取文件，这意味着，你的程序可以在下载文件的同时，同步进行其它操作。
     * @author Holyrisk
     * @date 2020/12/29 17:04
     * @param $connectId
     * @param $local_file 保存到的本地文件路径（如果文件已存在会被覆盖）。
     * @param $remote_file 远程文件路径。
     * @param int $mode 传送模式。只能为 (文本模式) FTP_ASCII 或 (二进制模式) FTP_BINARY 中的其中一个。
     * @param int $resumepos 开始下载文件的起始位置。
     * @return bool
     * @throws Exception
     */
    public function nb_get($connectId,$local_file,$remote_file,$mode = FTP_BINARY,$resumepos = 0)
    {
        $result = ftp_nb_get($connectId,$local_file,$remote_file,$mode,$resumepos);
        while ($result == FTP_MOREDATA) {

            // 可以同步干其它事
            //echo ".";
            // 继续下载...
            $result = ftp_nb_continue($connectId);
        }
        if ($result != FTP_FINISHED) {
            throw new Exception("下载文件出错...");
        }
        return true;
    }

    /**
     * @description 删除 FTP 服务器上的一个文件
     * @author Holyrisk
     * @date 2020/12/29 17:33
     * @param $connectId
     * @param $filePath 函数用来删除 FTP 服务器上的一个由参数 path 指定的的文件。
     * @return bool
     */
    public function delete($connectId,$filePath)
    {
        $result = ftp_delete($connectId,$filePath);
        return $result;
    }

    //--------------------------------------------------------------------------------------------------------------
    // 文件 end
    //--------------------------------------------------------------------------------------------------------------


}