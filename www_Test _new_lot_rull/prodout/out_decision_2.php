<?php
include("../lib/sag_in.php");
remurl("sag");
datepick();
$query="SELECT          OUT_DECISION.*
		FROM              OUT_DECISION
		WHERE          (OPM_ORDER_NO = '".$_GET['order_no']."')";
//		echo $query."<BR>";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
		$cid=$row['CTD_CUST_NO'];
		$order_no=$row['OPM_ORDER_NO'];
		$po_no=$row['OPM_PO_NO'];
		$otd_no=$row['OTD_NO'];
		$invoice_no=$row['OTD_INVOICE_NO'];
		$eta_date=$row['OPM_ETA_DATE'];
		$sag_no=$row['SAG_NO'];
	}
?>
<table width="1240" border="0"><tr><td><?php echo '訂單號碼：'.$_GET['order_no'];?></td><td align="right"><a target="_self" href="
<?php echo 'index.php?url=out_decision';?>"><font size="+0">上一頁</font></a></td></tr></table>

<form name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <table width="1240" border="1">
    <tr bgcolor="#CCCCCC">
      <td width="620">出荷決定書/輸入</td>
      <td>客戶名稱：<?php echo get_cust_name($cid)."   (".$cid.")";?></td>
    </tr>
    <tr>
      <td>訂單號碼： <?php echo $order_no;?></td>
      <td>出荷決定日：
      <input name="datepicker1" type="text" id="datepicker1" size="14" value="<?php echo odo(substr($eta_date,0,8)) ;?>" />
      <label for="hrs"></label>
      <input name="hrs" type="text" id="hrs" size="2" value="<?php echo substr($eta_date,8,2);?>"/>
      ：
      <input name="min" type="text" id="min" size="2" value="<?php echo substr($eta_date,10,2);?>"/></td>
    </tr>
    <tr>
      <td>P/O No： <?php echo $po_no;?></td>
      <td>決定書號碼：
      <input type="text" name="otd_no" id="otd_no" value="<?php echo $otd_no ;?>"/></td>
    </tr>
    <tr>
      <td>先行COA(  )  先行樣品(  )</td>
      <td>發 票  號 碼：
        <label for="invoice_no"></label>
      <input type="text" name="invoice_no" id="invoice_no" value="<?php echo $invoice_no;?>"/>
      <input type="submit" name="save" id="save" value="   存    檔   " />
      <input type="submit" name="leave" id="leave" value="   不存檔離開   " /></td>
    </tr>
  </table>
  </br>
  <table border="1" width="1240"><tr align="center" bgcolor="#CCCCCC">
  <td width="50">序號</td><td width="100">料號</td><td width="150">品名</td><td width="100">包裝型態</td><td width="100">Lot No.</td><td width="100">出貨期限</td><td width="50">單位</td>
  <td width="100">桶數</td><td width="100">數量KG</td><td width="100">數量L</td><td width="75">簽收單檢查</td><td width="75">COA檢查</td><td width="140">備註</td><td width="75">出貨次序</td>
  </tr>
<?php
	$query="SELECT          OUT_PRODUCT.*
			FROM              OUT_PRODUCT
			WHERE          (OPM_ORDER_NO = '".$_GET['order_no']."')";
//	echo $query."<BR>";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	$cc=array();$i=0;
	while($row = mssql_fetch_array($result))
	{
		$num=check_OTNP_SERIAL_NO($row['OPD_LOT_NO']);
		echo '<tr align="left">';
		echo '<td align="center">'.$row['OPD_SERIAL_NO'].'</td><td>'.$row['PDD_PROD_NO'].'</td><td>'.get_prod_name($row['PDD_PROD_NO']).'</td><td>'.$row['OAF_PACKAGE'].'</td><td>'.$row['OPD_LOT_NO'].'</td>
				<td>'.$row['OPD_TERM_DATE'].'</td><td>'.$row['OPD_ACC_UNIT'].'</td><td>'.$row['OPD_QTY_DRUM'].'</td><td>'.$row['OPD_QTY_KG'].'</td><td>'.$row['OPD_QTY_LITER'].'</td>
				<td>'.$row['OPD_SIGN_RECEIPT'].'</td><td>'.$row['OPD_COA_RECEIPT'].'</td><td>'.$row['OPD_MEMO'].'</td><td>'.$row['OPD_SERIAL_NO'].'</td>';
		echo '</tr>';
		$cc[$i][0]=$num;
	}
?>
</table>
</br>
</form>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
	
$sag=new sign_in;
$sag->cbt_no='2-03';
$sag->order_no=$_GET['order_no'];
$sag->sa_no =$sag_no;
$sag->show();

if(isset($_POST['save'])){
	$date_eta=dod($_POST['datepicker1']).$_POST['hrs'].$_POST['min']."00";
	$query="UPDATE          OUT_DECISION
			SET             OTD_NO = '".$_POST['otd_no']."', OPM_ETA_DATE = '".$date_eta."', OTD_INVOICE_NO = '".$_POST['invoice_no']."'
			WHERE          (OPM_ORDER_NO = '".$_GET['order_no']."')";
	$result = mssql_query($query);
	if($result){my_msg("OUT_DECISION Update success");}
}

if(isset($_POST['leave'])){
	jumpto("index.php?url=out_decision");
}

function check_OTNP_SERIAL_NO($lotno){
	$query="SELECT OPM_ORDER_NO  FROM OUT_PRODUCT WHERE (OPD_LOT_NO = '".$lotno."') order by COPCONFIRMSERIES";
//	echo $query."<BR>";
	$result=mssql_query($query);
	$i=0;
	while($row=mssql_fetch_array($result)){
		$i++;
		$query1="UPDATE OUT_PRODUCT SET OTNP_SERIAL_NO = ".$i." WHERE (OPD_LOT_NO = '".$lotno."') AND (OPM_ORDER_NO = '".$row['OPM_ORDER_NO']."')";
//		echo $query1."<BR>";
		$result1=mssql_query($query1);
	}
}
?>