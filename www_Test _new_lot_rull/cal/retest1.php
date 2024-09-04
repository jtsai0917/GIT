<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php 
session_start();
include_once("../lib/fun.php");
include_once("../checkuser.php");
include_once("../lib/jtsai.php");
lasturl();
datepick();
?>
<form name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <table width="1238" border="1">
    <tr>
      <td width="480">分析日期
        <input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php echo $_SESSION['datepicker1'] ; ?>"  onchange="set_date_session(this.name,this.value)">
        ~
        <input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php echo $_SESSION['datepicker2'] ; ?>"  onchange="set_date_session(this.name,this.value)">
        品名：<span class="d1">
        <input type="button" name="X" id="X" value="X" onClick="window.open('../erase_prod.php ', '_self');">
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
		?>" readonly>
        <input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onClick="window.open('../pdd_prod_no.php ', '_self');" >
        <input name="pdd_chemical2" type="text" id="pdd_chemical2" size="16" value="<?php echo get_prod_name($_SESSION['pid']);?>" readonly>
        檢驗項目：<?php select_ani_group();?> 
        分析師：<?php select_Analyst();?> 
         <input type="submit" name="search" id="search" value="    搜  尋   " />
         <input type="submit" name="print" id="print" value="    列  印   " />
         <input type="hidden" name="mm_insert" id="mm_insert" value="form1" />
        </td>

    </tr>
  </table>
</form>

<?php
if($_SESSION['datepicker1']==''){$_SESSION['datepicker1']=date("m/d/Y");}
if($_SESSION['datepicker2']==''){$_SESSION['datepicker2']=date("m/d/Y");}
if($_GET['page']==''){$_GET['page']=1;}
if(isset($_POST['print']) and $_POST['EMP_NO']=='0'){
	$url='http://143.2.11.48/TEST/print_1.php?datepicker1='.$_POST['datepicker1'].'&datepicker2='.$_POST['datepicker2'];
	echo '<script>window.open("'.$url.'")</script>';
}
elseif(isset($_POST['print']) and $_POST['EMP_NO']<>'0'){  // 人員統計
	echo '分析師：'.get_uname($_POST['EMP_NO'])."<BR>";
	
	include  "../PHPEXCEL/Classes/PHPExcel.php";
	include "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
	$objPHPExcel = PHPExcel_IOFactory::load("./retest.xlsx");
	$objPHPExcel->setActiveSheetIndex(0);
	
	$query="SELECT DISTINCT LotNo FROM RE_TEST where  (op = N'".$_POST['EMP_NO']."') ";
	if($_SESSION['datepicker1']<>''){ $query.= "and Analyzetime>='".dod(trim($_SESSION['datepicker1']))."000000' ";}
	if($_SESSION['datepicker2']<>''){ $query.= "and Analyzetime<='".dod(trim($_SESSION['datepicker2']))."235959' ";}
	if($_SESSION['pid']<>''){ $query.= "and PDD_NO='".trim($_SESSION['pid'])."' ";}
	if($_POST['selected_group']<>'0'){ $query.= "and ani_group='".trim($_POST['selected_group'])."' ";}
	$query.=" order by LotNo";
	$result=mssql_query($query);
	$numrows=mssql_num_rows($result); 
	
	$query="SELECT distinct LotNo,PDD_NO,ani_group FROM RE_TEST where (op = N'".$_POST['EMP_NO']."') ";
	if($_SESSION['datepicker1']<>''){ $query.= "and Analyzetime>='".dod(trim($_SESSION['datepicker1']))."000000' ";}
	if($_SESSION['datepicker2']<>''){ $query.= "and Analyzetime<='".dod(trim($_SESSION['datepicker2']))."235959' ";}
	if($_SESSION['pid']<>''){ $query.= "and PDD_NO='".trim($_SESSION['pid'])."' ";}
	if($_POST['selected_group']<>'0'){ $query.= "and ani_group='".trim($_POST['selected_group'])."' ";}
	$query.=" order by LotNo";
	$result=mssql_query($query);
	
	$i=1;
	$total_times=0;
	$total_retest=0;
	$total_resample=0;
	while($row=mssql_fetch_array($result)){
		$aa=new retest;
		$aa->LotNo=$row['LotNo'];
		$aa->ani_group=$row['ani_group'];
		$aa->ana_times();
		$aa->ana_re();
		$total_times=$total_times+$aa->times;
		$total_retest=$total_retest+$aa->ani_retest;
		$total_resample=$total_resample+$aa->ani_resample;	
		
		$objPHPExcel->getActiveSheet()->setCellValue("A".($i+1), iconv("big5","utf-8",$i));
		  $objPHPExcel->getActiveSheet()->setCellValue("B".($i+1), iconv("big5","utf-8",$aa->LotNo));
		   $objPHPExcel->getActiveSheet()->setCellValue("c".($i+1), $row['PDD_NO']);
		    $objPHPExcel->getActiveSheet()->setCellValue("D".($i+1), iconv("big5","utf-8",get_prod_name($row['PDD_NO'])));
			 $objPHPExcel->getActiveSheet()->setCellValue("E".($i+1), iconv("big5","utf-8",$row['ani_group']));
			  $objPHPExcel->getActiveSheet()->setCellValue("F".($i+1), iconv("big5","utf-8",$aa->ani_times));
			   $objPHPExcel->getActiveSheet()->setCellValue("G".($i+1), iconv("big5","utf-8",$aa->ani_retest));
			    $objPHPExcel->getActiveSheet()->setCellValue("H".($i+1), iconv("big5","utf-8",$aa->ani_resample));
		
		$i++;
	}
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
	$objWriter->save('retest_.xlsx');
	$path_root=$_SERVER['HTTP_HOST'];
	echo '<script>document.location.href="http://'.$path_root.'/cal/retest_.xlsx";</script>';
	echo "finished...";
}

