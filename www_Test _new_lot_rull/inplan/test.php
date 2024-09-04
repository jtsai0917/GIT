
<?php
unset($aa);
$aa[0][0]='00';
$aa[0][1]='01';
$aa[0][2]='02';
$aa[0][3]='03';
$aa[1][0]='10';
$aa[1][1]='11';
$aa[1][2]='12';
$aa[1][3]='13';
$aa[2][0]='20';
$aa[2][1]='21';
$aa[2][2]='22';
$aa[2][3]='23';
foreach($aa as $v1){
	foreach($v1 as $v2){
//		echo "XXX:".$v2;
	}
	echo "<BR>";
}
echo "CountX:". count($aa);
echo "<BR>";
echo "CountY:". count($aa,1);
?>