<?php
	include 'fonctions.php' ;

/*
------------------------------------------------------------------------------------------------------------------------
Définitions
------------------------------------------------------------------------------------------------------------------------
*/

	/*	Catégories des pages 	*/
		define ( "ADMIN", 		-1 	) ;
		define ( "ACCUEIL", 		0 	) ;
		define ( "NEWS", 		1 	) ;
		define ( "COMPAGNIE", 	2 	) ;
		define ( "TERRAIN", 		3 	) ;
		define ( "CONCOURS", 	4 	) ;
		define ( "EVENEMENT", 	5 	) ;
		define ( "PLANNING", 	6 	) ;
		define ( "FAQ", 		7 	) ;
		define ( "LIENS", 		8 	) ;
		define ( "PLAN",		9 	) ;
		define ( "CONTACT",	10 	) ;
		
	/*
	*	Cette fonction gére les données relatives aux pages
	*	@param	$page	int		Catégorie de la page
	*	@result			Array
	*						( 	$titre	string	Titre de la page
	*							$css		string	Chemin vers le fichier de Style )
	*/
	function cie_gen_cat ( $page )
	{
		$css = '../styles/cie.css';
		switch( $page )
		{
			case 1	:	$titre = 'News';			break;
			case 2	:	$titre = 'La Compagnie' ;	break;
			case 3	:	$titre = 'Le Terrain' ;		break;
			case 4	:	$titre = 'Concours' ;		break;
			case 5	:	$titre = 'Evenements' ;		break;
			case 6	:	$titre = 'Planning' ;			break;
			case 7	:	$titre = 'FAQ / Informations' ;	break;
			case 8	:	$titre = 'Liens' ;			break;
			case 9	:	$titre = 'Plan du Site' ;		break;
			case 10	:	$titre = 'Nous Contacter' ;	break;
		}
		return Array ( 'titre' => $titre, 'css' => $css );
	}

/*
------------------------------------------------------------------------------------------------------------------------
Connexion DB
------------------------------------------------------------------------------------------------------------------------
*/	
	
	/**
	*	Connexion à la base de données 
	*/
	function cie_connect_db() {
		mysql_connect('localhost','g4n_mysql','nux7nay9') or cie_erreur();
		mysql_select_db('g4n_mysql') or cie_erreur();
	}
	
	/**
	*	Déconnexion de la base de données 
	*/
	function cie_disconnect_db() {
		mysql_close() or cie_erreur();
	}
	
