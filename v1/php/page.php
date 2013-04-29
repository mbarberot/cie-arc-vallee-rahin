<?php
	include 'fonctions_spec.php' ;

	// -- Debut de la bufferisation ----------------------------------------
	ob_start ( ) ; 
	
	// -- Verification du statut (connecté ou non?) ---------------
	session_start( ) ;
	if ( isset($_SESSION['connected']) ){	$connected = $_SESSION['connected']; 	}
	else 						{	$connected = false ; 					}
	
	// -- Gestion de la page -----------------------------------------------
	if( isset($_GET['artCat']))	{	$page = $_GET['artCat'] ; 	}
	else						{	$page = -2;				}
	if( $page <= 0 || $page > 10 ) { cie_redirect('../accueil.php'); } 		//  0 < $page <= 10
	
	// -- Génération du contenu ---------------------------------------------
	$t = cie_gen_cat( $page );
	
	cie_entete ($t['titre'], $t['css'], $connected, $page ) ;
	cie_contenu ( "cie_traitement", $t['titre']) ;
	cie_pied ( $page ) ;
	
	// -- Fin de la bufferisation et envoi du tampon -------------
	ob_end_flush ( ) ;

/*
------------------------------------------------------------------------------------------------------------------------
*/

	function cie_traitement ( )
	{
		$connected = false;
		if ( isset($_SESSION['connected']) ){	$connected = $_SESSION['connected']; 	}
		
		$page = $_GET['artCat'] ;
	
		cie_connect_db();
		
		$sql = "SELECT * FROM article WHERE artCat=".$page." ORDER BY artDate DESC";
		$r = @mysql_query( $sql ) or cie_erreur( );
		
		$cpt = 0;
		
		if ( $page == 7 || $page == 8 ) // Page de la FAQ (mise en page spéciale ^^)
		{
			echo '<div id="bcArticle">',
				'<span class="head"></span>',
				'<div class="cont">';
			
			while( $enr = mysql_fetch_assoc( $r ) )
			{
				cie_super_article( $enr, $page );
			}
				
			echo '</div>',
				'<span class="foot"></span>',
				'</div>';
		
		}
		else if ( $page == 9 ) { cie_redirect('plan.php'); }
		else
		{
			while( $enr = mysql_fetch_assoc( $r ) )
			{
				cie_article( $enr, $page, $connected );
				if( $cpt == 5 ) { break; }
				$cpt++;
			}
		}
		
		@mysql_free_result( $r ) or cie_erreur( );
		cie_disconnect_db( );
	}


?>