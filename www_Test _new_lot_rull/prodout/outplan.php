<?php
include("../lib/sag_in.php");
lasturl();
$sag= new sign_in;
$sag->aut_no='';
datepick();
?>
<body>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
<table width="1240" border="1">
  <tr>
    <td width="250">訂單單號: 
      <label for="orderno"></label>
      <input name="orderno" type="text" id="orderno" size="15" value="<?php echo $_SESSION['orderno'];?>"/></td>
    <td width="490">出荷計畫日
		<input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php echo $_SESSION['datepicker1'] ; ?>"  onchange="set_date_session(this.name,this.value)">
        ~
        <input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php echo $_SESSION['datepicker2'] ; ?>"  onchange="set_date_session(this.name,this.value)">     
	</td>
    <td width="500">藥品名<span class="d1">
      <input type="button" name="X3" id="X3" value="X" onClick="window.open('../erase_prod.php ', '_self');" />
      <input name="pid" type="text" id="pid" size="10" value="<?php 

			echo $_SESSION['pid'];

		?>" readonly />
      <input type="button" name="pdd_no2" id="pdd_no2" value="查詢品名" onClick="window.open('../main.php?url=pdd_prod_no ', '_self');" />
      <input name="pdd_chemical3" type="text" id="pdd_chemical4" size="16" value="<?php 

			echo get_prod_name($_SESSION['pid']);

		?>" readonly />
    </span></td>
  </tr>
  <tr>
    <td>P/O No:
      <input name="pono" type="text" id="pono" size="15" value="<?php echo $_SESSION['pono'];?>"/>
    </td>
    <td>客戶：
        <input type="button" name="X" id="X" value="X" onClick="window.open('../erase_customer.php ', '_self');" />
        <input name="cid" type="text" id="cid" size="16" value="<?php echo $_SESSION['cust_no'];?>" readonly />
        <input type="button" name="pdd_no" id="pdd_no" value="查詢" onClick="window.open('../main.php?url=cust_no&sup=N ', '_self');" />
        <input name="pdd_chemical" type="text" id="pdd_chemical2" size="20" value="<?php echo $_SESSION['cust_name'];?>" />
    </td>
    <td>
      <input type="submit" name="search" id="search" value="      查      詢      " />
      <input type="submit" name="print" id="print" value=  "      列      印      " />
      <a href="fix_stock.php" target="new">修改LOT庫存</a><BR>請先勾選欲列印的訂單，列印時請選擇符合欄寬列印‧</td>
  </tr>
</table>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
if($_POST['search'])
{
	$_SESSION['datepicker1']=$_POST['datepicker1'];
	$_SESSION['datepicker2']=$_POST['datepicker2'];
	$_SESSION['pono']=$_POST['pono'];
	$_SESSION['orderno']=$_POST['orderno'];
	$_SESSION['cid']=$_POST['cid'];
	$_SESSION['pid']=$_POST['pid'];
}
if($_SESSION['datepicker1']==''){$_SESSION['datepicker1']=$_SESSION['datepicker2']=date("m/d/Y");}
	echo'<table width="1240" border="1"><tr align="center" ><td width="30"><input type="submit" name="select_all" value="√"></td>';
	echo '<td width="130">客戶</td><td width="50">訂單號碼</td><td width="50">藥品</td><td width="100">狀況</td><td width="100">P/O No:</td><td width="100">出荷計劃日</td></tr>';
	$query="SELECT          OPM_ORDER_NO, OAF_DELI_CUST_NO, OAF_DELI_CUST_NAME, PDD_PROD_NO, OAF_ETA_DATE, 
                            OAF_CUST_ORDER_NO
FROM              OUT_PLAN_FROM_FILE
WHERE (OUT_PLAN_FROM_FILE.OPM_ORDER_NO<>'') 
and          (REPLACE(OUT_PLAN_FROM_FILE.OAF_ETA_DATE, '/', '') >= '".((int)dod($_SESSION['datepicker1'])-19110000)."') and (REPLACE(OUT_PLAN_FROM_FILE.OAF_ETA_DATE, '/', '') 
<= '".((int)dod($_SESSION['datepicker2'])-19110000)."')";

if($_SESSION['pid']<>''){
	$query.=" AND (PDD_PROD_NO='".$_SESSION['pid']."')";
	}
if($_SESSION['cid']<>''){
	$query.=" AND (OAF_DELI_CUST_NO='".$_SESSION['cid']."')";
	}
if($_SESSION['pono']<>'')
	{
	$query.=" AND (OAF_CUST_ORDER_NO='".$_SESSION['pono']."')";
	}
if($_SESSION['orderno']<>''){
	$query="SELECT        
							OUT_PLAN_FROM_FILE.OPM_ORDER_NO, OUT_PLAN_FROM_FILE.OAF_DELI_CUST_NO, 
                            OUT_PLAN_FROM_FILE.OAF_DELI_CUST_NAME, OUT_PLAN_FROM_FILE.PDD_PROD_NO, 
                            OUT_PLAN_FROM_FILE.OAF_ETA_DATE, OAF_CUST_ORDER_NO
FROM              OUT_PLAN_FROM_FILE
where (OPM_ORDER_NO='".$_SESSION['orderno']."')";
	}
$query.=" order by OUT_PLAN_FROM_FILE.OPM_ORDER_NO";
//echo "<BR>".$query."<BR>";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result))
{
	if(trim($row['OPM_ORDER_NO'])!=$OPN_ORDER_NO){
	$sag->order_no=$row['OPM_ORDER_NO'];
	$sag->read_decision();
	if($sag->numrows==0){$lot_no="";}else{$lot_no="已轉為決定書";}
		echo '<tr><td align="center"><input type="checkbox" name="chkbox[]" value="'.$row['OPM_ORDER_NO'].'" '.$_SESSION['select_all'].'/></td>';
		echo '<td><a target="_self" href="index.php?url=contents_outplan&order_no='.trim($row['OPM_ORDER_NO']).'&po_no='.$row['OAF_CUST_ORDER_NO'].'&pid='.trim($row['PDD_PROD_NO']).'">'.
		get_cust_name($row['OAF_DELI_CUST_NO']).'  ('.$row['OAF_DELI_CUST_NO'].')</a></td><td>'.$row['OPM_ORDER_NO'].'</td><td>'.prod($row['OPM_ORDER_NO']).'</td><td>'.$lot_no.'</td>
		<td>'.$row['OAF_CUST_ORDER_NO'].'</td><td>'.($row['OAF_ETA_DATE']).'</td></tr>';
	}
	$OPN_ORDER_NO=trim($row['OPM_ORDER_NO']);
}
echo '</br></table></br>';
echo '</form>
</body>
</html>';
if($_POST['select_all'])
{
	if($_SESSION['select_all']==''){$_SESSION['select_all']='checked="checked"';}
	elseif($_SESSION['select_all']=='checked="checked"'){$_SESSION['select_all']='';}
	refresh();
}

if($_POST['print']){
	include_once("../lib/print.php");
	$prt=new print_outplan;
	$prt->aa=$_POST['chkbox'];
	echo "TOTAL ：".count($prt->aa)." 筆</br>";
	$prt->print_out_plan();
}
function prod($pono){
	$query="SELECT distinct PDD_PROD_NO FROM OUT_PRODUCT WHERE (OPM_ORDER_NO = '".$pono."')";
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		$rtn=$rtn.$row['PDD_PROD_NO']."<br>";	
	}
	return $rtn;
}
?>