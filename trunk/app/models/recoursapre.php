<?php
	class Recoursapre extends AppModel
	{
		public $name = 'Recoursapre';

		public $useTable = false;

		/**
		*
		*/

		public function search( $avisRecours, $criteresrecours ) {

			/// Conditions de base
			$conditions = array(
			);

			if( !empty( $avisRecours ) ) {
				if( $avisRecours == 'Recoursapre::demande' ) {
					$conditions[] = 'ApreComiteapre.decisioncomite = \'REF\'';
					$conditions[] = 'ApreComiteapre.recoursapre IS NULL';
				}
				else {
					$conditions[] = 'ApreComiteapre.decisioncomite = \'REF\'';
					$conditions[] = 'ApreComiteapre.recoursapre IS NOT NULL';
					$conditions[] = 'ApreComiteapre.daterecours IS NOT NULL';
				}
			}

			///Criteres
			$matricule = Set::extract( $criteresrecours, 'Recoursapre.matricule' );
			$numeroapre = Set::extract( $criteresrecours, 'Recoursapre.numeroapre' );

			/// Critères sur le Comité - intitulé du comité
			if( isset( $criteresrecours['Cohortecomiteapre']['id'] ) && !empty( $criteresrecours['Cohortecomiteapre']['id'] ) ) {
				$conditions['Comiteapre.id'] = $criteresrecours['Cohortecomiteapre']['id'];
			}


			/// Critères sur les recours APRE - date de recours
			if( isset( $criteresrecours['Recoursapre']['datedemandeapre'] ) && !empty( $criteresrecours['Recoursapre']['datedemandeapre'] ) ) {
				$valid_from = ( valid_int( $criteresrecours['Recoursapre']['datedemandeapre_from']['year'] ) && valid_int( $criteresrecours['Recoursapre']['datedemandeapre_from']['month'] ) && valid_int( $criteresrecours['Recoursapre']['datedemandeapre_from']['day'] ) );
				$valid_to = ( valid_int( $criteresrecours['Recoursapre']['datedemandeapre_to']['year'] ) && valid_int( $criteresrecours['Recoursapre']['datedemandeapre_to']['month'] ) && valid_int( $criteresrecours['Recoursapre']['datedemandeapre_to']['day'] ) );
				if( $valid_from && $valid_to ) {
					$conditions[] = 'Apre.datedemandeapre BETWEEN \''.implode( '-', array( $criteresrecours['Recoursapre']['datedemandeapre_from']['year'], $criteresrecours['Recoursapre']['datedemandeapre_from']['month'], $criteresrecours['Recoursapre']['datedemandeapre_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteresrecours['Recoursapre']['datedemandeapre_to']['year'], $criteresrecours['Recoursapre']['datedemandeapre_to']['month'], $criteresrecours['Recoursapre']['datedemandeapre_to']['day'] ) ).'\'';
				}
			}

			/// Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
			$filtersPersonne = array();
			foreach( array( 'nom', 'prenom', 'nomnai', 'nir' ) as $criterePersonne ) {
				if( isset( $criteresrecours['Recoursapre'][$criterePersonne] ) && !empty( $criteresrecours['Recoursapre'][$criterePersonne] ) ) {
					$conditions[] = 'Personne.'.$criterePersonne.' ILIKE \''.$this->wildcard( $criteresrecours['Recoursapre'][$criterePersonne] ).'\'';
				}
			}

			// N° CAF
			if( !empty( $matricule ) ) {
				$conditions[] = 'Dossier.matricule ILIKE \'%'.Sanitize::clean( $matricule ).'%\'';
			}

			// N° APRE
			if( !empty( $numeroapre ) ) {
				$conditions[] = 'Apre.numeroapre ILIKE \'%'.Sanitize::clean( $numeroapre ).'%\'';
			}

           // Trouver la dernière demande RSA pour chacune des personnes du jeu de résultats
            if( $criteresrecours['Dossier']['dernier'] ) {
                $conditions[] = 'Dossier.id IN (
                    SELECT
                            dossiers.id
                        FROM personnes
                            INNER JOIN prestations ON (
                                personnes.id = prestations.personne_id
                                AND prestations.natprest = \'RSA\'
                            )
                            INNER JOIN foyers ON (
                                personnes.foyer_id = foyers.id
                            )
                            INNER JOIN dossiers ON (
                                dossiers.id = foyers.dossier_id
                            )
                        WHERE
                            prestations.rolepers IN ( \'DEM\', \'CJT\' )
                            AND (
                                (
                                    nir_correct( Personne.nir )
                                    AND nir_correct( personnes.nir )
                                    AND personnes.nir = Personne.nir
                                    AND personnes.dtnai = Personne.dtnai
                                )
                                OR
                                (
                                    personnes.nom = Personne.nom
                                    AND personnes.prenom = Personne.prenom
                                    AND personnes.dtnai = Personne.dtnai
                                )
                            )
                        ORDER BY dossiers.dtdemrsa DESC
                        LIMIT 1
                )';
            }


			/// Requête
			$this->Dossier = ClassRegistry::init( 'Dossier' );

			$query = array(
				'fields' => array(
					'"Comiteapre"."id"',
					'"Comiteapre"."datecomite"',
					'"Comiteapre"."heurecomite"',
					'"Comiteapre"."lieucomite"',
					'"Comiteapre"."intitulecomite"',
					'"Comiteapre"."observationcomite"',
					'"ApreComiteapre"."id"',
					'"ApreComiteapre"."apre_id"',
					'"ApreComiteapre"."comiteapre_id"',
					'"ApreComiteapre"."decisioncomite"',
					'"ApreComiteapre"."montantattribue"',
					'"ApreComiteapre"."observationcomite"',
					'"ApreComiteapre"."recoursapre"',
					'"ApreComiteapre"."daterecours"',
					'"ApreComiteapre"."observationrecours"',
					'"Dossier"."numdemrsa"',
					'"Dossier"."matricule"',
					'"Personne"."qual"',
					'"Personne"."nom"',
					'"Personne"."prenom"',
					'"Personne"."dtnai"',
					'"Personne"."nir"',
					'"Adresse"."locaadr"',
					'"Adresse"."codepos"',
					'"Apre"."id"',
					'"Apre"."datedemandeapre"',
//                     '"Apre"."statutapre"',
					'"Apre"."numeroapre"',
					'"Apre"."mtforfait"',
//                     '"Apre"."montantattribue"',
				),
				'recursive' => -1,
				'joins' => array(
					array(
						'table'      => 'comitesapres',
						'alias'      => 'Comiteapre',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'ApreComiteapre.comiteapre_id = Comiteapre.id' )
					),
					array(
						'table'      => 'apres',
						'alias'      => 'Apre',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'ApreComiteapre.apre_id = Apre.id' )
					),
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.id = Apre.personne_id' )
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
						'table'      => 'adressesfoyers',
						'alias'      => 'Adressefoyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Foyer.id = Adressefoyer.foyer_id', 'Adressefoyer.rgadr = \'01\'' )
					),
					array(
						'table'      => 'adresses',
						'alias'      => 'Adresse',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
					)
				),
				'order' => array( '"ApreComiteapre"."daterecours" ASC' ),
				'conditions' => $conditions
			);

			return $query;
		}
	}
?>