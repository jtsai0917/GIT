<meta http-equiv="Content-Type" content="text/html; charset=big5" />

<?php 

class tble{
public $tbname;
public $lot_no,$shortlot;
public $pdd_chemical,$pdd_prod_name,$pdd_prod_short_name;
public $ani_groupname,$ps;
public $pdd_type,$pdd_box;
public $ani_index,$AnalyzeTime;

function print_table(){
require_once("../lib/fun.php");
require_once("../lib/jtsai.php");
require_once("../connections/conn.php");
$fileurl="../tmp1/TI".$this->lot_no;
if($this->tbname==''){
$this->tbname=get_table($this->ani_groupname,$this->pdd_chemical);
}
echo $this->tbname."</br>";
$query="SELECT DISTINCT 
                          *, CONVERT(char(10), TestDate, 20) AS TestDate
FROM             dbo.".$this->tbname."
WHERE          (LotNo  = '".$this->lot_no."') and (AnalyzeTime='".$this->AnalyzeTime."') order by AnalyzeTime ";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
if ($numRows>0)
{	
	$ss=new analyze_first;
	$ss->lot_no=$this->lot_no;
	$ss->sample_no=$row['SampleNo'];
	$ss->create_time=$this->AnalyzeTime;
	$ss->get();
	$_SESSION['FIRST00000000']=get_uname($ss->first);
	$shortlot=substr($ss->lot_no,0,6);
	$lastlot=substr($ss->lot_no,-4);
	if($lastlot=='A001'){$samplename='TZ104入口';$spec='>3.2';}
	if($lastlot=='B001'){$samplename='中間 TANK';$spec='>3.2';}
	if($shortlot=='TO1MXA'){$samplename='MX(A)入口';$spec='>3.2';}
	if($shortlot=='TO1MXB'){$samplename='MX(B)入口';$spec='>3.2';}
	if($shortlot=='TO1MXC'){$samplename='MX(C)入口';$spec='>3.2';}
	
	$i=0;
	require_once ('../PHPWord/PHPWord.php');
	$PHPWord = new PHPWord();
	if(($this->pdd_chemical=='H2O') and ($_SESSION['anigroup']=='COD'))
	{
		$tname='TLWSQA9110101A1';
	}
	else
	{
		$tname=$this->tbname;	
	}
	$document = $PHPWord->loadTemplate('../FormList/PrintAnalyze/'.$this->pdd_chemical.'/'.$tname.'.docx');
	$type=$this->pdd_type;
	$document->setValue("type", $this->pdd_type);
	$document->setValue("pdd_type0", $type);
	$document->setValue("pdd_prod_short_name",$this->pdd_prod_short_name);
	$document->setValue("pdd_prod_name",$this->pdd_prod_name);	
	$document->setValue("chemical",$_GET['pdd_chemical']);	
	$document->setValue("pdd_box0",$this->pdd_box);
	$document->setValue("ps0",$this->ps);
	$document->setValue("first0",get_uname($ss->first));
	$document->setValue("samplename0",$samplename);
	$document->setValue("spec0",$spec);
	while($row = mssql_fetch_array($result))
	{
		if($row['Ok']=='1'){$row['Ok']='合';}
		if($row['Ok']=='0'){$row['Ok']='否';}
		$document->setValue("dno".$i,get_sample_drum_no($row['SampleNo'],$this->lot_no));
		$query1="SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$this->tbname."')";
		$result1 = mssql_query($query1);
		while($row1 = mssql_fetch_array($result1))
		{
			$rowname=$row1['COLUMN_NAME'];
			if($row1['DATA_TYPE']=='float')
			{
				$value=$row[$rowname];
				if(($value<>'1.0E-6') and ($value<>'0.000001') and ($value<>'')  and ($value<>'NULL'))
					{
						if(($this->ani_groupname=="RAI") or ($rowname=="Factor1") or ($rowname=="Factor2") or (($this->ani_groupname=="SRP") and ($rowname=="F")) )
						{
							$value=$value;}else{$value=round($value,3);
						}   //  (不須四捨五入)
					}
				if($value=='0'){$value='0';}
				if($value=='1.0E-6'){$value='0.000001';}
				if($value==''){$value='NULL';}	
			}
			else{$value=$row[$rowname];}
			if (($rowname=="CHK1") or ($rowname=="CHK2") or ($rowname=="CHK3") or ($rowname=="CHK4")){$value="V";}
			if ($rowname=="As"){$rowname="AAS";}
			if($rowname=='TestDate'){$value="西曆 ".substr($row[$rowname],0,4)." 年 ".substr($row[$rowname],5,2)." 月 ".substr($row[$rowname],8,2)." 日";}	
			if($rowname=='AnalyzeTime'){$value="西曆 ".substr($row[$rowname],0,4)." 年 ".substr($row[$rowname],4,2)." 月 ".substr($row[$rowname],6,2)." 日";}	
			$document->setValue($rowname.$i,$value);	
			$_SESSION[$rowname.$i]=$value;		
		}
		$document->setValue("pdd_box".$i,$this->pdd_box);
		$i++;
		echo "</br></br></br>";
	}
		
		if ($i=1){
		$document->setValue('first',get_uname(get_first($this->lot_no,$row['SampleNo'],$this->tbname)));
		$document->setValue("LotNo0",$this->lot_no);
		$document->setValue("pdd_type".$i, "");
		$document->setValue("dno".$i,"");
		$document->setValue("AAS".$i,"");
		$query1="SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$this->tbname."')";
		$result1 = mssql_query($query1);
		while($row1 = mssql_fetch_array($result1)){
			$rowname=$row1['COLUMN_NAME'];
			$value=$row[$rowname];
				if(($value<>'1.0E-6') and ($value<>'0.000001') and ($value<>'')  and ($value<>'NULL'))
					{
						if(($this->ani_groupname=="RAI") or ($rowname=="Factor1") or ($rowname=="Factor2") or (($this->ani_groupname=="SRP") and ($rowname=="F")) )
						{
							$value=$value;}else{$value=round($value,3);
						}   //  (不須四捨五入)
					}
			$document->setValue($rowname.$i, "");		
			}
		}
		if ($i=2){
		$document->setValue('first',get_uname(get_first($this->lot_no,$row['SampleNo'],$this->tbname)));
		$document->setValue("pdd_type".$i, "");
		$document->setValue("dno".$i,"");
		
		$document->setValue("AAS".$i,"");
		$query1="SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$this->tbname."')";
		$result1 = mssql_query($query1);
		$numRows = mssql_num_rows($result1);
		while($row1 = mssql_fetch_array($result1)){
			$rowname=$row1['COLUMN_NAME'];
			$value=$row[$rowname];
				if(($value<>'1.0E-6') and ($value<>'0.000001') and ($value<>'')  and ($value<>'NULL'))
					{
						if(($this->ani_groupname=="RAI") or ($rowname=="Factor1") or ($rowname=="Factor2") or (($this->ani_groupname=="SRP") and ($rowname=="F")) )
						{
							$value=$value;}else{$value=round($value,3);
						}   //  (不須四捨五入)
					}
			$document->setValue($rowname.$i, "");		
			}
		}
		if ($i=3){
		$document->setValue('first',get_uname(get_first($this->lot_no,$row['SampleNo'],$this->tbname)));
		$document->setValue("pdd_type".$i, "");
		$document->setValue("dno".$i,"");
		$document->setValue("AAS".$i,"");
		$query1="SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$this->tbname."')";
		$result1 = mssql_query($query1);
		$numRows = mssql_num_rows($result1);
		if($numRows>0){
		while($row1 = mssql_fetch_array($result1)){
			$rowname=$row1['COLUMN_NAME'];
			$value=$row[$rowname];
				if(($value<>'1.0E-6') and ($value<>'0.000001') and ($value<>'')  and ($value<>'NULL'))
					{
						if(($this->ani_groupname=="RAI") or ($rowname=="Factor1") or ($rowname=="Factor2") or (($this->ani_groupname=="SRP") and ($rowname=="F")) )
						{
							$value=$value;}else{$value=round($value,3);
						}   //  (不須四捨五入)
					}
			$document->setValue($rowname.$i, "");		
			}
			}
		}
		
		if ($i=4){
		$document->setValue('first',get_uname(get_first($this->lot_no,$row['SampleNo'],$this->tbname)));
		$document->setValue("pdd_type".$i, "");
		$document->setValue("dno".$i,"");
		$document->setValue("AAS".$i,"");
		$query1="SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$this->tbname."')";
		$result1 = mssql_query($query1);
		$numRows = mssql_num_rows($result1);
		if($numRows>0){
		while($row1 = mssql_fetch_array($result1)){
			$rowname=$row1['COLUMN_NAME'];
			$value=$row[$rowname];
				if(($value<>'1.0E-6') and ($value<>'0.000001') and ($value<>'')  and ($value<>'NULL'))
					{
						if(($this->ani_groupname=="RAI") or ($rowname=="Factor1") or ($rowname=="Factor2") or (($this->ani_groupname=="SRP") and ($rowname=="F")) )
						{
							$value=$value;}else{$value=round($value,3);
						}   //  (不須四捨五入)
					}
			$document->setValue($rowname.$i, "");		
			}
			}
		}	
}
//$document->setValue('TestDate0',$dst);
$document->setValue("LotNo0",$this->lot_no);
$document->save('../tmp2/ani_result'.$this->tbname.'.docx');
echo '<script>document.location.href="http://'.$_SERVER['HTTP_HOST'].'/tmp2/ani_result'.$this->tbname.'.docx";</script>';
}

function print_metal()
{
$this->tbname='TLUQA9100346';
require_once("../lib/fun.php");
require_once("../lib/jtsai.php");
require_once("../connections/conn.php");
require_once ('../PHPWord/PHPWord.php');
	$PHPWord = new PHPWord();
	$document = $PHPWord->loadTemplate('../FormList/PrintAnalyze/'.$this->pdd_chemical.'/'.$this->tbname.'.docx');	
$fileurl="../tmp1/TI".$this->lot_no;
$m1=get_table("M13",$this->pdd_chemical);
$m2=get_table("M21",$this->pdd_chemical);
$m3=get_table("TT_Si",$this->pdd_chemical);
$m4=get_table("TT_B",$this->pdd_chemical);
$m5=get_table("TT",$this->pdd_chemical);
echo $this->tbname."</br>";
$query="SELECT DISTINCT 
                          dbo.".$m1.".*
FROM             dbo.".$m1." 
WHERE         (dbo.".$m1.".LotNo = '".$this->lot_no."') ORDER BY  AnalyzeTime DESC";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
if ($numRows>0)
{	
	$ss=new analyze_first;
	$ss->lot_no=$this->lot_no;
	$ss->get();
	$i=0;
	$document->setValue("first0",get_uname($ss->first));
	while($row = mssql_fetch_array($result))
	{
		$document->setValue("Tester0",$row['Tester']);
		$query1="SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$m1."')";
		$result1 = mssql_query($query1);
		while($row1 = mssql_fetch_array($result1)){
			$rowname=$row1['COLUMN_NAME'];
			if($row1['DATA_TYPE']=='float'){
				$value=$row[$rowname];
				if($value=='0'){$value='0';}
				if($value=='1.0E-6'){$value='0.000001';}
				if($value==''){$value='NULL';}		
			}
			else{$value=$row[$rowname];}
			if (($rowname=="CHK1") or ($rowname=="CHK2") or ($rowname=="CHK3") or ($rowname=="CHK4")){$value="V";}
			if ($rowname=="As"){$rowname="AAS";}
			if($rowname=='TestDate'){$value="西曆 ".substr($row[$rowname],0,4)." 年 ".substr($row[$rowname],5,2)." 月 ".substr($row[$rowname],8,2)." 日";}	
			if($rowname=='AnalyzeTime'){$value="西曆 ".substr($row[$rowname],0,4)." 年 ".substr($row[$rowname],4,2)." 月 ".substr($row[$rowname],6,2)." 日";}	
			if($value<>''){$document->setValue($rowname.$i,$value);}	
			else{$document->setValue($rowname.$i,'---');}	
		}
		$i++;
	}
}
else
{	
	$ss=new analyze_first;
	$ss->lot_no=$this->lot_no;
	$ss->get();
	$i=0;
	$document->setValue("first0",get_uname($ss->first));
		$document->setValue("Tester0",$row['Tester']);
		$query1="SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$m1."')";
		$result1 = mssql_query($query1);
		while($row1 = mssql_fetch_array($result1)){
			$rowname=$row1['COLUMN_NAME'];
			if (($rowname=="CHK1") or ($rowname=="CHK2") or ($rowname=="CHK3") or ($rowname=="CHK4")){$value="V";}
			if ($rowname=="As"){$rowname="AAS";}
			if($rowname=='TestDate'){$value="西曆 ".substr($row[$rowname],0,4)." 年 ".substr($row[$rowname],5,2)." 月 ".substr($row[$rowname],8,2)." 日";}	
			if($rowname=='AnalyzeTime'){$value="西曆 ".substr($row[$rowname],0,4)." 年 ".substr($row[$rowname],4,2)." 月 ".substr($row[$rowname],6,2)." 日";}		
			$document->setValue($rowname.$i,'- - -');
		}
		$i++;
}

$query="SELECT DISTINCT 
                          dbo.".$m2.".*
FROM             dbo.".$m2." 
WHERE         ((dbo.".$m2.".LotNo = '".$this->lot_no."') and (dbo.".$m2.".Li IS NOT NULL)) ORDER BY  AnalyzeTime DESC";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
if ($numRows>0)
{	
	$ss=new analyze_first;
	$ss->lot_no=$this->lot_no;
	$ss->get();
	$i=0;
	$document->setValue("first1",get_uname($ss->first));
	while($row = mssql_fetch_array($result))
	{
		$document->setValue("Tester1",$row['Tester']);
		$query1="SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$m2."')";
		$result1 = mssql_query($query1);
		while($row1 = mssql_fetch_array($result1)){
			$rowname=$row1['COLUMN_NAME'];
			if($row1['DATA_TYPE']=='float'){
				$value=$row[$rowname];
				if($value=='0'){$value='0';}
				if($value=='1.0E-6'){$value='0.000001';}
				if($value==''){$value='NULL';}			
			}
			else{$value=$row[$rowname];}
			if ($rowname=="As"){$rowname="AAS";}
			if($value<>''){$document->setValue($rowname.$i,$value);}			
		}
		$i++;
	}
}
else
{	
	$ss=new analyze_first;
	$ss->lot_no=$this->lot_no;
	$ss->get();
	$i=0;
	$document->setValue("first1",get_uname($ss->first));
		$document->setValue("Tester1",$row['Tester']);
		$query1="SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$m2."')";
		$result1 = mssql_query($query1);
		while($row1 = mssql_fetch_array($result1)){
			$rowname=$row1['COLUMN_NAME'];
			if (($rowname=="CHK1") or ($rowname=="CHK2") or ($rowname=="CHK3") or ($rowname=="CHK4")){$value="V";}
			if ($rowname=="As"){$rowname="AAS";}
			if($rowname=='TestDate'){$value="西曆 ".substr($row[$rowname],0,4)." 年 ".substr($row[$rowname],5,2)." 月 ".substr($row[$rowname],8,2)." 日";}	
			if($rowname=='AnalyzeTime'){$value="西曆 ".substr($row[$rowname],0,4)." 年 ".substr($row[$rowname],4,2)." 月 ".substr($row[$rowname],6,2)." 日";}		
			$document->setValue($rowname.$i,'---');
		}
		$i++;
}

$query="SELECT DISTINCT 
                          dbo.".$m3.".*
FROM             dbo.".$m3." 
WHERE         (dbo.".$m3.".LotNo = '".$this->lot_no."') ORDER BY  AnalyzeTime DESC";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
if ($numRows>0)
{	
	$ss=new analyze_first;
	$ss->lot_no=$this->lot_no;
	$ss->get();
	$i=0;
	$document->setValue("first3",get_uname($ss->first));
	while($row = mssql_fetch_array($result))
	{
		$document->setValue("Tester3",$row['Tester']);
		$query1="SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$m3."')";
		$result1 = mssql_query($query1);
		while($row1 = mssql_fetch_array($result1)){
			$rowname=$row1['COLUMN_NAME'];
			if($row1['DATA_TYPE']=='float'){
				$value=$row[$rowname];
				if($value=='0'){$value='0';}
				if($value=='1.0E-6'){$value='0.000001';}
				if($value==''){$value='NULL';}		
			}
			else{$value=$row[$rowname];}
			if($value<>''){$document->setValue($rowname."3",$value);}			
		}
		$i++;
	}
}
else
{	
	$ss=new analyze_first;
	$ss->lot_no=$this->lot_no;
	$ss->get();
	$i=0;
	$document->setValue("first3",get_uname($ss->first));
		$document->setValue("Tester3",$row['Tester']);
		$query1="SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$m3."')";
		$result1 = mssql_query($query1);
		while($row1 = mssql_fetch_array($result1)){
			$rowname=$row1['COLUMN_NAME'];
			if (($rowname=="CHK1") or ($rowname=="CHK2") or ($rowname=="CHK3") or ($rowname=="CHK4")){$value="V";}
			if ($rowname=="As"){$rowname="AAS";}
			if($rowname=='TestDate'){$value="西曆 ".substr($row[$rowname],0,4)." 年 ".substr($row[$rowname],5,2)." 月 ".substr($row[$rowname],8,2)." 日";}	
			if($rowname=='AnalyzeTime'){$value="西曆 ".substr($row[$rowname],0,4)." 年 ".substr($row[$rowname],4,2)." 月 ".substr($row[$rowname],6,2)." 日";}		
			$document->setValue($rowname.$i,'---');
		}
		$i++;
}

$query="SELECT DISTINCT 
                          dbo.".$m4.".*
FROM             dbo.".$m4." 
WHERE         (dbo.".$m4.".LotNo = '".$this->lot_no."') ORDER BY  AnalyzeTime DESC";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
if ($numRows>0)
{	
	$ss=new analyze_first;
	$ss->lot_no=$this->lot_no;
	$ss->get();
	$i=0;
//	$document->setValue("first1",get_uname($ss->first));
	while($row = mssql_fetch_array($result))
	{
//		$document->setValue("Tester3",$row['Tester']);
		$query1="SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$m4."')";
		$result1 = mssql_query($query1);
		while($row1 = mssql_fetch_array($result1)){
			$rowname=$row1['COLUMN_NAME'];
			if($row1['DATA_TYPE']=='float'){
				$value=$row[$rowname];
				if($value=='0'){$value='0';}
				if($value=='1.0E-6'){$value='0.000001';}
				if($value==''){$value='NULL';}			
			}
			else{$value=$row[$rowname];}
			if($value<>''){$document->setValue($rowname.$i,$value);}			
		}
		$i++;
	}
}
else
{	
	$ss=new analyze_first;
	$ss->lot_no=$this->lot_no;
	$ss->get();
	$i=0;
	$document->setValue("first4",get_uname($ss->first));
		$document->setValue("Tester4",$row['Tester']);
		$query1="SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$m4."')";
		$result1 = mssql_query($query1);
		while($row1 = mssql_fetch_array($result1)){
			$rowname=$row1['COLUMN_NAME'];
			if (($rowname=="CHK1") or ($rowname=="CHK2") or ($rowname=="CHK3") or ($rowname=="CHK4")){$value="V";}
			if ($rowname=="As"){$rowname="AAS";}
			if($rowname=='TestDate'){$value="西曆 ".substr($row[$rowname],0,4)." 年 ".substr($row[$rowname],5,2)." 月 ".substr($row[$rowname],8,2)." 日";}	
			if($rowname=='AnalyzeTime'){$value="西曆 ".substr($row[$rowname],0,4)." 年 ".substr($row[$rowname],4,2)." 月 ".substr($row[$rowname],6,2)." 日";}		
			$document->setValue($rowname.$i,'---');
		}
		$i++;
}
$query="SELECT DISTINCT 
                          dbo.".$m5.".*
FROM             dbo.".$m5." 
WHERE         ((dbo.".$m5.".LotNo = '".$this->lot_no."') and (dbo.".$m5.".PT IS NOT NULL)) ORDER BY  AnalyzeTime DESC";
$_SESSION['tmp']=$query;
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
if ($numRows>0)
{	
	$ss=new analyze_first;
	$ss->lot_no=$this->lot_no;
	$ss->get();
	$i=0;
	$document->setValue("first2",get_uname($ss->first));
	while($row = mssql_fetch_array($result))
	{
		$document->setValue("Tester2",$row['Tester']);
		
		$query1="SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$m5."')";
		$result1 = mssql_query($query1);
		while($row1 = mssql_fetch_array($result1)){
			$rowname=$row1['COLUMN_NAME'];
			if($row1['DATA_TYPE']=='float'){
				$value=$row[$rowname];
				if($value=='0'){$value='0';}
				if($value=='1.0E-6'){$value='0.000001';}
				if($value==''){$value='NULL';}			
			}
			else{$value=$row[$rowname];}
			if($value<>''){$document->setValue($rowname.$i,$value);}			
		}
		$i++;
	}
}
else
{	
	$ss=new analyze_first;
	$ss->lot_no=$this->lot_no;
	$ss->get();
	$i=0;
	$document->setValue("first2",get_uname($ss->first));
		$document->setValue("Tester2",$row['Tester']);
		$query1="SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$m5."')";
		$result1 = mssql_query($query1);
		while($row1 = mssql_fetch_array($result1)){
			$rowname=$row1['COLUMN_NAME'];
			if (($rowname=="CHK1") or ($rowname=="CHK2") or ($rowname=="CHK3") or ($rowname=="CHK4")){$value="V";}
			if ($rowname=="As"){$rowname="AAS";}
			if($rowname=='TestDate'){$value="西曆 ".substr($row[$rowname],0,4)." 年 ".substr($row[$rowname],5,2)." 月 ".substr($row[$rowname],8,2)." 日";}	
			if($rowname=='AnalyzeTime'){$value="西曆 ".substr($row[$rowname],0,4)." 年 ".substr($row[$rowname],4,2)." 月 ".substr($row[$rowname],6,2)." 日";}		
			$document->setValue($rowname.$i,'---');
		}
		$i++;
}
//$document->setValue('TestDate0',$dst);
$document->save('../tmp2/ani_result'.$this->tbname.'.docx');
echo '<script>document.location.href="http://'.$_SERVER['HTTP_HOST'].'/tmp2/ani_result'.$this->tbname.'.docx";</script>';
}
function save_result(){
$document->save('../tmp2/ani_result'.$this->tbname.'.docx');
}
}

