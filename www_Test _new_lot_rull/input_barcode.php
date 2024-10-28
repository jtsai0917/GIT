<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<form id="form1" name="form1" method="POST" action="">
  <p>輸入要加密的文字:
    <input type="text" name="str" id="str" />
  </p>
    <input type="submit" name="submit" id="submit" value="Submit" />
</form>
<?php
if(isset($_POST['submit'])){
	$s3=trim($_POST['str']);
	$base64encode=base64_encode($s3); 
	echo '條碼樣式 ：<img src=" ./barcode/html/image.php?filetype=PNG&dpi=72&scale=1&rotation=0&font_family=0&font_size=8&text='.$base64encode.'&thickness=30&start=NULL&code=BCGcode128" /><BR>';
	echo "<BR>編碼: ".$base64encode."<BR>";
	echo '<BR>條碼文字：'.base64_decode($base64encode).'<br>'; 
}
?>