<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php 
session_start();
include("../lib/fun.php");
include("../checkuser.php");
include("../lib/jtsai.php");
lasturl();
datepick();
if($_SESSION['datepicker1']==''){$_SESSION['datepicker1']=date("m/d/Y")   ;}
if($_SESSION['datepicker2']==''){$_SESSION['datepicker2']=date("m/d/Y")   ;}
if($_SESSION['class_type']=='P'){$k1='selected';$k2='';$k3='';}
if($_SESSION['class_type']=='A'){$k1='';$k2='selected';$k3='';}
if($_SESSION['class_type']=='R'){$k1='';$k2='';$k3='selected';}
?>
<form name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <table width="1240" border="1">
    <tr>
      <td width="480"><p>類型: 
        <label for="class_type"></label>
        <select name="class_type" id="class_type" onchange="set_date_session(this.name,this.value)">
          <option value="P" <?php echo $k1;?>>製品</option>
          <option value="A" <?php echo $k2;?>>解析</option>
          <option value="R" <?php echo $k3;?>>受入</option>
        </select>
        LOT充填日期
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
		?>" readonly>
          <input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onClick="window.open('../pdd_prod_no.php ', '_self');" >
          <input name="pdd_chemical2" type="text" id="pdd_chemical2" size="16" value="<?php echo get_prod_name($_SESSION['pid']);?>" readonly>
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
?>">    
          </br>  
          出荷先：
          <input type="button" name="X3" id="X3" value="X" onclick="window.open('../erase_customer.php ', '_self');" />
          <input name="pdd_chemical3" type="text" id="pdd_chemical3" size="10" value="<?php echo $_SESSION['cust_no'];?>" readonly="readonly" />
          <input type="button" name="pdd_no2" id="pdd_no2" value="查詢" onclick="window.open('../cust_no.php?sup=N ', '_self');" />
          <input name="pdd_chemical4" type="text" id="pdd_chemical4" size="16" value="<?php echo get_cust_name($_SESSION['cust_no']);?>" />
          檢驗項目：
          <?php select_ani_group();?>
          
          <span class="d1">非定常分析
          <input type="checkbox" name="rut" />
          </span>
          <input type="submit" name="search" id="search" value="    搜  尋   " />
          <?php //        <input type="button" name="peint" id="peint" value="列印"> ?>
          <input type="button" name="exit" id="exit" value="離開" />
          <input type="hidden" name="mm_insert" id="mm_insert" value="form1" />
        </p>
        </td>

    </tr>
  </table>
</form>
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
                            AnalyzeDesign.AND_CANCEL, AnalyzeDesign.AND_REPORT_DATETIME, EMPLOYEE_DATA.EMP_NAME, 
                            AnalyzeDesign.AND_MEMO, AnalyzeDesign.AND_LOT_NO, AnalyzeDesign.AND_APPLY_DATE, 
                            PRODUCT_DATA.PDD_TYPE, PRODUCT_DATA.PDD_PROD_NO, PRODUCT_DATA.PDD_CHEMICAL, 
                            PRODUCT_DATA.PDD_PROD_NAME, AnalyzeDesign.AND_ITEM, AnalyzeDesign.AND_NEED_NO, 
                            PRODUCT_TYPE.department, AnalyzeDesign.rut, AnalyzeDesign.AND_SMP_DATETIME
FROM              PRODUCT_TYPE INNER JOIN
                            PRODUCT_DATA ON PRODUCT_TYPE.PDD_PROD_NO = PRODUCT_DATA.PDD_PROD_NO RIGHT OUTER JOIN
                            AnalyzeDesign LEFT OUTER JOIN
                            FILLPLAN_OUT_DECIDE ON AnalyzeDesign.AND_LOT_NO = FILLPLAN_OUT_DECIDE.FDM_LOT_NO ON 
                            PRODUCT_DATA.PDD_PROD_NO = AnalyzeDesign.AND_GOODS LEFT OUTER JOIN
                            EMPLOYEE_DATA ON AnalyzeDesign.AND_PERSON = EMPLOYEE_DATA.EMP_NO ";		
