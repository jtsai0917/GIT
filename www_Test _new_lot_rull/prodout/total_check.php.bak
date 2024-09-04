<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
	session_start();
	include("../connections/conn.php");
	include("../lib/fun.php");
	include("../lib/jtsai.php");
	auth('2-11',$_SESSION['aut']);
	datepick();
	$aa=array();
	$aa=explode(",",$_SESSION['lot_no_total']);
	$lot_no=trim($aa[0]);
	$sn=trim($aa[1]);
	$query="SELECT          OUT_PRODUCT.OPD_LOT_NO, OUT_PRODUCT.OPD_SERIAL_NO AS sn1, LORRY_EXAMINE_LIST.LEL_LOT_NO, 
                            LORRY_EXAMINE_LIST.PRA_OUT_COUNT AS sn2, LORRY_EXAMINE_LIST.PDD_PROD_NO, 
                            LORRY_EXAMINE_LIST.LEL_LY_NO, LORRY_EXAMINE_LIST.OTD_NO, 
                            OUT_PRODUCT.PDD_PROD_NO AS prodno
FROM              LORRY_EXAMINE_LIST RIGHT OUTER JOIN
                            OUT_PRODUCT ON LORRY_EXAMINE_LIST.PRA_OUT_COUNT = OUT_PRODUCT.OPD_SERIAL_NO AND 
                            LORRY_EXAMINE_LIST.LEL_LOT_NO = OUT_PRODUCT.OPD_LOT_NO 
WHERE          (OUT_PRODUCT.OPD_LOT_NO = '".$lot_no."' and OUT_PRODUCT.OPD_SERIAL_NO='".$sn."')";

	$result = mssql_query($query);
	$numrow=mssql_num_rows($result);
//	echo $query."<BR>";
	if($numrow >0){
		while($row=mssql_fetch_array($result)){
			$lyno=substr(trim($lot_no),0,2).substr(trim($lot_no),-4,4);
			$_SESSION['left_qty']=$row['LEL_REMNANT_QTY'];
			$_SESSION['pre_out_qty']=$row['LEL_FAKE_QTY'];
			$_SESSION['tys_qty']=$row['LEL_TYS_QTY'];
			$_SESSION['tys_left_qty']=$row['LEL_TYS_REMNANT_QTY'];
		}		
	}
	else{
		$qq="INSERT INTO LORRY_EXAMINE_LIST (LEL_LOT_NO, PRA_OUT_COUNT, PDD_PROD_NO, LEL_LY_NO)
						VALUES          ('".$lot_no."', ".$sn.", '".get_prod_no($lot_no)."', '".$lyno."')";
	//		$_SESSION['dfd']= $qq."<BR>";
				$rr=mssql_query($qq);
	}
	
	
	$query="SELECT [FDM_REAL_OUT_TIME], [FDM_REAL_RETURN_TIME] FROM [FILLPLAN_OUT_DECIDE] WHERE [FDM_LOT_NO] = '".$lot_no."'";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	if($numRows>0)
	{
		while($row=mssql_fetch_array($result))
		{
			$_SESSION['datepicker3']=ttd($row['FDM_REAL_OUT_TIME']);

			$_SESSION['hs_1']=th($row['FDM_REAL_OUT_TIME']).tm($row['FDM_REAL_OUT_TIME']);
			$_SESSION['datepicker4']=ttd($row['FDM_REAL_RETURN_TIME']);

			$_SESSION['hs_2']=th($row['FDM_REAL_RETURN_TIME']).tm($row['FDM_REAL_RETURN_TIME']);
		}		
	}

?>
<script type="text/javascript" src="/css/jquery.min.js"></script>
    <script type="text/javascript" src="/css/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/css3menu/GridViewScroll/gridviewScroll.min.js"></script>
    <link href="/css3menu/GridViewScroll/GridviewScroll.css" rel="stylesheet" />
        <script type="text/javascript">
	    $(document).ready(function () {
	        gridviewScroll();
	    });
	
	    function gridviewScroll() {
	        gridView1 = $('#GridView1').gridviewScroll({
                width: 1100,
                height: 550,
                railcolor: "#F0F0F0",
                barcolor: "#CDCDCD",
                barhovercolor: "#606060",
                bgcolor: "#F0F0F0",
                freezesize: 3,
                arrowsize: 30,
                varrowtopimg: "/css3menu/GridViewScroll/Images/arrowvt.png",
                varrowbottomimg: "/css3menu/GridViewScroll/Images/arrowvb.png",
                harrowleftimg: "/css3menu/GridViewScroll/Images/arrowhl.png",
                harrowrightimg: "/css3menu/GridViewScroll/Images/arrowhr.png",
                headerrowcount: 1,
                railsize: 16,
                barsize: 8
            });
	    }
	</script>
