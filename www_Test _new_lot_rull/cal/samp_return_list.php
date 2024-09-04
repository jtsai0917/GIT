<?php 
session_start();
include("../lib/fun.php");
include("../lib/jtsai.php");
lasturl();
datepick(); 
?>
<meta http-equiv="Content-Type" content="text/html; charset=Big5" />
<form name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <label for="textfield"></label>

  <table width="1240" border="1">
    <tr>
      <td width="480">退瓶日期 
        <input name="datepicker1" type="text" id="datepicker1" size="14" value="<?php 	echo $_SESSION['datepicker1'];?>"  onchange="set_date_session(this.name,this.value)">
        ~
        <input name="datepicker2" type="text" id="datepicker2" size="14" value="<?php 	echo $_SESSION['datepicker2'];?>"  onchange="set_date_session(this.name,this.value)">

        Lot No：
        <input name="lid" type="text" id="lid" size="14"  value="<?php echo $_SESSION['lid'];	?>" onchange="set_date_session(this.name,this.value)"> 
        <input type="submit" name="search" id="search" value="搜尋">
        <input type="hidden" name="mm_insert" id="mm_insert" value="form1">

        </span></td>

    </tr>
  </table>
</form>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
if(isset($_POST['search'])){
	$query="SELECT  Sample_Returning.Lot_No, Sample_Returning.sample_no, Sample_Returning.return_datetime, Sample_Returning.creator, EMPLOYEE_DATA.EMP_NAME 
			FROM      Sample_Returning INNER JOIN EMPLOYEE_DATA ON Sample_Returning.creator = EMPLOYEE_DATA.EMP_NO WHERE   (sample_no <>'') ";
	if($_POST['lid']<>''){$query.=" and (Lot_No='".$_POST['lid']."')";}
	if($_POST['datepicker1']<>''){$query.=" and (return_datetime>'".dod($_POST['datepicker1'])."000000')";}
	if($_POST['datepicker2']<>''){$query.=" and (return_datetime<'".dod($_POST['datepicker2'])."235959')";}
echo '退瓶紀錄<table width="800" border="1"><tr bgcolor="#CCCCCC"><td width="200">Lot NO</td><td width="200">Sample NO</td><td width="200">退瓶日期時間</td><td width="200">退瓶人員</td></tr>';
$result=mssql_query($query);
while($row=mssql_fetch_array($result)){
	echo '<tr><td>'.$row['Lot_No'].'</td><td>'.$row['sample_no'].'</td><td>'.$row['return_datetime'].'</td><td>'.$row['EMP_NAME'].'</td></tr>';
}
echo '</table>';
}
?>