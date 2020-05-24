    <!-- Division pour le sommaire -->
    <div id="menuGauche">
     <div id="infosUtil">
    
        <h2>
    
</h2>
    
      </div>  
        <ul id="menuList">
			<li >
				  
				<?php echo $_SESSION['statut']." :" ?> <br> <?php echo $_SESSION['prenom']."  ".$_SESSION['nom'] ;
                                if($_SESSION['statut']=="comptable")
                                {
                                    echo "<li class='smenu'>
              <a href='index.php?uc=validerFicheFrais&action=selectionnerVisiteur' title='Fiches de frais à valider '>- Fiches de frais à valider </a>
           </li>
           <li class='smenu'>
              <a href='index.php?uc=suivreFicheFrais&action=selectionnerUneFiche' title='Suivi des fiches de frais'>- Suivi des fiches de frais</a>
           </li>
           <li class='smenu'>
              <a href='index.php?uc=connexion&action=deconnexion' title='Se déconnecter'>- Déconnexion</a>
           </li>";
                                }
                                else
                                {
                                    echo "<li class='smenu'>
              <a href='index.php?uc=gererFrais&action=saisirFrais' title='Saisie fiche de frais '>- Saisie fiche de frais</a>
           </li>
           <li class='smenu'>
              <a href='index.php?uc=etatFrais&action=selectionnerMois' title='Consultation de mes fiches de frais'>- Mes fiches de frais</a>
           </li>
 	   <li class='smenu'>
              <a href='index.php?uc=connexion&action=deconnexion' title='Se déconnecter'>- Déconnexion</a>
           </li>";
                                }
                                        
                                        
                                        
                                        
                                        ?>
			</li>
           
         </ul>
        
    </div>
    