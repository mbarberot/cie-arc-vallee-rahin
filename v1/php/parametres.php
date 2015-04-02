<?php 
	include 'fonctions_spec.php' ;

	ob_start ( ) ;
	
	cie_verif_login ( ) ;
	
	cie_connect_db ( ) ;
	
	cie_entete ( "Paramètres" , "../styles/cie.css", $_SESSION['connected'], ADMIN ) ;
	cie_contenu ( "cie_affichage", "Modifier les paramètres" ) ;
	cie_pied ( ADMIN ) ;
	
	cie_disconnect_db ( ) ;
	
	ob_end_flush ( ) ;
	
/*
------------------------------------------------------------------------------------------------------------------------
*/

	function cie_affichage ( ) 
	{
		
		$r = mysql_query( "SELECT * FROM users WHERE userId = {$_SESSION['user']}" ) or cie_erreur( );
		$enr = mysql_fetch_assoc( $r ) ;
		
		if( isset($_POST['btnEmail']))	{ cie_traitement_email ( $enr ); }
		if( isset($_POST['btnPw']))	{ cie_traitement_pw ( $enr );      }
		
		echo '<div id="bcForm">',
			'<form enctype="plain/text" method="post" action="parametres.php">',
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
			
			
		echo 		'<table>',
						cie_gen_ligne	( '<p>Votre E-mail actuel : </p>', $enr['userEmail'] ),
						cie_gen_ligne	( '<p>Votre nouvel E-mail : </p>', cie_gen_input('text', 'email', '') ),
						cie_gen_sep	( '<input class="btn" type="submit" name="btnEmail" value="Changer">' ),
						cie_gen_sep	( '<br><br>' ),
						cie_gen_ligne	( '<p>Mot de passe actuel : </p>', cie_gen_input('password', 'pw', '')),
						cie_gen_ligne	( '<p>Nouveau mot de passe : </p>', cie_gen_input('password', 'newPw', '') ),
						cie_gen_ligne	( '<p>Retapez le nouveau mot de passe : </p>', cie_gen_input('password', 'reNewPw', '' ) ),
						cie_gen_sep	( '<input class="btn" type="submit" name="btnPw" value="Changer">' ),
						cie_gen_sep	( '<br><br>' ),
					'</table>',
				'</div>',
				'<span class="foot"></span>',
			"</form></div>";
	}	
	
	function cie_traitement_email ( $enr )
	{
		global $erreur;
		$erreur = Array ( );
		$post = $_POST;
		
		
		if( !isset($_POST['email']) || $_POST['email'] == '' )	{	$erreur[] = 'Le champ "Adresse E-mail" n\'est pas renseigné' ; 	}
		else if( $_POST['email'] == $enr['userEmail'] )		{	$erreur[] = 'L\'adresse E-mail fournie est deja enregistrée comme étant la votre' ;	}
		else if( cie_test_mail( $_POST['email']) == false )		{	$erreur[] = 'L\'adresse E-mail n\'est pas conforme' ;	}
		else
		{
			$email = cie_protect_text( $_POST['email'] ) ;
			$sql = "UPDATE users SET userEmail = '$email' WHERE userId = '{$_SESSION['user']}' ";
			mysql_query( $sql ) or cie_erreur( ) ;
			$erreur[] = "Votre adresse e-mail a bien été mise à jour !";
		}
	}
	
	function cie_traitement_pw ( $enr )
	{
		global $erreur;
		$erreur = Array ( );
		$post = $_POST;
		
		if( !isset($_POST['pw']) || $_POST['pw'] == '' )			{	$erreur[] = 'Le champ "Mot de Passe" n\'est pas renseigné' ; 	}
		if( !isset($_POST['newPw']) || $_POST['newPw'] == '' )		{	$erreur[] = 'Le champ "Nouveau Mot de Passe" n\'est pas renseigné' ; 	}
		if( !isset($_POST['reNewPw']) || $_POST['reNewPw'] == '' )	{	$erreur[] = 'Le champ "Retapez le nouveau Mot de Passe" n\'est pas renseigné' ; 	}
		if( sizeof($erreur) == 0)
		{
			if( $_POST['pw'] != $enr['userPassword'] )			{	$erreur[] = 'Le mot de passe est erroné' ; 	}
			else if( $_POST['newPw'] != $_POST['reNewPw'] )		{	$erreur[] = 'Les mots de passes ne correspondent pas' ; 	}
			else
			{
				$pw = cie_protect_text( $_POST['newPw'] ) ;
				$sql = "UPDATE users SET userPassword = '$pw' WHERE userId = '{$_SESSION['user']}' ";
				mysql_query( $sql ) or cie_erreur( ) ;
				$erreur[] = "Votre mot de passe a bien été mis à jour !";
			}
		}
		
	
	}
	
	
	
?>