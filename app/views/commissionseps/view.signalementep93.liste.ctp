<?php

// debug( Set::flatten( $dossiers[$theme] ) );
// debug($dossiers);

	if( ( $theme == 'nonorientationproep58' ) || ( $theme == 'reorientationep93' ) || ( $theme == 'nonorientationproep93' ) || ( $theme == 'regressionorientationep58' ) || ( $theme == 'sanctionep58' ) ){
		$controller = 'orientsstructs';
	}
	else if( ( $theme == 'nonrespectsanctionep93' ) || ( $theme == 'saisinepdoep66' ) ){
		$controller = 'propospdos';
	}
	else if( ( $theme == 'defautinsertionep66' ) || ( $theme == 'saisinebilanparcoursep66' ) ){
		$controller = 'bilansparcours66';
	}

	echo "<div id=\"$theme\"><h3 class=\"title\">".__d( 'dossierep',  'ENUM::THEMEEP::'.Inflector::tableize( $theme ), true )."</h3>";

	if( in_array( 'dossierseps::choose', $etatsActions[$commissionep['Commissionep']['etatcommissionep']] ) ) {
		echo '<ul class="actionMenu"><li>'.$xhtml->affecteLink(
            'Affecter les dossiers',
            array( 'controller' => 'dossierseps', 'action' => 'choose', Set::classicExtract( $commissionep, 'Commissionep.id' ), "#{$theme}" )
        ).' </li></ul>';
    }
    else {
        echo '<li><span class="disabled"> Affecter les dossiers </span></li>';
    }

	if( empty( $dossiers[$theme] ) ) {
		echo '<p class="notice">Il n\'existe aucun dossier de cette thématique associé à cette commission d\'EP.</p>';
	}
	else {
		echo $default2->index(
			$dossiers[$theme],
			array(
				'Dossierep.Personne.Foyer.Dossier.numdemrsa',
				'Dossierep.Personne.Foyer.Adressefoyer.0.Adresse.locaadr',
				'Dossierep.Signalementep93.Contratinsertion.num_contrat',
				'Dossierep.Signalementep93.Contratinsertion.dd_ci',
				'Dossierep.Signalementep93.Contratinsertion.duree_engag',
				'Dossierep.Signalementep93.Contratinsertion.df_ci',
				'Dossierep.Signalementep93.Contratinsertion.Structurereferente.lib_struc',
				'Dossierep.Signalementep93.Contratinsertion.nature_projet',
				'Dossierep.Signalementep93.Contratinsertion.type_demande',
// 				'Passagecommissionep.etatdossierep',
			),
			array(
				'actions' => array(
					'Dossierseps::view' => array( 'label' => 'Voir', 'url' => array( 'controller' => $controller, 'action' => 'index', '#Dossierep.Personne.id#' ), 'class' => 'external' ),
					'Commissionseps::printConvocationBeneficiaire' => array( 'url' => array( 'controller' => 'commissionseps', 'action' => 'printConvocationBeneficiaire', '#Passagecommissionep.id#' ), 'disabled' => empty( $disableConvocationBeneficiaire ))
				),
				'options' => $options,
				'id' => $theme
			)
		);

	}
	echo "</div>";

?>