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
		$indexDecision = count( $dossierep['Passagecommissionep'][0]['Decisioncontratcomplexeep93'] ) - 1;

		$innerTable = "<table id=\"innerTableDecisioncontratcomplexeep93{$i}\" class=\"innerTable\">
			<tbody>
				<tr>
					<th>Observations de l'EP</th>
					<td>".Set::classicExtract( $dossierep, "Passagecommissionep.0.Decisioncontratcomplexeep93.{$indexDecision}.commentaire" )."</td>
				</tr>
			</tbody>
		</table>";

		$hiddenFields = $form->input( "Decisioncontratcomplexeep93.{$i}.id", array( 'type' => 'hidden' ) ).
						$form->input( "Decisioncontratcomplexeep93.{$i}.passagecommissionep_id", array( 'type' => 'hidden' ) ).
						$form->input( "Decisioncontratcomplexeep93.{$i}.etape", array( 'type' => 'hidden', 'value' => 'cg' ) );

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
							Set::enum( @$dossierep['Passagecommissionep'][0]['Decisioncontratcomplexeep93'][$indexDecision]['decision'], $options['Decisioncontratcomplexeep93']['decision'] ),
							$locale->date( 'Locale->date', @$dossierep['Passagecommissionep'][0]['Decisioncontratcomplexeep93'][$indexDecision]['datevalidation_ci'] ),
							@$dossierep['Passagecommissionep'][0]['Decisioncontratcomplexeep93'][$indexDecision]['observ_ci'],
							@$dossierep['Passagecommissionep'][0]['Decisioncontratcomplexeep93'][$indexDecision]['raisonnonpassage']
						)
					)
				),

				$form->input( "Decisioncontratcomplexeep93.{$i}.decisionpcg", array( 'legend' => false, 'options' => @$options['Decisionreorientationep93']['decisionpcg'], 'type' => 'radio' ) ),
				array(
					$form->input( "Decisioncontratcomplexeep93.{$i}.decision", array( 'type' => 'select', 'options' => $options['Decisioncontratcomplexeep93']['decision'], 'label' => false ) ),
					array( 'id' => "Decisioncontratcomplexeep93{$i}DecisionColumn", 'class' => ( !empty( $this->validationErrors['Decisioncontratcomplexeep93'][$i]['decision'] ) ? 'error' : '' ) )
				),
				array(
					$form->input( "Decisioncontratcomplexeep93.{$i}.datevalidation_ci", array( 'type' => 'date', 'label' => false, 'dateFormat' => __( 'Locale->dateFormat', true ) ) ),
					( !empty( $this->validationErrors['Decisioncontratcomplexeep93'][$i]['datevalidation_ci'] ) ? array( 'class' => 'error' ) : array() )
				),
				array(
					$form->input( "Decisioncontratcomplexeep93.{$i}.observ_ci", array( 'type' => 'textarea', 'label' => false ) ),
					( !empty( $this->validationErrors['Decisioncontratcomplexeep93'][$i]['observ_ci'] ) ? array( 'class' => 'error' ) : array() )
				),
				$form->input( "Decisioncontratcomplexeep93.{$i}.commentaire", array( 'label' => false, 'type' => 'textarea' ) ).
				$hiddenFields,
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
			observeDisableFieldsOnValue(
				'Decisioncontratcomplexeep93<?php echo $i;?>Decision',
				[ 'Decisioncontratcomplexeep93<?php echo $i;?>DatevalidationCiDay', 'Decisioncontratcomplexeep93<?php echo $i;?>DatevalidationCiMonth', 'Decisioncontratcomplexeep93<?php echo $i;?>DatevalidationCiYear' ],
				'valide',
				false
			);

			$( 'Decisioncontratcomplexeep93<?php echo $i;?>DecisionpcgEnattente' ).observe( 'click', function() {
				$( 'Decisioncontratcomplexeep93<?php echo $i;?>Decision' ).setValue( 'reporte' );
				fireEvent( $( 'Decisioncontratcomplexeep93<?php echo $i;?>Decision' ), 'change' );
			} );

			$( 'Decisioncontratcomplexeep93<?php echo $i;?>Decision' ).observe( 'change', function() {
				changeColspanAnnuleReporte( 'Decisioncontratcomplexeep93<?php echo $i;?>DecisionColumn', 3, 'Decisioncontratcomplexeep93<?php echo $i;?>Decision', [ 'Decisioncontratcomplexeep93<?php echo $i;?>ObservCi', 'Decisioncontratcomplexeep93<?php echo $i;?>DatevalidationCiDay', 'Decisioncontratcomplexeep93<?php echo $i;?>DatevalidationCiMonth', 'Decisioncontratcomplexeep93<?php echo $i;?>DatevalidationCiYear' ] );
			});
			changeColspanAnnuleReporte( 'Decisioncontratcomplexeep93<?php echo $i;?>DecisionColumn', 3, 'Decisioncontratcomplexeep93<?php echo $i;?>Decision', [ 'Decisioncontratcomplexeep93<?php echo $i;?>ObservCi', 'Decisioncontratcomplexeep93<?php echo $i;?>DatevalidationCiDay', 'Decisioncontratcomplexeep93<?php echo $i;?>DatevalidationCiMonth', 'Decisioncontratcomplexeep93<?php echo $i;?>DatevalidationCiYear' ] );

// 			$( 'Decisioncontratcomplexeep93<?php echo $i;?>Decision' ).observe( 'change', function() {
// 				afficheRaisonpassage(
// 					'Decisioncontratcomplexeep93<?php echo $i;?>Decision',
// 					[ 'Decisioncontratcomplexeep93<?php echo $i;?>ObservCi', 'Decisioncontratcomplexeep93<?php echo $i;?>DatevalidationCiDay', 'Decisioncontratcomplexeep93<?php echo $i;?>DatevalidationCiMonth', 'Decisioncontratcomplexeep93<?php echo $i;?>DatevalidationCiYear' ],
// 					'Decisioncontratcomplexeep93<?php echo $i;?>Raisonnonpassage'
// 				);
// 			});
// 			afficheRaisonpassage(
// 				'Decisioncontratcomplexeep93<?php echo $i;?>Decision',
// 				[ 'Decisioncontratcomplexeep93<?php echo $i;?>ObservCi', 'Decisioncontratcomplexeep93<?php echo $i;?>DatevalidationCiDay', 'Decisioncontratcomplexeep93<?php echo $i;?>DatevalidationCiMonth', 'Decisioncontratcomplexeep93<?php echo $i;?>DatevalidationCiYear' ],
// 				'Decisioncontratcomplexeep93<?php echo $i;?>Raisonnonpassage'
// 			);
		<?php endfor;?>
	});
</script>