<?php
	//PDA
	echo '<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    /* 在這裡設定 select 元素的寬度 */
    select {
      width: 150px; /* 這裡可以是具體的像素值或百分比 */
      height: 30px;
    }
    select option {
      font-size: 16px; /* 這裡可以是具體的像素值 */
    }
  </style>
</head>';
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../checkuser.php");
include("../lib/jtsai.php");
lasturl();
datepick(); 

if($_SESSION['prod_no']==''){
	$_SESSION['prod_no']="P001-000";
}
if($_SESSION['cust_no']==''){
	$_SESSION['cust_no']="C00001";
}

echo '<form id="form1" method="post" action=""  style="font-size:24;">';
echo '樣品瓶接收人員：'.$_SESSION['uid']."<BR>";
if($_SESSION['s1']==1){$a0=' checked';}else{$a0='';}
if($_SESSION['s1']==3){$a1=' checked';}else{$a1='';}
if($_SESSION['s1']==4){$a2=' checked';}else{$a2='';}
if($_SESSION['s1']==2){$a3=' checked';}else{$a3='';}
echo '接收地點:<BR>';
echo '<input type="radio" name="s1" value="1" '.$a0.' onchange="set_date_session(this.name,this.value)">充填現場&nbsp;&nbsp;';
echo '<input type="radio" name="s1" value="4" '.$a2.' onchange="set_date_session(this.name,this.value)">分析&nbsp;&nbsp;';
echo '<input type="radio" name="s1" value="3" '.$a1.' onchange="set_date_session(this.name,this.value)">客戶&nbsp;&nbsp;';
echo '<input type="radio" name="s1" value="2" '.$a3.' onchange="set_date_session(this.name,this.value)">樣品室&nbsp;&nbsp;';


if ($_SESSION['s1']==3){
	echo '<br><h1 style="font-size:24;">LOT NO ：<input type="text" name="lotno" size="12" style="font-size:24;" value="'.trim($_SESSION['lotno']).'" onchange="set_date_session(this.name,this.value)">';
	echo '<br><h1 style="font-size:24;">客戶編號：';
	list_cust(trim($_SESSION['lotno']));
	echo '<BR><h1 style="font-size:24;">出荷日期：<input name="datepicker1"  style="font-size:24;" type="text" id="datepicker1" size="12" value="" >';
	echo '<br><h1 style="font-size:24;">接收瓶號：<input type="text" name="sample_no" size="8" autofocus style="font-size:24;">&nbsp;&nbsp;';
	$outdate= gts(trim($_SESSION['datepicker1']))." 12:00:00";
}

elseif ($_SESSION['s1']==4){
	echo '<br><h1 style="font-size:24;">LOT NO ： <input type="text" name="lotno" size="8" style="font-size:24;">';
	echo '<br><h1 style="font-size:24;">接收瓶號： <input type="text" name="sample_no" size="8" autofocus style="font-size:24;">&nbsp;&nbsp;';
	$outdate='';
}

else{
	echo '<br><h1 style="font-size:24;">接收瓶號： <input type="text" name="sample_no" size="8" autofocus style="font-size:24;">&nbsp;&nbsp;';
	$outdate='';
}

echo '<input type="submit" name="submit" value="確定" style="font-size:21;"></h1>';
echo '</form>';
echo '<a href="index.php"><h1 style="font-size:24;"> 返 回 </h1></a>';
$outdate= gts(trim($_SESSION['datepicker1']))." 12:00:00";

