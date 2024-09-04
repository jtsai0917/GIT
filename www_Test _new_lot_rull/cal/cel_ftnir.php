<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php 
session_start();
include("../connections/conn_msa.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
include("../PHPEXCEL/Classes/PHPExcel.php");
include("../PHPEXCEL/Classes/PHPExcel/IOFactory.php");

$path_root=$_SERVER['HTTP_HOST'];
datepick();




echo '<table width="900"><tr><td>
<font size="+1">
<form name="form1" method="post" action="">
  日期區間
	<input name="datepicker1" type="text" id="datepicker1" size="10" value="'.$_SESSION['datepicker1'].'"  onchange="set_date_session(this.name,this.value)">
        ~
	<input name="datepicker2" type="text" id="datepicker2" size="10" value="'.$_SESSION['datepicker2'].'"  onchange="set_date_session(this.name,this.value)">
	<input type="submit" name="submit" value="搜尋">
	<input type="submit" name="excel" value="excel">
	<a href="FTNIR_SPC1.xlsx" target="new">原始範本</a>
	
</form>
</font>
</td></tr></table>
';
if(isset($_POST['submit'])){
	$datefrom=ddo($_POST['datepicker1'])." 00:00:00";
	$dateto=ddo($_POST['datepicker2'])." 23:59:59";
	echo '<table border="1" width="900"><tr bgcolor="#CCCCCC"><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A: Signal to Noise test      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;B: 100% LINE test      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;C: Interferogram peak test     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;D: Energy test</td></tr></table>';
	echo '<table border="1" width="900"><tr bgcolor="#CCCCCC"><td width="40" align="center">Item</td><td width="100" align="center">日期</td><td width="100" align="center">CalibrateID</td><td width="90" align="center">A</td><td width="90" align="center">B</td><td width="90" align="center">C</td><td width="90" align="center">D</td><td width="100" align="center">溫度</td><td width="100" align="center">濕度</td><td width="100" align="center">分析師</td></tr>';
		
	$query="SELECT DISTINCT A.CalibrateID, B.N_DP, B.ShowCount, CONVERT(varchar, B.RealDate, 101) AS Expr1, B.DoMan,B.RealDate 
	FROM      CalibrateData AS A INNER JOIN
					   Calibrate AS B ON A.CalibrateID = B.CalibrateID
	WHERE   (A.CtrlItem LIKE N'a.%' OR
					   A.CtrlItem LIKE N'b.%' OR
					   A.CtrlItem LIKE N'c.%' OR
					   A.CtrlItem LIKE N'd.%') ";
	if(trim($_POST['datepicker1']<>'')){$query.=" AND (B.RealDate >= CONVERT(DATETIME, '".$datefrom."', 102)) ";}
	if(trim($_POST['datepicker2']<>'')){$query.=" AND (B.RealDate <= CONVERT(DATETIME, '".$dateto."', 102)) ";}			
	$query.=" order by B.RealDate";	   
	$result=mssql_query($query);
	$x=1;
	while($row=mssql_fetch_array($result)){
		list($t1,$t2,$t3,$t4,$t5,$t6)=value($row['CalibrateID']);
		echo '<tr><td align="center" bgcolor="#CCCCCC">'.$x.'</td><td align="center">'.$row['Expr1'].'</td><td align="center">'.$row['CalibrateID'].'</td><td align="center">'.$t1.'</td><td align="center">'.$t2.'</td><td align="center">'.$t3.'</td><td align="center">'.$t4.'</td><td align="center">'.$t5.'</td><td align="center">'.$t6.'</td><td align="center">'.$row['DoMan'].'</td></tr>';
		$x++;
	}

}
if(isset($_POST['excel'])){
	$objPHPExcel = new PHPExcel();
	$objPHPExcel = PHPExcel_IOFactory::load("ftnir.xlsx");
	$objPHPExcel->setActiveSheetIndex(0);
	$datefrom=ddo($_POST['datepicker1'])." 00:00:00";
	$dateto=ddo($_POST['datepicker2'])." 23:59:59";
	$query="SELECT DISTINCT A.CalibrateID, B.N_DP, B.ShowCount, CONVERT(varchar, B.RealDate, 101) AS Expr1, B.DoMan,B.RealDate 
	FROM      CalibrateData AS A INNER JOIN
					   Calibrate AS B ON A.CalibrateID = B.CalibrateID
	WHERE   (A.CtrlItem LIKE N'a.%' OR
					   A.CtrlItem LIKE N'b.%' OR
					   A.CtrlItem LIKE N'c.%' OR
					   A.CtrlItem LIKE N'd.%') ";
	if(trim($_POST['datepicker1']<>'')){$query.=" AND (B.RealDate >= CONVERT(DATETIME, '".$datefrom."', 102)) ";}
	if(trim($_POST['datepicker2']<>'')){$query.=" AND (B.RealDate <= CONVERT(DATETIME, '".$dateto."', 102)) ";}			
	$query.=" order by B.RealDate";	   
	$result=mssql_query($query);
	$x=8;$n=1;
	while($row=mssql_fetch_array($result)){
		list($t1,$t2,$t3,$t4,$t5,$t6)=value($row['CalibrateID']);
		$objPHPExcel->setActiveSheetIndex(0)
 							->setCellValue("A".$x,$n)
	    					->setCellValue("B".$x,$row['Expr1'])
	  						->setCellValue("C".$x,$row['CalibrateID'])
							->setCellValue("D".$x,$t1)
							->setCellValue("E".$x,$t2)
							->setCellValue('F'.$x,$t3)
							->setCellValue('G'.$x,$t4)
							->setCellValue('H'.$x,$t5)
							->setCellValue('I'.$x,$t6)
							->setCellValue('J'.$x,iconv("big5","utf-8",$row['DoMan']))
							;
		$oo[$n][0]=$t1;
		$oo[$n][1]=$t2;
		$oo[$n][2]=$t3;
		$oo[$n][3]=$t4;
		$oo[$n][4]=$t5;
		$oo[$n][5]=$t6;
		$sum1=$sum1+$t1;
		$sum2=$sum2+$t2;
		$sum3=$sum3+$t3;
		$sum4=$sum4+$t4;
		$sum5=$sum5+$t5;
		$sum6=$sum6+$t6;
		$x++;$n++;
	}
	
	$av1=$sum1/($n-1);
	$av2=$sum2/($n-1);
	$av3=$sum3/($n-1);
	$av4=$sum4/($n-1);
	$av5=$sum5/($n-1);
	$av6=$sum6/($n-1);
	for($x=1;$x<$n;$x++){
		$stdev1=$stdev1+pow(($oo[$x][0]-$av1),2);
		$stdev2=$stdev2+pow(($oo[$x][1]-$av2),2);
		$stdev3=$stdev3+pow(($oo[$x][2]-$av3),2);
		$stdev4=$stdev4+pow(($oo[$x][3]-$av4),2);
		$stdev5=$stdev5+pow(($oo[$x][4]-$av5),2);
		$stdev6=$stdev6+pow(($oo[$x][5]-$av6),2);
	}
	$stdev1=pow(($stdev1/($n-1)),0.5);
	$stdev2=pow(($stdev2/($n-1)),0.5);
	$stdev3=pow(($stdev3/($n-1)),0.5);
	$stdev4=pow(($stdev4/($n-1)),0.5);
	$stdev5=pow(($stdev5/($n-1)),0.5);
	$stdev6=pow(($stdev6/($n-1)),0.5);

	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D5",$av1)->setCellValue("E5",$av2)->setCellValue("F5",$av3)->setCellValue("G5",$av4)->setCellValue("H5",$av5)->setCellValue("I5",$av6);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D6",$stdev1)->setCellValue("E6",$stdev2)->setCellValue("F6",$stdev3)->setCellValue("G6",$stdev4)->setCellValue("H6",$stdev5)->setCellValue("I6",$stdev6);
	
	$objPHPExcel->setActiveSheetIndex(0);
	

// 列印圖表
/*
	//橫條圖
	//設定數列名稱
	$dataseriesLabels = array(
		new PHPExcel_Chart_DataSeriesValues('100% LINE test', '$E$2', NULL, 1),   //Data1
		new PHPExcel_Chart_DataSeriesValues('Interferogram peak', '$F$2', NULL, 1),   //Data2
		new PHPExcel_Chart_DataSeriesValues('Energy test', '$G$2', NULL, 1),   //Data4
	);

	//Y軸名稱
	$yAxisTickValues = array(
		new PHPExcel_Chart_DataSeriesValues('', '$F$8:$F$208', NULL, 200),  // 0~6
	);

//資料線
//PHPExcel_Chart_DataSeriesValues
$dataSeriesValues = array(
    new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$B$2:$B$5'),
    new PHPExcel_Chart_DataSeriesValues(PHPExcel_Chart_DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$C$2:$C$5'),
    new PHPExcel_Chart_DataSeriesValues('Number','Worksheet!$D$2:$D$5'),
    new PHPExcel_Chart_DataSeriesValues('Number','Worksheet!$E$2:$E$5'),
	new PHPExcel_Chart_DataSeriesValues('Number','Worksheet!$F$2:$F$5'),
	new PHPExcel_Chart_DataSeriesValues('Number','Worksheet!$G$2:$G$5'),
	new PHPExcel_Chart_DataSeriesValues('Number','Worksheet!$H$2:$H$5'),    
);
//將剛剛建立的資料線
//折線圖,第二個參數要用PHPExcel_Chart_DataSeries::GROUPING_STACKED
$series1=new PHPExcel_Chart_DataSeries(
PHPExcel_Chart_DataSeries::TYPE_LINECHART,
        PHPExcel_Chart_DataSeries::GROUPING_STACKED,    // plotGrouping
    range(0, count($dataSeriesValues)-1),           // plotOrder
    $dataseriesLabels,                              // plotLabel
    $yAxisTickValues,                               // plotCategory
    $dataSeriesValues
);
//設定Chart的類型，這邊是橫條圖
$series->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_BAR);
//Excel的Chart可以包含多種類型的線在同一個Chart裡面
//在這邊將要加入的線的類型及資料加入
$plotarea = new PHPExcel_Chart_PlotArea(NULL, array($series));
//圖例位置設定在上面
$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_TOP, NULL, false);
//設定Chart的Title,就是最上方的顯示字樣
$title = new PHPExcel_Chart_Title("Chart Demo");
//設定Chart的X軸下方的字樣
$yAxisLabel = new PHPExcel_Chart_Title("Test Data");
//設定最大值,這是1.81版才有的功能，若是1.80則無此功能
$axis=new PHPExcel_Chart_Axis();
//$axis->setAxisOptionsProperties('nextTo', null, null, null, null, null, null, 100);
$axis->setAxisOptionsProperties("nextTo", null, null, null, null, null,0, 100);
//將剛剛的設定彙整
$chart = new PHPExcel_Chart(
    'chart1',       // name
    $title,         // title
    $legend,        // legend
    $plotarea,      // plotArea
    true,           // plotVisibleOnly
    0,              // displayBlanksAs
    NULL,           // xAxisLabel
    $yAxisLabel     // yAxisLabel
        ,$axis
);
 
//設定Chart的位置從A7到H25
$chart->setTopLeftPosition("A7");
$chart->setBottomRightPosition("H25");
//將Chart加到工作表
$excel->getSheet(0)->addChart($chart);

	//
*/

	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(12);//設定欄寬
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
//	$objWriter->setIncludeCharts(TRUE);
	$objWriter->save("./FTNIR_SPC.xlsx");

	echo '<script>document.location.href="http://'.$path_root.'/cal/FTNIR_SPC.xlsx";</script>';
}

function value($CalibrateID){
	$query="SELECT  Calibrate.Temper, Calibrate.Wet, CalibrateData.CtrlItem, CalibrateData.ShowValue 
FROM      Calibrate INNER JOIN
                   CalibrateData ON Calibrate.CalibrateID = CalibrateData.CalibrateID
WHERE   (CalibrateData.CalibrateID = N'".$CalibrateID."')
 ORDER BY CalibrateData.CtrlItem";
$result=mssql_query($query);
$i=0;
while($row=mssql_fetch_array($result)){
	$Temper=$row['Temper'];
	$Wet=$row['Wet'];
	$aa[$i]=$row['ShowValue'];
	$i++;
}
return array($aa[0],$aa[1],$aa[2],$aa[3],$Temper,$Wet);
}
