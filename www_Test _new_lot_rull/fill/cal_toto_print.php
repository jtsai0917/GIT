
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title></title>
CAL 在庫TOTO轉出荷TOTO移液作業檢查表
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

			
if(isset($_POST['leave']))
	{
		jumpto($_SESSION['lasturl']);
	}
	if(isset($_POST['print']))
		{ 	$objPHPExcel = new PHPExcel();
			$objPHPExcel = PHPExcel_IOFactory::load("./toto.xlsx");	
			$objPHPExcel->setActiveSheetIndex(0);
			$R1="select PDD.PDD_PROD_NAME,FOD.FOD_O_YEAR_MONTH,	FOD.FOD_O_DAY,CTD.CTD_CUST_SHORT_NAME,FOD.FDM_QTY,FOD.FDM_SAM_BEF_CNT,FOD.FDM_SAM_CNT,FOD.FDM_ATTACH_CNT
		from 	IN2OUT_CAL_TOTO_CHECK  as T2M
		inner join  FILLPLAN_OUT_DECIDE as FOD on T2M.FDM_LOT_NO = FOD.FDM_LOT_NO
		inner join	PRODUCT_DATA as PDD on FOD.PDD_PROD_NO = PDD.PDD_PROD_NO
		inner  join CUSTOMER_DATA as CTD on FOD.CTD_CUST_NO = CTD.CTD_CUST_NO
	

		where T2M.FDM_LOT_NO='".$_GET['id']."'";
		//echo $R1;
			$result = mssql_query($R1);
			$numRows = mssql_num_rows($result);
			while($row = mssql_fetch_array($result))
			{
				$prod=iconv("big5","utf-8",$row['PDD_PROD_NAME']);
				$dat=$row['FOD_O_YEAR_MONTH'].$row['FOD_O_DAY'];
				$cust=iconv("big5","utf-8",$row['CTD_CUST_SHORT_NAME']);
				$qty=$row['FDM_QTY'];
				$number=$row['FDM_SAM_BEF_CNT']+$row['FDM_SAM_CNT']+$row['FDM_ATTACH_CNT'];
			}
			$objPHPExcel->getActiveSheet()->setCellValue("E7",$prod );
			$objPHPExcel->getActiveSheet()->setCellValue("E8",$dat );
			$objPHPExcel->getActiveSheet()->setCellValue("E9",$cust );
			$objPHPExcel->getActiveSheet()->setCellValue("E10",$qty );
			$objPHPExcel->getActiveSheet()->setCellValue("E11",$number );
			
			$R2="select T2M.T2M_LOT_NO,T2M.FDM_LOT_NO,T2M.T2M_I_DRUM_NO,T2M.T2M_O_DRUM_NO,T2M.T2M_W_I_CHK_SURFACE,T2M.T2M_W_O_CHK_SURFACE,T2M.T2M_W_I_WEIGHT,T2M.T2M_W_O_WEIGHT,EMP1.EMP_NAME as EMP1,T2M.T2M_B_CHK_FILL_CLOSE,T2M.T2M_B_CHK_FILL_PIPE,T2M.T2M_B_I_CHK_WASHER,T2M.T2M_B_O_CHK_WASHER,T2M.T2M_B_I_CHK_FILL_PIPE
			from		IN2OUT_CAL_TOTO_CHECK  as T2M
								inner join FILLPLAN_OUT_DECIDE as FOD on T2M.FDM_LOT_NO = FOD.FDM_LOT_NO

			inner	join	EMPLOYEE_DATA as EMP1 on FOD.FDM_CREATOR = EMP1.EMP_NO
			
			
			where T2M.FDM_LOT_NO='".$_GET['id']."'";
			$result1 = mssql_query($R2);
			$numRows1 = mssql_num_rows($result1);
			while($row1 = mssql_fetch_array($result1))
			{
				$objPHPExcel->getActiveSheet()->setCellValue("E13",$row1['T2M_LOT_NO']);
				$objPHPExcel->getActiveSheet()->setCellValue("G13",$row1['FDM_LOT_NO']);
				$objPHPExcel->getActiveSheet()->setCellValue("E14",$row1['T2M_I_DRUM_NO']);
				$objPHPExcel->getActiveSheet()->setCellValue("G14",$row1['T2M_O_DRUM_NO']);
				$objPHPExcel->getActiveSheet()->setCellValue("E15",$row1['T2M_W_I_CHK_SURFACE']);
				$objPHPExcel->getActiveSheet()->setCellValue("G15",$row1['T2M_W_O_CHK_SURFACE']);
				$objPHPExcel->getActiveSheet()->setCellValue("E16",$row1['T2M_W_I_WEIGHT']);
				$objPHPExcel->getActiveSheet()->setCellValue("G16",$row1['T2M_W_O_WEIGHT']);
				$emp1=iconv("big5","utf-8",$row1['EMP1']);
				$objPHPExcel->getActiveSheet()->setCellValue("E18",$row1['T2M_B_CHK_FILL_CLOSE']);
				$objPHPExcel->getActiveSheet()->setCellValue("E19",$row1['T2M_B_CHK_FILL_PIPE']);
				$objPHPExcel->getActiveSheet()->setCellValue("E20",$row1['T2M_B_I_CHK_WASHER']);
				$objPHPExcel->getActiveSheet()->setCellValue("G20",$row1['T2M_B_O_CHK_WASHER']);
				$objPHPExcel->getActiveSheet()->setCellValue("E21",$row1['T2M_B_I_CHK_FILL_PIPE']);
				
				
				
			}
			$objPHPExcel->getActiveSheet()->setCellValue("E17",$emp1);
			//echo $R2;
			
			$R3="select FOD.FDM_PURGE,T2M.T2M_B_O_CHK_FILL_PIPE,T2M.T2M_F_CHK_OPEN_PUMP,T2M.T2M_F_BEGIN_TIME,T2M.T2M_F_END_TIME,FID.FID_SAM_COUNT,T2M.T2M_F_CHK_CLOSE_PUMP,T2M.T2M_E_I_CHK_SURFACE,T2M.T2M_E_O_CHK_SURFACE,T2M.T2M_E_I_CHK_CLOSE_CAP,T2M.T2M_E_O_CHK_CLOSE_CAP,T2M.T2M_E_I_CHK_PIPE_PICKUP,T2M.T2M_E_O_CHK_PIPE_PICKUP,EMP2.EMP_NAME as NAME2,T2M.T2M_C_CHK_PACKAGE,T2M.T2M_C_O_WEIGHT,T2M.T2M_W_O_WEIGHT,T2M.T2M_C_O_WEIGHT
			from IN2OUT_CAL_TOTO_CHECK  as T2M
			inner join FILLPLAN_OUT_DECIDE as FOD on T2M.FDM_LOT_NO = FOD.FDM_LOT_NO
			inner   join FILL_INDICATE as FID on FOD.FDM_LOT_NO = FID.FDM_LOT_NO
			inner join EMPLOYEE_DATA as EMP2 on T2M.T2M_FILLER = EMP2.EMP_NO
			
			where T2M.FDM_LOT_NO='".$_GET['id']."'";

			echo'</br>';
			//echo $R3;
			
			$result2 = mssql_query($R3);
			$numRows2 = mssql_num_rows($result2);
			while($row2 = mssql_fetch_array($result2))
			{
				$stmin=substr($row2['T2M_F_BEGIN_TIME'],0,2);
				$stsec=substr($row2['T2M_F_BEGIN_TIME'],2,2);
				$edmin=substr($row2['T2M_F_END_TIME'],0,2);
				$edsec=substr($row2['T2M_F_END_TIME'],2,2);
				$objPHPExcel->getActiveSheet()->setCellValue("E22",$row2['FDM_PURGE']);
				$objPHPExcel->getActiveSheet()->setCellValue("G23",$row2['T2M_B_O_CHK_FILL_PIPE']);
				$objPHPExcel->getActiveSheet()->setCellValue("E24",$row2['T2M_F_CHK_OPEN_PUMP']);
				$objPHPExcel->getActiveSheet()->setCellValue("E27",$row2['FID_SAM_COUNT']);
				$objPHPExcel->getActiveSheet()->setCellValue("E28",$row2['T2M_F_CHK_CLOSE_PUMP']);
				$objPHPExcel->getActiveSheet()->setCellValue("E29",$row2['T2M_E_I_CHK_SURFACE']);
				$objPHPExcel->getActiveSheet()->setCellValue("G29",$row2['T2M_E_O_CHK_SURFACE']);
				$objPHPExcel->getActiveSheet()->setCellValue("E30",$row2['T2M_E_I_CHK_CLOSE_CAP']);
				$objPHPExcel->getActiveSheet()->setCellValue("G30",$row2['T2M_E_O_CHK_CLOSE_CAP']);
				$objPHPExcel->getActiveSheet()->setCellValue("E31",$row2['T2M_E_I_CHK_PIPE_PICKUP']);
				$objPHPExcel->getActiveSheet()->setCellValue("G31",$row2['T2M_E_O_CHK_PIPE_PICKUP']);
				$name2=iconv("big5","utf-8",$row2['NAME2']);
				$objPHPExcel->getActiveSheet()->setCellValue("E33",$row2['T2M_C_CHK_PACKAGE']);
				$objPHPExcel->getActiveSheet()->setCellValue("E34",$row2['T2M_C_O_WEIGHT']);
				
				$weight=$row2['T2M_W_O_WEIGHT']-$row2['T2M_C_O_WEIGHT'];
				
				
			}
			$totosec=(($edmin*60)+$edsec)-(($stmin*60)+$stsec);
			$timest=$stmin.":".$stsec;
			$timend=$edmin.":".$edsec;
			$objPHPExcel->getActiveSheet()->setCellValue("E25",$timest);
			$objPHPExcel->getActiveSheet()->setCellValue("E26",$timend);
			$objPHPExcel->getActiveSheet()->setCellValue("G25",$totosec);
			$objPHPExcel->getActiveSheet()->setCellValue("E32",$name2);
			$objPHPExcel->getActiveSheet()->setCellValue("E35",$weight);
			
			

			$path_root=$_SERVER['HTTP_HOST'];
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
			$objWriter->save('toto1.xlsx');
						echo '</br>';
			echo "列印完成";
		echo '<script>document.location.href="http://'.$path_root.'/fill/toto1.xlsx";</script>';	
			
		}