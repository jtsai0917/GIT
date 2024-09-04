<?php
session_start();
$_SESSION['lasturl']=$_SERVER['REQUEST_URI'];
include("../lib/fun.php");
include("../connections/conn.php");
if(isset($_GET['lot_no'])){
	$_GET['AND_LOT_NO']=$_GET['lot_no'];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
  <link rel="stylesheet" href="/js/jquery-ui.css">
  <script src="/js/jquery-1.10.2.js"></script>
  <script src="/js/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
  <style type="text/css">
  .red {
	color: #F00;
}
  </style>
  <script type="text/javascript">
    $(function() {
    $( "#datepicker1" ).datepicker();
	$( "#datepicker2" ).datepicker();
	$( "#datepicker3" ).datepicker();
	$( "#datepicker4" ).datepicker();
  });
  </script>
<title>修改依賴</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">

    <label for="index"></label>

  <table width="1024" border="1">
    <tr>
      <td width="512"><input type="hidden" name="index" id="index" />
        <label for="datepicker1"></label>
日期時間:
<input name="date1" type="text" id="date1" size="15" value="<?php 
if($_GET['AND_APPLY_DATE']<>''){echo $_GET['AND_APPLY_DATE'];}
else{echo date("Ymd");}
?>" readonly="readonly"/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;目的：
<label for="class"></label>
<select name="class" id="class">
  <option value="1" <?php 
  if ($_GET['AND_NEED_NO']<151){echo "selected";}
  ?>>製品</option>
  <option value="151" <?php 
  if (($_GET['AND_NEED_NO']<270) and ($_GET['AND_NEED_NO']>150)){echo "selected";}
  ?>>解析</option>
  <option value="251" <?php 
  if (($_GET['AND_NEED_NO']>270)){echo "selected";}
  ?>>受入</option>
</select>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
緊急樣本：
<input type="checkbox" name="urgent" id="urgent" <?php 
	  	if($_GET['AND_MARK']=="1"){
			echo "checked";
		}
	  ?>
      />
<label for="urgent"></label></td>
      <td width="512">是否先行
      <input type="checkbox" name="before" id="before" 
	  <?php 
	  	if($_GET['AND_BEFORE']=="Y"){
			echo "checked";
		}
	  ?>
      />
      <label for="before">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;總瓶數：</label>
      <label for="sample_number"></label>
      <input name="sample_number" type="text" id="sample_number" size="4" value="<?php if($_GET['AND_TOTAL']<>''){echo $_GET['AND_TOTAL'];}else{echo $_SESSION['AND_TOTAL'];}?>"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lot No：
      <label for="lot_no"></label><?php 
	  $PP=new get_from_lot_no;
	  $PP->lid=$_GET['AND_LOT_NO'];
	  $PP->ani();
	    if($PP->numrows>0){
	  	$load='./index.php?url=edit_anylize_&lot_no='.$_GET['AND_LOT_NO'];}
		
	  ?>
      <input name="lot_no" type="text" id="lot_no" size="10" value="<?php echo $_GET['AND_LOT_NO'];?>"/><input type="button" name="pdd_no2" id="pdd_no2" value="取得資料" onClick="window.open('<?php echo $load?>', '_self');">&nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr>
    <tr>
      <td>
      品名：
      <input type="button" name="X" id="X" value="X" />
      <input name="pdd_chemical1" type="text" id="pdd_chemical1" size="10" value="<?php echo $_GET['AND_GOODS'] ?>" readonly="readonly" />
      <input type="button" name="pdd_no" id="pdd_no" value="查詢品名"/ readonly="readonly">
      <input name="pdd_chemical2" type="text" id="pdd_chemical2" size="16" value="<?php echo get_prod_name($_GET['AND_GOODS']);?>" readonly="readonly" /></td>
    <td>預定取樣時間：
      <label for="datepicker1"></label>
      <input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php 
	  	if($_SESSION['smptime']){echo ttd($_SESSION['smptime']);}
		elseif($_GET['AND_SMP_DATETIME']<>''){echo ttd($_GET['AND_SMP_DATETIME']);}
		else{echo date("m/d/Y");}
	  ?>" />
      &nbsp;&nbsp;
      <label for="order_time"></label>
      <input name="order_time1" type="text" id="order_time1" size="4"  value="<?php 
	 	if($_SESSION['smptime']){echo th($_SESSION['smptime']);}
		elseif($_GET['AND_SMP_DATETIME']<>''){echo th($_GET['AND_SMP_DATETIME']);}
		else{echo '08';}
	  ?>"/>
：
<input name="order_time2" type="text" id="order_time2" size="4" value="<?php 
		if($_SESSION['smptime']){echo tm($_SESSION['smptime']);}
		elseif($_GET['AND_SMP_DATETIME']<>''){echo tm($_GET['AND_SMP_DATETIME']);}
		else{echo '30';}
	  ?>"/>  
<span class="d1"> &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
<input type="button" name="rcv_bottle" id="rcv_bottle" value="  接收樣品瓶  " onclick="window.open('index.php?url=samp_rcv&AND_LOT_NO=<?php echo $_GET['AND_LOT_NO'];?>', '_self');"/>
</span></td>
    </tr>
     <tr>
       <td><input type="checkbox" name="deliver_time" id="deliver_time" />
         <label for="deliver_time"></label>
         &nbsp;&nbsp;送樣時間：
         <input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php 
	 	if($_SESSION['outtime']){echo ttd($_SESSION['outtime']);}
		elseif($_GET['AND_OUT_DATETIME']<>''){echo ttd($_GET['AND_OUT_DATETIME']);}
		else{echo date("m/d/Y");}
	  ?>" />
      &nbsp;&nbsp;
         <input name="deliver_time1" type="text" id="deliver_time1" size="4" value="<?php 
	 	if($_SESSION['outtime']){echo th($_SESSION['outtime']);}
		elseif($_GET['AND_OUT_DATETIME']<>''){echo th($_GET['AND_OUT_DATETIME']);}
		else{echo '09';}
	  ?>" />
         ：
         <input name="deliver_time2" type="text" id="deliver_time2" size="4" value="<?php 
	 	if($_SESSION['outtime']){echo tm($_SESSION['outtime']);}
		elseif($_GET['AND_OUT_DATETIME']<>''){echo tm($_GET['AND_OUT_DATETIME']);}
		else{echo '30';}
	  ?>" />
      </td>
    <td>希望報告時間：
      <input name="datepicker3" type="text" id="datepicker3" size="10" value="<?php 
	  	if($_SESSION['ordertime']){echo ttd($_SESSION['ordertime']);}
		elseif($_GET['AND_REPORT_DATETIME']<>''){echo ttd($_GET['AND_REPORT_DATETIME']);}
		else{$d=strtotime("+1 Days"); echo date("m/d/Y",$d);}
	  ?>" />
&nbsp;&nbsp;
<label for="order_time"></label>
<input name="order_time3" type="text" id="order_time3" value="<?php 
		if($_SESSION['ordertime']){echo th($_SESSION['ordertime']);}
		elseif($_GET['AND_REPORT_DATETIME']<>''){echo th($_GET['AND_REPORT_DATETIME']);}
		else{echo '17';}
	  ?>" size="4" />
：
<input name="order_time4" type="text" id="order_time4" value="<?php 
		if($_SESSION['ordertime']){echo tm($_SESSION['ordertime']);}
		elseif($_GET['AND_REPORT_DATETIME']<>''){echo tm($_GET['AND_REPORT_DATETIME']);}
		else{echo '30';}
	  ?>" size="4" />&nbsp;&nbsp;
      <input name="new" type="submit" id="new" value="新增/儲存" />
      <input name="leave" type="submit" value=" 離開 "/>
    </tr>
    <tr>
    <td>分析項目<span class="red">(自動產生)</span>：
<input type="submit" name="add_testitems_auto" id="add_testitems_auto" value="新增/修改分析項目" onclick="window.open('index.php?url=add_testitems_auto&AND_GOODS=<?php if($_GET['AND_GOODS']<>'') {echo $_GET['AND_GOODS'];}?> ', '_self');" />
<br />
<?php
include("../lib/jtsai.php");

if(!isset($_SESSION['items1'])){
$rull=new autoani;
$rull->lid=$_GET['AND_LOT_NO'];
$rull->cid=$_GET['CTD_CUST_NO'];
$rull->pid=$_GET['AND_GOODS'];	
$rull->outdate=$_GET['outdate'];
$rull->get_rull();
$rull->status();
$rull->items();
echo "</br>";
$rull->showitems();
$_SESSION['auto_testitems']=$rull->item;
}
else{
$_SESSION['auto_testitems']=$_SESSION['items1'];
$aa=explode(',',$_SESSION['auto_testitems']);
	$query="SELECT DISTINCT ANI_GROUPNAME,ANI_ID
FROM              AnalyzeItem
WHERE          (ANI_INDEX <>'') and ";
for($i=0;$i<count($aa);$i++){
	$query.="(ANI_INDEX =".$aa[$i].") OR ";}
	$query=substr($query,0,-4);
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
echo $row['ANI_ID']."</br>";
}
echo "Total :".(count($aa))." 項</br>";

}
?>
</td>
    <td>備註：
      <label for="remark"></label>
      <textarea name="remark" id="remark" cols="65" rows="20"></textarea></tr>
  </table>
