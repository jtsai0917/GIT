<?php
session_start();
include("../lib/fun.php");
include("../checkuser.php");
include("../connections/conn.php");
datepick();
if($_SESSION['met']==''){$_SESSION['met']='E';}
$path_root=$_SERVER['HTTP_HOST'];
lasturl();
if($_SESSION['pid'])
{
	$query="select * from PRODUCT_DATA where PDD_PROD_NO='".$_SESSION['pid']."'";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	while($row=mssql_fetch_array($result))
	{
		$short_name=$row['PDD_PROD_SHORT_NAME'];
	}
}
$short=substr(trim($short_name),0,2);

$yr=substr(date("Y"),3,1);
$mon=exmonth(date("m"));
$name=$_SESSION['met'].$short.$yr.$mon;
$query="select * from Sample where SMP_ID like '".$name."%' and SUBSTRING(SMP_ID, 8, 1)<>'L'";
//echo $query;
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
if($numRows>0){
	while($row=mssql_fetch_array($result))
	{
		$smpid=$row['SMP_ID'];
	}
	$number=substr($smpid,-3);
	}
	else{
		$number=0;
	}
	$no=sprintf("%03s",$number+1);
	$fname=$_SESSION['met'].$short.$yr.$mon.$no;
?>
<form name="create_samples" method="post" action="<?php $loginFormAction; ?>">
  <table width="600" border="0">
    <tr>
      <td>建立新樣品瓶</td>
    </tr>
  </table>
  <table width="600" border="1">
    <tr>
      <td width="300">起始瓶號：
      <input type="text" name="start_no" id="start_no" value="<?php echo $fname; ?>"/></td>
      <td>總瓶數：
      <input name="count" type="text" id="count" size="6" value="<?php echo $_SESSION['count']; ?>"/>最末碼的流水號不可超過999</td>
    </tr>
    <tr>
      <td>建立時間：<?php echo $now=date("m/d/Y H:i:s"); ?></td>
      <td>月流水號：<?php echo $no; ?></td>
    </tr>
    <tr>
      <td>材質：
        <label for="met"></label>
        <select name="met" id="met" onchange="set_date_session(this.name,this.value)">
          <option value="E" <?php if($_SESSION['met']=='E'){echo "selected";} ?>>PE</option>
          <option value="T" <?php if($_SESSION['met']=='T'){echo "selected";} ?>>PFA</option>
      </select></td>
      <td></td>
    </tr>
  </table>
  <table width="600" border="0">
    <tr>
      <td><input type="button" name="X" id="X" value="X" onclick="window.open('../erase_prod.php ', '_self');" />
        <input name="pdd_chemical1" type="text" id="pdd_chemical1" size="10" value="<?php 
		if ($_GET['pid']){
			echo $_GET['pid'];
			$_SESSION['pid']=$_GET['pid'];
		}
		elseif($_SESSION['pid']){
			echo $_SESSION['pid'];
		}
		else{
		echo '';
		}
		?>" readonly="readonly" />
        <input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onclick="window.open('../pdd_prod_no.php ', '_self');" />
      <input name="pdd_chemical2" type="text" id="pdd_chemical2" size="16" value="<?php 
		if ($_GET['pname']){
			echo $_GET['pname'];
			$_SESSION['pname']=$_GET['pname'];
		}
		elseif($_SESSION['pname']){
			echo $_SESSION['pname'];
		}
		else{
		echo '';
		}
		?>" readonly="readonly" /></td>
    </tr>
 </table>
 <table width="600" border="0">
    <tr>
      <td><input type="submit" name="new" id="new" value="  建立新瓶號  " /></td>
      <td><input type="submit" name="leave" id="leave" value="  離 開  " /></td>
    </tr>
  </table>

<?php
$loginFormAction = $_SERVER['PHP_SELF'];
if($_POST['new'])
{
	$_SESSION['pid']=trim($_POST['pdd_chemical1']);
	$_SESSION['met']=$_POST['met'];
	$_SESSION['start_no']=$_POST['start_no'];
	$_SESSION['count']=$_POST['count'];
	$_SESSION['start_no']=trim($_POST['start_no']);
	$n1=substr($_SESSION['start_no'],0,-3);
	$no=sprintf("%03s",$number+1);
	echo '</br>開始號碼：'.$n1.$no.'</br>';
	echo '結束號碼：'.$n1.sprintf("%03s",$no+$_SESSION['count']-1).'</br>';
	echo '總計：'.$_SESSION['count']." 瓶</br>";
	echo '<input type="submit" name="create" id="create" value="  確定新增以上瓶號  " />';
}

if($_POST['leave'])
{
	jumpto("index.php?url=add_sample");	
}

if($_POST['create'])
{
	for($i=0;$i<$_SESSION['count'];$i++)
	{
		$n1=substr($_SESSION['start_no'],0,-3);
		$smpno=$n1.sprintf("%03s",$no+$i);
		if($_SESSION['met']=='E'){$met="PE";}
		if($_SESSION['met']=='T'){$met="PFA";}
		$query="INSERT INTO [dbo].[Sample] ([SMP_ID], [SMP_MAT], [SMP_MID_ID], [SMP_START], [SMP_TIMES], [SMP_SERVICE], [SMP_LOT], [SMP_DRUMNO], [SMP_USER], [SMP_OUT], [SMP_SMP], [SMP_ANA], [SMP_BACK], [SMP_SAVE], [SMP_SAVE_TIME], [SMP_FINISH], [SMP_JUNK], [SMP_JUNK_D]) VALUES (N'".$smpno."', N'".$met."', N'".$_SESSION['pid']."', N'".date("Ymd")."', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)"; 
//		echo $query;
//		echo "<BR>";
		$result = mssql_query($query);
		
		$query="INSERT INTO dbo.Sample_All
                            (SMA_ID, SMA_TIMES, SMA_SERVICE, SMA_LOT, SMA_USER, SMA_OUT, SMA_SMP, SMA_ANA, SMA_BACK, 
                            SMA_SAVE, SMA_SAVE_TIME, ISREWORK, SMA_FINISH, SMA_JUNK, SMA_JUNK_D, SMA_DRUMNO, 
                            SMA_SERIAL_NO, DHN_DRUM_NO, SMA_PREPSAMPLE_MAN, SMA_PREPSAMPLE_DATE, 
                            SMA_PREPSAMPLE_BACK_DATE, SMA_PREPSAMPLE_BACK_MAN, SMA_RETURN, SMA_RETURN_MAN)
							VALUES          ('".$smpno."',0,0,NULL,
							NULL,NULL,'".$_SESSION['uid']."',NULL,NULL,'".date("Ymd")."','".date("His")."',NULL,
							NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL)";
		$result = mssql_query($query);
//		echo $query;
//		echo "<BR>";
// if (!$result) {print("SQL statement failed with error:\n");print("   ".mssql_get_last_message()."\n");}
	}
//	refresh();
}
	
?>
</form>