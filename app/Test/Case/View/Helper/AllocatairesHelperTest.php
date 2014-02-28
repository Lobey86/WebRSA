<?php
	/**
	 * Code source de la classe AllocatairesHelperTest.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Case.View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses('Controller', 'Controller');
	App::uses( 'View', 'View' );
	App::uses( 'AppHelper', 'View/Helper' );
	App::uses( 'AllocatairesHelper', 'View/Helper' );

	require_once dirname( __FILE__ ). DS.'..'. DS.'..'.DS.'cake_test_select_options.php';

	/**
	 * Classe AllocatairesHelperTest.
	 *
	 * @package app.Test.Case.Model.Behavior
	 */
	class AllocatairesHelperTest extends CakeTestCase
	{
		/**
		 * Fixtures utilisés.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'app.Adresse',
			'app.Adressefoyer',
			'app.Calculdroitrsa',
			'app.Detailcalculdroitrsa',
			'app.Detaildroitrsa',
			'app.Dossier',
			'app.Foyer',
			'app.Personne',
			'app.PersonneReferent',
			'app.Prestation',
			'app.Referent',
			'app.Sitecov58',
			'app.Situationdossierrsa',
			'app.Structurereferente',
		);

		/**
		 * Préparation du test.
		 */
		public function setUp() {
			parent::setUp();
			$controller = null;
			$this->View = new View( $controller );
			$this->Allocataires = new AllocatairesHelper( $this->View );
		}

		/**
		 * Nettoyage postérieur au test.
		 */
		public function tearDown() {
			parent::tearDown();
			unset( $this->View, $this->Allocataires );
		}

		/**
		 * Test de la méthode AllocatairesHelper::blocDossier()
		 */
		public function testBlocDossier() {
			$timestampFrom = strtotime( '-1 week' );
			$timestampTo = strtotime( 'now' );

			$thisYearFrom = date( 'Y', $timestampFrom );
			$thisYearTo = date( 'Y', $timestampTo );
			$yearsFrom = CakeTestSelectOptions::years( $thisYearFrom, $thisYearFrom - 120, $thisYearFrom );
			$yearsTo = CakeTestSelectOptions::years( $thisYearTo + 5, $thisYearTo - 120, $thisYearTo );

			$thisMonthFrom = date( 'm', $timestampFrom );
			$thisMonthTo = date( 'm', $timestampTo );
			$monthsFrom = CakeTestSelectOptions::months( $thisMonthFrom );
			$monthsTo = CakeTestSelectOptions::months( $thisMonthTo );

			$thisDayFrom = date( 'd', $timestampFrom );
			$thisDayTo = date( 'd', $timestampTo );
			$daysFrom = CakeTestSelectOptions::days( $thisDayFrom );
			$daysTo = CakeTestSelectOptions::days( $thisDayTo );

			// -----------------------------------------------------------------

			$result = $this->Allocataires->blocDossier( array() );
			$expected = '<fieldset><legend>Recherche par dossier</legend><div class="input text required"><label for="SearchDossierNumdemrsa">Numéro de dossier RSA</label><input name="data[Search][Dossier][numdemrsa]" maxlength="11" type="text" id="SearchDossierNumdemrsa"/></div><div class="input text"><label for="SearchDossierMatricule">Numéro CAF</label><input name="data[Search][Dossier][matricule]" maxlength="15" type="text" id="SearchDossierMatricule"/></div><script type="text/javascript">
//<![CDATA[
document.observe( \'dom:loaded\', function() { observeDisableFieldsetOnCheckbox( \'SearchDossierDtdemrsa\', $( \'SearchDossierDtdemrsa_from_to\' ), false ); } );
//]]>
</script><div class="input checkbox"><input type="hidden" name="data[Search][Dossier][dtdemrsa]" id="SearchDossierDtdemrsa_" value="0"/><input type="checkbox" name="data[Search][Dossier][dtdemrsa]"  value="1" id="SearchDossierDtdemrsa"/><label for="SearchDossierDtdemrsa">Filtrer par date de demande RSA</label></div><fieldset id="SearchDossierDtdemrsa_from_to"><legend>Date de demande RSA</legend><div class="input date"><label for="SearchDossierDtdemrsaFromDay">Du (inclus)</label><select name="data[Search][Dossier][dtdemrsa_from][day]" id="SearchDossierDtdemrsaFromDay">
'.$daysFrom.'
</select>-<select name="data[Search][Dossier][dtdemrsa_from][month]" id="SearchDossierDtdemrsaFromMonth">
'.$monthsFrom.'
</select>-<select name="data[Search][Dossier][dtdemrsa_from][year]" id="SearchDossierDtdemrsaFromYear">
'.$yearsFrom.'
</select></div><div class="input date"><label for="SearchDossierDtdemrsaToDay">Au (inclus)</label><select name="data[Search][Dossier][dtdemrsa_to][day]" id="SearchDossierDtdemrsaToDay">
'.$daysTo.'
</select>-<select name="data[Search][Dossier][dtdemrsa_to][month]" id="SearchDossierDtdemrsaToMonth">
'.$monthsTo.'
</select>-<select name="data[Search][Dossier][dtdemrsa_to][year]" id="SearchDossierDtdemrsaToYear">
'.$yearsTo.'
</select></div></fieldset><div class="input checkbox"><input type="hidden" name="data[Search][Dossier][dernier]" id="SearchDossierDernier_" value="0"/><input type="checkbox" name="data[Search][Dossier][dernier]"  value="1" id="SearchDossierDernier"/><label for="SearchDossierDernier">Uniquement la dernière demande RSA pour un même allocataire</label></div></fieldset>';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode AllocatairesHelper::blocDossier() sans fieldset.
		 */
		public function testBlocDossierNoFieldset() {
			$timestampFrom = strtotime( '-1 week' );
			$timestampTo = strtotime( 'now' );

			$thisYearFrom = date( 'Y', $timestampFrom );
			$thisYearTo = date( 'Y', $timestampTo );
			$yearsFrom = CakeTestSelectOptions::years( $thisYearFrom, $thisYearFrom - 120, $thisYearFrom );
			$yearsTo = CakeTestSelectOptions::years( $thisYearTo + 5, $thisYearTo - 120, $thisYearTo );

			$thisMonthFrom = date( 'm', $timestampFrom );
			$thisMonthTo = date( 'm', $timestampTo );
			$monthsFrom = CakeTestSelectOptions::months( $thisMonthFrom );
			$monthsTo = CakeTestSelectOptions::months( $thisMonthTo );

			$thisDayFrom = date( 'd', $timestampFrom );
			$thisDayTo = date( 'd', $timestampTo );
			$daysFrom = CakeTestSelectOptions::days( $thisDayFrom );
			$daysTo = CakeTestSelectOptions::days( $thisDayTo );

			// -----------------------------------------------------------------

			$result = $this->Allocataires->blocDossier( array( 'fieldset' => false ) );
			$expected = '<div class="input text required"><label for="SearchDossierNumdemrsa">Numéro de dossier RSA</label><input name="data[Search][Dossier][numdemrsa]" maxlength="11" type="text" id="SearchDossierNumdemrsa"/></div><div class="input text"><label for="SearchDossierMatricule">Numéro CAF</label><input name="data[Search][Dossier][matricule]" maxlength="15" type="text" id="SearchDossierMatricule"/></div><script type="text/javascript">
//<![CDATA[
document.observe( \'dom:loaded\', function() { observeDisableFieldsetOnCheckbox( \'SearchDossierDtdemrsa\', $( \'SearchDossierDtdemrsa_from_to\' ), false ); } );
//]]>
</script><div class="input checkbox"><input type="hidden" name="data[Search][Dossier][dtdemrsa]" id="SearchDossierDtdemrsa_" value="0"/><input type="checkbox" name="data[Search][Dossier][dtdemrsa]"  value="1" id="SearchDossierDtdemrsa"/><label for="SearchDossierDtdemrsa">Filtrer par date de demande RSA</label></div><fieldset id="SearchDossierDtdemrsa_from_to"><legend>Date de demande RSA</legend><div class="input date"><label for="SearchDossierDtdemrsaFromDay">Du (inclus)</label><select name="data[Search][Dossier][dtdemrsa_from][day]" id="SearchDossierDtdemrsaFromDay">
'.$daysFrom.'
</select>-<select name="data[Search][Dossier][dtdemrsa_from][month]" id="SearchDossierDtdemrsaFromMonth">
'.$monthsFrom.'
</select>-<select name="data[Search][Dossier][dtdemrsa_from][year]" id="SearchDossierDtdemrsaFromYear">
'.$yearsFrom.'
</select></div><div class="input date"><label for="SearchDossierDtdemrsaToDay">Au (inclus)</label><select name="data[Search][Dossier][dtdemrsa_to][day]" id="SearchDossierDtdemrsaToDay">
'.$daysTo.'
</select>-<select name="data[Search][Dossier][dtdemrsa_to][month]" id="SearchDossierDtdemrsaToMonth">
'.$monthsTo.'
</select>-<select name="data[Search][Dossier][dtdemrsa_to][year]" id="SearchDossierDtdemrsaToYear">
'.$yearsTo.'
</select></div></fieldset><div class="input checkbox"><input type="hidden" name="data[Search][Dossier][dernier]" id="SearchDossierDernier_" value="0"/><input type="checkbox" name="data[Search][Dossier][dernier]"  value="1" id="SearchDossierDernier"/><label for="SearchDossierDernier">Uniquement la dernière demande RSA pour un même allocataire</label></div>';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode AllocatairesHelper::blocDossier() avec des options
		 * pour l'etatdosrsa et la natpf.
		 */
		public function testBlocDossierOptions() {
			$timestampFrom = strtotime( '-1 week' );
			$timestampTo = strtotime( 'now' );

			$thisYearFrom = date( 'Y', $timestampFrom );
			$thisYearTo = date( 'Y', $timestampTo );
			$yearsFrom = CakeTestSelectOptions::years( $thisYearFrom, $thisYearFrom - 120, $thisYearFrom );
			$yearsTo = CakeTestSelectOptions::years( $thisYearTo + 5, $thisYearTo - 120, $thisYearTo );

			$thisMonthFrom = date( 'm', $timestampFrom );
			$thisMonthTo = date( 'm', $timestampTo );
			$monthsFrom = CakeTestSelectOptions::months( $thisMonthFrom );
			$monthsTo = CakeTestSelectOptions::months( $thisMonthTo );

			$thisDayFrom = date( 'd', $timestampFrom );
			$thisDayTo = date( 'd', $timestampTo );
			$daysFrom = CakeTestSelectOptions::days( $thisDayFrom );
			$daysTo = CakeTestSelectOptions::days( $thisDayTo );

			// -----------------------------------------------------------------

			$params = array(
				'options' => array(
					'Detailcalculdroitrsa' => array(
						'natpf' => array(
							'RSD' => 'RSA Socle (Financement sur fonds Conseil général)',
							'RSI' => 'RSA Socle majoré (Financement sur fonds Conseil général)',
						)
					),
					'Situationdossierrsa' => array(
						'etatdosrsa' => array(
							'Z' => 'Non défini',
							0 => 'Nouvelle demande en attente de décision CG pour ouverture du droit'
						),
					),
				)
			);

			$result = $this->Allocataires->blocDossier( $params );
			$expected = '<fieldset><legend>Recherche par dossier</legend><div class="input text required"><label for="SearchDossierNumdemrsa">Numéro de dossier RSA</label><input name="data[Search][Dossier][numdemrsa]" maxlength="11" type="text" id="SearchDossierNumdemrsa"/></div><div class="input text"><label for="SearchDossierMatricule">Numéro CAF</label><input name="data[Search][Dossier][matricule]" maxlength="15" type="text" id="SearchDossierMatricule"/></div><script type="text/javascript">
//<![CDATA[
document.observe( \'dom:loaded\', function() { observeDisableFieldsetOnCheckbox( \'SearchDossierDtdemrsa\', $( \'SearchDossierDtdemrsa_from_to\' ), false ); } );
//]]>
</script><div class="input checkbox"><input type="hidden" name="data[Search][Dossier][dtdemrsa]" id="SearchDossierDtdemrsa_" value="0"/><input type="checkbox" name="data[Search][Dossier][dtdemrsa]"  value="1" id="SearchDossierDtdemrsa"/><label for="SearchDossierDtdemrsa">Filtrer par date de demande RSA</label></div><fieldset id="SearchDossierDtdemrsa_from_to"><legend>Date de demande RSA</legend><div class="input date"><label for="SearchDossierDtdemrsaFromDay">Du (inclus)</label><select name="data[Search][Dossier][dtdemrsa_from][day]" id="SearchDossierDtdemrsaFromDay">
'.$daysFrom.'
</select>-<select name="data[Search][Dossier][dtdemrsa_from][month]" id="SearchDossierDtdemrsaFromMonth">
'.$monthsFrom.'
</select>-<select name="data[Search][Dossier][dtdemrsa_from][year]" id="SearchDossierDtdemrsaFromYear">
'.$yearsFrom.'
</select></div><div class="input date"><label for="SearchDossierDtdemrsaToDay">Au (inclus)</label><select name="data[Search][Dossier][dtdemrsa_to][day]" id="SearchDossierDtdemrsaToDay">
'.$daysTo.'
</select>-<select name="data[Search][Dossier][dtdemrsa_to][month]" id="SearchDossierDtdemrsaToMonth">
'.$monthsTo.'
</select>-<select name="data[Search][Dossier][dtdemrsa_to][year]" id="SearchDossierDtdemrsaToYear">
'.$yearsTo.'
</select></div></fieldset><div class="input checkbox"><input type="hidden" name="data[Search][Situationdossierrsa][etatdosrsa_choice]" id="SearchSituationdossierrsaEtatdosrsaChoice_" value="0"/><input type="checkbox" name="data[Search][Situationdossierrsa][etatdosrsa_choice]"  value="1" id="SearchSituationdossierrsaEtatdosrsaChoice"/><label for="SearchSituationdossierrsaEtatdosrsaChoice">Filtrer par état du dossier</label></div><fieldset id="SearchSituationdossierrsaEtatdosrsaFieldset"><legend>États du dossier</legend><div class="input select required"><input type="hidden" name="data[Search][Situationdossierrsa][etatdosrsa]" value="" id="SearchSituationdossierrsaEtatdosrsa"/>

<div class="checkbox"><input type="checkbox" name="data[Search][Situationdossierrsa][etatdosrsa][]" value="Z" id="SearchSituationdossierrsaEtatdosrsaZ" /><label for="SearchSituationdossierrsaEtatdosrsaZ">Non défini</label></div>
<div class="checkbox"><input type="checkbox" name="data[Search][Situationdossierrsa][etatdosrsa][]" value="0" id="SearchSituationdossierrsaEtatdosrsa0" /><label for="SearchSituationdossierrsaEtatdosrsa0">Nouvelle demande en attente de décision CG pour ouverture du droit</label></div>
</div></fieldset><script type="text/javascript">
//<![CDATA[
document.observe( \'dom:loaded\', function() { observeDisableFieldsetOnCheckbox( \'SearchSituationdossierrsaEtatdosrsaChoice\', $( \'SearchSituationdossierrsaEtatdosrsaFieldset\' ), false ); } );
//]]>
</script><div class="input checkbox"><input type="hidden" name="data[Search][Detailcalculdroitrsa][natpf_choice]" id="SearchDetailcalculdroitrsaNatpfChoice_" value="0"/><input type="checkbox" name="data[Search][Detailcalculdroitrsa][natpf_choice]"  value="1" id="SearchDetailcalculdroitrsaNatpfChoice"/><label for="SearchDetailcalculdroitrsaNatpfChoice">Filtrer par nature de prestation</label></div><fieldset id="SearchDetailcalculdroitrsaNatpfFieldset"><legend>Natures de prestation</legend><div class="input select"><input type="hidden" name="data[Search][Detailcalculdroitrsa][natpf]" value="" id="SearchDetailcalculdroitrsaNatpf"/>

<div class="checkbox"><input type="checkbox" name="data[Search][Detailcalculdroitrsa][natpf][]" value="RSD" id="SearchDetailcalculdroitrsaNatpfRSD" /><label for="SearchDetailcalculdroitrsaNatpfRSD">RSA Socle (Financement sur fonds Conseil général)</label></div>
<div class="checkbox"><input type="checkbox" name="data[Search][Detailcalculdroitrsa][natpf][]" value="RSI" id="SearchDetailcalculdroitrsaNatpfRSI" /><label for="SearchDetailcalculdroitrsaNatpfRSI">RSA Socle majoré (Financement sur fonds Conseil général)</label></div>
</div></fieldset><script type="text/javascript">
//<![CDATA[
document.observe( \'dom:loaded\', function() { observeDisableFieldsetOnCheckbox( \'SearchDetailcalculdroitrsaNatpfChoice\', $( \'SearchDetailcalculdroitrsaNatpfFieldset\' ), false ); } );
//]]>
</script><div class="input checkbox"><input type="hidden" name="data[Search][Dossier][dernier]" id="SearchDossierDernier_" value="0"/><input type="checkbox" name="data[Search][Dossier][dernier]"  value="1" id="SearchDossierDernier"/><label for="SearchDossierDernier">Uniquement la dernière demande RSA pour un même allocataire</label></div></fieldset>';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode AllocatairesHelper::blocDossier() en ne faisant pas
		 * la plage de date de dtdemrsa.
		 */
		public function testBlocDossierSkip() {
			$params = array(
				'options' => array(
					'Situationdossierrsa' => array(
						'etatdosrsa' => array( 'Z' => 'Non défini' )
					)
				),
				'skip' => array(
					'Search.Dossier.dtdemrsa'
				)
			);

			$result = $this->Allocataires->blocDossier( $params );
			$expected = '<fieldset><legend>Recherche par dossier</legend><div class="input text required"><label for="SearchDossierNumdemrsa">Numéro de dossier RSA</label><input name="data[Search][Dossier][numdemrsa]" maxlength="11" type="text" id="SearchDossierNumdemrsa"/></div><div class="input text"><label for="SearchDossierMatricule">Numéro CAF</label><input name="data[Search][Dossier][matricule]" maxlength="15" type="text" id="SearchDossierMatricule"/></div><div class="input checkbox"><input type="hidden" name="data[Search][Situationdossierrsa][etatdosrsa_choice]" id="SearchSituationdossierrsaEtatdosrsaChoice_" value="0"/><input type="checkbox" name="data[Search][Situationdossierrsa][etatdosrsa_choice]"  value="1" id="SearchSituationdossierrsaEtatdosrsaChoice"/><label for="SearchSituationdossierrsaEtatdosrsaChoice">Filtrer par état du dossier</label></div><fieldset id="SearchSituationdossierrsaEtatdosrsaFieldset"><legend>États du dossier</legend><div class="input select required"><input type="hidden" name="data[Search][Situationdossierrsa][etatdosrsa]" value="" id="SearchSituationdossierrsaEtatdosrsa"/>

<div class="checkbox"><input type="checkbox" name="data[Search][Situationdossierrsa][etatdosrsa][]" value="Z" id="SearchSituationdossierrsaEtatdosrsaZ" /><label for="SearchSituationdossierrsaEtatdosrsaZ">Non défini</label></div>
</div></fieldset><script type="text/javascript">
//<![CDATA[
document.observe( \'dom:loaded\', function() { observeDisableFieldsetOnCheckbox( \'SearchSituationdossierrsaEtatdosrsaChoice\', $( \'SearchSituationdossierrsaEtatdosrsaFieldset\' ), false ); } );
//]]>
</script><div class="input checkbox"><input type="hidden" name="data[Search][Dossier][dernier]" id="SearchDossierDernier_" value="0"/><input type="checkbox" name="data[Search][Dossier][dernier]"  value="1" id="SearchDossierDernier"/><label for="SearchDossierDernier">Uniquement la dernière demande RSA pour un même allocataire</label></div></fieldset>';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode AllocatairesHelper::blocDossier() en changeant le
		 * préfixe.
		 *
		 * @todo: les traductions ne sont pas faites dans ce cas-là...
		 */
		/*public function testBlocDossierPrefix() {
			$params = array(
				'options' => array(
					'Situationdossierrsa' => array(
						'etatdosrsa' => array( 'Z' => 'Non défini' )
					)
				),
				'skip' => array(
					'Search.Dossier.dtdemrsa'
				),
				'prefix' => 'Filter'
			);

			$result = $this->Allocataires->blocDossier( $params );
			$expected = '<fieldset><legend>Recherche par dossier</legend><div class="input text required"><label for="FilterDossierNumdemrsa">Numéro de dossier RSA</label><input name="data[Filter][Dossier][numdemrsa]" maxlength="11" type="text" id="FilterDossierNumdemrsa"/></div><div class="input text"><label for="FilterDossierMatricule">Numéro CAF</label><input name="data[Filter][Dossier][matricule]" maxlength="15" type="text" id="FilterDossierMatricule"/></div><div class="input checkbox"><input type="hidden" name="data[Filter][Situationdossierrsa][etatdosrsa_choice]" id="FilterSituationdossierrsaEtatdosrsaChoice_" value="0"/><input type="checkbox" name="data[Filter][Situationdossierrsa][etatdosrsa_choice]"  value="1" id="FilterSituationdossierrsaEtatdosrsaChoice"/><label for="FilterSituationdossierrsaEtatdosrsaChoice">Filtrer par état du dossier</label></div><fieldset id="FilterSituationdossierrsaEtatdosrsaFieldset"><legend>États du dossier</legend><div class="input select required"><input type="hidden" name="data[Filter][Situationdossierrsa][etatdosrsa]" value="" id="FilterSituationdossierrsaEtatdosrsa"/>

<div class="checkbox"><input type="checkbox" name="data[Filter][Situationdossierrsa][etatdosrsa][]" value="Z" id="FilterSituationdossierrsaEtatdosrsaZ" /><label for="FilterSituationdossierrsaEtatdosrsaZ">Non défini</label></div>
</div></fieldset><script type="text/javascript">
//<![CDATA[
document.observe( \'dom:loaded\', function() { observeDisableFieldsetOnCheckbox( \'FilterSituationdossierrsaEtatdosrsaChoice\', $( \'FilterSituationdossierrsaEtatdosrsaFieldset\' ), false ); } );
//]]>
</script><div class="input checkbox"><input type="hidden" name="data[Filter][Dossier][dernier]" id="FilterDossierDernier_" value="0"/><input type="checkbox" name="data[Filter][Dossier][dernier]"  value="1" id="FilterDossierDernier"/><label for="FilterDossierDernier">Uniquement la dernière demande RSA pour un même allocataire</label></div></fieldset>';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}*/

		/**
		 * Test de la méthode AllocatairesHelper::blocAdresse()
		 */
		public function testBlocAdresse() {
			Configure::write( 'CG.cantons', true );
			Configure::write( 'Cg.departement', 58 );

			$params = array(
				'options' => array(
					'Adresse' => array(
						'numcomptt' => array( '58000' => 'Nevers' )
					),
					'Canton' => array(
						'canton' => array( 'LA CHARITE SUR LOIRE' => 'LA CHARITE SUR LOIRE' )
					),
					'Sitecov58' => array(
						'id' => array( 1 => 'Bords de Loire' )
					),
				),
			);
			$result = $this->Allocataires->blocAdresse( $params );
			$expected = '<fieldset><legend>Recherche par adresse</legend><div class="input text required"><label for="SearchAdresseNomvoie">Nom de voie de l\'allocataire </label><input name="data[Search][Adresse][nomvoie]" type="text" id="SearchAdresseNomvoie"/></div><div class="input text required"><label for="SearchAdresseLocaadr">Commune de l\'allocataire </label><input name="data[Search][Adresse][locaadr]" type="text" id="SearchAdresseLocaadr"/></div><div class="input select required"><label for="SearchAdresseNumcomptt">Numéro de commune au sens INSEE</label><select name="data[Search][Adresse][numcomptt]" id="SearchAdresseNumcomptt">
<option value=""></option>
<option value="58000">Nevers</option>
</select></div><div class="input select required"><label for="SearchCantonCanton">Canton</label><select name="data[Search][Canton][canton]" id="SearchCantonCanton">
<option value=""></option>
<option value="LA CHARITE SUR LOIRE">LA CHARITE SUR LOIRE</option>
</select></div><div class="input select"><label for="SearchSitecov58Id">Site COV</label><select name="data[Search][Sitecov58][id]" id="SearchSitecov58Id">
<option value=""></option>
<option value="1">Bords de Loire</option>
</select></div></fieldset>';

			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}


		/**
		 * Test de la méthode AllocatairesHelper::blocAllocataire()
		 */
		/*public function testBlocAllocataire() {
			$timestamp = strtotime( 'now' );

			$thisYear = date( 'Y', $timestamp );
			$years = CakeTestSelectOptions::years( $thisYear, $thisYear - 120, $thisYear );
			$years = str_replace( ' selected="selected"', '', $years );

			$thisMonth = date( 'm', $timestamp );
			$months = CakeTestSelectOptions::months( $thisMonth );
			$months = str_replace( ' selected="selected"', '', $months );

			$thisDay = date( 'd', $timestamp );
			$days = CakeTestSelectOptions::days( $thisDay );
			$days = str_replace( ' selected="selected"', '', $days );

			// -----------------------------------------------------------------

			$params = array(
				'options' => array(
					'Calculdroitrsa' => array(
						'toppersdrodevorsa' => array(
							'NULL' => 'Non défini',
							1 => 'Oui',
							0 => 'Non'
						)
					),
					'Personne' => array(
						'sexe' => array(
							1 => 'Homme',
							2 => 'Femme'
						),
						'trancheage' => array(
							'0_24' => '- 25 ans',
							'25_34' => '25 - 34 ans',
							'35_44' => '35 - 44 ans',
							'45_54' => '45 - 54 ans',
							'55_999' => '+ 55 ans'
						),
					),
				),
			);
			$result = $this->Allocataires->blocAllocataire( $params );
			$expected = '<fieldset><legend>Recherche par allocataire</legend><div class="input date required"><label for="SearchPersonneDtnaiDay">Date de naissance</label><select name="data[Search][Personne][dtnai][day]" id="SearchPersonneDtnaiDay">
'.$days.'
</select>-<select name="data[Search][Personne][dtnai][month]" id="SearchPersonneDtnaiMonth">
'.$months.'
</select>-<select name="data[Search][Personne][dtnai][year]" id="SearchPersonneDtnaiYear">
'.$years.'
</select></div><div class="input text required"><label for="SearchPersonneNom">Nom</label><input name="data[Search][Personne][nom]" maxlength="50" type="text" id="SearchPersonneNom"/></div><div class="input text"><label for="SearchPersonneNomnai">Nom de jeune fille</label><input name="data[Search][Personne][nomnai]" maxlength="50" type="text" id="SearchPersonneNomnai"/></div><div class="input text required"><label for="SearchPersonnePrenom">Prénom</label><input name="data[Search][Personne][prenom]" maxlength="50" type="text" id="SearchPersonnePrenom"/></div><div class="input text"><label for="SearchPersonneNir">NIR</label><input name="data[Search][Personne][nir]" maxlength="15" type="text" id="SearchPersonneNir"/></div><div class="input select"><label for="SearchPersonneSexe">Sexe</label><select name="data[Search][Personne][sexe]" id="SearchPersonneSexe">
<option value=""></option>
<option value="1">Homme</option>
<option value="2">Femme</option>
</select></div><div class="input select"><label for="SearchPersonneTrancheage">Tranche d\'âge</label><select name="data[Search][Personne][trancheage]" id="SearchPersonneTrancheage">
<option value=""></option>
<option value="0_24">- 25 ans</option>
<option value="25_34">25 - 34 ans</option>
<option value="35_44">35 - 44 ans</option>
<option value="45_54">45 - 54 ans</option>
<option value="55_999">+ 55 ans</option>
</select></div><div class="input select"><label for="SearchCalculdroitrsaToppersdrodevorsa">Personne soumise à droits et devoirs ?</label><select name="data[Search][Calculdroitrsa][toppersdrodevorsa]" id="SearchCalculdroitrsaToppersdrodevorsa">
<option value=""></option>
<option value="NULL">Non défini</option>
<option value="1">Oui</option>
<option value="0">Non</option>
</select></div></fieldset>';

			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}*/
	}
?>