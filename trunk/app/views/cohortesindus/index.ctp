<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Gestion des indus';?>

<h1>Recherche par Indus</h1>

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
	$pagination = $xpaginator->paginationBlock( 'Dossier', $this->passedArgs );
?>

<?php echo $form->create( 'Cohorteindu', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( ( !empty( $this->data ) && empty( $this->validationErrors ) ) ? 'folded' : 'unfolded' ) ) );?>
	<fieldset>
		<legend>Recherche par personne</legend>
		<?php echo $form->input( 'Cohorteindu.nom', array( 'label' => 'Nom ', 'type' => 'text' ) );?>
		<?php echo $form->input( 'Cohorteindu.prenom', array( 'label' => 'Prénom ', 'type' => 'text' ) );?>
		<?php echo $form->input( 'Cohorteindu.nir', array( 'label' => 'NIR ', 'maxlength' => 15 ) );?>
		<?php echo $form->input( 'Cohorteindu.matricule', array( 'label' => 'N° CAF ', 'maxlength' => 15 ) );?>
		<?php
			$valueDossierDernier = isset( $this->data['Dossier']['dernier'] ) ? $this->data['Dossier']['dernier'] : true;
			echo $form->input( 'Dossier.dernier', array( 'label' => 'Uniquement la dernière demande RSA pour un même allocataire', 'type' => 'checkbox', 'checked' => $valueDossierDernier ) );
		?>
		<?php echo $search->etatdosrsa($etatdosrsa); ?>
	</fieldset>
	<fieldset>
		<legend>Recherche d'Indu</legend>
			<?php echo $form->input( 'Cohorteindu.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>
			<?php echo $form->input( 'Cohorteindu.natpf', array( 'label' => 'Nature de la prestation', 'type' => 'select', 'options' => $natpf, 'empty' => true ) );?>
			<?php echo $form->input( 'Cohorteindu.natpfcre', array( 'label' => 'Type d\'indu', 'type' => 'select', 'options' => $natpfcre, 'empty' => true ) );?>
			<?php echo $form->input( 'Cohorteindu.locaadr', array( 'label' => 'Commune de l\'allocataire ', 'type' => 'text' ) );?>
			<!-- <?php echo $form->input( 'Cohorteindu.numcomptt', array( 'label' => 'Numéro de commune au sens INSEE' ) );?> -->
			<?php echo $form->input( 'Cohorteindu.numcomptt', array( 'label' => 'Numéro de commune au sens INSEE', 'type' => 'select', 'options' => $mesCodesInsee, 'empty' => true ) );?>
			<?php
				if( Configure::read( 'CG.cantons' ) ) {
					echo $form->input( 'Canton.canton', array( 'label' => 'Canton', 'type' => 'select', 'options' => $cantons, 'empty' => true ) );
				}
			?>

			<?php echo $form->input( 'Cohorteindu.typeparte', array( 'label' => 'Suivi', 'type' => 'select', 'options' => $typeparte, 'empty' => true ) ); ?>
			<?php echo $form->input( 'Cohorteindu.structurereferente_id', array( 'label' => 'Structure référente', 'type' => 'select', 'options' => $sr , 'empty' => true )  ); ?>
			<?php
				echo $form->input( 'Cohorteindu.compare', array( 'label' => 'Opérateurs', 'type' => 'select', 'options' => $comparators, 'empty' => true ) );
				echo $form->input( 'Cohorteindu.mtmoucompta', array( 'label' => 'Montant de l\'indu', 'type' => 'text' ) );
			?>
	</fieldset>

	<div class="submit noprint">
		<?php echo $form->button( 'Filtrer', array( 'type' => 'submit' ) );?>
		<?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>
<?php echo $form->end();?>

<!-- Résultats -->
<?php if( isset( $cohorteindu ) ):?>

	<h2 class="noprint">Résultats de la recherche</h2>

	<?php if( is_array( $cohorteindu ) && count( $cohorteindu ) > 0 ):?>
		<?php echo $pagination;?>
			<table id="searchResults" class="tooltips">
				<thead>
					<tr>
						<th><?php echo $xpaginator->sort( 'N° Dossier', 'Dossier.numdemrsa' );?></th>
						<th><?php echo $xpaginator->sort( 'Nom de l\'allocataire', 'Personne.nom' );?></th>
						<th><?php echo $xpaginator->sort( 'Suivi', 'Dossier.typeparte' );?></th>
						<th><?php echo $xpaginator->sort( 'Situation des droits', 'Situationdossierrsa.etatdosrsa' );?></th>

						<th>Date indus</th><!-- FIXME -->

						<th><?php echo $xpaginator->sort( 'Montant initial de l\'indu', 'IndusConstates.mtmoucompta' );?></th>
						<th><?php echo $xpaginator->sort( 'Montant transféré CG', 'IndusTransferesCG.mtmoucompta' );?></th>
						<th><?php echo $xpaginator->sort( 'Remise CG', 'RemisesIndus.mtmoucompta' );?></th>

						<th class="action">Action</th>
						<th class="innerTableHeader">Informations complémentaires</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach( $cohorteindu as $index => $indu ):?>
						<?php
						$innerTable = '<table id="innerTablesearchResults'.$index.'" class="innerTable">
							<tbody>
								<tr>
									<th>Date naissance</th>
									<td>'.h( date_short( $indu['Personne']['dtnai'] ) ).'</td>
								</tr>
								<tr>
									<th>Numéro CAF</th>
									<td>'.h( $indu['Dossier']['matricule'] ).'</td>
								</tr>
								<tr>
									<th>NIR</th>
									<td>'.h( $indu['Personne']['nir'] ).'</td>
								</tr>
								<tr>
									<th>Code postal</th>
									<td>'.h( $indu['Adresse']['codepos'] ).'</td>
								</tr>
								<tr>
									<th>Code INSEE</th>
									<td>'.h( $indu['Adresse']['numcomptt'] ).'</td>
								</tr>
								<tr>
									<th>Rôle</th>
									<td>'.$rolepers[$indu['Prestation']['rolepers']].'</td>
								</tr>								
							</tbody>
						</table>';
							$title = $indu['Dossier']['numdemrsa'];
							echo $xhtml->tableCells(
								array(
									h( $indu['Dossier']['numdemrsa'] ),
									h( $indu['Personne']['nom'].' '.$indu['Personne']['prenom'] ),
									h( $indu['Dossier']['typeparte'] ),
									h( $etatdosrsa[$indu['Situationdossierrsa']['etatdosrsa']] ),
									$locale->date( 'Date::miniLettre', $indu[0]['moismoucompta'] ),
									$xhtml->tag( 'span', $locale->money( $indu[0]['mt_indus_constate'] ), array( 'class' => 'number' ) ),
									$xhtml->tag( 'span', $locale->money( $indu[0]['mt_indus_transferes_c_g'] ), array( 'class' => 'number' ) ),
									$xhtml->tag( 'span', $locale->money( $indu[0]['mt_remises_indus'] ), array( 'class' => 'number' ) ),
									$xhtml->viewLink(
										'Voir le contrat « '.$title.' »',
										array( 'controller' => 'indus', 'action' => 'view', $indu['Dossier']['id'] )
									),
									array( $innerTable, array( 'class' => 'innerTableCell' ) )
								),
							array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
							array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
							);
						?>
					<?php endforeach;?>
				</tbody>
			</table>

	<?php echo $pagination;?>

	<ul class="actionMenu">
			<li><?php
				echo $xhtml->exportLink(
					'Télécharger le tableau',
					array( 'controller' => 'cohortesindus', 'action' => 'exportcsv' ) + Set::flatten( $this->data, '__' )
				);
			?></li>
		</ul>
	<?php else:?>
		<p>Vos critères n'ont retourné aucun dossier.</p>
	<?php endif?>
<?php endif?>