<?php
	/**
	 * Code source de la classe SituationsdossiersrsaController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe SituationsdossiersrsaController ...
	 *
	 * @package app.Controller
	 */
	class SituationsdossiersrsaController extends AppController
	{
		public $name = 'Situationsdossiersrsa';

		public $uses = array( 'Situationdossierrsa',  'Option' , 'Dossier', 'Suspensiondroit',  'Suspensionversement');

		public $components = array( 'Jetons2', 'DossiersMenus' );

		public $commeDroit = array(
			'view' => 'Situationsdossiersrsa:index'
		);

		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'index' => 'read',
			'view' => 'read',
		);

		/**
		 *
		 */
		public function beforeFilter() {
			parent::beforeFilter();
			$this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
			$this->set( 'moticlorsa', $this->Option->moticlorsa() );
			$this->set( 'motisusdrorsa', $this->Option->motisusdrorsa() );
			$this->set( 'motisusversrsa', $this->Option->motisusversrsa() );
		}

		/**
		 *
		 * @param integer $dossier_id
		 */
		public function index( $dossier_id = null ){
			// Vérification du format de la variable
			$this->assert( valid_int( $dossier_id ), 'error404' );

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'id' => $dossier_id ) ) );

			$situationdossierrsa = $this->Situationdossierrsa->find(
				'first',
				array(
					'conditions' => array(
						'Situationdossierrsa.dossier_id' => $dossier_id
					),
					'contain' => array(
						'Dossier',
						'Suspensiondroit',
						'Suspensionversement'
					)
				)
			) ;
			// Assignations à la vue
			$this->set( 'dossier_id', $dossier_id );
			$this->set( 'situationdossierrsa', $situationdossierrsa );
		}

		/**
		 *
		 * @param integer $situationdossierrsa_id
		 */
		public function view( $situationdossierrsa_id = null ) {
			// Vérification du format de la variable
			$this->assert( valid_int( $situationdossierrsa_id ), 'error404' );

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'id' => $this->Situationdossierrsa->dossierId( $situationdossierrsa_id ) ) ) );

			$situationdossierrsa = $this->Situationdossierrsa->find(
				'first',
				array(
					'conditions' => array(
						'Situationdossierrsa.id' => $situationdossierrsa_id
					),
				'recursive' => -1
				)
			);
			$this->assert( !empty( $situationdossierrsa ), 'error404' );

			// Assignations à la vue
			$this->set( 'dossier_id', $situationdossierrsa['Situationdossierrsa']['dossier_id'] );
			$this->set( 'situationdossierrsa', $situationdossierrsa );
		}
	}

?>