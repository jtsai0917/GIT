<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
session_start();
$_SESSION['aa']=array();;
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
include  "../PHPEXCEL/Classes/PHPExcel.php";
include "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
lasturl();
datepick();
?>
棧板回收資料匯入<br>


<form action="<?php echo $loginFormAction; ?>" method="post" enctype="multipart/form-data">
<input type="text" name="board_no" autofocus="autofocus" /> 
<input type="submit" value="  新增  " name="add" />
&emsp;&emsp;&emsp;&emsp;&emsp;回收日期
<input name="datepicker1" type="text" id="datepicker1" size="14" value="<?php 	echo $_SESSION['datepicker1'];?>"  onchange="set_date_session(this.name,this.value)" />  
&emsp;&emsp;&emsp;&emsp;
<input type="submit" name="clear" value="  清除  " />
&emsp;  &emsp;&emsp;&emsp;&emsp;&emsp;  &emsp;&emsp;&emsp;
<input type="submit" name="upload" value=" 確認上傳 " /><BR /><BR />

<?php
if(isset($_POST['add']))
{
	if(trim($_POST['board_no'])<>''){
	$i=0;
	$filename = fopen("tmp_board.txt","a+"); //開啟檔案

	fwrite($filename,$_POST['board_no'].",");

	fclose($file);
	
	}
}

echo '<table width="300" border="1" ><tr bgcolor="#CCCCCC"><td>item</td><td>棧板編號</td></tr>';
	$myfile = fopen("tmp_board.txt", "r") or die("Unable to open file!");
	$str=fread($myfile,filesize("tmp_board.txt"));
	$str= substr($str,0,-1);
	$aa=explode(",",$str);
	$n=count($aa);
	for($i=0;$i<$n;$i++){
		echo '<tr><td>'.($i+1).'</td><td>'.$aa[$i].'</td></tr>';		
	}
	fclose($file);
	echo '</table></form>';
	
	
if(isset($_POST['clear']))
{
	file_put_contents("tmp_board.txt",'');
	refresh();
}

if(isset($_POST['upload']))
{
	echo '<BR>上傳狀況<table border="1" width="300" >';
	for($i=0;$i<$n;$i++)
	{			echo '<tr>';  
				echo '<td>'.($i+1).'</td>';
		  		echo '<td>'.$aa[$i].'</td>';
		$query="select BOA_NO,max(BOA_TIMES) as MAX
         from BOARD_ALL
         where BOA_NO='".$aa[$i]."'
         GROUP BY BOA_NO";
		 $result = mssql_query($query);
		 //echo $query;
		$numrows = mssql_num_rows($result);
		if($numrows==0)
				{
					echo '<td>'."無此棧板".'</td>';
				}
		while($row=mssql_fetch_array($result))
		{		
			$query2="select * from BOARD_ALL where BOA_NO='".$row['BOA_NO']."' and BOA_TIMES='".$row['MAX']."'";
			//echo $query2;
			$result2 = mssql_query($query2);
			$numrows2 = mssql_num_rows($result2);
			
			while($row2=mssql_fetch_array($result2))
			{ 
				
				if($row2['BOA_BACK_DATE']=='')
				{
					$query1="update BOARD_ALL
					set BOA_BACK_DATE='".dod($_SESSION['datepicker1'])."'
					where BOA_NO='".$aa[$i]."' and BOA_TIMES='".$row['MAX']."' ";
					$query3="update BOARD set BOD_OUT_LOC=NULL,BOD_OUT_DATE=NULL where BOD_NO='".$aa[$i]."'";
					$result1 = mssql_query($query1);
					$result3 = mssql_query($query3);
					echo '<td>'."此棧板第".$row['MAX']."次使用已回收".'</td>';
				}
				else
				{
					echo '<td>'."此棧板尚未離場".'</td>';
				}
				
			}
		}
	}

}
?>
</form>
