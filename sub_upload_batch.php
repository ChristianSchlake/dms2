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
	<?php
		$baseFolder="scanner/";
		include("sub_topbar.php");
		include("sub_init_database.php");
		$files = scandir($baseFolder);
		foreach($files as $fileSource) {
			$fileSource=$baseFolder.$fileSource;
			/*
			MaxID ermitteln
			*/
			$abfrage="SELECT MAX(datensaetze_id)+1 FROM DMS_datensaetze;";			
			$resultat=$mysqli->query($abfrage);
			$daten = $resultat->fetch_array();
			$maxID=$daten["MAX(datensaetze_id)+1"];
			if($maxID=="") {
				$maxID=0;
			}
			
			/*
			SQL Aufruf aufbauen
			*/
			$insert_data="INSERT INTO `DMS_datensaetze` (`datensaetze_id`, `datensaetze_angelegt_von`,`datensaetze_geaendert_von`) VALUES (".$maxID.",180,179);";
			$insert_meta="INSERT INTO `DMS_metadaten`(`datensaetze_id`) VALUES (".$maxID.");";
									
			/*
			File kopieren
			*/
			$path_parts=pathinfo($fileSource);
			$datei="upload/".$maxID.".".$path_parts['extension'];
			$erfolg=false;
			if (($path_parts['basename']<> ".") AND ($path_parts['basename']<> "..")){
				if (file_exists($datei)==false) {
					if(copy($fileSource, $datei)==true){				
						$erfolg=true;
					}
					if ($erfolg==true) {
						echo "<div data-alert class=\"alert-box success radius\">";
				  			echo "Upload erfolgreich: ".$path_parts['basename'];
				  			echo "<a href=\"#\" class=\"close\">&times;</a>";
				  			//echo "<br>".$insert_data."<br>".$insert_meta;
							$mysqli->query($insert_data);
							$mysqli->query($insert_meta);			  			
						echo "</div>";		
					} else {
						echo "<div data-alert class=\"alert-box alert radius\">";
				  			echo "Upload fehlgeschlagen: ".$path_parts['basename'];
				  			echo "<a href=\"#\" class=\"close\">&times;</a>";
						echo "</div>";			
					}
				} else {
					echo "<div data-alert class=\"alert-box alert radius\">";
					echo "Temporäre Datei ".$datei." existiert bereits";
					echo "<a href=\"#\" class=\"close\">&times;</a>";
					echo "</div>";
				}
			}
		}
	?>
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