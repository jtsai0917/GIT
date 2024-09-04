<?php
session_start();
include("../connections/conn.php");
include ("../lib/fun.php");
include ("../lib/jtsai.php");
datepick();
?><head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
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



<form id="form1" name="form1" method="post" action="ins_day_fill_chk.php">
<p>Drum 充填檢查 (每日項目)</p>
<p>充填 LOT NO:
  <input type="text" style="font-size:20px" " name="lid" value="<?php echo $_GET['lid']; ?>" readonly /><BR />
  充填 料號: <input type="text" style="font-size:20px" " name="pid" value="<?php echo $_GET['pid']; ?>" readonly /><BR />
  每天一次的PURGE量(KG): <input style="font-size:20px" " type="text" name="purge_qty" value="" /><BR />
  每天一次的SETCOUNT量(KG): <input style="font-size:20px" " type="text" name="setcount" value="" /><BR />
  每天一次的秤量(KG): <input style="font-size:20px" " type="text" name="qty" value="" /><BR />
  <input type="submit" name="submit" style="font-size:30px" value=" 確定 ">&nbsp;&nbsp;&nbsp;&nbsp;
  <input type="submit" name="cancel" style="font-size:30px" value=" 取消 ">
</p>
<?php
	if(isset($_POST['submit'])){
		$query="SELECT FID_FILL_BEGIN_DATE FROM FILL_INDICATE WHERE (FDM_LOT_NO = '".$_SESSION['lid']."')";
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);
		if(trim($row[0])==''){
			$query="UPDATE FILL_INDICATE SET FID_FILL_BEGIN_DATE = '".date("YmdHis")."' WHERE (FDM_LOT_NO = '".$_SESSION['lid']."')";	
			$result=mssql_query($query);
		}

		$query="INSERT INTO DRUM_FILL_DAY_CHECK (DDC_CHK_DATE, FDM_LOT_NO, PDD_PROD_NO, DDC_PURGE, DDC_SCALES_SET, DDC_CHK_SCALES)
		VALUES  ('".date("Ymd")."','".$_POST['lid']."','".$_POST['pid']."',".$_POST['purge_qty'].",".$_POST['setcount'].",".$_POST['qty'].")";
//		echo $query;
		$result=mssql_query($query);
		jumpto("el_drum_f2.php");
	}
	if(isset($_POST['cancel'])){
		jumpto("el_drum_f1.php");
	}
?>