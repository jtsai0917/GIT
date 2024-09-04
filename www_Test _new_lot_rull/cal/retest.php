<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php 
session_start();
include("../lib/fun.php");
include("../checkuser.php");
include("../lib/jtsai.php");
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
if(isset($_POST['print'])){
	$url='http://143.2.11.48/TEST/print_1.php?datepicker1='.$_POST['datepicker1'].'&datepicker2='.$_POST['datepicker2'];
	echo '<script>window.open("'.$url.'")</script>';
}
if(isset($_POST['search']) and $_POST['EMP_NO']=='0'){
	$query="SELECT DISTINCT LotNo FROM RE_TEST where LotNo <>'' ";
	if($_SESSION['datepicker1']<>''){ $query.= "and Analyzetime>='".dod(trim($_SESSION['datepicker1']))."000000' ";}
	if($_SESSION['datepicker2']<>''){ $query.= "and Analyzetime<='".dod(trim($_SESSION['datepicker2']))."235959' ";}
	if($_SESSION['pid']<>''){ $query.= "and PDD_NO='".trim($_SESSION['pid'])."' ";}
	if($_POST['selected_group']<>'0'){ $query.= "and ani_group='".trim($_POST['selected_group'])."' ";}
	$query.=" order by LotNo";
	$result=mssql_query($query);
	$numrows=mssql_num_rows($result); 
	
	
	$query="SELECT DISTINCT LotNo,PDD_NO,ani_group FROM RE_TEST where LotNo <>'' ";
	if($_SESSION['datepicker1']<>''){ $query.= "and Analyzetime>='".dod(trim($_SESSION['datepicker1']))."000000' ";}
	if($_SESSION['datepicker2']<>''){ $query.= "and Analyzetime<='".dod(trim($_SESSION['datepicker2']))."235959' ";}
	if($_SESSION['pid']<>''){ $query.= "and PDD_NO='".trim($_SESSION['pid'])."' ";}
	if($_POST['selected_group']<>'0'){ $query.= "and ani_group='".trim($_POST['selected_group'])."' ";}
	$query.=" order by LotNo";
	
	$i=1;
//	echo $query."<BR>";
	echo '<table border="1" width="1238"/>';
	echo '<tr bgcolor="#CCCCCC"><td width="5">NO</td><td width="20">Lot No</td><td width="40">產品編號</td><td width="100">產品名稱</td><td width="100">分析項目</td><td width="100">分析次數</td><td width="100">再確認次數</td><td width="100">再取樣次數</td><td width="100"></td></tr>';
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		$aa=new retest;
		$aa->LotNo=$row['LotNo'];
		$aa->ani_group=$row['ani_group'];
		$aa->ana_times();
		$aa->ana_re();
		echo '<tr><td>'.$i.'</td><td>'.$row['LotNo'].'</td><td>'.htmlspecialchars($row['PDD_NO']).'</td><td>'.get_prod_name($row['PDD_NO']).'</td><td width="100">'.$row['ani_group'].'</td><td>'.$aa->ani_times.'</td><td>'.$aa->ani_retest.'</td><td>'.$aa->ani_resample.'</td><td width=""></td></tr>';	
		$total_times=$total_times+$aa->times;
		$total_retest=$total_retest+$aa->ani_retest;
		$total_resample=$total_resample+$aa->ani_resample;	
		$i++;
	}
	echo '</table><BR>';
	echo '<table border="1" width="1238"/><tr  bgcolor="#CCCCCC"><td align="center">總 Lot 數目</td><td align="center">再確認數</td><td align="center">再取樣數</td></tr>';
	echo '<tr><td align="center">'.$numrows.'</td><td align="center">'.$total_retest.'</td><td align="center">'.$total_resample.'</td></tr>';
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
//		echo $row['PDD_NO']."<BR>";
		echo '<tr><td>'.$i.'</td><td>'.$row['LotNo'].'</td><td>'.htmlspecialchars($row['PDD_NO']).'</td><td>'.get_prod_name($row['PDD_NO']).'</td><td width="100">'.$row['ani_group'].'</td><td>'.$aa->ana_times_pri.'</td><td>'.$aa->ana_retest_pri.'</td><td>'.$aa->ana_resample_pri.'</td><td width=""></td></tr>';		
		
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
?>