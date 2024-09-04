<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
include("../PHPEXCEL/Classes/PHPExcel.php");
include("../PHPEXCEL/Classes/PHPExcel/IOFactory.php");
datepick();
$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);
?>

      充填報表<br />
<td><form name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <input type="text" name="datepicker1" id="datepicker1" size="10" value="<?php echo $_SESSION['datepicker1'];?>" onchange="set_date_session(this.name,this.value)">
  ~
  <input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php echo $_SESSION['datepicker2'];?>" onchange="set_date_session(this.name,this.value)">
  <input name="print" type="submit" id="print" value="  查詢  ">
  <input name="print1" type="submit" id="print" value="  列印實際充填量  ">
  <input name="print2" type="submit" id="print" value="  列印估計充填量  ">
  <input name="print3" type="submit" id="print" value="  查詢(列出所有LOT)  ">
  <input name="print4" type="submit" id="print" value="  列印(列出所有LOT)">
  </form>

<?php
if(isset($_POST["print"]))
{	
//	$date2=substr($_POST['datepicker1'],-4)."年".substr($_POST['datepicker1'],0,2)."月".substr($_POST['datepicker1'],3,2)."日";
//	$date1=dod($_POST['datepicker1']);
	echo '<table border="1">';
	echo '<tr><td>充填日</td><td bgcolor="#FFFF00">T-2121B</td><td bgcolor="#FFFF00">T-3123C</td><td bgcolor="#FFFF00">T-3123D</td><td bgcolor="#FFFF00">T-3123E</td><td bgcolor="#FFFF00">T-3123F</td><td bgcolor="#FFFF00">T-3122A</td><td bgcolor="#FFFF00">T-3122B</td><td bgcolor="#33FF99">T-3121A</td><td bgcolor="#33FF99">T-3121B</td><td bgcolor="#33FF99">T-3122C</td><td bgcolor="#33FF99">T-3122D</td><td  bgcolor="#FF99FF">一系</td><td  bgcolor="#FF99FF">LL</td><td  bgcolor="#FF99FF">HL</td><td  bgcolor="#FF99FF">一系估計值</td><td  bgcolor="#FF99FF">LL估計值</td><td  bgcolor="#FF99FF">HL估計值</td></tr>';
	
	$first_day=ddo($_POST['datepicker1']);
	$last_day=ddo($_POST['datepicker2']);
	$AA=date_range($first_day,$last_day);
	$n=count($AA);
	for($i=0;$i<$n;$i++){
		$list=new byday;
		$list->date=des($AA[$i]);
		$list->total();
		if(fmod($i,2)==1){$color=' bgcolor="#CCCCCC"';}
		else{$color='';}
		echo '<tr'.$color.'><td>'.$AA[$i].'</td><td>'.$list->t2121b.'</td><td>'.$list->t3123c.'</td><td>'.$list->t3123d.'</td><td>'.$list->t3123e.'</td><td>'.$list->t3123f.'</td><td>'.$list->t3122a.'</td><td>'.$list->t3122b.'</td><td>'.$list->t3121a.'</td><td>'.$list->t3121b.'</td><td>'.$list->t3122c.'</td><td>'.$list->t3122d.'</td><td>'.$list->total_1.'</td><td>'.$list->total_low.'</td><td>'.$list->total_high.'</td><td>'.number_format($list->total_1_).'</td><td>'.number_format($list->total_low_).'</td><td>'.number_format($list->total_high_).'</td></tr>';
		
	}
}


