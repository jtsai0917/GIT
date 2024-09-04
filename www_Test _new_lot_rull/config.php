<?php
//建立LOG資料夾
$dirname = str_replace('\\','/',dirname(__FILE__)) ;
$dir = $dirname."/log/".date("Ymd");
$filename_ = date("Ymd").".log";
$filename_print = date("Ymd")."_print.log";

if (!is_dir($dir)) {
	mkdir($dir,0777);
	chmod($dir,0777);
}
//伺服器位置
//測試區
define('__SERVER_URL__','http://10.15.10.1:8080/test/xml/PrintApi.php');
//正式區
//define('__SERVER_URL__','http://10.15.10.1:8080/xml/PrintApi.php');

//伺服器測試連接位置
//測試區
define('__SERVER_CONECT_URL__','10.15.10.1:8080/test/xml/conect.php');
//正式區
//define('__SERVER_CONECT_URL__','10.15.10.1:8080/xml/conect.php');

//LOG資料夾路徑
define('__LOG_DIR__',$dir);

//建立本日LOG檔案
define('__LOG_FILE__',$filename_);
define('__LOG_PRINT_FILE__',$filename_print);

//列印方式 本地端:0，網路:1
define('__PRINT_TYPE__',"0");
//COM PORT
define('__COM_PORT__',"2");
//POS TYPE 熱感機型號(限定5200,8000)
define('__POS_TYPE__',"5200");
//熱感機頻率
define('__POS_BAUD__',"115200");

//網路型 IP
define('__PRINT_IP__',"192.168.10.110");

//網路型 PORT
define('__PRINT_PORT__',"9100");

//嘗試列印最大次數(每秒一次)
define('__MAX_PRINT_TIMES__',"60");
?>