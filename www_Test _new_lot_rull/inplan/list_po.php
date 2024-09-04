<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php 
include("../connections/conn.php");
include("../lib/fun.php");
include  "../PHPEXCEL/Classes/PHPExcel.php";
include "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
session_start();
$query="SELECT          IN_PLAN.IPA_ETA_DATE, IN_PLAN.IPA_PO_NO, IN_PLAN.IPA_INVOICE_NO, CUSTOMER_DATA.CTD_CUST_NAME, 
                            IN_PLAN_FROM_FILE.PDD_PROD_NO, IN_PLAN_FROM_FILE.IAF_PROD_NAME, PRODUCT_DATA.PDD_STYLE, 
                            IN_PLAN_PRODUCT.IAP_UNIT, PRODUCT_DATA.PDD_PACKAGE, IN_PLAN_PRODUCT.IAP_ORDER_QTY, 
                            IN_PLAN_PRODUCT.IAP_QTY, IN_PLAN.IPA_IN_DATE, IN_PLAN.IPA_TYPE, 
                            IN_PLAN_FROM_FILE.IPA_PO_NO AS Expr1, IN_PLAN_PRODUCT.IAP_STATE, IN_PLAN_PRODUCT.IAP_LOT_NO, 
                            IN_PLAN_PRODUCT.IAP_INWARD_DATE, CUSTOMER_DATA.CTD_SUPPLIER, IN_PLAN.IPA_CUSTOMS_NO, 
                            IN_PLAN.IPA_CONTAINER_NO, dbo.IN_PLAN_PRODUCT.IAP_PLT_NO,dbo.IN_PLAN.IPA_IN_DATE, PRODUCT_DATA.PDD_CLASS 
FROM              IN_PLAN INNER JOIN
                            IN_PLAN_FROM_FILE ON IN_PLAN.IPA_PO_NO = IN_PLAN_FROM_FILE.IPA_PO_NO INNER JOIN
                            IN_PLAN_PRODUCT ON IN_PLAN.IPA_PO_NO = IN_PLAN_PRODUCT.IPA_PO_NO INNER JOIN
                            CUSTOMER_DATA ON IN_PLAN.CTD_CUST_NO = CUSTOMER_DATA.CTD_CUST_NO INNER JOIN
                            PRODUCT_DATA ON IN_PLAN_FROM_FILE.PDD_PROD_NO = PRODUCT_DATA.PDD_PROD_NO
WHERE          (IN_PLAN.IPA_PO_NO = '".$_GET['po_no']."')";
// echo $query."<BR>";
$result = mssql_query($query);
$IAP_QTY=0;
while($row = mssql_fetch_array($result))
{
	$IPA_CUSTOMS_NO= $row['IPA_CUSTOMS_NO'];
	if ($row['IAP_ORDER_QTY']<>0){
	$IAP_ORDER_QTY= $row['IAP_ORDER_QTY'];}
	$IPA_ETA_DATE=$row['IPA_ETA_DATE'];
	$IPA_INVOICE_NO=$row['IPA_INVOICE_NO'];
	$IPA_PO_NO=$row['IPA_PO_NO'];
	$IPA_CONTAINER_NO=$row['IPA_CONTAINER_NO'];
	$CTD_CUST_NAME=$row['CTD_CUST_NAME'];
	$IAP_QTY=$IAP_QTY+$row['IAP_QTY'];
	$IPA_IN_DATE=$row['IPA_IN_DATE'];
	$prod_no=$row['PDD_PROD_NO'];
	$IAP_UNIT=$row['IAP_UNIT'];
	$PDD_CLASS=$row['PDD_CLASS'];
}
if($PDD_CLASS=='原料'){
	$_GET['t13']='A';
}
else{
	$_GET['t13']='B';	
}
?>
<?php 
$sg1=new sag;
$sg1->pono=$_GET['po_no'];
$sg1->num_sg();
if($sg1->finished_time<>'')
{
	$btnenable='disabled="disable"';
}
else {$btnenable="";}
?>
<link href="/css/boilerplate.css" rel="stylesheet" type="text/css">
<link href="/css/main.css" rel="stylesheet" type="text/css">
<link href="../css/fm.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.right {
	text-align: right;
}
.cent {
	text-align: center;
}
</style>

