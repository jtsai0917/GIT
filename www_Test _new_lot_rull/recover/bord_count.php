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
出荷/回收/報廢日期
<input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php echo $_SESSION['datepicker1'] ; ?>"  onchange="set_date_session(this.name,this.value)" />
~
<input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php echo $_SESSION['datepicker2'] ; ?>"  onchange="set_date_session(this.name,this.value)" />
<input type="submit" id="all" value="列出所有棧板" name="all" style="height:40; width:100" />

<input type="submit" id="using"  name="using" value="使用中棧板"  style="height:40; width:100" />
<input type="submit" id="back"  name="back" value="回收棧板"  style="height:40; width:100" />
<input type="submit" id="unuse"  name="unuse" value="閒置中棧板"  style="height:40; width:100" />
<input type="submit" id="unuse2"  name="unuse2" value="已報廢棧板"  style="height:40; width:100" />
<input type="button" id="new"  name="new" value="新增棧板"  onClick="window.open('./new_bord.php ', '_self');" style="height:40; width:100" />



 


<input type="button" name="cust" id="cust" value="單一報廢棧板" onClick="window.open('./broke_bord.php ', '_self');" style="height:40; width:100" >
  <input name="submit3" type="submit" class="center button" id="submit3" style="height:40; width:100"  value=" 下載報告 ">需要先搜尋結果下載才有資料</form>
  

<?php

//<input type="button" name="cust" id="cust" value="新增棧板" onClick="window.open('./new_bord.php ', '_self');" style="height:40; width:100" > 

