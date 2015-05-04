<?php
	$this->pageTitle = __d( 'droit', 'Gestionsdoublons:fusion' );
	echo $this->Xhtml->tag( 'h1', $this->pageTitle );
?>

<?php if( !empty( $fichiersModuleLies ) ): ?>
	<div class="errorslist">
		Impossible de procéder à la fusion des enregistrements liés aux foyers en doublons car des fichiers liés à ces enregistrements existent:
		<ul>
			<?php
				foreach( $fichiersModuleLies as $fichier ) {
					$controller = Inflector::tableize( $fichier['Fichiermodule']['modele'] );
					echo "<li>".$this->Xhtml->link(
						$fichier['Fichiermodule']['modele'],
						array( 'controller' => $controller, 'action' => 'filelink', $fichier['Fichiermodule']['fk_value'] ),
						array( 'class' => 'external' )
					)."</li>";
				}
			?>
		</ul>
	</div>
<?php endif;?>

<?php if( isset( $errors ) && !empty( $errors ) ): ?>
	<div class="errorslist">
		Impossible de procéder à la fusion des enregistrements:
		<ul>
			<?php
				foreach( $errors as $error ) {
					echo "<li>".h( $error )."</li>";
				}
			?>
		</ul>
	</div>
<?php endif;?>

<?php if( empty( $fichiersModuleLies ) ): ?>
	<?php
		echo $this->element( 'required_javascript' );

		echo $this->Default3->DefaultForm->create();

		// Foyers
		echo '<h2>Foyer</h2>';
		echo '<table id="Foyer">';
		echo '<thead>';
		echo '<tr>';
		echo '<th>Garder ?</th>';
		foreach( array_keys( $results[0]['Dossier'] ) as $field ) {
			echo '<th>'.$field.'</th>';
		}
		foreach( array_keys( $results[0]['Foyer'] ) as $field ) {
			echo '<th>'.$field.'</th>';
		}
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		foreach( $results as $result ) {
			echo '<tr class="id'.$result['Foyer']['id'].'">';
			echo '<td><label><input type="radio" name="data[Foyer][id]" value="'.$result['Foyer']['id'].'" /> Garder</label></td>';
			foreach( array_keys( $result['Dossier'] ) as $field ) {
				echo '<td>'.$result['Dossier'][$field].'</td>';
			}
			foreach( array_keys( $result['Foyer'] ) as $field ) {
				echo '<td>'.$result['Foyer'][$field].'</td>';
			}
			echo '</tr>';
		}
		echo '</tbody>';
		echo '</table>';

		// Tables des enregistrements liés
		foreach( $modelNames as $modelName ) {
			if( $modelName != 'Dossier' ) {
				echo '<h2>'.$modelName.'</h2>';
				$records = Hash::extract( $results, "{n}.{$modelName}.{n}" );

				if( empty( $records ) ) {
					echo '<p class="notice">Aucun enregistrement</p>';
				}
				else {
					echo '<table id="'.$modelName.'" class="tableliee">';
					echo '<thead>';
					echo '<tr>';
					echo '<th>Garder ?</th>';
					foreach( array_keys( $records[0] ) as $field ) {
						echo '<th>'.$field.'</th>';
					}
					echo '</tr>';
					echo '</thead>';

					echo '<tbody>';
					foreach( $records as $record ) {
						echo '<tr class="foyer_id'.$record['foyer_id'].'">';
						// Radio ou checkbox, c'est selon
						echo '<td><label><input type="checkbox" name="data['.$modelName.'][id][]" id="'.$modelName.'Id'.$record['id'].'" value="'.$record['id'].'" /> Garder</label></td>';
						foreach( $record as $field => $value ) {
							echo '<td>'.$value.'</td>';
						}
						echo '</tr>';
					}
					echo '</tbody>';
					echo '</table>';
				}
			}
		}

		echo $this->Default3->DefaultForm->buttons( array( 'Save', 'Cancel' ) );
		echo $this->Default3->DefaultForm->end();
	?>

	<script type="text/javascript">
			// <![CDATA[
			// Cocher les enregistrements dépendants depuis la table foyers
			var v = $( 'FoyerFusionForm' ).getInputs( 'radio', 'data[Foyer][id]' );
			var currentValue = undefined;
			$( v ).each( function( radio ) {
				$( radio ).observe( 'change', function( event ) {
					toutDecocher( '.tableliee input[type="checkbox"]' );
					toutDecocher( '.tableliee input[type="radio"]' );
					toutCocher( '.tableliee .foyer_id' + radio.value + ' input[type=checkbox]' );
					toutCocher( '.tableliee .foyer_id' + radio.value + ' input[type=radio]' );
				} );
			} );

			// Cocher les enregistrements dépendants entre tables (ex.: dsps.id, dsps_revs.dsp_id)
			function foo( modelFrom, columnFrom, modelTo, columnTo ) {
				var v = $( 'Foyer' ).getInputs( 'radio', 'data[' + modelTo + '][' + columnTo + '][]' );//FIXME
				var currentValue = undefined;
				$( v ).each( function( radio ) {
					$( radio ).observe( 'change', function( event ) {
						toutDecocher( '#' + modelFrom + ' input[type="checkbox"]' );
						toutDecocher( '#' + modelFrom + ' input[type="radio"]' );
						toutCocher( '#' + modelFrom + ' .' + columnFrom + radio.value + ' input[type=checkbox]' );
						toutCocher( '#' + modelFrom + ' .' + columnFrom + radio.value + ' input[type=radio]' );
					} );
				} );
			}

			// FIXME
			/*<?php if( !empty( $foreignkeys ) ): ?>
				<?php foreach( $foreignkeys as $foreignkey ): ?>
					foo( '<?php echo Inflector::classify( $foreignkey['From']['table'] );?>',
						'<?php echo $foreignkey['From']['column'];?>',
						'<?php echo Inflector::classify( $foreignkey['To']['table'] );?>',
						'<?php echo $foreignkey['To']['column'];?>'
					);
				<?php endforeach; ?>
			<?php endif; ?>*/

			// Mise en évidence à partir de la table #Foyer vers les tables liées
			var re = new RegExp( '^.*id([0-9]+).*$', 'g' );
			$$( '#Foyer tr' ).each( function( elmt ) {
				// Ajout d'une classe
				$(elmt).observe( 'mouseover', function( event ) {
					var classes = this.classNames().toString();
					var foyerId = classes.replace( re, '$1' );
					$(this).addClassName( 'highlight' );
					$$( '.tableliee tr.foyer_id' + foyerId ).each( function( row ) {
						$(row).addClassName( 'highlight' );
					} );
				} );
				// Suppression d'une classe
				$(elmt).observe( 'mouseout', function( event ) {
					var classes = this.classNames().toString();
					var foyerId = classes.replace( re, '$1' );
					$(this).removeClassName( 'highlight' );
					$$( '.tableliee tr.foyer_id' + foyerId ).each( function( row ) {
						$(row).removeClassName( 'highlight' );
					} );
				} );
			} );
			// ]]>
	</script>
<?php endif; ?>