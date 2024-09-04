<?php 
include("../checkuser.php");
include("../lib/fun.php");
datepick();
session_start();
	lasturl();
$_SESSION['lasturl']=$_SERVER['REQUEST_URI'];
if($_SESSION['datepicker1']==''){$_SESSION['datepicker1']=date("m/d/Y");}
if($_SESSION['datepicker2']==''){$_SESSION['datepicker2']=date("m/d/Y");}
if(isset($_POST['submit'])){
 	$_SESSION['printed']=$_POST['printed'];
	$_SESSION['cancel']=$_POST['cancel'];
}

?>
<!doctype html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />

</head>
<body>
 

<form name="form1" method="post" action="">

  <table width="1000" border="1">
  <tr>放行查詢
  </tr>
    <tr>
      <td width="300" bgcolor="#FFFFFF" class="d1" align="center">檢查日:
        <input type="text" name="datepicker1" id="datepicker1" size="10" value="<?php echo $_SESSION['datepicker1'];?>" onChange="set_date_session(this.name,this.value)">
~
<input type="text" name="datepicker2" id="datepicker2" size="10" value="<?php echo $_SESSION['datepicker2']?>" onChange="set_date_session(this.name,this.value)"></td>
      
      <td width="200" class="centerutton"><span class="d1">
      	包括已取消<input type="checkbox" name="cancel" <?php if($_SESSION['cancel']=='on'){echo 'checked';} else{echo '';}?> >&nbsp;&nbsp;&nbsp;
        包括已印過<input type="checkbox" name="printed" <?php if($_SESSION['printed']=='on'){echo 'checked';} else{echo '';}?> >&nbsp;&nbsp;&nbsp;
        <input name="submit" type="submit" class="centerutton" id="submit" value="  查  詢  ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        
      </span></td>
    </tr>
  </table>
</form>
</body>
</html>
<?php
$editFormAction = $_SERVER['PHP_SELF'];



if(isset($_POST['submit']) or isset($_POST['print'])){

	echo '<table width="1200" border="1"><tr  bgcolor="#CCCCCC" ><td width="10">OUT_NO</td><td width="80">運輸車輛</td><td width="20">檢查員</td><td width="20">決定書序號</td><td width="20">Lot No</td><td width="50">客戶</td><td width="100">建立日期</td><td width="100">列印日期</td><td width="100">取消日期</td><td width="120">列印</td></tr>';
	 
$query="SELECT DISTINCT YG_CAR_OUT.[index], YG_CAR_OUT.CAR_NO, YG_CAR_OUT.[DATE], YG_CAR_OUT.PRINTED, YG_CAR_OUT.CANCEL
 		FROM              YG_CAR_OUT INNER JOIN
                            YG_CHECK_DETAIL ON YG_CAR_OUT.[index] = YG_CHECK_DETAIL.car_out_id
                   WHERE (YG_CHECK_DETAIL.datetime  >= N'".dod($_POST['datepicker1'])."000000') and (YG_CHECK_DETAIL.datetime <= N'".dod($_POST['datepicker2'])."235959' )";

if($_POST['printed']<>'on'){
	$query.=" AND (LTRIM(RTRIM(YG_CAR_OUT.PRINTED)) IS NULL) ";
	
}
if($_POST['cancel']<>'on'){
	$query.=" and (CANCEL is NULL)";
	
}

$query.=" order by [index] desc";

// echo $query."<BR>";
$i=1;	
$j=0;
$result=mssql_query($query);
while($row=mssql_fetch_array($result)){
	if(trim($row['CAR_NO'])==''){$cano='Lorry 拖車';}
	else{$cano=$row['CAR_NO'];}
	list($otdno,$lotno,$custname)=get_otdno($row['index']);
	$lotno=get_lotno($row['index']);
	$print='
	<a  href=/YG/print_out.php?carid='.$row['index'].'&sub_type=1 target="_blank" >列印2張</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  href=/YG/cancel.php?carid='.$row['index'].' target="_blank" >取消</a>';
	echo '<tr><td width="10">'.$row['index'].'</td><td width="50">'.$cano.'</td><td width="50">'.checker($row['index']).'</td><td width="50">'.$otdno.'</td><td width="50">'.$lotno.'</td><td width="50">'.$custname.'</td><td width="50">'.sta($row['DATETIME']).'</td><td width="50">'.sta($row['PRINTED']).'</td><td width="50">'.sta($row['CANCEL']).'</td><td width="50">'.$print.'</td></tr>';
	echo '<input type="hidden" name="carno'.$j.'" value="'.$row['CAR_NO'].'">';
	echo '<input type="hidden" name="id'.$j.'" value="'.$row['index'].'">';
	echo '<input type="hidden" name="ckdate'.$j.'" value="'.$row['DATETIME'].'">';
	echo '<input type="hidden" name="checker'.$j.'" value="'.$row['PRINTED'].'">';
	echo '<input type="hidden" name="cartype'.$j.'" value="'.$row['CALCEL'].'">';
	echo '<input type="hidden" name="custn'.$j.'" value="'.$custname.'">';
	$i++;
	$j++;
}
$_SESSION['i']=$i;
$_SESSION['j']=$j;
echo '</form>';
}



