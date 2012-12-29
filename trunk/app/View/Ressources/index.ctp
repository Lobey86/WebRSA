<?php  $this->pageTitle = 'Ressources de la personne';?>
<h1>Ressources</h1>

<?php if( empty( $ressources ) ):?>
	<p class="notice">aucune information relative aux ressources de cette personne.</p>

	<?php if( $this->Permissions->checkDossierDossier( 'ressources', 'add', $dossierMenu ) ) :?>
		<ul class="actionMenu">
			<?php
				echo '<li>'.$this->Xhtml->addLink(
					'Déclarer une ressource',
					array( 'controller' => 'ressources', 'action' => 'add', $personne_id )
				).' </li>';
			?>
		</ul>
	<?php endif;?>

<?php  else:?>

	<?php if( $this->Permissions->checkDossier( 'ressources', 'add', $dossierMenu ) ) :?>
		<ul class="actionMenu">
			<?php
				echo '<li>'.$this->Xhtml->addLink(
					'Déclarer une ressource',
					array( 'controller' => 'ressources', 'action' => 'add', $personne_id )
				).' </li>';
			?>
		</ul>
	<?php endif;?>

<table class="tooltips">
	<thead>
		<tr>
			<th>Percevez-vous des ressources ?</th>
			<th>Montant DTR RSA</th>
			<th>Date de début </th>
			<th>Date de fin</th>
			<th colspan="2" class="action">Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach( $ressources as $ressource ):?>
			<?php

				$title = implode( ' ', array(
					$ressource['Ressource']['topressnotnul'] ,
					$this->Locale->money( $ressource['Ressource']['avg'] ),
					$ressource['Ressource']['ddress'] ,
					$ressource['Ressource']['dfress'] ,
				));

				echo $this->Xhtml->tableCells(
					array(
						h( $ressource['Ressource']['topressnotnul']  ? 'Oui' : 'Non'),
						$this->Locale->money( $ressource['Ressource']['avg'] ),
						h( date_short( $ressource['Ressource']['ddress'] ) ),
						h( date_short( $ressource['Ressource']['dfress'] ) ),
						$this->Xhtml->viewLink(
							'Voir la ressource',
							array( 'controller' => 'ressources', 'action' => 'view', $ressource['Ressource']['id'] ),
							$this->Permissions->checkDossier( 'ressources', 'view', $dossierMenu )
						),
						$this->Xhtml->editLink(
							'Éditer la ressource ',
							array( 'controller' => 'ressources', 'action' => 'edit', $ressource['Ressource']['id'] ),
							$this->Permissions->checkDossier( 'ressources', 'edit', $dossierMenu )
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