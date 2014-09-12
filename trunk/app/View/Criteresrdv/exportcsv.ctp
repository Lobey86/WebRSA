<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	$this->Csv->addRow(
		array_merge(
			array(
				'Qualité',
				'Nom',
				'Prénom',
				'N° CAF',
				'Numéro de voie',
				'Type de voie',
				'Nom de voie',
				'Complément adresse 1',
				'Complément adresse 2',
				'Code postal',
				'Commune',
				'Structure référente',
				'Adresse de la structure',
				'Référent',
				'Objet du RDV',
			),
			( ( isset( $useThematiques ) && $useThematiques ) ? array( 'Thématiques du RDV' ) : array() ),
			array(
				'Statut du RDV',
				'Date du RDV',
				'Heure du RDV',
				'Objectif du RDV',
				'Commentaire suite RDV',
				'Etat du droit',
				__d( 'search_plugin', 'Structurereferenteparcours.lib_struc' ),
				__d( 'search_plugin', 'Referentparcours.nom_complet' ),
			)
		)
	);

	foreach( $rdvs as $rdv ) {
		$thematiquesrdvs = Hash::extract( $rdv, 'Thematiquerdv.{n}.name' );

		$row = array_merge(
			array(
				value( $qual, Hash::get( $rdv, 'Personne.qual' ) ),
				Hash::get( $rdv, 'Personne.nom' ),
				Hash::get( $rdv, 'Personne.prenom' ),
				Hash::get( $rdv, 'Dossier.matricule'  ),
				Hash::get( $rdv, 'Adresse.numvoie' ),
				Hash::get( $rdv, 'Adresse.libtypevoie' ),
				Hash::get( $rdv, 'Adresse.nomvoie' ),
				Hash::get( $rdv, 'Adresse.complideadr' ),
				Hash::get( $rdv, 'Adresse.compladr' ),
				Hash::get( $rdv, 'Adresse.codepos' ),
				Hash::get( $rdv, 'Adresse.nomcom' ),
				Hash::get( $rdv, 'Structurereferente.lib_struc' ),
				Hash::get( $rdv, 'Structurereferente.num_voie' ).' '.Set::enum( Hash::get( $rdv, 'Structurereferente.type_voie' ), $typevoie ).' '.Hash::get( $rdv, 'Structurereferente.nom_voie' ).' '.Hash::get( $rdv, 'Structurereferente.code_postal' ).' '.Hash::get( $rdv, 'Structurereferente.ville' ),
				value( $qual, Hash::get( $rdv, 'Referent.qual' ) ).' '.Hash::get( $rdv, 'Referent.nom' ).' '.Hash::get( $rdv, 'Referent.prenom' ),
				value( $typerdv, Hash::get( $rdv, 'Rendezvous.typerdv_id' ) ),
			),
			( ( isset( $useThematiques ) && $useThematiques ) ? array( !empty( $thematiquesrdvs ) ? '- '.implode( "\n- ", $thematiquesrdvs ) : '' ) : array() ),
			array(
				value( $statutrdv, Hash::get( $rdv, 'Rendezvous.statutrdv_id' ) ),
				date_short( $rdv['Rendezvous']['daterdv'] ),
				$rdv['Rendezvous']['heurerdv'],
				Hash::get( $rdv, 'Rendezvous.objetrdv' ),
				Hash::get( $rdv, 'Rendezvous.commentairerdv' ),
				value( $etatdosrsa, Hash::get( $rdv, 'Situationdossierrsa.etatdosrsa' ) ),
				Hash::get( $rdv, 'Structurereferenteparcours.lib_struc' ),
				Hash::get( $rdv, 'Referentparcours.nom_complet' ),
			)
		);
		$this->Csv->addRow($row);
	}

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( 'rendezvous-'.date( 'Ymd-His' ).'.csv' );
?>