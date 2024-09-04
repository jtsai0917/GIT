<?php
session_start();
include("../checkuser.php");
include("../lib/fun.php");
include("../connections/conn.php");
lasturl1();
lasturl();
datepick(); 
if($_SESSION['datepicker1']==''){$_SESSION['datepicker1']=date("m/d/Y");}
if($_SESSION['datepicker2']==''){$_SESSION['datepicker2']=date("m/d/Y");}
$_SESSION['search']=1;
?>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>"> 
  <table width="1200" border="1">
    <tr>查詢範圍
      <td>日期區間: 
        <input name="datepicker1" type="text" id="datepicker1" size="14" value="<?php echo $_SESSION['datepicker1'] ; ?>"   onchange="set_date_session(this.name,this.value)">
~
        <input name="datepicker2" type="text" id="datepicker2" size="14" value="<?php echo $_SESSION['datepicker2'] ; ?>"   onchange="set_date_session(this.name,this.value)">
品名：
<input type="button" name="X" id="X" value="X" onclick="window.open('../erase_prod.php ', '_self');" />
<input name="prod_no" type="text" id="prod_no" size="10" value="<?php echo $_SESSION['prod_no']?>" readonly="readonly" />
<input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onclick="window.open('../pdd_prod_no.php ', '_self');" />
<input name="prod_name" type="text" id="prod_name" size="16" value="<?php echo get_prod_name($_SESSION['prod_no']);?>" readonly="readonly" />
</br>目的: 
<label for="purpose"></label>
<select name="purpose" id="purpose">
  <option value="0" <?php if($_SESSION['search']==0){echo " selected";}?> >ALL</option>
  <option value="1" <?php if($_SESSION['search']==1){echo " selected";}?>>製品</option>
  <option value="2" <?php if($_SESSION['search']==2){echo " selected";}?>>解析</option>
  <option value="3" <?php if($_SESSION['search']==3){echo " selected";}?>>受入</option>
</select>
Lot No：
<input type="submit" name="X2" id="X2" value="X" />
<input name="lot_nox" type="text" id="lot_nox" size="10" value="<?php echo trim($_SESSION['lot_nox']);?>" onchange="set_date_session(this.name,this.value)" />
</td><td width="400" align="center">
</br>
<input type="submit" name="search" id="search" value="  搜 尋  " />
<input type="button" name="new" id="new" value="  新增依賴  " onClick="window.open('add_anylize.php', '_self');"/>
<input type="hidden" name="x3" id="X3" value="X3"/>
</td>
    </tr>
  </table>
</form>
</body>
</html>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
if(isset($_POST['X2'])){
	unset($_SESSION['lot_nox']);
	refresh();
}

$path_root=$_SERVER['HTTP_HOST'];
	analyzelist1();
//////////////////////////////////////////////////////////
if ($_GET['lot_no']){$_SESSION['AND_LOT_NO']=$_POST['textfield4']=$_GET['lot_no'];}
if($_POST['search']){
	$_SESSION['d2']=$_POST['datepicker2'];
	$_SESSION['d1']=$_POST['datepicker1'];
	$_SESSION['lot_nox']=trim($_POST['lot_nox']);
	$_SESSION['search']=$_POST['purpose'];
	$_SESSION['lot1']=substr($_POST['datepicker1'],-1).exmonth(substr($_POST['datepicker1'],0,2)).substr($_POST['datepicker1'],3,2);
	$_SESSION['lot2']=substr($_POST['datepicker2'],-1).exmonth(substr($_POST['datepicker2'],0,2)).substr($_POST['datepicker2'],3,2);
refresh();
}

//////////////////////////////////////////////////////////
if(isset($_POST['print_order'])){
	$_SESSION['d2']=$_POST['datepicker2'];
	$_SESSION['d1']=$_POST['datepicker1'];
	$_SESSION['lot_nox']=$_POST['lot_nox'];
	$_SESSION['search']=$_POST['purpose'];
	$_SESSION['lot1']=$lot1=substr($_POST['datepicker1'],-1).exmonth(substr($_POST['datepicker1'],0,2)).substr($_POST['datepicker1'],3,2);
	$_SESSION['lot2']=$lot2=substr($_POST['datepicker2'],-1).exmonth(substr($_POST['datepicker2'],0,2)).substr($_POST['datepicker2'],3,2);

	$dat=date("YmdHis");
	include("../PHPEXCEL/Classes/PHPExcel.php");
	require_once "../PHPEXCEL/Classes/PHPExcel.php";
	require_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
	$fileurl="./form/tmp";
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel = PHPExcel_IOFactory::load("./form/analyze_order.xlsx");	
$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);

