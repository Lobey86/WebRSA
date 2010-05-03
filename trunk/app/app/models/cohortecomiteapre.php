<?php
    class Cohortecomiteapre extends AppModel
    {
        var $name = 'Cohortescomitesapres';
        var $useTable = false;


        function search( $avisComite, $criterescomite ) {
            /// Conditions de base
            $conditions = array(
            );

            if( !empty( $avisComite ) ) {
                if( $avisComite == 'Cohortecomiteapre::aviscomite' ) {
                    $conditions[] = 'ApreComiteapre.decisioncomite IS NULL';
                }
                else {
                    $conditions[] = 'ApreComiteapre.decisioncomite IS NOT NULL';
                }
            }

            /// Critères sur le Comité - intitulé du comité
            if( isset( $criterescomite['Cohortecomiteapre']['id'] ) && !empty( $criterescomite['Cohortecomiteapre']['id'] ) ) {
				$conditions['Comiteapre.id'] = $criterescomite['Cohortecomiteapre']['id'];
            }

            /// Critères sur le Comité - date du comité
            if( isset( $criterescomite['Cohortecomiteapre']['datecomite'] ) && !empty( $criterescomite['Cohortecomiteapre']['datecomite'] ) ) {
                $valid_from = ( valid_int( $criterescomite['Cohortecomiteapre']['datecomite_from']['year'] ) && valid_int( $criterescomite['Cohortecomiteapre']['datecomite_from']['month'] ) && valid_int( $criterescomite['Cohortecomiteapre']['datecomite_from']['day'] ) );
                $valid_to = ( valid_int( $criterescomite['Cohortecomiteapre']['datecomite_to']['year'] ) && valid_int( $criterescomite['Cohortecomiteapre']['datecomite_to']['month'] ) && valid_int( $criterescomite['Cohortecomiteapre']['datecomite_to']['day'] ) );
                if( $valid_from && $valid_to ) {
                    $conditions[] = 'Comiteapre.datecomite BETWEEN \''.implode( '-', array( $criterescomite['Cohortecomiteapre']['datecomite_from']['year'], $criterescomite['Cohortecomiteapre']['datecomite_from']['month'], $criterescomite['Cohortecomiteapre']['datecomite_from']['day'] ) ).'\' AND \''.implode( '-', array( $criterescomite['Cohortecomiteapre']['datecomite_to']['year'], $criterescomite['Cohortecomiteapre']['datecomite_to']['month'], $criterescomite['Cohortecomiteapre']['datecomite_to']['day'] ) ).'\'';
                }
            }

            /// Critères sur le Comité - heure du comité
            if( isset( $criterescomite['Cohortecomiteapre']['heurecomite'] ) && !empty( $criterescomite['Cohortecomiteapre']['heurecomite'] ) ) {
                $valid_from = ( valid_int( $criterescomite['Cohortecomiteapre']['heurecomite_from']['hour'] ) && valid_int( $criterescomite['Cohortecomiteapre']['heurecomite_from']['min'] ) );
                $valid_to = ( valid_int( $criterescomite['Cohortecomiteapre']['heurecomite_to']['hour'] ) && valid_int( $criterescomite['Cohortecomiteapre']['heurecomite_to']['min'] ) );
                if( $valid_from && $valid_to ) {
                    $conditions[] = 'Comiteapre.heurecomite BETWEEN \''.implode( ':', array( $criterescomite['Cohortecomiteapre']['heurecomite_from']['hour'], $criterescomite['Cohortecomiteapre']['heurecomite_from']['min'] ) ).'\' AND \''.implode( ':', array( $criterescomite['Cohortecomiteapre']['heurecomite_to']['hour'], $criterescomite['Cohortecomiteapre']['heurecomite_to']['min'] ) ).'\'';
                }
            }

            /// Requête
            $this->Dossier =& ClassRegistry::init( 'Dossier' );

            $query = array(
                'fields' => array(
                    '"Comiteapre"."id"',
                    '"Comiteapre"."datecomite"',
                    '"Comiteapre"."heurecomite"',
                    '"Comiteapre"."lieucomite"',
                    '"Comiteapre"."intitulecomite"',
                    '"Comiteapre"."observationcomite"',
                    '"ApreComiteapre"."id"',
                    '"ApreComiteapre"."apre_id"',
                    '"ApreComiteapre"."comiteapre_id"',
                    '"ApreComiteapre"."decisioncomite"',
                    '"ApreComiteapre"."montantattribue"',
                    '"ApreComiteapre"."observationcomite"',
                    '"Dossier"."numdemrsa"',
                    '"Dossier"."matricule"',
                    '"Personne"."id"',
                    '"Personne"."qual"',
                    '"Personne"."nom"',
                    '"Personne"."prenom"',
                    '"Personne"."dtnai"',
                    '"Personne"."nir"',
                    '"Adresse"."locaadr"',
                    '"Adresse"."codepos"',
                    '"Apre"."id"',
                    '"Apre"."datedemandeapre"',
                    '"Apre"."mtforfait"',
//                     '"Apre"."montanttotal"'
                ),
                'recursive' => -1,
                'joins' => array(
                    array(
                        'table'      => 'apres_comitesapres',
                        'alias'      => 'ApreComiteapre',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'ApreComiteapre.comiteapre_id = Comiteapre.id' )
                    ),
                    array(
                        'table'      => 'apres',
                        'alias'      => 'Apre',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'ApreComiteapre.apre_id = Apre.id' )
                    ),
                    array(
                        'table'      => 'personnes',
                        'alias'      => 'Personne',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Personne.id = Apre.personne_id' )
                    ),
                    array(
                        'table'      => 'foyers',
                        'alias'      => 'Foyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Personne.foyer_id = Foyer.id' )
                    ),
                    array(
                        'table'      => 'dossiers_rsa',
                        'alias'      => 'Dossier',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Foyer.dossier_rsa_id = Dossier.id' )
                    ),
                    array(
                        'table'      => 'prestations',
                        'alias'      => 'Prestation',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array(
                            'Personne.id = Prestation.personne_id',
                            'Prestation.natprest = \'RSA\'',
                            '( Prestation.rolepers = \'DEM\' OR Prestation.rolepers = \'CJT\' )',
                        )
                    ),
                    array(
                        'table'      => 'adresses_foyers',
                        'alias'      => 'Adressefoyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Foyer.id = Adressefoyer.foyer_id', 'Adressefoyer.rgadr = \'01\'' )
                    ),
                    array(
                        'table'      => 'adresses',
                        'alias'      => 'Adresse',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
                    )
                ),
                'order' => array( '"Comiteapre"."datecomite" ASC' ),
                'conditions' => $conditions
            );


            /// Création du champ virtuel montant total pour connaître les montants attribués à une APRE complémentaire
            $this->Apre =& ClassRegistry::init( 'Apre' );
            $query['fields'][] = $this->Apre->sousRequeteMontantTotal().' AS "Apre__montanttotal"';
            $query['joins'] = array_merge( $query['joins'], $this->Apre->joinsAidesLiees() );

            return $query;
        }

    }
?>
