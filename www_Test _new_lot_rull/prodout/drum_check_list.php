<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
remurl("urln1");
datepick();
?>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  檢查日期:
    <input name="datepicker1" type="text" id="datepicker1" size="14"  onchange="set_date_session(this.name,this.value)" value="<?php echo $_SESSION['datepicker1'];	?>" />
~
<input name="datepicker2" type="text" id="datepicker2" size="14"  onchange="set_date_session(this.name,this.value)" value="<?php echo $_SESSION['datepicker2'];	?>" />

<span class="d1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;決定書 NO:
<input name="otd_no" type="text" id="otd_no" size="10"  onchange="set_date_session(this.name,this.value)" value="<?php echo $_SESSION['otd_no'];?>"/>
</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" name="search"  value=" 查  詢 " />
<input type="button" name="del"   value=" 刪除 " onClick="window.open('./index.php?url=del_drum ', '_self');" >


</form>
</body>
</html>
<?php 
$loginFormAction = $_SERVER['PHP_SELF'];
if(isset($_POST['search']))
{
	echo '<table width="1200" border="1">
	  <tr>
		<td width="150">決定書</td>
		<td width="150">客戶</td>
		<td width="150">批號檢查</td>
		<td width="150">附帶條件</td>
		<td>外觀檢查</td>
		<td width="150">棧板名稱</td>
		<td width="150">櫃內荷姿</td>
		<td>出貨擔當</td>
	  </tr>';
	  
	$query="SELECT          OTD.OTD_NO AS a1, CTD.CTD_CUST_SHORT_NAME AS a2, OCM.OCM_CHK_ADDITION AS a4, 
                            OCM.OCM_CHK_SURFACE AS a5, OCM.OCM_PLT_NAME AS a6, OCM.OCM_PLT_STYLE AS a7, 
                            OCM.OCM_CF_MAN AS a8
FROM              OUT_CHECK_DRUM AS OCM INNER JOIN
                            OUT_DECISION AS OTD ON OTD.OTD_NO = OCM.OTD_NO LEFT OUTER JOIN
                            CUSTOMER_DATA AS CTD ON OTD.CTD_CUST_NO = CTD.CTD_CUST_NO
			WHERE  OCM.OCM_CHK_DATE >= '".dod($_POST['datepicker1'])."' AND  OCM.OCM_CHK_DATE <= '".dod($_POST['datepicker2'])."'";
	if ($_POST['otd_no']<>'')
	{
		$query="SELECT          OTD.OTD_NO AS a1, CTD.CTD_CUST_SHORT_NAME AS a2, OCM.OCM_CHK_ADDITION AS a4, 
                            OCM.OCM_CHK_SURFACE AS a5, OCM.OCM_PLT_NAME AS a6, OCM.OCM_PLT_STYLE AS a7, 
                            OCM.OCM_CF_MAN AS a8
FROM              OUT_CHECK_DRUM AS OCM INNER JOIN
                            OUT_DECISION AS OTD ON OTD.OTD_NO = OCM.OTD_NO LEFT OUTER JOIN
                            CUSTOMER_DATA AS CTD ON OTD.CTD_CUST_NO = CTD.CTD_CUST_NO
				WHERE OTD.OTD_NO = '".$_POST['otd_no']."' ORDER BY  OTD.OTD_NO DESC";
	}
//	echo $query."<BR>";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	while($row = mssql_fetch_array($result))
	{
		echo '<tr>';
		echo '<td>'.$row['a1'].'</td><td>'.$row['a2'].'</td><td>'._otd_($row['a1']).'</td><td>'.$row['a4'].'</td>
		<td>'.$row['a5'].'</td><td>'.$row['a6'].'</td><td>'.$row['a7'].'</td><td>'.$row['a8'].'</td>'.'<td>'.'<input type="button" name="rull" id="rull" value="列印" onClick="'."window.open('./index.php?url=drum_check_print&id=".$row['a1']." ', '_self');".'"/>';

		echo '</tr>';
	}	
	echo '</table></br>';
	$_SESSION['urln1']='';
}
function _otd_($otd_no)
{
	$query="Select DISTINCT OCD_LOT_NO from OUT_CHECK_DRUM_DETAIL where OTD_NO = '".$otd_no."'";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	while($row = mssql_fetch_array($result))
	{
		$otd_lot=$otd_lot.$row['OCD_LOT_NO'].";";
	}
	return substr($otd_lot,0,-1);
}
?>
