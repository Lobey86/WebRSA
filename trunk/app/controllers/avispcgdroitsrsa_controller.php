<?php
	class AvispcgdroitsrsaController extends AppController
	{
		public $name = 'Avispcgdroitrsa';
		public $uses = array( 'Avispcgdroitrsa', 'Option' , 'Dossier', 'Condadmin',  'Reducrsa');
		
		public $commeDroit = array( 'view' => 'Actionscandidats:index' );

		/**
		*
		*/

		public function beforeFilter() {
			$return = parent::beforeFilter();
			$this->set( 'avisdestpairsa', $this->Option->avisdestpairsa() );
			$this->set( 'typeperstie', $this->Option->typeperstie() );
			$this->set( 'aviscondadmrsa', $this->Option->aviscondadmrsa() );
			return $return;
		}

		/**
		*
		*/

		public function index( $dossier_id = null ){
			// TODO : vérif param
			// Vérification du format de la variable
			$this->assert( valid_int( $dossier_id ), 'error404' );


			$avispcgdroitrsa = $this->Avispcgdroitrsa->find(
				'first',
				array(
					'conditions' => array(
						'Avispcgdroitrsa.dossier_id' => $dossier_id
					),
					'recursive' => -1
				)
			) ;

			// Assignations à la vue
			$this->set( 'dossier_id', $dossier_id );
			$this->set( 'avispcgdroitrsa', $avispcgdroitrsa );
		}

		/**
		*
		*/

		public function view( $avispcgdroitrsa_id = null ) {
			// Vérification du format de la variable
			$this->assert( valid_int( $avispcgdroitrsa_id ), 'error404' );

			$avispcgdroitrsa = $this->Avispcgdroitrsa->find(
				'first',
				array(
					'conditions' => array(
						'Avispcgdroitrsa.id' => $avispcgdroitrsa_id
					),
				'recursive' => -1
				)

			);

			$this->assert( !empty( $avispcgdroitrsa ), 'error404' );

			// Assignations à la vue
			$this->set( 'dossier_id', $avispcgdroitrsa['Avispcgdroitrsa']['dossier_id'] );
			$this->set( 'avispcgdroitrsa', $avispcgdroitrsa );
		}
	}
?>