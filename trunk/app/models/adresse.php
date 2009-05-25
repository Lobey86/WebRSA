<?php
    class Adresse extends AppModel
    {
        var $name = 'Adresse';
        var $useTable = 'adresses';

        //*********************************************************************

        /**
            Associations
        */
        var $hasOne = array(
            'Adressefoyer' => array(
                'className'     => 'Adressefoyer',
                'foreignKey'    => 'adresse_id'
            )
        );

        //*********************************************************************

        /**
            Validation ... TODO
        */
        var $validate = array(
//             'numvoie' => array(
//                 array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 )
//             ),
            'typevoie' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'nomvoie' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'codepos' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'locaadr' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'pays' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
        );
    }
?>