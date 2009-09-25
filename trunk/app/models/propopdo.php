<?php 

    class Propopdo extends AppModel
    {
        var $name = 'Propopdo';

        var $belongTo = array(
            'Dossier' => array(
                'classname'     => 'Dossier',
                'foreignKey'    => 'dossier_rsa_id'
            )
        );

        var $validate = array(
            'typepdo' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'motifpdo' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'decisionpdo' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            )
        );

    }
?>