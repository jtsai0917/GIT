<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
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

<input name="submit" type="submit" id="submit" value=" IPA(ACID)查詢 ">
<input name="submit2" type="submit" id="submit2" value=" 下載 ">
<input name="submit3" type="submit" id="submit3" value=" TXT ">



<?php


$path_root=$_SERVER['HTTP_HOST'];
echo '<table border="1" width="500">';
if(isset($_POST['submit']))
{
	$objPHPExcel = new PHPExcel();
	$objPHPExcel = PHPExcel_IOFactory::load("./prod.xlsx");
	$objPHPExcel->setActiveSheetIndex(0);
	$NUM=1;
	$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","LOT NO"));$ENG++;


$query="select PDD_PROD_NO from PRODUCT_DATA ";
$result = mssql_query($query);
			while ($row = mssql_fetch_array($result))
			{echo '</tr>';
				$ENG='F';$NUM++;
				$query1="SELECT 
      [AND_NEED_NO]
      
  FROM [CHEMICAL].[dbo].[AnalyzeDesign]
  where AND_APPLY_DATE>20070000 and AND_GOODS='".$row['PDD_PROD_NO']."'and AND_NEED_NO<151
  ";
 // echo $query1;
  $result1 = mssql_query($query1);
  $numRows1= mssql_num_rows($result1);
  //while ($row1 = mssql_fetch_array($result1))
			{
				
				if($numRows1<10 )
				{echo '<td>'.$numRows1.'</td>';
				//echo $row['PDD_PROD_NO']."使用:".$numRows1;
				//$queryup="update PRODUCT_TYPE set TYPE_PROD='Y' where  PDD_PROD_NO='".$row['PDD_PROD_NO']."' ";
				 $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$numRows1));$ENG++;
				//  $resultup = mssql_query($queryup);
				  //echo "製品";
				}
				else {echo '<td>'.'</td>';}
				

			}
			
			$query2="SELECT 
      [AND_NEED_NO]
      
  FROM [CHEMICAL].[dbo].[AnalyzeDesign]
  where AND_APPLY_DATE>20070000 and AND_GOODS='".$row['PDD_PROD_NO']."'and (AND_NEED_NO>150 or AND_NEED_NO<271)
  order by AND_NEED_NO ";
  $result2 = mssql_query($query2);
  $numRows2 = mssql_num_rows($result2);

//  while ($row2 = mssql_fetch_array($result2))
			{
				if($numRows2<10 )
				{echo '<td>'.$numRows2.'</td>';
					 $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$numRows2));$ENG++;
				//echo $row['PDD_PROD_NO']."使用:".$numRows2;
				//$queryup="update PRODUCT_TYPE set TYPE_ANALY='Y' where  PDD_PROD_NO='".$row['PDD_PROD_NO']."' ";
				// $resultup = mssql_query($queryup);
				 				//  echo "解析";
				}
				else {echo '<td>'.'</td>';}
			}
			
			$query3="SELECT 
      [AND_NEED_NO]
      
  FROM [CHEMICAL].[dbo].[AnalyzeDesign]
  where AND_APPLY_DATE>20070000 and AND_GOODS='".$row['PDD_PROD_NO']."'and AND_NEED_NO>270
  order by AND_NEED_NO ";
  $result3 = mssql_query($query3);
  $numRows3 = mssql_num_rows($result3);

  //while ($row3 = mssql_fetch_array($result3))
			{
				if($numRows3<10)
				{
					echo '<td>'.$numRows3.'</td>';
					 $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$numRows3));$ENG++;
				//echo $row['PDD_PROD_NO']."使用".$numRows3;
				//$queryup="update PRODUCT_TYPE set TYPE_RAW='Y' where  PDD_PROD_NO='".$row['PDD_PROD_NO']."' ";
				// $resultup = mssql_query($queryup);
				 				//  echo "原料".'</br>';
				}
				else {echo '<td>'.'</td>';}
			}
			
			}
			echo '</br>';
			
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
			$objWriter->save('prod.xlsx');
}

if(isset($_POST['submit2']))
		{
			
			echo '</br>';
			echo "下載";
		echo '<script>document.location.href="http://'.$path_root.'/people/prod.xlsx";</script>';
		
		}

if(isset($_POST['submit3']))
{$aa=array();$i=0;
	$myfile = fopen("www.txt", "r") or die("unable to open file!");
		// ?出?行直到 end-of-file
		while(!feof($myfile)) {
			$i++;
		  echo fgets($myfile) ;
		  $aa[$i]=trim(fgets($myfile));
		  echo $i;	  
		  
		 
		}
		print_r($aa);
		$j=1;
		$query="select PDD_PROD_NO from PRODUCT_TYPE  order by PDD_PROD_NO ";
		$result = mssql_query($query);
			while ($row = mssql_fetch_array($result))
			{
				$queryup="update PRODUCT_TYPE set department='".$aa[$j]."' where  PDD_PROD_NO='".$row['PDD_PROD_NO']."'";
				echo $queryup;
				$resultup = mssql_query($queryup);
				$j++;

			}
}
?>
