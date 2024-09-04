<?php
		include("../connections/conn.php");
		include_once("fun.php");
		session_start();
		
class anylize{
public $lot_no;
public $sma_time;
public $and_total;
public $and_person;
public $report_time;
	function rcv(){
		include("../connections/conn.php");
		$query="SELECT          dbo.AnalyzeDesign.*
FROM              dbo.AnalyzeDesign
WHERE          (AND_LOT_NO = '".$this->lot_no."')";
	$result = mssql_query($query);
	while($row = mssql_fetch_array($result)){
		$this->sma_time=$row['AND_SMP_DATETIME'];
		$this->and_total=$row['AND_TOTAL'];
		$this->and_person=$row['AND_PERSON'];
		$this->report_time=$row['AND_REPORT_DATETIME'];
	}
	}
}

class sag_in{
public $rull;	
public $lid;
public $sagno;
public $cbtno;
public $sag_creator;
public $sag_creat_time;
public $sag_finished_time;
public $comfirm_count;

function sag(){
include("../connections/conn.php");
$query="SELECT          SIGN_AGREE.*
		FROM              SIGN_AGREE
		WHERE          (SIGN_AGREE.SAG_NO = ".$this->sagno.")";
		$result=mssql_query($query);
		while ($row=mssql_fetch_array($result)){
			$this->sag_creat_time=$row['SAG_CREATE_TIME'];
			$this->sag_creator=$row['SAG_CREATE_MAN'];
			$this->cbtno=$row['CBT_NO'];
			$this->sag_creator=$row['SAG_FINISHED_TIME'];
			$this->comfirm_count=$row['SAG_COMFIRM_COUNT'];
		}
	}	
}

class FLM{
	public $day;
	public $pid;
	public $cid;
	public $ym;
	function lot($day){
		include("../connections/conn.php");
		$query="SELECT    ".$day." 
FROM              dbo.FILLPLAN_LORRY_MONTH
WHERE          (PDD_PROD_NO = '".$this->pid."') AND (CTD_CUST_NO = '".$this->cid."') AND (FLM_YEAR_MONTH = '".$this->ym."')
ORDER BY   FLM_YEAR_MONTH DESC";
		$result=mssql_query($query);
		while ($row=mssql_fetch_array($result)){
			return $row[$day];
		}

	}
}

class autoani{
public $Total_Monthly_1st;
public $Total_Duration_times;
public $eachday;
public $eachday_first;
public $Rull_Total;
public $num_rull_total;
public $Rull_Reg;
public $num_rull_reg;
public $pid;
public $cid;
public $outdate;
public $first_lid;
public $lid,$st;
public $item,$custgroup,$ns;

	function get_rull(){
		$query="SELECT         dbo.Analyze_Rulls.*
		FROM             dbo.Analyze_Rulls where (CTD_CUST_NO LIKE '%".$this->cid."%') AND (PDD_PROD_NO='".$this->pid."')";
		$result=mssql_query($query);
		$numRows = mssql_num_rows($result);
		if($numRows>0){
		while($row=mssql_fetch_array($result))
		{
		$this->custgroup=$row['CTD_CUST_NO'];
		$this->Total_Monthly_1st=$row['Total_Monthly_1st'];
		$this->Total_Duration_times=$row['Total_Duration_times'];
		if ($this->Total_Duration_times<>NULL){$this->st=" TOP (".($this->Total_Duration_times-1).") ";}
		$this->eachday=$row['eachday'];
		$this->eachday_first=$row['eachday_first'];
		$this->Rull_Total=$row['Rull_Total'];
		$this->Rull_Reg=$row['Rull_Reg'];
		}
		$this->ns=explode(';',$this->custgroup);
		$n1=explode(',',$this->Rull_Total);
		$this->num_rull_total=count($n1);
		$n2=explode(',',$this->Rull_Reg);
		$this->num_rull_reg=count($n2);
		echo "客戶：".get_cust_name($_GET['CTD_CUST_NO']);
		}
		else
		{
			
			$this->Total_Monthly_1st=$row['Total_Monthly_1st'];
			$this->Total_Duration_times=$row['Total_Duration_times'];
			$this->eachday=$row['eachday'];
			$this->Rull_Total=$row['Rull_Total'];
			$this->Rull_Reg=$row['Rull_Reg'];
			$n1=explode(',',$this->Rull_Total);
			$this->num_rull_total=count($n1);
			$n2=explode(',',$this->Rull_Reg);
			$this->num_rull_reg=count($n2);		
			echo "尚未建立客戶分析頻度規則，請先建立規則以利自動產生客戶產品檢驗項目";
		}
	
/*	
	echo "全檢項目= ".$this->Rull_Total."</br>";
	echo "常規項目= ".$this->Rull_Reg."</br>";   
*/
	}
	
