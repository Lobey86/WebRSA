<?php $this->pageTitle = 'Paramétrage des statuts de rendez-vous';?>
<?php echo $xform->create( 'StatutRDV' );?>
<div>
	<h1><?php echo 'Visualisation de la table Statut de RDV ';?></h1>

	<ul class="actionMenu">
		<?php
			echo '<li>'.$xhtml->addLink(
				'Ajouter',
				array( 'controller' => 'statutsrdvs', 'action' => 'add' )
			).' </li>';
		?>
	</ul>
	<?php if( empty( $statutsrdvs ) ):?>
		<p class="notice">Aucun statut de RDV présent pour le moment.</p>
	<?php else:?>
	<div>
		<h2>Table Statut de rendez-vous</h2>
		<table>
		<thead>
			<tr>
				<th>Statut de rendez-vous</th>
				<?php
					if( Configure::read( 'Cg.departement' ) == 58 ) {
						echo '<th>Provoque un passage en EP ?</th>';
					}
				?>
				<th colspan="2" class="action">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach( $statutsrdvs as $statutrdv ):?>
				<?php
					$listefields = array( h( $statutrdv['Statutrdv']['libelle'] ) );
					if( Configure::read( 'Cg.departement' ) == 58 ) {
						$listefields = array_merge(
							$listefields,
							array( $provoquepassageep[$statutrdv['Statutrdv']['provoquepassageep']] )
						);
					}
					$listefields = array_merge(
						$listefields,
						array(
							$xhtml->editLink(
								'Éditer le type d\'action',
								array( 'controller' => 'statutsrdvs', 'action' => 'edit', $statutrdv['Statutrdv']['id'] )
							),
							$xhtml->deleteLink(
								'Supprimer le type d\'action',
								array( 'controller' => 'statutsrdvs', 'action' => 'delete', $statutrdv['Statutrdv']['id'] )
							)
						)
					);
					echo $xhtml->tableCells(
						array(
							$listefields
						),
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);
				?>
			<?php endforeach;?>
			</tbody>
		</table>
	</div>
	<?php endif;?>
</div>
	<div class="submit">
		<?php
			echo $xform->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>

<div class="clearer"><hr /></div>
<?php echo $xform->end();?>