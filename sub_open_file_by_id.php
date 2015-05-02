<?php
	include("functions.php");

	$id="";
	foreach ($_POST as $key => $value) {
		if ($key=="id") {
			$id=$value;
		}
	}
	echo 
	$fileName=getFilenameByID($id);
	header("Location: $fileName");
	print "<script>self.close();</script>";
?>
</body>