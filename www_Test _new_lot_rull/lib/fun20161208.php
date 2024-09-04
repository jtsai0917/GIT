<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
class sql{
function run($query){
	include("../connections/conn.php");
	$results=mssql_query($query);
	}
}
session_start();
function short_date($ds){
	
	$year=substr($ds,0,4);
	$month=substr($ds,4,2);
	$day=substr($ds,6,2);
	$hr=substr($ds,8,2);
	$min=substr($ds,10,2);
	$sec=substr($ds,12,2);
	if($sec){$smalldatetime.=$year."-".$month."-".$day." ".$hr.":".$min.":".$sec;}
	elseif($min){$smalldatetime=$year."-".$month."-".$day." ".$hr.":".$min.":00";}
	elseif($hr){$smalldatetime=$year."-".$month."-".$day." ".$hr.":00:00";}
	elseif($day){$smalldatetime=$year."-".$month."-".$day." 00:00:00";}
	elseif($month){$smalldatetime=$year."-".$month."-01 00:00:00";}
	elseif($year){$smalldatetime=$year."-01-01 00:00:00";}
	else{$smalldatetime=date("Y-m-d H:i:s");}
	return $smalldatetime;

}
function dtm($ds){  //day-style  09/02/2015=>201509
$dat=substr($ds,6).substr($ds,0,-8);
return $dat;
}
function dtd($ds){  //day-style  09/02/2015=>02
$dat=substr($ds,3,2);
return $dat;
}

function gtm($ds){  //day-style  09/02/2015=>104/09/02
$dat=(substr($ds,6)-1911)."/".substr($ds,0,2)."/".substr($ds,3,2);
return $dat;
}

function dod($ds){  //day-style  09/02/2015=>20150902
$dat=substr($ds,6).substr($ds,0,-8).substr($ds,3,-5);
return $dat;
}

function ddo($ds){  //day-style  09/02/2015=>2015-09-02
$dat=substr($ds,6)."-".substr($ds,0,-8)."-".substr($ds,3,-5);
return $dat;
}

function odo($ds){  //day-style  20150902=>09/02/2015
$dat=substr($ds,4,-2)."/".substr($ds,6)."/".substr($ds,0,-4);
return $dat;
}
function ddd($ds){   //day-style  20150902=>2015-09-02
	$dat=substr($ds,0,-4)."-".substr($ds,4,-2)."-".substr($ds,6);
	return $dat;
}
function ttd($ds){   //day-style  201509020102=>09/02/2015
	$dat=substr($ds,4,+2)."/".substr($ds,6,+2)."/".substr($ds,0,+4);
	return $dat;
}
function th($ds){   //day-style  201509020102=>01
	$dat=substr($ds,8,+2);
	return $dat;
}
function tm($ds){   //day-style  201509020102=>02
$dat=substr($ds,10,+2);
	return $dat;
}
function ddt($ds){   //daytime-style  201509020102=>2015-09-02 01:02
if($ds<>NULL){$dat=substr($ds,0,+4)."-".substr($ds,4,+2)."-".substr($ds,6,+2)." ".substr($ds,8,+2).":".substr($ds,10,+2);}
$d1=" -- :";
if (false !== ($rst = strpos($dat, $d1))) {
	$dat="";
} 

	return $dat;
}
function date_cy_ny($ds){
if($ds<>NULL){$dat=(1911+(int)substr($ds,0,-6)).substr($ds,-5,2).substr($ds,-2,2);}
return $dat;		
}
function dds($ds){   //daytime-style  201509020102=>2015-09-02 01:02
if($ds<>NULL){$dat=substr($ds,6,+2)."/".substr($ds,0,+4)."/".substr($ds,4,+2);}
return $dat;
}

function std($ds){   //daytime-style  20150902010203=>2015/09/02
if($ds<>NULL){$dat=substr($ds,0,+4)."/".substr($ds,4,+2)."/".substr($ds,6,+2);}
return $dat;
}

function stm($ds){   //daytime-style  20150902010203=>01:02
if($ds<>NULL){$dat=substr($ds,8,+2).":".substr($ds,10,+2);}
return $dat;
}

function sta($ds){   //daytime-style  20150902010203=>2015/09/02 01:02:03
if($ds<>NULL){$dat=substr($ds,0,+4)."/".substr($ds,4,+2)."/".substr($ds,6,+2)." ".$dat=substr($ds,8,+2).":".substr($ds,10,+2).":".substr($ds,12,+2);}
return $dat;
}
function getusername($no){
	include("../connections/conn.php");
	$query="SELECT          EMP_NAME
FROM              EMPLOYEE_DATA
WHERE          (EMP_NO = '".$no."')";	
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$uname=$row['EMP_NAME'];
}
return $uname;
mssql_close($dbhandle);
}
function lasturl(){
$_SESSION['lasturl']=$_SERVER['REQUEST_URI'];	
}
function lasturl1(){
$_SESSION['lasturl1']=$_SERVER['REQUEST_URI'];	
}
function laststr(){
$_SESSION['laststr']=$_SERVER['QUERY_STRING'];
}
function remurl($id){
$_SESSION[$id]=$_SERVER['REQUEST_URI'];	
}
function lastpage($str){
$_SESSION[$str]=$_SERVER['REQUEST_URI'];	
}