if(isset($_POST["print1"]))
{
	$path_root=$_SERVER['HTTP_HOST'];
	$objPHPExcel = new PHPExcel();
	$objPHPExcel = PHPExcel_IOFactory::load("H2SO4.xlsx");
	$objPHPExcel->setActiveSheetIndex(0);
//	$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow(0,1, iconv("big5","utf-8","LOT NO"));		
//  get data
	$first_day=ddo($_POST['datepicker1']);
	$last_day=ddo($_POST['datepicker2']);
	$AA=date_range($first_day,$last_day);
	$n=count($AA);
	$weekarray=array("日","一","二","三","四","五","六");
	$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow(0,1,date("Y"));
	for($i=0;$i<$n;$i++){
		$list=new byday;
		$list->date=des($AA[$i]);
		$list->total();
		$weekno=datetrans($AA[$i],"w");
		$des=str_replace('-','/',$AA[$i]);
		$dds=str_replace('-','',$AA[$i]);
		$week=mb_convert_encoding($weekarray[$weekno],"utf-8","big5");
	//	$rng=AtoZ($i+4)."11:".AtoZ($i+4)."18";
	//	$rng1=AtoZ($i+4)."24:".AtoZ($i+4)."29";
	//	$objPHPExcel->setActiveSheetIndex(0)->mergeCells($rng); 
	//	$objPHPExcel->setActiveSheetIndex(0)->mergeCells($rng1); 
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,3,substr($des,5));
 		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,4,$week);
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,5,get_daily_value(trim($des),1));
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,6,get_daily_value(trim($des),2));
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,7,get_daily_value(trim($des),3));
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,8,get_daily_value(trim($des),4));
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,9,get_daily_value(trim($des),5));
//		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,11,get_daily_value(trim($des),6));
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,19,get_daily_value(trim($des),11));
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,21,get_daily_value(trim($des),13));
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,22,get_daily_value(trim($des),14));
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,23,get_daily_value(trim($des),15));  //ok
//		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,24,get_daily_value(trim($des),16));
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,30,get_daily_value(trim($des),20));
//		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,32,get_daily_value(trim($des),21));
//		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,33,get_daily_value(trim($des),22));
		
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,36,get_hic01_value(trim($dds))/1000);
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,37,get_hic02_value(trim($dds))/1000);

//		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,38,get_daily_value(trim($des),27));
//		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,39,get_daily_value(trim($des),28));
//		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,40,get_daily_value(trim($des),29));
		
/*
		for($s=3;$s<=9;$s++){
			$objPHPExcel->setActiveSheetIndex()->getStyle(AtoZ($i+4).$s)->applyFromArray($styleThinBlackBorderOutline);
		}
		for($s=11;$s<=27;$s++){
			$objPHPExcel->setActiveSheetIndex()->getStyle(AtoZ($i+4).$s)->applyFromArray($styleThinBlackBorderOutline);
		}
		for($s=29;$s<=37;$s++){
			$objPHPExcel->setActiveSheetIndex()->getStyle(AtoZ($i+4).$s)->applyFromArray($styleThinBlackBorderOutline);
		}

		border(AtoZ($i+4),3,9);
		border(AtoZ($i+4),11,27);
		border(AtoZ($i+4),29,37);
*/
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,20,$list->total_low/1000);
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,30,$list->total_high/1000);
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,34,$list->line1/1000);
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,35,$list->dm/1000);
	}



	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
	$objWriter->save('find2.xlsx');
	echo '<script>document.location.href="http://'.$path_root.'/PRODUCE/find2.xlsx";</script>';
}

