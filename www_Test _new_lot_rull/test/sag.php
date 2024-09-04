<?php 
session_start();
include("../lib/sag_in.php");
remurl("sag");
$sag=new sign_in;
$sag->cbt_no='2-02';
$sag->order_no='0105081L0001';
$sag->sa_no =245399;
$sag->show();
?>