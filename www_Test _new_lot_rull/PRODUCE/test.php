<?php

echo $ioi=trim(AtoZ(55));

function AtoZ($i){
	$letter = range("A","Z");
	if($i>26){
		$x=floor($i/26);
		$s1=$letter[$x-1];
	}
		$s2_=$i%26;
		$s2=$letter[$s2_-1];
		return $s1.$s2;
}
?>