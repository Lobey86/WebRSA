<?php	
	/**
	 * Code source de la classe Codesromesecteursdsps66Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Codesromesecteursdsps66Controller ...
	 *
	 * @package app.Controller
	 */
	class Codesromesecteursdsps66Controller extends AppController
	{
		public $helpers = array( 'Default', 'Default2' );

		public $commeDroit = array(
			'add' => 'Codesromesecteursdsps66:edit'
		);

		protected function _setOptions() {
			$options = array();
			$this->set( compact( 'options' ) );
		}

		public function index() {
			$this->paginate = array(
				'fields' => array(
					'Coderomesecteurdsp66.id',
					'Coderomesecteurdsp66.code',
					'Coderomesecteurdsp66.name'
				),
				'contain' => false,
				'limit' => 10
			);
			$this->_setOptions();
			$this->set( 'codesromesecteursdsps66', $this->paginate( $this->Coderomesecteurdsp66 ) );
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

		protected function _add_edit( $id = null ) {
			if( !empty( $this->request->data ) ) {
				$this->Coderomesecteurdsp66->create( $this->request->data );
				$success = $this->Coderomesecteurdsp66->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->redirect( array( 'action' => 'index' ) );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->request->data = $this->Coderomesecteurdsp66->find(
					'first',
					array(
						'contain' => false,
						'conditions' => array( 'Coderomesecteurdsp66.id' => $id )
					)
				);
				$this->assert( !empty( $this->request->data ), 'error404' );
			}

			$this->_setOptions();
			$this->render( 'add_edit' );
		}

		/**
		*
		*/

		public function delete( $id ) {
			$success = $this->Coderomesecteurdsp66->delete( $id );
			$this->_setFlashResult( 'Delete', $success );
			$this->redirect( array( 'action' => 'index' ) );
		}
	}
?>