<?php	
//	include("conf.php");
//	include("sub_init_database.php");
//	include("functions.php");
//	get_spaltenEigenschaft();	
		
	$foundationVersion="foundation551";
	$foundationIcons="foundation-icons";
?>


<head>
	<meta http-equiv="content-type" content="text/html; charset=utf8">
	<meta name="viewport" content="width=device-width">
	<?php
		echo "<script src=\"".$foundationVersion."/js/vendor/modernizr.js\"></script>";	
		echo "<link rel=\"stylesheet\" href=\"".$foundationVersion."/css/foundation.css\">";
		echo "<link rel=\"stylesheet\" href=\"".$foundationIcons."/foundation-icons/foundation-icons.css\"/>";
	?>
</head>

<html>
<body>
	<div class="row">
		<div class="small-12 columns"><h1>Datei wirklich löschen?</h1></div>
		<?php
		
			/*
			Daten aus $_POST verarbeiten
			*/
			if (!empty($_POST)) {
				$editID=$_POST["editID"];
				$eingabeTyp=$_POST["eingabetyp"];			
			}		
			/*
			Daten aus $_GET verarbeiten
			*/
			if (!empty($_GET)) {
				$editID=$_GET["editID"];
				$eingabeTyp=$_GET["eingabetyp"];
			}		
		
		
		
			if ($eingabeTyp=="delEntry" AND isset($editID)) {
				echo "<div class=\"small-6 columns\">";
					echo "<form action=\"main_suche.php\" method=\"POST\" class=\"custom\">";
						echo "<button class=\"button alert expand\" type=\"Submit\">JA</button>";
						echo "<input type=\"hidden\" value=\"".$editID."\" name=\"editID\">";
						echo "<input type=\"hidden\" value=\"delEntry\" name=\"eingabetyp\">";
		  			echo "</form>";
				echo "</div>";
				echo "<div class=\"small-6 columns\">";
					echo "<form action=\"main_suche.php\" method=\"POST\" class=\"custom\">";
						echo "<button class=\"button expand\" type=\"Submit\">NEIN</button>";
						echo "<input type=\"hidden\" value=\"nothing\" name=\"eingabetyp\">";
		  			echo "</form>";
				echo "</div>";				
			}
		?>
	</div>

	<?php
		echo "<script src=\"".$foundationVersion."/js/vendor/jquery.js\"></script>";
		
		echo "<script src=\"".$foundationVersion."/js/foundation/foundation.js\"></script>";
		echo "<script src=\"".$foundationVersion."/js/foundation/foundation.topbar.js\"></script>";
		echo "<script src=\"".$foundationVersion."/js/foundation/foundation.dropdown.js\"></script>";
		echo "<script src=\"".$foundationVersion."/js/foundation/foundation.reveal.js\"></script>";
		echo "<script src=\"".$foundationVersion."/js/foundation/foundation.alert.js\"></script>";	
		echo "<script src=\"".$foundationVersion."/js/foundation/foundation.offcanvas.js\"></script>";
		echo "<script>$(document).foundation();</script>";  
	?>
	<?php
		$mysqli->close();
	?>
</body>

</html>