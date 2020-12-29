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

}