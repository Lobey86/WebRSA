<?php
    class RefreshShell extends Shell
    {
        var $uses = array( 'Foyer' );

        function main() {
            /** ****************************************************************
            *   Démarrage du script
            *** ***************************************************************/

            $this_start = microtime( true );
            echo "Demarrage du script de rafraicissement: ".date( 'Y-m-d H:i:s' )."\n";

            $this->Foyer->begin();
            $saved = true;

            /** ****************************************************************
            *   Réparation des données du flux CAF (les rgadr ne sont pas sur deux chiffres)
            *   Si le rang est bien formé, il n'y a pas de mise à jour
            *** ***************************************************************/
            $this->hr();

            echo 'Debut de la mise a jour des rangs adresse: '.number_format( microtime( true ) - $this_start, 2 )."\n";

            $adressesFoyers = $this->Foyer->Adressefoyer->find( 'list', array( 'fields' => array( 'Adressefoyer.id', 'Adressefoyer.rgadr' ) ) );
            foreach( $adressesFoyers as $id => $rgadr ) {
                $rgadr = trim( $rgadr );
                if( strlen( $rgadr ) == 1 ) {
                    $rgadr = '0'.$rgadr;
                    $this->Foyer->Adressefoyer->create( array( 'Adressefoyer' => array( 'id' => $id, 'rgadr' => $rgadr ) ) );
                    $saved = $this->Foyer->Adressefoyer->save() && $saved;
                }
            }

            echo 'Fin de la mise a jour des rangs adresse: '.number_format( microtime( true ) - $this_start, 2 )."\n";

            /** ****************************************************************
            *   Rafraichissement de "soumis à droits et devoirs" pour la table
            *   orientsstructs
            *** ***************************************************************/
            // FIXME: ajouter une entrée dans la table orientsstructs ?

            $this->hr();
            echo 'Debut de la mise a jour des orientsstructs: '.number_format( microtime( true ) - $this_start, 2 )."\n";

            $foyers = $this->Foyer->find( 'list', array( 'fields' => array( 'Foyer.id', 'Foyer.id' ), 'order' => 'Foyer.id ASC' ) );
            foreach( $foyers as $foyer_id ) {
                $saved = $this->Foyer->refreshRessources( $foyer_id ) && $saved;
                $saved = $this->Foyer->refreshSoumisADroitsEtDevoirs( $foyer_id ) && $saved;
            }

            echo 'Fin de la mise a jour des orientsstructs: '.number_format( microtime( true ) - $this_start, 2 )."\n";

            /** ****************************************************************
            *   Fin du script
            *** ***************************************************************/

            $this->hr();

            if( $saved ) {
                $this->Foyer->commit();
                echo "Script de rafraicissement termine avec succes: ".date( 'Y-m-d H:i:s' ).'( en '.number_format( microtime( true ) - $this_start, 2 ).' secondes )'."\n";
                return 0;
            }
            else {
                $this->Foyer->rollback();
                echo "Script de rafraicissement termine avec erreurs: ".date( 'Y-m-d H:i:s' ).'( en '.number_format( microtime( true ) - $this_start, 2 ).' secondes )'."\n";
                return 1;
            }

        }
    }
?>