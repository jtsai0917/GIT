<?php 
include('../connections/conn.php');
include('../lib/fun.php');
session_start();
?>
<head>
<title>TYS 化學藥品充填及管理系統</title>
<meta http-equiv="content-type" content="text/html; charset=big5" />
<link rel="stylesheet" href="/css3menu/mbcsmbmcp.css" type="text/css" />
</head>
<body>
<form name="form1" enctype="multipart/form-data" method="post" action="<?php echo $loginFormAction; ?>">
  <label for="file"></label>
  <input type="file" name="file" id="file">
  <input type="submit" name="submit" id="submit" value="確定">
</form>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
$pd= new pdd_type;			
if(isset($_POST["submit"]))
{
				echo '<table border="1">
  <tr>
    <td>轉檔日期</td>
    <td>採購單號</td>
    <td>廠商編號</td>
    <td>廠商名稱</td>
    <td>幣別</td>
    <td>匯率</td>
    <td>產品編號</td>
    <td>數量</td>
    <td>單位</td>
    <td>單價</td>
    <td>入荷預定日</td>
    <td>產品簡稱</td>
    <td>EXC</td>
    <td>會計編號</td>
    <td>備註</td>
	<td>Select</td>
  </tr><form name="form2"  method="post" action="">';
				move_uploaded_file($_FILES["file"]["tmp_name"],"filetmp/".$_FILES["file"]["name"]);
				$fp=fopen("filetmp/".$_FILES["file"]["name"],"r");
				$filename=$_FILES["file"]["name"];
				$ext= substr(strrchr($filename, '.'), 1);
				$row = 0;
				$array[0][0]='';
				if($ext=='txt')
				{
					while ($data=fgetcsv($fp,400,";")) 
						{
							$num = count($data);
							echo '<tr>';
							for ($c=0; $c < $num; $c++) 
							{
								$array[$row][$c]=$data[$c];
								echo '<td>'.$array[$row][$c].'</td>';
								echo '<input name="x'.$row.'y'.$c.'" type="hidden" id="x'.$row.'y'.$c.'" size="10" value="'.$array[$row][$c].'"/>';
							}
							echo '</tr>';
							$row=$row+1;
						 }
					fclose($fp);
					echo '<input name="row" type="hidden" id="row" size="10" value="'.$row.'"/>';
					echo '<input name="po_no" type="hidden" id="po_no" size="10" value="'.$array[0][1].'"/>';
				}
				
				if($ext=='xls' or $ext=='xlsx')
				{
//					echo "filetmp/".$_FILES["file"]["name"];
					require_once("../connections/conn.php");
					require_once "../PHPEXCEL/Classes/PHPExcel.php";
					require_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";

					$reader= PHPExcel_IOFactory::createReaderForFile("./filetmp/".$_FILES["file"]["name"]);
					$reader->setReadDataOnly(true);
					$excel= $reader->load("./filetmp/".$_FILES["file"]["name"]);	
					$sheetCount = $excel->getSheetCount();
					$sheetNames = $excel->getSheetNames();
					$sheet = $excel->getActiveSheet(0); //讀取第一個工作表(編號從 0 開始)
					$colString = $sheet->getHighestColumn(); //最大欄位的英文代號
					$highestColumns = PHPExcel_Cell::columnIndexFromString($colString); //最大欄位的數字編號。A=0, B=1, C=2....
					$highestRows = $sheet->getHighestRow(); //最高行數。從1開始 
					$SheetName = $sheetNames[0];
					$excelData[$s]['SheetName'] = $SheetName;
					$row=0;
					for($x=2;$x<=$highestRows;$x++)
					{
						echo '<tr>';
							{ // 0 = A欄
								$array[$row][$c]=$data[$c];
								
								$v0 = trim($sheet->getCellByColumnAndRow(0,$x)->getValue());
								$t = PHPExcel_Shared_Date::ExcelToPHP($v0);
								echo "<td>".dod2(date("Ymd",$t))."</td>";	
								echo '<input name="x'.$row.'y0" type="hidden" id="x'.$row.'y0" size="10" value="'.dod2(date("Ymd",$t)).'"/>';
								
								$v1 = trim($sheet->getCellByColumnAndRow(1,$x)->getValue());	
								echo "<td>".$v1."</td>";
								echo '<input name="x'.$row.'y1" type="hidden" id="x'.$row.'y1" size="10" value="'.$v1.'"/>';
								
								$v2 = trim($sheet->getCellByColumnAndRow(14,$x)->getValue());
								echo "<td>".$v2."</td>";
								echo '<input name="x'.$row.'y2" type="hidden" id="x'.$row.'y2" size="10" value="'.$v2.'"/>';
								
								$v3 = trim($sheet->getCellByColumnAndRow(2,$x)->getValue());
								echo "<td>".iconv("utf-8","big5",$v3)."</td>";
								echo '<input name="x'.$row.'y3" type="hidden" id="x'.$row.'y3" size="10" value="'.iconv("utf-8","big5",$v3).'"/>';
								
								echo "<td></td>";
								echo "<td></td>";
								
								$v4 = trim($sheet->getCellByColumnAndRow(15,$x)->getValue());
								echo "<td>".$v4."</td>";
								echo '<input name="x'.$row.'y6" type="hidden" id="x'.$row.'y6" size="10" value="'.$v4.'"/>';
								
								$v5 = trim($sheet->getCellByColumnAndRow(5,$x)->getValue());
								echo "<td>".$v5."</td>";
								echo '<input name="x'.$row.'y7" type="hidden" id="x'.$row.'y7" size="10" value="'.$v5.'"/>';
								
								$v6 = trim($sheet->getCellByColumnAndRow(6,$x)->getValue());		
								echo "<td>".$v6."</td>";
								echo '<input name="x'.$row.'y8" type="hidden" id="x'.$row.'y8" size="10" value="'.$v6.'"/>';
								
								echo "<td></td>";
								
								$v7 = trim($sheet->getCellByColumnAndRow(7,$x)->getValue());
								$t1 = PHPExcel_Shared_Date::ExcelToPHP($v7);		
								echo "<td>".dod2(date("Ymd",$t1))."</td>";
								echo '<input name="x'.$row.'y10" type="hidden" id="x'.$row.'y10" size="10" value="'.dod2(date("Ymd",$t1)).'"/>';
								
								$v8 = trim($sheet->getCellByColumnAndRow(8,$x)->getValue());		
								echo "<td>".iconv("utf-8","big5",$v8)."</td>";
								echo '<input name="x'.$row.'y11" type="hidden" id="x'.$row.'y11" size="10" value="'.iconv("utf-8","big5",$v8).'"/>';
								
								$v9 = trim($sheet->getCellByColumnAndRow(9,$x)->getValue());		
								echo "<td>".$v9."</td>";
								echo '<input name="x'.$row.'y12" type="hidden" id="x'.$row.'y12" size="10" value="'.$v9.'"/>';
								
								echo "<td></td>";
								
								$v10 = trim($sheet->getCellByColumnAndRow(10,$x)->getValue());		
								echo "<td>".iconv("utf-8","big5",$v10)."</td>";	
								echo '<input name="x'.$row.'y14" type="hidden" id="x'.$row.'y14" size="10" value="'.iconv("utf-8","big5",$v10).'"/>';
								
								echo "<td></td>";		
							}
						echo '</tr>';$row=$row+1;
						echo '<input name="row" type="hidden" id="row" size="10" value="'.$row.'"/>';
						echo '<input name="po_no" type="hidden" id="po_no" size="10" value="'.$array[0][1].'"/>';
						
					}
				}
				
	echo '</table><input type="submit" name="submit1" id="submit1" value="匯入">';
	echo '</form>';		
}

