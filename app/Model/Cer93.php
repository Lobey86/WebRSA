<?php
	/**
	 * Code source de la classe Cer93.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Cer93 gère les CER du CG 93.
	 *
	 * @package app.Model
	 */
	class Cer93 extends AppModel
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Cer93';

		/**
		 * Récursivité.
		 *
		 * @var integer
		 */
		public $recursive = -1;

		/**
		 * Behaviors utilisés.
		 *
		 * @var array
		 */
		public $actsAs = array(
			'Formattable',
			'Pgsqlcake.PgsqlAutovalidate',
			'Gedooo.Gedooo',
			'ModelesodtConditionnables' => array(
				93 => array(
					'Contratinsertion/contratinsertion.odt',
					'Contratinsertion/cer_valide.odt',
					'Contratinsertion/cer_rejete.odt',
					'Contratinsertion/decision_valide.odt',
					'Contratinsertion/decision_rejete.odt'
				)
			)
		);

		public $validate = array(
			'matricule' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' )
				)
			),
			'qual' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' )
				)
			),
			// FIXME
			/*'adresse' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' )
				)
			),*/
			'codepos' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' )
				)
			),
			'nomcom' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' )
				)
			),
			'isemploitrouv' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' )
				)
			),
			'secteuracti_id' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmptyIf', 'isemploitrouv', true, array( 'O' ) ),
					'message' => 'Champ obligatoire',
				)
			),
			'metierexerce_id' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmptyIf', 'isemploitrouv', true, array( 'O' ) ),
					'message' => 'Champ obligatoire',
				)
			),
			'dureehebdo' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmptyIf', 'isemploitrouv', true, array( 'O' ) ),
					'message' => 'Champ obligatoire',
				)
			),
			'naturecontrat_id' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmptyIf', 'isemploitrouv', true, array( 'O' ) ),
					'message' => 'Champ obligatoire',
				)
			),
