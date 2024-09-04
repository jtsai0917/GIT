<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php 
session_start();
$path=dirname(__FILE__);
$path_root=$_SERVER['HTTP_HOST'];
include("../lib/fun.php");
include("../checkuser.php");
include("../lib/jtsai.php");
include("sinetics_lib.php");
include("../PHPEXCEL18/Classes/PHPExcel.php");
include("../PHPEXCEL18/Classes/PHPExcel/IOFactory.php");

$particle_trend_chart_st3="./form/particle_trend_chart_st3.xlsx";
$objPHPExcel = new PHPExcel();	
$objPHPExcel2 = new PHPExcel();	
$objReader = PHPExcel_IOFactory::createReader('Excel2007'); 
$objReader->setIncludeCharts(TRUE); 	
$objPHPExcel= $objReader->load($particle_trend_chart_st3); 

lasturl();
datepick();

if($_SESSION['datepicker1']==''){$_SESSION['datepicker1']=date("m/d/Y")   ;}
if($_SESSION['datepicker2']==''){$_SESSION['datepicker2']=date("m/d/Y")   ;}
?>
硫酸 Particle trend chart 產生作業 <a href="define_flow_chart.php" target="_blank">定義充填路徑欄位</a>
<table width="1240" border="1">
  <tr>
    <td width="480">
    <form name="form1" action="" method="post">
      檢驗日期<input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php echo $_SESSION['datepicker1'] ; ?>"  onchange="set_date_session(this.name,this.value)" />
      ~
      <input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php echo $_SESSION['datepicker2'] ; ?>"  onchange="set_date_session(this.name,this.value)" />
      <input type="submit" name="search" id="search" value="    搜  尋   " /><input type="submit" name="print" id="print" value="    列  印   " />
      <?php //        <input type="button" name="peint" id="peint" value="列印"> ?>
      <input type="button" name="exit" id="exit" value="離開" />
      <input type="hidden" name="mm_insert" id="mm_insert" value="form1" /></form></td>
  </tr>
</table>
<?php
	if(isset($_POST['search'])){
		
		echo '<table border="1" width="800" bgcolor="#CCCCCC"><tr><td>3系-1經3系10nm</td></tr></table>';
		echo '<table border="1" width="800"><tr><td width="100">Customer</td><td width="120">LotNo</td><td width="420">Flow_Chart</td><td>Sheet_No</td></tr>';		
		
		echo_content(1,$_POST['datepicker1'],$_POST['datepicker2']);
		
		echo "</table>";
		echo '<table border="1" width="800" bgcolor="#CCCCCC"><tr><td>(3系-2經12nm) (3系-2經20nm)</td></tr></table>';
		echo '<table border="1" width="800"><tr><td width="100">Customer</td><td width="120">LotNo</td><td width="420">Flow_Chart</td><td>Sheet_No</td></tr>';		
		
		echo_content(2,$_POST['datepicker1'],$_POST['datepicker2']);
		
		echo "</table>";
		echo '<table border="1" width="800" bgcolor="#CCCCCC"><tr><td> 2系-AB經10nm </td></tr></table>';
		echo '<table border="1" width="800"><tr><td width="100">Customer</td><td width="120">LotNo</td><td width="420">Flow_Chart</td><td>Sheet_No</td></tr>';		
						
		echo_content(3,$_POST['datepicker1'],$_POST['datepicker2']);
		
		echo "</table>";
		echo '<table border="1" width="800" bgcolor="#CCCCCC"><tr><td> 2系-A經20nm </td></tr></table>';
		echo '<table border="1" width="800"><tr><td width="100">Customer</td><td width="120">LotNo</td><td width="420">Flow_Chart</td><td>Sheet_No</td></tr>';		
		
		echo_content(4,$_POST['datepicker1'],$_POST['datepicker2']);
		
		echo "</table>";
		echo '<table border="1" width="800" bgcolor="#CCCCCC"><tr><td> P050-000 offline P</td></tr></table>';
		echo '<table border="1" width="800"><tr><td width="100">Customer</td><td width="120">LotNo</td><td width="420">Flow_Chart</td><td>Sheet_No</td></tr>';		
		
		echo_content_('P050-000','TLAQA9110900',5,$_POST['datepicker1'],$_POST['datepicker2']);
		
		echo "</table>";
		echo '<table border="1" width="800" bgcolor="#CCCCCC"><tr><td> P006-000 offline P</td></tr></table>';
		echo '<table border="1" width="800"><tr><td width="100">Customer</td><td width="120">LotNo</td><td width="420">Flow_Chart</td><td>Sheet_No</td></tr>';		
		
		echo_content_('P006-000','TLAQA9110900',6,$_POST['datepicker1'],$_POST['datepicker2']);
		
		echo "</table>";
		echo '<table border="1" width="800" bgcolor="#CCCCCC"><tr><td> P006-000 PMS</td></tr></table>';
		echo '<table border="1" width="800"><tr><td width="100">Customer</td><td width="120">LotNo</td><td width="420">Flow_Chart</td><td>Sheet_No</td></tr>';		
		
		echo_content_('P006-000','TLAQA9110901',7,$_POST['datepicker1'],$_POST['datepicker2']);
		
		echo "</table>";
		echo '<table border="1" width="800" bgcolor="#CCCCCC"><tr><td> P050-000 PMS</td></tr></table>';
		echo '<table border="1" width="800"><tr><td width="100">Customer</td><td width="120">LotNo</td><td width="420">Flow_Chart</td><td>Sheet_No</td></tr>';		
		
		echo_content(8,$_POST['datepicker1'],$_POST['datepicker2'],'P050-000');
		
		echo "</table><br><br>";
	}


