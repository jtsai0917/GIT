<?php 
session_start();
include("../lib/fun.php");
include("../checkuser.php");
lasturl();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>setup_lorry</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="1240" border="1">
    <tr>
      <td width="1240">新增規則</td>
    </tr>
    <tr>
      <td>客戶：
          <input type="button" name="X3" id="X3" value="X" onclick="window.open('../erase_customer.php ', '_self');" />
          <input name="pdd_chemical3" type="text" id="pdd_chemical3" size="5" value="<?php echo $_SESSION['cust_no'];?>" readonly="readonly" />
          <input type="button" name="pdd_no2" id="pdd_no2" value="查詢" onclick="window.open('../cust_no.php?sup=N ', '_self');" />
          <input name="pdd_chemical4" type="text" id="pdd_chemical4" size="16" value="<?php echo $_SESSION['cust_name'];?>" />
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      品名：
      <input type="button" name="X" id="X" value="X" onclick="window.open('../erase_prod.php ', '_self');" />
      <input name="pdd_chemical1" type="text" id="pdd_chemical1" size="5" value="<?php echo $_SESSION['pid']; ?>" readonly="readonly" />
      <input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onclick="window.open('../pdd_prod_no.php ', '_self');" />
      <input name="pdd_chemical2" type="text" id="pdd_chemical2" size="16" value="<?php echo get_prod_name($_SESSION['pid']);?>" readonly="readonly" />
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  分析：
        <input type="button" name="X2" id="X2" value="X" onclick="window.open('../erase_anyrull.php ', '_self');" />
          <input name="pdd_chemical5" type="text" id="pdd_chemical3" size="5" value="<?php echo $_SESSION['anyid'];?>" readonly="readonly" />
          <input type="button" name="pdd_no2" id="pdd_no2" value="查詢" onclick="window.open('../any_no.php', '_self');" />
          <input name="pdd_chemical6" type="text" id="pdd_chemical6" size="16" value="<?php echo $_SESSION['anyname'];?>" />
        </td></tr><tr>
	    <td>
          每月首次全檢
        <input type="checkbox" name="month_1st" id="month_1st" />
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;全部全檢
        <input type="checkbox" name="all" id="all" />
        <label for="all"></label>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;頻率：
        <label for="duration"></label>
        <input name="duration" type="text" id="duration" size="3" />
        次檢一
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="submit" name="button" id="button" value="     儲存設定     " /></td>
    </tr>
    
  </table>
  <?php echo $_SESSION['testitems'];?>
</form>
</body>
</html>