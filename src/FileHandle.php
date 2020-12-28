<?php
/**
 * @description 文件 处理
 * @author Holyrisk
 * @date 2020/12/28 11:51
 * Class FileHandle
 */

class FileHandle
{

    /**
     * @description 获取 CSV 内容 | 按行 读取文件
     * @author Holyrisk
     * @date 2020/12/28 15:25
     * @param $filePath
     * @param bool $position 是否 为 csv 文件读取方式 默认 是  读取 csv文件格式  false 则是 按行 读取
     * @return Generator 注意  如果为空行 则会返回 false 或者 "" | 建议 使用 empty 判断 是否为空行 | 如 一般的CSV 最后一行 为空行
     */
    public function getCsv($filePath,$position = true)
    {
        $handle = fopen($filePath, 'rb');
        if ($position == true){
            while (feof($handle)===false) {
                # code...
                yield fgetcsv($handle);//fgetcsv 读取 csv 格式 , 分割 -- 数组
            }
        }
        else
        {
            while (feof($handle)===false) {
                # code...
                yield fgets($handle);//fgets 读取一行数据 默认 一行长度 1K 1024 | 忽略掉 length将继续从流中读取数据直到行结束
            }
        }
        fclose($handle);
    }

}