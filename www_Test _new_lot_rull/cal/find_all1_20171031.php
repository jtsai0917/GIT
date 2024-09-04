<meta http-equiv="Content-Type" content="text/html; charset=big5" />
	<?php
	//ini_set("memory_limit","2048M");
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
include  "../PHPEXCEL/Classes/PHPExcel.php";
include "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
lasturl();
datepick();
?>
 </br><form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
 以下項目皆為必填          &emsp;&emsp;&emsp;&emsp;&emsp; 
<br>  
特別查詢
日期： <input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php if($_SESSION['datepicker1']){echo $_SESSION['datepicker1'];}?>" onChange="set_date_session(this.name,this.value)">
~
<input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php if($_SESSION['datepicker2']){echo $_SESSION['datepicker2'];}?>" onChange="set_date_session(this.name,this.value)"></td> 

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
        檢驗項目：
          <?php select_ani_group();?>
<input name="submit1" type="submit" id="submit1" value=" 指定藥品查詢 ">
<input name="submit10" type="submit" id="submit10" value=" 相關藥品查詢 ">

<input name="submit3" type="submit" id="submit3" value=" 再分析查詢 ">
 

<input name="submit2" type="submit" id="submit2" value=" 下載報告 "><br />



<?php
$path_root=$_SERVER['HTTP_HOST'];

		if(isset($_POST['submit2']))
		{
			
			echo '</br>';
			echo "下載";
		echo '<script>document.location.href="http://'.$path_root.'/cal/find2.xlsx";</script>';
		
		}
		
if(isset($_POST['submit1']))
{
	if($_POST['selected_group']!='0' and $_POST['pdd_chemical1']!='' )
	{	
		$objPHPExcel = new PHPExcel();
		$objPHPExcel = PHPExcel_IOFactory::load("./QC1.xlsx");
		$objPHPExcel->setActiveSheetIndex(0);
		
		$query="select * from PRODUCT_DATA where PDD_PROD_NO='".$_POST['pdd_chemical1']."' ";
		$result = mssql_query($query);
		while ($row = mssql_fetch_array($result))
		{
			$chemical=$row['PDD_CHEMICAL'];
		}
	
	
		$item=array();
		$disc=array();
		
		$table1=get_table($_POST['selected_group'],$chemical);
		$query="select * from ani_excel_location where efm='".$table1."' and disc!='X' and pid='".$_POST['pdd_chemical1']."' and 							
		item!='Lot_No' ";
		
		$result = mssql_query($query);
		$numrows=mssql_num_rows($result);
		while ($row = mssql_fetch_array($result))
		{
			array_push($item,$row['item']);
			if(trim($row['disc'])!='NULL')
			{
				array_push($disc,$row['disc']);
				//echo "NO NULL";
			}
			elseif(trim($row['disc'])=='NULL')
			{				
				array_push($disc,$row['item']);
				//echo "IS NULL";
			}
		}
		if($numrows>=6){
		echo '<table border="1" width="1800">';
	//	echo 1;
		}
		else
		{
			echo '<table border="1" width="800">';
		//	echo 2;
		}
		echo '</tr>';
		echo '<td>'."LOT NO".'</td>';
		echo '<td>'."SAMPLE NO".'</td>';
		echo '<td>'."DRUM NO".'</td>';
		for($i=0;$i<$numrows;$i++)
		{
			echo '<td>'.$disc[$i].'</td>';
		}
		echo '<td>'."分析時間".'</td>';
		echo '<td>'."Tester".'</td>';
		echo '<td>'."Operator".'</td>';
		echo '<td>'."前處理人".'</td>';

		$NUM=1;$ENG='A';
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","LOT NO"));$ENG++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","SAMPLE NO"));$ENG++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","DRUM NO"));$ENG++;
		for($i=0;$i<$numrows;$i++)
		{
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",trim($disc[$i])));$ENG++;
		}
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","分析時間"));$ENG++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","Tester"));$ENG++;	
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","Operator"));$ENG++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","前端處理人"));$ENG++;
		
			$query1="SELECT  TL.TestDate, TL.CHK1, TL.CHK2, TL.LotNo, TL.SerialNo, TL.SampleNo, TL.Na, TL.Mg, TL.Al, TL.K, TL.Ca, TL.Cr, TL.Mn, TL.Fe, 
                   TL.Ni, TL.Co, TL.Cu, TL.Zn, TL.[In], TL.Pb, TL.CHK3, TL.CHK4, TL.Ok, TL.Tester, TL.Operator, TL.AnaManager, 
                   TL.AnalyzeTime, Sample_All.SMA_DRUMNO AS Expr1, Sample_All.SMA_SMP AS Expr2, Sample_All.SMA_DRUMNO, 
                   AnalyzeDesign.AND_GOODS
