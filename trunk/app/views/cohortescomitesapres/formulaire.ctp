<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>
<?php if( isset( $comitesapres ) && is_array( $comitesapres ) && count( $comitesapres ) > 0 ):?>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $comitesapres ) ; $i++ ):?>
		observeDisableFieldsOnValue( 'ApreComiteapre<?php echo $i;?>Decisioncomite', [ 'ApreComiteapre<?php echo $i;?>Montantattribue' ], 'ACC', false );
		<?php endfor;?>
	});
</script>
<?php endif;?>

<h1><?php echo $this->pageTitle = 'Décisions des comités';?></h1>

<?php
	if( isset( $comitesapres ) ) {
		$pagination = $xpaginator->paginationBlock( 'Comiteapre', $this->passedArgs );
	}
	else {
		$pagination = '';
	}
?>

<?php  require_once( 'filtre.ctp' );?>
<!-- Résultats -->

<?php if( isset( $comitesapres ) ):?>

	<?php if( is_array( $comitesapres ) && count( $comitesapres ) > 0 ):?>
		<?php echo $pagination;?>
		<?php echo $xform->create( 'Cohortecomiteapre', array( 'url'=> Router::url( null, true ) ) );?>

		<?php
			$filtre = Set::extract( $this->data, 'Cohortecomiteapre' );
			if( !empty( $filtre ) ) {
				foreach( $filtre as $key => $value ) {
					echo $xform->input( "Cohortecomiteapre.{$key}", array( 'type' => 'hidden', 'value' => $value ) );
				}
			}
		?>

		<table id="searchResults" class="tooltips">
			<thead>
				<tr>
					<th><?php echo $xpaginator->sort( 'N° demande RSA', 'Dossier.numdemrsa' );?></th>
					<th><?php echo $xpaginator->sort( 'Nom de l\'allocataire', 'Personne.nom' );?></th>
					<th><?php echo $xpaginator->sort( 'Commune de l\'allocataire', 'Adresse.locaadr' );?></th>
					<th><?php echo $xpaginator->sort( 'Date de demande APRE', 'Apre.datedemandeapre' );?></th>
					<th>Décision comité examen</th>
					<th><?php echo $xpaginator->sort( 'Date de décision comité', 'Comiteapre.datecomite' );?></th>
					<th>Montant demandé</th>
					<th>Montant attribué</th>
					<th>Observations</th>
					<th class="action noprint">Actions</th>
					<th class="innerTableHeader noprint">Informations complémentaires</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $comitesapres as $index => $comite ):?>
				<?php
					$innerTable = '<table id="innerTablesearchResults'.$index.'" class="innerTable">
							<tbody>
								<tr>
									<th>N° CAF</th>
									<td>'.h( $comite['Dossier']['matricule'] ).'</td>
								</tr>
								<tr>
									<th>Date naissance</th>
									<td>'.h( date_short( $comite['Personne']['dtnai'] ) ).'</td>
								</tr>
								<tr>
									<th>NIR</th>
									<td>'.h( $comite['Personne']['nir'] ).'</td>
								</tr>
								<tr>
									<th>Code postal</th>
									<td>'.h( $comite['Adresse']['codepos'] ).'</td>
								</tr>
							</tbody>
						</table>';
						$title = $comite['Dossier']['numdemrsa'];


					$apre_id = Set::extract( $comite, 'ApreComiteapre.apre_id');
					$comiteapre_id = Set::extract( $comite, 'ApreComiteapre.comiteapre_id');
					$aprecomiteapre_id = Set::extract( $comite, 'ApreComiteapre.id');

					echo $xhtml->tableCells(
						array(
							h( Set::classicExtract( $comite, 'Dossier.numdemrsa') ),
							h( Set::classicExtract( $comite, 'Personne.qual').' '.Set::classicExtract( $comite, 'Personne.nom').' '.Set::classicExtract( $comite, 'Personne.prenom') ),
							h( Set::classicExtract( $comite, 'Adresse.locaadr') ),
							h( $locale->date( 'Date::short', Set::extract( $comite, 'Apre.datedemandeapre' ) ) ),

							$xform->enum( 'ApreComiteapre.'.$index.'.decisioncomite', array( 'label' => false, 'type' => 'select', 'options' => $options['decisioncomite'], 'empty' => true ) ).
							$xform->input( 'ApreComiteapre.'.$index.'.apre_id', array( 'label' => false, 'div' => false, 'value' => $apre_id, 'type' => 'hidden' ) ).
							$xform->input( 'ApreComiteapre.'.$index.'.id', array( 'label' => false, 'div' => false, 'value' => $aprecomiteapre_id, 'type' => 'hidden' ) ).
							$xform->input( 'ApreComiteapre.'.$index.'.comiteapre_id', array( 'label' => false, 'type' => 'hidden', 'value' => $comiteapre_id ) ).
							$xform->input( 'Comiteapre.'.$index.'.id', array( 'label' => false, 'type' => 'hidden', 'value' => Set::extract( $comite, 'Comiteapre.id' ) ) ).
							$xform->input( 'Apre.'.$index.'.id', array( 'label' => false, 'type' => 'hidden', 'value' => Set::extract( $comite, 'Apre.id' ) ) ),


							h( $locale->date( 'Date::short', Set::extract( $comite, 'Comiteapre.datecomite' ) ) ),
							h( Set::classicExtract( $comite, 'Apre.montanttotal') ),
							$xform->input( 'ApreComiteapre.'.$index.'.montantattribue', array( 'label' => false, 'type' => 'text', 'value' => Set::classicExtract( $comite, 'Apre.montanttotal' ) ) ),
							$xform->input( 'ApreComiteapre.'.$index.'.observationcomite', array( 'label' => false, 'type' => 'text', 'rows' => 3 ) ),
							$xhtml->viewLink(
								'Voir l\'APRE',
								array( 'controller' => 'apres', 'action' => 'index', Set::classicExtract( $comite, 'Personne.id' ) ),
								true,
								true
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
		<?php echo $xform->submit( 'Validation de la liste', array( 'onclick' => 'return confirm( "Êtes-vous sûr de vouloir valider ?" )' ) );?>
		<?php echo $xform->end();?>


	<?php else:?>
		<p>Aucune APRE présente pour ce Comité d'examen.</p>
	<?php endif?>
<?php endif?>