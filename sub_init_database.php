<?php
	global $mysqli;

	$serverhost="localhost";
	$db_user="somebody";
	$db_pass="eFQVMXuFfyNafJWm";
	$db_database="DMS_NEU";

	// Verbindungs-Objekt samt Zugangsdaten festlegen
	$mysqli = new MySQLi($serverhost,$db_user,$db_pass,$db_database);		

	// Verbindung prüfen --> gegebenfalls exit	
	if (mysqli_connect_errno()) {
	  printf("Verbindung zur MySQL Datenbank fehlgeschlagen: %s\n", mysqli_connect_error()
	  );	 
	  exit;
	}	

	$result = $mysqli->query("SET CHARACTER SET utf8");
	$result = $mysqli->query("SET NAMES utf8");

	header("Content-type:text/html; charset=utf8");
?>
