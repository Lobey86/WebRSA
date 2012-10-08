<?php
echo '<table><thead>
<tr>
	<th>Nom du demandeur</th>
	<th>Adresse</th>
	<th>Date de naissance</th>
	<th>Création du dossier EP</th>
	<th>Origine du dossier</th>
	<th colspan=\'4\'>Avis EPL</th>
	<th>Observations</th>
</tr>
<tr>
	<th colspan="5"></th>
	<th colspan="2">Sanction n°1</th>
	<th colspan="2">Sanction n°2</th>
	<th></th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		$multiple = ( count( $dossiersAllocataires[$dossierep['Personne']['id']] ) > 1 ? 'multipleDossiers' : null );

		$hiddenFields = $this->Form->input( "Decisionsanctionep58.{$i}.id", array( 'type' => 'hidden' ) ).
						$this->Form->input( "Decisionsanctionep58.{$i}.etape", array( 'type' => 'hidden', 'value' => 'ep' ) ).
						$this->Form->input( "Decisionsanctionep58.{$i}.sanctionep58_id", array( 'type' => 'hidden' ) ).
						$this->Form->input( "Decisionsanctionep58.{$i}.passagecommissionep_id", array( 'type' => 'hidden' ) ).
						$this->Form->input( "Decisionsanctionep58.{$i}.user_id", array( 'type' => 'hidden', 'value' => $this->Session->read( 'Auth.User.id' ) ) );

		echo $this->Xhtml->tableCells(
			array(
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$this->Locale->date( __( 'Locale->date' ), $dossierep['Personne']['dtnai'] ),
				$this->Locale->date( __( 'Locale->date' ), $dossierep['Dossierep']['created'] ),
				$options['Sanctionep58']['origine'][$dossierep['Sanctionep58']['origine']],

				array(
					$this->Form->input( "Decisionsanctionep58.{$i}.decision", array( 'type' => 'select', 'label' => false, 'empty' => true, 'options' => @$options['Decisionsanctionep58']['decision'] ) ),
					array( 'id' => "Decisionsanctionep58{$i}DecisionColumn", 'class' => ( !empty( $this->validationErrors['Decisionsanctionep58'][$i]['decision'] ) ? 'error' : '' ) )
				),
				array(
					$this->Form->input( "Decisionsanctionep58.{$i}.listesanctionep58_id", array( 'type' => 'select', 'label' => false, 'options' => $listesanctionseps58 ) ),
					( !empty( $this->validationErrors['Decisionsanctionep58'][$i]['listesanctionep58_id'] ) ? array( 'class' => 'error' ) : array() )
				),
				//Ajout de la possibilité de saisir une 2ème sanction lors de l'émission de la décision en EP
				array(
					$this->Form->input( "Decisionsanctionep58.{$i}.decision2", array( 'type' => 'select', 'label' => false, 'empty' => true, 'options' => @$options['Decisionsanctionep58']['decision'], 'value' => @$dossierep['Sanctionep58']['Decisionsanctionep58'][0]['decision2'] ) ),
					array( 'id' => "Decisionsanctionep58{$i}DecisionColumn2", 'class' => ( !empty( $this->validationErrors['Decisionsanctionep58'][$i]['decision2'] ) ? 'error' : '' ) )
				),
				array(
					$this->Form->input( "Decisionsanctionep58.{$i}.autrelistesanctionep58_id", array( 'type' => 'select', 'label' => false, 'options' => $listesanctionseps58 ) ),
					( !empty( $this->validationErrors['Decisionsanctionep58'][$i]['autrelistesanctionep58_id'] ) ? array( 'class' => 'error' ) : array() )
				),
				$this->Form->input( "Decisionsanctionep58.{$i}.commentaire", array( 'label' => false, 'type' => 'textarea' ) ).
				$hiddenFields
			),
			array( 'class' => "odd {$multiple}" ),
			array( 'class' => "even {$multiple}" )
		);
	}
	echo '</tbody></table>';
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>

			$( 'Decisionsanctionep58<?php echo $i;?>Decision' ).observe( 'change', function() {
				changeColspanFormAnnuleReporteEps( 'Decisionsanctionep58<?php echo $i;?>DecisionColumn', 2, 'Decisionsanctionep58<?php echo $i;?>Decision', [ 'Decisionsanctionep58<?php echo $i;?>Listesanctionep58Id' ] );
			});
			changeColspanFormAnnuleReporteEps( 'Decisionsanctionep58<?php echo $i;?>DecisionColumn', 2, 'Decisionsanctionep58<?php echo $i;?>Decision', [ 'Decisionsanctionep58<?php echo $i;?>Listesanctionep58Id' ] );

			
			$( 'Decisionsanctionep58<?php echo $i;?>Decision2' ).observe( 'change', function() {
				changeColspanFormAnnuleReporteEps( 'Decisionsanctionep58<?php echo $i;?>DecisionColumn2', 2, 'Decisionsanctionep58<?php echo $i;?>Decision2', [ 'Decisionsanctionep58<?php echo $i;?>Autrelistesanctionep58Id' ] );
			});
			changeColspanFormAnnuleReporteEps( 'Decisionsanctionep58<?php echo $i;?>DecisionColumn2', 2, 'Decisionsanctionep58<?php echo $i;?>Decision2', [ 'Decisionsanctionep58<?php echo $i;?>Autrelistesanctionep58Id' ] );

			
			observeDisableFieldsOnValue(
				'Decisionsanctionep58<?php echo $i;?>Decision',
				[
					'Decisionsanctionep58<?php echo $i;?>Listesanctionep58Id'
				],
				'sanction',
				false
			);
			observeDisableFieldsOnValue(
				'Decisionsanctionep58<?php echo $i;?>Decision2',
				[
					'Decisionsanctionep58<?php echo $i;?>Autrelistesanctionep58Id'
				],
				'sanction',
				false
			);
		<?php endfor;?>
	});
</script>