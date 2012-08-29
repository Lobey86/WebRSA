<?php
    $this->pageTitle = 'CER';
    $domain = 'contratinsertion';

    echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
    echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>

<div class="with_treemenu">
    <h1><?php  echo $this->pageTitle;?></h1>
        <?php if( empty( $orientstruct ) && empty( $contratsinsertion ) ) :?>
            <p class="error">Cette personne ne possède pas d'orientation. Impossible de créer un CER.</p>
        <?php else:?>
            <?php if( empty( $persreferent ) ) :?>
                <p class="error">Aucun référent n'est lié au parcours de cette personne.</p>
            <?php endif;?>

			<?php  if( !empty( $orientstructEmploi ) ) :?>
				<p class="error">Cette personne possède actuellement une orientation professionnelle. Impossible de créer un CER.</p>
			<?php endif; ?>

			<?php  if( !empty( $cuiEncours ) ) :?>
				<p class="error">Cette personne possède actuellement un CUI en cours. Impossible de créer un CER.</p>
			<?php endif; ?>


			<?php if( empty( $contratsinsertion ) && empty( $orientstructEmploi ) ):?>
				<p class="notice">Cette personne ne possède pas encore de CER.</p>
			<?php endif;?>
			
			<?php if( empty( $orientstruct ) ):?>
				<p class="error">Cette personne ne possède pas d'orientation. Impossible de créer un CER.</p>
			<?php endif;?>

			<ul class="actionMenu">
				<?php
					$block = empty( $orientstruct ) || !empty( $orientstructEmploi ) || !empty( $cuiEncours );

					echo '<li>'.$xhtml->addLink(
						'Ajouter un CER',
						array( 'controller' => 'contratsinsertion', 'action' => 'add', $personne_id ),
						( !$block )
					).' </li>';
				?>
			</ul>

	<?php if( !empty( $contratsinsertion ) ):?>

	<table class="tooltips default2" id="searchResults">
		<thead>
			<tr>
				<th>Forme du contrat</th>
				<th>Type de contrat</th>
				<th>Date de début de contrat</th>
				<th>Date de fin de contrat</th>
				<th>Contrat signé le</th>
				<th>Décision</th>
				<th>Date décision</th>
				<th>Position du CER</th>
				<th colspan="12" class="action">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php

				foreach( $contratsinsertion as $index => $contratinsertion ) {

				
					$innerTable = '<table id="innerTablesearchResults'.$index.'" class="innerTable">
							<tbody>
								<tr>
									<th>Raison annulation</th>
									<td>'.$contratinsertion['Contratinsertion']['motifannulation'].'</td>
								</tr>
							</tbody>
						</table>';
						
					$action = ( ( $contratinsertion['Contratinsertion']['forme_ci'] == 'S' ) ? 'propositionsimple' : 'propositionparticulier' );
					$dateCreation = Set::classicExtract( $contratinsertion, 'Contratinsertion.created' );
					$periodeblock = false;
					if( !empty( $dateCreation ) ){
						if(  ( time() >= ( strtotime( $dateCreation ) + 3600 * Configure::read( 'Periode.modifiablecer.nbheure' ) ) ) ){
							$periodeblock = true;
						}
					}

					$decision = Set::classicExtract( $decision_ci, Set::classicExtract( $contratinsertion, 'Contratinsertion.decision_ci' ) );
					$position = Set::classicExtract( $contratinsertion, 'Contratinsertion.positioncer' );

					//Personne de + de 55ans
					$isReconductible = false;
					$personnePlus55Ans = Set::classicExtract( $contratinsertion, 'Personne.plus55ans' );
					if( !empty( $personnePlus55Ans ) ) {
						$isReconductible = true;
					}


					$datenotif = Set::classicExtract( $contratinsertion, 'Contratinsertion.datenotification' );
					if( empty( $datenotif ) /*&& ( $position == 'attvalid' )*/ ) {
						$positioncer = Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.positioncer' ), $options['positioncer'] );
					}
					else if( !empty( $datenotif ) && in_array( $position, array( 'nonvalidnotifie', 'validnotifie' ) ) ){
						$positioncer = Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.positioncer' ), $options['positioncer'] ).' le '.date_short( $datenotif );
					}
// 					else if( empty( $datenotif ) && ( $position == 'annule' ) ){
// 						$positioncer = Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.positioncer' ), $options['positioncer'] ).' car '.Set::classicExtract( $contratinsertion, 'Contratinsertion.motifannulation' );
// 					}
					else {
						$positioncer = Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.positioncer' ), $options['positioncer'] );
					}

					$isvalidcer = Set::classicExtract( $contratinsertion, 'Propodecisioncer66.isvalidcer' );
// debug($isvalidcer);
					echo $xhtml->tableCells(
						array(
							h( Set::classicExtract( $forme_ci, Set::classicExtract( $contratinsertion, 'Contratinsertion.forme_ci' ) ) ),
							h( Set::classicExtract( $options['num_contrat'], Set::classicExtract( $contratinsertion, 'Contratinsertion.num_contrat' ) ) ),
							h( date_short( Set::classicExtract( $contratinsertion, 'Contratinsertion.dd_ci' ) ) ),
							h( date_short( Set::classicExtract( $contratinsertion, 'Contratinsertion.df_ci' ) ) ),
							h( date_short( Set::classicExtract( $contratinsertion, 'Contratinsertion.date_saisi_ci' ) ) ),
							h( $decision ),
							h( date_short( Set::classicExtract( $contratinsertion, 'Contratinsertion.datedecision' ) ) ),
							h( $positioncer ),


							$default2->button(
								'view',
								array( 'controller' => 'contratsinsertion', 'action' => 'view',
								$contratinsertion['Contratinsertion']['id'] ),
								array(
									'enabled' => (
										$permissions->check( 'contratsinsertion', 'view' ) == 1
									)
								)
							),
							$default2->button(
								'edit',
								array( 'controller' => 'contratsinsertion', 'action' => 'edit',
								$contratinsertion['Contratinsertion']['id'] ),
								array(
									'enabled' => (
										( $permissions->check( 'contratsinsertion', 'edit' ) == 1 )
										&& ( Set::classicExtract( $contratinsertion, 'Contratinsertion.positioncer' ) != 'annule' )
										&& ( Set::classicExtract( $contratinsertion, 'Contratinsertion.positioncer' ) != ( 'validnotifie' ) )
										&& ( Set::classicExtract( $contratinsertion, 'Contratinsertion.positioncer' ) != ( 'nonvalidnotifie' ) )
										&& ( !$periodeblock )
									)
								)
							),

							$default2->button(
								'valider',
								array( 'controller' => 'proposdecisionscers66', 'action' => $action,
								$contratinsertion['Contratinsertion']['id'] ),
								array(
									'enabled' => (
											( $permissions->check( 'proposdecisionscers66', $action ) == 1 )
											&& ( Set::classicExtract( $contratinsertion, 'Contratinsertion.positioncer' ) != 'fincontrat' )
											&& ( Set::classicExtract( $contratinsertion, 'Contratinsertion.positioncer' ) != 'annule' )
											&& ( Set::classicExtract( $contratinsertion, 'Contratinsertion.positioncer' ) != 'validnotifie' )
											&& ( Set::classicExtract( $contratinsertion, 'Contratinsertion.positioncer' ) != 'nonvalidnotifie' )
											&& ( Set::classicExtract( $contratinsertion, 'Contratinsertion.decision_ci' ) == 'E' )
									)
								)
							),
							$default2->button(
								'ficheliaisoncer',
								array( 'controller' => 'contratsinsertion', 'action' => 'ficheliaisoncer',
								$contratinsertion['Contratinsertion']['id'] ),
								array(
									'enabled' => (
										( $permissions->check( 'contratsinsertion', 'ficheliaisoncer' ) == 1 )
										&& ( Set::classicExtract( $contratinsertion, 'Contratinsertion.positioncer' ) != 'annule' )
										&& ( !empty( $isvalidcer )  )
									)
								)
							),
							$default2->button(
								'notifbenef',
								array( 'controller' => 'contratsinsertion', 'action' => 'notifbenef',
								$contratinsertion['Contratinsertion']['id'] ),
								array(
									'enabled' => (
										( $permissions->check( 'contratsinsertion', 'notifbenef' ) == 1 )
										&& ( Set::classicExtract( $contratinsertion, 'Contratinsertion.positioncer' ) != 'annule' )
										&& ( !empty( $isvalidcer )  )
									)
								)
							),
							$default2->button(
								'notifop',
								array( 'controller' => 'contratsinsertion', 'action' => 'notificationsop',
								$contratinsertion['Contratinsertion']['id'] ),
								array(
									'enabled' => (
										( $permissions->check( 'contratsinsertion', 'notificationsop' ) == 1 )
										&& ( Set::classicExtract( $contratinsertion, 'Contratinsertion.positioncer' ) != 'annule' )
										&& ( !empty( $isvalidcer ) && ( $isvalidcer != 'N' ) )
									)
								)
							),
							$default2->button(
								'print',
								array( 'controller' => 'contratsinsertion', 'action' => 'impression',
								$contratinsertion['Contratinsertion']['id'] ),
								array(
									'enabled' => (
										( $permissions->check( 'contratsinsertion', 'impression' ) == 1 )
										&& ( Set::classicExtract( $contratinsertion, 'Contratinsertion.positioncer' ) != 'annule' )
									)
								)
							),

							$default2->button(
								'notification',
								array( 'controller' => 'contratsinsertion', 'action' => 'notification',
								$contratinsertion['Contratinsertion']['id'] ),
								array(
									'enabled' => (
										( $permissions->check( 'contratsinsertion', 'notification' ) == 1 )
										&& ( Set::classicExtract( $contratinsertion, 'Contratinsertion.positioncer' ) != 'annule' )
									)
								)
							),
							$default2->button(
								'reconduction',
								array( 'controller' => 'contratsinsertion', 'action' => 'reconductionCERPlus55Ans',
								$contratinsertion['Contratinsertion']['id'] ),
								array(
									'enabled' => (
										( $permissions->check( 'contratsinsertion', 'reconductionCERPlus55Ans' ) == 1 )
										&& ( Set::classicExtract( $contratinsertion, 'Contratinsertion.positioncer' ) != 'annule' )
										&& $isReconductible
									)
								)
							),
							$default2->button(
								'cancel',
								array( 'controller' => 'contratsinsertion', 'action' => 'cancel',
								$contratinsertion['Contratinsertion']['id'] ),
								array(
									'enabled' => (
										( $permissions->check( 'contratsinsertion', 'cancel' ) == 1 )
										&& ( Set::classicExtract( $contratinsertion, 'Contratinsertion.positioncer' ) != 'annule' )
									)
								)
							),
							$default2->button(
								'filelink',
								array( 'controller' => 'contratsinsertion', 'action' => 'filelink',
								$contratinsertion['Contratinsertion']['id'] ),
								array(
									'enabled' => (
										$permissions->check( 'contratsinsertion', 'filelink' ) == 1
									)
								)
							),
							h( '('.Set::classicExtract( $contratinsertion, 'Fichiermodule.nb_fichiers_lies' ).')' ),
							array( $innerTable, array( 'class' => 'innerTableCell noprint' ) )
						),
						array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
						array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
					);
				}
			?>
		</tbody>
	</table>
	<?php  endif;?>
<?php endif;?>
</div>
<div class="clearer"><hr /></div>
