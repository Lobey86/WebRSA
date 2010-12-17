<?php
	class MembresepsController extends AppController
	{
		public $helpers = array( 'Default', 'Default2' );

		public function beforeFilter() {
		}

		
		protected function _setOptions() {
			$options = $this->Membreep->enums();
			if( $this->action != 'index' ) {
				$options['Membreep']['fonctionmembreep_id'] = $this->Membreep->Fonctionmembreep->find( 'list' );
				$options['Membreep']['ep_id'] = $this->Membreep->Ep->find( 'list' );
			}
			$enums = $this->Membreep->MembreepSeanceep->enums();
			$options['MembreepSeanceep'] = $enums['MembreepSeanceep'];
			$this->set( compact( 'options' ) );
		}


		public function index() {
			$this->paginate = array(
				'fields' => array(
					'Membreep.id',
					'Fonctionmembreep.name',
					'Membreep.qual',
					'Membreep.nom',
					'Membreep.prenom',
					'Membreep.suppleant_id',
					'Suppleant.qual',
					'Suppleant.nom',
					'Suppleant.prenom'
				),
				'contain' => array(
					'Fonctionmembreep',
					'Suppleant'
				),
				'limit' => 10
			);
			$membreseps = $this->paginate( $this->Membreep );
			foreach( $membreseps as &$membreep) {
				if (isset($membreep['Suppleant']['id']) && !empty($membreep['Suppleant']['id']))
					$membreep['Membreep']['nomcompletsuppleant'] = implode ( ' ', array( $membreep['Suppleant']['qual'], $membreep['Suppleant']['nom'], $membreep['Suppleant']['prenom']) );
				$membreep['Membreep']['nomcomplet'] = implode ( ' ', array( $membreep['Membreep']['qual'], $membreep['Membreep']['nom'], $membreep['Membreep']['prenom']) );
					
			}

			$this->_setOptions();
			$this->set( compact( 'membreseps' ) );
		}

		/**
		*
		*/

		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		public function edit() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		protected function _add_edit( $id = null ) {
			if( !empty( $this->data ) ) {
				$this->Membreep->create( $this->data );
				$success = $this->Membreep->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->redirect( array( 'action' => 'index' ) );
				}
			}
			elseif( $this->action == 'edit' ) {
				$this->data = $this->Membreep->find(
					'first',
					array(
						'contain' => false,
						'conditions' => array( 'Membreep.id' => $id )
					)
				);
				$this->assert( !empty( $this->data ), 'error404' );
			}
			
			$listeSuppleants = array();
			if( $this->action == 'add' ) {
				$membres = $this->Membreep->find(
					'all',
					array(
						'contain'=>false
					)
				);
			}
			elseif( $this->action == 'edit' ) {
				$membres = $this->Membreep->find(
					'all',
					array(
						'conditions'=>array(
							'Membreep.id <>'=>$id
						),
						'contain'=>false
					)
				);
			}
			foreach($membres as $membre) {
				$listeSuppleants[$membre['Membreep']['id']] = implode(' ', array($membre['Membreep']['qual'], $membre['Membreep']['nom'], $membre['Membreep']['prenom']));
			}
			$this->set(compact('listeSuppleants'));
			
			$this->_setOptions();
			$this->render( null, null, 'add_edit' );
		}

		/**
		*
		*/

		public function delete( $id ) {
			$success = $this->Membreep->delete( $id );
			$this->_setFlashResult( 'Delete', $success );
			$this->redirect( array( 'action' => 'index' ) );
		}

		public function ajaxfindsuppleant( $ep_id = null, $defaultvalue = '', $membreEp_id = 0 ) {
            Configure::write( 'debug', 0 );
            $suppleants = $this->Membreep->find(
            	'all',
            	array(
            		'conditions'=>array(
            			'Membreep.ep_id'=>$ep_id,
            			'Membreep.id <>'=>$membreEp_id
            		),
            		'contain'=>false
            	)
            );
			$listeSuppleant = array();
			foreach($suppleants as $suppleant) {
				$listeSuppleant[$suppleant['Membreep']['id']] = $suppleant['Membreep']['qual'].' '.$suppleant['Membreep']['nom'].' '.$suppleant['Membreep']['prenom'];
			}
            $this->set( compact( 'listeSuppleant' ) );
            $this->set( compact( 'defaultvalue' ) );
            $this->render( $this->action, 'ajax', '/membreseps/ajaxfindsuppleant' );
		}

		/**
		 * Dresse la liste de tous les membres de l'EP pour enregistrer ceux, parmis-eux, qui participeront à la séance.
		 * @param integer $ep_id Index de l'EP dont on veut récupérer tous les membres.
		 */
		public function editliste( $ep_id, $seance_id ) {
			if( !empty( $this->data ) ) {
				$success = true;
				$this->Membreep->MembreepSeanceep->begin();
				foreach($this->data['MembreepSeanceep']['Membreep_id'] as $membreep_id => $reponse) {
					$existeEnBase = $this->Membreep->MembreepSeanceep->find(
						'first',
						array(
							'conditions'=>array(
								'MembreepSeanceep.membreep_id'=>$membreep_id,
								'MembreepSeanceep.seanceep_id'=>$seance_id
							),
							'contain' => false
						)
					);

					if (!empty($existeEnBase)) {
						$existeEnBase['MembreepSeanceep']['reponse'] = $reponse['reponse'];
						$this->Membreep->MembreepSeanceep->create( $existeEnBase );
						$success = $this->Membreep->MembreepSeanceep->save() && $success;
					}
					else {
						$nouvelleEntree['MembreepSeanceep']['seanceep_id'] = $seance_id;
						$nouvelleEntree['MembreepSeanceep']['membreep_id'] = $membreep_id;
						$nouvelleEntree['MembreepSeanceep']['reponse'] = $reponse['reponse'];
						$this->Membreep->MembreepSeanceep->create($nouvelleEntree);
						$success = $this->Membreep->MembreepSeanceep->save() && $success;
					}
				}
				
				$this->_setFlashResult( 'Save', $success );
				if ($success) {
					$this->Membreep->MembreepSeanceep->commit();
					$this->redirect(array('controller'=>'seanceseps', 'action'=>'view', $seance_id));
				}
				else {
					$this->Membreep->MembreepSeanceep->rollback();
				}
				
				/*$enBase  =Set::extract( $membres, '/Seanceep/MembreepSeanceep/membreep_id' ); 

				$ajouts = array();
				$suppressions = array();
				foreach($this->data['Membreep'] as $i => $membre) {
					if( $membre['checked'] && !in_array( $membre['id'], $enBase ) ) {
						$ajouts[] = array(
							'membreep_id' => $membre['id'],
							'seanceep_id' => $seance_id,
						);
					}
					else if( !$membre['checked'] && in_array( $membre['id'], $enBase ) ) {
						$suppressions[] = $membre['id'];
					}
				}

				if( !empty( $ajouts ) || !empty( $suppressions ) ) {
					$success = true;
					$this->Membreep->MembreepSeanceep->begin();
	
					if( !empty( $ajouts ) ) {
						$success = $this->Membreep->MembreepSeanceep->saveAll( $ajouts, array( 'atomic' => false ) ) && $success;
					}
					if( !empty( $suppressions ) ) {
							$success = $this->Membreep->MembreepSeanceep->deleteAll(
								array(
									'MembreepSeanceep.membreep_id' => $suppressions,
									'MembreepSeanceep.seanceep_id' => $seance_id,
								)
							) && $success;
					}
	
					if( $success ) {
						$this->Membreep->MembreepSeanceep->commit();
						$this->Session->setFlash('Enregistrement effectué', 'flash/success');
						$this->redirect( array( 'controller' => 'seanceseps', 'action' => 'view', $seance_id ));
					}
					else {
						$this->Membreep->MembreepSeanceep->rollback();
					}
				}*/
			}

			$membres = $this->Membreep->find(
				'all',
				array(
					'fields' => array(
						'Membreep.id',
						'Membreep.qual',
						'Membreep.nom',
						'Membreep.prenom',
						'Membreep.tel',
						'Membreep.mail',
						'Membreep.fonctionmembreep_id',
						'MembreepSeanceep.reponse'
					),
					'joins' => array(
						array(
							'table' => 'membreseps_seanceseps',
							'alias' => 'MembreepSeanceep',
							'type' => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array(
								'Membreep.id = MembreepSeanceep.membreep_id',
								'MembreepSeanceep.seanceep_id' => $seance_id
							)
						),
						array(
							'table' => 'seanceseps',
							'alias' => 'Seanceep',
							'type' => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array(
								'Seanceep.id = MembreepSeanceep.seanceep_id'
							)
						),
						array(
							'table' => 'eps_membreseps',
							'alias' => 'EpMembreep',
							'type' => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Membreep.id = EpMembreep.membreep_id',
								'EpMembreep.ep_id' => $ep_id
							)
						),
						array(
							'table' => 'eps',
							'alias' => 'Ep',
							'type' => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Ep.id = EpMembreep.ep_id'
							)
						)
					),
					'contain'=>false
				)
			);
			$this->set('membres', $membres);

			$fonctionsmembres = $this->Membreep->Fonctionmembreep->find(
				'all',
				array(
					'fields' => array(
						'Fonctionmembreep.id',
						'Fonctionmembreep.name'
					),
					'joins' => array(
						array(
							'table' => 'membreseps',
							'alias' => 'Membreep',
							'type' => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Fonctionmembreep.id = Membreep.fonctionmembreep_id'
							)
						),
						array(
							'table' => 'eps_membreseps',
							'alias' => 'EpMembreep',
							'type' => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Membreep.id = EpMembreep.membreep_id',
								'EpMembreep.ep_id' => $ep_id
							)
						),
						array(
							'table' => 'eps',
							'alias' => 'Ep',
							'type' => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Ep.id = EpMembreep.ep_id'
							)
						)
					),
					'contain'=>false,
					'group' => array(
						'Fonctionmembreep.id',
						'Fonctionmembreep.name'
					)
				)
			);
			$this->set('fonctionsmembres', $fonctionsmembres);
			
			$this->set('seance_id', $seance_id);
			$this->set('ep_id', $ep_id);
			$this->_setOptions();
		}
		
		
		public function editpresence( $ep_id, $seance_id ) {
			$presences = $this->Membreep->find('all', array(
				'conditions' => array(
					'Membreep.ep_id' => $ep_id
				),
				'contain' => array(
					'Seanceep' => array(
						'conditions' => array(
							'MembreepSeanceep.seanceep_id' => $seance_id
						)
					),
					'Fonctionmembreep'
				)
			));
						$this->set('seance_id', $seance_id);
			$this->set('presences', Set::extract( $presences, '/Seanceep/MembreepSeanceep/membreep_id' ));
		}
		
	}
	
?>
