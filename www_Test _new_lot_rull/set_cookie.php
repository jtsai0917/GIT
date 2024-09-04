<?php
if($_GET['comfirm']=='Y')
	{
	setcookie("comfirm", "Y", time()+3600);
	}
if($_GET['comfirm']=='N')
	{
		setcookie("comfirm", "N", time()+3600);
	}
echo '<script type="text/javascript">
	window.close( ); 
	</script>
	';