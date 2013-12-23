<h1><?php echo $this->pageTitle = __d( 'sanctionep58', "{$this->name}::{$this->action}" );?></h1>

<?php
    echo '<ul class="actionMenu"><li>'.$this->Xhtml->link(
        $this->Xhtml->image(
            'icons/application_form_magnify.png',
            array( 'alt' => '' )
        ).' Formulaire',
        '#',
        array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
    ).'</li></ul>';
?>
<?php echo $this->Xform->create( 'Sanctionseps58', array( 'id' => 'Search', 'class' => 'folded' ) );?>
	<?php echo $this->Search->paginationNombretotal(); ?>

    <div class="submit noprint">
        <?php echo $this->Xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
    </div>

<?php echo $this->Xform->end();?>

<?php
	echo $this->Default2->index(
		$personnes,
		array(
			'Orientstruct.chosen' => array( 'input' => 'checkbox', 'type' => 'boolean', 'domain' => 'sanctionep58' ),
			'Dossier.matricule',
			'Personne.nom',
			'Personne.prenom',
			'Personne.dtnai',
			'Adresse.locaadr',
			'Typeorient.lib_type_orient' => array( 'type' => 'text' ),
			'Structurereferente.lib_struc' => array( 'type' => 'text' ),
			'Structureorientante.lib_struc' => array( 'type' => 'text' ),
			'Orientstruct.date_valid' => array( 'type' => 'date' ),
			'Serviceinstructeur.lib_service' => array( 'type' => 'text' )
		),
		array(
			'cohorte' => true,
			'hidden' => array(
				'Personne.id',
				'Orientstruct.id',
				'Dossierep.id',
			),
			'paginate' => 'Personne',
			'domain' => 'sanctionep58',
			'tooltip' => array(
				'Structurereferenteparcours.lib_struc' => array( 'type' => 'text', 'domain' => 'search_plugin' ),
				'Referentparcours.nom_complet' => array( 'type' => 'text', 'domain' => 'search_plugin' )
			)
		)
	);
?>
<?php if( !empty( $personnes ) ):?>
		<ul class="actionMenu">
			<li><?php
				echo $this->Xhtml->exportLink(
					'Télécharger le tableau',
					array( 'controller' => 'sanctionseps58', 'action' => 'exportcsv', 'qdNonInscrits' )
				);
			?></li>
		</ul>
<?php
		echo $this->Form->button( 'Tout cocher', array( 'onclick' => 'return toutCocher();' ) );
		echo $this->Form->button( 'Tout décocher', array( 'onclick' => 'return toutDecocher();' ) );

endif;?>

<?php echo $this->Search->observeDisableFormOnSubmit( 'Search' ); ?>