if($_POST['class_type']=='P'){$query.=" WHERE          (AnalyzeDesign.AND_NEED_NO<150)";}
elseif($_POST['class_type']=='A'){$query.=" WHERE          (AnalyzeDesign.AND_NEED_NO>149) and (AnalyzeDesign.AND_NEED_NO<270) ";}
elseif($_POST['class_type']=='R'){$query.=" WHERE          (AnalyzeDesign.AND_NEED_NO>=270) ";}

// (dbo.FILLPLAN_OUT_DECIDE.FOD_YEAR_MONTH + dbo.FILLPLAN_OUT_DECIDE.FOD_DAY <= '20151220')

if ($_POST['pdd_chemical1']){
	$_SESSION['pid']=$_POST['pdd_chemical1'];
	$query=$query." AND (AnalyzeDesign.AND_GOODS ='".$_POST['pdd_chemical1']."')";
}
elseif($_SESSION['pid']){
	$query=$query." AND (AnalyzeDesign.AND_GOODS ='".$_SESSION['pid']."')";
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
	if($_POST['datepicker1']){
	$_SESSION['datepicker1']=$_POST['datepicker1'];
    $query=$query." AND (AnalyzeDesign.AND_SMP_DATETIME >='".dod($_SESSION['datepicker1'])."0000')";
}
elseif($_SESSION['datepicker1'])
{
	$query=$query." AND (AnalyzeDesign.AND_SMP_DATETIME >='".dod($_SESSION['datepicker1'])."0000')";
}
if ($_POST['datepicker2']){
	$_SESSION['datepicker2']=$_POST['datepicker2'];
	$query=$query." AND ( AnalyzeDesign.AND_SMP_DATETIME <='".dod($_SESSION['datepicker2'])."2359')";
}
elseif($_SESSION['datepicker2']){
	$query=$query." AND ( AnalyzeDesign.AND_SMP_DATETIME <='".dod($_SESSION['datepicker2'])."2359')";
}
if($_POST['rut']=='on'){
	$query.=" and (rut=1) ";	
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

// echo $query."<BR>";

$result = mssql_query($query);
$numRows = mssql_num_rows($result);
if($_POST['class_type']=='P'){echo "製品";}
if($_POST['class_type']=='A'){echo "分析";}
if($_POST['class_type']=='R'){echo "受入";}

?>

<table width="1240" border="1">
  <tr bgcolor="#CCCCCC">
    <td height="30" width="80">日期序號</td>
    <td width="76">客戶</td>
    <td width="84">品名</td>
    <td width="29">容器</td>
    <td width="67">LotNO</td>
    <td width="28">定常</td>
    <td width="603"><font color="#FF0000">僅用於輸入用‧ 點選分析群組名稱可以進入輸入分析資料畫面. 分析項目本身不會有顏色變化用來區別分析及報告的狀況</font></td>

  </tr>
<?php
while($row = mssql_fetch_array($result))
{   
$chemical=trim($row['PDD_CHEMICAL']);
$pdd_no=trim($row['PDD_PROD_NO']);
$str1=$row['PDD_PROD_NO'];
$str2="P001";
$str3="UP007";
$str4="UP319";
$str5="P007";
$str6="UP001";
$cus=new get_from_lot_no;
$cus->lid=$row['AND_LOT_NO'];
$cus->cid();
/*
$diff=new difference_too_large;
$diff->LotNo=$row['AND_LOT_NO'];
$diff->status();

	$note=$row['AND_NOTE'];
	$rpd=$row['AND_REPORT_DATETIME'];
	if($row['AND_CANCEL']==1){$rpd='已取消';echo '<tr bgcolor="#FF9900">';}
	elseif($diff->flag=='1'){$note=$diff->item.' 差異太大';echo '<tr bgcolor="#FF9900">';}
	else{echo '<tr>';}
*/
	echo '<tr>';
    echo '<td>'.$row['AND_APPLY_DATE']."-".$row['AND_NEED_NO'].'</td>';
	echo '<td>'.$cus->csname.'</td>';
    echo '<td>'.$row['PDD_PROD_NAME'].'</td>';
    echo '<td>'.$row['PDD_TYPE'].'</td>';
    echo '<td>'.$row['AND_LOT_NO'].'</td>';
	if($row['rut']==1){$rut='否';}else{$rut='是';}
	echo '<td>'.$rut.'</td>';
//	if(substr($row['AND_ITEM'],-1)==','){$row['AND_ITEM']=substr($row['AND_ITEM'],0,-1);}
// 	echo "LOT:".trim($row['AND_LOT_NO'])."CHEMICAL: ".$row['PDD_CHEMICAL']."PDD_NAME: ".$row['PDD_PROD_NO']."<BR>";
	anl($row['AND_ITEM'],trim($row['AND_LOT_NO']),$row['EMP_NAME'],$chemical,$pdd_no);
/*    
	echo '<td><a target="_self" href="'.$url.$row['AND_LOT_NO']."&testdate=".$row['AND_APPLY_DATE']."&operator=".iconv("big5","utf-8",$row['EMP_NAME']).'">ASSAY</a></td>';
    echo '<td><a target="_self" href="'.$ur2.$row['AND_LOT_NO']."&testdate=".$row['AND_APPLY_DATE']."&operator=".iconv("big5","utf-8",$row['EMP_NAME']).'">NH4</a></td>';
	    echo '<td><a target="_self" href="'.$ur3.$row['AND_LOT_NO']."&testdate=".$row['AND_APPLY_DATE']."&operator=".iconv("big5","utf-8",$row['EMP_NAME']).'">UV</a></td>';
*/
	echo '</tr>';
}
}


/*
function anl($str,$lot_no,$operator,$pdd_chemical,$pdd_prod_no){
	$query1="SELECT DISTINCT ani_group as ANI_GROUPNAME 
FROM              sampler
WHERE          (lotno = N'".$lot_no."')";
// echo '</br>'.$query1.'</br>';
$result1 = mssql_query($query1);
$numRows = mssql_num_rows($result1);
echo '<td>';
while($row = mssql_fetch_array($result1)){

if($row['ANI_GROUPNAME']=='PMS' or $row['ANI_GROUPNAME']=='FPMS' or $row['ANI_GROUPNAME']=='TM'){$_SESSION['passornot']=1;}
if($row['ANI_GROUPNAME']=='TM'){
	echo '<a target="_self" href=index.php?url=input_report_TM&lot_no='.trim($lot_no)."&pdd_prod_no=".trim($pdd_prod_no)."&ani_groupname=".trim($row['ANI_GROUPNAME'])."&pdd_chemical=".$pdd_chemical."&operator=".$operator.' '.'>'.$row['ANI_GROUPNAME'].',  </a>';
}
else{
	echo '<a target="_self" href=index.php?url=input_report&lot_no='.trim($lot_no)."&ani_groupname=".trim($row['ANI_GROUPNAME'])."&pdd_chemical=".$pdd_chemical."&pdd_prod_no=".$pdd_prod_no."&operator=".$operator.'>'.$row['ANI_GROUPNAME'].', </a>';}
}
echo '</td>';
}
*/


function anl($str,$lot_no,$operator,$pdd_chemical,$pdd_prod_no){
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
while($row = mssql_fetch_array($result)){

if($row['ANI_GROUPNAME']=='PMS' or $row['ANI_GROUPNAME']=='FPMS' or $row['ANI_GROUPNAME']=='TM'){$_SESSION['passornot']=1;}
if($row['ANI_GROUPNAME']=='TM'){
	echo '<a target="_blank" href=index.php?url=input_report_TM&lot_no='.trim($lot_no)."&pdd_prod_no=".trim($pdd_prod_no)."&ani_groupname=".trim($row['ANI_GROUPNAME'])."&pdd_chemical=".$pdd_chemical."&operator=".$operator.' '.'>'.$row['ANI_GROUPNAME'].',  </a>';
}
else{
	echo '<a target="_blank" href=index.php?url=input_report&lot_no='.trim($lot_no)."&ani_groupname=".trim($row['ANI_GROUPNAME'])."&pdd_chemical=".$pdd_chemical."&pdd_prod_no=".$pdd_prod_no."&operator=".$operator.'>'.$row['ANI_GROUPNAME'].', </a>';}
}
echo '</td>';
}

?>
</table>
<br>