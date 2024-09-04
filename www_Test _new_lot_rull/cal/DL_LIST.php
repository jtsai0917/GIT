<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php 
session_start();
include("../lib/fun.php");
include("../checkuser.php");
include("../lib/jtsai.php");
lasturl();
datepick();
if($_SESSION['datepicker1']==''){$_SESSION['datepicker1']=date("m/d/Y");}
if($_SESSION['datepicker2']==''){$_SESSION['datepicker2']=date("m/d/Y");}
?>
<form name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <table width="1240" border="1">
    <tr>
      <td width="480">日期
        <input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php echo $_SESSION['datepicker1'] ; ?>"  onchange="set_date_session(this.name,this.value)">
        ~
        <input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php echo $_SESSION['datepicker2'] ; ?>"  onchange="set_date_session(this.name,this.value)">
        品名：<span class="d1">
        <input type="button" name="X" id="X" value="X" onClick="window.open('../erase_prod.php ', '_self');">
        <input name="pdd_chemical1" type="text" id="pdd_chemical1" size="10" value="<?php 
		if ($_GET['pid']){
			echo $_GET['pid'];
			$_SESSION['pid']=$_GET['pid'];
		}
		elseif($_SESSION['pid']){
			echo $_SESSION['pid'];
		}
		else{
		echo '';
		}
		?>" >
        <input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onClick="window.open('../pdd_prod_no.php ', '_self');" >
        <input name="pdd_chemical2" type="text" id="pdd_chemical2" size="16" value="<?php echo get_prod_name($_SESSION['pid']);?>" >
        Lot No：
        
        <input name="textfield4" type="text" id="textfield4" size="10" onchange="set_date_session(this.name,this.value)" value="<?php 
if ($_GET['lid']){
			echo $_GET['lid'];
			$_SESSION['textfield4']=$_GET['lid'];
		}
		elseif($_SESSION['textfield4']){
			echo $_SESSION['textfield4'];
		}
		else{
		echo '';
		}
?>
">    </br>  
          
          檢驗項目：
          <?php select_ani_group();?>
		  
<input type="submit" name="search" id="search" value="    搜  尋   " />
          <?php //        <input type="button" name="peint" id="peint" value="列印"> ?>
          <input type="button" name="exit" id="exit" value="離開" />
          <input type="hidden" name="mm_insert" id="mm_insert" value="form1" />
        </td>

    </tr>
  </table>

