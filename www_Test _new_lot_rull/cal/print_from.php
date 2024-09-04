<meta http-equiv="Content-Type" content="text/html; charset=big5" />
	<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
include  "../PHPEXCEL/Classes/PHPExcel.php";
include "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";

datepick();
?>
<?php

require_once ('../PHPWord/PHPWord.php');
	$PHPWord = new PHPWord();
	$document = $PHPWord->loadTemplate('./all/TYS.docx');
	$num=0;
//	if(trim($_SESSION['cust_no'])=='')
//	{echo "請選擇客戶!";}
//	if($_SESSION['cust_no']<>'')
	$_SESSION['chkkk']=$_GET['chk'];
		$chose=explode(",",$_GET['chk']);		
		$document->setValue("CUST",get_cust_name($chose[3]));
		$date3=dat1($chose[2]);echo "lalala";
		$document->setValue("DAT",$date3);

		
	if(trim($chose[3])=='C13121' and trim($chose[4])=='LY')
	{
		$prints=" select OP.OPD_QTY_DRUM,PD.PDD_STYLE,PD.PDD_PROD_NAME,PD.PDD_TYPE,OP.OPD_LOT_NO,OP.OAF_PACKAGE ,OP.OPD_QTY_KG,OP.OPD_ACC_UNIT from OUT_PRODUCT as OP inner join PRODUCT_DATA as PD on OP.PDD_PROD_NO=PD.PDD_PROD_NO 
 
inner join OUT_DECISION as OD on OD.OTD_NO=OP.OTD_NO 
where OD.OPM_ETA_DATE='".$chose[2]."'    and CTD_CUST_NO='C13121'   and   PDD_TYPE='LY' ";		
	}
	else
	{
		$prints="select OP.OPD_QTY_DRUM,PD.PDD_STYLE,PD.PDD_PROD_NAME,PD.PDD_TYPE,OP.OPD_LOT_NO,OP.OAF_PACKAGE
		,OP.OPD_QTY_KG,OP.OPD_ACC_UNIT
		from OUT_PRODUCT as OP 
		inner join PRODUCT_DATA as PD on OP.PDD_PROD_NO=PD.PDD_PROD_NO
		
		inner join OUT_DECISION as OD on OD.OTD_NO=OP.OTD_NO 

		where OP.OPM_ORDER_NO='".$chose[0]."'   and  OD.OPM_ETA_DATE='".$chose[2]."' and  CTD_CUST_NO='".$chose[3]."' ";
	}
	 echo $prints;
	$result = mssql_query($prints);
	$numRows = mssql_num_rows($result);
	while($row = mssql_fetch_array($result))
	{	
		if(trim($row['PDD_TYPE'])!="LY")
		{
		$document->setValue("NAME".$num,$row['PDD_PROD_NAME']);
		$document->setValue("STYLE".$num,$row['PDD_STYLE']);
		$document->setValue("QTY".$num,$row['PDD_STYLE']."*".$row['OPD_QTY_DRUM']);
		if(substr($row['OPD_LOT_NO'],0,1)=="T")
		{
			$LOTNO=substr($row['OPD_LOT_NO'],0,7);
		}
		else
		{
			$LOTNO=$row['OPD_LOT_NO'];
		}
		$document->setValue("LOT".$num,$LOTNO);
		$num++;
		}
		if(trim($row['PDD_TYPE'])=="LY")
		{
		$document->setValue("NAME".$num,$row['PDD_PROD_NAME']);
		$document->setValue("STYLE".$num,$row['OAF_PACKAGE']);
		$document->setValue("QTY".$num,floor($row['OPD_QTY_KG'])." ".$row['OPD_ACC_UNIT']);
		if(substr($row['OPD_LOT_NO'],0,1)=="T")
		{
			$LOTNO=substr($row['OPD_LOT_NO'],0,7);
		}
		else
		{
			$LOTNO=$row['OPD_LOT_NO'];
		}
		$document->setValue("LOT".$num,$LOTNO);
		$num++;
		}
	}	

	
	for($i=0;$i<10;$i++)
	{
		$document->setValue("NAME".$num," ");
		$document->setValue("STYLE".$num," ");
		$document->setValue("QTY".$num," ");
		$document->setValue("LOT".$num," ");
		
		$num++;
	}
	$document->save('./all/TYS1.docx');
	//echo "???";if(isset($_POST['submit2']))
	
			
			
$path_root=$_SERVER['HTTP_HOST'];
			echo '</br>';
			echo "請用儲存後列印";
		echo '<script>document.location.href="http://'.$path_root.'/cal/all/TYS1.docx";</script>';	
		

function dat1($ds){   //day-style  201509020102=>09/02/2015
		$dat=substr($ds,0,+4)."/".substr($ds,4,+2)."/".substr($ds,6,+2);
	return $dat;
	
}

?>