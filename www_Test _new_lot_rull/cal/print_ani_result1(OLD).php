
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
	<?php
	
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
include  "../PHPEXCEL/Classes/PHPExcel.php";
include "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
lasturl();

?>

<title>列印</title>
<form id="form1" name="form1" method="post" action="">
	
    <?php 
	if(trim($_GET['pdd_prod_no'])=='GM-CRP' or trim($_GET['pdd_prod_no'])=='GM-CRBP')
	{
		echo '<input name="submit1" type="submit" id="submit1" value=" 舊版列印 ">
   		<input name="submit2" type="submit" id="submit2" value=" 下載原始檔(舊版) ">
		<br><input name="submit3" type="submit" id="submit3" value=" 新版列印 ">
   		<input name="submit4" type="submit" id="submit4" value=" 下載原始檔(新版) ">';
	}
	else
	{
		echo '<input name="submit1" type="submit" id="submit1" value=" 列印 ">
   		<input name="submit2" type="submit" id="submit2" value=" 下載原始檔 ">';
	}
	
	 ?>
   
   
<input type="submit" name="leave" id="leave" value=" 離 開 " />





<?PHP
$path_root=$_SERVER['HTTP_HOST'];
if (isset($_POST["submit3"]))
{
	$objPHPExcel = new PHPExcel();	
	$objPHPExcel = PHPExcel_IOFactory::load("..//FormList/PrintAnalyze/GM/new/".trim($_GET['pdd_prod_no']).".xlsx");
	$objPHPExcel->setActiveSheetIndex(0);
	if(trim($_GET['pdd_prod_no'])=='GM-CRP' or trim($_GET['pdd_prod_no'])=='GM-CRBP')
	{	$_SESSION['hohoho']='gogogo';
	$NUM=4;$i=0;$times=0;$ENG='B';
		$query="select distinct top 14 lotno from ".$_GET['table']." where lotno like 'TAC%' order by lotno desc ";
		echo $query;
		$result = mssql_query($query);
		while($row = mssql_fetch_array($result))
		{$NUM='4';
			$query1="select * from ".$_GET['table']." where lotno='".$row['lotno']."'   order by serialno";
			$result1 = mssql_query($query1);
		while($row1 = mssql_fetch_array($result1))
		{
			if($times==0){$ENG='B';}
			if($times==1){$ENG='G';}
			elseif($times==2){$ENG='L';}
			elseif($times==3){$ENG='Q';}
			elseif($times==4){$ENG='V';}
			elseif($times==5){$ENG='AA';}
			elseif($times==6){$ENG='AF';}
			elseif($times==7){$ENG='AK';}
			elseif($times==8){$ENG='AP';}
			elseif($times==9){$ENG='AU';}
			elseif($times==10){$ENG='AZ';}
			elseif($times==11){$ENG='BE';}
			elseif($times==12){$ENG='BJ';}
			elseif($times==13){$ENG='BO';}
			elseif($times==14){$ENG='BU';}
			elseif($times==15){$ENG='CA';}
			elseif($times==16){$ENG='CF';}
			elseif($times==17){$ENG='CK';}
			elseif($times==18){$ENG='CP';}			
			$objPHPExcel->getActiveSheet()->setCellValue($ENG."2", iconv("big5","utf-8",substr($row1['AnalyzeTime'],0,8)));
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['PP01']));$ENG++;
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['PPG1']));$ENG++;
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['PP02']));$ENG++;
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['PP03']));$ENG++;
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['PP05']));$ENG++;
			$NUM++;
		}
		$times++;
		}
		$NUM=8;$i=0;$times=0;$ENG='B';
		$query="select distinct top 14 lotno from ".$_GET['table']." where lotno like 'TAB%' order by lotno desc ";
		echo $query.'<BR>';
		$result = mssql_query($query);
		while($row = mssql_fetch_array($result))
		{$NUM='8';
			$query1="select * from ".$_GET['table']." where lotno='".$row['lotno']."'   order by serialno";
			$result1 = mssql_query($query1);
		while($row1 = mssql_fetch_array($result1))
		{
			if($times==0){$ENG='B';}
			if($times==1){$ENG='G';}
			elseif($times==2){$ENG='L';}
			elseif($times==3){$ENG='Q';}
			elseif($times==4){$ENG='V';}
			elseif($times==5){$ENG='AA';}
			elseif($times==6){$ENG='AF';}
			elseif($times==7){$ENG='AK';}
			elseif($times==8){$ENG='AP';}
			elseif($times==9){$ENG='AU';}
			elseif($times==10){$ENG='AZ';}
			elseif($times==11){$ENG='BE';}
			elseif($times==12){$ENG='BJ';}
			elseif($times==13){$ENG='BO';}
			elseif($times==14){$ENG='BU';}
			elseif($times==15){$ENG='CA';}
			elseif($times==16){$ENG='CF';}
			elseif($times==17){$ENG='CK';}
			elseif($times==18){$ENG='CP';}			
			$objPHPExcel->getActiveSheet()->setCellValue($ENG."2", iconv("big5","utf-8",substr($row1['AnalyzeTime'],0,8)));
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['PP01']));$ENG++;
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['PPG1']));$ENG++;
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['PP02']));$ENG++;
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['PP03']));$ENG++;
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['PP05']));$ENG++;
			$NUM++;
		}
		$times++;
		}
	}	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
			$objWriter->save('../FormList/PrintAnalyze/GM/save/'.$_GET['pdd_prod_no'].'.xlsx');
		
			echo '</br>';
	echo "下載";
			echo '<script>document.location.href="http://'.$path_root.'/FormList/PrintAnalyze/GM/save/'.$_GET['pdd_prod_no'].'.xlsx";</script>';
}

