<?php
/* SVN FILE: $Id: bootstrap.php 7945 2008-12-19 02:16:01Z gwoo $ */
/**
 * Short description for file.
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @version       $Revision: 7945 $
 * @modifiedby    $LastChangedBy: gwoo $
 * @lastmodified  $Date: 2008-12-18 18:16:01 -0800 (Thu, 18 Dec 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 *
 * This file is loaded automatically by the app/webroot/index.php file after the core bootstrap.php is loaded
 * This is an application wide file to load any function that is not used within a class define.
 * You can also use this to include or require any files in your application.
 *
 */
/**
 * The settings below can be used to set additional paths to models, views and controllers.
 * This is related to Ticket #470 (https://trac.cakephp.org/ticket/470)
 *
 * $modelPaths = array('full path to models', 'second full path to models', 'etc...');
 * $viewPaths = array('this path to views', 'second full path to views', 'etc...');
 * $controllerPaths = array('this path to controllers', 'second full path to controllers', 'etc...');
 *
 */

    define( 'REQUIRED_MARK', '<abbr class="required" title="Champ obligatoire">*</abbr>' );

    // Messages en français
    App::import( 'l10n' );
    $this->L10n = new L10n();
    $this->L10n->get( 'fre' );

    // Dates, nombres, ... en français
    $locales = array( 'fr_FR', 'fr_FR@euro', 'fr', 'fr_FR.UTF8' );
    setlocale( LC_ALL, $locales );

    function valid_int( $value ) {
        return !( empty( $value ) || !is_numeric( $value ) || !( (int)$value == $value ) );
    }

    function date_short( $sqldate ) {
        if( !empty( $sqldate ) ) {
            return strftime( '%d/%m/%Y', strtotime( $sqldate ) );
        }
    }

    function required( $label ) {
        return h( $label ).' '.REQUIRED_MARK;
    }

    function cake_version() {
        $versionData = explode( "\n", file_get_contents( ROOT.DS.'cake'.DS.'VERSION.txt' ) );
        $version = explode( '.', $versionData[ count( $versionData) - 1] );
        return implode( '.', $version );
    }

//     foreach( array( 'cg23', 'cg54', 'cg58', 'cg66', 'cg93' ) as $i => $cg ) {
//         echo "INSERT INTO users VALUES (
//     ".( $i + 1 ).",
//     '".$cg."',
//     '".sha1( 'AYhG93b0qyJfIxfs2guVoUubWwvniR2G0FgaC9mi'.$cg )."'
// );\n";
//     }
//EOF
?>