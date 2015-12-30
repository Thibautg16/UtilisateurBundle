CHANGELOG for 0.2
===================

* 0.2 (30-12-2015)
	* Feature : gestion des groupes (Entite Groupe)
	* Feature : gestion des routes (Entite Route)
	* Feature : gestioon des autorisations à partir des routes de l'application
    * Update SecurityController 
	* Update password level (sha512 + salt)
	* Bug : inversedBy="routes" => inversedBy="groupes" (Entite Groupe)
	* Bug : thibautg16_utilisateurs_liste => thibautg16_utilisateur_liste
	* Bug : autoload psr4 => autoload psr0 (Gestion Bdd du bundle)

	
* 0.1 (22-08-2015)
	* Premier Commit