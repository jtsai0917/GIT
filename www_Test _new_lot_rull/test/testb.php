
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>''G''10''</title>
<form method="post" action="<?php echo $loginFormAction; ?>">
<input type="submit" name="print" id="print" value="列印" />

<?PHP 
$editFormAction = $_SERVER['PHP_SELF'];
include("../lib/fun.php");
include("../connections/conn.php");
include("../checkuser.php");
include("../lib/jtsai.php");
include  "../PHPEXCEL/Classes/PHPExcel.php";
include "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";

session_start();
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);	
	$objPHPExcel = PHPExcel_IOFactory::load("./chklist.xlsx");
		
if(isset($_POST['print']))
{	
			Rone();
			Rthree();
			echo "執行了R2";
			
		$R2="select EWD_SERIAL_NO,EWD_DRUM,EWD_CHK_SURFACE,EWD_USED_COUNT,EWD_CHK_LABEL,EWD_CHK_DRUM_IN,EWD_CHK_FILL_LINK,EWD_WASH_TIME,EWD_CLEAR,EWD_CHK_WASHER,EWD_MAKELOT,DHN.DHN_MAKE_DATE
		from EL_WASH_DRUM as EWD 
		inner  join DRUM_HISTORY_NORMAL  DHN on DHN_DRUM_NO =EWD.EWD_DRUM
		where   EWD.FDM_LOT_NO = '".$_GET['id']."'
		order by EWD_SERIAL_NO";
		
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
		$num=1;
		 // echo $R2;
		$result1 = mssql_query($R2);
		$numRows = mssql_num_rows($result1);
		//echo $numRows;
		while($row1 = mssql_fetch_array($result1))
		{
			
			array_push($V01,$row1['EWD_SERIAL_NO']);
			array_push($V02,$row1['EWD_DRUM']);
			array_push($V03,$row1['EWD_CHK_SURFACE']);
			array_push($V04,$row1['EWD_USED_COUNT']);
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
		$ENG='G';
		
		for($i=0;$i<$numRows;$i++)
		{
			$ten=10;
			$eve=11;
			$ten2=12;
			$ten3=13;
			$ten4=14;
			$ten5=15;
			$ten6=16;
			$ten7=17;
			$ten8=14;
			$thirty3=33;
			$thirty4=34;
			$thirty5=35;
			
			//echo $ENG.$ten;
			
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

			
			$ENG++;
			
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
	$objWriter->save('print.xlsx');
	
			$ans=($i+1)%16;
			//echo $ans.'</br>';
			if($ans==0)
				{
					$objPHPExcel = new PHPExcel();
	$objPHPExcel = PHPExcel_IOFactory::load("./print.xlsx");
				$objPHPExcel->setActiveSheetIndex($num);
				//R1;
				//R3;
				$num++;
				$ENG='G';
				echo $num."YAYA";
				echo $i;
				
				}
			
		}
		
	function Rthree(){
		echo "執行了R1";
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
		 //echo $R3;
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
		
	}
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
	$objWriter->save('print.xlsx');
	}

function Rone(){

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
		while($row = mssql_fetch_array($result))
		{
			echo $day1=substr($row['FID_FILL_END_DATE'],0,8);
			echo "</br>";
			echo $short1=iconv("big5","utf-8",$row['CTD_CUST_SHORT_NAME']);
			echo "</br>";
			//echo  iconv("big5","utf-8",$row['CTD_CUST_SHORT_NAME']);
			echo $qty=$row['FDM_QTY'];
			echo "</br>";
			echo $emp1=iconv("big5","utf-8",$row['EMP_NAME']);
			echo "</br>";
			echo $emp2=iconv("big5","utf-8",$row['NAME2']);
			echo "</br>";
			echo $washcount=$row['FID_WASHED_COUNT'];
			//$objPHPExcel->getActiveSheet()->setCellValue("S5", $row['EMP2.EMP_NAME']);	
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("R1", $_GET['id']);	
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B8", $_GET['id']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B5", $day1);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B6", $short1);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B7", $qty);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("R5", $emp1);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("S5", $emp2);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C11", $washcount);
			//$objPHPExcel->getActiveSheet()->setCellValue("S5", $row['EMP2.EMP_NAME']);

			echo "結束了R1";
		}
}
?>