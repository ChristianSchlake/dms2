<?php
	function get_spaltenEigenschaft() {
		global $spalten, $spaltenNamen, $mysqli, $tabellenPrefix, $spaltenAnzahlWerte;
		$spalten = array();
		if ($resultat = $mysqli->query("SHOW FULL COLUMNS FROM DMS_metadaten WHERE Field Like 'metadaten_spalte%'")) {
			while($daten = $resultat->fetch_object() ){
				$spalten[$daten->Field] = $daten->Type;
				$spaltenNamen[$daten->Field] = $daten->Comment;
				$tabellenPrefix[$daten->Field]="tMETA.";
				$spaltenAnzahlWerte[$daten->Field]=Anzahl_eintraege_Werteliste($daten->Field);				
			}  
			$resultat->close();
		};
		// Default Feste spalten eintragen (aus der Tabelle DMS_datensaetze)
		if ($resultat = $mysqli->query("SHOW FULL COLUMNS FROM DMS_datensaetze WHERE FIELD <> \"datensaetze_id\"")) {
			while($daten = $resultat->fetch_object() ){
				$spalten[$daten->Field] = $daten->Type;
				$spaltenNamen[$daten->Field] = $daten->Comment;
				$tabellenPrefix[$daten->Field]="tDATA.";				
			}  
			$resultat->close();
		};
	}

	function generateListOrdner($root_id = 0, $stufe = 0, $spaltenNameX, $eingabetyp, $selected) {
		global $mysqli;
		
		$abfrage="SELECT DISTINCT * FROM DMS_werteliste WHERE werteliste_vater_id = ".$root_id." AND `werteliste_metadaten_spaltenName`=\"".$spaltenNameX."\"";
		$einrueckung = str_repeat ("....", $stufe);
		if ($resultat = $mysqli->query($abfrage)) {
			while($daten = $resultat->fetch_object() ){
				if ($eingabetyp=="searchEntry") {
					$echoWert = "<option value=\"=".$daten->werteliste_id."\">".$einrueckung.$daten->werteliste_wert."</option>";
				} else {
					$echoWert = "<option value=\"".$daten->werteliste_id."\">".$einrueckung.$daten->werteliste_wert."</option>";				
				}
				if ($selected==$daten->werteliste_id) {
					$echoWert = str_replace("<option value=", "<option selected value=", $echoWert);
				}
				echo $echoWert;
				generateListOrdner($daten->werteliste_id, $stufe+1, $spaltenNameX, $eingabetyp, $selected);
			}
		}
	}

	function generateListOrdner_leftNavigation($root_id = 0, $stufe = 0, $spaltenNameX, $nr, $selected) {
		global $mysqli;
		$abfrage="SELECT DISTINCT * FROM DMS_werteliste WHERE werteliste_vater_id = ".$root_id." AND `werteliste_metadaten_spaltenName`=\"".$spaltenNameX."\"";
		//$einrueckung = str_repeat ("....", $stufe);
		if ($resultat = $mysqli->query($abfrage)) {
			while($daten = $resultat->fetch_object() ){
				$nr=$nr+1;								
				$echoWert = "<option value=\"=".$daten->werteliste_id."\">".$einrueckung.$daten->werteliste_wert."</option>";
				$kinder=Anzahl_Kinder_Werteliste($spaltenNameX, $daten->werteliste_id);
				if($kinder > 0) {
					echo "<li class=\"has-submenu\"><a href=\"#\">".$daten->werteliste_wert."</a>";
						echo "<ul class=\"left-submenu\">";
							echo "<li class=\"back\"><a href=\"#\">Back</a></li>";
							echo "<li><label>".$daten->werteliste_wert."</label></li>";
							generateListOrdner_leftNavigation($daten->werteliste_id, $stufe+1, $spaltenNameX, $nr+1, $selected);
						echo "</ul>";
					echo "</li>";
				} else {
					echo "<form id=\"Form".$daten->werteliste_id."\" action=\"main_suche.php\" method=\"POST\" class=\"custom\">";
							echo "<a href=\"#\" onclick=\"Form".$daten->werteliste_id.".submit()\">".$daten->werteliste_wert." - ".$daten->werteliste_id."</a>";
							echo "<input type=\"hidden\" value=\"=".$daten->werteliste_id."\" name=\"".$leftNavigation."\">";
							echo "<input type=\"hidden\" value=\"searchEntry\" name=\"eingabetyp\">";
					echo "</form>";
				}
			}
		}
	}
	
	function generateListOrdner_rightNavigation($spaltenNameX) {
		global $mysqli, $rightNavigation;
		
		$abfrage=selectDistinctAbfrageSpalte($spaltenNameX);
		
		//$abfrage="SELECT DISTINCT * FROM DMS_werteliste WHERE werteliste_vater_id = ".$root_id." AND `werteliste_metadaten_spaltenName`=\"".$spaltenNameX."\"";
		//$einrueckung = str_repeat ("....", $stufe);
		if ($resultat = $mysqli->query($abfrage)) {
			while($daten = $resultat->fetch_object() ){
				$nr=$nr+1;
				$echoWert = "<option value=\"=".$daten->werteliste_id."\">".$einrueckung.$daten->werteliste_wert."</option>";
				$kinder=Anzahl_Kinder_Werteliste($spaltenNameX, $daten->werteliste_id);
				if($kinder > 0) {
					echo "<li class=\"has-submenu\"><a href=\"#\">".$daten->werteliste_wert."</a>";
						echo "<ul class=\"left-submenu\">";
							echo "<li class=\"back\"><a href=\"#\">Back</a></li>";
							echo "<li><label>".$daten->werteliste_wert."</label></li>";
							generateListOrdner_leftNavigation($daten->werteliste_id, $stufe+1, $spaltenNameX, $nr+1, $selected);
						echo "</ul>";
					echo "</li>";
				} else {
					echo "<form id=\"Form".$daten->werteliste_id."\" action=\"main_suche.php\" method=\"POST\" class=\"custom\">";
							echo "<a href=\"#\" onclick=\"Form".$daten->werteliste_id.".submit()\">".$daten->werteliste_wert." - ".$daten->werteliste_id."</a>";
							echo "<input type=\"hidden\" value=\"=".$daten->werteliste_id."\" name=\"".$leftNavigation."\">";
							echo "<input type=\"hidden\" value=\"searchEntry\" name=\"eingabetyp\">";
					echo "</form>";
				}
			}
		}
	}
	
	function Anzahl_eintraege_Werteliste($spaltenNameX) {
		global $mysqli;
		/*
		Gibt die Anzahl an Wertelisteneinträgen für die übergebene Spalte zurück
		*/
		$zahl = 0;
		$abfrage = "SELECT * FROM `DMS_werteliste` WHERE `werteliste_metadaten_spaltenName`=\"".$spaltenNameX."\"";
		if ($resultat = $mysqli->query($abfrage)) {
			$zahl = mysqli_num_rows($resultat);
		}
		return $zahl;		
	}
	
	function Anzahl_Kinder_Werteliste($spaltenNameX, $root_id) {
		global $mysqli;
		/*
		Gibt die Anzahl an Kindereinträgen für die übergebene Spalte zurück
		*/
		$zahl = 0;
		$abfrage = "SELECT * FROM `DMS_werteliste` WHERE `werteliste_metadaten_spaltenName`=\"".$spaltenNameX."\" AND `werteliste_vater_id`= ".$root_id;
		if ($resultat = $mysqli->query($abfrage)) {
			$zahl = mysqli_num_rows($resultat);
		}
		return $zahl;		
	}	
	


	function selectAbfrage() {
		global /*$abfrage,*/ $spalten, $tabellenPrefix, $spaltenAnzahlWerte;
		
		/*
		Select Abfrage aufbauen
		*/		
		$abfrage="SELECT
						tDATA.datensaetze_id AS ID,
						tUSER.user_name AS AngelegtVon,
						DATE_FORMAT(tDATA.datensaetze_angelegt_am, \"%d.%m.%Y - %H:%i\") AS AngelegtAm,
						tUSER2.user_name AS geaendertVon,
						DATE_FORMAT(tDATA.datensaetze_geaendert_am, \"%d.%m.%Y - %H:%i\") AS geaendertAm";
		
		foreach ($spalten as $key => $value) {
			if($tabellenPrefix[$key]=="tMETA.") {
				if($spaltenAnzahlWerte[$key]>0) {
					/*
					Spalte mit Werteliste
					*/
					$nWerte="tWERTE_".$key;
					$abfrage=$abfrage.",".$nWerte.".werteliste_wert AS ".$nWerte;
					$abfrage=$abfrage.",".$tabellenPrefix[$key].$key;
				} else {
					/*
					Spalte ohne Werteliste
					*/
					switch($spalten[$key]) {
						case "date":																	
							$abfrage=$abfrage.",DATE_FORMAT(".$tabellenPrefix[$key].$key.", GET_FORMAT(DATE, 'EUR')) AS '".$key."'";
							break;
						default:
							$abfrage=$abfrage.",".$tabellenPrefix[$key].$key;
							break;
					}
				}
			}			
		}
		
		$abfrage=$abfrage." FROM DMS_datensaetze AS tDATA
						INNER JOIN DMS_metadaten as tMETA ON tMETA.datensaetze_id=tDATA.datensaetze_id
						INNER JOIN DMS_user as tUSER on tUSER.user_id=tDATA.datensaetze_angelegt_von
						INNER JOIN DMS_user as tUSER2 on tUSER2.user_id=tDATA.datensaetze_geaendert_von";
		/*
		INNER JOIN abfrage für Wertelisten ergänzen
		*/
		$i=0;
		foreach ($spalten as $key => $value) {
			if ($spaltenAnzahlWerte[$key] > 0) {
				/*
				Wenn eine Werteliste vorhanden ist:
				*/
				$i=$i+1;
				$nWerte="tWERTE_".$key;
				$abfrage=$abfrage." INNER JOIN DMS_werteliste as ".$nWerte." ON tMETA.".$key."=".$nWerte.".werteliste_id"; 
			}
		}
		return $abfrage;
	}
	
	function selectDistinctAbfrageSpalte($spalteX) {
		global $whereClause;		
		/*
		Select Abfrage aufbauen		
		*/		
		$abfrage="SELECT DISTINCT tWERTE.werteliste_wert AS werte, tWERTE.werteliste_id as";

		
		$abfrage=$abfrage." FROM DMS_datensaetze AS tDATA
						INNER JOIN DMS_metadaten as tMETA ON tMETA.datensaetze_id=tDATA.datensaetze_id
						INNER JOIN DMS_werteliste as tWERTE ON tWERTE.werteliste_id=tMETA.".$spalteX;
		$abfrage=$abfrage." ".$whereClause;
		return $abfrage;
	}	
	
	function getFilenameByID($id) {
		foreach (glob("upload/".$id.".*") as $filename) {
	    	return $filename;
		}
	}
?>