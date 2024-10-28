<?php
session_start();
include("../connections/conn.php");
include ("../lib/fun.php");
include ("../lib/jtsai.php");
datepick();
if($_SESSION['font_size']==''){$_SESSION['font_size']=20;}
	
if(isset($_POST['enter']))
{
	$_SESSION['sttime']=date("YmdHis");
	$_SESSION['dno']=trim($_POST['dno']);
	$_SESSION['lot']=strtoupper(trim($_POST['lot']));
}
if(isset($_POST['next1']))
{
	$_SESSION['makeno']=trim($_POST['makeno']);
}

if(trim($_SESSION['dno'])<>'' and trim($_SESSION['lot'])<>'')
	{
		$str1=' autofocus="autofocus" ';
		$str2='';
		$str3='';
	}
	elseif(trim($_SESSION['dno'])=='' and $_SESSION['lot']<>'')
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
</head>
<a href="fill.php"><img src="../pics/TYS.jpg" alt="" width="50" height="28" /></a>人員：<?php echo $_SESSION['uname']?>
<form id="form0" name="form0" method="post">
<input type="submit" name="_fill" style="width:120px;height:30px;border:2px #666666 double; background-color:#CCC; font-size:18px" value="充填">
&nbsp;&nbsp;
<input type="submit" name="_wash" style="width:120px;height:30px;border:2px #666666 double; background-color:#FF0; font-size:18px" value="洗淨"><BR>
</form>
 20L PE 洗淨作業.
  <br>
   日期: <?PHP echo date("Y-m-d"); ?>
  <br>
<?php
	$i=1;
	
 echo "客戶名:".$_SESSION['cus1'].'<br />';
 echo "	充填量:基準值20.0~20.2kg"."<br />";
 echo '<form id="form1" name="form1" method="post" action="'.$loginFormAction.'">';
 echo  " Lot NO:". '<input type="text" name="lot" id="lot"  size="14" autocomplete="off"  style="font-size:20px" value="'.$_SESSION['lot'].'" 
 /><BR>';
 echo "Drum NO:".'<input type="text" name="dno" id="dno"  size="12" autocomplete="off" '.$str2.' style="font-size:20px" value="'.$_SESSION['dno'].'"
 />';
 echo '<input type="submit" name="enter"  style="font-size:20px" id="enter" value="送出" />'.'</br>';

			echo '<input type="hidden" name="makeno" id="makeno"  style="font-size:20px" value="'.$_POST['makeno'].'">';
			echo '<input type="hidden" name="DFDC1" id="1"  style="font-size:20px" value="'.$_POST['DFDC1'].'">';
				echo '<input type="hidden" name="DFDC3" id="3" width="50" height="20" value="'.$_POST['DFDC3'].'">';
				echo '<input type="hidden" name="DFDC2" id="2" width="50" height="20" value="'.$_POST['DFDC2'].'">';
				echo '<input type="hidden" name="DFDC4" id="4" width="50" height="20" value="'.$_POST['DFDC4'].'">';
				echo '<input type="hidden" name="smp_no" id="smp_no" width="50" height="20" value="'.$_POST['smp_no'].'">';
				$_SESSION['DFDC1']=$_POST['DFDC1'];
				$_SESSION['DFDC2']=$_POST['DFDC2'];
				$_SESSION['DFDC3']=$_POST['DFDC3'];
				$_SESSION['DFDC4']=$_POST['DFDC4'];
				$_SESSION['smp_no']=$_POST['smp_no'];
				
