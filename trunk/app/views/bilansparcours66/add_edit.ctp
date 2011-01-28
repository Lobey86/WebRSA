<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
    $domain = 'bilanparcours66';

    echo $this->element( 'dossier_menu', array( 'personne_id' => Set::classicExtract( $personne, 'Personne.id') ) );
?>

<?php
    if( $this->action == 'add'  ) {
        if( Configure::read( 'nom_form_bilan_cg' ) == 'cg66' ) {
            $this->pageTitle = 'Ajout d\'un bilan de parcours';
        }
        else {
            $this->pageTitle = 'Ajout d\'une fiche de saisine';
        }
    }
    else {
        if( Configure::read( 'nom_form_bilan_cg' ) == 'cg66' ) {
            $this->pageTitle = 'Édition du bilan de parcours';
        }
        else {
            $this->pageTitle = 'Édition de la fiche de saisine';
        }
    }

	function radioBilan( $view, $path, $value, $label ) {
		$name = 'data['.implode( '][', explode( '.', $path ) ).']';
		$storedValue = Set::classicExtract( $view->data, $path );
		$checked = ( ( $storedValue == $value ) ? 'checked="checked"' : '' );
		return "<label><input type=\"radio\" name=\"{$name}\" value=\"{$value}\" {$checked} />{$label}</label>";
	}
?>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php
        if( $this->action == 'add' ) {
            echo $form->create( 'Bilanparcours66', array( 'type' => 'post', 'url' => Router::url( null, true ),  'id' => 'Bilan' ) );
        }
        else {
            echo $form->create( 'Bilanparcours66', array( 'type' => 'post', 'url' => Router::url( null, true ), 'id' => 'Bilan' ) );
            echo '<div>';
            echo $form->input( 'Bilanparcours66.id', array( 'type' => 'hidden' ) );
            echo '</div>';
        }
        echo '<div>';
        echo $form->input( 'Bilanparcours66.personne_id', array( 'type' => 'hidden', 'value' => Set::classicExtract( $personne, 'Personne.id') ) );
        echo '</div>';
    ?>

    <div class="aere">
    
    	<?php
    		echo $default->subform(
    			array(
    				'Bilanparcours66.typeformulaire' => array( 'type' => 'radio' )
    			),
                array(
                    'options' => $options
                )
    		);
    	?>

