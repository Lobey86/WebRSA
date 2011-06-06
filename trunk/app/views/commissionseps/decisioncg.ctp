<h1><?php echo $this->pageTitle = 'Décisions de la commission du '.date('d/m/Y à H:i', strtotime($commissionep['Commissionep']['dateseance'])).' par le CG '; ?></h1>
<?php echo $javascript->link( 'dependantselect.js' ); ?>
<br/>
<div id="tabbedWrapper" class="tabs">
	<?php
		foreach( array_keys( $dossiers ) as $theme ) {
			$file = sprintf( 'decisioncg.%s.liste.ctp', Inflector::underscore( $theme ) );
			echo '<div id="'.$theme.'"><h2 class="title">'.__d( 'dossierep', 'ENUM::THEMEEP::'.Inflector::tableize( $theme ), true ).'</h2>';
			if( !empty( $dossiers[$theme]['liste'] ) ) {
				require_once( $file );
			}
			else {
				echo '<p class="notice">Aucun dossier n\'a été traité pour cette thématique.</p>';
			}
			echo '</div>';
		}

		echo $default->button(
		    'back',
		    array(
		        'controller' => 'commissionseps',
		        'action'     => 'arbitragecg'
		    ),
		    array(
		        'id' => 'Back'
		    )
		);

		/*echo $form->create( null, array( 'url' => Router::url( null, true ) ) );
		echo $form->submit( 'Imprimer le PV de la commission', array( 'name' => 'Imprimerpv' ) );
		echo $form->end();*/

	?>
</div>

<?php
	//if( Configure::read( 'debug' ) > 0 ) {
		echo $javascript->link( 'prototype.livepipe.js' );
		echo $javascript->link( 'prototype.tabs.js' );
	//}
?>

<script type="text/javascript">
	makeTabbed( 'tabbedWrapper', 2 );

	function changeColspanAnnuleReporte( idColumnToChangeColspan, decision, colspanMax, idsNonRaisonpassage ) {
		if ( in_array( decision, [ 'reporte', 'annule', 'maintienref', 'refuse', 'suspensionnonrespect', 'suspensiondefaut', 'maintien' ] ) ) {
			$( idColumnToChangeColspan ).writeAttribute( "colspan", colspanMax );
			idsNonRaisonpassage.each( function ( id ) {
				$( id ).hide();
			});
		}
	}
	
// 	function changeColspanRaisonNonPassage( idColumnToChangeColspan, decision, idsNonRaisonpassage, idRaisonpassage ) {
// 		var colspan = $( idColumnToChangeColspan ).readAttribute( "colspan" );
// 		if ( decision == 'reporte' || decision == 'annule' ) {
// 			$( idColumnToChangeColspan ).writeAttribute( "colspan", parseInt( colspan ) - 1 );
// 		}
// 		afficheRaisonpassage( decision, idsNonRaisonpassage, idRaisonpassage );
// 	}
// 
// 	function afficheRaisonpassage( decision, idsNonRaisonpassage, idRaisonpassage ) {
// 		if ( decision == 'reporte' || decision == 'annule' ) {
// 			idsNonRaisonpassage.each( function ( id ) {
// 				$( id ).hide();
// 			});
// 			$( idRaisonpassage ).show();
// 		}
// 		else {
// 			idsNonRaisonpassage.each( function ( id ) {
// 				$( id ).show();
// 			});
// 			$( idRaisonpassage ).hide();
// 		}
// 	}
</script>