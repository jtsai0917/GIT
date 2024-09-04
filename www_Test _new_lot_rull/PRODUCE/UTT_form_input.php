<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php 
session_start();
include("../lib/fun.php");
include("../checkuser.php");
include("../lib/jtsai.php");
include("../connections/conn.php");
lasturl();
datepick(); 
?>
<form name="form1" method="post" enctype="multipart/form-data" action="<?php echo $loginFormAction; ?>">
  <label for="textfield"></label>
   巡檢表單：<span class="d1">
		<select name="form" onchange="set_date_session(this.name,this.value)" >
        <?php
		$query="select * from UTT_FORM_DATA ORDER BY Form";
		$result=mssql_query($query);
		if($_SESSION['uid']=='E903' or $_SESSION['uid']=='E319' or $_SESSION['uid']=='E1263' or $_SESSION['uid']=='E1048'){
		if($_SESSION['form']=='ALL'){$show1=' selected ';$show2=$show3=$show4='';}
		elseif($_SESSION['form']=='ALL_'){$show2=' selected ';$show1=$show3=$show4='';}
		elseif($_SESSION['form']=='ST'){$show3=' selected ';$show2=$show1=$show4='';}
		elseif($_SESSION['form']=='ST_'){$show4=' selected ';$show2=$show3=$show1='';}
		echo '<option value="ALL"'.$show1.'>ALL</option>';
		echo '<option value="ALL_"'.$show2.'>ALL_</option>';
		echo '<option value="ST"'.$show3.'>ST</option>';
		echo '<option value="ST_"'.$show4.'>ST_</option>';
		}
		while($row=mssql_fetch_array($result)){
		if(trim($row['index'])==$_SESSION['form']){$show1=' selected ';}else{$show1='';}
		echo '<option value="'.trim($row['index']).'"'.$show1.'>'.trim($row['Form']).'</option>';
		}
		?>
        </select>
  		<input type="submit" name="search" value="巡檢表資料" />
        <input type="submit" name="add" value="新增巡檢表" />
        <?php
        if($_SESSION['uid']=='E903' or $_SESSION['uid']=='E319' or $_SESSION['uid']=='E1263' or $_SESSION['uid']=='E1048'){
		echo '<input type="submit" name="update" value="表單TAG_NO與系統TAG_NO同步" />';}
		?>
        <?php
        if($_SESSION['uid']=='E903'){
		echo '<input type="submit" name="search1" value="查詢表單TAG_NO與系統TAG_NO是否缺失" />';}
		?>
</form>

    <script> 
	////onclick增加欄位 
	var count = 1;  
	var countMax = 2;  
	function addField() {  
	if(count >= countMax )  
	        alert("最多"+countMax+"個欄位");  
	    else      
	        document.getElementById("span1").innerHTML = document.getElementById("span1").innerHTML + '<br><input type="text" size="5" placeholder="第二欄" name="column1' + '">';  
			(++count)  
	}  
	</script>  
    
<?php
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
			$sheet->disconnectWorksheets();
			unset($sheet);
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
	/*
	$aa = [];
				$queryx="SELECT  ltrim(rtrim(TAG_NO)) as SS FROM UTT_TAGNO_DATA WHERE (form1 = ".trim($_POST['form']).")" ;
			echo $queryx."<BR>";
			$resultx=mssql_query($queryx);
			while($rowx=mssql_fetch_array($resultx)){
				echo $rowx[0]."<BR>";
			}
	*/	