if(isset($_POST['print']))
{
//	$objPHPExcel->removeSheetByIndex(5);
	
	$objPHPExcel->setActiveSheetIndex(1);
	$i=0;$j=1;
	$query1="SELECT flow_txt AS fl FROM H2SO4_flow_chart_sheet WHERE (sheet_no = 1)";		
	$result1=mssql_query($query1);
	while($row1=mssql_fetch_array($result1)){

	$query="SELECT DISTINCT Fill_Flow_Chart.flow, QC_LotData.LotNo, QC_LotData.CustNo
FROM              QC_LotData INNER JOIN
                            Fill_Flow_Chart ON QC_LotData.LotNo = Fill_Flow_Chart.Lot_No
WHERE          (QC_LotData.TestDate >= '".gts($_POST['datepicker1'])." 00:00:00') AND (QC_LotData.TestDate <= '".gts($_POST['datepicker2'])." 23:59:59') AND 
                            (QC_LotData.Chemical = 'H2SO4') AND (Fill_Flow_Chart.flow <> N'') and (Fill_Flow_Chart.flow like '%".trim($row1['fl'])."%') ORDER BY   QC_LotData.LotNo";
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("A".($i+4),$row['CustNo']);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("B".($i+4),$row['LotNo']);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("C".($i+4),iconv("big5","utf-8","充填中"));
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("P".($i+4),iconv("big5","utf-8","內液反壓"));
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("AD".($i+4),iconv("big5","utf-8",$row['flow']));
		//PMS
		$b1=new sinetics;
		$b1->lotno=$row['LotNo'];
		$b1->table='TLAQA9110901';
		$b1->x1();	
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("Q".($i+4),$b1->value1);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("R".($i+4),$b1->value2);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("S".($i+4),$b1->value3);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("T".($i+4),$b1->value4);
		//FPMS
		$b1=new sinetics;
		$b1->lotno=$row['LotNo'];
		$b1->table='TLAQA9110901F';
		$b1->x1();
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("E".($i+4),$b1->value1);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("G".($i+4),$b1->value2);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("F".($i+4),$b1->value3);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("D".($i+4),$b1->value4);
		//RION
		$b1=new sinetics;
		$b1->lotno=$row['LotNo'];
		$b1->table='TLRIO9110900_';
		$b1->x1();
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("U".($i+4),$b1->value1);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("V".($i+4),$b1->value2);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("W".($i+4),$b1->value3);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("X".($i+4),$b1->value4);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("Y".($i+4),$b1->value5);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("Z".($i+4),$b1->value6);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("AA".($i+4),$b1->value7);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("AB".($i+4),$b1->value8);		
		//FRION
		$b1=new sinetics;
		$b1->lotno=$row['LotNo'];
		$b1->table='TSRIO9110900';
		$b1->x1();
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("H".($i+4),$b1->value1);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("I".($i+4),$b1->value2);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("K".($i+4),$b1->value3);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("L".($i+4),$b1->value4);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("M".($i+4),$b1->value5);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("N".($i+4),$b1->value6);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("O".($i+4),$b1->value7);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("J".($i+4),$b1->value8);
		
		$i++;
	}
		}
	
		
	$objPHPExcel->setActiveSheetIndex(2);
	$i=0;$j=2;
	$query1="SELECT flow_txt AS fl FROM H2SO4_flow_chart_sheet WHERE (sheet_no = 2)";		
	$result1=mssql_query($query1);
	while($row1=mssql_fetch_array($result1)){

	$query="SELECT DISTINCT Fill_Flow_Chart.flow, QC_LotData.LotNo, QC_LotData.CustNo
FROM              QC_LotData INNER JOIN
                            Fill_Flow_Chart ON QC_LotData.LotNo = Fill_Flow_Chart.Lot_No
WHERE          (QC_LotData.TestDate >= '".gts($_POST['datepicker1'])." 00:00:00') AND (QC_LotData.TestDate <= '".gts($_POST['datepicker2'])." 23:59:59') AND 
                            (QC_LotData.Chemical = 'H2SO4') AND (Fill_Flow_Chart.flow <> N'') and (Fill_Flow_Chart.flow like '%".trim($row1['fl'])."%') ORDER BY   QC_LotData.LotNo";
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("A".($i+4),$row['CustNo']);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("B".($i+4),$row['LotNo']);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("C".($i+4),iconv("big5","utf-8","充填中"));
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("H".($i+4),iconv("big5","utf-8","內液反壓"));
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("N".($i+4),iconv("big5","utf-8",$row['flow']));
		//FPMS
		$b1=new sinetics;
		$b1->lotno=$row['LotNo'];
		$b1->table='TLAQA9110901F';
		$b1->x1();	
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("D".($i+4),$b1->value1);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("E".($i+4),$b1->value2);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("F".($i+4),$b1->value3);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("G".($i+4),$b1->value4);
		
		$b1=new sinetics;
		$b1->lotno=$row['LotNo'];
		$b1->table='TLAQA9110901';
		$b1->x1();	
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("I".($i+4),$b1->value1);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("J".($i+4),$b1->value2);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("K".($i+4),$b1->value3);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("L".($i+4),$b1->value4);
		
		$i++;
	}}
	
	$objPHPExcel->setActiveSheetIndex(3);
	$i=0;$j=3;
	$query1="SELECT flow_txt AS fl FROM H2SO4_flow_chart_sheet WHERE (sheet_no = 3)";		
	$result1=mssql_query($query1);
	while($row1=mssql_fetch_array($result1)){

	$query="SELECT DISTINCT Fill_Flow_Chart.flow, QC_LotData.LotNo, QC_LotData.CustNo
FROM              QC_LotData INNER JOIN
                            Fill_Flow_Chart ON QC_LotData.LotNo = Fill_Flow_Chart.Lot_No
WHERE          (QC_LotData.TestDate >= '".gts($_POST['datepicker1'])." 00:00:00') AND (QC_LotData.TestDate <= '".gts($_POST['datepicker2'])." 23:59:59') AND 
                            (QC_LotData.Chemical = 'H2SO4') AND (Fill_Flow_Chart.flow <> N'') and (Fill_Flow_Chart.flow like '%".trim($row1['fl'])."%') ORDER BY   QC_LotData.LotNo";
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("A".($i+4),$row['CustNo']);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("B".($i+4),$row['LotNo']);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("C".($i+4),iconv("big5","utf-8","充填中"));
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("P".($i+4),iconv("big5","utf-8","內液反壓"));
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("AD".($i+4),iconv("big5","utf-8",$row['flow']));
		//PMS
		$b1=new sinetics;
		$b1->lotno=$row['LotNo'];
		$b1->table='TLAQA9110901';
		$b1->x1();	
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("Q".($i+4),$b1->value1);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("R".($i+4),$b1->value2);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("S".($i+4),$b1->value3);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("T".($i+4),$b1->value4);
		//FPMS
		$b1=new sinetics;
		$b1->lotno=$row['LotNo'];
		$b1->table='TLAQA9110901F';
		$b1->x1();
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("D".($i+4),$b1->value1);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("E".($i+4),$b1->value2);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("F".($i+4),$b1->value3);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("G".($i+4),$b1->value4);
		//RION
		$b1=new sinetics;
		$b1->lotno=$row['LotNo'];
		$b1->table='TLRIO9110900_';
		$b1->x1();
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("U".($i+4),$b1->value1);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("V".($i+4),$b1->value2);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("W".($i+4),$b1->value3);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("X".($i+4),$b1->value4);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("Y".($i+4),$b1->value5);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("Z".($i+4),$b1->value6);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("AA".($i+4),$b1->value7);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("AB".($i+4),$b1->value8);		
		//FRION
		$b1=new sinetics;
		$b1->lotno=$row['LotNo'];
		$b1->table='TSRIO9110900';
		$b1->x1();
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("H".($i+4),$b1->value1);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("I".($i+4),$b1->value2);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("K".($i+4),$b1->value3);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("L".($i+4),$b1->value4);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("M".($i+4),$b1->value5);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("N".($i+4),$b1->value6);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("O".($i+4),$b1->value7);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("J".($i+4),$b1->value8);
		
		$i++;
	}}
	
	$objPHPExcel->setActiveSheetIndex(4);
	$i=0;$j=4;
	$query1="SELECT flow_txt AS fl FROM H2SO4_flow_chart_sheet WHERE (sheet_no = 4)";		
	$result1=mssql_query($query1);
	while($row1=mssql_fetch_array($result1)){

	$query="SELECT DISTINCT Fill_Flow_Chart.flow, QC_LotData.LotNo, QC_LotData.CustNo
FROM              QC_LotData INNER JOIN
                            Fill_Flow_Chart ON QC_LotData.LotNo = Fill_Flow_Chart.Lot_No
WHERE          (QC_LotData.TestDate >= '".gts($_POST['datepicker1'])." 00:00:00') AND (QC_LotData.TestDate <= '".gts($_POST['datepicker2'])." 23:59:59') AND 
                            (QC_LotData.Chemical = 'H2SO4') AND (Fill_Flow_Chart.flow <> N'') and (Fill_Flow_Chart.flow like '%".trim($row1['fl'])."%') ORDER BY   QC_LotData.LotNo";
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("A".($i+4),$row['CustNo']);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("B".($i+4),$row['LotNo']);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("C".($i+4),iconv("big5","utf-8","充填中"));
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("P".($i+4),iconv("big5","utf-8","內液反壓"));
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("AD".($i+4),iconv("big5","utf-8",$row['flow']));
		//PMS
		$b1=new sinetics;
		$b1->lotno=$row['LotNo'];
		$b1->table='TLAQA9110901';
		$b1->x1();	
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("Q".($i+4),$b1->value1);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("R".($i+4),$b1->value2);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("S".($i+4),$b1->value3);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("T".($i+4),$b1->value4);
		//FPMS
		$b1=new sinetics;
		$b1->lotno=$row['LotNo'];
		$b1->table='TLAQA9110901F';
		$b1->x1();
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("D".($i+4),$b1->value1);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("E".($i+4),$b1->value2);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("F".($i+4),$b1->value3);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("G".($i+4),$b1->value4);
		//RION
		$b1=new sinetics;
		$b1->lotno=$row['LotNo'];
		$b1->table='TLRIO9110900_';
		$b1->x1();
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("U".($i+4),$b1->value1);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("V".($i+4),$b1->value2);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("W".($i+4),$b1->value3);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("X".($i+4),$b1->value4);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("Y".($i+4),$b1->value5);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("Z".($i+4),$b1->value6);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("AA".($i+4),$b1->value7);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("AB".($i+4),$b1->value8);		
		//FRION
		$b1=new sinetics;
		$b1->lotno=$row['LotNo'];
		$b1->table='TSRIO9110900';
		$b1->x1();
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("H".($i+4),$b1->value1);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("I".($i+4),$b1->value2);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("K".($i+4),$b1->value3);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("L".($i+4),$b1->value4);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("M".($i+4),$b1->value5);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("N".($i+4),$b1->value6);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("O".($i+4),$b1->value7);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("J".($i+4),$b1->value8);
		
		$i++;
	}}
	
	$objPHPExcel->setActiveSheetIndex(5);
	$i=0;$j=5;
	$query="SELECT DISTINCT QC_LotData.LotNo, QC_LotData.CustNo
