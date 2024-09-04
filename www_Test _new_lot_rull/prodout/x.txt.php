<?php
include("../lib/fun.php");
include("../connections/conn.php");
include("../lib/jtsai.php");
lasturl();
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
  <td><input type="submit" name="submit" id="submit" value=" 確定 " /></td></tr></table>
<table width="480" border="1"><tr><td width="30">選擇</td><td width="270">Lot No</td><td width="90">庫存數量</td><td width="90">庫存桶數</td></tr>
<?php
$d=strtotime("-180 Days"); 
$make_date = date("Ymd",$d);
$query="SELECT          STK_LOT_NO, PDD_PROD_NO, CTD_CUST_NO, STK_OLD_LOT_NO, STK_MAKE_DATE, STK_QTY, 
                            STK_DRUM_COUNT, STK_PRE_OUT_QTY, STK_PRE_OUT_DRUM_COUNT, STK_DECI_OUT_QTY
		FROM              PRODUCT_STOCKS
		WHERE          (PDD_PROD_NO = '".$_GET['pid']."') AND (STK_QTY > 0) AND (STK_PRE_OUT_QTY = 0) AND 
                            (STK_MAKE_DATE > '".$make_date."')
		ORDER BY   STK_MAKE_DATE DESC";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row=mssql_fetch_array($result))
{
echo '<tr><td align="center"><input type="radio" name="radio" value="'.$row['STK_LOT_NO'].",".$row['STK_QTY'].'" /></td>'	;
echo '<td>'.$row['STK_LOT_NO'].'</td><td>'.$row['STK_QTY'].'</td><td>'.$row['STK_DRUM_COUNT'].'</td></tr>';
}
?>

</table></br>
</form>
<?php
	$loginFormAction = $_SERVER['PHP_SELF'];
	if($_GET['qty']<>'')
	{
		$pp=new product;
		$pp->pid=$_GET['pid'];
		$pp->getone();
		$_kg=$_GET['qty']*$pp->pdd_liter_kg;	
		$query3="UPDATE      dbo.OUT_PRODUCT 
				SET         OPD_LOT_NO ='".$_GET['lid']."',OPD_QTY_LITER =".$_GET['qty'].", OPD_QTY_KG =".$_kg."
				WHERE (OPM_ORDER_NO = '".$_GET['order_no']."')";
		$result3 = mssql_query($query3);	
		if($result3){echo "OK";}	
		jumpto("./index.php?url=outplan");
	}
	
	if(isset($_POST['submit']))
	{		
		$aa=explode(',',$_POST['radio']);
		$lot_no=$aa[0];
		$qty=$aa[1];
		echo "<script type='text/javascript'>set_lot();";
		echo 'function set_lot(){
				var sStr = prompt("'.$lot_no.'  數量：","'.$qty.'");
				var sUrl;
				if(sStr!=null&&sStr!="")
				{
					location.href="./index.php?url=x_lot&order_no='.$_GET['order_no'].'&po_no='.$_GET['po_no'].'&pid='.$_GET['pid'].'&lid='.$lot_no.'"+"&qty="+sStr;
				}
			}
			</script>';
	}
?>
</body>
</html>