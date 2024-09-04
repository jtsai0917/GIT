<?php 
include("../checkuser.php");
include("../lib/fun.php");
session_start();
lasturl();
$filetmp=$_SESSION['filetmp'];		
?>
<!doctype html><head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
  <title></title>
  <link rel="stylesheet" href="/js/jquery-ui.css">
  <script src="/js/jquery-1.10.2.js"></script>
  <script src="/js/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script type="text/javascript">
    $(function() {
    $( "#datepicker1" ).datepicker();
	$( "#datepicker2" ).datepicker();
	$( "#datepicker3" ).datepicker();
	$( "#datepicker4" ).datepicker();
  });

var d;
function sendIt() {
 if (d) document.body.removeChild(d);
 var info = document.getElementById("pdd_chemical").value;
 d = document.createElement("script");
 d.src = "pdd_prod_no.php?info="+info;
 d.type = "text/javascript";
 document.body.appendChild(d);
}
</script>
</head>

<form name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  
  <table border="1" width="1024">
    <tr>
      <td width="60">採購日：</td>
      <td width="100"><input name="datepicker1" type="text" id="datepicker1" size="20" maxlength="20"></td>
      <td width="40">單號：</td>
      <td width="100"><input name="pono" type="text" id="pono" size="20" maxlength="20"></td>
      <td width="255"><span class="d1">入荷先：
          <input type="button" name="cust" id="cust" value="X" onclick="window.open('../erase_customer.php ', '_self');" />
          <input name="cid" type="text" id="cid" size="12" value="<?php echo $_SESSION['cust_no'];?>" readonly>
          <input type="button" name="search_cust" id="search_cust" value="查詢" onclick="window.open('../main.php?url=cust_no&sup=Y ', '_self');">
          <input name="cname" type="text" id="cname" size="16" value="<?php echo $_SESSION['cust_name'];?>" >
      </span></td>
    </tr>
    <tr> 
      <td>預交量：</td>
      <td><input name="iap_qty" type="text" id="iap_qty" size="20" maxlength="20"></td>
      <td>單位：</td>
      <td><label for="unit"></label>
        <select name="unit" id="unit">
          <option>KG</option>
          <option>L</option>
      </select></td>
      <td><span class="d1">品名：<input type="button" name="X" id="X" value="X" onclick="window.open('../erase_prod.php ', '_self');">
        <input name="pid" type="text" id="pid" size="10" value="<?php echo $_SESSION['prod_no']?>" readonly>
          <input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onclick="window.open('../main.php?url=pdd_prod_no&pdd_class=原料', '_self');" >
          <input name="pdd_chemical2" type="text" id="pdd_chemical2" size="16" value="<?php echo $_SESSION['prod_name']?>" readonly>
      </span></td>
    </tr>
    <tr> 
      <td>預交日：</td>
      <td><input name="datepicker2" type="text" id="datepicker2" size="20" maxlength="20"></td>
      <td>&nbsp;</td>
      <td><input type="submit" name="add" id="add" value="加入"></td>
      <td>摘要：
      <input name="remark" type="text" id="remark" size="50" maxlength="20"></td>
    </tr>
  </table>

  <table width="1024" border="0"><tr>
    <td>&darr;
      <input type="submit" name="select" id="select" value="選擇">
<input type="submit" name="submit1" id="submit1" value="匯至資料庫">
  </td></tr></table>

<table border="1" width="1024">
  <tr>
   	<td>Select</td>
    <td>採購日</td>
    <td>單號</td>
    <td>廠商</td>
    <td>預交量</td>
    <td>單位</td>
    <td>品名</td>
    <td>預交日</td>
    <td>摘要</td>
  </tr>
<?php
if(file_exists($filetmp)){
  $file = fopen($filetmp, "r");

	        //當檔案未執行到最後一筆，迴圈繼續執行(fgets一次抓一行)
			$y=0;
	        while (!feof($file)) {
	            $str = fgets($file);
				$aa=explode(';',$str);
				for($i=0;$i<count($aa);$i++){
					$se[$y][$i]=$aa[$i];
					}
				if($y>=0){
				echo '<tr>';
	  			echo '<td><input type="checkbox" name="selt[]" value="'.($y+1).'"  />'.($y+1).'</td>';
	  			echo '<td><input name="pst'.$y.'0" type="hidden" id="pst'.$y.'0"  value="'.$se[$y][0].'">'.$se[$y][0].'</td>';
				echo '<td><input name="pst'.$y.'1" type="hidden" id="pst'.$y.'1"  value="'.$se[$y][1].'">'.$se[$y][1].'</td>';
	  			echo '<td><input name="pst'.$y.'2" type="hidden" id="pst'.$y.'2"  value="'.$se[$y][2].'">'.$se[$y][2].'</td>';
	  			echo '<td><input name="pst'.$y.'3" type="hidden" id="pst'.$y.'3"  value="'.$se[$y][3].'">'.$se[$y][3].'</td>';
	  			echo '<td><input name="pst'.$y.'4" type="hidden" id="pst'.$y.'4"  value="'.$se[$y][4].'">'.$se[$y][4].'</td>';
	  			echo '<td><input name="pst'.$y.'5" type="hidden" id="pst'.$y.'5"  value="'.$se[$y][5].'">'.$se[$y][5].'</td>';
	  			echo '<td><input name="pst'.$y.'6" type="hidden" id="pst'.$y.'6"  value="'.$se[$y][6].'">'.$se[$y][6].'</td>';
	  			echo '<td><input name="pst'.$y.'7" type="hidden" id="pst'.$y.'7"  value="'.$se[$y][7].'">'.$se[$y][7].'</td>';
	  			echo '</tr>';
				}
				$y=$y+1;
	        }
			fclose($file);
}
			?>
</table></form>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
if(isset($_POST["add"]))
{
	$file=fopen($filetmp,"a+");
	$content=$_POST['datepicker1'].";".$_POST['pono'].";".$_POST['cid'].";".$_POST['iap_qty'].";".$_POST['unit'].";".$_POST['pid'].";".
	$_POST['datepicker2'].";".$_POST['remark']."\r\n" ;
	fwrite($file,$content);	
	fclose($file);
	$ulink=$_SERVER['REQUEST_URI'];
	echo '<script>document.location.href="'.$ulink.'";</script>'; 
}


if(isset($_POST["select"]))
	{
		$file=fopen($filetmp,"w+");
		$target=$_POST["selt"];
    	$num=count($target);
		if ($num>0){
		for ($i=0; $i<$num; $i++){
			$content.=$_POST['pst'.$i.'0'].";".$_POST['pst'.$i.'1'].";".$_POST['pst'.$i.'2'].";".$_POST['pst'.$i.'3'].";".
			$_POST['pst'.$i.'4'].";".$_POST['pst'.$i.'5'].";".$_POST['pst'.$i.'6'].";".$_POST['pst'.$i.'7']."\r\n"			;
		}}
		fwrite($file,$content);
		fclose($file);
		$ulink=$_SERVER['REQUEST_URI'];
	echo '<script>document.location.href="'.$ulink.'";</script>';		
	}
?>