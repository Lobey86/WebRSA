<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}

	// Titre
	$this->pageTitle = sprintf(
		__( 'Données socio-professionnelles de %s' ),
		Set::extract( $dsp, 'Personne.qual' ).' '.Set::extract( $dsp, 'Personne.nom' ).' '.Set::extract( $dsp, 'Personne.prenom' )
	);

	$dsp_id = Set::classicExtract( $this->request->data, 'Dsp.id' );
?>

<?php
	echo $this->Xhtml->tag( 'h1', $this->pageTitle );

	// Formulaire
	echo $this->Xform->create( null, array( 'id' => 'dspform' ) );

	// FIXME: id / personne_id
	$tmp = '';
	if( !empty( $this->request->data['Dsp']['id'] ) ) {
		$tmp .= $this->Xform->input( 'Dsp.id', array( 'type' => 'hidden' ) );
	}
	$tmp .= $this->Xform->input( 'Dsp.personne_id', array( 'type' => 'hidden', 'value' => Set::extract( $dsp, 'Personne.id' ) ) );
	echo $this->Xhtml->tag( 'div', $tmp );
/*
Plan:
- GeneraliteDSPP
- SituationSociale
	* CommunSituationSociale
	* DetailDifficulteSituationSociale (0-n)
	* DetailAccompagnementSocialFamilial (0-n)
	* DetailAccompagnementSocialIndividuel (0-n)
	* DetailDifficulteDisponibilite (0-n)
- NiveauEtude
- DisponibiliteEmploi
- SituationProfessionnelle
- Mobilite
	* CommunMobilite
	* DetailMobilite (0-n)
- DifficulteLogement
	* CommunDifficulteLogement
	* DetailDifficulteLogement (0-n)
*/
?>
<fieldset>
	<legend>Généralités</legend>
	<?php
		echo $this->Default->subform(
			array(
				'Dsp.sitpersdemrsa',
				'Dsp.topisogroouenf',
				'Dsp.topdrorsarmiant',
				'Dsp.drorsarmianta2',
				'Dsp.topcouvsoc'
			),
			array(
				'options' => $options
			)
		);
	?>
</fieldset>

<fieldset>
	<legend>Situation sociale</legend>
	<fieldset>
		<legend>Généralités</legend>
		<?php
			echo $this->Default->subform(
				array(
					'Dsp.accosocfam',
					'Dsp.libcooraccosocfam' => array( 'type' => 'textarea' ),
					'Dsp.accosocindi',
					'Dsp.libcooraccosocindi' => array( 'type' => 'textarea' ),
					'Dsp.soutdemarsoc'
				),
				array(
					'options' => $options
				)
			);
		?>
	</fieldset>

	<?php
		// SituationSociale - DetailDifficulteSituationSociale (0-n)
		echo $this->Dsphm->fieldset( 'Detaildifsoc', 'difsoc', 'libautrdifsoc', $dsp_id, '0407', $options['Detaildifsoc']['difsoc'] );

		// SituationSociale - DetailDifficulteSituationSocialeProfessionel (0-n)
		if ( $cg == 'cg58' ) {
			echo '<fieldset>';
				echo '<legend>'.__d( 'dsp', 'Detaildifsocpro.difsocpro' ).'</legend>';
				echo $this->Dsphm->fields( 'Detaildifsocpro', 'difsocpro', 'libautrdifsocpro', $dsp_id, '2110', $options['Detaildifsocpro']['difsocpro'] );
				echo $this->Default->subform(
					array(
						'Dsp.suivimedical' => array( 'type' => 'radio', 'options' => $options['Dsp']['suivimedical'] )
					),
					array(
						'options' => $options
					)
				);
			echo '</fieldset>';
		}

		// SituationSociale - DetailAccompagnementSocialFamilial (0-n)
		echo $this->Dsphm->fieldset( 'Detailaccosocfam', 'nataccosocfam', 'libautraccosocfam', $dsp_id, '0413', $options['Detailaccosocfam']['nataccosocfam'] );

		// SituationSociale - DetailAccompagnementSocialIndividuel (0-n)
		echo $this->Dsphm->fieldset( 'Detailaccosocindi', 'nataccosocindi', 'libautraccosocindi', $dsp_id, '0420', $options['Detailaccosocindi']['nataccosocindi'] );

		// SituationSociale - DetailDifficulteDisponibilite (0-n)
		echo $this->Dsphm->fieldset( 'Detaildifdisp', 'difdisp', null, $dsp_id, null, $options['Detaildifdisp']['difdisp'] );
	?>