if(isset($_POST["print2"]))
{
	$path_root=$_SERVER['HTTP_HOST'];
	$objPHPExcel = new PHPExcel();
	ini_set("memory_limit", "128M"); 
	$objPHPExcel = PHPExcel_IOFactory::load("H2SO4.xlsx");
	$objPHPExcel->setActiveSheetIndex(0);
//	$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow(0,1, iconv("big5","utf-8","LOT NO"));		
//  get data
	$first_day=ddo($_POST['datepicker1']);
	$last_day=ddo($_POST['datepicker2']);
	$AA=date_range($first_day,$last_day);
	$n=count($AA);
	$weekarray=array("日","一","二","三","四","五","六");
	$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow(0,1,date("Y"));
	for($i=0;$i<$n;$i++){
		$list=new byday;
		$list->date=des($AA[$i]);
		$list->total();
		$weekno=datetrans($AA[$i],"w");
		$des=str_replace('-','/',$AA[$i]);
		$dds=str_replace('-','',$AA[$i]);
		$week=mb_convert_encoding($weekarray[$weekno],"utf-8","big5");
	//	$rng=AtoZ($i+4)."11:".AtoZ($i+4)."18";
	//	$rng1=AtoZ($i+4)."24:".AtoZ($i+4)."29";
	//	$objPHPExcel->setActiveSheetIndex(0)->mergeCells($rng); 
	//	$objPHPExcel->setActiveSheetIndex(0)->mergeCells($rng1); 
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,3,substr($des,5));
 		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,4,$week);
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,5,get_daily_value(trim($des),1));
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,6,get_daily_value(trim($des),2));
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,7,get_daily_value(trim($des),3));
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,8,get_daily_value(trim($des),4));
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,9,get_daily_value(trim($des),5));
//		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,11,get_daily_value(trim($des),6));
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,19,get_daily_value(trim($des),11));
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,21,get_daily_value(trim($des),13));
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,22,get_daily_value(trim($des),14));
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,23,get_daily_value(trim($des),15));  //ok
//		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,24,get_daily_value(trim($des),16));
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,30,get_daily_value(trim($des),20));
//		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,32,get_daily_value(trim($des),21));
//		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,33,get_daily_value(trim($des),22));
		
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,36,get_hic01_value(trim($dds))/1000);
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,37,get_hic02_value(trim($dds))/1000);
		
//		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,38,get_daily_value(trim($des),27));
//		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,39,get_daily_value(trim($des),28));
//		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,40,get_daily_value(trim($des),29));
/*
		for($s=3;$s<=9;$s++){
			$objPHPExcel->setActiveSheetIndex()->getStyle(AtoZ($i+4).$s)->applyFromArray($styleThinBlackBorderOutline);
		}
		for($s=11;$s<=27;$s++){
			$objPHPExcel->setActiveSheetIndex()->getStyle(AtoZ($i+4).$s)->applyFromArray($styleThinBlackBorderOutline);
		}
		for($s=29;$s<=37;$s++){
			$objPHPExcel->setActiveSheetIndex()->getStyle(AtoZ($i+4).$s)->applyFromArray($styleThinBlackBorderOutline);
		}

		border(AtoZ($i+4),3,9);
		border(AtoZ($i+4),11,27);
		border(AtoZ($i+4),29,37);
*/
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,20,$list->total_low_/1000);
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,30,$list->total_high_/1000);
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,34,$list->line1_/1000);
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow($i+3,35,$list->dm/1000);
	}



	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
	$objWriter->save('find2.xlsx');
	echo '<script>document.location.href="http://'.$path_root.'/PRODUCE/find2.xlsx";</script>';
}

class byday{
	public $date,$total_low,$total_high,$total_1,$t2121b,$t3123c,$t3123d,$t3123e,$t3123f,$t3122a,$t3122b,$t3121a,$t3121b,$t3122c,$t3122d,$dm,$line1,$total_low_,$total_high_,$total_1_,$t2121b_,$t3123c_,$t3123d_,$t3123e_,$t3123f_,$t3122a_,$t3122b_,$t3121a_,$t3121b_,$t3122c_,$t3122d_,$dm_,$line1_;
	
