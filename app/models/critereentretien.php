<?php
	App::import( 'Sanitize' );

	class Critereentretien extends AppModel
	{
		public $name = 'Critereentretien';

		public $useTable = false;

		public $actsAs = array( 'Conditionnable' );

		/**
		*
		*/

		public function search( $mesCodesInsee, $filtre_zone_geo, $criteresentretiens, $lockedDossiers  ) {
			/// Conditions de base
			$conditions = array( );

			/// Critères zones géographiques
			$conditions[] = $this->conditionsZonesGeographiques( $filtre_zone_geo, $mesCodesInsee );

			/// Dossiers lockés
			if( !empty( $lockedDossiers ) ) {
				$conditions[] = 'Dossier.id NOT IN ( '.implode( ', ', $lockedDossiers ).' )';
			}

			/// Critères
			$numeroapre = Set::extract( $criteresentretiens, 'Apre.numeroapre' );
			$referent = Set::extract( $criteresentretiens, 'Apre.referent_id' );
			$locaadr = Set::extract( $criteresentretiens, 'Adresse.locaadr' );
			$numcomptt = Set::extract( $criteresentretiens, 'Adresse.numcomptt' );
			$structure = Set::extract( $criteresentretiens, 'Entretien.structurereferente_id' );
			$referent = Set::extract( $criteresentretiens, 'Entretien.referent_id' );



			// Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
			foreach( array( 'nom', 'prenom', 'nomnai', 'nir' ) as $criterePersonne ) {
				if( isset( $criteresentretiens['Personne'][$criterePersonne] ) && !empty( $criteresentretiens['Personne'][$criterePersonne] ) ) {
					$conditions[] = 'Personne.'.$criterePersonne.' ILIKE \''.$this->wildcard( $criteresentretiens['Personne'][$criterePersonne] ).'\'';
				}
			}

			// Localité adresse
			if( !empty( $locaadr ) ) {
				$conditions[] = 'Adresse.locaadr ILIKE \'%'.Sanitize::clean( $locaadr ).'%\'';
			}


			// Commune au sens INSEE
			if( !empty( $numcomptt ) ) {
				$conditions[] = 'Adresse.numcomptt ILIKE \'%'.Sanitize::clean( $numcomptt ).'%\'';
			}

			// Référent lié à l'APRE
			if( !empty( $arevoirle ) ) {
				$conditions[] = 'Entretien.arevoirle = \''.Sanitize::clean( $arevoirle ).'\'';
			}

			//Critères sur le dossier de l'allocataire - numdemrsa + matricule
			foreach( array( 'numdemrsa', 'matricule' ) as $critereDossier ) {
				if( isset( $criteresentretiens['Dossier'][$critereDossier] ) && !empty( $criteresentretiens['Dossier'][$critereDossier] ) ) {
					$conditions[] = 'Dossier.'.$critereDossier.' ILIKE \''.$this->wildcard( $criteresentretiens['Dossier'][$critereDossier] ).'\'';
				}
			}

			if( isset( $criteresentretiens['Entretien']['arevoirle'] ) && !empty( $criteresentretiens['Entretien']['arevoirle'] ) ) {
				if( valid_int( $criteresentretiens['Entretien']['arevoirle']['year'] ) ) {
					$conditions[] = 'EXTRACT(YEAR FROM Entretien.arevoirle) = '.$criteresentretiens['Entretien']['arevoirle']['year'];
				}
				if( valid_int( $criteresentretiens['Entretien']['arevoirle']['month'] ) ) {
					$conditions[] = 'EXTRACT(MONTH FROM Entretien.arevoirle) = '.$criteresentretiens['Entretien']['arevoirle']['month'];
				}
			}

			if ( isset($criteresentretiens['Entretien']['structurereferente_id']) && !empty($criteresentretiens['Entretien']['structurereferente_id']) ) {
				$conditions[] = array('Entretien.structurereferente_id'=>$criteresentretiens['Entretien']['structurereferente_id']);
			}

			if ( isset($criteresentretiens['Entretien']['referent_id']) && !empty($criteresentretiens['Entretien']['referent_id']) ) {
				$conditions[] = array('Entretien.referent_id'=>$criteresentretiens['Entretien']['referent_id']);
			}

			// Trouver la dernière demande RSA pour chacune des personnes du jeu de résultats
			$conditions = $this->conditionsDernierDossierAllocataire( $conditions, $criteresentretiens );

			/// Requête
			$this->Dossier = ClassRegistry::init( 'Dossier' );

			$joins = array(
				array(
					'table'      => 'personnes',
					'alias'      => 'Personne',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Personne.id = Entretien.personne_id' ),
				),
				array(
					'table'      => 'referents',
					'alias'      => 'Referent',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Referent.id = Entretien.referent_id' ),
				),
				array(
					'table'      => 'structuresreferentes',
					'alias'      => 'Structurereferente',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Structurereferente.id = Entretien.structurereferente_id' ),
				),
				array(
					'table'      => 'objetsentretien',
					'alias'      => 'Objetentretien',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Objetentretien.id = Entretien.objetentretien_id' ),
				),
				array(
					'table'      => 'prestations',
					'alias'      => 'Prestation',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array(
						'Personne.id = Prestation.personne_id',
						'Prestation.natprest = \'RSA\'',
						'( Prestation.rolepers = \'DEM\' OR Prestation.rolepers = \'CJT\' )',
					)
				),
				array(
					'table'      => 'foyers',
					'alias'      => 'Foyer',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Personne.foyer_id = Foyer.id' )
				),
				array(
					'table'      => 'dossiers',
					'alias'      => 'Dossier',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Foyer.dossier_id = Dossier.id' )
				),
				array(
					'table'      => 'adressesfoyers',
					'alias'      => 'Adressefoyer',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array(
						'Foyer.id = Adressefoyer.foyer_id',
						'Adressefoyer.id IN (
							'.ClassRegistry::init( 'Adressefoyer' )->sqDerniereRgadr01('Adressefoyer.foyer_id').'
						)'
					)
				),
				array(
					'table'      => 'adresses',
					'alias'      => 'Adresse',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
				)
			);


			$query = array(
				'fields' => array(
					'Entretien.personne_id',
					'Entretien.arevoirle',
					'Entretien.dateentretien',
					'Entretien.structurereferente_id',
					'Entretien.referent_id',
					'Entretien.typeentretien',
					'Entretien.objetentretien_id',
					'Referent.qual',
					'Referent.nom',
					'Referent.prenom',
					'Structurereferente.lib_struc',
					'Objetentretien.name',
					'Dossier.id',
					'Dossier.numdemrsa',
					'Dossier.dtdemrsa',
					'Dossier.matricule',
					'Personne.id',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Personne.nir',
					'Personne.qual',
					'Personne.nomcomnai',
					'Adresse.locaadr',
					'Adresse.codepos',
					'Adressefoyer.rgadr',
					'Adresse.numcomptt'
				),
				'joins' => $joins,
				'contain' => false,
				'conditions' => $conditions
			);

			return $query;


		}
	}
?>