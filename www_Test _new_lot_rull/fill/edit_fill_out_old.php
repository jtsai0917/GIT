<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
unset($_SESSION['items1']);
lasturl1();
remurl("edit_fill_out");
datepick();
	$cid=$_GET['cid'];
	$getrull=new autoani;
	$getrull->lid=$_SESSION['new_lot'];
	$getrull->cid=$cid;
	$getrull->pid=$_GET['pid'];
	$getrull->outdate=dod($_SESSION['datepicker1']);
	$getrull->get_rull_1();
	$getrull->status_();
	$getrull->items_();
	$_SESSION['ANALYZE_ITEMS']=$getrull->item;
	$ss= new get_from_lot_no;
if($_SESSION['new_lot']<>''){$ss->lid=$_GET['AND_LOT_NO'];}
$ss->ani();
$this_items=$ss->items;

	
// echo "This One:".$this_items;
// echo "</br>";
$_SESSION['Full']=$getrull->Rull_Total;

$_SESSION['Reg']=$getrull->Rull_Reg;

$query="SELECT    TOP 1   FOD_UNI, FOD_YEAR_MONTH, FOD_DAY, FOD_O_YEAR_MONTH, 
                          FOD_O_DAY, CTD_CUST_NO, CTD_CUST_NO_GROUP, PDD_PROD_NO, 
                          FDM_SERIAL_NO, FDM_LY_NO, FDM_CREATE_DATE, FDM_CREATOR, 
                          FDM_SPECIFIC, FDM_QTY_DRUM, FDM_QTY, FDM_QTY_UNIT, 
                          FDM_EXPECT_DATE, FDM_LOT_NO, FDM_ITEM, FDM_RESULT_DATE, 
                          FDM_COA_BEFORE, FDM_OUT_DATE, FDM_BACK_DATE, FDM_SAM_BEFORE, 
                          FDM_SAM_BEF_CNT, FDM_SAM_CNT, FDM_ATTACH_CNT, FDM_TRANSFROM, 
                          FDM_PRINT_OUT, FDM_FILLED_B_DATE, FDM_FILLED_E_DATE, 
                          FDM_FILLED_MAN, FDM_FILLED_LY_QTY, FDM_FILLED_DM_QTY, 
                          FDM_CHK_SAM_PURGE, FDM_CHK_CHG_PURGE, FDM_PURGE, 
                          FDM_MOD_DATE, FDM_P_TOTO, FOD_BAR_PRN_DATE, remark1,
                          FDM_REAL_OUT_TIME, FDM_REAL_RETURN_TIME
