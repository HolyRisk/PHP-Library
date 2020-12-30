<?php
/**
 * @description 字符串转换处理
 * @author Holyrisk
 * @date 2020/12/30 10:25
 * Class TruncateString
 */

class TruncateString
{

    /**
     * @description 下划线转驼峰
     * @author Holyrisk
     * @date 2020/12/30 10:20
     * @param $str
     * @return string|string[]|null
     */
    public function camelize($str)
    {
        $str = preg_replace_callback('/([-_]+([a-z]{1}))/i', function ($matches) {
            return strtoupper($matches[2]);
        }, $str);
        return $str;
    }

    /**
     * @description 驼峰转下划线
     * @author Holyrisk
     * @date 2020/11/8 19:18
     * @param        $str
     * @return string
     */
    public function uncamelize($str)
    {
        $str = str_replace("_", "", $str);
        $str = preg_replace_callback('/([A-Z]{1})/', function ($matches) {
            return '_' . strtolower($matches[0]);
        }, $str);
        return ltrim($str, "_");
    }

    /**
     * @description 一维关系数组的 key | 驼峰命名转下划线命名
     * @author Holyrisk
     * @date 2020/11/8 19:25
     * @param     $arr 要转换的 关系数组
     * @param int $type 1、驼峰命名转下划线命名 2、下划线转驼峰
     * @return array
     */
    public function arrKeysStringModel($arr,$type = 1)
    {
        $result = [];
        if(!empty($arr)){
            $arrKeys = array_keys($arr);
            foreach($arrKeys as $key => $val){
                switch($type){
                    case 1:
                        $res = $this->uncamelize($val);
                        break;
                    case 2:
                        $res = $this->camelize($val);
                        break;
                    default:
                        $res = $val;
                        break;
                }
                $result[$res] = $arr[$val];
            }
        }
        return $result;
    }

}