<?php 
	include 'fonctions_spec.php' ;

	ob_start ( ) ;
	
	cie_verif_login ( ) ;
	
	cie_entete ( "Ajouter un article" , "../styles/cie.css", $_SESSION['connected'], ADMIN ) ;
	cie_contenu ( "cie_affichage", "Ajouter un article" ) ;
	cie_pied ( ADMIN ) ;
	
	
	ob_end_flush ( ) ;
	
/*
------------------------------------------------------------------------------------------------------------------------
*/

	function cie_affichage ( ) 
	{
		if ( isset($_GET['artCat'] ) )
		{
			if( isset($_POST['addFile']) )
			{
				// Sauvegardes des variables de fichiers multiples
				$img = $_POST['img'];
				$pj = $_POST['pj'];
				$t = $_POST;
			
				$r = cie_traitement_file();
				$t['img'] .= $r['img'];
				$t['pj'] .= $r['pj'];
			}
			else if( isset($_POST['addLien']) )
			{
				$t = $_POST;
				$r = cie_traitement_liens();
				$t['url'] = $r['url'];
				$t['txt'] = $r['txt'];
			}
			else if( isset($_POST['btnAdd']) )
			{
				cie_add();
				$t = $_POST;
			}
			else 
			{ 
				$t = Array (
						'titre' 	=> '',
						'jour' 	=> -1,
						'mois' 	=> -1,
						'annee' 	=> -1,
						'contenu'	=> '',
						'img'	=> '',
						'pj'		=> '',
						'url'		=> '',
						'txt'		=> '',
						); 
			}
		
		
			echo "<div id='bcForm'>",
				"<form enctype='multipart/form-data' method='post' action='ajout_article.php?artCat={$_GET['artCat']}' >",
					'<span class="head"></span>',
					'<div class="cont">';
					
			// -- Affichage des erreurs		
			if ( isset($GLOBALS['erreur']) )
			{
				echo '<p class="erreur">';
				$n = sizeof($GLOBALS['erreur']) ;
				for( $i = 0; $i < $n; $i++ )
				{
					echo ' - ', $GLOBALS['erreur'][$i] , '<br>' ;
				}
				echo '<br></p>';
			}
				
			if ( $_GET['artCat'] == 7 ) // FAQ
			{
				echo '<table>',
						cie_gen_ligne( '<p>Titre : </p>', cie_gen_input( 'text', 'titre', $t['titre'] ) ),
						cie_gen_ligne( '<p>Contenu : </p>', cie_gen_textarea( 'contenu', $t['contenu']) ),
					'</table>';
			}	
			else // Autres pages
			{
				echo '<table>',
						// Variables de fichiers multiples
						cie_gen_input( 'hidden', 'img', $t['img'] ),
						cie_gen_input( 'hidden', 'pj', $t['pj'] ),
						cie_gen_input( 'hidden', 'url', $t['url'] ),
						cie_gen_input( 'hidden', 'txt', $t['txt'] ),
						// Entrée des formulaires "normaux"
						cie_gen_ligne( '<p>Titre : </p>', cie_gen_input( 'text', 'titre', $t['titre'] ) ),
						cie_gen_ligne( '<p>Date : </p>', ''.cie_gen_select( 'jour', 1, 31, $t['jour']).cie_gen_select( 'mois', 1, 12, $t['mois']).cie_gen_select( 'annee', 1970, 2010 , $t['annee']) ),
						cie_gen_ligne( '<p>Contenu : </p>', cie_gen_textarea( 'contenu', $t['contenu']) ),
						// Ajout de fichiers (pdf ou jpg)
						'<tr><td><p>Fichier : </p></td>',
							'<td>',
								cie_gen_input( 'file', 'pj', '' ),
								cie_gen_input( 'submit', 'addFile', 'Ajouter un fichier' ),
							'</td>',
						'</tr>';
						// Affichage des fichiers déja entrés
						if( $t['img'] != '' )	{	cie_disp_file ( $t['img'], 'jpg', true ) ;	}
						if( $t['pj'] != '' )	{	cie_disp_file ( $t['pj'], 'pdf', true ) ;		}
						// Ajout des liens
				echo	cie_gen_ligne( '<p>Lien : </p>', cie_gen_input( 'text', 'lien', 'http://' ) ),
						'<tr>',
							'<td><p>Texte pour le lien : </p></td>',
							'<td>',
								cie_gen_input( 'text', 'nomLien', ''),
								cie_gen_input( 'submit', 'addLien', 'Ajouter un lien' ),
							'</td>',
						'</tr>';
						// Affichage des liens déja entrés
						if( $t['url'] != '' )	{	cie_disp_lien ( $t['url'], $t['txt'], true ) ;	}
				echo '</table>';
			}
			echo '</div>',
				'<span class="foot">',
					'<input class="btn" type="submit" name="btnAdd" value="Valider">',
				'</span>',
			"</form></div>";
		}
		else 
		{
			echo '<div id="bcArticle">',
					'<span class="head"></span>',
					'<div class="cont">',
						'<p class="contenu erreur"> Erreur ! Aucune catégorie choisie ! </p>', 
					'</div>',
					'<span class="foot"></span>',
				'</div>';
		}
		
	}
	
	function cie_traitement_file ( )
	{
		global $erreur;
		$erreur = Array();
		$img = ''; $pj = '';
		
		// Verification qu'un fichier à été envoyé
		if( isset($_FILES['pj']['name']) && $_FILES['pj']['name'] != '')
		{
			// Vérifications de validité du fichier ( -1MO / jpg - pdf )
			if( $_FILES['pj']['size'] > 1048576 ) 					{ 	$erreur[] = "L'image doit faire moins d'1Mo" ;	} 
			if ($_FILES['pj']['error'] == 3 ) 					{ 	$erreur[] = "L'envoi du fichier a été interrompu pendant le transfert !"; }  
			
			if( sizeof($erreur) <= 0 )
			{
				switch($_FILES['pj']['type'])
				{
					case 'image/jpeg' 	: 	$img = cie_fichier( $_FILES['pj'], 'jpg').',' ; break;
					case 'application/pdf' : 	$pj = cie_fichier( $_FILES['pj'], 'pdf').',' ;	break;
					default : 	$erreur[] = "Format du fichier incorrect" ;
				}
			}
			return Array( 'img' => $img, 'pj' => $pj );
		}
		return 0;
	}
	
	function cie_traitement_liens ( )
	{
		// Vérification qu'un lien à bien été soumis
		if( $_POST['lien'] != '' )
		{

			$url = $_POST['url'].$_POST['lien'].',' ;
			// Vérification qu'un nom de lien à été choisi
			if( $_POST['nomLien'] != '' )
			{
				$txt = $_POST['txt'].$_POST['nomLien'].',' ;
			}
			else	
			{ 
				$txt = $_POST['txt'].'aucun,' ; 
			}
			
			return Array( 'url' => $url, 'txt' => $txt );
		}
		return 0;
		
	}
	
	
	
	function cie_add ( $res )
	{
		// -- Protection des chaines -------------------------------
		
		foreach($_POST as $lib => $val) 	{	$_POST[$lib] = cie_protect_text($val);	}
		foreach($_GET as $lib => $val)	{	$_GET[$lib] = cie_protect_text($val);	}
			
		// -- Insertion dans la base de donnée ----------------
		
			cie_connect_db ( ) ;
		
			$sql = "INSERT INTO `g4n_mysql`.`article` 
				(	`artId`, 
					`artCat`, 
					`artTitre`, 
					`artDate`, 
					`artContenu`, 
					`artImage`, 
					`artPJointe`, 
					`artTitreLien`, 
					`artLien`
				) 
				VALUES 
				(	NULL, 
					'{$_GET['artCat']}', 
					'{$_POST['titre']}', 
					'{$_POST['annee']}-{$_POST['mois']}-{$_POST['jour']}', 
					'{$_POST['contenu']}', 
					'{$_POST['img']}', 
					'{$_POST['pj']}', 
					'{$_POST['txt']}', 
					'{$_POST['url']}'
					);";
			mysql_query( $sql ) or cie_erreur( );
			
			cie_disconnect_db( );
			
		// -- Redirection --------------------------------------------------------------------------
		cie_redirect ( 'page.php?artCat='.$_GET['artCat'] );
	}
?>