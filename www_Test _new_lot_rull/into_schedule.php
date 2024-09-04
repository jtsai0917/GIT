<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
/*session_start();
include("../connections/conn.php");
include("../lib/jtsai.php");
include("../lib/sag_in.php");

lasturl();
datepick();
*/
session_start();
//include("../auth.php");
include("../connections/conn.php");
include("/lib/jtsai.php");
include("/lib/fun.php");
lasturl();
datepick();

?>	
		<font size="+2">基本資料上傳</font>
		<form id="form1" name="form1" method="post" enctype="multipart/form-data" action="<?php echo $loginFormAction; ?>">
<table border="1">
  <tr  bgcolor="#CCCCCC">
      <td width="1240">
      <input name="add" type="submit" id="add" size="5" value="送出">
<input id="update" name="update" type="submit" value="開始上傳">&nbsp;<input id="file" name="file" type="file">
<input name="noall" type="submit" id="noall" size="5" value="取消">
上傳格式:請合併成同一份EXCEL之後在檔名後面(加上_月份)
<?php select_month();  ?>月
<input name="print" type="submit" id="submit1" size="5" value="下載">

</form>

</td></tr></table>
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
                width: 1150,
                height: 500,
                railcolor: "#F0F0F0",
                barcolor: "#CDCDCD",
                barhovercolor: "#606060",
                bgcolor: "#F0F0F0",
                freezesize: 2,
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




