<?php
echo '<table><thead>
<tr>
<th rowspan="2">Dossier EP</th>
<th rowspan="2">Nom du demandeur</th>
<th rowspan="2">Adresse</th>
<th rowspan="2">Date de naissance</th>
<th rowspan="2">Création du dossier EP</th>
<th rowspan="2">Date de début du contrat</th>
<th rowspan="2">Date de fin du contrat</th>
<th rowspan="2">Avis EP</th>
<th colspan="3">Décision CG</th>
</tr>
<tr>
<th>Décision</th>
<th>Date de validation</th>
<th>Observations</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		$indexDecision = count( $dossierep['Passagecommissionep'][0]['Decisioncontratcomplexeep93'] ) - 1;
		echo $xhtml->tableCells(
			array(
				$dossierep['Dossierep']['id'],
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				$locale->date( __( 'Locale->date', true ), @$dossierep['Contratcomplexeep93']['Contratinsertion']['dd_ci'] ),
				$locale->date( __( 'Locale->date', true ), @$dossierep['Contratcomplexeep93']['Contratinsertion']['df_ci'] ),
				implode(
					' - ',
					Set::filter(
						array(
							Set::enum( @$dossierep['Passagecommissionep'][0]['Decisioncontratcomplexeep93'][$indexDecision]['decision'], $options['Decisioncontratcomplexeep93']['decision'] ),
							$locale->date( 'Locale->date', @$dossierep['Passagecommissionep'][0]['Decisioncontratcomplexeep93'][$indexDecision]['datevalidation_ci'] ),
							@$dossierep['Passagecommissionep'][0]['Decisioncontratcomplexeep93'][$indexDecision]['observ_ci'],
							@$dossierep['Passagecommissionep'][0]['Decisioncontratcomplexeep93'][$indexDecision]['raisonnonpassage']
						)
					)
				),

				$options['Decisionnonorientationproep93']['decision'][Set::classicExtract( $datas, "Decisionnonorientationproep93.{$i}.decision" )],
				array( Set::classicExtract( $datas, "Decisionnonorientationproep93.{$i}.datevalidation_ci" ), array( 'id' => "Decisionnonorientationproep93{$i}DatevalidationCi" ) ),
				array( Set::classicExtract( $datas, "Decisionnonorientationproep93.{$i}.observ_ci" ), array( 'id' => "Decisionnonorientationproep93{$i}ObservCi" ) ),
				array( Set::classicExtract( $datas, "Decisionnonorientationproep93.{$i}.raisonnonpassage" ), array( 'colspan' => '2', 'id' => "Decisionnonorientationproep93{$i}Raisonnonpassage" ) )
			)
		);
	}
	echo '</tbody></table>';
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>
			afficheRaisonpassage(
				'<?php echo $options['Decisioncontratcomplexeep93']['decision'][Set::classicExtract( $datas, "Decisioncontratcomplexeep93.{$i}.decision" )];?>',
				[ 'Decisioncontratcomplexeep93<?php echo $i;?>ObservCi', 'Decisioncontratcomplexeep93<?php echo $i;?>DatevalidationCiDay' ],
				'Decisioncontratcomplexeep93<?php echo $i;?>Raisonnonpassage'
			);
		<?php endfor;?>
	});
</script>