<?php
$host = '195.7.2.100';    // IP
$port = '1433';             // PORT
$dbname = 'chemical';         // 資料庫名稱
$user = 'sa';               // 帳號
$passwd = '9037';         // 密碼

try {
    // Driver 指定驅動
    $dbConn = new PDO('odbc:Driver=FreeTDS;Server=' . $host . ';Port=' . $port . ';Database=' . $dbname . ';UID=' . $user . ';PWD=' . $passwd . ';clientcharset=UTF-8');
} catch (PDOException $e){
    echo $e->getMessage();
}

$stmt = $dbConn->query('SELECT * FROM FT_Employee');

$row = $stmt->fetch();
echo '<pre>';
var_dump($row);
echo '</pre>';