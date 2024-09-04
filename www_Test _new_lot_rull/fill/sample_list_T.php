<?php
	session_start();
	include("../connections/conn.php");
	include("../lib/fun.php");
	include("../lib/jtsai.php");
	auth('2-11',$_SESSION['aut']);
	datepick();

?>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
日期區間：
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
Lot NO.:<input type="text" size="16" name="lotno" value="<?php echo $_SESSION['lotno'];?>" onChange="set_date_session(this.name,this.value)">
<input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php if($_SESSION['datepicker1']){echo $_SESSION['datepicker1'];}?>" onChange="set_date_session(this.name,this.value)">
~
<input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php if($_SESSION['datepicker2']){echo $_SESSION['datepicker2'];}?>" onChange="set_date_session(this.name,this.value)">
</td>
<input name="submit" type="submit" class="center button" id="submit" value="  查    詢  ">

<?php
 if(isset($_POST['submit'])){
	if($_POST['lotno']<>''){
		$query="SELECT  Lot_No, sample_no, rcv_datetime, creator FROM Sample_Recieving_T where Lot_No='".$_POST['lotno']."' ";
	}
	else{
		$query="SELECT  Lot_No, sample_no, rcv_datetime, creator FROM Sample_Recieving_T where  (Left(rcv_datetime,8) >= '".dod($_SESSION['datepicker1'])."' and Left(rcv_datetime,8) <= '".dod($_SESSION['datepicker2'])."')";
	}
	$result=mssql_query($query);
	$i=1;
	echo '<table width="800" border="1"><tr bgcolor="#CCCCCC"><td> </td><td>Lot NO</td><td>瓶號</td><td>取樣時間</td><td>取樣人員</td></tr>';
	while($row=mssql_fetch_array($result)){
		echo '<tr><td>'.$i.'</td><td>'.$row['Lot_No'].'</td>';
		echo '<td>'.$row['sample_no'].'</td>';
		echo '<td>'.ddt($row['rcv_datetime']).'</td>';
		echo '<td>'.getusername($row['creator']).'</td></tr>';
		$i++;
	}
	echo '</table>';
 }
?>