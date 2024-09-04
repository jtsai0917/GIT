<?php
$pid='P001-200';
$cid='C00001';
if(($pid=='P001-200' || $pid=='P002-190' || $pid=='P006-330' || $pid=='P050-330') and ($cid<>'C16043' and $cid<>'C20701' and $cid<>'C20703' and $cid<>'C20704' and $cid<>'C20705' and $cid<>'C20707' and $cid<>'C20708' and $cid<>'C20709' and $cid<>'C20801' and $cid<>'C20901' and $cid<>'C20904' and $cid<>'C20942' and $cid<>'C20955' and $cid<>'C20956'))
{$dun=1;}else{$dun=0;}
echo $dun;
?>
