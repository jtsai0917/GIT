<?php
/**
 * 詢問server要列印的資料
 * @version 20140313
 */
function select () {
    //接收資料
    $_GET['com'] = __COM_PORT__;
    $_GET['posType'] = __POS_TYPE__;
	$Data['G'] = $_GET;
	$Data['P'] = $_POST;
    $DataJson = json_encode($Data);
    //server端的API
    $url = __SERVER_URL__;
    //CURL,POST
    try{
        $str = "[".date("H:i:s")."]".mb_convert_encoding("開始抓資料".$DataJson,"big5","utf-8");
        rec_log($str);
        //CURL
    	$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($ch, CURLOPT_POST, 1);
        //DRMED 需要輸入帳密
    	//curl_setopt($ch, CURLOPT_USERPWD, 'admin551:webpos168'); 
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $DataJson);
    	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    	$ch_result = curl_exec($ch);
    	if($ch_result){
            $str = "[".date("H:i:s")."]".mb_convert_encoding("連接成功:$url","big5","utf-8");
            rec_log($str);
            rec_log1("[".date("H:i:s")."]".$ch_result);
    	
        } else {
            $str = "[".date("H:i:s")."]".mb_convert_encoding("連接失敗:$url","big5","utf-8");
            rec_log($str);
    	    rec_log1("[".date("H:i:s")."]".$ch_result);
            echo $str;
            throw new Exception(curl_error($ch), curl_errno($ch));    
        }
        curl_close($ch);
    } catch(Exception $e) {
        $str = "[".date("H:i:s")."]".sprintf( 'Curl failed with error #%d: %s',$e->getCode(), $e->getMessage());
        rec_log($str);
    	trigger_error($str,E_USER_ERROR);
    }
    //轉換
	$Pdata  = base64_decode($ch_result);
    return $Pdata;
}

/**
	 * 列印發票
	 * @param array $Pdata
	 * @return unknown
	 */
function Print_Data($Pdata){
	$fpData = $Pdata;
	$is_SUC = 0;
    $times = 0;
    $comport = "COM".__COM_PORT__;
    $IP = __PRINT_IP__;
    $PORT = __PRINT_PORT__;
	//COM PORT BAUD 初始化
    do{
        if(__PRINT_TYPE__){
            $fp = fsockopen($IP, $PORT, $errno, $errstr, 30);
        } else {
            exec("mode ".$comport.": baud=".__POS_BAUD__." data=8 stop=1 parity=n");
    	    $fp = fopen ($comport.":", "w+");
    	}
        if (!$fp) {
            if(__PRINT_TYPE__){
                $str = "[".date("H:i:s")."]".$errstr ($errno).":fsockopen error!!";
            } else{
                $str = "[".date("H:i:s")."]".$comport.":fopen error!!";
            }
            rec_log($str);
    		//echo $str."\n";
            $times++;
    	    sleep(1);
        } else {
            $is_SUC = 1;
            break;
        }   
    }while($times <= __MAX_PRINT_TIMES__);
     
	if($is_SUC){
        $is_second = 0;
        $str = "[".date("H:i:s")."]SUC print";
        rec_log($str);
        rec_log1("[".date("H:i:s")."]".$fpData);
		fwrite($fp, $fpData);
        fclose($fp);
	}
}

//測試連接伺服器
function connection_test () {
	echo __SERVER_CONECT_URL__."\n";
	 while (true) {
         $str = "[".date("H:i:s")."]connect:".__SERVER_CONECT_URL__;
         rec_log($str);
	 	 unset($result);
		 $setUrl = __SERVER_CONECT_URL__ ;
	     $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, $setUrl);
         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	     //curl_setopt($ch, CURLOPT_USERPWD, 'admin551:webpos168'); 
	     curl_setopt($ch, CURLOPT_TIMEOUT,2);
	     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	     $result = curl_exec($ch);
	     curl_close($ch);
	     
	     if ($result!=1) {
            $str = "[".date("H:i:s")."]".mb_convert_encoding($result."伺服器連線失敗!!\n","big5","utf-8");
            rec_log($str);
	     	echo $str;
	     	sleep(1);
	     } else {
            $str = "[".date("H:i:s")."]".mb_convert_encoding("伺服器連線完成!!\n","big5","utf-8");
            rec_log($str);
	     	break ;
	     }
	 }
	  return $result ;
}

/**
* LOG 記錄
* @param string $str
* 
*/
function rec_log($str=''){
    $fp = fopen(__LOG_DIR__."/".__LOG_FILE__,"a+");
    fputs($fp,$str."\r\n");
    fclose($fp);
}

/**
* LOG 記錄
* @param string $str
* 
*/
function rec_log1($str=''){
    $fp = fopen(__LOG_DIR__."/".__LOG_PRINT_FILE__,"a+");
    fputs($fp,$str."\r\n");
    fclose($fp);
}

/**
 * objectToArray
 *
 * @param string $d
 * @return array
 */
function objectToArray($d) {
	if (is_object($d)) {
		// Gets the properties of the given object
		// with get_object_vars function
		$d = get_object_vars($d);
	}
	if (is_array($d)) {
		/*
		* Return array converted to object
		* Using __FUNCTION__ (Magic constant)
		* for recursive call
		*/
		return array_map(__FUNCTION__, $d);
	}
	else {
		// Return array
        return $d;
	}
}
?>