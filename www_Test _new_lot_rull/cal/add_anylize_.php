<?php
session_start();
if($_SESSION['X09']==''){
	$_SESSION['X09']=5;
}
$_SESSION['lasturl']="/cal/index.php?url=add_anylize_";
include("../lib/lot_no.php");
lastpage("add_analyze_");
if($_GET['AND_LOT_NO']<>''){
	$_SESSION['lot_no']=$_GET['AND_LOT_NO'];
	xload($_GET['AND_LOT_NO']);
}
	
	

if(!isset($_SESSION['needno'])){$_SESSION['needno']=1;}
list($tfa,$pe,$particle,$metal)=cust_prod($_SESSION['cust_no'],$_SESSION['AND_GOODS']);

if($_SESSION['sample_metal_f']==''){$_SESSION['sample_metal_f']=0;}
if($_SESSION['sample_particle_f']==''){$_SESSION['sample_particle_f']=0;}
if($_SESSION['needno']>=500){
		$ifid='hidden="hidden"';
		$inid='';
	}
	else{
		$ifid='';
		$inid=' disabled ';
		
	}

			if($_SESSION['needno']==1){
				$needno1='selected';$needno2='';$needno3='';$needno4='';
			}
			if($_SESSION['needno']==151){
				$needno1='';$needno2='selected';$needno3='';$needno4='';
			}
			if($_SESSION['needno']==271){
				$needno1='';$needno2='';$needno3='selected';$needno4='';
			}
			if($_SESSION['needno']==501){
				$needno1='';$needno2='';$needno3='';$needno4='selected';
			}
// xload($_GET['AND_LOT_NO']);
?>
<title>修改依賴</title>
<font size="+2" color="red">如果是編輯依賴請先點選取得資料</font>
<body><table width="1240" style='border-left:1.0pt solid black;border-right:1.0pt solid black;border-buttom:1.0pt solid black;border-top:1.0pt solid black'/>
<form id="form1" name="form1" method="post" action="">
    <tr>
      <td width="512">
日期時間:<input name="datepicker4" type="text" id="datepicker4" size="10"  onchange="set_date_session(this.name,this.value)" value="<?php 
		if($_SESSION['datepicker4']<>''){
			echo $_SESSION['datepicker4'];
			}
		else{
			echo date("m/d/Y");
			}
			
	  ?>" />
&nbsp;目的：
<select name="needno" id="needno"  onchange="set_date_session(this.name,this.value)" >
		<option value="1" <?php echo $needno1; ?> selected >製品</option>
    <option value="151" <?php echo $needno2; ?> >解析</option>
    <option value="271" <?php echo $needno3; ?>>受入</option>
    <option value="501" <?php echo $needno4; ?>>特殊分析</option>
</select>

<?php 
if($_SESSION['needno']==501){
	echo '<select name="reason" id="reason" >
		<option value="1.	客戶 月/季年/半年檢驗(前處理瓶_100ml)">1.	客戶 月/季年/半年檢驗(前處理瓶_100ml)</option>
    <option value="2.	客戶 月/季年/半年檢驗(非前處理瓶) ">2.	客戶 月/季年/半年檢驗(非前處理瓶) </option>
    <option value="3.	客戶 分析 OOS要求MCTW保留樣分析">3.	客戶 分析 OOS要求MCTW保留樣分析</option>
    <option value="4.	客戶 特殊委託分析(換管路/新桶槽….品質確認)">4.	客戶 特殊委託分析(換管路/新桶槽….品質確認)</option>
    <option value="5.	供應商 樣品評估">5.	供應商 樣品評估</option>
    <option value="6.	洗淨部 委託分析">6.	洗淨部 委託分析</option>
    <option value="7.	技術課 委託分析">7.	技術課 委託分析</option>
    <option value="8.	其他 (請於 Remark 說明)">8.	其他 (請於備註說明)</option>
</select>';
}
if($_SESSION['needno']>=171 and $_SESSION['needno']<500 )
{
	$_SESSION['cust_no']='C00001';
} 
?>
<input type="hidden" name="cust_no" id="index" value="<?php echo $_SESSION['cust_no']; ?>"  />
&nbsp;

&nbsp;
<font <?php echo $ifid; ?>>先行COA</font>
<input type="checkbox" name="and_coa_fst" id="and_coa_fst" <?php 
	  	if($_SESSION['and_coa_fst']=="Y"){
			echo "checked";
		}
		echo $ifid;
	  ?>
      /></td>
      <td width="226"  ><label <?php echo $ifid;?>>是否先行
        <input type="checkbox" name="before" id="before" 
	  <?php 
	  	if($_SESSION['and_before']=="Y"){
			echo "checked";
		}
	
	  ?>
      />	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;總瓶數：
      <input name="sample_number" type="text" id="sample_number" size="4" value="<?php 
	  if($_GET['sample_number']<>''){$_SESSION['sample_number']=$_GET['sample_number'];}
	  elseif($_SESSION['sample_number']<>''){}
	  else{$_SESSION['sample_number']=0;}
		  echo $_SESSION['sample_number'];
	  ?>" onchange="set_date_session(this.name,this.value)"/>
      <td width="286" align="left">  
	  </label>
	  保存樣品<input type="checkbox" name="keep_sample" <?php if($_SESSION['keep_sample']=='1'){echo 'checked="checked"';}else{echo '';}?>>
      <?php 
	  		if(($_SESSION['needno']<270) and ($_SESSION['needno']>150))
			{
				echo "LOT NO：";
				$aa=new creat_ana_lot_no;
				if($_SESSION['datepicker4']<>''){$aa->day=dod($_SESSION['datepicker4']);}
				else{$aa->day=date("Ymd");}
				$aa->pid=$_SESSION['AND_GOODS'];
				$aa->type_ana();
				$post_str=$aa->post_str;	
				echo $aa->new_lotno;
			}
			else{
			//	my_msg(substr(trim($_SESSION['lot_no']),-2));
				if(substr(trim($_SESSION['lot_no']),-2)=='_C'){
					$_SESSION['lot_no']=substr(trim($_SESSION['lot_no']),0,-2);
				}
				$llotnoo=$_SESSION['lot_no'];
				
      		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			Lot No：<input name="lot_no" type="text" id="lot_no" size="10" value="'.trim($_SESSION['lot_no']).'"  onchange="set_date_session(this.name,this.value)" />';
			if($_SESSION['needno']>=500){
				echo "_C";
			}
			echo ' <input type="submit" name="load_" id="load_" value="取得資料" />&nbsp;&nbsp;&nbsp;&nbsp;</td>';}
	  ?>
    </td></tr>
    <tr>
      <td>
      品名：
      <input type="button" name="X" id="X" value="X" onclick="window.open('../erase_prod.php ', '_self');" />
      <input name="pdd_chemical1" type="text" id="pdd_chemical1" size="10" value="<?php 
	  echo $_SESSION['AND_GOODS']; 
	  ?>" />
      <input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onclick="window.open('../main.php?url=pdd_prod_no2 ', '_self');" />
      <input name="pdd_chemical2" type="text" id="pdd_chemical2" size="16" value="<?php echo get_prod_name($_SESSION['AND_GOODS']);?>"  />
  <label>
      <BR>客戶：
          <input <?php echo $inid;?>  type="button" name="X3" id="X3" value="X" onclick="window.open('../erase_customer.php ', '_self');" />
          <input name="cust_no" type="text" id="cust_no" size="10" value="<?php echo $_SESSION["cust_no"] ;?>" readonly="readonly" />
          <input  <?php echo $inid;?> type="button" name="pdd_no2" id="pdd_no2" value="查詢" onclick="window.open('../cust_no.php?sup=N ', '_self');" />
          <input  <?php echo $inid;?> name="cust_name" type="text" id="cust_name" size="16" value="<?php echo get_cust_name($_SESSION["cust_no"]);?>" readonly="readonly"/>
	</label>
      </td>
      
    <td><label  <?php echo $ifid;?> style='border-top:none;text-align:right;'><font <?php echo $ifid;?>>預定取樣時間：<BR></font>
      <label form="datepicker1"></label>
      <input name="datepicker1" <?php echo $ifid;?> type="text" id="datepicker1" size="6" value="<?php 
	  	if($_SESSION['datepicker1']<>''){echo $_SESSION['datepicker1'];}
		elseif($_SESSION['smptime']<>''){echo ttd($_SESSION['smptime']);}
		else{echo date("m/d/Y");}
	  ?>" onchange="set_date_session(this.name,this.value)" readonly>
      &nbsp;
		
      <input name="order_time1" type="text" id="order_time1" size="1"  value="<?php 
	 	if($_SESSION['smptime']<>''){echo th($_SESSION['smptime']);}
		else{echo '08';}
	  ?>"/>
