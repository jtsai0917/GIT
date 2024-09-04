<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
remurl("edit_change");
datepick();
	$query="select * from STYLE_MODIFY
			where  STM_OLD_LOT_NO='".$_SESSION['STM_OLD_LOT_NO']."' and STM_NEW_LOT_NO='".$_SESSION['STM_NEW_LOT_NO']."'";
	$result = mssql_query($query);
	$row = mssql_fetch_array($result);
	$oldcust=substr($row[6],2,6);
	if($_SESSION['cid1']==''){$_SESSION['cid1']=$oldcust;}
	$newcust=substr($row[6],9,6);
	if($_SESSION['cid2']==''){$_SESSION['cid2']=$newcust;}
	if($_SESSION['pid1']==''){$_SESSION['pid1']=$row[4];}
	if($_SESSION['pid2']==''){$_SESSION['pid2']=$row[3];}

	echo '<form id="form2" name="form2" method="post" action="'.$loginFormAction.'">';
	echo '荷姿變動連絡書 修改';
	echo '<table width="1024" height="120" bgcolor="#CCCCCC"><tr>';
	echo '<td>原 客 戶：
			<input name="cid1" type="text" id="cid1" size="16" value="'.$_SESSION['cid1'].'" readonly>
			<input type="button" name="cidb1" id="cidb1" value="查詢客戶" onClick="window.open('."'cust_no1.php?sup=N ', '_blank',config='height=600,width=400'".'),window.close();">
			<input name="cname1" type="text" id="cname1" size="20" value="'.get_cust_name($_SESSION['cid1']).'">
			</br>原藥品名:
			<input name="pid1" type="text" id="pid1" size="16" value="'.$_SESSION['pid1'].'" readonly>
			<input type="button" name="pidb1" id="pdd_no" value="查詢品名" onClick="window.open('."'pdd_prod_no1.php ', '_blank',config='height=600,width=400'".'),window.close();" >
          <input name="pdd_chemical2" type="text" id="pdd_chemical2" size="20" value="'.get_prod_name($_SESSION['pid1']).'" readonly>
		  </br>原LOT NO ：<input name="oldlotno" type="text" id="oldlotno" size="14" value="'.$row[0].'" >
		  &nbsp;&nbsp;新LOT NO ：<input name="newlotno" type="text" id="newlotno" size="14" value="'.$row[2].'">
		  </br>有效月數 ：<input name="remonth" type="text" id="remonth" size="6" value="'.$row[9].'">
		  殘存月數 ：<input name="leftmonth" type="text" id="leftmonth" size="6" value="'.$row[10].'">
		  </td>';
	echo '<td>新 客 戶：
          <input name="cid1" type="text" id="cid1" size="16" value="'.$_SESSION['cid2'].'" readonly>
          <input type="button" name="cidb2" id="cidb2" value="查詢客戶" onClick="window.open('."'cust_no2.php?sup=N ', '_blank',config='height=600,width=400'".'),window.close();">
          <input name="cname1" type="text" id="cname1" size="20" value="'.get_cust_name($_SESSION['cid2']).'">
		  </br>新藥品名:
          <input name="pid2" type="text" id="pid2" size="16" value="'.$_SESSION['pid2'].'" readonly>
          <input type="button" name="pidb2" id="pdd_no" value="查詢品名" onClick="window.open('."'pdd_prod_no2.php', '_blank',config='height=600,width=400'".'),window.close();" >
          <input name="pdd_chemical2" type="text" id="pdd_chemical2" size="20" value="'.get_prod_name($_SESSION['pid2']).'" readonly>
		  </br>原數量 ：<input name="oldqty" type="text" id="oldqty" size="10" value="'.$row[7].'">
		  &nbsp;&nbsp;新數量 ：<input name="newqty" type="text" id="newqty" size="10" value="'.$row[8].'" >
		  </br>變更日期 ：<input name="chmonth" type="text id="chmonth" size="10" value="'.$row[5].'" readonly>
		  特殊變更要項 ：<input name="remark" type="text" id="remark" size="28" value="'.$row[26].'" >
		  </td></tr><tr><td></td><td><input name="save" type="submit" id="save" size="28" value="         儲    存        " /></td></tr>';
	echo '</table></form>';

if(isset($_POST['save']))
{
	echo 'SSSS';
}
?>