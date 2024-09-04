<?php 
session_start();
include("../lib/fun.php");
include("../lib/jtsai.php");
include("../checkuser.php");
auth('3-02',$_SESSION['aut']);
unset($_SESSION['edit_fill_out']);
unset($_SESSION['change_status']);
datepick();
lasturl();
?>
<form name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <table width="1240" border="1">
    <tr>
      <td width="500">預計出荷日期:
        <input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php echo $_SESSION['datepicker1'] ; ?>"  onchange="set_date_session(this.name,this.value)"></td>
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

lasturl1();
lasturl();
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
			<td width="70" align="center">數量(L)</td>
			<td width="70" align="center">預計充填</td>
			<td width="70" align="center">LOT-NO</td>
			<td width="70" align="center">希望報告時間</td>
			<td width="70" align="center">檢驗項目</td>
			<td width="70" align="center">先行COA</td>
			<td width="70" align="center">出荷時間</td>
			<td width="70" align="center">回廠時間</td>
			<td width="70" align="center">先行樣品</td>
			<td width="70" align="center">取樣瓶數</td>
			<td width="70" align="center">轉充填</td>
			</tr>';
/// DM 
		$query="SELECT      FDM.FDM_SERIAL_NO AS 序號, FDM.FDM_CREATE_DATE AS 登錄日期, EMP.EMP_NAME AS 輸入人員, 
                            FDM.FDM_CREATOR, PDD.PDD_PROD_NAME AS 藥品名稱, PDD.PDD_TYPE, PDD.PDD_PROD_NO AS Prodno,
                            CTD.CTD_CUST_NO as cid, CTD.CTD_CUST_SHORT_NAME AS 客戶名稱, FDM.FDM_LY_NO AS [LY-NO], FDM.FDM_QTY_DRUM AS 桶數, 
                            FDM.FDM_QTY AS 數量, LEFT(FDM.FDM_EXPECT_DATE, 8) AS 預計充填日期, RIGHT(FDM.FDM_EXPECT_DATE, 6) 
                            AS 預計充填時間, FDM.FDM_LOT_NO AS [LOT-NO], FDM.FDM_ITEM AS 分析預定, 
                            FDM.FDM_RESULT_DATE AS 結果報告希望日時, FDM.FDM_COA_BEFORE AS [先行 COA 要否], 
                            FDM.FDM_OUT_DATE AS 出荷日時, FDM.FDM_BACK_DATE AS 回廠日時, FDM.FDM_SAM_BEFORE AS 先行樣品要否,
                             ISNULL(FDM.FDM_SAM_CNT, 0) + ISNULL(FDM.FDM_SAM_BEF_CNT, 0) + ISNULL(FDM.FDM_ATTACH_CNT, 0) 
                            AS 取樣瓶數, ' ' AS 是否轉充填, FDM.FOD_YEAR_MONTH, FDM.FOD_DAY, FDM.FOD_O_YEAR_MONTH, 
                            FDM.FOD_O_DAY, FDM.PDD_PROD_NO, PDD.PDD_UNIT, PDD.PDD_PROD_SHORT_NAME, PDD.PDD_DRUM_KG, 
                            FDM.CTD_CUST_NO AS Expr1, FDM.CTD_CUST_NO_GROUP, FDM.FDM_SPECIFIC, FDM.FDM_SAM_CNT, 
                            FDM.FDM_SAM_BEF_CNT, FDM.FDM_ATTACH_CNT, FDM.FDM_LY_NO, FDM.FOD_UNI, 
                            AnalyzeDesign.AND_ITEM
				FROM              FILLPLAN_OUT_DECIDE AS FDM LEFT OUTER JOIN
                            AnalyzeDesign ON FDM.FDM_LOT_NO = AnalyzeDesign.AND_LOT_NO LEFT OUTER JOIN
                            EMPLOYEE_DATA AS EMP ON FDM.FDM_CREATOR = EMP.EMP_NO LEFT OUTER JOIN
                            PRODUCT_DATA AS PDD ON FDM.PDD_PROD_NO = PDD.PDD_PROD_NO LEFT OUTER JOIN
                            CUSTOMER_DATA AS CTD ON FDM.CTD_CUST_NO = CTD.CTD_CUST_NO
				WHERE          (FDM.FOD_O_YEAR_MONTH = '".dtm($_SESSION['datepicker1'])."') AND (FDM.FOD_O_DAY = '".dtd($_SESSION['datepicker1'])."') ";
				if($_SESSION['pdd_chemical1']){$query.= " AND PDD.PDD_PROD_NO = '".$_SESSION['pdd_chemical1']."'";}
				if($_SESSION['pdd_chemical3']){$query.= " AND CTD.CTD_CUST_NO = '".$_SESSION['pdd_chemical3']."'";}
				$query.=" order by 序號";
		$result = mssql_query($query);
		while ($row = mssql_fetch_array($result))
		{
			echo '<tr>';
			echo '<td>';

echo '<a target="_self" href="index.php?url=edit_fill_out&AND_LOT_NO='.$row['LOT-NO'].'&cdt='.$row['登錄日期'].'&ct='.$row['FDM_CREATOR'].
			'&pid='.$row['PDD_PROD_NO'].'&style='.$row['PDD_TYPE'].'&cid='.$row['cid'].'&lyno='.$row['LY-NO'].'&qtydm='.$row["桶數"].
			'&qtyun='.$row['PDD_UNIT'].'&qty='.$row['數量'].'&sn='.$row['序號'].'&uni='.$row['FOD_UNI'].'&datepicker1='.dod($_POST['datepicker1']).'&outdate='.$row['出荷日時'].'">編輯</a></br>';
			if(strtoupper($row['FDM_CREATOR'])==strtoupper($_SESSION['uid'])){ echo '
			<a target="_self" href="index.php?url=delete_fill_out&uni='.$row['FOD_UNI'].'">刪除</a>';}

			echo '</td>';
			echo '<td>'.$row['序號']."</td>";
			echo '<td>'.$row['登錄日期']."</td>";
			echo '<td>'.$row['輸入人員']."</td>";
			echo '<td>'.$row['藥品名稱']."</td>";
			echo '<td>'.$row['PDD_TYPE']."</td>";
			echo '<td>'.$row['客戶名稱']."</td>";
			echo '<td>'.$row['LY-NO']."</td>";
			echo '<td>'.$row["桶數"]."</td>";
			echo '<td>'.$row['數量']."</td>";
			echo '<td>'.$row['預計充填日期']."</br>".$row['預計充填時間']."</td>";
			echo '<td>'.$row['LOT-NO']."</td>";
			echo '<td>'.$row['結果報告希望日時']."</td>";
			echo '<td>'.show_item($row['AND_ITEM'],$row['PDD_PROD_NO'],$row['Expr1'])."</td>";
			echo '<td>'.$row['先行 COA 要否']."</td>";
			echo '<td>'.$row['出荷日時']."</td>";
			echo '<td>'.$row['回廠日時']."</td>";
			echo '<td>'.$row['先行樣品要否']."</td>";
			echo '<td>'.$row['取樣瓶數']."</td>";
			echo '<td>'.$row['是否轉充填']."</td>";
			echo '</tr>';
		}			
///end DM

echo "</table></br></br>";


if(isset($_POST["submit"]))
{
	$_SESSION['dapic1']=$_SESSION['datepicker1']=$_POST['datepicker1'];
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

