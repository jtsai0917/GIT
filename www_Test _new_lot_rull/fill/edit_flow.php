<?php 
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
$query="select top(1) * from Fill_Flow_Chart where Lot_no='".$_GET['lot_no']."'  order by date desc";
$result=mssql_query($query);
$row=mssql_fetch_array($result);
$FLOW=explode("/",trim($row['flow']));
$pressure=explode("/",trim($row['pressure']));$v=$w=$x=$y=1;
unset($ta_,$za_,$pu_,$fi_);
for($i=0;$i<count($FLOW);$i++)
{
	if(substr(trim($FLOW[$i]),0,1)=='T'){$ta[$v]=$pressure[$i];$ta_[$v]=trim($FLOW[$i]);$v++;}
	if(substr(trim($FLOW[$i]),0,1)=='Z'){$za[$w]=$pressure[$i];$za_[$w]=trim($FLOW[$i]);$w++;}
	if(substr(trim($FLOW[$i]),0,1)=='P'){$pu[$x]=$pressure[$i];$pu_[$x]=trim($FLOW[$i]);$x++;}
	if(substr(trim($FLOW[$i]),0,1)=='S'){$fi[$y]=$pressure[$i];$fi_[$y]=trim($FLOW[$i]);$y++;}
}
$jj=new get_from_lot_no;
$jj->lid=$_GET['lot_no'];
$jj->ani();
echo "產品編號:".$pid=trim($jj->pid);
echo '<form name="form1" method="post" action="'.$loginFormAction.'">';
echo '<font size="+2">設定 '.$_GET['lot_no'].' 充填流程</font>';
if($_GET['id']==2){echo '<input type="submit" name="set_trans" value="設定相關參數">';}
	echo '<BR>選擇充填路徑<table width="600" border="1" bgcolor="#CCCCCC"><tr><td width="100">系別</td><td width="100">TANK</td><td width="100">Pump1</td><td width="100">TANK1</td></tr><tr><td>';
echo '<tr><td>';
select_series($pid,$FLOW[0]);
	echo '</td><td>';
	select_Tank(trim($jj->pid),$ta_[1]);
	echo '</td><td>';
	select_Pump($pid,$pu_[1]);
	echo '<br><input type="text" size="5" name="p0" value="'.$pu[1].'">kg/cm^2';
	echo '</td><td>';
	select_Tank1(trim($jj->pid),$ta_[2]);
	echo '</td></tr><tr>';
	echo '<td width="100">Pump2</td><td width="100">Filter 1</td><td width="100">Filter 2</td><td width="100">Filter 3</td></tr><td>';
	select_Pump1($pid,$pu_[2]);
	echo '<br><input type="text" size="5" name="p1" value="'.$pu[2].'">kg/cm^2';
	echo '</td><td>';
	select_Filter1($pid,$fi_[1]);
	echo '<br><input type="text" size="5" name="f1" value="'.$fi[1].'">kg/cm^2';
	echo '</td><td>';
	select_Filter2($pid,$fi_[2]);
	echo '<br><input type="text" size="5" name="f2" value="'.$fi[2].'">kg/cm^2';
	echo '</td><td>';
	select_Filter3($pid,$fi_[3]);
	echo '<br><input type="text" size="5" name="f3" value="'.$fi[3].'">kg/cm^2';
	echo '</td></tr><tr><td>充填站</td><tr><td>';
	select_Spot($pid,$za_[1]);
	echo '</td></tr></table>';	
echo '<table width="600" border="1" bgcolor="#CCCCCC"><tr><td>';
echo '<input type="submit" name="save1" value="    選擇完畢   " />    ';
echo '<input type="submit" name="leave" value="    離開   " />';
echo '</td></tr></table>';

