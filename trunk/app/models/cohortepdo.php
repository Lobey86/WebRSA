<?php
	class Cohortepdo extends AppModel
	{
		public $name = 'Cohortepdo';

		public $useTable = false;

		/**
		*
		*/

		public function search( $statutValidationAvis, $mesCodesInsee, $filtre_zone_geo, $criterespdo, $lockedDossiers ) {
			$Situationdossierrsa = ClassRegistry::init( 'Situationdossierrsa' );

		/// Conditions de base
			$conditions = array( );

			if( !empty( $statutValidationAvis ) ) {
				if( $statutValidationAvis == 'Decisionpdo::nonvalide' ) {
					$conditions[] = 'Situationdossierrsa.etatdosrsa IN ( \''.implode( '\', \'', $Situationdossierrsa->etatAttente() ).'\' ) ';
					$conditions[] = 'Propopdo.user_id IS NULL';

				}
//                 else if( $statutValidationAvis == 'Decisionpdo::enattente' ) {
//                     $conditions[] = 'Situationdossierrsa.etatdosrsa IN ( \''.implode( '\', \'', $Situationdossierrsa->etatAttente() ).'\' ) ';
//                     $conditions[] = 'Propopdo.motifpdo = \'E\'';
//                 }
				else if( $statutValidationAvis == 'Decisionpdo::valide' ) {
					$conditions[] = 'Propopdo.user_id IS NOT NULL';
				}
			}

			/// Filtre zone géographique
			if( $filtre_zone_geo ) {
				$mesCodesInsee = ( !empty( $mesCodesInsee ) ? $mesCodesInsee : '0' );
				$conditions[] = 'Adresse.numcomptt IN ( \''.implode( '\', \'', $mesCodesInsee ).'\' )';
			}

			/// Dossiers lockés
			if( !empty( $lockedDossiers ) ) {
				$conditions[] = 'Dossier.id NOT IN ( '.implode( ', ', $lockedDossiers ).' )';
			}

			/// Critères
			$typepdo_id = Set::extract( $criterespdo, 'Cohortepdo.typepdo_id' );
			$decisionpdo_id = Set::extract( $criterespdo, 'Cohortepdo.decisionpdo_id' );
			$motifpdo = Set::extract( $criterespdo, 'Cohortepdo.motifpdo' );
			$datedecisionpdo = Set::extract( $criterespdo, 'Cohortepdo.datedecisionpdo' );
			$matricule = Set::extract( $criterespdo, 'Cohortepdo.matricule' );
			$numcomptt = Set::extract( $criterespdo, 'Cohortepdo.numcomptt' );
			$gestionnaire = Set::extract( $criterespdo, 'Cohortepdo.user_id' );

			// Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
			$filtersPersonne = array();
			foreach( array( 'nom', 'prenom', 'nomnai' ) as $criterePersonne ) {
				if( isset( $criterespdo['Cohortepdo'][$criterePersonne] ) && !empty( $criterespdo['Cohortepdo'][$criterePersonne] ) ) {
					$conditions[] = 'Personne.'.$criterePersonne.' ILIKE \''.$this->wildcard( replace_accents( $criterespdo['Cohortepdo'][$criterePersonne] ) ).'\'';
				}
			}

			// Type de PDO
			if( !empty( $typepdo_id ) ) {
				$conditions[] = 'Propopdo.typepdo_id = \''.$typepdo_id.'\'';
			}

			// Motif de la PDO
			if( !empty( $motifpdo ) ) {
				$conditions[] = 'Propopdo.motifpdo ILIKE \'%'.Sanitize::clean( $motifpdo ).'%\'';
			}

			// N° CAF
			if( !empty( $matricule ) ) {
				$conditions[] = 'Dossier.matricule ILIKE \'%'.Sanitize::clean( $matricule ).'%\'';
			}

			// Commune au sens INSEE
			if( !empty( $numcomptt ) ) {
				$conditions[] = 'Adresse.numcomptt ILIKE \'%'.Sanitize::clean( $numcomptt ).'%\'';
			}

			/// Critères sur l'adresse - canton
			if( Configure::read( 'CG.cantons' ) ) {
				if( isset( $criterespdo['Canton']['canton'] ) && !empty( $criterespdo['Canton']['canton'] ) ) {
					$this->Canton = ClassRegistry::init( 'Canton' );
					$conditions[] = $this->Canton->queryConditions( $criterespdo['Canton']['canton'] );
				}
			}

			// Décision CG
			if( !empty( $decisionpdo_id ) ) {
				$conditions[] = 'Propopdo.decisionpdo_id = \''.$decisionpdo_id.'\'';
			}

			// Décision CG
			if( !empty( $gestionnaire ) ) {
				$conditions[] = 'Propopdo.user_id = \''.$gestionnaire.'\'';
			}

			/// Critères sur les PDOs - date de décision
			if( isset( $criterespdo['Cohortepdo']['datedecisionpdo'] ) && !empty( $criterespdo['Cohortepdo']['datedecisionpdo'] ) ) {
				$valid_from = ( valid_int( $criterespdo['Cohortepdo']['datedecisionpdo_from']['year'] ) && valid_int( $criterespdo['Cohortepdo']['datedecisionpdo_from']['month'] ) && valid_int( $criterespdo['Cohortepdo']['datedecisionpdo_from']['day'] ) );
				$valid_to = ( valid_int( $criterespdo['Cohortepdo']['datedecisionpdo_to']['year'] ) && valid_int( $criterespdo['Cohortepdo']['datedecisionpdo_to']['month'] ) && valid_int( $criterespdo['Cohortepdo']['datedecisionpdo_to']['day'] ) );
				if( $valid_from && $valid_to ) {
					$conditions[] = 'Propopdo.datedecisionpdo BETWEEN \''.implode( '-', array( $criterespdo['Cohortepdo']['datedecisionpdo_from']['year'], $criterespdo['Cohortepdo']['datedecisionpdo_from']['month'], $criterespdo['Cohortepdo']['datedecisionpdo_from']['day'] ) ).'\' AND \''.implode( '-', array( $criterespdo['Cohortepdo']['datedecisionpdo_to']['year'], $criterespdo['Cohortepdo']['datedecisionpdo_to']['month'], $criterespdo['Cohortepdo']['datedecisionpdo_to']['day'] ) ).'\'';
				}
			}

			$query = array(
				'fields' => array(
					'"Personne"."id"',
					'"Personne"."nom"',
					'"Personne"."prenom"',
					'"Personne"."dtnai"',
					'"Personne"."nir"',
					'"Personne"."qual"',
					'"Propopdo"."user_id"',
					'"Dossier"."id"',
					'"Dossier"."numdemrsa"',
					'"Dossier"."dtdemrsa"',
					'"Dossier"."matricule"',
					'"Personne"."nomcomnai"',
					'"Adresse"."locaadr"',
					'"Adresse"."codepos"',
					'"Adresse"."numcomptt"',
					'"Situationdossierrsa"."etatdosrsa"',
				),
				'joins' => array(
					array(
						'table'      => 'foyers',
						'alias'      => 'Foyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Foyer.id = Personne.foyer_id' )
					),
					array(
						'table'      => 'propospdos',
						'alias'      => 'Propopdo',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Propopdo.personne_id = Personne.id' )
					),
					array(
						'table'      => 'adressesfoyers',
						'alias'      => 'Adressefoyer',
						'type'       => 'LEFT OUTER',
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
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
					),
					array(
						'table'      => 'dossiers',
						'alias'      => 'Dossier',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Dossier.id = Foyer.dossier_id' )
					),
					array(
						'table'      => 'situationsdossiersrsa',
						'alias'      => 'Situationdossierrsa',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Situationdossierrsa.dossier_id = Dossier.id'
						)
					),
					array(
						'table'      => 'prestations',
						'alias'      => 'Prestation',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id = Prestation.personne_id',
							'Prestation.natprest = \'RSA\'',
//                             '( Prestation.natprest = \'RSA\' OR Prestation.natprest = \'PFA\' )',
							'( Prestation.rolepers = \'DEM\' OR Prestation.rolepers = \'CJT\' )',
						)
					),
				),
				'recursive' => -1,
				'conditions' => $conditions,
				'order' => array( '"Personne"."nom"' ),
//                 'limit' => $_limit
			);

			return $query;
		}
	}
?>
