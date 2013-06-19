<?php
	/**
	 * Code source de la classe Criterestraitementspcgs66Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::import('Sanitize');

	/**
	 * La classe Criterestraitementspcgs66Controller ...
	 *
	 * @package app.Controller
	 */
	class Criterestraitementspcgs66Controller extends AppController
	{
		public $uses = array( 'Criteretraitementpcg66', 'Traitementpcg66', 'Option' );
		public $helpers = array( 'Default', 'Default2', 'Locale', 'Csv', 'Search' );

		public $components = array( 'Gestionzonesgeos','Search.Prg' => array( 'actions' => array( 'index' ) ) );

		/**
		*
		*/

		protected function _setOptions() {
			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
			$this->set( 'typepdo', $this->Traitementpcg66->Personnepcg66->Dossierpcg66->Typepdo->find( 'list' ) );
			$this->set( 'originepdo', $this->Traitementpcg66->Personnepcg66->Dossierpcg66->Originepdo->find( 'list' ) );
			$this->set( 'descriptionpdo', $this->Traitementpcg66->Descriptionpdo->find( 'list' ) );
			$this->set( 'motifpersonnepcg66', $this->Traitementpcg66->Personnepcg66->Situationpdo->find( 'list' ) );
			$this->set( 'statutpersonnepcg66', $this->Traitementpcg66->Personnepcg66->Statutpdo->find( 'list' ) );

			$this->set( 'gestionnaire', $this->User->find(
					'list',
					array(
						'fields' => array(
							'User.nom_complet'
						),
						'conditions' => array(
							'User.isgestionnaire' => 'O'
						),
                        'order' => array( 'User.nom ASC', 'User.prenom ASC' )
					)
				)
			);

			$options = $this->Traitementpcg66->enums();
            $this->set( 'natpf', $this->Option->natpf() );
// 			$etatdossierpcg = $options['Traitementpcg66']['etatdossierpcg'];
//
// 			$options = array_merge(
// 				$options,
// 				$this->Traitementpcg66->Personnepcg66->Traitementpcg66->enums()
// 			);
			$this->set( compact( 'options' ) );
			$this->set( 'exists', array( '1' => 'Oui', '0' => 'Non' ) );
		}

		/**
		*
		*/

		public function index() {
			$this->Gestionzonesgeos->setCantonsIfConfigured();

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );


			$params = $this->request->data;
			if( !empty( $params ) ) {
				$paginate = array( 'Traitementpcg66' => $this->Criteretraitementpcg66->search( $this->request->data, $mesCodesInsee,
					$mesZonesGeographiques ) );
				$paginate['Traitementpcg66']['limit'] = 10;

				$this->paginate = $paginate;
				$criterestraitementspcgs66 = $this->paginate( 'Traitementpcg66' );

				$this->set( compact( 'criterestraitementspcgs66' ) );
			}

			$this->_setOptions();
			$this->set( 'mesCodesInsee', $this->Gestionzonesgeos->listeCodesInsee() );
			$this->render( $this->action );
		}

	}
?>