class prt_count{
public $lot_no;
public $pdd_chemical;
public $type;
public $sma_time;
public $sma_count;
public $report_date_time;
public $sma_id;
public $operator;	
public $tester;
public $activesheet;
public $start_a;
public $start_n;
public $table;
public $select0;
public $select1;
public $select2;
public $select3;

function prt_3_h2so4(){


	include("../lib/jtsai.php");
	include("../connections/conn.php");
	$path_root=$_SERVER['HTTP_HOST'];
	$dat=date("YmdHis");
	require_once "../PHPEXCEL/Classes/PHPExcel.php";
	require_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
	$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);	
	
	$fileurl="../tmp1/TI".$this->lot_no;
	$anyl=new anylize;
	$anyl->lot_no=$this->lot_no;
	$anyl->rcv();
	$b7="SAMPLE 取得   (  " .substr($anyl->sma_time,0,-8)."  年 ".substr($anyl->sma_time,4,-6)."  月  ".substr($anyl->sma_time,6,-4)."  日共  ".$anyl->and_total."  瓶  )";
	$b8="時間： ".substr($anyl->sma_time,8,-2)."  時  ".substr($anyl->sma_time,10)."  分";
	$v6=get_uname($anyl->and_person);
	$s8=ddt($anyl->report_time);
	/// Assay
	$tb=get_table("A",$this->pdd_chemical);
	$query="SELECT DISTINCT 
                            ".$tb.".*, Sample_All.SMA_DRUMNO, 
                            Sample_All.SMA_ID
FROM              ".$tb." INNER JOIN
                            Sample_All ON ".$tb.".LotNo = Sample_All.SMA_LOT AND 
                            ".$tb.".SampleNo = Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select0."')
ORDER BY   dbo.".$tb.".AnalyzeTime";

$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$q13=$row['SMA_ID'];
	$q14=$row['Value'];
	$tester11=$row['Tester'];
	$tt11=substr($row['AnalyzeTime'],0,-6);
}	
//  echo $query."</br>";
	$tb=get_table("M13",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select0."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$q16=$row['SMA_ID'];
	$tester12=$row['Tester'];
	$tt12=substr($row['AnalyzeTime'],0,-6);
	$q17=$row['Na'];
	$q18=$row['Mg'];
	$q19=$row['Al'];
	$q20=$row['K'];
	$q21=$row['Ca'];
	$q22=$row['Cr'];
	$q23=$row['Mn'];
	$q24=$row['Fe'];
	$q25=$row['Ni'];
	$q26=$row['Co'];
	$q27=$row['Cu'];
	$q28=$row['Zn'];
	$q29=$row['In'];
	$q30=$row['W'];
	$q31=$row['Pb'];
}
	$tb=get_table("P",$this->pdd_chemical);
	$query="SELECT DISTINCT 
                            ".$tb.".*, Sample_All.SMA_DRUMNO, 
                            Sample_All.SMA_ID
