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
auth('4-02',$_SESSION['aut']);

?>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
使用中棧板--

日期： <input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php if($_SESSION['datepicker1']){echo $_SESSION['datepicker1'];}?>" onChange="set_date_session(this.name,this.value)">
~
<input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php if($_SESSION['datepicker2']){echo $_SESSION['datepicker2'];}?>" onChange="set_date_session(this.name,this.value)"></td> 

<input name="submit1" type="submit" id="submit1" value=" 查詢 ">
<input name="submit2" type="submit" id="submit2" value=" 下載 ">

<br>

<?php 
$path_root=$_SERVER['HTTP_HOST'];

		if(isset($_POST['submit2']))
		{
			
			echo '</br>';
			echo "下載";
		echo '<script>document.location.href="http://'.$path_root.'/recover/bord_unuse.xlsx";</script>';
		
		}
		
		
		
if(isset($_POST['submit1']))
{
		echo '<input type="submit" name="submit3" value="廢棄勾選棧板">';
			echo '<input type="submit" name="submit6" value="全選">';
			
	echo '<br>';
	 $p=1;
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		 echo '<table border="1" width="500">';
		 echo '<tr height="">';
		 echo  '<td>'."棧板編號".'</td>';
		 echo  '<td>'."啟用日期".'</td>';
		 echo  '<td>'."使用客戶".'</td>';
		 echo  '<td>'."出廠日期".'</td>';
		 echo  '<td>'."選擇".'</td>';
		 
		 $objPHPExcel->getActiveSheet()->setCellValue("A".$p, iconv("big5","utf-8","棧板編號"));
		$objPHPExcel->getActiveSheet()->setCellValue("B".$p, iconv("big5","utf-8","啟用日期"));
		$objPHPExcel->getActiveSheet()->setCellValue("C".$p, iconv("big5","utf-8","使用客戶"));
		$objPHPExcel->getActiveSheet()->setCellValue("D".$p, iconv("big5","utf-8","出廠日期"));
		$p++;
		$NOUSE="select BA.BOD_NO,BA.BOD_BEGIN_DATE,BA.BOD_OUT_LOC,BA.BOD_OUT_DATE ,CD.CTD_CUST_SHORT_NAME
				from BOARD AS BA
				left join CUSTOMER_DATA AS CD on BA.BOD_OUT_LOC=CD.CTD_CUST_NO 
				where BA.BOD_OUT_LOC!='' and BOD_OUT_DATE>='".dod($_POST['datepicker1'])."' 
				and BOD_OUT_DATE<='".dod($_POST['datepicker2'])."'";
				//echo $NOUSE;
				$W=0;
				$resultNOUSE = mssql_query($NOUSE);
				$numrowsNOUSE = mssql_num_rows($resultNOUSE);
				$_SESSION['num1']=$numrowsNOUSE;
				while($rowNOUSE=mssql_fetch_array($resultNOUSE))
				{
					
					echo '<tr height="">';
					 echo  '<td>'.$rowNOUSE['BOD_NO'].'</td>'; 
					  echo  '<td>'.$rowNOUSE['BOD_BEGIN_DATE'].'</td>'; 
					   echo  '<td>'.$rowNOUSE['CTD_CUST_SHORT_NAME'].'</td>'; 
					    echo  '<td>'.$rowNOUSE['BOD_OUT_DATE'].'</td>'; 
					    echo  '<td>'.'<input type="checkbox" value='.$rowNOUSE['BOD_NO'].' name='.$W.'>'.'</td>';$W++; 

						$objPHPExcel->getActiveSheet()->setCellValue("A".$p,$rowNOUSE['BOD_NO']);
						
						$objPHPExcel->getActiveSheet()->setCellValue("B".$p,$rowNOUSE['BOD_BEGIN_DATE']);
						
						$objPHPExcel->getActiveSheet()->setCellValue("C".$p, iconv("big5","utf-8",$rowNOUSE['CTD_CUST_SHORT_NAME']));
						
						$objPHPExcel->getActiveSheet()->setCellValue("D".$p,$rowNOUSE['BOD_OUT_DATE']);
						$p++;
				}
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
			$objWriter->save('bord_unuse.xlsx');
}
if(isset($_POST['submit6']))
{
		echo '<input type="submit" name="submit3" value="廢棄勾選棧板">';
			echo '<input type="submit" name="submit1" value="全部取消">';
			
	echo '<br>';
	 $p=1;
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		 echo '<table border="1" width="500">';
		 echo '<tr height="">';
		 echo  '<td>'."棧板編號".'</td>';
		 echo  '<td>'."啟用日期".'</td>';
		 echo  '<td>'."使用客戶".'</td>';
		 echo  '<td>'."出廠日期".'</td>';
		 echo  '<td>'."選擇".'</td>';
		 
		 $objPHPExcel->getActiveSheet()->setCellValue("A".$p, iconv("big5","utf-8","棧板編號"));
		$objPHPExcel->getActiveSheet()->setCellValue("B".$p, iconv("big5","utf-8","啟用日期"));
		$objPHPExcel->getActiveSheet()->setCellValue("C".$p, iconv("big5","utf-8","使用客戶"));
		$objPHPExcel->getActiveSheet()->setCellValue("D".$p, iconv("big5","utf-8","出廠日期"));
		$p++;
		$NOUSE="select BA.BOD_NO,BA.BOD_BEGIN_DATE,BA.BOD_OUT_LOC,BA.BOD_OUT_DATE ,CD.CTD_CUST_SHORT_NAME
				from BOARD AS BA
				left join CUSTOMER_DATA AS CD on BA.BOD_OUT_LOC=CD.CTD_CUST_NO 
				where BA.BOD_OUT_LOC!='' and BOD_OUT_DATE>='".dod($_POST['datepicker1'])."' 
				and BOD_OUT_DATE<='".dod($_POST['datepicker2'])."'";
				//echo $NOUSE;
				$W=0;
				$resultNOUSE = mssql_query($NOUSE);
				$numrowsNOUSE = mssql_num_rows($resultNOUSE);
				$_SESSION['num1']=$numrowsNOUSE;
				while($rowNOUSE=mssql_fetch_array($resultNOUSE))
				{
					
					echo '<tr height="">';
					 echo  '<td>'.$rowNOUSE['BOD_NO'].'</td>'; 
					  echo  '<td>'.$rowNOUSE['BOD_BEGIN_DATE'].'</td>'; 
					   echo  '<td>'.$rowNOUSE['CTD_CUST_SHORT_NAME'].'</td>'; 
					    echo  '<td>'.$rowNOUSE['BOD_OUT_DATE'].'</td>'; 
					    echo  '<td>'.'<input type="checkbox" value='.$rowNOUSE['BOD_NO'].' name='.$W.' checked="checked">'.'</td>';$W++; 

						$objPHPExcel->getActiveSheet()->setCellValue("A".$p,$rowNOUSE['BOD_NO']);
						
						$objPHPExcel->getActiveSheet()->setCellValue("B".$p,$rowNOUSE['BOD_BEGIN_DATE']);
						
						$objPHPExcel->getActiveSheet()->setCellValue("C".$p, iconv("big5","utf-8",$rowNOUSE['CTD_CUST_SHORT_NAME']));
						
						$objPHPExcel->getActiveSheet()->setCellValue("D".$p,$rowNOUSE['BOD_OUT_DATE']);
						$p++;
				}
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
			$objWriter->save('bord_unuse.xlsx');
}


