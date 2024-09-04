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
  <table width="1240" border="1">
    <tr>
      <td width="480">LOT充填日期
        <input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php echo $_SESSION['datepicker1'] ; ?>"  onchange="set_date_session(this.name,this.value)">
        ~
        <input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php echo $_SESSION['datepicker2'] ; ?>"  onchange="set_date_session(this.name,this.value)">
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
        <input name="pdd_chemical2" type="text" id="pdd_chemical2" size="16" value="<?php echo get_prod_name($_SESSION['pid']);?>" readonly>
        Lot No：
        
        <input name="textfield4" type="text" id="textfield4" size="10" onchange="set_date_session(this.name,this.value)" value="<?php 
if ($_GET['lid']){
			echo $_GET['lid'];
			$_SESSION['textfield4']=$_GET['lid'];
		}
		elseif($_SESSION['textfield4']){
			echo $_SESSION['textfield4'];
		}
		else{
		echo '';
		}
?>">    </br>  
          出荷先：
          <input type="button" name="X3" id="X3" value="X" onclick="window.open('../erase_customer.php ', '_self');" />
          <input name="pdd_chemical3" type="text" id="pdd_chemical3" size="10" value="<?php echo $_SESSION['cust_no'];?>" readonly="readonly" />
          <input type="button" name="pdd_no2" id="pdd_no2" value="查詢" onclick="window.open('../cust_no.php?sup=N ', '_self');" />
          <input name="pdd_chemical4" type="text" id="pdd_chemical4" size="16" value="<?php echo get_cust_name($_SESSION['cust_no']);?>" />
          檢驗項目：
          <?php select_ani_group();?>
		  
<input type="submit" name="search" id="search" value="    搜  尋   " />
          <?php //        <input type="button" name="peint" id="peint" value="列印"> ?>
          <input type="button" name="exit" id="exit" value="離開" />
          <input type="hidden" name="mm_insert" id="mm_insert" value="form1" />
        </td>

    </tr>
  </table>
