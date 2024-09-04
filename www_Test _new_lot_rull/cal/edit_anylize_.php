<?php
session_start();
$_SESSION['lasturl']=$_SERVER['REQUEST_URI'];
include("../lib/fun.php");
include("../lib/jtsai.php");
include("../connections/conn.php");
datepick();
if($_GET['AND_LOT_NO']){$_SESSION['AND_LOT_NO']=$_GET['AND_LOT_NO'];}
elseif($_GET['lot_no']){$_SESSION['lot_no']=$_GET['lot_no'];}
	$bb=new get_from_lot_no;
	$bb->lid=$_SESSION['lot_no'];
	$bb->ani();
	$bb->cid();
	$_SESSION['AND_APPLY_DATE']=$bb->testdate;
	$_SESSION['prod_no']=$bb->pid;
	$_SESSION['outtime']=$bb->smptime;
	$_SESSION['sample_number']=$bb->smpnumber;
	$_SESSION['ccancel']=$bb->cancel;
	$_SESSION['AND_NEED_NO']=$bb->needno;
	$_SESSION['items']=$bb->items;
	$_SESSION['note']=$bb->note;
	$_SESSION['note2']=$bb->note2;
	$_SESSION['cust_no']=$bb->cid;
	$_SESSION['cname']=get_cust_name($_SESSION['cust_no']);
	$_SESSION['smptime']=$bb->smptime;
	if($_SESSION['AND_NEED_NO']>270){$_SESSION['class_name']='受入';}
	if($_SESSION['AND_NEED_NO']<151){$_SESSION['class_name']='製品';}
	else{$_SESSION['class_name']='解析';}
	
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<title>修改依賴</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">

    <label for="index"></label>
<input type="hidden" name="cvcancel" value="<?php echo $bb->cancel;?>" />
  <table width="1237" border="1" <?php echo $_SESSION['cancel'];?>>
    <tr>
      <td width="617"><input type="hidden" name="index" id="index" />
        <label for="datepicker1"></label>
日期時間:
<input name="datepicker4" type="text" id="datepicker4" size="6"  onchange="set_date_session(this.name,this.value)" value="<?php 
if($_GET['AND_APPLY_DATE']<>''){echo $_GET['AND_APPLY_DATE'];}
else{echo ($_SESSION['datepicker4']);}
if($_SESSION['needno']<=150){$_SESSION['class_name']='製品';}
if(($_SESSION['needno']>150) and ($_SESSION['needno']<=270)){$_SESSION['class_name']='解析';}
if($_SESSION['needno']>270){$_SESSION['class_name']='受入';}
?>" readonly="readonly"/>
&nbsp;目的：
<input type="button" name="class1" id="class1" value="<?php $_SESSION['edit_anylize']=$_SERVER['REQUEST_URI'];	echo $_SESSION['class_name'];?>" onclick="window.open('index.php?url=select_class&lot_no=<?php echo $_SESSION['lid'];?>', '_self');"/>
<?php if($_SESSION['class_name']=='受入'){
		echo "廠商:".get_cust_name($_SESSION['cid']) ;
		echo '&nbsp;&nbsp;';
		echo "規格:".get_cust_name($_SESSION['cust_no']);
	
	} ;?>
<input type="hidden" name="class" id="index" value="<?php echo $_SESSION['needno']; ?>"  />
<input type="hidden" name="cust_no" id="index" value="<?php echo $_SESSION['cust_no']; ?>"  />
<label for="class"></label>
&nbsp;
<label for="urgent">先行COA：
  <input type="checkbox" name="and_coa_fst" id="and_coa_fst" <?php 
	  	if($_SESSION['and_coa_fst']=="Y"){
			echo "checked";
		}
	  ?>
      />
