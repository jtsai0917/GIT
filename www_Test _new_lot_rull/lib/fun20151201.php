<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
session_start();

function dod($ds){  //day-style  09/02/2015=>20150902
$dat=substr($ds,6).substr($ds,0,-8).substr($ds,3,-5);
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
function tm($ds){   //day-style  201509020102=>01
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
function datepick(){

echo '<link rel="stylesheet" href="/js/jquery-ui.css">';
echo '<script src="/js/jquery-1.10.2.js"></script>';
echo '<script src="/js/jquery-ui.js"></script>';
echo '<link rel="stylesheet" href="/resources/demos/style.css">';
echo '<script type="text/javascript">';
echo '  $(function() {';
echo '  $( "#datepicker1" ).datepicker();';
echo '	$( "#datepicker2" ).datepicker();';
echo '	$( "#datepicker3" ).datepicker();';
echo '	$( "#datepicker4" ).datepicker();';
echo '  });';
echo '</script>';
}

function analyzelist(){  //列出依賴
	
	include("../connections/conn.php");
	$query="SELECT    DISTINCT      AnalyzeDesign.AND_APPLY_DATE, AnalyzeDesign.AND_NEED_NO, AnalyzeDesign.AND_LOT_NO, 
                            AnalyzeDesign.AND_GOODS, AnalyzeDesign.CTD_CUST_NO, AnalyzeDesign.AND_BEFORE, 
                            AnalyzeDesign.AND_SMP_DATETIME, AnalyzeDesign.AND_ITEM, AnalyzeDesign.AND_ANA_ID, 
                            AnalyzeDesign.AND_VALUE, AnalyzeDesign.AND_MEMO, AnalyzeDesign.AND_TOTAL, 
                            AnalyzeDesign.AND_OUT_QTY, AnalyzeDesign.AND_OUT_DATETIME, AnalyzeDesign.AND_REPORT_DATETIME, 
                            AnalyzeDesign.AND_MARK, AnalyzeDesign.AND_GET_QTY, AnalyzeDesign.AND_GET_DATETIME, 
                            AnalyzeDesign.AND_RESULT_DATETIME, AnalyzeDesign.AND_RESULT, 
                            AnalyzeDesign.AND_PERSON, AnalyzeDesign.ALM_IDENTITY51, AnalyzeDesign.ALM_IDENTITY52, 
                            AnalyzeDesign.ALM_IDENTITY53, AnalyzeDesign.ALM_IDENTITY54, AnalyzeDesign.ALM_IDENTITY55, 
                            AnalyzeDesign.ALM_IDENTITY56, AnalyzeDesign.ALM_IDENTITY13, PRODUCT_DATA.PDD_PROD_NAME, 
                            EMPLOYEE_DATA.EMP_NAME,PRODUCT_DATA.PDD_PROD_NO
FROM              AnalyzeDesign INNER JOIN
                            EMPLOYEE_DATA ON AnalyzeDesign.AND_PERSON = EMPLOYEE_DATA.EMP_NO INNER JOIN
                            PRODUCT_DATA ON AnalyzeDesign.AND_GOODS = PRODUCT_DATA.PDD_PROD_NO
WHERE          (AND_APPLY_DATE <> '')";
if ($_POST['lot_no']<>""){
		$query.=" AND (AnalyzeDesign.AND_LOT_NO = '".$_POST['lot_no']."')";
}
elseif ($_POST['datepicker1']<>""){
	$query.=" AND (AnalyzeDesign.AND_APPLY_DATE >='".dod($_POST['datepicker1'])."000000')";
	if ($_POST['datepicker2']<>""){
	$query.=" AND (AnalyzeDesign.AND_APPLY_DATE <='".dod($_POST['datepicker2'])."235959')";
    }
	if ($_POST['purpose']=="1"){
		$query.=" and (AnalyzeDesign.AND_NEED_NO<=150)";}
	if ($_POST['purpose']=="2"){
		$query.=" and (AnalyzeDesign.AND_NEED_NO>150) and (AnalyzeDesign.AND_NEED_NO<=250)";}
    if ($_POST['purpose']=="3"){
		$query.=" and (AnalyzeDesign.AND_NEED_NO>250) and (AnalyzeDesign.AND_NEED_NO<=350)";}
	if ($_POST['prod_no']<>""){
		$query.=" and (AnalyzeDesign.AND_GOODS='".$_POST['prod_no']."')";}

    $query.=" ORDER BY   AnalyzeDesign.AND_APPLY_DATE DESC";
}
//echo $query;
    $result = mssql_query($query);
    $numRows = mssql_num_rows($result);
echo '<table  border="1">';
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
    echo '<td>註記</td>';
    echo '<td>樣品取得瓶數時間</td>';
    echo '<td>結果報告時間</td>';
    echo '<td>結果</td>';
    echo '<td>依賴人</td>';
  echo '</tr>';	
while($row = mssql_fetch_array($result)){
	if($row['AND_NEED_NO']<=150){$purpose="製品";}
	if(($row['AND_NEED_NO']>150)&& ($row['AND_NEED_NO']<=250)){$purpose="分析";}
	if(($row['AND_NEED_NO']>250)&& ($row['AND_NEED_NO']<=350)){$purpose="受入";}
	if($row['AND_BEFORE']=="Y"){$before="是";}else{$before="否";}
  echo '<tr>';    
    echo '<td>'.ddd($row['AND_APPLY_DATE']).'</td>';
    echo '<td>'.$row['PDD_PROD_NAME'].'</td>';
    echo '<td>'.$row['PDD_STYLE'].'</td>';
    echo '<td>'.$row['AND_LOT_NO'].'</td>';
    echo '<td>'.$purpose.'</td>';
    echo '<td>'.$before.'</td>';
    echo '<td>'.ddt($row['AND_SMP_DATETIME']).'</td>';
    echo '<td>'.$row['AND_TOTAL'].'</td>';
    echo '<td>'.$row['AND_OUT_QTY']."瓶 ".ddt($row['AND_OUT_DATETIME']).'</td>';
    echo '<td>'.ddt($row['AND_REPORT_DATETIME']).'</td>';
    echo '<td>'.$row['AND_MARK'].'</td>';
    echo '<td>'.$row['AND_GET_QTY']."瓶 ".ddt($row['AND_GET_DATETIME']).'</td>';
    echo '<td>'.ddt($row['AND_RESULT_DATETIME']).'</td>';
    echo '<td>'.$row['AND_RESULT'].'</td>';
    echo '<td>'.$row['EMP_NAME'].'</td>';
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
echo '<a target="_self" href=input_results.php?lot_no='.$lot_no."&pdd_prod_no=".$pdd_prod_no."&ani_groupname=".$row['ANI_GROUPNAME']."&pdd_chemical=".$pdd_chemical."&operator=".$operator.' '.'><font color="'.$ok.'">'.$row['ANI_GROUPNAME'].'  ,</font> </a>
';
//	echo iconv("big5","utf-8",$row['ANI_GROUPNAME']).', ';
}
echo '</td>';
}
function getok($lot_no,$pdd_prod_no,$ani_groupname,$pdd_cchemical){
$query1="SELECT DISTINCT ELEMENT_FORM.ELF_FORM AS Expr1
FROM              ELEMENT_FORM INNER JOIN
                            AnalyzeItem ON ELEMENT_FORM.ELM_ID = AnalyzeItem.ANI_INDEX
WHERE          (ELEMENT_FORM.PDD_CHEMICAL = '".$pdd_cchemical."') AND (AnalyzeItem.ANI_GROUPNAME = '".$ani_groupname."')";
$result = mssql_query($query1);
//echo $query1;
while($row = mssql_fetch_array($result)){$table=$row['Expr1'];}
$query1="SELECT          dbo.Sample_All.SMA_LOT, dbo.Sample_All.SMA_ID, dbo.".$table.".*
FROM              dbo.".$table." INNER JOIN
                            dbo.Sample_All ON dbo.".$table.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$table.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$table.".Ok = '1') AND (dbo.Sample_All.SMA_LOT = '".$lot_no."')";
//echo $query1;
$result = mssql_query($query1);
$numRows = mssql_num_rows($result);
if ($numRows==0){return "red";}
if ($numRows>0){return "blue";}
}

function showcust_spec($cid,$pid,$itemname){
	include("../connections/conn.php");
	$query="SELECT DISTINCT 
                            QC_CustProdSpec.ProdNo, QC_Spec.ID, QC_Spec.SpecNo, QC_Spec.SpecVer, QC_Spec.IX, QC_Spec.ItemName, 
                            QC_Spec.ItemUnit, QC_Spec.USL, QC_Spec.LSL, QC_Spec.DL, QC_Spec.UCL, QC_Spec.LCL, QC_Spec.ShowMode, 
                            QC_Spec.state, QC_Spec.createuser, QC_Spec.createts, QC_Spec.Digit, QC_Spec.UAXIS, QC_Spec.LAXIS, 
                            QC_Spec.STD_U, QC_Spec.STD_L, QC_Spec.showUSL, AnalyzeItem.ANI_FULLNAME, 
                            AnalyzeItem.ANI_GROUPNAME
FROM              AnalyzeItem INNER JOIN
                            QC_CustProdSpec INNER JOIN
                            QC_Spec ON QC_CustProdSpec.SpecNo = QC_Spec.SpecNo AND QC_CustProdSpec.SpecVer = QC_Spec.SpecVer ON 
                            AnalyzeItem.ANI_FULLNAME = QC_Spec.ItemName
WHERE          (QC_CustProdSpec.CustNo = '".$cid."') AND (QC_CustProdSpec.ProdNo = '".$pid."') AND 
                            (AnalyzeItem.ANI_GROUPNAME = '".$itemname."')";


//echo $query;	
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
$itm=$row['ItemUnit'];
$usl=$row['USL'];
$lsl=$row['LSL'];
$dl=$row['DL'];
$std_u=$row['STD_U'];
$std_l=$row['STD_L'];
$_SESSION['teststatus']=1;
$spec=$row['SpecNo'];
$ver=$row['SpecVer'];
if ($row['ShowMode']=='<DL'){$showmode='< DL';}
else {$showmode=$row['ShowMode'];}
// echo $query."</br>";
}
echo "客戶規格：  ".$spec."  版本：".$ver."</br>";
echo "測試規格：";
echo "  (USL=".$usl.")";
echo "  (LSL=".$lsl.")";
echo "  (DL=".$dl.')</br>';
echo "再分析標準： (再分析_U=".$std_u.")  (再分析_L=".$std_l.")</br>";
echo "報告顯示：".$showmode."</br>";
echo "(單位=".$itm." )";
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
//echo $query;
echo "社內規格：</br>";
$result = mssql_query($query);
while($row = mssql_fetch_array($result)){
	$itm=$row['ELI_UNIT'];
}
echo "測試規格：(單位=".$itm." )";
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
	//	echo $row['ANI_NICKNAME'];

$spec="<".$row['USL']."";
$again=$row['STD_U']."<\\".$row['DL'];
$usl=$row['USL'];
$dl=$row['DL'];

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
//echo $query;			
			$result = mssql_query($query);
//			echo $query;
				while($row = mssql_fetch_array($result)){
				$spec="<".$row['USL'];
				$dl=$row['CTS_DL'];
				$usl=$row['CTS_LY_USL'];
				$again="<".$row['CTS_LY_AGAIN_USL'];
				}

	}
	
		
