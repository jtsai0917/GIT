<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php 
session_start();
include("../lib/fun.php");
include("../checkuser.php");
include("../lib/jtsai.php");
lasturl();
datepick();
if($_SESSION['datepicker1']==''){$_SESSION['datepicker1']=date("m/d/Y")   ;}
if($_SESSION['datepicker2']==''){$_SESSION['datepicker2']=date("m/d/Y")   ;}
?>
<form name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <table width="1240" border="1">
    <tr>
      <td width="480"><p>硫酸生產日報</p>
        <p>製造日期
          <input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php echo $_SESSION['datepicker1'] ; ?>"  onchange="set_date_session(this.name,this.value)">
          ~
          <input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php echo $_SESSION['datepicker2'] ; ?>"  onchange="set_date_session(this.name,this.value)">
          <input type="submit" name="search" id="search" value="    搜  尋   " />
          <?php //        <input type="button" name="peint" id="peint" value="列印"> ?>
          <input type="hidden" name="mm_insert" id="mm_insert" value="form1" />
        </p>
      </td>

    </tr>
  </table>

<?php
if(isset($_POST['search'])){
	
	$dt=$d1=strtotime(ddo($_SESSION['datepicker1'])." 00:00:00");
	$d2=strtotime(ddo($_SESSION['datepicker2'])." 00:00:00");
	$days= ($d2-$d1)/86400;
	
	echo '<input type="hidden" name="days" value="'.$days.'"><table border="1"><tr><td>class</td><td></td>';
	for($i=0;$i<=$days;$i++){
		echo '<td>'.date("m/d",$dt).'</td>';
		$dt=strtotime("+1 day",$dt);	
	}
	echo '</tr>';
	$index=1;
	$query="SELECT  class, name, ps  FROM produce_class where ps<>9";
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		$dt=$d1=strtotime(ddo($_SESSION['datepicker1'])." 00:00:00");
		echo '<tr><td>'.$row['class'].'</td><td>'.$row['name'].'</td>';
		for($i=0;$i<=$days;$i++){
			echo '<td><input type="text" name="VL'.$i.$row['class'].'" size="3" value="'.get_value($row['class'],date("Y/m/d",$dt)).'"></td>';
			echo '<input type="hidden" value="'.$row['class'].'" name="class'.$i.$row['class'].'">';
			echo '<input type="hidden" value="'.date("Y/m/d",$dt).'" name="date'.$i.$row['class'].'">';
			$dt=strtotime("+1 day",$dt);
		}
		$index++;
		echo '</tr>';
	}
	
	echo '</table><BR><input type="submit" name="input" id="input" value="    SAVE DATA   " />';	
}
echo '</form><BR>';

if(isset($_POST['input'])){
	$n=0;
	$tm=date("YmdHis");
	echo "DAYS:".($_POST['days']+1)."<BR>";
	$j=$_POST['days'];
	$query="SELECT  class, name, ps FROM produce_class";
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
	for($i=0;$i<=$j;$i++){
		if(trim($_POST['VL'.$i.$row['class']])<>'')
		{
			echo "插入值...日期： ".$_POST['date'.$i.$row['class']]."****Class： ".$_POST['class'.$i.$row['class']]."****Value：".$_POST['VL'.$i.$row['class']]."<BR>";
			$query1="INSERT INTO produce_daily (date, class, produce_qty, creator, createtime) VALUES (N'".$_POST['date'.$i.$row['class']]."', ".$_POST['class'.$i.$row['class']].", ".$_POST['VL'.$i.$row['class']].", N'".$_SESSION['uid']."', N'".$tm."')";
			$result1=mssql_query($query1);
			$n=$n+1;
		}
	}
	$index++;
	}
	echo "<br>共:".$n."筆";
}

function get_value($idx,$date){
	$query="SELECT TOP (1) produce_qty FROM produce_daily WHERE (class = ".$idx.") AND ([date] = N'".$date."') 
ORDER BY   createtime DESC";	
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	return $row[0];
}
?>
