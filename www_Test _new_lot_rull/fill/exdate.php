<?php 
session_start();
include("../lib/fun.php");
include("../connections/conn.php");
$host=$_SERVER['HTTP_HOST'];
?><head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
  <title>變更日期</title>
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

<body>
<p>變更日期</p>
<form name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <span class="d1">
  <input type="text" name="datepicker1" id="datepicker1" size="10" value="<?php $d=strtotime("-0 Days"); echo date("m/d/Y",$d);?>">
  </span>
  <input type="submit" name="submit" id="submit" value="確定/離開">
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
if(isset($_POST["submit"])){
	$rl=substr($_SESSION['lasturl'],0,-8).dod($_POST['datepicker1']);
	echo $rl;
	echo $host;
	echo $sl="http://".$host.$rl;
echo '<script>document.location.href="'.$sl.'";</script>';

}
?>