<?php
	class RegroupementsepsController extends AppController
	{
		public $helpers = array( 'Default', 'Default2' );

		public $commeDroit = array(
			'add' => 'Regroupementseps:edit'
		);

		public function beforeFilter() {
		}


		protected function _setOptions() {
			$options = $this->Regroupementep->enums();
			$this->set( compact( 'options' ) );
		}


		public function index() {
			$this->paginate = array(
				'limit' => 10
			);

			$this->_setOptions();
			$this->set( 'regroupementeps', $this->paginate( $this->Regroupementep ) );
			$this->set( 'themes', $this->Regroupementep->themes() );
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
			if( !empty( $this->data ) ) {
				$this->Regroupementep->create( $this->data );
				$success = $this->Regroupementep->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->redirect( array( 'action' => 'index' ) );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->data = $this->Regroupementep->find(
					'first',
					array(
						'contain' => false,
						'conditions' => array( 'Regroupementep.id' => $id )
					)
				);
				$this->assert( !empty( $this->data ), 'error404' );
			}

			$this->_setOptions();
			$this->render( null, null, 'add_edit' );
		}

		/**
		*
		*/

		public function delete( $id ) {
			$success = $this->Regroupementep->delete( $id );
			$this->_setFlashResult( 'Delete', $success );
			$this->redirect( array( 'action' => 'index' ) );
		}
	}
?>