<meta http-equiv="Content-Type" content="text/html; charset=big5" />
	<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");

datepick();

?>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
</br>
輸入棧板編號:
<input name="name" type="text" id="name" size="10"  />&nbsp;&nbsp;
輸入報廢理由:
<input name="re" type="text" id="re" size="100"  />&nbsp;&nbsp;
</br>
選擇報廢時間:
 <input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php if($_SESSION['datepicker1']){echo $_SESSION['datepicker1'];}?>" onChange="set_date_session(this.name,this.value)">&nbsp;&nbsp;
 <input type="submit" id="new" value="送出" name="new"  />
  <input type="submit" id="leave" value="離開" name="leave"  />
  <?php 
if(isset($_POST['new']))
{	
	$date1=dod($_SESSION['datepicker1']);
	$ALL="select BOA_NO,max(BOA_TIMES) as MAX 
         from BOARD_ALL
		 where BOA_NO='".$_POST['name']."'
         GROUP BY BOA_NO";
	$resultALL = mssql_query($ALL);
	$numrowsALL = mssql_num_rows($resultALL);
	while($rowALL=mssql_fetch_array($resultALL))
	{
		$UP="update BOARD_ALL
		set BOA_DISCARD_DATE='".$date1."'
		,BOA_DISCARD_REASON='".$_POST['re']."'
		where BOA_TIMES='".$rowALL['MAX']."'
		and BOA_NO='".$_POST['name']."'";
		$resultup = mssql_query($UP);
	}
	
}
if(isset($_POST['leave']))
{
header("Location: " . $_SESSION['lasturl'] );
}
?>