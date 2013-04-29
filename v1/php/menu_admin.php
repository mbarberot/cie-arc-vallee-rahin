<?php 
	include 'fonctions_spec.php' ;

	ob_start ( ) ;
	
	cie_verif_login ( ) ;
	
	cie_entete ( "Menu : Administration" , "../styles/cie.css", $_SESSION['connected'], ADMIN ) ;
	cie_contenu ( "cie_traitement", "Administration : Menu" ) ;
	cie_pied ( ADMIN ) ;
	
	
	ob_end_flush ( ) ;
	
/*
------------------------------------------------------------------------------------------------------------------------
*/

	function cie_traitement ( ) 
	{
		echo '<div id="bcArticle">',
				'<span class="head"></span>',
				'<div class="cont">',
					'<h2 class="contenu">Ajouter un article dans la section ...</h2>',
					'<ul>',
					'<li><a href="ajout_article.php?artCat=1">... News</a></li>',
					'<li><a href="ajout_article.php?artCat=2">... La Compagnie</a></li>',
					'<li><a href="ajout_article.php?artCat=3">... Photos du Terrain</a></li>',
					'<li><a href="ajout_article.php?artCat=4">... Concours</a></li>',
					'<li><a href="ajout_article.php?artCat=5">... Evenement</a></li>',
					'<li><a href="ajout_article.php?artCat=6">... Planning</a></li>',
					'<li><a href="ajout_article.php?artCat=7">... FAQ</a></li>',
					'<li><a href="ajout_article.php?artCat=8">... Lien</a></li>',
					'<li><a href="ajout_article.php?artCat=10">... Nous Contacter</a></li>',
					'<br>',
					'</ul>',
					'<h2 class="contenu">Modifier un article dans la section ...</h2>',
					'<ul>',
					'<li><a href="menu_modif.php?artCat=1">... News</a></li>',
					'<li><a href="menu_modif.php?artCat=2">... La Compagnie</a></li>',
					'<li><a href="menu_modif.php?artCat=3">... Photos du Terrain</a></li>',
					'<li><a href="menu_modif.php?artCat=4">... Concours</a></li>',
					'<li><a href="menu_modif.php?artCat=5">... Evenement</a></li>',
					'<li><a href="menu_modif.php?artCat=6">... Planning</a></li>',
					'<li><a href="menu_modif.php?artCat=7">... FAQ</a></li>',
					'<li><a href="menu_modif.php?artCat=8">... Lien</a></li>',
					'<li><a href="menu_modif.php?artCat=10">... Nous Contacter</a></li>',
					'</ul>',
					'<h2 class="contenu">Autre ...</h2>',
					'<ul>',
					'<li><a href="parametres.php">...modifier vos paramètres</a></li>',
					'<li><a href="deconnexion.php">...se deconnecter</a></li>',
					'<br>',
					'</ul>',
				'</div>',
				'<span class="foot"></span>',
			'</div>';
	}

?>