</label></td>
      <td width="620">是否先行
      <input type="checkbox" name="before" id="before" 
	  <?php 
	  	if($_SESSION['and_before']=="Y"){
			echo "checked";
		}
	  ?>
      />
      <label for="before">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;總瓶數：</label>
      <label for="sample_number"></label>
      <input name="sample_number" type="text" id="sample_number" size="4" value="<?php 
	  echo $_SESSION['and_total'];
	  ?>"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lot No：
      <input name="lot_no" type="text" id="lot_no" size="10" value="<?php 	  
		echo trim($_SESSION['AND_LOT_NO']);
	  ?>"
      /> 
      <input type="submit" name="load_" id="load_" value="取得資料" />&nbsp;&nbsp;&nbsp;&nbsp;</td>
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
	    if($_GET['AND_SMP_DATETIME']<>''){$_SESSION['smptime']=$_GET['AND_SMP_DATETIME'];echo ttd($_SESSION['smptime']);}
		elseif($_SESSION['smptime']<>''){echo ttd($_SESSION['smptime']);}
		else{echo date("m/d/Y");}
	  ?>" />
      &nbsp;&nbsp;
      <label for="order_time"></label>
      <input name="order_time1" type="text" id="order_time1" size="4"  value="<?php 
	  if($_GET['AND_SMP_DATETIME']<>''){$_SESSION['smptime']=$_GET['AND_SMP_DATETIME'];}
	 	if($_SESSION['smptime']<>''){echo th($_SESSION['smptime']);}
		else{echo '08';}
	  ?>"/>
：
<input name="order_time2" type="text" id="order_time2" size="4" value="<?php 
if($_GET['AND_SMP_DATETIME']<>''){$_SESSION['smptime']=$_GET['AND_SMP_DATETIME'];}
		if($_SESSION['smptime']<>''){echo tm($_SESSION['smptime']);}
		else{echo '30';}
	  ?>"/>  
<span class="d1"> &nbsp;&nbsp;
<input type="submit" name="rcv_bottle" id="rcv_bottle" value="  接收樣品瓶  " onclick="window.open('index.php?url=samp_rcv&lot_no=<?php echo $_GET['AND_LOT_NO'];?>', '_blank');"/>
<input name="new" type="submit" id="new" value="新增/修改" />
</span></td>
    </tr>
     <tr>
       <td>
         <input type="checkbox" name="deliver_time" id="deliver_time" />
         <label for="deliver_time"></label>
         &nbsp;&nbsp;送樣時間：
         <input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php 
		 if($_GET['outdate']<>''){$_SESSION['datepicker2']=$_GET['outdate'];}
	 	if($_SESSION['datepicker2']<>''){echo ttd($_SESSION['datepicker2']);}
		else{echo date("m/d/Y");}
	  ?>" onchange="set_date_session(this.name,this.value)" />
         &nbsp;&nbsp;
         <input name="deliver_time1" type="text" id="deliver_time1" size="4" value="<?php 
	 	if($_SESSION['outtime']<>''){echo th($_SESSION['outtime']);}
		elseif($_GET['AND_OUT_DATETIME']<>''){echo th($_GET['AND_OUT_DATETIME']);}
		else{echo '09';}
	  ?>" />
         ：
         <input name="deliver_time2" type="text" id="deliver_time2" size="4" value="<?php 
	 	if($_SESSION['outtime']<>''){echo tm($_SESSION['outtime']);}
		elseif($_GET['AND_OUT_DATETIME']<>''){echo tm($_GET['AND_OUT_DATETIME']);}
		else{echo '30';}
	  ?>" />
         緊急樣本：
         <input type="checkbox" name="urgent" id="urgent" <?php 
	  	if($_SESSION['and_mark']=="1"){
			echo "checked";
		}
	  ?>
      />
       <BR />
       Metal 先行樣品
         <input type="text" name="sample_metal_f" onchange="set_date_session(this.name,this.value)" value="<?php echo $_SESSION['sample_metal_f'];?>" size="4"/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Particle 先行樣品
