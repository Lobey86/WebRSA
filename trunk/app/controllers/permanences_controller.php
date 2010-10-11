<?php
    class PermanencesController extends AppController
    {

        var $name = 'Permanences';
        var $uses = array( 'Permanence', 'Structurereferente', 'Option' );
        var $helpers = array( 'Xform' );
        
		var $commeDroit = array(
			'add' => 'Permanences:edit'
		);

        protected function _setOptions() {
            $this->set( 'typevoie', $this->Option->typevoie() );
            $this->set( 'sr', $this->Structurereferente->find( 'list' ) );
        }
/*
         function beforeFilter() {
            parent::beforeFilter();
            $this->set( 'typevoie', $this->Option->typevoie() );
            $this->set( 'sr', $this->Structurereferente->find( 'list' ) );
        }*/


        function index() {
            // Retour à la liste en cas d'annulation
            if( isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
            }


            $permanences = $this->Permanence->find(
                'all',
                array(
                    'recursive' => -1
                )

            );
            $this->_setOptions();
            $this->set( 'permanences', $permanences );
        }

        function add() {

            if( !empty( $this->data ) ) {
                if( $this->Permanence->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'permanences', 'action' => 'index' ) );
                }
            }

            $this->_setOptions();
            $this->render( $this->action, null, 'add_edit' );
        }

        function edit( $permanence_id = null ) {
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $permanence_id ), 'error404' );

            if( !empty( $this->data ) ) {
                if( $this->Permanence->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'permanences', 'action' => 'index' ) );
                }
            }
            else {
                $permanence = $this->Permanence->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Permanence.id' => $permanence_id,
                        )
                    )
                );
                $this->data = $permanence;
            }

            $this->_setOptions();
            $this->render( $this->action, null, 'add_edit' );
        }

        function delete( $permanence_id = null ) {
            // Vérification du format de la variable
            if( !valid_int( $permanence_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Recherche de la personne
            $permanence = $this->Permanence->find(
                'first',
                array( 'conditions' => array( 'Permanence.id' => $permanence_id )
                )
            );

            // Mauvais paramètre
            if( empty( $permanence_id ) ) {
                $this->cakeError( 'error404' );
            }

//             $this->_setOptions();
            // Tentative de suppression ... FIXME
            if( $this->Permanence->delete( array( 'Permanence.id' => $permanence_id ) ) ) {
                $this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
                $this->redirect( array( 'controller' => 'permanences', 'action' => 'index' ) );
            }
        }
    }

?>