if((isset($_POST['search']) and $_POST['EMP_NO']=='0') or $_GET['page']<>'NULL'){	

	$query="SELECT DISTINCT LotNo,PDD_NO,ani_group FROM RE_TEST where LotNo <>'' ";
	if($_SESSION['datepicker1']<>''){ $query.= "and Analyzetime>='".dod(trim($_SESSION['datepicker1']))."000000' ";}
	if($_SESSION['datepicker2']<>''){ $query.= "and Analyzetime<='".dod(trim($_SESSION['datepicker2']))."235959' ";}
	if($_SESSION['pid']<>''){ $query.= "and PDD_NO='".trim($_SESSION['pid'])."' ";}
	if($_POST['selected_group']<>0){ $query.= "and ani_group='".trim($_POST['selected_group'])."' ";}
	$query.=" order by LotNo";
	
	$i=1;
	$result=mssql_query($query);
	$numrow=mssql_num_rows($result);
	$page_item=800;
	$q=ceil($numrow/$page_item);
	$result=NULL;
	echo '每頁 '.$page_item.' 筆紀錄，現在第 '.$_GET['page']. ' 頁‧選擇頁面:';
	for($p=1;$p<=$q;$p++){
	echo '<a href=index.php?url=retest&page='.$p.' target="_self">'.$p.'</a>&nbsp;&nbsp;';
	}
	echo '<table border="1" width="1238"/>';
	echo '<tr bgcolor="#CCCCCC"><td width="5">NO</td><td width="20">Lot No</td><td width="40">產品編號</td><td width="100">產品名稱</td><td width="100">分析項目</td><td width="100">分析次數</td><td width="100">再確認次數</td><td width="100">再取樣次數</td><td width="100"></td></tr>';
	
	
	
	$query="SELECT DISTINCT LotNo,PDD_NO,ani_group FROM RE_TEST where LotNo <>'' ";
	if($_SESSION['datepicker1']<>''){ $query.= "and Analyzetime>='".dod(trim($_SESSION['datepicker1']))."000000' ";}
	if($_SESSION['datepicker2']<>''){ $query.= "and Analyzetime<='".dod(trim($_SESSION['datepicker2']))."235959' ";}
	if($_SESSION['pid']<>''){ $query.= "and PDD_NO='".trim($_SESSION['pid'])."' ";}
	if($_POST['selected_group']<>0){ $query.= "and ani_group='".trim($_POST['selected_group'])."' ";}
	$s=($_GET['page']-1)*800;
	$query.=" order by LotNo,PDD_NO offset ".$s." row fetch next ".$page_item." rows only ";
//	echo '<BR>'.$query.'<BR>';
	$result=mssql_query($query);

//	echo "NUM".mssql_num_rows($result);
	while($row=mssql_fetch_array($result)){
		/*
		$aa=new retest;
		$aa->LotNo=$row['LotNo'];
		$aa->ani_group=$row['ani_group'];
		$aa->ana_times();
		$aa->ana_re();
		
	$bb['LotNo'][$i]=$row['LotNo'];
	$bb['PDD_NO'][$i]=$row['PDD_NO'];
	$bb['PDD_NAME'][$i]=get_prod_name($row['PDD_NO']);
	$bb['ani_group'][$i]=$row['ani_group'];
	$bb['ANI_TIMES'][$i]=ana_times($row['LotNo'],$row['ani_group']);
	$bb['RETEST'][$i]=ana_retest1($row['LotNo'],$row['ani_group']);
	$bb['total_resample'][$i]=ana_retest2($row['LotNo'],$row['ani_group']);
	*/
		echo $i;
		echo '<tr><td>'.($s+$i).'</td><td>'.$row['LotNo'].'</td><td>'.$row['PDD_NO'].'</td><td>'.get_prod_name($row['PDD_NO']).'</td><td width="100">'.$row['ani_group'].'</td><td>'.		
		ana_times($row['LotNo'],$row['ani_group']).'</td><td>'.ana_retest1($row['LotNo'],$row['ani_group']).'</td><td>'.ana_retest2($row['LotNo'],$row['ani_group']).'</td><td width=""></td></tr>';	


		$total_times=$total_times+ana_times($row['LotNo'],$row['ani_group']);
		$total_retest=$total_retest+ana_retest1($row['LotNo'],$row['ani_group']);
		$total_resample=$total_resample+ana_retest2($row['LotNo'],$row['ani_group']);	
	

		$i++;
	}
	echo '</table><BR>';
	
}
elseif(isset($_POST['search']) and $_POST['EMP_NO']<>'0'){  // 人員統計

	$query="SELECT DISTINCT LotNo FROM RE_TEST where  (op = N'".$_POST['EMP_NO']."') ";
	if($_SESSION['datepicker1']<>''){ $query.= "and Analyzetime>='".dod(trim($_SESSION['datepicker1']))."000000' ";}
	if($_SESSION['datepicker2']<>''){ $query.= "and Analyzetime<='".dod(trim($_SESSION['datepicker2']))."235959' ";}
	if($_SESSION['pid']<>''){ $query.= "and PDD_NO='".trim($_SESSION['pid'])."' ";}
	if($_POST['selected_group']<>'0'){ $query.= "and ani_group='".trim($_POST['selected_group'])."' ";}
	$query.=" order by LotNo";
	$result=mssql_query($query);
	$numrows=mssql_num_rows($result); 
	
	$query="SELECT distinct LotNo,PDD_NO,ani_group FROM RE_TEST where (op = N'".$_POST['EMP_NO']."') ";
	if($_SESSION['datepicker1']<>''){ $query.= "and Analyzetime>='".dod(trim($_SESSION['datepicker1']))."000000' ";}
	if($_SESSION['datepicker2']<>''){ $query.= "and Analyzetime<='".dod(trim($_SESSION['datepicker2']))."235959' ";}
	if($_SESSION['pid']<>''){ $query.= "and PDD_NO='".trim($_SESSION['pid'])."' ";}
	if($_POST['selected_group']<>'0'){ $query.= "and ani_group='".trim($_POST['selected_group'])."' ";}
	$query.=" order by LotNo";
	
	$i=1;
	$total_times=0;
	$total_retest=0;
	$total_resample=0;
	echo '分析師：'.get_uname($_POST['EMP_NO']);
	
	echo '<table border="1" width="1238"/>';
	echo '<tr bgcolor="#CCCCCC"><td width="5">NO</td><td width="20">Lot No</td><td width="40">產品編號</td><td width="100">產品名稱</td><td width="100">分析項目</td><td width="100">分析次數</td><td width="100">再確認次數</td><td width="100">再取樣次數</td><td width="100"></td></tr>';
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		$aa=new retest;
		$aa->LotNo=$row['LotNo'];
		$aa->ani_group=$row['ani_group'];
		$aa->analyst=$_POST['EMP_NO'];
		$aa->ana_times_pri();
		echo '<tr><td>'.$i.'</td><td>'.$row['LotNo'].'</td><td>'.$row['PDD_NO'].'</td><td>'.get_prod_name($row['PDD_NO']).'</td><td width="100">'.$row['ani_group'].'</td><td>'.$aa->ana_times_pri.'</td><td>'.$aa->ana_retest_pri.'</td><td>'.$aa->ana_resample_pri.'</td><td width=""></td></tr>';		
		
		$total_times=$total_times+$aa->ana_times_pri;
		$total_retest=$total_retest+$aa->ana_retest_pri;
		$total_resample=$total_resample+$aa->ana_resample_pri;	
		
		$i++;
	}
	echo '</table><BR>';
	echo '<table border="1" width="1238"/><tr  bgcolor="#CCCCCC"><td align="center">總 Lot 數目</td><td align="center">再確認數</td><td align="center">再取樣數</td></tr>';
	echo '<tr><td align="center">'.$numrows.'</td><td align="center">'.$total_retest.'</td><td align="center">'.$total_resample.'</td></tr>';
}