	function total(){
			$query="SELECT FILL_INDICATE.FDM_LOT_NO, Fill_Flow_Chart.flow, PRODUCT_DATA.PDD_PROD_SHORT_NAME, FILL_INDICATE.FID_FILL_BEGIN_DATE, FILL_INDICATE.FID_QTY, PRODUCT_DATA.PDD_TYPE, SUBSTRING(FILL_INDICATE.FDM_LOT_NO, 8, 2) AS lorry_type FROM FILL_INDICATE INNER JOIN
                            PRODUCT_DATA ON FILL_INDICATE.PDD_PROD_NO = PRODUCT_DATA.PDD_PROD_NO LEFT OUTER JOIN
                            Fill_Flow_Chart ON FILL_INDICATE.FDM_LOT_NO = Fill_Flow_Chart.Lot_No 
WHERE           (PRODUCT_DATA.PDD_CHEMICAL = 'H2SO4') AND (FILL_INDICATE.FID_FILL_BEGIN_DATE like '".$this->date."%')";

			$result=mssql_query($query);
			while($row=mssql_fetch_array($result)){
				$flow=$row['flow'];
				if($row['lorry_type']=='L6'){$weight=10000 ;}
				elseif($row['lorry_type']=='L0'){$weight=20000 ;}
				elseif($row['lorry_type']=='L1'){$weight=1800 ;}
				if($row['PDD_TYPE']<>'DM')
				{
					
					//一系		
					$flow=substr(trim($flow),0,12);	
					if(strpos($flow,"T-0151")==TRUE){
					//	echo "#".$flow."#<BR>";	
						$this->line1=$this->line1+$row['FID_QTY']*1.841;	
						$this->line1_=$this->line1_+$weight;
					}
					
					// low level 96%
					// T-2121B			
					
					if(substr(trim($flow),0,12)=="二系/T-2121A"){
						$T2121A=$T2121A+$row['FID_QTY']*1.841;	
						$L96=$L96+$row['FID_QTY']*1.841;	
						$this->total_low_=$this->total_low_+$weight;
				//		echo "T-2121A:".$row['FID_QTY']."weight:".$L96."<BR>";
					}
					if(substr(trim($flow),0,12)=="二系/T-2121B"){
						$T2121B=$T2121B+$row['FID_QTY']*1.841;	
						$L96=$L96+$row['FID_QTY']*1.841;	
						$this->total_low_=$this->total_low_+$weight;
			//			echo "T-2121A<BR>";
					}
					// T-3123C
					if(substr(trim($flow),0,12)=="二系/T-3123C"){
						$T3123C=$T3123C+$row['FID_QTY']*1.841;
						$L96=$L96+$row['FID_QTY']*1.841;	
						$this->total_low_=$this->total_low_+$weight;	
			//			echo "T-3123C<BR>";
					}
					// T-3123D
					if(substr(trim($flow),0,12)=="二系/T-3123D"){
						$T3123D=$T3123D+$row['FID_QTY']*1.841;	
						$L96=$L96+$row['FID_QTY']*1.841;	
						$this->total_low_=$this->total_low_+$weight;
			//			echo "T-3123D<BR>";
					}
					// T-3123E
					if(substr(trim($flow),0,12)=="二系/T-3123E"){
						$T3123E=$T3123E+$row['FID_QTY']*1.841;	
						$L96=$L96+$row['FID_QTY']*1.841;	
						$this->total_low_=$this->total_low_+$weight;
			//			echo "T-3123E<BR>";
					}
					// T-3123F
					if(substr(trim($flow),0,12)=="二系/T-3123F"){
						$T3123F=$T3123F+$row['FID_QTY']*1.841;	
						$L96=$L96+$row['FID_QTY']*1.841;	
						$this->total_low_=$this->total_low_+$weight;
			//			echo "T-3123F<BR>";
					}
					// T-3122A
					if(substr(trim($flow),0,12)=="二系/T-3122A"){
						$T3122A=$T3122A+$row['FID_QTY']*1.841;	
						$L96=$L96+$row['FID_QTY']*1.841;	
						$this->total_low_=$this->total_low_+$weight;
			//			echo "T-3122A<BR>";
					}
					// T-3122B
					if(substr(trim($flow),0,12)=="二系/T-3122B"){
						$T3122B=$T3122B+$row['FID_QTY']*1.841;	
						$L96=$L96+$row['FID_QTY']*1.841;	
						$this->total_low_=$this->total_low_+$weight;
			//			echo "T-3122B<BR>";
					}
					
					// high level 96%
					// T-3121A
					if(substr(trim($flow),0,12)=="三系/T-3121A"){
						$T3121A=$T3121A+$row['FID_QTY']*1.841;	
						$H96=$H96+$row['FID_QTY']*1.841;	
						$this->total_high_=$this->total_high_+$weight;
					}
					// T-3121B
					if(substr(trim($flow),0,12)=="三系/T-3121B"){
						$T3121B=$T3121B+$row['FID_QTY']*1.841;	
						$H96=$H96+$row['FID_QTY']*1.841;	
						$this->total_high_=$this->total_high_+$weight;
					}
					// T-3122A
					if(substr(trim($flow),0,12)=="三系/T-3122A"){
						$T3122C=$T3122C+$row['FID_QTY']*1.841;	
						$H96=$H96+$row['FID_QTY']*1.841;	
						$this->total_high_=$this->total_high_+$weight;
				//		echo "T-3122A:".$row['FID_QTY']."weight:".$H96."<BR>";
					}
					// T-3122B
					if(substr(trim($flow),0,12)=="三系/T-3122B"){
						$T3122D=$T3122D+$row['FID_QTY']*1.841;	
						$H96=$H96+$row['FID_QTY']*1.841;	
						$this->total_high_=$this->total_high_+$weight;
					}
					if(substr(trim($flow),0,12)=="三系/T-3122C"){
						$T3122C=$T3122C+$row['FID_QTY']*1.841;	
						$H96=$H96+$row['FID_QTY']*1.841;	
						$this->total_high_=$this->total_high_+$weight;
					}
					// T-2121B
					if(substr(trim($flow),0,12)=="三系/T-3122D"){
						$T3122D=$T3122D+$row['FID_QTY']*1.841;	
						$H96=$H96+$row['FID_QTY']*1.841;	
						$this->total_high_=$this->total_high_+$weight;
					}
				}
				else{
					$this->dm=$this->dm+$row['FID_QTY']*1.841;
				}
				$this->total_low=$L96;
				$this->total_high=$H96;
				$this->t2121b=$T2121B;
				$this->t3121a=$T3121A;
				$this->t3121b=$T3121B;
				$this->t3122a=$T3122A;
				$this->t3122b=$T3122B;
				$this->t3122c=$T3122C;
				$this->t3122d=$T3122D;
				$this->t3123c=$T3123C;
				$this->t3123d=$T3123D;
				$this->t3123e=$T3123E;
				$this->t3123f=$T3123F;
				$this->total_1=$this->dm+$this->line1;
			}
		
	}	
}
if(isset($_POST["print3"]))
{	
	$query="SELECT  FILL_INDICATE.FDM_LOT_NO, Fill_Flow_Chart.flow, PRODUCT_DATA.PDD_PROD_SHORT_NAME, FILL_INDICATE.FID_FILL_BEGIN_DATE, FILL_INDICATE.FID_QTY FROM FILL_INDICATE INNER JOIN PRODUCT_DATA ON FILL_INDICATE.PDD_PROD_NO = PRODUCT_DATA.PDD_PROD_NO LEFT OUTER JOIN Fill_Flow_Chart ON FILL_INDICATE.FDM_LOT_NO = Fill_Flow_Chart.Lot_No
			WHERE          (FILL_INDICATE.FID_FILL_BEGIN_DATE > '".dod($_POST['datepicker1'])."000000') AND (FILL_INDICATE.FID_FILL_BEGIN_DATE < '".dod($_POST['datepicker2'])."235959') AND (PRODUCT_DATA.PDD_CHEMICAL = 'H2SO4') order by FID_FILL_BEGIN_DATE ";

	echo '<table border="1">';
	echo '<tr><td>Lot No.</td><td>FLOWS</td><td>藥品簡稱</td><td>充填日期</td><td>充填量</td></tr>';
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		echo '<tr><td>'.$row[0].'</td><td>'.$row[1].'</td><td>'.$row[2].'</td><td>'.std($row[3]).'</td><td>'.$row[4].'</td></tr>';
	}
	echo "</table><BR><BR>";
}


