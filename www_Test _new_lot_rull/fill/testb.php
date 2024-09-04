
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title></title>
EL DRUM 洗淨 CHECKLIST
<form method="post" action="<?php echo $loginFormAction; ?>">
<input type="submit" name="print" id="print" value="列印" />
<input type="submit" name="leave" id="leave" value="離開" />
<?PHP 
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
include  "../PHPEXCEL/Classes/PHPExcel.php";
include "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
$query="SELECT  DRUM_WASH_UPDATE.*
FROM      DRUM_WASH_UPDATE
WHERE   (FDM_LOT_NO = '".$_GET['id']."')";
//echo $query."<BR>";
$result=mssql_query($query);
$numrows=mssql_num_rows($result);
$row=mssql_fetch_row($result);
$n19=$row[1];$v19=$row[2];
$n20=$row[3];$v20=$row[4];
$n21=$row[5];$v21=$row[6];
$n22=$row[7];$v22=$row[8];
$n23=$row[9];$v23=$row[10];
$n24=$row[11];$v24=$row[12];
$n25=$row[13];$v25=$row[14];
$n26=$row[15];$v26=$row[16];
$n27=$row[17];$v27=$row[18];
$n28=$row[19];$v28=$row[20];
$n29=$row[21];$n30=$row[22];
if($numrows==0){
	$query="INSERT INTO DRUM_WASH_UPDATE	(FDM_LOT_NO)	VALUES  ('".$_GET['id']."')";
	$result=mssql_query($query);
}
datepick();

session_start();
echo "<BR>Lot NO. : ".$_GET['id'];
global $num;
global $times;
$num = 0;
$times = 0;
if(isset($_POST['leave']))
	{
		jumpto($_SESSION['lasturl']);
	}