$x=1;$y=3;
include("../connections/conn.php");
$query="SELECT          AnalyzeDesign.* , PRODUCT_DATA.PDD_PROD_NAME, 
                            EMPLOYEE_DATA.EMP_NAME,PRODUCT_DATA.PDD_PROD_NO, PRODUCT_DATA.PDD_PACKAGE, AnalyzeDesign.AND_CANCEL
FROM              AnalyzeDesign INNER JOIN
                            EMPLOYEE_DATA ON AnalyzeDesign.AND_PERSON = EMPLOYEE_DATA.EMP_NO INNER JOIN
                            PRODUCT_DATA ON AnalyzeDesign.AND_GOODS = PRODUCT_DATA.PDD_PROD_NO
WHERE          (AND_APPLY_DATE <> '') ";
if($_SESSION['prod_no']<>NULL){$query.=" AND (PRODUCT_DATA.PDD_PROD_NO='".$_SESSION['prod_no']."') ";}
if(($_SESSION['d1']<>'') and ($_SESSION['d2']<>'')){
	$query.=" AND 		((SUBSTRING(dbo.AnalyzeDesign.AND_LOT_NO, 2, 4) = '".$_SESSION['lot1']."') or (SUBSTRING(dbo.AnalyzeDesign.AND_LOT_NO, 7, 4) = '".$_SESSION['lot1']."') 
	or (SUBSTRING(dbo.AnalyzeDesign.AND_LOT_NO, 4, 4) = '".$_SESSION['lot1']."'))";}
	if($_SESSION['search']=='1'){$query.=" and (AND_NEED_NO<=150)" ;}
	if($_SESSION['search']=='2'){$query.=" and (AND_NEED_NO<=270) and (AND_NEED_NO>150)";}
	if($_SESSION['search']=='3'){$query.=" and (AND_NEED_NO>270)";}
    $query.=" ORDER BY  AnalyzeDesign.AND_GOODS, PRODUCT_DATA.PDD_PACKAGE DESC";
if($_SESSION['lot_nox']<>NULL){
	$query="SELECT          AnalyzeDesign.* , PRODUCT_DATA.PDD_PROD_NAME, 
                            EMPLOYEE_DATA.EMP_NAME,PRODUCT_DATA.PDD_PROD_NO, PRODUCT_DATA.PDD_PACKAGE, AnalyzeDesign.AND_CANCEL
FROM              AnalyzeDesign INNER JOIN
                            EMPLOYEE_DATA ON AnalyzeDesign.AND_PERSON = EMPLOYEE_DATA.EMP_NO INNER JOIN
                            PRODUCT_DATA ON AnalyzeDesign.AND_GOODS = PRODUCT_DATA.PDD_PROD_NO
WHERE          (AND_APPLY_DATE <> '') ";
		$query.=" AND (AnalyzeDesign.AND_LOT_NO like '%".$_SESSION['lot_nox']."%')";
}
$_SESSION['QQQQQ']=$query;
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
$x=1;$y=3;

if($numRows>0){
	while($row = mssql_fetch_array($result)){
		$hh=new get_from_lot_no;
		$hh->lid=$row['AND_LOT_NO'];
		$hh->cid();
		$hh->ani();
		if($row['AND_NEED_NO']<=150){$tmp="製品";}
		if(($row['AND_NEED_NO']<=250) and ($row['AND_NEED_NO']>150)){$tmp="解析";}
		if($row['AND_NEED_NO']>=270){$tmp="受入";}
		$I1="( ".substr($_POST['datepicker1'],0,2)."  月  ".substr($_POST['datepicker1'],3,2)."  日  )";
		$a[$x]=$row['AND_REPORT_DATETIME'];
		$b[$x]=get_prod_name($row['AND_GOODS']);
		$c[$x]=$row['PDD_PACKAGE'];
		$d[$x]=$row['AND_LOT_NO'];
		$e[$x]=$tmp;
		$coa=$row['AND_COA_FST'];
		if($coa=='Y'){$coa="先行COA";}
		else{$coa='';}
		$f[$x]=$row['AND_SMP_DATETIME'];
		$hh->ani_group();
		$g[$x]=$hh->ani_group();
		$h[$x]=$row['AND_TOTAL'];	
		if($hh->cname=='TYS Internal'){$cname='';}
		else{$cname=$hh->cname;}
		$i[$x]=$cname."\n".$row['AND_NOTE'];
		$p[$x]=$row['AND_NOTE2']."\n".$coa;
		$k[$x]=$row['AND_REPORT_DATETIME'];	
		if($row['AND_CANCEL']==1){$row['AND_CANCEL']='已取消';$i[$x]='已取消';}
		else{$row['AND_CANCEL']='';}
		$x=$x+1;	
	}	
}

