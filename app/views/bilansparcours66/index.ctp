<?php $personne_id = Set::classicExtract( $this->params, 'pass.0' ); ?>

<?php if( empty( $personne_id ) ):?>
	<h1> <?php echo $this->pageTitle = 'Écran de synthèse des bilans de parcours'; ?> </h1>
	<?php
        unset( $options['Bilanparcours66']['saisineepparcours'] );
	// 	require_once( 'index.ctp' );
// debug($bilansparcours66);
		echo $default2->index(
			$bilansparcours66,
			array(
				'Bilanparcours66.created' => array( 'type' => 'date' ),
				// Personne
				'Personne.nom_complet' => array( 'type' => 'text' ),
				'Orientstruct.Personne.Foyer.Adressefoyer.0.Adresse.locaadr' => array( 'type' => 'text' ),
				// Orientation
				'Orientstruct.date_valid',
				'Orientstruct.Typeorient.lib_type_orient',
				'Orientstruct.Structurereferente.lib_struc',
				// Contrat d'insertion
				'Contratinsertion.date_saisi_ci',
				'Contratinsertion.Structurereferente.Typeorient.lib_type_orient',
				'Contratinsertion.Structurereferente.lib_struc',
				'Bilanparcours66.saisineepparcours' => array( 'type' => 'boolean' ),
				'Saisineepbilanparcours66.Dossierep.etapedossierep'
			),
			array(
				'actions' => array(
					'Bilansparcours66::view' => array( 'label' => 'Voir', 'url' => array( 'controller' => 'bilansparcours66', 'action' => 'index', '#Orientstruct.personne_id#' ) )
				),
				'groupColumns' => array(
					'Orientation' => array( 1, 2, 3 ),
					'Contrat d\'insertion' => array( 4, 5, 6 ),
					'Équipe pluridisciplinaire' => array( 7, 8 ),
				),
				'paginate' => 'Bilanparcours66',
				'options' => $options
			)
		);

// 		debug( $bilansparcours66 );
	?>
<?php else:?>
	<?php
		if( Configure::read( 'nom_form_bilan_cg' ) == 'cg66' ){
			$this->pageTitle = 'Bilan de parcours de la personne';
		}
		else {
			$this->pageTitle = 'Fiche de saisine de la personne';
		}

	?>
	<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id) );?>

	<div class="with_treemenu">
		<h1><?php echo $this->pageTitle;?></h1>

			<?php
