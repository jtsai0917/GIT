<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php 
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
auth('5-05',$_SESSION['aut']);
datepick();
echo '<form id="form1" name="form1" method="post" action="">';
echo '要變更的 Lot NO ： <input type="text" name="olotname" value="'.$_SESSION['olotname'].'" onchange="set_date_session(this.name,this.value)">';
echo '&nbsp;&nbsp;<input type="submit" style="font-size:12px" name="start" value=" 確定 "><hr/>';
if($_SESSION['olotname']<>''){
	$query="SELECT     CUSTOMER_DATA.CTD_CUST_NAME, CUSTOMER_DATA.CTD_CUST_NO
FROM           FILLPLAN_OUT_DECIDE INNER JOIN
                            CUSTOMER_DATA ON 
                            FILLPLAN_OUT_DECIDE.CTD_CUST_NO = CUSTOMER_DATA.CTD_CUST_NO
WHERE       (FILLPLAN_OUT_DECIDE.FDM_LOT_NO = '".$_SESSION['olotname']."') ";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$ocustname=$row[0];
	echo '原客戶：'.$ocustname .'('.$row[1].') =>  新客戶：<input type="text" name="newcust" value="'.$_SESSION['newcust'].'" onchange="set_date_session(this.name,this.value)"> 請輸入客戶編號  &nbsp;&nbsp;<input type="submit" name="sure" value="確定"><hr/>';
}
if(isset($_POST['sure'])){
	$query="SELECT CTD_CUST_NAME FROM CUSTOMER_DATA WHERE (CTD_CUST_NO = '".$_POST['newcust']."')";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	echo '確認要將 '.$_SESSION['olotname']." 客戶變更為 ".$row[0] ."(".trim($_POST['newcust']).')&nbsp;&nbsp;<input type="submit" name="enter" value="確認變更">';	
}
echo '</form>';
if(isset($_POST['enter'])){
	$query="UPDATE FILLPLAN_OUT_DECIDE SET CTD_CUST_NO = '".trim($_POST['newcust'])."' WHERE (FDM_LOT_NO = '".$_SESSION['olotname']."')";	
	$result=mssql_query($query);
	echo "出荷決定書變更完成...<BR>";
	
	$query="UPDATE AnalyzeDesign SET CTD_CUST_NO = '".$_POST['newcust']."' WHERE (AND_LOT_NO = '".$_SESSION['olotname']."')";	
	$result=mssql_query($query);
	echo "分析依賴變更完成...<BR>";
}