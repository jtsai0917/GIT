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
datepick1();
function sta1($ds){   //daytime-style  20150902010203=>2015/09/02 01:02:03
if($ds<>NULL){$dat=substr($ds,0,+4)."/".substr($ds,4,+2)."/".substr($ds,6,+2);}
return $dat;
}
function datepick1(){
echo '
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
  <link rel="stylesheet" href="/css/jquery-ui.css">
  <link rel="stylesheet" href="/css/style.css">
  <script src="/css/jquery-1.12.4.js"></script>
  <script src="/css/jquery-ui.js"></script>
  <script src="/css/datepicker-zh-TW.js"></script>
  <script src="/css3menu/GridViewScroll/gridviewScroll.js"></script>
  <link href="/css3menu/GridViewScroll/GridviewScroll.css" rel="stylesheet" />
  <script type="text/javascript">
    $(function() {
  
  $( "#datepicker5" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
  });
  function set_date_session(nam,val){
	window.open("/backend.php?name="+nam+"&value="+val)  
  }
</script>
</head>';
}
?>
 </br><form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
 TOTO 容器編號: <input name="toto" type="text" id="toto" size="10"  />
充填日期： <input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php if($_SESSION['datepicker1']){echo $_SESSION['datepicker1'];}?>" onChange="set_date_session(this.name,this.value)">
~
<input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php if($_SESSION['datepicker2']){echo $_SESSION['datepicker2'];}?>" onChange="set_date_session(this.name,this.value)"></td>
<input type="button" name="X3" id="X3" value="X" onclick="window.open('./erase_time.php ', '_self');" />

回廠日期： <input name="datepicker3" type="text" id="datepicker3" size="10" value="<?php if($_SESSION['datepicker3']){echo $_SESSION['datepicker3'];}?>" onChange="set_date_session(this.name,this.value)">
~
<input name="datepicker4" type="text" id="datepicker4" size="10" value="<?php if($_SESSION['datepicker4']){echo $_SESSION['datepicker4'];}?>" onChange="set_date_session(this.name,this.value)"></td>
啟用日期： <input name="datepicker5" type="text" id="datepicker5" size="10" value="<?php if($_SESSION['datepicker5']){echo $_SESSION['datepicker5'];}?>" onChange="set_date_session(this.name,this.value)">
     
        <input name="submit" type="submit" class="center button" id="submit" value="  查    詢  ">
         <input name="submit3" type="submit" class="center button" id="submit3" value=" 下載報告 ">
<?php 
if(isset($_POST['submit']))
{	


	$date1=dod($_SESSION['datepicker1']);
	$date2=dod($_SESSION['datepicker2']);
	$date3=dod($_SESSION['datepicker3']);
	$date4=dod($_SESSION['datepicker4']);
	$date5=dod($_SESSION['datepicker5']);
	$query="select DTD.*,DHT.HTM_VALIDATE,CD.CTD_CUST_SHORT_NAME
	from DRUM_HISTORY_TOTO_DETAIL as DTD
	left join DRUM_HISTORY_TOTO as DHT on DTD.HTM_DRUM_NO=DHT.HTM_DRUM_NO
	left join CUSTOMER_DATA as CD on DTD.CTD_CUST_NO=CD.CTD_CUST_NO
	where DTD.HTD_SERIAL_NO like '%%'  ";
	if($_POST['toto']<>'')
	{
	$query=$query." and DTD.HTM_DRUM_NO='".$_POST['toto']."' ";
	}
	if($_SESSION['datepicker1']<>'')
	{
	$query=$query." and DTD.HTD_FILLED_DATE>'".$date1."' and DTD.HTD_FILLED_DATE<'".$date2."' ";
	//echo $query;
	}
	if($_SESSION['datepicker3']<>'')
	{
	$query=$query." and DTD.HTD_TYS_RCV_DATE>'".$date3."' and DTD.HTD_TYS_RCV_DATE<'".$date4."' ";
	}
	if($_SESSION['datepicker5']<>'')
	{
	$query=$query." and DTD.HTM_CREATE_DATE='".$date5."' ";
	}
	$p=1;
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	$result = mssql_query($query);
	$numrows = mssql_num_rows($result);
	echo '<table border="1" width="1500">';
	echo '<tr height="">';
	echo  '<td>'."品名".'</td>';
	echo  '<td>'."客戶名稱".'</td>';
	echo  '<td>'."TOTO NO".'</td>';
	echo  '<td>'."使用期限".'</td>';
	echo  '<td>'."充填日".'</td>';
	echo  '<td>'."LOT NO".'</td>';
	echo  '<td>'."藥品有效期限".'</td>';
	echo  '<td>'."出荷(移液)日".'</td>';
	echo  '<td>'."移入槽編號".'</td>';
	echo  '<td>'."TYS回收日".'</td>';
	echo  '<td>'."廢棄(轉用)日".'</td>';
	echo  '<td>'."出場作業人員".'</td>';
	$objPHPExcel->getActiveSheet()->setCellValue("A".$p, iconv("big5","utf-8","品名"));
	$objPHPExcel->getActiveSheet()->setCellValue("B".$p, iconv("big5","utf-8","客戶名稱"));
	$objPHPExcel->getActiveSheet()->setCellValue("C".$p, iconv("big5","utf-8","TOTO NO"));
	$objPHPExcel->getActiveSheet()->setCellValue("D".$p, iconv("big5","utf-8","使用期限"));
	$objPHPExcel->getActiveSheet()->setCellValue("E".$p, iconv("big5","utf-8","充填日"));
	$objPHPExcel->getActiveSheet()->setCellValue("F".$p, iconv("big5","utf-8","LOT NO"));
	$objPHPExcel->getActiveSheet()->setCellValue("G".$p, iconv("big5","utf-8","藥品有效期限"));
	$objPHPExcel->getActiveSheet()->setCellValue("H".$p, iconv("big5","utf-8","出荷(移液)日"));
	$objPHPExcel->getActiveSheet()->setCellValue("I".$p, iconv("big5","utf-8","移入槽編號"));
	$objPHPExcel->getActiveSheet()->setCellValue("J".$p, iconv("big5","utf-8","TYS回收日"));
	$objPHPExcel->getActiveSheet()->setCellValue("K".$p, iconv("big5","utf-8","廢棄(轉用)日"));
	$objPHPExcel->getActiveSheet()->setCellValue("L".$p, iconv("big5","utf-8","出場作業人員"));
	$p++;
	while($row=mssql_fetch_array($result))
	{
	$ENG='A';
	echo '<tr height="">';
	if(substr($row['HTM_DRUM_NO'],1,1)==1)
	{
		echo  '<td>'."CAL1".'</td>';
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$p,"CAL1");$ENG++;
	}
	if(substr($row['HTM_DRUM_NO'],1,1)==2)
	{
		echo  '<td>'."CAL2".'</td>';
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$p,"CAL2");$ENG++;
	}
	if(substr($row['HTM_DRUM_NO'],1,1)==3)
	{
		echo  '<td>'."CAL3".'</td>';
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$p,"CAL3");$ENG++;
	}
	if(substr($row['HTM_DRUM_NO'],1,1)==4)
	{
		echo  '<td >'."CAL4".'</td>';
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$p,"CAL4");$ENG++;
	}
	echo  '<td>'.$row['CTD_CUST_SHORT_NAME'].'</td>';
	$objPHPExcel->getActiveSheet()->setCellValue($ENG.$p, iconv("big5","utf-8",$row['CTD_CUST_SHORT_NAME']));$ENG++;
	echo  '<td>'.$row['HTM_DRUM_NO'].'</td>';
	$objPHPExcel->getActiveSheet()->setCellValue($ENG.$p,$row['HTM_DRUM_NO']);$ENG++;
	echo  '<td width="120">'.sta1($row['HTM_VALIDATE']).'</td>';
	$objPHPExcel->getActiveSheet()->setCellValue($ENG.$p,sta1($row['HTM_VALIDATE']));$ENG++;
	echo  '<td width="120">'.sta1($row['HTD_FILLED_DATE']).'</td>';
	$objPHPExcel->getActiveSheet()->setCellValue($ENG.$p,sta1($row['HTD_FILLED_DATE']));$ENG++;
	echo  '<td>'.$row['HTD_LOT_NO'].'</td>';
	$objPHPExcel->getActiveSheet()->setCellValue($ENG.$p,$row['HTD_LOT_NO']);$ENG++;
	
	
	$query1="select * from CUSTOMER_PRODUCTS where CTD_CUST_NO='".$row['CTD_CUST_NO']."' and PDD_PROD_NO='".$row['PDD_PROD_NO']."'";
	$result1 = mssql_query($query1);
	$numrows1 = mssql_num_rows($result1);
	while($row1=mssql_fetch_array($result1))
	{
		$m=$row1['CTP_VALID_MON'];
		$vday=sta1(date("Ymd",strtotime ( "+$m month", strtotime ($row['HTD_FILLED_DATE']."0000"))));
		echo '<td width="120">'.$vday.'</td>';
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$p,$vday);$ENG++;
	}
	echo  '<td width="120">'.sta1($row['HTD_WORK_DATE']).'</td>';
	$objPHPExcel->getActiveSheet()->setCellValue($ENG.$p,sta1($row['HTD_WORK_DATE']));$ENG++;
	echo  '<td>'.$row['HTD_MOVE_DRUM_NO'].'</td>';
	$objPHPExcel->getActiveSheet()->setCellValue($ENG.$p,$row['HTD_MOVE_DRUM_NO']);$ENG++;
	echo  '<td width="120">'.sta1($row['HTD_TYS_RCV_DATE']).'</td>';
	$objPHPExcel->getActiveSheet()->setCellValue($ENG.$p,sta1($row['HTD_TYS_RCV_DATE']));$ENG++;
	echo  '<td>'.'</td>';
	$objPHPExcel->getActiveSheet()->setCellValue($ENG.$p,"");$ENG++;
	echo  '<td>'.'</td>';
	$objPHPExcel->getActiveSheet()->setCellValue($ENG.$p,"");$ENG++;
	$p++;
	}//$query while 括號
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
			$objWriter->save('CAL_TOTO.xlsx');
}//if submit
  echo '</table>';
		if(isset($_POST['submit3']))		
		{			
			$path_root=$_SERVER['HTTP_HOST'];
			echo '</br>';			
		echo '<script>document.location.href="http://'.$path_root.'/recover/CAL_TOTO.xlsx";</script>';
		}


?>
        
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 