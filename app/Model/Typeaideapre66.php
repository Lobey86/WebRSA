<?php
	class Typeaideapre66 extends AppModel
	{
		public $name = 'Typeaideapre66';

		public $order = 'Typeaideapre66.name ASC';

		public $actsAs = array(
            'Validation.Autovalidate',
            'Enumerable' => array(
                'fields' => array(
                    'isincohorte'
                )
            )
        );

		public $belongsTo = array(
			'Themeapre66' => array(
				'className' => 'Themeapre66',
				'foreignKey' => 'themeapre66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasMany = array(
			'Aideapre66' => array(
				'className' => 'Aideapre66',
				'foreignKey' => 'typeaideapre66_id',
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
			'Pieceaide66' => array(
				'className' => 'Pieceaide66',
				'joinTable' => 'piecesaides66_typesaidesapres66',
				'foreignKey' => 'typeaideapre66_id',
				'associationForeignKey' => 'pieceaide66_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'Pieceaide66Typeaideapre66'
			),
			'Piececomptable66' => array(
				'className' => 'Piececomptable66',
				'joinTable' => 'piecescomptables66_typesaidesapres66',
				'foreignKey' => 'typeaideapre66_id',
				'associationForeignKey' => 'piececomptable66_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'Piececomptable66Typeaideapre66'
			)
		);


		/**
		*
		*/

		public function listOptions() {
			$tmp = $this->find(
				'all',
				array (
					'fields' => array(
						'Typeaideapre66.id',
						'Typeaideapre66.themeapre66_id',
						'Typeaideapre66.name'
					),
					'recursive' => -1,
					'order' => 'Typeaideapre66.name ASC',
				)
			);

			$return = array();
			foreach( $tmp as $key => $value ) {
				$return[$value['Typeaideapre66']['themeapre66_id'].'_'.$value['Typeaideapre66']['id']] = $value['Typeaideapre66']['name'];
			}
			return $return;
		}


		public function occurences() {

			$queryData = array(
				'fields' => array(
					'"Typeaideapre66"."id"',
					'COUNT("Aideapre66"."id") AS "Typeaideapre66__occurences"',
				),
				'joins' => array( 
					$this->join( 'Aideapre66' )
				),
				'recursive' => -1,
				'group' => array(  '"Typeaideapre66"."id"', '"Typeaideapre66"."name"' )
			);
			$results = $this->find( 'all', $queryData );

			return Set::combine( $results, '{n}.Typeaideapre66.id', '{n}.Typeaideapre66.occurences' );
		}
	}
?>
