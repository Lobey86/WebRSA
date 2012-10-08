<?php
	/*
	* INFO: Parfois la variable a le nom personne_id, parfois personneId
	* 	On met tout le monde d'accord (en camelcase)
	*/

	if( isset( ${Inflector::variable( 'personne_id' )} ) ) {
		$personne_id = ${Inflector::variable( 'personne_id' )};
	}

	if( isset( ${Inflector::variable( 'foyer_id' )} ) ) {
		$foyer_id = ${Inflector::variable( 'foyer_id' )};
	}

	/*
	* Recherche du dossier à afficher
	*/

	if( isset( $personne_id ) ) {
		$dossier = $this->requestAction( array( 'controller' => 'dossiers', 'action' => 'menu' ), array( 'personne_id' => $personne_id ) );
	}
	else if( isset( $foyer_id ) ) {
		$dossier = $this->requestAction( array( 'controller' => 'dossiers', 'action' => 'menu' ), array( 'foyer_id' => $foyer_id ) );
	}
	else if( isset( $id ) ) {
		$dossier = $this->requestAction( array( 'controller' => 'dossiers', 'action' => 'menu' ), array( 'id' => $id ) );
	}
?>

<div class="treemenu">

		<h2 >
			<?php if( Configure::read( 'UI.menu.large' ) ):?>
			<?php
				echo $this->Xhtml->link(
					$this->Xhtml->image( 'icons/bullet_toggle_plus2.png', array( 'alt' => '', 'title' => 'Étendre le menu ', 'width' => '12px' ) ),
					'#',
					array( 'onclick' => 'treeMenuExpandsAll( \''.Router::url( '/', true ).'\' ); return false;', 'id' => 'treemenuToggleLink' ),
					false,
					false
				);
			?>
			<?php endif;?>

			<?php
				echo $this->Xhtml->link( 'Dossier RSA '.$dossier['Dossier']['numdemrsa'], array( 'controller' => 'dossiers', 'action' => 'view', $dossier['Dossier']['id'] ) ).
				( $dossier['Dossier']['locked'] ? $this->Xhtml->image( 'icons/lock.png', array( 'alt' => '', 'title' => 'Dossier verrouillé' ) ) : null ).
				$this->Gestionanomaliebdd->foyerErreursPrestationsAllocataires( $dossier ).
				$this->Gestionanomaliebdd->foyerPersonnesSansPrestation( $dossier );
			?>
		</h2>

<?php $etatdosrsaValue = Set::classicExtract( $dossier, 'Situationdossierrsa.etatdosrsa' );?>

<?php
	if( isset( $personne_id ) ) {
		$personneDossier = Set::extract( $dossier, '/Foyer/Personne' );
		foreach( $personneDossier as $i => $personne ) {
			if( $personne_id == Set::classicExtract( $personne, 'Personne.id' ) ) {
				$personneDossier = Set::classicExtract( $personne, 'Personne.qual' ).' '.Set::classicExtract( $personne, 'Personne.nom' ).' '.Set::classicExtract( $personne, 'Personne.prenom' );
			}
		}

		if( Configure::read( 'UI.menu.lienDemandeur' ) ) {
			echo $this->Xhtml->tag(
				'p',
				$this->Xhtml->link( $personneDossier, sprintf( Configure::read( 'UI.menu.lienDemandeur' ), $dossier['Dossier']['matricule'] ), array(  'class' => 'external' ) ),
				array( 'class' => 'etatDossier' ),
				false,
				false
			);
		}
		else {
			echo $this->Xhtml->tag( 'p', $personneDossier, array( 'class' => 'etatDossier' ) );
		}
	}
?>


<p class="etatDossier">
<?php
    $etatdosrsa = ClassRegistry::init( 'Option' )->etatdosrsa();
//     debug($this->viewVars);
    echo ( isset( $etatdosrsa[$etatdosrsaValue] ) ? $etatdosrsa[$etatdosrsaValue] : 'Non défini' );?>
