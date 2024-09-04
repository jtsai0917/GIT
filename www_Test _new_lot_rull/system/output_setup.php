<form action="" name="form1" method="post" >
新增料號<input type="text" name="prodno"></br>
新增檔案位置及名稱<input type="text" name="file_name" size="50">
<input type="submit" name="insert_file" value="新增">
</form>

	
<?php

session_start();
if(isset($_POST['insert_file'])){
	include_once'../connections/conn.php';
	$query="INSERT INTO FILE_LOCATION (PROD_NO, file_path, creator, active) VALUES (N'".trim($_POST['prodno'])."', N'".trim($_POST['file_name'])."', N'".$_SESSION['uid']."', 1)"; 
	$result=mssql_query($query);
	if ($result) {
	 	echo "新增料號".trim($_POST['prodno']);
	}
}

?>

