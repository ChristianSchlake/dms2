<?php
	function get_spaltenEigenschaft() {
		global $spalten, $spaltenNamen, $mysqli, $tabellenPrefix, $tabellenPrefixShort, $spaltenAnzahlWerte;
		$spalten = array();
		// Default Feste spalten eintragen (aus der Tabelle DMS_datensaetze)
		if ($resultat = $mysqli->query("SHOW FULL COLUMNS FROM DMS_datensaetze ")) {
			while($daten = $resultat->fetch_object() ){
				$spalten[$daten->Field] = $daten->Type;
				$spaltenNamen[$daten->Field] = $daten->Comment;
				$tabellenPrefix[$daten->Field]="tDATA.";
				$tabellenPrefixShort[$daten->Field]="tDATA_";
				$spaltenAnzahlWerte[$daten->Field]=Anzahl_eintraege_Werteliste($daten->Field);								
			}  
			$resultat->close();
		};
		if ($resultat = $mysqli->query("SHOW FULL COLUMNS FROM DMS_metadaten WHERE Field Like 'metadaten_spalte%'")) {
			while($daten = $resultat->fetch_object() ){
				$spalten[$daten->Field] = $daten->Type;
				$spaltenNamen[$daten->Field] = $daten->Comment;
				$tabellenPrefix[$daten->Field]="tMETA.";
				$tabellenPrefixShort[$daten->Field]="tMETA_";
				$spaltenAnzahlWerte[$daten->Field]=Anzahl_eintraege_Werteliste($daten->Field);				
			}  
			$resultat->close();
		};
	}

	function generateListOrdner($root_id = 0, $stufe = 0, $spaltenNameX, $eingabetyp, $selected) {
		global $mysqli;
		
		$abfrage="SELECT DISTINCT * FROM DMS_werteliste WHERE werteliste_vater_id = ".$root_id." AND `werteliste_metadaten_spaltenName`=\"".$spaltenNameX."\" ORDER BY werteliste_wert";
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

	function generateListOrdner_leftNavigation($root_id = 0, $stufe = 0, $spaltenNameX, $nr) {
		global $mysqli, $leftNavigation;
		$abfrage="SELECT DISTINCT * FROM DMS_werteliste WHERE werteliste_vater_id = ".$root_id." AND `werteliste_metadaten_spaltenName`=\"".$spaltenNameX."\" ORDER BY werteliste_wert";
		//$einrueckung = str_repeat ("....", $stufe);
		if ($resultat = $mysqli->query($abfrage)) {
			while($daten = $resultat->fetch_object() ){
				$nr=$nr+1;								
				$echoWert = "<option value=\"=".$daten->werteliste_id."\">".$daten->werteliste_wert."</option>";
				$kinder=Anzahl_Kinder_Werteliste($spaltenNameX, $daten->werteliste_id);
				if($kinder > 0) {
					echo "<li class=\"has-submenu\"><a href=\"#\">".$daten->werteliste_wert."</a>";
						echo "<ul class=\"left-submenu\">";
							echo "<li class=\"back\"><a href=\"#\">Back</a></li>";
							echo "<li><label>".$daten->werteliste_wert."</label></li>";
							generateListOrdner_leftNavigation($daten->werteliste_id, $stufe+1, $spaltenNameX, $nr+1);
						echo "</ul>";
					echo "</li>";
				} else {
					echo "<form id=\"Form".$daten->werteliste_id."\" action=\"main_suche.php\" method=\"POST\" class=\"custom\">";
							echo "<li><a href=\"#\" onclick=\"Form".$daten->werteliste_id.".submit()\">".$daten->werteliste_wert."</a></li>";
							echo "<input type=\"hidden\" value=\"=".$daten->werteliste_id."\" name=\"".$leftNavigation."\">";
							echo "<input type=\"hidden\" value=\"searchEntry\" name=\"eingabetyp\">";
					echo "</form>";
				}
			}
		}
	}

	function generateListOrdner_List($root_id = 0, $stufe = 0, $spaltenNameX, $nr) {
		global $mysqli, $leftNavigation;
		$abfrage="SELECT DISTINCT * FROM DMS_werteliste WHERE werteliste_vater_id = ".$root_id." AND `werteliste_metadaten_spaltenName`=\"".$spaltenNameX."\" ORDER BY werteliste_wert";
		$einrueckung = str_repeat ("....", $stufe);
		if ($resultat = $mysqli->query($abfrage)) {
			while($daten = $resultat->fetch_object() ){
				$nr=$nr+1;
				$anzahl=Anzahl_Dokumente_in_DMS($spaltenNameX, $daten->werteliste_id);
				$kinder=Anzahl_Kinder_Werteliste($spaltenNameX, $daten->werteliste_id);
				echo "<tr>";
      			echo "<td>".$daten->werteliste_id."</td>";
      			echo "<td>".$einrueckung.$einrueckung.$daten->werteliste_wert."</td>";
      			echo "<td>".$anzahl."</td>";					
					echo "<td>";
						if ($anzahl == 0 and $kinder ==0 ) {
							echo "<form action=\"sub_edit_list.php\" method=\"POST\" class=\"custom\">";
								echo "<button class=\"button tiny secondary round\" type=\"Submit\"><i class=\"fi-x-circle\"></i></button>";
								echo "<input type=\"hidden\" value=\"".$daten->werteliste_id."\" name=\"deleteID\">";
								echo "<input type=\"hidden\" value=\"delEntryWert\" name=\"eingabetyp\">";							
								echo "<input type=\"hidden\" value=\"".$spaltenNameX."\" name=\"Auswahllisten\">";
							echo "</form>";
						}
					echo "</td>";
    			echo "</tr>";
				echo $echoWert;
				if($kinder > 0) {
					generateListOrdner_List($daten->werteliste_id, $stufe+1, $spaltenNameX, $nr+1);
				}
			}
		}
	}	

	function generateListOrdner_rightNavigation($root_id = 0, $stufe = 0, $spaltenNameX, $nr) {
		global $mysqli, $rightNavigation;
		$abfrage="SELECT DISTINCT * FROM DMS_werteliste WHERE werteliste_vater_id = ".$root_id." AND `werteliste_metadaten_spaltenName`=\"".$spaltenNameX."\" ORDER BY werteliste_wert";
		//$einrueckung = str_repeat ("....", $stufe);
		//echo $abfrage;
		if ($resultat = $mysqli->query($abfrage)) {
			while($daten = $resultat->fetch_object() ){
				$nr=$nr+1;								
				$echoWert = "<option value=\"=".$daten->werteliste_id."\">".$daten->werteliste_wert."</option>";
				$kinder=Anzahl_Kinder_Werteliste($spaltenNameX, $daten->werteliste_id);
				if($kinder > 0) {
					echo "<li class=\"has-submenu\"><a href=\"#\">".$daten->werteliste_wert."</a>";
						echo "<ul class=\"right-submenu\">";
							echo "<li class=\"back\"><a href=\"#\">Back</a></li>";
							echo "<li><label>".$daten->werteliste_wert."</label></li>";
							generateListOrdner_rightNavigation($daten->werteliste_id, $stufe+1, $spaltenNameX, $nr+1);
						echo "</ul>";
					echo "</li>";
				} else {
					echo "<form id=\"FormRIGHT".$daten->werteliste_id."\" action=\"main_suche.php\" method=\"POST\" class=\"custom\">";
							echo "<li><a href=\"#\" onclick=\"FormRIGHT".$daten->werteliste_id.".submit()\">".$daten->werteliste_wert."</a></li>";
							echo "<input type=\"hidden\" value=\"=".$daten->werteliste_id."\" name=\"".$rightNavigation."\">";
							echo "<input type=\"hidden\" value=\"searchEntry\" name=\"eingabetyp\">";
					echo "</form>";
				}
			}
		}
	}

	function generateListOrdner_rightNavigationOLD($spaltenNameX) {
		global $mysqli, $rightNavigation;
		
		$abfrage=selectDistinctAbfrageSpalte($spaltenNameX);		
		if ($resultat = $mysqli->query($abfrage)) {
			while($daten = $resultat->fetch_object() ){
				echo "<form id=\"FormRight".$daten->id."\" action=\"main_suche.php\" method=\"POST\" class=\"custom\">";
						echo "<li><a href=\"#\" onclick=\"FormRight".$daten->id.".submit()\">".$daten->werte."</a></li>";
						echo "<input type=\"hidden\" value=\"=".$daten->id."\" name=\"".$rightNavigation."\">";
						echo "<input type=\"hidden\" value=\"searchEntry\" name=\"eingabetyp\">";
				echo "</form>";
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
	
	function Anzahl_Dokumente_in_DMS($spaltenNameX, $wertX) {
		global $mysqli;
		/*
		Gibt die Anzahl an Kindereinträgen für die übergebene Spalte zurück
		*/
		$zahl = 0;
		$abfrage = "SELECT * FROM `DMS_metadaten` WHERE ".$spaltenNameX."=".$wertX;
		if ($resultat = $mysqli->query($abfrage)) {
			$zahl = mysqli_num_rows($resultat);
		}
		return $zahl;		
	}		
	
	
	function selectAbfrage() {
		global /*$abfrage,*/ $spalten, $tabellenPrefix, $tabellenPrefixShort, $spaltenAnzahlWerte;
		
		/*
		Select Abfrage aufbauen
		*/		
		$abfrage="SELECT ";
		foreach ($spalten as $key => $value) {
			if($spaltenAnzahlWerte[$key]>0) {
				/*
				Spalte mit Werteliste
				*/
				$joinTabelle="tWERTE_".$tabellenPrefixShort[$key].$key;
				$abfrage=$abfrage.",".$joinTabelle.".werteliste_wert AS ".$joinTabelle;
				$abfrage=$abfrage.",".$tabellenPrefix[$key].$key;
			} else {
				/*
				Spalte ohne Werteliste
				*/
				$joinSpaltenName=$tabellenPrefixShort[$key].$key;
				switch($spalten[$key]) {
					case "timestamp":
						$abfrage=$abfrage.",DATE_FORMAT(".$tabellenPrefix[$key].$key.", GET_FORMAT(DATE, 'EUR')) AS '".$joinSpaltenName."'";
						break;
					case "date":
						$abfrage=$abfrage.",DATE_FORMAT(".$tabellenPrefix[$key].$key.", GET_FORMAT(DATE, 'EUR')) AS '".$joinSpaltenName."'";
						break;
					default:
						$abfrage=$abfrage.",".$tabellenPrefix[$key].$key. " AS ".$joinSpaltenName;
						break;
				}
			}					
		}
		$abfrage=$abfrage." FROM DMS_datensaetze AS tDATA
						INNER JOIN DMS_metadaten as tMETA ON tMETA.datensaetze_id=tDATA.datensaetze_id";		
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
				$joinTabelle="tWERTE_".$tabellenPrefixShort[$key].$key;
				$abfrage=$abfrage." INNER JOIN DMS_werteliste as ".$joinTabelle." ON ".$tabellenPrefix[$key].$key."=".$joinTabelle.".werteliste_id";
			}
		}
		$abfrage="SELECT ".substr($abfrage, stripos($abfrage,",")+1);
		return $abfrage;
	}
	
	function getFilenameByID($id) {
		foreach (glob("upload/".$id.".*") as $filename) {
	    	return $filename;
		}
	}

	function selectDistinctAbfrageSpalte($spalteX) {
		global $whereClause;		
		/*
		Select Abfrage aufbauen		
		*/		
		$abfrage="SELECT DISTINCT tWERTE.werteliste_wert AS werte, tWERTE.werteliste_id AS id";

		
		$abfrage=$abfrage." FROM DMS_datensaetze AS tDATA
						INNER JOIN DMS_metadaten as tMETA ON tMETA.datensaetze_id=tDATA.datensaetze_id
						INNER JOIN DMS_werteliste as tWERTE ON tWERTE.werteliste_id=tMETA.".$spalteX." 
						ORDER BY werte";
		$abfrage=$abfrage." ".$whereClause;
		return $abfrage;
	}	
?>
