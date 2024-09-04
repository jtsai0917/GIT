<?php
if (!isset($argv[1])) {
    echo "POS 週邊測試程式 Cli for fopen & fsockopen \n";
    echo "請輸入參數1:\n";
    echo "1 -> POSIFLEX客顯\n";
    echo "2 -> WD304客顯\n";
    echo "3 -> POSIFLEX錢櫃\n";
    echo "4 -> PL420錢櫃\n";
    echo "5 -> 其他\n";
    echo "6 -> 出單機測試\n";
    echo "參數2:Com/IP:\n";
    echo "參數3:Baud/Service Port:\n";
    echo "\n";
    echo "範例:\n";
    echo "c:\php cdtest_fropen.php 6 COM6 115200\n";
    echo "c:\php cdtest_fropen.php 6 10.10.40.7 9100\n";
    exit;
}

$Data1 = '第一行';
$Data2 = '第二行';
$Data3 = '第三行';
$Data4 = '第四行';

switch ($argv[1]) {
    case('1'):
    echo "\nTest Type POSIFLEX\n";
    $strData = chr(12) . $Data1 . chr(10) .
    chr(13) . $Data2 . chr(10) .
    chr(13) . $Data3 . chr(10) .
    chr(13) . $Data4 . chr(10) ;
    break;
    case('2'):
    echo "\nTest Type WD304\n";
    $strData = chr(31) .
    chr(27) . chr(113) . chr(65) . $Data1 . chr(10) .
    chr(27) . chr(113) . chr(66) . $Data2 . chr(10) .
    chr(27) . chr(113) . chr(67) . $Data3 . chr(10) .
    chr(27) . chr(113) . chr(68) . $Data4 . chr(10) ;
    break;
    case('3'):
    echo "\nTest Type CR3100\n";
    $strData = chr(7);
    break;
    case('4'):
    echo "\nTest Type PL420\n";
    $strData = chr(27) . 'p0255255' . chr(7) . chr(27) . 'p0' . chr(7);
    break;
    case('5'):
    echo "\nTest Type OTHER\n";
    $strData = $Data1;
    break;
    case('6'):
    echo "\nTest Type ESC/POS Printer\n";
    $strData = chr(27).chr(82);
    $strData .= chr(27).chr(80).chr(66)." 雍然有限公司".chr(13).chr(10);
    $strData .= chr(27).chr(80).chr(66)." 中市德妻理國光路216號1樓".chr(13).chr(10);
    $strData .= chr(27).chr(80).chr(66)." NO:89387625".chr(13).chr(10);
    $strData .= chr(27).chr(80).chr(66)." TEL:04-22871671".chr(13).chr(10);
    $strData .= chr(27).chr(80).chr(66).chr(13).chr(10);
    $strData .= chr(27).chr(80).chr(66).chr(13).chr(10);
    $strData .= chr(27).chr(80).chr(66)."2013-12-25 15:53:35".chr(13).chr(10);
    $strData .= chr(27).chr(80).chr(66)."統編: 55860876".chr(13).chr(10);
    $strData .= chr(27).chr(80).chr(66)."------------------------".chr(13).chr(10);
    $strData .= chr(27).chr(80).chr(66)."護備名片   5   310".chr(13).chr(10);
    $strData .= chr(27).chr(80).chr(66)."漫畫   1   90".chr(13).chr(10);
    $strData .= chr(27).chr(80).chr(66)."書套   1   40".chr(13).chr(10);
    $strData .= chr(27).chr(80).chr(66)."------------------------".chr(13).chr(10);
    $strData .= chr(27).chr(80).chr(66)." 小計: 440".chr(13).chr(10);
    $strData .= chr(27).chr(80).chr(66)." 合計:          [    440]".chr(13).chr(10);
    $strData .= chr(27).chr(80).chr(66)."     現金:         [    440]".chr(13).chr(10);
    $strData .= chr(27).chr(80).chr(66).chr(13).chr(10);
    $strData .= chr(27).chr(80).chr(66)." PPC2000  收銀員: 999999".chr(13).chr(10);
    $strData .= chr(27).chr(80).chr(66)." 傳票號碼: 210121225008939".chr(13).chr(10);
    $strData .= chr(27).chr(80).chr(66)." 發票號碼:    76917".chr(13).chr(10);
    $strData .= chr(27).chr(80).chr(66)." 收銀機:  997   CLASS:1".chr(13).chr(10);
    $strData .= chr(27).chr(80).chr(66).chr(13).chr(10);
    $strData .= chr(27).chr(80).chr(66)."========================".chr(13).chr(10);
    break;

}

if (substr($argv[2],0,3) == 'COM') {
    exec("mode ".$argv[2].": baud=".$argv[3]." data=8 stop=1 parity=n");
    $fp = fopen ($argv[2].":", "w+");
    if (!$fp) {
        echo "$errstr ($errno)<br />\n";
    }else {
        fwrite($fp, $strData);
        fclose($fp);
    }
}else {
    $fp = fsockopen($argv[2], $argv[3], $errno, $errstr, 30);
    if (!$fp) {
        echo "$errstr ($errno)<br />\n";
    }else {
        fwrite($fp, $strData);
        fclose($fp);
    }
}