if(isset($_POST['enter'])or isset($_POST['next1']) or isset($_POST['endnext1']))//1
{	
	if(isset($_POST['endnext1']))
	{
		/*
			$inser="update [20L_FILL_CHECK_DRUM] set C2D_F_CHK_DM_EMPTY='Y',
			C2D_F_QTY='".$_SESSION['DFDC4']."',C2D_F_CHK_CAP_CLR='Y',C2D_F_SAMPLING='".$_SESSION['SMP']."',
			C2D_P_CHK_SURFACE='Y',C2D_P_CHK_LEAK='Y',C2D_P_CHK_LABEL='Y',C2D_P_CHK_CAP='Y'";
		//	echo $inser;
			$resulti = mssql_query($inser);
			*/
			
			$query="update FILL_INDICATE set FID_FILL_END_DATE='".date("Ymdhis")."',
			FID_QTY=FID_QTY+20,FID_OPERATOR='".$_SESSION['uid']."' where FDM_LOT_NO='".$_SESSION['lot']."'
			";
			$result = mssql_query($query);
			
			
			$query="update PRODUCT_RUNNING_ACCOUNT set PRA_FAKE_IN_DATE2='".date("Ymd")."' ,PRA_REAL_IN_DATE='".date("Ymd")."',
			PRA_IN_DM=PRA_IN_DM+1,PRA_REAL_IN_QTY=PRA_REAL_IN_QTY+20 
			where PRA_LOT_NO='".$_SESSION['lot']."' and CTD_CUST_NO='".$_SESSION['csn']."' ";
			$result = mssql_query($query);
			
			
	} 
	if(isset($_POST['next1']))
	{
		$_SESSION['makeno']=trim($_POST['makeno']);
		$find="select top 1 C2D_SERIAL_NO from [20L_FILL_CHECK_DRUM] where FDM_LOT_NO='".$_POST['lot']."' order by C2D_SERIAL_NO 
			desc";
//		echo $find."<BR>";
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
			$inser="INSERT INTO [20L_FILL_CHECK_DRUM] (FDM_LOT_NO,C2D_DRUM_NO,C2D_SERIAL_NO,C2D_MAKELOT_NO,C2D_B_CHK_VALIDATE,
			C2D_B_CHK_SURFACE,C2D_B_CHK_DRUM_IN,C2D_B_CHK_FILL_LINK,C2D_B_CHK_COMPONENT,
			C2D_B_CHK_LABEL,C2D_W_CHK_WASHER,C2D_W_CHK_WATER,C2D_FILLER) VALUES ('".$_POST['lot']."','".$_SESSION['dno']."'
			,'".$USE."','".$_POST['makeno']."','Y','Y','Y','Y','Y','Y','Y','Y','".$_SESSION['uid']."' )";
			$resulti = mssql_query($inser);
			unset($_SESSION['dno']);
			my_msg($_SESSION['dno']."檢查完成");
			
	}
		
	$query="select CTD_CUST_NO from FILLPLAN_DRUM_CUSTOMER where FDM_LOT_NO='".$_SESSION['lot']."'";
	
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	
	while($row = mssql_fetch_array($result))
	{
		
		
		if($i==1)
		{
		$name=get_cust_name($row['CTD_CUST_NO']);
		
			$_SESSION['csn']=$row['CTD_CUST_NO'];
			$_SESSION['cus1']=$name;
			$i++;
		
		}
		
		
	}
	echo $numRows;
	if($numRows>0)
	{
		
		echo '<input type="submit"  style="font-size:20px" '.$str1.' name="next" id="next" value="下一步" />';
		echo '<input type="submit"  style="font-size:20px" name="exit" id="exit" value="離開" /><br>';
		echo '</form>';
	}
	if($numRows==0)
	{
// 20180419 修改
//		echo "LOT NO 輸入錯誤".'<br>';
		echo '<input type="submit"  style="font-size:20px" name="exit" id="exit" value="離開" /><br>';
		echo '</form>';
	}
	
	$FINDPDD="select PDD_PROD_NO from PRODUCT_RUNNING_ACCOUNT where PRA_LOT_NO='".$_SESSION['lot']."' ";
	$resultFD = mssql_query($FINDPDD);
	$numRowsFD = mssql_num_rows($resultFD);
	
	while($rowFD = mssql_fetch_array($resultFD))
	{
		$_SESSION['prod']=$rowFD['PDD_PROD_NO'];
	}
	
	if(isset($_POST['next1']))
	{
		unset($_SESSION['dno']);	
	}
	
}
  
