<?php
    class InstructionShell extends Shell
    {
        var $uses = array( 'Dossier', 'Foyer', 'Personne' );
        var $_xmlFile;
        var $_xmlParser;
//         var $_stack = array();
//         var $_cdataElements = array();
        var $_xmlString = '';

        //*********************************************************************

        function _open( $file ) {
            $this->_xmlFile = $file;
            $this->_xmlParser = xml_parser_create( '' );
            // http://fr.php.net/manual/fr/function.xml-set-element-handler.php#64064
            xml_set_element_handler( $this->_xmlParser, array( $this, 'startElement' ), array( $this, 'endElement' ) );
            xml_set_character_data_handler( $this->_xmlParser, array( $this, 'cdataElement' ) );
            xml_parser_set_option( $this->_xmlParser, XML_OPTION_TARGET_ENCODING, 'ISO-8859-1' );
            xml_parser_set_option( $this->_xmlParser, XML_OPTION_SKIP_WHITE, 1 );
            if( ! ( $this->_xmlFile = fopen( $file, "r" ) ) ) {
                die("could not open XML input");
            }
        }

        //*********************************************************************

        function processDemandeRsa( $demandeXmlString ) {
            $demandeXml = simplexml_load_string( '<?xml version="1.0" encoding="ISO-8859-1"?>'.$demandeXmlString );

            /**
                Dossier
            */
            $dossierXml = array( 'Dossier' => (array) $demandeXml->identificationrsa->demandersa );
            $dossierDb = $this->Dossier->find(
                'first',
                array(
                    'conditions' => array( 'Dossier.numdemrsa' => $dossierXml['Dossier']['numdemrsa'] ),
                    'recursive' => -1
                )
            );
            $dossier = Set::merge( $dossierDb, $dossierXml );

            $this->Dossier->create();
            assert( $this->Dossier->save( $dossier ) );

            /**
                Foyer
            */
            $foyerXml = array( 'Foyer' => (array) $demandeXml->donneesadministratives->logement );
            $foyerXml['Foyer']['dossier_rsa_id'] = $this->Dossier->id;
            $foyerDb = $this->Foyer->find(
                'first',
                array(
                    'conditions' => array( 'Foyer.dossier_rsa_id' => $this->Dossier->id ),
                    'recursive' => -1
                )
            );
            $foyer = Set::merge( $foyerDb, $foyerXml );
            $this->Foyer->create();
            assert( $this->Foyer->save( $foyer ) );

            /**
                Personnes
            */
            foreach( $demandeXml->personne as $personne ) {
                $personneXml = array(
                    'Personne' => Set::merge(
                        (array) $personne->identification,
                        (array) $personne->nationalite,
                        (array) $personne->prestations
                    )
                );
                $personneXml['Personne']['foyer_id'] = $this->Foyer->id;
                $personneDb = $this->Personne->find(
                    'first',
                    array(
                        'conditions' => array(
                        // FIXME: changement de foyer ?
                            'Personne.foyer_id' => $this->Foyer->id,
                            'Personne.nom'       => (array) $personneXml['Personne']['nom'],
                            'Personne.nomnai'    => (array) $personneXml['Personne']['nomnai'],
                            'Personne.prenom'    => (array) $personneXml['Personne']['prenom'],
                            'Personne.dtnai'     => (array) $personneXml['Personne']['dtnai'],
                            'Personne.typedtnai' => (array) $personneXml['Personne']['typedtnai'],
                            'Personne.sexe'      => (array) $personneXml['Personne']['sexe']
                        ),
                        // FIXME: NIR ?
                        'recursive' => -1
                    )
                );
                $personne = Set::merge( $personneDb, $personneXml );
                $this->Personne->create();
                assert( $this->Personne->save( $personne ) );
                // FIXME: assertion failed + pourquoi l'insertion fonctionne et pas la mise à jour ?
                // debug( $personne );
//                 debug( $this->Personne->validationErrors );
            }
        }

        //*********************************************************************

        function startElement( $xmlParser, $currentTagName, $attrs ) {
            $currentTagName = strtolower( $currentTagName );
            if( $currentTagName == 'infodemandersa' ) {
                $this->_xmlString = '';
            }
            $this->_xmlString .= '<'.$currentTagName.'>';
        }

        //-------------------------------------------------------------------------

        function cdataElement( $xmlParser, $data ) {
            $data = trim( $data );
            if( !empty( $data ) ) {
                $this->_xmlString .= $data;
            }
        }

        //-------------------------------------------------------------------------

        function endElement( $xmlParser, $currentTagName ) {
            $currentTagName = strtolower( $currentTagName );

            $this->_xmlString .= '</'.$currentTagName.'>';

            switch( $currentTagName ) {
                case 'infodemandersa':
                /**
                    Fin d'un dossier RSA
                */
                    $this->processDemandeRsa( $this->_xmlString );
                    $this->_xmlString = '';
                    break;
            }
        }

        //*********************************************************************

        function main() {
            $this_start = microtime( true );
            echo "Démarrage: ".date( 'Y-m-d H:i:s' )."\n";

            //-----------------------------------------------------------------

            assert( !empty( $this->Dispatch->args[0] ) );
            assert( file_exists( $this->Dispatch->args[0] ) );

            //-----------------------------------------------------------------

            $this->_open( $this->Dispatch->args[0] );
            $this->Dossier->begin(); // FIXME -> comment faire ?
            while( $data = fread( $this->_xmlFile, 4096 ) ) {
                if( !xml_parse( $this->_xmlParser, $data, feof($this->_xmlFile ) ) ) {
                    die( sprintf( "XML error: %s at line %d", xml_error_string( xml_get_error_code( $this->_xmlParser ) ), xml_get_current_line_number( $this->_xmlParser ) ) );
                }
            }
            $this->Dossier->commit();

            //-----------------------------------------------------------------

            xml_parser_free( $this->_xmlParser );
            echo "Terminé: ".date( 'Y-m-d H:i:s' )."\n";
            echo number_format( microtime( true ) - $this_start, 2 )."\n";
        }
    }
?>