FROM             dbo.FILLPLAN_OUT_DECIDE
WHERE         (FOD_UNI = '".$_GET['uni']."')
ORDER BY  FILLPLAN_OUT_DECIDE.FDM_OUT_DATE";
//echo "<BR>".$query."<BR>";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result))
{   
	?>
<body>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <table border="0" width="600"><tr>
  <td align="center" class="ui-icon-alert">&nbsp;</td></tr></table>
  <table border="0" width="600">
    <tr>
      <td width="200">登錄日期：<?php echo $_GET['cdt'];?></td>
      <td width="200">輸入人員：<?php echo getusername($_GET['ct']);?></td>
      <td width="200">序號：<?php echo $_GET['sn'];?></td>
    </tr>
  </table>
	<?php 
	if($_GET['qtydm']<>''){
		echo '<table border="0"><tr><td width="600">客戶：';
        echo '<input type="button" name="X" id="X" value="X" onClick="window.open('."'../erase_customer.php ', '_self') ".'" />';
        echo '<input name="cid" type="text" id="cid" size="10" value="'.$_SESSION['cust_no'].'" readonly />';
        echo '<input type="button" name="pdd_no" id="pdd_no" value="查詢" onClick="window.open('."'../main.php?url=cust_no&sup=N&pid=".$_GET['pid']." ', '_self') ".'" />';
        echo '<input name="pdd_chemical" type="text" id="pdd_chemical2" size="20" value="'.$_SESSION['cust_name'].'" />';
		echo ' 桶數：<input name="cnt" type="text" id="cnt" size="7" value="'.$_SESSION['cnt'].'" />';
		echo '<input type="submit" name="add_cust_drum" id="" value="加入" ></td></tr>';
		echo '<tr><td>';
		echo '<textarea name="textarea" id="textarea" cols="72" rows="2">';
		echo _cust_drum_cnt();
		echo '</textarea>';
		echo '<input type="submit" name="del_cust_drum" id="" value="清除" ></td></tr>';
		echo '</td></tr>';
		echo '</table>';
	}
	?>
  <table border="0" width="600">
      <tr><td width="100">客戶；</td><td width="500">
	  <?php 
	  if (trim($row['CTD_CUST_NO'])<>''){echo $cid=$row['CTD_CUST_NO'];$cid1=$cid;echo "&nbsp;&nbsp;&nbsp;".trim(get_cust_name($row['CTD_CUST_NO']));$_cust_id=trim($row['CTD_CUST_NO']);}
	  else{
		  	$query1="select CTD_CUST_NO from  FILLPLAN_DRUM_CUSTOMER where FOD_UNI='".$_GET['uni']."'";
		  	$result1 = mssql_query($query1);
			$numRows1 = mssql_num_rows($result1);
			if($numRows1==1){$row1 = mssql_fetch_row($result1);echo $cid=$row1[0];$cid1=$cid;echo "&nbsp;&nbsp;&nbsp;".get_cust_name($row1[0]);$_cust_id=$row1[0];}
			if($numRows1>1){$_cust_id="C00000";echo $_cust_id."&nbsp;&nbsp;&nbsp;".get_cust_name($_cust_id);echo "(使用社內規格)";$cid1=$_cust_id;}
		  }
		 $_SESSION['cid1']=$cid1;
	   $_SESSION['fill_qty']=big_drum_volume($_GET['lyno']);
	  if(!$_SESSION['fill_qty']){$numl=$row['FDM_QTY']/literkg1($_GET['pid']);}else{$numl=$_SESSION['fill_qty'];}
	  if($row['FDM_QTY_UNIT']=='L' and $row['FDM_SPECIFIC']=='DM'){
		$qtykg=($row['FDM_QTY'])*literkg1($_GET['pid']);
		$numl=	$row['FDM_QTY'];  
	  }
	  else{
		$qtykg=$row['FDM_QTY'];
	  }
	    list($tfa,$pe,$particle,$metal)=cust_prod($_cust_id,$_GET['pid']);
	if($_SESSION['sample_particle_f']==''){
	$_SESSION['sample_particle_f']=$particle;}
	if($_SESSION['sample_metal_f']==''){
	$_SESSION['sample_metal_f']=$metal;}
	  ?>
      </td></tr>
      <tr><td width="100">品名；</td><td width="500"><?php echo get_pdd_name($_GET['pid']);?></td></tr>
      <tr><td width="100">LY-NO；</td><td width="500"><?php echo $_GET['lyno'];?></td></tr>
      <tr><td width="100">桶數；</td><td width="500"><?php echo $_GET['qtydm'];?></td></tr>
      <tr><td width="100">數量；</td><td width="500"><?php echo $qtykg;?> KG</td></tr>
      <tr><td width="100">充填量；</td><td width="500"><label for="numl"></label>
    <input name="numl" type="text" id="numl" size="8" value="
	<?php 	
	$nn=new product;
	$nn->pid=$_GET['pid'];
	$nn->getone();
	$drum_l=$nn->pdd_drum_l;
	if(($_GET['style']=='DM' or $_GET['style']=='BTL') and $_GET['qtyun']=='L'){echo $_GET['qtydm']*$drum_l;}
	elseif(!$_SESSION['fill_qty']){$numl=$row['FDM_QTY']/literkg1($_GET['pid']);echo trim($numl);}
	else{echo $_SESSION['fill_qty'];}
	?>
    " <?php 
	$query1="select PDD_PROD_SHORT_NAME from PRODUCT_DATA  where  PDD_PROD_NO='".$_GET['pid']."'";
	$result1=mssql_query($query1);
	$row1=mssql_fetch_array($result1);
	$query2="SELECT * FROM BIG_DRUM_VOLUME WHERE (PDD_PROD_SHORT_NAME = '".$row1[0]."')";
	$result2=mssql_query($query2);
	$Numrows2=mssql_num_rows($result2);
	if($Numrows2!=0){$read='readonly';}else{$read='';} echo $read; ?>/> 
    L</td></tr>
      <tr><td width="100">預計充填日期；</td><td width="300"><span class="d1">
        <input type="text" name="datepicker1" id="datepicker1" size="10" value="<?php 
		if($_SESSION['datepicker1']==''){
			$t=strtotime("+1 day");
			$_SESSION['datepicker1']=date("m/d/Y",$t);
		}
		echo $_SESSION['datepicker1'];
		?>" onChange="set_date_session(this.name,this.value)">
      </span></td></tr>
      <tr><td width="50">預計充填時間；</td><td width="50"><input name="time1" type="text" id="time1" value="08" size="4"/>
      :
          <input name="time12" type="text" id="time5" value="30" size="4"/>
EX: 08:30</td></tr>
<?php
if(trim($_GET['style'])=='DM' or trim($_GET['style'])=='BTL'){
	echo '<tr>
	<td>預定洗桶日期</td>
	<td><input type="text" name="datepicker5" id="datepicker5" size="10" value="';
		if($_SESSION['datepicker5']==''){
			$t=strtotime("+1 day");
			$_SESSION['datepicker5']=date("m/d/Y",$t);
		}
		echo $_SESSION['datepicker5'];
		
		echo '" onChange="set_date_session(this.name,this.value)"></td></tr>';
}
 
?>

	</table>
	<table border="0" width="600">
      <tr><td width="100">LOT_NO：</td><td width="100"><input name="lot_no" type="text" id="lot_no" value="<?php 
	  		if($_GET['AND_LOT_NO']=='' and $_SESSION['change_status']<>1)
			{
				$_SESSION['new_lot']=$lot=genlot($_GET['pid'],dod($_SESSION['datepicker1']),$_GET['lyno']);
			}
			elseif($_GET['AND_LOT_NO']<>'' and $_SESSION['change_status']<>1)
			{
				$lot=$_GET['AND_LOT_NO'];
				 $_SESSION['new_lot']=$_GET['AND_LOT_NO'];
			} 	
			else
			{
				$_SESSION['new_lot']=$lot=genlot($_GET['pid'],dod($_SESSION['datepicker1']),$_GET['lyno']);	
			}
			echo trim($lot);
	  ?>" size="12" 
	   readonly="readonly" /></td><td width="400" align="right"> 來源TANK
      <?php
	  	$tank=new tank_data;
		$tank->pid=$_GET['pid'];
		$tank->lid=$lot;
		$tank->show();
	  ?>
      </td></tr></table>
      <table border="0" width="600">
      <tr><td width="150">分析預定：</td><td width="450">
        <select name="select_test_items" id="select_test_items" onChange="set_date_session(this.name,this.value)">
          <option value="0"></option>
          <option value="1" <?php if($_SESSION['select_test_items']==1){echo "selected";}?>>常規</option>
          <option value="2" <?php if($_SESSION['select_test_items']==2){echo "selected";}?>>全項</option>
        </select>
        <input type="submit" name="anylize" id="anylize" value="  分析  " ><?php 
		$getrull->get_rull_1();
		$getrull->status_();
		$getrull->items_();
		$_SESSION['ANALYZE_ITEMS']=$getrull->item;
		$_aniitems=ani_items($_SESSION['new_lot'],$cid,$_GET['pid'],dod($_SESSION['datepicker1']));
		if($_SESSION['ANALYZE_ITEMS']==$_SESSION['Reg']){$ani_now='常規';}
				elseif($_SESSION['ANALYZE_ITEMS']==$_SESSION['Full']){$ani_now='全項';}
				else{$ani_now='AMP';}
		if(checkanalizestatus($lot)==1){	
				if($this_items==$_SESSION['Reg']){$ani_now='常規';}
				elseif($this_items==$_SESSION['Full']){$ani_now='全項';}
				else{$ani_now='AMP';}
				echo '已有分析依賴('.$ani_now.')';
				$str="add_anylize_&AND_LOT_NO=".$_SESSION['new_lot'];
			}
			else
			{
				if($_aniitems==$_SESSION['Full']){$ani_now='全項';}
				elseif($_aniitems==$_SESSION['Reg']){$ani_now='常規';}
				else{$ani_now='AMP';}
				echo "沒有分析依賴(本次將建立".$ani_now.")";$str="edit_anylize_auto";
				if($ani_now=='全項') {$_SESSION['item_ori']='2';}
				if($ani_now=='常規') {$_SESSION['item_ori']='1';}
				if(($_SESSION['select_test_items']=='1') and ($ani_now=='全項')) {$_SESSION['item_style']='1';} //全項->常規
				if(($_SESSION['select_test_items']=='2') and ($ani_now=='常規')) {$_SESSION['item_style']='2';} //常規->全項
			}
			?></td></tr>
      <tr><td width="50">希望報告時間；</td><td width="250"><span class="d1">
        <input type="text" name="datepicker2" id="datepicker2" size="10" value="<?php 
			$t=ddo($_SESSION['datepicker1']);		
			echo date("m/d/Y",strtotime($t."+1 day"));  //加一天
			?>" />
        <label for="time2"></label>
        <input name="time2" type="text" id="time2" size="6" value="<?php if($row["FDM_RESULT_DATE"]==''){echo '16';}
	  else{echo substr(stm($row["FDM_RESULT_DATE"]),0,2);}?>"/>
        :
        <input name="time22" type="text" id="time22" size="6" value="<?php if($row["FDM_RESULT_DATE"]==''){echo '00';}
	  else{echo substr(stm($row["FDM_RESULT_DATE"]),3,2);}?>"/>
    EX: 08:30</span></td></tr>
      <tr><td width="50">是否先行COA；</td><td width="250"><input type="checkbox" name="coa" <?php if($row["FDM_COA_BEFORE"]=='Y'){echo "Checked";}?> /></td></tr>
      <tr><td width="50">預計出荷時間；</td><td width="250"><span class="d1">
        <input type="text" name="datepicker3" id="datepicker3" size="10" value="<?php echo substr($row["FOD_O_YEAR_MONTH"],4,2)."/".$row["FOD_O_DAY"]."/".substr($row["FOD_O_YEAR_MONTH"],0,4);?>" />
        <input name="time3" type="text" id="time3" size="4"  value="<?php if($row["FDM_OUT_DATE"]==''){echo '13';}
	  else{echo substr(stm($row["FDM_OUT_DATE"]),0,2);}?>"/>
        :
        <input name="time32" type="text" id="time32" size="4"  value="<?php if($row["FDM_OUT_DATE"]==''){echo '30';}
	  else{echo substr(stm($row["FDM_OUT_DATE"]),3,2);}?>"/>
    EX: 08:30</span></td></tr>
      <tr><td width="50">預計回廠時間；</td><td width="250"><span class="d1">
        <input type="text" name="datepicker4" id="datepicker4" size="10" value="<?php echo substr($row["FOD_O_YEAR_MONTH"],4,2)."/".$row["FOD_O_DAY"]."/".substr($row["FOD_O_YEAR_MONTH"],0,4);?>" />
        <input name="time4" type="text" id="time4" size="4" value="<?php if($row["FDM_BACK_DATE"]==''){echo '17';}
	  else{echo substr(stm($row["FDM_BACK_DATE"]),0,2);}?>"/>
        :
        <input name="time42" type="text" id="time42" size="4" value="<?php if($row["FDM_BACK_DATE"]==''){echo '30';}
	  else{echo substr(stm($row["FDM_BACK_DATE"]),3,2);}?>"/>
    EX: 08:30</span></td></tr>
      <tr><td width="50">是否先行樣品；
      </td><td width="250">
    	<input type="checkbox" name="smp" <?php if($row["FDM_SAM_BEFORE"]=='Y'){echo "Checked";}?> />
      </td></tr>
      <tr><td width="50">Metal先行樣品；</td><td width="250">
   	  <input name="sample_metal_f" type="text" id="sample_metal_f" size="5" value="<?php if($_SESSION['sample_metal_f']==''){echo '0';}else{echo $_SESSION['sample_metal_f'];}?>"  onchange="set_date_session(this.name,this.value)"/></td></tr>
      <tr><td width="50">Particle先行樣品；</td><td width="250">
   	  <input name="sample_particle_f" type="text" id="sample_particle_f" size="5" value="<?php if($_SESSION['sample_particle_f']==''){echo '0';}else{echo $_SESSION['sample_particle_f'];}?>"  onchange="set_date_session(this.name,this.value)"/></td></tr>
      <tr><td width="50">隨貨取樣瓶數；</td><td width="250">
      <input name="att_cnt" type="text" id="att_cnt" size="5" value="<?php if($_SESSION['att_cnt']==''){echo $row["FDM_ATTACH_CNT"];}else{echo $_SESSION['att_cnt'];}?>"  onchange="set_date_session(this.name,this.value)"/></td></tr>
      <tr><td width="50">分析取樣瓶數；</td><td width="250">
	  <input name="sam_cnt" type="text" id="sam_cnt" size="5" value="<?php if($_SESSION['sam_cnt']==''){echo $_SESSION['sam_cnt']=$row["FDM_SAM_CNT"];}else{echo $_SESSION['sam_cnt'];}?>"  onchange="set_date_session(this.name,this.value)"/></td></tr>
      <tr>
  <td width="50">總取樣瓶數+2；</td>
  <td width="250"><input name="smp_total" type="text" id="smp_total" size="5" value="<?php 
	echo $_SESSION['smp_total']=$_SESSION['sample_metal_f']+$_SESSION['sample_particle_f']+$_SESSION['att_cnt']+$_SESSION['sam_cnt']+2;
	?>
	" readonly onChange="set_date_session(this.name,this.value)" /></td>
  </tr><tr><td width="50">備註1：</td><td><input name="remark1" type="text" id="remark1" size="20" value="<?php echo $row["remark1"];?>" /></td></tr>
  
  </table>
  <table border="0">
    <tr>
    <td width="300">&nbsp;</td>
    <td width="300"><input type="submit" name="save" id="save" value="  儲存  " />
      <input type="submit" name="leave"  value="  離開  " /></td>
    </tr>
    <tr><td></td>
    <td>儲存後將會自動建立分析依賴，</br>未設定檢驗規則以AMP檢驗為預設值</td></tr>
  </table>
</form>
<?php 
}
?>
</body>
</html>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
$_SESSION['fill_qty']=$_POST['numl'];
$_SESSION['change_status']=1;

