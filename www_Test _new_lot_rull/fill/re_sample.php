<?php
	session_start();
	$_SESSION['lasturl']=$_SERVER['REQUEST_URI'];
	include("../connections/conn.php");
	include("../lib/fun.php");
	include("../lib/jtsai.php");
	include("../lib/style.php");
	datepick();
?>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
  <title>jQuery UI Datepicker - Default functionality</title>


<form id="form1" name="form1" method="post" action="<?php $loginFormAction; ?>">
  <table width="1238" border="1">
    <tr bgcolor="#CCCCCC" >
      <td>再取樣列表</td>
    </tr>
    <tr>
      <td>送樣時間 : 
        <input name="datepicker1" type="text" id="datepicker1" size="14" value="<?php 
		if($_SESSION['datepicker1']){
			echo $_SESSION['datepicker1'];
		}
		else{
		$d=strtotime("-0 Days"); echo date("m/d/Y",$d);
		}
		?>">
~
  <input name="datepicker2" type="text" id="datepicker2" size="14" value="<?php 
		if($_SESSION['datepicker2']){
			echo $_SESSION['datepicker2'];
		}
		else{
		$d=strtotime("+0 Days"); echo date("m/d/Y",$d);
		}
		?>">
        &nbsp;&nbsp;&nbsp;&nbsp;
        Lot No: 
        <label for="lot_no"></label>
        <input type="text" name="lot_no" id="lot_no">
        &nbsp;&nbsp;&nbsp;&nbsp;
        品名：<span class="d1">
        <input type="button" name="X" id="X" value="X" onClick="window.open('../erase_prod.php ', '_self');">
        <input name="pdd_chemical1" type="text" id="pdd_chemical1" size="10" value="<?php
			echo $_SESSION['pid'];
		?>" readonly>
        <input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onClick="window.open('../pdd_prod_no.php ', '_self');" >
        <input name="pdd_chemical2" type="text" id="pdd_chemical2" size="16" value="<?php if ($_GET['pname']){
			echo $_GET['pname'];
			$_SESSION['pname']=$_GET['pname'];
		}
		elseif($_SESSION['pname']){
			echo $_SESSION['pname'];
		}
		else{
		echo '';
		}
		?>" readonly>
        </span>  
        &nbsp;&nbsp;&nbsp;&nbsp;
        <input type="submit" name="search" id="search" value=" 查尋 ">
        &nbsp;&nbsp;&nbsp;&nbsp;       
        <label for="ck0"></label></td>
    </tr>
  </table>
  <table width="1238" border="1">
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
if ($_POST['search'])
{
$query="SELECT         dbo.REANALYZE_REQUEST_DATA.*
		FROM             dbo.REANALYZE_REQUEST_DATA 
		WHERE         (RAN_REQ_TIME<>'') ";
	if($_POST['lot_no']<>''){$query.=" AND (RAN_LOT_NO Like '%".$_POST['lot_no']."%')";}
	if(($_POST['lot_no']=='') and ($_POST['datepicker1']<>'')){$query.=" AND (RAN_REQ_TIME>'".dod($_POST['datepicker1'])."000000')";}
	if(($_POST['lot_no']=='') and ($_POST['datepicker2']<>'')){$query.=" AND (RAN_REQ_TIME<'".dod($_POST['datepicker2'])."235959')";}
	if(($_POST['lot_no']=='') and ($_POST['pdd_chemical1']<>'')){$query.=" AND (RAN_PROD_NO='".$_POST['pdd_chemical1']."')";}	
	$_SESSION['datepicker1']=$_POST['datepicker1'];
	$_SESSION['datepicker2']=$_POST['datepicker2'];
	$_SESSION['pid']=$_POST['pdd_chemical1'];
	echo '<tr align="center" bgcolor="#CCCCCC">';
	echo '<td width="100">Lot No</td><td width="70">產品</td><td width="30">次數</td><td width="40">分析師</td><td width="310">不合格項目</td><td width="120">
	預估送樣時間</td><td width="120">說明</td><td width="100">取樣作業</td>';
	echo '</tr>';
	$_SESSION['TMP']=$query;
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	while($row=mssql_fetch_array($result))
	{
		if($row['RAN_REQ_TIME']){$rnt=ddt($row['RAN_REQ_TIME']);}
		echo '<tr><td>'.$row['RAN_LOT_NO'].'</td><td>'.$row['RAN_PROD_NO'].'</td><td>'."再".$row['RAN_TIMES'].'</td><td>'.get_uname($row['RAN_TESTER']).'</td><td>'.
		ana_id_to_nick($row['RAN_FAIL_ITEM']).'</td><td>'.$rnt.'</td><td>'.$row['Note'].'</td><td>';
		echo '<input type="button" value=" 樣品瓶作業 " name="ran_no'.$row['RAN_NO'].'" onClick="window.open('."'./index.php?url=re_sample&ani_group=".$row['RAS_GROUPNAME']."&ran_times=".$row['RAN_TIMES']."&ranid=".$row['RAN_NO']."&pid=".$row['RAN_PROD_NO']."&lot_no=".$row['RAN_LOT_NO']."', '_self');".'"'.' />';
		echo '</td></tr>';
	}
}
echo '</table></form>';
if (($_GET['ranid']) and !($_POST['search']))
{
	$cid=ran_sample($_GET['ranid'],$_GET['lot_no']);
}