if (isset($_POST["submit1"]))
{
	echo $_GET['pdd_prod_no'];
	$objPHPExcel = new PHPExcel();	
	$objPHPExcel = PHPExcel_IOFactory::load("..//FormList/PrintAnalyze/GM/".trim($_GET['pdd_prod_no']).".xlsx");

	$objPHPExcel->setActiveSheetIndex(0);
	if($_GET['pdd_prod_no']=='GM-CRI')
	{	
		$NUM=5;		
		
		$query="select top 5 * from ".$_GET['table']." where lotno='".$_GET['lot_no']."'  order by serialno";
	//	echo $query;
		$result = mssql_query($query);
		while($row = mssql_fetch_array($result))
			{	
				//echo "test".$NUM."";
				$ENG='B';
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row['F-']));$ENG++;
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row['CL']));$ENG++;
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row['Br-']));$ENG++;
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row['NO3']));$ENG++;
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row['PO4']));$ENG++;
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row['SO4']));$ENG++;$ENG++;
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row['FR']));$ENG++;
				$NUM++;
				
			}
				$objPHPExcel->setActiveSheetIndex(1);
				$NUM=4;$ENG='D';$i=1;
				
			$query="select distinct top 18 lotno from ".$_GET['table']." where lotno like '%GMI' order by lotno desc ";
			//echo $query;
			$result = mssql_query($query);
			while($row = mssql_fetch_array($result))
			{
				$query1="select * from ".$_GET['table']." where lotno='".$row['lotno']."'  order by serialno ";
			//	echo $query1;
				$result1 = mssql_query($query1);
			while($row1 = mssql_fetch_array($result1))
			{
				$F=($row1['F-']*0.03)/$row1['FR'];
				$CL=($row1['CL']*0.03)/$row1['FR'];
				$BR=($row1['Br-']*0.03)/$row1['FR'];
				$NO3=($row1['NO3']*0.03)/$row1['FR'];
				$PO4=($row1['PO4']*0.03)/$row1['FR'];
				$SO4=($row1['SO4']*0.03)/$row1['FR'];
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$F));$NUM++;
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$CL));$NUM++;
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$BR));$NUM++;
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$NO3));$NUM++;
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$PO4));$NUM++;
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$SO4));$NUM++;
				
			}$ENG++;$NUM=4;

				
			}
	}
