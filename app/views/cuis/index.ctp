<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Contrat Unique d\'Insertion';?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<div class="with_treemenu">
	<?php
		echo $xhtml->tag(
			'h1',
			$this->pageTitle = __d( 'cui', "Cuis::{$this->action}", true )
		);
	?>
	<?php if( empty( $cuis ) ):?>
		<p class="notice">Cette personne ne possède pas encore de CUI.</p>
	<?php endif;?>

	<?php if( $permissions->check( 'cuis', 'add' ) ):?>
		<ul class="actionMenu">
			<?php
				echo '<li>'.$xhtml->addLink(
					'Ajouter un CUI',
					array( 'controller' => 'cuis', 'action' => 'add', $personne_id )
				).' </li>';
			?>
		</ul>
	<?php endif;?>

	<?php if( !empty( $cuis ) ):?>
	<table class="tooltips">
		<thead>
			<tr>
				<th>Date du contrat</th>
				<th>Secteur</th>
				<th>Dénomination</th>
				<th>Décision pour le CUI</th>
				<th>Date de validation</th>
				<th colspan="5" class="action">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach( $cuis as $cui ):?>
				<?php
					$isPeriodeImmersion = false;
					$hasPeriode = Set::classicExtract( $cui, 'Cui.iscae' );
					if( $hasPeriode == 'O' ) {
						$isPeriodeImmersion = true;
					}

					echo $xhtml->tableCells(
						array(
							h( date_short( Set::classicExtract( $cui, 'Cui.datecontrat' ) ) ),
							h( Set::enum( Set::classicExtract( $cui, 'Cui.secteur' ), $options['secteur'] ) ),
							h( Set::classicExtract( $cui, 'Cui.nomemployeur' ) ),
							h( Set::enum( Set::classicExtract( $cui, 'Cui.decisioncui' ), $options['decisioncui'] ) ),
							h( date_short( Set::classicExtract( $cui, 'Cui.datevalidationcui' ) ) ),
							$xhtml->validateLink(
								'Valider le CUI',
								array( 'controller' => 'cuis', 'action' => 'valider',  Set::classicExtract( $cui, 'Cui.id' ) )
							),
							$xhtml->periodeImmersionLink(
								'Périodes d\'immersion',
								array( 'controller' => 'periodesimmersion', 'action' => 'index', Set::classicExtract( $cui, 'Cui.id' ) ),
								$isPeriodeImmersion,
								$permissions->check( 'periodesimmersion', 'index' )
							),
							$xhtml->editLink(
								'Éditer le CUI',
								array( 'controller' => 'cuis', 'action' => 'edit', Set::classicExtract( $cui, 'Cui.id' ) ),
								$permissions->check( 'cuis', 'edit' )
							),
							$xhtml->printLink(
								'Imprimer le CUI',
								array( 'controller' => 'cuis', 'action' => 'impression', Set::classicExtract( $cui, 'Cui.id' ) ),
								$permissions->check( 'cuis', 'impression' )
							),
							$xhtml->deleteLink(
								'Supprimer le CUI',
								array( 'controller' => 'cuis', 'action' => 'delete', Set::classicExtract( $cui, 'Cui.id' ) ),
								$permissions->check( 'cuis', 'delete' )
							)
						),
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);
				?>
			<?php endforeach;?>
		</tbody>
	</table>
	<?php  endif;?>
</div>
<div class="clearer"><hr /></div>