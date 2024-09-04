<?php
session_start();
$_SESSION['lasturl']=$_SERVER['REQUEST_URI'];
include("../lib/lot_no.php");
lastpage("add_analyze");
if($_GET['AND_LOT_NO']){$_SESSION['lot_no']=$_GET['AND_LOT_NO'];}
if(!isset($_SESSION['needno'])){$_SESSION['needno']=1;}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<title>修改依賴</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">

    <label for="index"></label>

  <table width="1240" border="1" <?php echo $_SESSION['cancel'];?>>
    <tr>
      <td width="512"><input type="hidden" name="index" id="index" />

日期時間:<input name="datepicker4" type="text" id="datepicker4" size="10"  onchange="set_date_session(this.name,this.value)" value="<?php 
		if($_SESSION['datepicker4']<>''){echo $_SESSION['datepicker4'];}
		else{echo date("m/d/Y");}
	  ?>" />
&nbsp;目的：
<select name="needno" id="needno"  onchange="set_date_session(this.name,this.value)" >;
	<option value="1" <?php if($_SESSION['needno']<=150){echo "selected";}?> >製品</option>;
    <option value="151" <?php if(($_SESSION['needno']<270) and ($_SESSION['needno']>150)){echo "selected";}?> >解析</option>;
    <option value="271" <?php if($_SESSION['needno']>=270){echo "selected";}?>>受入</option>;
</select>

<?php 

if($_SESSION['needno']>=171)
{
	$_SESSION['cust_no']='C00001';
} 
?>
<input type="hidden" name="cust_no" id="index" value="<?php echo $_SESSION['cust_no']; ?>"  />
&nbsp;

&nbsp;先行COA
<input type="checkbox" name="and_coa_fst" id="and_coa_fst" <?php 
	  	if($_SESSION['and_coa_fst']=="Y"){
			echo "checked";
		}
	  ?>
      /></td>
      <td width="512">是否先行
        <input type="checkbox" name="before" id="before" 
	  <?php 
	  	if($_SESSION['and_before']=="Y"){
			echo "checked";
		}
	  ?>
      />
        <label for="before">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;總瓶數：</label>
      <input name="sample_number" type="text" id="sample_number" size="4" value="<?php 
	  if($_GET['sample_number']<>''){$_SESSION['sample_number']=$_GET['sample_number'];}
	  elseif($_SESSION['sample_number']<>''){}
	  else{$_SESSION['sample_number']=0;}
		  echo $_SESSION['sample_number'];
	  ?>" onchange="set_date_session(this.name,this.value)"/>
      <?php 
      		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			Lot No：<input name="lot_no" type="text" id="lot_no" size="10" value="'.trim($_SESSION['lot_no']).'"  onchange="set_date_session(this.name,this.value)" /> <input type="submit" name="load_" id="load_" value="取得資料" />&nbsp;&nbsp;&nbsp;&nbsp;</td>';
	  ?>
    </tr>
    <tr>
      <td>
      品名：
      <input type="button" name="X" id="X" value="X" onclick="window.open('../erase_prod.php ', '_self');" />
      <input name="pdd_chemical1" type="text" id="pdd_chemical1" size="10" value="<?php 
	  echo $_SESSION['AND_GOODS']; 
	  ?>" />
      <input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onclick="window.open('../main.php?url=pdd_prod_no2 ', '_self');" />
      <input name="pdd_chemical2" type="text" id="pdd_chemical2" size="16" value="<?php echo get_prod_name($_SESSION['AND_GOODS']);?>"  /></td>
    <td>預定取樣時間：
      <label for="datepicker1"></label>
      <input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php 
		if($_SESSION['smptime']<>''){echo ttd($_SESSION['smptime']);}
		else{echo date("m/d/Y");}
	  ?>" />
      &nbsp;&nbsp;
      <label for="order_time"></label>
      <input name="order_time1" type="text" id="order_time1" size="4"  value="<?php 
	 	if($_SESSION['smptime']<>''){echo th($_SESSION['smptime']);}
		else{echo '08';}
	  ?>"/>