FROM              QC_LotData WHERE (QC_LotData.TestDate >= '".gts($_POST['datepicker1'])." 00:00:00') AND (QC_LotData.TestDate <= '".gts($_POST['datepicker2'])." 23:59:59') AND (QC_LotData.Chemical = 'H2SO4') AND (ProdNo = 'P050-000') AND (OrgTable = 'TLAQA9110900') ORDER BY   QC_LotData.LotNo";
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("A".($i+4),$row['CustNo']);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("B".($i+4),$row['LotNo']);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("C".($i+4),iconv("big5","utf-8","內液反壓"));
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("I".($i+4),iconv("big5","utf-8",$row['flow']));
		//FPMS
		$b1=new sinetics;
		$b1->lotno=$row['LotNo'];
		$b1->table='TLAQA9110900';
		$b1->x1();	
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("D".($i+4),$b1->value1);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("E".($i+4),$b1->value2);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("F".($i+4),$b1->value3);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("G".($i+4),$b1->value4);
		$i++;
	}
	
	$objPHPExcel->setActiveSheetIndex(6);
	$i=0;$j=6;
	$query="SELECT DISTINCT QC_LotData.LotNo, QC_LotData.CustNo FROM QC_LotData WHERE (QC_LotData.TestDate >= '".gts($_POST['datepicker1'])." 00:00:00') AND (QC_LotData.TestDate <= '".gts($_POST['datepicker2'])." 23:59:59') AND (QC_LotData.Chemical = 'H2SO4') AND (ProdNo = 'P006-000') AND (OrgTable = 'TLAQA9110900') ORDER BY   QC_LotData.LotNo";
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("A".($i+4),$row['CustNo']);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("B".($i+4),$row['LotNo']);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("C".($i+4),iconv("big5","utf-8","內液反壓"));
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("I".($i+4),iconv("big5","utf-8",$row['flow']));
		//FPMS
		$b1=new sinetics;
		$b1->lotno=$row['LotNo'];
		$b1->table='TLAQA9110900';
		$b1->x1();	
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("D".($i+4),$b1->value1);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("E".($i+4),$b1->value2);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("F".($i+4),$b1->value3);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("G".($i+4),$b1->value4);
		$i++;
	}
	
		
	$objPHPExcel->setActiveSheetIndex(7);
	$i=0;$j=7;
	$query="SELECT DISTINCT QC_LotData.LotNo, QC_LotData.CustNo FROM QC_LotData WHERE (QC_LotData.TestDate >= '".gts($_POST['datepicker1'])." 00:00:00') AND (QC_LotData.TestDate <= '".gts($_POST['datepicker2'])." 23:59:59') AND (QC_LotData.Chemical = 'H2SO4') AND (ProdNo = 'P006-000') AND (OrgTable = 'TLAQA9110901') ORDER BY   QC_LotData.LotNo";
	// echo $query.'<BR>';
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("A".($i+4),$row['CustNo']);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("B".($i+4),$row['LotNo']);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("C".($i+4),iconv("big5","utf-8","充填中"));
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("H".($i+4),iconv("big5","utf-8","內液反壓"));
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("N".($i+4),iconv("big5","utf-8",$row['flow']));
		//FPMS
		$b1=new sinetics;
		$b1->lotno=$row['LotNo'];
		$b1->table='TLAQA9110901F';
		$b1->x1();	
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("D".($i+4),$b1->value1);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("E".($i+4),$b1->value2);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("F".($i+4),$b1->value3);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("G".($i+4),$b1->value4);
		
		$b1=new sinetics;
		$b1->lotno=$row['LotNo'];
		$b1->table='TLAQA9110901';
		$b1->x1();	
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("I".($i+4),$b1->value1);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("J".($i+4),$b1->value2);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("K".($i+4),$b1->value3);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("L".($i+4),$b1->value4);
		
		$i++;
	}
	
	$objPHPExcel->setActiveSheetIndex(8);
	$i=0;$j=8;
	$query1="SELECT flow_txt AS fl FROM H2SO4_flow_chart_sheet WHERE (sheet_no = 8)";		
	$result1=mssql_query($query1);
	while($row1=mssql_fetch_array($result1)){

	$query="SELECT DISTINCT Fill_Flow_Chart.flow, QC_LotData.LotNo, QC_LotData.CustNo
FROM              QC_LotData INNER JOIN
                            Fill_Flow_Chart ON QC_LotData.LotNo = Fill_Flow_Chart.Lot_No
WHERE          (QC_LotData.TestDate >= '".gts($_POST['datepicker1'])." 00:00:00') AND (QC_LotData.TestDate <= '".gts($_POST['datepicker2'])." 23:59:59') AND 
                            (QC_LotData.Chemical = 'H2SO4') and (QC_LotData.ProdNo = 'P050-000') AND (Fill_Flow_Chart.flow <> N'') and (Fill_Flow_Chart.flow like '%".trim($row1['fl'])."%') ORDER BY   QC_LotData.LotNo";
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("A".($i+4),$row['CustNo']);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("B".($i+4),$row['LotNo']);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("C".($i+4),iconv("big5","utf-8","充填中"));
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("H".($i+4),iconv("big5","utf-8","內液反壓"));
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("N".($i+4),iconv("big5","utf-8",$row['flow']));
		//FPMS
		$b1=new sinetics;
		$b1->lotno=$row['LotNo'];
		$b1->table='TLAQA9110901F';
		$b1->x1();	
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("D".($i+4),$b1->value1);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("E".($i+4),$b1->value2);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("F".($i+4),$b1->value3);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("G".($i+4),$b1->value4);
		
		$b1=new sinetics;
		$b1->lotno=$row['LotNo'];
		$b1->table='TLAQA9110901';
		$b1->x1();	
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("I".($i+4),$b1->value1);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("J".($i+4),$b1->value2);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("K".($i+4),$b1->value3);
		$objPHPExcel->setActiveSheetIndex($j)->setCellValue("L".($i+4),$b1->value4);
		
		$i++;
		}	
	}
	
	// Copy worksheets from $objPHPExcel2 to $objPHPExcel1 
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
	$objWriter->setIncludeCharts(TRUE); 
	$objWriter->save("../cal/tmp/tmp.xlsx"); 
	echo '<script>document.location.href="http://'.$path_root.'/cal/tmp/tmp.xlsx";</script>';	
}

