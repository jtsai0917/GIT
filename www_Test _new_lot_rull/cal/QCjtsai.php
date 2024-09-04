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
查詢 </br><form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
日期： <input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php if($_SESSION['datepicker1']){echo $_SESSION['datepicker1'];}?>" onChange="set_date_session(this.name,this.value)">
~
<input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php if($_SESSION['datepicker2']){echo $_SESSION['datepicker2'];}?>" onChange="set_date_session(this.name,this.value)"></td> 
&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
<input name="submit" type="submit" id="submit" value=" 查詢再分析 ">
<input name="submitx" type="submit" id="submitx" value="  查詢  ">
<input name="submit1" type="submit" id="submit1" value=" 下載報告 ">
<input name="submit2" type="submit" id="submit2" value=" 下載報告 ">
<br><br>



        品名:
<input name="pdd_chemical1" type="text" id="pdd_chemical1" size="10" value="<?php 
		if ($_GET['prod_no']){
			echo $_GET['prod_no'];
			$_SESSION['prod_no']=$_GET['prod_no'];
		}
		elseif($_SESSION['prod_no']){
			echo $_SESSION['prod_no'];
		}
		else{
		echo '';
		}?>">

        
		<input name="textfield1" type="text" id="textfield1" size="10" value="<?php 
if ($_GET['name']){
			echo $_GET['prod_name'];
			$_SESSION['prod_name']=$_GET['prod_name'];
		}
		elseif($_SESSION['prod_name']){
			echo $_SESSION['prod_name'];
		}
		else{
		echo '';
		}
		 
		
		?>" readonly>
       
 <input type="button" name="pdd" id="pdd" value="選擇藥品" onClick="window.open('../recover/pdd_prod.php ', '_self');" >
        <input type="button" name="X" id="X" value="X" onclick="window.open('../recover/erase_prod.php ', '_self');" /> 
      <input type="button" name="group" id="group" value="選擇分析項目(須先選擇藥品)" onClick="window.open('./index.php?url=add_testitems100 ', '_self');" >
        
         用戶:
<?PHP //  echo $_SESSION['CTD_CUST_NO'] ;?>

        <input type="button" name="cust" id="cust" value="選擇客戶" onClick="window.open('../main.php?url=cust_no1', '_self');" />
        <input type="button" name="X2" id="X2" value="X" onclick="window.open('./erase_customer.php ', '_self');" /></form>


         		
        <?PHP 
		echo '</br>';
		global $num;
		global $num1;
