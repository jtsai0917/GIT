<meta http-equiv="Content-Type" content="text/html; charset=big5" />
	<?php
	//ini_set("memory_limit","2048M");
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
include  "../PHPEXCEL/Classes/PHPExcel.php";
include "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
lasturl();
datepick();
?>

 </br><form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
 

 
  日期： <input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php if($_SESSION['datepicker1']){echo $_SESSION['datepicker1'];}?>" onChange="set_date_session(this.name,this.value)">
~
<input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php if($_SESSION['datepicker2']){echo $_SESSION['datepicker2'];}?>" onChange="set_date_session(this.name,this.value)"></td> 
<input name="submit3" type="submit" id="submit3" value=" 查詢 ">
 
<?PHP 
/*if(isset($_POST['submit1']))
{
	$query="select distinct PDD_PROD_NO from IN_PLAN_PRODUCT where IAP_INWARD_DATE >20150100";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	while ($row = mssql_fetch_array($result))
	{
		$query1="select * from  IN_PLAN_PRODUCT where PDD_PROD_NO='".$row['PDD_PROD_NO']."' and   IAP_INWARD_DATE >20150100 ";
			$result1 = mssql_query($query1);
			$numRows1 = mssql_num_rows($result1);
			if($numRows1>10){$query2="update PRODUCT_TYPE set PDD_INPUT='Y' where PDD_PROD_NO='".$row['PDD_PROD_NO']."'" ;echo $query2;}
			if($numRows1<=10 and $numRows1>0)
			{$query2="update PRODUCT_TYPE set PDD_INPUT='N' where PDD_PROD_NO='".$row['PDD_PROD_NO']."'" ;}
			$result2 = mssql_query($query2);
	}
	
}

if(isset($_POST['submit2']))
{
	$query="select distinct PDD_PROD_NO from OUT_PRODUCT where  OTD_NO>201501000000";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	while ($row = mssql_fetch_array($result))
	{
		$query1="select * from  OUT_PRODUCT where PDD_PROD_NO='".$row['PDD_PROD_NO']."' and   OTD_NO>201501000000 ";
			$result1 = mssql_query($query1);
			$numRows1 = mssql_num_rows($result1);
			if($numRows1>10){$query2="update PRODUCT_TYPE set PDD_OUTPUT='Y' where PDD_PROD_NO='".$row['PDD_PROD_NO']."'" ;}
			if($numRows1<=10 and $numRows1>0)
			{$query2="update PRODUCT_TYPE set PDD_OUTPUT='N' where PDD_PROD_NO='".$row['PDD_PROD_NO']."'" ;}
			$result2 = mssql_query($query2);
			
	}
	echo "abcd";
}*/

if(isset($_POST['submit3']))
{  
	 echo '<table border="1" width="60%">';
			 echo '<tr height="">';
			 echo  '<td>'."料號".'</td>';
			  echo  '<td>'."平均月使用次數".'</td>';
	$date1=dod($_POST['datepicker1']);
	$date2=dod($_POST['datepicker2']);
	$query="select distinct PDD_PROD_NO from IN_PLAN_PRODUCT where IAP_INWARD_DATE >= '".$date1."' and IAP_INWARD_DATE <= '".$date2."' ";
	//echo $query;
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	$Y=(substr($date2,0,4)-substr($date1,0,4))*12;
	$M=(substr($date2,4,2)-substr($date1,4,2));
	$D=(substr($date2,6,2)-substr($date1,6,2));
	$TOTO=$Y+$M;
	while ($row = mssql_fetch_array($result))
	{
		$query1="select * from  IN_PLAN_PRODUCT where PDD_PROD_NO='".$row['PDD_PROD_NO']."' 
		and IAP_INWARD_DATE >= '".$date1."' and IAP_INWARD_DATE <= '".$date2."' ";
			$result1 = mssql_query($query1);
			$numRows1 = mssql_num_rows($result1);
		echo '<tr height="">';
			 echo  '<td>'.$row['PDD_PROD_NO'].'</td>';
			  echo  '<td>'.$numRows1/$TOTO.'</td>';
			
	}
} 	
?>