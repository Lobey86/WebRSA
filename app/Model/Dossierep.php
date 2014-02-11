<?php
	/**
	 * Code source de la classe Dossierep.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Dossierep ...
	 *
	 * @package app.Model
	 */
	class Dossierep extends AppModel
	{
		public $name = 'Dossierep';

		public $recursive = -1;

		public $actsAs = array(
			'Allocatairelie',
			'Autovalidate2',
			'Conditionnable',
			'Enumerable' => array(
				'fields' => array(
					'etapedossierep',
					'themeep',
				)
			),
			'Formattable',
			'ValidateTranslate',
		);

		public $belongsTo = array(
			/*'Commissionep' => array(
				'className' => 'Commissionep',
				'foreignKey' => 'commissionep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),*/
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

		public $hasOne = array(
			// Thèmes 66
			'Saisinebilanparcoursep66' => array(
				'className' => 'Saisinebilanparcoursep66',
				'foreignKey' => 'dossierep_id',
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
			'Saisinepdoep66' => array(
				'className' => 'Saisinepdoep66',
				'foreignKey' => 'dossierep_id',
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
			'Defautinsertionep66' => array(
				'className' => 'Defautinsertionep66',
				'foreignKey' => 'dossierep_id',
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
			'Nonorientationproep66' => array(
				'className' => 'Nonorientationproep66',
				'foreignKey' => 'dossierep_id',
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
			// Thèmes 93
			'Reorientationep93' => array(
				'className' => 'Reorientationep93',
				'foreignKey' => 'dossierep_id',
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
			'Nonrespectsanctionep93' => array(
				'className' => 'Nonrespectsanctionep93',
				'foreignKey' => 'dossierep_id',
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
			'Nonorientationproep93' => array(
				'className' => 'Nonorientationproep93',
				'foreignKey' => 'dossierep_id',
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
			'Signalementep93' => array(
				'className' => 'Signalementep93',
				'foreignKey' => 'dossierep_id',
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
			'Contratcomplexeep93' => array(
				'className' => 'Contratcomplexeep93',
				'foreignKey' => 'dossierep_id',
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
			// Thèmes 58
			'Nonorientationproep58' => array(
				'className' => 'Nonorientationproep58',
				'foreignKey' => 'dossierep_id',
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
			'Regressionorientationep58' => array(
				'className' => 'Regressionorientationep58',
				'foreignKey' => 'dossierep_id',
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
			'Sanctionep58' => array(
				'className' => 'Sanctionep58',
				'foreignKey' => 'dossierep_id',
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
			'Sanctionrendezvousep58' => array(
				'className' => 'Sanctionrendezvousep58',
				'foreignKey' => 'dossierep_id',
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
			'Passagecommissionep' => array(
				'className' => 'Passagecommissionep',
				'foreignKey' => 'dossierep_id',
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

		/*public $hasAndBelongsToMany = array(
			'Commissionep' => array(
				'className' => 'Commissionep',
				'joinTable' => 'passagescommissionseps',
				'foreignKey' => 'dossierep_id',
				'associationForeignKey' => 'commissionep_id',
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
			)
		);*/

		/**
		* Retourne la liste des thèmes traités par le CG suivant la valeur
		* de la configuration Cg.departement
		*/

		public function themesCg() {
			$return = array();
			$enums = $this->enums();
			$ereg = '/eps'.Configure::read( 'Cg.departement' ).'$/';

			foreach( $enums['Dossierep']['themeep'] as $key => $value ) {
				if( preg_match( $ereg, $key ) ) {
					$return[$key] = $value;
				}
			}

			return $return;
		}

		/**
		*
		*/

		public function themeTraite( $id ) {
			$dossierep = $this->find(
				'first',
				array(
					'conditions' => array(
						"{$this->alias}.{$this->primaryKey}" => $id
					),
					'contain' => array(
						'Passagecommissionep' => array(
							'Commissionep' => array(
								'Ep' => array(
									'Regroupementep'
								)
							)
						)
					)
				)
			);

			$themes = $this->Passagecommissionep->Commissionep->Ep->Regroupementep->themes();
			$themesTraites = array();

			foreach( $themes as $key => $theme ) {
				if( Inflector::tableize( $theme ) == $dossierep['Dossierep']['themeep'] && in_array( $dossierep['Passagecommissionep'][0]['Commissionep']['Ep']['Regroupementep'][$theme], array( 'decisionep', 'decisioncg' ) ) ) {
					$themesTraites[$theme] = $dossierep['Passagecommissionep'][0]['Commissionep']['Ep']['Regroupementep'][$theme];
				}
			}
			return $themesTraites;
		}

		/**
		*
		*/

		public function prepareFormDataUnique( $dossierep_id, $dossier, $niveauDecision ) {
			$data = array();

			foreach( $this->themeTraite( $dossierep_id ) as $theme => $niveauDecision ) {
				$model = Inflector::classify( $theme );

				$data = Set::merge(
					$data,
					$this->{$model}->prepareFormDataUnique(
						$dossierep_id,
						$dossier,
						$niveauDecision
					)
				);
			}

			return $data;
		}

		/**
		*
		*/

		public function sauvegardeUnique( $dossierep_id, $data, $niveauDecision ) {
			$success = true;

			foreach( $this->themeTraite( $dossierep_id ) as $theme => $decision ) {
				$model = Inflector::classify( $theme );
				$success = $this->{$model}->saveDecisionUnique( $data, $niveauDecision ) && $success;
			}

			return $success;
		}

		/**
		* Retourne un array de chaînes de caractères indiquant pourquoi on ne
		* peut pas créer de dossier d'EP pour la personne.
		*
		* Les valeurs possibles sont:
		* 	- Personne.id: la personne n'existe pas en base ou n'a pas de prestation RSA
		* 	- Situationdossierrsa.etatdosrsa: le dossier ne se trouve pas dans un état ouvert
		* 	- Prestation.rolepers: la personne n'est ni demandeur ni conjoint RSA
		* 	- Calculdroitrsa.toppersdrodevorsa: la personne n'est pas soumise à droits et devoirs
		*
		* @param integer $personne_id L'id technique de la personne
		* @return array
		* @access public
		*/

		public function erreursCandidatePassage( $personne_id ) {
			$result = $this->Personne->find(
				'first',
				array(
					'fields' => array(
						'Situationdossierrsa.etatdosrsa',
						'Prestation.rolepers',
						'Calculdroitrsa.toppersdrodevorsa'
					),
					'joins' => array(
						array(
							'table'      => 'foyers',
							'alias'      => 'Foyer',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Foyer.id = Personne.foyer_id'
							)
						),
						array(
							'table'      => 'situationsdossiersrsa',
							'alias'      => 'Situationdossierrsa',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Foyer.dossier_id = Situationdossierrsa.dossier_id',
							)
						),
						array(
							'table'      => 'prestations',
							'alias'      => 'Prestation',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Personne.id = Prestation.personne_id',
								'Prestation.natprest' => 'RSA',
							)
						),
						array(
							'table'      => 'calculsdroitsrsa',
							'alias'      => 'Calculdroitrsa',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Personne.id = Calculdroitrsa.personne_id'
							)
						),
					),
					'conditions' => array(
						'Personne.id' => $personne_id,
					),
					'contain' => false
				)
			);
			$result = Hash::flatten( $result );

			$errors = array();
			if( empty( $result ) ) {
				$errors[] = 'Personne.id';
			}
			else {
				if( !in_array( $result['Situationdossierrsa.etatdosrsa'], ClassRegistry::init( 'Situationdossierrsa' )->etatOuvert() ) ) {
					$errors[] = 'Situationdossierrsa.etatdosrsa';
				}
				if( !in_array( $result['Prestation.rolepers'], array( 'DEM', 'CJT' ) ) ) {
					$errors[] = 'Prestation.rolepers';
				}
				if( empty( $result['Calculdroitrsa.toppersdrodevorsa'] ) ) {
					$errors[] = 'Calculdroitrsa.toppersdrodevorsa';
				}
			}

			return $errors;
		}

		/**
		* Récupération des informations propres au dossier devant passer en EP
		* après liaison avec la commission d'EP
		*/

		public function getConvocationBeneficiaireEpPdf( $passagecommissionep_id ) {
			$passagecommission = $this->Passagecommissionep->find(
				'first',
				array(
					'conditions' => array( 'Passagecommissionep.id' => $passagecommissionep_id ),
					'contain' => array(
						'Dossierep'
					)
				)
			);

			if( empty( $passagecommission ) ) {
				return false;
			}

			$theme = Inflector::classify( $passagecommission['Dossierep']['themeep'] );

			if( empty( $theme ) ) {
				return false;
			}

			$this->Passagecommissionep->updateAllUnBound(
				array( 'Passagecommissionep.impressionconvocation' => "'".date( 'Y-m-d' )."'" ),
				array(
					'"Passagecommissionep"."id"' => $passagecommissionep_id,
					'"Passagecommissionep"."impressionconvocation" IS NULL'
				)
			);

			$pdf = $this->{$theme}->getConvocationBeneficiaireEpPdf( $passagecommissionep_id );

			if( empty( $pdf ) ) {
				$this->Passagecommissionep->updateAllUnBound(
					array( 'Passagecommissionep.impressionconvocation' => null ),
					array(
						'"Passagecommissionep"."id"' => $passagecommissionep_id,
						'"Passagecommissionep"."impressionconvocation" IS NOT NULL'
					)
				);
			}

			return $pdf;
		}

		/**
		*
		*/

		public function getDecisionPdf( $passagecommissionep_id, $user_id = null  ) {
			$passagecommission = $this->Passagecommissionep->find(
				'first',
				array(
					'conditions' => array( 'Passagecommissionep.id' => $passagecommissionep_id ),
					'contain' => array(
						'Dossierep'
					)
				)
			);

			$pdf = false;
			if( !empty( $passagecommission ) ) {
				$theme = Inflector::classify( $passagecommission['Dossierep']['themeep'] );
				$this->Passagecommissionep->updateAllUnBound(
					array( 'Passagecommissionep.impressiondecision' => "'".date( 'Y-m-d' )."'" ),
					array(
						'"Passagecommissionep"."id"' => $passagecommissionep_id,
						'"Passagecommissionep"."impressiondecision" IS NULL'
					)
				);

				$pdf = $this->{$theme}->getDecisionPdf( $passagecommissionep_id, $user_id );
			}

			return $pdf;
		}

		/**
		*
		*/
		public function qdDossiersepsOuverts( $personne_id ) {
			$themes = array_keys( $this->themesCg() );

			return array(
				'conditions' => array(
					'Dossierep.personne_id' => $personne_id,
					'Dossierep.themeep' => $themes,
					'Dossierep.id NOT IN ( '.$this->Passagecommissionep->sq(
						array(
							'alias' => 'passagescommissionseps',
							'fields' => array(
								'passagescommissionseps.dossierep_id'
							),
							'conditions' => array(
								'passagescommissionseps.etatdossierep' => array( 'traite', 'annule' )
							)
						)
					).' )'
				),
				'contain' => false
			);
		}



		/**
		* Vérifie l'intervalle, entre la date du jour et la date de création du dossier EP
		* le dossier d'EP n'apparaîtra que 1 mois et demi après sa création dans la liste des dossiers devant passer en EP
		*/

		public function checkConfigDossierepDelaiavantselection() {
			$delaiavantselection = Configure::read( 'Dossierep.delaiavantselection' );

			if( is_null( $delaiavantselection ) ) {
				return true;
			}

			return $this->_checkPostgresqlIntervals( array( 'Dossierep.delaiavantselection'  ), true );

			/*if( $cg66 ) {
				return 'Oubli de paramétrage: veuillez vérifier que le champ <em>Dossierep.delaiavantselection</em> dans le fichier webrsa.inc est correctement renseigné';
			}*/
			return true;
		}

		public function checkPostgresqlIntervals() {
			$value = Configure::read( 'Dossierep.delaiavantselection' );

			if( is_null( $value ) ) {
				return array();
			}
			else {
				return $this->_checkPostgresqlIntervals(
					array( 'Dossierep.delaiavantselection' )
				);
			}
		}

		/**
		 * Retourne une sous-requête permettant d'obtenir l'id du dossier d'EP de la personne
		 * associé à la commission d'EP la plus récente.
		 *
		 * @param string $personneIdAlias Le champ désignant l'id de la personne
		 * @return string
		 */
		public function sqDernierPassagePersonne( $personneIdAlias = 'Personne.id' ) {
			// Dossierep INNER Passagecommissionep INNER Commissionep ORDER BY Commissionep.dateseance DESC
			return $this->sq(
				array(
					'fields' => array( 'dossierseps.id' ),
					'alias' => 'dossierseps',
					'joins' => array(
						array_words_replace( $this->join( 'Passagecommissionep', array( 'type' => 'INNER' ) ), array( 'Dossierep' => 'dossierseps', 'Passagecommissionep' => 'passagescommissionseps' ) ),
						array_words_replace( $this->Passagecommissionep->join( 'Commissionep', array( 'type' => 'INNER' ) ), array( 'Passagecommissionep' => 'passagescommissionseps', 'Commissionep' => 'commissionseps' ) ),
					),
					'contain' => false,
					'conditions' => array(
						"dossierseps.personne_id = {$personneIdAlias}"
					),
					'order' => array( 'commissionseps.dateseance DESC' ),
					'limit' => 1
				)
			);
		}

		/**
		 * On complète le querydata avec les jointures permettant de savoir de
		 * quelle décision d'EP vient une réorientation, pour le CG connecté.
		 *
		 * @param array $querydata
		 * @return array
		 */
		public function completeQdReorientation( array $querydata ) {
			// Réorientation suite à passage en EP (pour le CG connecté) ?
			$or = array();
			$conditionsDossierep = array();
			$modelesDecision = array();
			$hasMany = $this->Personne->Orientstruct->hasMany;

			foreach( $hasMany as $aliasName => $params ) {
				if( preg_match( '/ep'.Configure::read( 'Cg.departement' ).'nv$/', $aliasName ) ) {
					$modelesDecision[$params['className']] = 'Decision'.Inflector::underscore( $params['className'] );

					$querydata['joins'][] = $this->Personne->Orientstruct->join( $aliasName, array( 'type' => 'LEFT OUTER' ) );

					$or[] = array(
						'OR' => array(
							"{$aliasName}.nvorientstruct_id IS NULL",
							"{$aliasName}.nvorientstruct_id = Orientstruct.id"
						)
					);

					$assoc = $this->Personne->Orientstruct->{$aliasName}->join( 'Dossierep', array( 'type' => 'LEFT OUTER' ) );
					$conditionsDossierep[] = $assoc['conditions'];
				}
			}

			if( !empty( $or ) ) {
				$querydata['conditions'][] = array( 'OR' => $or );

				// Jointure avec le dossier EP pour les différentes thématiques
				$assoc = $this->Personne->join( 'Dossierep', array( 'type' => 'LEFT OUTER' ) );
				$assoc['conditions'] = array( 'OR' => $conditionsDossierep );
				$querydata['joins'][] = $assoc;
				$querydata['fields'][] = 'Dossierep.themeep';

				// Recherche du passage en commission
				$querydata['joins'][] = $this->join( 'Passagecommissionep', array( 'type' => 'LEFT OUTER' ) );
				$sqDernier = $this->Passagecommissionep->sqDernier();
				$querydata['conditions'][] = array(
					'OR' => array(
						'Passagecommissionep.id IS NULL',
						"Passagecommissionep.id IN ( {$sqDernier} )"
					)
				);

				$querydata['joins'][] = $this->Passagecommissionep->join( 'Commissionep', array( 'type' => 'LEFT OUTER' ) );
				$querydata['fields'][] = 'Commissionep.dateseance';
			}

			if( !empty( $modelesDecision ) ) {
				foreach( $modelesDecision as $modeleThematique => $modeleDecision ) {
					$querydata['joins'][] = $this->Passagecommissionep->join(
						$modeleDecision,
						array(
							'type' => 'LEFT OUTER',
							'conditions' => array(
								'Dossierep.id IS NOT NULL',
								'Dossierep.themeep' => Inflector::tableize( $modeleThematique )
							)
						)
					);
					$querydata['conditions'][] = $this->Passagecommissionep->{$modeleDecision}->conditionsDerniere();

					$querydata['fields'][] = "{$modeleDecision}.decision";
					$querydata['fields'][] = "{$modeleDecision}.etape";
				}
			}

			return $querydata;
		}

		/**
		 * Permet de lister les dossiers d'EP pouvant être supprimés, c'est-à-dire
		 * qui soit:
		 *   - ne sont pas attachés à une commission
		 *   - soit sont attachés à une commission qui n'a pas encore débuté
		 *   - soit sont attachés à une (des) commission(s) terminée(s) et comportant
		 *     une décision de report ou d'annulation.
		 *
		 * @param array $search La liste des filtres venant du moteur de recherche
		 * @return array
		 */
		public function searchAdministration( array $search ) {
			$this->forceVirtualFields = true;
			$this->virtualFields['nb_passages_commission'] = $this->Passagecommissionep->sq(
				array(
					'alias' => 'passagescommissionseps',
					'fields' => array( 'COUNT(passagescommissionseps.id)' ),
					'contain' => false,
					'conditions' => array(
						'passagescommissionseps.dossierep_id = Dossierep.id'
					)
				)
			);

			$query = array(
				'fields' => array(
					'Dossierep.id',
					'Commissionep.id',
					'Passagecommissionep.id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Commissionep.dateseance',
					'Commissionep.etatcommissionep',
					'Dossierep.created',
					'Dossierep.themeep',
					'Dossierep.nb_passages_commission',
				),
				'joins' => array(
					$this->join( 'Personne', array( 'type' => 'INNER' ) ),
					$this->join( 'Passagecommissionep', array( 'type' => 'LEFT OUTER' ) ),
					$this->Passagecommissionep->join( 'Commissionep', array( 'type' => 'LEFT OUTER' ) ),
					$this->Passagecommissionep->Commissionep->join( 'Ep', array( 'type' => 'LEFT OUTER' ) ),
					$this->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
					$this->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
					$this->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
					$this->Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
				),
				'conditions' => array(
					array(
						'OR' => array(
							'Adressefoyer.id IS NULL',
							'Adressefoyer.id IN ( '.$this->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
						)
					),
					array(
						'OR' => array(
							'Passagecommissionep.id IS NULL',
							'Passagecommissionep.id IN ('.$this->Passagecommissionep->sqDernier().' )'
						)
					),
				),
				'contain' => false,
				'limit' => 10
			);

			// On peut supprimer des dossiers qui ne sont pas encore attaché à une
			// commission, ou attachés à une commission qui n'a pas encore débuté
			// ou qui a été menée à terme (traitée, annulée, reportée).
			$query['conditions'][] = array(
				'OR' => array(
					'Commissionep.id IS NULL',
					'NOT' => array(
						'Commissionep.etatcommissionep' => array( 'valide', 'presence', 'decisionep', 'traiteep', 'decisioncg' )
					)
				)
			);

			$case = '';
			$themes = array_keys( $this->themesCg() );
			$conditions = array();

			foreach( $themes as $theme ) {
				$tableNameDecision = "decisions{$theme}";
				$modelNameDecision = Inflector::classify( $tableNameDecision );

				$sqlDerniereDecicion = $this->Passagecommissionep->{$modelNameDecision}->sq(
					array(
						'alias' => $tableNameDecision,
						'fields' => array( "{$tableNameDecision}.id" ),
						'contain' => false,
						'conditions' => array(
							"{$tableNameDecision}.passagecommissionep_id = Passagecommissionep.id"
						),
						'order' => array( "{$tableNameDecision}.etape DESC" ),
						'limit' => 1
					)
				);

				$query['joins'][] = $this->Passagecommissionep->join(
					$modelNameDecision,
					array(
						'type' => 'LEFT OUTER',
						'conditions' => array(
							"{$modelNameDecision}.id IN ( {$sqlDerniereDecicion} )"
						)
					)
				);

				$case .= " WHEN \"{$modelNameDecision}\".\"decision\" IS NOT NULL THEN \"{$modelNameDecision}\".\"decision\"::TEXT ";

				$conditions[] = array(
					'OR' => array(
						"{$modelNameDecision}.decision IS NULL",
						"{$modelNameDecision}.decision" => array( 'annule', 'reporte' ),
					)
				);
			}

			$query['fields'][] = "( CASE {$case} ELSE NULL END ) AS \"Decisionthematique__decision\"";

			$query['conditions'][] = $conditions;

			$query['conditions'] = $this->conditionsAdresse( $query['conditions'], $search );
			$query['conditions'] = $this->conditionsPersonneFoyerDossier( $query['conditions'], $search );
			$query['conditions'] = $this->conditionsDernierDossierAllocataire( $query['conditions'], $search );

			// Filtres de recherche spécifiques aux EPs

			// 1. Filtrer par EP
			$regroupementep_id = Hash::get( $search, 'Ep.regroupementep_id' );
			if( !empty( $regroupementep_id ) ) {
				$query['conditions']['Ep.regroupementep_id'] = $regroupementep_id;
			}

			$name = Hash::get( $search, 'Ep.name' );
			if( !empty( $name ) ) {
				$query['conditions']['Ep.name'] = $name;
			}

			$identifiant = Hash::get( $search, 'Ep.identifiant' );
			if( !empty( $identifiant ) ) {
				$query['conditions']['Ep.identifiant'] = $identifiant;
			}

			// 2. Filtrer par commission d'EP
			$name = Hash::get( $search, 'Commissionep.name' );
			if( !empty( $name ) ) {
				$query['conditions']['Commissionep.name'] = $name;
			}

			$identifiant = Hash::get( $search, 'Commissionep.identifiant' );
			if( !empty( $identifiant ) ) {
				$query['conditions']['Commissionep.identifiant'] = $identifiant;
			}

			$query['conditions'] = $this->conditionsDates( $query['conditions'], $search, 'Commissionep.dateseance' );

			// 3. Filtrer par dossier d'EP
			$themeep = Hash::get( $search, 'Dossierep.themeep' );
			if( !empty( $themeep ) ) {
				$query['conditions']['Dossierep.themeep'] = $themeep;
			}

			$query = $this->Personne->PersonneReferent->completeQdReferentParcours( $query, $search );

			return $query;
		}
	}
?>