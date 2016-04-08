<?php
	/**
	 * Code source de la classe CuisControllerTest.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Case.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses('Controller', 'Controller');
	App::uses('AppController', 'Controller');
	App::uses('CuiController', 'Controller');
	App::uses('SuperFixture', 'SuperFixture.Utility');
	
	/**
	 * La classe CuisControllerTest ...
	 *
	 * @see http://book.cakephp.org/2.0/en/development/testing.html#testing-controllers
	 *
	 * @package app.Test.Case.Controller
	 */
	class CuisControllerTest extends ControllerTestCase
	{
		/**
		 * Préparation du test.
		 */
		public function setUp() {
			parent::setUp();
			$this->controller = $this->generate('Cuis');
		}

		/**
		 * Test de la méthode CuisControllerTest::search()
		 */
		public function testSearch() {
			Faker\Factory::create('fr_FR')->seed(1234);
			$this->_defineConf();
			$this->_setRequestData();
			SuperFixture::load($this, 'Cui');
			
			$this->testAction('/cuis/search', array('data' => $this->controller->request->data, 'method' => 'post') );
			$result = $this->controller->viewVars;
			
			$compareData = $result['results'][9];
			$compareData['Cui']['effetpriseencharge'] = null; // Les dates changes d'une année à l'autre
			$compareData['Cui']['finpriseencharge'] = null;
			$compareData['Cui']['faitle'] = null;
			
			$expected = array(
				'Personne' => array(
					'nom_complet' => 'MR Voisin Éric'
				),
				'Adresse' => array(
					'nomcom' => 'Delannoy-sur-Begue'
				),
				'Dossier' => array(
					'matricule' => '247449598856582',
					'locked' => false
				),
				'Cui' => array(
					'effetpriseencharge' => null,
					'finpriseencharge' => null,
					'id' => (int) 10,
					'personne_id' => (int) 17,
					'faitle' => null
				)
			);
			$this->assertEquals($compareData, $expected, "Différences détectées dans le résultat de la recherche");
		}
		
		protected function _defineConf() {
			Configure::write('Cg.departement', 123);
			Configure::write(
				'ConfigurableQuery.Cuis.search',
				array(
					// 1. Filtres de recherche
					'filters' => array(
						// 1.1 Valeurs par défaut des filtres de recherche
						'defaults' => array(
							'Dossier' => array(
								// Case à cocher "Uniquement la dernière demande RSA pour un même allocataire"
								'dernier' => '1'
							)
						),
						// 1.2 Restriction des valeurs qui apparaissent dans les filtres de recherche
						'accepted' => array(),
						// 1.3 Ne pas afficher ni traiter certains filtres de recherche
						'skip' => array(),
						// 1.4 Filtres additionnels : La personne possède un(e)...
						'has' => array()
					),
					// 2. Recherche
					'query' => array(
						// 2.1 Restreindre ou forcer les valeurs renvoyées par le filtre de recherche
						'restrict' => array(),
						// 2.2 Conditions supplémentaires optionnelles
						'conditions' => array(),
						// 2.3 Tri par défaut
						'order' => array('Personne.nom', 'Personne.prenom', 'Cui.id')
					),
					// 3. Nombre d'enregistrements par page
					'limit' => 10,
					// 4. Lancer la recherche au premier accès à la page ?
					'auto' => false,
					// 5. Résultats de la recherche
					'results' => array(
						// 5.1 Ligne optionnelle supplémentaire d'en-tête du tableau de résultats
						'header' => array(),
						// 5.2 Colonnes du tableau de résultats
						'fields' => array (
							'Dossier.matricule',
							'Personne.nom_complet',
							'Adresse.nomcom',
							'Cui.effetpriseencharge',
							'Cui.finpriseencharge',
							'/Cuis/index/#Cui.personne_id#' => array( 'class' => 'view' ),
						),
						// 5.3 Infobulle optionnelle du tableau de résultats
						'innerTable' => array()
					),
					// 6. Temps d'exécution, mémoire maximum, ...
					'ini_set' => array()
				)
			);
		}
		
		protected function _setRequestData() {
			$this->controller->request->data = array(
				'Search' => array(
//					'Dossier' => array(
//						'dernier' => '1',
//						'dtdemrsa' => '0'
//					),
//					'Situationdossierrsa' => array(
//						'etatdosrsa_choice' => '0'
//					),
//					'Detailcalculdroitrsa' => array(
//						'natpf_choice' => '0'
//					),
//					'Detaildroitrsa' => array(
//						'oridemrsa_choice' => '0'
//					),
//					'Cui66' => array(
//						'dateeligibilite' => '0',
//						'datereception' => '0',
//						'datecomplet' => '0'
//					),
//					'Historiquepositioncui66' => array(
//						'created' => '0'
//					),
//					'Cui' => array(
//						'dateembauche' => '0',
//						'findecontrat' => '0',
//						'effetpriseencharge' => '0',
//						'finpriseencharge' => '0',
//						'decisionpriseencharge' => '0',
//						'faitle' => '0'
//					),
//					'Emailcui' => array(
//						'insertiondate' => '0',
//						'created' => '0',
//						'dateenvoi' => '0'
//					),
//					'Decisioncui66' => array(
//						'datedecision' => '0'
//					),
					'Pagination' => array(
						'nombre_total' => '0'
					)
				)
			);
		}
	}
?>