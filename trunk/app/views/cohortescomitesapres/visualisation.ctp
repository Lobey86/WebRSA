<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Notifications des décisions des comités';?>

<h1>Notification des Comités</h1>

<?php
    if( isset( $comitesapres ) ) {
        $paginator->options( array( 'url' => $this->passedArgs ) );
        $params = array( 'format' => 'Résultats %start% - %end% sur un total de %count%.' );
        $pagination = $html->tag( 'p', $paginator->counter( $params ) );

        $pages = $paginator->first( '<<' );
        $pages .= $paginator->prev( '<' );
        $pages .= $paginator->numbers();
        $pages .= $paginator->next( '>' );
        $pages .= $paginator->last( '>>' );

        $pagination .= $html->tag( 'p', $pages );
    }
    else {
        $pagination = '';
    }
?>

<?php require_once( 'filtre.ctp' );?>

<!-- Résultats -->

<?php if( isset( $comitesapres ) ):?>

    <h2 class="noprint">Résultats de la recherche</h2>

    <?php if( is_array( $comitesapres ) && count( $comitesapres ) > 0 ):?>
        <?php echo $form->create( 'NotifComite', array( 'url'=> Router::url( null, true ) ) );?>
    <?php echo $pagination;?> 
        <table id="searchResults" class="tooltips_oupas">
            <thead>
                <tr>
                    <th><?php echo $paginator->sort( 'N° demande RSA', 'Dossier.numdemrsa' );?></th>
                    <th><?php echo $paginator->sort( 'Nom de l\'allocataire', 'Personne.nom' );?></th>
                    <th><?php echo $paginator->sort( 'Commune de l\'allocataire', 'Adresse.locaadr' );?></th>
                    <th><?php echo $paginator->sort( 'Date de demande APRE', 'Apre.datedemandeapre' );?></th>
                    <th>Décision comité examen</th>
                    <th><?php echo $paginator->sort( 'Date de décision comité', 'Comiteapre.datecomite' );?></th>
                    <th>Montant attribué</th>
                    <th>Observations</th>

                    <th class="action">Notification Bénéficiaire</th>
                    <th class="action">Notification Référent</th>
                    <th class="action">Notification Tiers prestataire</th>
                    <th class="innerTableHeader noprint">Informations complémentaires</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $comitesapres as $index => $comite ):?>
                <?php
                    $innerTable = '<table id="innerTable'.$index.'" class="innerTable">
                            <tbody>
                                <tr>
                                    <th>Date naissance</th>
                                    <td>'.h( date_short( $comite['Personne']['dtnai'] ) ).'</td>
                                </tr>
                                <tr>
                                    <th>NIR</th>
                                    <td>'.h( $comite['Personne']['nir'] ).'</td>
                                </tr>
                                <tr>
                                    <th>Code postal</th>
                                    <td>'.h( $comite['Adresse']['codepos'] ).'</td>
                                </tr>
                            </tbody>
                        </table>';
                        $title = $comite['Dossier']['numdemrsa'];

                        //Pour masquer les champs imprimer en cas de Non accord
                        $typedecision = ( Set::enum( Set::classicExtract( $comite, 'ApreComiteapre.decisioncomite' ), $options['decisioncomite'] ) );
                        // debug($typedecision);
                        $isTiers = false;
                        if( $typedecision == 'Accord' ) {
                            $isTiers = true;

                        }
// debug($comite);
                    echo $html->tableCells(
                        array(
                            h( Set::classicExtract( $comite, 'Dossier.numdemrsa' ) ),
                            h( Set::classicExtract( $comite, 'Personne.qual' ).' '.Set::classicExtract( $comite, 'Personne.nom' ).' '.Set::classicExtract( $comite, 'Personne.prenom' ) ),
                            h( Set::classicExtract( $comite, 'Adresse.locaadr' ) ),
                            h( $locale->date( 'Date::short', Set::classicExtract( $comite, 'Apre.datedemandeapre' ) ) ),
                            h( Set::enum( Set::classicExtract( $comite, 'ApreComiteapre.decisioncomite' ), $options['decisioncomite'] ) ),
                            h( $locale->date( 'Date::short', Set::classicExtract( $comite, 'Comiteapre.datecomite' ) ) ),
                            h( Set::classicExtract( $comite, 'ApreComiteapre.montantattribue' ) ),
                            h( Set::classicExtract( $comite, 'ApreComiteapre.observationcomite' ) ),
                            $html->printLink(
                                'Imprimer pour le bénéficiaire',
                                array( 'controller' => 'cohortescomitesapres', 'action' => 'notificationscomitegedooo', Set::classicExtract( $comite, 'ApreComiteapre.apre_id' ), 'dest' => 'beneficiaire' ),
                                $permissions->check( 'cohortescomitesapres', 'notificationscomitegedooo' )
                            ),
                            $html->printLink(
                                'Imprimer pour le référent',
                                array( 'controller' => 'cohortescomitesapres', 'action' => 'notificationscomitegedooo', Set::classicExtract( $comite, 'ApreComiteapre.apre_id' ), 'dest' => 'referent' ),
                                $permissions->check( 'cohortescomitesapres', 'notificationscomitegedooo' )
                            ),
                            $html->printLink(
                                'Imprimer pour le tiers prestataire',
                                array( 'controller' => 'cohortescomitesapres', 'action' => 'notificationscomitegedooo', Set::classicExtract( $comite, 'ApreComiteapre.apre_id' ), 'dest' => 'tiers' ),
                                $isTiers,
                                $permissions->check( 'cohortescomitesapres', 'notificationscomitegedooo' )
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
        <?php echo $pagination;?>
       <ul class="actionMenu">
            <li><?php
                echo $html->printLinkJs(
                    'Imprimer le tableau',
                    array( 'onclick' => 'printit(); return false;' )
                );
            ?></li>

            <li><?php
                echo $html->exportLink(
                    'Télécharger le tableau',
                    array( 'controller' => 'cohortescomitesapres', 'action' => 'exportcsv', implode_assoc( '/', ':', array_unisize( $this->data ) ) )
                );
            ?></li>
        </ul>
        <?php echo $form->end();?>


    <?php else:?>
        <p>Aucune notification présente dans la cohorte.</p>
    <?php endif?>
<?php endif?>