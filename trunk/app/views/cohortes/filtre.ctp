<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		observeDisableFieldsetOnCheckbox( 'FiltreDtdemrsa', $( 'FiltreDtdemrsaFromDay' ).up( 'fieldset' ), false );
		observeDisableFieldsetOnCheckbox( 'FiltreDatePrint', $( 'FiltreDateImpressionFromDay' ).up( 'fieldset' ), false );
		observeDisableFieldsetOnCheckbox( 'FiltreDateValid', $( 'FiltreDateValidFromDay' ).up( 'fieldset' ), false );
	});
</script>

<?php
	$oridemrsaCochees = Set::extract( $this->data, 'Filtre.oridemrsa' );
	if( empty( $oridemrsaCochees ) ) {
		$oridemrsaCochees = array_keys( $oridemrsa );
	}

	$formSent = ( isset( $this->data['Filtre']['actif'] ) && $this->data['Filtre']['actif'] );
?>
<?php echo $form->create( 'Filtre', array( 'url'=> Router::url( null, true ), 'id' => 'Filtre', 'class' => ( $formSent ? 'folded' : 'unfolded' ) ) );?>
	<div><?php echo $form->input( 'Filtre.actif', array( 'type' => 'hidden', 'value' => true ) );?></div>
	<fieldset>
		<legend>Recherche par personne</legend>
		<?php echo $form->input( 'Filtre.nom', array( 'label' => 'Nom ', 'type' => 'text' ) );?>
		<?php echo $form->input( 'Filtre.prenom', array( 'label' => 'Prénom ', 'type' => 'text' ) );?>
		<?php
// 			if( isset( $toppersdrodevorsa ) ) {
				echo $form->input( 'Filtre.toppersdrodevorsa', array( 'label' => 'Soumis à Droit et Devoir', 'type' => 'select', 'options' => $toppersdrodevorsa, 'selected' => ( !empty( $this->data ) ? @$this->data['Filtre']['toppersdrodevorsa'] : null ), 'empty' => true ) );
