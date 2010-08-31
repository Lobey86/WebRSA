<?php
    App::import( 'Sanitize' );
//     App::import( 'Dossier' );

    // ************************************************************************

    class Critererdv extends AppModel
    {
        var $name = 'Critererdv';
        var $useTable = false;

        function search( $mesCodesInsee, $filtre_zone_geo, $criteresrdv ) {
            /// Conditions de base
            $conditions = array();

            /// Critères
            $statutrdv_id = Set::extract( $criteresrdv, 'Critererdv.statutrdv_id' );
            $natpf = Set::extract( $criteresrdv, 'Critererdv.natpf' );
            $typerdv_id = Set::extract( $criteresrdv, 'Critererdv.typerdv_id' );
            $structurereferente_id = Set::extract( $criteresrdv, 'Critererdv.structurereferente_id' );
            $referent_id = Set::extract( $criteresrdv, 'Critererdv.referent_id' );
            $permanence_id = Set::extract( $criteresrdv, 'Critererdv.permanence_id' );
            $locaadr = Set::extract( $criteresrdv, 'Critererdv.locaadr' );
            $numcomptt = Set::extract( $criteresrdv, 'Critererdv.numcomptt' );
            $nom = Set::extract( $criteresrdv, 'Critererdv.nom' );
            $nir = Set::extract( $criteresrdv, 'Critererdv.nir' );
            $matricule = Set::extract( $criteresrdv, 'Critererdv.matricule' );

            /// Filtre zone géographique
            if( $filtre_zone_geo ) {
                $mesCodesInsee = ( !empty( $mesCodesInsee ) ? $mesCodesInsee : '0' );
                $conditions[] = 'Adresse.numcomptt IN ( \''.implode( '\', \'', $mesCodesInsee ).'\' )';
            }

            /// Critères sur le RDV - date de demande
            if( isset( $criteresrdv['Critererdv']['daterdv'] ) && !empty( $criteresrdv['Critererdv']['daterdv'] ) ) {
                $valid_from = ( valid_int( $criteresrdv['Critererdv']['daterdv_from']['year'] ) && valid_int( $criteresrdv['Critererdv']['daterdv_from']['month'] ) && valid_int( $criteresrdv['Critererdv']['daterdv_from']['day'] ) );
                $valid_to = ( valid_int( $criteresrdv['Critererdv']['daterdv_to']['year'] ) && valid_int( $criteresrdv['Critererdv']['daterdv_to']['month'] ) && valid_int( $criteresrdv['Critererdv']['daterdv_to']['day'] ) );
                if( $valid_from && $valid_to ) {
                    $conditions[] = 'Rendezvous.daterdv BETWEEN \''.implode( '-', array( $criteresrdv['Critererdv']['daterdv_from']['year'], $criteresrdv['Critererdv']['daterdv_from']['month'], $criteresrdv['Critererdv']['daterdv_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteresrdv['Critererdv']['daterdv_to']['year'], $criteresrdv['Critererdv']['daterdv_to']['month'], $criteresrdv['Critererdv']['daterdv_to']['day'] ) ).'\'';
                }
            }
            /// Statut RDV
            if( !empty( $statutrdv_id ) ) {
                $conditions[] = 'Rendezvous.statutrdv_id = \''.Sanitize::clean( $statutrdv_id ).'\'';
            }

            /// Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
            $filtersPersonne = array();
            foreach( array( 'nom', 'prenom', 'nomnai' ) as $criterePersonne ) {
                if( isset( $criteresrdv['Critererdv'][$criterePersonne] ) && !empty( $criteresrdv['Critererdv'][$criterePersonne] ) ) {
                    $conditions[] = 'Personne.'.$criterePersonne.' ILIKE \''.$this->wildcard( replace_accents( $criteresrdv['Critererdv'][$criterePersonne] ) ).'\'';
                }
            }

            /// Adresse personne
            if( !empty( $locaadr ) ) {
                $conditions[] = 'Adresse.locaadr ILIKE \'%'.Sanitize::clean( $locaadr ).'%\'';
            }

            /// Code INSSE
            if( !empty( $numcomptt ) ) {
                $conditions[] = 'Adresse.numcomptt ILIKE \'%'.Sanitize::clean( $numcomptt ).'%\'';
            }

            /// N° CAF
            if( !empty( $matricule ) ) {
                $conditions[] = 'Dossier.matricule = \''.Sanitize::clean( $matricule ).'\'';
            }

            /// Critères sur l'adresse - canton
			if( Configure::read( 'CG.cantons' ) ) {
				if( isset( $criteresrdv['Canton']['canton'] ) && !empty( $criteresrdv['Canton']['canton'] ) ) {
					$this->Canton =& ClassRegistry::init( 'Canton' );
					$conditions[] = $this->Canton->queryConditions( $criteresrdv['Canton']['canton'] );
				}
			}

            /// Structure référente
            if( !empty( $structurereferente_id ) ) {
                $conditions[] = 'Rendezvous.structurereferente_id = \''.Sanitize::clean( $structurereferente_id ).'\'';
            }

            /// Référent
            if( !empty( $referent_id ) ) {
                $conditions[] = 'Rendezvous.referent_id = \''.Sanitize::clean( $referent_id ).'\'';
            }

            /// Permanence
            if( !empty( $permanence_id ) ) {
                $conditions[] = 'Rendezvous.permanence_id = \''.Sanitize::clean( $permanence_id ).'\'';
            }

            /// Nature de la prestation
            if( !empty( $natpf ) ) {
                $conditions[] = 'Dossier.id IN (
                    SELECT detailsdroitsrsa.dossier_rsa_id
                        FROM detailsdroitsrsa
                            INNER JOIN detailscalculsdroitsrsa ON ( detailscalculsdroitsrsa.detaildroitrsa_id = detailsdroitsrsa.id )
                        WHERE detailscalculsdroitsrsa.natpf ILIKE \'%'.Sanitize::clean( $natpf ).'%\'
                )';
            }

            /// Objet du rendez vous
            if( !empty( $typerdv_id ) ) {
                $conditions[] = 'Rendezvous.typerdv_id = \''.Sanitize::clean( $typerdv_id ).'\'';
            }
            /// Requête
            $this->Dossier =& ClassRegistry::init( 'Dossier' );

            $query = array(
                'fields' => array(
                    '"Rendezvous"."id"',
                    '"Rendezvous"."personne_id"',
                    '"Rendezvous"."referent_id"',
                    '"Rendezvous"."permanence_id"',
                    '"Rendezvous"."statutrdv_id"',
                    '"Rendezvous"."structurereferente_id"',
                    '"Rendezvous"."typerdv_id"',
                    '"Rendezvous"."daterdv"',
                    '"Rendezvous"."heurerdv"',
                    '"Rendezvous"."objetrdv"',
                    '"Rendezvous"."commentairerdv"',
                    '"Dossier"."numdemrsa"',
                    '"Adresse"."locaadr"',
                    '"Adresse"."numcomptt"',
                    '"Personne"."nom"',
                    '"Personne"."prenom"',
                    '"Personne"."nir"',
                    '"Personne"."nomcomnai"',
                    '"Personne"."dtnai"',
                    '"Structurereferente"."lib_struc"'
                ),
                'recursive' => -1,
                'joins' => array(
                    array(
                        'table'      => 'personnes',
                        'alias'      => 'Personne',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Rendezvous.personne_id = Personne.id' ),
                    ),
                    array(
                        'table'      => 'structuresreferentes',
                        'alias'      => 'Structurereferente',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Structurereferente.id = Rendezvous.structurereferente_id' ),
                    ),
                    array(
                        'table'      => 'typesrdv',
                        'alias'      => 'Typerdv',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'Typerdv.id = Rendezvous.typerdv_id' ),
                    ),
                    array(
                        'table'      => 'statutsrdvs',
                        'alias'      => 'Statutrdv',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'Statutrdv.id = Rendezvous.statutrdv_id' ),
                    ),
                    array(
                        'table'      => 'prestations',
                        'alias'      => 'Prestation',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array(
                            'Personne.id = Prestation.personne_id',
                            'Prestation.natprest = \'RSA\'',
//                             '( Prestation.natprest = \'RSA\' OR Prestation.natprest = \'PFA\' )',
                            '( Prestation.rolepers = \'DEM\' OR Prestation.rolepers = \'CJT\' )',
                        )
                    ),
                    array(
                        'table'      => 'foyers',
                        'alias'      => 'Foyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Personne.foyer_id = Foyer.id' )
                    ),
                    array(
                        'table'      => 'adresses_foyers',
                        'alias'      => 'Adressefoyer',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array(
                            'Foyer.id = Adressefoyer.foyer_id',
                            // FIXME: c'est un hack pour n'avoir qu'une seule adresse de range 01 par foyer!
                            'Adressefoyer.id IN (
                				'.ClassRegistry::init( 'Adressefoyer' )->sqDerniereRgadr01('Adressefoyer.foyer_id').'
                			)'
                        )
                    ),
                    array(
                        'table'      => 'adresses',
                        'alias'      => 'Adresse',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
                    ),
                    array(
                        'table'      => 'dossiers_rsa',
                        'alias'      => 'Dossier',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Foyer.dossier_rsa_id = Dossier.id' )
                    )
                ),
                'order' => array( '"Rendezvous"."daterdv" ASC' ),
                'conditions' => $conditions
            );

            return $query;

        }
    }
?>
