    <script type="text/javascript">
        document.observe("dom:loaded", function() {

            observeDisableFieldsOnCheckbox( 'DifsocDifsoc7', [ 'DsppLibautrdifsoc' ], false );
            observeDisableFieldsOnCheckbox( 'NataccosocindiNataccosocindi6', [ 'DsppLibautraccosocindi' ], false );

            observeDisableFieldsOnCheckbox( 'AccoemploiAccoemploi1', [ 'DsppLibcooraccoemploi', 'AccoemploiAccoemploi2', 'AccoemploiAccoemploi3', 'DsppLibcooraccoemploi' ], true );

            observeDisableFieldsOnValue( 'DsppHispro', [ 'DsppLibderact', 'DsppLibsecactderact', 'DsppDfderactDay', 'DsppDfderactMonth', 'DsppDfderactYear', 'DsppDomideract', 'DsppLibactdomi', 'DsppLibsecactdomi', 'DsppDuractdomi' ], '1904', true );
            observeDisableFieldsOnCheckbox( 'DifsocDifsoc1', [ 'DifsocDifsoc2', 'DifsocDifsoc3', 'DifsocDifsoc4', 'DifsocDifsoc5', 'DifsocDifsoc6', 'DifsocDifsoc7' ], true );

            observeDisableFieldsOnCheckbox( 'NataccosocindiNataccosocindi1', [ 'NataccosocindiNataccosocindi2', 'NataccosocindiNataccosocindi3', 'NataccosocindiNataccosocindi4', 'NataccosocindiNataccosocindi5', 'NataccosocindiNataccosocindi6' ], true );

            observeDisableFieldsOnValue( 'DsppDrorsarmiant', [ 'DsppDrorsarmianta2' ], 'O', false );
            observeDisableFieldsOnValue( 'DsppElopersdifdisp', [ 'DsppObstemploidifdisp' ], 'O', false );
        });
    </script>

    <fieldset>
            <legend>Généralités DSPP</legend>
                <?php echo $form->input( 'Dspp.drorsarmiant', array( 'label' =>  ( __( 'drorsarmiant', true ) ), 'type' => 'select', 'options' => $drorsarmiant, 'empty' => true ) );?>
                <?php echo $form->input( 'Dspp.drorsarmianta2', array( 'label' =>  ( __( 'drorsarmianta2', true ) ), 'type' => 'select', 'options' => $drorsarmianta2, 'empty' => true ) );?>
                <?php echo $form->input( 'Dspp.couvsoc', array( 'label' =>  ( __( 'couvsoc', true ) ), 'type' => 'select', 'options' => $couvsoc, 'empty' => true ) );?>
    </fieldset>
    <fieldset>
            <legend>Situation sociale</legend>
                <?php echo $form->input( 'Dspp.elopersdifdisp', array( 'label' =>  ( __( 'elopersdifdisp', true ) ), 'type' => 'select', 'options' => $elopersdifdisp, 'empty' => true ) );?>
                <?php echo $form->input( 'Dspp.obstemploidifdisp', array( 'label' =>  ( __( 'obstemploidifdisp', true ) ), 'type' => 'select', 'options' => $obstemploidifdisp, 'empty' => true ) );?>
                <?php echo $form->input( 'Dspp.soutdemarsoc', array( 'label' =>  ( __( 'soutdemarsoc', true ) ), 'type' => 'select', 'options' => $soutdemarsoc, 'empty' => true ) );?>
                <?php echo $form->input( 'Dspp.libcooraccosocindi', array( 'label' => __( 'libcooraccosocindi', true ), 'type' => 'textarea', 'rows' =>3 ) );?>
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
                <?php echo $form->input( 'Dspp.annderdipobt', array( 'label' =>  __( 'annderdipobt', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y'), 'minYear'=>date('Y')-80, 'empty' => true ) );?>
                <?php
                    echo $widget->booleanRadio( 'Dspp.rappemploiquali', array( 'legend' => __( 'rappemploiquali', true )) );
                ?>
                <?php
                    echo $widget->booleanRadio( 'Dspp.rappemploiform', array( 'legend' => __( 'rappemploiform', true )) );
                ?>
                <?php echo $form->input( 'Dspp.libautrqualipro', array( 'label' => __( 'libautrqualipro', true ), 'type' => 'text' ) );?>
                <?php
                    echo $widget->booleanRadio( 'Dspp.permicondub', array( 'legend' => __( 'permicondub', true )) );
                ?>
                <?php echo $form->input( 'Dspp.libautrpermicondu', array( 'label' => __( 'libautrpermicondu', true ), 'type' => 'text' ) );?>
                <?php echo $form->input( 'Dspp.libcompeextrapro', array( 'label' => __( 'libcompeextrapro', true ), 'type' => 'text' ) );?>
                <legend><?php echo __( 'nivetu', true ) ?></legend>
                <?php echo $form->input( 'Nivetu.Nivetu', array( 'label' => false, 'div' => false,  'multiple' => 'checkbox', 'options' => $nivetus ) );?>
                <br />
                <?php echo $form->input( 'Dspp.diplomes', array( 'label' => __( 'diplomes', true ), 'type' => 'textarea', 'rows' => 3)  ); ?>
        </fieldset>

    <fieldset>
            <legend>Situation professionnelle</legend>
                <legend><?php echo __( 'accoemploi', true ) ?></legend>
                <?php echo $form->input( 'Accoemploi.Accoemploi', array( 'label' => false, 'div' => false, 'multiple' => 'checkbox', 'options' => $accoemplois ) );?>
                <?php echo $form->input( 'Dspp.libcooraccoemploi', array( 'label' => __( 'libcooraccoemploi', true ), 'type' => 'textarea', 'rows' =>3 ) );?>
                <?php echo $form->input( 'Dspp.hispro', array( 'label' =>  required( __( 'hispro', true ) ), 'type' => 'select', 'options' => $hispro, 'empty' => true ) );?>
                <?php echo $form->input( 'Dspp.libderact', array( 'label' => __( 'libderact', true ), 'type' => 'text' ) );?>
                <?php echo $form->input( 'Dspp.libsecactderact', array( 'label' => __( 'libsecactderact', true ), 'type' => 'text' ) );?>
                <?php echo $form->input( 'Dspp.dfderact', array( 'label' => required( __( 'dfderact', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y'), 'minYear'=>date('Y')-80 , 'empty' => true ) );?>
                <?php echo $form->input( 'Dspp.domideract', array( 'label' => required( __( 'domideract', true ) ), 'type' => 'select', 'options' => $domideract, 'empty' => true ) );?>
                <?php echo $form->input( 'Dspp.libactdomi', array( 'label' => __( 'libactdomi', true ), 'type' => 'text' ) );?>
                <?php echo $form->input( 'Dspp.libsecactdomi', array( 'label' => __( 'libsecactdomi', true ), 'type' => 'text' ) );?>
                <?php echo $form->input( 'Dspp.duractdomi', array( 'label' => required( __( 'duractdomi', true ) ), 'type' => 'select', 'options' => $duractdomi, 'empty' => true ) );?>
    </fieldset>
    <fieldset>
            <legend>Métier recherché</legend>
                <?php echo $form->input( 'Dspp.libemploirech', array( 'label' => __( 'libemploirech', true ), 'type' => 'text' ) );?>
                <?php echo $form->input( 'Dspp.libsecactrech', array( 'label' => __( 'libsecactrech', true ), 'type' => 'text' ) );?>
                <?php echo $form->input( 'Dspp.creareprisentrrech', array( 'label' =>  ( __( 'creareprisentrrech', true ) ), 'type' => 'select', 'options' => $creareprisentrrech, 'empty' => true ) );?>
                <?php
                    echo $widget->booleanRadio( 'Dspp.moyloco', array( 'legend' => __( 'moyloco', true )) );
                ?>
                <?php
                    echo $widget->booleanRadio( 'Dspp.persisogrorechemploi', array( 'legend' => __( 'persisogrorechemploi', true )) );
                ?>
    </fieldset>
    <fieldset>
        <legend><?php echo __( 'natmob', true ) ?> </legend>
            <?php echo $form->input( 'Natmob.Natmob', array( 'label' => false, 'div' => false, 'multiple' => 'checkbox', 'options' => $natmobs ) );?>
    </fieldset>