//print_r($_SESSION['aaa']);
		


			$objPHPExcel = new PHPExcel();
	$objPHPExcel = PHPExcel_IOFactory::load("./QC.xlsx");
	$objPHPExcel->setActiveSheetIndex(0);
	 echo '<table border="1" width="2000">';
			/* echo '<tr height="">';
			 echo  '<td>'."LOT NO".'</td>';
			  echo  '<td>'."SampleNO".'</td>';
			   echo  '<td>'."TEST DATE".'</td>';
			   echo  '<td>'."Orgfield".'</td>';
			   echo  '<td>'."藥品料號".'</td>';
			  echo  '<td>'."藥品名稱".'</td>';
			 echo  '<td>'."檢測資料".'</td>';
			 echo '<td>'."檢測類別".'</td>';
			   echo  '<td>'."檢測項目".'</td>';
			   echo  '<td>'."TESTER".'</td>';
			   echo  '<td>'."客戶".'</td>';
			   $objPHPExcel->getActiveSheet()->setCellValue("A1", iconv("big5","utf-8","LOT NO"));
			   $objPHPExcel->getActiveSheet()->setCellValue("B1", iconv("big5","utf-8","樣品瓶號"));
			   $objPHPExcel->getActiveSheet()->setCellValue("C1", iconv("big5","utf-8","TESTDATE"));
			   $objPHPExcel->getActiveSheet()->setCellValue("D1", iconv("big5","utf-8","OrgField"));
			   $objPHPExcel->getActiveSheet()->setCellValue("E1", iconv("big5","utf-8","藥品料號"));
			   $objPHPExcel->getActiveSheet()->setCellValue("F1", iconv("big5","utf-8","藥品名稱"));
			   $objPHPExcel->getActiveSheet()->setCellValue("G1", iconv("big5","utf-8","檢測資料"));
			   $objPHPExcel->getActiveSheet()->setCellValue("H1", iconv("big5","utf-8","檢測類別"));
			   $objPHPExcel->getActiveSheet()->setCellValue("H1", iconv("big5","utf-8","檢測項目"));
			   $objPHPExcel->getActiveSheet()->setCellValue("I1", iconv("big5","utf-8","TESTER"));
			   $objPHPExcel->getActiveSheet()->setCellValue("J1", iconv("big5","utf-8","客戶"));*/
		if(isset($_POST['submit']))
		{	
				$str1=explode(";",$_SESSION['CTD_CUST_NO']);
				$strn=count($str1);
				$query2="and (FOD.CTD_CUST_NO='".$str1[0]."'";
				for($p=1;$p<$strn;$p++)
				{
					$query2=$query2." or  FOD.CTD_CUST_NO='".$str1[$p]."'";
				}
				$query2=$query2."  )  ";
				//-------------------------------------------------------------------------------------------------
				if(trim($_SESSION['items'])<>'')
				{
					//$str=substr_replace($_SESSION['items'],"",-1);
					
					$str=explode(",",$_SESSION['items']);
					$GLOBALS['num']=((count($str))/3);
					$t=0;$u=1;
					//echo $num."我在這";
					//echo $str[0];
				}
				//-------------------------------------------------------------------------------------------------
			$date1=dod($_SESSION['datepicker1']);
			$date2=dod($_SESSION['datepicker2']);
			
			$query="select distinct QC.LotNo,QC.SampleNo,QC.AnalyzeTime,QC.OrgTable    
			from QC_LotData as QC 
			left join FILLPLAN_OUT_DECIDE as FOD on QC.LotNO=FOD.FDM_LOT_NO    
			left join AnalyzeItem as AI on QC.Itemname=AI.ANI_FULLNAME	
			left join CUSTOMER_DATA as CD on CD.CTD_CUST_NO=FOD.CTD_CUST_NO
			where ID!='' ";
			if($_SESSION['datepicker1']<>'')
			{
				$query=$query." and QC.AnalyzeTime >'".$date1."000000"."' and QC.AnalyzeTime < '".$date2."999999"."' ";
			}
			if($_POST['pdd_chemical1']<>'')
			{
				$query=$query." and QC.ProdNo='".$_POST['pdd_chemical1']."' ";
			}
			if($_SESSION['CTD_CUST_NO']<>'')
			{
				$query=$query.$query2;							
			}
			if(trim($str[0])<>'0')
			{
				$query=$query." and (";
				for($i=0;$i<$GLOBALS['num'];$i++)
				{	
						$query1=$query1." ItemName='".$str[$t]."'   or ";
						
						
						$t=$t+2;
				}
				
				$query1=substr($query1,0,-4);
				$query=$query.$query1." )   "; 
			}
			if(trim($str[1]<>''))
			{
				$query=$query." and ANI_GROUPNAME='".$str[$u]."'  ";
				$u=$u+2;
			}			
		//	$query=$query." order by QC.LotNo,QC.Chemical,AI.ANI_GROUPNAME";
						$queryre="select b.LotNo,b.OrgTable from ( ".$query;
						$queryre=$queryre."  )  b   group by  b.LotNo,b.OrgTable having count(b.LotNo)>1    ";
						//echo $query;
						$chk=0;$NUM=1;
						//echo $queryre;				
				$result = mssql_query($queryre);
				$numRows=mssql_num_rows($result);
			
				while ($row = mssql_fetch_array($result))
				{	
					$t=0;$ID=array();
					for($i=0;$i<$GLOBALS['num'];$i++)
					{	
						$QT="
						select top 1 ID from qc_lotdata where LotNo='".$row['LotNo']."' and OrgTable='".$row['OrgTable']."' 
						 and  itemname='".$str[$t]."'";
						$t=$t+2;
						//echo $QT.'<br>';
						$resultQT = mssql_query($QT);
						$numRowsQT=mssql_num_rows($resultQT);
						while ($rowQT = mssql_fetch_array($resultQT))
						{
							array_push($ID,$rowQT['ID']);
						}
					}
					$IDN=count($ID);
					//echo $IDN;
					$insert=" and   (  ";
					for($i=0;$i<$IDN;$i++)
					{
					$insert=$insert." QC.ID='".$ID[$i]."'   or ";
					
					}
					$insert=substr($insert,0,-3);
					$insert=$insert." )   ";
				//	echo $insert.'</br>';
					
					
					$qf=substr_replace($query,"QC.*,CD.CTD_CUST_SHORT_NAME,AI.ANI_GROUPNAME,AI.ANI_INDEX",7,57);
					//$qf=substr_replace($qf," ",-46);
					$qf=substr_replace($qf,"and QC.LotNo='".$row['LotNo']."'  and  QC.OrgTable='".$row['OrgTable']."' 
					 and QC.TestData!=''    and QC.AnalyzeTime='".$rowqf1['AnalyzeTime']."' order by AI.ANI_INDEX ",-1);//加在最後
					$qf1="select temp.AnalyzeTime from ( ".$qf;
					$qf1=substr_replace($qf1," )temp      group by temp.AnalyzeTime",-46);
					//echo $qf1.'<br />';
					$resultqf1 = mssql_query($qf1);
					$numRowsqf1=mssql_num_rows($resultqf1);
					while ($rowqf1 = mssql_fetch_array($resultqf1))
					{
						
						
					$t=0;$ID=array();
					$QQT=" and (  ";
		for($i=0;$i<$GLOBALS['num'];$i++)
		{			
			$QQT=$QQT."itemname='".$str[$t]."'   or ";
			$t=$t+2;
		}
		$QQT=substr($QQT,0,-3);
		$QQT=$QQT." ) ";
		$QT="select top ".$GLOBALS['num']." ID from QC_LotData where Analyzetime='".$rowqf1['AnalyzeTime']."' ".$QQT." order by id desc";
						$t=$t+2;
					//	echo $QT.'<br>';
						$resultQT = mssql_query($QT);
						$numRowsQT=mssql_num_rows($resultQT);
						while ($rowQT = mssql_fetch_array($resultQT))
						{
							array_push($ID,$rowQT['ID']);
						}
					$IDN=count($ID);
					//echo $IDN;
					$insert=" and   (  ";
					for($i=0;$i<$IDN;$i++)
					{
					$insert=$insert." QC.ID='".$ID[$i]."'   or ";
					}
					$insert=substr($insert,0,-3);
					$insert=$insert." )   ";
					//echo $insert.'</br>';
				
				
						
					$qf="select QC.*,CD.CTD_CUST_SHORT_NAME,AI.ANI_GROUPNAME,AI.ANI_INDEX 
					  from QC_LotData as QC 
					left join FILLPLAN_OUT_DECIDE as FOD on QC.LotNO=FOD.FDM_LOT_NO    
					left join AnalyzeItem as AI on QC.Itemname=AI.ANI_FULLNAME
					left join CUSTOMER_DATA as CD on CD.CTD_CUST_NO=FOD.CTD_CUST_NO
					where ID!='' ".$insert."  and AI.ANI_GROUPNAME='".$str[1]."'  order by AI.ANI_INDEX";
					$bb=array();
					$aa=array(); 
		//echo $qf.'<br />';
						$resultqf = mssql_query($qf);
						$numRowsqf=mssql_num_rows($resultqf);
						while ($rowqf = mssql_fetch_array($resultqf))
						{	//$jj[$a]=$numRowsqf;$a++;
							array_push($aa,$rowqf['OrgField']);array_push($bb,$rowqf['TestData']);
							$lot=$rowqf['LotNo'];
							$sample=$rowqf['SampleNo'];
							$Prod=$rowqf['ProdNo'];
							$chemical=$rowqf['Chemical'];
							$tester=$rowqf['Tester'];
							$Analy=$rowqf['AnalyzeTime'];
							$CTD=$rowqf['CTD_CUST_SHORT_NAME'];
							
						} 
						
					if($chk==0)
					{
						$ENG='A';
						
						  echo '<tr height="">';
						 echo  '<td width="120">'."Lot No".'</td>';
						 echo  '<td width="100">'."Sample No".'</td>';
						 echo  '<td width="100">'."產品料號".'</td>';
						 echo  '<td width="100">'."藥品料號".'</td>';
						 echo  '<td>'."TESTER".'</td>';
						 echo  '<td width="140">'."測試時間".'</td>';
						 echo  '<td	width="100">'."客戶".'</td>';
						 $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","LOT NO"));$ENG++;
						 $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","Sample No"));$ENG++;
						 $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","產品料號"));$ENG++;
						 $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","藥品料號"));$ENG++;
						 $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","TESTER"));$ENG++;
						 $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","測試時間"));$ENG++;
						  $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","客戶"));$ENG++;
						
						for($j=0;$j<$numRowsqf;$j++)
						 {
						 	echo  '<td>'.$aa[$j].'</td>';
						 	$objPHPExcel->getActiveSheet()->setCellValue($ENG."1", iconv("big5","utf-8",$aa[$j]));
							if($ENG=='Z')
							{$ENG='AA';}
							else
							{
							$ENG++;
							}
						 } 
						  $chk++;$NUM++;
					}
						 
						 $ENG='A';
						 echo '<tr height="">';
						 echo  '<td>'.$lot.'</td>';
						 echo  '<td>'.$sample.'</td>';
						 echo  '<td>'.$Prod.'</td>';
						 echo  '<td>'.$chemical.'</td>';
						 echo  '<td>'.$tester.'</td>';
						 echo  '<td>'.$Analy.'</td>';
						 echo  '<td>'.$CTD.'</td>';
						 $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$lot));$ENG++;
						 $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$sample));$ENG++;
						 $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$Prod));$ENG++;
						 $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$chemical));$ENG++;
						 $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$tester));$ENG++;
						 $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$Analy));$ENG++;
						  $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$CTD));$ENG++;
						 for($j=0;$j<$numRowsqf;$j++)
						 {
						 echo  '<td>'.$bb[$j].'</td>';
						 $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$bb[$j]));
							if($ENG=='Z')
							{$ENG='AA';}
							else
							{
							$ENG++;
							}

						 }
						 $NUM++;
					}
				}
						 
						 
						 
				
				
						
				
			/*if($_POST['cust']<>'')
			{
				$query1="select FDM_LOT_NO from FILLPLAN_OUT_DECIDE where CTD_CUST_NO='".$_POST['cust']."' and FOD_YEAR_MONTH=
				'".substr($date1,0,6)."' and FOD_DAY>'".substr($date1,-2)."' and ";
				
					
				}
			}*/
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	   		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	   		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
 			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	   		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	   	   	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		   	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
			$objWriter->save('QC1.xlsx');
	
	
	 	}
				if(isset($_POST['submit2']))
		{
			$path_root=$_SERVER['HTTP_HOST'];
			echo '</br>';
			echo "列印完";
		echo '<script>document.location.href="http://'.$path_root.'/cal/QC2.xlsx";</script>';
		
		}
		
		