function datepick(){
echo '
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
  <link rel="stylesheet" href="/css/jquery-ui.css">
  <link rel="stylesheet" href="/css/style.css">
  <script src="/css/jquery-1.12.4.js"></script>
  <script src="/css/jquery-ui.js"></script>
  <script src="/css/datepicker-zh-TW.js"></script>
  <script src="/css3menu/GridViewScroll/gridviewScroll.js"></script>
  <link href="/css3menu/GridViewScroll/GridviewScroll.css" rel="stylesheet" />
  <script type="text/javascript">
    $(function() {
    $( "#datepicker1" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
	$( "#datepicker2" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
	$( "#datepicker3" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
	$( "#datepicker4" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
  });
  function set_date_session(nam,val){
	window.open("../backend.php?name="+nam+"&value="+val)  
	window.location.href="'.$_SESSION['urln1'].'";
  }
</script>
</head>';
}

function analyzelist(){  //列出依賴
	
	include("../connections/conn.php");
	$query="SELECT    AnalyzeDesign.AND_NOTE, AnalyzeDesign.AND_APPLY_DATE, AnalyzeDesign.AND_NEED_NO, AnalyzeDesign.AND_LOT_NO, 
                            AnalyzeDesign.AND_GOODS, AnalyzeDesign.CTD_CUST_NO, AnalyzeDesign.AND_BEFORE, 
                            AnalyzeDesign.AND_SMP_DATETIME, AnalyzeDesign.AND_ITEM, AnalyzeDesign.AND_ANA_ID, 
                            AnalyzeDesign.AND_VALUE, AnalyzeDesign.AND_MEMO, AnalyzeDesign.AND_TOTAL, 
                            AnalyzeDesign.AND_OUT_QTY, AnalyzeDesign.AND_OUT_DATETIME, AnalyzeDesign.AND_REPORT_DATETIME, 
                            AnalyzeDesign.AND_MARK, AnalyzeDesign.AND_GET_QTY, AnalyzeDesign.AND_GET_DATETIME, 
                            AnalyzeDesign.AND_RESULT_DATETIME, AnalyzeDesign.AND_RESULT, AnalyzeDesign.AND_COA_FST, 
                            AnalyzeDesign.AND_PERSON, AnalyzeDesign.ALM_IDENTITY51, AnalyzeDesign.ALM_IDENTITY52, 
                            AnalyzeDesign.ALM_IDENTITY53, AnalyzeDesign.ALM_IDENTITY54, AnalyzeDesign.ALM_IDENTITY55, 
                            AnalyzeDesign.ALM_IDENTITY56, AnalyzeDesign.ALM_IDENTITY13, PRODUCT_DATA.PDD_PROD_NAME, 
                            EMPLOYEE_DATA.EMP_NAME,PRODUCT_DATA.PDD_PROD_NO, PRODUCT_DATA.PDD_PACKAGE, AnalyzeDesign.AND_CANCEL
FROM              AnalyzeDesign INNER JOIN
                            EMPLOYEE_DATA ON AnalyzeDesign.AND_PERSON = EMPLOYEE_DATA.EMP_NO INNER JOIN
                            PRODUCT_DATA ON AnalyzeDesign.AND_GOODS = PRODUCT_DATA.PDD_PROD_NO
WHERE          (AND_APPLY_DATE <> '') ";
if($_SESSION['pid']<>NULL){$query.=" AND (PRODUCT_DATA.PDD_PROD_NO='".$_SESSION['pid']."') ";}
if(!$_SESSION['lot1']){
	$_SESSION['lot1']=substr(date("m/d/Y"),-1).exmonth(substr(date("m/d/Y"),0,2)).substr(date("m/d/Y"),3,2);
	$_SESSION['lot2']=substr(date("m/d/Y"),-1).exmonth(substr(date("m/d/Y"),0,2)).substr(date("m/d/Y"),3,2);	
}
	$query.=" AND 		(((SUBSTRING(dbo.AnalyzeDesign.AND_LOT_NO, 2, 4) >= '".$_SESSION['lot1']."') AND 
                          (SUBSTRING(dbo.AnalyzeDesign.AND_LOT_NO, 2, 4) <= '".$_SESSION['lot2']."')) or ((SUBSTRING(dbo.AnalyzeDesign.AND_LOT_NO, 7, 4) >= '".$_SESSION['lot1']."') 				AND 
                          (SUBSTRING(dbo.AnalyzeDesign.AND_LOT_NO, 7, 4) <= '".$_SESSION['lot2']."')) or ((SUBSTRING(dbo.AnalyzeDesign.AND_LOT_NO, 4, 4) >= '".$_SESSION['lot1']."') 				AND 
                          (SUBSTRING(dbo.AnalyzeDesign.AND_LOT_NO, 4, 4) <= '".$_SESSION['lot2']."')))";
	if($_SESSION['search']=='1'){$query.=" and (AND_NEED_NO<=150)" ;}
	if($_SESSION['search']=='2'){$query.=" and (AND_NEED_NO<=250) and (AND_NEED_NO>150)";}
	if($_SESSION['search']=='3'){$query.=" and (AND_NEED_NO>250)";}
    $query.=" ORDER BY  AnalyzeDesign.AND_GOODS, PRODUCT_DATA.PDD_PACKAGE DESC";
if($_SESSION['lot_nox']<>NULL){
	$query="SELECT   AnalyzeDesign.AND_NOTE, AnalyzeDesign.AND_APPLY_DATE, AnalyzeDesign.AND_NEED_NO, AnalyzeDesign.AND_LOT_NO, 
                            AnalyzeDesign.AND_GOODS, AnalyzeDesign.CTD_CUST_NO, AnalyzeDesign.AND_BEFORE, 
                            AnalyzeDesign.AND_SMP_DATETIME, AnalyzeDesign.AND_ITEM, AnalyzeDesign.AND_ANA_ID, 
                            AnalyzeDesign.AND_VALUE, AnalyzeDesign.AND_MEMO, AnalyzeDesign.AND_TOTAL, 
                            AnalyzeDesign.AND_OUT_QTY, AnalyzeDesign.AND_OUT_DATETIME, AnalyzeDesign.AND_REPORT_DATETIME, 
                            AnalyzeDesign.AND_MARK, AnalyzeDesign.AND_GET_QTY, AnalyzeDesign.AND_GET_DATETIME, 
                            AnalyzeDesign.AND_RESULT_DATETIME, AnalyzeDesign.AND_RESULT,  AnalyzeDesign.AND_COA_FST, 
                            AnalyzeDesign.AND_PERSON, AnalyzeDesign.ALM_IDENTITY51, AnalyzeDesign.ALM_IDENTITY52, 
                            AnalyzeDesign.ALM_IDENTITY53, AnalyzeDesign.ALM_IDENTITY54, AnalyzeDesign.ALM_IDENTITY55, 
                            AnalyzeDesign.ALM_IDENTITY56, AnalyzeDesign.ALM_IDENTITY13, PRODUCT_DATA.PDD_PROD_NAME, 
                            EMPLOYEE_DATA.EMP_NAME,PRODUCT_DATA.PDD_PROD_NO, PRODUCT_DATA.PDD_PACKAGE, AnalyzeDesign.AND_CANCEL
FROM              AnalyzeDesign INNER JOIN
                            EMPLOYEE_DATA ON AnalyzeDesign.AND_PERSON = EMPLOYEE_DATA.EMP_NO INNER JOIN
                            PRODUCT_DATA ON AnalyzeDesign.AND_GOODS = PRODUCT_DATA.PDD_PROD_NO
WHERE          (AND_APPLY_DATE <> '') ";
		$query.=" AND (AnalyzeDesign.AND_LOT_NO like '%".$_SESSION['lot_nox']."%')";
}
    $result = mssql_query($query);
    $numRows = mssql_num_rows($result);
echo '<table  border="1" width="1200">';
  echo '<tr>';
    echo '<td>日期</td>';
    echo '<td>品名</td>';
    echo '<td>容量容器</td>';
    echo '<td>Lot NO</td>';
    echo '<td>目的</td>';
    echo '<td>先行</td>';
    echo '<td>預定取樣日</td>';
    echo '<td>總瓶數</td>';
    echo '<td>樣品送出瓶數時間</td>';
    echo '<td>希望報告時間</td>';
    echo '<td>樣品取得瓶數時間</td>';
    echo '<td>結果報告時間</td>';
    echo '<td>結果</td>';
	echo '<td>先行COA</td>';
    echo '<td>依賴人</td>';
	echo '<td>備註</td>';
	echo '<td>編輯</td>';
  echo '</tr>';	
while($row = mssql_fetch_array($result)){
	
	if($row['AND_NEED_NO']<=150){$purpose="製品";}
	if(($row['AND_NEED_NO']>150)&& ($row['AND_NEED_NO']<=250)){$purpose="解析";}
	if(($row['AND_NEED_NO']>250)&& ($row['AND_NEED_NO']<=350)){$purpose="受入";}
	if($row['AND_BEFORE']=="Y"){$before="是";}else{$before="否";}
	if($row['AND_CANCEL']==1){$background='bgcolor="#FF6600"';$row['AND_MARK']='已取消';}
	else{$background='';}
  echo '<tr '.$background.'>';    
    echo '<td>'.$row['AND_APPLY_DATE'].'</td>';
    echo '<td>'.$row['PDD_PROD_NAME'].'</td>';
    echo '<td width="40">'.$row['PDD_PACKAGE'].'</td>';
    echo '<td>'.$row['AND_LOT_NO'].'</td>';
    echo '<td>'.$purpose.'</td>';
    echo '<td>'.$before.'</td>';
    echo '<td>'.ddt($row['AND_SMP_DATETIME']).'</td>';
    echo '<td>'.$row['AND_TOTAL'].'</td>';
    echo '<td>'.$row['AND_OUT_QTY']."瓶 ".ddt($row['AND_OUT_DATETIME']).'</td>';
    echo '<td>'.ddt($row['AND_REPORT_DATETIME']).'</td>';
    echo '<td>'.$row['AND_GET_QTY']."瓶 ".ddt($row['AND_GET_DATETIME']).'</td>';
    echo '<td>'.ddt($row['AND_RESULT_DATETIME']).'</td>';
    echo '<td>'.$row['AND_RESULT'].'</td>';
	echo '<td>'.$row['AND_COA_FST'].'</td>';
    echo '<td>'.$row['EMP_NAME'].'</td>';
	echo '<td>'.$row['AND_NOTE'].'</td>';
	echo '<td><a href="index.php?url=edit_anylize_&AND_LOT_NO='.$row['AND_LOT_NO'].'">編輯</a></td>';
  echo '</tr>';	
}

echo '</table>';

}



function smp_rcv(){

}
function testnotonick($index,$pdd_no,$str){
$index=",".$index.",";
$str=",".$str;


$pattern="/".$index."/i";
if (preg_match($pattern, $str)){
	//return $index."==".$str;
	return 'checked="checked"';
}
}

function analyzeitem($str,$lot_no,$operator,$pdd_chemical,$pdd_prod_no){

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
while($row = mssql_fetch_array($result)){
$ok=getok($lot_no,$pdd_prod_no,$row['ANI_GROUPNAME'],$pdd_chemical);
echo '<a target="_self" href=index.php?url=input_results&lot_no='.$lot_no."&pdd_prod_no=".$pdd_prod_no."&ani_groupname=".$row['ANI_GROUPNAME']."&pdd_chemical=".$pdd_chemical.
"&operator=".$operator.' '.'><font color="'.$ok.'">'.$row['ANI_GROUPNAME'].'  ,</font> </a>
';
//	echo iconv("big5","utf-8",$row['ANI_GROUPNAME']).', ';
}
echo '</td>';
}

function analyzereport($str,$lot_no,$operator,$pdd_chemical,$pdd_prod_no){

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

echo '<a target="_self" href=index.php?url=input_report&lot_no='.$lot_no."&pdd_prod_no=".$pdd_prod_no."&ani_groupname=".
$row['ANI_GROUPNAME']."&pdd_chemical=".$pdd_chemical."&operator=".$operator.' '.'><span style="background-color: '.$ok1.'"><font color="'.$ok.'">'.$row['ANI_GROUPNAME'].',  </font></span> </a>
';
//	echo iconv("big5","utf-8",$row['ANI_GROUPNAME']).', ';
}
echo '</td>';
}

function upload_ok($lot_no,$pdd_prod_no,$ani_groupname,$pdd_cchemical){
$query="SELECT          FILE_REPORTS.*
FROM              FILE_REPORTS
WHERE          (FILE_LOT_NO = '".$lot_no."') AND (ANI_GROUP = '".$ani_groupname."')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
if ($numRows==0){return "red";}
if ($numRows>0){return "blue";}
}

function getok($lot_no,$pdd_prod_no,$ani_groupname,$pdd_cchemical){
$query1="SELECT         AND_NEED_NO
FROM             dbo.AnalyzeDesign
WHERE         (AND_LOT_NO = '".$lot_no."')";
$result = mssql_query($query1);
while($row = mssql_fetch_array($result)){$needno=$row['AND_NEED_NO'];}

$query1="SELECT DISTINCT ELEMENT_FORM.ELF_FORM AS Expr1
FROM              ELEMENT_FORM INNER JOIN
                            AnalyzeItem ON ELEMENT_FORM.ELM_ID = AnalyzeItem.ANI_INDEX
WHERE          (ELEMENT_FORM.PDD_CHEMICAL = '".$pdd_cchemical."') AND (AnalyzeItem.ANI_GROUPNAME = '".$ani_groupname."')";
$result = mssql_query($query1);
while($row = mssql_fetch_array($result)){$table=$row['Expr1'];}

$query1="SELECT dbo.".$table.".*, dbo.AnalyzeDesign.AND_NEED_NO";
if($needno<=150){$query1.=", dbo.Sample_All.* ";}
$query1.=" FROM             dbo.".$table;
 if($needno<=150){  $query1.=" INNER JOIN dbo.Sample_All ON dbo.".$table.".LotNo = dbo.Sample_All.SMA_LOT 
							AND dbo.".$table.".SampleNo = dbo.Sample_All.SMA_ID ";}
$query1.=				  " INNER JOIN  AnalyzeDesign ON ".$table.".LotNo = AnalyzeDesign.AND_LOT_NO

WHERE          (dbo.".$table.".LotNo = '".$lot_no."') order by ".$table.".AnalyzeTime desc";
if($table=='TLFQA9110113'){$_SESSION['tmp']=$query1;}
$result = mssql_query($query1);
$numRows = mssql_num_rows($result);
if($numRows==0){return "red";}
else {
while($row = mssql_fetch_array($result)){
		if ($row['Ok']==0){return "green";}
		if ($row['Ok']==1){return "blue";}
}}
}


function showcust_spec($cid,$pid,$itemname){
	include("../connections/conn.php");
	$query="SELECT DISTINCT top 1
                          QC_CustProdSpec.ProdNo, QC_Spec.ID, QC_Spec.SpecNo, QC_Spec.SpecVer, 
                          QC_Spec.IX, QC_Spec.ItemName, QC_Spec.ItemUnit, QC_Spec.USL, 
                          QC_Spec.LSL, QC_Spec.DL, QC_Spec.UCL, QC_Spec.LCL, QC_Spec.ShowMode, 
                          QC_Spec.state, QC_Spec.createuser, QC_Spec.createts, QC_Spec.Digit, 
                          QC_Spec.UAXIS, QC_Spec.LAXIS, QC_Spec.STD_U, QC_Spec.STD_L, 
                          QC_Spec.showUSL, AnalyzeItem.ANI_FULLNAME, 
                          AnalyzeItem.ANI_GROUPNAME
FROM             dbo.AnalyzeItem INNER JOIN
                          dbo.QC_CustProdSpec INNER JOIN
                          dbo.QC_Spec ON QC_CustProdSpec.SpecNo = QC_Spec.SpecNo AND 
                          QC_CustProdSpec.SpecVer = QC_Spec.SpecVer ON 
                          AnalyzeItem.ANI_FULLNAME = QC_Spec.ItemName
WHERE          (QC_CustProdSpec.CustNo = '".$cid."') AND (QC_CustProdSpec.ProdNo = '".$pid."')";

$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
$itm=$row['ItemUnit'];
$_SESSION['teststatus']=1;
$spec=$row['SpecNo'];
$ver=$row['SpecVer'];
if ($row['ShowMode']=='<DL'){$showmode='< DL';}
else {$showmode=$row['ShowMode'];}
// echo $query."</br>";
}
echo "客戶規格：  ".$spec."  版本：".$ver."</br>";
if ($numRows==0){

	echo "查無QC客規，使用社內規格</br>";
	$_SESSION['teststatus']=0;
$query="SELECT DISTINCT 
                            AnalyzeItem.ANI_DATAFIELD, AnalyzeItem.ANI_ORDER, COA_TYS.CTS_NO, COA_TYS.CTS_STYLE, 
                            COA_TYS.PDD_PROD_NO, COA_TYS.ELI_NAME, COA_TYS.CTS_DM_USL, COA_TYS.CTS_LY_USL, 
                            COA_TYS.CTS_DM_LSL, COA_TYS.CTS_LY_LSL, COA_TYS.CTS_DM_AGAIN_USL, COA_TYS.CTS_LY_AGAIN_USL, 
                            COA_TYS.CTS_DM_AGAIN_LSL, COA_TYS.CTS_LY_AGAIN_LSL, COA_TYS.CTS_DL, COA_TYS.CTS_UCL, 
                            COA_TYS.CTS_LCL, COA_TYS.ELI_UNIT
FROM              COA_TYS INNER JOIN
                            AnalyzeItem ON COA_TYS.ELI_NAME = AnalyzeItem.ANI_ID
WHERE          (COA_TYS.PDD_PROD_NO = '".$pid."') AND (AnalyzeItem.ANI_GROUPNAME = '".$_GET['ani_groupname']."') AND (COA_TYS.ELI_UNIT<>'')";
$result = mssql_query($query);
while($row = mssql_fetch_array($result)){
	$itm=$row['ELI_UNIT'];
}
}

return $itm;
}

function get_pdd_name($pdd_prod_no){
	include("../connections/conn.php");
	$query="SELECT          PDD_PROD_NAME
FROM              PRODUCT_DATA
WHERE          (PDD_PROD_NO = '".$pdd_prod_no."')";
$result = mssql_query($query);
while($row = mssql_fetch_array($result)){
return $row['PDD_PROD_NAME'];
}
}
function get_test_spec($itemname,$pid,$cid,$ani_groupname){
if ($_SESSION['teststatus']==1){
	$query="SELECT DISTINCT 
                          QC_Spec.ID, QC_Spec.SpecNo, QC_Spec.SpecVer, QC_Spec.IX, 
                          QC_Spec.ItemName, QC_Spec.ItemUnit, QC_Spec.USL, QC_Spec.LSL, 
                          QC_Spec.DL, QC_Spec.UCL, QC_Spec.LCL, QC_Spec.ShowMode, 
                          QC_Spec.state, QC_Spec.createuser, QC_Spec.createts, QC_Spec.Digit, 
                          QC_Spec.UAXIS, QC_Spec.LAXIS, QC_Spec.STD_U, QC_Spec.STD_L, 
                          QC_Spec.showUSL, dbo.QC_CustProdSpec.CustNo, 
                          dbo.QC_CustProdSpec.ProdNo, dbo.QC_Spec.ItemName AS Expr1, 
                          dbo.QC_Item.ItemNickName, dbo.AnalyzeItem.ANI_DATAFIELD
FROM             dbo.QC_CustProdSpec INNER JOIN
                          dbo.QC_Spec ON dbo.QC_CustProdSpec.SpecVer = dbo.QC_Spec.SpecVer AND 
                          dbo.QC_CustProdSpec.SpecNo = dbo.QC_Spec.SpecNo INNER JOIN
                          dbo.QC_Item ON dbo.QC_Spec.ItemName = dbo.QC_Item.ItemName INNER JOIN
                          dbo.AnalyzeItem ON 
                          dbo.QC_Item.ItemNickName = dbo.AnalyzeItem.ANI_NICKNAME
WHERE          (QC_CustProdSpec.CustNo = '".$cid."') AND (QC_CustProdSpec.ProdNo = '".$pid."') AND (AnalyzeItem.ANI_GROUPNAME = 
'".$ani_groupname."') AND (AnalyzeItem.ANI_DATAFIELD='".$itemname."')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
if(($numRows<1) and ($ani_groupname=='RAI') and ($itemname=='DsubC')){
	$query="SELECT DISTINCT 
                          QC_Spec.ID, QC_Spec.SpecNo, QC_Spec.SpecVer, QC_Spec.IX, 
                          QC_Spec.ItemName, QC_Spec.ItemUnit, QC_Spec.USL, QC_Spec.LSL, 
                          QC_Spec.DL, QC_Spec.UCL, QC_Spec.LCL, QC_Spec.ShowMode, 
                          QC_Spec.state, QC_Spec.createuser, QC_Spec.createts, QC_Spec.Digit, 
                          QC_Spec.UAXIS, QC_Spec.LAXIS, QC_Spec.STD_U, QC_Spec.STD_L, 
                          QC_Spec.showUSL, dbo.QC_CustProdSpec.CustNo, 
                          dbo.QC_CustProdSpec.ProdNo, dbo.QC_Spec.ItemName AS Expr1, 
                          dbo.QC_Item.ItemNickName
FROM             dbo.QC_CustProdSpec INNER JOIN
                          dbo.QC_Spec ON dbo.QC_CustProdSpec.SpecVer = dbo.QC_Spec.SpecVer AND 
                          dbo.QC_CustProdSpec.SpecNo = dbo.QC_Spec.SpecNo INNER JOIN
                          dbo.QC_Item ON dbo.QC_Spec.ItemName = dbo.QC_Item.ItemName
WHERE         (QC_CustProdSpec.CustNo = '".$cid."') AND 
                          (QC_CustProdSpec.ProdNo = '".$pid."') AND 
                          (dbo.QC_Item.ItemNickName = 'RE')";
$result = mssql_query($query);
}
//$ps=$query;
while($row = mssql_fetch_array($result)){
	//	echo $row['ANI_NICKNAME'];

$spec=$row['USL'];
//$spec=$query;
$again=trim($row['STD_U']);
$againl=trim($row['STD_L']);
$lsl=trim($row['LSL']);
$dl=trim($row['DL']);
$ps=trim($row['ItemUnit']);
$usl=trim($row['USL']);
	}
}
if($_SESSION['teststatus']==0){
	$query="SELECT DISTINCT 
                            AnalyzeItem.ANI_DATAFIELD, AnalyzeItem.ANI_ORDER, COA_TYS.CTS_NO, COA_TYS.CTS_STYLE, 
                            COA_TYS.PDD_PROD_NO, COA_TYS.ELI_NAME, COA_TYS.CTS_DM_USL, COA_TYS.CTS_LY_USL, 
                            COA_TYS.CTS_DM_LSL, COA_TYS.CTS_LY_LSL, COA_TYS.CTS_DM_AGAIN_USL, COA_TYS.CTS_LY_AGAIN_USL, 
                            COA_TYS.CTS_DM_AGAIN_LSL, COA_TYS.CTS_LY_AGAIN_LSL, COA_TYS.CTS_DL, COA_TYS.CTS_UCL, 
                            COA_TYS.CTS_LCL, COA_TYS.ELI_UNIT
FROM              COA_TYS INNER JOIN
                            AnalyzeItem ON COA_TYS.ELI_NAME = AnalyzeItem.ANI_ID
WHERE          (AnalyzeItem.ANI_GROUPNAME = '".$_GET['ani_groupname']."') AND (COA_TYS.PDD_PROD_NO = '".$pid."') AND (COA_TYS.ELI_UNIT <> '')";		
			$result = mssql_query($query);
				while($row = mssql_fetch_array($result)){
				$spec="<".$row['USL'];
				$dl=$row['CTS_DL'];
				$usl=$row['CTS_LY_USL'];
				$again="<".$row['CTS_LY_AGAIN_USL'];
				}

	}
	
		
return array(trim($spec),trim($dl),trim($usl),trim($again),trim($ps),trim($againl),trim($lsl));
}
function check_samples($lot_no,$sampleno){
$query="SELECT DISTINCT Sample_All.*, Sample_All.SMA_LOT AS Expr1, Sample_All.SMA_ID AS Expr2
FROM              AnalyzeDesign CROSS JOIN
                            Sample_All
WHERE          (Sample_All.SMA_LOT = '".$lot_no."') AND (Sample_All.SMA_ID = '".$sampleno."')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
if ($numRows==0){
	my_msg("錯誤的樣品瓶號碼");
}
}
function chk_smp_pid($sample_no,$pid){
	$query="SELECT * from Sample where (SMP_ID='".$sample_no."') and (SMP_MID_ID='".$pid."')";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	if($numRows>0){return 1;}
	else{return 0;}
}
function my_msg($msg,$redirect){
    echo "<SCRIPT Language=javascript>";
    echo "window.alert('".$msg."')";
    echo "</SCRIPT>";
    echo "<script language=\"javascript\">";
    echo "location.href='".$redirect."'";
    echo "</script>";
    break;
} 

function fun_alert($msg){
	echo "<script type='text/javascript'>alert('$msg');</script>";
}
function fun_confirm($msg,$fail,$true){
	echo "<script type='text/javascript'>
	if(confirm('$msg')==true){
		window.open('".$true."','_self')
	}
	else{
		window.open('../../".$fail."','_self')
	};
	</script>";
}

function _confirm($msg,$fail,$true){
	echo "<script type='text/javascript'>
	if(confirm('$msg')==true){
		window.open('".$true."','_self')
	}
	else{
		window.open('".$fail."','_self')
	};
	</script>";
}

function anyitems($str){
include("../connections/conn.php");
$aa=explode(',',$str,-1);
	$query="SELECT DISTINCT ANI_GROUPNAME
FROM              AnalyzeItem
WHERE          (ANI_INDEX <>'') and ";
for($i=0;$i<count($aa);$i++){
if ($i<(count($aa)-1)){
	$query.="(ANI_INDEX =".$aa[$i].") OR ";}
else {$query.="(ANI_INDEX =".$aa[$i].")";}	
}
//echo '</br>'.$query.'</br>';
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
$a1.=$row['ANI_GROUPNAME'].",";
}
$a1=",".$a1;
$aa=explode(',',$a1,-1);
$a3=count($aa);
	return $a3;
}

