<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
?>
核對完成之後，點取新增以新增一筆 Total metal 的資料‧取樣資料以M13檢驗項目為依據‧
</br>

<form name="form1" method="post" action="<?php echo $loginFormAction; ?>">
<?php 
$loginFormAction = $_SERVER['PHP_SELF'];
$cust_no=$_GET['cust_no'];
$ani=new get_from_lot_no;
$ani->lid=$_GET['lot_no'];
$ani->ani();
	$dt=$ani->testdate;
$lot=new get_from_lot_no;
	$lot->cid();
	$tm=new TM;
	$tm->lid=$_GET['lot_no'];
	$tm->chemical=$_GET['pdd_chemical'];
	$tm->cid=$_GET['cust_no'];
	$tm->pid=$_GET['pid'];
	
	list($na,$mg,$al,$k,$ca,$cr,$mn,$fe,$ni,$co,$cu,$zn,$pb,$w,$in,$smpid_m13)=$tm->get_m13();
	$value_m13=$na+$mg+$al+$k+$ca+$cr+$mn+$fe+$ni+$co+$cu+$zn+$pb+$w+$in;	
	list ($li,$be,$ti,$v,$ga,$ge,$as,$sr,$zr,$nb,$mo,$ag,$cd,$sn,$sb,$ba,$ta,$au,$tl,$bi,$ce,$cs,$rb,$smpid_m21)=$tm->get_m21();
	$value_m21=$li+$be+$ti+$v+$ga+$ge+$as+$sr+$zr+$nb+$mo+$ag+$cd+$sn+$sb+$ba+$ta+$au+$tl+$bi+$ce+$cs+$rb;
	list($in2,$pt,$se,$hg,$p,$pd,$w2,$smpid_tt)=$tm->get_tt();
	$value_tt=$in2+$pt+$se+$hg+$p+$pd+$w2;	
	list($si2,$smpid_tt_si)=$tm->get_tt_si();
	$value_tt_si=$si2;	
	list($b2,$smpid_tt_b)=$tm->get_tt_b();
	$value_tt_b=$b2;	
	
	$value_tm=$value_m13+$value_m21+$value_tt+$value_tt_si+$value_tt_b;

/// show items
echo '</br><table border="1">M13';
echo '<tr>';echo '<td width="120" align="center">M13樣品瓶號</td>'  ;
echo '<td width="60" align="center">Na</td>'  ;echo '<td width="60" align="center">Mg</td>'  ;echo '<td width="60" align="center">Al</td>'  ;echo '<td width="60" align="center">K</td>'  ;echo '<td width="60" align="center">Ca</td>'  ;
echo '<td width="60" align="center">Cr</td>'  ;echo '<td width="60" align="center">Mn</td>'  ;echo '<td width="60" align="center">Fe</td>'  ;echo '<td width="60" align="center">Ni</td>'  ;echo '<td width="60" align="center">Co</td>'  ;
echo '<td width="60" align="center">Cu</td>'  ;echo '<td width="60" align="center">Zn</td>'  ;echo '<td width="60" align="center">Pb</td>'  ;echo '<td width="60" align="center">W</td>'  ;echo '<td width="60" align="center">In</td>'  ;
echo '</tr>';
echo '<tr>';echo '<td width="60" align="center">'.$smpid_m13.'</td>'  ;
echo '<td width="60" align="center">'.$na.'</td>'  ;echo '<td width="60" align="center">'.$mg.'</td>'  ;echo '<td width="60" align="center">'.$al.'</td>'  ;echo '<td width="60" align="center">'.$k.'</td>'  ;echo '<td width="60" align="center">'.$ca.'</td>'  ;
echo '<td width="60" align="center">'.$cr.'</td>'  ;echo '<td width="60" align="center">'.$mn.'</td>'  ;echo '<td width="60" align="center">'.$fe.'</td>'  ;echo '<td width="60" align="center">'.$ni.'</td>'  ;echo '<td width="60" align="center">'.$co.'</td>'  ;
echo '<td width="60" align="center">'.$cu.'</td>'  ;echo '<td width="60" align="center">'.$zn.'</td>'  ;echo '<td width="60" align="center">'.$pb.'</td>'  ;echo '<td width="60" align="center">'.$w.'</td>'  ;echo '<td width="60" align="center">'.$in.'</td>'  ;
echo '</tr>';
echo '</table>'  ;
echo '<table><tr><td>Total：'.$value_m13.'</td></tr></table>';
echo '</br>';

