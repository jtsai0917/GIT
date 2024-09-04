
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
	<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
include  "../PHPEXCEL/Classes/PHPExcel.php";
include "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
lasturl();
datepick();
auth('5-04',$_SESSION['aut']);
?>
</br>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">

<input type="submit" name="search" id="search" value="送出修改" />
<input type="button" name="worktime" id="worktime" value="新增項目" onClick="window.open('./worktime_new.php ', '_self');" >

<input type="submit" name="del" id="search" value="刪除" />

在輸入欄位輸入99999送出修改   選刪除即可刪除該欄位
</br>
<input name="submit2" type="submit" class="center button" id="submit2" value=" 下載EXCEL ">

<?php
			$objPHPExcel = new PHPExcel();
	
			$objPHPExcel->setActiveSheetIndex(0);
 echo '<table border="1" width="60%">';
			 echo '<tr height="">';
			 echo  '<td>'."檢測藥品".'</td>';
			 echo  '<td>'."檢測項目".'</td>';
			 echo  '<td>'."檢測代號".'</td>';
			  echo  '<td>'."輸入工時".'</td>';
			  $objPHPExcel->getActiveSheet()->setCellValue("A1", iconv("big5","utf-8","檢測藥品"));
			  $objPHPExcel->getActiveSheet()->setCellValue("B1", iconv("big5","utf-8","檢測項目"));
			  $objPHPExcel->getActiveSheet()->setCellValue("C1", iconv("big5","utf-8","檢測代號"));
			  $objPHPExcel->getActiveSheet()->setCellValue("D1", iconv("big5","utf-8","工時"));
			  
$query="select EF.PDD_CHEMICAL,EF.ANI_FULLNAME,EF.ANI_GROUPNAME,EF.hrs
from [WORKTIME] as EF
left join AnalyzeItem as AI on EF.ELM_ID=AI.ANI_INDEX
 ORDER BY EF.PDD_CHEMICAL,EF.ANI_GROUPNAME,EF.ANI_FULLNAME"; 
$result = mssql_query($query);
$numRows=mssql_num_rows($result);	
$i=0;
$V02=array();
$V01=array();
$V03=array();
$NUM=2;
while ($row = mssql_fetch_array($result))
{ $ENG='A';

	echo '<tr height="">';
	echo  '<td>'.$row['PDD_CHEMICAL'].'</td>';
	 $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,$row['PDD_CHEMICAL']);
	 $ENG++;
	echo  '<td>'.$row['ANI_FULLNAME'].'</td>';
	 $objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",$row['ANI_FULLNAME']));
	 $ENG++;
	echo  '<td>'.$row['ANI_GROUPNAME'].'</td>';
	$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,$row['ANI_GROUPNAME']);
	 $ENG++;
	echo  '<td>'.'<input type="text" name="time'.$i.'" value="'.$row['hrs'].'"    >'.'</td>';
	$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,$row['hrs']);
	array_push($V03,$row['PDD_CHEMICAL']);
	array_push($V01,$row['ANI_GROUPNAME']);
	array_push($V02,$row['ANI_FULLNAME']);
	$i++;
	$NUM++;
}
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
			$objWriter->save('timeset.xlsx');
 echo '<tr height="">';
if(isset($_POST['del'])){
	$query2="DELETE FROM WORKTIME			WHERE hrs=99999 ";
			$result2=mssql_query($query2);
			$path_root=$_SERVER['HTTP_HOST'];
	echo '<script>location.href="http://'.$path_root.'/people/worktime_set.php";</script>';	
			}
if(isset($_POST['search']))
{
	for($i=0;$i<$numRows;$i++)
	{		
		if($_POST['time'.$i]=='' )
		{
			
		}
		
			
			
		
		else
		{
			$query1="UPDATE WORKTIME
			set hrs='".$_POST['time'.$i.'']."'
			where ANI_GROUPNAME ='".$V01[$i]."' AND ANI_FULLNAME='".$V02[$i]."' and PDD_CHEMICAL='".$V03[$i]."'";
			$result1 = mssql_query($query1);
			

			echo $V02[$i]."的".$V01[$i]."已經修改";
			echo '</br>';
			
			
		}
		}
		$path_root=$_SERVER['HTTP_HOST'];
	echo '<script>location.href="http://'.$path_root.'/people/worktime_set.php";</script>';	

	
	
	
	/*$query="select WT.ELM_ID,AI.ANI_FULLNAME
	from WORKTIME as WT
	inner join AnalyzeItem as AI on WT.ELM_ID=AI.ANI_INDEX";
	$result = mssql_query($query);
$numRows=mssql_num_rows($result);	

while ($row = mssql_fetch_array($result)){
	$query1="update WORKTIME
	set ANI_FULLNAME='".$row['ANI_FULLNAME']."'
	where ELM_ID='".$row['ELM_ID']."'";
	$result1 = mssql_query($query1);}*/
	
	//$query="UPDATE WORKTIME
	
	//set ANI_GROUPNAME=";
	
	
}
if(isset($_POST['submit2']))
		{
			
			
$path_root=$_SERVER['HTTP_HOST'];
			echo '</br>';
			echo "列印完成";
		echo '<script>document.location.href="http://'.$path_root.'/people/timeset.xlsx";</script>';	
		}
?></form>