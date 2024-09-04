<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
session_start();
$_SESSION['lasturl']=$_SERVER['REQUEST_URI'];
include("../connections/conn.php");
	include("../lib/fun.php");
	include("../lib/jtsai.php");
	auth('2-11',$_SESSION['aut']);
	lasturl();
	datepick();
	$path_root=$_SERVER['HTTP_HOST'];
//	remurl("urln1");
?>

<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
<table width="1240" border="1">
<tr><td>品名：<span class="d1">
      <input type="button" name="X3" id="X3" value="X" onclick="window.open('../erase_prod.php ', '_self');" />
      <input name="pid" type="text" id="pid" size="10" value="<?php 
		if($_SESSION['pid']){
			echo $_SESSION['pid'];
		}
		else{
		echo '';
		}
		?>" readonly="readonly" />
      <input type="button" name="pdd_no2" id="pdd_no2" value="查詢品名" onclick="window.open('../main.php?url=pdd_prod_no ', '_self');" />
      <input name="pdd_chemical3" type="text" id="pdd_chemical4" size="16" value="<?php 
		if($_SESSION['pid']){
			echo get_prod_name($_SESSION['pid']);
		}
		else{
		echo '';
		}
		?>" readonly="readonly" />
        
  
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PUMP Name : <?php get_pump($_SESSION['pid']);?>
        
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Filter Name : <?php get_filter($_SESSION['pid']);?>
        
        
</td></tr>
<tr>
<td>充填日期：
      		<input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php echo $_SESSION['datepicker1'] ; ?>"  onchange="set_date_session(this.name,this.value)">
        ~
        <input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php echo $_SESSION['datepicker2'] ; ?>"  onchange="set_date_session(this.name,this.value)"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Lot NO: 
        <input type="text" name="lotno" value="<?php echo $_SESSION['lotno'] ; ?>"   onchange="set_date_session(this.name,this.value)"/>
<label for="dec_no1"></label>
		<input name="search" type="submit" class="ui-button-text-icons" id="search" value="	查	詢	  "  />
        <input name="excel" type="submit" class="ui-button-text-icons" id="search2" value="	EXCEL	  "  /></td></tr></form>

<?php

echo '<table width="1024" border="1"><tr bgcolor="#CCCCCC"><td>Lot No</td><td>充填日期</td><td>Pump 1</td><td>Pump 2</td><td>Filter 1</td><td>Filter 2</td><td>Filter 3</td></tr>';
if(isset($_POST['search'])){
	$query="SELECT DISTINCT Fill_Flow_Chart.Lot_No, AnalyzeDesign.AND_GOODS FROM Fill_Flow_Chart INNER JOIN AnalyzeDesign ON Fill_Flow_Chart.Lot_No = AnalyzeDesign.AND_LOT_NO 
	WHERE (AND_GOODS = N'".$_SESSION['pid']."')";
	
	if($_POST['pump']<>'' and $_POST['filter']<>''){
		$query.=" and (flow like '%".$_POST['pump']."/%' and flow like '%".$_POST['filter']."/%' )";
	}
	elseif($_POST['pump']<>''){
		$query.=" and (flow like '%".$_POST['pump']."/%')";
	}
	elseif($_POST['filter']<>''){
		$query.=" and (flow like '%".$_POST['filter']."/%' )";
	}
	$query.=" and date>='".dod($_POST['datepicker1'])."0000' and date <='".dod($_POST['datepicker2'])."235959'";
	$query.=" order by Lot_No";
//	echo $query;
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		$aa=new pre_flo;
		$aa->lotno=$row['Lot_No'];
		$aa->get();
		$aa->pre;
		
		$flowa=explode("/",$aa->flo);
		$pressurea=explode("/",$aa->pre);
		$n=count($flowa);
		$x=$y=1;
		for($i=0;$i<$n;$i++){
			if(substr(trim($flowa[$i]),0,1)=='P'){$pu[$x]=$pressurea[$i];$pu_[$x]=trim($flowa[$i]);$x++;}
			if(substr(trim($flowa[$i]),0,1)=='S'){$fi[$y]=$pressurea[$i];$fi_[$y]=trim($flowa[$i]);$y++;}
		}
		
		echo '<tr><td>'.$row['Lot_No'].'</td><td>'.sta($aa->dat).'</td><td>'.$pu_[1]." : ".$pu[1].'</td><td>'.$pu_[2]." : ".$pu[2].'</td><td>'.$fi_[1]." : ".$fi[1].'</td><td>'.$fi_[2]." : ".$fi[2].'</td><td>'.$fi_[3]." : ".$fi[3].'</td></tr>';	
	unset($pu_,$pu,$fi,$fi_);
	}
}

