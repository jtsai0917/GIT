<?php 
include("../connections/conn.php");
include("../lib/fun.php");
lasturl();
?>
<body>
<p>出荷計畫輸入 </p>
<form id="form1" name="form1" method="post" action="">
  <table width="900" height="316" border="1">
    <tr>
      <td width="462">訂貨日：
        <input name="OAF_ORDER_DATE" type="text" id="OAF_ORDER_DATE" size="14" value="<?php 
		if($_SESSION['d1']){
			echo trim($_SESSION['d1']);}
			else{
		$d=strtotime("-1 Days"); 
		echo date("m/d/Y",$d);	}
	?>" /> 
      單號：
      <label for="OPM_ORDER_NO"></label>
      <input type="text" name="OPM_ORDER_NO" id="OPM_ORDER_NO" /></td>
      <td width="322">部門代號：
        <label for="price"></label>
      <input name="depid" type="text" id="depid" size="6" />
      <input type="button" name="select_depart" id="select_depart" value="查詢" onclick="window.open('../main.php?url=select_depart ', '_self');" />
      <input name="dep_name" type="text" id="dep_name" size="12" /></td>
    </tr>
    <tr>
      <td>銷帳客戶：<span class="d1">
        <input type="button" name="X" id="X" value="X" onclick="window.open('../erase_customer.php ', '_self');" />
        <input name="cid" type="text" id="cid" size="16" value="<?php echo $_SESSION['cust_no'];?>" readonly="readonly" />
        <input type="button" name="pdd_no" id="pdd_no" value="查詢" onclick="window.open('../cust_no.php?sup=N ', '_self');" />
        <input name="pdd_chemical" type="text" id="pdd_chemical2" size="20" value="<?php echo $_SESSION['cust_name'];?>" />
      </span></td>
      <td>單價：
        <label for="price"></label>
      <input name="price" type="text" id="price" size="10" /></td></tr>
      <tr>
        <td>送貨客戶：<span class="d1">
          <input type="button" name="X2" id="X2" value="X" onclick="window.open('../erase_customer.php ', '_self');" />
        <input name="cid2" type="text" id="cid2" size="16" value="<?php echo $_SESSION['cust_no2'];?>" readonly="readonly" />
          <input type="button" name="pdd_no2" id="pdd_no2" value="查詢" onclick="window.open('../cust_no.php?sup=N&serial_id=cust_no2 ', '_self');" />
          <input name="pdd_chemical2" type="text" id="pdd_chemical" size="20" value="<?php echo get_cust_name($_SESSION['cust_no2']);?>" />
        </span></td>
        <td>單位：
          <label for="unit"></label>
          <select name="unit" id="unit">
          	<option  selected="selected"></option>
            <option value="KG">KG</option>
            <option value="L">L</option>
        </select>
          匯率：
          <label for="exchange_rate"></label>
          <input name="exchange_rate" type="text" id="exchange_rate" size="10" /></td></tr>
      <tr>
        <td>客戶訂單編號：
          <label for="cust_order_no"></label>
    <input type="text" name="cust_order_no" id="cust_order_no" />
    幣別：
    <label for="monetary"></label>
    <input name="monetary" type="text" id="monetary" size="10" /></td>
        <td>包裝：
          <label for="package"></label>
    <input type="text" name="package" id="package" /></td></tr>
      <tr>
      <td>聯絡人：
        <label for="contact_man"></label>
        <input type="text" name="contact_man" id="contact_man" />
        電話：
        <label for="contact_tel"></label>
        <input type="text" name="contact_tel" id="contact_tel" /></td>
      <td>包裝說明：
        <input type="text" name="package2" id="package2" /></td></tr>
      <tr>
      <td>送貨地址：
        <label for="deliver_addr"></label>
        <input name="deliver_addr" type="text" id="deliver_addr" size="50" /></td>
      <td>摘要：
        <label for="excerpt"></label>
        <input type="text" name="excerpt" id="excerpt" /></td></tr>
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
          <input name="pdd_chemical3" type="text" id="pdd_chemical3" size="16" value="<?php 
		if ($_GET['pname']){
			echo $_GET['pname'];
			$_SESSION['pname']=$_GET['pname'];
		}
		elseif($_SESSION['pname']){
			echo $_SESSION['pname'];
		}
		else{
		echo '';
		}
		?>" readonly="readonly" />
    </span>
    </td>
   	<td>
    預交量：
      <label for="order_qty"></label>
      <input name="order_qty" type="text" id="order_qty" size="10" />
      預交日：<input name="datepicker2" type="text" id="datepicker2" size="14" value="<?php 
		if ($_GET['datepicker2']){
			echo $_GET['datepicker2'];
			$_SESSION['datepicker2']=$_GET['datepicker2'];
		}
		elseif($_SESSION['datepicker2']){
			echo $_SESSION['datepicker2'];
		}
		else{
		$d=strtotime("+0 Days"); echo date("m/d/Y",$d);
		}
		?>">
    </td></tr>
	<tr>
    <td height="136"><p>備註</p>
      <p>
        <label for="memo"></label>
        <textarea name="memo" id="memo" cols="60" rows="5"></textarea>
      </p>
      <p>&nbsp;</p></td>
    <td align="center"><input type="checkbox" name="tainan" id="tainan" />
    <label for="tainan"><font size="16">台南倉暫出</font>
     
    </label>
    <input type="submit" name="save" id="save" value="儲存修改" size="5"/></td></tr>
  </table>

</form>
</body>
</html>