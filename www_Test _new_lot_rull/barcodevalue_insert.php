<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
	include("fun_spc.php");
	include("conn_spc.php");
	datepick();
?>
<form name="SS" method="post">
	客戶代號:<input type="text" name="cust_no"  value="<?php echo $_SESSION['cust_no']; ?>" onchange="set_date_session(this.name,this.value)"><br>
	產品代號:<input type="text" name="prod_no"  value="<?php echo $_SESSION['prod_no']; ?>" onchange="set_date_session(this.name,this.value)">
	輸入型態<select name="status" id="status" onChange="set_date_session(this.name,this.value)">
          <option value="0" <?php if($_SESSION['status']==0){echo "selected";}?>>新增</option>
          <option value="1" <?php if($_SESSION['status']==1){echo "selected";}?>>新規</option>
          <option value="2" <?php if($_SESSION['status']==2){echo "selected";}?>>更新</option>
        </select>
	<br>
	<input type="submit" name="enter" value="搜尋">
	<input type="submit" name="intovalue" value=" 導入分析值 ">
</form>
<?php
if(isset($_POST['intovalue'])){
	$query="SELECT * FROM TEMPNEWVSUB_BK INNER JOIN TEMPMAIN_BK ON TEMPNEWVSUB_BK.MID = TEMPMAIN_BK.MID WHERE MID<>''";
	if(trim($_POST['cust_no'])<>''){
		$query.="and (TEMPMAIN_BK.FILENAME LIKE N'".trim($_POST['cust_no'])."_%')"; 
	}
	if(trim($_POST['prod_no'])<>''){
		$query.="  AND (TEMPMAIN_BK.GROUPNAME LIKE N'".trim($_POST['prod_no'])."_%') ";
	}
	echo $query."<BR>";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){
		echo "SSS<BR>";
		echo $row['FILENAME']."<BR>";
		/*
		$custno1=export('_',trim($row['FILENAME']));
		$prodno1=export('_',trim($row['GROUPNAME']));
		$item1=export('_',trim($row['CTRLITEM']));
		$custno=$custno1[0];
		$prodno=$prodno1[0];
		$item=$item1[1];
		*/
//		echo $custno."<BR>";
//		$a=ins_tmp($custno,$prodno,$item);
		
	}
}
	

function ins_tmp($custno,$prodno,$item){
	$query1="SELECT LotNo, TestData, ProdNo, Chemical, CustNo, ItemName, ItemUnit, createts FROM QC_LotData WHERE (ID <> '') 
					AND (CustNo = '".$custno."') AND (ProdNo = '".$prodno."') AND (ItemName = '".$item."') ORDER BY   createts";
	echo $query1."<BR>";
	$result1=mssql_query($query1);
	
}	
?>