：
<input name="order_time2" type="text" id="order_time2" size="4" value="<?php 
		if($_SESSION['smptime']<>''){echo tm($_SESSION['smptime']);}
		else{echo '30';}
	  ?>"/>  
<span class="d1"> &nbsp;&nbsp;
<input type="submit" name="rcv_bottle" id="rcv_bottle" value="  接收樣品瓶  " onclick="window.open('index.php?url=samp_rcv&lot_no=<?php echo $llotnoo;?>', '_self');"/>
<input name="new" type="submit" id="new" value="新增/修改" />
</span></td>
    </tr>
     <tr>
       <td><input type="checkbox" name="deliver_time" id="deliver_time" />
         <label for="deliver_time"></label>
         &nbsp;&nbsp;送樣時間：
         <input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php 
	 	if($_SESSION['datepicker2']<>''){echo $_SESSION['datepicker2'];}
		else{echo date("m/d/Y");}
	  ?>" onchange="set_date_session(this.name,this.value)" />
      &nbsp;&nbsp;
         <input name="deliver_time1" type="text" id="deliver_time1" size="4" value="<?php 
	 	if($_SESSION['outtime']<>''){echo th($_SESSION['outtime']);}
		else{echo '09';}
	  ?>" />
         ：
         <input name="deliver_time2" type="text" id="deliver_time2" size="4" value="<?php 
	 	if($_SESSION['outtime']<>''){echo tm($_SESSION['outtime']);}
		else{echo '30';}
	  ?>" />
         緊急樣本：
         <input type="checkbox" name="and_mark" id="and_mark" <?php 
	  	if($_SESSION['and_mark']=="1"){
			echo "checked";
		}
	  ?>
      />
         <label for="urgent"></label></td>
    <td>希望報告時間：
      <input name="datepicker3" type="text" id="datepicker3" size="10" value="<?php 
	  	if($_SESSION['ordertime']){echo ttd($_SESSION['ordertime']);}
		else{$d=strtotime("+1 Days"); echo date("m/d/Y",$d);}
	  ?>" />
&nbsp;&nbsp;
<label for="order_time"></label>
<input name="order_time3" type="text" id="order_time3" value="<?php 
		if($_SESSION['ordertime']){echo th($_SESSION['ordertime']);}
		else{echo '17';}
	  ?>" size="4" />
：
<input name="order_time4" type="text" id="order_time4" value="<?php 
		if($_SESSION['ordertime']){echo tm($_SESSION['ordertime']);}
		else{echo '30';}
	  ?>" size="4" />&nbsp;&nbsp;
<input name="leave" type="submit" value=" 離開 "/>
    </tr>
    <tr>
    <td height="280">分析項目：
      <label for="testitems"></label>
      <input type="submit" name="add_testitems" id="add_testitems" value="新增/修改分析項目" onclick="window.open('index.php?url=add_testitems&AND_GOODS=<?php if($_SESSION['AND_GOODS']<>'')
	  {$_SESSION['AND_GOODS']=$_SESSION['AND_GOODS'];}echo $_SESSION['AND_GOODS'];?> ', '_self');" />
      <br />
<?php

$loginFormAction = $_SERVER['PHP_SELF'];
if($_SESSION['items1']){$itm=$_SESSION['items1'];}
else{$itm=$_SESSION['items'];}
$aa=explode(',',$itm);
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
//echo $_SESSION['testitems'];
?></td>
    <td><p>備註1：
      <label for="remark"></label>
      <textarea name="remark" id="remark" cols="65" rows="5" ><?php echo $_SESSION['note'];?></textarea>
    </p>
    <p>備註2：
      <label for="remark2"></label>
      <textarea name="remark2" id="remark2" cols="65" rows="5" ><?php echo $_SESSION['note2'];?></textarea>
    </p>    </tr>
  </table>
  <p></p>
</form>
</body>
</html>
<?php

$url=$_SERVER['REQUEST_URI']."&lot_no=".$_POST['lot_no'];

