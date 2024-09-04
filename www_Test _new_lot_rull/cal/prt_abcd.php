<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php

	session_start();
	include("../connections/conn.php");
	include("../lib/fun.php");
	include("../lib/jtsai.php");
	$dat=date("YmdHis");
	include("../PHPEXCEL/Classes/PHPExcel.php");
	include "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
	$query="SELECT          AND_ITEM
			FROM              AnalyzeDesign
			WHERE          (AND_LOT_NO like '".$_GET['lot_no']."%')";
	//echo $query;
	$result = mssql_query($query);
	$row = mssql_fetch_row($result);
	$row[0];
	$str=$row[0].",";
	$aa=explode(',',$str,-1);
	$query="SELECT DISTINCT ANI_GROUPNAME
	FROM              AnalyzeItem
	WHERE          (ANI_INDEX <>'') and ";
	for($i=0;$i<count($aa);$i++){
	if ($i<(count($aa)-1)){
		$query.="(ANI_INDEX =".$aa[$i].") OR ";}
	else {$query.="(ANI_INDEX =".$aa[$i].")";}	
	}
	//echo $query;
	// echo '</br>'.$str.'</br>';
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	echo '	<form name="form1" method="post" action="'.$loginFormAction.'" >LOT NO.: '.$_GET['lot_no'].'<BR>選擇列印項目
			  <select name="group" id="group">';
	while($row = mssql_fetch_array($result)){
	echo '<option value="'.$row['ANI_GROUPNAME'].'">'.$row['ANI_GROUPNAME'].'</option>';	
	}
	echo '		</select>
	<input type="submit" name="print" id="print" value="列印">
			</form>';
			
	if($_POST['print'])
	{//	echo  $_POST['group'];
		if($_POST['group']==''){$_POST['group']=='M13';}
		
		if($_POST['group']!='TOC_H2SO4')
		{
		if($_POST['group']=='M13')
		{
			$i1=7;$i2=22;
		}
		elseif($_POST['group']=='M21')
		{
			$i1=7;$i2=28;
		}
		elseif($_POST['group']=='TT_B' or ($_POST['group']=='TT_Hg') or ($_POST['group']=='TT_Se') or ($_POST['group']=='TT_Si'))
		{
			$i1=10;$i2=10;
		}
		elseif($_POST['group']=='TT_P')
		{
			$i1=10;$i2=10;
		}
		elseif($_POST['group']=='TT')
		{
			$i1=10;$i2=11;
		}
		elseif($_POST['group']=='TT_Au')
		{
			$i1=9;$i2=9;
		}
		elseif($_POST['group']=='IC' or $_POST['group']=='NH4' or $_POST['group']=='F-')
		{
			$i1=12;$i2=14;
		}
		elseif($_POST['group']=='I')
		{
			$i1=11;$i2=14;
		}
		elseif($_POST['group']=='TOC_H2SO4')
		{
			$i1=11;$i2=14;
		}
		
		else
		{
			$i1=10;$i2=14;
		}
		$reader = PHPExcel_IOFactory::createReader('Excel2007'); // 讀取2007 excel 檔案
		$PHPExcel = $reader->load("../FormList/PrintAnalyze/RCIC/".$_GET['pid']."/".$_POST['group'].".xlsx"); // 檔案名稱 需已經上傳到主機上
		echo  $_POST['group'];
//		echo "../FormList/PrintAnalyze/RCIC/".$_GET['pid']."/".$_POST['group'].".xlsx<br>";
		$sheet = $PHPExcel->getSheet(0);
		$a1=array();
		for ($row = $i1; $row <= $i2; $row++) {
			$val = $sheet->getCellByColumnAndRow(0, $row)->getValue();
			$val = iconv("utf-8","big5",$val);
//			echo $val;
//			echo "<BR>";
			array_push($a1,$val);
		}	
		$b=$sheet->getCellByColumnAndRow(0,12)->getValue();
		
		
		$objPHPExcel = new PHPExcel();
		$objPHPExcel = PHPExcel_IOFactory::load("../FormList/PrintAnalyze/RCIC/".$_GET['pid']."/".$_POST['group'].".xlsx");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B3',iconv("big5","utf-8",$_GET['lot_no']));

		if($_POST['group']=='IC' or $_POST['group']=='NH4' or $_POST['group']=='F-' or $_POST['group']=='I')
		{
			///write A
		$query="select ".get_table($_POST['group'],$_GET['pid']).".*,EMPLOYEE_DATA.EMP_NAME from ".get_table($_POST['group'],$_GET['pid'])." left OUTER JOIN
                            analyze_first ON ".get_table($_POST['group'],$_GET['pid']).".LotNo = analyze_first.lot_no AND 
                            ".get_table($_POST['group'],$_GET['pid']).".AnalyzeTime = analyze_first.create_time left OUTER JOIN
                            EMPLOYEE_DATA ON analyze_first.first = EMPLOYEE_DATA.EMP_NO where LotNo='".$_GET['lot_no']."A' order by AnalyzeTime";
							//echo $query;
		$result = mssql_query($query);
		while($row = mssql_fetch_array($result)){
			for($i=0;$i<count($a1);$i++)
			{
				
				$s=$i+$i1;
				$item[$a1[$i]]['A']=$row[$a1[$i]];
				if($_POST['group']=='I')
				{
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$s,iconv("big5","utf-8", $item[$a1[$i]]['A']));
				//echo $item[$a1[$i]]['A'];
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B4',substr(sta($row['AnalyzeTime']),0,10));
				}
				else
				{
					if($_POST['group']=='IC' or $_POST['group']=='NH4' or $_POST['group']=='F-'){
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$s,iconv("big5","utf-8", $item[$a1[$i]]['A']));
				//echo $item[$a1[$i]]['A'];
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B4',substr(sta($row['AnalyzeTime']),0,10));
					}
					else{
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$s,iconv("big5","utf-8", $item[$a1[$i]]['A']));
				//echo $item[$a1[$i]]['A'];
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B4',substr(sta($row['AnalyzeTime']),0,10));
					}
				}
			}
		}
		///write B
		$query="select ".get_table($_POST['group'],$_GET['pid']).".*,EMPLOYEE_DATA.EMP_NAME from ".get_table($_POST['group'],$_GET['pid'])." left OUTER JOIN
                            analyze_first ON ".get_table($_POST['group'],$_GET['pid']).".LotNo = analyze_first.lot_no AND 
                            ".get_table($_POST['group'],$_GET['pid']).".AnalyzeTime = analyze_first.create_time left OUTER JOIN
                            EMPLOYEE_DATA ON analyze_first.first = EMPLOYEE_DATA.EMP_NO where LotNo='".$_GET['lot_no']."B' order by AnalyzeTime";
		$result = mssql_query($query);
		//echo $query;
		while($row = mssql_fetch_array($result)){
			for($i=0;$i<count($a1);$i++)
			{
				$s=$i+$i1;
				$item[$a1[$i]]['B']=$row[$a1[$i]];
				//echo $item[$a1[$i]]['B'];
				if($_POST['group']=='I')
				{
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$s,iconv("big5","utf-8", $item[$a1[$i]]['B']));
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B4',substr(sta($row['AnalyzeTime']),0,10));
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$s,$row[$a1[$i]."_R"]);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$s,$row[$a1[$i]."_CHK1"]);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$s,$row[$a1[$i]."_CHK2"]);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K11',iconv("big5","utf-8",$row['Tester']));


					
				
				}
				else
				{
				if($_POST['group']=='IC' or $_POST['group']=='NH4' or $_POST['group']=='F-'){
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$s,iconv("big5","utf-8", $item[$a1[$i]]['B']));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B4',substr(sta($row['AnalyzeTime']),0,10));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$s,$row[$a1[$i]."_R"]);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$s,$row[$a1[$i]."_CHK1"]);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$s,$row[$a1[$i]."_CHK2"]);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K12',iconv("big5","utf-8",$row['Tester']));
				}
				else{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$s,iconv("big5","utf-8", $item[$a1[$i]]['B']));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B4',substr(sta($row['AnalyzeTime']),0,10));
				}
				}			
			}
		}
		}//if
		
		
		else
		{
			//echo "test";
		///write A
		$query="select ".get_table($_POST['group'],$_GET['pid']).".*,EMPLOYEE_DATA.EMP_NAME from ".get_table($_POST['group'],$_GET['pid'])." left OUTER JOIN
                            analyze_first ON ".get_table($_POST['group'],$_GET['pid']).".LotNo = analyze_first.lot_no AND 
                            ".get_table($_POST['group'],$_GET['pid']).".AnalyzeTime = analyze_first.create_time left OUTER JOIN
                            EMPLOYEE_DATA ON analyze_first.first = EMPLOYEE_DATA.EMP_NO where LotNo='".$_GET['lot_no']."A' order by AnalyzeTime";
							//echo $query;
		$result = mssql_query($query);
		while($row = mssql_fetch_array($result)){
			for($i=0;$i<count($a1);$i++)
			{
				$s=$i+$i1;
				$item[$a1[$i]]['A']=$row[$a1[$i]];
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$s,iconv("big5","utf-8", $item[$a1[$i]]['A']));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B4',substr(sta($row['AnalyzeTime']),0,10));
			}
		}
		///write B
		$query="select ".get_table($_POST['group'],$_GET['pid']).".*,EMPLOYEE_DATA.EMP_NAME from ".get_table($_POST['group'],$_GET['pid'])." left OUTER JOIN
                            analyze_first ON ".get_table($_POST['group'],$_GET['pid']).".LotNo = analyze_first.lot_no AND 
                            ".get_table($_POST['group'],$_GET['pid']).".AnalyzeTime = analyze_first.create_time left OUTER JOIN
                            EMPLOYEE_DATA ON analyze_first.first = EMPLOYEE_DATA.EMP_NO where LotNo='".$_GET['lot_no']."B' order by AnalyzeTime";
		$result = mssql_query($query);
		while($row = mssql_fetch_array($result)){
			for($i=0;$i<count($a1);$i++)
			{
				$s=$i+$i1;
				$item[$a1[$i]]['B']=$row[$a1[$i]];
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$s,iconv("big5","utf-8", $item[$a1[$i]]['B']));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B4',substr(sta($row['AnalyzeTime']),0,10));
			}
		}
		///write C
		$query="select ".get_table($_POST['group'],$_GET['pid']).".*,EMPLOYEE_DATA.EMP_NAME from ".get_table($_POST['group'],$_GET['pid'])." left OUTER JOIN
                            analyze_first ON ".get_table($_POST['group'],$_GET['pid']).".LotNo = analyze_first.lot_no AND 
                            ".get_table($_POST['group'],$_GET['pid']).".AnalyzeTime = analyze_first.create_time left OUTER JOIN
                            EMPLOYEE_DATA ON analyze_first.first = EMPLOYEE_DATA.EMP_NO where LotNo='".$_GET['lot_no']."C'  order by AnalyzeTime";
							//echo $query;
		$result = mssql_query($query);
		
		while($row = mssql_fetch_array($result)){
			for($i=0;$i<count($a1);$i++)
			{
				$s=$i+$i1;
				
				$item[$a1[$i]]['C']=$row[$a1[$i]];
		//		echo $row[$a1[$i]];
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$s,iconv("big5","utf-8", $item[$a1[$i]]['C']));
			//	echo $item[$a1[$i]]['C'];
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B4',substr(sta($row['AnalyzeTime']),0,10));
			}
		}
		///write D
		$query="select ".get_table($_POST['group'],$_GET['pid']).".*,EMPLOYEE_DATA.EMP_NAME from ".get_table($_POST['group'],$_GET['pid'])." left OUTER JOIN
                            analyze_first ON ".get_table($_POST['group'],$_GET['pid']).".LotNo = analyze_first.lot_no AND 
                            ".get_table($_POST['group'],$_GET['pid']).".AnalyzeTime = analyze_first.create_time left OUTER JOIN
                            EMPLOYEE_DATA ON analyze_first.first = EMPLOYEE_DATA.EMP_NO where LotNo='".$_GET['lot_no']."D' order by AnalyzeTime";
		$result = mssql_query($query);
		while($row = mssql_fetch_array($result)){
			for($i=0;$i<count($a1);$i++)
			{
				$s=$i+$i1;
				$item[$a1[$i]]['D']=$row[$a1[$i]];
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$s,iconv("big5","utf-8", $item[$a1[$i]]['D']));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B4',substr(sta($row['AnalyzeTime']),0,10));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$s,iconv("big5","utf-8", $row['EMP_NAME']));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$s,iconv("big5","utf-8", $row['Tester']));
			}
		}
		}//else
		}//if($_POST['group']!='TOC_H2SO4')
		if($_POST['group']=='TOC_H2SO4')
		{
			$objPHPExcel = new PHPExcel();
			$objPHPExcel = PHPExcel_IOFactory::load("../FormList/PrintAnalyze/RCIC/".$_GET['pid']."/".$_POST['group'].".xlsx");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D6',iconv("big5","utf-8",$_GET['lot_no']));
			$query="select ".get_table($_POST['group'],$_GET['pid']).".*,EMPLOYEE_DATA.EMP_NAME 
			from ".get_table($_POST['group'],$_GET['pid'])." left OUTER JOIN
			analyze_first ON ".get_table($_POST['group'],$_GET['pid']).".LotNo = analyze_first.lot_no AND 
			".get_table($_POST['group'],$_GET['pid']).".AnalyzeTime = analyze_first.create_time left OUTER JOIN
			EMPLOYEE_DATA ON analyze_first.first = EMPLOYEE_DATA.EMP_NO 
			where LotNo='".$_GET['lot_no']."C' or  LotNo='".$_GET['lot_no']."D'  order by AnalyzeTime";
			$result = mssql_query($query);
			$ENG='D';$NUM='12';
			while($row = mssql_fetch_array($result))
			{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D4',iconv("big5","utf-8",ttd($row['AnalyzeTime'])));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D7',iconv("big5","utf-8",$row['SampleNo']));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N12',iconv("big5","utf-8",$row['Tester']));

				$one=1;
				for($i=1;$i<=4;$i++)
				{
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($ENG.$NUM,iconv("big5","utf-8", $row['TEN_'.$one.'']));
					$ENG++;$one++;
				}
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($ENG.$NUM,iconv("big5","utf-8", $row['AVG_TEN']));
				$ENG='D';$NUM++;

			}
		}
		if($b=='value1'){$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A12',iconv("big5","utf-8","NH4+"));}
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
		$objWriter->save("../tmp/abcd.xlsx");
		echo '<script>document.location.href="http://'.$_SERVER['HTTP_HOST'].'/tmp/abcd.xlsx";</script>';	
	}
?>

