<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>
<?php
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'descriptionpdo', "Descriptionspdos::{$this->action}", true )
	)
?>
<?php
	$sensibilite = Set::classicExtract( $this->data, 'Descriptionpdo.sensibilite' );
	if( empty( $sensibilite ) ) {
		$sensibilite = 'N';
	}

	echo $default->form(
		array(
			'Descriptionpdo.name' => array( 'required' => true ),
			'Descriptionpdo.modelenotification',
			'Descriptionpdo.sensibilite' => array( 'type' => 'radio', 'required' => true ),
			'Descriptionpdo.decisionpcg' => array( 'type' => 'radio' , 'required' => true ),
			'Descriptionpdo.nbmoisecheance' => array( 'type' => 'text', 'required' => true ),
			'Descriptionpdo.dateactive' => array( 'type' => 'select' , 'required' => true )
// 			'Descriptionpdo.declencheep' => array( 'type' => 'radio' )
		),
		array(
			'actions' => array(
				'descriptionspdos::save',
				'descriptionspdos::cancel'
			),
			'options' => $options
		)
	);
?>
