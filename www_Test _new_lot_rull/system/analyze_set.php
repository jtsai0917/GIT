<?php 
	session_start();
	include("../connections/conn.php");
	include("../lib/fun.php");
	include("../lib/jtsai.php");
	auth('9-10',$_SESSION['aut']);
	remurl("analyze_set");
	lasturl();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>setup_lorry</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <table width="1240" border="1">
    <tr>
      <td width="1240">查詢檢驗規則</td>
    </tr>
    <tr>
      <td>客戶：<input type="submit" name="X4" id="X4" value="X" />
        <input name="pdd_chemical6" type="text" id="pdd_chemical7" size="5" value="<?php echo $_SESSION['cust_no'];?>" readonly="readonly" />
        <input type="button" name="pdd_no4" id="pdd_no4" value="查詢" onclick="window.open('../main.php?url=cust_no&amp;sup=N ', '_self');" />
        <input name="pdd_chemical6" type="text" id="pdd_chemical8" size="16" value="<?php echo $_SESSION['cust_name'];?>" />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;品名：
<input type="button" name="X" id="X" value="X" onclick="window.open('../erase_prod.php ', '_self');" />
      <input name="pdd_chemical1" type="text" id="pdd_chemical1" size="5" value="<?php echo $_SESSION['pid']; ?>" readonly="readonly" />
      <input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onclick="window.open('../main.php?url=pdd_prod_no ', '_self');" />
      <input name="pdd_chemical2" type="text" id="pdd_chemical2" size="16" value="<?php echo get_prod_name($_SESSION['pid']);?>" readonly="readonly" />
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;排序方式
      <label for="searchway"></label>
      <select name="searchway" id="searchway">
        <option value="1">料號-正</option>
        <option value="2">料號-反</option>
      </select><input name="search" type="submit" id="search" value="    查詢     " />
      <input name="search2" type="submit" id="search2" value="    查詢(全顯示)     " /></td></tr>
</table></br></br>
<table width="1240" border="1">
<tr><td>新增條件</td></tr>
<tr>
	    <td>
          <p>項目非全檢即為常規，客戶(可多選)：<?php echo substr($_SESSION['myallcust'],0,125)."...";?> </br>
          <input type="submit" name="X3" id="X3" value="X" />
          <input name="pdd_chemical3" type="text" id="pdd_chemical3" size="5" value="<?php echo $_SESSION['myallcust'];?>" readonly="readonly" />
          <input type="submit" name="pdd_no2" id="pdd_no2" value="查詢" onclick="window.open('../main.php?url=cust_no1&amp;sup=N ', '_self');" />
          <input name="pdd_chemical4" type="text" id="pdd_chemical4" size="16" value="<?php echo $_SESSION['cust_name1'];?>" />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 品名：

<input name="pdd_chemical5" type="text" id="pdd_chemical5" size="5" value="<?php echo $_SESSION['pid3']; ?>" readonly="readonly" />
<input type="submit" name="pdd_no3" id="pdd_no3" value="查詢品名" onclick="window.open('../main.php?url=pdd_prod_no3 ', '_self');" />
<input name="pdd_chemical6" type="text" id="pdd_chemical6" size="16" value="<?php echo get_prod_name($_SESSION['pid3']);?>" readonly="readonly" />
          </p>
          <p>全檢：每月首次全檢
            <input type="checkbox" name="Total_monthly_1st" id="Total_monthly_1st" <?php if($_SESSION['Total_monthly_1st']=='on'){echo 'checked="checked"';} ?>/>
            ；
            &nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;頻率：
            <input name="duration1" type="text" id="duration1" size="2" value="<?php echo $_SESSION['duration1'];?>"/>
            次全檢一次 ；&nbsp;&nbsp;&nbsp;&nbsp;
            全部全檢
            <input type="checkbox" name="eachday" id="eachday"<?php if($_SESSION['eachday']=='on'){echo 'checked="checked"';} ?>/>
          ；&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;每日第一個LOT全檢
            <input type="checkbox" name="eachday2" id="eachday2" <?php if($_SESSION['eachday2']=='on'){echo 'checked="checked"';} ?>/>
            <input name="selectitem" type="submit" id="selectitem" value="選擇檢驗項目" />
          </p>
          <p>
            說明：
            <input name="memo" type="text" id="memo" size="80" value="<?php echo $_SESSION['memo'];?>"/>
            <input type="submit" name="save" value="     新增設定     " />
          </p></td>
    </tr> 
  </table>
  </br></br>
