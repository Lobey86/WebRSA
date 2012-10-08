<?php
	 $this->pageTitle = 'Suppression de la commission d\'EP';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>

<h1><?php echo $this->pageTitle; ?></h1>

<?php
	echo $this->Form->create( 'Commissionep', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );

	echo $this->Form->input( 'Commissionep.raisonannulation', array( 'type' => 'textarea', 'label' => 'Raison de l\'annulation de la commission d\'EP' ) );

	echo $this->Form->end( 'Confirmer' );

	echo $this->Default->button(
		'back',
		array(
			'controller' => 'commissionseps',
			'action'     => 'view',
			$commissionep_id
		),
		array(
			'id' => 'Back'
		)
	);
?>