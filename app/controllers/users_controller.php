<?php
    class UsersController extends AppController
    {
        var $name = 'Users';
        var $uses = array( 'Group', 'Zonegeographique', 'User', 'Serviceinstructeur', 'Connection' );
        var $aucunDroit = array('login', 'logout');

        /**
        *  The AuthComponent provides the needed functionality
        *  for login, so you can leave this function blank.
        */
        function login() {
           if( $this->Auth->user() ) {
                /* Lecture de l'utilisateur authentifié */
                $authUser = $this->Auth->user();

                // Utilisateurs concurrents
                if( Configure::read( 'Utilisateurs.multilogin' ) == false ) {
                    $this->Connection->begin();
                    // Suppression des connections dépassées
                    $this->Connection->deleteAll(
                        array(
                            '"Connection"."modified" <' => strftime( '%Y-%m-%d %H:%M:%S', strtotime( '-'.readTimeout().' seconds' ) )
                        )
                    );
                    if( $this->Connection->find( 'count', array( 'conditions' => array( 'Connection.user_id' => $authUser['User']['id'] ) ) ) == 0 ) {
                        $connection = array(
                            'Connection' => array(
                                'user_id' => $authUser['User']['id'],
                                'php_sid' => session_id()
                            )
                        );

                        $this->Connection->set( $connection );
                        if( $this->Connection->save( $connection ) ) {
                            $this->Connection->commit();
                        }
                        else {
                            $this->Connection->rollback();
                        }
                    }
                    else {
                        $this->Session->delete( 'Auth' );
                        $this->Session->setFlash( 'Utilisateur déjà connecté', 'flash/error' );
                        $this->redirect( $this->Auth->logout() );
                    }
                }
                // Fin utilisateurs concurrents

                /* lecture du service de l'utilisateur authentifié */
                $this->Utilisateur->Service->recursive=-1;
                $group =  $this->Group->findById($authUser['User']['group_id']);
                //$authUser['aroAlias'] = $group['Group']['name'].':'. $authUser['User']['username'];
                $authUser['User']['aroAlias'] = 'Utilisateur:'. $authUser['User']['username'];
                /* lecture de la collectivite de l'utilisateur authentifié */
                $this->Session->write( 'Auth', $authUser );
                $this->redirect( $this->Auth->redirect() );
            }
        }

        function logout() {
            if( $user_id = $this->Session->read( 'Auth.User.id' ) ) {
                if( valid_int( $user_id ) ) {
                    $this->Jeton = ClassRegistry::init( 'Jeton' ); // FIXME: dans Jetons
                    $this->Jeton->deleteAll(
                        array(
                            '"Jeton"."user_id"' => $user_id
                        )
                    );
                    // Utilisateurs concurrents
                    if( Configure::read( 'Utilisateurs.multilogin' ) == false ) {
                        $this->Connection->deleteAll( array( 'Connection.user_id' => $user_id ) );
                    }
                    // Fin utilisateurs concurrents
                }
            }
            $this->Session->delete( 'Auth' );
            $this->redirect( $this->Auth->logout() );
        }

        function index() {
            $users = $this->User->find(
                'all',
                array(
                    'recursive' => 1
                )

            );

            $this->set('users', $users);
        }
        // FIXME: à l'ajout, on n'obtient pas toutes les acl de son groupe
        function add() {
            $zg = $this->Zonegeographique->find(
                'list',
                array(
                    'fields' => array(
                        'Zonegeographique.id',
                        'Zonegeographique.libelle'
                    )
                )
            );
            $this->set( 'zglist', $zg );

            $gp = $this->Group->find(
                'list',
                array(
                    'fields' => array(
                       // 'Group.id',
                        'Group.name'
                    )
                )
            );
            $this->set( 'gp', $gp );

            $si = $this->Serviceinstructeur->find(
                'list',
                array(
                    'fields' => array(
                        'Serviceinstructeur.id',
                        'Serviceinstructeur.lib_service'
                    ),
                )
            );
            $this->set( 'si', $si );

            if( !empty( $this->data ) ) {
                if( $this->User->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'users', 'action' => 'index' ) );
                }
            }

            $this->render( $this->action, null, 'add_edit' );
        }

        function edit( $user_id = null ) {
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $user_id ), 'error404' );

            $zg = $this->Zonegeographique->find(
                'list',
                array(
                    'fields' => array(
                        'Zonegeographique.id',
                        'Zonegeographique.libelle'
                    )
                )
            );
            $this->set( 'zglist', $zg );

            $gp = $this->Group->find(
                'list',
                array(
                    'fields' => array(
                        //'Group.id',
                        'Group.name'
                    )
                )
            );
            $this->set( 'gp', $gp );

            $si = $this->Serviceinstructeur->find(
                'list',
                array(
                    'fields' => array(
                        //'Serviceinstructeur.id',
                        'Serviceinstructeur.lib_service'
                    )
                )
            );
            $this->set( 'si', $si );

            if( !empty( $this->data ) ) {
                if( $this->User->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'users', 'action' => 'index' ) );
                }
            }
            else {
               $user = $this->User->find(
                    'first',
                    array(
                        'conditions' => array(
                            'User.id' => $user_id,
                        )
                    )
                );
                $this->data = $user;
            }
            $this->render( $this->action, null, 'add_edit' );
        }


        function delete( $user_id = null ) {
            // Vérification du format de la variable
            if( !valid_int( $user_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Recherche de la personne
            $user = $this->User->find(
                'first',
                array( 'conditions' => array( 'User.id' => $user_id )
                )
            );

            // Mauvais paramètre
            if( empty( $user_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Tentative de suppression ... FIXME
            if( $this->User->delete( array( 'User.id' => $user_id ) ) ) {
                $this->Session->setFlash( 'Suppression effectuée' );
                $this->redirect( array( 'controller' => 'users', 'action' => 'index' ) );
            }
        }
    }
?>
