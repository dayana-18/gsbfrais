<div id="contenu">
      <h2>Validation des fiches de frais</h2>
         
      <form method="POST"  action="index.php?uc=suivreFicheFrais&action=voirLaFiche">
      <!--<div class="corpsForm">-->
          <div class="corpsForm">
          
            <legend>Fiches des visiteurs et mois à selectionner</legend>    
      <p>
                <label for="lstFiche" accesskey="n">Visiteur : </label>
                <select id="lstFiche" name="lstFiche">
                    <?php
                    foreach ($lesMoisVisi as $unVisiteur)
                    {
                        $mois = $unVisiteur['mois'];
                        $idVisiteur = $unVisiteur['id'];
                        $nomVisiteur =  $unVisiteur['nom'];
                        $prenomVisiteur =  $unVisiteur['prenom'];
                        $numAnnee =substr( $mois,0,4);
			$numMois =substr( $mois,4,2);
                    }
                    ?>
                    <option selected value="<?php echo $idVisiteur ?>"><?php echo  $numMois."/".$numAnnee." - ".$nomVisiteur." ".$prenomVisiteur ?> 
                    </option>
                     <?php
                    echo "<input type ='hidden' name='mois' value='$mois'>";
                    ?>
                </select>
            </p>
      <!--<div class="piedForm">-->
      <p>
        <centre><input id="ok" type="submit" value="Valider" size="20" /> </centre>
        <input id="annuler" type="reset" value="Effacer" size="20" /> 
      </p> 
      <!--</div>-->
        
       
      </div>
      </form>