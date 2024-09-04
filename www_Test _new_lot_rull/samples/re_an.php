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
$path_root=$_SERVER['HTTP_HOST'];
?>
查詢 </br><form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
日期： <input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php if($_SESSION['datepicker1']){echo $_SESSION['datepicker1'];}?>" onChange="set_date_session(this.name,this.value)">
~
<input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php if($_SESSION['datepicker2']){echo $_SESSION['datepicker2'];}?>" onChange="set_date_session(this.name,this.value)"></td> 
&emsp;&emsp;&emsp;&emsp;&emsp;品名:
<input name="pdd_chemical1" type="text" id="pdd_chemical1" size="10" value="<?php 
		
		if ($_GET['prod_nono']){
			echo $_GET['prod_nono'];
			$_SESSION['prod_nono']=$_GET['prod_nono'];
		}
		elseif($_SESSION['prod_nono']){
			echo $_SESSION['prod_nono'];
		}
		else{
		echo '';
		}?>" />
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
		 
		
		?>" readonly="readonly" />
<input type="button" name="pdd" id="pdd" value="選擇藥品" onclick="window.open('./session/pdd_prod.php ', '_self');" />
<input type="button" name="X" id="X" value="X" onclick="window.open('./session/erase_prod.php ', '_self');" />
&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
<input name="search" type="submit" id="search" value=" 查詢再分析瓶 ">
<input name="report" type="submit" id="report" value=" 下載報告 ">
<br><br></form>
<?php
if(isset($_POST["search"]))
{
	$d1=dod(trim($_POST['datepicker1']));
	$d2=dod(trim($_POST['datepicker2']));
	$p=trim($_POST['pdd_chemical1']);
	$query="SELECT          Sample_All.SMA_SAVE, Sample_All.SMA_ID, Sample_All.SMA_LOT, AnalyzeDesign.AND_ITEM, 
                            PRODUCT_DATA.PDD_CHEMICAL
FROM              Sample_All INNER JOIN
                            AnalyzeDesign ON Sample_All.SMA_LOT = AnalyzeDesign.AND_LOT_NO INNER JOIN
                            PRODUCT_DATA ON AnalyzeDesign.AND_GOODS = PRODUCT_DATA.PDD_PROD_NO 
 WHERE (ISREWORK <> '') AND (AnalyzeDesign.AND_NEED_NO <= 150) AND (Sample_All.SMA_ID <> 'W2') AND (Sample_All.SMA_ID <> 'O1') AND (Sample_All.SMA_ID <> 'S6') AND (Sample_All.SMA_ID <> 'S9') AND (Sample_All.SMA_ID <> 'I0') AND (Sample_All.SMA_ID <> 'F5') AND (Sample_All.SMA_ID <> 'A0') AND (Sample_All.SMA_ID <> 'N7') AND (Sample_All.SMA_ID <> 'L5') AND (Sample_All.SMA_ID <> 'CN') AND (Sample_All.SMA_ID <> 'M4') AND (Sample_All.SMA_ID <> 'M3') AND (Sample_All.SMA_ID <> 'M2') AND (Sample_All.SMA_ID <> '') AND (AnalyzeDesign.AND_GOODS = '".$p."')AND (SMA_SAVE >= '".$d1."') AND (SMA_SAVE <= '".$d2."') ORDER BY   Sample_All.SMA_LOT, Sample_All.SMA_SAVE";	
// echo $query;
	echo '<table width="1024" border="1" bgcolor="#CCCCCC"><tr><td>日期</td><td>Lot No</td><td>瓶號</td><td>對應分析項目</td></tr>';
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		$lotno=$row['SMA_LOT'];
		echo '<tr bgcolor="#FFFFFF"><td>'.$row['SMA_SAVE'].'</td><td>'.$row['SMA_LOT'].'</td><td>'.$row['SMA_ID'].'</td><td>'.report($row['AND_ITEM'],$lotno,$prod_no,$row['PDD_CHEMICAL'],$row['SMA_ID']).'</td></tr>';
	}
	echo '</table><BR>';
}

