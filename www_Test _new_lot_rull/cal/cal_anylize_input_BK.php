<?php 
session_start();
include("../lib/fun.php");
include("../checkuser.php");
include("../lib/jtsai.php");
lasturl();
datepick(); 
if($_SESSION['datepicker1']==''){$_SESSION['datepicker1']=date("m/d/Y")   ;}
if($_SESSION['datepicker2']==''){$_SESSION['datepicker2']=date("m/d/Y")   ;}
?>

<form name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <label for="textfield"></label>

  <table width="1240" border="1">
    <tr>
      <td width="480">要求日期 
        <input name="datepicker1" type="text" id="datepicker1" size="14" value="<?php 	echo $_SESSION['datepicker1'];?>"  onchange="set_date_session(this.name,this.value)">
        ~
        <input name="datepicker2" type="text" id="datepicker2" size="14" value="<?php 	echo $_SESSION['datepicker2'];?>"  onchange="set_date_session(this.name,this.value)">
        
        品名：<span class="d1">
        <input type="button" name="X" id="X" value="X" onClick="window.open('../erase_prod.php ', '_self');">
        <input name="pid" type="text" id="pid" size="10" onchange="set_date_session(this.name,this.value)" value="<?php 
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
		?>" >
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
        <input name="textfield4" type="text" id="textfield4" size="14"  value="<?php echo $_SESSION['textfield4'];	?>" onchange="set_date_session(this.name,this.value)">    </br>  
          檢驗項目：
          <?php select_ani_group();?>
		
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
$_SESSION['lasturl']=$_SERVER['REQUEST_URI'];

