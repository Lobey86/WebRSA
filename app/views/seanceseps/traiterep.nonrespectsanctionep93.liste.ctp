<h2>FIXME</h2>

<!--<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>
		dependantSelect( 'Nvsrepreorientsr93<?php echo $i?>StructurereferenteId', 'Nvsrepreorientsr93<?php echo $i?>TypeorientId' );
		try { $( 'Nvsrepreorientsr93<?php echo $i?>StructurereferenteId' ).onchange(); } catch(id) { }

		observeDisableFieldsOnValue(
			'Nvsrepreorientsr93<?php echo $i;?>Decision',
			[ 'Nvsrepreorientsr93<?php echo $i;?>TypeorientId', 'Nvsrepreorientsr93<?php echo $i;?>StructurereferenteId' ],
			'accepte',
			false
		);
		<?php endfor;?>
	});
</script>-->

<?php
	echo $form->create( null, array( 'url' => Router::url( null, true ) ) );
	echo '<table><thead>
<tr>
<th>Dossier EP</th>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Origine du dossier</th>
<th>Date d\'orientation</th>
<!--<th>Orientation actuelle</th>
<th>Structure référente actuelle</th>
<th>Orientation préconisée</th>
<th>Structure référente préconisée</th>
<th>Avis EP</th>
<th>Orientation choisie</th>
<th>Structure référente choisie</th>-->
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		// FIXME: ailleurs
		$origine = 'Iconnue';
		if( !empty( $dossierep['Nonrespectsanctionep93']['orientstruct_id'] ) ) {
			$origine = 'Non contractualisation';
		}

		echo $xhtml->tableCells(
			array(
				$dossierep['Dossierep']['id'],
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				$origine,
				$locale->date( __( 'Locale->date', true ), $dossierep['Nonrespectsanctionep93']['Orientstruct']['date_valid'] ),
				/*$dossierep['Saisineepreorientsr93']['Motifreorient']['name'],
				$dossierep['Saisineepreorientsr93']['Orientstruct']['Typeorient']['lib_type_orient'],
				$dossierep['Saisineepreorientsr93']['Orientstruct']['Structurereferente']['lib_struc'],
				@$dossierep['Saisineepreorientsr93']['Typeorient']['lib_type_orient'],
				@$dossierep['Saisineepreorientsr93']['Structurereferente']['lib_struc'],
// 				$form->input( "Nvsrepreorientsr93.{$i}.id", array( 'type' => 'hidden', 'value' => @$dossierep['Saisineepreorientsr93']['id'] ) ).
				$form->input( "Dossierep.{$i}.id", array( 'type' => 'hidden', 'value' => $dossierep['Dossierep']['id'] ) ).
				$form->input( "Nvsrepreorientsr93.{$i}.etape", array( 'type' => 'hidden', 'value' => 'cg' ) ).
				$form->input( "Nvsrepreorientsr93.{$i}.saisineepreorientsr93_id", array( 'type' => 'hidden', 'value' => @$dossierep['Saisineepreorientsr93']['id'] ) ).
				$form->input( "Nvsrepreorientsr93.{$i}.decision", array( 'label' => false, 'options' => @$options['Nvsrepreorientsr93']['decision'], 'empty' => true ) ),
				$form->input( "Nvsrepreorientsr93.{$i}.typeorient_id", array( 'label' => false, 'options' => @$options['Seanceep']['typeorient_id'], 'empty' => true ) ),
				$form->input( "Nvsrepreorientsr93.{$i}.structurereferente_id", array( 'label' => false, 'options' => @$options['Seanceep']['structurereferente_id'], 'empty' => true ) ),*/
			)
		);
	}
	echo '</tbody></table>';
	echo $form->submit( 'Enregistrer' );
	echo $form->end();

// 	debug( $dossiers );
// 	debug( $seanceep );
// 	debug( $options );
?>
