<?php
session_start();  
include("../connections/conn.php");
include ("../lib/fun.php");
include ("../lib/jtsai.php");
datepick();
if($_SESSION['uid']==''){jumpto("login.php");}
else{$return_page="index.php";}
$ao=new get_from_lot_no;
	$ao->lid=$_SESSION['lid'];
	$ao->cid();
	$ao->ani();
if($ao->pdd_type=='LY'){
	$type=$ao->lyno;
	$ao->lorry_no1=$type;
	$ao->chk_car_type();
	}
else{
	$type=$ao->showname;
	$ao->lorry_no1=$type;
	$ao->chk_car_type();	
}
if($ao->pdd_type=='LY'){$type_='LY';$oc='拖車';}
elseif($ao->pdd_type=='DM'){$type_='DM';$oc='鷗翼車';}
echo "出貨車輛：".$oc;

?><head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>悠技出貨檢查</title>
<style type="text/css">

body,td,th {
	font-size:<?php echo $_SESSION['font_size'];?>px;
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
</head>

<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
<p><a href="fill.php"></a>
	<BR />作 業 者：
<?php 
	echo $_SESSION['uname']."<BR>車輛代號：";
		if($_SESSION['cartype']==''){$s0='selected';}
		if($_SESSION['cartype']=='ZI-60'){$s1='selected';}
		if($_SESSION['cartype']=='ZI-61'){$s2='selected';}
		if($_SESSION['cartype']=='ZI-99'){$s3='selected';}
		if($_SESSION['cartype']=='ZL-01'){$s4='selected';}
		if($_SESSION['cartype']=='92-YZ'){$s5='selected';}
		if($_SESSION['cartype']=='96-ZA'){$s6='selected';}
		if($_SESSION['cartype']=='57-PV'){$s7='selected';}
		echo '<select name="cartype" id="cartype" onchange="set_date_session(this.name,this.value)">
			  <option value="" '.$s0.'></option>
			  <option value="ZI-60" '.$s1.'>ZI-60</option>
			  <option value="ZI-61" '.$s2.'>ZI-61</option>
			  <option value="ZI-99" '.$s3.'>ZI-99</option>
			  <option value="ZL-01" '.$s4.'>ZL-01</option>
			  <option value="92-YZ" '.$s5.'>92-YZ</option>
			  <option value="96-ZA" '.$s6.'>96-ZA</option>
			  <option value="57-PV" '.$s7.'>57-PV</option>
			  </select >';

	echo '<table width="300" border="1"><tr align="center"><td width="100">批 號 </td><td width="200">'.$_SESSION['lid'].'</td></tr>' ;
	$ap=new get_from_lot_no;
	$ap->lid=$_SESSION['lid'];
	$ap->cid();
	
//	if($ap->spc<>'LY'){echo '<tr align="center"><td>位置</td><td>'.$_SESSION['lorry_no1'].'</td></tr>' ; }
	
	echo '<tr align="center"><td>槽車條碼(LotNo+MCTW)</td><td>'.$_SESSION['lorry_no1'].'</td></tr>' ;
	
	echo '<tr align="center"><td>包裝型態</td><td>'.$type.'</td></tr></table>' ;
	echo '<input type="hidden" name="lorry__no" value="'.$type.'">';
if(substr(trim($_SESSION['lorry_no1']),0,11)==trim($_SESSION['lid']) and (substr(trim($_SESSION['lorry_no1']),11,4)=='MCTW' or substr(trim($_SESSION['lorry_no1']),11,4)=='mctw')){
		
	
			echo '<BR>選擇裝車位置<BR>
			1<input type="radio" name="radio" id="place" value="1" />&nbsp;&nbsp;
			2<input type="radio" name="radio" id="place" value="2" />&nbsp;&nbsp;
			3<input type="radio" name="radio" id="place" value="3" /><BR>
			4<input type="radio" name="radio" id="place" value="4" />&nbsp;&nbsp;
			5<input type="radio" name="radio" id="place" value="5" />&nbsp;&nbsp;
			6<input type="radio" name="radio" id="place" value="6" />';
			echo '<BR><input type="submit" name="save_" value=" 完 成 " autofocus="autofocus">';
}		
else{
	echo "檢查碼錯誤<BR>";
}		
	
	if($ao->numrows==0 and $_SESSION['lid']<>''){
		$_SESSION['lid']=$_SESSION['lorry_no1']=$_SESSION['lorry_no']='';
		my_msg('Lot NO 輸入錯誤!!');
	}
	if($_SESSION['lid']=='') // LOT NO
	{
		$_SESSION['lorry_no1']=$_SESSION['lorry_no']='';
		$fo1='autofocus="autofocus"';
		echo '<BR>Lot NO ：
    <input type="text"  autocomplete="off" name="lid" id="lid"  style="font-size:'.$_SESSION['font_size'].'px" size="12" value="'.$_SESSION['lid'].'"'.$fo1.'onchange="set_date_session(this.name,this.value)"/>';
	}
	else{
		$fo1='';
	}
	
	if($_SESSION['lid']<>'' and $_SESSION['lorry_no1']=='') //Lorry NO
	{
		$fo2='autofocus="autofocus"';
		echo '<BR />槽車檢查碼：
    <input type="password" autocomplete="off" name="lorry_no" id="lorry_no"  style="font-size:'.$_SESSION['font_size'].'px" size="12"  value="'.$_SESSION['lorry_no'].'"'.$fo2. ' onchange="set_date_session(this.name,this.value)"/>';
	$_SESSION['lorry_no1']=trim($_SESSION['lorry_no']) ;
	}
	else{
		$fo2='';
	}
?>
</p>
<input type="submit" hidden="hidden" name="enter" value="ENTER"  />

<?php
if(isset($_POST['enter'])) {
	$_SESSION['lorry_no']=trim($_POST['lorry_no']);
	$_SESSION['lorry_no1']=(trim($_SESSION['lorry_no'])) ;
	$ap=new get_from_lot_no;
	$ap->lid=$_SESSION['lid'];
	refresh();
}
if(isset($_POST['save'])) {
	$datetime=date("YmdHis");
	$query="SELECT TOP (1) [index], CAR_NO, DATE, PRINTED, CANCEL, DATETIME FROM YG_CAR_OUT  WHERE (CAR_NO = '".$_SESSION['cartype']."') ORDER BY [index] DESC";
	echo $query."<BR>";;
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$numrows=mssql_num_rows($result);
	$car_no=$row[1];$car_date=$row[2];$car_printed=$row[3];$car_cancel=$row[4];$car_index=$row[0];
	if($car_cancel<>'' or $car_printed<>'' or $numrows==0){

		$query1="INSERT INTO YG_CAR_OUT (CAR_NO, [DATE], DATETIME) VALUES ( N'".$_SESSION['cartype']."', N'".date("Ymd")."', N'".$datetime."')  select scope_identity() ";	
		echo $query1."<BR>";;
		$result1=mssql_query($query1);
		$row1=mssql_fetch_row($result1);
		$sn=$row1[0];
	}
	else{
		$sn=$car_index;	
	}
	$query="INSERT INTO YG_CHECK_DETAIL (LOT_NO, CAR_TYPE, PLACE, LORRY_NO, datetime, car_out_id,CAR_NO, CHECKER) VALUES 
	('".$_SESSION['lid']."',1, 0, N'".$_POST['lorry__no']."', N'".$datetime."',".$sn.", N'".$_SESSION['cartype']."',N'".$_SESSION['uid']."')";
 	echo $query."<BR>";;
 	break();
 	$result=mssql_query($query);	
	$_SESSION['lorry_no1']=$_SESSION['lorry_no']==$_SESSION['lid']='';
		my_msg($sn."");
		my_msg($oo." 檢查完成 !!");
}

if(isset($_POST['save_'])) {
 //	echo "RADIO:".$_POST['radio']."<BR>";
	$datetime=date("YmdHis");
	$query="SELECT TOP (1) [index], CAR_NO, DATE, PRINTED, CANCEL, DATETIME FROM YG_CAR_OUT  WHERE (CAR_NO = '".$_POST['cartype']."') ORDER BY [index] DESC";
	echo $query."<BR>";;
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$numrows=mssql_num_rows($result);
	$car_no=$row[1];$car_date=$row[2];$car_printed=$row[3];$car_cancel=$row[4];$car_index=$row[0];
//	my_msg($car_cancel);
	if($car_cancel<>'' or $car_printed<>'' or $numrows==0){

		$query1="INSERT INTO YG_CAR_OUT (CAR_NO, [DATE], DATETIME) VALUES ( N'".$_POST['cartype']."', N'".date("Ymd")."', N'".$datetime."')  select scope_identity() ";	
//		echo $query1."<BR>";;
		$result1=mssql_query($query1);
		$row1=mssql_fetch_row($result1);
		$sn=$row1[0];
	}
	else{
		$sn=$car_index;	
	}
	
	// add car no and get id
//	$query="insert  INTO YG_CAR_OUT(CAR_NO, CAR_TYPE, DATE, SN, PRINTED, CANCEL) VALUES (N'3', 4, N'5', 6, 7, 8) select scope_identity() ";
	$query="INSERT INTO YG_CHECK_DETAIL (LOT_NO, CAR_TYPE, PLACE, LORRY_NO, datetime, car_out_id,CAR_NO, CHECKER) VALUES 
	('".$_SESSION['lid']."',2, ".$_POST['radio'].", N'".$_POST['lorry__no']."', N'".$datetime."',".$sn.", N'".$_POST['cartype']."',N'".$_SESSION['uid']."')";
	echo $query."<BR>";
	break();
 	$result=mssql_query($query);	
	$_SESSION['lorry_no1']=$_SESSION['lorry_no']==$_SESSION['lid']='';
		my_msg($sn."");

	my_msg($oo." 檢查完成 !!");
}


?>

</form>
<p><strong><a href="<?php echo $return_page;?>">主目錄</a></strong></p>