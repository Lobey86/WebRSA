<?php    
    /**
     * Code source de la classe Piecesmodelestypescourrierspcgs66Controller.
     *
     * PHP 5.3
     *
     * @package app.Controller
     * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
     */
    App::import('Behaviors', 'Occurences');

    /**
     * La classe Piecesmodelestypescourrierspcgs66Controller ...
     *
     * @package app.Controller
     */
    class Piecesmodelestypescourrierspcgs66Controller extends AppController
    {
        public $name = 'Piecesmodelestypescourrierspcgs66';

        public $helpers = array( 'Default2' );

		/**
		 * Components utilisés par ce contrôleur.
		 *
		 * @var array
		 */
		public $components = array( 'Default' );

		public $commeDroit = array(
			'view' => 'Piecesmodelestypescourrierspcgs66:index',
			'add' => 'Piecesmodelestypescourrierspcgs66:edit'
		);

		protected function _setOptions() {
			$options = array();
			$options[$this->modelClass]['modeletypecourrierpcg66_id'] = $this->Piecemodeletypecourrierpcg66->Modeletypecourrierpcg66->find( 'list', array( 'fields' => array( 'id', 'name' ) ) );

			$options = Set::merge( $this->Piecemodeletypecourrierpcg66->enums(), $options);
			$this->set( compact( 'options' ) );

		}

        
        public function index() {
            $this->Piecemodeletypecourrierpcg66->Behaviors->attach( 'Occurences' );
            $querydata = $this->Piecemodeletypecourrierpcg66->qdOccurencesExists(
                array(
                    'fields' => array_merge(
                        $this->Piecemodeletypecourrierpcg66->fields(),
                        $this->Piecemodeletypecourrierpcg66->Modeletypecourrierpcg66->fields()
                    ),
                    'order' => array( 'Piecemodeletypecourrierpcg66.name ASC' )
                )
            );
            $this->paginate = $querydata;
            $piecesmodelestypescourrierspcgs66 = $this->paginate( 'Piecemodeletypecourrierpcg66' );

            $this->_setOptions();
            $this->set( compact('piecesmodelestypescourrierspcgs66'));
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