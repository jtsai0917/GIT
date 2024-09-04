<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
session_start();
auth('9-11',$_SESSION['aut']);
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
datepick();
echo 
'
<form name="" action="" method="post">
輸入要拋轉的 LotNo： <input type="text" name="lotno" size="16" value="'.$_SESSION['lotno'].'" onchange="set_date_session(this.name,this.value)"> <input type="submit" name="get" value="確定"/>
';
if(isset($_POST['get'])){
	echo '<BR>取得資料 <input type="submit" name="trans" value="拋轉"><BR>';
	$query="SELECT DISTINCT 
                            d.OPM_ORDER_NO AS OrderNo, q.LotNo, p.PDD_PROD_NO, d.CTD_CUST_NO, d.OPM_ETA_DATE AS ShipDate, 
                            p.OPD_LOT_NO
FROM              OUT_DECISION AS d RIGHT OUTER JOIN
                            OUT_PRODUCT AS p ON d.OPM_ORDER_NO = p.OPM_ORDER_NO LEFT OUTER JOIN
                            QC_LotData AS q ON p.OPD_LOT_NO = q.LotNo
WHERE          (1 = 1) AND (p.OPD_LOT_NO = '".$_SESSION['lotno']."')
ORDER BY   q.LotNo";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	if($row[5]==''){echo "查無出貨列表<BR>";}
	if($row[4]==''){echo "查無出荷決定書<BR>";}
	if($row[1]==''){echo "查無拋轉資料<BR>";}
	$CUST_NO= $row[3];
	echo '<table border="1" width=""/><tr>';
	$query="SELECT AnalyzeDesign.AND_ITEM, AnalyzeDesign.CTD_CUST_NO, AnalyzeDesign.AND_GOODS, PRODUCT_DATA.PDD_CHEMICAL FROM AnalyzeDesign INNER JOIN PRODUCT_DATA ON AnalyzeDesign.AND_GOODS = PRODUCT_DATA.PDD_PROD_NO WHERE (AND_LOT_NO = '".$_POST['lotno']."')";

	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$str= $row[0];
	
	$AND_GOOD= $row[2];
	$CHEMICAL= $row[3];
	$query="SELECT DISTINCT 
                            ELEMENT_FORM.ELF_FORM, QC_Spec.ShowMode, AnalyzeItem.ANI_DATAFIELD, AnalyzeItem.ANI_NICKNAME, QC_Spec.ItemName,
                            AnalyzeItem.ANI_GROUPNAME, QC_Spec.ItemUnit, QC_CustProdSpec.CustNo 
FROM              AnalyzeItem INNER JOIN
                            ELEMENT_FORM ON AnalyzeItem.ANI_INDEX = ELEMENT_FORM.ELM_ID INNER JOIN
                            QC_Spec ON AnalyzeItem.ANI_FULLNAME = QC_Spec.ItemName INNER JOIN
                            QC_CustProdSpec ON QC_Spec.SpecNo = QC_CustProdSpec.SpecNo AND 
                            QC_Spec.SpecVer = QC_CustProdSpec.SpecVer WHERE (QC_CustProdSpec.CustNo = '".$CUST_NO."') AND (QC_CustProdSpec.ProdNo = '".$AND_GOOD."') AND (ELEMENT_FORM.PDD_CHEMICAL = '".$CHEMICAL."')";
//	echo $query;
//	echo "<BR>";
	$result=mssql_query($query);
	$i=1;
	echo '<table width="800" border="1"><tr><td>序</td><td>分析群組</td><td>項目</td><td>項目名稱</td><td>紀錄表</td><td>欄位名</td><td>樣品瓶號</td><td>報告值</td><td>單位</td><td>Test Date</td><td>報告時間</td></tr>';
	while($rows=mssql_fetch_array($result)){
		$custno=$row['CustNo'];
		$AA=A1($rows['ANI_DATAFIELD'],$rows['ELF_FORM'],$_SESSION['lotno']);
		if($AA[0]<>''and $AA[1]<>'' and $AA[2]<>''){
			echo '<tr><td>'.$i.'</td><td>'.$rows['ANI_GROUPNAME'].'</td><td>'.$rows['ANI_NICKNAME'].'</td><td>'.$rows['ItemName'].'</td><td>'.$rows['ELF_FORM'].'</td><td>'.$rows['ANI_DATAFIELD'].'</td><td>'.$AA[2].'</td><td>'.$AA[1].'</td><td>'.$rows['ItemUnit'].'</td><td>'.$AA[3].'</td><td>'.$AA[0].'</td></tr>
			
			<input type="hidden" name="testdate'.$i.'" value="'.$AA[3].'"><input type="hidden" name="sample_no'.$i.'" value="'.$AA[2].'"><input type="hidden" name="item'.$i.'" value="'.$rows['ANI_NICKNAME'].'"><input type="hidden" name="value'.$i.'" value="'.$AA[1].'"><input type="hidden" name="ELF_FORM'.$i.'" value="'.$rows['ELF_FORM'].'"><input type="hidden" name="ANI_DATAFIELD'.$i.'" value="'.$rows['ANI_DATAFIELD'].'"><input type="hidden" name="ELF_FORM'.$i.'" value="'.$rows['ELF_FORM'].'"><input type="hidden" name="itemname'.$i.'" value="'.$rows['ItemName'].'"><input type="hidden" name="itemname'.$i.'" value="'.$rows['ItemName'].'"><input type="hidden" name="itemunit'.$i.'" value="'.$rows['ItemUnit'].'"><input type="hidden" name="ok'.$i.'" value="'.$AA[4].'"><input type="hidden" name="Tester'.$i.'" value="'.$AA[5].'"><input type="hidden" name="Operator'.$i.'" value="'.$AA[6].'"><input type="hidden" name="anatime'.$i.'" value="'.$AA[0].'">
			';
			$i++;
		}
	}
	echo '<input type="hidden" name="count" value="'.$i.'"><input type="hidden" name="cstno" value="'.$CUST_NO.'"><input type="hidden" name="AND_GOOD" value="'.$AND_GOOD.'"><input type="hidden" name="CHEMICAL" value="'.$CHEMICAL.'">';
	
	echo "##".$CUST_NO."##";
	
	
	/*
	$query="SELECT DISTINCT 
                            ELEMENT_FORM.ELF_FORM, ELEMENT_FORM.PDD_CHEMICAL, AnalyzeItem.ANI_INDEX, AnalyzeItem.ANI_ID, 
                            AnalyzeItem.ANI_NICKNAME, AnalyzeItem.ANI_FULLNAME, AnalyzeItem.ANI_UNIT, AnalyzeItem.ANI_ANR_ID, 
                            AnalyzeItem.ANI_GROUPNAME, AnalyzeItem.ANI_DATAFIELD, AnalyzeItem.ANI_ORDER, QC_Spec.ItemUnit, 
                            QC_CustProdSpec.CustNo
FROM              AnalyzeItem INNER JOIN
                            ELEMENT_FORM ON AnalyzeItem.ANI_INDEX = ELEMENT_FORM.ELM_ID INNER JOIN
                            QC_Spec ON AnalyzeItem.ANI_FULLNAME = QC_Spec.ItemName INNER JOIN
                            QC_CustProdSpec ON QC_Spec.SpecNo = QC_CustProdSpec.SpecNo AND 
                            QC_Spec.SpecVer = QC_CustProdSpec.SpecVer
WHERE          (ELEMENT_FORM.ELM_ID = 21) AND (ELEMENT_FORM.PDD_CHEMICAL = '".$CHEMICAL."') AND 
                            (QC_CustProdSpec.CustNo = '".$CUST_NO."')  AND (QC_CustProdSpec.ProdNo = '".$AND_GOOD."')";
	*/
	
//	echo $query;
//	echo "<BR>";
	$aa=explode(',',$str,-1);
	for($i=0;$i<count($aa);$i++){
		$aa[$i];
//		echo "<BR>";
	}
	echo '</tr></table>';
}
echo '</form>';