FROM      ".$table1." AS TL INNER JOIN
                   AnalyzeDesign ON TL.LotNo = AnalyzeDesign.AND_LOT_NO LEFT OUTER JOIN
                   Sample_All ON TL.SampleNo = Sample_All.SMA_ID AND TL.LotNo = Sample_All.SMA_LOT LEFT OUTER JOIN
                   analyze_first1 AS AF ON TL.AnalyzeTime = AF.create_time
						 where 			 (AnalyzeTime > '".dod($_POST['datepicker1'])."000000"."') and  (AnalyzeDesign.AND_GOODS = '".$_POST['pdd_chemical1']."') 
						AND (AnalyzeTime < '".dod($_POST['datepicker2'])."999999"."') order by analyzetime";
			if($_POST['lot']<>''){
			$query1="SELECT  TL.TestDate, TL.CHK1, TL.CHK2, TL.LotNo, TL.SerialNo, TL.SampleNo, TL.Na, TL.Mg, TL.Al, TL.K, TL.Ca, TL.Cr, TL.Mn, TL.Fe, 
                   TL.Ni, TL.Co, TL.Cu, TL.Zn, TL.[In], TL.Pb, TL.CHK3, TL.CHK4, TL.Ok, TL.Tester, TL.Operator, TL.AnaManager, 
                   TL.AnalyzeTime, Sample_All.SMA_DRUMNO AS Expr1, Sample_All.SMA_SMP AS Expr2, Sample_All.SMA_DRUMNO, 
                   AnalyzeDesign.AND_GOODS
FROM      ".$table1." AS TL INNER JOIN
                   AnalyzeDesign ON TL.LotNo = AnalyzeDesign.AND_LOT_NO LEFT OUTER JOIN
                   Sample_All ON TL.SampleNo = Sample_All.SMA_ID AND TL.LotNo = Sample_All.SMA_LOT LEFT OUTER JOIN
                   analyze_first1 AS AF ON TL.AnalyzeTime = AF.create_time
						 where 			 (AnalyzeTime > '".dod($_POST['datepicker1'])."000000"."') and  (AnalyzeDesign.AND_GOODS = '".$_POST['pdd_chemical1']."') 
						AND (AnalyzeTime < '".dod($_POST['datepicker2'])."999999"."') order by analyzetime";	
			}
//			echo "<BR>";
//			echo $query1;
//			echo "<BR>";
				$result1 = mssql_query($query1);
				$numrows1=mssql_num_rows($result1);
			while ($row1 = mssql_fetch_array($result1))
			{
				echo '</tr>';
				echo '<td>'.$row1['LotNo'].'</td>';
				echo '<td>'.$row1['SampleNo'].'</td>';
				echo '<td>'.$row1['SMA_DRUMNO'].'</td>';
				for($i=0;$i<$numrows;$i++)
				{
					echo '<td>'.$row1[trim($item[$i])].'</td>';
				}
				echo '<td>'.$row1['AnalyzeTime'].'</td>';
				echo '<td>'.$row1['Tester'].'</td>';
				echo '<td>'.$row1['Operator'].'</td>';
				echo '<td>'.get_uname($row1['first']).'</td>';

				$NUM++;$ENG='A';
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['LotNo']));$ENG++;
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['SampleNo']));$ENG++;
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['SMA_DRUMNO']));$ENG++;
				for($i=0;$i<$numrows;$i++)
				{
					$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,$row1[trim($item[$i])]);$ENG++;
				}
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['AnalyzeTime']));$ENG++;	
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['Tester']));$ENG++;	
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['Operator']));$ENG++;
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",get_uname($row1['first'])));$ENG++;

			}
			echo $numrows1."個LOT";
				$aut='C';
				for($i=0;$i<25;$i++)
				{
					$objPHPExcel->getActiveSheet()->getColumnDimension($aut)->setWidth(12); $aut++;
				}
	///////////////////////////////////////////////////			
		if(trim($_POST['selected_group'])=="I")
		{
			$objPHPExcel->setActiveSheetIndex(1);
		
		$query="select * from PRODUCT_DATA where PDD_PROD_NO='".$_POST['pdd_chemical1']."' ";
		echo $query;
		$result = mssql_query($query);
		while ($row = mssql_fetch_array($result))
		{
			$chemical=$row['PDD_CHEMICAL'];
		}
		
			$item=array();
		$disc=array();
		
		$table2=get_table1($_POST['selected_group'],$chemical);
		if($table2!="X")
		{
		$query="select * from ani_excel_location where efm='".$table2."' and disc!='X' and pid='".$_POST['pdd_chemical1']."' and 							
		item!='Lot_No' ";
		//echo $query;
		$result = mssql_query($query);
		$numrows=mssql_num_rows($result);
		while ($row = mssql_fetch_array($result))
		{
			array_push($item,$row['item']);
			if(trim($row['disc'])!='NULL')
			{
				array_push($disc,$row['disc']);
				//echo "NO NULL";
			}
			elseif(trim($row['disc'])=='NULL')
			{				
				array_push($disc,$row['item']);
				//echo "IS NULL";
			}
		}
		echo '<table border="1" width="1800">';
		echo '</tr>';
		echo '<td>'."LOT NO".'</td>';
		echo '<td>'."SAMPLE NO".'</td>';
		echo '<td>'."DRUM NO".'</td>';
		for($i=0;$i<$numrows;$i++)
		{
			echo '<td>'.$disc[$i].'</td>';
		}
		echo '<td>'."分析時間".'</td>';
		echo '<td>'."Tester".'</td>';
		echo '<td>'."Operator".'</td>';
		echo '<td>'."前處理人".'</td>';

		$NUM=1;$ENG='A';
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","LOT NO"));$ENG++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","SAMPLE NO"));$ENG++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","DRUM NO"));$ENG++;
		for($i=0;$i<$numrows;$i++)
		{
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",trim($disc[$i])));$ENG++;
		}
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","分析時間"));$ENG++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","Tester"));$ENG++;	
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","Operator"));$ENG++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","前端處理人"));$ENG++;
		
			$query1="	SELECT           TL.*, Sample_All.SMA_DRUMNO AS Expr1, 
                            Sample_All.SMA_SMP AS Expr2, Sample_All.SMA_DRUMNO, AnalyzeDesign.AND_GOODS
