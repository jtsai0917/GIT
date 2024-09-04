<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
datepick();
if(isset($_POST['add'])){
	$i=count($_SESSION['aa']);

	if($_POST['total']<>''){
		$_SESSION['aa'][$i]=array($_POST['coa'],$_POST['total'],$_POST['pla'],dod($_POST['datepicker1']));
	}
}///end add

//$_SESSION['aa']=array("製造商Lot No","數量","棧板編號","製造日期","ContainerNO");
echo '<form method="post"><table width="450" border="1"><tr><td>驗收輸入</td></tr><tr><td>';
echo '製造商Lot No: <input type="text" name="coa" autofocus="autofocus"><BR>';
echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;數量 : <input type="text" name="total"><BR>';
echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;棧板編號: <input type="text" name="pla"><BR>';
echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;製造日期: <input type="text" name="datepicker1" id="datepicker1">';
echo '&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="add" value="新增">&nbsp;&nbsp;<input type="submit" name="submit" value="確認">&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="leave" value="離開"><BR></td></tr></table>';
echo '驗收數量:<BR>';
echo '<table width="450" border="1"><tr bgcolor="#CCCCCC"><td width="150">Lot No</td><td width="70" align="center">數量</td><td width="100">PLT NO</td><td width="100">製造日期</td><td width="30">取消</td></tr>';
	$x=0;
	foreach($_SESSION['aa'] as $v1){
	
	echo '<tr>';
		foreach($v1 as $v2){
			echo "<td>".$v2.'</td>';
		}
	echo '<td width="30"><a href="remove_array.php?id='.$x.'" target="new">刪除</a></td></tr>';
	$x=$x+1;
	}

echo '</form>';



if(isset($_POST['submit'])){
	$query="delete from IN_PLAN_PRODUCT where PDD_PROD_NO = '".$_GET['pid']."' and IPA_PO_NO='".$_GET['po_no']."'";
	$result=mssql_query($query);	
	$query="delete from PRODUCT_RUNNING_ACCOUNT where PDD_PROD_NO = '".$_GET['pid']."' and  IPA_PO_NO = '".$_GET['po_no']."'";
	$result=mssql_query($query);	
	$i=1;
	foreach($_SESSION['aa'] as $v1){
		if($v1[1]<>''){
			$query="INSERT INTO dbo.IN_PLAN_PRODUCT
                            (IPA_PO_NO, IAP_SERIAL_NO, PDD_PROD_NO, IAP_LOT_MAKE_DATE, IAP_UNIT, IAP_QTY, IAP_PLT_NO, 
                            IAP_INWARD_DATE, IAP_STATE, IAP_MAKER_LOT_NO)
					VALUES          ('".$_GET['po_no']."',".$i.",'".$_GET['pid']."','".$v1[3]."','".$_GET['unit']."',".$v1[1].",'".$v1[2]."','".$_GET['inward']."','P','".$v1[0]."')";	
			$result=mssql_query($query);	
			$query="INSERT INTO  PRODUCT_RUNNING_ACCOUNT  (PRA_LOT_NO,PRA_SERIAL_NO,PDD_PROD_NO,PRA_PURPOSE,CTD_CUST_NO,PRA_FAKE_IN_DATE1,IPA_PO_NO) VALUES ('".$v1[0]."',".$i.",'".$_GET['pid']."',1,'".$_GET['unit']."',".$v1[1].",'".$_GET['po_no']."')";
			$result=mssql_query($query);	
		}
	$i=$i+1;
	}
	$query="UPDATE          dbo.IN_PLAN_PRODUCT
SET                   IAP_ORDER_QTY = ".$_GET['total']."
WHERE          (IPA_PO_NO = '".$_GET['po_no']."') AND (IAP_SERIAL_NO = 1)";
	$result=mssql_query($query);
/*	echo '<script>window.close()</script>';   */
	
}/// end submit

if(isset($_POST['leave'])){
	echo '<script>window.close()</script>';	
}
?>
