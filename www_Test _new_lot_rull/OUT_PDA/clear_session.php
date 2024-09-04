<?php
include "../lib/fun.php";
session_start();
unset($_SESSION['lorry_no']);
unset($_SESSION['lorry_no1']);
unset($_SESSION['lid']);
unset($_SESSION['cartype']);
jumpto("check_new.php");
?>