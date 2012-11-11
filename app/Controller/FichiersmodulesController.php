<?php
	/**
	 * Code source de la classe FichiersmodulesController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe FichiersmodulesController ...
	 *
	 * @package app.Controller
	 */
	class FichiersmodulesController extends AppController
	{

		public $name = 'Fichiersmodules';
		public $uses = array( 'Fichiermodule' );


		/**
		*   Suppression du fichiers préalablement associés à un traitement donné
		*/

		public function delete( $fichiermodule_id ) {
			$this->assert( !empty( $fichiermodule_id ), 'error404' );

			if( $this->Fichiermodule->delete( $fichiermodule_id ) ) {
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
			}
			else {
				$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
			}

			$this->redirect( $this->referer() );
		}
	}
?>