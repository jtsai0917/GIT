<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php 
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
$query="SELECT  ori_pid FROM PDD_MADE_FROM where target_pid='".trim($_GET['pid'])."'" ;
$result=mssql_query($query);
$row=mssql_fetch_row($result);
echo '<font  style="font-size:20px;">';
echo "來原料號:".$ori_pid=$row[0];
echo '<BR>';
echo "來原品名:".get_prod_name($ori_pid=$row[0]);
echo '</font>';
echo '
<form name="form1" method="post" action="">
<font size="+2">	輸入充填來源: </font><BR>
<font  style="font-size:20px;">手動輸入</font><input type="text" style="font-size:20px;" name="FID_SOURCE" id="FID_SOURCE" value="'.$_GET['fid_source'].'"><BR><BR><font  style="font-size:20px;">選擇現有原料批號</font><br><select name="select">';

$query="SELECT  IAP_MAKER_LOT_NO FROM IN_PLAN_PRODUCT WHERE (PDD_PROD_NO = '".$ori_pid."') AND (IAP_STATE <> '0') ORDER BY IAP_INWARD_DATE DESC";
// echo $query;
$result=mssql_query($query);
while($row=mssql_fetch_array($result)){
	echo '<option value="'.$row[0].'">'.$row[0].'</option>';	
}
echo '</select>';
echo '<input type="submit"  style="font-size:20px;" name="submit" value=" 確定 " >
<input type="submit"  style="font-size:20px;" name="leave" value=" 取消\離開 " >
</form>';

if(isset($_POST['submit'])){
	$query="UPDATE  FILL_INDICATE SET FID_SOURCE_LOT = N'".strtoupper($_POST['FID_SOURCE'])."' WHERE   (FDM_LOT_NO = '".$_GET['lot_no']."')"; 
	$resylt=mssql_query($query);
//	echo $query;
//	echo "完成";
	echo '<script type="text/javascript">window.close()</script>';
}

if(isset($_POST['leave'])){
	echo '<script type="text/javascript">window.close()</script>';
}
