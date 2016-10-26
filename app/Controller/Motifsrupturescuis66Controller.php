<?php
	/**
	 * Code source de la classe Motifsrupturescuis66Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
    App::uses( 'OccurencesBehavior', 'Model/Behavior' );
	App::uses( 'AppController', 'Controller' );

	/**
	 * La classe Motifsrupturescuis66Controller ...
	 *
	 * @package app.Controller
	 */
	class Motifsrupturescuis66Controller extends AppController
	{
		/**
		 * Nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Motifsrupturescuis66';

		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array(
			'Default',
		);

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Default',
			'Default2',
			'Theme',
			'Xform',
		);

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array(
			'Motifrupturecui66',
			'Option',
		);

		/**
		 * Utilise les droits d'un autre Controller:action
		 * sur une action en particulier
		 *
		 * @var array
		 */
		public $commeDroit = array(
			'view' => 'Motifsrupturescuis66:index',
			'add' => 'Motifsrupturescuis66:edit',
		);

		/**
		 * Méthodes ne nécessitant aucun droit.
		 *
		 * @var array
		 */
		public $aucunDroit = array(

		);

		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'add' => 'create',
			'delete' => 'delete',
			'edit' => 'update',
			'index' => 'read',
			'view' => 'read',
		);

		/**
		*   Ajout à la suite de l'utilisation des nouveaux helpers
		*   - default.php
		*   - theme.php
		*/

		public function index() {
			$this->Motifrupturecui66->Behaviors->attach( 'Occurences' );

            $querydata = $this->Motifrupturecui66->qdOccurencesExists(
                array(
                    'fields' => $this->Motifrupturecui66->fields(),
                    'order' => array( 'Motifrupturecui66.name ASC' )
                )
            );

            $this->paginate = $querydata;
            $motifsrupturescuis66 = $this->paginate('Motifrupturecui66');
            $this->set( compact('motifsrupturescuis66'));
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
                $this->redirect( array( 'controller' => 'motifsrupturescuis66', 'action' => 'index' ) );
            }

			if( !empty( $this->request->data ) ) {
				$this->Motifrupturecui66->create( $this->request->data );
				$success = $this->Motifrupturecui66->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->redirect( array( 'action' => 'index' ) );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->request->data = $this->Motifrupturecui66->find(
					'first',
					array(
						'contain' => false,
						'conditions' => array( 'Motifrupturecui66.id' => $id )
					)
				);
				$this->assert( !empty( $this->request->data ), 'error404' );
			}
			else{
				$this->request->data['Motifrupturecui66']['actif'] = true;
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