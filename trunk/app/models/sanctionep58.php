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
// 			'Formattable' => array(
// 				'suffix' => array(
// 					'structurereferente_id'
// 				)
// 			),
			'Enumerable' => array(
				'fields' => array(
					'origine',
					'type'
				)
			),
			'Formattable'
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
			'Contratinsertion' => array(
				'className' => 'Contratinsertion',
				'foreignKey' => 'contratinsertion_id',
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
			'Historiqueetatpe' => array(
				'className' => 'Historiqueetatpe',
				'foreignKey' => 'historiqueetatpe_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Listesanctionep58' => array(
				'className' => 'Listesanctionep58',
				'foreignKey' => 'listesanctionep58_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		/**
		*
		*/
		/*public function containQueryData() {
			return array(
				'Sanctionep58' => array(
					'Decisionsaisinebilanparcoursep66'=>array(
						'Typeorient',
						'Structurereferente'
					),
				)
			);
		}*/

		/**
		* FIXME: et qui n'ont pas de dossier EP en cours de traitement pour cette thématique
		* FIXME: et qui ne sont pas passés en EP pour ce motif dans un délai de moins de 1 mois (paramétrable)
		*/

		protected function _qdSelection( $origine ) {
			$idSanctionMax = $this->Listesanctionep58->find(
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
						$this->alias.'.listesanctionep58_id <>' => $idSanctionMax['Listesanctionep58']['id'],
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
							'table' => 'listesanctionseps58',
							'alias' => 'Listesanctionep58',
							'type' => 'INNER',
							'conditions' => array(
								'Sanctionep58.listesanctionep58_id = Listesanctionep58.id'
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
			$qdNonInscrits = $this->Historiqueetatpe->Informationpe->qdNonInscrits();
			$queryData['fields'] = array_merge( $queryData['fields'] ,$qdNonInscrits['fields'] );

			/*$queryData['joins'][] = array(
				'table'      => 'orientsstructs',
				'alias'      => 'Orientstruct',
				'type'       => 'INNER',
				'foreignKey' => false,
				'conditions' => array(
					'Personne.id = Orientstruct.personne_id'
				)
			);*/
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
			$qdRadies = $this->Historiqueetatpe->Informationpe->qdRadies();
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
							'listesanctionep58_id',
							'origine',
							'created',
							'modified'

						),
						'Listesanctionep58'
					),
					'Passagecommissionep' => array(
						'conditions' => array(
							'Passagecommissionep.commissionep_id' => $commissionep_id
						),
						'Decisionsanctionep58' => array(
							'order' => array( 'etape DESC' )
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
				$formData['Decisionsanctionep58'][$key]['passagecommissionep_id'] = @$datas[$key][Passagecommissionep][0]['id'];

				if( $niveauDecision == 'ep' ) {
					if( isset( $datas[$key]['Passagecommissionep'][0]['Decisionsanctionep58'][0] ) ) { // Modification
						$formData['Decisionsanctionep58'][$key]['id'] = @$datas[$key]['Passagecommissionep'][0]['Decisionsanctionep58'][0]['id'];
						$formData['Decisionsanctionep58'][$key]['decision'] = @$datas[$key]['Passagecommissionep'][0]['Decisionsanctionep58'][0]['decision'];
						$formData['Decisionsanctionep58'][$key]['raisonnonpassage'] = @$datas[$key]['Passagecommissionep'][0]['Decisionsanctionep58'][0]['raisonnonpassage'];
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
				foreach( array_keys( $themeData ) as $key ) {
					if ( empty( $themeData[$key]['Decisionsanctionep58']['decision'] ) ) {
						unset( $themeData[$key] );
					}
				}

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
			/*$commissionep = $this->Dossierep->Commissionep->find(
				'first',
				array(
					'conditions' => array( 'Commissionep.id' => $commissionep_id ),
					'contain' => array( 'Ep' )
				)
			);

			$niveauDecisionFinale = $commissionep['Ep'][Inflector::underscore( $this->alias )];

			$dossierseps = $this->find(
				'all',
				array(
					'conditions' => array(
						'Dossierep.commissionep_id' => $commissionep_id,
						'Dossierep.themeep' => Inflector::tableize( $this->alias ),//FIXME: ailleurs aussi
					),
					'contain' => array(
						'Decisionsanctionep58' => array(
							'conditions' => array(
								'Decisionsanctionep58.etape' => $etape
							)
						),
						'Dossierep'
					)
				)
			);

			$success = true;
			foreach( $dossierseps as $dossierep ) {
				if( $niveauDecisionFinale == $etape ) {
					$sanctionep58 = array( $this->alias => $dossierep[$this->alias] );
					if( !isset( $dossierep['Decisionsanctionep58'][0]['decision'] ) ) {
						$success = false;
					}
					$sanctionep58[$this->alias]['decision'] = @$dossierep['Decisionsanctionep58'][0]['decision'];

					$success = $this->save( $sanctionep58 ) && $success;
				}
			}

			return $success;*/
			return true;
		}
	}
?>