function ifchecked(){


}
function jumpto($url){
	echo '<script>document.location.href="'.$url.'";</script>';
}

function jumptonew($url){
	echo '<script>document.location.href="'.$url.'";</script>';
/*
	echo '<SCRIPT LANGUAGE="javascript">';
	echo 'window.open('.$url.')';
	echo '</SCRIPT>';
*/
}

function get_testspec($itemname,$pid,$cid,$ani_groupname){
		$query="SELECT DISTINCT 
                            QC_Spec.ID, QC_Spec.SpecNo, QC_Spec.SpecVer, QC_Spec.IX, QC_Spec.ItemName, QC_Spec.ItemUnit, 
                            QC_Spec.USL, QC_Spec.LSL, QC_Spec.DL, QC_Spec.UCL, QC_Spec.LCL, QC_Spec.ShowMode, QC_Spec.state, 
                            QC_Spec.createuser, QC_Spec.createts, QC_Spec.Digit, QC_Spec.UAXIS, QC_Spec.LAXIS, QC_Spec.STD_U, 
                            QC_Spec.STD_L, QC_Spec.showUSL, AnalyzeItem.ANI_ID, AnalyzeItem.ANI_NICKNAME
FROM              QC_CustProdSpec INNER JOIN
                            QC_Spec ON QC_CustProdSpec.SpecNo = QC_Spec.SpecNo AND 
                            QC_CustProdSpec.SpecVer = QC_Spec.SpecVer INNER JOIN
                            AnalyzeItem ON QC_Spec.ItemName = AnalyzeItem.ANI_FULLNAME
WHERE          (QC_CustProdSpec.CustNo = '".$cid."') AND (QC_CustProdSpec.ProdNo = '".$pid."') AND 
                            (AnalyzeItem.ANI_GROUPNAME = '".$ani_groupname."')";
//echo $query;
$result = mssql_query($query);
	while($row = mssql_fetch_array($result)){
		echo $itemname."==".$row['ANI_NICKNAME'];
    if ($itemname==$row['ANI_NICKNAME']){$as=" ";}else{$as="";}
	}
	echo $as;
}

