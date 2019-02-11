<?php
	session_start();
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

<style>

/*
 * CSS3 Treeview. No JavaScript
 * @version 1.0
 * @author Martin Ivanov
 * @url developer's website: http://wemakesites.net/
 * @url developer's twitter: https://twitter.com/#!/wemakesitesnet
 * @url developer's blog http://acidmartin.wordpress.com/
 **/
 
/*
 * This solution works with all modern browsers and Internet Explorer 9+. 
 * If you are interested in purchasing a JavaScript enabler for IE8 
 * for the CSS3 Treeview, please, check this link:
 * http://experiments.wemakesites.net/miscellaneous/acidjs-css3-treeview/
 **/
 
.css-treeview ul,
.css-treeview li
{
    padding: 0;
    margin: 0;
    list-style: none;
}
 
.css-treeview input
{
    position: absolute;
    opacity: 0;
}
 
.css-treeview
{
    font: normal 11px "Segoe UI", Arial, Sans-serif;
    -moz-user-select: none;
    -webkit-user-select: none;
    user-select: none;
}
 
.css-treeview a
{
    color: #00f;
    text-decoration: none;
}
 
.css-treeview a:hover
{
    text-decoration: underline;
}
 
.css-treeview input + label + ul
{
    margin: 0 0 0 22px;
}
 
.css-treeview input ~ ul
{
    display: none;
}
 
.css-treeview label,
.css-treeview label::before
{
    cursor: pointer;
}
 
.css-treeview input:disabled + label
{
    cursor: default;
    opacity: .6;
}
 
.css-treeview input:checked:not(:disabled) ~ ul
{
    display: block;
}
 
.css-treeview label,
.css-treeview label::before
{
    background: url("../pages/css3-treeview/example/icons.png") no-repeat;
}
 
.css-treeview label,
.css-treeview a,
.css-treeview label::before
{
    display: inline-block;
    height: 16px;
    line-height: 16px;
    vertical-align: middle;
}
 
.css-treeview label
{
    background-position: 18px 0;
}
 
.css-treeview label::before
{
    content: "";
    width: 16px;
    margin: 0 22px 0 0;
    vertical-align: middle;
    background-position: 0 -32px;
}
 
.css-treeview input:checked + label::before
{
    background-position: 0 -16px;
}
 
/* webkit adjacent element selector bugfix */