<?php
$loginFormAction = $_SERVER['PHP_SELF'];
if(isset($_POST['search'])){
		$_SESSION['lid']=$_POST['textfield4'];
		$_SESSION['datepicker2']=$_POST['datepicker2'];
		$_SESSION['datepicker1']=$_POST['datepicker1'];
		$_SESSION['prod_id']=$_POST['pdd_chemical1'];
		$_SESSION['selected_group']=$_POST['selected_group'];
		$_SESSION['cid']=$_SESSION['cust_no']=$_POST['pdd_chemical3'];
$d=strtotime("+0 Days");
if (!$_SESSION['datepicker1']){$_SESSION['datepicker1']=date("m/d/Y",$d);}
if (!$_SESSION['datepicker2']){$_SESSION['datepicker2']=date("m/d/Y",$d);}
$_SESSION['lasturl']=$_SERVER['REQUEST_URI'];
include('../connections/conn.php'); 
$query="SELECT DISTINCT 
                            SUBSTRING(AnalyzeDesign.AND_LOT_NO, 0, DATALENGTH(AnalyzeDesign.AND_LOT_NO)) AS LOT_NO, AnalyzeDesign.AND_APPLY_DATE,
                            AnalyzeDesign.AND_NEED_NO, AnalyzeDesign.AND_GOODS, PRODUCT_DATA.PDD_PROD_NO, AnalyzeDesign.AND_ITEM,EMPLOYEE_DATA.EMP_NAME,
                            AnalyzeDesign.AND_SMP_DATETIME,PRODUCT_DATA.PDD_PROD_NAME, AnalyzeDesign.AND_REPORT_DATETIME, PRODUCT_DATA.PDD_CHEMICAL 
FROM              AnalyzeDesign INNER JOIN
                            PRODUCT_DATA ON AnalyzeDesign.AND_GOODS = PRODUCT_DATA.PDD_PROD_NO INNER JOIN
                            EMPLOYEE_DATA ON AnalyzeDesign.AND_PERSON = EMPLOYEE_DATA.EMP_NO 
WHERE          (AnalyzeDesign.AND_NEED_NO>=150 and AnalyzeDesign.AND_NEED_NO<270) and ((substring(AnalyzeDesign.AND_LOT_NO,8,3) = 'A11' or substring(AnalyzeDesign.AND_LOT_NO,8,3) = 'B11' or substring(AnalyzeDesign.AND_LOT_NO,8,3) = 'A25' or substring(AnalyzeDesign.AND_LOT_NO,8,3) = 'A21' or  substring(AnalyzeDesign.AND_LOT_NO,8,3) = 'ICP') and AnalyzeDesign.AND_LOT_NO not like 'TDL%') ";

// (dbo.FILLPLAN_OUT_DECIDE.FOD_YEAR_MONTH + dbo.FILLPLAN_OUT_DECIDE.FOD_DAY <= '20151220')

if ($_POST['pdd_chemical1']){
	$_SESSION['pid']=$_POST['pdd_chemical1'];
	$query=$query." AND (AnalyzeDesign.AND_GOODS like '%".$_POST['pdd_chemical1']."%')";
}
elseif($_SESSION['pid']){
	$query=$query." AND (AnalyzeDesign.AND_GOODS like '%".$_SESSION['pid']."%')";
}

if ($_POST['pdd_chemical3']){
	$_SESSION['cust_no']=$_POST['pdd_chemical3'];
	$query=$query." AND (FILLPLAN_OUT_DECIDE.CTD_CUST_NO ='".$_SESSION['cust_no']."')";
}
elseif($_SESSION['cust_no']){
	$query=$query." AND (FILLPLAN_OUT_DECIDE.CTD_CUST_NO ='".$_SESSION['cust_no']."')";
}

if ($_POST['textfield4']<>""){
	$_POST['textfield4']=trim($_POST['textfield4']);
	$query.=" and (AnalyzeDesign.AND_LOT_NO LIKE '%".trim($_POST['textfield4'])."%')";	
}
else{
	if ($_POST['datepicker1']){
	$_SESSION['datepicker1']=$_POST['datepicker1'];
    $query=$query." AND (AnalyzeDesign.AND_SMP_DATETIME >='".dod($_POST['datepicker1'])."0000')";
}
elseif($_SESSION['datepicker1'])
{
	$query=$query." AND (AnalyzeDesign.AND_SMP_DATETIME >='".dod($_SESSION['datepicker1'])."0000')";
}
if ($_POST['datepicker2']){
	$_SESSION['datepicker2']=$_POST['datepicker2'];
	$query=$query." AND ( AnalyzeDesign.AND_SMP_DATETIME <='".dod($_POST['datepicker2'])."2359')";
}
elseif($_SESSION['datepicker2']){
	$query=$query." AND ( AnalyzeDesign.AND_SMP_DATETIME <='".dod($_SESSION['datepicker2'])."2359')";
}
}
if($_POST['selected_group']<>'0'){
	$query=$query." and (";
	$query1="SELECT ANI_INDEX
			FROM              AnalyzeItem
			WHERE          (ANI_GROUPNAME = '".$_POST['selected_group']."')";
	$result1 = mssql_query($query1);
	$numRows1 = mssql_num_rows($result1);
	$x=0;
	while ($row1 = mssql_fetch_array($result1)){
	if($x>0){$query.=" OR ";}
	$query.="(','+AnalyzeDesign.AND_ITEM+',' LIKE '%,".$row1['ANI_INDEX'].",%')";
	$x=$x+1;
	}
	$query=$query.")";
}
$query.=" ORDER BY AND_SMP_DATETIME";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);