</p>

	<p class="etatDossier">
	<?php
		$numcaf = $dossier['Dossier']['matricule'];
		$fonorg = $dossier['Dossier']['fonorg'];

		if( !empty( $numcaf ) && !empty( $fonorg ) ) {
			echo 'N°'.( isset( $fonorg ) ? $fonorg : '' ).' : '.( isset( $numcaf ) ? $numcaf : '' );
		}
		else {
			echo '';
		}
	?>
	</p>




	<ul>
		<li><?php echo $this->Xhtml->link( 'Composition du foyer', array( 'controller' => 'personnes', 'action' => 'index', $dossier['Foyer']['id'] ) );?>
			<ul>
				<?php foreach( $dossier['Foyer']['Personne'] as $personne ):?>
					<li><?php
							echo $this->Xhtml->link(
								implode( ' ', array( '(', $personne['Prestation']['rolepers'], ')', $personne['qual'], $personne['nom'], $personne['prenom'] ) ),
								array( 'controller' => 'personnes', 'action' => 'view', $personne['id'] )
							);
						?>
							<!-- Début "Partie du sous-menu concernant uniquement le demandeur et son conjoint" -->
							<?php if( $personne['Prestation']['rolepers'] == 'DEM' || $personne['Prestation']['rolepers'] == 'CJT' ):?>
								<ul>
								<?php if( $this->Permissions->check( 'memos', 'index' ) && Configure::read( 'Cg.departement' ) == '66' ):?>
									<li>
										<?php
											echo $this->Xhtml->link(
												'Mémos',
												array( 'controller' => 'memos', 'action' => 'index', $personne['id'] )
											);
										?>
									</li>
									<?php endif;?>
								<?php if( $this->Permissions->check( 'situationsdossiersrsa', 'index' ) || $this->Permissions->check( 'detailsdroitsrsa', 'index' ) ):?>
									<li><span>Droit</span>
										<ul>
											<?php if( $this->Permissions->check( 'dsps', 'view' ) ):?>
												<li>
													<?php
														echo $this->Xhtml->link(
															'DSP d\'origine',
															array( 'controller' => 'dsps', 'action' => 'view', $personne['id'] )
														);?>
												</li>
											<?php endif;?>
											<?php if (Configure::read( 'Cg.departement' ) == 66 ):?>
                                                <?php if( $this->Permissions->check( 'dsps', 'histo' ) ):?>
                                                    <li>
                                                        <?php
                                                            echo $this->Xhtml->link(
                                                                'DSPs mises à jour',
                                                                array( 'controller' => 'dsps', 'action' => 'histo', $personne['id'] )
                                                            );?>
                                                    </li>
                                                <?php endif;?>
                                            <?php else:?>
                                                <?php if( $this->Permissions->check( 'dsps', 'histo' ) ):?>
                                                    <li>
                                                        <?php
                                                            echo $this->Xhtml->link(
                                                                'DSPs CG',
                                                                array( 'controller' => 'dsps', 'action' => 'histo', $personne['id'] )
                                                            );?>
                                                    </li>
                                                <?php endif;?>
                                            <?php endif;?>
											<!--<?php if( $this->Permissions->check( 'dspps', 'view' ) ):?>
												<li>
													<?php
														echo $this->Xhtml->link(
															'DSP CAF',
															array( 'controller' => 'dspps', 'action' => 'view', $personne['id'] )
														);?>
												</li>
											<?php endif;?>-->

											<?php if (Configure::read( 'nom_form_ci_cg' ) == 'cg58' ) { ?>

												<?php if( $this->Permissions->check( 'propospdos', 'index' ) ):?>
													<li>
														<?php
															echo $this->Xhtml->link(
																'Consultation dossier PDO',
																array( 'controller' => 'propospdos', 'action' => 'index', $personne['id'] )
															);
														?>
													</li>
												<?php endif;?>
												<?php if( $this->Permissions->check( 'orientsstructs', 'index' ) ):?>
													<li>
														<?php
															echo $this->Xhtml->link(
																'Orientation',
																array( 'controller' => 'orientsstructs', 'action' => 'index', $personne['id'] )
															);
														?>
													</li>
												<?php endif;?>

											<?php } elseif (Configure::read( 'nom_form_ci_cg' ) == 'cg66' ) { ?>

												<?php if( $this->Permissions->check( 'orientsstructs', 'index' ) ):?>
													<li>
														<?php
															echo $this->Xhtml->link(
																'Orientation',
																array( 'controller' => 'orientsstructs', 'action' => 'index', $personne['id'] )
															);
														?>
													</li>
												<?php endif;?>
												<?php if( $this->Permissions->check( 'traitementspcgs66', 'index' ) ):?>
													<li>
														<?php
															echo $this->Xhtml->link(
																'Traitements PCG',
																array( 'controller' => 'traitementspcgs66', 'action' => 'index', $personne['id'] )
															);
														?>
													</li>
												<?php endif;?>


											<?php } else { ?>

												<?php if( $this->Permissions->check( 'orientsstructs', 'index' ) ):?>
													<li>
														<?php
															echo $this->Xhtml->link(
																'Orientation',
																array( 'controller' => 'orientsstructs', 'action' => 'index', $personne['id'] )
															);
														?>
													</li>
												<?php endif;?>
												<?php if( $this->Permissions->check( 'propospdos', 'index' ) ):?>
													<li>
														<?php
															echo $this->Xhtml->link(
																'Consultation dossier PDO',
																array( 'controller' => 'propospdos', 'action' => 'index', $personne['id'] )
															);
														?>
													</li>
												<?php endif;?>

											<?php } ?>

										</ul>
									</li>
								<?php endif;?>


								<?php if( $this->Permissions->check( 'personnes_referents', 'index' ) || $this->Permissions->check( 'rendezvous', 'index' ) || $this->Permissions->check( 'contratsinsertion', 'index' ) || $this->Permissions->check( 'cuis', 'index' ) ):?>
									<li><span>Accompagnement du parcours</span>
										<ul>
											<li>
												<?php
													echo $this->Xhtml->link(
														'Chronologie parcours',
														'#'
//                                                         array( 'controller' => '#', 'action' => '#', $personne['id'] )
													);
												?>
											</li>
										<?php if( $this->Permissions->check( 'personnes_referents', 'index' ) ):?>
											<li>
												<?php
													echo $this->Xhtml->link(
														'Référent du parcours',
														array( 'controller' => 'personnes_referents', 'action' => 'index', $personne['id'] )
													);
												?>
											</li>
										<?php endif;?>
										<?php if( $this->Permissions->check( 'rendezvous', 'index' ) ):?>
											<li>
												<?php
													echo $this->Xhtml->link(
														'Gestion RDV',
														array( 'controller' => 'rendezvous', 'action' => 'index', $personne['id'] )
													);
												?>
											</li>
										<?php endif;?>
										<?php if( $this->Permissions->check( 'bilansparcours66', 'index' ) ):?>
											<li>
												<?php
													if( Configure::read( 'nom_form_bilan_cg' ) == 'cg66' ){
														echo $this->Xhtml->link(
															'Bilan du parcours',
                                                            array( 'controller' => 'bilansparcours66', 'action' => 'index', $personne['id'] )
														);
													}
												?>
											</li>
										<?php endif;?>
											<li><span>Contrats</span>
												<ul>
												<?php if( $this->Permissions->check( 'contratsinsertion', 'index' ) ):?>
													<li>
														<?php
															echo $this->Xhtml->link(
																'CER',
																array( 'controller' => 'contratsinsertion', 'action' => 'index', $personne['id'] )
															);
														?>
													</li>
												<?php endif;?>
												<?php if( $this->Permissions->check( 'cuis', 'index' ) ):?>
													<li>
														<?php
															echo $this->Xhtml->link(
																'CUI',
																array( 'controller' => 'cuis', 'action' => 'index', $personne['id'] )
															);
														?>
													</li>
												<?php endif;?>
												</ul>
											</li>
											<?php if( $this->Permissions->check( 'entretiens', 'index' ) || $this->Permissions->check( 'relancesnonrespectssanctionseps93', 'index' ) ):?>
											<li><span>Actualisation suivi</span>
												<ul>
													<?php if( $this->Permissions->check( 'entretiens', 'index' ) ):?>
														<li>
															<?php
																echo $this->Xhtml->link(
																	'Entretiens',
																	array( 'controller' => 'entretiens', 'action' => 'index', $personne['id'] )
																);
															?>
														</li>
													<?php endif;?>
													<?php if( Configure::read( 'Cg.departement' ) == 93 && $this->Permissions->check( 'relancesnonrespectssanctionseps93', 'index' ) ):?>
														<li>
															<?php
																echo $this->Xhtml->link(
																	'Relances',
																	array( 'controller' => 'relancesnonrespectssanctionseps93', 'action' => 'index', $personne['id'] )
																);
															?>
														</li>
													<?php endif;?>
												</ul>
											</li>
											<?php endif;?>
											<?php if( $this->Permissions->check( 'historiqueseps', 'index' ) ):?>
                                            <li>
                                                <?php
                                                    echo $this->Xhtml->link(
                                                        'Historique des EPs',
                                                        array( 'controller' => 'historiqueseps', 'action' => 'index', $personne['id'] )
                                                    );
                                                ?>
                                            </li>
                                            <?php endif;?>
											<li><span>Offre d'insertion</span>
												<ul>
												<!-- <li>
														<?php
															/*echo $this->Xhtml->link(
																'Recherche action',
																array( 'controller' => 'actionscandidats_personnes', 'action' => 'index', $personne['id'] )
															);*/
														?>
													</li> -->
													<?php if( $this->Permissions->check( 'actionscandidats_personnes', 'index' ) ):?>
													<li>
														<?php
															if( Configure::read( 'ActioncandidatPersonne.suffixe' ) == 'cg93' ){
																echo $this->Xhtml->link(
																	'Fiche de liaison',
																	array( 'controller' => 'actionscandidats_personnes', 'action' => 'index', $personne['id'] )
																);
															}
															else{
																echo $this->Xhtml->link(
																	'Fiche de candidature',
																	array( 'controller' => 'actionscandidats_personnes', 'action' => 'index', $personne['id'] )
																);
															}
														?>
													</li>
													<?php endif;?>
												</ul>
											</li>
											<li><span>Aides financières</span>
												<ul>
												<?php if( $this->Permissions->check( 'apres'.Configure::read( 'Apre.suffixe' ), 'index' ) ):?>
													<li>
														<?php
															echo $this->Xhtml->link(
																'Aides / APRE',
																array( 'controller' => 'apres'.Configure::read( 'Apre.suffixe' ), 'action' => 'index', $personne['id'] )
															);
														?>
													</li>
													<?php endif;?>
												</ul>
											</li>
											<!--<li><span>Documents scannés</span>
												<ul>
													<li>
														<?php
