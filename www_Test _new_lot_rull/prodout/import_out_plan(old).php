<?php
session_start();
$_SESSION['lasturl']=$_SERVER['REQUEST_URI'];
	include("../connections/conn.php");
	include("../lib/fun.php");
	include("../lib/jtsai.php");
	auth('9-01',$_SESSION['aut']);
	lasturl();
	datepick();
?>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<form action="<?php echo $loginFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
<table border="1" width="1200" bgcolor="#CCCCCC">
    <tr><td>出荷計畫檔匯入：
<input type="file" name="file" id="file" />
<input type="submit" name="upload" id="upload" value="  確  認  " />
<input type="submit" name="import" id="import" value="  匯入全部  " />
</td></tr></table>
<BR />

<?php
$loginFormAction = $_SERVER['PHP_SELF'];
$aa='';
$t=$x=$r=0;
if(isset($_POST["upload"]))
{ 
	$path="./tmp/";
	$filename='tmp_out.txt';
	$_SESSION['fullpath']=$fullpath="../prodout/tmp/tmp_out.txt";
	if (file_exists($path)){
	
		} 
	else {
		mkdir($path);
		}
	move_uploaded_file($_FILES["file"]["tmp_name"],$path.$filename);
}	
	$myfile = fopen($_SESSION['fullpath'], "r") or die("Unable to open file!");
	$x=$y=1;
	$aa='';
			while(!feof($myfile)) 
			{
  				$str=trim(fgets($myfile)).";";
				$aa[$x]=explode(";",$str);
				$x=$x+1;
			}
			fclose($myfile);
	
echo '<table width="1240" border="1">';
echo '<tr bgcolor="#CCCCCC"><td>訂貨日</td><td>訂單號碼</td><td>送貨客戶</td><td>銷帳客戶</td><td>部門</td><td>客戶訂單</td><td>聯絡人</td><td>連絡電話</td>
	<td>送貨地址</td><td>料號</td><td>預交量</td><td>單位</td><td>單價</td><td>包裝</td><td>預交日</td><td>規格</td><td>摘要</td><td>台南倉暫出</td><td>會計ID</td><td>備註</td></tr> ';
	
	for($i=0;$i<$x;$i++){
		if(trim($aa[$i][0])<>''){
			
		if($_SESSION['i']==$i){ //取陣列值填寫入上方編輯欄位
			$bgcolor=  'bgcolor="yellow"';
			$_SESSION['OAF_ORDER_DATE']=trim($aa[$i][0]);
			$_SESSION['OPM_ORDER_NO']=trim($aa[$i][1]);
			$_SESSION['cust_no']=trim($aa[$i][2]);
			$_SESSION['cust_no2']=trim($aa[$i][4]);
			$_SESSION['depid']=trim($aa[$i][6]);
			$_SESSION['cust_order_no']=trim($aa[$i][7]);
			$_SESSION['monetary']=trim($aa[$i][8]);
			$_SESSION['exchange_rate']=trim($aa[$i][9]);
			$_SESSION['contact_man']=trim($aa[$i][10]);
			$_SESSION['contact_tel']=trim($aa[$i][11]);
			$_SESSION['deliver_addr']=trim($aa[$i][12]);
			$_SESSION['pid']=trim($aa[$i][13]);
			$_SESSION['order_qty']=trim($aa[$i][14]);
			$_SESSION['unit']=trim($aa[$i][15]);
			$_SESSION['price']=trim($aa[$i][16]);
			$_SESSION['package']=trim($aa[$i][17]);
			$_SESSION['datepicker2']=trim($aa[$i][18]);
			$_SESSION['package2']=trim($aa[$i][20]);
			$_SESSION['excerpt']=trim($aa[$i][21]);
			$_SESSION['memo']=trim($aa[$i][23]);
			$_SESSION['tainan']=trim($aa[$i][24]);
			}
		else{$bgcolor='';}
		
		echo '<tr onClick="set_date_session('."'i'".','."'".$i."'".')" '.$bgcolor.'>';
		
		echo 
		'<td>'.trim($aa[$i][0]).'</td><td>'.trim($aa[$i][1]).'</td><td>'.trim($aa[$i][2]).'</td><td>'.trim($aa[$i][4]).'</td><td>'.trim($aa[$i][6]).'</td><td>'.trim($aa[$i][7]).
		'</td><td>'.trim($aa[$i][10]).'</td><td>'.trim($aa[$i][11]).'</td><td>'.trim($aa[$i][12]).'</td><td>'.trim($aa[$i][13]).'</td><td>'.(int)trim($aa[$i][14]).'</td><td>'.trim($aa[$i][15]).
		'</td><td>'.(int)trim($aa[$i][16]).'</td><td>'.trim($aa[$i][17]).'</td><td>'.trim($aa[$i][18]).'</td><td>'.get_prod_name(trim($aa[$i][13])).'</td><td>'.trim($aa[$i][21]).'</td><td>'.trim($aa[$i][24]).
		'</td><td>'.trim($aa[$i][22]).'</td><td>'.trim($aa[$i][23]).'</td></tr>'   ;}
	}
