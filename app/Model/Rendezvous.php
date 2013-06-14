<?php
	/**
	 * Code source de la classe Rendezvous.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Rendezvous ...
	 *
	 * @package app.Model
	 */
	class Rendezvous extends AppModel
	{
		public $name = 'Rendezvous';

		public $displayField = 'libelle';

		public $actsAs = array(
			'Formattable' => array(
				'suffix' => array( 'referent_id', 'permanence_id' )
			),
			'Enumerable' => array(
				'fields' => array(
					'haspiecejointe',
					'isadomicile'
				)
			),
			'Gedooo.Gedooo'
		);

		public $validate = array(
			'structurereferente_id' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'typerdv_id' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'daterdv' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
				array(
					'rule' => 'date',
					'message' => 'Veuillez vérifier le format de la date.'
				),
			),
			'heurerdv' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'rang' => array(
				'integer' => array(
					'rule' => 'integer',
					'message' => 'Veuillez entrer un nombre entier',
				)
			),
		);

		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
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
			'Typerdv' => array(
				'className' => 'Typerdv',
				'foreignKey' => 'typerdv_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Referent' => array(
				'className' => 'Referent',
				'foreignKey' => 'referent_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Permanence' => array(
				'className' => 'Permanence',
				'foreignKey' => 'permanence_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Statutrdv' => array(
				'className' => 'Statutrdv',
				'foreignKey' => 'statutrdv_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasOne = array(
			'Propoorientsocialecov58' => array(
				'className' => 'Propoorientsocialecov58',
				'foreignKey' => 'rendezvous_id',
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
		);

		public $hasMany = array(
			'Entretien' => array(
				'className' => 'Entretien',
				'foreignKey' => 'rendezvous_id',
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
			'Fichiermodule' => array(
				'className' => 'Fichiermodule',
				'foreignKey' => false,
				'dependent' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'Rendezvous\'',
					'Fichiermodule.fk_value = {$__cakeID__$}'
				),
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Sanctionrendezvousep58' => array(
				'className' => 'Sanctionrendezvousep58',
				'foreignKey' => 'rendezvous_id',
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
		);

		/**
		* Retourne la clé primaire d'un dossier RSA à partir de la clé primaire
		* d'un rendez-vous.
		*/

		public function dossierId( $rdv_id ){
			$qd_rdv = array(
				'conditions'=> array(
					'Rendezvous.id' => $rdv_id
				),
				'fields' => array( 'Foyer.dossier_id' ),
				'joins' => array(
					$this->join( 'Personne', array( 'type' => 'INNER' ) ),
					$this->Personne->join( 'Foyer', array( 'type' => 'INNER' ) )
				),
				'recursive' => -1
			);
			$rdv = $this->find('first', $qd_rdv);

			if( !empty( $rdv ) ) {
				return $rdv['Foyer']['dossier_id'];
			}
			else {
				return null;
			}
		}

		/**
		* Retourne un booléen selon si un dossier d'EP doit ou non
		* être créé pour la personne dont l'id est passé en paramètre
		*/
		public function passageEp( $data ) {
			$statutrdvtyperdv = $this->Typerdv->StatutrdvTyperdv->find(
				'first',
				array(
					'conditions' => array(
						'StatutrdvTyperdv.typerdv_id' => $data['Rendezvous']['typerdv_id'],
						'StatutrdvTyperdv.statutrdv_id' => $data['Rendezvous']['statutrdv_id']
					),
					'contain' => false
				)
			);

			if( empty( $statutrdvtyperdv ) || empty( $data['Rendezvous']['rang'] ) || ( $data['Rendezvous']['rang'] % $statutrdvtyperdv['StatutrdvTyperdv']['nbabsenceavantpassagecommission'] ) != 0 ) {
				return false;
			}

			if( $statutrdvtyperdv['StatutrdvTyperdv']['typecommission'] == 'ep' ) {
				$dossiercommission = $this->Personne->Dossierep->find(
					'first',
					array(
						'conditions' => array(
							'Dossierep.personne_id' => $data['Rendezvous']['personne_id'],
							'Dossierep.themeep' => 'sanctionsrendezvouseps58',
							'Dossierep.id NOT IN ( '.
								$this->Personne->Dossierep->Passagecommissionep->sq(
									array(
										'fields' => array(
											'passagescommissionseps.dossierep_id'
										),
										'alias' => 'passagescommissionseps',
										'conditions' => array(
											'passagescommissionseps.etatdossierep' => array ( 'traite', 'annule' )
										)
									)
								)
							.' )'
						),
						'contain' => array(
							'Sanctionrendezvousep58' => array(
								'Rendezvous' => array(
									'conditions' => array(
										'Rendezvous.typerdv_id' => $data['Rendezvous']['typerdv_id']
									)
								)
							)
						)
					)
				);
			}
			else {
				$dossiercommission = $this->Personne->Dossiercov58->find(
					'first',
					array(
						'conditions' => array(
							'Dossiercov58.personne_id' => $data['Rendezvous']['personne_id'],
							'Dossiercov58.themecov58' => 'proposorientssocialescovs58',
							'Dossiercov58.id NOT IN ( '.
								$this->Personne->Dossiercov58->Passagecov58->sq(
									array(
										'alias' => 'passagescovs58',
										'fields' => array(
											'passagescovs58.dossiercov58_id'
										),
										'conditions' => array(
											'passagescovs58.etatdossiercov' => array ( 'traite', 'annule' )
										)
									)
								)
							.' )'
						)
					)
				);
			}

			return empty( $dossiercommission );
		}


		/**
		* FIXME: la même avec dossier COV
		*/
		public function beforeSave( $options = array ( ) ) {
			$return = parent::beforeSave( $options );

			if ( Configure::read( 'Cg.departement' ) == 58 ) {
				// Pour les nouveaux enregistrements,
				if ( !isset( $this->data['Rendezvous']['id'] ) || empty( $this->data['Rendezvous']['id'] ) ) {
					$dossierep = $this->Personne->Dossierep->find(
						'first',
						array(
							'conditions' => array(
								'Dossierep.personne_id' => $this->data['Rendezvous']['personne_id'],
								'Dossierep.themeep' => 'sanctionsrendezvouseps58',
								'Dossierep.id NOT IN ( '.
									$this->Personne->Dossierep->Passagecommissionep->sq(
										array(
											'fields' => array(
												'passagescommissionseps.dossierep_id'
											),
											'alias' => 'passagescommissionseps',
											'conditions' => array(
												'passagescommissionseps.etatdossierep' => array ( 'traite', 'annule' )
											)
										)
									)
								.' )'
							),
							'contain' => array(
								'Sanctionrendezvousep58' => array(
									'Rendezvous'
								)
							)
						)
					);

					if ( isset( $dossierep['Sanctionrendezvousep58']['Rendezvous']['typerdv_id'] ) ) {
						$this->invalidate( 'typerdv_id', 'Un passage en EP est déjà en cours pour cette objet, vous ne pouvez créer un nouveau rendez-vous pour ce même objet.' );
						$return = false;
					}
				}
				else {
					if ( !$this->Statutrdv->provoquePassageCommission( $this->data['Rendezvous']['statutrdv_id'] ) || !$this->passageEp( $this->data ) ) {
						$dossierep = $this->Sanctionrendezvousep58->find(
							'first',
							array(
								'fields' => array(
									'Sanctionrendezvousep58.id',
									'Sanctionrendezvousep58.dossierep_id'
								),
								'conditions' => array(
									'Sanctionrendezvousep58.rendezvous_id' => $this->data['Rendezvous']['id']
								),
								'contain' => false
							)
						);

						if ( !empty( $dossierep ) ) {
							$this->Sanctionrendezvousep58->delete( $dossierep['Sanctionrendezvousep58']['id'] );
							$this->Sanctionrendezvousep58->Dossierep->delete( $dossierep['Sanctionrendezvousep58']['dossierep_id'] );
						}
					}
				}
			}

			return $return;
		}

		/**
		* Règle de validation sur le statut du RDV uniquement si pas CG58
		*/

		public function beforeValidate( $options = array() ) {
			$return = parent::beforeValidate( $options );

			if( Configure::read( 'Cg.departement' ) != 58 ){
				$rule = array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire',
				);

				$this->validate['statutrdv_id'][] = $rule;
			}


			return $return;
		}

		/**
		 * Retourne le PDF d'un rendez-vous.
		 *
		 * @param integer $id L'id du rendez-vous pour lequel générer l'impression
		 * @param $user_id L'id de l'utilisateur qui génère l'impression.
		 * @return string
		 */
		public function getDefaultPdf( $id, $user_id ) {
			$rdv = $this->find(
				'first',
				array(
					'fields' => array_merge(
						$this->fields(),
						$this->Permanence->fields(),
						$this->Personne->fields(),
						$this->Referent->fields(),
						$this->Statutrdv->fields(),
						$this->Structurereferente->fields(),
						$this->Typerdv->fields(),
						$this->Personne->Foyer->fields(),
						$this->Personne->Foyer->Dossier->fields(),
						$this->Personne->Foyer->Adressefoyer->Adresse->fields()
					),
					'joins' => array(
						$this->join( 'Permanence', array( 'type' => 'LEFT OUTER' ) ),
						$this->join( 'Personne', array( 'type' => 'INNER' ) ),
						$this->join( 'Referent', array( 'type' => 'LEFT OUTER' ) ),
						$this->join( 'Statutrdv', array( 'type' => 'LEFT OUTER' ) ),
						$this->join( 'Structurereferente', array( 'type' => 'INNER' ) ),
						$this->join( 'Typerdv', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
						$this->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
						$this->Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
					),
					'conditions' => array(
						'Rendezvous.id' => $id,
						'OR' => array(
							'Adressefoyer.id IS NULL',
							'Adressefoyer.id IN ( '.$this->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
						)
					),
					'contain' => false
				)
			);

			$User = ClassRegistry::init( 'User' );
			$user = $User->find(
				'first',
				array(
					'fields' => array_merge(
						$User->fields(),
						$User->Serviceinstructeur->fields()
					),
					'joins' => array(
						$User->join( 'Serviceinstructeur', array( 'type' => 'INNER' ) )
					),
					'conditions' => array(
						'User.id' => $user_id
					),
					'contain' => false
				)
			);
			$rdv = Set::merge( $rdv, $user );

			$rdv['Rendezvous']['heurerdv'] = date( "H:i", strtotime( $rdv['Rendezvous']['heurerdv'] ) );

			$Option = ClassRegistry::init( 'Option' );
			$options = array(
				'Adresse' => array(
					'typevoie' => $Option->typevoie()
				),
				'Permanence' => array(
					'typevoie' => $Option->typevoie()
				),
				'Personne' => array(
					'qual' => $Option->qual()
				),
				'Referent' => array(
					'qual' => $Option->qual()
				),
				'Structurereferente' => array(
					'type_voie' => $Option->typevoie()
				),
				'Type' => array(
					'voie' => $Option->typevoie()
				),
			);

			return $this->ged(
				$rdv,
				"RDV/{$rdv['Typerdv']['modelenotifrdv']}.odt",
				false,
				$options
			);
		}

		/**
		 * FIXME: devrait remplacer la méthode passageEp ?
		 *
		 * @param type $data
		 * @return type
		 */
		public function provoquePassageCommission( $data ) {
			return (
				Configure::read( 'Cg.departement' ) == 58
				&& !empty( $data['Rendezvous']['statutrdv_id'] )
				&& $this->Statutrdv->provoquePassageCommission( $data['Rendezvous']['statutrdv_id'] )
				&& $this->passageEp( $data )
			);
		}

		/**
		 * FIXME: le nombre vient du nouveau champ
		 *
		 * @param type $data
		 * @param type $user_id
		 * @return type
		 */
		public function creePassageCommission( $data, $user_id ) {
			$statutrdv_typerdv = $this->Statutrdv->StatutrdvTyperdv->find(
				'first',
				array(
					'conditions' => array(
						'StatutrdvTyperdv.statutrdv_id' => $data['Rendezvous']['statutrdv_id'],
						'StatutrdvTyperdv.typerdv_id' => $data['Rendezvous']['typerdv_id'],
					),
					'contain' => false
				)
			);

			if( $statutrdv_typerdv['StatutrdvTyperdv']['typecommission'] == 'ep' ) {
				$dossierep = array(
					'Dossierep' => array(
						'personne_id' => $data['Rendezvous']['personne_id'],
						'themeep' => 'sanctionsrendezvouseps58'
					)
				);
				$success = $this->Personne->Dossierep->save( $dossierep );

				$sanctionrendezvousep58 = array(
					'Sanctionrendezvousep58' => array(
						'dossierep_id' => $this->Personne->Dossierep->id,
						'rendezvous_id' => $this->id
					)
				);

				$success = $this->Personne->Dossierep->Sanctionrendezvousep58->save( $sanctionrendezvousep58 ) && $success;
			}
			else {
				$themecov58_id = $this->Propoorientsocialecov58->Dossiercov58->Themecov58->field( 'id', array( 'name' => 'proposorientssocialescovs58' ) );
				$dossiercov58 = array(
					'Dossiercov58' => array(
						'personne_id' => $data['Rendezvous']['personne_id'],
						'themecov58' => 'proposorientssocialescovs58',
						'themecov58_id' => $themecov58_id,
					)
				);
				$success = $this->Personne->Dossiercov58->save( $dossiercov58 );

				$propoorientsocialecov58 = array(
					'Propoorientsocialecov58' => array(
						'dossiercov58_id' => $this->Propoorientsocialecov58->Dossiercov58->id,
						'rendezvous_id' => $this->id,
						'user_id' => $user_id
					)
				);

				$success = $this->Propoorientsocialecov58->save( $propoorientsocialecov58 ) && $success;
			}

			return $success;
		}


		/**
		 * Retourne une sous-requête permettant de connaître le dernier rendez-vous pour un
		 * allocataire donné.
		 *
		 * @param string $field Le champ Personne.id sur lequel faire la sous-requête
		 * @return string
		 */
		public function sqDernier( $field ) {
			$dbo = $this->getDataSource( $this->useDbConfig );
			$table = $dbo->fullTableName( $this, false, false );
			return "SELECT {$table}.id
					FROM {$table}
					WHERE
						{$table}.personne_id = ".$field."
					ORDER BY {$table}.daterdv DESC
					LIMIT 1";
		}

		/**
		 * Retourne l'id de la personne à laquelle est lié un enregistrement.
		 *
		 * @param integer $id L'id de l'enregistrement
		 * @return integer
		 */
		public function personneId( $id ) {
			$querydata = array(
				'fields' => array( "{$this->alias}.personne_id" ),
				'conditions' => array(
					"{$this->alias}.id" => $id
				),
				'recursive' => -1
			);

			$result = $this->find( 'first', $querydata );

			if( !empty( $result ) ) {
				return $result[$this->alias]['personne_id'];
			}
			else {
				return null;
			}
		}

		/**
		 * Retourne la liste des rendez-vous d'une personne, ordonnés par date
		 * et heure (du plus récent au plus ancien), et libellé de l'objet,
		 * formattés comme suit:
		 * <pre>
		 * array(
		 *	<Id du RDV> => "<Objet du RDV> du <Date du RDV> à <Heure du RDV>"
		 * )
		 * </pre>
		 *
		 * @param integer $personne_id L'id de la personne
		 * @return array
		 */
		public function findListPersonneId( $personne_id ) {
			$rendezvous = array();

			$results = $this->find(
				'all',
				array(
					'fields' => array(
						'Rendezvous.id',
						'Rendezvous.daterdv',
						'Rendezvous.heurerdv',
						'Typerdv.libelle',
					),
					'contain' => false,
					'conditions' => array(
						'Rendezvous.personne_id' => $personne_id
					),
					'joins' => array(
						$this->join( 'Typerdv', array( 'type' => 'INNER' ) )
					),
					'order' => array(
						'Rendezvous.daterdv DESC',
						'Rendezvous.heurerdv DESC',
						'Typerdv.libelle ASC',
					)
				)
			);

			if( !empty( $results ) ) {
				foreach( $results as $result ) {
					$rendezvous[$result['Rendezvous']['id']] = sprintf(
						'%s du %s à %s',
						$result['Typerdv']['libelle'],
						date( 'd/m/Y', strtotime( $result['Rendezvous']['daterdv'] ) ),
						date( 'H:i:s', strtotime( $result['Rendezvous']['heurerdv'] ) )
					);
				}
			}

			return $rendezvous;
		}
	}
?>