if(isset($_POST["leave"])){
	unset($_SESSION['sample_metal_f']);
	unset($_SESSION['sample_particle_f']);
	unset($_SESSION['att_cnt']);
	unset($_SESSION['sam_cnt']);
	unset($_SESSION['smp_total']);
	echo "<script type='text/javascript'>
	
		window.open('index.php?url=fill_out_plan','_self')
		</script>
		";
	
}
	
if(isset($_POST["anylize"])){
		$_POST['lot_no']=trim($_POST['lot_no']);
	$_SESSION['fill_qty']=$_POST['numl'];
	if($_POST['coa']=='on'){$coa='Y';}else{$coa='N';}
	if($_POST['smp']=='on'){$smp='Y';}else{$smp='N';}
	if($_POST['style']='LY'){$cust_group='NULL';$qdm='NULL';}
	else{
	$qdm=$_GET['qtydm'];
	}
	if($_GET['qtyun']=='L'){$qty=$_POST['numl'];}
	if($_GET['qtyun']=='KG'){$qty=$_POST['numl']*literkg1($_GET['pid']);}
	if($_GET['qtydm']==''){$_GET['qtydm']=NULL;}
	$s1=substr($_POST['datepicker1'],6,4).substr($_POST['datepicker1'],0,2).substr($_POST['datepicker1'],3,2).substr($_POST['time1'],0,2).$_POST['time12']."00";
	$s2=substr($_POST['datepicker2'],6,4).substr($_POST['datepicker2'],0,2).substr($_POST['datepicker2'],3,2).substr($_POST['time2'],0,2).$_POST['time22']."00";
	$s3=substr($_POST['datepicker3'],6,4).substr($_POST['datepicker3'],0,2).substr($_POST['datepicker3'],3,2).substr($_POST['time3'],0,2).$_POST['time32']."00";
	$s4=substr($_POST['datepicker4'],6,4).substr($_POST['datepicker4'],0,2).substr($_POST['datepicker4'],3,2).substr($_POST['time4'],0,2).$_POST['time42']."00";
	
	if($_POST['smp']=='on'){$smp='Y';}else{$smp='N';}
	if($_POST['coa']=='on'){$coa='Y';}else{$coa='N';}
	$url="../cal/index.php?url=".$str."&CTD_CUST_NO=".$_cust_id."&AND_TOTAL=".($_POST['smp_total']).
	"&AND_GOODS=".$_GET['pid']."&AND_BEFORE=".$smp."&COA_BEFORE=".$coa."&rt=".$s2."&AND_LOT_NO=".$_SESSION['new_lot']."&outdate=".dod($_POST['datepicker3'])."&sample_metal_f=".($_POST['sample_metal_f'])."&sample_particle_f=".($_POST['sample_particle_f']);
	jumpto($url);
}

