<?php
	/**
	 * Code source de la classe StatistiquesministeriellesController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses('AppController', 'Controller');

	/**
	 * La classe StatistiquesministeriellesController ...
	 *
	 * @package app.Controller
	 */
	class StatistiquesministeriellesController extends AppController
	{
		/**
		 * Nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Statistiquesministerielles';

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
		public $uses = array( 'Statistiqueministerielle', 'Serviceinstructeur' );

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
				$results = $this->Statistiqueministerielle->indicateursOrientations( $this->request->data );
				$tranches = $this->Statistiqueministerielle->tranches;

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
				$results = $this->Statistiqueministerielle->indicateursReorientations( $this->request->data );
				$tranches = $this->Statistiqueministerielle->tranches;

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
				$results = $this->Statistiqueministerielle->indicateursMotifsReorientation( $this->request->data );
				$tranches = $this->Statistiqueministerielle->tranches;

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
				$results = $this->Statistiqueministerielle->indicateursOrganismes( $this->request->data );

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
				$results = $this->Statistiqueministerielle->indicateursDelais( $this->request->data );
				$types_cers = $this->Statistiqueministerielle->types_cers;

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
				$results = $this->Statistiqueministerielle->indicateursCaracteristiquesContrats( $this->request->data );
				$durees_cers = array_keys( $this->Statistiqueministerielle->durees_cers );

				$this->set( compact( 'results', 'durees_cers' ) );
			}

			$this->set( 'title_for_layout', 'Indicateurs de caractéristiques des contrats' );
		}
	}
?>