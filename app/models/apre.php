<?php
	class Apre extends AppModel
	{
		public $name = 'Apre';

		public $displayField = 'numeroapre';

		public $aidesApre = array( 'Formqualif', 'Formpermfimo', 'Actprof', 'Permisb', 'Amenaglogt', 'Acccreaentr', 'Acqmatprof', 'Locvehicinsert' );

		public $modelsFormation = array( 'Formqualif', 'Formpermfimo', 'Permisb', 'Actprof' );

		public $deepAfterFind = true;

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'typedemandeapre' => array( 'type' => 'typedemandeapre', 'domain' => 'apre' ),
					'naturelogement' => array( 'type' => 'naturelogement', 'domain' => 'apre' ),
					'activitebeneficiaire' => array( 'type' => 'activitebeneficiaire', 'domain' => 'apre' ),
					'typecontrat' => array( 'type' => 'typecontrat', 'domain' => 'apre' ),
					'statutapre' => array( 'type' => 'statutapre', 'domain' => 'apre' ),
// 					'ajoutcomiteexamen' => array( 'type' => 'no', 'domain' => 'apre' ),
					'etatdossierapre' => array( 'type' => 'etatdossierapre', 'domain' => 'apre' ),
					'eligibiliteapre' => array( 'type' => 'eligibiliteapre', 'domain' => 'apre' ),
// 					'presence' => array( 'type' => 'presence', 'domain' => 'apre' ),
					'justificatif' => array( 'type' => 'justificatif', 'domain' => 'apre' ),
					'isdecision' => array( 'domain' => 'apre' ),
					'haspiecejointe' => array( 'domain' => 'apre' )
				)
			),
			'Frenchfloat' => array(
				'fields' => array(
					'montantaverser',
					'montantattribue',
					'montantdejaverse'
				)
			),
			'Formattable',
			'Gedooo'
		);

		public $validate = array(
			'secteurprofessionnel' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'typedemandeapre' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'activitebeneficiaire' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'montantaverser' => array(
				array(
					'rule' => 'numeric',
					'message' => 'Veuillez entrer une valeur numérique.'
				),
			),
			'montantattribue' => array(
				array(
					'rule' => 'numeric',
					'message' => 'Veuillez entrer une valeur numérique.'
				),
				array(
					'rule' => array( 'comparison', '>=', 0 ),
					'message' => 'Veuillez entrer un nombre positif.'
				)
			),
			'structurereferente_id' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'nbheurestravaillees' => array(
				array(
					'rule' => array( 'comparison', '>=', 0 ),
					'message' => 'Veuillez saisir une valeur positive.',
					'allowEmpty' => true
				)
			),
			'datedemandeapre' => array(
				'rule' => 'date',
				'message' => 'Veuillez vérifier le format de la date.',
				'allowEmpty' => true
			),
			'dateentreeemploi' => array(
				'rule' => 'date',
				'message' => 'Veuillez vérifier le format de la date.',
				'allowEmpty' => true
			)
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
			'Referent' => array(
				'className' => 'Referent',
				'foreignKey' => 'referent_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasOne = array(
			'Acccreaentr' => array(
				'className' => 'Acccreaentr',
				'foreignKey' => 'apre_id',
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
			'Acqmatprof' => array(
				'className' => 'Acqmatprof',
				'foreignKey' => 'apre_id',
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
			'Actprof' => array(
				'className' => 'Actprof',
				'foreignKey' => 'apre_id',
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
			'Amenaglogt' => array(
				'className' => 'Amenaglogt',
				'foreignKey' => 'apre_id',
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
			'Permisb' => array(
				'className' => 'Permisb',
				'foreignKey' => 'apre_id',
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
			'Formqualif' => array(
				'className' => 'Formqualif',
				'foreignKey' => 'apre_id',
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
			'Formpermfimo' => array(
				'className' => 'Formpermfimo',
				'foreignKey' => 'apre_id',
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
			'Locvehicinsert' => array(
				'className' => 'Locvehicinsert',
				'foreignKey' => 'apre_id',
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


		public $hasMany = array(
			'Montantconsomme' => array(
				'className' => 'Montantconsomme',
				'foreignKey' => 'apre_id',
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
			'Relanceapre' => array(
				'className' => 'Relanceapre',
				'foreignKey' => 'apre_id',
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
                    'Fichiermodule.modele = \'Apre\'',
                    'Fichiermodule.fk_value = {$__cakeID__$}'
                ),
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
			'Comiteapre' => array(
				'className' => 'Comiteapre',
				'joinTable' => 'apres_comitesapres',
				'foreignKey' => 'apre_id',
				'associationForeignKey' => 'comiteapre_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'ApreComiteapre'
			),
			'Etatliquidatif' => array(
				'className' => 'Etatliquidatif',
				'joinTable' => 'apres_etatsliquidatifs',
				'foreignKey' => 'apre_id',
				'associationForeignKey' => 'etatliquidatif_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'ApreEtatliquidatif'
			),
			'Pieceapre' => array(
				'className' => 'Pieceapre',
				'joinTable' => 'apres_piecesapre',
				'foreignKey' => 'apre_id',
				'associationForeignKey' => 'pieceapre_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'AprePieceapre'
			)
		);

		/**
		*
		*/

		public function sqApreNomaide() {
			$dbo = $this->getDataSource( $this->useDbConfig );
			$natureAidesApres = ClassRegistry::init( 'Option' )->natureAidesApres();

			$case = "CASE \n";
			foreach( array_keys( $natureAidesApres ) as $aideModel ) {
				$tableName = $dbo->fullTableName( $this->{$aideModel}, false );
				$case .= "WHEN EXISTS( SELECT * FROM {$tableName} AS \"{$aideModel}\" WHERE \"Apre\".\"id\" = \"{$aideModel}\".\"apre_id\" ) THEN '{$aideModel}'\n";
			}
			$case .= 'ELSE NULL END';

			return $case;
		}

		/**
		*
		*/

		public function sqApreAllocation() {
			return "CASE WHEN \"Apre\".\"statutapre\" = 'F' THEN \"Apre\".\"mtforfait\" ELSE \"ApreEtatliquidatif\".\"montantattribue\" END";
		}

		/**
		*
		*/

		public function sousRequeteMontanttotal() {
			$fieldTotal = array();
			foreach( $this->aidesApre as $modelAide ) {
				$fieldTotal[] = "\"{$modelAide}\".\"montantaide\"";
			}
			return '( COALESCE( '.implode( ', 0 ) + COALESCE( ', $fieldTotal ).', 0 ) )';
		}

		/**
		*
		*/

		public function joinsAidesLiees( $tiersprestataire = false ) {
			$joins = array();
			foreach( $this->aidesApre as $modelAide ) {
				$joins[] = array(
					'table'      => Inflector::tableize( $modelAide ),
					'alias'      => $modelAide,
					'type'       => 'LEFT OUTER',
					'foreignKey' => false,
					'conditions' => array( "Apre.id = {$modelAide}.apre_id" )
				);
			}
			return $joins;
		}

		/**
		*
		*/

		public function qdFormationsPourPdf() {
			$querydata = array();
			$conditionsTiersprestataireapre = array();

			foreach( $this->modelsFormation as $modelAide ) {
				$querydata['joins'][] = array(
					'table'      => Inflector::tableize( $modelAide ),
					'alias'      => $modelAide,
					'type'       => 'LEFT OUTER',
					'foreignKey' => false,
					'conditions' => array( "Apre.id = {$modelAide}.apre_id" )
				);

				$conditionsTiersprestataireapre[] = "{$modelAide}.tiersprestataireapre_id = Tiersprestataireapre.id";
			}

			$querydata['fields'] = array(
				'Tiersprestataireapre.nomtiers',
				'Tiersprestataireapre.guiban',
				'Tiersprestataireapre.etaban',
				'Tiersprestataireapre.numcomptban',
				'Tiersprestataireapre.clerib'
			);

			$querydata['joins'][] = array(
				'table'      => Inflector::tableize( 'Tiersprestataireapre' ),
				'alias'      => 'Tiersprestataireapre',
				'type'       => 'LEFT OUTER',
				'foreignKey' => false,
				'conditions' => array( 'OR' => $conditionsTiersprestataireapre )
			);

			return $querydata;
		}

		/**
		*
		*/

		public function dossierId( $apre_id ){
			$this->unbindModelAll();
			$this->bindModel(
				array(
					'hasOne' => array(
						'Personne' => array(
							'foreignKey' => false,
							'conditions' => array( 'Personne.id = Apre.personne_id' )
						),
						'Foyer' => array(
							'foreignKey' => false,
							'conditions' => array( 'Foyer.id = Personne.foyer_id' )
						)
					)
				)
			);
			$apre = $this->findById( $apre_id, null, null, 0 );

			if( !empty( $apre ) ) {
				return $apre['Foyer']['dossier_id'];
			}
			else {
				return null;
			}
		}

		/**
		*   Récupération des pièces liées à une APRE ainsi que les pièces des aides liées à cette APRE
		*/

		public function _nbrNormalPieces() {
			$nbNormalPieces = array();
			$nbNormalPieces['Apre'] = $this->Pieceapre->find( 'count' );
			foreach( $this->aidesApre as $model ) {
				$nbNormalPieces[$model] = $this->{$model}->{'Piece'.strtolower( $model )}->find( 'count' );
			}
			return $nbNormalPieces;
		}

		/**
		*   Détails des APREs afin de récupérer les pièces liés à cette APRE ainsi que les aides complémentaires avec leurs pièces
		*   @param int $id
		*/

		public function _details( $apre_id ) {
			$nbNormalPieces = $this->_nbrNormalPieces();
			$details['Piecepresente'] = array();
			$details['Piecemanquante'] = array();

			// Nombre de pièces trouvées par-rapport au nombre de pièces prévues - Apre
			$details['Piecepresente']['Apre'] = $this->AprePieceapre->find( 'count', array( 'conditions' => array( 'apre_id' => $apre_id ) ) );
			$details['Piecemanquante']['Apre'] = abs( $details['Piecepresente']['Apre'] - $nbNormalPieces['Apre'] );

			// Quelles sont les pièces manquantes
			$piecesPresentes = Set::extract(
                $this->AprePieceapre->find(
                    'all',
                    array(
                        'fields' => array( 'AprePieceapre.pieceapre_id' ),
                        'conditions' => array( 'apre_id' => $apre_id ),
                        'contain' => false
                    )
                ),
                '/AprePieceapre/pieceapre_id'
            );

			$conditions = array();
			if( !empty( $piecesPresentes ) ) {
				$conditions = array( 'NOT' => array( 'Pieceapre.id' => $piecesPresentes ) );
			}

			$piecesAbsentes = $this->Pieceapre->find( 'list', array( 'conditions' => $conditions, 'recursive' => -1 ) );
			$details['Piece']['Manquante']['Apre'] = $piecesAbsentes;

			/// Essaie de récupération des pièces des aides liées
			foreach( $this->aidesApre as $model ) {
				// Nombre de pièces trouvées par-rapport au nombre de pièces prévues pour chaque type d'aide
				$aides = $this->{$model}->find(
					'all',
					array(
						'conditions' => array(
							"$model.apre_id" => $apre_id
						),
                        'contain' => array(
                            'Piece'.strtolower( $model )
                        )
					)
				);

				// Combien d'aides liées à l'APRE sont présentes pour chaque type d'aide
				$details['Natureaide'][$model] = count( $aides );

				if( !empty( $aides ) ) {
					$details['Piecepresente'][$model] = count( Set::filter( Set::extract( $aides, '/Piece'.strtolower( $model ) ) ) );
					$details['Piecemanquante'][$model] = abs( $nbNormalPieces[$model] - $details['Piecepresente'][$model] );

					if( !empty( $details['Piecemanquante'][$model] ) ) {
						$piecesAidesPresentes = Set::extract(
							$aides,
							'/Piece'.strtolower( $model ).'/'.$model.'Piece'.strtolower( $model ).'/piece'.strtolower( $model ).'_id'
						);

						$piecesAidesAbsentes = array();
						$conditions = array();
						if( !empty( $piecesAidesPresentes ) ) {
							$conditions = array( 'NOT' => array( 'Piece'.strtolower( $model ).'.id' => $piecesAidesPresentes ) );
						}
						$piecesAidesAbsentes = $this->{$model}->{'Piece'.strtolower( $model )}->find( 'list', array( 'recursive' => -1, 'conditions' => $conditions ) );

						$details['Piece']['Manquante'][$model] = $piecesAidesAbsentes;
					}
				}
			}

			return $details;
		}

		/**
		*
		*/

		public function afterFind( $results, $primary = false ) {
			parent::afterFind( $results, $primary );

			if( $this->deepAfterFind && !empty( $results ) && Set::check( $results, '0.Apre' ) ) {
				foreach( $results as $key => $result ) {
					if( isset( $result['Apre']['id'] ) ) {
						$results[$key]['Apre'] = Set::merge(
							$results[$key]['Apre'],
							$this->_details( $result['Apre']['id'] )
						);
					}
					else if( isset( $result['Apre'][0]['id'] ) ) {
						foreach( $result['Apre'] as $key2 => $result2 ) {
							$results[$key]['Apre'][$key2] = Set::merge(
								$results[$key]['Apre'][$key2],
								$this->_details( $result2['id'] )
							);
						}
					}
				}
			}

			return $results;
		}

		/**
		*
		*/

		public function beforeSave( $options = array() ) {
			$return = parent::beforeSave( $options );
			$statutapre = Set::classicExtract( $this->data, "{$this->alias}.statutapre" );

			if( $statutapre == 'C' ) {
				$valide = true;
				$nbNormalPieces = $this->_nbrNormalPieces();
				foreach( $nbNormalPieces as $aide => $nbPieces ) {
					$key = 'Piece'.strtolower( $aide );
					if( isset( $this->data[$aide] ) && isset( $this->data[$key] ) && isset( $this->data[$key][$key] ) ) {
						$valide = ( count( $this->data[$key][$key] ) == $nbPieces ) && $valide;
					}
				}
				$this->data['Apre']['etatdossierapre'] = ( $valide ? 'COM' : 'INC' );
			}
			else if( $statutapre == 'F' ){
				$this->data['Apre']['etatdossierapre'] = 'COM';
			}

			if( array_key_exists( $this->name, $this->data ) && array_key_exists( 'referent_id', $this->data[$this->name] ) ) {
				$this->data = Set::insert( $this->data, "{$this->alias}.referent_id", suffix( Set::extract( $this->data, "{$this->alias}.referent_id" ) ) );
			}
			return $return;
		}

		/**
		*
		*/

		public function supprimeFormationsObsoletes( $apre ) {
			foreach( $this->modelsFormation as $formation ) {
				if( !isset( $apre[$formation] ) ) {
					$this->{$formation}->deleteAll( array( "{$formation}.apre_id" => Set::classicExtract( $apre, 'Apre.id' ) ), true, true );
				}
			}
		}

		/**
		*
		*/

		public function supprimeAidesObsoletes( $apre ) {
			foreach( $this->aidesApre as $formation ) {
				if( !isset( $apre[$formation] ) ) {
					$this->{$formation}->deleteAll( array( "{$formation}.apre_id" => Set::classicExtract( $apre, 'Apre.id' ) ), true, true );
				}
			}
		}

		/**
		*
		*/


		public function afterSave( $created ) {
			$return = parent::afterSave( $created );

			$details = $this->_details( $this->id );

			$personne_id = Set::classicExtract( $this->data, "{$this->alias}.personne_id" );
			$statutapre = Set::classicExtract( $this->data, "{$this->alias}.statutapre" );

			if( !empty( $personne_id ) && ( $statutapre == 'C' ) && Configure::read( 'Cg.departement' ) == 66 ){
				$return = $this->query( "UPDATE apres SET eligibiliteapre = 'O' WHERE apres.personne_id = {$personne_id} AND apres.etatdossierapre = 'COM' AND ( SELECT COUNT(contratsinsertion.id) FROM contratsinsertion WHERE contratsinsertion.personne_id = {$personne_id} ) > 0;" ) && $return;

				$return = $this->query( "UPDATE apres SET eligibiliteapre = 'N' WHERE apres.personne_id = {$personne_id} AND NOT ( apres.etatdossierapre = 'COM' AND ( SELECT COUNT(contratsinsertion.id) FROM contratsinsertion WHERE contratsinsertion.personne_id = {$personne_id} ) > 0 );" ) && $return;
			}
			else if( Configure::read( 'Cg.departement' ) == 93 ){
				$return = $this->query( "UPDATE apres SET eligibiliteapre = 'O' WHERE apres.personne_id = {$personne_id} AND apres.etatdossierapre = 'COM';" ) && $return;
				$return = $this->query( "UPDATE apres SET eligibiliteapre = 'N' WHERE apres.personne_id = {$personne_id} AND NOT ( apres.etatdossierapre = 'COM' );" ) && $return;
			}

			// FIXME: return ?
			return $return;
		}


		/**
		* Mise à jour des montants déjà versés pour chacune des APREs
		* FIXME: pas de valeur de retour car $return est à false ?
		*/

		public function calculMontantsDejaVerses( $apre_ids ) {
			$return = true;

			if( !is_array( $apre_ids ) ) {
				$apre_ids = array( $apre_ids );
			}

			foreach( $apre_ids as $id ) {
				/*$return = */$this->query( "UPDATE apres SET montantdejaverse = ( SELECT SUM( apres_etatsliquidatifs.montantattribue ) FROM apres_etatsliquidatifs WHERE apres_etatsliquidatifs.apre_id = {$id} GROUP BY apres_etatsliquidatifs.apre_id ) WHERE apres.id = {$id};" )/* && $return*/;
			}

			return $return;
		}

		/**
		* Récupération des données des APREs Forfaitaires lors de l'impression des notifications selon une APRE
		*   @param int $id
		*/

		public function donneesForfaitaireGedooo( $apre_id, $etatliquidatif_id ) {
			$this->bindModel(
				array(
					'hasOne' => array(
						'ApreEtatliquidatif' => array(
							'conditions' => array( 'ApreEtatliquidatif.etatliquidatif_id' => $etatliquidatif_id )
						)
					)
				)
			);
			$apre = $this->findById( $apre_id, null, null, 1 );

			if( !empty( $apre ) ) {
				unset( $apre['Apre']['Piecemanquante'] );
				unset( $apre['Apre']['Piecepresente'] );
				unset( $apre['Apre']['Piece'] );
				unset( $apre['Pieceapre'] );
				unset( $apre['Comiteapre'] );
				unset( $apre['Relanceapre'] );

				if( $apre['Apre']['statutapre'] == 'F' ) {
					$apre['Apre']['allocation'] = $apre['Apre']['mtforfait'];
				}
				else if( $apre['Apre']['statutapre'] == 'C' ) {
					$apre['Apre']['allocation'] = $apre['ApreEtatliquidatif']['montantattribue'];
				}
				else {
					$this->cakeError( 'error500' );
				}

				///Données nécessaire pour obtenir l'adresse du bénéficiaire
				$AdressefoyerModel = ClassRegistry::init( 'Adressefoyer' );
				$TiersprestataireapreModel = ClassRegistry::init( 'Tiersprestataireapre' );

				$AdressefoyerModel->bindModel(
					array(
						'belongsTo' => array(
							'Adresse' => array(
								'className'     => 'Adresse',
								'foreignKey'    => 'adresse_id'
							)
						)
					)
				);

				$adresse = $AdressefoyerModel->find(
					'first',
					array(
						'conditions' => array(
							'Adressefoyer.foyer_id' => Set::classicExtract( $apre, 'Personne.foyer_id' ),
							'Adressefoyer.rgadr' => '01',
						)
					)
				);
				$apre['Adresse'] = $adresse['Adresse'];

				/**
				*   Début:
				*   Partie pour les aides Liées à une APRE complémentaire
				*/

				foreach( $this->aidesApre as $model ) {
					if( ( $apre['Apre']['Natureaide'][$model] == 0 ) ){
						unset( $apre['Apre']['Natureaide'][$model] );
					}
				}
				$modelFormation = array( 'Formqualif', 'Formpermfimo', 'Permisb', 'Actprof' );
				$modelHorsFormation = array( 'Acqmatprof', 'Amenaglogt', 'Locvehicinsert', 'Acccreaentr' );

				///Paramètre nécessaire pour connaitre le type de formation du bénéficiaire (Formation / Hors Formation )
				if( array_any_key_exists( $modelFormation, $apre['Apre']['Natureaide'] ) ) {
					$typeformation = 'Formation';
				}
				else {
					$typeformation = 'HorsFormation';
				}

				$apre['Apre']['Natureaide'] = array_keys( $apre['Apre']['Natureaide'] );


				foreach( $modelFormation as $model ){
					$tmpId = Set::classicExtract( $apre, "{$model}.tiersprestataireapre_id" );
					if( !empty( $tmpId ) ) {
						$dataTiersprestataireapre_id = $tmpId;
					}
				}

				//Pour récupérer les modèles des aides liées à l'APRE
				$apre['Modellie'] = array();
				if( !empty( $apre['Apre']['Natureaide'] ) ){
					foreach( $apre['Apre']['Natureaide'] as $key => $modelBon ){
						$apre['Modellie'] = $apre[$modelBon];
					}
				}

				///Données faisant le lien entre l'APRE, ses Aides et le tiers prestataire lié à l'aide
				if( !empty( $dataTiersprestataireapre_id ) ) {
					$tiersprestataire = $TiersprestataireapreModel->find(
						'first',
						array(
							'conditions' => array(
								'Tiersprestataireapre.id' => $dataTiersprestataireapre_id
							)
						)
					);
					$apre['Tiersprestataireapre'] = $tiersprestataire['Tiersprestataireapre'];
				}

				///Données faisant le lien entre l'APRE et son comité
				$aprecomiteapre = $this->ApreComiteapre->find(
					'first',
					array(
						'conditions' => array(
							'ApreComiteapre.apre_id' => $apre_id
						)
					)
				);
				$apre['ApreComiteapre'] = $aprecomiteapre['ApreComiteapre'];


				///Données concernant le comité de l'APRE
				$comiteapre = $this->Comiteapre->find(
					'first',
					array(
						'conditions' => array(
							'Comiteapre.id' => Set::classicExtract( $apre, 'ApreComiteapre.comiteapre_id' )
						)
					)
				);
				$apre['Comiteapre'] = $comiteapre['Comiteapre'];

				///Données concernant les coordonées bancaires du tiers
				if( !empty( $dataTiersprestataireapre_id ) ) {
					$domiciliationbancaire = $this->find(
						'first',
						array(
							'fields' => array(
								'Domiciliationbancaire.libelledomiciliation'
							),
							'joins' => array(
								array(
									'table'      => 'tiersprestatairesapres',
									'alias'      => 'Tiersprestataireapre',
									'type'       => 'INNER',
									'foreignKey' => false,
									'conditions' => array(
										'Tiersprestataireapre.id' => $dataTiersprestataireapre_id
									)
								),
								array(
									'table'      => 'domiciliationsbancaires',
									'alias'      => 'Domiciliationbancaire',
									'type'       => 'INNER',
									'foreignKey' => false,
									'conditions' => array(
										'Domiciliationbancaire.codebanque = Tiersprestataireapre.etaban',
										'Domiciliationbancaire.codeagence = Tiersprestataireapre.guiban'
									)
								),
							),
							'recursive' => -1,
						)
					);
					$apre = Set::merge( $apre, $domiciliationbancaire );
				}

				/**
				*  Fin de la Partie pour les aides Liées à une APRE complémentaire
				*/
			}

			return $apre;
		}
	}
?>