if(isset($_POST['back']))
{	$p=1;
		$objPHPExcel = new PHPExcel();
		$objPHPExcel = PHPExcel_IOFactory::load("./bord.xlsx");
		$objPHPExcel->setActiveSheetIndex(0);
		 echo '<table border="1" width="">';
		 echo '<tr height="">';
		 echo  '<td>'."棧板編號".'</td>';
		 echo  '<td>'."啟用日期".'</td>';
		 echo  '<td>'."使用客戶".'</td>';
		 echo  '<td>'."出廠日期".'</td>';
		 echo  '<td>'."回收日期".'</td>';
		 echo  '<td>'."報廢日期".'</td>';
		 echo  '<td>'."報廢原因".'</td>';
		 echo  '<td>'."報廢登記人".'</td></tr>';
		$objPHPExcel->getActiveSheet()->setCellValue("A".$p, iconv("big5","utf-8","棧板編號"));
		$objPHPExcel->getActiveSheet()->setCellValue("B".$p, iconv("big5","utf-8","啟用日期"));
		$objPHPExcel->getActiveSheet()->setCellValue("C".$p, iconv("big5","utf-8","使用客戶"));
		$objPHPExcel->getActiveSheet()->setCellValue("D".$p, iconv("big5","utf-8","出廠日期"));
		$objPHPExcel->getActiveSheet()->setCellValue("E".$p, iconv("big5","utf-8","回收日期"));
		$objPHPExcel->getActiveSheet()->setCellValue("F".$p, iconv("big5","utf-8","報廢日期"));
		$objPHPExcel->getActiveSheet()->setCellValue("G".$p, iconv("big5","utf-8","報廢原因"));
		$objPHPExcel->getActiveSheet()->setCellValue("H".$p, iconv("big5","utf-8","報廢登記人"));
		$p++;
	/*$ALL="select BOA_NO,max(BOA_TIMES) as MAX 
         from BOARD_ALL
         GROUP BY BOA_NO";
		 {*/
				$ALL="SELECT DISTINCT BOA_NO, BOA_BEGIN_DATE FROM BOARD_ALL ";
				if($_SESSION['datepicker1']<>'' and $_SESSION['datepicker2']<>''){
					$ALL.=" where BOA_BACK_DATE>'".dod($_SESSION['datepicker1'])."' and BOA_BACK_DATE<'".dod($_SESSION['datepicker2'])."' ";
				}
				$ALL.=" order by BOA_BEGIN_DATE desc";
				echo "<BR>出荷日期在  ".$_SESSION['datepicker1']."-".$_SESSION['datepicker2']." 之間<BR>";
				$resultALL = mssql_query($ALL);
				$numrowsALL = mssql_num_rows($resultALL);
				while($rowALL=mssql_fetch_array($resultALL))
				{
					$ba=new board_all;
					$ba->bno=$rowALL['BOA_NO'];
					$ba->last_boa();
					echo '<tr height="">';
					echo  '<td>'.$rowALL['BOA_NO'].'</td>'; 
					echo  '<td>'.$ba->begin.'</td>'; 
					echo  '<td>'.get_cust_name($ba->out_cus).'</td>'; 
					echo  '<td>'.$ba->out_date.'</td>'; 
					echo  '<td>'.$ba->back_date.'</td>'; 
					echo  '<td>'.$ba->discard_date.'</td>'; 
					echo  '<td>'.$ba->discard_reason.'</td>'; 
					echo  '<td>'.$ba->discard_uid.'</td>'; 	
						$objPHPExcel->getActiveSheet()->setCellValue("A".$p,$ba->bno);
						$objPHPExcel->getActiveSheet()->setCellValue("B".$p,$ba->begin);
						$objPHPExcel->getActiveSheet()->setCellValue("C".$p, iconv("big5","utf-8",get_cust_name($ba->out_cus)));
						$objPHPExcel->getActiveSheet()->setCellValue("D".$p,$ba->out_date);
						$objPHPExcel->getActiveSheet()->setCellValue("E".$ba->back_date);
						$objPHPExcel->getActiveSheet()->setCellValue("F".$p,$ba->discard_date);
						$objPHPExcel->getActiveSheet()->setCellValue("G".$p,$ba->discard_reason);
						$objPHPExcel->getActiveSheet()->setCellValue("H".$p,$ba->discard_uid);
						
						$p++;
				}
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
			$objWriter->save('bord1.xlsx');
}		
if(isset($_POST['all']))
{	$p=1;
		$objPHPExcel = new PHPExcel();
		$objPHPExcel = PHPExcel_IOFactory::load("./bord.xlsx");
		$objPHPExcel->setActiveSheetIndex(0);
		 echo '<table border="1" width="">';
		 echo '<tr height="">';
		 echo  '<td>'."棧板編號".'</td>';
		 echo  '<td>'."啟用日期".'</td>';
		 echo  '<td>'."使用客戶".'</td>';
		 echo  '<td>'."出廠日期".'</td>';
		 echo  '<td>'."回收日期".'</td>';
		 echo  '<td>'."報廢日期".'</td>';
		 echo  '<td>'."報廢原因".'</td>';
		 echo  '<td>'."報廢登記人".'</td></tr>';
		$objPHPExcel->getActiveSheet()->setCellValue("A".$p, iconv("big5","utf-8","棧板編號"));
		$objPHPExcel->getActiveSheet()->setCellValue("B".$p, iconv("big5","utf-8","啟用日期"));
		$objPHPExcel->getActiveSheet()->setCellValue("C".$p, iconv("big5","utf-8","使用客戶"));
		$objPHPExcel->getActiveSheet()->setCellValue("D".$p, iconv("big5","utf-8","出廠日期"));
		$objPHPExcel->getActiveSheet()->setCellValue("E".$p, iconv("big5","utf-8","回收日期"));
		$objPHPExcel->getActiveSheet()->setCellValue("F".$p, iconv("big5","utf-8","報廢日期"));
		$objPHPExcel->getActiveSheet()->setCellValue("G".$p, iconv("big5","utf-8","報廢原因"));
		$objPHPExcel->getActiveSheet()->setCellValue("H".$p, iconv("big5","utf-8","報廢登記人"));
		$p++;
	/*$ALL="select BOA_NO,max(BOA_TIMES) as MAX 
         from BOARD_ALL
         GROUP BY BOA_NO";
		 {*/
				$ALL="SELECT DISTINCT BOA_NO, BOA_BEGIN_DATE FROM BOARD_ALL ";
				if($_SESSION['datepicker1']<>'' and $_SESSION['datepicker2']<>''){
					$ALL.=" where BOA_OUT_DATE>'".dod($_SESSION['datepicker1'])."' and BOA_OUT_DATE<'".dod($_SESSION['datepicker2'])."' ";
				}
				$ALL.=" order by BOA_NO ";
				echo "<BR>出荷日期在  ".$_SESSION['datepicker1']."-".$_SESSION['datepicker2']." 之間<BR>";
//				echo $ALL."<BR>";
				$resultALL = mssql_query($ALL);
				$numrowsALL = mssql_num_rows($resultALL);
				while($rowALL=mssql_fetch_array($resultALL))
				{
					$ba=new board_all;
					$ba->bno=$rowALL['BOA_NO'];
					$ba->last_boa();
					echo '<tr height="">';
					echo  '<td>'.$rowALL['BOA_NO'].'</td>'; 
					echo  '<td>'.$ba->begin.'</td>'; 
					echo  '<td>'.get_cust_name($ba->out_cus).'</td>'; 
					echo  '<td>'.$ba->out_date.'</td>'; 
					echo  '<td>'.$ba->back_date.'</td>'; 
					echo  '<td>'.$ba->discard_date.'</td>'; 
					echo  '<td>'.$ba->discard_reason.'</td>'; 
					echo  '<td>'.$ba->discard_uid.'</td>'; 	
						$objPHPExcel->getActiveSheet()->setCellValue("A".$p,$ba->bno);
						$objPHPExcel->getActiveSheet()->setCellValue("B".$p,$ba->begin);
						$objPHPExcel->getActiveSheet()->setCellValue("C".$p, iconv("big5","utf-8",get_cust_name($ba->out_cus)));
						$objPHPExcel->getActiveSheet()->setCellValue("D".$p,$ba->out_date);
						$objPHPExcel->getActiveSheet()->setCellValue("E".$ba->back_date);
						$objPHPExcel->getActiveSheet()->setCellValue("F".$p,$ba->discard_date);
						$objPHPExcel->getActiveSheet()->setCellValue("G".$p,$ba->discard_reason);
						$objPHPExcel->getActiveSheet()->setCellValue("H".$p,$ba->discard_uid);
						
						$p++;
				}
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
			$objWriter->save('bord1.xlsx');
}		

