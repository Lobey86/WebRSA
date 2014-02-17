<?php
	/**
	 * Code source de la classe Structurereferente.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Structurereferente s'occupe de la gestion des structures référentes.
	 *
	 * @package app.Model
	 */
	class Structurereferente extends AppModel
	{
		public $name = 'Structurereferente';

		public $displayField = 'lib_struc';

		public $order = array( 'lib_struc ASC' );

		public $actsAs = array(
			'Formattable' => array(
				'phone' => array( 'numtel' )
			),
			'Pgsqlcake.PgsqlAutovalidate',
			'Validation.ExtraValidationRules',
		);

		public $validate = array(
			'numtel' => array(
				'phoneFr' => array(
					'rule' => array( 'phoneFr' ),
					'allowEmpty' => true,
				)
			)
		);

		public $belongsTo = array(
			'Typeorient' => array(
				'className' => 'Typeorient',
				'foreignKey' => 'typeorient_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasOne = array(
			'Contratinsertion' => array(
				'className' => 'Contratinsertion',
				'foreignKey' => 'structurereferente_id',
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
			'Cui' => array(
				'className' => 'Cui',
				'foreignKey' => 'structurereferente_id',
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
			'Bilanparcours66' => array(
				'className' => 'Bilanparcours66',
				'foreignKey' => 'structurereferente_id',
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
			'Entretien' => array(
				'className' => 'Entretien',
				'foreignKey' => 'structurereferente_id',
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
			'Apre' => array(
				'className' => 'Apre',
				'foreignKey' => 'structurereferente_id',
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
			'Permanence' => array(
				'className' => 'Permanence',
				'foreignKey' => 'structurereferente_id',
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
			'PersonneReferent' => array(
				'className' => 'PersonneReferent',
				'foreignKey' => 'structurereferente_id',
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
			'Propopdo' => array(
				'className' => 'Propopdo',
				'foreignKey' => 'structurereferente_id',
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
			'Rendezvous' => array(
				'className' => 'Rendezvous',
				'foreignKey' => 'structurereferente_id',
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
			'Referent' => array(
				'className' => 'Referent',
				'foreignKey' => 'structurereferente_id',
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
			'Orientstruct' => array(
				'className' => 'Orientstruct',
				'foreignKey' => 'structurereferente_id',
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
				'foreignKey' => 'structurereferente_id',
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
			'Decisionpropoorientsocialecov58' => array(
				'className' => 'Decisionpropoorientsocialecov58',
				'foreignKey' => 'structurereferente_id',
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
			'User' => array(
				'className' => 'User',
				'foreignKey' => 'structurereferente_id',
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
			'Decisionnonorientationproep58' => array(
				'className' => 'Decisionnonorientationproep58',
				'foreignKey' => 'structurereferente_id',
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
			'Decisionnonorientationproep93' => array(
				'className' => 'Decisionnonorientationproep93',
				'foreignKey' => 'structurereferente_id',
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
			'Zonegeographique' => array(
				'className' => 'Zonegeographique',
				'joinTable' => 'structuresreferentes_zonesgeographiques',
				'foreignKey' => 'structurereferente_id',
				'associationForeignKey' => 'zonegeographique_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'StructurereferenteZonegeographique'
			)
		);

		/**
		 *
		 * @return array
		 */
		public function list1Options() {
			$cacheKey = 'structurereferente_list1_options';
			$results = Cache::read( $cacheKey );

			if( $results === false ) {
				$tmp = $this->find(
					'all',
					array(
						'conditions' => array( 'Structurereferente.actif' => 'O' ),
						'fields' => array(
							'Structurereferente.id',
							'Structurereferente.typeorient_id',
							'Structurereferente.lib_struc'
						),
						'order'  => array( 'Structurereferente.lib_struc ASC' ),
						'recursive' => -1
					)
				);

				$results = array();
				foreach( $tmp as $key => $value ) {
					$results[$value['Structurereferente']['typeorient_id'].'_'.$value['Structurereferente']['id']] = $value['Structurereferente']['lib_struc'];
				}

				Cache::write( $cacheKey, $results );
				ModelCache::write( $cacheKey, array( 'Structurereferente', 'Typeorient' ) );
			}

			return $results;
		}

		/**
		 * Récupère la liste des structures référentes groupées par type d'orientation.
		 * Cette liste est mise en cache et on se sert de la classe ModelCache
		 * pour savoir quelles clés de cache supprimer lorsque les données de ce
		 * modèle changent.
		 *
		 * @return array
		 */
		public function listOptions() {
			$cacheKey = 'structurereferente_list_options';
			$results = Cache::read( $cacheKey );

			if( $results === false ) {
				$results = $this->find(
					'list',
					array(
						'fields' => array(
							'Structurereferente.id',
							'Structurereferente.lib_struc',
							'Typeorient.lib_type_orient'
						),
						'recursive' => -1,
						'joins' => array(
							$this->join( 'Typeorient', array( 'type' => 'INNER' ) )
						),
						'order' => array(
							'Typeorient.lib_type_orient ASC',
							'Structurereferente.lib_struc'
						),
						'conditions' => array(
							'Structurereferente.actif' => 'O'
						)
					)
				);
				Cache::write( $cacheKey, $results );
				ModelCache::write( $cacheKey, array( 'Structurereferente', 'Typeorient' ) );
			}
			return $results;
		}

		/**
		*
		*/

		public function listePourApre() {
			///Récupération de la liste des référents liés à l'APRE
			$structsapre = $this->Structurereferente->find( 'list', array( 'conditions' => array( 'Structurereferente.apre' => 'O' ) ) );
			$this->set( 'structsapre', $structsapre );
		}

		/**
		*   Retourne la liste des structures référentes filtrée selon un type donné
		* @param array $types ( array( 'apre' => true, 'contratengagement' => true ) )
		* par défaut, toutes les clés sont considérées commen étant à false
		*/

		public function listeParType( $types ) {
			$conditions = array();

			foreach( array( 'apre', 'contratengagement', 'orientation', 'pdo', 'cui' ) as $type ) {
				$bool = Set::classicExtract( $types, $type );
				if( !empty( $bool ) ) {
					$conditions[] = "Structurereferente.{$type} = 'O'";
				}
			}

			return $this->find( 'list', array( 'conditions' => $conditions, 'recursive' => -1 ) );
		}

		/**
		 * Retourne les enregistrements pour lesquels une erreur de paramétrage
		 * a été détectée.
		 * Il s'agit des structures pour lesquelles on ne sait pas si elles gèrent
		 * l'APRE ni le CER.
		 *
		 * @return array
		 */
		public function storedDataErrors() {
			return $this->find(
				'all',
				array(
					'fields' => array(
						'Structurereferente.id',
						'Structurereferente.lib_struc',
						'Structurereferente.apre',
						'Structurereferente.contratengagement',
						'Structurereferente.cui'
					),
					'recursive' => -1,
					'conditions' => array(
						'OR' => array(
							'Structurereferente.apre' => NULL,
							'Structurereferente.contratengagement' => NULL,
							'Structurereferente.cui' => NULL
						)
					),
					'contain' => false
				)
			);
		}

		/**
		 * Suppression et regénération du cache.
		 *
		 * @return boolean
		 */
		protected function _regenerateCache() {
			$this->_clearModelCache();

			// Regénération des éléments du cache.
			$success = true;

			if( $this->alias == 'Structurereferente' ) {
				$success = ( $this->listOptions() !== false )
					&& ( $this->list1Options() !== false );
			}

			return $success;
		}

		/**
		 * Exécute les différentes méthods du modèle permettant la mise en cache.
		 * Utilisé au préchargement de l'application (/prechargements/index).
		 *
		 * @return boolean true en cas de succès, false en cas d'erreur,
		 * 	null pour les fonctions vides.
		 */
		public function prechargement() {
			$success = $this->_regenerateCache();
			return $success;
		}




		/**
		*	Recherche des structures dans le paramétrage de l'application
		*
		*/
		public function search( $criteres ) {
			/// Conditions de base
			$conditions = array();

			// Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
			$filtersStruct = array();
			foreach( array( 'lib_struc', 'ville' ) as $critereStructure ) {
				if( isset( $criteres['Structurereferente'][$critereStructure] ) && !empty( $criteres['Structurereferente'][$critereStructure] ) ) {
					$conditions[] = 'Structurereferente.'.$critereStructure.' ILIKE \''.$this->wildcard( $criteres['Structurereferente'][$critereStructure] ).'\'';
				}
			}

			// Critère sur la structure référente de l'utilisateur
			if( isset( $criteres['Structurereferente']['typeorient_id'] ) && !empty( $criteres['Structurereferente']['typeorient_id'] ) ) {
				$conditions[] = array( 'Structurereferente.typeorient_id' => $criteres['Structurereferente']['typeorient_id'] );
			}


			$query = array(
				'fields' => array_merge(
					$this->fields(),
					$this->Typeorient->fields()
				),
				'order' => array( 'Structurereferente.lib_struc ASC' ),
				'joins' => array(
					$this->join( 'Typeorient', array( 'type' => 'INNER' ) )
				),
				'recursive' => -1,
				'conditions' => $conditions
			);

			return $query;
		}
	}
?>