<style type="text/css">
body {
}
</style> 
<form id="form4" name="form4" method="post" action="<?php echo $loginFormAction; ?>">
  (<?php echo $_GET['t13'];?>) 採購單號：
  <input name="sch" type="text" id="sch" value="<?php echo $_GET['po_no']; ?>" size="12" maxlength="15" />
  <input type="submit" name="search" id="搜尋" value="搜尋" />
  <input type="submit" name="excel" id="excel" value="excel 輸出" />
  <input type="submit" name="leave" id="離開" value="離開" />
</form>
<hr>
<form name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <p>輸入：</p>
  <table width="1024" border="0">
    <tr>
      <td width="185" class="right">入荷預定日：</td>
      <td width="197"><?php echo $IPA_ETA_DATE; ?></td>
      <td width="127" class="right">報關號碼：</td>
      <td width="225">
      <input type="text" name="IPA_CUSTOMS_NO" id="IPA_CUSTOMS_NO" value="<?php echo $IPA_CUSTOMS_NO;?>" /></td>
      <td width="86" class="right">總量：</td>
      <td width="178"><?php echo $IAP_ORDER_QTY; ?></td>
    </tr>
    <tr>
      <td class="right">INVOICE NO：</td>
      <td><input type="text" name="IPA_INVOICE_NO" id="IPA_INVOICE_NO" value="<?php echo $IPA_INVOICE_NO;?>"></td>
      <td class="right">PO NO：</td>
      <td><?php echo $IPA_PO_NO;?></td>
      <td class="right">殘量：</td>
      <td><?php echo ($IAP_ORDER_QTY-$IAP_QTY) ;?></td>
    </tr>
    <tr>
      <td class="right">Container NO：</td>
      <td><input type="text" name="IPA_CONTAINER_NO" id="IPA_CONTAINER_NO" value="<?php echo $IPA_CONTAINER_NO;?>" /></td>
      <td class="right">入荷先：</td>
      <td><?php echo $CTD_CUST_NAME;?></td>
      <td class="right">驗收量：</td>
      <td><?php echo ($IAP_QTY) ;?></td>
    </tr>
    <tr>
      <td class="right">實際入荷日期：</td>
      <td><?php echo $IPA_IN_DATE;?></td>
      <td class="right">作成日期：</td>
      <td>&nbsp;</td>
      <td class="right"><input type="submit" name="save" id="save" value="存檔" <?php echo $btnenable;?> /></td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
<hr>
<?php 
$sg1=new sag;
$sg1->pono=$_GET['po_no'];
$sg1->num_sg();
if($sg1->finished_time<>'')
{
	$btnenable='disabled="disable"';
}
else {$btnenable="";}
?>
<form name="form2" method="post" action="<?php echo $loginFormAction; ?>">
  <p>產品明細：
</p>
  <table width="1280" border="0">
    <tr>
      <td><input type="submit" name="rcv" id="rcv" value="驗  收" <?php echo $btnenable;?>> 
      <input type="submit" name="lot" id="lot" value="TYS Lot 輸入" <?php echo $btnenable;?> /> 
      <input type="button" name="prt_barcode" id="prt_barcode" value=" 列印條碼 " disabled="disabled"> 
       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      </td><td>
        <input name="ContainerNo" type="text" id="ContainerNo" size="10" />
        <input type="submit" name="CON" id="CON"  value="ContainerNo 輸入" <?php echo $btnenable;?> /> &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
		<input name="plt" type="text" id="plt" size="10">
      	<input type="submit" name="subplt" id="subplt" value=" 儲存Plt " <?php echo $btnenable;?> /> &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
      	<input name="remark" type="text" id="remark" size="10">
      	<input type="submit" name="rem" id="rem" value=" 儲存備註 " <?php echo $btnenable;?> /> &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
   	  	<input type="button" name="button" id="button" value=" 畫面重整  " onclick="window.open('<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>', '_self');" /></td>
   		</tr>
  </table>