echo '</table>';


if(isset($_POST["import"]))
{ 
	$path="./tmp/";
	$filename='tmp_out.txt';
	$fullpath="../prodout/tmp/tmp_out.txt";
	$myfile = fopen($fullpath, "r") or die("Unable to open file!");
	$x=$y=1;
			while(!feof($myfile)) 
			{
  				$str=fgets($myfile);
				$aa[$x]=explode(";",$str);
				$x=$x+1;
			}
			fclose($myfile);
	for($i=1;$i<$x;$i++)
	{
		if(trim($aa[$i][0])<>''){
			$query="INSERT INTO OUT_PLAN_FROM_FILE
                            (OPM_ORDER_NO, OAF_SERIAL_NO, OAF_ORDER_DATE, OAF_DELI_CUST_NO, OAF_DELI_CUST_NAME, 
                            OAF_ORDER_CUST_NO, OAF_ORDER_CUST_NAME, OAF_DEP_NO, OAF_CUST_ORDER_NO, OAF_MONETARY, 
                            OAF_EXCHANGE_RATE, OAF_CONTACT_MAN, OAF_CONTACT_TEL, OAF_DELI_ADDR, PDD_PROD_NO, 
                            OAF_ORDER_QTY, OAF_UNIT, OAF_PRICE, OAF_PACKAGE, OAF_ETA_DATE, OAF_PROD_NAME, 
                            OAF_PACKAGE_DESC, OAF_EXCERPT, OAF_ACC_ID, OAF_MEMO)
					VALUES          ('".trim($aa[$i][1])."',".$i.",'".trim($aa[$i][0])."','".trim($aa[$i][2])."','".trim($aa[$i][3])."','".trim($aa[$i][4])."','".trim($aa[$i][5])."','".trim($aa[$i][6]).
					"','".trim($aa[$i][7])."','".trim($aa[$i][8])."',".sprintf("%.3f",($aa[$i][9])).",'".trim($aa[$i][10])."','".trim($aa[$i][11])."','".trim($aa[$i][12])."','".trim($aa[$i][13])."','".
					sprintf("%.3f",($aa[$i][14]))."','".trim($aa[$i][15])."','".sprintf("%.3f",($aa[$i][16]))."','".trim($aa[$i][17])."','".trim($aa[$i][18])."','".trim($aa[$i][19])."','".trim($aa[$i][20]).
					"','".trim($aa[$i][21])."','".trim($aa[$i][22])."','".trim($aa[$i][23])."')";
//			echo "OUT_PLAN_FROM_FILE : ".$query."<BR><BR>";
			$result=mssql_query($query);
			if($result){echo "From_File_OK:".$i.'</br>';}
//			if($result){my_msg("新增完成");}
//echo "###".$i."### ".$query.'</br>';

		}		
	}
	for($i=1;$i<$x;$i++)
	{
		if(trim($aa[$i][0])<>''){
			$query="INSERT INTO OUT_PLAN
                            (OPM_ORDER_NO, OPM_PO_NO, OPM_CREATE_DATE, OPM_ETA_DATE, CTD_CUST_NO, 
                            OPM_PMAN1,OPM_TAICHUNG_CACHE)
					VALUES          ('".trim($aa[$i][1])."','".trim($aa[$i][7])."','".date("YmdHis")."','".date_cy_ny(trim($aa[$i][18]))."','".trim($aa[$i][4])."','".$_SESSION['uid']."','N')";
//			echo "OUT_PLAN : ".$query."<BR><BR>";
			$result=mssql_query($query);
			if($result){echo "From_Plan_OK:".$i.'</br>';}
//			if($result){my_msg("新增完成");}
//echo "###".$i."### ".$query.'</br>';

		}
		
		
	}
	for($i=1;$i<$x;$i++)
	{
		if(trim($aa[$i][0])<>''){
			$li=literkg1(trim($aa[$i][13]));
			$sas=(int)trim($aa[$i][14])/$li;
			$query="INSERT INTO OUT_PRODUCT
                            (OPM_ORDER_NO, OPD_SERIAL_NO, PDD_PROD_NO, OAF_PACKAGE, OPD_ACC_UNIT, OPD_QTY_LITER, OPD_QTY_KG, OPD_INWARD_DATE, OAF_ACC_ID)
					VALUES          ('".trim($aa[$i][1])."',".$i.",'".trim($aa[$i][13])."','".trim($aa[$i][17])."','".trim($aa[$i][15])."','".(int)trim($aa[$i][14])."','".$sas."',
					'".date("Ymd")."','".trim($aa[$i][22])."')";
//			echo "OUT_PRODUCT : ".$query."<BR><BR>";
			$result=mssql_query($query);
			if($result){echo "From_OutProduct_OK:".$i.'</br>';}
//			if($result){my_msg("新增完成");}
//echo "###".$i."### ".$query.'</br>';
		}
	}
}
?>
<BR />

<table width="900" border="1" bgcolor="#66FFFF">
  <tr>
    <td width="462">訂貨日：
      <input name="OAF_ORDER_DATE" type="text" id="OAF_ORDER_DATE" size="14" value="<?php 
		if($_SESSION['OAF_ORDER_DATE']){
			echo trim($_SESSION['OAF_ORDER_DATE']);}
	?>" />
      單號：
      <label for="OPM_ORDER_NO"></label>
      <input type="text" name="OPM_ORDER_NO" id="OPM_ORDER_NO"  value="<?php 
		if($_SESSION['OPM_ORDER_NO']){
			echo trim($_SESSION['OPM_ORDER_NO']);}
	?>" /></td>
    <td width="322">部門代號：
      <label for="price"></label>
      <input name="depid" type="text" id="depid" size="6" value="<?php 
		if($_SESSION['dep_id']){
			echo $did= trim($_SESSION['dep_id']);}
		else{
			echo $did= trim($_SESSION['depid']);}
		
	?>"/>
      <input type="button" name="select_depart" id="select_depart" value="查詢" onclick="window.open('../main.php?url=select_depart ', '_self');" />
      <input name="dep_name" type="text" id="dep_name" size="12" value="<?php echo get_dep_name(trim($did));?>"/></td>
  </tr>
  <tr>
    <td>銷帳客戶：<span class="d1">
      <input type="button" name="X" id="X" value="X" onclick="window.open('../erase_customer.php ', '_self');" />
      <input name="cid" type="text" id="cid" size="16" value="<?php echo $_SESSION['cust_no'];?>" readonly="readonly" />
      <input type="button" name="pdd_no" id="pdd_no" value="查詢" onclick="window.open('../cust_no.php?sup=N ', '_self');" />
      <input name="pdd_chemical" type="text" id="pdd_chemical2" size="20" value="<?php echo get_cust_name($_SESSION['cust_no']);?>" />
    </span></td>
    <td>單價：
      <label for="price"></label>
      <input name="price" type="text" id="price" size="10" value="<?php 
		if($_SESSION['price']){
			echo trim($_SESSION['price']);}
	?>"/></td>
  </tr>
  <tr>
    <td>送貨客戶：<span class="d1">
      <input type="button" name="X2" id="X2" value="X" onclick="window.open('../erase_customer.php ', '_self');" />
      <input name="cid2" type="text" id="cid2" size="16" value="<?php echo $_SESSION['cust_no2'];?>" readonly="readonly" />
      <input type="button" name="pdd_no2" id="pdd_no2" value="查詢" onclick="window.open('../cust_no.php?sup=N&amp;serial_id=cust_no2 ', '_self');" />
      <input name="pdd_chemical2" type="text" id="pdd_chemical" size="20" value="<?php echo get_cust_name($_SESSION['cust_no2']);?>" />
    </span></td>
    <td>單位：
      <label for="unit"></label>
      <select name="unit" id="unit">
        <option value="KG" <?php if($_SESSION['unit']=='KG'){echo 'selected="selected"';}?>>KG</option>
        <option value="L" <?php if($_SESSION['unit']=='L'){echo 'selected="selected"';}?>>L</option>
      </select>
      匯率：
      <label for="exchange_rate"></label>
      <input name="exchange_rate" type="text" id="exchange_rate" size="10" value="<?php 
		if($_SESSION['exchange_rate']){
			echo trim($_SESSION['exchange_rate']);}
	?>"/></td>
  </tr>
  <tr>
    <td>客戶訂單編號：
      <label for="cust_order_no"></label>
      <input type="text" name="cust_order_no" id="cust_order_no" value="<?php 
		if($_SESSION['cust_order_no']){
			echo trim($_SESSION['cust_order_no']);}
	?>"/>
      幣別：
      <label for="monetary"></label>
      <input name="monetary" type="text" id="monetary" size="10" value="<?php 
		if($_SESSION['monetary']){
			echo trim($_SESSION['monetary']);}
	?>"/></td>
    <td>包裝：
      <label for="package"></label>
      <input type="text" name="package" id="package" value="<?php 
		if($_SESSION['package']){
			echo trim($_SESSION['package']);}
	?>"/></td>
  </tr>
  <tr>
    <td>聯絡人：
      <label for="contact_man"></label>
      <input type="text" name="contact_man" id="contact_man" value="<?php 
		if($_SESSION['contact_man']){
			echo trim($_SESSION['contact_man']);}
	?>"/>
      電話：
      <label for="contact_tel"></label>
      <input type="text" name="contact_tel" id="contact_tel" value="<?php 
		if($_SESSION['contact_tel']){
			echo trim($_SESSION['contact_tel']);}
	?>"/></td>
    <td>包裝說明：
      <input type="text" name="package2" id="package2"  value="<?php 
		if($_SESSION['package2']){
			echo trim($_SESSION['package2']);}
	?>"/></td>
  </tr>
  <tr>
    <td>送貨地址：
      <label for="deliver_addr"></label>
      <input name="deliver_addr" type="text" id="deliver_addr" size="50" value="<?php 
		if($_SESSION['deliver_addr']){
			echo trim($_SESSION['deliver_addr']);}
	?>"/></td>
    <td>摘要：
      <label for="excerpt"></label>
      <input type="text" name="excerpt" id="excerpt" value="<?php 
		if($_SESSION['excerpt']){
			echo trim($_SESSION['excerpt']);}
	?>"/></td>
  </tr>
  <tr>
    <td>品名：<span class="d1">
      <input type="button" name="X3" id="X3" value="X" onclick="window.open('../erase_prod.php ', '_self');" />
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
      <input type="button" name="pdd_no3" id="pdd_no3" value="查詢品名" onclick="window.open('../pdd_prod_no.php ', '_self');" />
      <input name="pdd_chemical3" type="text" id="pdd_chemical3" size="16" value="<?php echo get_prod_name($_SESSION['pid']);?>" readonly="readonly" />
    </span></td>
    <td> 預交量：
      <label for="order_qty"></label>
      <input name="order_qty" type="text" id="order_qty" size="10" value="<?php echo $_SESSION['order_qty'];?>"/>
      預交日：
      <input name="datepicker2" type="text" id="datepicker2" size="14" value="<?php 
		if($_SESSION['datepicker2']){
			echo trim($_SESSION['datepicker2']);}
	?>" /></td>
  </tr>
  <tr>
    <td>備註
        <input type="text" size="60" name="memo" id="memo" value="<?php 
		if($_SESSION['memo']){
			echo trim($_SESSION['memo']);}
	?>" /></td>
    <td align="center"><input type="checkbox" name="tainan" id="tainan" <?php if($_SESSION['tainan']==1){echo 'checked';}?>/>
      <label for="tainan">台南倉暫出</label>
      <input type="submit" name="save2" id="save2" value="儲存修改" size="5"/>
</td>
  </tr>
</table>
</form>
<?php

if(isset($_POST['save2'])){
	$str='';
	$x=$x-1;
	unlink($_SESSION['fullpath']);
	$myfile = fopen($_SESSION['fullpath'],"a");
	////設定row[i]
			 $aa[$_SESSION['i']][0]=$_POST['OAF_ORDER_DATE'];
			 
			 $aa[$_SESSION['i']][1]=$_POST['OPM_ORDER_NO'];
			 
			 $aa[$_SESSION['i']][2]=$_POST['cid'];
			 
			 $aa[$_SESSION['i']][4]=$_POST['cid2'];
			 
			 $aa[$_SESSION['i']][6]=$_POST['depid'];
			 
			 $aa[$_SESSION['i']][7]=$_POST['cust_order_no'];
			 
			 $aa[$_SESSION['i']][8]=$_POST['monetary'];
			 
			 $aa[$_SESSION['i']][9]=$_POST['exchange_rate'];
			 
			 $aa[$_SESSION['i']][10]=$_POST['contact_man'];
			 
			 $aa[$_SESSION['i']][11]=$_POST['contact_tel'];
			 
			 $aa[$_SESSION['i']][12]=$_POST['deliver_addr'];
			 
			 $aa[$_SESSION['i']][13]=$_POST['pdd_chemical1'];
			 
			 $aa[$_SESSION['i']][14]=$_POST['order_qty'];
			 
			 $aa[$_SESSION['i']][15]=$_POST['unit'];
			 
			 $aa[$_SESSION['i']][16]=$_POST['price'];
			 
			 $aa[$_SESSION['i']][17]=$_POST['package'];
			 
			 $aa[$_SESSION['i']][18]=$_POST['datepicker2'];
			 
			 $aa[$_SESSION['i']][20]=$_POST['package2'];
			 
			 $aa[$_SESSION['i']][21]=$_POST['excerpt'];
			 
			 $aa[$_SESSION['i']][23]=trim($_POST['memo']);
			 
			 $aa[$_SESSION['i']][24]=$_POST['tainan'];
//			 $_SESSION['i']=$_SESSION['i']+1;


	for($t=1;$t<=$x;$t++){
		for($r=0;$r<=24;$r++){
			if($r<>24){
				$str.=$aa[$t][$r].";";}
			else{$str.=$aa[$t][$r];}
		}	
		if($t<>$x){
			$str1=trim($str)."\r\n";}
		else{$str1=trim($str);}
		//		fputs($myfile,$str);
		fputs($myfile,$str1);
		$str=$str1='';

	}
	$t=$x=$r=0;
	$aa='';
	fclose($myfile);
	refresh();
}


?>