function updateTM($table,$lot_no,$cust_no,$groupname,$prod_no,$samp_no,$pdd_chemiacl){
if (($groupname=='M13') || ($groupname=='M21') || ($groupname=='TT' || ($groupname=='TM'))){
	$tm_13=sprintf("%f",m13(get_table('M13',$pdd_chemiacl),$lot_no,$cust_no,$groupname,$prod_no,$samp_no));
    $tm_21=sprintf("%f",m21(get_table('M21',$pdd_chemiacl),$lot_no,$cust_no,$groupname,$prod_no,$samp_no));
	$tm_tt=sprintf("%f",tt(get_table('TT',$pdd_chemiacl),$lot_no,$cust_no,$groupname,$prod_no,$samp_no));
	$tm=$tm_tt+$tm_13+$tm_21;
	$tm1=sprintf("%1\$.3f",$tm);
	$tm2=$tm-$tm1;
	$tm=$tm2*1000000*0.005;
	$tb=$table;
	$pid=$prod_no;
	$sn=get_sn($table,$lot_no);
$_SESSION['tmp']="Final取得資料LOT：".$lot_no."  Total：".sprintf("%f",$tm);

///取得依賴資料
$query="SELECT          AnalyzeDesign.AND_APPLY_DATE, dbo.AnalyzeDesign.*
FROM              dbo.AnalyzeDesign
WHERE          (AnalyzeDesign.AND_LOT_NO = '".$lot_no."')";
//echo $query;
$result = mssql_query($query);
$numRows = mssql_num_rows($result);

//echo $query;
while ($row=mssql_fetch_array($result)){
	$dt=$row['AND_APPLY_DATE'];
	$person=$row['AND_PERSON'];
	$pname=get_uname($person);

}
$apd=ddd($dt)." 00:00:00";
$tb1=get_table('TM',$pdd_chemiacl);
$query="SELECT          ".$tb1.".*
FROM              ".$tb1." 
WHERE          (LotNo  = '".$lot_no."')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
$field= mssql_num_fields($result);
$sn=$numRows+1;

///取得客戶規格，如無客戶規格取社內規格
$ul=get_tm_ul('TM',$pid,$cust_no,$groupname);
if($tm>$ul){$status="0";}
else{$status="1";}

///新增TM資料
$table=get_table('TM',$pdd_chemiacl);
$query="INSERT INTO ".$table." ([TestDate], [CHK1], [CHK2], [LotNo], [SerialNo], [SampleNo], [TM], [Ok], [Tester], [Operator], [AnaManager], [AnalyzeTime]) VALUES ('".$apd."', '1', '1', '".$lot_no."', ".$sn.", '".$samp_no."', ".sprintf("%f",$tm).", '".$status."', 'System', '".$pname."', 'none', '".date("YmdHis")."') ";
$result = mssql_query($query);
if (!$result) {
    print("SQL statement failed with error:\n");
    print("   ".mssql_get_last_message()."\n");
	echo $ul."===".sprintf("%f",$tm);
  } else {

//header("Location:cal_prod.php?lot_no=".$_POST['lot_no']);  

}
echo "m13".$tm_13."</br>";
echo "m21".$tm_21."</br>";
echo "tt".$tm_tt."</br>";
echo $ul."===".sprintf("%f",$tm);
echo "TM:".sprintf("%f",$tm)."</br>";
echo "ul:".$ul."</br>";
echo $tm3."</br>";
}
}



function m13($table,$lot_no,$cust_no,$groupname,$prod_no,$samp_no){
$groupname='M13';
	$query="SELECT DISTINCT 
                            dbo.".$table.".SampleNo, dbo.".$table.".Na, dbo.".$table.".Mg, dbo.".$table.".Al, 
                            dbo.".$table.".K, dbo.".$table.".Ca, dbo.".$table.".Cr, dbo.".$table.".Mn, 
                            dbo.".$table.".Fe, dbo.".$table.".Ni, dbo.".$table.".Co, dbo.".$table.".Cu, 
                            dbo.".$table.".Zn, dbo.".$table.".Pb, dbo.".$table.".W, dbo.".$table.".LotNo, 
                            dbo.".$table.".AnalyzeTime, dbo.".$table.".Ok
FROM              dbo.".$table." INNER JOIN
                            dbo.Sample_All ON dbo.".$table.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$table.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$table.".LotNo = '".$lot_no."') AND (dbo.".$table.".Ok = '1') order by dbo.".$table.".AnalyzeTime";
//echo $query;
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
if ($numRows>0)
	{
	$tm_13=0;
	while($row = mssql_fetch_array($result))
		{
		$tm_13=sprintf("%f",$row['Na'])+sprintf("%f",$row['Mg'])+sprintf("%f",$row['Al'])+sprintf("%f",$row['K'])+sprintf("%f",$row['Ca'])+sprintf("%f",$row['Cr'])+sprintf("%f",$row['Mn'])+sprintf("%f",$row['Fe'])+sprintf("%f",$row['Ni'])+sprintf("%f",$row['Co'])+sprintf("%f",$row['Cu'])+sprintf("%f",$row['Zn'])+sprintf("%f",$row['Pb'])+sprintf("%f",$row['W']);
		$lotno=$row['LotNo'];
		$_SESSION['tmp']="取得資料LOT：".$row['LotNo']."  Total：".sprintf("%f",$tm_13);
		}
	}
elseif($numRows==0)
	{
				$query="SELECT DISTINCT 
                            dbo.".$table.".SampleNo, dbo.".$table.".Na, dbo.".$table.".Mg, dbo.".$table.".Al, 
                            dbo.".$table.".K, dbo.".$table.".Ca, dbo.".$table.".Cr, dbo.".$table.".Mn, 
                            dbo.".$table.".Fe, dbo.".$table.".Ni, dbo.".$table.".Co, dbo.".$table.".Cu, 
                            dbo.".$table.".Zn, dbo.".$table.".Pb, dbo.".$table.".W, dbo.".$table.".LotNo, 
                            dbo.".$table.".AnalyzeTime, dbo.".$table.".Ok, dbo.Sample_All.SMA_USER
FROM              dbo.".$table." INNER JOIN
                            dbo.Sample_All ON dbo.".$table.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$table.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$table.".Ok = '1') AND 
                            (dbo.Sample_All.SMA_USER = '".$cust_no."') order by dbo.".$table.".AnalyzeTime";

$result = mssql_query($query);
$numRows = mssql_num_rows($result);
if ($numRows>0)
	{
	$tm_13=0;
	while($row = mssql_fetch_array($result))
		{
		$tm_13=sprintf("%f",$row['Na'])+sprintf("%f",$row['Mg'])+sprintf("%f",$row['Al'])+sprintf("%f",$row['K'])+sprintf("%f",$row['Ca'])+sprintf("%f",$row['Cr'])+sprintf("%f",$row['Mn'])+sprintf("%f",$row['Fe'])+sprintf("%f",$row['Ni'])+sprintf("%f",$row['Co'])+sprintf("%f",$row['Cu'])+sprintf("%f",$row['Zn'])+sprintf("%f",$row['Pb'])+sprintf("%f",$row['W']);
		$lotno=$row['LotNo'];
		$_SESSION['tmp']="取得舊資料LOT：".$row['LotNo']."  Total：".sprintf("%f",$tm_13);
		}
	}
}
return $tm_13;
}


function m21($table,$lot_no,$cust_no,$groupname,$prod_no,$samp_no){
    $groupname='M21';
	$query="SELECT DISTINCT 
                            dbo.Sample_All.SMA_USER, dbo.".$table.".LotNo, dbo.".$table.".SampleNo, dbo.".$table.".Li, 
                            dbo.".$table.".Be, dbo.".$table.".Ti, dbo.".$table.".V, dbo.".$table.".Ga, 
                            dbo.".$table.".Ge, dbo.".$table.".[As], dbo.".$table.".Sr, dbo.".$table.".Zr, 
                            dbo.".$table.".Nb, dbo.".$table.".Mo, dbo.".$table.".Ag, dbo.".$table.".Cd, 
                            dbo.".$table.".Sn, dbo.".$table.".Sb, dbo.".$table.".Ba, dbo.".$table.".Ta, 
                            dbo.".$table.".Au, dbo.".$table.".Tl, dbo.".$table.".Bi, dbo.".$table.".Rb, 
                            dbo.".$table.".Cs, dbo.".$table.".Ce, dbo.".$table.".Ok, dbo.".$table.".AnalyzeTime
FROM              dbo.Sample_All INNER JOIN
                            dbo.".$table." ON dbo.Sample_All.SMA_LOT = dbo.".$table.".LotNo AND 
                            dbo.Sample_All.SMA_ID = dbo.".$table.".SampleNo
WHERE          (dbo.".$table.".LotNo = '".$lot_no."') AND (dbo.".$table.".Ok = '1') order by dbo.".$table.".AnalyzeTime";
//		echo $query;
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
if ($numRows>0)
	{
	$tm_21=0;
	while($row = mssql_fetch_array($result))
		{
		$tm_21=sprintf("%f",$row['Ag'])+sprintf("%f",$row['As'])+sprintf("%f",$row['Au'])+sprintf("%f",$row['Ba'])+sprintf("%f",$row['Be'])+sprintf("%f",$row['Bi'])+sprintf("%f",$row['Cd'])+sprintf("%f",$row['Ce'])+sprintf("%f",$row['Cs'])+sprintf("%f",$row['Ga'])+sprintf("%f",$row['Ge'])+sprintf("%f",$row['Li'])+sprintf("%f",$row['Mo'])+sprintf("%f",$row['Nb'])+sprintf("%f",$row['Rb'])+sprintf("%f",$row['Sb'])+sprintf("%f",$row['Sn'])+sprintf("%f",$row['Sr'])+sprintf("%f",$row['Ta'])+sprintf("%f",$row['Ti'])+sprintf("%f",$row['Tl'])+sprintf("%f",$row['V'])+sprintf("%f",$row['Zr']);
		$lotno=$row['LotNo'];
		$_SESSION['tmp']="取得資料LOT：".$row['LotNo']."  Total：".sprintf("%f",$tm_21);
		}
	}
elseif($numRows==0)
	{
				$query="SELECT DISTINCT 
                             dbo.Sample_All.SMA_USER, dbo.".$table.".LotNo, dbo.".$table.".SampleNo, dbo.".$table.".Li, 
                            dbo.".$table.".Be, dbo.".$table.".Ti, dbo.".$table.".V, dbo.".$table.".Ga, 
                            dbo.".$table.".Ge, dbo.".$table.".[As], dbo.".$table.".Sr, dbo.".$table.".Zr, 
                            dbo.".$table.".Nb, dbo.".$table.".Mo, dbo.".$table.".Ag, dbo.".$table.".Cd, 
                            dbo.".$table.".Sn, dbo.".$table.".Sb, dbo.".$table.".Ba, dbo.".$table.".Ta, 
                            dbo.".$table.".Au, dbo.".$table.".Tl, dbo.".$table.".Bi, dbo.".$table.".Rb, 
                            dbo.".$table.".Cs, dbo.".$table.".Ce, dbo.".$table.".Ok, dbo.".$table.".AnalyzeTime
FROM              dbo.Sample_All INNER JOIN
                            dbo.".$table." ON dbo.Sample_All.SMA_LOT = dbo.".$table.".LotNo AND 
                            dbo.Sample_All.SMA_ID = dbo.".$table.".SampleNo
WHERE          (dbo.".$table.".Ok = '1') AND 
                            (dbo.Sample_All.SMA_USER = '".$cust_no."') order by dbo.".$table.".AnalyzeTime";

$result = mssql_query($query);
$numRows = mssql_num_rows($result);
if ($numRows>0)
	{
	$tm_21=0;
	while($row = mssql_fetch_array($result))
		{
		$tm_21=sprintf("%f",$row['Ag'])+sprintf("%f",$row['As'])+sprintf("%f",$row['Au'])+sprintf("%f",$row['Ba'])+sprintf("%f",$row['Be'])+sprintf("%f",$row['Bi'])+sprintf("%f",$row['Cd'])+sprintf("%f",$row['Ce'])+sprintf("%f",$row['Cs'])+sprintf("%f",$row['Ga'])+sprintf("%f",$row['Ge'])+sprintf("%f",$row['Li'])+sprintf("%f",$row['Mo'])+sprintf("%f",$row['Nb'])+sprintf("%f",$row['Rb'])+sprintf("%f",$row['Sb'])+sprintf("%f",$row['Sn'])+sprintf("%f",$row['Sr'])+sprintf("%f",$row['Ta'])+sprintf("%f",$row['Ti'])+sprintf("%f",$row['Tl'])+sprintf("%f",$row['V'])+sprintf("%f",$row['Zr']);
		$lotno=$row['LotNo'];
		$_SESSION['tmp']="取得舊資料LOT：".$row['LotNo']."  Total：".sprintf("%f",$tm_21);
		}
	}
}
return $tm_21;
}


