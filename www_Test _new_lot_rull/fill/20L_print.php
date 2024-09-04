
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title></title>
H2O2(20L PE)充填檢查表</br>
<form method="post" action="<?php echo $loginFormAction; ?>">
<input type="submit" name="print" id="print" value="列印" />
<input type="submit" name="leave" id="leave" value="離開" />

<?PHP 
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
include  "../PHPEXCEL/Classes/PHPExcel.php";
include "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
datepick();
echo'</br>';
echo $_GET['id'];
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
	$objPHPExcel = new PHPExcel();
	$objPHPExcel = PHPExcel_IOFactory::load("./20L.xlsx");
	$objPHPExcel->setActiveSheetIndex($GLOBALS['num']);
	$short2=array();
	$ID=array();
	
	$R3="select SA.SMA_ID
	from  FILLPLAN_OUT_DECIDE  as FOD
	inner join Sample_All as SA on FOD.FDM_LOT_NO=SA.SMA_LOT
	where FOD.FDM_LOT_NO='".$_GET['id']."'";
	$result2 = mssql_query($R3);
			$numRows2 = mssql_num_rows($result2);
			while($row2 = mssql_fetch_array($result2))
			{
				array_push($ID,$row2['SMA_ID']);
				$ID1="   ".$ID[0]."  ".$ID[1]."  ".$ID[2]."  ".$ID[3]."  ".$ID[4]."  ".$ID[5]."  ".$ID[6]."  ".$ID[7]."  ".$ID[8]."  ".$ID[9]."  ".$ID[10]."  ".$ID[11]."  ".$ID[12]."  ".$ID[13]."  ".$ID[14]."  ".$ID[15];
			}
	$objPHPExcel->getActiveSheet()->setCellValue("A35",$ID1);
	
		$R1="select  DISTINCT 
		FID.FID_FILL_BEGIN_DATE,PDD.PDD_PROD_NAME,
		FOD.FDM_LOT_NO,CTD.CTD_CUST_SHORT_NAME,
		DDC.DDC_PURGE,DDC.DDC_CHK_SCALES,DDC.DDC_SCALES_SET,EMP.EMP_NAME,FOD.FDM_QTY_DRUM
		from  FILLPLAN_OUT_DECIDE  as FOD
		inner   join	FILLPLAN_DRUM_CUSTOMER as FDC
		on		FOD.FDM_LOT_NO=FDC.FDM_LOT_NO 		
		inner join CUSTOMER_DATA as CTD on FDC.CTD_CUST_NO = CTD.CTD_CUST_NO 
		inner join PRODUCT_DATA as PDD on FOD.PDD_PROD_NO = PDD.PDD_PROD_NO
		inner join FILL_INDICATE as FID on FOD.FDM_LOT_NO = FID.FDM_LOT_NO
		inner join EMPLOYEE_DATA as EMP on  FID.FID_OPERATOR = EMP.EMP_NO
		inner join DRUM_FILL_DAY_CHECK as DDC  on DDC.FDM_LOT_NO = FOD.FDM_LOT_NO
		where FOD.FDM_LOT_NO='".$_GET['id']."'";
		echo $R1."<BR>";
		$result = mssql_query($R1);
			$numRows = mssql_num_rows($result);
			while($row = mssql_fetch_array($result))
			{
				$prod=iconv("big5","utf-8",$row['PDD_PROD_NAME']);
				$dat=substr($row['FID_FILL_BEGIN_DATE'],0,8);
				array_push($short2,$row['CTD_CUST_SHORT_NAME']);
			$short1=iconv("big5","utf-8",$short2[0])." ".iconv("big5","utf-8",$short2[1])." ".iconv("big5","utf-8",$short2[2])." ".iconv("big5","utf-8",$short2[3])." ".iconv("big5","utf-8",$short2[4])." ".iconv("big5","utf-8",$short2[5])." ".iconv("big5","utf-8",$short2[6]);
				$emp=iconv("big5","utf-8",$row['EMP_NAME']);
				$aa=$row['DDC_PURGE'];
				$bb=$row['DDC_CHK_SCALES'];
				$cc=$row['DDC_SCALES_SET'];
				$dd=$row['FDM_QTY_DRUM'];
				
			}
			$objPHPExcel->getActiveSheet()->setCellValue("B3",$prod );
			$objPHPExcel->getActiveSheet()->setCellValue("B2",$dat );
			$objPHPExcel->getActiveSheet()->setCellValue("B5",$short1 );
			$objPHPExcel->getActiveSheet()->setCellValue("B4",$_GET['id']);
			$objPHPExcel->getActiveSheet()->setCellValue("B32",$emp);
			$objPHPExcel->getActiveSheet()->setCellValue("F18",$aa);
				$objPHPExcel->getActiveSheet()->setCellValue("F19",$bb);
				$objPHPExcel->getActiveSheet()->setCellValue("F20",$cc);
				$objPHPExcel->getActiveSheet()->setCellValue("F30",$dd);
			
					
			$R2="select 
			C2D_SERIAL_NO,C2D_DRUM_NO,C2D_B_CHK_VALIDATE,C2D_B_CHK_SURFACE,C2D_B_CHK_DRUM_IN,
			C2D_B_CHK_FILL_LINK,C2D_B_CHK_COMPONENT
			,C2D_B_CHK_LABEL,C2D_W_CHK_WASHER,C2D_W_CHK_WATER,C2D_F_WASHER_NO,C2D_F_CHK_DM_EMPTY,C2D_F_CHK_CAP_CLR,
			C2D_F_SAMPLING,C2D_P_CHK_SURFACE,C2D_P_CHK_LEAK,C2D_P_CHK_LABEL,C2D_P_CHK_CAP,C2D_MAKELOT_NO,C2D_F_QTY
			from [20L_FILL_CHECK_DRUM]
			where FDM_LOT_NO='".$_GET['id']."'
			order by C2D_SERIAL_NO";
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
			$V13=array();
			$V14=array();
			$V15=array();
			$V16=array();
			$V17=array();
			$V18=array();
			$V19=array();
			$result1 = mssql_query($R2);
			$numRows1 = mssql_num_rows($result1);
			echo $numRows1;
			$ENG='F';
			
			while($row1 = mssql_fetch_array($result1))
			{
					array_push($V01,$row1['C2D_SERIAL_NO']);
					array_push($V02,$row1['C2D_DRUM_NO']);
					array_push($V03,$row1['C2D_B_CHK_VALIDATE']);
					array_push($V04,$row1['C2D_B_CHK_SURFACE']);
					array_push($V05,$row1['C2D_B_CHK_DRUM_IN']);
					array_push($V06,$row1['C2D_B_CHK_FILL_LINK']);
					array_push($V07,$row1['C2D_B_CHK_COMPONENT']);
					array_push($V08,$row1['C2D_B_CHK_LABEL']);
					array_push($V09,$row1['C2D_W_CHK_WASHER']);
					array_push($V10,$row1['C2D_W_CHK_WATER']);
					array_push($V11,$row1['C2D_F_CHK_DM_EMPTY']);
					array_push($V12,$row1['C2D_F_QTY']);
					array_push($V13,$row1['C2D_F_CHK_CAP_CLR']);
					array_push($V14,$row1['C2D_F_SAMPLING']);
					array_push($V15,$row1['C2D_P_CHK_SURFACE']);
					array_push($V16,$row1['C2D_P_CHK_LEAK']);
					array_push($V17,$row1['C2D_P_CHK_LABEL']);
					array_push($V18,$row1['C2D_P_CHK_CAP']);
					array_push($V19,$row1['C2D_MAKELOT_NO']);					
			}
			$sum1=array_sum($V12);
			$objPHPExcel->getActiveSheet()->setCellValue("F31",$sum1);
			for($i=0;$i<$numRows1;$i++)
			{
				$objPHPExcel->getActiveSheet()->setCellValue($ENG."7",$V01[$i] );
				$objPHPExcel->getActiveSheet()->setCellValue($ENG."8",$V02[$i] );
				$objPHPExcel->getActiveSheet()->setCellValue($ENG."9",$V19[$i] );
				$objPHPExcel->getActiveSheet()->setCellValue($ENG."10",$V03[$i] );
				$objPHPExcel->getActiveSheet()->setCellValue($ENG."11",$V04[$i] );
				$objPHPExcel->getActiveSheet()->setCellValue($ENG."12",$V05[$i] );
				$objPHPExcel->getActiveSheet()->setCellValue($ENG."13",$V06[$i] );
				$objPHPExcel->getActiveSheet()->setCellValue($ENG."14",$V07[$i] );
				$objPHPExcel->getActiveSheet()->setCellValue($ENG."15",$V08[$i] );
				$objPHPExcel->getActiveSheet()->setCellValue($ENG."16",$V09[$i] );
				$objPHPExcel->getActiveSheet()->setCellValue($ENG."17",$V10[$i] );
				$objPHPExcel->getActiveSheet()->setCellValue($ENG."21",$V01[$i] );
				$objPHPExcel->getActiveSheet()->setCellValue($ENG."22",$V11[$i] );
				$objPHPExcel->getActiveSheet()->setCellValue($ENG."23",$V12[$i] );
				$objPHPExcel->getActiveSheet()->setCellValue($ENG."24",$V13[$i] );
				$objPHPExcel->getActiveSheet()->setCellValue($ENG."25",$V14[$i] );
				$objPHPExcel->getActiveSheet()->setCellValue($ENG."26",$V15[$i] );
				$objPHPExcel->getActiveSheet()->setCellValue($ENG."27",$V16[$i] );
				$objPHPExcel->getActiveSheet()->setCellValue($ENG."28",$V17[$i] );
				$objPHPExcel->getActiveSheet()->setCellValue($ENG."29",$V18[$i] );
				$ENG++;
				$ans=($i+1)%8;
				$brk=($i+1);
				
				if($ans==0 and $brk<>$numRows1)
				{	
					
					$ENG='F';										
					$GLOBALS['num']++;
					$objPHPExcel->setActiveSheetIndex($GLOBALS['num']);
				///////////R1////////////////////////////////////////////////	
					
				$objPHPExcel->getActiveSheet()->setCellValue("F18",$aa);
				$objPHPExcel->getActiveSheet()->setCellValue("F19",$bb);
				$objPHPExcel->getActiveSheet()->setCellValue("F20",$cc);
				$objPHPExcel->getActiveSheet()->setCellValue("F30",$dd);
			
			$objPHPExcel->getActiveSheet()->setCellValue("B3",$prod );
			$objPHPExcel->getActiveSheet()->setCellValue("B2",$dat );
			$objPHPExcel->getActiveSheet()->setCellValue("B5",$short1 );
			$objPHPExcel->getActiveSheet()->setCellValue("B4",$_GET['id']);
			$objPHPExcel->getActiveSheet()->setCellValue("B32",$emp);
			//////R1//////////////////////////////////////////////////////////////////////////	
			
			$objPHPExcel->getActiveSheet()->setCellValue("A35",$ID1);////////////////////////////////////////////////////////////////////////////R3//////////////////////						
					
					
					$objPHPExcel->getActiveSheet()->setCellValue("F31",$sum1);
					
				}
				
			}
			
			$path_root=$_SERVER['HTTP_HOST'];
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
			$objWriter->save('20LEND.xlsx');
			echo '</br>';
			echo "列印完成";
		echo '<script>document.location.href="http://'.$path_root.'/fill/20LEND.xlsx";</script>';	
			
			
	}
			
			
			
			
		