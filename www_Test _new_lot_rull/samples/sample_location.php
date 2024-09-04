<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=big5" />
<title>樣品瓶紀錄</title>
<?php
	session_start();
	include("../lib/fun.php");
	include("../connections/conn.php");
	datepick(); 
	    
	echo '<form method="post" action=""><font size="+2"><BR>現在時間：'.$now=date("Y-m-d H:i:s").'<BR>';
	echo "選擇地點：";
	echo '<select name="location_name" id="location_name" onchange="set_date_session(this.name,this.value)">';
	$query="SELECT [index], location_name AS location_name FROM Sample_Location_Name";
	$result=mssql_query($query);  
	while ($row =mssql_fetch_array($result)){
		if(trim($_SESSION['location_name'])==trim($row['index'])){$select=' selected ';}
		else{$select='';}
		echo '<option value="'.trim($row['index']).'" '.$select.' >'.trim($row['location_name']).'</option>';
	}
	echo '</select>';
	echo '<BR>樣品瓶號：<input type="text" size="11" name="smpno" autofocus value="'.$_SESSION['smpno'].'" >';	
	echo '<input type="submit" name="enter" value="瓶號確定"><BR><BR>';
	if(isset($_POST['enter'])){
		echo '選擇批號<select name="lotno" id="lotno" >';
		$query="SELECT TOP (3) SMA_ID, SMA_LOT FROM Sample_All WHERE (SMA_ID = '".trim($_POST['smpno'])."') ORDER BY   [index] DESC";
		$result=mssql_query($query);
	while ($row =mssql_fetch_array($result)){
			echo '<option value="'.trim($row['SMA_LOT']).'" '.$select.' >'.trim($row['SMA_LOT']).'</option>';
		}
		echo '</select>';
		echo '<input type="submit" name="insert" value="新增"></font>';
	}
	if(isset($_POST['insert'])){
		 $smpno =  $_POST['smpno'];
		$lotno = $_POST['lotno'];
    $location = $_POST['location_name'];
    $createtime=date("Y-m-d H:i:s");
		$insertQuery = "INSERT INTO Sample_Location (SMPID, Lot_NO, LOCATION, createtime, creator) VALUES ('".$smpno."', '".$lotno."','".$location."', '".$createtime."', '".$_SESSION['uid']."')";
		$result=mssql_query($insertQuery);  

    
    echo "Data inserted successfully.";
	}
	echo '</form>';
?>