if(isset($_POST['submit3']))
{
	echo '<br>';
	echo ' <input name="submit4" type="submit" id="submit4" value=" 確定 ">';
	echo ' <input name="submit5" type="submit" id="submit5" value=" 取消 ">';

	echo '<table border="1" width="200">';
		echo '</tr>';
		echo '<td>'."棧板編號".'</td>';
		
	for($i=0;$i<=$_SESSION['num1'];$i++)
	{	
		
		if($_POST[$i]!='')
		{	
			$_SESSION['A'.$i.'']=$_POST[$i];
			echo '</tr>';
			echo '<td>'.$_POST[$i].'</td>';
				
			
			
		}
		
	}
	echo "確定廢棄以下資料?";
	
}
if(isset($_POST['submit4']))
{$Q=0;

		
		$_SESSION['day']=date("YmdHis");
	for($i=0;$i<=$_SESSION['num1'];$i++)
	{	
		
		if($_SESSION['A'.$i.'']!='')
		{	
			$query="select top 1 * from BOARD_ALL where BOA_NO='".$_SESSION['A'.$i.'']."' order by BOA_TIMES desc ";
				$result = mssql_query($query);
				$numrows = mssql_num_rows($result);	
				while($row=mssql_fetch_array($result))
				{
					if($numrows>0){$times=$row['BOA_TIMES'];}
					else{$times=1;}
				}
			
			$query="delete from BOARD where BOD_NO='".$_SESSION['A'.$i.'']."'";
			$query1="update BOARD_ALL set BOA_DISCARD_DATE='".date("Ymd")."',BOA_DISCARD_REASON='byprogram',BOA_DISCARD_UID='".$_SESSION['uid']."' where BOA_NO='".$_SESSION['A'.$i.'']."' and BOA_TIMES='".$times."'";
				
			//echo $query;
			//echo $query1;
			$result = mssql_query($query);
			$result1 = mssql_query($query1);
			$Q++;
			$_SESSION['A'.$i.'']='';
			
		}
		
	}
	echo "已廢棄".$Q."筆資料";
}
if(isset($_POST['submit5']))
{
 	echo 
	'<script>document.location.href="'.$_SERVER['REQUEST_URI'].'"
	</script>';

}
?>