</fieldset>

<fieldset>
	<legend>Niveau d'étude</legend>
	<?php
		echo $this->Default->subform(
			array(
				'Dsp.nivetu',
				'Dsp.nivdipmaxobt',
				'Dsp.annobtnivdipmax',
				'Dsp.topqualipro',
				'Dsp.libautrqualipro',
				'Dsp.topcompeextrapro',
				'Dsp.libcompeextrapro'
			),
			array(
				'options' => $options
			)
		);
	?>
</fieldset>

<fieldset>
	<legend>Disponibilités emploi</legend>
	<?php
		echo $this->Default->subform(
			array(
				'Dsp.topengdemarechemploi'
			),
			array(
				'options' => $options
			)
		);
	?>
</fieldset>

<fieldset>
	<legend>Situation professionnelle</legend>
	<?php
		if( Configure::read( 'Romev3.enabled' ) ) {
			echo $this->Default->subform(
				array(
					'Dsp.hispro'
				),
				array(
					'options' => $options
				)
			);

			// Codes ROME V3 dernière activité
			echo $this->Romev3->fieldset( array( 'modelName' => 'Dsp', 'prefix' => 'deract', 'options' => $options ) );

			// Codes ROME V2 dernière activité (FIXME) + intitulés dernière activité
			echo $this->Default->subform(
				array(
					'Dsp.libsecactderact66_secteur_id' => array( 'type' => 'select', 'options' => $options['Coderomesecteurdsp66'] ),
					'Dsp.libsecactderact' => array( 'label' => '' ),
					'Dsp.libderact66_metier_id' => array( 'type' => 'select', 'options' => $options['Coderomemetierdsp66'] ),
					'Dsp.libderact' => array( 'label' => '' ),
				),
				array(
					'options' => $options
				)
			);

			echo $this->Default->subform(
				array(
					'Dsp.cessderact',
					'Dsp.topdomideract'
				),
				array(
					'options' => $options
				)
			);

			// Codes ROME V3 dernière activité dominante
			echo $this->Romev3->fieldset( array( 'modelName' => 'Dsp', 'prefix' => 'deractdomi', 'options' => $options ) );

			// Codes ROME V2 dernière activité dominante (FIXME) + intitulés dernière activité dominante
			echo $this->Default->subform(
				array(
					'Dsp.libsecactdomi66_secteur_id' => array( 'type' => 'select', 'options' => $options['Coderomesecteurdsp66'] ),
					'Dsp.libsecactdomi' => array( 'label' => '' ),
					'Dsp.libactdomi66_metier_id' => array( 'type' => 'select', 'options' => $options['Coderomemetierdsp66'] ),
					'Dsp.libactdomi' => array( 'label' => '' ),
				),
				array(
					'options' => $options
				)
			);

			echo $this->Default->subform(
				array(
					'Dsp.duractdomi',
					'Dsp.inscdememploi',
					'Dsp.topisogrorechemploi',
					'Dsp.accoemploi',
					'Dsp.libcooraccoemploi' => array( 'type' => 'textarea' ),
					'Dsp.topprojpro'
				),
				array(
					'options' => $options
				)
			);
		}
		else {
			echo $this->Default->subform(
				array(
					'Dsp.hispro',
					'Dsp.libderact',
					'Dsp.libsecactderact',
					'Dsp.cessderact',
					'Dsp.topdomideract',
					'Dsp.libactdomi',
					'Dsp.libsecactdomi',
					'Dsp.duractdomi',
					'Dsp.inscdememploi',
					'Dsp.topisogrorechemploi',
					'Dsp.accoemploi',
					'Dsp.libcooraccoemploi' => array( 'type' => 'textarea' ),
					'Dsp.topprojpro'
				),
				array(
					'options' => $options
				)
			);
		}

		if ( $cg == 'cg58' ) {
			echo $this->Dsphm->fieldset( 'Detailprojpro', 'projpro', 'libautrprojpro', $dsp_id, '2213', $options['Detailprojpro']['projpro'] );
		}

		if ( Configure::read( 'Romev3.enabled' ) ) {

			// Codes ROME V3 dernière activité dominante
			echo $this->Romev3->fieldset( array( 'modelName' => 'Dsp', 'prefix' => 'actrech', 'options' => $options ) );

			// Codes ROME V2 dernière activité dominante (FIXME) + intitulés dernière activité dominante
			echo $this->Default->subform(
				array(
					'Dsp.libsecactrech66_secteur_id' => array( 'type' => 'select', 'options' => $options['Coderomesecteurdsp66'] ),
					'Dsp.libsecactrech' => array( 'label' => '' ),
					'Dsp.libemploirech66_metier_id' => array( 'type' => 'select', 'options' => $options['Coderomemetierdsp66'] ),
					'Dsp.libemploirech' => array( 'label' => '' ),
				),
				array(
					'options' => $options
				)
			);

			echo $this->Default->subform(
				array(
					'Dsp.topcreareprientre',
					'Dsp.concoformqualiemploi'
				),
				array(
					'options' => $options
				)
			);
		}
		else {
			echo $this->Default->subform(
				array(
					'Dsp.libemploirech',
					'Dsp.libsecactrech',
					'Dsp.topcreareprientre',
					'Dsp.concoformqualiemploi'
				),
				array(
					'options' => $options
				)
			);
		}

		if ($cg=='cg58') {
			echo $this->Default->subform(
				array(
					'Dsp.libformenv'
				),
				array(
					'options' => $options
				)
			);
			echo $this->Dsphm->fieldset( 'Detailfreinform', 'freinform', null, $dsp_id, null, $options['Detailfreinform']['freinform'] );
		}
	?>