if(isset($_POST['print']))
{
	$path_root=$_SERVER['HTTP_HOST'];
	$x=0;
	$i=$_SESSION['i'];
	$j=$_SESSION['j'];
	for($k=0;$k<$j;$k++)
		{
			$o='ck'.$k;
			if($_POST[$o]=='on'){

		   		$aa[$x][1]= $_POST['lotno'.$k];
				$aa[$x][2]= $_POST['ckdate'.$k];
				$aa[$x][3]= $_POST['checker'.$k];
				$aa[$x][4]= $_POST['cartype'.$k];
				$aa[$x][5]= $_POST['place'.$k];
				$aa[$x][6]= $_POST['lorryno'.$k];
				$aa[$x][7]= $_POST['pddc'.$k];
				$aa[$x][8]="**";
//				$aa[$x][8]= $_POST['custn'.$k];
				$x++;
			}
		}
	require_once "../PHPEXCEL/Classes/PHPExcel.php";
		require_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel = PHPExcel_IOFactory::load("letout.xlsx");
		$chem='';
	for($z=0;$z<$x;$z++){
		
		// 檢查是否為LORRY    A-02-2   口DRUM  口LORRY   出車 / 回廠  檢查放行單
		if($aa[$z][5]==0){	
		$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('B2',std($aa[$z][2]))
								->setCellValue("B3",iconv("big5","utf-8",$aa[$z][8]))
								->setCellValue('B4',$aa[$z][7])
								->setCellValue('J3',"V")
								->setCellValue('A1',iconv("big5","utf-8"," A-02-2   口DRUM  ■LORRY   出車 / 回廠  檢查放行單"))
								->setCellValue('K2',$aa[$z][6]); 
		}
		if($aa[$z][5]==1){	
		$chem.=$aa[$z][7].",";
		$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('B2',std($aa[$z][2]))
								->setCellValue("B3",iconv("big5","utf-8",$aa[$z][8]))
								->setCellValue('B4',$chem);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1',iconv("big5","utf-8"," A-02-2   口DRUM  口LORRY   出車 / 回廠  檢查放行單"));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J4',"V");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C6',trim($aa[$z][7]));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C7",trim($aa[$z][6]));

		}
		if($aa[$z][5]==2){	
		$chem.=$aa[$z][7].",";
		$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('B2',std($aa[$z][2]))
								->setCellValue("B3",iconv("big5","utf-8",$aa[$z][8]))
								->setCellValue('B4',$chem);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1',iconv("big5","utf-8"," A-02-2   口DRUM  口LORRY   出車 / 回廠  檢查放行單"));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J4',"V");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D6',trim($aa[$z][7]));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D7",trim($aa[$z][6]));

		}
		
		if($aa[$z][5]==3){	
		$chem.=$aa[$z][7].",";
		$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('B2',std($aa[$z][2]))
								->setCellValue("B3",iconv("big5","utf-8",$aa[$z][8]))
								->setCellValue('B4',$chem);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1',iconv("big5","utf-8"," A-02-2   口DRUM  口LORRY   出車 / 回廠  檢查放行單"));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J4',"V");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F6',trim($aa[$z][7]));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("F7",trim($aa[$z][6]));

		}
		if($aa[$z][5]==4){	
		$chem.=$aa[$z][7].",";
		$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('B2',std($aa[$z][2]))
								->setCellValue("B3",iconv("big5","utf-8",$aa[$z][8]))
								->setCellValue('B4',$chem);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1',iconv("big5","utf-8"," A-02-2   口DRUM  口LORRY   出車 / 回廠  檢查放行單"));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J4',"V");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G6',trim($aa[$z][7]));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("G7",trim($aa[$z][6]));
		}
		if($aa[$z][5]==5){	
		$chem.=$aa[$z][7].",";
		$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('B2',std($aa[$z][2]))
								->setCellValue("B3",iconv("big5","utf-8",$aa[$z][8]))
								->setCellValue('B4',$chem);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1',iconv("big5","utf-8"," A-02-2   口DRUM  口LORRY   出車 / 回廠  檢查放行單"));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J4',"V");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I6',trim($aa[$z][7]));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("I7",trim($aa[$z][6]));
		}
		if($aa[$z][5]==6){	
		$chem.=$aa[$z][7].",";
		$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('B2',std($aa[$z][2]))
								->setCellValue("B3",iconv("big5","utf-8",$aa[$z][8]))
								->setCellValue('B4',$chem);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1',iconv("big5","utf-8"," A-02-2   口DRUM  口LORRY   出車 / 回廠  檢查放行單"));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J4',"V");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J6',trim($aa[$z][7]));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("J7",trim($aa[$z][6]));
		}
		update_printed($aa[$z][1],$aa[$z][2]);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
		$objWriter->save("printout.xlsx");
		echo '<script>document.location.href="http://'.$path_root.'/YG/printout.xlsx";</script>';
		echo "<BR>";
		
		
	}
}

