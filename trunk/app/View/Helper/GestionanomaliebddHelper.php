<?php
	class GestionanomaliebddHelper extends AppHelper
	{
		public $helpers = array( 'Xhtml', 'Permissions' );

		/**
		*
		*/
		public function foyerPersonnesSansPrestation( $data, $link = true , $valuePath = 'Foyer.sansprestation', $foyerIdPath = 'Foyer.id' ) {
			$value = Set::classicExtract( $data, $valuePath );

			$icon = ( $value ? $this->Xhtml->image( 'icons/error.png', array( 'alt' => '', 'title' => $value ) ) : null );
			if( $link && $this->Permissions->check( 'gestionsanomaliesbdds', 'foyer' ) ) {
				$icon = $this->Xhtml->link(
					$icon,
					array(
						'controller' => 'gestionsanomaliesbdds',
						'action' => 'foyer',
						Set::classicExtract( $data, $foyerIdPath )
					),
					array(),
					false,
					false
				);
			}

			return $icon;
		}

		/**
		*
		*/
		public function foyerErreursPrestationsAllocataires( $data, $link = true , $valuePath = 'Foyer.enerreur', $foyerIdPath = 'Foyer.id' ) {
			$value = Set::classicExtract( $data, $valuePath );

			$icon = ( $value ? $this->Xhtml->image( 'icons/exclamation.png', array( 'alt' => '', 'title' => $value ) ) : null );
			if( $link && $this->Permissions->check( 'gestionsanomaliesbdds', 'foyer' ) ) {
				$icon = $this->Xhtml->link(
					$icon,
					array(
						'controller' => 'gestionsanomaliesbdds',
						'action' => 'foyer',
						Set::classicExtract( $data, $foyerIdPath )
					),
					array(),
					false,
					false
				);
			}

			return $icon;
		}

		/**
		*
		*/
		public function foyerErreursDoublonsPersonnes( $data, $link = true, $valuePath = 'Foyer.doublonspersonnes', $foyerIdPath = 'Foyer.id' ) {
			$value = Set::classicExtract( $data, $valuePath );

			$icon = ( $value ? $this->Xhtml->image( 'icons/group.png', array( 'alt' => '', 'title' => $value ) ) : null );
			if( $link && $this->Permissions->check( 'gestionsanomaliesbdds', 'foyer' ) ) {
				$icon = $this->Xhtml->link(
					$icon,
					array(
						'controller' => 'gestionsanomaliesbdds',
						'action' => 'foyer',
						Set::classicExtract( $data, $foyerIdPath )
					),
					array(),
					false,
					false
				);
			}

			return $icon;
		}
	}
?>