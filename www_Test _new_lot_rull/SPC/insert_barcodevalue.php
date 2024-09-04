<?php
	session_start();
	// include("../connections/conn.php");
	include("fun_spc.php");
	datepick(); 
	$datefrom=date("Y",strtotime("-3 year"));
	$datefrom=$datefrom.'/01/01';
?>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<form name="form1" method="post" action="<?php echo $loginFormAction; ?>">
	開始日期:<input name="datepicker1" type="text" id="datepicker1" size="14" value="<?php $_SESSION['datepicker1'] ;?>" onchange="set_date_session(this.name,this.value)"/>
	結束日期:<input name="datepicker2" type="text" id="datepicker2" size="14" value="<?php $_SESSION['datepicker2'] ;?>" onchange="set_date_session(this.name,this.value)"/>
	<input type="submit" name="intovalue" value=" 導入分析值 ">
</form>
<?php
if(isset($_POST['intovalue'])){
	include("../connections/conn.php");
	$query="SELECT DISTINCT 
                            QC_LotData.LotNo, QC_LotData.SampleNo, QC_LotData.TestData, QC_LotData.ProdNo, QC_LotData.CustNo, 
                            QC_LotData.ItemName, QC_LotData.ItemUnit, QC_LotData.state, QC_LotData.createts, 
                            CUSTOMER_DATA.CTD_CUST_NAME, PRODUCT_DATA.PDD_PROD_NAME
FROM              QC_LotData LEFT OUTER JOIN
                            PRODUCT_DATA ON QC_LotData.ProdNo = PRODUCT_DATA.PDD_PROD_NO LEFT OUTER JOIN
                            CUSTOMER_DATA ON QC_LotData.CustNo = CUSTOMER_DATA.CTD_CUST_NO 
						WHERE          (CustNo <> '') AND (state = 't') ";
	if(!isset($_POST['datepicker1']) and !isset($_POST['datepicker2'])){
		$query.=" AND ";
	}
	else{
		$query.=" AND (createts >='".$_POST['datepicker1']."' and createts <='".$_POST['datepicker2']."')";
	}
	echo $query."<BR>";
	$result=mssql_query($query);
	while($row = mssql_fetch_array($result))
	{
		$groupname=trim($row['CustNo'])."_".trim($row['CTD_CUST_NAME']);
		$filename=trim($row['ProdNo'])."_".trim($row['PDD_PROD_NAME']);
		$item=trim($row['ItemName']);
	}
}
	
function check_spc_($custno,$prodno,$item,$datefrom){
		$custno1=explode('_',$custno);
		$prodno1=explode('_',$prodno);
		$item1=explode('_',$item);
		$custno1=$custno1[0];
		$prodno1=$prodno1[0];
		$item1=$item1[1];
	include_once("../connections/conn.php");
	$query1="SELECT DISTINCT LotNo, TestData, ProdNo, Chemical, CustNo, ItemName, ItemUnit, createts FROM QC_LotData WHERE (ID <> '') 
					AND (CustNo = '".$custno1."') AND (ProdNo = '".$prodno1."') AND (ItemName = '".$item1."') and createts>='".$datefrom."'ORDER BY   createts";
//	echo $query1."<BR>";
	$result1=mssql_query($query1);
	while($row1=mssql_fetch_array($result1)){
		insert_into_spc($custno,$prodno,$item,$row1['LotNo'],$row1['TestData'],$row1['createts']);
	}	
	echo $item1."<BR>";
}	

function insert_into_spc($custno,$prodno,$item,$lotno,$value,$createtime){
//	echo "SSS<BR>";
	/*
		include_once("conn_spc.php");
		$query="select ";
	*/
}

?>