if(isset($_POST['using']))
{
	
	echo '<br>'.'<input type="button" onclick="'."window.open('./index.php?url=board_del1','_self');".'" name="box1" value="前往廢棄功能">
';
	 $p=1;
		$objPHPExcel = new PHPExcel();
		$objPHPExcel = PHPExcel_IOFactory::load("./bord1.xlsx");
		$objPHPExcel->setActiveSheetIndex(1);
		 echo '<table border="1" width="">';
		 echo '<tr height="">';
		 echo  '<td>'."棧板編號".'</td>';
		 echo  '<td>'."啟用日期".'</td>';
		 echo  '<td>'."使用客戶".'</td>';
		 echo  '<td>'."出廠日期".'</td>';
		 echo  '<td>'."回收日期".'</td>';
		 echo  '<td>'."報廢日期".'</td>';
		 echo  '<td>'."報廢原因".'</td>';
		 echo  '<td>'."報廢登記人".'</td></tr>';
		$objPHPExcel->getActiveSheet()->setCellValue("A".$p, iconv("big5","utf-8","棧板編號"));
		$objPHPExcel->getActiveSheet()->setCellValue("B".$p, iconv("big5","utf-8","啟用日期"));
		$objPHPExcel->getActiveSheet()->setCellValue("C".$p, iconv("big5","utf-8","使用客戶"));
		$objPHPExcel->getActiveSheet()->setCellValue("D".$p, iconv("big5","utf-8","出廠日期"));
		$objPHPExcel->getActiveSheet()->setCellValue("E".$p, iconv("big5","utf-8","回收日期"));
		$objPHPExcel->getActiveSheet()->setCellValue("F".$p, iconv("big5","utf-8","報廢日期"));
		$objPHPExcel->getActiveSheet()->setCellValue("G".$p, iconv("big5","utf-8","報廢原因"));
		$objPHPExcel->getActiveSheet()->setCellValue("H".$p, iconv("big5","utf-8","報廢登記人"));
		$p++;
		$USE="SELECT DISTINCT BOA_NO, BOA_BEGIN_DATE FROM BOARD_ALL where BOA_OUT_LOC!='' ";
				if($_SESSION['datepicker1']<>'' and $_SESSION['datepicker2']<>''){
					$USE.=" and BOA_OUT_DATE>'".dod($_SESSION['datepicker1'])."' and BOA_OUT_DATE<'".dod($_SESSION['datepicker2'])."' ";
				}
				$USE.=" order by BOA_BEGIN_DATE desc";
				echo "<BR>出荷日期在  ".$_SESSION['datepicker1']."-".$_SESSION['datepicker2']." 之間<BR>";
				$resultUSE = mssql_query($USE);
				$numrowsUSE = mssql_num_rows($resultUSE);
				while($rowUSE=mssql_fetch_array($resultUSE))
				{
					$ba=new board_all;
					$ba->bno=$rowUSE['BOA_NO'];
					$ba->last_boa();
					echo '<tr height="">';
					echo  '<td>'.$rowUSE['BOA_NO'].'</td>'; 
					echo  '<td>'.$ba->begin.'</td>'; 
					echo  '<td>'.get_cust_name($ba->out_cus).'</td>'; 
					echo  '<td>'.$ba->out_date.'</td>'; 
					echo  '<td>'.$ba->back_date.'</td>'; 
					echo  '<td>'.$ba->discard_date.'</td>'; 
					echo  '<td>'.$ba->discard_reason.'</td>'; 
					echo  '<td>'.$ba->discard_uid.'</td>'; 
						$objPHPExcel->getActiveSheet()->setCellValue("A".$p,$ba->bno);
						$objPHPExcel->getActiveSheet()->setCellValue("B".$p,$ba->begin);
						$objPHPExcel->getActiveSheet()->setCellValue("C".$p, iconv("big5","utf-8",get_cust_name($ba->out_cus)));
						$objPHPExcel->getActiveSheet()->setCellValue("D".$p,$ba->out_date);
						$objPHPExcel->getActiveSheet()->setCellValue("E".$ba->back_date);
						$objPHPExcel->getActiveSheet()->setCellValue("F".$p,$ba->discard_date);
						$objPHPExcel->getActiveSheet()->setCellValue("G".$p,$ba->discard_reason);
						$objPHPExcel->getActiveSheet()->setCellValue("H".$p,$ba->discard_uid);
						$p++;
				}
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
			$objWriter->save('bord1.xlsx');
}
if(isset($_POST['unuse']))
{
	echo '<br>';
	echo '<input type="button" onclick="'."window.open('./index.php?url=board_del','_self');".'" name="box1" value="前往廢棄功能">
';

	 $p=1;
		$objPHPExcel = new PHPExcel();
		$objPHPExcel = PHPExcel_IOFactory::load("./bord1.xlsx");
		$objPHPExcel->setActiveSheetIndex(2);
		 echo '<table border="1" width="">';
		 echo '<tr height="">';
		 echo  '<td>'."棧板編號".'</td>';
		 echo  '<td>'."啟用日期".'</td>';
		 echo  '<td>'."使用客戶".'</td>';
		 echo  '<td>'."出廠日期".'</td>';
		 echo  '<td>'."回收日期".'</td>';
		 echo  '<td>'."報廢日期".'</td>';
		 echo  '<td>'."報廢原因".'</td>';
		 echo  '<td>'."報廢登記人".'</td></tr>';
		$objPHPExcel->getActiveSheet()->setCellValue("A".$p, iconv("big5","utf-8","棧板編號"));
		$objPHPExcel->getActiveSheet()->setCellValue("B".$p, iconv("big5","utf-8","啟用日期"));
		$objPHPExcel->getActiveSheet()->setCellValue("C".$p, iconv("big5","utf-8","使用客戶"));
		$objPHPExcel->getActiveSheet()->setCellValue("D".$p, iconv("big5","utf-8","出廠日期"));
		$objPHPExcel->getActiveSheet()->setCellValue("E".$p, iconv("big5","utf-8","回收日期"));
		$objPHPExcel->getActiveSheet()->setCellValue("F".$p, iconv("big5","utf-8","報廢日期"));
		$objPHPExcel->getActiveSheet()->setCellValue("G".$p, iconv("big5","utf-8","報廢原因"));
		$objPHPExcel->getActiveSheet()->setCellValue("H".$p, iconv("big5","utf-8","報廢登記人"));
		$p++;
		$NOUSE="SELECT DISTINCT BOA_NO, BOA_BEGIN_DATE FROM BOARD_ALL 
				where BOA_OUT_LOC IS NULL order by BOA_BEGIN_DATE desc";
				$resultNOUSE = mssql_query($NOUSE);
				$numrowsNOUSE = mssql_num_rows($resultNOUSE);
				while($rowNOUSE=mssql_fetch_array($resultNOUSE))
				{
$ba=new board_all;
					$ba->bno=$rowNOUSE['BOA_NO'];
					$ba->last_boa();
					echo '<tr height="">';
					echo  '<td>'.$rowNOUSE['BOA_NO'].'</td>'; 
					echo  '<td>'.$ba->begin.'</td>'; 
					echo  '<td>'.get_cust_name($ba->out_cus).'</td>'; 
					echo  '<td>'.$ba->out_date.'</td>'; 
					echo  '<td>'.$ba->back_date.'</td>'; 
					echo  '<td>'.$ba->discard_date.'</td>'; 
					echo  '<td>'.$ba->discard_reason.'</td>'; 
					echo  '<td>'.$ba->discard_uid.'</td>';
						$objPHPExcel->getActiveSheet()->setCellValue("A".$p,$ba->bno);
						$objPHPExcel->getActiveSheet()->setCellValue("B".$p,$ba->begin);
						$objPHPExcel->getActiveSheet()->setCellValue("C".$p, iconv("big5","utf-8",get_cust_name($ba->out_cus)));
						$objPHPExcel->getActiveSheet()->setCellValue("D".$p,$ba->out_date);
						$objPHPExcel->getActiveSheet()->setCellValue("E".$ba->back_date);
						$objPHPExcel->getActiveSheet()->setCellValue("F".$p,$ba->discard_date);
						$objPHPExcel->getActiveSheet()->setCellValue("G".$p,$ba->discard_reason);
						$objPHPExcel->getActiveSheet()->setCellValue("H".$p,$ba->discard_uid);
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