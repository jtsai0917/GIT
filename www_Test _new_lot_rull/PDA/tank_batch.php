<?php 
session_start();
include("../connections/conn.php");
include ("../lib/fun.php");
include ("../lib/jtsai.php");
include("../lib/lot_no.php");
datepick();
lasturl();
$srvip=$_SERVER['SERVER_ADDR'];
if(substr($srvip,0,7)=='195.7.2.100')
{
	$_SESSION['index']='index.php';	
}
elseif(substr($srvip,0,7)=='143.2.11.48')
{
	$_SESSION['index']='index.php';	
}
elseif(substr($srvip,0,7)=='195.7.5.4')
{
	$_SESSION['index']='index1.php';	
}
else
{
	$_SESSION['index']='index.php';	
}
$return_page=$_SESSION['index'];
?>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
</head>

<body>
<p><a href="tank_batch.php"><img src="../pics/TYS.jpg" alt="" width="50" height="28" /></a>人員：<?php echo $_SESSION['uname']?></p>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
<BR>
<table width="350" border="1">
<tr bgcolor="#CCCCCC"><td align="center"> <BR> <font color="#CC0000" size="+2"> 封槽管理系統 </font><BR><BR> </td></tr>
<tr><td align="center"> <font size="+1"> 建立封槽 LOT </font> </td></tr></table>
<table width="350" border="1">
<tr>
<td width="250">    <p>&nbsp;</p>
  <p>品名
    <input type="button" name="X" id="X" value="X" onClick="window.open('../erase_prod.php ', '_self');">
    <input name="pdd_chemical1" type="text" id="pdd_chemical1" size="8" value="<?php 
		if ($_GET['pid']){
			echo $_GET['pid'];
			$_SESSION['pid']=$_GET['pid'];
		}
		elseif($_SESSION['pid']){
			echo $_SESSION['pid'];
		}
		else{
		echo '';
		}
		?>" readonly>
    <input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onClick="window.open('../pdd_prod_no.php?pdd_class=tank ', '_self');" >
    <input name="pdd_chemical2" type="text" id="pdd_chemical2" size="8" value="<?php 
		if ($_GET['pname']){
			echo $_GET['pname'];
			$_SESSION['pname']=$_GET['pname'];
		}
		elseif($_SESSION['pname']){
			echo $_SESSION['pname'];
		}
		else{
		echo '';
		}
		?>" readonly>
  </p>
  <p><BR> 
    請選擇封槽日期：
    <input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php 
			echo $_SESSION['datepicker1'];
?>" onChange="set_date_session(this.name,this.value)">
&nbsp;&nbsp;    </p>
  <p>請選擇封槽 TANK 號碼: &nbsp;
    <select name="tank_no" id="tank_no" onChange="set_date_session(this.name,this.value)">
      <option value=""></option>
      <?php
		$a= strripos($_SESSION['prod_no'],"-");
		$str=substr($_SESSION['prod_no'],0,$a);
		$_SESSION['AND_GOODS']=$str;
		$query="SELECT  PROD_TANK
FROM      TANK_DATA1
WHERE   (PDD_PROD_NO LIKE '".$str."%')";
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result)){
			if($_SESSION['tank_no']==$row['PROD_TANK']){$str1=' selected';}else{$str1='';}
			echo '<option value="'.$row['PROD_TANK'].'" '.$str1.'>'.$row['PROD_TANK'].'</option>';
		}
	?>
    </select>
    <br> 
    <br>
    備註: <input type="text" name="memo" size="30" value="<?php echo $_SESSION['memo'];?>"  onchange="set_date_session(this.name,this.value)">
  </p>
  </td><td width="100" align="center">封槽 LOT NO: <BR>
<?php
	$aa=new creat_ana_lot_no;
	$aa->day=dod($_SESSION['datepicker1']);
	$aa->pid=$_SESSION['prod_no'];
	$aa->type_ana();
	$OriginalString=trim($_SESSION['tank_no']);
	$_SESSION['tank_no']=preg_replace("/-/",'',$OriginalString);
	echo $b_lot=$_SESSION['newlot'].$_SESSION['tank_no'];
