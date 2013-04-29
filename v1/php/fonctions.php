<?php

/*
------------------------------------------------------------------------------------------------------------------------
Autres Fonctions
------------------------------------------------------------------------------------------------------------------------
*/

	/*
	 *	D�sassemble une chaine du type mot1,mot2,mot3 en un tableau t[0] = mot1, t[1] = mot2, t[2] = mot3
	 *	@param	$str		String		La chaine en question
	 *	@return			Array		Tableau � index num�rique
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
	*	V�rifie que l'utilisateur est bien connect�
	*
	*	Note : N'est utilis�e que dans les pages d'administration.
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
	* 	V�rifier une adresse e-mail en conformit� avec la norme RFC 2822(ou a peu pr�s (:-))
	*	-- Nota : fonction reprise du tutoriel PHP --
	*	@param 	$Mail 	string 	L'adresse email � tester
	*	@return 			boolean 	Vrai si l'adresse est valide, faux sinon
	*/
	function cie_test_mail( $Mail) 
	{
		// Nous commen�ons par v�rifier que l'adresse contient bien un
		// @ (et un seul), et que les deux parties sont de longueur correcte.
		// Il doit y avoir de 1 � 64 caract�res, @ exclus
		//    [^@]{1,64}
		// suivis de @ et de 1 � 255 caract�res, @ exclus
		//    @[^@]{1,255}
		// Comme la chaine ne doit rien avoir d'autre, on l'entoure
		// avec ^ (commence) et $ (fini)
		$ExpReg = '/^[^@]{1,64}@[^@]{1,255}$/';
		if (! preg_match($ExpReg, $Mail)) 
		{
			return false;
		}

		// On d�coupe l'adresse en 2 parties : locale et domaine
		// Les tests seront plus faciles � faire.
		$Parties = explode('@', $Mail);

		// La partie locale doit etre conforme � une chaine de caract�res pr�d�finis
		// et elle ne doit pas commencer par un point (.)
		//    ^[A-Za-z0-9!#$%&'*+-\/=?^_`{|}~]
		// On peut ensuite avoir de 0 � 62 autres caract?res pr?d?finis
		//    [A-Za-z0-9!#$%&'*+-\/=?^_`{|}~\.]{0,62}$
		// Ce qui donne :
		//    ^[A-Za-z0-9!#$%&'*+-\/=?^_`{|}~][A-Za-z0-9!#$%&'*+-\/=?^_`{|}~\.]{0,62}$
		//
		// La cha?ne peute aussi etre compos�e de n'importe quelles caract�res,
		// sauf \, entour�s de guillemets :
		//    ^"[^(\\|")]{1,62}"$
		// Dans cette derni�re expression, la sous-expression entre crochets
		// signifie que l'on exclu (caract�re ^) le caract�res \ (prot�g� ici
		// par un \) et le caract�re "
		//
		// Les deux expressions sont reli�es avec un ou (|).
		// Pour ne pas provoquer de collision avec les guillemets simples ou doubles
		// contenus dans les expressions, nous construisons la variable
		// par plusieurs concat�nations.
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
		// On commence r�cup�rer les parties s�par�es par des points (.)
		$Domaines = explode('.', $Parties[1]);

		// Si la premi�re partie est compos�e de 1 � 3 chiffres, on consid�re
		// que le domaine est une adresse IP de la forme 123.123.12.1 soit
		//    ^(\d{1,3}\.){3}\d{1,3}$
		// Cette v�rification d'adresse IP est un peu faible ...
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
		// On consid�re que le domaine doit etre au moins compos� de 2 parties
		// comme monsite.com, bien que dans la norme rien ne l'oblige.
		if (count($Domaines) < 2) {
		return false;    
		}
		// Chacune des parties du domaine doit etre conforme � :
		// une lettre ou un chiffre, suivi de 0 � 61 lettres, chiffres ou tiret,
		// suivi de une lettre ou un chiffre
		//    ^[A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9]$
		// ou � l'expression :
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
Fonctions li�es � la base de donn�e
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
	*	Protection d'une chaine de caract�res avant de l'enregistrer dans une base de donn�es
	*		- ajout de \ devant les caract�res sp�ciaux
	*		- protection des entit�s HTML
	*	@param $str string La chaine � prot�ger
	*	@return string La chaine prot�g�e
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
	*	Affichage d'une chaine issue de la base de donn�es
	*		- retrait des \ devant les caract?res sp�ciaux
	*		- protection des entit�s HTML
	*	@param $str string La chaine � prot�ger
	*	@return string La cha?ie prot�g�e
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
	*	G�n�re une ligne d'un tableau � deux cases
	*	@param $case1	string	Contenu de la premiere case
	*	@param $case2	string	Contenu de la deuxieme case
	*	@return 			string	La ligne de code html correspondante
	*
	*	Note : Les cases peuvent bien �videmment contenir du code html !
	*/
	function cie_gen_ligne ( $case1, $case2 )
	{
		return "<tr><td> $case1 </td><td> $case2 </td></tr>";
	}
	
	/*
	*	G�n�re une ligen "s�paratrice" dans un tableau. Elle se caract�rise par un colspan de 2.
	*	@param	$case	string	Contenu (html ou non) de la case
	*	@return			string	Code Html correspondant
	*/
	function cie_gen_sep ( $case )
	{
		return "<tr><td colspan='2'> $case </td></tr>";
	}
	
	/*
	*	G�n�re un input
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
	*	G�n�re un textarea
	*	@param	$name	string	Nom
	*	@param	$value	string	Valeur
	*	@return			string	La ligne de code html correspondante
	*/
	function cie_gen_textarea ( $name, $value )
	{
		return "<textarea name='{$name}'>{$value}</textarea>";
	}
	
	/*
	*	G�n�re un select et ses options
	*	@param	$name	string	Nom
	*	@param	$i		int		Valeur de d�part
	*	@param	$n		int		Valeur d'arriv�e
	*	@param	$slctd	int		Valeur automatiquement s�l�ctionn�e
	*	@return			string	La ligne de code html correspondante
	*
	*	Note : Cette fonction � �t� d�velopp�e dans le seul but de g�n�rer des select num�riques pour les dates.
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