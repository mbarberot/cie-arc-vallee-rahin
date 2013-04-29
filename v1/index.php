<?php
	include './php/fonctions_spec.php' ;
	
	ob_start ( ) ;
	
	session_start( ) ;
	if ( isset($_SESSION['connected']) ){	$connected = $_SESSION['connected']; 	}
	else 						{	$connected = false ; 					}
	
	
	cie_entete ( "Accueil : Compagnie d'arc de la vallée du Rahin", './styles/cie.css', $connected, ACCUEIL ) ;	
	cie_traitement ( ) ;
	cie_pied ( ACCUEIL ) ;
	
	ob_end_flush ( ) ;
	
	
/* -------------------------------------------------------------------------------------------------------------------------------------------------- */

	function cie_traitement ( )
	{
		echo '<div id="bcAccueil">
			<table>
				<tr rowspan="1">
					<td colspan="2"></td>
					<td colspan="1"><a href="./php/page.php?artCat=1">News</a></td>
					<td colspan="2"></td>
				</tr>
				<tr rowspan="1">
					<td colspan="1"><a href="./php/page.php?artCat=2">La Compagnie</a></td>
					<td colspan="3"></td>
					<td colspan="1"><a href="./php/page.php?artCat=3">Le Terrain</a></td>
				</tr>
				<tr rowspan="1">
					<td></td>
					<td colspan="1"><a href="./php/page.php?artCat=4">Concours</a></td>
					<td></td>
					<td colspan="1"><a href="./php/page.php?artCat=5">Evènements</a></td>
					<td></td>
				</tr>
			</table>
			</div>';
	}

?>