FROM              ".$tb." INNER JOIN
                            Sample_All ON ".$tb.".LotNo = Sample_All.SMA_LOT AND 
                            ".$tb.".SampleNo = Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select0."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);

$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$q33=$row['PP05'];
	$q34=$row['PP03'];
	$q35=$row['PP02'];
	$q36=$row['PP01'];
	$q32=$row['SMA_ID'];
	$tester13=$row['Tester'];
	$tt13=substr($row['AnalyzeTime'],0,-6);
}

///R2
	/// Assay
	$tb=get_table("A",$this->pdd_chemical);
	$query="SELECT DISTINCT 
                            ".$tb.".*, Sample_All.SMA_DRUMNO, 
                            Sample_All.SMA_ID
FROM              ".$tb." INNER JOIN
                            Sample_All ON ".$tb.".LotNo = Sample_All.SMA_LOT AND 
                            ".$tb.".SampleNo = Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select1."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$r13=$row['SMA_ID'];
	$r14=$row['Value'];
	$tester21=$row['Tester'];
	$tt21=substr($row['AnalyzeTime'],0,-6);
}	
//  echo $query."</br>";
	$tb=get_table("M13",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select1."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$r16=$row['SMA_ID'];
	$tester22=$row['Tester'];
	$tt22=substr($row['AnalyzeTime'],0,-6);
	$r17=$row['Na'];
	$r18=$row['Mg'];
	$r19=$row['Al'];
	$r20=$row['K'];
	$r21=$row['Ca'];
	$r22=$row['Cr'];
	$r23=$row['Mn'];
	$r24=$row['Fe'];
	$r25=$row['Ni'];
	$r26=$row['Co'];
	$r27=$row['Cu'];
	$r28=$row['Zn'];
	$r29=$row['In'];
	$r30=$row['W'];
	$r31=$row['Pb'];
}
	$tb=get_table("P",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select1."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$r33=$row['PP05'];
	$r34=$row['PP03'];
	$r35=$row['PP02'];
	$r36=$row['PP01'];
	$r32=$row['SMA_ID'];
	$tester23=$row['Tester'];
	$tt23=substr($row['AnalyzeTime'],0,-6);
}

///R3
	/// Assay
	$tb=get_table("A",$this->pdd_chemical);
	$query="SELECT DISTINCT 
                            ".$tb.".*, Sample_All.SMA_DRUMNO, 
                            Sample_All.SMA_ID
FROM              ".$tb." INNER JOIN
                            Sample_All ON ".$tb.".LotNo = Sample_All.SMA_LOT AND 
                            ".$tb.".SampleNo = Sample_All.SMA_ID
							WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select2."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$s13=$row['SMA_ID'];
	$s14=$row['Value'];
	$tester31=$row['Tester'];
	$tt31=substr($row['AnalyzeTime'],0,-6);
}	
//  echo $query."</br>";
	$tb=get_table("M13",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select2."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$s16=$row['SMA_ID'];
	$tester32=$row['Tester'];
	$tt32=substr($row['AnalyzeTime'],0,-6);
	$s17=$row['Na'];
	$s18=$row['Mg'];
	$s19=$row['Al'];
	$s20=$row['K'];
	$s21=$row['Ca'];
	$s22=$row['Cr'];
	$s23=$row['Mn'];
	$s24=$row['Fe'];
	$s25=$row['Ni'];
	$s26=$row['Co'];
	$s27=$row['Cu'];
	$s28=$row['Zn'];
	$s29=$row['In'];
	$s30=$row['W'];
	$s31=$row['Pb'];
}
	$tb=get_table("P",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select2."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$s33=$row['PP05'];
	$s34=$row['PP03'];
	$s35=$row['PP02'];
	$s36=$row['PP01'];
	$s32=$row['SMA_ID'];
	$tester33=$row['Tester'];
	$tt33=substr($row['AnalyzeTime'],0,-6);
}

/// R4
	/// Assay
	$tb=get_table("A",$this->pdd_chemical);
	$query="SELECT DISTINCT 
                            ".$tb.".*, Sample_All.SMA_DRUMNO, 
                            Sample_All.SMA_ID
FROM              ".$tb." INNER JOIN
                            Sample_All ON ".$tb.".LotNo = Sample_All.SMA_LOT AND 
                            ".$tb.".SampleNo = Sample_All.SMA_ID
							WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select3."')
ORDER BY   dbo.".$tb.".AnalyzeTime desc desc";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$t13=$row['SMA_ID'];
	$t14=$row['Value'];
	$tester41=$row['Tester'];
	$tt41=substr($row['AnalyzeTime'],0,-6);
}	
//  echo $query."</br>";
	$tb=get_table("M13",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select3."')
ORDER BY   dbo.".$tb.".AnalyzeTime desc desc";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$t16=$row['SMA_ID'];
	$tester42=$row['Tester'];
	$tt42=substr($row['AnalyzeTime'],0,-6);
	$t17=$row['Na'];
	$t18=$row['Mg'];
	$t19=$row['Al'];
	$t20=$row['K'];
	$t21=$row['Ca'];
	$t22=$row['Cr'];
	$t23=$row['Mn'];
	$t24=$row['Fe'];
	$t25=$row['Ni'];
	$t26=$row['Co'];
	$t27=$row['Cu'];
	$t28=$row['Zn'];
	$t29=$row['In'];
	$t30=$row['W'];
	$t31=$row['Pb'];
}
	$tb=get_table("P",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select3."')
ORDER BY   dbo.".$tb.".AnalyzeTime desc desc";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$t33=$row['PP05'];
	$t34=$row['PP03'];
	$t35=$row['PP02'];
	$t36=$row['PP01'];
	$t32=$row['SMA_ID'];
	$tester43=$row['Tester'];
	$tt43=substr($row['AnalyzeTime'],0,-6);
}
$w14=$tester11."\n".$tester21."\n".$tester31."\n".$tester41;
$w17=$tester12."\n".$tester22."\n".$tester32."\n".$tester42;
$w33=$tester13."\n".$tester23."\n".$tester33."\n".$tester43;

$x14=$tt11."\n".$tt21."\n".$tt31."\n".$tt41;
$x17=$tt12."\n".$tt22."\n".$tt32."\n".$tt42;
$x33=$tt13."\n".$tt23."\n".$tt33."\n".$tt43;
///                                                 H2SO4
    $objPHPExcel = new PHPExcel();
	$fl="../FormList/PrintAnalyze/anycounts.xlsx";
	$objPHPExcel = PHPExcel_IOFactory::load($fl);
	$objPHPExcel->setActiveSheetIndex($this->activesheet);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)
 							->setCellValue("D5",iconv('big5','utf-8',$this->lot_no))
	    					->setCellValue("J5",conv($this->type))
	  						->setCellValue("B7",conv($b7))
							->setCellValue("B8",conv($b8))
							->setCellValue("s8",conv($s8))
							->setCellValue('q13',$q13)
							->setCellValue('Q14',$q14)
							->setCellValue('W14',conv($w14))
							->setCellValue('X14',$x14)
							->setCellValue('q16',$q16)
							->setCellValue("q17",$q17)
							->setCellValue("Q18",$q18)
							->setCellValue("Q19",$q19)
							->setCellValue("Q20",$q20)
							->setCellValue("Q21",$q21)
							->setCellValue("Q22",$q22)
							->setCellValue("Q23",$q23)
							->setCellValue("Q24",$q24)
							->setCellValue("Q25",$q25)
							->setCellValue("Q26",$q26)
							->setCellValue("Q27",$q27)
							->setCellValue("Q28",$q28)
							->setCellValue("Q29",$q29)
							->setCellValue("Q30",$q30)
							->setCellValue("Q31",$q31)
							->setCellValue("Q32",$q32)
							->setCellValue("Q33",$q33)
							->setCellValue("Q34",$q34)
							->setCellValue("Q35",$q35)
							->setCellValue("Q36",$q36)
							->setCellValue("W17",conv($w17))
							->setCellValue("X17",$x17)
							->setCellValue("W33",conv($w33))
							->setCellValue("X33",$x33)
							
							->setCellValue('r13',$r13)
							->setCellValue('r14',$r14)
							->setCellValue('w14',conv($w14))
							->setCellValue('X14',$x14)
							->setCellValue('r16',$r16)
							->setCellValue("r17",$r17)
							->setCellValue("r18",$r18)
							->setCellValue("r19",$r19)
							->setCellValue("r20",$r20)
							->setCellValue("r21",$r21)
							->setCellValue("r22",$r22)
							->setCellValue("r23",$r23)
							->setCellValue("r24",$r24)
							->setCellValue("r25",$r25)
							->setCellValue("r26",$r26)
							->setCellValue("r27",$r27)
							->setCellValue("r28",$r28)
							->setCellValue("r29",$r29)
							->setCellValue("r30",$r30)
							->setCellValue("r31",$r31)
							->setCellValue("r32",$r32)
							->setCellValue("r33",$r33)
							->setCellValue("r34",$r34)
							->setCellValue("r35",$r35)
							->setCellValue("r36",$r36)
							->setCellValue("W17",conv($w17))
							->setCellValue("X17",$x17)
							->setCellValue("W33",conv($w33))
							->setCellValue("X33",$x33)
							
							->setCellValue('s13',$s13)
							->setCellValue('s14',$s14)
							->setCellValue('w14',conv($w14))
							->setCellValue('X14',$x14)
							->setCellValue('s16',$s16)
							->setCellValue("s17",$s17)
							->setCellValue("s18",$s18)
							->setCellValue("s19",$s19)
							->setCellValue("s20",$s20)
							->setCellValue("s21",$s21)
							->setCellValue("s22",$s22)
							->setCellValue("s23",$s23)
							->setCellValue("s24",$s24)
							->setCellValue("s25",$s25)
							->setCellValue("s26",$s26)
							->setCellValue("s27",$s27)
							->setCellValue("s28",$s28)
							->setCellValue("s29",$s29)
							->setCellValue("s30",$s30)
							->setCellValue("s31",$s31)
							->setCellValue("s32",$s32)
							->setCellValue("s33",$s33)
							->setCellValue("s34",$s34)
							->setCellValue("s35",$s35)
							->setCellValue("s36",$s36)
							->setCellValue("W17",conv($w17))
							->setCellValue("X17",$x17)
							->setCellValue("W33",conv($w33))
							->setCellValue("X33",$x33)
							
							->setCellValue('t13',$t13)
							->setCellValue('t14',$t14)
							->setCellValue('w14',conv($w14))
							->setCellValue('X14',$x14)
							->setCellValue('t16',$t16)
							->setCellValue("t17",$t17)
							->setCellValue("t18",$t18)
							->setCellValue("t19",$t19)
							->setCellValue("t20",$t20)
							->setCellValue("t21",$t21)
							->setCellValue("t22",$t22)
							->setCellValue("t23",$t23)
							->setCellValue("t24",$t24)
							->setCellValue("t25",$t25)
							->setCellValue("t26",$t26)
							->setCellValue("t27",$t27)
							->setCellValue("t28",$t28)
							->setCellValue("t29",$t29)
							->setCellValue("t30",$t30)
							->setCellValue("t31",$t31)
							->setCellValue("t32",$t32)
							->setCellValue("t33",$t33)
							->setCellValue("t34",$t34)
							->setCellValue("t35",$t35)
							->setCellValue("t36",$t36)
							->setCellValue("W17",conv($w17))
							->setCellValue("X17",$x17)
							->setCellValue("W33",conv($w33))
							->setCellValue("X33",$x33)
							->setCellValue("q12",$this->select0)
							->setCellValue("r12",$this->select1)
							->setCellValue("s12",$this->select2)
							->setCellValue("t12",$this->select3)
							;
	for($s=13;$s<37;$s++){
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'q".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'r".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'s".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'t".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'w".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'x".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'q".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'r".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'s".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'t".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'w".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'x".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'q".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'r".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'s".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'t".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'w".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'x".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	}
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle('s5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle('s5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
	$objWriter->save($fileurl.".xlsx");
echo '<script>document.location.href="http://'.$path_root.'/tmp1/'.$fileurl.'.xlsx";</script>';	

}
	