function update_printed($id)
{
	$query="UPDATE YG_CHECK_DETAIL SET PRINTED = 1,print_time ='".date("YmdHis")."' WHERE (car_out_id = ".$id.") ";	
	$result=mssql_query($query);
	$query="UPDATE YG_CAR_OUT SET PRINTED = N'".date("YmdHis")."' WHERE ([index] = ".$id.")";	
	$result=mssql_query($query);
}

function checker($id){
	$query="SELECT EMPLOYEE_DATA.EMP_NAME FROM YG_CHECK_DETAIL INNER JOIN EMPLOYEE_DATA ON YG_CHECK_DETAIL.CHECKER = EMPLOYEE_DATA.EMP_NO WHERE (YG_CHECK_DETAIL.car_out_id = ".$id.")";	
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	return $row[0];
}

function get_otdno($coid){
	$query="SELECT          OUT_PRODUCT.OTD_NO, OUT_PRODUCT.OPD_LOT_NO, CUSTOMER_DATA.CTD_CUST_SHORT_NAME
FROM              YG_CHECK_DETAIL INNER JOIN
                            OUT_PRODUCT ON YG_CHECK_DETAIL.LOT_NO = OUT_PRODUCT.OPD_LOT_NO INNER JOIN
                            OUT_DECISION ON OUT_PRODUCT.OPM_ORDER_NO = OUT_DECISION.OPM_ORDER_NO INNER JOIN
                            CUSTOMER_DATA ON OUT_DECISION.CTD_CUST_NO = CUSTOMER_DATA.CTD_CUST_NO
WHERE (YG_CHECK_DETAIL.car_out_id = ".$coid.")";	
// echo "<BR>".$query."<BR>";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	return array($row[0],$row[1],$row[2]);
}

function get_lotno($coid){
	$nn='';
	$query="SELECT     distinct     LOT_NO
FROM              YG_CHECK_DETAIL
WHERE          (car_out_id = ".$coid.")";	
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		$nn=$nn."(".$row['LOT_NO'].")";
	}

	return $nn;
}
?>
