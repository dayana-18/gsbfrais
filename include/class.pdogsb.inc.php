<?php
/** 
 * Classe d'accès aux données. 
 
 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO 
 * $monPdoGsb qui contiendra l'unique instance de la classe
 
 * @package default
 * @author Cheri Bibi
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */

class PdoGsb{   		
      	private static $serveur='mysql:host=localhost';
      	private static $bdd='dbname=gsb_frais';   		
      	private static $user='root' ;    		
      	private static $mdp='dayanavr' ;	
		private static $monPdo;
		private static $monPdoGsb=null;
/**
 * Constructeur privé, crée l'instance de PDO qui sera sollicitée
 * pour toutes les méthodes de la classe
 */				
	private function __construct(){
    	PdoGsb::$monPdo = new PDO(PdoGsb::$serveur.';'.PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp); 
		PdoGsb::$monPdo->query("SET CHARACTER SET utf8");
	}
	public function _destruct(){
		PdoGsb::$monPdo = null;
	}
/**
 * Fonction statique qui crée l'unique instance de la classe
 
 * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
 
 * @return l'unique objet de la classe PdoGsb
 */
	public  static function getPdoGsb(){
		if(PdoGsb::$monPdoGsb==null){
			PdoGsb::$monPdoGsb= new PdoGsb();
		}
		return PdoGsb::$monPdoGsb;  
	}
/**
 * Retourne les informations d'un Visiteur
 
 * @param $login 
 * @param $mdp
 * @return l'id, le nom et le prénom sous la forme d'un tableau associatif 
*/
	public function getInfosVisiteur($login, $mdp){
		$req = "select Visiteur.id as id, Visiteur.nom as nom, Visiteur.prenom as prenom, Visiteur.statut as statut from Visiteur 
		where Visiteur.login='$login' and Visiteur.mdp='$mdp'";
		$rs = PdoGsb::$monPdo->query($req);
		$ligne = $rs->fetch();
		return $ligne;
	}
        
/**
 * Retourne sous forme d'un tableau associatif toutes les lignes de frais hors forfait
 * concernées par les deux arguments
 
 * La boucle foreach ne peut être utilisée ici car on procède
 * à une modification de la structure itérée - transformation du champ date-
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return tous les champs des lignes de frais hors forfait sous la forme d'un tableau associatif 
*/
	public function getLesFraisHorsForfait($idVisiteur,$mois){
	    $req = "select * from LigneFraisHorsForfait where LigneFraisHorsForfait.idVisiteur ='$idVisiteur' 
		and LigneFraisHorsForfait.mois = '$mois' ";	
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		$nbLignes = count($lesLignes);
		for ($i=0; $i<$nbLignes; $i++){
			$date = $lesLignes[$i]['date'];
			$lesLignes[$i]['date'] =  dateAnglaisVersFrancais($date);
		}
		return $lesLignes; 
	}
/**
 * Retourne le nombre de justificatif d'un visiteur pour un mois donné
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return le nombre entier de justificatifs 
*/
	public function getNbjustificatifs($idVisiteur, $mois){
		$req = "select fichefrais.nbjustificatifs as nb from  fichefrais where fichefrais.idVisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne['nb'];
	}
/**
 * Retourne sous forme d'un tableau associatif toutes les lignes de frais au forfait
 * concernées par les deux arguments
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return l'id, le libelle et la quantité sous la forme d'un tableau associatif 
*/
	public function getLesFraisForfait($idVisiteur, $mois){
		$req = "select Fraisforfait.id as idfrais, Fraisforfait.libelle as libelle, 
		LigneFraisForfait.quantite as quantite from LigneFraisForfait inner join Fraisforfait 
		on Fraisforfait.id = LigneFraisForfait.idFraisforfait
		where LigneFraisForfait.idVisiteur ='$idVisiteur' and LigneFraisForfait.mois='$mois' 
		order by LigneFraisForfait.idFraisforfait";	
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes; 
	}

/**
 * Retourne tous les id de la table FraisForfait
 
 * @return un tableau associatif 
*/
	public function getLesIdFrais(){
		$req = "select Fraisforfait.id as idfrais from Fraisforfait order by Fraisforfait.id";
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes;
	}
/**
 * Met à jour la table LigneFraisForfait
 
 * Met à jour la table LigneFraisForfait pour un Visiteur et
 * un mois donné en enregistrant les nouveaux montants
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @param $lesFrais tableau associatif de clé idFrais et de valeur la quantité pour ce frais
 * @return un tableau associatif 
*/
	public function majFraisForfait($idVisiteur, $mois, $lesFrais){
		$lesCles = array_keys($lesFrais);
		foreach($lesCles as $unIdFrais){
			$qte = $lesFrais[$unIdFrais];
			$req = "update LigneFraisForfait set LigneFraisForfait.quantite = $qte
			where LigneFraisForfait.idVisiteur = '$idVisiteur' and LigneFraisForfait.mois = '$mois'
			and LigneFraisForfait.idFraisforfait = '$unIdFrais'";
			PdoGsb::$monPdo->exec($req);
		}
		
	}

/**
 * met à jour le nombre de justificatifs de la table ficheFrais
 * pour le mois et le Visiteur concerné
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
*/
	public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs){
		$req = "update fichefrais set nbjustificatifs = $nbJustificatifs 
		where fichefrais.idVisiteur = '$idVisiteur' and fichefrais.mois = '$mois'";
		PdoGsb::$monPdo->exec($req);	
	}