function tt($table,$lot_no,$cust_no,$groupname,$prod_no,$samp_no){
	$groupname='TT';
	$query="
SELECT DISTINCT 
                            dbo.Sample_All.SMA_USER, dbo.".$table.".[In], dbo.".$table.".Pt, dbo.".$table.".Se, 
                            dbo.".$table.".Hg, dbo.".$table.".P, dbo.".$table.".Pd, dbo.".$table.".Ok, dbo.".$table.".LotNo, 
                            dbo.".$table.".AnalyzeTime
FROM              dbo.Sample_All INNER JOIN
                            dbo.".$table." ON dbo.Sample_All.SMA_LOT = dbo.".$table.".LotNo AND 
                            dbo.Sample_All.SMA_ID = dbo.".$table.".SampleNo
WHERE          (dbo.".$table.".LotNo = '".$lot_no."') AND (dbo.".$table.".Ok = '1') order by dbo.".$table.".AnalyzeTime";

$result = mssql_query($query);
$numRows = mssql_num_rows($result);
if ($numRows>0)
	{
	$tm_tt=0;
	while($row = mssql_fetch_array($result))
		{
		$tm_tt=sprintf("%f",$row['Hg'])+sprintf("%f",$row['In'])+sprintf("%f",$row['P'])+sprintf("%f",$row['Pd'])+sprintf("%f",$row['Pt'])+sprintf("%f",$row['Se']);
		$lotno=$row['LotNo'];
		$_SESSION['tmp']="取得資料LOT：".$row['LotNo']."  Total：".sprintf("%f",$tm_tt);
		}
	}
elseif($numRows==0)
	{
				$query="SELECT DISTINCT 
                            dbo.Sample_All.SMA_USER, dbo.".$table.".[In], dbo.".$table.".Pt, dbo.".$table.".Se, 
                            dbo.".$table.".Hg, dbo.".$table.".P, dbo.".$table.".Pd, dbo.".$table.".Ok, dbo.".$table.".LotNo,
                            dbo.".$table.".AnalyzeTime
FROM              dbo.Sample_All INNER JOIN
                            dbo.".$table." ON dbo.Sample_All.SMA_LOT = dbo.".$table.".LotNo AND 
                            dbo.Sample_All.SMA_ID = dbo.".$table.".SampleNo

WHERE          (dbo.".$table.".Ok = '1') AND 
                            (dbo.Sample_All.SMA_USER = '".$cust_no."') order by dbo.".$table.".AnalyzeTime";

$result = mssql_query($query);
$numRows = mssql_num_rows($result);
if ($numRows>0)
	{
	$tm_tt=0;
	while($row = mssql_fetch_array($result))
		{
		$tm_tt=sprintf("%f",$row['Hg'])+sprintf("%f",$row['In'])+sprintf("%f",$row['P'])+sprintf("%f",$row['Pd'])+sprintf("%f",$row['Pt'])+sprintf("%f",$row['Se']);
		$lotno=$row['LotNo'];
		$_SESSION['tmp']="取得舊資料LOT：".$row['LotNo']."  Total：".sprintf("%f",$tm_tt);
		}
	}
}
return $tm_tt;
}


function get_table($groupname,$pdd_chemical){
include ('../connections/conn.php');
	$query="SELECT DISTINCT ELEMENT_FORM.ELF_FORM
FROM              ELEMENT_FORM INNER JOIN
                            AnalyzeItem ON ELEMENT_FORM.ELM_ID = AnalyzeItem.ANI_INDEX
WHERE          (ELEMENT_FORM.PDD_CHEMICAL = '".$pdd_chemical."') AND (AnalyzeItem.ANI_GROUPNAME = '".$groupname."')";
$result = mssql_query($query);
while($row = mssql_fetch_array($result)){
$table=$row['ELF_FORM'];
}
return $table;
}

function get_pdd_chemicla_from_pid($pid)
{
	$query="SELECT PDD_CHEMICAL FROM [dbo].[PRODUCT_DATA] WHERE [PDD_PROD_NO] = '".$pid."'";
	$result = mssql_query($query);
	while($row = mssql_fetch_array($result)){
	$pdd_chemical=$row['PDD_CHEMICAL'];
	}
	return $pdd_chemical;
}

function get_uname($uid){
$query="SELECT          dbo.EMPLOYEE_DATA.*, EMP_NO AS Expr1
FROM              dbo.EMPLOYEE_DATA
WHERE          (EMP_NO = '".$uid."')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
$table=$row['EMP_NAME'];
}
return $table;
}


function get_tm_ul($itemname,$pid,$cid,$ani_groupname){
	$query="SELECT DISTINCT 
                            QC_Spec.ID, QC_Spec.SpecNo, QC_Spec.SpecVer, QC_Spec.IX, QC_Spec.ItemName, QC_Spec.ItemUnit, 
                            QC_Spec.USL, QC_Spec.LSL, QC_Spec.DL, QC_Spec.UCL, QC_Spec.LCL, QC_Spec.ShowMode, QC_Spec.state, 
                            QC_Spec.createuser, QC_Spec.createts, QC_Spec.Digit, QC_Spec.UAXIS, QC_Spec.LAXIS, QC_Spec.STD_U, 
                            QC_Spec.STD_L, QC_Spec.showUSL, AnalyzeItem.ANI_ID, AnalyzeItem.ANI_NICKNAME
			FROM              QC_CustProdSpec INNER JOIN
                            QC_Spec ON QC_CustProdSpec.SpecNo = QC_Spec.SpecNo AND 
                            QC_CustProdSpec.SpecVer = QC_Spec.SpecVer INNER JOIN
                            AnalyzeItem ON QC_Spec.ItemName = AnalyzeItem.ANI_FULLNAME
			WHERE          (QC_CustProdSpec.CustNo = '".$cid."') AND (QC_CustProdSpec.ProdNo = '".$pid."') AND 
                            (AnalyzeItem.ANI_GROUPNAME = 'TM')";
//echo $query;
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
if ($numRows>0)
{
	while($row = mssql_fetch_array($result))
	{
	//	echo $row['ANI_NICKNAME'];
	if ($row['ShowMode']=="<DL")
		{
    	if ($itemname==$row['ANI_NICKNAME'])
			{
		return $row['USL'];
			}
		}
	
	}
}
else
	{
					$query="SELECT DISTINCT 
                            AnalyzeItem.ANI_DATAFIELD, AnalyzeItem.ANI_ORDER, COA_TYS.CTS_NO, COA_TYS.CTS_STYLE, 
                            COA_TYS.PDD_PROD_NO, COA_TYS.ELI_NAME, COA_TYS.CTS_DM_USL, COA_TYS.CTS_LY_USL, 
                            COA_TYS.CTS_DM_LSL, COA_TYS.CTS_LY_LSL, COA_TYS.CTS_DM_AGAIN_USL, COA_TYS.CTS_LY_AGAIN_USL, 
                            COA_TYS.CTS_DM_AGAIN_LSL, COA_TYS.CTS_LY_AGAIN_LSL, COA_TYS.CTS_DL, COA_TYS.CTS_UCL, 
                            COA_TYS.CTS_LCL, COA_TYS.ELI_UNIT
							FROM              COA_TYS INNER JOIN
                            AnalyzeItem ON COA_TYS.ELI_NAME = AnalyzeItem.ANI_ID
							WHERE          (AnalyzeItem.ANI_GROUPNAME = 'TM') AND (COA_TYS.PDD_PROD_NO = '".$pid."') AND (COA_TYS.ELI_UNIT <> '')";
//echo $query;			
			$result = mssql_query($query);
				while($row = mssql_fetch_array($result)){
					return $row['CTS_DL'];
					}
	}
}

function get_prod_name($pid){
	include("../connections/conn.php");
$query1="SELECT          PDD_PROD_NO, PDD_PROD_NAME  
FROM              dbo.PRODUCT_DATA
WHERE (PDD_PROD_NO='".$pid."')";	
	$result1= mssql_query($query1);
	$numRows1 = mssql_num_rows($result1);

	while($row1 = mssql_fetch_array($result1)){
		$pname=$row1['PDD_PROD_NAME'];
	}
	return $pname;
}

function analyzeitem1($str,$lot_no,$operator,$pdd_chemical,$pdd_prod_no){

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
while($row = mssql_fetch_array($result)){
$ok=getok($lot_no,$pdd_prod_no,$row['ANI_GROUPNAME'],$pdd_chemical);
echo '<a><font color="'.$ok.'">'.$row['ANI_GROUPNAME'].'  ,</font> </a>
';
//	echo iconv("big5","utf-8",$row['ANI_GROUPNAME']).', ';
}
echo '</td>';
}
function showcust_spec1($cid,$pid,$aid){
	include("../connections/conn.php");
	$query="SELECT DISTINCT 
                          QC_CustProdSpec.ProdNo, QC_Spec.ID, QC_Spec.SpecNo, QC_Spec.SpecVer, 
                          QC_Spec.IX, QC_Spec.ItemName, QC_Spec.ItemUnit, QC_Spec.USL, 
                          QC_Spec.LSL, QC_Spec.DL, QC_Spec.UCL, QC_Spec.LCL, QC_Spec.ShowMode, 
                          QC_Spec.state, QC_Spec.createuser, QC_Spec.createts, QC_Spec.Digit, 
                          QC_Spec.UAXIS, QC_Spec.LAXIS, QC_Spec.STD_U, QC_Spec.STD_L, 
                          QC_Spec.showUSL, AnalyzeItem.ANI_FULLNAME, 
                          AnalyzeItem.ANI_GROUPNAME, dbo.AnalyzeItem.ANI_ID, 
                          dbo.AnalyzeItem.ANI_UNIT, dbo.AnalyzeItem.ANI_GROUPNAME AS Expr1
FROM             dbo.AnalyzeItem INNER JOIN
                          dbo.QC_CustProdSpec INNER JOIN
                          dbo.QC_Spec ON QC_CustProdSpec.SpecNo = QC_Spec.SpecNo AND 
                          QC_CustProdSpec.SpecVer = QC_Spec.SpecVer ON 
                          AnalyzeItem.ANI_FULLNAME = QC_Spec.ItemName
			WHERE          (QC_CustProdSpec.CustNo = '".$cid."') AND (QC_CustProdSpec.ProdNo = '".$pid."') AND (dbo.AnalyzeItem.ANI_INDEX = '".$aid."') order by dbo.AnalyzeItem.ANI_GROUPNAME";
{$_SESSION['tmpq']=$query;}
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
if(($numRows<1) and ($aid=='27')){
		$query="SELECT DISTINCT 
                          QC_CustProdSpec.ProdNo, QC_Spec.ID, QC_Spec.SpecNo, QC_Spec.SpecVer, 
                          QC_Spec.IX, QC_Spec.ItemName, QC_Spec.ItemUnit, QC_Spec.USL, 
                          QC_Spec.LSL, QC_Spec.DL, QC_Spec.UCL, QC_Spec.LCL, QC_Spec.ShowMode, 
                          QC_Spec.state, QC_Spec.createuser, QC_Spec.createts, QC_Spec.Digit, 
                          QC_Spec.UAXIS, QC_Spec.LAXIS, QC_Spec.STD_U, QC_Spec.STD_L, 
                          QC_Spec.showUSL, dbo.QC_Item.ItemNickName
FROM             dbo.QC_CustProdSpec INNER JOIN
                          dbo.QC_Spec ON QC_CustProdSpec.SpecNo = QC_Spec.SpecNo AND 
                          QC_CustProdSpec.SpecVer = QC_Spec.SpecVer INNER JOIN
                          dbo.QC_Item ON dbo.QC_Spec.ItemName = dbo.QC_Item.ItemName
WHERE         (QC_CustProdSpec.CustNo = '".$cid."') AND 
                          (QC_CustProdSpec.ProdNo = '".$pid."') AND (dbo.QC_Item.ItemNickName = 'RE')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
	}
if(($numRows<1) and ($aid<>'27')){
	$query="SELECT DISTINCT ANI_FULLNAME, ANI_GROUPNAME, ANI_ID, ANI_UNIT, ANI_GROUPNAME AS Expr1
			FROM              AnalyzeItem
			WHERE          (ANI_INDEX = '".$aid."')
			ORDER BY   ANI_GROUPNAME";	
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
}
$_SESSION['tmpq']=$query;
if ($numRows>0){
while($row = mssql_fetch_array($result)){
$unit=$row['ItemUnit'];
$itm_name=get_ani_itemname($aid);
$showmode=$row['ShowMode'];
$dl=$row['DL'];
$oocu=$row['STD_U'];
$oocl=$row['STD_L'];
$usl=$row['USL'];
$lsl=$row['LSL'];
$dl=$row['DL'];
$spec=$row['SpecNo'];
$ver=$row['SpecVer'];
$fullname=$row['ANI_FULLNAME'];

if ($row['ShowMode']=='<DL')
{
	$showmode='<DL';
	$spc="<".$usl;
}
if ($row['ShowMode']=='<USL')
{
	$showmode='<USL';
	$spc="<".$usl;
}
if (($row['ShowMode']=='VALUE') or ($row['ShowMode']=='Average'))
{
	$showmode='VALUE';
	$spc=$usl." ~ ".$lsl;
}

else {$showmode=$row['ShowMode'];}
// echo $query."</br>";
}
if($aid=='32'){$unit="---";}
return array($itm_name,$spc,$unit,$dl,$fullname,$usl,$lsl,$showmode,$oocu,$oocl);
}
else{
	$itm_name=get_ani_itemname($aid);
	return array($itm_name,"none","none",No_DL,"none");
}
}