<font <?php echo $ifid;?>>：</font>
<input name="order_time2"  type="text" id="order_time2" size="1" value="<?php 
		if($_SESSION['smptime']<>''){echo tm($_SESSION['smptime']);}
		else{echo '30';}
	  ?>"
	  /> </td><td align="right"> </label>
<span class="d1"> <br>
<input type="submit" name="rcv_bottle" id="rcv_bottle" value="  接收樣品瓶  " onclick="window.open('index.php?url=samp_rcv&lot_no=<?php echo $llotnoo;?>', '_self');"/>
<input name="new" type="submit" id="new" value="新增/修改" />&nbsp;&nbsp;&nbsp;&nbsp;
</span></td>
    </tr>
     <tr>
       <td><label for="deliver_time" <?php echo $ifid;?>>
       	<input type="checkbox" name="deliver_time" id="deliver_time" />
         
         &nbsp;送樣時間：
         <input name="datepicker2" type="text" id="datepicker2" size="6" value="<?php 
		if($_SESSION['datepicker2']<>''){echo $_SESSION['datepicker2'];}
		else{echo date("m/d/Y");}
	  ?>" onchange="set_date_session(this.name,this.value)" readonly/>
      &nbsp;
         <input name="deliver_time1" type="text" id="deliver_time1" size="1" value="<?php 
	 	if($_SESSION['outtime']<>''){echo th($_SESSION['outtime']);}
		else{echo '09';}
	  ?>" />
         ：
         <input name="deliver_time2" type="text" id="deliver_time2" size="1" value="<?php 
	 	if($_SESSION['outtime']<>''){echo tm($_SESSION['outtime']);}
		else{echo '30';}
	  ?>" />
         緊急樣本：
         <input type="checkbox" name="and_mark" id="and_mark" <?php 
	  	if($_SESSION['and_mark']=="1"){
			echo "checked";
		}
	
	  ?>
	  </label>
      
         <label for="urgent"><br />
       <font color="#FF0000">Metal 先行樣品 (必填) <input type="text" name="sample_metal_f" onchange="set_date_session(this.name,this.value)" value="<?php echo $_SESSION['sample_metal_f'];?>" size="4"/> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Particle 先行樣品(必填) </font><input type="text" name="sample_particle_f" onchange="set_date_session(this.name,this.value)" value="<?php echo $_SESSION['sample_particle_f'];?>" size="4"/></label></td>
    <td><label <?php echo $ifid;?>> 希望報告時間：<br>
      <input name="datepicker3" type="text" id="datepicker3" size="6" value="<?php 
	  if($_SESSION['datepicker3']<>''){echo $_SESSION['datepicker3'];}
		elseif($_SESSION['ordertime']){echo ttd($_SESSION['ordertime']);}
		else{$d=strtotime("+0 Days"); echo date("m/d/Y",$d);}
	  ?>" onchange="set_date_session(this.name,this.value)" readonly/>
&nbsp;
<label for="order_time"></label>
<input name="order_time3" type="text" id="order_time3" value="<?php 
		if($_SESSION['ordertime']){echo th($_SESSION['ordertime']);}
		else{echo '17';}
	  ?>" size="1" />
：
<input name="order_time4" type="text" id="order_time4" value="<?php 
		if($_SESSION['ordertime']){echo tm($_SESSION['ordertime']);}
		else{echo '30';}
	  ?>
	  " size="1" />&nbsp;&nbsp;</label>
	  </td><td align="right">
<input name="leave" type="submit" value=" 離開 " <?php echo $ifid;?>/> 
<?php 
  $_SESSION['cancel']=is_canceled($_SESSION['lot_no']) ;
  if($_SESSION['cancel']==1){echo "<BR>狀態: 已取消&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";}
  else{echo "<BR>依賴狀態:正常&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";}
?>
<input type="submit" name="cancel" value="取消/恢復依賴" />
    </tr>
  </table>
  <table width="1240" border="1" >
    <tr>
    <td height="280" width="512">分析項目：
      <label for="testitems"></label>
      <input type="submit" name="add_testitems" id="add_testitems" value="新增/修改分析項目" onclick="window.open('index.php?url=add_testitems&AND_GOODS=<?php if($_SESSION['AND_GOODS']<>'')
	  {$_SESSION['AND_GOODS']=$_SESSION['AND_GOODS'];}echo $_SESSION['AND_GOODS'];?>&cust_no=<?php echo $_POST['cust_no'];?> ', '_self');" />
      <input name="fst_all" type="submit" id="fst_all" value="月初全項" />
      <br />
<?php

$loginFormAction = $_SERVER['PHP_SELF'];
if($_SESSION['items1']){$itm=$_SESSION['items1'];}
else{$itm=$_SESSION['items'];}
$aa=explode(',',$itm);
	$query="SELECT DISTINCT ANI_GROUPNAME,ANI_ID, ANI_FULLNAME