function prt_3_h2o2(){
	include("../lib/jtsai.php");
	include("../connections/conn.php");
	$path_root=$_SERVER['HTTP_HOST'];
	$dat=date("YmdHis");
	require_once "../PHPEXCEL/Classes/PHPExcel.php";
	require_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
	$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);	
	
	$fileurl="../tmp1/TI".$this->lot_no;
	$anyl=new anylize;
	$anyl->lot_no=$this->lot_no;
	$anyl->rcv();
	$b7="SAMPLE 取得   (  " .substr($anyl->sma_time,0,-8)."  年 ".substr($anyl->sma_time,4,-6)."  月  ".substr($anyl->sma_time,6,-4)."  日共  ".$anyl->and_total."  瓶  )";
	$b8="時間： ".substr($anyl->sma_time,8,-2)."  時  ".substr($anyl->sma_time,10)."  分";
	$v6=get_uname($anyl->and_person);
	$s8=ddt($anyl->report_time);
	/// Assay
	$tb=get_table("A",$this->pdd_chemical);
	$query="SELECT DISTINCT 
                            ".$tb.".*, Sample_All.SMA_DRUMNO, 
                            Sample_All.SMA_ID
FROM              ".$tb." INNER JOIN
                            Sample_All ON ".$tb.".LotNo = Sample_All.SMA_LOT AND 
                            ".$tb.".SampleNo = Sample_All.SMA_ID
							WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select0."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$l13=$row['SMA_ID'];
	$l14=$row['Average'];
	$tester11=$row['Tester'];
	$tt11=substr($row['AnalyzeTime'],0,-6);
}	
//  echo $query."</br>";
	$tb=get_table("M13",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select0."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$l16=$row['SMA_ID'];
	$tester12=$row['Tester'];
	$tt12=substr($row['AnalyzeTime'],0,-6);
	$l17=$row['Na'];
	$l18=$row['Mg'];
	$l19=$row['Al'];
	$l20=$row['K'];
	$l21=$row['Ca'];
	$l22=$row['Cr'];
	$l23=$row['Mn'];
	$l24=$row['Fe'];
	$l25=$row['Ni'];
	$l26=$row['Co'];
	$l27=$row['Cu'];
	$l28=$row['Zn'];
	$l29=$row['W'];
	$l30=$row['Pb'];
	$l31=$row['B'];
	$l32=$row['Si'];
}
	$tb=get_table("P",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select0."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);

$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$l34=$row['PP05'];
	$l35=$row['PP03'];
	$l36=$row['PP02'];
	$l37=$row['PP01'];
	$l33=$row['SMA_ID'];
	$tester13=$row['Tester'];
	$tt13=substr($row['AnalyzeTime'],0,-6);
}

///R2
	/// Assay
	$tb=get_table("A",$this->pdd_chemical);
	$query="SELECT DISTINCT 
                            ".$tb.".*, Sample_All.SMA_DRUMNO, 
                            Sample_All.SMA_ID
FROM              ".$tb." INNER JOIN
                            Sample_All ON ".$tb.".LotNo = Sample_All.SMA_LOT AND 
                            ".$tb.".SampleNo = Sample_All.SMA_ID
							WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select1."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$r13=$row['SMA_ID'];
	$r14=$row['Average'];
	$tester21=$row['Tester'];
	$tt21=substr($row['AnalyzeTime'],0,-6);
}	
//  
	$tb=get_table("M13",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select1."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$m16=$row['SMA_ID'];
	$tester22=$row['Tester'];
	$tt22=substr($row['AnalyzeTime'],0,-6);
	$m17=$row['Na'];
	$m18=$row['Mg'];
	$m19=$row['Al'];
	$m20=$row['K'];
	$m21=$row['Ca'];
	$m22=$row['Cr'];
	$m23=$row['Mn'];
	$m24=$row['Fe'];
	$m25=$row['Ni'];
	$m26=$row['Co'];
	$m27=$row['Cu'];
	$m28=$row['Zn'];
	$m29=$row['W'];
	$m30=$row['Pb'];
	$m31=$row['B'];
	$m32=$row['Si'];
}
	$tb=get_table("P",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select1."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$r34=$row['PP05'];
	$r35=$row['PP03'];
	$r36=$row['PP02'];
	$r37=$row['PP01'];
	$r33=$row['SMA_ID'];
	$tester23=$row['Tester'];
	$tt23=substr($row['AnalyzeTime'],0,-6);
}

///R3
	/// Assay
	$tb=get_table("A",$this->pdd_chemical);
	$query="SELECT DISTINCT 
                            ".$tb.".*, Sample_All.SMA_DRUMNO, 
                            Sample_All.SMA_ID
FROM              ".$tb." INNER JOIN
                            Sample_All ON ".$tb.".LotNo = Sample_All.SMA_LOT AND 
                            ".$tb.".SampleNo = Sample_All.SMA_ID
							WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select2."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$s13=$row['SMA_ID'];
	$s14=$row['Average'];
	$tester31=$row['Tester'];
	$tt31=substr($row['AnalyzeTime'],0,-6);
}	
//  echo $query."</br>";
	$tb=get_table("M13",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select2."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$s16=$row['SMA_ID'];
	$tester32=$row['Tester'];
	$tt32=substr($row['AnalyzeTime'],0,-6);
	$s17=$row['Na'];
	$s18=$row['Mg'];
	$s19=$row['Al'];
	$s20=$row['K'];
	$s21=$row['Ca'];
	$s22=$row['Cr'];
	$s23=$row['Mn'];
	$s24=$row['Fe'];
	$s25=$row['Ni'];
	$s26=$row['Co'];
	$s27=$row['Cu'];
	$s28=$row['Zn'];
	$s29=$row['W'];
	$s30=$row['Pb'];
	$s31=$row['B'];
	$s32=$row['Si'];
}
	$tb=get_table("P",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select2."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$s34=$row['PP05'];
	$s35=$row['PP03'];
	$s36=$row['PP02'];
	$s37=$row['PP01'];
	$s33=$row['SMA_ID'];
	$tester33=$row['Tester'];
	$tt33=substr($row['AnalyzeTime'],0,-6);
}

/// R4
	/// Assay
	$tb=get_table("A",$this->pdd_chemical);
	$query="SELECT DISTINCT 
                            ".$tb.".*, Sample_All.SMA_DRUMNO, 
                            Sample_All.SMA_ID
FROM              ".$tb." INNER JOIN
                            Sample_All ON ".$tb.".LotNo = Sample_All.SMA_LOT AND 
                            ".$tb.".SampleNo = Sample_All.SMA_ID
							WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select3."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$t13=$row['SMA_ID'];
	$t14=$row['Average'];
	$tester41=$row['Tester'];
	$tt41=substr($row['AnalyzeTime'],0,-6);
}	
//  echo $query."</br>";
	$tb=get_table("M13",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select3."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$t16=$row['SMA_ID'];
	$tester42=$row['Tester'];
	$tt42=substr($row['AnalyzeTime'],0,-6);
	$t17=$row['Na'];
	$t18=$row['Mg'];
	$t19=$row['Al'];
	$t20=$row['K'];
	$t21=$row['Ca'];
	$t22=$row['Cr'];
	$t23=$row['Mn'];
	$t24=$row['Fe'];
	$t25=$row['Ni'];
	$t26=$row['Co'];
	$t27=$row['Cu'];
	$t28=$row['Zn'];
	$t29=$row['W'];
	$t30=$row['Pb'];
	$t31=$row['B'];
	$t32=$row['Si'];
}
	$tb=get_table("P",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select3."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$o34=$row['PP05'];
	$o35=$row['PP03'];
	$o36=$row['PP02'];
	$o37=$row['PP01'];
	$o33=$row['SMA_ID'];
	$tester43=$row['Tester'];
	$tt43=substr($row['AnalyzeTime'],0,-6);
}


//#Si
$tb=get_table("TT_Si",$this->pdd_chemical);
	$query="SELECT DISTINCT dbo.Sample_All.SMA_DRUMNO, dbo.".$tb.".*
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select0."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$si0=$row['Si'];
	$tester14=$row['Tester'];
	$tt14=substr($row['AnalyzeTime'],0,-6);
}	
$tb=get_table("TT_Si",$this->pdd_chemical);
	$query="SELECT DISTINCT dbo.Sample_All.SMA_DRUMNO, dbo.".$tb.".*
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select1."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
//echo $query;
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$m32=$row['Si'];
	$tester24=$row['Tester'];
	$tt24=substr($row['AnalyzeTime'],0,-6);
}	
$tb=get_table("TT_Si",$this->pdd_chemical);
	$query="SELECT DISTINCT dbo.Sample_All.SMA_DRUMNO, dbo.".$tb.".*
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select2."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$si2=$row['Si'];
	$tester34=$row['Tester'];
	$tt34=substr($row['AnalyzeTime'],0,-6);
}	
$tb=get_table("TT_Si",$this->pdd_chemical);
	$query="SELECT DISTINCT dbo.Sample_All.SMA_DRUMNO, dbo.".$tb.".*
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select3."')
ORDER BY   dbo.".$tb.".AnalyzeTime";$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$si3=$row['Si'];
	$tester44=$row['Tester'];
	$tt44=substr($row['AnalyzeTime'],0,-6);
}	


//#B

$tb=get_table("TT_B",$this->pdd_chemical);
	$query="SELECT DISTINCT dbo.Sample_All.SMA_DRUMNO, dbo.".$tb.".*
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select0."')
ORDER BY   dbo.".$tb.".AnalyzeTime";

$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$l31=$row['B'];
	$tester15=$row['Tester'];
	$tt15=substr($row['AnalyzeTime'],0,-6);
}	
	$query="SELECT DISTINCT dbo.Sample_All.SMA_DRUMNO, dbo.".$tb.".*
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select1."')
ORDER BY   dbo.".$tb.".AnalyzeTime";

$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$m31=$row['B'];
	$tester25=$row['Tester'];
	$tt25=substr($row['AnalyzeTime'],0,-6);
}
	$query="SELECT DISTINCT dbo.Sample_All.SMA_DRUMNO, dbo.".$tb.".*
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select2."')
ORDER BY   dbo.".$tb.".AnalyzeTime";

$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$n31=$row['B'];
	$tester35=$row['Tester'];
	$tt35=substr($row['AnalyzeTime'],0,-6);
}
	$query="SELECT DISTINCT dbo.Sample_All.SMA_DRUMNO, dbo.".$tb.".*
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select3."')
ORDER BY   dbo.".$tb.".AnalyzeTime";

$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$o31=$row['B'];
	$tester45=$row['Tester'];
	$tt45=substr($row['AnalyzeTime'],0,-6);
}


//#CapN
$tb=get_table("CapN",$this->pdd_chemical);
	$query="SELECT DISTINCT dbo.Sample_All.SMA_DRUMNO, dbo.".$tb.".*
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select0."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$l38=$row['Value'];
	$tester_capn1=$row['Tester'];
	$tt_capn1=substr($row['AnalyzeTime'],0,-6);
}	
	$query="SELECT DISTINCT dbo.Sample_All.SMA_DRUMNO, dbo.".$tb.".*
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select1."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$m38=$row['Value'];
	$tester_capn2=$row['Tester'];
	$tt_capn2=substr($row['AnalyzeTime'],0,-6);
}	
	$query="SELECT DISTINCT dbo.Sample_All.SMA_DRUMNO, dbo.".$tb.".*
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select2."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$n38=$row['Value'];
	$tester_capn3=$row['Tester'];
	$tt_capn3=substr($row['AnalyzeTime'],0,-6);
}	
	$query="SELECT DISTINCT dbo.Sample_All.SMA_DRUMNO, dbo.".$tb.".*
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select3."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$o38=$row['Value'];
	$tester_capn4=$row['Tester'];
	$tt_capn4=substr($row['AnalyzeTime'],0,-6);
}	

 

$w14=$tester11."\n".$tester21."\n".$tester31."\n".$tester41;
$w17=$tester12."\n".$tester22."\n".$tester32."\n".$tester42;
$w33=$tester13."\n".$tester23."\n".$tester33."\n".$tester43;
$tester_si=$tester14."\n".$tester24."\n".$tester34."\n".$tester44;
$tester_b=$tester15."\n".$tester25."\n".$tester35."\n".$tester45;
$tester_capn=$tester_capn1."\n".$tester_capn2."\n".$tester_capn3."\n".$tester_capn4;

