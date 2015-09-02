<?php
	$departement = Configure::read( 'Cg.departement' );
	$controller = $this->params->controller;
	$action = $this->action;
	$formId = ucfirst($controller) . ucfirst($action) . 'Form';
	$availableDomains = MultiDomainsTranslator::urlDomains();
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
	
	echo $this->Default3->titleForLayout( array(), array( 'domain' => $domain ) );
	
	$dates = array(
		'Dossier' => array('dtdemrsa' => $dateRule),
		'Personne' => array('dtnai' => $dateRule),
		'Dossierpcg66' => array('datereceptionpdo' => $dateRule)
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
				'class' => $action . 'Form display_formulaire',
				'domain' => $domain
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
	
	echo '<fieldset><legend>' . __m( 'Dossierpcg66.'.$action ) . '</legend>'
		. $this->SearchForm->dateRange( 'Search.Dossierpcg66.datereceptionpdo', $paramDate )
		. $this->Default3->subform(
			array(
				'Search.Originepdo.libelle',
				'Search.Dossierpcg66.serviceinstructeur_id',
				'Search.Typepdo.libelle',
				'Search.Dossierpcg66.orgpayeur',
			),
			array( 'options' => array( 'Search' => $options ), 'domain' => $domain )
		)
		. '</fieldset>'
	;
	
	echo $this->Allocataires->blocReferentparcours($paramAllocataire);
	
	echo $this->Allocataires->blocPagination($paramAllocataire);

	echo $this->Xform->end( 'Search' );
	
	echo $this->Search->observeDisableFormOnSubmit( $formId );

	// 2. Formulaire de traitement des résultats de la recherche
	if( isset( $results ) ) {
		echo $this->Xform->create( null, 
			array( 
				'id' => 'Cohorte'.$formId, 
				'url' => Router::url( array( 'controller' => $controller, 'action' => $action ), true )
			)
		);
		

		echo $this->Default3->configuredCohorte(
			$results,
			array(
				'format' => SearchProgressivePagination::format( !Hash::get( $this->request->data, 'Search.Pagination.nombre_total' ) ),
				'options' => $options,
				'extraHiddenFields' => $extraHiddenFields,
				'entityErrorPrefix' => 'Cohorte',
				'cohorteFields' => $cohorteFields
			)
		);
		
		echo $this->Xform->end( 'Save' );
	}
	else{
		$results = array();
	}
?>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		for (var i=0; i<<?php echo count($results);?>; i++) {
			dependantSelect( 'Cohorte'+i+'Dossierpcg66UserId', 'Cohorte'+i+'Dossierpcg66Poledossierpcg66Id' );

			observeDisableFieldsOnCheckbox(
				'Cohorte'+i+'Dossierpcg66Atraiter',
				[
					'Cohorte'+i+'Dossierpcg66Poledossierpcg66Id',
					'Cohorte'+i+'Dossierpcg66UserId',
					'Cohorte'+i+'Dossierpcg66DateaffectationDay',
					'Cohorte'+i+'Dossierpcg66DateaffectationMonth',
					'Cohorte'+i+'Dossierpcg66DateaffectationYear'
				],
				false
			);
		}
	});
</script>