<?php	
	/**
	 * Code source de la classe Infofinanciere.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Infofinanciere ...
	 *
	 * @package app.Model
	 */
	class Infofinanciere extends AppModel
	{
		public $name = 'Infofinanciere';

		public $validate = array(
			'type_allocation' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'natpfcre' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'typeopecompta' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'sensopecompta' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'dttraimoucompta' => array(
				array(
					'rule' => 'date',
					'message' => 'Veuillez entrer une date valide'
				),
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'mtmoucompta' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
				array(
					'rule' => 'numeric',
					'message' => 'Veuillez n\'utiliser que des lettres et des chiffres'
				),
			),
			'mtmoucompta' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			)
		);

		public $belongsTo = array(
			'Dossier' => array(
				'className' => 'Dossier',
				'foreignKey' => 'dossier_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		/**
		*
		*/

		public function search( $mesCodesInsee, $filtre_zone_geo, $criteres ) {
			/// Conditions de base
			$conditions = array();

			/// Critères
			$mois = Set::extract( $criteres, 'Filtre.moismoucompta' );
			$types = Set::extract( $criteres, 'Filtre.type_allocation' );
			$locaadr = Set::extract( $criteres, 'Filtre.locaadr' );
			$numcomptt = Set::extract( $criteres, 'Filtre.numcomptt' );

			/// Mois du mouvement comptable
			if( !empty( $mois ) && dateComplete( $criteres, 'Filtre.moismoucompta' ) ) {
				$month = $mois['month'];
				$year = $mois['year'];
				$conditions[] = 'EXTRACT(MONTH FROM Infofinanciere.moismoucompta) = '.$month;
				$conditions[] = 'EXTRACT(YEAR FROM Infofinanciere.moismoucompta) = '.$year;
			}

			/// Id du Dossier
			if( !empty( $criteres ) && isset( $criteres['Dossier.id'] ) ) {
				$conditions['Dossier.id'] = $criteres['Dossier.id'];
			}

			/// Type d'allocation
			if( !empty( $types ) ) {
				$conditions[] = 'Infofinanciere.type_allocation ILIKE \'%'.Sanitize::clean( $types, array( 'encode' => false ) ).'%\'';
			}

			/// Par adresse
			if( !empty( $locaadr ) ) {
				$conditions[] = 'Adresse.locaadr ILIKE \'%'.Sanitize::clean( $locaadr, array( 'encode' => false ) ).'%\'';
			}

			/// Par code postal
			if( !empty( $numcomptt ) ) {
				$conditions[] = 'Adresse.numcomptt ILIKE \'%'.Sanitize::clean( $numcomptt, array( 'encode' => false ) ).'%\'';
			}

			$conditions[] = $this->conditionsZonesGeographiques( $filtre_zone_geo, $mesCodesInsee );

			/// Requête
			$this->Dossier = ClassRegistry::init( 'Dossier' );

			$query = array(
				'fields' => array(
					'"Infofinanciere"."id"',
					'"Infofinanciere"."dossier_id"',
					'"Infofinanciere"."moismoucompta"',
					'"Infofinanciere"."type_allocation"',
					'"Infofinanciere"."natpfcre"',
					'"Infofinanciere"."rgcre"',
					'"Infofinanciere"."numintmoucompta"',
					'"Infofinanciere"."typeopecompta"',
					'"Infofinanciere"."sensopecompta"',
					'"Infofinanciere"."mtmoucompta"',
					'"Infofinanciere"."ddregu"',
					'"Infofinanciere"."dttraimoucompta"',
					'"Infofinanciere"."heutraimoucompta"',
					'"Dossier"."id"',
					'"Dossier"."numdemrsa"',
					'"Dossier"."matricule"',
					'"Dossier"."typeparte"',
					'"Personne"."id"',
					'"Personne"."nom"',
					'"Personne"."prenom"',
					'"Personne"."nir"',
					'"Personne"."dtnai"',
					'"Personne"."qual"',
					'"Personne"."nomcomnai"',
					'"Situationdossierrsa"."etatdosrsa"',
					'"Adresse"."locaadr"',
					'"Adresse"."numcomptt"',
				),
				'recursive' => -1,
				'joins' => array(
					array(
						'table'      => 'dossiers',
						'alias'      => 'Dossier',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Infofinanciere.dossier_id = Dossier.id' )
					),
					array(
						'table'      => 'situationsdossiersrsa',
						'alias'      => 'Situationdossierrsa',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Situationdossierrsa.dossier_id = Dossier.id' )
					),
					array(
						'table'      => 'foyers',
						'alias'      => 'Foyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Foyer.dossier_id = Dossier.id' )
					),
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.foyer_id = Foyer.id' )
					),
					array(
						'table'      => 'prestations',
						'alias'      => 'Prestation',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id = Prestation.personne_id',
							'Prestation.natprest = \'RSA\'',
							'( Prestation.rolepers = \'DEM\' )'
						)
					),
					array(
						'table'      => 'adressesfoyers',
						'alias'      => 'Adressefoyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Foyer.id = Adressefoyer.foyer_id', 'Adressefoyer.rgadr = \'01\'' )
					),
					array(
						'table'      => 'adresses',
						'alias'      => 'Adresse',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
					),
				),
				'limit' => 10,
				'order' => array( '"Dossier"."numdemrsa"' ),
				'conditions' => $conditions
			);

			$typesAllocation = array( 'AllocationsComptabilisees', 'IndusConstates', 'IndusTransferesCG', 'RemisesIndus', 'AnnulationsFaibleMontant', 'AutresAnnulations' );

			$query['conditions'] = Set::merge( $query['conditions'], $conditions );
			return $query;
		}

		/**
		*
		* @return array contenant les clés minYear et maxYear
		* @access public
		*/

		public function range() {
			$first = $this->find( 'first', array( 'order' => 'moismoucompta ASC', 'recursive' => -1 ) );
			$last = $this->find( 'first', array( 'order' => 'moismoucompta DESC', 'recursive' => -1 ) );

			if( !empty( $first ) && !empty( $last ) ) {
				list( $yearFirst, ,  ) = explode( '-', $first[$this->name]['moismoucompta'] );
				list( $yearLast, ,  ) = explode( '-', $last[$this->name]['moismoucompta'] );

				return array( 'minYear' => $yearFirst, 'maxYear' => $yearLast );
			}
			else {
				return array( 'minYear' => date( 'Y' ), 'maxYear' => date( 'Y' ) );
			}
		}

	}
?>