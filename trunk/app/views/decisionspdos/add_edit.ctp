<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Décisions PDOs';?>

    <h1><?php echo $this->pageTitle;?></h1>

    <?php 
        if( $this->action == 'add' ) {
            echo $form->create( 'Decisionpdo', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
            echo $form->input( 'Decisionpdo.id', array( 'type' => 'hidden', 'value' => '' ) );
        }
        else {
            echo $form->create( 'Decisionpdo', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
            echo $form->input( 'Decisionpdo.id', array( 'type' => 'hidden' ) );
        }
    ?>

    <fieldset>
        <?php //echo $form->input( 'Decisionpdo.libelle', array( 'label' => required( __( 'Décision de PDO', true ) ), 'type' => 'text' ) );?>
        <?php //echo $form->input( 'Decisionpdo.clos', array( 'label' => required( __d( 'Decisionpdo.clos', 'decisionpdo', true ) ), 'type' => 'radio' ) );?>
        <?php
        	echo $default->subform(
				array(
					'Decisionpdo.libelle',
					'Decisionpdo.clos' => array( 'type' => 'radio' )
				),
				array(
					'options' => $options
				)
			);
		?>
    </fieldset>

        <?php echo $form->submit( 'Enregistrer' );?>
    <?php echo $form->end();?>

<div class="clearer"><hr /></div>