FROM              AnalyzeItem
WHERE          (ANI_INDEX <>'') and ";
for($i=0;$i<count($aa);$i++){
	$query.="(ANI_INDEX =".$aa[$i].") OR ";}
	$query=substr($query,0,-4);
	//echo $query;
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
echo '<pre>';
while($row = mssql_fetch_array($result)){
echo $row['ANI_ID']."&#9;".$row['ANI_GROUPNAME']."&#9;".$row['ANI_FULLNAME']."</br>";
}
echo '</pre>';
echo "Total :".(count($aa))." 項</br>";
//echo $_SESSION['testitems'];
?></td><td>
    <label <?php echo $ifid;?>>
      <strong>非定常分析</strong>
      <?PHP
	  $query1="select rut from AnalyzeDesign where AND_LOT_NO like '%".$_SESSION['lot_no']."%'";
	  $result1= mssql_query($query1);
	  $row1 = mssql_fetch_array($result1);
	  if(trim($row1['rut'])=='1'){$check='checked';}else{$check='';}
      echo '<input type="checkbox" name="rut" '.$check.' /><BR />';
	  ?>
	</label>
      <p>備註1：
      <label for="remark"></label>
      <textarea name="remark" id="remark" cols="65" rows="5" ><?php echo trim($_SESSION['note']);?></textarea>
    </p>
    <p>備註2：
      <label for="remark2"></label>
      <textarea name="remark2" id="remark2" cols="65" rows="5" ><?php echo trim($_SESSION['note2']);?></textarea>
    </p> </td>   </tr>
  </table>
</form>
</body>
</html>
<?php
$group=rt_ani_group($_SESSION['items1']);
$url=$_SERVER['REQUEST_URI']."&lot_no=".$_POST['lot_no'];

