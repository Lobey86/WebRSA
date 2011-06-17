<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  $this->pageTitle = 'Détails demande PDO';?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

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

    <h1>Détails demande PDO</h1>
    <ul class="actionMenu">
        <?php
            if( $permissions->check( 'propospdos', 'edit' ) ) {
                echo '<li>'.$xhtml->editLink(
                    'Éditer PDO',
                    array( 'controller' => 'propospdos', 'action' => 'edit', Set::classicExtract( $pdo, 'Propopdo.id' ) )
                ).' </li>';
            }
        ?>
    </ul>


    <?php
        echo $form->create( 'Propopdo', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );

        $complet = Set::enum( $pdo['Propopdo']['iscomplet'], $options['iscomplet'] );
        $origpdo = Set::enum( $pdo['Propopdo']['originepdo_id'], $originepdo );
        $motifpdo = Set::enum( $pdo['Propopdo']['motifpdo'], $motifpdo );
        $structure = Set::enum( $pdo['Propopdo']['structurereferente_id'], $structs );
        $decision = Set::enum( $pdo['Decisionpropopdo'][0]['decisionpdo_id'], $decisionpdo );
// debug($decisionpdo);
        echo $default2->view(
            $pdo,
            array( 
                'Structurereferente.lib_struc' => array( 'type' => 'text', 'value' => $structure ),
                'Typepdo.libelle',
                'Propopdo.datereceptionpdo',
                'Propopdo.originepdo_id' => array( 'type' => 'text', 'value' => $origpdo ),
                'Decisionpropopdo.decisionpdo_id' => array( 'type' => 'text', 'value' => $decision ),
                'Propopdo.motifpdo' => array( 'type' => 'text', 'value' => $motifpdo ),
                'Decisionpropopdo.0.datedecisionpdo',
                'Decisionpropopdo.0.commentairepdo',
                'Propopdo.iscomplet' => array( 'type' => 'text', 'value' => $complet ),
            ),
            array(
                'class' => 'aere'
            )
        );
    ?>


    <?php
        echo "<h2>Pièces jointes</h2>";
        if( !empty( $pdo['Fichiermodule'] ) ){
            $fichiersLies = Set::extract( $pdo, 'Propopdo/Fichiermodule' );
            echo '<table class="aere"><tbody>';
                echo '<tr><th>Nom de la pièce jointe</th><th>Date de l\'ajout</th><th>Action</th></tr>';
                if( isset( $fichiersLies ) ){
                    foreach( $fichiersLies as $i => $fichiers ){
                        echo '<tr><td>'.$fichiers['Fichiermodule']['name'].'</td>';
                        echo '<td>'.date_short( $fichiers['Fichiermodule']['created'] ).'</td>';
                        echo '<td>'.$xhtml->link( 'Télécharger', array( 'action' => 'download', $fichiers['Fichiermodule']['id']    ) ).'</td></tr>';
                    }
                }
            echo '</tbody></table>';
        }
        else{
            echo '<p class="notice aere">Aucun élément.</p>';
        }
    ?>

</div>
    <div class="submit"> <?php echo $form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) ); ?> </div>

    <?php echo $form->end();?>
<div class="clearer"><hr /></div>