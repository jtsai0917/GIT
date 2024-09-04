<?php
include("../lib/fun.php");
$str=strtotime(ddd("20160801"));
echo $str;
echo "</br>";
$t=strtotime("-1 day",$str);
$t2=date("Y-m-d",$t);
echo $t2;