    <script type="text/javascript">
        document.observe("dom:loaded", function() {
            observeDisableFieldsOnCheckbox( 'DsppDrorsarmiant', [ 'DsppDrorsarmianta2' ], false );
            observeDisableFieldsOnCheckbox( 'DifsocDifsoc7', [ 'DsppLibautrdifsoc' ], false );
            observeDisableFieldsOnCheckbox( 'NataccosocindiNataccosocindi5', [ 'DsppLibautraccosocindi' ], false );
            observeDisableFieldsOnCheckbox( 'DsppElopersdifdisp', [ 'DsppObstemploidifdisp' ], false );

            observeDisableFieldsOnValue( 'DsppAccoemploi', [ 'DsppLibcooraccoemploi' ], '1801', true );
            observeDisableFieldsOnValue( 'DsppHispro', [ 'DsppLibderact', 'DsppLibsecactderact', 'DsppDfderactDay', 'DsppDfderactMonth', 'DsppDfderactYear', 'DsppDomideract', 'DsppLibactdomi', 'DsppLibsecactdomi', 'DsppDuractdomi', 'DsppLibemploirech', 'DsppLibsecactrech' ], '1904', true );
        });
    </script>

    <fieldset>
            <legend>Généralités DSPP</legend>
                <?php echo $form->input( 'Dspp.drorsarmiant', array( 'label' => __( 'drorsarmiant', true ), 'type' => 'checkbox' ) );?>
                <?php echo $form->input( 'Dspp.drorsarmianta2', array( 'label' => __( 'drorsarmianta2', true ), 'type' => 'checkbox' ) );?>
                <?php echo $form->input( 'Dspp.couvsoc', array( 'label' => __( 'couvsoc', true )));?>
    </fieldset>
    <fieldset>
            <legend>Situation sociale</legend>
                <?php echo $form->input( 'Dspp.elopersdifdisp', array( 'label' => __( 'elopersdifdisp', true ), 'type' => 'checkbox' ) );?>
                <?php echo $form->input( 'Dspp.obstemploidifdisp', array( 'label' => __( 'obstemploidifdisp', true ), 'type' => 'checkbox' ) );?>
                <?php echo $form->input( 'Dspp.soutdemarsoc', array( 'label' => __( 'soutdemarsoc', true ), 'type' => 'checkbox' ) );?>
                <?php echo $form->input( 'Dspp.libcooraccosocindi', array( 'label' => required( __( 'libcooraccosocindi', true ) ), 'type' => 'textarea', 'rows' =>3 ) );?>
    </fieldset>


    <fieldset class="col2">
        <legend><?php echo __( 'difsoc', true ) ?></legend>
            <?php echo $form->input( 'Difsoc.Difsoc', array( 'label' => false, 'div' => false, 'multiple' => 'checkbox', 'options' => $difsocs ) );?>
                <?php echo $form->input( 'Dspp.libautrdifsoc', array( 'label' => __( 'libautrdifsoc', true ), 'type' => 'text') );?>
    </fieldset>
    <fieldset class="col2">
        <legend><?php echo __( 'nataccosocindi', true ) ?></legend>
        <?php echo $form->input( 'Nataccosocindi.Nataccosocindi', array( 'label' => false, 'div' => false, 'multiple' => 'checkbox', 'options' => $nataccosocindis ) );?>
        <?php echo $form->input( 'Dspp.libautraccosocindi', array( 'label' => __( 'libautraccosocindi', true ), 'type' => 'text' ) );?>
    </fieldset>
    <fieldset class="col2">
        <legend><?php echo __( 'difdisp', true ) ?></legend>
    <?php echo $form->input( 'Difdisp.Difdisp', array( 'label' => false, 'div' => false,  'multiple' => 'checkbox', 'options' => $difdisps ) );?>
    </fieldset>
    <fieldset>
            <legend>Niveau d'étude</legend>
                <?php echo $form->input( 'Dspp.annderdipobt', array( 'label' => required( __( 'annderdipobt', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y'), 'minYear'=>date('Y')-80, 'empty' => true ) );?>
                <?php echo $form->input( 'Dspp.rappemploiquali', array( 'label' => __( 'rappemploiquali', true ), 'type' => 'checkbox' ) );?>
                <?php echo $form->input( 'Dspp.rappemploiform', array( 'label' => __( 'rappemploiform', true ), 'type' => 'checkbox' ) );?>
                <?php echo $form->input( 'Dspp.libautrqualipro', array( 'label' => __( 'libautrqualipro', true ), 'type' => 'text' ) );?>
                <?php echo $form->input( 'Dspp.permicondub', array( 'label' => __( 'permicondub', true ), 'type' => 'checkbox' ) );?>
                <?php echo $form->input( 'Dspp.libautrpermicondu', array( 'label' => __( 'libautrpermicondu', true ), 'type' => 'text' ) );?>
                <?php echo $form->input( 'Dspp.libcompeextrapro', array( 'label' => __( 'libcompeextrapro', true ), 'type' => 'text' ) );?>
                <?php echo $form->input( 'Dspp.nivetu', array( 'label' => __( 'nivetu', true ), 'type' => 'select', 'options' => $nivetu, 'empty' => true ) );?>
    </fieldset>

    <fieldset>
            <legend>Situation professionnelle</legend>
                <?php echo $form->input( 'Dspp.accoemploi', array( 'label' => required( __( 'accoemploi', true ) ), 'type' => 'select', 'options' => $accoemploi, 'empty' => true) );?>
                <?php echo $form->input( 'Dspp.libcooraccoemploi', array( 'label' => __( 'libcooraccoemploi', true ), 'type' => 'textarea', 'rows' =>3 ) );?>
                <?php echo $form->input( 'Dspp.hispro', array( 'label' => required( __( 'hispro', true ) ), 'type' => 'select', 'options' => $hispro, 'empty' => true ) );?>
                <?php echo $form->input( 'Dspp.libderact', array( 'label' => __( 'libderact', true ), 'type' => 'text' ) );?>
                <?php echo $form->input( 'Dspp.libsecactderact', array( 'label' => __( 'libsecactderact', true ), 'type' => 'text' ) );?>
                <?php echo $form->input( 'Dspp.dfderact', array( 'label' => required( __( 'dfderact', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y'), 'minYear'=>date('Y')-80 , 'empty' => true ) );?>
                <?php echo $form->input( 'Dspp.domideract', array( 'label' => __( 'domideract', true ), 'type' => 'checkbox' ) );?>
                <?php echo $form->input( 'Dspp.libactdomi', array( 'label' => __( 'libactdomi', true ), 'type' => 'text' ) );?>
                <?php echo $form->input( 'Dspp.libsecactdomi', array( 'label' => __( 'libsecactdomi', true ), 'type' => 'text' ) );?>
                <?php echo $form->input( 'Dspp.duractdomi', array( 'label' => required( __( 'duractdomi', true ) ), 'type' => 'select', 'options' => $duractdomi, 'empty' => true ) );?>
                <?php echo $form->input( 'Dspp.libemploirech', array( 'label' => __( 'libemploirech', true ), 'type' => 'text' ) );?>
                <?php echo $form->input( 'Dspp.libsecactrech', array( 'label' => __( 'libsecactrech', true ), 'type' => 'text' ) );?>
                <?php echo $form->input( 'Dspp.creareprisentrrech', array( 'label' => __( 'creareprisentrrech', true ), 'type' => 'checkbox' ) );?>
                <?php echo $form->input( 'Dspp.moyloco', array( 'label' => __( 'moyloco', true ), 'type' => 'checkbox' ) );?>
                <?php echo $form->input( 'Dspp.persisogrorechemploi', array( 'label' => __( 'persisogrorechemploi', true ), 'type' => 'checkbox' ) );?>
    </fieldset>
    <fieldset>
        <legend><?php echo __( 'natmob', true ) ?> </legend>
            <?php echo $form->input( 'Natmob.Natmob', array( 'label' => false, 'div' => false, 'multiple' => 'checkbox', 'options' => $natmobs ) );?>
    </fieldset>