<font size="+2" color="#990000">悠技 KEY IN TYS磅單用 </font>
<form name="form1" method="post" action="<?php echo $loginFormAction; ?>">
<table width="1024" border="1"><tr bgcolor="#999999"><td width="724">
年月
  <label for="ym"></label>
  <input name="ym" type="text" id="ym" size="8" maxlength="6" value="<?php echo $_SESSION['ym'];?>" onchange="set_date_session(this.name,this.value)" > 
  ( EX: 201612 )
  <span class="d1">出荷預定日:
  <input name="datepicker1" type="text" id="datepicker1" size="10" onchange="set_date_session(this.name,this.value)" value="<?php echo $_SESSION['datepicker1'];?>">
~
<input name="datepicker2" type="text" id="datepicker2" size="10" onchange="set_date_session(this.name,this.value)" value="<?php  echo $_SESSION['datepicker2'];?>">
  </span> LORRY NO: 
  <label for="lorry_no"></label>
  <input name="lorry_no" type="text" id="lorry_no" size="10" maxlength="6"  value="<?php echo $_SESSION['lorry_no'];?>" onchange="set_date_session(this.name,this.value)" > 
  ( 6 碼 )
  </td>
  <td align="left">
    <input type="submit" name="excel" id="excel" value="  Excel  ">    <input type="submit" name="submit" id="submit" value="  查詢  ">    <input type="submit" name="can" id="can" value="  清除  ">
    <input type="button" id="can" value="Excel上傳 " onclick="window.open('/prodout/index.php?url=total_check_insert');">
  </td> 
    </table>
    <table width="1024" border="1" bgcolor="#CCCCCC"> 
    	<tr><td>輸入</td></tr>
    </table>
    
    <table width="1024" border="1" bgcolor="#CCCCCC"> 
    <tr>
      <td width="312" align="right">
    假出庫數量：
      <label for="pre_out_qty"></label>
      <input name="pre_out_qty" type="text" id="pre_out_qty" size="10" value="<?php if($_SESSION['pre_out_qty']){echo $_SESSION['pre_out_qty'];}?>" />
      </td>
      <td width="200">
      </td>
      <td width="312" align="right">
      </td>
      <td width="200">
      </td>
      </tr>
    <tr>
      <td width="312" align="right">
      殘液量：
      <label for="left_qty"></label>
      <input name="left_qty" type="text" id="left_qty" size="10" value="<?php if($_SESSION['left_qty']){echo $_SESSION['left_qty'];}?>"/>
      </td>
      <td width="200">
     </td>
      <td align="right">充填 滿單；
      </td>
      <td width="200"><?php echo $_SESSION['tys_qty']  ;?>
      </td>
      </tr>
    <tr>
      <td width="312" align="right">
     出荷時間：
      <input name="datepicker3" type="text" id="datepicker3" size="10"  value="<?php if($_SESSION['datepicker3']){echo $_SESSION['datepicker3'];}?>">
      <input name="hs_1" type="text" id="textfield" size="5" value="<?php if($_SESSION['hs_1']<>''){echo $_SESSION['hs_1'];}?>" />
      (時分)
      </td>
      <td width="200">
      </td><td align="right">充填 殘單：</td><td><?php echo $_SESSION['tys_left_qty']  ;?></td>
      </tr>
    <tr>
      <td width="312" align="right">
      回廠時間：
      <input name="datepicker4" type="text" id="datepicker4" size="10" value="<?php if($_SESSION['datepicker4']){echo $_SESSION['datepicker4'];}?>">
      <input name="hs_2" type="text" id="textfield"  size="5" value="<?php if($_SESSION['hs_2']<>''){echo $_SESSION['hs_2'];}?>"  />
      (時分)
      </td>
      <td width="200">      
      <input type="submit" name="save_1" id="save_1" value=" 儲 存 " />
      </td><td></td><td></td></tr>
    </table>
 </form>
