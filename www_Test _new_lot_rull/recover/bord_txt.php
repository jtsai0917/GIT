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
?>
棧板回收資料匯入<br><br>


<form action="<?php echo $loginFormAction; ?>" method="post" enctype="multipart/form-data">
檔案名稱:<input type="file" name="file" id="file" />
<input type="submit" name="submit" value="上傳檔案" />
  &emsp;&emsp;&emsp;&emsp;&emsp;  &emsp;&emsp;&emsp;&emsp;&emsp;  &emsp;&emsp;&emsp;&emsp;&emsp;  &emsp;&emsp;&emsp;
<input type="submit" name="submit1" value="確定上傳資料" />
</form>




<?php
echo '<table border="1" width="450">';
	echo '<tr height="">';
	echo  '<td>'."棧板編號".'</td>';
	echo  '<td>'."回廠日期".'</td>';
	echo  '<td>'."狀態".'</td>';
if(isset($_POST['submit']))
{
	
	if ($_FILES["file"]["error"]<=0)
	{
	echo "檔案名稱: " . $_FILES["file"]["name"]."<br/>";
	
	move_uploaded_file($_FILES["file"]["tmp_name"],"../recover/PT.txt");
	}
		$aa=array();$i=0;
		global $c;
		$myfile = fopen("PT.txt", "r") or die("Unable to open file!");
		// ?出?行直到 end-of-file
		while(!feof($myfile)) {
			$i++;
		  //echo fgets($myfile) 
		  $aa[$i]=fgets($myfile);
		 
		  $str[$i]=explode(",",$aa[$i]);
		  echo '<tr>';
		  echo '<td>'.$str[$i][0].'</td>';
		  echo '<td>'.$str[$i][1].'</td>';
		  echo '<td>'.'</td>';
		  
		}
		//echo count($aa);
		$_SESSION['c']=count($aa);
		//echo $_SESSION['c'];
		fclose($myfile);
		
}
if(isset($_POST['submit1']))
{
		echo "全部上傳完成";

	$myfile = fopen("PT.txt", "r") or die("Unable to open file!");
		// ?出?行直到 end-of-file
		while(!feof($myfile)) {
			$i++;
		  //echo fgets($myfile) 
		  $aa[$i]=fgets($myfile);
		  //echo $aa[$i]. "<br>";
		  $str[$i]=explode(",",$aa[$i]);
		  
		
		  
		}		
		fclose($myfile);
	for($j=1;$j<=$_SESSION['c'];$j++)
	{			echo '<tr>';  
				echo '<td>'.$str[$j][0].'</td>';
		  		echo '<td>'.$str[$j][1].'</td>';
		$query="select BOA_NO,max(BOA_TIMES) as MAX
         from BOARD_ALL
         where BOA_NO='".$str[$j][0]."'
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
					set BOA_BACK_DATE='".$str[$j][1]."'
					where BOA_NO='".$str[$j][0]."' and BOA_TIMES='".$row['MAX']."' ";
					$query3="update BOARD set BOD_OUT_LOC=NULL,BOD_OUT_DATE=NULL where BOD_NO='".$str[$j][0]."'";
					$result1 = mssql_query($query1);
					$result3 = mssql_query($query3);
					echo '<td>'."此棧板第".$row['MAX']."次使用以回收".'</td>';
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