function get_sn($table,$lot_no){
	//echo $table.$lot_no;
$query="SELECT          ".$table.".*
FROM              ".$table." 
WHERE          (LotNo  = '".$lot_no."')";
//	echo $query;
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
return $numRows+1;
}

function get_cust_name($cid){
	
		$query="SELECT          CTD_CUST_NAME 
FROM              dbo.CUSTOMER_DATA
WHERE          (CTD_CUST_NO = '".$cid."')";
$result = mssql_query($query);
while($row = mssql_fetch_array($result)){
	$uname=$row['CTD_CUST_NAME'];}
	return $uname;
}
function get_cust_sname($cid){
	
		$query="SELECT          CTD_CUST_SHORT_NAME 
FROM              dbo.CUSTOMER_DATA
WHERE          (CTD_CUST_NO = '".$cid."')";
$result = mssql_query($query);
while($row = mssql_fetch_array($result)){
	$uname=$row['CTD_CUST_SHORT_NAME'];}
	return $uname;
}

function get_air_start($lot_no){
		$query="SELECT          LFC_AIR_SEAL_START, LFC_AIR_SEAL, LFC_AIR_SEAL_END
FROM              dbo.LORRY_FILL_CHECK
WHERE          (FDM_LOT_NO = '".$lot_no."')";

$result = mssql_query($query);
while($row = mssql_fetch_array($result)){
	$LFC_AIR_SEAL_START=$row['LFC_AIR_SEAL_START'];
	$LFC_AIR_SEAL_END=$row['LFC_AIR_SEAL_END'];
	}
	return array ($LFC_AIR_SEAL_START,$LFC_AIR_SEAL_END);
}

function get_report_value($table,$lot_no,$item,$spc,$dl,$usl,$lsl,$showmode,$stdu,$stdl)
{
//	echo "TABLE:".$table."</br>";
if($item=='Na'){
$_SESSION['showmod']=$showmode;}
$query="SELECT          LotNo, SampleNo, Ok, Tester, Operator, AnalyzeTime,[".$item."]
FROM              dbo.".$table."
WHERE          (LotNo = '".$lot_no."') order by AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $table." : ".$query."</br>";
if ($numRows>0){
while($row = mssql_fetch_array($result))
{
	$report=$row[$item];
	$SampleNo=$row['SampleNo'];
	$Tester=$row['Tester'];
	$AnalyzeTime=$row['AnalyzeTime'];
	$Ok=$row['Ok'];
	$AnalyzeTime=substr($AnalyzeTime,0,-6);
	$AnalyzeTime=ddd($AnalyzeTime);
}

if(($showmode=='VALUE') or ($showmode=='AVERAGE') and ($usl=='') and ($lsl<>''))
{
		if($report>$lsl)
		{
			$ok="合";
		}
		else
		{
			$ok="否";
		}
	
}
elseif(($showmode=='VALUE') or ($showmode=='AVERAGE'))
{
	if(($report>=$lsl) and ($report<=$usl))
	{
		$ok="合";
	}
	else
	{
		$ok="否";
	}
}

if(($showmode=='<DL') or ($showmode=='<USL'))
{
	if($report<=$stdu)
	{
		$ok="合";
	}
	else
	{
		$ok="否";
	}
}

if(($dl=='') or ($dl==NULL))
{
	$ok="";
}


return array(trim($report),trim($SampleNo),trim($Tester),trim($AnalyzeTime),trim($ok));
}

else{
	return array(none,未檢驗,none,none,none);
	}

}

function rm_dir($path){
  if( is_dir($path) ){
    $handle = opendir($path); 
    while($file = readdir($handle)){ 
      if( $file != '.' && $file != '..' ){ 
        $file = "$path/$file"; 
        if( is_dir($file) ){ 
          rm_dir($file); 
        }else{ 
          unlink($file); 
        } 
      } 
    } 
  } 
}  

function get_ani_itemname($aid){
$query="SELECT          ANI_ID, ANI_INDEX, ANI_FULLNAME
FROM              dbo.AnalyzeItem
WHERE          (ANI_INDEX = ".$aid.")";

$result = mssql_query($query);
while($row = mssql_fetch_array($result))
{
	return $row['ANI_FULLNAME'];
}
}

function get_ISO_no($prodtype,$pid){
$query="SELECT          PDD_CHEMICAL, PDD_CLASS, AIL_ISO_NO
FROM              dbo.ACCOUNT_ISO_LIST
WHERE          (PDD_CHEMICAL = '".$pid."') and (PDD_CLASS<>'".$prodtype."')
ORDER BY   PDD_CHEMICAL";
$result = mssql_query($query);
$numrows=mssql_num_rows($result);
if ($numrows>0){
while($row = mssql_fetch_array($result))
{
	$isono=$row['AIL_ISO_NO'];
}
return $isono;
}
else {return "none";}
}

function lotnoTotype($lid){
$query="SELECT          AND_NEED_NO, AND_LOT_NO
FROM              AnalyzeDesign
WHERE          (AND_LOT_NO = '".$lid."')";	

$result = mssql_query($query);
while($row = mssql_fetch_array($result))
{
	$ino=$row['AND_NEED_NO'];
}
if ($ino<151){$isono="製品";}
if ($ino<250 and 149<$ino){$isono="解析";}
if ($ino<350 and 249<$ino){$isono="受入";}
if (349<$ino){$isono="其他";}
return $isono;

}


function get_sample_drum_no($smpno,$lid){
$query="SELECT          SMA_DRUMNO
FROM              dbo.Sample_All
WHERE          (SMA_LOT = '".$lid."') AND (SMA_ID = '".$smpno."')";
$result = mssql_query($query);
while($row = mssql_fetch_array($result))
{
	$ino=$row['SMA_DRUMNO'];
}
return $ino;
}

function validmon($cid,$pid){
$query="SELECT          CUSTOMER_PRODUCTS.CTP_VALID_MON
FROM              CUSTOMER_PRODUCTS
WHERE          (PDD_PROD_NO = '".$pid."') AND (CTD_CUST_NO = '".$cid."')";
$result = mssql_query($query);
while($row = mssql_fetch_array($result))
{
	$ino=$row['CTP_VALID_MON'];
}
return $ino;
}

class tbl
{
public $pdd_chemical;
public $aid;
public $dfd;
public $tbno;

function tb()
	{
	include("../connections/conn.php");
	$query="SELECT DISTINCT 
                            ELEMENT_FORM.ELF_FORM, ELEMENT_ITEM.ELM_ID, AnalyzeItem.ANI_NICKNAME, 
                            AnalyzeItem.ANI_DATAFIELD
			FROM              ELEMENT_FORM INNER JOIN
                            ELEMENT_ITEM ON ELEMENT_FORM.ELM_ID = ELEMENT_ITEM.ELM_ID INNER JOIN
                            AnalyzeItem ON ELEMENT_FORM.ELM_ID = AnalyzeItem.ANI_INDEX
			WHERE          (ELEMENT_FORM.PDD_CHEMICAL = '".$this->pdd_chemical."') AND (AnalyzeItem.ANI_DATAFIELD = '".$this->dfd."')";
			$result = mssql_query($query);
			$numrows=mssql_num_rows($result);
			if ($numrows>0)
			{
			while($row = mssql_fetch_array($result))
				{
					$this->tbno=$row['ELF_FORM'];
				}
			}
			else{
				$this->tbno="";
			}
	}
}


class sag
{
	public $sagno;
	public $cdate;
	public $uname;
	public $sai_sn;
	public $emp_no;
	public $ok;
	public $sai_time;
	public $sai_memo;
	public $pono;
	public $finished_memo;
	public $finished_time;
	public $sai_num;
	public $pidtype;
	public $ctbno;
	public $autno;
	public $createtime;
	public $uid;
	function cbt_no(){
		if($this->pidtype=='A'){$this->ctbno='1-02';}
		if($this->pidtype=='B'){$this->ctbno='1-03';}
	}
	
	function sign_in()
	{
		include("../connections/conn.php");
		$query="SELECT          SIGN_AGREE.*, EMPLOYEE_DATA.EMP_NAME
				FROM              SIGN_AGREE INNER JOIN
                EMPLOYEE_DATA ON SIGN_AGREE.SAG_CREATE_MAN = EMPLOYEE_DATA.EMP_NO
				WHERE          (SIGN_AGREE.SAG_NO = ".$this->sagno.")
				";
		$result = mssql_query($query);
		while($row = mssql_fetch_array($result))
		{
			$cdate=$this->cdate=$row['SAG_CREATE_TIME'];
			$uname=$this->uname=$row['EMP_NAME'];
		}
	}
	
