<?php
	$title_for_layout = 'Décison du CPDV';
	$this->set( 'title_for_layout', $title_for_layout );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>

<div class="with_treemenu">
<?php
	// FIXME: liste de titres depuis le contrôleur
	// FIXME: droits (étapes, liens, voir ci-dessous) depuis le contrôleur (ou le modèle)
	echo $this->Html->tag( 'h1', $title_for_layout );

	echo $this->Xform->create( null, array( 'inputDefaults' => array( 'domain' => 'histochoixcer93' ) ) );

	if( $this->action == 'attdecisioncg' ) {
		echo $this->Xform->input( 'Histochoixcer93.isrejet', array( 'type' => 'checkbox' ) );
	}
	
	echo $this->Xform->inputs(
		array(
			'fieldset' => false,
			'legend' => false,
			'Histochoixcer93.id' => array( 'type' => 'hidden' ),
			'Histochoixcer93.cer93_id' => array( 'type' => 'hidden' ),
			'Histochoixcer93.user_id' => array( 'type' => 'hidden' ),
			'Histochoixcer93.formeci' => array( 'type' => 'radio', 'options' => $options['formeci'] ),
			'Histochoixcer93.commentaire' => array( 'type' => 'textarea' ),
			'Histochoixcer93.datechoix' => array( 'type' => 'date', 'dateFormat' => 'DMY' ),
			'Histochoixcer93.etape' => array( 'type' => 'hidden' )
		)
	);
?>

<?php
	echo $this->Html->tag(
		'div',
		 $this->Xform->button( 'Enregistrer', array( 'type' => 'submit' ) )
		.$this->Xform->button( 'Annuler', array( 'type' => 'submit', 'name' => 'Cancel' ) ),
		array( 'class' => 'submit noprint' )
	);

	echo $this->Xform->end();
?>
</div>
<div class="clearer"><hr /></div>