<?php
	include 'fonctions_spec.php' ;

	ob_start ( ) ;
	
	session_start( ) ;
	if ( isset($_SESSION['connected']) ){	$connected = $_SESSION['connected']; 	}
	else 						{	$connected = false ; 					}
	
	cie_entete ( "Connexion" , "../styles/cie.css", $connected, ADMIN ) ;
	cie_contenu ( "cie_affichage", "Connexion" ) ;
	cie_pied ( ADMIN ) ;
	
	
	ob_end_flush ( ) ;

/*
------------------------------------------------------------------------------------------------------------------------
*/

	function cie_affichage ( )
	{
		if ( isset($_POST['btnLogin']) ) 
		{
			cie_traitement ( ) ;
		}
		
		echo '<div id="bcForm">',
			'<form enctype="plain/text" method="post" action="connexion.php" >',
			'<span class="head"></span>',
			'<div class="cont">';
			
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
		
		echo	'<table class="cont">',
					cie_gen_ligne( "<p>Adresse e-mail : </p>", 	cie_gen_input ( 'text', 'email', '') ),
					cie_gen_ligne( "<p>Mot de passe : </p>", 	cie_gen_input ( 'password', 'password', '' )),
				'</table>',
			'</div>',
			'<span class="foot">',
				'<input class="btn" type="submit" name="btnLogin" value="Se Connecter">',
			'</span>',
			'</form>',
			'</div>';
	}
	
	function cie_traitement ( ) 
	{
		global $erreur;
		$erreur = Array ( ) ;
		$cpt = 0;
		
		if ( !isset($_POST['email']) || $_POST['email'] == '' )			{	$erreur[] = 'Le champ "Adresse E-Mail" n\'est pas renseigné';	} 
		if ( !isset($_POST ['password']) || $_POST['password'] == '' )		{	$erreur[] = 'Le champ "Mot de Passe" n\'est pas renseigné';		}
		
		if( sizeof( $erreur ) == 0 )
		{
			
			// -- MySql ----------------------
				cie_connect_db( );
				
				$sql = "SELECT * FROM users WHERE userEmail ='{$_POST['email']}' ;";
				$r = @mysql_query( $sql ) or cie_erreur( ) ;
				$enr = mysql_fetch_assoc( $r ) ;
				
				mysql_free_result( $r );
				cie_disconnect_db( );
			// -----------------------------------------
				
			if( !isset($enr['userEmail']) )					{	$erreur[] = "Adresse E-mail inconnue";	return '';	}
			else 									{	$cpt++;	}
			if( $_POST['password'] != $enr['userPassword'] ) 	{	$erreur[] = "Mot de passe erroné.";	return '';	}
			else 									{	$cpt++;	}
			if ( $cpt == 2 ) 							{ 	cie_login ( $enr['userId'], $enr['userCat'] );	} 
		}
	}

	function cie_login ( $userId, $userCat ) 
	{
		session_start( ) ;
		$_SESSION['user'] = $userId;
		$_SESSION['connected'] = true;
		$_SESSION['cat'] = $userCat;
		cie_redirect('../php/menu_admin.php');
	}

?>