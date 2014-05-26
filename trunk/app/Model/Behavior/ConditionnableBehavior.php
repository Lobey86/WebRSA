<?php
	/**
	 * Fichier source de la classe ConditionnableBehavior.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model.Behavior
	 */
	App::uses( 'Sanitize', 'Utility' );

	/**
	 * Ce behavior permet de transformer les critères d'un filtre de recherche en conditions pour les queryData
	 * d'une requête CakePHP pour le projet WebRSA
	 *
	 * @package app.Model.Behavior
	 */
	class ConditionnableBehavior extends ModelBehavior
	{
		/**
		 * Filtre par Adresse pour les moteurs de recherche.
		 *
		 * @param Model $model
		 * @param array $conditions
		 * @param array $search
		 * @param boolean $filtre_zone_geo
		 * @param array $mesCodesInsee
		 * @return array
		 */
		public function conditionsAdresse( Model $model, $conditions, $search, $filtre_zone_geo = false, $mesCodesInsee = array() ) {
			$CantonModel = ClassRegistry::init( 'Canton' );

			/// Critères sur l'adresse - nom de commune
            foreach( array( 'locaadr', 'nomvoie' ) as $filtre ) {
                if( isset( $search['Adresse'][$filtre] ) && !empty( $search['Adresse'][$filtre] ) ) {
    // 				$conditions[] = "Adresse.locaadr ILIKE '%".Sanitize::clean( $search['Adresse']['locaadr'], array( 'encode' => false ) )."%'";
                    $conditions[] = "Adresse.$filtre ILIKE '".$model->wildcard( Sanitize::clean( $search['Adresse'][$filtre], array( 'encode' => false ) ) )."'";
                }
            }

			/// Critères sur l'adresse - code insee
			if( isset( $search['Adresse']['numcomptt'] ) && !empty( $search['Adresse']['numcomptt'] ) ) {
				$numcomptt = Sanitize::clean( trim( $search['Adresse']['numcomptt'] ), array( 'encode' => false ) );
				if( strlen( $numcomptt ) == 5 ) {
					$conditions[] = "Adresse.numcomptt = '{$numcomptt}'";
				}
				else {
					$conditions[] = "Adresse.numcomptt ILIKE '%{$numcomptt}%'";
				}
			}

			/// Critères sur l'adresse - canton
			if( Configure::read( 'CG.cantons' ) ) {
				if( isset( $search['Canton']['canton'] ) && !empty( $search['Canton']['canton'] ) ) {
					$conditions[] = $CantonModel->queryConditions( $search['Canton']['canton'] );
				}
			}

			// Critère sur le site COV (CG 58)
			if( Configure::read( 'Cg.departement' ) == 58 ) {
				$sitecov58_id = Hash::get( $search, 'Sitecov58.id' );

				if( !empty( $sitecov58_id ) ) {
					$Sitecov58 = ClassRegistry::init( 'Sitecov58' );

					$sq = $Sitecov58->Sitecov58Zonegeographique->sq(
						array(
							'alias' => 'sitescovs58_zonesgeographiques',
							'fields' => 'zonesgeographiques.codeinsee',
							'contain' => false,
							'joins' => array(
								array_words_replace(
									$Sitecov58->Sitecov58Zonegeographique->join( 'Zonegeographique', array( 'type' => 'INNER' ) ),
									array(
										'Sitecov58Zonegeographique' => 'sitescovs58_zonesgeographiques',
										'Zonegeographique' => 'zonesgeographiques'
									)
								)
							),
							'conditions' => array(
								'sitescovs58_zonesgeographiques.sitecov58_id' => $sitecov58_id
							)
						)
					);

					$conditions[] = "Adresse.numcomptt IN ( {$sq} )";
				}
			}

			/// Filtre zone géographique de l'utilisateur
			if( $filtre_zone_geo ) {
				// Si on utilise la table des cantons plutôt que la table zonesgeographiques
				if( Configure::read( 'CG.cantons' ) ) {
					$conditions[] = $CantonModel->queryConditionsByZonesgeographiques( array_keys( $mesCodesInsee ) );
				}
				else {
					$mesCodesInsee = ( !empty( $mesCodesInsee ) ? $mesCodesInsee : array( null ) );
					$conditions[] = '( Adresse.numcomptt IN ( \''.implode( '\', \'', $mesCodesInsee ).'\' ) )';
				}
			}

			return $conditions;
		}

		/**
		 * Filtres sur le Dossier: numdemrsa, matricule, dtdemrsa, fonorg
		 *
		 * @param Model $model
		 * @param array $conditions
		 * @param array $search
		 * @return array
		 */
		public function conditionsDossier( Model $model, $conditions, $search ) {
			foreach( array( 'numdemrsa', 'matricule' ) as $critereDossier ) {
				if( isset( $search['Dossier'][$critereDossier] ) && !empty( $search['Dossier'][$critereDossier] ) ) {
					$conditions[] = 'Dossier.'.$critereDossier.' ILIKE \''.$model->wildcard( "*{$search['Dossier'][$critereDossier]}*" ).'\'';
				}
			}

			foreach( array( 'dtdemrsa' ) as $critereDossier ) {
				if( isset( $search['Dossier'][$critereDossier] )  ) {
					if( is_array( $search['Dossier'][$critereDossier] ) && !empty( $search['Dossier'][$critereDossier]['day'] ) && !empty( $search['Dossier'][$critereDossier]['month'] ) && !empty( $search['Dossier'][$critereDossier]['year'] ) ) {
						$conditions["Dossier.{$critereDossier}"] = "{$search['Dossier'][$critereDossier]['year']}-{$search['Dossier'][$critereDossier]['month']}-{$search['Dossier'][$critereDossier]['day']}";
					}
					else if( ( is_int( $search['Dossier'][$critereDossier] ) || is_bool( $search['Dossier'][$critereDossier] ) || ( $search['Dossier'][$critereDossier] == '1' ) ) && isset( $search['Dossier']['dtdemrsa_from'] ) && isset( $search['Dossier']['dtdemrsa_to'] ) ) {
						$search['Dossier']['dtdemrsa_from'] = $search['Dossier']['dtdemrsa_from']['year'].'-'.$search['Dossier']['dtdemrsa_from']['month'].'-'.$search['Dossier']['dtdemrsa_from']['day'];
						$search['Dossier']['dtdemrsa_to'] = $search['Dossier']['dtdemrsa_to']['year'].'-'.$search['Dossier']['dtdemrsa_to']['month'].'-'.$search['Dossier']['dtdemrsa_to']['day'];

						$conditions[] = 'Dossier.dtdemrsa BETWEEN \''.$search['Dossier']['dtdemrsa_from'].'\' AND \''.$search['Dossier']['dtdemrsa_to'].'\'';
					}
				}
			}

			if( isset( $search['Dossier']['fonorg'] ) && !empty( $search['Dossier']['fonorg'] ) ) {
				$conditions[] = array( 'Dossier.fonorg' => $search['Dossier']['fonorg'] );
			}

			$anciennete_dispositif = Hash::get( $search, 'Dossier.anciennete_dispositif' );
			if( !empty( $anciennete_dispositif ) ) {
				list( $min, $max ) = explode( '_', $anciennete_dispositif );
				$conditions[] = 'EXTRACT( YEAR FROM AGE( NOW(), "Dossier"."dtdemrsa" ) ) BETWEEN '.$min.' AND '.$max;
			}

			return $conditions;
		}

		/**
		 * Filtres sur le Foyer: sitfam, ddsitfam
		 *
		 * @param Model $model
		 * @param array $conditions
		 * @param array $search
		 * @return array
		 */
		public function conditionsFoyer( Model $model, $conditions, $search ) {
			foreach( array( 'sitfam' ) as $critere ) {
				if( isset( $search['Foyer'][$critere] ) && !empty( $search['Foyer'][$critere] ) ) {
					$conditions["Foyer.{$critere}"] = $search['Foyer'][$critere];
				}
			}

			foreach( array( 'ddsitfam' ) as $critere ) {
				if( isset( $search['Foyer'][$critere] ) && !empty( $search['Foyer'][$critere]['day'] ) && !empty( $search['Foyer'][$critere]['month'] ) && !empty( $search['Foyer'][$critere]['year'] ) ) {
					$conditions["Foyer.{$critere}"] = "{$search['Foyer'][$critere]['year']}-{$search['Foyer'][$critere]['month']}-{$search['Foyer'][$critere]['day']}";
				}
			}

			return $conditions;
		}

		/**
		 * Filtres sur la Situationdossierrsa: etatdosrsa
		 *
		 * @param Model $model
		 * @param array $conditions
		 * @param array $search
		 * @return array
		 */
		public function conditionsSituationdossierrsa( Model $model, $conditions, $search ) {
			$etatdossier = Set::extract( $search, 'Situationdossierrsa.etatdosrsa' );
			if( ( Hash::get( $search, 'Situationdossierrsa.etatdosrsa_choice' ) !== '0' ) && isset( $search['Situationdossierrsa']['etatdosrsa'] ) && !empty( $search['Situationdossierrsa']['etatdosrsa'] ) ) {
				$conditions[] = '( Situationdossierrsa.etatdosrsa IN ( \''.implode( '\', \'', $etatdossier ).'\' ) )';
			}

			return $conditions;
		}

		/**
		 * Filtres sur la Personne:  nom, prenom, nomnai, nir, dtnai
		 *
		 * @param Model $model
		 * @param array $conditions
		 * @param array $search
		 * @return array
		 */
		public function conditionsPersonne( Model $model, $conditions, $search ) {
			foreach( array( 'nom', 'prenom', 'nomnai', 'nir' ) as $criterePersonne ) {
				if( isset( $search['Personne'][$criterePersonne] ) && !empty( $search['Personne'][$criterePersonne] ) ) {
					$conditions[] = 'UPPER(Personne.'.$criterePersonne.') LIKE \''.$model->wildcard( strtoupper( replace_accents( $search['Personne'][$criterePersonne] ) ) ).'\'';
				}
			}

			foreach( array( 'sexe' ) as $critere ) {
				if( isset( $search['Personne'][$critere] ) && !empty( $search['Personne'][$critere] ) ) {
					$conditions["Personne.{$critere}"] = $search['Personne'][$critere];
				}
			}

			/// Critères sur une personne du foyer - date de naissance
			if( isset( $search['Personne']['dtnai'] ) && !empty( $search['Personne']['dtnai'] ) ) {
				if( valid_int( $search['Personne']['dtnai']['year'] ) ) {
					$conditions[] = 'EXTRACT(YEAR FROM Personne.dtnai) = '.$search['Personne']['dtnai']['year'];
				}
				if( valid_int( $search['Personne']['dtnai']['month'] ) ) {
					$conditions[] = 'EXTRACT(MONTH FROM Personne.dtnai) = '.$search['Personne']['dtnai']['month'];
				}
				if( valid_int( $search['Personne']['dtnai']['day'] ) ) {
					$conditions[] = 'EXTRACT(DAY FROM Personne.dtnai) = '.$search['Personne']['dtnai']['day'];
				}
			}

			// Voir si une sous-requête ne serait pas plus simple
			if( isset( $search['Personne']['trancheage'] ) ) {
				$trancheage = Hash::get( $search, 'Personne.trancheage' );

				if( !empty( $trancheage )  ) {
					list( $ageMin, $ageMax ) = explode( '_', $trancheage );
					$conditions[] = '( EXTRACT ( YEAR FROM AGE( Personne.dtnai ) ) ) BETWEEN '.$ageMin.' AND '.$ageMax;
				}
			}

			// Filtre par code activité
			$value = Hash::get( $search, 'Activite.act' );
			if( !empty( $value ) ) {
				$conditions['Activite.act'] = $value;
			}

			return $conditions;
		}

		/**
		 * Filtres sur le Detailcalculdroitrsa:  natpf
		 *
		 * @param Model $model
		 * @param array $conditions
		 * @param array $search
		 * @return array
		 */
		public function conditionsDetailcalculdroitrsa( Model $model, $conditions, $search ) {
			if( isset( $search['Detailcalculdroitrsa']['natpf'] ) && !empty( $search['Detailcalculdroitrsa']['natpf'] ) ) {
				if( !is_array( $search['Detailcalculdroitrsa']['natpf'] ) ) {
					if( strstr( $search['Detailcalculdroitrsa']['natpf'], ',' ) === false && strstr( $search['Detailcalculdroitrsa']['natpf'], '-' ) === false ) {
						$condition = 'Detaildroitrsa.id IN (
									SELECT detailscalculsdroitsrsa.detaildroitrsa_id
										FROM detailscalculsdroitsrsa
											INNER JOIN detailsdroitsrsa ON (
												detailscalculsdroitsrsa.detaildroitrsa_id = detailsdroitsrsa.id
											)
										WHERE
											detailsdroitsrsa.dossier_id = Dossier.id
											AND detailscalculsdroitsrsa.natpf = \''.Sanitize::clean( $search['Detailcalculdroitrsa']['natpf'], array( 'encode' => false ) ).'\'
								)';

						$conditions[] = $condition;
					}
					// Si -
					else if( strstr( $search['Detailcalculdroitrsa']['natpf'], '-' ) !== false ) {
						$natspfs = explode( '-', $search['Detailcalculdroitrsa']['natpf'] );
						$natpfOui = $natspfs[0];
						$natpfNon = $natspfs[1];

						$conditions = $this->conditionsDetailcalculdroitrsa(
							$model,
							$conditions,
							array(
								'Detailcalculdroitrsa' => array(
									'natpf' => $natpfOui
								)
							)
						);

						$conditionsNon = $this->conditionsDetailcalculdroitrsa(
							$model,
							array(),
							array(
								'Detailcalculdroitrsa' => array(
									'natpf' => $natpfNon
								)
							)
						);

						$conditions[] = array( 'NOT' => $conditionsNon );
					}
					else {
						$natspfs = explode( ',', $search['Detailcalculdroitrsa']['natpf'] );
						foreach( $natspfs as $natpf ) {
							$conditions = $this->conditionsDetailcalculdroitrsa(
								$model,
								$conditions,
								array(
									'Detailcalculdroitrsa' => array(
										'natpf' => $natpf
									)
								)
							);
						}
					}
				}
				else {
					$multipleEnd = false;
					foreach( $search['Detailcalculdroitrsa']['natpf'] as $natpf ) {
						if( strstr( $natpf, ',' ) !== false ) {
							$multipleEnd = true;
						}
					}

					if( !$multipleEnd ) {
						$condition = 'Detaildroitrsa.id IN (
									SELECT detailscalculsdroitsrsa.detaildroitrsa_id
										FROM detailscalculsdroitsrsa
											INNER JOIN detailsdroitsrsa ON (
												detailscalculsdroitsrsa.detaildroitrsa_id = detailsdroitsrsa.id
											)
										WHERE
											detailsdroitsrsa.dossier_id = Dossier.id
											AND detailscalculsdroitsrsa.natpf IN ( \''.implode( '\', \'', $search['Detailcalculdroitrsa']['natpf'] ).'\' )
								)';
						$conditions[] = $condition;
					}
					else {
						$conditionsMultiples = array();

						foreach( $search['Detailcalculdroitrsa']['natpf'] as $natpf ) {
							$conditionsMultiples[] = $this->conditionsDetailcalculdroitrsa(
								$model,
								array(),
								array(
									'Detailcalculdroitrsa' => array(
										'natpf' => $natpf
									)
								)
							);
						}

						$conditions[] = array( 'OR' => $conditionsMultiples );
					}
				}
			}

			return $conditions;
		}

		/**
		 * Filtres sur le Calculdroitrsa: toppersdrodevorsa
		 *
		 * @param Model $model
		 * @param array $conditions
		 * @param array $search
		 * @return array
		 */
		public function conditionsCalculdroitrsa( Model $model, $conditions, $search ) {
			if( isset( $search['Calculdroitrsa']['toppersdrodevorsa'] ) ) {
				if( is_numeric( $search['Calculdroitrsa']['toppersdrodevorsa'] ) ) {
					$conditions[] = array( 'Calculdroitrsa.toppersdrodevorsa' => $search['Calculdroitrsa']['toppersdrodevorsa'] );
				}
				else if( $search['Calculdroitrsa']['toppersdrodevorsa'] == 'NULL' ) {
					$conditions[] = array( 'Calculdroitrsa.toppersdrodevorsa IS NULL' );
				}
			}

			return $conditions;
		}

		/**
		 * Combinaison des filtres conditionsDossier, conditionsPersonne, conditionsFoyer,
		 * conditionsSituationdossierrsa, conditionsDetailcalculdroitrsa et
		 * conditionsCalculdroitrsa.
		 *
		 * @param Model $model
		 * @param array $conditions
		 * @param array $search
		 * @return array
		 */
		public function conditionsPersonneFoyerDossier( Model $model, $conditions, $search ) {
			$conditions = $this->conditionsDossier( $model, $conditions, $search );
			$conditions = $this->conditionsPersonne( $model, $conditions, $search );
			$conditions = $this->conditionsFoyer( $model, $conditions, $search );
			$conditions = $this->conditionsSituationdossierrsa( $model, $conditions, $search );
			$conditions = $this->conditionsDetailcalculdroitrsa( $model, $conditions, $search );
			$conditions = $this->conditionsCalculdroitrsa( $model, $conditions, $search );

			return $conditions;
		}

		/**
		 * Conditions permettant d'obtenir le dernier dossier pour un allocataire donné.
		 *
		 * Si dans la configuration, la clé Optimisations.useTableDernierdossierallocataire
		 * est à la valeur booléenne true, alors la table derniersdossiersallocataires
		 * sera utilisée, sinon on effectuera la sous-requête avec les jointures.
		 *
		 * @param Model $model
		 * @param array $conditions
		 * @param array $search
		 * @return array
		 */
		public function conditionsDernierDossierAllocataire( Model $model, $conditions, $search ) {
			if( isset( $search['Dossier']['dernier'] ) && $search['Dossier']['dernier'] ) {
				if( Configure::read( 'Optimisations.useTableDernierdossierallocataire' ) === true ) {
					$conditions[] = 'Dossier.id IN (
						SELECT
								derniersdossiersallocataires.dossier_id
							FROM derniersdossiersallocataires
							WHERE
								derniersdossiersallocataires.personne_id = Personne.id
					)';
				}
				else {
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
										nir_correct13( Personne.nir )
										AND nir_correct13( personnes.nir )
										AND SUBSTRING( TRIM( BOTH \' \' FROM personnes.nir ) FROM 1 FOR 13 ) = SUBSTRING( TRIM( BOTH \' \' FROM Personne.nir ) FROM 1 FOR 13 )
										AND personnes.dtnai = Personne.dtnai
									)
									OR
									(
										UPPER(personnes.nom) = UPPER(Personne.nom)
										AND UPPER(personnes.prenom) = UPPER(Personne.prenom)
										AND personnes.dtnai = Personne.dtnai
									)
								)
							ORDER BY dossiers.dtdemrsa DESC, dossiers.id DESC
							LIMIT 1
					)';
				}
			}

			return $conditions;
		}

		/**
		 * Ajoute des conditions sur des plages de dates. Pour chacun des $paths, on extrait le nom du
		 * modèle et le nom du champ; si un checkbox existe avec ce chemin-là, on cherchera une date
		 * située entre <chemin>_from (inclus) et <chemin>_to (exclus).
		 *
		 * Exemple:
		 * <pre>$this->conditionsDates(
		 *	$model,
		 *	array(),
		 *	array(
		 *		'Orientstruct' => array(
		 *			'date_valid' => true,
		 *			'date_valid_from' => array(
		 *				'year' => '2012',
		 *				'month' => '03',
		 *				'day' => '01'
		 *			),
		 *			'date_valid_to' => array(
		 *				'year' => '2012',
		 *				'month' => '03',
		 *				'day' => '02'
		 *			),
		 *		)
		 *	),
		 *	'Orientstruct.date_valid'
		 * );</pre>
		 * retournera
		 * <pre>array( '"Orientstruct"."date_valid" BETWEEN \'2012-03-01\' AND \'2012-03-02\'' )</pre>
		 *
		 * @see app/views/criteres/index.ctp
		 * @see app/views/cohortes/filtre.ctp
		 * @see Dossier.dtdemrsa, ...
		 *
		 * @param Model $model Le modèle auquel ce behavior est attaché
		 * @param array $conditions Les conditions déjà existantes
		 * @param array $search Les critères renvoyés par le formulaire de recherche
		 * @param mixed $paths Le chemin (ou les chemins) sur lesquels on cherche à appliquer ces filtres.
		 * @return array
		 */
		public function conditionsDates( Model $model, $conditions, $search, $paths ) {
			$paths = (array)$paths;

			if( !empty( $paths ) ) {
				foreach( $paths as $path ) {
					list( $modelName, $fieldName ) = model_field( $path );
					if( isset( $search[$modelName][$fieldName] ) && $search[$modelName][$fieldName] ) {
						$from = $search[$modelName]["{$fieldName}_from"];
						$to = $search[$modelName]["{$fieldName}_to"];

						$from = $from['year'].'-'.$from['month'].'-'.$from['day'];
						$to = $to['year'].'-'.$to['month'].'-'.$to['day'];

						$conditions[] = "DATE( {$modelName}.{$fieldName} ) BETWEEN '{$from}' AND '{$to}'";
					}
				}
			}

			return $conditions;
		}
	}
?>