if(isset($_POST["submit1"]))
{
	$row=$_POST['row'];
	for($a=0;$a<$row;$a++){
//		echo '<BR>$A:'.$a."<BR>";
		$sg=new sag;
		$sg->uid=$_SESSION['uid'];
		$pd->pid=$_POST['x'.$a.'y6'];
		$pd->pono=$_POST['x'.$a.'y1'];
		$pd->get_ab();
		$typ=$pd->ab;
		
		////新增 SIGN_AGREE
		$sg->pidtype=$typ;
		$sg->cbt_no();
		////新增 SIGN_AGREE_ITEM
		$sg->write_sign_agree();
		$sg->sai_sn=1;
		$sg->autno=1002;
		$sg->write_sign_agree_item();
		$sg->sai_sn=2;
		$sg->autno=1009;
		$sg->write_sign_agree_item();
		$sg->sai_sn=3;
		$sg->autno=1014;
		$sg->write_sign_agree_item();	
	

	
	////寫入IN.PLAN
		$query="INSERT INTO dbo.IN_PLAN
                            (IPA_PO_NO, IPA_TYPE, IPA_ETA_DATE, CTD_CUST_NO,SAG_NO)
			VALUES          ('".trim($pd->pono)."','".$typ."','".dod1(trim($_POST['x'.$a.'y10']))."','".trim($_POST['x'.$a.'y2'])."','".trim($sg->sagno)."')";
//			echo "<BR>".$query."<BR>";
			$result = mssql_query($query);
			if (!$result) {
  		  		fun_alert("IN_PLAN 採購單".$pd->pono."重複");
  			}
			else{
//				my_msg("建立入荷計畫");	
			}
			
		////寫入IN_PLAN_FROM_FILE
		$query="INSERT INTO IN_PLAN_FROM_FILE
                   (IPA_PO_NO, CTD_CUST_NO, PDD_PROD_NO, IAF_ORDER_QTY, IAF_UNIT, IPA_ETA_DATE, IAF_ACC_ID, IAF_SERIAL_NO) VALUES  ('".trim($pd->pono)."','".trim($_POST['x'.$a.'y2'])."','".trim($_POST['x'.$a.'y6'])."',".trim($_POST['x'.$a.'y7']).",'".trim($_POST['x'.$a.'y8'])."','".dod1(trim($_POST['x'.$a.'y10']))."','".trim($_POST['x'.$a.'y13'])."',1)	";
			$result = mssql_query($query);
//			my_msg("建立入荷計畫檔");	
	}
			
	for($i=0;$i<$row;$i++)
	{
		$pd->pid=$_POST['x'.$i.'y6'];
		$pd->pono=$_POST['x'.$i.'y1'];
		$pd->get_ab();
		$typ=$pd->ab;	
		
		////寫入IN_PLAN_PRODUCT
		$sno=new posn;
		$sno->pono=$pd->pono;
		$sno->getsn();
		$query="INSERT INTO dbo.IN_PLAN_PRODUCT
                            (IAP_INWARD_DATE, IPA_PO_NO, IAP_SERIAL_NO, PDD_PROD_NO, IAP_ORDER_QTY, IAP_UNIT,  
                             IAP_STATE)
VALUES          ('".dod1(trim($_POST['x'.$i.'y0']))."','".trim($pd->pono)."','".trim($sno->sn)."','".trim($pd->pid)."','".trim($_POST['x'.$i.'y7'])."','".trim($_POST['x'.$i.'y8'])."','0')";
		$result = mssql_query($query);
 	echo "<BR>ROW: ".$row."<BR>";
	
 			if (!$result) 
			{
  		  		fun_alert("IN_PLAN_PRODUCT:".$pd->pono."重複");
  			}
	}
	my_msg("出荷計畫建立完成");		
}
?>
</body>