<table width="1240" border="1">
  <tr>
  	<td>NO</td>
 	<td>選擇</td>
    <td>料號</td>
    <td>出荷先</td>
    <td>品名</td>
    <td>包裝</td>
    <td>荷姿</td>
    <td>Lot No</td>
    <td>單位</td>
    <td>預交量</td>
    <td>數量</td>
    <td>PLT</td>
    <td>COA</td>
    <td>報關號碼</td>
    <td>INVOICE NO</td>
    <td>Container NO</td>
    <td>備註</td>
  </tr>

<?php
$query="SELECT  IN_PLAN.IPA_ETA_DATE, IN_PLAN.IPA_PO_NO, IN_PLAN.IPA_INVOICE_NO, CUSTOMER_DATA.CTD_CUST_NAME, 
                   IN_PLAN_PRODUCT.IAP_UNIT, PRODUCT_DATA.PDD_PACKAGE, IN_PLAN_PRODUCT.IAP_ORDER_QTY, 
                   IN_PLAN_PRODUCT.IAP_SERIAL_NO, IN_PLAN_PRODUCT.IAP_QTY, IN_PLAN.IPA_IN_DATE, IN_PLAN.IPA_TYPE, 
                   IN_PLAN_PRODUCT.IAP_STATE, IN_PLAN_PRODUCT.IAP_LOT_NO, IN_PLAN_PRODUCT.IAP_INWARD_DATE, 
                   CUSTOMER_DATA.CTD_SUPPLIER, IN_PLAN.IPA_CUSTOMS_NO, IN_PLAN_PRODUCT.PRA_SERIAL_NO, 
                   IN_PLAN_PRODUCT.IAP_PLT_NO, IN_PLAN.IPA_IN_DATE AS Expr1, IN_PLAN_PRODUCT.IAP_MAKER_LOT_NO, 
                   IN_PLAN.SAG_NO, IN_PLAN_PRODUCT.IAP_MEMO, PRODUCT_DATA.PDD_PROD_NO, PRODUCT_DATA.PDD_STYLE, 
                   PRODUCT_DATA.PDD_TYPE, PRODUCT_DATA.PDD_PROD_NAME, IN_PLAN_PRODUCT.IPA_PO_NO AS Expr2, 
                   CUSTOMER_PRODUCTS.CTP_REMNANT_MON, IN_PLAN_PRODUCT.PDD_PROD_NO AS Expr3, 
                   CUSTOMER_DATA.CTD_CUST_SHORT_NAME AS CNAME, CUSTOMER_DATA.CTD_CUST_NO AS CNO, 
                   PRODUCT_DATA.PDD_DRUM_KG, IN_PLAN_PRODUCT.IAP_LOT_MAKE_DATE 
FROM      CUSTOMER_DATA INNER JOIN
                   CUSTOMER_PRODUCTS ON 
                   CUSTOMER_DATA.CTD_CUST_NO = CUSTOMER_PRODUCTS.CTD_CUST_NO RIGHT OUTER JOIN
                   IN_PLAN_PRODUCT ON CUSTOMER_PRODUCTS.PDD_PROD_NO = IN_PLAN_PRODUCT.PDD_PROD_NO AND 
                   CUSTOMER_DATA.CTD_CUST_NO = IN_PLAN_PRODUCT.IAP_OUT_CUST_NO LEFT OUTER JOIN
                   PRODUCT_DATA ON IN_PLAN_PRODUCT.PDD_PROD_NO = PRODUCT_DATA.PDD_PROD_NO FULL OUTER JOIN
                   IN_PLAN INNER JOIN
                   CUSTOMER_DATA AS CUSTOMER_DATA_1 ON IN_PLAN.CTD_CUST_NO = CUSTOMER_DATA_1.CTD_CUST_NO ON 
                   IN_PLAN_PRODUCT.IPA_PO_NO = IN_PLAN.IPA_PO_NO 				   
