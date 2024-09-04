<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
lasturl1();
remurl("edit_fill_out");
datepick();
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
		echo ' 桶數：<input name="cnt" type="text" id="cnt" size="8" value="'.$_SESSION['cnt'].'" />';
		echo '<input type="submit" name="add_cust_drum" id="" value="+" ></td></tr>';
		echo '<tr><td>';
		echo '<textarea name="textarea" id="textarea" cols="70" rows="2">';
		echo _cust_drum_cnt();
		echo '</textarea>';
		echo '<input type="submit" name="del_cust_drum" id="" value="清除" ></td></tr>';
		echo '</td></tr>';
		echo '</table>';
	}
	?>
  <table border="0">
      <tr><td width="100">客戶；</td><td width="500">
	  <?php 
	  if ($row['CTD_CUST_NO']!=NULL){echo get_cust_name($row['CTD_CUST_NO']);}
	  else{echo $row['CTD_CUST_NO_GROUP'];}
	  $_SESSION['fill_qty']=big_drum_volume($_GET['lyno']);
	  ?>
      </td></tr>
      <tr><td width="100">品名；</td><td width="500"><?php echo get_pdd_name($_GET['pid']);?></td></tr>
      <tr><td width="100">LY-NO；</td><td width="500"><?php echo $_GET['lyno'];?></td></tr>
      <tr><td width="100">桶數；</td><td width="500"><?php echo $_GET['qtydm'];?></td></tr>
      <tr><td width="100">數量；</td><td width="500"><?php echo $row['FDM_QTY'];?> KG</td></tr>
      <tr><td width="100">充填量；</td><td width="500"><label for="numl"></label>
    <input name="numl" type="text" id="numl" size="8" value="<?php 	if(!$_SESSION['fill_qty']){$numl=$row['FDM_QTY']/literkg1($_GET['pid']);echo trim($numl);}else{echo $_SESSION['fill_qty'];}?>"/> 
    L</td></tr>
      <tr><td width="100">預計充填日期；</td><td width="300"><span class="d1">
        <input type="text" name="datepicker1" id="datepicker1" size="10" value="<?php 
		if($_SESSION['fill_date']==''){
			$t=strtotime("+1 day");
			$_SESSION['fill_date']=date("m/d/Y",$t);
		}
		echo $_SESSION['fill_date'];
		?>" readonly/>
        <input type="submit" name="renew" value="更新LOT_NO" />
    </span></td></tr>
      <tr><td width="50">預計充填時間；</td><td width="50"><input name="time1" type="text" id="time1" value="<?php 
	  if($row["FDM_EXPECT_DATE"]==''){echo '08';}
	  else{echo substr($row["FDM_EXPECT_DATE"],8,2);}
	  ?>" size="4"/>
      :
          <input name="time12" type="text" id="time5" value="<?php if($row["FDM_EXPECT_DATE"]==''){echo '30';}
	  else{echo substr($row["FDM_EXPECT_DATE"],10,2);}?>" size="4"/>