<?php
$editFormAction = $_SERVER['PHP_SELF'];
if(isset($_POST['can'])){$_SESSION['ii']=0;}
if(isset($_POST['submit']) or $_SESSION['ii']==1){
$_SESSION['ii']=1;
$query="SELECT DISTINCT 
                            LEFT(OUT_DECISION.OPM_ETA_DATE, 8) AS 出荷日期, OUT_PRODUCT.OPM_ORDER_NO AS SALESID, 
                            OUT_PRODUCT.OTD_NO, OUT_PRODUCT.COPCONFIRMSERIES AS series, 
                            OUT_PRODUCT.PDD_PROD_NO AS PRA_PROD_NO, OUT_PRODUCT.OPD_LOT_NO AS [Lot No], 
                            LORRY_EXAMINE_LIST.LEL_CUST_UNIT AS custuni, 
                            CUSTOMER_PRODUCTS.CTP_UNIT, CUSTOMER_DATA.CTD_CUST_NO AS CNO, 
                            CUSTOMER_DATA.CTD_CUST_NAME AS 客戶名稱, PRODUCT_DATA.PDD_TYPE, 
                            OUT_PRODUCT.COPCONFIRMSERIES, OUT_DECISION.OPM_TAINAN_CACHE AS TN, 
                            OUT_PRODUCT.OPD_SERIAL_NO AS sn, OUT_PRODUCT.OTNP_SERIAL_NO AS ns, PRODUCT_DATA.PDD_PROD_SHORT_NAME
FROM              CUSTOMER_DATA INNER JOIN
                            OUT_DECISION INNER JOIN
                            OUT_PRODUCT ON OUT_DECISION.OPM_ORDER_NO = OUT_PRODUCT.OPM_ORDER_NO ON 
                            CUSTOMER_DATA.CTD_CUST_NO = OUT_DECISION.CTD_CUST_NO INNER JOIN
                            PRODUCT_DATA ON OUT_PRODUCT.PDD_PROD_NO = PRODUCT_DATA.PDD_PROD_NO LEFT OUTER JOIN
                            CUSTOMER_PRODUCTS INNER JOIN
                            LORRY_EXAMINE_LIST ON CUSTOMER_PRODUCTS.PDD_PROD_NO = LORRY_EXAMINE_LIST.PDD_PROD_NO ON 
                            OUT_DECISION.CTD_CUST_NO = CUSTOMER_PRODUCTS.CTD_CUST_NO AND 
                            OUT_PRODUCT.OPD_LOT_NO = LORRY_EXAMINE_LIST.LEL_LOT_NO       
WHERE   (PRODUCT_DATA.PDD_TYPE<> 'DM' ) ";
// echo $query."<BR>";
if($_SESSION['ym']<>''){$query.=" AND  Left(OPM_ETA_DATE,6) = '".$_SESSION['ym']."' ";}
else{
$query.="and Left(OPM_ETA_DATE,8) >= '".dod($_SESSION['datepicker1'])."'";
$query.=" AND  Left(OPM_ETA_DATE,8) <= '".dod($_SESSION['datepicker2'])."'";
}
if($_SESSION['lorry_no']<>''){$query.="and (PRODUCT_DATA.PDD_PROD_SHORT_NAME + SUBSTRING(OUT_PRODUCT.OPD_LOT_NO, 8, 4) = '".$_SESSION['lorry_no']."') ";}

$query.=" ORDER BY   PRA_PROD_NO ";	

// echo $query."<BR>";
echo '<table width="1024" border="1" id="GridView1">
<tr bgcolor="#CCCCCC" border="1" class="GridviewScrollHeader"><td>出荷日期</td><td>訂單</td><td>決定書</td><td>LORRY NO</td><td>品名</td><td>Lot No</td><td>出貨次序</td><td>假出庫數量</td><td>殘量</td><td>實際出庫量</td><td>客戶名稱</td></tr>';
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result))
	{
		$s1=check_OTNP_SERIAL_NO(trim($row['Lot No']));
		$query1="select * from PRODUCT_DATA where PDD_PROD_NO='".trim($row['PRA_PROD_NO'])."'";
//		echo $query1."<BR>";
		$result1 = mssql_query($query1);
		$row1 = mssql_fetch_array($result1);
		if(trim($row1['PDD_TYPE'])=='BTL' or trim($row1['PDD_TYPE'])=='DM'){continue;}
		$nn=new last_LEL;
		if($row['ns']<>''){
			$row['sn']=$row['ns'];
		}
		$nn->sn=$row['sn'];
		$nn->lotno=trim($row['Lot No']);
		$nn->run();
		$row['out_count']=$nn->out_count;
		$row['假出庫數量']=$nn->fake_out;
//		echo "SSSSS:".$nn->rem_qty;
//		echo "<BR>";
		$row['殘量']=$nn->rem_qty;
		$row['實際出庫數量']=$nn->real_out;
		if($_SESSION['lot_no_total']==$row['Lot No'].",".$row['sn'])
		{
			$bgcolor=  ' style="background-color:#FFFF00" ';
			$_SESSION['prod_no']=trim($row['PRA_PROD_NO']);
			$_SESSION['ly_no']=trim($row['LORRY NO']);
		}else{
			$bgcolor='';
		}
		echo '<tr class="GridviewScrollItem"  style="font-size:15px">';
		//echo '<tr class="GridviewScrollItem" onClick="set_date_session('."'lot_no_total'".','."'".$row['Lot No'].",".$row['out_count']."'".')" '.$bgcolor.'>';
//		echo '<td><input type="checkbox" name="chkbox[]" value="'.$j.'" '.$_SESSION['select_all'].'/></td>';
		$lorryno=substr($row['Lot No'],0,2).substr($row['Lot No'],-4,4);
		echo '<td onClick="set_date_session('."'lot_no_total'".','."'".$row['Lot No'].",".$row['out_count']."'".')" '.$bgcolor.'>'.$row['出荷日期'].'</td>
		<td onClick="set_date_session('."'lot_no_total'".','."'".$row['Lot No'].",".$row['sn']."'".')" '.$bgcolor.'>'.$row['SALESID'].'</td>
		<td onClick="set_date_session('."'lot_no_total'".','."'".$row['Lot No'].",".$row['sn']."'".')" '.$bgcolor.'>'.$row['OTDNO'].'</td>
		<td onClick="set_date_session('."'lot_no_total'".','."'".$row['Lot No'].",".$row['sn']."'".')" '.$bgcolor.'>'.$lorryno.'</td>
		<td onClick="set_date_session('."'lot_no_total'".','."'".$row['Lot No'].",".$row['sn']."'".')" '.$bgcolor.'>'.get_prod_name($row['PRA_PROD_NO']).'</td>
		<td onClick="set_date_session('."'lot_no_total'".','."'".$row['Lot No'].",".$row['sn']."'".')" '.$bgcolor.'>'.$row['Lot No'].'</td>
		<td onClick="set_date_session('."'lot_no_total'".','."'".$row['Lot No'].",".$row['sn']."'".')" '.$bgcolor.'>'.$row['sn'].'</td>
		<td onClick="set_date_session('."'lot_no_total'".','."'".$row['Lot No'].",".$row['sn']."'".')" '.$bgcolor.'>'.$row['假出庫數量'].'</td>
		<td onClick="set_date_session('."'lot_no_total'".','."'".$row['Lot No'].",".$row['sn']."'".')" '.$bgcolor.'>'.$row['殘量'].'</td>
		<td onClick="set_date_session('."'lot_no_total'".','."'".$row['Lot No'].",".$row['sn']."'".')" '.$bgcolor.'>'.$row['實際出庫數量'].'</td>
		<td onClick="set_date_session('."'lot_no_total'".','."'".$row['Lot No'].",".$row['sn']."'".')" '.$bgcolor.'>'.$row['客戶名稱'].'</td>';
		echo '</tr>';
	}
}

