<?php
	/**
	 * Fichier source de la classe DerniersdossiersallocatairesShell.
	 *
	 * PHP 5.3
	 *
	 * @package app.Console.Command
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'XShell', 'Console/Command' );

	/**
	 * La classe DerniersdossiersallocatairesShell ...
	 *
	 * @package app.Console.Command
	 */
	class DerniersdossiersallocatairesShell extends XShell
	{
		public $uses = array( 'Dernierdossierallocataire' );

		/**
		 *
		 */
		public function main() {
			$success = true;
			$start = microtime( true );
			$this->Dernierdossierallocataire->begin();

			$this->out( 'Suppression des entrées de la table derniersdossiersallocataires' );
			$sql = 'TRUNCATE derniersdossiersallocataires;';
			$success = ( $this->Dernierdossierallocataire->query( $sql ) !== false ) && $success;

			$this->out( 'Remise à zéro de séquence de la clé primaire de la table derniersdossiersallocataires' );
			$sql = "SELECT table_name AS \"Model__table\",
						column_name	AS \"Model__column\",
						column_default AS \"Model__sequence\"
						FROM information_schema.columns
						WHERE table_schema = 'public'
							AND table_name = 'derniersdossiersallocataires'
							AND column_default LIKE 'nextval(%::regclass)'
						ORDER BY table_name, column_name";

			foreach( $this->Dernierdossierallocataire->query( $sql ) as $model ) {
				$sequence = preg_replace( '/^nextval\(\'(.*)\'.*\)$/', '\1', $model['Model']['sequence'] );

				$sql = "SELECT setval('{$sequence}', 1, true);";
				$success = ( $this->Dernierdossierallocataire->query( $sql ) !== false ) && $success;
			}

			$sql = "INSERT INTO derniersdossiersallocataires (personne_id, dossier_id)
	SELECT
			personnes.id AS personne_id,
			(
				SELECT
						dossiers.id
					FROM personnes AS p2
						INNER JOIN prestations AS pr2 ON (
							p2.id = pr2.personne_id
							AND pr2.natprest = 'RSA'
						)
						INNER JOIN foyers ON (
							p2.foyer_id = foyers.id
						)
						INNER JOIN dossiers ON (
							dossiers.id = foyers.dossier_id
						)
					WHERE
						prestations.rolepers IN ( 'DEM', 'CJT' )
						AND (
							(
								nir_correct13( personnes.nir )
								AND nir_correct13( p2.nir )
								AND SUBSTRING( TRIM( BOTH ' ' FROM personnes.nir ) FROM 1 FOR 13 ) = SUBSTRING( TRIM( BOTH ' ' FROM p2.nir ) FROM 1 FOR 13 )
								AND personnes.dtnai = p2.dtnai
							)
							OR
							(
								UPPER(personnes.nom) = UPPER(p2.nom)
								AND UPPER(personnes.prenom) = UPPER(p2.prenom)
								AND personnes.dtnai = p2.dtnai
							)
						)
					ORDER BY dossiers.dtdemrsa DESC
					LIMIT 1
			) AS dossier_id
		FROM personnes
		INNER JOIN prestations ON (
			personnes.id = prestations.personne_id
			AND prestations.natprest = 'RSA'
		)
		WHERE prestations.rolepers IN ( 'DEM', 'CJT' );";

			$this->out( 'Population de la table derniersdossiersallocataires' );
			$success = ( $this->Dernierdossierallocataire->query( $sql ) !== false ) && $success;

			if( $success ) {
				$this->Dernierdossierallocataire->commit();
				$this->out( "Succès" );
			}
			else {
				$this->Dernierdossierallocataire->rollback();
				$this->err( "Erreur" );
			}

			$this->out( sprintf( "\nExécuté en %s secondes.", number_format( microtime( true ) - $start, 2, ',', ' ' ) ) );
			$this->_stop( ( $success ? 0 : 1 ) );
		}

		/**
		 *
		 */
		public function help() {
			$this->out( "Usage: cake/console/cake derniersdossiersallocataires" );

			$this->_stop( 0 );
		}
	}
?>