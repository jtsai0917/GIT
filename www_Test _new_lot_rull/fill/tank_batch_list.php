<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php 

session_start();
include("../checkuser.php");
include("../lib/fun.php");
include("../connections/conn.php");
lasturl();
datepick(); 
if($_SESSION['datepicker1']==''){$_SESSION['datepicker1']=date("m/d/Y");}
if($_SESSION['datepicker2']==''){$_SESSION['datepicker2']=date("m/d/Y");}
?>
<table width="1024" border="1">
<tr><td align="center">
封槽查詢</td></tr>
<tr><td>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  日期區間:
  <input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php echo $_SESSION['datepicker1'] ; ?>"   onchange="set_date_session(this.name,this.value)">
~
<input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php echo $_SESSION['datepicker2'] ; ?>"   onchange="set_date_session(this.name,this.value)">
品名：
<input type="button" name="X" id="X" value="X" onclick="window.open('../erase_prod.php ', '_self');" />
<input name="prod_no" type="text" id="prod_no" size="10" value="<?php echo $_SESSION['prod_no']?>" readonly />
<input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onclick="window.open('../pdd_prod_no.php ', '_self');" />
<input name="prod_name" type="text" id="prod_name" size="16" value="<?php echo get_prod_name($_SESSION['prod_no']);?>" readonly />
TANK 編號：<input type="text" name="tank_no" size="8" value="<?php echo $_SESSION['tank_no'] ; ?>"   onchange="set_date_session(this.name,this.value)">
Lot No：
<input type="submit" name="X2" id="X2" value="X" />
<input name="lot_nox" type="text" id="lot_nox" size="10" value="<?php echo trim($_SESSION['lot_nox']);?>" onchange="set_date_session(this.name,this.value)" />
<input type="submit" name="search" id="search" value="  搜 尋  " />
</form>
</td></tr></table>

<?php
if(isset($_POST['search'])){
	$query="SELECT  Tank_batch.*, EMPLOYEE_DATA.EMP_NAME, PRODUCT_DATA.PDD_PROD_NAME
FROM      Tank_batch INNER JOIN
                   EMPLOYEE_DATA ON Tank_batch.creator = EMPLOYEE_DATA.EMP_NO INNER JOIN
                   PRODUCT_DATA ON Tank_batch.PDD_PROD_NO = PRODUCT_DATA.PDD_PROD_NO WHERE Tank_batch.active=1 ";
	if($_POST['lot_nox']<>''){
		$query=$query."	AND lot_no<'".$_POST['lot_nox']."'";
	}
	else{
		if($_POST['datepicker1']<>''){
			$query=$query."	AND filldatetime>='".dod($_POST['datepicker1'])."'";
		}
		if($_POST['datepicker2']<>''){
			$query=$query."	AND filldatetime<='".dod($_POST['datepicker2'])."'";
		}
	}
	echo '搜尋結果列表 <table width="1024" border="1"><tr bgcolor="#CCCCCC"><td width="30">index</td><td>料號</td><td>品名</td><td>封槽日期</td><td>Lot NO / 分析</td><td>TANK_NO</td><td>建立人員</td><td>存量 M^3</td><td>備註</td><td>刪除</td></tr>';	
	$i=1;
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		$str='<a href="index.php?url=delete_batch&lot_no='.$row['lot_no'].'">刪除</a>';
		$str1='<a href="../cal/index.php?url=cal_prod_input_1&lid='.$row['lot_no'].'">'.$row['lot_no'].'</a>';
		$str='<a href="index.php?url=delete_batch&lot_no='.$row['lot_no'].'">刪除</a>';
		$str='<a href="index.php?url=delete_batch&lot_no='.$row['lot_no'].'">刪除</a>';
		
		echo '<tr><td width="30">'.$i.'</td><td>'.$row['PDD_PROD_NO'].'</td><td>'.$row['PDD_PROD_NAME'].'</td><td>'.$row['filldatetime'].'</td><td>'.$str1.'</td><td>'.$row['tank_no'].'</td><td>'.$row['EMP_NAME'].'</td><td>'.$row['leave'].'</td><td>'.$row['memo'].'</td><td>'.$str.'</td></tr>';	
		$i++;
	}
} //search

?>