if(isset($_POST["print4"]))
{
	$path_root=$_SERVER['HTTP_HOST'];
	$objPHPExcel = new PHPExcel();
	ini_set("memory_limit", "128M"); 
	$objPHPExcel = PHPExcel_IOFactory::load("lot_list.xlsx");
	$objPHPExcel->setActiveSheetIndex(0);
	$query="SELECT  FILL_INDICATE.FDM_LOT_NO, Fill_Flow_Chart.flow, PRODUCT_DATA.PDD_PROD_SHORT_NAME, FILL_INDICATE.FID_FILL_BEGIN_DATE, FILL_INDICATE.FID_QTY FROM FILL_INDICATE INNER JOIN PRODUCT_DATA ON FILL_INDICATE.PDD_PROD_NO = PRODUCT_DATA.PDD_PROD_NO LEFT OUTER JOIN Fill_Flow_Chart ON FILL_INDICATE.FDM_LOT_NO = Fill_Flow_Chart.Lot_No
			WHERE          (FILL_INDICATE.FID_FILL_BEGIN_DATE > '".dod($_POST['datepicker1'])."000000') AND (FILL_INDICATE.FID_FILL_BEGIN_DATE < '".dod($_POST['datepicker2'])."235959') AND (PRODUCT_DATA.PDD_CHEMICAL = 'H2SO4') order by FID_FILL_BEGIN_DATE ";

	$i=0;
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result))
	{
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow(0,$i+2,$row[0]);	
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow(1,$i+2,mb_convert_encoding($row[1],"utf-8","big5"));	
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow(2,$i+2,$row[2]);	
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow(3,$i+2,std($row[3]));	
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow(4,$i+2,$row[4]);		
		$i++;
	}	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
	$objWriter->save('find3.xlsx');
	echo '<script>document.location.href="http://'.$path_root.'/PRODUCE/find3.xlsx";</script>';
}


