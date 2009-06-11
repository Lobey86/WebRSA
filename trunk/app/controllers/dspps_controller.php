<?php
    class DsppsController extends AppController
    {

        var $name = 'Dspps';
        var $uses = array( 'Dspp', 'Difsoc', 'Nataccosocindi', 'Difdisp', 'Natmob', 'Nivetu', 'Accoemploi', 'Personne', 'Option', 'Serviceinstructeur');


        function beforeFilter() {
            parent::beforeFilter();
            //$this->set( 'accoemploi', $this->Option->accoemploi() );
            $this->set( 'hispro', $this->Option->hispro() );
            $this->set( 'duractdomi', $this->Option->duractdomi() );
            // Données socioprofessionnelles personne
            $this->set( 'difsocs', $this->Difsoc->find( 'list' ) );
            $this->set( 'nataccosocindis', $this->Nataccosocindi->find( 'list' ) );
            $this->set('difdisps', $this->Difdisp->find( 'list' ) );
            $this->set( 'natmobs', $this->Natmob->find( 'list' ) );
            $this->set( 'nivetus', $this->Nivetu->find( 'list' ) );
            $this->set( 'accoemplois', $this->Accoemploi->find( 'list' ) );
        }

        function view( $personne_id = null ){
            // TODO : vérif param
            // Vérification du format de la variable
            if( !valid_int( $personne_id ) ) {
                $this->cakeError( 'error404' );
            }

            $typeservices = $this->Serviceinstructeur->find(
                'list',
                array(
                    'fields' => array(
                        'Serviceinstructeur.lib_service'
                    )
                )
            );
            $this->set( 'typeservices', $typeservices );


            $dspp = $this->Dspp->find(
                'first',
                array(
                    'conditions' => array(
                        'Dspp.personne_id' => $personne_id
                    )
                )
            ) ;

            // TODO: si personne n'existe pas -> 404
            $this->set( 'dspp', $dspp );


// debug ($dspp);
            $this->set( 'personne_id', $personne_id );
        }


        /**
            Ajout/création d'un dossier socio-professionnel pour la personne
        */
        function add( $personne_id = null ) {
            // Vérification du format de la variable
            if( !valid_int( $personne_id ) ) {
                $this->cakeError( 'error404' );
            }

            $typeservices = $this->Serviceinstructeur->find(
                'list',
                array(
                    'fields' => array(
                        'Serviceinstructeur.lib_service'
                    )
                )
            );
            $this->set( 'typeservices', $typeservices );

          /*  if( !empty( $this->data ) ) {
                $this->data['Dspf']['foyer_id'] = $foyerId;

                // Essai de sauvegarde
                $this->Dspf->begin();
                $this->Dspf->set( $this->data['Dspf'] );
                $valid = $this->Dspf->validates();

                if( $valid ) {
                    $saved = $this->Dspf->save( $this->data['Dspf'] );

                    if( $saved ) {
                        $this->Dspf->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    }
                    else {
                        $this->Dspf->rollback();
                        $this->Session->setFlash( 'Impossible d\'enregistrer', 'flash/error' );
                    }
                }
                else {
                    $this->Dspf->rollback();
                    $this->Session->setFlash( 'Impossible d\'enregistrer', 'flash/error' );
                }
            }*/
            // Essai de sauvegarde
            if( !empty( $this->data ) && $this->Dspp->save( $this->data ) ) {
                $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                $this->redirect( array( 'controller' => 'dspps', 'action' => 'view', $personne_id ) );
            }
            $personne = $this->Personne->find( 'first', array( 'conditions'=> array( 'Personne.id' => $personne_id ) ));
            $this->set(
                'foyer_id',
                $personne['Personne']['foyer_id']
            );
            $this->set( 'personne_id', $personne_id );
            $this->render( $this->action, null, 'add_edit' );
        }



        function edit( $personne_id = null ) {
            // Vérification du format de la variable
            if( !valid_int( $personne_id ) ) {
                $this->cakeError( 'error404' );
            }


            $typeservices = $this->Serviceinstructeur->find(
                'list',
                array(
                    'fields' => array(
                        'Serviceinstructeur.lib_service'
                    )
                )
            );
            $this->set( 'typeservices', $typeservices );

            // FOYER
            $personne = $this->Personne->find(
                'first', array(
                        'conditions'=> array( 'Personne.id' => $personne_id ) ));


            // TODO -> 404
            if( !empty( $this->data ) ) {

                if( $this->Dspp->saveAll( $this->data ) ) {
                   // debug( 'w00t' );

                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'dspps', 'action' => 'view', $personne_id ) );
                }
            }
            else {
                $dspp = $this->Dspp->find(
                    'first',
                    array(
                        'conditions'=> array( 'Dspp.personne_id' => $personne_id )
                    )
                );
                $this->data = $dspp;
//debug( $dspp );
            }
//             $this->set(
//                 'foyer_id',
//                 $personne['Personne']['foyer_id']
//             );
            $this->set( 'personne_id', $personne_id );
            $this->render( $this->action, null, 'add_edit' );
        }
    }
?>