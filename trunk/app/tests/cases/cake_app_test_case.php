<?php

	if( !defined( 'CAKEPHP_UNIT_TEST_EXECUTION' ) ) {
		define( 'CAKEPHP_UNIT_TEST_EXECUTION', 1 );
	}

	ClassRegistry::config(array('ds' => 'test_suite'));

	class CakeAppTestCase extends CakeTestCase {

		/**
		* Tables de données à utiliser
		*/

		public $fixtures = array (
			'app.nonorientationpro58',
			'app.nonorientationpro66',
			'app.nonorientationpro93',
			'app.dossiercov58',
			'app.pieceacqmatprof',
			'app.fonctionmembreep',
			'app.motifreorient',
			'app.nvsrepreorientsr93',
			'app.saisineepreorientsr93',
			'app.regroupementep',
			'app.ep_membreep',
			'app.ep_zonegeographique',
			'app.membreep_seanceep',
			'app.membreep',
			'app.seanceep',
			//'app.commissionep',
			'app.nonrespectsanctionep93',
			'app.propoorientationcov58',
			'app.decisionnonrespectsanctionep93',
			'app.relancenonrespectsanctionep93',
			'app.dossierep',
			'app.ep',
			'app.decisionpropopdo',
			'app.decisionpdo',
			'app.pieceformpermfimo',
			'app.pieceaide66',
			'app.suspensionversement',
			'app.dossiercaf',
			'app.canton',
			'app.pieceamenaglogt',
			'app.descriptionpdo',
			'app.piececomptable66_typeaideapre66',
			'app.acccreaentr',
			'app.aro_aco',
			'app.typeaction',
			'app.identificationflux',
			'app.personne',
			'app.dsp',
			'app.action',
			'app.aro',
			'app.detailressourcemensuelle_ressourcemensuelle',
			'app.detailprojpro',
			'app.activite',
			'app.cui',
			'app.formqualif_pieceformqualif',
			'app.creancealimentaire',
			'app.orientstruct_serviceinstructeur',
			'app.propopdo',
			'app.detailnatmob_rev',
			'app.anomalie',
			'app.formpermfimo_pieceformpermfimo',
			'app.jeton',
			'app.aidedirecte',
			'app.detailconfort_rev',
			'app.comiteapre_participantcomite',
			'app.participantcomite',
			'app.creance',
			'app.detaildiflog_rev',
			'app.piecepdo',
			'app.detaildifsocpro_rev',
			'app.propopdo_situationpdo',
			'app.detaildifdisp_rev',
			'app.zonegeographique',
			'app.derogation',
			'app.budgetapre',
			'app.avispcgdroitrsa',
			'app.pieceacqmatprof',
			'app.contratinsertion',
			'app.detailmoytrans_rev',
			'app.regroupementzonegeo',
			'app.aideapre66_piececomptable66',
			'app.detailressourcemensuelle',
			'app.prestation',
			'app.infofinanciere',
			'app.detailcalculdroitrsa',
			'app.personne_referent',
			'app.apre',
			'app.pieceacccreaentr',
			'app.foyer',
			'app.apre_pieceapre',
			'app.suiviaideapretypeaide',
			'app.typeaideapre66',
			'app.ressource',
			'app.themeapre66',
			'app.actioninsertion',
			'app.detaildifsoc',
			'app.group',
			'app.referent',
			'app.tempinscription',
			'app.departement',
			'app.detaildroitrsa',
			'app.detailaccosocfam',
			'app.permisb',
			'app.aideapre66',
			'app.detaildifdisp',
			'app.detaildifsoc_rev',
			'app.locvehicinsert',
			'app.detailfreinform',
			'app.reducrsa',
			'app.propopdo_statutdecisionpdo',
			'app.avispcgpersonne',
			'app.evenement',
			'app.calculdroitrsa',
			'app.detailmoytrans',
			'app.contratinsertion_user',
			'app.comiteapre',
			'app.contactpartenaire',
			'app.typocontrat',
			'app.prestform',
			'app.detailaccosocindi',
			'app.suiviappuiorientation',
			'app.acqmatprof',
			'app.originepdo',
			'app.adresse',
			'app.suspensiondroit',
			'app.user_zonegeographique',
			'app.dossier',
			'app.paiementfoyer',
			'app.memo',
			'app.rendezvous',
			'app.infopoleemploi',
			'app.precoreorient',
			'app.permanence',
			'app.condadmin',
			'app.pieceformqualif',
			'app.formqualif',
			'app.aideagricole',
			'app.detailconfort',
			'app.actprof',
			'app.pieceaide66_typeaideapre66',
			'app.allocationsoutienfamilial',
			'app.amenaglogt',
			'app.detailfreinform_rev',
			'app.bilanparcours',
			'app.apre_comiteapre',
			'app.apre_etatliquidatif',
			'app.acccreaentr_pieceacccreaentr',
			'app.acqmatprof_pieceacqmatprof',
			'app.amenaglogt_pieceamenaglogt',
			'app.tempcessation',
			'app.structurereferente',
			'app.traitementpdo',
			'app.ressource_ressourcemensuelle',
			'app.statutrdv',
			'app.connection',
			'app.totalisationacompte',
			'app.traitementtypepdo',
			'app.regroupementzonegeo_zonegeographique',
			'app.informationeti',
			'app.actioncandidat',
			'app.suiviaideapre',
			'app.actioncandidat_personne',
			'app.structurereferente_zonegeographique',
			'app.typepdo',
			'app.orientstruct',
			'app.orientation',
			'app.situationpdo',
			'app.dsp_rev',
			'app.piecelocvehicinsert',
			'app.decisionparcours',
			'app.statutpdo',
			'app.detaildifsocpro',
			'app.rattachement',
			'app.actprof_pieceactprof',
			'app.modecontact',
			'app.serviceinstructeur',
			'app.user',
			'app.relanceapre',
			'app.fraisdeplacement66',
			'app.aideapre66_pieceaide66',
			'app.refpresta',
			'app.aco',
			'app.suiviinstruction',
			'app.statutdecisionpdo',
			'app.integrationfichierapre',
			'app.actioncandidat_partenaire',
			'app.formpermfimo',
			'app.tempradiation',
			'app.pieceactprof',
			'app.montantconsomme',
			'app.titresejour',
			'app.detailaccosocfam_rev',
			'app.situationdossierrsa',
			'app.pdf',
			'app.piecepermisb',
			'app.adressefoyer',
			'app.liberalite',
			'app.domiciliationbancaire',
			'app.detailprojpro_rev',
			'app.jetonfonction',
			'app.typenotifpdo',
			'app.infoagricole',
			'app.typeorient',
			'app.piececomptable66',
			'app.pieceapre',
			'app.propopdo_statutpdo',
			'app.controleadministratif',
			'app.contactpartenaire_partenaire',
			'app.parametrefinancier',
			'app.grossesse',
			'app.entretien',
			'app.typerdv',
			'app.parcours',
			'app.ressourcemensuelle',
			'app.detailaccosocindi_rev',
			'app.permisb_piecepermisb',
			'app.detaildiflog',
			'app.transmissionflux',
			'app.locvehicinsert_piecelocvehicinsert',
			'app.etatliquidatif',
			'app.detailnatmob',
			'app.partenaire',
			'app.periodeimmersion',
			'app.tiersprestataireapre',
		);

		function startCase() { Cache::clear(); clearCache(); }

	}

?>
