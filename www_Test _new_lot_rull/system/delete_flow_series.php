<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<form action="" method="post">
刪除? <input type="submit" name="enter" value="確定" />
/ <input type="submit" name="cancel" value="取消" />
</form>
<?php
	if(isset($_POST['enter'])){
		session_start();
		include("../connections/conn.php");
		if($_GET['table']=='TANK_DATA'){$query="delete FROM TANK_DATA where [TANK_NO]=".$_GET['index'];}
		else{$query="delete FROM ".$_GET['table']." where [index]=".$_GET['index'];}
		echo $query;
		$result=mssql_query($query);
		echo '<script type="text/javascript">window.close()</script>';
	}
	if(isset($_POST['cancel'])){
		echo '<script type="text/javascript">window.close()</script>';
	}
?>