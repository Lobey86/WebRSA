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

		public function params( array $params = array() ) {
			$Controller = $this->_Collection->getController();

			$params += array(
				'modelName' => $Controller->modelClass,
				'modelRechercheName' => 'WebrsaRecherche'.$Controller->modelClass,
				'searchKey' => 'Search',
				'configurableQueryFieldsKey' => "{$Controller->name}.{$Controller->request->params['action']}"
			);

			return $params;
		}

		public function options( array $params = array() ) {
			$Controller = $this->_Collection->getController();
			$params = $this->params( $params );

			return Hash::merge(
				$this->Allocataires->options(),
				$Controller->{$params['modelName']}->enums()
			);
		}

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

		public function search( array $params = array() ) {
			$Controller = $this->_Collection->getController();
			$params = $this->params( $params );

			$Controller->loadModel( $params['modelRechercheName'] );

			if( !empty( $Controller->request->data ) ) {
				$keys = array( "{$params['configurableQueryFieldsKey']}.fields", "{$params['configurableQueryFieldsKey']}.innerTable" );
				$query = $this->getQuery( $keys, $params );

				$query = $Controller->{$params['modelRechercheName']}->searchConditions( $query, (array)Hash::get( $Controller->request->data, $params['searchKey'] ) );
				$query = $this->Allocataires->completeSearchQuery( $query );

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

		public function exportcsv( array $params = array() ) {
			$Controller = $this->_Collection->getController();
			$params = $this->params( $params );

			$Controller->loadModel( $params['modelRechercheName'] );

			$query = $this->getQuery( $params['configurableQueryFieldsKey'], $params );

			$search = Hash::get( Hash::expand( $Controller->request->params['named'], '__' ), $params['searchKey'] );
			$query = $Controller->{$params['modelRechercheName']}->searchConditions( $query, $search );
			$query = $this->Allocataires->completeSearchQuery( $query );
			unset( $query['limit'] );

			$order = trim( Hash::get( $Controller->request->params, 'named.sort' ).' '.Hash::get( $Controller->request->params, 'named.direction' ) );
			if( !empty( $order ) ) {
				$query['order'] = $order;
			}

			$Controller->{$params['modelName']}->forceVirtualFields = true;
			$results = $Controller->{$params['modelName']}->find( 'all', $query );

			$Controller->set( compact( 'results' ) );
			$Controller->set( 'options', $this->options( $params ) );

			$Controller->layout = '';
		}
	}
?>