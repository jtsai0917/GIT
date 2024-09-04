<?php
echo '<font size="+2">樣品瓶儲位查詢</font><br>';
session_start();
include("../lib/fun.php");
include("../connections/conn.php"); 
include("../checkuser.php");
$_SESSION['lasturl']=$_SERVER['REQUEST_URI'];
datepick();
?>
<form name="form1" action="" method="post">
  <table width="1240" border="1">
  	<tr>
  	</tr>
    <tr>
      <td width="250" bgcolor="#FFFFFF" class="d1">日期:
        <input name="datepicker1" type="text" id="datepicker1"  size="14"  value="<?php 	echo $_SESSION['datepicker1'];?>"  onchange="set_date_session(this.name,this.value)">
~
<input name="datepicker2" type="text" id="datepicker2" size="14"  value="<?php 	echo $_SESSION['datepicker2'];?>"  onchange="set_date_session(this.name,this.value)">&nbsp;&nbsp;&nbsp;站別: 
			<select name="station" id="station">
				<option value="0"></option>
				<option value="1">充填現場</option>
				<option value="2">樣品室</option>
				<option value="3">客戶</option>
				<option value="4">分析</option>
			</select>

			</td>
      <td width="250" class="d1">
        <label for="sn"></label>
       瓶號：
      <input type="text" name="smpno" id="smpno" onChange="set_date_session(this.name,this.value)" value="<?php echo $_SESSION['smpno'];?>"></td>

      <td width="100" class="centerutton">        <span class="d1">
        <input name="submit" type="submit" class="centerutton" id="submit" value="         查      詢         ">     
      </span></td>
    </tr>
    <tr>
      <td width="250" class="d1"><span class="d1">藥品名:
        <input type="button" name="X" id="X" value="X" onClick="window.open('../erase_prod.php ', '_self');">
        <input name="pdd_chemical1" type="text" id="pdd_chemical1" size="16" value="<?php echo $_SESSION['prod_no']?>" readonly>
        <input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onClick="window.open('../pdd_prod_no.php ', '_self');" >
<input name="pdd_chemical2" type="text" id="pdd_chemical2" size="20" value="<?php echo $_SESSION['prod_name']?>" readonly>
      </span></td>
      <td width="250" class="d1">客戶：
        <input type="button" name="X2" id="X2" value="X" onClick="window.open('../erase_customer.php ', '_self');">
        <input name="pdd_chemical3" type="text" id="pdd_chemical3" size="16" value="<?php echo $_SESSION['cust_no']?>" readonly>
        <input type="button" name="pdd_no2" id="pdd_no2" value="查詢客戶" onClick="window.open('../cust_no.php?sup=N ', '_self');">
      <input name="pdd_chemical4" type="text" id="pdd_chemical4" size="20" value="<?php echo $_SESSION['cust_name']?>"></td>

      <td width="100" class="centerutton">        <span class="d1">
      	<input type="submit" name="excel2" id="excel2" value=" 產生 EXCLE ">   
      </span></td>
    </tr>
  </table>