return array($spec,	$dl,$usl,$again);
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
 
function my_msg($msg,$redirect){
    echo "<SCRIPT Language=javascript>";
    echo "window.alert('".$msg."')";
    echo "</SCRIPT>";
    echo "<script language=\"javascript\">";
    echo "location.href='".$redirect."'";
    echo "</script>";
    return;
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
echo $query;
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
echo $_SESSION['tmp']."</br>";
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
	$query="SELECT DISTINCT ELEMENT_FORM.ELF_FORM,AnalyzeItem.ANI_NICKNAME, AnalyzeItem.ANI_FULLNAME
FROM              ELEMENT_FORM INNER JOIN
                            AnalyzeItem ON ELEMENT_FORM.ELM_ID = AnalyzeItem.ANI_INDEX
WHERE          (ELEMENT_FORM.PDD_CHEMICAL = '".$pdd_chemical."') AND (AnalyzeItem.ANI_GROUPNAME = '".$groupname."')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
$table=$row['ELF_FORM'];
return $table;
}
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
                            QC_CustProdSpec.ProdNo, QC_Spec.ID, QC_Spec.SpecNo, QC_Spec.SpecVer, QC_Spec.IX, QC_Spec.ItemName, 
                            QC_Spec.ItemUnit, QC_Spec.USL, QC_Spec.LSL, QC_Spec.DL, QC_Spec.UCL, QC_Spec.LCL, QC_Spec.ShowMode, 
                            QC_Spec.state, QC_Spec.createuser, QC_Spec.createts, QC_Spec.Digit, QC_Spec.UAXIS, QC_Spec.LAXIS, 
                            QC_Spec.STD_U, QC_Spec.STD_L, QC_Spec.showUSL, AnalyzeItem.ANI_FULLNAME, AnalyzeItem.ANI_GROUPNAME,
                             dbo.AnalyzeItem.ANI_ID
