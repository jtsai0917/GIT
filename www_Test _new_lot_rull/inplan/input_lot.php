<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php 
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
lasturl();
datepick();
?>
<form name="form1" method="post" action="">
  <p>Lot No :
	<input type="text" name="lotno" id="lotno" value="<?php echo $_SESSION['lotno'];?>"  onChange="set_date_session(this.name,this.value)">
  	<input type="submit" name="modify" id="modify" value="   修  改   ">
   	<input type="submit" name="modify1" id="modify1" value="   儲存/離開   ">
  </p>
  <p>客戶：<span class="d1">
  <input type="button" name="X2" id="X2" value="X" onClick="window.open('../erase_customer.php ', '_self');" />
  <input name="cid2" type="text" id="cid2" size="16" value="<?php echo $_SESSION['cust_no2'];?>" readonly />
  <input type="button" name="pdd_no2" id="pdd_no2" value="查詢" onClick="window.open('../cust_no.php?sup=N&serial_id=cust_no2 ', '_self');" />
  <input name="pdd_chemical2" type="text" id="pdd_chemical" size="20" value="<?php echo get_cust_name($_SESSION['cust_no2']);?>" />
  </span></p>
</form>
<?php
echo '<table border="1" width="600"><tr><td width="150">製造商 Lot</td><td width="150">TYS Lot</td><td width="150">數量</td><td width="150">客戶</td></tr>';
$n= count($_SESSION['ab']);
$ab=$_SESSION['ab'];
for($i=0;$i<$n;$i++){
	if($_SESSION['i']==$i){ //取陣列值填寫入上方編輯欄位
				$bgcolor=  'bgcolor="yellow"';
	}
	else{$bgcolor='';}
	
echo '<tr onClick="set_date_session('."'i'".','."'".$i."'".')" '.$bgcolor.'>';
echo '<td width="150">'.$ab[$i][10].'</td><td width="150">'.$ab[$i][5].'</td><td width="150">'.$ab[$i][8].'</td><td width="150">'.$ab[$i][13].'</td></tr>';	
}
echo '</table>';
if(isset($_POST['modify'])){
	$_SESSION['lotno']=$_POST['lotno'];
	 $_SESSION['ab'][$_SESSION['i']][5]=$_POST['lotno'];
	 $_SESSION['ab'][$_SESSION['i']][14]=$_POST['cid2'];
	 $_SESSION['ab'][$_SESSION['i']][13]=get_cust_name($_SESSION['cust_no2']);
	refresh();
}
if(isset($_POST['modify1'])){
	$n= count($_SESSION['ab']);
	$ab=$_SESSION['ab'];
	fun_confirm("確定更改");
	if (fun_confirm)
	{
	for($i=0;$i<$n;$i++){

		$query="update PRODUCT_RUNNING_ACCOUNT set PRA_LOT_NO = '".$_SESSION['ab'][$i][5]."' where (IPA_PO_NO = '".$_GET['po_no']."' and IAP_SERIAL_NO = ".($i+1).")";
		$result=mssql_query($query);
		$query=" update IN_PLAN_PRODUCT set IAP_LOT_NO = '".$_SESSION['ab'][$i][5]."', IAP_OUT_CUST_NO= '". $_SESSION['ab'][$i][14]."' where IPA_PO_NO = '".$_GET['po_no']."' and IAP_SERIAL_NO = ".($i+1);

		$result=mssql_query($query);
		if (!$result) {
  		  		fun_alert("輸入失敗");
		 	}
		}
	}
		$usi='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?url=list_po&po_no='.$_GET['po_no'];;
		echo '<script>document.location.href="'.$usi.'";</script>';	
}
?>