if(isset($_POST["excel"]))
{ 
	$path_root=$_SERVER['HTTP_HOST'];
	$i=2;
	$dat=date("YmdHis");
	include("../PHPEXCEL/Classes/PHPExcel.php");
	require_once "../PHPEXCEL/Classes/PHPExcel.php";
	require_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
	$objPHPExcel = new PHPExcel();	
	$objPHPExcel = PHPExcel_IOFactory::load("./formlist/total_check.xlsx");	
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
//	echo "<BR>";
//	echo $query;
//	echo "<BR>";
	while($row = mssql_fetch_array($result))
	{
		$nn=new last_LEL;
		$nn->lotno=trim($row['Lot No']);
		if($row['ns']<>''){
			$row['sn']=$row['ns'];
		}
		$nn->sn=$row['sn'];
		$nn->run();
		$row['out_count']=$nn->out_count;
		$row['假出庫數量']=$nn->fake_out;
		$row['殘量']=$nn->rem_qty;
		$row['實際出庫數量']=$nn->real_out;
		$lorryno=substr($row['Lot No'],0,2).substr($row['Lot No'],-4,4);
			$HH=std($row['出荷日期']);
			$objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('a'.$i,$HH)
							->setCellValue('b'.$i,iconv("big5","utf-8",$lorryno))
							->setCellValue('c'.$i,iconv("big5","utf-8",get_prod_name($row['PRA_PROD_NO'])))
							->setCellValue('d'.$i,iconv("big5","utf-8",$row['Lot No']))
							->setCellValue('e'.$i,iconv("big5","utf-8",$row['假出庫數量']))
							->setCellValue('f'.$i,iconv("big5","utf-8",$row['殘量']))
							->setCellValue('g'.$i,iconv("big5","utf-8",$row['實際出庫數量']))
							->setCellValue('h'.$i,iconv("big5","utf-8",$row['PDA殘量']))
							->setCellValue('i'.$i,iconv("big5","utf-8",$row['CNO']))
							->setCellValue('j'.$i,iconv("big5","utf-8",$row['客戶名稱']))
							->setCellValueExplicit('k'.$i, (string)$row['OTD_NO'],PHPExcel_Cell_DataType::TYPE_STRING)
//							->setCellValue('k'.$i,$row['OTD_NO'])
							->setCellValue('n'.$i,iconv("big5","utf-8",$row['LEL_TYS_QTY']))
							->setCellValue('m'.$i,iconv("big5","utf-8",$row['LEL_TYS_REMNANT_QTY']))
			;
			$i=$i+1;
	}
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
	$objWriter->save("tmp.xlsx");
	$link='http://'.$path_root.'/prodout/tmp.xlsx';	
	echo '<a href="'.$link.'">檔案連結：'.$link.'</a>';
	echo '<script>document.location.href="http://'.$path_root.'/prodout/tmp.xlsx";</script>';	
	echo '</br>列印完成';
}