WHERE          (IN_PLAN.IPA_PO_NO = '".$_GET['po_no']."')";

//   $_SESSION['record']= $query.'<br>';

$result = mssql_query($query);
$i=0;
while($row = mssql_fetch_array($result))
{
	$sagno=$row['SAG_NO'];
	echo '<tr>';
	echo '<td>'.$row['IAP_SERIAL_NO'].'</td>';
	echo '<td><label>
    <input type="radio" name="selectl" value="'.$row['IAP_SERIAL_NO'].'"  />
    </label></td>';
	$aa[$i][1]=$row['PDD_PROD_NO'];
	echo '<td>'.$row['PDD_PROD_NO'].'</td>';
	
	$aa[$i][13]=$row['CNAME'];
	$aa[$i][14]=$row['CNO'];
	$aa[$i][15]=$row['IPA_IN_DATE'];
	$aa[$i][16]=$row['PDD_DRUM_KG'];
	$mm=$row['CTP_REMNANT_MON'];
	$term=$aa[$i][17]=$row['IAP_LOT_MAKE_DATE'];
	$term_date=substr($term,0,4)."-".substr($term,4,2)."-".substr($term,6,2)." 00:00:00";
	$str="+".$mm." month";
	$term_date=date("Y-m-d H:i:s",strtotime($term_date.$str));
	$aa[$i][18]=$term_date;
	
	echo '<td>'.$row['CNAME'].'</td>';
	
	$aa[$i][2]=get_prod_name($row['PDD_PROD_NO']);
	echo '<td>'.get_prod_name($row['PDD_PROD_NO']).'</td>';
	
	$aa[$i][3]=$row['PDD_STYLE'];
	echo '<td>'.$row['PDD_STYLE'].'</td>';
	
	$aa[$i][4]=$row['PDD_PACKAGE'];
	echo '<td>'.$row['PDD_PACKAGE'].'</td>';
	
	$aa[$i][5]=$row['IAP_LOT_NO'];
	echo '<td>'.$row['IAP_LOT_NO'].'</td>';
	
	$aa[$i][6]=$row['IAP_UNIT'];
	echo '<td>'.$row['IAP_UNIT'].'</td>';
			
	$aa[$i][7]=$row['IAP_ORDER_QTY'];
	echo '<td>'.$row['IAP_ORDER_QTY'].'</td>';
	
	$aa[$i][8]=$row['IAP_QTY'];
	echo '<td>'.$row['IAP_QTY'].'</td>';
	
	$aa[$i][9]=$row['IAP_PLT_NO'];
	echo '<td>'.$row['IAP_PLT_NO'].'</td>';
	
	$aa[$i][10]=$row['IAP_MAKER_LOT_NO'];
	echo '<td>'.$row['IAP_MAKER_LOT_NO'].'</td>';
	
	echo '<td>'.$row['IPA_CUSTOMS_NO'].'</td>';
	
	echo '<td>'.$row['IPA_INVOICE_NO'].'</td>';
	
	$aa[$i][12]=$row['PRA_SERIAL_NO'];
	echo '<td>'.$row['PRA_SERIAL_NO'].'</td>';
	
	$aa[$i][11]=$row['IAP_MEMO'];
	echo '<td>'.$row['IAP_MEMO'].'</td>';
	echo '</tr>';
	$i=$i+1;
}
echo "共 ".$i."筆<BR>";
$_SESSION['ab']=$aa;
?>
</table>
</form>
<hr>
<?php echo $sagno;?>
簽核一覽：
<form name="form3" method="post" action="<?php echo $loginFormAction; ?>">
<?php
$sg=new sag;
$sg->sagno=$sagno;
$sg->sign_in();
$sg->num_sg();
if(($sg->sai_num>=3) and ($sg->finished_time<>'')){$vis='disabled="disable"';}
?>
  <div id="fm3">
    <div id="LayoutDiv1">
      <div id="left">
	  <?php
	  if($sg->sai_num==0){echo "事務採購";} 
	  elseif($sg->sai_num==1){echo "品保";} 
	  elseif($sg->sai_num==2){echo "物流倉庫";} 
	  elseif($sg->sai_num==3){echo "最終確認";}
	   ?>簽核輸入
        <input type="checkbox" name="ensure" id="ensure" value="OK">
        <label for="ensure">確認</label>
        <input type="submit" name="sign" id="sign" value="簽核" <?php echo $vis; ?> />
        <label for="comment"><br>
        意見<br>
        </label>
        <textarea name="comment" id="comment" cols="20" rows="4"></textarea>
      </div>
      <div id="dep">
        <div id="sig_dep">簽核單位</div>
        <div id="sig_ok">簽核結果</div>
        <div id="sig_time">確認時間</div>
        <div id="sig_comn">意見</div>

      </div>
      <?php 
		$creattime=new timech;
		$creattime->str_sec=$sg->cdate;
		$creattime->sectoymdhs1();
	  ?>
      <div id="sig1">
      	<div id="sig_dep">製表人員-<?php echo $s11=$sg->uname;$s12=$s14="N/A";?></div>
        <div id="sig_ok">N/A</div>
        <div id="sig_time"><?php echo $s13=$creattime->str_YMD_Hs;?>
		</div>
        <div id="sig_comn">N/A</div>
      </div>
      
      <?php
	  $sg->sai_sn=1;
	  $sg->read_sig();
	  ?>
      <div id="sig1">
      	<div id="sig_dep">事務採購-<?php echo $s21=$sg->uname;?></div>
        <div id="sig_ok"><?php echo $s22=$sg->ok;?></div>
        <div id="sig_time">
		<?php 
		$creattime->str_sec=$sg->sai_time;
		$creattime->sectoymdhs1();
		echo $s23=$creattime->str_YMD_Hs;
		?>
        </div>
        <div id="sig_comn"><?php echo $s24=$sg->sai_memo;?></div>
      </div>
      
      <?php
	  $sg->sai_sn=2;
	  $sg->read_sig();
	  if($_GET['t13']=='A'){
	  	echo '
      	<div id="sig1">
      	<div id="sig_dep">製造課長-'.$s31=$sg->uname.'</div>
        <div id="sig_ok">'.$s32=$sg->ok.'</div>
        <div id="sig_time">';
	  }
	  if($_GET['t13']=='B'){
		$s31=$sg->uname;$s32=$sg->ok;
	  	echo '
      	<div id="sig1">
      	<div id="sig_dep">品保-'.$s31.'</div>
        <div id="sig_ok">'.$s32.'</div>
        <div id="sig_time">';
	  }

		$creattime->str_sec=$sg->sai_time;
		$creattime->sectoymdhs1();
		echo $s33=$creattime->str_YMD_Hs;
		?>
        </div>
        <div id="sig_comn"><?php echo $s34=$sg->sai_memo;?></div>
      </div>
      
      <?php
	  $sg->sai_sn=3;
	  $sg->read_sig();
	  ?>
      <div id="sig1">
      	<div id="sig_dep">物流倉庫-<?php echo $s41=$sg->uname;?></div>
        <div id="sig_ok"><?php echo $s42=$sg->ok;?></div>
        <div id="sig_time">
		<?php 
		$creattime->str_sec=$sg->sai_time;
		$creattime->sectoymdhs1();
		echo $s43=$creattime->str_YMD_Hs;
		?>
        </div>
        <div id="sig_comn"><?php echo $s44=$sg->sai_memo;?></div>
      </div>
       <?php
	  $sg->pono=$_GET['po_no'];
	  $sg->read_sig();
	  ?>
      <div id="sig1">
      	<div id="sig_dep">最後確認-<?php echo $s51=$sg->uname;?></div>
        <div id="sig_ok"><?php if($sg->finished_time<>''){echo $s52="已確認";}?></div>
        <div id="sig_time"><?php echo $s53=$sg->finished_time;?></div>
        <div id="sig_comn"><?php echo $s54=$sg->finished_memo;?></div>
      </div>
  	</div>
  </div>