	function read_sig()
	{
		$query="SELECT          dbo.SIGN_AGREE_ITEM.SAG_NO, dbo.SIGN_AGREE_ITEM.SAI_SERIAL_NO, dbo.SIGN_AGREE_ITEM.AUT_NO, 
                            dbo.SIGN_AGREE_ITEM.SAI_OK_NG, dbo.SIGN_AGREE_ITEM.SAI_TIME, dbo.SIGN_AGREE_ITEM.SAI_MEMO, 
                            dbo.EMPLOYEE_DATA.EMP_NAME, dbo.SIGN_AGREE_ITEM.EMP_NO
				FROM              dbo.SIGN_AGREE_ITEM INNER JOIN
                            dbo.EMPLOYEE_DATA ON dbo.SIGN_AGREE_ITEM.EMP_NO = dbo.EMPLOYEE_DATA.EMP_NO
				WHERE          (SAG_NO = ".$this->sagno.") and (SAI_SERIAL_NO=".$this->sai_sn.")";
		$result = mssql_query($query);
		$numrows=mssql_num_rows($result);
		$this->sai_num=$numrows;
		if($numrows>0){
		while($row = mssql_fetch_array($result))
		{
			$this->ok=$row['SAI_OK_NG'];
			$this->sai_memo=$row['SAI_MEMO'];
			$this->sai_time=$row['SAI_TIME'];
			$this->uname=$row['EMP_NAME'];
		}
		}
		else {
			$this->ok="";
			$this->sai_memo="";
			$this->sai_time="";
			$this->uname="";
		}
		
		$query="SELECT          dbo.IN_PLAN.IPA_PO_NO AS Expr1, dbo.EMPLOYEE_DATA.EMP_NAME, dbo.SIGN_AGREE.*
				FROM              dbo.SIGN_AGREE INNER JOIN
                            dbo.SIGN_AGREE_ITEM ON dbo.SIGN_AGREE.SAG_NO = dbo.SIGN_AGREE_ITEM.SAG_NO INNER JOIN
                            dbo.IN_PLAN ON dbo.SIGN_AGREE.SAG_NO = dbo.IN_PLAN.SAG_NO INNER JOIN
                            dbo.EMPLOYEE_DATA ON dbo.SIGN_AGREE_ITEM.EMP_NO = dbo.EMPLOYEE_DATA.EMP_NO
				WHERE          (dbo.IN_PLAN.IPA_PO_NO = '".$_GET['po_no']."')";
		$result = mssql_query($query);
		$numrows=mssql_num_rows($result);
		if ($numrows>0)
		{
			while($row = mssql_fetch_array($result))
			{
				$this->finished_memo=$row['SAG_FINISHED_MEMO'];
				$this->finished_time=$row['SAG_FINISHED_TIME'];
			}
		}
	}
	
	function num_sg()
	{
		$query="SELECT          dbo.SIGN_AGREE.SAG_NO AS Expr1, dbo.SIGN_AGREE.CBT_NO, dbo.SIGN_AGREE.SAG_CREATE_TIME, 
                            dbo.SIGN_AGREE.SAG_CREATE_MAN, dbo.SIGN_AGREE.SAG_FINISHED_MEMO, 
                            dbo.SIGN_AGREE.SAG_FINISHED_TIME, dbo.SIGN_AGREE.SAG_TERMINATE_TIME, 
                            dbo.SIGN_AGREE.SAG_COMFIRM_COUNT, dbo.IN_PLAN.IPA_PO_NO, dbo.SIGN_AGREE_ITEM.SAI_TIME
FROM              dbo.SIGN_AGREE_ITEM INNER JOIN
                            dbo.SIGN_AGREE ON dbo.SIGN_AGREE_ITEM.SAG_NO = dbo.SIGN_AGREE.SAG_NO INNER JOIN
                            dbo.IN_PLAN ON dbo.SIGN_AGREE_ITEM.SAG_NO = dbo.IN_PLAN.SAG_NO
WHERE          (dbo.IN_PLAN.IPA_PO_NO = '".$_GET['po_no']."') AND (dbo.SIGN_AGREE_ITEM.SAI_TIME <> '')";
		$result = mssql_query($query);
		$numrows=mssql_num_rows($result);
		$this->sai_num=$numrows;
		if($numrows>0){
			while($row = mssql_fetch_array($result))
			{
				$this->finished_time=$row['SAG_FINISHED_TIME'];				
			}
		}	
	}
	
	function write_sign_agree_item(){
		$query="INSERT INTO dbo.SIGN_AGREE_ITEM
                            (SAG_NO, SAI_SERIAL_NO, AUT_NO, EMP_NO, SAI_OK_NG, SAI_TIME, SAI_MEMO)
			VALUES          ('".$this->sagno."','".$this->sai_sn."','".$this->autno."','','','','')";

			$result = mssql_query($query);
			if (!$result) {
  		  		fun_alert("write to SIGN_AGREE_ITEM 失敗");
  			}
	}
		function write_sign_agree(){
		$this->createtime=date("YmdHis");
		$query="INSERT INTO dbo.SIGN_AGREE
                            (CBT_NO, SAG_CREATE_TIME, SAG_CREATE_MAN, SAG_FINISHED_TIME, SAG_FINISHED_MEMO, 
                            SAG_TERMINATE_TIME,SAG_COMFIRM_COUNT)
				VALUES          ('".$this->ctbno."','".$this->createtime."','".$this->uid."',NULL,NULL,NULL,3)";
			$result = mssql_query($query);
			if (!$result) {
  		  		fun_alert("write to SIGN_AGREE 失敗");
  			}
		$query="SELECT          SAG_NO
				FROM              dbo.SIGN_AGREE
				WHERE          (SAG_CREATE_TIME = '".$this->createtime."') AND (SAG_CREATE_MAN = '".$this->uid."')";
				$result = mssql_query($query);
				while($row = mssql_fetch_array($result))
			  	{
				$this->sagno=$row['SAG_NO'];
				}
	}

}

class timech
{
public $str_sec;
public $str_YMD_Hs;
function sectoymdhs1(){
	//day-style  201509020102=>09/02/2015
	$this->str_YMD_Hs=substr($this->str_sec,0,+4)."/".substr($this->str_sec,4,+2)."/".substr($this->str_sec,6,+2)." ".substr($this->str_sec,8,+2).":".substr($this->str_sec,10,+2);
}
}

class pdd_type
{
	public $pono;
	public $ab;
	public $pddtype;
	public $pid;
	
	function get_ab(){
		$query="SELECT      dbo.PRODUCT_DATA.PDD_CLASS
				FROM              dbo.IN_PLAN_PRODUCT INNER JOIN
                            dbo.PRODUCT_DATA ON dbo.IN_PLAN_PRODUCT.PDD_PROD_NO = dbo.PRODUCT_DATA.PDD_PROD_NO
				WHERE          (dbo.PRODUCT_DATA.PDD_PROD_NO = '".$this->pid."')";
				$result = mssql_query($query);
				while($row = mssql_fetch_array($result)){
					$this->ab=$row['PDD_CLASS'];
				}
				if($this->ab=="原料"){$this->ab="A";}
				if($this->ab=="商品"){$this->ab="B";}
	}
}

class sag_no{
public $no;
public $cbt_name;
public $cbt_no;
function now(){
	$query="SELECT          TOP (1) SAG_NO
FROM              dbo.SIGN_AGREE
ORDER BY   SAG_NO DESC";
$result = mssql_query($query);
				while($row = mssql_fetch_array($result)){
					$this->no=($row['SAG_NO']+1);
				}
	}
}

class posn{
public $pono;
public $sn;
function getsn(){
	$query="SELECT          IAP_SERIAL_NO
FROM              dbo.IN_PLAN_PRODUCT
WHERE          (IPA_PO_NO = '".$this->pono."')";
		$result = mssql_query($query);
		$numrows=mssql_num_rows($result);
		$this->sn=$numrows+1;
	}
}

class get_from_lot_no{
public $lid,$and_before,$and_total;
public $cid,$unit;
public $pid,$numrows;
public $cname,$csname;
public $pdd_chemical,$pdd_prod_short_name;
public $needno,$and_coa_fst;
public $prodstyle;
public $qty,$items;
public $pdd_type;
public $qty_drum,$note,$note2;
public $spc,$cancel,$cid2;
public $tmp,$smptime,$outdate,$testdate;


function ani_group(){
	$str=$this->items.",";
$aa=explode(',',$str,-1);
	$query="SELECT DISTINCT ANI_GROUPNAME
FROM              AnalyzeItem
WHERE          (ANI_INDEX <>'') and ";
for($i=0;$i<count($aa);$i++){
if ($i<(count($aa)-1)){
	$query.="(ANI_INDEX =".$aa[$i].") OR ";}
else {$query.="(ANI_INDEX =".$aa[$i].")";}	
}
$result=mssql_query($query);
while($row=mssql_fetch_array($result)){
	$groups=$groups.$row['ANI_GROUPNAME']." , ";
}
return $groups;
}

function ani(){
	include("../connections/conn.php");
	$query="SELECT         dbo.AnalyzeDesign.*, dbo.PRODUCT_DATA.*
			FROM             dbo.AnalyzeDesign inner join dbo.PRODUCT_DATA on dbo.AnalyzeDesign.AND_GOODS = dbo.PRODUCT_DATA.PDD_PROD_NO 
			WHERE         (AND_LOT_NO = '".$this->lid."')";	
	$result=mssql_query($query);
	$this->numrows=mssql_num_rows($result);
	while($row=mssql_fetch_array($result)){
		$this->pid=$row['AND_GOODS'];
		$this->needno=$row['AND_NEED_NO'];
		$this->smptime=$row['AND_SMP_DATETIME'];
		$this->smpnumber=$row['AND_GET_QTY'];
		$this->cancel=$row['AND_CANCEL'];
		$this->items=$row['AND_ITEM'];
		$this->testdate=$row['AND_APPLY_DATE'];
		$this->note=$row['AND_NOTE'];
		$this->note2=$row['AND_NOTE2'];
		$this->pdd_type=$row['PDD_TYPE'];
		$this->and_before=$row['AND_BEFORE'];
		$this->and_total=$row['AND_TOTAL'];
		$this->and_coa_fst=$row['AND_COA_FST'];
		$this->pdd_prod_short_name=$row['PDD_PROD_SHORT_NAME'];
		$this->unit=$row['PDD_UNIT'];
	}
}

function cid(){
	include("../connections/conn.php");
	$query="SELECT         dbo.AnalyzeDesign.*, dbo.PRODUCT_DATA.PDD_TYPE
FROM             dbo.AnalyzeDesign INNER JOIN
                          dbo.PRODUCT_DATA ON 
                          dbo.AnalyzeDesign.AND_GOODS = dbo.PRODUCT_DATA.PDD_PROD_NO
WHERE         (dbo.AnalyzeDesign.AND_LOT_NO = '".$this->lid."')";
$result = mssql_query($query);
while($row = mssql_fetch_array($result)){
	$this->needno=$row['AND_NEED_NO'];
	if(($this->needno>150) and ($this->needno<270)){$this->cid='C00001';$this->cname='TYS Internal';}
	if($this->needno>=270){$this->cid='C00001';$this->cname='TYS Internal';}
	}

	$query="SELECT         dbo.FILLPLAN_OUT_DECIDE.*,   dbo.CUSTOMER_DATA.CTD_CUST_NAME, dbo.CUSTOMER_DATA.CTD_CUST_SHORT_NAME,
                                                   dbo.PRODUCT_DATA.*
FROM             dbo.FILLPLAN_OUT_DECIDE INNER JOIN
                          dbo.PRODUCT_DATA ON 
                          dbo.FILLPLAN_OUT_DECIDE.PDD_PROD_NO = dbo.PRODUCT_DATA.PDD_PROD_NO
                           INNER JOIN
                          dbo.CUSTOMER_DATA ON 
                          dbo.FILLPLAN_OUT_DECIDE.CTD_CUST_NO = dbo.CUSTOMER_DATA.CTD_CUST_NO
WHERE          (FILLPLAN_OUT_DECIDE.FDM_LOT_NO = '".$this->lid."')";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	if($numRows>0){
	while($row = mssql_fetch_array($result))
	{	
		$this->cid=$row['CTD_CUST_NO'];
		$this->cname=$row['CTD_CUST_NAME'];
		$this->csname=$row['CTD_CUST_SHORT_NAME'];
		$this->pdd_chemical=$row['PDD_CHEMICAL'];
		$this->prodstyle=$row['PDD_STYLE'];
		$this->qty=$row['FDM_QTY'];
		$this->qty_drum=$row['FDM_QTY_DRUM'];
		$this->pid=$row['PDD_PROD_NO'];
		$this->spc=$row['FDM_SPECIFIC'];
		$this->pdd_type=$row['PDD_TYPE'];
		$this->smptime=$row['AND_OUT_DATETIME'];
		$this->outdate=$row['FDM_OUT_DATE'];
	}
	}
	}
