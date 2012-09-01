<?php
	$domain = 'criterecui';
	$this->pageTitle = __d( 'criterecui', "Criterescuis::{$this->action}", true );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>
<?php
	echo $xhtml->tag( 'h1', $this->pageTitle );
?>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		observeDisableFieldsetOnCheckbox( 'CuiDatecontrat', $( 'CuiDatecontratFromDay' ).up( 'fieldset' ), false );
	});
</script>
<?php
	if( is_array( $this->data ) ) {
		echo '<ul class="actionMenu"><li>'.$xhtml->link(
			$xhtml->image(
				'icons/application_form_magnify.png',
				array( 'alt' => '' )
			).' Formulaire',
			'#',
			array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
		).'</li></ul>';
	}

	echo $xform->create( 'Criterescuis', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );
	echo $form->input( 'Criterecui.active', array( 'type' => 'hidden', 'value' => true ) );
?>
	<?php
		echo $search->blocAllocataire( );

		echo $search->blocAdresse( $mesCodesInsee, $cantons );
	?>
	<fieldset>
		<legend>Recherche par dossier</legend>
		<?php
			echo $form->input( 'Dossier.numdemrsa', array( 'label' => 'Numéro de demande RSA' ) );
			echo $form->input( 'Dossier.matricule', array( 'label' => 'N° CAF', 'maxlength' => 15 ) );

			$valueDossierDernier = isset( $this->data['Dossier']['dernier'] ) ? $this->data['Dossier']['dernier'] : true;
			echo $form->input( 'Dossier.dernier', array( 'label' => 'Uniquement la dernière demande RSA pour un même allocataire', 'type' => 'checkbox', 'checked' => $valueDossierDernier ) );
			echo $search->etatdosrsa($etatdosrsa);
		?>
	</fieldset>
<fieldset>
	<legend>Recherche de Contrat Unique d'Insertion</legend>
		<?php echo $form->input( 'Cui.datecontrat', array( 'label' => 'Filtrer par date de saisie du contrat', 'type' => 'checkbox' ) );?>
		<fieldset>
			<legend>Date de saisie du contrat</legend>
			<?php
				$datecontrat_from = Set::check( $this->data, 'Cui.datecontrat_from' ) ? Set::extract( $this->data, 'Cui.datecontrat_from' ) : strtotime( '-1 week' );
				$datecontrat_to = Set::check( $this->data, 'Cui.datecontrat_to' ) ? Set::extract( $this->data, 'Cui.datecontrat_to' ) : strtotime( 'now' );

				echo $form->input( 'Cui.datecontrat_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $datecontrat_from ) );
				echo $form->input( 'Cui.datecontrat_to', array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120,  'maxYear' => date( 'Y' ) + 5,  'selected' => $datecontrat_to ) );
			?>
		</fieldset>
		<?php
			echo $form->input( 'Cui.secteur', array( 'label' => __d( 'cui', 'Cui.secteur', true ), 'type' => 'select', 'options' => $options['secteur'], 'empty' => true ) );
		?>
</fieldset>

	<div class="submit noprint">
		<?php echo $form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
		<?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>

<?php echo $form->end();?>