</form>


<?php 
$loginFormAction = $_SERVER['PHP_SELF'];
$url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$path_root=$_SERVER['HTTP_HOST'];
if(isset($_POST["excel"])) ///產生EXCEL
{
	if($_GET['t13']=='A'){$a31="製造課長-".$s31;}
	if($_GET['t13']=='B'){$a31="品保-".$s31;}		
	$a11="製表人員-".$s11;
	$a21="事務採購-".$s21;
	$a41="物流倉庫-".$s41;
	$a51="最後確認-".$s51;
	
	$objPHPExcel = new PHPExcel();
	if($_GET['t13']=='A'){
		$objPHPExcel = PHPExcel_IOFactory::load("./in_plan.xlsx");	
	}
	elseif($_GET['t13']=='B'){
		$objPHPExcel = PHPExcel_IOFactory::load("./in_plan_B.xlsx");	
	}
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setCellValue("E5",ddd($IPA_ETA_DATE));
	$objPHPExcel->getActiveSheet()->setCellValue("E6",$IPA_INVOICE_NO);
	$objPHPExcel->getActiveSheet()->setCellValue("E7",$IPA_CONTAINER_NO);
	$objPHPExcel->getActiveSheet()->setCellValue("E8","");
	$objPHPExcel->getActiveSheet()->setCellValue("M3",$IPA_PO_NO);
	$objPHPExcel->getActiveSheet()->setCellValue("M5",$IPA_CUSTOMS_NO);
	$objPHPExcel->getActiveSheet()->setCellValue("M6",$IPA_PO_NO);
	$objPHPExcel->getActiveSheet()->setCellValue("M7",conv($CTD_CUST_NAME));
	$objPHPExcel->getActiveSheet()->setCellValue("M8","N/A");
	
	$objPHPExcel->getActiveSheet()->setCellValue("D25",conv($a11));
	$objPHPExcel->getActiveSheet()->setCellValue("D26",conv($s12));
	$objPHPExcel->getActiveSheet()->setCellValue("D27",conv($s13));
	$objPHPExcel->getActiveSheet()->setCellValue("D28",conv($s14));
	
	$objPHPExcel->getActiveSheet()->setCellValue("F25",conv($a21));
	$objPHPExcel->getActiveSheet()->setCellValue("F26",conv($s22));
	$objPHPExcel->getActiveSheet()->setCellValue("F27",conv($s23));
	$objPHPExcel->getActiveSheet()->setCellValue("F28",conv($s24));
	
	$objPHPExcel->getActiveSheet()->setCellValue("H25",conv($a31));
	$objPHPExcel->getActiveSheet()->setCellValue("H26",conv($s32));
	$objPHPExcel->getActiveSheet()->setCellValue("H27",conv($s33));
	$objPHPExcel->getActiveSheet()->setCellValue("H28",conv($s34));
	
	$objPHPExcel->getActiveSheet()->setCellValue("K25",conv($a41));
	$objPHPExcel->getActiveSheet()->setCellValue("K26",conv($s42));
	$objPHPExcel->getActiveSheet()->setCellValue("K27",conv($s43));
	$objPHPExcel->getActiveSheet()->setCellValue("K28",conv($s44));
	
	$objPHPExcel->getActiveSheet()->setCellValue("M25",conv($a51));
	$objPHPExcel->getActiveSheet()->setCellValue("M26",conv($s52));
	$objPHPExcel->getActiveSheet()->setCellValue("M27",conv($s53));
	$objPHPExcel->getActiveSheet()->setCellValue("M28",conv($s54));
	for($j=0;$j<$i;$j++)
	{
		$objPHPExcel->getActiveSheet()->setCellValue("C".(10+$j),$aa[$j][1]);
		$objPHPExcel->getActiveSheet()->setCellValue("D".(10+$j),conv(get_prod_name($aa[$j][1])));
		$objPHPExcel->getActiveSheet()->setCellValue("E".(10+$j),$aa[$j][3]);
		$objPHPExcel->getActiveSheet()->setCellValue("F".(10+$j),conv($aa[$j][4]));
		$objPHPExcel->getActiveSheet()->setCellValue("G".(10+$j),$aa[$j][5]);
		$objPHPExcel->getActiveSheet()->setCellValue("H".(10+$j),$aa[$j][6]);
		$objPHPExcel->getActiveSheet()->setCellValue("I".(10+$j),$aa[$j][7]);
		$objPHPExcel->getActiveSheet()->setCellValue("J".(10+$j),$aa[$j][8]);
		$objPHPExcel->getActiveSheet()->setCellValue("K".(10+$j),$aa[$j][9]);
		$objPHPExcel->getActiveSheet()->setCellValue("L".(10+$j),$aa[$j][10]);
		$objPHPExcel->getActiveSheet()->setCellValue("M".(10+$j),$aa[$j][11]);
		$objPHPExcel->getActiveSheet()->setCellValue("N".(10+$j),$aa[$j][12]);
	}
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
	$objWriter->save('tmp.xlsx');
	echo '<script>document.location.href="http://'.$path_root.'/inplan/tmp.xlsx";</script>';	
}

