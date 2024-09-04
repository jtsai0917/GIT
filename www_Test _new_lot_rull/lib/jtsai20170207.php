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
		$numrows=mssql_num_rows($result);
		while ($row=mssql_fetch_array($result)){
//			return $row[$day];
			$str= $row[$day];
			$aa=explode(";",$str);
			$ret=count($aa);
			if($ret>1){return $ret;}
			else{return substr($str,-3);}
		}
	}
}

class autoani{
public $Total_Monthly_1st;
public $Total_Duration_times;
public $eachday,$individual;
public $eachday_first;
public $Rull_Total;
public $num_rull_total;
public $Rull_Reg;
public $num_rull_reg,$analyze_item; //analyze_item from analyze_design
public $pid,$rull_status,$pdd_chemical;
public $cid;
public $outdate;
public $first_lid;
public $lid,$st;
public $item,$custgroup,$ns;
public $cnt,$last_item;

	function get_rull()
	{
		include("../connections/conn.php");
		$query="SELECT         dbo.Analyze_Rulls.*
		FROM             dbo.Analyze_Rulls where (CTD_CUST_NO LIKE '%".$this->cid."%') AND (PDD_PROD_NO='".$this->pid."')";
		$result=mssql_query($query);
		$numRows = mssql_num_rows($result);
		if($numRows>0)
		{
			while($row=mssql_fetch_array($result))
			{
				$this->custgroup=$row['CTD_CUST_NO'];
				$this->Total_Monthly_1st=$row['Total_Monthly_1st'];
				$this->Total_Duration_times=$row['Total_Duration_times'];
				if ($this->Total_Duration_times<>NULL)
					{
						$this->st=" TOP (".($this->Total_Duration_times-1).") ";
					}
				$this->eachday=$row['eachday'];
				$this->eachday_first=$row['eachday_first'];
				$this->Rull_Total=$row['Rull_Total'];
				$this->Rull_Reg=$row['Rull_Reg'];
				$this->individual=$row['individual'];
			}
			$this->ns=explode(';',$this->cid);
			$n1=explode(',',$this->Rull_Total);
			$this->num_rull_total=count($n1);
			$n2=explode(',',$this->Rull_Reg);
			$this->num_rull_reg=count($n2);
			$this->rull_status=0;
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
			echo "尚未建立客戶分析頻度規則，將使用三項分析</br>";
			$this->rull_status=1;
		}
	}
	
