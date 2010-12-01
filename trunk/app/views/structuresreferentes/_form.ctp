<fieldset>
    <?php echo $form->input( 'Structurereferente.lib_struc', array( 'label' => required( __d( 'structurereferente', 'Structurereferente.lib_struc', true ) ), 'type' => 'text' ) );?>
    <?php echo $form->input( 'Structurereferente.num_voie', array( 'label' => required( __( 'num_voie', true ) ), 'type' => 'text', 'maxlength' => 15 ) );?>
    <?php echo $form->input( 'Structurereferente.type_voie', array( 'label' => required( __( 'type_voie', true ) ), 'type' => 'select', 'options' => $typevoie, 'empty' => true ) );?>
    <?php echo $form->input( 'Structurereferente.nom_voie', array( 'label' => required(  __( 'nom_voie', true ) ), 'type' => 'text', 'maxlength' => 50 ) );?> 
    <?php echo $form->input( 'Structurereferente.code_postal', array( 'label' => required( __( 'code_postal', true ) ), 'type' => 'text', 'maxlength' => 5 ) );?> 
    <?php echo $form->input( 'Structurereferente.ville', array( 'label' => required( __( 'ville', true ) ), 'type' => 'text' ) );?> 
    <?php echo $form->input( 'Structurereferente.code_insee', array( 'label' => required( __( 'code_insee', true ) ), 'type' => 'text', 'maxlength' => 5 ) );?> 
</fieldset>
<div><?php echo $form->input( 'Structurereferente.filtre_zone_geo', array( 'label' => 'Restreindre les zones géographiques', 'type' => 'checkbox' ) );?></div>
<fieldset class="col2" id="filtres_zone_geo">
    <legend>Zones géographiques</legend>
    <script type="text/javascript">
        function toutCocher() {
            $$( 'input[name="data[Zonegeographique][Zonegeographique][]"]' ).each( function( checkbox ) {
                $( checkbox ).checked = true;
            });
        }

        function toutDecocher() {
            $$( 'input[name="data[Zonegeographique][Zonegeographique][]"]' ).each( function( checkbox ) {
                $( checkbox ).checked = false;
            });
        }

        document.observe("dom:loaded", function() {
            Event.observe( 'toutCocher', 'click', toutCocher );
            Event.observe( 'toutDecocher', 'click', toutDecocher );
            observeDisableFieldsetOnCheckbox( 'StructurereferenteFiltreZoneGeo', 'filtres_zone_geo', false );
        });
    </script>
    <?php echo $form->button( 'Tout cocher', array( 'id' => 'toutCocher' ) );?>
    <?php echo $form->button( 'Tout décocher', array( 'id' => 'toutDecocher' ) );?>

    <?php echo $form->input( 'Zonegeographique.Zonegeographique', array( 'label' => required( false ), 'multiple' => 'checkbox' , 'options' => $zglist ) );?>
</fieldset>
<fieldset class="col2">
    <legend>Types d'orientations</legend>
    <?php echo $form->input( 'Structurereferente.typeorient_id', array( 'label' => required( false ), 'type' => 'select' , 'options' => $options, 'empty' => true ) );?>
</fieldset>

<fieldset class="col2">
    <legend><?php echo required( 'Gère les CERs ?' );?></legend>
    <?php echo $xform->enum( 'Structurereferente.contratengagement', array(  'legend' => false, /*'div' => false, */ 'required' => true, 'type' => 'radio', 'separator' => '<br />', 'options' => $optionsradio['contratengagement'] ) );?>
</fieldset>
<fieldset class="col2">
    <legend><?php echo required( 'Gère les APREs ?' );?></legend>
    <?php echo $xform->enum( 'Structurereferente.apre', array(  'legend' => false,/* 'div' => false,*/  'required' => true, 'type' => 'radio', 'separator' => '<br />', 'options' => $optionsradio['apre'] ) );?>
</fieldset>

<fieldset class="col2">
    <legend><?php echo 'Gère les Orientations ?';?></legend>
    <?php echo $xform->enum( 'Structurereferente.orientation', array(  'legend' => false, 'type' => 'radio', 'separator' => '<br />', 'options' => $optionsradio['orientation'] ) );?>
</fieldset>
<fieldset class="col2">
    <legend><?php echo 'Gère les PDOs ?';?></legend>
    <?php echo $xform->enum( 'Structurereferente.pdo', array(  'legend' => false, 'type' => 'radio', 'separator' => '<br />', 'options' => $optionsradio['pdo'] ) );?>
</fieldset>