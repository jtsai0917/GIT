<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php 
include("../connections/conn.php");
include("../lib/fun.php");
?>
<form method="post" action="" name="123">
Name: <input type="text" name="column_name" value=""></br>
type: <input type="text" name=" type" value=""></br>
<input type="submit" name="submit" value="Submit">
</form>
<?php
if(isset($_POST['submit'])){
	$name=trim($_POST['column_name']);
	$type=trim($_POST['type']);
	$query="SELECT DISTINCT ELF_FORM FROM ELEMENT_FORM";
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		add_column($row['ELF_FORM'],$name,$type);
	}
}
function add_column($table,$column_name,$type){
	$query="ALTER TABLE ".$table." ADD ".$column_name." ".$type."";
	$result=mssql_query($query);
}

?>