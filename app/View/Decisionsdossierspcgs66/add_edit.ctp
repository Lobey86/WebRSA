<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>
<?php
	echo $this->Xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'decisiondossierpcg66', "Decisionsdossierspcgs66::{$this->action}" )
	);

	echo $this->Xform->create( 'Decisiondossierpcg66', array( 'id' => 'decisiondossierpcg66form' ) );
	if( Set::check( $this->request->data, 'Decisiondossierpcg66.id' ) ){
		echo $this->Xform->input( 'Decisiondossierpcg66.id', array( 'type' => 'hidden' ) );
	}
	echo $this->Xform->input( 'Decisiondossierpcg66.dossierpcg66_id', array( 'type' => 'hidden', 'value' => $dossierpcg66_id ) );
	if( $this->action == 'add' ) {
		echo $this->Xform->input( 'Decisiondossierpcg66.user_id', array( 'type' => 'hidden', 'value' => $userConnected ) );
	}

?>

<?php if( !empty( $personnespcgs66 ) ):?>

	<table class="tooltips aere"><caption style="caption-side: top;">Informations concernant la (les) personne(s)</caption>
			<thead>
				<tr>
					<th>Personne concernée</th>
					<th>Motif(s)</th>
					<th>Statut(s)</th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach( $personnespcgs66 as $personnepcg66 ) {
						//Liste des différentes situations de la personne
						$listeSituations = Set::extract( $personnepcg66, '/Situationpdo/libelle' );
						$differentesSituations = '';
						foreach( $listeSituations as $key => $situation ) {
							if( !empty( $situation ) ) {
								$differentesSituations .= $this->Xhtml->tag( 'h3', '' ).'<ul><li>'.$situation.'</li></ul>';
							}
						}

						//Liste des différents statuts de la personne
						$listeStatuts = Set::extract( $personnepcg66, '/Statutpdo/libelle' );
						$differentsStatuts = '';
						foreach( $listeStatuts as $key => $statut ) {
							if( !empty( $statut ) ) {
								$differentsStatuts .= $this->Xhtml->tag( 'h3', '' ).'<ul><li>'.$statut.'</li></ul>';
							}
						}
						echo $this->Xhtml->tableCells(
							array(
								h( Set::classicExtract( $personnepcg66, 'Personne.qual' ).' '.Set::classicExtract( $personnepcg66, 'Personne.nom' ).' '.Set::classicExtract( $personnepcg66, 'Personne.prenom' ) ),
								$differentesSituations,
								$differentsStatuts
							),
							array( 'class' => 'odd' ),
							array( 'class' => 'even' )
						);
					}
				?>
			</tbody>
		</table>
<?php else:?>
	<p class="notice">Aucune personne n'est concernée par ce dossier.</p>
<?php  endif;?>

<?php
	echo "<h2>Pièces liées au dossier</h2>";
	echo $this->Fileuploader->results( Set::classicExtract( $dossierpcg66, 'Fichiermodule' ) );
?>

