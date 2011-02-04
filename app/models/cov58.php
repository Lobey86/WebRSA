<?php
	/**
	* Commision d'orientation et validation (COV)
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.models
	*/

	class Cov58 extends AppModel
	{
		public $name = 'Cov58';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Formattable' => array(
				'suffix' => array(
					'structurereferente_id'
				)
			),
			'Enumerable' => array(
				'fields' => array(
					'etatcov'
				)
			)
		);
		
		public $hasMany = array(
			'Dossiercov58' => array(
				'className' => 'Dossiercov58',
				'foreignKey' => 'cov58_id',
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

		public function search( $criterescov58 ) {
			/// Conditions de base

			$conditions = array();

			if ( isset($criterescov58['Cov58']['name']) && !empty($criterescov58['Cov58']['name']) ) {
				$conditions[] = array('Cov58.name'=>$criterescov58['Cov58']['name']);
			}

			if ( isset($criterescov58['Cov58']['lieu']) && !empty($criterescov58['Cov58']['lieu']) ) {
				$conditions[] = array('Cov58.lieu'=>$criterescov58['Cov58']['lieu']);
			}

			/// Critères sur le Comité - date du comité
			if( isset( $criterescov58['Cov58']['datecommission'] ) && !empty( $criterescov58['Cov58']['datecommission'] ) ) {
				$valid_from = ( valid_int( $criterescov58['Cov58']['datecommission_from']['year'] ) && valid_int( $criterescov58['Cov58']['datecommission_from']['month'] ) && valid_int( $criterescov58['Cov58']['datecommission_from']['day'] ) );
				$valid_to = ( valid_int( $criterescov58['Cov58']['datecommission_to']['year'] ) && valid_int( $criterescov58['Cov58']['datecommission_to']['month'] ) && valid_int( $criterescov58['Cov58']['datecommission_to']['day'] ) );
				if( $valid_from && $valid_to ) {
					$conditions[] = 'Cov58.datecommission BETWEEN \''.implode( '-', array( $criterescov58['Cov58']['datecommission_from']['year'], $criterescov58['Cov58']['datecommission_from']['month'], $criterescov58['Cov58']['datecommission_from']['day'] ) ).'\' AND \''.implode( '-', array( $criterescov58['Cov58']['datecommission_to']['year'], $criterescov58['Cov58']['datecommission_to']['month'], $criterescov58['Cov58']['datecommission_to']['day'] ) ).'\'';
				}
			}

			$query = array(
				'fields' => array(
					'Cov58.id',
					'Cov58.name',
					'Cov58.datecommission',
					'Cov58.etatcov',
					'Cov58.observation'
				),
				'contain'=>false,
				'order' => array( '"Cov58"."datecommission" ASC' ),
				'conditions' => $conditions
			);

			return $query;
		}

		public function dossiersParListe( $cov58_id ) {
			$dossiers = array();

			foreach( $this->Dossiercov58->Themecov58->find('list') as $theme ) {
				$model = Inflector::classify( $theme );
				$contain = array_merge(
					$this->Dossiercov58->{$model}->getContain(),
					array(
						'Personne' => array(
							'Foyer' => array(
								'Adressefoyer' => array(
									'conditions' => array(
										'Adressefoyer.rgadr' => '01'
									),
									'Adresse'
								)
							)
						)
					)
				);
				$dossiers[$model]['liste'] = $this->Dossiercov58->find(
					'all',
					array(
						'conditions' => array(
							'Dossiercov58.cov58_id' => $cov58_id,
							'Dossiercov58.etapecov' => 'traitement'
						),
						'contain' => $contain
					)
				);
			}
			return $dossiers;
		}
		
		public function saveDecisions( $cov58_id, $datas ) {
			$success = true;
			$cov58 = $this->find(
				'first',
				array(
					'conditions' => array(
						'Cov58.id' => $cov58_id
					),
					'contain' => false
				)
			);
			foreach($this->Dossiercov58->Themecov58->find('list') as $theme) {
				$class = Inflector::classify($theme);
				foreach($datas[$class] as $data) {
					$dossier = $this->Dossiercov58->{$class}->find(
						'first',
						array(
							'conditions' => array(
								$class.'.id' => $data['id']
							),
							'contain' => false
						)
					);
					debug($dossier);
				}
			}
			return $success;
		}

	}
?>
