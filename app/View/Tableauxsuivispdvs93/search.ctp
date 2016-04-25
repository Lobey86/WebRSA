<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}

	$searchFormId = Inflector::camelize( Inflector::underscore( Inflector::classify( $this->request->params['controller'] ) )."_{$this->request->params['action']}_form" );

	$tableau = null;
	$tableaux = array_keys( (array)$options['Tableausuivipdv93']['name'] );
	if( in_array( $this->request->params['action'], $tableaux ) ) {
		$tableau = $this->request->params['action'];
	}
	else {
		$pass = Hash::get( $this->request->params, 'pass.0' );
		if( in_array( $this->request->params['action'], $tableaux ) ) {
			$pass = $this->request->params['action'];
		}
	}

	echo $this->Default3->titleForLayout();

	$actions['/'.Inflector::camelize( $this->request->params['controller'] ).'/'.$this->request->params['action'].'/#toggleform'] =  array(
		'title' => 'Visibilité formulaire',
		'text' => 'Formulaire',
		'class' => 'search',
		'onclick' => "$( '{$searchFormId}' ).toggle(); return false;"
	);
	echo $this->Default3->actions( $actions );

	// 1. Formulaire de recherche, CG

	echo $this->Default3->DefaultForm->create( null, array( 'novalidate' => 'novalidate', 'id' => $searchFormId, 'class' => ( isset( $results ) ? 'folded' : 'unfolded' ) ) );

	echo $this->Default3->subform(
		array(
			'Search.annee' => array( 'empty' => ( empty( $tableau ) ? true : false ) )
		),
		array(
			'options' => $options
		)
	);
	if( $hasMode ) {
		echo $this->Default3->subform(
			array(
				'Search.mode' => array( 'empty' => false )
			),
			array(
				'options' => $options
			)
		);
	}
	else {
		echo $this->Default3->subform(
			array(
				'Search.mode' => array( 'type' => 'hidden' )
			)
		);
	}
	echo '<fieldset class="invisible" id="SearchStructurereferenteFieldsetPdv">';
	if( $hasCommunautessrs ) {
		echo $this->Default3->subform(
			array(
				'Search.communautesr_id' => array( 'empty' => true, 'type' => 'select' )
			),
			array(
				'options' => $options
			)
		);
	}
	echo $this->Default3->subform(
		array(
			'Search.structurereferente_id' => array( 'empty' => true, 'type' => 'select' ),
			'Search.referent_id' => array( 'empty' => true, 'type' => 'select' )
		),
		array(
			'options' => $options
		)
	);
	echo '</fieldset>';

	echo '<fieldset class="invisible" id="SearchStructurereferenteFieldsetMacro">';
	echo $this->SearchForm->dependantCheckboxes(
		'Search.structurereferente_id',
		array(
			'options' => $options['Search']['structurereferente_id'],
			'class' => 'divideInto3Collumn',
			'buttons' => true,
			'autoCheck' => true,
			'id' => 'SearchStructurereferenteIdMacro',
			'domain' => 'tableauxsuivispdvs93'
		)
	);
	echo '</fieldset>';

	// Formulaire de recherche seulement
	if( empty( $tableau ) ) {
		echo $this->Default3->subform(
			array(
				'Search.user_id' => array( 'empty' => true, 'type' => 'select' ),
				'Search.tableau' => array( 'empty' => true, 'type' => 'select' )
			),
			array(
				'options' => $options
			)
		);
	}
	echo $this->Default3->DefaultForm->buttons( array( 'Search' ) );
	echo $this->Default3->DefaultForm->end();

	echo $this->Observer->dependantSelect(
		array(
			'Search.structurereferente_id' => 'Search.referent_id'
		)
	);

	echo $this->Observer->disableFormOnSubmit( $searchFormId );

	echo $this->Observer->disableFieldsetOnValue(
		'Search.mode',
		'SearchStructurereferenteFieldsetMacro',
		'statistiques',
		false,
		true
	);
	echo $this->Observer->disableFieldsetOnValue(
		'Search.mode',
		'SearchStructurereferenteFieldsetPdv',
		'fse',
		false,
		true
	);
?>
<!--
<hr />
<?php
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
			'Search.communautesr_id' => array( 'empty' => true, 'type' => ( $hasCommunautessrs ? 'select' : 'hidden' ) ),
			'Search.structurereferente_id' => array( 'empty' => true, 'type' => ( $hasStructuresreferentes ? 'select' : 'hidden' ) ),
			'Search.referent_id' => array( 'empty' => true, 'type' => ( $hasReferents ? 'hidden' : 'select' ) ),
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
?>
-->