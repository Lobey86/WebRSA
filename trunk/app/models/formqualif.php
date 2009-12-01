<?php 
    class Formqualif extends AppModel
    {
        var $name = 'Formqualif';

        var $actsAs = array( 'Frenchfloat' => array( 'fields' => array( 'coutform', 'montantaide', 'dureeform' ) ) );

        var $validate = array(
            'intituleform' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'organismeform' => array(
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
            ),
            'coutform' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
                array(
                    'rule' => 'numeric',
                    'message' => 'Veuillez entrer une valeur numérique.',
                    'allowEmpty' => true
                )
            ),
            'dureeform' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
                array(
                    'rule' => 'numeric',
                    'message' => 'Veuillez entrer une valeur numérique.',
                    'allowEmpty' => true
                )
            ),
            'ddform' => array(
                array(
                    'rule' => 'date',
                    'message' => 'Veuillez vérifier le format de la date.'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'dfform' => array(
                array(
                    'rule' => 'date',
                    'message' => 'Veuillez vérifier le format de la date.'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
        );

        var $hasAndBelongsToMany = array(
            'Pieceformqualif' => array(
                'className'             => 'Pieceformqualif',
                'joinTable'             => 'formsqualifs_piecesformsqualifs',
                'foreignKey'            => 'formqualif_id',
                'associationForeignKey' => 'pieceformqualif_id',
                'with'                  => 'FormqualifPieceformqualif'
            )
        );
    }
?>