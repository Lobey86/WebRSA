<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Gestion des contrats d\'insertion';?>

<h1>Gestion des Contrats d'insertion</h1>

<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsOnValue( 'ContratinsertionDecisionCi', [ 'ContratinsertionDatevalidationCiDay', 'ContratinsertionDatevalidationCiMonth', 'ContratinsertionDatevalidationCiYear' ], 'V', false );
    });
</script>


<?php
    if( is_array( $this->data ) ) {
        echo '<ul class="actionMenu"><li>'.$html->link(
            $html->image(
                'icons/application_form_magnify.png',
                array( 'alt' => '' )
            ).' Formulaire',
            '#',
            array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
        ).'</li></ul>';
    }
?>

<?php echo $form->create( 'Cohorteci', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( is_array( $this->data ) ? 'folded' : 'unfolded' ) ) );?>
    <fieldset>
        <legend>Recherche de Contrat d'insertion</legend>
            <?php echo $form->input( 'Cohorteci.date_saisi_ci', array( 'label' => 'Date de saisie du contrat', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' )+10, 'minYear' => date( 'Y' ) - 10, 'empty' => true ) );?>
            <?php echo $form->input( 'Cohorteci.locaadr', array( 'label' => 'Commune de l\'allocataire ', 'type' => 'text' ) );?>
            <?php echo $form->input( 'Cohorteci.serviceinstructeur_id', array( 'label' => 'Contrat envoyé par ', 'type' => 'select' , 'options' => $typeservice, 'empty' => true ) );?>
            <?php echo $form->input( 'Cohorteci.decision_ci', array( 'label' => 'Statut du contrat', 'type' => 'select', 'options' => $decision_ci, 'empty' => true ) ); ?>
            <?php echo $form->input( 'Cohorteci.datevalidation_ci', array( 'label' => '', 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true)  ); ?>

    </fieldset>

    <div class="submit noprint">
        <?php echo $form->button( 'Filtrer', array( 'type' => 'submit' ) );?>
        <?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>
<?php echo $form->end();?>

<!-- Résultats -->
<?php if( isset( $contrats ) ):?>

    <h2 class="noprint">Résultats de la recherche</h2>

    <?php if( is_array( $contrats ) && count( $contrats ) > 0 ):?>
        <?php /*require( 'index.pagination.ctp' )*/?>
        <?php echo $form->create( 'GestionContrat', array( 'url'=> Router::url( null, true ) ) );?>
            <table id="searchResults" class="tooltips_oupas">
                <thead>
                    <tr>
                        <th>N° Dossier</th>
                        <th>Nom de l'allocataire</th>
                        <th>Commune de l'allocataire</th>
                        <th>Date début contrat</th>
                        <th>Date fin contrat</th>
                        <th>Statut actuel</th>
                        <th>Décision</th>
                        <th>Date validation</th>
                        <th>Observations</th>
                        <th class="action">Action</th>
                        <th class="innerTableHeader">Informations complémentaires</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach( $contrats as $index => $contrat ):?>
                        <?php
                        $innerTable = '<table id="innerTable'.$index.'" class="innerTable">
                            <tbody>
                                <tr>
                                    <th>Date naissance</th>
                                    <td>'.h( date_short( $contrat['Personne']['dtnai'] ) ).'</td>
                                </tr>
                                <tr>
                                    <th>Numéro CAF</th>
                                    <td>'.h( $contrat['Dossier']['matricule'] ).'</td>
                                </tr>
                                <tr>
                                    <th>NIR</th>
                                    <td>'.h( $contrat['Personne']['nir'] ).'</td>
                                </tr>
                                <tr>
                                    <th>Code postal</th>
                                    <td>'.h( $contrat['Adresse']['codepos'] ).'</td>
                                </tr>
                            </tbody>
                        </table>';
                            $title = $contrat['Dossier']['numdemrsa'];

                            echo $html->tableCells(
                                array(
                                    h( $contrat['Dossier']['numdemrsa'] ),
                                    h( $contrat['Personne']['nom'].' '.$contrat['Personne']['prenom'] ),
                                    h( $contrat['Adresse']['locaadr'] ),
                                    h( date_short( $contrat['Contratinsertion']['dd_ci'] ) ),
                                    h( date_short( $contrat['Contratinsertion']['df_ci'] ) ),
                                    h( $decision_ci[$contrat['Contratinsertion']['decision_ci']].' '.date_short( $contrat['Contratinsertion']['datevalidation_ci'] ) ),// statut BD
                                    $form->input( 'Contratinsertion.'.$index.'.id', array( 'label' => false, 'type' => 'hidden', 'value' => $contrat['Contratinsertion']['id'] ) ).
                                        $form->input( 'Contratinsertion.'.$index.'.dossier_id', array( 'label' => false, 'type' => 'hidden', 'value' => $contrat['Dossier']['id'] ) ).
                                        $form->input( 'Contratinsertion.'.$index.'.decision_ci', array( 'label' => false, 'type' => 'select', 'options' => $decision_ci, 'value' => $contrat['Contratinsertion']['proposition_decision_ci'] ) ),
                                    h( date_short( $contrat['Contratinsertion']['proposition_datevalidation_ci'] ) ).
                                     $form->input( 'Contratinsertion.'.$index.'.datevalidation_ci', array( 'label' => false, 'type' => 'hidden', 'value' => $contrat['Contratinsertion']['proposition_datevalidation_ci'] ) ),
                                    $form->input( 'Contratinsertion.'.$index.'.observ_ci', array( 'label' => false, 'type' => 'text', 'rows' => 2, 'value' => $contrat['Contratinsertion']['observ_ci'] ) ),
                                    $html->viewLink(
                                        'Voir le contrat « '.$title.' »',
                                        array( 'controller' => 'contratsinsertion', 'action' => 'view', $contrat['Contratinsertion']['id'] )
                                    ),
                                    array( $innerTable, array( 'class' => 'innerTableCell' ) )
                                ),
                            array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
                            array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
                            );
                        ?>
                    <?php endforeach;?>
                </tbody>
            </table>

            <?php echo $form->submit( 'Validation de la liste' );?>
        <?php echo $form->end();?>

    <?php /*require( 'index.pagination.ctp' )*/ ?>

    <?php else:?>
        <p>Vos critères n'ont retourné aucun dossier.</p>
    <?php endif?>
<?php endif?>