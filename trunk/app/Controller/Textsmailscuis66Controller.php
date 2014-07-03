<?php
	/**
	 * Code source de la classe Textsmailscuis66Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

    App::import( 'Behaviors', 'Occurences' );
	/**
	 * La classe Textsmailscuis66Controller ...
	 *
	 * @package app.Controller
	 */
	class Textsmailscuis66Controller extends AppController
	{
		public $name = 'Textsmailscuis66';
		public $uses = array( 'Textmailcui66', 'Option' );
		public $helpers = array( 'Xform', 'Default', 'Default2', 'Theme' );
		public $components = array( 'Default' );

		public $commeDroit = array(
			'view' => 'Textsmailscuis66:index'
		);

		/**
		*   Ajout à la suite de l'utilisation des nouveaux helpers
		*   - default.php
		*   - theme.php
		*/

		public function index() {
			$this->Textmailcui66->Behaviors->attach( 'Occurences' );
  
            $querydata = $this->Textmailcui66->qdOccurencesExists(
                array(
                    'fields' => $this->Textmailcui66->fields(),
                    'order' => array( 'Textmailcui66.name ASC' )
                )
            );

            $this->paginate = $querydata;
            $textsmailscuis66 = $this->paginate('Textmailcui66');
            $this->set( compact('textsmailscuis66'));
		}

		/**
		*
		*/

		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		public function edit() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		protected function _add_edit( $id = null){
            // Retour à la liste en cas d'annulation
            if( isset( $this->request->data['Cancel'] ) ) {
                $this->redirect( array( 'controller' => 'textsmailscuis66', 'action' => 'index' ) );
            }

			if( !empty( $this->request->data ) ) {
				$this->Textmailcui66->create( $this->request->data );
				$success = $this->Textmailcui66->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->redirect( array( 'action' => 'index' ) );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->request->data = $this->Textmailcui66->find(
					'first',
					array(
						'contain' => false,
						'conditions' => array( 'Textmailcui66.id' => $id )
					)
				);
				$this->assert( !empty( $this->request->data ), 'error404' );
			}

			$this->render( 'add_edit' );
		}

		/**
		*
		*/

		public function delete( $id ) {
			$this->Default->delete( $id );
		}

		/**
		*
		*/

		public function view( $id ) {
			$this->Default->view( $id );
		}
	}
?>