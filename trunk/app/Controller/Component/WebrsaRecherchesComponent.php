<?php
	/**
	 * Code source de la classe WebrsaRecherchesComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'ConfigurableQueryFields', 'ConfigurableQuery.Utility' );

	/**
	 * La classe WebrsaRecherchesComponent ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaRecherchesComponent extends Component
	{
		/**
		 * Paramètres de ce component
		 *
		 * @var array
		 */
		public $settings = array();

		/**
		 * Components utilisés par ce component.
		 *
		 * @var array
		 */
		public $components = array(
			'Allocataires'
		);

		/**
		 * Retourne un array avec clés de paramètres suivantes complétées en
		 * fonction du contrôleur:
		 *	- modelName: le nom du modèle sur lequel se fera la pagination
		 *	- modelRechercheName: le nom du modèle de moteur de recherche
		 *	- searchKey: le préfixe des filtres renvoyés par le moteur de recherche
		 *	- searchKeyPrefix: le préfixe des champs configurés
		 *	- configurableQueryFieldsKey: les clés de configuration contenant les
		 *    champs à sélectionner dans la base de données.
		 *
		 * @todo protected ?
		 *
		 * @param array $params
		 * @return array
		 */
		public function params( array $params = array() ) {
			$Controller = $this->_Collection->getController();

			$params += array(
				'modelName' => $Controller->modelClass,
				'modelRechercheName' => 'WebrsaRecherche'.$Controller->modelClass,
				'searchKey' => 'Search',
				'searchKeyPrefix' => 'ConfigurableQuery',
				'configurableQueryFieldsKey' => "{$Controller->name}.{$Controller->request->params['action']}"
			);

			return $params;
		}

		/**
		 * Retourne les options à envoyer dans la vue pour les champs du moteur
		 * de recherche et les traductions de valeurs de certains champs.
		 *
		 * @param array $params
		 * @return array
		 */
		public function options( array $params = array() ) {
			$Controller = $this->_Collection->getController();
			$params = $this->params( $params );

			return Hash::merge(
				$this->Allocataires->options(),
				$Controller->{$params['modelName']}->enums()
			);
		}

		// @todo protected ?
		// TODO: tant qu'on est dans le cache, une méthode pour préparer les données (types, options statiques, libellés)
		public function getQuery( $keys, array $params = array() ) {
			$keys = (array)$keys;
			$Controller = $this->_Collection->getController();
			$params = $this->params( $params );

			// FIXME: on n'a pas la bonne clé de cache en production ?!?
			$cacheKey = $Controller->{$params['modelName']}->useDbConfig.'_'.$Controller->name.'_'.$Controller->action.'_'.$Controller->{$params['modelName']}->alias.'_searchQuery';
			$query = Cache::read( $cacheKey );

			if( $query === false ) {
				$query = $Controller->{$params['modelRechercheName']}->searchQuery();
				$query = ConfigurableQueryFields::getFieldsByKeys( $keys, $query );

				Cache::write( $cacheKey, $query );
			}

			return $query;
		}

		/**
		 * Retourne le querydata complété par les conditions du moteur de recherche,
		 * ainsi que des conditions liées à l'utilisateur connecté.
		 *
		 * @todo protected ?
		 *
		 * @param array $query
		 * @param array $params
		 * @return array
		 */
		public function getQueryConditions( array $query, array $params = array()  ) {
			$Controller = $this->_Collection->getController();
			$params = $this->params( $params );

			$query = $Controller->{$params['modelRechercheName']}->searchConditions( $query, (array)Hash::get( $Controller->request->data, $params['searchKey'] ) );
			$query = $this->Allocataires->completeSearchQuery( $query, $params );

			return $query;

		}

		/**
		 * Ajoute des order by en fonction du paramétrage.
		 * Dans le cas d'un exportcsv, on ne modifi pas l'ordre affiché dans le
		 * moteur de recherche.
		 *
		 * @fixme: simplifier, test unitaire, permettre d'enlever le tri
		 * @todo protected ?
		 *
		 * @param array $query
		 * @param array $params
		 * @return array
		 */
		public function getQueryOrder( $query = array(), array $params = array() ) {
			$Controller = $this->_Collection->getController();
			$params = $this->params( $params );

			$myPathParams = "{$params['searchKeyPrefix']}{$params['configurableQueryFieldsKey']}";
			$myParams = (array)Configure::read( $myPathParams );

			// 1. Si le tri est configuré pour mon action
			if( Hash::check( $myParams, 'order' ) ) {
				$query['order'] = Hash::get( $myParams, 'order' );
			}
			// 2. Si le tri dépend du paramètre prevAction éventuellement présent dans l'URL
			else if( Hash::check( $Controller->request->params, 'named.prevAction') ) {
				$action = Hash::get( $Controller->request->params, 'named.prevAction');

				$myPathParams = "{$params['searchKeyPrefix']}{$Controller->name}.{$action}";
				$myParams = (array)Configure::read( $myPathParams );

				if( Hash::check( $myParams, 'order' ) ) {
					$query['order'] = Hash::get( $myParams, 'order' );
				}
			}
			// 3 Si le tri est configuré dans la page ayant redirigé vers nous
			else {
				// INFO: on pourrait utiliser named.prevAction / celui-ci ne sert donc plus à rien
				$referer = Router::parse( $Controller->request->referer( true ) );
				if( is_array( $referer ) && !empty( $referer ) ) {
					$myPathParams = $params['searchKeyPrefix'].Inflector::camelize($referer['controller']).'.'.$referer['action'];
					$myParams = (array)Configure::read( $myPathParams );
					if( Hash::check( $myParams, 'order' ) ) {
						$query['order'][] = Hash::get( $myParams, 'order' );
					}
				}
			}

			return $query;
		}

		/**
		 *
		 * @param array $params
		 */
		public function search( array $params = array() ) {
			$Controller = $this->_Collection->getController();
			$params = $this->params( $params );

			$Controller->loadModel( $params['modelRechercheName'] );

			if( !empty( $Controller->request->data ) ) {
				$keys = array(
					"{$params['searchKeyPrefix']}{$params['configurableQueryFieldsKey']}.fields",
					"{$params['searchKeyPrefix']}{$params['configurableQueryFieldsKey']}.innerTable"
				);
				$query = $this->getQuery( $keys, $params );

				$query = $this->getQueryConditions( $query, $params );

				$query = $this->getQueryOrder( $query, $params );

				$Controller->{$params['modelName']}->forceVirtualFields = true;
				$results = $this->Allocataires->paginate( $query, $params['modelName'] );

				$Controller->set( compact( 'results' ) );
			}
			else {
				// TODO: le charger au démarrage ?
				$filtresdefaut = Configure::read( "Filtresdefaut.{$Controller->name}_{$Controller->action}" );
				$Controller->request->data = Hash::merge( $Controller->request->data, array( $params['searchKey'] => $filtresdefaut ) );
			}

			$Controller->set( 'options', $this->options( $params ) );
		}

		/**
		 *
		 * @param array $params
		 */
		public function exportcsv( array $params = array() ) {
			$Controller = $this->_Collection->getController();
			$params = $this->params( $params );

			$Controller->loadModel( $params['modelRechercheName'] );

			$query = $this->getQuery( "{$params['searchKeyPrefix']}{$params['configurableQueryFieldsKey']}", $params );

			$search = Hash::get( Hash::expand( $Controller->request->params['named'], '__' ), $params['searchKey'] );
			$query = $Controller->{$params['modelRechercheName']}->searchConditions( $query, $search );
			$query = $this->Allocataires->completeSearchQuery( $query );
			unset( $query['limit'] );

			$order = trim( Hash::get( $Controller->request->params, 'named.sort' ).' '.Hash::get( $Controller->request->params, 'named.direction' ) );
			if( !empty( $order ) ) {
				$query['order'] = $order;
			}
			else {
				$query = $this->getQueryOrder($query, $params);
			}

			$Controller->{$params['modelName']}->forceVirtualFields = true;
			$results = $Controller->{$params['modelName']}->find( 'all', $query );

			$Controller->set( compact( 'results' ) );
			$Controller->set( 'options', $this->options( $params ) );

			$Controller->layout = '';
		}
	}
?>