	function get_rull_1(){
		include("../connections/conn.php");
		$query="SELECT         dbo.Analyze_Rulls.*
		FROM             dbo.Analyze_Rulls where (CTD_CUST_NO LIKE '%".$this->cid."%') AND (PDD_PROD_NO='".$this->pid."')";
		$result=mssql_query($query);
		$numRows = mssql_num_rows($result);
		if($numRows>0)
		{
			while($row=mssql_fetch_array($result))
			{
				$this->custgroup=$row['CTD_CUST_NO'];
				$this->Total_Monthly_1st=$row['Total_Monthly_1st'];
				$this->Total_Duration_times=$row['Total_Duration_times'];
				if ($this->Total_Duration_times<>NULL)
					{
						$this->st=" TOP (".($this->Total_Duration_times-1).") ";
					}
				$this->eachday=$row['eachday'];
				$this->eachday_first=$row['eachday_first'];
				$this->Rull_Total=$row['Rull_Total'];
				$this->Rull_Reg=$row['Rull_Reg'];
				$this->individual=$row['individual'];
			}
			$this->ns=explode(';',$this->cid);
			$n1=explode(',',$this->Rull_Total);
			$this->num_rull_total=count($n1);
			$n2=explode(',',$this->Rull_Reg);
			$this->num_rull_reg=count($n2);
			$this->rull_status=0;
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
			$this->rull_status=1;
		}	}
	
function status()
{
	if($this->rull_status!=1)
	{
		
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
				WHERE         (FOD_YEAR_MONTH = '".substr($this->outdate,0,6)."') AND (PDD_PROD_NO = '".$this->pid."')";
		if($this->eachday_first=='Y'){$query.=" AND (FOD_DAY = '".substr($this->outdate,6,2)."')";	}
		$this->ns=explode(';',$this->custgroup);
		$cnt=count($this->ns);
		$str1=" AND (";
		for($i=0;$i<$cnt;$i++){	
			$str1.="(CTD_CUST_NO = '".$this->ns[$i]."') or ";			
		}
		$str1=substr($str1,0,-3).")";
		$query.=" AND (FDM_LOT_NO<>'') order by FOD_DAY desc";	

		$result=mssql_query($query);
		while($row=mssql_fetch_array($result)){
			$this->first_lid=$row['FDM_LOT_NO'];
			$an=new get_from_lot_no;
			$an->lid=$row['FDM_LOT_NO'];
			$an->ani();
//			echo "ANI_ITEMS:".$an->items."</br>";
//			echo "Rull_Total:".$this->Rull_Total."</br>";
			if($this->Rull_Total==$an->items){$this->cnt=1;}
//			echo "CNT:".$this->cnt."</br>";
		}
	}
}
	
function status_()
{
	if($this->rull_status!=1)
	{
		
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
				WHERE         (FOD_YEAR_MONTH = '".substr($this->outdate,0,6)."') AND (PDD_PROD_NO = '".$this->pid."')";
		if($this->eachday_first=='Y'){$query.=" AND (FOD_DAY = '".substr($this->outdate,6,2)."')";	}
		$this->ns=explode(';',$this->custgroup);
		$cnt=count($this->ns);
		$str1=" AND (";
		for($i=0;$i<$cnt;$i++){	
			$str1.="(CTD_CUST_NO = '".$this->ns[$i]."') or ";			
		}
		$str1=substr($str1,0,-3).")";
		$query.=" AND (FDM_LOT_NO<>'') order by FOD_DAY desc";	

		$result=mssql_query($query);
		while($row=mssql_fetch_array($result)){
			$this->first_lid=$row['FDM_LOT_NO'];
			$an=new get_from_lot_no;
			$an->lid=$row['FDM_LOT_NO'];
			$an->ani();
			if($this->Rull_Total==$an->items){$this->cnt=1;}

		}
	}
}

function items()
{
	if($this->rull_status!=1)
	{
		if($this->eachday=='Y')
		{		
			$this->item=$this->Rull_Total;	
		}
		elseif($this->Total_Monthly_1st=='Y')
		{
			$_SESSION['lid']=$this->lid;
			$_SESSION['firstlid']=$this->first_lid;
			if($this->lid==$this->first_lid){$this->item=$this->Rull_Total;	}
			else{$this->item=$this->Rull_Reg;}
			$_SESSION['items']=$this->item;
		}
		elseif(($this->eachday=='N') and ($this->eachday_first=='N') and ($this->Total_Duration_times == NULL)){$this->item=$_SESSION['Reg'];}
		elseif($this->eachday_first=='Y')
		{
			if($this->cnt<>'1'){$this->item=$this->Rull_Total;}
			else{$this->item=$this->Rull_Reg;}
		}
		elseif(($this->eachday=='N') and ($this->eachday_first=='N') and ($this->Total_Monthly_1st=='N') and ($this->Total_Duration_times == NULL))
		{ 
			$this->item=$this->Rull_Reg; echo '全部使用常規';
		}
		else
		{		
			$this->freq();		
		}
	}
	else
	{
		echo "XXXX";
		$this->freq();		//使用三項分析 AMP
	}
}

function items_()
{
	if($this->rull_status!=1)
	{
		$_SESSION['Full']=$this->Rull_Total;
		$_SESSION['Reg']=$this->Rull_Reg;
		if($this->eachday=='Y')
		{		
			$this->item=$this->Rull_Total;	
		}
		elseif($this->Total_Monthly_1st=='Y')
		{
			$_SESSION['lid']=$this->lid;
			$_SESSION['firstlid']=$this->first_lid;
			if($this->lid==$this->first_lid){$this->item=$this->Rull_Total;	}
			else{$this->item=$this->Rull_Reg;}
			$_SESSION['items']=$this->item;
		}
		elseif(($this->eachday=='N') and ($this->eachday_first=='N') and ($this->Total_Duration_times == NULL)){$this->item=$_SESSION['Reg'];}
		elseif($this->eachday_first=='Y')
		{
			if($this->cnt<1){$this->item=$this->Rull_Total;}
			else{$this->item=$this->Rull_Reg;}
		}
		elseif(($this->eachday=='N') and ($this->eachday_first=='N') and ($this->Total_Monthly_1st=='N') and ($this->Total_Duration_times == NULL))
		{ 
			$this->item=$this->Rull_Reg;
		}
		else
		{		
			$this->freq_();		
		}
	}
	else
	{
		$this->freq_();		//使用三項分析 AMP
	}
}

function showitems()
{
	if($this->rull_status!=1)
	{
	include("../connections/conn.php");
	if($this->item==$this->Rull_Total){echo "全項</br>";}
	if($this->item==$this->Rull_Reg){echo "常規</br>";}
	$aa=explode(',',$this->item);
	$query="SELECT DISTINCT ANI_GROUPNAME, ANI_ID, ANI_NICKNAME, ANI_FULLNAME, ANI_INDEX
			FROM             dbo.AnalyzeItem
			WHERE          (ANI_INDEX <>'') and ";
	for($i=0;$i<count($aa);$i++)
	{
		$query.="(ANI_INDEX =".$aa[$i].") OR ";
	}
	$query=substr($query,0,-4);
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);

	while($row = mssql_fetch_array($result))
	{
		echo $row['ANI_INDEX']."~~".$row['ANI_NICKNAME'].'</br>';
	}
	}
}
function analyze_item()
{
	$query="select AND_ITEM from AnalyzeDesign where AND_LOT_NO='".$this->lid."'";
	$result = mssql_query($query);
	while($row = mssql_fetch_array($result))
	{
		$this->analyze_item=$row['AND_ITEM'];
	}
}

