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
	<th>Action</th>
</tr>
<tr>
	<th colspan="5"></th>
	<th colspan="2">Sanction n°1</th>
	<th colspan="2">Sanction n°2</th>
	<th colspan="2"></th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		$multiple = ( count( $dossiersAllocataires[$dossierep['Personne']['id']] ) > 1 ? 'multipleDossiers' : null );

		$decisionep = @$dossierep['Passagecommissionep'][0]['Decisionsanctionrendezvousep58'][0];

		echo $this->Xhtml->tableCells(
			array(
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$this->Locale->date( __( 'Locale->date' ), $dossierep['Personne']['dtnai'] ),
				$this->Locale->date( __( 'Locale->date' ), $dossierep['Dossierep']['created'] ),
				Set::classicExtract( $typesrdv, $dossierep['Sanctionrendezvousep58']['Rendezvous']['typerdv_id'], true),

				array( @$options['Decisionsanctionrendezvousep58']['decision'][Set::classicExtract( $decisionep, "decision" )], array( 'id' => "Decisionsanctionrendezvousep58{$i}DecisionColumn" ) ),
				array( @$listesanctionseps58[Set::classicExtract( $decisionep, "listesanctionep58_id" )], array( 'id' => "Decisionsanctionrendezvousep58{$i}Listesanctionep58Id" ) ),
				array( @$options['Decisionsanctionrendezvousep58']['decision'][Set::classicExtract( $decisionep, "decision2" )], array( 'id' => "Decisionsanctionrendezvousep58{$i}DecisionColumn" ) ),
				array( @$listesanctionseps58[Set::classicExtract( $decisionep, "autrelistesanctionep58_id" )], array( 'id' => "Decisionsanctionrendezvousep58{$i}Autrelistesanctionep58Id" ) ),
				Set::classicExtract( $decisionep, "commentaire" ),
				$this->Xhtml->printLink( 'Imprimer', array( 'controller' => 'commissionseps', 'action' => 'impressionDecision', $dossierep['Passagecommissionep'][0]['id'] ), ( $commissionep['Commissionep']['etatcommissionep'] != 'annule' ) ),
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
			changeColspanViewInfosEps( 'Decisionsanctionrendezvousep58<?php echo $i;?>DecisionColumn', '<?php echo Set::classicExtract( $dossiers, "{$theme}.liste.{$i}.Passagecommissionep.0.Decisionsanctionrendezvousep58.0.decision" );?>', 2, [ 'Decisionsanctionrendezvousep58<?php echo $i;?>Listesanctionep58Id' ] );
		<?php endfor;?>
	});
</script>