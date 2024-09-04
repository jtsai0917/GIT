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

?>荷姿變更一覽表
 </br><form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
 日期： <input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php if($_SESSION['datepicker1']){echo $_SESSION['datepicker1'];}?>" onChange="set_date_session(this.name,this.value)">
~
<input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php if($_SESSION['datepicker2']){echo $_SESSION['datepicker2'];}?>" onChange="set_date_session(this.name,this.value)"></td>
變更理由:<?php selected();?>
原LOT:<input name="oldlot" type="text" id="oldlot" size="10"  />
新LOT <input name="lotno" type="text" id="lotno" size="10"  />
<br>
原藥品名:
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
		}?>"readonly>
   
        
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
       
        

新藥品名:
<input name="pdd_chemical12" type="text" id="pdd_chemical12" size="10" value="<?php 
		if ($_GET['prod_no1']){
			echo $_GET['prod_no1'];
			$_SESSION['prod_no1']=$_GET['prod_no1'];
		}
		elseif($_SESSION['prod_no1']){
			echo $_SESSION['prod_no1'];
		}
		else{
		echo '';
		}?>"readonly>
   
        
		<input name="textfield1" type="text" id="textfield1" size="10" value="<?php 
if ($_GET['name']){
			echo $_GET['prod_name1'];
			$_SESSION['prod_name1']=$_GET['prod_name1'];
		}
		elseif($_SESSION['prod_name1']){
			echo $_SESSION['prod_name1'];
		}
		else{
		echo '';
		}		
		?>" readonly>
        
<input type="button" name="pdd" id="pdd" value="選擇藥品" onClick="window.open('../recover/pdd_prod1.php ', '_self');" >
        <input type="button" name="X" id="X" value="X" onclick="window.open('../recover/erase_prod.php ', '_self');" />
        <br>
 <input name="submit" type="submit" class="center button" id="submit" value="  查    詢  ">
                  <input name="submit3" type="submit" class="center button" id="submit3" value=" 下載報告 ">


