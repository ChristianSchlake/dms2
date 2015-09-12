<?php
	include("functions.php");

	/*
	Daten aus $_POST verarbeiten
	*/
	if (!empty($_POST)) {
		$id=$_POST["id"];			
	}		
	/*
	Daten aus $_GET verarbeiten
	*/
	if (!empty($_GET)) {
		$id=$_GET["id"];			
	}
 
	$fileName=getFilenameByID($id);
	header("Location: $fileName");
	print "<script>self.close();</script>";
?>
</body>