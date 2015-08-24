<?php
	/**
	 * Code source de la classe Propositionscuis66.
	 *
	 * @package app.Controller
	 * @license Expression license is undefined on line 11, column 23 in Templates/CakePHP/CakePHP Controller.php.
	 */
	App::uses('AppController', 'Controller');

	/**
	 * La classe Propositionscuis66 ...
	 *
	 * @package app.Controller
	 */
	class Propositionscuis66Controller extends AppController
	{
		/**
		 * Nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Propositionscuis66';

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array( 'Propositioncui66', 'Option' );
		
		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array(
			'Allocataires',
			'DossiersMenus',
			'Fileuploader',
			'Gestionzonesgeos',
			'Jetons2',
			'WebrsaModelesLiesCuis66',
		);

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Ajax2' => array(
				'className' => 'Prototype.PrototypeAjax',
				'useBuffer' => false
			),
			//'Allocataires',
			'Default3' => array(
				'className' => 'Default.DefaultDefault'
			),
			//'Search.SearchForm',
			'Observer' => array(
				'className' => 'Prototype.PrototypeObserver',
				'useBuffer' => true
			),
			'Romev3', 'Cake1xLegacy.Ajax'
		);

		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'index' => 'read',
			'add' => 'create',
			'edit' => 'update',
			'delete' => 'delete',
			'view' => 'read',
			'filelink' => 'view',
			'ajaxfileupload' => 'add',
			'ajaxfiledelete' => 'delete',
			'fileview' => 'view',
			'download' => 'view',
			'impression' => 'view',
		);
		
		/**
		 * Envoi d'un fichier temporaire depuis le formualaire.
		 */
		public function ajaxfileupload() {
			$this->Fileuploader->ajaxfileupload();
		}

		/**
		 * Suppression d'un fichier temporaire.
		 */
		public function ajaxfiledelete() {
			$this->Fileuploader->ajaxfiledelete();
		}

		/**
		 * Visualisation d'un fichier temporaire.
		 *
		 * @param integer $id
		 */
		public function fileview( $id ) {
			$this->Fileuploader->fileview( $id );
		}

		/**
		 * Visualisation d'un fichier stocké.
		 *
		 * @param integer $id
		 */
		public function download( $id ) {
			$this->Fileuploader->download( $id );
		}

		/**
		 * Liste des fichiers liés à une orientation.
		 *
		 * @param integer $id
		 */
		public function filelink( $id ) {
			$query = array(
				'fields' => array(
					'Cui.personne_id',
					'Cui.id'
				),
				'joins' => array(
					$this->Propositioncui66->join( 'Cui66' ),
					$this->Propositioncui66->Cui66->join( 'Cui' ),
				),
				'conditions' => array( 'Propositioncui66.id' => $id )
			);
			$result = $this->Propositioncui66->find( 'first', $query );
			$personne_id = $result['Cui']['personne_id'];
			$cui_id = $result['Cui']['id'];
			
			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) );

			$this->Fileuploader->filelink( $id, array( 'action' => 'index', $cui_id ) );
			$urlmenu = "/cuis66/index/{$personne_id}";
			
			$options = $this->Propositioncui66->enums();
			$this->set( compact( 'options', 'dossierMenu', 'urlmenu' ) );
		}
		
		/**
		 * Liste des avis techniques
		 * 
		 * @param integer $cui_id
		 */
		public function index( $cui_id ) {
			$params = array(
				'modelClass' => 'Propositioncui66',
				'urlmenu' => "/Cuis66/index/#0.Cui.personne_id#"
			);
			$customQuery['fields'][] = $this->Propositioncui66->Fichiermodule->sqNbFichiersLies( $this->Propositioncui66, 'nombre' );
			
			$this->WebrsaModelesLiesCuis66->index( $cui_id, $params, $customQuery );
			
			//$this->Cui->Fichiermodule->sqNbFichiersLies( $this->Cui, 'nombre' ),
		}
		
		/**
		 * Visualisation d'un avis technique
		 * 
		 * @param integer $id
		 */
		public function view( $id ) {
			$params = array(
				'modelClass' => 'Propositioncui66',
				'urlmenu' => "/Cuis66/index/#Cui.personne_id#"
			);
			return $this->WebrsaModelesLiesCuis66->view( $id, $params );
		}
			
		/**
		 * Formulaire d'ajout d'avis technique CUI
		 *
		 * @param integer $cui_id L'id du CUI
		 */
		public function add( $cui_id ) {
			$args = func_get_args();
			call_user_func_array( array( $this, 'edit' ), $args );
		}
		
		/**
		 * Méthode générique d'ajout et de modification d'avis technique
		 *
		 * @param integer $id L'id du CUI (add) ou de la proposition (edit)
		 */
		public function edit( $id = null ) {
			$params = array(
				'modelClass' => 'Propositioncui66',
				'view' => 'edit',
				'redirect' => "/Propositionscuis66/index/#Cui.id#",
				'urlmenu' => "/Cuis66/index/#Cui.personne_id#"
			);
			return $this->WebrsaModelesLiesCuis66->addEdit( $id, $params );
		}
		
		/**
		 * Suppression d'un avis technique
		 * 
		 * @param integer $id
		 * @return boolean
		 */
		public function delete( $id ){
			return $this->WebrsaModelesLiesCuis66->delete( $id );
		}
		
		/**
		 * Impression générique
		 * 
		 * @param integer $id
		 * @return boolean
		 */
		public function impression( $id ){
			return $this->WebrsaModelesLiesCuis66->impression( $id );
		}
		
		/**
		 * Impression avis élu
		 * 
		 * @param integer $id
		 * @return boolean
		 */
		public function impression_aviselu( $id ){
			return $this->WebrsaModelesLiesCuis66->impression( $id, 'aviselu' );
		}
	}
?>