EX: 08:30</td></tr>
      <tr><td width="50">LOT_NO；</td><td width="50"><input name="lot_no" type="text" id="lot_no" value="<?php 
		  	echo $lot=genlot($_GET['pid'],dod($_SESSION['fill_date']),$_GET['lyno']);		
	  ?>" size="12"  readonly="readonly" /></td></tr>
      <tr><td width="50">分析預定；</td><td width="250">
        <select name="select_test_items" id="select_test_items">
          <option value="0"></option>
          <option value="1">常規</option>
          <option value="2">全項</option>
        </select>
        <input type="submit" name="anylize" id="anylize" value="  分析  " ><?php if(checkanalizestatus($lot)==1){echo '已有分析依賴';$str="edit_anylize_";}else{echo "沒有分析依賴";$str="edit_anylize_auto";}?>
       </td></tr>
      <tr><td width="50">希望報告時間；</td><td width="250"><span class="d1">
        <input type="text" name="datepicker2" id="datepicker2" size="10" value="<?php 
			$t=ddo($_SESSION['fill_date']);		
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
      <tr><td width="50">先行樣品瓶數；</td><td width="250">
   	  <input name="be_cnt" type="text" id="be_cnt" size="5" value="<?php echo $row["FDM_SAM_BEF_CNT"];?>" /></td></tr>
      <tr><td width="50">隨貨取樣瓶數；</td><td width="250">
      <input name="att_cnt" type="text" id="att_cnt" size="5" value="<?php echo $row["FDM_ATTACH_CNT"];?>" /></td></tr>
      <tr><td width="50">分析取樣瓶數；</td><td width="250">
	  <input name="sam_cnt" type="text" id="sam_cnt" size="5" value="<?php echo $row["FDM_SAM_CNT"];?>" /></td></tr>
      <tr><td width="50">總取樣瓶數；</td>
  <td width="250"><input name="smp_cnt" type="text" id="smp_cnt" size="5" value="<?php echo $cn=2+$row["FDM_SAM_BEF_CNT"]+$row["FDM_ATTACH_CNT"]+$row["FDM_SAM_CNT"];?>" readonly/></td>
  </tr><tr><td width="50">備註1：</td><td><input name="remark1" type="text" id="remark1" size="20" value="<?php echo $row["remark1"];?>" /></td></tr>
  
  </table>
  <table border="0">
    <tr>
    <td width="300">&nbsp;</td>
    <td width="300"><input type="submit" name="save" id="save" value="  儲存  " />
      <input type="submit" name="leave" onClick="window.open('index.php?url=fill_out_plan', '_self');" value="  離開  " /></td>
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
if(isset($_POST["anylize"])){
$_SESSION['fill_qty']=$_POST['numl'];
if($_POST['coa']=='on'){$coa='Y';}else{$coa='N';}
if($_POST['smp']=='on'){$smp='Y';}else{$smp='N';}
if($_POST['style']='LY'){$cust_group='NULL';$qdm='NULL';}
else{
$qdm=$_GET['qtydm'];
}
	if($_GET['qtydm']==''){$_GET['qtydm']=NULL;}
$s1=substr($_POST['datepicker1'],6,4).substr($_POST['datepicker1'],0,2).substr($_POST['datepicker1'],3,2).substr($_POST['time1'],0,2).$_POST['time12']."00";
$s2=substr($_POST['datepicker2'],6,4).substr($_POST['datepicker2'],0,2).substr($_POST['datepicker2'],3,2).substr($_POST['time2'],0,2).$_POST['time22']."00";
$s3=substr($_POST['datepicker3'],6,4).substr($_POST['datepicker3'],0,2).substr($_POST['datepicker3'],3,2).substr($_POST['time3'],0,2).$_POST['time32']."00";
$s4=substr($_POST['datepicker3'],6,4).substr($_POST['datepicker4'],0,2).substr($_POST['datepicker4'],3,2).substr($_POST['time4'],0,2).$_POST['time42']."00";
$query="UPDATE        dbo.FILLPLAN_OUT_DECIDE
		SET               FOD_YEAR_MONTH ='".substr($_POST['datepicker1'],6,4).substr($_POST['datepicker1'],0,2)."', FOD_DAY ='".substr($_POST['datepicker1'],3,2)."', 
						  FOD_O_YEAR_MONTH ='".substr($_POST['datepicker3'],6,4).substr($_POST['datepicker3'],0,2)."', FOD_O_DAY ='".substr($_POST['datepicker3'],3,2)."',
						  CTD_CUST_NO ='".$_GET['cid']."', PDD_PROD_NO ='".$_GET['pid']."', CTD_CUST_NO_GROUP =".$cust_group.",
                          FDM_SERIAL_NO = 1, FDM_LY_NO ='".$_GET['lyno']."', FDM_CREATE_DATE ='".$_GET['cdt']."', 
						  FDM_CREATOR ='".$_GET['ct']."', FDM_SPECIFIC ='".$_GET['style']."', FDM_QTY =".$_POST['numl']*literkg1($_GET['pid']).", 
						  FDM_QTY_UNIT ='".$_GET['qtyun']."', FDM_EXPECT_DATE ='".$s1."', FDM_LOT_NO ='".$_POST['lot_no']."', FDM_ITEM =NULL, 
						  FDM_RESULT_DATE ='".$s2."', FDM_COA_BEFORE ='".$coa."', FDM_OUT_DATE ='".$s3."', FDM_BACK_DATE ='".$s4."', 
                          FDM_SAM_BEFORE ='".$smp."', FDM_SAM_BEF_CNT ='".$_POST['be_cnt']."', FDM_SAM_CNT ='".$_POST['sam_cnt']."', 
                          FDM_ATTACH_CNT ='".$_POST['att_cnt']."', FDM_TRANSFROM =NULL, FDM_PRINT_OUT =NULL, 
                          FDM_FILLED_B_DATE =NULL, FDM_FILLED_E_DATE =NULL, FDM_FILLED_MAN =NULL, 
                          FDM_FILLED_LY_QTY =NULL, FDM_FILLED_DM_QTY =NULL, FDM_CHK_SAM_PURGE =NULL, 
                          FDM_CHK_CHG_PURGE =NULL, FDM_PURGE = 0, FDM_MOD_DATE =NULL, 
                          FDM_P_TOTO = 'N', FOD_BAR_PRN_DATE =NULL, FDM_REAL_OUT_TIME =NULL, 
                          FDM_REAL_RETURN_TIME =NULL, 
						  remark1='".$_POST['remark1']."'
		WHERE         (FOD_UNI = '".$_GET['uni']."')";

$result = mssql_query($query);
$query="SELECT         FDM_LOT_NO, PDD_PROD_NO, FID_FILL_BEGIN_DATE, FID_FILL_END_DATE, 
                          FID_QTY, FID_SAM_COUNT, FID_SAM_DATE, FID_SAM_ACOUNT, 
                          FID_SAM_ADATE, FID_SAM_STATE, FID_OPERATOR, FID_WASHED_COUNT, 
                          FID_NDRUM_COUNT, FID_IS_WASHED, FID_REQ_DM_COUNT, 
                          FID_BK_CLR_DM, FID_BK_NEW_DM
FROM             dbo.FILL_INDICATE
WHERE         (FDM_LOT_NO = '".$_POST['lot_no']."')";

$result = mssql_query($query);
$numRows=mssql_num_rows($result);
if($numRows>0){
$query="UPDATE        dbo.FILL_INDICATE
SET                  FDM_LOT_NO =, PDD_PROD_NO =, FID_FILL_BEGIN_DATE =, 
                          FID_FILL_END_DATE =, FID_QTY =, FID_SAM_COUNT =, FID_SAM_DATE =, 
                          FID_SAM_ACOUNT =, FID_SAM_ADATE =, FID_SAM_STATE =, 
                          FID_OPERATOR =, FID_WASHED_COUNT =, FID_NDRUM_COUNT =, 
                          FID_IS_WASHED =, FID_REQ_DM_COUNT =, FID_BK_CLR_DM =, 
                          FID_BK_NEW_DM =
WHERE         (FDM_LOT_NO = '".$_POST['lot_no']."')";

	}
	else{
		$query="INSERT INTO dbo.FILL_INDICATE
                          (FDM_LOT_NO, PDD_PROD_NO, FID_QTY, FID_SAM_COUNT, FID_SAM_ACOUNT)
				VALUES         ('".$_POST['lot_no']."','".$_GET['pid']."','".$_POST['numl']."',".($_POST['be_cnt']+$_POST['att_cnt']+$_POST['sam_cnt']).",
				'".$_POST['sam_cnt']."')";
	}
	
//	echo "Q4 : ".$query."</br>";
	$result = mssql_query($query);
if($_POST['smp']=='on'){$smp='Y';}else{$smp='N';}
if($_POST['coa']=='on'){$coa='Y';}else{$coa='N';}
$url="../cal/index.php?url=".$str."&CTD_CUST_NO=".$_GET['cid']."&AND_TOTAL=".($_POST['be_cnt']+$_POST['att_cnt']+$_POST['sam_cnt']).
"&AND_GOODS=".$_GET['pid']."&AND_BEFORE=".$smp."&COA_BEFORE=".$coa."&rt=".$s2."&AND_LOT_NO=".$lot."&outdate=".dod($_POST['datepicker3']);
jumpto($url);
}

