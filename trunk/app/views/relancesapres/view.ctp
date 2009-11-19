<?php $this->pageTitle = 'Relances pour l\'APRE';?>
<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout d\'une relance';
    }
    else {
        $this->pageTitle = 'Relance d\'APRE ';
        $foyer_id = $this->data['Personne']['foyer_id'];
    }
?>
<div class="with_treemenu">
    <h1><?php echo 'Relance d\'APRE  ';?></h1>


<div id="ficheCI">
        <table>
            <tbody>
                <tr class="even">
                    <th><?php __( 'N° dossier APRE');?></th>
                    <td><?php echo Set::classicExtract( $apre, 'Apre.numeroapre' );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'Nom / Prénom bénéficiare' );?></th>
                    <td><?php echo ( $apre['Personne']['nom'].' '.$apre['Personne']['prenom'] );?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'Date de relance' );?></th>
                    <td><?php echo date_short( Set::classicExtract( $relanceapre, 'Relanceapre.daterelance' ) );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'Etat du dossier APRE' );?></th>
                    <td><?php echo Set::enum( Set::classicExtract( $relanceapre, 'Relanceapre.etatdossierapre' ), $options['etatdossierapre'] );?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'Commentaire' );?></th>
                    <td><?php echo Set::classicExtract( $relanceapre, 'Relanceapre.commentairerelance' );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'Pièces manquantes' );?></th>
                    <td><?php echo /*Set::classicExtract( $relanceapre, 'Relanceapre.etatdossierapre' )*/'';?></td>
                </tr>
            </tbody>
        </table>
</div>
</div>
<div class="clearer"><hr /></div>