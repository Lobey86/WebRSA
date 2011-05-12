<?php $this->pageTitle = 'Paramétrage des Permanences';?>
<?php echo $xform->create( 'Permanence' );?>
<h1><?php echo 'Visualisation de la table  ';?></h1>

    <ul class="actionMenu">
        <?php
            echo '<li>'.$xhtml->addLink(
                'Ajouter',
                array( 'controller' => 'permanences', 'action' => 'add' )
            ).' </li>';
        ?>
    </ul>

    <?php if( empty( $permanences ) ):?>
        <p class="notice">Aucune permanence présente pour le moment.</p>

    <?php else:?>
    <div>
        <h2>Table Permanences</h2>
        <table>
            <thead>
                <tr>
                    <th>Nom de la permanence</th>
                    <th>Nom de la structure liée </th>
                    <th>N° Téléphone</th>
                    <th>N° de voie</th>
                    <th>Type de voie</th>
                    <th>Nom de voie</th>
                    <th>Code postal</th>
                    <th>Ville</th>
                    <!-- <th>Canton</th> -->
                    <th colspan="2" class="action">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $permanences as $permanence ):?>
                    <?php echo $xhtml->tableCells(
                        array(
                            h( Set::classicExtract( $permanence, 'Permanence.libpermanence' ) ),
                            h( Set::classicExtract( $sr, Set::classicExtract( $permanence, 'Permanence.structurereferente_id' ) ) ),
                            h( Set::classicExtract( $permanence, 'Permanence.numtel' ) ),
                            h( Set::classicExtract( $permanence, 'Permanence.numvoie' ) ),
                            h( Set::classicExtract( $typevoie, Set::classicExtract( $permanence, 'Permanence.typevoie' ) ) ),
                            h( Set::classicExtract( $permanence, 'Permanence.nomvoie' ) ),
                            h( Set::classicExtract( $permanence, 'Permanence.codepos' ) ),
                            h( Set::classicExtract( $permanence, 'Permanence.ville' ) ),
//                             h( Set::classicExtract( $permanence, 'Permanence.canton' ) ),
                            $xhtml->editLink(
                                'Éditer la structure référente ',
                                array( 'controller' => 'permanences', 'action' => 'edit', Set::classicExtract( $permanence, 'Permanence.id' ) )
                            ),
                            $xhtml->deleteLink(
                                'Supprimer la structure référente ',
                                array( 'controller' => 'permanences', 'action' => 'delete', Set::classicExtract( $permanence, 'Permanence.id' ) )
                            )
                        ),
                        array( 'class' => 'odd' ),
                        array( 'class' => 'even' )
                    );
                    ?>
                <?php endforeach;?>
            </tbody>
        </table>
	</div>
	<?php endif;?>

    <div class="submit">
        <?php echo $xform->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) ); ?>
    </div>
	<?php echo $xform->end();?>

<div class="clearer"><hr /></div>