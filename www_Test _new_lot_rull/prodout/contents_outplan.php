<?php
include("../lib/sag_in.php");
lasturl();
remurl("sag");
$sag=new sign_in;
$sag->cbt_no='2-02';
$sag->order_no=$_GET['order_no'];
$sag->order_sag();
$sag->r_opf_file_();
if($sag->sa_no<>''){$sano="簽核號碼:".$sag->sa_no;}else{$sano='<input type="submit" name="insert_sag"  value="   申請簽核   " />';}
if($sag->opm_stock <>''){$save="倉庫:".$sag->opm_stock;}else{$save='<select name="stock" id="stock">
          <option value="TYS">TYS</option>
          <option value="TAINAN">TAINAN</option>
        </select><input type="submit" name="save"  value="   存   檔   " />';}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>產品內容</title>
</head>
<body>
<table width="1240" border="0">
  <tr>
    <td><font size="+1">產品內容：&nbsp;&nbsp;&nbsp;&nbsp;</font>
<form id="prod_content" name="prod_content" method="post" action="<?php echo $loginFormAction; ?>">
        
<?php 
echo $save."&nbsp;&nbsp;&nbsp;&nbsp;".$sano;
$sag->aut_no='';
$sag->read_agree();
if($sag->finished_time<>''){echo'<font color="#FF0000"> &nbsp;&nbsp;&nbsp;&nbsp;(此計劃書已轉為決定書，不可再變更‧)</font>';}
?>
<input type="hidden" name="MM_insert" value="prod_content">
    
    <td align="right"><a target="_self" href="<?php echo $_SERVER["HTTP_REFERER"];?>"><font size="+1">上一頁</font></a>
  
</table>
<table width="1240" border="1">
<tr><td>序號 <input type="submit" name="new" value="  +  " /></td><td>料號</td><td>品名</td><td>包裝型態</td><td>LOT NO (新增/更改Lot)</td><td>出貨期限</td><td>單位</td><td>桶數</td><td>數量(KG)</td><td>數量(L)</td><td>備註</td><td>轉檔日期</td></tr>
 
  <?php
  $i=0;
  $query="SELECT  OPM_ORDER_NO , OPD_SERIAL_NO, OTD_NO, OTN_NO, OTNP_SERIAL_NO, PDD_PROD_NO, OAF_PACKAGE, 
                   OPD_LOT_NO, PRA_SERIAL_NO, OPD_TERM_DATE, OPD_VALIDATE, OPD_QTY_DRUM, OPD_ACC_UNIT, 
                   OPD_QTY_LITER, OPD_QTY_KG, OPD_SIGN_RECEIPT, OPD_COA_RECEIPT, OPD_MEMO, OPD_REAL_QTY_KG, 
                   OPD_INWARD_DATE, OPD_COA_NO, COA_INDEX, OAF_ACC_ID, OPD_SMP_DATETIME, OPD_COA_DATETIME, 
                   COPCONFIRMSERIES
FROM      OUT_PRODUCT
WHERE      (OPM_ORDER_NO  = '".$_GET['order_no']."')";

$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result))
  	{
		
		$dep_no=
		$order_date=$row['OPD_INWARD_DATE'];
		$order_no=$row['OPM_ORDER_NO'];
		$sag->read_decision();
		$sag->eta_date=$row['OPM_ETA_DATE'];
		if($row['OPD_LOT_NO']== ''){$lot_no="選擇";}
		else{$lot_no=$row['OPD_LOT_NO'];}
		if($sag->numrows==0){		
		$showlot='<a target="_self" href="index.php?url=x_lot&new=N&id='.$row['OPD_SERIAL_NO'].'&order_no='.$row['OPM_ORDER_NO'].'&po_no='.$row['OPM_PO_NO'].'&pid='.$row['PDD_PROD_NO'].'">'.$lot_no.'</a>';
		}
		else{$showlot=$row['OPD_LOT_NO']; }	
		echo '<tr>';
		echo '<td>'.$row['OPD_SERIAL_NO'].'</td><td>'.$row['PDD_PROD_NO'].'</td><td>'.get_prod_name($row['PDD_PROD_NO']).'</td><td>'.$row['OAF_PACKAGE'].'</td><td>'.$showlot.'</td><td>'.$row['OPD_TERM_DATE'].'</td>'  ;
		echo '<td>'.$row['OPD_ACC_UNIT'].'</td><td>'.$row['OPD_QTY_DRUM'].'</td><td>'.($row['OPD_QTY_KG']).'</td><td>'.$row['OPD_QTY_LITER'].'</td><td>'.$row['OPD_MEMO'].'</td><td>'.$row['OPD_INWARD_DATE'].'</td>'  ;
		echo '</tr>';	
		$aa[$i][0]=trim($row['OPD_SERIAL_NO']);
		$aa[$i][1]=trim($row['PDD_PROD_NO']);
		$aa[$i][2]=trim($lot_no);
		$aa[$i][3]=trim($row['OPD_QTY_DRUM']);
		$aa[$i][4]=trim($row['OPD_QTY_KG']);
		$aa[$i][5]=trim($row['OPD_TERM_DATE']);
		$aa[$i][6]=trim($row['CTD_CUST_NO']);
		$aa[$i][7]=$_GET['order_no'];
		$aa[$i][8]=trim($row['FDM_LY_NO']);
		$_SESSION['prod_list']=$aa;
		$i++;
	}
echo '</table>';
//echo count($aa);
/*
for($n=0;$n<$i;$n++){
	echo "#".$_SESSION['aa'][$n][0]."#<BR>";
	echo "#".$_SESSION['aa'][$n][1]."#<BR>";
	echo "#".$_SESSION['aa'][$n][2]."#<BR>";
	echo "#".$_SESSION['aa'][$n][3]."#<BR>";
	echo "#".$_SESSION['aa'][$n][4]."#<BR>";
}
*/
?>

</form>
</br>
</body>
</html>
<?php
$sag->show();

$loginFormAction = $_SERVER['PHP_SELF'];
if(isset($_POST['save'])){
	$sag->opm_stock=$_POST['stock'];
	$sag->save_plan();	
	refresh();
}
	
if(isset($_POST['insert_sag'])){
	$sag->create_sag_outplan();
	refresh();
}
	
if(isset($_POST['new']))
{
	$query="select count(*)+1 as id FROM dbo.OUT_PRODUCT WHERE (OPM_ORDER_NO = '".$_GET['order_no']."')";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$showlotn='index.php?url=x_lot&id='.$row[0].'&order_no='.$_GET['order_no'].'&new=Y&pid='.$_GET['pid'];
	jumpto($showlotn);
}

?>