$obj=$objPHPExcel->setActiveSheetIndex(0);
			$obj->getColumnDimension('A')->setWidth(3.44);
			$obj->getColumnDimension('B')->setWidth(8);
			$obj->getColumnDimension('C')->setWidth(5);
			$obj->getColumnDimension('D')->setWidth(11);
			$obj->getColumnDimension('E')->setWidth(8);
			$obj->getColumnDimension('F')->setWidth(8);
			$obj->getColumnDimension('G')->setWidth(20);
			$obj->getColumnDimension('H')->setWidth(8);
			$obj->getColumnDimension('I')->setWidth(8);
			$obj->getColumnDimension('J')->setWidth(8);
			$obj->getColumnDimension('K')->setWidth(16);
			$obj->getColumnDimension('L')->setWidth(8);
			$obj->getColumnDimension('M')->setWidth(8);			
			$obj->setCellValue('H1',iconv("big5","utf-8",$I1));
			
	for($x=1;$x<($numRows+1);$x++){	
		for($z=0;$z<8;$z++){
		$obj->mergeCells('I'.($y).":J".($y));
		$obj->setCellValue('I1',iconv("big5","utf-8",$I1));
		if($b[$x]){
		$obj->setCellValue('A'.$y,iconv("big5","utf-8","○"));}
		$obj->getStyle("'A".($y)."'")->getFont()->setSize(24);
		$obj->setCellValue('B'.$y,iconv("big5","utf-8",$b[$x]));
		$obj->getStyle("'B".($y)."'")->getFont()->setSize(9);
		$obj->setCellValue('C'.$y,iconv("big5","utf-8",$c[$x]));
		$obj->setCellValue('D'.$y,iconv("big5","utf-8",$d[$x]));
		$obj->setCellValue('E'.$y,iconv("big5","utf-8",$e[$x]));
		$obj->getStyle("'B".($y).":O".($y)."'")->getFont()->setSize(9);
		if($f[$x]<>''){$value_date2=substr($f[$x],4,2)."/".substr($f[$x],6,2)."\n".substr($f[$x],8,2).":".substr($f[$x],10,2);}
		else{$value_date2='';}
		$obj->setCellValue('F'.$y,iconv("big5","utf-8",$value_date2));
		$obj->setCellValue('G'.$y,iconv("big5","utf-8",$g[$x]));
		$obj->setCellValue('H'.$y,iconv("big5","utf-8",$h[$x]));
		$obj->setCellValue('I'.$y,iconv("big5","utf-8",$i[$x]));
		if($k[$x]<>''){$value_date=substr($k[$x],4,2)."/".substr($k[$x],6,2)." ".substr($k[$x],8,2).":".substr($k[$x],10,2)."\n".$p[$x];}
		else{$value_date='';}
		$obj->setCellValue('K'.$y,iconv("big5","utf-8",$value_date));
		if($l[$x]<>''){$value_date1=substr($l[$x],4,2)."/".substr($l[$x],6,2)."\n".substr($l[$x],8,2).":".substr($l[$x],10,2);}
		else{$value_date1='';}
		$obj->setCellValue('L'.$y,iconv("big5","utf-8",$value_date1));
		$obj->getRowDimension($y)->setRowHeight(50);
		$obj->getStyle("'A".$y."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'B".$y."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'C".$y."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'D".$y."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'E".$y."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'F".$y."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'G".$y."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'H".$y."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'I".$y."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'J".$y."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'K".$y."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'L".$y."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'M".$y."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'A".($y).":M".$y."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$obj->getStyle("'A".($y).":M".$y."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$obj->getStyle("'A".$y.":M".$y."'")->getAlignment()->setWrapText(true);
		$x=$x+1;
		$y=$y+1;
		}
		$y=$y+4;
		$x=$x-1;
		$obj->getRowDimension($y-1)->setRowHeight(21);
		$obj->getRowDimension($y-2)->setRowHeight(41.25);
		$obj->getRowDimension($y-3)->setRowHeight(15.75);
		$obj->getRowDimension($y-4)->setRowHeight(35.25);
		$obj->mergeCells('A'.($y-4).":M".($y-4));
		$obj->getStyle("'A".($y-4)."'")->getFont()->setSize(9);
		$sss="A=濃度、M=金屬、P=微粒子、I=陰離子；1=第一優先、2=第二優先；臨時分析依賴者對分析報告值是否需分析師判定".' "合格/不合格"'." (依社內再分析基準)，請在備註欄中註記‧";
		$elai='(依賴者:';
		$elai1='           )';
		$dep_ani='(分析課:';
		$dep_ani1='           )';
		$dep_QA='(品保課:';		
		$dep_QA1='           )';
		$obj->getStyle("'A".($y-4)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$obj->getStyle("'A".($y-4)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$obj->setCellValue('A'.($y-4),iconv("big5","utf-8",$sss));
		$obj->setCellValue('E'.($y-3),iconv("big5","utf-8",$elai));
		$obj->setCellValue('F'.($y-3),iconv("big5","utf-8",$elai1));
		$obj->setCellValue('H'.($y-3),iconv("big5","utf-8",$dep_ani));
		$obj->setCellValue('I'.($y-3),iconv("big5","utf-8",$dep_ani1));
		$obj->setCellValue('L'.($y-3),iconv("big5","utf-8",$dep_QA));
		$obj->setCellValue('M'.($y-3),iconv("big5","utf-8",$dep_QA1));
		$obj->getStyle("E".($y-2))->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("F".($y-2))->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("H".($y-2))->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("I".($y-2))->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("L".($y-2))->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("M".($y-2))->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'A".($y-4).":M".($y-4)."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->setBreak("'A". ($y-1)."'", PHPExcel_Worksheet::BREAK_ROW );
		$obj->setCellValue('O'.$y,iconv("big5","utf-8",$o[$x]));
	}
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
$objWriter->save($fileurl.".xlsx");
echo '<script>document.location.href="http://'.$path_root.'/cal/'.$fileurl.'.xlsx";</script>' ;
}

if(isset($_POST['page'])){
	echo "<SCRIPT Language=javascript>";
    echo "window.open('list.php?d1=".$_POST['datepicker1']."&d2=".$_POST['datepicker2']."')";
    echo "</SCRIPT>";
}

function analyzelist1(){  //列出依賴
	
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
if($_SESSION['lot_nox']<>''){$query.=" and AND_LOT_NO like '%".$_SESSION['lot_nox']."%'"; }
elseif($_SESSION['pid']<>NULL){$query.=" AND (PRODUCT_DATA.PDD_PROD_NO='".$_SESSION['pid']."') ";}
elseif($_SESSION['search']=='1' or '2'){$query.=" AND AND_SMP_DATETIME<='".dod($_SESSION['datepicker2'])."2359' and AND_SMP_DATETIME>='".dod($_SESSION['datepicker1'])."0000' ";}
	if($_SESSION['search']=='1'){$query.=" and (AND_NEED_NO<=150)" ;}
	if($_SESSION['search']=='2'){$query.=" and (AND_NEED_NO<=270) and (AND_NEED_NO>150)";}
	if($_SESSION['search']=='3'){$query.=" and (AND_NEED_NO>270)";}
    $query.=" ORDER BY  AnalyzeDesign.AND_GOODS, PRODUCT_DATA.PDD_PACKAGE DESC";
//	echo $query."<BR>";
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
	if($row['AND_TOTAL']<9){$row['AND_TOTAL']=9;}
    echo '<td>'.$row['AND_TOTAL'].'</td>';
	if($row['AND_OUT_QTY']<9){$row['AND_OUT_QTY']=9;}
    echo '<td>'.$row['AND_OUT_QTY']."瓶 ".ddt($row['AND_OUT_DATETIME']).'</td>';
    echo '<td>'.ddt($row['AND_REPORT_DATETIME']).'</td>';
	if($row['AND_GET_QTY']<9){$row['AND_GET_QTY']=9;}
    echo '<td>'.$row['AND_GET_QTY']."瓶 ".ddt($row['AND_GET_DATETIME']).'</td>';
    echo '<td>'.ddt($row['AND_RESULT_DATETIME']).'</td>';
    echo '<td>'.$row['AND_RESULT'].'</td>';
	echo '<td>'.$row['AND_COA_FST'].'</td>';
    echo '<td>'.$row['EMP_NAME'].'</td>';
	echo '<td>'.$row['AND_NOTE'].'</td>';
	echo '<td><a href="index.php?url=edit_anylize_A&AND_LOT_NO='.$row['AND_LOT_NO'].'">編輯</a></td>';
  echo '</tr>';	
}

echo '</table>';

}
?>