<?php 
if(isset($_POST['submit']))
{
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	$L=2;
	$ENG=A;
	//echo get
	$date1=dod($_SESSION['datepicker1']);
	$date2=dod($_SESSION['datepicker2']);		
	$query="select SM.STM_DATE,SM.PDD_PROD_NO,PDD.PDD_PROD_NAME,PDD.PDD_STYLE,SM.STM_OLD_PROD_NO,PDD.PDD_PROD_NAME,
	SM.STM_OLD_LOT_NO,SM.STM_OLD_QTY,SM.STM_NEW_LOT_NO,SM.STM_NEW_QTY,SM.STM_PRN_BAR_DATE,SM.SAG_NO,
	SM.STM_MOD_COUNT,SM.STM_STATE,SM.STM_MEMO,SM.STM_REASON
	from STYLE_MODIFY as SM
	inner join PRODUCT_DATA as PDD on SM.PDD_PROD_NO=PDD.PDD_PROD_NO
	
	where SM.STM_DATE>'".$date1."' and SM.STM_DATE<'".$date2."' ";
	if($_POST['selected']<>0)
	{
		$query=$query."and SM.STM_REASON like '%".$_POST['selected']."%'";
	}
	if($_POST['oldlot']<>'')
	{
		$query=$query."and SM.STM_OLD_LOT_NO='".$_POST['oldlot']."'";
	}
	if($_POST['lotno']<>'')
	{
		$query=$query."and SM.STM_NEW_LOT_NO='".$_POST['lotno']."'";
	}
	if($_SESSION['prod_no']<>'')
	{
		$query=$query."and SM.STM_OLD_PROD_NO='".$_SESSION['prod_no']."'";
	}
	if($_SESSION['prod_no1']<>'')
	{
		$query=$query."and SM.PDD_PROD_NO='".$_SESSION['prod_no1']."'";
	}
	$query=$query."order by SM.STM_DATE";
	//echo $query;
	$result = mssql_query($query);
	$numRows=mssql_num_rows($result);	
	echo '<table border="1" width="2000">';
	echo '<tr height="">';
	echo  '<td>'."是否取消".'</td>';
	echo  '<td>'."日期".'</td>';
	echo  '<td>'."藥品名".'</td>';
	echo  '<td>'."包裝形態".'</td>';
	echo  '<td>'."變更理由".'</td>';
	echo  '<td>'."原料號".'</td>';
	echo  '<td>'."原LOTNO".'</td>';
	echo  '<td>'."原庫存量".'</td>';
	echo  '<td>'."新料號".'</td>';
	echo  '<td>'."新LOTNO".'</td>';
	echo  '<td>'."新LOTNO量".'</td>';
	echo  '<td>'."剩餘數量".'</td>';
	echo  '<td>'."條碼製作時間".'</td>';
	echo  '<td>'."備註".'</td>';
	$objPHPExcel->getActiveSheet()->setCellValue("A1", iconv("big5","utf-8","是否取消"));
	$objPHPExcel->getActiveSheet()->setCellValue("B1", iconv("big5","utf-8","日期"));
	$objPHPExcel->getActiveSheet()->setCellValue("C1", iconv("big5","utf-8","藥品名"));
	$objPHPExcel->getActiveSheet()->setCellValue("D1", iconv("big5","utf-8","包裝形態"));
	$objPHPExcel->getActiveSheet()->setCellValue("E1", iconv("big5","utf-8","變更理由"));
	$objPHPExcel->getActiveSheet()->setCellValue("F1", iconv("big5","utf-8","原料號"));
	$objPHPExcel->getActiveSheet()->setCellValue("G1", iconv("big5","utf-8","原LOTNO"));
	$objPHPExcel->getActiveSheet()->setCellValue("H1", iconv("big5","utf-8","原庫存量"));
	$objPHPExcel->getActiveSheet()->setCellValue("I1", iconv("big5","utf-8","新料號"));
	$objPHPExcel->getActiveSheet()->setCellValue("J1", iconv("big5","utf-8","新LOTNO"));
	$objPHPExcel->getActiveSheet()->setCellValue("K1", iconv("big5","utf-8","新LOTNO量"));
	$objPHPExcel->getActiveSheet()->setCellValue("L1", iconv("big5","utf-8","剩餘數量"));
	$objPHPExcel->getActiveSheet()->setCellValue("M1", iconv("big5","utf-8","條碼製作時間"));
	$objPHPExcel->getActiveSheet()->setCellValue("N1", iconv("big5","utf-8","備註"));
	while ($row = mssql_fetch_array($result))
	{   $bb=$row['STM_OLD_QTY']-$row['STM_NEW_QTY'];
		$aa=explode(";",$row['STM_REASON']);
		
		echo '<tr height="">';
		$query1="select SAG_TERMINATE_TIME
		from SIGN_AGREE
		where SAG_NO='".$row['SAG_NO']."'";
		$result1 = mssql_query($query1);
		while ($row1 = mssql_fetch_array($result1))
		{
			if($row1['SAG_TERMINATE_TIME']=='')
			{
				echo '<td>'.'</td>';
				$ENG++;
			}
			else{
				echo '<td>'."以取消".'</td>';
				$objPHPExcel->getActiveSheet()->setCellValue($ENG.$L, iconv("big5","utf-8","以取消"));
				$ENG++;
				}
		}
		echo  '<td>'.odo($row['STM_DATE']).'</td>';
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$L,odo($row['STM_DATE']));$ENG++;
		echo  '<td>'.$row['PDD_PROD_NAME'].'</td>';
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$L,iconv("big5","utf-8",$row['PDD_PROD_NAME']));$ENG++;
		echo  '<td>'.$row['PDD_STYLE'].'</td>';
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$L,$row['PDD_STYLE']);$ENG++;
		echo  '<td>'.get_cust_name($aa[1])."--＞".get_cust_name($aa[2]).'</td>';
		$resion=get_cust_name($aa[1])."--＞".get_cust_name($aa[2]);
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$L,iconv("big5","utf-8",$resion));$ENG++;
		echo  '<td>'.$row['STM_OLD_PROD_NO'].'</td>';
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$L,$row['STM_OLD_PROD_NO']);$ENG++;
		echo  '<td>'.$row['STM_OLD_LOT_NO'].'</td>';
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$L,$row['STM_OLD_LOT_NO']);$ENG++;
		echo  '<td>'.$row['STM_OLD_QTY'].'</td>';
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$L,$row['STM_OLD_QTY']);$ENG++;
		echo  '<td>'.$row['PDD_PROD_NO'].'</td>';
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$L,$row['PDD_PROD_NO']);$ENG++;
		echo  '<td>'.$row['STM_NEW_LOT_NO'].'</td>';
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$L,$row['STM_NEW_LOT_NO']);$ENG++;
		echo  '<td>'.$row['STM_NEW_QTY'].'</td>';
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$L,$row['STM_NEW_QTY']);$ENG++;
		echo  '<td>'.$bb.'</td>';
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$L,$bb);$ENG++;
		echo  '<td>'.$row['STM_PRN_BAR_DATE'].'</td>';
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$L,$row['STM_PRN_BAR_DATE']);$ENG++;
		echo  '<td>'.$row['STM_MEMO'].'</td>';
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$L, iconv("big5","utf-8",$row['STM_MEMO']));$ENG=A;
		$L++;
		
		
	}
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
			$objWriter->save('changes.xlsx');
}

			
		
		
		
		
		  echo '</table>';
		if(isset($_POST['submit3']))		
		{			
			$path_root=$_SERVER['HTTP_HOST'];
			echo '</br>';			
		echo '<script>document.location.href="http://'.$path_root.'/prodout/changes.xlsx";</script>';
		}


function selected(){
	session_start();
	echo '<select name="selected" id="selected">';
	echo '<option value="0"></option>';
	echo '<option value="1" >'."OEM->TYS".'</option>';
	echo '<option value="2" >'."OEM->客戶".'</option>';
	echo '<option value="3" >'."TYS->OEM".'</option>';
	echo '<option value="4" >'."TYS->客戶".'</option>';
	echo '<option value="5" >'."客戶->客戶".'</option>';
	echo '<option value="6" >'."其他".'</option>';
	echo '</select>';
}
?>