class retest{
	public $LotNo,$type,$pretester,$preop,$tester,$op,$nanlyzetime,$prod_id,$ani_group,$ani_times,$ani_resample,$ani_retest,$analyst,$total_lots,$ana_times_pri,$ana_retest_pri,$ana_resample_pri;
	function ana_times(){
		$query="select count(LotNo) as times from RE_TEST where LotNo='".$this->LotNo."' and ani_group='".$this->ani_group."'";	
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);
		$this->ani_times=$row[0];
	}
	function ana_re(){
		$query="select count(LotNo) as times from RE_TEST where LotNo='".$this->LotNo."' and ani_group='".$this->ani_group."' and type=1";	
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);
		$this->ani_retest=$row[0];
		$query="select count(LotNo) as times from RE_TEST where LotNo='".$this->LotNo."' and ani_group='".$this->ani_group."' and type=2";	
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);
		$this->ani_resample=$row[0];		
	}
	
	
	function ana_times_pri(){
		$query="select count(LotNo) as times from RE_TEST where LotNo='".$this->LotNo."' and ani_group='".$this->ani_group."' and (op = N'".$this->analyst."') ";	
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);
		$this->ana_times_pri=$row[0];
		$query="select count(LotNo) as times from RE_TEST where LotNo='".$this->LotNo."' and ani_group='".$this->ani_group."' and type=1 and (Pre_op = N'".$this->analyst."') ";	
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);
		$this->ana_retest_pri=$row[0];
		$query="select count(LotNo) as times from RE_TEST where LotNo='".$this->LotNo."' and ani_group='".$this->ani_group."' and type=2 and (Pre_op = N'".$this->analyst."') ";	
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);
		$this->ana_resample_pri=$row[0];
	}
}

