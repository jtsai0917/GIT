
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
	<?php
session_start();
include("./connections/conn.php");
include("./lib/fun.php");
include("./lib/jtsai.php");
lasturl();
datepick();
?>
</br>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
<input type="submit" name="search" id="search" value="送出修改" />

<?php
 echo '<table border="1" width="60%">';
			 echo '<tr height="">';
			 echo  '<td>'."檢測藥品".'</td>';
			 echo  '<td>'."檢測項目".'</td>';
			 echo  '<td>'."檢測代號".'</td>';
			  echo  '<td>'."輸入工時".'</td>';
$query="select EF.PDD_CHEMICAL,ANI_FULLNAME,ANI_GROUPNAME
from ELEMENT_FORM as EF
inner join AnalyzeItem as AI on AI.ANI_INDEX=EF.ELM_ID
 ORDER BY EF.PDD_CHEMICAL"; 
$result = mssql_query($query);
$numRows=mssql_num_rows($result);	
$i=0;
$V02=array();
$V01=array();
while ($row = mssql_fetch_array($result))
{
	
	echo '<tr height="">';
	echo  '<td>'.$row['PDD_CHEMICAL'].'</td>';
	echo  '<td>'.$row['ANI_FULLNAME'].'</td>';
	echo  '<td>'.$row['ANI_GROUPNAME'].'</td>';
	echo  '<td>'.'<input type="text" name="time'.$i.'" value="">'.'</td>';
	array_push($V01,$row['ANI_GROUPNAME']);
	array_push($V02,$row['ANI_FULLNAME']);
	$i++;
}
 echo '<tr height="">';

/*if(isset($_POST['search']))
{
	for($i=0;$i<$numRows;$i++)
	{
		if($_POST['time'.$i.'']=='')
		{}
		else
		{
			$query1="UPDATE AnalyzeItem
			set ANI_WORKTIME='".$_POST['time'.$i.'']."'
			where ANI_GROUPNAME = '".$V01[$i]."' AND ANI_FULLNAME='".$V02[$i]."'";
			$result1 = mssql_query($query1);
			
			echo $V02[$i]."的".$V01[$i]."已經修改";
			
			
		}
		
	}
	
}*/

?></form>