<?php
	/**
	 * Code source de la classe Proposorientationscovs58Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Proposorientationscovs58Controller ... (CG 58).
	 *
	 * @package app.Controller
	 */
	class Proposorientationscovs58Controller extends AppController
	{
		public $name = "Proposorientationscovs58";

		public $helpers = array( 'Default', 'Default2' );

		public $commeDroit = array(
			'add' => 'Proposorientationscovs58:edit'
		);

		public $components = array( 'Jetons2' );

		protected function _setOptions() {
			$this->set( 'referents', $this->Propoorientationcov58->Referent->listOptions() );
			$this->set( 'typesorients', $this->Propoorientationcov58->Typeorient->listOptions() );
			$this->set( 'structuresreferentes', $this->Propoorientationcov58->Structurereferente->list1Options( array( 'orientation' => 'O' ) ) );

			//Ajout des structures et référents orientants
			$this->set( 'refsorientants', $this->Propoorientationcov58->Referent->listOptions() );
			$this->set( 'structsorientantes', $this->Propoorientationcov58->Structurereferente->listOptions( array( 'orientation' => 'O' ) ) );
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

		public function _add_edit( $personne_id = null ) {
			$this->assert( valid_int( $personne_id ), 'invalidParameter' );

			if ( $this->action == 'edit' ) {
				$propoorientationcov58 = $this->Propoorientationcov58->find(
					'first',
					array(
						'fields' => array(
							'Propoorientationcov58.id',
							'Propoorientationcov58.dossiercov58_id',
							'Propoorientationcov58.typeorient_id',
							'Propoorientationcov58.structurereferente_id',
							'Propoorientationcov58.referent_id',
							'Propoorientationcov58.structureorientante_id',
							'Propoorientationcov58.referentorientant_id',
							'Propoorientationcov58.datedemande'
						),
						'joins' => array(
							array(
								'table' => 'dossierscovs58',
								'alias' => 'Dossiercov58',
								'type' => 'INNER',
								'conditions' => array(
									'Dossiercov58.id = Propoorientationcov58.dossiercov58_id',
									'Dossiercov58.personne_id' => $personne_id
								)
							),
							'contain' => false,
							'order' => array( 'Propoorientationcov58.rgorient DESC' )
						)
					)
				);
			}

			$dossier_id = $this->Propoorientationcov58->Dossiercov58->Personne->dossierId( $personne_id );
			$this->Jetons2->get( $dossier_id );

			// Retour à l'index en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'controller' => 'orientsstructs', 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->request->data ) ) {
				$saved = true;
				$this->Propoorientationcov58->begin();

				$this->request->data['Propoorientationcov58']['rgorient'] = $this->Propoorientationcov58->Dossiercov58->Personne->Orientstruct->rgorientMax( $personne_id );

				if ( $this->Propoorientationcov58->Structurereferente->Orientstruct->isRegression( $personne_id, $this->request->data['Propoorientationcov58']['typeorient_id'] ) ) {
					$dossierep = array(
						'Dossierep' => array(
							'personne_id' => $personne_id,
							'themeep' => 'regressionsorientationseps58',
						)
					);
					$saved = $this->Propoorientationcov58->Structurereferente->Regressionorientationep58->Dossierep->save( $dossierep ) && $saved;

					$regressionorientationep58['Regressionorientationep58'] = $this->request->data['Propoorientationcov58'];
					$regressionorientationep58['Regressionorientationep58']['personne_id'] = $personne_id;
					$regressionorientationep58['Regressionorientationep58']['dossierep_id'] = $this->Propoorientationcov58->Structurereferente->Regressionorientationep58->Dossierep->id;

					if ( isset( $regressionorientationep58['Regressionorientationep58']['referent_id'] ) && !empty( $regressionorientationep58['Regressionorientationep58']['referent_id'] ) ) {
						list( $structurereferente_id, $referent_id) = explode( '_', $regressionorientationep58['Regressionorientationep58']['referent_id'] );
						$regressionorientationep58['Regressionorientationep58']['structurereferente_id'] = $structurereferente_id;
						$regressionorientationep58['Regressionorientationep58']['referent_id'] = $referent_id;
					}

					$saved = $this->Propoorientationcov58->Structurereferente->Regressionorientationep58->save( $regressionorientationep58 ) && $saved;
				}
				else {
					if ( $this->action == 'add' ) {
						$themecov58 = $this->Propoorientationcov58->Dossiercov58->Themecov58->find(
							'first',
							array(
								'conditions' => array(
									'Themecov58.name' => Inflector::tableize($this->Propoorientationcov58->alias)
								),
								'contain' => false
							)
						);
						$dossiercov58['Dossiercov58']['themecov58_id'] = $themecov58['Themecov58']['id'];
						$dossiercov58['Dossiercov58']['personne_id'] = $personne_id;
						$dossiercov58['Dossiercov58']['themecov58'] = 'proposorientationscovs58';

						$saved = $this->Propoorientationcov58->Dossiercov58->save($dossiercov58) && $saved;

						$this->Propoorientationcov58->create();

						$this->request->data['Propoorientationcov58']['dossiercov58_id'] = $this->Propoorientationcov58->Dossiercov58->id;
					}

					$saved = $this->Propoorientationcov58->save( $this->request->data['Propoorientationcov58'] ) && $saved;
				}

				if( $saved ) {
					$this->Propoorientationcov58->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'orientsstructs', 'action' => 'index', $personne_id ) );
				}
				else {
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					$this->Propoorientationcov58->rollback();
				}
			}
			elseif ( $this->action == 'edit' ) {
				$this->request->data = $propoorientationcov58;
			}

			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
			$this->set( 'urlmenu', '/orientsstructs/index/'.$personne_id );
			$this->render( '_add_edit' );
		}

		/**
		 * Suppression de la proposition d'orientation en COV lorsque le dossier COV n'est pas encore attaché
		 * à une COV.
		 *
		 * @param integer $propoorientationcov58_id L'id de la proposition d'orientation
		 */
		public function delete( $propoorientationcov58_id ) {
			$propoorientationcov58 = $this->Propoorientationcov58->find(
				'first',
				array(
					'fields' => array(
						'Propoorientationcov58.id',
						'Propoorientationcov58.dossiercov58_id'
					),
					'contain' => false,
					'conditions' => array(
						'Propoorientationcov58.id' => $propoorientationcov58_id
					)
				)
			);

			$this->Propoorientationcov58->begin();

			$success = $this->Propoorientationcov58->delete( $propoorientationcov58['Propoorientationcov58']['id'] );
			$success = $this->Propoorientationcov58->Dossiercov58->delete( $propoorientationcov58['Propoorientationcov58']['dossiercov58_id'] ) && $success;

			$this->_setFlashResult( 'Delete', $success );

			if( $success ) {
				$this->Propoorientationcov58->commit();
			}
			else {
				$this->Propoorientationcov58->rollback();
			}
			$this->redirect( $this->referer() );
		}
	}
?>