if(isset($_POST['leave'])){
	unset($_SESSION['fill_qty']);
	unset($_SESSION['dapic1']);
	unset($_SESSION['datepicker1']);
	jumpto("index.php?url=fill_out_plan");
}

if(isset($_POST["save"]))
{
	$_POST['lot_no']=trim($_POST['lot_no']);
	if($_POST['coa']=='on'){$coa='Y';}else{$coa='N';}
	if($_POST['smp']=='on'){$smp='Y';}else{$smp='N';}
	if($_POST['style']='LY'){$cust_group='NULL';$qdm='NULL';}
	$s1=substr($_POST['datepicker1'],6,4).substr($_POST['datepicker1'],0,2).substr($_POST['datepicker1'],3,2).substr($_POST['time1'],0,2).$_POST['time12']."00";
	$s2=substr($_POST['datepicker2'],6,4).substr($_POST['datepicker2'],0,2).substr($_POST['datepicker2'],3,2).substr($_POST['time2'],0,2).$_POST['time22']."00";
	$s3=substr($_POST['datepicker3'],6,4).substr($_POST['datepicker3'],0,2).substr($_POST['datepicker3'],3,2).substr($_POST['time3'],0,2).$_POST['time32']."00";
	$s4=substr($_POST['datepicker4'],6,4).substr($_POST['datepicker4'],0,2).substr($_POST['datepicker4'],3,2).substr($_POST['time4'],0,2).$_POST['time42']."00";
	if($_POST['numl']=='0'){my_msg("充填量不可為零");}
	else
	{
	// SN
		$query="select  top 1 FDM_SERIAL_NO from FILLPLAN_OUT_DECIDE where FOD_YEAR_MONTH ='".substr($_POST['datepicker1'],6,4).substr($_POST['datepicker1'],0,2)."' and  
		FOD_DAY = '".substr($_POST['datepicker1'],3,2)."' and  (FDM_P_TOTO <> 'Y'  or FDM_P_TOTO is null) order by FDM_SERIAL_NO desc ";
	
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result))
		{
			$sn=$row['FDM_SERIAL_NO'];
		}

		$sn=$sn+1;
		
		$query="SELECT          FOD_UNI, FDM_LOT_NO, remark1
				FROM              FILLPLAN_OUT_DECIDE
				WHERE          (FDM_LOT_NO = '".$_POST['lot_no']."')";
		$result=mssql_query($query);
	
		$numRows=mssql_num_rows($result);
		while($row=mssql_fetch_array($result))
		{
			$fod_uni=$row['FOD_UNI'];
		}
	
		///更新充填計畫
		if($_GET['qtydm']==''){$qty_drum=NULL;}
		$qty=$_POST['numl']*literkg1($_GET['pid']);
		$query="UPDATE        dbo.FILLPLAN_OUT_DECIDE
				SET                  	  FOD_YEAR_MONTH ='".substr($_POST['datepicker1'],6,4).substr($_POST['datepicker1'],0,2)."', FOD_DAY ='".substr($_POST['datepicker1'],3,2)."', 
										  FOD_O_YEAR_MONTH ='".substr($_POST['datepicker3'],6,4).substr($_POST['datepicker3'],0,2)."', FOD_O_DAY ='".substr($_POST['datepicker3'],3,2)."',
										  CTD_CUST_NO ='".$_GET['cid']."', PDD_PROD_NO ='".$_GET['pid']."', CTD_CUST_NO_GROUP =".$cust_group.",
										  FDM_SERIAL_NO =".$sn.", FDM_LY_NO ='".$_GET['lyno']."', FDM_CREATE_DATE ='".$_GET['cdt']."', 
										  FDM_CREATOR ='".$_GET['ct']."', FDM_SPECIFIC ='".$_GET['style']."', FDM_QTY =".$qty.", 
										  FDM_QTY_UNIT ='".$_GET['qtyun']."', FDM_EXPECT_DATE ='".$s1."', FDM_LOT_NO ='".trim($_POST['lot_no'])."', FDM_ITEM =NULL, 
										  FDM_RESULT_DATE ='".$s2."', FDM_COA_BEFORE ='".$coa."', FDM_OUT_DATE ='".$s3."', FDM_BACK_DATE ='".$s4."', 
										  FDM_SAM_BEFORE ='".$smp."', FDM_SAM_BEF_CNT ='".$_POST['be_cnt']."', FDM_SAM_CNT ='".$_POST['sam_cnt']."', 
										  FDM_ATTACH_CNT ='".$_POST['att_cnt']."', FDM_TRANSFROM =NULL, FDM_PRINT_OUT =NULL, 
										  FDM_FILLED_B_DATE =NULL, FDM_FILLED_E_DATE =NULL, FDM_FILLED_MAN =NULL, 
										  FDM_FILLED_LY_QTY =NULL, FDM_FILLED_DM_QTY =NULL, FDM_CHK_SAM_PURGE =NULL, 
										  FDM_CHK_CHG_PURGE =NULL, FDM_PURGE = 0, FDM_MOD_DATE ='".date("YmdHis")."', 
										  FDM_P_TOTO = 'N', FOD_BAR_PRN_DATE =NULL, FDM_REAL_OUT_TIME =NULL, 
										  FDM_REAL_RETURN_TIME =NULL, WASH_DATE='".dod(trim($_POST['datepicker5']))."',  
										  remark1='".$_POST['remark1']."'
				WHERE         (FOD_UNI = '".$_GET['uni']."')";
		sql_rec($_SERVER['PHP_SELF'],$query);