function echo_content($sheetno,$da1,$da2,$pid){
$query1="SELECT flow_txt AS fl FROM H2SO4_flow_chart_sheet WHERE (sheet_no = ".$sheetno.")";		
		$result1=mssql_query($query1);
		while($row1=mssql_fetch_array($result1)){
			$query="SELECT DISTINCT Fill_Flow_Chart.flow as flo, QC_LotData.LotNo as lotno,  QC_LotData.CustNo as cno  
FROM              QC_LotData INNER JOIN Fill_Flow_Chart ON QC_LotData.LotNo = Fill_Flow_Chart.Lot_No WHERE (QC_LotData.TestDate >= '".gts($da1)." 00:00:00') and (QC_LotData.TestDate <= '".gts($da2)." 23:59:59') AND (QC_LotData.Chemical = 'H2SO4') AND (Fill_Flow_Chart.flow LIKE '%".trim($row1['fl'])."%')  AND (QC_LotData.ProdNo like '".$pid."%') ORDER BY   QC_LotData.LotNo";		
// echo $query;	
			$result=mssql_query($query);
			while($row=mssql_fetch_array($result)){
				echo '<tr><td>'.$row['cno'].'</td><td>'.$row['lotno'].'</td><td>'.$row['flo'].'</td><td>'.$sheetno.'</td></tr>'	;
			}
		}	
	
}

function echo_content_($pid,$table,$sheetno,$da1,$da2){
	$query="SELECT DISTINCT QC_LotData.LotNo as lotno,  QC_LotData.CustNo as cno  
FROM              QC_LotData INNER JOIN Fill_Flow_Chart ON QC_LotData.LotNo = Fill_Flow_Chart.Lot_No WHERE (QC_LotData.TestDate >= '".gts($da1)." 00:00:00') and (QC_LotData.TestDate <= '".gts($da2)." 23:59:59') AND (QC_LotData.Chemical = 'H2SO4') AND (Fill_Flow_Chart.flow <> N'') AND (OrgTable = '".$table."') and  (ProdNo = '".$pid."') ORDER BY   QC_LotData.LotNo";	
//	echo $query."<BR>";		
			$result=mssql_query($query);
			while($row=mssql_fetch_array($result)){
				echo '<tr><td>'.$row['cno'].'</td><td>'.$row['lotno'].'</td><td>'.$row['flo'].'</td><td>'.$sheetno.'</td></tr>'	;
			}
}

?>