echo '<table border="1">M21';
echo '<tr>';echo '<td width="120" align="center">M21樣品瓶號</td>'  ;
echo '<td width="60" align="center">Li</td>'  ;echo '<td width="60" align="center">Be</td>'  ;echo '<td width="60" align="center">Ti</td>'  ;echo '<td width="60" align="center">V</td>'  ;echo '<td width="60" align="center">Ga</td>'  ;
echo '<td width="60" align="center">Ge</td>'  ;echo '<td width="60" align="center">As</td>'  ;echo '<td width="60" align="center">Sr</td>'  ;echo '<td width="60" align="center">Zr</td>'  ;echo '<td width="60" align="center">Nb</td>'  ;
echo '<td width="60" align="center">Mo</td>'  ;echo '<td width="60" align="center">Ag</td>'  ;echo '<td width="60" align="center">Cd</td>'  ;echo '<td width="60" align="center">Sn</td>'  ;echo '<td width="60" align="center">Sb</td>'  ;
echo '<td width="60" align="center">Ba</td>'  ;echo '<td width="60" align="center">Ta</td>'  ;echo '<td width="60" align="center">Au</td>'  ;echo '<td width="60" align="center">Tl</td>'  ;echo '<td width="60" align="center">Bi</td>'  ;
echo '<td width="60" align="center">Ce</td>'  ;echo '<td width="60" align="center">Cs</td>'  ;echo '<td width="60" align="center">Rb</td>'  ;
echo '</tr>';
echo '<tr>';echo '<td width="120" align="center">'.$smpid_m21.'</td>'  ;
echo '<td width="60" align="center">'.$li.'</td>'  ;echo '<td width="60" align="center">'.$be.'</td>'  ;echo '<td width="60" align="center">'.$ti.'</td>'  ;echo '<td width="60" align="center">'.$v.'</td>'  ;echo '<td width="60" align="center">'.$ga.'</td>'  ;
echo '<td width="60" align="center">'.$ge.'</td>'  ;echo '<td width="60" align="center">'.$as.'</td>'  ;echo '<td width="60" align="center">'.$sr.'</td>'  ;echo '<td width="60" align="center">'.$zr.'</td>'  ;echo '<td width="60" align="center">'.$nb.'</td>'  ;
echo '<td width="60" align="center">'.$mo.'</td>'  ;echo '<td width="60" align="center">'.$ag.'</td>'  ;echo '<td width="60" align="center">'.$cd.'</td>'  ;echo '<td width="60" align="center">'.$sn.'</td>'  ;echo '<td width="60" align="center">'.$sb.'</td>'  ;
echo '<td width="60" align="center">'.$ba.'</td>'  ;echo '<td width="60" align="center">'.$ta.'</td>'  ;echo '<td width="60" align="center">'.$au.'</td>'  ;echo '<td width="60" align="center">'.$tl.'</td>'  ;echo '<td width="60" align="center">'.$bi.'</td>'  ;
echo '<td width="60" align="center">'.$ce.'</td>'  ;echo '<td width="60" align="center">'.$cs.'</td>'  ;echo '<td width="60" align="center">'.$rb.'</td>'  ;
echo '</tr>';
echo '</table>'  ;
echo '<table><tr><td>Total：'.$value_m21.'</td></tr></table>';
echo '</br>';