if(isset($_POST['trans'])){
	for($i=1;$i<$_POST['count'];$i++){
		$query="INSERT INTO QC_LotData
								(ID, LotNo, SampleNo, TestDate, TestData, OrgTable, OrgField, ProdNo, Chemical, CustNo, ItemName, ItemUnit, OK, 
								Tester, Operator, AnalyzeTime, state, createuser, createts)
	SELECT          MAX(ID + 1) AS Expr1, '".$_SESSION['lotno']."' AS Expr2, '".$_POST['sample_no'.$i]."' AS Expr3, '".$_POST['testdate'.$i]."' AS Expr4, ".$_POST['value'.$i]." AS Expr5, 
								'".$_POST['ELF_FORM'.$i]."' AS Expr6, '".$_POST['ANI_DATAFIELD'.$i]."' AS Expr7, '".$_POST['AND_GOOD']."' AS Expr8, '".$_POST['CHEMICAL']."' AS Expr9, '".$_POST['cstno']."' AS Expr10, '".$_POST['itemname'.$i]."' AS Expr11, '".$_POST['itemunit'.$i]."' AS Expr12, ".$_POST['ok'.$i]." AS Expr13, '".$_POST['Tester'.$i]."' AS Expr14, '".$_POST['Operator'.$i]."' AS Expr15, '".$_POST['anatime'.$i]."' AS Expr16, 'n' AS Expr17, 'adm' AS Expr18, '".date("Y/m/d")."' AS Expr19
	FROM              QC_LotData AS QC_LotData_1"; 

		$result1=mssql_query($query);
		if(!$result1){
			die('Unable to create '.$_POST['itemname'.$i]."<BR>");
		}
	}
}

function A1($field, $table, $lotno){
	$query="SELECT AnalyzeTime, ".$field. ", SampleNo,  CONVERT(varchar(100), TestDate, 111), Ok, Tester, Operator FROM ".$table." WHERE (LotNo = '".$lotno."')";	
	$result=mssql_query($query);
	$A1=mssql_fetch_array($result);
	return $A1;
//	echo $query;
//	echo "<BR>";
}
?>