function cid2()
{
	include("../connections/conn.php");
	$query="SELECT          FILLPLAN_DRUM_CUSTOMER.CTD_CUST_NO, CUSTOMER_DATA.CTD_CUST_SHORT_NAME, FDMC_DRUM_COUNT
			FROM              FILLPLAN_DRUM_CUSTOMER INNER JOIN
										CUSTOMER_DATA ON FILLPLAN_DRUM_CUSTOMER.CTD_CUST_NO = CUSTOMER_DATA.CTD_CUST_NO
			WHERE          (FDM_LOT_NO = '".$this->lid."') ";	
	$result = mssql_query($query);
	while($row = mssql_fetch_array($result)){
		$this->cid2.=$row['CTD_CUST_SHORT_NAME']."(".$row['FDMC_DRUM_COUNT']."),";
	}
	$this->cid2=substr($this->cid2,0,-1);
}

function ned(){
	include("../connections/conn.php");
	$query="SELECT          AND_NEED_NO
			FROM              AnalyzeDesign
			WHERE          (AND_LOT_NO = '".$this->lid."')";
	$result = mssql_query($query);
	while($row = mssql_fetch_array($result)){
		$this->needno=$row['AND_NEED_NO'];
	}
}
}
function refresh($refresht){
	$ulink=$_SERVER['REQUEST_URI'];
	echo '<script>document.location.href="'.$ulink.'";</script>';		

}

function conv($str){
	$str=iconv("big5","utf-8",$str);
	return $str;
}
function get_table_from_aniindex($ani_index,$pdd_chemical){
	$query="SELECT          dbo.ELEMENT_FORM.ELF_FORM
FROM              dbo.ELEMENT_FORM INNER JOIN
                            dbo.AnalyzeItem ON dbo.ELEMENT_FORM.ELM_ID = dbo.AnalyzeItem.ANI_INDEX
WHERE          (dbo.AnalyzeItem.ANI_INDEX = ".$ani_index.") AND (dbo.ELEMENT_FORM.PDD_CHEMICAL = '".$pdd_chemical."')";
$result = mssql_query($query);
	while($row = mssql_fetch_array($result)){
		$s=$row['ELF_FORM'];
	}
	return $s;
}

function literkg1($pid){
	$query="SELECT          PDD_PROD_NO, PDD_LITER_KG
FROM              PRODUCT_DATA
WHERE          (PDD_PROD_NO = '".$pid."')";	
$result = mssql_query($query);
	while($row = mssql_fetch_array($result)){
		$s=$row['PDD_LITER_KG'];
	}
	return $s;
}

function checkanalizestatus($lot){
	include("../connections/conn.php");
	$query1="SELECT          AND_LOT_NO
	FROM              dbo.AnalyzeDesign
	WHERE          (AND_LOT_NO = '".$lot."')";	

	$result1 = mssql_query($query1);
	$numRows1 = mssql_num_rows($result1);
	return $numRows1;	
}

function exmonth($month){
	switch($month)
	{
		case '01':
		return "A";
		break;
		case '02':
		return "B";
		break;
		case '03':
		return "C";
		break;
		case '04':
		return "D";
		break;
		case '05':
		return "E";
		break;
		case '06':
		return "F";
		break;		
		case '07':
		return "G";
		break;
		
		case '08':
		return "H";
		break;
		
		case '09':
		return "I";
		break;
		
		case '10':
		return "X";
		break;
		case '11':
		return "Y";
		break;
		case '12':
		return "Z";
		break;
	}
}

function space($times){
for($i=0;$i<$times;$i++)
{
	echo "&nbsp;";
}
}

function ifregularanalyze($aid,$pid){
include("../connections/conn.php");
$query="SELECT          dbo.RegularAnalyzeItem.*
FROM              dbo.RegularAnalyzeItem
WHERE          (PROD_NO = '".$pid."') AND (ANI_ID = '".$aid."')";	
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
if($numRows>0){return 'checked="checked"';}
else {return '';}
}

function differencetoolarge($lot_no,$anigroup){
	
}

function keep_session(){
session_start();
$uid=$_SESSION['MM_Username'];
	$uname=$_SESSION['uname'];
	$lid=$_SESSION['lid'];	
	$rdir=$_SESSION['redir'];
	$lasturl=$_SESSION['lasturl'];
	$lasturl1=$_SESSION['lasturl1'];
	$datepicker1=$_SESSION['datepicker1'];
	$datepicker2=$_SESSION['datepicker2'];
	$cid=$_SESSION['cid'];
	$cname=$_SESSION['cname'];
	$pid=$_SESSION['pid'];
	$pname=$_SESSION['pname'];
	$item=$_SESSION['item'];
	$ym=$_SESSION['ym'];
	session_destroy();
	session_start();
	$_SESSION['uid']=$uid;
	$_SESSION['MM_Username']=$uid;
	$_SESSION['uname']=$uname;
	$_SESSION['lid']=$lid;
	$_SESSION['redir']=$rdir;	
	$_SESSION['lasturl']=$lasturl;
	$_SESSION['lasturl1']=$lasturl1;
	$_SESSION['datepicker1']=$datepicker1;
	$_SESSION['datepicker2']=$datepicker2;
	$_SESSION['cid']=$cid;
	$_SESSION['cname']=$cname;
	$_SESSION['pid']=$pid;
	$_SESSION['pname']=$pname;
	$_SESSION['item']=$item;
	$_SESSION['ym']=$ym;
}

function analyzeItems($index)
{
	$query="SELECT         ANI_INDEX, ANI_ID, ANI_NICKNAME, ANI_FULLNAME, ANI_UNIT, ANI_ANR_ID, 
                          ANI_GROUPNAME, ANI_DATAFIELD, ANI_ORDER
			FROM             dbo.AnalyzeItem
			WHERE         (ANI_INDEX = ".$index.")";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result))
	{
		$ani_id=$row['ANI_ID'];
		$ani_nick=$row['ANI_NICKNAME'];
		$ani_full=$row['ANI_FULLNAME'];
		$ani_igroup=$row['ANI_GROUPNAME'];
	}
	return array($ani_id,$ani_nick,$ani_full,$ani_igroup);
}

function ana_id_to_nick($str)
{
	$aa=explode(",",$str);
	$num=count($aa);
	for($i=0;$i<$num;$i++)
	{
		list($ani_id,$ani_nick,$ani_full,$ani_igroup)=analyzeItems($aa[$i]);
		$nick.=$ani_full."(".$ani_nick.") ,";
	}
	
	return substr($nick,0,-1);
}

function ana_cid_to_nick($str)
{
	$aa=explode(";",$str);
	$num=count($aa);
	for($i=0;$i<$num;$i++)
	{
		$ani_nick=get_cust_name($aa[$i]);
		$nick.="(".$ani_nick.") ,";
	}
	
	return substr($nick,0,-1);
}

function auth($aid,$emp_aut_group)
{
	$chk=0;
	if($emp_aut_group=='')
	{
		$query="SELECT          EMPLOYEE_AUTHORITY.*
				FROM              EMPLOYEE_AUTHORITY
				WHERE          (EMP_NO = '".$_SESSION['uid']."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result))
		{
			$emp_aut_group=$row['AUT_GROUP'];	
		}
	}
	
	$query="select * from CAPABILITY_DATA where (CBT_NO='".$aid."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result))
	{
		$str=$row['CBT_KEYIN'];	
	}
	$aa=explode(';',$str);
	for($i=0;$i<count($aa);$i++){
		$aa[$i].=";";
		if(preg_match("/$aa[$i]/i", $emp_aut_group)){$chk=1;}
	}
if ($chk==0) {
	my_msg(" 此帳號沒有權限 !! ","/main.php");
	}
}

function list_ani_items_reg($pid)
{
	$query="SELECT          RegularAnalyzeItem.*, AnalyzeItem.ANI_INDEX
			FROM              RegularAnalyzeItem INNER JOIN
                            AnalyzeItem ON RegularAnalyzeItem.ANI_ID = AnalyzeItem.ANI_ID
			WHERE          (RegularAnalyzeItem.PROD_NO = '".$pid."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){
		$items.=$row['ANI_INDEX'].",";
	}
	return $items;
	
}

function get_public_sample_id($pid)
{
	$query="select sample_no from lot_no_rules where pid='".$pid."'";	
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){
		$items=$row['sample_no'];
	}
	return $items;
	
}

function head()
{
echo'<head>
<title>編輯</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
  <title>jQuery UI Datepicker - Default functionality</title>
  <link rel="stylesheet" href="/css/jquery-ui.css">
  <script src="/css/jquery-1.12.4.js"></script>
  <script src="/css/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
  <style type="text/css">
  #form1 table tr .ui-icon-alert strong {
	color: #F00;
}
  </style>
  <script type="text/javascript">
    $(function() {
    $( "#datepicker1" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
	$( "#datepicker2" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
	$( "#datepicker3" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
	$( "#datepicker4" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
	$( "#datepicker5" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
  });

var d;
function sendIt() {
 if (d) document.body.removeChild(d);
 var info = document.getElementById("pdd_chemical").value;
 d = document.createElement("script");
 d.src = "pdd_prod_no.php?info="+info;
 d.type = "text/javascript";
 document.body.appendChild(d);
}
  </script>
</head>';
}

function select_ani_group(){
	session_start();
	echo '<select name="selected_group" id="selected_group">';
	echo '<option value="0"></option>';
	$query="SELECT DISTINCT ANI_GROUPNAME
	FROM              AnalyzeItem
	WHERE          (ANI_GROUPNAME <> '')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		if($row['ANI_GROUPNAME']==$_SESSION['selected_group']){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['ANI_GROUPNAME'].'" '.$select.'>'.$row['ANI_GROUPNAME'].'</option>';
	}
	echo '</select>';
}

function prod_out_term($pid,$cid)
{
	$query="SELECT    top(1)      CTP_VALID_MON
			FROM              CUSTOMER_PRODUCTS
			WHERE          (CTD_CUST_NO = '".$cid."') AND (PDD_PROD_NO = '".$pid."')";
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		$a= $row['CTP_VALID_MON'];
	}
	return date("Ymd" , mktime(0,0,0,date("m")+$a,date("d"),date("Y")) );	
}

function sql_rec($url,$querys)
{
	session_start();
	include("../connections/conn.php");
	$querys=str_replace ("'","##",$querys);
	$querys=str_replace ('"',"##",$querys);
	$query="INSERT INTO PHP_LOG
                            (url, sql_cmd, uid, rec_time)
			VALUES          ('".$url."','".$querys."', '".$_SESSION['uid']."', '".date("Y-m-d H:i:s")."') select SCOPE_IDENTITY() as id";
	$result=mssql_query($query);
}
?>
