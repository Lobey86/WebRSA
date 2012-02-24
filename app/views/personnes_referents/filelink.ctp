<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $xhtml->css( array( 'fileuploader' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $javascript->link( 'fileuploader.js' );
	}

	$this->pageTitle =  __d( 'personne_referent', "PersonnesReferents::{$this->action}", true );
	echo $this->element( 'dossier_menu', array( 'personne_id' => $personneId ) );
?>
<div class="with_treemenu">
	<?php
		echo $xhtml->tag( 'h1', $this->pageTitle );
		echo $fileuploader->element( 'PersonneReferent', $fichiers, $personneReferent, $options['haspiecejointe'] );
	?>
</div>
<div class="clearer"><hr /></div>