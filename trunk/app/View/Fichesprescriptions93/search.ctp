<?php
	echo $this->Default3->titleForLayout();

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ), array( 'inline' => false ) );
	}

	echo $this->Default3->actions(
		array(
			'/Fichesprescriptions93/search/#toggleform' => array(
				'onclick' => '$(\'Fichesprescriptions93SearchForm\').toggle(); return false;',
				'class' => 'search'
			),
		)
	);

	echo $this->Xform->create( null, array( 'id' => 'Fichesprescriptions93SearchForm', 'class' => ( !empty( $this->request->params['named'] ) ? 'folded' : 'unfolded' ) ) );

	echo $this->Allocataires->blocDossier( array( 'options' => $options ) );
	echo $this->Allocataires->blocAdresse( array( 'options' => $options ) );
	echo $this->Allocataires->blocAllocataire( array( 'options' => $options ) );

	// Début spécificités fiche de prescription
	echo $this->Xform->input( 'Search.Ficheprescription93.exists', array( 'type' => 'select', 'options' => (array)Hash::get( $options, 'Ficheprescription93.exists' ), 'domain' => 'fichesprescriptions93', 'empty' => true ) );
	echo '<fieldset id="SpecificitesFichesprescriptions93"><legend>'.__d( 'fichesprescriptions93', 'Search.Ficheprescription93' ).'</legend>';
	echo $this->Xform->input( 'Search.Ficheprescription93.numconvention', array( 'domain' => 'fichesprescriptions93' ) );
	echo $this->Xform->input( 'Search.Ficheprescription93.typethematiquefp93_id', array( 'type' => 'select', 'options' => (array)Hash::get( $options, 'Ficheprescription93.typethematiquefp93_id' ), 'empty' => true, 'domain' => 'fichesprescriptions93' ) );
	echo $this->Xform->input( 'Search.Ficheprescription93.thematiquefp93_id', array( 'type' => 'select', 'options' => array(), 'empty' => true, 'domain' => 'fichesprescriptions93' ) );
	echo $this->Xform->input( 'Search.Ficheprescription93.categoriefp93_id', array( 'type' => 'select', 'options' => array(), 'empty' => true, 'domain' => 'fichesprescriptions93' ) );
	echo $this->Xform->input( 'Search.Ficheprescription93.filierefp93_id', array( 'type' => 'select', 'options' => array(), 'empty' => true, 'domain' => 'fichesprescriptions93' ) );
	echo $this->Xform->input( 'Search.Ficheprescription93.prestatairefp93_id', array( 'type' => 'select', 'options' => array(), 'empty' => true, 'domain' => 'fichesprescriptions93' ) );
	echo $this->Xform->input( 'Search.Ficheprescription93.actionfp93_id', array( 'type' => 'select', 'options' => array(), 'empty' => true, 'domain' => 'fichesprescriptions93' ) );
	echo $this->Xform->input( 'Search.Ficheprescription93.actionfp93', array( 'type' => 'text', 'domain' => 'fichesprescriptions93' ) );

	// TODO: fieldset
	echo '<fieldset><legend>'.__d( 'fichesprescriptions93', 'Search.Ficheprescription93.Referent' ).'</legend>';
	echo $this->Xform->input( 'Search.Ficheprescription93.structurereferente_id', array( 'type' => 'select', 'empty' => true, 'options' => $options['PersonneReferent']['structurereferente_id'], 'domain' => 'fichesprescriptions93' ) );
	echo $this->Xform->input( 'Search.Ficheprescription93.referent_id', array( 'type' => 'select', 'empty' => true, 'options' => $options['PersonneReferent']['referent_id'], 'domain' => 'fichesprescriptions93' ) );
	echo '</fieldset>';

	echo $this->SearchForm->dateRange( 'Search.Ficheprescription93.created', array( 'domain' => 'fichesprescriptions93' ) );
	echo $this->SearchForm->dateRange( 'Search.Ficheprescription93.date_signature', array( 'domain' => 'fichesprescriptions93' ) );
	echo $this->SearchForm->dateRange( 'Search.Ficheprescription93.rdvprestataire_date', array( 'domain' => 'fichesprescriptions93' ) );
	echo $this->SearchForm->dateRange( 'Search.Ficheprescription93.date_transmission', array( 'domain' => 'fichesprescriptions93' ) );
	echo $this->SearchForm->dateRange( 'Search.Ficheprescription93.date_retour', array( 'domain' => 'fichesprescriptions93' ) );

	echo $this->Xform->input( 'Search.Ficheprescription93.statut', array( 'type' => 'select', 'options' => (array)Hash::get( $options, 'Ficheprescription93.statut' ), 'empty' => true, 'domain' => 'fichesprescriptions93' ) );

	echo $this->Xform->input( 'Search.Ficheprescription93.has_date_retour', array( 'type' => 'select', 'options' => (array)Hash::get( $options, 'Ficheprescription93.exists' ), 'domain' => 'fichesprescriptions93', 'empty' => true ) );

	echo $this->SearchForm->dateRange( 'Search.Ficheprescription93.df_action', array( 'domain' => 'fichesprescriptions93' ) );

	$paths = array(
		'Ficheprescription93.benef_retour_presente',
		'Ficheprescription93.personne_recue',
		'Ficheprescription93.personne_retenue',
		'Ficheprescription93.personne_a_integre',
	);
	foreach( $paths as $path ) {
		echo $this->Xform->input( "Search.{$path}", array( 'type' => 'select', 'options' => (array)Hash::get( $options, $path ), 'domain' => 'fichesprescriptions93', 'empty' => true ) );
	}

	echo $this->Xform->input( 'Search.Ficheprescription93.has_date_bilan_mi_parcours', array( 'type' => 'select', 'options' => (array)Hash::get( $options, 'Ficheprescription93.exists' ), 'domain' => 'fichesprescriptions93', 'empty' => true ) );
	echo $this->Xform->input( 'Search.Ficheprescription93.has_date_bilan_final', array( 'type' => 'select', 'options' => (array)Hash::get( $options, 'Ficheprescription93.exists' ), 'domain' => 'fichesprescriptions93', 'empty' => true ) );

	echo '</fieldset>';
	// Fin spécificités fiche de prescription

	echo $this->Allocataires->blocReferentparcours( array( 'options' => $options ) );
	echo $this->Allocataires->blocPagination( array( 'options' => $options ) );
	echo $this->Allocataires->blocScript( array( 'options' => $options ) );

	echo $this->Xform->end( 'Search' );

	if( isset( $results ) ) {
		echo $this->Html->tag( 'h2', 'Résultats de la recherche' );

		echo $this->Default3->configuredindex(
			$results,
			array(
				'options' => $options
			)
		);

		echo $this->element( 'search_footer', array( 'modelName' => $modelName ) );
	}

	echo $this->Observer->disableFieldsetOnValue(
		'Search.Ficheprescription93.exists',
		'SpecificitesFichesprescriptions93',
		array( null, '1' ),
		true,
		true
	);

	// Catalogue PDI
	echo $this->Observer->disableFieldsOnValue(
		'Search.Ficheprescription93.typethematiquefp93_id',
		array(
			'Search.Ficheprescription93.prestatairehorspdifp93_id',
			'Search.Ficheprescription93.actionfp93',
		),
		array( null, '', 'pdi' ),
		true,
		true
	);

	// Catalogue Hors PDI
	echo $this->Observer->disableFieldsOnValue(
		'Search.Ficheprescription93.typethematiquefp93_id',
		array(
			'Search.Ficheprescription93.numconvention',
			'Search.Ficheprescription93.prestatairefp93_id',
			'Search.Ficheprescription93.actionfp93_id',
		),
		array( 'horspdi' ),
		true,
		true
	);

	echo $this->Ajax2->observe(
		array(
			'Search.Ficheprescription93.numconvention' => array( 'event' => 'keyup' ),
			'Search.Ficheprescription93.typethematiquefp93_id',
			'Search.Ficheprescription93.thematiquefp93_id',
			'Search.Ficheprescription93.categoriefp93_id',
			'Search.Ficheprescription93.filierefp93_id',
			'Search.Ficheprescription93.prestatairefp93_id',
			'Search.Ficheprescription93.actionfp93_id',
		),
		array(
			'url' => array( 'action' => 'ajax_action' ),
			'prefix' => 'Search',
			'onload' => !empty( $this->request->data )
		)
	);
debug( $results );
	echo $this->Observer->dependantSelect( array( 'Search.Ficheprescription93.structurereferente_id' => 'Search.Ficheprescription93.referent_id' ) );
?>