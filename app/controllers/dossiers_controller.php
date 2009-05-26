<?php
    App::import('Sanitize');

    class DossiersController extends AppController
    {
        var $name = 'Dossiers';
        var $uses = array( 'Dossier', 'Foyer', 'Adresse', 'Personne', 'Structurereferente', 'Orientstruct', 'Typeorient', 'Contratinsertion', 'Detaildroitrsa', 'Detailcalculdroitrsa', 'Option', 'Dspp', 'Dspf', 'Infofinanciere', 'ModeContact',/*'Foyercreance',*/ 'Creance', 'Adressefoyer' );


        function beforeFilter() {
            parent::beforeFilter();
            $this->set( 'type_ci', $this->Option->type_ci() );
            $this->set( 'natpf', $this->Option->natpf() );
            $this->set( 'decision_ci', $this->Option->decision_ci() );
        }
        /**
            INFO: ILIKE et EXTRACT sont spécifiques à PostgreSQL
        */
        function index() {
            $params = $this->data;

            if( count( $params ) > 0 ) {
                $filters = array();

                // Critères sur le dossier - numéro de dossier
                if( isset( $params['Dossier']['numdemrsa'] ) && !empty( $params['Dossier']['numdemrsa'] ) ) {
                    $filters[] = "Dossier.numdemrsa ILIKE '%".Sanitize::paranoid( $params['Dossier']['numdemrsa'] )."%'";
                }

                // Critères sur le dossier - date de demande
                if( isset( $params['Dossier']['dtdemrsa'] ) && !empty( $params['Dossier']['dtdemrsa'] ) ) {
                    $valid_from = ( valid_int( $params['Dossier']['dtdemrsa_from']['year'] ) && valid_int( $params['Dossier']['dtdemrsa_from']['month'] ) && valid_int( $params['Dossier']['dtdemrsa_from']['day'] ) );
                    $valid_to = ( valid_int( $params['Dossier']['dtdemrsa_to']['year'] ) && valid_int( $params['Dossier']['dtdemrsa_to']['month'] ) && valid_int( $params['Dossier']['dtdemrsa_to']['day'] ) );
                    if( $valid_from && $valid_to ) {
                        $filters[] = 'Dossier.dtdemrsa BETWEEN \''.implode( '-', array( $params['Dossier']['dtdemrsa_from']['year'], $params['Dossier']['dtdemrsa_from']['month'], $params['Dossier']['dtdemrsa_from']['day'] ) ).'\' AND \''.implode( '-', array( $params['Dossier']['dtdemrsa_to']['year'], $params['Dossier']['dtdemrsa_to']['month'], $params['Dossier']['dtdemrsa_to']['day'] ) ).'\'';
                    }
                }

                // Critères sur une personne du foyer - nom, prénom, nom de jeune fille
                $filtersPersonne = array();
                foreach( array( 'nom', 'prenom', 'nomnai' ) as $criterePersonne ) {
                    if( isset( $params['Personne'][$criterePersonne] ) && !empty( $params['Personne'][$criterePersonne] ) ) {
                        $filtersPersonne['Personne.'.$criterePersonne.' ILIKE'] = '%'.$params['Personne'][$criterePersonne].'%';
                    }
                }

                // Critères sur une personne du foyer - date de naissance
                if( isset( $params['Personne']['dtnai'] ) && !empty( $params['Personne']['dtnai'] ) ) {
                    if( valid_int( $params['Personne']['dtnai']['year'] ) ) {
                        $filtersPersonne['EXTRACT(YEAR FROM Personne.dtnai) ='] = $params['Personne']['dtnai']['year'];
                    }
                    if( valid_int( $params['Personne']['dtnai']['month'] ) ) {
                        $filtersPersonne['EXTRACT(MONTH FROM Personne.dtnai) ='] = $params['Personne']['dtnai']['month'];
                    }
                    if( valid_int( $params['Personne']['dtnai']['day'] ) ) {
                        $filtersPersonne['EXTRACT(DAY FROM Personne.dtnai) ='] = $params['Personne']['dtnai']['day'];
                    }
                }

                // Recherche des foyers suivant les critères sur les personnes
                if( count( $filtersPersonne ) > 0 ) {
                    $foyers = $this->Personne->find(
                        'list',
                        array(
                            'fields' => array(
                                'Personne.id',
                                'Personne.foyer_id',
                            ),
                            'conditions' => array( $filtersPersonne ),
                            'recursive' => -1
                        )
                    );
                    // Critères sur les dossiers suivant les numéros de foyers retournés
                    $filters[] = ( count( $foyers ) > 0 ) ? 'Foyer.id IN ( '.implode( ',', $foyers ).' )' : 'FALSE';
                }

                // Recherche
                $this->Dossier->recursive = 2;
                $dossiers = $this->paginate( 'Dossier', array( $filters ) );

                $this->set( 'dossiers', $dossiers );
                $this->data['Search'] = $params;


            }
        }

        /**
        */
        function menu() {
            // Ce n'est pas un appel par une URL
            $this->assert( isset( $this->params['requested'] ), 'error404' );

            $conditions = array();

            if( !empty( $this->params['id'] ) && is_numeric( $this->params['id'] ) ) {
                $conditions['"Dossier"."id"'] = $this->params['id'];
            }
            else if( !empty( $this->params['foyer_id'] ) && is_numeric( $this->params['foyer_id'] ) ) {
                $conditions['"Foyer"."id"'] = $this->params['foyer_id'];
            }
            else if( !empty( $this->params['personne_id'] ) && is_numeric( $this->params['personne_id'] ) ) {
                $personne = $this->Dossier->Foyer->Personne->find(
                    'first', array(
                        'conditions' => array(
                            'Personne.id' => $this->params['personne_id']
                        )
                    )
                );

                $this->assert( !empty( $personne ), 'error500' );

                $conditions['"Foyer"."id"'] = $personne['Personne']['foyer_id'];
            }

            $this->assert( !empty( $conditions ), 'error500' );

            $dossier = $this->Dossier->find(
                'first',
                array(
                    'conditions' => $conditions,
                    'recursive'  => 2
                )
            );

            $this->assert( !empty( $dossier ), 'error500' );

            usort( $dossier['Foyer']['AdressesFoyer'], create_function( '$a,$b', 'return strcmp( $a["rgadr"], $b["rgadr"] );' ) );

            foreach( $dossier['Foyer']['AdressesFoyer'] as $key => $AdressesFoyer ) {
                $adresses = $this->Adresse->find(
                    'all',
                    array(
                        'conditions' => array(
                            'Adresse.id' => $AdressesFoyer['adresse_id'] )
                    )
                );
                $dossier['Foyer']['AdressesFoyer'][$key] = array_merge( $dossier['Foyer']['AdressesFoyer'][$key], $adresses[0] );
            }

            return $dossier;
        }

        /**
        */
        function view( $id = null ) {
            $this->assert( valid_int( $id ), 'error404' );

            $dossier = $this->Dossier->find(
                'first',
                array(
                    'conditions' => array( 'Dossier.id' => $id ),
                    'recursive' => 0
                )
            );
            $this->assert( !empty( $dossier ), 'error500' );

            //*****************************************************************

            $personne = $this->Personne->find(
                'first',
                array(
                    'conditions' => array(
                        'Personne.foyer_id' => $dossier['Foyer']['id'],
                        'Personne.rolepers' => 'DEM' // FIXME ?
                    ),
                    'recursive' => -1
                )
            );
            $this->assert( !empty( $personne ), 'error500' );

            //-----------------------------------------------------------------

            $orientStruct = $this->Orientstruct->find(
                'first',
                array(
                    'conditions' => array( 'Orientstruct.personne_id' => $personne['Personne']['id'] ),
                    'recursive' => -1
                )
            );
            //$this->assert( !empty( $orientStruct ), 'error500' );

            //-----------------------------------------------------------------

            $contratinsertion = $this->Contratinsertion->find(
                'first',
                array(
                    'conditions' => array( 'Contratinsertion.personne_id' => $personne['Personne']['id'] ),
                    'recursive' => -1
                )
            );
            //$this->assert( !empty( $contratinsertion ), 'error500' );

            //-----------------------------------------------------------------

            $personne['Personne']['Orientstruct'] = $orientStruct['Orientstruct'];
            $personne['Personne']['Contratinsertion'] = $contratinsertion['Contratinsertion'];
            $dossier = Set::merge( $dossier, $personne );

            //*****************************************************************

            $adresseFoyer = $this->Adressefoyer->find(
                'first',
                array(
                    'conditions' => array(
                        'Adressefoyer.foyer_id' => $dossier['Foyer']['id'],
                        'Adressefoyer.rgadr'    => '01'
                    ),
                    'recursive' => 1
                )
            );
            //$this->assert( !empty( $adresseFoyer ), 'error500' );
            $dossier = Set::merge( $dossier, array( 'Adresse' => $adresseFoyer['Adresse'] ) );

            //-----------------------------------------------------------------

            $creance = $this->Creance->find(
                'first',
                array(
                    'conditions' => array(
                        'Creance.id' => $dossier['Foyer']['id'],
                    ),
                    'recursive' => 2
                )
            );
            $dossier = Set::merge( $dossier, array( 'Creance' => $creance['Creance'] ) );


            $modes = $this->ModeContact->find(
                'first',
                array(
                    'conditions' => array(
                        'ModeContact.id' => $dossier['Foyer']['id'],
                    ),
                    'recursive' => 2
                )
            );
            $dossier = Set::merge( $dossier, array( 'ModeContact' => $modes['ModeContact'] ) );

            //-----------------------------------------------------------------
            $struct = $this->Structurereferente->find(
                'first',
                array(
                    'conditions' => array(
                        'Structurereferente.id' => $dossier['Foyer']['id'],
                    ),
                    'recursive' => -1
                )
            );
            $dossier = Set::merge( $dossier, array( 'Structurereferente' => $struct['Structurereferente'] ) );
            //-----------------------------------------------------------------
            $typeorient = $this->Typeorient->find(
                'first',
                array(
                    'conditions' => array(
                        'Typeorient.id' => $dossier['Foyer']['id'],
                    ),
                    'recursive' => -1
                )
            );
            $dossier = Set::merge( $dossier, array( 'Typeorient' => $typeorient['Typeorient'] ) );

            //-----------------------------------------------------------------

            $detail = $this->Detailcalculdroitrsa->find(
                'first',
                array(
                    'conditions' => array(
                        'Detailcalculdroitrsa.detaildroitrsa_id' => $dossier['Detaildroitrsa']['id'],
                    ),
                    'order' => 'dtderrsavers DESC',
                    'recursive' => -1
                )
            );

            $dossier['Detaildroitrsa']['Detailcalculdroitrsa'] = $detail['Detailcalculdroitrsa']; // FIXME: vérifier avec plusieurs



            $this->assert( !empty( $dossier ), 'error404' );
            $this->set( 'dossier', $dossier );

        }
    }
?>
