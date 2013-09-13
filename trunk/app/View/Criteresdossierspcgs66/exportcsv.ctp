<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	if( $this->request->params['pass'][0] == 'searchDossier') {
		$this->Csv->addRow(
			array(
				__d( 'dossier', 'Dossier.numdemrsa' ),
				'Allocataire',
				'Origine de la PDO',
				'Type de dossier',
				'Date de réception',
				'Gestionnaire',
                'Décision',
				'Nb de propositions de décisions',
				'État du dossier',
				'Motif(s) de la personne',
                'Statut(s) de la personne',
				'Nb de fichiers dans la corbeille'
			)
		);
	}
	else {
		$this->Csv->addRow(
			array(
				__d( 'dossier', 'Dossier.numdemrsa' ),
				'Allocataire',
				'Origine de la PDO',
				'Type de dossier',
				'Date de réception',
				'Gestionnaire',
				'Nb de propositions de décisions',
				'Nb de traitements PCGs',
				'Type de traitement',
				'État du dossier',
				'Nb de fichiers dans la corbeille'
			)
		);
	}

	foreach( $results as $i => $result ) {

        // Liste des organismes auxquels on transmet le dossier
        $orgs = vfListeToArray($result['Orgtransmisdossierpcg66']['listorgs']);
        if( !empty( $orgs ) ) {
            $orgs = implode( ',', $orgs );
        }
        else {
            $orgs = '';
        }

        $datetransmission = '';
        if( $result['Dossierpcg66']['etatdossierpcg'] == 'transmisop' ){
            $datetransmission = ' à '.$orgs.' le '.date_short( Set::classicExtract( $result, 'Decisiondossierpcg66.datetransmissionop' ) );
        }
        else if( $result['Dossierpcg66']['etatdossierpcg'] == 'atttransmisop' ){
            $datetransmission = ' à '.$orgs;
            
        }

        $etatdosrsaValue = Set::classicExtract( $result, 'Situationdossierrsa.etatdosrsa' );
        $etatDossierRSA = isset( $etatdosrsa[$etatdosrsaValue] ) ? $etatdosrsa[$etatdosrsaValue] : 'Non défini';
        
        //Liste des différents motifs de la personne
        $differentsMotifs = $result['Personnepcg66']['listemotifs'];
        //Liste des différents statuts de la personne
        $differentsStatuts = $result['Personnepcg66']['listestatuts'];
        
        //Liste des différents traitements de la personne
        $traitementspcgs66 = $result['Dossierpcg66']['listetraitements'];
        
	
		if( $this->request->params['pass'][0] == 'searchDossier' ) {
			$row = array(
				h( Hash::get( $result, 'Dossier.numdemrsa' ) ),
				h( Set::enum( Hash::get( $result, 'Personne.qual' ), $qual ).' '.Hash::get( $result, 'Personne.nom' ).' '.Hash::get( $result, 'Personne.prenom' ) ),
				h( Set::enum( Hash::get( $result, 'Dossierpcg66.originepdo_id' ), $originepdo ) ),
				h( Set::enum( Hash::get( $result, 'Dossierpcg66.typepdo_id' ), $typepdo ) ),
				h( $this->Locale->date( 'Locale->date',  Hash::get( $result, 'Dossierpcg66.datereceptionpdo' ) ) ),
				h( Set::enum( Hash::get( $result, 'Dossierpcg66.user_id' ), $gestionnaire ) ),
                h( Hash::get( $result, 'Decisionpdo.libelle' ) ),
				h( $result['Dossierpcg66']['nbpropositions'] ),
				Set::enum( Hash::get( $result, 'Dossierpcg66.etatdossierpcg' ), $options['Dossierpcg66']['etatdossierpcg'] ).$datetransmission,
				$differentsMotifs,
                $differentsStatuts,
				h( $result['Fichiermodule']['nb_fichiers_lies'] )
			);
		}
		else {
			$row = array(
				h( Hash::get( $result, 'Dossier.numdemrsa' ) ),
				h( Set::enum( Hash::get( $result, 'Personne.qual' ), $qual ).' '.Hash::get( $result, 'Personne.nom' ).' '.Hash::get( $result, 'Personne.prenom' ) ),
				h( Set::enum( Hash::get( $result, 'Dossierpcg66.originepdo_id' ), $originepdo ) ),
				h( Set::enum( Hash::get( $result, 'Dossierpcg66.typepdo_id' ), $typepdo ) ),
				h( $this->Locale->date( 'Locale->date',  Hash::get( $result, 'Dossierpcg66.datereceptionpdo' ) ) ),
				h( Set::enum( Hash::get( $result, 'Dossierpcg66.user_id' ), $gestionnaire ) ),
				h( $result['Dossierpcg66']['nbpropositions'] ),
				h( $result['Personnepcg66']['nbtraitements'] ),
				$traitementspcgs66,
				Set::enum( Hash::get( $result, 'Dossierpcg66.etatdossierpcg' ), $options['Dossierpcg66']['etatdossierpcg'] ).$datetransmission,
				h( $result['Fichiermodule']['nb_fichiers_lies'] )
			);
		}
		$this->Csv->addRow( $row );
	}

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( "{$this->request->params['controller']}_{$this->request->params['action']}_".date( 'Ymd-His' ).'.csv' );
?>