<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'ajoutdossiercomplet', "Ajoutdossierscomplets::{$this->action}", true )
	);

	echo $form->create( 'Ajoutdossiercomplet', array( 'id' => 'dossiercomplet', 'url'=> Router::url( null, true ) ) );

	echo '<fieldset><legend>Ajout d\'un nouveau dossier</legend>';
		//On masque les prestations et on leur met des valeurs par défaut
		echo $form->input( 'Prestation.natprest', array( 'type' => 'hidden', 'value' => 'RSA' ) );
		echo $form->input( 'Prestation.rolepers', array( 'type' => 'hidden', 'value' => 'DEM' ) );

		// Données de la personne
		echo '<fieldset><legend>Données de la personne</legend>';
			echo $default2->subform(
				array(
					'Personne.qual' => array( 'required' => true, 'type' => 'select', 'options' => $options['qual'] ),
					'Personne.nom' => array( 'required' => true ),
					'Personne.prenom' => array( 'required' => true ),
					'Personne.dtnai' => array( 'required' => false, 'type' => 'date', 'minYear' => date('Y')-100, 'maxYear' => date('Y') ),
					'Personne.nir'
				)
			);
		echo '</fieldset>';

		// Adresse de la personne
		echo $form->input( 'Adressefoyer.rgadr', array( 'type' => 'hidden', 'value' => '01' ) );
		echo '<fieldset><legend>Adresse de la personne</legend>';
			echo $default2->subform(
				array(
					'Adresse.presence' => array( 'label' => 'Adresse présente ?', 'type' => 'checkbox' )
				)
			);
			echo $default2->subform(
				array(
					 'Adresse.numvoie',
					 'Adresse.typevoie' => array( 'type' => 'select', 'options' => $options['typevoie'], 'empty' => true ),
					 'Adresse.nomvoie',
					 'Adresse.complideadr',
					 'Adresse.codepos',
					 'Adresse.locaadr',
					 'Adresse.canton'
				)
			);
		echo '</fieldset>';

		// Informations du dossier
		echo '<fieldset><legend>Données dossier</legend>';
			echo $default2->subform(
				array(
					 'Dossier.numdemrsatemp' => array( 'label' => 'Génération automatique d\'un N° de demande RSA temporaire', 'type' => 'checkbox' ),
					 'Dossier.numdemrsa' => array( 'required' => true ),
					 'Dossier.matricule'
				)
			);
		echo '</fieldset>';
	echo '</fieldset>'; 
	echo $xform->end( 'Save' );
?>
<script type="text/javascript">
	observeDisableFieldsOnCheckbox(
		'DossierNumdemrsatemp',
		[
			'DossierNumdemrsa'
		],
		true
	);
	
	// On masque les champs adresses si la case est cochée
	observeDisableFieldsOnCheckbox(
		'AdressePresence',
		[
			'AdresseNumvoie',
			'AdresseTypevoie',
			'AdresseNomvoie',
			'AdresseCompladr',
			'AdresseComplideadr',
			'AdresseCodepos',
			'AdresseLocaadr',
			'AdresseCanton'
		],
		false
	);
</script>