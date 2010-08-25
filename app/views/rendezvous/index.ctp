<?php  $this->pageTitle = 'Rendez-vous de la personne';?>
<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id) );?>

<?php
    function value( $array, $index ) {
        $keys = array_keys( $array );
        $index = ( ( $index == null ) ? '' : $index );
        if( @in_array( $index, $keys ) && isset( $array[$index] ) ) {
            return $array[$index];
        }
        else {
            return null;
        }
    }
?>

<div class="with_treemenu">
    <h1>Rendez-vous</h1>


    <?php
        echo $default->index(
            $rdvs,
            array(
                'Personne.nom_complet',
                'Structurereferente.lib_struc',
                'Referent.nom_complet',
                'Permanence.libpermanence',
                'Typerdv.libelle',
                'Statutrdv.libelle',
                'Rendezvous.daterdv',
                'Rendezvous.heurerdv',
                'Rendezvous.objetrdv',
                'Rendezvous.commentairerdv'
            ),
            array(
                'actions' => array(
                    'Rendezvous.view',
                    'Rendezvous.edit',
                    'Rendezvous.delete'
                ),
                'add' => array( 'Rendezvous.add' => $personne_id )
            )
        );
    ?>


    <!-- <?php /*if( empty( $orientstruct ) ):?>
        <p class="error">Impossible d'ajouter une demande de RDV lorsqu'il n'existe pas d'orientation.</p>

    <?php elseif( !empty( $orientstruct ) && empty( $permanence )  ):?>
        <p class="error">Impossible d'ajouter une demande de RDV lorsqu'il n'existe pas de permanence liée à la structure <?php echo '(' . $struct . ')';?></p>

    <?php elseif( !empty( $orientstruct ) && !empty( $permanence ) && empty( $refrdv ) ):?>
        <p class="error">Impossible d'ajouter une demande de RDV lorsqu'il n'existe pas de référent pour le RDV.</p>

    <?php elseif( !empty( $orientstruct ) && !empty( $permanence ) && !empty( $refrdv ) && empty( $statutrdv ) ):?>
        <p class="error">Impossible d'ajouter une demande de RDV lorsqu'il n'existe pas de statut pour le RDV.</p>

    <?php else:*/?>

        <?php /*if( empty( $rdvs ) ):?>
            <p class="notice">Cette personne ne possède pas encore de rendez-vous.</p>
        <?php endif;?>

    <?php if( $permissions->check( 'rendezvous', 'add' ) ):?>
        <ul class="actionMenu">
            <?php
                echo '<li>'.$html->addLink(
                    'Ajouter un RDV',
                    array( 'controller' => 'rendezvous', 'action' => 'add', $personne_id )
                ).' </li>';
            ?>
        </ul>
    <?php endif;?>

    <?php if( !empty( $rdvs ) ):?>
    <table class="tooltips">
        <thead>
            <tr>
                <th>Nom/Prénom Allocataire</th>
                <th>Structure référente</th>
                <th>Nom de l'agent / du référent</th>
                <th>Permanence liée</th>
                <th>Type de RDV</th>
                <th>Statut du RDV</th>
                <th>Date du RDV</th>
                <th>Heure du RDV</th>
                <th>Objet du RDV</th>
                <th>Commentaire suite au RDV</th>
                <th colspan="3" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach( $rdvs as $rdv ) {
// debug($rdv);
                    echo $html->tableCells(
                        array(
                            h( $rdv['Personne']['nom'].' '.$rdv['Personne']['prenom'] ),
                            h( Set::extract( $rdv, 'Structurereferente.lib_struc' ) ),
                            h( Set::extract( $rdv, 'Referent.qual' ).' '.Set::extract( $rdv, 'Referent.nom' ).' '.Set::extract( $rdv, 'Referent.prenom' ) ),
                            h( Set::extract( $rdv, 'Permanence.libpermanence' ) ),
                            h( Set::extract( $rdv, 'Typerdv.libelle' ) ),
                            h( Set::enum( Set::classicExtract( $rdv, 'Rendezvous.statutrdv_id' ), $statutrdv ) ),
                            h( date_short( Set::extract( $rdv, 'Rendezvous.daterdv' ) ) ),
                            h( $locale->date( 'Time::short', Set::classicExtract( $rdv, 'Rendezvous.heurerdv' ) ) ),
                            h( Set::extract( $rdv, 'Rendezvous.objetrdv' ) ),
                            h( Set::extract( $rdv, 'Rendezvous.commentairerdv' ) ) ,
                            $html->viewLink(
                                'Voir le rendez-vous',
                                array( 'controller' => 'rendezvous', 'action' => 'view', $rdv['Rendezvous']['id'] ),
                                $permissions->check( 'rendezvous', 'view' )
                            ),
                            $html->editLink(
                                'Editer le rendez-vous',
                                array( 'controller' => 'rendezvous', 'action' => 'edit', $rdv['Rendezvous']['id'] ),
                                $permissions->check( 'rendezvous', 'edit' )
                            ),
                            $html->printLink(
                                'Imprimer la notification',
                                array( 'controller' => 'gedooos', 'action' => 'rendezvous', $rdv['Rendezvous']['id'] ),
                                $permissions->check( 'gedooos', 'rendezvous' )
                            ),
                        ),
                        array( 'class' => 'odd' ),
                        array( 'class' => 'even' )
                    );
                }
            ?>
        </tbody>
    </table> 
    <?php  endif;*/?> -->


</div>
<div class="clearer"><hr /></div>