</form>
</body>
</html>
<?php

$loginFormAction = $_SERVER['PHP_SELF'];


//////////////////////////////////////////////////////////
if(isset($_POST["subentry"]))
{   session_start();
//////////////////////////////////////////////////////////
	$smptime=dod($_POST['datepicker1']).$_POST['order_time1'].$_POST['order_time2']."00";
	$outtime=dod($_POST['datepicker2']).$_POST['deliver_time1'].$_POST['deliver_time2']."00";
	$ordertime=dod($_POST['datepicker3']).$_POST['order_time3'].$_POST['order_time4']."00";
	$query="UPDATE dbo.AnalyzeDesign SET AND_ITEM = '".$_SESSION['auto_testitems']."' WHERE (AND_LOT_NO = '".$_POST['lot_no']."')" ;
	$result = mssql_query($query);

if (!$result) {
    print("SQL statement failed with error:\n");
    print("   ".mssql_get_last_message()."\n");
  } else {  //echo $_SESSION['auto_testitems'];
}
} 

if(isset($_POST["load"])){
	session_start();
$query="SELECT          	AND_APPLY_DATE, AND_NEED_NO, AND_LOT_NO, AND_GOODS, CTD_CUST_NO, AND_BEFORE, 
                            AND_SMP_DATETIME, AND_ITEM, AND_ANA_ID, AND_VALUE, AND_MEMO, AND_TOTAL, AND_OUT_QTY, 
                            AND_OUT_DATETIME, AND_REPORT_DATETIME, AND_MARK, AND_GET_QTY, AND_GET_DATETIME, 
                            AND_RESULT_DATETIME, AND_RESULT, AND_NOTE, AND_PERSON, ALM_IDENTITY51, ALM_IDENTITY52, 
                            ALM_IDENTITY53, ALM_IDENTITY54, ALM_IDENTITY55, ALM_IDENTITY56, ALM_IDENTITY13
FROM              dbo.AnalyzeDesign
WHERE          (AND_LOT_NO = '".$_POST['lot_no']."')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
if($numRows>0){
				while($row = mssql_fetch_array($result)){	
				$_SESSION['auto_testitems']=$row['AND_ITEM'];
				$_SESSION['smptime']=$row['AND_SMP_DATETIME'];	
				$_SESSION['outtime']=$row['AND_OUT_DATETIME'];
				$_SESSION['ordertime']=$row['AND_REPORT_DATETIME'];	
$url="index.php?url=edit_anylize_auto&AND_APPLY_DATE=".$row['AND_APPLY_DATE']."&AND_NEED_NO=".$row['AND_NEED_NO']."&AND_LOT_NO=".$row['AND_LOT_NO']."&AND_GOODS=".$row['AND_GOODS']."&CTD_CUST_NO=".$_GET['cid']."&AND_BEFORE=".$row['AND_BEFORE']."&AND_SMP_DATETIME=".$row['AND_SMP_DATETIME']."&AND_ITEM=".$row['AND_ITEM']."&AND_ANA_ID=".$row['AND_ANA_ID']."&AND_VALUE=".$row['AND_VALUE']."&AND_MEMO=".$row['AND_MEMO']."&AND_TOTAL=".$row['AND_TOTAL']."&AND_OUT_QTY=".$row['AND_OUT_QTY']."&AND_OUT_DATETIME=".$row['AND_OUT_DATETIME']."&AND_REPORT_DATETIME=".$row['AND_REPORT_DATETIME']."&AND_MARK=".$row['AND_MARK']."&AND_GET_QTY=".$row['AND_GET_QTY']."&AND_GET_DATETIME=".$row['AND_GET_DATETIME']."&AND_RESULT_DATETIME=".$row['AND_RESULT_DATETIME']."&AND_RESULT=".$row['AND_RESULT']."&AND_NOTE=".$row['AND_NOTE']."&AND_PERSON=".$row['AND_PERSON']."&AND_MARK=".$row['AND_MARK'];

				}
					jumpto($url);
				}
				else {echo "查無資料";
				}
}
if($_GET['lot_no']<>''){
	session_start();
	$_SESSION['AND_LOT_NO']=$_POST['AND_LOT_NO']=$_GET['lot_no'];
$query="SELECT          	AND_APPLY_DATE, AND_NEED_NO, AND_LOT_NO, AND_GOODS, CTD_CUST_NO, AND_BEFORE, 
                            AND_SMP_DATETIME, AND_ITEM, AND_ANA_ID, AND_VALUE, AND_MEMO, AND_TOTAL, AND_OUT_QTY, 
                            AND_OUT_DATETIME, AND_REPORT_DATETIME, AND_MARK, AND_GET_QTY, AND_GET_DATETIME, 
                            AND_RESULT_DATETIME, AND_RESULT, AND_NOTE, AND_PERSON, ALM_IDENTITY51, ALM_IDENTITY52, 
                            ALM_IDENTITY53, ALM_IDENTITY54, ALM_IDENTITY55, ALM_IDENTITY56, ALM_IDENTITY13
FROM              dbo.AnalyzeDesign
WHERE          (AND_LOT_NO = '".$_POST['AND_LOT_NO']."')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
if($numRows>0){
				while($row = mssql_fetch_array($result)){	
				$_SESSION['auto_testitems']=$row['AND_ITEM'];
								
$url="index.php?url=edit_anylize_auto&AND_APPLY_DATE=".$row['AND_APPLY_DATE']."&AND_NEED_NO=".$row['AND_NEED_NO']."&AND_LOT_NO=".$row['AND_LOT_NO']."&AND_GOODS=".$row['AND_GOODS']."&CTD_CUST_NO=".$_GET['cid']."&AND_BEFORE=".$row['AND_BEFORE']."&AND_SMP_DATETIME=".$row['AND_SMP_DATETIME']."&AND_ITEM=".$row['AND_ITEM']."&AND_ANA_ID=".$row['AND_ANA_ID']."&AND_VALUE=".$row['AND_VALUE']."&AND_MEMO=".$row['AND_MEMO']."&AND_TOTAL=".$row['AND_TOTAL']."&AND_OUT_QTY=".$row['AND_OUT_QTY']."&AND_OUT_DATETIME=".$row['AND_OUT_DATETIME']."&AND_REPORT_DATETIME=".$row['AND_REPORT_DATETIME']."&AND_MARK=".$row['AND_MARK']."&AND_GET_QTY=".$row['AND_GET_QTY']."&AND_GET_DATETIME=".$row['AND_GET_DATETIME']."&AND_RESULT_DATETIME=".$row['AND_RESULT_DATETIME']."&AND_RESULT=".$row['AND_RESULT']."&AND_NOTE=".$row['AND_NOTE']."&AND_PERSON=".$row['AND_PERSON']."&AND_MARK=".$row['AND_MARK'];

				}
					jumpto($url);
				}
				else {echo "查無資料";
				}
}
if(isset($_POST["new"])){
//////////////////////////////////////////////////////////
	$smptime=dod($_POST['datepicker1']).$_POST['order_time1'].$_POST['order_time2'];
	$outtime=dod($_POST['datepicker2']).$_POST['deliver_time1'].$_POST['deliver_time2'];
	$ordertime=dod($_POST['datepicker3']).$_POST['order_time3'].$_POST['order_time4'];
	if($_POST['before']=="on"){$before="Y";}else{$before="N";}
	if($_POST['urgent']=="on"){$urgent="1";}else{$urgent="0";}
	$query="select * from dbo.AnalyzeDesign where AND_LOT_NO='".$_POST['lot_no']."'";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	if($numRows<1){
	$query="INSERT INTO dbo.AnalyzeDesign
                            (AND_RESULT,AND_RESULT_DATETIME,AND_GET_DATETIME,AND_GET_QTY,AND_MARK,AND_VALUE,AND_ANA_ID,AND_APPLY_DATE, AND_NEED_NO, AND_LOT_NO, AND_GOODS, AND_BEFORE, 
                            AND_SMP_DATETIME, AND_ITEM, AND_NOTE, AND_TOTAL, AND_OUT_QTY, 
                            AND_OUT_DATETIME, AND_REPORT_DATETIME, AND_PERSON)
  VALUES          ('','','',0,'".$urgent."','','','".date("Ymd")."',".$_POST['class'].",'".$_POST['lot_no']."','".$_POST['pdd_chemical1']."','".$before."','".$outtime."','".
 $_SESSION['auto_testitems']."','".$_POST['remark']."',".$_POST['sample_number'].",0,'".$smptime."','".$ordertime."','".$_SESSION['uid']."')";
}
else{$query="UPDATE        dbo.AnalyzeDesign
SET                  	  AND_GET_QTY = ".$_POST['sample_number'].", AND_BEFORE = '".$before."', AND_SMP_DATETIME = '".$smptime."', 
                          AND_ITEM = '".$_SESSION['auto_testitems']."', AND_NOTE = '".$_POST['remark']."', AND_OUT_DATETIME = '".$outtime."', 
                          AND_REPORT_DATETIME = '".$ordertime."', AND_PERSON = '".$_SESSION['uid']."'
WHERE         (AND_LOT_NO = '".$_POST['lot_no']."')";}
$result = mssql_query($query);
if (!$result) {
    print("SQL statement failed with error:\n");
    print("   ".mssql_get_last_message()."\n");
  } else {
	  echo " Anylize Changed!!";}
	  unset($_SESSION['fill_qty']);
	unset($_SESSION['dapic1']);
if($_SESSION['lasturl1']<>''){jumpto($_SESSION['lasturl1']);}
jumpto("../fill/index.php?url=fill_out_plan");
}
if(isset($_POST["add_testitems_auto"]))
{   session_start();
//////////////////////////////////////////////////////////
	$_SESSION['smptime']=$smptime=dod($_POST['datepicker1']).$_POST['order_time1'].$_POST['order_time2']."00";
	$_SESSION['outtime']=$outtime=dod($_POST['datepicker2']).$_POST['deliver_time1'].$_POST['deliver_time2']."00";
	$_SESSION['ordertime']=$ordertime=dod($_POST['datepicker3']).$_POST['order_time3'].$_POST['order_time4']."00";
	$url="index.php?url=add_testitems_auto&AND_GOODS=".$_GET['AND_GOODS'];
	$_SESSION['items']=$_SESSION['auto_testitems'] ;
	jumpto($url);
//	$query="UPDATE dbo.AnalyzeDesign SET AND_ITEM = '".$_SESSION['auto_testitems']."' WHERE (AND_LOT_NO = '".$_POST['lot_no']."')" ;
//	$result = mssql_query($query);
}

if(isset($_POST['leave'])){
	unset($_SESSION['fill_qty']);
	unset($_SESSION['dapic1']);
jumpto($_SESSION['edit_fill_out']);
}

?>


