<?php
    class Amenaglogt extends AppModel
    {
        var $name = 'Amenaglogt';
        var $actsAs = array( 'Enumerable', 'Frenchfloat' => array( 'fields' => array( 'montantaide' ) ) );


        var $validate = array(
            'typeaidelogement' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'montantaide' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
                array(
                    'rule' => 'numeric',
                    'message' => 'Veuillez entrer une valeur numérique.',
                    'allowEmpty' => true
                )
            )
        );

        var $enumFields = array(
            'typeaidelogement' => array( 'type' => 'typeaidelogement', 'domain' => 'apre' ),
        );

        var $hasAndBelongsToMany = array(
            'Pieceamenaglogt' => array(
                'className'             => 'Pieceamenaglogt',
                'joinTable'             => 'amenagslogts_piecesamenagslogts',
                'foreignKey'            => 'amenaglogt_id',
                'associationForeignKey' => 'pieceamenaglogt_id',
                'with'                  => 'AmenaglogtPieceamenaglogt'
            )
        );
    }
?>