if(isset($_POST["save_1"]))
{ 
	
	//////假出庫數量
	$_SESSION['pre_out_qty']=$_POST['pre_out_qty'];
	$query="select * from LORRY_EXAMINE_LIST where (LEL_LOT_NO = '".$lot_no."') and PRA_OUT_COUNT=".$sn;
	$result=mssql_query($query);
	$numrow=mssql_num_rows($result);
	if($numrow==0){
		
		$query="INSERT INTO LORRY_EXAMINE_LIST
SELECT          LEL_LOT_NO,".$sn." , PDD_PROD_NO, LEL_LY_NO, LEL_FAKE_QTY, LEL_REMNANT_QTY, 
                            LEL_PDA_REMNANT_QTY, LEL_TYS_QTY, LEL_TYS_REMNANT_QTY, LEL_TYS_QTY_MAN, 
                            LEL_TYS_REMNANT_QTY_MAN, LEL_CUST_QTY, LEL_CUST_UNIT, OTD_NO
FROM              LORRY_EXAMINE_LIST AS LORRY_EXAMINE_LIST_1
WHERE          (LEL_LOT_NO = '".$lot_no."')";
		$result=mssql_query($query);
	}
	$query="UPDATE          LORRY_EXAMINE_LIST
			SET                   LEL_FAKE_QTY = ".$_SESSION['pre_out_qty']."
			WHERE          (LEL_LOT_NO = '".$lot_no."') and PRA_OUT_COUNT=".$sn;
	$_SESSION['s1']= $query."<BR>";
	$result = mssql_query($query);
	//////殘液量
	$_SESSION['left_qty']=$_POST['left_qty'];
	$query="UPDATE          LORRY_EXAMINE_LIST
			SET                   LEL_REMNANT_QTY = ".$_SESSION['left_qty']."
			WHERE          (LEL_LOT_NO = '".$lot_no."') and PRA_OUT_COUNT=".$sn;
	$_SESSION['s2']= $query."<BR>";
	$result = mssql_query($query);
	////出荷時間
	$_SESSION['datepicker3']=$_POST['datepicker3'];
	$_SESSION['hs_1']=$_POST['hs_1'];
	$query="UPDATE          FILLPLAN_OUT_DECIDE
			SET                   FDM_REAL_OUT_TIME = '".dod($_SESSION['datepicker3']).$_SESSION['hs_1']."'
			WHERE          (FDM_LOT_NO = '".$lot_no."')";
	$_SESSION['s3']= $query."<BR>";
	$result = mssql_query($query);
	////回廠時間
	$_SESSION['datepicker4']=$_POST['datepicker4'];
	$_SESSION['hs_2']=$_POST['hs_2'];
	$query="UPDATE          FILLPLAN_OUT_DECIDE
			SET                   FDM_REAL_RETURN_TIME = '".dod($_SESSION['datepicker4']).$_SESSION['hs_2']."'
			WHERE          (FDM_LOT_NO = '".$lot_no."')";
	$_SESSION['s4']= $query."<BR>";
	$result = mssql_query($query);
	refresh();
}

