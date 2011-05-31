<?php
echo '<table><thead>
<tr>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th colspan="2">Orientation actuelle</th>
<th colspan="2">Proposition référent</th>
<th colspan="3">Avis EPL</th>
<th>Observations</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		$decisionep = $dossierep['Passagecommissionep'][0]['Decisionregressionorientationep58'][0];

		echo $xhtml->tableCells(
			array(
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				@$dossierep['Personne']['Orientstruct'][0]['Typeorient']['lib_type_orient'],
				@$dossierep['Personne']['Orientstruct'][0]['Structurereferente']['lib_struc'],
				@$dossierep['Regressionorientationep58']['Typeorient']['lib_type_orient'],
				@$dossierep['Regressionorientationep58']['Structurereferente']['lib_struc'],

				array( $options['Decisionregressionorientationep58']['decision'][Set::classicExtract( $decisionep, "decision" )], array( 'id' => "Decisionregressionorientationep58{$i}DecisionColumn" ) ),
				array( @$liste_typesorients[Set::classicExtract( $decisionep, "typeorient_id" )], array( 'id' => "Decisionregressionorientationep58{$i}TypeorientId" ) ),
				array( @$liste_structuresreferentes[Set::classicExtract( $decisionep, "structurereferente_id" )], array( 'id' => "Decisionregressionorientationep58{$i}StructurereferenteId" ) ),
// 				array( Set::classicExtract( $decisionep, "raisonnonpassage" ), array( 'colspan' => '2', 'id' => "Decisionregressionorientationep58{$i}Raisonnonpassage" ) ),
				Set::classicExtract( $decisionep, "commentaire" )
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
			changeColspanAnnuleReporte( 'Decisionregressionorientationep58<?php echo $i;?>DecisionColumn', '<?php echo Set::classicExtract( $dossiers, "{$theme}.liste.{$i}.Passagecommissionep.0.Decisionregressionorientationep58.0.decision" );?>', 3, [ 'Decisionregressionorientationep58<?php echo $i;?>TypeorientId', 'Decisionregressionorientationep58<?php echo $i;?>StructurereferenteId' ] );

// 			afficheRaisonpassage( '<?php echo Set::classicExtract( $dossiers, "{$theme}.liste.{$i}.Passagecommissionep.0.Decisionregressionorientationep58.0.decision" );?>', [ 'Decisionregressionorientationep58<?php echo $i;?>TypeorientId', 'Decisionregressionorientationep58<?php echo $i;?>StructurereferenteId' ], 'Decisionregressionorientationep58<?php echo $i;?>Raisonnonpassage' );
		<?php endfor;?>
	});
</script>