if(isset($_POST['submitx']))
{	
	$O=1;
	$_SESSION['itmstr']=$str=explode(",",$_SESSION['itemsx']);
	$_SESSION['group1']=$strg=explode(",",$_SESSION['group']);
	$num_str=count($str);		
	$ENG='A';
	$chk=0;$NUM=1;					
	echo '<table border="1"><tr height=""><td></td>';
	echo  '<td width="120">'."Lot No".'</td>';
	echo  '<td width="100">'."Sample No".'</td>';
	echo  '<td width="140">'."測試時間".'</td>';
	echo  '<td width="100">'."產品料號".'</td>';
	echo  '<td>'."TESTER".'</td>';
	echo  '<td	width="100">'."客戶".'</td>';
	

	$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","LOT NO"));$ENG++;
	$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","Sample No"));$ENG++;
	$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","測試時間"));$ENG++;
	$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","產品料號"));$ENG++;
	$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","TESTER"));$ENG++;	
	$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","客戶"));$ENG++;
	for($i=0;$i<$num_str;$i++)
	{
		echo '<td>'.$str[$i].'</td>';
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$str[$i]));$ENG++;
	}
		echo '</tr>';$NUM++;
		
	$query="SELECT DISTINCT QC.LotNo, QC.AnalyzeTime, QC.SampleNo, QC.ProdNo, Tester,CD.CTD_CUST_NO
			FROM              QC_LotData AS QC LEFT OUTER JOIN
										FILLPLAN_OUT_DECIDE AS FOD ON QC.LotNo = FOD.FDM_LOT_NO LEFT OUTER JOIN
										AnalyzeItem AS AI ON QC.ItemName = AI.ANI_FULLNAME LEFT OUTER JOIN
										CUSTOMER_DATA AS CD ON CD.CTD_CUST_NO = FOD.CTD_CUST_NO
			WHERE          (QC.ID <> '') AND (QC.AnalyzeTime > '".dod($_POST['datepicker1'])."000000"."') 
			AND (QC.AnalyzeTime < '".dod($_POST['datepicker2'])."999999"."') AND 
                            (QC.ProdNo = '".$_POST['pdd_chemical1']."') 
							and (CHARINDEX(','+OrgField+',',',".$_SESSION['itemsx'].",')>1)
							and AI.ANI_GROUPNAME='".$strg[0]."' and Testdata!='' ";
							echo $query;
	$result = mssql_query($query);
	$numRows=mssql_num_rows($result);$ENG='A';
		while ($row = mssql_fetch_array($result))
		{	
			echo '<tr><td>'.$O.'</td><td>'.$row['LotNo'].'</td><td>'.
			$row['SampleNo'].'</td><td>'.$row['AnalyzeTime'].'</td><td>'.
			$row['ProdNo'].'</td><td>'.$row['Tester'].'</td><td>'.$row['CTD_CUST_NO'].'</td>';
			//echo list_($row['LotNo'],$row['SampleNo'],$row['AnalyzeTime'],$num_str);
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row['LotNo']));$ENG++;
	$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row['SampleNo']));$ENG++;
	$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row['AnalyzeTime']));$ENG++;
	$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row['ProdNo']));$ENG++;
	$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row['Tester']));$ENG++;	
	$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row['CTD_CUST_NO']));$ENG++;
			for($i=0;$i<$num_str;$i++)
			{
				echo '<td>';
				$query1="select top 1 QC.TestData
					from QC_LotData as QC
					left join AnalyzeItem as AI on QC.Itemname=AI.ANI_FULLNAME
					where LotNo='".$row['LotNo']."' and AnalyzeTime='".
					$row['AnalyzeTime']."' and SampleNo='".$row['SampleNo']."' and 		
					orgfield='".$_SESSION['itmstr'][$i]."' and AI.ANI_GROUPNAME='".$strg[0]."' order by id desc ";
				//echo $query1;
				$result1 = mssql_query($query1);
				
				while ($row1 = mssql_fetch_array($result1))
				{	
					echo $row1['TestData'];
					$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row1['TestData']));
					//echo $ENG;
				}
				echo '</td>';$ENG++;
			}
			echo '</tr>';$O=$O+1;$NUM++;$ENG='A';
		}
	echo '</table><br>'."在此結束";
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	   		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	   		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
 			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	   		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	   	   	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		   	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
			$objWriter->save('QC2.xlsx');
}
		
		if(isset($_POST['submit1']))
		{
			$O=1;
			$str1=explode(";",$_SESSION['CTD_CUST_NO']);
				$strn=count($str1);
				$query2="and (FOD.CTD_CUST_NO='".$str1[0]."'";
				for($p=1;$p<$strn;$p++)
				{ 
					$query2=$query2."  or  FOD.CTD_CUST_NO='".$str1[$p]."'";
				}
				$query2=$query2."  )  ";
				//-------------------------------------------------------------------------------------------------
				if(trim($_SESSION['it'])<>'')
				{
					//$str=substr_replace($_SESSION['items'],"",-1);
					
					$str=explode(",",$_SESSION['it']);
					$str_g=explode(",",$_SESSION['group']);
					$GLOBALS['num1']=count($str);
					
					$t=0;$u=0;
					//echo $num."我在這";
					//echo $str[0];
				}
				//-------------------------------------------------------------------------------------------------
			$date1=dod($_SESSION['datepicker1']);
			$date2=dod($_SESSION['datepicker2']);
			$query="select distinct QC.LotNo,QC.SampleNo,QC.AnalyzeTime,QC.OrgTable    
			from QC_LotData as QC 
			left join FILLPLAN_OUT_DECIDE as FOD on QC.LotNO=FOD.FDM_LOT_NO    
			left join AnalyzeItem as AI on QC.Itemname=AI.ANI_FULLNAME
			left join CUSTOMER_DATA as CD on CD.CTD_CUST_NO=FOD.CTD_CUST_NO
			where ID!='' ";
			if($_SESSION['datepicker1']<>'')
			{
				$query=$query." and QC.AnalyzeTime >'".$date1."000000"."' and QC.AnalyzeTime < '".$date2."999999"."' ";
			}
			if($_POST['pdd_chemical1']<>'')
			{
				$query=$query." and QC.ProdNo='".$_POST['pdd_chemical1']."' ";
			}
			if($_SESSION['CTD_CUST_NO']<>'')
			{
				$query=$query.$query2;							
			}
			if(trim($str[0])<>'0')
			{
				$query1=$query1." and (";
				for($i=0;$i<$GLOBALS['num1'];$i++)
				{	
						$query1=$query1." ItemName='".$str[$i]."'   or ";
						
				}
				$query1=substr($query1,0,-3);
				$query1=$query1." )   "; 
				$query=$query.$query1; 
				$query=$query." and ANI_GROUPNAME='".$str_g[0]."'  ";
			}
			
			
						
		//	$query=$query." order by QC.LotNo,QC.Chemical,AI.ANI_GROUPNAME";
			//echo $query.'<br />';
			$result = mssql_query($query);
				$numRows=mssql_num_rows($result);
			
				$chk=0;$NUM=1;
while ($row = mssql_fetch_array($result))
{	
					$t=0;
					$bb=array();
					$aa=array();
					$_SESSION['QID']=$ID=array();
					
					/*$QQT=" and (  ";
		for($i=0;$i<$GLOBALS['num1'];$i++)
		{			
			$QQT=$QQT."itemname='".$str[$t]."'   or ";
			$t=$t+3;
		}
		$QQT=substr($QQT,0,-3);
		$QQT=$QQT." ) ";*/
		$QT="select top ".round($GLOBALS['num1'])." ID from QC_LotData 
		where analyzetime='".$row['AnalyzeTime']."' ".$query1." order by Testdate desc";
		echo $QT;
						$t=$t+2;
						$resultQT = mssql_query($QT);
						$numRowsQT=mssql_num_rows($resultQT);
						while ($rowQT = mssql_fetch_array($resultQT))
						{
							array_push($_SESSION['QID'],$rowQT['ID']);
						}

						$IDN=count($_SESSION['QID']);
						$insert="and (  ";
						for($i=0;$i<$IDN;$i++){$insert=$insert." QC.ID='".$_SESSION['QID'][$i]."'   or ";}
					$insert=substr($insert,0,-3);
					$insert=$insert." )   ";
 					
//					echo $insert.'<br />';
					
					//$qf=substr_replace($query,"QC.*,CD.CTD_CUST_SHORT_NAME,AI.ANI_GROUPNAME,AI.ANI_INDEX",7,57);
					//$qf=substr_replace($qf," ",-46);
					//$qf=substr_replace($qf,"and QC.LotNo='".$row['LotNo']."'
					 //and QC.SampleNo='".$row['SampleNo']."' and AnalyzeTime='".$row['AnalyzeTime']."'
					  //".$insert." and QC.TestData!='' order by AI.ANI_INDEX ",-1);//加在最後

					  $qf="select QC.*,CD.CTD_CUST_SHORT_NAME,AI.ANI_GROUPNAME,AI.ANI_INDEX 
					  from QC_LotData as QC 
					left join FILLPLAN_OUT_DECIDE as FOD on QC.LotNO=FOD.FDM_LOT_NO    
					left join AnalyzeItem as AI on QC.Itemname=AI.ANI_FULLNAME
					left join CUSTOMER_DATA as CD on CD.CTD_CUST_NO=FOD.CTD_CUST_NO
					where ID!='' ".$insert."  and AI.ANI_GROUPNAME='".$str[1]."'  order by AI.ANI_INDEX";
			//		echo $qf.'<br />';
					$resultqf = mssql_query($qf);
					$numRowsqf=mssql_num_rows($resultqf);
					while ($rowqf = mssql_fetch_array($resultqf))
					{	//$jj[$a]=$numRowsqf;$a++;
						if($rowqf['OrgField']=='In'){$rowqf['OrgField']='[In]';}
						array_push($aa,$rowqf['OrgField']);array_push($bb,$rowqf['TestData']);
						$lot=$rowqf['LotNo'];
						$sample=$rowqf['SampleNo'];
						$Prod=$rowqf['ProdNo'];
						$chemical=$rowqf['Chemical'];
						$tester=$rowqf['Tester'];
						$Analy=$rowqf['AnalyzeTime'];
						$CTD=$rowqf['CTD_CUST_SHORT_NAME'];
						
					} 
					//if($chk==0)
					//{


						$ENG='A';
						
						  echo '<tr height="">';
						  echo '<td>'.$O.'</td>';
						 echo  '<td width="120">'."Lot No".'</td>';
						 echo  '<td width="100">'."Sample No".'</td>';
						 echo  '<td width="100">'."產品料號".'</td>';
						 echo  '<td width="100">'."藥品料號".'</td>';
						 echo  '<td>'."TESTER".'</td>';
						 echo  '<td width="140">'."測試時間".'</td>';
						 echo  '<td	width="100">'."客戶".'</td>';
						 $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","LOT NO"));$ENG++;
						 $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","Sample No"));$ENG++;
						 $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","產品料號"));$ENG++;
						 $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","藥品料號"));$ENG++;
						 $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","TESTER"));$ENG++;
						 $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","測試時間"));$ENG++;
						 $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","客戶"));$ENG++;
						
						for($j=0;$j<$numRowsqf;$j++)
						 {
						 	echo  '<td>'.$aa[$j].'</td>';
						 	$objPHPExcel->getActiveSheet()->setCellValue($ENG."1", iconv("big5","utf-8",$aa[$j]));
							if($ENG=='Z')
							{$ENG='AA';}
							else
							{
							$ENG++;
							}
						 } 
						  $chk++;$NUM++;
					//}
					$O=$O+1;
						 
						 $ENG='A';
						 echo '<tr height=""><td></td>';
						 echo  '<td>'.$lot.'</td>';
						 echo  '<td>'.$sample.'</td>';
						 echo  '<td>'.$Prod.'</td>';
						 echo  '<td>'.$chemical.'</td>';
						 echo  '<td>'.$tester.'</td>';
						 echo  '<td>'.$Analy.'</td>';
						 echo  '<td>'.$CTD.'</td>';
						 $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$lot));$ENG++;
						 $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$sample));$ENG++;
						 $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$Prod));$ENG++;
						 $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$chemical));$ENG++;
						 $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$tester));$ENG++;
						 $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$Analy));$ENG++;
						  $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$CTD));$ENG++;
						 for($j=0;$j<$numRowsqf;$j++)
						 {
						 echo  '<td>'.$bb[$j].'</td>';
						 $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$bb[$j]));
							if($ENG=='Z')
							{$ENG='AA';}
							else
							{
							$ENG++;
							}

						 }
						 $NUM++;
}
						 
						 
						 
				
				
						
				
			/*if($_POST['cust']<>'')
			{
				$query1="select FDM_LOT_NO from FILLPLAN_OUT_DECIDE where CTD_CUST_NO='".$_POST['cust']."' and FOD_YEAR_MONTH=
				'".substr($date1,0,6)."' and FOD_DAY>'".substr($date1,-2)."' and ";
				
					
				}
			}
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	   		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	   		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
 			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	   		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	   	   	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		   	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
			$objWriter->save('QC1.xlsx');
	*/
		}	
		
/*function list_($lotno,$smpno,$anatime,$num_str)
{
	for($i=0;$i<$num_str;$i++)
	{
		echo '<td>';
		$query1="select  QC.TestData
			from QC_LotData as QC
			where LotNo='".$lotno."' and AnalyzeTime='".$anatime."' and SampleNo='".$smpno."' and orgfield='".$_SESSION['itmstr'][$i]."'";
	//	echo $query1;
		$result1 = mssql_query($query1);
		while ($row1 = mssql_fetch_array($result1))
		{	
			echo $row1['TestData'];
		}
		echo '</td>';
	}
}
*/
		?>