/**
 * Teste si un Visiteur possède une fiche de frais pour le mois passé en argument
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return vrai ou faux 
*/	
	public function estPremierFraisMois($idVisiteur,$mois)
	{
		$ok = false;
		$req = "select count(*) as nblignesfrais from fichefrais 
		where fichefrais.mois = '$mois' and fichefrais.idVisiteur = '$idVisiteur'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		if($laLigne['nblignesfrais'] == 0){
			$ok = true;
		}
		return $ok;
	}
/**
 * Retourne le dernier mois en cours d'un Visiteur
 
 * @param $idVisiteur 
 * @return le mois sous la forme aaaamm
*/	
	public function dernierMoisSaisi($idVisiteur){
		$req = "select max(mois) as dernierMois from fichefrais where fichefrais.idVisiteur = '$idVisiteur'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		$dernierMois = $laLigne['dernierMois'];
		return $dernierMois;
	}
	
/**
 * Crée une nouvelle fiche de frais et les lignes de frais au forfait pour un Visiteur et un mois donnés
 
 * récupère le dernier mois en cours de traitement, met à 'CL' son champs idEtat, crée une nouvelle fiche de frais
 * avec un idEtat à 'CR' et crée les lignes de frais forfait de quantités nulles 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
*/
	public function creeNouvellesLignesFrais($idVisiteur,$mois){
		$dernierMois = $this->dernierMoisSaisi($idVisiteur);
		$laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur,$dernierMois);
		if($laDerniereFiche['idEtat']=='CR'){
				$this->majEtatFicheFrais($idVisiteur, $dernierMois,'CL');
				
		}
		$req = "insert into fichefrais(idVisiteur,mois,nbJustificatifs,montantValide,dateModif,idEtat) 
		values('$idVisiteur','$mois',0,0,now(),'CR')";
		PdoGsb::$monPdo->exec($req);
		$lesIdFrais = $this->getLesIdFrais();
		foreach($lesIdFrais as $uneLigneIdFrais){
			$unIdFrais = $uneLigneIdFrais['idfrais'];
			$req = "insert into LigneFraisForfait(idVisiteur,mois,idFraisForfait,quantite) 
			values('$idVisiteur','$mois','$unIdFrais',0)";
			PdoGsb::$monPdo->exec($req);
		 }
	}

/**
 * Crée un nouveau frais hors forfait pour un Visiteur un mois donné
 * à partir des informations fournies en paramètre
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @param $libelle : le libelle du frais
 * @param $date : la date du frais au format français jj//mm/aaaa
 * @param $montant : le montant
*/
	public function creeNouveauFraisHorsForfait($idVisiteur,$mois,$libelle,$date,$montant){
		$dateFr = dateFrancaisVersAnglais($date);
		$req = "insert into LigneFraisHorsForfait(id,idVisiteur,mois,libelle,date,montant) 
		values(null,'$idVisiteur','$mois','$libelle','$dateFr','$montant')";
		PdoGsb::$monPdo->exec($req);
	}
