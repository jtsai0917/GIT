<?php include("../checkuser.php");
session_start();
include("../lib/fun.php");
include("../checkuser.php");
lasturl();
datepick();
?><head>

  <script type="text/javascript">
 
var d;
function sendIt() {
 if (d) document.body.removeChild(d);
 var info = document.getElementById("pdd_chemical").value;
 d = document.createElement("script");
 d.src = "pdd_prod_no.php?info="+info;
 d.type = "text/javascript";
 document.body.appendChild(d);
}
</script>
</head>
<form name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <label for="textfield"></label>

  <table width="1240" border="1">
    <tr>
      <td width="480">上傳日期 
        <input name="datepicker1" type="text" id="datepicker1" size="14" value="<?php 
		if($_SESSION['datepicker1']){
			echo $_SESSION['datepicker1'];
		}
		else{
		$d=strtotime("-0 Days"); echo date("m/d/Y",$d);
		}
		?>">
        ~
        <input name="datepicker2" type="text" id="datepicker2" size="14" value="<?php 
		if($_SESSION['datepicker2']){
			echo $_SESSION['datepicker2'];
		}
		else{
		$d=strtotime("+0 Days"); echo date("m/d/Y",$d);
		}
		?>">
        
        品名：<span class="d1">
        <input type="button" name="X" id="X" value="X" onClick="window.open('../erase_prod.php ', '_self');">
        <input name="pdd_chemical1" type="text" id="pdd_chemical1" size="10" value="<?php 
		if ($_GET['pid']){
			echo $_GET['pid'];
			$_SESSION['pid']=$_GET['pid'];
		}
		elseif($_SESSION['pid']){
			echo $_SESSION['pid'];
		}
		else{
		echo '';
		}
		?>" readonly>
        <input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onClick="window.open('../pdd_prod_no.php ', '_self');" >
        <input name="pdd_chemical2" type="text" id="pdd_chemical2" size="16" value="<?php 
		if ($_GET['pname']){
			echo $_GET['pname'];
			$_SESSION['pname']=$_GET['pname'];
		}
		elseif($_SESSION['pname']){
			echo $_SESSION['pname'];
		}
		else{
		echo '';
		}
		?>" readonly>
        Lot No：
        <input type="button" name="X2" id="X2" value="X" onclick="window.open('../erase_lot.php ', '_self');" />
<input name="textfield4" type="text" id="textfield4" size="10" value="<?php 
		if ($_GET['lid']){
			echo $_GET['lid'];
			$_SESSION['lid']=$_GET['lid'];
		}
		elseif($_SESSION['lid']){
			echo $_SESSION['lid'];
		}
		else{
		echo '';
		}
		?>">
        <input type="submit" name="search" id="search" value="搜尋">
<?php //        <input type="button" name="peint" id="peint" value="列印"> ?>
        <input type="button" name="exit" id="exit" value="離開">
        <input type="hidden" name="mm_insert" id="mm_insert" value="form1">

        </span></td>

    </tr>
  </table>
</form>

<?php 
$loginFormAction = $_SERVER['PHP_SELF'];
echo '<table width="1024" border="1">';
	echo '<tr align="center"><td width="50">日期</td><td width="100">LOT NO</td><td width="250">廠商</td><td width="250">品名</td><td width="100">上傳人員</td><td width="200">說明(檔案連結)</td><td width="74">刪除</td></tr>';
	$query="SELECT          FILE_REPORTS.*
FROM              FILE_REPORTS WHERE  ([index]<>'') ";	
	if($_POST['pdd_chemical1']<>''){$query.=" AND (FILE_PID='".$_POST['pdd_chemical1']."')";}
	$query.=" AND (FILE_UPLOAD_TIME<='".dod($_SESSION['datepicker2'])."235959') AND (FILE_UPLOAD_TIME>='".dod($_SESSION['datepicker1'])."000000')";
	if($_POST['textfield4']<>''){
		$query="SELECT          FILE_REPORTS.*
		FROM              FILE_REPORTS WHERE  ([FILE_LOT_NO]='".$_POST['textfield4']."') ";
	}	
	$query.=" and (FORM_ID='COA')";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	while($row = mssql_fetch_array($result)){
		echo '<tr><td>'.std($row['FILE_UPLOAD_TIME']).'</td><td>'.$row['FILE_LOT_NO'].'</td><td>'.$row['FILE_CID']."(".get_cust_name($row['FILE_CID']).')</td><td>'.$row['FILE_PID']."(".get_prod_name($row['FILE_PID']).')</td><td>'.$row['FILE_UID']."(".get_uname($row['FILE_UID']).')</td><td><a href="'.$row['FILE_PATH'].'">'.$row['FILE_DISC'].'(下載)</td><td><a href="../cal/index.php?url=_delete_report&lot_no='.trim($row['FILE_LOT_NO']).'&index='.$row['index'].'","_blank">刪除</td></tr>';
	}
	echo '</table>';
if(isset($_POST["search"])){
	$_SESSION['datepicker1']=$_POST['datepicker1'];
	$_SESSION['datepicker2']=$_POST['datepicker2'];
	refresh();
}
?>