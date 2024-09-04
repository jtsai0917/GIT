<meta http-equiv="Content-Type" content="text/html; charset=big5" />
	<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
include  "../PHPEXCEL/Classes/PHPExcel.php";
include "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";

echo'	<form id="form1" name="form1" method="post" action="">
	  <label for="lot_no"></label>
	  輸入要列印 COA 的批號 <input type="text" name="lot_no" id="lot_no" />
	  &nbsp; &nbsp; &nbsp;<input type="submit" name="submit" value="確定">
    </form> ';

if(isset($_POST['submit'])){
	echo trim($_POST['lot_no'])."查詢中............<BR>";	
	$_POST['lot_no']=trim($_POST['lot_no']);
	
	$query="SELECT DISTINCT 
                            d.OPM_ORDER_NO AS OrderNo, q.LotNo, p.PDD_PROD_NO, t.PDD_PROD_NAME, d.CTD_CUST_NO, 
                            c.CTD_CUST_SHORT_NAME, d.OPM_ETA_DATE AS ShipDate, s.SMA_DRUMNO
FROM              QC_LotData AS q LEFT OUTER JOIN
                            OUT_PRODUCT AS p ON q.LotNo = p.OPD_LOT_NO LEFT OUTER JOIN
                            PRODUCT_DATA AS t ON p.PDD_PROD_NO = t.PDD_PROD_NO LEFT OUTER JOIN
                            OUT_DECISION AS d ON p.OPM_ORDER_NO = d.OPM_ORDER_NO LEFT OUTER JOIN
                            CUSTOMER_DATA AS c ON d.CTD_CUST_NO = c.CTD_CUST_NO LEFT OUTER JOIN
                            Sample_All AS s ON s.SMA_ID = q.SampleNo AND s.SMA_LOT = q.LotNo
WHERE          (1 = 1) AND (q.LotNo LIKE '".$_POST['lot_no']."%') 
ORDER BY   q.LotNo";
	$result=mssql_query($query);
	echo'	<form id="form2" name="form2" method="post" action="">
	
	<table width="1024" border="1"><tr><td>Select</td><td>訂單號碼</td><td>批號</td><td>產品編號</td><td>產品名稱</td><td>客戶編號</td><td>客戶名稱</td><td>出貨日期</td><td>桶號</td></tr>';
	while($row=mssql_fetch_array($result)){
	echo '<tr><td>
	<input type="radio" name="RadioGroup1" value="'.trim($row[7]).'" />
	</td><td>'.$row[0].'</td><td>'.$row[1].'</td><td>'.$row[2].'</td><td>'.$row[3].'</td><td>'.$row[4].'</td><td>'.$row[5].'</td><td>'.$row[6].'</td><td>'.$row[7].'</td></tr>';
	}
	echo '</table><input type="submit" name="enter" value="選擇完成" /></form> ';
	
}

if(isset($_POST['enter'])){
	echo "桶號". $_POST['RadioGroup1']."<BR>";
	
}