<?php
	$this->pageTitle = 'Traitements des PDOs';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>

<div class="with_treemenu">
	<?php
		echo $xhtml->tag(
			'h1',
			$this->pageTitle = __d( 'traitementpdo', "Traitementspdos::{$this->action}", true )
		);
	?>

	<?php
		echo $default2->index(
			$traitementspdos,
			array(
				'Traitementpdo.descriptionpdo_id' => array( 'type'=>'string' ),
				'Traitementpdo.datereception',
				'Traitementpdo.datedepart',
				'Traitementpdo.traitementtypepdo_id' => array( 'type'=>'string' ),
				'Traitementpdo.hascourrier',
				'Traitementpdo.hasrevenu',
				'Traitementpdo.hasficheanalyse',
				'Traitementpdo.haspiecejointe'
			),
			array(
				'actions' => array(
					'Traitementspdos::view' => array( 'disabled' => '\'#Traitementpdo.clos#\' != 0' ),
					'Traitementspdos::edit' => array( 'disabled' => '\'#Traitementpdo.clos#\' != 0' ),
					'Traitementspdos::clore' => array( 'disabled' => '\'#Traitementpdo.clos#\' != 0' ),
					'Traitementspdos::print' => array( 'controller' => 'traitementspdos', 'action' => 'gedooo' )
				),
				'add' => array( 'Traitementpdo.add' => array( 'controller'=>'traitementspdos', 'action'=>'add', $pdo_id ) ),
				'options' => $options
			)
		);

		echo $default->button(
			'back',
			array(
				'controller' => 'propospdos',
				'action'     => 'index',
				$personne_id
			),
			array(
				'id' => 'Back'
			)
		);

	?>
</div>
<div class="clearer"><hr /></div>