echo '<table border="1">TT';
echo '<tr>';echo '<td width="120" align="center">TT樣品瓶號</td>'  ;
echo '<td width="60" align="center">In</td>' ;echo '<td width="60" align="center">Pt</td>' ;echo '<td width="60" align="center">Se</td>' ;echo '<td width="60" align="center">Hg</td>' ;echo '<td width="60" align="center">P</td>' ;
echo '<td width="60" align="center">Pd</td>' ;echo '<td width="60" align="center">W</td>' ;echo '<td width="60" align="center">Si</td>' ;echo '<td width="60" align="center">B</td>' ;
echo '</tr>';
echo '<tr>';echo '<td width="120" align="center">'.$smpid_tt.'</td>'  ;
echo '<td width="60" align="center">'.$in2.'</td>' ;echo '<td width="60" align="center">'.$pt.'</td>' ;echo '<td width="60" align="center">'.$se.'</td>' ;echo '<td width="60" align="center">'.$hg.'</td>' ;echo '<td width="60" align="center">'.$p.'</td>' ;
echo '<td width="60" align="center">'.$pd.'</td>' ;echo '<td width="60" align="center">'.$w2.'</td>' ;echo '<td width="60" align="center">'.$si2.'</td>' ;echo '<td width="60" align="center">'.$b2.'</td>' ;
echo '</tr>';
echo '</table>'  ;
echo '<table><tr><td>Total：'.(($value_tt)+($value_tt_b)+($value_tt_si)).'</td></tr></table>';
echo '</br>';
?>
和否判定
<input type="checkbox" name="Ok" id="Ok" /></br>
<input type="submit" name="creat" id="submit" value="   新 增   ">
<input type="button" name="button" id="button" value="   離 開   " onClick="window.open('<?php echo $_SESSION['retir'];?>', '_self');" />
</form>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];

if (isset($_POST["creat"])) {
	if ($_POST['Ok']=="on"){$_POST['Ok']=1;}
	else {$_POST['Ok']=0;}
	//////////取得欄位名///////////
include ("../connections/conn.php");
$_SESSION['createtime']=date("YmdHis");
$query="INSERT INTO dbo.analyze_first
                          (lot_no, sample_no, create_time, ps, create_user,first)
VALUES         ('".$_GET['lot_no']."', '".$_POST['SampleNo']."', '".$_SESSION['createtime']."', '".$_POST['ani_groupname']."', '".$_SESSION['uid']."','".$_SESSION['userid']."')";
$result=mssql_query($query);
if (!$result) {
    print("SQL statement failed with error:\n");
    print("   ".mssql_get_last_message()."\n");
  }
	$table=get_table("TM",$_GET['pdd_chemical']);
	$sn=new_analyze_sn($table,$_GET['lot_no']);
	$query="INSERT INTO ".$table." 
                          (CHK1, CHK2, TestDate, LotNo, SerialNo, SampleNo, TM, Ok, Tester, Operator, 
                          AnaManager, AnalyzeTime)
VALUES         ('1','1','".$dt."','".$_GET['lot_no']."','".$sn."','".$smpid_m13."','".$value_tm."','".$_POST['Ok']."','".$_SESSION['uname']."','".$_GET['operator']."',NULL,'".$_SESSION['createtime']."')";
$result=mssql_query($query);
if (!$result) {
    print("SQL statement failed with error:\n");
    print("   ".mssql_get_last_message()."\n");
  }
  
jumpto($_SESSION['input_repot_sec']);
}

class TM{
	public $group,$lid,$cid,$pid,$chemical,$radis,$table ;
	