FROM              ".$table2." AS TL INNER JOIN
                            Sample_All ON TL.SampleNo = Sample_All.SMA_ID AND TL.LotNo = Sample_All.SMA_LOT INNER JOIN
                            AnalyzeDesign ON TL.LotNo = AnalyzeDesign.AND_LOT_NO LEFT OUTER JOIN
                            analyze_first1 AS AF ON TL.AnalyzeTime = AF.create_time 
						 where 			 (AnalyzeTime > '".dod($_POST['datepicker1'])."000000"."') and  (AnalyzeDesign.AND_GOODS = '".$_POST['pdd_chemical1']."') 
						AND (AnalyzeTime < '".dod($_POST['datepicker2'])."999999"."') order by analyzetime";
			if($_POST['lot']<>''){
			$query1="	SELECT           TL.*, Sample_All.SMA_DRUMNO AS Expr1, 
                            Sample_All.SMA_SMP AS Expr2, Sample_All.SMA_DRUMNO, AnalyzeDesign.AND_GOODS
FROM              ".$table2." AS TL INNER JOIN
                            Sample_All ON TL.SampleNo = Sample_All.SMA_ID AND TL.LotNo = Sample_All.SMA_LOT INNER JOIN
                            AnalyzeDesign ON TL.LotNo = AnalyzeDesign.AND_LOT_NO LEFT OUTER JOIN
                            analyze_first1 AS AF ON TL.AnalyzeTime = AF.create_time 
						 where 			 (AnalyzeTime > '".dod($_POST['datepicker1'])."000000"."') and  (AnalyzeDesign.AND_GOODS = '".$_POST['pdd_chemical1']."') 
						AND (AnalyzeTime < '".dod($_POST['datepicker2'])."999999"."') order by analyzetime";	
			}
				//echo $query1;
				$result1 = mssql_query($query1);
				$numrows1=mssql_num_rows($result1);
			while ($row1 = mssql_fetch_array($result1))
			{
				echo '</tr>';
				echo '<td>'.$row1['LotNo'].'</td>';
				echo '<td>'.$row1['SampleNo'].'</td>';
				echo '<td>'.$row1['SMA_DRUMNO'].'</td>';
				for($i=0;$i<$numrows;$i++)
				{
					echo '<td>'.$row1[trim($item[$i])].'</td>';
				}
				echo '<td>'.$row1['AnalyzeTime'].'</td>';
				echo '<td>'.$row1['Tester'].'</td>';
				echo '<td>'.$row1['Operator'].'</td>';
				echo '<td>'.get_uname($row1['first']).'</td>';

				$NUM++;$ENG='A';
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['LotNo']));$ENG++;
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['SampleNo']));$ENG++;
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['SMA_DRUMNO']));$ENG++;
				for($i=0;$i<$numrows;$i++)
				{
					$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,$row1[trim($item[$i])]);$ENG++;
				}
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['AnalyzeTime']));$ENG++;	
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['Tester']));$ENG++;	
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['Operator']));$ENG++;
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",get_uname($row1['first'])));$ENG++;

			}
			echo $numrows1."個LOT";
				$aut='C';
				for($i=0;$i<25;$i++)
				{
					$objPHPExcel->getActiveSheet()->getColumnDimension($aut)->setWidth(12); $aut++;
				}	
		}
				
		}
				
				
			$objPHPExcel->setActiveSheetIndex(0);	
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
			$objWriter->save('find2.xlsx');
	}
}
if(isset($_POST['submit10']))
{
	if($_POST['selected_group']!='0' and $_POST['pdd_chemical1']!='' )
	{	
		$objPHPExcel = new PHPExcel();
		$objPHPExcel = PHPExcel_IOFactory::load("./QC1.xlsx");
		$objPHPExcel->setActiveSheetIndex(0);
		
		$query="select * from PRODUCT_DATA where PDD_PROD_NO='".$_POST['pdd_chemical1']."' ";
		$result = mssql_query($query);
		while ($row = mssql_fetch_array($result))
		{
			$chemical=$row['PDD_CHEMICAL'];
		}
	
	
		$item=array();
		$disc=array();
		
		$table1=get_table($_POST['selected_group'],$chemical);
		$query="select * from ani_excel_location where efm='".$table1."' and disc!='X' and pid='".$_POST['pdd_chemical1']."' and 							
		item!='Lot_No' ";
		//echo $query;
		$result = mssql_query($query);
		$numrows=mssql_num_rows($result);
		while ($row = mssql_fetch_array($result))
		{
			array_push($item,$row['item']);
			if(trim($row['disc'])!='NULL')
			{
				array_push($disc,$row['disc']);
				//echo "NO NULL";
			}
			elseif(trim($row['disc'])=='NULL')
			{				
				array_push($disc,$row['item']);
				//echo "IS NULL";
			}
		}
		if($numrows>=6){
		echo '<table border="1" width="1800">';
	//	echo 1;
		}
		else
		{
			echo '<table border="1" width="800">';
		//	echo 2;
		}
		echo '</tr>';
		echo '<td>'."LOT NO".'</td>';
		echo '<td>'."SAMPLE NO".'</td>';
		for($i=0;$i<$numrows;$i++)
		{
			echo '<td>'.$disc[$i].'</td>';
		}
		echo '<td>'."分析時間".'</td>';
		echo '<td>'."Tester".'</td>';
		echo '<td>'."Operator".'</td>';
		echo '<td>'."前處理人".'</td>';

		$NUM=1;$ENG='A';
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","LOT NO"));$ENG++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","SAMPLE NO"));$ENG++;
		for($i=0;$i<$numrows;$i++)
		{
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",trim($disc[$i])));$ENG++;
		}
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","分析時間"));$ENG++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","Tester"));$ENG++;	
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","Operator"));$ENG++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","前端處理人"));$ENG++;
		
			$query1="select TL.*,AF.first from ".$table1."  as TL left join analyze_first1 as AF on TL.analyzetime=AF.create_time  where 			 (AnalyzeTime > '".dod($_POST['datepicker1'])."000000"."') 
				AND (AnalyzeTime < '".dod($_POST['datepicker2'])."999999"."') order by analyzetime";
		//	echo $query1;
				$result1 = mssql_query($query1);
				$numrows1=mssql_num_rows($result1);
			while ($row1 = mssql_fetch_array($result1))
			{
				echo '</tr>';
				echo '<td>'.$row1['LotNo'].'</td>';
				echo '<td>'.$row1['SampleNo'].'</td>';
				for($i=0;$i<$numrows;$i++)
				{
					echo '<td>'.$row1[trim($item[$i])].'</td>';
				}
				echo '<td>'.$row1['AnalyzeTime'].'</td>';
				echo '<td>'.$row1['Tester'].'</td>';
				echo '<td>'.$row1['Operator'].'</td>';
				echo '<td>'.get_uname($row1['first']).'</td>';

				$NUM++;$ENG='A';
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['LotNo']));$ENG++;
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['SampleNo']));$ENG++;
				for($i=0;$i<$numrows;$i++)
				{
					$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,$row1[trim($item[$i])]);$ENG++;
				}
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['AnalyzeTime']));$ENG++;	
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['Tester']));$ENG++;	
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['Operator']));$ENG++;
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",get_uname($row1['first'])));$ENG++;

			}
			echo $numrows1."個LOT";
				$aut='C';
				for($i=0;$i<25;$i++)
				{
					$objPHPExcel->getActiveSheet()->getColumnDimension($aut)->setWidth(12); $aut++;
				}
	/////////////////////////////////////////////////////////////////////////////////////			
		if(trim($_POST['selected_group'])=="I")
		{
				$objPHPExcel->setActiveSheetIndex(1);
		
		$query="select * from PRODUCT_DATA where PDD_PROD_NO='".$_POST['pdd_chemical1']."' ";
		$result = mssql_query($query);
		while ($row = mssql_fetch_array($result))
		{
			$chemical=$row['PDD_CHEMICAL'];
		}
	
	
		$item=array();
		$disc=array();
		
		$table2=get_table1($_POST['selected_group'],$chemical);
		if($table2!='X')
		{
		$query="select * from ani_excel_location where efm='".$table2."' and disc!='X' and pid='".$_POST['pdd_chemical1']."' and 							
		item!='Lot_No' ";
		//echo $query;
		$result = mssql_query($query);
		$numrows=mssql_num_rows($result);
		while ($row = mssql_fetch_array($result))
		{
			array_push($item,$row['item']);
			if(trim($row['disc'])!='NULL')
			{
				array_push($disc,$row['disc']);
				//echo "NO NULL";
			}
			elseif(trim($row['disc'])=='NULL')
			{				
				array_push($disc,$row['item']);
				//echo "IS NULL";
			}
		}
		echo '<table border="1" width="1800">';
		echo '</tr>';
		echo '<td>'."LOT NO".'</td>';
		echo '<td>'."SAMPLE NO".'</td>';
		for($i=0;$i<$numrows;$i++)
		{
			echo '<td>'.$disc[$i].'</td>';
		}
		echo '<td>'."分析時間".'</td>';
		echo '<td>'."Tester".'</td>';
		echo '<td>'."Operator".'</td>';
		echo '<td>'."前處理人".'</td>';

		$NUM=1;$ENG='A';
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","LOT NO"));$ENG++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","SAMPLE NO"));$ENG++;
		for($i=0;$i<$numrows;$i++)
		{
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",trim($disc[$i])));$ENG++;
		}
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","分析時間"));$ENG++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","Tester"));$ENG++;	
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","Operator"));$ENG++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","前端處理人"));$ENG++;
		
			$query1="select TL.*,AF.first from ".$table2."  as TL left join analyze_first1 as AF on TL.analyzetime=AF.create_time  where 			 (AnalyzeTime > '".dod($_POST['datepicker1'])."000000"."') 
				AND (AnalyzeTime < '".dod($_POST['datepicker2'])."999999"."') order by analyzetime";
		//	echo $query1;
				$result1 = mssql_query($query1);
				$numrows1=mssql_num_rows($result1);
			while ($row1 = mssql_fetch_array($result1))
			{
				echo '</tr>';
				echo '<td>'.$row1['LotNo'].'</td>';
				echo '<td>'.$row1['SampleNo'].'</td>';
				for($i=0;$i<$numrows;$i++)
				{
					echo '<td>'.$row1[trim($item[$i])].'</td>';
				}
				echo '<td>'.$row1['AnalyzeTime'].'</td>';
				echo '<td>'.$row1['Tester'].'</td>';
				echo '<td>'.$row1['Operator'].'</td>';
				echo '<td>'.get_uname($row1['first']).'</td>';

				$NUM++;$ENG='A';
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['LotNo']));$ENG++;
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['SampleNo']));$ENG++;
				for($i=0;$i<$numrows;$i++)
				{
					$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,$row1[trim($item[$i])]);$ENG++;
				}
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['AnalyzeTime']));$ENG++;	
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['Tester']));$ENG++;	
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['Operator']));$ENG++;
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",get_uname($row1['first'])));$ENG++;

			}
			echo $numrows1."個LOT";
				$aut='C';
				for($i=0;$i<25;$i++)
				{
					$objPHPExcel->getActiveSheet()->getColumnDimension($aut)->setWidth(12); $aut++;
				}
				
		}	
		}
				
	////////////////////////////////////////////////////////////////////////////////////			
				
				
			$objPHPExcel->setActiveSheetIndex(0);	
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
			$objWriter->save('find2.xlsx');
	}
}
		