$x14=$tt11."\n".$tt21."\n".$tt31."\n".$tt41;
$x17=$tt12."\n".$tt22."\n".$tt32."\n".$tt42;
$x33=$tt13."\n".$tt23."\n".$tt33."\n".$tt43;
$d_si=$tt14."\n".$tt24."\n".$tt34."\n".$tt44;
$d_b=$tt15."\n".$tt25."\n".$tt35."\n".$tt45;
$d_capn=$tt_capn1."\n".$tt_capn2."\n".$tt_capn3."\n".$tt_capn4;

    $objPHPExcel = new PHPExcel();
	$fl="../FormList/PrintAnalyze/anycounts.xlsx";
	$objPHPExcel = PHPExcel_IOFactory::load($fl);
	$objPHPExcel->setActiveSheetIndex($this->activesheet);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)
 							->setCellValue("c4",iconv('big5','utf-8',$this->lot_no))
	    					->setCellValue("i4",conv($this->type))
	  						->setCellValue("B6",conv($b7))
							->setCellValue("B7",conv($b8))
							->setCellValue("p7",conv($s8))
							->setCellValue('l13',$l13)
							->setCellValue('l14',$l14)
							->setCellValue('s14',conv($w14))
							->setCellValue('t14',$x14)
							->setCellValue('l16',$l16)
							->setCellValue("l17",$l17)
							->setCellValue("l18",$l18)
							->setCellValue("l19",$l19)
							->setCellValue("l20",$l20)
							->setCellValue("l21",$l21)
							->setCellValue("l22",$l22)
							->setCellValue("l23",$l23)
							->setCellValue("l24",$l24)
							->setCellValue("l25",$l25)
							->setCellValue("l26",$l26)
							->setCellValue("l27",$l27)
							->setCellValue("l28",$l28)
							->setCellValue("l29",$l29)
							->setCellValue("l30",$l30)
							->setCellValue("l31",$l31)
							->setCellValue("l32",$si0)
							->setCellValue("l33",$l33)
							->setCellValue("l34",$l34)
							->setCellValue("l35",$l35)
							->setCellValue("l36",$l36)
							->setCellValue("l37",$l37)
							->setCellValue("l38",$l38)
							->setCellValue("s17",conv($w17))
							->setCellValue("t17",$x17)
							->setCellValue("s34",conv($w33))
							->setCellValue("t34",$x33)
							
							->setCellValue('m13',$r13)
							->setCellValue('m14',$r14)
							->setCellValue('s14',conv($w14))
							->setCellValue('t14',$x14)
							->setCellValue('m16',$m16)
							->setCellValue("m17",$m17)
							->setCellValue("m18",$m18)
							->setCellValue("m19",$m19)
							->setCellValue("m20",$m20)
							->setCellValue("m21",$m21)
							->setCellValue("m22",$m22)
							->setCellValue("m23",$m23)
							->setCellValue("m24",$m24)
							->setCellValue("m25",$m25)
							->setCellValue("m26",$m26)
							->setCellValue("m27",$m27)
							->setCellValue("m28",$m28)
							->setCellValue("m29",$m29)
							->setCellValue("m30",$m30)
							->setCellValue("m31",$m31)
							->setCellValue("m32",$m32)
							->setCellValue("m33",$r33)
							->setCellValue("m34",$r34)
							->setCellValue("m35",$r35)
							->setCellValue("m36",$r36)
							->setCellValue("m37",$r37)
							->setCellValue("m38",$m38)
							->setCellValue("s17",conv($w17))
							->setCellValue("t17",$x17)
							->setCellValue("s34",conv($w33))
							->setCellValue("t34",$x33)						
							->setCellValue('n13',$s13)
							->setCellValue('n14',$s14)
							->setCellValue('s14',conv($w14))
							->setCellValue('t14',$x14)
							->setCellValue('n16',$s16)
							->setCellValue("n17",$s17)
							->setCellValue("n18",$s18)
							->setCellValue("n19",$s19)
							->setCellValue("n20",$s20)
							->setCellValue("n21",$s21)
							->setCellValue("n22",$s22)
							->setCellValue("n23",$s23)
							->setCellValue("n24",$s24)
							->setCellValue("n25",$s25)
							->setCellValue("n26",$s26)
							->setCellValue("n27",$s27)
							->setCellValue("n28",$s28)
							->setCellValue("n29",$s29)
							->setCellValue("n30",$s30)
							->setCellValue("n31",$n31)
							->setCellValue("n32",$si2)
							->setCellValue("n33",$s33)
							->setCellValue("n34",$s34)
							->setCellValue("n35",$s35)
							->setCellValue("n36",$s36)
							->setCellValue("n37",$s37)
							->setCellValue("n38",$n38)
							->setCellValue("s17",conv($w17))
							->setCellValue("t17",$x17)
							->setCellValue("s34",conv($w33))
							->setCellValue("t34",$x33)			
							->setCellValue('o13',$t13)
							->setCellValue('o14',$t14)
							->setCellValue('s14',conv($w14))
							->setCellValue('t14',$x14)
							->setCellValue('o16',$t16)
							->setCellValue("o17",$t17)
							->setCellValue("o18",$t18)
							->setCellValue("o19",$t19)
							->setCellValue("o20",$t20)
							->setCellValue("o21",$t21)
							->setCellValue("o22",$t22)
							->setCellValue("o23",$t23)
							->setCellValue("o24",$t24)
							->setCellValue("o25",$t25)
							->setCellValue("o26",$t26)
							->setCellValue("o27",$t27)
							->setCellValue("o28",$t28)
							->setCellValue("o29",$t29)
							->setCellValue("o30",$t30)
							->setCellValue("o31",$o31)
							->setCellValue("o32",$si3)
							->setCellValue("o33",$o33)
							->setCellValue("o34",$o34)
							->setCellValue("o35",$o35)
							->setCellValue("o36",$o36)
							->setCellValue("o37",$o37)
							->setCellValue("o38",$o38)
							->setCellValue("s17",conv($w17))
							->setCellValue("t17",$x17)
							->setCellValue("s34",conv($w33))
							->setCellValue("t34",$x33)					
							->setCellValue("s32",conv($tester_si))
							->setCellValue("t32",($d_si))
							->setCellValue("t31",($d_b))
							->setCellValue("s31",conv($tester_b))
							->setCellValue("t38",$d_capn)
							->setCellValue("s38",conv($tester_capn))
							->setCellValue("l12",$this->select0)
							->setCellValue("m12",$this->select1)
							->setCellValue("n12",$this->select2)
							->setCellValue("o12",$this->select3)
							;
	for($s=13;$s<39;$s++)
	{
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'l".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'m".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'n".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'o".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'p".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'s".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'t".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'l".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'m".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'n".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'o".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'p".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'s".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'t".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'l".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'m".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'n".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'o".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'p".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'s".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'t".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	}
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle('s5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle('s5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
	$objWriter->save($fileurl.".xlsx");
echo '<script>document.location.href="http://'.$path_root.'/tmp1/'.$fileurl.'.xlsx";</script>';	

}

function prt_3_nh4oh(){
	include("../lib/jtsai.php");
	include("../connections/conn.php");
	$path_root=$_SERVER['HTTP_HOST'];
	$dat=date("YmdHis");
	include("../PHPEXCEL/Classes/PHPExcel.php");
	require_once "../PHPEXCEL/Classes/PHPExcel.php";
	require_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
	$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);	
	
	$fileurl="../tmp1/TI".$this->lot_no;
	$anyl=new anylize;
	$anyl->lot_no=$this->lot_no;
	$anyl->rcv();
	$b7="SAMPLE 取得   (  " .substr($anyl->sma_time,0,-8)."  年 ".substr($anyl->sma_time,4,-6)."  月  ".substr($anyl->sma_time,6,-4)."  日共  ".$anyl->and_total."  瓶  )";
	$b8="時間： ".substr($anyl->sma_time,8,-2)."  時  ".substr($anyl->sma_time,10)."  分";
	$v6=get_uname($anyl->and_person);
	$s8=ddt($anyl->report_time);
	/// Assay
	$tb=get_table("A",$this->pdd_chemical);
	$query="SELECT DISTINCT 
                            ".$tb.".*, Sample_All.SMA_DRUMNO, 
                            Sample_All.SMA_ID
FROM              ".$tb." INNER JOIN
                            Sample_All ON ".$tb.".LotNo = Sample_All.SMA_LOT AND 
                            ".$tb.".SampleNo = Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select0."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$l13=$row['SMA_ID'];
	$l14=$row['Average'];
	$tester11=$row['Tester'];
	$tt11=substr($row['AnalyzeTime'],0,-6);
}	
//  echo $query."</br>";
	$tb=get_table("M13",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select0."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$l15=$row['SMA_ID'];
	$tester12=$row['Tester'];
	$tt12=substr($row['AnalyzeTime'],0,-6);
	$l16=$row['Na'];
	$l17=$row['Mg'];
	$l18=$row['Al'];
	$l19=$row['K'];
	$l20=$row['Ca'];
	$l21=$row['Cr'];
	$l22=$row['Mn'];
	$l23=$row['Fe'];
	$l24=$row['Ni'];
	$l25=$row['Co'];
	$l26=$row['Cu'];
	$l27=$row['Zn'];
	$l28=$row['In'];
	$l29=$row['Ce'];
	$l30=$row['W'];
	$l31=$row['Pb'];
}
	$tb=get_table("P",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select0."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$l33=$row['PP05'];if($l33=='0'){$l33=conv("─");}
	$l34=$row['PP03'];if($l34=='0'){$l34=conv("─");}
	$l35=$row['PP02'];if($l35=='0'){$l35=conv("─");}
	$l36=$row['PP01'];if($l36=='0'){$l36=conv("─");}
	$l32=$row['SMA_ID'];
	$tester13=$row['Tester'];
	$tt13=substr($row['AnalyzeTime'],0,-6);
}

///R2
// 
	$tb=get_table("A",$this->pdd_chemical);
	$query="SELECT DISTINCT 
                            ".$tb.".*, Sample_All.SMA_DRUMNO, 
                            Sample_All.SMA_ID
FROM              ".$tb." INNER JOIN
                            Sample_All ON ".$tb.".LotNo = Sample_All.SMA_LOT AND 
                            ".$tb.".SampleNo = Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select1."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$r13=$row['SMA_ID'];
	$r14=$row['Average'];
	$tester21=$row['Tester'];
	$tt21=substr($row['AnalyzeTime'],0,-6);
}	
//  echo $query."</br>";
	$tb=get_table("M13",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select1."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);

while($row = mssql_fetch_array($result)){
	$m15=$row['SMA_ID'];
	$tester22=$row['Tester'];
	$tt22=substr($row['AnalyzeTime'],0,-6);
	$m16=$row['Na'];
	$m17=$row['Mg'];
	$m18=$row['Al'];
	$m19=$row['K'];
	$m20=$row['Ca'];
	$m21=$row['Cr'];
	$m22=$row['Mn'];
	$m23=$row['Fe'];
	$m24=$row['Ni'];
	$m25=$row['Co'];
	$m26=$row['Cu'];
	$m27=$row['Zn'];
	$m28=$row['In'];
	$m29=$row['Ce'];
	$m30=$row['W'];
	$m31=$row['Pb'];
}
	$tb=get_table("P",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select1."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$m33=$row['PP05'];if($m33=='0'){$m33=conv("─");}
	$m34=$row['PP03'];if($m34=='0'){$m34=conv("─");}
	$m35=$row['PP02'];if($m35=='0'){$m35=conv("─");}
	$m36=$row['PP01'];if($m36=='0'){$m36=conv("─");}
	$m32=$row['SMA_ID'];
	$tester23=$row['Tester'];
	$tt23=substr($row['AnalyzeTime'],0,-6);
}

///R3
	/// Assay
	$tb=get_table("A",$this->pdd_chemical);
	$query="SELECT DISTINCT 
                            ".$tb.".*, Sample_All.SMA_DRUMNO, 
                            Sample_All.SMA_ID
FROM              ".$tb." INNER JOIN
                            Sample_All ON ".$tb.".LotNo = Sample_All.SMA_LOT AND 
                            ".$tb.".SampleNo = Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select2."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$n13=$row['SMA_ID'];
	$n14=$row['Average'];
	$tester31=$row['Tester'];
	$tt31=substr($row['AnalyzeTime'],0,-6);
}	
//  echo $query."</br>";
	$tb=get_table("M13",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select2."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$n15=$row['SMA_ID'];
	$tester32=$row['Tester'];
	$tt32=substr($row['AnalyzeTime'],0,-6);
	$n16=$row['Na'];
	$n17=$row['Mg'];
	$n18=$row['Al'];
	$n19=$row['K'];
	$n20=$row['Ca'];
	$n21=$row['Cr'];
	$n22=$row['Mn'];
	$n23=$row['Fe'];
	$n24=$row['Ni'];
	$n25=$row['Co'];
	$n26=$row['Cu'];
	$n27=$row['Zn'];
	$n28=$row['In'];
	$n29=$row['Ce'];
	$n30=$row['W'];
	$n31=$row['Pb'];
}
	$tb=get_table("P",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select2."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$n33=$row['PP05'];if($n33=='0'){$n33=conv("─");}
	$n34=$row['PP03'];if($n34=='0'){$n34=conv("─");}
	$n35=$row['PP02'];if($n35=='0'){$n35=conv("─");}
	$n36=$row['PP01'];if($n36=='0'){$n36=conv("─");}
	$n32=$row['SMA_ID'];
	$tester33=$row['Tester'];
	$tt33=substr($row['AnalyzeTime'],0,-6);
}

