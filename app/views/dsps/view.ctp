<?php
	// CSS
	echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );

	// Titre
	$this->pageTitle = sprintf(
		__( 'Données socio-professionnelles de %s', true ),
		Set::extract( $dsp, 'Personne.qual' ).' '.Set::extract( $dsp, 'Personne.nom' ).' '.Set::extract( $dsp, 'Personne.prenom' )
	);

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>

<div class="with_treemenu">
	<div id="dsps">
		<?php
			echo $xhtml->tag( 'h1', $this->pageTitle );

            echo $form->create( 'Dsp', array( 'type' => 'post', 'id' => 'dspform', 'url' => Router::url( null, true ) ) );


			function result( $data, $path, $type, $options = array() ) {
				$result = Set::classicExtract( $data, $path );
				if( $type == 'enum' ) {
					if( !empty( $options['Dsp'][$result] ) ) {
						$result = $options['Dsp'][$result];
					}
				}

				return $result;
			}

			if( empty( $dsp['Dsp']['id'] ) ) {
				echo '<p class="notice">Cette personne ne possède pas encore de données socio-professionnelles.</p>';

				if( $permissions->check( 'dsps', 'add' ) ) {
					echo '<ul class="actionMenu">
							<li>'.$xhtml->addLink(
								'Ajouter une DSP',
								array( 'controller' => 'dsps', 'action' => 'add', $personne_id )
							).' </li></ul>';
				}
			}
			else {

				if( $permissions->check( 'dsps', 'edit' ) && ( (isset($rev)) && (!$rev) || ( $this->action == 'view_revs' ) ) ) {
					echo '<ul class="actionMenu">
							<li>'.$xhtml->editLink(
								'Modifier cette DSP',
								array( 'controller' => 'dsps', 'action' => 'edit', Set::classicExtract( $dsp, 'Personne.id' ) )
							).' </li></ul>';
				}
				
				if( $permissions->check( 'dsps', 'revertTo' ) && ( $this->action == 'view_revs' ) ) {
					echo '<ul class="actionMenu">
							<li>'.$xhtml->revertToLink(
								'Revenir à cette version',
								array( 'controller' => 'dsps', 'action' => 'revertTo', Set::classicExtract( $dsp, 'Dsp.id' ) )
							).' </li></ul>';
				}

				echo $form->input(
					'Dsp.hideempty',
					array(
						'type' => 'checkbox',
						'label' => 'Cacher les questions sans réponse',
						'onclick' => 'if( $( \'DspHideempty\' ).checked ) {
							$$( \'.empty\' ).each( function( elmt ) { elmt.hide() } );
						} else { $$( \'.empty\' ).each( function( elmt ) { elmt.show() } ); }'
					)
				);
// debug($dsp);
				$generalites = $default->view(
					$dsp,
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

				if( !empty( $generalites ) ) {
					echo $xhtml->tag( 'h2', 'Généralités' ).$generalites;
				}

				$generalites = $default->view(
					$dsp,
					array(
						'Dsp.accosocfam',
						'Dsp.libcooraccosocfam',
						'Dsp.accosocindi',
						'Dsp.libcooraccosocindi',
						'Dsp.soutdemarsoc'
					),
					array(
						'options' => $options
					)
				);

				if( !empty( $generalites ) ) {
					echo $xhtml->tag( 'h3', 'Généralités' ).$generalites;
				}

				// Situation SituationSociale
				// SituationSociale - CommunSituationSociale
				/*$rows = array(
					array(
						__d( 'dsp', 'Dsp.accosocfam', true ),
						result( $dsp, 'Dsp.accosocfam', 'enum', $options['Dsp']['accosocfam'] ),
					),
					array(
						__d( 'dsp', 'Dsp.libcooraccosocfam', true ),
						result( $dsp, 'Dsp.libcooraccosocfam', 'textarea' ),
					),
					array(
						__d( 'dsp', 'Dsp.accosocindi', true ),
						result( $dsp, 'Dsp.accosocindi', 'enum', $options['Dsp']['accosocindi'] ),
					),
					array(
						__d( 'dsp', 'Dsp.libcooraccosocindi', true ),
						result( $dsp, 'Dsp.libcooraccosocindi', 'textarea' ),
					),
					array(
						__d( 'dsp', 'Dsp.soutdemarsoc', true ),
						result( $dsp, 'Dsp.soutdemarsoc', 'enum', $options['Dsp']['soutdemarsoc'] ),
					)
				);
				$generalites = $xhtml->details( $rows, array( 'type' => 'list', 'empty' => true ) );
				if( !empty( $generalites ) ) {
					$generalites = $xhtml->tag( 'h3', 'Généralités' ).$generalites;
				}*/

				// SituationSociale - DetailDifficulteSituationSociale (0-n)
				$difficultes = $dsphm->details( $dsp, 'Detaildifsoc', 'difsoc', 'libautrdifsoc', $options['Detaildifsoc']['difsoc'] );
				$difficultes = $xhtml->tag( 'h3', 'Difficultés sociales' ).$difficultes;

				// SituationSociale - DetailDifficulteSituationSocialeProfessionnel (0-n)
				if ($cg=='cg58') {
					$difficultes = $dsphm->details( $dsp, 'Detaildifsocpro', 'difsocpro', 'libautrdifsocpro', $options['Detaildifsocpro']['difsocpro'] );
					$difficultes = $xhtml->tag( 'h3', 'Difficultés sociales décelées par le professionel' ).$difficultes;
				}

				// SituationSociale - DetailAccompagnementSocialFamilial (0-n)
				$accosocfam = $dsphm->details( $dsp, 'Detailaccosocfam', 'nataccosocfam', 'libautraccosocfam', $options['Detailaccosocfam']['nataccosocfam'] );
				$accosocfam = $xhtml->tag( 'h3', 'Difficultés accompagnement social familial' ).$accosocfam;

				// SituationSociale - DetailAccompagnementSocialIndividuel (0-n)
				$accosocindi = $dsphm->details( $dsp, 'Detailaccosocindi', 'nataccosocindi', 'libautraccosocindi', $options['Detailaccosocindi']['nataccosocindi'] );
				$accosocindi = $xhtml->tag( 'h3', 'Difficultés accompagnement social individuel' ).$accosocindi;

				// SituationSociale - DetailDifficulteDisponibilite (0-n)
				$difdisps = $dsphm->details( $dsp, 'Detaildifdisp', 'difdisp', null, $options['Detaildifdisp']['difdisp'] );
				$difdisps = $xhtml->tag( 'h3', 'Difficultés disponibilités' ).$difdisps;

	// debug( array_keys( $options ) );

				$nivetus = $default->view(
					$dsp,
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

				if( !empty( $nivetus ) ) {
					$nivetus = $xhtml->tag( 'h2', 'Niveau d\'étude' ).$nivetus;
				}

				// Niveau d'étude
				/*$rows = array(
					array(
						__d( 'dsp', 'Dsp.nivetu', true ),
						result( $dsp, 'Dsp.nivetu', 'enum', $options['Dsp']['nivetu'] ),
					),
					array(
						__d( 'dsp', 'Dsp.nivdipmaxobt', true ),
						result( $dsp, 'Dsp.nivdipmaxobt', 'enum', $options['Dsp']['nivdipmaxobt'] ),
					),
					array(
						__d( 'dsp', 'Dsp.annobtnivdipmax', true ),
						result( $dsp, 'Dsp.annobtnivdipmax', 'text' ),
					),
					array(
						__d( 'dsp', 'Dsp.topqualipro', true ),
						result( $dsp, 'Dsp.topqualipro', 'enum', $options['Dsp']['topqualipro'] ),
					),
					array(
						__d( 'dsp', 'Dsp.libautrqualipro', true ),
						result( $dsp, 'Dsp.libautrqualipro', 'textarea' ),
					),
					array(
						__d( 'dsp', 'Dsp.topcompeextrapro', true ),
						result( $dsp, 'Dsp.topcompeextrapro', 'enum', $options['Dsp']['topcompeextrapro'] ),
					),
					array(
						__d( 'dsp', 'Dsp.libcompeextrapro', true ),
						result( $dsp, 'Dsp.libcompeextrapro', 'textarea' ),
					),
				);
				$nivetus = $xhtml->details( $rows, array( 'type' => 'list', 'empty' => true ) );
				if( !empty( $nivetus ) ) {
					$nivetus = $xhtml->tag( 'h2', 'Niveau d\'étude' ).$nivetus;
				}*/

				// Disponibilités emploi
				$disponibilitesEmploi = $default->view(
					$dsp,
					array(
						'Dsp.topengdemarechemploi'
					),
					array(
						'options' => $options
					)
				);

				if( !empty( $disponibilitesEmploi ) ) {
					$disponibilitesEmploi = $xhtml->tag( 'h2', 'Disponibilités emploi' ).$disponibilitesEmploi;
				}

				/*$disponibilitesEmploi = array(
					array(
						__d( 'dsp', 'Dsp.topengdemarechemploi', true ),
						result( $dsp, 'Dsp.topengdemarechemploi', 'enum', $options['Dsp']['topengdemarechemploi'] ),
					)
				);
				$disponibilitesEmploi = $xhtml->details( $disponibilitesEmploi, array( 'type' => 'list', 'empty' => true ) );
				if( !empty( $disponibilitesEmploi ) ) {
					$disponibilitesEmploi = $xhtml->tag( 'h2', 'Disponibilités emploi' ).$disponibilitesEmploi;
				}*/

				// Situation professionnelle
				$situationProfessionnelle = $default->view(
					$dsp,
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
						'Dsp.libcooraccoemploi',
						'Dsp.topprojpro'
					),
					array(
						'options' => $options
					)
				);

				if( !empty( $situationProfessionnelle ) ) {
					$situationProfessionnelle = $xhtml->tag( 'h2', 'Situation professionnelle' ).$situationProfessionnelle;
				}
				
				if ($cg=='cg58')
					$situationProfessionnelle .= $dsphm->details( $dsp, 'Detailprojpro', 'projpro', 'libautrprojpro', $options['Detailprojpro']['projpro'] );
				
				$situationProfessionnelle .= $default->view(
					$dsp,
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
				
				if ($cg=='cg58') {
					$situationProfessionnelle .= $default->view(
						$dsp,
						array(
							'Dsp.libformenv'
						),
						array(
							'options' => $options
						)
					);
					$situationProfessionnelle .= $dsphm->details( $dsp, 'Detailfreinform', 'freinform', null, $options['Detailfreinform']['freinform'] );
				}
				/*$rows = array(
					array(
						__d( 'dsp', 'Dsp.hispro', true ),
						result( $dsp, 'Dsp.hispro', 'enum', $options['Dsp']['hispro'] ),
					),
					array(
						__d( 'dsp', 'Dsp.libderact', true ),
						result( $dsp, 'Dsp.libderact', 'textarea' ),
					),
					array(
						__d( 'dsp', 'Dsp.libsecactderact', true ),
						result( $dsp, 'Dsp.libsecactderact', 'textarea' ),
					),
					array(
						__d( 'dsp', 'Dsp.cessderact', true ),
						result( $dsp, 'Dsp.cessderact', 'enum', $options['Dsp']['cessderact'] ),
					),
					array(
						__d( 'dsp', 'Dsp.topdomideract', true ),
						result( $dsp, 'Dsp.topdomideract', 'enum', $options['Dsp']['topdomideract'] ),
					),
					array(
						__d( 'dsp', 'Dsp.libactdomi', true ),
						result( $dsp, 'Dsp.libactdomi', 'textarea' ),
					),
					array(
						__d( 'dsp', 'Dsp.libsecactdomi', true ),
						result( $dsp, 'Dsp.libsecactdomi', 'textarea' ),
					),
					array(
						__d( 'dsp', 'Dsp.duractdomi', true ),
						result( $dsp, 'Dsp.duractdomi', 'enum', $options['Dsp']['duractdomi'] ),
					),
					array(
						__d( 'dsp', 'Dsp.inscdememploi', true ),
						result( $dsp, 'Dsp.inscdememploi', 'enum', $options['Dsp']['inscdememploi'] ),
					),
					array(
						__d( 'dsp', 'Dsp.topisogrorechemploi', true ),
						result( $dsp, 'Dsp.topisogrorechemploi', 'enum', $options['Dsp']['topisogrorechemploi'] ),
					),
					array(
						__d( 'dsp', 'Dsp.accoemploi', true ),
						result( $dsp, 'Dsp.accoemploi', 'enum', $options['Dsp']['accoemploi'] ),
					),
					array(
						__d( 'dsp', 'Dsp.libcooraccoemploi', true ),
						result( $dsp, 'Dsp.libcooraccoemploi', 'textarea' ),
					),
					array(
						__d( 'dsp', 'Dsp.topprojpro', true ),
						result( $dsp, 'Dsp.topprojpro', 'enum', $options['Dsp']['topprojpro'] ),
					),
					array(
						__d( 'dsp', 'Dsp.libemploirech', true ),
						result( $dsp, 'Dsp.libemploirech', 'textarea' ),
					),
					array(
						__d( 'dsp', 'Dsp.libsecactrech', true ),
						result( $dsp, 'Dsp.libsecactrech', 'textarea' ),
					),
					array(
						__d( 'dsp', 'Dsp.topcreareprientre', true ),
						result( $dsp, 'Dsp.topcreareprientre', 'enum', $options['Dsp']['topcreareprientre'] ),
					),
					array(
						__d( 'dsp', 'Dsp.concoformqualiemploi', true ),
						result( $dsp, 'Dsp.concoformqualiemploi', 'enum', $options['Dsp']['concoformqualiemploi'] ),
					),
				);
				$situationProfessionnelle = $xhtml->details( $rows, array( 'type' => 'list', 'empty' => true ) );
				if( !empty( $situationProfessionnelle ) ) {
					$situationProfessionnelle = $xhtml->tag( 'h2', 'Situation professionnelle' ).$situationProfessionnelle;
				}*/

				// Mobilité
				$mobilite = $default->view(
					$dsp,
					array(
						'Dsp.topmoyloco'
					),
					array(
						'options' => $options
					)
				);
				if( !empty( $mobilite ) ) {
					$mobilite = $xhtml->tag( 'h2', 'Mobilité' ).$mobilite;
				}
				
				if ($cg=='cg58') {
					$mobilite .= $dsphm->details( $dsp, 'Detailmoytrans', 'moytrans', 'libautrmoytrans', $options['Detailmoytrans']['moytrans'] );
				
					$mobilite .= $default->view(
						$dsp,
						array(
							'Dsp.toppermicondub',
							'Dsp.topautrpermicondu',
							'Dsp.libautrpermicondu'
						),
						array(
							'options' => $options
						)
					);
				}

				/*$rows = array(
					array(
						__d( 'dsp', 'Dsp.topmoyloco', true ),
						result( $dsp, 'Dsp.topmoyloco', 'enum', $options['Dsp']['topmoyloco'] ),
					),
					array(
						__d( 'dsp', 'Dsp.toppermicondub', true ),
						result( $dsp, 'Dsp.toppermicondub', 'enum', $options['Dsp']['toppermicondub'] ),
					),
					array(
						__d( 'dsp', 'Dsp.topautrpermicondu', true ),
						result( $dsp, 'Dsp.topautrpermicondu', 'enum', $options['Dsp']['topautrpermicondu'] ),
					),
					array(
						__d( 'dsp', 'Dsp.libautrpermicondu', true ),
						result( $dsp, 'Dsp.libautrpermicondu', 'textarea' ),
					),
				);
				$mobilite = $xhtml->details( $rows, array( 'type' => 'list', 'empty' => true ) );
				if( !empty( $mobilite ) ) {
					$mobilite = $xhtml->tag( 'h2', 'Mobilité' ).$mobilite;
				}*/

				// Mobilite - DetailMobilite (0-n)
				$natmobs = $dsphm->details( $dsp, 'Detailnatmob', 'natmob', null, $options['Detailnatmob']['natmob'] );
				$natmobs = $xhtml->tag( 'h3', 'Code mobilité' ).$natmobs;


				// Difficultés logement
				$difficultesLogement = $default->view(
					$dsp,
					array(
						'Dsp.natlog'
						// FIXME
	// 					'Dsp.topautrpermicondu',
	// 					'Dsp.libautrpermicondu'
					),
					array(
						'options' => $options
					)
				);
				
				if( !empty( $difficultesLogement ) ) {
					$difficultesLogement = $xhtml->tag( 'h2', 'Difficultés logement' ).$difficultesLogement;
				}
				
				if ($cg=='cg58')
					$difficultesLogement .= $dsphm->details( $dsp, 'Detailconfort', 'confort', null, $options['Detailconfort']['confort'] );

//     debug($dsp);
				$difficultesLogement .= $default->view(
					$dsp,
					array(
						'Dsp.demarlog',
						'Dsp.statutoccupation',
						'Dsp.demarlog'
					),
					array(
						'options' => $options
					)
				);

				/*$rows = array(
					array(
						__d( 'dsp', 'Dsp.natlog', true ),
						result( $dsp, 'Dsp.natlog', 'enum', $options['Dsp']['natlog'] ),
					),
					array(
						__d( 'dsp', 'Dsp.demarlog', true ),
						result( $dsp, 'Dsp.demarlog', 'enum', $options['Dsp']['demarlog'] ),
					),
					array(
						__d( 'dsp', 'Dsp.topautrpermicondu', true ),
						result( $dsp, 'Dsp.topautrpermicondu', 'enum', $options['Dsp']['topautrpermicondu'] ),
					),
					array(
						__d( 'dsp', 'Dsp.libautrpermicondu', true ),
						result( $dsp, 'Dsp.libautrpermicondu', 'textarea' ),
					),
				);
				$difficultesLogement = $xhtml->details( $rows, array( 'type' => 'list', 'empty' => true ) );
				if( !empty( $difficultesLogement ) ) {
					$difficultesLogement = $xhtml->tag( 'h2', 'Difficultés logement' ).$difficultesLogement;
				}*/

				// DifficulteLogement - DetailDifficulteLogement
				$diflogs = $dsphm->details( $dsp, 'Detaildiflog', 'diflog', 'libautrdiflog', $options['Detaildiflog']['diflog'] );
				$diflogs = $xhtml->tag( 'h3', 'Détails difficultés logement' ).$diflogs;

				$situationSolciale = array( $generalites, $difficultes, $accosocfam, $accosocindi, $difdisps, $nivetus, $disponibilitesEmploi, $situationProfessionnelle, $mobilite, $natmobs, $difficultesLogement, $diflogs );
				$situationSolciale = implode( '', $situationSolciale );
				if( !empty( $situationSolciale ) ) {
					echo $xhtml->tag( 'h2', 'Situation sociale' ).$situationSolciale;
				}
			}
		?>
	</div>
</div>
<?php if( $this->action == 'view_revs' ):?>
    <div class="submit">
            <?php
                echo $form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
            ?>
        </div>
        <?php echo $form->end();?>
<?php endif;?>
<div class="clearer"><hr /></div>

<?php /*debug( $dsp );*/ ?>
