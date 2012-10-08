<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->element( 'dossier_menu', array( 'id' => $id ) );
?>

<div class="with_treemenu">

<?php $this->pageTitle = 'Informations sur le dossier';?>
<h1><?php echo $this->pageTitle;?></h1>

<fieldset>
	<?php
		echo $this->Form->create( 'Dossier', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
		echo $this->Form->input( 'Dossier.id', array( 'type' => 'hidden' ) );

		echo $this->Default2->subform(
			array(
				'Dossier.numdemrsa' => array( 'domain' => 'dossier', 'required' => true ),
				'Dossier.dtdemrsa' => array( 'domain' => 'dossier' ),
				'Dossier.fonorg' => array( 'domain' => 'dossier', 'required' => true, 'type' => 'select', 'options' => $fonorg ),
				'Dossier.matricule' => array( 'domain' => 'dossier', 'required' => true ),
				'Dossier.dtdemrmi' => array( 'domain' => 'dossier' ),
				'Dossier.numdepinsrmi' => array( 'domain' => 'dossier' ),
				'Dossier.typeinsrmi' => array( 'domain' => 'dossier' ),
				'Dossier.numcominsrmi' => array( 'domain' => 'dossier' ),
				'Dossier.numagrinsrmi' => array( 'domain' => 'dossier' ),
				'Dossier.numdosinsrmi' => array( 'domain' => 'dossier' ),
				'Dossier.numcli' => array( 'domain' => 'dossier' ),
				'Dossier.numorg' => array( 'domain' => 'dossier' ),
				'Dossier.statudemrsa' => array( 'domain' => 'dossier', 'type' => 'select', 'options' => $optionsDossier['Dossier']['statudemrsa'] ),
				'Dossier.typeparte' => array( 'domain' => 'dossier' ),
				'Dossier.ideparte' => array( 'domain' => 'dossier' ),
				'Dossier.fonorgcedmut' => array( 'domain' => 'dossier', 'type' => 'select', 'options' => $optionsDossier['Dossier']['fonorgcedmut'] ),
				'Dossier.numorgcedmut' => array( 'domain' => 'dossier' ),
				'Dossier.matriculeorgcedmut' => array( 'domain' => 'dossier' ),
				'Dossier.ddarrmut' => array( 'domain' => 'dossier' ),
				'Dossier.codeposanchab' => array( 'domain' => 'dossier' ),
				'Dossier.fonorgprenmut' => array( 'domain' => 'dossier' ),
				'Dossier.numorgprenmut' => array( 'domain' => 'dossier' ),
				'Dossier.dddepamut' => array( 'domain' => 'dossier' ),
			),
			array(
				'options' => $optionsDossier
			)
		);
	?>
</fieldset>

	<?php echo $this->Form->submit( 'Enregistrer' );?>
<?php echo $this->Form->end();?>

<?php
	echo $this->Default->button(
		'back',
		array(
			'controller' => 'dossiers',
			'action'     => 'view',
			$id
		),
		array(
			'id' => 'Back'
		)
	);
?>

</div>
<div class="clearer"><hr /></div>