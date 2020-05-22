<div id="contenu">
      <h2>Validation des fiches de frais</h2>
         
      <form method="POST"  action="index.php?uc=validerFicheFrais&action=voirFiche">
      <!--<div class="corpsForm">-->
          <div class="corpsForm">
          
            <legend>Visiteur et mois à selectionner</legend>    
      <p>
                <label for="lstVisiteur" accesskey="n">Visiteur : </label>
                <select id="lstVisiteur" name="lstVisiteur">
                    <?php
                    foreach ($LesVisi as $leVisiteur)
                    {
                        $idVisiteur = $leVisiteur['id'];
                        $nomVisiteur =  $leVisiteur['nom'];
                        $prenomVisiteur =  $leVisiteur['prenom']; 
                        
                    }
                    ?>
                    <option selected value="<?php echo $idVisiteur ?>"><?php echo  $nomVisiteur." ".$prenomVisiteur ?> </option>
                            <?php
                    echo "<input type ='hidden' name='id' value='$idVisiteur'>";
                    ?>

                </select>
            </p>

    <p>
   
        <label for="lstMois" accesskey="n">Mois : </label>
        <select id="lstMois" name="lstMois">
            <?php
      foreach ($lesMois as $unMois)
      {
          $mois = $unMois['mois'];
        $numAnnee =  $unMois['numAnnee'];
        $numMois =  $unMois['numMois'];
        if($mois == $moisASelectionner){
        ?>
        <option selected value="<?php echo $mois ?>"><?php echo  $numMois."/".$numAnnee ?> </option>
        <?php 
        }
        else{ ?>
        <option value="<?php echo $mois ?>"><?php echo  $numMois."/".$numAnnee ?> </option>
        <?php 
        }
      
      }
           echo "<input type ='hidden' name='idVisiteur' value='$mois'>";
       ?>    
            
        </select>
      </p>
      <p>
      <!--<div class="piedForm">-->
      <p>
        <centre><input id="ok" type="submit" value="Valider" size="20" /> </centre>
        <input id="annuler" type="reset" value="Effacer" size="20" /> 
      </p> 
      <!--</div>-->
        
       
      </div>
      </form>