function freq()
{
	$ss=new product;
	$ss->pid=$this->pid;
	$ss->getone();
	$this->pdd_chemical=$ss->pdd_chemical;
	if($this->rull_status!=1)
	{
		if($this->Total_Duration_times!=NULL)
		{
			$this->item=$this->Rull_Total;
			$query="SELECT   ".$this->st."
                          dbo.FILLPLAN_OUT_DECIDE.*, 
                          dbo.AnalyzeDesign.AND_LOT_NO, dbo.AnalyzeDesign.AND_ITEM
					FROM             dbo.FILLPLAN_OUT_DECIDE INNER JOIN
                          dbo.AnalyzeDesign ON 
                          dbo.FILLPLAN_OUT_DECIDE.FDM_LOT_NO = dbo.AnalyzeDesign.AND_LOT_NO
					where (";
			for($i=0;$i<count($this->ns);$i++){
			$query.= " (dbo.FILLPLAN_OUT_DECIDE.CTD_CUST_NO = '".$this->ns[$i]."') or";
			}
			$query=substr($query,0,-2).")";
            $query.=" (dbo.FILLPLAN_OUT_DECIDE.PDD_PROD_NO = '".$this->pid."') AND 
                          ({ fn CONCAT(dbo.FILLPLAN_OUT_DECIDE.FOD_YEAR_MONTH, 
                          dbo.FILLPLAN_OUT_DECIDE.FOD_DAY) } <= '".substr($this->outdate,0,8)."')
					order by FILLPLAN_OUT_DECIDE.FDM_CREATE_DATE DESC,{ fn CONCAT(dbo.FILLPLAN_OUT_DECIDE.FOD_YEAR_MONTH, 
                          dbo.FILLPLAN_OUT_DECIDE.FOD_DAY) }";
			$result = mssql_query($query);
			$numRows = mssql_num_rows($result);
			if($numRows>0)
				{
					while($row=mssql_fetch_array($result))
					{
						if($row['AND_ITEM']==$this->Rull_Total){$this->item=$this->Rull_Reg;}
					}
				}
			if(substr($this->item,-1)==','){$this->item=substr($this->item,0,-1);}
		}	
	}
	else
	{
		$query="SELECT DISTINCT 
                          dbo.AnalyzeItem.*
				FROM             dbo.ELEMENT_FORM INNER JOIN
                          dbo.AnalyzeItem ON 
                          dbo.ELEMENT_FORM.ELM_ID = dbo.AnalyzeItem.ANI_INDEX
				WHERE         (dbo.ELEMENT_FORM.PDD_CHEMICAL = '".$this->pdd_chemical."') AND 
                          (dbo.AnalyzeItem.ANI_GROUPNAME = 'A') OR
                          (dbo.AnalyzeItem.ANI_GROUPNAME = 'M13') OR
                          (dbo.AnalyzeItem.ANI_GROUPNAME = 'P')
				ORDER BY  dbo.AnalyzeItem.ANI_ORDER";
				
		$result = mssql_query($query);
		$numRows = mssql_num_rows($result);
		while($row=mssql_fetch_array($result))
			{
				$this->item.=$row['ANI_INDEX'].",";
				echo $row['ANI_INDEX']."~~~".$row['ANI_NICKNAME'].'</br>';
			}
		$this->item=substr($this->item,0,-1);
	}
}

function freq_()
{
	$ss=new product;
	$ss->pid=$this->pid;
	$ss->getone();
	$this->pdd_chemical=$ss->pdd_chemical;
	if($this->rull_status!=1)
	{
		if($this->Total_Duration_times!=NULL)
		{
			$this->item=$this->Rull_Total;
			$query="SELECT   ".$this->st."
                          dbo.FILLPLAN_OUT_DECIDE.*, dbo.AnalyzeDesign.AND_NEED_NO,
                          dbo.AnalyzeDesign.AND_LOT_NO, dbo.AnalyzeDesign.AND_ITEM
					FROM             dbo.FILLPLAN_OUT_DECIDE inner JOIN
                          dbo.AnalyzeDesign ON 
                          dbo.FILLPLAN_OUT_DECIDE.FDM_LOT_NO = dbo.AnalyzeDesign.AND_LOT_NO where (";
			for($i=0;$i<count($this->ns);$i++){
			$query.= " (dbo.FILLPLAN_OUT_DECIDE.CTD_CUST_NO = '".$this->ns[$i]."') or";
			}
			$query=substr($query,0,-2).")";
			$query.=" and (dbo.FILLPLAN_OUT_DECIDE.PDD_PROD_NO = '".$this->pid."') AND 
                          ({ fn CONCAT(dbo.FILLPLAN_OUT_DECIDE.FOD_YEAR_MONTH, 
                          dbo.FILLPLAN_OUT_DECIDE.FOD_DAY) } <= '".substr($this->outdate,0,8)."')
					order by FILLPLAN_OUT_DECIDE.FDM_CREATE_DATE DESC,{ fn CONCAT(dbo.FILLPLAN_OUT_DECIDE.FOD_YEAR_MONTH, 
                          dbo.FILLPLAN_OUT_DECIDE.FOD_DAY) } "; 
	
			$_SESSION['tmp']= $query;
	
			$result = mssql_query($query);
			$numRows = mssql_num_rows($result);
			if($numRows>0)
				{
					while($row=mssql_fetch_array($result))
					{
						if($row['AND_ITEM']==$this->Rull_Total){$this->item=$this->Rull_Reg;}
					}
				}
			if(substr($this->item,-1)==','){$this->item=substr($this->item,0,-1);}

		}	
	}
	else
	{
		$query="SELECT DISTINCT 
                          dbo.AnalyzeItem.*
				FROM             dbo.ELEMENT_FORM INNER JOIN
                          dbo.AnalyzeItem ON 
                          dbo.ELEMENT_FORM.ELM_ID = dbo.AnalyzeItem.ANI_INDEX
				WHERE         (dbo.ELEMENT_FORM.PDD_CHEMICAL = '".$this->pdd_chemical."') AND 
                          (dbo.AnalyzeItem.ANI_GROUPNAME = 'A') OR
                          (dbo.AnalyzeItem.ANI_GROUPNAME = 'M13') OR
                          (dbo.AnalyzeItem.ANI_GROUPNAME = 'P')
				ORDER BY  dbo.AnalyzeItem.ANI_ORDER";
		$result = mssql_query($query);
		$numRows = mssql_num_rows($result);
		while($row=mssql_fetch_array($result))
			{
				$this->item.=$row['ANI_INDEX'].",";
			}
		$this->item=substr($this->item,0,-1);
	}
}

function check_if_fit(){
		

}
}

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
		$z=substr($day,4,2);
		$a="T".$prod->pdd_shortname.$y.exmonth($z).substr($day,6,2).substr($lid,2,4);
		return $a;
//		return "X1";
	}
	else{		
		$y=substr($day,3,1);
		if($prod->pdd_chemical=='CAN')
		{
			$a="T".$prod->pdd_shortname.$y.exmonth(substr($day,4,2)).substr($day,6,2).substr($prod->pdd_type,0,1).substr($prod->pid,-3);
		}
		else
		{
			$a="T".$prod->pdd_shortname.$y.exmonth(substr($day,4,2)).substr($day,6,2).substr($prod->pdd_type,0,1).sprintf("%'03s",$prod->pdd_drum_kg);
		}
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
	public $table,$item,$disc,$pid,$excel,$create_user,$create_datetime,$ani_group;
	function get()
	{
		include("../connections/conn.php");
		$query1="SELECT         *
				FROM             dbo.ani_excel_location
				WHERE         (efm = '".$this->table."') AND  (pid = '".$this->pid."') and (item = '".$this->item."')";
		if(isset($this->ani_group)){
			$query1.=" and (ani_group = '".$this->ani_group."')";}
		if(trim($this->item)=='Lot_No')
		{
		}
		$result1=mssql_query($query1);
		while($row=mssql_fetch_array($result1)){			
			$this->disc=trim($row['disc']);
			$this->excel=$row['excel'];
			$this->create_datetime=$row['create_datetime'];
			$this->create_user=$row['create_user'];
			$this->id=$row['index'];		
		}
		
	}
}