</form>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
if(isset($_POST['search'])){
		$_SESSION['lid']=$_POST['textfield4'];
		$_SESSION['datepicker2']=$_POST['datepicker2'];
		$_SESSION['datepicker1']=$_POST['datepicker1'];
		$_SESSION['prod_id']=$_POST['pdd_chemical1'];
		$_SESSION['selected_group']=$_POST['selected_group'];
		$_SESSION['cid']=$_SESSION['cust_no']=$_POST['pdd_chemical3'];
$d=strtotime("+0 Days");
if (!$_SESSION['datepicker1']){$_SESSION['datepicker1']=date("m/d/Y",$d);}
if (!$_SESSION['datepicker2']){$_SESSION['datepicker2']=date("m/d/Y",$d);}
$_SESSION['lasturl']=$_SERVER['REQUEST_URI'];
include('../connections/conn.php'); 
$query="SELECT AnalyzeDesign.*, AnalyzeDesign.AND_CANCEL, AnalyzeDesign.AND_REPORT_DATETIME, EMPLOYEE_DATA.EMP_NAME, AnalyzeDesign.AND_MEMO, 
                            AnalyzeDesign.AND_LOT_NO, AnalyzeDesign.AND_APPLY_DATE, PRODUCT_DATA.PDD_TYPE, 
                            PRODUCT_DATA.PDD_PROD_NO, PRODUCT_DATA.PDD_CHEMICAL, PRODUCT_DATA.PDD_PROD_NAME, 
                            AnalyzeDesign.AND_ITEM, AnalyzeDesign.AND_NEED_NO, dbo.FILLPLAN_OUT_DECIDE.FOD_YEAR_MONTH, 
                            dbo.FILLPLAN_OUT_DECIDE.FOD_DAY, FILLPLAN_OUT_DECIDE.CTD_CUST_NO
FROM              dbo.AnalyzeDesign INNER JOIN
                            dbo.PRODUCT_DATA ON AnalyzeDesign.AND_GOODS = PRODUCT_DATA.PDD_PROD_NO INNER JOIN
                            dbo.EMPLOYEE_DATA ON AnalyzeDesign.AND_PERSON = EMPLOYEE_DATA.EMP_NO LEFT OUTER JOIN
                            dbo.FILLPLAN_OUT_DECIDE ON 
                            dbo.AnalyzeDesign.AND_LOT_NO = dbo.FILLPLAN_OUT_DECIDE.FDM_LOT_NO
							
WHERE          (AnalyzeDesign.AND_NEED_NO<150)";

// (dbo.FILLPLAN_OUT_DECIDE.FOD_YEAR_MONTH + dbo.FILLPLAN_OUT_DECIDE.FOD_DAY <= '20151220')

if ($_POST['pdd_chemical1']){
	$_SESSION['pid']=$_POST['pdd_chemical1'];
	$query=$query." AND (AnalyzeDesign.AND_GOODS ='".$_POST['pdd_chemical1']."')";
}
elseif($_SESSION['pid']){
	$query=$query." AND (AnalyzeDesign.AND_GOODS ='".$_SESSION['pid']."')";
}

if ($_POST['pdd_chemical3']){
	$_SESSION['cust_no']=$_POST['pdd_chemical3'];
	$query=$query." AND (FILLPLAN_OUT_DECIDE.CTD_CUST_NO ='".$_SESSION['cust_no']."')";
}
elseif($_SESSION['cust_no']){
	$query=$query." AND (FILLPLAN_OUT_DECIDE.CTD_CUST_NO ='".$_SESSION['cust_no']."')";
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
if($_POST['selected_group']<>'0'){
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
$query.=" ORDER BY AND_SMP_DATETIME";
// echo "<BR>".$query."<BR>";
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
$n=strpos($row['PDD_PROD_NO'],"-");
if(substr($row['PDD_PROD_NO'],$n,3)<>'000'){$pdd_no=substr($row['PDD_PROD_NO'],0,$n+1)."000";}
else{$pdd_no=$row['PDD_PROD_NO'];}
// echo "<BR>SSS: ".$pdd_no."<BR>";
$str1=$pdd_no;
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
	
	$ID=get_ani_sinetics($cus->cid,$pdd_no);
//	if($row['PDD_TYPE']=='DM'){$_SESSION['IDDDDDDDDDDDDDDD']=$ID;}
	if(trim($ID)==',' or $ID=='')
	{///$numrowp=0
	$queryp="select Monthly_first FROM Analyze_Rulls WHERE CTD_CUST_NO LIKE '%C00001%' and PDD_PROD_NO='".$pdd_no."'";
	$resultp = mssql_query($queryp);
	$numrowp=mssql_num_rows($resultp);
	if($numrowp>0){
		while($rowp = mssql_fetch_row($resultp))
		{
			$ID=$rowp[0].",";
			//echo $ID1;
		}//$row['AND_ITEM']
	}
	}
	echo '<tr>';
	
    echo '<td>'.$row['AND_APPLY_DATE']."-".$row['AND_NEED_NO'].'</td>';
	echo '<td>'.$cus->csname.'</td>';
    echo '<td>'.$row['PDD_PROD_NAME'].'</td>';
    echo '<td>'.$row['PDD_TYPE'].'</td>';
//	echo '<td>'.$row['AND_LOT_NO'].'</td>';
    echo '<td><a target="_blank" href="index.php?url=anylize_list_all&items='.$ID.'&type='.$row['PDD_TYPE'].'&lot_no='.$row['AND_LOT_NO'].'&pid='.$row['PDD_CHEMICAL'].'">'.$row['AND_LOT_NO'].'</a></td>';
    echo '<td>'.$note.'</td>';
    echo '<td>'.$rpd.'</td>';
//	echo $row['AND_ITEM']."<BR>";
	if(substr($row['AND_ITEM'],-1)==','){$row['AND_ITEM']=substr($row['AND_ITEM'],0,-1);}
	
	analyzereport1($ID,trim($row['AND_LOT_NO']),$row['EMP_NAME'],$row['PDD_CHEMICAL'],$pdd_no);

/*    echo '<td><a target="_self" href="'.$url.$row['AND_LOT_NO']."&testdate=".$row['AND_APPLY_DATE']."&operator=".iconv("big5","utf-8",$row['EMP_NAME']).'">ASSAY</a></td>';
    echo '<td><a target="_self" href="'.$ur2.$row['AND_LOT_NO']."&testdate=".$row['AND_APPLY_DATE']."&operator=".iconv("big5","utf-8",$row['EMP_NAME']).'">NH4</a></td>';
	    echo '<td><a target="_self" href="'.$ur3.$row['AND_LOT_NO']."&testdate=".$row['AND_APPLY_DATE']."&operator=".iconv("big5","utf-8",$row['EMP_NAME']).'">UV</a></td>';
*/
	echo '</tr>';
}
}

function analyzereport1($str,$lot_no,$operator,$pdd_chemical,$pdd_prod_no){
$_SESSION['STR']='';
include("../connections/conn.php");
$_SESSION['STR']=$str;
// echo '</br>'.substr($_SESSION['STR'],0,-1).'</br>';
$aa=explode(',',substr($_SESSION['STR'],0,-1));
	$query="SELECT DISTINCT ANI_GROUPNAME
FROM              AnalyzeItem
WHERE          (ANI_INDEX <>'') and ";
for($i=0;$i<count($aa);$i++){
if ($i<(count($aa)-1)){
	$query.="(ANI_INDEX =".$aa[$i].") OR ";}
else {$query.="(ANI_INDEX =".$aa[$i].")";}	
}
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
echo '<td>';
//
while($row = mssql_fetch_array($result)){
$table=get_table($row['ANI_GROUPNAME'],$pdd_chemical);
$rec=new recent;
$rec->table=$table;
$rec->pdd_prod_no=$pdd_prod_no;
$rec->lot_no=$lot_no;
$rec->read_recent();
$lotdata=base64_encode($rec->lotdata);
echo '<a target="_self" href=index.php?url=input_report_all&lot_no='.$rec->lot_no."&lotdata=".$lotdata."&pdd_prod_no=".$pdd_prod_no."&ani_groupname=".
$row['ANI_GROUPNAME']."&pdd_chemical=".$pdd_chemical."&operator=".$operator.' '.'><span style="background-color: '.$ok1.'"><font color="'.$ok.'">'.$row['ANI_GROUPNAME'].',  </font></span> </a>
';
}
echo '</td>';
}
?>
</table>
<br>
<?php
class recent{
	public $lotdata,$ani_group,$table,$pdd_chemical,$pdd_prod_no,$lot_no;
	function read_recent(){
		$query="SELECT  top 1   TAB.*, AD.AND_GOODS
FROM              ".$this->table." as TAB INNER JOIN
                            AnalyzeDesign as AD ON TAB.LotNo = AD.AND_LOT_NO where TAB.LotNo='".$this->lot_no."' order by AnalyzeTime desc";
//		echo $query;
		$result = mssql_query($query);
		$numRows = mssql_num_rows($result);
		if($numRows==0){
			$query="SELECT top(1) TAB.LotNo, AD.AND_GOODS 
FROM              ".$this->table." AS TAB INNER JOIN
                            AnalyzeDesign AS AD ON TAB.LotNo = AD.AND_LOT_NO
WHERE          (AD.AND_GOODS = '".$this->pdd_prod_no."') AND (TAB.Ok = 1)
ORDER BY   TAB.AnalyzeTime DESC";
			$result=mssql_query($query);
			$row=mssql_fetch_row($result);
			$this->lotdata=$row[0];
//			echo $query."<BR>";	
		}///end numrows>0
		else{
			$this->lotdata=$this->lot_no;
		}
	}///end read recent
}

function get_ani_sinetics($cid,$pid)
{
	if($cid==''){$cid='C00001';}
	$query="SELECT DISTINCT TOP (1) QC_Spec.SpecNo, QC_Spec.SpecVer
FROM              AnalyzeItem INNER JOIN
                            QC_CustProdSpec INNER JOIN
                            QC_Spec ON QC_CustProdSpec.SpecNo = QC_Spec.SpecNo AND QC_CustProdSpec.SpecVer = QC_Spec.SpecVer ON 
                            AnalyzeItem.ANI_FULLNAME = QC_Spec.ItemName
WHERE          (QC_CustProdSpec.CustNo = '".$cid."') AND (QC_CustProdSpec.ProdNo = '".$pid."')";
$result=mssql_query($query);
$row=mssql_fetch_row($result);
$qcspec=$row[0];
$qcver=$row[1];
$query="SELECT          AnalyzeItem.ANI_INDEX
FROM              QC_Spec INNER JOIN
                            AnalyzeItem ON QC_Spec.ItemName = AnalyzeItem.ANI_FULLNAME
WHERE          (QC_Spec.SpecNo = '".$qcspec."') AND (QC_Spec.SpecVer = '".$qcver."')";
$result=mssql_query($query);
while($row=mssql_fetch_array($result)){
		$item_sinetics=$item_sinetics.$row['ANI_INDEX'].",";
	}
	return $item_sinetics;
}
?>