function select_Analyst(){
	echo '<select name="EMP_NO">';
	echo '<option value="0"></option>'; 
	$query="SELECT EMPLOYEE_AUTHORITY.EMP_NO, EMPLOYEE_DATA.EMP_NAME FROM EMPLOYEE_DATA INNER JOIN EMPLOYEE_AUTHORITY ON EMPLOYEE_DATA.EMP_NO = EMPLOYEE_AUTHORITY.EMP_NO WHERE (EMPLOYEE_AUTHORITY.AUT_GROUP LIKE '%1024%')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		if($row['EMP_NO']==$_SESSION['EMP_NO']){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['EMP_NO'].'" '.$select.'>'.$row['EMP_NAME'].'</option>';
	}
	echo '</select>';
}

function ana_retest1($lotno,$group){
		$query="select count(LotNo) as times from RE_TEST where LotNo='".$lotno."' and ani_group='".$group."' and type=1";	
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);
		$b=$row[0];
		$result=$row=NULL;
		unset($result);
		unset($row);
		return $b;	
	}
function ana_retest2($lotno,$group){
		$query="select count(LotNo) as times from RE_TEST where LotNo='".$lotno."' and ani_group='".$group."' and type=2";	
//		echo '<br>'.$query.'<br>';
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);
		$b=$row[0];
		$result=$row=NULL;
		unset($result);
		unset($row);
		return $b;
	}
function ana_times($lotno,$group){
		$query="select count(LotNo) as times from RE_TEST where LotNo='".$lotno."' and ani_group='".$group."'";	
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);
		$b=$row[0];
		$result=$row=NULL;
		unset($result);
		unset($row);
		return $b;
	}
?>