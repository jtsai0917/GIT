<?php include("../checkuser.php");
session_start();
include("../lib/fun.php");
include("../checkuser.php");
lasturl();
?><head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
  <title>jQuery UI Datepicker - Default functionality</title>
  <link rel="stylesheet" href="/js/jquery-ui.css">
  <script src="/js/jquery-1.10.2.js"></script>
  <script src="/js/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script type="text/javascript">
    $(function() {
    $( "#datepicker1" ).datepicker();
	$( "#datepicker2" ).datepicker();
	$( "#datepicker3" ).datepicker();
	$( "#datepicker4" ).datepicker();
  });

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
      <td width="480">要求日期 
        <input name="datepicker1" type="text" id="datepicker1" size="14" value="<?php 
		if ($_GET['datepicker1']){
			echo $_GET['datepicker1'];
			$_SESSION['datepicker1']=$_GET['datepicker1'];
		}
		elseif($_SESSION['datepicker1']){
			echo $_SESSION['datepicker1'];
		}
		else{
		$d=strtotime("-0 Days"); echo date("m/d/Y",$d);
		}
		?>">
        ~
        <input name="datepicker2" type="text" id="datepicker2" size="14" value="<?php 
		if ($_GET['datepicker2']){
			echo $_GET['datepicker2'];
			$_SESSION['datepicker2']=$_GET['datepicker2'];
		}
		elseif($_SESSION['datepicker2']){
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
if($_POST['search']){
		$_SESSION['lid']=$_POST['textfield4'];
		$_SESSION['datepicker2']=$_POST['datepicker2'];
		$_SESSION['datepicker1']=$_POST['datepicker1'];
		refresh();
	}
	
if (!$_SESSION['datepicker1']){$_SESSION['datepicker1']=date("m/d/Y",$d);}
if (!$_SESSION['datepicker2']){$_SESSION['datepicker2']=date("m/d/Y",$d);}
$_SESSION['lasturl']=$_SERVER['REQUEST_URI'];
include('../connections/conn.php'); 
$query="SELECT  AnalyzeDesign.*, AnalyzeDesign.AND_CANCEL, AnalyzeDesign.AND_REPORT_DATETIME, EMPLOYEE_DATA.EMP_NAME, AnalyzeDesign.AND_MEMO, 
                            AnalyzeDesign.AND_LOT_NO, AnalyzeDesign.AND_APPLY_DATE, PRODUCT_DATA.PDD_TYPE, PRODUCT_DATA.PDD_PROD_NO,PRODUCT_DATA.PDD_CHEMICAL,
                            PRODUCT_DATA.PDD_PROD_NAME, AnalyzeDesign.AND_ITEM, AnalyzeDesign.AND_NEED_NO

FROM              AnalyzeDesign INNER JOIN
                            PRODUCT_DATA ON AnalyzeDesign.AND_GOODS = PRODUCT_DATA.PDD_PROD_NO INNER JOIN
                            EMPLOYEE_DATA ON AnalyzeDesign.AND_PERSON = EMPLOYEE_DATA.EMP_NO
WHERE          (AnalyzeDesign.AND_NEED_NO>249) and (AnalyzeDesign.AND_NEED_NO<350)";
if ($_POST['datepicker1']){
	$_SESSION['datepicker1']=$_POST['datepicker1'];
    $query=$query." AND (AnalyzeDesign.AND_REPORT_DATETIME >='".dod($_POST['datepicker1'])."0000')";
}
elseif($_SESSION['datepicker1'])
{
	$query=$query." AND (AnalyzeDesign.AND_REPORT_DATETIME >='".dod($_SESSION['datepicker1'])."0000')";
}
if ($_POST['datepicker2']){
	$_SESSION['datepicker2']=$_POST['datepicker2'];
	$query=$query." AND ( AnalyzeDesign.AND_REPORT_DATETIME <='".dod($_POST['datepicker2'])."2359')";
}
elseif($_SESSION['datepicker2']){
	$query=$query." AND ( AnalyzeDesign.AND_REPORT_DATETIME <='".dod($_SESSION['datepicker2'])."2359')";
}
if ($_POST['pdd_chemical1']){
	$_SESSION['pid']=$_POST['pdd_chemical1'];
	$query=$query." AND (AnalyzeDesign.AND_GOODS ='".$_POST['pdd_chemical1']."')";
}
elseif($_SESSION['pid']){
	$query=$query." AND (AnalyzeDesign.AND_GOODS ='".$_SESSION['pid']."')";
}

if ($_POST['textfield4']){
	$_POST['textfield4']=trim($_POST['textfield4']);
	$_SESSION['lid']=$_POST['textfield4'];
	$query="SELECT  AnalyzeDesign.*, AnalyzeDesign.AND_CANCEL, AnalyzeDesign.AND_REPORT_DATETIME, EMPLOYEE_DATA.EMP_NAME, AnalyzeDesign.AND_MEMO, 
                            AnalyzeDesign.AND_LOT_NO, AnalyzeDesign.AND_GOODS, AnalyzeDesign.AND_APPLY_DATE, PRODUCT_DATA.PDD_TYPE, PRODUCT_DATA.PDD_PROD_NO,PRODUCT_DATA.PDD_CHEMICAL,
                            PRODUCT_DATA.PDD_PROD_NAME, AnalyzeDesign.AND_ITEM, AnalyzeDesign.AND_NEED_NO

FROM              AnalyzeDesign INNER JOIN
                            PRODUCT_DATA ON AnalyzeDesign.AND_GOODS = PRODUCT_DATA.PDD_PROD_NO INNER JOIN
                            EMPLOYEE_DATA ON AnalyzeDesign.AND_PERSON = EMPLOYEE_DATA.EMP_NO
WHERE          (AnalyzeDesign.AND_LOT_NO LIKE '%".trim($_POST['textfield4'])."%')";
}
elseif($_SESSION['lid']){
	$_SESSION['lid']=trim($_SESSION['lid']);
$query="SELECT AnalyzeDesign.*, AnalyzeDesign.AND_CANCEL, AnalyzeDesign.AND_REPORT_DATETIME, EMPLOYEE_DATA.EMP_NAME, AnalyzeDesign.AND_MEMO, 
                            AnalyzeDesign.AND_LOT_NO, AnalyzeDesign.AND_GOODS, AnalyzeDesign.AND_APPLY_DATE, PRODUCT_DATA.PDD_TYPE, PRODUCT_DATA.PDD_PROD_NO,PRODUCT_DATA.PDD_CHEMICAL,
                            PRODUCT_DATA.PDD_PROD_NAME, AnalyzeDesign.AND_ITEM, AnalyzeDesign.AND_NEED_NO

FROM              AnalyzeDesign INNER JOIN
                            PRODUCT_DATA ON AnalyzeDesign.AND_GOODS = PRODUCT_DATA.PDD_PROD_NO INNER JOIN
                            EMPLOYEE_DATA ON AnalyzeDesign.AND_PERSON = EMPLOYEE_DATA.EMP_NO
WHERE          (AnalyzeDesign.AND_LOT_NO LIKE '%".trim($_SESSION['lid'])."%')";}
$query.=" ORDER BY AND_APPLY_DATE DESC";

$result = mssql_query($query);
$numRows = mssql_num_rows($result);
?>
<table width="1240" border="1">
  <tr bgcolor="#CCCCCC">
    <td height="49" width="80">日期序號</td>
    <td width="80">客戶</td>
    <td width="100">品名</td>
    <td width="30">容器</td>
    <td width="100">LotNO</td>
    <td width="30">註記</td>
    <td width="100">要求完成時間</td>
    <td width="720"><p>分析項目:<font color="#0000FF">藍字</font>表示已有報告及結果，<font color="#FF0000">紅字</font>表示沒有報告及結果，<font color="green">綠字</font>表示有報告但是無檢驗結果，</p>
    <p><font color="purple">紫色</font>表示沒有報告但是有檢驗結果‧<span style="background-color: red"><font color="white">紅底白字</font></span>表示檢驗未通過‧</p></td>

  </tr>
<?php
while($row = mssql_fetch_array($result))
{   
$str1=$row['PDD_PROD_NO'];
$str2="P001";
$str3="UP007";
$str4="UP319";
$str5="P007";
$str6="UP001";
$cus=new get_from_lot_no;
$cus->lid=$row['AND_LOT_NO'];
$cus->cid();
	if($row['AND_CANCEL']==1){$rpd='已取消';echo '<tr bgcolor="#FF9900">';}
	else{$rpd=$row['AND_REPORT_DATETIME'];echo '<tr>';}
	
    echo '<td>'.$row['AND_APPLY_DATE']."-".$row['AND_NEED_NO'].'</td>';
	echo '<td>'.$cus->csname.'</td>';
    echo '<td>'.$row['PDD_PROD_NAME'].'</td>';
    echo '<td>'.$row['PDD_TYPE'].'</td>';
    echo '<td>'.$row['AND_LOT_NO'].'</td>';
    echo '<td>'.$row['AND_NOTE'].'</td>';
    echo '<td>'.$rpd.'</td>';
	if(substr($row['AND_ITEM'],-1)==','){$row['AND_ITEM']=substr($row['AND_ITEM'],0,-1);}
	analyzereport($row['AND_ITEM'],$row['AND_LOT_NO'],$row['EMP_NAME'],$row['PDD_CHEMICAL'],$row['PDD_PROD_NO']);
/*    echo '<td><a target="_self" href="'.$url.$row['AND_LOT_NO']."&testdate=".$row['AND_APPLY_DATE']."&operator=".iconv("big5","utf-8",$row['EMP_NAME']).'">ASSAY</a></td>';
    echo '<td><a target="_self" href="'.$ur2.$row['AND_LOT_NO']."&testdate=".$row['AND_APPLY_DATE']."&operator=".iconv("big5","utf-8",$row['EMP_NAME']).'">NH4</a></td>';
	    echo '<td><a target="_self" href="'.$ur3.$row['AND_LOT_NO']."&testdate=".$row['AND_APPLY_DATE']."&operator=".iconv("big5","utf-8",$row['EMP_NAME']).'">UV</a></td>';
*/
	echo '</tr>';
}
?>
</table>