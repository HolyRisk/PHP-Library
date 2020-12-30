<?php
/**
 * @description 引用
 * @author Holyrisk
 * @date 2020/12/28 14:45
 */
require_once 'getPhpFile.php';

$obj = new DateHandle();

//$start = '2020-12-30';
//$end = '2020-11-01';
$end = date("Y-m-d",time());
//$result = $obj->tempDay($start,$end);

//$result = $obj->lists($end,$start,true);

$result = $obj->trunDateFormat($end,'Y-m-d 00:00:00');

var_dump($result);
$result = $obj->trunDateFormat($end,'Y-m-d 23:59:59');

var_dump($result);
//echo json_encode($result);