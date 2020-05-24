
<fieldset><legend>Fiche de frais du mois <?php echo $numMois."-".$numAnnee?> : 
    </legend>
    <!--<div class="encadre">-->
      <form method="POST"  action="index.php?uc=validerFicheFrais&action=voirFiche">
    <p>
        Etat : <?php echo $libEtat?> depuis le <?php echo $dateModif?> <br> Montant validé : <?php echo $montantValide?>
              
                     
    </p>
    <table class="listeLegere">
       <caption>Eléments forfaitisés </caption>
        <tr>
         <?php
         foreach ( $lesFraisForfait as $unFrais )
     {
      $idFrais = $unFrais['idfrais'];
      $libelle = $unFrais['libelle'];
      $quantite = $unFrais['quantite'];
    ?>  
      <th> <?php echo $libelle?>
      </th>
     <?php
        }
    ?>
    </tr>
        <tr>
        <?php
          foreach (  $lesFraisForfait as $unFraisForfait  ) 
      {
        $quantite = $unFraisForfait['quantite'];
        $idFrais = $unFraisForfait['idfrais'];
    ?>
                <td class="qteForfait"><p>
            
            <input type="text" id="idFrais" name="lesfrais[<?php echo $idFrais?>]" size="10" maxlength="5" value="<?php echo $quantite?>" >
          </p> </td>
     <?php
          }
    ?>
    </table>
    <div>
      <p>
        <input id="ok" type="submit" name="modifierFiche" value="Valider" size="20" />
        <input id="annuler" type="reset" value="Effacer" size="20" />
      </p> 
      </div>
    </form>
      <br>
    <table class="listeLegere">
       <caption>Descriptif des éléments hors forfait -
      <input type="text" id="nbJustificatifs" name="nbJustificatifs" size="10" maxlength="5"
      value="<?php echo $nbJustificatifs?>" >
        justificatifs reçus -
       </caption>
             <tr>
                <th class="date">Date</th>
                <th class="libelle">Libellé</th>
                <th class='montant'>Montant</th>
                <th colspan="2" class='action'>Action</th>
             </tr>
        <?php      
          foreach ( $lesFraisHorsForfait as $unFraisHorsForfait ) 
      {
      $id = $unFraisHorsForfait['id'];
      $mois = $unFraisHorsForfait['mois'];
      $date = $unFraisHorsForfait['date'];
      $libelle = $unFraisHorsForfait['libelle'];
      $montant = $unFraisHorsForfait['montant'];

    ?>
             <tr>
                <td><?php echo $date ?></td>
                <td><?php echo $libelle ?></td>
                <td><?php echo $montant ?></td>
                <td><a href="index.php?uc=validerFicheFrais&action=supprimerFraisHF&idFrais=<?php echo $id ?>&libelle=<?php echo $libelle ?>" 
        onclick="return confirm('Voulez-vous vraiment supprimer ce frais?');">Supprimer</a></td>
                <td><a href="index.php?uc=validerFicheFrais&action=reporterFraisHF&idFrais=<?php echo $id ?>&ceMois=<?php echo $mois ?>" 
        onclick="return confirm('Voulez-vous vraiment reporter ce frais?');">Reporter</a></td>
             </tr>
        <?php 
          }
    ?>
    </table>
    <div>
      <p>
        <input id="ok" type="submit" name=validerfiche value="Valider la fiche" size="20" />
      </p> 
      </div>
  </div>
  </fieldset>
 