if(isset($_POST['excel'])){
	include_once "../PHPEXCEL/Classes/PHPExcel.php";
	include_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
	$objPHPExcel = new PHPExcel();
	$fl="DIF_Pressure.xlsx";
	$objPHPExcel = PHPExcel_IOFactory::load($fl);
	$obj=$objPHPExcel->setActiveSheetIndex(0);
	
	
	$query="SELECT DISTINCT Fill_Flow_Chart.Lot_No, AnalyzeDesign.AND_GOODS FROM Fill_Flow_Chart INNER JOIN AnalyzeDesign ON Fill_Flow_Chart.Lot_No = AnalyzeDesign.AND_LOT_NO 
	WHERE (AND_GOODS = N'".$_SESSION['pid']."')";
	
	if($_POST['pump']<>'' and $_POST['filter']<>''){
		$query.=" and ((flow like '%".$_POST['pump']."/%' and flow like '%".$_POST['filter']."/%'))";
	}
	elseif($_POST['pump']<>''){
		$query.=" and (flow like '%".$_POST['pump']."/%')";
	}
	elseif($_POST['filter']<>''){
		$query.=" and (flow like '%".$_POST['filter']."/%' )";
	}
	$query.=" and date>='".dod($_POST['datepicker1'])."0000' and date <='".dod($_POST['datepicker2'])."235959'";
	$query.=" order by Lot_No";
	$z=3;
//	echo $query;
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		$aa=new pre_flo;
		$aa->lotno=$row['Lot_No'];
		$aa->get();
		$aa->flo;
		$flowa=explode("/",$aa->flo);
		$pressurea=explode("/",$aa->pre);
		$n=count($flowa);
		$x=$y=1;
		$obj
							->setCellValue("B".$z,iconv('big5','utf-8',$row['Lot_No']))
							->setCellValue("C".$z,iconv('big5','utf-8',$aa->dat))	;				
							

		
		for($i=0;$i<$n;$i++){
			if(substr(trim($flowa[$i]),0,1)=='P'){$pu[$x]=$pressurea[$i];$pu_[$x]=trim($flowa[$i]);$x++;}
			if(substr(trim($flowa[$i]),0,1)=='S'){$fi[$y]=$pressurea[$i];$fi_[$y]=trim($flowa[$i]);$y++;}
		}
		
		$obj
							->setCellValue("D".$z,iconv('big5','utf-8',$pu_[1]." : ".$pu[1]))
							->setCellValue("E".$z,iconv('big5','utf-8',$fi_[1]." : ".$fi[1]))
							->setCellValue("F".$z,iconv('big5','utf-8',$fi_[2]." : ".$fi[2]))
							->setCellValue("G".$z,iconv('big5','utf-8',$pu[1]))
							->setCellValue("H".$z,iconv('big5','utf-8',$fi[1]))
							->setCellValue("I".$z,iconv('big5','utf-8',$pu[1]-$fi[1]))
							->setCellValue("J".$z,iconv('big5','utf-8',$fi[2]))	
							->setCellValue("k".$z,iconv('big5','utf-8',$fi[1]-$fi[2]))
							;
		$c=$z;
		$z++;
	unset($pu_,$pu,$fi,$fi_);
	}
	$obj->getStyle('B2:M'.$c)->getBorders()->getAllborders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);	
	
	$fileurl="./formlist/tmp1/tmp1";
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
	$objWriter->save($fileurl.".xlsx");
	echo '<script>document.location.href="http://'.$path_root."/fill/".$fileurl.'.xlsx";</script>';			

	
}



echo '</table><BR>';
function get_pump($pid){
	echo ' <select name="pump" id="pump" onchange="set_date_session(this.name,this.value)"><option value="'.$_SESSION['pump'].'">'.$_SESSION['pump'].'</option><option value=""></option>';
	$query="SELECT PumpNo FROM Fill_Pump WHERE (Prod_No = N'".$pid."')";	
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
    	echo '<option value="'.trim($row['PumpNo']).'">'.trim($row['PumpNo']).'</option>';
	}
  	echo '</select>';
}

function get_filter($pid){
	echo ' <select name="filter" id="filter" onchange="set_date_session(this.name,this.value)"><option value="'.$_SESSION['filter'].'">'.$_SESSION['filter'].'</option><option value=""></option>';
	$query="SELECT filter FROM Fill_Filter WHERE (Prod_No = N'".$pid."')";	
	echo $query;
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
    	echo '<option value="'.trim($row['filter']).'">'.trim($row['filter']).'</option>';
	}
  	echo '</select>';
}

class pre_flo{
	public $pre,$flo,$lotno,$dat;
	function get(){
		$query="SELECT TOP (1)  pressure ,flow, date FROM Fill_Flow_Chart WHERE (Lot_No = N'".$this->lotno."') ORDER BY   date DESC";	
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);
		$this->pre=$row[0];
		$this->flo=$row[1];
		$this->dat=$row[2];
	}
}
?>