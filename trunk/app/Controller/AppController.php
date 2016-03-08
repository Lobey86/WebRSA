<?php
	/**
	 * Fichier source de la classe AppController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 */
	App::uses( 'Controller', 'Controller' );
	App::uses( 'SearchProgressivePagination', 'Search.Utility' );

	/**
	 * Classe de base de tous les contrôleurs de l'application.
	 *
	 * @package app.Controller
	 */
	class AppController extends Controller
	{
		/**
		 * Components utilisés
		 *
		 * @var array
		 */
		public $components = array( 'Session', 'Auth', 'Acl' );

		/**
		 * Helpers utilisés
		 *
		 * @var array
		 */
		public $helpers = array(
			'Xhtml',
			'Form',
			'Permissions',
			'Locale',
			'Default',
			'Xpaginator',
			'Gestionanomaliebdd',
			'Menu',
			'Observer' => array(
				'className' => 'Prototype.PrototypeObserver',
				'useBuffer' => false
			)
		);

		/**
		 * Modèles utilisés
		 *
		 * @var array
		 */
		public $uses = array( 'User', 'Connection' );

		/**
		 * @fixme
		 *
		 * @var array
		 */
		public $commeDroit = array();

		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array();

		/**
		 * Méthode temporaire permettant de continuer à utiliser AppController::cakeError() durant la
		 * migration.
		 *
		 * @param string $method
		 * @param array $messages
		 * @return boolean
		 */
		public function cakeError( $method, $messages = array() ) {
			return $this->assert( false, $method, $messages );
		}

		/**
		* INFO:
		*   cake/libs/error.php
		*   cake/libs/view/errors/
		*/
		public function assert( $condition, $error = 'error500', $parameters = array( ) ) {
			if( $condition !== true ) {
				$calledFrom = debug_backtrace();
				$calledFromFile = substr( str_replace( ROOT, '', $calledFrom[0]['file'] ), 1 );
				$calledFromLine = $calledFrom[0]['line'];

				$this->log( 'Assertion failed: '.$error.' in '.$calledFromFile.' line '.$calledFromLine.' for url '.$this->request->here );

				// Need to finish transaction ?
				if( isset( $this->{$this->modelClass} ) ) {
					$db = $this->{$this->modelClass}->getDataSource();
					$db->rollback( $this->{$this->modelClass} );
				}

				$exceptionClass = "{$error}Exception";
				if( class_exists( $exceptionClass, false ) ) {
					throw new $exceptionClass( $error );
				}
				else {
					throw new InternalErrorException( $error );
				}

				exit();
			}
		}

		/**
		 * Fait-on une pagination standard ou une pagination progressive ?
		 *
		 * @param type $object
		 * @param type $scope
		 * @param type $whitelist
		 * @param type $progressivePaginate
		 * @return type
		 */
		public function paginate( $object = null, $scope = array( ), $whitelist = array( ), $progressivePaginate = null ) {
			return $this->Components->load( 'Search.SearchPaginator', $this->paginate )->paginate( $object, $scope, $whitelist, $progressivePaginate );
		}

		/**
		 * Il faut décharger les paginator dans une boucle en CakePHP 2.x sinon
		 * ça ne fonctionne pas correctement.
		 */
		public function refreshPaginator() {
			$this->Components->unload( 'Search.ProgressivePaginator' );
			$this->Components->unload( 'Paginator' );
		}

		/**
		 * Permet de rajouter des conditions aux conditions de recherches suivant
		 * le paramétrage des service référent dont dépend l'utilisateur connecté.
		 *
		 * Nécessite la mise à true du paramètre 'Recherche.qdFilters.Serviceinstructeur'
		 * ainsi que l'ajout de conditions au service instructeur de l'utilisateur
		 * connecté.
		 *
		 * Utilisé pour l'injection de conditions pour la confidentialité au CG 58.
		 *
		 * @param array $querydata Les querydata dans lesquelles rajouter les conditionss
		 * @return array
		 */
		protected function _qdAddFilters( $querydata ) {
			if( Configure::read( 'Recherche.qdFilters.Serviceinstructeur' ) ) {
				$sqrecherche = $this->Session->read( 'Auth.Serviceinstructeur.sqrecherche' );
				if( !empty( $sqrecherche ) ) {
					$querydata['conditions'][] = $sqrecherche;
				}
			}

			return $querydata;
		}

		/**
		 * Vérification des habilitations de l'utilisateur connecté.
		 *
		 * @return void
		 */
		protected function _checkHabilitations() {
			$habilitations = array(
				'date_deb_hab' => $this->Session->read( 'Auth.User.date_deb_hab' ),
				'date_fin_hab' => $this->Session->read( 'Auth.User.date_fin_hab' )
			);

			$error = (
				( !empty( $habilitations['date_deb_hab'] ) && ( strtotime( $habilitations['date_deb_hab'] ) >= time() ) )
				// Si la date d'habilitation est celle du jour il n'est plus habilité du tout
				|| ( !empty( $habilitations['date_fin_hab'] ) && ( strtotime( $habilitations['date_fin_hab'] ) < time() ) )
			);

			if( $error ) {
				throw new DateHabilitationUserException(
					'Mauvaises dates d\'habilitation de l\'utilisateur',
					401,
					array( 'habilitations' => $habilitations )
				);
			}
		}

		/**
		 * Utilisateurs concurrents, mise à jour du dernier accès pour la connection, au sein d'une transaction.
		 * Si la session a expiré, on redirige sur UsersController::logout
		 *
		 * @return void
		 */
		protected function _updateConnection() {
			if( Configure::read( 'Utilisateurs.multilogin' ) == false ) {
				if( !( $this->name == 'Users' && in_array( $this->action, array( 'login', 'logout', 'forgottenpass' ) ) ) ) {
					$connection_id = $this->Connection->field(
						'id',
						array(
							'user_id' => $this->Session->read( 'Auth.User.id' ),
							'php_sid' => $this->Session->id(),
							'( Connection.modified + INTERVAL \''.readTimeout().' seconds\' ) >= NOW()'
						)
					);

					if( !empty( $connection_id ) ) {
						$this->Connection->id = $connection_id;
						$this->Connection->saveField( 'modified', null );
					}
					else {
						$this->redirect( array( 'controller' => 'users', 'action' => 'logout' ) );
					}
				}
			}
		}

		/**
		 * Vérifie que l'utilisateur a la permission d'accéder à la page.
		 *
		 * @see WebrsaPermissions::check()
		 *
		 * @return void
		 */
		protected function _checkPermissions() {
			$plugin = Inflector::camelize( $this->request->params['plugin'] );
			$controller = empty( $plugin ) ? $this->name : "{$plugin}.{$this->name}";

			// FIXME
			if( $controller === 'Fluxcnaf.Fluxcnaf' ) {
				return;
			}

			if( !WebrsaPermissions::check( $controller, $this->action ) ) {
				throw new error403Exception( null );
			}
		}

		/**
		 * Retourne un tableau contenant un booléen pour chacune des clés suivantes
		 * permettant de savoir si l'appel à l'URL actuelle est un appel "classique"
		 * ou non (login, logout, forgottenPass, allo, requested, ajax).
		 *
		 * @return array
		 */
		protected function _is() {
			return array(
				'login' => ( substr( $_SERVER['REQUEST_URI'], strlen( $this->request->base ) ) == '/users/login' ),
				'logout' => ( substr( $_SERVER['REQUEST_URI'], strlen( $this->request->base ) ) == '/users/logout' ),
				'forgottenPass' => ( substr( $_SERVER['REQUEST_URI'], strlen( $this->request->base ) ) == '/users/forgottenpass' ),
				'allo' => ( strpos( substr( $_SERVER['REQUEST_URI'], strlen( $this->request->base ) ), '/api/rest/allo/' ) === 0 ),
				'requested' => isset( $this->request->params['requested'] ),
				'ajax' => isset( $this->request->params['isAjax'] )
			);
		}

		/**
		 * Permet de modifier à la volée certaines valeurs de du fichier php.ini
		 * via la commande ini_set.
		 * Configuré dans le fichier webrsa.inc: <Contrôleur>.<action>.ini_set
		 */
		protected function _iniSet() {
			$path = "{$this->name}.{$this->action}.ini_set";
			$configuration = Configure::read( $path );

			if( $configuration !== null && is_array( $configuration ) ) {
				foreach( $configuration as $varname => $newvalue ) {
					if( ini_set( $varname, $newvalue ) === false ) {
						$msgstr = 'Erreur lors de la configuration de %s.%s à la valeur \'%s\'';
						$this->log( sprintf( $msgstr, $path, $varname, $newvalue ) );
					}
				}
			}
		}

		/**
		 * @return void
		 */
		public function beforeFilter() {
			// Désactivation du cache du navigateur: (quand on revient en arrière dans l'historique de
			// navigation, la page n'est pas cachée du côté du navigateur, donc il ré-exécute la demande)
			// TODO, et vérifier que ça n'entraîne pas de bug avec une autre conf / en production:
			// Désactivation uniquement sur les méthode faisant autre chose que de la lecture ?
			//if( Hash::get( $this->crudMap, $this->request->action ) !== 'read' )
			$this->disableCache();

			//Paramétrage du composant Auth
			$this->Auth->loginAction = array( 'controller' => 'users', 'action' => 'login' );
			$this->Auth->logoutRedirect = array( 'controller' => 'users', 'action' => 'login' );
			$this->Auth->loginRedirect = array( 'controller' => 'dossiers', 'action' => 'index' );
			$this->Auth->authorize = array( 'Actions' => array( 'actionPath' => 'controllers' ) );

			$this->set( 'etatdosrsa', ClassRegistry::init( 'Option' )->etatdosrsa() );
			$return = parent::beforeFilter();

			$this->Auth->allow( '*' );

			$is = $this->_is();

			// Fin du traitement pour les requestactions et les appels ajax
			if( $is['requested'] ) {
				return $return;
			}

			// Utilise-t'on l'alerte de fin de session ?
			$useAlerteFinSession = (
				!$is['login']
				&& !$is['forgottenPass']
				&& ( Configure::read( "alerteFinSession" ) )
				&& ( Configure::read( 'debug' ) == 0 )
			);
			$this->set( 'useAlerteFinSession', $useAlerteFinSession );

			if( !$is['login'] && !$is['logout'] && !$is['forgottenPass'] && !$is['allo'] ) {
				if( !$this->Session->check( 'Auth' ) || !$this->Session->check( 'Auth.User' ) ) {
					//le forcer a se connecter
					$this->redirect( array( 'controller' => 'users', 'action' => 'login' ) );
				}
				else {
					$this->_updateConnection();

					if( !$is['ajax'] ) {
						$this->_checkHabilitations();
						$this->_checkPermissions();
					}
				}
			}

			// Chargement du fichier de configuration lié au contrôleur, s'il existe
			$path = APP.'Config'.DS.'Cg'.Configure::read( 'Cg.departement' ).DS.$this->name.'.php';
			if( file_exists( $path ) ) {
				include_once $path;
			}

			$this->_iniSet();

			return $return;
		}

		/**
		 * Fonction utilisataire permettant de mettre en message flash le résultat des actions Save et Delete.
		 *
		 * @param string $message
		 * @param boolean $result
		 * @return void
		 */
		protected function _setFlashResult( $message, $result ) {
			$class = ( $result ? 'success' : 'error' );
			$this->Session->setFlash(
					__( "{$message}->{$class}" ), 'default', array( 'class' => $class )
			);
		}

		/**
		 * Lorsque la configuration de AncienAllocataire.enabled est à true, envoie
		 * la liste des dossiers dans lesquels l'allocataire est à présent sans
		 * prestation mais pour lesquels au moins un enregistrement du modèle existe.
		 *
		 * @param integer $personne_id
		 * @param string $modelAlias
		 */
		protected function _setEntriesAncienDossier( $personne_id, $modelAlias ) {
			if( Configure::read( 'AncienAllocataire.enabled' ) ) {
				$this->helpers['Default3'] = array( 'className' => 'Default.DefaultDefault' );
				$entriesAncienDossier = ClassRegistry::init( 'Personne' )->getEntriesAnciensDossiers( $personne_id, $modelAlias );
				$this->set( compact( 'entriesAncienDossier' ) );
			}
		}

		/**
		 * Called after the controller action is run, but before the view is rendered. You can use this method
		 * to perform logic or set view variables that are required on every request.
		 *
		 * @return void
		 * @link http://book.cakephp.org/2.0/en/controllers.html#request-life-cycle-callbacks
		 */
		public function beforeRender() {
			parent::beforeRender();

			// Affiche un cadenas avec le nombre de jetons pris par l'utilisateur
			$is = $this->_is();
			if (!Configure::read('Jetons2.disabled') && Configure::read('Etatjetons.enabled') && !in_array( true, $is )) {
				$this->set('jetons_count', $this->Components->load('Jetons2')->count());
			}
			
			// Envoi des données au menu
			$menuData = array();
			if (Configure::read('Module.Savesearch.enabled') && Configure::read('Module.Savesearch.mon_menu.enabled')) {
				$menuData['mon_menu'] = $this->_getMonMenu();
			}
			$this->set('main_navigation_menu_data', $menuData);
		}
		
		/**
		 * Permet de récupérer les données formattés à envoyer au menu
		 * ex: array(
		 *		'Le titre' => array('url' => array('controller' => 'nom_du_controller', 'action' => 'nom_de_action' ),
		 *		...
		 * )
		 * 
		 * @return array
		 */
		protected function _getMonMenu() {
			$cache = $this->Session->read('Module.Monmenu');
			
			if (!$cache) {
				$user_id = $this->Session->read('Auth.User.id');
				$savedSearch = ClassRegistry::init('Savesearch')->find('all',
					array(
						'fields' => array(
							'Savesearch.user_id',
							'Savesearch.controller',
							'Savesearch.action',
							'Savesearch.url',
							'Savesearch.name',
						),
						'conditions' => array(
							'Savesearch.isformenu' => 1,
							'OR' => array(
								'Savesearch.user_id' => $user_id,
								array(
									'Savesearch.group_id' => $this->Session->read('Auth.User.group_id'),
									'Savesearch.isforgroup' => 1,
								),
							)
						)
					)
				);
				
				$cache = array();
				foreach ((array)$savedSearch as $saved) {
					$s =& $saved['Savesearch'];
					$params = (substr($s['url'], strlen('/'.$s['controller'].'/'.$s['action'].'/')));
					
					// FIXME : Controle des permissions dans le menu impossible avec une url valide
					if (!WebrsaPermissions::check($s['controller'], $s['action'])) {
						continue;
					}
					
					$menu = array(
						'url' => $s['url']
//						'url' => array(
//							'controller' => $s['controller'],
//							'action' => $s['action'],
//						)
					);
//					$menu['url'] = array_merge($menu['url'], explode('/', $params));
					
					if ($s['user_id'] == $user_id) {
						$cache['Sauvegardes personnelles'][$s['name']] = $menu;
					} else {
						$cache['Sauvegardes de groupe'][$s['name']] = $menu;
					}
				}
				
				// Suppression du cache du menu
				Cache::delete('element_'.$this->Session->read('Auth.User.username'), 'views');
				
				$this->Session->write('Module.Monmenu', $cache);
			}
			
			return $cache;
		}
	}
?>