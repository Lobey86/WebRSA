<?php
	/**
	* ...
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.models
	*/

	App::import( array( 'Model', 'Historiqueetatpe' ) );

	class Nonrespectsanctionep93 extends AppModel
	{
		public $name = 'Nonrespectsanctionep93';

		public $recursive = -1;

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'origine',
					'decision' => array( 'domain' => 'decisionnonrespectsanctionep93' ),
				)
			),
			'Autovalidate',
			'ValidateTranslate',
			'Formattable',
			'Gedooo'
		);

		public $belongsTo = array(
			'Dossierep' => array(
				'className' => 'Dossierep',
				'foreignKey' => 'dossierep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Orientstruct' => array(
				'className' => 'Orientstruct',
				'foreignKey' => 'orientstruct_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Contratinsertion' => array(
				'className' => 'Contratinsertion',
				'foreignKey' => 'contratinsertion_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Propopdo' => array(
				'className' => 'Propopdo',
				'foreignKey' => 'propopdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

		public $hasMany = array(
			'Relancenonrespectsanctionep93' => array(
				'className' => 'Relancenonrespectsanctionep93',
				'foreignKey' => 'nonrespectsanctionep93_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
// 			'Decisionnonrespectsanctionep93' => array(
// 				'className' => 'Decisionnonrespectsanctionep93',
// 				'foreignKey' => 'nonrespectsanctionep93_id',
// 				'dependent' => true,
// 				'conditions' => '',
// 				'fields' => '',
// 				'order' => '',
// 				'limit' => '',
// 				'offset' => '',
// 				'exclusive' => '',
// 				'finderQuery' => '',
// 				'counterQuery' => ''
// 			),
		);

		/**
		* INFO: Fonction inutile pour cette thématique donc elle retourne simplement true
		*/

		public function verrouiller( $commissionep_id, $etape ) {
			return true;
		}

		/**
		* Querydata permettant d'obtenir les dossiers qui doivent être traités
		* par liste pour la thématique de ce modèle.
		*
		* TODO: une autre liste pour avoir un tableau permettant d'accéder à la fiche
		* TODO: que ceux avec accord, les autres en individuel
		*
		* @param integer $commissionep_id L'id technique de la séance d'EP
		* @param string $niveauDecision Le niveau de décision ('ep' ou 'cg') pour
		*	lequel il faut les dossiers à passer par liste.
		* @return array
		* @access public
		*/

		public function qdDossiersParListe( $commissionep_id, $niveauDecision ) {
			// Doit-on prendre une décision à ce niveau ?
			$themes = $this->Dossierep->Passagecommissionep->Commissionep->themesTraites( $commissionep_id );
			$niveauFinal = $themes[Inflector::underscore($this->alias)];
			if( ( $niveauFinal == 'ep' ) && ( $niveauDecision == 'cg' ) ) {
				return array();
			}

			return array(
				'conditions' => array(
					'Dossierep.themeep' => Inflector::tableize( $this->alias ),
					//'Dossierep.commissionep_id' => $commissionep_id
					'Dossierep.id IN ( '.
						$this->Dossierep->Passagecommissionep->sq(
							array(
								'fields' => array(
									'passagescommissionseps.dossierep_id'
								),
								'alias' => 'passagescommissionseps',
								'conditions' => array(
									'passagescommissionseps.commissionep_id' => $commissionep_id
								)
							)
						)
					.' )'
				),
				'contain' => array(
					'Personne' => array(
						'Foyer' => array(
							'fields' => array(
								'id',
								'dossier_id',
								'sitfam',
								'ddsitfam',
								'typeocclog',
								'mtvallocterr',
								'mtvalloclog',
								'contefichliairsa',
								'mtestrsa',
								'raisoctieelectdom',
								"( SELECT COUNT(DISTINCT(personnes.id)) FROM personnes INNER JOIN prestations ON ( personnes.id = prestations.personne_id ) WHERE personnes.foyer_id = \"Foyer\".\"id\" AND prestations.natprest = 'RSA' AND prestations.rolepers = 'ENF' ) AS \"Foyer__nbenfants\"",
							),
							'Adressefoyer' => array(
								'conditions' => array(
									'Adressefoyer.rgadr' => '01'
								),
								'Adresse'
							)
						)
					),
					$this->alias => array(
						'fields' => array(
							'id',
							'dossierep_id',
							'propopdo_id',
							'orientstruct_id',
							'contratinsertion_id',
							'origine',
							'rgpassage',
							'active',
							'created',
							'modified'

						),
// 						'Decisionnonrespectsanctionep93' => array(
// 							'order' => array( 'etape DESC' )
// 						),
						/*'Decisionreorientationep93',
						'Motifreorientep93',
						'Typeorient',
						'Structurereferente',*/
						'Orientstruct' => array(
							'Typeorient',
							'Structurereferente',
						)
					),
					'Passagecommissionep' => array(
						'conditions' => array(
							'Passagecommissionep.commissionep_id' => $commissionep_id
						),
						'Decisionnonrespectsanctionep93' => array(
							'order' => array( 'Decisionnonrespectsanctionep93.etape DESC' ),
						)
					)
				)
			);
		}

		/**
		* FIXME
		*
		* @param integer $commissionep_id L'id technique de la séance d'EP
		* @param array $datas Les données des dossiers
		* @param string $niveauDecision Le niveau de décision ('ep' ou 'cg') pour
		*	lequel il faut préparer les données du formulaire
		* @return array
		* @access public
		*/

		public function prepareFormData( $commissionep_id, $datas, $niveauDecision ) {
			// Doit-on prendre une décision à ce niveau ?
			$themes = $this->Dossierep->Passagecommissionep->Commissionep->themesTraites( $commissionep_id );
			$niveauFinal = $themes[Inflector::underscore($this->alias)];
			if( ( $niveauFinal == 'ep' ) && ( $niveauDecision == 'cg' ) ) {
				return array();
			}

			$formData = array();
			foreach( $datas as $key => $dossierep ) {
				$formData['Nonrespectsanctionep93'][$key]['id'] = @$datas[$key]['Nonrespectsanctionep93']['id'];
				$formData['Nonrespectsanctionep93'][$key]['dossierep_id'] = @$datas[$key]['Nonrespectsanctionep93']['dossierep_id'];
				$formData['Decisionnonrespectsanctionep93'][$key]['passagecommissionep_id'] = @$datas[$key]['Passagecommissionep'][0]['id'];

				// On modifie les enregistrements de cette étape
				if( @$dossierep['Passagecommissionep'][0]['Decisionnonrespectsanctionep93'][0]['etape'] == $niveauDecision ) {
					$formData['Decisionnonrespectsanctionep93'][$key] = @$dossierep['Passagecommissionep'][0]['Decisionnonrespectsanctionep93'][0];
				}
				// On ajoute les enregistrements de cette étape -> FIXME: manque les id ?
				else {
					if( $niveauDecision == 'ep' ) {
						if( !empty( $datas[$key]['Passagecommissionep'][0]['Decisionnonrespectsanctionep93'][0] ) ) { // Modification
							$formData['Decisionnonrespectsanctionep93'][$key]['decision'] = @$datas[$key]['Passagecommissionep'][0]['Decisionnonrespectsanctionep93'][0]['decision'];
						}
						else {
							if( ( $dossierep['Personne']['Foyer']['nbenfants'] > 0 ) || ( $dossierep['Personne']['Foyer']['sitfam'] == 'MAR' ) ) {
								$formData['Decisionnonrespectsanctionep93'][$key]['decision'] = '1maintien';
							}
							// FIXME: autre cas ?
						}
					}
					else if( $niveauDecision == 'cg' ) {
						$formData['Decisionnonrespectsanctionep93'][$key]['decision'] = $dossierep['Passagecommissionep'][0]['Decisionnonrespectsanctionep93'][0]['decision'];
						$formData['Decisionnonrespectsanctionep93'][$key]['decisionpcg'] = 'valide';
						$formData['Decisionnonrespectsanctionep93'][$key]['raisonnonpassage'] = $dossierep['Passagecommissionep'][0]['Decisionnonrespectsanctionep93'][0]['raisonnonpassage'];
					}
				}
			}

			return $formData;
		}

		/**
		* TODO: docs
		*/

		public function saveDecisions( $data, $niveauDecision ) {
			// FIXME: filtrer les données
			$themeData = Set::extract( $data, '/Decisionnonrespectsanctionep93' );
			if( empty( $themeData ) ) {
				return true;
			}
			else {
				foreach( array_keys( $themeData ) as $key ) {
					// On complètre /on nettoie si ce n'est pas envoyé par le formulaire
					if( $themeData[$key]['Decisionnonrespectsanctionep93']['decision'] == '1reduction' ) {
						$themeData[$key]['Decisionnonrespectsanctionep93']['dureesursis'] = null;
						$themeData[$key]['Decisionnonrespectsanctionep93']['montantreduction'] = Configure::read( 'Nonrespectsanctionep93.montantReduction' );
					}
					else if( $themeData[$key]['Decisionnonrespectsanctionep93']['decision'] == '1sursis' ) {
						$themeData[$key]['Decisionnonrespectsanctionep93']['montantreduction'] = null;
						$themeData[$key]['Decisionnonrespectsanctionep93']['dureesursis'] = Configure::read( 'Nonrespectsanctionep93.dureeSursis' );
					}
					else if( in_array( $themeData[$key]['Decisionnonrespectsanctionep93']['decision'],  array( '1maintien', '1pasavis', '1delai' ) ) ) {
						$themeData[$key]['Decisionnonrespectsanctionep93']['montantreduction'] = null;
						$themeData[$key]['Decisionnonrespectsanctionep93']['dureesursis'] = null;
					}
					// FIXME: la même chose pour l'étape 2
				}

				$success = $this->Dossierep->Passagecommissionep->Decisionnonrespectsanctionep93->saveAll( $themeData, array( 'atomic' => false ) );
				$this->Dossierep->Passagecommissionep->updateAll(
					array( 'Passagecommissionep.etatdossierep' => '\'decision'.$niveauDecision.'\'' ),
					array( '"Passagecommissionep"."id"' => Set::extract( $data, '/Decisionnonrespectsanctionep93/passagecommissionep_id' ) )
				);

				return $success;
			}
		}

		/**
		*
		*/

		public function saveDecisionUnique( $data, $niveauDecision ) {
			return true;
		}

		/**
		* TODO: docs
		*/

		public function finaliser( $commissionep_id, $etape ) {
			$commissionep = $this->Dossierep->Passagecommissionep->Commissionep->find(
				'first',
				array(
					'conditions' => array( 'Commissionep.id' => $commissionep_id ),
					'contain' => array(
						'Ep' => array(
							'Regroupementep'
						)
					)
				)
			);

			$niveauDecisionFinale = $commissionep['Ep']['Regroupementep'][Inflector::underscore( $this->alias )];

			$dossierseps = $this->Dossierep->Passagecommissionep->find(
				'all',
				array(
					'fields' => array(
						'Passagecommissionep.id',
						'Passagecommissionep.commissionep_id',
						'Passagecommissionep.dossierep_id',
						'Passagecommissionep.etatdossierep',
						'Dossierep.personne_id',
						'Decisionnonrespectsanctionep93.decision',
						/*'Decisionreorientationep93.typeorient_id',
						'Decisionreorientationep93.structurereferente_id',
						'Reorientationep93.structurereferente_id',
						'Reorientationep93.referent_id',
						'Reorientationep93.datedemande'*/
					),
					'conditions' => array(
						'Passagecommissionep.commissionep_id' => $commissionep_id
						/*'Dossierep.commissionep_id' => $commissionep_id,
						'Dossierep.themeep' => Inflector::tableize( $this->alias ),//FIXME: ailleurs aussi*/
					),
					'joins' => array(
						array(
							'table' => 'dossierseps',
							'alias' => 'Dossierep',
							'type' => 'INNER',
							'conditions' => array(
								'Passagecommissionep.dossierep_id = Dossierep.id'
							)
						),
						array(
							'table' => 'nonrespectssanctionseps93',
							'alias' => 'Nonrespectsanctionep93',
							'type' => 'INNER',
							'conditions' => array(
								'Nonrespectsanctionep93.dossierep_id = Dossierep.id'
							)
						),
						array(
							'table' => 'decisionsnonrespectssanctionseps93',
							'alias' => 'Decisionnonrespectsanctionep93',
							'type' => 'INNER',
							'conditions' => array(
								'Decisionnonrespectsanctionep93.passagecommissionep_id = Passagecommissionep.id',
								'Decisionnonrespectsanctionep93.etape' => $etape
							)
						)
					),
					/*'contain' => array(
						'Decisionnonrespectsanctionep93' => array(
							'conditions' => array(
								'Decisionnonrespectsanctionep93.etape' => $etape
							)
						),
						'Dossierep'
					)*/
				)
			);

			$success = true;
			foreach( $dossierseps as $dossierep ) {
				if( $niveauDecisionFinale == $etape ) {
					$nonrespectsanctionep93 = array( 'Nonrespectsanctionep93' => $dossierep['Nonrespectsanctionep93'] );
					$nonrespectsanctionep93['Nonrespectsanctionep93']['active'] = 0;
					if( !isset( $dossierep['Decisionnonrespectsanctionep93'][0]['decision'] ) ) {
						$success = false;
					}

					// Copie de la décision
					$nonrespectsanctionep93['Nonrespectsanctionep93']['decision'] = @$dossierep['Decisionnonrespectsanctionep93'][0]['decision'];
					$nonrespectsanctionep93['Nonrespectsanctionep93']['montantreduction'] = @$dossierep['Decisionnonrespectsanctionep93'][0]['montantreduction'];
					$nonrespectsanctionep93['Nonrespectsanctionep93']['dureesursis'] = @$dossierep['Decisionnonrespectsanctionep93'][0]['dureesursis'];

					/*if( $nonrespectsanctionep93['Nonrespectsanctionep93']['decision'] == '1reduction' ) { // FIXME: vient de la dernière décision
						$nonrespectsanctionep93['Nonrespectsanctionep93']['montantreduction'] = Configure::read( 'Nonrespectsanctionep93.montantReduction' );
					}
					else if( $nonrespectsanctionep93['Nonrespectsanctionep93']['decision'] == '1sursis' ) {
						$nonrespectsanctionep93['Nonrespectsanctionep93']['dureesursis'] = Configure::read( 'Nonrespectsanctionep93.dureeSursis' );
					}*/

					$this->create( $nonrespectsanctionep93 ); // TODO: un saveAll ?
					$success = $this->save() && $success;
				}
			}

			return $success;
		}

		/**
		*
		*/

		public function containPourPv() {
			return array(
				'Nonrespectsanctionep93' => array(
					'Decisionnonrespectsanctionep93' => array(
						/*'fields' => array(
							'( CAST( decision AS TEXT ) || montantreduction ) AS avis'
						),*/
						'conditions' => array(
							'etape' => 'ep'
						),
					)
				),
			);
		}

		/**
		*
		*/

		public function qdProcesVerbal() {
			return array(
				'fields' => array(
					'Nonrespectsanctionep93.id',
					'Nonrespectsanctionep93.dossierep_id',
					'Nonrespectsanctionep93.propopdo_id',
					'Nonrespectsanctionep93.orientstruct_id',
					'Nonrespectsanctionep93.contratinsertion_id',
					'Nonrespectsanctionep93.origine',
					'Nonrespectsanctionep93.decision',
					'Nonrespectsanctionep93.rgpassage',
					'Nonrespectsanctionep93.montantreduction',
					'Nonrespectsanctionep93.dureesursis',
					'Nonrespectsanctionep93.sortienvcontrat',
					'Nonrespectsanctionep93.active',
					'Nonrespectsanctionep93.created',
					'Nonrespectsanctionep93.modified',
					'Decisionnonrespectsanctionep93.id',
// 					'Decisionnonrespectsanctionep93.nonrespectsanctionep93_id',
					'Decisionnonrespectsanctionep93.etape',
					'Decisionnonrespectsanctionep93.decision',
					'Decisionnonrespectsanctionep93.montantreduction',
					'Decisionnonrespectsanctionep93.dureesursis',
					'Decisionnonrespectsanctionep93.commentaire',
					'Decisionnonrespectsanctionep93.created',
					'Decisionnonrespectsanctionep93.modified',
					'Decisionnonrespectsanctionep93.raisonnonpassage',
				),
				'joins' => array(
					array(
						'table'      => 'nonrespectssanctionseps93',
						'alias'      => 'Nonrespectsanctionep93',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Nonrespectsanctionep93.dossierep_id = Dossierep.id' ),
					),
					array(
						'table'      => 'decisionsnonrespectssanctionseps93',
						'alias'      => 'Decisionnonrespectsanctionep93',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Decisionnonrespectsanctionep93.passagecommissionep_id = Passagecommissionep.id',
							'Decisionnonrespectsanctionep93.etape' => 'ep'
						),
					),
				)
			);
		}

		/**
		*
		*/

		public function qdRadies() {
			$queryData = array(
				'fields' => array(
					'Personne.id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Personne.nir'
				),
				'contain' => false,
				'joins' => array(
					array(
						'table'      => 'prestations', // FIXME:
						'alias'      => 'Prestation',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id = Prestation.personne_id',
							'Prestation.natprest' => 'RSA',
							'Prestation.rolepers' => array( 'DEM', 'CJT' ),
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
						'conditions' => array( 'Dossier.id = Foyer.dossier_id' )
					),
					array(
						'table'      => 'situationsdossiersrsa',
						'alias'      => 'Situationdossierrsa',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Situationdossierrsa.dossier_id = Dossier.id',
							'Situationdossierrsa.etatdosrsa' => array( 'Z', '2', '3', '4' )
						)
					),
					array(
						'table'      => 'calculsdroitsrsa', // FIXME:
						'alias'      => 'Calculdroitrsa',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id = Calculdroitrsa.personne_id',
							'Calculdroitrsa.toppersdrodevorsa' => '1',
						)
					),
					array(
						'table'      => 'orientsstructs', // FIXME:
						'alias'      => 'Orientstruct',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id = Orientstruct.personne_id',
							// La dernière
							'Orientstruct.id IN (
										SELECT o.id
											FROM orientsstructs AS o
											WHERE
												o.personne_id = Personne.id
												AND o.date_valid IS NOT NULL
											ORDER BY o.date_valid DESC
											LIMIT 1
							)',
							// en emploi
							'Orientstruct.typeorient_id IN (
								SELECT t.id
									FROM typesorients AS t
									WHERE t.lib_type_orient LIKE \'Emploi%\'
							)'// FIXME
						)
					)
				),
				'conditions' => array(
					'Personne.id NOT IN ( '.
						$this->Dossierep->sq(
							array(
								'alias' => 'dossierseps',
								'fields' => array( 'dossierseps.personne_id' ),
								'conditions' => array(
									'dossierseps.personne_id = Personne.id',
									array(
										'OR' => array(
											'dossierseps.id NOT IN ( '.
												$this->Dossierep->Passagecommissionep->sq(
													array(
														'alias' => 'passagescommissionseps',
														'fields' => array( 'passagescommissionseps.dossierep_id' ),
														'conditions' => array(
															'NOT' => array(
																'passagescommissionseps.etatdossierep' => array( 'traite', 'annule' )
															)
														)
													)
												)
											.' )',
											'dossierseps.id IN ( '.
												$this->Dossierep->Passagecommissionep->sq(
													array(
														'alias' => 'passagescommissionseps',
														'fields' => array( 'passagescommissionseps.dossierep_id' ),
														'conditions' => array(
															'passagescommissionseps.etatdossierep' => array( 'traite', 'annule' ),
															'( DATE( NOW() ) - CAST( dossierseps.modified AS DATE ) ) <=' => Configure::read( $this->alias.'.delaiRegularisation' )
														)
													)
												)
											.' )',
										)
									)
								)
							)
						) .' )'
				)
			);

			$this->Historiqueetatpe = ClassRegistry::init('Historiqueetatpe');

			$qdRadies = $this->Historiqueetatpe->Informationpe->qdRadies();
			$queryData['fields'] = array_merge( $queryData['fields'] ,$qdRadies['fields'] );
			$queryData['joins'] = array_merge( $queryData['joins'] ,$qdRadies['joins'] );
			$queryData['conditions'] = array_merge( $queryData['conditions'] ,$qdRadies['conditions'] );
			$queryData['order'] = $qdRadies['order'];

			return $queryData;
		}

        /**
        *    Récupération des informations propres au dossier devant passer en EP
        *   avant liaison avec la commission d'EP
        */
        public function getCourrierInformationPdf( $dossierep_id ) {
            $gedooo_data = $this->find(
                'first',
                array(
                    'conditions' => array( 'Dossierep.id' => $dossierep_id ),
                    'contain' => array(
                        'Dossierep' => array(
                            'Personne'
                        ),
                        'Orientstruct' => array(
                            'Typeorient',
                            'Structurereferente'
                        ),
                        'Contratinsertion',
                        'Propopdo'
                    )
                )
            );
// debug($gedooo_data);
            return $this->ged( $gedooo_data, "{$this->alias}/{$gedooo_data[$this->alias]['origine']}_courrierinformationavantep.odt" );
        }

	}
?>