// 			}
				echo $form->input( 'Filtre.hasDsp', array( 'label' => 'Possède des DSPs ?', 'type' => 'select', 'options' => $hasDsp, 'selected' => ( !empty( $this->data ) ? @$this->data['Filtre']['hasDsp'] : 1 ), 'empty' => true ) );


			if( !is_null($natpf)) {
				echo $search->natpf($natpf);
			}
		?>
	</fieldset>

	<fieldset>
		<legend>Code origine demande Rsa</legend>
		<?php echo $form->input( 'Filtre.oridemrsa', array( 'label' => false, 'type' => 'select', 'multiple' => 'checkbox', 'options' => $oridemrsa, 'empty' => false, 'value' => $oridemrsaCochees ) );?>
	</fieldset>

	<fieldset>
		<legend>Commune de la personne</legend>
		<?php
			if( Configure::read( 'CG.cantons' ) ) {
				echo $form->input( 'Canton.canton', array( 'label' => 'Canton', 'type' => 'select', 'options' => $cantons, 'empty' => true ) );
			}
		?>
		<?php echo $form->input( 'Filtre.locaadr', array( 'label' => __d( 'adresse', 'Adresse.locaadr', true ), 'type' => 'text' ) );?>
		<!-- <?php echo $form->input( 'Filtre.numcomptt', array( 'label' => 'Numéro de commune au sens INSEE' ) );?> -->
		<?php echo $form->input( 'Filtre.numcomptt', array( 'label' => 'Numéro de commune au sens INSEE', 'type' => 'select', 'options' => $mesCodesInsee, 'empty' => true ) );?>
		<?php echo $form->input( 'Filtre.codepos', array( 'label' => __d( 'adresse', 'Adresse.codepos', true ), 'type' => 'text', 'maxlength' => 5 ) );?>
	</fieldset>

	<?php if( $this->action == 'orientees' ):?>
		<?php echo $form->input( 'Filtre.date_valid', array( 'label' => 'Filtrer par date d\'orientation', 'type' => 'checkbox' ) );?>
		<fieldset>
			<legend>Date d'orientation</legend>
			<?php
				$dateValidFromSelected = array();
				if( !dateComplete( $this->data, 'Filtre.date_valid_from' ) ) {
					$dateValidFromSelected = array( 'selected' => strtotime( '-1 week' ) );
				}
				echo $form->input( 'Filtre.date_valid_from', Set::merge( array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120 ), $dateValidFromSelected ) );

				echo $form->input( 'Filtre.date_valid_to', array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 5, 'minYear' => date( 'Y' ) - 120 ) );
			?>
		</fieldset>
		<fieldset>
			<legend>Imprimé/Non imprimé</legend>
			<?php echo $form->input( 'Filtre.date_impression', array( 'label' => 'Filtrer par impression', 'type' => 'select', 'options' => $printed, 'empty' => true ) );?>

		<?php echo $form->input( 'Filtre.date_print', array( 'label' => 'Filtrer par date d\'impression', 'type' => 'checkbox' ) );?>
		<fieldset>
			<legend>Date d'impression</legend>
			<?php
				$dateImpressionFromSelected = array();
				if( !dateComplete( $this->data, 'Filtre.date_impression_from' ) ) {
					$dateImpressionFromSelected = array( 'selected' => strtotime( '-1 week' ) );
				}
				echo $form->input( 'Filtre.date_impression_from', Set::merge( array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 5 ), $dateImpressionFromSelected ) );

				echo $form->input( 'Filtre.date_impression_to', array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 5, 'minYear' => date( 'Y' ) - 5 ) );
			?>
		</fieldset>
		</fieldset>
	<?php endif;?>

	<?php echo $form->input( 'Filtre.dtdemrsa', array( 'label' => 'Filtrer par date de demande', 'type' => 'checkbox' ) );?>
	<fieldset>
		<legend>Date de demande RSA</legend>
		<?php
			$dtdemrsaFromSelected = array();
			if( !dateComplete( $this->data, 'Filtre.dtdemrsa_from' ) ) {
				$dtdemrsaFromSelected = array( 'selected' => strtotime( '-1 week' ) );
			}
			echo $form->input( 'Filtre.dtdemrsa_from', Set::merge( array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120 ), $dtdemrsaFromSelected ) );

			echo $form->input( 'Filtre.dtdemrsa_to', array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 5, 'minYear' => date( 'Y' ) - 120 ) );
		?>
	</fieldset>
	<fieldset>
		<?php
			$valueDossierDernier = isset( $this->data['Dossier']['dernier'] ) ? $this->data['Dossier']['dernier'] : true;
			echo $form->input( 'Dossier.dernier', array( 'label' => 'Uniquement la dernière demande RSA pour un même allocataire', 'type' => 'checkbox', 'checked' => $valueDossierDernier ) );
		?>
	</fieldset>
	<fieldset>
		<?php
			if( in_array( $this->action, array( 'preconisationscalculables', 'preconisationsnoncalculables' ) ) ) {
				$enattente = array( 'Non orienté', 'En attente' );
				echo $form->input( 'Filtre.enattente', array( 'label' => __( 'Statut de l\'orientation', true ), 'type' => 'select', 'options' => array_combine( $enattente, $enattente ), 'empty' => true ) );
			}

			if( $this->action != 'preconisationsnoncalculables' ) {
				if ( Configure::read( 'Cg.departement' ) == 93 && ( in_array( $this->action, array( 'nouvelles', 'enattente', 'preconisationscalculables' ) ) ) ) {
					echo $form->input( 'Filtre.propo_algo', array( 'label' => __( 'Type de préOrientation', true ), 'type' => 'select', 'options' => $modeles, 'empty' => true ) );
				}
				else {
					echo $form->input( 'Filtre.typeorient', array( 'label' => __( 'Type d\'orientation', true ), 'type' => 'select', 'options' => $modeles, 'empty' => true ) );
					if( Configure::read( 'Cg.departement' ) == 93 ) {
						echo $form->input( 'Filtre.origine', array( 'label' => __d( 'orientstruct', 'Orientstruct.origine', true ), 'type' => 'select', 'options' => $options['Orientstruct']['origine'], 'empty' => true ) );
					}
				}
			}
		?>
	</fieldset>
	<?php
		if( !is_null($etatdosrsa)) {
			echo $search->etatdosrsa($etatdosrsa);
		}
	?>
	<fieldset>
		<legend>Comptage des résultats</legend>
		<?php echo $form->input( 'Filtre.paginationNombreTotal', array( 'label' => 'Obtenir le nombre total de résultats (plus lent)', 'type' => 'checkbox' ) );?>
	</fieldset>
	<div class="submit">
		<?php echo $form->button( 'Filtrer', array( 'type' => 'submit' ) );?>
		<?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>
<?php echo $form->end();?>