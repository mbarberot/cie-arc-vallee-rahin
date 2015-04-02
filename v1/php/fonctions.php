<?php

/*
------------------------------------------------------------------------------------------------------------------------
Autres Fonctions
------------------------------------------------------------------------------------------------------------------------
*/

	/*
	 *	Désassemble une chaine du type mot1,mot2,mot3 en un tableau t[0] = mot1, t[1] = mot2, t[2] = mot3
	 *	@param	$str		String		La chaine en question
	 *	@return			Array		Tableau à index numérique
	 */
	function cie_deformate ( $str )
	{
		$tab = Array () ;
		$end = -1;
		$len = strlen($str);
		
		while( $end <= $len )
		{
			$start = $end+1;
			if($start >= $len) { break; }
			$end = strpos($str,',',$start+1);
			if($end === false ) { break; }
			$tab[] = substr($str,$start,$end - $start);
		}
		return $tab;
	}

	/*
	*	Vérifie que l'utilisateur est bien connecté
	*
	*	Note : N'est utilisée que dans les pages d'administration.
	*/
	function cie_verif_login ( )
	{
		session_start( );
		
		if ( !isset($_SESSION['user']) ) 
		{ 
			cie_redirect( '../' );
		}
	}
	
	/*
	*	Fonction simplifiant la redirection
	*	@param	$url		string	Adresse de redirection
	*/
	function cie_redirect ( $url ) 
	{
		header("Location: $url ");
	}
	
	/**
	* 	Vérifier une adresse e-mail en conformité avec la norme RFC 2822(ou a peu près (:-))
	*	-- Nota : fonction reprise du tutoriel PHP --
	*	@param 	$Mail 	string 	L'adresse email à tester
	*	@return 			boolean 	Vrai si l'adresse est valide, faux sinon
	*/
	function cie_test_mail( $Mail) 
	{
		// Nous commençons par vérifier que l'adresse contient bien un
		// @ (et un seul), et que les deux parties sont de longueur correcte.
		// Il doit y avoir de 1 à 64 caractères, @ exclus
		//    [^@]{1,64}
		// suivis de @ et de 1 à 255 caractères, @ exclus
		//    @[^@]{1,255}
		// Comme la chaine ne doit rien avoir d'autre, on l'entoure
		// avec ^ (commence) et $ (fini)
		$ExpReg = '/^[^@]{1,64}@[^@]{1,255}$/';
		if (! preg_match($ExpReg, $Mail)) 
		{
			return false;
		}

		// On découpe l'adresse en 2 parties : locale et domaine
		// Les tests seront plus faciles à faire.
		$Parties = explode('@', $Mail);

		// La partie locale doit etre conforme à une chaine de caractères prédéfinis
		// et elle ne doit pas commencer par un point (.)
		//    ^[A-Za-z0-9!#$%&'*+-\/=?^_`{|}~]
		// On peut ensuite avoir de 0 à 62 autres caract?res pr?d?finis
		//    [A-Za-z0-9!#$%&'*+-\/=?^_`{|}~\.]{0,62}$
		// Ce qui donne :
		//    ^[A-Za-z0-9!#$%&'*+-\/=?^_`{|}~][A-Za-z0-9!#$%&'*+-\/=?^_`{|}~\.]{0,62}$
		//
		// La cha?ne peute aussi etre composée de n'importe quelles caractères,
		// sauf \, entourés de guillemets :
		//    ^"[^(\\|")]{1,62}"$
		// Dans cette dernière expression, la sous-expression entre crochets
		// signifie que l'on exclu (caractère ^) le caractères \ (protégé ici
		// par un \) et le caractère "
		//
		// Les deux expressions sont reliées avec un ou (|).
		// Pour ne pas provoquer de collision avec les guillemets simples ou doubles
		// contenus dans les expressions, nous construisons la variable
		// par plusieurs concaténations.
		$ExpReg = '/';
		$ExpReg .= "^[A-Za-z0-9!#$%&'*+-\/=?^_`{|}~][A-Za-z0-9!#$%&'*+-\/=?^_`{|}~\.]{0,62}$";
		$ExpReg .= '|';
		$ExpReg .= '^"[^(\\|")]{1,62}"$';
		$ExpReg .= '/';
		if (! preg_match($ExpReg, $Parties[0])) 
		{
			echo "<hr>$Mail n'est pas une adresse valide (partie locale)";
			return false;
		}

		// On peut maintenant s'occuper de la partie domaine.
		// On commence récupérer les parties séparées par des points (.)
		$Domaines = explode('.', $Parties[1]);

		// Si la première partie est composée de 1 à 3 chiffres, on considère
		// que le domaine est une adresse IP de la forme 123.123.12.1 soit
		//    ^(\d{1,3}\.){3}\d{1,3}$
		// Cette vérification d'adresse IP est un peu faible ...
		if (preg_match('/^\d{1,3}$/', $Domaines[0])) 
		{
			if (preg_match('/^(\d{1,3}\.){3}\d{1,3}$/', $Parties[1]))	{	echo "<hr>$Mail est une adress valide";	} 
			else												{	echo "<hr>$Mail n'est pas une adresse valide (IP)";	}
			return false;
		}

		// Le domaine peut aussi etre une adresse IP de la forme [123.123.12.1]
		// soit l'expression
		//    ^\[(\d{1,3}\.){3}\d{1,3}\]$
		if (preg_match('/^\[\d{1,3}$/', $Domaines[0])) {
		if (preg_match('/^\[(\d{1,3}\.){3}\d{1,3}\]$/', $Domaines)) {
		echo "<hr>$Mail est une adress valide";
		} else {
		echo "<hr>$Mail n'est pas une adresse valide (IP [])";
		}
		return false;
		}

		// Si on passe ici c'est que le domaine n'est pas une adresse IP.
		// On considère que le domaine doit etre au moins composé de 2 parties
		// comme monsite.com, bien que dans la norme rien ne l'oblige.
		if (count($Domaines) < 2) {
		return false;    
		}
		// Chacune des parties du domaine doit etre conforme à :
		// une lettre ou un chiffre, suivi de 0 à 61 lettres, chiffres ou tiret,
		// suivi de une lettre ou un chiffre
		//    ^[A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9]$
		// ou à l'expression :
		//    ^[A-Za-z0-9]+$    
		$ExpReg = '/^[A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9]$|^[A-Za-z0-9]+$/';
		foreach ($Domaines as $d) {
			if (! preg_match($ExpReg, $d)) 
			{
				return false;
			}
		}

		return true;
	}

