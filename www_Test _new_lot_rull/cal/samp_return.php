<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
$_SESSION['passornot']=1;
datepick();
if(trim($_SESSION['sample_no'])==''){$autofocus_sample_no='autofocus="autofocus"';}
elseif($_SESSION['smp_lot']==''){$autofocus_smp_lot='autofocus="autofocus"';}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Big5" />
<title>樣品瓶退瓶</title>
</head>
<body>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
<font size="+3">樣品瓶退瓶作業</font>
  <table width="450" border="1">
    <tr>
    
      <td align="right" width="250"><font size="+3">樣品瓶號:</font></td><td>
        <input type="text" name="sample_no"  style="font-size:20px" value="<?php echo $_SESSION['sample_no'];?>"   onchange="set_date_session(this.name,this.value)" <?php echo $autofocus_sample_no;?>/></td>
    </tr>
    <tr>
      <td align="right"><font size="+3">Lot No:</font></td><td><input type="text" name="smp_lot" style="font-size:20px" value="<?php echo $_SESSION['smp_lot'];?>"  onchange="set_date_session(this.name,this.value)" <?php echo $autofocus_smp_lot;?>/>
        <input type="submit" name="enter" hidden="on"/></td>
    </tr>
</table>
</body>
</html>

<?php
$loginFormAction = $_SERVER['PHP_SELF'];
if(isset($_POST['enter']))
{
	// 查瓶數
	$query="SELECT DISTINCT AnalyzeDesign.AND_GET_QTY AS getqty
FROM      Sample LEFT OUTER JOIN
                   AnalyzeDesign ON AnalyzeDesign.AND_LOT_NO = Sample.SMP_LOT
WHERE SAMPLE.SMP_LOT = '".$_SESSION['smp_lot']."'";
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);
		$getqty=$row[0];
		$_SESSION['new_qty']=$getqty-1;	
		
	$query="SELECT  Sample_Keeping.* FROM Sample_Keeping WHERE (Lot_No = N'".$_POST['smp_lot']."') AND (sample_no = N'".$_SESSION['sample_no']."')";
	$result=mssql_query($query);
	$numrows=mssql_num_rows($result);
	/// 檢查分析項目
	$query="SELECT  AnalyzeDesign.AND_GOODS, AnalyzeDesign.CTD_CUST_NO, AnalyzeDesign.AND_ITEM, AnalyzeDesign.AND_PERSON, 
                   PRODUCT_DATA.PDD_CHEMICAL, AnalyzeDesign.AND_GET_QTY 
FROM      AnalyzeDesign INNER JOIN
                   PRODUCT_DATA ON AnalyzeDesign.AND_GOODS = PRODUCT_DATA.PDD_PROD_NO INNER JOIN
                   EMPLOYEE_DATA ON AnalyzeDesign.AND_PERSON = EMPLOYEE_DATA.EMP_NO LEFT OUTER JOIN
                   FILLPLAN_OUT_DECIDE ON AnalyzeDesign.AND_LOT_NO = FILLPLAN_OUT_DECIDE.FDM_LOT_NO
WHERE   (AnalyzeDesign.AND_LOT_NO = '".$_SESSION['smp_lot']."')";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	if(substr($row[2],-1)==','){$row[2]=substr($row[2],0,-1);}
	$aniitems=$row[2];
	if($numrows==0){
	echo '<table width="800" border="1"><tr><td width="70">瓶數：<font color="#FF0000">'.$row[5].'</font></td><td width="60">檢驗項目</td>';
	analyzereport($aniitems,$_SESSION['smp_lot'],$row[3],$row[4],$row[0]);
	
		if(trim($_SESSION['passornot'])==0)
			{
			echo '<font color="#FF0000"><font size="+3">分析未完成，請確定真的要退瓶?  </font>';
			echo '<td width="140">';
			echo '<input type="submit" name="save" autofocus="autofocus" value=" 確定要退瓶 ">';
			echo '<input type="submit" name="cancel" value=" 取消 "></td></tr>';
			}
		elseif($row[5]<=3){
			echo '<font color="#FF0000"><font size="+3">最後三瓶，請確定真的要退瓶?  </font>';
			echo '<td width="140">';
			echo '<input type="submit" name="save" autofocus="autofocus" value=" 確定要退瓶 ">';
			echo '<input type="submit" name="cancel" value=" 取消 "></td></tr>';
			}
		else{
			echo '<td width="60">';
			echo '<input type="submit" name="save" autofocus="autofocus" value=" 確定要退瓶 ">';			
		}
		}
	else{
		echo '<table width="800" border="1"><tr><td width="70">瓶數：<font color="#FF0000">'.$row[5].'</font></td><td width="60">檢驗項目</td>';
		analyzereport($aniitems,$_SESSION['smp_lot'],$row[3],$row[4],$row[0]);
		echo '<font color="#FF0000"><font size="+3">此為保留瓶，不可退瓶 </font>';	
		echo '<td width="140">';
		echo '<input type="submit" name="cancel" value=" 取消 "></td></tr>';
	}
}

if(isset($_POST['save']))
{
	$chkre="select *  from analyze_first1 where lot_no='".$_SESSION['smp_lot']."' and  work_type=703 ";
		$resultre = mssql_query($chkre);
		$numRowsre = mssql_num_rows($resultre);	
		if($numRowsre==0)
		{
			$query="INSERT INTO dbo.analyze_first1
								  (lot_no, sample_no, create_time, create_user,work_type)
				VALUES         ('".$_SESSION['smp_lot']."', '".$_SESSION['sample_no']."', '".date("YmdHis")."','".$_SESSION['uid']."',703)";
			$result=mssql_query($query);
		}
	
	$query="UPDATE  AnalyzeDesign SET AND_GET_QTY = ".$_SESSION['new_qty'].", AND_GET_DATETIME = '".date("YmdHi")."' WHERE (AND_LOT_NO = '".$_SESSION['smp_lot']."')";
		$result=mssql_query($query);
		
	$query="INSERT INTO Sample_Returning (Lot_No, sample_no, return_datetime, creator) VALUES  
	('".$_SESSION['smp_lot']."','".$_SESSION['sample_no']."','".date("YmdHis")."','".$_SESSION['uid']."')";
	$result=mssql_query($query);
	$_SESSION['sample_no']='';
	$_SESSION['smp_lot']='';
	my_msg("儲存完畢");
	refresh();
}

if(isset($_POST['cancel']))
{
	$_SESSION['sample_no']='';
	$_SESSION['smp_lot']='';
	refresh();
}
?>
</table>
</form>