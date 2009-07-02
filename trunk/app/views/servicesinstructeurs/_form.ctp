<script type="text/javascript">
    document.observe("dom:loaded", function() {
        //observeDisableFieldsOnValue( 'ServiceinstructeurNumdepins', [ 'ServiceinstructeurTypeserins' ], 093, false );
    });
</script>

<fieldset>
    <?php echo $form->input( 'Serviceinstructeur.lib_service', array( 'label' =>  required( __( 'lib_service', true ) ), 'type' => 'text' ) );?>
    <?php echo $form->input( 'Serviceinstructeur.num_rue', array( 'label' =>  __( 'num_rue', true ), 'type' => 'text', 'maxLength' => 6 ) );?>
    <?php echo $form->input( 'Serviceinstructeur.type_voie', array( 'label' =>  __( 'type_voie', true ), 'type' => 'select', 'options' => $typevoie, 'empty' => true ) );?>
    <?php echo $form->input( 'Serviceinstructeur.nom_rue', array( 'label' =>  __( 'nom_rue', true ), 'type' => 'text' ) );?>
    <?php echo $form->input( 'Serviceinstructeur.complement_adr', array( 'label' =>  __( 'complement_adr', true ), 'type' => 'text' ) );?>
    <?php echo $form->input( 'Serviceinstructeur.code_insee', array( 'label' =>  required( __( 'code_insee', true ) ), 'type' => 'text' ) );?>
    <?php echo $form->input( 'Serviceinstructeur.code_postal', array( 'label' =>  __( 'code_postal', true ), 'type' => 'text', 'maxLength' => 5 ) );?>
    <?php echo $form->input( 'Serviceinstructeur.ville', array( 'label' =>  __( 'ville', true ), 'type' => 'text' ) );?>
</fieldset>
<fieldset>
    <?php echo $form->input( 'Serviceinstructeur.numdepins', array( 'label' =>  required( __( 'numdepins', true ) ), 'type' => 'text', 'maxLength' => 3 ) );?>
    <?php echo $form->input( 'Serviceinstructeur.typeserins', array( 'label' => required( __( 'typeserins', true ) ), 'type' => 'select', 'empty' => true ) );?>
    <?php echo $form->input( 'Serviceinstructeur.numcomins', array( 'label' =>  required( __( 'numcomins', true ) ), 'type' => 'text', 'maxLength' => 3 ) );?>
    <?php echo $form->input( 'Serviceinstructeur.numagrins', array( 'label' => required( __( 'numagrins', true ) ), 'type' => 'text', 'maxLength' => 2 ) );?>
</fieldset>