	function get_m13(){
		$this->table=get_table("M13",$this->chemical);
		$query="select * from ".$this->table." where LotNo='".$this->lid."' ORDER BY  AnalyzeTime ";
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result)){
			$na=$row['Na'];$mg=$row['Mg'];$al=$row['Al'];$k=$row['K'];$ca=$row['Ca'];$cr=$row['Cr'];$mn=$row['Mn'];$fe=$row['Fe'];
			$ni=$row['Ni'];$co=$row['Co'];$cu=$row['Cu'];$zn=$row['Zn'];$pb=$row['Pb'];$w=$row['W'];$in=$row['In'];
			$smpid_m13=$row['SampleNo'];
			if(($na==0.000001) or ($na==0) or ($na<0.005)){$na=0.005;}
			if(($mg==0.000001) or ($mg==0) or ($mg<0.005)){$mg=0.005;}
			if(($al==0.000001) or ($al==0) or ($al<0.005)){$al=0.005;}
			if(($k==0.000001) or ($k==0) or ($k<0.005)){$k=0.005;}
			if(($ca==0.000001) or ($ca==0) or ($ca<0.005)){$ca=0.005;}
			if(($cr==0.000001) or ($cr==0) or ($cr<0.005)){$cr=0.005;}
			if(($mn==0.000001) or ($mn==0) or ($mn<0.005)){$mn=0.005;}
			if(($fe==0.000001) or ($fe==0) or ($fe<0.005)){$fe=0.005;}
			if(($ni==0.000001) or ($ni==0) or ($ni<0.005)){$ni=0.005;}
			if(($co==0.000001) or ($co==0) or ($co<0.005)){$co=0.005;}
			if(($cu==0.000001) or ($cu==0) or ($cu<0.005)){$cu=0.005;}
			if(($zn==0.000001) or ($zn==0) or ($zn<0.005)){$zn=0.005;}
			if(($pb==0.000001) or ($pb==0) or ($pb<0.005)){$pb=0.005;}
			if(($w==0.000001) or ($w==0) or ($w<0.005)){$w=0.005;}
			if(($in==0.000001) or ($in==0) or ($in<0.005)){$in=0.005;}
		}
		if($this->get_spec("Na")==0){$na=0;}
			if($this->get_spec("Mg")==0){$mg=0;}
			if($this->get_spec("Al")==0){$al=0;}
			if($this->get_spec("K")==0){$k=0;}
			if($this->get_spec("Ca")==0){$ca=0;}
			if($this->get_spec("Cr")==0){$cr=0;}
			if($this->get_spec("Mn")==0){$mn=0;}
			if($this->get_spec("Fe")==0){$fe=0;}
			if($this->get_spec("Ni")==0){$ni=0;}
			if($this->get_spec("Co")==0){$co=0;}
			if($this->get_spec("Cu")==0){$cu=0;}
			if($this->get_spec("Zn")==0){$zn=0;}
			if($this->get_spec("Pb")==0){$pb=0;}
			if($this->get_spec("W")==0){$w=0;}
			if($this->get_spec("In")==0){$in=0;}
			
		return array($na,$mg,$al,$k,$ca,$cr,$mn,$fe,$ni,$co,$cu,$zn,$pb,$w,$in,$smpid_m13);
	}
	
	function get_m21(){
		$this->table=get_table("M21",$this->chemical);
		$query="select * from ".$this->table." where LotNo='".$this->lid."' ORDER BY  AnalyzeTime";
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result)){
			$li=$row['Li'];$be=$row['Be'];$ti=$row['Ti'];$v=$row['V'];$ga=$row['Ga'];$ge=$row['Ge'];$as=$row['As'];$sr=$row['Sr'];
			$zr=$row['Zr'];$nb=$row['Nb'];$mo=$row['Mo'];$ag=$row['Ag'];$cd=$row['Cd'];$sn=$row['Sn'];$sb=$row['Sb'];$ba=$row['Ba'];
			$ta=$row['Ta'];$au=$row['Au'];$tl=$row['Tl'];$bi=$row['Bi'];$ce=$row['Ce'];$cs=$row['Cs'];$rb=$row['Rb'];
			$smpid_m21=$row['SampleNo'];
			if(($li==0.000001) or ($li==0) or ($li<0.005)){$li=0.005;}
			if(($be==0.000001) or ($be==0) or ($be<0.005)){$be=0.005;}
			if(($ti==0.000001) or ($ti==0) or ($ti<0.005)){$ti=0.005;}
			if(($v==0.000001) or ($v==0) or ($v<0.005)){$v=0.005;}
			if(($ga==0.000001) or ($ga==0) or ($ga<0.005)){$ga=0.005;}
			if(($ge==0.000001) or ($ge==0) or ($ge<0.005)){$ge=0.005;}
			if(($as==0.000001) or ($as==0) or ($as<0.005)){$as=0.005;}
			if(($sr==0.000001) or ($sr==0) or ($sr<0.005)){$sr=0.005;}
			if(($zr==0.000001) or ($zr==0) or ($zr<0.005)){$zr=0.005;}
			if(($nb==0.000001) or ($nb==0) or ($nb<0.005)){$nb=0.005;}
			if(($mo==0.000001) or ($mo==0) or ($mo<0.005)){$mo=0.005;}
			if(($ag==0.000001) or ($ag==0) or ($ag<0.005)){$ag=0.005;}
			if(($cd==0.000001) or ($cd==0) or ($cd<0.005)){$cd=0.005;}
			if(($sn==0.000001) or ($sn==0) or ($sn<0.005)){$sn=0.005;}
			if(($sb==0.000001) or ($sb==0) or ($sb<0.005)){$sb=0.005;}
			if(($ba==0.000001) or ($ba==0) or ($ba<0.005)){$ba=0.005;}
			if(($ta==0.000001) or ($ta==0) or ($ta<0.005)){$ta=0.005;}
			if(($au==0.000001) or ($au==0) or ($au<0.005)){$au=0.005;}
			if(($tl==0.000001) or ($tl==0) or ($tl<0.005)){$tl=0.005;}
			if(($bi==0.000001) or ($bi==0) or ($bi<0.005)){$bi=0.005;}
			if(($ce==0.000001) or ($ce==0) or ($ce<0.005)){$ce=0.005;}
			if(($cs==0.000001) or ($cs==0) or ($cs<0.005)){$cs=0.005;}
			if(($rb==0.000001) or ($rb==0) or ($rb<0.005)){$rb=0.005;}
			
		}
		if($this->get_spec("Li")==0){$li=0;}
			if($this->get_spec("Be")==0){$be=0;}
			if($this->get_spec("Ti")==0){$ti=0;}
			if($this->get_spec("V")==0){$v=0;}
			if($this->get_spec("Ga")==0){$ga=0;}
			if($this->get_spec("Ge")==0){$ge=0;}
			if($this->get_spec("As")==0){$as=0;}
			if($this->get_spec("Sr")==0){$sr=0;}
			if($this->get_spec("Zr")==0){$zr=0;}
			if($this->get_spec("Nb")==0){$nb=0;}
			if($this->get_spec("Mo")==0){$mo=0;}
			if($this->get_spec("Ag")==0){$ag=0;}
			if($this->get_spec("Cd")==0){$cd=0;}
			if($this->get_spec("Sn")==0){$sn=0;}
			if($this->get_spec("Sb")==0){$sb=0;}
			if($this->get_spec("Ba")==0){$ba=0;}
			if($this->get_spec("Ta")==0){$ta=0;}
			if($this->get_spec("Au")==0){$au=0;}
			if($this->get_spec("Tl")==0){$tl=0;}
			if($this->get_spec("Bi")==0){$bi=0;}
			if($this->get_spec("Ce")==0){$ce=0;}
			if($this->get_spec("Cs")==0){$cs=0;}
			if($this->get_spec("Rb")==0){$rb=0;}
		return array($li,$be,$ti,$v,$ga,$ge,$as,$sr,$zr,$nb,$mo,$ag,$cd,$sn,$sb,$ba,$ta,$au,$tl,$bi,$ce,$cs,$rb,$smpid_m21);
	}
	
	function get_tt(){
		$this->table=get_table("TT",$this->chemical);
		$query="select * from ".$this->table." where LotNo='".$this->lid."' ORDER BY  AnalyzeTime ";
		$result=mssql_query($query);
		$numRows = mssql_num_rows($result);
		if($numRows>0){
		while($row=mssql_fetch_array($result)){
			$in=$row['In'];$pt=$row['Pt'];$se=$row['Se'];$hg=$row['Hg'];$p=$row['P'];$pd=$row['Pd'];$w=$row['W'];
			$smpid_tt=$row['SampleNo'];
			if(($in==0.000001) or ($in==0) or ($in<0.005)){$in=0.005;}
			if(($pt==0.000001) or ($pt==0) or ($pt<0.005)){$pt=0.005;}
			if(($se==0.000001) or ($se==0) or ($se<0.01)){$se=0.01;}
			if(($hg==0.000001) or ($hg==0) or ($hg<0.005)){$hg=0.01;}
			if(($p==0.000001) or ($p==0) or ($p<0.02)){$p=0.02;}
			if(($pd==0.000001) or ($pd==0) or ($pd<0.02)){$pd=0.02;}
			if(($w==0.000001) or ($w==0) or ($w<0.005)){$w=0.005;}
			
		}}
		else{$in=0.005;$pt=0.005;$se=0.01;$hg=0.01;$p=0.02;$pd=0.02;$w=0.005;}
		if($this->get_spec("In")==0){$in=0;}
			if($this->get_spec("Pt")==0){$pt=0;}
			if($this->get_spec("Se")==0){$se=0;}
			if($this->get_spec("Hg")==0){$hg=0;}
			if($this->get_spec("P")==0){$p=0;}
			if($this->get_spec("Pd")==0){$pd=0;}
			if($this->get_spec("W")==0){$w=0;}
		return array($in,$pt,$se,$hg,$p,$pd,$w,$smpid_tt);
	}
	
	function get_tt_b(){
		$this->table=get_table("TT_B",$this->chemical);
		$query="select * from ".$this->table." where LotNo='".$this->lid."' ORDER BY  AnalyzeTime ";
		$result=mssql_query($query);
		$numRows = mssql_num_rows($result);
		if($numRows>0){
		while($row=mssql_fetch_array($result)){
			$b=$row['B'];
			$smpid_tt_b=$row['SampleNo'];
			if(($b==0.000001) or ($b==0) or ($b<0.05)){$b=0.05;}
		}}
		else{$b=0.05;}
		if($this->get_spec("B")==0){$b=0;}
		return array($b,$smpid_tt_b);
	}
	
	function get_tt_si(){
		$this->table=get_table("TT_Si",$this->chemical);
		$query="select * from ".$this->table." where LotNo='".$this->lid."' ORDER BY  AnalyzeTime ";
		$result=mssql_query($query);
		$numRows = mssql_num_rows($result);
		if($numRows>0){
		while($row=mssql_fetch_array($result)){
			$si=$row['Si'];
			$smpid_tt_si=$row['SampleNo'];
			if(($si==0.000001) or ($si==0) or ($si<0.5)){$si=0.5;}
		}}
		else{$si=0.5;}
		if($this->get_spec("Si")==0){$si=0;}
		return array($si,$smpid_tt_si);
	}
	
	function get_spec($item){
		$query="SELECT    QC_Spec.DL
				FROM      dbo.QC_CustProdSpec INNER JOIN
                          dbo.QC_Spec ON dbo.QC_CustProdSpec.SpecVer = dbo.QC_Spec.SpecVer AND 
                          dbo.QC_CustProdSpec.SpecNo = dbo.QC_Spec.SpecNo INNER JOIN
                          dbo.QC_Item ON dbo.QC_Spec.ItemName = dbo.QC_Item.ItemName INNER JOIN
                          dbo.AnalyzeItem ON 
                          dbo.QC_Item.ItemNickName = dbo.AnalyzeItem.ANI_NICKNAME
				WHERE          (QC_CustProdSpec.CustNo = '".$this->cid."') AND (QC_CustProdSpec.ProdNo = '".$this->pid."') AND (AnalyzeItem.ANI_DATAFIELD='".$item."')";	
		$_SESSION['tmppppppppp']=$query;
		$result=mssql_query($query);
		$numRows = mssql_num_rows($result);
		if($numRows>0){return 1;}
		else{return 0;}		
	}
}

?>
