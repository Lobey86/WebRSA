<?php
	/**
	 * Valeurs des filtres de recherche par défaut pour la "Recherche par rendez
	 * -vous (nouveau)"
	 *
	 * @var array
	 */
	Configure::write(
			'Filtresdefaut.Rendezvous_search',
		array(
			'Dossier' => array(
				// Case à cocher "Uniquement la dernière demande RSA pour un même allocataire"
				'dernier' => '1'
			),
			'Rendezvous' => array(
				// Case à cocher "Filtrer par date de RDV"
				'daterdv' => '0',
				// Du (inclus)
				'daterdv_from' => date_sql_to_cakephp( date( 'Y-m-d', strtotime( '-1 week' ) ) ),
				// Au (inclus)
				'daterdv_to' => date_sql_to_cakephp( date( 'Y-m-d', strtotime( 'now' ) ) ),
			)
		)
	);

	/**
	 * Les champs à faire apparaître dans les résultats de la recherche par
	 * rendez-vous:
	 *	- lignes du tableau: ConfigurableQueryRendezvous.search.fields
	 *	- info-bulle du tableau: ConfigurableQueryRendezvous.search.innerTable
	 *	- export CSV: ConfigurableQueryRendezvous.exportcsv
	 *
	 * @var array
	 */
	// FIXME: champs export CSV
	Configure::write(
		'ConfigurableQueryRendezvous',
		array(
			'search' => array(
				'fields' => array(
					'Personne.nom_complet',
					'Adresse.nomcom',
					'Structurereferente.lib_struc',
					'Referent.nom_complet',
					'Typerdv.libelle',
					'Rendezvous.daterdv',
					'Rendezvous.heurerdv',
					'Statutrdv.libelle',
					// FIXME: caché dans le title, attention au thead
					/*'Dossier.numdemrsa' => array(
						'condition' => false
					),*/
					'/Rendezvous/index/#Rendezvous.personne_id#',
					'/Rendezvous/impression/#Rendezvous.id#'
				),
				'innerTable' => array(
					'Personne.dtnai',
					'Adresse.numcom',
					'Personne.nir',
					'Prestation.rolepers',
					'Rendezvous.thematiques' => array(
						'type' => 'list'
					),
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet',
				),
				'order' => array( 'Rendezvous.daterdv' )
			),
			'exportcsv' => array(
				'Personne.qual',
				'Personne.nom',
				'Personne.prenom',
				'Dossier.matricule',
				'Adresse.numvoie',
				'Adresse.libtypevoie',
				'Adresse.nomvoie',
				'Adresse.complideadr',
				'Adresse.compladr',
				'Adresse.codepos',
				'Adresse.nomcom',
				'Structurereferente.lib_struc',
				'Structurereferente.num_voie',
				'Structurereferente.type_voie',
				'Structurereferente.nom_voie',
				'Structurereferente.code_postal',
				'Structurereferente.ville',
				'Referent.nom_complet',
				'Typerdv.libelle',
				'Rendezvous.thematiques' => array(
					'type' => 'list'
				),
				'Statutrdv.libelle',
				'Rendezvous.daterdv',
				'Rendezvous.heurerdv',
				'Rendezvous.objetrdv',
				'Rendezvous.commentairerdv',
				'Situationdossierrsa.etatdosrsa',
				'Structurereferenteparcours.lib_struc',
				'Referentparcours.nom_complet',
			)
		)
	);
?>