<?php if( !empty( $listeFicheAReporter ) ):?>
	<?php echo "<h2>Fiche(s) de calcul à prendre en compte</h2>";?>
	<table class="tooltips aere"><caption style="caption-side: top;">Informations concernant la (les) fiche(s) de calcul</caption>
		<thead>
			<tr>
				<th>Régime</th>
				<th>Bénéfice pris en compte</th>
				<th>Montant des revenus arrêtés à</th>
				<th>Date de début de période</th>
				<th>Date de fin de période</th>
				<th>Date de révision</th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach( $listeFicheAReporter as $i => $fichecalcul ){

				if( $fichecalcul['regime'] == 'microbic' ) {
					$montanttotal = $fichecalcul['benefpriscompte'];
				}
				else{
					$montanttotal = $fichecalcul['mnttotalpriscompte'];
				}

				echo $this->Xhtml->tableCells(
					array(
						h( Set::enum( $fichecalcul['regime'], $options['Traitementpcg66']['regime'] ) ),
						h( $this->Locale->money( $montanttotal ) ),
						h( $this->Locale->money( $fichecalcul['revenus'] ).' par mois' ),
						h( date_short( $fichecalcul['dtdebutperiode'] ) ),
						h( date_short( $fichecalcul['datefinperiode'] ) ),
						h( date_short( $fichecalcul['daterevision'] ) )
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
			}
		?>
		</tbody>
	</table>
<?php  endif;?>


	<fieldset><legend>Proposition du technicien</legend>
		<?php if( !empty( $dossierpcg66['Decisiondossierpcg66'] ) ):?>
			<table class="aere"><caption style="caption-side: top;">Propositions passées</caption>
				<thead>
					<tr>
						<th>Proposition de décision</th>
						<th>Date de la proposition</th>
						<th>Avis technique</th>
						<th>Commentaire de l'avis technique</th>
						<th>Validation proposition</th>
						<th>Commentaire du décideur</th>
						<th>Commentaire technicien</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach( $dossierpcg66['Decisiondossierpcg66'] as $decision ):?>
						<?php
							echo $this->Xhtml->tableCells(
								array(
									h( Set::classicExtract( $decision, 'Decisionpdo.libelle' ) ),
									h( date_short( Set::classicExtract( $decision, 'datepropositiontechnicien' ) ) ),
									h( Set::enum( Set::classicExtract( $decision, 'avistechnique' ), $options['Decisiondossierpcg66']['validationproposition'] ) ),
									h( Set::classicExtract( $decision, 'commentaireavistechnique' ) ),
									h( Set::enum( Set::classicExtract( $decision, 'validationproposition' ), $options['Decisiondossierpcg66']['validationproposition'] ) ),
									h( Set::classicExtract( $decision, 'commentairevalidation' ) ),
									h( Set::classicExtract( $decision, 'commentairetechnicien' ) )
								),
								array( 'class' => 'odd' ),
								array( 'class' => 'even' )
							);
					?>
				<?php endforeach;?>
			</tbody>
		</table>
	<?php else:?>
		<p class="notice">Aucune proposition passée n'a encore été émise par le technicien.</p>
	<?php  endif;?>

	<?php if( !empty( $dossierpcg66['Dossierpcg66']['contratinsertion_id'] ) ):?>
		<?php echo "<h2>Informations du CER Particulier lié</h2>";?>
		<table class="tooltips default2">
	<thead>
		<tr>
			<th>Forme du contrat</th>
			<th>Type de contrat</th>
			<th>Date de début de contrat</th>
			<th>Date de fin de contrat</th>
			<th>Contrat signé le</th>
			<th>Position du CER</th>
			<th colspan="11" class="action">Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php
				$positioncer = Set::enum( Set::classicExtract( $dossierpcg66, 'Contratinsertion.positioncer' ), $options['Contratinsertion']['positioncer'] );

				echo $this->Xhtml->tableCells(
					array(
						h( Set::classicExtract( $formeCi, Set::classicExtract( $dossierpcg66, 'Contratinsertion.forme_ci' ) ) ),
						h( Set::classicExtract( $options['Contratinsertion']['num_contrat'], Set::classicExtract( $dossierpcg66, 'Contratinsertion.num_contrat' ) ) ),
						h( date_short( Set::classicExtract( $dossierpcg66, 'Contratinsertion.dd_ci' ) ) ),
						h( date_short( Set::classicExtract( $dossierpcg66, 'Contratinsertion.df_ci' ) ) ),
						h( date_short( Set::classicExtract( $dossierpcg66, 'Contratinsertion.date_saisi_ci' ) ) ),
						h( $positioncer ),


						$this->Default2->button(
							'view',
							array( 'controller' => 'contratsinsertion', 'action' => 'view',
							$dossierpcg66['Contratinsertion']['id'] ),
							array(
								'enabled' => (
									$this->Permissions->checkDossier( 'contratsinsertion', 'view', $dossierMenu ) == 1
								),
								'class' => 'external'
							)
						),
						$this->Default2->button(
							'ficheliaisoncer',
							array( 'controller' => 'contratsinsertion', 'action' => 'ficheliaisoncer',
							$dossierpcg66['Contratinsertion']['id'] ),
							array(
								'enabled' => (
									( $this->Permissions->checkDossier( 'contratsinsertion', 'ficheliaisoncer', $dossierMenu ) == 1 )
									&& ( Set::classicExtract( $dossierpcg66, 'Contratinsertion.positioncer' ) != 'annule' )
									&& ( !empty( $isvalidcer )  )
								)
							)
						),
						$this->Default2->button(
							'notifbenef',
							array( 'controller' => 'contratsinsertion', 'action' => 'notifbenef',
							$dossierpcg66['Contratinsertion']['id'] ),
							array(
								'enabled' => (
									( $this->Permissions->checkDossier( 'contratsinsertion', 'notifbenef', $dossierMenu ) == 1 )
									&& ( Set::classicExtract( $dossierpcg66, 'Contratinsertion.positioncer' ) != 'annule' )
									&& ( !empty( $isvalidcer )  )
								)
							)
						),
						$this->Default2->button(
							'notifop',
							array( 'controller' => 'contratsinsertion', 'action' => 'notificationsop',
							$dossierpcg66['Contratinsertion']['id'] ),
							array(
								'enabled' => (
									( $this->Permissions->checkDossier( 'contratsinsertion', 'notificationsop', $dossierMenu ) == 1 )
									&& ( Set::classicExtract( $dossierpcg66, 'Contratinsertion.positioncer' ) != 'annule' )
									&& ( !empty( $isvalidcer ) && ( $isvalidcer != 'N' ) )
								)
							)
						),
						$this->Default2->button(
							'print',
							array( 'controller' => 'contratsinsertion', 'action' => 'impression',
							$dossierpcg66['Contratinsertion']['id'] ),
							array(
								'enabled' => (
									( $this->Permissions->checkDossier( 'contratsinsertion', 'impression', $dossierMenu ) == 1 )
									&& ( Set::classicExtract( $dossierpcg66, 'Contratinsertion.positioncer' ) != 'annule' )
								)
							)
						),

						$this->Default2->button(
							'notification',
							array( 'controller' => 'contratsinsertion', 'action' => 'notification',
							$dossierpcg66['Contratinsertion']['id'] ),
							array(
								'enabled' => (
									( $this->Permissions->checkDossier( 'contratsinsertion', 'notification', $dossierMenu ) == 1 )
									&& ( Set::classicExtract( $dossierpcg66, 'Contratinsertion.positioncer' ) != 'annule' )
								)
							)
						),
						$this->Default2->button(
							'filelink',
							array( 'controller' => 'contratsinsertion', 'action' => 'filelink',
							$dossierpcg66['Contratinsertion']['id'] ),
							array(
								'enabled' => (
									$this->Permissions->checkDossier( 'contratsinsertion', 'filelink', $dossierMenu ) == 1
								)
							)
						),
						h( '('.Set::classicExtract( $dossierpcg66, 'Fichiermodule.nbFichiersLies' ).')' )
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
		?>
	</tbody>
</table>
	<?php endif;?>

	<fieldset id="Propositionpcg" class="invisible"></fieldset>

		<?php
			if( !empty( $dossierpcg66['Dossierpcg66']['contratinsertion_id'] ) ) {
				$listdecisionpdo = $listdecisionpcgCer;
			}

			echo $this->Default2->subform(
				array(
					'Typersapcg66.Typersapcg66' => array( 'type' => 'select', 'label' => 'Type de prestation', 'multiple' => 'checkbox', 'empty' => false, 'options' => $typersapcg66 ),
					'Decisiondossierpcg66.decisionpdo_id' => array( 'type' => 'select', 'empty' => true, 'options' => $listdecisionpdo )
				),
				array(
					'options' => $options
				)
			);
		?>
		<fieldset id="propononvalidcerparticulier" class="invisible">
			<?php
				if( !empty( $idsDecisionNonValidCer ) ) {
					if( Set::check( $this->request->data, 'Propodecisioncer66.id' ) ){
						echo $this->Xform->input( 'Propodecisioncer66.id', array( 'type' => 'hidden' ) );
					}
					echo $this->Xform->input( 'Propodecisioncer66.contratinsertion_id', array( 'type' => 'hidden', 'value' => $contratinsertion_id ) );
					echo $this->Xform->input( 'Propodecisioncer66.datevalidcer', array( 'label' => 'Date de la proposition du CER', 'type' => 'date', 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 4, 'maxYear' => date( 'Y' ) + 2, 'empty' => false ) );
					echo $this->Default2->subform(
						array(
							'Motifcernonvalid66.Motifcernonvalid66' => array( 'type' => 'select', 'label' => 'Motif de non validation', 'multiple' => 'checkbox', 'empty' => false, 'options' => $listMotifs ),
							'Propodecisioncer66.motifficheliaison' => array( 'type' => 'textarea' ),
							'Propodecisioncer66.motifnotifnonvalid' => array( 'type' => 'textarea' )
						),
						array(
							'options' => $options
						)
					);
				}
			?>
		</fieldset>
		<?php
			echo $this->Default2->subform(
				array(
					'Decisiondossierpcg66.datepropositiontechnicien' => array( 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear' => date('Y')+5, 'minYear' => date('Y')-1, 'empty' => false ),
					'Decisiondossierpcg66.commentairetechnicien' => array( 'value' => isset( $dossierpcg66['Decisiondossierpcg66'][0]['commentairetechnicien'] ) ? ( $dossierpcg66['Decisiondossierpcg66'][0]['commentairetechnicien'] ) : null )
				),
				array(
					'options' => $options
				)
			);
		?>
	</fieldset>

	<?php if( !empty( $idsDecisionNonValidCer ) ):?>
		<script type="text/javascript">
			document.observe("dom:loaded", function() {
				observeDisableFieldsetOnValue(
					'Decisiondossierpcg66DecisionpdoId',
					$( 'propononvalidcerparticulier' ),
					['<?php echo implode( ',', $idsDecisionNonValidCer );?>'],
					false,
					true
				);

			});
		</script>
	<?php endif;?>

	<?php if( $avistechniquemodifiable && !in_array( $this->action, array( 'add', 'edit' ) ) && ( $this->Permissions->checkDossier( 'decisionsdossierspcgs66', 'avistechnique', $dossierMenu ) || $this->Permissions->checkDossier( 'decisionsdossierspcgs66', 'validation', $dossierMenu ) ) ):?>
		<fieldset id="avtech"><legend><?php echo 'Avis technique'; ?></legend>
				<?php
					if( empty( $this->request->data['Decisiondossierpcg66']['useravistechnique_id'] ) ){
						echo $this->Xform->input( 'Decisiondossierpcg66.useravistechnique_id', array( 'type' => 'hidden', 'value' => $userConnected ) );
					}
					echo $this->Default2->subform(
						array(
							'Decisiondossierpcg66.avistechnique' => array( 'label' => false, 'type' => 'radio', 'options' => $options['Decisiondossierpcg66']['avistechnique'] ),
//                                'Decisiondossierpcg66.useravistechnique_id' => array( 'type' => 'hidden', 'value' => $userConnected )
						),
						array(
							'options' => $options
						)
					);
				?>
				<fieldset id="avistech" class="noborder">
					<?php
						echo $this->Default2->subform(
							array(
								'Decisiondossierpcg66.commentaireavistechnique',
								'Decisiondossierpcg66.dateavistechnique' => array( 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear' => date('Y')+5, 'minYear' => date('Y')-1, 'empty' => false )
							),
							array(
								'options' => $options
							)
						);
						
						if( !empty( $decisiondossierpcg66['Decisiondossierpcg66']['avistechnique'] ) ) {
							echo $this->Xform->fieldValue( 'Decisiondossierpcg66.useravistechnique_id', Set::enum( Set::classicExtract( $decisiondossierpcg66, 'Decisiondossierpcg66.useravistechnique_id'), $gestionnaire ) );
						}
					?>
				</fieldset>
		</fieldset>
	<?php endif;?>

	<?php if( $validationmodifiable && !in_array( $this->action, array( 'add', 'edit' ) ) && ( $this->Permissions->checkDossier( 'decisionsdossierspcgs66', 'avistechnique', $dossierMenu ) || $this->Permissions->checkDossier( 'decisionsdossierspcgs66', 'validation', $dossierMenu ) ) ):?>
		<fieldset id="propovalid"><legend>Validation de la proposition</legend>
				<?php
					echo $this->Default2->subform(
						array(
							'Decisiondossierpcg66.validationproposition' => array( 'label' => false, 'type' => 'radio', 'options' => $options['Decisiondossierpcg66']['validationproposition'] ),
							'Decisiondossierpcg66.userproposition_id' => array( 'type' => 'hidden', 'value' => $userConnected )
						),
						array(
							'options' => $options
						)
					);
				?>
				<fieldset id="validpropo" class="noborder">
					<?php
						echo $this->Default2->subform(
						array(
							'Decisiondossierpcg66.retouravistechnique' => array( 'type' => 'checkbox' ),
							'Decisiondossierpcg66.vuavistechnique' => array( 'type' => 'checkbox' ),
							'Decisiondossierpcg66.commentairevalidation',
							'Decisiondossierpcg66.datevalidation' => array( 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear' => date('Y')+5, 'minYear' => date('Y')-1, 'empty' => false )
						),
						array(
							'options' => $options
						)
					);
						if( !empty( $decisiondossierpcg66['Decisiondossierpcg66']['validationproposition'] ) ) {
							echo $this->Xform->fieldValue( 'Decisiondossierpcg66.userproposition_id', Set::enum( Set::classicExtract( $decisiondossierpcg66, 'Decisiondossierpcg66.userproposition_id'), $gestionnaire ) );
						}
					?>
				</fieldset>
		</fieldset>
	<?php endif;?>

	<?php

		echo $this->Default2->subform(
			array(
				'Decisiondossierpcg66.commentaire' => array( 'label' =>  'Observations : ', 'type' => 'textarea', 'rows' => 3 ),
			),
			array(
				'options' => $options
			)
		);
	?>
<?php
	echo "<div class='submit'>";
		echo $this->Form->submit('Enregistrer', array('div'=>false));
		echo $this->Form->button( 'Retour', array( 'type' => 'button', 'onclick'=>"location.replace('".Router::url( array( 'controller' => 'dossierspcgs66', 'action' => 'edit', $dossierpcg66_id ) )."')" ) );
	echo "</div>";

	echo $this->Form->end();
?>

<?php echo $this->Xform->end();?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
	<?php if( $avistechniquemodifiable ):?>
		observeDisableFieldsetOnRadioValue(
			'decisiondossierpcg66form',
			'data[Decisiondossierpcg66][avistechnique]',
			$( 'avistech' ),
			['O','N'],
			false,
			true
		);
	<?php endif;?>

	<?php if( $validationmodifiable ):?>
		observeDisableFieldsetOnRadioValue(
			'decisiondossierpcg66form',
			'data[Decisiondossierpcg66][validationproposition]',
			$( 'validpropo' ),
			['O','N'],
			false,
			true
		);

		observeDisableFieldsOnValue(
			'Decisiondossierpcg66Retouravistechnique',
			[
				'Decisiondossierpcg66Vuavistechnique'
			],
			'1',
			false
		);

		$( 'decisiondossierpcg66form' ).getInputs( 'radio', 'data[Decisiondossierpcg66][validationproposition]' ).each( function( radio ) {
			$( radio ).observe( 'change', function( event ) {
				disableFieldsOnValue(
					'Decisiondossierpcg66Retouravistechnique',
					[
						'Decisiondossierpcg66Vuavistechnique'
					],
					'1',
					false
				);
			} );
		} );
	<?php endif;?>
	} );

</script>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		[ $('Decisiondossierpcg66Compofoyerpcg66Id'), $('Decisiondossierpcg66RecidiveN'), $('Decisiondossierpcg66RecidiveO'), $('Decisiondossierpcg66Phase') ].each(function(field) {
			field.observe('change', function(element, value) {
				fieldUpdater();
			});
		});

		fieldUpdater();
	});

	function radioValue( form, radioName ) {
		var v = $( form ).getInputs( 'radio', radioName );

		var currentValue = null;
		$( v ).each( function( radio ) {
			if( radio.checked ) {
				currentValue = radio.value;
			}
		} );

		return currentValue;
	}

	function fieldUpdater() {
		new Ajax.Updater(
			'Propositionpcg',
			'<?php echo Router::url( array( "action" => "ajaxproposition" ) ); ?>',
			{
				asynchronous:true,
				evalScripts:true,
				parameters:
				{
					'defautinsertion' : $F('Decisiondossierpcg66Defautinsertion'),
					'compofoyerpcg66_id' :  $F( 'Decisiondossierpcg66Compofoyerpcg66Id' ),
					'recidive' : radioValue( 'decisiondossierpcg66form', 'data[Decisiondossierpcg66][recidive]' ),
					'phase' : $F('Decisiondossierpcg66Phase'),
					'decisionpcg66_id' : '<?php echo @$this->request->data['Decisiondossierpcg66']['decisionpcg66_id'];?>'
				},
				requestHeaders:['X-Update', 'Propositionpcg']
			}
		);
	}
</script>