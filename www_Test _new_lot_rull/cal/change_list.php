<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
session_start();
include("../lib/fun.php");
include("../lib/jtsai.php");
lasturl();
datepick();
?>
<form name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  日期：
    <input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php echo $_SESSION['datepicker1'] ; ?>"  onchange="set_date_session(this.name,this.value)">
~
<input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php echo $_SESSION['datepicker2'] ; ?>"  onchange="set_date_session(this.name,this.value)">
品名：
<input type="button" name="X" id="X" value="X" onclick="window.open('../erase_prod.php ', '_self');" />
<input name="prod_no" type="text" id="prod_no" size="10" value="<?php echo $_SESSION['prod_no']?>" onchange="set_date_session(this.name,this.value)"/>
<input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onclick="window.open('../pdd_prod_no.php ', '_self');" />
<input name="prod_name" type="text" id="prod_name" size="16" value="<?php echo get_prod_name($_SESSION['prod_no']);?>" readonly />
<select name="search" id="search" onchange="set_date_session(this.name,this.value)">
  <option value="0" <?php if($_SESSION['search']==0){echo " selected";}?> >ALL</option>
  <option value="1" <?php if($_SESSION['search']==1){echo " selected";}?>>製品</option>
  <option value="2" <?php if($_SESSION['search']==2){echo " selected";}?>>解析</option>
  <option value="3" <?php if($_SESSION['search']==3){echo " selected";}?>>受入</option>
</select>
Lot NO:<input type="text" name="lot" id="lot" size="10" value="<?php echo $_SESSION['lot'] ?>" onchange="set_date_session(this.name,this.value)" />
<input type="submit" name="submit" id="submit" value="    搜  尋   " />
</form>
<table width="1240" border="1"><tr><td>日期</td><td>藥品</td><td>Lot No</td><td>預定分析項</td><td>修改分析項</td><td>依賴建立人員</td><td>最後修改人員</td></tr>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
if(isset($_POST['submit'])){
$query="SELECT          AnalyzeDesign.* , PRODUCT_DATA.PDD_PROD_NAME, 
                            EMPLOYEE_DATA.EMP_NAME,PRODUCT_DATA.PDD_PROD_NO, PRODUCT_DATA.PDD_PACKAGE, AnalyzeDesign.AND_CANCEL
FROM              AnalyzeDesign INNER JOIN
                            EMPLOYEE_DATA ON AnalyzeDesign.AND_PERSON = EMPLOYEE_DATA.EMP_NO INNER JOIN
                            PRODUCT_DATA ON AnalyzeDesign.AND_GOODS = PRODUCT_DATA.PDD_PROD_NO
WHERE /*(AND_SELECT_TYPE <> '0' and AND_SELECT_TYPE <> '') AND*/";
	if($_SESSION['lot']!=''){$query.=" (AND_LOT_NO like '%".$_SESSION['lot']."%')";}else{
	if($_POST['prod_no']<>''){$query.=" AND (PRODUCT_DATA.PDD_PROD_NO='".$_POST['prod_no']."') ";}
	if($_POST['datepicker1']<>''){$query.=" AND_SMP_DATETIME >= '".dod($_POST['datepicker1'])."000000' ";}
	if($_POST['datepicker2']<>''){$query.=" AND AND_SMP_DATETIME <= '".dod($_POST['datepicker2'])."235959' ";}
	if($_POST['search']=='1'){$query.=" and (AND_NEED_NO<=150)" ;}
	if($_POST['search']=='2'){$query.=" and (AND_NEED_NO<=270) and (AND_NEED_NO>150)";}
	if($_POST['search']=='3'){$query.=" and (AND_NEED_NO>270)";}
    $query.=" ORDER BY  AnalyzeDesign.AND_GOODS, PRODUCT_DATA.PDD_PACKAGE DESC";
	}
	//echo $query;
	$_SESSION['tmp']=$query;
$result=mssql_query($query);
while($row=mssql_fetch_array($result))
{
	if($row['AND_ITEM_ORI']=='1'){$a='常規';}
	if($row['AND_ITEM_ORI']=='2'){$a='全項';}
	if($row['AND_SELECT_TYPE']=='1'){$b='常規';}
	elseif($row['AND_SELECT_TYPE']=='2'){$b='全項';}
	else{$b=$row['AND_SELECT_TYPE'];}
	echo '<tr>';
	echo '<td>'.($row['AND_SMP_DATETIME']).'</td><td>'.get_prod_name($row['PDD_PROD_NO']).'</td><td>'.$row['AND_LOT_NO'].'</td><td>'.$a.'</td><td>'.$b.'</td><td>'.get_uname($row['AND_PERSON']).'</td><td>'.get_uname($row['AND_LAST']).'</td>';
	echo '</tr>';
}
}
echo '</table>';

?>