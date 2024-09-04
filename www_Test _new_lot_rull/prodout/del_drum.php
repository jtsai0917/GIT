<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php

session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
lasturl();
datepick();
$_SESSION['day']=date("Ymd");
$date3=$_SESSION['day'];
		$date4=strtotime("-3 day",strtotime($date3));
		$date2=date("Ymd",$date4);
		$_SESSION['day1']=$date2;
		auth('2-15',$_SESSION['aut']);
?>
<br />
請輸入決定書號碼
<form action="<?php echo $loginFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">

<input name="OTD" type="text" id="OTD" size="10" value="<?php $_SESSION['OTD'] ?>">
<input type="submit" name="upload" id="upload" value="  送出  " /><br />


<?php


if(isset($_POST['upload']))
{
	$query="select * from OUT_CHECK_DRUM where OTD_NO='".$_POST['OTD']."' ";
	$result = mssql_query($query);
			$numRows = mssql_num_rows($result);
			while($row = mssql_fetch_array($result))
			{
				
				if($_SESSION['day1']<=$row['OCM_CHK_DATE'])
				{
					$chk='ok';	
				}
			}
			$chk='ok';
	if($chk!='ok')
	{
		echo "此決定書號碼超過三天";
		
		
	}
	
	if($chk=='ok')
	{	$_SESSION['OTD']=$_POST['OTD'];
			echo '<input type="submit" name="golot" id="golot" value=" 下一步 " />';

		$query=" SELECT DISTINCT OCD_LOT_NO from OUT_CHECK_DRUM_DETAIL where OTD_NO='".$_POST['OTD']."' ";
		//echo $query;
		$result = mssql_query($query);
		$numRows = mssql_num_rows($result);
		 echo '<table border="1" width="600">';
			 echo '<tr height="">';
			 echo  '<td>'."LOT NO".'</td>';
			 echo  '<td>'."勾選".'</td>';

			 
			 $num=0;
		while($row = mssql_fetch_array($result))
		{	echo '<tr>';
			 echo  '<td>'.$row['OCD_LOT_NO'].'</td>';
			 echo  '<td>'.'<input type="radio" name="lot" value="'.$row['OCD_LOT_NO'].'">'.'</td>';
		}
	}
}
if(isset($_POST['golot']))
{
	$_SESSION['lot']=$_POST['lot'];
		$chkbox=0;
		echo '<input type="submit" name="del_chk" id="del_chk" value="  確認刪除  " />';
		 echo '<table border="1" width="60%">';
			 echo '<tr height="">';
			 echo  '<td>'."LOT NO".'</td>';
			  echo  '<td>'."桶號".'</td>';
			   echo  '<td>'."勾選".'</td>';
			   
		$query="select * from OUT_CHECK_DRUM_DETAIL where OCD_LOT_NO='".$_POST['lot']."'  and OTD_NO='".$_SESSION['OTD']."' ";
		//echo $query;
		$result = mssql_query($query);
		$numRows = mssql_num_rows($result);
		while($row = mssql_fetch_array($result))
		{
			echo '<tr>';
			 echo  '<td>'.$row['OCD_LOT_NO'].'</td>';
			  echo  '<td>'.$row['OCD_DRUM_NO'].'</td>';
			   echo  '<td>'.'<input type="checkbox" name='.$chkbox.' value="'.$row['OCD_DRUM_NO'].'">'.'</td>';
					$chkbox++;
		}
		echo "請輸入刪除原因:".'<input type="text" name="reason" size="60%">';
	$_SESSION['chkbox']=$chkbox;
}


if(isset($_POST['del_chk']))
{
	for($i=0;$i<$_SESSION['chkbox'];$i++)
	{
		if($_POST[$i]!='')
		{
			$query="DELETE FROM  OUT_CHECK_DRUM_DETAIL 
			where OCD_DRUM_NO='".$_POST[$i]."' and OTD_NO='".$_SESSION['OTD']."' and OCD_LOT_NO='".$_SESSION['lot']."' ";
			$result = mssql_query($query);
			echo '<br>'."已刪除桶號:".$_POST[$i];
			$query1="select * from  DRUM_HISTORY_NORMAL where DHN_DRUM_NO='".$_POST[$i]."' and 
			DHN_LOT_NO1='".$_SESSION['lot']."' ";
			$result1 = mssql_query($query1);
			$numRows1 = mssql_num_rows($result1);
			if($numRows1>0)
			{
				$query="UPDATE  DRUM_HISTORY_NORMAL SET CTD_CUST_NO1='',PDD_PROD_NO1='',DHN_LOT_NO1='',DHN_OUT_DATE1=''
				where  DHN_DRUM_NO='".$_POST[$i]."' ";
							$result = mssql_query($query);
							//echo "以刪除DHN_LOT_NO1".'</br>';
							$times=1;

			}
			/////
			$query2="select * from  DRUM_HISTORY_NORMAL where DHN_DRUM_NO='".$_POST[$i]."' and 
			DHN_LOT_NO2='".$_SESSION['lot']."' ";
			$result2 = mssql_query($query2);
			$numRows2 = mssql_num_rows($result2);
			if($numRows2>0)
			{
				$query="UPDATE  DRUM_HISTORY_NORMAL SET CTD_CUST_NO2='',PDD_PROD_NO2='',DHN_LOT_NO2='',DHN_OUT_DATE2=''
				where  DHN_DRUM_NO='".$_POST[$i]."' ";
							$result = mssql_query($query);
							//echo "以刪除DHN_LOT_NO2".'</br>';
							$times=2;
			}
			$query3="select * from  DRUM_HISTORY_NORMAL where DHN_DRUM_NO='".$_POST[$i]."' and 
			DHN_LOT_NO3='".$_SESSION['lot']."' ";
			$result3 = mssql_query($query3);
			$numRows3 = mssql_num_rows($result3);
			if($numRows3>0)
			{
				$query="UPDATE  DRUM_HISTORY_NORMAL SET CTD_CUST_NO3='',PDD_PROD_NO3='',DHN_LOT_NO3='',DHN_OUT_DATE3=''
				where  DHN_DRUM_NO='".$_POST[$i]."' ";
							$result = mssql_query($query);
							//echo "以刪除DHN_LOT_NO3".'</br>';
							$times=3;

			}
			$query="insert into DRUM_DEL (uid,otd_no,lot_no,drum_no,del_time,times,reason) values ('".$_SESSION['uid']."','".$_SESSION['OTD']
			."','".$_SESSION['lot']."','".$_POST[$i]."','".date("Ymdhis")."','".$times."','".$_POST['reason']."')";
										$result = mssql_query($query);

			//
		}
	}
	
}

?>

