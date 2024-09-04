<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
session_start();
include("../lib/fun.php");
include("../lib/jtsai.php");
include("../connections/conn.php");
datepick();
lasturl();
?>
<BR>
<table border="1" width="1024"><tr>
<td>產品群組設定</td></tr><tr>
  <td>   
品名：<form id="form1" name="form1" method="post" action="<?php echo $editFormAction;?>">
      <input type="button" name="X" id="X" value="X" onclick="window.open('../erase_prod.php ', '_self');" />
      <input name="pdd_chemical1" type="text" id="pdd_chemical1" size="10" value="<?php 
	  echo $_SESSION['AND_GOODS']; 
	  ?>" />
      <input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onclick="window.open('../main.php?url=pdd_prod_no2 ', '_self');" />
    <input name="pdd_chemical2" type="text" id="pdd_chemical2" size="16" value="<?php echo get_prod_name($_SESSION['AND_GOODS']);?>"  />
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    群組：
    <input name="pdd_group" type="text" id="pdd_group" size="16" value="<?php echo $_SESSION['pdd_group'];?>"   onchange="set_date_session(this.name,this.value)"/>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="submit" name="add" id="add" value="新增">
</form>
    <BR>
    </td></tr></table>

<?php
$editFormAction = $_SERVER['PHP_SELF'];
if(isset($_POST['add'])){
	$query="select index from prod_group where pdd_prod_no='".$_POST['pdd_chemical1']."' and pdd_prod_group='".$_POST['pdd_group']."'";
	echo $query."<BR>";
	$result=mssql_query($query);
	$numrows=mssql_num_rows($result);
	$row_index=mssql_fetch_row($result);
	if($numrows==0){//
		$query="insert into prod_group (pdd_prod_no,pdd_prod_group) values ('".$_POST['pdd_chemical1']."','".$_POST['pdd_group']."')";
		echo $query."<BR>";
		$result=mssql_query($query);
	}	
} /// end add
?>