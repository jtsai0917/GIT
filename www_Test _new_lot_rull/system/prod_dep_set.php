<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php /// last edit 20161126
include("../connections/conn.php");
session_start();
include("../lib/fun.php");
include("../lib/jtsai.php");
lasturl();
datepick();
echo '藥品-部門 設定';
?>

<form id="form1" name="form1" method="post" action="<?php echo $editFormAction; ?>">
 <table width="800" border="1"><tr><td width="100" height="10">新增對應:</td>
 <td width="700">
&nbsp;&nbsp; 料號：&nbsp;&nbsp;
   <input name="prod_no" type="text" id="prod_no" size="5" value="<?php echo $_SESSION['prod_no']; ?>" width="40"/>
   <label for="dep"></label>
   <select name="dep" id="dep">
     <option value="EL">EL</option>
     <option value="IQ">品保</option>
     <option value="SA">Display</option>
   </select>
   <input name="add" type="submit" id="add" value="    新增     " />
   </td></tr></table>
</form>
<?php
	$editFormAction = $_SERVER['PHP_SELF'];
	echo '<table width="300" border="1"><tr><td>編號</td><td>部門</td><td>產品編號</td><td>產品名稱</td></tr>';
	$query="SELECT          PROD_DEP.*, PRODUCT_DATA.PDD_PROD_NAME as prod_name
FROM              PROD_DEP INNER JOIN
                            PRODUCT_DATA ON PROD_DEP.prod_id = PRODUCT_DATA.PDD_PROD_NO";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result))
	{
		echo '<tr><td>'.$row['index'].'</td><td>'.$row['dep'].'</td><td>'.$row['prod_id'].'</td><td>'.$row['prod_name'].'</td><td></td></tr>';	
	}
		echo '</table>';
		
	if(isset($_POST['add']))
	{
		$query="select count(*) as total from dbo.PROD_DEP where dep='".$_POST['dep']."' and prod_id='".$_POST['prod_no']."'";
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);
		$num=$row[0];
		
		if($num==0)
		{
			$query="INSERT INTO PROD_DEP
                            (dep, prod_id)
VALUES          ('".$_POST['dep']."','".$_POST['prod_no']."')";
			$result=mssql_query($query);
		}
		if($num>0)
		{
			my_msg("重複輸入");	
		}
	}	
?>