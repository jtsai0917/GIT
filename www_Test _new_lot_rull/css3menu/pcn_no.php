<?php 
session_start();
include("../lib/fun.php");
include("../checkuser.php");
include("../lib/jtsai.php");
lasturl();
datepick();
include("../lib/user_right.php");
$a1=array_user_group($_SESSION['uid'],'QA');
$a2=array_user_group($_SESSION['uid'],'分析主管');
if($a1==0){ $disable1=' disabled="disabled" ';}else{ $disable1='';}
if($a2==0){ $disable2=' disabled="disabled" ';}else{ $disable2='';}
if($_SESSION['datepicker1']==''){$_SESSION['datepicker1']=date("m/d/Y")   ;}
if($_SESSION['datepicker2']==''){$_SESSION['datepicker2']=date("m/d/Y")   ;}
?>

<form name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <label for="textfield"></label>

  <table width="1240" border="1">
    <tr>
      <td width="480">要求日期 
        <input name="datepicker1" type="text" id="datepicker1" size="14" value="<?php 	echo $_SESSION['datepicker1'];?>"  onchange="set_date_session(this.name,this.value)">
        ~
        <input name="datepicker2" type="text" id="datepicker2" size="14" value="<?php 	echo $_SESSION['datepicker2'];?>"  onchange="set_date_session(this.name,this.value)">

        
        品名：<span class="d1">
        <input type="button" name="X" id="X" value="X" onClick="window.open('../erase_prod.php ', '_self');">
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
		?>" >
        <input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onClick="window.open('../pdd_prod_no.php ', '_self');" >
        <input name="pdd_chemical2" type="text" id="pdd_chemical2" size="16" value="<?php 
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
		?>" >
        Lot No：
        <input type="button" name="X2" id="X2" value="X" onclick="window.open('../erase_lot.php ', '_self');" />
<input name="textfield4" type="text" id="textfield4" size="10" value="<?php 
		if ($_GET['lid']){
			echo $_GET['lid'];
			$_SESSION['lid']=$_GET['lid'];
		}
		elseif($_SESSION['lid']){
			echo $_SESSION['lid'];
		}
		else{
		echo '';
		}
		?>">
		</br>  
          檢驗項目：
          <?php select_ani_group();?>
		  <input type="checkbox" name="rut" />非定常分析          
        &nbsp;&nbsp;&nbsp;<input type="checkbox" name="urgent1">急件
        &nbsp;&nbsp;&nbsp;<input type="checkbox" name="signed">未簽核
				&nbsp;&nbsp;&nbsp;<input type="submit" name="search" id="search" value="    搜  尋   " />
				&nbsp;&nbsp;&nbsp;<input type="button" name="exit" id="exit" value="離開" />
          <input type="hidden" name="mm_insert" id="mm_insert" value="form1" />

        </span></td>

    </tr>
  </table>
</form>