if(isset($_POST['submit']))
{
	if(($_POST['lotno']=='' or $_POST['datepicker1']=='' or $_POST['sample_no']=='' or $_POST['cust_no']=='') and ($_SESSION['s1']=='3')){
		my_msg("不得為空值");
	}
	$check1=substr(trim($_POST['lotno']),0,2);
	$check2=substr(trim($_POST['sample_no']),1,2);
//	echo "C1:".$check1."C2:".$check2;
	if($check1<>$check2 and $_SESSION['s1'] > '2'){
		my_msg("瓶號與藥品不符合");
	}
	if($_SESSION['s1']=='3' or $_SESSION['s1']==1 or $_SESSION['s1']==2 or $_SESSION['s1']==4){
		$smpid=trim($_POST['sample_no']);
		if(strlen($smpid)==8){
			$dif=substr($smpid,4,1);
			if($dif=='A' or $dif=='B' or $dif=='C' or $dif=='D' or $dif=='E' or $dif=='F' or $dif=='G' or $dif=='H' or $dif=='I' or $dif=='X' or $dif=='Y' or $dif=='Z')
			{			
				$uid=$_SESSION['uid'];
				$lotno=$_POST['lotno'];
				$location=$_SESSION['s1']=$_POST['s1'];
				$custno=$_SESSION['cust_no']=trim($_POST['cust_no']);
				if($location < 3 ){
					$custno="";
					$lotno="";
				}
				if($location == 1 ){
					$gid=max_gid();
				}
				else{
					$gid=get_gid($smpid);
				}
				$smpid=trim($smpid);
				$query = "INSERT INTO dbo.Sample_Location (Lot_NO, SMPID, Cust_NO, Out_Date, createtime, creator, LOCATION, gid) VALUES 
				(N'$lotno',N'$smpid', N'$custno', '$outdate', '".date("Y-m-d H:i:s")."', '$uid', $location, $gid)";
				$result = mssql_query($query);
				if($result){
					my_msg($smpid."輸入完成");
				}
				else{
					my_msg($smpid."輸入失敗 :".odbc_errormsg());
				}	
			}
			my_msg("樣品瓶編號錯誤");
		}
		else{
			my_msg("瓶號非八碼");
		}
	}
}
function max_gid(){
	$query="select max(gid) + 1 as mx from Sample_Location";
	$result=mssql_query($query);
	if($result){
		$row=mssql_fetch_row($result);
	return $row[0];
	}
	else{
		return 1;
	}
}
function get_lotno($smpid){
		$query="SELECT Lot_NO FROM Sample_Location WHERE (SMPID = N'$smpid') AND (Lot_NO <> '')";
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);
		return $row[0];
}
function get_gid($smpid){
	$query="select gid from Sample_Location where SMPID = '".$smpid."' order by gid desc ";
//	echo $query."<BR>";
	$result=mssql_query($query);
	if($result){
		$row=mssql_fetch_row($result);
		if($row[0]<>''){
			return $row[0];
		}
		else{
			return max_gid();
		}
	}
	else{
		return max_gid();
	}
}	
function list_prod(){
	echo '<select name="prod_no" id="prod_no" onChange="set_date_session(this.name,this.value)" >';
	include "../connections/conn.php";
	$query="SELECT PDD_PROD_NO, PDD_PROD_NAME FROM PRODUCT_DATA WHERE (PDD_PROD_NO LIKE 'P%' OR PDD_PROD_NO LIKE 'UP%') AND (PDD_CLASS = '商品' OR PDD_CLASS = '成品')";
	$result=mssql_query($query);
	while ($row=mssql_fetch_array($result)){
		if ($_SESSION['prod_no']==$row['PDD_PROD_NO']){$ss=' selected="selected"		';}
		else{ $ss =""; }
		echo '<option value="'.$row['PDD_PROD_NO'].'" '.$ss.' >'.$row['PDD_PROD_NO']."：".$row['PDD_PROD_NAME'].'</option>';
	}
	unset($result);
	echo '</select>';
}

function list_cust($lotno){
	include "../connections/conn.php";
	$query="SELECT AND_GOODS FROM AnalyzeDesign WHERE (AND_LOT_NO = '".$lotno."') ";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$prod_no=$row[0];
	
	echo '<select name="cust_no" id="cust_no"  style="min-width:205px; max-width:1805x;">';
	$query="SELECT DISTINCT OUT_DECISION.CTD_CUST_NO, CUSTOMER_DATA.CTD_CUST_SHORT_NAME,OUT_PRODUCT.PDD_PROD_NO 
FROM              OUT_DECISION INNER JOIN
                            OUT_PRODUCT ON OUT_DECISION.OPM_ORDER_NO = OUT_PRODUCT.OPM_ORDER_NO INNER JOIN
                            CUSTOMER_DATA ON OUT_DECISION.CTD_CUST_NO = CUSTOMER_DATA.CTD_CUST_NO
WHERE          (OUT_PRODUCT.OPD_LOT_NO = '".$lotno."')";
//echo $query."<BR>";
$result=mssql_query($query);
	while ($row=mssql_fetch_array($result)){
		echo '<option value="'.$row['CTD_CUST_NO'].'"  >'.$row['CTD_CUST_NO']."：".$row['CTD_CUST_SHORT_NAME'].'</option>';
	}
	
	
	$query="SELECT DISTINCT CUSTOMER_PRODUCTS.CTD_CUST_NO, CUSTOMER_DATA.CTD_CUST_SHORT_NAME
FROM              CUSTOMER_PRODUCTS INNER JOIN
                            CUSTOMER_DATA ON CUSTOMER_PRODUCTS.CTD_CUST_NO = CUSTOMER_DATA.CTD_CUST_NO
WHERE          (CUSTOMER_PRODUCTS.PDD_PROD_NO = '".$prod_no."')";
//echo $query."<BR>";
	$result=mssql_query($query);
	while ($row=mssql_fetch_array($result)){

		echo '<option value="'.$row['CTD_CUST_NO'].'" >'.$row['CTD_CUST_NO']."：".$row['CTD_CUST_SHORT_NAME'].'</option>';
	}
	unset($result);
	echo '</select>&nbsp;&nbsp;';
}

function last_location($smpid){
	$query=" SELECT TOP (1) SMPID, gid FROM Sample_Location WHERE (SMPID = '$smpid') ORDER BY createtime DESC ";
	if($result=mssql_query($query)){
		$row=mssql_fetch_row($result);
		$rt=$row[0];
	}
	else{
		$rt='NULL';
	}
	return $rt;
}
?>