if(isset($_POST['renew'])){
	$_SESSION['fill_qty']=$_POST['numl'];
	$_SESSION['fill_date']=$_POST['datepicker1'];
	refresh();
}

if(isset($_POST['leave'])){
	unset($_SESSION['fill_qty']);
	unset($_SESSION['dapic1']);
	unset($_SESSION['fill_date']);
	jumpto("index.php?url=fill_out_plan");
}

if(isset($_POST["save"]))
{
	if($_POST['coa']=='on'){$coa='Y';}else{$coa='N';}
	if($_POST['smp']=='on'){$smp='Y';}else{$smp='N';}
	if($_POST['style']='LY'){$cust_group='NULL';$qdm='NULL';}
	$s1=substr($_POST['datepicker1'],6,4).substr($_POST['datepicker1'],0,2).substr($_POST['datepicker1'],3,2).substr($_POST['time1'],0,2).$_POST['time12']."00";
	$s2=substr($_POST['datepicker2'],6,4).substr($_POST['datepicker2'],0,2).substr($_POST['datepicker2'],3,2).substr($_POST['time2'],0,2).$_POST['time22']."00";
	$s3=substr($_POST['datepicker3'],6,4).substr($_POST['datepicker3'],0,2).substr($_POST['datepicker3'],3,2).substr($_POST['time3'],0,2).$_POST['time32']."00";
	$s4=substr($_POST['datepicker3'],6,4).substr($_POST['datepicker4'],0,2).substr($_POST['datepicker4'],3,2).substr($_POST['time4'],0,2).$_POST['time42']."00";
if($_POST['numl']=='0'){my_msg("充填量不可為零");}
else
{
	$sn=1;
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
	$query="UPDATE        dbo.FILLPLAN_OUT_DECIDE
			SET                  	  FOD_YEAR_MONTH ='".substr($_POST['datepicker1'],6,4).substr($_POST['datepicker1'],0,2)."', FOD_DAY ='".substr($_POST['datepicker1'],3,2)."', 
									  FOD_O_YEAR_MONTH ='".substr($_POST['datepicker3'],6,4).substr($_POST['datepicker3'],0,2)."', FOD_O_DAY ='".substr($_POST['datepicker3'],3,2)."',
									  CTD_CUST_NO ='".$_GET['cid']."', PDD_PROD_NO ='".$_GET['pid']."', CTD_CUST_NO_GROUP =".$cust_group.",
									  FDM_SERIAL_NO =1, FDM_LY_NO ='".$_GET['lyno']."', FDM_CREATE_DATE ='".$_GET['cdt']."', 
									  FDM_CREATOR ='".$_GET['ct']."', FDM_SPECIFIC ='".$_GET['style']."', FDM_QTY =".$_POST['numl']*literkg1($_GET['pid']).", 
									  FDM_QTY_UNIT ='".$_GET['qtyun']."', FDM_EXPECT_DATE ='".$s1."', FDM_LOT_NO ='".$_POST['lot_no']."', FDM_ITEM =NULL, 
									  FDM_RESULT_DATE ='".$s2."', FDM_COA_BEFORE ='".$coa."', FDM_OUT_DATE ='".$s3."', FDM_BACK_DATE ='".$s4."', 
									  FDM_SAM_BEFORE ='".$smp."', FDM_SAM_BEF_CNT ='".$_POST['be_cnt']."', FDM_SAM_CNT ='".$_POST['sam_cnt']."', 
									  FDM_ATTACH_CNT ='".$_POST['att_cnt']."', FDM_TRANSFROM =NULL, FDM_PRINT_OUT =NULL, 
									  FDM_FILLED_B_DATE =NULL, FDM_FILLED_E_DATE =NULL, FDM_FILLED_MAN =NULL, 
									  FDM_FILLED_LY_QTY =NULL, FDM_FILLED_DM_QTY =NULL, FDM_CHK_SAM_PURGE =NULL, 
									  FDM_CHK_CHG_PURGE =NULL, FDM_PURGE = 0, FDM_MOD_DATE =NULL, 
									  FDM_P_TOTO = 'N', FOD_BAR_PRN_DATE =NULL, FDM_REAL_OUT_TIME =NULL, 
									  FDM_REAL_RETURN_TIME =NULL,
									  remark1='".$_POST['remark1']."'
			WHERE         (FOD_UNI = '".$_GET['uni']."')";
			$_SESSION['sup_qty']=$query;
	echo $query."</br>";
	$result = mssql_query($query);
	
	///檢查充填指示

	$query="SELECT         FDM_LOT_NO, PDD_PROD_NO, FID_FILL_BEGIN_DATE, FID_FILL_END_DATE, 
									  FID_QTY, FID_SAM_COUNT, FID_SAM_DATE, FID_SAM_ACOUNT, 
									  FID_SAM_ADATE, FID_SAM_STATE, FID_OPERATOR, FID_WASHED_COUNT, 
									  FID_NDRUM_COUNT, FID_IS_WASHED, FID_REQ_DM_COUNT, 
									  FID_BK_CLR_DM, FID_BK_NEW_DM
			FROM             dbo.FILL_INDICATE
			WHERE         (FDM_LOT_NO = '".$_POST['lot_no']."')";
	$result = mssql_query($query);
	$numRows=mssql_num_rows($result);
	echo $query."</br>";
	echo "NO:". $numRows."</br>";
	if($numRows>0)
		{
			$query="UPDATE        dbo.FILL_INDICATE
			SET                  FDM_LOT_NO ='".$_POST['lot_no']."', PDD_PROD_NO ='".$_GET['pid']."', FID_FILL_BEGIN_DATE ='".date("Ymdhis")."', 
									  FID_QTY ='".$_POST['numl']."', FID_SAM_COUNT =".($_POST['be_cnt']+$_POST['att_cnt']+$_POST['sam_cnt']).",
									  FID_SAM_ACOUNT =".$_POST['sam_cnt']." 
			WHERE         (FDM_LOT_NO = '".$_POST['lot_no']."')";
			echo $query."</br>";
			$result = mssql_query($query);
		}
	else
		{
			$query="INSERT INTO dbo.FILL_INDICATE
							  (FID_FILL_BEGIN_DATE, FDM_LOT_NO, PDD_PROD_NO, FID_QTY, FID_SAM_COUNT, FID_SAM_ACOUNT)
					VALUES         ('".date("Ymdhis")."','".$_POST['lot_no']."','".$_GET['pid']."','".$_POST['numl']."',".($_POST['be_cnt']+$_POST['att_cnt']+$_POST['sam_cnt']).",
					'".$_POST['sam_cnt']."')";
			echo $query."</br>";
			$result = mssql_query($query);
		}

	///新增 PROD_STOCKS
	$query="INSERT INTO PRODUCT_STOCKS
                            (STK_LOT_NO, PDD_PROD_NO, CTD_CUST_NO, STK_OLD_LOT_NO, STK_MAKE_DATE, STK_QTY, 
                            STK_DRUM_COUNT, STK_PRE_OUT_QTY, STK_PRE_OUT_DRUM_COUNT, STK_DECI_OUT_QTY)
			VALUES          ('".$_POST['lot_no']."','".$_GET['pid']."','".$_GET['cid']."',NULL,'".dod($_SESSION['fill_date'])."','".$_SESSION['fill_qty']."',
							'".$stk_drum_cnt."','".$stk_pre_out_qty."','".$stk_pre_out_drum_cnt."','".$stk_deci_qty."')";
	$result = mssql_query($query);
	echo $query."</br>";



	///新增 PRODUCT_RUNNING_ACCOUNT
	$query="select PRA_LOT_NO from PRODUCT_RUNNING_ACCOUNT where PRA_LOT_NO='".$_POST['lot_no']."'";
	$result = mssql_query($query);
	$numRows=mssql_num_rows($result);
	if($numRows==0){
		$query="INSERT INTO PRODUCT_RUNNING_ACCOUNT
								(PRA_LOT_NO, PRA_SERIAL_NO, PDD_PROD_NO, PRA_PURPOSE, CTD_CUST_NO, PRA_UNIT, 
								PRA_FAKE_IN_DATE1, PRA_IN_DM, PRA_REAL_IN_QTY, PRA_OUT_DM, PRA_REAL_OUT_QTY, PRA_OUT_COUNT, PRA_OUT_TERM)
				VALUES          ('".$_POST['lot_no']."', ".$sn.", '".$_GET['pid']."', 1, '".$_GET['cid']."', '".$_GET['qtyun']."', '".date("Ymd")."', 0, 0, 0, 0, 0, '".prod_out_term($_GET['pid'],$_GET['cid'])."')";
		$result = mssql_query($query);
		echo $query."</br>";
		}
	}
	if($numRows>0){
		$query="update PRODUCT_RUNNING_ACCOUNT set PRA_LOT_NO='".$_POST['lot_no']."', PRA_SERIAL_NO=".$sn.", PDD_PROD_NO='".$_GET['pid']."', PRA_PURPOSE=1, 
				CTD_CUST_NO='".$_GET['cid']."', PRA_UNIT='".$_GET['qtyun']."', PRA_FAKE_IN_DATE1='".date("Ymd")."', PRA_IN_DM=0, PRA_REAL_IN_QTY=0, PRA_OUT_DM=0,
				PRA_REAL_OUT_QTY=0, PRA_OUT_COUNT=0, PRA_OUT_TERM='".prod_out_term($_GET['pid'],$_GET['cid'])."' where PRA_LOT_NO='".$_POST['lot_no']."'";
		$result = mssql_query($query);
		echo $query."</br>";
	}
	///檢查有沒有依賴	
	if(checkanalizestatus($lot)<>1) 
			{
				$_SESSION['select_test_items']=$_POST['select_test_items'];			
				if($_POST['select_test_items']==0)
					{
						$ull='../lib/save_analyze.php?datepicker1='.dod($_POST['datepicker1'])."1000&comfirm=Y&cid=".$_GET['cid']."&pid=".$_GET['pid']."&out_date=".$_POST['datepicker3'].$_POST['time3'].$_POST['time32']."&lot_no=".$_POST['lot_no']."&out_date=".$s3;
						jumpto($ull); 
					}
				elseif($_POST['select_test_items']==1)
					{
						$items=new autoani;
						$items->cid=$_GET['cid'];
						$items->pid=$_GET['pid'];
						$items->get_rull();
						$ull='../lib/save_analyze.php?datepicker1='.dod($_POST['datepicker1'])."1000&comfirm=Y&cid=".$_GET['cid']."&pid=".$_GET['pid']."&out_date=".$_POST['datepicker3'].$_POST['time3'].$_POST['time32']."&lot_no=".$_POST['lot_no']."&out_date=".$s3."&testitems=".$items->Rull_Reg;
						jumpto($ull); 
					}
				elseif($_POST['select_test_items']==2)
					{
						$items=new autoani;
						$items->cid=$_GET['cid'];
						$items->pid=$_GET['pid'];
						$items->get_rull();
						$ull='../lib/save_analyze.php?datepicker1='.dod($_POST['datepicker1'])."1000&comfirm=Y&cid=".$_GET['cid']."&pid=".$_GET['pid']."&out_date=".$_POST['datepicker3'].$_POST['time3'].$_POST['time32']."&lot_no=".$_POST['lot_no']."&out_date=".$s3."&testitems=".$items->Rull_Total;
						$_SESSION['QQQQQ']=$ull;
							jumpto($ull); 
					}
			}
		
}   ///end save