<fieldset id="bilanparcourscg">
	<legend>BILAN DU PARCOURS</legend>
		<?php
		    echo $default->subform(
		        array(
		        	'Bilanparcours66.orientstruct_id' => array( 'type' => 'hidden' ),
		            'Bilanparcours66.structurereferente_id',
		            'Bilanparcours66.referent_id',
		            'Bilanparcours66.presenceallocataire' => array('required'=>true),
		            'Bilanparcours66.saisineepl' => array( 'type' => 'radio' )
		        ),
		        array(
		            'options' => $options
		        )
		    );
		?>

	<fieldset>
		<legend>Situation de l'allocataire</legend>
		<table class="wide noborder">
		    <tr>
		        <td class="mediumSize noborder">
		            <strong>Statut de la personne : </strong><?php echo Set::extract( $rolepers, Set::extract( $personne, 'Prestation.rolepers' ) ); ?>
		            <br />
		            <strong>Nom : </strong><?php echo Set::enum( Set::classicExtract( $personne, 'Personne.qual') , $qual ).' '.Set::classicExtract( $personne, 'Personne.nom' );?>
		            <br />
		            <strong>Prénom : </strong><?php echo Set::classicExtract( $personne, 'Personne.prenom' );?>
		            <br />
		            <strong>Date de naissance : </strong><?php echo date_short( Set::classicExtract( $personne, 'Personne.dtnai' ) );?>
		        </td>
		        <td class="mediumSize noborder">
		            <strong>N° Service instructeur : </strong><?php echo Set::classicExtract( $personne, 'Serviceinstructeur.lib_service');?>
		            <br />
		            <strong>N° demandeur : </strong><?php echo Set::classicExtract( $personne, 'Foyer.Dossier.numdemrsa' );?>
		            <br />
		            <strong>N° CAF/MSA : </strong><?php echo Set::classicExtract( $personne, 'Foyer.Dossier.matricule' );?>
		            <br />
		            <strong>Inscrit au Pôle emploi</strong>
		            <?php
		                $isPoleemploi = Set::classicExtract( $personne, 'Activite.0.act' );
		                if( $isPoleemploi == 'ANP' )
		                    echo 'Oui';
		                else
		                    echo 'Non';
		            ?>
		            <br />
		            <strong>N° identifiant : </strong><?php echo Set::classicExtract( $personne, 'Personne.idassedic' );?>
		        </td>
		    </tr>
		    <tr>
		        <td class="mediumSize noborder">
		            <strong>Adresse : </strong><br /><?php echo Set::classicExtract( $personne, 'Foyer.Adressefoyer.0.Adresse.numvoie' ).' '.Set::enum( Set::classicExtract( $personne, 'Foyer.Adressefoyer.0.Adresse.typevoie' ), $options['typevoie'] ).' '.Set::classicExtract( $personne, 'Foyer.Adressefoyer.0.Adresse.nomvoie' ).'<br /> '.Set::classicExtract( $personne, 'Foyer.Adressefoyer.0.Adresse.codepos' ).' '.Set::classicExtract( $personne, 'Foyer.Adressefoyer.0.Adresse.locaadr' );?>
		        </td>
		        <td class="mediumSize noborder">
		            <?php if( Set::extract( $personne, 'Foyer.Modecontact.0.autorutitel' ) == 'A' ):?>
		                    <strong>Numéro de téléphone 1 : </strong><?php echo Set::extract( $personne, 'Foyer.Modecontact.0.numtel' );?>
		            <?php endif;?>
		            <?php if( Set::extract( $personne, 'Foyer.Modecontact.1.autorutitel' ) == 'A' ):?>
		                    <br />
		                    <strong>Numéro de téléphone 2 : </strong><?php echo Set::extract( $personne, 'Foyer.Modecontact.1.numtel' );?>
		            <?php endif;?>
		        </td>
		    </tr>
		    <tr>
		        <td colspan="2" class="mediumSize noborder">
		        <?php if( Set::extract( $personne, 'Foyer.Modecontact.0.autorutiadrelec' ) == 'A' ):?>
		            <strong>Adresse mail : </strong><?php echo Set::extract( $personne, 'Foyer.Modecontact.0.adrelec' );?> <!-- FIXME -->
		        <?php endif;?>
		        </td>
		    </tr>
		</table>
		<?php
		    echo $default->subform(
		        array(
		            'Bilanparcours66.sitfam' => array( 'type' => 'radio' )
		        ),
		        array(
		            'options' => $options
		        )
		    );
		?>
	</fieldset>

		<?php
		    echo $default2->subform(
		        array(
		            'Bilanparcours66.objinit',
		            'Bilanparcours66.objatteint',
		            'Bilanparcours66.objnew',
		            'Bilanparcours66.motifsaisine'
		        ),
		        array(
		            'options' => $options
		        )
		    );
		?>
		
	<?php
		echo $html->tag(
			'p',
			'Proposition du référent :',
			array(
				'style' => 'text-align: center; font-size: 14px; font-weight:bold;'
			)
		);
	?>

	<fieldset>
		<?php
		    /// Traitement de l'orientation sans passage en EP locale
		    $tmp = radioBilan( $this, 'Bilanparcours66.proposition', 'traitement', 'Traitement de l\'orientation du dossier sans passage en EP Locale' );
		    echo $xhtml->tag( 'h3', $tmp );
		?>
		<fieldset id="traitement" class="invisible">
		    <?php
		        echo $default2->subform(
		            array(
		                'Bilanparcours66.maintienorientation' => array( 'type' => 'radio', 'required' => true ),
		                'Bilanparcours66.changementrefsansep' => array( 'type' => 'radio', 'required' => true )
		            ),
		            array(
		                'options' => $options
		            )
		        );
		    ?>
		    <!--<fieldset id="NvReferent">
		        <?php
		            echo $default->subform(
		                array(
		                    'Bilanparcours66.nvsansep_referent_id'
		                ),
		                array(
		                    'options' => $options
		                )
		            );
		        ?>
		    </fieldset>-->
		    <fieldset id="Contratreconduit">
		        <legend>Reconduction du contrat librement débattu</legend>
		        <?php
					echo $default2->subform(
						array(
							'Bilanparcours66.duree_engag',
							'Bilanparcours66.ddreconductoncontrat',
							'Bilanparcours66.dfreconductoncontrat',
							'Bilanparcours66.accompagnement' => array( 'type' => 'radio' )
						),
						array(
							'options' => $options,
							'domain' => $domain
						)
					);
		        ?>
		    </fieldset>
		    <!--<?php
		        echo $default2->subform(
		            array(
		                'Bilanparcours66.accordprojet' => array( 'type' => 'radio', 'required' => true )
		            ),
		            array(
		                'options' => $options,
		                'domain' => $domain
		            )
		        );
		    ?>-->
		</fieldset>
	</fieldset>
	 <fieldset>
		<?php
		    /// "Commission Parcours": Examen du dossier avec passage en EP Locale
		    $tmp = radioBilan( $this, 'Bilanparcours66.proposition', 'parcours', '"Commission Parcours": Examen du dossier avec passage en EP Locale' );
		    echo $xhtml->tag( 'h3', $tmp );
		?>
		<fieldset id="parcours" class="invisible">
		    <?php
		        echo $default2->subform(
		            array(
		                'Bilanparcours66.choixparcours' => array( 'type' => 'radio', 'required' => true )
		            ),
		            array(
		                'options' => $options
		            )
		        );
		    ?>
		    <fieldset id="Maintien" class="invisible">
		        <?php
		             echo $default2->subform(
		                array(
		                    'Bilanparcours66.maintienorientparcours' => array( 'type' => 'radio', 'required' => true ),
		                    'Bilanparcours66.changementrefparcours' => array( 'type' => 'radio', 'required' => true )
		                ),
		                array(
		                    'options' => $options
		                )
		            );
		        ?>
		        <!--<fieldset id="NvparcoursReferent">
		            <?php
		                echo $default2->subform(
		                    array(
		                        'Bilanparcours66.nvparcours_referent_id'
		                    ),
		                    array(
		                        'options' => $options
		                    )
		                );
		            ?>
		        </fieldset>-->
		        <fieldset id="TypeAccompagnement">
		            <?php
		                echo $default2->subform(
		                    array(
								'Bilanparcours66.accompagnement' => array( 'type' => 'radio' )
		                    ),
		                    array(
		                        'options' => $options
		                    )
		                );
		            ?>
		        </fieldset>
		    </fieldset>
		    <fieldset id="Reorientation" class="noborder">
		        <?php
		            echo $default2->subform(
		                array(
		                    'Bilanparcours66.reorientation' => array ( 'type' => 'radio' )
		                ),
		                array(
		                    'options' => $options
		                )
		            );
		        ?>
		    </fieldset>
		    <fieldset id="Precoreorient">
		    	<legend>Préconisation de réorientation</legend>
		        <?php
					echo $default->subform(
						array(
							'Saisineepbilanparcours66.typeorient_id',
							'Saisineepbilanparcours66.structurereferente_id'
						),
						array(
							'options' => $options
						)
					);
		        ?>
		    </fieldset>
		</fieldset>
	</fieldset>
	 <fieldset>
		<?php
		    /// "Commission Audition": Examen du dossier par la commission EP Locale
		    $tmp = radioBilan( $this, 'Bilanparcours66.proposition', 'audition', '"Commission Audition": Examen du dossier par la commission EP Locale' );
		    echo $xhtml->tag( 'h3', $tmp );
		?>
		<fieldset id="audition" class="invisible">
		    <?php
		        echo $default2->subform(
		            array(
		                'Bilanparcours66.examenaudition' => array( 'type' => 'radio', 'required' => true )
		            ),
		            array(
		                'options' => $options
		            )
		        );
		    ?>
		</fieldset>
	</fieldset>
		<?php
		    echo $default2->subform(
		        array(
		            'Bilanparcours66.infoscomplementaires'
		        ),
		        array(
		            'options' => $options
		        )
		    );
		?>
		<?php
			echo $html->tag(
				'p',
				'Observations du bénéficiaire :',
				array(
					'style' => 'text-align: center; font-size: 14px; font-weight:bold;'
				)
			);
		?>
		<?php
		    echo $default2->subform(
		        array(
		            'Bilanparcours66.observbenefrealisationbilan',
		            'Bilanparcours66.observbenefcompterendu',
		            'Bilanparcours66.datebilan' => array( 'dateFormat' => 'DMY', 'maxYear' => date('Y') + 2, 'minYear' => date('Y') - 2, 'empty' => false ),
		        ),
		        array(
		            'options' => $options
		        )
		    );
		?>
    <div class="submit">
        <?php echo $form->submit( 'Enregistrer', array( 'div' => false ) );?>
        <?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
    </div>
