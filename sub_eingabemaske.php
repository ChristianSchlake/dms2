<?php
	$foundationVersion="foundation551";
	$foundationIcons="foundation-icons";	
	
	/*
	Maske für "Eintrag editieren" vorbereiten.
	*/	
	if ($_POST["eingabetyp"]=="editEntry" AND !isset($eingabetyp)) {
		/*
			HTML <head>
		*/
		echo "<head>";
			echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=utf8\"/>";
			echo "<meta name=\"viewport\" content=\"width=device-width\">";		

			echo "<script src=\"".$foundationVersion."/js/vendor/modernizr.js\"></script>";	
			echo "<link rel=\"stylesheet\" href=\"".$foundationVersion."/css/foundation.css\">";
			echo "<link rel=\"stylesheet\" href=\"".$foundationIcons."/foundation-icons/foundation-icons.css\"/>";
		echo "</head>";		
									
		include("sub_topbar.php");
		$eingabetyp=$_POST["eingabetyp"];
		$editID=$_POST["editID"];
		include("sub_init_database.php");
		include("functions.php");
		get_spaltenEigenschaft();
		
		/*
		$aTMP manipulieren		
		*/
		unset($aTMP);
		$aTMP=array();
		$abfrage=selectAbfrage();
		$abfrage=$abfrage." WHERE tMETA.datensaetze_id=".$editID;
		if ($resultat = $mysqli->query($abfrage)) {
			$daten = $resultat->fetch_object();
			foreach ($spalten as $key => $value) {
				$aTMP[$key]=$daten->$key;				
			}
		}
		echo "<html>";
		echo "<body>";
	} else {
		include("sub_get_session.php");	
	}
?>