function select_series($pid,$value){
	echo '<select name="select_series" id="select_series" >';
	$query="SELECT series FROM FILL_Series WHERE (Prod_No = '".$pid."') order by [index]";
//	$_SESSION['test']= "<BR>".$query."<BR>";
	$result = mssql_query($query);$i=0;$PASS=0;
	$Numrows=mssql_num_rows($result);
	while($row=mssql_fetch_array($result)){	
		if($value=='' and $i==$Numrows){echo '<option value=""></option>';$PASS=1;}
		if(trim($row['series'])==$value){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['series'].'" '.$select.'>'.$row['series'].'</option>';
		$i++;
	}
	if($PASS==0){echo '<option value=""></option>';}
	echo '</select>';
}

function select_Tank($pid,$value){
	
	
	echo '<select name="select_Tank" id="select_Tank">';
	//echo '<option value=""></option>';
	$query="SELECT PROD_TANK FROM TANK_DATA1 WHERE (PDD_PROD_NO = '".$pid."') order by PROD_TANK";
	$result = mssql_query($query);$i=0;$PASS=0;	
	$Numrows=mssql_num_rows($result);
	while($row=mssql_fetch_array($result)){	
		if($value=='' and $i==$Numrows){echo '<option value=""></option>';$PASS=1;}
		if(trim($row['PROD_TANK'])==$value){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['PROD_TANK'].'" '.$select.'>'.$row['PROD_TANK'].'</option>';
	}
	if($PASS==0){echo '<option value=""></option>';}
	echo '</select>';
}

function select_Tank1($pid,$value){
	echo '<select name="select_Tank1" id="select_Tank1">';
	$query="SELECT PROD_TANK FROM TANK_DATA1 WHERE (PDD_PROD_NO = '".$pid."')";
	$result = mssql_query($query);$i=0;$PASS=0;
	$Numrows=mssql_num_rows($result);	
	while($row=mssql_fetch_array($result)){	
		if($value=='' and $PASS==0){echo '<option value=""></option>';$PASS=1;}
		if(trim($row['PROD_TANK'])==$value){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['PROD_TANK'].'" '.$select.'>'.$row['PROD_TANK'].'</option>';
	}
	if($PASS==0){echo '<option value=""></option>';}
	echo '</select>';
}

function select_Pump($pid,$value){
	echo '<select name="select_Pump" id="select_Pump">';
	$query="SELECT PumpNo FROM Fill_Pump WHERE (Prod_No = '".$pid."')";
	$result = mssql_query($query);$i=0;$PASS=0;
	$Numrows=mssql_num_rows($result);
	while($row=mssql_fetch_array($result)){
		if($value=='' and $PASS==0){echo '<option value=""></option>';$PASS=1;}	
		if(trim($row['PumpNo'])==$value){$select='selected';}	
		else{$select='';}
		echo '<option value="'.$row['PumpNo'].'" '.$select.'>'.$row['PumpNo'].'</option>';
	}
	if($PASS==0){echo '<option value=""></option>';}
	echo '</select>';
}

function select_Pump1($pid,$value){
	echo '<select name="select_Pump1" id="select_Pump1">';
	$query="SELECT PumpNo FROM Fill_Pump WHERE (Prod_No = '".$pid."')";
	$result = mssql_query($query);$i=0;$PASS=0;
	$Numrows=mssql_num_rows($result);
	while($row=mssql_fetch_array($result)){
		if($value=='' and $PASS==0){echo '<option value=""></option>';$PASS=1;}
		if(trim($row['PumpNo'])==$value){$select='selected';}		
		else{$select='';}
		echo '<option value="'.$row['PumpNo'].'" '.$select.'>'.$row['PumpNo'].'</option>';
	}
	if($PASS==0){echo '<option value=""></option>';}
	echo '</select>';
}

function select_Filter1($pid,$value){
	echo '<select name="select_Filter1" id="select_Filter1">';
	$query="SELECT filter FROM Fill_Filter WHERE (Prod_No = '".$pid."')";
	$result = mssql_query($query);$i=0;$PASS=0;
	$Numrows=mssql_num_rows($result);
	while($row=mssql_fetch_array($result)){		
		if($value=='' and $PASS==0){echo '<option value=""></option>';$PASS=1;}
		if(trim($row['filter'])==$value){$select='selected';}		
		else{$select='';}
		echo '<option value="'.$row['filter'].'" '.$select.'>'.$row['filter'].'</option>';
	}
	if($PASS==0){echo '<option value=""></option>';}
	echo '</select>';
}

function select_Filter2($pid,$value){
	echo '<select name="select_Filter2" id="select_Filter2">';
	$query="SELECT filter FROM Fill_Filter WHERE (Prod_No = '".$pid."')";
	$result = mssql_query($query);$i=0;$PASS=0;
	$Numrows=mssql_num_rows($result);
	while($row=mssql_fetch_array($result)){		
		if($value=='' and $PASS==0){echo '<option value=""></option>';$PASS=1;}
		if(trim($row['filter'])==$value){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['filter'].'" '.$select.'>'.$row['filter'].'</option>';
	}
	if($PASS==0){echo '<option value=""></option>';}
	echo '</select>';
}

function select_Filter3($pid,$value){
	echo '<select name="select_Filter3" id="select_Filter3">';
	$query="SELECT filter FROM Fill_Filter WHERE (Prod_No = '".$pid."')";
	$result = mssql_query($query);$i=0;$PASS=0;
	$Numrows=mssql_num_rows($result);
	while($row=mssql_fetch_array($result)){		
		if($value=='' and $PASS==0){echo '<option value=""></option>';$PASS=1;}
		if(trim($row['filter'])==$value){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['filter'].'" '.$select.'>'.$row['filter'].'</option>';
	}
	if($PASS==0){echo '<option value=""></option>';}
	echo '</select>';
}

function select_Spot($pid,$value){
	echo '<select name="select_Spot" id="select_Spot">';
	$query="SELECT SpotNo FROM Fill_Spot WHERE (Prod_No = '".$pid."')";
	$result = mssql_query($query);$i=0;$PASS=0;
	$Numrows=mssql_num_rows($result);
	while($row=mssql_fetch_array($result)){		
		if($value=='' and $PASS==0){echo '<option value=""></option>';$PASS=1;}
		if(trim($row['SpotNo'])==$value){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['SpotNo'].'" '.$select.'>'.$row['SpotNo'].'</option>';
	}
	if($PASS==0){echo '<option value=""></option>';}
	echo '</select>';
}


if(isset($_POST['save1'])){
	$jj=new get_from_lot_no;
	$jj->lid=$_GET['lot_no'];
	$jj->ani();
	$pid=trim($jj->pid);
	$cid=trim($jj->cid);
	$text=array('系別','TANK','Pump1','TANK1','Pump2','Filter1','Filter2','Filter3','充填站');
	if($_POST['select_series']<>'' and $_POST['select_series']<>'NA')
	{
		$flow.=trim($_POST['select_series'])."/";
		$flow1.=trim($_POST['select_series'])."/";
		$pressurex.="NA/";
	}else{
		$flow1.="NA/";
	}
	if($_POST['select_Tank']<>'' and $_POST['select_Tank']<>'NA')
	{
		$flow.=trim($_POST['select_Tank'])."/";
		$flow1.=trim($_POST['select_Tank'])."/";
		$pressurex.="NA/";	
	}else{
		$flow1.="NA/";
	}
	if($_POST['select_Pump']<>'' and $_POST['select_Pump']<>'NA')
	{
		$flow.=trim($_POST['select_Pump'])."/";
		$flow1.=trim($_POST['select_Pump'])."/";
		$pressurex.=trim($_POST['p0'])."/";	
	}else{
		$flow1.="NA/";
	}
	if($_POST['select_Tank1']<>'' and $_POST['select_Tank1']<>'NA')
	{
		$flow.=trim($_POST['select_Tank1'])."/";
		$flow1.=trim($_POST['select_Tank1'])."/";
		$pressurex.="NA/";
	}else{
		$flow1.="NA/";
	}
	if($_POST['select_Pump1']<>'' and $_POST['select_Pump1']<>'NA')
	{
		$flow.=trim($_POST['select_Pump1'])."/";
		$flow1.=trim($_POST['select_Pump1'])."/";
		$pressurex.=trim($_POST['p1'])."/";
	}else{
		$flow1.="NA/";
	}
	if($_POST['select_Filter1']<>'' and $_POST['select_Filter1']<>'NA')
	{
		$flow.=trim($_POST['select_Filter1'])."/";
		$flow1.=trim($_POST['select_Filter1'])."/";
		$pressurex.=trim($_POST['f1'])."/";
	}else{
		$flow1.="NA/";
	}
	if($_POST['select_Filter2']<>'' and $_POST['select_Filter2']<>'NA')
	{
		$flow.=trim($_POST['select_Filter2'])."/";
		$flow1.=trim($_POST['select_Filter2'])."/";
		$pressurex.=trim($_POST['f2'])."/";
	}else{
		$flow1.="NA/";
	}
	if($_POST['select_Filter3']<>'' and $_POST['select_Filter3']<>'NA')
	{
		$flow.=trim($_POST['select_Filter3'])."/";
		$flow1.=trim($_POST['select_Filter3'])."/";
		$pressurex.=trim($_POST['f3'])."/";
	}else{
		$flow1.="NA/";
	}
	if($_POST['select_Spot']<>'' and $_POST['select_Spot']<>'NA')
	{
		$flow.=trim($_POST['select_Spot'])."/";
		$flow1.=trim($_POST['select_Spot'])."/";
		$pressurex.="NA/";
	}else{
		$flow1.="NA/";
	}
	
	
	$flow=substr($flow,0,-1);
	$pressurex=substr($pressurex,0,-1);
	$flow1a=explode("/",$flow1);
	$pressure1=explode("/",$pressurex);
	echo '充填流程：'.$flow.'<br>';
	echo '充填壓力：'.$pressurex.'<br>';
	for($i=0;$i<9;$i++)
	{
		if($flow1a[$i]<>'' and $flow1a[$i]<>'NA'){
			echo $flow1a[$i].'＝'.$pressure1[$i].'<br>';
		}
		/*
		if($i==0 ){echo $text[$i].'：'.$flow1a[$i].'<br>';}
		elseif($i==1){echo $text[$i].'：'.$flow1a[$i].'＝<br>';}
		elseif($i==3){echo $text[$i].'：'.$flow1a[$i].'＝<br>';}
		else
		{
		echo $text[$i].'：'.$flow1a[$i].'＝'.$pressure1[$i].'<br>';
		}
		*/
	}
	echo '<font size="+1">';
	//echo $flow;
	echo '<input type="hidden" name="flow" value="'.$flow.'" />';
	echo '<input type="hidden" name="presure" value="'.$pressurex.'" /></font>';
	echo '<BR><input type="submit" name="save" value="    保存   " /><input type="submit" name="leave" value="    取消   " /><BR></form>';
}

if(isset($_POST['save'])){
/*
	$query="select count(*) FROM dbo.Fill_Flow_Chart where Lot_No='".$_GET['lot_no']."'";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$n=$row[0];
*/
	$query="delete from dbo.Fill_Flow_Chart where Lot_No='".$_GET['lot_no']."'";
	$result=mssql_query($query);
	
	$query="INSERT INTO dbo.Fill_Flow_Chart (Lot_No, flow, creator, date, pressure) VALUES ('".$_GET['lot_no']."','".$_POST['flow']."','".$_SESSION['uid']."','".date("YmdHis")."','".$_POST['presure']."')";	
	$result=mssql_query($query);
	echo "完成";
}
if(isset($_POST['leave'])){
	echo '<script type="text/javascript">window.close()</script>';
}