</fieldset>

<fieldset id="bilanparcourspe">
	<legend>BILAN DU PARCOURS (Pôle Emploi)</legend>
		<?php
		    echo $default->subform(
		        array(
		        	'Pe.Bilanparcours66.orientstruct_id' => array( 'type' => 'hidden' ),
		            'Pe.Bilanparcours66.structurereferente_id'
		        ),
		        array(
		            'options' => $options
		        )
		    );
		    
		    echo '<div class ="input select';
		    if (isset($this->validationErrors['Bilanparcours66']['referent_id'])) echo ' error';
		    echo '">';
		    echo $default->subform(
			    array(
			        'Pe.Bilanparcours66.referent_id' => array('div'=>false)
			    ),
			    array(
			        'options' => $options
			    )
		    );
		    if (isset($this->validationErrors['Bilanparcours66']['referent_id'])) echo '<div class="error-message">'.$this->validationErrors['Bilanparcours66']['referent_id'].'</div>';
		    echo '</div>';
		    
		    echo '<div class ="input select';
		    if (isset($this->validationErrors['Bilanparcours66']['presenceallocataire'])) echo ' error';
		    echo '">';
		    echo $default->subform(
		        array(
		            'Pe.Bilanparcours66.presenceallocataire' => array('required'=>true, 'div'=>false)
		        ),
		        array(
		            'options' => $options
		        )
		    );
		    if (isset($this->validationErrors['Bilanparcours66']['presenceallocataire'])) echo '<div class="error-message">'.$this->validationErrors['Bilanparcours66']['presenceallocataire'].'</div>';
		    echo '</div>';
		?>

