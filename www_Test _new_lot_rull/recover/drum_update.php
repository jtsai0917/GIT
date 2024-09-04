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
auth('4-02',$_SESSION['aut']);

?>
</br><form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
日期： <input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php if($_SESSION['datepicker1']){echo $_SESSION['datepicker1'];}?>" onChange="set_date_session(this.name,this.value)">
~
<input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php if($_SESSION['datepicker2']){echo $_SESSION['datepicker2'];}?>" onChange="set_date_session(this.name,this.value)"></td> 

 
 <input name="submit" type="submit" id="submit" value=" 查詢區間內閒置桶號 ">
 
 
<input name="text" type="text" >
 <input name="submit5" type="submit" id="submit" value=" 輸入桶號單筆廢棄 ">
 
<?php
$path_root=$_SERVER['HTTP_HOST'];

		if(isset($_POST['submit4']))
		{
			
			echo '</br>';
			echo "下載";
		echo '<script>document.location.href="http://'.$path_root.'/recover/drum_list.xlsx";</script>';
		
		}
if(isset($_POST['submit']))
{		 
		
		
		echo '<br>';
		echo ' <input name="submit1" type="submit" id="submit1" value=" 前往最後確認 ">';
		echo ' <input name="submit4" type="submit" id="submit4" value=" 下載本次查詢 ">';
		echo '<table border="1" width="1000">';
		echo '</tr>';
		echo '<td>'."勾選".'</td>';
		echo '<td>'."桶號".'</td>';
		echo '<td>'."藥品".'</td>';
		echo '<td>'."出貨客戶".'</td>';
		echo '<td>'."出貨日期".'</td>';
		echo '<td>'."LOTNO".'</td>';
		
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		$NUM=1;$ENG='A';
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","桶號"));$ENG++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","藥品"));$ENG++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","出貨客戶"));$ENG++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","出貨日期"));$ENG++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8","LOTNO"));$ENG++;
		
		
	$query="select DH.* from DRUM_HISTORY_NORMAL as DH
	left join CUSTOMER_DATA as CD on CTD_CUST_NO1=CD.CTD_CUST_NO
	where   (DHN_out_DATE1>='".dod($_POST['datepicker1'])."000000"."' and DHN_OUT_DATE1<='".dod($_POST['datepicker2'])."999999"."') and (DHN_DISCARD_DATE is NULL and DHN_TRANS_DATE is NULL and DHN_MEMO is NULL) and CD.CTD_OUT_TAIWAN is null order by  DHN_OUT_DATE1";
	//echo $query;
	$result = mssql_query($query);
	$numrows=mssql_num_rows($result);
	//echo $numrows;
	$_SESSION['num']=$numrows;
	echo $_SESSION['num']."筆資料";$i=0;
	while ($row = mssql_fetch_array($result))
	{
		
		echo '</tr>';
		echo '<td>'.'<input type="checkbox" name='.$i.' value='.$row['DHN_DRUM_NO'].' checked>'.'</td>';$i++;
		echo '<td>'.$row['DHN_DRUM_NO'].'</td>';
		echo '<td>'.get_pdd_name($row['PDD_PROD_NO1']).'</td>';
		echo '<td>'.get_cust_name($row['CTD_CUST_NO1']).'</td>';
			echo '<td>'.substr($row['DHN_OUT_DATE1'],0,8).'</td>';
			echo '<td>'.$row['DHN_LOT_NO1'].'</td>';		
		$NUM++;$ENG='A';
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row['DHN_DRUM_NO']));$ENG++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",get_pdd_name($row['PDD_PROD_NO1'])));$ENG++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",get_cust_name($row['CTD_CUST_NO1'])));$ENG++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",substr($row['DHN_OUT_DATE1'],0,8)));$ENG++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM, iconv("big5","utf-8",$row['DHN_LOT_NO1']));$ENG++;
		
	}
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
			$objWriter->save('drum_list.xlsx');
}
if(isset($_POST['submit1']))
{		
	echo '<br>';
	echo ' <input name="submit2" type="submit" id="submit2" value=" 確定 ">';
	echo ' <input name="submit3" type="submit" id="submit3" value=" 取消 ">';

	echo '<table border="1" width="200">';
		echo '</tr>';
		echo '<td>'."桶號".'</td>';
		
	for($i=0;$i<=$_SESSION['num'];$i++)
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
if(isset($_POST['submit2']))
{$Q=0;

		
		$_SESSION['day']=date("YmdHis");
	for($i=0;$i<=$_SESSION['num'];$i++)
	{	
		
		if($_SESSION['A'.$i.'']!='')
		{	
				
			$query="update DRUM_HISTORY_NORMAL set DHN_DISCARD_DATE='".$_SESSION['day']."',DHN_DISCARD_MAN='".$_SESSION['uid']."' where  DHN_DRUM_NO='".$_SESSION['A'.$i.'']."' ";
			echo $query;
			$result = mssql_query($query);
			$Q++;
			$_SESSION['A'.$i.'']='';
			
		}
		
	}
	echo "已廢棄".$Q."筆資料";
}
if(isset($_POST['submit3']))
{
 	echo 
	'<script>document.location.href="'.$_SERVER['REQUEST_URI'].'"
	</script>';

}
if(isset($_POST['submit5']))
{
	$_SESSION['text']=$_POST['text'];
	
	echo '<br>'.'<input name="submit6" type="submit" id="submit6" value=" 確定廢棄 ">'.'<br>';
	echo '<table border="1" width="1000">';
		echo '</tr>';
		echo '<td>'."桶號".'</td>';
		echo '<td>'."藥品".'</td>';
		echo '<td>'."出貨客戶".'</td>';
		echo '<td>'."出貨日期".'</td>';
		echo '<td>'."LOTNO".'</td>';
		

		
	$query="select DH.* from DRUM_HISTORY_NORMAL as DH
	left join CUSTOMER_DATA as CD on CTD_CUST_NO1=CD.CTD_CUST_NO
	where DHN_DRUM_NO='".$_POST['text']."' ";	
	//echo $query;
	$result = mssql_query($query);
	while ($row = mssql_fetch_array($result))
	{
	echo '</tr>';
		
		echo '<td>'.$row['DHN_DRUM_NO'].'</td>';
		echo '<td>'.get_pdd_name($row['PDD_PROD_NO1']).'</td>';
		echo '<td>'.get_cust_name($row['CTD_CUST_NO1']).'</td>';
			echo '<td>'.substr($row['DHN_OUT_DATE1'],0,8).'</td>';
			echo '<td>'.$row['DHN_LOT_NO1'].'</td>';	
	}
}
if(isset($_POST['submit6']))
{
	
			$_SESSION['day']=date("YmdHis");

	$query="update DRUM_HISTORY_NORMAL set DHN_DISCARD_DATE='".$_SESSION['day']."',DHN_DISCARD_MAN='".$_SESSION['uid']."' where  DHN_DRUM_NO='".$_SESSION['text']."' ";
	//echo $query;
	$result = mssql_query($query);
	
	echo '<br>'.$_SESSION['text']."已廢棄";


}
?>

