<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
session_start();
include("../connections/conn.php");
include("../lib/sag_in.php");
lasturl();
datepick();
?>
<table width="1240" border="1"><tr><td>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
荷姿變動連絡書
<table width="1024" border="1">
	<tr>
		<td>
			變更日期： <input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php if($_SESSION['datepicker1']){echo $_SESSION['datepicker1'];}?>" onChange="set_date_session(this.name,this.value)">
~
<input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php if($_SESSION['datepicker2']){echo $_SESSION['datepicker2'];}?>" onChange="set_date_session(this.name,this.value)">

變更理由:<?php selected();?> &nbsp;&nbsp;&nbsp;
 <input name="submit" type="submit" class="center button" id="submit" value="  查    詢  ">
 <input name="submit3" type="submit" class="center button" id="submit3" value=" 下載報告 ">
		</td>
		<td align="right"><font color="red">
			原LOT:<input name="oldlot" type="text" id="oldlot" size="10"  onChange="set_date_session(this.name,this.value)" value="<?php if($_SESSION['oldlot']){echo $_SESSION['oldlot'];}?>"/>
新LOT <input name="lotno" type="text" id="lotno" size="10"  onChange="set_date_session(this.name,this.value)" value="<?php if($_SESSION['lotno']){echo $_SESSION['lotno'];}?>"/>
 <input name="new" type="submit" class="center button" id="new" value="  變   更  " /></font>
		</td>
	</tr>
</table>



</form> 
</td></tr></table></br>
<?php 
	$editFormAction = $_SERVER['PHP_SELF'];
		
	$query="select * from STYLE_MODIFY
			where  STM_OLD_LOT_NO='".$_SESSION['STM_OLD_LOT_NO']."' and STM_NEW_LOT_NO='".$_SESSION['STM_NEW_LOT_NO']."'";
	$result = mssql_query($query);
	$row = mssql_fetch_array($result);
	$sagno=$row['SAG_NO'];
	$oldcust=substr($row[6],2,6);
	if($_SESSION['cid1']==''){$_SESSION['cid1']=$oldcust;}
	$newcust=substr($row[6],9,6);
	if($_SESSION['cid2']==''){$_SESSION['cid2']=$newcust;}
	///form2
	
	echo '<form id="form2" name="form2" method="post" action="'.$loginFormAction.'">';
	echo '荷姿變動連絡書 變更/輸入';
	echo '<table width="1240" height="100" bgcolor="#CCCCCC"><tr>';
	echo '<td>原 客 戶：
			<input type="button" name="X1" id="X1" value="X">
			<input name="cid1" type="text" id="cid1" size="16" value="'.$oldcust.'" readonly>
			<input type="button" name="cidb1" id="cidb1" value="查詢客戶">
			<input name="cname1" type="text" id="cname1" size="20" value="'.get_cust_name($oldcust).'" readonly>
			</br>原藥品名:
			<input type="button" name="X2" id="X2" value="X" readonly>
			<input name="pid1" type="text" id="pid1" size="16" value="'.$row[4].'" readonly>
			<input type="button" name="pidb1" id="pdd_no" value="查詢品名" readonly >
          <input name="pdd_chemical2" type="text" id="pdd_chemical2" size="20" value="'.get_prod_name($row[4]).'" readonly>
		  </br>原LOT NO ：<input name="oldlotno" type="text" id="oldlotno" size="14" value="'.$row[0].'" readonly>
		  &nbsp;&nbsp;新LOT NO ：<input name="newlotno" type="text" id="newlotno" size="14" value="'.$row[2].'" readonly>
		  </br>有效月數 ：<input name="remonth" type="text" id="remonth" size="6" value="'.$row[9].'" readonly>
		  殘存月數 ：<input name="leftmonth" type="text" id="leftmonth" size="6" value="'.$row[10].'" readonly>
		  </td>';
	echo '<td>新 客 戶：
          <input type="button" name="X3" id="X3" value="X" readonly>
          <input name="cid1" type="text" id="cid1" size="16" value="'.$newcust.'" readonly>
          <input type="button" name="cidb2" id="cidb2" value="查詢客戶" readonly>
          <input name="cname1" type="text" id="cname1" size="20" value="'.get_cust_name($newcust).'" readonly>
		  </br>新藥品名:
          <input type="button" name="X4" id="X4" value="X" readonly >
          <input name="pid2" type="text" id="pid2" size="16" value="'.$row[3].'" readonly>
          <input type="button" name="pidb2" id="pdd_no" value="查詢品名" readonly >
          <input name="pdd_chemical2" type="text" id="pdd_chemical2" size="20" value="'.get_prod_name($row[3]).'" readonly>
		  </br>原數量 ：<input name="oldqty" type="text" id="oldqty" size="10" value="'.$row[7].'" readonly>
		  &nbsp;&nbsp;新數量 ：<input name="newqty" type="text" id="newqty" size="10" value="'.$row[8].'" readonly>
		  </br>變更日期 ：<input name="chmonth" type="text id="chmonth" size="10" value="'.$row[5].'" readonly>
		  特殊變更要項 ：<input name="remark" type="text" id="remark" size="28" value="'.$row[26].'" readonly>
		  </td></tr><tr><td></td><td><input name="save" type="button" id="save" size="28" value="         編   輯        " onClick="window.open('."'./edit_change.php','_blank',config='height=170,width=1050')".'" /></td></tr>';
	echo '</table></form></br>';

	$sag=new sign_in;

	$sag->sa_no=$sagno;
	$sag->cbt_no='2-09';
	$sag->show();
	

	$query="select STM_OLD_LOT_NO,* from STYLE_MODIFY
			where  STM_OLD_LOT_NO<>'' ";
			
	echo '荷姿變動連絡書 列表<table width="1240" border="1"><tr><td>更改日期</td><td>原客戶</td><td>新客戶</td><td>原LOT</td><td>新LOT</td><td>原品名</td><td>新品名</td><td>原數量</td><td>新數量</td></tr>';
	if($_POST['selected']<>0){$query.="and STM_REASON like '".$_POST['selected']."%'";}

	if($_POST['oldlot']<>'')
	{
		$query.= " and STM_OLD_LOT_NO='".$_POST['oldlot']."'";
	}
	if($_POST['lotno']<>'')
	{
		$query.=" and SM.STM_NEW_LOT_NO='".$_POST['lotno']."'";
	}
		if($_SESSION['datepicker1']<>'' and $_SESSION['datepicker2']<>'')
	{
		$query.=" and STM_DATE>'".dod($_SESSION['datepicker1'])."' and STM_DATE<'".dod($_SESSION['datepicker2'])."'";
	}
	$query.=" order by STM_DATE desc";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	while($row = mssql_fetch_array($result))
	{
		$oldcust=substr($row['STM_REASON'],2,6);
		$newcust=substr($row['STM_REASON'],9,6);
		if(($_SESSION['STM_OLD_LOT_NO']==$row['STM_OLD_LOT_NO']) and ($_SESSION['STM_NEW_LOT_NO']==$row['STM_NEW_LOT_NO'])){$bgcolor=  'bgcolor="yellow"';}else{$bgcolor='';}
		echo '<tr onClick="set_date_session('."'STM_OLD_LOT_NO'".','."'".$row['STM_OLD_LOT_NO']."'".'),set_date_session('."'STM_NEW_LOT_NO'".','."'".$row['STM_NEW_LOT_NO']."'".')" '.$bgcolor.'>';
		echo '<td>'.ddd($row['STM_DATE']).'</td><td>'.$oldcust.'</td><td>'.$newcust.'</td><td>'.$row['STM_OLD_LOT_NO'].'</td><td>'.$row['STM_NEW_LOT_NO'].'</td><td>'.$row['STM_OLD_PROD_NO'].'</td><td>'.$row['PDD_PROD_NO'].'</td><td>'.$row['STM_OLD_QTY'].'</td><td>'.$row['STM_NEW_QTY'].'</td></tr>';
	}
	echo '</table>';

	
