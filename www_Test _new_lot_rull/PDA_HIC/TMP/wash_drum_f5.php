<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
</head>
 <p><a href="fill.php"><img src="../pics/TYS.jpg" alt="" width="161" height="86" /></a>人員：<?php echo $_SESSION['uname']?> </p>
 <p>&nbsp;</p>
 <p><br />
   <br />
   <?php
session_start();
include("../connections/conn.php");
include ("../lib/fun.php");
include ("../lib/jtsai.php");
datepick();
echo "(EL 藥品) 充填作業"."<BR>";
echo "客戶:".$_SESSION['cid']."<BR>";
echo "Lot NO.:".$_SESSION['lid']."<BR>";
echo "桶號:".$_SESSION['dm_no']."<BR>";
echo "製造Lot:".$_SESSION['made_lot']."<BR>";

?>
 </p>
 <form id="form1" name="form1" method="post" action="<?php echo $loginFormAction;?>">
   <p>
     <label for="weight"></label>
   空桶重：
   <input type="text" name="weight" id="weight" />
   </p>
   <p>
     <input type="checkbox" name="ck1" id="ck1" checked="checked"/>
     <label for="ck1" ></label>
   重量CHECK</p>
   <p>
     <input type="checkbox" name="ck2" id="ck2" checked="checked"/>
     <label for="ck2"></label>
   CAP蓋上</p>
   <p>
     <input type="checkbox" name="ck3" id="ck3" checked="checked"/>
     <label for="ck3"></label> 
   桶上層擦拭(無汙染/水附著)</p>
   <p>
     <input type="submit" name="smp" id="smp" value="取樣" />
     <input type="submit" name="next" id="smp2" value="下一桶" />
     <input type="submit" name="finish" id="smp3" value="結束" />
   </p>
 </form>
<?php 
$loginFormAction = $_SERVER['PHP_SELF'];
if(isset($_POST['smp'])){
		
}

if(isset($_POST['next'])){  // EL_FILLDATA_DRUM
	$query="select max(EFD_SERIAL_NO)+1 from EL_FILLDATA_DRUM where FDM_LOT_NO='".$_SESSION['lid']."'";
//	echo $query;
//	echo "<BR>";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$sn=$row[0];
	$query="INSERT INTO EL_FILLDATA_DRUM (FDM_LOT_NO, EFD_DRUM_NO, EFD_SERIAL_NO, EFD_OPERATOR, EFD_EMPTY_SCALES, EFD_QTY, EFD_CHK_CAP, EFD_CHK_CAP_CLR, EFD_SAMPLING, EFD_MAKELOT) VALUES ('".$_SESSION['lid']."','".$_SESSION['dm_no']."',".$sn." ,'".$_SESSION['uid']."',".$_POST['weight'].",".$_SESSION['drum_kg'].",'Y','Y','N','".$_SESSION['made_lot']."')";
	$result=mssql_query($query);
	
	echo '<a  href=el_drum_f2.php target="_blank"> NEXT f5</a>';
	jumpto("el_drum_f2.php");	
}

if(isset($_POST['finish'])){
	jumpto("index.php");
}

 
?>
 
 