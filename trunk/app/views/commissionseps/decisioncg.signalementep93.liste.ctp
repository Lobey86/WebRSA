<?php
echo '<table><thead>
<tr>
<th>Dossier EP</th>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Date de début du contrat</th>
<th>Date de fin du contrat</th>
<th>Date de signalement</th>
<th>Motif de passage en EP</th>
<th>Rang du passage en EP</th>
<th>Situation familiale</th>
<th>Nombre d\'enfants</th>
<th>Avis EP</th>
<th id=\'decisionCg\' colspan="3">Décision CG</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		$decisionep = $dossierep['Passagecommissionep'][0]['Decisionsignalementep93'][1];
		$decisioncg = $dossierep['Passagecommissionep'][0]['Decisionsignalementep93'][0];

		$lineOptions = array();
		foreach( $options['Decisionsignalementep93']['decision'] as $key => $label ) {
			if( !in_array( $key[0], array( 1, 2 ) ) || ( $key[0] == min( 2, $dossierep['Signalementep93']['rang'] ) ) ) {
				$lineOptions[$key] = $label;
			}
		}

		echo $xhtml->tableCells(
			array(
				$dossierep['Dossierep']['id'],
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				$locale->date( __( 'Locale->date', true ), @$dossierep['Signalementep93']['Contratinsertion']['dd_ci'] ),
				$locale->date( __( 'Locale->date', true ), @$dossierep['Signalementep93']['Contratinsertion']['df_ci'] ),
				$locale->date( __( 'Locale->date', true ), @$dossierep['Signalementep93']['date'] ),
				@$dossierep['Signalementep93']['motif'],
				@$dossierep['Signalementep93']['rang'],
				Set::enum( @$dossierep['Personne']['Foyer']['sitfam'], $options['Foyer']['sitfam'] ),
				@$dossierep['Personne']['Foyer']['nbenfants'],
				implode(
					' - ',
					Set::filter(
						array(
							Set::enum( @$dossierep['decision'], $options['Decisionsignalementep93']['decision'] ),
							@$dossierep['raisonnonpassage']
						)
					)
				),

				$options['Decisionsignalementep93']['decisionpcg'][Set::classicExtract( $decisioncg, "decisionpcg" )],
				$options['Decisionsignalementep93']['decision'][Set::classicExtract( $decisioncg, "decision" )],
				array( Set::classicExtract( $decisioncg, "raisonnonpassage" ), array( 'colspan' => '2', 'id' => "Decisionsignalementep93{$i}Raisonnonpassage" ) )
			)
		);
	}
	echo '</tbody></table>';
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>
			changeColspanRaisonNonPassage( 'decisionCg', '<?php echo Set::classicExtract( $dossiers, "{$theme}.liste.{$i}.Passagecommissionep.0.Decisionsignalementep93.0.decision" );?>', [], 'Decisionsignalementep93<?php echo $i;?>Raisonnonpassage' );
		<?php endfor;?>
	});
</script>
