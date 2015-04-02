<?php 
	include 'fonctions_spec.php' ;

	ob_start ( ) ;
	
	cie_verif_login ( ) ;
	
	cie_entete ( "Menu : Modification" , "../styles/cie.css", $_SESSION['connected'], ADMIN ) ;
	cie_contenu ( "cie_traitement", "Administration : Modifier un article" ) ;
	cie_pied ( ADMIN ) ;
	
	
	ob_end_flush () ;
	
/*
------------------------------------------------------------------------------------------------------------------------
*/

	function cie_traitement ( ) 
	{
		// -- MySql ----------------------------------
			cie_connect_db ( ) ;
			
			$sql = "SELECT artId, artTitre FROM article WHERE artCat = '{$_GET['artCat']}'";
			$r = mysql_query( $sql ) or cie_erreur( ) ;
			
		
			
		echo '<div id="bcArticle">',
				'<span class="head"></span>',
				'<div class="cont">',
					'<h2 class="contenu">Modifier ...</h2>',
					'<ul>';
		
		// -- Traitement des résulats
		$cpt = 0;
		while ( $enr = mysql_fetch_assoc( $r ) )
		{
			echo "<li><a href='modif_article.php?artCat={$_GET['artCat']}&artId={$enr['artId']}'>{$enr['artTitre']}</a></li>";
			if( $cpt == 5 ) { break; }
		}
		
		echo		'</ul>',
				'</div>',
				'<span class="foot"></span>',
			'</div>';
		
		
		mysql_free_result($r);
		cie_disconnect_db();
	}
	
?>