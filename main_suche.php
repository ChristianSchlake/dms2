<?php
	session_start();	
	include("conf.php");
	include("sub_init_database.php");
	include("functions.php");
	get_spaltenEigenschaft();		
	include("sub_get_session.php");
	include("sub_get_auswerten.php");
/*
	echo "<br>Suchoptionen: <br>";
	print_r($suchOptionen);
	echo "<br>POST: <br>";
	print_r($_POST);
*/
		
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
	?>

		
	<div class="off-canvas-wrap" data-offcanvas>
		<div class="inner-wrap">
			<nav class="tab-bar">
				<section class="left-small">
					<a class="left-off-canvas-toggle menu-icon" ><span></span></a>
				</section>
				<section class="right-small">
					<a class="right-off-canvas-toggle menu-icon" ><span></span></a>
				</section>			
			</nav>
			
			<aside class="left-off-canvas-menu">
				<ul class="off-canvas-list">
					<?php
						echo "<li><label>".$spaltenNamen[$leftNavigation]."</label></li>";					
						generateListOrdner_leftNavigation(0,0,$leftNavigation, 0);
					?>
				</ul>
			</aside>

			<aside class="right-off-canvas-menu">
				<ul class="off-canvas-list">
					<?php
						echo "<li><label>".$spaltenNamen[$rightNavigation]."</label></li>";
						generateListOrdner_rightNavigation(0,0,$rightNavigation, 0);
						//generateListOrdner_rightNavigation($rightNavigation);
					?>
				</ul>
    		</aside>
		<section class="main-section">			
				<?php
					/*
					Tabellenüberschriften aufbauen
					*/
					echo "<div class=\"row\">";
						foreach ($spaltenNamen as $key => $value) {
							$nValue=$tabellenPrefixShort[$key].$key;
							if (isset($spaltenBreite[$nValue])) {
								if (isset($spaltenBreite[$nValue])) {
									echo "<div class=\"".$breite=$spaltenBreite[$nValue]." columns\">";
								} else {
									echo "<div class=\"small-12 medium-6 large-3 columns\">";
								}
									echo "<form action=\"main_suche.php\" method=\"POST\" class=\"custom\">";
										// Prüfen ob die aktuelle spalte die jenige ist nach der aktuell sortiert wird (auch die Spalten mit Werteliste prüfen)
										if ($sortierSpalte==$key OR $sortierSpalte=="tWERTE_".$tabellenPrefixShort[$key].$key) {
											if ($sortierung=="ASC") {
												$value=$value."  <i class=\"fi-arrow-up\"></i>";
											} else {
												$value=$value."  <i class=\"fi-arrow-down\"></i>";												
											}												
											echo "<button class=\"button expand secondary tiny\" type=\"Submit\">".$value."</button>";
										} else {												
											echo "<button class=\"button expand tiny\" type=\"Submit\">".$value."</button>";
										}
										



										if($spaltenAnzahlWerte[$key]>0) {
											/*
											Spalte mit Werteliste
											*/
											$joinTabelle="tWERTE_".$tabellenPrefixShort[$key].$key;
											echo "<input type=\"hidden\" value=\"".$joinTabelle."\" name=\"sortierSpalte\">";
										} else {
											/*
											Spalte ohne Werteliste
											*/
											echo "<input type=\"hidden\" value=\"".$key."\" name=\"sortierSpalte\">";
										}
										
										echo "<input type=\"hidden\" value=\"".$sortierung."\" name=\"sortierung\">";
										echo "<input type=\"hidden\" value=\"nothing\" name=\"eingabetyp\">";
		  							echo "</form>";
		  						echo "</div>";
		  					}
						}
						echo "<HR>";
					echo "</div>";

					/*						
					Abfrage abschicken
					*/
					$abfrage=selectAbfrage();									
					$abfrage=$abfrage." ".$whereClause;
					if ($resultat = $mysqli->query($abfrage)) {
						$menge = mysqli_num_rows($resultat);
						if ($menge<$startPage) {
							$startPage=0;				
						}
					}
					$abfrage=$abfrage." ORDER BY ".$tabellenPrefix[$sortierSpalte].$sortierSpalte." ".$sortierung;
					$abfrage=$abfrage." LIMIT ".$startPage.", ".$maxEintraegeProSite;						
					//echo $abfrage;

					/*
					Inahlt der Tabelle eintragen
					*/
					if ($resultat = $mysqli->query($abfrage)) {
						while($daten = $resultat->fetch_object() ){
							echo "<div class=\"row\">";
								$link="";
								foreach ($spalten as $key => $value) {
									$nValue=$tabellenPrefixShort[$key].$key;
									if ($key==$colURL) {
										//echo "<div class=\"".$breite." columns\"><a href=\"".$link."\"></a>".$daten->$nValue."</div>";
										$link=$daten->$nValue; 
									}									
									if (isset($spaltenBreite[$nValue])) {																												
										$breite=$spaltenBreite[$nValue];
										if ($spaltenAnzahlWerte[$key] > 0) {
											// Spalten mit Wertelise
											$joinSpaltenName="tWERTE_".$tabellenPrefixShort[$key].$key;
											if ($daten->$joinSpaltenName=="") {
												echo "<div class=\"".$breite." columns\">-</div>";
											} else {
												echo "<div class=\"".$breite." columns\">".$daten->$joinSpaltenName."</div>";
											}
										} else {
											// Spalten ohne Wertelise
											if ($daten->$nValue=="") {
												echo "<div class=\"".$breite." columns\">-</div>";
											} else {												
												if ($key==$colURL_Text) {																																							
													echo "<div class=\"".$breite." columns\"><a href=\"".$link."\">".$daten->$nValue."</a></div>"; 
												} else {
													echo "<div class=\"".$breite." columns\">".$daten->$nValue."</div>";
												}
											}
										}
									}
								}
								/*
								View, Download und Delete Button
								*/
								if (isset($spaltenBreite["show"])) {
									$breite=$spaltenBreite["show"];
									echo "<div class=\"".$breite." columns\">";
										echo "<form action=\"sub_eingabemaske.php\" method=\"POST\" class=\"custom\">";
											echo "<button class=\"button tiny secondary round\" type=\"Submit\"><i class=\"fi-page\"></i></button>";
											echo "<input type=\"hidden\" value=\"".$daten->tDATA_datensaetze_id."\" name=\"editID\">";
											echo "<input type=\"hidden\" value=\"showEntry\" name=\"eingabetyp\">";
			  							echo "</form>";
			  						echo "</div>";
								}		  						
								if (isset($spaltenBreite["edit"])) {
									$breite=$spaltenBreite["edit"];
									echo "<div class=\"".$breite." columns\">";
										echo "<form action=\"sub_eingabemaske.php\" method=\"POST\" class=\"custom\">";
											echo "<button class=\"button tiny secondary round\" type=\"Submit\"><i class=\"fi-page-edit\"></i></button>";
											echo "<input type=\"hidden\" value=\"".$daten->tDATA_datensaetze_id."\" name=\"editID\">";
											echo "<input type=\"hidden\" value=\"editEntry\" name=\"eingabetyp\">";
			  							echo "</form>";
			  						echo "</div>";									
								}
								if (isset($spaltenBreite["download"])) {
									$breite=$spaltenBreite["download"];
									echo "<div class=\"".$breite." columns\">";
										echo "<form action=\"sub_open_file_by_id.php\" method=\"POST\" class=\"custom\">";
											echo "<button class=\"button tiny secondary round\" type=\"Submit\"><i class=\"fi-download\"></i></button>";
											echo "<input type=\"hidden\" value=\"".$daten->tDATA_datensaetze_id."\" name=\"id\">";
			  							echo "</form>";
			  						echo "</div>";
								}
								if (isset($spaltenBreite["delete"])) {
									$breite=$spaltenBreite["delete"];
									echo "<div class=\"".$breite." columns\">";
										echo "<form action=\"del_file.php\" method=\"POST\" class=\"custom\">";
											echo "<button class=\"button tiny secondary round\" type=\"Submit\"><i class=\"fi-x-circle\"></i></button>";
											echo "<input type=\"hidden\" value=\"".$daten->tDATA_datensaetze_id."\" name=\"editID\">";
											echo "<input type=\"hidden\" value=\"delEntry\" name=\"eingabetyp\">";
		  								echo "</form>";
		  							echo "</div>";
								}
		  						echo "<HR>";
		  					echo "</div>";													
						}
						$resultat->close();
					}											
				?>
			</section>
			<a class="exit-off-canvas"></a>
		</div>
	</div>
	<div class="row">
		<div class="pagination-centered">
			<ul class="pagination">		
				<?php
					echo "<li class=\"arrow\"><a href=\"main_suche.php?startPage=",$startPage-$maxEintraegeProSite,"\">&laquo;</a></li>";
					for ($i=0; $i < $menge; $i=$i+$maxEintraegeProSite) { 								
						if($i>=$startPage and $i <$startPage+$maxEintraegeProSite){
							echo "<li class=\"current\"><a href=\"main_suche.php?startPage=",$i,"\">",$i,"</a></li>";
						}
						else{
							echo "<li><a href=\"main_suche.php?startPage=",$i,"\">",$i,"</a></li>";
						}
					}
					echo "<li class=\"arrow\"><a href=\"main_suche.php?startPage=",$startPage+$maxEintraegeProSite,"\">&raquo;</a></li>";							
				?>
			</ul>
		</div>
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