<form action="main_suche.php" method="POST" class="custom" enctype="multipart/form-data">
	<div class="row collapse"></div>	
	<?php	
		$morgen = strtotime("+1 day");
		get_spaltenEigenschaft;

		/*
		Fester Metadatenstamm für Sucheinträge
		*/	
		switch($eingabetyp){
			case ("searchEntry"):					
				echo "<fieldset>";
					echo "<legend>Verwaltungsdaten</legend>";
					echo "<div class=\"large-8 columns\">";
						echo "<label>angelegt von";
			 				echo "<input type=\"text\" name=\"datensaetze_angelegt_von\"/>";
			 			echo "</label>";
			 		echo "</div>";
					echo "<div class=\"large-4 columns\">";
						echo "<label>angelegt am";
			 				echo "<input type=\"text\" name=\"datensaetze_angelegt_am\"/>";
			 			echo "</label>";
			 		echo "</div>";
					echo "<div class=\"large-8 columns\">";
						echo "<label>geändert von";
							if(isset($aTMP["datensaetze_geaendert_von"])) {
								echo "<input type=\"text\" value=\"".$aTMP["datensaetze_geaendert_von"]."\" name=\"datensaetze_geaendert_von\"/>";
							} else {
								echo "<input type=\"text\" name=\"datensaetze_geaendert_von\"/>";
							}	 				
			 			echo "</label>";
			 		echo "</div>";
					echo "<div class=\"large-4 columns\">";
						echo "<label>geändert am";
			 				echo "<input type=\"text\" name=\"datensaetze_geaendert_am\"/>";
			 			echo "</label>";
			 		echo "</div>";
				echo "</fieldset>";
				break;
		}
      /*
      Variabler Metadatenstamm
      */
		echo "<fieldset>";
			echo "<legend>Metadaten</legend>";
			foreach ($spalten as $key => $value) {
				if($tabellenPrefix[$key]=="tMETA.") {
  					echo "<div class=\"large-12 columns\">";
						echo "<label>".$spaltenNamen[$key];
							switch($spalten[$key]) { //Spaltentyp auswerten
								case "timestamp": case "date":																	
									if(isset($aTMP[$key])) {										
										echo "<input type=\"text\" value=\"".$aTMP[$key]."\" name=\"".$key."\"/>";
									} else {
										echo "<input type=\"text\" name=\"".$key."\"/>";										
									}
									break;
								case (preg_match('/int.*/', $spalten[$key]) ? true : false) :									
									if ($spaltenAnzahlWerte[$key] > 0) {
										/*
										Wenn eine Werteliste vorhanden ist:
										*/
										echo "<select name=\"".$key."\">";
											if (isset($aTMP[$key])==false) {
												echo "<option selected value=\"\">ALLES</option>";												
											} else {
												echo "<option value=\"\">ALLES</option>";
											}
											$suchwert=$aTMP[$key];
											if (substr($suchwert,0,1)=="=") {
												$suchwert=substr($aTMP[$key],1);												
											}
          								generateListOrdner(0,0,$key,$eingabetyp, $suchwert);
										echo "</select>";
									} else {
										/*
										Wenn keine Werteliste vorhanden ist:
										*/									
										if(isset($aTMP[$key])) {
											echo "<input type=\"text\" placeholder=\"<>0\" value=\"".$aTMP[$key]."\" name=\"".$key."\"/>";
										} else {
											echo "<input type=\"text\" placeholder=\"<>0\" name=\"".$key."\"/>";										
										}
									}
									break;									
								case (preg_match('/decimal.*/', $spalten[$key]) ? true : false) :
									if(isset($aTMP[$key])) {
										echo "<input type=\"text\" value=\"".str_replace(".",",",$aTMP[$key])."\" name=\"".$key."\"/>";
									} else {
										echo "<input type=\"text\" name=\"".$key."\"/>";										
									}									
									break;
								case (preg_match('/char.*/', $spalten[$key]) ? true : false) :
									if ( $dokumentspalte==$key) {
										/*
										Ein Button für eine Datei
										*/
		    							if ($eingabetyp=="addEntry") {
		    								echo "<input type=\"file\" name=\"userfile\"/>";
		    							} else {
		    								echo "<input type=\"file\" name=\"userfile\" disabled />";
		    							}
									} else {																		
										/*
										Text als Metadaten
										*/
										if(isset($aTMP[$key])) {
		    								echo "<input type=\"text\" value=\"".$aTMP[$key]."\" name=\"".$key."\"/>";
		    							} else {
		    								echo "<input type=\"text\" name=\"".$key."\"/>";
		    							}
		    						}
    								break;																		
								default:
									if(isset($aTMP[$key])) {
	    								echo "<input type=\"text\" value=\"".$aTMP[$key]."\" name=\"".$key."\"/>";
	    							} else {
	    								echo "<input type=\"text\" name=\"".$key."\"/>";
	    							}
    								break;
    							}
 						echo "</label>";
					echo "</div>";
				}
			}
		echo "</fieldset>";
		/*
		Butten um suchen oder Eingabe zu starten
		*/
		switch($eingabetyp){
			case ("searchEntry"):					
				echo "<button class=\"button secondary round\" type=\"Submit\"><i class=\"fi-magnifying-glass\"></i> suche starten</button>";
				break;
			case ("addEntry"):
				echo "<button class=\"button secondary round\" type=\"Submit\"><i class=\"fi-page-add\"></i> Eintrag hinzufügen</button>";			
				break;
			case ("editEntry"):
				echo "<button class=\"button secondary round\" type=\"Submit\"><i class=\"fi-save\"></i> Eintrag speichern</button>";
				echo "<input type=\"hidden\" value=\"".$editID."\" name=\"editID\">";			
				break;
		}		
		echo "<input type=\"hidden\" value=\"".$eingabetyp."\" name=\"eingabetyp\">";
		unset($aTMP);
	?>		
</form>
<?php
	if ($_POST["eingabetyp"]=="editEntry" AND !isset($eingabetyp)) {
				echo "<script src=\"".$foundationVersion."/js/vendor/jquery.js\"></script>";
		
		echo "<script src=\"".$foundationVersion."/js/foundation/foundation.js\"></script>";
		echo "<script src=\"".$foundationVersion."/js/foundation/foundation.topbar.js\"></script>";
		echo "<script src=\"".$foundationVersion."/js/foundation/foundation.dropdown.js\"></script>";
		echo "<script src=\"".$foundationVersion."/js/foundation/foundation.reveal.js\"></script>";
		echo "<script src=\"".$foundationVersion."/js/foundation/foundation.alert.js\"></script>";	
		echo "<script src=\"".$foundationVersion."/js/foundation/foundation.offcanvas.js\"></script>";
		echo "<script>$(document).foundation();</script>";  
		
		$mysqli->close();
		
		echo "</body>";
		echo "</html>";		
	}
?>