<!--<div class="input select error"><label for="Bilanparcours66Presenceallocataire">Allocataire est-il présent ? <abbr title="Champ obligatoire" class="required">*</abbr></label><select class="form-error" id="Bilanparcours66Presenceallocataire" name="data[Bilanparcours66][presenceallocataire]">
<option value=""></option>
<option value="0">Non</option>
<option value="1">Oui</option>
</select><div class="error-message">Champ obligatoire</div></div>-->

	<fieldset>
		<legend>Situation de l'allocataire</legend>
		<table class="wide noborder">
		    <tr>
		        <td class="mediumSize noborder">
		            <strong>Statut de la personne : </strong><?php echo Set::extract( $rolepers, Set::extract( $personne, 'Prestation.rolepers' ) ); ?>
		            <br />
		            <strong>Nom : </strong><?php echo Set::enum( Set::classicExtract( $personne, 'Personne.qual') , $qual ).' '.Set::classicExtract( $personne, 'Personne.nom' );?>
		            <br />
		            <strong>Prénom : </strong><?php echo Set::classicExtract( $personne, 'Personne.prenom' );?>
		            <br />
		            <strong>Date de naissance : </strong><?php echo date_short( Set::classicExtract( $personne, 'Personne.dtnai' ) );?>
		        </td>
		        <td class="mediumSize noborder">
		            <strong>N° Service instructeur : </strong><?php echo Set::classicExtract( $personne, 'Serviceinstructeur.lib_service');?>
		            <br />
		            <strong>N° demandeur : </strong><?php echo Set::classicExtract( $personne, 'Foyer.Dossier.numdemrsa' );?>
		            <br />
		            <strong>N° CAF/MSA : </strong><?php echo Set::classicExtract( $personne, 'Foyer.Dossier.matricule' );?>
		            <br />
		            <strong>Inscrit au Pôle emploi</strong>
		            <?php
		                $isPoleemploi = Set::classicExtract( $personne, 'Activite.0.act' );
		                if( $isPoleemploi == 'ANP' )
		                    echo 'Oui';
		                else
		                    echo 'Non';
		            ?>
		            <br />
		            <strong>N° identifiant : </strong><?php echo Set::classicExtract( $personne, 'Personne.idassedic' );?>
		        </td>
		    </tr>
		    <tr>
		        <td class="mediumSize noborder">
		            <strong>Adresse : </strong><br /><?php echo Set::classicExtract( $personne, 'Foyer.Adressefoyer.0.Adresse.numvoie' ).' '.Set::enum( Set::classicExtract( $personne, 'Foyer.Adressefoyer.0.Adresse.typevoie' ), $options['typevoie'] ).' '.Set::classicExtract( $personne, 'Foyer.Adressefoyer.0.Adresse.nomvoie' ).'<br /> '.Set::classicExtract( $personne, 'Foyer.Adressefoyer.0.Adresse.codepos' ).' '.Set::classicExtract( $personne, 'Foyer.Adressefoyer.0.Adresse.locaadr' );?>
		        </td>
		        <td class="mediumSize noborder">
		            <?php if( Set::extract( $personne, 'Foyer.Modecontact.0.autorutitel' ) == 'A' ):?>
		                    <strong>Numéro de téléphone 1 : </strong><?php echo Set::extract( $personne, 'Foyer.Modecontact.0.numtel' );?>
		            <?php endif;?>
		            <?php if( Set::extract( $personne, 'Foyer.Modecontact.1.autorutitel' ) == 'A' ):?>
		                    <br />
		                    <strong>Numéro de téléphone 2 : </strong><?php echo Set::extract( $personne, 'Foyer.Modecontact.1.numtel' );?>
		            <?php endif;?>
		        </td>
		    </tr>
		    <tr>
		        <td colspan="2" class="mediumSize noborder">
		        <?php if( Set::extract( $personne, 'Foyer.Modecontact.0.autorutiadrelec' ) == 'A' ):?>
		            <strong>Adresse mail : </strong><?php echo Set::extract( $personne, 'Foyer.Modecontact.0.adrelec' );?> <!-- FIXME -->
		        <?php endif;?>
		        </td>
		    </tr>
		</table>
	</fieldset>

	<?php
		echo $default2->subform(
		    array(
		        'Pe.Bilanparcours66.textbilanparcours',
		        'Pe.Bilanparcours66.observbenef'
		    ),
		    array(
		        'options' => $options
		    )
		);
	?>
	<fieldset id="Precoreorient">
		<legend>Préconisation de réorientation</legend>
		<?php
			echo $default->subform(
				array(
					'Pe.Saisineepbilanparcours66.typeorient_id' => array('domain'=>'bilanparcours66'),
					'Pe.Saisineepbilanparcours66.structurereferente_id' => array('domain'=>'bilanparcours66')
				),
				array(
					'options' => $options
				)
			);
		?>
	</fieldset>
	<?php
		echo $default2->subform(
		    array(
		        'Pe.Bilanparcours66.datebilan' => array( 'dateFormat' => 'DMY', 'maxYear' => date('Y') + 2, 'minYear' => date('Y') - 2, 'empty' => false )
		    ),
		    array(
		        'options' => $options
		    )
		);
	?>
    <div class="submit">
        <?php echo $form->submit( 'Enregistrer', array( 'div' => false ) );?>
        <?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
    </div>
