<h1><?php echo $this->pageTitle = __d( 'radiepoleemploiep93', "{$this->name}::{$this->action}", true );?></h1>

<?php
	echo $default2->index(
		$personnes,
		array(
			'Historiqueetatpe.chosen' => array( 'input' => 'checkbox', 'type' => 'boolean', 'domain' => 'radiepoleemploiep93' ),
			'Personne.nom',
			'Personne.prenom',
			'Personne.dtnai',
			'Historiqueetatpe.date',
		),
		array(
			'cohorte' => true,
			'hidden' => array(
				'Personne.id',
				'Historiqueetatpe.id'
			),
			'paginate' => 'Personne'
		)
	);
?>