if(isset($_POST["report"]))
{
	$objPHPExcel = new PHPExcel();
	$objPHPExcel = PHPExcel_IOFactory::load("re_sample.xlsx");	
    $objPHPExcel->setActiveSheetIndex(0);

	
								
	$d1=dod(trim($_POST['datepicker1']));
	$d2=dod(trim($_POST['datepicker2']));
	$p=trim($_POST['pdd_chemical1']);
	$query="SELECT          Sample_All.SMA_SAVE, Sample_All.SMA_ID, Sample_All.SMA_LOT, AnalyzeDesign.AND_ITEM, 
                            PRODUCT_DATA.PDD_CHEMICAL
FROM              Sample_All INNER JOIN
                            AnalyzeDesign ON Sample_All.SMA_LOT = AnalyzeDesign.AND_LOT_NO INNER JOIN
                            PRODUCT_DATA ON AnalyzeDesign.AND_GOODS = PRODUCT_DATA.PDD_PROD_NO 
WHERE (ISREWORK <> '') AND (AnalyzeDesign.AND_NEED_NO <= 150) AND (Sample_All.SMA_ID <> 'W2') AND (Sample_All.SMA_ID <> 'O1') AND (Sample_All.SMA_ID <> 'S6') AND (Sample_All.SMA_ID <> 'S9') AND (Sample_All.SMA_ID <> 'I0') AND (Sample_All.SMA_ID <> 'F5') AND (Sample_All.SMA_ID <> 'A0') AND (Sample_All.SMA_ID <> 'N7') AND (Sample_All.SMA_ID <> 'L5') AND (Sample_All.SMA_ID <> 'CN') AND (Sample_All.SMA_ID <> 'M4') AND (Sample_All.SMA_ID <> 'M3') AND (Sample_All.SMA_ID <> 'M2') AND (Sample_All.SMA_ID <> '') AND (AnalyzeDesign.AND_GOODS = '".$p."')AND (SMA_SAVE >= '".$d1."') AND (SMA_SAVE <= '".$d2."') ORDER BY   Sample_All.SMA_LOT, Sample_All.SMA_SAVE";		
	$result=mssql_query($query);
	$i=2;
	while($row=mssql_fetch_array($result)){
		$lotno=$row['SMA_LOT'];
		$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue("'A".$i."'",iconv("big5","utf-8",$row['SMA_SAVE']))
							->setCellValue("'B".$i."'",iconv("big5","utf-8",$row['SMA_LOT']))
							->setCellValue("'C".$i."'",iconv("big5","utf-8",$row['SMA_ID']))
							->setCellValue("'D".$i."'",iconv("big5","utf-8",report($row['AND_ITEM'],$lotno,$prod_no,$row['PDD_CHEMICAL'],$row['SMA_ID'])));
		$i++;
	}
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
	$objWriter->save("./tmp/tmp.xlsx");
	echo '<script>document.location.href="http://'.$path_root.'/samples/tmp/tmp.xlsx";</script>';	
}


function report($str,$lot_no,$pdd_prod_no,$pdd_chemical,$smpid){
	$aa=explode(',',$str,-1);
	$query="SELECT DISTINCT AnalyzeItem.ANI_GROUPNAME AS ag, ELEMENT_FORM.PDD_CHEMICAL , ELEMENT_FORM.ELF_FORM FROM AnalyzeItem INNER JOIN ELEMENT_FORM ON AnalyzeItem.ANI_INDEX = ELEMENT_FORM.ELM_ID WHERE (ANI_INDEX <>'') AND (ELEMENT_FORM.PDD_CHEMICAL = '".$pdd_chemical."') and (";
	for($i=0;$i<count($aa);$i++)
	{
		if ($i<(count($aa)-1)){
			$query.="(ANI_INDEX =".$aa[$i].") OR ";}
		else {$query.="(ANI_INDEX =".$aa[$i]."))";}	
	}
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		$ef=trim($row['ELF_FORM']);
		$sp=re_sample($ef,$smpid,$lot_no);
		if($sp>>0){
			$san.=$row['ag'].',';
		}
	}
	return substr($san,0,-1);
//	return substr($ss,0,-1);
//	echo $query;
//	echo '<br>';
//	echo '<br>';
//	return substr($ss,0,-1);
}

function re_sample($form,$smpid,$lotno){
	$query1="SELECT * FROM ".$form." WHERE (LotNo = '".$lotno."') AND (SampleNo = '".$smpid."')";
	$result1=mssql_query($query1);
	$numrow=mssql_num_rows($result1);
//	echo $row1['cn'].'<br><br>';
	return $numrow;
}