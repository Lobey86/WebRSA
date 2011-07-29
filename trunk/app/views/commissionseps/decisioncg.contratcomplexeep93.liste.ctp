<?php
echo '<table id="Decisioncontratcomplexeep93" class="tooltips"><thead>
<tr>
<th rowspan="2">Nom du demandeur</th>
<th rowspan="2">Adresse</th>
<th rowspan="2">Date de naissance</th>
<th rowspan="2">Création du dossier EP</th>
<th rowspan="2">Date de début du contrat</th>
<th rowspan="2">Date de fin du contrat</th>
<th rowspan="2">Avis EP</th>
<th colspan="4">Décision CG</th>
<th rowspan="2">Observations</th>
<th rowspan="2">Action</th>
<th rowspan="2" class="innerTableHeader noprint">Avis EP</th>
</tr>
<tr>
<th>Décision PCG</th>
<th>Décision</th>
<th>Date de validation</th>
<th>Observations du contrat</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		$decisionep = @$dossierep['Passagecommissionep'][0]['Decisioncontratcomplexeep93'][1];
		$decisioncg = @$dossierep['Passagecommissionep'][0]['Decisioncontratcomplexeep93'][0];

		$innerTable = "<table id=\"innerTableDecisioncontratcomplexeep93{$i}\" class=\"innerTable\">
			<tbody>
				<tr>
					<th>Observations de l'EP</th>
					<td>".Set::classicExtract( $decisionep, "commentaire" )."</td>
				</tr>
			</tbody>
		</table>";

		echo $xhtml->tableCells(
			array(
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
							Set::enum( $decisionep['decision'], $options['Decisioncontratcomplexeep93']['decision'] ),
							$locale->date( 'Locale->date', $decisionep['datevalidation_ci'] ),
							$decisionep['observ_ci'],
							$decisionep['raisonnonpassage']
						)
					)
				),

				@$options['Decisioncontratcomplexeep93']['decisionpcg'][Set::classicExtract( $decisioncg, "decisionpcg" )],
				array( @$options['Decisioncontratcomplexeep93']['decision'][Set::classicExtract( $decisioncg, "decision" )], array( 'id' => "" ) ),
				array( Set::classicExtract( $decisioncg, "datevalidation_ci" ), array( 'id' => "Decisioncontratcomplexeep93{$i}DatevalidationCi" ) ),
				array( Set::classicExtract( $decisioncg, "observ_ci" ), array( 'id' => "Decisioncontratcomplexeep93{$i}ObservCi" ) ),
				Set::classicExtract( $decisioncg, "commentaire" ),
				$xhtml->printLink( 'Imprimer', array( 'controller' => 'commissionseps', 'action' => 'impressionDecision', $dossierep['Passagecommissionep'][0]['id'] ), ( $commissionep['Commissionep']['etatcommissionep'] != 'annule' ) ),
				array( $innerTable, array( 'class' => 'innerTableCell noprint' ) )
			),
			array( 'class' => 'odd' ),
			array( 'class' => 'even' )
		);
	}
	echo '</tbody></table>';
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>
			changeColspanViewInfosEps( 'Decisioncontratcomplexeep93<?php echo $i;?>DecisionColumn', '<?php echo Set::classicExtract( $dossiers, "{$theme}.liste.{$i}.Passagecommissionep.0.Decisioncontratcomplexeep93.0.decision" );?>', 3, [ 'Decisioncontratcomplexeep93<?php echo $i;?>DatevalidationCi', 'Decisioncontratcomplexeep93<?php echo $i;?>ObservCi' ] );
		<?php endfor;?>
	});
</script>