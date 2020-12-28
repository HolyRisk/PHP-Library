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


}