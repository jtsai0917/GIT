<?php
include("../lib/fun.php");
if($_SESSION['uid']==''){
	jumpto("login.php");
}
