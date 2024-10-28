<?php 
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
$jj=new get_from_lot_no;
$jj->lid=$_GET['lid'];
$jj->ani();
echo $pid=trim($jj->pid);
$query="SELECT TOP (1) [index], Lot_No, flow, creator, [date], discription, pressure FROM Fill_Flow_Chart WHERE (Lot_No = N'".$_GET['lid']."') ORDER BY   [index] DESC";
$result=mssql_query($query);
$row=mssql_fetch_row($result);
if($row[6]<>''){
	echo '<BR>記錄站點為：'.$row[2];
	echo '<BR>紀錄現值為：'.$row[6];
}
else{echo '無紀錄';}
echo '<form name="form1" method="post" action="'.$loginFormAction.'">';
echo '<font size="+2">設定 '.$_GET['lot_no'].' 充填流程</font>';
if($_GET['id']==2){echo '<input type="submit" name="set_trans" value="設定相關參數">';}
	echo '<BR>選擇充填路徑<table width="420" border="1" bgcolor="#CCCCCC"><tr><td width="50">系別</td><td width="50">TANK</td><td width="50">Pump</td></tr><tr><td>';
echo '<tr><td>';
select_series($pid);
	echo '</td><td>';
	select_Tank(trim($jj->pid));
	echo '</td><td>';
	select_Pump($pid);
	echo '<br><input type="text" size="5" name="p0">kg/cm^2';
	echo '</td></tr><tr>';
	echo '<td width="100">Filter 1</td><td width="100">Filter 2</td><td>充填站</td></tr><td>';
	select_Filter1($pid);
	echo '<br><input type="text" size="5" name="f1">kg/cm^2';
	echo '</td><td>';
	select_Filter2($pid);
	echo '<br><input type="text" size="5" name="f2">kg/cm^2';
	echo '</td><td>';
	select_Spot($pid);
	echo '</td></table>';	
echo '<table width="420" border="1" bgcolor="#CCCCCC"><tr><td>';
echo '<input type="submit" name="save1" value="    選擇完畢   " />';
echo '</td></tr></table>';

function select_series($pid){
	echo '<select name="select_series" id="select_series" >';
	echo '<option value=""></option>';
	$query="SELECT series FROM FILL_Series WHERE (Prod_No = '".$pid."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		if(trim($row['series'])==$_SESSION['select_series']){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['series'].'" '.$select.'>'.$row['series'].'</option>';
	}
	echo '</select>';
}

function select_Tank($pid){
	echo '<select name="select_Tank" id="select_Tank">';
	echo '<option value=""></option>';
	$query="SELECT PROD_TANK FROM TANK_DATA1 WHERE (PDD_PROD_NO = '".$pid."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		if(trim($row['PROD_TANK'])==$_SESSION['select_Tank']){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['PROD_TANK'].'" '.$select.'>'.$row['PROD_TANK'].'</option>';
	}
	echo '</select>';
}

function select_Tank1($pid){
	echo '<select name="select_Tank1" id="select_Tank1">';
	echo '<option value=""></option>';
	$query="SELECT PROD_TANK FROM TANK_DATA1 WHERE (PDD_PROD_NO = '".$pid."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		if(trim($row['PROD_TANK'])==$_SESSION['select_Tank1']){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['PROD_TANK'].'" '.$select.'>'.$row['PROD_TANK'].'</option>';
	}
	echo '</select>';
}

function select_Pump($pid){
	echo '<select name="select_Pump" id="select_Pump">';
	echo '<option value=""></option>';
	$query="SELECT PumpNo FROM Fill_Pump WHERE (Prod_No = '".$pid."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		if(trim($row['PumpNo'])==$_SESSION['select_Pump']){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['PumpNo'].'" '.$select.'>'.$row['PumpNo'].'</option>';
	}
	echo '</select>';
}

function select_Pump1($pid){
	echo '<select name="select_Pump1" id="select_Pump1">';
	echo '<option value=""></option>';
	$query="SELECT PumpNo FROM Fill_Pump WHERE (Prod_No = '".$pid."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		if(trim($row['PumpNo'])==$_SESSION['select_Pump1']){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['PumpNo'].'" '.$select.'>'.$row['PumpNo'].'</option>';
	}
	echo '</select>';
}

function select_Filter1($pid){
	echo '<select name="select_Filter1" id="select_Filter1">';
	echo '<option value=""></option>';
	$query="SELECT filter FROM Fill_Filter WHERE (Prod_No = '".$pid."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		if(trim($row['filter'])==$_SESSION['select_Filter1']){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['filter'].'" '.$select.'>'.$row['filter'].'</option>';
	}
	echo '</select>';
}

