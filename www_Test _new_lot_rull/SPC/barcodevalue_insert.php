<?php
	session_start();
	// include("../connections/conn.php");
	include("fun_spc.php");
	datepick(); 
	$datefrom=date("Y",strtotime("-3 year"));
	$datefrom=$datefrom.'/01/01';
	if($_SESSION['datepicker1']==''){$_SESSION['datepicker1']=$datefrom;$_SESSION['datepicker2']=date("Y/m/d");}
?>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<form name="form1" method="post" action="<?php echo $loginFormAction; ?>">
	開始日期:<input name="datepicker1" type="text" id="datepicker1" size="14" value="<?php echo $_SESSION['datepicker1'] ;?>" onchange="set_date_session(this.name,this.value)"/>
	結束日期:<input name="datepicker2" type="text" id="datepicker2" size="14" value="<?php echo $_SESSION['datepicker2'] ;?>" onchange="set_date_session(this.name,this.value)"/>
	<input type="submit" name="intovalue" value=" 導入分析值 ">
</form>
<?php
if(isset($_POST['intovalue'])){
	$barcodehnd = mssql_connect("localhost", "sa", "2iairiol")
  or die("Couldn't connect to SQL Server on $myServer");
	$conn_barcode=mssql_select_db("CHEMICAL", $barcodehnd)
  or die("Couldn't open database CHEMICAL");
	//TEMPMAIN
	$TEMPMAIN=array();
	$query="SELECT DISTINCT 
                            QC_LotData.ProdNo, QC_LotData.CustNo, CUSTOMER_DATA.CTD_CUST_NAME, 
                            PRODUCT_DATA.PDD_PROD_NAME
					FROM              QC_LotData LEFT OUTER JOIN
					                            PRODUCT_DATA ON QC_LotData.ProdNo = PRODUCT_DATA.PDD_PROD_NO LEFT OUTER JOIN
					                            CUSTOMER_DATA ON QC_LotData.CustNo = CUSTOMER_DATA.CTD_CUST_NO
					WHERE          (QC_LotData.CustNo <> '') AND (QC_LotData.state = 't') ";
  if(!isset($_POST['datepicker1']) and !isset($_POST['datepicker2'])){
		$query.=" AND createts >='".$datefrom."' ";
	}
	else{
		$query.=" AND (createts >='".$_POST['datepicker1']."' and createts <='".$_POST['datepicker2']."')";
	}      
	echo $query."<BR>";      
//	$resulu=$conn_barcode->query($query);            
  $result=mssql_query($query,$barcodehnd);
  $i=0;
	while($row = mssql_fetch_array($result))
	{
		$groupname=trim($row['CustNo'])."_".trim($row['CTD_CUST_NAME']);
		$filename=trim($row['ProdNo'])."_".trim($row['PDD_PROD_NAME']);
		$TEMPMAIN[$i][1]=$groupname;
		$TEMPMAIN[$i][2]=$filename;
		$i++;
	}
	$cnt_TEMPMAIN=$i;
	echo "群組檔案共：".$i."項<BR>";
	mssql_close($barcodehnd);
	unset($barcodehnd);
	unset($conn_barcode);
	
	$spcdbhandle = mssql_connect("localhost", "spc", "spc")
  or die("Couldn't connect to SQL Server on $myServer");
	$conn_spc=mssql_select_db("spc", $spcdbhandle)
  or die("Couldn't open database spc"); 
  
	$x=0; 
	for($j=0;$j < $cnt_TEMPMAIN;$j++){  //填寫TEMPMAIN
		$date=date("Y-m-d H:i:s");			
		$query="INSERT INTO TEMPMAIN (GROUPNAME, FILENAME, TRANSINTIME, FLAG, STATUS, TYPE, TYPE2) 
				VALUES (N'".$TEMPMAIN[$j][1]."', N'".$TEMPMAIN[$j][2]."', CONVERT(DATETIME, '".$date."', 102), 'N', 'A', 'V', 'V') SELECT SCOPE_IDENTITY()";
	 	//		echo $query."<BR>";
	 	$result=mssql_query($query,$spcdbhandle);
		$row=mssql_fetch_row($result);
		$mid=$row[0];
		$query="update TEMPMAIN SET INSPECTID=".$mid." where MID=".$mid;
		$result=mssql_query($query);
		$x++;
	}		 //結束填寫TEMPMAIN
		unset($TEMPMAIN);	
		echo "結束填寫TEMPMAIN  ".$x."項<BR>";
		mssql_close($spcdbhandle);
		unset($spcdbhandle);
		unset($conn_spc);
	//分析結果    =
	$barcodehnd = mssql_connect("localhost", "sa", "2iairiol")
  or die("Couldn't connect to SQL Server on CHEMICAL");
	$conn_barcode=mssql_select_db("CHEMICAL", $barcodehnd)
  or die("Couldn't open database CHEMICAL");
                 
	$query="SELECT DISTINCT 
                            QC_LotData.LotNo as lot_no, QC_LotData.SampleNo as smpno, QC_LotData.TestData as tdate, QC_LotData.ProdNo as prod_no, 
                            QC_LotData.CustNo as cno, QC_LotData.ItemName as iname, QC_LotData.ItemUnit as unit, QC_LotData.state as stat, 
                            QC_LotData.createts as cdatetime, CUSTOMER_DATA.CTD_CUST_NAME as cname, PRODUCT_DATA.PDD_PROD_NAME as pname
FROM              QC_LotData LEFT OUTER JOIN
                            PRODUCT_DATA ON QC_LotData.ProdNo = PRODUCT_DATA.PDD_PROD_NO LEFT OUTER JOIN
                            CUSTOMER_DATA ON QC_LotData.CustNo = CUSTOMER_DATA.CTD_CUST_NO 
						WHERE          (QC_LotData.CustNo <> '') AND (QC_LotData.state = 't') ";
	if(!isset($_POST['datepicker1']) and !isset($_POST['datepicker2'])){
		$query.=" AND createts >='".$datefrom."' ";
	}
	else{
		$query.=" AND (QC_LotData.createts >='".$_POST['datepicker1']."' and QC_LotData.createts <='".$_POST['datepicker2']."')";
	}
	echo $query."<BR>";
	$i=0;
	$result=mssql_query($query);
	$numr=mssql_num_rows($result);
	/*
	while($row = mssql_fetch_array($result))
	{
	//	$groupname=trim($row['cno'])."_".trim($row['cname']);
	//	echo $groupname."<BR>";
		
		$filename=trim($row['ProdNo'])."_".trim($row['PDD_PROD_NAME']);
//		$MID=get_mainid($groupname,$filename);
		$item=trim($row['ItemName']);
		
		$i++;
	}
	*/
	echo "共計:".$numr."<BR>";
	
}  //END of POST intovalue

function get_mainid($groupname,$filename){
	/*
	include_once("conn_spc.php");
	$query="SELECT TEMPMAIN.MID FROM TEMPMAIN WHERE (GROUPNAME = N'".$groupname."') AND (FILENAME = N'".$filename."') ORDER BY   TRANSINTIME";
	echo $query."<BR>";
	*/
}

?>