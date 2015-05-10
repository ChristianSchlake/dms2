<?php
	$speicherpfad="/media/texte/dms_NEU/";
	$dokumentspalte="metadaten_spalte_5"; //muss eine Spalte vom Typ String sein aus der Tabelle DMS_metadaten!
	$leftNavigation="metadaten_spalte_3"; //Werteliste für die linke navigation (Hierarchische Liste)
	$rightNavigation="metadaten_spalte_4"; //Werteliste für die linke navigation (flache Liste)
	$maxEintraegeProSite=10; // Maximale Anzahl an Einträgen die pro Seite angezeigt werden.
	$showAlertOnResetSession=0; // einstellen ob die Alert Meldung beim reset der Session angezeigt werden soll. Mögliche Werte sind 0 oder 1

	/*
	Spaltenbreiten
	*/
	$spaltenBreite["tDATA_datensaetze_id"]=				"small-12 medium-2 large-2";
	$spaltenBreite["tDATA_datensaetze_angelegt_von"]=	"small-6 medium-6 large-3";
	$spaltenBreite["tDATA_datensaetze_angelegt_am"]=	"small-6 medium-4 large-2";
	$spaltenBreite["tDATA_datensaetze_geaendert_von"]=	"small-6 medium-6 large-3";
	$spaltenBreite["tDATA_datensaetze_geaendert_am"]=	"small-6 medium-6 large-2";
	
	$spaltenBreite["tMETA_metadaten_spalte_1"]=			"small-12 medium-12 large-5"; 			// Beschreibung
	$spaltenBreite["tMETA_metadaten_spalte_2"]=			"small-6 medium-4 large-5"; 			// Ausgabedatum

	$spaltenBreite["tMETA_metadaten_spalte_3"]=			"small-6 medium-4 large-3"; 			// Ordner
	$spaltenBreite["tMETA_metadaten_spalte_4"]=			"small-6 medium-4 large-4"; 			// Herausgeber
//	$spaltenBreite["tMETA_metadaten_spalte_5"]=			"small-6 medium-6 large-2"; 			// Dateiname
	$spaltenBreite["tMETA_metadaten_spalte_6"]=			"small-6 medium-6 large-2"; 			// Typ
	
	$spaltenBreite["download"]=								"small-6 medium-6 large-1"; 			// Download Button
	$spaltenBreite["view"]=										"small-6 medium-6 large-1"; 			// View Button
	$spaltenBreite["delete"]=									"small-6 medium-6 large-1"; 			// Delete Button	
	
	/*
	Sichtbarkeit der Spalten einstellen
	*/
	$spaltenSichtbarkeit["tDATA_datensaetze_id"]=				1;
	$spaltenSichtbarkeit["tDATA_datensaetze_angelegt_von"]=	0;
	$spaltenSichtbarkeit["tDATA_datensaetze_angelegt_am"]=	0;
	$spaltenSichtbarkeit["tDATA_datensaetze_geaendert_von"]=	0;
	$spaltenSichtbarkeit["tDATA_datensaetze_geaendert_am"]=	0;
	
	$spaltenSichtbarkeit["tMETA_metadaten_spalte_1"]=			1; 			// Beschreibung
	$spaltenSichtbarkeit["tMETA_metadaten_spalte_2"]=			1; 			// Ausgabedatum
	$spaltenSichtbarkeit["tMETA_metadaten_spalte_3"]=			1; 			// Ordner
	$spaltenSichtbarkeit["tMETA_metadaten_spalte_4"]=			1; 			// Herausgeber
	$spaltenSichtbarkeit["tMETA_metadaten_spalte_5"]=			0; 			// Dateiname
	$spaltenSichtbarkeit["tMETA_metadaten_spalte_6"]=			1; 			// Typ
?>