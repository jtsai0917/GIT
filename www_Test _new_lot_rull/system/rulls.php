<?php session_start();?>
<p>作業權限基本資料</p>
<form name="form1" method="post" action="<?php echo $loginFormAction; ?>">
<select name="select1" id="select1">
<?php
include("../connections/conn.php");
include("../lib/fun.php");
$query="SELECT         dbo.CAPABILITY_DATA.*
FROM             dbo.CAPABILITY_DATA";
	$result = mssql_query($query);
	while($row = mssql_fetch_array($result)){
?>
  <option value="<?php echo $row['CBT_NO'];?>" <?php if($_SESSION['select1']){if($_SESSION['select1']==$row['CBT_NO']){echo "selected";}} ?>>
  <?php echo $row['CBT_NO']."  ".$row['CBT_NAME'];?>
  </option>
<?php
}
?>
</select>
  <input type="submit" name="submit" id="submit" value="確定">
  <input type="submit" name="save" id="save" value="儲存">
<?php 
showfun();
?>

<?php
$loginFormAction = $_SERVER['PHP_SELF'];

if(isset($_POST["submit"])){
	
	$_SESSION['select1']=$_POST['select1'];
	refresh();
		
	
}

if(isset($_POST["inward"])){
	showdep($_POST['select1'],'inward');
}

if(isset($_POST["keyin"])){
	showdep($_POST['select1'],'keyin');
}

if(isset($_POST["cfm1"])){
	showdep($_POST['select1'],'cfm1');
}

if(isset($_POST["cfm2"])){
	showdep($_POST['select1'],'cfm2');
}

if(isset($_POST["cfm3"])){
	showdep($_POST['select1'],'cfm3');
}

if(isset($_POST["cfm4"])){
	showdep($_POST['select1'],'cfm4');
}

if(isset($_POST["cfm5"])){
	showdep($_POST['select1'],'cfm5');
}

if(isset($_POST["cfm6"])){
	showdep($_POST['select1'],'cfm6');
}

if(isset($_POST["outward"])){
	showdep($_POST['select1'],'outward');
}


if (isset($_POST["save"])){
	showdep($_POST['select1'],'');
	$aa=$_POST['checkbox'];
    $dd=implode(";", $aa);
	$query="UPDATE        dbo.CAPABILITY_DATA
SET                  ".$_POST['column2']." ='".$dd."'
WHERE         (CBT_NO = '".$_POST['cbtno']."')";
$result3=mssql_query($query);
sql_rec($_SERVER['QUERY_STRING'] ,$query);
if($result3){echo my_msg("儲存完畢!");}
}
?>
</form>
<p>&nbsp;</p>

<?php 
function showfun(){
	echo '  <table width="800" border="1">
    <tr><td align="center">
	<input type="submit" name="inward" id="inward" value=" 資料匯入 ">&nbsp;&nbsp;
	<input type="submit" name="keyin" id="keyin" value=" 資料輸入 ">&nbsp;&nbsp;
	<input type="submit" name="cfm1" id="cfm1" value=" 確認單位1 ">&nbsp;&nbsp;
	<input type="submit" name="cfm2" id="cfm2" value=" 確認單位2 ">&nbsp;&nbsp;
	<input type="submit" name="cfm3" id="cfm3" value=" 確認單位3 ">&nbsp;&nbsp;
	<input type="submit" name="cfm4" id="cfm4" value=" 確認單位4 ">&nbsp;&nbsp;
	<input type="submit" name="cfm5" id="cfm5" value=" 確認單位5 ">&nbsp;&nbsp;
	<input type="submit" name="cfm6" id="cfm6" value=" 確認單位6 ">&nbsp;&nbsp;
	<input type="submit" name="outward" id="outward" value=" 資料匯出 ">
	</td></tr></table>';	
	
}


function showdep($cbtno,$col){
	$query="SELECT         ".column($col)."
FROM             dbo.CAPABILITY_DATA where (CBT_NO='".$cbtno."')";
$result=mssql_query($query);
while($row=mssql_fetch_array($result)){
	$ss=$row[column($col)];
}
	echo '<table width="800" border="1" ><tr><td align="center">選擇</td><td>單位</td></tr>';
	$query="SELECT         dbo.AUTHORITY_DATA.*
FROM             dbo.AUTHORITY_DATA order by AUT_NO";
$result=mssql_query($query);
//$aa=explode(';',$ss);
//for($i=0;$i<=count($aa);$i++){
while($row=mssql_fetch_array($result)){
		echo '
		<tr><td align="center"><input type="checkbox" name="checkbox[]" value="'.$row['AUT_NO'].'"'.check1($ss,$row['AUT_NO']).'/></td><td align="left" >'.$row['AUT_NAME'].'</td></tr>';	
		}	
	echo '</table>';
	echo '<input type="hidden" name="cbtno" id="normal" value="'.$cbtno.'" />';
	echo '<input type="hidden" name="column2" id="column2" value="'.column($col).'" />';
echo '<table width="800" border="1"><tr><td align="center"><input type="submit" name="save" id="save" value="     儲存   "></td></tr></table>';

}


function check1($s1,$s2)
{
	$pattern="/".$s2."/i";
	$s1=$s1.";";
	$s2=$s2.";";

	if (preg_match($pattern, $s1)){
	//return $index."==".$str;
	return 'checked="checked"';
	}
}
function column($id){
	switch($id)
	{
		case inward:
		return "CBT_INWARD";
		break;
		case keyin:
		return "CBT_KEYIN";
		break;
		case cfm1:
		return "CBT_CFM1";
		break;
		case cfm2:
		return "CBT_CFM2";
		break;
		case cfm3:
		return "CBT_CFM3";
		break;
		case cfm4:
		return "CBT_CFM4";
		break;
		case cfm5:
		return "CBT_CFM5";
		break;
		case cfm6:
		return "CBT_CFM6";
		break;
		case outward:
		return "CBT_OUTWARD";
		break;
	}
}

?>
