<meta http-equiv="Content-Type" content="text/html; charset=big5" />

<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
include  "../PHPEXCEL/Classes/PHPExcel.php";
include "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";

datepick();
auth('5-04',$_SESSION['aut']);
?>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
藥品(EX:H2O2,H2SO4)<br>


<input type="text" name="box1" ><br>


工時/分鐘<br>
<input type="text" name="box2" ><br>

檢測代號<br>

<input type="text" name="box3" ><br>

檢測項目(EX:Cerium,PMS> 0.07 um Particle)<br />
若是只要算一次可以填分析方法ex:M13 <br>

<input type="text" name="box4" ><br>

        <input name="submit" type="submit" class="center button" id="submit" value="  新增  ">
                <input name="leave" type="submit" class="center button" id="submit" value="  離開  ">



<?php
if(isset($_POST['submit']))
{
	$query="insert into [WORKTIME] (ELM_ID,PDD_CHEMICAL,ELF_FORM,hrs,ANI_GROUPNAME,ANI_FULLNAME) VALUES ('199','".strtoupper($_POST['box1'])."','no','".$_POST['box2']."','".strtoupper($_POST['box3'])."','".$_POST['box4']."')";
	$result = mssql_query($query);
	echo "已新增";
}
if(isset($_POST['leave']))
	{
		jumpto($_SESSION['lasturl']);
	}
?>