/*
------------------------------------------------------------------------------------------------------------------------
Génération de la page
------------------------------------------------------------------------------------------------------------------------
*/

	/*
	*	Genere l'entete de page
	*	@param $title - String - Titre de la page
	*	@param $css - String - Feuille de style de la page
	*	@param $connected - Boolean - Connexion de l'utilisateur
	*/
	function cie_entete ( $title, $css, $connected, $page )
	{
	
		echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
			<html><head>',
					'<title>',$title,'</title>',
					'<link rel="stylesheet" type="text/css" href="',$css,'">',
				'</head><body><div id="bcPage"><div id="bcTete">';
		if( $page == 0 )  // Page = Accueil
		{
			echo ($connected == false ) ? 
					'<a href="./php/connexion.php">Connexion</a>' :
					'<a href="./php/menu_admin.php">Menu</a>';
		}
		else
		{
			echo ($connected == false ) ?
					'<a href="connexion.php">Connexion</a>' :
					'<a href="menu_admin.php">Menu</a>';
		}
		cie_menu ( $page ) ;
		echo '</div>';
	}
	
	/*
	*	Genere le pied de page
	*/
	function cie_pied ( $page )
	{
		if ( $page == ACCUEIL || $page == ADMIN ) 
		{
			echo '<div id="bcPiedAcc">',
				'</div></div></body></html>';
		}
		else
		{
			echo  '<div id="bcPied">',
					'<a href="page.php?artCat=6">Planning</a>',
					'<a href="page.php?artCat=7">Informations/FAQ</a>',
					'<a href="page.php?artCat=8">Liens</a>',
					'<a href="plan.php">Plan du Site</a>',
					'<a class="contact" href="page.php?artCat=10">@</a>',
				'</div></div></body></html>';
		}
	}
	
	/*
	*	Genere le contenu
	*	@param $fct - String - Fonction à executer
	*	@param $titre - String - Titre de la page
	*/
	function cie_contenu ( $fct, $titre )
	{
		echo '<div id="bcContenu">
				<span class="title">',$titre,'</span>';
		$fct ( ) ;
		echo '</div>';		
	}
	
	/*
	*	Génère le menu sur les pages de contenu
	*	@param $page - Entier - Catégorie de la page
	*/
	function cie_menu ( $page )
	{	
		if( $page != 0 )
		{
			if( $page > 5 ) { $page = -2; }
			echo '<div id="bcMenu">',
					($page > 0) ? "<a href='../'>Accueil</a>"						: 	"<a href='page.php?artCat=1'>News</a>",						
					($page > 1) ? "<a href='page.php?artCat=1'>News</a>"			: 	"<a href='page.php?artCat=2'>La Compagnie</a>", 				
					($page > 2) ? "<a href='page.php?artCat=2'>La Compagnie</a>"	: 	"<a href='page.php?artCat=3'>Le Terrain</a>",			
					($page > 3) ? "<a href='page.php?artCat=3'>Le Terrain</a>"		: 	"<a href='page.php?artCat=4'>Concours</a>",				
					($page > 4) ? "<a href='page.php?artCat=4'>Concours</a>"		: 	"<a href='page.php?artCat=5'>Evènements</a>",		
					($page > 5) ? "<a href='page.php?artCat=5'>Evènements</a>"	: 	'',										
				"</div>";
		}
		else { echo '' ; }
		
		/*
		Donc :
			Page d'administration ( -1 ) ----------	: News* / La Compagnie* / Le Terrain* / Concours* / Evenements*
			Page d'accueil	( 0 )	-----------------------	: rien *
			Page de News ( 1 )	-----------------------	: Accueil* / La Compagnie* / Le Terrain* / Concours* / Evenements*
			Page de La Compagnie ( 2 ) ---------	: Accueil* / News* / Le Terrain* / Concours* / Evenements*
			Page du Terrain ( 3 ) ---------------------	: Accueil* / News* / La Compagnie* / Concours* / Evenements*
			Page des Concours ( 4 ) ----------------	: Accueil* / News* / La Compagnie* / Le Terrain* / Evenements*
			Page des Evenements ( 5 ) ------------	: Accueil* / News* / La Compagnie* / Le Terrain* / Concours*
			Page du Planning ( 6+ ) ----------------	: News* / La Compagnie* / Le Terrain* / Concours* / Evenements*
			
			* = OK
		*/
	}
	
	
	/*
	 *	Affiche un article et son contenu (text, img, pdf, liens)
	 *	@param	$data			Tableau Associatif		Tableau de résultats d'une requete sql "SELECT"
	 *	@param	$page			int					Numéro de la page
	 *	@param	$connected		boolean				True = Utilisateur connecté
	 */
	function cie_article ( $data, $page, $connected ) 
	{
		echo '<div id="bcArticle">',
				'<span class="head">',
					($data['artTitre'] == '') ? '' : "<h1>", cie_affichage_text( $data['artTitre'] ), "</h1>",
					($data['artDate'] == '') ? '' : "<p class='date'>", cie_affichage_text( $data['artDate'] ), "</p>",
				'</span>',
				'<br>',
				'<div class="cont">',
					($data['artContenu'] == '') 	? '' : "<p class='contenu'>", cie_affichage_text( $data['artContenu'] ), "</p>",
					($data['artImage'] == '' ) 	? '' : cie_disp_file($data['artImage'], 'jpg', false),
					($data['artPJointe'] == '')	? '' : cie_disp_file($data['artPJointe'], 'pdf', false),
					($data['artLien'] == '')		? '' : cie_disp_lien($data['artLien'], $data['artTitreLien'], false),
				'</div>',
				'<span class="foot">',
					($connected == true) ? "<a class='plus petit' href='modif_article.php?artCat=$page&artId={$data['artId']}'>Editer cet article</a>" : '' ,
				'</span>',
			'</div>';
	}
	
	
	
	/*	
	*	Génere une agrégation d'articles	
	*	@param	$enr		Tableau Associatif		Résultat d'une requette Sql
	*	@param	$page	int					Catégorie de la page
	*/
	function cie_super_article ( $enr, $page )
	{
		switch( $page )
		{
			case 7 :	// FAQ
				echo "<h2 class='contenu'> {$enr['artTitre']} </h2>",
					"<p class='contenu'> {$enr['artContenu']} </p>";
				break;
			case 8 :	// Liens
				echo "<a class='contenu' href='{$enr['artLien']}' target='_blank'> {$enr['artTitre']} </a><br>";
				break;
			default : '' ;
				break;
		}
	}
	






