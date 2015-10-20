<?php
	/**
	 * Code source de la classe WebrsaCohortesNonorientationsproscovs58NewComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaAbstractCohortesNewComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaCohortesNonorientationsproscovs58NewComponent ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaCohortesNonorientationsproscovs58NewComponent extends WebrsaAbstractCohortesNewComponent
	{
		/**
		 * Retourne les options de type "enum", c'est à dire liées aux schémas des
		 * tables de la base de données.
		 *
		 * La mise en cache se fera dans ma méthode _options().
		 *
		 * @return array
		 */
		protected function _optionsEnums( array $params ) {
			$Controller = $this->_Collection->getController();

			return Hash::merge(
				parent::_optionsEnums( $params ),
				$Controller->Orientstruct->enums(),
				$Controller->Orientstruct->Personne->Contratinsertion->enums()
			);
		}
	}
?>