class analyze_first{
	public $lot_no,$sample_no,$create_time,$create_user,$first;
	function get(){
		include("../connections/conn.php");
		$query="SELECT         dbo.analyze_first.*
				FROM             dbo.analyze_first where (lot_no='".$this->lot_no."') and (create_time='".$this->create_time."')";
		if($this->sample_no<>''){$query=$query." and (sample_no='".$this->sample_no."')";}
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result)){
				$_SESSION['first0']=$this->first=$row['first'];
				$this->create_time=$row['create_time'];
				$this->create_user=$row['create_user'];
		}
	}	
}


class read_report
{
	public $url,$location,$item,$efm,$value,$float,$pid,$group,$x,$y,$yy,$ft,$smpid ;
	function read()
	{
		session_start();
		$this->ft =strtoupper(substr($this->url,-4,4));

		if(($this->ft=='XLSX') or ($this->ft=='.XLS'))
		{	  ///read excel2007	
			session_start();
			require_once("../connections/conn.php");
				require_once "../PHPEXCEL/Classes/PHPExcel.php";
				require_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
				$reader= PHPExcel_IOFactory::createReaderForFile($this->url);
				$reader->setReadDataOnly(true);
				$excel= $reader->load($this->url);	
				$sheetCount = $excel->getSheetCount();
				$sheetNames = $excel->getSheetNames();
				$sheet = $excel->getActiveSheet(0); //讀取第一個工作表(編號從 0 開始)
				$colString = $sheet->getHighestColumn(); //最大欄位的英文代號
				$highestColumns = PHPExcel_Cell::columnIndexFromString($colString); //最大欄位的數字編號。A=0, B=1, C=2....
				$highestRows = $sheet->getHighestRow(); //最高行數。從1開始
    			$sheet = $excel->getActiveSheet(0);
   				$colString = $sheet->getHighestColumn(); //最大欄位的英文代號
    			$highestColumns = PHPExcel_Cell::columnIndexFromString($colString); //最大欄位的數字編號。A=0, B=1, C=2....
    			$highestRows = $sheet->getHighestRow(); //最高行數。從1開始    
    			$SheetName = $sheetNames[0];
    			$excelData[$s]['SheetName'] = $SheetName;
     		if((($this->group=='M13') or ($this->group=='M21')) and (($this->pid<>'SUP007-001') or ($this->pid<>'SUP007-000')))
			{
    			$v = null;
				for($x=0;$x<4;$x++)
				{
    				for ($c = 0; $c < $highestColumns; $c++) 
						{ // 0 = A欄
        					$v = trim($sheet->getCellByColumnAndRow($c,$x)->getValue());
							if($v=='Sample Name')
								{
									$lot=trim($sheet->getCellByColumnAndRow($c,$x+1)->getValue());
									$_SESSION['smpno']=brackets($lot);
									$_SESSION['get_lot_no']=brackets_1(trim($lot));
									if(trim($_SESSION['get_lot_no'])<>trim($_GET['lot_no']))
									{
										if($_SESSION['cnt']==0){
											$_SESSION['cnt']=$_SESSION['cnt']+1;
									//		my_msg("上傳文件的LOT NO 錯誤",$_SESSION['lasturl']);
											fun_confirm('Lot NO 錯誤...繼續上傳',$_SESSION['redir']);				
										}
									}
								}
							if(strpos($v," ".$this->item." "))
								{
									$this->value=trim($sheet->getCellByColumnAndRow($c,$x+2)->getValue());
								}
							
						}
				}
			}
    		else
			{
				$query="SELECT         excel
				FROM             dbo.ani_excel_location
				WHERE         (efm = '".$this->efm."') AND (item = 'Lot_No')";
				if($_GET['pdd_prod_no']){$query.=" AND (pid = '".$this->pid."') ";}	
				$result=mssql_query($query);
				$numrows=mssql_num_rows($result);
				if($numrows>0)
				{	
					while($row=mssql_fetch_array($result))
					{ 
							$_SESSION['get_lot_no']=$this->value=$sheet->getCell(trim(strtoupper($row['excel'])))->getValue();	
							if(trim($_SESSION['get_lot_no'])<>trim($_GET['lot_no']))
									{
										if($_SESSION['cnt']==0){
											$_SESSION['cnt']=$_SESSION['cnt']+1;
									//		my_msg("上傳文件的LOT NO 錯誤",$_SESSION['lasturl']);
											fun_confirm('Lot NO 錯誤...繼續上傳',$_SESSION['redir']);	
										}
									}
					}
				}

				$query="SELECT         excel
				FROM             dbo.ani_excel_location
				WHERE         (efm = '".$this->efm."') AND (item = '".$this->item."')";
				$query.=" AND (pid = '".$this->pid."') ";
				$result=mssql_query($query);
				$numrows=mssql_num_rows($result);
				if($numrows>0)
				{	
					$u=0;
					while($row=mssql_fetch_array($result))
					{ 
						$this->location=$row['excel'];
						$this->value=$sheet->getCell(trim(strtoupper($row['excel'])))->getValue();	
						$_SESSION[$u]=$this->value;
						$u=$u+1;
					}
				}
				$this->value=number_format($this->value,6);
				if(trim($this->value)==''){$this->value='Null';}
				$this->value=$sheet->getCell(trim(strtoupper($this->location)))->getValue();
				$ssp=substr($this->value,0,1);	
				$_SESSION[$this->value]=$ssp;
//				if($this->item=='Color'){$_SESSION['tmpsss']=$ssp;}	
				if($ssp=="=")
				{
					$this->value=$sheet->getCell(trim(strtoupper($this->location)))->getCalculatedValue();
				}
				elseif(($ssp==">") or ($ssp=="<"))
				{
					$this->value=$sheet->getCell(trim(strtoupper($this->location)))->getCalculatedValue();
					$this->value=substr($this->value,1);
				}
				else
				{
				$this->value=$sheet->getCell(trim(strtoupper($this->location)))->getValue();
				}
			}
			
		}

		if($this->ft=='.TXT')
		{
			$myfile = fopen($this->url, "r") or die("Unable to open file!");
			while(!feof($myfile)) 
			{
  				$str=fgets($myfile);
				$str1=trim(substr($str,1,9));
				$item_value=trim(substr($str,42,12));
//				echo $str1.":".$item_value.":".$this->item."</br>";
				if($str1==($this->item))
				{
					$_SESSION[$this->item]=$this->value=$item_value;
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
	
	function read_coa(){
		$query="";	
	}
}
///end class

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

function get_column_name($tbl,$i)
{
	$query="SELECT         COLUMN_NAME
			FROM             INFORMATION_SCHEMA.COLUMNS
			WHERE         (TABLE_NAME = '".$tbl."')";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	$field= mssql_num_fields($result);
      for ($x=0 ;$x<$field;$x++)
         {
          echo mssql_field_name($field,$x) . "<br>";
         }
}


function brackets($s1)
{
	for($i=0;$i<strlen($s1);$i++)
	{
		if((substr($s1,$i,1)=='(') or (substr($s1,$i,1)=='[')){$start=$i;}
		if((substr($s1,$i,1)==')') or (substr($s1,$i,1)==']')){$end=$i;}
	}
	return substr($s1,$start+1,$end-$start-1);
}
function brackets_1($s1)
{
	for($i=0;$i<strlen($s1);$i++)
	{
		if((substr($s1,$i,1)=='(') or (substr($s1,$i,1)=='[')){$start=$i;}
		if((substr($s1,$i,1)==')') or (substr($s1,$i,1)==']')){$end=$i;}
	}
	return substr($s1,0,$start);
}



class sample
{
	public $smp_no,$lot_no,$sma_lot,$sma_drumno,$sma_smp,$sma_date,$sma_time,$isrework;
	
	function write()
	{
		$query="INSERT INTO dbo.Sample_All
                            (SMA_ID, SMA_TIMES, SMA_SERVICE, SMA_LOT, SMA_USER, SMA_OUT, SMA_SMP, SMA_ANA, SMA_BACK, 
                            SMA_SAVE, SMA_SAVE_TIME, ISREWORK, SMA_FINISH, SMA_JUNK, SMA_JUNK_D, SMA_DRUMNO, 
                            SMA_SERIAL_NO, DHN_DRUM_NO, SMA_PREPSAMPLE_MAN, SMA_PREPSAMPLE_DATE, 
                            SMA_PREPSAMPLE_BACK_DATE, SMA_PREPSAMPLE_BACK_MAN, SMA_RETURN, SMA_RETURN_MAN)
							VALUES          ('".$_POST['sma_id']."',".$smp_times.",".$smp_service.",'".$_POST['lot_no']."',
							'".$_SESSION['s1']."',NULL,'".$_SESSION['uid']."',NULL,NULL,'".date("Ymd")."','".date("His")."','".$isrework."',
							NULL,NULL,NULL,'".$_POST['sn']."',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL)";
		$result = mssql_query($query);
		if (!$result) 
		{
    		echo "無法新增樣品瓶</br>";
 		 }
		else
		{
		$query="UPDATE          dbo.Sample
				SET                   SMP_TIMES ='".$smp_times."', SMP_LOT ='".$_POST['lot_no']."', SMP_DRUMNO = '".$_POST['sn']."', SMP_USER ='".$_SESSION['s1']."'
				, SMP_SMP ='".$_SESSION['uid']."', SMP_SAVE ='".date("Ymd")."', SMP_SAVE_TIME ='".date("His")."' 
				WHERE          (SMP_ID = '".$_POST['sma_id']."')";
		$result = mssql_query($query);
			if (!$result) 
			{
    			echo "無法更新Sample資料表</br>";
  			}
		}
	}
		
	function get()
	{
		$query="SELECT TOP(1) *
				FROM             dbo.Sample_All
				WHERE         (SMA_ID='".$lot_no."') ORDER BY  SMA_TIMES DESC";
		$result=mssql_query($query);
		$numRows1 = mssql_num_rows($result);
		while($row=mssql_fetch_array($result)){
			$this->sma_time=$row['SMA_TIMES'];
			$this->sma_lot=$row['SMA_LOT'];
			$this->sma_drumno=$row['SMA_DRUMNO'];
			$this->sma_smp=$row['SMA_SMP'];
			$this->sma_date=$row['SMA_SAVE'];
			$this->sma_time=$row['SMA_SAVE_TIME'];
			$this->isrework=$row['ISREWORK'];
		}
	}
	
	function selections()
	{
		$gn=new get_from_lot_no;
		$gn->lid=$this->lot_no;
		$gn->ani();
		$gn->cid();
		$gn->pdd_type;
		if(($gn->pdd_chemical!='NH4OH') and (($gn->pdd_type=='DM') or ($gn->pdd_type=='BTL')))
		{
			$drum_qty=new fillplan_out_decide;
			$drum_qty->lot_no=$this->lot_no;
			$drum_qty->get();
			$_SESSION['drum_qty']=$drum_qty->drum_qty;
			$x=floor($drum_qty->drum_qty/8);
			echo '<option value="0~0">0~0</option>';
			for($i=1;$i<=$x;$i++)
			{
				$num1=(($i-1)*8+1)."~".($i*8);
				if($num1==trim($_SESSION['drumno'])){echo '<option value="'.$num1.'" selected="selected">'.$num1.'</option>';}
				else{echo '<option value="'.$num1.'">'.$num1.'</option>';}
			}
		}
		if(($gn->pdd_type=='DM') and ($gn->pdd_chemical=='NH4OH'))
		{
			$query="SELECT  dbo.FILLPLAN_OUT_DECIDE.*       
			FROM             dbo.FILLPLAN_OUT_DECIDE INNER JOIN
                          dbo.PRODUCT_DATA ON 
                          dbo.FILLPLAN_OUT_DECIDE.PDD_PROD_NO = dbo.PRODUCT_DATA.PDD_PROD_NO
			WHERE         (dbo.FILLPLAN_OUT_DECIDE.FDM_SPECIFIC = 'DM') and (dbo.FILLPLAN_OUT_DECIDE.FDM_LOT_NO = '".$_GET['lot_no']."')";
			$result=mssql_query($query);
			while ($row=mssql_fetch_array($result))
			{
				$num=$row['FDM_QTY_DRUM'];
			}
			$x=floor($num/8);
			for($i=0;$i<=$x;$i++)
			{
				$num1=($i*8)."~".($i*8);
				if($num1==trim($_SESSION['drumno'])){echo '<option value="'.$num1.'" selected="selected">'.$num1.'</option>';}
				else{echo '<option value="'.$num1.'">'.$num1.'</option>';}
			}
		}
		if($gn->pdd_type=='LY')
		{
			echo '<option value="1~8">1~8</option>';
		}	
	}
	
	function get_from_sample_id()
	{
		$query="select * from Sample where (SMP_ID='".$this->smp_no."')";

		$result=mssql_query($query);
		while ($row=mssql_fetch_array($result))
		{
			$this->sma_time=$row['SMP_TIMES'];
		}
	}
	
}


class fillplan_out_decide
{
	public $lot_no,$drum_qty;
	function get()
	{
		$query="SELECT         dbo.FILLPLAN_OUT_DECIDE.FDM_QTY_DRUM
				FROM             dbo.AnalyzeDesign INNER JOIN
                          dbo.FILLPLAN_OUT_DECIDE ON 
                          dbo.AnalyzeDesign.AND_LOT_NO = dbo.FILLPLAN_OUT_DECIDE.FDM_LOT_NO
				WHERE         (dbo.AnalyzeDesign.AND_LOT_NO = '".$this->lot_no."')";	
		$result=mssql_query($query);
		$numRows1 = mssql_num_rows($result);
		while($row=mssql_fetch_array($result)){
			$this->drum_qty=$row['FDM_QTY_DRUM'];
		}
	}
}

class re_analyze
{
	public $lot_no,$ras_no;
	
	function get_()
	{
		$query="SELECT dbo.REANALYZE_REQUEST_DATA.* FROM dbo.REANALYZE_REQUEST_DATA WHERE (RAN_LOT_NO Like '%".$this->lot_no."%')";
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result))
		{
			$this->ras_no=$row['RAS_NO'];
		}
	}
	
}

class out_plan_from_file
{
	public $po_no,$deli_cust,$dep_no,$order_cust,$monetary,$exchange_rate,$order_no,$oaf_sn,$order_date,$contact_man,$contact_tel,$deli_addr,$pdd_prod_no,$unit,$price,$package,$eta_date,$acc_id,$memo;
	function select(){
	$query="SELECT          OUT_PLAN_FROM_FILE.*
			FROM              OUT_PLAN_FROM_FILE
			WHERE          (OPM_ORDER_NO = '".$this->order_no."') and (PDD_PROD_NO='".$this->pdd_prod_no."')";
	$result=mssql_query($query);
		$numRows1 = mssql_num_rows($result);
		while($row=mssql_fetch_array($result)){
			$this->memo =$row['OAF_MEMO'];
		}
	}
}

class read_table
{
	public $table,$row_name,$value,$LotNo;
	function read()
	{

		$query="select ".($this->row_name)." from ".($this->table)." where (LotNo='".($this->LotNo)."') order by AnalyzeTime";
		
		$result=mssql_query($query);
		
		while($row=mssql_fetch_array($result)){
			$this->value =$row[$this->row_name];
			number_format($this->value,8);
			$this->row_name;
		}
	}	
}

class difference_too_large
{
	public $table,$flag,$uid,$LotNo,$create_time,$ani_group,$item,$numrows;
	function read()
	{
		$query="select * from ".$this->table." where (LotNo='".$this->LotNo."')";	
		$result=mssql_query($query);
		$this->numrows = mssql_num_rows($result);
		while($row=mssql_fetch_array($result)){
			$this->flag =$row['flag'];
			$this->uid =$row['uid'];
			$this->create_time =$row['create_time'];
			$this->ani_group =$row['ani_group'];
			$this->item =$row['item'];
		}
	}
	function status()
	{
		$query="select * from difference_too_large where (LotNo='".$this->LotNo."')";	
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result))
		{
			$this->item=$this->item.$row['item'];
			$this->flag=$row['flag'];
		}			
	}
	