//		echo $query."<BR>";
//		break;
		$result = mssql_query($query);
	
		// update FILLPLAN_DRUM_CUSTOMER 
		$query="UPDATE          FILLPLAN_DRUM_CUSTOMER
				SET                   FDM_LOT_NO = '".$_POST['lot_no']."' 
				WHERE          (FOD_UNI = ".$_GET['uni'].") ";
		sql_rec($_SERVER['PHP_SELF'],$query);
		$result=mssql_query($query);

	///新增 PROD_STOCKS
	if($_GET['style']<>'DM')
	{
		if($_SESSION['fill_qty']<1){$_SESSION['fill_qty']=1;}
		$query="INSERT INTO PRODUCT_STOCKS
                            (STK_LOT_NO, PDD_PROD_NO, CTD_CUST_NO, STK_OLD_LOT_NO, STK_MAKE_DATE, STK_QTY, 
                            STK_DRUM_COUNT, STK_PRE_OUT_QTY, STK_PRE_OUT_DRUM_COUNT, STK_DECI_OUT_QTY)
			VALUES          ('".$_POST['lot_no']."','".$_GET['pid']."','".$_GET['cid']."',NULL,'".dod($_SESSION['datepicker1'])."','".$_SESSION['fill_qty']."',
							'".$_GET['qtydm']."','".$stk_pre_out_qty."','".$stk_pre_out_drum_cnt."','".$stk_deci_qty."')";
		$result = mssql_query($query);
	}
	else  ///DM 處理
	{
		$query="SELECT          FILLPLAN_DRUM_CUSTOMER.FOD_UNI, FILLPLAN_DRUM_CUSTOMER.CTD_CUST_NO, FILLPLAN_DRUM_CUSTOMER.FDMC_DRUM_COUNT
			FROM              FILLPLAN_DRUM_CUSTOMER INNER JOIN
                            CUSTOMER_DATA ON FILLPLAN_DRUM_CUSTOMER.CTD_CUST_NO = CUSTOMER_DATA.CTD_CUST_NO
			WHERE          (FOD_UNI = ".$_GET['uni'].")";	
		$result = mssql_query($query);
		while($row=mssql_fetch_array($result))
		{
			$query1="INSERT INTO PRODUCT_STOCKS
                            (STK_LOT_NO, PDD_PROD_NO, CTD_CUST_NO, STK_OLD_LOT_NO, STK_MAKE_DATE, STK_QTY, 
                            STK_DRUM_COUNT, STK_PRE_OUT_QTY, STK_PRE_OUT_DRUM_COUNT, STK_DECI_OUT_QTY)
			VALUES          ('".$_POST['lot_no']."','".$_GET['pid']."','".$row['CTD_CUST_NO']."',NULL,'".dod($_SESSION['datepicker1'])."','".($_GET['qty']/$_GET['qtydm']*$row['FDMC_DRUM_COUNT'])."',
							'".$row['FDMC_DRUM_COUNT']."','".$stk_pre_out_qty."','".$stk_pre_out_drum_cnt."','".$stk_deci_qty."')";
			$result1 = mssql_query($query1);			
		}	
	
	}

	///檢查充填指示  
	

	$query="SELECT FILL_INDICATE.FDM_LOT_NO, FILL_INDICATE.PDD_PROD_NO, FILL_INDICATE.FID_FILL_BEGIN_DATE, 
                   FILL_INDICATE.FID_FILL_END_DATE, FILL_INDICATE.FID_QTY, FILL_INDICATE.FID_SAM_COUNT, 
                   FILL_INDICATE.FID_SAM_DATE, FILL_INDICATE.FID_SAM_ACOUNT, FILL_INDICATE.FID_SAM_ADATE, 
                   FILL_INDICATE.FID_SAM_STATE, FILL_INDICATE.FID_OPERATOR, FILL_INDICATE.FID_WASHED_COUNT, 
                   FILL_INDICATE.FID_NDRUM_COUNT, FILL_INDICATE.FID_IS_WASHED, FILL_INDICATE.FID_REQ_DM_COUNT, 
                   FILL_INDICATE.FID_BK_CLR_DM, FILL_INDICATE.FID_BK_NEW_DM, FILLPLAN_OUT_DECIDE.FDM_EXPECT_DATE
