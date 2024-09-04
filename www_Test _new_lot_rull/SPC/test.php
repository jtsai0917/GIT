<?php
	include_once("./conn.php");                     
	$query2="SELECT DISTINCT 
                            QC_LotData.CustNo as cno,CUSTOMER_DATA.CTD_CUST_NAME as cname
FROM              QC_LotData LEFT OUTER JOIN
                            PRODUCT_DATA ON QC_LotData.ProdNo = PRODUCT_DATA.PDD_PROD_NO LEFT OUTER JOIN
                            CUSTOMER_DATA ON QC_LotData.CustNo = CUSTOMER_DATA.CTD_CUST_NO 
						WHERE          (CustNo <> '') AND (state = 't') ";
	if(!isset($_POST['datepicker1']) and !isset($_POST['datepicker2'])){
		$query2.=" AND createts >='".$datefrom."' ";
	}
	else{
		$query2.=" AND (createts >='".$_POST['datepicker1']."' and createts <='".$_POST['datepicker2']."')";
	}
	echo $query2."<BR>";
//	echo "spcdbhandle:".$spcdbhandle."<BR>";
//	echo "barcodedb:".$barcodehnd."<BR>";
	$i=0;
	$result2=mssql_query($query2);
	while($row2 = mssql_fetch_array($result2))
	{
		$groupname=trim($row2['cno'])."_".trim($row2['cname']);
		echo $groupname."<BR>";
		/*
		$filename=trim($row['ProdNo'])."_".trim($row['PDD_PROD_NAME']);
//		$MID=get_mainid($groupname,$filename);
		$item=trim($row['ItemName']);
		*/
		$i++;
	}
	echo "¦@­p:".$i."<BR>";
?>