</fieldset>

    </div>
    <?php echo $form->end();?>
</div>
<div class="clearer"><hr /></div>

<?php echo $javascript->link( 'dependantselect.js' ); ?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		dependantSelect( 'Saisineepbilanparcours66StructurereferenteId', 'Saisineepbilanparcours66TypeorientId' );
		try { $( 'Saisineepbilanparcours66StructurereferenteId' ).onchange(); } catch(id) { }

		dependantSelect( 'Bilanparcours66ReferentId', 'Bilanparcours66StructurereferenteId' );

		dependantSelect( 'PeSaisineepbilanparcours66StructurereferenteId', 'PeSaisineepbilanparcours66TypeorientId' );
		try { $( 'PeSaisineepbilanparcours66StructurereferenteId' ).onchange(); } catch(id) { }

		dependantSelect( 'PeBilanparcours66ReferentId', 'PeBilanparcours66StructurereferenteId' );
		
        observeDisableFieldsetOnRadioValue(
            'Bilan',
            'data[Bilanparcours66][typeformulaire]',
            $( 'bilanparcourscg' ),
            'cg',
            false,
            true
        );

        observeDisableFieldsetOnRadioValue(
            'Bilan',
            'data[Bilanparcours66][typeformulaire]',
            $( 'bilanparcourspe' ),
            'pe',
            false,
            true
        );

		$( 'Bilanparcours66DdreconductoncontratYear' ).observe( 'change', function(event) {
			checkDatesToRefresh();
		} );
		$( 'Bilanparcours66DdreconductoncontratMonth' ).observe( 'change', function(event) {
			checkDatesToRefresh();
		} );
		$( 'Bilanparcours66DdreconductoncontratDay' ).observe( 'change', function(event) {
			checkDatesToRefresh();
		} );
		$( 'Bilanparcours66DureeEngag' ).observe( 'change', function(event) {
			checkDatesToRefresh();
		} );

		// Javascript pour les aides liées à l'APRE
		['traitement', 'parcours', 'audition' ].each( function( proposition ) {
		    observeDisableFieldsetOnRadioValue(
		        'Bilan',
		        'data[Bilanparcours66][proposition]',
		        $( proposition ),
		        proposition,
		        false,
		        true
		    );
		} );

		// Partie en cas de changement ou non du référent
		/*observeDisableFieldsetOnRadioValue(
		    'Bilan',
		    'data[Bilanparcours66][changementrefsansep]',
		    $( 'NvReferent' ),
		    'O',
		    false,
		    true
		);*/

		observeDisableFieldsetOnRadioValue(
		    'Bilan',
		    'data[Bilanparcours66][changementrefsansep]',
		    $( 'Contratreconduit' ),
		    'N',
		    false,
		    true
		);

		// Partie en cas de maintien ou  de réorientation
		observeDisableFieldsetOnRadioValue(
		    'Bilan',
		    'data[Bilanparcours66][choixparcours]',
		    $( 'Maintien' ),
		    'maintien',
		    false,
		    true
		);

		observeDisableFieldsetOnRadioValue(
		    'Bilan',
		    'data[Bilanparcours66][choixparcours]',
		    $( 'Reorientation' ),
		    'reorientation',
		    false,
		    true
		);

		observeDisableFieldsetOnRadioValue(
		    'Bilan',
		    'data[Bilanparcours66][choixparcours]',
		    $( 'Precoreorient' ),
		    'reorientation',
		    false,
		    true
		);

		/*observeDisableFieldsetOnRadioValue(
		    'Bilan',
		    'data[Bilanparcours66][changementrefparcours]',
		    $( 'NvparcoursReferent' ),
		    'O',
		    false,
		    true
		);*/

		observeDisableFieldsetOnRadioValue(
		    'Bilan',
		    'data[Bilanparcours66][changementrefparcours]',
		    $( 'TypeAccompagnement' ),
		    'N',
		    false,
		    true
		);
		
		<?php if ($this->action=='edit') { ?>
			['traitement', 'parcours', 'audition' ].each( function( proposition ) {
				$( proposition ).up().getElementsBySelector( 'input', 'select' ).each( function( elmt ) {
					$( elmt ).writeAttribute('disabled', 'disabled');
				} );
			} );
			['Bilanparcours66TypeformulaireCg', 'Bilanparcours66TypeformulairePe', 'Bilanparcours66DatebilanDay', 'Bilanparcours66DatebilanMonth', 'Bilanparcours66DatebilanYear'].each( function ( elmt ) {
				$( elmt ).writeAttribute('disabled', 'disabled');
			} );
		<?php } ?>
	
	});
    
	function checkDatesToRefresh() {
		if( ( $F( 'Bilanparcours66DdreconductoncontratMonth' ) ) && ( $F( 'Bilanparcours66DdreconductoncontratYear' ) ) && ( $F( 'Bilanparcours66DureeEngag' ) ) ) {
			var correspondances = new Array();
			<?php
				foreach( $options['Bilanparcours66']['duree_engag'] as $index => $duree ):?>correspondances[<?php echo $index;?>] = <?php echo str_replace( ' mois', '' ,$duree );?>;<?php endforeach;?>

			setDateInterval(
				'Bilanparcours66Ddreconductoncontrat',
				'Bilanparcours66Dfreconductoncontrat',
				correspondances[$F( 'Bilanparcours66DureeEngag' )],
				false
			);
		}
		
	}
</script>