if(isset($_POST['exit']))
{
	jumpto($_SESSION['index']);
}

if(isset($_POST['next']))
{	
	
	
	$query="select * from [20L_FILL_CHECK_DRUM] where FDM_LOT_NO='".$_SESSION['lot']."' and C2D_DRUM_NO='".$_SESSION['dno']."'";
//	echo $query."<BR>";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	
	$queryprod="select * from DRUM_HISTORY_NORMAL where DHN_DRUM_NO='".$_SESSION['dno']."' ";//看看有沒有這桶
//	echo $queryprod."<BR>";
	$resultprod = mssql_query($queryprod);
	$numRowsprod = mssql_num_rows($resultprod);
	while($rowprod = mssql_fetch_array($resultprod))
	{
		if(trim($rowprod['PDD_PROD_NO1'])==$_SESSION['prod'])
		{
			$prodchk=1;
		}
	}

if($numRows>0)
	{
		if($numRows>0)
		{
			echo "此桶已完成,請重新輸入";
		}
		
		echo '<br>'."輸入桶號".'<br>';
		
		echo '<input type="submit" name="next" id="next" value="下一步" />';
		echo '<input type="submit" name="exit" id="exit" value="離開" /><br>';
		echo '</form>';
		
	}

		
			
			
	//if(($numRows==0 and $prodchk==1) or $_SESSION['dno']=='TEST')
	
	{		
	
			echo '<form id="form2" name="form2" method="post" onsubmit="return checkform2(this);" action="'.$loginFormAction.'">';
			echo "桶號:".$_SESSION['dno'].'</br>';
			echo "製造LOT:".'<input type="text" name="makeno" id="makeno" value="'.$_SESSION['makeno'].'" style="font-size:20px">'.'</br>';
			echo '<input type="checkbox" checked="checked" name="chk1" id="chk1" value="1" />'."有效期限(未滿兩年)".'</br>'; 
			echo '<input type="checkbox" checked="checked" name="chk2" id="chk2" value="1" />'."外觀無汙穢,變形,損壞,變色,生鏽".'</br>'; 
			echo '<input type="checkbox" checked="checked" name="chk3" id="chk3" value="1" />'."桶內無汙穢,變形,損壞,變色,生鏽".'</br>'; 
			echo '<input type="checkbox" checked="checked" name="chk4" id="chk4" value="1" />'."口部無變形".'</br>'; 
			echo '<input type="checkbox" checked="checked" name="chk5" id="chk5" value="1" />'."部品無汙穢,變形,損壞,變色,生鏽".'</br>'; 
			echo '<input type="checkbox" checked="checked" name="chk6" id="chk6" value="1" />'."Lable 無脫落,破損".'</br>'; 
			 
			echo "沖洗工程".'</br>';
			echo '<input type="checkbox" checked="checked" name="chk7" id="chk7" value="1" />'."沖洗(五分鐘以上)".'</br>'; 
			echo '<input type="checkbox" checked="checked" name="chk8" id="chk8" value="1" />'."無殘液,無汙穢".'</br>'; 
			echo '<input type="submit"  style="font-size:20px" name="next1" autofocus="autofocus" id="next1" value="下一桶" />';
//			echo '<input type="submit"  style="font-size:20px" name="fill" id="fill" value="充填" /><br>';
			echo '</form>';
			

	}
	
}


