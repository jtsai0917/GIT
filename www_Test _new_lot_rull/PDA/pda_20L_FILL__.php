<?php
session_start();
include("../connections/conn.php");
include ("../lib/fun.php");
include ("../lib/jtsai.php");
datepick();

$_SESSION['sourcelotno']=$_POST['sourcelotno'];
if($_GET['FDC4']<>''){
	$_SESSION['FDC4']=$_GET['FDC4'];	
}
$_SESSION['dno']=trim($_POST['dno']);
$_SESSION['lot']=trim($_POST['lot']);
if($_SESSION['lot']==''){my_msg("沒有輸入Lot NO","pda_20L_Fill.php");}
$a=substr($_SESSION['lot'],0,1)."S";
$b=substr($_SESSION['dno'],0,2);
if($a<>$b){my_msg("錯誤桶號:".$_SESSION['dno'],"pda_20L_Fill.php");}
$lotno=trim($_POST['lot']);
	$sma_id=trim($_POST['dno']);
	$sma_short=substr($sma_id,1,2);
	// check if fit rule
	$pdd=new get_from_lot_no;
	$pdd->lid=$lotno;
	$pdd->ani();
	$pdd_short_name= $pdd->pdd_prod_short_name;
	$sma_s1=substr($pdd_short_name,0,1);
	$sma_id1=substr($sma_id,0,1);
if($sma_s1<>$sma_id1){
//	echo "<BR>".$pdd_short_name."<BR>";
	my_msg("桶號不符規則","pda_20L_FILL.php");
}



if($_SESSION['font_size']==''){$_SESSION['font_size']=20;}

if(isset($_POST['next1']))
{
	$_SESSION['makeno']=trim($_POST['makeno']);
}
if(trim($_POST['dno'])<>'' and trim($_POST['lot'])<>'')
	{
		$str1=' autofocus="autofocus" ';
		$str2='';
		$str3='';
	}
	elseif(trim($_POST['dno'])=='' and $_POST['lot']<>'')
	{
		$str1='';
		$str3='';
		$str2=' autofocus="autofocus" ';
	}
	else {
		$str1='';
		$str3=' autofocus="autofocus" ';
		$str2='';
	}
	
