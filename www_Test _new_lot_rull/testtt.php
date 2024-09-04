<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>無標題文件</title>
</head>

<body>
<form action="yee.php" method="post">
 
id:<input type="text" name="aa" ><br />
mail:<input type="text" name="email" /><br>

<input type="submit" >	

<input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onclick="window.open('../Koala.jpg','_self');" />



<?PHP 
include("../connections/conn.php");
session_start();
function fun(){
	echo 'applepen'; 
	} 
function a(){
	fun();
	}	
$query="select ANI_NICKNAME from dbo.AnalyzItem where (ANI_INDEX<>' ')";
echo $query;


	?>
</form>
</body>
</html>