if(isset($_POST['submit3']))
{
	if($_POST['selected_group']!='0' and $_POST['pdd_chemical1']!='' )
	{
		$objPHPExcel = new PHPExcel();
		$objPHPExcel = PHPExcel_IOFactory::load("./QC1.xlsx");
		$objPHPExcel->setActiveSheetIndex(0);
		
		$query="select * from PRODUCT_DATA where PDD_PROD_NO='".$_POST['pdd_chemical1']."' ";
		$result = mssql_query($query);
		while ($row = mssql_fetch_array($result))
		{
			$chemical=$row['PDD_CHEMICAL'];
		}
	
	
		$item=array();
		$disc=array();
		
		$table1=get_table($_POST['selected_group'],$chemical);
		
		$query1="select TL.*,AF.first from ".$table1."  as TL left join analyze_first1 as AF on TL.analyzetime=AF.create_time  where 			 (AnalyzeTime > '".dod($_POST['datepicker1'])."000000"."') 
				AND (AnalyzeTime < '".dod($_POST['datepicker2'])."999999"."') order by analyzetime";
			
			if($_POST['lot']<>'')
		{
				$query1="select TL.*,AF.first from ".$table1."  as TL left join analyze_first1 as AF on TL.analyzetime=AF.create_time  where 			 (AnalyzeTime > '".dod($_POST['datepicker1'])."000000"."') 
				AND (AnalyzeTime < '".dod($_POST['datepicker2'])."999999"."')
				and lotno like '%".$_POST['lot']."%'order by analyzetime";
		}
	//	echo $query1;
				$result1 = mssql_query($query1);
				$numrowsall=mssql_num_rows($result1);
				$All=$numrowsall;
		$query="select * from ani_excel_location where efm='".$table1."' and disc!='X' and pid='".$_POST['pdd_chemical1']."' and 							
		item!='Lot_No' ";
	//	echo $query;
		$result = mssql_query($query);
		$numrows=mssql_num_rows($result);
		while ($row = mssql_fetch_array($result))
		{
			array_push($item,$row['item']);
			if(trim($row['disc'])!='NULL')
			{
				array_push($disc,$row['disc']);
				//echo "NO NULL";
			}
			elseif(trim($row['disc'])=='NULL')
			{				
				array_push($disc,$row['item']);
				//echo "IS NULL";
			}
		}
		echo '<table border="1" width="2000">';
		echo '</tr>';
		echo '<td>'."LOT NO".'</td>';
		echo '<td>'."SAMPLE NO".'</td>';
		for($i=0;$i<$numrows;$i++)
		{
			echo '<td>'.$disc[$i].'</td>';
		}
		echo '<td>'."分析時間".'</td>';
		echo '<td>'."Tester".'</td>';
		echo '<td>'."Operator".'</td>';
		echo '<td>'."前處理人".'</td>';

		$NUM=1;$ENG='A';
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","LOT NO"));$ENG++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","SAMPLE NO"));$ENG++;
		for($i=0;$i<$numrows;$i++)
		{
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",trim($disc[$i])));$ENG++;
		}
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","分析時間"));$ENG++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","Tester"));$ENG++;	
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","Operator"));$ENG++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","前端處理人"));$ENG++;
		
		$query1="select distinct lotno from ".$table1." where  (AnalyzeTime > '".dod($_POST['datepicker1'])."000000"."') 
				AND (AnalyzeTime < '".dod($_POST['datepicker2'])."999999"."') ";
				
		if($_POST['lot']<>'')
		{
			$query1="select distinct lotno from ".$table1." where  (AnalyzeTime > '".dod($_POST['datepicker1'])."000000"."') 
				AND (AnalyzeTime < '".dod($_POST['datepicker2'])."999999"."') and lotno like '%".$_POST['lot']."%' ";
		}
		
			$result1 = mssql_query($query1);
			$numRows1=mssql_num_rows($result1);
			while ($row1 = mssql_fetch_array($result1))
			{
			$query2="select * from ".$table1." as TL left join analyze_first1 as AF on TL.analyzetime=AF.create_time  where 
			  lotno='".$row1['lotno']."' ";
			//echo $query2;
			$result2 = mssql_query($query2);
			$numrows2=mssql_num_rows($result2);
			if($numrows2>1)
			{
				$lottime=$lottime+$numrows2;
				$lotnum=$lotnum+1;
				while ($row2 = mssql_fetch_array($result2))
				{
					echo '</tr>';
					echo '<td>'.$row2['LotNo'].'</td>';
					echo '<td>'.$row2['SampleNo'].'</td>';
					for($i=0;$i<$numrows;$i++)
					{
						echo '<td>'.$row2[trim($item[$i])].'</td>';
					}
					echo '<td>'.$row2['AnalyzeTime'].'</td>';
					echo '<td>'.$row2['Tester'].'</td>';
					echo '<td>'.$row2['Operator'].'</td>';
					echo '<td>'.get_uname($row2['first']).'</td>';
	
					$NUM++;$ENG='A';
					$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row2['LotNo']));$ENG++;
					$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row2['SampleNo']));$ENG++;
					for($i=0;$i<$numrows;$i++)
					{
						$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,$row2[trim($item[$i])]);$ENG++;
					}
					$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row2['AnalyzeTime']));$ENG++;	
					$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row2['Tester']));$ENG++;	
					$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row2['Operator']));$ENG++;
					$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",get_uname($row2['first'])));$ENG++;
				}
			}	
			}	
			echo "共".$All."LOT__".$lottime."再分析次數__".$lotnum."個再分析LOT";
				$NUM++;$objPHPExcel->getActiveSheet()->setCellValue("A".$NUM, iconv("big5","utf-8","總LOT數"));
				$objPHPExcel->getActiveSheet()->setCellValue("B".$NUM, iconv("big5","utf-8","再分析LOT數"));
				$objPHPExcel->getActiveSheet()->setCellValue("C".$NUM, iconv("big5","utf-8","總再分析次數"));
				$NUM++;$objPHPExcel->getActiveSheet()->setCellValue("A".$NUM, iconv("big5","utf-8",$All));
				$objPHPExcel->getActiveSheet()->setCellValue("B".$NUM, iconv("big5","utf-8",$lotnum));
				$objPHPExcel->getActiveSheet()->setCellValue("C".$NUM, iconv("big5","utf-8",$lottime));
				$aut='C';
				for($i=0;$i<25;$i++)
				{
					$objPHPExcel->getActiveSheet()->getColumnDimension($aut)->setWidth(12); $aut++;
				}
				
				
		if(trim($_POST['selected_group'])=="I")
		{
			$objPHPExcel->setActiveSheetIndex(0);
		
		$query="select * from PRODUCT_DATA where PDD_PROD_NO='".$_POST['pdd_chemical1']."' ";
		$result = mssql_query($query);
		while ($row = mssql_fetch_array($result))
		{
			$chemical=$row['PDD_CHEMICAL'];
		}
	
	
		$item=array();
		$disc=array();
		
		$table2=get_table1($_POST['selected_group'],$chemical);
		if($table2!='X')
		{
		$query1="select TL.*,AF.first from ".$table2."  as TL left join analyze_first1 as AF on TL.analyzetime=AF.create_time  where 			 (AnalyzeTime > '".dod($_POST['datepicker1'])."000000"."') 
				AND (AnalyzeTime < '".dod($_POST['datepicker2'])."999999"."') order by analyzetime";
			
			if($_POST['lot']<>'')
		{
				$query1="select TL.*,AF.first from ".$table2."  as TL left join analyze_first1 as AF on TL.analyzetime=AF.create_time  where 			 (AnalyzeTime > '".dod($_POST['datepicker1'])."000000"."') 
				AND (AnalyzeTime < '".dod($_POST['datepicker2'])."999999"."')
				and lotno like '%".$_POST['lot']."%'order by analyzetime";
		}
	//	echo $query1;
				$result1 = mssql_query($query1);
				$numrowsall=mssql_num_rows($result1);
				$All=$numrowsall;
		$query="select * from ani_excel_location where efm='".$table2."' and disc!='X' and pid='".$_POST['pdd_chemical1']."' and 							
		item!='Lot_No' ";
	//	echo $query;
		$result = mssql_query($query);
		$numrows=mssql_num_rows($result);
		while ($row = mssql_fetch_array($result))
		{
			array_push($item,$row['item']);
			if(trim($row['disc'])!='NULL')
			{
				array_push($disc,$row['disc']);
				//echo "NO NULL";
			}
			elseif(trim($row['disc'])=='NULL')
			{				
				array_push($disc,$row['item']);
				//echo "IS NULL";
			}
		}
		echo '<table border="1" width="2000">';
		echo '</tr>';
		echo '<td>'."LOT NO".'</td>';
		echo '<td>'."SAMPLE NO".'</td>';
		for($i=0;$i<$numrows;$i++)
		{
			echo '<td>'.$disc[$i].'</td>';
		}
		echo '<td>'."分析時間".'</td>';
		echo '<td>'."Tester".'</td>';
		echo '<td>'."Operator".'</td>';
		echo '<td>'."前處理人".'</td>';

		$NUM=1;$ENG='A';
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","LOT NO"));$ENG++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","SAMPLE NO"));$ENG++;
		for($i=0;$i<$numrows;$i++)
		{
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",trim($disc[$i])));$ENG++;
		}
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","分析時間"));$ENG++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","Tester"));$ENG++;	
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","Operator"));$ENG++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","前端處理人"));$ENG++;
		
		$query1="select distinct lotno from ".$table2." where  (AnalyzeTime > '".dod($_POST['datepicker1'])."000000"."') 
				AND (AnalyzeTime < '".dod($_POST['datepicker2'])."999999"."') ";
				
		if($_POST['lot']<>'')
		{
			$query1="select distinct lotno from ".$table2." where  (AnalyzeTime > '".dod($_POST['datepicker1'])."000000"."') 
				AND (AnalyzeTime < '".dod($_POST['datepicker2'])."999999"."') and lotno like '%".$_POST['lot']."%' ";
		}
		
			$result1 = mssql_query($query1);
			$numRows1=mssql_num_rows($result1);
			while ($row1 = mssql_fetch_array($result1))
			{
			$query2="select * from ".$table2." as TL left join analyze_first1 as AF on TL.analyzetime=AF.create_time  where 
			  lotno='".$row1['lotno']."' ";
			//echo $query2;
			$result2 = mssql_query($query2);
			$numrows2=mssql_num_rows($result2);
			if($numrows2>1)
			{
				$lottime=$lottime+$numrows2;
				$lotnum=$lotnum+1;
				while ($row2 = mssql_fetch_array($result2))
				{
					echo '</tr>';
					echo '<td>'.$row2['LotNo'].'</td>';
					echo '<td>'.$row2['SampleNo'].'</td>';
					for($i=0;$i<$numrows;$i++)
					{
						echo '<td>'.$row2[trim($item[$i])].'</td>';
					}
					echo '<td>'.$row2['AnalyzeTime'].'</td>';
					echo '<td>'.$row2['Tester'].'</td>';
					echo '<td>'.$row2['Operator'].'</td>';
					echo '<td>'.get_uname($row2['first']).'</td>';
	
					$NUM++;$ENG='A';
					$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row2['LotNo']));$ENG++;
					$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row2['SampleNo']));$ENG++;
					for($i=0;$i<$numrows;$i++)
					{
						$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,$row2[trim($item[$i])]);$ENG++;
					}
					$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row2['AnalyzeTime']));$ENG++;	
					$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row2['Tester']));$ENG++;	
					$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row2['Operator']));$ENG++;
					$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",get_uname($row2['first'])));$ENG++;
				}
			}	
			}	
			echo "共".$All."LOT__".$lottime."再分析次數__".$lotnum."個再分析LOT";
				$NUM++;$objPHPExcel->getActiveSheet()->setCellValue("A".$NUM, iconv("big5","utf-8","總LOT數"));
				$objPHPExcel->getActiveSheet()->setCellValue("B".$NUM, iconv("big5","utf-8","再分析LOT數"));
				$objPHPExcel->getActiveSheet()->setCellValue("C".$NUM, iconv("big5","utf-8","總再分析次數"));
				$NUM++;$objPHPExcel->getActiveSheet()->setCellValue("A".$NUM, iconv("big5","utf-8",$All));
				$objPHPExcel->getActiveSheet()->setCellValue("B".$NUM, iconv("big5","utf-8",$lotnum));
				$objPHPExcel->getActiveSheet()->setCellValue("C".$NUM, iconv("big5","utf-8",$lottime));
				$aut='C';
				for($i=0;$i<25;$i++)
				{
					$objPHPExcel->getActiveSheet()->getColumnDimension($aut)->setWidth(12); $aut++;
				}
		}
		}
			$objPHPExcel->setActiveSheetIndex(0);	
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
			$objWriter->save('find2.xlsx');
	}
}