FROM              dbo.AnalyzeItem INNER JOIN
                            dbo.QC_CustProdSpec INNER JOIN
                            dbo.QC_Spec ON QC_CustProdSpec.SpecNo = QC_Spec.SpecNo AND 
                            QC_CustProdSpec.SpecVer = QC_Spec.SpecVer ON AnalyzeItem.ANI_FULLNAME = QC_Spec.ItemName
WHERE          (QC_CustProdSpec.CustNo = '".$cid."') AND (QC_CustProdSpec.ProdNo = '".$pid."') AND (dbo.AnalyzeItem.ANI_INDEX = '".$aid."') order by dbo.AnalyzeItem.ANI_GROUPNAME";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
if ($numRows>0){
while($row = mssql_fetch_array($result)){
$unit=$row['ItemUnit'];
$itm_name=get_ani_itemname($aid);
$showmode=$row['ShowMode'];
$dl=$row['DL'];
$usl=$row['UCL'];
$lsl=$row['LSL'];
$dl=$row['DL'];
$spec=$row['SpecNo'];
$ver=$row['SpecVer'];
$fullname=$row['ANI_FULLNAME'];
if ($row['ShowMode']=='<DL')
{
	$showmode='< USL';
	$spc="< ".$usl;
}
if ($row['ShowMode']=='<USL')
{
	$showmode='< USL';
	$spc="< ".$usl;
}
if (($row['ShowMode']=='VALUE') or ($row['ShowMode']=='Average'))
{
	$showmode='VALUE';
	$spc=$usl." ~ ".$lsl;
}

else {$showmode=$row['ShowMode'];}
// echo $query."</br>";
}
return array($itm_name,$spc,$unit,$dl,$fullname);
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

function get_report_value($table,$lot_no,$item){
$query="SELECT          LotNo, SampleNo, Ok, Tester, Operator, AnalyzeTime,[".$item."]
FROM              dbo.".$table."
WHERE          (LotNo = '".$lot_no."') And (Ok='1') order by AnalyzeTime";

$result = mssql_query($query);
$numRows = mssql_num_rows($result);

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
return array($report,$SampleNo,$Tester,$AnalyzeTime,$Ok);
}
else{
$query="SELECT          LotNo, SampleNo, Ok, Tester, Operator, AnalyzeTime,[".$item."]
FROM              dbo.".$table."
WHERE          (LotNo = '".$lot_no."') And (Ok='0') order by AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
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
return array($report,$SampleNo,$Tester,$AnalyzeTime,$Ok);
}
else{
	return array(none,未檢驗,none,none,無);
	}
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

?>
