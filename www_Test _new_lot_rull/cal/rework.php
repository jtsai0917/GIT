<?php
	$_SESSION['lasturl']=$_SERVER['REQUEST_URI'];
	include("../connections/conn.php");
	include("../lib/fun.php");
	include("../lib/jtsai.php");
	include("../lib/style.php");
	$path_root=$_SERVER['HTTP_HOST'];
	datepick(); 
?>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>jQuery UI Datepicker - Default functionality</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script type="text/javascript">
    $(function() {
    $( "#datepicker1" ).datepicker();
	$( "#datepicker2" ).datepicker();
	$( "#datepicker3" ).datepicker();
	$( "#datepicker4" ).datepicker();
  });
</script>
<form name="form1" method="post" action="">
<table width="1238" border="1">
  <tr>
  	<td>
    	<font size="+1"> 再分析作業</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  </tr>
  </table>
  <table width="1238" border="1">
    <tr>
      <td>
        <p>新建再分析： Lot No:
          <input name="lot_no" type="text" id="lot_no" size="14" value="<?php if($_GET['lot_no']<>''){$_SESSION['lot_no']= $_GET['lot_no'];}
	  if($_SESSION['lot_no']<>''){echo $_SESSION['lot_no'];} ?>"  onchange="set_date_session(this.name,this.value)"> 
          &nbsp;&nbsp;&nbsp;&nbsp;
          <input type="submit" name="select_items" id="select_items" value="選擇再分析項目" />
          預定送樣時間：
          <input name="datepicker3" type="text" id="datepicker3" size="8" value="<?php echo date("m/d/Y"); ?>" onchange="set_date_session(this.name,this.value)">  
          &nbsp;&nbsp;
          <?php hr();?>
          ：
          <?php mm();?>
          &nbsp;&nbsp;再分析次數
          <select name="times" id="times">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
          </select>
          &nbsp;&nbsp; 說明：
          <input type="text" name="note" id="note">
  <input name="create" type="submit" id="create" value=" 建立再分析 " size="14">  
  </p></td></tr><tr><td>
        <p>
          搜尋再分析：送樣時間 
          	<input name="datepicker1" type="text" id="datepicker1" size="14" value="<?php 	echo $_SESSION['datepicker1'];?>"  onchange="set_date_session(this.name,this.value)">
        ~
        	<input name="datepicker2" type="text" id="datepicker2" size="14" value="<?php 	echo $_SESSION['datepicker2'];?>"  onchange="set_date_session(this.name,this.value)">
          <input name="list" type="submit" id="list" value=" 列表顯示 " size="14"> 
          <input name="list1" type="submit" id="list1" value=" 列印EXCEL " size="14">
        </p>  
      </td>
    </tr>
</table>
</form>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
$lt=new get_from_lot_no;
$lt->lid=$_POST['lot_no'];
$lt->ani();
$items=$lt->items;

if(isset($_POST['list'])){
	echo '<form name="form2" method="post" action="">
		<table width="1238" border="1">';	
				$query="SELECT          REANALYZE_REQUEST_DATA.*
				FROM              REANALYZE_REQUEST_DATA
				WHERE  (RAN_REQ_TIME>'".dod($_SESSION['datepicker1'])."0000') and (RAN_REQ_TIME<'".dod($_SESSION['datepicker2'])."2359')";
	echo '<tr align="center" bgcolor="#CCCCCC">';
	echo '<td width="100">Lot No</td><td width="70">產品</td><td width="30">次數</td><td width="40">分析師</td><td width="350">不合格項目</td><td width="110">
	預估送樣時間</td><td>說明</td><td width="100">取消作業</br>(限本人)</td>';
	echo '</tr>';
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	while($row=mssql_fetch_array($result))
	{
		if($row['RAN_REQ_TIME']){$rnt=ddt($row['RAN_REQ_TIME']);}
		echo '<tr><td>'.$row['RAN_LOT_NO'].'</td><td>'.$row['RAN_PROD_NO'].'</td><td>'."再".$row['RAN_TIMES'].'</td><td>'.get_uname($row['RAN_TESTER']).'</td><td>'.
		ana_id_to_nick($row['RAN_FAIL_ITEM']).'</td><td>'.$rnt.'</td><td>'.$row['Note'].'</td><td align="center">';
		if($_SESSION['uid']==$row['RAN_TESTER']){
		echo '<input type="submit" value=" 取消 " name="delete" onClick="window.open('."'./index.php?url=delete_re_sample&ranid=".$row['RAN_NO']."&lot_no=".$row['RAN_LOT_NO']."', '_blank');".'"'.' />';
		}
		echo '</td></tr>';
	}
	echo '</table></form>';	
}

