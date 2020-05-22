<?php
include("vues/v_sommaire.php");
$idVisiteur = $_SESSION['idVisiteur'];
$action = $_REQUEST['action'];
switch($action){
	case 'selectionnerVisiteur':{
                $lesMois=getSixDerniersMois();
		$lesCles = array_keys( $lesMois );
		$moisASelectionner = $lesCles[0];
		$LesVisi=$pdo->getLesVisiteurs();
		include("vues/v_listeVisiteurs1.php");
		break;
	}
        case 'voirFiche':{
                $lesMois=getSixDerniersMois();
                $LesVisi=$pdo->getLesVisiteurs();
                $leVisiteur=isset($_REQUEST['lstVisiteur']) ? $_REQUEST['lstVisiteur'] : null;
                $leMois = isset($_REQUEST['lstMois']) ? $_REQUEST['lstMois'] : null;
                if ($leVisiteur && $leMois){
                    $_SESSION['lstMois']= $leMois ; 
                    $_SESSION['lstVisiteur'] =$leVisiteur;
                }
		$moisASelectionner = $leMois;
                include("vues/v_listeVisiteurs1.php");
                
                $leMois = $_SESSION['lstMois']; 
                $leVisiteur = $_SESSION['lstVisiteur'];
                
                if(isset ($_REQUEST['modifierFiche'])){
                    $lesFrais=$_REQUEST['lesfrais'];
                    if(lesQteFraisValides($lesFrais)){
                            $pdo->majFraisForfait($leVisiteur, $leMois, $lesFrais);
                            ajouterModification("Informations mises à jour.");
                            include("vues/v_message1.php");
                    }
                    else{
                            ajouterErreur("Les valeurs des frais doivent être numériques");
                            include("vues/v_erreurs.php");
                    }
                }
                
                if(isset ($_REQUEST['validerfiche'])){
                    $unEtat = 'VA';
                    $pdo->majEtatFicheFrais($leVisiteur,$leMois,$unEtat);
                    $leNbJustificatifs = $_REQUEST['leNbJustificatifs'];
                    $hey = $pdo->majNbJustificatifs($leVisiteur, $leMois, $leNbJustificatifs);
                }
                
                $lesFraisForfait= $pdo->getLesFraisForfait($leVisiteur,$leMois);
		$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($leVisiteur,$leMois);
                $etat= 'CL';
		$lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($leVisiteur,$leMois,$etat);
		if (is_array( $lesInfosFicheFrais)) {	
                    $numAnnee =substr( $leMois,0,4);
                    $numMois =substr( $leMois,4,2);
                    $libEtat = $lesInfosFicheFrais['libEtat'];
                    $montantValide=$pdo->updateFraisForfait($leMois,$leVisiteur);
                    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
                    $dateModif =  $lesInfosFicheFrais['dateModif'];
                    $dateModif =  dateAnglaisVersFrancais($dateModif);
                    include("vues/v_voirfiche1.php");
		}
		else{
                    ajouterErreur("Pas de fiche de frais pour ce visiteur ce mois");
                    include("vues/v_erreurs.php");
		}
                
            break;
	}

    case 'supprimerFraisHF':{
                $lesMois=getSixDerniersMois();
                $LesVisi=$pdo->getLesVisiteurs();
                $leVisiteur=isset($_REQUEST['lstVisiteur']) ? $_REQUEST['lstVisiteur'] : null;;
                $leMois = isset($_REQUEST['lstMois']) ? $_REQUEST['lstMois'] : null;
                if ($leVisiteur && $leMois){
                    $_SESSION['lstMois']= $leMois ; 
                    $_SESSION['lstVisiteur'] =$leVisiteur;
                }
		$moisASelectionner = $leMois;
                include("vues/v_listeVisiteurs1.php");
                
                $leMois = $_SESSION['lstMois']; 
                $leVisiteur = $_SESSION['lstVisiteur'];
                
                if(isset ($_REQUEST['modifierFiche'])){
                    $lesFrais=$_REQUEST['lesfrais'];
                    if(lesQteFraisValides($lesFrais)){
                            $pdo->majFraisForfait($leVisiteur, $leMois, $lesFrais);
                            ajouterModification("Informations mises à jour.");
                            include("vues/v_message1.php");
                    }
                    else{
                            ajouterErreur("Les valeurs des frais doivent être numériques");
                            include("vues/v_erreurs.php");
                    }
                }
                
                if(isset ($_REQUEST['validerfiche'])){
                    $unEtat = 'VA';
                    $pdo->majEtatFicheFrais($leVisiteur,$leMois,$unEtat);
                    $leNbJustificatifs = $_REQUEST['leNbJustificatifs'];
                    $hey = $pdo->majNbJustificatifs($leVisiteur, $leMois, $leNbJustificatifs);
                }
                
                $lesFraisForfait= $pdo->getLesFraisForfait($leVisiteur,$leMois);
		$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($leVisiteur,$leMois);
		$etat= 'CL';
		$lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($leVisiteur,$leMois,$etat);
		if (is_array( $lesInfosFicheFrais)) {	
                    $numAnnee =substr( $leMois,0,4);
                    $numMois =substr( $leMois,4,2);
                    $libEtat = $lesInfosFicheFrais['libEtat'];
                    $montantValide=$pdo->updateFraisForfait($leMois,$leVisiteur);
                    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
                    $dateModif =  $lesInfosFicheFrais['dateModif'];
                    $dateModif =  dateAnglaisVersFrancais($dateModif);
                    include("vues/v_voirfiche1.php");
                    
                $leMois = $_SESSION['lstMois']; 
                $leVisiteur = $_SESSION['lstVisiteur'];
                $idFrais = $_REQUEST['idFrais'];
                $libelle = $_REQUEST['libelle'];
                $supp = $pdo->setSupprimer($idFrais,$leVisiteur,$leMois,$libelle);
                #$pdo->majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs);
        
		break;
	}
    }
    case 'reporterFraisHF':{
                $lesMois=getSixDerniersMois();
                $LesVisi=$pdo->getLesVisiteurs();
                $leVisiteur=isset($_REQUEST['lstVisiteur']) ? $_REQUEST['lstVisiteur'] : null;;
                $leMois = isset($_REQUEST['lstMois']) ? $_REQUEST['lstMois'] : null;
                if ($leVisiteur && $leMois){
                    $_SESSION['lstMois']= $leMois ; 
                    $_SESSION['lstVisiteur'] =$leVisiteur;
                }
		$moisASelectionner = $leMois;
                include("vues/v_listeVisiteurs1.php");
                
                $leMois = $_SESSION['lstMois']; 
                $leVisiteur = $_SESSION['lstVisiteur'];
                
                if(isset ($_REQUEST['modifierFiche'])){
                    $lesFrais=$_REQUEST['lesfrais'];
                    if(lesQteFraisValides($lesFrais)){
                            $pdo->majFraisForfait($leVisiteur, $leMois, $lesFrais);
                            ajouterModification("Informations mises à jour.");
                            include("vues/v_message1.php");
                    }
                    else{
                            ajouterErreur("Les valeurs des frais doivent être numériques");
                            include("vues/v_erreurs.php");
                    }
                }
                
                if(isset ($_REQUEST['validerfiche'])){
                    $unEtat = 'VA';
                    $pdo->majEtatFicheFrais($leVisiteur,$leMois,$unEtat);
                    $leNbJustificatifs = $_REQUEST['leNbJustificatifs'];
                    $hey = $pdo->majNbJustificatifs($leVisiteur, $leMois, $leNbJustificatifs);
                }
                
                $lesFraisForfait= $pdo->getLesFraisForfait($leVisiteur,$leMois);
		$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($leVisiteur,$leMois);
		$etat= 'CL';
		$lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($leVisiteur,$leMois,$etat);
		if (is_array( $lesInfosFicheFrais)) {	
                    $numAnnee =substr( $leMois,0,4);
                    $numMois =substr( $leMois,4,2);
                    $libEtat = $lesInfosFicheFrais['libEtat'];
                    $montantValide=$pdo->updateFraisForfait($leMois,$leVisiteur);
                    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
                    $dateModif =  $lesInfosFicheFrais['dateModif'];
                    $dateModif =  dateAnglaisVersFrancais($dateModif);
                    include("vues/v_voirfiche1.php");
                    
                $leMois = $_SESSION['lstMois']; 
                $leVisiteur = $_SESSION['lstVisiteur'];
		$idFrais = $_REQUEST['idFrais'];
                $ceMois = $_REQUEST['ceMois'];
                $moisreporte = $pdo->reporter($idFrais,$leVisiteur,$ceMois);
		break;
	}
    }	
}
?>