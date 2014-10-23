<?php
	/**
	 * Code source de la classe SituationspdosController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe SituationspdosController ...
	 *
	 * @package app.Controller
	 */
	class SituationspdosController extends AppController
	{
		public $name = 'Situationspdos';
		public $uses = array( 'Situationpdo', 'Propopdo', 'Option' );
		public $helpers = array( 'Xform', 'Default', 'Default2', 'Theme' );
		public $components = array( 'Default' );

		public $commeDroit = array(
			'view' => 'Situationspdos:index',
			'add' => 'Situationspdos:edit'
		);

		/**
		*   Ajout à la suite de l'utilisation des nouveaux helpers
		*   - default.php
		*   - theme.php
		*/

         protected function _setOptions() {
			$options = $this->Situationpdo->enums();
			$this->set( compact( 'options' ) );
		}
        
		public function index() {
			$queryData = $this->Situationpdo->qdOccurences();
            $this->paginate = $queryData;
			$situationspdos = $this->paginate( $this->modelClass );
            $this->_setOptions();
            $this->set( compact( 'situationspdos' ) );
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

		protected function _add_edit(){
			$args = func_get_args();
 			$this->_setOptions();
			$this->Default->{$this->action}( $args );
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