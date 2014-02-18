<?php
	/**
	 * Fichier source de la classe CakeTestSession.
	 *
	 * PHP 5.3
	 * @package Search
	 * @subpackage Test.Case
	 */
	App::uses( 'CakeSession', 'Model/Datasource' );

	/**
	 * La classe CakeTestSession permet de manipuler une session CakePHP pour les
	 * tests en console.
	 *
	 * @package Search
	 * @subpackage Test.Case
	 */
	class CakeTestSession extends CakeSession
	{
		/**
		 * Un identifiant de session utilisé uniquement pour les tests unitaires.
		 *
		 * @var string
		 */
		public static $testSessionId = '00000000000000000000000000';

		/**
		 * Permet de démarrer une session également en console.
		 *
		 * On change les droits sur le fichier de session géré par PHP.
		 * Comme ça, dans le navigateur et en console, les droits sont bons.
		 * Ou alors, lancer la console en tant qu'apache ? Sinon, ça peut casser...
		 *
		 * @return boolean True if session was started
		 */
		public static function start() {
			session_id( self::$testSessionId );

			$file = ini_get('session.save_path').DS.'sess_'.self::$testSessionId;
			if( file_exists( $file ) && is_writable( $file ) ) {
				chmod( $file, 0770 );
			}

			$return = parent::start();
			$_SESSION = array();

			return $return;
		}

		/**
		 * Permet de supprimer les données de la session.
		 */
		public static function destroy() {
			$_SESSION = array();
//			parent::destroy();
		}
	}
?>