// 				echo $default2->index(
// 					$bilansparcours66,
// 					array(
// 						'Bilanparcours66.created' => array( 'type' => 'date' ),
// 						// Orientation
// 						/*'Orientstruct.date_valid',
// 						'Orientstruct.Typeorient.lib_type_orient',*/
// 						'Orientstruct.Structurereferente.lib_struc',
// 						// Contrat d'insertion
// 						'Contratinsertion.date_saisi_ci',
// 						'Contratinsertion.Structurereferente.Typeorient.lib_type_orient',
// 						'Contratinsertion.Structurereferente.lib_struc',
// 						'Bilanparcours66.saisineepparcours' => array( 'type' => 'boolean' ),
// 						'Saisineepbilanparcours66.Dossierep.etapedossierep'
// 					),
// 					array(
// 						/*'groupColumns' => array(
// 							'Orientation' => array( 1, 2, 3 ),
// 							'Contrat d\'insertion' => array( 4, 5, 6 ),
// 							'Équipe pluridisciplinaire' => array( 7, 8 ),
// 						),*/
// 						'paginate' => 'Bilanparcours66',
// 						'options' => $options,
// 						'add' => true
// 					)
// 				);

				if( empty( $nborientstruct ) ) {
					echo '<p class="error">Cette personne ne possède pas d\'orientation. Veuillez en saisir une pour pouvoir poursuivre.</p>';
				}
				else {
// 					debug($bilansparcours66);
					echo "<ul class='actions'><li class='add'>";
						echo $default2->button('add', array('controller'=>'bilansparcours66', 'action'=>'add', $personne_id));
					echo "</li></ul>";

					echo "<table><thead><tr>";
						echo "<th>".__d('bilanparcours66', 'Bilanparcours66.datebilan', true)."</th>";
						echo "<th>".__d('structurereferente', 'Structurereferente.lib_struc',true)."</th>";
						echo "<th>Nom du référent</th>";
						echo "<th>".__d('bilanparcours66', 'Bilanparcours66.proposition', true)."</th>";
						echo "<th>Motif de la saisine</th>";
						echo "<th colspan='2'>".__d('saisineepbilanparcours66', 'Saisineepbilanparcours66.propref', true)."</th>";
						echo "<th colspan='2'>".__d('saisineepbilanparcours66', 'Saisineepbilanparcours66.avisep', true)."</th>";
						echo "<th colspan='2'>".__d('saisineepbilanparcours66', 'Saisineepbilanparcours66.decisioncg', true)."</th>";
						echo "<th colspan='2'>Actions</th>";
					echo "</tr></thead><tbody>";

					foreach($bilansparcours66 as $bilanparcour66) {
// debug($bilanparcour66);
						echo "<tr>";
							echo $type2->format( $bilanparcour66, 'Bilanparcours66.datebilan', array( 'type' => 'date', 'tag' => 'td', 'options' => $options ) );
							echo $type2->format( $bilanparcour66, 'Referent.Structurereferente.lib_struc', array( 'tag' => 'td', 'options' => $options ) );
							echo $type2->format( $bilanparcour66, 'Referent.nom_complet', array( 'type' => 'text', 'tag' => 'td', 'options' => $options ) );
							echo $type2->format( $bilanparcour66, 'Bilanparcours66.proposition', array( 'tag' => 'td', 'options' => $options ) );


							if (empty($bilanparcour66['Bilanparcours66']['choixparcours']) && !empty($bilanparcour66['Bilanparcours66']['examenaudition'])) {
								echo $type2->format( $bilanparcour66, 'Bilanparcours66.examenaudition', array( 'tag' => 'td', 'options' => $options ) );
							}
							elseif (empty($bilanparcour66['Bilanparcours66']['choixparcours']) && empty($bilanparcour66['Bilanparcours66']['examenaudition'])) {
								if ($bilanparcour66['Bilanparcours66']['maintienorientation']==0) {
									$bilanparcour66['Bilanparcours66']['choixparcours']='reorientation';
								}
								else {
									$bilanparcour66['Bilanparcours66']['choixparcours']='maintien';
								}
								echo $type2->format( $bilanparcour66, 'Bilanparcours66.choixparcours', array( 'tag' => 'td', 'options' => $options ) );
							}
							else {
								echo $type2->format( $bilanparcour66, 'Bilanparcours66.choixparcours', array( 'tag' => 'td', 'options' => $options ) );
							}
							//Proposition du référent - Type orientation
							if (Set::classicExtract( $bilanparcour66, 'Saisineepbilanparcours66.typeorient_id' )) {
                                echo $xhtml->tag(
                                    'td',
                                    Set::classicExtract( $typesorients, Set::classicExtract( $bilanparcour66, 'Saisineepbilanparcours66.typeorient_id' ) )
                                );
                            }
                            else {
                                echo "<td></td>";
                            }
// 							echo $type2->format( $bilanparcour66, 'Saisineepbilanparcours66.typeorient_id', array( 'tag' => 'td', 'options' => $typesorients ) );
// debug( isset(Set::classicExtract( $bilanparcour66, 'Saisineepbilanparcours66.structurereferente_id' )) );
                            //Proposition du référent - structure referente
                            if ( Set::classicExtract( $bilanparcour66, 'Saisineepbilanparcours66.structurereferente_id' )) {
                                echo $xhtml->tag(
                                    'td',
                                    Set::classicExtract( $structuresreferentes, Set::classicExtract( $bilanparcour66, 'Saisineepbilanparcours66.structurereferente_id' ) )
                                );
                            }
                            else {
                                echo "<td></td>";
                            }
// 							echo $type2->format( $bilanparcour66, 'Saisineepbilanparcours66.structurereferente_id', array( 'tag' => 'td', 'options' => $structuresreferentes ) );

							if( Set::classicExtract( $bilanparcour66, 'Saisineepbilanparcours66.Nvsrepreorient66.0.typeorient_id') ) {
								echo $xhtml->tag(
									'td',
									Set::classicExtract( $typesorients, Set::classicExtract( $bilanparcour66, 'Saisineepbilanparcours66.Nvsrepreorient66.0.typeorient_id' ) )
								);
							}
							else {
								echo "<td></td>";
							}

							if( Set::classicExtract( $bilanparcour66, 'Saisineepbilanparcours66.Nvsrepreorient66.0.structurereferente_id' ) ) {
								echo $xhtml->tag(
									'td',
									Set::classicExtract( $structuresreferentes, Set::classicExtract( $bilanparcour66, 'Saisineepbilanparcours66.Nvsrepreorient66.0.structurereferente_id' ) )
								);
							}
							else {
								echo "<td></td>";
							}
							if( Set::classicExtract( $bilanparcour66, 'Saisineepbilanparcours66.Nvsrepreorient66.1.typeorient_id' ) ) {
								echo $xhtml->tag(
									'td',
									Set::classicExtract( $typesorients, Set::classicExtract( $bilanparcour66, 'Saisineepbilanparcours66.Nvsrepreorient66.1.typeorient_id' ) )
								);
							}
							else {
								echo "<td></td>";
							}
							if( Set::classicExtract( $bilanparcour66, 'Saisineepbilanparcours66.Nvsrepreorient66.1.structurereferente_id' ) ) {
								echo $xhtml->tag(
									'td',
									Set::classicExtract( $structuresreferentes, Set::classicExtract( $bilanparcour66, 'Saisineepbilanparcours66.Nvsrepreorient66.1.structurereferente_id' ) )
								);
							}
							else {
								echo "<td></td>";
							}
							echo $html->tag(
								'td',
								$xhtml->editLink( 'Modifier', array( 'controller'=>'bilansparcours66', 'action'=>'edit', Set::classicExtract($bilanparcour66, 'Bilanparcours66.id') ) )
							);
							echo $html->tag(
                                'td',
                                $xhtml->printLink( 'Imprimer', array( 'controller'=>'bilansparcours66', 'action'=>'gedooo', Set::classicExtract($bilanparcour66, 'Bilanparcours66.id') ), false  ) //FIXME: mise à false du bouton "Imprimer"
                            );
						echo "</tr>";
					}
					/*echo $default2->index(
						$bilansparcours66,
						array(
							'Bilanparcours66.datebilan' => array( 'type' => 'date' ),
							'Orientstruct.Structurereferente.lib_struc',
							'Referent.nom_complet' => array( 'type' => 'text' ),
							'Bilanparcours66.proposition',
							'Bilanparcours66.choixparcours',
							'Saisineepbilanparcours66.typeorient_id',
							'Saisineepbilanparcours66.structurereferente_id',
							'Saisineepbilanparcours66.Nvsrepreorient66.0.typeorient_id',
							'Saisineepbilanparcours66.Nvsrepreorient66.0.structurereferente_id',
							'Saisineepbilanparcours66.Nvsrepreorient66.1.typeorient_id',
							'Saisineepbilanparcours66.Nvsrepreorient66.1.structurereferente_id'
						),
						array(
							'actions' => array(
								'Bilansparcours66::edit'
							),
							'add' => array( 'url' => array( 'controller'=>'bilansparcours66', 'action'=>'add', $personne_id ) ),
							'options' => $options
						)
					);*/
					echo "</tbody></table>";
				}
			?>

	</div>
	<div class="clearer"><hr /></div>
<?php endif;?>