<input type="text" name="sample_particle_f" onchange="set_date_session(this.name,this.value)" value="<?php echo $_SESSION['sample_particle_f'];?>" size="4"/>
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
<input name="cancel" type="submit" <?php 
	if($_SESSION['ccancel']==1){echo 'value=" 恢復依賴 "';}
	else{echo 'value=" 取消依賴 "';}
?> onClick="return confirm('確定更改 Lot：<?php echo $_SESSION['lot_no']?> 的依賴?')"/>
<input name="leave" type="submit" value=" 離開 "/>
    </tr>
    <tr>
    <td>分析項目：
      <label for="testitems"></label>
      <input type="submit" name="add_testitems" id="add_testitems" value="新增/修改分析項目" onclick="window.open('index.php?url=add_testitems&AND_GOODS=<?php if($_GET['AND_GOODS']<>'')
	  {$_SESSION['AND_GOODS']=$_GET['AND_GOODS'];}echo $_SESSION['AND_GOODS'];?> ', '_self');" />
      <input name="fst_all" type="submit" id="fst_all" value="月初全項" />
      <br />
<?php

$loginFormAction = $_SERVER['PHP_SELF'];
include("../connections/conn.php");
if($_SESSION['items1']<>''){$itm=$_SESSION['items1'];}
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
    <td><p><strong>非定常分析</strong>
        <?PHP
	  $query1="select rut from AnalyzeDesign where AND_LOT_NO like '%".$_SESSION['lot_no']."%'";
	  $result1= mssql_query($query1);
	  $row1 = mssql_fetch_array($result1);
	  if(trim($row1['rut'])=='1'){$check='checked';}else{$check='';}
      echo '<input type="checkbox" name="rut" '.$check.' /><BR />';
	  ?>
    </p>
      <p>備註1：
        <label for="remark"></label>
        <textarea name="remark" id="remark" cols="65" rows="7" ><?php echo $_SESSION['note'];?></textarea>
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
$query="SELECT          	AND_APPLY_DATE, AND_NEED_NO, AND_LOT_NO, AND_GOODS, CTD_CUST_NO, AND_BEFORE, 
                            AND_SMP_DATETIME, AND_ITEM, AND_ANA_ID, AND_VALUE, AND_MEMO, AND_TOTAL, AND_OUT_QTY, 
                            AND_OUT_DATETIME, AND_REPORT_DATETIME, AND_MARK, AND_GET_QTY, AND_GET_DATETIME, 
                            AND_RESULT_DATETIME, AND_RESULT, AND_NOTE, AND_PERSON, ALM_IDENTITY51, ALM_IDENTITY52, 
                            ALM_IDENTITY53, ALM_IDENTITY54, ALM_IDENTITY55, ALM_IDENTITY56, ALM_IDENTITY13, AND_CANCEL
FROM              dbo.AnalyzeDesign
WHERE          (AND_LOT_NO = '".$_SESSION['lid']."')";