//////////////////////////////////////////////////////////
if(isset($_POST["new"])){
	echo $_POST['lot_no'];
//////////////////////////////////////////////////////////
	$smptime=dod($_POST['datepicker1']).$_POST['order_time1'].$_POST['order_time2'];
	$outtime=dod($_POST['datepicker2']).$_POST['deliver_time1'].$_POST['deliver_time2'];
	$ordertime=dod($_POST['datepicker3']).$_POST['order_time3'].$_POST['order_time4'];
	if($_POST['before']=="on"){$before="Y";}else{$before="N";}
	if($_POST['and_mark']=="on"){$urgent="1";}else{$urgent="0";}
	if($_POST['and_coa_fst']=="on"){$and_coa_fst="Y";}else{$and_coa_fst="N";}
	if($_SESSION['items1']<>''){$itm=$_SESSION['items1'];}
	else{$itm=$_SESSION['items'];}
	if($itm==''){$itm='0';}
	if($_SESSION['needno']<='150' or $_SESSION['needno']>='270'){$_SESSION['new_lot_no']=$_POST['lot_no'];}
	if(($_SESSION['needno']>'150') and ($_SESSION['needno']<'270')){$_SESSION['new_lot_no']=$post_str;}
	$query="select * from dbo.AnalyzeDesign where AND_LOT_NO='".$_SESSION['new_lot_no']."'";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	if($numRows<1){
	$query="INSERT INTO dbo.AnalyzeDesign
                            (AND_VALUE, AND_ANA_ID, AND_GET_DATETIME, AND_RESULT_DATETIME, AND_RESULT, AND_GET_QTY,AND_MARK,AND_APPLY_DATE, AND_NEED_NO, AND_LOT_NO, AND_GOODS, AND_BEFORE, 
                            AND_SMP_DATETIME, AND_ITEM, AND_NOTE, AND_NOTE2, AND_TOTAL, AND_OUT_QTY, AND_OUT_DATETIME, AND_REPORT_DATETIME, CTD_CUST_NO, AND_PERSON , AND_COA_FST)
  VALUES          (' ',' ',' ',' ',' ','0','".$urgent."','".dod($_POST['datepicker4'])."',".$_POST['needno'].",'".$_SESSION['new_lot_no']."','".$_POST['pdd_chemical1']."','".$before."','".$outtime."','".
 $itm."','".$_POST['remark']."','".$_POST['remark2']."','".$_POST['sample_number']."','0','".$smptime."','".$ordertime."',' ','".$_SESSION['uid']."','".$and_coa_fst."')";
}
else{$query="UPDATE        dbo.AnalyzeDesign
SET                  	  AND_COA_FST= '".$and_coa_fst."', AND_TOTAL= ".$_POST['sample_number'].", AND_GET_QTY = '".$_POST['sample_number']."', AND_BEFORE = '".$before."', AND_SMP_DATETIME = '".$smptime."', AND_NEED_NO='".$_POST['needno']."',
                          AND_ITEM = '".$itm."', AND_NOTE = '".$_POST['remark']."',AND_NOTE2 = '".$_POST['remark2']."', AND_OUT_DATETIME = '".$outtime."', 
                          AND_REPORT_DATETIME = '".$ordertime."', AND_MARK = ".$urgent.", CTD_CUST_NO='".$_POST['cust_no']."'
WHERE         (AND_LOT_NO = '".$_POST['lot_no']."')";}
$_SESSION['tmpppp']=$query;
$result = mssql_query($query);
if (!$result) {
    print("SQL statement failed with error:\n");
    print("   ".mssql_get_last_message()."\n");
  } else {
	  echo " Anylize Changed!!";}

if(($_SESSION['needno']<270) and ($_SESSION['needno']>150)){
	$query="select * from lot_no_rules where pid='".$_SESSION['AND_GOODS']."'";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	
}
mssql_close($dbhandle);
//jumpto($_SESSION['lasturl']);
}


if(isset($_POST["add_testitems"]))
{   session_start();
//////////////////////////////////////////////////////////
	$_SESSION['smptime']=$smptime=dod($_POST['datepicker1']).$_POST['order_time1'].$_POST['order_time2']."00";
	$_SESSION['outtime']=$outtime=dod($_POST['datepicker2']).$_POST['deliver_time1'].$_POST['deliver_time2']."00";
	$_SESSION['ordertime']=$ordertime=dod($_POST['datepicker3']).$_POST['order_time3'].$_POST['order_time4']."00";
	$url="index.php?url=add_testitems&AND_GOODS=".$_SESSION['AND_GOODS'];
	jumpto($url);
//	$query="UPDATE dbo.AnalyzeDesign SET AND_ITEM = '".$_SESSION['testitems']."' WHERE (AND_LOT_NO = '".$_POST['lot_no']."')" ;
//	$result = mssql_query($query);
}

if(isset($_POST['rcv_bottle']))
{
	$_SESSION['lot_no']=$_POST['lot_no'];
	$url="index.php?url=samp_rcv&lot_no=".$llotnoo ;
	jumpto($url);
}

if(isset($_POST['cancel'])){
	if($_SESSION['ccancel']<>1){
	$query="UPDATE        dbo.AnalyzeDesign
			SET                  AND_CANCEL = 1, CTD_CUST_NO = ''
			WHERE         (AND_LOT_NO = '".$_POST['lot_no']."')";
	$result=mssql_query($query);
	changelog('AnalyzeDesign','insert','AND_CANCEL','1',$_SESSION['uid'],date("Ymdhis"));
	refresh();}
	elseif($_SESSION['ccancel']==1){
		$query="UPDATE        dbo.AnalyzeDesign
			SET                  AND_CANCEL = 0, CTD_CUST_NO = ''
			WHERE         (AND_LOT_NO = '".$_POST['lot_no']."')";
	$result=mssql_query($query);
	changelog('AnalyzeDesign','insert','AND_CANCEL','0',$_SESSION['uid'],date("Ymdhis"));
	refresh();		
	}
}

if(isset($_POST['leave'])){
$_SESSION['lot_no']='';
unset($_SESSION['dapic1']);
unset($_SESSION['items']);
unset($_SESSION['items1']);
jumpto($_SESSION['lasturl1']);
}

if(isset($_POST["load_"])){
$query="SELECT          AND_COA_FST, AND_APPLY_DATE, AND_NEED_NO, AND_LOT_NO, AND_GOODS, CTD_CUST_NO, AND_BEFORE, 
                            AND_SMP_DATETIME, AND_ITEM, AND_ANA_ID, AND_VALUE, AND_MEMO, AND_TOTAL, AND_OUT_QTY, 
                            AND_OUT_DATETIME, AND_REPORT_DATETIME, AND_MARK, AND_GET_QTY, AND_GET_DATETIME, 
                            AND_RESULT_DATETIME, AND_RESULT, AND_NOTE, AND_PERSON, ALM_IDENTITY51, ALM_IDENTITY52, 
                            ALM_IDENTITY53, ALM_IDENTITY54, ALM_IDENTITY55, ALM_IDENTITY56, ALM_IDENTITY13, AND_CANCEL
FROM              dbo.AnalyzeDesign
WHERE          (AND_LOT_NO = '".$_POST['lot_no']."')";

$result = mssql_query($query);
$numRows = mssql_num_rows($result);
if($numRows>0){
	
				while($row = mssql_fetch_array($result)){	
				$_SESSION['cancel']=$row['AND_CANCEL'];
				$_SESSION['datepicker4']=odo($row['AND_APPLY_DATE']);
				$_SESSION['testitems']=$row['AND_ITEM'];
				$_SESSION['smptime']=$row['AND_SMP_DATETIME'];	
				$_SESSION['outtime']=$row['AND_OUT_DATETIME'];
				$_SESSION['ordertime']=$row['AND_REPORT_DATETIME'];	
				$_SESSION['lot_no']=$_POST['lot_no'];
				$_SESSION['AND_GOODS']=$row['AND_GOODS'];
				$_SESSION['items']=$row['AND_ITEM'];
				$_SESSION['and_before']=$row['AND_BEFORE'];
				$_SESSION['and_coa_fst']=$row['AND_COA_FST'];
				$_SESSION['and_mark']=$row['AND_MARK'];
				$_SESSION['and_total']=$row['AND_TOTAL'];
				$_SESSION['needno']=$row['AND_NEED_NO'];
				}
}
refresh();
}

?>


