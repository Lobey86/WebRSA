<?php
echo '<table><thead>
<tr>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Date d\'orientation</th>
<th>Orientation actuelle</th>
<th colspan="3">Avis EPL</th>
<th>Observations</th>
<th>Action</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		$decisionep = $dossierep['Passagecommissionep'][0]['Decisionnonorientationproep58'][0];

		echo $xhtml->tableCells(
			array(
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Nonorientationproep58']['Orientstruct']['date_valid'] ),
				implode(
					' - ',
					array(
						$dossierep['Nonorientationproep58']['Orientstruct']['Typeorient']['lib_type_orient'],
						$dossierep['Nonorientationproep58']['Orientstruct']['Structurereferente']['lib_struc'],
						implode(
							' ',
							array(
								@$dossierep['Nonorientationproep58']['Orientstruct']['Referent']['qual'],
								@$dossierep['Nonorientationproep58']['Orientstruct']['Referent']['nom'],
								@$dossierep['Nonorientationproep58']['Orientstruct']['Referent']['prenom']
							)
						)
					)
				),

				array( $options['Decisionnonorientationproep58']['decision'][Set::classicExtract( $decisionep, "decision" )], array( 'id' => "Decisionnonorientationproep58{$i}DecisionColumn" ) ),
				array( @$liste_typesorients[Set::classicExtract( $decisionep, "typeorient_id" )], array( 'id' => "Decisionnonorientationproep58{$i}TypeorientId" ) ),
				array( @$liste_structuresreferentes[Set::classicExtract( $decisionep, "structurereferente_id" )], array( 'id' => "Decisionnonorientationproep58{$i}StructurereferenteId" ) ),
// 				array( Set::classicExtract( $decisionep, "raisonnonpassage" ), array( 'colspan' => '2', 'id' => "Decisionnonorientationproep58{$i}Raisonnonpassage" ) ),
				Set::classicExtract( $decisionep, "commentaire" ),
				$xhtml->printLink( 'Imprimer', array( 'controller' => 'commissionseps', 'action' => 'impressionDecision', $dossierep['Passagecommissionep'][0]['id'] ) )
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
			changeColspanAnnuleReporte( 'Decisionnonorientationproep58<?php echo $i;?>DecisionColumn', '<?php echo Set::classicExtract( $dossiers, "{$theme}.liste.{$i}.Passagecommissionep.0.Decisionnonorientationproep58.0.decision" );?>', 3, [ 'Decisionnonorientationproep58<?php echo $i;?>TypeorientId', 'Decisionnonorientationproep58<?php echo $i;?>StructurereferenteId' ] );

// 			afficheRaisonpassage( '<?php echo Set::classicExtract( $dossiers, "{$theme}.liste.{$i}.Passagecommissionep.0.Decisionnonorientationproep58.0.decision" );?>', [ 'Decisionnonorientationproep58<?php echo $i;?>TypeorientId', 'Decisionnonorientationproep58<?php echo $i;?>StructurereferenteId' ], 'Decisionnonorientationproep58<?php echo $i;?>Raisonnonpassage' );
		<?php endfor;?>
	});
</script>