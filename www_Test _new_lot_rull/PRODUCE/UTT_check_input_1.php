<?php 
session_start();
include("../lib/fun.php");
include("../checkuser.php");
include("../lib/jtsai.php");
include("../connections/conn.php");
lasturl();
datepick();
?>
<style type="text/css">
body,td,th {
	font-size:50px;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
</style>
<form name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <label for="textfield"></label>

 		<font size="+3"><strong>巡檢表單：<span class="d1">
        <?php
		$query="select Form from UTT_FORM_DATA where [index]='".$_SESSION['form']."'";
		$result=mssql_query($query);
		$row=mssql_fetch_array($result);
		echo trim($row[0]);
		?>
        <br>
        <br>
 		巡檢日期: 
        <?php
		echo std($_SESSION['date']);
		?>
        <br>
        <br>
        巡檢時間:
		<?php
		echo $_SESSION['UTT_time'];
		?>
        <br>
        <br>
        巡檢項目:
        <?php
		$query="SELECT project from UTT_TAGNO_DATA WHERE TAG_NO='".trim($_SESSION['TAG_NO'][$_SESSION['i']])."'";
		$result=mssql_query($query);
		$row=mssql_fetch_array($result);
		echo trim($row[0]).' '.$_SESSION['TAG_NO'][$_SESSION['i']];
		?>
        <br>
        <br>
        數值:
        <?php
		$query="SELECT data from UTT_CHECK_DATA WHERE TAG_NO='".trim($_SESSION['TAG_NO'][$_SESSION['i']])."' and date='".$_SESSION['date'].$_SESSION['UTT_time']."'";
		$result=mssql_query($query);
		$row=mssql_fetch_array($result);
		$date=trim($row['data']);
		?>
        <input type="tel" step="0.001" name="data" id="data" size="6" style="font-size:50px"  value="<?php echo $date; ?>" autofocus="autofocus" bind-hasFocus="true">
       <br><br>
        <input type="submit" name="ins" id="ins" value="新增" style="font-size:100px">
        <input type="submit" name="backup" id="backup" value="上一個項目" style="font-size:100px">
        <input  type="button" name="back" id="back" style="font-size:100px" value="返回上一頁" onclick="location.href='/PRODUCE/UTT_check_input.php'">
        <input type="hidden" name="mm_insert" id="mm_insert" value="form1" >

        </span></strong></font>
</form>
<?php
$max=count($_SESSION['TAG_NO']);
$loginFormAction = $_SERVER['PHP_SELF'];
$_SESSION['lasturl']=$_SERVER['REQUEST_URI'];
if(isset($_POST['backup']))
{
	$_SESSION['i']=$_SESSION['i']-1;
	if($_SESSION['i']<0){$_SESSION['i']=0;my_msg('已至頂端');}
	echo "<script>history.go(-1)</script>";
}
if(isset($_POST['ins']))
{
	$TAG=trim($_SESSION['TAG_NO'][$_SESSION['i']]);
	//if($_POST['data']==''){my_msg(請輸入數值);}
	$_SESSION['time']=substr($_SESSION['UTT_time'],0,2);
	$_SESSION['time1']=substr($_SESSION['UTT_time'],2,2);
	$query1="select max(sn) from UTT_CHECK_DATA WHERE TAG_NO='".$_SESSION['TAG_NO'][$_SESSION['i']]."'and date like '%".$_SESSION['date']."%'";
	$result1=mssql_query($query1);
	$row1=mssql_fetch_array($result1);
	$sn=trim($row1[0]);
	$sn=$sn+1;
	$query="select * from UTT_TAGNO_DATA WHERE TAG_NO='".$_SESSION['TAG_NO'][$_SESSION['i']]."' and (high<>'' or low<>'')";
	$result=mssql_query($query);
	$numrows=mssql_num_rows($result);
	$row=mssql_fetch_array($result);
	$query1="select * from UTT_record_spec where TAG_NO='".trim($_SESSION['TAG_NO'][$_SESSION['i']])."' and Date='".trim($_SESSION['date']).$_SESSION['UTT_time']."'";
	$result1=mssql_query($query1);
	$numrows1=mssql_num_rows($result1);
	if($numrows!=0)
	{	
		$data=trim($_POST['data']);
		if(trim($row['mode'])=='GT'){$Num=trim($row['high']);$sign=1;}
		elseif(trim($row['mode'])=='LS'){$Num=trim($row['low']);$sign=2;}
		elseif(trim($row['mode'])=='BT'){$Num=trim($row['high']);$Num1=trim($row['low']);$sign=3;}
		elseif(trim($row['mode'])=='SAME'){$Num=trim($row['high']);$sign=4;}
		if($sign==1 or $sign==2 or $sign==4)
		{
			if($sing==1)
			{
				if($data>$Num){$check='O';}else{$check='X';}
			}
			elseif($sign=2)
			{
				if($data<$Num){$check='O';}else{$check='X';}
			}
			else
			{
				if($data==$Num){$check='O';}else{$check='X';}
			}
		}
		else
		{
			if($data>$Num1 and $data<$Num){$check='O';}else{$check='X';}
		}
		if($numrows1==''){
		$query="insert into UTT_record_spec (TAG_NO, Data, Date, sn, pass, savetime, saveuid) values ('".trim($_SESSION['TAG_NO'][$_SESSION['i']])."','".trim($_POST['data'])."','".trim($_SESSION['date']).$_SESSION['UTT_time']."',".$sn.",'".$check."','".date('Ymdhis')."','".$_SESSION['uid']."')";
		}
		else{
		$query="update UTT_record_spec set Data='".trim($_POST['data'])."' where TAG_NO='".trim($_SESSION['TAG_NO'][$_SESSION['i']])."' and Date='".trim($_SESSION['date']).$_SESSION['UTT_time']."'";
		}
		//echo $query;break;
		$result=mssql_query($query);
	}
	else
	{
		$query1="select * from UTT_record_spec where TAG_NO='".trim($_SESSION['TAG_NO'][$_SESSION['i']])."' and Date='".trim($_SESSION['date']).$_SESSION['UTT_time']."'";
		$result1=mssql_query($query1);
		$numrows1=mssql_num_rows($result1);
		if($numrows1==''){
		$query="insert into UTT_record_spec (TAG_NO, Data, Date, sn, pass, savetime, saveuid) values ('".trim($_SESSION['TAG_NO'][$_SESSION['i']])."','".trim($_POST['data'])."','".trim($_SESSION['date']).$_SESSION['UTT_time']."',".$sn.",'△','".date('Ymdhis')."','".$_SESSION['uid']."')";
			//echo $query;
		}
		else
		{
			$query="update UTT_record_spec set Data='".trim($_POST['data'])."' where TAG_NO='".trim($_SESSION['TAG_NO'][$_SESSION['i']])."' and Date='".trim($_SESSION['date']).$_SESSION['UTT_time']."'";
		}
			$result=mssql_query($query);
	}
	
	if($numrows1==0){
	$query="insert into UTT_CHECK_DATA (TAG_NO ,Data ,sn ,date ,savetime, saveuid) values ('".trim($_SESSION['TAG_NO'][$_SESSION['i']])."','".trim($_POST['data'])."','".$sn."','".trim($_SESSION['date']).$_SESSION['UTT_time']."','".date('Ymdhis')."','".$_SESSION['uid']."')";}
	else{
	$query="update UTT_CHECK_DATA set Data='".trim($_POST['data'])."' where TAG_NO='".trim($_SESSION['TAG_NO'][$_SESSION['i']])."' and date='".trim($_SESSION['date']).$_SESSION['UTT_time']."'";
	}
	$result=mssql_query($query);
		//fun_alert(儲存完畢);
	$_SESSION['i']++;
	if($_SESSION['i']==$max or $TAG=='')
	{
		
		my_msg('巡檢完成!','/PRODUCE/UTT_check_input.php');
	}
	else{
	refresh();}
}
?>
</table>
</br>