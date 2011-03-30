<!-- <h2>Demandes de réorientation 93 par liste</h2> -->

<?php
// 	echo $form->create( null, array( 'url' => Router::url( null, true ) ) );
	echo '<table><thead>
<tr>
<th>Dossier EP</th>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Motif de la demande</th>
<th>Orientation actuelle</th>
<th>Structure référente actuelle</th>
<th>Orientation préconisée</th>
<th>Structure référente préconisée</th>
<th>Avis EP</th>
<th>Orientation choisie</th>
<th>Structure référente choisie</th>
</tr>
</thead><tbody>';
// debug( $this->data );
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		// Pré-remplissage avec les valeurs de l'avis EP
		// $this->data est peuplée avec prepareFormData, donc on utilise un autre moyen pour savoir si on a renvoyé le formulaire
// 		if( empty( $_POST ) ) {
// 			if( @$dossierep['Reorientationep93']['Decisionreorientationep93'][count(@$dossierep['Reorientationep93']['Decisionreorientationep93'])-1]['etape'] == 'cg' ) {
// 				$record = @$dossierep['Reorientationep93']['Decisionreorientationep93'][count(@$dossierep['Reorientationep93']['Decisionreorientationep93'])-1];
// 			}
// 			else {
// 				$record = @$dossierep['Reorientationep93']['Decisionreorientationep93'][0];
// 			}
// 		}
		echo $xhtml->tableCells(
			array(
				$dossierep['Dossierep']['id'],
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				$dossierep['Reorientationep93']['Motifreorientep93']['name'],
				$dossierep['Reorientationep93']['Orientstruct']['Typeorient']['lib_type_orient'],
				$dossierep['Reorientationep93']['Orientstruct']['Structurereferente']['lib_struc'],
				@$dossierep['Reorientationep93']['Typeorient']['lib_type_orient'],
				@$dossierep['Reorientationep93']['Structurereferente']['lib_struc'],
// 				$form->input( "Decisionreorientationep93.{$i}.id", array( 'type' => 'hidden', 'value' => @$dossierep['Reorientationep93']['id'] ) ).
// 				$form->input( "Dossierep.{$i}.id", array( 'type' => 'hidden', 'value' => $dossierep['Dossierep']['id'] ) ).
// 				$form->input( "Reorientationep93.{$i}.dossierep_id", array( 'type' => 'hidden', 'value' => $dossierep['Dossierep']['id'] ) ).
				$form->input( "Reorientationep93.{$i}.id", array( 'type' => 'hidden'/*, 'value' => $dossierep['Reorientationep93']['id']*/ ) ).
				$form->input( "Reorientationep93.{$i}.dossierep_id", array( 'type' => 'hidden'/*, 'value' => $dossierep['Dossierep']['id']*/ ) ).
				$form->input( "Decisionreorientationep93.{$i}.id", array( 'type' => 'hidden'/*, 'value' => @$record['id']*/ ) ).

				$form->input( "Decisionreorientationep93.{$i}.etape", array( 'type' => 'hidden', 'value' => 'ep' ) ).
				$form->input( "Decisionreorientationep93.{$i}.reorientationep93_id", array( 'type' => 'hidden'/*, 'value' => @$dossierep['Reorientationep93']['id']*/ ) ).
				$form->input( "Decisionreorientationep93.{$i}.decision", array( 'label' => false, 'options' => @$options['Decisionreorientationep93']['decision'], 'empty' => true ) ),
				$form->input( "Decisionreorientationep93.{$i}.typeorient_id", array( 'label' => false, 'options' => @$options['Commissionep']['typeorient_id'], 'empty' => true ) ),
				$form->input( "Decisionreorientationep93.{$i}.structurereferente_id", array( 'label' => false, 'options' => @$options['Commissionep']['structurereferente_id'], 'empty' => true ) ),
			)
		);
	}
	echo '</tbody></table>';
// 	echo $form->submit( 'Enregistrer' );
// 	echo $form->end();

// 	debug( $commissionep );
// 	debug( $options );
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>
		dependantSelect( 'Decisionreorientationep93<?php echo $i?>StructurereferenteId', 'Decisionreorientationep93<?php echo $i?>TypeorientId' );
		try { $( 'Decisionreorientationep93<?php echo $i?>StructurereferenteId' ).onchange(); } catch(id) { }

		observeDisableFieldsOnValue(
			'Decisionreorientationep93<?php echo $i;?>Decision',
			[ 'Decisionreorientationep93<?php echo $i;?>TypeorientId', 'Decisionreorientationep93<?php echo $i;?>StructurereferenteId' ],
			'accepte',
			false
		);
		<?php endfor;?>
	});
</script>