// 			'dureecdd' => array(
// 				'notEmpty' => array(
// 					'rule' => array( 'notEmptyIf', 'isemploitrouv', true, array( 'O' ) ),
// 					'message' => 'Champ obligatoire',
// 				)
// 			),
			'prevu' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' )
				)
			),
			'duree' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' )
				)
			),
			'pointparcours' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' )
				)
			),
			'datepointparcours' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmptyIf', 'pointparcours', true, array( 'aladate' ) ),
					'message' => 'Champ obligatoire',
				)
			),
			'datesignature' => array(
				'datePassee' => array(
					'rule' => 'datePassee',
					'message' => 'Merci de renseigner une date antérieure ou égale à la date du jour'
				)
			)
		);

		/**
		 * Liaisons "belongsTo" avec d'autres modèles.
		 *
		 * @var array
		 */
		public $belongsTo = array(
			'Contratinsertion' => array(
				'className' => 'Contratinsertion',
				'foreignKey' => 'contratinsertion_id',
				'conditions' => null,
				'type' => 'INNER',
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Metierexerce' => array(
				'className' => 'Metierexerce',
				'foreignKey' => 'metierexerce_id',
				'conditions' => null,
				'type' => 'LEFT OUTER',
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Secteuracti' => array(
				'className' => 'Secteuracti',
				'foreignKey' => 'secteuracti_id',
				'conditions' => null,
				'type' => 'LEFT OUTER',
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Naturecontrat' => array(
				'className' => 'Naturecontrat',
				'foreignKey' => 'naturecontrat_id',
				'conditions' => null,
				'type' => 'LEFT OUTER',
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'User' => array(
				'className' => 'User',
				'foreignKey' => 'user_id',
				'conditions' => null,
				'type' => 'INNER',
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
		);

		/**
		 * Liaisons "hasMany" avec d'autres modèles.
		 *
		 * @var array
		 */
		public $hasMany = array(
			'Compofoyercer93' => array(
				'className' => 'Compofoyercer93',
				'foreignKey' => 'cer93_id',
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
			'Diplomecer93' => array(
				'className' => 'Diplomecer93',
				'foreignKey' => 'cer93_id',
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
			'Expprocer93' => array(
				'className' => 'Expprocer93',
				'foreignKey' => 'cer93_id',
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
			'Histochoixcer93' => array(
				'className' => 'Histochoixcer93',
				'foreignKey' => 'cer93_id',
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
		 * Liaisons "hasAndBelongsToMany" avec d'autres modèles.
		 *
		 * @var array
		 */
		public $hasAndBelongsToMany = array(
            'Sujetcer93' => array(
				'className' => 'Sujetcer93',
				'joinTable' => 'cers93_sujetscers93',
				'foreignKey' => 'cer93_id',
				'associationForeignKey' => 'sujetcer93_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'Cer93Sujetcer93'
			)
		);

		/**
		 * 	Fonction permettant la sauvegarde du formulaire du CER 93.
		 *
		 * 	Une règle de validation est supprimée en amont
		 * 	Les valeurs de la table Compofoyercer93 sont mises à jour à chaque modifciation
		 *
		 * 	@param $data Les données à sauvegarder.
		 * 	@return boolean
		 */
		public function saveFormulaire( $data, $typeUser ) {
			$success = true;

			// Sinon, ça pose des problèmes lors du add car les valeurs n'existent pas encore
			$this->unsetValidationRule( 'contratinsertion_id', 'notEmpty' );

			// Si aucun sujet n'est renseigné, alors on lance un erreur
			if( empty( $data['Sujetcer93']['Sujetcer93'] ) ) {
				$success = false;
				$this->Sujetcer93->invalidate( 'Sujetcer93', 'Il est obligatoire de saisir au moins un sujet.' );
			}

			foreach( array( 'Compofoyercer93', 'Diplomecer93', 'Expprocer93' ) as $hasManyModel ) {
				$this->{$hasManyModel}->unsetValidationRule( 'cer93_id', 'notEmpty' );

				if( isset( $data['Cer93']['id'] ) && !empty( $data['Cer93']['id'] ) ) {
					$success = $this->{$hasManyModel}->deleteAll(
						array( "{$hasManyModel}.cer93_id" => $data['Cer93']['id'] )
					) && $success;
				}
			}

			// On passe les champs du fieldset emploi trouvé si l'allocataire déclare
			// ne pas avoir trouvé d'emploi
			if( $data['Cer93']['isemploitrouv'] == 'N' ) {
				$fields = array( 'secteuracti_id', 'metierexerce_id', 'dureehebdo', 'naturecontrat_id', 'dureecdd' );
				foreach( $fields as $field ) {
					$data['Cer93'][$field] = null;
				}
			}

			if( !isset( $data['Cer93']['dureecdd'] ) ){
				$data['Cer93']['dureecdd'] = null;
			}

			// On passe le champ date de point de aprcours à null au cas où l'allocataire
			// décide finalement de faire le point à la find e son contrat
			if( $data['Cer93']['pointparcours'] == 'alafin' ) {
				$fields = array( 'datepointparcours' );
				foreach( $fields as $field ) {
					$data['Cer93'][$field] = null;
				}
			}

			$tmp = (array)$data['Sujetcer93']['Sujetcer93'];
			unset( $data['Sujetcer93'] );

			foreach( $tmp as $k => $v ) {
				foreach( array( 'autresoussujet', 'valeurparsoussujetcer93_id' ) as $w ) {
					if( isset( $v[$w] ) && empty( $v[$w] ) ) {
						unset( $tmp[$k][$w] );
					}
				}
			}

			$success = $this->saveResultAsBool(
				$this->saveAssociated( $data, array( 'validate' => 'first', 'atomic' => false, 'deep' => true ) )
			) && $success;

			if( $success ) {
				$tmp = array( 'Sujetcer93' => array( 'Sujetcer93' => $tmp ) );
				$tmp['Cer93']['id'] = $this->id;
				$success = $this->saveResultAsBool(
				$this->saveAll( $tmp, array( 'validate' => 'first', 'atomic' => false, 'deep' => false ) )
				) && $success;
				/*$this->create( $tmp );
				$success = $this->create() && $success;*/
			}


			// Dans le cas d'un ajout de CER, on vérifie s'il faut ajouter un rendez-vous implicite
			if( $success && empty( $data['Cer93']['id'] ) && Configure::read( 'Contratinsertion.RdvAuto.active' ) === true && ( $typeUser != 'cg' ) ) {
				$created = date( 'Y-m-d H:i:s' );

				$querydata = array(
					'conditions' => array(
						'Rendezvous.personne_id' => $data['Contratinsertion']['personne_id'],
						'Rendezvous.structurereferente_id' => $data['Contratinsertion']['structurereferente_id'],
						'Rendezvous.typerdv_id' => Configure::read( 'Contratinsertion.RdvAuto.typerdv_id' ),
						'daterdv' => date( 'Y-m-d', strtotime( $created ) ),
					),
					'contain' => false,
				);

				// Si on utilise la thématique...
				$useThematiquerdv = $this->Contratinsertion->Personne->Rendezvous->Thematiquerdv->used();
				if( $useThematiquerdv ) {
					$querydata['joins'] = array(
						$this->Contratinsertion->Personne->Rendezvous->join( 'RendezvousThematiquerdv', array( 'type' => 'INNER' ) )
					);
					$querydata['conditions']['RendezvousThematiquerdv.thematiquerdv_id'] = Configure::read( 'Contratinsertion.RdvAuto.thematiquerdv_id' );
				}

				if( $this->Contratinsertion->Personne->Rendezvous->find( 'count', $querydata ) == 0 ) {
					$rendezvous = array(
						'Rendezvous' => array(
							'personne_id' => $data['Contratinsertion']['personne_id'],
							'structurereferente_id' => $data['Contratinsertion']['structurereferente_id'],
							'referent_id' => suffix( $data['Contratinsertion']['referent_id'] ),
							'objetrdv' => null,
							'commentairerdv' => null,
							'typerdv_id' => Configure::read( 'Contratinsertion.RdvAuto.typerdv_id' ),
							'statutrdv_id' => Configure::read( 'Contratinsertion.RdvAuto.statutrdv_id' ),
							'daterdv' => date( 'Y-m-d', strtotime( $created ) ),
							'heurerdv' => date( 'H:i:s', strtotime( $created ) - ( strtotime( $created ) % ( 5 * 60 ) ) ),
							'permanence_id' => null,
							'isadomicile' => '0',
						)
					);

					if( $useThematiquerdv ) {
						$rendezvous['Thematiquerdv'] = array( 'Thematiquerdv' => Configure::read( 'Contratinsertion.RdvAuto.thematiquerdv_id' ) );
					}

					$this->Contratinsertion->Personne->Rendezvous->create( $rendezvous );
					$success = $this->Contratinsertion->Personne->Rendezvous->save() && $success;
					if( !$success ) {
						$this->log(
							sprintf(
								'Erreur(s) lors de l\'enregistrement automatique d\'un rendez-vous lors de la création d\'un CER (erreurs de validation: %s)',
								var_export( $this->Contratinsertion->Personne->Rendezvous->validationErrors, true )
							),
							LOG_ERROR
						);
					}
				}
			}

			return $success;
		}

		/**
		 * Recherche des données CAF liées à l'allocataire dans le cadre du Cer93.
		 *
		 * @param integer $personne_id
		 * @return array
		 * @throws NotFoundException
		 * @throws InternalErrorException
		 */
		public function dataCafAllocataire( $personne_id ) {
			$Informationpe = ClassRegistry::init( 'Informationpe' );

			$querydataCaf = array(
				'fields' => array_merge(
					$this->Contratinsertion->Personne->fields(),
					$this->Contratinsertion->Personne->Prestation->fields(),
					$this->Contratinsertion->Personne->Dsp->fields(),
					$this->Contratinsertion->Personne->DspRev->fields(),
					$this->Contratinsertion->Personne->Foyer->fields(),
					$this->Contratinsertion->Personne->Foyer->Adressefoyer->Adresse->fields(),
					$this->Contratinsertion->Personne->Foyer->Dossier->fields(),
					array(
						'Historiqueetatpe.identifiantpe',
						'Historiqueetatpe.etat'
					)
				),
				'joins' => array(
					$Informationpe->joinPersonneInformationpe( 'Personne', 'Informationpe', 'LEFT OUTER' ),
					$Informationpe->join( 'Historiqueetatpe', array( 'type' => 'LEFT OUTER' ) ),
					$this->Contratinsertion->Personne->join( 'Dsp', array( 'type' => 'LEFT OUTER' )),
					$this->Contratinsertion->Personne->join( 'DspRev', array( 'type' => 'LEFT OUTER' )),
					$this->Contratinsertion->Personne->join( 'Foyer', array( 'type' => 'INNER' )),
					$this->Contratinsertion->Personne->join( 'Prestation', array( 'type' => 'LEFT OUTER'  )),
					$this->Contratinsertion->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
					$this->Contratinsertion->Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
					$this->Contratinsertion->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
				),
				'conditions' => array(
					'Personne.id' => $personne_id,
					array(
						'OR' => array(
							'Adressefoyer.id IS NULL',
							'Adressefoyer.id IN ( '.$this->Contratinsertion->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
						)
					),
					array(
						'OR' => array(
							'Dsp.id IS NULL',
							'Dsp.id IN ( '.$this->Contratinsertion->Personne->Dsp->sqDerniereDsp( 'Personne.id' ).' )'
						)
					),
					array(
						'OR' => array(
							'DspRev.id IS NULL',
							'DspRev.id IN ( '.$this->Contratinsertion->Personne->DspRev->sqDerniere( 'Personne.id' ).' )'
						)
					),
					array(
						'OR' => array(
							'Informationpe.id IS NULL',
							'Informationpe.id IN( '.$Informationpe->sqDerniere( 'Personne' ).' )'
						)
					),
					array(
						'OR' => array(
							'Historiqueetatpe.id IS NULL',
							'Historiqueetatpe.id IN( '.$Informationpe->Historiqueetatpe->sqDernier( 'Informationpe' ).' )'
						)
					)
				),
				'contain' => false
			);
			$dataCaf = $this->Contratinsertion->Personne->find( 'first', $querydataCaf );

			// On copie les DspsRevs si elles existent à la place des DSPs (on garde l'information la plus récente)
			if( !empty( $dataCaf['DspRev']['id'] ) ) {
				$dataCaf['Dsp'] = $dataCaf['DspRev'];
			}
			unset( $dataCaf['DspRev'] );

			// On s'assure d'avoir trouvé l'allocataire
			if( empty( $dataCaf ) ) {
				throw new NotFoundException();
			}

			// Et que celui-ci soit bien demandeur ou conjoint
			if( !in_array( $dataCaf['Prestation']['rolepers'], array( 'DEM', 'CJT' ) ) ) {
				throw new InternalErrorException( "L'allocataire \"{$personne_id}\" doit être demandeur ou conjont" );
			}

			// Bloc 2 : Composition du foyer
			// Récupération des informations de composition du foyer de l'allocataire
			$composfoyerscers93 = $this->Contratinsertion->Personne->find(
				'all',
				array(
					'fields' => array(
						'"Personne"."qual" AS "Compofoyercer93__qual"',
						'"Personne"."nom" AS "Compofoyercer93__nom"',
						'"Personne"."prenom" AS "Compofoyercer93__prenom"',
						'"Personne"."dtnai" AS "Compofoyercer93__dtnai"',
						'"Prestation"."rolepers" AS "Compofoyercer93__rolepers"'
					),
					'conditions' => array( 'Personne.foyer_id' => $dataCaf['Foyer']['id'] ),
					'contain' => array(
						'Prestation'
					)
				)
			);
			$composfoyerscers93 = array( 'Compofoyercer93' => Set::classicExtract( $composfoyerscers93, '{n}.Compofoyercer93' ) );
			$dataCaf = Set::merge( $dataCaf, $composfoyerscers93 );

			return $dataCaf;
		}

		/**
		 * Préparation des données du formulaire d'ajout ou de modification d'un
		 * CER pour le CG 93.
		 *
		 * @param integer $personne_id
		 * @param integer $contratinsertion_id
		 * @param integer $user_id
		 * @return array
		 * @throws InternalErrorException
		 * @throws NotFoundException
		 */
		public function prepareFormDataAddEdit( $personne_id, $contratinsertion_id, $user_id ) {
			// Recherche des données CAF.
			$dataCaf = $this->dataCafAllocataire( $personne_id );

			// Querydata pour le contrat
			$querydataCer = array(
				'contain' => array(
					'Cer93' => array(
						'Diplomecer93' => array(
							'order' => array( 'Diplomecer93.annee DESC' )
						),
						'Expprocer93' => array(
							'order' => array( 'Expprocer93.anneedeb DESC' )
						),
						'Sujetcer93',
					),
				)
			);

			// Données de l'utilisateur
			$querydataUser = array(
				'conditions' => array(
					'User.id' => $user_id
				),
				'contain' => array(
					'Structurereferente',
					'Referent' => array(
						'Structurereferente'
					)
				)
			);
			$dataUser = $this->Contratinsertion->User->find( 'first', $querydataUser );

			// On s'assure que l'utilisateur existe
			if( empty( $dataUser ) ) {
				throw new InternalErrorException( "Utilisateur non trouvé \"{$user_id}\"" );
			}

			// Si c'est une modification, on lit l'enregistrement, on actualise
			// les données (CAF et dernier CER validé) et on renvoit.
			if( !empty( $contratinsertion_id ) ) {
				$querydataCerActuel = $querydataCer;
				$querydataCerActuel['conditions'] = array(
					'Contratinsertion.id' => $contratinsertion_id
				);
				$dataCerActuel = $this->Contratinsertion->find( 'first', $querydataCerActuel );

				// Il faut que l'enregistrement à modifier existe
				if( empty( $dataCerActuel ) ) {
					throw new NotFoundException();
				}

				// Il faut que l'enregistrement à modifier soit en attente
				if( $dataCerActuel['Contratinsertion']['decision_ci'] != 'E' ) {
					throw new InternalErrorException( "Tentative de modification d'un enregistrement déjà traité \"{$contratinsertion_id}\"" );
				}

				$data = $dataCerActuel;

				$modelsToCopy = array( 'Diplomecer93', 'Expprocer93', 'Sujetcer93' );
				foreach( $modelsToCopy as $modelName ) {
					$data[$modelName] = $dataCerActuel['Cer93'][$modelName];
					unset( $data['Cer93'][$modelName] );
				}

				// Bloc 6 : Liste des sujets sur lesquels le CEr porte
				$data['Sujetcer93'] = array( 'Sujetcer93' => Set::classicExtract( $data, 'Sujetcer93.{n}.Cer93Sujetcer93' ) );

				// FIXME: il faut en faire quelque chose de $dataCerActuel
//				$this->log( var_export( $data, true ), LOG_DEBUG );
			}
			// Sinon, on construit un nouvel enregistrement vide, on y met les
			// données CAF et ancien CER.
			else {
				// Création d'un "enregistrement type" vide.
				$data = array(
					'Contratinsertion' => array(
						'id' => null,
						'decision_ci' => 'E',
						'rg_ci' => null
					),
					'Cer93' => array(
						'id' => null,
						'contratinsertion_id' => null,
						'nomutilisateur' => null,
						'structureutilisateur' => null,
						'nivetu' => null
					),
					'Compofoyercer93' => array(),
					'Diplomecer93' => array(),
					'Expprocer93' => array(),
					'Sujetcer93' => array(),
				);

				$dataReferentParcours = $this->Contratinsertion->Personne->PersonneReferent->find(
					'first',
					array(
						'conditions' => array(
							'PersonneReferent.personne_id' => $personne_id,
							'PersonneReferent.dfdesignation IS NULL'
						),
						'contain' => array(
							'Referent'
						)
					)
				);
				// On préremplit le formulaire avec des données du référent affecté (du parcours actuel)
				if( !empty( $dataReferentParcours ) ) {
					$data['Contratinsertion']['structurereferente_id'] = $dataReferentParcours['Referent']['structurereferente_id'];
					$data['Contratinsertion']['referent_id'] = $dataReferentParcours['Referent']['id'];
				}
				// On préremplit le formulaire avec des données de l'utilisateur connecté si possible
				else {
					if( !empty( $dataUser['Structurereferente']['id'] ) ) {
						$data['Contratinsertion']['structurereferente_id'] = $dataUser['Structurereferente']['id'];
					}
					else if( !empty( $dataUser['Referent']['id'] ) ) {
						$data['Contratinsertion']['structurereferente_id'] = $dataUser['Referent']['structurereferente_id'];
						$data['Contratinsertion']['referent_id'] = $dataUser['Referent']['id'];
					}
				}
			}

			// On ajoute d'autres données de l'utilisateur connecté
			// TODO: du coup, on peut faire on delete set null (+la structure ?)
			$data['Cer93']['user_id'] = $user_id;
			$data['Cer93']['nomutilisateur'] = $dataUser['User']['nom_complet'];
			if( !empty( $dataUser['Structurereferente']['id'] ) ) {
				$data['Cer93']['structureutilisateur'] = $dataUser['Structurereferente']['lib_struc'];;
			}
			else if( !empty( $dataUser['Referent']['id'] ) ) {
				$data['Cer93']['structureutilisateur'] = $dataUser['Referent']['Structurereferente']['lib_struc'];
			}

			// Fusion avec les données CAF
			$data = Set::merge( $data, $dataCaf );

			// 1. Récupération de l'adresse complète afin de remplir le champ adresse du CER93
			$adresseComplete = trim( $dataCaf['Adresse']['numvoie'].' '.$dataCaf['Adresse']['libtypevoie'].' '.$dataCaf['Adresse']['nomvoie']."\n".$dataCaf['Adresse']['compladr'].' '.$dataCaf['Adresse']['complideadr'] );

			// 2. Transposition des données
			//Bloc 2 : Etat civil
			$data['Cer93']['matricule'] = $dataCaf['Dossier']['matricule'];
			$data['Cer93']['numdemrsa'] = $dataCaf['Dossier']['numdemrsa'];
			$data['Cer93']['rolepers'] = $dataCaf['Prestation']['rolepers'];
			$data['Cer93']['dtdemrsa'] = $dataCaf['Dossier']['dtdemrsa'];
			$data['Cer93']['identifiantpe'] = $dataCaf['Historiqueetatpe']['identifiantpe'];
			$data['Cer93']['qual'] = $dataCaf['Personne']['qual'];
			$data['Cer93']['nom'] = $dataCaf['Personne']['nom'];
			$data['Cer93']['nomnai'] = $dataCaf['Personne']['nomnai'];
			$data['Cer93']['prenom'] = $dataCaf['Personne']['prenom'];
			$data['Cer93']['dtnai'] = $dataCaf['Personne']['dtnai'];
			$data['Cer93']['adresse'] = $adresseComplete;
			$data['Cer93']['codepos'] = $dataCaf['Adresse']['codepos'];
			$data['Cer93']['nomcom'] = $dataCaf['Adresse']['nomcom'];
			$data['Cer93']['sitfam'] = $dataCaf['Foyer']['sitfam'];
			$data['Cer93']['dtdemrsa'] = $dataCaf['Dossier']['dtdemrsa'];

			// Bloc 3
			if( !isset( $data['Cer93']['inscritpe'] ) || is_null( $data['Cer93']['inscritpe'] ) ) {
				$data['Cer93']['inscritpe'] = null;
				if( isset( $dataCaf['Historiqueetatpe']['etat'] ) && !empty( $dataCaf['Historiqueetatpe']['etat'] ) ) {
					$data['Cer93']['inscritpe'] = ( $dataCaf['Historiqueetatpe']['etat'] == 'inscription' );
				}
			}

			// Copie des données du dernier CER validé en cas d'ajout
			if( empty( $contratinsertion_id ) ) {
				// Données du dernier CER validé
				$sqDernierCerValide = $this->Contratinsertion->sq(
					array(
						'alias' => 'derniercervalide',
						'fields' => array( 'derniercervalide.id' ),
						'conditions' => array(
							'derniercervalide.personne_id = Contratinsertion.personne_id',
							'derniercervalide.decision_ci' => 'V',
						),
						'order' => array( 'derniercervalide.rg_ci DESC' ),
						'limit' => 1
					)
				);
				$querydataDernierCerValide = $querydataCer;
				$querydataDernierCerValide['conditions'] = array(
					'Contratinsertion.personne_id' => $personne_id,
					"Contratinsertion.id IN ( {$sqDernierCerValide} )"
				);

				$dataDernierCerValide = $this->Contratinsertion->find( 'first', $querydataDernierCerValide );

				// Copie des données du dernier CER validé
				if( !empty( $dataDernierCerValide ) ) {
					//Champ pour le bloc 5 reprenant ce qui était prévu dans le pcd CER
					$data['Cer93']['prevupcd'] = $dataDernierCerValide['Cer93']['prevu'];

					// Copie des champs du CER précédent
					$cer93FieldsToCopy = array( 'incoherencesetatcivil', 'cmu', 'cmuc', 'nivetu', 'autresexps', 'secteuracti_id', 'metierexerce_id', 'dureehebdo', 'naturecontrat_id', 'isemploitrouv', 'dureecdd' );
					foreach( $cer93FieldsToCopy as $field ) {
						$data['Cer93'][$field] = $dataDernierCerValide['Cer93'][$field];
					}

					// Copie des enregistrements liés
					$cer93ModelsToCopy = array( 'Diplomecer93', 'Expprocer93', 'Sujetcer93' );
					foreach( $cer93ModelsToCopy as $modelName ) {
						if( isset( $dataDernierCerValide['Cer93'][$modelName] ) ) {
							$data[$modelName] = $dataDernierCerValide['Cer93'][$modelName];
							if( !empty( $data[$modelName] ) ) {
								foreach( array_keys( $data[$modelName] ) as $key ) {
									unset(
										$data[$modelName][$key]['id'],
										$data[$modelName][$key]['cer93_id'],
										$data[$modelName][$key]['Cer93Sujetcer93']['id'],
										$data[$modelName][$key]['Cer93Sujetcer93']['cer93_id']
									);
								}
							}
						}
					}

					if( !empty( $data['Sujetcer93'] ) ) {
						$sousSujetsIds = Hash::filter( (array)Set::extract( $data, '/Sujetcer93/Cer93Sujetcer93/soussujetcer93_id' ) );
						$valeursparSousSujetsIds = Hash::filter( (array)Set::extract( $data, '/Sujetcer93/Cer93Sujetcer93/valeurparsoussujetcer93_id' ) );

						if( !empty( $sousSujetsIds ) ) {
							$sousSujets = $this->Sujetcer93->Soussujetcer93->find( 'list', array( 'conditions' => array( 'Soussujetcer93.id' => $sousSujetsIds ) ) );
							foreach( $data['Sujetcer93'] as $key => $values ) {
								if( isset( $values['Cer93Sujetcer93']['soussujetcer93_id'] ) && !empty( $values['Cer93Sujetcer93']['soussujetcer93_id'] ) ) {
									$data['Sujetcer93'][$key]['Cer93Sujetcer93']['Soussujetcer93'] = array( 'name' => $sousSujets[$values['Cer93Sujetcer93']['soussujetcer93_id']] );
								}
								else {
									$data['Sujetcer93'][$key]['Cer93Sujetcer93']['Soussujetcer93'] = array( 'name' => null );
								}

								if( !empty( $valeursparSousSujetsIds ) ) {
									// Valeur par sous sujet
									$valeursparSousSujets = $this->Sujetcer93->Soussujetcer93->Valeurparsoussujetcer93->find( 'list', array( 'conditions' => array( 'Valeurparsoussujetcer93.id' => $valeursparSousSujetsIds ) ) );

									//Valeur par sous s sujet
									if( isset( $values['Cer93Sujetcer93']['valeurparsoussujetcer93_id'] ) && !empty( $values['Cer93Sujetcer93']['valeurparsoussujetcer93_id'] ) ) {
										$data['Sujetcer93'][$key]['Cer93Sujetcer93']['Valeurparsoussujetcer93'] = array( 'name' => $valeursparSousSujets[$values['Cer93Sujetcer93']['valeurparsoussujetcer93_id']] );
									}
									else {
										$data['Sujetcer93'][$key]['Cer93Sujetcer93']['Valeurparsoussujetcer93'] = array( 'name' => null );
									}
								}
							}
						}

						$data['Cer93']['sujetpcd'] = serialize( array( 'Sujetcer93' => $data['Sujetcer93'] ) );
						$data['Sujetcer93'] = array();
					}
					// Cas où on a un dernier CER validé
					$data['Contratinsertion']['rg_ci'] = ( $dataDernierCerValide['Contratinsertion']['rg_ci'] ) + 1;
				}
				else {
					$data['Contratinsertion']['rg_ci'] = 1;

					// RG CER précédent, puis RG DSP
					$data['Cer93']['nivetu'] = $dataCaf['Dsp']['nivetu'];

					// Si le niveau d'étude vient des DSP, alors le niveau 1201 doit être rempli manuellement car il est scindé dans les DSP
					if( $data['Cer93']['nivetu'] === '1201' ) {
						$data['Cer93']['nivetu'] = null;
					}
				}
			}

			// Les données CAF prévalent
			$data['Cer93']['natlog'] = $dataCaf['Dsp']['natlog'];

			return $data;
		}

		/**
		 * Retourne le chemin relatif du modèle de document à utiliser pour
		 * l'enregistrement du PDF.
		 *
		 * @param array $data
		 * @return string
		 */
		public function modeleOdt( $data ) {
			return "Contratinsertion/contratinsertion.odt";
		}

		/**
		 * Récupère les données pour le PDF.
		 *
		 * @param integer $contratinsertion_id
		 * @param integer $user_id
		 * @return array
		 */
		public function getDataForPdf( $contratinsertion_id, $user_id ) {
			$this->Contratinsertion->Personne->forceVirtualFields = true;
			$Informationpe = ClassRegistry::init( 'Informationpe' );

			$joins = array(
				$this->join( 'Contratinsertion', array( 'type' => 'INNER' ) ),
				$this->join( 'User', array( 'type' => 'INNER' ) ),
				$this->Contratinsertion->join( 'Personne', array( 'type' => 'INNER' )),
				$Informationpe->joinPersonneInformationpe( 'Personne', 'Informationpe', 'LEFT OUTER' ),
				$Informationpe->join( 'Historiqueetatpe', array( 'type' => 'LEFT OUTER' ) ),
				$this->Contratinsertion->join( 'Structurereferente', array( 'type' => 'INNER' )),
				$this->Contratinsertion->join( 'Referent', array( 'type' => 'LEFT OUTER' )),
				$this->Contratinsertion->Personne->join( 'Dsp', array( 'type' => 'LEFT OUTER' )),
				$this->Contratinsertion->Personne->join( 'DspRev', array( 'type' => 'LEFT OUTER' )),
				$this->Contratinsertion->Personne->join( 'Foyer', array( 'type' => 'INNER' )),
				$this->Contratinsertion->Personne->join( 'Prestation', array( 'type' => 'LEFT OUTER'  )),
				$this->Contratinsertion->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
				$this->Contratinsertion->Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
				$this->Contratinsertion->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
				$this->join( 'Naturecontrat', array( 'type' => 'LEFT OUTER' ) ),
				array_words_replace(
					$this->join( 'Metierexerce', array( 'type' => 'LEFT OUTER' ) ),
					array(
						'Metierexerce' => 'Metierexercecer93'
					)
				),
				array_words_replace(
					$this->join( 'Secteuracti', array( 'type' => 'LEFT OUTER' ) ),
					array(
						'Secteuracti' => 'Secteuracticer93'
					)
				)
			);

			$queryData = array(
				'fields' => array_merge(
					$this->fields(),
					$this->User->fields(),
					$this->Contratinsertion->fields(),
					array_words_replace(
						$this->Metierexerce->fields(),
						array(
							'Metierexerce' => 'Metierexercecer93'
						)
					),
					array_words_replace(
						$this->Secteuracti->fields(),
						array(
							'Secteuracti' => 'Secteuracticer93'
						)
					),
					$this->Naturecontrat->fields(),
					$this->Contratinsertion->Structurereferente->fields(),
					$this->Contratinsertion->Structurereferente->Referent->fields(),
					$this->Contratinsertion->Personne->fields(),
					$this->Contratinsertion->Personne->Prestation->fields(),
					$this->Contratinsertion->Personne->Dsp->fields(),
					$this->Contratinsertion->Personne->DspRev->fields(),
					$this->Contratinsertion->Personne->Foyer->fields(),
					$this->Contratinsertion->Personne->Foyer->Adressefoyer->Adresse->fields(),
					$this->Contratinsertion->Personne->Foyer->Dossier->fields(),
					array(
// 						$this->Contratinsertion->vfRgCiMax( '"Personne"."id"' ),
						'Historiqueetatpe.identifiantpe',
						'Historiqueetatpe.etat'
					)
				),
				'joins' => $joins,
				'conditions' => array(
					'Cer93.contratinsertion_id' => $contratinsertion_id,
					array(
						'OR' => array(
							'Adressefoyer.id IS NULL',
							'Adressefoyer.id IN ( '.$this->Contratinsertion->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
						)
					),
					array(
						'OR' => array(
							'Dsp.id IS NULL',
							'Dsp.id IN ( '.$this->Contratinsertion->Personne->Dsp->sqDerniereDsp( 'Personne.id' ).' )'
						)
					),
					array(
						'OR' => array(
							'DspRev.id IS NULL',
							'DspRev.id IN ( '.$this->Contratinsertion->Personne->DspRev->sqDerniere( 'Personne.id' ).' )'
						)
					),
					array(
						'OR' => array(
							'Informationpe.id IS NULL',
							'Informationpe.id IN( '.$Informationpe->sqDerniere( 'Personne' ).' )'
						)
					),
					array(
						'OR' => array(
							'Historiqueetatpe.id IS NULL',
							'Historiqueetatpe.id IN( '.$Informationpe->Historiqueetatpe->sqDernier( 'Informationpe' ).' )'
						)
					)
				),
				'contain' => false
			);

			// On copie les DspsRevs si elles existent à la place des DSPs (on garde l'information la plus récente)
			if( !empty( $data['DspRev']['id'] ) ) {
				$data['Dsp'] = $data['DspRev'];
				unset( $data['DspRev'], $data['Dsp']['id'], $data['Dsp']['dsp_id'] );
			}

			$data = $this->find( 'first', $queryData );

			// Si on ne trouve pas de référent lié au CER, on va chercher le référent de parcours qui était désigné au moment de la date de validation du CER
			if( empty( $data['Referent']['id'] ) ) {
				$referent = $this->Contratinsertion->Personne->PersonneReferent->find(
					'first',
					array(
						'conditions' => array(
							'PersonneReferent.personne_id' => $data['Personne']['id'],
							'PersonneReferent.dddesignation <=' => $data['Contratinsertion']['date_saisi_ci'],
							'OR' => array(
								'PersonneReferent.dfdesignation IS NULL',
								'PersonneReferent.dfdesignation >=' => $data['Contratinsertion']['date_saisi_ci'],
							)
						),
						'contain' => array(
							'Referent'
						),
						'order' => array( 'PersonneReferent.dfdesignation ASC' )
					)
				);

				if( !empty( $referent ) ) {
					$data['Referent'] = $referent['Referent'];
				}
			}

			// Liste des informations concernant la composition du foyer
			$composfoyerscers93 = $this->Contratinsertion->Personne->find(
				'all',
				array(
					'fields' => array(
						'"Personne"."qual" AS "Compofoyercer93__qual"',
						'"Personne"."nom" AS "Compofoyercer93__nom"',
						'"Personne"."prenom" AS "Compofoyercer93__prenom"',
						'"Personne"."dtnai" AS "Compofoyercer93__dtnai"',
						'"Prestation"."rolepers" AS "Compofoyercer93__rolepers"'
					),
					'conditions' => array( 'Personne.foyer_id' => $data['Foyer']['id'] ),
					'contain' => array(
						'Prestation'
					)
				)
			);

			// Liste des diplômes enregistrés pour ce CER
			$diplomescers93 = $this->Diplomecer93->find(
				'all',
				array(
					'fields' => array(
						'Diplomecer93.id',
						'Diplomecer93.cer93_id',
						'Diplomecer93.name',
						'Diplomecer93.annee',
						'Diplomecer93.isetranger'
					),
					'conditions' => array( 'Diplomecer93.cer93_id' => $data['Cer93']['id'] ),
					'order' => array( 'Diplomecer93.annee DESC' ),
					'contain' => false
				)
			);

			// Bloc 4 : Formation et expériece
			// Liste des expériences pro enregistrés pour ce CER
			$expsproscers93 = $this->Expprocer93->find(
				'all',
				array(
					'fields' => array(
						'Expprocer93.id',
						'Expprocer93.cer93_id',
						'Expprocer93.metierexerce_id',
						'Expprocer93.secteuracti_id',
						'Expprocer93.anneedeb',
						'Expprocer93.duree',
						'Expprocer93.nbduree',
						'Expprocer93.typeduree',
						'Metierexerce.name',
						'Secteuracti.name',
					),
					'conditions' => array( 'Expprocer93.cer93_id' => $data['Cer93']['id'] ),
					'order' => array( 'Expprocer93.anneedeb DESC' ),
					'contain' => array(
						'Metierexerce',
						'Secteuracti'
					)
				)
			);

			// Liste des sujets sur lequel porte ce CER
			$sujetscers93 = $this->Cer93Sujetcer93->find(
				'all',
				array(
					'conditions' => array( 'Cer93Sujetcer93.cer93_id' => $data['Cer93']['id'] ),
					'contain' => array(
						'Sujetcer93',
						'Soussujetcer93',
						'Valeurparsoussujetcer93'
					)
				)
			);

			// Transformation des sujets, ... précédents
			$sujetscerspcds93 = array();
			$sujetspcds = Hash::get( $data, 'Cer93.sujetpcd' );
			if( !empty($sujetspcds) ) {
				$sujetspcds = unserialize( $sujetspcds );
				foreach( $sujetspcds['Sujetcer93'] as $i => $sujetcer93pcd ) {
					$sujetscerspcds93[$i]['Soussujetcerpcd93'] = (array)Hash::get( $sujetcer93pcd, 'Cer93Sujetcer93.Soussujetcer93' );
					unset( $sujetcer93pcd['Cer93Sujetcer93']['Soussujetcer93'] );

					$sujetscerspcds93[$i]['Valeurparsoussujetcerpcd93'] = (array)Hash::get( $sujetcer93pcd, 'Cer93Sujetcer93.Valeurparsoussujetcer93' );
					unset( $sujetcer93pcd['Cer93Sujetcer93']['Valeurparsoussujetcer93'] );

					$sujetscerspcds93[$i]['Cerpcd93Sujetcerpcd93'] = (array)Hash::get( $sujetcer93pcd, 'Cer93Sujetcer93' );
					unset( $sujetcer93pcd['Cer93Sujetcer93'] );

					$sujetscerspcds93[$i]['Sujetcerpcd93'] = $sujetcer93pcd;
				}
			}


            // Récupération du nom de l'utilsiateur ayant émis la première lecture
			$histopremierelecture = $this->Histochoixcer93->find(
				'first',
				array(
					'fields' => array_merge(
						$this->Histochoixcer93->fields(),
                        array(
                            $this->Histochoixcer93->User->sqVirtualField( 'nom_complet' ),
                            'User.numtel'
                        )
					),
					'conditions' => array(
                        'Histochoixcer93.cer93_id' => $data['Cer93']['id'],
                        'Histochoixcer93.etape' => '04premierelecture'
                    ),
					'contain' => array(
                        'User'
                    )
				)
			);

            if( !empty( $histopremierelecture ) ) {
                $userPremierelecture = Hash::get( $histopremierelecture, 'User.nom_complet' );
                $data['Cer93']['userpremierelecture'] = $userPremierelecture;
                $data['Cer93']['userpremierelecture_numtel'] = Hash::get( $histopremierelecture, 'User.numtel' );
            }

			return array(
				$data,
				'compofoyer' => $composfoyerscers93,
				'exppro' => $expsproscers93,
				'diplome' => $diplomescers93,
				'sujetcer' => $sujetscers93,
				'sujetcerpcd' => $sujetscerspcds93
			);
		}


		/**
		 * Retourne le PDF par défaut, stocké, ou généré par les appels aux méthodes getDataForPdf, modeleOdt et
		 * à la méthode ged du behavior Gedooo et le stocke,
		 *
		 * @param integer $id Id du CER
		 * @param integer $user_id Id de l'utilisateur connecté
		 * @return string
		 */
		public function getDefaultPdf( $contratinsertion_id, $user_id ) {
			$data = $this->getDataForPdf( $contratinsertion_id, $user_id );
			$modeleodt = $this->modeleOdt( $data );

			$Option = ClassRegistry::init( 'Option' );
			$options =  Set::merge(
				array(
					'Personne' => array(
						'qual' => $Option->qual()
					),
					'Cer93' => array(
						'dureecdd' => $Option->duree_cdd()
					)
				),
				$this->enums()
			);

			return $this->ged( $data, $modeleodt, true, $options );
		}

		/**
		 * Retourne l'ensemble de données liées au CER en cours
		 *
		 * @param integer $id Id du CER
		 * @return array
		 */
		public function dataView( $contratinsertion_id ) {

			// Recherche du contrat pour l'affichage
			$data = $this->Contratinsertion->find(
				'first',
				array(
					'conditions' => array(
						'Contratinsertion.id' => $contratinsertion_id
					),
					'contain' => array(
						'Cer93' => array(
							'Compofoyercer93',
							'Diplomecer93',
							'Expprocer93',
							'Histochoixcer93' => array(
								'User' => array(
									'fields' => array( 'nom_complet' )
								),
								'order' => array( 'Histochoixcer93.etape ASC' ),
								'Commentairenormecer93'
							),
							'Sujetcer93'
						),
						'Structurereferente' => array(
							'Typeorient'
						),
						'Referent',
						'Personne' => array(
							'Foyer' => array(
								'Adressefoyer' => array(
									'Adresse',
									'conditions' => array(
										'Adressefoyer.id IN (
											'.ClassRegistry::init( 'Adressefoyer' )->sqDerniereRgadr01('Adressefoyer.foyer_id').'
										)'
									),
									'NvTransfertpdv93'
								)
							)
						)
					)
				)
			);

			$data = $this->Contratinsertion->Personne->Foyer->Adressefoyer->NvTransfertpdv93->calculVfdateAnterieureTransfert(
				$data,
				'Personne.Foyer.Adressefoyer.0.NvTransfertpdv93.created',
				'Contratinsertion.date_saisi_ci',
				'Personne.Foyer.Adressefoyer.0.NvTransfertpdv93.encoursvalidation'
			);

			$data['Adresse'] = $data['Personne']['Foyer']['Adressefoyer'][0]['Adresse'];

			$sousSujetsIds = Hash::filter( (array)Set::extract( $data, '/Cer93/Sujetcer93/Cer93Sujetcer93/soussujetcer93_id' ) );
			$valeursparSousSujetsIds = Hash::filter( (array)Set::extract( $data, '/Cer93/Sujetcer93/Cer93Sujetcer93/valeurparsoussujetcer93_id' ) );
			if( !empty( $sousSujetsIds ) ) {
				$sousSujets = $this->Sujetcer93->Soussujetcer93->find( 'list', array( 'conditions' => array( 'Soussujetcer93.id' => $sousSujetsIds ) ) );

				foreach( $data['Cer93']['Sujetcer93'] as $key => $values ) {
					if( isset( $values['Cer93Sujetcer93']['soussujetcer93_id'] ) && !empty( $values['Cer93Sujetcer93']['soussujetcer93_id'] ) ) {
						$data['Cer93']['Sujetcer93'][$key]['Cer93Sujetcer93']['Soussujetcer93'] = array( 'name' => $sousSujets[$values['Cer93Sujetcer93']['soussujetcer93_id']] );
					}
					else {
						$data['Cer93']['Sujetcer93'][$key]['Cer93Sujetcer93']['Soussujetcer93'] = array( 'name' => null );
					}

					if( !empty( $valeursparSousSujetsIds ) ) {
						// Valeur par sous sujet
						$valeursparSousSujets = $this->Sujetcer93->Soussujetcer93->Valeurparsoussujetcer93->find( 'list', array( 'conditions' => array( 'Valeurparsoussujetcer93.id' => $valeursparSousSujetsIds ) ) );

						//Valeur par sous s sujet
						if( isset( $values['Cer93Sujetcer93']['valeurparsoussujetcer93_id'] ) && !empty( $values['Cer93Sujetcer93']['valeurparsoussujetcer93_id'] ) ) {
							$data['Cer93']['Sujetcer93'][$key]['Cer93Sujetcer93']['Valeurparsoussujetcer93'] = array( 'name' => $valeursparSousSujets[$values['Cer93Sujetcer93']['valeurparsoussujetcer93_id']] );
						}
						else {
							$data['Cer93']['Sujetcer93'][$key]['Cer93Sujetcer93']['Valeurparsoussujetcer93'] = array( 'name' => null );
						}
					}
				}
			}

			return $data;
		}


		/**
		 *	Liste des options envoyées à la vue pour le CER93
		 * 	@return array
		 */
		public function optionsView() {
			// Options
			$options = array(
				'Cer93' => array(
					'formeci' => ClassRegistry::init( 'Option' )->forme_ci(),
					'qual' => ClassRegistry::init( 'Option' )->qual()
				),
				'Contratinsertion' => array(
					'structurereferente_id' => $this->Contratinsertion->Structurereferente->listOptions(),
					'referent_id' => $this->Contratinsertion->Referent->listOptions()
				),
				'Prestation' => array(
					'rolepers' => ClassRegistry::init( 'Option' )->rolepers()
				),
				'Personne' => array(
					'qual' => ClassRegistry::init( 'Option' )->qual()
				),
				'Serviceinstructeur' => array(
					'typeserins' => ClassRegistry::init( 'Option' )->typeserins()
				),
				'Expprocer93' => array(
					'metierexerce_id' => $this->Expprocer93->Metierexerce->find( 'list' ),
					'secteuracti_id' => $this->Expprocer93->Secteuracti->find( 'list' )
				),
				'Foyer' => array(
					'sitfam' => ClassRegistry::init( 'Option' )->sitfam()
				),
				'Dsp' => array(
					'natlog' => ClassRegistry::init( 'Option' )->natlog()
				),
				'dureehebdo' => array_range( '0', '39' ),
				'dureecdd' => ClassRegistry::init( 'Option' )->duree_cdd(),
				'Structurereferente' => array(
					'type_voie' => ClassRegistry::init( 'Option' )->typevoie()
				),
				'Naturecontrat' => array(
					'naturecontrat_id' => $this->Naturecontrat->find( 'list' )
				)
			);
			$options = Set::merge(
				$this->Contratinsertion->Personne->Dsp->enums(),
				$this->enums(),
				$this->Histochoixcer93->enums(),
				$this->Expprocer93->enums(),
				$options
			);
			return $options;

		}

		/**
		 * Retourne le PDF de décision pour un CER donné
		 *
		 * @param integer $id Id du CER
		 * @param integer $user_id Id de l'utilisateur connecté
		 * @return string
		 */

		public function getDecisionPdf( $contratinsertion_id, $user_id ) {
			$options = $this->optionsView();
// 			$data = $this->dataView( $contratinsertion_id );
			$data = $this->getDataForPdf( $contratinsertion_id, $user_id );
			$data = $data[0];

			$dateimpressiondecision = date( 'Y-m-d' );

			if( !empty( $dateimpressiondecision ) ) {
				$this->updateAllUnBound(
					array( 'Cer93.dateimpressiondecision' => '\''.$dateimpressiondecision.'\'' ),
					array(
						'"Cer93"."id"' => $data['Cer93']['id']
					)
				);
			}

			// Choix du modèle de document
			$decision = $data['Contratinsertion']['decision_ci'];
			// Forme du CER
			$formeci = $data['Contratinsertion']['forme_ci'];

			if( $formeci == 'S' ) {
				if( $decision == 'V' ) {
					$modeleodt  = "Contratinsertion/cer_valide.odt";
				}
				else if( in_array( $decision, array( 'R', 'N' ) ) ){
					$modeleodt  = "Contratinsertion/cer_rejete.odt";
				}
			}
			else {
				if( $decision == 'V' ) {
					$modeleodt  = "Contratinsertion/decision_valide.odt";
				}
				else if( in_array( $decision, array( 'R', 'N' ) ) ){
					$modeleodt  = "Contratinsertion/decision_rejete.odt";
				}
			}

			return $this->ged( $data, $modeleodt, false, $options );
		}

		/**
		 * Retourne l'id du dossier à partir de l'id du CER (CG 93)
		 *
		 * @param integer $id
		 * @return integer
		 */
		public function dossierId( $id ) {
			$contratinsertion = $this->find(
				'first',
				array(
					'fields' => array(
						'Foyer.dossier_id'
					),
					'joins' => array(
						$this->join( 'Contratinsertion', array( 'type' => 'INNER' ) ),
						$this->Contratinsertion->join( 'Personne', array( 'type' => 'INNER' ) ),
						$this->Contratinsertion->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
					),
					'conditions' => array(
						'Cer93.id' => $id
					),
					'contain' => false
				)
			);

			if( !empty( $contratinsertion ) ) {
				return $contratinsertion['Foyer']['dossier_id'];
			}
			else {
				return null;
			}
		}

		/**
		 * Retourne l'id de la personne à laquelle est lié un enregistrement.
		 *
		 * @param integer $id L'id de l'enregistrement
		 * @return integer
		 */
		public function personneId( $id ) {
			$querydata = array(
				'fields' => array( "Contratinsertion.personne_id" ),
				'joins' => array(
					$this->join( 'Contratinsertion', array( 'type' => 'INNER' ) )
				),
				'conditions' => array(
					"{$this->alias}.id" => $id
				),
				'recursive' => -1
			);

			$result = $this->find( 'first', $querydata );

			if( !empty( $result ) ) {
				return $result['Contratinsertion']['personne_id'];
			}
			else {
				return null;
			}
		}

		/**
		 * Retourne les options nécessaires au formulaire de recherche
		 *
		 * @param array $params <=> array( 'find' => false, 'autre' => false )
		 * @return array
		 */
		public function options( array $params = array() ) {
			$options = array();
			$params = $params + array( 'find' => false, 'autre' => false );

			$foreignKeyPrev = null;
			foreach( array( 'Sujetcer93', 'Soussujetcer93', 'Valeurparsoussujetcer93' ) as $modelName ) {
				$Model = ClassRegistry::init( $modelName );

				// Find list normal
				if( Hash::get( $params, 'find' ) ) {
					$query = array(
						'fields' => array(
							"{$Model->alias}.{$Model->primaryKey}",
							"{$Model->alias}.{$Model->displayField}"
						),
						'order' => array(
							"{$Model->alias}.name ASC"
						)
					);

					if( !empty( $foreignKeyPrev ) ) {
						array_unshift( $query['order'], "{$Model->alias}.{$foreignKeyPrev} ASC" );
						$query['fields'] = array(
							"{$Model->alias}.{$Model->primaryKey}",
							"{$Model->alias}.{$Model->displayField}",
							"{$Model->alias}.{$foreignKeyPrev}",
						);
					}

					$results = (array)$Model->find( 'all', $query );

					if( !empty( $foreignKeyPrev ) ) {
						$results = Hash::combine(
							$results,
							array( '%s_%s', "{n}.{$Model->alias}.{$foreignKeyPrev}", "{n}.{$Model->alias}.{$Model->primaryKey}" ),
							"{n}.{$Model->alias}.{$Model->displayField}"
						);
					}
					else {
						$results = Hash::combine(
							$results,
							"{n}.{$Model->alias}.{$Model->primaryKey}",
							"{n}.{$Model->alias}.{$Model->displayField}"
						);
					}

					$options['Cer93Sujetcer93'][Inflector::underscore( $Model->alias ).'_id'] = $results;
				}

				// Valeurs "Autre"
				if( Hash::get( $params, 'autre' ) ) {
					$query = array(
						'conditions' => array(
							"{$Model->alias}.isautre" => 1
						)
					);
					$options['Autre']['Cer93Sujetcer93'][Inflector::underscore( $Model->alias ).'_id'] = array_keys( (array)$Model->find( 'list', $query ) );
				}

				//
				$foreignKeyPrev = Inflector::underscore( $Model->alias ).'_id';
			}

			return $options;
		}
	}
?>