if(isset($_POST["rcv"]))
{
	unset($_SESSION['aa']);
	$rcv="index.php?url=rcv&po_no=".$_GET['po_no']."&total=". $IAP_ORDER_QTY."&pid=". $prod_no.'&unit='.$IAP_UNIT.'&inward='.$IPA_ETA_DATE.'&inward='.$IPA_ETA_DATE;
	jumptonew($rcv);
}

if(isset($_POST["lot"]))
{
//	my_msg("OOOO");
	$input_lot="index.php?url=input_lot&po_no=".$_GET['po_no']."&total=". $IAP_ORDER_QTY."&pid=". $prod_no.'&unit='.$IAP_UNIT.'&inward='.$IPA_ETA_DATE;
	jumpto($input_lot);
}
	
if(isset($_POST["save"]))
	{
		fun_confirm("確定更改");
		if (fun_confirm){
		$query="UPDATE          dbo.IN_PLAN
				SET                   IPA_INVOICE_NO = '".$_POST['IPA_INVOICE_NO']."', IPA_CUSTOMS_NO = '".$_POST['IPA_CUSTOMS_NO']."', IPA_CONTAINER_NO 
				= '".$_POST['IPA_CONTAINER_NO']."'
				WHERE          (IPA_PO_NO = '".$_GET['po_no']."')";
		$result = mssql_query($query);
		if (!$result) {
  		  		fun_alert("輸入失敗");
  			}
		header("Location:".$url);
		}
		$usi='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?url=list_po&po_no='.$_GET['po_no'].'&t13='.$_GET['t13'];
		echo '<script>document.location.href="'.$usi.'";</script>';		
	}

