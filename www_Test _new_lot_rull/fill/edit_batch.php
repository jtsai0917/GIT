<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php 
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
include("../lib/lot_no.php");
datepick();
$query="Select * from tank_batch where lot_no='".$_GET['lot_no']."' and active=1";
$result=mssql_query($query);
$row=mssql_fetch_row($result);
$pid=$row[1];
$createtime=$row[2];
$creator=$row[3];
$lot_no=$row[4];
$tank_no=trim($row[5]);
$b= strripos($tank_no,"-");
$fill_date=trim($row[6]);
$memo=$row[7];
$leave=$row[9];
?>

<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
<BR>
<table width="640" border="1">
<tr bgcolor="#CCCCCC"><td align="center"> <BR> <font color="#CC0000" size="+2"> 封槽管理系統 </font><BR><BR> </td></tr>
<tr><td align="center"> <font size="+1"> 編輯封槽 LOT </font> </td></tr></table>
<table width="640" border="1">
<tr>
<td width="460">    <p>品名：
  <input type="button" name="X" id="X" value="X" onClick="window.open('../erase_prod.php ', '_self');">
  <input name="pdd_chemical1" type="text" id="pdd_chemical1" size="10" value="<?php echo $pid;?>" readonly>
  <input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onClick="window.open('../pdd_prod_no.php ', '_self');">
  <input name="pdd_chemical2" type="text" id="pdd_chemical2" size="16" value="<?php echo get_prod_name($pid);?>" readonly>

<BR> <BR> 
    封槽日期：
    <input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php 
			echo odo($fill_date);
?>">
&nbsp;&nbsp;      TANK 號碼: &nbsp;
    <select name="tank_no" id="tank_no" onchange="set_date_session(this.name,this.value)">
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
			if(trim($row['PROD_TANK'])==trim($tank_no)){$str1=' selected';}else{$str1='';}
			echo '<option value="'.$row['PROD_TANK'].'" '.$str1.'>'.$row['PROD_TANK'].'</option>';
		}
	?>
    </select>
    <br> <br>
    備註: <input type="text" name="memo" size="50" value="<?php echo $_SESSION['memo'];?>"  onchange="set_date_session(this.name,this.value)">
</td><td width="200" align="center">LOT NO: <BR>
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
