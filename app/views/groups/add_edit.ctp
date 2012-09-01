<?php
	$this->pageTitle = 'Groupe';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $javascript->link( 'prototype.livepipe.js' );
		echo $javascript->link( 'prototype.tabs.js' );
	}
?>
<h1><?php echo $this->pageTitle." ".$this->data['Group']['name'];?></h1><br />
<?php echo $form->create( 'Group', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );?>

<div id="tabbedWrapper" class="tabs">
	<div id="infos">
		<h2 class="title">Informations</h2>
		<?php
			if( $this->action == 'add' ) {
				echo $form->input( 'Group.id', array( 'type' => 'hidden', 'value' => '' ) );
			}
			else {
				echo $form->input( 'Group.id', array( 'type' => 'hidden' ) );
			}
		?>

		<fieldset>
			<?php echo $form->input( 'Group.name', array( 'label' => required( __( 'name', true ) ), 'type' => 'text' ) );?>
			<?php echo $form->input( 'Group.parent_id', array( 'label' => required(  __( 'parent_id', true ) ), 'type' => 'text' ) );?>
		</fieldset>

	</div>

	<div id="droits">
		<h2 class="title">Droits</h2>
		<?php
			if( $this->action == 'add' ) {
				echo $xhtml->para(null, __('Sauvegardez puis &eacute;ditez &agrave; nouveau le groupe pour modifier ses droits.', true));
				echo $xhtml->para(null, __('Les nouveaux groupes h&eacute;ritent des droits des profils auxquels ils sont rattach&eacute;s.', true));
			}
			else {
				echo $this->element('editDroits');
			}
		?>
	</div>
</div>
<?php echo $form->submit( 'Enregistrer' );?>
<?php echo $form->end();?>

<script type="text/javascript">
	makeTabbed( 'tabbedWrapper', 2 );
</script>