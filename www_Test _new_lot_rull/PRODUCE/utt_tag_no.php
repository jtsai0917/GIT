<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<?php
session_start();
$_SESSION['which']='1';
?>
<form id="form1" name="form1" method="post" action="">
  <p>查詢</p>
  <p>巡檢編號(Tag_no) : 
    <label for="utt_tag_no"></label>
    <input type="text" name="utt_tag_no" id="utt_tag_no" />
   巡檢名稱 : 
   <label for="project"></label>
   <input type="text" name="project" id="project" />
   <input type="submit" name="submit" id="submit" value="送出" /><br />


  </p>
   <input type="hidden" name="MM_insert" value="form1">

</form>
<table width="450" border="1">
  <tr>
    <td width="99" class="center">編號</td>
    <td width="185" class="center">巡檢名稱</td>   
  </tr>
<?PHP 
$editFormAction = $_SERVER['PHP_SELF'];
include_once("../connections/conn.php");
	$query="select * from UTT_TAGNO_DATA ";
	if($_POST['utt_tag_no']<>"" or $_POST['project']<>""){$_SESSION['which']='0';}else{$_SESSION['which']='1';}
	if($_POST['utt_tag_no']<>"" and $_POST['project']==""){
	$query=$query." WHERE ([tag_no] LIKE '%".$_POST['utt_tag_no']."%')";}
	if($_POST['project']<>"" and $_POST['utt_tag_no']==""){
	$query=$query." WHERE ([project] LIKE '%".$_POST['project']."%')";}
	if($_POST['utt_tag_no']<>"" and $_POST['project']<>""){
	$query=$query." WHERE ([utt_tag_no] LIKE '%".$_POST['pdd_prod_name']."%') and ([project] LIKE '%".$_POST['project']."%')";}
	$query=$query." order by TAG_NO";
		$result = mssql_query($query);
		while($row = mssql_fetch_array($result))
		{
			echo "<tr>";
			echo '<td><a href="setsession_prod.php?pro_no='.trim($row['TAG_NO']).'&pro_name='.trim($row['project']).'">'.trim($row['TAG_NO']).'</a></td>';
			echo "<td>".trim($row['project'])."</td>";
			echo "</tr>";
		}
mssql_close($dbhandle);
?>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
function get_prod_name($pid){
	include_once("connections/conn.php");
$query1="SELECT          PDD_PROD_NO, PDD_PROD_NAME  
FROM              dbo.PRODUCT_DATA
WHERE (PDD_PROD_NO='".$pid."')";	
	$result1= mssql_query($query1);
	$numRows1 = mssql_num_rows($result1);

	while($row1 = mssql_fetch_array($result1)){
		$pname=$row1['PDD_PROD_NAME'];
	}
	return $pname;
}