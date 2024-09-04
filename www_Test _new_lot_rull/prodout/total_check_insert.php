<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
	session_start();
	include("../connections/conn.php");
	include("../lib/fun.php");
	include("../lib/jtsai.php");
	auth('2-11',$_SESSION['aut']);
 ?>
 <form id="form1" name="form1" method="post" enctype="multipart/form-data" action="<?php echo $loginFormAction; ?>">
 <font size="+2">LORRY出荷檢量總表Excel上傳</font>
 <table border="1">
  <tr  bgcolor="#CCCCCC">
      <td width="1240">
<input id="file" name="file" type="file">
<input id="update" name="update" type="submit" value="上傳">
 
<input name="noall" type="submit" id="noall" size="5" value="取消">
<?PHP if(isset($_POST['update'])){echo '<input name="add" type="submit" id="add" size="5" value="確認送出">';} ?>
</td></tr></table>
<script type="text/javascript" src="/css/jquery.min.js"></script>
    <script type="text/javascript" src="/css/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/css3menu/GridViewScroll/gridviewScroll.min.js"></script>
    <script src="/css/datepicker-zh-TW.js"></script>
    <link href="/css3menu/GridViewScroll/GridviewScroll.css" rel="stylesheet" />
        <script type="text/javascript">
	    $(document).ready(function () {
	        gridviewScroll();
	    });
	    function gridviewScroll() {
	        gridView1 = $("#GridView1").gridviewScroll({
                width: 1420,
                height: 410,
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
if(isset($_POST['update']))
{
	$path="../prodout/tmp/";
	$name=$_FILES["file"]["name"];
	move_uploaded_file($_FILES['file']['tmp_name'],$path.$_FILES["file"]["name"]);
	require_once "../PHPEXCEL/Classes/PHPExcel.php";
	require_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
	$reader = PHPExcel_IOFactory::createReader('Excel2007'); 
	$reader->setReadDataOnly(true);
	$excel = $reader->load($path.$name);
	$sheet = $excel->getActiveSheet(0);
	$sheet = $excel->getSheet(0);
	$colString = $sheet->getHighestColumn(); //最大欄位的英文代號
	$highestColumns = PHPExcel_Cell::columnIndexFromString($colString); //最大欄位的數字編號。A=0, B=1, C=2....
	$highestRows = $sheet->getHighestRow(); //最高行數。從1開始 
	datepickmore($highestRows);
	////////先偵測第一分頁磅單序號與磅單的淨重A,B
	for($y=2;$y<=$highestRows;$y++)
	{
		$_SESSION['data'][$y]=$sheet->getCellByColumnAndRow(0,$y)->getValue();
		$_SESSION['dataA'][$y]=$sheet->getCellByColumnAndRow(6,$y)->getValue();
		$_SESSION['dataB'][$y]=$sheet->getCellByColumnAndRow(7,$y)->getValue();
	}
	$sheet = $excel->getActiveSheet(1);
	$sheet = $excel->getSheet(1);
	$colString = $sheet->getHighestColumn(); //最大欄位的英文代號
	$highestColumns = PHPExcel_Cell::columnIndexFromString($colString); //最大欄位的數字編號。A=0, B=1, C=2....f
	$highestRows = $sheet->getHighestRow(); //最高行數。從1開始 
	echo '<table id="GridView1"  width="" border="1" align="left">';
	echo '<class="GridviewScrollHeader" >';
	//echo $highestRows.','.$highestColumns.','.$colString;
	///////導出第二分頁資料與第一頁的資料去比對
	for($y=1;$y<=$highestRows;$y++)
	{
		$sheet = $excel->getSheet(1);
		$lot_no=trim($sheet->getCellByColumnAndRow(6,$y)->getValue());
		if($lot_no<>''){echo '<tr class="GridviewScrollItem"  style="font-size:15px">';}
		for($x=1;$x<$highestColumns;$x++)
		{
			$value=$sheet->getCellByColumnAndRow($x,$y)->getValue();
			if($y>1 and $x==2){
			$value=date('Y/m/d',\PHPExcel_Shared_Date::ExcelToPHP($sheet->getCellByColumnAndRow(2,$y)->getValue()));
			$_SESSION['date'][$y]=$value;
			}
			if($y>1)
			{
				$_SESSION['time1'][$y] = $sheet->getCellByColumnAndRow(7,$y)->getValue();
				$_SESSION['time2'][$y] = $sheet->getCellByColumnAndRow(8,$y)->getValue();
				$_SESSION['date1'][$y]=$_SESSION['date'][$y];
			}
			
			$value1=mb_convert_encoding(trim($value),"big5","utf-8");
//			$lot_no=trim($sheet->getCellByColumnAndRow(6,$y)->getValue());
			if(substr($value1,0,1)=='=' and $x<11){$value1=$sheet->getCellByColumnAndRow($x,$y)->getCalculatedValue();}
			if($x>=11 and $y>1)
			{
				if($x==11){$ENG=6;}else{$ENG=7;}
				$sheet = $excel->getSheet(0);
				$value1=$sheet->getCellByColumnAndRow($ENG,$y)->getCalculatedValue();
			}
			if($lot_no==''){break;}
			if($x==7 and $y>1)
			{
				$sheet = $excel->getSheet(1);
				$pound=$sheet->getCellByColumnAndRow(1,$y)->getValue();
				$lot_no=$sheet->getCellByColumnAndRow(10,$y)->getCalculatedValue();
				for($X=2;$X<=(count($_SESSION['data'])+1);$X++)
				{
					//echo (count($_SESSION['data'])+1).','.$X.','.$pound.','.$_SESSION['time1'][$X].'<br>';
					if($pound==$_SESSION['data'][$X])
					{
						$_SESSION['pound']=$pound;
						$value1=$_SESSION['time1'][$y];
						echo '<td>'.'<input name="time'.$y.'_7" style="background-color:#C9FFC9" type="text"  size="1" value="'.substr($value1,0,2).'"/>:<input name="min'.$y.'_7" style="background-color:#C9FFC9" type="text"  size="1" value="'.substr($value1,3,2).'"/>'.'</td>';	
					}
				}
			}
			elseif($x==8 and $y>1)
			{
				$sheet = $excel->getSheet(1);
				$pound=$sheet->getCellByColumnAndRow(1,$y)->getValue();
				$lot_no=$sheet->getCellByColumnAndRow(10,$y)->getCalculatedValue();
				for($X=2;$X<=(count($_SESSION['data'])+1);$X++)
				{
					//echo (count($_SESSION['data'])+1).','.$X.','.$pound.','.$_SESSION['data'][$X].'<br>';
					if($pound==$_SESSION['data'][$X])
					{
						$_SESSION['pound']=$pound;
						$value1=$_SESSION['time2'][$y];
						echo '<td>'.'<input name="time'.$y.'_8" style="background-color:#C9FFC9" type="text"  size="1" value="'.substr($value1,0,2).'"/>:<input name="min'.$y.'_8" style="background-color:#C9FFC9" type="text"  size="1" value="'.substr($value1,3,2).'"/>'.'</td>';	
					}
				}
			}
			elseif($x==11 and $y>1)
			{
				$sheet = $excel->getSheet(1);
				$pound=$sheet->getCellByColumnAndRow(1,$y)->getValue();
				$lot_no=$sheet->getCellByColumnAndRow(10,$y)->getCalculatedValue();
				for($X=2;$X<=(count($_SESSION['data'])+1);$X++)
				{
					//echo (count($_SESSION['data'])+1).','.$X.','.$pound.','.$_SESSION['data'][$X].'<br>';
					if($pound==$_SESSION['data'][$X])
					{
						$_SESSION['pound']=$pound;
						$value1=$_SESSION['dataA'][$X];
						$_SESSION['lot_no'][$y]=$lot_no;
						echo '<td>'.'<input name="data'.$y.'_11" style="background-color:#C9FFC9" type="text"  size="2" value="'.$value1.'"/>'.'</td>';	
					}
				}
			}
			elseif($x==12 and $y>1)
			{
				$sheet = $excel->getSheet(1);
				$pound=$sheet->getCellByColumnAndRow(1,$y)->getValue();
				$lot_no=$sheet->getCellByColumnAndRow(10,$y)->getCalculatedValue();
				for($X=2;$X<=(count($_SESSION['data'])+1);$X++)
				{
					//echo (count($_SESSION['data'])+1).','.$X.','.$pound.','.$_SESSION['data'][$X].'<br>';
					if($pound==$_SESSION['data'][$X])
					{
						$_SESSION['pound']=$pound;
						$value2=$_SESSION['dataB'][$X];
						$_SESSION['lot_no'][$y]=$lot_no;
						echo '<td>'.'<input name="data'.$y.'_12" style="background-color:#C9FFC9" type="text"  size="2" value="'.$value2.'"/>'.'</td>';	
					}
				}
			}
			elseif($x==13 and $y>1)
			{
				$sheet = $excel->getSheet(1);
				$pound=$sheet->getCellByColumnAndRow(1,$y)->getValue();
				$lot_no=$sheet->getCellByColumnAndRow(10,$y)->getCalculatedValue();
				for($X=2;$X<=(count($_SESSION['data'])+1);$X++)
				{
					//echo (count($_SESSION['data'])+1).','.$X.','.$pound.','.$_SESSION['data'][$X].'<br>';
					if($pound==$_SESSION['data'][$X])
					{
						$_SESSION['pound']=$pound;
						$_SESSION['lot_no'][$y]=$lot_no;
						$value1=$_SESSION['date1'][$y];
						echo '<td><input name="date'.$y.'_13" id="datepicker'.$y.'"  style="background-color:#C9FFC9" type="text"  size="8" value="'.$value1.'"/></td>';	
					}
				}
			}
			elseif($x==14 and $y>1)
			{
				$sheet = $excel->getSheet(1);
				$pound=$sheet->getCellByColumnAndRow(1,$y)->getValue();
				$lot_no=$sheet->getCellByColumnAndRow(10,$y)->getCalculatedValue();
				for($X=2;$X<=(count($_SESSION['data'])+1);$X++)
				{
					//echo (count($_SESSION['data'])+1).','.$X.','.$pound.','.$_SESSION['data'][$X].'<br>';
					if($pound==$_SESSION['data'][$X])
					{
						$_SESSION['pound']=$pound;
						$_SESSION['lot_no'][$y]=$lot_no;
						$value1=$_SESSION['date1'][$y];
						echo '<td><input name="date'.$y.'_14" id="datepicker-'.$y.'"  style="background-color:#C9FFC9" type="text"  size="8" value="'.$value1.'"/></td>';	
					}
				}
			}
			else{
			echo '<td>'.$value1.'</td>';}
		}
		$_SESSION['Y']=$y;
	}
}
if(isset($_POST['add']))
{
	for($x=0;$x<$_SESSION['Y'];$x++)
	{
		$time1=$_POST['time'.($x+2).'_'.'7'].$_POST['min'.($x+2).'_'.'7'];  ///開始時間
		$time2=$_POST['time'.($x+2).'_'.'8'].$_POST['min'.($x+2).'_'.'8'];  ///結束時間
		$value1=$_POST['data'.($x+2).'_'.'11'];   ////淨重A
		$value2=$_POST['data'.($x+2).'_'.'12'];	  ////淨重B
		$date1=$_POST['date'.($x+2).'_'.'13'];	  ////出荷時間
		$date2=$_POST['date'.($x+2).'_'.'14'];	  ////回廠時間
		//echo $_SESSION['POUND1'][($x+2)].','.$value1.','.$value2.'<br>';
		$query="select top 1 * from LORRY_EXAMINE_LIST       where lel_lot_no like '%".$_SESSION['lot_no'][($x+2)]."%'  ORDER BY PRA_OUT_COUNT DESC";
		//echo $query.'<br>';
		$result = mssql_query($query);
		$row = mssql_fetch_array($result);
		$PRA=trim($row['PRA_OUT_COUNT']);
		if($_SESSION['lot_no'][($x+2)]!='' and $value1!='' and $value2!=''){
		/////更新假出庫量與殘量
		$query="update LORRY_EXAMINE_LIST set LEL_FAKE_QTY='".$value1."',LEL_REMNANT_QTY='".$value2."' where LEL_LOT_NO='".$_SESSION['lot_no'][($x+2)]."' and PRA_OUT_COUNT='".$PRA."'";
		//echo $query.'<br>';
		$result = mssql_query($query);
		/*
		$time1=$_SESSION['time1'][$x+2];
		$time1=substr($time1,0,2).substr($time1,3,2);
		$time2=$_SESSION['time2'][$x+2];
		$time2=substr($time2,0,2).substr($time2,3,2);
		*/
		////更新出廠時間
		$query="UPDATE          FILLPLAN_OUT_DECIDE
			SET                   FDM_REAL_OUT_TIME = '".des($date1).$time1."00'
			WHERE          (FDM_LOT_NO = '".$_SESSION['lot_no'][($x+2)]."')";
			//echo $query.'<br>';
		$result = mssql_query($query);
		
		////更新回廠時間
		$query="UPDATE          FILLPLAN_OUT_DECIDE
			SET                   FDM_REAL_RETURN_TIME = '".des($date2).$time2."00'
			WHERE          (FDM_LOT_NO = '".$_SESSION['lot_no'][($x+2)]."')";
			//echo $query.'<br><BR>';
		$result = mssql_query($query);
		}
	}
	echo $_SESSION['Y'].'筆匯入成功!';
}
function datepickmore($a)
{
	
echo '
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
  <link rel="stylesheet" href="/css/jquery-ui.css">
  <link rel="stylesheet" href="/css/style.css">
  <script src="/css/datepicker-zh-TW.js"></script>
  <script type="text/javascript">
    $(function() {';
	for($i=2;$i<=$a;$i++)
	{
	$A='$( "#datepicker'.$i.'" ).datepicker({
      changeMonth: true,
      changeYear: true,
	  altFormat : "yy/mm/dd",
      dateFormat : "yy/mm/dd"
    });';
	$B='$( "#datepicker-'.$i.'" ).datepicker({
      changeMonth: true,
      changeYear: true,
	  altFormat : "yy/mm/dd",
      dateFormat : "yy/mm/dd"
    });';
	echo $A;
	echo $B;
	}
echo  '  });
  function set_date_session(nam,val){
	window.open("../backend.php?name="+nam+"&value="+val)  
	window.location.href="'.$_SESSION['urln1'].'";
  }
</script>
</head>';
}
?>