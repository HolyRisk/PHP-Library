<?php
/**
 * @description 字符串 处理
 * @author Holyrisk
 * @date 2020/12/28 11:49
 * Class StringHandle
 */

class StringHandle
{

    /**
     * @description 判断字符串 前面是否有 符合的 要删除的字符串 有 则删除 否 直接返回 | 只删除 一次
     * @author Holyrisk
     * @date 2020/12/28 15:08
     * @param string $string 处理的字符串
     * @param string $deleteString 要删除的字符串
     * @param int $position 方式： 默认 1 从头开始  2 从尾开始
     * @return bool|string
     */
    public function delete($string,$deleteString,$position = 1)
    {
        $length = strlen($deleteString);
        switch ($position){
            case 1:
                //从头开始
                //截取
                $interceptTemp = substr($string,0,$length);
                if($interceptTemp == $deleteString) {
                    $string =  substr($string,$length);
                }
                break;
            case 2:
                //从尾开始
                //截取
                $interceptTemp = substr($string,-1,$length);
                if($interceptTemp == $deleteString) {
                    $string =  substr($string,0,-$length);
                }
                break;
            default:
                break;
        }
        return $string;
    }

}