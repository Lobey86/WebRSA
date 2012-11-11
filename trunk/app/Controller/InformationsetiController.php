<?php
	/**
	 * Code source de la classe InformationsetiController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe InformationsetiController ...
	 *
	 * @package app.Controller
	 */
	class InformationsetiController extends AppController
	{

		public $name = 'Informationseti';
		public $uses = array( 'Informationeti',  'Option' , 'Personne' );

		public $commeDroit = array(
			'view' => 'Informationseti:index'
		);

		public function beforeFilter() {
			parent::beforeFilter();
			$this->set( 'topcreaentre', $this->Option->topcreaentre() );
			$this->set( 'topaccre', $this->Option->topaccre() );
			$this->set( 'acteti', $this->Option->acteti() );
			$this->set( 'topempl1ax', $this->Option->topempl1ax() );
			$this->set( 'topstag1ax', $this->Option->topstag1ax() );
			$this->set( 'topsansempl', $this->Option->topsansempl() );
			$this->set( 'regfiseti', $this->Option->regfiseti() );
			$this->set( 'topbeneti', $this->Option->topbeneti() );
			$this->set( 'regfisetia1', $this->Option->regfisetia1() );
			$this->set( 'topevoreveti', $this->Option->topevoreveti() );
			$this->set( 'topressevaeti', $this->Option->topressevaeti() );
		}

		public function index( $personne_id = null ){
			// TODO : vérif param
			// Vérification du format de la variable
			$this->assert( valid_int( $personne_id ), 'error404' );

			$informationeti = $this->Informationeti->find(
				'first',
				array(
					'conditions' => array(
						'Informationeti.personne_id' => $personne_id
					),
					'recursive' => -1
				)
			) ;

			// Assignations à la vue
			$this->set( 'personne_id', $personne_id );
			$this->set( 'informationeti', $informationeti );
		}

		public function view( $informationeti_id = null ) {
			// Vérification du format de la variable
			$this->assert( valid_int( $informationeti_id ), 'error404' );

			$informationeti = $this->Informationeti->find(
				'first',
				array(
					'conditions' => array(
						'Informationeti.id' => $informationeti_id
					),
				'recursive' => -1
				)
			);
			$this->assert( !empty( $informationeti ), 'error404' );

			// Assignations à la vue
			$this->set( 'personne_id', $informationeti['Informationeti']['personne_id'] );
			$this->set( 'informationeti', $informationeti );
		}
	}

?>