	function write()
	{
		$query="INSERT INTO difference_too_large
                            ([table], ani_group, LotNo, flag, uid, create_time,item)
				VALUES          (N'".$this->table."', N'".$this->ani_group."', N'".$this->LotNo."', N'1',
				 N'".$this->uid."', N'".$this->create_time."', N'".$this->item."')";
		$results=mssql_query($query);
	}
}

class auth_user
{
	public $cbt_no,$cbt_name,$cbt_inward,$cbt_keyin,$cbt_cfm1,$uid,$user_group,$keyin_ok;
	
	function user_auth_group()
	{
		include("../connections/conn.php");
		$query="SELECT          AUT_GROUP
				FROM              EMPLOYEE_AUTHORITY
				WHERE          (EMP_NO = '".$this->uid."')";
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result))
		{
			$this->user_group=$row['AUT_GROUP'];
		}
	}
	
	function keyin_isfit()
	{
		$aa=explode(";",($this->user_group));
		$ret=count($aa);
		for($i=0;$i<$ret;$i++)
		{
			$str="/".$aa[$i]."/";
			$this->keyin_ok=preg_match($str, $this->cbt_keyin);	
		}
	}
	
	function capability()
	{
		include("../connections/conn.php");
		$query="SELECT          CAPABILITY_DATA.*
				FROM              CAPABILITY_DATA
				WHERE          (CBT_NO = '".$this->cbt_no."')";	
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result))
		{
			$this->cbt_name=$row['CBT_NAME'];
			$this->cbt_keyin=$row['CBT_KEYIN'];	
		}
	}
}

