<?php $this->pageTitle = 'Visualisation des suivis d\'instruction';?>

<?php echo $this->element( 'dossier_menu', array( 'id' => $dossier_id ) );?>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <div id="ficheInfoFina">
        <table>
            <tbody>
                <tr class="even">
                    <th ><?php __d( 'suiviinstruction', 'Suiviinstruction.suiirsa' );?></th>
                    <td><?php echo  h( $suiirsa[$suiviinstruction['Suiviinstruction']['suiirsa']] );?></td>
                </tr>
                <tr class="odd">
                    <th ><?php __d( 'suiviinstruction', 'Suiviinstruction.date_etat_instruction' );?></th>
                    <td><?php echo (date_short( $suiviinstruction['Suiviinstruction']['date_etat_instruction'] ) );?></td>
                </tr>
                <tr class="even">
                    <th ><?php __d( 'suiviinstruction', 'Suiviinstruction.nomins' );?></th>
                    <td><?php echo ($suiviinstruction['Suiviinstruction']['nomins']);?></td>
                </tr>
                <tr class="odd">
                    <th ><?php __d( 'suiviinstruction', 'Suiviinstruction.prenomins' );?></th>
                    <td><?php echo ($suiviinstruction['Suiviinstruction']['prenomins']);?></td>
                </tr>
                <tr class="even">
                    <th ><?php __d( 'suiviinstruction', 'Suiviinstruction.numdepins' );?></th>
                    <td><?php echo ($suiviinstruction['Suiviinstruction']['numdepins']);?></td>
                </tr>
                <tr class="odd">
                    <th ><?php __d( 'suiviinstruction', 'Suiviinstruction.typeserins' );?></th>
                    <td><?php echo ( isset( $typeserins[$suiviinstruction['Suiviinstruction']['typeserins']] ) ? $typeserins[$suiviinstruction['Suiviinstruction']['typeserins']] : null );?></td>
                </tr>
                <tr class="even">
                    <th ><?php __d( 'suiviinstruction', 'Suiviinstruction.numcomins' );?></th>
                    <td><?php echo ($suiviinstruction['Suiviinstruction']['numcomins']);?></td>
                </tr>
                <tr class="odd">
                    <th ><?php __( 'numagrins' );?></th>
                    <td><?php echo ($suiviinstruction['Suiviinstruction']['numagrins']);?></td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
<div class="clearer"><hr /></div>