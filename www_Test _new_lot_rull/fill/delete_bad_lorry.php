<?php
	session_start();
	include("../connections/conn.php");
	include("../lib/fun.php");
	include("../lib/jtsai.php");
//	auth('9-10',$_SESSION['aut']);
?>	
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>" >
 刪除過期的ＬＯＴ NO<BR>
 輸入要刪除的Lot NO <input type="text"name="lotno"> 
<input name="submitXX" type="submit" id="submitXX" value="  刪除  ">
</form>
<?php 
if(isset($_POST['submitXX'])){
	$query="DELETE FROM LORRY_FILL_CHECK  WHERE  FDM_LOT_NO='".trim($_POST['lotno'])."'";
//	echo $query."<BR>";
	$result=mssql_query($query);
	echo " Lorry_Fill_Check deleted..";
}
	
?>