if(isset($_POST["leave"]))
	{
		jumpto("./index.php?url=inplanlist");
	}
if(isset($_POST["subplt"]))
	{
		$query="UPDATE          IN_PLAN_PRODUCT
				SET             IAP_PLT_NO ='".$_POST['plt']."'
				WHERE          (IPA_PO_NO = '".$_GET['po_no']."') AND (IAP_SERIAL_NO='".$_POST['selectl']."')";		
		fun_confirm("確定更改");
		echo $query;
		if (fun_confirm){
		$result = mssql_query($query);
		if (!$result) {
  		  		fun_alert("輸入失敗");
  			}
		}

/*		
		$usi='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?url=list_po&po_no='.$_GET['po_no'];
		echo '<script>document.location.href="'.$usi.'";</script>';	
*/		
refresh();
	}

if(isset($_POST["rem"]))
	{
		$query="UPDATE          IN_PLAN_PRODUCT
				SET             IAP_MEMO ='".$_POST['remark']."'
				WHERE          (IPA_PO_NO = '".$_GET['po_no']."') AND (IAP_SERIAL_NO='".$_POST['selectl']."')";		
		$result = mssql_query($query);
		if (!$result) {
  		  		fun_alert("輸入失敗");
  			}
/*
		$usi='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?url=list_po&po_no='.$_GET['po_no'];;
		echo '<script>document.location.href="'.$usi.'";</script>';
*/
refresh();
	}

