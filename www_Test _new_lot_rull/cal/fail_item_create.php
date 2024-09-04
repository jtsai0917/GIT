<?php
session_start();
include("../lib/fun.php");
include("../connections/conn.php");
lasturl();
?>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <p>建立不合格品處置聯絡書</p>
  <p>Lot NO ：
    <label for="lot_no"></label>
    <input type="text" name="lot_no" id="lot_no" value="<?php echo $_SESSION['lid'];?>"/>
    <input type="submit" name="search"  value="  確定  " />
  </p>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
$ss=new get_from_lot_no;
echo $ss->lid=$_SESSION['lid'];
$ss->ani();
$ss->cid();
echo $pid=$ss->pid;
echo '客戶名稱：'.$ss->csname."   (".$ss->cid.')</br>' ;
echo '產品名稱：'.$ss->pdd_chemical."   (".$pid.')</br>' ;
echo '<p>不合格項目：</br>
    <input type="submit" name="add_testitems" id="add_testitems" value="選擇不合格分析項目" />
	<input type="submit" name="create" id="create" value="建立不合格品處置聯絡書"/>
  </p>';	
if($_POST['search']){
echo	$_SESSION['lid']=$_POST['lot_no'];
	refresh();
}

if($_POST['create'])
{
	$query="select FIR_NO from FAIL_ITEM_REPORT order by FIR_NO";
	$result = mssql_query($query);
	while($row = mssql_fetch_array($result))
	{
		$firno=$row['FIR_NO'];
	}
	$firno=$firno+1;
	$query="select * from FAIL_ITEM_REPORT where (FIR_LOT_NO='".$_SESSION['lid']."')";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	if($numRows==0)
	{
		$query="insert into dbo.FAIL_ITEM_REPORT  (FIR_NO, FIR_REPORT_DATE, FIR_YEAR, FIR_SEQ, FIR_CUST_NO, FIR_PROD_NO, FIR_LOT_NO, FIR_QTY,
		FIR_FAIL_ITEM, FIR_CREATE_TIME)
		VALUES          ('".$firno."','".date("Ymd")."','".date("Y")."','".$firno."','".$ss->cid."','".$ss->pid."','".$_SESSION['lid']."','".$ss->qty."','".$_SESSION['items1']."','".date("YmdHis")."')";
		$results = mssql_query($query);
		if (!$results) 
		{
			print("SQL statement failed with error:\n");
    		print("   ".mssql_get_last_message()."\n");
		}
		else 
		{
			my_msg("Success Created.");
		}
	}
	else
	{
		my_msg("已有此LOT 之不合格品處置聯絡書");
	}
} 
		
if($_POST['add_testitems'])
	{
		jumpto("index.php?url=add_testitems&AND_GOODS=".$pid);
		$_SESSION['pid']=$_POST['pdd_chemical1'];
	}
	
?>
</form>