</form>
<?php
if(isset($_POST['excel2'])){
	include_once("../PHPEXCEL/Classes/PHPExcel.php");
	include_once("../PHPEXCEL/Classes/PHPExcel/IOFactory.php");
	$objPHPExcel = PHPExcel_IOFactory::load("sample.xlsx");
	$objPHPExcel->setActiveSheetIndex(0);
	$i=2;
	$j=1;
	$query="SELECT DISTINCT SMPID, gid FROM Sample_Location WHERE          (createtime IS NOT NULL) ";
	if($_POST['datepicker1']<>''){
		$query.=" and  (createtime > CONVERT(DATETIME, '".gts($_POST['datepicker1'])." 00:00:00', 102))";
	}
	if($_POST['datepicker2']<>''){
		$query.=" and  (createtime <= CONVERT(DATETIME, '".gts($_POST['datepicker2'])." 23:25:59', 102))";
	}
	if($_SESSION['prod_no']<>''){
		$pdd_short=pdd_short($_SESSION['prod_no']);
		$query.=" and  (SUBSTRING(SMPID, 2, 2) = '".$pdd_short."')";
	}
	if(trim($_POST['smpno'])<>''){
		$query.=" and  SMPID = '".$_POST['smpno']."'";
	}
		if(trim($_POST['station'])<>'0'){
		$query.=" and  LOCATION = '".$_POST['station']."'";
	}
	$query.=" order by gid";
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		$smpid=trim($row['SMPID']);
		$gid=$row['gid'];
		$lot_no=lot_no($smpid,$gid);
		$cust_no=cust_no($smpid,$gid);
		if(trim($cust_no)=='NULL'){$cust_no='';}
		$createtime=createtime($smpid,$gid);
		$last_location=last_location($smpid,$gid);
		$bb=loc_aa(trim($row['SMPID']),trim($row['gid']));
		$time1=$bb[0];
		$time2=$bb[1];
		$time3=$bb[2];
		$time4=$bb[3];
		$time5=$bb[4];
		$objPHPExcel->getActiveSheet()->setCellValue("A".$i,$j);
		$objPHPExcel->getActiveSheet()->setCellValue("B".$i,$gid);
		$objPHPExcel->getActiveSheet()->setCellValue("C".$i,trim($lot_no));
		$objPHPExcel->getActiveSheet()->setCellValue("D".$i,$smpid);
		$objPHPExcel->getActiveSheet()->setCellValue("E".$i,$cust_no);
		$objPHPExcel->getActiveSheet()->setCellValue("F".$i,iconv("big5","utf-8",get_cust_name($cust_no)));
		$objPHPExcel->getActiveSheet()->setCellValue("G".$i,iconv("big5","utf-8",$last_location));
		$objPHPExcel->getActiveSheet()->setCellValue("H".$i,$time1);
		$objPHPExcel->getActiveSheet()->setCellValue("I".$i,$time2);
		$objPHPExcel->getActiveSheet()->setCellValue("J".$i,$time4);
		$objPHPExcel->getActiveSheet()->setCellValue("K".$i,$time3);
		$objPHPExcel->getActiveSheet()->setCellValue("L".$i,$time5);
		$i++;
		$j++;
	}
	
	$objPHPExcel->getActiveSheet()->getStyle('H2:L'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD);
/*
	$objPHPExcel->getActiveSheet()->getStyle('I1')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD);
	$objPHPExcel->getActiveSheet()->getStyle('J1')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD);
	$objPHPExcel->getActiveSheet()->getStyle('K1')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD);
	$objPHPExcel->getActiveSheet()->getStyle('L1')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD);
*/	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
	$objWriter->save('sample_report.xlsx');  
	$path_root=$_SERVER['HTTP_HOST'];		
	echo '<script>document.location.href="http://'.$path_root.'/recover/sample_report.xlsx";</script>';
}


