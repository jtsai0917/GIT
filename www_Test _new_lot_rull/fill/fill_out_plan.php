<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php 
session_start();
include("../lib/fun.php");
include("../lib/jtsai.php");
include("../checkuser.php");
auth('3-02',$_SESSION['aut']);
unset($_SESSION['edit_fill_out']);
unset($_SESSION['change_status']);
unset($_SESSION['sample_metal_f']);
unset($_SESSION['sample_particle_f']);
unset($_SESSION['att_cnt']);
unset($_SESSION['sam_cnt']);
unset($_SESSION['smp_total']);
datepick();
lasturl();
lasturl1();
?>
<form name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <table width="1240" border="1">
    <tr>
      <td width="500">預計出荷日期:
        <input name="datepicker4" type="text" id="datepicker4" size="10" value="<?php echo $_SESSION['datepicker4'] ; ?>"  onchange="set_date_session(this.name,this.value)"> 
          LOT_NO：
        <input name="lot_no" type="text" id="lot_no" size="14"  value="<?php echo $_SESSION['lot_no'];?>" onchange="set_date_session(this.name,this.value)" /></td>
      <td width="500"><span class="d1">藥品名:
          <input type="button" name="X" id="X" value="X" onClick="window.open('../erase_prod.php ', '_self');">
          <input name="pdd_chemical1" type="text" id="pdd_chemical1" size="16" value="<?php echo $_SESSION['pid']?>" readonly>
          <input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onClick="window.open('../main.php?url=pdd_prod_no ', '_self');" >
          <input name="pdd_chemical2" type="text" id="pdd_chemical2" size="20" value="<?php echo get_prod_name($_SESSION['pid']); ?>" readonly>
      </span>&nbsp;</td>
    </tr>
    <tr>
      <td><span class="d1">客戶：
          <input type="button" name="X2" id="X2" value="X" onClick="window.open('../erase_customer.php ', '_self');">
          <input name="pdd_chemical3" type="text" id="pdd_chemical3" size="16" value="<?php echo $_SESSION['cid']?>" readonly>
          <input type="button" name="pdd_no2" id="pdd_no2" value="查詢客戶" onClick="window.open('../main.php?url=cust_no&sup=N ', '_self');">
          <input name="pdd_chemical4" type="text" id="pdd_chemical4" size="20" value="<?php echo get_cust_name($_SESSION['cid']);?>">
      </span></td>
      <td><span class="d1">
        <input name="submit" type="submit" class="center button" id="submit" value="         查      詢         ">
      </span></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<?php 
$editFormAction = $_SERVER['PHP_SELF'];
	echo '<table width="1240" border="1">';
	echo '<tr>
			<td width="40" align="center">修改</td>
			<td width="20" align="center">序號</td>
			<td width="70" align="center">登錄日期</td>
			<td width="70" align="center">輸入人員</td>
			<td width="70" align="center">品名</td>
			<td width="50" align="center">類型</td>
			<td width="70" align="center">客戶名稱</td>
			<td width="70" align="center">LY-NO</td>
			<td width="70" align="center">桶數</td>
			<td width="70" align="center">數量</td>
			<td width="70" align="center">預計充填</td>
			<td width="70" align="center">LOT-NO</td>
			<td width="70" align="center">LORRY SN</td>
			<td width="70" align="center">希望報告時間</td>
			<td width="70" align="center">檢驗項目</td>
			<td width="70" align="center">先行COA</td>
			<td width="70" align="center">出荷時間</td>
			<td width="70" align="center">回廠時間</td>
			<td width="70" align="center">先行樣品</td>
			<td width="70" align="center">取樣瓶數</td>
			</tr>';
