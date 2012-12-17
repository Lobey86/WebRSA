<?php
	$title_for_layout = 'Valider chargé de suivi';
	$this->set( 'title_for_layout', $title_for_layout );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $this->Html->script( 'prototype.livepipe.js' );
		echo $this->Html->script( 'prototype.tabs.js' );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}
	
	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>

<div class="with_treemenu">
	<?php
		// FIXME: liste de titres depuis le contrôleur
		echo $this->Html->tag( 'h1', $title_for_layout );
	?>
	<br />
	<div id="tabbedWrapper" class="tabs">
		<div id="premierelecture">
			<h2 class="title">Seconde lecture</h2>
			<?php

				echo $this->Xform->create( null, array( 'id' => 'FormHistochoixcer93', 'inputDefaults' => array( 'domain' => 'histochoixcer93' ) ) );

				// FIXME: affichage du CER et des étapes précédentes de l'historique

				echo $this->Xform->inputs(
					array(
						'fieldset' => false,
						'legend' => false,
						'Histochoixcer93.id' => array( 'type' => 'hidden' ),
						'Histochoixcer93.cer93_id' => array( 'type' => 'hidden' ),
						'Histochoixcer93.user_id' => array( 'type' => 'hidden' ),
						'Histochoixcer93.formeci' => array( 'type' => 'radio', 'options' => $options['Cer93']['formeci'] ),
						'Histochoixcer93.commentaire' => array( 'type' => 'textarea' ),
						'Histochoixcer93.datechoix' => array( 'type' => 'date', 'dateFormat' => 'DMY' ),
						'Histochoixcer93.duree' => array( 'legend' => 'Ce contrat est proposé pour une durée de ', 'domain' => 'cer93', 'type' => 'radio', 'options' => $options['Cer93']['duree'] ),
						'Histochoixcer93.decisioncs' => array( 'type' => 'select', 'options' => $options['Histochoixcer93']['decisioncs'], 'empty' => true ),
						'Histochoixcer93.etape' => array( 'type' => 'hidden' ),
					)
				);
			?>

			<?php
				echo $this->Html->tag(
					'div',
					$this->Xform->button( 'Enregistrer', array( 'type' => 'submit' ) )
					.$this->Xform->button( 'Annuler', array( 'type' => 'submit', 'name' => 'Cancel' ) ),
					array( 'class' => 'submit noprint' )
				);

				echo $this->Xform->end();
			?>
		</div>
		<div id="historique">
			<h2 class="title">Décisions précédentes</h2>
				<?php
				$histo = $contratinsertion['Cer93']['Histochoixcer93'];
				foreach( $histo as $i => $h ) {
					$etapeValue = Set::classicExtract( $h, 'etape');
					$etape = Set::enum( Set::classicExtract( $h, 'etape'), $options['Histochoixcer93']['etape'] );
					if( $etapeValue != '02attdecisioncpdv' ) {
						echo "<fieldset><legend>$etape</legend>";
						echo $this->Xform->fieldValue( 'Cer93.formeci', Set::enum( Set::classicExtract( $h, 'formeci'), $options['Cer93']['formeci'] ) );
						echo $this->Xform->fieldValue( 'Histochoixcer93.datechoix', date_short( Set::classicExtract( $h, 'datechoix') ) );

						if( !empty( $h['commentaire'] ) ) {
							echo $this->Xform->fieldValue( 'Histochoixcer93.commentaire', Set::classicExtract( $h, 'commentaire') );
						}
						
						if( !empty( $h['Commentairenormecer93'] ) ) {
							echo '<fieldset><legend>Commentaires</legend>';
							$commentaires = '';
							foreach( $h['Commentairenormecer93'] as $key => $commentaire ) {
								if( !empty( $commentaire ) ) {
									if( $commentaire['isautre'] == '1' ) {
										$commentaires .= "<li>{$commentaire['name']}: <em>{$commentaire['Commentairenormecer93Histochoixcer93']['commentaireautre']}</em></li>";
									}
									else {
										$commentaires .= '<li>'.$commentaire['name'].'</li>';
									}
								}
							}

							if( !empty( $commentaires ) ) {
								echo "<ul>{$commentaires}</ul></fieldset>";
							}
						}

						echo $this->Xform->fieldValue( 'Histochoixcer93.user_id', Set::classicExtract( $h, 'User.nom_complet') );

						if( $h['etape'] == '03attdecisioncg' ) {
							echo $this->Xform->fieldValue( 'Histochoixcer93.isrejet', Set::enum( Set::classicExtract( $h, 'isrejet'), $options['Histochoixcer93']['isrejet'] ) );
						}
						else if( $h['etape'] == '04premierelecture' ) {
							echo $this->Xform->fieldValue( 'Histochoixcer93.prevalide', Set::enum( Set::classicExtract( $h, 'prevalide'), $options['Histochoixcer93']['prevalide'] ) );
						}
						else if( $h['etape'] == '05secondelecture' ) {
							echo $this->Xform->fieldValue( 'Histochoixcer93.decisioncs', Set::enum( Set::classicExtract( $h, 'decisioncs'), $options['Histochoixcer93']['decisioncs'] ) );
						}
						else if( $h['etape'] == '06attaviscadre' ) {
							echo $this->Xform->fieldValue( 'Histochoixcer93.decisioncadre', Set::enum( Set::classicExtract( $h, 'decisioncadre'), $options['Histochoixcer93']['decisioncadre'] ) );
						}
						echo '</fieldset>';
					}
				}
			?>
		</div>
		<div id="cerview">
			<h2 class="title">Visualisation du CER</h2>
			<?php
				include( dirname( __FILE__ ).'/../Cers93/_view.ctp' );
			?>
		</div>
	</div>
</div>
<div class="clearer"><hr /></div>
<script type="text/javascript">
	observeFilterSelectOptionsFromRadioValue(
		'FormHistochoixcer93',
		'data[Histochoixcer93][formeci]',
		'Histochoixcer93Decisioncs',
		{
			'S': ['valide', 'aviscadre'],
			'C': ['aviscadre', 'passageep']
		}
	);

	makeTabbed( 'tabbedWrapper', 2 );
</script>