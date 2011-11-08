<?php
	$domain = "actioncandidat_personne_".Configure::read( 'ActioncandidatPersonne.suffixe' );
	echo $this->element( 'dossier_menu', array( 'id' => $dossierId, 'personne_id' => $personne_id ) );
	echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
?>

<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $javascript->link( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}
?>

<div class="with_treemenu">
	<?php
		echo $xhtml->tag(
			'h1',
			$this->pageTitle = __d( $domain, "ActionscandidatsPersonnes::{$this->action}", true )
		);
	?>
	<?php
		echo $xform->create( 'ActioncandidatPersonne', array( 'id' => 'candidatureform' ) );
		if( Set::check( $this->data, 'ActioncandidatPersonne.id' ) ){
			echo $xform->input( 'ActioncandidatPersonne.id', array( 'type' => 'hidden' ) );
		}
	?>
	<fieldset id="infocandidature">
		<legend>Informations de candidature</legend>
		<?php

			echo $default->subform(
				array(
					'ActioncandidatPersonne.personne_id' => array( 'value' => $personneId, 'type' => 'hidden' ),
					'ActioncandidatPersonne.actioncandidat_id' => array( 'type' => 'select', 'options' => $actionsfiche ),
					'ActioncandidatPersonne.referent_id' => array( 'value' => $referentId ),
				),
				array(
					'domain' => $domain,
					'options' => $options
				)
			);

			echo $ajax->observeField( 'ActioncandidatPersonneActioncandidatId', array( 'update' => 'ActioncandidatPartenairePartenaireId', 'url' => Router::url( array( 'action' => 'ajaxpart' ), true ) ) );

			echo $xhtml->tag(
				'div',
				' ',
				array(
					'id' => 'ActioncandidatPartenairePartenaireId'
				)
			);

			echo $xhtml->tag(
				'div',
				' ',
				array(
					'id' => 'ActioncandidatPrescripteurReferentId'
				)
			);

			echo $ajax->observeField( 'ActioncandidatPersonneReferentId', array( 'update' => 'ActioncandidatPrescripteurReferentId', 'url' => Router::url( array( 'action' => 'ajaxreferent' ), true ) ) );

		?>
	</fieldset>
	<fieldset id="infocandidat">
		<legend>Informations du candidat</legend>
		<?php
			echo $default->view(
				$personne,
				array(
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom'
				),
				array(
					'widget' => 'dl',
					'class' => 'allocataire infos',
					'options' => $options
				)
			);

			$labelMatricule = Set::classicExtract( $personne, 'Dossier.fonorg' );
			$numTel = Set::classicExtract( $personne, 'Personne.numtel' );
			echo $default->view(
				$personne,
				array(
					'Personne.dtnai',
					'Personne.numfixe' => array( 'type' => 'text' ),//  'label' => "N° de téléphone {$numTel}" ),
					'Dossier.matricule' => array( 'type' => 'text', 'label' => "N° {$labelMatricule}" )
				),
				array(
					'widget' => 'dl',
					'class' => 'allocataire infos'
				)
			);
			if( !empty( $identifiantpe ) ){
				echo $xhtml->tag(
					'dl', 
					$xhtml->tag( 'dt', 'N° Pôle Emploi') . $xhtml->tag( 'dd', $identifiantpe['Informationpe']['identifiantpe']),
					array( 'class' => 'allocataire infos' )
				);
			}

			echo $xhtml->tag(
				'dl',
				$xhtml->tag( 'dt', 'Adresse' ).
				$xhtml->tag(
					'dd',
					$default->format( $personne, 'Adresse.numvoie' ).' '.$default->format( $personne, 'Adresse.typevoie', array( 'options' => $options ) ).' '.$default->format( $personne, 'Adresse.nomvoie' ).'<br />'.$default->format( $personne, 'Adresse.codepos' ).' '.$default->format( $personne, 'Adresse.locaadr' )
				),
				array(
					'class' => 'allocataire infos'
				)
			);
		?>
	</fieldset>
	<fieldset id="motifdemande">
		<legend><?php echo required( "Motif de la demande (donner des précisions sur le parcous d'insertion et les motifs de la prescription)" ); ?></legend>
			<?php
				echo $default->subform(
					array(
						'ActioncandidatPersonne.motifdemande' => array( 'label' => false, 'required' => false )
					),
					array(
						'domain' => $domain
					)
				);
			?>
	</fieldset>
	<fieldset id="mobilite">
		<legend>Mobilité</legend>
		<?php
			echo $default->subform(
				array(
					'ActioncandidatPersonne.mobile' => array( 'type' => 'radio' , 'legend' => 'Etes-vous mobile ?', 'div' => false, 'options' => array( '0' => 'Non', '1' => 'Oui' ) ),
					'ActioncandidatPersonne.naturemobile' => array( 'label' => 'Nature de la mobilité', 'options' => $options['ActioncandidatPersonne']['naturemobile'], 'empty' => true ),
					'ActioncandidatPersonne.typemobile'=> array( 'label' => 'Type de mobilité ' ),
				),
				array(
					'domain' => $domain,
					'options' => $options
				)
			);
		?>
	</fieldset>
	<fieldset id="rdv">
		<legend>Rendez-vous</legend>
		<?php 
			echo $default->subform(
				array(
					'ActioncandidatPersonne.rendezvouspartenaire' => array( 'type' => 'radio' , 'legend' => 'Rendez-vous', 'div' => false, 'options' => array( '0' => 'Non', '1' => 'Oui' ) ),
					'ActioncandidatPersonne.horairerdvpartenaire' => array(
						'type' => 'datetime',
						'label' => 'Rendez-vous fixé le ',
						'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 2, 'maxYear' => date( 'Y' ) + 2,
						'timeFormat' => 24,
						'hourRange' => array( 8, 19 ),
						'interval' => 5,
						'empty' => true
					),
				),
				array(
					'options' => $options,
					'domain' => $domain
				)
			);
		?>
	</fieldset>

	<fieldset id="engagement" class="loici">
		<p>
			<strong>Engagement:</strong><br />
			<em>Je m’engage à me rendre disponible afin d’être présent à la prestation ou au rendez vous qui me sera fixé. En cas de force majeure, je m’engage à prévenir le référent chargé de mon suivi.<br />
			Je suis informé(e) que dans le cas où je ne donnerai pas suite à ce rendez-vous sans motif valable, <strong>cela pourra entraîner la suspension du versement de mon allocataion rSa</strong>.<br />
			</em>
		</p>
		<?php
			echo $default->subform(
				array(
					'ActioncandidatPersonne.datesignature' => array( 'dateFormat' => 'DMY', 'empty' => false )
				),
				array(
					'domain' => $domain
				)
			);
		?>
	</fieldset>

	<?php if( $this->action == 'edit' ):?>

		<p class="center"><em><strong>A remplir par le partenaire :</strong></em></p>
		<fieldset class="partenaire bilan">
			<?php
				echo $xhtml->tag(
					'dl',
					'Bilan d\'accueil : '.REQUIRED_MARK
				);

				echo $default->subform(
					array(
						'ActioncandidatPersonne.bilanvenu' => array( 'required' => true, 'type' => 'radio', 'separator' => '<br />',  'legend' => false, 'style' => 'padding:0;' ),
						'ActioncandidatPersonne.bilanretenu' => array( 'required' => true, 'type' => 'radio', 'separator' => '<br />', 'legend' => false ),
					),
					array(
						'domain' => $domain,
						'options' => $options
					)
				);

				echo $default->subform(
					array(
						'ActioncandidatPersonne.infocomplementaire',
						'ActioncandidatPersonne.datebilan' => array( 'dateFormat' => 'DMY', 'empty' => false )
					),
					array(
						'domain' => $domain,
						'options' => $options
					)
				);
			?>
		</fieldset>
		<fieldset id="blocsortie">
			<?php
				echo $default2->subform(
					array(
						'ActioncandidatPersonne.issortie' => array( 'label' =>  'Sortie', 'type' => 'checkbox' ),
						),
					array(
						'options' => $options
					)
				);
			?>
			<fieldset id="issortie" class="invisible">
				<?php 
					echo $default->subform(
						array(
							'ActioncandidatPersonne.sortiele',
							'ActioncandidatPersonne.motifsortie_id'
						),
						array(
							'domain' => $domain,
							'options' => $options
						)
					);
				?>
			</fieldset>
		</fieldset>
		
		
	<?php endif;?>
	<div class="submit">
		<?php
			echo $xform->submit( 'Enregistrer', array( 'div' => false ) );
			echo $xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>
	<?php echo $xform->end();?>