FROM      FILL_INDICATE INNER JOIN
                   FILLPLAN_OUT_DECIDE ON FILL_INDICATE.FDM_LOT_NO = FILLPLAN_OUT_DECIDE.FDM_LOT_NO
WHERE   (FILL_INDICATE.FDM_LOT_NO = '".$_POST['lot_no']."')";
	$result = mssql_query($query);
	$numRows=mssql_num_rows($result);
	while($rows=mssql_fetch_array($result)){
		$expect_date=$rows['FDM_EXPECT_DATE'];	
	}
	if($numRows>0)
		{
			
			$query="UPDATE        dbo.FILL_INDICATE
			SET                  FDM_LOT_NO ='".$_POST['lot_no']."', PDD_PROD_NO ='".$_GET['pid']."',
									  FID_QTY ='".$_POST['numl']."', FID_SAM_COUNT =".($_POST['be_cnt']+$_POST['att_cnt']+$_POST['sam_cnt']).",
									  FID_SAM_ACOUNT =".$_POST['sam_cnt']." 
			WHERE         (FDM_LOT_NO = '".$_POST['lot_no']."')";
			
			$result = mssql_query($query);
			echo "update fill_indicate.........</br>";
		}
	else
		{
			$query="INSERT INTO dbo.FILL_INDICATE
							  (FDM_LOT_NO, PDD_PROD_NO, FID_QTY, FID_SAM_COUNT, FID_SAM_ACOUNT)
					VALUES         ('".$_POST['lot_no']."','".$_GET['pid']."','".$_POST['numl']."',".($_POST['be_cnt']+$_POST['att_cnt']+$_POST['sam_cnt']).",
					'".$_POST['sam_cnt']."')"; 
			$result = mssql_query($query);
			echo "insert into fill_indicate.........</br>";
		}
	echo $query.'</br>';
	
	



		///新增 PRODUCT_RUNNING_ACCOUNT LY
		if($_GET['style']=='LY')
		{
			$query="select PRA_LOT_NO from PRODUCT_RUNNING_ACCOUNT where PRA_LOT_NO='".$_POST['lot_no']."'";
			$result = mssql_query($query);
			$numRows=mssql_num_rows($result);
			if($numRows==0)
			{
				$query="INSERT INTO PRODUCT_RUNNING_ACCOUNT
									(PRA_LOT_NO, PRA_SERIAL_NO, PDD_PROD_NO, PRA_PURPOSE, CTD_CUST_NO, PRA_UNIT, 
									PRA_FAKE_IN_DATE1, PRA_IN_DM, PRA_REAL_IN_QTY, PRA_OUT_DM, PRA_REAL_OUT_QTY, PRA_OUT_COUNT, PRA_OUT_TERM)
					VALUES          ('".$_POST['lot_no']."', ".$sn.", '".$_GET['pid']."', 1, '".$_GET['cid']."', '".$_GET['qtyun']."', '".date("Ymd")."', 0, 0, 0, 0, 0, '".prod_out_term($_GET['pid'],$_GET['cid'])."')";
				$result = mssql_query($query);
				sql_rec($_SERVER['PHP_SELF'],$query);
				echo "insert into PRODUCT_RUNNING_ACCOUNT.........</br>";
			}	
			if($numRows>0)
			{
					$query="update PRODUCT_RUNNING_ACCOUNT set PRA_LOT_NO='".$_POST['lot_no']."', PRA_SERIAL_NO=".$sn.", PDD_PROD_NO='".$_GET['pid']."', PRA_PURPOSE=1, 
							CTD_CUST_NO='".$_GET['cid']."', PRA_UNIT='".$_GET['qtyun']."', PRA_FAKE_IN_DATE1='".date("Ymd")."', PRA_IN_DM=0, PRA_REAL_IN_QTY=0, PRA_OUT_DM=0,
							PRA_REAL_OUT_QTY=0, PRA_OUT_COUNT=0, PRA_OUT_TERM='".prod_out_term($_GET['pid'],$_GET['cid'])."' where PRA_LOT_NO='".$_POST['lot_no']."'";
					$result = mssql_query($query);
					sql_rec($_SERVER['PHP_SELF'],$query);
					echo "update PRODUCT_RUNNING_ACCOUNT.........</br>";
			}
		} // end if($_GET['style']=='LY')
	
	///檢查有沒有依賴				{
				$_SESSION['select_test_items']=$_POST['select_test_items'];			
				if($_POST['select_test_items']=='0')
					{
						$ull='../lib/save_analyze.php?datepicker1='.dod($_POST['datepicker1'])."1000&comfirm=Y&cid=".$_GET['cid']."&pid=".$_GET['pid']."&out_date=".$_POST['datepicker3'].$_POST['time3'].$_POST['time32'].
						"&lot_no=".$_POST['lot_no']."&out_date=".$s1."&testitems=".$_aniitems."&smp_cnt=".($_SESSION['smp_total'])."&item_style=".$_SESSION['select_test_items']."&sample_metal_f=".$_SESSION['sample_metal_f']."&sample_particle_f=".$_SESSION['sample_particle_f'];
						jumpto($ull); 
					}
				elseif($_POST['select_test_items']=='1')
					{
						$_aniitems=$_SESSION['Reg'];
						$items=new autoani;
						$items->cid=$_GET['cid'];
						$items->pid=$_GET['pid'];
						$items->get_rull_1();
						$ull='../lib/save_analyze.php?datepicker1='.dod($_POST['datepicker1'])."1000&comfirm=Y&cid=".$_GET['cid']."&pid=".$_GET['pid']."&out_date=".$_POST['datepicker3'].$_POST['time3'].$_POST['time32']."&lot_no=".$_POST['lot_no']."&out_date=".$s1."&testitems=".$_aniitems."&smp_cnt=".$_SESSION['smp_total']."&item_style=".$_SESSION['select_test_items']."&sample_metal_f=".$_SESSION['sample_metal_f']."&sample_particle_f=".$_SESSION['sample_particle_f'];
						jumpto($ull); 
					}
				elseif($_POST['select_test_items']=='2')
					{
						$_aniitems=$_SESSION['Full'];
						$items=new autoani;
						$items->cid=$_GET['cid'];
						$items->pid=$_GET['pid'];
						$items->get_rull_1();
						$ull='../lib/save_analyze.php?datepicker1='.dod($_POST['datepicker1'])."1000&comfirm=Y&cid=".$_GET['cid']."&pid=".$_GET['pid']."&out_date=".$_POST['datepicker3'].$_POST['time3'].$_POST['time32']."&lot_no=".$_POST['lot_no']."&out_date=".$s1."&testitems=".$_aniitems."&smp_cnt=".$_SESSION['smp_total']."&item_style=".$_SESSION['select_test_items']."&sample_metal_f=".$_SESSION['sample_metal_f']."&sample_particle_f=".$_SESSION['sample_particle_f'];
						jumpto($ull); 
					}

		if($_GET['style']=='LY')
		{		
			$query="select FOD_UNI from FILLPLAN_DRUM_CUSTOMER where FOD_UNI=".$_GET['uni'];
			$result = mssql_query($query);
			$numRows = mssql_num_rows($result);		
			if($numRows>0)
			{
				$query="update FILLPLAN_DRUM_CUSTOMER set CTD_CUST_NO='".$_GET['cid']."' where FOD_UNI=".$_GET['uni'];	
			}
			else{
				$query="INSERT INTO FILLPLAN_DRUM_CUSTOMER
								(FOD_UNI, FDM_LOT_NO, CTD_CUST_NO, FDMC_SERIAL_NO)
	VALUES          (".$_GET['uni'].",'".$_POST['lot_no']."','".$_GET['cid']."',".$sn.")";	
			}
			sql_rec($_SERVER['PHP_SELF'],$query);
			$result = mssql_query($query);
		}	
		else
		{
			$query="SELECT          FILLPLAN_DRUM_CUSTOMER.* FROM FILLPLAN_DRUM_CUSTOMER where FOD_UNI=".$_GET['uni'];
			$result = mssql_query($query);
			$numRows = mssql_num_rows($result);	
			$dn=1;	
			while($row = mssql_fetch_array($result))
			{		
				$query1="INSERT INTO PRODUCT_RUNNING_ACCOUNT
									(PRA_LOT_NO, PRA_SERIAL_NO, PDD_PROD_NO, PRA_PURPOSE, CTD_CUST_NO, PRA_UNIT, 
									PRA_FAKE_IN_DATE1, PRA_IN_DM, PRA_REAL_IN_QTY, PRA_OUT_DM, PRA_REAL_OUT_QTY, PRA_OUT_COUNT, PRA_OUT_TERM)
					VALUES          ('".$_POST['lot_no']."', ".$dn.", '".$_GET['pid']."', 1, '".$row['A']."', '".$_GET['qtyun']."', '".date("Ymd")."', 0, 0, 0, 0, 0, '".prod_out_term($_GET['pid'],$row['A'])."')";
				$result1 = mssql_query($query1);
				$dn=$dn+1;
			}
		} //end if($_GET['style']=='LY')
	}  //end if($_POST['numl']=='0')
}   ///end save