if(isset($_POST["save_5"]))
{ 
	$_SESSION['tys_qty']=$_POST['tys_qty'];
	$query="UPDATE          LORRY_EXAMINE_LIST
			SET                   LEL_TYS_QTY = ".$_SESSION['tys_qty']."
			WHERE          (LEL_LOT_NO = '".$lot_no."') and PRA_OUT_COUNT=".$sn;
	$result = mssql_query($query);
	refresh();
}

if(isset($_POST["save_6"]))
{ 
	$_SESSION['tys_left_qty']=$_POST['tys_left_qty'];
	$query="UPDATE          LORRY_EXAMINE_LIST
			SET                   LEL_TYS_REMNANT_QTY = ".$_SESSION['tys_left_qty']."
			WHERE          (LEL_LOT_NO = '".$lot_no."') and OTNP_SERIAL_NO=".$sn;
	$result = mssql_query($query);
	refresh();
}

class last_LEL{
	public $out_count,$fake_out,$rem_qty,$lotno,$real_out,$sn;	
	function run()
	{
		$query="SELECT          PRA_OUT_COUNT, LEL_CUST_QTY, LEL_CUST_UNIT, LEL_CUST_QTY AS custqty, LEL_TYS_QTY AS tys_qty, 
                            LEL_TYS_REMNANT_QTY AS tys_rem, LEL_REMNANT_QTY AS rem_qty, LEL_FAKE_QTY AS fake_qty, 
                            PDD_PROD_NO
FROM              LORRY_EXAMINE_LIST  
            WHERE   (LEL_LOT_NO  = '".$this->lotno."')  
            AND (LORRY_EXAMINE_LIST.PRA_OUT_COUNT = ".$this->sn.") ORDER BY PRA_OUT_COUNT DESC";	
//		echo $query."<BR>"; 
		$result=mssql_query($query);
		$rows=mssql_fetch_row($result);
		$this->out_count=$rows[0];
		$this->fake_out=$rows[7];
		$this->rem_qty=$rows[6];
		$this->real_out=$rows[7]-$rows[6];
	}
}

function get_prod_no($lotno){
	$query="SELECT PDD_PROD_NO AS prodno FROM OUT_PRODUCT WHERE (OPD_LOT_NO = '".$lotno."') ";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	return $row[0];
}

function check_OTNP_SERIAL_NO($lotno){
	$query="SELECT OPM_ORDER_NO,  PRA_OUT_COUNT FROM OUT_PRODUCT WHERE (OPD_LOT_NO = '".$lotno."') order by COPCONFIRMSERIES";
//	echo $query."<BR>";
	$result=mssql_query($query);
	$i=0;
	while($row=mssql_fetch_array($result)){
		$i++;
		$query1="UPDATE OUT_PRODUCT SET OTNP_SERIAL_NO = ".$i." WHERE (OPD_LOT_NO = '".$lotno."') AND (OPM_ORDER_NO = '".$row['OPM_ORDER_NO']."')";
//		echo $query1."<BR>";
		$result1=mssql_query($query1);
	}
}
 ?>
