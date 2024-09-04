<meta http-equiv="Content-Type" content="text/html; charset=big5" />
	<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");

datepick();

?>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">

查詢功能:輸入前兩碼(EX:PA or PT)
<input type="text" id="chk" value='' name="chk" >
<input type="submit" id="go" value="查詢" name="go" >	
</br><br /><br />


輸入棧板開始編號:
<input type="text" id="newbord" name="newbord" >&nbsp;&nbsp;<br />
輸入棧板數量: <input type="text" id="newbord" TextMode="Number" value="1"  name="number" style="width:40" >
<br />
選擇啟用時間:
 <input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php if($_SESSION['datepicker1']){echo $_SESSION['datepicker1'];}?>" onChange="set_date_session(this.name,this.value)">&nbsp;&nbsp;
 <input type="submit" id="new" value="確定新增" name="go1"  />

 

 
  <input type="submit" id="leave" value="離開" name="leave"  /><br />

<?php 
if(isset($_POST['go']))
{
	$find="select top 1 BOD_NO from BOARD where BOD_NO like  '%".$_POST['chk']."%'  order by BOD_NO desc";
	$resultf = mssql_query($find);
				$numrowsf = mssql_num_rows($resultf);
				while($rowf=mssql_fetch_array($resultf))
				{	echo '</br>'.'</br>';
					echo "最新的棧板:".$rowf['BOD_NO'].'<br />';
;
					
				}
				
						
}
if(isset($_POST['go1']))
{
if(strlen($_POST['newbord'])==5)
{
	$PA=substr($_POST['newbord'],0,2);
	$numb=substr($_POST['newbord'],2,3);
	$num1000="1".$numb;
	
	$date1=dod($_SESSION['datepicker1']);
	$chk="select * from BOARD where BOD_NO='".$_POST['newbord']."'";
	$resultchk = mssql_query($chk);
	$numrowschk = mssql_num_rows($resultchk);
	if($numrowschk==0)
	{
	for($i=0;$i<$_POST['number'];$i++)
	{	
		$numb=$num1000-1000;
		$numberAsString = str_pad($numb, 3, '0', STR_PAD_LEFT);
		$bordno=$PA.$numberAsString;
		
		
		$newb="insert into BOARD_ALL (BOA_NO,BOA_TIMES,BOA_BEGIN_DATE) VALUES ('".$bordno."','1','".date("Ymd")."') ";
		$newa="insert into BOARD (BOD_NO,BOD_BEGIN_DATE) VALUES ('".$bordno."','".date("Ymd")."')";
		if($_SESSION['datepicker1']<>'')
		{
			$newb="insert into BOARD_ALL (BOA_NO,BOA_TIMES,BOA_BEGIN_DATE) VALUES ('".$bordno."','1','".$date1."') ";
			$newa="insert into BOARD (BOD_NO,BOD_BEGIN_DATE) VALUES ('".$bordno."','".$date1."')";
			echo "已新增".$bordno.'<br>';
		}
		
				
		$resulta = mssql_query($newa);
		$resultb = mssql_query($newb);
		$num1000++;

	}
	}
	if($numrowschk>0)
	{
		echo "重複棧板";
	}
}
else
{
	echo "棧板編號錯誤";
}
}
if(isset($_POST['leave']))
{
header("Location: " . $_SESSION['lasturl'] );
}
?>