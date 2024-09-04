<meta http-equiv="Content-Type" content="text/html; charset=big5" />

<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
	echo '<table width="1240" border="1">';
	echo '<tr>
			<td width="20" align="center">序號</td>
			<td width="70" align="center">登錄日期</td>
			<td width="70" align="center">輸入人員</td>
			<td width="70" align="center">品名</td>
			<td width="70" align="center">類型</td>
			<td width="70" align="center">客戶名稱</td>
			<td width="70" align="center">LY-NO</td>
			<td width="70" align="center">桶數</td>
			<td width="70" align="center">數量(L)</td>
			<td width="70" align="center">預計充填</td>
			<td width="70" align="center">LOT-NO</td>
			<td width="70" align="center">希望報告時間
			</br>(檢驗項目)</td>
			<td width="70" align="center">先行COA</td>
			<td width="70" align="center">出荷時間</td>
			<td width="70" align="center">回廠時間</td>
			<td width="70" align="center">先行樣品</td>
			<td width="70" align="center">取樣瓶數</td>
			<td width="70" align="center">轉充填</td>
			</tr>';

$query="SELECT         dbo.FILLPLAN_OUT_DECIDE.*, dbo.PRODUCT_DATA.PDD_TYPE, dbo.PRODUCT_DATA.PDD_LITER_KG,  dbo.PRODUCT_DATA.PDD_UNIT 
FROM             dbo.FILLPLAN_OUT_DECIDE INNER JOIN
                          dbo.PRODUCT_DATA ON 
                          dbo.FILLPLAN_OUT_DECIDE.PDD_PROD_NO = dbo.PRODUCT_DATA.PDD_PROD_NO where FOD_UNI=".$_GET['uni'];

$result = mssql_query($query);
while ($row = mssql_fetch_array($result)){
	echo '<tr>';
	echo '<td>'.$row['FDM_SERIAL_NO']."</td>";
	echo '<td>'.std($row['FDM_CREATE_DATE'])."</td>";
	echo '<td>'.getusername($row['FDM_CREATOR'])."</td>";
	echo '<td>'.get_pdd_name($row['PDD_PROD_NO'])."</td>";
	echo '<td>'.$row['PDD_TYPE']."</td>";
	echo '<td>'.get_cust_name($row['CTD_CUST_NO'])."</td>";
	echo '<td>'.$row['FDM_LY_NO']."</td>";
	echo '<td>'.$row['FDM_QTY_DRUM']."</td>";
	echo '<td>'.$sum."</td>";
	echo '<td>'.std($row['FDM_EXPECT_DATE'])."</br>".stm($row['FDM_EXPECT_DATE'])."</td>";
	echo '<td>'.$row['FDM_LOT_NO']."</td>";
	echo '<td>'.std($row['FDM_RESULT_DATE'])."</br>".stm($row['FDM_RESULT_DATE']).'</br>'.$ss."</td>";
	echo '<td>'.$row['FDM_COA_BEFORE']."</td>";
	echo '<td>'.std($row['FDM_OUT_DATE'])."</br>".stm($row['FDM_OUT_DATE'])."</td>";
	echo '<td>'.std($row['FDM_BACK_DATE'])."</br>".stm($row['FDM_BACK_DATE'])."</td>";
	echo '<td>'.$row['FDM_SAM_BEFORE']."</td>";
	echo '<td>'.$row['FDM_SAM_CNT']."</td>";
	$lot_no=$row['FDM_LOT_NO'];
	if($row['FDM_TRANSFROM']==''){$rs="否";}
	echo '<td>'.$rs."</td>";
	echo '</tr>';
}
echo "</table></br></br>";
echo '<form name="form1" method="post" action="'.$loginFormAction.'">
<input type="submit" name="delete" id="delete" value="確定刪除">
<input type="submit" name="cancel" id="cancel" value="取消">
</form>';
if(isset($_POST["delete"]))
{
	$query="DELETE FROM FILLPLAN_OUT_DECIDE where (FOD_UNI = ".$_GET['uni'].")";
	$results=mssql_query($query);
	$query="delete from AnalyzeDesign where AND_LOT_NO='".$lot_no."'";
	$results=mssql_query($query);
	$query="delete from PRODUCT_RUNNING_ACCOUNT where PRA_LOT_NO='".$lot_no."'";
	$results=mssql_query($query);

	jumpto($_SESSION['lasturl']);
}
if(isset($_POST["cancel"]))
{jumpto($_SESSION['lasturl']);}
