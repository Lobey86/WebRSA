<?php $this->pageTitle = 'Dossier de la personne';?>
<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout d\'une personne';
    }
    else {
        $this->pageTitle = 'Visualisation de la personne ';
        $foyer_id = $this->data['Personne']['foyer_id'];
    }
?>
<div class="with_treemenu">
    <h1><?php echo 'Visualisation des données  ';?></h1>



    <?php if( empty( $dspp ) ):?>
        <p class="notice">Cette personne ne possède pas encore de questionnaire socio-professionnel.</p>

        <?php if( $permissions->check( 'dspps', 'add' ) ):?>
            <ul class="actionMenu">
                <?php
                    echo '<li>'.$html->addLink(
                        'Ajouter un dossier',
                        array( 'controller' => 'dspps', 'action' => 'add', $personne_id )
                    ).' </li>';
                ?>
            </ul>
        <?php endif;?>

    <?php else:?>

        <?php if( $permissions->check( 'dspps', 'edit' ) ):?>
            <ul class="actionMenu">
                <?php
                    echo '<li>'.$html->editLink(
                        'Éditer un dossier',
                        array( 'controller' => 'dspps', 'action' => 'edit', $personne_id )
                    ).' </li>';
                ?>
            </ul>
        <?php endif;?>

<div id="ficheDspp">
            <h2>Généralités DSPP</h2>

<table>
        <tbody>
           <!-- <tr class="even">
                <th><?php __( 'lib_service' );?></th>
                <td><?php /*echo ( $typeservices['Serviceinstructeur']['lib_service'] );*/ ?></td>
            </tr> -->
            <tr class="odd">
                <th ><?php __( 'drorsarmiant' );?></th>
                <td><?php echo (isset( $drorsarmiant[$dspp['Dspp']['drorsarmiant']] ) ? $drorsarmiant[$dspp['Dspp']['drorsarmiant']] : null );?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'drorsarmianta2' );?></th>
                <td><?php echo ( isset( $drorsarmianta2[$dspp['Dspp']['drorsarmianta2']] ) ? $drorsarmianta2[$dspp['Dspp']['drorsarmianta2']] : null );?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'couvsoc' );?></th>
                <td><?php echo ( isset( $couvsoc[$dspp['Dspp']['couvsoc']] ) ? $couvsoc[$dspp['Dspp']['couvsoc']] : null );?></td>
            </tr>
        </tbody>
</table>
            <h2>Situation sociale</h2>
<table>
        <tbody>

            <tr class="even">
                <th><?php __( 'elopersdifdisp' );?></th>
                <td><?php echo ( isset( $elopersdifdisp[$dspp['Dspp']['elopersdifdisp']] ) ? $elopersdifdisp[$dspp['Dspp']['elopersdifdisp']] : null );?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'obstemploidifdisp' );?></th>
                <td><?php echo ( isset( $obstemploidifdisp[$dspp['Dspp']['obstemploidifdisp']] ) ? $obstemploidifdisp[$dspp['Dspp']['obstemploidifdisp']] : null );?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'soutdemarsoc' );?></th>
                <td><?php echo ( isset( $soutdemarsoc[$dspp['Dspp']['soutdemarsoc']] ) ? $soutdemarsoc[$dspp['Dspp']['soutdemarsoc']] : null );?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'libcooraccosocindi' );?></th>
                <td><?php echo $dspp['Dspp']['libcooraccosocindi'];?></td>
            </tr>
        </tbody>
</table>
            <h2>Difficultés sociales</h2>
<table>
        <tbody>
            <tr class="even">
                <th><?php __( 'difsoc');?></th>
                <td>
                    <?php if( !empty( $dspp['Difsoc'] ) ):?>
                        <ul>
                            <?php foreach( $dspp['Difsoc'] as $difsoc ):?>
                                <li><?php echo h( $difsoc['name'] );?></li>
                            <?php endforeach;?>
                        </ul>
                    <?php endif;?>
                </td>
            </tr>
            <tr class="odd">
                <th><?php __( 'libautrdifsoc' );?></th>
                <td><?php echo $dspp['Dspp']['libautrdifsoc'];?></td>
            </tr>
        </tbody>
</table>
            <h2>Accompagnement individuel</h2>
<table>
        <tbody>
            <tr class="even">
                <th><?php __( 'nataccosocindi' );?></th>
                    <td>
                        <?php if( !empty( $dspp['Nataccosocindi'] ) ):?>
                            <ul>
                                <?php foreach( $dspp['Nataccosocindi'] as $nataccosocindi ):?>
                                    <li><?php echo h( $nataccosocindi['name'] );?></li>
                                <?php endforeach;?>
                            </ul>
                        <?php endif;?>
                    </td>
            </tr>
            <tr class="odd">
                <th><?php __( 'libautraccosocindi' );?></th>
                <td><?php echo $dspp['Dspp']['libautraccosocindi'];?></td>
            </tr>
        </tbody>
</table>
            <h2>Difficultés de disponibilité</h2>
