<?php
	if (!isset($_POST["eingabetyp"])) {
		$_POST["eingabetyp"]="searchEntry";
	}

	/*
	neue ID ermitteln
	*/
	$abfrage="SELECT MAX(datensaetze_id)+1 FROM DMS_datensaetze";
	$resultat = $mysqli->query($abfrage);
	$daten = $resultat->fetch_array();
	$maxID=$daten["MAX(datensaetze_id)+1"];
	if($maxID=="") {
		$maxID=0;
	}
	/*
	Variablen initialisieren
	*/
	$insertField_data="INSERT INTO `DMS_datensaetze` (`datensaetze_id`, `datensaetze_angelegt_von`,`datensaetze_geaendert_von`";
	$insertValue_data=" VALUES (".$maxID.",180,179";
	$insertField_meta="INSERT INTO `DMS_metadaten`(`datensaetze_id`,";
	$insertValue_meta=" VALUES (".$maxID.",";
	$whereClause="WHERE ";
	$updateClause="UPDATE DMS_metadaten SET ";

	/*	
	File Upload ausführen
	*/
	if (isset($dokumentspalte)==false) {
		$erfolg=true;
	}
	if (($_POST["eingabetyp"]=="addEntry") AND (isset($dokumentspalte))) {
		$erfolg=false;
		$path_parts=pathinfo($_FILES['userfile']['name']);	
		$datei="upload/".$maxID.".".$path_parts['extension'];
		if (file_exists($datei)==false) {				
			if(is_uploaded_file($_FILES['userfile']['tmp_name'])){
				if (move_uploaded_file($_FILES['userfile']['tmp_name'], $datei)==true) {
					$erfolg=true;
				}
			}
			if ($erfolg==true) {
				echo "<div data-alert class=\"alert-box success radius\">";
		  			echo "Upload erfolgreich";
		  			echo "<a href=\"#\" class=\"close\">&times;</a>";
				echo "</div>";		
			} else {
				echo "<div data-alert class=\"alert-box alert radius\">";
		  			echo "Upload fehlgeschlagen !!";
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

	/*	
	File löschen
	*/
	if ($_POST["eingabetyp"]=="delEntry") {
		$editID=$_POST["editID"];		
		$datei=getFilenameByID($editID);
		$dateiNeu=$datei.".del";
		if (file_exists($datei)==true) {				
			if (rename($datei,$dateiNeu)==true) {
				echo "<div data-alert class=\"alert-box success radius\">";
		  			echo "Datei umbenannt in: ".$dateiNeu;
					echo "<br>Löschen erfolgreich abgeschlossen".$dateiNeu;
		  			echo "<a href=\"#\" class=\"close\">&times;</a>";
				echo "</div>";
				$abfrage="DELETE FROM `DMS_datensaetze` WHERE `datensaetze_id`=".$editID;
				$mysqli->query($abfrage);
				$abfrage="DELETE FROM `DMS_metadaten` WHERE `datensaetze_id`=".$editID;
				$mysqli->query($abfrage);
			} else {
				echo "<div data-alert class=\"alert-box alert radius\">";
		  			echo "konnte die Datei nicht umbenennen. Löschen abgebrochen !!";
		  			echo "<a href=\"#\" class=\"close\">&times;</a>";
				echo "</div>";	
			}
		}
	}

	/*
	Eintrag suchen (WHERE ...)
	*/
	foreach ($suchOptionen as $key => $value) {
		if ($key!="eingabetyp" AND $value<>"" AND isset($spalten[$key])) {			
			/*			
			Suche vorbereiten
			*/
			switch($spalten[$key]) {
				case "timestamp":					
					$whereDatum="";
					if(strpos($value, "-") > 0) {
						/*
						Es wird nach einem Zeitbereich gesucht
						*/
						$datumX=explode("-",$value);
						$datumX[0]=date_create_from_format("d.m.Y" ,$datumX[0]);						
						$datumX[0]=date_format($datumX[0], 'Y-m-d');
						$datumX[1]=date_create_from_format("d.m.Y" ,$datumX[1]);
						$datumX[1]=date_format($datumX[1], 'Y-m-d');																		
						$whereDatum=" ".$tabellenPrefix[$key].$key." >= TIMESTAMP('".$datumX[0]."') AND ".$tabellenPrefix[$key].$key." <= TIMESTAMPADD(DAY,1,'".$datumX[1]."') AND";
					} else {
						/*
						Es wird nach einem gebauen Datum gesucht
						*/
						$datumX=date_create_from_format("d.m.Y" ,$value);
						$datumX=date_format($datumX, 'Y-m-d');
						$whereDatum=" ".$tabellenPrefix[$key].$key." >= TIMESTAMP('".$datumX."') AND ".$tabellenPrefix[$key].$key." < TIMESTAMPADD(DAY,1,'".$datumX."') AND";
					}
					$whereClause=$whereClause.$whereDatum;
					break;
				case "date":
					$datumX=explode("-",$value);
					$whereDatum="";
					
					if (date_create_from_format('d.m.Y', $datumX[0]) > date_create_from_format('d.m.Y', '01.01.0000')){
						$whereDatum=" ".$tabellenPrefix[$key].$key." >= STR_TO_DATE('".$datumX[0]."','%d.%m.%Y') AND";
					}
					if (date_create_from_format('d.m.Y', $datumX[1]) > date_create_from_format('d.m.Y', '01.01.0000')){
						$whereDatum=$whereDatum." ".$tabellenPrefix[$key].$key." <= STR_TO_DATE('".$datumX[1]."','%d.%m.%Y') AND";
					} else {
						$whereDatum=" ".$tabellenPrefix[$key].$key." = STR_TO_DATE('".$datumX[0]."','%d.%m.%Y') AND";
					}
					$whereClause=$whereClause.$whereDatum;
					break;					
				case (preg_match('/char.*/', $spalten[$key]) ? true : false) :
					$whereClause=$whereClause." ".$tabellenPrefix[$key].$key." LIKE \"%".$value."%\" AND";
					break;
				case (preg_match('/varchar.*/', $spalten[$key]) ? true : false) :
					$whereClause=$whereClause." ".$tabellenPrefix[$key].$key." LIKE \"%".$value."%\" AND";
					break;					
				case (preg_match('/int.*/', $spalten[$key]) ? true : false) :
					$whereClause=$whereClause." ".$tabellenPrefix[$key].$key.$value." AND";
					break;
				case (preg_match('/decimal.*/', $spalten[$key]) ? true : false) :
					$whereClause=$whereClause." ".$tabellenPrefix[$key].$key.$value." AND";
					break;
				default:
					$whereClause=$whereClause." ".$tabellenPrefix[$key].$key." LIKE %".$value."% AND";
					break;
			}
		}
	}
	foreach ($_POST as $key => $value) {
		/*
		neuer Eintrag (INSERT INTO ...)
		*/
		if ($_POST["eingabetyp"]=="addEntry" AND $key!="eingabetyp" AND $value<>"") {
			// Suchergebnisse in den Übergabeparamtern
			switch($tabellenPrefix[$key]) { // Auswerten ob Daten aus der Tabelle metadaten oder datensätze übergeben werden
				case "tMETA.":
					switch($spalten[$key]) { //Spaltentyp auswerten
						case "date": case "timestamp":					
							$insertField_meta=$insertField_meta."`".$key."`,"; 											
							$insertValue_meta =$insertValue_meta."STR_TO_DATE(\"".$value."\", \"%d.%m.%Y\"),";
							break;
						case (preg_match('/char.*/', $spalten[$key]) ? true : false) :
							$insertField_meta=$insertField_meta."`".$key."`,"; 											
							$insertValue_meta =$insertValue_meta."\"".$value."\",";		
							break;
						case (preg_match('/varchar.*/', $spalten[$key]) ? true : false) :
							$insertField_meta=$insertField_meta."`".$key."`,"; 											
							$insertValue_meta =$insertValue_meta."\"".$value."\",";		
							break;							
						case (preg_match('/int.*/', $spalten[$key]) ? true : false):
							$insertField_meta=$insertField_meta."`".$key."`,";
							$insertValue_meta =$insertValue_meta."".$value.",";
							break;
						case (preg_match('/decimal.*/', $spalten[$key]) ? true : false) :
							$insertField_meta=$insertField_meta."`".$key."`,";
							$insertValue_meta =$insertValue_meta."".str_replace(",", ".",$value).",";
							break;
					}
				break;
			}
		}

		/*
		Eintrag ändern (UPDATE ...)
		*/
		if ($_POST["eingabetyp"]=="editEntry" AND $key!="eingabetyp" AND $value<>"") {
			switch($tabellenPrefix[$key]) { // Auswerten ob Daten aus der Tabelle metadaten oder datensätze übergeben werden
				case "tMETA.":
					switch($spalten[$key]) { //Spaltentyp auswerten					
						case "date": case "timestamp":
							$updateClause=$updateClause.$key."=STR_TO_DATE(\"".$value."\", \"%d.%m.%Y\"),";
							break;
						case (preg_match('/char.*/', $spalten[$key]) ? true : false) :
							$updateClause=$updateClause.$key."=\"".$value."\",";
							break;
						case (preg_match('/varchar.*/', $spalten[$key]) ? true : false) :
							$updateClause=$updateClause.$key."=\"".$value."\",";
							break;
						case (preg_match('/int.*/', $spalten[$key]) ? true : false):
							$updateClause=$updateClause.$key."=\"".$value."\",";
							break;
						case (preg_match('/decimal.*/', $spalten[$key]) ? true : false) :
							$updateClause=$updateClause.$key."=".str_replace(",", ".",$value).",";
							break;
					}
				break;
			}
		}
	}

	/*
	Zeichenketten aufbereiten
	*/
	$whereClause=rtrim($whereClause, "AND");
	$insertValue_data=rtrim($insertValue_data, ",");
	$insertField_data=rtrim($insertField_data, ",");
	$insertValue_data=$insertValue_data.")";
	$insertField_data=$insertField_data.")";
	
	$insertValue_meta=rtrim($insertValue_meta, ",");
	$insertField_meta=rtrim($insertField_meta, ",");
	$insertValue_meta=$insertValue_meta.")";
	$insertField_meta=$insertField_meta.")";	
	
	$whereClause=rtrim($whereClause, "AND");
	$insert_data=$insertField_data.$insertValue_data;
	$insert_meta=$insertField_meta.$insertValue_meta;	
	if ($whereClause=="WHERE ") {
		$whereClause="";
	}
	
	$updateClause=rtrim($updateClause, ",");

	/*
	Eintra ändern
	*/	
	if ($_POST["eingabetyp"]=="editEntry") {
		/*
		Metadaten update
		*/
		$updateClause=$updateClause." WHERE datensaetze_id=".$_POST["editID"];
		$mysqli->query($updateClause);
		/*
		Verwaltungsdaten update
		*/
		$updateClause="UPDATE DMS_datensaetze SET datensaetze_geaendert_am=NOW() WHERE datensaetze_id=".$_POST["editID"];
		$mysqli->query($updateClause);
		$_POST["eingabetyp"]="searchEntry";
	}


	/*
	Neue Einträge einfügen
	*/	
	if ($_POST["eingabetyp"]=="addEntry" and $erfolg==true) {
		$mysqli->query($insert_data);
		$mysqli->query($insert_meta);
		echo "<br>".$insert_data."<br>";
		echo "<br>".$insert_meta."<br>";
	}

?>
