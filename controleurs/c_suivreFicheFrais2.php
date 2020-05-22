<?php
include("vues/v_sommaire.php");
$idVisiteur = $_SESSION['idVisiteur'];
$action = $_REQUEST['action'];
switch($action){
    case 'selectionnerUneFiche':{
        $lesMoisVisi= $pdo->getMoisVisiteurs();
        include("vues/v_listeVisiteurMois2.php"); //fiches à rembourser, fiches
                //a l'etat validé
        break;
    }
    case 'voirLaFiche':{
        $lesMoisVisi= $pdo->getMoisVisiteurs();
                $visiteur= $_REQUEST['lstFiche'];
                $mois=$_REQUEST['mois'];
                if ($visiteur && $mois){
                    $_SESSION['lstFiche']= $visiteur ;
                    $_SESSION['mois']= $mois ;
                }
                include("vues/v_listeVisiteurMois2.php");
                $visiteur = $_SESSION['lstFiche'];
                $mois = $_SESSION['mois'];
                
                if(isset ($_REQUEST['rembourserFiche'])){
                    $unEtat = 'RB';
                    $pdo->majEtatFicheFrais($visiteur,$mois,$unEtat);
                }

        $lesFraisForfait= $pdo->getLesFraisForfait($visiteur,$mois);
                $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($visiteur,$mois);
        $etat= 'VA';
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($visiteur,$mois,$etat);
        $numAnnee =substr($mois,0,4);
        $numMois =substr( $mois,4,2);
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $montantValide=$pdo->updateFraisForfait($mois,$visiteur);
        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
        $dateModif =  $lesInfosFicheFrais['dateModif'];
        $dateModif =  dateAnglaisVersFrancais($dateModif);
        include("vues/v_ficheValidee2.php");
    }
}
?>