<?php $pagination = $xpaginator->paginationBlock( 'Cui', $this->passedArgs ); ?>

	<?php if( isset( $criterescuis ) ):?>
	<br />
	<h2 class="noprint aere">Résultats de la recherche</h2>

	<?php if( is_array( $criterescuis ) && count( $criterescuis ) > 0  ):?>
		<?php echo $pagination;?>
		<table class="tooltips">
			<thead>
				<tr>
					<th><?php echo $xpaginator->sort( 'N° dossier', 'Dossier.numdemrsa' );?></th>
					<th><?php echo $xpaginator->sort( 'Nom du demandeur', 'Personne.nom' );?></th>
					<th><?php echo $xpaginator->sort( 'Secteur', 'Cui.secteur' );?></th>
					<th><?php echo $xpaginator->sort( 'Date du contrat', 'Cui.datecontrat' );?></th>
					<th><?php echo $xpaginator->sort( 'Nom de l\'employeur', 'Cui.nomemployeur' );?></th>
					<th><?php echo $xpaginator->sort( 'Date de début de prise en charge', 'Cui.datedebprisecharge' );?></th>
					<th><?php echo $xpaginator->sort( 'Date de fin de prise en charge', 'Cui.datefinprisecharge' );?></th>
					<th colspan="4" class="action">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach( $criterescuis as $index => $criterecui ) {
						$innerTable = '<table id="innerTable'.$index.'" class="innerTable">
							<tbody>
								<tr>
									<th>Etat du droit</th>
									<td>'.Set::enum( Set::classicExtract( $criterecui, 'Situationdossierrsa.etatdosrsa' ),$criterecui ).'</td>
								</tr>
								<tr>
									<th>Commune de naissance</th>
									<td>'. $criterecui['Personne']['nomcomnai'].'</td>
								</tr>
								<tr>
									<th>Date de naissance</th>
									<td>'.date_short( $criterecui['Personne']['dtnai']).'</td>
								</tr>
								<tr>
									<th>Code INSEE</th>
									<td>'.$criterecui['Adresse']['numcomptt'].'</td>
								</tr>
								<tr>
									<th>NIR</th>
									<td>'.$criterecui['Personne']['nir'].'</td>
								</tr>
								<tr>
									<th>N° CAF</th>
									<td>'.$criterecui['Dossier']['matricule'].'</td>
								</tr>
								<tr>
									<th>Rôle</th>
									<td>'.$rolepers[$criterecui['Prestation']['rolepers']].'</td>
								</tr>

							</tbody>
						</table>';
						echo $xhtml->tableCells(
							array(
								h( Set::classicExtract( $criterecui, 'Dossier.numdemrsa' ) ),
								h( Set::enum( Set::classicExtract( $criterecui, 'Personne.qual' ), $qual ).' '.Set::classicExtract( $criterecui, 'Personne.nom' ).' '.Set::classicExtract( $criterecui, 'Personne.prenom' ) ),
								h( Set::enum( Set::classicExtract( $criterecui, 'Cui.secteur' ), $options['secteur'] ) ),
								h( $locale->date( 'Locale->date',  Set::classicExtract( $criterecui, 'Cui.datecontrat' ) ) ),
								h( Set::classicExtract( $criterecui, 'Cui.nomemployeur' ) ),
								h( $locale->date( 'Locale->date',  Set::classicExtract( $criterecui, 'Cui.datedebprisecharge' ) ) ),
								h( $locale->date( 'Locale->date',  Set::classicExtract( $criterecui, 'Cui.datefinprisecharge' ) ) ),
								$xhtml->viewLink(
									'Voir',
									array( 'controller' => 'cuis', 'action' => 'index', Set::classicExtract( $criterecui, 'Cui.personne_id' ) )
								),
								array( $innerTable, array( 'class' => 'innerTableCell noprint' ) ),
							),
							array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
							array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
						);
					}
				?>
			</tbody>
		</table>
		<?php echo $pagination;?>
		<ul class="actionMenu">
			<li><?php
				echo $xhtml->printLinkJs(
					'Imprimer le tableau',
					array( 'onclick' => 'printit(); return false;', 'class' => 'noprint' )
				);
			?></li>
			<li><?php
				echo $xhtml->exportLink(
					'Télécharger le tableau',
					array( 'controller' => 'criterescuis', 'action' => 'exportcsv' ) + Set::flatten( $this->data, '__' )
				);
			?></li>
		</ul>
	<?php else:?>
		<p class="notice">Vos critères n'ont retourné aucun contrat unique d'insertion.</p>
	<?php endif?>
<?php endif?>

<!-- *********************************************************************** -->