/*
------------------------------------------------------------------------------------------------------------------------
Fonctions liées à la base de donnée
------------------------------------------------------------------------------------------------------------------------
*/
	/*
	*	Affichage d'un message d'erreur si une fonction mysql_ a ?chou?
	*/
	function cie_erreur() 
	{
		echo '<table cellpadding="2" cellspacing="2" align="center">',
				'<tr><td bgcolor="red"><p>',
					'Une erreur s\'est produite.</td></tr>',
				'<tr><td><p>Erreur : ', mysql_errno(),'</td></tr>',
				'<tr><td><p>', mysql_error(), '</td></tr></table>';
		exit();
	}
	
	/*
	*	Protection d'une chaine de caractères avant de l'enregistrer dans une base de données
	*		- ajout de \ devant les caractères spéciaux
	*		- protection des entités HTML
	*	@param $str string La chaine à protéger
	*	@return string La chaine protégée
	*/
	function cie_protect_text($str) 
	{
		if (! get_magic_quotes_gpc()) {
			$str = addslashes($str);
		}
		//~ $str = htmlentities($str);
		return $str;
	}
	
	/*
	*	Affichage d'une chaine issue de la base de données
	*		- retrait des \ devant les caract?res spéciaux
	*		- protection des entités HTML
	*	@param $str string La chaine à protéger
	*	@return string La cha?ie protégée
	*/
	function cie_affichage_text($str) 
	{
		$str = stripslashes($str);
		return ($str);
	}


/*
------------------------------------------------------------------------------------------------------------------------
Fonctions de base (formulaires)
------------------------------------------------------------------------------------------------------------------------
*/

	/*
	*	Génère une ligne d'un tableau à deux cases
	*	@param $case1	string	Contenu de la premiere case
	*	@param $case2	string	Contenu de la deuxieme case
	*	@return 			string	La ligne de code html correspondante
	*
	*	Note : Les cases peuvent bien évidemment contenir du code html !
	*/
	function cie_gen_ligne ( $case1, $case2 )
	{
		return "<tr><td> $case1 </td><td> $case2 </td></tr>";
	}
	
	/*
	*	Génère une ligen "séparatrice" dans un tableau. Elle se caractérise par un colspan de 2.
	*	@param	$case	string	Contenu (html ou non) de la case
	*	@return			string	Code Html correspondant
	*/
	function cie_gen_sep ( $case )
	{
		return "<tr><td colspan='2'> $case </td></tr>";
	}
	
	/*
	*	Génère un input
	*	@param $type	string	Type (text, password, ... )
	*	@param $name	string	Nom
	*	@param $value	string	Valeur de base
	*	@return 			string	La ligne de code html correspondante 
	*/
	function cie_gen_input ( $type, $name, $value )
	{
		return "<input type='$type' name='$name' value='$value'>";
	}
	
	/*
	*	Génère un textarea
	*	@param	$name	string	Nom
	*	@param	$value	string	Valeur
	*	@return			string	La ligne de code html correspondante
	*/
	function cie_gen_textarea ( $name, $value )
	{
		return "<textarea name='{$name}'>{$value}</textarea>";
	}
	
	/*
	*	Génère un select et ses options
	*	@param	$name	string	Nom
	*	@param	$i		int		Valeur de départ
	*	@param	$n		int		Valeur d'arrivée
	*	@param	$slctd	int		Valeur automatiquement séléctionnée
	*	@return			string	La ligne de code html correspondante
	*
	*	Note : Cette fonction à été développée dans le seul but de générer des select numériques pour les dates.
	*/
	function cie_gen_select ( $name, $i, $n, $slctd )
	{
		if( $i == -1 ) { $i = 0; }
	
		$str = "<select name='{$name}'><option value=''>--</option>";
		for ( $i ; $i <= $n; $i++ )
		{
			if ($i == $slctd) 	{ $str .= "<option value='{$i}' selected='yes'> {$i} </option>"; }
			else 			{ $str .= "<option value='{$i}'> {$i} </option>"; }
		}
		$str .= "</select>";
		return $str;
	}







?>