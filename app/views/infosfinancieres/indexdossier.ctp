<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  $this->pageTitle = 'Paiement des allocations';?>

<h1><?php echo $this->pageTitle;?></h1>

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

//     if( isset( $infosfinancieres ) ) {
//         $paginator->options( array( 'url' => $this->passedArgs ) );
//         $params = array( 'format' => 'Résultats %start% - %end% sur un total de %count%.' );
//         $pagination = $html->tag( 'p', $paginator->counter( $params ) );
// 
//         $pages = $paginator->first( '<<' );
//         $pages .= $paginator->prev( '<' );
//         $pages .= $paginator->numbers();
//         $pages .= $paginator->next( '>' );
//         $pages .= $paginator->last( '>>' );
// 
//         $pagination .= $html->tag( 'p', $pages );
//     }
//     else {
//         $pagination = '';
//     }
?>


<?php echo $form->create( 'Infosfinancieres', array( 'type' => 'post', 'action' => '/indexdossier/', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );?>
    <fieldset>
        <?php echo $form->input( 'Filtre.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>
        <?php echo $form->input( 'Filtre.moismoucompta', array( 'label' => 'Recherche des paiements pour le mois de ', 'type' => 'date', 'dateFormat' => 'MY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) ) );?>
    </fieldset>

    <div class="submit noprint">
        <?php echo $form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
        <?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>
<?php echo $form->end();?>

<!-- Résultats -->
<?php if( isset( $infosfinancieres ) ):?>
   <?php $mois = strftime('%B %Y', strtotime( $this->data['Filtre']['moismoucompta']['year'].'-'.$this->data['Filtre']['moismoucompta']['month'].'-01' ) ); ?>

    <h2 class="noprint">Liste des allocations pour le mois de <?php echo isset( $mois ) ? $mois : null ; ?></h2>

    <?php if( is_array( $infosfinancieres ) && count( $infosfinancieres ) > 0  ):?>
    <?php /*echo $pagination;*/?>
    <?php require( 'index.pagination.ctp' );?>
        <table id="searchResults" class="tooltips_oupas">
            <thead>
                <tr>
                    <!--<th><?php echo $paginator->sort( 'N° Dossier', 'Dossier.numdemrsa' );?></th>
                    <th><?php echo $paginator->sort( 'N° CAF', 'Dossier.matricule' );?></th>
                    <th><?php echo $paginator->sort( 'Nom/prénom du bénéficiaire', 'Personne.nom' );?></th>
                    <th><?php echo $paginator->sort( 'Date de naissance du bénéficiaire', 'Personne.dtnai' );?></th>
                    <th><?php echo $paginator->sort( 'Type d\'allocation', 'Infofinanciere.type_allocation' );?></th>
                    <th><?php echo $paginator->sort( 'Montant de l\'allocation', 'Infofinanciere.mtmoucompta' );?></th> -->
                    <th>N° Dossier</th>
                    <th>N° CAF</th>
                    <th>Nom/Prénom allocataire</th>
                    <th>Date de naissance du bénéficiaire</th>
                    <th>Type d'allocation</th>
                    <th>Montant de l'allocation</th>
                    <th colspan="2" class="action">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $infosfinancieres as $index => $infofinanciere ):?>
                    <?php
                        $even = true;
//                         debug( $index );
                        $rowspan = 1;
                        for( $i = $index + 1 ; $i < count( $infofinanciere ) ; $i++ ) {
                            if( Set::extract( $infofinanciere, 'Dossier.numdemrsa' ) == Set::extract( $infosfinancieres, $index.'.Dossier.numdemrsa' ) )
                                $rowspan++;
                        }
                        if( Set::extract( $infosfinancieres, ( $index-1 ).'.Dossier.numdemrsa' ) != Set::extract( $infosfinancieres, $index.'.Dossier.numdemrsa' ) ) {
                            if( $rowspan == 1 ) {
                                $even = !$even;
                                echo $html->tableCells(
                                    array(
                                        h( $infofinanciere['Dossier']['numdemrsa'] ),
                                        h( $infofinanciere['Dossier']['matricule'] ),
        //                                 h( $infofinanciere['Personne']['qual'].' '.$infofinanciere['Personne']['nom'].' '.$infofinanciere['Personne']['prenom'] ),
                                        h( $infofinanciere['Personne']['qual'].' '.$infofinanciere['Personne']['nom'].' '.$infofinanciere['Personne']['prenom'] ),
                                        $locale->date( 'Date::short', $infofinanciere['Personne']['dtnai'] ),
                                        h( $type_allocation[$infofinanciere['Infofinanciere']['type_allocation']]),
                                        $locale->money( $infofinanciere['Infofinanciere']['mtmoucompta'] ),
                                        $html->viewLink(
                                            'Voir les informations financières',
                                            array( 'controller' => 'infosfinancieres', 'action' => 'index', $infofinanciere['Infofinanciere']['dossier_rsa_id'] ),
                                            $permissions->check( 'infosfinancieres', 'view' )
                                        ),

                                    ),
                                    array( 'class' => 'odd' ),
                                    array( 'class' => 'even' )
                                );
                            }
                            else {
//                                 $even = !$even;
                                echo '<tr class="'.( $even ? 'even' : 'odd' ).'">
                                        <td rowspan="'.$rowspan.'">'.h( $infofinanciere['Dossier']['numdemrsa'] ).'</td>
                                        <td rowspan="'.$rowspan.'">'.h( $infofinanciere['Dossier']['matricule'] ).'</td>
                                        <td rowspan="'.$rowspan.'">'.h( $infofinanciere['Personne']['qual'].' '.$infofinanciere['Personne']['nom'].' '.$infofinanciere['Personne']['prenom'] ).'</td>
                                        <td rowspan="'.$rowspan.'">'.$locale->date( 'Date::short', $infofinanciere['Personne']['dtnai'] ).'</td>

                                        <td>'.h( $type_allocation[$infofinanciere['Infofinanciere']['type_allocation']]).'</td>
                                        <td>'.$locale->money( $infofinanciere['Infofinanciere']['mtmoucompta'] ).'</td>
                                        <td rowspan="'.$rowspan.'">'.$html->viewLink(
                                            'Voir les informations financières',
                                            array( 'controller' => 'infosfinancieres', 'action' => 'index', $infofinanciere['Infofinanciere']['dossier_rsa_id'] ),
                                            $permissions->check( 'infosfinancieres', 'view' )
                                        ).'</td>
                                    </tr>';
                            }
                        }
                        else {
                            echo '<tr class="'.( $even ? 'even' : 'odd' ).'">
                                    <td>'.h( $type_allocation[$infofinanciere['Infofinanciere']['type_allocation']]).'</td>
                                    <td>'.$locale->money( $infofinanciere['Infofinanciere']['mtmoucompta'] ).'</td>

                                </tr>';
                        }
                        
                        
//                         echo $html->tableCells(
//                             array(
//                                 h( $infofinanciere['Dossier']['numdemrsa'] ),
//                                 h( $infofinanciere['Dossier']['matricule'] ),
// //                                 h( $infofinanciere['Personne']['qual'].' '.$infofinanciere['Personne']['nom'].' '.$infofinanciere['Personne']['prenom'] ),
//                                 h( $infofinanciere['Personne']['qual'].' '.$infofinanciere['Personne']['nom'].' '.$infofinanciere['Personne']['prenom'] ),
//                                 $locale->date( 'Date::short', $infofinanciere['Personne']['dtnai'] ),
//                                 h( $type_allocation[$infofinanciere['Infofinanciere']['type_allocation']]),
//                                 $locale->money( $infofinanciere['Infofinanciere']['mtmoucompta'] ),
//                                 $html->viewLink(
//                                     'Voir les informations financières',
//                                     array( 'controller' => 'infosfinancieres', 'action' => 'index', $infofinanciere['Infofinanciere']['dossier_rsa_id'] ),
//                                     $permissions->check( 'infosfinancieres', 'view' )
//                                 ),
// 
//                             ),
//                             array( 'class' => 'odd' ),
//                             array( 'class' => 'even' )
//                         );
                    ?>
                <?php endforeach; ?>
            </tbody>
        </table>
       <!-- <ul class="actionMenu">
            <?php
                echo $html->printLink(
                    'Imprimer le tableau',
                    array( 'controller' => 'gedooos', 'action' => 'notifications_cohortes' )
                );
            ?>

            <?php
                echo $html->exportLink(
                    'Télécharger le tableau',
                    array( 'controller' => 'cohortes', 'action' => 'exportcsv' )
                );
            ?>
        </ul> -->
    <?php require( 'index.pagination.ctp' ); ?>
    <?php else:?>
        <p>Vos critères n'ont retourné aucun dossier.</p>
    <?php endif?>

<?php endif?>