<?php
if(isset($_POST['noall']))
{
	unset($_SESSION['A'],$_SESSION['B'],$_SESSION['C'],$_SESSION['D'],$_SESSION['E'],$_SESSION['F'],$_SESSION['G'],$_SESSION['H'],$_SESSION['I'],$_SESSION['J'],$_SESSION['K'],$_SESSION['L'],$_SESSION['M'],$_SESSION['N'],$_SESSION['O'],$_SESSION['P'],$_SESSION['Q'],$_SESSION['R'],$_SESSION['S'],$_SESSION['T'],$_SESSION['U'],$_SESSION['V'],$_SESSION['W'],$_SESSION['X'],$_SESSION['Y'],$_SESSION['Z'],$_SESSION['a'],$_SESSION['b'],$_SESSION['c'],$_SESSION['d'],$_SESSION['e'],$_SESSION['f'],$_SESSION['g'],$_SESSION['h'],$_SESSION['i']);

}
if($_POST['update'])
{
	$path="/var/www/HR/schedule/ELfile/";
	
	if(move_uploaded_file($_FILES['file']['tmp_name'],$path.$_FILES['file']['name'])){echo '完成!';}else{echo '失敗';}
	require_once "../PHPEXCEL/Classes/PHPExcel.php";
	require_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
	$reader = PHPExcel_IOFactory::createReader('Excel2007'); 
	$reader->setReadDataOnly(true);
	$excel = $reader->load('/var/www/HR/schedule/ELfile/'.$_FILES['file']['name'].'');
	$sheet = $excel->getActiveSheet(0);
	$sheetCount = $excel->getSheetCount();
	$sheetNames = $excel->getSheetNames();
	$colString = $sheet->getHighestColumn(); //最大欄位的英文代號
	$highestColumns = PHPExcel_Cell::columnIndexFromString($colString); //最大欄位的數字編號。A=0, B=1, C=2....
	$highestRows = $sheet->getHighestRow(); //最高行數。從1開始
    $sheet = $excel->getActiveSheet(0);   
	echo '<table id="GridView1"  width="" border="1" align="left">';
 	echo '<tr class="GridviewScrollHeader">';
	//echo '<table border="1" width="720">';
	echo '<td>'."工號".'</td>';
	echo '<td>'."姓名".'</td>';
	echo '<td>'."部門".'</td>';
	echo '<td>'."單位".'</td>';
	echo '<td>'."職稱".'</td>';
	echo '<td>'."入廠日期".'</td>';
	echo '<td>'."離職日期".'</td>';
	echo '<td>'."身分證號碼".'</td>';
	echo '<td>'."銀行帳號".'</td>';
	echo '<td>'."工具".'</td>';
	echo '<td>'."學歷".'</td>';
	echo '<td>'."婚姻".'</td>';
	echo '<td>'."子女".'</td>';
	echo '<td>'."扶養人數".'</td>';
	echo '<td>'."男".'</td>';
	echo '<td>'."女".'</td>';
	echo '<td>'."出生日期".'</td>';
	echo '<td>'."電話1".'</td>';
	echo '<td>'."電話2".'</td>';
	echo '<td>'."戶籍地址".'</td>';
	echo '<td>'."聯絡地址".'</td>';
	echo '<td>'."配偶姓名".'</td>';
	echo '<td>'."身分證號碼".'</td>';
	echo '<td>'."出生年月日".'</td>';
	echo '<td>'."子女姓名".'</td>';
	echo '<td>'."身分證號碼".'</td>';
	echo '<td>'."出生年月日".'</td>';
	echo '<td>'."子女姓名".'</td>';
	echo '<td>'."身分證號碼".'</td>';
	echo '<td>'."出生年月日".'</td>';
	echo '<td>'."子女姓名".'</td>';
	echo '<td>'."身分證號碼".'</td>';
	echo '<td>'."出生年月日".'</td>';
	echo '<td>'."父姓名".'</td>';
	echo '<td>'."身分證號碼".'</td>';
	echo '<td>'."出生年月日".'</td>';
	echo '<td>'."母姓名".'</td>';
	echo '<td>'."身分證號碼".'</td>';
	echo '<td>'."出生年月日".'</td>';
	echo '<td>'."選擇".'</td>';
	
	$x=0;
	//echo $highestRows;
	for($i=5;$i<=$highestRows;$i++)
	{
		
		$val0 = $sheet->getCellByColumnAndRow(0,$i)->getValue();
		$_SESSION['A'][$x]=$val0;
		$val1 = $sheet->getCellByColumnAndRow(1,$i)->getValue();
		$empno = mb_convert_encoding($val1,"big5","utf-8");
		
		$val2 = $sheet->getCellByColumnAndRow(2,$i)->getValue();
		$dep1 = mb_convert_encoding($val2,"big5","utf-8"); 
		
		$val3 = $sheet->getCellByColumnAndRow(3,$i)->getValue();
		$dep2 = mb_convert_encoding($val3,"big5","utf-8");		
		
		$val4 = $sheet->getCellByColumnAndRow(4,$i)->getValue();
		$duties = mb_convert_encoding($val4,"big5","utf-8");
		
		$val5 = $sheet->getCellByColumnAndRow(5,$i)->getValue();
		$_SESSION['B'][$x]=$val5;
		$val6 = $sheet->getCellByColumnAndRow(6,$i)->getValue();
		$_SESSION['C'][$x]=$val6;
		$val7 = $sheet->getCellByColumnAndRow(7,$i)->getValue();
		$_SESSION['D'][$x]=$val7;
		$val8 = $sheet->getCellByColumnAndRow(8,$i)->getValue();
		$_SESSION['E'][$x]=$val8;
		$val15 = $sheet->getCellByColumnAndRow(15,$i)->getValue();
		$cars = mb_convert_encoding($val15,"big5","utf-8");
		$_SESSION['F'][$x]=$cars;
		
		$val16 = $sheet->getCellByColumnAndRow(16,$i)->getValue();
		$college = mb_convert_encoding($val16,"big5","utf-8");
		$_SESSION['G'][$x]=$college;
		
		$val17 = $sheet->getCellByColumnAndRow(17,$i)->getValue();
		if($val17==1){$val18='Y';}else{$val17='N';}
		$_SESSION['H'][$x]=$val17;
		$val18 = $sheet->getCellByColumnAndRow(18,$i)->getValue();
		$_SESSION['I'][$x]=$val18;
		$val19 = $sheet->getCellByColumnAndRow(19,$i)->getValue();
		$_SESSION['J'][$x]=$val19;
		
		$val21 = $sheet->getCellByColumnAndRow(21,$i)->getValue();
		$val22 = $sheet->getCellByColumnAndRow(22,$i)->getValue();
		
		if($val21=='1'){$val21='男';$_SESSION['K'][$x]=$val21;}
		elseif($val22=='2'){$val22='女';$_SESSION['K'][$x]=$val22;}
		
		$val23 = $sheet->getCellByColumnAndRow(23,$i)->getValue();
		$_SESSION['M'][$x]=$val23;
		$val25 = $sheet->getCellByColumnAndRow(25,$i)->getValue();
		$_SESSION['N'][$x]=$val25;
		$val26 = $sheet->getCellByColumnAndRow(26,$i)->getValue();
		$_SESSION['O'][$x]=$val26;
		$val27 = $sheet->getCellByColumnAndRow(27,$i)->getValue();
		$bron = mb_convert_encoding($val27,"big5","utf-8");
		$_SESSION['P'][$x]=$bron;
		$val28 = $sheet->getCellByColumnAndRow(28,$i)->getValue();
		$home = mb_convert_encoding($val28,"big5","utf-8");
		$_SESSION['Q'][$x]=$home;
		
		$val29 = $sheet->getCellByColumnAndRow(29,$i)->getValue();
		$love = mb_convert_encoding($val29,"big5","utf-8");
		$_SESSION['R'][$x]=$love;
		$val30 = $sheet->getCellByColumnAndRow(30,$i)->getValue();
		$_SESSION['S'][$x]=$val30;
		$val31 = $sheet->getCellByColumnAndRow(31,$i)->getValue();
		$_SESSION['T'][$x]=$val31;
		//////////小孩1
		$val32 = $sheet->getCellByColumnAndRow(32,$i)->getValue();
		$child0 = mb_convert_encoding($val32,"big5","utf-8");
		$_SESSION['U'][$x]=$child0;
		$val33 = $sheet->getCellByColumnAndRow(33,$i)->getValue();
		$_SESSION['V'][$x]=$val33;
		$val34 = $sheet->getCellByColumnAndRow(34,$i)->getValue();
		$_SESSION['W'][$x]=$val34;
		//////////小孩2
		$val35 = $sheet->getCellByColumnAndRow(35,$i)->getValue();
		$child1 = mb_convert_encoding($val35,"big5","utf-8");
		$_SESSION['X'][$x]=$child1;
		$val36= $sheet->getCellByColumnAndRow(36,$i)->getValue();
		$_SESSION['Y'][$x]=$val36;
		$val37= $sheet->getCellByColumnAndRow(37,$i)->getValue();
		$_SESSION['Z'][$x]=$val37;
		/////////小孩3
		$val38= $sheet->getCellByColumnAndRow(38,$i)->getValue();
		$child2 = mb_convert_encoding($val38,"big5","utf-8");
		$_SESSION['a'][$x]=$child2;
		$val39= $sheet->getCellByColumnAndRow(39,$i)->getValue();
		$_SESSION['b'][$x]=$val39;
		$val40= $sheet->getCellByColumnAndRow(40,$i)->getValue();
		$_SESSION['c'][$x]=$val40;
		//////////父親
		$val41= $sheet->getCellByColumnAndRow(41,$i)->getValue();
		$father = mb_convert_encoding($val41,"big5","utf-8");
		$_SESSION['d'][$x]=$father;
		$val42= $sheet->getCellByColumnAndRow(42,$i)->getValue();
		$_SESSION['e'][$x]=$val42;
		$val43= $sheet->getCellByColumnAndRow(43,$i)->getValue();
		$_SESSION['f'][$x]=$val43;
		//////////母親
		$val44= $sheet->getCellByColumnAndRow(44,$i)->getValue();
		$mom = mb_convert_encoding($val44,"big5","utf-8");
		$_SESSION['g'][$x]=$mom;
		$val45 = $sheet->getCellByColumnAndRow(45,$i)->getValue();
		$_SESSION['h'][$x]=$val45;
		$val46= $sheet->getCellByColumnAndRow(46,$i)->getValue();
		$_SESSION['i'][$x]=$val46;
		
		echo '<tr class="GridviewScrollItem">';
		echo '<td>'.$val0.'</td>';
		echo '<td>'.$empno.'</td>';
		echo '<td>'.$dep1.'</td>';
		echo '<td>'.$dep2.'</td>';
		echo '<td>'.$duties.'</td>';
		echo '<td>'.$val5.'</td>';
		echo '<td>'.$val6.'</td>';
		echo '<td>'.$val7.'</td>';
		echo '<td>'.$val8.'</td>';
		echo '<td>'.$cars.'</td>';
		echo '<td>'.$college.'</td>';
		echo '<td>'.$val17.'</td>';
		echo '<td>'.$val18.'</td>';
		echo '<td>'.$val19.'</td>';
		echo '<td>'.$val21.'</td>';
		echo '<td>'.$val22.'</td>';
		echo '<td>'.$val23.'</td>';
		echo '<td>'.$val25.'</td>';
		echo '<td>'.$val26.'</td>';
		echo '<td>'.$bron.'</td>';
		echo '<td>'.$home.'</td>';
		echo '<td>'.$love.'</td>';
		echo '<td>'.$val30.'</td>';
		echo '<td>'.$val31.'</td>';
		echo '<td>'.$child0.'</td>';
		echo '<td>'.$val33.'</td>';
		echo '<td>'.$val34.'</td>';
		echo '<td>'.$child1.'</td>';
		echo '<td>'.$val36.'</td>';
		echo '<td>'.$val37.'</td>';
		echo '<td>'.$child2.'</td>';
		echo '<td>'.$val39.'</td>';
		echo '<td>'.$val40.'</td>';
		echo '<td>'.$father.'</td>';
		echo '<td>'.$val42.'</td>';
		echo '<td>'.$val43.'</td>';
		echo '<td>'.$mom.'</td>';
		echo '<td>'.$val45.'</td>';
		echo '<td>'.$val46.'</td>';
		echo '<td>'.'<input type="checkbox" name="check'.$x.'" value="'.$_SESSION['A'][$x].'" '.'checked="checked"'.'/>'.'</td>';
		$x++;
		$_SESSION['X']=$x;
	 /* 
	$val = $sheet->getCellByColumnAndRow(0,$i)->getValue();
	$val6 = $sheet->getCellByColumnAndRow(6,$i)->getValue();
	$query="update EMPLOYEE_DATA set duties='".$val6."' where empno='".$val."'";
	$result = mssql_query($query);
	*/

	}

	
}
if(isset($_POST['add']))
	{
		
		for($i=0;$i<=$_SESSION['X'];$i++)
		{
			
			$empno='check'.$i;
			if($_POST[$empno]!=''){
			$query="select * from EMPLOYEE_DATA where empno='".$_POST[$empno]."'";
			$result = mssql_query($query);
			$numRows=mssql_num_rows($result);
				if($numRows!='')
				{
				$query1="update EMPLOYEE_DATA set empno='".$_SESSION['A'][$i]."', duty_start='".$_SESSION['B'][$i]."', duty_end='".$_SESSION['C'][$i]."', duty_start='".$_SESSION['D'][$i]."', bank='".$_SESSION['E'][$i]."', transportation='".$_SESSION['F'][$i]."', school='".$_SESSION['G'][$i]."', merriage='".$_SESSION['H'][$i]."', child='".$_SESSION['I'][$i]."', support='".$_SESSION['J'][$i]."', gender='".$_SESSION['K'][$i]."', birthday='".$_SESSION['M'][$i]."', tel1='".$_SESSION['N'][$i]."', tel2='".$_SESSION['O'][$i]."', residence_addr='".$_SESSION['P'][$i]."', contact_addr='".$_SESSION['Q'][$i]."'";
				echo $query1.'<br>';
				$query2="select * from RELATIONSHIP where empno='".$_POST[$empno]."'";
				$numRows2=mssql_num_rows($result2);
					for($i=0;$i<$numRows;$i++)
					{
						$query="select ";
					}
				}
				else
				{
					$query="insert into ";
				}
			//echo $query.'<br>';
			}
		}
		//unset($_SESSION['A'],$_SESSION['B'],$_SESSION['C'],$_SESSION['D'],$_SESSION['E'],$_SESSION['F'],$_SESSION['G'],$_SESSION['H'],$_SESSION['I'],$_SESSION['J'],$_SESSION['K'],$_SESSION['L'],$_SESSION['M'],$_SESSION['N'],$_SESSION['O'],$_SESSION['P'],$_SESSION['Q'],$_SESSION['R'],$_SESSION['S'],$_SESSION['T'],$_SESSION['U'],$_SESSION['V'],$_SESSION['W'],$_SESSION['X'],$_SESSION['Y'],$_SESSION['Z'],$_SESSION['a'],$_SESSION['b'],$_SESSION['c'],$_SESSION['d'],$_SESSION['e'],$_SESSION['f'],$_SESSION['g'],$_SESSION['h'],$_SESSION['i']);
	}
function dep1($dep1)
	{
		$query="select * FROM DEP where [index]='".$dep1."'";
		$result = mssql_query($query);
		while($row=mssql_fetch_array($result))
		{
			$T=$row['DEP_NAME'];
		}
		return $T;
		
	}
	function dep2($dep2)
	{
		$query="select * FROM DEP where [index]='".$dep2."'";
		$result = mssql_query($query);
		while($row=mssql_fetch_array($result))
		{
			$T=$row['DEP_NAME'];
		}
		return $T;
		
	}
	function duties($duties)
	{
		$query="select * from DUTIES where [index]='".$duties."'";
		$result = mssql_query($query);
		while($row=mssql_fetch_array($result))
		{
			$T=$row['duty_name'];
		}
		return $T;
		
	}
function select_month()
	{
		session_start();
		echo '<select name="month" id="month" width="90" onchange="set_date_session(this.name,this.value)">';
	//	echo '<option value="0"></option>';
		for($i=1;$i<=12;$i++)
		{
			
				if($i==$_SESSION['month']){$show1=' selected ';}else{$show1='';}
				echo '<option value='.$i.'" '.$show1.' >'.$i.'</option>';
			
		}
		echo '</select>';
	}	
	
?>
      


