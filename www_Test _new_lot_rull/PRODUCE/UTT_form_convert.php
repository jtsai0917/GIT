<?php 
session_start();
include("../lib/fun.php");
include("../checkuser.php");
include("../lib/jtsai.php");
lasturl();
datepick(); 
?>
<form name="form1" method="post" enctype="multipart/form-data" action="<?php echo $loginFormAction; ?>">
  <label for="textfield"></label>
  轉檔用:<input id="file" name="file" type="file"/> 位置:<input type="text" name="column" size="5">  <input type="submit" name="saveconvert" value="送出" />
    
<?php
if(isset($_POST['saveconvert']))
{
	include("../PHPEXCEL/Classes/PHPExcel.php");
	include("../PHPEXCEL/Classes/PHPExcel/IOFactory.php");
	if($_POST['column']==''){my_msg('請填入位置');}
	move_uploaded_file($_FILES['file']['tmp_name'],'../PRODUCE/CONVERT.xlsx');
	//echo $_FILES['file']['name'];break;
	$reader = PHPExcel_IOFactory::createReader('Excel2007'); 
	$PHPExcel = $reader->load("../PRODUCE/CONVERT.xlsx");
	$sheet = $PHPExcel->getSheet(0);
	$highestRow = $sheet->getHighestRow ();// 獲取當頁最大行數
	$highestColumn = $sheet->getHighestColumn ();// 獲取當頁最大列數 
	//echo $_POST['column'];
	//echo $highestRow.','.$highestColumn;
	$ENG=substr($_POST['column'],0,1);
	$ENG=PHPExcel_Cell::columnIndexFromString($ENG)-1;//A->1
	$NUM=substr($_POST['column'],1,1);
	//echo $ENG.','.$NUM;break;
	$I=0;
	for($i=$NUM;$i<$highestRow;$i++)
	{
		$val[$I]=$sheet->getCellByColumnAndRow($ENG,$i)->getValue();
		if($val[$I]==''){continue;}
		//echo $val.'<br>';
		$query="select * from UTT_TAGNO_DATA where TAG_NO='".$val[$I]."'";
		$result=mssql_query($query);
		$Numrows=mssql_num_rows($result);
		if($Numrows==0)
		{
			$space=strrpos($val[$I],' ');
			$first=substr($val[$I],0,$space);
			$second=substr($val[$I],$space+1);
			//echo $first.','.$second.'<br>';
			$query1="select * from UTT_TAGNO_DATA where TAG_NO like '".$first."%".$second."%'";
			$result1=mssql_query($query1);
			$row1=mssql_fetch_array($result1);
			$val[$I]=trim($row1['TAG_NO']);
			//echo $query1.'<br>';
			//echo $space.'<br>';
		}
		$I++;
		//echo $val.'<br>';		
		
	}
	$objPHPExcel = new PHPExcel();
	$objPHPExcel = PHPExcel_IOFactory::load("../PRODUCE/CONVERT.xlsx");
	$objPHPExcel-> setActiveSheetIndex(0);
	$Eng=substr($_POST['column'],0,1);
	$Num=substr($_POST['column'],1,1);
	for($i=0;$i<count($val);$i++)
	{
		
		if($val[$i]==''){continue;}
		//echo $i.','.$ENG.$NUM.'='.$val[$i].'<br>'; 
		$objPHPExcel->getActiveSheet()->setCellValue($Eng.$Num, iconv("big5","utf-8",$val[$i]));
		$Num++;
	}
	$path_root=$_SERVER['HTTP_HOST'];
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
	$objWriter->save($_FILES['file']['name']);
	echo '<script>document.location.href="http://'.$path_root.'/PRODUCE/'.$_FILES['file']['name'].'";</script>';
	//$_FILES['file']['name']
	
}
if(isset($_POST['search1']))
{
	include("../PHPEXCEL/Classes/PHPExcel.php");
	include("../PHPEXCEL/Classes/PHPExcel/IOFactory.php");
	if($_POST['form']=='ALL'){
	$query="select * from UTT_FORM_DATA order by Form";
	}else{
	$query="select * from UTT_FORM_DATA where [index]=".$_POST['form']." order by Form";}
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result))
	{
		$I=0;
		$link=trim($row['link']);
		$sn=trim($row['sn']);
		$sn1=trim($row['sn1']);
		$way=trim($row['way']);
		$index=trim($row['index']);
		$reader = PHPExcel_IOFactory::createReader('Excel2007'); 
		$PHPExcel = $reader->load("..".$link);
		$sheet = $PHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow ();// 獲取當頁最大行數
		$highestColumn = $sheet->getHighestColumn ();// 獲取當頁最大列數 
		if($way=='A')
		{
			$Num=PHPExcel_Cell::columnIndexFromString(substr($sn,0,1))-1;//A->1
			$Num1=preg_replace('/[^\d]/','',$sn);
			for($i=$Num1;$i<=$highestRow;$i++)
			{
				$val=$sheet->getCellByColumnAndRow($Num,$i)->getValue();
				$val1=$sheet->getCellByColumnAndRow(($Num+1),$i)->getValue();
				$spec=$sheet->getCellByColumnAndRow(($Num+2),$i)->getValue();
				$unit=$sheet->getCellByColumnAndRow(($Num+3),$i)->getValue();
				$val=trim($val);
				if($val==''){continue;}
				$_SESSION['TAG_NO'][$I]=$val.','.$val1.','.$spec.','.$unit;
				//echo '第一行:'.$I.':'.$val.'<br>';
				$I++;
			}
			//echo $Num.','.$Num1.','.$sn1.'<br>';
			$Num=PHPExcel_Cell::stringFromColumnIndex($Num+4);
			if($sn1<>'')
			{
				$Num=PHPExcel_Cell::columnIndexFromString(substr($sn1,0,1))-1;//A->1
				$Num1=preg_replace('/[^\d]/','',$sn1);
				//echo $Num.','.$Num1;break;
				for($i=$Num1;$i<=$highestRow;$i++)
				{
					$val=$sheet->getCellByColumnAndRow($Num,$i)->getValue();
					$val1=$sheet->getCellByColumnAndRow(($Num+1),$i)->getValue();
					$spec=$sheet->getCellByColumnAndRow(($Num+2),$i)->getValue();
					$unit=$sheet->getCellByColumnAndRow(($Num+3),$i)->getValue();
					$val=trim($val);
					if($val==''){continue;}
					$_SESSION['TAG_NO'][$I]=$val.','.$val1.','.$spec.','.$unit;
					$I++;
					//echo '第二行:'.$I.':'.$val.'<br>';
				}
			}
			for($i=0;$i<(count($_SESSION['TAG_NO'])-1);$i++){
			$A=explode(",",$_SESSION['TAG_NO'][$i]);
			$query1="select * from UTT_TAGNO_DATA where TAG_NO='".$A[0]."'";
			$result1=mssql_query($query1);
			$Numrows1=mssql_num_rows($result1);
			if($Numrows1=='')
			{
				echo "直".trim($row['Form']).','.mb_convert_encoding($_SESSION['TAG_NO'][$i],"big5","utf-8").'<br>';	
			}
			//echo $query1.'<br>';
			}
		}
		else
		{
			$highColumn=PHPExcel_Cell::columnIndexFromString($highestColumn);
			$Num=PHPExcel_Cell::columnIndexFromString(substr($sn,0,1))-1; //A->1
			$Num1=preg_replace('/[^\d]/','',$sn);
			for($i=$Num;$i<=$highColumn;$i++)
			{
				$val=$sheet->getCellByColumnAndRow($i,$Num1)->getValue();
				//echo $val.','.$i.','.$Num1.'<br>';
				$val=trim($val);
				$_SESSION['TAG_NO'][$I]=$val;
				if($val==''){continue;}
				$EN=PHPExcel_Cell::stringFromColumnIndex($i); //0->A
				//echo '第一行:'.$I.':'.$val.'<br>';
				$I++;
			}
			if($sn1<>'')
			{
				$Num=PHPExcel_Cell::columnIndexFromString(substr($sn1,0,1))-1;//A->1
				$Num1=preg_replace('/[^\d]/','',$sn1);
				for($i=$Num;$i<=$highColumn;$i++)
				{
					$val=$sheet->getCellByColumnAndRow($i,$Num1)->getValue();
					$val=trim($val);
					$_SESSION['TAG_NO'][$I]=$val;
					if($val==''){continue;}
					//echo '第二行:'.$I.':'.$val.'<br>';
					$I++;
				}
			}
			for($i=0;$i<(count($_SESSION['TAG_NO'])-1);$i++){
			$A=explode(",",$_SESSION['TAG_NO'][$i]);
			$query2="select * from UTT_TAGNO_DATA where TAG_NO='".$A[0]."'";
			//echo $query2.'<br>';
			$result2=mssql_query($query2);
			$Numrows2=mssql_num_rows($result2);
			if($Numrows2==''){
			//echo "橫".trim($row['Form']).','.mb_convert_encoding($_SESSION['TAG_NO'][$i],"big5","utf-8").'<br>';	
			}
			//echo $query2.'<br>';
			}
		}
		unset($_SESSION['TAG_NO']);
	}
}
$query="select * from [CHEMICAL].[dbo].[UTT_FORM_DATA] WHERE [index]=".trim($_POST['form'])."";
$result=mssql_query($query);
$row=mssql_fetch_array($result);
$A=explode("/",trim($row[3]));
$B=$A[3];
$C=explode(".",$B);
$Num=$C[0];
$loginFormAction = $_SERVER['PHP_SELF'];
$_SESSION['lasturl']=$_SERVER['REQUEST_URI'];
if(isset($_POST['update']))
{	
	include("../PHPEXCEL/Classes/PHPExcel.php");
	include("../PHPEXCEL/Classes/PHPExcel/IOFactory.php");
	if($_POST['form']=='ALL'){
	$query="select * from UTT_FORM_DATA order by Form";
	}else{
	$query="select * from UTT_FORM_DATA where [index]=".$_POST['form']." order by Form";}
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result))
	{
		$I=0;
		$link=trim($row['link']);
		$sn=trim($row['sn']);
		$sn1=trim($row['sn1']);
		$way=trim($row['way']);
		$index=trim($row['index']);
		$reader = PHPExcel_IOFactory::createReader('Excel2007'); 
		$PHPExcel = $reader->load("..".$link);
		$sheet = $PHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow ();// 獲取當頁最大行數
		$highestColumn = $sheet->getHighestColumn ();// 獲取當頁最大列數 
		if($way=='A')
		{
			$Num=PHPExcel_Cell::columnIndexFromString(substr($sn,0,1))-1;//A->1
			$Num1=preg_replace('/[^\d]/','',$sn);
			for($i=$Num1;$i<=$highestRow;$i++)
			{
				$val=$sheet->getCellByColumnAndRow($Num,$i)->getValue();
				$val1=$sheet->getCellByColumnAndRow(($Num+1),$i)->getValue();
				$val3=$sheet->getCellByColumnAndRow(($Num+2),$i)->getValue();
				$val4=$sheet->getCellByColumnAndRow(($Num+3),$i)->getValue();
				$val2=mb_convert_encoding($val1,"big5","utf-8");
				$val3=mb_convert_encoding($val3,"big5","utf-8");
				$val4=mb_convert_encoding($val4,"big5","utf-8");
				$val=trim($val);
				if($val==''){continue;}
				$_SESSION[$val]='1';
				$_SESSION['TAG_NO'][$I]=$val;
				$_SESSION['TAG_NAME'][$I]=$val2;
				$_SESSION['spec'][$I]=$val3;
				$_SESSION['unit'][$I]=$val4;
				//echo '第一行:'.$I.':'.$val.'<br>';
				$I++;
			}
			//echo $Num.','.$Num1.','.$sn1.'<br>';
			$Num=PHPExcel_Cell::stringFromColumnIndex($Num+4);
			if($sn1<>'')
			{
				$Num=PHPExcel_Cell::columnIndexFromString(substr($sn1,0,1))-1;//A->1
				$Num1=preg_replace('/[^\d]/','',$sn1);
				//echo $Num.','.$Num1;break;
				for($i=$Num1;$i<=$highestRow;$i++)
				{
					$val=$sheet->getCellByColumnAndRow($Num,$i)->getValue();
					$val1=$sheet->getCellByColumnAndRow(($Num+1),$i)->getValue();
					$val3=$sheet->getCellByColumnAndRow(($Num+2),$i)->getValue();
					$val4=$sheet->getCellByColumnAndRow(($Num+3),$i)->getValue();
					$val2=mb_convert_encoding($val1,"big5","utf-8");
					$val3=mb_convert_encoding($val3,"big5","utf-8");
					$val4=mb_convert_encoding($val4,"big5","utf-8");
					$val=trim($val);
					if($val==''){continue;}
					$_SESSION[$val]='1';
					$_SESSION['TAG_NO'][$I]=$val;
					$_SESSION['TAG_NAME'][$I]=$val2;
					$_SESSION['spec'][$I]=$val3;
					$_SESSION['unit'][$I]=$val4;
					$I++;
					//echo '第二行:'.$I.':'.$val.'<br>';
				}
			}
			for($i=0;$i<=(count($_SESSION['TAG_NO'])-1);$i++){
			$compare=array(">","<","~","=","＞","＜","～","＝");
			//$compareA=">";$compareB="<";$compareC="~";$compareD="=";
			//echo $_SESSION['TAG_NO'][$i].'<br>';
			//echo sbc2Dbc($_SESSION['spec'][$i]).'<br>';
			for($X=0;$X<7;$X++)
			{
				//echo $_SESSION['TAG_NO'][$i].':'.$_SESSION['spec'][$i].'<br>';
				//echo $compare[$X].'<BR>';
				if(strstr(trim($_SESSION['spec'][$i]),$compare[$X])!=false and trim($_SESSION['spec'][$i])<>'')
				{
					//echo 'pass<br>';
					//echo $_SESSION['TAG_NO'][$i].'等於'.trim($_SESSION['spec'][$i]).'：'.$compare[$X].'<br>';
					$A=explode($compare[$X],trim($_SESSION['spec'][$i]));
					//echo $A[0].','.$A[1].'<BR>';
					if($X==0 or $X==4){$compare1="GT";$high=$A[1];$low='';}
					elseif($X==1 or $X==5){$compare1="LS";$low=$A[1];$high='';}
					elseif($X==2 or $X==6){$compare1="BT";$high=$A[1];$low=$A[0];}
					elseif($X==3 or $X==7){$compare1="SAME";$high=$A[1];$low='';}
					//echo $_SESSION['TAG_NO'][$i].','.$compare1.','.$low.','.$high.'<br>';
				}
				elseif(trim($_SESSION['spec'][$i])=='')
				{
					$high='';$low='';$compare1='REMARK';	
				}
				
			}
			if($_SESSION['TAG_NO'][$i]<>''){
			$query1="update UTT_TAGNO_DATA set form1=".$index.",project='".$_SESSION['TAG_NAME'][$i]."',unit='".$_SESSION['unit'][$i]."',spec='".$_SESSION['spec'][$i]."',high='".$high."',low='".$low."',mode='".$compare1."' where TAG_NO='".$_SESSION['TAG_NO'][$i]."'";
			$result1=mssql_query($query1);
			//echo $query1.'<br>';
			}
			}
		}
		else
		{
			$highColumn=PHPExcel_Cell::columnIndexFromString($highestColumn);
			$Num=PHPExcel_Cell::columnIndexFromString(substr($sn,0,1))-1; //A->1
			$Num1=preg_replace('/[^\d]/','',$sn);
			for($i=$Num;$i<=$highColumn;$i++)
			{
				$val=$sheet->getCellByColumnAndRow($i,$Num1)->getValue();
				//echo $val.','.$i.','.$Num1.'<br>';
				$val=trim($val);
				$_SESSION['TAG_NO'][$I]=$val;
				if($val==''){continue;}
				$EN=PHPExcel_Cell::stringFromColumnIndex($i); //0->A
				//echo '第一行:'.$I.':'.$val.'<br>';
				$I++;
			}
			if($sn1<>'')
			{
				$Num=PHPExcel_Cell::columnIndexFromString(substr($sn1,0,1))-1;//A->1
				$Num1=preg_replace('/[^\d]/','',$sn1);
				for($i=$Num;$i<=$highColumn;$i++)
				{
					$val=$sheet->getCellByColumnAndRow($i,$Num1)->getValue();
					$val=trim($val);
					$_SESSION['TAG_NO'][$I]=$val;
					if($val==''){continue;}
					//echo '第二行:'.$I.':'.$val.'<br>';
					$I++;
				}
			}
			for($i=0;$i<=(count($_SESSION['TAG_NO'])-1);$i++){
			if($_SESSION['TAG_NO'][$i]<>''){
			$query2="update UTT_TAGNO_DATA set form=".$index." where TAG_NO='".$_SESSION['TAG_NO'][$i]."'";
			$result2=mssql_query($query2);
			}
			//echo $query2.'<br>';
			}
		}
		unset($_SESSION['TAG_NO'],$_SESSION['spec']);
	}
	$query="select TAG_NO from UTT_TAGNO_DATA";
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result))
	{
		//echo trim($row[0]).','.$_SESSION[trim($row[0])].'<br>';
		if($_SESSION[trim($row[0])]!=1)
		{
			$query1="UPDATE UTT_TAGNO_DATA SET form='',form1='' where TAG_NO='".trim($row[0])."'";
			//echo $query1.'<br>';
			$result1=mssql_query($query1);
			unset($_SESSION[trim($row[0])]);
		}
	}
	fun_alert('同步完成!');
}
if(isset($_POST['search']) or isset($_POST['Title']))
{
	if($_POST['form']==''){my_msg('請選擇巡檢表');}
	$_SESSION['form']=trim($_POST['form']);
	$query="SELECT * FROM UTT_FORM_DATA WHERE [index]='".$_POST['form']."'";
	$result=mssql_query($query);
	$row=mssql_fetch_array($result);
	$link=explode("/",trim($row['link']));
	$_SESSION['linkall']=trim($row['link']);
	if(trim($row['sn1'])<>''){$sn1='<br><input type="text" name="column1" placeholder="第二欄" size="5" value="'.$row['sn1'].'">';}
	if(trim($row['way'])=='A'){$select='checked="checked"';$Title='<input type="submit" name="Title" value="Title位置輸入">';}else{$select1='checked="checked"';$Title='';}
	echo '<br><table width="1100" border="1" align="left">';
	echo '<tr bgcolor="#CCCCCC"><td>表單名稱</td><td>巡檢項目起始欄位</td><td>格式</td><td>Excel表單</td><td>更新表單</td><td>中繼標題位置'.$Title.'</td><td>儲存人</td><td>儲存</td></tr>';
	echo '<tr><td>	<input type="text" name="Form" value="'.trim($row['Form']).'"></td>
	<td><input type="text" name="column" placeholder="第一欄" size="5" value="'.trim($row['sn']).'">'.$sn1;
	echo '<span id="span1"></span> 
	<input type="button" value="+" onclick="addField()"></td>
	<td>直:<input name="way" type="radio" value="A" '.$select.' />橫:<input name="way" type="radio" value="B" '.$select1.' /></td>
	<td><a href="'.trim($row['link']).'?_'.date(Ymdhis).'">'.trim($row[2]).'</a></td>
	<td><input id="file" name="file" type="file"></td>
	<td></td>
	<td>'.getusername($row['saveuid']).'</td>
	<td><input type="submit" name="save2" value="更新"> <input type="submit" name="del" value="刪除資料"></td></tr></table>';
	echo '<br><br><br><br><font size="+1">表單起始位置解說:</font><img src="column.jpg" width="500" height="225" />';
	if(isset($_POST['Title']))
	{
		echo '<br><input type="submit" name="title" value="表格儲存" style="font-size:20px">';
		echo '<br><table width="1100" border="1" align="left">';
		echo '<tr bgcolor="#CCCCCC"><td>順序</td><td>Tagno</td><td>項目</td><td>管理值</td><td>單位</td></tr>';
		include("../PHPEXCEL/Classes/PHPExcel.php");
		include("../PHPEXCEL/Classes/PHPExcel/IOFactory.php");
		$query2="select * from UTT_FORM_DATA where [index]='".$_SESSION['form']."'";
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
		$Num=PHPExcel_Cell::columnIndexFromString(substr($sn,0,1))-1;//A->1
		$Num1=preg_replace('/[^\d]/','',$sn);$I=0;$A=0;
		for($i=$Num1;$i<=$highestRow;$i++)
		{
			$val=$sheet->getCellByColumnAndRow($Num,$i)->getValue();
			$val1=$sheet->getCellByColumnAndRow(($Num+1),$i)->getValue();
			$val2=$sheet->getCellByColumnAndRow(($Num+2),$i)->getValue();
			$val3=$sheet->getCellByColumnAndRow(($Num+3),$i)->getValue();
			$val1=mb_convert_encoding($val1,"big5","utf-8");
			$val=trim($val);
			if($val==''){continue;}
			//echo '第一行:'.$I.':'.$val.'<br>';
			if(strpos($A,'.')==true){echo '<TD>'.$A.'</TD><td colspan="4"><input type="text" name="'.$I.'"></tr>';}else{
			echo '<td>'.$A.'</td><td>'.$val.'</td><td>'.trim($val1).'</td><td>'.trim($val2).'</td><td>'.trim($val3).'</td></tr>';}
			$A=$A+0.5;
			$I++;
		}
		//echo $Num.','.$Num1.','.$sn1.'<br>';
		$Num=PHPExcel_Cell::stringFromColumnIndex($Num+4);
		if($sn1<>'')
		{
			$Num=PHPExcel_Cell::columnIndexFromString(substr($sn1,0,1))-1;//A->1
			$Num1=preg_replace('/[^\d]/','',$sn1);
			//echo $Num.','.$Num1;break;
			for($i=$Num1;$i<=$highestRow;$i++)
			{
				$val=$sheet->getCellByColumnAndRow($Num,$i)->getValue();
				$val1=$sheet->getCellByColumnAndRow(($Num+1),$i)->getValue();
				$val=trim($val);
				if($val==''){continue;}
				$_SESSION['TAG_NO'][$I]=$val;
				$_SESSION['TAG_NAME'][$I]=mb_convert_encoding($val1,"big5","utf-8");
				$I++;
				//echo '第二行:'.$I.':'.$val.'<br>';
			}
		}
	}
	
}
if(isset($_POST['save2']))
{
	if(trim($_FILES['file']['name'])<>"")
	{
		$link=explode("/",$_SESSION['linkall']);
		$path='../'.$link[1].'/'.$link[2].'/'.$Num.'.xlsx';
		$path1='../'.$_SESSION['linkall'];
		unlink($path1);
		
		
		move_uploaded_file($_FILES['file']['tmp_name'],$path);
		$link='link='."'/".$link[1].'/'.$link[2].'/'.$Num.".xlsx'".',';
	}
	else{$link='';}
	$query="update UTT_FORM_DATA SET Form='".$_POST['Form']."',".$link."sn='".strtoupper($_POST['column'])."',sn1='".strtoupper($_POST['column1'])."',way='".$_POST['way']."',savetime='".date(Ymdhis)."',saveuid='".$_SESSION['uid']."' where [index]='".$_SESSION['form']."'";
	//echo $query;
	$result=mssql_query($query);
	fun_alert('更新完畢!');
	refresh();
}
if(isset($_POST['del']))
{
	$query="DELETE FROM UTT_FORM_DATA WHERE [index] = '".$_SESSION['form']."'";
	$result=mssql_query($query);
	$_SESSION['linkall'];
	//echo $_SERVER['DOCUMENT_ROOT'].'/PORDUCT/UTT_FORM/'.$link[3];
	unlink('../'.$_SESSION['linkall']);
	fun_alert('刪除完畢!');
	refresh();
}
if(isset($_POST['add']))
{
	$query="select max(link)  from [CHEMICAL].[dbo].[UTT_FORM_DATA]";
	$result=mssql_query($query);
	$row=mssql_fetch_array($result);
	$A=explode("/",trim($row[0]));
	$B=$A[3];
	$C=explode(".",$B);
	$Num=$C[0];
	$Num++;
	$_SESSION['Num']=$Num;
	echo '<br><table width="1000" border="1" align="left">';
	echo '<tr bgcolor="#CCCCCC"><td>表單名稱*</td><td>巡檢項目起始欄位＊</td><td>格式＊</td><td>上傳表單＊</td><td>儲存</td></tr>';
	echo '<tr><td><input type="text" name="Form"></td>
	<td><input type="text" name="column" placeholder="第一欄" size="5"> 
	<span id="span1"></span> 
	<input type="button" value="+" onclick="addField()"></td>
	<td>直:<input name="way" type="radio" value="A" />橫:<input name="way" type="radio" value="B" /></td>
	<td><input id="file" name="file" type="file"></td>
	<td><input type="submit" name="save" value="儲存"></td></tr></table>';
	echo '<br><br><br><br><font size="+1">表單起始位置解說:</font><img src="column.jpg" width="500" height="225" />';
	
}
if(isset($_POST['save']))
{
	$Num=$_SESSION['Num'];
	$path="../PRODUCE/UTT_TEMP_FORM/";
	if(move_uploaded_file($_FILES['file']['tmp_name'],$path.$_FILES['file']['name'])){echo '完成!';}else{echo '失敗';}
	$_SESSION['path']="/PRODUCE/UTT_TEMP_FORM/".$_FILES['file']['name'];
	$_SESSION['path1']="/PRODUCE/UTT_FORM/".$Num.'.xlsx';
	$_SESSION['pathname']=$_FILES['file']['name'];
	$_SESSION['Form']=trim($_POST['Form']);
	$_SESSION['column']=trim($_POST['column']);
	$_SESSION['column1']=trim($_POST['column1']);
	$_SESSION['way']=trim($_POST['way']);
	$_SESSION['Filename']=$_FILES['file']['name'];
	if($_SESSION['Form']=='' or $_SESSION['way']=='' or $_SESSION['column']=='' or $_SESSION['Filename']==''){my_msg('＊'.的項目不得為空);}
	if($column1<>''){$col='<td>'.$column1.'</td>';}
	if($_SESSION['way']=='A'){$way1='直';}else{$way1='橫';}
	echo '<table width="1000" border="1" align="left">';
	echo '<tr bgcolor="#CCCCCC"><td>表單名稱*</td><td>巡檢項目起始欄位*</td><td>格式*</td><td>上傳表單*</td><td>儲存</td></tr>';
	echo '<tr><td>'.$_SESSION['Form'].'</td><td>'.$_SESSION['column'].$col.'</td><td>'.$way1.'</td><td>'.$_SESSION['Filename'].'</td><td><input type="submit" name="save1" value="確認儲存"</td>';
}
if(isset($_POST['save1']))
{
	rename($_SERVER['DOCUMENT_ROOT'].$_SESSION['path'],$_SERVER['DOCUMENT_ROOT'].$_SESSION['path1']);
	//echo $_SERVER['DOCUMENT_ROOT'].','.$_SESSION['path'].','.$_SERVER['DOCUMENT_ROOT']."/www_test/PRODUCE/UTT_FORM/".$_SESSION['path1'];
	//if($_SESSION['way']=='A'){$way='(直)';}else{$way='(橫)';}
	
$query="INSERT INTO UTT_FORM_DATA (Form, filename, link, sn, sn1, way, savetime, saveuid) values ('".$_SESSION['Form']."','".$_SESSION['pathname']."','".$_SESSION['path1']."','".strtoupper($_SESSION['column'])."','".strtoupper($_SESSION['column1'])."','".$_SESSION['way']."','".date(Ymdhis)."','".$_SESSION['uid']."')";
$result=mssql_query($query);
unset($_SESSION['Form'],$_SESSION['path1'],$_SESSION['column'],$_SESSION['column1'],$_SESSION['way'],$_SESSION['Num'],$_SESSION['path'],$_SESSION['pathname'],$_SESSION['Form'],$_SESSION['Filename']);
fun_alert('儲存完畢!');
refresh();
}
//全形字轉半形字用
function str_f2h($str){
//全形英數字及符號
$f=array("　","０","１","２","３","４","５","６","７","８","９","Ａ","Ｂ","Ｃ","Ｄ","Ｅ","Ｆ","Ｇ","Ｈ","Ｉ","Ｊ","Ｋ","Ｌ","Ｍ","Ｎ","Ｏ","Ｐ","Ｑ","Ｒ","Ｓ","Ｔ","Ｕ","Ｖ","Ｗ","Ｘ","Ｙ","Ｚ","ａ","ｂ","ｃ","ｄ","ｅ","ｆ","ｇ","ｈ","ｉ","ｊ","ｋ","ｌ","ｍ","ｎ","ｏ","ｐ","ｑ","ｒ","ｓ","ｔ","ｕ","ｖ","ｗ","ｘ","ｙ","ｚ","～","！","＠","＃","＄","％","︿","＆","＊","（","）","＿","＋","｜","‘","－","＝","＼","｛","｝","〔","〕","：","”","；","’","＜","＞","？","，","．","／");
//半形英數字及符號
$h=array(" ","0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","~","!","@","#","\$","%","^","&","*","(",")","_","+","|","`","-","=","\\\\","{","}","[","]",":","\"",";","'","<",">","?",",",".","/");//注意"\\\\"為四條
return str_replace($f,$h,$str);
}
?>
</table>
</br>