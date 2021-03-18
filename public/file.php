<?php
/**
 * @description 引用
 * @author Holyrisk
 * @date 2020/12/28 15:26
 */
require_once 'getPhpFile.php';

$obj = new FileHandle();

//测试 CSV 文件
$filePath = $fileRootPath.'/demo/data/csv/csvtest.csv';

//读取文件
//$data = $obj->getCsv($filePath);
$data = $obj->getCsv($filePath,false);

$arr = [];
foreach ($data as $key => $val)
{
    //空行
    if ($val === false){
        continue;
    }
    $arr[] = $val;
}
echo json_encode($arr);