echo"<BR>";
	if($_POST['form']=='ALL'){
		$query="select * from UTT_FORM_DATA ORDER BY Form ";
	}elseif($_POST['form']=='ALL_'){
		$query="select * from UTT_FORM_DATA ORDER BY Form desc";
	}elseif($_POST['form']=='ST'){
		$query="select * from UTT_FORM_DATA where way='A' ORDER BY Form ";
	}elseif($_POST['form']=='ST_'){
		$query="select * from UTT_FORM_DATA where way='A' ORDER BY Form desc";
	}else{
		$query="select * from UTT_FORM_DATA where [index]=".$_POST['form']." order by Form";
	}
	$P=1;
	$result=mssql_query($query);
	while($row=mssql_fetch_row($result))
	{
		include_once("../PHPEXCEL/Classes/PHPExcel.php");
		include_once("../PHPEXCEL/Classes/PHPExcel/IOFactory.php");
		$I=0;
		$form_name=trim($row[1]);
		$link=trim($row[3]);
		$sn=trim($row[4]);
		$sn1=trim($row[5]);
		$way=trim($row[6]);
		$index=trim($row[0]);
		$reader= PHPExcel_IOFactory::createReaderForFile("..".$link);
		$reader->setReadDataOnly(true);
		$excel= $reader->load("..".$link);
		$sheet = $excel->getSheet(0);
		$highestRow = $sheet->getHighestRow ();// 獲取當頁最大行數
		$highestColumn = $sheet->getHighestColumn ();// 獲取當頁最大列數 
		
		deleteold($index);
		if($way=='A'){$style="直式";$bb="form1";}else{$style="橫式";$bb="form";}
		echo '<font color="red">表格：'.$P.'</font>'.Trim($row[1]).'   <font color="red">Form ID：</font>'.$index."<BR>";
		$P++;
//		$queryx="UPDATE UTT_TAGNO_DATA SET ".$bb." = '' WHERE ".$bb."='".$index."'";
//		$rrx=mssql_query($queryx);
		if($way=='A')
		{
			$qclear="";
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
			//	$_SESSION['TAG_NO'][$I]=$val;
				$tagno[$I]=$val;
			//	$_SESSION['TAG_NAME'][$I]=$val2;
				$tagname[$I]=$val2;
			//	$_SESSION['spec'][$I]=$val3;
				$spec[$I]=$val3;
			//	$_SESSION['unit'][$I]=$val4;
				$unit[$I]=$val4;
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
					//	$_SESSION['TAG_NO'][$I]=$val;
						$tagno[$I]=$val;
					//	$_SESSION['TAG_NAME'][$I]=$val2;
						$tagname[$I]=$val2;
					//	$_SESSION['spec'][$I]=$val3;
						$spec[$I]=$val3;
					//	$_SESSION['unit'][$I]=$val4;
						$unit[$I]=$val4;
							$I++;
					//echo '第二行:'.$I.':'.$val.'<br>';
				}
			}
		//	echo "<BR>";
			for($i=0;$i< (count($tagno));$i++){
			$compare=array(">","<","~","=","＞","＜","～","＝");
			
			//$compareA=">";$compareB="<";$compareC="~";$compareD="=";
			//echo $_SESSION['TAG_NO'][$i].'<br>';
			//echo sbc2Dbc($_SESSION['spec'][$i]).'<br>';
			
			for($X=0;$X<7;$X++)
			{
				//echo $_SESSION['TAG_NO'][$i].':'.$_SESSION['spec'][$i].'<br>';

				if(strstr(trim($spec[$i]),$compare[$X])!=false and trim($spec[$i])<>'')
				{
					//echo 'pass<br>';

					$A=explode($compare[$X],trim($spec[$i]));
					//echo $A[0].','.$A[1].'<BR>';
					if($X==0 or $X==4){$compare1="GT";$high=$A[1];$low='';}
					elseif($X==1 or $X==5){$compare1="LS";$low=$A[1];$high='';}
					elseif($X==2 or $X==6){$compare1="BT";$high=$A[1];$low=$A[0];}
					elseif($X==3 or $X==7){$compare1="SAME";$high=$A[1];$low='';}
					//echo $_SESSION['TAG_NO'][$i].','.$compare1.','.$low.','.$high.'<br>';
				}
				elseif(trim($spec[$i])=='')
				{
					$high='';$low='';$compare1='REMARK';
				}
			}
			if($tagno[$i]<>''){
				$queryx="SELECT  count(TAG_NO) as SS FROM UTT_TAGNO_DATA WHERE (form1 = ".trim($index)." and TAG_NO='".$tagno[$i]."')" ;
			//	echo $queryx."<BR>";
				$resultx=mssql_query($queryx);
				$rowx=mssql_fetch_row($resultx);
				if($rowx[0]>0){
					$query1="update UTT_TAGNO_DATA set form1=".$index.",project='".$tagname[$i]."',unit='".$unit[$i]."',spec='".$spec[$i]."',high='".$high."',low='".$low."',mode='".$compare1."'  where form1=".$index." and TAG_NO='".$tagno[$i]."'" ;
				//	echo $query1."<BR>";
					$result1=mssql_query($query1);
				}
				else{
						$query1="INSERT INTO UTT_TAGNO_DATA
                            (TAG_NO, project, unit, spec, high, low, mode, form, form1, savetime, saveuid)
VALUES          (N'".$tagno[$i]."', '".$tagname[$i]."', '".$unit[$i]."', '".$spec[$i]."', '".$high."', '".$low."', '".$compare1."', N'', ".$index.", '".date("Y-m-d H:i:s")."', N'".$_SESSION['uid']."')" ;
				//		echo $query1."<BR>";
						$result1.mssql_query($query1);
			//		my_msg("工作中止，請先建立".$form_name."的巡檢項目".$tagno[$i]);
				}

			}
		//	echo "finished...";
		
			}
			unset($tagno,$tagname,$unit,$spec);
		}
		
		else  //$way=='B'
		{
			$highColumn=PHPExcel_Cell::columnIndexFromString($highestColumn);
			$Num=PHPExcel_Cell::columnIndexFromString(substr($sn,0,1))-1; //A->1
			$Num1=preg_replace('/[^\d]/','',$sn);
			for($i=$Num;$i<=$highColumn;$i++)
			{
				$val=$sheet->getCellByColumnAndRow($i,$Num1)->getValue();
				//echo $val.','.$i.','.$Num1.'<br>';
				$val=trim($val);
			//	$_SESSION['TAG_NO'][$I]=$val;
				$tagno[$I]=$val;
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
					$tagno[$I]=$val;
					if($val==''){continue;}
					//echo '第二行:'.$I.':'.$val.'<br>';
					$I++;
				}
			}
			for($i=0;$i<=(count($tagno)-1);$i++){
			if($tagno[$i]<>''){
			$query2="update UTT_TAGNO_DATA set form=".$index." where TAG_NO='".iconv("utf-8","big5",$tagno[$i])."'";
//			echo $query2."<BR>";
			$result2=mssql_query($query2);
			}
		//	echo $query2.'<br>';
			}
		}
		unset($_SESSION['TAG_NO'],$_SESSION['spec'],$reader,$excel,$sheet);
	}
	fun_alert('同步完成!');
}
if(isset($_POST['search']))
{
	if($_POST['form']==''){my_msg('請選擇巡檢表');}
	$_SESSION['form']=trim($_POST['form']);
	$query="SELECT * FROM UTT_FORM_DATA WHERE [index]='".$_POST['form']."'";
	$result=mssql_query($query);
	$row=mssql_fetch_array($result);
	$link=explode("/",trim($row['link']));
	$_SESSION['linkall']=trim($row['link']);
	if(trim($row['sn1'])<>''){$sn1='<br><input type="text" name="column1" placeholder="第二欄" size="5" value="'.$row['sn1'].'">';}
	if(trim($row['way'])=='A'){$select='checked="checked"';}else{$select1='checked="checked"';}
	echo '<br><table width="1000" border="1" align="left">';
	echo '<tr bgcolor="#CCCCCC"><td>表單名稱</td><td>巡檢項目起始欄位</td><td>格式</td><td>Excel表單</td><td>更新表單</td><td>儲存人</td><td>儲存</td></tr>';
	echo '<tr><td>	<input type="text" name="Form" value="'.trim($row['Form']).'"></td>
	<td><input type="text" name="column" placeholder="第一欄" size="5" value="'.trim($row['sn']).'">'.$sn1;
	echo '<span id="span1"></span> 
	<input type="button" value="+" onclick="addField()"></td>
	<td>直:<input name="way" type="radio" value="A" '.$select.' />橫:<input name="way" type="radio" value="B" '.$select1.' /></td>
	<td><a href="'.trim($row['link']).'?_'.date(Ymdhis).'">'.trim($row[2]).'</a></td>
	<td><input id="file" name="file" type="file"></td>
	<td>'.getusername($row['saveuid']).'</td>
	<td><input type="submit" name="save2" value="更新"> <input type="submit" name="del" value="刪除資料"></td></tr></table>';
	echo '<br><br><br><br><font size="+1">表單起始位置解說:</font><img src="column.jpg" width="500" height="225" />';
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
//	echo $query;
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
<?php
function	deleteold($index){
	$query1="DELETE FROM UTT_TAGNO_DATA WHERE (form1 = N'".$index."' or form = N'".$index."') ";
	$result.mssql_query($query1);
}
?>