class analyze_repor
{
	public $lot_no,$str,$group_str;
	
	function group_string()
	{
		include("../connections/conn.php");
		$query="SELECT          AND_ITEM
		FROM              AnalyzeDesign
		WHERE          (AND_LOT_NO = '".$this->lot_no."')";
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result))
		{$this->str=$row['AND_ITEM'];}	

		$aa=explode(',',$this->str);
		$query="SELECT DISTINCT ANI_GROUPNAME
		FROM              AnalyzeItem
		WHERE          ";
		for($i=0;$i<count($aa);$i++){
		if ($i<(count($aa)-1)){
			$query.="(ANI_INDEX =".$aa[$i].") OR ";}
		else {$query.="(ANI_INDEX =".$aa[$i].")";}	
		}
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result))
		{$this->group_str.=",";}
	}
}

class tank_data
{
	public $pid,$lid;
	function show()
	{
		echo '<select name="PROD_TANK" id="PROD_TANK">';
		echo '<option value="0"></option>';
		$query="SELECT          TANK_DATA.*
				FROM              TANK_DATA
				WHERE          (PDD_PROD_NO = '".$this->pid."')";
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result))
		{
		if($row['PROD_TANK']==$_SESSION['PROD_TANK']){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['ANI_GROUPNAME'].'" '.$select.'>'.$row['PROD_TANK']."(".$row['TANK_DESC'].')</option>';
		}
		echo '</select>';
	}
}
?>