@media screen and (-webkit-min-device-pixel-ratio:0)
{
    .css-treeview 
    {
        -webkit-animation: webkit-adjacent-element-selector-bugfix infinite 1s;
    }
 
    @-webkit-keyframes webkit-adjacent-element-selector-bugfix 
    {
        from 
        { 
            padding: 0;
        } 
        to 
        { 
            padding: 0;
        }
    }
</style>	
	
</head>

<html>


<body>
	<?php
				
		/*
		Daten aus $_POST verarbeiten
		*/
		if (!empty($_POST)) {
			$eingabetyp=$_POST["eingabetyp"];
			$editID=$_POST["editID"];
			$UebergabeParameter=$_POST;
		}		
		/*
		Daten aus $_GET verarbeiten
		*/
		if (!empty($_GET)) {
			$eingabetyp=$_GET["eingabetyp"];
			$editID=$_GET["editID"];
			$UebergabeParameter=$_GET;
		}
		

		switch($eingabetyp){
			case ("showEntry"):
				echo "<div class=\"icon-bar five-up\">";
					echo "<a class=\"item\" href=\"main_suche.php\">";
						echo "<i class=\"fi-arrow-left\"></i>";
//						echo "<label>Home</label>";
					echo "</a>";
					echo "<a class=\"item\" href=\"sub_eingabemaske.php?editID=".$editID."&eingabetyp=editEntry\">";
						echo "<i class=\"fi-page-edit\"></i>";
//						echo "<label>Bearbeiten</label>";
					echo "</a>";
										
					echo "<a class=\"item\" href=\"sub_eingabemaske.php?editID=".$editID."&eingabetyp=addEntry\">";
						echo "<i class=\"fi-page-add\"></i>";
//						echo "<label>Erstellen</label>";
					echo "</a>";
					
					echo "<a class=\"item\" href=\"sub_open_file_by_id.php?id=".$editID."\">";
						echo "<i class=\"fi-download\"></i>";
//						echo "<label>Download</label>";
					echo "</a>";					
					
					echo "<a class=\"item\" href=\"del_file.php?editID=".$editID."&eingabetyp=delEntry\">";
						echo "<i class=\"fi-trash\"></i>";
//						echo "<label>Löschen</label>";
					echo "</a>";						
				echo "</div>";
			break;
		case ("editEntry"):
				echo "<div class=\"icon-bar five-up\">";
					echo "<a class=\"item\" href=\"main_suche.php\">";
						echo "<i class=\"fi-arrow-left\"></i>";
//						echo "<label>Home</label>";
					echo "</a>";
					echo "<a class=\"item\" href=\"sub_eingabemaske.php?editID=".$editID."&eingabetyp=showEntry\">";
						echo "<i class=\"fi-page\"></i>";
//						echo "<label>Anzeigen</label>";
					echo "</a>";
										
					echo "<a class=\"item\" href=\"sub_eingabemaske.php?editID=".$editID."&eingabetyp=addEntry\">";
						echo "<i class=\"fi-page-add\"></i>";
//						echo "<label>Erstellen</label>";
					echo "</a>";
					
					echo "<a class=\"item\" href=\"sub_open_file_by_id.php?id=".$editID."\">";
						echo "<i class=\"fi-download\"></i>";
//						echo "<label>Download</label>";
					echo "</a>";					
					
					echo "<a class=\"item\" href=\"del_file.php?editID=".$editID."&eingabetyp=delEntry\">";
						echo "<i class=\"fi-x-circle\"></i>";
//						echo "<label>Löschen</label>";
					echo "</a>";						
				echo "</div>";			
			break;		
		case ("searchEntry"):
			include("sub_topbar.php");			
		break;
		case ("addEntry"):
			include("sub_topbar.php");			
		break;		
		}
		include("sub_init_database.php");
		include("functions.php");
		include("conf.php");
		get_spaltenEigenschaft();		
		
		/*
		$aTMP manipulieren wenn eine neue ID aufgerufen wird		
		*/
		if (isset($editID)) {
			unset($aTMP);
			$aTMP=array();
			$abfrage=selectAbfrage();
			$abfrage=$abfrage." WHERE tDATA.datensaetze_id=".$editID;
			
			/*
			aTMP mit den werten des gesuchten Objekts füllen
			*/
			if ($resultat = $mysqli->query($abfrage)) {
				$daten = $resultat->fetch_object();
				foreach ($spalten as $key => $value) {							
					if ($spaltenAnzahlWerte[$key] > 0) {
						// Spalten mit Wertelise
						$joinSpaltenName=$key;
						$aTMP[$key]=$daten->$joinSpaltenName;
						if ($tabellenPrefixShort[$key]=="tDATA_") {
							$nValue="tWERTE_".$tabellenPrefixShort[$key].$key;						
							$aTMP[$key]=$daten->$nValue;
						}					
					} else {
						// Spalten ohne Wertelise
						$nValue=$tabellenPrefixShort[$key].$key;
						$aTMP[$key]=$daten->$nValue;
					}
				}
			}
		} else {
			include("sub_get_session.php");	
		}
	?>



	<div class="row">
		<form action="main_suche.php" method="POST" class="custom" enctype="multipart/form-data">
			<div class="row"></div>	
			<?php		
				$morgen = strtotime("+1 day");
				get_spaltenEigenschaft;
		
				/*
				Fester Metadatenstamm für Sucheinträge
				*/	
				if($eingabetyp=="searchEntry" OR $eingabetyp=="showEntry") {					
					echo "<fieldset>";
						echo "<legend>Verwaltungsdaten</legend>";
						echo "<div class=\"large-8 columns\">";
							echo "<label>angelegt von";
								if(isset($aTMP["datensaetze_angelegt_von"])) {
									echo "<input type=\"text\" value=\"".$aTMP["datensaetze_angelegt_von"]."\" name=\"datensaetze_angelegt_von\"/>";
								} else {
									echo "<input type=\"text\" name=\"datensaetze_angelegt_von\"/>";
								}		 				
				 			echo "</label>";
				 		echo "</div>";
						echo "<div class=\"large-4 columns\">";
							echo "<label>angelegt am";
								if(isset($aTMP["datensaetze_angelegt_am"])) {
									echo "<input type=\"text\" value=\"".$aTMP["datensaetze_angelegt_am"]."\" name=\"datensaetze_angelegt_am\"/>";
								} else {
									echo "<input type=\"text\" name=\"datensaetze_angelegt_am\"/>";
								}
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
								if(isset($aTMP["datensaetze_geaendert_am"])) {
									echo "<input type=\"text\" value=\"".$aTMP["datensaetze_geaendert_am"]."\" name=\"datensaetze_geaendert_am\"/>";
								} else {
									echo "<input type=\"text\" name=\"datensaetze_geaendert_am\"/>";
								}		 				
				 			echo "</label>";
				 		echo "</div>";
					echo "</fieldset>";				
				}		
		      /*
		      Variabler Metadatenstamm
		      */
		       
				echo "<br><fieldset>";
					echo "<legend>Metadaten</legend>";
					foreach ($spalten as $key => $value) {
						if($tabellenPrefixShort[$key]=="tMETA_") {
		  					echo "<div class=\"large-12 columns\">";								
									switch($spalten[$key]) { //Spaltentyp auswerten
										case "timestamp": case "date":
											echo "<label>".$spaltenNamen[$key];																	
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
												/*
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
												*/
												$suchwert=$aTMP[$key];
												if (substr($suchwert,0,1)=="=") {
													$suchwert=substr($aTMP[$key],1);
												}
												echo "<div class=\"css-treeview\">";
													echo "<li><input type=\"checkbox\" id=\"x".$key."\" /><label for=\"x".$key."\">+	".$spaltenNamen[$key]."</label>";
														echo "<ul>";
															generateListOrdner2(0,0,$key,$eingabetyp, $suchwert);
														echo "</ul>";
													echo "</li>";
												echo "</div>";												
												echo "<input type=\"hidden\" value=\"".$suchwert."\" placeholder=\"".$key."\" id=\"".$key."\" name=\"".$key."\"/>";
												echo "<input type=\"text\"  value=\"".get_WertByID($key,$suchwert)."\" id=\"visible".$key."\" name=\"visible".$key."\"  disabled />";
												
											} else {
												/*
												Wenn keine Werteliste vorhanden ist:
												*/
												echo "<label>".$spaltenNamen[$key];							
												if(isset($aTMP[$key])) {
													echo "<input type=\"text\" placeholder=\"<>0\" value=\"".$aTMP[$key]."\" name=\"".$key."\"/>";
												} else {
													echo "<input type=\"text\" placeholder=\"<>0\" name=\"".$key."\"/>";										
												}
											}
											break;									
										case (preg_match('/decimal.*/', $spalten[$key]) ? true : false) :
											echo "<label>".$spaltenNamen[$key];
											if(isset($aTMP[$key])) {
												echo "<input type=\"text\" value=\"".str_replace(".",",",$aTMP[$key])."\" name=\"".$key."\"/>";
											} else {
												echo "<input type=\"text\" name=\"".$key."\"/>";										
											}									
											break;
										case (preg_match('/char.*/', $spalten[$key]) ? true : false) :
											echo "<label>".$spaltenNamen[$key];
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
										case (preg_match('/varchar.*/', $spalten[$key]) ? true : false) :
											echo "<label>".$spaltenNamen[$key];
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
											echo "<label>".$spaltenNamen[$key];
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

				//Preview
				if(($eingabetyp=="editEntry" OR $eingabetyp=="showEntry") AND (isset($dokumentspalte))) {
					$datei=getFilenameByID($editID);
					echo $datei;
					echo "<br><fieldset>";
						echo "<legend>Preview: ".basename($datei)."</legend>";					
						echo "<iframe src = \"ViewerJS/#../".$datei."\" width='800' height='566' allowfullscreen webkitallowfullscreen></iframe>";
					echo "</fieldset>";
				}
				/*
				Butten um suchen oder Eingabe zu starten
				*/
				switch($eingabetyp){
					case ("searchEntry"):					
						echo "<button class=\"button expand round\" type=\"Submit\"><i class=\"fi-magnifying-glass\"></i> suche starten</button>";
						echo "<input type=\"hidden\" value=\"searchEntry\" name=\"eingabetyp\">";
						break;
					case ("addEntry"):
						echo "<button class=\"button expand round\" type=\"Submit\"><i class=\"fi-page-add\"></i> Eintrag hinzufügen</button>";			
						break;
					case ("editEntry"):
						echo "<button class=\"button expand round\" type=\"Submit\"><i class=\"fi-save\"></i> Eintrag speichern</button>";
						echo "<input type=\"hidden\" value=\"".$editID."\" name=\"editID\">";
						break;
				}
				
				echo "<input type=\"hidden\" value=\"".$eingabetyp."\" name=\"eingabetyp\">";
				unset($aTMP);
			?>		
		</form>
	</div>

	<?php
		if ($_POST["eingabetyp"]=="editEntry" OR $_POST["eingabetyp"]=="showEntry") {
			echo "<script src=\"".$foundationVersion."/js/vendor/jquery.js\"></script>";
			
			echo "<script src=\"".$foundationVersion."/js/foundation/foundation.js\"></script>";
			echo "<script src=\"".$foundationVersion."/js/foundation/foundation.topbar.js\"></script>";
			echo "<script src=\"".$foundationVersion."/js/foundation/foundation.dropdown.js\"></script>";
			echo "<script src=\"".$foundationVersion."/js/foundation/foundation.reveal.js\"></script>";
			echo "<script src=\"".$foundationVersion."/js/foundation/foundation.alert.js\"></script>";	
			echo "<script src=\"".$foundationVersion."/js/foundation/foundation.offcanvas.js\"></script>";
			echo "<script>$(document).foundation();</script>";  
			
			$mysqli->close();
			
		}
	?>

<script>
	var tree = document.querySelectorAll('ul.tree a:not(:last-child)');
	for(var i = 0; i < tree.length; i++){
		tree[i].addEventListener('click', function(e) {
			var parent = e.target.parentElement;
			var classList = parent.classList;
			if(classList.contains("open")) {
				classList.remove('open');
				var opensubs = parent.querySelectorAll(':scope .open');
				for(var i = 0; i < opensubs.length; i++){
					opensubs[i].classList.remove('open');
				}
			} else {
				classList.add('open');
			}
			e.preventDefault();
		});    
	}
	function myFunction(element, valueID, valueXName, closeX) {
		// Auswahl eintragen
		document.getElementById(element).value = valueID;
		document.getElementById("visible"+element).value = valueXName;

		// Treeview wieder schließen
		if (closeX=="True") {
			document.getElementById("x"+element).click();	
		}
	}	
</script>
	
	
</body>
</html>
