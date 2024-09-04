<?php 
session_start();
include("../lib/fun.php");
include("../checkuser.php");
include("../lib/jtsai.php");
lasturl();
datepick(); 
include("../lib/user_right.php");
$a1=array_user_group($_SESSION['uid'],'QA');
$a2=array_user_group($_SESSION['uid'],'分析主管');
if($a1==0){ $disable1=' disabled="disabled" ';}else{ $disable1='';}
if($a2==0){ $disable2=' disabled="disabled" ';}else{ $disable2='';}
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
         &nbsp;&nbsp;&nbsp;
        <input type="checkbox" name="rut" />非定常分析          
        &nbsp;&nbsp;&nbsp;<input type="checkbox" name="urgent1">急件
        &nbsp;&nbsp;&nbsp;<input type="checkbox" name="signed">未簽核
				&nbsp;&nbsp;&nbsp;<input type="submit" name="search" id="search" value="    搜  尋   " />
				&nbsp;&nbsp;&nbsp;<input type="button" name="exit" id="exit" value="離開" />
          <input type="hidden" name="mm_insert" id="mm_insert" value="form1" />

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
$query="SELECT DISTINCT 
                            AnalyzeDesign.AND_CANCEL, AnalyzeDesign.AND_REPORT_DATETIME, EMPLOYEE_DATA.EMP_NAME, 
                            AnalyzeDesign.AND_MEMO, AnalyzeDesign.AND_LOT_NO, AnalyzeDesign.AND_APPLY_DATE, 
                            PRODUCT_DATA.PDD_TYPE, PRODUCT_DATA.PDD_PROD_NO, PRODUCT_DATA.PDD_CHEMICAL, 
                            PRODUCT_DATA.PDD_PROD_NAME, AnalyzeDesign.AND_ITEM, AnalyzeDesign.AND_NEED_NO, 
                            PRODUCT_TYPE.department, AnalyzeDesign.rut, AnalyzeDesign.AND_SMP_DATETIME, 
                            Analyze_Urgent.Lot_No
FROM              Analyze_Urgent RIGHT OUTER JOIN
                            EMPLOYEE_DATA INNER JOIN
                            AnalyzeDesign ON EMPLOYEE_DATA.EMP_NO = AnalyzeDesign.AND_PERSON INNER JOIN
                            PRODUCT_DATA ON AnalyzeDesign.AND_GOODS = PRODUCT_DATA.PDD_PROD_NO ON 
                            Analyze_Urgent.Lot_No = AnalyzeDesign.AND_LOT_NO LEFT OUTER JOIN
                            Ani_Signatory ON AnalyzeDesign.AND_LOT_NO = Ani_Signatory.LotNo LEFT OUTER JOIN
                            PRODUCT_TYPE ON PRODUCT_DATA.PDD_PROD_NO = PRODUCT_TYPE.PDD_PROD_NO 
WHERE         (PRODUCT_TYPE.department = N'EL分析課內') ";

if ($_POST['pid']){
	$query=$query." AND (AnalyzeDesign.AND_GOODS = '".$_POST['pid']."')";
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
if($_POST['urgent1']=='on'){
	$query=$query." and (Analyze_Urgent.canceled IS NOT NULL and active =1 )";
}

if($_POST['signed']=='on'){
	$query=$query." and Ani_Signatory.cancel IS NULL ";
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
// if($_POST['rut']=='on'){$query.=" and (rut=1) ";}else{$query.=" and (rut<>1) ";	}

$query.=" ORDER BY AND_APPLY_DATE DESC";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
?>
<form method="post"  action="group_signatory.php" target="_blank">
<table width="1240" border="1">
  <tr bgcolor="#CCCCCC">
    <td height="49" width="80">日期序號</td>
    <td width="30">客戶</td>
    <td width="84">品名</td>
    <td width="29">容器</td>
    <td width="67">LotNO</td>
    <td width="158">備註1</td>
    <td width="95">要求完成時間</td>
    <td width="603"><p>分析項目:<font color="#0000FF">藍字</font>表示已有報告及結果，<font color="#FF0000">紅字</font>表示沒有報告及結果，<font color="green">綠字</font>表示有報告但是無檢驗結果，</p>
    <p><font color="purple">紫色</font>表示沒有報告但是有檢驗結果‧<span style="background-color: red"><font color="white">紅底白字</font></span>表示檢驗未通過‧</p></td>
		<td width="131">簽核<BR><input type="submit" name="sign_selected" value="簽核(勾選)" <?php echo $disable2; ?>><br><BR><input type="submit" name="urgent" value="急件確認" <?php echo $disable1; ?>></td>
  </tr>
<?php
$p=0;
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
	analyzereport($row['AND_ITEM'],trim($row['AND_LOT_NO']),$row['EMP_NAME'],$row['PDD_CHEMICAL'],$row['PDD_PROD_NO'],$row['AND_REPORT_DATETIME']);
/*    echo '<td><a target="_self" href="'.$url.$row['AND_LOT_NO']."&testdate=".$row['AND_APPLY_DATE']."&operator=".iconv("big5","utf-8",$row['EMP_NAME']).'">ASSAY</a></td>';
    echo '<td><a target="_self" href="'.$ur2.$row['AND_LOT_NO']."&testdate=".$row['AND_APPLY_DATE']."&operator=".iconv("big5","utf-8",$row['EMP_NAME']).'">NH4</a></td>';
	    echo '<td><a target="_self" href="'.$ur3.$row['AND_LOT_NO']."&testdate=".$row['AND_APPLY_DATE']."&operator=".iconv("big5","utf-8",$row['EMP_NAME']).'">UV</a></td>';
*/
echo '<td><input type="checkbox" name="chkbox[]" value="'.$row['AND_LOT_NO'].'">';
	 check_status(trim($row['AND_LOT_NO']));
	 $urgent=check_urgent(trim($row['AND_LOT_NO']));
	 echo '<BR><input type="checkbox" name="chkbox'.$p.'"'.$urgent.' >急件';
	 echo '<BR><input type="hidden" name="Lot_No'.$p.'" value="'.$row['AND_LOT_NO'].'" >';
	echo '</td>';
	echo '</tr>';
	$p++;
}
echo '<input type="hidden" name="count" value="'.$p.'" >';
}
?>
</table>
  </form>
