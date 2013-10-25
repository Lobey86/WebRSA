<?php
	echo $this->Default3->titleForLayout();

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	$searchFormOptions = array( 'domain' => 'search_plugin' );

	echo $this->Default3->actions(
		array(
			'/Cohortesd2pdvs93/index/#toggleform' => array(
				'onclick' => '$(\'Cohortesd2pdvs93IndexForm\').toggle(); return false;'
			),
		)
	);

	if( isset( $this->request->data['Search'] ) && !empty( $this->request->params['named'] ) ) {
		$out = "document.observe( 'dom:loaded', function() { \$('Cohortesd2pdvs93IndexForm').hide(); } );";
		echo $this->Html->scriptBlock( $out );
	}

	echo $this->Xform->create( null, array( 'id' => 'Cohortesd2pdvs93IndexForm' ) );

	// Filtres concernant le dossier
	echo '<fieldset>';
	echo sprintf( '<legend>%s</legend>', __d( 'cohortesd2pdvs93', 'Search.Dossier' ) );
	echo $this->Xform->input( 'Search.Dossier.numdemrsa', array( 'domain' => 'cohortesd2pdvs93' ) );
	echo $this->Xform->input( 'Search.Dossier.matricule', array( 'domain' => 'cohortesd2pdvs93' ) );
//	echo $this->SearchForm->dependantCheckboxes( 'Search.Situationdossierrsa.etatdosrsa', Hash::get( $options, 'Situationdossierrsa.etatdosrsa' ), $searchFormOptions );
//	echo $this->SearchForm->dependantCheckboxes( 'Search.Detailcalculdroitrsa.natpf', Hash::get( $options, 'Detailcalculdroitrsa.natpf' ), $searchFormOptions );
//	echo $this->SearchForm->dependantDateRange( 'Search.Dossier.dtdemrsa', $searchFormOptions );
	echo '</fieldset>';

	// Filtres concernant l'allocataire
	echo '<fieldset>';
	echo sprintf( '<legend>%s</legend>', __d( 'cohortesd2pdvs93', 'Search.Personne' ) );
	echo $this->Xform->input( 'Search.Dossier.dernier', array( 'type' => 'checkbox', 'domain' => 'cohortesd2pdvs93' ) );
	echo $this->Xform->input( 'Search.Personne.nom', array( 'domain' => 'cohortesd2pdvs93' ) );
	echo $this->Xform->input( 'Search.Personne.prenom', array( 'domain' => 'cohortesd2pdvs93' ) );
//	echo $this->SearchForm->dependantDateRange( 'Search.Personne.dtnai', $searchFormOptions );
	echo '</fieldset>';

	// Filtres concernant l'accompagnement
	echo '<fieldset>';
	echo sprintf( '<legend>%s</legend>', __d( 'cohortesd2pdvs93', 'Search.Questionnaired2pdv93' ) );
	echo $this->Xform->input( 'Search.Questionnaired1pdv93.annee', array( 'options' => $options['Questionnaired1']['annee'], 'domain' => 'cohortesd2pdvs93' ) );
	echo $this->Xform->input( 'Search.Rendezvous.structurereferente_id', array( 'options' => $options['Rendezvous']['structurereferente_id'], 'empty' => true, 'domain' => 'cohortesd2pdvs93' ) );
	echo $this->Xform->input( 'Search.Questionnaired2pdv93.exists', array( 'type' => 'checkbox', 'domain' => 'cohortesd2pdvs93' ) );
	echo '</fieldset>';

	echo $this->Xform->end( 'Search' );


	if( isset( $results ) ) {
		$this->Default3->DefaultPaginator->options(
			array( 'url' => Hash::flatten( (array)$this->request->data, '__' ) )
		);

		echo $this->Default3->index(
			$results,
			array(
				'Personne.nir',
				'Personne.nom',
				'Personne.prenom',
				'Personne.numfixe',
				'Personne.numport',
				'Rendezvous.daterdv',
				'Structurereferente.lib_struc',
				'Questionnaired2pdv93.created' => array( 'type' => 'date' ),
				// 'Dossier.locked' => array( 'type' => 'boolean', 'sort' => false ),
				'/Questionnairesd2pdvs93/index/#Personne.id#' => array( 'class' => 'external' ),
			),
			array(
				'options' => $options
			)
		);
	}
?>