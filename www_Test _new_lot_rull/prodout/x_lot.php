<?php
include("../lib/fun.php");
include("../connections/conn.php");
include("../lib/jtsai.php");
remurl("xlot");
$nn=new product;
$nn->pid=$_GET['pid'];
$nn->getone();
echo "TYPE:".$nn->pdd_type;	
if($_GET['new']=='Y'){$bv=" 新增 ";$name="new";}else{$bv=" 修改 ";$name="submit";}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>CHANGE LOT</title>

</head>

<body>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
<table width="480" border="0"><tr><td>選擇 LOT</td><td><a target="_self" href="<?php echo $_SERVER["HTTP_REFERER"];?>"><font size="+1">上一頁</font></a></td></tr></table>
<table width="480" border="1"><tr>
  <td><input type="submit" name="<?php echo $name;?>" id="submit" value=" <?php echo $bv;?> " /></td></tr></table>
<table width="480" border="1"><tr><td width="30">選擇</td><td width="270">Lot No</td><td width="90">庫存數量</td><td width="90">庫存桶數</td></tr>
<?php
$d=strtotime("-180 Days"); 
$make_date = date("Ymd",$d);
$query="SELECT          STK_LOT_NO, PDD_PROD_NO, CTD_CUST_NO, STK_OLD_LOT_NO, STK_MAKE_DATE, STK_QTY, 
                            STK_DRUM_COUNT, STK_PRE_OUT_QTY, STK_PRE_OUT_DRUM_COUNT, STK_DECI_OUT_QTY
FROM              dbo.PRODUCT_STOCKS
		WHERE          (PDD_PROD_NO = '".$_GET['pid']."') AND (STK_QTY > 0) AND (STK_PRE_OUT_QTY = 0) AND 
                            (STK_MAKE_DATE > '".$make_date."')
		ORDER BY   STK_MAKE_DATE DESC";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
// echo $query."<BR>";
while($row=mssql_fetch_array($result))
{
echo '<tr><td align="center"><input type="radio" name="radio" value="'.$row['STK_LOT_NO'].",".$row['STK_QTY'].",".$row['STK_DRUM_COUNT'].'" /></td>'	;
echo '<td>'.$row['STK_LOT_NO'].'</td><td>'.$row['STK_QTY'].'</td><td>'.$row['STK_DRUM_COUNT'].'</td></tr>';
}
?>

