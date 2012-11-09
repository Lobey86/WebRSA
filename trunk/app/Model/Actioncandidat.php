<?php
	/**
	 * Code source de la classe Actioncandidat.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Actioncandidat s'occupe de la gestion des fiches de candidature.
	 *
	 * @package app.Model
	 */
	class Actioncandidat extends AppModel
	{
		public $name = 'Actioncandidat';

		public $displayField = 'name';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate2',
			'ValidateTranslate',
			'Formattable',
			'Enumerable' => array(
				'fields' => array(
					'contractualisation',
					'correspondantaction',
					'hasfichecandidature',
					'typeaction',
					'actif' => array( 'type' => 'no', 'domain' => 'default' ),
					'haspiecejointe' => array(
						'domain' => 'actioncandidat'
					),
				)
			)
		);

		public $validate = array(
			'nbpostedispo' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'hasfichecandidature', true, array( '1' ) ),
					'message' => 'Champ obligatoire',
				),
			),
			'chargeinsertion_id' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'hasfichecandidature', true, array( '1' ) ),
					'message' => 'Champ obligatoire',
				),
			),
			'secretaire_id' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'hasfichecandidature', true, array( '1' ) ),
					'message' => 'Champ obligatoire',
				),
			),
			'contractualisation' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'hasfichecandidature', true, array( '1' ) ),
					'message' => 'Champ obligatoire',
				),
			),
			'lieuaction' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'hasfichecandidature', true, array( '1' ) ),
					'message' => 'Champ obligatoire',
				),
			),
			'cantonaction' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'hasfichecandidature', true, array( '1' ) ),
					'message' => 'Champ obligatoire',
				),
			),
			'ddaction' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'hasfichecandidature', true, array( '1' ) ),
					'message' => 'Champ obligatoire',
				),
			),
			'dfaction' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'hasfichecandidature', true, array( '1' ) ),
					'message' => 'Champ obligatoire',
				),
			),
			'contactpartenaire_id' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'hasfichecandidature', true, array( '1' ) ),
					'message' => 'Champ obligatoire',
				),
			),
			'typeaction' => array(
				'notEmptyIf' => array(
                    'rule' => array( 'notEmptyIf', 'hasfichecandidature', true, array( '1' ) ),
                    'message' => 'Champ obligatoire',
                ),
			)
		);

		public $belongsTo = array(
			'Contactpartenaire' => array(
				'className' => 'Contactpartenaire',
				'foreignKey' => 'contactpartenaire_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Chargeinsertion' => array(
				'className' => 'User',
				'foreignKey' => 'chargeinsertion_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Secretaire' => array(
				'className' => 'User',
				'foreignKey' => 'secretaire_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

		public $hasAndBelongsToMany = array(
            'Motifsortie' => array(
				'className' => 'Motifsortie',
				'joinTable' => 'actionscandidats_motifssortie',
				'foreignKey' => 'actioncandidat_id',
				'associationForeignKey' => 'motifsortie_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'ActioncandidatMotifsortie'
			),
			'Partenaire' => array(
				'className' => 'Partenaire',
				'joinTable' => 'actionscandidats_partenaires',
				'foreignKey' => 'actioncandidat_id',
				'associationForeignKey' => 'partenaire_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'ActioncandidatPartenaire'
			),
			'Personne' => array(
				'className' => 'Personne',
				'joinTable' => 'actionscandidats_personnes',
				'foreignKey' => 'actioncandidat_id',
				'associationForeignKey' => 'personne_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'ActioncandidatPersonne'
			),
			'Zonegeographique' => array(
				'className' => 'Zonegeographique',
				'joinTable' => 'actionscandidats_zonesgeographiques',
				'foreignKey' => 'actioncandidat_id',
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
				'with' => 'ActioncandidatZonegeographique'
			)
		);



		public $hasMany = array(
			'Fichiermodule' => array(
				'className' => 'Fichiermodule',
				'foreignKey' => false,
				'dependent' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'Actioncandidat\'',
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
            'Contratinsertion' => array(
				'className' => 'Contratinsertion',
				'foreignKey' => 'actioncandidat_id',
				'dependent' => false,
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
				'foreignKey' => 'actioncandidat_id',
				'dependent' => false,
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


		/**
		*
		*/

		public function listePourFicheCandidature( $codelocalite, $isactif, $hasFiche ) {
			$conditions = array();
            if( Configure::read( 'Cg.departement') != 66 ) {
                $conditions[] = 'Actioncandidat.id IN (
							'.$this->ActioncandidatZonegeographique->sq(
								array(
									'alias' => 'actionscandidats_zonesgeographiques',
									'fields' => array( 'actionscandidats_zonesgeographiques.actioncandidat_id' ),
									'conditions' => array(
										'actionscandidats_zonesgeographiques.zonegeographique_id IN ('.ClassRegistry::init( 'Canton' )->sq(
											array(
												'alias' => 'cantons',
												'fields' => array( 'cantons.zonegeographique_id' ),
												'conditions' => array(
													'cantons.numcomptt' => $codelocalite
												),
												'contain' => false
											)
										).' )'
									)
								)
							).'
						)';
            }

            $actionscandidats = $this->find(
				'list',
				array(
					'conditions' => array(
						'Actioncandidat.actif' => $isactif,
						'Actioncandidat.hasfichecandidature' => $hasFiche,
                        $conditions
					),
					'recursive' => -1,
					'order' => 'name'
				)
			);

			return $actionscandidats;
		}

		public function afterFind($results,$primary = false)
		{
			$resultset = parent::afterFind( $results, $primary );

			if( !empty( $resultset ) )
			{
				foreach( $resultset as $i => $results )
				{
					if( isset( $results['Actioncandidat']['id'] ) && isset( $results['Actioncandidat']['themecode'] ) )
					{
						$codeaction = $results['Actioncandidat']['themecode'].$results['Actioncandidat']['codefamille'].$results['Actioncandidat']['numcodefamille'];
						$results['Actioncandidat']['codeaction'] = $codeaction;
					}
					$resultset[$i] = $results;
				}
			}
			return $resultset;
		}



		/**
		*
		*/

		public function listActionParPartenaire() {
			$tmp = $this->find(
				'all',
				array (
					'fields' => array(
						'Actioncandidat.id',
						'Partenaire.id',
						'Actioncandidat.name'
					),
					'joins' => array(
						$this->join( 'Contactpartenaire', array( 'type' => 'INNER' ) ),
						$this->Contactpartenaire->join( 'Partenaire', array( 'type' => 'INNER' ) )
					),
					'order' => 'Actioncandidat.name ASC',
					'conditions' => array(
// 						'Actioncandidat.actif' => 'O',
						'Actioncandidat.hasfichecandidature' => '1'
					)
				)
			);

			$results = array();
			foreach( $tmp as $key => $value ) {
				$results[$value['Partenaire']['id'].'_'.$value['Actioncandidat']['id']] = $value['Actioncandidat']['name'];
			}
			return $results;
		}

		/**
		 * Renvoit une liste clé / valeur avec clé qui est l'id du motif de sortie
		 * et la valeur qui est le name du motif de sortie.
		 * Utilisé pour les valeurs des input select.
		 *
		 * @return array
		 */
		public function listOptions() {
			$cacheKey = 'actionscandidats_list_options';
			$results = Cache::read( $cacheKey );

			if( $results === false ) {
				$results = $this->find(
					'list',
					array (
						'contain' => false,
						'order' => 'Actioncandidat.name ASC',
					)
				);

				Cache::write( $cacheKey, $results );
				ModelCache::write( $cacheKey, array( 'Actioncandidat' ) );
			}

			return $results;
		}

		/**
		 * Suppression et regénération du cache.
		 *
		 * @return boolean
		 */
		protected function _regenerateCache() {
			$this->_clearModelCache();

			// Regénération des éléments du cache.
			$success = ( $this->listOptions() !== false );

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
	}
?>