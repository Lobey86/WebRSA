<?php
	/**
	 * Code source de la classe SearchFormHelper.
	 *
	 * PHP 5.3
	 *
	 * @package Search
	 * @subpackage View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe SearchFormHelper fournit des méthodes génériques pour des éléments
	 * de formulaires. Utilise la librairire javascript prototype.js.
	 *
	 * @package Search
	 * @subpackage View.Helper
	 */
	class SearchFormHelper extends AppHelper
	{
		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Form',
			'Html',
			'Prototype.PrototypeObserver' => array(
				'useBuffer' => false
			)
		);

		/**
		 * Fournit le code javascript permettant de désactiver les boutons de
		 * soumission d'un formumlaire lors de son envoi afin de ne pas renvoyer
		 * celui-ci plusieurs fois avant que le reqête n'ait abouti.
		 *
		 * @deprecated See PrototypeObserverHelper::disableFormOnSubmit()
		 *
		 * @param string $form L'id du formulaire au sens Prototype
		 * @param string $message Le message (optionnel) qui apparaîtra en haut du formulaire
		 * @return string
		 */
		public function observeDisableFormOnSubmit( $form, $message = null ) {
			if( empty( $message ) ) {
				$out = "document.observe( 'dom:loaded', function() {
					observeDisableFormOnSubmit( '{$form}' );
				} );";
			}
			else {
				$message = str_replace( "'", "\\'", $message );

				$out = "document.observe( 'dom:loaded', function() {
					observeDisableFormOnSubmit( '{$form}', '{$message}' );
				} );";
			}

			return "<script type='text/javascript'>{$out}</script>";
		}

		/**
		 * Méthode générique permettant de retourner un ensemble de cases à cocher au sein d'un
		 * fieldset, activées ou désactivées par une autre case à cocher située au-dessus du fieldset.
		 *
		 * Les traductions - "{$path}_choice" pour la case à cocher d'activation/désactivation et
		 * $path pour le fieldset sont faites dans le fichier de traduction correspondant au nom
		 * du contrôleur.
		 *
		 * Remplacements possibles:
		 * //			echo $this->Search->etatdosrsa($etatdosrsa);
		 * echo $this->SearchForm->dependantCheckboxes( 'Situationdossierrsa.etatdosrsa', $etatdosrsa );
		 * @see SearchHelper
		 *
		 * @param string $path
		 * @param array $params
		 * @return string
		 */
		public function dependantCheckboxes( $path, array $params = array() ) {
			$default = array(
				'domain' => 'search_plugin',
				'options' => array(),
				'hide' => false,
			);
			$params = $params + $default;

			$options = $params['options'];

			$fieldsetId = $this->domId( "{$path}_fieldset" );
			$choicePath = "{$path}_choice";

			$input = $this->Form->input(
				$choicePath,
				array(
					'label' => __d( $params['domain'], $choicePath ),
					'type' => 'checkbox'
				)
			);

			$input .= $this->Html->tag(
				'fieldset',
				$this->Html->tag( 'legend', __d( $params['domain'], $path ) )
				.$this->Form->input(
					$path,
					array(
						'label' => false,
						'type' => 'select',
						'multiple' => 'checkbox',
						'options' => $options,
						'fieldset' => false
					)
				),
				array( 'id' => $fieldsetId )
			);

			$script = $this->PrototypeObserver->disableFieldsetOnCheckbox( $choicePath, $fieldsetId, false, $params['hide'] );
//			$script = $this->_constuctObserve( $this->domId( $choicePath ), $fieldsetId, false, $params['hide'] );

			return $input.$script;
		}

		/**
		 * Méthode générique permettant de filtrer sur une plage de dates.
		 *
		 * @todo Options: dateFormat, maxYear, minYear, ...
		 *
		 * @param string $path
		 * @param array $params
		 * @return string
		 */
		public function dateRange( $path, array $params = array() ) {
			$default = array(
				'domain' => 'search_plugin',
				'options' => array(),
				'legend' => null,
				'hide' => false
			);
			$params = $params + $default;

			$fieldsetId = $this->domId( $path ).'_from_to';

			$script = $this->PrototypeObserver->disableFieldsetOnCheckbox( $path, $fieldsetId, false, $params['hide'] );
//			$script = $this->_constuctObserve( $this->domId( $path ), $fieldsetId, false, $params['hide'] );

			$legend = Hash::get( $params, 'legend' );
			if( $legend === null ) {
				$legend = __d( $params['domain'], $path );
			}

			$input = $this->Form->input( $path, array( 'label' => 'Filtrer par '.lcfirst( $legend ), 'type' => 'checkbox' ) );

			$input .= $this->Html->tag(
				'fieldset',
				$this->Html->tag( 'legend', $legend )
				.$this->Form->input( $path.'_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'default' => strtotime( '-1 week' ) ) )
				.$this->Form->input( $path.'_to', array( 'label' => 'Au (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 5, 'minYear' => date( 'Y' ) - 120 ) ),
				array( 'id' => $fieldsetId )
			);

			return $script.$input;
		}
	}
?>