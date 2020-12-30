<?php
/**
 * @description curl 请求
 * @author Holyrisk
 * @date 2020/12/30 10:54
 * Class RequestCurl
 */

class RequestCurl
{

    /**
     * @description GET 批量请求
     * @author Holyrisk
     * @date 2020/12/15 17:59
     * @param $urls
     * @return array|bool
     */
    public function getCurlMore($urls) {
        if (empty($urls)){
            return false;
        }
        $queue = curl_multi_init(); //允许并行地处理批处理cURL句柄。
        foreach ($urls as $i => $url) {
            $ch[$i] = curl_init();
            curl_setopt($ch[$i], CURLOPT_URL, $url); //这个curl只是说明了它是要干嘛,但是一直到 curl_multi_add_handle 都啥也没有去干,也就是说没有去模拟访问
            curl_setopt($ch[$i], CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:21.0) Gecko/20100101 Firefox/21.0'); //指明你的浏览器,这是我的
            //绕过ssl验证
            curl_setopt($ch[$i], CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch[$i], CURLOPT_SSL_VERIFYHOST, false);
            //设置获取的信息以文件流的形式返回，而不是直接输出
            curl_setopt($ch[$i], CURLOPT_RETURNTRANSFER, true);
            //只需要设置一个秒的数量就可以  超时设置
            curl_setopt($ch[$i], CURLOPT_TIMEOUT, 30);
            //curl_setopt($ch[$i], CURLOPT_COOKIEFILE, $this->_cookie); // 如果是需要登陆才能采集的话,需要加上你登陆后得到的cookie文件
            curl_setopt($ch[$i], CURLOPT_HEADER, 0);
            curl_setopt($ch[$i], CURLOPT_NOSIGNAL, TRUE);
            curl_multi_add_handle($queue, $ch[$i]); //向批处理句柄中添加句柄,后面批量的去模拟访问,抓取回资源
        }
        $responses = array(); //事先声名一个数组
        do {
            while (($code = curl_multi_exec($queue, $active)) == CURLM_CALL_MULTI_PERFORM); //返回值如果是0,那就得继续
        } while ($active); //一直到资源里还有0条为止
        //经过了刚才的循环指抓取 此时 $queue 就是一个批量的资源集了 这只是资源集,你需要把它读取出来就好
        foreach ($urls as $i => $url) {
            //要读取资源集的资源条抓取回来的内容,还得用专门的curl_multi_getcontent函数才能完成
            $responses[$i] = curl_multi_getcontent($ch[$i]);
            curl_close($ch[$i]);
        }
        curl_multi_close($queue); //把总句柄关闭
        return $responses;
    }

}