<?php
	$this->pageTitle = __d( 'contactpartenaire', "Contactspartenaires::{$this->action}", true );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $xhtml->tag( 'h1', $this->pageTitle );
?>

<?php
	echo $default->form(
		array(
			'Contactpartenaire.qual' => array( 'options' => $qual, 'empty' => true, 'required' => true ),
			'Contactpartenaire.nom' => array( 'required' => true ),
			'Contactpartenaire.prenom' => array( 'required' => true ),
			'Contactpartenaire.numtel',
			'Contactpartenaire.numfax',
			'Contactpartenaire.email',
			'Contactpartenaire.partenaire_id' => array( 'type' => 'select', 'empty' => true, 'required' => true )
		),
		array(
			'actions' => array(
				'Contactpartenaire.save',
				'Contactpartenaire.cancel',
			),
			'options' => $options
		)
	);
	echo $default->button(
		'back',
		array(
			'controller' => 'contactspartenaires',
			'action'     => 'index'
		),
		array(
			'id' => 'Back'
		)
	);
?>