function select_chemical(){
	session_start();
	echo '<select name="selected_chemical" id="selected_chemical">';
	echo '<option value="0"></option>';
	$query="SELECT DISTINCT PDD_CHEMICAL
	FROM              WORKTIME";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		//if($row['PDD_CHEMICAL']==$_SESSION['selected_group']){$select='selected';}
		//else{$select='';}
		echo '<option value="'.$row['PDD_CHEMICAL'].'" >'.$row['PDD_CHEMICAL'].'</option>';
	}
	echo '</select>';
}

function get_table1($groupname,$pdd_chemical){
include ('../connections/conn.php');
	$query="SELECT DISTINCT ELEMENT_FORM.ELF_FORM
FROM              ELEMENT_FORM INNER JOIN
                            AnalyzeItem ON ELEMENT_FORM.ELM_ID = AnalyzeItem.ANI_INDEX
WHERE          (ELEMENT_FORM.PDD_CHEMICAL = '".$pdd_chemical."') AND (AnalyzeItem.ANI_GROUPNAME = '".$groupname."') order by  ELEMENT_FORM.ELF_FORM desc";
$result = mssql_query($query);
$numrows=mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
$table1=$row['ELF_FORM'];
if($numrows<=1)
{
	$table1="X";
}
}
return $table1;
}
?>