	function status(){
		$query="SELECT    FOD_UNI, FOD_YEAR_MONTH, FOD_DAY, FOD_O_YEAR_MONTH, 
                          FOD_O_DAY, CTD_CUST_NO, CTD_CUST_NO_GROUP, PDD_PROD_NO, 
                          FDM_SERIAL_NO, FDM_LY_NO, FDM_CREATE_DATE, FDM_CREATOR, 
                          FDM_SPECIFIC, FDM_QTY_DRUM, FDM_QTY, FDM_QTY_UNIT, 
                          FDM_EXPECT_DATE, FDM_LOT_NO, FDM_ITEM, FDM_RESULT_DATE, 
                          FDM_COA_BEFORE, FDM_OUT_DATE, FDM_BACK_DATE, FDM_SAM_BEFORE, 
                          FDM_SAM_BEF_CNT, FDM_SAM_CNT, FDM_ATTACH_CNT, FDM_TRANSFROM, 
                          FDM_PRINT_OUT, FDM_FILLED_B_DATE, FDM_FILLED_E_DATE, 
                          FDM_FILLED_MAN, FDM_FILLED_LY_QTY, FDM_FILLED_DM_QTY, 
                          FDM_CHK_SAM_PURGE, FDM_CHK_CHG_PURGE, FDM_PURGE, 
                          FDM_MOD_DATE, FDM_P_TOTO, FOD_BAR_PRN_DATE, 
                          FDM_REAL_OUT_TIME, FDM_REAL_RETURN_TIME
				FROM             dbo.FILLPLAN_OUT_DECIDE
				WHERE         (FOD_O_YEAR_MONTH = '".substr($this->outdate,0,6)."') AND (CTD_CUST_NO = '".$this->cid."') AND (PDD_PROD_NO = '".$this->pid."')
				AND (FDM_LOT_NO<>'') order by FOD_O_DAY desc";
				
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result)){
			$this->first_lid=$row['FDM_LOT_NO'];
		}
	}
	
	function items(){
		if($this->eachday=='Y'){$this->item=$this->Rull_Total;}
		elseif($this->Total_Monthly_1st=='Y')
		{
			$_SESSION['lid']=$this->lid;
			$_SESSION['firstlid']=$this->first_lid;
			if($this->lid==$this->first_lid){$this->item=$this->Rull_Total;	}
				else{$this->item=$this->Rull_Reg;	}
				$_SESSION['this_items']=$this->item;
			}
		elseif(($this->Total_Monthly_1st=='Y')  and ($this->Total_Duration_times!=NULL))
		{					
		 	if($this->lid==$this->first_lid){$this->item=$this->Rull_Total;}
			else{$this->freq();	}
		}
		elseif($this->eachday_first=='Y')
		{
			for($x=0;$x<count($this->ns);$x++)
			{
				$str.=" dbo.FILLPLAN_OUT_DECIDE.CTD_CUST_NO ='".$this->ns[$x]."' or";
			}
			$str=substr($str,0,-2);
			$query="SELECT   ".$this->st."
                          dbo.FILLPLAN_OUT_DECIDE.*, 
                          dbo.AnalyzeDesign.AND_LOT_NO, dbo.AnalyzeDesign.AND_ITEM
		FROM             dbo.FILLPLAN_OUT_DECIDE INNER JOIN
                          dbo.AnalyzeDesign ON 
                          dbo.FILLPLAN_OUT_DECIDE.FDM_LOT_NO = dbo.AnalyzeDesign.AND_LOT_NO
		WHERE         (".$str.") AND (PDD_PROD_NO = '".$this->pid."') AND 
                      (FOD_O_YEAR_MONTH+FOD_O_DAY='".substr($this->outdate,0,8)."')
		ORDER BY  dbo.FILLPLAN_OUT_DECIDE.FDM_OUT_DATE desc";
		$result=mssql_query($query);
		$numrows=mssql_num_rows($result);
		if($numrows==0){$this->item=$this->Rull_Total;}
			else{$this->item=$this->Rull_Reg;}
		}
		elseif(($this->eachday=='N') and ($this->eachday_first=='N') and ($this->Total_Monthly_1st=='N') and ($this->Total_Duration_times == NULL))
		{ $this->item=$this->Rull_Reg; echo '全部使用常規';}
		else
		{		
		 	$this->freq();		
		}
		if($this->item=$this->Rull_Total){$_SESSION['AND_TOTAL']=9;}
		if($this->item=$this->Rull_Reg){$_SESSION['AND_TOTAL']=7;}
	}
	
	function showitems(){
		include("../connections/conn.php");
		if($this->item==$this->Rull_Total){echo "全項</br>";}
		if($this->item==$this->Rull_Reg){echo "常規</br>";}
		$aa=explode(',',$this->item);
	$query="SELECT DISTINCT ANI_GROUPNAME, ANI_ID, ANI_NICKNAME, ANI_FULLNAME, ANI_INDEX
FROM             dbo.AnalyzeItem
WHERE          (ANI_INDEX <>'') and ";
for($i=0;$i<count($aa);$i++){
	$query.="(ANI_INDEX =".$aa[$i].") OR ";}
	$query=substr($query,0,-4);

$result = mssql_query($query);
$numRows = mssql_num_rows($result);

while($row = mssql_fetch_array($result)){
echo $row['ANI_INDEX']."~~".$row['ANI_NICKNAME'].'</br>';
}
	}

