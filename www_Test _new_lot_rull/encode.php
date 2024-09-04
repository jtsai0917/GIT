<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<form id="form1" name="form1" method="POST" action="">
  <p>輸入要加密的文字:
    <input type="text" name="str" id="str" />
  </p>
    <input type="submit" name="submit" id="submit" value="Submit" />
</form>
<?php
if(isset($_POST['submit'])){
	$str=strtoupper($_POST['str']);
	echo "加密: ".$s3=trim($str)."<BR>";
	$base64encode=base64_encode($s3);// base64_encode() 加密
	echo '條碼樣式：  <img src="./barcode/test_1D.php?font=0&text='.$base64encode.'" /><BR>';
}
?>