function selected()
{
	session_start();
	echo '<select name="selected" id="selected" onChange="set_date_session(this.name,this.value)">';
	echo '<option value="0" ';
	if($_SESSION['selected']=='0'){echo ' selected="selected" ';}
	echo ' ></option>';
	echo '<option value="1" ';
	if($_SESSION['selected']=='1'){echo ' selected="selected" ';}
	echo ' >'."OEM->TYS".'</option>';
	echo '<option value="2" ';
	if($_SESSION['selected']=='2'){echo ' selected="selected" ';}
	echo ' >'."OEM->客戶".'</option>';
	echo '<option value="3" ';
	if($_SESSION['selected']=='3'){echo ' selected="selected" ';}
	echo ' >'."TYS->OEM".'</option>';
	echo '<option value="4" ';
	if($_SESSION['selected']=='4'){echo ' selected="selected" ';}
	echo ' >'."TYS->客戶".'</option>';
	echo '<option value="5" ';
	if($_SESSION['selected']=='5'){echo ' selected="selected" ';}
	echo ' >'."客戶->客戶".'</option>';
	echo '<option value="6" ';
	if($_SESSION['selected']=='6'){echo ' selected="selected" ';}
	echo ' >'."其他".'</option>';
	echo '</select>';
}

if(isset($_POST['new'])){
	echo "<script>
		window.open('add_change.php?oldlotno=".$_POST['oldlot']."&newlotno=".$_POST['lotno']."','_blank',config='height=260,width=1200');
		window.close();
		</script>";	
}
 ?>                 