if(isset($_POST['submit'])){
	$i=1;
	echo '<table width="1280" border="1"><tr><td width="8" align="center">Item</td><td width="8" align="center">GID</td><td width="100" align="center">LotNo</td><td width="100" align="center">瓶號</td><td width="100" align="center">客戶代碼</td>
	<td width="100" align="center">客戶名稱</td><td width="100" align="center">目前站別</td><td width="150" align="center">MCTW充填現場</td><td width="150" align="center">保存樣品室</td>
	<td width="150" align="center">分析</td><td width="150" align="center">客戶端</td><td width="150" align="center">客戶端刷入時間</td></tr>';
	$query="SELECT DISTINCT SMPID, gid FROM Sample_Location WHERE          (createtime IS NOT NULL) ";
	if($_POST['datepicker1']<>''){
		$query.=" and  (createtime > CONVERT(DATETIME, '".gts($_POST['datepicker1'])." 00:00:00', 102))";
	}
	if($_POST['datepicker2']<>''){
		$query.=" and  (createtime <= CONVERT(DATETIME, '".gts($_POST['datepicker2'])." 23:25:59', 102))";
	}
	if($_SESSION['prod_no']<>''){
		$pdd_short=pdd_short($_SESSION['prod_no']);
		$query.=" and  (SUBSTRING(SMPID, 2, 2) = '".$pdd_short."')";
	}
	if(trim($_POST['smpno'])<>''){
		$query.=" and  SMPID = '".$_POST['smpno']."'";
	}
		if(trim($_POST['station'])<>'0'){
		$query.=" and  LOCATION = '".$_POST['station']."'";
	}
	$query.=" order by gid";
//	echo $query."<BR>";
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		$smpid=trim($row['SMPID']);
		$gid=$row['gid'];
		$lot_no=lot_no($smpid,$gid);
		$cust_no=cust_no($smpid,$gid);
		if(trim($cust_no)=='NULL'){$cust_no='';}
		$createtime=createtime($smpid,$gid);
		$last_location=last_location($smpid,$gid);
		$bb=loc_aa(trim($row['SMPID']),trim($row['gid']));
		$time1=$bb[0];
		$time2=$bb[1];
		$time3=$bb[2];
		$time4=$bb[3];
		$time5=$bb[4];
		
		echo '<tr><td width="8" align="center">'.$i.'</td><td width="8" align="center">'.$gid.'</td><td width="8" align="center">'.$lot_no.'</td><td width="100" align="center">'.$smpid.'</td><td width="100" align="center">'.$cust_no.'</td>
		<td width="100" align="center">'.get_cust_name($cust_no).'</td><td width="100" align="center">'.$last_location.'</td>
		<td width="100" align="center">'.$time1.'</td><td width="100" align="center">'.$time2.'</td><td width="100" align="center">'.$time4.'</td><td width="100" align="center">'.$time5.'</td><td width="100" align="center">'.$time3.'</td></tr>';
		$i++;
	}	
	echo '</table><br>';
}
function loc_aa($smpid,$gid){
	$query="SELECT [index], SMPID, Lot_NO, CONVERT(VARCHAR, createtime, 120) AS createtime , LOCATION, CONVERT(VARCHAR, Out_Date, 120) AS Out_Date FROM Sample_Location WHERE (SMPID = N'$smpid') AND (gid = $gid) ORDER BY   [index]";
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		if($row['LOCATION']==1){$time1=str_replace("-","/",$row['createtime']);}
		elseif($row['LOCATION']==2){$time2=str_replace("-","/",$row['createtime']);}
		elseif($row['LOCATION']==3){$time3=str_replace("-","/",$row['createtime']);$time5=str_replace("-","/",$row['Out_Date']);}
		elseif($row['LOCATION']==4){$time4=str_replace("-","/",$row['createtime']);}
	}
	$aa=array(0 => $time1, 1 => $time2, 2 => $time3, 3 => $time4, 4 => $time5);
	return $aa;
}

function pdd_short($prodno){
	$query="SELECT PDD_PROD_SHORT_NAME FROM PRODUCT_DATA WHERE (PDD_PROD_NO = '$prodno')";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	return $row[0];
}

function cust_no($smpid,$gid){
	$query="SELECT top 1 Cust_NO  FROM Sample_Location WHERE (SMPID = N'$smpid') AND (gid = $gid) and (Cust_NO <> '') ";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	return $row[0];
}

function createtime($smpid,$gid){
	$query="SELECT TOP (1) CONVERT(VARCHAR, createtime, 120) AS createtime FROM Sample_Location WHERE (SMPID = N'$smpid') AND (gid = $gid) ORDER BY createtime DESC ";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	return $row[0];
}

function lot_no($smpid,$gid){
	$query="SELECT top 1 Lot_NO  FROM Sample_Location WHERE (SMPID = N'$smpid') AND (gid = $gid) and (Lot_NO <> '') ";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	return $row[0];
}

function last_location($smpid,$gid){
	$query="SELECT          TOP (1) Sample_Location_Name.location_name FROM Sample_Location INNER JOIN Sample_Location_Name ON Sample_Location.LOCATION = Sample_Location_Name.[index] 
	WHERE (SMPID = N'$smpid') AND (gid = $gid) ORDER BY   Sample_Location.createtime DESC";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	return $row[0];
}

?>