<?php

    App::import( 'Sanitize' );

    class Repddtefp2 extends AppModel{

        var $name = 'Repddtefp2';
        var $useTable = false;


        /**
        *
        */

        function _query( $sql ) {
            $results = $this->query( $sql );
            return Set::classicExtract( $results, '{n}.0' );
        }

        /**
        *   Donnéees concernant le 1er tableau de reporting (DDTEFP)
        **/

        /**
        *
        */
        function _conditionsTemporelles( $annee, $semestre ) {
            if( $semestre == 1 ) {
                $range = array( 1, 2 );
            }
            else if( $semestre == 2 ) {
                $range = array( 3, 4 );
            }
            else {
                // FIXME: throw error
            }
            return 'EXTRACT(YEAR FROM apres.datedemandeapre) = '.$annee.' AND EXTRACT( QUARTER FROM apres.datedemandeapre ) IN ('.implode( ',', $range ).')';
        }

        /**
        *
        */

        function _nbrPersonnesInstruitesParSexe( $annee, $semestre, $sexe, $numcomptt ) {
            $sql = 'SELECT ( CASE WHEN ( EXTRACT( DAY FROM apres.datedemandeapre ) <= 15 ) THEN 1 ELSE 2 END ) AS quinzaine, EXTRACT(MONTH FROM apres.datedemandeapre) AS mois, EXTRACT(YEAR FROM apres.datedemandeapre) AS annee, COUNT(apres.*) AS indicateur
                        FROM apres
                            INNER JOIN personnes ON personnes.id = apres.personne_id
                            INNER JOIN foyers ON personnes.foyer_id = foyers.id
                            INNER JOIN adresses_foyers ON ( adresses_foyers.foyer_id = foyers.id AND adresses_foyers.rgadr = \'01\' )
                            INNER JOIN adresses ON adresses_foyers.adresse_id = adresses.id
                        WHERE '.$this->_conditionsTemporelles( $annee, $semestre ).'
                            AND personnes.sexe = \''.$sexe.'\'
                            AND adresses.numcomptt ILIKE \'%'.Sanitize::clean( $numcomptt ).'%\'
                        GROUP BY annee, mois, quinzaine
                        ORDER BY annee, mois, quinzaine;';

            $results = $this->_query( $sql );
            return $results;
        }

        /**
        *
        */

        function _nbrPersonnesInstruitesParTrancheDAge( $annee, $semestre, $ageMin, $ageMax, $numcomptt ) {
            $sql = 'SELECT ( CASE WHEN ( EXTRACT( DAY FROM apres.datedemandeapre ) <= 15 ) THEN 1 ELSE 2 END ) AS quinzaine, EXTRACT(MONTH FROM apres.datedemandeapre) AS mois, EXTRACT(YEAR FROM apres.datedemandeapre) AS annee, COUNT(apres.*) AS indicateur
                        FROM apres
                            INNER JOIN personnes ON personnes.id = apres.personne_id
                            INNER JOIN foyers ON personnes.foyer_id = foyers.id
                            INNER JOIN adresses_foyers ON ( adresses_foyers.foyer_id = foyers.id AND adresses_foyers.rgadr = \'01\' )
                            INNER JOIN adresses ON adresses_foyers.adresse_id = adresses.id
                        WHERE '.$this->_conditionsTemporelles( $annee, $semestre ).'
                            AND ( EXTRACT ( YEAR FROM AGE( personnes.dtnai ) ) ) BETWEEN '.$ageMin.' AND '.$ageMax.'
                            AND adresses.numcomptt ILIKE \'%'.Sanitize::clean( $numcomptt ).'%\'
                        GROUP BY annee, mois, quinzaine
                        ORDER BY annee, mois, quinzaine;';

            $results = $this->_query( $sql );
            return $results;
        }

        /**
        *
        */
        function listeSexe( $annee, $semestre, $numcomptt ) {
            $results['nbrHommesInstruits'] = $this->_nbrPersonnesInstruitesParSexe( $annee, $semestre, 1, $numcomptt );
            $results['nbrFemmesInstruits'] = $this->_nbrPersonnesInstruitesParSexe( $annee, $semestre, 2, $numcomptt );
            return $results;
        }

        /**
        *
        */
        function listeAge( $annee, $semestre, $numcomptt ) {
            $results['nbr0_24Instruits'] = $this->_nbrPersonnesInstruitesParTrancheDAge( $annee, $semestre, 0, 24, $numcomptt );
            $results['nbr25_34Instruits'] = $this->_nbrPersonnesInstruitesParTrancheDAge( $annee, $semestre, 25, 34, $numcomptt );
            $results['nbr35_44Instruits'] = $this->_nbrPersonnesInstruitesParTrancheDAge( $annee, $semestre, 35, 44, $numcomptt );
            $results['nbr45_54Instruits'] = $this->_nbrPersonnesInstruitesParTrancheDAge( $annee, $semestre, 45, 54, $numcomptt );
            $results['nbr55_59Instruits'] = $this->_nbrPersonnesInstruitesParTrancheDAge( $annee, $semestre, 55, 59, $numcomptt );
            $results['nbr60_200Instruits'] = $this->_nbrPersonnesInstruitesParTrancheDAge( $annee, $semestre, 60, 200, $numcomptt );
            return $results;
        }

		/**
		*
		*/

		function _conditionsApresEtatsliquidatifs( $criteresrepddtefp ) {
			$conditionsApresEtatsliquidatifs = array(
				'etatsliquidatifs.datecloture IS NOT NULL'
			);

            $annee = Set::classicExtract( $criteresrepddtefp, 'Repddtefp.annee' );
            $mois = Set::classicExtract( $criteresrepddtefp, 'Repddtefp.mois.month' );
            $quinzaine = Set::classicExtract( $criteresrepddtefp, 'Repddtefp.quinzaine' );
            $statutapre = Set::classicExtract( $criteresrepddtefp, 'Repddtefp.statutapre' );

            /// Année de demande APRE
            if( !empty( $annee ) ) {
                $conditionsApresEtatsliquidatifs[] = 'EXTRACT(YEAR FROM etatsliquidatifs.datecloture ) = '.$annee;
            }

            /// Mois de demande APRE
            if( !empty( $mois ) ) {
                $conditionsApresEtatsliquidatifs[] = 'EXTRACT(MONTH FROM etatsliquidatifs.datecloture ) = '.$mois;
            }

            /// Quinzaine du mois de demande APRE
            if( !empty( $quinzaine ) ) {
                if( $quinzaine == 1 ) {
                    $conditionsApresEtatsliquidatifs[] = 'EXTRACT( DAY FROM etatsliquidatifs.datecloture ) < 15';
                }
                else if( $quinzaine == 2 ) {
                    $conditionsApresEtatsliquidatifs[] = 'EXTRACT( DAY FROM etatsliquidatifs.datecloture ) >= 15';
                }
            }

            /// Statut de l'APRE
            if( !empty( $statutapre ) ) {
                $conditionsApresEtatsliquidatifs[] = 'etatsliquidatifs.typeapre = \''.( $statutapre == 'C' ? 'complementaire' : 'forfaitaire' ).'\'';
            }

			return $conditionsApresEtatsliquidatifs;
		}

        /**
        *   Critères de recherche envoyés par le contrôleur + jointure
        */

        function _queryData( $criteresrepddtefp ) {
            /// Conditions de base
            $conditions = array(
				'"Apre"."id" IN (
						SELECT apres_etatsliquidatifs.apre_id
							FROM apres_etatsliquidatifs
							WHERE apres_etatsliquidatifs.etatliquidatif_id IN (
								SELECT etatsliquidatifs.id
									FROM etatsliquidatifs
									WHERE '.implode( ' AND ', $this->_conditionsApresEtatsliquidatifs( $criteresrepddtefp ) ).'
							)
							AND (
								"Apre"."statutapre" = \'F\'
								OR (
									"Apre"."statutapre" = \'C\'
									AND apres_etatsliquidatifs.montantattribue IS NOT NULL
								)
							)
				)',
// 				'"Apre"."etatdossierapre" = \'COM\''
            );

            $numcomptt = Set::classicExtract( $criteresrepddtefp, 'Repddtefp.numcomptt' );

            /// Localité adresse
            if( !empty( $numcomptt ) ) {
                $conditions[] = 'Adresse.numcomptt ILIKE \'%'.Sanitize::clean( $numcomptt ).'%\'';
            }

            $queryData = array(
                'recursive' => -1,
                'joins' => array(
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
                        'table'      => 'dossiers_rsa',
                        'alias'      => 'Dossier',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Foyer.dossier_rsa_id = Dossier.id' )
                    ),
                    array(
                        'table'      => 'prestations',
                        'alias'      => 'Prestation',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array(
                            'Personne.id = Prestation.personne_id',
                            'Prestation.natprest = \'RSA\''/*,
                            'Prestation.rolepers IN ( \'DEM\', \'CJT\' )',*/ // FIXME ??!
                        )
                    ),
                    array(
                        'table'      => 'adresses_foyers',
                        'alias'      => 'Adressefoyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array(
							'Foyer.id = Adressefoyer.foyer_id',
							'Adressefoyer.rgadr = \'01\'',
							'Adressefoyer.id IN '.ClassRegistry::init( 'Adressefoyer' )->sqlFoyerActuelUnique()
						),
                    ),
                    array(
                        'table'      => 'adresses',
                        'alias'      => 'Adresse',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
                    )
                ),
                'conditions' => $conditions
            );

            $this->Apre =& ClassRegistry::init( 'Apre' );

            foreach( $this->Apre->aidesApre as $modelAide ) {
                $queryData['joins'][] = array(
                    'table'      => Inflector::tableize( $modelAide ),
                    'alias'      => $modelAide,
                    'type'       => 'LEFT OUTER',
                    'foreignKey' => false,
                    'conditions' => array( "Apre.id = {$modelAide}.apre_id" )
                );
            }

            return $queryData;
        }

        /**
        * Champ calculé des aides effectivement versées
        */

        function _apreMontantAidesVersees( $criteresrepddtefp ) {
			return '(
					SELECT SUM( apres_etatsliquidatifs.montantattribue )
						FROM apres_etatsliquidatifs
						WHERE apres_etatsliquidatifs.apre_id = "Apre"."id"
							AND apres_etatsliquidatifs.etatliquidatif_id IN (
								SELECT etatsliquidatifs.id
									FROM etatsliquidatifs
									WHERE '.implode( ' AND ', $this->_conditionsApresEtatsliquidatifs( $criteresrepddtefp ) ).'
							)
				)';
        }

		/**
		*
		*/

		function _conditionsApresEtatsliquidatifs2( $criteresrepddtefp ) {
			$conditionsApresEtatsliquidatifs = array(
				'Etatliquidatif.datecloture IS NOT NULL'
			);

            $annee = Set::classicExtract( $criteresrepddtefp, 'Repddtefp.annee' );
            $mois = Set::classicExtract( $criteresrepddtefp, 'Repddtefp.mois.month' );
            $quinzaine = Set::classicExtract( $criteresrepddtefp, 'Repddtefp.quinzaine' );
            $statutapre = Set::classicExtract( $criteresrepddtefp, 'Repddtefp.statutapre' );

            /// Année de demande APRE
            if( !empty( $annee ) ) {
                $conditionsApresEtatsliquidatifs[] = 'EXTRACT(YEAR FROM Etatliquidatif.datecloture ) = '.$annee;
            }

            /// Mois de demande APRE
            if( !empty( $mois ) ) {
                $conditionsApresEtatsliquidatifs[] = 'EXTRACT(MONTH FROM Etatliquidatif.datecloture ) = '.$mois;
            }

            /// Quinzaine du mois de demande APRE
            if( !empty( $quinzaine ) ) {
                if( $quinzaine == 1 ) {
                    $conditionsApresEtatsliquidatifs[] = 'EXTRACT( DAY FROM Etatliquidatif.datecloture ) < 15';
                }
                else if( $quinzaine == 2 ) {
                    $conditionsApresEtatsliquidatifs[] = 'EXTRACT( DAY FROM Etatliquidatif.datecloture ) >= 15';
                }
            }

            /// Statut de l'APRE
            if( !empty( $statutapre ) ) {
                $conditionsApresEtatsliquidatifs[] = 'Etatliquidatif.typeapre = \''.( $statutapre == 'C' ? 'complementaire' : 'forfaitaire' ).'\'';
            }

			return $conditionsApresEtatsliquidatifs;
		}

		/**
		*
		*/

		public function search2( $criteresrepddtefp ) {
            $queryData = $this->_queryData( $criteresrepddtefp );

            /// Conditions de base
            $conditions = array(
				'(
					( "Apre"."statutapre" = \'F\' )
					OR ( "Apre"."statutapre" = \'C\' AND "ApreEtatliquidatif"."montantattribue" IS NOT NULL )
				)',
				$this->_conditionsApresEtatsliquidatifs2( $criteresrepddtefp )
            );

            /// Localité adresse
			$numcomptt = Set::classicExtract( $criteresrepddtefp, 'Repddtefp.numcomptt' );
            if( !empty( $numcomptt ) ) {
                $conditions[] = 'Adresse.numcomptt ILIKE \'%'.Sanitize::clean( $numcomptt ).'%\'';
            }

			$joins = array(
				array(
					'table'      => 'apres_etatsliquidatifs',
					'alias'      => 'ApreEtatliquidatif',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Etatliquidatif.id = ApreEtatliquidatif.etatliquidatif_id' )
				),
				array(
					'table'      => 'apres',
					'alias'      => 'Apre',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'ApreEtatliquidatif.apre_id = Apre.id' )
				),
			);

			foreach( $queryData['joins'] as $join ) {
				$joins[] = $join;
			}

			$queryData['joins'] = $joins;

			$queryData['conditions'] = $conditions;

            $queryData['fields'] = array(
                '"Apre"."id"',
                '"Apre"."datedemandeapre"',
                '"Apre"."mtforfait"',
                '"Apre"."montantaverser"',
                '"Apre"."montantdejaverse"',
                '"Apre"."statutapre"',
                '"Apre"."activitebeneficiaire"',
                '"Apre"."secteuractivite"',
                '"Dossier"."numdemrsa"',
                '"Dossier"."matricule"',
                '"Personne"."id"',
                '"Personne"."qual"',
                '"Personne"."nom"',
                '"Personne"."prenom"',
                '"Personne"."dtnai"',
                '"Personne"."sexe"',
                '"Personne"."nir"',
                '"Adresse"."locaadr"',
                '"Adresse"."codepos"',
                '"Adresse"."numcomptt"',
				$this->_apreMontantAidesVersees( $criteresrepddtefp ).' AS "Apre__montantaides"'
            );

            $queryData['order'] = array(
				'Personne.nom',
				'Personne.prenom',
            );

            return $queryData;
		}

		/**
		*
		*/

        public function detailsEnveloppe2( $criteresrepddtefp ) {
            $result = array();
            $this->Etatliquidatif =& ClassRegistry::init( 'Etatliquidatif' );
            $queryData = $this->search2( $criteresrepddtefp );

            foreach( array( null, 'C', 'F' ) AS $statut ) {
                $queryDataTmp = $queryData;
                $suffix = '';
                $conditions = array();
                if( !empty( $statut ) ) {
                    $suffix = strtolower( "_{$statut}" );
                    $conditions = array( "Apre.statutapre = '{$statut}'" );
                }
                $queryDataTmp['conditions'] = Set::merge( $queryDataTmp['conditions'], $conditions );
				unset( $queryDataTmp['order'] );


                // Montants consommés
                $fieldTotal = array();
                foreach( $this->Etatliquidatif->Apre->aidesApre as $modelAide ) {
                    $fieldTotal[] = "\"{$modelAide}\".\"montantaide\"";
                }

				$queryDataTmp['fields'] = array(
					'COUNT( * ) AS "Etatliquidatif__nbrresultats"',
					'COUNT( DISTINCT ( "Apre"."id" ) ) AS "Etatliquidatif__nbrapres"',
					'COUNT( DISTINCT( "Personne"."id" ) ) AS "Etatliquidatif__nbrpersonnes"',
					'SUM( COALESCE( "Apre"."mtforfait", 0 ) + COALESCE( '.$this->_apreMontantAidesVersees( $criteresrepddtefp ).', 0 ) ) AS "Etatliquidatif__montantconsomme"'
				);

				$tmpResult = $this->Etatliquidatif->find( 'all', $queryDataTmp );
                $result[( empty( $statut ) ? 'A' : $statut )] = Set::classicExtract( $tmpResult, '0.Etatliquidatif' );
            }

            return $result;
        }
    }
?>