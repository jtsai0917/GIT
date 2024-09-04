<?php 
session_start();
include("../lib/fun.php");
include("../checkuser.php");
include("../lib/jtsai.php");
include("../connections/conn.php");
lasturl();
datepick();
if($_SESSION['datepicker1']==''){$_SESSION['datepicker1']=date("m/d/Y")   ;}
if($_SESSION['datepicker2']==''){$_SESSION['datepicker2']=date("m/d/Y")   ;}
?>

<form name="form1" method="post" action="">
  <table width="1240" border="1">
    <tr>
      <td width="480">要求日期 
        <input name="datepicker1" type="text" id="datepicker1" size="14" value="<?php 	echo $_SESSION['datepicker1'];?>"  onchange="set_date_session(this.name,this.value)">
        ~
        <input name="datepicker2" type="text" id="datepicker2" size="14" value="<?php 	echo $_SESSION['datepicker2'];?>"  onchange="set_date_session(this.name,this.value)">
        <span class="d1">
<input type="submit" name="search" id="search" value="搜尋">
        </span></td>

    </tr>
  </table>
</form>
<?php

if($_POST['search']){
		$_SESSION['datepicker2']=$_POST['datepicker2'];
		$_SESSION['datepicker1']=$_POST['datepicker1'];
if (!$_SESSION['datepicker1']){$_SESSION['datepicker1']=date("m/d/Y",$d);}
if (!$_SESSION['datepicker2']){$_SESSION['datepicker2']=date("m/d/Y",$d);}

$query="SELECT DISTINCT 
                            AnalyzeDesign.AND_CANCEL, AnalyzeDesign.AND_REPORT_DATETIME, 
                            EMPLOYEE_DATA.EMP_NAME, AnalyzeDesign.AND_MEMO, AnalyzeDesign.AND_LOT_NO, 
                            AnalyzeDesign.AND_APPLY_DATE, PRODUCT_DATA.PDD_TYPE, 
                            PRODUCT_DATA.PDD_PROD_NO, PRODUCT_DATA.PDD_CHEMICAL, 
                            PRODUCT_DATA.PDD_PROD_NAME, AnalyzeDesign.AND_ITEM, 
                            AnalyzeDesign.AND_NEED_NO, PRODUCT_TYPE.department, AnalyzeDesign.rut, 
                            AnalyzeDesign.AND_SMP_DATETIME
FROM           PRODUCT_TYPE RIGHT OUTER JOIN
                            EMPLOYEE_DATA INNER JOIN
                            AnalyzeDesign ON EMPLOYEE_DATA.EMP_NO = AnalyzeDesign.AND_PERSON INNER JOIN
                            PRODUCT_DATA ON AnalyzeDesign.AND_GOODS = PRODUCT_DATA.PDD_PROD_NO ON 
                            PRODUCT_TYPE.PDD_PROD_NO = PRODUCT_DATA.PDD_PROD_NO 
WHERE          (AnalyzeDesign.AND_NEED_NO>=270) AND (PRODUCT_DATA.PDD_CHEMICAL like '%H2SO4%')";



if ($_POST['datepicker1']){
	$_SESSION['datepicker1']=$_POST['datepicker1'];
    $query=$query." AND (AnalyzeDesign.AND_SMP_DATETIME >='".dod($_POST['datepicker1'])."000000')";
}
elseif($_SESSION['datepicker1'])
{
	$query=$query." AND (AnalyzeDesign.AND_SMP_DATETIME >='".dod($_SESSION['datepicker1'])."000000')";
}
if ($_POST['datepicker2']){
	$_SESSION['datepicker2']=$_POST['datepicker2'];
	$query=$query." AND ( AnalyzeDesign.AND_SMP_DATETIME <='".dod($_POST['datepicker2'])."235959')";
}
elseif($_SESSION['datepicker2']){
	$query=$query." AND ( AnalyzeDesign.AND_SMP_DATETIME <='".dod($_SESSION['datepicker2'])."235959')";
}

$query.=" ORDER BY AND_APPLY_DATE DESC";
$result = mssql_query($query);

echo '受入 
<table width="1240" border="1">
  <tr bgcolor="#CCCCCC">
    <td height="49" width="100">日期序號</td>
    <td width="160">品名</td>
    <td width="80">LotNO</td>
    <td width="200">備註1</td>
    <td width="100">要求完成時間</td>
	<td width="600">充填路徑</td>
  </tr>
';

while($row = mssql_fetch_array($result))
{ 
$str1=$row['PDD_PROD_NO'];
$cus=new get_from_lot_no;
$cus->lid=$row['AND_LOT_NO'];
$cus->cid();
	$note=$row['AND_NOTE'];
	$rpd=$row['AND_REPORT_DATETIME'];
	echo '<tr>';
    echo '<td>'.$row['AND_APPLY_DATE']."-".$row['AND_NEED_NO'].'</td>';
    echo '<td>'.$row['PDD_PROD_NO'].":".$row['PDD_PROD_NAME'].'</td>';
    echo '<td>'.$row['AND_LOT_NO'].'</td>';
    echo '<td>'.$note.'</td>';
    echo '<td>'.$rpd.'</td>';
	$A=fill_flow($row['AND_LOT_NO']);
	echo '<td>'.$A[0].'</td>';
	echo '</tr>';
}
}
?>

</table>
<br>The End
<?php
function fill_cloum($A,$B){
	
}
function fill_flow($lotno){
	$query="SELECT	Fill_Flow_Chart.flow FROM Fill_Flow_Chart WHERE (Lot_No = N'".$lotno."') order by date desc";
	$url="edit_flow.php?lot_no=".$lotno;
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	if(trim($row[0])==''){$a='編輯';}else{$a=$row[0];}
	$A='<a target="_blank" href="'.$url.'">'.$a.'</a>;'.$a;
	$B=explode(";",$A);
	return $B;	
}
?>