<br>--------<br>

<?php	
if(isset($_POST['urgent'])){
	for($o=0;$o < $_POST['count'];$o++){
	//	echo $_POST['Lot_No'.$o].":".$_POST['chkbox'.$o]."<BR>";
		if($_POST['chkbox'.$o]==''){
			$g=0;
		}
		if($_POST['chkbox'.$o]=='on'){
			$g=1;
		}
		$query="select top(1) active from Analyze_Urgent where Lot_No='".$_POST['Lot_No'.$o]."' and canceled=0";
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);
		
		if($row[0]<>$g){
			$query="update Analyze_Urgent set active=".$g.",canceled=1,last_modify_uid='".$_SESSION['uid']."',last_modify_datetime='".date("Y-m-d H:i:s")."' where Lot_No='".$_POST['Lot_No'.$o]."' and canceled=0";
			$result=mssql_query($query);
			
			$query="INSERT INTO Analyze_Urgent (Lot_No, create_datetime, creator, canceled, active)
							VALUES          (N'".$_POST['Lot_No'.$o]."', CONVERT(DATETIME, '".date("Y-m-d H:i:s")."', 102), N'".$_SESSION['uid']."', 0,".$g. ")";
			$result=mssql_query($query);
			echo "Update ".$_POST['Lot_No'.$o]."<BR>";
			}
			else{
				echo "Passed ".$_POST['Lot_No'.$o]."<br>";
			}
			/*
			$query="update Analyze_Urgent set active=".$g.",canceled=1,last_modify_uid='".$_SESSION['uid']."',last_modify_datetime='".date("Y-m-d H:i:s")."' where Lot_No='".$_POST['Lot_No'.$o]."' and canceled=0";
			$result=mssql_query($query);
			$query="INSERT INTO Analyze_Urgent (Lot_No, create_datetime, creator, canceled, active)
							VALUES          (N'".$_POST['Lot_No'.$o]."', CONVERT(DATETIME, '".date("Y-m-d H:i:s")."', 102), N'".$_SESSION['uid'].'", 0,'.$g. ")";
			echo $query."<BR>";
			$result=mssql_query($query);
			*/
	}
}
if(isset($_POST['sign_selected'])){
		
	$cc=$_POST['chkbox'];
 	$n=count($cc);
 	$lotno_str='';
	for($x=0;$x<$n;$x++){
		echo "Lot NO: ".$cc[$x]."<BR>";
		$lotno_str  = $lotno_str.$cc[$x].",";
	}
	$lotno_str=substr($lotno_str,0,-1);
	$url='../cal/index.php?url=signatory&lotno='.$lotno_str;
	echo '<a href="/cal/index.php?url=signatory&lotno='.$lotno_str.'" target="_new" title="">合併簽核</a>';
	echo '<script>windows.open('.$url.');</script>';
	
	echo '<script>document.location.href="'.$url.'";</script>';
}

function check_urgent($lotno){
	$query1="SELECT active FROM Analyze_Urgent where canceled=0 and Lot_No='".$lotno."'";
	$result1=mssql_query($query1);
	$row=mssql_fetch_row($result1);
	if($row[0]==1){
		return ' checked="checked" ';
	}
	else{
		return  ' ';
	}
}

function check_status($lotno,$disable1){
	$query1="SELECT Signatory_Name, sign_items, Signatory_ID, LotNo, sign_datetime FROM Ani_Signatory where LotNo='".$lotno."' and cancel=0 order by sign_datetime desc";
	$result1=mssql_query($query1);
	if($row1=mssql_fetch_row($result1)){
		$o= $row1[0];
	}
	else{
		$o= '簽核';
	}
	$url='../cal/index.php?url=signatory&lotno='.$lotno;
	if($disable1==1){
		echo '<a target="_blank" href="'.$url.'">'.$o.'</a>';
	}
	else{
		echo $o;
	}
}

function fill_flow($lotno){
	$query="SELECT	Fill_Flow_Chart.flow FROM Fill_Flow_Chart WHERE (Lot_No = N'".$lotno."') order by date desc";
	$url="edit_flow.php?lot_no=".$lotno;
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	if(trim($row[0])==''){$a='編輯';}else{$a=$row[0];}
	$A='<a target="_blank" href="'.$url.'">'.$a.'</a>;'.$a;
	$B=explode(";",$A);
	return $B;	
}
?>