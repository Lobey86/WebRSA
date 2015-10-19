<?php
	/**
	 * Code source de la classe WebrsaRecherche.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Controller', 'Controller' );
	App::uses( 'AppController', 'Controller' );
	App::uses( 'Folder', 'Utility' );

	/**
	 * La classe WebrsaRecherche ...
	 *
	 * @package app.Model
	 */
	class WebrsaRecherche extends AppModel
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaRecherche';

		/**
		 * Pas de table liée.
		 *
		 * @var integer
		 */
		public $useTable = false;

		/**
		 * Liste des moteurs de recherche, exports CSV, cohortes, par département,
		 * utilisé dans la vérification de l'application.
		 *
		 * @todo ajout des clés décrivant le ou les départements utilisateurs
		 * @todo: utiliser ces informations dans les contrôleurs ?
		 * @todo Voir l'onglet "Environnement logiciel" > "WebRSA" > "Champs spécifiés dans
		 * le webrsa.inc" de la vérification de l'application.
		 *
		 * @var array
		 */
		public $searches = array(
			'Contratsinsertion.search' => array(
				'modelName' => 'Contratinsertion',
				'component' => 'WebrsaRecherchesContratsinsertionNew',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Contratsinsertion.exportcsv' => array(
				'modelName' => 'Contratinsertion',
				'component' => 'WebrsaRecherchesContratsinsertionNew',
				'keys' => array( 'results.fields' )
			),
			'Dossiers.search' => array(
				'modelName' => 'Dossier',
				'component' => 'WebrsaRecherchesDossiersNew',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Dossiers.exportcsv' => array(
				'modelName' => 'Dossier',
				'component' => 'WebrsaRecherchesDossiersNew',
				'keys' => array( 'results.fields' )
			),
			'Dsps.search' => array(
				'modelName' => 'Personne',
				'modelRechercheName' => 'WebrsaRechercheDsp',
				'component' => 'WebrsaRecherchesDspsNew',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Dsps.exportcsv' => array(
				'modelName' => 'Personne',
				'modelRechercheName' => 'WebrsaRechercheDsp',
				'component' => 'WebrsaRecherchesDspsNew',
				'keys' => array( 'results.fields' )
			),
			'Demenagementshorsdpts.search' => array(
				'modelName' => 'Personne',
				'modelRechercheName' => 'WebrsaRechercheDemenagementhorsdpt',
				'component' => 'WebrsaRecherchesDemenagementshorsdptsNew',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Demenagementshorsdpts.exportcsv' => array(
				'modelName' => 'Personne',
				'modelRechercheName' => 'WebrsaRechercheDemenagementhorsdpt',
				'component' => 'WebrsaRecherchesDemenagementshorsdptsNew',
				'keys' => array( 'results.fields' )
			),
			'Entretiens.search' => array(
				'modelName' => 'Entretien',
				'component' => 'WebrsaRecherchesEntretiensNew',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Entretiens.exportcsv' => array(
				'modelName' => 'Entretien',
				'component' => 'WebrsaRecherchesEntretiensNew',
				'keys' => array( 'results.fields' )
			),
			'Indus.search' => array(
				'modelName' => 'Dossier',
				'component' => 'WebrsaRecherchesIndusNew',
				'modelRechercheName' => 'WebrsaRechercheIndu',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Indus.exportcsv' => array(
				'modelName' => 'Dossier',
				'component' => 'WebrsaRecherchesIndusNew',
				'modelRechercheName' => 'WebrsaRechercheIndu',
				'keys' => array( 'results.fields' )
			),
			'Orientsstructs.search' => array(
				'modelName' => 'Orientstruct',
				'component' => 'WebrsaRecherchesOrientsstructsNew',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Orientsstructs.exportcsv' => array(
				'modelName' => 'Orientstruct',
				'component' => 'WebrsaRecherchesOrientsstructsNew',
				'keys' => array( 'results.fields' )
			),
			'Propospdos.search' => array(
				'modelName' => 'Propopdo',
				'component' => 'WebrsaRecherchesPropospdosNew',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Propospdos.exportcsv' => array(
				'modelName' => 'Propopdo',
				'component' => 'WebrsaRecherchesPropospdosNew',
				'keys' => array( 'results.fields' )
			),
			'Propospdos.search_possibles' => array(
				'modelName' => 'Personne',
				'modelRechercheName' => 'WebrsaRecherchePropopdoPossible',
				'component' => 'WebrsaRecherchesPropospdosPossiblesNew',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Propospdos.exportcsv_possibles' => array(
				'modelName' => 'Personne',
				'modelRechercheName' => 'WebrsaRecherchePropopdoPossible',
				'component' => 'WebrsaRecherchesPropospdosPossiblesNew',
				'keys' => array( 'results.fields' )
			),
			'Rendezvous.search' => array(
				'modelName' => 'Rendezvous',
				'component' => 'WebrsaRecherchesRendezvousNew',
				'keys' => array( 'results.fields', 'results.innerTable' )
			),
			'Rendezvous.exportcsv' => array(
				'modelName' => 'Rendezvous',
				'component' => 'WebrsaRecherchesRendezvousNew',
				'keys' => array( 'results.fields' )
			),
		);

		/**
		 * Live cache.
		 *
		 * Clés "config" et "query"
		 *
		 * @var array
		 */
		protected $_cache = array();

		protected function _loadCache() {
			if( $this->_cache === array() ) {
				$this->_cache = array();
				$this->_includeConfigFiles();

				$currentDepartement = (int)Configure::read( 'Cg.departement' );

				foreach( $this->searches as $key => $config ) {
					$departement = Hash::get( $config, 'departement' );
					if( $departement === null || in_array( $currentDepartement, (array)$departement ) ) {
						$Recherches = $this->_component( $key, $config );

						$this->_cache[$key]['config'] = $Recherches->configureKeys( $config );
						$this->_cache[$key]['fields'] = $Recherches->checkConfiguredFields( $config );
						$this->_cache[$key]['query'] = $Recherches->checkQuery( $config );
					}
				}
			}
		}

		// @deprecated
		public function configureKeys() {
			$this->_loadCache();

			$result = array();
			foreach( $this->_cache as $cache ) {
				$result = array_merge( $result, $cache['config'] );
			}

			return $result;
		}

		public function checks() {
			$this->_loadCache();

			return $this->_cache;
		}

		/**
		 * Fonction utilitaire permettant de charger l'ensemble des fichiers de
		 * configuration se trouvant dans le répertoire du département connecté:
		 * app/Config/CgXXX (où XXX représente le n° du département)
		 *
		 * @param integer $departement
		 */
		protected function _includeConfigFiles() {
			$path = APP.'Config'.DS.'Cg'.Configure::read( 'Cg.departement' );

			$Dir = new Folder( $path );
			foreach( $Dir->find( '.*\.php' ) as $file ) {
				include_once $path.DS.$file;
			}
		}

		protected function _component( $key, array $config ) {
			list($controllerName, $actionName) = explode( '.', $key );
			$url = array( 'controller' => Inflector::underscore( $controllerName ), 'action' => Inflector::underscore( $actionName) );
			$Request = new CakeRequest( "{$url['controller']}/{$url['action']}", false );
			$Request->addParams( $url );

			$Controller = new AppController( $Request, new CakeResponse() );

			$Controller->name = $controllerName;
			$Controller->action = $actionName;
			$Controller->modelClass = $config['modelName'];
			$Controller->uses = array( $Controller->modelClass, Inflector::classify( $controllerName), $config['modelName'], 'Jeton', 'User' );
			$Controller->components = array( 'Session', 'Jetons2', 'InsertionsAllocataires', 'Gestionzonesgeos' );
			$Controller->helpers = array(
				'Default3' => array(
					'className' => 'ConfigurableQuery.ConfigurableQueryDefault'
				)
			);

			$Controller->constructClasses();
			$Controller->Jetons2->initialize( $Controller );

			$config += array( 'configurableQueryFieldsKey' => $key );
			$Recherches = $Controller->Components->load( $config['component'] );

			return $Recherches;
		}

		/**
		 * Exécute les différentes méthods du modèle permettant la mise en cache.
		 * Utilisé au préchargement de l'application (/prechargements/index).
		 *
		 * @return boolean true en cas de succès, false en cas d'erreur,
		 * 	null pour les fonctions vides.
		 */
		public function prechargement() {
			$currentDepartement = (int)Configure::read( 'Cg.departement' );
			$this->_includeConfigFiles();

			foreach( $this->searches as $key => $config ) {
				$departement = Hash::get( $config, 'departement' );
				if( $departement === null || in_array( $currentDepartement, (array)$departement ) ) {
					echo "\t{$key}\n";
					$Recherches = $this->_component( $key, $config );

					if( strpos( $key, '.search' ) !== false ) {
						$Recherches->search( $config );
					}
					else {
						$Recherches->exportcsv( $config );
					}
				}
			}

			return true;
		}
	}
?>