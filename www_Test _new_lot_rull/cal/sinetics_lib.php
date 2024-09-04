<?php
session_start();
include("../connections/conn.php");
class sinetics{
	public $lotno,$table,$value1,$value2,$value3,$value4,$value5,$value6,$value7,$value8;	
	function x1(){
		$query=	"SELECT  TestData,OrgField FROM QC_LotData WHERE (LotNo = '".$this->lotno."') AND (OrgTable = '".$this->table."') ORDER BY ID";
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result)){
			if($row['OrgField']=='PMS01'){$this->value1=$row['TestData'];}
			if($row['OrgField']=='PMS04'){$this->value2=$row['TestData'];}
			if($row['OrgField']=='PMS07'){$this->value3=$row['TestData'];}
			if($row['OrgField']=='PMS12'){$this->value4=$row['TestData'];}
			if($row['OrgField']=='FPMS01'){$this->value1=$row['TestData'];}
			if($row['OrgField']=='FPMS04'){$this->value2=$row['TestData'];}
			if($row['OrgField']=='FPMS07'){$this->value3=$row['TestData'];}
			if($row['OrgField']=='FPMS12'){$this->value4=$row['TestData'];}
			
			if($row['OrgField']=='FRION003'){$this->value1=$row['TestData'];}
			if($row['OrgField']=='FRION004'){$this->value2=$row['TestData'];}
			if($row['OrgField']=='FRION006'){$this->value3=$row['TestData'];}
			if($row['OrgField']=='FRION007'){$this->value4=$row['TestData'];}
			if($row['OrgField']=='FRION010'){$this->value5=$row['TestData'];}
			if($row['OrgField']=='FRION012'){$this->value6=$row['TestData'];}
			if($row['OrgField']=='FRION013'){$this->value7=$row['TestData'];}
			if($row['OrgField']=='FRION005'){$this->value8=$row['TestData'];}
			
			if($row['OrgField']=='value003'){$this->value1=$row['TestData'];}
			if($row['OrgField']=='value004'){$this->value2=$row['TestData'];}
			if($row['OrgField']=='value005'){$this->value3=$row['TestData'];}
			if($row['OrgField']=='value006'){$this->value4=$row['TestData'];}
			if($row['OrgField']=='value007'){$this->value5=$row['TestData'];}
			if($row['OrgField']=='value010'){$this->value6=$row['TestData'];}
			if($row['OrgField']=='value012'){$this->value7=$row['TestData'];}
			if($row['OrgField']=='value013'){$this->value8=$row['TestData'];}
			
			//P
			if($row['OrgField']=='PP01'){$this->value4=$row['TestData'];}
			if($row['OrgField']=='PP02'){$this->value3=$row['TestData'];}
			if($row['OrgField']=='PP03'){$this->value2=$row['TestData'];}
			if($row['OrgField']=='PP05'){$this->value1=$row['TestData'];}

		}
	}
}
?>