<table>
        <tbody>
            <tr class="even">
                <th><?php __( 'difdisp' );?></th>
                <td>
                    <?php if( !empty( $dspp['Difdisp'] ) ):?>
                        <ul>
                            <?php foreach( $dspp['Difdisp'] as $difdisp ):?>
                                <li><?php echo h( $difdisp['name'] );?></li>
                            <?php endforeach;?>
                        </ul>
                    <?php endif;?>
                </td>
            </tr>
        </tbody>
</table>
            <h2>Niveau d'étude</h2>
<table>
        <tbody>
            <tr class="odd">
                <th><?php __( 'annderdipobt' );?></th>
                <td><?php echo date_short( $dspp['Dspp']['annderdipobt'] );?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'rappemploiquali' );?></th>
                <td><?php echo ( $dspp['Dspp']['rappemploiquali'] ? 'Oui' : 'Non' );?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'rappemploiform' );?></th>
                <td><?php echo ( $dspp['Dspp']['rappemploiform'] ? 'Oui' : 'Non' );?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'libautrqualipro' );?></th>
                <td><?php echo $dspp['Dspp']['libautrqualipro'];?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'permicondub' );?></th>
                <td><?php echo ( $dspp['Dspp']['permicondub'] ? 'Oui' : 'Non' );?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'libautrpermicondu' );?></th>
                <td><?php echo $dspp['Dspp']['libautrpermicondu'];?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'libcompeextrapro' );?></th>
                <td><?php echo $dspp['Dspp']['libcompeextrapro'];?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'nivetu' );?></th>
                <td>
                    <?php if( !empty( $dspp['Nivetu'] ) ):?>
                        <ul>
                            <?php foreach( $dspp['Nivetu'] as $nivetu ):?>
                                <li><?php echo h( $nivetu['name'] );?></li>
                            <?php endforeach;?>
                        </ul>
                    <?php endif;?>
                </td>
            </tr>
            <tr class="odd">
                <th><?php __( 'diplomes' );?></th>
                <td><?php echo $dspp['Dspp']['diplomes'];?></td>
            </tr>
        </tbody>
</table>
            <h2>Situation professionelle</h2>
<table>
        <tbody>
            <tr class="odd">
                <th><?php __( 'accoemploi' );?></th>
                <td>
                    <?php if( !empty( $dspp['Accoemploi'] ) ):?>
                        <ul>
                            <?php foreach( $dspp['Accoemploi'] as $accoemploi ):?>
                                <li><?php echo h( $accoemploi['name'] );?></li>
                            <?php endforeach;?>
                        </ul>
                    <?php endif;?>
                </td>
            </tr>
            <tr class="even">
                <th><?php __( 'libcooraccoemploi' );?></th>
                <td><?php echo $dspp['Dspp']['libcooraccoemploi'];?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'hispro' );?></th>
                <td><?php echo isset( $hispro[$dspp['Dspp']['hispro']] ) ? $hispro[$dspp['Dspp']['hispro']] : null ;?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'libderact' );?></th>
                <td><?php echo $dspp['Dspp']['libderact'];?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'libsecactderact' );?></th>
                <td><?php echo $dspp['Dspp']['libsecactderact'];?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'dfderact' );?></th>
                <td><?php echo date_short( $dspp['Dspp']['dfderact'] );?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'domideract' );?></th>
                <td><?php echo ( isset( $domideract[$dspp['Dspp']['domideract']] ) ? $domideract[$dspp['Dspp']['domideract']] : null ) ;?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'libactdomi' );?></th>
                <td><?php echo $dspp['Dspp']['libactdomi'];?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'libsecactdomi' );?></th>
                <td><?php echo $dspp['Dspp']['libsecactdomi'];?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'duractdomi' );?></th>
                <td><?php echo isset( $duractdomi[$dspp['Dspp']['duractdomi']] ) ? $duractdomi[$dspp['Dspp']['duractdomi']] : null ;?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'libemploirech' );?></th>
                <td><?php echo $dspp['Dspp']['libemploirech'];?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'libsecactrech' );?></th>
                <td><?php echo $dspp['Dspp']['libsecactrech'];?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'creareprisentrrech' );?></th>
                <td><?php echo ( isset( $creareprisentrrech[$dspp['Dspp']['creareprisentrrech']] ) ? $creareprisentrrech[$dspp['Dspp']['creareprisentrrech']] : null );?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'moyloco' );?></th>
                <td><?php echo ( $dspp['Dspp']['moyloco'] ? 'Oui' : 'Non' );?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'persisogrorechemploi' );?></th>
                <td><?php echo ( $dspp['Dspp']['persisogrorechemploi'] ? 'Oui' : 'Non' );?></td>
            </tr>
        </tbody>
</table>
            <h2>Mobilité</h2>
<table>
        <tbody>
            <tr class="even">
                <th><?php __( 'natmob' );?></th>
                <td>
                    <?php if( !empty( $dspp['Natmob'] ) ):?>
                        <ul>
                            <?php foreach( $dspp['Natmob'] as $natmob ):?>
                                <li><?php echo h( $natmob['name'] );?></li>
                            <?php endforeach;?>
                        </ul>
                    <?php endif;?>
                </td>
           </tr>
        </tbody>
    </table>
</div>
    <?php endif;?>
</div>
<div class="clearer"><hr /></div>
