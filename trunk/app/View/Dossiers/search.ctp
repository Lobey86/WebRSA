<?php
	$departement = (int)Configure::read( 'Cg.departement' );

	$actions = array();
	if( $departement == 66 ) {
		if( $this->Permissions->check( 'ajoutdossierscomplets', 'add' ) ) {
			$actions['/Ajoutdossierscomplets/add'] = array( 'class' => 'add', 'domain' => 'dossiers' );
		}
	}
	else {
		if( $this->Permissions->check( 'ajoutdossiers', 'wizard' ) ) {
			$actions['/Ajoutdossiers/wizard'] = array( 'class' => 'add', 'domain' => 'dossiers' );
		}
	}

	if( $this->Permissions->check( 'dossierssimplifies', 'add' ) ) {
		if( $departement != 58 ) {
			$actions['/Dossierssimplifies/add'] = array( 'class' => 'add', 'domain' => 'dossiers' );
		}
	}
?>

<?php $this->start( 'custom_search_filters' );?>
<fieldset>
	<legend>Recherche par parcours de l'allocataire</legend>
	<?php
		echo $this->Xform->input( 'Search.Dsp.natlog', array( 'label' => 'Conditions de logement', 'type' => 'select', 'empty' => true, 'options' => $options['Dsp']['natlog'] ) );
		if( $departement == 66 ) {
			echo $this->Xform->input( 'Search.Personne.has_prestation', array( 'label' => 'Rôle de la personne ?', 'type' => 'select', 'options' => $options['Prestation']['exists'], 'empty' => true ) );
		}
		if( $departement == 58 ) {
			echo $this->Xform->input( 'Search.Activite.act', array( 'label' => 'Code activité', 'type' => 'select', 'empty' => true, 'options' => $options['Activite']['act'] ) );
			echo $this->Form->input( 'Search.Propoorientationcov58.referentorientant_id', array( 'label' => 'Travailleur social chargé de l\'évaluation', 'type' => 'select', 'options' => $options['Propoorientationcov58']['referentorientant_id'], 'empty' => true ) );
			echo $this->Form->input( 'Search.Personne.etat_dossier_orientation', array( 'label' => __d( 'personne', 'Personne.etat_dossier_orientation' ), 'type' => 'select', 'options' => $options['Personne']['etat_dossier_orientation'], 'empty' => true ) );
			echo $this->Form->input( 'Search.Personne.has_dsp', array( 'label' => 'Possède une DSP ?', 'type' => 'select', 'options' => $options['Personne']['has_dsp'], 'empty' => true ) );
		}
		else if( $departement != 93 ) {
			echo $this->Form->input( 'Search.Personne.has_orientstruct', array( 'label' => 'Possède une orientation ? ', 'type' => 'select', 'options' => $options['Personne']['has_orientstruct'], 'empty' => true ) );
		}
		if( $departement == 66 ) {
			echo $this->Form->input( 'Search.Personne.has_cui', array( 'label' => 'Possède un CUI ? ', 'type' => 'select', 'options' => $options['Personne']['has_cui'], 'empty' => true ) );
		}
		echo $this->Form->input( 'Search.Personne.has_contratinsertion', array( 'label' => 'Possède un CER ? ', 'type' => 'select', 'options' => $options['Personne']['has_contratinsertion'], 'empty' => true ) );
	?>
</fieldset>
<?php $this->end();?>

<?php
	echo $this->element(
		'ConfigurableQuery/search',
		array(
			'actions' => $actions,
			'custom' => $this->fetch( 'custom_search_filters' ),
			'exportcsv' => array( 'action' => 'exportcsv' )
		)
	);
?>