<?php 
$editFormAction = $_SERVER['PHP_SELF'];
if(isset($_POST['search'])){
	$_SESSION['pdd_chemical1']=$_POST['pdd_chemical1'];
$_SESSION['pdd_chemical3']=$_POST['pdd_chemical3'];
$_SESSION['pdd_chemical4']=$_POST['pdd_chemical4'];
$_SESSION['pdd_chemical2']=$_POST['pdd_chemical2'];
$_SESSION['searchway']=$_POST['searchway'];
if($_SESSION['pdd_chemical1']){$_POST['pdd_chemical1']=$_SESSION['pdd_chemical1'];}   //pid
if($_SESSION['pdd_chemical3']){$_POST['pdd_chemical3']=$_SESSION['pdd_chemical3'];}   //cid
if($_SESSION['pdd_chemical4']){$_POST['pdd_chemical4']=$_SESSION['pdd_chemical4'];}
if($_SESSION['pdd_chemical2']){$_POST['pdd_chemical2']=$_SESSION['pdd_chemical2'];}
$query="SELECT         dbo.Analyze_Rulls.*
FROM             dbo.Analyze_Rulls where ([index]<>'') ";
if($_SESSION['pdd_chemical1']<>''){$query=$query." AND (PDD_PROD_NO = '".$_SESSION['pdd_chemical1']."')";}
if($_SESSION['cust_no']<>''){$query=$query." AND (CTD_CUST_NO LIKE '%".$_SESSION['cust_no']."%')";}
if($_SESSION['searchway']<>''){
	if($_SESSION['searchway']==1){$way="PDD_PROD_NO";}
	if($_SESSION['searchway']==2){$way="PDD_PROD_NO desc";}
	$query=$query." order by ".$way;}
$_SESSION['tmpani']=$query;

echo '	
  <table border="1" width="1240">
  <tr>
    <td width="30" align="center">編號</td>
	<td width="50" align="center">客戶代號</td>
    <td width="50" align="center">產品代號</td>
	<td width="100" align="center">產品名稱</td>
    <td width="30" align="center">月初</td>
    <td width="30" align="center">1/n</td>
	<td width="30" align="center">全部</td>
	<td width="30" align="center">日首</td>
	<td width="150" align="center">常規</td>
	<td width="150" align="center">全項</td>
	<td width="150" align="center">月首</td>
	<td width="150" align="center">說明</td>
	<td width="290" align="center">編輯</td>
  </tr>';
  $query=$_SESSION['tmpani'];
  $result = mssql_query($query);
while($row = mssql_fetch_array($result)){
	echo '<tr>
    <td>'.$row['index'].'</td>
    <td><a href="showitem.php?items='.$row['CTD_CUST_NO'].'" ,"_blank">'.substr($row['CTD_CUST_NO'],0,10).'</a></td>
    <td>'.$row['PDD_PROD_NO'].'</td>
    <td>'.get_prod_name($row['PDD_PROD_NO']).'</td>
    <td>'.$row['Total_Monthly_1st'].'</td>
	<td>'.$row['Total_Duration_times'].'</td>
	<td>'.$row['eachday'].'</td>
	<td>'.$row['eachday_first'].'</td>
	<td>'.substr($row['Rull_Reg'],0,21).'</td>
	<td>'.substr($row['Rull_Total'],0,21).'</td>
	<td>'.substr($row['Monthly_first'],0,21).'</td>
	<td>'.$row['Memo'].'</td>
	<td>
	<input type="button" name="n'.$row['index'].'" value="編輯" onClick="'."window.open('./index.php?url=edit_ani&id=".$row['index']." ', '_self');".'"/>
	<input type="button" name="n'.$row['index'].'" value="刪除" onClick="'."window.open('./index.php?url=delete&id=".$row['index']."', '_self');".'"/>
	<input type="button" name="n'.$row['index'].'" value="常規" onClick="'."window.open('./index.php?url=session_tmp&ani_type=ani_reg&id=".$row['index']."&item=".$row['Rull_Reg']."&pid=".$row['PDD_PROD_NO']." ', '_self');".'"/>
	<input type="button" name="n'.$row['index'].'" value="全項" onClick="'."window.open('./index.php?url=session_tmp&ani_type=ani_all&id=".$row['index']."&item=".$row['Rull_Total']."&pid=".$row['PDD_PROD_NO']." ', '_self');".'"/>
	<input type="button" name="n'.$row['index'].'" value="月初" onClick="'."window.open('./index.php?url=session_tmp&ani_type=ani_m_fst&id=".$row['index']."&item=".$row['Monthly_first']."&pid=".$row['PDD_PROD_NO']." ', '_self');".'"/>
	</td>
  </tr>';}
  echo '</table></br>';
}

if(isset($_POST['search2'])){
	$_SESSION['pdd_chemical1']=$_POST['pdd_chemical1'];
$_SESSION['pdd_chemical3']=$_POST['pdd_chemical3'];
$_SESSION['pdd_chemical4']=$_POST['pdd_chemical4'];
$_SESSION['pdd_chemical2']=$_POST['pdd_chemical2'];
$_SESSION['searchway']=$_POST['searchway'];
if($_SESSION['pdd_chemical1']){$_POST['pdd_chemical1']=$_SESSION['pdd_chemical1'];}   //pid
if($_SESSION['pdd_chemical3']){$_POST['pdd_chemical3']=$_SESSION['pdd_chemical3'];}   //cid
if($_SESSION['pdd_chemical4']){$_POST['pdd_chemical4']=$_SESSION['pdd_chemical4'];}
if($_SESSION['pdd_chemical2']){$_POST['pdd_chemical2']=$_SESSION['pdd_chemical2'];}
$query="SELECT         dbo.Analyze_Rulls.*
FROM             dbo.Analyze_Rulls where ([index]<>'') ";
if($_SESSION['pdd_chemical1']<>''){$query=$query." AND (PDD_PROD_NO = '".$_SESSION['pdd_chemical1']."')";}
if($_SESSION['cust_no']<>''){$query=$query." AND (CTD_CUST_NO LIKE '%".$_SESSION['cust_no']."%')";}
if($_SESSION['searchway']<>''){
	if($_SESSION['searchway']==1){$way="PDD_PROD_NO";}
	if($_SESSION['searchway']==2){$way="PDD_PROD_NO desc";}
	$query=$query." order by ".$way;}
$_SESSION['tmpani']=$query;

echo '	
  <table border="1" width="1240">
  <tr>
    <td width="30" align="center">編號</td>
	<td width="50" align="center">客戶代號</td>
    <td width="50" align="center">產品代號</td>
	<td width="100" align="center">產品名稱</td>
    <td width="30" align="center">月初</td>
    <td width="30" align="center">1/n</td>
	<td width="30" align="center">全部</td>
	<td width="30" align="center">日首</td>
	<td width="150" align="center">常規</td>
	<td width="150" align="center">全項</td>
	<td width="150" align="center">月首</td>
	<td width="150" align="center">說明</td>
	<td width="290" align="center">編輯</td>
  </tr>';
  $query=$_SESSION['tmpani'];
  $result = mssql_query($query);
while($row = mssql_fetch_array($result)){
	echo '<tr>
    <td>'.$row['index'].'</td>
    <td><a href="showitem.php?items='.$row['CTD_CUST_NO'].'" ,"_blank">'.substr($row['CTD_CUST_NO'],0,10).'</a></td>
    <td>'.$row['PDD_PROD_NO'].'</td>
    <td>'.get_prod_name($row['PDD_PROD_NO']).'</td>
    <td>'.$row['Total_Monthly_1st'].'</td>
	<td>'.$row['Total_Duration_times'].'</td>
	<td>'.$row['eachday'].'</td>
	<td>'.$row['eachday_first'].'</td>
	<td>'.$row['Rull_Reg'].'</td>
	<td>'.$row['Rull_Total'].'</td>
	<td>'.$row['Monthly_first'].'</td>
	<td>'.$row['Memo'].'</td>
	<td>
	<input type="button" name="n'.$row['index'].'" value="編輯" onClick="'."window.open('./index.php?url=edit_ani&id=".$row['index']." ', '_self');".'"/>
	<input type="button" name="n'.$row['index'].'" value="刪除" onClick="'."window.open('./index.php?url=delete&id=".$row['index']."', '_self');".'"/>
	<input type="button" name="n'.$row['index'].'" value="常規" onClick="'."window.open('./index.php?url=session_tmp&ani_type=ani_reg&id=".$row['index']."&item=".$row['Rull_Reg']."&pid=".$row['PDD_PROD_NO']." ', '_self');".'"/>
	<input type="button" name="n'.$row['index'].'" value="全項" onClick="'."window.open('./index.php?url=session_tmp&ani_type=ani_all&id=".$row['index']."&item=".$row['Rull_Total']."&pid=".$row['PDD_PROD_NO']." ', '_self');".'"/>
	<input type="button" name="n'.$row['index'].'" value="月初" onClick="'."window.open('./index.php?url=session_tmp&ani_type=ani_m_fst&id=".$row['index']."&item=".$row['Monthly_first']."&pid=".$row['PDD_PROD_NO']." ', '_self');".'"/>
	</td>
  </tr>';}
  echo '</table></br>';
}

if(isset($_POST["save"]))
{
if($_POST['duration1']==''){$_POST['duration1']='NULL';}
$query="INSERT INTO dbo.Analyze_Rulls
                          (CTD_CUST_NO, Total_Monthly_1st, Total_Duration_times, 
                          Creat_dt, Creator, Memo, PDD_PROD_NO, eachday, eachday_first, Rull_Total, Rull_Reg)
		VALUES         ('".$_SESSION['myallcust']."','".cx($_POST['Total_monthly_1st'])."',".$_POST['duration1'].",'".date("Ymdhis")."','".$_SESSION['uid']."','".$_POST['memo']."',
		'".$_POST['pdd_chemical5']."','".cx($_POST['eachday'])."','".cx($_POST['eachday2'])."','".$_SESSION['ani_all']."','".$_SESSION['ani_regular']."')";

$result = mssql_query($query);
sql_rec($_SERVER['QUERY_STRING'] ,$query);
$_SESSION['pdd_chemical1']=$_POST['pdd_chemical1'];
$_SESSION['pdd_chemical3']=$_POST['pdd_chemical3'];
$_SESSION['pdd_chemical4']=$_POST['pdd_chemical4'];
$_SESSION['pdd_chemical2']=$_POST['pdd_chemical2'];
//refresh();
}


if(isset($_POST["X3"])){
	$_SESSION['myallcust']=NULL;
	refresh();
}

if(isset($_POST["X4"])){
	$_SESSION['cust_no']=NULL;
	$_SESSION['cust_name']=NULL;
	refresh();
}


if(isset($_POST["selectitem"]))
{
	$url="index.php?url=ani_total&pid=".$_POST['pdd_chemical5'];
	jumpto($url);
}

if(isset($_POST['pdd_no3'])){
	$url="../main.php?url=pdd_prod_no3" ;
	$_SESSION['myallcust']=$_POST['pdd_chemical3'];
	$_SESSION['pdd_chemical4']=$_POST['pdd_chemical4'];
	$_SESSION['pid3']=$_POST['pdd_chemical5'];
	$_SESSION['pdd_chemical6']=$_POST['pdd_chemical6'];
	$_SESSION['Total_monthly_1st']=$_POST['Total_monthly_1st'];
	$_SESSION['duration1']=$_POST['duration1'];
	$_SESSION['eachday']=$_POST['eachday'];
	$_SESSION['eachday2']=$_POST['eachday2'];
	$_SESSION['memo']=$_POST['memo'];
	jumpto($url);
}
if(isset($_POST['pdd_no2']))
{
	$url="../main.php?url=cust_no1&sup=N" ;
	$_SESSION['myallcust']=$_POST['pdd_chemical3'];
	$_SESSION['pdd_chemical4']=$_POST['pdd_chemical4'];
	$_SESSION['pid3']=$_POST['pdd_chemical5'];
	$_SESSION['pdd_chemical6']=$_POST['pdd_chemical6'];
	$_SESSION['Total_monthly_1st']=$_POST['Total_monthly_1st'];
	$_SESSION['duration1']=$_POST['duration1'];
	$_SESSION['eachday']=$_POST['eachday'];
	$_SESSION['eachday2']=$_POST['eachday2'];
	$_SESSION['memo']=$_POST['memo'];
	jumpto($url);
}


?>
</form>
</body>
</html>
<?php
function cx($c){
	if($c=='on'){return 'Y';}
	else{return 'N';}
}
?>