?>
<BR><BR><input type="submit" name="save" value=" 建立 ">
</td></tr></table>
</form>

<?php
$loginFormAction = $_SERVER['PHP_SELF'];

if(isset($_POST['save']))
{
	// 存 Tank_batch
	$query="";
	$query="SELECT  COUNT(*) AS n
FROM      Tank_batch
WHERE   (lot_no = '".$b_lot."')";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	if($row[0]>0){
		$query="update Tank_batch set active=0 where (lot_no = '".$b_lot."')";
		$result=mssql_query($query);
	}
	$query="SELECT  TANK_DATA1.capacity
FROM      TANK_DATA1
WHERE   (PROD_TANK = '".$_POST['tank_no']."')";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$capacity=$row[0];
	$query="INSERT INTO Tank_batch
                   (createtime, creator, lot_no, tank_no, filldatetime, memo, active, leave, PDD_PROD_NO)
VALUES  ('".date("YmdHis")."','".$_SESSION['uid']."','".$b_lot."','".$_SESSION['tank_no']."','".dod($_SESSION['datepicker1'])."','".$_POST['memo']."',1,".$capacity.",'".$_SESSION['pdd_chemical1']."')";

	$result=mssql_query($query);
	if($result<>''){
	echo "<BR>新增了一筆資料!!<BR>";
	echo "Lot NO :".$b_lot."<BR>";
	echo "封槽日期 :".$_SESSION['datepicker1']."<BR>";
	echo "建立日期 :".date("m/d/Y")."<BR>";
	echo "TANK NO :".$_SESSION['tank_no']."<BR>";
	echo "建檔人員 :".get_uname($_SESSION['uid'])."<BR>";
	save_ani($ani_items,$b_lot,$_SESSION['datepicker1'],$_SESSION['prod_no']);
	}
	else{
		echo "未建立資料<BR>".$query;}	
}

function save_ani($ani_items,$b_lot,$date1,$pdd_no){
// 存依賴
	$query="SELECT  Rull_Total FROM      Analyze_Rulls WHERE   (CTD_CUST_NO LIKE '%C00001%') AND (PDD_PROD_NO='".$_SESSION['prod_no']."')";
	$result=mssql_query($query);
	$numrows=mssql_num_rows($result);
	if($numrows>0){
		$row=mssql_fetch_row($result);
		$ani_items=$row[0];	
		create_ani($ani_items,$b_lot,$date1,$pdd_no);
	}
	else{
		echo "找不到 C00001，藥品 ".$pdd_no." 的全項目設定<BR>";
	}	
}

function create_ani($items,$lot_no,$datepicker,$prod_no){
	$query="SELECT  PDD_PROD_SHORT_NAME FROM      PRODUCT_DATA WHERE   (PDD_PROD_NO = '".$prod_no."')";	
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$short_name=$row[0];
	$query="select AND_LOT_NO as nn from AnalyzeDesign where AND_LOT_NO='".$lot_no."'";
	$result=mssql_query($query);
	$numrows=mssql_num_rows($result);
	if($numrows==0){
		$query="INSERT INTO AnalyzeDesign
					   (AND_APPLY_DATE, AND_NEED_NO, AND_LOT_NO, AND_GOODS, CTD_CUST_NO, AND_BEFORE, AND_SMP_DATETIME, AND_ITEM, AND_PERSON, AND_REPORT_DATETIME)
				VALUES  ('".dod($_SESSION['datepicker1'])."',140,'".$lot_no."','".$prod_no."','C00001', 'N','".date("YmdHis")."','".$items."','".$_SESSION['uid']."','".dod($_SESSION['datepicker1'])."173000')";
		$result=mssql_query($query);
	}
	
	if (!$result){
		print("SQL statement failed with error:\n");
		print("   ".mssql_get_last_message()."\n");
	  } else {
		  echo " 新增 ".$lot_no." 的依賴!!";}		
}

mssql_close($dbhandle);
//jumpto($_SESSION['lasturl']);
				
?>
<a href="<?php echo $return_page?>" target="_self">回首頁</a>
</body>
</html>