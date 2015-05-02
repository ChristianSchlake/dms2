<?php
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
	$aTMP aufbauen
	*/			
	$suchOptionen=$_SESSION["suchOptionen"];	
	
	//print_r($_POST);	
	
	if ($_POST["eingabetyp"]=="searchEntry") {
		foreach ($_POST as $key => $value) {
			$suchOptionen[$key]=$value;
		}
		foreach ($suchOptionen as $key => $value) {
			$aTMP[$key]=$value;
		}							
	} else {
		foreach ($_POST as $key => $value) {
			$aTMP[$key]=$value;
		}
	}
	$_SESSION["suchOptionen"]=$suchOptionen;

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
?>