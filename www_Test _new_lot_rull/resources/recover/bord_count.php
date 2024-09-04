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

<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">



<input type="submit" id="all" value="列出所有棧板" name="all" style="height:40; width:100" />

<input type="submit" id="using"  name="using" value="使用中棧板"  style="height:40; width:100" />

<input type="submit" id="unuse"  name="unuse" value="閒置中棧板"  style="height:40; width:100" />

<input type="button" id="new"  name="new" value="新增棧板"  onClick="window.open('./new_bord.php ', '_self');" style="height:40; width:100" />



 


<input type="button" name="cust" id="cust" value="報廢棧板" onClick="window.open('./broke_bord.php ', '_self');" style="height:40; width:100" >
  <input name="submit3" type="submit" class="center button" id="submit3" style="height:40; width:100"  value=" 下載報告 ">
  需要先搜尋結果下載才有資料

<?php

//<input type="button" name="cust" id="cust" value="新增棧板" onClick="window.open('./new_bord.php ', '_self');" style="height:40; width:100" > 

if(isset($_POST['all']))
{	$p=1;
		$objPHPExcel = new PHPExcel();
		$objPHPExcel = PHPExcel_IOFactory::load("./bord.xlsx");
		$objPHPExcel->setActiveSheetIndex(0);
		 echo '<table border="1" width="500">';
		 echo '<tr height="">';
		 echo  '<td>'."棧板編號".'</td>';
		 echo  '<td>'."啟用日期".'</td>';
		 echo  '<td>'."使用客戶".'</td>';
		 echo  '<td>'."出廠日期".'</td>';
		$objPHPExcel->getActiveSheet()->setCellValue("A".$p, iconv("big5","utf-8","棧板編號"));
		$objPHPExcel->getActiveSheet()->setCellValue("B".$p, iconv("big5","utf-8","啟用日期"));
		$objPHPExcel->getActiveSheet()->setCellValue("C".$p, iconv("big5","utf-8","使用客戶"));
		$objPHPExcel->getActiveSheet()->setCellValue("D".$p, iconv("big5","utf-8","出廠日期"));
	/*$ALL="select BOA_NO,max(BOA_TIMES) as MAX 
         from BOARD_ALL
         GROUP BY BOA_NO";
		 {*/
				$ALL="select BA.BOD_NO,BA.BOD_BEGIN_DATE,BA.BOD_OUT_LOC,BA.BOD_OUT_DATE ,CD.CTD_CUST_SHORT_NAME
				from BOARD AS BA
				left join CUSTOMER_DATA AS CD on BA.BOD_OUT_LOC=CD.CTD_CUST_NO ";
				$resultALL = mssql_query($ALL);
				$numrowsALL = mssql_num_rows($resultALL);
				while($rowALL=mssql_fetch_array($resultALL))
				{
					
					echo '<tr height="">';
					 echo  '<td>'.$rowALL['BOD_NO'].'</td>'; 
					  echo  '<td>'.$rowALL['BOD_BEGIN_DATE'].'</td>'; 
					   echo  '<td>'.$rowALL['CTD_CUST_SHORT_NAME'].'</td>'; 
					    echo  '<td>'.$rowALL['BOD_OUT_DATE'].'</td>'; 
						$objPHPExcel->getActiveSheet()->setCellValue("A".$p,$rowALL['BOD_NO']);
						
						$objPHPExcel->getActiveSheet()->setCellValue("B".$p,$rowALL['BOD_BEGIN_DATE']);
						
						$objPHPExcel->getActiveSheet()->setCellValue("C".$p, iconv("big5","utf-8",$rowALL['CTD_CUST_SHORT_NAME']));
						
						$objPHPExcel->getActiveSheet()->setCellValue("D".$p,$rowALL['BOD_OUT_DATE']);
						$p++;
				}
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
			$objWriter->save('bord1.xlsx');
}		
if(isset($_POST['using']))
{
	 $p=1;
		$objPHPExcel = new PHPExcel();
		$objPHPExcel = PHPExcel_IOFactory::load("./bord1.xlsx");
		$objPHPExcel->setActiveSheetIndex(1);
		 echo '<table border="1" width="500">';
		 echo '<tr height="">';
		 echo  '<td>'."棧板編號".'</td>';
		 echo  '<td>'."啟用日期".'</td>';
		 echo  '<td>'."使用客戶".'</td>';
		 echo  '<td>'."出廠日期".'</td>';
		 
		 $objPHPExcel->getActiveSheet()->setCellValue("A".$p, iconv("big5","utf-8","棧板編號"));
		$objPHPExcel->getActiveSheet()->setCellValue("B".$p, iconv("big5","utf-8","啟用日期"));
		$objPHPExcel->getActiveSheet()->setCellValue("C".$p, iconv("big5","utf-8","使用客戶"));
		$objPHPExcel->getActiveSheet()->setCellValue("D".$p, iconv("big5","utf-8","出廠日期"));
		$USE="select BA.BOD_NO,BA.BOD_BEGIN_DATE,BA.BOD_OUT_LOC,BA.BOD_OUT_DATE ,CD.CTD_CUST_SHORT_NAME
				from BOARD AS BA
				left join CUSTOMER_DATA AS CD on BA.BOD_OUT_LOC=CD.CTD_CUST_NO 
				where BA.BOD_OUT_LOC!=''";
				$resultUSE = mssql_query($USE);
				$numrowsUSE = mssql_num_rows($resultUSE);
				while($rowUSE=mssql_fetch_array($resultUSE))
				{
					
					echo '<tr height="">';
					 echo  '<td>'.$rowUSE['BOD_NO'].'</td>'; 
					  echo  '<td>'.$rowUSE['BOD_BEGIN_DATE'].'</td>'; 
					   echo  '<td>'.$rowUSE['CTD_CUST_SHORT_NAME'].'</td>'; 
					    echo  '<td>'.$rowUSE['BOD_OUT_DATE'].'</td>'; 
						$objPHPExcel->getActiveSheet()->setCellValue("A".$p,$rowUSE['BOD_NO']);
						
						$objPHPExcel->getActiveSheet()->setCellValue("B".$p,$rowUSE['BOD_BEGIN_DATE']);
						
						$objPHPExcel->getActiveSheet()->setCellValue("C".$p, iconv("big5","utf-8",$rowUSE['CTD_CUST_SHORT_NAME']));
						
						$objPHPExcel->getActiveSheet()->setCellValue("D".$p,$rowUSE['BOD_OUT_DATE']);
						$p++;
				}
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
			$objWriter->save('bord1.xlsx');
}
if(isset($_POST['unuse']))
{
	 $p=1;
		$objPHPExcel = new PHPExcel();
		$objPHPExcel = PHPExcel_IOFactory::load("./bord1.xlsx");
		$objPHPExcel->setActiveSheetIndex(2);
		 echo '<table border="1" width="500">';
		 echo '<tr height="">';
		 echo  '<td>'."棧板編號".'</td>';
		 echo  '<td>'."啟用日期".'</td>';
		 echo  '<td>'."使用客戶".'</td>';
		 echo  '<td>'."出廠日期".'</td>';
		 
		 $objPHPExcel->getActiveSheet()->setCellValue("A".$p, iconv("big5","utf-8","棧板編號"));
		$objPHPExcel->getActiveSheet()->setCellValue("B".$p, iconv("big5","utf-8","啟用日期"));
		$objPHPExcel->getActiveSheet()->setCellValue("C".$p, iconv("big5","utf-8","使用客戶"));
		$objPHPExcel->getActiveSheet()->setCellValue("D".$p, iconv("big5","utf-8","出廠日期"));
		$NOUSE="select BA.BOD_NO,BA.BOD_BEGIN_DATE,BA.BOD_OUT_LOC,BA.BOD_OUT_DATE ,CD.CTD_CUST_SHORT_NAME
				from BOARD AS BA
				left join CUSTOMER_DATA AS CD on BA.BOD_OUT_LOC=CD.CTD_CUST_NO 
				where BA.BOD_OUT_LOC IS NULL";
				$resultNOUSE = mssql_query($NOUSE);
				$numrowsNOUSE = mssql_num_rows($resultNOUSE);
				while($rowNOUSE=mssql_fetch_array($resultNOUSE))
				{
					
					echo '<tr height="">';
					 echo  '<td>'.$rowNOUSE['BOD_NO'].'</td>'; 
					  echo  '<td>'.$rowNOUSE['BOD_BEGIN_DATE'].'</td>'; 
					   echo  '<td>'.$rowNOUSE['CTD_CUST_SHORT_NAME'].'</td>'; 
					    echo  '<td>'.$rowNOUSE['BOD_OUT_DATE'].'</td>'; 
						$objPHPExcel->getActiveSheet()->setCellValue("A".$p,$rowNOUSE['BOD_NO']);
						
						$objPHPExcel->getActiveSheet()->setCellValue("B".$p,$rowNOUSE['BOD_BEGIN_DATE']);
						
						$objPHPExcel->getActiveSheet()->setCellValue("C".$p, iconv("big5","utf-8",$rowNOUSE['CTD_CUST_SHORT_NAME']));
						
						$objPHPExcel->getActiveSheet()->setCellValue("D".$p,$rowNOUSE['BOD_OUT_DATE']);
						$p++;
				}
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
			$objWriter->save('bord1.xlsx');
}		
		if(isset($_POST['submit3']))		
		{			
			$path_root=$_SERVER['HTTP_HOST'];
			echo '</br>';			
		echo '<script>document.location.href="http://'.$path_root.'/recover/bord1.xlsx";</script>';
		}
			


?>