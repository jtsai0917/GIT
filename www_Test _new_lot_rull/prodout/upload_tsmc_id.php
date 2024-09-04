<?php
session_start();
include("../lib/fun.php");
include("../connections/conn.php");
datepick();
lasturl();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>Upload</title>
</head>

<body>
<form action="<?php echo $loginFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
<?php echo "Lot NO: ".$_GET['lotno'];?> <BR />TSMC 圖檔上傳：
<input type="file" name="file" id="file" />
檔案說明：
<label for="disc"></label>
<input name="disc" type="text" id="disc" size="30" />
<input type="submit" name="upload" id="upload" value="送出" />
只可以上傳GIF,JPG,PNG,TIF四種副檔名，建議用PNG
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</form>
</body>
</html>
<?php
if(isset($_POST['upload'])){
	$ext = end(explode('.', $_FILES["file"]["name"]));
	$path="../reports/TSMC_ID/";
	$filename=$_GET['lotno']."_".date("YmdHis").".".$ext;
	if (file_exists($path)){
	
		} 
	else {
		mkdir($path);
		}
	move_uploaded_file($_FILES["file"]["tmp_name"],$path.$filename);
	$_SESSION['uptime']=date("YmdHis");	
//	echo "FileName:".$filename."<BR>";
//	echo "Fullpath:".$fullpath."<BR>";
	echo '<form action="" method="post" name="form2" >
		Lot_No : <input type="text" name="lotno" value="'.trim($_GET['lotno']).'" readonly="readonly" ><BR>
		File_Name : <input type="text" name="filename" value="'.trim($filename).'" readonly="readonly" ><BR>
		備註 : <input type="text" name="disc1" value="'.trim($_POST['disc']).'" readonly="readonly" ><BR>
		<input type="submit" name="save" value=" SAVE ">
		
	</form>';
}

if(isset($_POST['save'])){
	$fullpath="/reports/TSMC_ID/".$_POST['filename'];
	//寫入資料表
	$query="INSERT INTO TSMC_ID
                            (createdatetime, creator, FILE_PATH, FILE_LOT_NO, FILE_DISC, FILE_NAME)
VALUES          (N'".date("Ymdhis")."', N'".$_SESSION['empno']."', '".$fullpath."', '".$_GET['lotno']."', N'".$_POST['disc1']."', '".$_POST['filename']."')"; 	
	$result=mssql_query($query);
	if($result==1){
		close_frame();	
	}
	else{
		"存檔失敗";
	}
}
?>