if (($_POST['add']) and (!$_POST['ran_sample']))
{
//	$_SESSION['tmp']=chk_smp_pid($_POST['sam_no'],$_GET['pid']);
	if(chk_smp_pid($_POST['sam_no'],$_GET['pid'])==1)
	{
	$now=date("YmdHis");
	$query="INSERT INTO dbo.REANALYZE_SAMPLE_LIST
                          (RAS_NO, RAS_LOT_NO, RAS_REQ_TIME, RAS_SAM_TIME, 
                          RAS_SAM_PERSON, RAS_SAM_SOURCE, RAS_GROUPNAME, RAS_SAM_NO)
			VALUES         (".$_GET['ranid'].",'".$_GET['lot_no']."','".date("YmdHis")."','".date("YmdHis")."','".$_SESSION['uid']."','".$_POST['source']."','".$_GET['ani_group']."','".$_POST['sam_no']."')";
	$result1 = mssql_query($query);
	$ss=new sample;
	$ss->smp_no=$_POST['sam_no'];
	$ss->get_from_sample_id();
	$smatimes=($ss->sma_time)+1;
	$query="INSERT INTO [CHEMICAL].[dbo].[Sample_All]
           ([SMA_ID]
           ,[SMA_TIMES]
           ,[SMA_SERVICE]
           ,[SMA_LOT]
           ,[SMA_USER]
           ,[SMA_SMP]
           ,[ISREWORK]
           ,[SMA_DRUMNO]
           ,[SMA_SERIAL_NO]        
           )
     VALUES
           ('".$_POST['sam_no']."'
           ,".$smatimes."
           ,'".$_POST['smp_type']."'
           ,'".$_GET['lot_no']."'
           ,'".$cid."'
           ,'".$_SESSION['uid']."'
           ,'".$_GET['ran_times']."'
           ,'1~8'
		   ,1)";	

		$result2 = mssql_query($query);
	$query="update Sample set SMP_TIMES=".$smatimes."
			, [SMP_SERVICE]='".$_POST['smp_type']."'
			, [SMP_LOT]='".$_GET['lot_no']."'
			, [SMP_DRUMNO]='1~8'
			, [SMP_USER]='".$cid."'
			, [SMP_SMP]='".$_SESSION['uid']."' 
			where (SMP_ID='".$_POST['sam_no']."')";
	$result3 = mssql_query($query);
	}
	else{my_msg("Failure")  ;}
}

?>
  
