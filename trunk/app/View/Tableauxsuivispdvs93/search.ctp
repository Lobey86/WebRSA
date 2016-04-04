<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}

	if( $this->action == 'view' ) {
		$name = $tableausuivipdv93['Tableausuivipdv93']['name'];
		$tableausuivipdv93['Tableausuivipdv93']['name'] = strtoupper( preg_replace( '/^tableau1{0,1}/', '', $tableausuivipdv93['Tableausuivipdv93']['name'] ) );
		echo $this->Default3->titleForLayout( $tableausuivipdv93 );
		$tableausuivipdv93['Tableausuivipdv93']['name'] = $name;
	}
	else {
		echo $this->Default3->titleForLayout();
	}
	$domain = Inflector::tableize( Inflector::classify( $this->request->params['controller'] ) );

	if( $this->action == 'view' ) {
		$created = $this->Locale->date( __( 'Locale->datetime' ), $tableausuivipdv93['Tableausuivipdv93']['created'] );
		$modified = $this->Locale->date( __( 'Locale->datetime' ), $tableausuivipdv93['Tableausuivipdv93']['modified'] );

		if( $created == $modified ) {
			$h2 = "Photographie du {$created}";
		}
		else {
			$h2 = "Photographie entre le {$created} et le {$modified}";
		}

		echo $this->Html->tag( 'h2', $h2 );
	}

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
	}

	if( in_array( $this->request->params['action'], array( 'historiser', 'view' ) ) ) {
		$tableau = Hash::get( $tableausuivipdv93, 'Tableausuivipdv93.name' );
		if( empty( $tableau ) ) {
			$tableau = $this->request->pass[0];
		}
	}
	else {
		$tableau = $this->request->params['action'];
	}

	$params_rdv_structurereferente = array(
		'type' => (
			in_array( $tableau, array( 'tableau1b3', 'index', 'tableaud1', 'tableaud2' ) )
			? 'hidden'
			: 'checkbox'
		)
	);

	$params_dsps_maj_dans_annee = array(
		'type' => (
			in_array( $tableau, array( 'tableau1b3' ) )
			? 'checkbox'
			: 'hidden'
		)
	);

	$params_soumis_dd_dans_annee = array(
		'type' => (
			in_array( $tableau, array( 'tableaud1', 'tableaud2' ) )
			? 'checkbox'
			: 'hidden'
		)
	);

	$params_typethematiquefp93_id = array(
		'type' => (
			in_array( $tableau, array( 'tableau1b4', 'tableau1b5' ) )
			? 'select'
			: 'hidden'
		),
		'empty' => true
	);

	echo $this->Default3->form(
		array(
			'Search.annee' => array( 'empty' => ( $tableau == 'index' ? true : false ) ),
			'Search.structurereferente_id' => array( 'empty' => true, 'type' => ( $hasStructuresreferentes ? 'select' : 'hidden' ) ),
			'Search.referent_id' => array( 'empty' => true, 'type' => ( $userIsCi ? 'hidden' : 'select' ) ),
			'Search.user_id' => array( 'empty' => true, 'type' => ( $tableau == 'index' ? 'select' : 'hidden' ) ),
			'Search.tableau' => array( 'empty' => true, 'type' => ( $tableau == 'index' ? 'select' : 'hidden' ) ),
			'Search.typethematiquefp93_id' => $params_typethematiquefp93_id,
			'Search.rdv_structurereferente' => $params_rdv_structurereferente,
			'Search.dsps_maj_dans_annee' => $params_dsps_maj_dans_annee,
			'Search.soumis_dd_dans_annee' => $params_soumis_dd_dans_annee,
		),
		array(
			'options' => $options,
			'buttons' => ( in_array( $this->action, array( 'view', 'historiser' ) ) ? false : array( 'Search' ) )
		)
	);

	// FIXME: si on fixe le CG!
	echo $this->Observer->dependantSelect(
		array(
			'Search.structurereferente_id' => 'Search.referent_id'
		)
	);

	echo $this->Observer->disableFormOnSubmit( Inflector::camelize( Inflector::underscore( Inflector::classify( $this->request->params['controller'] ) )."_{$this->request->params['action']}_form" ) );
?>