function freq(){
	if($this->Total_Duration_times!=NULL){
	$this->item=$this->Rull_Reg;
$query="SELECT   ".$this->st."
                          dbo.FILLPLAN_OUT_DECIDE.*, 
                          dbo.AnalyzeDesign.AND_LOT_NO, dbo.AnalyzeDesign.AND_ITEM
		FROM             dbo.FILLPLAN_OUT_DECIDE INNER JOIN
                          dbo.AnalyzeDesign ON 
                          dbo.FILLPLAN_OUT_DECIDE.FDM_LOT_NO = dbo.AnalyzeDesign.AND_LOT_NO
		Where (dbo.FILLPLAN_OUT_DECIDE.CTD_CUST_NO = '".$this->cid."') AND 
                          (dbo.FILLPLAN_OUT_DECIDE.PDD_PROD_NO = '".$this->pid."') AND 
                          ({ fn CONCAT(dbo.FILLPLAN_OUT_DECIDE.FOD_O_YEAR_MONTH, 
                          dbo.FILLPLAN_OUT_DECIDE.FOD_O_DAY) } <= '".substr($this->outdate,0,8)."')
		ORDER BY  { fn CONCAT(dbo.FILLPLAN_OUT_DECIDE.FOD_O_YEAR_MONTH, 
                          dbo.FILLPLAN_OUT_DECIDE.FOD_O_DAY) } DESC";
$_SESSION['tmp']=$query;
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
if($numRows>0){
	while($row=mssql_fetch_array($result)){
//		$_SESSION['tmp_and_item']=$row['AND_ITEM'];
	//	$_SESSION['Rull_Total']=$this->Rull_Total;
		//$_SESSION['Rull_Reg']=$this->Rull_Reg;
		if($row['AND_ITEM']==$this->Rull_Total){$this->item=$this->Rull_Reg;}
		if($row['AND_ITEM']==$this->Rull_Reg){$this->item=$this->Rull_Total;}
	}
}
if(substr($this->item,-1)==','){$this->item=substr($this->item,0,-1);}
}	}
}//class

class product{
public $pid;
public $pdd_type;	
public $pdd_name;
public $pdd_style;
public $pdd_package;
public $pdd_chemical;
public $pdd_shortname;
public $pdd_class;
public $pdd_liter_kg;
public $pdd_consistency;
public $pdd_drum_kg;
public $pdd_unit;
function getone(){
		$query="SELECT         dbo.PRODUCT_DATA.*
		FROM             dbo.PRODUCT_DATA where (PDD_PROD_NO='".$this->pid."')";
		$result = mssql_query($query);
		$numRows = mssql_num_rows($result);
		while($row=mssql_fetch_array($result)){
			$this->pdd_type=$row['PDD_TYPE'];
			$this->pdd_name=$row['PDD_PROD_NAME'];
			$this->pdd_style=$row['PDD_STYLE'];
			$this->pdd_package=$row['PDD_PACKAGE'];
			$this->pdd_chemical=$row['PDD_CHEMICAL'];
			$this->pdd_shortname=$row['PDD_PROD_SHORT_NAME'];
			$this->pdd_class=$row['PDD_CLASS'];
			$this->pdd_liter_kg=$row['PDD_LITER_KG'];
			$this->pdd_drum_kg=$row['PDD_DRUM_KG'];
			$this->pdd_consistency=$row['PDD_CONSISTENCY'];
			$this->pdd_unit=$row['PDD_UNIT'];
		}
	}
}

