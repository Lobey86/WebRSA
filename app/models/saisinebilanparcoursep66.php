<?php
	require_once( ABSTRACTMODELS.'thematiqueep.php' );

	/**
	* Saisines d'EP pour les bilans de parcours pour le conseil général du
	* département 66.
	*
	* Une saisine regoupe plusieurs thèmes des EPs pour le CG 66.
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.models
	*/

	class Saisinebilanparcoursep66 extends Thematiqueep
	{
		public $name = 'Saisinebilanparcoursep66';

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Formattable' => array(
				'suffix' => array(
					'typeorient_id',
					'structurereferente_id'
				)
			),
			'Gedooo.Gedooo',
			'Enumerable' => array(
				'fields' => array(
					'choixparcours',
					'maintienorientparcours',
					'changementrefparcours',
					'reorientation'
				)
			)
		);

		public $belongsTo = array(
			'Bilanparcours66' => array(
				'className' => 'Bilanparcours66',
				'foreignKey' => 'bilanparcours66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Dossierep' => array(
				'className' => 'Dossierep',
				'foreignKey' => 'dossierep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Structurereferente' => array(
				'className' => 'Structurereferente',
				'foreignKey' => 'structurereferente_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Typeorient' => array(
				'className' => 'Typeorient',
				'foreignKey' => 'typeorient_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

		/**
		* Chemin relatif pour les modèles de documents .odt utilisés lors des
		* impressions. Utiliser %s pour remplacer par l'alias.
		*/
		public $modelesOdt = array(
			// Convocation EP
			'Commissionep/convocationep_beneficiaire.odt',
			// Décision EP (décision CG)
			'%s/decision_maintien_avec_changement.odt',
			'%s/decision_maintien_sans_changement.odt',
			'%s/decision_reorientation.odt',
			'%s/decision_reporte.odt',
			'%s/decision_annule.odt',
		);

		/**
		*
		*/
		public function containQueryData() {
			return array(
				'Saisinebilanparcoursep66' => array(
					'Decisionsaisinebilanparcoursep66'=>array(
						'Typeorient',
						'Structurereferente'
					),
				)
			);
		}


		/**
		* TODO: comment finaliser l'orientation précédente ?
		* FIXME: à ne faire que quand le cg valide sa décision
		*/

		public function finaliser( $commissionep_id, $etape, $user_id ) {
			$dossierseps = $this->Dossierep->find(
				'all',
				array(
					'conditions' => array(
						'Dossierep.themeep' => Inflector::tableize( $this->alias ),
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
						$this->alias => array(
							'Bilanparcours66' => array(
								'Orientstruct'
							)
						),
						'Passagecommissionep' => array(
							'conditions' => array(
								'Passagecommissionep.commissionep_id' => $commissionep_id
							),
							'Decisionsaisinebilanparcoursep66' => array(
								'conditions' => array(
									'Decisionsaisinebilanparcoursep66.etape' => $etape
								)
							)
						)
					)
				)
			);
			$typeOrientPrincipaleEmploiId = Configure::read( 'Orientstruct.typeorientprincipale.Emploi' );
			if( is_array( $typeOrientPrincipaleEmploiId ) && isset( $typeOrientPrincipaleEmploiId[0] ) ){
				$typeOrientPrincipaleEmploiId = $typeOrientPrincipaleEmploiId[0];
			}
			else{
				trigger_error( __( 'Le type orientation principale Emploi n\'est pas bien défini.', true ), E_USER_WARNING );
			}



			$success = true;
			foreach( $dossierseps as $dossierep ) {
				if( $dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['decision'] == 'maintien' && $dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['changementrefparcours'] == 'N' && ( $dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['typeorientprincipale_id'] != $typeOrientPrincipaleEmploiId ) ) {
					$vxContratinsertion = $this->Bilanparcours66->Contratinsertion->find(
						'first',
						array(
							'conditions' => array(
								'Contratinsertion.personne_id' => $dossierep['Saisinebilanparcoursep66']['Bilanparcours66']['Orientstruct']['personne_id'],
								'Contratinsertion.structurereferente_id' => $dossierep['Saisinebilanparcoursep66']['Bilanparcours66']['Orientstruct']['structurereferente_id']
							),
							'contain' => false
						)
					);
					if( empty( $vxContratinsertion ) ) {
						$vxContratinsertion = array();
					}
					$contratinsertion = $vxContratinsertion;
					unset( $contratinsertion['Contratinsertion']['id'] );
					$contratinsertion['Contratinsertion']['dd_ci'] = $dossierep['Saisinebilanparcoursep66']['Bilanparcours66']['ddreconductoncontrat'];
					$contratinsertion['Contratinsertion']['df_ci'] = $dossierep['Saisinebilanparcoursep66']['Bilanparcours66']['dfreconductoncontrat'];
					$contratinsertion['Contratinsertion']['duree_engag'] = $dossierep['Saisinebilanparcoursep66']['Bilanparcours66']['duree_engag'];

					$idRenouvellement = $this->Bilanparcours66->Contratinsertion->Typocontrat->field( 'Typocontrat.id', array( 'Typocontrat.lib_typo' => 'Renouvellement' ) );
					$contratinsertion['Contratinsertion']['typocontrat_id'] = $idRenouvellement;
					$contratinsertion['Contratinsertion']['rg_ci'] = ( Set::classicExtract( $contratinsertion, 'Contratinsertion.rg_ci' ) + 1 );

					// La date de validation est à null afin de pouvoir modifier le contrat
					$contratinsertion['Contratinsertion']['datevalidation_ci'] = null;
					// La date de saisie du nouveau contrat est égale à la date du jour
					$contratinsertion['Contratinsertion']['date_saisi_ci'] = date( 'Y-m-d' );

					unset($contratinsertion['Contratinsertion']['decision_ci']);
					unset($contratinsertion['Contratinsertion']['datevalidation_ci']);

					$fields = array( 'actions_prev', 'aut_expr_prof', 'emp_trouv', 'sect_acti_emp', 'emp_occupe', 'duree_hebdo_emp', 'nat_cont_trav', 'duree_cdd', 'niveausalaire' ); // FIXME: une variable du modèle
					foreach( $fields as $field ) {
						unset( $contratinsertion['Contratinsertion'][$field] );
					}

					$this->Bilanparcours66->Contratinsertion->create( $contratinsertion );
					$success = $this->Bilanparcours66->Contratinsertion->save() && $success;
				}
				elseif ( $dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['decision'] == 'maintien' || $dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['decision'] == 'reorientation' ) {
					$orientstruct = array(
						'Orientstruct' => array(
							'personne_id' => $dossierep[$this->alias]['Bilanparcours66']['Orientstruct']['personne_id'],
							'typeorient_id' => $dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['typeorient_id'],
							'structurereferente_id' => $dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['structurereferente_id'],
							'date_propo' => date( 'Y-m-d' ),
							'date_valid' => date( 'Y-m-d' ),
							'statut_orient' => 'Orienté',
							'user_id' => $user_id
						)
					);
					$this->Bilanparcours66->Orientstruct->create( $orientstruct );
					$success = $this->Bilanparcours66->Orientstruct->save() && $success;
				}

				if( !empty( $dossierep['Bilanparcours66']['contratinsertion_id'] ) ) {
					$this->Bilanparcours66->Orientstruct->Personne->Contratinsertion->updateAll(
						array( 'Contratinsertion.df_ci' => "'".date( 'Y-m-d' )."'" ),
						array(
							'"Contratinsertion"."personne_id"' => $dossierep['Bilanparcours66']['Orientstruct']['personne_id'],
							'"Contratinsertion"."id"' => $dossierep['Bilanparcours66']['contratinsertion_id']
						)
					);
				}


				$this->Bilanparcours66->Orientstruct->Personne->PersonneReferent->updateAll(
					array( 'PersonneReferent.dfdesignation' => "'".date( 'Y-m-d' )."'" ),
					array(
						'"PersonneReferent"."personne_id"' => $dossierep[$this->alias]['Bilanparcours66']['Orientstruct']['personne_id'],
						'"PersonneReferent"."dfdesignation" IS NULL'
					)
				);

				if( !empty( $dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['referent_id'] ) ) {
					$referent = array(
						'PersonneReferent' => array(
							'personne_id' => $dossierep[$this->alias]['Bilanparcours66']['Orientstruct']['personne_id'],
							'referent_id' => $dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['referent_id'],
							'dddesignation' => date( 'Y-m-d' ),
							'structurereferente_id' => $dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['structurereferente_id'],
							'user_id' => $user_id
						)
					);
					$this->Bilanparcours66->Orientstruct->Personne->PersonneReferent->create( $referent );
					$success = $this->Bilanparcours66->Orientstruct->Personne->PersonneReferent->save() && $success;
				}
			}

			return $success;
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
							'Adressefoyer' => array(
								'conditions' => array(
									'Adressefoyer.rgadr' => '01'
								),
								'Adresse'
							)
						)
					),
					$this->alias => array(
						'Typeorient',
						'Structurereferente',
						'Bilanparcours66' => array(
							'Orientstruct' => array(
								'Typeorient',
								'Structurereferente',
							),
						)
					),
					'Passagecommissionep' => array(
						'conditions' => array(
							'Passagecommissionep.commissionep_id' => $commissionep_id
						),
						'Decisionsaisinebilanparcoursep66' => array(
							'order' => array(
								'Decisionsaisinebilanparcoursep66.etape DESC'
							),
							'Typeorient',
							'Structurereferente'
						),
					)
				)
			);
		}

		/**
		*
		*/

		public function saveDecisions( $data, $niveauDecision ) {
			// Calcul du changement de référent
			if( isset( $data['Bilanparcours66'] ) && !empty( $data['Bilanparcours66'] ) ) {
				foreach( $data['Bilanparcours66'] as $i => $values ) {
					if ( isset( $data['Decisionsaisinebilanparcoursep66'][$i]['structurereferente_id'] ) && !empty( $data['Decisionsaisinebilanparcoursep66'][$i]['structurereferente_id'] ) ) {
						list( $typeorient_id, $structurereferente_id ) = explode( '_', $data['Decisionsaisinebilanparcoursep66'][$i]['structurereferente_id'] );
						if ( $values['oldstructurereferente_id'] == $structurereferente_id ) {
							$data['Decisionsaisinebilanparcoursep66'][$i]['changementrefparcours'] = 'N';
						}
						else {
							$data['Decisionsaisinebilanparcoursep66'][$i]['changementrefparcours'] = 'O';
						}
					}
				}
			}

			// Filtrage des données
			$themeData = Set::extract( $data, '/Decisionsaisinebilanparcoursep66' );
			if( empty( $themeData ) ) {
				return true;
			}
			else {
				$success = $this->Dossierep->Passagecommissionep->Decisionsaisinebilanparcoursep66->saveAll( $themeData, array( 'atomic' => false ) );
				$passagescommissionseps_ids = Set::extract( $themeData, '/Decision'.Inflector::underscore( $this->alias ).'/passagecommissionep_id' );

				// Mise à jour de l'état du passage en commission EP
				$success = $this->Dossierep->Passagecommissionep->updateAll(
					array( 'Passagecommissionep.etatdossierep' => '\'decision'.$niveauDecision.'\'' ),
					array( '"Passagecommissionep"."id"' => $passagescommissionseps_ids )
				) && $success;

				// Mise à jour de la position du bilan de parcours
				$success = $this->Bilanparcours66->updatePositionBilanDecisionsEp( $this->name, $themeData, $niveauDecision, $passagescommissionseps_ids ) && $success;
				return $success;
			}
		}

		/**
		* Prépare les données du formulaire d'un niveau de décision
		* en prenant en compte les données du bilan ou du niveau de décision
		* précédent.
		*
		* @param integer $commissionep_id L'id technique de la séance d'EP
		* @param array $datas Les données des dossiers
		* @param string $niveauDecision Le niveau de décision pour lequel il
		* 	faut préparer les données du formulaire
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
				$formData['Decisionsaisinebilanparcoursep66'][$key]['passagecommissionep_id'] = @$datas[$key]['Passagecommissionep'][0]['id'];

				// On modifie les enregistrements de cette étape
				if( @$dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['etape'] == $niveauDecision ) {
					$formData['Decisionsaisinebilanparcoursep66'][$key] = @$dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0];
					$formData['Decisionsaisinebilanparcoursep66'][$key]['checkcomm'] = !empty( $dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['commentaire'] );
					$formData['Decisionsaisinebilanparcoursep66'][$key]['referent_id'] = implode(
						'_',
						array(
							$formData['Decisionsaisinebilanparcoursep66'][$key]['structurereferente_id'],
							$formData['Decisionsaisinebilanparcoursep66'][$key]['referent_id']
						)
					);
					$formData['Decisionsaisinebilanparcoursep66'][$key]['structurereferente_id'] = implode(
						'_',
						array(
							$formData['Decisionsaisinebilanparcoursep66'][$key]['typeorient_id'],
							$formData['Decisionsaisinebilanparcoursep66'][$key]['structurereferente_id']
						)
					);
					$formData['Decisionsaisinebilanparcoursep66'][$key]['typeorient_id'] = implode(
						'_',
						array(
							$formData['Decisionsaisinebilanparcoursep66'][$key]['typeorientprincipale_id'],
							$formData['Decisionsaisinebilanparcoursep66'][$key]['typeorient_id']
						)
					);
				}
				// On ajoute les enregistrements de cette étape
				else {
					if( $niveauDecision == 'ep' ) {
						$formData['Decisionsaisinebilanparcoursep66'][$key]['decision'] = $dossierep[$this->alias]['choixparcours'];
						$formData['Decisionsaisinebilanparcoursep66'][$key]['checkcomm'] = 0;
						$formData['Decisionsaisinebilanparcoursep66'][$key]['typeorientprincipale_id'] = $dossierep[$this->alias]['typeorientprincipale_id'];

						$formData['Decisionsaisinebilanparcoursep66'][$key]['structurereferente_id'] = implode(
							'_',
							array(
								$dossierep[$this->alias]['typeorient_id'],
								$dossierep[$this->alias]['structurereferente_id']
							)
						);
						$formData['Decisionsaisinebilanparcoursep66'][$key]['typeorient_id'] = implode(
							'_',
							array(
								$dossierep[$this->alias]['typeorientprincipale_id'],
								$dossierep[$this->alias]['typeorient_id']
							)
						);
						if ( $dossierep[$this->alias]['Bilanparcours66']['changereferent'] == 'O' ) {
							$formData['Decisionsaisinebilanparcoursep66'][$key]['referent_id'] = implode(
								'_',
								array(
									$dossierep[$this->alias]['Bilanparcours66']['Orientstruct']['structurereferente_id'],
									$dossierep[$this->alias]['Bilanparcours66']['Orientstruct']['referent_id']
								)
							);
						}
					}
					elseif( $niveauDecision == 'cg' ) {
						$formData['Decisionsaisinebilanparcoursep66'][$key]['decision'] = $dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['decision'];
						$formData['Decisionsaisinebilanparcoursep66'][$key]['commentaire'] = $dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['commentaire'];
						$formData['Decisionsaisinebilanparcoursep66'][$key]['checkcomm'] = !empty( $dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['commentaire'] );
						$formData['Decisionsaisinebilanparcoursep66'][$key]['typeorientprincipale_id'] = $dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['typeorientprincipale_id'];
						$formData['Decisionsaisinebilanparcoursep66'][$key]['referent_id'] = implode(
							'_',
							array(
								$dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['structurereferente_id'],
								$dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['referent_id']
							)
						);
						$formData['Decisionsaisinebilanparcoursep66'][$key]['structurereferente_id'] = implode(
							'_',
							array(
								$dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['typeorient_id'],
								$dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['structurereferente_id']
							)
						);
						$formData['Decisionsaisinebilanparcoursep66'][$key]['typeorient_id'] = implode(
							'_',
							array(
								$dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['typeorientprincipale_id'],
								$dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['typeorient_id']
							)
						);
					}
				}
			}

			return $formData;
		}

		/**
		*
		*/

		public function prepareFormDataUnique( $dossierep_id, $dossierep, $niveauDecision ) {
			$formData = array();
			return $formData;
		}

		/**
		*
		*/

		public function qdProcesVerbal() {
			return array(
				'fields' => array(
					'Saisinebilanparcoursep66.id',
					'Saisinebilanparcoursep66.bilanparcours66_id',
					'Saisinebilanparcoursep66.dossierep_id',
					'Saisinebilanparcoursep66.typeorient_id',
					'Saisinebilanparcoursep66.structurereferente_id',
					'Saisinebilanparcoursep66.created',
					'Saisinebilanparcoursep66.modified',
					'Decisionsaisinebilanparcoursep66.id',
					'Decisionsaisinebilanparcoursep66.etape',
					'Decisionsaisinebilanparcoursep66.decision',
					'Decisionsaisinebilanparcoursep66.typeorient_id',
					'Decisionsaisinebilanparcoursep66.structurereferente_id',
					'Decisionsaisinebilanparcoursep66.commentaire',
					'Decisionsaisinebilanparcoursep66.created',
					'Decisionsaisinebilanparcoursep66.modified',
					'Decisionsaisinebilanparcoursep66.raisonnonpassage',
				),
				'joins' => array(
					array(
						'table'      => 'saisinesbilansparcourseps66',
						'alias'      => 'Saisinebilanparcoursep66',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Saisinebilanparcoursep66.dossierep_id = Dossierep.id' ),
					),
					array(
						'table'      => 'decisionssaisinesbilansparcourseps66',
						'alias'      => 'Decisionsaisinebilanparcoursep66',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Decisionsaisinebilanparcoursep66.passagecommissionep_id = Passagecommissionep.id',
							'Decisionsaisinebilanparcoursep66.etape' => 'ep'
						),
					),
				)
			);
		}

		/**
		* Récupération du courrier de convocation à l'allocataire pour un passage
		* en commission donné.
		*/
		public function getConvocationBeneficiaireEpPdf( $passagecommissionep_id ) {
			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ );
			$datas = Cache::read( $cacheKey );

			if( $datas === false ) {
				$datas = $this->_qdConvocationBeneficiaireEpPdf();

				Cache::write( $cacheKey, $datas );
			}

			$datas['querydata']['conditions']['Passagecommissionep.id'] = $passagecommissionep_id;
			$gedooo_data = $this->Dossierep->Passagecommissionep->find( 'first', $datas['querydata'] );
			$modeleOdt = 'Commissionep/convocationep_beneficiaire.odt';

			if( empty( $gedooo_data ) ) {
				return false;
			}

			return $this->ged(
				$gedooo_data,
				$modeleOdt,
				false,
				$datas['options']
			);
		}

		/**
		* Fonction retournant un querydata qui va permettre de retrouver des dossiers d'EP
		*/
		public function qdListeDossier( $commissionep_id = null ) {
			$return = array(
				'fields' => array(
					'Dossierep.id',
					'Personne.id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Adresse.locaadr',
					'Dossierep.created',
					'Dossierep.themeep',
					'Passagecommissionep.id',
					'Passagecommissionep.commissionep_id',
					'Passagecommissionep.etatdossierep',
				)
			);

			if( !empty( $commissionep_id ) ) {
				$join = array(
					'alias' => 'Dossierep',
					'table' => 'dossierseps',
					'type' => 'INNER',
					'conditions' => array(
						'Dossierep.id = '.$this->alias.'.dossierep_id'
					)
				);
			}
			else {
				$join = array(
					'alias' => $this->alias,
					'table' => Inflector::tableize( $this->alias ),
					'type' => 'INNER',
					'conditions' => array(
						'Dossierep.id = '.$this->alias.'.dossierep_id'
					)
				);
			}

			$return['joins'] = array(
				$join,
				array(
					'alias' => 'Personne',
					'table' => 'personnes',
					'type' => 'INNER',
					'conditions' => array(
						'Dossierep.personne_id = Personne.id'
					)
				),
				array(
					'alias' => 'Foyer',
					'table' => 'foyers',
					'type' => 'INNER',
					'conditions' => array(
						'Personne.foyer_id = Foyer.id'
					)
				),
				array(
					'alias' => 'Dossier',
					'table' => 'dossiers',
					'type' => 'INNER',
					'conditions' => array(
						'Foyer.dossier_id = Dossier.id'
					)
				),
				array(
					'alias' => 'Adressefoyer',
					'table' => 'adressesfoyers',
					'type' => 'INNER',
					'conditions' => array(
						'Adressefoyer.foyer_id = Foyer.id',
						'Adressefoyer.rgadr' => '01'
					)
				),
				array(
					'alias' => 'Adresse',
					'table' => 'adresses',
					'type' => 'INNER',
					'conditions' => array(
						'Adressefoyer.adresse_id = Adresse.id'
					)
				),
				array(
					'alias' => 'Passagecommissionep',
					'table' => 'passagescommissionseps',
					'type' => 'LEFT OUTER',
					'conditions' => Set::merge(
						array( 'Passagecommissionep.dossierep_id = Dossierep.id' ),
						empty( $commissionep_id ) ? array() : array(
							'OR' => array(
								'Passagecommissionep.commissionep_id IS NULL',
								'Passagecommissionep.commissionep_id' => $commissionep_id
							)
						)
					)
				)
			);

			return $return;
		}

		/**
		* Récupération de la décision suite au passage en commission d'un dossier
		* d'EP pour un certain niveau de décision.
		*/
		public function getDecisionPdf( $passagecommissionep_id  ) {
			$modele = $this->alias;
			$modeleDecisions = 'Decision'.Inflector::underscore( $this->alias );

			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ );
			$datas = Cache::read( $cacheKey );

			if( $datas === false ) {
				$datas['querydata'] = $this->_qdDecisionPdf();

				// Bilan de parcours
				$datas['querydata']['fields'] = array_merge( $datas['querydata']['fields'], $this->Bilanparcours66->fields() );
				$datas['querydata']['joins'][] = $this->join( 'Bilanparcours66' );

				// Orientation liée au bilan de parcours
				$datas['querydata']['fields'] = array_merge( $datas['querydata']['fields'], $this->Bilanparcours66->Orientstruct->fields() );
				$datas['querydata']['joins'][] = $this->Bilanparcours66->join( 'Orientstruct' );

				/* TODO:
					$this->alias => array(
						'Typeorient',
						'Structurereferente',
						'Bilanparcours66' => array(
							'Orientstruct' => array(
								'Typeorient',
								'Structurereferente',
							),
						)
					),*/

				// Jointures et champs décisions
				$modelesProposes = array(
					'Typeorient' => "{$modeleDecisions}typeorient",
					'Structurereferente' => "{$modeleDecisions}structurereferente",
					'Referent' => "{$modeleDecisions}referent",
				);

				foreach( $modelesProposes as $modelePropose => $modeleProposeAliase ) {
					$replacement = array( $modelePropose => $modeleProposeAliase );

					$datas['querydata']['joins'][] = array_words_replace( $this->Dossierep->Passagecommissionep->{$modeleDecisions}->join( $modelePropose ), $replacement );
					$datas['querydata']['fields'] = array_merge( $datas['querydata']['fields'], array_words_replace( $this->Dossierep->Passagecommissionep->{$modeleDecisions}->{$modelePropose}->fields(), $replacement ) );
				}

				// Traductions
				$datas['options'] = $this->Dossierep->Passagecommissionep->{$modeleDecisions}->enums();
				$datas['options']['Personne']['qual'] = ClassRegistry::init( 'Option' )->qual();
				$datas['options']['Adresse']['typevoie'] = ClassRegistry::init( 'Option' )->typevoie();
				$datas['options']['type']['voie'] = $datas['options']['Adresse']['typevoie'];

				Cache::write( $cacheKey, $datas );
			}

			$datas['querydata']['conditions']['Passagecommissionep.id'] = $passagecommissionep_id;
			// INFO: permet de ne pas avoir d'erreur avec les virtualFields aliasés
			$virtualFields = $this->Dossierep->Passagecommissionep->virtualFields;
			$this->Dossierep->Passagecommissionep->virtualFields = array();
			$gedooo_data = $this->Dossierep->Passagecommissionep->find( 'first', $datas['querydata'] );
			$this->Dossierep->Passagecommissionep->virtualFields = $virtualFields;

			if( empty( $gedooo_data ) || !isset( $gedooo_data[$modeleDecisions] ) || empty( $gedooo_data[$modeleDecisions] ) ) {
				return false;
			}

			// Choix du modèle de document
			$decision = $gedooo_data[$modeleDecisions]['decision'];
			if( $decision == 'maintien' ) {
				if( $gedooo_data[$modeleDecisions]['changementrefparcours'] == 'O' ) {
					$modeleOdt  = "{$this->alias}/decision_maintien_avec_changement.odt";
				}
				else if( $gedooo_data[$modeleDecisions]['changementrefparcours'] == 'N' ) {
					$modeleOdt  = "{$this->alias}/decision_maintien_sans_changement.odt";
				}
			}
			else { // reorientation, reporte, annule
				$modeleOdt = "{$this->alias}/decision_{$decision}.odt";
			}

			return $this->_getOrCreateDecisionPdf( $passagecommissionep_id, $gedooo_data, $modeleOdt, $datas['options'] );
		}
	}
?>
