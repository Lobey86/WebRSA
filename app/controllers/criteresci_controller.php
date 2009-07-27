<?php

    App::import('Sanitize');

    class CriteresciController extends AppController
    {
        var $name = 'Criteresci';
        var $uses = array(  'Dossier', 'Foyer', 'Adresse', 'Personne', 'Typocontrat', 'Structurereferente', 'Contratinsertion', 'Option', 'Serviceinstructeur', 'Cohorteci' );
        var $aucunDroit = array( 'constReq' );

        /**
            INFO: ILIKE et EXTRACT sont spécifiques à PostgreSQL
        */


//         var $paginate = array(
//             // FIXME
//             'limit' => 20,
// //             'order' => array(
// //                 'Criteresci.locaadr' => 'asc'
// //             )
//         );

        /**
        */
        function __construct() {
            $this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'index' ) ) ) );
            parent::__construct();
        }


        function beforeFilter() {
            $return = parent::beforeFilter();

//             $typeservice = $this->Serviceinstructeur->find(
//                 'list',
//                 array(
//                     'fields' => array(
//                         'Serviceinstructeur.id',
//                         'Serviceinstructeur.lib_service'
//                     ),
//                 )
//             );
//             $this->set( 'typeservice', $typeservice );

            $personne_suivi = $this->Contratinsertion->find(
                'list',
                array(
                    'fields' => array(
                        'Contratinsertion.pers_charg_suivi',
                        'Contratinsertion.pers_charg_suivi'
                    ),
                    'order' => 'Contratinsertion.pers_charg_suivi ASC',
                    'group' => 'Contratinsertion.pers_charg_suivi',
                )
            );
            $this->set( 'personne_suivi', $personne_suivi );

            $this->set( 'decision_ci', $this->Option->decision_ci() );
            return $return;
        }


        function index() {
            $params = $this->data;
            if( !empty( $params ) ) {
                /*$conditions = array();

                // INFO: seulement les personnes qui sont dans ma zone géographique
                $conditions['Contratinsertion.personne_id'] = $this->Personne->findByZones( $this->Session->read( 'Auth.Zonegeographique' ), $this->Session->read( 'Auth.User.filtre_zone_geo' ) );

                //Critère recherche par Contrat insertion: date de création contrat
                if( dateComplete( $this->data, 'Contratinsertion.date_saisi_ci' ) ) {
                    $date_saisi_ci = $this->data['Contratinsertion']['date_saisi_ci'];
                    $conditions['Contratinsertion.date_saisi_ci'] = $date_saisi_ci['year'].'-'.$date_saisi_ci['month'].'-'.$date_saisi_ci['day'];
                }

                //Critère recherche par Contrat insertion: localisation de la personne rattachée au contrat
                if( isset( $params['Adresse']['locaadr'] ) && !empty( $params['Adresse']['locaadr'] ) ){
                    $conditions[] = "Adresse.locaadr ILIKE '%".Sanitize::paranoid( $params['Adresse']['locaadr'] )."%'";
                }

                //Critère recherche par Contrat insertion: par décision du CG
                if( isset( $params['Contratinsertion']['decision_ci'] ) && !empty( $params['Contratinsertion']['decision_ci'] ) ){
                    $conditions[] = "Contratinsertion.decision_ci ILIKE '%".Sanitize::paranoid( $params['Contratinsertion']['decision_ci'] )."%'";
                }

                //Critère recherche par Contrat insertion: date de validation du contrat
                if( dateComplete( $this->data, 'Contratinsertion.datevalidation_ci' ) ) {
                    $datevalidation_ci = $this->data['Contratinsertion']['datevalidation_ci'];
                    $conditions['Contratinsertion.datevalidation_ci'] = $datevalidation_ci['year'].'-'.$datevalidation_ci['month'].'-'.$datevalidation_ci['day'];
                }

                //Critère recherche par Contrat insertion: par service instructeur
                if( isset( $params['Serviceinstructeur']['id'] ) && !empty( $params['Serviceinstructeur']['id'] ) ){
                    $conditions['Serviceinstructeur.id'] = $params['Serviceinstructeur']['id'];
                }

                $query = $this->Contratinsertion->queries['criteresci'];
                $query['limit'] = 10;

                $this->paginate = $query;
                $contrats = $this->paginate( 'Contratinsertion', $conditions );*/

                $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
                $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

                $this->Dossier->begin(); // Pour les jetons

                $this->paginate = $this->Cohorteci->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
                $this->paginate['limit'] = 10;
                $contrats = $this->paginate( 'Contratinsertion' );

                $this->Dossier->commit();

                $this->set( 'contrats', $contrats );
                $this->data['Search'] = $params;
            }
        }

    }
?>
