<?php
	include 'fonctions_spec.php' ;

	// -- Debut de la bufferisation ----------------------------------------
	ob_start ( ) ; 
	
	// -- Verification du statut (connecté ou non?) ---------------
	session_start( ) ;
	if ( isset($_SESSION['connected']) ){	$connected = $_SESSION['connected']; 	}
	else 						{	$connected = false ; 					}
	
	
	// -- Génération du contenu ---------------------------------------------
	$t = cie_gen_cat( PLAN );
	
	cie_entete ($t['titre'], $t['css'], $connected, PLAN ) ;
	cie_contenu ( "cie_traitement", $t['titre']) ;
	cie_pied ( PLAN ) ;
	
	// -- Fin de la bufferisation et envoi du tampon -------------
	ob_end_flush ( ) ;

/*
------------------------------------------------------------------------------------------------------------------------
*/

	function cie_traitement ( )
	{
		echo '<div id="bcArticle">',
				'<span class="head"></span>',
				'<div class="cont">',
					'<h2 class="contenu">Pages Principales</h2>',
					'<ul>',
						'<li><a href="../">Accueil</a></li>',
						'<li><a href="page.php?artCat=1">News</a></li>',
						'<li><a href="page.php?artCat=2">La Compagnie</a></li>',
						'<li><a href="page.php?artCat=3">Le Terrain</a></li>',
						'<li><a href="page.php?artCat=4">Concours</a></li>',
						'<li><a href="page.php?artCat=5">Evènements</a></li>',
					'</ul>',
					'<h2 class="contenu">Pages Secondaires</h2>',
					'<ul>',
						'<li><a href="page.php?artCat=6">Planning</a></li>',
						'<li><a href="page.php?artCat=7">Informations / FAQ</a></li>',
						'<li><a href="page.php?artCat=8">Liens</a></li>',
						'<li><a href="plan.php">Plan du Site</a></li>',
						'<li><a href="page.php?artCat=10">Nous Contacter</a></li>',
					'</ul>',
				'</div>',
				'<span class="foot"></span>',
			'</div>';
	}


?>