function ani_items($lid,$cid,$pid,$odate)
{
	$rull=new autoani;
	$rull->lid=$lid;
	$rull->cid=$cid;
	$rull->pid=$pid;
	$rull->outdate=$odate;
	$rull->get_rull_1();
	$rull->status_();
	$rull->items_();
	return $rull->item;
}
	
function big_drum_volume($lorry_no)
{
	$query="SELECT          BDV_VOLUME
			FROM              BIG_DRUM_VOLUME
			WHERE          (PDD_PROD_SHORT_NAME = '".$lorry_no."')";	
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result))
	{
		$qty=$row['BDV_VOLUME'];
	}	
	return $qty;
}

if(isset($_POST['add_cust_drum']))
{
	$query="select FOD_UNI from FILLPLAN_DRUM_CUSTOMER where FOD_UNI=".$_GET['uni'];
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	if($numRows>0){
		$query="INSERT INTO FILLPLAN_DRUM_CUSTOMER
                            (FOD_UNI, FDM_LOT_NO, CTD_CUST_NO, FDMC_SERIAL_NO, FDMC_DRUM_COUNT)
			SELECT     top(1)     ".$_GET['uni']." , '".$_POST['lot_no']."' ,'".$_SESSION['cid']."', FDMC_SERIAL_NO + 1 , ".$_POST['cnt']." 
			FROM              FILLPLAN_DRUM_CUSTOMER 
			WHERE          (FOD_UNI = ".$_GET['uni'].") 
			ORDER BY   FDMC_SERIAL_NO DESC";
			echo "INSERT INTO FILLPLAN_DRUM_CUSTOMER.........</br>";
	}
	else {
		$query="INSERT INTO FILLPLAN_DRUM_CUSTOMER
                (FOD_UNI, FDM_LOT_NO, CTD_CUST_NO, FDMC_SERIAL_NO, FDMC_DRUM_COUNT) VALUES (".$_GET['uni'].", '".$_POST['lot_no']."' ,'".$_SESSION['cid']."', 1,".$_POST['cnt'].")";
			echo "INSERT INTO FILLPLAN_DRUM_CUSTOMER.........</br>";
	}
	sql_rec($_SERVER['PHP_SELF'],$query);
//	$_SESSION['tmppp']= $query;
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	refresh();
}