/*
------------------------------------------------------------------------------------------------------------------------
Génération de la page - Annexe
------------------------------------------------------------------------------------------------------------------------
*/

	/*
	 *	Upload un fichier entré via un <input type="file">
	 *	@param	$file		Tableau associatif		Equivalent à $_FILES
	 *	@param	$type	String				Type du document : pdf ou jpg
	 *	@return	$nom	String				Nom (sans extension) du fichier
	 */
	function cie_fichier ( $file, $type )
	{
		// -- Image (full size)
		$nom= substr($file['name'], 0, strlen($file['name'])-4); 			// Nom du fichier (sans extension)
		$upload = "../upload/{$nom}.{$type}";								// Futur emplacement du fichier
		@move_uploaded_file( $file['tmp_name'], $upload);				// Déplacement du fichier au bon emplacement
		
		return $nom;
	}


	/*
	 *	Calcule la taille de miniature d'une image pour les articles selon la disposition (paysage ou portrait)
	 *	@param	$adr	String					adresse de l'image (../upload/nomdufichier.jpg)
	 *	@return	$t		Tableau index numérique	t[0] = longueur, t[1] = hauteur (equivalent au tableau de retour de getimagesize)
	 */
	function cie_setsize ( $adr )
	{
		$t = getimagesize( $adr );
		if( $t[0] > $t[1] )	{	$t[0] = 300;	$t[1] = 225;	}
		else 			{	$t[0] = 225;	$t[1] = 300;	}
		return $t;
	}
	

	/*
	 *	Affiche des fichiers grâce a leur nom contenu dans une chaine formatée.
	 *	@param	$str		String		Chaine formatée
	 *	@param	$type	String		Type du fichier (pdf ou jpg)
	 *	@param	$b		Boolean		Génération de lignes et colonnes (<tr><td>)
	 */
	function cie_disp_file ( $str, $type, $b = false )
	{
		$t = cie_deformate($str);
		echo ($b == true) ? '<tr><td colspan="2">' : '';
		foreach( $t as $val )
		{
			$adr = "../upload/{$val}.{$type}";
			
			echo "<a href='{$adr}' target='_blank'>";
			if( $type == 'jpg' )	
			{	
				$r = cie_setsize($adr);
				echo "<img src='{$adr}' width='{$r[0]}' height='{$r[1]}'>";
			}
			if( $type == 'pdf' )
			{
				echo "<img src='../img/pdf.png' width='115' height='90'>";
			}
			echo "</a>";
		}
		echo ($b == true ) ? '</td></tr>' : '';
	}
	

	/*
	 *	Affiche des liens grâce a leur nom contenu dans une chaine formatée.
	 *	@param	$str		String		Chaine formatée
	 *	@param	$type	String		Type du fichier (pdf ou jpg)
	 *	@param	$b		Boolean		Génération de lignes et colonnes (<tr><td>)
	 */
	function cie_disp_lien ( $url, $txt, $b = false )
	{
		$t1 = cie_deformate($url);
		$t2 = cie_deformate($txt);
		
		
		foreach( $t1 as $i => $v1)
		{
			echo ($b == true) ? '<tr><td colspan="2">' : '',
				"<a class='link' href='{$v1}' target='_blank'>>>> ";
				
			if ($t2[$i] == 'aucun' )	{	echo "{$v1}" ;		}
			else					{	echo "{$t2[$i]}";	}
			
			echo ' <<<</a>',
				($b == true ) ? '</td></tr>' : '' ;
		}
	}






?>