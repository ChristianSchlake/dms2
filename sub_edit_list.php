<?php
	session_start();	
	include("conf.php");
	include("sub_init_database.php");
	include("functions.php");
	get_spaltenEigenschaft();
	
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
		include("sub_topbar.php");
		//print_r($_POST);
	?>
	
	<?php
	/*
	Eingabe prüfen
	*/
		if($_POST["eingabetyp"]=="delEntryWert") {
			$abfrage="DELETE FROM `DMS_werteliste` WHERE werteliste_metadaten_spaltenName=\"".$_POST["Auswahllisten"]."\" AND werteliste_id=".$_POST["deleteID"].";";
			//echo "<br>".$abfrage;
			$mysqli->query($abfrage);
		}
		if($_POST["eingabetyp"]=="addEntryWert") {
			$abfrage="INSERT INTO `DMS_werteliste`(`werteliste_wert`, `werteliste_vater_id`, `werteliste_metadaten_spaltenName`) VALUES (\"".$_POST["addEintrag_Wert"]."\",".$_POST["addEintrag_Vater"].",\"".$_POST["Auswahllisten"]."\");";
			//echo "<br>".$abfrage;
			$mysqli->query($abfrage);
		}
	?>	
	
	
	<div class="row">		
		<fieldset>
		<legend>Auswahl der Werteliste</legend>
			<div class="row">	
				<form action="sub_edit_list.php" method="POST" class="custom" enctype="multipart/form-data">
					<?php		
						$morgen = strtotime("+1 day");
						get_spaltenEigenschaft;
			
				      /*
				      Variabler Metadatenstamm
				      */
						echo "<div class=\"large-12 columns\">";
							echo "<select name=\"Auswahllisten\">";
								foreach ($spalten as $key => $value) {
									if($tabellenPrefixShort[$key]=="tMETA_") {			  													
										switch($spalten[$key]) { //Spaltentyp auswerten
											case (preg_match('/int.*/', $spalten[$key]) ? true : false) :									
												if ($spaltenAnzahlWerte[$key] > 0) {
													/*
													Wenn eine Werteliste vorhanden ist:
													*/
													if($key==$_POST["Auswahllisten"]) {
														echo "<option selected value=\"".$key."\">".$spaltenNamen[$key]."</option>";
													} else {
														echo "<option value=\"".$key."\">".$spaltenNamen[$key]."</option>";
													}
												}
											break;	
		    							}
									}
								}
								echo "</select>";
								echo "<button class=\"button expand round\" type=\"Submit\"><i class=\"fi-magnifying-glass\"></i> suche starten</button>";
						echo "</div>";				
					?>		
				</form>
			</div>
			<div class="row">
				<?php 
					if (isset($_POST["Auswahllisten"])) {
						echo "<form action=\"sub_edit_list.php\" method=\"POST\" class=\"custom\">";
							echo "<input type=\"text\" name=\"addEintrag_Wert\" placeholder=\"Wert\" />";
							echo "<input type=\"text\" name=\"addEintrag_Vater\" placeholder=\"ID Vater\" />";			
							echo "<button class=\"button expand round\" type=\"Submit\"><i class=\"fi-page-add\"></i> Eintrag ergänzen</button>";						
							echo "<input type=\"hidden\" value=\"addEntryWert\" name=\"eingabetyp\">";
							echo "<input type=\"hidden\" value=\"".$_POST["Auswahllisten"]."\" name=\"Auswahllisten\">";
						echo "</form>";
					}
				?>
			</div>
			<div class="row">
				<table>
					<thead>
						<tr>
							<th>id</th>
							<th>Wert</th>
							<th>anzahl Dokumente</th>
							<th>Optionen</th>
						</tr>
					</thead>
					<tbody>
					<?php
						generateListOrdner_List(0,0,$_POST["Auswahllisten"], 0);
					?>
					</tbody>
				</table>
			</div>
		</fieldset>
		<fieldset>
		<legend>Batch Import aus dem Ordner "Scanner"</legend>
			<?php
				echo "<form action=\"sub_upload_batch.php\" method=\"GET\" class=\"custom\">";			
				echo "<button class=\"button expand round\" type=\"Submit\"><i class=\"fi-upload\"></i> batch Upload starten</button>";						
				echo "</form>";
			?>
		</fieldset>
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