</div>
<div class="clearer"><hr /></div>

<script type="text/javascript">
	document.observe( "dom:loaded", function() {


		// Mise en disabled des champs lors du passage du formulaire en édition
		<?php if ($this->action == 'edit' && isset( $this->data['ActioncandidatPersonne']['positionfiche'] ) && ( $this->data['ActioncandidatPersonne']['positionfiche'] != 'enattente' ) ):?>

			function disableFormPart( formpartid ) {
				$( formpartid ).addClassName( 'disabled' );
				
				$( formpartid ).getElementsBySelector( 'div.input', 'radio' ).each( function( elmt ) {
					elmt.addClassName( 'disabled' );
				} );
					
				$( formpartid ).getElementsBySelector( 'input', 'select', 'button', 'textarea' ).each( function( elmt ) {
					elmt.disable();
				} );
			}

			['infocandidature', 'infocandidat', 'motifdemande', 'mobilite', 'rdv', 'engagement' ].each( function( formpartid ) {
				disableFormPart( formpartid );
			});
		<?php endif;?>
		<?php
			echo $ajax->remoteFunction(
				array(
					'update' => 'ActioncandidatPartenairePartenaireId',
					'url' => Router::url( array( 'action' => 'ajaxpart', Set::extract( $this->data, 'ActioncandidatPersonne.actioncandidat_id' ) ), true )
				)
			);
		?>;
		<?php
			if( ( $this->action == 'add' ) && !empty( $referentId ) ) {
				echo $ajax->remoteFunction(
					array(
						'update' => 'ActioncandidatPrescripteurReferentId',
						'url' => Router::url( array( 'action' => 'ajaxreferent', $referentId ), true)
					)
				);
			}
			else {
				echo $ajax->remoteFunction(
					array(
						'update' => 'ActioncandidatPrescripteurReferentId',
						'url' => Router::url( array( 'action' => 'ajaxreferent', Set::extract( $this->data, 'ActioncandidatPersonne.referent_id' ) ), true)
					)
				);
			}
		?>

		observeDisableFieldsOnRadioValue(
			'candidatureform',
			'data[ActioncandidatPersonne][rendezvouspartenaire]',
			[
					'ActioncandidatPersonneHorairerdvpartenaireDay',
					'ActioncandidatPersonneHorairerdvpartenaireMonth',
					'ActioncandidatPersonneHorairerdvpartenaireYear',
					'ActioncandidatPersonneHorairerdvpartenaireHour',
					'ActioncandidatPersonneHorairerdvpartenaireMin',
			],
			'1',
			true
		);

		observeDisableFieldsOnRadioValue(
			'candidatureform',
			'data[ActioncandidatPersonne][mobile]',
			[
				'ActioncandidatPersonneTypemobile',
				'ActioncandidatPersonneNaturemobile'
			],
			'1',
			true
		);

		<?php  if( $this->action == 'edit' ):?>

			/*observeDisableFieldsetOnRadioValue(
				'candidatureform',
				'data[ActioncandidatPersonne][bilanvenu]',
				$( 'blocsortie' ),
				undefined,
				false,
				true
			);*/

			observeDisableFieldsetOnRadioValue(
				'candidatureform',
				'data[ActioncandidatPersonne][bilanvenu]',
				$( 'blocsortie' ),
				'VEN',
				false,
				true
			);

			/*observeDisableFieldsOnRadioValue(
				'candidatureform',
				'data[ActioncandidatPersonne][bilanretenu]',
				[ 'ActioncandidatPersonneIssortie' ],
				'NRE',
				false
			);*/

			observeDisableFieldsetOnRadioValue(
				'candidatureform',
				'data[ActioncandidatPersonne][bilanretenu]',
				$( 'blocsortie' ),
				'RET',
				false,
				true
			);

			observeDisableFieldsetOnCheckbox(
				'ActioncandidatPersonneIssortie',
				'issortie',
				false,
				true
			);

			observeDisableFieldsOnRadioValue(
				'candidatureform',
				'data[ActioncandidatPersonne][bilanvenu]',
				[ 
					'ActioncandidatPersonneBilanretenu_',
					'ActioncandidatPersonneBilanretenuRET',
					'ActioncandidatPersonneBilanretenuNRE'
				],
				'VEN',
				true
			);

		<?php endif;?>


	} );
</script>
<!--/************************************************************************/ -->