//-------------------------------------------------------------------------------
	if($_GET['pdd_prod_no']=='TS06-K001'or $_GET['pdd_prod_no']=='TS01-K010')
	{
		$print='C10';
		$query="select top 1 * from ".$_GET['table']." where lotno='".$_GET['lot_no']."' and AnalyzeTime='".$_GET['AnalyzeTime']."' ";
		echo $query;
		
		$result = mssql_query($query);
		while($row = mssql_fetch_array($result))
		{			$objPHPExcel->getActiveSheet()->setCellValue("B5", iconv("big5","utf-8","標定日期:".substr($row['AnalyzeTime'],0,8)));
				
			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['Value']));$print='C19';
			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['R_Base']));$print='D17';	
			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['T3']));$print='D10';
			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['T2']));$print='C20';
			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['T1']));$print='D17';
			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['T3']));			
			
		}
	}
		
	if($_GET['pdd_prod_no']=='TS02-S100'or $_GET['pdd_prod_no']=='TS02-N100')
	{
		$print='C12';
		$query="select top 1 * from ".$_GET['table']." where lotno='".$_GET['lot_no']."' and AnalyzeTime='".$_GET['AnalyzeTime']."' ";
		echo $query;
		
		$result = mssql_query($query);
		while($row = mssql_fetch_array($result))
		{			$objPHPExcel->getActiveSheet()->setCellValue("B5", iconv("big5","utf-8","標定日期:".substr($row['AnalyzeTime'],0,8)));

			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['Value']));$print='C22';
			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['R_Base']));$print='D20';	
			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['T3']));$print='D12';
			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['T2']));$print='C23';
			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['T1']));$print='D20';
			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['T3']));			
			
		}
	}
		if($_GET['pdd_prod_no']=='TS07-N050')
	{
		$print='C12';
		$query="select top 1 * from ".$_GET['table']." where lotno='".$_GET['lot_no']."' and AnalyzeTime='".$_GET['AnalyzeTime']."' ";
		echo $query;
		
		$result = mssql_query($query);
		while($row = mssql_fetch_array($result))
		{			$objPHPExcel->getActiveSheet()->setCellValue("B5", iconv("big5","utf-8","標定日期:".substr($row['AnalyzeTime'],0,8)));

			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['Value']));$print='C21';
			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['R_Base']));$print='D19';	
			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['T3']));$print='D12';
			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['T2']));$print='C22';
			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['T1']));$print='D19';
			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['T3']));			
			
		}
	}
	if($_GET['pdd_prod_no']=='TS01-N001')
	{
		$print='C12';
		$query="select top 1 * from ".$_GET['table']." where lotno='".$_GET['lot_no']."' and AnalyzeTime='".$_GET['AnalyzeTime']."' ";
		echo $query;
		
		$result = mssql_query($query);
		while($row = mssql_fetch_array($result))
		{			$objPHPExcel->getActiveSheet()->setCellValue("B5", iconv("big5","utf-8","標定日期:".substr($row['AnalyzeTime'],0,8)));

			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['Value']));$print='C23';
			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['R_Base']));$print='D21';	
			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['T3']));$print='D12';
			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['T2']));$print='C26';
			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['T1']));$print='D21';
			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['T3']));			
			
		}
	}
	if($_GET['pdd_prod_no']=='TS06-N050' or $_GET['pdd_prod_no']=='TS04-N010')
	{
		$print='C12';
		$query="select top 1 * from ".$_GET['table']." where lotno='".$_GET['lot_no']."' and AnalyzeTime='".$_GET['AnalyzeTime']."' ";
		//echo $query;
		
		$result = mssql_query($query);
		while($row = mssql_fetch_array($result))
		{	
			$objPHPExcel->getActiveSheet()->setCellValue("B5", iconv("big5","utf-8","標定日期:".substr($row['AnalyzeTime'],0,8)));
			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['Value']));$print='C21';
			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['R_Base']));$print='D19';	
			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['T3']));$print='D12';
			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['T2']));$print='C23';
			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['T1']));$print='D19';
			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['T3']));			
			
		}
	}
	if($_GET['pdd_prod_no']=='TS08-F020')
	{
		$print='C18';
		$query="select top 1 * from ".$_GET['table']." where lotno='".$_GET['lot_no']."' and AnalyzeTime='".$_GET['AnalyzeTime']."' ";
	//	echo $query;
		
		$result = mssql_query($query);
		while($row = mssql_fetch_array($result))
		{			
		
		$objPHPExcel->getActiveSheet()->setCellValue("D16", iconv("big5","utf-8",substr($row['AnalyzeTime'],0,8)));
		$objPHPExcel->getActiveSheet()->setCellValue("H18", iconv("big5","utf-8",$row['Tester']));


			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['N1A1']));$print='C19';
			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['N2A1']));$print='D18';	
			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['N1K2']));$print='D19';
			$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['N2K2']));$print='C20';
			//$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['AvA1']));$print='D20';
			//$objPHPExcel->getActiveSheet()->setCellValue($print, iconv("big5","utf-8",$row['AvK2']));
			
		}
	}
	//--------------------------------------------------------------------------------------------------------------------
	if(trim($_GET['pdd_prod_no'])=='GM-CRP' or trim($_GET['pdd_prod_no'])=='GM-CRBP')
	{	$_SESSION['hohoho']='gogogo';
	$NUM=4;$i=0;$times=0;$ENG='B';
		$query="select distinct top 14 lotno from ".$_GET['table']." where lotno like 'TAC%' order by lotno desc ";
		echo $query;
		$result = mssql_query($query);
		while($row = mssql_fetch_array($result))
		{$NUM='4';
			$query1="select * from ".$_GET['table']." where lotno='".$row['lotno']."'   order by serialno";
			$result1 = mssql_query($query1);
		while($row1 = mssql_fetch_array($result1))
		{
			if($times==0){$ENG='B';}
			if($times==1){$ENG='G';}
			elseif($times==2){$ENG='L';}
			elseif($times==3){$ENG='Q';}
			elseif($times==4){$ENG='V';}
			elseif($times==5){$ENG='AA';}
			elseif($times==6){$ENG='AF';}
			elseif($times==7){$ENG='AK';}
			elseif($times==8){$ENG='AP';}
			elseif($times==9){$ENG='AU';}
			elseif($times==10){$ENG='AZ';}
			elseif($times==11){$ENG='BE';}
			elseif($times==12){$ENG='BJ';}
			elseif($times==13){$ENG='BO';}
			elseif($times==14){$ENG='BU';}
			elseif($times==15){$ENG='CA';}
			elseif($times==16){$ENG='CF';}
			elseif($times==17){$ENG='CK';}
			elseif($times==18){$ENG='CP';}			
			$objPHPExcel->getActiveSheet()->setCellValue($ENG."2", iconv("big5","utf-8",substr($row1['AnalyzeTime'],0,8)));
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['PP01']));$ENG++;
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['PPG1']));$ENG++;
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['PP02']));$ENG++;
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['PP03']));$ENG++;
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['PP05']));$ENG++;
			$NUM++;
		}
		$times++;
		}
		$NUM=8;$i=0;$times=0;$ENG='B';
		$query="select distinct top 14 lotno from ".$_GET['table']." where lotno like 'TAB%' order by lotno desc ";
		echo $query.'<BR>';
		$result = mssql_query($query);
		while($row = mssql_fetch_array($result))
		{$NUM='8';
			$query1="select * from ".$_GET['table']." where lotno='".$row['lotno']."'   order by serialno";
			$result1 = mssql_query($query1);
		while($row1 = mssql_fetch_array($result1))
		{
			if($times==0){$ENG='B';}
			if($times==1){$ENG='G';}
			elseif($times==2){$ENG='L';}
			elseif($times==3){$ENG='Q';}
			elseif($times==4){$ENG='V';}
			elseif($times==5){$ENG='AA';}
			elseif($times==6){$ENG='AF';}
			elseif($times==7){$ENG='AK';}
			elseif($times==8){$ENG='AP';}
			elseif($times==9){$ENG='AU';}
			elseif($times==10){$ENG='AZ';}
			elseif($times==11){$ENG='BE';}
			elseif($times==12){$ENG='BJ';}
			elseif($times==13){$ENG='BO';}
			elseif($times==14){$ENG='BU';}
			elseif($times==15){$ENG='CA';}
			elseif($times==16){$ENG='CF';}
			elseif($times==17){$ENG='CK';}
			elseif($times==18){$ENG='CP';}			
			$objPHPExcel->getActiveSheet()->setCellValue($ENG."2", iconv("big5","utf-8",substr($row1['AnalyzeTime'],0,8)));
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['PP01']));$ENG++;
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['PPG1']));$ENG++;
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['PP02']));$ENG++;
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['PP03']));$ENG++;
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['PP05']));$ENG++;
			$NUM++;
		}
		$times++;
		}
	}	
	if($_GET['pdd_prod_no']=='GM-NBP')
	{ 
		$NUM=4;$i=0;$times=0;$ENG='B';
		$query="select distinct top 14 lotno from ".$_GET['table']." where lotno like 'TAN%' order by lotno desc ";
		
		$result = mssql_query($query);
		while($row = mssql_fetch_array($result))
		{$NUM='4';
			$query1="select * from ".$_GET['table']." where lotno='".$row['lotno']."'  order by serialno ";
			//echo $query1;
			$result1 = mssql_query($query1);
		while($row1 = mssql_fetch_array($result1))
		{	
			if($times==0){$ENG='B';}
			if($times==1){$ENG='G';}
			elseif($times==2){$ENG='L';}
			elseif($times==3){$ENG='Q';}
			elseif($times==4){$ENG='V';}
			elseif($times==5){$ENG='AA';}
			elseif($times==6){$ENG='AF';}
			elseif($times==7){$ENG='AK';}
			elseif($times==8){$ENG='AP';}
			elseif($times==9){$ENG='AU';}
			elseif($times==10){$ENG='BA';}
			elseif($times==11){$ENG='BF';}
			elseif($times==12){$ENG='BK';}
			elseif($times==13){$ENG='BP';}
			elseif($times==14){$ENG='BU';}
			elseif($times==15){$ENG='CA';}
			elseif($times==16){$ENG='CF';}
			elseif($times==17){$ENG='CK';}
			elseif($times==18){$ENG='CP';}	
			$objPHPExcel->getActiveSheet()->setCellValue($ENG."2", iconv("big5","utf-8",substr($row1['AnalyzeTime'],0,8)));
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['PP01']));$ENG++;
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['PPG1']));$ENG++;
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['PP02']));$ENG++;
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['PP03']));$ENG++;
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['PP05']));$ENG++;
			$NUM++;
		}$times++;
			
		}
	}
	
		
				
