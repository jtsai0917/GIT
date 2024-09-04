<?php
session_start();
include("../lib/fun.php");
include("../checkuser.php");
include("../connections/conn.php");
$path_root=$_SERVER['HTTP_HOST'];
lasturl();
datepick();
unset($_SESSION['urln1']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>輸出樣品瓶清單</title>
  <title>jQuery UI Datepicker - Default functionality</title>

<body>
<form id="form1" name="form1" method="post" action="<?php $loginFormAction; ?>">
  <label for="date_type"></label>
  <table width="1024" border="1">
    輸出樣品瓶清單
    <tr>
      <td width="924" height="70" align="left" valign="center">查詢範圍 
            <label for="datetype"></label>
          <select name="datetype" id="datetype">
            <option value="1">啟用日期</option>
            <option value="2">出廠日期</option>
            <option value="3">回收日期</option>
            <option value="4">廢棄日期</option>
          </select>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;日期
        <input name="datepicker1" type="text" id="datepicker1" size="10"  onchange="set_date_session(this.name,this.value)" value="<?php 
		if($_GET['datepicker1']){
			echo $_GET['datepicker1'];
			$_SESSION['datepicker1']=$_GET['datepicker1'];
		}
		elseif($_SESSION['datepicker1']){
			echo $_SESSION['datepicker1'];
		}
		elseif($_SESSION['datepicker1']){
			echo $_SESSION['datepicker1'];
		}
		elseif($_POST['datepicker1']){
			$_SESSION['datepicker1']=$_POST['datepicker1'];
			echo $_SESSION['datepicker1'];
		}
		else{
			$d=strtotime("-0 Days"); echo date("m/d/Y",$d);
		}
?>" />
        ~
        <input name="datepicker2" type="text" id="datepicker2" size="10"  onchange="set_date_session(this.name,this.value)" value="<?php 
		if ($_GET['datepicker2']){
			echo $_GET['datepicker2'];
			$_SESSION['datepicker2']=$_GET['datepicker2'];
		}
		elseif($_SESSION['datepicker2']){
			echo $_SESSION['datepicker2'];
		}
		elseif($_POST['datepicker2']){
			$_SESSION['datepicker2']=$_POST['datepicker2'];
			echo $_SESSION['datepicker1'];
		}
		else{
		$d=strtotime("+0 Days"); echo date("m/d/Y",$d);
		}
		?>" /> 
<?php space(8); ?>類型
<label for="smp_type"></label>
<select name="smp_type" id="smp_type">
  <option value="1">全部</option>
  <option value="2">廠內分析</option>
  <option value="3">隨貨或評估</option>
  <option value="4">保存</option>
  <option value="5">先行</option>
  <option value="6">客戶其他</option>
  <option value="7">先行+客戶其他</option>
</select>
</br>
使用次數
<input name="times" type="text" id="times" value="<?php echo $_SESSION['times'];?>" size="6"/>
        <?php space(10); ?>樣品瓶號
        <input name="smpid" type="text" id="smpid" value="<?php echo $_SESSION['smpid'];?>" size="14"  onchange="set_date_session(this.name,this.value)"/>
        <?php space(23); ?>客戶
        <input type="button" name="X3" id="X3" value="X" onclick="window.open('../erase_customer.php ', '_self');" />
        <input name="cust_no" type="text"  size="10" value="<?php echo $_SESSION['cust_no'];?>" readonly="readonly" />
        <input type="button" name="pdd_no2" id="pdd_no2" value="查詢" onclick="window.open('../cust_no.php?sup=N ', 'Select');" />
        <input name="cust_name" type="text" size="16" value="<?php echo $_SESSION['cust_name'];?>" />
        </br>
      樣品材質
      <label for="metrial"></label>
      <select name="metrial" id="metrial">
        <option value="">不指定</option>
        <option value="PE" <?php if($_SESSION['metrial']=='PE'){echo "selected";}?>>PE</option>
        <option value="PFA" <?php if($_SESSION['metrial']=='PFA'){echo "selected";}?>>PFA</option>
      </select>
       <?php space(11); ?>回收判斷
      <select name="returned" id="returned">
        <option value="">不指定</option>
        <option value="OK">OK</option>
        <option value="NG">NG</option>
      </select>
      <?php space(38); ?>
      <input type="button" name="X" id="X" value="X" onclick="window.open('../erase_prod.php ', '_self');" />
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
		?>" readonly="readonly" />
      <input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onclick="window.open('../pdd_prod_no.php ', '_self');" />
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
		?>" readonly="readonly" /></td>
      <td width="100" height="70" align="center" ><input type="submit" name="print" id="print" value="列印" />
        </br>
          <input type="submit" name="search" id="search" value="查詢" />
          </br>
        <input type="submit" name="new_samples" id="new_samples" value="新增樣品瓶" />
    </td>
    </tr>
  </table>
</form>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
if($_POST['search'])
{
	$_SESSION['datepicker1']=$_POST['datepicker1'];
	$_SESSION['datepicker2']=$_POST['datepicker2'];
	echo '<table width="1024" border="1"><tr>';
	echo '<td>次數</td><td>材質</td><td>Service</td><td>品名</td><td>瓶號</td><td>啟用日期</td><td>LotNo</td><td>客戶</td><td>出廠日</td><td>取樣擔當</td>
	<td>回收判斷</td><td>保存期限</td><td>回收日期</td><td>廢棄原因</td><td>廢棄日期</td></tr>';
	
	$query="SELECT   dbo.Sample.*
			FROM             dbo.Sample 
			WHERE ";	
	if($_POST['smpid']){$query.=" (SMP_ID like '%".$_POST['smpid']."%') " ;}
	else{
	if($_POST['datetype']=='1'){$query.="(SMP_START>'".dod($_POST['datepicker1'])."') and (SMP_START<'".dod($_POST['datepicker2'])."')" ;}
	if($_POST['datetype']=='2'){$query.="(SMP_OUT>'".dod($_POST['datepicker1'])."') and (SMP_OUT<'".dod($_POST['datepicker2'])."')" ;}
	if($_POST['datetype']=='3'){$query.="(SMP_FINISH>'".dod($_POST['datepicker1'])."') and (SMP_FINISH<'".dod($_POST['datepicker2'])."')" ;}
	if($_POST['datetype']=='4'){$query.="(SMP_JUNK_D>'".dod($_POST['datepicker1'])."') and (SMP_JUNK_D<'".dod($_POST['datepicker2'])."')" ;}
	if($_POST['smp_type']=='2'){$query.="and (SMP_SERVICE=1)" ;}
	if($_POST['smp_type']=='3'){$query.="and (SMP_SERVICE=2)" ;}
	if($_POST['smp_type']=='4'){$query.="and (SMP_SERVICE=3)" ;}
	if($_POST['smp_type']=='5'){$query.="and (SMP_SERVICE=4)" ;}
	if($_POST['smp_type']=='6'){$query.="and (SMP_SERVICE=5)" ;}
	if($_POST['smp_type']=='7'){$query.="and ((SMP_SERVICE=4) or (SMP_SERVICE=5))" ;}
	if($_POST['pdd_chemical1']){$query.="and (SMP_MID_ID='".$_POST['pdd_chemical1']."')";}
	if($_POST['metrial']){$query.=" and (SMP_MAT='".$_POST['metrial']."') " ;}
	if($_POST['cust_no']){$query.=" and (SMP_USER='".$_POST['cust_no']."') " ;}
	}
	$_SESSION['query']=$query;
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	while($row=mssql_fetch_array($result))
	{
		$str='onclick="'."window.open('./smp_history.php?smpid=".$row['SMP_ID']."', '_blank',config='height=500,width=1200');".'"';
		echo '<tr '.$str.'>';
		echo '<td>'.$row['SMP_TIMES'].'</td><td>'.$row['SMP_MAT'].'</td><td>'.sma_service_type($row['SMP_SERVICE']).'</td><td>'.get_prod_name($row['SMP_MID_ID']).'</td><td>'.
		$row['SMP_ID'].'</td><td>'.$row['SMP_START'].'</td><td>'.$row['SMP_LOT'].'</td><td>'.get_cust_name($row['SMP_USER']).'</td><td>'.$row['SMP_OUT'].'</td><td>'.get_uname($row['SMP_SMP']).
		'</td><td>'.$row['SMP_BACK'].'</td><td>'.$row['SMP_SAVE'].'</td><td>'.$row['SMP_FINISH'].'</td><td>'.$row['SMP_JUNK'].'</td><td>'.$row['SMP_JUNK_D'].'</td></tr>';
	}
	echo '</table></br>';
}
if($_POST['print'])
{
	require_once("../PHPEXCEL/Classes/PHPExcel.php");
	require_once("../PHPEXCEL/Classes/PHPExcel/IOFactory.php");
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel = PHPExcel_IOFactory::load("./form/sample_list.xlsx");
	$query=$_SESSION['query'];
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	$x=2;
	while($row=mssql_fetch_array($result))
	{
		$objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$x,iconv("big5","utf-8",$row['SMP_TIMES']))
							->setCellValue('B'.$x,iconv("big5","utf-8",$row['SMP_MAT']))
							->setCellValue('C'.$x,iconv("big5","utf-8",sma_service_type($row['SMP_SERVICE'])))
							->setCellValue('D'.$x,iconv("big5","utf-8",get_prod_name($row['SMP_MID_ID'])))
							->setCellValue('E'.$x,iconv("big5","utf-8",$row['SMP_ID']))
							->setCellValue('F'.$x,iconv("big5","utf-8",$row['SMP_START']))
							->setCellValue('G'.$x,iconv("big5","utf-8",$row['SMP_LOT']))
							->setCellValue('H'.$x,iconv("big5","utf-8",get_cust_name($row['SMP_USER'])))
							->setCellValue('I'.$x,iconv("big5","utf-8",$row['SMP_OUT']))
							->setCellValue('J'.$x,iconv("big5","utf-8",get_uname($row['SMP_SMP'])))
							->setCellValue('K'.$x,iconv("big5","utf-8",$row['SMP_BACK']))
							->setCellValue('L'.$x,iconv("big5","utf-8",$row['SMP_SAVE']))
							->setCellValue('M'.$x,iconv("big5","utf-8",$row['SMP_FINISH']))
							->setCellValue('N'.$x,iconv("big5","utf-8",$row['SMP_JUNK']))
							->setCellValue('O'.$x,iconv("big5","utf-8",$row['SMP_JUNK_D']))
							;
		$x=$x+1;
	}
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
	$objWriter->save("sample.xlsx");
	
	echo '<script>document.location.href="http://'.$_SERVER['HTTP_HOST'].'/samples/sample.xlsx";</script>';  

}
?>
</body>
</html>

<?php 
function sma_service_type($sma_service)
{
	if($sma_service==1){return "廠內分析";} 
	if($sma_service==2){return "隨貨或評估";} 
	if($sma_service==3){return "保存樣品";}
	if($sma_service==4){return "先行樣品";}	
}

if($_POST['new_samples'])
{
 jumpto("index.php?url=create_sample");	
}

?>