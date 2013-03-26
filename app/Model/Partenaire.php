<?php	
	/**
	 * Code source de la classe Partenaire.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Partenaire ...
	 *
	 * @package app.Model
	 */
	class Partenaire extends AppModel
	{
		public $name = 'Partenaire';

		public $displayField = 'libstruc';
		
		public $recursive = -1;

		public $actsAs = array(
			'Formattable',
			'Pgsqlcake.PgsqlAutovalidate'
		);

		public $validate = array(
			'libstruc' => array(
				'isUnique' => array(
					'rule' => array( 'isUnique' ),
					'message' => 'Cette valeur est déjà utilisée'
				),
				'notEmpty' => array(
					'rule' => array( 'notEmpty' ),
					'message' => 'Champ obligatoire'
				)
			),
			'numvoie' => array(
				array(
					'rule' => array('notEmpty'),
				),
			),
			'typevoie' => array(
				array(
					'rule' => array('notEmpty'),
				),
			),
			'nomvoie' => array(
				array(
					'rule' => array('notEmpty'),
				),
			),
			'codepostal' => array(
				array(
					'rule' => array('notEmpty'),
				),
			),
			'ville' => array(
				array(
					'rule' => array('notEmpty'),
				),
			),
		);

		public $hasOne = array(
			'Contactpartenaire' => array(
				'className' => 'Contactpartenaire',
				'foreignKey' => 'partenaire_id',
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
			'Actioncandidat' => array(
				'className' => 'Actioncandidat',
				'joinTable' => 'actionscandidats_partenaires',
				'foreignKey' => 'partenaire_id',
				'associationForeignKey' => 'actioncandidat_id',
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
			)
		);
		
		public $belongsTo = array(
			'Serviceinstructeur' => array(
				'className' => 'Serviceinstructeur',
				'foreignKey' => 'serviceinstructeur_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
		
		public $virtualFields = array(
			'adresse' => array(
				'type'      => 'string',
				'postgres'  => '( "%s"."numvoie" || \' \' || "%s"."typevoie" || \' \' || "%s"."nomvoie" || \' \' || "%s"."compladr" || \' \' || "%s"."codepostal" || \' \' || "%s"."ville" )'
			)
		);

		
		/**
		*	Recherche des partenaires dans le paramétrage de l'application
		*
		*/
		public function search( $criteres ) {
			/// Conditions de base
			$conditions = array();

			// Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
			$filtersPartenaires = array();
			foreach( array( 'libstruc', 'ville', 'codepartenaire' ) as $criterePartenaire ) {
				if( isset( $criteres['Partenaire'][$criterePartenaire] ) && !empty( $criteres['Partenaire'][$criterePartenaire] ) ) {
					$conditions[] = 'Partenaire.'.$criterePartenaire.' ILIKE \''.$this->wildcard( $criteres['Partenaire'][$criterePartenaire] ).'\'';
				}
			}

			// Critère sur la structure référente de l'utilisateur
			if( isset( $criteres['Partenaire']['serviceinstructeur_id'] ) && !empty( $criteres['Partenaire']['serviceinstructeur_id'] ) ) {
				$conditions[] = array( 'Partenaire.serviceinstructeur_id' => $criteres['Partenaire']['serviceinstructeur_id'] );
			}


			$query = array(
				'fields' => array_merge(
					$this->fields(),
					$this->Serviceinstructeur->fields()
				),
				'order' => array( 'Partenaire.libstruc ASC' ),
				'joins' => array(
					$this->join( 'Serviceinstructeur', array( 'type' => 'LEFT OUTER' ) )
				),
				'recursive' => -1,
				'conditions' => $conditions
			);

			return $query;
		}
	}
?>