</fieldset>

<fieldset>
	<legend>Mobilité</legend>

	<fieldset>
		<legend>Généralités</legend>
		<?php
			echo $this->Default->subform(
				array(
					'Dsp.topmoyloco'
				),
				array(
					'options' => $options
				)
			);

			if( $cg=='cg58' ) {
				echo $this->Dsphm->fieldset( 'Detailmoytrans', 'moytrans', 'libautrmoytrans', $dsp_id, '2008', $options['Detailmoytrans']['moytrans'] );
			}

			echo $this->Default->subform(
				array(
					'Dsp.toppermicondub',
					'Dsp.topautrpermicondu',
					'Dsp.libautrpermicondu'
				),
				array(
					'options' => $options
				)
			);
		?>
	</fieldset>

	<?php
		// Mobilite - DetailMobilite (0-n)
		echo $this->Dsphm->fieldset( 'Detailnatmob', 'natmob', null, $dsp_id, null, $options['Detailnatmob']['natmob'] );
	?>
</fieldset>

<fieldset>
	<legend>Difficultés logement</legend>
	<?php
		echo $this->Default->subform(
			array(
				'Dsp.natlog'
			),
			array(
				'options' => $options
			)
		);

		if ($cg=='cg58') {
			echo $this->Default->subform(
				array(
						'Dsp.statutoccupation'
				),
				array(
						'options' => $options
				)
			);
			echo $this->Dsphm->fieldset( 'Detailconfort', 'confort', null, $dsp_id, null, $options['Detailconfort']['confort'] );
		}

		echo $this->Default->subform(
			array(
				'Dsp.demarlog'
			),
			array(
				'options' => $options
			)
		);
	?>

	<?php
		// DifficulteLogement - DetailDifficulteLogement
// 			if ( $cg == 'cg58' ) {
// 				echo $this->Dsphm->fieldset( 'Detaildiflog', 'diflog', null, $dsp_id, null, $options['Detaildiflog']['diflog'] );
// 			}
// 			else {
		echo $this->Dsphm->fieldset( 'Detaildiflog', 'diflog', 'libautrdiflog', $dsp_id, '1009', $options['Detaildiflog']['diflog'] );
// 			}
	?>
</fieldset>

<div class="submit">
	<?php echo $this->Form->submit( 'Enregistrer', array( 'div' => false ) );?>
	<?php echo $this->Form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
