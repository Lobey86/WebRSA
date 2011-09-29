<?php
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

	class Sanctionep58 extends AppModel
	{
		public $name = 'Sanctionep58';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Enumerable' => array(
				'fields' => array(
					'origine',
					'type'
				)
			),
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
		);

		/**
		* FIXME: et qui n'ont pas de dossier EP en cours de traitement pour cette thématique
		* FIXME: et qui ne sont pas passés en EP pour ce motif dans un délai de moins de 1 mois (paramétrable)
		*/

		protected function _qdSelection( $origine ) {
			$idSanctionMax = $this->Dossierep->Passagecommissionep->Decisionsanctionep58->Listesanctionep58->find(
				'first',
				array(
					'order' => array( 'Listesanctionep58.rang DESC' ),
					'contain' => false
				)
			);

			$personnesEnSanction = $this->Dossierep->find(
				'all',
				array(
					'fields' => array(
						'Dossierep.personne_id',
						'EXTRACT( EPOCH FROM "Dossierep"."created" ) AS "Dossierep__created"',
						'Listesanctionep58.duree'
					),
					'conditions' => array(
						$this->alias.'.origine' => $origine,
						'Decisionsanctionep58.listesanctionep58_id <>' => $idSanctionMax['Listesanctionep58']['id'],
						'Dossierep.id = (
							SELECT dossierseps.id
								FROM dossierseps
								WHERE dossierseps.personne_id = Dossierep.personne_id
									AND dossierseps.themeep = \''.Inflector::tableize( $this->alias ).'\'
								ORDER BY dossierseps.created DESC
								LIMIT 1
						)',
						'Decisionsanctionep58.decision' => 'sanction'
					),
					'joins' => array(
						array(
							'table' => 'sanctionseps58',
							'alias' => 'Sanctionep58',
							'type' => 'INNER',
							'conditions' => array(
								'Sanctionep58.dossierep_id = Dossierep.id',
							)
						),
						array(
							'table' => 'passagescommissionseps',
							'alias' => 'Passagecommissionep',
							'type' => 'INNER',
							'conditions' => array(
								'Passagecommissionep.dossierep_id = Dossierep.id'
							)
						),
						array(
							'table' => 'decisionssanctionseps58',
							'alias' => 'Decisionsanctionep58',
							'type' => 'INNER',
							'conditions' => array(
								'Decisionsanctionep58.passagecommissionep_id = Passagecommissionep.id'
							)
						),
						array(
							'table' => 'listesanctionseps58',
							'alias' => 'Listesanctionep58',
							'type' => 'INNER',
							'conditions' => array(
								'Decisionsanctionep58.listesanctionep58_id = Listesanctionep58.id'
							)
						)
					),
					'contain' => false
				)
			);

			$listePersonnes = array();
			foreach( $personnesEnSanction as $personne ) {
				///FIXME: mettre la date de début de sanction à un autre moment
				$dateFinSanction = strtotime( '+'.$personne['Listesanctionep58']['duree'].' mons', $personne['Dossierep']['created'] );
				if ( time() < $dateFinSanction ) {
					$listePersonnes[] = $personne['Dossierep']['personne_id'];
				}
			}
			$personnesEnSanction = implode( ', ', $listePersonnes );

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
						'type'       => 'LEFT OUTER',
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
					'Personne.id NOT IN (
						SELECT
								dossierseps.personne_id
							FROM dossierseps
							WHERE
								dossierseps.personne_id = Personne.id
								AND dossierseps.id NOT IN ( '.
									$this->Dossierep->Passagecommissionep->sq(
										array(
											'fields' => array(
												'passagescommissionseps.dossierep_id'
											),
											'alias' => 'passagescommissionseps',
											'conditions' => array(
												'passagescommissionseps.dossierep_id = dossierseps.id',
												'passagescommissionseps.etatdossierep' => array( 'traite', 'annule' )
											)
										)
									)
								.' )
					)'
				)
			);

			if ( !empty( $personnesEnSanction ) ) {
				$queryData['conditions'][] = 'Personne.id NOT IN ( '.$personnesEnSanction.' )';
			}

			return $queryData;
		}

		/**
		*
		*/

		public function qdNonInscrits() {
			$queryData = $this->_qdSelection( 'noninscritpe' );
			$qdNonInscrits = ClassRegistry::init( 'Informationpe' )->qdNonInscrits();
			$queryData['fields'] = array_merge( $queryData['fields'] ,$qdNonInscrits['fields'] );
			$queryData['joins'] = array_merge( $queryData['joins'] ,$qdNonInscrits['joins'] );

			$queryData['conditions'] = array_merge( $queryData['conditions'] ,$qdNonInscrits['conditions'] );
			$queryData['order'] = $qdNonInscrits['order'];

			return $queryData;
		}

		/**
		*
		*/

		public function qdRadies() {
			// FIXME: et qui ne sont pas passés dans une EP pour ce motif depuis au moins 1 mois (?)
			$queryData = $this->_qdSelection( 'radiepe' );
			$qdRadies = ClassRegistry::init( 'Informationpe' )->qdRadies();
			$queryData['fields'] = array_merge( $queryData['fields'] ,$qdRadies['fields'] );
			$queryData['joins'] = array_merge( $queryData['joins'] ,$qdRadies['joins'] );
			$queryData['conditions'] = array_merge( $queryData['conditions'] ,$qdRadies['conditions'] );
			$queryData['order'] = $qdRadies['order'];

			return $queryData;
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
							'origine',
							'created',
							'modified'

						)
					),
					'Passagecommissionep' => array(
						'conditions' => array(
							'Passagecommissionep.commissionep_id' => $commissionep_id
						),
						'Decisionsanctionep58' => array(
							'order' => array( 'etape DESC' ),
							'Listesanctionep58'
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
				$formData['Decisionsanctionep58'][$key]['passagecommissionep_id'] = @$datas[$key]['Passagecommissionep'][0]['id'];

				// On modifie les enregistrements de cette étape
				if( @$dossierep['Passagecommissionep'][0]['Decisionsanctionep58'][0]['etape'] == $niveauDecision ) {
					$formData['Decisionsanctionep58'][$key] = @$dossierep['Passagecommissionep'][0]['Decisionsanctionep58'][0];
				}
				// On ajoute les enregistrements de cette étape
				else {
					if( $niveauDecision == 'ep' ) {
						$nbdossierssanctions = $this->Dossierep->find(
							'count',
							array(
								'conditions' => array(
									'Dossierep.personne_id' => $dossierep['Personne']['id'],
									'Dossierep.themeep' => 'sanctionseps58'
								),
								'joins' => array(
									array(
										'table' => 'sanctionseps58',
										'alias' => 'Sanctionep58',
										'type' => 'INNER',
										'conditions' => array(
											'Sanctionep58.dossierep_id = Dossierep.id',
											'Sanctionep58.origine' => $dossierep['Sanctionep58']['origine']
										)
									),
									array(
										'table' => 'passagescommissionseps',
										'alias' => 'Passagecommissionep',
										'type' => 'INNER',
										'conditions' => array(
											'Passagecommissionep.dossierep_id = Dossierep.id',
											'Passagecommissionep.etatdossierep' => 'traite'
										)
									),
									array(
										'table' => 'decisionssanctionseps58',
										'alias' => 'Decisionsanctionep58',
										'type' => 'INNER',
										'conditions' => array(
											'Decisionsanctionep58.passagecommissionep_id = Passagecommissionep.id',
											'Decisionsanctionep58.decision' => 'sanction'
										)
									)
								),
								'contain' => false
							)
						);
						
						$listesanctionep58 = $this->Dossierep->Passagecommissionep->Decisionsanctionep58->Listesanctionep58->find(
							'first',
							array(
								'fields' => array(
									'Listesanctionep58.id'
								),
								'conditions' => array(
									'Listesanctionep58.rang' => $nbdossierssanctions + 1
								),
								'contain' => false
							)
						);
						
						$formData['Decisionsanctionep58'][$key]['listesanctionep58_id'] = $listesanctionep58['Listesanctionep58']['id'];
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
			$themeData = Set::extract( $data, '/Decisionsanctionep58' );
			if( empty( $themeData ) ) {
				return true;
			}
			else {
				$success = $this->Dossierep->Passagecommissionep->Decisionsanctionep58->saveAll( $themeData, array( 'atomic' => false ) );
				$this->Dossierep->Passagecommissionep->updateAll(
					array( 'Passagecommissionep.etatdossierep' => '\'decision'.$niveauDecision.'\'' ),
					array( '"Passagecommissionep"."id"' => Set::extract( $data, '/Decisionsanctionep58/passagecommissionep_id' ) )
				);
				return $success;
			}
		}

		/**
		* INFO: Fonction inutile dans cette saisine donc elle retourne simplement true
		*/

		public function verrouiller( $commissionep_id, $etape ) {
			return true;
		}

		/**
		* TODO: docs
		*/

		public function finaliser( $commissionep_id, $etape ) {
			// Aucune action utile ?
			return true;
		}
		/**
		*
		*/

		public function qdProcesVerbal() {
			return array(
				'fields' => array(
					'Sanctionep58.id',
					'Sanctionep58.dossierep_id',
					'Sanctionep58.origine',
					'Sanctionep58.commentaire',
					'Sanctionep58.created',
					'Sanctionep58.modified',
					//
					'Decisionsanctionep58.id',
					'Decisionsanctionep58.listesanctionep58_id',
					'Decisionsanctionep58.etape',
					'Decisionsanctionep58.decision',
					'Decisionsanctionep58.commentaire',
					'Decisionsanctionep58.created',
					'Decisionsanctionep58.modified',
					'Decisionsanctionep58.raisonnonpassage',

				),
				'joins' => array(
					array(
						'table'      => 'sanctionseps58',
						'alias'      => 'Sanctionep58',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Sanctionep58.dossierep_id = Dossierep.id' ),
					),
					array(
						'table'      => 'decisionssanctionseps58',
						'alias'      => 'Decisionsanctionep58',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Decisionsanctionep58.passagecommissionep_id = Passagecommissionep.id',
							'Decisionsanctionep58.etape' => 'ep'
						),
					)
				)
			);
		}

		/**
		* Récupération du courrier de convocation à l'allocataire pour un passage
		* en commission donné.
		* FIXME: spécifique par thématique
		*/

		public function getConvocationBeneficiaireEpPdf( $passagecommissionep_id ) {
			$gedooo_data = $this->Dossierep->Passagecommissionep->find(
				'first',
				array(
					'conditions' => array( 'Passagecommissionep.id' => $passagecommissionep_id ),
					'contain' => array(
						'Dossierep' => array(
							'Personne',
						),
						'Commissionep'
					)
				)
			);

			return $this->ged( $gedooo_data, "Commissionep/convocationep_beneficiaire.odt" );
		}

		/**
		* Récupération de la décision suite au passage en commission d'un dossier
		* d'EP pour un certain niveau de décision.
		* FIXME: spécifique par thématique
		*/

		public function getDecisionPdf( $passagecommissionep_id  ) {
			$modele = 'Sanctionep'.Configure::read( 'Cg.departement' );
			$modeleDecisions = 'Decisionsanctionep'.Configure::read( 'Cg.departement' );

			$gedooo_data = $this->Dossierep->Passagecommissionep->find(
				'first',
				array(
					'conditions' => array( 'Passagecommissionep.id' => $passagecommissionep_id ),
					'contain' => array(
						'Commissionep',
						'Dossierep' => array(
							'Personne' => array(
								'Foyer' => array(
									'Adressefoyer' => array(
										'conditions' => array(
											'Adressefoyer.id IN ( '.ClassRegistry::init( 'Adressefoyer' )->sqDerniereRgadr01( 'Adressefoyer.foyer_id' ).' )'
										),
										'Adresse'
									)
								)
							),
							$modele
						),
						$modeleDecisions => array(
							'Listesanctionep58',
							'order' => array(
								$modeleDecisions.'.etape DESC'
							),
							'limit' => 1
						)
					)
				)
			);

			if( empty( $gedooo_data ) || !isset( $gedooo_data[$modeleDecisions][0] ) || empty( $gedooo_data[$modeleDecisions][0] ) ) {
				return false;
			}

			// Choix du modèle de document
			$decision = $gedooo_data[$modeleDecisions][0]['decision'];

			if( $decision == 'annule' ) {
				$modeleOdt  = "{$this->alias}/decision_annule.odt";
			}
			else if( $decision == 'reporte' ) {
				$modeleOdt  = "{$this->alias}/decision_reporte.odt";
			}
			else {
				$modeleOdt  = "{$this->alias}/decision_autre.odt";
			}

			// Calcul de la date de fin de sursis si besoin
			$dateDepart = strtotime( $gedooo_data['Passagecommissionep']['impressiondecision'] );
			if( empty( $dateDepart ) ) {
				$dateDepart = mktime();
			}

			// Possède-t'on un PDF déjà stocké ?
			$pdfModel = ClassRegistry::init( 'Pdf' );
			$pdf = $pdfModel->find(
				'first',
				array(
					'conditions' => array(
						'modele' => 'Passagecommissionep',
						'modeledoc' => $modeleOdt,
						'fk_value' => $passagecommissionep_id
					)
				)
			);

			if( !empty( $pdf ) && empty( $pdf['Pdf']['document'] ) ) {
				$cmisPdf = Cmis::read( "/Passagecommissionep/{$passagecommissionep_id}.pdf", true );
				$pdf['Pdf']['document'] = $cmisPdf['content'];
			}

			if( !empty( $pdf['Pdf']['document'] ) ) {
				return $pdf['Pdf']['document'];
			}

			// Traductions
			$options = $this->Dossierep->Passagecommissionep->{$modeleDecisions}->enums();
			$options['Personne']['qual'] = ClassRegistry::init( 'Option' )->qual();
			$options['Adresse']['typevoie'] = ClassRegistry::init( 'Option' )->typevoie();

			// Sinon, on génère le PDF
			$pdf =  $this->ged(
				$gedooo_data,
				$modeleOdt,
                false,
                $options
			);

			$oldRecord['Pdf']['modele'] = 'Passagecommissionep';
			$oldRecord['Pdf']['modeledoc'] = $modeleOdt;
			$oldRecord['Pdf']['fk_value'] = $passagecommissionep_id;
			$oldRecord['Pdf']['document'] = $pdf;

			$pdfModel->create( $oldRecord );
			$success = $pdfModel->save();

			if( !$success ) {
				return false;
			}

			return $pdf;
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
					$this->alias.'.origine',
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
	}
?>