<?php
	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );

	if( $this->action == 'add' ) {
		$this->pageTitle = 'Ajout d\'un signalement';
	}
	else {
		$this->pageTitle = 'Modification d\'un signalement';
	}
	$modelClassName = 'Signalementep'.Configure::read( 'Cg.departement' );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>
<div class="with_treemenu">
	<h1> <?php echo $this->pageTitle; ?> </h1>
	<?php
		echo $this->Xform->create();

		if( empty( $this->request->data ) || !dateComplete( $this->request->data, "{$modelClassName}.date" ) ) {
			$defaultDate = date( 'Y-m-d' );
		}
		else {
			$defaultDate = $this->request->data[$modelClassName]['date'];
		}

		echo $this->Default->subform(
			array(
				"{$modelClassName}.id" => array( 'type' => 'hidden' ),
				"{$modelClassName}.date" => array( 'selected' => $defaultDate, 'type' => 'date' ),
				"{$modelClassName}.motif" => array( 'type' => 'textarea' ),
			)
		);

		echo '<div class="submit">';
		echo $this->Xform->submit( 'Enregistrer', array( 'div' => false ) );
		echo $this->Xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
		echo '</div>';
		echo $this->Xform->end();
	?>
</div>