?>
<table width="1240" border="1">
  <tr bgcolor="#CCCCCC">
    <td height="49" width="80">日期序號</td>
    <td width="30">客戶</td>
    <td width="84">品名</td>
    <td width="67">LotNO</td>
    <td width="158">備註1</td>
    <td width="95">要求完成時間</td>
    <td width="603"><p>分析項目:<font color="#0000FF">藍字</font>表示已有報告及結果，<font color="#FF0000">紅字</font>表示沒有報告及結果，<font color="green">綠字</font>表示有報告但是無檢驗結果，</p>
    <p><font color="purple">紫色</font>表示沒有報告但是有檢驗結果‧<span style="background-color: red"><font color="white">紅底白字</font></span>表示檢驗未通過‧</p></td>
<td width="131">簽核<BR><input type="submit" name="sign_selected" value="簽核(勾選)"></td>
  </tr>
<?php
while($row = mssql_fetch_array($result))
{   
$str1=$row['PDD_PROD_NO'];
$str2="P001";
$str3="UP007";
$str4="UP319";
$str5="P007";
$str6="UP001";
$cus=new get_from_lot_no;
$lotno=$cus->lid=$row['AND_LOT_NO'];
$cus->cid();
$diff=new difference_too_large;
$lot_no=$diff->LotNo=$row['AND_LOT_NO'];
$diff->status();
	$note=$row['AND_NOTE'];
	$rpd=$row['AND_REPORT_DATETIME'];
	if($row['AND_CANCEL']==1){$rpd='已取消';echo '<tr bgcolor="#FF9900">';}
	elseif($diff->flag=='1'){$note=$diff->item.' 差異太大';echo '<tr bgcolor="#FF9900">';}
	else{echo '<tr>';}
	
    echo '<td>'.$row['AND_APPLY_DATE']."-".$row['AND_NEED_NO'].'</td>';
	echo '<td>'.$cus->csname.'</td>';
    echo '<td>'.$row['PDD_PROD_NAME'].'</td>';
    echo '<td><a target="_blank" href="index.php?url=prt_abcd&lot_no='.$row['LOT_NO'].'&pid='.$row['PDD_CHEMICAL'].'">'.$row['LOT_NO'].'</a></td>';
    echo '<td>'.$note.'</td>';
    echo '<td>'.$rpd.'</td>';
	if(substr($row['AND_ITEM'],-1)==','){$row['AND_ITEM']=substr($row['AND_ITEM'],0,-1);}
	analyzereportX($row['AND_ITEM'],trim($row['LOT_NO']),$row['EMP_NAME'],$row['PDD_CHEMICAL'],$row['PDD_PROD_NO']);
/*    echo '<td><a target="_self" href="'.$url.$row['AND_LOT_NO']."&testdate=".$row['AND_APPLY_DATE']."&operator=".iconv("big5","utf-8",$row['EMP_NAME']).'">ASSAY</a></td>';
    echo '<td><a target="_self" href="'.$ur2.$row['AND_LOT_NO']."&testdate=".$row['AND_APPLY_DATE']."&operator=".iconv("big5","utf-8",$row['EMP_NAME']).'">NH4</a></td>';
	    echo '<td><a target="_self" href="'.$ur3.$row['AND_LOT_NO']."&testdate=".$row['AND_APPLY_DATE']."&operator=".iconv("big5","utf-8",$row['EMP_NAME']).'">UV</a></td>';
*/
	echo '</tr>';
}
}