$query="select FID_FILL_BEGIN_DATE  FROM FILL_INDICATE WHERE   (FDM_LOT_NO = '".$_POST['lot']."')";
$result=mssql_query($query);
$row=mssql_fetch_row($result);
if($row[0]==''){
	$query="update FILL_INDICATE set FID_SOURCE_LOT= '".$_SESSION['sourcelotno']."', FID_FILL_BEGIN_DATE='".date("YmdHis")."' WHERE   (FDM_LOT_NO = '".$_POST['lot']."')";
	$result=mssql_query($query);
}
?><head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>無標題文件</title>
<style type="text/css">
body,td,th {
	font-size:<?php echo $_SESSION['font_size'];?>px;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
</style>
<a href="fill.php"><img src="../pics/TYS.jpg" alt="" width="50" height="28" /></a>人員：<?php echo $_SESSION['uname']?>
<form id="form0" name="form0" method="post">
<input type="submit" name="_fill" style="width:120px;height:30px;border:2px #666666 double; background-color:#FF0; font-size:18px" value="充填" >
&nbsp;&nbsp;
<input type="submit" name="_wash" style="width:120px;height:30px;border:2px #666666 double; background-color:#CCC; font-size:18px" value="洗淨"><BR>
<input type="text" name="lot" hidden="hidden" value="<?php echo $_POST['lot'];?>" ?>
</form>
 20L PE 充填作業.
  <br>
   日期: <?PHP echo date("Y-m-d"); ?>
  <br>
<?php	
	$_SESSION['sttime']=date("YmdHis");
	echo "客戶名:".$_SESSION['cus1'].'<br />';
	echo "充填量:基準值20.0~20.2kg"."<br />";
	echo '<form id="form1" name="form1" method="post" action="'.$loginFormAction.'">';
	echo "Lot NO:".$_POST['lot']."<BR>";
	echo "DRUM NO:".$_POST['dno']."<BR>";
	echo '<input type="text" name="lot" hidden="hidden" value="'.$_POST['lot'].'" />';
	echo '<input type="text" name="sourcelotno" hidden="hidden" value="'.$_POST['sourcelotno'].'" />';
	echo '<input type="text" name="dno" hidden="hidden" value="'.$_POST['dno'].'" />';
if(isset($_POST['fill']))
{
		$find="select top 1 C2D_SERIAL_NO from [20L_FILL_CHECK_DRUM] where FDM_LOT_NO='".$_POST['lot']."' order by C2D_SERIAL_NO 
			desc";
			$resultf = mssql_query($find);
			$numRowsf = mssql_num_rows($resultf);
			
			while($rowf = mssql_fetch_array($resultf))
			{
				$USE=$rowf['C2D_SERIAL_NO']+1;
			}
			if($numRowsf==0)
			{
				$USE=1;
			}
			$inser="update [20L_FILL_CHECK_DRUM] set C2D_B_CHK_VALIDATE='Y', SOURCE_LOT_NO= '".$_SESSION['sourcelotno']."', 
			C2D_B_CHK_SURFACE='Y',C2D_B_CHK_DRUM_IN='Y',C2D_B_CHK_FILL_LINK='Y',C2D_B_CHK_COMPONENT='Y',
			C2D_B_CHK_LABEL='Y',C2D_W_CHK_WASHER='Y',C2D_W_CHK_WATER='Y',C2D_FILLER='".$_SESSION['uid']."' where FDM_LOT_NO='".$_POST['lot']."' and C2D_DRUM_NO='".$_SESSION['dno']."'";
//			echo $inser;
			$resulti = mssql_query($inser);
			$find2="select * from DRUM_FILL_DAY_CHECK where DDC_CHK_DATE='".date("Ymd")."' and FDM_LOT_NO='".$_SESSION['lot']."' ";
			$_SESSION['f2']=$find2;
			$resultf2 = mssql_query($find2);
			$numRowsf2 = mssql_num_rows($resultf2);
			$_SESSION['chkifpack']=$numRowsf2;
			if($numRowsf2==0)
			{						
				echo "IPA管內purge(20kg以上):".'<input type="text" name="DFDC1" id="1"  style="font-size:20px">'.'</br>';
				echo "重量計Check".'<input type="text" name="DFDC3" id="3"  style="font-size:20px">'.'</br>';
				echo "重量計set".'<input type="text" name="DFDC2" id="2"  style="font-size:20px">'.'</br>';
				echo '<input type="checkbox" name="chk1" value="1" checked="checked">'."空桶重量1.7KG以下".'</br>'; 
				echo "充填量Check(KG):".'<input type="text" name="DFDC4" autofocus="autofocus" id="4"  style="font-size:20px" value="">'.'</br>';
				echo '<input type="checkbox" checked="checked" name="chk2" value="1">'."桶上層擦拭(無汙穢)".'</br>'; 
				echo '<input type="submit" name="pack" id="pack" style="font-size:20px" value="捆包" /><br>';
			}
			if($numRowsf2>0)
			{
				echo '<input type="checkbox"  checked="checked" name="chk1" value="1"  style="font-size:20px">'."空桶重量1.7KG以下".'</br>'; 
				echo "充填量Check(KG):".'<input type="text" name="DFDC4" id="4" style="font-size:20px"  value="'.$_SESSION['DFDC4'].'"></br>';
				echo '<input type="checkbox"  checked="checked" name="chk2" value="1"  style="font-size:20px">'."桶上層擦拭(無汙穢)".'</br>'; 
				echo '<input type="submit" name="pack1" id="pack1" value="捆包" autofocus="autofocus"  style="font-size:20px"/><br>';
			}
			echo '</form>';
}

if(isset($_POST['pack'])){
	if($_POST['DFDC1']=='' or $_POST['DFDC3']=='' or $_POST['DFDC2']=='' or $_POST['DFDC4']==''){my_msg("任一欄位不可為空值","pda_20L_Fill.php");}
}

if(isset($_POST['pack']) or isset($_POST['pack1']))
{	$inser="update [20L_FILL_CHECK_DRUM] set C2D_B_CHK_VALIDATE='Y', SOURCE_LOT_NO= '".$_SESSION['sourcelotno']."', C2D_F_CHK_DM_EMPTY='Y', 
			C2D_B_CHK_SURFACE='Y',C2D_B_CHK_DRUM_IN='Y',C2D_B_CHK_FILL_LINK='Y',C2D_B_CHK_COMPONENT='Y',C2D_F_QTY= ".$_POST['DFDC4'].",
			C2D_B_CHK_LABEL='Y',C2D_W_CHK_WASHER='Y',C2D_W_CHK_WATER='Y',C2D_FILLER='".$_SESSION['uid']."' where FDM_LOT_NO='".$_POST['lot']."' and C2D_DRUM_NO='".$_SESSION['dno']."'";
//			echo $inser;
			$resulti = mssql_query($inser);
	
	if($_POST['DFDC4']<>''){$_SESSION['DFDC4']=$_POST['DFDC4'];}
	if($_POST['20L']<>''){$_SESSION['DFDC4']=$_POST['20L'];}
	$query="SELECT  DDC_CHK_DATE FROM DRUM_FILL_DAY_CHECK WHERE   (FDM_LOT_NO = '".$_SESSION['lot']."')";
	$result=mssql_query($query);
	$numRows=mssql_num_rows($result);
	$row=mssql_fetch_row($result);
	if($numRows==0)
	{
		$pack="Insert Into DRUM_FILL_DAY_CHECK (DDC_CHK_DATE, FDM_LOT_NO,PDD_PROD_NO,DDC_PURGE,DDC_SCALES_SET,DDC_CHK_SCALES)
		values ('".date("Ymd")."','".$_SESSION['lot']."','".$_SESSION['prod']."','".$_POST['DFDC1']."','".$_POST['DFDC2']."',
		'".$_POST['DFDC3']."')";
		$result = mssql_query($pack);
	}
	elseif($row[0]==''){
		
		$query="update DRUM_FILL_DAY_CHECK set DDC_CHK_DATE ='".date("Ymd")."' WHERE   (FDM_LOT_NO = '".$_SESSION['lot']."')";
	//	echo "<BR>".$query."<BR>";
		$result=mssql_query($query);
	}
			echo '<input type="text" name="20L" id="4" hidden="hidden"value="'.$_POST['20L'].'">';
			echo '<input type="text" name="DFDC4" id="4" hidden="hidden"value="'.$_POST['DFDC4'].'">';
			echo '<input type="checkbox" checked="checked" name="chk1" id="chk1" value="1" />'."桶蓋及外表無汙穢".'</br>'; 
			echo '<input type="checkbox" checked="checked" name="chk2" id="chk2" value="1" />'."充填口無洩漏".'</br>'; 
			echo '<input type="checkbox" checked="checked" name="chk3" id="chk3" value="1" />'."標籤檢查(無脫落或破損)".'</br>'; 
			echo '<input type="checkbox" checked="checked" name="chk4" id="chk4" value="1" />'."Cap chech(無汙染,變形,變色,損壞)".'</br>'; 
			echo '<input type="checkbox" checked="checked" name="chk5" id="chk5" value="1" />'."桶上層擦拭(無汙穢)".'</br>'; 
			echo '<input type="submit" name="endnext1" autofocus="autofocus" id="endnext1" style="font-size:20px" value="下一桶" />';
			echo '<input type="submit" name="exit1" id="exit1"  style="font-size:20px" value="結束" /><br>';
			echo '</form>';
			if($smp_chk!='1')
			{
				$_SESSION['SMP']='N';
			}
				
}

if(isset($_POST['exit1']))//要改成UPDATE
{
	
			$inser="update [20L_FILL_CHECK_DRUM] set C2D_F_CHK_DM_EMPTY='Y',
			C2D_F_QTY='".$_SESSION['DFDC4']."',C2D_F_CHK_CAP_CLR='Y',C2D_F_SAMPLING='".$_SESSION['SMP']."',
			C2D_P_CHK_SURFACE='Y',C2D_P_CHK_LEAK='Y',C2D_P_CHK_LABEL='Y',C2D_P_CHK_CAP='Y'
			";
		//	echo $inser;
			$resulti = mssql_query($inser);
			
			$queryf="select * from FILL_INDICATE where FDM_LOT_NO='".$_SESSION['lot']."' ";
			$resultf = mssql_query($queryf);
			$numRowsf = mssql_num_rows($resultf);
			if($numRowsf>0)
			{
			$query="update FILL_INDICATE set FID_SOURCE_LOT= '".$_SESSION['sourcelotno']."', FID_FILL_END_DATE='".date("Ymdhis")."',
			FID_QTY=FID_QTY+20,FID_OPERATOR='".$_SESSION['uid']."' where FDM_LOT_NO='".$_SESSION['lot']."'
			";
			$result = mssql_query($query);
			}
			if($numRowsf==0)
			{
			$query="Insert Into FILL_INDICATE 
			 (FID_SOURCE_LOT,FDM_LOT_NO,PDD_PROD_NO,FID_FILL_BEGIN_DATE,FID_FILL_END_DATE,FID_QTY,FID_SAM_COUNT,FID_OPERATOR)
			 values ('".$_SESSION['sourcelotno']."','".$_SESSION['lot']."','".$_SESSION['prod']."','".date("Ymdhis")."',
			 '".date("Ymdhis")."','20','".$_SESSION['sampn']."','".$_SESSION['uid']."')";
			 $result = mssql_query($query);
			}

			$query="update PRODUCT_RUNNING_ACCOUNT set PRA_FAKE_IN_DATE2='".date("Ymd")."' ,PRA_REAL_IN_DATE='".date("Ymd")."',
			PRA_IN_DM=PRA_IN_DM+1,PRA_REAL_IN_QTY=PRA_REAL_IN_QTY+20 
			where PRA_LOT_NO='".$_SESSION['lot']."' and CTD_CUST_NO='".$_SESSION['csn']."' ";
						$result = mssql_query($query);		
 			jumpto($_SESSION['index']);
}

if(isset($_POST['endnext1']))
	{
			if($_POST['DFDC4']<>''){
				$_POST['DFDC4']=$_POST['DFDC4'];
			}
			elseif($_POST['20L']<>''){
				$_POST['DFDC4']=$_POST['20L'];
			}
			$query="UPDATE     [20L_FILL_CHECK_DRUM] SET C2D_F_CHK_DM_EMPTY = 'Y',  C2D_F_CHK_CAP_CLR = 'Y', C2D_F_SAMPLING = 'Y', C2D_P_CHK_SURFACE = 'Y', C2D_P_CHK_LEAK = 'Y',C2D_P_CHK_LABEL = 'Y', C2D_P_CHK_CAP = 'Y' WHERE (FDM_LOT_NO = '".$_POST['lot']."') AND (C2D_DRUM_NO = '".$_POST['dno']."') ";
			$result=mssql_query($query);
			
			
			$query="update FILL_INDICATE set FID_SOURCE_LOT= '".$_SESSION['sourcelotno']."',FID_FILL_END_DATE='".date("Ymdhis")."',
			FID_QTY=FID_QTY+20,FID_OPERATOR='".$_SESSION['uid']."' where FDM_LOT_NO='".$_SESSION['lot']."'
			";
			$result = mssql_query($query);
			
			$query="update PRODUCT_RUNNING_ACCOUNT set PRA_FAKE_IN_DATE2='".date("Ymd")."' ,PRA_REAL_IN_DATE='".date("Ymd")."',
			PRA_IN_DM=PRA_IN_DM+1,PRA_REAL_IN_QTY=PRA_REAL_IN_QTY+20 
			where PRA_LOT_NO='".$_SESSION['lot']."' and CTD_CUST_NO='".$_SESSION['csn']."' ";
			$result = mssql_query($query);
 			jumpto("pda_20L_FILL_.php?&lot=".$_POST['lot']);	
	} 
if(isset($_POST['_fill'])){
	jumpto("pda_20L_FILL.php");
}
if(isset($_POST['_wash'])){
	jumpto("pda_20L_WASH.php");
}?>