//// standalone functions

function genlot($pid,$day,$lid){
	$prod=new product;
	$prod->pid=$pid;
	$prod->getone();  
	if($prod->pdd_type=='LY'){
		$y=substr($day,3,1);
		$a="T".$prod->pdd_shortname.$y.exmonth(substr($day,4,2)).substr($day,6,2).substr($lid,2,4);
		return $a;
//		return "X1";
	}
	else{		
		$y=substr($day,3,1);
		$a="T".$prod->pdd_shortname.$y.exmonth(substr($day,4,2)).substr($day,6,2).substr($prod->pdd_type,0,1).sprintf("%'03s",$prod->pdd_drum_kg);
		return $a;
//		return "X2";
	}
}
		
class analist{
public $lot_no;
public $sma_time;
public $and_total;
public $and_person;
public $report_time;
public $start;
public $endtime;

	function rcv(){
		include("../connections/conn.php");
		$query="SELECT          dbo.AnalyzeDesign.*
FROM              dbo.AnalyzeDesign
WHERE          (AND_LOT_NO = '".$this->lot_no."')";
if($this->start<>''){$query=$query." and (AND_REPORT_DATETIME<'".$this->start."')";}
if($this->endtime<>''){$query=$query." and (AND_REPORT_DATETIME>'".$this->endtime."')";}
	$result = mssql_query($query);
	while($row = mssql_fetch_array($result)){
		$this->sma_time[]=$row['AND_SMP_DATETIME'];
		$this->and_total[]=$row['AND_TOTAL'];
		$this->and_person[]=$row['AND_PERSON'];
		$this->report_time[]=$row['AND_REPORT_DATETIME'];
	}
	}
}

class ani_excel{
	public $table,$item,$disc,$pid,$excel,$create_user,$create_datetime;
	function get()
	{
		include("../connections/conn.php");
		$query1="SELECT         *
				FROM             dbo.ani_excel_location
				WHERE         (efm = '".$this->table."') AND  (pid = '".$this->pid."') and (item = '".$this->item."')";
		$result1=mssql_query($query1);
		while($row=mssql_fetch_array($result1)){			
			$this->disc=$row['disc'];
			$this->excel=$row['excel'];
			$this->create_datetime=$row['create_datetime'];
			$this->create_user=$row['create_user'];
			
		}
		
	}
}

class analyze_first{
	public $lot_no,$sample_no,$create_time,$create_user,$first;
	function get(){
		include("../connections/conn.php");
		$query="SELECT         dbo.analyze_first.*
				FROM             dbo.analyze_first where (lot_no='".$this->lot_no."')";
		if($this->sample_no<>''){$query=$query." and (sample_no='".$this->sample_no."')";}
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result)){
				$this->first=$row['first'];
				$this->create_time=$row['create_time'];
				$this->create_user=$row['create_user'];
		}
	}	
}