/// R4
	/// Assay
	$tb=get_table("A",$this->pdd_chemical);
	$query="SELECT DISTINCT 
                            ".$tb.".*, Sample_All.SMA_DRUMNO, 
                            Sample_All.SMA_ID
FROM              ".$tb." INNER JOIN
                            Sample_All ON ".$tb.".LotNo = Sample_All.SMA_LOT AND 
                            ".$tb.".SampleNo = Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select3."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$o13=$row['SMA_ID'];
	$o14=$row['Average'];
	$tester41=$row['Tester'];
	$tt41=substr($row['AnalyzeTime'],0,-6);
}	
//  echo $query."</br>";
	$tb=get_table("M13",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select3."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$o15=$row['SMA_ID'];
	$tester42=$row['Tester'];
	$tt42=substr($row['AnalyzeTime'],0,-6);
	$o16=$row['Na'];
	$o17=$row['Mg'];
	$o18=$row['Al'];
	$o19=$row['K'];
	$o20=$row['Ca'];
	$o21=$row['Cr'];
	$o22=$row['Mn'];
	$o23=$row['Fe'];
	$o24=$row['Ni'];
	$o25=$row['Co'];
	$o26=$row['Cu'];
	$o27=$row['Zn'];
	$o28=$row['In'];
	$o29=$row['Ce'];
	$o30=$row['W'];
	$o31=$row['Pb'];
}
	$tb=get_table("P",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select3."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$o33=$row['PP05'];if($o33=='0'){$o33=conv("─");}
	$o34=$row['PP03'];if($o34=='0'){$o34=conv("─");}
	$o35=$row['PP02'];if($o35=='0'){$o35=conv("─");}
	$o36=$row['PP01'];if($o36=='0'){$o36=conv("─");}
	$o32=$row['SMA_ID'];
	$tester43=$row['Tester'];
	$tt43=substr($row['AnalyzeTime'],0,-6);
}


$w14=$tester11."\n".$tester21."\n".$tester31."\n".$tester41;
$w17=$tester12."\n".$tester22."\n".$tester32."\n".$tester42;
$w33=$tester13."\n".$tester23."\n".$tester33."\n".$tester43;

$x14=$tt11."\n".$tt21."\n".$tt31."\n".$tt41;
$x17=$tt12."\n".$tt22."\n".$tt32."\n".$tt42;
$x33=$tt13."\n".$tt23."\n".$tt33."\n".$tt43;
    $objPHPExcel = new PHPExcel();
	$fl="../FormList/PrintAnalyze/anycounts.xlsx";
	$objPHPExcel = PHPExcel_IOFactory::load($fl);
	$objPHPExcel->setActiveSheetIndex($this->activesheet);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)
 							->setCellValue("c5",iconv('big5','utf-8',$this->lot_no))
	    					->setCellValue("j5",conv($this->type))
	  						->setCellValue("B7",conv($b7))
							->setCellValue("B8",conv($b8))
							->setCellValue("p7",conv($s8))
							->setCellValue('l13',$l13)
							->setCellValue('l14',$l14)
							->setCellValue('l16',$l16)
							->setCellValue('l15',$l15)
							->setCellValue("l17",$l17)
							->setCellValue("l18",$l18)
							->setCellValue("l19",$l19)
							->setCellValue("l20",$l20)
							->setCellValue("l21",$l21)
							->setCellValue("l22",$l22)
							->setCellValue("l23",$l23)
							->setCellValue("l24",$l24)
							->setCellValue("l25",$l25)
							->setCellValue("l26",$l26)
							->setCellValue("l27",$l27)
							->setCellValue("l28",$l28)
							->setCellValue("l29",$l29)
							->setCellValue("l30",$l30)
							->setCellValue("l31",$l31)
							->setCellValue("l32",$l32)
							->setCellValue("l33",$l33)
							->setCellValue("l34",$l34)
							->setCellValue("l35",$l35)
							->setCellValue("l36",$l36)
							->setCellValue("s16",conv($w17))
							->setCellValue("t16",$x17)
							->setCellValue("s33",conv($w33))
							->setCellValue("t33",$x33)
							
							->setCellValue('m13',$r13)
							->setCellValue('m14',$r14)
							->setCellValue('s14',conv($w14))
							->setCellValue('t14',$x14)
							->setCellValue('m15',$m15)
							->setCellValue('m16',$m16)
							->setCellValue("m17",$m17)
							->setCellValue("m18",$m18)
							->setCellValue("m19",$m19)
							->setCellValue("m20",$m20)
							->setCellValue("m21",$m21)
							->setCellValue("m22",$m22)
							->setCellValue("m23",$m23)
							->setCellValue("m24",$m24)
							->setCellValue("m25",$m25)
							->setCellValue("m26",$m26)
							->setCellValue("m27",$m27)
							->setCellValue("m28",$m28)
							->setCellValue("m29",$m29)
							->setCellValue("m30",$m30)
							->setCellValue("m31",$m31)
							->setCellValue("m32",$m32)
							->setCellValue("m33",$m33)
							->setCellValue("m34",$m34)
							->setCellValue("m35",$m35)
							->setCellValue("m36",$m36)
							->setCellValue("s17",conv($w17))
							->setCellValue("t17",$x17)
							->setCellValue("s34",conv($w33))
							->setCellValue("t34",$x33)
							
							->setCellValue('n13',$n13)
							->setCellValue('n14',$n14)
							->setCellValue('s14',conv($w14))
							->setCellValue('t14',$x14)
							->setCellValue('n15',$n15)
							->setCellValue('n16',$n16)
							->setCellValue("n17",$n17)
							->setCellValue("n18",$n18)
							->setCellValue("n19",$n19)
							->setCellValue("n20",$n20)
							->setCellValue("n21",$n21)
							->setCellValue("n22",$n22)
							->setCellValue("n23",$n23)
							->setCellValue("n24",$n24)
							->setCellValue("n25",$n25)
							->setCellValue("n26",$n26)
							->setCellValue("n27",$n27)
							->setCellValue("n28",$n28)
							->setCellValue("n29",$n29)
							->setCellValue("n30",$n30)
							->setCellValue("n31",$n31)
							->setCellValue("n32",$n32)
							->setCellValue("n33",$n33)
							->setCellValue("n34",$n34)
							->setCellValue("n35",$n35)
							->setCellValue("n36",$n36)
							->setCellValue("s17",conv($w17))
							->setCellValue("t17",$x17)
							->setCellValue("s34",conv($w33))
							->setCellValue("t34",$x33)
							
							->setCellValue('o13',$o13)
							->setCellValue('o14',$o14)
							->setCellValue('s14',conv($w14))
							->setCellValue('t14',$x14)
							->setCellValue('o15',$o15)
							->setCellValue('o16',$o16)
							->setCellValue("o17",$o17)
							->setCellValue("o18",$o18)
							->setCellValue("o19",$o19)
							->setCellValue("o20",$o20)
							->setCellValue("o21",$o21)
							->setCellValue("o22",$o22)
							->setCellValue("o23",$o23)
							->setCellValue("o24",$o24)
							->setCellValue("o25",$o25)
							->setCellValue("o26",$o26)
							->setCellValue("o27",$o27)
							->setCellValue("o28",$o28)
							->setCellValue("o29",$o29)
							->setCellValue("o30",$o30)
							->setCellValue("o31",$o31)
							->setCellValue("o32",$o32)
							->setCellValue("o33",$o33)
							->setCellValue("o34",$o34)
							->setCellValue("o35",$o35)
							->setCellValue("o36",$o36)
							->setCellValue("s17",conv($w17))
							->setCellValue("t17",$x17)
							->setCellValue("s34",conv($w33))
							->setCellValue("t34",$x33)
							->setCellValue("l12",$this->select0)
							->setCellValue("m12",$this->select1)
							->setCellValue("n12",$this->select2)
							->setCellValue("o12",$this->select3)
							;
	for($s=11;$s<37;$s++){
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'l".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'m".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'n".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'o".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'p".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'s".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'t".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'l".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'m".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'n".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'o".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'p".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'s".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'t".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'l".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'m".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'n".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'o".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'p".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'s".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'t".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	}
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle('s6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle('s6')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
	$objWriter->save($fileurl.".xlsx");
echo '<script>document.location.href="http://'.$path_root.'/tmp1/'.$fileurl.'.xlsx";</script>';	

}
	
function prt_3_ipa(){
	include("../lib/jtsai.php");
	include("../connections/conn.php");
	$path_root=$_SERVER['HTTP_HOST'];
	$dat=date("YmdHis");
	include("../PHPEXCEL/Classes/PHPExcel.php");
	require_once "../PHPEXCEL/Classes/PHPExcel.php";
	require_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
	$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);	
	
	$fileurl="../tmp1/TI".$this->lot_no;
	$anyl=new anylize;
	$anyl->lot_no=$this->lot_no;
	$anyl->rcv();
	$b7="SAMPLE 取得   (  " .substr($anyl->sma_time,0,-8)."  年 ".substr($anyl->sma_time,4,-6)."  月  ".substr($anyl->sma_time,6,-4)."  日共  ".$anyl->and_total."  瓶  )";
	$b8="時間： ".substr($anyl->sma_time,8,-2)."  時  ".substr($anyl->sma_time,10)."  分";
	$v6=get_uname($anyl->and_person);
	$s8=ddt($anyl->report_time);
	///water
$query="SELECT DISTINCT 
                          Sample_All.SMA_DRUMNO, Sample_All.SMA_LOT, TLIQA9100320.SampleNo, 
                          TLIQA9100320.WaterAve, TLIQA9100320.Tester, 
                          TLIQA9100320.AnalyzeTime
FROM             Sample_All INNER JOIN
                          TLIQA9100320 ON Sample_All.SMA_LOT = TLIQA9100320.LotNo AND 
                          Sample_All.SMA_ID = TLIQA9100320.SampleNo
WHERE          (dbo.Sample_All.SMA_LOT = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select0."')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$l13=$row['SampleNo'];
	$l14=$row['WaterAve'];
	$s14=trim($row['Tester']);
	$t14=substr($row['AnalyzeTime'],0,-6);
}	
	/// I-CL
$query="SELECT DISTINCT 
                          Sample_All.*, TLIQA9100304.*
FROM             Sample_All INNER JOIN
                          TLIQA9100304 ON Sample_All.SMA_LOT = TLIQA9100304.LotNo AND 
                          Sample_All.SMA_ID = TLIQA9100304.SampleNo
WHERE          (dbo.Sample_All.SMA_LOT = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select0."') and (TLIQA9100304.CL<>'')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$l15=$row['SampleNo'];
	$l16=$row['CL'];
	$s16=trim($row['Tester']);
	$t16=substr($row['AnalyzeTime'],0,-6);
}
	/// I
$query="SELECT DISTINCT 
                          Sample_All.*, TLIQA9100304.*
FROM             Sample_All INNER JOIN
                          TLIQA9100304 ON Sample_All.SMA_LOT = TLIQA9100304.LotNo AND 
                          Sample_All.SMA_ID = TLIQA9100304.SampleNo
WHERE          (dbo.Sample_All.SMA_LOT = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select0."')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$l15=$row['SampleNo'];
	$l17=$row['NO3'];
	$l18=$row['PO4'];
	$l19=$row['SO4'];
	$tester11=trim($row['Tester']);
	$tt11=substr($row['AnalyzeTime'],0,-6);
}

