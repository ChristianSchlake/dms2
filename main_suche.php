<?php
	session_start();	
	include("conf.php");
	include("sub_init_database.php");
	include("functions.php");
	get_spaltenEigenschaft();	
	include("sub_get_auswerten.php");
	
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
					<li><label>Users</label></li>
					<li><a href="#">Hari Seldon</a></li>
					<li class="has-submenu"><a href="#">R. Giskard Reventlov</a>
						<ul class="right-submenu">
							<li class="back"><a href="#">Back</a></li>
							<li><label>Level 1</label></li>
							<li><a href="#">Link 1</a></li>
							<li class="has-submenu"><a href="#">Link 2 w/ submenu</a>
								<ul class="right-submenu">
									<li class="back"><a href="#">Back</a></li>
									<li><label>Level 2</label></li>
									<li><a href="#">...</a></li>
								</ul>
							</li>
							<li><a href="#">...</a></li>
						</ul>
					</li>
					<li><a href="#">...</a></li>
				</ul>
    		</aside>
		<section class="main-section">
				<?php
					/*
					Tabellenüberschriften aufbauen
					*/		
					echo"<table>";
						echo "<thead>";
							echo "<tr>";
									echo "<th>ID</th>";
									echo "<th>angelegt von</th>";
									echo "<th>angelegt am</th>";
									echo "<th>geändert von</th>";
									echo "<th>geändert am</th>";
									foreach ($spaltenNamen as $key => $value) {
										if ($tabellenPrefix[$key]=="tMETA.") {
											echo "<th>".$value."</th>";
										}
									}
									echo "<th></th>";
							echo "</tr>";
						echo "</thead>";
						echo "<tbody>";
						/*
						Abfrage abschicken
						Tabellenkörper aufbauen
						*/
						$abfrage=selectAbfrage();									
						$abfrage=$abfrage." ".$whereClause;
						if ($resultat = $mysqli->query($abfrage)) {
							$menge = mysqli_num_rows($resultat);
							if ($menge<$startPage) {
								$startPage=0;				
							}
						}
						$abfrage=$abfrage." LIMIT ".$startPage.", ".$maxEintraegeProSite;
//						echo $abfrage;
						if ($resultat = $mysqli->query($abfrage)) {								
							while($daten = $resultat->fetch_object() ){					
								echo "<tr>";
									echo "<td>".$daten->ID."</td>";
									echo "<td>".$daten->AngelegtVon."</td>";
									echo "<td>".$daten->AngelegtAm."</td>";
									echo "<td>".$daten->geaendertVon."</td>";
									echo "<td>".$daten->geaendertAm."</td>";
									foreach ($spalten as $key => $value) {
										if ($tabellenPrefix[$key]=="tMETA.") {
											if ($spaltenAnzahlWerte[$key] > 0) {
												/*
												Spalten mit Wertelise
												*/
												$nWerte="tWERTE_".$key;
												echo "<td>".$daten->$nWerte."</td>"; 
											} else {
												/*
												Spalten ohne Wertelise
												*/
												echo "<td>".$daten->$key."</td>";
											}
										}
									}
									echo "<td>";						
										echo "<form action=\"sub_eingabemaske.php\" method=\"POST\" class=\"custom\">";
											echo "<div class=\"row collapse\"></div>";
												echo "<button class=\"button tiny secondary round\" type=\"Submit\"><i class=\"fi-page-edit\"></i></button>";
												echo "<input type=\"hidden\" value=\"".$daten->ID."\" name=\"editID\">";
												echo "<input type=\"hidden\" value=\"editEntry\" name=\"eingabetyp\">";
				  							echo "</div>";
				  						echo "</form>";
				  					echo "</td>";
				  					echo "<td>";
										echo "<form action=\"sub_open_file_by_id.php\" method=\"POST\" class=\"custom\">";
											echo "<div class=\"row collapse\"></div>";
												echo "<button class=\"button tiny secondary round\" type=\"Submit\"><i class=\"fi-download\"></i></button>";
												echo "<input type=\"hidden\" value=\"".$daten->ID."\" name=\"id\">";
				  							echo "</div>";
				  						echo "</form>";
				  					echo "</td>";
								echo "</tr>";					
							}  
							$resultat->close();
						}
						echo "</tbody>";
					echo "</table>";
				?>
			</section>
			<a class="exit-off-canvas"></a>
		</div>
	</div>
	<div class="row">
		<div class="pagination-centered">
			<ul class="pagination">		
				<?php
					//echo "<li class=\"arrow\"><a href=\"main_suche.php?startPage=",$startPage-$maxEintraegeProSite,"\">&laquo;</a></li>";
					for ($i=0; $i < $menge; $i=$i+$maxEintraegeProSite) { 								
						if($i>=$startPage and $i <$startPage+$maxEintraegeProSite){
							echo "<li class=\"current\"><a href=\"main_suche.php?startPage=",$i,"\">",$i,"</a></li>";
						}
						else{
							echo "<li><a href=\"main_suche.php?startPage=",$i,"\">",$i,"</a></li>";
						}
					}
					//echo "<li class=\"arrow\"><a href=\"main_suche.php?startPage=",$startPage+$maxEintraegeProSite,"\">&raquo;</a></li>";							
				?>
			</ul>
		</div>
	</div>	
	
	<!-- Formular "Suche" -->
	<div id="suchModal" class="reveal-modal" data-reveal>
		<?php
			$eingabetyp="searchEntry";
			include("sub_eingabemaske.php");
		?>
	</div>
	
	<!--Formular "neuer Eintrag"-->
	<div id="eingabeModal" class="reveal-modal" data-reveal>
		<?php
			$eingabetyp="addEntry";
			include("sub_eingabemaske.php");
		?>
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