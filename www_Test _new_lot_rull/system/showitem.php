<?php 
session_start();
include("../lib/fun.php");
include("../connections/conn.php");
my_msg(ana_cid_to_nick($_GET['items']),"index.php?url=analyze_set");

?>