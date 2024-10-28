<?php
session_start();  
include("../connections/conn.php");
include ("../lib/fun.php");
include ("../lib/jtsai.php");
datepick();
if($_SESSION['uid']==''){jumpto("login.php");}
if($_SESSION['place']=="HIC"){$return_page="index1.php";}
else{$return_page="index.php";}
?><head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>Lorry 充填</title>
<style type="text/css">

body,td,th {
	font-size:<?php echo $_SESSION['font_size'];?>px;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
</style>
<script language="JavaScript" type="text/javascript">
<!--
function checkform2 ( form )
{
  // ** START **
  if (form2.remnant.value == "") {
    alert( "請輸入殘液量" );
    form2.remnant.focus();
    return false ;
  }
  // ** END **
  return true ;
}
function checkform4 ( form )
{
  // ** START **
  if (form4.remnant.value == "") {
    alert( "請輸入殘液量" );
    form2.remnant.focus();
    return false ;
  }
  // ** END **
  return true ;
}
//-->
</script>
<p><a href="fill.php"><img src="../pics/TYS.jpg" alt="" width="50" height="28" /></a>人員：<?php echo $_SESSION['uname']?></p>
<?php
	$_SESSION['lid']=$_POST['lid'];
	
	$query="SELECT          A.FOD_UNI, A.CTD_CUST_NO, B.CTD_CUST_NAME, A.FDM_SERIAL_NO, A.PDD_PROD_NO, C.PDD_PROD_NAME, 
                            C.PDD_PROD_SHORT_NAME, A.FDM_SAM_BEFORE, A.FDM_SAM_BEF_CNT, A.FDM_SAM_CNT, 
                            A.FDM_ATTACH_CNT, A.FDM_QTY_DRUM, A.FDM_QTY, D.CTP_ExportCountLimit, A.FDM_LY_NO
FROM              FILLPLAN_OUT_DECIDE AS A LEFT OUTER JOIN
                            CUSTOMER_DATA AS B ON A.CTD_CUST_NO = B.CTD_CUST_NO LEFT OUTER JOIN
                            PRODUCT_DATA AS C ON A.PDD_PROD_NO = C.PDD_PROD_NO LEFT OUTER JOIN
                            CUSTOMER_PRODUCTS AS D ON A.CTD_CUST_NO = D.CTD_CUST_NO AND A.PDD_PROD_NO = D.PDD_PROD_NO
WHERE          (A.FDM_LOT_NO = '".$_SESSION['lid']."')";

	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	while($row = mssql_fetch_array($result))
	{
		$_SESSION['pidx']=$pid=$row['PDD_PROD_NO'];
		$cid=$row['CTD_CUST_NO'];
		$prod_name=$row['PDD_PROD_NAME'];
		$cust_name=$row['CTD_CUST_NAME'];
		$smp_cnt=$row['FDM_SAM_BEF_CNT']+$row['FDM_ATTACH_CNT']+$row['FDM_SAM_CNT']+2;
		$fdm_qty=$row['FDM_QTY'];
		$lorry_no=$row['FDM_LY_NO'];
	// P1	
		echo '品名 ： '.$prod_name.'</br>';
		echo '日期 ： '.date("Y/m/d").'</br>';
		echo '客戶 ： '.$cust_name. '</br>';
		echo '樣品瓶數 ： '.$smp_cnt.'</br>';
		echo '充填量 ： '.$fdm_qty.'</br></br>';
	}
