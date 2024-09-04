<?php
include("config.php");
//初始化
$comport = "COM".__COM_PORT__;
$baud = __POS_BAUD__;
$IP = __PRINT_IP__;
$PORT = __PRINT_PORT__;
//列印內容
$str0 = mb_convert_encoding("出單機測試列印成功!!\n","big5","utf-8");
$str1 = mb_convert_encoding("列印時間：".date("Y-m-d H:i:s"),"big5","utf-8");
$strData  = chr(27).chr(64)
			.chr(27).chr(33).chr(16).chr(28).chr(33).chr(8).$str0
            .$str1
            .chr(10).chr(10).chr(10).chr(29).'V1';

if(__PRINT_TYPE__){
    $fp = fsockopen($IP, $PORT, $errno, $errstr, 30);
} else {
    $excute = "mode $comport: baud=".$baud." data=8 stop=1 parity=n";
    exec($excute);
    $fp = fopen ($comport.":", "w+");
}

//列印
if (!$fp) {
	echo (__PRINT_TYPE__)?("$comport <br />\n"):("$IP : $PORT <br />\n");
	echo "$errstr ($errno)<br />\n";
} else {
	fwrite($fp, $strData);
	fclose($fp);
}

?>