<?php
	/**
	* Séance d'équipe pluridisciplinaire.
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.models
	*/

	class Commissionep extends AppModel
	{
		public $name = 'Commissionep';

		public $displayField = 'dateseance';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Formattable',
			'Enumerable' => array(
				'fields' => array(
					'etatcommissionep'
				)
			),
			'Gedooo'
		);

		public $belongsTo = array(
			'Ep' => array(
				'className' => 'Ep',
				'foreignKey' => 'ep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

		public $hasMany = array(
			'Passagecommissionep' => array(
				'className' => 'Passagecommissionep',
				'foreignKey' => 'commissionep_id',
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
			'CommissionepMembreep' => array(
				'className' => 'CommissionepMembreep',
				'foreignKey' => 'commissionep_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			)
		);

		public $hasAndBelongsToMany = array(
			/*'Dossierep' => array(
				'className' => 'Dossierep',
				'joinTable' => 'passagescommissionseps',
				'foreignKey' => 'commissionep_id',
				'associationForeignKey' => 'dossierep_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'Passagecommissionep'
			),*/
			'Membreep' => array(
				'className' => 'Membreep',
				'joinTable' => 'commissionseps_membreseps',
				'foreignKey' => 'commissionep_id',
				'associationForeignKey' => 'membreep_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'CommissionepMembreep'
			),
		);

		public $validate = array(
			'raisonannulation' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Ce champ est obligatoire.',
					'allowEmpty' => false,
					'required' => false
				)
			)
		);

		public function search( $criteresseanceep, $filtre_zone_geo, $zonesgeographiques ) {
			/// Conditions de base

			$conditions = $this->Ep->sqRestrictionsZonesGeographiques(
				'Commissionep.ep_id',
				$filtre_zone_geo,
				$zonesgeographiques
			);

			if ( isset($criteresseanceep['Ep']['regroupementep_id']) && !empty($criteresseanceep['Ep']['regroupementep_id']) ) {
				$conditions[] = array('Ep.regroupementep_id'=>$criteresseanceep['Ep']['regroupementep_id']);
			}

			if ( isset($criteresseanceep['Commissionep']['name']) && !empty($criteresseanceep['Commissionep']['name']) ) {
				$conditions[] = array('Commissionep.name'=>$criteresseanceep['Commissionep']['name']);
			}

			if ( isset($criteresseanceep['Commissionep']['identifiant']) && !empty($criteresseanceep['Commissionep']['identifiant']) ) {
				$conditions[] = array('Commissionep.identifiant'=>$criteresseanceep['Commissionep']['identifiant']);
			}

			if ( isset($criteresseanceep['Structurereferente']['ville']) && !empty($criteresseanceep['Structurereferente']['ville']) ) {
				//$conditions[] = array('Structurereferente.ville'=>$criteresseanceep['Structurereferente']['ville']);
				$conditions[] = array('Commissionep.villeseance'=>$criteresseanceep['Structurereferente']['ville']);
			}

			/// Critères sur le Comité - date du comité
			if( isset( $criteresseanceep['Commissionep']['dateseance'] ) && !empty( $criteresseanceep['Commissionep']['dateseance'] ) ) {
				$valid_from = ( valid_int( $criteresseanceep['Commissionep']['dateseance_from']['year'] ) && valid_int( $criteresseanceep['Commissionep']['dateseance_from']['month'] ) && valid_int( $criteresseanceep['Commissionep']['dateseance_from']['day'] ) );
				$valid_to = ( valid_int( $criteresseanceep['Commissionep']['dateseance_to']['year'] ) && valid_int( $criteresseanceep['Commissionep']['dateseance_to']['month'] ) && valid_int( $criteresseanceep['Commissionep']['dateseance_to']['day'] ) );
				if( $valid_from && $valid_to ) {
					$conditions[] = 'Commissionep.dateseance BETWEEN \''.implode( '-', array( $criteresseanceep['Commissionep']['dateseance_from']['year'], $criteresseanceep['Commissionep']['dateseance_from']['month'], $criteresseanceep['Commissionep']['dateseance_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteresseanceep['Commissionep']['dateseance_to']['year'], $criteresseanceep['Commissionep']['dateseance_to']['month'], $criteresseanceep['Commissionep']['dateseance_to']['day'] ) ).'\'';
				}
			}

			$query = array(
				'fields' => array(
					'Commissionep.id',
					'Commissionep.name',
					'Commissionep.identifiant',
					//'Commissionep.structurereferente_id',
					'Commissionep.dateseance',
					'Commissionep.etatcommissionep',
					'Commissionep.lieuseance',
					'Commissionep.observations'
				),
				'contain'=>array(
					//'Structurereferente',
					'Ep' => array(
						'fields'=>array(
							'id',
							'name',
							'identifiant'
						),
						'Regroupementep'
					),
					'Membreep'
				),
				'order' => array( '"Commissionep"."dateseance" ASC' ),
				'conditions' => $conditions
			);

			return $query;
		}

		/**
		* Renvoie un array associatif contenant les thèmes traités par la commission
		* ainsi que le niveau de décision pour chacun de ces thèmes.
		*
		* @param integer $id L'id technique de la commission d'EP
		* @return array
		* @access public
		*/

		public function themesTraites( $id ) {
			$regroupementep = $this->Ep->Regroupementep->find(
				'first',
				array(
					'contain' => false,
					'conditions' => array(
						'Regroupementep.id IN ( '.
							$this->Ep->sq(
								array(
									'alias' => 'eps',
									'fields' => array( 'eps.regroupementep_id' ),
									'conditions' => array(
										'eps.id IN ( '.
											$this->sq(
												array(
													'alias' => 'commissionseps',
													'fields' => array( 'commissionseps.ep_id' ),
													'conditions' => array(
														'commissionseps.id' => $id
													)
												)
											)
										.' )'
									)
								)
							)
						.' )'
					)
				)
			);

			$themes = $this->Ep->themes();
			$themesTraites = array();

			foreach( $themes as $theme ) {
				if( in_array( $regroupementep['Regroupementep'][$theme], array( 'decisionep', 'decisioncg' ) ) ) {
					$themesTraites[$theme] = $regroupementep['Regroupementep'][$theme];
				}
			}

			return $themesTraites;
		}

		/**
		* Sauvegarde des avis/décisions par liste d'une séance d'EP, au niveau ep ou cg
		*
		* @param integer $commissionep_id L'id technique de la séance d'EP
		* @param array $data Les données à sauvegarder
		* @param string $niveauDecision Le niveau de décision pour lequel il faut sauvegarder
		* @return boolean
		* @access public
		*/

		public function saveDecisions( $commissionep_id, $data, $niveauDecision ) {
			$commissionep = $this->find( 'first', array( 'conditions' => array( 'Commissionep.id' => $commissionep_id ) ) );

			if( empty( $commissionep ) ) {
				return false;
			}

			$success = true;

			// FIXME: ne sert à rien ?
			// FIXME: ça va casser ailleurs ?
			/*foreach( $data as $thematique => $dossierseps ) {
				foreach( $dossierseps as $key => $dossierep ) {
					if( empty( $dossierep['decision'] ) ) {
						unset( $dossierseps[$key] );
					}
				}
				if( empty( $dossierseps ) ) {
					unset( $data[$thematique] );
				}
			}*/

			foreach( $this->themesTraites( $commissionep_id ) as $theme => $decision ) {
				$model = Inflector::classify( $theme );
				$modeleDecision = Inflector::classify( "decision{$theme}" );
				if( isset( $data[$model] ) || isset( $data[$modeleDecision] ) ) { // FIXME: && $data['Decision..] ?
					$success = $this->Passagecommissionep->Dossierep->{$model}->saveDecisions( $data, $niveauDecision ) && $success;
				}
			}

			///FIXME : calculer si tous les dossiers ont bien une décision avant de chager l'état ?
			$this->id = $commissionep_id;
			$this->set( 'etatcommissionep', "decision{$niveauDecision}" );
			$success = $this->save() && $success;

			return $success;
		}

		/**
		* Retourne la liste des dossiers de la séance d'EP, groupés par thème,
		* pour les dossiers qui doivent passer par liste.
		*
		* @param integer $commissionep_id L'id technique de la séance d'EP
		* @param string $niveauDecision Le niveau de décision ('decisionep' ou 'decisioncg') pour
		*	lequel il faut les dossiers à passer par liste.
		* @return array
		* @access public
		*/

		public function dossiersParListe( $commissionep_id, $niveauDecision ) {
			$dossiers = array();

			foreach( $this->themesTraites( $commissionep_id ) as $theme => $decision ) {
				$model = Inflector::classify( $theme );
				$queryData = $this->Passagecommissionep->Dossierep->{$model}->qdDossiersParListe( $commissionep_id, $niveauDecision );
				$dossiers[$model]['liste'] = array();
				if( !empty( $queryData ) ) {
					$dossiers[$model]['liste'] = $this->Passagecommissionep->Dossierep->find( 'all', $queryData );
				}
			}

			return $dossiers;
		}

		/**
		* Retourne les données par défaut du formulaire de traitement par liste,
		* pour une séance donnée, pour des dossiers données et à un niveau de
		* décision donné.
		*
		* @param integer $commissionep_id L'id technique de la séance d'EP
		* @param array $dossiers Array de résultats de requêtes CakePHP pour
		* 	chacun des thèmes, par liste.
		* @param string $niveauDecision Le niveau de décision ('decisionep' ou 'decisioncg')
		*	pour lequel on veut obtenir les données par défaut du formulaire de
		*	traitement.
		* @return array
		* @access public
		*/

		public function prepareFormData( $commissionep_id, $dossiers, $niveauDecision ) {
			$data = array();

			foreach( $this->themesTraites( $commissionep_id ) as $theme => $decision ) {
				$model = Inflector::classify( $theme );

				$data = Set::merge(
					$data,
					$this->Passagecommissionep->Dossierep->{$model}->prepareFormData(
						$commissionep_id,
						$dossiers[$model]['liste'],
						$niveauDecision
					)
				);
			}

			return $data;
		}

		/**
		* Tentative de finalisation des décisions d'une séance donnée, pour un
		* niveau de décision donné.
		* Retourne false si tous les dossiers de la séance n'ont pas eu de décision
		* ou si la finalisation n'a pas pu avoir lieu.
		*
		* TODO: être plus précis dans la description de la fonction + faire une
		* doc précise pour les fonctions "finaliser" des différents modèles de
		* thèmes.
		*
		* @param integer $commissionep_id L'id technique de la séance d'EP
		* @param string $niveauDecision Le niveau de décision ('decisionep' ou 'decisioncg')
		*	pour lequel on veut finaliser les décisions.
		* @return boolean
		* @access public
		*/

		public function finaliser( $commissionep_id, $data, $niveauDecision, $user_id ) {
			$themesTraites = $this->themesTraites( $commissionep_id );

			// Première partie: revalidation "spéciale" des décisions
			foreach( $themesTraites as $themeTraite => $niveauDecisionTheme ) {
				$modeleDecision = Inflector::classify( "decision{$themeTraite}" );
				if( isset( $this->Passagecommissionep->{$modeleDecision}->validateFinalisation ) ) {
					// FIXME: pas possible de faire un merge avec les règles déduites par Autovalidate ?
					$this->Passagecommissionep->{$modeleDecision}->validate = $this->Passagecommissionep->{$modeleDecision}->validateFinalisation;
				}
			}

			if( !$this->saveDecisions( $commissionep_id, $data, $niveauDecision ) ) {
				return false;
			}

			// Deuxième partie: recherche des dossiers pas encore traités à cette étape
			$success = true;
			$totalErrors = 0;
			foreach( $themesTraites as $themeTraite => $niveauDecisionTheme ) {
				$themeTraite = Inflector::tableize( $themeTraite );

				// On est au niveau de décision final ou au niveau cg
				if( ( $niveauDecisionTheme == "decision{$niveauDecision}" ) || $niveauDecisionTheme == 'decisioncg' ) {
					$conditions = array(
						'Dossierep.themeep' => $themeTraite,
						'Dossierep.id IN ( '.$this->Passagecommissionep->sq(
							array(
								'alias' => 'passagescommissionseps',
								'fields' => array(
									'passagescommissionseps.dossierep_id'
								),
								'conditions' => array(
									'passagescommissionseps.commissionep_id' => $commissionep_id,
									'passagescommissionseps.etatdossierep <>' => "decision{$niveauDecision}",
								)
							)
						).' )',
					);
					$totalErrors += $this->Passagecommissionep->Dossierep->find( 'count', array( 'conditions' => $conditions ) );
				}
			}

			if( empty( $totalErrors ) ) {
				$niveauMax = 'decisionep';
				foreach( $themesTraites as $themeTraite => $niveauDecisionTheme ) {
					$themeTraite = Inflector::tableize( $themeTraite );
					$tableDecisionTraite = "decisions".Inflector::underscore( $themeTraite );
					$modelDecisionTraite = Inflector::classify( $tableDecisionTraite );

					if( "decision{$niveauDecision}" == $niveauDecisionTheme ) {
						$this->Passagecommissionep->updateAll(
							array( 'Passagecommissionep.etatdossierep' => '\'traite\'' ),
							array(
								'"Passagecommissionep"."commissionep_id"' => $commissionep_id,
								'"Passagecommissionep"."id" NOT IN ( '. $this->Passagecommissionep->{$modelDecisionTraite}->sq(
									array(
										'fields' => array(
											"{$tableDecisionTraite}.passagecommissionep_id"
										),
										'alias' => "{$tableDecisionTraite}",
										'conditions' => array(
											"{$tableDecisionTraite}.decision" => array( 'reporte', 'annule' ),
											"{$tableDecisionTraite}.etape" => $niveauDecision
										)
									)
								).' )',
								'"Passagecommissionep"."dossierep_id" IN ( '. $this->Passagecommissionep->Dossierep->sq(
									array(
										'fields' => array(
											'dossierseps.id'
										),
										'alias' => 'dossierseps',
										'conditions' => array(
											'dossierseps.themeep' => $themeTraite
										)
									)
								).' )'
							)
						);

						$listeDecisions = array( 'annule', 'reporte' );
						foreach( $listeDecisions as $decision ) {
							$this->Passagecommissionep->updateAll(
								array( 'Passagecommissionep.etatdossierep' => "'{$decision}'" ),
								array(
									'"Passagecommissionep"."commissionep_id"' => $commissionep_id,
									'"Passagecommissionep"."id" IN ( '. $this->Passagecommissionep->{$modelDecisionTraite}->sq(
										array(
											'fields' => array(
												"{$tableDecisionTraite}.passagecommissionep_id"
											),
											'alias' => "{$tableDecisionTraite}",
											'conditions' => array(
												"{$tableDecisionTraite}.decision" => array( $decision ),
													"{$tableDecisionTraite}.etape" => $niveauDecision
											)
										)
									).' )'
								)
							);
						}

						if( $tableDecisionTraite == 'decisionsnonrespectssanctionseps93' || $tableDecisionTraite == 'decisionssignalementseps93' ) {
							$listeDecisions = array( '1pasavis', '2pasavis' );
							foreach( $listeDecisions as $decision ) {
								$this->Passagecommissionep->updateAll(
									array( 'Passagecommissionep.etatdossierep' => "'reporte'" ),
									array(
										'"Passagecommissionep"."commissionep_id"' => $commissionep_id,
										'"Passagecommissionep"."id" IN ( '. $this->Passagecommissionep->{$modelDecisionTraite}->sq(
											array(
												'fields' => array(
													"{$tableDecisionTraite}.passagecommissionep_id"
												),
												'alias' => "{$tableDecisionTraite}",
												'conditions' => array(
													"{$tableDecisionTraite}.decision" => array( $decision ),
														"{$tableDecisionTraite}.etape" => $niveauDecision
												)
											)
										).' )'
									)
								);
							}
						}

					}
					elseif( $niveauDecisionTheme == 'decisioncg' && "decision{$niveauDecision}" == 'decisionep' ) {
						$this->Passagecommissionep->updateAll(
							array( 'Passagecommissionep.etatdossierep' => '\'decisioncg\'' ),
							array(
								'"Passagecommissionep"."commissionep_id"' => $commissionep_id
							)
						);
					}

					if ( $niveauDecisionTheme == 'decisioncg' ) {
						$niveauMax = 'decisioncg';
					}
				}

				$commissionep = $this->find(
					'first',
					array(
						'conditions' => array(
							'Commissionep.id' => $commissionep_id
						)
					)
				);

				if( "decision{$niveauDecision}" == 'decisioncg' || ( $niveauMax == 'decisionep' && "decision{$niveauDecision}" == 'decisionep' ) ) {
					$commissionep['Commissionep']['etatcommissionep'] = 'traite';
					// Finalisation de chacun des dossiers
					foreach( $themesTraites as $themeTraite => $niveauDecisionTheme ) {
						if( $niveauDecisionTheme == "decision{$niveauDecision}" ) {
							$themeTraite = Inflector::tableize( $themeTraite );
							$model = Inflector::classify( $themeTraite );
							$success = $this->Passagecommissionep->Dossierep->{$model}->finaliser( $commissionep_id, $niveauDecision, $user_id ) && $success;
						}
					}
				}
				else {
					$niveauxDecisionsSeance = array_values( $themesTraites );
					$commissionep['Commissionep']['etatcommissionep'] = 'traiteep';
					if( !in_array( 'decisioncg', $niveauxDecisionsSeance ) ) {
						// Finalisation de chacun des dossiers
						foreach( $themesTraites as $themeTraite => $niveauDecisionTheme ) {
							$themeTraite = Inflector::tableize( $themeTraite );
							$model = Inflector::classify( $themeTraite );
							if( $niveauDecisionTheme == "decision{$niveauDecision}" ) {
								$success = $this->Passagecommissionep->Dossierep->{$model}->finaliser( $commissionep_id, $niveauDecisionTheme, $user_id ) && $success;
							}
							else {
								$success = $this->Passagecommissionep->Dossierep->{$model}->verrouiller( $commissionep_id, $niveauDecision, $user_id ) && $success;
							}
						}
					}
				}
				$this->id = $commissionep['Commissionep']['id'];
				$this->set( 'etatcommissionep', $commissionep['Commissionep']['etatcommissionep'] );
				$success = $this->save() && $success;
			}

			return $success && empty( $totalErrors );
		}

		/**
		* Savoir si la séance est cloturée ou non (suivant le thème l'EP et le CG ce sont prononcés)
		*/

		public function clotureSeance($datas) {
			$cloture = true;

			foreach( $this->themesTraites( $datas['Commissionep']['id'] ) as $theme => $decision ) {
				$cloture = ($datas['Ep'][$theme]==$datas['Commissionep']['etatcommissionep']) && $cloture;
			}

			return $cloture;
		}

		/**
		* Change l'état de la commission d'EP entre 'cree' et 'associe'
		* S'il existe au moins un dossier associé et un membre ayant donné une réponse
		* "Confirmé" ou "Remplacé par", l'état devient associé, sinon l'état devient 'cree'
		*
		* FIXME: il faudrait une réponse pour tous les membres ?
		*
		* @param integer $commissionep_id L'identifiant technique de la commission d'EP
		* @return boolean
		*/

		public function changeEtatCreeAssocie( $commissionep_id ) {
			$commissionep = $this->find(
				'first',
				array(
					'conditions' => array(
						'Commissionep.id' => $commissionep_id
					),
					'contain' => array(
						'Ep',
						'CommissionepMembreep'
					)
				)
			);

			if( empty( $commissionep ) || !in_array( $commissionep['Commissionep']['etatcommissionep'], array( 'cree', 'quorum', 'associe' ) ) ) {
				return false;
			}

			$success = true;

			$nbDossierseps = $this->Passagecommissionep->find(
				'count',
				array(
					'conditions' => array(
						'Passagecommissionep.commissionep_id' => $commissionep_id
					)
				)
			);

			$nbMembresepsNonRenseignes = $this->CommissionepMembreep->find(
				'count',
				array(
					'conditions' => array(
						'CommissionepMembreep.commissionep_id' => $commissionep_id,
						'CommissionepMembreep.reponse' => array( 'nonrenseigne' ),
					)
				)
			);

			$nbMembresepsTotal = $this->CommissionepMembreep->find(
				'count',
				array(
					'conditions' => array(
						'CommissionepMembreep.commissionep_id' => $commissionep_id
					)
				)
			);

			$this->id = $commissionep_id;
			if( ( $nbDossierseps > 0 ) && ( $nbMembresepsNonRenseignes == 0 ) && ( $nbMembresepsTotal > 0 ) && ( $commissionep['Commissionep']['etatcommissionep'] == 'cree' || $commissionep['Commissionep']['etatcommissionep'] == 'quorum' ) ) {
				$this->set( 'etatcommissionep', 'associe' );
				$success = $this->save() && $success;
			}
			else if( ( ( $nbDossierseps == 0 ) || ( $nbMembresepsNonRenseignes > 0 ) || ( $nbMembresepsTotal == 0 ) ) && ( $commissionep['Commissionep']['etatcommissionep'] == 'associe' || $commissionep['Commissionep']['etatcommissionep'] == 'quorum' ) ) {
				$this->set( 'etatcommissionep', 'cree' );
				$success = $this->save() && $success;
			}

			if ( Configure::read( 'Cg.departement' ) == 66 ) {
				$listeMembrePresentRemplace = array();
				foreach( $commissionep['CommissionepMembreep'] as $membre ) {
					if ( $membre['reponse'] == 'confirme' || $membre['reponse'] == 'remplacepar' ) {
						$listeMembrePresentRemplace[] = $membre['membreep_id'];
					}
				}

				$compositionValide = $this->Ep->Regroupementep->Compositionregroupementep->compositionValide( $commissionep['Ep']['regroupementep_id'], $listeMembrePresentRemplace );
				if( !$compositionValide['check'] ) {
					$this->set( 'etatcommissionep', 'quorum' );
					$success = $this->save() && $success;
				}
			}
			return $success;
		}

		/**
		* Change l'état de la commission d'EP entre 'associe' et 'presence'
		* S'il existe au moins un membre présent à la commission
		*
		* FIXME: à modifier lors de la mise en place du corum
		*
		* @param integer $commissionep_id L'identifiant technique de la commission d'EP
		* @return boolean
		*/

		public function changeEtatAssociePresence( $commissionep_id ) {
			$commissionep = $this->find(
				'first',
				array(
					'conditions' => array(
						'Commissionep.id' => $commissionep_id
					),
					'contain' => false
				)
			);

			if( empty( $commissionep ) || !in_array( $commissionep['Commissionep']['etatcommissionep'], array( 'associe', 'valide', 'presence' ) ) ) {
				return false;
			}

			$success = true;
			$nbMembreseps = $this->CommissionepMembreep->find(
				'count',
				array(
					'conditions' => array(
						'CommissionepMembreep.commissionep_id' => $commissionep_id,
						'CommissionepMembreep.presence' => array( 'present', 'remplacepar' ),
					)
				)
			);

			$this->id = $commissionep_id;
			if( !empty( $nbMembreseps ) && in_array( $commissionep['Commissionep']['etatcommissionep'], array( 'associe', 'valide' ) ) ) {
				$this->set( 'etatcommissionep', 'presence' );
				$success = $this->save() && $success;
			}
			else if(  empty( $nbMembreseps ) && $commissionep['Commissionep']['etatcommissionep'] == 'presence' ) {
				if( Configure::read( 'Cg.departement' ) == 93 ) {
					$this->set( 'etatcommissionep', 'valide' );
				}
				else {
					$this->set( 'etatcommissionep', 'associe' );
				}
				$success = $this->save() && $success;
			}

			return $success;
		}


		/**
		* Retourne une chaîne de 12 caractères formattée comme suit:
		* CO, année sur 4 chiffres, mois sur 2 chiffres, nombre de commissions.
		*/

		public function identifiant() {
			return 'CO'.date( 'Ym' ).sprintf( "%010s",  $this->find( 'count' ) + 1 );
		}

		/**
		* Ajout de l'identifiant de la séance lors de la sauvegarde.
		*/

		public function beforeValidate( $options = array() ) {
			$primaryKey = Set::classicExtract( $this->data, "{$this->alias}.{$this->primaryKey}" );
			$identifiant = Set::classicExtract( $this->data, "{$this->alias}.identifiant" );

			if( empty( $primaryKey ) && empty( $identifiant ) && empty( $this->{$this->primaryKey} ) ) {
				$this->data[$this->alias]['identifiant'] = $this->identifiant();
			}

			return true;
		}


		/**
		*
		*/

		public function getPdfPv( $commissionep_id ) {
			/*
				commissionep_id,
				commissionep_identifiant,
				commissionep_name,
				commissionep_ep_id,
				commissionep_structurereferente_id,
				commissionep_dateseance,
				commissionep_salle,
				commissionep_observations,
				commissionep_etatcommissionep,
				structurereferente_id,
				structurereferente_typeorient_id,
				structurereferente_lib_struc,
				structurereferente_num_voie,
				structurereferente_type_voie,
				structurereferente_nom_voie,
				structurereferente_code_postal,
				structurereferente_ville,
				structurereferente_code_insee,
				structurereferente_filtre_zone_geo,
				structurereferente_contratengagement,
				structurereferente_apre,
				structurereferente_orientation,
				structurereferente_pdo,
			*/
			$commissionep_data = $this->find(
				'first',
				array(
					'conditions' => array(
						'Commissionep.id' => $commissionep_id
					),
					'contain' => array(
						'Ep' => array(
							'Regroupementep'
						)
					)
				)
			);

			$queryData = array(
				'fields' => array(
					'Dossierep.id',
					'Dossierep.personne_id',
					'Dossierep.themeep',
					'Dossierep.created',
					'Dossierep.modified',
					//
					'Personne.id',
					'Personne.foyer_id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.nomnai',
					'Personne.prenom2',
					'Personne.prenom3',
					'Personne.nomcomnai',
					'Personne.dtnai',
					'Personne.rgnai',
					'Personne.typedtnai',
					'Personne.nir',
					'Personne.topvalec',
					'Personne.sexe',
					'Personne.nati',
					'Personne.dtnati',
					'Personne.pieecpres',
					'Personne.idassedic',
					'Personne.numagenpoleemploi',
					'Personne.dtinscpoleemploi',
					'Personne.numfixe',
					'Personne.numport',
					'Adresse.locaadr',
					'Adresse.numcomptt',
					'Adresse.codepos',

				),
				'joins' => array(
					array(
						'table'      => 'passagescommissionseps',
						'alias'      => 'Passagecommissionep',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Passagecommissionep.dossierep_id = Dossierep.id',
							'Passagecommissionep.commissionep_id' => $commissionep_id,
						),
					),
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( "Dossierep.personne_id = Personne.id" ),
					),
					array(
						'table'      => 'foyers',
						'alias'      => 'Foyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.foyer_id = Foyer.id' )
					),
					array(
						'table'      => 'adressesfoyers',
						'alias'      => 'Adressefoyer',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Foyer.id = Adressefoyer.foyer_id',
							// FIXME: c'est un hack pour n'avoir qu'une seule adresse de range 01 par foyer!
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
				)
			);

			$options = array( 'Personne' => array( 'qual' => ClassRegistry::init( 'Option' )->qual() ) );
			foreach( $this->themesTraites( $commissionep_id ) as $theme => $decision ) {
				$model = Inflector::classify( $theme );
				if( in_array( 'Enumerable', $this->Passagecommissionep->Dossierep->{$model}->Behaviors->attached() ) ) {
					$options = Set::merge( $options, $this->Passagecommissionep->Dossierep->{$model}->enums() );
				}

				$modeleDecision = Inflector::classify( "decision{$theme}" );
				if( in_array( 'Enumerable', $this->Passagecommissionep->{$modeleDecision}->Behaviors->attached() ) ) {
					$options = Set::merge( $options, $this->Passagecommissionep->{$modeleDecision}->enums() );
				}
				/*$modeleDecisions = array( 'Nonrespectsanctionep93' => 'Decisionnonrespectsanctionep93' );// FIXME: à supprimer après le renommage des tables
				if( isset( $modeleDecisions[$model] ) ) {
					$options = Set::merge( $options, $this->Passagecommissionep->{$modeleDecisions[$model]}->enums() );
				}*/

				foreach( array( 'fields', 'joins' ) as $key ) {
					$qdModele = $this->Passagecommissionep->Dossierep->{$model}->qdProcesVerbal();
					$queryData[$key] = array_merge( $queryData[$key], $qdModele[$key] );
				}
			}
			$options = Set::merge( $options, $this->enums() );
			$options = Set::merge( $options, $this->Passagecommissionep->Dossierep->enums() );
			$options = Set::merge( $options, $this->Membreep->enums() );
			$options = Set::merge( $options, $this->CommissionepMembreep->enums() );

			$dossierseps = $this->Passagecommissionep->Dossierep->find( 'all', $queryData );
			// FIXME: faire la traduction des enums dans les modèles correspondants ?

			// present, excuse, FIXME: remplace_par
			$presencesTmp = $this->CommissionepMembreep->find(
				'all',
				array(
					'conditions' => array(
						'CommissionepMembreep.commissionep_id' => $commissionep_id
					),
					'contain' => array(
						'Membreep' => array(
							'Fonctionmembreep'
						),
						'Remplacanteffectifmembreep',
						'Remplacantmembreep'
					)
				)
			);

			// FIXME: presence -> obliger de prendre les présences avant d'imprimer le PV
			$presences = array();
			foreach( $presencesTmp as $presence ) {
				// Y-a-t'il eu un remplaçant effectif ?
				if( ( $presence['CommissionepMembreep']['presence'] == 'remplacepar' ) && !empty( $presence['CommissionepMembreep']['presencesuppleant_id'] ) ) {
					$presence['CommissionepMembreep']['presence'] = 'present';
					$presence['Membreep'] = Set::merge( $presence['Membreep'], $presence['Remplacanteffectifmembreep'] );
				}
				else if( $presence['CommissionepMembreep']['presence'] == 'excuse' ) {
					$presence['Membreep'] = Set::merge( $presence['Membreep'], $presence['Remplacantmembreep'] );
				}

				$presences["Presences_{$presence['CommissionepMembreep']['presence']}"][] = array( 'Membreep' => $presence['Membreep'] );
			}
// debug( $presencesTmp );
// die();
			foreach( $options['CommissionepMembreep']['presence'] as $typepresence => $libelle ) {
				if( !isset( $presences["Presences_{$typepresence}"] ) ) {
					$presences["Presences_{$typepresence}"] = array();
				}
				$commissionep_data["presences_{$typepresence}_count"] = count( $presences["Presences_{$typepresence}"] );
			}

			// Nb de dossiers d'EP par thématique
			$themes = array();
			foreach( $dossierseps as $key => $theme ) {
				$themes["Themes_{$theme['Dossierep']['themeep']}"][] = array( 'Dossierep' => $theme['Dossierep'] );
			}
			foreach( $options['Dossierep']['themeep'] as $theme => $libelleTheme ) {
				if( !isset( $themes["Themes_{$theme}"] ) ) {
					$themes["Themes_{$theme}"] = array();
				}
				$commissionep_data["nbdossiers_{$theme}_count"] = count( $themes["Themes_{$theme}"] );
			}

// debug( $commissionep_data );
// debug($dossierseps);
// die();
			return $this->ged(
				array_merge(
					array(
						$commissionep_data,
						'Decisionseps' => $dossierseps,
					),
					$presences
				),
				"{$this->alias}/pv.odt",
				true,
				$options
			);
		}

		/**
		*
		*/

		/*public function getPdfOrdreDuJour( $commissionep_id ) {
			$commissionep_data = $this->find(
				'first',
				array(
					'conditions' => array(
						'Commissionep.id' => $commissionep_id
					),
					'contain' => false
				)
			);

			$queryData = array(
				'fields' => array(
					'Dossierep.id',
					'Dossierep.personne_id',
					'Passagecommissionep.commissionep_id',
					'Passagecommissionep.etatdossierep',
					'Dossierep.themeep',
					'Dossierep.created',
					'Dossierep.modified',
					//
					'Personne.id',
					'Personne.foyer_id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.nomnai',
					'Personne.prenom2',
					'Personne.prenom3',
					'Personne.nomcomnai',
					'Personne.dtnai',
					'Personne.rgnai',
					'Personne.typedtnai',
					'Personne.nir',
					'Personne.topvalec',
					'Personne.sexe',
					'Personne.nati',
					'Personne.dtnati',
					'Personne.pieecpres',
					'Personne.idassedic',
					'Personne.numagenpoleemploi',
					'Personne.dtinscpoleemploi',
					'Personne.numfixe',
					'Personne.numport',
					'Adresse.locaadr',
					'Adresse.numcomptt',
					'Adresse.codepos',
				),
				'joins' => array(
					array(
						'table'      => 'passagescommissionseps',
						'alias'      => 'Passagecommissionep',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( "Dossierep.id = Passagecommissionep.dossierep_id" ),
					),
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( "Dossierep.personne_id = Personne.id" ),
					),
					array(
						'table'      => 'foyers',
						'alias'      => 'Foyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.foyer_id = Foyer.id' )
					),
					array(
						'table'      => 'adressesfoyers',
						'alias'      => 'Adressefoyer',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Foyer.id = Adressefoyer.foyer_id',
							// FIXME: c'est un hack pour n'avoir qu'une seule adresse de range 01 par foyer!
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
				),
				'conditions' => array(
					'Passagecommissionep.commissionep_id' => $commissionep_id
				)
			);

			$options = array( 'Personne' => array( 'qual' => ClassRegistry::init( 'Option' )->qual() ) );
			$options = Set::merge( $options, $this->enums() );
			$options = Set::merge( $options, $this->Passagecommissionep->Dossierep->enums() );
			$options = Set::merge( $options, $this->Membreep->enums() );
			$options = Set::merge( $options, $this->CommissionepMembreep->enums() );
			$options = Set::merge( $options, $this->Passagecommissionep->enums() );

			$dossierseps = $this->Passagecommissionep->Dossierep->find( 'all', $queryData );
			// FIXME: faire la traduction des enums dans les modèles correspondants ?

			// present, excuse, FIXME: remplace_par
			$reponsesTmp = $this->CommissionepMembreep->find(
				'all',
				array(
					'conditions' => array(
						'CommissionepMembreep.commissionep_id' => $commissionep_id
					),
					'contain' => array(
						'Membreep' => array(
							'Fonctionmembreep'
						)
					)
				)
			);

			// FIXME: presence -> obliger de prendre les présences avant d'imprimer le PV
			$reponses = array();
			foreach( $reponsesTmp as $reponse ) {
				$reponses["Reponses_{$reponse['CommissionepMembreep']['reponse']}"][] = array( 'Membreep' => $reponse['Membreep'] );
			}
			foreach( $options['CommissionepMembreep']['reponse'] as $typereponse => $libelle ) {
				if( !isset( $reponses["Reponses_{$typereponse}"] ) ) {
					$reponses["Reponses_{$typereponse}"] = array();
				}
				$commissionep_data["reponses_{$typereponse}_count"] = count( $reponses["Reponses_{$typereponse}"] );
			}

			// Nb de dossiers d'EP par nom de commune
			///FIXME: voir comment faire pour avoir et la ville et le nombre correspondant
			$dossiersParCommune = array();
			$adresses = array();
			foreach( $dossierseps as $key => $adresse ) {
				$adresses["Dossiers_{$adresse['Adresse']['locaadr']}"][] = array( 'Adresse' => $adresse['Adresse'] );
				$locaadr = $adresse['Adresse']['locaadr'];
//                 $commissionep_data["nbdossiers_parville_count"][$locaadr] = count( $adresses["Dossiers_{$adresse['Adresse']['locaadr']}"] );
				//$commissionep_data["nbdossiers_{$locaadr}_count"] = count( $adresses["Dossiers_{$adresse['Adresse']['locaadr']}"] );
				$dossiersParCommune[] = array( 'ville' => $locaadr, 'count' => count( $adresses["Dossiers_{$adresse['Adresse']['locaadr']}"] ) );
			}

			// Nb de dossiers d'EP par thématique
			$themes = array();
			foreach( $dossierseps as $key => $theme ) {
				$themes["Themes_{$theme['Dossierep']['themeep']}"][] = array( 'Dossierep' => $theme['Dossierep'] );
			}
			foreach( $options['Dossierep']['themeep'] as $theme => $libelleTheme ) {
				if( !isset( $themes["Themes_{$theme}"] ) ) {
					$themes["Themes_{$theme}"] = array();
				}
//                 $commissionep_data["nbdossiers_{$theme}_count"] = count( $themes["Themes_{$theme}"] );
				$dossiersParTheme[] = array( 'theme' => $theme, 'count' => count( $themes["Themes_{$theme}"] ) );
			}

// debug( $dossiersParCommune );
// debug( $commissionep_data );
// debug($adresses);
// die();
			return $this->ged(
				array_merge(
					array(
						$commissionep_data,
						'Dossierseps' => $dossierseps,
						'DossiersParCommune' => $dossiersParCommune,
						'DossiersParTheme' => $dossiersParTheme
					),
					$reponses
				),
				"{$this->alias}/ordedujour.odt",
				true,
				$options
			);
		}*/

		/**
		*
		*/

		protected function _qdFichesSynthetiques( $conditions ) {
			return array(
				'fields' => array(
					'Dossierep.themeep',
					'Foyer.sitfam',
					'(
						SELECT
								dossiers.dtdemrsa
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
										nir_correct( "Personne"."nir" )
										AND nir_correct( personnes.nir )
										AND personnes.nir = "Personne"."nir"
										AND personnes.dtnai = "Personne"."dtnai"
									)
									OR
									(
										personnes.nom = "Personne"."nom"
										AND personnes.prenom = "Personne"."prenom"
										AND personnes.dtnai = "Personne"."dtnai"
									)
								)
							ORDER BY dossiers.dtdemrsa ASC
							LIMIT 1
					) AS "Dossier__dtdemrsa"',
					'( CASE WHEN "Serviceinstructeur"."lib_service" IS NULL THEN \'Hors département\' ELSE "Serviceinstructeur"."lib_service" END ) AS "Serviceinstructeur__lib_service"',
					'',
					'Orientstruct.date_valid',
					'( CASE WHEN "Historiqueetatpe"."etat" IN ( NULL, \'cessation\' ) THEN \'Non\' ELSE \'Oui\' END ) AS "Historiqueetatpe__inscritpe"',
					'Adresse.locaadr',
				),
				'joins' => array(
					array(
						'table'      => 'passagescommissionseps',
						'alias'      => 'Passagecommissionep',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( "Dossierep.id = Passagecommissionep.dossierep_id" ),
					),
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( "Dossierep.personne_id = Personne.id" ),
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
						'table'      => 'suivisinstruction',
						'alias'      => 'Suiviinstruction',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Suiviinstruction.dossier_id = Dossier.id',
							'Suiviinstruction.id IN (
								'.ClassRegistry::init( 'Suiviinstruction' )->sq(
									array(
										'alias' => 'suivisinstruction',
										'fields' => array( 'suivisinstruction.id' ),
										'conditions' => array( 'suivisinstruction.dossier_id = Dossier.id' ),
										'order' => array( 'suivisinstruction.date_etat_instruction DESC' ),
										'limit' => 1,
									)
								).'
							)',

						)
					),
					array(
						'table'      => 'servicesinstructeurs',
						'alias'      => 'Serviceinstructeur',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Suiviinstruction.numdepins = Serviceinstructeur.numdepins',
							'Suiviinstruction.typeserins = Serviceinstructeur.typeserins',
							'Suiviinstruction.numcomins = Serviceinstructeur.numcomins',
							'Suiviinstruction.numagrins = Serviceinstructeur.numagrins',
						)
					),
					array(
						'table'      => 'orientsstructs',
						'alias'      => 'Orientstruct',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Orientstruct.personne_id = Personne.id',
							'Orientstruct.id IN (
								'.ClassRegistry::init( 'Orientstruct' )->sq(
									array(
										'alias' => 'orientsstructs',
										'fields' => array( 'orientsstructs.id' ),
										'conditions' => array(
											'orientsstructs.personne_id = Personne.id',
											'orientsstructs.statut_orient' => 'Orienté',
										),
										'order' => array( 'orientsstructs.date_valid DESC' ),
										'limit' => 1,
									)
								).'
							)',
						)
					),
					array(
						'table'      => 'informationspe',
						'alias'      => 'Informationpe',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'OR' => array(
								array(
									'Informationpe.nir IS NOT NULL',
									'Personne.nir IS NOT NULL',
									'Informationpe.nir = Personne.nir',
								),
								array(
									'Informationpe.nom = Personne.nom',
									'Informationpe.prenom = Personne.prenom',
									'Informationpe.dtnai = Personne.dtnai',
								)
							)
						)
					),
					array(
						'table'      => 'historiqueetatspe',
						'alias'      => 'Historiqueetatpe',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Historiqueetatpe.informationpe_id = Informationpe.id',
							'Historiqueetatpe.id IN (
										SELECT h.id
											FROM historiqueetatspe AS h
											WHERE h.informationpe_id = Informationpe.id
											ORDER BY h.date DESC
											LIMIT 1
							)'
						)
					),
					array(
						'table'      => 'adressesfoyers',
						'alias'      => 'Adressefoyer',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Foyer.id = Adressefoyer.foyer_id',
							// FIXME: c'est un hack pour n'avoir qu'une seule adresse de range 01 par foyer!
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
				),
				'conditions' => $conditions,
				'order' => array(
					'Dossierep.themeep DESC',
					'Adresse.locaadr ASC'
				)
			);
		}

		/**
		*   Impression de convocation pour un participant à une commission d'EP
		*/

		public function getPdfConvocationParticipant( $commissionep_id, $membreep_id ) {
			// Participant auquel la convocation doit être envoyée
// 			$convocation = $this->CommissionepMembreep->find(
// 				'first',
// 				array(
// 					'conditions' => array(
// 						'CommissionepMembreep.id' => $commissionep_membreep_id
// 					),
// 					'contain' => array(
// 						'Commissionep' => array(
// 							'Ep' => array(
// 								'Regroupementep'
// 							)
// 						),
// 						'Membreep' => array(
// 							'Fonctionmembreep'
// 						)
// 					)
// 				)
// 			);

			$commissionep = $this->find(
				'first',
				array(
					'conditions' => array(
						'Commissionep.id' => $commissionep_id
					),
					'contain' => array(
						'Ep' => array(
							'Regroupementep'
						)
					)
				)
			);

			$membreep = $this->Membreep->find(
				'first',
				array(
					'conditions' => array(
						'Membreep.id' => $membreep_id
					),
					'contain' => array(
						'Fonctionmembreep'
					)
				)
			);

			$convocation = Set::merge( $commissionep, $membreep );

// debug($convocation);
// die();

			$options = $this->Membreep->enums();
			$options['Membreep']['typevoie'] = ClassRegistry::init( 'Option' )->typevoie();

			return $this->ged(
				$convocation,
				"{$this->alias}/convocationep_participant.odt",
				false,
				$options
			);

		}

		/**
		*   Impression de l'ordre du jour pour un participant à une commission d'EP
		*/

		public function getPdfOrdredujour( $commissionep_membreep_id ) {
			// Participant auquel la convocation doit être envoyée
			$convocation = $this->CommissionepMembreep->find(
				'first',
				array(
					'conditions' => array(
						'CommissionepMembreep.id' => $commissionep_membreep_id
					),
					'contain' => array(
						'Commissionep' => array(
							'Ep' => array(
								'Regroupementep'
							)
						),
						'Membreep' => array(
							'Fonctionmembreep'
						)
					)
				)
			);

			// Si le membre est remplacé par un autre, il faut aller cherche le remplaçant
			if( $convocation['CommissionepMembreep']['reponse'] == 'remplacepar' ) {
				$membreep = $this->Membreep->find(
					'first',
					array(
						'conditions' => array(
							'Membreep.id' => $convocation['CommissionepMembreep']['reponsesuppleant_id']
						),
						'contain' => false
					)
				);
				$convocation = Set::merge( $convocation, $membreep );
			}

			$convocation = array( 'Participant' => $convocation['Membreep'], 'Commissionep' => $convocation['Commissionep'] );

			// FIXME: doc
			if ( Configure::read( 'Cg.departement' ) == 93 || Configure::read( 'Cg.departement' ) == 58 ) {
				$queryData = array(
					'fields' => array(
						'Dossierep.themeep',
						'Adresse.locaadr',
						'COUNT("Dossierep"."id") AS "nombre"',
					),
					'joins' => array(
						array(
							'table'      => 'passagescommissionseps',
							'alias'      => 'Passagecommissionep',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( "Dossierep.id = Passagecommissionep.dossierep_id" ),
						),
						array(
							'table'      => 'personnes',
							'alias'      => 'Personne',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( "Dossierep.personne_id = Personne.id" ),
						),
						array(
							'table'      => 'foyers',
							'alias'      => 'Foyer',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Personne.foyer_id = Foyer.id' )
						),
						array(
							'table'      => 'adressesfoyers',
							'alias'      => 'Adressefoyer',
							'type'       => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array(
								'Foyer.id = Adressefoyer.foyer_id',
								// FIXME: c'est un hack pour n'avoir qu'une seule adresse de rang 01 par foyer!
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
					),
					'conditions' => array(
						'Passagecommissionep.commissionep_id' => $convocation['Commissionep']['id']
					),
					'group' => array(
						'Dossierep.themeep',
						'Adresse.locaadr'
					),
					'order' => array(
						'Adresse.locaadr ASC'
					)
				);
			}
			else {
				$queryData = array(
					'fields' => array(
						'Dossierep.id',
						'Dossierep.personne_id',
						'Passagecommissionep.commissionep_id',
						'Passagecommissionep.etatdossierep',
						'Dossierep.themeep',
						'Dossierep.created',
						'Dossierep.modified',
						//
						'Personne.id',
						'Personne.foyer_id',
						'Personne.qual',
						'Personne.nom',
						'Personne.prenom',
						'Personne.nomnai',
						'Personne.prenom2',
						'Personne.prenom3',
						'Personne.nomcomnai',
						'Personne.dtnai',
						'Personne.rgnai',
						'Personne.typedtnai',
						'Personne.nir',
						'Personne.topvalec',
						'Personne.sexe',
						'Personne.nati',
						'Personne.dtnati',
						'Personne.pieecpres',
						'Personne.idassedic',
						'Personne.numagenpoleemploi',
						'Personne.dtinscpoleemploi',
						'Personne.numfixe',
						'Personne.numport',
						'Adresse.locaadr',
						'Adresse.numcomptt',
						'Adresse.codepos',
						//
						'Defautinsertionep66.origine'
					),
					'joins' => array(
						array(
							'table'      => 'defautsinsertionseps66',
							'alias'      => 'Defautinsertionep66',
							'type'       => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array( "Dossierep.id = Defautinsertionep66.dossierep_id" ),
						),
						array(
							'table'      => 'passagescommissionseps',
							'alias'      => 'Passagecommissionep',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( "Dossierep.id = Passagecommissionep.dossierep_id" ),
						),
						array(
							'table'      => 'personnes',
							'alias'      => 'Personne',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( "Dossierep.personne_id = Personne.id" ),
						),
						array(
							'table'      => 'foyers',
							'alias'      => 'Foyer',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Personne.foyer_id = Foyer.id' )
						),
						array(
							'table'      => 'adressesfoyers',
							'alias'      => 'Adressefoyer',
							'type'       => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array(
								'Foyer.id = Adressefoyer.foyer_id',
								// FIXME: c'est un hack pour n'avoir qu'une seule adresse de rang 01 par foyer!
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
					),
					'conditions' => array(
						'Passagecommissionep.commissionep_id' => $convocation['Commissionep']['id']
					)
				);
			}

			$options = array( 'Personne' => array( 'qual' => ClassRegistry::init( 'Option' )->qual() ) );
			$options = Set::merge( $options, $this->enums() );
			$options = Set::merge( $options, $this->Passagecommissionep->Dossierep->enums() );
			$options = Set::merge( $options, $this->Membreep->enums() );
			$options['Participant'] = $options['Membreep'];
			$options = Set::merge( $options, $this->CommissionepMembreep->enums() );
			$options = Set::merge( $options, $this->Passagecommissionep->enums() );
			$options = Set::merge( $options, $this->Passagecommissionep->Dossierep->Defautinsertionep66->enums() );
			$options['Participant']['typevoie'] = ClassRegistry::init( 'Option' )->typevoie();
			$options['Remplacantmembreep'] = $options['Membreep'];

			$dossierseps = $this->Passagecommissionep->Dossierep->find( 'all', $queryData );
			// FIXME: faire la traduction des enums dans les modèles correspondants ?

			// FIXME: documentation
			$themesTraites = $this->themesTraites( $convocation['Commissionep']['id'] );
			$themesTraites = array_keys( $themesTraites );
			sort( $themesTraites );

			if ( Configure::read( 'Cg.departement' ) == 93 || Configure::read( 'Cg.departement' ) == 58 ) {
				$dossiersParCommune = array();
				foreach( $dossierseps as $dossierep ) {
					$commune = $dossierep['Adresse']['locaadr'];
					if( !isset( $dossiersParCommune[$commune] ) ) {
						$dossiersParCommune[$commune] = array();
					}
					$dossiersParCommune[$commune][$dossierep['Dossierep']['themeep']] = $dossierep[0]['nombre'];
				}

				$dossierseps = array();
				$default = array();
				foreach( $themesTraites as $themeTraite ) {
					$default[Inflector::pluralize($themeTraite)] = 0;
				}

				foreach( $dossiersParCommune as $commune => $dossierParCommune ) {
					$dossierParCommune = array_merge( array( 'commune' => $commune ), $default, $dossierParCommune );
					$dossierParCommune['total'] = array_sum( $dossierParCommune );
					$dossierseps[] = $dossierParCommune;
				}
			}

			// present, excuse, FIXME: remplace_par
			$reponsesTmp = $this->CommissionepMembreep->find(
				'all',
				array(
					'conditions' => array(
						'CommissionepMembreep.commissionep_id' => $convocation['Commissionep']['id']
					),
					'contain' => array(
						'Membreep' => array(
							'Fonctionmembreep'
						),
						'Remplacantmembreep'
					)
				)
			);

			$reponses = array();
			foreach( $reponsesTmp as $reponse ) {
				$reponses["Reponses_{$reponse['CommissionepMembreep']['reponse']}"][] = array( 'Membreep' => $reponse['Membreep'], 'Remplacantmembreep' => $reponse['Remplacantmembreep'] );
			}
			foreach( $options['CommissionepMembreep']['reponse'] as $typereponse => $libelle ) {
				if( !isset( $reponses["Reponses_{$typereponse}"] ) ) {
					$reponses["Reponses_{$typereponse}"] = array();
				}
				$commissionep_data["reponses_{$typereponse}_count"] = count( $reponses["Reponses_{$typereponse}"] );
			}

			// Fiches synthétiques des dossiers d'EP
			$fichessynthetiques = $this->Passagecommissionep->Dossierep->find(
				'all',
				$this->_qdFichesSynthetiques( array( 'Passagecommissionep.commissionep_id' => $convocation['Commissionep']['id'] ) )
			);

			$options['Foyer']['sitfam'] = ClassRegistry::init( 'Option' )->sitfam();

			return $this->ged(
				array_merge(
					array(
						$convocation,
						'Dossierseps' => $dossierseps,
						'Fichessynthetiques' => $fichessynthetiques
					),
					$reponses
				),
				"{$this->alias}/ordredujour_participant_".Configure::read( 'Cg.departement' ).".odt",
				true,
				$options
			);
		}



		/**
		*
		*/

		public function getPdfDecision( $commissionep_id ) {

			$commissionep_data = $this->find(
				'first',
				array(
					'conditions' => array(
						'Commissionep.id' => $commissionep_id
					),
					'contain' => false
				)
			);

			$queryData = array(
				'fields' => array(
					'Dossierep.id',
					'Dossierep.personne_id',
					'Dossierep.themeep',
					'Dossierep.created',
					'Dossierep.modified',
					//
					'Personne.id',
					'Personne.foyer_id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.nomnai',
					'Personne.prenom2',
					'Personne.prenom3',
					'Personne.nomcomnai',
					'Personne.dtnai',
					'Personne.rgnai',
					'Personne.typedtnai',
					'Personne.nir',
					'Personne.topvalec',
					'Personne.sexe',
					'Personne.nati',
					'Personne.dtnati',
					'Personne.pieecpres',
					'Personne.idassedic',
					'Personne.numagenpoleemploi',
					'Personne.dtinscpoleemploi',
					'Personne.numfixe',
					'Personne.numport',
					'Adresse.locaadr',
					'Adresse.numcomptt',
					'Adresse.codepos',

				),
				'joins' => array(
					array(
						'table'      => 'passagescommissionseps',
						'alias'      => 'Passagecommissionep',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Passagecommissionep.dossierep_id = Dossierep.id',
							'Passagecommissionep.commissionep_id' => $commissionep_id,
						),
					),
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( "Dossierep.personne_id = Personne.id" ),
					),
					array(
						'table'      => 'foyers',
						'alias'      => 'Foyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.foyer_id = Foyer.id' )
					),
					array(
						'table'      => 'adressesfoyers',
						'alias'      => 'Adressefoyer',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Foyer.id = Adressefoyer.foyer_id',
							// FIXME: c'est un hack pour n'avoir qu'une seule adresse de range 01 par foyer!
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
				)
			);

			$options = array( 'Personne' => array( 'qual' => ClassRegistry::init( 'Option' )->qual() ) );
			foreach( $this->themesTraites( $commissionep_id ) as $theme => $decision ) {
				$model = Inflector::classify( $theme );
				if( in_array( 'Enumerable', $this->Passagecommissionep->Dossierep->{$model}->Behaviors->attached() ) ) {
					$options = Set::merge( $options, $this->Passagecommissionep->Dossierep->{$model}->enums() );
				}

				$modeleDecision = Inflector::classify( "decision{$theme}" );
				if( in_array( 'Enumerable', $this->Passagecommissionep->{$modeleDecision}->Behaviors->attached() ) ) {
					$options = Set::merge( $options, $this->Passagecommissionep->{$modeleDecision}->enums() );
				}

				foreach( array( 'fields', 'joins' ) as $key ) {
					$qdModele = $this->Passagecommissionep->Dossierep->{$model}->qdProcesVerbal();
					$queryData[$key] = array_merge( $queryData[$key], $qdModele[$key] );
				}
			}
			$options = Set::merge( $options, $this->enums() );
			$options = Set::merge( $options, $this->Passagecommissionep->Dossierep->enums() );
			$options = Set::merge( $options, $this->Membreep->enums() );
			$options = Set::merge( $options, $this->CommissionepMembreep->enums() );

			$dossierseps = $this->Passagecommissionep->Dossierep->find( 'all', $queryData );
			// FIXME: faire la traduction des enums dans les modèles correspondants ?
// debug($dossierseps);
// die();
			return $this->ged(
				array_merge(
					array(
						$commissionep_data,
						'Dossierseps' => $dossierseps
					)
				),
				"{$this->alias}/decisionep.odt",
				true,
				$options
			);
		}


		public function getFicheSynthese( $commissionep_id, $dossierep_id ) {

//             $commissionep = $this->find(
//                 'first',
//                 array(
//                     'conditions' => array(
//                         'Commissionep.id' => $commissionep_id
//                     ),
//                     'contain' => array(
//                         'Ep' => array(
//                              'Regroupementep'
//                          )
//                     )
//                 )
//             );
// 
//             $dossierep = $this->Passagecommissionep->Dossierep->find(
//                 'first',
//                 array(
//                     'conditions' => array(
//                         'Dossierep.id' => $dossierep_id
//                     ),
//                     'contain' => false
//                 )
//             );
// 
//             $fichesynthese = Set::merge( $commissionep, $dossierep );

			$queryData = array(
				'fields' => array(
					'Dossierep.id',
					'Dossierep.personne_id',
					'Dossierep.themeep',
					'Dossierep.created',
					'Dossierep.modified',
					//
					'Personne.id',
					'Personne.foyer_id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.nomnai',
					'Personne.prenom2',
					'Personne.prenom3',
					'Personne.nomcomnai',
					'Personne.dtnai',
					'Personne.rgnai',
					'Personne.typedtnai',
					'Personne.nir',
					'Personne.topvalec',
					'Personne.sexe',
					'Personne.nati',
					'Personne.dtnati',
					'Personne.pieecpres',
					'Personne.idassedic',
					'Personne.numagenpoleemploi',
					'Personne.dtinscpoleemploi',
					'Personne.numfixe',
					'Personne.numport',
					'Dossier.matricule',
					'Foyer.sitfam',
					'Adresse.numvoie',
					'Adresse.typevoie',
					'Adresse.nomvoie',
					'Adresse.compladr',
					'Adresse.locaadr',
					'Adresse.numcomptt',
					'Adresse.codepos',

				),
				'joins' => array(
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( "Dossierep.personne_id = Personne.id" ),
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
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
					)
				),
				'conditions' => array(
					'Dossierep.id' => $dossierep_id
				)
			);

			// Fiches synthétiques des dossiers d'EP
			$fichessynthetiques = $this->Passagecommissionep->Dossierep->find(
				'first',
				$this->_qdFichesSynthetiques( array( 'Passagecommissionep.commissionep_id' => $commissionep_id ) )
			);
			
			$dossierep = $this->Passagecommissionep->Dossierep->find( 'first', $queryData );
			$dataFiche = Set::merge( $dossierep, $fichessynthetiques );

			$options['Foyer']['sitfam'] = ClassRegistry::init( 'Option' )->sitfam();

			return $this->ged(
				$dataFiche,
				"{$this->alias}/fichesynthese.odt",
				false,
				$options
			);
		}

	}
?>