if($_POST['search']){
		$_SESSION['lid']=$_POST['textfield4'];
		$_SESSION['datepicker2']=$_POST['datepicker2'];
		$_SESSION['datepicker1']=$_POST['datepicker1'];
		$_SESSION['selected_group']=$_POST['selected_group'];
include('../connections/conn.php'); 
$query="SELECT     AnalyzeDesign.*, AnalyzeDesign.AND_CANCEL, AnalyzeDesign.AND_REPORT_DATETIME, EMPLOYEE_DATA.EMP_NAME, AnalyzeDesign.AND_MEMO, 
                            AnalyzeDesign.AND_LOT_NO, AnalyzeDesign.AND_APPLY_DATE, PRODUCT_DATA.PDD_TYPE, PRODUCT_DATA.PDD_PROD_NO,PRODUCT_DATA.PDD_CHEMICAL,
                            PRODUCT_DATA.PDD_PROD_NAME, AnalyzeDesign.AND_ITEM, AnalyzeDesign.AND_NEED_NO

FROM              AnalyzeDesign INNER JOIN
                            PRODUCT_DATA ON AnalyzeDesign.AND_GOODS = PRODUCT_DATA.PDD_PROD_NO INNER JOIN
                            EMPLOYEE_DATA ON AnalyzeDesign.AND_PERSON = EMPLOYEE_DATA.EMP_NO
WHERE          (AnalyzeDesign.AND_NEED_NO>149) and (AnalyzeDesign.AND_NEED_NO<270)";

if ($_POST['pid']){
	$query=$query." AND (AnalyzeDesign.AND_GOODS like '".$_POST['pid']."%')";
}
elseif($_SESSION['pid']){
	$query=$query." AND (AnalyzeDesign.AND_GOODS like '".$_SESSION['pid']."%')";
}

if ($_POST['textfield4']<>""){
	$_POST['textfield4']=trim($_POST['textfield4']);
	$query.=" and (AnalyzeDesign.AND_LOT_NO LIKE '%".trim($_POST['textfield4'])."%')";
	
}
else{
	if ($_POST['datepicker1']){
	$_SESSION['datepicker1']=$_POST['datepicker1'];
    $query=$query." AND (AnalyzeDesign.AND_SMP_DATETIME >='".dod($_POST['datepicker1'])."0000')";
}
elseif($_SESSION['datepicker1'])
{
	$query=$query." AND (AnalyzeDesign.AND_SMP_DATETIME >='".dod($_SESSION['datepicker1'])."0000')";
}
if ($_POST['datepicker2']){
	$_SESSION['datepicker2']=$_POST['datepicker2'];
	$query=$query." AND ( AnalyzeDesign.AND_SMP_DATETIME <='".dod($_POST['datepicker2'])."2359')";
}
elseif($_SESSION['datepicker2']){
	$query=$query." AND ( AnalyzeDesign.AND_SMP_DATETIME <='".dod($_SESSION['datepicker2'])."2359')";
}
}

if($_POST['selected_group']=='0'){}
else{
	$query=$query." and (";
	$query1="SELECT ANI_INDEX
			FROM              AnalyzeItem
			WHERE          (ANI_GROUPNAME = '".$_POST['selected_group']."')";
	$result1 = mssql_query($query1);
	$numRows1 = mssql_num_rows($result1);
	$x=0;
	while ($row1 = mssql_fetch_array($result1)){
	if($x>0){$query.=" OR ";}
	$query.="(','+AnalyzeDesign.AND_ITEM+',' LIKE '%,".$row1['ANI_INDEX'].",%')";
	$x=$x+1;
	}
	$query=$query.")";
	
}

$query.=" ORDER BY AND_APPLY_DATE DESC";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
?>
<table width="1240" border="1">
  <tr bgcolor="#CCCCCC">
    <td height="49" width="80">日期序號</td>
    <td width="76">客戶</td>
    <td width="84">品名</td>
    <td width="29">容器</td>
    <td width="67">LotNO</td>
    <td width="158">備註1</td>
    <td width="95">要求完成時間</td>
    <td width="603"><p>分析項目:<font color="#0000FF">藍字</font>表示已有報告及結果，<font color="#FF0000">紅字</font>表示沒有報告及結果，<font color="green">綠字</font>表示有報告但是無檢驗結果，</p>
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
$diff=new difference_too_large;
$diff->LotNo=$row['AND_LOT_NO'];
$diff->status();
	$note=$row['AND_NOTE'];
	$rpd=$row['AND_REPORT_DATETIME'];
	if($row['AND_CANCEL']==1){$rpd='已取消';echo '<tr bgcolor="#FF9900">';}
	elseif($diff->flag=='1'){$note=$diff->item.' 差異太大';echo '<tr bgcolor="#FF9900">';}
	else{echo '<tr>';}
	
    echo '<td>'.$row['AND_APPLY_DATE']."-".$row['AND_NEED_NO'].'</td>';
	echo '<td>'.$cus->csname.'</td>';
    echo '<td>'.$row['PDD_PROD_NAME'].'</td>';
    echo '<td>'.$row['PDD_TYPE'].'</td>';
    echo '<td>'.$row['AND_LOT_NO'].'</td>';
    echo '<td>'.$note.'</td>';
    echo '<td>'.$rpd.'</td>';
	if(substr($row['AND_ITEM'],-1)==','){$row['AND_ITEM']=substr($row['AND_ITEM'],0,-1);}
	analyzereport($row['AND_ITEM'],trim($row['AND_LOT_NO']),$row['EMP_NAME'],$row['PDD_CHEMICAL'],$row['PDD_PROD_NO']);
/*    echo '<td><a target="_self" href="'.$url.$row['AND_LOT_NO']."&testdate=".$row['AND_APPLY_DATE']."&operator=".iconv("big5","utf-8",$row['EMP_NAME']).'">ASSAY</a></td>';
    echo '<td><a target="_self" href="'.$ur2.$row['AND_LOT_NO']."&testdate=".$row['AND_APPLY_DATE']."&operator=".iconv("big5","utf-8",$row['EMP_NAME']).'">NH4</a></td>';
	    echo '<td><a target="_self" href="'.$ur3.$row['AND_LOT_NO']."&testdate=".$row['AND_APPLY_DATE']."&operator=".iconv("big5","utf-8",$row['EMP_NAME']).'">UV</a></td>';
*/
	echo '</tr>';
}
}
?>
</table>
</br>