if(isset($_POST['print']))
	{
	//	Rone();
		$objPHPExcel = new PHPExcel();
		$objPHPExcel = PHPExcel_IOFactory::load("./prin_t.xlsx");
		$objPHPExcel->setActiveSheetIndex($GLOBALS['num']);
			//echo "執行了R2";
		$R2="SELECT DISTINCT 
                            EWD.EWD_DRUM, EWD.EWD_CHK_SURFACE, EWD.EWD_CHK_LABEL, EWD.EWD_CHK_DRUM_IN, 
                            EWD.EWD_CHK_FILL_LINK, EWD.EWD_WASH_TIME, EWD.EWD_CLEAR, EWD.EWD_CHK_WASHER, 
                            EWD.EWD_MAKELOT, DHN.DHN_MAKE_DATE
FROM              EL_WASH_DRUM AS EWD LEFT OUTER JOIN
                            DRUM_HISTORY_NORMAL AS DHN ON DHN.DHN_DRUM_NO = EWD.EWD_DRUM 
		where   EWD.FDM_LOT_NO = '".$_GET['id']."' order by EWD.EWD_DRUM";
		
//	echo "<BR>".$R2;
		$V01=array();
		$V02=array();
		$V03=array();
		$V04=array();
		$V05=array();
		$V06=array();
		$V07=array();
		$V08=array();
		$V09=array();
		$V10=array();
		$V11=array();
		$V12=array();
		  //echo $R2;
		$result1 = mssql_query($R2);
		$numRows = mssql_num_rows($result1);
//		echo "<BR>numRows:".$numRows."<BR>";
		if($numRows<=16){$numRows=16;}
		while($row1 = mssql_fetch_array($result1))
		{
			$EWD_USED_COUNT=cnt_used($row1['EWD_DRUM']);
		//	echo "NN:".$EWD_USED_COUNT."<BR>";
			array_push($V01,$row1['EWD_SERIAL_NO']);
			array_push($V02,$row1['EWD_DRUM']);
			array_push($V03,$row1['EWD_CHK_SURFACE']);
			array_push($V04,$EWD_USED_COUNT);
			array_push($V05,$row1['EWD_CHK_LABEL']);
			array_push($V06,$row1['EWD_CHK_DRUM_IN']);
			array_push($V07,$row1['EWD_CHK_FILL_LINK']);
			array_push($V08,$row1['EWD_WASH_TIME']);
			array_push($V09,$row1['EWD_CLEAR']);
			array_push($V10,$row1['EWD_CHK_WASHER']);
			array_push($V11,$row1['DHN_MAKE_DATE']);
			array_push($V12,$row1['EWD_MAKELOT']);
			
		}
		//print_r($V12);
		$ENG='N';
		
		for($i=0;$i< $numRows;$i++)
		{	
			$ten=9;
			$eve=10;
			$ten2=11;
			$ten3=12;
			$ten4=13;
			$ten5=14;
			$ten6=15;
			$ten7=16;
			$ten8=14;
			$thirty3=31;
			$thirty4=32;
			$thirty5=33;

			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$ten,$V01[$i] );
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$eve,$V02[$i] );
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$ten3,$V03[$i] );
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$ten4,$V04[$i] );
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$ten5,$V05[$i] );
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$ten6,$V06[$i] );
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$ten7,$V07[$i] );
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$thirty3,$V08[$i] );
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$thirty4,$V09[$i] );
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$thirty5,$V10[$i] );
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$ten2,$V12[$i] );
			
			$objPHPExcel->getActiveSheet()->setCellValue("N19",$n19 );
			$objPHPExcel->getActiveSheet()->setCellValue("V19",$v19 );
			$objPHPExcel->getActiveSheet()->setCellValue("N20",$n20 );
			$objPHPExcel->getActiveSheet()->setCellValue("V20",$v20 );
			$objPHPExcel->getActiveSheet()->setCellValue("N21",$n21 );
			$objPHPExcel->getActiveSheet()->setCellValue("V21",$v21 );
			$objPHPExcel->getActiveSheet()->setCellValue("N22",$n22 );
			$objPHPExcel->getActiveSheet()->setCellValue("V22",$v22 );
			$objPHPExcel->getActiveSheet()->setCellValue("N23",$n23 );
			$objPHPExcel->getActiveSheet()->setCellValue("V23",$v23 );
			$objPHPExcel->getActiveSheet()->setCellValue("N24",$n24 );
			$objPHPExcel->getActiveSheet()->setCellValue("V24",$v24 );
			$objPHPExcel->getActiveSheet()->setCellValue("N25",$n25 );
			$objPHPExcel->getActiveSheet()->setCellValue("V25",$v25 );
			$objPHPExcel->getActiveSheet()->setCellValue("N26",$n26 );
			$objPHPExcel->getActiveSheet()->setCellValue("V26",$v26 );
			$objPHPExcel->getActiveSheet()->setCellValue("N27",$n27 );
			$objPHPExcel->getActiveSheet()->setCellValue("V27",$v27 );
			$objPHPExcel->getActiveSheet()->setCellValue("N28",$n28 );
			$objPHPExcel->getActiveSheet()->setCellValue("V28",$v28 );
			$objPHPExcel->getActiveSheet()->setCellValue("N29",$n29 );
			$objPHPExcel->getActiveSheet()->setCellValue("N30",$n30 );		


			$ENG++;
			$ans=($i+1)%16;
			
			if($ans==0)
				{
					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
			$objWriter->save('print.xlsx');
					$GLOBALS['num']++;
					$ENG='N';
					R1();
					
					$objPHPExcel = new PHPExcel();
					$objPHPExcel = PHPExcel_IOFactory::load("./print.xlsx");
					$objPHPExcel->setActiveSheetIndex($GLOBALS['num']);
				}
		}	
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
		$objWriter->save('print.xlsx');
	}
