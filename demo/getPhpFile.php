<?php
//当前执行 PHP 文件路径
$filePathBase = __FILE__;
//当前执行 PHP 文件夹路径
$fileDirPathBase = __DIR__;
//因为 只有两层 所以 上一层 为根目录 上一层文件夹路径
$fileRootPath = dirname(__DIR__);

//引用 字符串
require_once $fileRootPath.'/src/StringHandle.php';