$result = mssql_query($query);
$numRows = mssql_num_rows($result);
if($numRows>0){
	
				while($row = mssql_fetch_array($result)){	
				$_SESSION['cancel']=$row['AND_CANCEL'];
				$_SESSION['testitems']=$row['AND_ITEM'];
				$_SESSION['smptime']=$row['AND_SMP_DATETIME'];	
				$_SESSION['outtime']=$row['AND_OUT_DATETIME'];
				$_SESSION['ordertime']=$row['AND_REPORT_DATETIME'];	
				$_SESSION['lot_no']=$_POST['lot_no'];
				$_SESSION['prod_no']=$row['AND_GOODS'];
				$_SESSION['items']=$row['AND_ITEM'];
				
$url="index.php?url=edit_anylize_&AND_APPLY_DATE=".$row['AND_APPLY_DATE']."&AND_NEED_NO=".$row['AND_NEED_NO']."&AND_LOT_NO=".$row['AND_LOT_NO'].
"&AND_GOODS=".$row['AND_GOODS']."&CTD_CUST_NO=".$row['CTD_CUST_NO']."&AND_BEFORE=".$row['AND_BEFORE']."&AND_SMP_DATETIME=".$row['AND_SMP_DATETIME'].
"&AND_ITEM=".$row['AND_ITEM']."&AND_ANA_ID=".$row['AND_ANA_ID']."&AND_VALUE=".$row['AND_VALUE']."&AND_MEMO=".$row['AND_MEMO']."&AND_TOTAL=".$row['AND_
TOTAL']."&AND_OUT_QTY=".$row['AND_OUT_QTY']."&AND_OUT_DATETIME=".$row['AND_OUT_DATETIME']."&AND_REPORT_DATETIME=".$row['AND_REPORT_DATETIME']."&AND_MAR
K=".$row['AND_MARK']."&AND_GET_QTY=".$row['AND_GET_QTY']."&AND_GET_DATETIME=".$row['AND_GET_DATETIME']."&AND_RESULT_DATETIME=".$row['AND_RESULT_DATETIM
E']."&AND_RESULT=".$row['AND_RESULT']."&AND_NOTE=".$row['AND_NOTE']."&AND_PERSON=".$row['AND_PERSON']."&AND_MARK=".$row['AND_MARK'];
				}
					if($_SESSION['cancel']==1){$_SESSION['cancel']='bgcolor="#CCCCCC"';}
				}
				else {echo "查無資料";
				}

//////////////////////////////////////////////////////////
if(isset($_POST["subentry"]))
{   session_start();
//////////////////////////////////////////////////////////
	$smptime=dod($_POST['datepicker1']).$_POST['order_time1'].$_POST['order_time2'];
	$outtime=dod($_POST['datepicker2']).$_POST['deliver_time1'].$_POST['deliver_time2']."00";
	$ordertime=dod($_POST['datepicker3']).$_POST['order_time3'].$_POST['order_time4']."00";
	$query="UPDATE dbo.AnalyzeDesign SET AND_ITEM = '".$_SESSION['testitems']."' WHERE (AND_LOT_NO = '".$_POST['lot_no']."')" ;
	$result = mssql_query($query);

if (!$result) {
    print("SQL statement failed with error:\n");
    print("   ".mssql_get_last_message()."\n");
  } else {  //echo $_SESSION['testitems'];
}
} 

if($_GET['AND_LOT_NO']<>''){
	session_start();
	$_SESSION['AND_LOT_NO']=$_POST['AND_LOT_NO']=$_GET['lot_no'];
$query="SELECT          	AND_APPLY_DATE, AND_NEED_NO, AND_LOT_NO, AND_GOODS, CTD_CUST_NO, AND_BEFORE, 
                            AND_SMP_DATETIME, AND_ITEM, AND_ANA_ID, AND_VALUE, AND_MEMO, AND_TOTAL, AND_OUT_QTY, 
                            AND_OUT_DATETIME, AND_REPORT_DATETIME, AND_MARK, AND_GET_QTY, AND_GET_DATETIME, 
                            AND_RESULT_DATETIME, AND_RESULT, AND_NOTE, AND_PERSON, ALM_IDENTITY51, ALM_IDENTITY52, 
                            ALM_IDENTITY53, ALM_IDENTITY54, ALM_IDENTITY55, ALM_IDENTITY56, ALM_IDENTITY13
FROM              dbo.AnalyzeDesign
WHERE          (AND_LOT_NO = '".$_SESSION['lot_no']."')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
if($numRows>0){
				while($row = mssql_fetch_array($result)){	
				$_SESSION['testitems']=$row['AND_ITEM'];
								
$url="index.php?url=edit_anylize_&AND_APPLY_DATE=".$row['AND_APPLY_DATE']."&AND_NEED_NO=".$row['AND_NEED_NO']."&AND_LOT_NO=".$row['AND_LOT_NO'].
"&AND_GOODS=".$row['AND_GOODS']."&CTD_CUST_NO=".$row['CTD_CUST_NO']."&AND_BEFORE=".$row['AND_BEFORE']."&AND_SMP_DATETIME=".$row['AND_SMP_DATETIME']."&AND_ITEM=".
$row['AND_ITEM']."&AND_ANA_ID=".$row['AND_ANA_ID']."&AND_VALUE=".$row['AND_VALUE']."&AND_MEMO=".$row['AND_MEMO']."&AND_TOTAL=".$row['AND_TOTAL']."&AND_OUT_QTY=".
$row['AND_OUT_QTY']."&AND_OUT_DATETIME=".$row['AND_OUT_DATETIME']."&AND_REPORT_DATETIME=".$row['AND_REPORT_DATETIME']."&AND_MARK=".$row['AND_MARK']."&AND_GET_QTY=".
$row['AND_GET_QTY']."&AND_GET_DATETIME=".$row['AND_GET_DATETIME']."&AND_RESULT_DATETIME=".$row['AND_RESULT_DATETIME']."&AND_RESULT=".$row['AND_RESULT']."&AND_NOTE=".
$row['AND_NOTE']."&AND_PERSON=".$row['AND_PERSON']."&AND_MARK=".$row['AND_MARK'];
				}
				}
				else {echo ".....";
				}
}

if(isset($_POST["new"])){
	if($_POST['rut']=='on'){
		$rut=1;	
	}
	else{
		$rut=0;	
	}
//////////////////////////////////////////////////////////
	$smptime=dod($_POST['datepicker1']).$_POST['order_time1'].$_POST['order_time2']."00";
	$outtime=dod($_POST['datepicker2']).$_POST['deliver_time1'].$_POST['deliver_time2']."00";
	$ordertime=dod($_POST['datepicker3']).$_POST['order_time3'].$_POST['order_time4']."00";
	if($_POST['before']=="on"){$before="Y";}else{$before="N";}
	if($_POST['and_coa_fst']=="on"){$and_coa_fst="Y";}else{$and_coa_fst="N";}
	if($_POST['urgent']=="on"){$urgent="1";}else{$urgent="0";}
	if($_SESSION['items1']<>''){$itm=$_SESSION['items1'];}
	else{$itm=$_SESSION['items'];}
	if($_SESSION['needno']==NULL){$_SESSION['needno']=1;}
	$query="select * from dbo.AnalyzeDesign where AND_LOT_NO='".$_POST['lot_no']."'";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	if($itm==''){$itm='0';}
	if(trim($_POST['datepicker4'])==''){$_POST['datepicker4']=date("m/d/Y");}
	if($_SESSION['class_name']<>'受入')
	{
		if($numRows<1)
		{
			$query="INSERT INTO dbo.AnalyzeDesign (rut,AND_COA_FST, AND_RESULT,AND_RESULT_DATETIME,AND_GET_DATETIME,AND_GET_QTY,AND_MARK,AND_VALUE,AND_ANA_ID,AND_APPLY_DATE, AND_NEED_NO, AND_LOT_NO, AND_GOODS, AND_BEFORE, AND_SMP_DATETIME, AND_ITEM, AND_NOTE, AND_NOTE2, AND_TOTAL, AND_OUT_QTY, AND_OUT_DATETIME, AND_REPORT_DATETIME, CTD_CUST_NO, AND_PERSON) VALUES ('".$rut."','".$and_coa_fst."','','','','0','".$urgent."','','','".date("Ymd")."',".$_SESSION['needno'].",'".$_POST['lot_no']."','".$_POST['pdd_chemical1']."','".$before."','".$outtime."','".$itm."','".$_POST['remark']."','".$_POST['remark2']."','".$_POST['sample_number']."','0','".$smptimeouttime."','".$ordertime."','".$_POST['cust_no']."','".$_SESSION['uid']."')";
		}
		else
		{
			$query="UPDATE        dbo.AnalyzeDesign
					SET                  	  rut='".$rut."',AND_APPLY_DATE = '".dod($_POST['datepicker4'])."',AND_GOODS = '".$_POST['pdd_chemical1']."',AND_TOTAL = '".$_POST['sample_number']."', AND_BEFORE = '".$before."', AND_SMP_DATETIME = '".$smptime."', AND_NEED_NO='".$_POST['class']."',
							  AND_ITEM = '".$itm."', AND_NOTE = '".$_POST['remark']."', AND_NOTE2 = '".$_POST['remark2']."', AND_OUT_DATETIME = '".$outtime."', AND_COA_FST = '".$and_coa_fst."', 
							  AND_REPORT_DATETIME = '".$ordertime."', AND_MARK = ".$urgent.", CTD_CUST_NO='".$_POST['cust_no']."',  AND_LAST='".$_SESSION['uid']."'  	
					WHERE         (AND_LOT_NO = '".$_POST['lot_no']."')";
		}
					
	}
	else
	{
		if($numRows<1)
		{
			$query="INSERT INTO dbo.AnalyzeDesign
								(rut,AND_COA_FST, AND_RESULT,AND_RESULT_DATETIME,AND_GET_DATETIME,AND_GET_QTY,AND_MARK,AND_VALUE,AND_ANA_ID,AND_APPLY_DATE, AND_NEED_NO, AND_LOT_NO, AND_GOODS, AND_BEFORE, 
								AND_SMP_DATETIME, AND_ITEM, AND_NOTE, AND_NOTE2, AND_TOTAL, AND_OUT_QTY, AND_OUT_DATETIME, AND_REPORT_DATETIME, CTD_CUST_NO, AND_PERSON)
					 VALUES          ('".$rut."','".$and_coa_fst."','','','','0','".$urgent."','','','".date("Ymd")."',".$_SESSION['needno'].",'".$_POST['lot_no']."','".$_POST['pdd_chemical1']."','".$before."','".$outtime."','".
					$itm."','".$_POST['remark']."','".$_POST['remark2']."','".$_POST['sample_number']."','0','".$smptime."','".$ordertime."','".$_POST['cust_no']."','".$_SESSION['uid']."')";
		}
		else
		{
			$query="UPDATE        dbo.AnalyzeDesign
					SET                  	  rut='".$rut."',AND_APPLY_DATE = '".dod($_POST['datepicker4'])."',AND_GOODS = '".$_POST['pdd_chemical1']."',AND_TOTAL = '".$_POST['sample_number']."', AND_BEFORE = '".$before."', AND_SMP_DATETIME = '".$smptime."', AND_NEED_NO='".$_POST['class']."',
							  AND_ITEM = '".$itm."', AND_NOTE = '".$_POST['remark']."', AND_NOTE2 = '".$_POST['remark2']."', AND_OUT_DATETIME = '".$outtime."', AND_COA_FST = '".$and_coa_fst."', 
							  AND_REPORT_DATETIME = '".$ordertime."', AND_MARK = ".$urgent.", AND_LAST='".$_SESSION['uid']."'  	WHERE         (AND_LOT_NO = '".$_POST['lot_no']."')";
		}
					
	}
echo $query;	
sql_rec($_SERVER['PHP_SELF'],$query);  
$result = mssql_query($query);
if (!$result) {
    print("SQL statement failed with error:\n");
    print("   ".mssql_get_last_message()."\n");
  } else {
	  echo " Anylize Changed!!";}
mssql_close($dbhandle);
if($_SESSION['lasturl1']<>''){
	jumpto($_SESSION['lasturl1']);
}
	jumpto("../fill/index.php?url=fill_out_plan");
}


if(isset($_POST["add_testitems"]))
{   session_start();
//////////////////////////////////////////////////////////
	$_SESSION['smptime']=$smptime=dod($_POST['datepicker1']).$_POST['order_time1'].$_POST['order_time2']."00";
	$_SESSION['outtime']=$outtime=dod($_POST['datepicker2']).$_POST['deliver_time1'].$_POST['deliver_time2']."00";
	$_SESSION['ordertime']=$ordertime=dod($_POST['datepicker3']).$_POST['order_time3'].$_POST['order_time4']."00";
	$url="index.php?url=add_testitems&AND_GOODS=".$_GET['AND_GOODS'];
	jumpto($url);
//	$query="UPDATE dbo.AnalyzeDesign SET AND_ITEM = '".$_SESSION['testitems']."' WHERE (AND_LOT_NO = '".$_POST['lot_no']."')" ;
//	$result = mssql_query($query);
}

if(isset($_POST['rcv_bottle']))
{
	$_SESSION['lot_no']=$_POST['lot_no'];
	$url="index.php?url=samp_rcv&lot_no=".$_POST['lot_no'] ;
	jumpto($url);
}

if(isset($_POST['cancel'])){
	if($_POST['cvcancel']<>1){
	$query="UPDATE        dbo.AnalyzeDesign
			SET                  AND_CANCEL = 1, CTD_CUST_NO = '', AND_LAST='".$_SESSION['uid']."' 
			WHERE         (AND_LOT_NO = '".$_POST['lot_no']."')";
			echo $query;
	$result=mssql_query($query);
	
	changelog('AnalyzeDesign','insert','AND_CANCEL','1',$_SESSION['uid'],date("Ymdhis"));
	refresh();
	}
	elseif($_POST['cvcancel']==1){
		$query="UPDATE        dbo.AnalyzeDesign
			SET                  AND_CANCEL = 0, CTD_CUST_NO = '', AND_LAST='".$_SESSION['uid']."'  	
			WHERE         (AND_LOT_NO = '".$_POST['lot_no']."')";
			echo $query;
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
	if($_GET['AND_LOT_NO']){$_SESSION['AND_LOT_NO']=$_SESSION['lid']=$_SESSION['lot_no']=$_POST['lot_no']=$_GET['AND_LOT_NO'];}
	$_SESSION['AND_LOT_NO']=$_SESSION['lid']=$_SESSION['lot_no']=$_POST['lot_no'];
	$query="SELECT          AND_COA_FST, AND_APPLY_DATE, AND_NEED_NO, AND_LOT_NO, AND_GOODS, CTD_CUST_NO, AND_BEFORE, 
                            AND_SMP_DATETIME, AND_ITEM, AND_ANA_ID, AND_VALUE, AND_MEMO, AND_TOTAL, AND_OUT_QTY, 
                            AND_OUT_DATETIME, AND_REPORT_DATETIME, AND_MARK, AND_GET_QTY, AND_GET_DATETIME, 
                            AND_RESULT_DATETIME, AND_RESULT, AND_NOTE, AND_PERSON, ALM_IDENTITY51, ALM_IDENTITY52, 
                            ALM_IDENTITY53, ALM_IDENTITY54, ALM_IDENTITY55, ALM_IDENTITY56, ALM_IDENTITY13, AND_CANCEL
FROM              dbo.AnalyzeDesign
WHERE          (AND_LOT_NO = '".$_SESSION['AND_LOT_NO']."')";

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

if(isset($_POST['fst_all'])){
	$lt=new get_from_lot_no;
	$lt->lid=$_POST['lot_no'] ;
	$lt->cid();
	$custid=$lt->cid;
	$query="SELECT          Monthly_first
FROM              Analyze_Rulls where PDD_PROD_NO='".$_POST['pdd_chemical1']."' and  CTD_CUST_NO like '%".$custid."%'";
	$result=mssql_query($query);
	$rows=mssql_num_rows($result);
	if($rows>0){
		$row=mssql_fetch_row($result);
		echo $_SESSION['items1']=$row[0];
	}
	else{
		$query="SELECT          Monthly_first
FROM              Analyze_Rulls where PDD_PROD_NO='".$_POST['pdd_chemical1']."' and  CTD_CUST_NO like 'C00001'";
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);
		echo $_SESSION['items1']=$row[0];
	}
	refresh();
}
?>


