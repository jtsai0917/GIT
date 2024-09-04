
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
	<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
lasturl();
datepick();
?>
</br>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
<input type="submit" name="search" id="search" value="送出修改" />

<?php
 echo '<table border="1" width="60%">';
			 echo '<tr height="">';
			 echo  '<td>'."檢測代號".'</td>';
			  echo  '<td>'."輸入工時".'</td>';
$query="select distinct ANI_GROUPNAME
from AnalyzeItem"; 
$result = mssql_query($query);
$numRows=mssql_num_rows($result);	
$i=1;
while ($row = mssql_fetch_array($result))
{
	
	echo '<tr height="">';
	echo  '<td>'.$row['ANI_GROUPNAME'].'<td>';
	echo '<input type="text" name="time'.$i.'" value="">';
	$i++;
}


?></form>
<?php
if(isset($_POST['search']))
{
	echo $_POST['time1'];
	echo "我在這";
}
?>