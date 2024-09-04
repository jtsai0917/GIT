<?php 
session_start();
include("../lib/fun.php");
include("../checkuser.php");
include("../lib/jtsai.php");
include("../connections/conn.php");
include("../PHPEXCEL/Classes/PHPExcel.php");
include("../PHPEXCEL/Classes/PHPExcel/IOFactory.php");
lasturl();
datepick(); 
?>
<form name="form1" method="post" enctype="multipart/form-data" action="<?php echo $loginFormAction; ?>">
  <label for="textfield"></label>

		<table bgcolor="#CCCCCC" border="1">
        <tr><td>
 		巡檢日期 <span class="d1">
        <input name="datepicker7" type="text" id="datepicker7" size="10" value="<?php echo $_SESSION['datepicker7'] ?>" onchange="set_date_session(this.name,this.value)" >
        ~
        <input name="datepicker8" type="text" id="datepicker8" size="10" value="<?php echo $_SESSION['datepicker8'] ?>" onchange="set_date_session(this.name,this.value)" >
        巡檢表：
        <select name="form"  onchange="set_date_session(this.name,this.value)">
         <?php
		$query="select * from UTT_FORM_DATA";
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result)){
		if(trim($row['index'])==$_SESSION['form']){$show1=' selected ';}else{$show1='';}
		echo '<option value="'.trim($row['index']).'" '.$show1.'>'.trim($row['Form']).'</option>';
		if($_SESSION['form']==''){$_SESSION['form']=trim($row['index']);}
		}
		$query="select * from UTT_FORM_DATA where [index]='".$_SESSION['form']."'";
		$result=mssql_query($query);
		$row=mssql_fetch_array($result);
		$_SESSION['way']=trim($row['way']);
		?>
        </select>

         第幾次巡檢：
        <select name="sn"  onchange="set_date_session(this.name,this.value)">
         <?php
		 if($i==$_SESSION['sn']){$show=' selected ';}else{$show='';}
		echo '<option value="0" '.$show.'>全部</option>';
		 for($i=1;$i<7;$i++){
		if($i==$_SESSION['sn']){$show1=' selected ';}else{$show1='';}
		echo '<option value="'.$i.'" '.$show1.'>'.$i.'</option>';
		}
		?>
        </select>
        <input type="submit" name="search" id="search" value="搜尋">
        <input type="submit" name="print" id="print" value="列印">
        <input type="button" value="巡檢資料輸入" onclick="location.href='/PRODUCE/UTT_check_input.php'"">
        <input type="hidden" name="mm_insert" id="mm_insert" value="form1">

        </span></td></table>