/**
 * Supprime le frais hors forfait dont l'id est passé en argument
 
 * @param $idFrais 
*/
	public function supprimerFraisHorsForfait($idFrais){
		$req = "delete from LigneFraisHorsForfait where LigneFraisHorsForfait.id =$idFrais ";
		PdoGsb::$monPdo->exec($req);
	}


/**
 * Retourne les mois pour lesquel un Visiteur a une fiche de frais
 
 * @param $idVisiteur 
 * @return un tableau associatif de clé un mois -aaaamm- et de valeurs l'année et le mois correspondant 
*/
	public function getLesMoisDisponibles($idVisiteur){
		$req = "select fichefrais.mois as mois from  fichefrais where fichefrais.idVisiteur ='$idVisiteur' 
		order by fichefrais.mois desc ";
		$res = PdoGsb::$monPdo->query($req);
		$lesMois =array();
		$laLigne = $res->fetch();
		while($laLigne != null)	{
			$mois = $laLigne['mois'];
			$numAnnee =substr( $mois,0,4);
			$numMois =substr( $mois,4,2);
			$lesMois["$mois"]=array(
		     "mois"=>"$mois",
		    "numAnnee"  => "$numAnnee",
			"numMois"  => "$numMois"
             );
			$laLigne = $res->fetch(); 		
		}
		return $lesMois;
	}
        
/**FUNCTION
 * Retourne les Visiteurs
*/
	public function getLesVisiteurs(){
		$req = "select DISTINCT Visiteur.id,Visiteur.nom,Visiteur.prenom from Visiteur,fichefrais,Etat where
                    Visiteur.id=fichefrais.idVisiteur and fichefrais.idEtat=Etat.id and idEtat='CL' and Visiteur.statut=0 ;";
		$res = PdoGsb::$monPdo->query($req);
		$LesVisi = $res->fetchAll();
		return $LesVisi;
	}
        
        /**
         * #FUNCTION
         * @param type $idVisiteur
         * @param type $mois
         */
        public function nouvelleFicheFrais($idVisiteur,$mois){
        #$laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur,$mois,$Etat);
        $req = "insert into fichefrais(idVisiteur,mois,nbJustificatifs,montantValide,dateModif,idEtat) 
        values('$idVisiteur','$mois',0,0,now(),'CR')";
        PdoGsb::$monPdo->exec($req);
        }

        
   #FUNCTION modifié     
/**
 * Retourne les informations d'une fiche de frais d'un Visiteur pour un mois donné
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return un tableau avec des champs de jointure entre une fiche de frais et la ligne d'état 
*/	
	public function getLesInfosFicheFrais($idVisiteur,$mois,$Etat){
        $req = "select fichefrais.idEtat as idEtat, fichefrais.dateModif as dateModif, fichefrais.nbJustificatifs as nbJustificatifs, 
            fichefrais.montantValide as montantValide, Etat.libelle as libEtat from  fichefrais inner join Etat on fichefrais.idEtat = Etat.id 
            where fichefrais.idVisiteur ='$idVisiteur' and fichefrais.mois = '$mois' and
                        Etat.id='$Etat'";
        $res = PdoGsb::$monPdo->query($req);
        $laLigne = $res->fetch();
        return $laLigne;
    }

        #FUNCTION MODIFIE
/**
 * Modifie l'état et la date de modification d'une fiche de frais
 
 * Modifie le champ idEtat et met la date de modif à aujourd'hui
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 */
	public function majEtatFicheFrais($idVisiteur,$mois,$Etat){
		$req = "update ficheFrais set idEtat = '$Etat', dateModif = now() 
		where fichefrais.idVisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
		PdoGsb::$monPdo->exec($req);
	}

