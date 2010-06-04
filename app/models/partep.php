<?php
	class Partep extends AppModel
	{
		public $name = 'Partep';

        public $displayField = 'nom_complet';

		public $actsAs = array(
			'Autovalidate',
            'Enumerable' => array(
                'fields' => array(
                    'rolepartep' => array( 'domain' => 'partep' )
                )
            ),
            'Formattable'
		);

		public $belongsTo = array(
			'Ep',
			'Fonctionpartep'
		);

        public $hasAndBelongsToMany = array(
            'Seanceep' => array(
                'className' => 'Seanceep',
                'joinTable' => 'partseps_seanceseps',
                'foreignKey' => 'partep_id',
                'associationForeignKey' => 'seanceep_id',
                'unique' => true,
                'with' => 'PartepSeanceep'
            ),
        );

        public $virtualFields = array(
            'nom_complet' => array(
                'type'      => 'string',
                'postgres'  => '( "%s"."qual" || \' \' || "%s"."nom" || \' \' || "%s"."prenom" )'
            ),
        );
	}
?>