<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
	}

	$paramDate = array(
		'minYear_from' => '2009',
		'maxYear_from' => date( 'Y' ) + 1,
		'minYear_to' => '2009',
		'maxYear_to' => date( 'Y' ) + 5
	);


	//$this->pageTitle = 'Recherche par PDOs (nouveau)';
	echo $this->Default3->titleForLayout();

    echo '<ul class="actionMenu"><li>'.$this->Xhtml->link(
        $this->Xhtml->image(
            'icons/application_form_magnify.png',
            array( 'alt' => '' )
        ).' Formulaire',
        '#',
        array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'PropospdosSearchForm' ).toggle(); return false;" )
    ).'</li></ul>';
?>
<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}

	echo $this->Xform->create( null, array( 'type' => 'post', 'action' => $this->action, 'id' => 'PropospdosSearchForm', 'class' => ( isset( $results ) ? 'folded' : 'unfolded' ) ) );
	echo $this->Allocataires->blocAllocataire( array( 'prefix' => 'Search', 'options' => $options ) );
	echo $this->Allocataires->blocAdresse( array( 'prefix' => 'Search', 'options' => $options ) );
	echo $this->Allocataires->blocDossier( array( 'prefix' => 'Search', 'options' => $options ) );
?>
<fieldset>
	<legend><?php echo __m( 'Search.Propopdo' );?></legend>
	<?php
		echo $this->SearchForm->dateRange( 'Search.Propopdo.datereceptionpdo', $paramDate + array( 'legend' => __m( 'Search.Propopdo.datereceptionpdo' ) ) );
		echo $this->SearchForm->dateRange( 'Search.Decisionpropopdo.datedecisionpdo', $paramDate + array( 'legend' => __m( 'Search.Decisionpropopdo.datedecision' ) ) );

		echo $this->Xform->input( 'Search.Propopdo.traitementencours', array( 'label' => 'Uniquement les PDOs possédant un traitement avec une date d\'échéance', 'type' => 'checkbox' ) );

		echo $this->Default3->subform(
			array(
				'Search.Propopdo.originepdo_id' => array( 'empty' => true ),
				'Search.Propopdo.etatdossierpdo' => array( 'empty' => true ),
				'Search.Decisionpropopdo.decisionpdo_id' => array( 'empty' => true ),
				'Search.Propopdo.user_id' => array( 'empty' => true ),
				'Search.Propopdo.motifpdo' => array( 'empty' => true )
			),
			array( 'options' => array( 'Search' => $options ) )
		);
	?>
</fieldset>
<?php
	echo $this->Allocataires->blocReferentparcours( array( 'prefix' => 'Search', 'options' => $options ) );
	echo $this->Allocataires->blocPagination( array( 'prefix' => 'Search', 'options' => $options ) );
	echo $this->Allocataires->blocScript( array( 'prefix' => 'Search', 'options' => $options ) );
?>
<div class="submit noprint">
	<?php echo $this->Xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
	<?php echo $this->Xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
</div>

<?php
	echo $this->Xform->end();
?>

<script type="text/javascript">
	document.observe( "dom:loaded", function() {
		// FIXME: où ça ?
		observeDisableFieldsetOnCheckbox( 'SearchPropopdoDateentretien', $( 'SearchPropopdoDateentretienFromDay' ).up( 'fieldset' ), false );
		dependantSelect( 'SearchPropopdoReferentId', 'SearchPropopdoStructurereferenteId' );
	} );
</script>
<?php
	if( isset( $results ) ) {
		echo $this->Html->tag( 'h2', 'Résultats de la recherche', array( 'class' => 'noprint' ) );

		echo $this->Default3->configuredindex(
			$results,
			array(
				'options' => $options
			)
		);

		echo $this->element( 'search_footer' );
	}
?>