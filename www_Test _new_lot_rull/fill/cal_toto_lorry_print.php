<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title></title>
CAL TOTO轉LORRY移液作業檢查表
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
//echo'</br>';
echo $_GET['id'];

if(isset($_POST['leave']))
	{
		jumpto($_SESSION['lasturl']);
	}
	if(isset($_POST['print']))
	{	
			$objPHPExcel = new PHPExcel();
			$objPHPExcel = PHPExcel_IOFactory::load("./CAL_TOTO_LORRY.xlsx");	
			$objPHPExcel->setActiveSheetIndex(0);
		$R1="select		
		PDD.PDD_PROD_NAME,FID.FID_FILL_BEGIN_DATE,CTD.CTD_CUST_SHORT_NAME,
		FOD.FDM_QTY,FOD.FDM_SAM_BEF_CNT,FOD.FDM_SAM_CNT,FOD.FDM_ATTACH_CNT,T3M.FDM_LOT_NO,T3M.T3M_F_SAM_PURGE,
		T3M.T3M_LY_NO,T3M.T3M_W_CHK_LY_SURFACE,T3M.T3M_W_LY_WEIGHT,PDD.PDD_LITER_KG,T3M.T3M_W_LY_WEIGHT2,
		PDD.PDD_LITER_KG,T3M.T3M_W_LY_WEIGHT2,EMP1.EMP_NAME as name1 ,T3M.T3M_B_CHK_FILL_CLOSE,FOD.FDM_PURGE,T3M.T3M_B_CHK_COUP_CLR,
		T3M.T3M_B_CHK_COUP,T3M.T3M_B_CHK_COUP_LINK,T3M.T3M_B_CHK_DM_REL,T3M.T3M_B_SET_FILL,T3M.T3M_F_CHK_OPEN_PUMP,
		FID.FID_SAM_COUNT,T3M.T3M_E_CHK_CLOSE_PUMP,T3M.T3M_E_CHK_AIR_SEAL,T3M.T3M_E_CHK_EXHAUST,T3M.T3M_E_CHK_VALVE,
		T3M.T3M_E_CHK_COUP,T3M.T3M_E_CHK_PIPE_PICKUP,EMP2.EMP_NAME  as name2,T3M.T3M_C_O_WEIGHT,T3M.T3M_W_LY_WEIGHT
		
		
		from   IN2LORRY_CAL_TOTO_CHECK  as T3M
		inner join FILLPLAN_OUT_DECIDE as FOD on T3M.FDM_LOT_NO = FOD.FDM_LOT_NO
		inner join 		PRODUCT_DATA as PDD on FOD.PDD_PROD_NO = PDD.PDD_PROD_NO
		inner join		FILL_INDICATE as FID on FOD.FDM_LOT_NO = FID.FDM_LOT_NO
		inner join 		CUSTOMER_DATA as CTD on FOD.CTD_CUST_NO = CTD.CTD_CUST_NO
		inner join 		EMPLOYEE_DATA as EMP1 on FOD.FDM_CREATOR = EMP1.EMP_NO
		inner join 		EMPLOYEE_DATA as EMP2 on T3M.T3M_FILLER = EMP2.EMP_NO
			
		
		where  T3M.FDM_LOT_NO='".$_GET['id']."'";
		//echo $R1;
			$result = mssql_query($R1);
			$numRows = mssql_num_rows($result);
			while($row = mssql_fetch_array($result))
			{
				$dat=substr($row['FID_FILL_BEGIN_DATE'],0,8);
				$cust=iconv("big5","utf-8",$row['CTD_CUST_SHORT_NAME']);
				$need=$row['FDM_SAM_BEF_CNT']+$row['FDM_SAM_CNT']+$row['FDM_ATTACH_CNT'];
				$name1=iconv("big5","utf-8",$row['name1']);
				$name2=iconv("big5","utf-8",$row['name2']);
				$sub=$row['T3M_C_O_WEIGHT']-$row['T3M_W_LY_WEIGHT'];
				$objPHPExcel->getActiveSheet()->setCellValue("D8",$row['PDD_PROD_NAME']);
				$objPHPExcel->getActiveSheet()->setCellValue("P9",$row['FDM_QTY']);
				$objPHPExcel->getActiveSheet()->setCellValue("P9",$row['FDM_QTY']);
				$objPHPExcel->getActiveSheet()->setCellValue("D12",$_GET['id']);				
				$objPHPExcel->getActiveSheet()->setCellValue("D13",$row['T3M_LY_NO']);
				$objPHPExcel->getActiveSheet()->setCellValue("D14",$row['T3M_W_CHK_LY_SURFACE']);
				$objPHPExcel->getActiveSheet()->setCellValue("D15",$row['T3M_W_LY_WEIGHT']);
				$objPHPExcel->getActiveSheet()->setCellValue("L15",$row['PDD_LITER_KG']);
				$objPHPExcel->getActiveSheet()->setCellValue("P15",$row['T3M_W_LY_WEIGHT2']);
				$objPHPExcel->getActiveSheet()->setCellValue("D17",$row['T3M_B_CHK_FILL_CLOSE']);
				$objPHPExcel->getActiveSheet()->setCellValue("D19",$row['T3M_F_SAM_PURGE']);//有問題,改成這個對
				$objPHPExcel->getActiveSheet()->setCellValue("D20",$row['T3M_B_CHK_COUP_CLR']);
				$objPHPExcel->getActiveSheet()->setCellValue("D21",$row['T3M_B_CHK_COUP']);
				$objPHPExcel->getActiveSheet()->setCellValue("D22",$row['T3M_B_CHK_COUP_LINK']);
				$objPHPExcel->getActiveSheet()->setCellValue("D23",$row['T3M_B_CHK_DM_REL']);
				$objPHPExcel->getActiveSheet()->setCellValue("D24",$row['T3M_B_SET_FILL']);
				$objPHPExcel->getActiveSheet()->setCellValue("D25",$row['T3M_F_CHK_OPEN_PUMP']);
				$objPHPExcel->getActiveSheet()->setCellValue("D28",$row['FID_SAM_COUNT']);
				$objPHPExcel->getActiveSheet()->setCellValue("D29",$row['T3M_E_CHK_CLOSE_PUMP']);
				$objPHPExcel->getActiveSheet()->setCellValue("D30",$row['T3M_E_CHK_AIR_SEAL']);
				$objPHPExcel->getActiveSheet()->setCellValue("D31",$row['T3M_E_CHK_EXHAUST']);
				$objPHPExcel->getActiveSheet()->setCellValue("D32",$row['T3M_E_CHK_VALVE']);
				$objPHPExcel->getActiveSheet()->setCellValue("D33",$row['T3M_E_CHK_COUP']);
				$objPHPExcel->getActiveSheet()->setCellValue("D34",$row['T3M_E_CHK_PIPE_PICKUP']);
				$objPHPExcel->getActiveSheet()->setCellValue("D36",$row['T3M_C_O_WEIGHT']);
				//EXCEL上D27D38都印不出來所以都沒做
				
				
				
				
				

			}
			$objPHPExcel->getActiveSheet()->setCellValue("P8",$dat);
			$objPHPExcel->getActiveSheet()->setCellValue("D9",$cust);
			$objPHPExcel->getActiveSheet()->setCellValue("D10",$need);
			$objPHPExcel->getActiveSheet()->setCellValue("D16",$name1);
			$objPHPExcel->getActiveSheet()->setCellValue("D35",$name2);
			$objPHPExcel->getActiveSheet()->setCellValue("D37",$sub);
			
			$V01=array();
			$V02=array();
			$V03=array();
			$V04=array();
			$V05=array();
			
			$R2="select T3D_LOT_NO,T3D_DRUM_NO,T3D_W_CHK_SURFACE,T3D_B_CHK_FILL_PIPE,T3D_F_TIME
			from  IN2LORRY_CAL_TOTO_DRUM_CHECK_DRUM 
			where FDM_LOT_NO='".$_GET['id']."'";
			$result1 = mssql_query($R2);
			$numRows1 = mssql_num_rows($result1);
			while($row1 = mssql_fetch_array($result1))
			{
				array_push($V01,$row1['T3D_LOT_NO']);
				array_push($V02,$row1['T3D_DRUM_NO']);
				array_push($V03,$row1['T3D_W_CHK_SURFACE']);
				array_push($V04,$row1['T3D_B_CHK_FILL_PIPE']);
				array_push($V05,$row1['T3D_F_TIME']);
			}
			$ENG='F';
			for($i=0;$i<$numRows1;$i++)
			{	
				$objPHPExcel->getActiveSheet()->setCellValue($ENG."12",$V01[$i]);
				$objPHPExcel->getActiveSheet()->setCellValue($ENG."13",$V02[$i]);
				$objPHPExcel->getActiveSheet()->setCellValue($ENG."14",$V03[$i]);
				$objPHPExcel->getActiveSheet()->setCellValue($ENG."18",$V04[$i]);
				$objPHPExcel->getActiveSheet()->setCellValue($ENG."25",$V05[$i]);
				
				$ENG++;
				$ENG++;

			}
			$VR3=array();
			$R3="select SMA_ID
			from Sample_All
			where SMA_LOT='".$_GET['id']."'";
			$result2 = mssql_query($R3);
			$numRows2 = mssql_num_rows($result2);
			while($row2 = mssql_fetch_array($result2))
			{
				array_push($VR3,$row2['SMA_ID']);
			}
			//echo $R3;
			$NUM=30;
			$ENG1="K";
			for($J=0;$J<$numRows2;$J++)
			{	
				$objPHPExcel->getActiveSheet()->setCellValue($ENG1.$NUM,$VR3[$J]);
				$NUM++;
				if($J==8or $J==17)
				{
					$ENG1++;$ENG1++;
					$NUM=30;
				}
			}
			
			$path_root=$_SERVER['HTTP_HOST'];
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
			$objWriter->save('CAL_TOTO_LORRY_1.xlsx');
			echo '</br>';
			echo "列印完成";
		echo '<script>document.location.href="http://'.$path_root.'/fill/CAL_TOTO_LORRY_1.xlsx";</script>';	
	}
