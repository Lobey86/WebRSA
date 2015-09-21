<?php
	$departement = (int)Configure::read( 'Cg.departement' );
	$controller = $this->params->controller;
	$action = $this->action;
	$formId = ucfirst($controller) . ucfirst($action) . 'Form';
	$availableDomains = MultiDomainsTranslator::urlDomains();
	// FIXME: a-t'on encore besoin de $domain ? Corriger les autres recherches (titleForLayout et actions)
	$domain = isset( $availableDomains[0] ) ? $availableDomains[0] : $controller;
	$paramDate = array(
		'domain' => $domain,
		'minYear_from' => '2009',
		'maxYear_from' => date( 'Y' ) + 1,
		'minYear_to' => '2009',
		'maxYear_to' => date( 'Y' ) + 4
	);
	$paramAllocataire = array(
		'options' => $options,
		'prefix' => 'Search',
	);
	$dateRule = array(
		'date' => array(
			'rule' => array('date'),
			'message' => null,
			'required' => null,
			'allowEmpty' => true,
			'on' => null
		)
	);

	echo $this->Default3->titleForLayout();

	$dates = array(
		'Dossier' => array('dtdemrsa' => $dateRule),
		'Personne' => array('dtnai' => $dateRule),
		'Orientstruct' => array('date_valid' => $dateRule)
	);
	echo $this->FormValidator->generateJavascript($dates, false);

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ), array( 'inline' => false ) );
	}

	echo $this->Default3->actions(
		array(
			'/' . $controller . '/' . $action . '/#toggleform' => array(
				'onclick' => '$(\'' . $formId . '\').toggle(); return false;',
				'class' => $action . 'Form'
			),
		)
	);

	// 1. Moteur de recherche
	echo $this->Xform->create( null,
		array(
			'id' => $formId,
			'class' => ( ( isset( $results ) ) ? 'folded' : 'unfolded' ),
			'url' => Router::url( array( 'controller' => $controller, 'action' => $action ), true )
		)
	);

	echo $this->Allocataires->blocDossier($paramAllocataire);

	echo $this->Allocataires->blocAdresse($paramAllocataire);

	echo $this->Allocataires->blocAllocataire($paramAllocataire);

	echo $this->Html->tag(
		'fieldset',
		$this->Html->tag( 'legend', 'Recherche par parcours de l\'allocataire' )
		.$this->Default3->subform(
			array(
				'Search.Historiqueetatpe.identifiantpe' => array( /*'maxlength' => 11*/ ),
				'Search.Personne.has_contratinsertion' => array( 'empty' => true ),
				'Search.Personne.has_personne_referent' => array( 'empty' => true ),
				'Search.Personne.is_inscritpe' => array( 'empty' => true )
			),
			array( 'options' => array( 'Search' => $options ) )
		)
		.(
			( $departement !== 58 )
			? ''
			: $this->Default3->subform(
				array(
					'Search.Activite.act' => array( 'empty' => true )
				),
				array( 'options' => array( 'Search' => $options ) )
			)
		)
	);

	echo '<fieldset><legend>' . __m( 'Orientstruct.search' ) . '</legend>'
		. $this->Default3->subform(
			array(
				'Search.Orientstruct.derniere' => array( 'type' => 'checkbox' )
			),
			array( 'options' => array( 'Search' => $options ) )
		)
		. $this->SearchForm->dateRange( 'Search.Orientstruct.date_valid', $paramDate )
	;

	if ($departement === 66) {
		echo '<fieldset><legend>' . __m( 'Orientstruct.orientepar' ) . '</legend>'
			. $this->Default3->subform(
				array(
					'Search.Orientstruct.structureorientante_id' => array('empty' => true, 'required' => false),
					'Search.Orientstruct.referentorientant_id' => array('empty' => true, 'required' => false),
				),
				array( 'options' => array( 'Search' => $options ) )
			)
			. '</fieldset>'
		;
	}

	if ($departement === 93) {
		echo $this->Default3->subform(
			array(
				'Search.Orientstruct.origine' => array('empty' => true),
			),
			array( 'options' => array( 'Search' => $options ) )
		);
	}

	echo $this->Default3->subform(
			array(
				'Search.Orientstruct.typeorient_id' => array('empty' => true, 'required' => false),
				'Search.Orientstruct.structurereferente_id' => array('empty' => true, 'required' => false),
				'Search.Orientstruct.statut_orient' => array('empty' => true, 'required' => false)
			),
			array( 'options' => array( 'Search' => $options ) )
		)
		. '</fieldset>'
	;

	echo $this->Allocataires->blocReferentparcours($paramAllocataire);

	echo $this->Allocataires->blocPagination($paramAllocataire);

	echo $this->Xform->end( 'Search' );

	echo $this->Search->observeDisableFormOnSubmit( $formId );

	// 2. Formulaire de traitement des résultats de la recherche
	if( isset( $results ) ) {
		echo $this->Html->tag( 'h2', 'Résultats de la recherche', array( 'class' => 'noprint' ) );

		echo $this->Default3->configuredIndex(
			$results,
			array(
				'format' => SearchProgressivePagination::format( !Hash::get( $this->request->data, 'Search.Pagination.nombre_total' ) ),
				'options' => $options
			)
		);

		echo $this->element( 'search_footer' );
	}

	if( $departement === 66 ) {
		echo $this->Observer->dependantSelect(
			array(
				'Search.Orientstruct.structureorientante_id' => 'Search.Orientstruct.referentorientant_id'
			)
		);
	}

	echo $this->Observer->dependantSelect(
		array(
			'Search.Orientstruct.typeorient_id' => 'Search.Orientstruct.structurereferente_id'
		)
	);
?>