// 															echo $this->Xhtml->link(
// 																'Courriers',
// 																'#'
// //                                                                 array( 'controller' => 'apres'.Configure::read( 'Apre.suffixe' ), 'action' => 'index', $personne['id'] )
// 															);
														?>
													</li>
												</ul>
											</li>-->
											<?php if( $this->Permissions->check( 'memos', 'index' ) && Configure::read( 'Cg.departement' ) != 66 ):?>
											<li>
												<?php
													echo $this->Xhtml->link(
														'Mémos',
														array( 'controller' => 'memos', 'action' => 'index', $personne['id'] )
													);
												?>
											</li>
											<?php endif;?>

											<?php if( $this->Permissions->check( 'historiqueemplois', 'index' ) && Configure::read( 'Cg.departement' ) == 93 ):?>
											<li>
												<?php
													echo $this->Xhtml->link(
														__d( 'historiqueemploi', 'Historiqueemplois::index' ),
														array( 'controller' => 'historiqueemplois', 'action' => 'index', $personne['id'] )
													);
												?>
											</li>
											<?php endif;?>

										</ul>
									</li>
								<?php endif;?>

								<?php if( $this->Permissions->check( 'ressources', 'index' ) || $this->Permissions->check( 'indus', 'index' ) ):?>
									<li><span>Situation financière</span>
										<ul>
											<li>
												<?php
													echo $this->Xhtml->link(
														'Ressources',
														array( 'controller' => 'ressources', 'action' => 'index', $personne['id'] )
													);
												?>
											</li>
										</ul>
									</li>
								<?php endif;?>

							</ul>
							<?php endif;?>
							<!-- Fin "Partie du sous-menu concernant uniquement le demandeur et son conjoint" -->

							<!-- Début "Partie du sous-menu concernant toutes les personnes du foyer" -->
							<!--<?php if( $this->Permissions->check( 'dossierscaf', 'view' ) ):?>
								<li>
									<?php
										echo $this->Xhtml->link(
											'Dossier CAF',
											array( 'controller' => 'dossierscaf', 'action' => 'view', $personne['id'] )
										);
									?>
								</li>
							<?php endif;?>-->
							<!-- Fin "Partie du sous-menu concernant toutes les personnes du foyer" -->
					</li>
				<?php endforeach;?>
			</ul>
		</li>
		<!-- TODO: permissions à partir d'ici et dans les fichiers concernés -->
		<li><span>Informations foyer</span>
			<ul>
				<?php if( $this->Permissions->check( 'situationsdossiersrsa', 'index' ) || $this->Permissions->check( 'detailsdroitsrsa', 'index' ) || $this->Permissions->check( 'dossierspdo', 'index' ) ):?>
					<li>
						<?php
							echo $this->Xhtml->link(
								'Historique du droit',
								array( 'controller' => 'situationsdossiersrsa', 'action' => 'index', $dossier['Dossier']['id'] )
							);
						?>
					</li>
					<li>
						<?php
							echo $this->Xhtml->link(
								'Détails du droit RSA',
								array( 'controller' => 'detailsdroitsrsa', 'action' => 'index', $dossier['Dossier']['id'] )
							);
						?>
					</li>
				<?php endif;?>
				<?php if( $this->Permissions->check( 'adressesfoyers', 'index' ) ):?>
					<li><?php echo $this->Xhtml->link( 'Adresses', array( 'controller' => 'adressesfoyers', 'action' => 'index', $dossier['Foyer']['id'] ) );?>
						<?php if( !empty( $dossier['Foyer']['AdressesFoyer'] ) ):?>
							<?php if( $this->Permissions->check( 'adressesfoyers', 'view' ) ):?>
								<ul>
									<?php foreach( $dossier['Foyer']['AdressesFoyer'] as $AdressesFoyer ):?>
										<li><?php echo $this->Xhtml->link(
												implode( ' ', array( $AdressesFoyer['Adresse']['numvoie'], isset( $typevoie[$AdressesFoyer['Adresse']['typevoie']] ) ? $typevoie[$AdressesFoyer['Adresse']['typevoie']] : null, $AdressesFoyer['Adresse']['nomvoie'] ) ),
												array( 'controller' => 'adressesfoyers', 'action' => 'view', $AdressesFoyer['id'] ) );
											;?></li>
									<?php endforeach;?>
								</ul>
							<?php endif;?>
						<?php endif;?>
					</li>
				<?php endif;?>

				<?php if( $this->Permissions->check( 'evenements', 'index' ) ):?>
					<li>
						<?php
							echo $this->Xhtml->link(
								'Evénements',
								array( 'controller' => 'evenements', 'action' => 'index', $dossier['Foyer']['id'] )
							);
						?>
					</li>
				<?php  endif;?>

				<?php if( $this->Permissions->check( 'modescontact', 'index' ) ):?>
					<li>
						<?php
							echo $this->Xhtml->link(
								'Modes de contact',
								array( 'controller' => 'modescontact', 'action' => 'index', $dossier['Foyer']['id'] )
							);
						?>
					</li>
				<?php endif;?>

				<?php if( $this->Permissions->check( 'avispcgdroitrsa', 'index' ) ):?>
					<li>
						<?php
							echo $this->Xhtml->link(
								'Avis PCG droit rsa',
								array( 'controller' => 'avispcgdroitrsa', 'action' => 'index', $dossier['Dossier']['id'] )
							);
						?>
					</li>
				<?php endif;?>

				<?php if( $this->Permissions->check( 'infosfinancieres', 'index' ) ):?>
					<li>
						<?php
							echo $this->Xhtml->link(
								'Informations financières',
								array( 'controller' => 'infosfinancieres', 'action' => 'index', $dossier['Dossier']['id'] )
							);
						?>
					</li>
				<?php endif;?>
				<?php if( $this->Permissions->check( 'indus', 'index' ) ):?>
					<li>
						<?php
							echo $this->Xhtml->link(
								'Liste des Indus',
								array( 'controller' => 'indus', 'action' => 'index', $dossier['Dossier']['id'] )
							);
						?>
					</li>
				<?php endif;?>
			<?php if( $this->Permissions->check( 'suivisinstruction', 'index' ) ):?>
					<li>
						<?php
							echo $this->Xhtml->link(
								'Suivi instruction du dossier',
								array( 'controller' => 'suivisinstruction', 'action' => 'index', $dossier['Dossier']['id'] )
							);
						?>
					</li>
				<?php endif;?>
			</ul>
		</li>

		<?php if( $this->Permissions->check( 'dossierspcgs66', 'view' ) && Configure::read( 'Cg.departement' ) == 66 ):?>
			<?php
				echo '<li>'.$this->Xhtml->link(
					'Dossier PCG',
					array( 'controller' => 'dossierspcgs66', 'action' => 'index', $dossier['Foyer']['id'] )
				).'</li>';
			?>
		<?php endif;?>


		<?php if( $this->Permissions->check( 'infoscomplementaires', 'view' ) ):?>
			<?php
				echo '<li>'.$this->Xhtml->link(
					'Informations complémentaires',
					array( 'controller' => 'infoscomplementaires', 'action' => 'view', $dossier['Dossier']['id'] )
				).'</li>';
			?>
		<?php endif;?>

		<?php if( $this->Permissions->check( 'suivisinsertion', 'index' ) ):?>
			<?php
				echo '<li>'.$this->Xhtml->link(
					'Synthèse du parcours d\'insertion',
					array( 'controller' => 'suivisinsertion', 'action' => 'index', $dossier['Dossier']['id'] )
				).'</li>';
			?>
		<?php endif;?>

		
		<?php if( $this->Permissions->check( 'dossiers', 'edit' ) ):?>
			<?php
				echo '<li>'.$this->Xhtml->link(
					'Modification Dossier RSA',
					array( 'controller' => 'dossiers', 'action' => 'edit', $dossier['Dossier']['id'] )
				).'</li>';
			?>
		<?php endif;?>

		
		<?php if( $this->Permissions->check( 'dossierssimplifies', 'edit' ) && Configure::read( 'Cg.departement' ) != 58 ):?>
			<li><span>Préconisation d'orientation</span>
				<ul>
					<?php if( !empty( $dossier['Foyer']['Personne'] ) ):?>
						<li>
							<?php foreach( $dossier['Foyer']['Personne'] as $personnes ):?>
								<?php if( $personnes['Prestation']['rolepers'] == 'DEM' || $personnes['Prestation']['rolepers'] == 'CJT' ):?>
									<?php
										echo $this->Xhtml->link(
											$personnes['qual'].' '.$personnes['nom'].' '.$personnes['prenom'],
											array( 'controller' => 'dossierssimplifies', 'action' => 'edit', $personnes['id'] )
										);
									?>
								<?php endif ?>
							<?php endforeach?>
						</li>
					<?php endif?>
				</ul>
			</li>
		<?php endif;?>
	</ul>
</div>
