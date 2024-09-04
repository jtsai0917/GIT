<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
lock_head();

echo 'Lot 選擇</br><table id="GridView1" width="420" border="1"><tr class="GridviewScrollHeader"><td width="220">Lot No</td><td width="100">數量</td><td width="100">桶數</td></tr>';
$query="select STK.STK_LOT_NO ,STK.PDD_PROD_NO, STK.STK_QTY  - STK.STK_PRE_OUT_QTY  as AvaQty , STK.STK_DRUM_COUNT - STK.STK_PRE_OUT_DRUM_COUNT as 
		AvaDrumCount , STK.CTD_CUST_NO  from PRODUCT_STOCKS STK  where STK.PDD_PROD_NO = '".$_GET['pid']."' and STK.STK_QTY  - STK.STK_PRE_OUT_QTY  > 0 or 
		STK.STK_LOT_NO='' order by STK_MAKE_DATE desc";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result))
{
	echo '<tr class="GridviewScrollItem" >';
	echo '<td>'.$row['STK_LOT_NO']."</td><td>".$row['AvaQty']."</td><td>".$row['AvaDrumCount'].'</td></tr>';
}
echo '</table>';
echo '</br>';
?>
<form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<table width="420" border="1" ><tr><td width="160" >可用庫存：
  </td><td width="260">選擇數量：<input name="qty" type="text" id="qty" size="8" /></td></tr>
  <td>可用桶數：</td><td>桶&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;數：<input name="drum_qty" type="text" id="drum_qty" size="8" />

  </td><td><input name="submit" type="submit" id="submit" value="儲&nbsp;&nbsp;&nbsp;&nbsp;存" ></td>
  </tr>
  </table>
  
</form>