//echo $query."</br>";
	$tb=get_table("M13",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select0."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$l15=$row['SMA_ID'];
	$tester12=$row['Tester'];
	$tt12=substr($row['AnalyzeTime'],0,-6);
	$l21=$row['Na'];
	$l22=$row['Mg'];
	$l23=$row['Al'];
	$l24=$row['K'];
	$l25=$row['Ca'];
	$l26=$row['Cr'];
	$l27=$row['Mn'];
	$l28=$row['Fe'];
	$l29=$row['Ni'];
	$l30=$row['Co'];
	$l31=$row['Cu'];
	$l32_=$row['Zn'];
	$l33_=$row['W'];
	$l34_=$row['Pb'];
}
	$tb=get_table("P",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select0."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);

$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$l33=$row['PP05'];
	$l34=$row['PP03'];
	$l35=$row['PP02'];
	$l36=$row['PP01'];
	$l32=$row['SMA_ID'];
	$tester13=$row['Tester'];
	$tt13=substr($row['AnalyzeTime'],0,-6);
}

///R2
	///water
$query="SELECT DISTINCT 
                          Sample_All.SMA_DRUMNO, Sample_All.SMA_LOT, TLIQA9100320.SampleNo, 
                          TLIQA9100320.WaterAve, TLIQA9100320.Tester, 
                          TLIQA9100320.AnalyzeTime
FROM             Sample_All INNER JOIN
                          TLIQA9100320 ON Sample_All.SMA_LOT = TLIQA9100320.LotNo AND 
                          Sample_All.SMA_ID = TLIQA9100320.SampleNo
WHERE          (dbo.Sample_All.SMA_LOT = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select1."')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$m13=$row['SampleNo'];
	$m14=$row['WaterAve'];
}	
	/// I-CL
$query="SELECT DISTINCT 
                          Sample_All.*, TLIQA9100304.*
FROM             Sample_All INNER JOIN
                          TLIQA9100304 ON Sample_All.SMA_LOT = TLIQA9100304.LotNo AND 
                          Sample_All.SMA_ID = TLIQA9100304.SampleNo
WHERE          (dbo.Sample_All.SMA_LOT = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select1."') and (TLIQA9100304.CL<>'')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$m15=$row['SampleNo'];
	$m16=$row['CL'];
}
	/// I
$query="SELECT DISTINCT 
                          Sample_All.*, TLIQA9100304.*
FROM             Sample_All INNER JOIN
                          TLIQA9100304 ON Sample_All.SMA_LOT = TLIQA9100304.LotNo AND 
                          Sample_All.SMA_ID = TLIQA9100304.SampleNo
WHERE          (dbo.Sample_All.SMA_LOT = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select1."')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$m15=$row['SampleNo'];
	$m17=$row['NO3'];
	$m18=$row['PO4'];
	$m19=$row['SO4'];
	$tester21=trim($row['Tester']);
	$tt21=substr($row['AnalyzeTime'],0,-6);
}

//  echo $query."</br>";
	$tb=get_table("M13",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select1."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);

while($row = mssql_fetch_array($result)){
	$m15=$row['SMA_ID'];
	$tester22=$row['Tester'];
	$tt22=substr($row['AnalyzeTime'],0,-6);
	$m21=$row['Na'];
	$m22=$row['Mg'];
	$m23=$row['Al'];
	$m24=$row['K'];
	$m25=$row['Ca'];
	$m26=$row['Cr'];
	$m27=$row['Mn'];
	$m28=$row['Fe'];
	$m29=$row['Ni'];
	$m30=$row['Co'];
	$m31=$row['Cu'];
	$m32_=$row['Zn'];
	$m33_=$row['W'];
	$m34_=$row['Pb'];
}
	$tb=get_table("P",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select1."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$m33=$row['PP05'];
	$m34=$row['PP03'];
	$m35=$row['PP02'];
	$m36=$row['PP01'];
	$m32=$row['SMA_ID'];
	$tester23=$row['Tester'];
	$tt23=substr($row['AnalyzeTime'],0,-6);
}

///R3
	///water
$query="SELECT DISTINCT 
                          Sample_All.SMA_DRUMNO, Sample_All.SMA_LOT, TLIQA9100320.SampleNo, 
                          TLIQA9100320.WaterAve, TLIQA9100320.Tester, 
                          TLIQA9100320.AnalyzeTime
FROM             Sample_All INNER JOIN
                          TLIQA9100320 ON Sample_All.SMA_LOT = TLIQA9100320.LotNo AND 
                          Sample_All.SMA_ID = TLIQA9100320.SampleNo
WHERE          (dbo.Sample_All.SMA_LOT = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select2."')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$n13=$row['SampleNo'];
	$n14=$row['WaterAve'];
	
}	
	/// I-CL
$query="SELECT DISTINCT 
                          Sample_All.*, TLIQA9100304.*
FROM             Sample_All INNER JOIN
                          TLIQA9100304 ON Sample_All.SMA_LOT = TLIQA9100304.LotNo AND 
                          Sample_All.SMA_ID = TLIQA9100304.SampleNo
WHERE          (dbo.Sample_All.SMA_LOT = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select2."') and (TLIQA9100304.CL<>'')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$n15=$row['SampleNo'];
	$n16=$row['CL'];
}
	/// I
$query="SELECT DISTINCT 
                          Sample_All.*, TLIQA9100304.*
FROM             Sample_All INNER JOIN
                          TLIQA9100304 ON Sample_All.SMA_LOT = TLIQA9100304.LotNo AND 
                          Sample_All.SMA_ID = TLIQA9100304.SampleNo
WHERE          (dbo.Sample_All.SMA_LOT = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select2."')";
$result = mssql_query($query);
//$_SESSION['query']=$query;
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$n15=$row['SampleNo'];
	$n17=$row['NO3'];
	$n18=$row['PO4'];
	$n19=$row['SO4'];
	$tester31=trim($row['Tester']);
	$tt31=substr($row['AnalyzeTime'],0,-6);
}
//  echo $query."</br>";
	$tb=get_table("M13",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select2."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$n15=$row['SMA_ID'];
	$tester32=$row['Tester'];
	$tt32=substr($row['AnalyzeTime'],0,-6);
	$n21=$row['Na'];
	$n22=$row['Mg'];
	$n23=$row['Al'];
	$n24=$row['K'];
	$n25=$row['Ca'];
	$n26=$row['Cr'];
	$n27=$row['Mn'];
	$n28=$row['Fe'];
	$n29=$row['Ni'];
	$n30=$row['Co'];
	$n31=$row['Cu'];
	$n32_=$row['Zn'];
	$n33_=$row['W'];
	$n34_=$row['Pb'];
}
	$tb=get_table("P",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select2."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$n33=$row['PP05'];
	$n34=$row['PP03'];
	$n35=$row['PP02'];
	$n36=$row['PP01'];
	$n32=$row['SMA_ID'];
	$tester33=$row['Tester'];
	$tt33=substr($row['AnalyzeTime'],0,-6);
}

$s17=$tester11."\n".$tester21."\n".$tester31."\n".$tester41;
$s20=$tester12."\n".$tester22."\n".$tester32."\n".$tester42;
$s35=$tester13."\n".$tester23."\n".$tester33."\n".$tester43;

$t17=$tt11."\n".$tt21."\n".$tt31."\n".$tt41;
$t20=$tt12."\n".$tt22."\n".$tt32."\n".$tt42;
$t35=$tt13."\n".$tt23."\n".$tt33."\n".$tt43;
    $objPHPExcel = new PHPExcel();
	$fl="../FormList/PrintAnalyze/anycounts.xlsx";
	$objPHPExcel = PHPExcel_IOFactory::load($fl);
	$objPHPExcel->setActiveSheetIndex($this->activesheet);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)
 							->setCellValue("c5",iconv('big5','utf-8',$this->lot_no))
	    					->setCellValue("h5",conv($this->type))
	  						->setCellValue("B7",conv($b7))
							->setCellValue("B8",conv($b8))
							->setCellValue("o8",conv($s8))
							->setCellValue('l13',$l13)
							->setCellValue('l14',$l14)
							->setCellValue('l16',$l16)
							->setCellValue('l20',$l15)
							->setCellValue("l17",$l17)
							->setCellValue("l18",$l18)
							->setCellValue("l19",$l19)
							->setCellValue("l21",$l21)
							->setCellValue("l22",$l22)
							->setCellValue("l23",$l23)
							->setCellValue("l24",$l24)
							->setCellValue("l25",$l25)
							->setCellValue("l26",$l26)
							->setCellValue("l27",$l27)
							->setCellValue("l28",$l28)
							->setCellValue("l29",$l29)
							->setCellValue("l30",$l30)
							->setCellValue("l31",$l31)
							->setCellValue("l32",$l32_)
							->setCellValue("l33",$l33_)
							->setCellValue("l34",$l34_)
							->setCellValue("l35",$l32)
							->setCellValue("l36",$l33)
							->setCellValue("l37",$l34)
							->setCellValue("l38",$l35)
							->setCellValue("l39",$l36)
							->setCellValue("s16",conv($s16))
							->setCellValue("s16",conv($s16))
							->setCellValue("s14",conv($s14))
							->setCellValue("t14",conv($t14))
							->setCellValue("s16",conv($s16))
							->setCellValue("t16",conv($t16))
							->setCellValue("t33",$x33)
							->setCellValue("s17",conv($s17))
							->setCellValue("t17",conv($t17))
							->setCellValue('m13',$m13)
							->setCellValue('m14',$m14)
							->setCellValue('s20',conv($s20))
							->setCellValue('t13',$t13)
							->setCellValue('m20',$m15)
							->setCellValue('m16',$m16)
							->setCellValue("m17",$m17)
							->setCellValue("m18",$m18)
							->setCellValue("m19",$m19)
							->setCellValue("m21",$m21)
							->setCellValue("m22",$m22)
							->setCellValue("m23",$m23)
							->setCellValue("m24",$m24)
							->setCellValue("m25",$m25)
							->setCellValue("m26",$m26)
							->setCellValue("m27",$m27)
							->setCellValue("m28",$m28)
							->setCellValue("m29",$m29)
							->setCellValue("m30",$m30)
							->setCellValue("m31",$m31)
							->setCellValue("m32",$m32_)
							->setCellValue("m33",$m33_)
							->setCellValue("m34",$m34_)
							->setCellValue("m35",$m32)
							->setCellValue("m36",$m33)
							->setCellValue("m37",$m34)
							->setCellValue("m38",$m35)
							->setCellValue("m39",$m36)
							->setCellValue("s35",conv($s35))
							->setCellValue("t20",$t20)
							->setCellValue('n13',$n13)
							->setCellValue('n14',$n14)
							->setCellValue('n20',$n15)
							->setCellValue('n16',$n16)
							->setCellValue("n17",$n17)
							->setCellValue("n18",$n18)
							->setCellValue("n19",$n19)
							->setCellValue("n21",$n21)
							->setCellValue("n22",$n22)
							->setCellValue("n23",$n23)
							->setCellValue("n24",$n24)
							->setCellValue("n25",$n25)
							->setCellValue("n26",$n26)
							->setCellValue("n27",$n27)
							->setCellValue("n28",$n28)
							->setCellValue("n29",$n29)
							->setCellValue("n30",$n30)
							->setCellValue("n31",$n31)
							->setCellValue("n32",$n32_)
							->setCellValue("n33",$n33_)
							->setCellValue("n34",$n34_)
							->setCellValue("n35",$n32)
							->setCellValue("n36",$n33)
							->setCellValue("n37",$n34)
							->setCellValue("n38",$n35)
							->setCellValue("n39",$n36)
							->setCellValue("t35",$t35)
							->setCellValue('s13',conv($s13))
							->setCellValue("l12",$this->select0)
							->setCellValue("m12",$this->select1)
							->setCellValue("n12",$this->select2)
							;

	for($s=12;$s<40;$s++){
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'l".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'m".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'n".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'o".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'r".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'s".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'t".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'l".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'m".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'n".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'o".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'r".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'s".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'t".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'l".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'m".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'n".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'o".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'r".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'s".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'t".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	}

	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle('r6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle('r6')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
	$objWriter->save($fileurl.".xlsx");
echo '<script>document.location.href="http://'.$path_root.'/tmp1/'.$fileurl.'.xlsx";</script>';	

}	


function prt_3_can(){
	include("../lib/jtsai.php");
	include("../connections/conn.php");
	$path_root=$_SERVER['HTTP_HOST'];
	$dat=date("YmdHis");
	require_once "../PHPEXCEL/Classes/PHPExcel.php";
	require_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
	$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);	
	
	$fileurl="../tmp1/TI".$this->lot_no;
	$anyl=new anylize;
	$anyl->lot_no=$this->lot_no;
	$anyl->rcv();
	$getsmpdate="SAMPLE 取得   (  " .substr($anyl->sma_time,0,-8)."  年 ".substr($anyl->sma_time,4,-6)."  月  ".substr($anyl->sma_time,6,-4)."  日共  ".$anyl->and_total."  瓶  )";
	$getsmptiem="時間： ".substr($anyl->sma_time,8,-2)."  時  ".substr($anyl->sma_time,10)."  分";
	$operator=get_uname($anyl->and_person);
	$reporttime=ddt($anyl->report_time);
	/// Assay
	
	$tb=get_table("A",$this->pdd_chemical);
	$query="SELECT DISTINCT 
                            ".$tb.".*, Sample_All.SMA_DRUMNO, 
                            Sample_All.SMA_ID