function save_ani($a1,$a2,$a3,$a4,$a5,$a6){
	include("../connections/conn.php");
	$rull=new autoani;
	$rull->lid=$a1;
	$rull->cid=$a2;
	$rull->pid=$a3;
	$rull->outdate=$s3;
	$rull->get_rull();
	$rull->status();
	$rull->items();
	$query="select * from dbo.AnalyzeDesign where AND_LOT_NO='".$a1."'";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	if($numRows<1){
	$query="INSERT INTO dbo.AnalyzeDesign
                            (AND_RESULT,AND_RESULT_DATETIME,AND_GET_DATETIME,AND_GET_QTY,AND_MARK,AND_VALUE,AND_ANA_ID,AND_APPLY_DATE, AND_NEED_NO, AND_LOT_NO, AND_GOODS, AND_BEFORE, 
                            AND_SMP_DATETIME, AND_ITEM, AND_NOTE, AND_TOTAL, AND_OUT_QTY, 
                            AND_OUT_DATETIME, AND_REPORT_DATETIME, AND_PERSON)
	  VALUES          ('','','',0,'".'0'."','','','".date("Ymd")."',1,'".$a1."','".$a2."','N','".$a4."','".
	 $rull->item."','',0,0,'','".$a5."','".$_SESSION['uid']."')";
	}
	else{$query="UPDATE        dbo.AnalyzeDesign
	SET                  	  AND_ITEM = '".$rull->item."', AND_SMP_DATETIME = '".$a6."', AND_OUT_DATETIME = '".$a5."', AND_PERSON = '".$_SESSION['uid']."'
	WHERE         (AND_LOT_NO = '".$a1."')";}
	$result = mssql_query($query);	
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
	}
	else {
		$query="INSERT INTO FILLPLAN_DRUM_CUSTOMER
                (FOD_UNI, FDM_LOT_NO, CTD_CUST_NO, FDMC_SERIAL_NO, FDMC_DRUM_COUNT) VALUES (".$_GET['uni'].", '".$_POST['lot_no']."' ,'".$_SESSION['cid']."', 1,".$_POST['cnt'].")";
	}
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
?>