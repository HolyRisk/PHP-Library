<?php
/**
 * @description 引用
 * @author Holyrisk
 * @date 2020/12/28 14:45
 */

require_once 'getPhpFile.php';

$domain = 'https://wangshengxian.com/';

$gk_mobile_arr = array(
    'mobile' => '测试 mobile',
);
$gk_card_arr = array(
    'cardno' => '测试 cardno',
);
$gk_device_arr = array(
    'deviceid' => '测试 deviceid',
);
$gk_bank_arr = array(
    'bankno' => '测试 bankno',
);
$gk_address_arr = array(
    'address' => '测试 address',
);



$urls = [
    $domain.'gk_mobile?'.http_build_query($gk_mobile_arr),
    $domain.'gk_card?'.http_build_query($gk_card_arr),
    $domain.'gk_device?'.http_build_query($gk_device_arr),
    $domain.'gk_bank?'.http_build_query($gk_bank_arr),
    $domain.'gk_address?'.http_build_query($gk_address_arr)
];
$result = [];
$obj =new RequestCurl();
$data = $obj->getCurlMore($urls);
print_r($data);
die();

if (!empty($data)){
    //解压json 数据
    foreach ($data as $key => $val){
        $arr = json_decode($val,true);
        //请求成功
        $result[$key] =$arr;
    }
}
echo json_encode($result);