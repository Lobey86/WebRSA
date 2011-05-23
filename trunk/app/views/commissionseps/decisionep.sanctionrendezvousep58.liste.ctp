<?php
echo '<table><thead>
<tr>
<th>Dossier EP</th>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Origine du dossier</th>
<th colspan=\'2\'>Avis EPL</th>
<th>Observations</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		$decisionep = $dossierep['Passagecommissionep'][0]['Decisionsanctionrendezvousep58'][0];
		
		echo $xhtml->tableCells(
			array(
				$dossierep['Dossierep']['id'],
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				Set::classicExtract( $typesrdv, $dossierep['Sanctionrendezvousep58']['Rendezvous']['typerdv_id'], true),

				array(
					$options['Decisionsanctionrendezvousep58']['decision'][Set::classicExtract( $decisionep, "decision" )],
					array( 'id' => "Decisionsanctionrendezvousep58{$i}ColumnDecision", 'colspan' => 2 )
				),
				array( Set::classicExtract( $decisionep, "raisonnonpassage" ), array( 'id' => "Decisionsanctionrendezvousep58{$i}Raisonnonpassage" ) ),
				Set::classicExtract( $decisionep, "commentaire"
			)
		);
	}
	echo '</tbody></table>';
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>
			changeColspanRaisonNonPassage( 'Decisionsanctionrendezvousep58<?php echo $i;?>ColumnDecision', '<?php echo Set::classicExtract( $dossiers, "{$theme}.liste.{$i}.Passagecommissionep.0.Decisionsanctionrendezvousep58.0.decision" );?>', [ ], 'Decisionsanctionrendezvousep58<?php echo $i;?>Raisonnonpassage' );
		<?php endfor;?>
	});
</script>