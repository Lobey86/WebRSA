<?php
	/**
	 * Code source de la classe Statistiquesministerielles2Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses('AppController', 'Controller');

	/**
	 * La classe Statistiquesministerielles2Controller ...
	 *
	 * @package app.Controller
	 */
	class Statistiquesministerielles2Controller extends AppController
	{
		/**
		 * Nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Statistiquesministerielles2';

		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array( 'Gestionzonesgeos' );

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array( 'Search' );

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array( 'Statistiqueministerielle2', 'Serviceinstructeur' );

		/**
		 * Envoi des données communes pour les moteurs de recherche.
		 */
		public function beforeFilter() {
			parent::beforeFilter();

			$this->set( 'servicesinstructeurs', $this->Serviceinstructeur->find( 'list' ) );
			$this->Gestionzonesgeos->setCantonsIfConfigured();
			$this->set( 'mesCodesInsee', $this->Gestionzonesgeos->listeCodesInsee() );
		}

		/**
		 * Moteur de recherche pour les indicateurs d'orientation.
		 *
		 * @return void
		 */
		public function indicateurs_orientations() {
			if( !empty( $this->request->data ) ) {
				$results = $this->Statistiqueministerielle2->indicateursOrientations( $this->request->data );
				$tranches = $this->Statistiqueministerielle2->tranches;

				$this->set( compact( 'results', 'tranches' ) );
			}

			$this->set( 'title_for_layout', 'Indicateurs d\'orientations' );
		}

		/**
		 * Moteur de recherche pour les indicateurs de réorientation.
		 *
		 * @return void
		 */
		public function indicateurs_reorientations() {
			if( !empty( $this->request->data ) ) {
				$results = $this->Statistiqueministerielle2->indicateursReorientations( $this->request->data );
				$tranches = $this->Statistiqueministerielle2->tranches;

				$this->set( compact( 'results', 'tranches' ) );
			}

			$this->set( 'title_for_layout', 'Indicateurs de réorientations' );
		}

		/**
		 * Moteur de recherche pour les indicateurs de motifs de réorientations.
		 *
		 * @return void
		 */
		public function indicateurs_motifs_reorientation() {
			if( !empty( $this->request->data ) ) {
				$results = $this->Statistiqueministerielle2->indicateursMotifsReorientation( $this->request->data );
				$tranches = $this->Statistiqueministerielle2->tranches;

				$this->set( compact( 'results', 'tranches' ) );
			}

			$this->set( 'title_for_layout', 'Indicateurs de motifs de réorientations' );
		}

		/**
		 * Moteur de recherche pour les indicateurs de motifs de réorientations.
		 *
		 * @return void
		 */
		public function indicateurs_organismes() {
			if( !empty( $this->request->data ) ) {
				$results = $this->Statistiqueministerielle2->indicateursOrganismes( $this->request->data );

				$this->set( compact( 'results' ) );
			}

			$this->set( 'title_for_layout', 'Indicateurs d\'organismes' );
		}

		/**
		 * Moteur de recherche pour les indicateurs de délais.
		 *
		 * @return void
		 */
		public function indicateurs_delais() {
			if( !empty( $this->request->data ) ) {
				$results = $this->Statistiqueministerielle2->indicateursDelais( $this->request->data );
				$types_cers = $this->Statistiqueministerielle2->types_cers;

				$this->set( compact( 'results', 'types_cers' ) );
			}

			$this->set( 'title_for_layout', 'Indicateurs de délais' );
		}

		/**
		 * Moteur de recherche pour les indicateurs de caractéristiques de contrats.
		 *
		 * @return void
		 */
		public function indicateurs_caracteristiques_contrats() {
			if( !empty( $this->request->data ) ) {
				$results = $this->Statistiqueministerielle2->indicateursCaracteristiquesContrats( $this->request->data );
				$durees_cers = array_keys( $this->Statistiqueministerielle2->durees_cers );

				$this->set( compact( 'results', 'durees_cers' ) );
			}

			$this->set( 'title_for_layout', 'Indicateurs de caractéristiques des contrats' );
		}
	}
?>
