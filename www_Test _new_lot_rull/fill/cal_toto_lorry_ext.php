<?php
session_start();  
include("../connections/conn.php");
include ("../lib/fun.php");
include ("../lib/jtsai.php");
auth_PDA('20-02',$_SESSION['aut']);
datepick();
if($_SESSION['uid']==''){jumpto("login.php");}
else{$return_page="index.php";}
if($_GET['lid']<>''){$_SESSION['lid']=$_GET['lid'];}

?><head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>Lorry 充填</title>
補充填作業 
<form name="form1" method="post" action="">
  <p>目標 Lot No.
    <input name="lid" type="text" id="lid" value="<?php echo $_SESSION['lid'] ; ?>"  onchange="set_date_session(this.name,this.value)"><input type="submit" name="search" value="check" />
  </p>
  <p>來源 Lot No. 
    <input type="text" name="s_lot_no" value="<?php echo $_SESSION['s_lot_no'];?>" onchange="set_date_session(this.name,this.value)">
  </p>
   <p>容 器  No.  
    <input type="text" name="contener" value="<?php echo $_SESSION['contener'];?>" onchange="set_date_session(this.name,this.value)">
  </p>
   <p>Air 幫浦時間:   
    <input type="text" name="air_t" value="<?php echo $_SESSION['air_t'];?>" onchange="set_date_session(this.name,this.value)">
  分鐘</p>
  <input type="submit" name="submit" value="確定">

<?php
if(isset($_POST['submit'])){
		$query="INSERT INTO IN2LORRY_CAL_TOTO_DRUM_CHECK_DRUM
                            (T3D_LOT_NO, T3D_DRUM_NO, FDM_LOT_NO, T3D_W_CHK_SURFACE, T3D_B_CHK_FILL_PIPE, T3D_F_TIME)
VALUES          ('".$_POST['s_lot_no']."', '".$_POST['contener']."', '".$_POST['lid']."', 'Y', 'Y', '".$_POST['air_t']."')";
//		echo $query ;
//		break;
		$result1 = mssql_query($query);

	echo '<table width="800" border="1" ><tr><td>'.$_SESSION['lid'].'</td></tr></table><table width="800" border="1"><tr><td>來源Lot</td><td>Lorry NO</td><td>Air Pump 時間</td></tr>';
	$query="SELECT T3D_LOT_NO, T3D_DRUM_NO, FDM_LOT_NO, T3D_F_TIME FROM IN2LORRY_CAL_TOTO_DRUM_CHECK_DRUM WHERE (FDM_LOT_NO = '".trim($_SESSION['lid'])."')";
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		echo '<tr><td>'.$row['T3D_LOT_NO'].'</td><td>'.$row['T3D_DRUM_NO'].'</td><td>'.$row['T3D_F_TIME'].'</td></tr>';	
	}
	echo '</table>';
}

if(isset($_POST['search'])){
	echo '<table width="800" border="1" ><tr><td>'.$_SESSION['lid'].'</td></tr></table><table width="800" border="1"><tr><td>來源Lot</td><td>Lorry NO</td><td>Air Pump 時間</td><td>編輯</td></tr>';
	$query="SELECT T3D_LOT_NO, T3D_DRUM_NO, FDM_LOT_NO, T3D_F_TIME FROM IN2LORRY_CAL_TOTO_DRUM_CHECK_DRUM WHERE (FDM_LOT_NO = '".trim($_SESSION['lid'])."')";
	$result=mssql_query($query);
	$i=0;
	while($row=mssql_fetch_array($result)){
		echo '<tr><td>'.$row['T3D_LOT_NO'].'</td><td>'.$row['T3D_DRUM_NO'].'</td><td>'.$row['T3D_F_TIME'].'</td><td><input type="submit" name="edit'.$i.'" value="刪除"></td></tr>';	
		echo '<input type="hidden" name="T3D_LOT_NO'.$i.'" value="'.$row['T3D_LOT_NO'].'">';
		$i++;
	}
	echo '</table>';
	$_SESSION['times']=$i;
}

for($j=0;$j<$_SESSION['times'];$j++){
	if(isset($_POST['edit'.$j])){
		$query="DELETE FROM IN2LORRY_CAL_TOTO_DRUM_CHECK_DRUM
WHERE          (FDM_LOT_NO = '".$_SESSION['lid']."') AND (T3D_LOT_NO = '".$_POST['T3D_LOT_NO'.$j]."')";
		$result=mssql_query($query);
	}
}
?>

</form>