function date_range($first, $last, $step = '+1 day', $format = 'Y-m-d')
{
    $dates   = array();
    $current = strtotime($first);
    $last    = strtotime($last);

    while ($current <= $last) {
        $dates[] = date($format, $current);
        $current = strtotime($step, $current);
    }

    return $dates;
}

// test: print_r(date_range('2014-06-22', '2014-07-02'));
function border_($p,$start,$end){
//	echo "#".$start."#".$end."#".$p."#";
	for($s=$start;$s<=$end;$s++){
		$objPHPExcel->setActiveSheetIndex()->getStyle("'".$p.$s."'")->applyFromArray($styleThinBlackBorderOutline);
		/*
		$objPHPExcel->setActiveSheetIndex()->getStyle($p.$s)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex()->getStyle($p.$s)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		*/
	}
}

function get_daily_value($date,$class){
	$query="SELECT TOP (1) produce_qty FROM produce_daily WHERE ([date] = N'".$date."') AND (class = ".$class.") ORDER BY   createtime DESC";	
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	return $row[0];
}

function get_hic01_value($date){
	$a=0;
	$query="SELECT  AND_LOT_NO FROM AnalyzeDesign WHERE (AND_REPORT_DATETIME >= '".$date."000000') AND (AND_GOODS = 'TP00601') AND (AND_REPORT_DATETIME <= '".$date."999999')  AND (AND_LOT_NO LIKE '%HIC01%')";
//	echo $query."<BR>";
	$result=mssql_query($query);		
	while($rows=mssql_fetch_array($result)){
		$a=$a+20000;	
	}
	return $a;
}

function get_hic02_value($date){
	$b=0;
	$query="SELECT  AND_LOT_NO FROM AnalyzeDesign WHERE (AND_REPORT_DATETIME >= '".$date."000000') AND (AND_GOODS = 'TP00601') AND (AND_REPORT_DATETIME <= '".$date."999999')  AND (AND_LOT_NO LIKE '%HIC02%')";
	$result=mssql_query($query);		
	while($rows=mssql_fetch_array($result)){
		$b=$b+20000;	
	}
	return $b;
}