//////////////////////////////////////////////////////////
if(isset($_POST["new"]))
{
	
//	echo "READON:".$_POST['reason']."<BR>";
				if($_POST['keep_sample']=='on'){$keep_sample=1;}else{$keep_sample=0;}
		
	$year=date("Y");
	if($_POST['rut']=='on'){
		$rut=1;	
	}
	else{
		$rut=0;	
	}
	/*
	if(substr(trim($_SESSION['new_lot_no']),7,4)=='TOCM' and substr(trim($_SESSION['new_lot_no']),0,2)=='S6'){
		$query="SELECT  TOP (1) SUBSTRING(AND_LOT_NO, 12, 1) AS Expr1 
FROM      AnalyzeDesign
WHERE   (AND_LOT_NO like '".$_SESSION['new_lot_no']."%') ORDER BY AND_LOT_NO DESC";
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);
		if($row[0]<>''){echo "<BR>ROW:".$row[0]."<BR>";
		$_SESSION['new_lot_no']=$_SESSION['new_lot_no'].($row[0]+1);}		
	}
*/
//	echo "YEAR:".$year."<BR>";
	echo "新增 Lot NO. ：".$_SESSION['new_lot_no']."<BR>";
//////////////////////////////////////////////////////////
	$smptime=dod($_POST['datepicker1']).trim($_POST['order_time1']).trim($_POST['order_time2'])."00";
	$smptime = str_pad($smptime, 14, "0", STR_PAD_RIGHT);
	
	$outtime=dod($_POST['datepicker2']).trim($_POST['deliver_time1']).trim($_POST['deliver_time2']);
	$outtime = str_pad($outtime, 14, "0", STR_PAD_RIGHT);
	
	$applytime=dod($_POST['datepicker4']);
	
	$ordertime=dod($_POST['datepicker3']).trim($_POST['order_time3']).trim($_POST['order_time4'])."00";
	$ordertime = str_pad($ordertime, 14, "0", STR_PAD_RIGHT);
		
	if($_POST['before']=="on"){$before="Y";}else{$before="N";}
	if($_POST['and_mark']=="on"){$urgent="1";}else{$urgent="0";}
	if($_POST['and_coa_fst']=="on"){$and_coa_fst="Y";}else{$and_coa_fst="N";}
	if($_SESSION['items1']<>''){$itm=$_SESSION['items1'];}
	else{$itm=$_SESSION['items'];}
	if($itm==''){$itm='0';}
	if($_SESSION['needno']<='150' or $_SESSION['needno']>='270'){$_SESSION['new_lot_no']=$_POST['lot_no'];}
	if(($_SESSION['needno']>'150') and ($_SESSION['needno']<'270')){$_SESSION['new_lot_no']=$post_str;}
	if($_SESSION['needno']>='500'){
		$_SESSION['new_lot_no']=trim($_POST['lot_no'])."_C";
	}
	
	if(substr(trim($_SESSION['new_lot_no']),7,4)=='TOCM' and substr(trim($_SESSION['new_lot_no']),0,2)=='S6'){
		$query="SELECT  TOP (1) SUBSTRING(AND_LOT_NO, 12, 1) AS Expr1 
FROM      AnalyzeDesign
WHERE   (AND_LOT_NO like '".$_SESSION['new_lot_no']."%') ORDER BY AND_LOT_NO DESC";
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);
		if($row[0]<>''){$_SESSION['new_lot_no']=$_SESSION['new_lot_no'].($row[0]+1);}		
	}


	if(substr($_SESSION['new_lot_no'],-2,2)=='RR' or substr($_SESSION['new_lot_no'],-3,2)=='RR')
	{  // check RR
		$n=$_SESSION['X09'];

		for($q1=0;$q1< $n;$q1++)
		{
			$query="select * from dbo.AnalyzeDesign where AND_LOT_NO='".$_SESSION['new_lot_no'].$q1."'";
			$result = mssql_query($query);
			$numRows = mssql_num_rows($result);
			if($numRows<1){
			$query="INSERT INTO dbo.AnalyzeDesign
									(AND_Item_Group,rut, AND_VALUE, AND_ANA_ID, AND_GET_DATETIME, AND_RESULT_DATETIME, AND_RESULT, AND_GET_QTY,AND_MARK,AND_APPLY_DATE, AND_NEED_NO, AND_LOT_NO, AND_GOODS, AND_BEFORE, 
									AND_SMP_DATETIME, AND_ITEM, AND_NOTE, AND_NOTE2, AND_TOTAL, AND_OUT_QTY, AND_OUT_DATETIME, AND_REPORT_DATETIME, CTD_CUST_NO, AND_PERSON , AND_COA_FST)
		  VALUES          ('".$group."',".$rut.",' ',' ',' ',' ',' ','0','".$urgent."','".dod($_POST['datepicker4'])."',".$_POST['needno'].",'".$_SESSION['new_lot_no'].$q1."','".$_POST['pdd_chemical1']."','".$before."','".$outtime."','".
		 $itm."','".$_POST['remark']."','".$_POST['remark2']."','".$_POST['sample_number']."','0','".$smptime."','".$ordertime."','".$_POST['pdd_chemical3']."','".$_SESSION['uid']."','".$and_coa_fst."')";
		}
		else{
			if(!isset($_POST['lot_no'])){$_POST['lot_no']=$_SESSION['lot_no'];}
			$query="UPDATE        dbo.AnalyzeDesign
		SET          AND_Item_Group='".$group."' ,rut=".$rut." ,AND_COA_FST= '".$and_coa_fst."', AND_TOTAL= ".$_POST['sample_number'].", AND_GET_QTY = '".$_POST['sample_number']."', AND_BEFORE = '".$before."', AND_SMP_DATETIME = '".$smptime."', AND_NEED_NO='".$_POST['needno']."',
								  AND_ITEM = '".$itm."', AND_NOTE = '".$_POST['remark']."',AND_NOTE2 = '".$_POST['remark2']."', AND_OUT_DATETIME = '".$outtime."', 
								  AND_REPORT_DATETIME = '".$ordertime."', AND_MARK = ".$urgent.", CTD_CUST_NO='".$_POST['pdd_chemical3']."',  AND_LAST='".$_SESSION['uid']."'
		WHERE         (AND_LOT_NO = '".$_SESSION['new_lot_no'].$q1."')";}
		echo $_SESSION['new_lot_no'].$q1."<BR>";
		$result = mssql_query($query);
		if (!$result) {
			print("SQL statement failed with error:\n");
			print("   ".mssql_get_last_message()."\n");
		  } else {
			  echo " Anylize Changed!!<BR>";}
	}//end for 
	} //end RR Check
	
	elseif(substr($_SESSION['AND_GOODS'],0,5)=='RCICP'){  // check RCICP
		for($q1="A";$q1<="D";$q1++)
		{
			$query="select * from dbo.AnalyzeDesign where AND_LOT_NO='".$_SESSION['new_lot_no'].$q1."'";
			$result = mssql_query($query);
			$numRows = mssql_num_rows($result);
			if($numRows<1){
			$query="INSERT INTO dbo.AnalyzeDesign
									(AND_Item_Group,rut,AND_VALUE, AND_ANA_ID, AND_GET_DATETIME, AND_RESULT_DATETIME, AND_RESULT, AND_GET_QTY,AND_MARK,AND_APPLY_DATE, AND_NEED_NO, AND_LOT_NO, AND_GOODS, AND_BEFORE, 
									AND_SMP_DATETIME, AND_ITEM, AND_NOTE, AND_NOTE2, AND_TOTAL, AND_OUT_QTY, AND_OUT_DATETIME, AND_REPORT_DATETIME, CTD_CUST_NO, AND_PERSON , AND_COA_FST)
		  VALUES          ('".$group."',".$rut.",' ',' ',' ',' ',' ','0','".$urgent."','".dod($_POST['datepicker4'])."',".$_POST['needno'].",'".$_SESSION['new_lot_no'].$q1."','".$_POST['pdd_chemical1']."','".$before."','".$outtime."','".
		 $itm."','".$_POST['remark']."','".$_POST['remark2']."','".$_POST['sample_number']."','0','".$smptime."','".$ordertime."','".$_POST['pdd_chemical3']."','".$_SESSION['uid']."','".$and_coa_fst."')";
		}
		else{
			if(!isset($_POST['lot_no'])){$_POST['lot_no']=$_SESSION['lot_no'];}
			$query="UPDATE        dbo.AnalyzeDesign
		SET          AND_Item_Group='".$group."' ,rut=".$rut.", AND_COA_FST= '".$and_coa_fst."', AND_TOTAL= ".$_POST['sample_number'].", AND_GET_QTY = '".$_POST['sample_number']."', AND_BEFORE = '".$before."', AND_SMP_DATETIME = '".$smptime."', AND_NEED_NO='".$_POST['needno']."',
								  AND_ITEM = '".$itm."', AND_NOTE = '".$_POST['remark']."',AND_NOTE2 = '".$_POST['remark2']."', AND_OUT_DATETIME = '".$outtime."', 
								  AND_REPORT_DATETIME = '".$ordertime."', AND_MARK = ".$urgent.", CTD_CUST_NO='".$_POST['pdd_chemical3']."',  AND_LAST='".$_SESSION['uid']."'
		WHERE         (AND_LOT_NO = '".$_POST['lot_no']."')";}
		$result = mssql_query($query);
		if (!$result) {
			print("SQL statement failed with error:\n");
			print("   ".mssql_get_last_message()."\n");
		  } else {
			  echo " Anylize Changed!<BR>";}
	}//end for 
	} //end RCICP Check
	
	elseif(substr($_SESSION['AND_GOODS'],0,4)=='RCIC'){  // check RCIC
		for($q1="A";$q1<="B";$q1++)
		{
			$query="select * from dbo.AnalyzeDesign where AND_LOT_NO='".$_SESSION['new_lot_no'].$q1."'";
			$result = mssql_query($query);
			$numRows = mssql_num_rows($result);
			if($numRows<1){
			$query="INSERT INTO dbo.AnalyzeDesign
									(AND_Item_Group,rut, AND_VALUE, AND_ANA_ID, AND_GET_DATETIME, AND_RESULT_DATETIME, AND_RESULT, AND_GET_QTY,AND_MARK,AND_APPLY_DATE, AND_NEED_NO, AND_LOT_NO, AND_GOODS, AND_BEFORE, 
									AND_SMP_DATETIME, AND_ITEM, AND_NOTE, AND_NOTE2, AND_TOTAL, AND_OUT_QTY, AND_OUT_DATETIME, AND_REPORT_DATETIME, CTD_CUST_NO, AND_PERSON , AND_COA_FST)
		  VALUES          ('".$group."',".$rut.",' ',' ',' ',' ',' ','0','".$urgent."','".dod($_POST['datepicker4'])."',".$_POST['needno'].",'".$_SESSION['new_lot_no'].$q1."','".$_POST['pdd_chemical1']."','".$before."','".$outtime."','".
		 $itm."','".$_POST['remark']."','".$_POST['remark2']."','".$_POST['sample_number']."','0','".$smptime."','".$ordertime."','".$_POST['pdd_chemical3']."','".$_SESSION['uid']."','".$and_coa_fst."')";
		}
		else{
			if(!isset($_POST['lot_no'])){$_POST['lot_no']=$_SESSION['lot_no'];}
			$query="UPDATE        dbo.AnalyzeDesign
		SET                 AND_Item_Group='".$group."' ,rut=".$rut.",AND_COA_FST= '".$and_coa_fst."', AND_TOTAL= ".$_POST['sample_number'].", AND_GET_QTY = '".$_POST['sample_number']."', AND_BEFORE = '".$before."', AND_SMP_DATETIME = '".$smptime."', AND_NEED_NO='".$_POST['needno']."',
								  AND_ITEM = '".$itm."', AND_NOTE = '".$_POST['remark']."',AND_NOTE2 = '".$_POST['remark2']."', AND_OUT_DATETIME = '".$outtime."', 
								  AND_REPORT_DATETIME = '".$ordertime."', AND_MARK = ".$urgent.", CTD_CUST_NO='".$_POST['pdd_chemical3']."',  AND_LAST='".$_SESSION['uid']."' 
		WHERE         (AND_LOT_NO = '".$_POST['lot_no']."')";}
		$result = mssql_query($query);
		if (!$result) {
			print("SQL statement failed with error:\n");
			print("   ".mssql_get_last_message()."\n");
		  } else {
			  echo " Anylize Changed!!<BR>";}
	}//end for 
	} //end RCIC Check
	
	elseif($_SESSION['AND_GOODS']=='GM-ICP75' or $_SESSION['AND_GOODS']=='GM-ICP75O' or $_SESSION['AND_GOODS']=='GM-ICP75M' or $_SESSION['AND_GOODS']=='GM-ICP77' or $_SESSION['AND_GOODS']=='GM-ICP77O' or $_SESSION['AND_GOODS']=='GM-ICP77M' or $_SESSION['AND_GOODS']=='GM-ICP79' or $_SESSION['AND_GOODS']=='GM-ICP79O' or $_SESSION['AND_GOODS']=='GM-ICP79M' 
	or $_SESSION['AND_GOODS']=='GM-ICP88' or $_SESSION['AND_GOODS']=='GM-ICP88O' or $_SESSION['AND_GOODS']=='GM-ICP88M' or $_SESSION['AND_GOODS']=='GM-ICP89' or $_SESSION['AND_GOODS']=='GM-ICP89M' or $_SESSION['AND_GOODS']=='GM-ICP89O'){  // check GM_ICP GM-ICP89
		
		$query="select * from dbo.AnalyzeDesign where AND_LOT_NO='".$_SESSION['new_lot_no']."'";
			$result = mssql_query($query);
			$numRows = mssql_num_rows($result);
			if($numRows<1){
			$query="INSERT INTO dbo.AnalyzeDesign
									(AND_Item_Group,rut,AND_VALUE, AND_ANA_ID, AND_GET_DATETIME, AND_RESULT_DATETIME, AND_RESULT, AND_GET_QTY,AND_MARK,AND_APPLY_DATE, AND_NEED_NO, AND_LOT_NO, AND_GOODS, AND_BEFORE, 
									AND_SMP_DATETIME, AND_ITEM, AND_NOTE, AND_NOTE2, AND_TOTAL, AND_OUT_QTY, AND_OUT_DATETIME, AND_REPORT_DATETIME, CTD_CUST_NO, AND_PERSON , AND_COA_FST)
		  VALUES          ('".$group."',".$rut.",' ',' ',' ',' ',' ','0','".$urgent."','".dod($_POST['datepicker4'])."',".$_POST['needno'].",'".$_SESSION['new_lot_no']."','".$_POST['pdd_chemical1']."','".$before."','".$outtime."','".
		 $itm."','".$_POST['remark']."','".$_POST['remark2']."','".$_POST['sample_number']."','0','".$smptime."','".$ordertime."','".$_POST['pdd_chemical3']."','".$_SESSION['uid']."','".$and_coa_fst."')";
		 $result = mssql_query($query);

		
		 $query="INSERT INTO dbo.AnalyzeDesign
									(AND_Item_Group,rut, AND_VALUE, AND_ANA_ID, AND_GET_DATETIME, AND_RESULT_DATETIME, AND_RESULT, AND_GET_QTY,AND_MARK,AND_APPLY_DATE, AND_NEED_NO, AND_LOT_NO, AND_GOODS, AND_BEFORE, 
									AND_SMP_DATETIME, AND_ITEM, AND_NOTE, AND_NOTE2, AND_TOTAL, AND_OUT_QTY, AND_OUT_DATETIME, AND_REPORT_DATETIME, CTD_CUST_NO, AND_PERSON , AND_COA_FST)
		  VALUES          ('".$group."',".$rut.",' ',' ',' ',' ',' ','0','".$urgent."','".dod($_POST['datepicker4'])."',".$_POST['needno'].",'".$_SESSION['new_lot_no']."CPS','".$_POST['pdd_chemical1']."CPS','".$before."','".$outtime."','".
		 $itm."','".$_POST['remark']."','".$_POST['remark2']."','".$_POST['sample_number']."','0','".$smptime."','".$ordertime."','".$_POST['pdd_chemical3']."','".$_SESSION['uid']."','".$and_coa_fst."')";

		 $result = mssql_query($query);
		}
		else{
			echo "ELSE<BR>";
			if(!isset($_POST['lot_no'])){$_POST['lot_no']=$_SESSION['lot_no'];}
			$query="UPDATE        dbo.AnalyzeDesign
		SET            CTD_CUST_NO='".$_POST['pdd_chemical3']."', AND_Item_Group='".$group."' ,rut=".$rut.", AND_COA_FST= '".$and_coa_fst."', AND_TOTAL= ".$_POST['sample_number'].", AND_GET_QTY = '".$_POST['sample_number']."', AND_BEFORE = '".$before."', AND_SMP_DATETIME = '".$smptime."', AND_NEED_NO='".$_POST['needno']."',
								  AND_ITEM = '".$itm."', AND_NOTE = '".$_POST['remark']."',AND_NOTE2 = '".$_POST['remark2']."', AND_OUT_DATETIME = '".$outtime."', 
								  AND_REPORT_DATETIME = '".$ordertime."', AND_MARK = ".$urgent.",   AND_LAST='".$_SESSION['uid']."' 
		WHERE         (AND_LOT_NO = '".$_POST['lot_no']."')";
		$result = mssql_query($query);
	//	   echo "<BR>".$query."<BR>";
		$query="UPDATE        dbo.AnalyzeDesign
		SET                reason='".$_POST['reason']."',keep_sample=".$keep_sample.", 	  CTD_CUST_NO='".$_POST['pdd_chemical3']."', AND_Item_Group='".$group."' ,rut=".$rut.",AND_COA_FST= '".$and_coa_fst."', AND_TOTAL= ".$_POST['sample_number'].", AND_GET_QTY = '".$_POST['sample_number']."', AND_BEFORE = '".$before."', AND_SMP_DATETIME = '".$smptime."', AND_NEED_NO='".$_POST['needno']."',
								  AND_ITEM = '".$itm."', AND_NOTE = '".$_POST['remark']."',AND_NOTE2 = '".$_POST['remark2']."', AND_OUT_DATETIME = '".$outtime."', 
								  AND_REPORT_DATETIME = '".$ordertime."', AND_MARK = ".$urgent.",  AND_LAST='".$_SESSION['uid']."' 
		WHERE         (AND_LOT_NO = '".$_POST['lot_no']."CPS')";
		$result = mssql_query($query);
	//	   echo "<BR>".$query."<BR>";
		}	
	}
	else
	{
	$query="select * from dbo.AnalyzeDesign where AND_LOT_NO='".$_SESSION['new_lot_no']."'";
//	echo "<BR>".$query."<BR>";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	if($numRows<1){
	$query="INSERT INTO dbo.AnalyzeDesign
                            (reason,keep_sample,AND_Item_Group, sample_metal_f, sample_particle_f, rut, AND_VALUE, AND_ANA_ID, AND_GET_DATETIME, AND_RESULT_DATETIME, AND_RESULT, AND_GET_QTY,AND_MARK,AND_APPLY_DATE, AND_NEED_NO, AND_LOT_NO, AND_GOODS, AND_BEFORE, AND_SMP_DATETIME, AND_ITEM, AND_NOTE, AND_NOTE2, 
                            AND_TOTAL, AND_OUT_QTY, AND_OUT_DATETIME, AND_REPORT_DATETIME, CTD_CUST_NO, AND_PERSON , AND_COA_FST)
  VALUES          ('".$_POST['reason']."',".$keep_sample.",'".$group."',".$_POST['sample_metal_f'].",".$_POST['sample_particle_f'].",".$rut.",' ',' ',' ',' ',' ','0','".$urgent."','".dod($_POST['datepicker4'])."',".$_POST['needno'].",'".$_SESSION['new_lot_no']."','".$_POST['pdd_chemical1']."','".$before."','".$outtime."','".
 $itm."','".$_POST['remark']."','".$_POST['remark2']."','".$_POST['sample_number']."','0','".$smptime."','".$ordertime."','".$_POST['cust_no']."','".$_SESSION['uid']."','".$and_coa_fst."')";
}
else{
	if(!isset($_POST['lot_no'])){$_POST['lot_no']=$_SESSION['lot_no'];}
	$query="UPDATE        dbo.AnalyzeDesign
SET      reason='".$_POST['reason']."',keep_sample=".$keep_sample.", CTD_CUST_NO='".$_POST['cust_no']."', AND_Item_Group='".$group."' ,sample_metal_f =".$_POST['sample_metal_f'].", sample_particle_f=".$_POST['sample_particle_f'].", rut=".$rut.",AND_COA_FST= '".$and_coa_fst."', AND_TOTAL= ".$_POST['sample_number'].", 
AND_GET_QTY = '".$_POST['sample_number']."', AND_BEFORE = '".$before."', AND_SMP_DATETIME = '".substr($smptime,0,14)."', AND_NEED_NO='".$_POST['needno']."', AND_GOODS='".$_SESSION['AND_GOODS']."',  
                          AND_ITEM = '".$itm."', AND_NOTE = '".$_POST['remark']."',AND_NOTE2 = '".$_POST['remark2']."', AND_OUT_DATETIME = '".$outtime."', 
                          AND_REPORT_DATETIME = '".$ordertime."', AND_MARK = ".$urgent.", AND_LAST='".$_SESSION['uid']."', AND_APPLY_DATE='".dod($_POST['datepicker4'])."'
WHERE         (AND_LOT_NO = '".$_SESSION['new_lot_no']."')";}

//   echo "<BR>".$query."<BR>";
//   break;
$result = mssql_query($query);
//////把尾碼是CRP的物料自動產生GM-CRBP的依賴還有LOT尾碼是BLK的自動加入SLOP與RSQ的依賴

if(substr(trim($_SESSION['AND_GOODS']),-3,3)=='CRP' or substr(trim($_SESSION['AND_GOODS']),-3,3)=='BLK')
{
	$_SESSION['auto']=1;
	if(substr(trim($_SESSION['AND_GOODS']),-3,3)=='CRP')
	{	
		$count=1;
		$lotnum=substr($_SESSION['new_lot_no'],-3,3);
		$andgood=substr($_SESSION['AND_GOODS'],-3,3);
	}
	elseif(substr(trim($_SESSION['AND_GOODS']),-3,3)=='BLK')
	{
		$count=2;
		$lotnum=substr($_SESSION['new_lot_no'],-3,3);
		$andgood=substr($_SESSION['AND_GOODS'],-3,3);
	}
	for($i=0;$i<$count;$i++)
	{
			if($lotnum=='BLK' and $andgood=='BLK')
			{
				if($i==0){
				$lotnum1=substr($_SESSION['new_lot_no'],0,-3).'RSQ';
				$andgood1=substr($_SESSION['AND_GOODS'],0,-3).'RSQ';
				}
				else{
				$lotnum1=substr($_SESSION['new_lot_no'],0,-3).'SLOP';
				$andgood1=substr($_SESSION['AND_GOODS'],0,-3).'SLOP';
				}
			}
			elseif($lotnum=='GMP' and $andgood=='CRP')
			{
				$lotnum1='TAB'.substr($_SESSION['new_lot_no'],3);
				$andgood1=substr($_SESSION['AND_GOODS'],0,2).'-CRBP';
			}
	$query="select * from dbo.AnalyzeDesign where AND_LOT_NO='".$lotnum1."'";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
		if($numRows<1)
		{
		$query="INSERT INTO dbo.AnalyzeDesign
								(reason,keep_sample, AND_Item_Group, rut, AND_VALUE, AND_ANA_ID, AND_GET_DATETIME, AND_RESULT_DATETIME, AND_RESULT, AND_GET_QTY,AND_MARK,AND_APPLY_DATE, AND_NEED_NO, AND_LOT_NO, AND_GOODS, AND_BEFORE, 
								AND_SMP_DATETIME, AND_ITEM, AND_NOTE, AND_NOTE2, AND_TOTAL, AND_OUT_QTY, AND_OUT_DATETIME, AND_REPORT_DATETIME, CTD_CUST_NO, AND_PERSON , AND_COA_FST)
	  VALUES          ('".$_POST['reason']."',".$keep_sample.",'".$group."',".$rut.",' ',' ',' ',' ',' ','0','".$urgent."','".dod($_POST['datepicker4'])."',".$_POST['needno'].",'".$lotnum1."','".$andgood1."','".$before."','".$outtime."','".
	 $itm."','".$_POST['remark']."','".$_POST['remark2']."','".substr($smptime,0,14)."','0','".$smptime."','".$ordertime."','".$_POST['pdd_chemical3']."','".$_SESSION['uid']."','".$and_coa_fst."')";
		}
		else{
		if(!isset($_POST['lot_no'])){$_POST['lot_no']=$_SESSION['lot_no'];}
		$query="UPDATE        dbo.AnalyzeDesign
	SET           reason='".$_POST['reason']."',keep_sample=".$keep_sample.",  AND_Item_Group='".$group."' ,rut=".$rut.", AND_COA_FST= '".$and_coa_fst."', AND_TOTAL= ".$_POST['sample_number'].", AND_GET_QTY = '".$_POST['sample_number']."', AND_BEFORE = '".$before."', AND_SMP_DATETIME = '".substr($smptime,0,14)."', AND_NEED_NO='".$_POST['needno']."', AND_GOODS='".$andgood1."',  
							  AND_ITEM = '".$itm."', AND_NOTE = '".$_POST['remark']."',AND_NOTE2 = '".$_POST['remark2']."', AND_OUT_DATETIME = '".$outtime."', 
							  AND_REPORT_DATETIME = '".$ordertime."', AND_MARK = ".$urgent.", CTD_CUST_NO='".$_POST['pdd_chemical3']."',  AND_LAST='".$_SESSION['uid']."' 
	WHERE         (AND_LOT_NO = '".$lotnum1."')";
	
		}
		
$result = mssql_query($query);
	}
	
}
//echo $query.'<br><br>';
if (!$result) {
    print("SQL statement failed with error:\n");
    print("   ".mssql_get_last_message()."\n");
  } else {
	  echo " Anylize Changed!!<BR>";}		
	}
if(($_SESSION['needno']<270) and ($_SESSION['needno']>150)){
	$query="SELECT          TOP (1) lot_no_rules.sample_no, Sample_All.SMA_TIMES
FROM              lot_no_rules INNER JOIN
                            Sample_All ON lot_no_rules.sample_no = Sample_All.SMA_ID
WHERE          lot_no_rules.pid = '".$_SESSION['AND_GOODS']."' ORDER BY   Sample_All.SMA_TIMES DESC";

	$result = mssql_query($query);
	$a3 = mssql_fetch_row($result);
	$a1=$a3[0];
	$a2=$a3[1]+1;
	$query="INSERT INTO dbo.Sample_All
                            (SMA_ID, SMA_TIMES, SMA_SERVICE, SMA_LOT,  SMA_OUT, SMA_SMP, SMA_ANA, SMA_BACK, 
                            SMA_SAVE, SMA_SAVE_TIME, ISREWORK, SMA_FINISH, SMA_JUNK, SMA_JUNK_D, SMA_DRUMNO, 
                            SMA_SERIAL_NO, DHN_DRUM_NO, SMA_PREPSAMPLE_MAN, SMA_PREPSAMPLE_DATE, 
                            SMA_PREPSAMPLE_BACK_DATE, SMA_PREPSAMPLE_BACK_MAN, SMA_RETURN, SMA_RETURN_MAN)
							VALUES          ('".$a1."',".$a2.",1,'".$_SESSION['new_lot_no'].$q1."',NULL,'".$_SESSION['uid']."',NULL,NULL,'".date("Ymd")."','".date("His")."',NULL,
							NULL,NULL,NULL,'1~8',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL)";

$result = mssql_query($query);
$query="UPDATE          dbo.Sample
			SET                   SMP_TIMES ='".$a2."', SMP_LOT ='".$_SESSION['new_lot_no'].$q1."', SMP_DRUMNO = '1~8', SMP_SMP ='".$_SESSION['uid']."', SMP_SAVE ='".date("Ymd")."', SMP_SAVE_TIME ='".date("His")."' 
			WHERE          (SMP_ID = '".$a1."')";
	$result = mssql_query($query);
//////////自動(非手動keyin)產生的解析數據的依賴要必須自動加入瓶號(Sample_all)表單，例如:RSQ、SLOP
	if($_SESSION['auto']==1 and trim($_SESSION['AND_GOODS'])!='GM-CRP')
	{
		
		if($lotnum=='BK' and $andgood=='BLK')
		{
			$count=2;
			$lotnum=substr($_SESSION['new_lot_no'],-2,2);
			$andgood=substr($_SESSION['AND_GOODS'],-3,3);
		}
		for($i=0;$i<$count;$i++)
		{
			if($lotnum=='BK' and $andgood=='BLK')
			{
				if($i==0){
				$lotnum1=substr($_SESSION['new_lot_no'],0,-2).'RSQ';
				$andgood1=substr($_SESSION['AND_GOODS'],0,-3).'RSQ';
				}
				else{
				$lotnum1=substr($_SESSION['new_lot_no'],0,-2).'SLOP';
				$andgood1=substr($_SESSION['AND_GOODS'],0,-3).'SLOP';
				}
			}
			$query="SELECT          TOP (1) lot_no_rules.sample_no, Sample_All.SMA_TIMES
FROM              lot_no_rules INNER JOIN
                            Sample_All ON lot_no_rules.sample_no = Sample_All.SMA_ID
WHERE          lot_no_rules.pid = '".$andgood1."' ORDER BY   Sample_All.SMA_TIMES DESC";
			$result = mssql_query($query);
			$a3 = mssql_fetch_row($result);
			$a1=$a3[0];
			$a2=$a3[1]+1;
			$query="INSERT INTO dbo.Sample_All
									(SMA_ID, SMA_TIMES, SMA_SERVICE, SMA_LOT,  SMA_OUT, SMA_SMP, SMA_ANA, SMA_BACK, 
									SMA_SAVE, SMA_SAVE_TIME, ISREWORK, SMA_FINISH, SMA_JUNK, SMA_JUNK_D, SMA_DRUMNO, 
									SMA_SERIAL_NO, DHN_DRUM_NO, SMA_PREPSAMPLE_MAN, SMA_PREPSAMPLE_DATE, 
									SMA_PREPSAMPLE_BACK_DATE, SMA_PREPSAMPLE_BACK_MAN, SMA_RETURN, SMA_RETURN_MAN)
									VALUES          ('".$a1."',".$a2.",1,'".$lotnum1.$q1."',NULL,'".$_SESSION['uid']."',NULL,NULL,'".date("Ymd")."','".date("His")."',NULL,
									NULL,NULL,NULL,'1~8',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL)";
			$result = mssql_query($query);
			$query="UPDATE          dbo.Sample
					SET                   SMP_TIMES ='".$a2."', SMP_LOT ='".$lotnum1.$q1."', SMP_DRUMNO = '1~8', SMP_SMP ='".$_SESSION['uid']."', SMP_SAVE ='".date("Ymd")."', SMP_SAVE_TIME ='".date("His")."' 
					WHERE          (SMP_ID = '".$a1."')";
			$result = mssql_query($query);
		}
	}
}	
	$queryd="UPDATE AnalyzeGroup_Lot SET active = 0 WHERE (Lot_No = N'".trim($_SESSION['lot_no'])."')";
	$resultd=mssql_query($queryd);
$aa=explode(',',$_SESSION['items1']);
	$queryc="SELECT DISTINCT ANI_GROUPNAME
FROM              AnalyzeItem
WHERE          (ANI_INDEX <>'') and ";
for($i=0;$i<count($aa);$i++){
if ($i<(count($aa)-1)){
	$queryc.="(ANI_INDEX =".$aa[$i].") OR ";}
else {$queryc.="(ANI_INDEX =".$aa[$i].")";}	
}
$resultc=mssql_query($queryc);
while($rowc=mssql_fetch_array($resultc)){
//	echo $rowc['ANI_GROUPNAME']."<BR>";

	$queryd="INSERT INTO AnalyzeGroup_Lot (Lot_No, Ana_Group_Name, Creator, active) VALUES (N'".trim($_SESSION['lot_no'])."', N'".$rowc['ANI_GROUPNAME']."', N'".$_SESSION['uid']."', 1)";
	$resultd=mssql_query($queryd);
}

unset($_SESSION['auto']);
mssql_close($dbhandle);
//jumpto($_SESSION['lasturl']);
}/// end new

if(isset($_POST["add_testitems"]))
{   session_start();
//////////////////////////////////////////////////////////
	$_SESSION['smptime']=$smptime=dod($_POST['datepicker1']).$_POST['order_time1'].$_POST['order_time2'];
	$_SESSION['outtime']=$outtime=dod($_POST['datepicker2']).$_POST['deliver_time1'].$_POST['deliver_time2']."00";
	$_SESSION['ordertime']=$ordertime=dod($_POST['datepicker3']).$_POST['order_time3'].$_POST['order_time4']."00";
	$url="index.php?url=add_testitems&AND_GOODS=".$_SESSION['AND_GOODS']."&cust_no=".$_SESSION['cust_no'];
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
	echo "cancel:".$_SESSION['cancel'].'</br>';
	if($_SESSION['cancel']<>1){
	echo $query="UPDATE        dbo.AnalyzeDesign
			SET                  AND_CANCEL = 1, CTD_CUST_NO = ''
			WHERE         (AND_LOT_NO = '".$_POST['lot_no']."')";
	$result=mssql_query($query);
	changelog('AnalyzeDesign','insert','AND_CANCEL','1',$_SESSION['uid'],date("Ymdhis"));
	refresh();
	}
	elseif($_SESSION['cancel']==1){
	echo	$query="UPDATE        dbo.AnalyzeDesign
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
	
	if($_SESSION['needno']>=500){
		$lnd=trim($_POST['lot_no'])."_C";

	}
	else{
		$lnd=trim($_SESSION['lot_no']);
	}
xload($lnd);
refresh();
}
function xload($lotno){
	include("../connections/conn.php");
	$query="SELECT          AnalyzeDesign.AND_COA_FST, AnalyzeDesign.AND_APPLY_DATE, AnalyzeDesign.AND_NEED_NO, 
                            AnalyzeDesign.AND_LOT_NO, AnalyzeDesign.AND_GOODS, AnalyzeDesign.CTD_CUST_NO, 
                            AnalyzeDesign.AND_BEFORE, AnalyzeDesign.AND_SMP_DATETIME, AnalyzeDesign.AND_ITEM, 
                            AnalyzeDesign.AND_ANA_ID, AnalyzeDesign.AND_VALUE, AnalyzeDesign.AND_MEMO, AnalyzeDesign.AND_TOTAL, 
                            AnalyzeDesign.AND_OUT_QTY, AnalyzeDesign.AND_OUT_DATETIME, AnalyzeDesign.AND_REPORT_DATETIME, 
                            AnalyzeDesign.AND_MARK, AnalyzeDesign.AND_GET_QTY, AnalyzeDesign.AND_GET_DATETIME, 
                            AnalyzeDesign.AND_RESULT_DATETIME, AnalyzeDesign.AND_RESULT, AnalyzeDesign.AND_NOTE, 
                            AnalyzeDesign.AND_NOTE2, AnalyzeDesign.AND_PERSON, AnalyzeDesign.ALM_IDENTITY51, 
                            AnalyzeDesign.ALM_IDENTITY52, AnalyzeDesign.ALM_IDENTITY53, AnalyzeDesign.ALM_IDENTITY54, 
                            AnalyzeDesign.ALM_IDENTITY55, AnalyzeDesign.ALM_IDENTITY56, AnalyzeDesign.ALM_IDENTITY13, 
                            AnalyzeDesign.AND_CANCEL, AnalyzeDesign.sample_metal_f, AnalyzeDesign.sample_particle_f, 
                            FILLPLAN_OUT_DECIDE.CTD_CUST_NO AS cust_no,keep_sample,reason 
FROM              AnalyzeDesign LEFT OUTER JOIN
                            FILLPLAN_OUT_DECIDE ON AnalyzeDesign.AND_LOT_NO = FILLPLAN_OUT_DECIDE.FDM_LOT_NO
WHERE          (AND_LOT_NO = '".$lotno."')";
// echo $query;
// break;
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
if($numRows>0){
	while($row = mssql_fetch_array($result)){	
		if(trim($row['CTD_CUST_NO'])==''){
			$_SESSION['cust_no']=$row['cust_no'];
		}
		else{
			$_SESSION['cust_no']=$row['CTD_CUST_NO'];
		}
	
	$_SESSION['cancel']=$row['AND_CANCEL'];
	$_SESSION['datepicker4']=odo($row['AND_APPLY_DATE']);
	if($_SESSION['testitems']==''){
		$_SESSION['testitems']=$row['AND_ITEM'];
	}
	
	$_SESSION['smptime']=$row['AND_SMP_DATETIME'];	
	$_SESSION['outtime']=$row['AND_OUT_DATETIME'];
	$_SESSION['ordertime']=$row['AND_REPORT_DATETIME'];	
	$_SESSION['lot_no']=$lotno;
	$_SESSION['AND_GOODS']=$row['AND_GOODS'];
	$_SESSION['items']=$_SESSION['items1']=$row['AND_ITEM'];
	$_SESSION['and_before']=$row['AND_BEFORE'];
	$_SESSION['and_coa_fst']=$row['AND_COA_FST'];
	$_SESSION['and_mark']=$row['AND_MARK'];
	$_SESSION['and_total']=$row['AND_TOTAL'];
	$_SESSION['needno']=$row['AND_NEED_NO'];
	$_SESSION['note']=$row['AND_NOTE'];
	$_SESSION['note2']=$row['AND_NOTE2'];
	$_SESSION['reason']=$row['reason'];
	$_SESSION['keep_sample']=$row['keep_sample'];
	$_SESSION['sample_metal_f']=$row['sample_metal_f'];
	if($_SESSION['sample_metal_f']==''){$_SESSION['sample_metal_f']=0;}
	$_SESSION['sample_particle_f']=$row['sample_particle_f'];
	if($_SESSION['sample_particle_f']==''){$_SESSION['sample_particle_f']=0;}
	}
}
else{
	my_msg("查無資料");
}

}
function cust_prod($_cust_id,$pid){
	$query="SELECT CTP_OUT_SMP_PE, CTP_OUT_SMP_TFA, CTP_BEF_SMP_PE, CTP_BEF_SMP_TFA FROM CUSTOMER_PRODUCTS WHERE (PDD_PROD_NO = '".$pid."') AND (CTD_CUST_NO = '".$_cust_id."')";
	$result=mssql_query($query);
	$row=mssql_fetch_array($result);
	$smp_pe=$row['CTP_OUT_SMP_PE'];
	$smp_tfa=$row['CTP_OUT_SMP_TFA'];
	$smp_particle=$row['CTP_BEF_SMP_PE'];
	$smp_metal=$row['CTP_BEF_SMP_TFA'];
	return $aa=array($smp_pe,$smp_tfa,$smp_particle,$smp_metal);
}

function is_canceled($lotno){
	$query="SELECT AND_CANCEL FROM AnalyzeDesign WHERE (AND_LOT_NO = '".$lotno."')";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	return $row[0];
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
//	refresh();
jumpto($_SESSION['lasturl']);
}
?>


