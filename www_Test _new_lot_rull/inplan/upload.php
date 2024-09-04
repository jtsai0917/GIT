<?php
		if ($_FILES["file"]["error"] > 0){
			echo $_FILES["file"]["name"]."дW╢╟ев▒╤";
			}
		else{
				move_uploaded_file($_FILES["file"]["tmp_name"],"filetmp/".$_FILES["file"]["name"]);
			}
?>