if(isset($_POST['fill']))
{
		echo '<form id="form3" name="form3" method="post" onsubmit="return checkform3(this);" action="'.$loginFormAction.'">';

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
			$inser="INSERT INTO [20L_FILL_CHECK_DRUM] (FDM_LOT_NO,C2D_DRUM_NO,C2D_SERIAL_NO,C2D_MAKELOT_NO,C2D_B_CHK_VALIDATE,
			C2D_B_CHK_SURFACE,C2D_B_CHK_DRUM_IN,C2D_B_CHK_FILL_LINK,C2D_B_CHK_COMPONENT,
			C2D_B_CHK_LABEL,C2D_W_CHK_WASHER,C2D_W_CHK_WATER,C2D_FILLER) VALUES ('".$_POST['lot']."','".$_SESSION['dno']."'
			,'".$USE."','".$_POST['makeno']."','Y','Y','Y','Y','Y','Y','Y','Y','".$_SESSION['uid']."' )";
			//echo $inser;
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
				echo "充填量Check(KG):".'<input type="text" name="DFDC4" id="4"  style="font-size:20px">'.'</br>';
				echo '<input type="checkbox" checked="checked" name="chk2" value="1">'."桶上層擦拭(無汙穢)".'</br>'; 
				echo '<input type="submit" name="samp" id="samp" style="font-size:20px" value="取樣" />';
				echo '<input type="submit" name="pack" id="pack" style="font-size:20px" value="捆包" /><br>';
			}
			if($numRowsf2>0)
			{
				echo '<input type="checkbox"  checked="checked" name="chk1" value="1"  style="font-size:20px">'."空桶重量1.7KG以下".'</br>'; 
				echo "充填量Check(KG):".'<input type="text" name="20L" id="4"  style="font-size:20px">'.'</br>';
				echo '<input type="checkbox"  checked="checked" name="chk2" value="1"  style="font-size:20px">'."桶上層擦拭(無汙穢)".'</br>'; 
				echo '<input type="submit" name="samp" id="samp" value="取樣"  style="font-size:20px"/>';
				echo '<input type="submit" name="pack" id="pack" value="捆包"  style="font-size:20px"/><br>';
			}
			echo '</form>';
}
if(isset($_POST['pack']) or isset($_POST['pack1']))
{	
	$query="SELECT  DDC_CHK_DATE FROM DRUM_FILL_DAY_CHECK WHERE   (FDM_LOT_NO = '".$_SESSION['lot']."')";
	$result=mssql_query($query);
	$numRows=mssql_num_rows($result);
	$row=mssql_fetch_row($result);
	if($numRows==0)
	{
		$pack="Insert Into DRUM_FILL_DAY_CHECK (DDC_CHK_DATE,FDM_LOT_NO,PDD_PROD_NO,DDC_PURGE,DDC_SCALES_SET,DDC_CHK_SCALES)
		values ('".date("Ymd")."','".$_SESSION['lot']."','".$_SESSION['prod']."','".$_SESSION['DFDC1']."','".$_SESSION['DFDC2']."',
		'".$_SESSION['DFDC3']."')";
		echo "<BR>".$pack."<BR>";
					$result = mssql_query($pack);
	}
	elseif($row[0]==''){
		
		$query="update DRUM_FILL_DAY_CHECK set DDC_CHK_DATE ='".date("Ymd")."' WHERE   (FDM_LOT_NO = '".$_SESSION['lot']."')";
		echo "<BR>".$query."<BR>";
		$result=mssql_query($query);
	}
	
	
			echo '<form id="form4" name="form4" method="post" onsubmit="return checkform4(this);" action="'.$loginFormAction.'">';

			
			echo '<input type="checkbox" checked="checked" name="chk1" id="chk1" value="1" />'."桶蓋及外表無汙穢".'</br>'; 
			echo '<input type="checkbox" checked="checked" name="chk2" id="chk2" value="1" />'."充填口無洩漏".'</br>'; 
			echo '<input type="checkbox" checked="checked" name="chk3" id="chk3" value="1" />'."標籤檢查(無脫落或破損)".'</br>'; 
			echo '<input type="checkbox" checked="checked" name="chk4" id="chk4" value="1" />'."Cap chech(無汙染,變形,變色,損壞)".'</br>'; 
			echo '<input type="checkbox" checked="checked" name="chk5" id="chk5" value="1" />'."桶上層擦拭(無汙穢)".'</br>'; 
			echo '<input type="submit" name="endnext1" id="endnext1" style="font-size:20px" value="下一桶" />';
			echo '<input type="submit" name="exit1" id="exit1"  style="font-size:20px" value="結束" /><br>';
			echo '</form>';
			if($smp_chk!='1')
			{
				$_SESSION['SMP']='N';
			}
				
}
if(isset($_POST['exit1']))//要改成UPDATE
{
	$query="SELECT  DDC_CHK_DATE FROM DRUM_FILL_DAY_CHECK WHERE   (FDM_LOT_NO = '".$_SESSION['lot']."')";
	$result=mssql_query($query);
	$numRows=mssql_num_rows($result);
	$row=mssql_fetch_row($result);
	if($numRows==0)
	{
		$pack="Insert Into DRUM_FILL_DAY_CHECK (DDC_CHK_DATE,FDM_LOT_NO,PDD_PROD_NO,DDC_PURGE,DDC_SCALES_SET,DDC_CHK_SCALES)
		values ('".date("Ymd")."','".$_SESSION['lot']."','".$_SESSION['prod']."','".$_SESSION['DFDC1']."','".$_SESSION['DFDC2']."',
		'".$_SESSION['DFDC3']."')";
		echo "<BR>".$pack."<BR>";
					$result = mssql_query($pack);
	}
	elseif($row[0]==''){
		
		$query="update DRUM_FILL_DAY_CHECK set DDC_CHK_DATE ='".date("Ymd")."' WHERE   (FDM_LOT_NO = '".$_SESSION['lot']."')";
		echo "<BR>".$query."<BR>";
		$result=mssql_query($query);
	}
	
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
			$query="update FILL_INDICATE set FID_FILL_END_DATE='".date("Ymdhis")."',
			FID_QTY=FID_QTY+20,FID_OPERATOR='".$_SESSION['uid']."' where FDM_LOT_NO='".$_SESSION['lot']."'
			";
			$result = mssql_query($query);
			}
			if($numRowsf==0)
			{
			$query="Insert Into FILL_INDICATE
			 (FDM_LOT_NO,PDD_PROD_NO,FID_FILL_BEGIN_DATE,FID_FILL_END_DATE,FID_QTY,FID_SAM_COUNT,FID_OPERATOR)
			 values ('".$_SESSION['lot']."','".$_SESSION['prod']."','".$_SESSION['sttime']."',
			 '".date("Ymdhis")."','20','".$_SESSION['sampn']."','".$_SESSION['uid']."')";
			 $result = mssql_query($query);
				$_SESSION['testf']=$query;
			}

			
			$query="update PRODUCT_RUNNING_ACCOUNT set PRA_FAKE_IN_DATE2='".date("Ymd")."' ,PRA_REAL_IN_DATE='".date("Ymd")."',
			PRA_IN_DM=PRA_IN_DM+1,PRA_REAL_IN_QTY=PRA_REAL_IN_QTY+20 
			where PRA_LOT_NO='".$_SESSION['lot']."' and CTD_CUST_NO='".$_SESSION['csn']."' ";
						$result = mssql_query($query);

			
			jumpto($_SESSION['index']);
			
	
}
if(isset($_POST['samp']) or isset($_POST['nextsamp']))
{
	if( isset($_POST['nextsamp']))
	{$_SESSION['sampn']=$_SESSION['sampn']+1;
		$chksamp="select * from Sample where SMP_ID='".$_POST['smp_no']."' ";
		$resultsa = mssql_query($chksamp);
	$numRowssa = mssql_num_rows($resultsa);
	while($rowsa = mssql_fetch_array($resultsa))
	{echo $rowsa['SMP_MID_ID'];
		if(substr($rowsa['SMP_MID_ID'],0,4)==substr($_SESSION['prod'],0,4))
		{
			
			$OK="Y";
		}
	}
		if($OK=="Y")
		{
			$query="Update Sample Set SMP_TIMES = SMP_TIMES+1,SMP_SERVICE = 3,SMP_LOT ='".$_SESSION['lot']."',
			 SMP_DRUMNO ='1~8', SMP_USER = '".$_SESSION['csn']."', SMP_SMP = '".$_SESSION['uid']."', SMP_SAVE = '".date("Ymd")."' 
			 Where SMP_ID = '".$_POST['smp_no']."' ";//OK
			$result = mssql_query($query);
			//echo $query;
			$query="Insert Into Sample_All 
			(SMA_ID, SMA_TIMES, SMA_SERVICE, SMA_LOT , SMA_SERIAL_NO , SMA_DRUMNO ,  SMA_USER, SMA_SMP, SMA_SAVE, SMA_SAVE_TIME, 
			ISREWORK , DHN_DRUM_NO) 
			select '".$_POST['smp_no']."' ,SMP_TIMES,3,'".$_SESSION['lot']."',1,'1~8','".$_SESSION['csn']."','".$_SESSION['uid']."'
			,'".date("Ymd")."','".date("His")."','0','' from sample Where SMP_ID = '".$_POST['smp_no']."' ";
			
			$result = mssql_query($query);
			echo "寫入".'</br>';
			$smp_chk='1';
		}
		if($OK!="Y")
		{
			echo "請輸入正確樣品瓶號".'</br>';
			echo $OK;
			

		}
	}
	
	$query="SELECT A.FDM_ATTACH_CNT,A.FDM_SAM_BEF_CNT,A.FDM_SAM_CNT
	from  FILLPLAN_OUT_DECIDE AS A 
	where FDM_LOT_NO='".$_SESSION['lot']."'";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	while($row = mssql_fetch_array($result))
	{
		$smp_num=$row['FDM_ATTACH_CNT']+$row['FDM_SAM_BEF_CNT']+$row['FDM_SAM_CNT']+2;
	}
	$query1="select * from Sample_All where SMA_LOT='".$_SESSION['lot']."' "; 
	$result1 = mssql_query($query1);
	$numRows1 = mssql_num_rows($result1);
	
	echo "取樣作業已有".$numRows1.'</br>';
	$_SESSION['sampn']=$numRows1;
	
	echo "應取瓶數:".$smp_num.'</br>';
	echo "取樣瓶號".'<input type="text" name="smp_no" id="smp_no" style="font-size:20px">'.'</br>';
	echo '<input type="submit" name="nextsamp" id="nextsamp"  style="font-size:20px"value="儲存/下一瓶" />';
	echo '<input type="submit" name="pack1" id="pack1"  style="font-size:20px" value="結束取樣" />'."請儲存後再結束取樣";
	
	
	if($_SESSION['chkifpack']==0)
	{
		$pack="Insert Into DRUM_FILL_DAY_CHECK (DDC_CHK_DATE,FDM_LOT_NO,PDD_PROD_NO,DDC_PURGE,DDC_SCALES_SET,DDC_CHK_SCALES)
		values ('".date("Ymd")."','".$_SESSION['lot']."','".$_SESSION['prod']."','".$_SESSION['DFDC1']."','".$_SESSION['DFDC2']."',
		'".$_SESSION['DFDC3']."')";
					$result = mssql_query($pack);

		//echo $pack;
	}
	
}
//結束取樣還沒做
echo '<p><strong><a href="fill.php">上一步</a></strong></p>';			

if(isset($_POST['_fill'])){
	jumpto("pda_20L_FILL.php");
}
if(isset($_POST['_wash'])){
	jumpto("pda_20L_WASH.php");
}
?>
