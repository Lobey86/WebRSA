<div id="menu1Wrapper">
	<div class="menu1">
		<ul>
		<?php if( $session->check( 'Auth.User' ) ): ?>
			<?php if(
					$permissions->check( 'cohortes', 'nouvelles' )
					|| $permissions->check( 'cohortes', 'orientees' )
					|| $permissions->check( 'cohortes', 'enattente' )
					|| $permissions->check( 'cohortesvalidationapres66', 'apresavalider' )
					|| $permissions->check( 'cohortesvalidationapres66', 'notifiees' )
					|| $permissions->check( 'cohortesvalidationapres66', 'validees' )
					|| $permissions->check( 'cohortesvalidationapres66', 'traitement' )
					|| $permissions->check( 'cohortesci', 'nouveaux' )
					|| $permissions->check( 'cohortesci', 'valides' )
					|| $permissions->check( 'cohortesci', 'nouveauxsimple' )
					|| $permissions->check( 'cohortesci', 'nouveauxparticulier' )
					|| $permissions->check( 'cohortesci', 'nouveauxparticulier' )
					|| $permissions->check( 'cohortescui', 'nouveaux' )
					|| $permissions->check( 'cohortescui', 'enattente' )
					|| $permissions->check( 'cohortescui', 'valides' )
					|| $permissions->check( 'cohortesfichescandidature66', 'fichesenattente' )
					|| $permissions->check( 'cohortesfichescandidature66', 'fichesencours' )
					|| $permissions->check( 'cohortesdossierspcgs66', 'enattenteaffectation' )
					|| $permissions->check( 'cohortesdossierspcgs66', 'affectes' )
					|| $permissions->check( 'cohortesdossierspcgs66', 'aimprimer' )
					|| $permissions->check( 'cohortesdossierspcgs66', 'atransmettre' )
					|| $permissions->check( 'cohortesnonorientes66', 'isemploi' )
					|| $permissions->check( 'cohortesnonorientes66', 'notisemploi' )
					|| $permissions->check( 'cohortesnonorientes66', 'notisemploiaimprimer' )
					|| $permissions->check( 'cohortesnonorientes66', 'oriente' )
					|| $permissions->check( 'cohortespdos', 'avisdemande' )
					|| $permissions->check( 'cohortespdos', 'valide' )
					|| $permissions->check( 'cohortespdos', 'enattente' )
					|| $permissions->check( 'relancesnonrespectssanctionseps93', 'cohorte' )
					|| $permissions->check( 'relancesnonrespectssanctionseps93', 'impressions' )
					|| $permissions->check( 'nonorientationsproseps', 'index' )
					|| $permissions->check( 'nonorientationsproseps', 'selectionradies' )
				):?>
				<li id="menu1one" onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
					<?php
						if( Configure::read( 'Cg.departement' ) == 66 ) {
							echo $xhtml->link( 'Gestion de listes', '#' );
						}
						else {
							echo $xhtml->link( 'Cohortes', '#' );
						}
					?>
					<ul>
						<?php if( ( Configure::read( 'Cg.departement' ) == 66 ) && ( $permissions->check( 'cohortesvalidationapres66', 'apresavalider' ) || $permissions->check( 'cohortesvalidationapres66', 'notifiees' ) || $permissions->check( 'cohortesvalidationapres66', 'transfert' ) || $permissions->check( 'cohortesvalidationapres66', 'validees' ) || $permissions->check( 'cohortesvalidationapres66', 'traitement' ) ) ):?>
							<!-- AJOUT POUR LA GESTION DES CONTRATS D'ENGAGEMENT RECIPROQUE (Cohorte) -->
							<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
								<?php  echo $xhtml->link( 'APRE ', '#' );?>
									<ul>
										<?php if( $permissions->check( 'cohortesvalidationapres66', 'apresavalider' ) ): ?>
											<li><?php echo $xhtml->link( 'À valider', array( 'controller' => 'cohortesvalidationapres66', 'action' => 'apresavalider' ), array( 'title' => 'À valider' ) );?></li>
										<?php endif; ?>
										<?php if( $permissions->check( 'cohortesvalidationapres66', 'validees' ) ): ?>
											<li><?php echo $xhtml->link( 'À notifier', array( 'controller' => 'cohortesvalidationapres66', 'action' => 'validees' ), array( 'title' => 'À notifier' ) );?></li>
										<?php endif; ?>
										<?php if( $permissions->check( 'cohortesvalidationapres66', 'notifiees' ) ): ?>
											<li><?php echo $xhtml->link( 'Notifiées', array( 'controller' => 'cohortesvalidationapres66', 'action' => 'notifiees' ), array( 'title' => 'Notifiées' ) );?></li>
										<?php endif; ?>
										<?php if( $permissions->check( 'cohortesvalidationapres66', 'transfert' ) ): ?>
											<li><?php echo $xhtml->link( 'Transfert cellule', array( 'controller' => 'cohortesvalidationapres66', 'action' => 'transfert' ), array( 'title' => 'Transfert cellule' ) );?></li>
										<?php endif; ?>
										<?php if( $permissions->check( 'cohortesvalidationapres66', 'traitement' ) ): ?>
											<li><?php echo $xhtml->link( 'Traitement cellule', array( 'controller' => 'cohortesvalidationapres66', 'action' => 'traitement' ), array( 'title' => 'Traitement cellule' ) );?></li>
										<?php endif; ?>
									</ul>
							</li>
						<?php endif;?>
						<?php if( in_array( Configure::read( 'Cg.departement' ), array( 66, 93 ) ) && ( $permissions->check( 'cohortesci', 'nouveaux' ) || $permissions->check( 'cohortesci', 'valides' ) || $permissions->check( 'cohortesci', 'nouveauxsimple' ) || $permissions->check( 'cohortesci', 'nouveauxparticulier' ) ) ) :?>
							<!-- AJOUT POUR LA GESTION DES CONTRATS D'ENGAGEMENT RECIPROQUE (Cohorte) -->
							<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
								<?php  echo $xhtml->link( 'CER ', '#' );?>
									<ul>
										<?php if( Configure::read( 'Cg.departement' ) == 66 ):?>
											<?php if( $permissions->check( 'cohortesci', 'nouveauxsimple' ) ): ?>
												<li><?php echo $xhtml->link( 'Contrats Simples à valider', array( 'controller' => 'cohortesci', 'action' => 'nouveauxsimple' ), array( 'title' => 'Contrats Simples à valider' ) );?></li>
											<?php endif; ?>
											<?php if( $permissions->check( 'cohortesci', 'nouveauxparticulier' ) ): ?>
												<li><?php echo $xhtml->link( 'Contrats Particuliers à valider', array( 'controller' => 'cohortesci', 'action' => 'nouveauxparticulier' ), array( 'title' => 'Contrats Particuliers à valider' ) );?></li>
											<?php endif; ?>
											<?php if( $permissions->check( 'cohortesci', 'valides' ) ): ?>
												<li><?php echo $xhtml->link( 'Décisions prises', array( 'controller' => 'cohortesci', 'action' => 'valides' ), array( 'title' => 'Décisions prises' ) );?></li>
											<?php endif; ?>
										<?php elseif( Configure::read( 'Cg.departement' ) == 93 ):?>
											<?php if( $permissions->check( 'cohortesci', 'nouveaux' ) ): ?>
												<li><?php echo $xhtml->link( 'Contrats à valider', array( 'controller' => 'cohortesci', 'action' => 'nouveaux' ), array( 'title' => 'Contrats à valider' ) );?></li>
											<?php endif; ?>
											<?php if( $permissions->check( 'cohortesci', 'valides' ) ): ?>
												<li><?php echo $xhtml->link( 'Contrats validés', array( 'controller' => 'cohortesci', 'action' => 'valides' ), array( 'title' => 'Contrats validés' ) );?></li>
											<?php endif; ?>
										<?php endif; ?>
									</ul>
							</li>
						<?php endif;?>

						<?php if( $permissions->check( 'cohortescui', 'nouveaux' ) || $permissions->check( 'cohortescui', 'valides' ) || $permissions->check( 'cohortescui', 'enattente' ) ):?>
							<!-- AJOUT POUR LA GESTION DES CONTRATS D'ENGAGEMENT RECIPROQUE (Cohorte) -->
							<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
								<?php  echo $xhtml->link( 'CUI ', '#' );?>
									<ul>
										<?php if( $permissions->check( 'cohortescui', 'nouveaux' ) ): ?>
											<li><?php echo $xhtml->link( 'CUIs à valider', array( 'controller' => 'cohortescui', 'action' => 'nouveaux' ), array( 'title' => 'Contrats à valider' ) );?></li>
										<?php endif; ?>
										<?php if( $permissions->check( 'cohortescui', 'enattente' ) ): ?>
											<li><?php echo $xhtml->link( 'En attente', array( 'controller' => 'cohortescui', 'action' => 'enattente' ), array( 'title' => 'Contrats en attente' ) );?></li>
										<?php endif; ?>
										<?php if( $permissions->check( 'cohortescui', 'valides' ) ): ?>
											<li><?php echo $xhtml->link( 'CUIs validés', array( 'controller' => 'cohortescui', 'action' => 'valides' ), array( 'title' => 'Contrats validés' ) );?></li>
										<?php endif; ?>
									</ul>
							</li>
						<?php endif;?>
						<?php if( ( Configure::read( 'Cg.departement' ) == 66 ) && ( $permissions->check( 'cohortesfichescandidature66', 'fichesenattente' ) || $permissions->check( 'cohortesfichescandidature66', 'fichesencours' ) ) ):?>
                            <!-- AJOUT POUR LA GESTION DES Fiches de candidature 66 (Cohorte) -->
                            <li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
                                <?php  echo $xhtml->link( 'Fiches de candidature ', '#' );?>
                                    <ul>
                                        <?php if( $permissions->check( 'cohortesfichescandidature66', 'fichesenattente' ) ): ?>
                                            <li><?php echo $xhtml->link( 'Fiches en attente', array( 'controller' => 'cohortesfichescandidature66', 'action' => 'fichesenattente' ), array( 'title' => 'Fiches en attente' ) );?></li>
                                        <?php endif; ?>
                                        <?php if( $permissions->check( 'cohortesfichescandidature66', 'fichesencours' ) ): ?>
                                            <li><?php echo $xhtml->link( 'Fiches en cours', array( 'controller' => 'cohortesfichescandidature66', 'action' => 'fichesencours' ), array( 'title' => 'Fiches en cours' ) );?></li>
                                        <?php endif; ?>
                                    </ul>
                            </li>
                        <?php endif;?>
						<?php if( ( Configure::read( 'Cg.departement' ) == 66 ) && ( $permissions->check( 'cohortesdossierspcgs66', 'enattenteaffectation' ) || $permissions->check( 'cohortesdossierspcgs66', 'affectes' ) || $permissions->check( 'cohortesdossierspcgs66', 'aimprimer' ) || $permissions->check( 'cohortesdossierspcgs66', 'atransmettre' ) ) ):?>
                            <!-- AJOUT POUR LA GESTION DES Fiches de candidature 66 (Cohorte) -->
                            <li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
                                <?php  echo $xhtml->link( 'Dossiers PCGs ', '#' );?>
                                    <ul>
                                        <?php if( $permissions->check( 'cohortesdossierspcgs66', 'enattenteaffectation' ) ): ?>
                                            <li>
												<?php echo $xhtml->link( 'Dossiers en attente d\'affectation', array( 'controller' => 'cohortesdossierspcgs66', 'action' => 'enattenteaffectation' ), array( 'title' => 'Dossiers en attente d\'affectation' ) );?>
											</li>
                                        <?php endif; ?>
                                        <?php if( $permissions->check( 'cohortesdossierspcgs66', 'affectes' ) ): ?>
                                            <li>
												<?php echo $xhtml->link( 'Dossiers affectés', array( 'controller' => 'cohortesdossierspcgs66', 'action' => 'affectes' ), array( 'title' => 'Dossiers affectés' ) );?>
											</li>
                                        <?php endif; ?>
                                        <?php if( $permissions->check( 'cohortesdossierspcgs66', 'aimprimer' ) ): ?>
                                            <li>
												<?php echo $xhtml->link( 'Dossiers à imprimer', array( 'controller' => 'cohortesdossierspcgs66', 'action' => 'aimprimer' ), array( 'title' => 'Dossiers à imprimer' ) );?>
											</li>
                                        <?php endif; ?>
                                        <?php if( $permissions->check( 'cohortesdossierspcgs66', 'atransmettre' ) ): ?>
                                            <li>
												<?php echo $xhtml->link( 'Dossiers à transmettre', array( 'controller' => 'cohortesdossierspcgs66', 'action' => 'atransmettre' ), array( 'title' => 'Dossiers à transmettre' ) );?>
											</li>
                                        <?php endif; ?>
                                    </ul>
                            </li>
                        <?php endif;?>
                        <?php if( ( Configure::read( 'Cg.departement' ) == 66 ) && ( $permissions->check( 'cohortesnonorientes66', 'isemploi' ) || $permissions->check( 'cohortesnonorientes66', 'notisemploi' ) || $permissions->check( 'cohortesnonorientes66', 'notisemploiaimprimer' ) || $permissions->check( 'cohortesnonorientes66', 'oriente' ) ) ):?>
                            <!-- AJOUT POUR LA GESTION DES Non orientés 66 (Cohorte) -->
                            <li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
                                <?php  echo $xhtml->link( 'Non orientation ', '#' );?>
                                    <ul>
                                        <?php if( $permissions->check( 'cohortesnonorientes66', 'isemploi' ) ): ?>
                                            <li>
												<?php echo $xhtml->link( 'Inscrits PE', array( 'controller' => 'cohortesnonorientes66', 'action' => 'isemploi' ), array( 'title' => 'Inscrits PE' ) );?>
											</li>
                                        <?php endif; ?>
                                        <?php if( $permissions->check( 'cohortesnonorientes66', 'notisemploiaimprimer' ) ): ?>
                                            <li>
												<?php echo $xhtml->link( 'Non inscrits PE', array( 'controller' => 'cohortesnonorientes66', 'action' => 'notisemploiaimprimer' ), array( 'title' => 'Non inscrits PE' ) );?>
											</li>
                                        <?php endif; ?>
                                        <?php if( $permissions->check( 'cohortesnonorientes66', 'notisemploi' ) ): ?>
                                            <li>
												<?php echo $xhtml->link( 'Gestion des réponses', array( 'controller' => 'cohortesnonorientes66', 'action' => 'notisemploi' ), array( 'title' => 'Gestion des réponses' ) );?>
											</li>
                                        <?php endif; ?>
                                        <?php if( $permissions->check( 'cohortesnonorientes66', 'notifaenvoyer' ) ): ?>
                                            <li>
												<?php echo $xhtml->link( 'Notifications à envoyer', array( 'controller' => 'cohortesnonorientes66', 'action' => 'notifaenvoyer' ), array( 'title' => 'Notifications à envoyer' ) );?>
											</li>
                                        <?php endif; ?>
                                        <?php if( $permissions->check( 'cohortesnonorientes66', 'oriente' ) ): ?>
                                            <li>
												<?php echo $xhtml->link( 'Notifications envoyées', array( 'controller' => 'cohortesnonorientes66', 'action' => 'oriente' ), array( 'title' => 'Notifications envoyées' ) );?>
											</li>
                                        <?php endif; ?>
                                    </ul>
                            </li>
                        <?php endif;?>
						<?php if( $permissions->check( 'cohortes', 'nouvelles' ) || $permissions->check( 'cohortes', 'orientees' ) || $permissions->check( 'cohortes', 'enattente' ) /*|| $permissions->check( 'cohortes', 'preconisationscalculables' )|| $permissions->check( 'cohortes', 'preconisationsnoncalculables' ) || $permissions->check( 'cohortes', 'statistiques' )*/ ): ?>
							<!-- MODIF POUR LA GESTION DES ORIENTATIONS (Cohorte) -->
							<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
								<?php  echo $xhtml->link( 'Orientation', '#' );?>
									<ul>
										<?php /*if( $permissions->check( 'cohortes', 'statistiques' ) ): ?>
											<li><?php echo $xhtml->link( 'Statistiques', array( 'controller' => 'cohortes', 'action' => 'statistiques' ), array( 'title'=>'Statistiques' ) );?></li>
										<?php endif; */ ?>
										<?php if( $permissions->check( 'cohortes', 'nouvelles' ) ): ?>
											<li><?php echo $xhtml->link( 'Demandes non orientées', array( 'controller' => 'cohortes', 'action' => 'nouvelles' ), array( 'title'=>'Demandes non orientées' ) );?></li>
										<?php endif; ?>
										<?php if( $permissions->check( 'cohortes', 'enattente' ) ): ?>
											<li><?php echo $xhtml->link( 'Demandes en attente de validation d\'orientation', array( 'controller' => 'cohortes', 'action' => 'enattente' ), array( 'title'=>'Demandes en attente de validation d\'orientation' ) );?></li>
										<?php endif; ?>
										<?php /*if( $permissions->check( 'cohortes', 'preconisationscalculables' ) ): ?>
											<li><?php echo $xhtml->link( 'Demandes d\'orientation préorientées', array( 'controller' => 'cohortes', 'action' => 'preconisationscalculables' ), array( 'title'=>'Demandes à orienter, possédant une préconisation' ) );?></li>
										<?php endif; ?>
										<?php if( $permissions->check( 'cohortes', 'preconisationsnoncalculables' ) ): ?>
											<li><?php echo $xhtml->link( 'Demandes d\'orientation non préorientées', array( 'controller' => 'cohortes', 'action' => 'preconisationsnoncalculables' ), array( 'title'=>'Demandes à orienter, ne possédant pas de préconisation' ) );?></li>
										<?php endif; */?>
										<?php if( $permissions->check( 'cohortes', 'orientees' ) ): ?>
											<li><?php echo $xhtml->link( 'Demandes orientées', array( 'controller' => 'cohortes', 'action' => 'orientees' ), array( 'title'=>'Demandes orientées' ) );?></li>
										<?php endif; ?>
									</ul>
							</li>
						<?php endif;?>
						<?php if( ( $permissions->check( 'cohortespdos', 'avisdemande' ) || $permissions->check( 'cohortespdos', 'valide' ) || $permissions->check( 'cohortespdos', 'enattente' ) ) && Configure::read( 'Cg.departement' ) == 93 ): ?>
							<!-- AJOUT POUR LA GESTION DES PDOs (Cohorte) -->
							<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
								<?php echo $xhtml->link( 'PDOs', '#' );?>
								<ul>
									<?php if( $permissions->check( 'cohortespdos', 'avisdemande' ) ): ?>
										<li><?php echo $xhtml->link( 'Nouvelles demandes', array( 'controller' => 'cohortespdos', 'action' => 'avisdemande' ), array( 'title' => 'Avis CG demandé' ) );?></li>
									<?php endif; ?>
									<?php if( $permissions->check( 'cohortespdos', 'valide' ) ): ?>
										<li><?php echo $xhtml->link( 'Liste PDOs', array( 'controller' => 'cohortespdos', 'action' => 'valide' ), array( 'title' => 'PDOs validés' ) );?></li>
									<?php endif; ?>
								</ul>
							</li>
						<?php endif;?>
						<?php if( ( $permissions->check( 'relancesnonrespectssanctionseps93', 'cohorte' ) || $permissions->check( 'relancesnonrespectssanctionseps93', 'impressions' ) || $permissions->check( 'nonorientationsproseps', 'index' ) || $permissions->check( 'nonrespectssanctionseps93', 'selectionradies' ) ) && Configure::read( 'Cg.departement' ) == 93 ): ?>
                            <!-- AJOUT POUR LA GESTION DES PDOs (Cohorte) -->
                            <li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
                                <?php echo $xhtml->link( 'EPs', '#' );?>
                                <ul>
								<?php if( $permissions->check( 'relancesnonrespectssanctionseps93', 'cohorte' ) || $permissions->check( 'relancesnonrespectssanctionseps93', 'impressions' ) ): ?>
									<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
										<?php echo $xhtml->link( 'Relances (EP)','#' );?>
										<ul>
											<?php if( $permissions->check( 'relancesnonrespectssanctionseps93', 'cohorte' ) ): ?>
												<li><?php echo $xhtml->link( __d( 'relancenonrespectsanctionep93', 'Relancesnonrespectssanctionseps93::cohorte', true ), array( 'controller' => 'relancesnonrespectssanctionseps93', 'action' => 'cohorte' ), array( 'title' => __d( 'relancenonrespectsanctionep93', 'Relancesnonrespectssanctionseps93::cohorte', true ) ) );?></li>
											<?php endif;?>
											<?php if( $permissions->check( 'relancesnonrespectssanctionseps93', 'impressions' ) ): ?>
												<li><?php echo $xhtml->link( __d( 'relancenonrespectsanctionep93', 'Relancesnonrespectssanctionseps93::impressions', true ), array( 'controller' => 'relancesnonrespectssanctionseps93', 'action' => 'impressions' ), array( 'title' => __d( 'relancenonrespectsanctionep93', 'Relancesnonrespectssanctionseps93::impressions', true ) ) );?></li>
											<?php endif;?>
										</ul>
									</li>
								<?php endif; ?>
                                <?php if( $permissions->check( 'nonorientationsproseps', 'index' ) ): ?>
									<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
										<?php echo $xhtml->link( 'Parcours social sans réorientation', array( 'controller' => 'nonorientationsproseps', 'action' => 'index' ) );?>
									</li>
                                <?php endif; ?>
                                <?php if( $permissions->check( 'nonrespectssanctionseps93', 'selectionradies' ) ): ?>
                                    <li> <?php echo $xhtml->link( 'Radiés de Pôle Emploi',  array( 'controller' => 'nonrespectssanctionseps93', 'action' => 'selectionradies'  ) );?> </li>
                                <?php endif;?>
                                </ul>
                            </li>
                        <?php endif;?>
					</ul>
				</li>
			<?php endif;?>
			<?php if( $permissions->check( 'dossiers', 'index' ) || $permissions->check( 'criteres', 'index' ) || $permissions->check( 'criteresci', 'index' ) ) :?>
				<li id="menu2one" >
					<?php echo $xhtml->link( 'Recherches', '#' );?>
					<ul>
						<?php if( $permissions->check( 'dossiers', 'index' ) ):?>
							<li><?php echo $xhtml->link( 'Par dossier / allocataire', array( 'controller' => 'dossiers', 'action' => 'index' ) );?></li>
						<?php endif;?>
						<?php if( $permissions->check( 'criteres', 'index' ) ):?>
							<li><?php echo $xhtml->link( 'Par Orientation', array( 'controller' => 'criteres', 'action' => 'index' )  );?></li>
						<?php endif;?>
						<?php if( Configure::read( 'Cg.departement' ) == 66 ): ?>
							<?php if( $permissions->check( 'criteresapres', 'index' ) ): ?>
								<li><?php echo $xhtml->link( 'Par APREs', array( 'controller' => 'criteresapres', 'action' => 'all' ) );?></li>
							<?php endif;?>
						<?php endif;?>
						<?php if( $permissions->check( 'criteresci', 'index' ) || $permissions->check( 'criterescuis', 'index' ) ):?>
							<li>
								<?php echo $xhtml->link( 'Par Contrats', '#' );?>
								<ul>
									<li><?php echo $xhtml->link( 'Par CER',  array( 'controller' => 'criteresci', 'action' => 'index'  ) );?></li>
									<li><?php echo $xhtml->link( 'Par CUI',  array( 'controller' => 'criterescuis', 'action' => 'index'  ) );?></li>
								</ul>
							</li>
						<?php endif;?>
						<?php if( $permissions->check( 'criteresentretiens', 'index' ) ): ?>
							<li><?php echo $xhtml->link( 'Par Entretiens', array( 'controller' => 'criteresentretiens', 'action' => 'index' ) );?>
							</li>
						<?php endif;?>

                        <?php if( Configure::read( 'Cg.departement' ) == 66 ): ?>
                            <?php if( $permissions->check( 'criteresfichescandidature', 'index' ) ): ?>
                                <li><?php echo $xhtml->link( 'Par Fiches de candidature', array( 'controller' => 'criteresfichescandidature', 'action' => 'index' ) );?>
                                </li>
                            <?php endif;?>
                        <?php endif;?>

						<?php if( $permissions->check( 'cohortesindus', 'index' ) ): ?>
							<li><?php echo $xhtml->link( 'Par Indus', array( 'controller' => 'cohortesindus', 'action' => 'index' ) );?>
							</li>
						<?php endif;?>
						
						<?php if( $permissions->check( 'dsps', 'index' ) ): ?>
							<li><?php echo $xhtml->link( 'Par DSPs', array( 'controller' => 'dsps', 'action' => 'index' ) );?>
							</li>
						<?php endif;?>
						
						<?php if( $permissions->check( 'criteresrdv', 'index' ) ):?>
							<li><?php echo $xhtml->link( 'Par Rendez-vous',  array( 'controller' => 'criteresrdv', 'action' => 'index'  ) );?></li>
						<?php endif;?>
						<?php if( Configure::read( 'Cg.departement' ) == 66 ): ?>
							<?php if( $permissions->check( 'criteresdossierspcgs66', 'dossier' ) || $permissions->check( 'criterestraitementspcgs66', 'index' ) || $permissions->check( 'criteresdossierspcgs66', 'gestionnaire' ) ):?>
								<li>
									<?php echo $xhtml->link( 'Par Dossiers PCGs', '#' );?>
									<ul>
										<?php if( $permissions->check( 'criteresdossierspcgs66', 'dossier' ) ):?>
											<li><?php echo $xhtml->link( 'Dossiers PCGs',  array( 'controller' => 'criteresdossierspcgs66', 'action' => 'dossier'  ) );?></li>
										<?php endif;?>
										<?php if( $permissions->check( 'criterestraitementspcgs66', 'index' ) ):?>
											<li><?php echo $xhtml->link( 'Traitements PCGs',  array( 'controller' => 'criterestraitementspcgs66', 'action' => 'index'  ) );?></li>
										<?php endif;?>
										<?php if( $permissions->check( 'criteresdossierspcgs66', 'gestionnaire' ) ):?>
											<li><?php echo $xhtml->link( 'Gestionnaires PCGs',  array( 'controller' => 'criteresdossierspcgs66', 'action' => 'gestionnaire'  ) );?></li>
										<?php endif;?>
									</ul>
								</li>
							<?php endif;?>
						<?php else:?>
							<?php if( $permissions->check( 'criterespdos', 'index' ) ):?>
								<li>
									<?php echo $xhtml->link( 'Par PDOs', '#' );?>
									<ul>
										<li><?php echo $xhtml->link( 'Nouvelles PDOs',  array( 'controller' => 'criterespdos', 'action' => 'nouvelles'  ) );?></li>
										<li><?php echo $xhtml->link( 'Liste des PDOs',  array( 'controller' => 'criterespdos', 'action' => 'index'  ) );?></li>
									</ul>
								</li>
							<?php endif;?>
						<?php endif;?>
						<?php if( Configure::read( 'Cg.departement' ) == 58 ): ?>
							<?php if( $permissions->check( 'criteresdossierscovs58', 'index' ) ):?>
								<li> <?php echo $xhtml->link( 'Par Dossiers COV', array( 'controller' => 'criteresdossierscovs58', 'action' => 'index'  ) );?> </li>
							<?php endif;?>
							<?php if( $permissions->check( 'sanctionseps58', 'selectionnoninscrits' ) ):?>
								<li>
									<?php echo $xhtml->link( 'Pôle Emploi', '#' );?>
									<ul>
										<li><?php echo $xhtml->link( 'Radiation de Pôle Emploi', array( 'controller' => 'sanctionseps58', 'action' => 'selectionradies' ) );?></li>
										<li><?php echo $xhtml->link( 'Non inscription à Pôle Emploi', array( 'controller' => 'sanctionseps58', 'action' => 'selectionnoninscrits' ) );?></li>
									</ul>
								</li>
							<?php endif;?>
							<?php if( $permissions->check( 'nonorientationsproseps', 'index' ) ):?>
								<li><?php echo $xhtml->link( 'Demande de maintien dans le social', array( 'controller' => 'nonorientationsproseps', 'action' => 'index' ) );?></li>
							<?php endif;?>
						<?php endif;?>
						<?php if( Configure::read( 'Cg.departement' ) == '66' ): ?>
							<?php if( $permissions->check( 'criteresbilansparcours66', 'index' ) ):?>
								<li><?php echo $xhtml->link( 'Par Bilans de parcours',  array( 'controller' => 'criteresbilansparcours66', 'action' => 'index'  ) );?></li>
							<?php endif;?>
							<!-- TODO : à faire !!! -->
							<?php if( $permissions->check( 'defautsinsertionseps66', 'selectionnoninscrits' ) ):?>
								<li>
									<?php echo $xhtml->link( 'Pôle Emploi', '#' );?>
									<ul>
										<li><?php echo $xhtml->link( 'Non inscrits au Pôle Emploi',  array( 'controller' => 'defautsinsertionseps66', 'action' => 'selectionnoninscrits'  ) );?></li>
										<li><?php echo $xhtml->link( 'Radiés de Pôle Emploi',  array( 'controller' => 'defautsinsertionseps66', 'action' => 'selectionradies'  ) );?></li>
									</ul>
								</li>
							<?php endif;?>
							<?php if( $permissions->check( 'nonorientationsproseps', 'index' ) ):?>
								<li><?php echo $xhtml->link( 'Demande de maintien dans le social',  array( 'controller' => 'nonorientationsproseps', 'action' => 'index'  ) );?></li>
							<?php endif;?>
						<?php endif;?>
					</ul>
				</li>
			<?php endif;?>
			<?php if( ( Configure::read( 'Cg.departement' ) == 93 )  && ( $permissions->check( 'criteresapres', 'index' ) || $permissions->check( 'repsddtefp', 'index' ) || $permissions->check( 'comitesapres', 'index' ) || $permissions->check( 'recoursapres', 'index' ) ) ):?>
				<li id="menu3one" >
					<?php echo $xhtml->link( 'APRE', '#' );?>
					<ul>
						<?php if( $permissions->check( 'criteresapres', 'index' ) ):?>
							<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
								<?php echo $xhtml->link( 'Liste des demandes d\'APRE', '#');?>
									<ul>
										<?php if( $permissions->check( 'criteresapres', 'index' ) ): ?>
											<li><?php echo $xhtml->link( 'Toutes les APREs', array( 'controller' => 'criteresapres', 'action' => 'all' ) );?></li>
										<?php endif;?>
										<?php if( $permissions->check( 'criteresapres', 'index' ) && ( Configure::read( 'Cg.departement' ) != 66 ) ): ?>
											<li><?php echo $xhtml->link( 'Eligibilité des APREs', array( 'controller' => 'criteresapres', 'action' => 'eligible' ) );?></li>
										<?php endif;?>
										<?php if( $permissions->check( 'recoursapres', 'index' ) ): ?>
											<li><?php echo $xhtml->link( 'Demande de recours', array( 'controller' => 'recoursapres', 'action' => 'demande' ) );?></li>
										<?php endif;?>
										<?php if( $permissions->check( 'recoursapres', 'index' ) ): ?>
											<li><?php echo $xhtml->link( 'Visualisation des recours', array( 'controller' => 'recoursapres', 'action' => 'visualisation' ) );?></li>
										<?php endif;?>
									</ul>
								</li>
						<?php endif;?>

				<?php if( Configure::read( 'nom_form_apre_cg' ) == 'cg93' ):?> <!-- Début de l'affichage en fonction du CG-->
						<?php if( $permissions->check( 'comitesapres', 'index' ) || $permissions->check( 'cohortescomitesapres', 'index' ) ):?>
							<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
								<?php echo $xhtml->link( 'Comité d\'examen', '#');?>
								<ul>
									<?php if( $permissions->check( 'comitesapres', 'index' ) ): ?>
										<li><?php echo $xhtml->link( 'Recherche de Comité', array( 'controller' => 'comitesapres', 'action' => 'index' ) );?></li>
									<?php endif;?>
									<?php if( $permissions->check( 'cohortescomitesapres', 'index' ) ): ?>
										<li><?php echo $xhtml->link( 'Gestion des décisions Comité', array( 'controller' => 'cohortescomitesapres', 'action' => 'aviscomite' ) );?></li>
									<?php endif;?>
									<?php if( $permissions->check( 'cohortescomitesapres', 'index' ) ): ?>
										<li><?php echo $xhtml->link( 'Notifications décisions Comité', array( 'controller' => 'cohortescomitesapres', 'action' => 'notificationscomite' ) );?></li>
									<?php endif;?>
									<?php if( $permissions->check( 'comitesapres', 'liste' ) ): ?>
										<li><?php echo $xhtml->link( 'Liste des Comités', array( 'controller' => 'comitesapres', 'action' => 'liste' ) );?></li>
									<?php endif;?>
								</ul>
							</li>
						<?php endif;?>
						<?php if( $permissions->check( 'repsddtefp', 'index' ) || $permissions->check( 'repsddtefp', 'suivicontrole' ) ):?>
							<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
								<?php echo $xhtml->link( 'Reporting bi-mensuel', '#' );?>
								<ul>
									<?php if( $permissions->check( 'repsddtefp', 'index' ) ):?>
										<li><?php echo $xhtml->link( 'Reporting bi-mensuel DDTEFP', array( 'controller' => 'repsddtefp', 'action' => 'index' ) );?></li>
									<?php endif;?>
									<?php if( $permissions->check( 'repsddtefp', 'suivicontrole' ) ):?>
										<li><?php echo $xhtml->link( 'Suivi et contrôle de l\'enveloppe APRE', array( 'controller' => 'repsddtefp', 'action' => 'suivicontrole' ) );?></li>
									<?php endif;?>
								</ul>
							</li>
						<?php endif;?>
						<?php if( $permissions->check( 'integrationfichiersapre', 'index' ) ):?>
							<li><?php echo $xhtml->link( 'Journal d\'intégration des fichiers CSV', array( 'controller' => 'integrationfichiersapre', 'action' => 'index' ) );?></li>
						<?php endif;?>
						<?php if( $permissions->check( 'etatsliquidatifs', 'index' ) ):?>
							<li><?php echo $xhtml->link( 'États liquidatifs APRE', array( 'controller' => 'etatsliquidatifs', 'action' => 'index' ) );?></li>
						<?php endif;?>
						<?php if( $permissions->check( 'budgetsapres', 'index' ) ):?>
							<li><?php echo $xhtml->link( 'Budgets APRE', array( 'controller' => 'budgetsapres', 'action' => 'index' ) );?></li>
						<?php endif;?>

				<?php endif;?> <!-- Fin de l'affichage en fonction du CG-->

					</ul>
				</li>
			<?php endif;?>

			<!-- Menu de gestion de la COV pour le cg 58-->
			<?php if( Configure::read( 'Cg.departement' ) == 58 ): ?>
				<?php if( $permissions->check( 'covs58', 'index' ) ): ?>
					<li id="menu8one">
						<?php echo $xhtml->link( 'COV', array( 'controller' => 'covs58', 'action' => 'index' ) ); ?>
					</li>
				<?php endif; ?>
			<?php endif;?>

			<?php if( Configure::read( 'Cg.departement' ) == 34 ):?>
				<!-- Début du menu des maquettes offre d'insertion-->
				<li id="menuTest0one" >
					<?php echo $html->link( 'Offre d\'Insertion', '#' );?>
					<ul>
						<li>
							<?php echo $html->link( 'Appels à projet', '#' );?>
							<ul>
								<li><?php echo $html->link( 'Saisie appels à projet', array( 'controller' => 'pages/display/webrsa/', 'action' => 'candidature_appel_a_projet' ) );?></li>
								<li><?php echo $html->link( 'Liste des appels à projet', array( 'controller' => 'pages/display/webrsa/', 'action' => 'liste_appels_a_projet' ) );?></li>
							</ul>
						</li>
						<li>
							<?php echo $html->link( 'Candidatures', '#' );?>
							<ul>
								<li><?php echo $html->link( 'Saisie de candidatures', array( 'controller' => 'pages/display/webrsa/', 'action' => 'saisie_candidature_structure1' ) );?></li>
								<li><?php echo $html->link( 'Liste des candidatures', array( 'controller' => 'pages/display/webrsa/', 'action' => 'suivi_candidats' ) );?></li>
							</ul>
						</li>
						<li>
							<?php echo $html->link( 'Analyses des candidatures', '#' );?>
							<ul>
								<li><?php echo $html->link( 'Par lot', array( 'controller' => 'pages/display/webrsa/', 'action' => 'analyse_candidature' ) );?></li>
								<li><?php echo $html->link( 'Par structures', array( 'controller' => 'pages/display/webrsa/', 'action' => 'suivi_structure_candidate' ) );?></li>
								<li><?php echo $html->link( 'Par actions', array( 'controller' => 'pages/display/webrsa/', 'action' => 'selection_actions' ) );?></li>
							</ul>
						</li>
						<li>
							<?php echo $html->link( 'Administration structure', '#' );?>
							<ul>
								<li><?php echo $html->link( 'Recherche de structures', array( 'controller' => 'pages/display/webrsa/', 'action' => 'recherche_admin_structure' ) );?></li>
								<li><?php echo $html->link( 'Suivi des étapes / pièces', array( 'controller' => 'pages/display/webrsa/', 'action' => 'suivi_etapes_pieces' ) );?></li>
							</ul>
						</li>
						<li>
							<?php echo $html->link( 'Conventions', '#' );?>
							<ul>
								<li><?php echo $html->link( 'Liste des conventions', array( 'controller' => 'pages/display/webrsa/', 'action' => 'liste_convention' ) );?></li>
								<li><?php echo $html->link( 'Création de convention', array( 'controller' => 'pages/display/webrsa/', 'action' => 'create_convention' ) );?></li>
								<li><?php echo $html->link( 'Gestion des conventions', array( 'controller' => 'pages/display/webrsa/', 'action' => 'gestion_convention' ) );?></li>
							</ul>
						</li>
						<li>
							<?php echo $html->link( 'Paiements', '#' );?>
							<ul>
								<li><?php echo $html->link( 'Saisie déclenchements paiement', array( 'controller' => 'pages/display/webrsa/', 'action' => 'gestion_convention' ) );?></li>
								<li><?php echo $html->link( 'Suivi des paiements', array( 'controller' => 'pages/display/webrsa/', 'action' => 'liste_suivi_paiement' ) );?></li>
								<li><?php echo $html->link( 'Demande de remboursement', array( 'controller' => 'pages/display/webrsa/', 'action' => 'demande_remboursement' ) );?></li>
							</ul>
						</li>
						<li>
							<?php echo $html->link( 'Offres', '#' );?>
							<ul>
								<li><?php echo $html->link( 'Recherche d\'offres', array( 'controller' => 'pages/display/webrsa/', 'action' => 'recherche_offre' ) );?></li>
								<li><?php echo $html->link( 'Création d\'offres', array( 'controller' => 'pages/display/webrsa/', 'action' => 'create_offre' ) );?></li>
								<li><?php echo $html->link( 'Gestion des offres', array( 'controller' => 'pages/display/webrsa/', 'action' => 'gestion_offre' ) );?></li>
							</ul>
						</li>
							<li><?php echo $html->link( 'Suivi des stagiaires', array( 'controller' => 'pages/display/webrsa/', 'action' => 'suivi_stagiaires' ) );?></li>
					</ul>
				</li>
			<?php endif;?>
			<!-- Fin du menu des maquettes offre d'insertion-->

			<!-- Début du Nouveau menu pour les Equipes pluridisciplinaires -->

			<?php if( $permissions->check( 'eps', 'liste' ) ) :?>
			<li id="menu4one">
				<?php echo $xhtml->link( 'Eq. Pluri.', '#' );?>
				<ul>
					<li>
						<?php if( Configure::read( 'Cg.departement' ) == 66 ):?>
							<a href="#">1. Gestion des EPs</a>
						<?php else:?>
							<a href="#">1. Mise en place du dispositif</a>
						<?php endif;?>
						<ul>
							<?php if( Configure::read( 'Cg.departement' ) == 66 && $permissions->check( 'defautsinsertionseps66', 'courriersinformations' ) ):?>
								<li><?php echo $xhtml->link( 'Courriers d\'information avant EPL Audition',  array( 'controller' => 'defautsinsertionseps66', 'action' => 'courriersinformations'  ) );?></li>
							<?php endif;?>
							<?php if( $permissions->check( 'membreseps', 'index' ) ):?>
								<li><?php echo $xhtml->link( 'Création des membres', array( 'controller' => 'membreseps', 'action' => 'index' ) );?></li>
							<?php endif;?>
							<?php if( $permissions->check( 'eps', 'index' ) ):?>
								<li><?php echo $xhtml->link( 'Création des EPs', array( 'controller' => 'eps', 'action' => 'index' ) );?></li>
							<?php endif;?>
							<?php if( $permissions->check( 'commissionseps', 'add' ) ):?>
								<li><?php echo $xhtml->link( 'Création des Commissions', array( 'controller' => 'commissionseps', 'action' => 'add' ) );?></li>
							<?php endif;?>
						</ul>
					</li>
					<?php if( $permissions->check( 'commissionseps', 'recherche' ) ):?>
						<li>
							<?php if( Configure::read( 'Cg.departement' ) == 66 ): ?>
								<?php echo $xhtml->link( '2. Recherche de commission', array( 'controller' => 'commissionseps', 'action' => 'recherche' ) );?>
							<?php else:?>
								<?php echo $xhtml->link( '2. Constitution de la commission', array( 'controller' => 'commissionseps', 'action' => 'recherche' ) );?>
							<?php endif;?>
						</li>
					<?php endif;?>
					<?php if( Configure::read( 'Cg.departement' ) == 58 ): ?>
						<li><?php echo $xhtml->link( '3. Arbitrage EP', array( 'controller' => 'commissionseps', 'action' => 'arbitrageep' ) );?></li>
					<?php else: ?>
						<li>
							<?php if( Configure::read( 'Cg.departement' ) == 66 ):?>
								<a href="#">3. Avis/Décisions</a>
							<?php else:?>
								<a href="#">3. Arbitrage</a>
							<?php endif;?>

							<ul>
                                <?php if( Configure::read( 'Cg.departement' ) == 66 ): ?>
                                    <li><?php echo $xhtml->link( 'Avis EP', array( 'controller' => 'commissionseps', 'action' => 'arbitrageep' ) );?></li>
                                    <li><?php echo $xhtml->link( 'Décisions CG', array( 'controller' => 'commissionseps', 'action' => 'arbitragecg' ) );?></li>
                                <?php else: ?>
                                    <li><?php echo $xhtml->link( 'EP', array( 'controller' => 'commissionseps', 'action' => 'arbitrageep' ) );?></li>
                                    <li><?php echo $xhtml->link( 'CG', array( 'controller' => 'commissionseps', 'action' => 'arbitragecg' ) );?></li>
								<?php endif; ?>
							</ul>
						</li>
					<?php endif; ?>
					<li><?php echo $xhtml->link( '4. Consultation et impression des décisions', array( 'controller' => 'commissionseps', 'action' => 'decisions' ) );?></li>
				</ul>
			</li>
			<?php endif;?>
			<!-- Fin du Nouveau menu pour les Equipes pluridisciplinaires -->

			<?php if( $permissions->check( 'indicateursmensuels', 'index' ) || $permissions->check( 'statistiquesministerielles', '#' ) ) :?>
				<li id="menu5one" >
					<?php echo $xhtml->link( 'Tableaux de bord', '#' );?>
					<ul>
						<?php if( $permissions->check( 'indicateursmensuels', 'index' ) ):?>
							<li><?php echo $xhtml->link( 'Indicateurs mensuels', array( 'controller' => 'indicateursmensuels', 'action' => 'index' ) );?></li>
						<?php endif;?>

						<?php if( $permissions->check( 'statistiquesministerielles', 'indicateursOrientations' ) || $permissions->check( 'statistiquesministerielles', 'indicateursOrganismes' ) || $permissions->check( 'statistiquesministerielles', 'indicateursNatureContrats' ) || $permissions->check( 'statistiquesministerielles', 'indicateursCaracteristiquesContrats' ) ):?>
							<li>
								<?php echo $xhtml->link( 'Statistiques ministérielles', '#' );?>
								<ul>
									<li>
										<?php echo $xhtml->link( 'Indicateurs d\'orientations',  array( 'controller' => 'statistiquesministerielles', 'action' => 'indicateursOrientations'  ) );?>
									</li>

									<li>
										<?php echo $xhtml->link( 'Indicateurs d\'organismes',  array( 'controller' => 'statistiquesministerielles', 'action' => 'indicateursOrganismes'  ) );?>
									</li>
									<li>
										<?php echo $xhtml->link( 'Indicateurs de nature de contrats',  array( 'controller' => 'statistiquesministerielles', 'action' => 'indicateursDelais'  ) );?>
									</li>
									<li>
										<?php echo $xhtml->link( 'Indicateurs de caractéristiques de contrats',  array( 'controller' => 'statistiquesministerielles', 'action' => 'indicateursCaracteristiquesContrats'  ) );?>
									</li>
									<li>
										<?php echo $xhtml->link( 'Indicateurs de réorientations',  array( 'controller' => 'statistiquesministerielles', 'action' => 'indicateursReorientations'  ) );?>
									</li>
									<li>
										<?php echo $xhtml->link( 'Indicateurs de motifs de réorientations',  array( 'controller' => 'statistiquesministerielles', 'action' => 'indicateursMotifsReorientation'  ) );?>
									</li>
								</ul>
							</li>
							
							<?php if( $permissions->check( 'indicateurssuivis', 'index' ) ):?>
								<li><?php echo $xhtml->link( 'Indicateurs de suivi', array( 'controller' => 'indicateurssuivis', 'action' => 'index' ) );?></li>
							<?php endif;?>							

						<?php endif;?>
					</ul>
				</li>
			<?php endif;?>
			<?php if( $permissions->check( 'parametrages', 'index' ) || $permissions->check( 'infosfinancieres', 'indexdossier' ) || $permissions->check( 'totalisationsacomptes', 'index' ) ): ?>
					<li id="menu6one">
						<?php echo $xhtml->link( 'Administration', '#' );?>
						<ul>
							<?php if( $permissions->check( 'parametrages', 'index' ) ):?>
								<li><?php echo $xhtml->link( 'Paramétrages',  array( 'controller' => 'parametrages', 'action' => 'index'  ) );?></li>
							<?php endif;?>
							<?php if( $permissions->check( 'infosfinancieres', 'indexdossier' ) || $permissions->check( 'totalisationsacomptes', 'index' ) ):?>
								<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
								<?php  echo $xhtml->link( 'Paiement allocation', '#' );?>
									<ul>
										<?php if( $permissions->check( 'infosfinancieres', 'indexdossier' ) ):?>
											<li><?php echo $xhtml->link( 'Listes nominatives', array( 'controller' => 'infosfinancieres', 'action' => 'indexdossier' ), array( 'title' => 'Listes nominatives' ) );?></li>
										<?php endif;?>
										<?php if( $permissions->check( 'totalisationsacomptes', 'index' ) ):?>
											<li><?php echo $xhtml->link( 'Mandats mensuels', array( 'controller' => 'totalisationsacomptes', 'action' => 'index' ), array( 'title' => 'Mandats mensuels' ) );?></li>
										<?php endif;?>
									</ul>
								</li>
							<?php endif;?>
							<?php if( $permissions->check( 'gestionsanomaliesbdds', 'index' ) ):?>
								<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
								<?php  echo $xhtml->link( 'Gestion des anomalies', '#' );?>
									<ul>
										<?php if( $permissions->check( 'gestionsanomaliesbdds', 'index' ) ):?>
											<li><?php echo $xhtml->link( 'Doublons simples',  array( 'controller' => 'gestionsanomaliesbdds', 'action' => 'index'  ), array( 'title' => 'Gestion des anomalies de doublons simples au sein d\'un foyer donné' ) );?></li>
										<?php endif;?>
									</ul>
								</li>
							<?php endif;?>
						</ul>
					</li>
			<?php endif;?>

			<?php if( $permissions->check( 'visionneuses', 'index' ) ) :?>
				<li id="menu7one" >
					<?php echo $xhtml->link( 'Visionneuse', '#' );?>
					<ul>
						<?php if( $permissions->check( 'visionneuses', 'index' ) ):?>
							<li><?php echo $xhtml->link( 'logs', array( 'controller' => 'visionneuses', 'action' => 'index' ) );?></li>
						<?php endif;?>
					</ul>
				</li>
			<?php endif;?>

			<li id="menu9one"><?php echo $xhtml->link( 'Déconnexion '.$session->read( 'Auth.User.username' ), array( 'controller' => 'users', 'action' => 'logout' ) );?></li>
			<?php else: ?>
				<li><?php echo $xhtml->link( 'Connexion', array( 'controller' => 'users', 'action' => 'login' ) );?></li>
			<?php endif; ?>
		</ul>
	</div>
</div>