#FUNCTION
      /*  public function modifierLignesFrais($idVisiteur,$mois){
		/*$laDerniereFiche = $this->getInfosFicheFraisValide($idVisiteur,$mois);
		$req = "insert into fichefrais(idVisiteur,mois,nbJustificatifs,montantValide,dateModif,idEtat) 
		values('$idVisiteur','$mois',0,0,now(),'CL')";
		PdoGsb::$monPdo->exec($req);
		$lesIdFrais = $this->getLesIdFrais();
		foreach($lesIdFrais as $uneLigneIdFrais){
			$unIdFrais = $uneLigneIdFrais['idfrais'];
			$req = "insert into LigneFraisForfait(idVisiteur,mois,idFraisForfait,quantite) 
			values('$idVisiteur','$mois','$unIdFrais',0)";
			PdoGsb::$monPdo->exec($req);
		 }
	}*/
        
        
        #Function
        
        public function updateFraisForfait($mois,$leVisiteur){
            $req ="SELECT sum(lff.quantite*ff.montant) as C1 from Fraisforfait ff, LigneFraisForfait lff
                    where ff.id=lff.idFraisForfait and lff.idVisiteur='$leVisiteur' AND lff.mois='$mois' ";
            $reqhf = "SELECT sum(montant) as C2 from LigneFraisHorsForfait
                    where idVisiteur='$leVisiteur' AND mois='$mois' AND upper(libelle) NOT LIKE 'REFUSE%' ";
            $res = PdoGsb::$monPdo->query($req);
            $res= $res->fetch();
            $reshf = PdoGsb::$monPdo->query($reqhf);
            $reshf= $reshf->fetch();
            $montantValide=$res['C1']+$reshf['C2'];
            $reqf="UPDATE fichefrais SET montantValide='$montantValide' where idVisiteur='$leVisiteur' AND mois='$mois' ";
            $reqf = PdoGsb::$monPdo->query($reqf);
            return $montantValide;
         }
     
#FUNCTION
        public function getMoisVisiteurs(){
            $req = "select Visiteur.id as id,fichefrais.mois as mois ,Visiteur.nom as nom,Visiteur.prenom as prenom from Visiteur,fichefrais,
                Etat where Visiteur.id=fichefrais.idVisiteur and fichefrais.idEtat=Etat.id and idEtat='VA' and Visiteur.statut=0;";
            $res = PdoGsb::$monPdo->query($req);
            $laLigne = $res->fetchAll();
            return $laLigne;
        }
 
#FUNCTION
        public function setSupprimer($idFraisHF,$leVisiteur,$mois,$libelleFHF){
        $reqsup = "UPDATE LigneFraisHorsForfait SET libelle = 'REFUSE:$libelleFHF' WHERE id='$idFraisHF' AND idVisiteur='$leVisiteur' AND libelle='$libelleFHF' AND mois='$mois'";
        PdoGsb::$monPdo->query($reqsup);
        }

#FUNCTION
        public function savoirSiRefuser($idFrais) {
        $req = "select * from LigneFraisHorsForfait WHERE libelle like 'REFUSE%' and id='$idFrais'";
        $res = PdoGsb::$monPdo->query($req);
        $laLigne = $res->fetchall();
        if (empty($laLigne)) {
            return 0; //pas refuser
        } else {
            return 1; //refuser
        }

    }
    
#FUNCTION
        public function reporter($idFrais,$leVisiteur,$mois){
            $req = "select mois from LigneFraisHorsForfait where LigneFraisHorsForfait.id ='$idFrais' AND idVisiteur='$leVisiteur' AND mois='$mois'";
            $res = PdoGsb::$monPdo->query($req);
            $res= $res->fetch();
            $mois = $res['mois'];
            $numAnnee =substr( $mois,0,4);
            $numMois =substr( $mois,4,2);
            $nextmois = getMoisNext($numAnnee, $numMois);
            #verifier que la fiche suivante existe, sinon créer une
            if ( $this->getLesInfosFicheFrais($leVisiteur,$nextmois,'CR') == 0){
                $this->nouvelleFicheFrais($leVisiteur,$nextmois);
            }
            $req2 = "update LigneFraisHorsForfait SET mois ='$nextmois' where LigneFraisHorsForfait.id ='$idFrais' AND idVisiteur='$leVisiteur' AND mois='$mois'";
            $res2 = PdoGsb::$monPdo->query($req2);
            return $nextmois;
        }
        
        

}//fin classe pdo


?>