class read_report{
	public $url,$location,$item,$efm,$value,$float,$pid,$group,$x,$y,$yy,$ft ;
	function read()
	{
		session_start();
		$this->ft =strtoupper(substr($this->url,-4,4));
//		echo $filetype."</br>";
		if(($this->ft=='XLSX') or ($this->ft=='.XLS')){	  ///read excel2007	
		require_once("../connections/conn.php");
		require_once "../PHPEXCEL/Classes/PHPExcel.php";
		require_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
		$reader= PHPExcel_IOFactory::createReaderForFile($this->url);
		$reader->setReadDataOnly(true);
		$excel= $reader->load($this->url);
		$sheet=$excel->getActiveSheet(0);
		$query="SELECT         excel
				FROM             dbo.ani_excel_location
				WHERE         (efm = '".$this->efm."') AND (pid = '".$this->pid."') AND (item = '".$this->item."')";
				$_SESSION['tmp']=$query;
		$result=mssql_query($query);
		$numrows=mssql_num_rows($result);
		if($numrows>0){	
		while($row=mssql_fetch_array($result)){ 
			$this->location=$row['excel'];
			$this->value=$sheet->getCell(trim(strtoupper($row['excel'])))->getValue();	
			
			}
		}
		$this->value=number_format($this->value,6);
		if(trim($this->value)==''){$this->value='Null';}
			$this->value=$sheet->getCell(trim(strtoupper($this->location)))->getValue();
			$_SESSION['tmp']=$ssp=substr($this->value,0,1);			
			if($ssp=="="){
			$this->value=$sheet->getCell(trim(strtoupper($this->location)))->getCalculatedValue();}}
			else{
			$this->value=$sheet->getCell(trim(strtoupper($this->location)))->getValue();}


		if($this->ft=='.xxXLS'){   ///read excel5
		session_start();
//		echo $filetype."</br>";
		require_once("../connections/conn.php");
		require_once "../PHPEXCEL/Classes/PHPExcel.php";
		require_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
		$query="SELECT         excel
				FROM             dbo.ani_excel_location
				WHERE         (efm = '".$this->efm."') AND (pid = '".$this->pid."') AND (item = '".$this->item."')";
		$_SESSION['tmp']=$query;
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result)){ $this->location=$row['excel'];}
			if(substr($this->location,0,1)<>'='){
			$this->value=$this->excel5_();}
			else{$this->value=$this->excel5_();}
			if(trim($this->value)==''){$this->value='Null';}
			if($this->group=='A'){
			my_msg("檔案格式錯誤",$_SESSION['retir']);	
		}
		}
		
	elseif($this->ft=='.TXT')
	{
		$x=1;
		$myfile = fopen($this->url, "r") or die("Unable to open file!");
		while(!feof($myfile)) {
		$str=trim(fgets($myfile));  
		$aa=explode(' ',$str,-1);		
		if($aa[0]==$this->item){
		$_SESSION['tmp']="line: ". $aa[0];
		$this->value=number_format(substr($str,36,14),7);
		
		$this->value=number_format($this->value,6);
		if(trim($this->value)==''){$this->value='Null';}
		$x=$x+1;
		}
	}
	fclose($myfile);			
	}
	}
	
	function excel5_(){
		$this->location=trim(strtoupper($this->location));
 		include_once("reader_excel5.php");
		$this->x=substr($this->location,0,1);
		$this->y=substr($this->location,1);
		$this->x=ord($this->x)-64;
		$x=substr($this->location,0,1);
		$y=substr($this->location,1);
			$excel = new Spreadsheet_Excel_Reader;		
			$excel->read($this->url);
			$this->value=$excel->sheets[0]['cells'][$this->y][$this->x];
			if(trim($this->value)==''){$this->value='Null';}
			$this->value=number_format($this->value,6);
			
				return $this->value;	
			
	}
}

function get_analyze_sn($tb,$lot)
{
	include("../connections/conn.php");
	$query="SELECT          *
			FROM             dbo.".$tb."
			WHERE         (LotNo = '".$lot."') and (SampleNo='".$smpno."')
			ORDER BY  SerialNo";
	$result=mssql_query($query);
	$numrows=mssql_num_rows($result);
	if($numrows>0){
	while($row=mssql_fetch_array($result)){
		$ss=$row['SerialNo'];
	}
	return $ss+1;
	}
	else{return 0;}
}

function new_analyze_sn($tb,$lot)
{
	include("../connections/conn.php");
	$query="SELECT          *
			FROM             dbo.".$tb."
			WHERE         (LotNo = '".$lot."')
			ORDER BY  SerialNo";
	$result=mssql_query($query);
	$numrows=mssql_num_rows($result);
	if($numrows>0){
		return $numrows+1;
	}
	else{return 1;}
}

function changelog($form,$type,$userid,$time){
	$query="INSERT INTO dbo.changelog
                          (form, type, userid, item, value, time)
VALUES         ('".$form."','".$type."','".$userid."','".$time."')";	
$result=mssql_query($query);
}

function scriptconfirm($str,$str1,$link)
{
	echo '
	<script>
if(confirm("'.$str.'"))
{
document.location.href="'.$link.'";
}
else
{
alert("'.$str1.'");
}
</script> ';	
}

?>