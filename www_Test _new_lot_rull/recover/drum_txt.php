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
DRUM回收資料匯入<br><br>


<form action="<?php echo $loginFormAction; ?>" method="post" enctype="multipart/form-data">
檔案名稱:<input type="file" name="file" id="file" />
<input type="submit" name="submit" value="上傳檔案" />
  &emsp;&emsp;&emsp;&emsp;&emsp;  &emsp;&emsp;&emsp;&emsp;&emsp;  &emsp;&emsp;&emsp;&emsp;&emsp;  &emsp;&emsp;&emsp;
<input type="submit" name="submit1" value="確定上傳資料" />
</form>

<?php
echo '<table border="1" width="450">';
	echo '<tr height="">';
	echo  '<td>'."桶號".'</td>';
	echo  '<td>'."動作".'</td>';
	echo  '<td>'."日期".'</td>';
	echo  '<td>'."狀態".'</td>';
if(isset($_POST['submit']))
{
	
	if ($_FILES["file"]["error"]<=0)
	{
		$_SESSION['file']=$_FILES["file"]["name"];
	echo "檔案名稱:".$_FILES["file"]["name"]."<br/>";
	
	move_uploaded_file($_FILES["file"]["tmp_name"],"../recover/".$_FILES["file"]["name"].".txt");
	$aa=array();$i=0;
	$myfile = fopen($_FILES["file"]["name"].".txt", "r") or die("unable to open file!");
		// ?出?行直到 end-of-file
		while(!feof($myfile)) {
			$i++;
		  //echo fgets($myfile) 
		  $aa[$i]=trim(fgets($myfile));
		 
		  $str[$i]=explode(",",$aa[$i]);
		  if($str[$i][0]=='')
		{break;}
		  echo '<tr>';
		  echo '<td>'.$str[$i][0].'</td>';
		  if($str[$i][1]==1)
		  {
		  	echo '<td>'.$str[$i][1].".回收".'</td>';
		  }
		  if($str[$i][1]==2)
		  {
		  	echo '<td>'.$str[$i][1].".轉用".'</td>';
		  }
		  if($str[$i][1]==3)
		  {
		  	echo '<td>'.$str[$i][1].".廢棄".'</td>';
		  }
		  echo '<td>'.$str[$i][2].'</td>';
		  echo '<td>'.'</td>';
		  
		}
		
		fclose($myfile);
	}
}
if(isset($_POST['submit1']))
{
	echo "全部上傳完成";

	$myfile = fopen($_SESSION['file'].".txt", "r") or die("unable to open file!");
		// ?出?行直到 end-of-file
		
		while(!feof($myfile)) {
			$i++;
		  //echo fgets($myfile) 
		  $aa[$i]=trim(fgets($myfile)); 
		  $str[$i]=explode(",",$aa[$i]);
		}
		$c=count($aa);
		fclose($myfile);
	}
	for($j=1;$j<=$c;$j++)
	{
		echo '<tr>';
		if($str[$j][0]=='')
		{break;}
		  echo '<td>'.$str[$j][0].'</td>';
		  if($str[$j][1]==1)
		  {
		  	echo '<td>'.$str[$j][1].".回收".'</td>';
		  }
		  if($str[$j][1]==2)
		  {
		  	echo '<td>'.$str[$j][1].".轉用".'</td>';
		  }
		  if($str[$j][1]==3)
		  {
		  	echo '<td>'.$str[$j][1].".廢棄".'</td>';
		  }
		  echo '<td>'.$str[$j][2].'</td>';
		
		  $re="select DHN.* ,CD1.CTD_CUST_SHORT_NAME as C1,CD2.CTD_CUST_SHORT_NAME as C2,CD3.CTD_CUST_SHORT_NAME as C3
		  from DRUM_HISTORY_NORMAL as DHN
		  left join CUSTOMER_DATA as CD1 on CD1.CTD_CUST_NO=DHN.CTD_CUST_NO1 
		  left join CUSTOMER_DATA as CD2 on CD2.CTD_CUST_NO=DHN.CTD_CUST_NO2
		  left join CUSTOMER_DATA as CD3 on CD3.CTD_CUST_NO=DHN.CTD_CUST_NO3
		  where DHN.DHN_DRUM_NO='".$str[$j][0]."' ";
			
			$resultre = mssql_query($re);
			$numrowsre = mssql_num_rows($resultre);
			if($numrowsre==0){echo '<td>'."無此桶號".'</td>';}
			while($rowre=mssql_fetch_array($resultre))
			{ 
		if($str[$j][1]==1)
		{
			if(trim($rowre['DHN_DISCARD_DATE'])=='')
			{	
				$query="UPDATE EL_WASH_DRUM SET EWD_DRUM = '".$str[$j][0]."_BACK' WHERE (EWD_DRUM = '".$str[$j][0]."') and (DISABLE = 0 or DISABLE is null)";
				$result=mssql_query($query);
				
				$query="update DRUM_HISTORY_NORMAL set DHN_TYS_RCV_DATE1='".$str[$j][2]."' where DHN_DRUM_NO='".$str[$j][0]."' and (DISABLE = 0 or DISABLE is null)";
				$result=mssql_query($query);
				if(trim($rowre['DHN_OUT_DATE3'])<>'' and trim($rowre['DHN_TYS_RCV_DATE3'])=='')
				{
					$up1="update DRUM_HISTORY_NORMAL
					set DHN_WL_RCV_DATE3='".$str[$j][2]."',DHN_TYS_RCV_DATE3='".$str[$j][2]."'
					where DHN_DRUM_NO='".$str[$j][0]."' and (DISABLE = 0 or DISABLE is null)";
					$resultup1 = mssql_query($up1);
					if(trim($rowre['CTD_CUST_NO3'])<>'')
						{
							echo '<td>'."第三次出貨回收,客戶:".$rowre['C3'].'</td>';
							break;
						}
						if(trim($rowre['CTD_CUST_NO3'])=='' and trim($rowre['CTD_CUST_NO2'])<>'')
						{
							echo '<td>'."第三次出貨回收,客戶:".$rowre['C2'].'</td>';
							break;
						}
						if(trim($rowre['CTD_CUST_NO3'])=='' and trim($rowre['CTD_CUST_NO2'])=='')
						{
							echo '<td>'."第三次出貨回收,客戶:".$rowre['C1'].'</td>';
							break;
						}
				
					echo "出現就是錯誤";
				}
				if(trim($rowre['DHN_OUT_DATE3'])=='')
				{
					if(trim($rowre['DHN_OUT_DATE2'])<>'' and trim($rowre['DHN_TYS_RCV_DATE2'])=='')
					{
						$up1="update DRUM_HISTORY_NORMAL
						set DHN_WL_RCV_DATE2='".$str[$j][2]."',DHN_TYS_RCV_DATE2='".$str[$j][2]."'
						where DHN_DRUM_NO='".$str[$j][0]."' and (DISABLE = 0 or DISABLE is null)";
						$resultup1 = mssql_query($up1);
						if(trim($rowre['CTD_CUST_NO2'])<>'')
						{
							echo '<td>'."第二次出貨回收,客戶:".$rowre['C2'].'</td>';
							break;
						}
						if(trim($rowre['CTD_CUST_NO2'])=='')
						{
							echo '<td>'."第二次出貨回收,客戶:".$rowre['C1'].'</td>';
							break;
						}
					}
					if(trim($rowre['DHN_OUT_DATE2'])=='')
					{
						if(trim($rowre['DHN_OUT_DATE1'])<>'' and trim($rowre['DHN_TYS_RCV_DATE1'])=='')
						{
							$up1="update DRUM_HISTORY_NORMAL
							set DHN_WL_RCV_DATE1='".$str[$j][2]."',DHN_TYS_RCV_DATE1='".$str[$j][2]."'
							where DHN_DRUM_NO='".$str[$j][0]."' and (DISABLE = 0 or DISABLE is null)";
							$resultup1 = mssql_query($up1);
							echo '<td>'."第一次出貨回收,客戶:".$rowre['C1'].'</td>';
							break;
						}
						if(trim($rowre['DHN_OUT_DATE1'])=='')
						{
							echo '<td>'."無出貨紀錄".'</td>';
							break;
						}
					}
				}
				/*if(($rowre['DHN_OUT_DATE1']<>'' and $rowre['DHN_TYS_RCV_DATE1']<>'') or ($rowre['DHN_OUT_DATE2']<>'' and $rowre['DHN_TYS_RCV_DATE2']<>'') or($rowre['DHN_OUT_DATE3']<>'' and $rowre['DHN_TYS_RCV_DATE3']<>'') )
				{
					echo '<td>'."無出貨紀錄".'</td>';
					break;
					echo "出現就是錯誤";
				}*/
				echo '<td>'."無出貨紀錄".'</td>';
			}
				if(trim($rowre['DHN_DISCARD_DATE'])<>'')
				{
					echo '<td>'."此桶已廢棄".'</td>';
					break;
				}
			
		}
				
		
			
		
		
		if($str[$j][1]==3)
		{
			if(trim($rowre['DHN_DISCARD_DATE'])=='')
			{	
				$query="update DRUM_HISTORY_NORMAL set DHN_TYS_RCV_DATE1='".$str[$j][2]."' where DHN_DRUM_NO='".$str[$j][0]."' and (DISABLE = 0 or DISABLE is null)";
				$result=mssql_query($query);
				if(trim($rowre['DHN_OUT_DATE1'])<>'' and  trim($rowre['DHN_TYS_RCV_DATE1'])=='')
				{
					$up2="update DRUM_HISTORY_NORMAL 
					set DHN_DISCARD_DATE='".$str[$j][2]."',DHN_TYS_RCV_DATE1='".$str[$j][2]."',DHN_CF_DISCARD_DATE='".$str[$j][2]."'
					where DHN_DRUM_NO='".$str[$j][0]."' and (DISABLE = 0 or DISABLE is null)";
					$resultup2 = mssql_query($up2);
					echo '<td>'."".$str[$j][0]."已登記廢棄+回收1".'</td>';
					break;
				}
				if(trim($rowre['DHN_OUT_DATE2'])<>'' and  trim($rowre['DHN_TYS_RCV_DATE2'])=='')
				{
					$up2="update DRUM_HISTORY_NORMAL 
					set DHN_DISCARD_DATE='".$str[$j][2]."',DHN_TYS_RCV_DATE2='".$str[$j][2]."',DHN_CF_DISCARD_DATE='".$str[$j][2]."'
					where DHN_DRUM_NO='".$str[$j][0]."' and (DISABLE = 0 or DISABLE is null)";
					$resultup2 = mssql_query($up2);
					echo '<td>'."".$str[$j][0]."已登記廢棄+回收2".'</td>';
					break;
				}
				if(trim($rowre['DHN_OUT_DATE3'])<>'' and  trim($rowre['DHN_TYS_RCV_DATE3'])=='')
				{
					$up2="update DRUM_HISTORY_NORMAL 
					set DHN_DISCARD_DATE='".$str[$j][2]."',DHN_TYS_RCV_DATE3='".$str[$j][2]."',DHN_CF_DISCARD_DATE='".$str[$j][2]."'
					where DHN_DRUM_NO='".$str[$j][0]."' and (DISABLE = 0 or DISABLE is null)";
					$resultup2 = mssql_query($up2);
					echo '<td>'."".$str[$j][0]."已登記廢棄+回收3".'</td>';
					break;
				}
				$up2="update DRUM_HISTORY_NORMAL 
					set DHN_DISCARD_DATE='".$str[$j][2]."',DHN_CF_DISCARD_DATE='".$str[$j][2]."'
					where DHN_DRUM_NO='".$str[$j][0]."' and (DISABLE = 0 or DISABLE is null)";
					echo '<td>'."".$str[$j][0]."已登記廢棄".'</td>';
					$resultup2 = mssql_query($up2);
			
			}
			if(trim($rowre['DHN_DISCARD_DATE'])>0)
				{
					echo '<td>'."此桶已廢棄".'</td>';
					break;
				}

						
		}
		if($str[$j][1]==2)
		{
			echo '<td>'."轉用已不使用".'</td>';
			break;
		}
	}//while
	
}
?>