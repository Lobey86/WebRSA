<?php

    class DossierspdoController extends AppController{

        var $name = 'Dossierspdo';
        var $uses = array( 'Dossierpdo', 'Situationdossierrsa', 'Option', 'Propopdo' );

        function beforeFilter(){
            parent::beforeFilter();
            $this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
            $this->set( 'decisionpdo', $this->Option->decisionpdo() );
            $this->set( 'typepdo', $this->Option->typepdo() );
            $this->set( 'pieecpres', $this->Option->pieecpres() );
            $this->set( 'commission', $this->Option->commission() );
            $this->set( 'motidempdo', $this->Option->motidempdo() );
            $this->set( 'motideccg', $this->Option->motideccg() );
        }


        function index( $dossier_rsa_id = null ){
            $this->assert( valid_int( $dossier_rsa_id ), 'invalidParameter' );

            $conditions = array( 'Dossier.id' => $dossier_rsa_id );

            if( $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) {
                $mesCodesInsee = $this->Session->read( 'Auth.Zonegeographique' );
                $mesCodesInsee = ( !empty( $mesCodesInsee ) ? array_values( $mesCodesInsee ) : array() );
                $conditions['Adresse.numcomptt'] = $mesCodesInsee;
            }

            /// Récupération de la situation du dossier
            $options = $this->Dossierpdo->prepare( 'etat', array( 'conditions' => $conditions ) );
            $details = $this->Situationdossierrsa->find( 'first', $options );

            /// Récupération des listes des PDO
            $options = $this->Dossierpdo->prepare( 'propopdo', array( 'conditions' => $conditions ) );
            $pdo = $this->Propopdo->find( 'first', $options );
// debug( $pdos );

            $this->set( 'dossier_rsa_id', $dossier_rsa_id );
            $this->set( 'pdo', $pdo );
            $this->set( 'details', $details );
        }


        function view( $pdo_id = null ) {
            $this->assert( valid_int( $pdo_id ), 'invalidParameter' );

            $conditions = array( 'Propopdo.id' => $pdo_id );

            $options = $this->Dossierpdo->prepare( 'propopdo', array( 'conditions' => $conditions ) );
            $pdo = $this->Propopdo->find( 'first', $options );

            $this->set( 'pdo', $pdo );
            $this->set( 'dossier_rsa_id', $pdo['Propopdo']['dossier_rsa_id'] );
        }



        /** ********************************************************************
        *
        *** *******************************************************************/

        public function add() {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }


        public function edit() {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function _add_edit( $dossier_rsa_id = null ) {
            $nbrDossiers = $this->Dossier->find( 'count', array( 'conditions' => array( 'Dossier.id' => $dossier_rsa_id ), 'recursive' => -1 ) );
            $this->assert( ( $nbrDossiers == 1 ), 'invalidParameter' );

             //debug( $this->data );

            $this->Propopdo->begin();

            if( !$this->Jetons->check( $dossier_rsa_id ) ) {
                $this->Propopdo->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_rsa_id ), 'lockedDossier' );

            //Essai de sauvegarde
            if( !empty( $this->data ) ) {
                if( $this->Propopdo->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
                    if( $this->Propopdo->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {

                        $this->Jetons->release( $dossier_rsa_id );
                        $this->Propopdo->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array(  'controller' => 'dossierspdo','action' => 'index', $dossier_rsa_id ) );
                    }
                    else {
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }
            //Affichage des données
            else {
                $this->data = $this->Propopdo->findByDossierRsaId( $dossier_rsa_id, null, null, -1 );

                if( $this->action == 'add' ) {
                    $this->assert( empty( $this->data ), 'invalidParameter' );
                }
                else if( $this->action == 'edit' ) {
                    $this->assert( !empty( $this->data ), 'invalidParameter' );
                }
            }
            $this->Propopdo->commit();

            $this->set( 'dossier_rsa_id', $dossier_rsa_id );
            $this->render( $this->action, null, 'add_edit' );
        }
    }

?>