if (isset($_POST['create']))
{
	echo "建立............";
	echo "</br>";
	if(($_POST['lot_no']<>'') and ($lt->pid<>'') and ($_SESSION['items1']<>''))
	{
	$query="INSERT INTO dbo.REANALYZE_REQUEST_DATA
                          (RAN_LOT_NO, RAN_PROD_NO, RAN_TIMES, RAN_TESTER, ANI_GROUPNAME,
                          RAN_FAIL_ITEM, RAN_REQ_TIME, Note)
			VALUES         ('".$_POST['lot_no']."', '".$lt->pid."', ".$_POST['times'].", '".$_SESSION['uid']."', '".$_GET['ani_groupname']."', '".$_SESSION['items1']."', '".dod($_POST['datepicker3']).$_POST['hh'].$_POST['mm']."', '".$_POST['note']."') SELECT scope_identity() as [ID] ";
	$result=mssql_query($query);
	echo "新增完成";
	echo "</br>";
	while($row=mssql_fetch_array($result)){$id=$row['ID'];$req_time=$row['req_time'];}
	
	$query="UPDATE        dbo.REANALYZE_REQUEST_DATA
			SET                  RAS_NO = ".$id."
			WHERE         (RAN_NO = ".$id.")";
	$result=mssql_query($query);
	}
	
	else{
		echo "新增失敗";
		echo "</br>";
		}
/*			
	$query="INSERT INTO dbo.REANALYZE_SAMPLE_LIST
                          (RAS_NO, RAS_LOT_NO, RAS_GROUPNAME, RAS_PLAN_TIME, RAS_SAM_SOURCE)
			VALUES         (".$id.",'".$_POST['lot_no']."','".$_GET['ani_groupname']."','".dod($_POST['datepicker3']).$_POST['hh'].$_POST['mm']."','".$_POST['source']."')";	
				
	$result=mssql_query($query);	
	
*/	
//	refresh();
}

if (isset($_POST['select_items']))
{
	$_SESSION['lot_no']=$_POST['lot_no'];
	select_testitems($_POST['lot_no'],$items);
}
if (isset($_POST["add1"]))
{
	$_SESSION['lot_no']=$_POST['lot_no'];
	$aa=$_POST['chkbox'];
    $_SESSION['items1']=implode(",", $aa);
}
if (isset($_POST["delete"]))
{

}

if(isset($_POST["list1"])){
require_once ("../PHPEXCEL/Classes/PHPExcel.php");
require_once ("../PHPEXCEL/Classes/PHPExcel/IOFactory.php");

$objPHPExcel = new PHPExcel(); 
$objPHPExcel->setActiveSheetIndex(0);

$query="SELECT         *
				FROM             dbo.REANALYZE_REQUEST_DATA 
				WHERE  (RAN_REQ_TIME>'".dod($_SESSION['datepicker1'])."0000') and (RAN_REQ_TIME<'".dod($_SESSION['datepicker2'])."2359')";
				
$no=1;
				$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	while($row=mssql_fetch_array($result))
	{
		if($row['RAN_REQ_TIME']){$rnt=ddt($row['RAN_REQ_TIME']);}
		
 $objPHPExcel->getActiveSheet()->setCellValue("'A".$no."'", $row['RAN_LOT_NO']);//可以指定位置
 
  $objPHPExcel->getActiveSheet()->setCellValue("'B".$no."'", $row['RAN_PROD_NO']);
   $objPHPExcel->getActiveSheet()->setCellValue("'C".$no."'",iconv("big5","utf-8","再".$row['RAN_TIMES']));
    $objPHPExcel->getActiveSheet()->setCellValue("'D".$no."'",iconv("big5","utf-8",get_uname($row['RAN_TESTER'])));
	 $objPHPExcel->getActiveSheet()->setCellValue("'E".$no."'", ana_id_to_nick($row['RAN_FAIL_ITEM']));
	  $objPHPExcel->getActiveSheet()->setCellValue("'F".$no."'", $rnt);
	   $objPHPExcel->getActiveSheet()->setCellValue("'G".$no."'", $row['Note']);
	   $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	   $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	   $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	   $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	   $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	   $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
	   $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
	   
	   $no++;
	}
	   

//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
//$objWriter->save('test1.xlsx');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
	$objWriter->save('test1.xlsx');
echo '<script>document.location.href="http://'.$path_root.'/cal/test1.xlsx";</script>';	

}
	
	
?>