if(isset($_POST["CON"]))
	{
		$query="UPDATE          IN_PLAN_PRODUCT
				SET             PRA_SERIAL_NO ='".$_POST['ContainerNo']."'
				WHERE          (IPA_PO_NO = '".$_GET['po_no']."') AND (IAP_SERIAL_NO='".$_POST['selectl']."')";		
		$result = mssql_query($query);
		$_SESSION['CONN']= $query;
		if (!$result) {
  		  		fun_alert("輸入失敗");
  			}
/*
		$usi='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?url=list_po&po_no='.$_GET['po_no'];;
		echo '<script>document.location.href="'.$usi.'";</script>';
*/
refresh();
	}


if(isset($_POST["sign"]))
	{
		$sg->num_sg();
		if($_POST['ensure']){$sure="OK";}
		if($sure=="OK"){
		if($sg->sai_num<3){
			$query="UPDATE          dbo.SIGN_AGREE_ITEM
					SET                   EMP_NO ='".$_SESSION['uid']."', SAI_OK_NG ='".$sure."', SAI_TIME ='".date("YmdHis")."', SAI_MEMO ='".$_POST['comment']."'
					WHERE          (SAI_SERIAL_NO = '".($sg1->sai_num+1)."') AND (SAG_NO = '".$sg->sagno."')";
			$result = mssql_query($query);
			if (!$result) {
					fun_alert("輸入失敗");
				}
		}
		elseif($sg->sai_num==3)
		{
			$query="UPDATE          dbo.SIGN_AGREE
					SET                   SAG_FINISHED_MEMO ='".$_POST['comment']."', SAG_FINISHED_TIME ='".date("YmdHis")."', SAG_TERMINATE_TIME ='".date("YmdHis")."'
					WHERE          (SAG_NO = ".$sg->sagno.")";

			$result = mssql_query($query);
			if (!$result) {
					fun_alert("輸入失敗");
				}
			
			}
			
			$query="UPDATE IN_PLAN SET IPA_IN_DATE='".$IPA_IN_DATE."' WHERE IPA_PO_NO='".$IPA_PO_NO."'";
			$result = mssql_query($query);		
			$query="UPDATE IN_PLAN_PRODUCT SET IAP_STATE='1' WHERE IPA_PO_NO='".$IPA_PO_NO."' and PDD_PROD_NO='"."' and IAP_STATE='P'";
			$result = mssql_query($query);	
			for($x=0;$x<$i;$x++){
				if($aa[$x][16]<>''){$dum=$aa[$x][8]/$aa[$x][16];}
				$query="INSERT INTO PRODUCT_STOCKS (STK_LOT_NO,PDD_PROD_NO,CTD_CUST_NO,STK_MAKE_DATE,STK_QTY,STK_DRUM_COUNT) VALUES ('".$aa[$x][5]."','".$aa[$x][1]."','".$aa[$x][14]."','".$aa[$x][16]."',".$aa[$x][8].",".$dum.")";
				$result = mssql_query($query);	
				
				$query="UPDATE  PRODUCT_RUNNING_ACCOUNT SET PRA_FAKE_IN_DATE2='".date("Ymd")."',PRA_ANALYZED='OK',PRA_REAL_IN_DATE='".date("Ymd")."',PRA_IN_DM=".$dum.",PRA_REAL_IN_QTY=".$aa[$x][8].",PRA_OUT_TERM='".des($aa[$x][18])."' WHERE PRA_LOT_NO='".$aa[$x][5]."' AND PRA_SERIAL_NO=".($x+1);
				$result = mssql_query($query);	
				
				
			}
			
		}
		$usi='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?url=list_po&po_no='.$_GET['po_no'].'&t13='.$_GET['t13'];
		echo '<script>document.location.href="'.$usi.'";</script>';
		
	}

if(isset($_POST["search"]))
	{
		$_GET['po_no']=$_POST['sch'];
		$usi='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?url=list_po&po_no='.$_POST['sch'];;
		echo '<script>document.location.href="'.$usi.'";</script>';
	}
?>