/// DM 
		$query="SELECT      FDM.FDM_SERIAL_NO AS a1, FDM.FDM_CREATE_DATE AS b1, EMP.EMP_NAME AS c1, 
                            FDM.FDM_CREATOR, PDD.PDD_PROD_NAME AS d1, PDD.PDD_TYPE, PDD.PDD_PROD_NO AS Prodno,
                            CTD.CTD_CUST_NO as cid, CTD.CTD_CUST_SHORT_NAME AS f1, FDM.FDM_LY_NO AS [lyno], FDM.FDM_QTY_DRUM AS g1, 
                            FDM.FDM_QTY AS h1, LEFT(FDM.FDM_EXPECT_DATE, 8) AS i1, RIGHT(FDM.FDM_EXPECT_DATE, 6) 
                            AS j1, FDM.FDM_LOT_NO AS [lotno], FDM.FDM_ITEM AS 分析預定, 
                            FDM.FDM_RESULT_DATE AS k1, FDM.FDM_COA_BEFORE AS [l1], 
                            FDM.FDM_OUT_DATE AS m1, FDM.FDM_BACK_DATE AS n1, FDM.FDM_SAM_BEFORE AS o1,
                             ISNULL(FDM.FDM_SAM_CNT, 0) + ISNULL(FDM.FDM_SAM_BEF_CNT, 0) + ISNULL(FDM.FDM_ATTACH_CNT, 0) +2 
                            AS p1, ' ' AS q1, FDM.FOD_YEAR_MONTH, FDM.FOD_DAY, FDM.FOD_O_YEAR_MONTH, 
                            FDM.FOD_O_DAY, FDM.PDD_PROD_NO, PDD.PDD_UNIT, PDD.PDD_PROD_SHORT_NAME, PDD.PDD_DRUM_KG, 
                            FDM.CTD_CUST_NO AS Expr1, FDM.CTD_CUST_NO_GROUP, FDM.FDM_SPECIFIC, FDM.FDM_SAM_CNT, 
                            FDM.FDM_SAM_BEF_CNT, FDM.FDM_ATTACH_CNT, FDM.FDM_LY_NO, FDM.FOD_UNI, 
                            AnalyzeDesign.AND_ITEM, AnalyzeDesign.AND_ITEM, FDM.LY_SN as lysn , PDD.PDD_LITER_KG , AnalyzeDesign.sample_metal_f as p11, 
                            AnalyzeDesign.sample_particle_f as p12
				FROM              FILLPLAN_OUT_DECIDE AS FDM LEFT OUTER JOIN
                            AnalyzeDesign ON FDM.FDM_LOT_NO = AnalyzeDesign.AND_LOT_NO LEFT OUTER JOIN
                            EMPLOYEE_DATA AS EMP ON FDM.FDM_CREATOR = EMP.EMP_NO LEFT OUTER JOIN
                            PRODUCT_DATA AS PDD ON FDM.PDD_PROD_NO = PDD.PDD_PROD_NO LEFT OUTER JOIN
                            CUSTOMER_DATA AS CTD ON FDM.CTD_CUST_NO = CTD.CTD_CUST_NO ";
				if($_POST['lot_no']<>''){$query.=" WHERE          (FDM.FDM_LOT_NO LIKE '%".$_POST['lot_no']."%') ";}
				else{
					$query.=" WHERE          (FDM.FOD_O_YEAR_MONTH = '".dtm($_SESSION['datepicker4'])."') AND (FDM.FOD_O_DAY = '".dtd($_SESSION['datepicker4'])."') ";
					}
				if($_SESSION['pdd_chemical1']){$query.= " AND PDD.PDD_PROD_NO = '".$_SESSION['pdd_chemical1']."'";}
				if($_SESSION['pdd_chemical3']){$query.= " AND CTD.CTD_CUST_NO = '".$_SESSION['pdd_chemical3']."'";}
				$query.=" order by b1";

		$result = mssql_query($query);
		while ($row = mssql_fetch_array($result))
		{
			if($row['PDD_UNIT']=='L'){
				$row['h1']=$row['h1']/$row['PDD_LITER_KG'];
			}
			echo '<tr>';
			echo '<td>';

echo '<a target="_self" href="./index.php?url=edit_fill_out&AND_LOT_NO='.$row['LOT-NO'].'&cdt='.$row['b1'].'&ct='.$row['FDM_CREATOR'].
			'&pid='.$row['PDD_PROD_NO'].'&style='.$row['PDD_TYPE'].'&cid='.$row['cid'].'&lyno='.$row['lyno'].'&qtydm='.$row["g1"].
			'&qtyun='.$row['PDD_UNIT'].'&qty='.$row['h1'].'&sn='.$row['a1'].'&uni='.$row['FOD_UNI'].'&datepicker4='.dod($_POST['datepicker4']).'&outdate='.$row['k1'].'">編輯</a></br>';
			if(strtoupper($row['FDM_CREATOR'])==strtoupper($_SESSION['uid'])){ echo '
			<a target="_self" href="./index.php?url=delete_fill_out&uni='.$row['FOD_UNI'].'">刪除</a>';}
			if($row['lysn']==0){$row['lysn']='';}
			echo '</td>';
			echo '<td>'.$row['a1']."</td>";
			echo '<td>'.$row['b1']."</td>";
			echo '<td>'.$row['c1']."</td>";
			echo '<td>'.$row['d1']."</td>";
			echo '<td>'.$row['PDD_TYPE']."</td>";
			echo '<td>'.$row['f1']."</td>";
			echo '<td>'.$row['lyno']."</td>";
			echo '<td>'.$row["g1"]."</td>";
			echo '<td><font color="red">'.$row['h1']."</font>  ".$row['PDD_UNIT']."</td>";
			echo '<td>'.$row['i1']."</br>".$row['j1']."</td>";
			echo '<td>'.$row['lotno']."</td>";
			echo '<td>'.$row['lysn']."</td>";
			echo '<td>'.$row['k1']."</td>";
			echo '<td>'.show_item($row['AND_ITEM'],$row['PDD_PROD_NO'],$row['Expr1'])."</td>";
			echo '<td>'.$row['l1']."</td>";
			echo '<td>'.$row['m1']."</td>";
			echo '<td>'.$row['n1']."</td>";
			echo '<td>'.$row['o1']."</td>";
			$smpcnt=$row['p1']+$row['p12']+$row['p11'];
			echo '<td>'.$smpcnt."</td>";
			echo '</tr>';
		}			
///end DM

echo "</table></br></br>";


if(isset($_POST["submit"]))
{
	$_SESSION['dapic1']=$_SESSION['datepicker4']=$_POST['datepicker4'];
	$_SESSION['pdd_chemical3']=$_SESSION['cid']=$_POST['pdd_chemical3'];
	$_SESSION['prod_no']=$_POST['pdd_chemical1'];
}	

function show_item($itm,$pid,$cid)
{
	$query="SELECT          [index], CTD_CUST_NO, CTD_CUST_Group, Total_Monthly_1st, Total_Duration_times, Creat_dt, Creator, Memo, 
                            PDD_PROD_NO, eachday, eachday_first, Rull_Total, Rull_Reg
			FROM              Analyze_Rulls
			WHERE          (CTD_CUST_NO LIKE '%".$cid."%') AND (PDD_PROD_NO = '".$pid."')"	;
	$result = mssql_query($query);
	while ($row = mssql_fetch_array($result))
	{		
		$total=$row['Rull_Total'];
		$reg=$row['Rull_Reg'];
	}
	if($itm==$total){return "全項";}
	elseif($itm==$reg){return "常規";}
	elseif($itm<>''){return "AMP";}
	else{return "";}
}
?>