</form>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
$_SESSION['lasturl']=$_SERVER['REQUEST_URI'];
if(isset($_POST['search']))
{
	$query2="select * from UTT_FORM_DATA where [index]='".$_POST['form']."'";
	$result2=mssql_query($query2);
	$row2=mssql_fetch_array($result2);
	$link=trim($row2['link']);
	$sn=trim($row2['sn']);
	$sn1=trim($row2['sn1']);
	$way=trim($row2['way']);
	$reader = PHPExcel_IOFactory::createReader('Excel2007'); 
	$PHPExcel = $reader->load("..".$link);
	$sheet = $PHPExcel->getSheet(0);
	$highestRow = $sheet->getHighestRow ();// 獲取當頁最大行數
	$highestColumn = $sheet->getHighestColumn ();// 獲取當頁最大列數 
	if($way=='B')///橫式
	{
		$highColumn=PHPExcel_Cell::columnIndexFromString($highestColumn);
		$Num=PHPExcel_Cell::columnIndexFromString(substr($sn,0,1))-1; //A->1
		$Num1=preg_replace('/[^\d]/','',$sn);
		for($i=$Num1;$i<=$highColumn;$i++)
		{
			//echo $i.','.$Num1.'<br>';
			$val=$sheet->getCellByColumnAndRow($i,$Num1)->getValue();
			$val=trim($val);
			if($val==''){continue;}
			$EN=PHPExcel_Cell::stringFromColumnIndex($i); //0->A
			$column[$val]=$EN.$Num1;
			//echo '第一行:'.$EN.$Num1.':'.$val.'<br>';
		}
		$Num=PHPExcel_Cell::stringFromColumnIndex($Num-1);
		$timetitle=$Num.($Num1+4); ///時間欄位
		if($sn1<>'')
		{
			$Num=PHPExcel_Cell::columnIndexFromString(substr($sn1,0,1))-1;//A->1
			$Num1=preg_replace('/[^\d]/','',$sn1);
			for($i=$Num;$i<=$highColumn;$i++)
			{
				$val=$sheet->getCellByColumnAndRow($i,$Num1)->getValue();
				$val=trim($val);
				if($val==''){continue;}
				$EN=PHPExcel_Cell::stringFromColumnIndex($i); //0->A
				$column[$val]=$EN.$Num1;
				//echo '第二行:'.$EN.$Num1.':'.$val.'<br>';
			}
			$Num=PHPExcel_Cell::stringFromColumnIndex($Num-1);
			$timetitle1=$Num.($Num1+4); ///時間欄位
		}
	}
	else///直式
	{
		$Num=PHPExcel_Cell::columnIndexFromString(substr($sn,0,1))-1;//A->1
		$Num1=preg_replace('/[^\d]/','',$sn);
		for($i=$Num1;$i<=$highestRow;$i++)
		{
			$val=$sheet->getCellByColumnAndRow($Num,$i)->getValue();
			$val=trim($val);
			if($val==''){continue;}
			$EN=PHPExcel_Cell::stringFromColumnIndex($Num); //0->A
			$column[$val]=$EN.$i;
			//echo '第一行:'.$EN.$i.':'.$val.'<br>';
		}
		//echo $Num.','.$Num1.','.$sn1.'<br>';
		$Num=PHPExcel_Cell::stringFromColumnIndex($Num+4);
		$timetitle=$Num.($Num1-1); ///時間欄位
		if($sn1<>'')
		{
			$Num=PHPExcel_Cell::columnIndexFromString(substr($sn1,0,1))-1;//A->1
			$Num1=preg_replace('/[^\d]/','',$sn1);
			//echo $Num.','.$Num1;break;
			for($i=$Num1;$i<=$highestRow;$i++)
			{
				$val=$sheet->getCellByColumnAndRow($Num,$i)->getValue();
				$val=trim($val);
				if($val==''){continue;}
				$EN=PHPExcel_Cell::stringFromColumnIndex($Num); //0->A
				$column[$val]=$EN.$i;
				//echo '第二行:'.$EN.$i.':'.$val.'<br>';
			}
			$Num=PHPExcel_Cell::stringFromColumnIndex($Num+4);
			$timetitle1=$Num.($Num1-1); ///時間欄位
		}
	}
	$objPHPExcel = new PHPExcel();
	$objPHPExcel = PHPExcel_IOFactory::load("..".$link);
	$objPHPExcel-> setActiveSheetIndex(0);
	$num=preg_replace('/[^\d]/','',$timetitle);
	$eng=ereg_replace("[0-9]","",$timetitle);	
	$num1=preg_replace('/[^\d]/','',$timetitle1);
	$eng1=ereg_replace("[0-9]","",$timetitle1);	
	if($_SESSION['way']=='A'){$form='form1';}else{$form='form';}
	$query2="SELECT distinct Date from UTT_CHECK_DATA UD left join UTT_TAGNO_DATA UN ON UD.TAG_NO=UN.TAG_NO where date>= '".dateform1($_SESSION['datepicker7'])."1600' and date<='".dateform1($_SESSION['datepicker8'])."0000' and ".$form."=".$_POST['form']." order by date";
	//echo $query2;
	$result2=mssql_query($query2);
	$Numrows=mssql_num_rows($result2);
	if($Numrows==0){my_msg('查無資料');}
	while($row2=mssql_fetch_array($result2))
{
	$query="select FD.*,TD.TAG_NO from UTT_FORM_DATA FD LEFT JOIN UTT_TAGNO_DATA TD ON   FD.[index]=TD.".$form."   where FD.[index]='".$_POST['form']."'";
	//echo $query;
	$result=mssql_query($query);$title=1;
	echo '<table width="1000" border="1" align="left">';
	echo '<tr bgcolor="#CCCCCC"><td>TAG_NO</td><td>項目名稱</td><td>數值</td><td>SPEC</td><td>巡檢次數</td><td>巡檢日期時間</td><td>巡檢人員</td><td>合格/不合格</td></tr>';
	while($row=mssql_fetch_array($result))
	{
	$styleThinBlackBorderOutline = array(  
				   'borders' => array (  
						 'outline' => array (  
							   'style' => PHPExcel_Style_Border::BORDER_THIN,   //?置border?式 
							   //'style' => PHPExcel_Style_Border::BORDER_THICK,  另一种?式  
						),  
				  ),  
			);
		$query1="select rs.*,TD.spec,TD.project from UTT_record_spec rs left join UTT_TAGNO_DATA TD ON rs.TAG_NO=TD.TAG_NO where rs.TAG_NO='".trim($row['TAG_NO'])."' and Date ='".$row2[0]."'";
		//echo $query1.'<br>';
		if($_POST['sn']!=0){$query1.=" and sn<='".$_POST['sn']."' order by sn";}
		//echo $query1.'<br>';
		$result1=mssql_query($query1);
		while($row1=mssql_fetch_array($result1))
		{
			//echo $title.','.trim($row1['sn']).'<br>';
				$pass=trim($row1['pass']);
				if($pass=='O'){$pass='合格';$BG='';}
				elseif($pass=='△'){$pass='';$BG='';}
				else{$pass='不合格';$BG='bgcolor="#FFFF66"';}
				echo '<tr '.$BG.'><td>'.$row1['TAG_NO'].'</td><td>'.$row1['project'].'</td><td>'.$row1['Data'].'</td><td>'.$row1['spec'].'</td><td>'.$row1['sn'].'</td><td>'.$row1['Date'].'</td><td>'.getusername($row1['saveuid']).'</td><td>'.$pass.'</td></tr>';
				$COLUMN=$column[trim($row1['TAG_NO'])];
				//echo trim($row1['TAG_NO']).'='.$COLUMN.'='.trim($row1['Data']).'<br>';
				if($way=='A')
				{//直式
					if($title==trim($row1['sn']))
					{
					$objPHPExcel->getActiveSheet()->setCellValue($eng.$num, iconv("big5","utf-8","'".trim($row1['Date'])));
					$objPHPExcel->getActiveSheet()->getStyle($eng.$num)->applyFromArray($styleThinBlackBorderOutline);  ///畫儲存格外框
						if($timetitle1<>'')
						{
							$objPHPExcel->getActiveSheet()->setCellValue($eng1.$num1, iconv("big5","utf-8","'".trim($row1['Date'])));
							$objPHPExcel->getActiveSheet()->getStyle($eng1.$num1)->applyFromArray($styleThinBlackBorderOutline);  ///畫儲存格外框
						}
					$eng++;$eng1++;
					}
					$A=PHPExcel_Cell::columnIndexFromString(ereg_replace("[0-9]","",$COLUMN))+2+$row1['sn'];
					$ENG=PHPExcel_Cell::stringFromColumnIndex($A);
					$Num=preg_replace('/[^\d]/','',$COLUMN);
				//echo trim($row1['TAG_NO']).':'.$ENG.$Num.'='.$row1['Data'].'<BR>';
					if($pass=='不合格')
					{
					$objPHPExcel->getActiveSheet()->getStyle($ENG.$Num)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' =>'FFFF30'))); 
					}
					
					$objPHPExcel->getActiveSheet()->setCellValue($ENG.$Num, iconv("big5","utf-8",trim($row1['Data'])));
				}
				else//橫式
				{
					
					if($title==trim($row1['sn']))
					{
					$objPHPExcel->getActiveSheet()->setCellValue($eng.$num, iconv("big5","utf-8","'".trim($row1['Date'])));
					$objPHPExcel->getActiveSheet()->getStyle($eng.$num)->applyFromArray($styleThinBlackBorderOutline);  ///畫儲存格外框
						if($timetitle1<>'')
						{
							$objPHPExcel->getActiveSheet()->setCellValue($eng1.$num1, iconv("big5","utf-8","'".trim($row1['Date'])));
							$objPHPExcel->getActiveSheet()->getStyle($eng1.$num1)->applyFromArray($styleThinBlackBorderOutline);  ///畫儲存格外框
						}
					$num++;$num1++;
					}
					$ENG=ereg_replace("[0-9]","",$COLUMN);
					//$ENG=PHPExcel_Cell::stringFromColumnIndex($COLUMN);
					$Num=preg_replace("/[^\d]/","",$COLUMN)+3+$row1['sn'];
					//echo trim($row1['TAG_NO']).':'.$ENG.$Num.'='.$row1['Data'].','.$pass.'<BR>';
					if($pass=='不合格')
					{
					$objPHPExcel->getActiveSheet()->getStyle($ENG.$Num)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' =>'FFFF30'))); 
					}
					$objPHPExcel->getActiveSheet()->setCellValue($ENG.$Num, iconv("big5","utf-8",trim($row1['Data'])));
				}
				$objPHPExcel->getActiveSheet()->getStyle($ENG.$Num)->applyFromArray($styleThinBlackBorderOutline); ///畫儲存格外框
				//echo $A;
				//$objPHPExcel->getActiveSheet()->setCellValue($COLUMN, iconv("big5","utf-8",'=SUM(H6:H36)'));
			$title++;
		}
	}
}
	echo '</table>';
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
	$A=explode("(",formname($_SESSION['form']));
	$_SESSION['filename']=$A[0].'('.des($_SESSION['datepicker7']).').xlsx';
	$objWriter->save(iconv("big5","utf-8",$A[0]).'_'.des($_SESSION['datepicker7']).'.xlsx');
} ///$_POST['search']
if(isset($_POST['print']))
{
	$A=explode("(",formname($_SESSION['form']));
	$path_root=$_SERVER['HTTP_HOST'];
	echo '<script>document.location.href="http://'.$path_root.'/PRODUCE/'.$A[0].'_'.des($_SESSION['datepicker7']).'.xlsx";</script>';
}
function dateform1($d1)  //2017/01/01 => 20170101
{	
	$rt=str_replace("/","",$d1);
	return $rt;
}
function formname($d1)  //2017/01/01 => 20170101
{	
	$query="select * from UTT_FORM_DATA where [index]='".$d1."'";
	$result=mssql_query($query);
	$row=mssql_fetch_array($result);
	$rt=trim($row['Form']);
	return $rt;
}
?>
</table>
</br>