//---------------------------------------------------------------------------------------------------			
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
			$objWriter->save('../FormList/PrintAnalyze/GM/save/'.$_GET['pdd_prod_no'].'.xlsx');
		
			echo '</br>';
	echo "下載";
			echo '<script>document.location.href="http://'.$path_root.'/FormList/PrintAnalyze/GM/save/'.$_GET['pdd_prod_no'].'.xlsx";</script>';
			
	
}
if(isset($_POST['submit2']))
		{		
			echo '</br>';
			echo "下載範本";
			//echo '"http://'.$path_root.'/FormList/PrintAnalyze/GM/'.$_GET['pdd_prod_no'].'.xlsx"';
		echo '<script>document.location.href="http://'.$path_root.'/FormList/PrintAnalyze/GM/'.$_GET['pdd_prod_no'].'.xlsx";</script>';
		}
//////////////////GM-CRP跟GM-CRBP新版範本檔
if(isset($_POST['submit4']))
		{		
			echo '</br>';
			echo "下載範本";
			//echo '"http://'.$path_root.'/FormList/PrintAnalyze/GM/'.$_GET['pdd_prod_no'].'.xlsx"';
		echo '<script>document.location.href="http://'.$path_root.'/FormList/PrintAnalyze/GM/new/'.$_GET['pdd_prod_no'].'.xlsx";</script>';
		}

?>