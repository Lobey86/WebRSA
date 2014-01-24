<?php
	/**
	 * Code source de la classe Criteresdossierspcgs66Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Sanitize', 'Utility' );

	/**
	 * La classe Criteresdossierspcgs66Controller ...
	 *
	 * @package app.Controller
	 */
	class Criteresdossierspcgs66Controller extends AppController
	{
		public $uses = array( 'Criteredossierpcg66', 'Dossierpcg66', 'Option', 'Canton' );
		public $helpers = array( 'Default', 'Default2', 'Locale', 'Csv', 'Search' );

		public $components = array( 'Gestionzonesgeos', 'InsertionsAllocataires', 'Search.SearchPrg' => array( 'actions' => array( 'dossier', 'gestionnaire' ) ), 'Jetons2' );

		/**
		*
		*/

		protected function _setOptions() {

			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
			$this->set( 'typepdo', $this->Dossierpcg66->Typepdo->find( 'list' ) );
			$this->set( 'originepdo', $this->Dossierpcg66->Originepdo->find( 'list' ) );
			$this->set( 'descriptionpdo', $this->Dossierpcg66->Personnepcg66->Traitementpcg66->Descriptionpdo->find( 'list' ) );
            $this->set( 'decisionpdo', $this->Dossierpcg66->Decisiondossierpcg66->Decisionpdo->find( 'list', array( 'conditions' => array( 'Decisionpdo.isactif' => '1')) ) );

			$this->set( 'motifpersonnepcg66', $this->Dossierpcg66->Personnepcg66->Situationpdo->find( 'list', array( 'order' => array( 'Situationpdo.libelle ASC' ), 'conditions' => array( 'Situationpdo.isactif' => '1' ) ) ) );
            $this->set( 'statutpersonnepcg66', $this->Dossierpcg66->Personnepcg66->Statutpdo->find( 'list', array( 'order' => array( 'Statutpdo.libelle ASC' ), 'conditions' => array( 'Statutpdo.isactif' => '1' ) ) ) );

			$this->set( 'orgpayeur', array('CAF'=>'CAF', 'MSA'=>'MSA') );

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

            $this->set( 'polesdossierspcgs66', $this->User->Poledossierpcg66->find(
					'list',
					array(
						'fields' => array(
							'Poledossierpcg66.name'
						),
						'conditions' => array(
							'Poledossierpcg66.isactif' => '1'
						),
                        'order' => array( 'Poledossierpcg66.name ASC', 'Poledossierpcg66.id ASC' )
					)
				)
			);

			$options = $this->Dossierpcg66->enums();

            $this->set( 'natpf', $this->Option->natpf() );

            $this->set( 'listorganismes', $this->Dossierpcg66->Decisiondossierpcg66->Orgtransmisdossierpcg66->find(
                    'list',
                    array(
                        'condition' =>  array( 'Orgtransmisdossierpcg66.isactif' => '1' ),
                        'order' => array( 'Orgtransmisdossierpcg66.name ASC' )
                    )
                )
            );

			$etatdossierpcg = $options['Dossierpcg66']['etatdossierpcg'];
			$this->set( 'exists', array( '1' => 'Oui', '0' => 'Non' ) );

			$options = array_merge(
				$options,
				$this->Dossierpcg66->Personnepcg66->Traitementpcg66->enums()
			);
			$this->set( compact( 'options', 'etatdossierpcg', 'mesCodesInsee' ) );
		}

		/**
		 *
		 * @param string $searchFunction
		 */
		private function _index( $searchFunction ) {

			$this->Gestionzonesgeos->setCantonsIfConfigured();

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );



			$params = $this->request->data;
			if( !empty( $params ) ) {
				$querydata = $this->Criteredossierpcg66->{$searchFunction}(
                    $this->request->data,
                    $mesCodesInsee,
					$mesZonesGeographiques
                );
                // -------------------------------------------------------------

				$querydata = $this->_qdAddFilters( $querydata );
				$querydata['conditions'][] = WebrsaPermissions::conditionsDossier();

                $querydata['fields'][] = $this->Jetons2->sqLocked( 'Dossier', 'locked' );
                $progressivePaginate = !Hash::get( $this->request->data, 'Dossierpcg66.paginationNombreTotal' );

                $this->paginate = $querydata;
				$criteresdossierspcgs66 = $this->paginate( 'Dossierpcg66', array(), array(), $progressivePaginate );

                $vflisteseparator = "\n\r-";
				$this->set( compact( 'criteresdossierspcgs66', 'vflisteseparator' ) );
			}
			else {
                $progressivePaginate = SearchProgressivePagination::enabled( $this->name, $this->action );
				if( !is_null( $progressivePaginate ) ) {
					$this->request->data['Dossierpcg66']['paginationNombreTotal'] = !$progressivePaginate;
				}

				$filtresdefaut = Configure::read( "Filtresdefaut.{$this->name}_{$this->action}" );
				$this->request->data = Set::merge( $this->request->data, $filtresdefaut );

			}

			$this->_setOptions();
			$this->set( 'mesCodesInsee', $this->Gestionzonesgeos->listeCodesInsee() );

			$this->set( 'structuresreferentesparcours', $this->InsertionsAllocataires->structuresreferentes( array( 'optgroup' => true, 'conditions' => array( 'orientation' => 'O' ) ) ) );
			$this->set( 'referentsparcours', $this->InsertionsAllocataires->referents( array( 'prefix' => true ) ) );

			$this->render( $this->action );
		}

		/**
		*
		*/

		public function dossier() {
			$this->_index( 'searchDossier' );
		}

		/**
		*
		*/

		public function gestionnaire() {
			$this->_index( 'searchGestionnaire' );
		}


		/**
		 * Export au format CSV des résultats de la recherche des allocataires transférés.
		 *
		 * @return void
		 */
		public function exportcsv( $searchFunction ) {
			$data = Hash::expand( $this->request->params['named'], '__' );

			$mesZonesGeographiques = (array)$this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$querydata = $this->Criteredossierpcg66->{$searchFunction}(
				$data,
				$mesCodesInsee,
				$mesZonesGeographiques
			);

			unset( $querydata['limit'] );
			$querydata['conditions'][] = WebrsaPermissions::conditionsDossier();

			$results = $this->Dossierpcg66->find( 'all', $querydata );

			$this->_setOptions();

			$this->layout = '';

            $vflisteseparator = "\n\r-";
			$this->set( compact( 'results', 'vflisteseparator' ) );
		}
	}
?>