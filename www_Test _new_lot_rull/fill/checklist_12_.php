<?php include("../checkuser.php");
session_start();
$_SESSION['lasturl']=$_SERVER['REQUEST_URI'];
include("../lib/fun.php");
include("../checkuser.php");
include("../lib/user_right.php");
$a1=array_user_group($_SESSION['uid'],'充填管理者');
$a2=array_user_group($_SESSION['uid'],'充填主管');
$_SESSION['befor_sig']=$_SERVER['REQUEST_URI'];
//echo "A1:".$a1."A2".$a2."<BR>";
if($a1==0){ $disable1=' disabled="disabled" ';}else{ $disable1='';}
if($a2==0){ $disable2=' disabled="disabled" ';}else{ $disable2='';}
lasturl();
datepick();
if($_SESSION['datepicker1']==''){$_SESSION['datepicker1']=date("m/d/Y");}
?><head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
  <title>jQuery UI Datepicker - Default functionality</title>


<form name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <label for="textfield"></label>
  <table width="1200" border="1">
    <tr>
      <td width="480">要求日期
        <input name="datepicker1" type="text" id="datepicker1" size="14" value="<?php echo $_SESSION['datepicker1'] ; ?>"   onchange="set_date_session(this.name,this.value)">
~
        <input name="datepicker2" type="text" id="datepicker2" size="14" value="<?php echo $_SESSION['datepicker2'] ; ?>"   onchange="set_date_session(this.name,this.value)">
        <span class="d1">
        <input type="submit" name="search" id="search" value="搜尋">
        </span></td>
    </tr>
  </table>


<?php
$loginFormAction = $_SERVER['PHP_SELF'];
	echo '
	<table width="600" border="1">
  	<tr>
    <td>Lot No</td>
    <td>產品料號</td>
    <td>產品名稱</td>
    <td>充填日期</td>
	<td>列印</td>';
//	echo '<td>列印(新)</td>';
	echo '<td>現場主管簽核</td>
	<td align="center">主管簽核<BR><input type="submit" name="sign_selected" value="簽核勾選項" '.$disable2.'></td>
  	</tr>';
	
	/// Get list from LORRY_FILL_CHECK  ///
	include("../connections/conn.php");
	$query="SELECT          LORRY_FILL_CHECK.FDM_LOT_NO, FILLPLAN_OUT_DECIDE.PDD_PROD_NO, PRODUCT_DATA.PDD_PROD_NAME, 
                            FILLPLAN_OUT_DECIDE.FOD_YEAR_MONTH, FILLPLAN_OUT_DECIDE.FOD_DAY
FROM              LORRY_FILL_CHECK INNER JOIN
                            FILLPLAN_OUT_DECIDE ON 
                            LORRY_FILL_CHECK.FDM_LOT_NO = FILLPLAN_OUT_DECIDE.FDM_LOT_NO INNER JOIN
                            PRODUCT_DATA ON FILLPLAN_OUT_DECIDE.PDD_PROD_NO = PRODUCT_DATA.PDD_PROD_NO
			WHERE         (FILLPLAN_OUT_DECIDE.FOD_YEAR_MONTH + FILLPLAN_OUT_DECIDE.FOD_DAY >= '".dod($_SESSION['datepicker1'])."') and 
			(FILLPLAN_OUT_DECIDE.FOD_YEAR_MONTH + FILLPLAN_OUT_DECIDE.FOD_DAY <='".dod($_SESSION['datepicker2'])."') 
			order by FILLPLAN_OUT_DECIDE.FOD_YEAR_MONTH + FILLPLAN_OUT_DECIDE.FOD_DAY,FDM_LOT_NO";
//	echo $query;
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	while($row = mssql_fetch_array($result)){
	$lid=$row['FDM_LOT_NO'];
	$pid=$row['PDD_PROD_NO'];
	$pname=$row['PDD_PROD_NAME'];
	$fym=$row['FOD_YEAR_MONTH'];
	$fd=$row['FOD_DAY'];
	$sn=signatory_empname(1,$lid);

	if(trim($sn)<>''){
		$sheet001=$sn;
	}else{
		$sheet001='簽核';
	}
	
	$sn2=signatory_empname(2,$lid);
//		echo $sn2."<BR>";
		if(trim($sn2)<>''){
		$sn2=$sn2;
	}else{
		$sn2='簽核';
	}

	echo '<tr>';
	echo '<td>'.$lid.'</td>';
	echo '<td>'.$pid.'</td>';
	echo '<td>'.$pname.'</td>';
	echo '<td>'.$fym.$fd.'</td>';
	echo '<td><a href="print_1_12m3.php?lid='.$lid.'" target="new">列印</a></td>';
//	echo '<td><a href="print_1_12m3_new.php?lid='.$lid.'" target="new">列印</a></td>';
	if($a1==1){ $n1='<td><a href="fill_chklsi_12m3.files/sheet001.php?lid='.$lid.'" target="new" '.$disable1.'>'.$sheet001.'</a></td>';}
	else{$n1='<td>'.$sheet001.'</td>'; }
	echo $n1;
	
	if($a2==1){ $n2='<td><input type="checkbox" name="ckbox[]" value="'.$lid.'" '.$disable2.'><a href="../main.php?url=signatory&lid='.$lid.'&lv=2" target="new">'.$sn2.'</a></td>';}
	else{$n2='<td>'.$sn2.'</td>'; }
	echo $n2;
	
//	echo '<td><input type="checkbox" name="ckbox[]"><a href="print_1_12m3_new.php?lid='.$lid.'" target="new">簽核</a></td>';
	echo '</form></tr>';
	}
	
if(isset($_POST['sign_selected'])){
	$cc=$_POST['ckbox'];
 	$n=count($cc);
 	for($x=0;$x< $n;$x++){
	//	echo "Lot NO: ".$cc[$x]."<BR>";
		$lotno_str  = $lotno_str.$cc[$x].",";
	}
		$lotno_str=substr($lotno_str,0,-1);
		$url='../main.php?url=signatory&lid='.$lotno_str.'&lv=2';
//    echo '<script>window.open('.$url.',"_new")</script>';

	echo '<script>document.location.href="'.$url.'";</script>';
}
	
	
	echo '</table>';
if(isset($_POST["search"]))
{
	$_SESSION['datepicker1']=$ymd=$_POST['datepicker1'];
	$_SESSION['datepicker2']=$eymd=$_POST['datepicker2'];
}
?>