if(isset($_POST['del_cust_drum']))
{
	$query="delete from FILLPLAN_DRUM_CUSTOMER where FOD_UNI=".$_GET['uni'];
	$result = mssql_query($query);
	refresh();
}

function _cust_drum_cnt()
{
//	require_once("../connections/conn.php");
	$str="";
	$query="SELECT          FILLPLAN_DRUM_CUSTOMER.FOD_UNI, FILLPLAN_DRUM_CUSTOMER.FDM_LOT_NO, 
                            FILLPLAN_DRUM_CUSTOMER.CTD_CUST_NO, FILLPLAN_DRUM_CUSTOMER.FDMC_SERIAL_NO, 
                            FILLPLAN_DRUM_CUSTOMER.FDMC_DRUM_COUNT, CUSTOMER_DATA.CTD_CUST_SHORT_NAME
			FROM              FILLPLAN_DRUM_CUSTOMER INNER JOIN
                            CUSTOMER_DATA ON FILLPLAN_DRUM_CUSTOMER.CTD_CUST_NO = CUSTOMER_DATA.CTD_CUST_NO
			WHERE          (FOD_UNI = ".$_GET['uni'].")";	
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result))
	{
		$str.=$row['CTD_CUST_SHORT_NAME']."(".$row['FDMC_DRUM_COUNT']."桶) ; ";
	}	
	return substr($str,0,-2);
}

function cust_prod($_cust_id,$pid){
	$query="SELECT  CTP_OUT_SMP_PE, CTP_OUT_SMP_TFA, CTP_BEF_SMP_PE, CTP_BEF_SMP_TFA FROM CUSTOMER_PRODUCTS WHERE (PDD_PROD_NO = '".$pid."') AND (CTD_CUST_NO = '".$_cust_id."')";
	$result=mssql_query($query);
	$row=mssql_fetch_array($result);
	$smp_pe=$row['CTP_OUT_SMP_PE'];
	$smp_tfa=$row['CTP_OUT_SMP_TFA'];
	$smp_particle=$row['CTP_BEF_SMP_TFA'];
	$smp_metal=$row['CTP_BEF_SMP_PE'];
	if($smp_particle==''){$smp_particle=0;}
	if($smp_metal==''){$smp_metal=0;}
	return $aa=array($smp_pe,$smp_tfa,$smp_particle,$smp_metal);
}
?>