</div>
<?php echo $this->Form->end();?>

<script type="text/javascript">
	observeDisableFieldsOnValue( 'DspTopdrorsarmiant', [ 'DspDrorsarmianta2' ], '1', false );
	observeDisableFieldsOnValue( 'DspAccosocfam', [ 'DspLibcooraccosocfam' ], 'O', false );
	observeDisableFieldsOnValue( 'DspAccosocindi', [ 'DspLibcooraccosocindi' ], 'O', false );
	observeDisableFieldsOnValue( 'DspTopqualipro', [ 'DspLibautrqualipro' ], '1', false );
	observeDisableFieldsOnValue( 'DspTopcompeextrapro', [ 'DspLibcompeextrapro' ], '1', false );
	observeDisableFieldsOnValue( 'DspHispro', [ 'DspLibderact', 'DspLibsecactderact', 'DspCessderact', 'DspTopdomideract', 'DspLibactdomi', 'DspLibsecactdomi', 'DspDuractdomi' ], '1904', true );
	observeDisableFieldsOnValue( 'DspAccoemploi', [ 'DspLibcooraccoemploi' ], [ '1802', '1803' ], false );
	observeDisableFieldsOnValue( 'DspTopautrpermicondu', [ 'DspLibautrpermicondu' ], '1', false );
	// FIXME: pas de niveau = pas de diplôme ?
	observeDisableFieldsOnValue( 'DspNivetu', [ 'DspNivdipmaxobt', 'DspAnnobtnivdipmax' ], [ '', '1207' ], true );

	observeDisableFieldsOnValue( 'DspTopmoyloco', [ 'Detailmoytrans0Moytrans', 'Detailmoytrans1Moytrans', 'Detailmoytrans2Moytrans', 'Detailmoytrans3Moytrans', 'Detailmoytrans4Moytrans', 'Detailmoytrans5Moytrans', 'Detailmoytrans6Moytrans', 'Detailmoytrans7Moytrans' ], '1', false );

	observeDisableFieldsOnValue( 'DspTopprojpro', [ 'Detailprojpro0Projpro', 'Detailprojpro1Projpro', 'Detailprojpro2Projpro', 'Detailprojpro3Projpro', 'Detailprojpro4Projpro', 'Detailprojpro5Projpro', 'Detailprojpro6Projpro', 'Detailprojpro7Projpro', 'Detailprojpro8Projpro', 'Detailprojpro9Projpro', 'Detailprojpro10Projpro', 'Detailprojpro11Projpro', 'Detailprojpro12Projpro' ], '1', false );

	observeDisableFieldsOnCheckbox( 'Detailnatmob0Natmob', [ 'Detailnatmob1Natmob', 'Detailnatmob2Natmob', 'Detailnatmob3Natmob' ], true );
	observeDisableFieldsOnCheckbox( 'Detaildifsoc0Difsoc', [ 'Detaildifsoc1Difsoc', 'Detaildifsoc2Difsoc', 'Detaildifsoc3Difsoc', 'Detaildifsoc4Difsoc', 'Detaildifsoc5Difsoc', 'Detaildifsoc6Difsoc', 'Detaildifsoc6Libautrdifsoc' ], true );
	observeDisableFieldsOnCheckbox( 'Detaildiflog0Diflog', [ 'Detaildiflog1Diflog', 'Detaildiflog2Diflog', 'Detaildiflog3Diflog', 'Detaildiflog4Diflog', 'Detaildiflog5Diflog', 'Detaildiflog6Diflog', 'Detaildiflog7Diflog', 'Detaildiflog8Diflog', 'Detaildiflog8Libautrdiflog' ], true );
	observeDisableFieldsOnCheckbox( 'Detaildiflog0Diflog', [ 'Detaildiflog8Libautrdiflog' ], true );

	// Champs textarea ayant une longueur maximale de 250 caractères.
	Event.observe( $( 'DspLibcooraccosocfam' ), 'keypress', function(event) {
		textareaMakeItCount('DspLibcooraccosocfam', 250, true );
	} );

	Event.observe( $( 'DspLibcooraccosocindi' ), 'keypress', function(event) {
		textareaMakeItCount('DspLibcooraccosocindi', 250, true );
	} );

	// Champs textarea ayant une longueur maximale de 100 caractères.
	Event.observe( $( 'Detailaccosocfam3Libautraccosocfam' ), 'keypress', function(event) {
		textareaMakeItCount('Detailaccosocfam3Libautraccosocfam', 100, true );
	} );

	Event.observe( $( 'Detailaccosocindi4Libautraccosocindi' ), 'keypress', function(event) {
		textareaMakeItCount('Detailaccosocindi4Libautraccosocindi', 100, true );
	} );

	Event.observe( $( 'Detaildifsoc6Libautrdifsoc' ), 'keypress', function(event) {
		textareaMakeItCount('Detaildifsoc6Libautrdifsoc', 100, true );
	} );

	Event.observe( $( 'DspLibcooraccoemploi' ), 'keypress', function(event) {
		textareaMakeItCount('DspLibcooraccoemploi', 100, true );
	} );

	Event.observe( $( 'Detaildiflog8Libautrdiflog' ), 'keypress', function(event) {
		textareaMakeItCount('Detaildiflog8Libautrdiflog', 100, true );
	} );
	<?php if( $cg == 'cg58' ):?>
		// Champs textarea ayant une longueur maximale de 100 caractères.
		Event.observe( $( 'Detailmoytrans7Libautrmoytrans' ), 'keypress', function(event) {
			textareaMakeItCount('Detailmoytrans7Libautrmoytrans', 100, true );
		} );

		Event.observe( $( 'Detailprojpro12Libautrprojpro' ), 'keypress', function(event) {
			textareaMakeItCount('Detailprojpro12Libautrprojpro', 100, true );
		} );

		Event.observe( $( 'Detaildifsocpro9Libautrdifsocpro' ), 'keypress', function(event) {
			textareaMakeItCount('Detaildifsocpro9Libautrdifsocpro', 100, true );
		} );

		Event.observe( $( 'Detaildifsocpro0Difsocpro' ), 'click', function(event) {
			if( $( 'Detaildifsocpro0Difsocpro' ).checked ) {
				$( 'DspSuivimedicalN' ).up(1).show();
			}
			else {
				$( 'DspSuivimedicalN' ).up(1).hide();
			}
		} );
		if( $( 'Detaildifsocpro0Difsocpro' ).checked ) {
			$( 'DspSuivimedicalN' ).up(1).show();
		}
		else {
			$( 'DspSuivimedicalN' ).up(1).hide();
		}

		observeDisableFieldsOnCheckbox( 'Detaildifdisp0Difdisp', [ 'Detaildifdisp1Difdisp', 'Detaildifdisp2Difdisp', 'Detaildifdisp3Difdisp', 'Detaildifdisp4Difdisp', 'Detaildifdisp5Difdisp', 'Detaildifdisp6Difdisp', 'Detaildifdisp7Difdisp', 'Detaildifdisp8Difdisp', 'Detaildifdisp9Difdisp', 'Detaildifdisp10Difdisp' ], true );
	<?php else:?>


		observeDisableFieldsOnCheckbox( 'Detaildifdisp0Difdisp', [ 'Detaildifdisp1Difdisp', 'Detaildifdisp2Difdisp', 'Detaildifdisp3Difdisp', 'Detaildifdisp4Difdisp', 'Detaildifdisp5Difdisp' ], true );
	<?php endif;?>

	<?php if ( Configure::read( 'Cg.departement' ) == 66 ):?>
		document.observe("dom:loaded", function() {
			dependantSelect( 'DspLibderact66MetierId', 'DspLibsecactderact66SecteurId' );
			try { $( 'DspLibderact66MetierId' ).onchange(); } catch(id) { }

			dependantSelect( 'DspLibactdomi66MetierId', 'DspLibsecactdomi66SecteurId' );
			try { $( 'DspLibactdomi66MetierId' ).onchange(); } catch(id) { }

			dependantSelect( 'DspLibemploirech66MetierId', 'DspLibsecactrech66SecteurId' );
			try { $( 'DspLibemploirech66MetierId' ).onchange(); } catch(id) { }
		});
	<?php endif;?>
</script>
<?php echo $this->Observer->disableFormOnSubmit( 'dspform' );?>