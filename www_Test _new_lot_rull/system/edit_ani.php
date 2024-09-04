<?php 
session_start();
include("../lib/fun.php");
include("../checkuser.php");
include("../connections/conn.php");
lasturl1();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>setup_lorry</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <table width="1240" border="1">
    <tr>
      <td width="1240">編輯</td>
    </tr>
    <tr>
      <td>客戶：<a href="index.php?url=cust_no1&sup=N&ani_rull_id=<?php echo $_GET['id'];?> "><?php 
	  $query="SELECT         dbo.Analyze_Rulls.*
		FROM             dbo.Analyze_Rulls
		WHERE         ([index] = ".$_GET['id'].")";
		$_SESSION['tmppp']= $query;
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result)){
			if($_SESSION['CTD_CUST_NO']){$_SESSION['myallcust']=$_SESSION['CTD_CUST_NO'];}
			else{$_SESSION['myallcust']=$row['CTD_CUST_NO'];}
	  		echo $_SESSION['myallcust'];
	  ?></a>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    品名：<?php echo $row['PDD_PROD_NO'];?>      </td></tr><tr>
	    <td>
          <p>全檢：每月首次全檢
            <input type="checkbox" name="Total_monthly_1st" id="Total_monthly_1st" <?php 
			if($row['Total_Monthly_1st']=='Y'){ echo "checked";}?>/>
；
            &nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;頻率：
            <input name="duration1" type="text" id="duration1" size="2" value="<?php echo $row['Total_Duration_times'];?>"/>
次全檢一次 ；&nbsp;&nbsp;&nbsp;&nbsp;
            全部全檢
            <input type="checkbox" name="eachday" id="eachday" <?php if($row['eachday']=='Y'){ echo "checked";}?>/>
；&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;每日第一個LOT全檢
            <input type="checkbox" name="eachday_first" id="eachday_first" <?php if($row['eachday_first']=='Y'){ echo "checked";}?>/>
          </p>
          <p>
            說明：
            <input name="memo" type="text" id="memo" size="80" value="<?php echo $row['Memo'];?>" />
            <input type="submit" name="save" value="     確定     " />
            <input type="submit" name="leave" value="     離開     " id="leave" />
      </p></td>
    </tr>
    
  </table>
<?php } ?>
</form>
</body>
</html>
<?php 
$editFormAction = $_SERVER['PHP_SELF'];
if($_SESSION['pdd_chemical1']){$_POST['pdd_chemical1']=$_SESSION['pdd_chemical1'];}   //pid
if($_SESSION['pdd_chemical3']){$_POST['pdd_chemical3']=$_SESSION['pdd_chemical3'];}   //cid
if($_SESSION['pdd_chemical4']){$_POST['pdd_chemical4']=$_SESSION['pdd_chemical4'];}
if($_SESSION['pdd_chemical2']){$_POST['pdd_chemical2']=$_SESSION['pdd_chemical2'];}

if(isset($_POST["save"]))
{
if($_POST['Total_monthly_1st']=='on'){$tm1='Y';}
else{$tm1='N';}
if($_POST['eachday']=='on'){$tm2='Y';}
else{$tm2='N';}
if($_POST['eachday_first']=='on'){$tm3='Y';}
else{$tm3='N';}
if($_POST['duration1']==''){$_POST['duration1']='NULL';}
$query="UPDATE        dbo.Analyze_Rulls
SET                  Total_Duration_times =".$_POST['duration1'].", eachday ='".$tm2."', eachday_first='".$tm3."',
                          Total_Monthly_1st ='".$tm1."', Memo ='".$_POST['memo']."', CTD_CUST_NO ='".$_SESSION['myallcust']."'
WHERE         ([index] = ".$_GET['id'].")";

$result = mssql_query($query);
$numrows=mssql_num_rows($query);
sql_rec($_SERVER['QUERY_STRING'] ,$query);
jumpto($_SESSION['analyze_set']);
}

if(isset($_POST['leave'])){
	unset($_SESSION['CTD_CUST_NO']);
	unset($_SESSION['myallcust']);
	jumpto($_SESSION['analyze_set']);
}
?>