function analyzereportX($str,$lot_no,$operator,$pdd_chemical,$pdd_prod_no){

include("../connections/conn.php");
$str=$str.",";
$aa=explode(',',$str,-1);
	$query="SELECT DISTINCT ANI_GROUPNAME
FROM              AnalyzeItem
WHERE          (ANI_INDEX <>'') and ";
for($i=0;$i<count($aa);$i++){
if ($i<(count($aa)-1)){
	$query.="(ANI_INDEX =".$aa[$i].") OR ";}
else {$query.="(ANI_INDEX =".$aa[$i].")";}	
}
// echo '</br>'.$str.'</br>';

$result = mssql_query($query);
$numRows = mssql_num_rows($result);
echo '<td>';
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
$oku=upload_ok($lot_no,$pdd_prod_no,$row['ANI_GROUPNAME'],$pdd_chemical);
$oka=getok($lot_no,$pdd_prod_no,$row['ANI_GROUPNAME'],$pdd_chemical);
$difference_too_large=differencetoolarge($lot_no,$row['ANI_GROUPNAME']);

if (($oku=='red') and ($oka=='red')){$ok='red';$ok1='white';}
if (($oku=='red') and ($oka=='blue')){$ok='purple';$ok1='white';}
if (($oku=='blue') and ($oka=='red')){$ok='green';$ok1='white';}
if (($oku=='blue') and ($oka=='blue')){$ok='blue';$ok1='white';}
if ($oka=='green'){$ok='white';$ok1='red';}

echo '<a target="_self" href=index.php?url=input_report3&lot_no='.$lot_no."&pdd_prod_no=".$pdd_prod_no."&ani_groupname=".
$row['ANI_GROUPNAME']."&pdd_chemical=".$pdd_chemical."&operator=".$operator.' '.'><span style="background-color: '.$ok1.'"><font color="'.$ok.'">'.$row['ANI_GROUPNAME'].',  </font></span> </a>
';
//	echo iconv("big5","utf-8",$row['ANI_GROUPNAME']).', ';
}
echo '</td>';
	echo '<td><input type="checkbox" name="chkbox[]" value="'.$lot_no.'">';
	 check_status($lot_no);
	echo '</td>';
	echo '</tr></form>';
}
if(isset($_POST['sign_selected'])){
	$cc=$_POST['chkbox'];
 	$n=count($cc);
 	$lotno_str='';
	for($x=0;$x<$n;$x++){
		echo "Lot NO: ".$cc[$x]."<BR>";
		$lotno_str  = $lotno_str.$cc[$x].",";
	}
	$lotno_str=substr($lotno_str,0,-1);
	$url='/cal/index.php?url=signatory&lotno='.$lotno_str;
	echo '<a href="/cal/index.php?url=signatory&lotno='.$lotno_str.'" target="_new" title="">合併簽核</a>';
	echo '<script>windows.open('.$url.');</script>';
	
//	echo '<script>document.location.href="'.$url.'";</script>';
}


function check_status($lotno){
	$query1="SELECT Signatory_Name, sign_items, Signatory_ID, LotNo, sign_datetime FROM Ani_Signatory where LotNo='".$lotno."' and cancel=0 order by sign_datetime desc";
	$result1=mssql_query($query1);
	if($row1=mssql_fetch_row($result1)){
		echo '<a href="/cal/index.php?url=signatory&lotno='.$lotno.'" target="_new" title="">'.$row1[0].'</a>';
	}
	else{
		echo '<a href="/cal/index.php?url=signatory&lotno='.$lotno.'" target="_new" title="">簽核</a>';
	}
}
function fill_flow($lotno){
	$query="SELECT	Fill_Flow_Chart.flow FROM Fill_Flow_Chart WHERE (Lot_No = N'".$lotno."') order by date desc";
	$url="edit_flow.php?lot_no=".$lotno;
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	if(trim($row[0])==''){$a='編輯';}else{$a=$row[0];}
	$A='<a target="_blank" href="'.$url.'">'.$a.'</a>;'.$a;
	$B=explode(";",$A);
	return $B;	
}
?>
</table>
<br>