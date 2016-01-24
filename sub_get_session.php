<?php
/*
	reset Session
	*/
	if (isset($_GET["resetSession"])) {
		if ($showAlertOnResetSession==1) {		
			echo "<div data-alert class=\"alert-box info radius\">";
	  			echo "Reset Session";
	  			echo "<button tabindex=\"0\" class=\"close\" aria-label=\"Close Alert\">&times;</button>";
	  		echo "</div>";
	  	}
		session_unset();
		unset($suchOptionen);
		unset($aTMP);
	};
	
	/*
	Variablen initialisieren
	*/
	if (!isset($suchOptionen)) {
		$suchOptionen=array();
	}
	if (!isset($aTMP)) {
		$aTMP=array();
	}
	
	/*
	Suchparameter aus POST in Session überschreiben		
	*/
	foreach ($_POST as $key => $value) {		
		if ($value<>"") {
			$suchOptionen[$key]=$value;
			$_SESSION[$key]=$value;			
		}		
	}

	/*
	Suchparameter aus Session lesen		
	*/
	foreach ($_SESSION as $key => $value) {
		if ($value<>"") {
			$suchOptionen[$key]=$value;
			$aTMP[$key]=$value;
		}
	}	

	/*
	Startpage auslesen und in die Session eintragen
	*/
	if (isset($_GET["startPage"])) {
		$_SESSION["startPage"]=$_GET["startPage"];
	} else {
		$startPage=0;
	}	
	if (isset($_SESSION["startPage"])) {
		$startPage=$_SESSION["startPage"];
	} else {
		$startPage=0;
	};

	/*
	sortierreihenfolge ändern
	*/
	if (!isset($_SESSION["sortierung"])) {
		$_SESSION["sortierung"]="DESC";
	}
	if (isset($_POST["sortierSpalte"]) AND isset($_SESSION["sortierSpalte"])) {
		if  ($_POST["sortierSpalte"]==$_SESSION["sortierSpalte"] AND isset($_POST["sortierung"])) {
			if ($_SESSION["sortierung"]=="ASC") {
				$_SESSION["sortierung"]="DESC";
			} else {
				$_SESSION["sortierung"]="ASC";
			}
		}
	} else {
		$_SESSION["sortierung"]="ASC";
	}
	$sortierung=$_SESSION["sortierung"];
	/*
	zu sortierende Spalte auslesen
	*/
	if (isset($_POST["sortierSpalte"])) {
		$_SESSION["sortierSpalte"]=$_POST["sortierSpalte"];
	} else {
		$sortierSpalte="datensaetze_id";
	}
	if (isset($_SESSION["sortierSpalte"])) {
		$sortierSpalte=$_SESSION["sortierSpalte"];
	} else {
		$sortierSpalte="datensaetze_id";
	}	

?>