function select_Filter2($pid){
	echo '<select name="select_Filter2" id="select_Filter2">';
	echo '<option value=""></option>';
	$query="SELECT filter FROM Fill_Filter WHERE (Prod_No = '".$pid."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		if(trim($row['filter'])==$_SESSION['select_Filter2']){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['filter'].'" '.$select.'>'.$row['filter'].'</option>';
	}
	echo '</select>';
}

function select_Filter3($pid){
	echo '<select name="select_Filter3" id="select_Filter3">';
	echo '<option value=""></option>';
	$query="SELECT filter FROM Fill_Filter WHERE (Prod_No = '".$pid."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		if(trim($row['filter'])==$_SESSION['select_Filter3']){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['filter'].'" '.$select.'>'.$row['filter'].'</option>';
	}
	echo '</select>';
}

function select_Spot($pid){
	echo '<select name="select_Spot" id="select_Spot">';
	echo '<option value=""></option>';
	$query="SELECT SpotNo FROM Fill_Spot WHERE (Prod_No = '".$pid."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		if(trim($row['SpotNo'])==$_SESSION['select_Spot']){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['SpotNo'].'" '.$select.'>'.$row['SpotNo'].'</option>';
	}
	echo '</select>';
}


if(isset($_POST['save1'])){
	$jj=new get_from_lot_no;
	$jj->lid=$_GET['lot_no'];
	$jj->ani();
	$pid=trim($jj->pid);
	$cid=trim($jj->cid);
	if($_POST['select_series']<>'')
	{$flow.=trim($_POST['select_series'])."/";}
	if($_POST['select_Tank']<>'')
	{$flow.=trim($_POST['select_Tank'])."/";}
	if($_POST['select_Pump']<>'')
	{$flow.=trim($_POST['select_Pump'])."/";}
	if($_POST['select_Tank1']<>'')
	{$flow.=trim($_POST['select_Tank1'])."/";}
	if($_POST['select_Pump1']<>'')
	{$flow.=trim($_POST['select_Pump1'])."/";}
	if($_POST['select_Filter1']<>'')
	{$flow.=trim($_POST['select_Filter1'])."/";}
	if($_POST['select_Filter2']<>'')
	{$flow.=trim($_POST['select_Filter2'])."/";}
	if($_POST['select_Filter3']<>'')
	{$flow.=trim($_POST['select_Filter3'])."/";}
	if($_POST['select_Spot']<>'')
	{$flow.=trim($_POST['select_Spot'])."/";}
	
	
	if($_POST['select_series']<>'')
	{$pressure.="NA/";}
	if($_POST['select_Tank']<>'')
	{$pressure.="NA/";}
	if($_POST['select_Pump']<>'')
	{$pressure.=trim($_POST['p0'])."/";}
	if($_POST['select_Tank1']<>'')
	{$pressure.="NA/";}
	if($_POST['select_Pump1']<>'')
	{$pressure.=trim($_POST['p1'])."/";}
	if($_POST['select_Filter1']<>'')
	{$pressure.=trim($_POST['f1'])."/";}
	if($_POST['select_Filter2']<>'')
	{$pressure.=trim($_POST['f2'])."/";}
	if($_POST['select_Filter3']<>'')
	{$pressure.=trim($_POST['f3'])."/";}
	if($_POST['select_Spot']<>'')
	{$pressure.=trim($_POST['select_Spot'])."/";}
	
	$flow=substr($flow,0,-1);
	$pressure=substr($pressure,0,-1);
	
	echo '充填流程：';
	echo $flow;
	echo '<input type="hidden" name="flow" value="'.$flow.'" />';
	echo '<input type="hidden" name="presure" value="'.$pressure.'" />';
	echo '<BR><input type="submit" name="save" value="    保存   " /><input type="submit" name="leave" value="    取消   " /><BR></form>';
}

if(isset($_POST['save'])){
/*
	$query="select count(*) FROM dbo.Fill_Flow_Chart where Lot_No='".$_GET['lot_no']."'";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$n=$row[0];
*/
	$query="INSERT INTO dbo.Fill_Flow_Chart (Lot_No, flow, creator, date, pressure) VALUES ('".$_GET['lid']."','".$_POST['flow']."','".$_SESSION['uid']."','".date("YmdHis")."','".$_POST['presure']."')";	
	$result=mssql_query($query);
	echo "完成";
//	echo '<script type="text/javascript">window.close()</script>';
}
if(isset($_POST['leave'])){
	echo '<script type="text/javascript">window.close()</script>';
}
