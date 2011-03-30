<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<h1>
<?php
	if( $this->action == 'add' ) {
		echo $this->pageTitle = 'Ajout d\'une séance d\'EP';
	}
	else {
		echo $this->pageTitle = 'Modification d\'une séance d\'EP';
	}
?>
</h1>

<!--<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php
			echo $ajax->remoteFunction(
				array(
					'update' => 'Adresse',
					'url' => Router::url( '/', true ).'commissionseps/ajaxadresse'
				)
			);
		?>
	});
</script>-->

<?php

	echo $form->create( 'Commissionep', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );

	echo $default->subform(
		array(
			'Commissionep.id' => array('type'=>'hidden'),
// 			'Commissionep.identifiant',
			'Commissionep.ep_id',
			'Commissionep.name',
			'Commissionep.dateseance' => array( 'dateFormat' => __( 'Locale->dateFormat', true ), 'timeFormat' => __( 'Locale->timeFormat', true ), 'interval'=>15 ), // TODO: à mettre par défaut dans Default2Helper
// 			'Commissionep.structurereferente_id' => array ('type'=>'select'),
			'Commissionep.lieuseance',
			'Commissionep.adresseseance',
			'Commissionep.codepostalseance',
			'Commissionep.villeseance'
		),
		array(
			'options' => $options
		)
	);

	echo $ajax->observeField( 'CommissionepStructurereferenteId', array( 'update' => 'Adresse', 'url' => Router::url( '/', true ).'commissionseps/ajaxadresse' ) );

	echo $html->tag(
		'div',
		'',
		array(
			'id' => 'Adresse'
		)
	);

	echo $default->subform(
		array(
			'Commissionep.salle',
			'Commissionep.observations' => array('type'=>'textarea')
// 			'Commissionep.finalisee'
		),
		array(
			'options' => $options
		)
	);

	echo $form->end( 'Enregistrer' );
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		// Affichage de l'adresse lors de l'apparition du formulaire
		new Ajax.Updater(
			'Adresse',
			'<?php echo Router::url( '/', true ).'commissionseps/ajaxadresse';?>',
			{
				asynchronous:true,
				evalScripts:true,
				parameters:Form.Element.serialize('CommissionepStructurereferenteId'),
				requestHeaders:['X-Update', 'Adresse']
			}
		)
	} );
</script>