</table></br>
</form>
<?php
	$loginFormAction = $_SERVER['PHP_SELF'];
	if($_GET['qty']<>'' or $_GET['qty_drum']<>'' and $_GET['new']=='N' and $_GET['action']=='Y')
	{				
		if($nn->pdd_type=='LY'){
			$_kg=$_GET['qty']*$nn->pdd_liter_kg;
			$query3="UPDATE      dbo.OUT_PRODUCT 
					SET         OPD_LOT_NO ='".$_GET['lid']."',OPD_QTY_LITER =".$_GET['qty'].", OPD_QTY_KG =".$_kg."
					WHERE (OPM_ORDER_NO = '".$_GET['order_no']."' and OPD_SERIAL_NO=".$_GET['id'].")";
			$result3 = mssql_query($query3);	
			if($result3){echo "LY_OK";}	
			jumpto("./index.php?url=outplan");
		}
		if($nn->pdd_type=='DM' or $nn->pdd_type=='BTL'){
			$_kg=$_GET['qty_drum']*$nn->pdd_drum_kg;
			$lit=$_kg/$nn->pdd_liter_kg;
			$query3="UPDATE      dbo.OUT_PRODUCT 
					SET         OPD_QTY_LITER=".$lit.",OPD_LOT_NO ='".$_GET['lid']."',OPD_QTY_DRUM =".$_GET['qty_drum'].", OPD_QTY_KG =".$_kg."
					WHERE (OPM_ORDER_NO = '".$_GET['order_no']."'and OPD_SERIAL_NO=".$_GET['id'].")";
			$result3 = mssql_query($query3);	
			if($result3){echo "DM_OK";}	
			jumpto("./index.php?url=outplan");
		}
	}
	/// 增加出貨用的LOT NO
	elseif($_GET['qty']<>'' or $_GET['qty_drum']<>'' and $_GET['new']=='Y' and $_GET['action']=='Y')
	{				
		if($nn->pdd_type=='LY'){
			$_kg=$_GET['qty']*$nn->pdd_liter_kg;
			$query3="UPDATE      dbo.OUT_PRODUCT 
					SET         OPD_LOT_NO ='".$_GET['lid']."',OPD_QTY_LITER =".$_GET['qty'].", OPD_QTY_KG =".$_kg."
					WHERE (OPM_ORDER_NO = '".$_GET['order_no']."')";
			$result3 = mssql_query($query3);	
			if($result3){echo "LY_OK";}	
			jumpto("./index.php?url=outplan");
		}
		if($nn->pdd_type=='DM' or $nn->pdd_type=='BTL'){
			$_kg=$_GET['qty_drum']*$nn->pdd_drum_kg;
			$lit=$_kg/$nn->pdd_liter_kg;
			$query3="INSERT INTO dbo.OUT_PRODUCT
                            (OPM_ORDER_NO, OPD_SERIAL_NO, OTD_NO, OTN_NO, OTNP_SERIAL_NO, PDD_PROD_NO, OAF_PACKAGE, 
                            OPD_LOT_NO, PRA_SERIAL_NO, OPD_TERM_DATE, OPD_VALIDATE, OPD_QTY_DRUM, OPD_ACC_UNIT, 
                            OPD_QTY_LITER, OPD_QTY_KG, OPD_SIGN_RECEIPT, OPD_COA_RECEIPT, OPD_MEMO, OPD_REAL_QTY_KG, 
                            OPD_INWARD_DATE, OPD_COA_NO, COA_INDEX, OAF_ACC_ID, OPD_SMP_DATETIME, OPD_COA_DATETIME)
SELECT          TOP (1) '".$_GET['order_no']."', OPD_SERIAL_NO + 1 AS Expr1, OTD_NO, OTN_NO, OTNP_SERIAL_NO, PDD_PROD_NO, 
                            OAF_PACKAGE, '".$_GET['lid']."', PRA_SERIAL_NO, OPD_TERM_DATE, OPD_VALIDATE, '".$_GET['qty_drum']."', 
                            OPD_ACC_UNIT, ".$lit.", '".$_kg."', OPD_SIGN_RECEIPT, OPD_COA_RECEIPT, OPD_MEMO, 
                            OPD_REAL_QTY_KG, OPD_INWARD_DATE, OPD_COA_NO, COA_INDEX, OAF_ACC_ID, OPD_SMP_DATETIME, 
                            OPD_COA_DATETIME
FROM              dbo.OUT_PRODUCT AS OUT_PRODUCT_1 
WHERE          (OPM_ORDER_NO = '".$_GET['order_no']."')
ORDER BY   OPD_SERIAL_NO DESC";

			$result3 = mssql_query($query3);	
			if($result3){echo "DM_OK";}	
			jumpto("./index.php?url=outplan");
		}

	}
	
	
	if(isset($_POST['submit']))
	{		
		$aa=explode(',',$_POST['radio']);
		$lot_no=$aa[0];
		$qty=$aa[1];
		$qty_drum=$aa[2];
		if($nn->pdd_type=='LY'){
		echo "<script type='text/javascript'>set_lot();";
		echo 'function set_lot(){
				var sStr = prompt("'.$lot_no.'  L：","'.$qty.'");
				var sUrl;
				if(sStr!=null&&sStr!="")
				{
					location.href="./index.php?url=x_lot&id='.$_GET['id'].'&action=Y&new='.$_GET['new'].'&order_no='.$_GET['order_no'].'&po_no='.$_GET['po_no'].'&pid='.$_GET['pid'].'&lid='.$lot_no.'"+"&qty="+sStr;
				}
			}
			</script>';
		}
		if($nn->pdd_type=='DM' or $nn->pdd_type=='BTL'){
		echo "<script type='text/javascript'>set_lot();";
		echo 'function set_lot(){
				var sStr = prompt("'.$lot_no.'  桶數：","'.$qty_drum.'");
				var sUrl;
				if(sStr!=null&&sStr!="")
				{
					location.href="./index.php?url=x_lot&id='.$_GET['id'].'&action=Y&new='.$_GET['new'].'&order_no='.$_GET['order_no'].'&po_no='.$_GET['po_no'].'&pid='.$_GET['pid'].'&lid='.$lot_no.'"+"&qty_drum="+sStr;
				}
			}
			</script>';
		}
	}
	
	if(isset($_POST['new']))
	{		
		$aa=explode(',',$_POST['radio']);
		$lot_no=$aa[0];
		$qty=$aa[1];
		$qty_drum=$aa[2];
		if($nn->pdd_type=='LY'){
		echo "<script type='text/javascript'>set_lot();";
		echo 'function set_lot(){
				var sStr = prompt("'.$lot_no.'  重量：","'.$qty.'");
				var sUrl;
				if(sStr!=null&&sStr!="")
				{
					location.href="./index.php?url=x_lot&action=Y&new=Y&order_no='.$_GET['order_no'].'&po_no='.$_GET['po_no'].'&pid='.$_GET['pid'].'&lid='.$lot_no.'"+"&qty="+sStr;
				}
			}
			</script>';
		}
		if($nn->pdd_type=='DM' or $nn->pdd_type=='BTL'){
		echo "<script type='text/javascript'>set_lot();";
		echo 'function set_lot(){
				var sStr = prompt("'.$lot_no.'  桶數：","'.$qty_drum.'");
				var sUrl;
				if(sStr!=null&&sStr!="")
				{
					location.href="./index.php?url=x_lot&action=Y&new=Y&order_no='.$_GET['order_no'].'&po_no='.$_GET['po_no'].'&pid='.$_GET['pid'].'&lid='.$lot_no.'"+"&qty_drum="+sStr;
				}
			}
			</script>';
		}
	}
?>
</body>
</html>