<?php 
session_start();
include("../lib/fun.php");
include("../checkuser.php");
include("../lib/jtsai.php");
lasturl();
datepick();
include('../connections/conn.php'); 
$query="SELECT AnalyzeDesign.*, AnalyzeDesign.AND_CANCEL, AnalyzeDesign.AND_REPORT_DATETIME, EMPLOYEE_DATA.EMP_NAME, AnalyzeDesign.AND_MEMO, 
                            AnalyzeDesign.AND_LOT_NO, AnalyzeDesign.AND_APPLY_DATE, PRODUCT_DATA.PDD_TYPE, 
                            PRODUCT_DATA.PDD_PROD_NO, PRODUCT_DATA.PDD_CHEMICAL, PRODUCT_DATA.PDD_PROD_NAME, 
                            AnalyzeDesign.AND_ITEM, AnalyzeDesign.AND_NEED_NO, dbo.FILLPLAN_OUT_DECIDE.FOD_YEAR_MONTH, 
                            dbo.FILLPLAN_OUT_DECIDE.FOD_DAY, FILLPLAN_OUT_DECIDE.CTD_CUST_NO
FROM              dbo.AnalyzeDesign INNER JOIN
                            dbo.PRODUCT_DATA ON AnalyzeDesign.AND_GOODS = PRODUCT_DATA.PDD_PROD_NO INNER JOIN
                            dbo.EMPLOYEE_DATA ON AnalyzeDesign.AND_PERSON = EMPLOYEE_DATA.EMP_NO LEFT OUTER JOIN
                            dbo.FILLPLAN_OUT_DECIDE ON 
                            dbo.AnalyzeDesign.AND_LOT_NO = dbo.FILLPLAN_OUT_DECIDE.FDM_LOT_NO 
WHERE     (AnalyzeDesign.AND_LOT_NO = '".trim($_GET['lid'])."')  ORDER BY AND_SMP_DATETIME";	

$result = mssql_query($query);
$numRows = mssql_num_rows($result);

?>
<table width="1240" border="1">
  <tr bgcolor="#CCCCCC">
    <td height="49" width="80">日期序號</td>
    <td width="76">客戶</td>
    <td width="84">品名</td>
    <td width="29">容器</td>
    <td width="67">LotNO</td>
    <td width="158">備註1</td>
    <td width="95">要求完成時間</td>
    <td width="603"><p>分析項目:<font color="#0000FF">藍字</font>表示已有報告及結果，<font color="#FF0000">紅字</font>表示沒有報告及結果，<font color="green">綠字</font>表示有報告但是無檢驗結果，</p>
    <p><font color="purple">紫色</font>表示沒有報告但是有檢驗結果‧<span style="background-color: red"><font color="white">紅底白字</font></span>表示檢驗未通過‧</p></td>

  </tr>
<?php
while($row = mssql_fetch_array($result))
{   
$str1=$row['PDD_PROD_NO'];
$str2="P001";
$str3="UP007";
$str4="UP319";
$str5="P007";
$str6="UP001";
$cus=new get_from_lot_no;
$cus->lid=$row['AND_LOT_NO'];
$cus->cid();
$diff=new difference_too_large;
$diff->LotNo=$row['AND_LOT_NO'];
$diff->status();
	$note=$row['AND_NOTE'];
	$rpd=$row['AND_REPORT_DATETIME'];
	if($row['AND_CANCEL']==1){$rpd='已取消';echo '<tr bgcolor="#FF9900">';}
	elseif($diff->flag=='1'){$note=$diff->item.' 差異太大';echo '<tr bgcolor="#FF9900">';}
	else{echo '<tr>';}
	
    echo '<td>'.$row['AND_APPLY_DATE']."-".$row['AND_NEED_NO'].'</td>';
	echo '<td>'.$cus->csname.'</td>';
    echo '<td>'.$row['PDD_PROD_NAME'].'</td>';
    echo '<td>'.$row['PDD_TYPE'].'</td>';
    echo '<td>'.$row['AND_LOT_NO'].'</td>';
    echo '<td>'.$note.'</td>';
    echo '<td>'.$rpd.'</td>';
	if(substr($row['AND_ITEM'],-1)==','){$row['AND_ITEM']=substr($row['AND_ITEM'],0,-1);}
	analyzereport($row['AND_ITEM'],trim($row['AND_LOT_NO']),$row['EMP_NAME'],$row['PDD_CHEMICAL'],$row['PDD_PROD_NO']);
/*    echo '<td><a target="_self" href="'.$url.$row['AND_LOT_NO']."&testdate=".$row['AND_APPLY_DATE']."&operator=".iconv("big5","utf-8",$row['EMP_NAME']).'">ASSAY</a></td>';
    echo '<td><a target="_self" href="'.$ur2.$row['AND_LOT_NO']."&testdate=".$row['AND_APPLY_DATE']."&operator=".iconv("big5","utf-8",$row['EMP_NAME']).'">NH4</a></td>';
	    echo '<td><a target="_self" href="'.$ur3.$row['AND_LOT_NO']."&testdate=".$row['AND_APPLY_DATE']."&operator=".iconv("big5","utf-8",$row['EMP_NAME']).'">UV</a></td>';
*/
	echo '</tr>';
}
?>
</table>
<br>