FROM              ".$tb." INNER JOIN
                            Sample_All ON ".$tb.".LotNo = Sample_All.SMA_LOT AND 
                            ".$tb.".SampleNo = Sample_All.SMA_ID
							WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select0."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$smpidA0=$row['SMA_ID'];
	$hno3A0=$row['HNO3'];
	$hno3B0=$row['CAN'];
	$testerA0=$row['Tester'];
	$analyzetimeA0=substr($row['AnalyzeTime'],0,-6);
}	
//  echo $query."</br>";
	$tb=get_table("M13",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select0."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$smpidM130=$row['SMA_ID'];
	$testerM130=$row['Tester'];
	$analyzetimeM130=substr($row['AnalyzeTime'],0,-6);
	$Na0=$row['Na'];
	$Mg0=$row['Mg'];
	$Al0=$row['Al'];
	$K0=$row['K'];
	$Ca0=$row['Ca'];
	$Cr0=$row['Cr'];
	$Mn0=$row['Mn'];
	$Fe0=$row['Fe'];
	$Ni0=$row['Ni'];
	$Co0=$row['Co'];
	$Cu0=$row['Cu'];
	$Zn0=$row['Zn'];
	$Pb0=$row['Pb'];}
	$tb=get_table("P",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select0."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);

$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$PP050=$row['PP05'];
	$PP030=$row['PP03'];
	$PP020=$row['PP02'];
	$PP010=$row['PP01'];
	$smpidP0=$row['SMA_ID'];
	$testerP0=$row['Tester'];
	$analyzetimeP0=substr($row['AnalyzeTime'],0,-6);
}



//#Si
$tb=get_table("TT_Si",$this->pdd_chemical);
	$query="SELECT DISTINCT dbo.Sample_All.SMA_DRUMNO, dbo.".$tb.".*
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select0."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$TTSi0=$row['Si'];
	$testerTTSi0=$row['Tester'];
	$analyzetimeTTSi0=substr($row['AnalyzeTime'],0,-6);
}	
//#B

$tb=get_table("TT_B",$this->pdd_chemical);
	$query="SELECT DISTINCT dbo.Sample_All.SMA_DRUMNO, dbo.".$tb.".*
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select0."')
ORDER BY   dbo.".$tb.".AnalyzeTime";

$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$TTB0=$row['B'];
	$testerTTB0=$row['Tester'];
	$analyzetimeTTB0=substr($row['AnalyzeTime'],0,-6);
}	

    $objPHPExcel = new PHPExcel();
	$fl="../FormList/PrintAnalyze/anycounts.xlsx";
	$objPHPExcel = PHPExcel_IOFactory::load($fl);
	$objPHPExcel->setActiveSheetIndex($this->activesheet);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)
 							->setCellValue("D3",iconv('big5','utf-8',$this->lot_no))
	    					->setCellValue("I3",conv($this->type))
	  						->setCellValue("A5",conv($getsmpdate))
							->setCellValue("A6",conv($getsmptime))
							->setCellValue("R4",conv($operator))
							->setCellValue('O6',$reporttime)
							->setCellValue('N10',$smpidA0)
							->setCellValue('R10',conv($testerA0))
							->setCellValue('S10',$analyzetimeA0)
							->setCellValue('N11',$hno3B0)
							->setCellValue('N12',$hno3A0)
							->setCellValue('N16',$smpidM130)
							->setCellValue('R16',conv($testerM130))
							->setCellValue('S16',$analyzetimeM130)
							->setCellValue('N17',$Na0)
							->setCellValue('N18',$Mg0)
							->setCellValue('N19',$Al0)
							->setCellValue('N20',$K0)
							->setCellValue('N21',$Ca0)
							->setCellValue('N22',$Cr0)
							->setCellValue('N23',$Mn0)
							->setCellValue('N24',$Fe0)
							->setCellValue('N25',$Ni0)
							->setCellValue('N26',$Co0)
							->setCellValue('N27',$Cu0)
							->setCellValue('N28',$Zn0)
							->setCellValue('N29',$Pb0)
							->setCellValue('N34',$smpidP0)
							->setCellValue('R34',conv($testerP0))
							->setCellValue('S34',$analyzetimeP0)
							->setCellValue('N35',$PP010)
							->setCellValue('N36',$PP050)
							->setCellValue('N37',$PP030)
							;
	for($s=10;$s<37;$s++){
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'N".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'R".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'S".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'N".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'R".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'S".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'N".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'R".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'S".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	}
	
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle('s5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle('s5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
	$objWriter->save($fileurl.".xlsx");
echo '<script>document.location.href="http://'.$path_root.'/tmp1/'.$fileurl.'.xlsx";</script>';	

}

function prt_3_hf(){
	session_start();
	include("../lib/jtsai.php");
	include("../connections/conn.php");
	$path_root=$_SERVER['HTTP_HOST'];
	$dat=date("YmdHis");
	require_once "../PHPEXCEL/Classes/PHPExcel.php";
	require_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
	$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);	
	
	$fileurl="../tmp1/TI".$this->lot_no;
	$anyl=new anylize;
	$anyl->lot_no=$this->lot_no;
	$anyl->rcv();
	$b7="SAMPLE 取得   (  " .substr($anyl->sma_time,0,-8)."  年 ".substr($anyl->sma_time,4,-6)."  月  ".substr($anyl->sma_time,6,-4)."  日共  ".$anyl->and_total."  瓶  )";
	$b8="時間： ".substr($anyl->sma_time,8,-2)."  時  ".substr($anyl->sma_time,10)."  分";
	$v6=get_uname($anyl->and_person);
	$s8=ddt($anyl->report_time);
	/// Assay
	$tb=get_table("A",$this->pdd_chemical);
	$query="SELECT DISTINCT 
                            ".$tb.".*, Sample_All.SMA_DRUMNO, 
                            Sample_All.SMA_ID
FROM              ".$tb." INNER JOIN
                            Sample_All ON ".$tb.".LotNo = Sample_All.SMA_LOT AND 
                            ".$tb.".SampleNo = Sample_All.SMA_ID
							WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select0."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$l13=$row['SMA_ID'];
	$l14=$row['Value'];
	$tester11=$row['Tester'];
	$tt11=substr($row['AnalyzeTime'],0,-6);
}	
//  echo $query."</br>";
	$tb=get_table("M13",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select0."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$l16=$row['SMA_ID'];
	$tester12=$row['Tester'];
	$tt12=substr($row['AnalyzeTime'],0,-6);
	$l17=$row['Na'];
	$l18=$row['Mg'];
	$l19=$row['Al'];
	$l20=$row['K'];
	$l21=$row['Ca'];
	$l22=$row['Cr'];
	$l23=$row['Mn'];
	$l24=$row['Fe'];
	$l25=$row['Ni'];
	$l26=$row['Co'];
	$l27=$row['Cu'];
	$l28=$row['Zn'];
	$l29=$row['In'];
	$l30=$row['Ce'];
	$l31=$row['Pb'];
	$l32=$row['B'];
	}
	$tb=get_table("P",$this->pdd_chemical);
		$query="SELECT          dbo.Sample_All.SMA_ID, dbo.".$tb.".*, dbo.Sample_All.SMA_DRUMNO
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select0."')
ORDER BY   dbo.".$tb.".AnalyzeTime";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$l34=$row['PP05'];
	$l35=$row['PP03'];
	$l36=$row['PP02'];
	$l37=$row['PP01'];
	$l33=$row['SMA_ID'];
	$tester13=$row['Tester'];
	$tt13=substr($row['AnalyzeTime'],0,-6);
}
$tb=get_table("TT_B",$this->pdd_chemical);
	$query="SELECT DISTINCT dbo.Sample_All.SMA_DRUMNO, dbo.".$tb.".*
FROM              dbo.".$tb." INNER JOIN
                            dbo.Sample_All ON dbo.".$tb.".LotNo = dbo.Sample_All.SMA_LOT AND 
                            dbo.".$tb.".SampleNo = dbo.Sample_All.SMA_ID
WHERE          (dbo.".$tb.".LotNo = '".$this->lot_no."') AND (dbo.Sample_All.SMA_DRUMNO = '".$this->select0."')
ORDER BY   dbo.".$tb.".AnalyzeTime";

$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query."</br>";
while($row = mssql_fetch_array($result)){
	$l32=$row['B'];
	$tester15=$row['Tester'];
	$tt15=substr($row['AnalyzeTime'],0,-6);
}	

$w14=$tester11."\n".$tester21."\n".$tester31."\n".$tester41;
$w17=$tester12."\n".$tester22."\n".$tester32."\n".$tester42;
$w33=$tester13."\n".$tester23."\n".$tester33."\n".$tester43;
$tester_si=$tester14."\n".$tester24."\n".$tester34."\n".$tester44;
$tester_b=$tester15."\n".$tester25."\n".$tester35."\n".$tester45;
$tester_capn=$tester_capn1."\n".$tester_capn2."\n".$tester_capn3."\n".$tester_capn4;

$x14=$tt11."\n".$tt21."\n".$tt31."\n".$tt41;
$x17=$tt12."\n".$tt22."\n".$tt32."\n".$tt42;
$x33=$tt13."\n".$tt23."\n".$tt33."\n".$tt43;
$d_si=$tt14."\n".$tt24."\n".$tt34."\n".$tt44;
$d_b=$tt15."\n".$tt25."\n".$tt35."\n".$tt45;
$d_capn=$tt_capn1."\n".$tt_capn2."\n".$tt_capn3."\n".$tt_capn4;
    $objPHPExcel = new PHPExcel();
	$fl="../FormList/PrintAnalyze/anycounts.xlsx";
	$objPHPExcel = PHPExcel_IOFactory::load($fl);
	$objPHPExcel->setActiveSheetIndex($this->activesheet);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)
 							->setCellValue("c6",iconv('big5','utf-8',$this->lot_no))
							->setCellValue("o7",conv($v6))
	    					->setCellValue("g6",conv($this->type))
	  						->setCellValue("B8",conv($b7))
							->setCellValue("B9",conv($b8))
							->setCellValue("m9",conv($s8))
							->setCellValue('l13',$l13)
							->setCellValue('l14',$l14)
							->setCellValue('o15',conv($w14))
							->setCellValue('p15',$x14)
							->setCellValue('l16',$l16)
							->setCellValue("l17",$l17)
							->setCellValue("l18",$l18)
							->setCellValue("l19",$l19)
							->setCellValue("l20",$l20)
							->setCellValue("l21",$l21)
							->setCellValue("l22",$l22)
							->setCellValue("l23",$l23)
							->setCellValue("l24",$l24)
							->setCellValue("l25",$l25)
							->setCellValue("l26",$l26)
							->setCellValue("l27",$l27)
							->setCellValue("l28",$l28)
							->setCellValue("l29",$l29)
							->setCellValue("l30",$l30)
							->setCellValue("l31",$l31)
							->setCellValue("l32",$l32)
							->setCellValue("l33",$l33)
							->setCellValue("l34",$l34)
							->setCellValue("l35",$l35)
							->setCellValue("l36",$l36)
							->setCellValue("l37",$l37)
							->setCellValue("o17",conv($w17))
							->setCellValue("p17",$x17)
							->setCellValue("o34",conv($w33))
							->setCellValue("p34",$x33)
;
	for($s=13;$s<38;$s++){
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'l".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'m".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'n".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'o".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'p".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'l".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'m".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'n".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'o".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'p".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'l".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'m".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'n".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'o".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'p".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	}
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle('o7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle('o7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("o7")->applyFromArray($styleThinBlackBorderOutline);
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
	$objWriter->save($fileurl.".xlsx");
echo '<script>document.location.href="http://'.$path_root.'/tmp1/'.$fileurl.'.xlsx";</script>';	
}
}

function get_first($lid,$smpid,$table){
	$query="SELECT         dbo.analyze_first.first
FROM             dbo.analyze_first INNER JOIN
                          dbo.".$table." ON 
                          dbo.analyze_first.lot_no = dbo.".$table.".LotNo AND 
                          dbo.analyze_first.sample_no = dbo.".$table.".SampleNo";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
//echo $query."</br>";
	while($row = mssql_fetch_array($result)){
		return $row['first'];
	}
	
}
?>