function R1(){
	$objPHPExcel = new PHPExcel();
	$objPHPExcel = PHPExcel_IOFactory::load("./print.xlsx");
	$n=$GLOBALS['num']-1;
	$objPHPExcel->setActiveSheetIndex($n);
//	echo "<BR>".$GLOBALS['num']. "<BR>";

	$R1="select 		CTD.CTD_CUST_SHORT_NAME,FOD.FDM_QTY,EMP1.EMP_NAME,EMP2.EMP_NAME as NAME2,FID.FID_FILL_END_DATE,FID.FID_WASHED_COUNT
		from 		FILLPLAN_OUT_DECIDE as FOD
		inner   join	FILLPLAN_DRUM_CUSTOMER as FDC
		on		FOD.FDM_LOT_NO=FDC.FDM_LOT_NO 
		inner 	join	CUSTOMER_DATA as CTD 
		on 		FDC.CTD_CUST_NO = CTD.CTD_CUST_NO 
		inner join 		EMPLOYEE_DATA as EMP1 
		on 		FOD.FDM_CREATOR = EMP1.EMP_NO 
		INNER join 		FILL_INDICATE as FID 	
		on 		FOD.FDM_LOT_NO = FID.FDM_LOT_NO 
		INNER join 		DRUM_WASH_UPDATE as DWU 
		on 		FOD.FDM_LOT_NO = DWU.FDM_LOT_NO 
		inner join		EMPLOYEE_DATA as EMP2
		on		DWU.DWU_WASHER=EMP2.EMP_NO 		
		where 		FOD.FDM_LOT_NO = '".$_GET['id']."'";
		
		$result = mssql_query($R1);
		$numRows = mssql_num_rows($result);
		
	$short2=array();
		$short1=array();
		while($row = mssql_fetch_array($result))
		{
			$day1=substr($row['FID_FILL_END_DATE'],0,8);
			array_push($short2,$row['CTD_CUST_SHORT_NAME']);
			$short1=iconv("big5","utf-8",$short2[0]).iconv("big5","utf-8",$short2[1]);
			//echo  iconv("big5","utf-8",$row['CTD_CUST_SHORT_NAME']);
			$qty=$row['FDM_QTY'];
			$emp1=iconv("big5","utf-8",$row['EMP_NAME']);
			$emp2=iconv("big5","utf-8",$row['NAME2']);
			$washcount=$row['FID_WASHED_COUNT'];
			//$objPHPExcel->getActiveSheet()->setCellValue("S5", $row['EMP2.EMP_NAME']);
		}
	//		$objPHPExcel->getActiveSheet()->setCellValue("R1", $_GET['id']);	
			$objPHPExcel->getActiveSheet()->setCellValue("c6", $_GET['id']);
			$objPHPExcel->getActiveSheet()->setCellValue("c3", $day1);
			$objPHPExcel->getActiveSheet()->setCellValue("c4", $short1);
			$objPHPExcel->getActiveSheet()->setCellValue("c5", $qty);
			$objPHPExcel->getActiveSheet()->setCellValue("R5", $emp1);
			$objPHPExcel->getActiveSheet()->setCellValue("S5", $emp2);
			$objPHPExcel->getActiveSheet()->setCellValue("C11", $washcount);
			$objPHPExcel->getActiveSheet()->getStyle("C3:C6")->getBorders()->getAllborders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle("C3:C6")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
		$R3="SELECT 	
		DWU_LIQ_TUBE_NO,
		DWU_LIQ_TUBE,
		DWU_LIQ_PLUG_B_NO,
		DWU_LIQ_PLUG_B,
		DWU_LIQ_PLUG_S_NO,
		DWU_LIQ_PLUG_S,
		DWU_LIQ_ORING_B_NO,
		DWU_LIQ_ORING_B,
		DWU_LIQ_WASHER_S_NO,
		DWU_LIQ_WASHER_S,
		DWU_GAS_PLUG_B_NO,
		DWU_GAS_PLUG_B,
		DWU_GAS_PLUG_S_NO,
		DWU_GAS_PLUG_S,
		DWU_GAS_ORING_B_NO,
		DWU_GAS_ORING_B,
		DWU_GAS_WASHER_S_NO,
		DWU_GAS_WASHER_S,
		DWU_GAS_CAP_NO,
		DWU_GAS_CAP,
		DWU_EL_MANOMETER,
		DWU_EL_WASH_PURGE
		from 	DRUM_WASH_UPDATE as DWU
		where   DWU.FDM_LOT_NO ='".$_GET['id']."'";
		$result2 = mssql_query($R3);
		$numRows = mssql_num_rows($result2);
		while($row2 = mssql_fetch_array($result2))
		{
			$R3V01=$row2['DWU_LIQ_TUBE_NO'];
			$R3V02=$row2['DWU_LIQ_TUBE'];
			$R3V03=$row2['DWU_LIQ_PLUG_B_NO'];
			$R3V04=$row2['DWU_LIQ_PLUG_B'];
			$R3V05=$row2['DWU_LIQ_PLUG_S_NO'];
			$R3V06=$row2['DWU_LIQ_PLUG_S'];
			$R3V07=$row2['DWU_LIQ_ORING_B_NO'];
			$R3V08=$row2['DWU_LIQ_ORING_B'];
			$R3V09=$row2['DWU_LIQ_WASHER_S_NO'];
			$R3V10=$row2['DWU_LIQ_WASHER_S'];
			$R3V11=$row2['DWU_GAS_PLUG_B_NO'];
			$R3V12=$row2['DWU_GAS_PLUG_B'];
			$R3V13=$row2['DWU_GAS_PLUG_S_NO'];
			$R3V14=$row2['DWU_GAS_PLUG_S'];
			$R3V15=$row2['DWU_GAS_ORING_B_NO'];
			$R3V16=$row2['DWU_GAS_ORING_B'];
			$R3V17=$row2['DWU_GAS_WASHER_S_NO'];
			$R3V18=$row2['DWU_GAS_WASHER_S'];
			$R3V19=$row2['DWU_GAS_CAP_NO'];
			$R3V20=$row2['DWU_GAS_CAP'];
			$R3V21=$row2['DWU_EL_MANOMETER'];
			$R3V22=$row2['DWU_EL_WASH_PURGE'];
		}
		$objPHPExcel->getActiveSheet()->setCellValue("G20",$R3V01 );
		$objPHPExcel->getActiveSheet()->setCellValue("O20",$R3V02 );
		$objPHPExcel->getActiveSheet()->setCellValue("G21",$R3V03 );
		$objPHPExcel->getActiveSheet()->setCellValue("O21",$R3V04 );
		$objPHPExcel->getActiveSheet()->setCellValue("G22",$R3V05 );
		$objPHPExcel->getActiveSheet()->setCellValue("O22",$R3V06 );
		$objPHPExcel->getActiveSheet()->setCellValue("G23",$R3V07 );
		$objPHPExcel->getActiveSheet()->setCellValue("O23",$R3V08 );
		$objPHPExcel->getActiveSheet()->setCellValue("G24",$R3V09 );
		$objPHPExcel->getActiveSheet()->setCellValue("O24",$R3V10 );
		$objPHPExcel->getActiveSheet()->setCellValue("G25",$R3V11 );
		$objPHPExcel->getActiveSheet()->setCellValue("O25",$R3V12 );
		$objPHPExcel->getActiveSheet()->setCellValue("G26",$R3V13 );
		$objPHPExcel->getActiveSheet()->setCellValue("O26",$R3V14 );
		$objPHPExcel->getActiveSheet()->setCellValue("G27",$R3V15 );
		$objPHPExcel->getActiveSheet()->setCellValue("O27",$R3V16 );
		$objPHPExcel->getActiveSheet()->setCellValue("G28",$R3V17 );
		$objPHPExcel->getActiveSheet()->setCellValue("O28",$R3V18 );
		$objPHPExcel->getActiveSheet()->setCellValue("G29",$R3V19 );
		$objPHPExcel->getActiveSheet()->setCellValue("O29",$R3V20 );
		$objPHPExcel->getActiveSheet()->setCellValue("G31",$R3V21 );
		$objPHPExcel->getActiveSheet()->setCellValue("G32",$R3V22 );
		
		$GLOBALS['times']++;
		
		$objPHPExcel->setActiveSheetIndex($n+1);
		$objPHPExcel->getActiveSheet()->setCellValue("c6", $_GET['id']);
			$objPHPExcel->getActiveSheet()->setCellValue("c3", $day1);
			$objPHPExcel->getActiveSheet()->setCellValue("c4", $short1);
			$objPHPExcel->getActiveSheet()->setCellValue("c5", $qty);
			$objPHPExcel->getActiveSheet()->setCellValue("R5", $emp1);
			$objPHPExcel->getActiveSheet()->setCellValue("S5", $emp2);
			$objPHPExcel->getActiveSheet()->setCellValue("C11", $washcount);
			$objPHPExcel->getActiveSheet()->getStyle("C3:C6")->getBorders()->getAllborders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle("C3:C6")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
		$R1="select 		CTD.CTD_CUST_SHORT_NAME,FOD.FDM_QTY,EMP1.EMP_NAME,EMP2.EMP_NAME as NAME2,FID.FID_FILL_END_DATE,FID.FID_WASHED_COUNT
		from 		FILLPLAN_OUT_DECIDE as FOD
		inner   join	FILLPLAN_DRUM_CUSTOMER as FDC
		on		FOD.FDM_LOT_NO=FDC.FDM_LOT_NO 
		inner 	join	CUSTOMER_DATA as CTD 
		on 		FDC.CTD_CUST_NO = CTD.CTD_CUST_NO 
		inner join 		EMPLOYEE_DATA as EMP1 
		on 		FOD.FDM_CREATOR = EMP1.EMP_NO 
		INNER join 		FILL_INDICATE as FID 	
		on 		FOD.FDM_LOT_NO = FID.FDM_LOT_NO 
		INNER join 		DRUM_WASH_UPDATE as DWU 
		on 		FOD.FDM_LOT_NO = DWU.FDM_LOT_NO 
		inner join		EMPLOYEE_DATA as EMP2
		on		DWU.DWU_WASHER=EMP2.EMP_NO 		
		where 		FOD.FDM_LOT_NO = '".$_GET['id']."'";
		
		$result = mssql_query($R1);
		$numRows = mssql_num_rows($result);
		
	$short2=array();
		$short1=array();
		while($row = mssql_fetch_array($result))
		{
			$day1=substr($row['FID_FILL_END_DATE'],0,8);
			array_push($short2,$row['CTD_CUST_SHORT_NAME']);
			$short1=iconv("big5","utf-8",$short2[0]).iconv("big5","utf-8",$short2[1]);
			//echo  iconv("big5","utf-8",$row['CTD_CUST_SHORT_NAME']);
			$qty=$row['FDM_QTY'];
			$emp1=iconv("big5","utf-8",$row['EMP_NAME']);
			$emp2=iconv("big5","utf-8",$row['NAME2']);
			$washcount=$row['FID_WASHED_COUNT'];
			//$objPHPExcel->getActiveSheet()->setCellValue("S5", $row['EMP2.EMP_NAME']);
		}
			$objPHPExcel->getActiveSheet()->setCellValue("R1", $_GET['id']);	

			$objPHPExcel->getActiveSheet()->setCellValue("B5", $day1);
			$objPHPExcel->getActiveSheet()->setCellValue("B6", $short1);

			$objPHPExcel->getActiveSheet()->setCellValue("R5", $emp1);
			$objPHPExcel->getActiveSheet()->setCellValue("S5", $emp2);
			$objPHPExcel->getActiveSheet()->setCellValue("C11", $washcount);
			$objPHPExcel->getActiveSheet()->getStyle("C3:C6")->getBorders()->getAllborders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle("C3:C6")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$path_root=$_SERVER['HTTP_HOST'];
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
			$objWriter->save('print.xlsx');
			echo '<script>document.location.href="http://'.$path_root.'/fill/print.xlsx";
			</script>';		
}

function cnt_used($sma_no){
	$query="SELECT COUNT(EWD_DRUM) AS aa FROM EL_WASH_DRUM AS EWD WHERE (EWD_DRUM = '".$sma_no."') ";
//	echo $query."<BR>";
	$result=mssql_query($query);
	$rr=mssql_fetch_row($result);
	
	return $rr[0];
}
?>

