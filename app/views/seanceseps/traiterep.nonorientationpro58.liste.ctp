<?php
// 	echo $form->create( null, array( 'url' => Router::url( null, true ) ) );
	echo '<table><thead>
<tr>
<th>Dossier EP</th>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Date d\'orientation</th>
<th>Orientation actuelle</th>
<th colspan="3">Avis EPL</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		echo $xhtml->tableCells(
			array(
				$dossierep['Dossierep']['id'],
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Nonorientationpro58']['Orientstruct']['date_valid'] ),
				$dossierep['Nonorientationpro58']['Orientstruct']['Typeorient']['lib_type_orient'].' - '.
				$dossierep['Nonorientationpro58']['Orientstruct']['Structurereferente']['lib_struc'].' - '.
				implode( ' ', array( @$dossierep['Nonorientationpro58']['Orientstruct']['Referent']['qual'], @$dossierep['Nonorientationpro58']['Orientstruct']['Referent']['nom'], @$dossierep['Nonorientationpro58']['Orientstruct']['Referent']['prenom'] ) ),

				$form->input( "Nonorientationpro58.{$i}.id", array( 'type' => 'hidden', 'value' => $dossierep['Nonorientationpro58']['id'] ) ).
				$form->input( "Nonorientationpro58.{$i}.dossierep_id", array( 'type' => 'hidden', 'value' => $dossierep['Dossierep']['id'] ) ).
				$form->input( "Decisionnonorientationpro58.{$i}.id", array( 'type' => 'hidden', 'value' => @$this->data[$i]['id'] ) ).
				$form->input( "Decisionnonorientationpro58.{$i}.etape", array( 'type' => 'hidden', 'value' => 'ep' ) ).
				$form->input( "Decisionnonorientationpro58.{$i}.defautinsertionep66_id", array( 'type' => 'hidden', 'value' => @$dossierep['Nonorientationpro58']['id'] ) ).

				$form->input( "Decisionnonorientationpro58.{$i}.decision", array( 'type' => 'select', 'label' => false, 'empty' => true, 'options' => $options['Decisionnonorientationpro58']['decision'], 'value' => @$this->data[$i]['decision'] ) ),
				$form->input( "Decisionnonorientationpro58.{$i}.typeorient_id", array( 'type' => 'select', 'label' => false, 'options' => @$options['Seanceep']['typeorient_id'], 'empty' => true, 'value' => @$this->data[$i]['typeorient_id'] ) ),
				$form->input( "Decisionnonorientationpro58.{$i}.structurereferente_id", array( 'label' => false, 'options' => @$options['Seanceep']['structurereferente_id'], 'empty' => true, 'type' => 'select', 'value' => @$this->data[$i]['structurereferente_id'] ) )
			)
		);
	}
	echo '</tbody></table>';
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>
 		dependantSelect( 'Decisionnonorientationpro58<?php echo $i?>StructurereferenteId', 'Decisionnonorientationpro58<?php echo $i?>TypeorientId' );
 		try { $( 'Decisionnonorientationpro58<?php echo $i?>StructurereferenteId' ).onchange(); } catch(id) { }

		observeDisableFieldsOnValue(
			'Decisionnonorientationpro58<?php echo $i;?>Decision',
			[
				'Decisionnonorientationpro58<?php echo $i;?>TypeorientId',
				'Decisionnonorientationpro58<?php echo $i;?>StructurereferenteId'
			],
			'reorientation',
			false
		);
		<?php endfor;?>
	});
</script>