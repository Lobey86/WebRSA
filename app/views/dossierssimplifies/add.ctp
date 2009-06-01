<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  echo $form->create( 'Dossiersimplifie',array( 'url' => Router::url( null, true ) ) ); ?>

<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsOnValue( 'Personne0Toppersdrodevorsa', [ /*'Typeorient0ParentId', */'Orientstruct0TypeorientId', 'Orientstruct0StructurereferenteId' ], 0, true );
        observeDisableFieldsOnValue( 'Personne1Toppersdrodevorsa', [ 'Orientstruct1TypeorientId', 'Orientstruct1StructurereferenteId' ], 0, true );
    });
</script>
   <h1><?php echo $this->pageTitle = 'Ajout d\'une préconisation d\'orientation'; ?></h1>

        <fieldset>
            <h2>Dossier RSA</h2>
            <?php echo $form->input( 'Dossier.numdemrsa', array( 'label' => required( 'Numéro de demande RSA' ) ) );?>
            <?php echo $form->input( 'Dossier.dtdemrsa', array( 'label' => required( 'Date de demande' ), 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1, 'minYear' => date( 'Y' ) - 1 ) );?>
            <div><?php echo $form->input( 'Foyer.id', array( 'label' => required( __( 'id', true ) ), 'type' => 'hidden') );?></div>
        </fieldset>
        <fieldset>
            <h2>Personne à orienter</h2>
            <div><?php echo $form->input( 'Personne.0.rolepers', array( 'label' => required( __( 'rolepers', true ) ), 'value' => 'DEM', 'type' => 'hidden') );?></div>
            <div><?php echo $form->input( 'Personne.0.id', array( 'label' => required( __( 'id', true ) ),  'type' => 'hidden') );?></div>
            <?php echo $form->input( 'Personne.0.qual', array( 'label' => required( __( 'qual', true ) ), 'type' => 'select', 'options' => $qual, 'empty' => true ) );?>
            <?php echo $form->input( 'Personne.0.nom', array( 'label' => required( __( 'nom', true ) ) ) );?>
            <?php echo $form->input( 'Personne.0.prenom', array( 'label' => required( __( 'prenom', true ) ) ) );?>
            <?php echo $form->input( 'Personne.0.nir', array( 'label' => required( __( 'nir', true ) ) ) );?>
            <?php echo $form->input( 'Personne.0.dtnai', array( 'label' => required( __( 'dtnai', true ) ), 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => ( date( 'Y' ) - 100 ), 'empty' => true ) );?>
            <?php echo $form->input( 'Personne.0.toppersdrodevorsa', array(  'label' =>  required( __( 'toppersdrodevorsa', true ) ), 'options' => $toppersdrodevorsa, 'type' => 'select', 'empty' => 'Non défini'  ) );?>
        </fieldset>
        <fieldset>
            <h3>Orientation</h3>
            <?php echo $form->input( 'Orientstruct.0.typeorient_id', array( 'label' => "Type d'orientation / Type de structure", 'type' => 'select' , 'options' => $options, 'empty' => true ) );?>
          <!--  <?php echo $form->input( 'Typeorient.0.parent_id', array( 'label' =>  __( 'lib_type_orient', true ), 'type' => 'select', 'options' => $typesOrient, 'empty' => true ) );?>
            <?php echo $form->input( 'Orientstruct.0.typeorient_id', array( 'label' => __( 'lib_struc', true ), 'type' => 'select', 'options' => $typesStruct, 'empty' => true ) );?> -->
            <?php echo $form->input( 'Orientstruct.0.structurereferente_id', array( 'label' => __( 'structure_referente', true ), 'type' => 'select', 'options' => $structsReferentes, 'empty' => true ) );?>
        </fieldset>
        <fieldset>
            <h2>Autre personne à orienter (le cas échéant)</h2>
            <div> <?php echo $form->input( 'Personne.1.rolepers', array( 'label' =>  __( 'rolepers', true ) , 'value' => 'CJT', 'type' => 'hidden') );?></div>
            <div><?php  echo $form->input( 'Personne.1.id', array( 'label' => required( __( 'id', true ) ), 'type' => 'hidden') );?></div>
            <?php echo $form->input( 'Personne.1.qual', array( 'label' =>  __( 'qual', true ) , 'type' => 'select', 'options' => $qual, 'empty' => true ) );?>
            <?php echo $form->input( 'Personne.1.nom', array( 'label' =>  __( 'nom', true )  ) );?>
            <?php echo $form->input( 'Personne.1.prenom', array( 'label' =>  __( 'prenom', true  ) ) );?>
            <?php echo $form->input( 'Personne.1.nir', array( 'label' =>  __( 'nir', true ) ) );?>
            <?php echo $form->input( 'Personne.1.dtnai', array( 'label' =>  __( 'dtnai', true  ), 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => ( date( 'Y' ) - 100 ), 'empty' => true ) );?>
            <?php echo $form->input( 'Personne.1.toppersdrodevorsa', array(  'label' =>   __( 'toppersdrodevorsa', true ), 'options' => $toppersdrodevorsa, 'type' => 'select', 'empty' => 'Non défini'  ) );?>
        </fieldset>
        <fieldset>
            <h3>Orientation</h3>
            <?php echo $form->input( 'Orientstruct.1.typeorient_id', array( 'label' => "Type d'orientation / Type de structure", 'type' => 'select' , 'options' => $options, 'empty' => true ) );?>
          <!--  <?php echo $form->input( 'Typeorient.1.parent_id', array( 'label' =>  __( 'lib_type_orient', true ), 'type' => 'select', 'options' => $typesOrient, 'empty' => true ) );?>
            <?php echo $form->input( 'Orientstruct.1.typeorient_id', array( 'label' => __( 'lib_struc', true ), 'type' => 'select', 'options' => $typesStruct, 'empty' => true ) );?> -->
            <?php echo $form->input( 'Orientstruct.1.structurereferente_id', array( 'label' => __( 'structure_referente', true ), 'type' => 'select', 'options' => $structsReferentes, 'empty' => true ) );?>
        </fieldset>

        <?php echo $form->submit( 'Enregistrer' );?>
    <?php echo $form->end();?>

<div class="clearer"><hr /></div>